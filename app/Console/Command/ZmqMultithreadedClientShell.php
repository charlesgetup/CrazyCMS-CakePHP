<?php
App::import('Vendor', 'ZMQ/zmsg');
App::uses ( 'ZmqMultithreadedShell', 'Console/Command' );
class ZmqMultithreadedClientShell extends ZmqMultithreadedShell {

	public $uses = array('JobQueue');

	public $maxParallelProcessAmount = 10; // How many thread can be processd in parallel

	public $maxFetchingAmount 		 = 10;

	public $pollFetchInteval		 = 1; // Seconds (If socket cannot get response during this period, request will be timeout)

	public $jobFetchInteval			 = 1; // Seconds

	public $serverWakeUpReTryTimes	 = 10;

	/*
	 * Max client idel time.
	 * After all jobs have been done, client will continue wait for some time.
	 * And during this period, if there are some jobs coming in, the waiting time flag will be cleared and start to do the jobs,
	 * and if after this max idel time no jobs come in, then shut down the workers, server and client.
	 *
	 * Do this to save time about starting and closing down the ZMQ server/workers
	 */
	public $maxIdelTime				 = 300; // Seconds

	protected  $isServerRunning		 = false; // Mark whether the server is running or not

/**
 * The parser is used after Shell::initialize(), but before Shell::startup().
 * This means if the arguments and options are invalid, only Shell::initialize() is run.
 * @see Shell::getOptionParser()
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addOption('max_parallel_threads', array(
			'short' 	=> 't',
			'help' 		=> __('Max parallel process jobs amount.'),
			'default'	=> 10
		))->addOption('job_fetch_interval', array(
			'short' 	=> 'j',
			'help' 		=> __('Poll new job from queue interval in seconds.'),
			'default'	=> 1
		))->addOption('poll_fetch_interval', array(
			'short' 	=> 'p',
			'help' 		=> __('Poll finished job from server interval in seconds.'),
			'default'	=> 1
		))->addOption('max_fetch_amount', array(
			'short' 	=> 'f',
			'help' 		=> __('Max jobs fetched from queue at one time.'),
			'default'	=> 10
		))->addOption('max_idel_time', array(
			'short' 	=> 'i',
			'help' 		=> __('Max idel time in seconds.'),
			'default'	=> 300
		))->description(
			__('Get jobs from queue and send them to the processing server at backend in a multithreaded way.')
		);

		return $parser;
	}

/**
 * This method runs first
 * @see Shell::initialize()
 */
	public function initialize(){
		parent::initialize();
	}

/**
 * This method runs after Shell::initialize()
 * Because the passed arguments and options can be accessed here, we do the preparation for the command.
 * @see Shell::startup()
 */
	public function startup(){
		parent::startup();

		$this->maxParallelProcessAmount = $this->params['max_parallel_threads'];
		$this->maxFetchingAmount		= $this->params['max_fetch_amount'];
		$this->pollFetchInteval			= $this->params['poll_fetch_interval'];
		$this->jobFetchInteval			= $this->params['job_fetch_interval'];
		$this->maxIdelTime				= $this->params['max_idel_time'];
	}

/**
 * This method runs after Shell::startup()
 * This method defines the main logic, and call different tasks or proviate method to do the actual job.
 */
	public function main() {
		parent::main();

		$zmqCompName = "client";
		$this->forkCurrentRunningProcessForZMQComponent($zmqCompName);

		exit(); // Make sure the shell command is over
	}

/**
 * ZMQ client which discovers the jobs in the queue and sends jobs to ZMQ server
 */
	protected function __client (){

		// Create ZMQ client
		$client = $this->createClientSocket();

		// Create client side poll
		$read = $write = array();
		$poll = new ZMQPoll();
		$poll->add($client, ZMQ::POLL_IN);

		// Send jobs to server
		$this->__debug(__("Client starts fetching jobs from queue and sending them to server."));
		$parallelJobCount = 0;
		$idelTime = 0;
		$serverWakeUpTime = 0;
		$updateConfiguration = false;
		while($this->isRunning){

			// Update system configuration flag and tell the system the ZMQ is running
			if(!$updateConfiguration){
				$this->__updateConfiguration(1);
			}

			sleep($this->jobFetchInteval);

			$this->__debug(__("Client is discovering jobs from queue."));

			// Keep parallel processing job amount under the max amount
			$totalJobAmount = $this->JobQueue->countPendingJobs(date("Y-m-d H:i:s"));
			if($totalJobAmount > 0 && $parallelJobCount < $this->maxParallelProcessAmount){
				$idelTime = 0; // Clear the idel flag when new jobs come in

				// Check whether the server is running or not. If not running, start the server.
				if($this->isServerRunning == false){

					if($serverWakeUpTime < $this->serverWakeUpReTryTimes){

						$serverWakeUpTime += 1;
						$this->__debug(__("Client send WAKEUP message to server side ( $serverWakeUpTime time(s) )."));
						$zmsg = new Zmsg($client);
						$zmsg->body_fmt("WAKEUP")->send();

					}else{
						// If client send detact message over MAX times and still cannot receive anything, this mean the server is down.
						// Force start the server
						$this->__debug(__("Force start server after $serverWakeUpTime times detaction."));
						$zmqMaxWorkerAmount 	= $this->_getSystemDefaultConfigSetting('ZMQMaxWorkerAmount', Configure::read('Config.type.system'));
						$zmqDebug 				= $this->_getSystemDefaultConfigSetting('ZMQDebug', Configure::read('Config.type.system'));
						$zmqDebugOutputMethod 	= $this->_getSystemDefaultConfigSetting('ZMQDebugOutputMethod', Configure::read('Config.type.system'));
						$startServerCommand	 = "zmq_multithreaded_server --max_worker_amount $zmqMaxWorkerAmount --user_id $this->superUserId --debug $zmqDebug --debug_output_method $zmqDebugOutputMethod";
						passthru(APP ."Console" . DS ."cake " .$startServerCommand ." > /dev/null 2>&1 &");
						$serverWakeUpTime = 0; // Reset the wake up times and keep checking the server status
					}

				}else{

					$maxAddAmount = ($totalJobAmount > $this->maxFetchingAmount) ? $this->maxFetchingAmount : $totalJobAmount;
					$allowAddAmount = $this->maxParallelProcessAmount - $parallelJobCount;
					$addAmount = ($maxAddAmount > $allowAddAmount) ? $allowAddAmount : $maxAddAmount;
					$this->__debug(__("{$totalJobAmount} jobs in the queue and {$addAmount} jobs are going to be processed."));
					for($i = 0; $i < $addAmount; $i++){
						$registerJob = $this->JobQueue->getJob(date("Y-m-d H:i:s")); // Acturally get job here
						$job = array(
								'job_id'			=> $registerJob['id'],
								'function' 			=> $registerJob['function'],
								'function_params'	=> $registerJob['function_params']
						);
						$job  = serialize($job);
						$zmsg = new Zmsg($client);
						$zmsg->body_fmt($job)->send();
						$this->__debug(__("Client job [{$registerJob['id']}] sent."));
						$parallelJobCount++;
					}

				}

			}elseif ($totalJobAmount <= 0){

				$serverWakeUpTime = 0; // Clear the server wake up times when client has no job to do
				if($idelTime <= 0){ $idelTime = time(); } // Record the idel start timestamp
				if((time() - $idelTime) >= $this->maxIdelTime){
					$this->__debug(__("Client send detact ECHO message to server side."));
					$zmsg = new Zmsg($client);
					$zmsg->body_fmt("ECHO")->send();
					// If client cannot receive the server ECHO response in one min, this means the server may be down already, then directly shutdown the client
					if((time() - $idelTime) >= ($this->maxIdelTime + 60)){
						$this->__debug(__("Client is shutting down."));
						$this->isRunning = false;
					}
				}

			}

			// Check which jobs are done
			$events = $poll->poll($read, $write, $this->pollFetchInteval);
			if ($events) {
				$zmsg = new Zmsg($client);
				$zmsg->recv();
				switch($zmsg->body()){
					case "JOB_DONE":
						$this->__debug(__("Client confirmed that the job is done."));
						$parallelJobCount--;
						break;
					case "JOB_FAILED":
						$this->__debug(__("Client confirmed that the job is failed. Re-queue this job."));
						$parallelJobCount--;
						break;
					case "ECHO":
						$this->__debug(__("Client receive server side ECHO response."));
						$this->__debug(__("Client send TERMINATE message to the server."));
						$zmsg->body_fmt("TERMINATE")->send();
						// Kill client
						$this->__debug(__("Client is shutting down."));
						$this->isRunning = false;
						break;
					case "AWAKE":
						$this->__debug(__("Client receive server side AWAKE response."));
						$this->isServerRunning = true;
						break;
					case "TERMINATE":
						// Kill client
						$this->__debug(__("Client is shutting down."));
						$this->isRunning = false;
						break;
				}
			}
		}

		// Close opened socket
		$this->__updateConfiguration(0);
		$this->__debug(__("Client has shut down."));
		$this->closeSocket($client);
	}

	private function __updateConfiguration($configurationValue){
		if($configurationValue === 0 || $configurationValue === 1){
			$configuration = $this->_getSystemDefaultConfigSetting('ZMQRunning', Configure::read('Config.type.system'), null, true);
			if(!empty($configuration)){
				$configuration['value'] = $configurationValue;
				if(isset($configuration['modified_by_name'])){ unset($configuration['modified_by_name']); }
				$this->Configuration->updateConfiguration($configuration['id'], array('Configuration' => $configuration));
				$updateConfiguration = true;
			}
		}
	}
}
?>