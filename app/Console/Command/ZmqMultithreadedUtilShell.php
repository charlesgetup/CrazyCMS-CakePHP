<?php
App::import('Vendor', 'ZMQ/zmsg');
App::uses ( 'ZmqMultithreadedShell', 'Console/Command' );
class ZmqMultithreadedUtilShell extends ZmqMultithreadedShell {

	public $action 				= null; // Which action user want to perform (action name => function/method name)

	public $actionParams 		= array(); // Parameters function/method needs

/**
 * The parser is used after Shell::initialize(), but before Shell::startup().
 * This means if the arguments and options are invalid, only Shell::initialize() is run.
 * @see Shell::getOptionParser()
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addArgument('action', array(
        	'help' => 'Action (function/method) name in CamelCase.'
		))->addOption('params', array(
			'short' 	=> 'p',
			'help' 		=> __('Function/Method params. If more than one, use comma separate them and no space between them'),
			'default'	=> ''
		))->description(
			__('Run ZMQ Multithreaded Server related functionalities.')
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

		$this->action 				= $this->args[0];
		$this->actionParams 		= $this->params['params'];
	}

/**
 * This method determines which internal function/method user want to call, and invoke it.
 */
	public function main() {
		parent::main();

		$this->action = "__" .lcfirst($this->action);

		if(method_exists($this, $this->action)){
			$this->actionParams = explode(",", $this->actionParams);
			$returnVal = call_user_func_array(array($this, $this->action), $this->actionParams);
			$this->out($returnVal);
			exit();
		}else{
			$this->out(
				'<error>The action does not exist.</error>'
			);
		}
		$this->out(0);
	}

/**
 * Start ZMQ client
 */
	private function __startClient(){
		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ client is going to be started."));
		$this->__debug(__("=================================="));

		$isClientRunning = 0; // 0: not running; other value: maybe running
		do{
			$isClientRunning = system(ROOT .DS .APP_DIR .DS ."Console" .DS  ."cake zmq_multithreaded_client --max_parallel_threads 10 --job_fetch_interval 0 --poll_fetch_interval 1 --max_fetch_amount 2 --max_idel_time 300 --user_id $this->superUserId --debug 1 --debug_output_method error_log");
			$isClientRunning = is_numeric($isClientRunning) ? intval($isClientRunning) : 1;
		}while ($isClientRunning == 0);

		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ client is started."));
		$this->__debug(__("=================================="));

		return $isClientRunning;
	}

/**
 * Start ZMQ server
 */
	private function __startServer(){
		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ server is going to be started."));
		$this->__debug(__("=================================="));

		$isServerRunning = 0; // 0: not running; other value: maybe running
		do{
			$isServerRunning = system(ROOT .DS .APP_DIR .DS ."Console" .DS  ."cake zmq_multithreaded_server --max_worker_amount 5 --user_id $this->superUserId --debug 1 --debug_output_method error_log");
			$isServerRunning = is_numeric($isServerRunning) ? intval($isServerRunning) : 1;
		}while ($isServerRunning == 0);

		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ server is started."));
		$this->__debug(__("=================================="));

		return $isServerRunning;
	}

/**
 * Start ZMQ server & client
 */
	private function __startAll(){
		$this->__startClient();
		$this->__startServer();
	}



