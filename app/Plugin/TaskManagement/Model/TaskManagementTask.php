<?php
App::uses('TaskManagementAppModel', 'TaskManagement.Model');

/**
 * Task Model
 *
 */
class TaskManagementTask extends TaskManagementAppModel {

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id,$table,$ds);

    }

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required' => true,
				'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
        'description' => array(
            'notempty' => array(
                'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required' => true,
				'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
		'end_time' => array(
			'notempty' => array(
                'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required' => true,
				'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
		),
		// Ticket is not assigned to a staff at beginning, when a staff takes it, ticket will assign to that staff
		// Move this validation to JS
// 		'assignee' => array(
// 			'numeric' => array(
// 				'rule' => array('numeric'),
// 				'message' => 'is not numeric',
// 				'allowEmpty' => false,
// 				'required' => true,
// 				'last' => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 			'notempty' => array(
// 				'rule' => array('notempty'),
// 				'message' => 'is not empty',
// 				'allowEmpty' => false,
// 				'required' => true,
// 				'last' => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Creator' => array(
			'className'  => 'User',
			'foreignKey' => 'created_by',
            'dependent'  => false
		),
		'Assignee' => array(
			'className'  => 'User',
			'foreignKey' => 'assignee',
			'dependent'  => false
		),
		'Parent' => array(
			'className'    => 'TaskManagement.TaskManagementTask',
			'foreignKey'   => 'parent_id',
			'dependent'    => false
		),
		'WebDevelopmentStage' => array(
			'className'    => 'WebDevelopment.WebDevelopmentStage',
			'foreignKey'   => 'web_development_stage_id',
			'dependent'    => false
		),
	);

    public $hasMany = array(
        'Children' => array(
    		'className'    => 'TaskManagement.TaskManagementTask',
    		'foreignKey'   => 'parent_id',
    		'dependent'    => true
	    ),
    	'TaskManagementTaskComment' => array(
    		'className'    => 'TaskManagement.TaskManagementTaskComment',
    		'foreignKey'   => 'task_management_task_id',
    		'dependent'    => true
    	),
    	'TaskManagementTaskUpload' => array(
    		'className'    => 'TaskManagement.TaskManagementTaskUpload',
    		'foreignKey'   => 'task_management_task_id',
    		'dependent'    => true
    	),
    );

/**
 * Use the same function to get first 5 tickets/tasks, and the total count. Re-use the same condition to get the count and contents
 *
 * @param string $superUserId
 * @param string $superUserGroupName
 * @param bool $countAll
 * @return mix
 */
    public function getTopFive($superUserId, $superUserGroupName, $countAll = FALSE){

    	$isClient = stristr($superUserGroupName, Configure::read('System.client.group.name'));
    	$ticketTaskType = Configure::read('TaskManagement.type.ticket');
    	$webdevTaskType = Configure::read('TaskManagement.type.webdev');

    	if($isClient === FALSE){

    		// Load working on tasks for staffs & managers
    		$query = array(
    			'conditions' => array(
    				'TaskManagementTask.assignee' => $superUserId,
    				'TaskManagementTask.type' => $webdevTaskType,
    				'NOT' => array(
	    				'TaskManagementTask.status' => Configure::read('TaskManagement.invisible-status')
	    			),
    			),
    			'recursive' => -1,
    			'order' => array('TaskManagementTask.priority ASC'),
    		);
    		if(!$countAll){
    			$query['limit'] = 5;
    			$topFiveTasks = $this->find('all', $query);
    		}else{
    			$topFiveTasks = $this->find('count', $query);
    		}
    	}

    	// Load ticket for everyone
    	$conditions = array(
    		'TaskManagementTask.type' => $ticketTaskType,
    		'NOT' => array(
    			'TaskManagementTask.status' => Configure::read('TaskManagement.invisible-status')
    		)
    	);
    	if($isClient){
    		$conditions['TaskManagementTask.created_by'] = $superUserId;
    	}else{
    		$conditions['TaskManagementTask.assignee'] = $superUserId;
    	}
    	$query = array(
    		'conditions' => $conditions,
    		'recursive' => -1,
    		'order' => array('TaskManagementTask.priority ASC')
    	);
    	if(!$countAll){
    		$query['limit'] = 5;
    		$topFive = $this->find('all', $query);
    	}else{
    		$topFive = $this->find('count', $query);
    	}

    	if(!$isClient){
    		$topFive = array(
    			$webdevTaskType => $topFiveTasks,
    			$ticketTaskType => $topFive
    		);
    	}

    	return $topFive;

    }

/**
 *
 * @param int $superUserId
 * @param string $superUserGroupName
 * @param int $webDevelopmentStageId
 * @param string $taskType
 * @param bool $returnMyTickets (This only applied to TICKET task type)
 * @return Mix
 */
    public function getTaskManagementTasks($superUserId, $superUserGroupName, $webDevelopmentStageId, $taskType, $returnMyTickets = FALSE){

    	$ticketTaskType = Configure::read('TaskManagement.type.ticket');

    	if(stristr($superUserGroupName, Configure::read('System.client.group.name')) === FALSE && $returnMyTickets === FALSE){

    		if(stristr($superUserGroupName, Configure::read('System.staff.group.name')) !== FALSE){

    			// Staff

    			if($taskType == $ticketTaskType){
    				$conditions = array(
    					'TaskManagementTask.assignee' => $superUserId,
    					'TaskManagementTask.type' => $taskType,
    					'NOT' => array(
    						'TaskManagementTask.status' => Configure::read('TaskManagement.invisible-status')
    					)
    				);
    			}else{
    				$conditions = array(
    					'TaskManagementTask.assignee' => $superUserId,
    					'TaskManagementTask.type' => $taskType,
    				);
    			}

    		}elseif(stristr($superUserGroupName, Configure::read('System.manager.group.name')) !== FALSE){

    			// Manager

    			$Group = ClassRegistry::init('Group');

    			if($taskType == $ticketTaskType){
    				$conditions = array(
    					'OR' => array(
    						'TaskManagementTask.created_by !=' => $superUserId,
    						'TaskManagementTask.assignee IS NULL',
    					),
    					'TaskManagementTask.type' => $taskType,
    					'TaskManagementTask.group_id' => $Group->getGroupId($superUserGroupName),
    					'NOT' => array(
    						'TaskManagementTask.status' => Configure::read('TaskManagement.invisible-status')
    					)
    				);
    			}else{
    				$conditions = array(
    					'TaskManagementTask.type' => $taskType,
    				);
    			}


    		}elseif(stristr($superUserGroupName, Configure::read('System.admin.group.name')) !== FALSE){

    			// Admin

    			if($taskType == $ticketTaskType){
    				$conditions = array(
    					'OR' => array(
    						'TaskManagementTask.created_by !=' => $superUserId,
    						'TaskManagementTask.assignee IS NULL',
    					),
    					'TaskManagementTask.type' => $taskType,
    					'NOT' => array(
    						'TaskManagementTask.status' => Configure::read('TaskManagement.invisible-status')
    					)
    				);
    			}else{
    				$conditions = array(
    					'TaskManagementTask.type' => $taskType,
    				);
    			}

    		}

    		if($taskType === Configure::read('TaskManagement.type.webdev')){
    			if(!empty($webDevelopmentStageId)){
    				$conditions['TaskManagementTask.web_development_stage_id'] = $webDevelopmentStageId;
    			}else{
    				return false;
    			}
    		}

    	}else{

    		// Client can only view TICKETS
    		if(!empty($webDevelopmentStageId) || $taskType !== $ticketTaskType){
    			return false;
    		}else{

    			$conditions = array(
    				'TaskManagementTask.created_by' => $superUserId,
    				'TaskManagementTask.type' => $ticketTaskType,
    			);

    		}

    	}

    	return $this->find('threaded', array(
    		'conditions' => $conditions,
    		'contain'	 => array('Creator', 'Assignee', 'TaskManagementTaskComment', 'TaskManagementTaskUpload'),
    		'order' 	 => array('TaskManagementTask.priority ASC, TaskManagementTask.end_time DESC, TaskManagementTask.created_at DESC')
    	));
    }

    public function saveTaskManagementTask($data){
    	$this->create();
    	if($this->saveAll($data, array('validate' => 'first'))){
    		return $this->id;
    	}else{
    		return false;
    	}
    }

    public function updateTaskManagementTask($id, $data){
    	$this->id = $id;
    	$this->contain();

    	if(!$this->saveAll($data, array('validate' => 'first'))){
    		return false;
    	}
    	return true;
    }

    public function updateTaskManagementTaskAssignee($id, $assignee){
    	$this->read(null, $id);
		$this->set('assignee', $assignee);
		return $this->save();
    }

    public function updateTaskManagementTaskStatus($id, $status){
    	$this->read(null, $id);
    	$this->set('status', $status);
    	if($this->save()){
    		if(in_array($status, Configure::read('TaskManagement.invisible-status'))){
    			$this->read(null, $id);
    			$this->set('progress', '100'); // Automatically set progress to 100% when the task is completed or closed.
    			return $this->save();
    		}
    		return true;
    	}
    	return false;
    }

    public function updateTaskManagementTaskDepartment($id, $department){
    	$this->read(null, $id);
    	$this->set('group_id', $department);
    	return $this->save();
    }

    public function updateTaskManagementTaskPriority($id, $priority){
    	$this->read(null, $id);
    	$this->set('priority', $priority);
    	return $this->save();
    }

    public function deleteTaskManagementTask($id){
    	if($this->delete($id, true)){
    		try{
    			$path = Configure::read('TaskManagement.aws.s3.path') .'/' .$id ."/";
    			$path = '?prefix=' .$path .'&delimiter=/';
    			$fileList = $this->amazonS3StorageManagement(Configure::read('System.aws.s3.action.list'), $path);
    			$uploadedFiles = array();
    			if(!empty($fileList) && is_array($fileList)){
    				foreach($fileList as $filename => $downloadLink){
    					$uploadedFiles[] = $filename;
    				}
    			}

    			if(!empty($uploadedFiles)){
    				$this->amazonS3StorageManagement(Configure::read('System.aws.s3.action.delete'), Configure::read('TaskManagement.aws.s3.path') ."/" .$id, $uploadedFiles);
    			}

    		}catch(AmazonS3Exception $exception){

    			// Because the data was already deleted from DB, we have to return true. Then we will report this issue and let staff to manually delete the S3 files

    			$logType 	 = Configure::read('Config.type.taskmanagement');
    			$logLevel 	 = Configure::read('System.log.level.critical');
    			$logMessage  = __('Cannot delete task related uploaded file in AWS S3, please manually delete those files. (Passed uploaded deleted task ID: ' .$id .')');
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
    			return true;
    		}
    		return true;
    	}else{
    		return false;
    	}
    }

/**
 * Get employee based on group
 */
    public function getAssigneeList($groupId){
    	if(empty($groupId)){
    		return false;
    	}
    	switch($groupId){
    		case Configure::read('EmailMarketing.manager.group.id'):
    			$groups = array(Configure::read('EmailMarketing.staff.group.id'), Configure::read('EmailMarketing.manager.group.id'));
    			break;
    		case Configure::read('WebDevelopment.manager.group.id'):
    			$groups = array(Configure::read('WebDevelopment.staff.group.id'), Configure::read('WebDevelopment.manager.group.id'));
    			break;
    		case Configure::read('Finance.manager.group.id'):
    			$groups = array(Configure::read('Finance.staff.group.id'), Configure::read('Finance.manager.group.id'));
    			break;
    		case Configure::read('LiveChat.manager.group.id'):
    			$groups = array(Configure::read('LiveChat.staff.group.id'), Configure::read('LiveChat.manager.group.id'));
    			break;
    		case Configure::read('System.admin.group.id'):
    			$groups = array(
    				Configure::read('EmailMarketing.staff.group.id'), Configure::read('EmailMarketing.manager.group.id'),
    				Configure::read('WebDevelopment.staff.group.id'), Configure::read('WebDevelopment.manager.group.id'),
    				Configure::read('Finance.staff.group.id'), Configure::read('Finance.manager.group.id'),
    				Configure::read('LiveChat.staff.group.id'), Configure::read('LiveChat.manager.group.id')
    			);
    			break;
    	}
    	if(empty($groups)){
    		return false;
    	}
    	$User = ClassRegistry::init('User');
    	$users = $User->find('all', array(
    		'conditions' => array('User.group_id' => $groups),
    		'recursive' => 0
    	));

    	$userList = array(); // Cannot use mysql function concat in query, do it manually
    	foreach($users as $u){
    		$userList[$u['User']['id']] = $u['User']['first_name'] .' ' .$u['User']['last_name'] .' (' .$u['Group']['name'] .')';
    	}

    	return $userList;
    }
}
