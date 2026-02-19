<?php
App::uses('AppModel', 'Model');
class JobQueue extends AppModel {

	public $useTable = 'job_queue';

	public $actsAs = array('Containable');

    public $validate = array(
    	'user_id' => array(
    		'numeric' => array(
    			'rule' => array('numeric'),
    			//'message' => 'Your custom message here',
    			'allowEmpty' => true,
    			'required'   => false,
    			'last'       => false, // Stop validation after this rule
    			'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
        'type' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            	'message' => 'is not empty',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    	'excution_time' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => false,
    			'required'   => true,
    			'last'       => false, // Stop validation after this rule
    			'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	)
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
    	'User' => array(
    		'className'     => 'User',
    		'foreignKey'    => 'user_id',
    		'dependent'     => false
    	)
    );

    public function getJob($jobCreatedTime, $type = null, $userId = null, $excutionTime = "NOW", $timeStampDirection = "AFTER"){
    	$conditions = $this->__queryCondition('PENDING', $userId, $type, $excutionTime, $jobCreatedTime, $timeStampDirection);

    	$job = $this->find('first', array(
    		'conditions' => $conditions,
    		'order'		 => array('JobQueue.id' => 'ASC') // Explicitly obey "First In First Out" rule
    	));

    	if(isset($job['JobQueue']) && !empty($job['JobQueue'])){

			// When a job is fetched, change its status and make sure it won't be fetched again
			$job['JobQueue']['status'] = 'PROCESSING';
			$job = array('JobQueue' => $job['JobQueue']);
			if($this->updateJob($job['JobQueue']['id'], $job)){
				return $job['JobQueue'];
			}else{

				$logType 	 = Configure::read('Config.type.system');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("Job fetch failed. (user ID: {$userId}, type: {$type}, excution time: {$excutionTime})");
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				return null;
			}

		}else{
			return null;
		}
    }

    public function countPendingJobs($jobCreatedTime, $type = null, $userId = null, $excutionTime = "NOW", $timeStampDirection = "AFTER"){
    	$conditions = $this->__queryCondition('PENDING', $userId, $type, $excutionTime, $jobCreatedTime, $timeStampDirection);

    	$count = $this->find('count', array(
    		'conditions' => $conditions
    	));

    	return $count;
    }

    public function countUndoneJobs($jobCreatedTime, $type = null, $userId = null, $excutionTime = "NOW", $timeStampDirection = "AFTER"){
    	$conditions = $this->__queryCondition('PROCESSING', $userId, $type, $excutionTime, $jobCreatedTime, $timeStampDirection);

    	$jobs = $this->find('all', array(
    		'conditions' => $conditions,
    	));

    	foreach($jobs as $job){
    		if($job['JobQueue']['status'] == "PROCESSING"){
    			$this->read(null, $job['JobQueue']['id']);
    			$this->set('status', 'PENDING');
    			$this->save();
    		}
    	}

    	return count($jobs);
    }

    public function countProcessedJobs($jobCreatedTime, $type = null, $userId = null, $excutionTime = "NOW", $timeStampDirection = "AFTER"){
    	$conditions = $this->__queryCondition('DONE', $userId, $type, $excutionTime, $jobCreatedTime, $timeStampDirection);

    	$count = $this->find('count', array(
    		'conditions' => $conditions
    	));

    	return $count;
    }

    public function saveJob($data){
    	$this->create();
        $result = $this->saveAll($data , array('validate' => 'first'));

        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function updateJob($id, $data) {
        $this->id = $id;
        $this->contain();
        return $this->saveAll($data, array('validate' => 'first'));
    }

    public function prepareScheduledPendingJobsForExecution($jobCreatedTime, $type = null, $userId = null, $excutionTime = "NOW", $timeStampDirection = "AFTER"){
    	$conditions = $this->__queryCondition('PENDING', $userId, $type, $excutionTime, $jobCreatedTime, $timeStampDirection);

    	return $this->updateAll(
    		array(
    			'JobQueue.excution_time' => "'NOW'"
    		),
    		$conditions
    	);
    }

    private function __queryCondition($jobStatus, $userId, $type, $excutionTime, $jobCreatedTime, $timeStampDirection){
    	$conditions = array(
    		'JobQueue.status' 	=> $jobStatus
    	);
		switch($timeStampDirection){
			case "BEFORE":
				$operator = ">=";
				break;
			case "AFTER":
				$operator = "<=";
				break;
			default:
				$operator = "<=";
				break;
		}
    	switch($jobStatus){
    		case "PENDING":
    			$conditions = array_merge($conditions, array('JobQueue.created ' .$operator => $jobCreatedTime));
    			break;
    		case "PROCESSING":
    			$conditions = array('OR' => array(
	    			'JobQueue.status = "PROCESSING"',
	    			'JobQueue.status = "PENDING"'
    			));
    			$conditions = array_merge($conditions, array('JobQueue.created ' .$operator => $jobCreatedTime));
    			break;
    		case "DONE":
    			$conditions = array_merge($conditions, array('JobQueue.finished ' .$operator => $jobCreatedTime));
    			break;
    	}
    	if(!empty($userId)){
    		$conditions = array_merge($conditions, array('JobQueue.user_id' => $userId));
    	}
    	if(!empty($type)){
    		$conditions = array_merge($conditions, array('JobQueue.type' => $type));
    	}
    	if(!empty($excutionTime)){
    		$conditions = array_merge($conditions, array('JobQueue.excution_time' => $excutionTime));
    	}
    	return $conditions;
    }
}
?>
