<?php
App::import('Vendor', 'ZMQ/zmsg');
App::uses ( 'AppShell', 'Console/Command' );
class ZmqMultithreadedShell extends AppShell {

	public $context				= null; // Unified ZMQ context (all sockets are created within this context)

	public $zmqComponents		= array('client', 'server');

	public $isRunning			= false;

	public $jobQueueAddress 	= "ipc:///tmp/job_queue.ipc";

	public $zmqServerAddress 	= "ipc:///tmp/backend.ipc";

	/*
	 * If client cannot reveive the server response after this period of time, client socket will timeout.
	* This could happen when server is down.
	*/
	public $clientRcvTimeOut	= 120; // Seconds

/**
 * The parser is used after Shell::initialize(), but before Shell::startup().
 * This means if the arguments and options are invalid, only Shell::initialize() is run.
 * @see Shell::getOptionParser()
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		return $parser;
	}

/**
 * This method runs first
 * @see Shell::initialize()
 */
	public function initialize(){
		parent::initialize();

		// Initialise global ZMQ context
		if(empty($this->context)){
			$this->context = new ZMQContext();
		}
	}

/**
 * This method runs after Shell::initialize()
 * Because the passed arguments and options can be accessed here, we do the preparation for the command.
 * @see Shell::startup()
 */
	public function startup(){
		parent::startup();
	}

/**
 * This method determines which internal function/method user want to call, and invoke it.
 */
	public function main() {
		parent::main();
	}

/**
 * Create client socket
 *
 * Note:
 * 		We can provide a new context for creating client socket in order to separate the client socket running in the main global context for certain purpose, like sending "ECHO" request
 * 		We also can give the socket an pre-defined identity.
 *
 * @return ZMQSocket
 */
	public function createClientSocket($clientType = ZMQ::SOCKET_DEALER, $newContext = null, $clientIdentity = null){
		// Create ZMQ socket
		$client  = new ZMQSocket((empty($newContext) ? $this->context : $newContext), $clientType);

		// Generate printable identity for the client
		$identity = empty($clientIdentity) ? sprintf ("%04X", rand(0, 0x10000)) : $clientIdentity;
		$client->setSockOpt(ZMQ::SOCKOPT_IDENTITY, $identity);

		//$client->setSockOpt(ZMQ::ZMQ_RCVTIMEO, $this->clientRcvTimeOut * 1000);

		//  Configure socket to not wait at close time
		$client->setSockOpt(ZMQ::SOCKOPT_LINGER, 0);

		$client->connect($this->jobQueueAddress);

		return $client;
	}

/**
 * Server frontend socket is the socket exchanging data with client socket
 * @return ZMQSocket
 */
	public function createServerFrontendSocket(){
		// Frontend socket talks to clients
		$frontend = new ZMQSocket($this->context, ZMQ::SOCKET_ROUTER);
		$frontend->bind($this->jobQueueAddress);

		return $frontend;
	}

/**
 * Create worker socket
 * @return ZMQSocket
 */
	public function createWorkerSocket(){
		// Connect worker socket to server backend
		$worker = new ZMQSocket($this->context, ZMQ::SOCKET_DEALER);

		// Generate printable identity for the worker
		$identity = sprintf ("%04X", rand(0, 0x10000));
		$worker->setSockOpt(ZMQ::SOCKOPT_IDENTITY, $identity);
		$worker->connect($this->zmqServerAddress);

		return $worker;
	}

/**
 * Forks the currently running process for ZMQ component
 */
	public function forkCurrentRunningProcessForZMQComponent($zmqCompName){
		$zmqCompName = strtolower($zmqCompName);
		if(in_array($zmqCompName, $this->zmqComponents)){
			$this->__debug(__("Fork {$zmqCompName} PID"));
			$pid = pcntl_fork();
			if ($pid == -1) {
				$this->__debug("Fork {$zmqCompName} failed.");
				$this->out(0);
				exit();
			} else if ($pid) {
				// we are the parent
				pcntl_wait($status); //Protect against Zombie children
			} else {
				// we are the child ($pid == 0)
				$this->__debug(__("ZMQ {$zmqCompName} created."));

				$this->isRunning = true; // Mark the component is running

				switch($zmqCompName){
					case "client":
						$this->__client();
						break;
					case "server":
						$this->__server();
						break;
				}

				exit();
			}
		}else{
			$this->__debug(__("ZMQ {$zmqCompName} component does not exist."));
			$this->out(0);
			exit();
		}
	}

/**
 * Close ZMQ socket
 * @param ZMQSocket $socket
 *
 * Note:
 * 		Return zero if successful
 */
	public function closeSocket($socket){
// 		return $socket->close();
		return 1; //TODO php zmq binding didn't support zmq_close() [http://au1.php.net/zmq]
	}

/**
 * Terminate ZMQ context
 * @param ZMQContext $context
 *
 * Note:
 * 		Return zero if successful
 */
	public function closeContext($context){
// 		return $context->term();
		return 1; //TODO php zmq binding didn't support zmq_term() [http://au1.php.net/zmq]
	}
}
?>