	// Rewrite the following status check

/**
 * Get client status: 0 (Stopped), 1 (Running)
 *
 * Note:
 * 		We use "echo" request to check whether the client is running or not
 */
	private function __getClientStatus(){
		$context 		= new ZMQContext();
// 		$client 		= $this->createClientSocket(ZMQ::SOCKET_REQ, $context, 'ZMQ_ECHO');
		$client 		= $this->createClientSocket();
		$client->connect($this->jobQueueAddress);
		return $this->__checkZMQStatus($context, $client);
	}

/**
 * Get server status
 */
	private function __getServerStatus(){
		$context 		= new ZMQContext();
		$client 		= $this->createClientSocket(ZMQ::SOCKET_REQ, $context, 'ZMQ_ECHO');
		$client->connect($this->jobQueueAddress);
		return $this->__checkZMQStatus($context, $client);
	}

/**
 * Get client status: 0 (Stopped), 1 (Running), -1 (Internal Error)
 * @param ZMQContext $context
 * @param ZMQSocket $client
 * @return number
 */
	private function __checkZMQStatus($context, $client){
		if(empty($context) || empty($client)){
			return -1;
		}

		$this->__debug("======== get status==========");

		$retriesLeft 	= 3;
		$requestTimeout = 2500;
		$read = $write 	= array();

		$status			= 0;

		$sendingPoint	= 1;
		$receivingPoint	= 1;

		try {
			while ($retriesLeft) {
				//  We send a request, then we work to get a reply
				$sendingPoint = time();
				$client->send('ECHO');

				$this->__debug("try: $retriesLeft");

				$expectReply = true;
				while ($expectReply) {
					//  Poll socket for a reply, with timeout
					$poll = new ZMQPoll();
					$poll->add($client, ZMQ::POLL_IN);
					$events = $poll->poll($read, $write, $requestTimeout);

					//  If we got a reply, process it
					if ($events > 0) {
						//  We got a reply from the server, must match sequence
						$reply 			= $client->recv();
						$receivingPoint = time();

						$this->__debug("Can receive something: " .$reply);

						// Get returned timestamp (Format: ECHO-xxxxxxx)
						// If the timestamp is between the sending point and receiving point, it is good; if not, it will be a malformed reply.
						$timestamp = explode("-", strval($reply));
						$timestamp = count($timestamp) == 2 ? intval($timestamp[1]) : 0;

						if ($timestamp > $sendingPoint && $timestamp < $receivingPoint) {
							$this->__debug(__("ZMQ replied OK ") ."($reply)" .PHP_EOL);
							$status 	 = 1;
							$retriesLeft = 0;
							$expectReply = false;
						} else {
							$this->__debug(__("Malformed reply from ZMQ: ") .$reply .PHP_EOL);
						}

					} elseif (--$retriesLeft == 0) {
						$this->__debug(__("ZMQ seems to be offline, abandoning") .PHP_EOL);
						break;
					} else {
						$this->__debug(__("No response from ZMQ, retrying...") .PHP_EOL);
						//  Old socket will be confused; close it and open a new one
						$client = $this->createClientSocket(ZMQ::SOCKET_REQ, $context, 'ZMQ_ECHO');
						//  Send request again, on new socket
						$sendingPoint = time();
						$client->send('ECHO');

						$this->__debug("try: $retriesLeft");
					}
				}
			}
		} catch (Exception $e) {
			$this->__debug($e->getTraceAsString() .PHP_EOL);
			return -1;
		}

		// Close ZMQ context
		$this->closeContext($context);

		return $status;
	}





/**
 * Shut the client down
 */
	private function __shutdownClient(){
		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ client is going to be shut down."));
		$this->__debug(__("=================================="));

		$frontend = $this->createServerFrontendSocket();
		$this->__debug(__("Send terminate singal to client.")); //TODO this may send signal to all clients. Double check this.
		$zmsg = new Zmsg($frontend);
		$zmsg->body_fmt("TERMINATE")->send();

		// Close the trigger socket
		$this->closeSocket($frontend);

		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ client is shut down."));
		$this->__debug(__("=================================="));

		return 1;
	}

/**
 * Shut the server down
 */
	private function __shutdownServer(){
		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ server is going to be shut down."));
		$this->__debug(__("=================================="));

		$client = $this->createClientSocket();
		$this->__debug(__("Send terminate singal to server."));
		$zmsg = new Zmsg($client);
		$zmsg->body_fmt("TERMINATE")->send();

		// Close the trigger socket
		$this->closeSocket($client);

		$this->__debug(__("=================================="));
		$this->__debug(__("ZMQ server is shut down."));
		$this->__debug(__("=================================="));

		return 1;
	}

/**
 * Shutdown ZMQ server & client
 */
	private function __shutdownAll(){
		$this->__shutdownClient();
		$this->__shutdownServer();

		// Close ZMQ context
		$this->closeContext($this->context);

		return 1;
	}

/**
 * Output ZMQ library info
 */
	private function __zmqInfo(){
// 		zmq_version (&major, &minor, &patch); printf ("Current ØMQ version is %d.%d.%d\n", major, minor, patch);
	}
}
?>