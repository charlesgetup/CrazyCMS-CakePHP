<?php
/**
 * Component for working with ZMQ which provides parallel processing.
 */
App::import('Vendor', 'ZMQ/zmsg');
App::uses('Component', 'Controller');
class ZMQMultithreadedServiceComponent extends Component {

	protected $JobQueue;

/**
 * Called automatically after controller beforeFilter
 * Stores refernece to controller object
 * Merges Settings.history array in $config with default settings
 *
 * @param object $controller
 */
	public function startup(Controller $controller) {
		$this->Controller 	= $controller;
		$this->JobQueue		= ClassRegistry::init("JobQueue");
	}

/**
 * Create a job and add it to the queue
 * @param string $jobTaskFunc
 * @param array $jobTaskFuncParams
 */
	public function addJob ($jobType, $userId, $excutionTime, $jobTaskFunc = null, array $jobTaskFuncParams = null) {
		if(!empty($jobTaskFunc) && !empty($jobType) && !empty($userId) && !empty($excutionTime)){
			$job = array(
				'user_id'			=> $userId,
				'type'				=> $jobType,
				'function' 			=> serialize($jobTaskFunc),
				'function_params' 	=> serialize($jobTaskFuncParams),
				'status'			=> 'PENDING',
				'excution_time'		=> $excutionTime,
				'created'			=> date('Y-m-d H:i:s')
			);
			return $this->JobQueue->saveJob($job);
		}
		return false;
	}

}