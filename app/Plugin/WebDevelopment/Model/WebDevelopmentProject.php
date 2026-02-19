<?php
App::uses('WebDevelopmentAppModel', 'WebDevelopment.Model');

/**
 * Web development project Model
 *
 */
class WebDevelopmentProject extends WebDevelopmentAppModel {

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
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasMany = array(
		'WebDevelopmentStage' => array(
			'className'    => 'WebDevelopment.WebDevelopmentStage',
			'foreignKey'   => 'web_development_project_id',
			'dependent'    => true
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		// Client, payer
		'Owner' => array(
			'className'  => 'User',
			'foreignKey' => 'project_owner',
            'dependent'  => false
		),
		// Project manager
		'Creator' => array(
			'className'  => 'User',
			'foreignKey' => 'created_by',
			'dependent'  => false
		),
	);

	public function getProjectbyId($id, $serviceAccountId, $superUserId, $superUserGroup){

		$clientGroupName 	= Configure::read('System.client.group.name');
		$managerGroupName 	= Configure::read('System.manager.group.name');
		$staffGroupname 	= Configure::read('System.staff.group.name');

		$conditions = array(
			'conditions' => array(
				'WebDevelopmentProject.id' => $id
			)
		);

		if(strstr($superUserGroup, $clientGroupName)){
			$conditions['conditions']['WebDevelopmentProject.project_owner'] = $serviceAccountId;
		}elseif(strstr($superUserGroup, $managerGroupName)){
			$conditions['conditions']['WebDevelopmentProject.created_by'] = $superUserId;
		}

		$project = $this->find('first', $conditions);

		if(!empty($project) && !empty($project['WebDevelopmentStage']) && is_array($project['WebDevelopmentStage']) && !empty($superUserId) && !empty($superUserGroup)){
			$Task = ClassRegistry::init('TaskManagement.TaskManagementTask');
			$Stage = ClassRegistry::init('WebDevelopment.WebDevelopmentStage');
			$assignedToStaff = false;
			foreach($project['WebDevelopmentStage'] as &$stage){

				$webDevelopmentStageId = $stage['id'];
				$taskType = Configure::read('TaskManagement.type.webdev');

				// Staff only can see the stage which has assigned tasks
				if (strstr($superUserGroup, $staffGroupname) && !$Stage->staffAssignedToStage($webDevelopmentStageId)){
					$stage = null;
					continue;
				}else{
					$assignedToStaff = true;
				}

				if(strstr($superUserGroup, $clientGroupName) === FALSE){
					$stage['tasks'] = $Task->getTaskManagementTasks($superUserId, $superUserGroup, $webDevelopmentStageId, $taskType);
				}

			}

			if(strstr($superUserGroup, $staffGroupname) && !$assignedToStaff){
				$project = false;
			}
		}

		return $project;
	}

	public function getProjectsByServiceUserId($serviceUserId, $userGroupName, $countAmount = FALSE){

		if(empty($serviceUserId) || !is_numeric($serviceUserId) || empty($userGroupName)){
			return false;
		}

		$conditions = array(
			'group' 	=> array('WebDevelopmentProject.id'),
			'contain' 	=> array('Owner', 'Creator')
		);

		$adminGroupName 	= Configure::read('System.admin.group.name');
		$managerGroupName 	= Configure::read('System.manager.group.name');
		$staffGroupname 	= Configure::read('System.staff.group.name');

		if(($userGroupName != $adminGroupName)){

			App::uses('CakeSession', 'Model/Datasource');

			if(strstr($userGroupName, $managerGroupName)){

				// Manager only can see the managed projects
				$conditions['conditions'] = array('created_by' => CakeSession::read('Auth.User.id'));

			}elseif (strstr($userGroupName, $staffGroupname)){

				// Staff can only see assigned projects
				$conditions['joins'] = array(
					array(
						'table' => 'web_development_stages',
						'alias' => 'WebDevelopmentStage',
						'type'  => 'inner',
						'conditions' => array(
							'WebDevelopmentProject.id = WebDevelopmentStage.web_development_project_id',
						)
					),
					array(
						'table' => 'task_management_tasks',
						'alias' => 'TaskManagementTask',
						'type'  => 'inner',
						'conditions' => array(
							'WebDevelopmentStage.id = TaskManagementTask.web_development_stage_id',
						)
					),
				);
				$conditions['conditions'] = array(
					'TaskManagementTask.assignee' => CakeSession::read('Auth.User.id')
				);

			}else{

				// Client can only see own project
				$conditions['conditions'] = array(
					'project_owner' => $serviceUserId
				);

			}

		}

		return $countAmount ? $this->find('count', $conditions) : $this->find('all', $conditions);
	}

	public function staffAssignedToProject($projectId){

		if(empty($projectId)){
			return false;
		}

		$project = $this->find('first', array(
			'conditions' => array(
				'TaskManagementTask.assignee' 	=> CakeSession::read('Auth.User.id'),
				'WebDevelopmentProject.id'		=> $projectId
			),
			'joins' => array(
				array(
					'table' => 'web_development_stages',
					'alias' => 'WebDevelopmentStage',
					'type'  => 'inner',
					'conditions' => array(
						'WebDevelopmentProject.id = WebDevelopmentStage.web_development_project_id',
					)
				),
				array(
					'table' => 'task_management_tasks',
					'alias' => 'TaskManagementTask',
					'type'  => 'inner',
					'conditions' => array(
						'WebDevelopmentStage.id = TaskManagementTask.web_development_stage_id',
					)
				),
			),
			'recursive' => -1
		));

		return !empty($project['WebDevelopmentProject']);
	}

	public function saveWebDevelopmentProject($data){
		$this->create();
		if($this->saveAll($data, array('validate' => 'first'))){
			return $this->id;
		}else{
			return false;
		}
	}

	public function updateProjectStatus($projectId, $status){
		if(empty($projectId) || empty($status)){
			return false;
		}
		$this->id = $projectId;
		return $this->saveField('status', $status, true);
	}

	public function updateWebDevelopmentProject($id, $data) {
		$this->id = $id;
		$this->contain();

		if(!$this->saveAll($data, array('validate' => 'first'))){
			return false;
		}
		return true;
	}

	public function deleteWebDevelopmentProject($id){

		$TaskUploadedFile = ClassRegistry::init('TaskManagement.TaskManagementTaskUpload');

		$projectStages = $this->browseBy('id', $id, array('WebDevelopmentStage' => array('TaskManagementTask' => array('TaskManagementTaskUpload'))));

		if(!empty($projectStages['WebDevelopmentStage']) && is_array($projectStages['WebDevelopmentStage'])){
			foreach($projectStages['WebDevelopmentStage'] as $stage){
				if(!empty($stage['TaskManagementTask']) && is_array($stage['TaskManagementTask'])){
					foreach($stage['TaskManagementTask'] as $task){
						if(!empty($task['TaskManagementTaskUpload']) && is_array($task['TaskManagementTaskUpload'])){
							$TaskUploadedFile->deleteUploadedFileInS3($task['TaskManagementTaskUpload']);
						}
					}
				}
			}
		}

		return $this->delete($id, true);
	}
}
