<?php
App::import('Vendor', 'ZMQ/zmsg');
App::uses ( 'ZmqMultithreadedShell', 'Console/Command' );
class ZmqMultithreadedServerShell extends ZmqMultithreadedShell {

	public $uses = array('JobQueue');

	public $maxWorkerAmount  = 5; // How many workers alive in the ZMQ server

/**
 * The parser is used after Shell::initialize(), but before Shell::startup().
 * This means if the arguments and options are invalid, only Shell::initialize() is run.
 * @see Shell::getOptionParser()
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addOption('max_worker_amount', array(
			'short' 	=> 'w',
			'help' 		=> __('Max ZMQ server alive workers.'),
			'default'	=> 5
		))->description(
			__('Process jobs at backend in a multithreaded way.')
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

		$this->maxWorkerAmount 			= $this->params['max_worker_amount'];
	}

/**
 * This method runs after Shell::startup()
 * This method defines the main logic, and call different tasks or proviate method to do the actual job.
 */
	public function main() {
		parent::main();

		$zmqCompName = "server";
		$this->forkCurrentRunningProcessForZMQComponent($zmqCompName);

		exit(); // Make sure the shell command is over
	}

/**
 * ZMQ server
 */
	protected function __server (){

		// Launch pool of workers
		$this->__debug(__("Start created {$this->maxWorkerAmount} workers"));
		for ($threadNbr = 0; $threadNbr < $this->maxWorkerAmount; $threadNbr++) {
			$pid = pcntl_fork();
			if ($pid == 0) {
				$this->__debug(__("Worker ({$threadNbr}) is ready."));
				$this->__worker($threadNbr);
				exit();
			}
		}

		// Frontend socket talks to clients
		$frontend = new ZMQSocket($this->context, ZMQ::SOCKET_ROUTER);
		$frontend->bind($this->jobQueueAddress);

		// Backend socket talks to workers
		$backend = new ZMQSocket($this->context, ZMQ::SOCKET_DEALER);
		$backend->bind($this->zmqServerAddress);

		//  Connect backend to frontend via a queue device
		//  We could do this:
		//      $device = new ZMQDevice($frontend, $backend);
		//  But doing it ourselves means we can debug this more easily

		$read = $write = array();
		$stopedWorkerCount = 0;
		$this->__debug(__("Server starts receiving jobs from client."));

		while ($this->isRunning) {

			// Create server side poll
			$poll = new ZMQPoll();
			$poll->add($frontend, ZMQ::POLL_IN);
			$poll->add($backend, ZMQ::POLL_IN);
			$poll->poll($read, $write, -1); // Server is always running until the client side send termination signal

			// Handle poll thread
			foreach ($read as $socket) {
				$zmsg = new Zmsg($socket);
				$zmsg->recv();
				if ($socket === $frontend) {
					if($zmsg->body() == "TERMINATE"){
						if($stopedWorkerCount < $this->maxWorkerAmount){
							$remainAliveWorkers = $this->maxWorkerAmount - $stopedWorkerCount;
							$this->__debug(__("There are {$remainAliveWorkers} workers alive."));
							$zmsg->set_socket($backend)->body_fmt("SHUTDOWN_WORKER")->send();
							$this->__debug(__("Trigger worker stop"));
						}
					}else{
						$this->__debug(__("Client job received at front end."));

						if($zmsg->body() == "ECHO"){
							$this->__debug(__("Server frontend receive ECHO detect message."));
						}else if($zmsg->body() == "WAKEUP"){
							$this->__debug(__("Server frontend receive WAKEUP message."));
						}

						$zmsg->set_socket($backend)->send();
					}
				} elseif ($socket === $backend) {
					if($zmsg->body() == "WORKER_STOPPED"){
						$stopedWorkerCount++;
						if($stopedWorkerCount < $this->maxWorkerAmount){
							$remainAliveWorkers = $this->maxWorkerAmount - $stopedWorkerCount;
							$this->__debug(__("There are still {$remainAliveWorkers} workers alive."));
							$zmsg->body_fmt("SHUTDOWN_WORKER")->send();
							$this->__debug(__("Trigger worker stop"));
						}
					}else if($zmsg->body() == "ECHO"){
						// Immdieately return "echo" response
						$this->__debug(__("Server backend send the ECHO response back to the client."));
						$zmsg->set_socket($frontend)->body_fmt("ECHO")->send();
					}else if($zmsg->body() == "AWAKE"){
						// Immdieately return "awake" response
						$this->__debug(__("Server backend send the AWAKE response back to the client."));
						$zmsg->set_socket($frontend)->body_fmt("AWAKE")->send();
					}else{
						if($zmsg->body()){
							$this->__debug(__("Client job is done and return to back end from worker."));
							$zmsg->set_socket($frontend)->body_fmt("JOB_DONE")->send();
						}else{
							$this->__debug(__("Client job is failed and return to back end from worker."));
							$zmsg->set_socket($frontend)->body_fmt("JOB_FAILED")->send();
						}
					}
				}
			}

			// If all workers are stopped, shutdown the server
			if($stopedWorkerCount >= $this->maxWorkerAmount){
				$this->__debug(__("All workers are stopped, stop the server now."));
				$this->isRunning = false;
				break;
			}
		}

		// Close opened socket
		$this->closeSocket($frontend);
		$this->closeSocket($backend);
	}

/**
 * ZMQ worker
 * @param int $threadNbr
 */
	private function __worker ($threadNbr){

		// Create ZMQ worker socket
		$worker = $this->createWorkerSocket();
		$zmsg = new Zmsg($worker);

		// Do job
		$waitingForJob = true;
		while ($waitingForJob) {

			// The DEALER socket gives us the address envelope and message
			$zmsg->recv();

			$this->__debug(__("Worker ({$threadNbr}) received {$zmsg->parts()} part(s)."));

			if($zmsg->body() == "SHUTDOWN_WORKER"){

				$waitingForJob = false;
				$this->__debug(__("Worker ($threadNbr) stopped"));
				$zmsg->body_fmt("WORKER_STOPPED")->send();

			}else if($zmsg->body() == "ECHO"){

				$zmsg->body_fmt("ECHO")->send();
				$this->__debug(__("Worker has receive the detect ECHO message, and response has been sent to the server backend"));

			}else if($zmsg->body() == "WAKEUP"){

				$zmsg->body_fmt("AWAKE")->send();
				$this->__debug(__("Worker has receive the WAKEUP message, and response has been sent to the server backend"));

			}else{

				// Do job here
				$jobDoneResult = null;
				$job = unserialize($zmsg->body());
				if(isset($job['function']) && !empty($job['function']) && isset($job['function_params']) && !empty($job['function_params'])){
					$jobId					= $job['job_id'];
					try{
						$threadTaskFunc 		= unserialize($job['function']);
						$threadTaskFuncParams 	= unserialize($job['function_params']);
						if(is_array($threadTaskFuncParams)){
							$this->__debug(__("Worker ({$threadNbr}) starts to do job."));
							$jobDoneResult = call_user_func_array($threadTaskFunc, $threadTaskFuncParams);

							$processedJob = $this->JobQueue->browseBy ( $this->JobQueue->primaryKey, $jobId, false);
							$processedJob['JobQueue']['status'] 	= 'DONE';
							$processedJob['JobQueue']['finished'] 	= date ( 'Y-m-d H:i:s', time () );
							$this->JobQueue->updateJob($processedJob['JobQueue']['id'], $processedJob);
						}else{
							throw Exception(__("Job function parameters are in wrong format."));
						}
					}catch(Exception $e){
						$processedJob = $this->JobQueue->browseBy ( $this->JobQueue->primaryKey, $jobId, false);
						$processedJob['JobQueue']['status'] 	= 'FAILED';
						$this->JobQueue->updateJob($processedJob['JobQueue']['id'], $processedJob);
					}
				}

				$this->__debug(__("Worker ({$threadNbr}) has finished the job. Send response to back end."));
				$zmsg->body_fmt($jobDoneResult)->send(false);
			}
		}
	}
}
?>