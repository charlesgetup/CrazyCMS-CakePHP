<?php
App::uses('WebDevelopmentAppModel', 'WebDevelopment.Model');

/**
 * Web development stage Model
 *
 */
class WebDevelopmentStage extends WebDevelopmentAppModel {

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
		'start_time' => array(
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $hasMany = array(
		'TaskManagementTask' => array(
			'className'    => 'TaskManagement.TaskManagementTask',
			'foreignKey'   => 'web_development_stage_id',
			'dependent'    => true
		),
	);

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
		'WebDevelopmentProject' => array(
			'className'    => 'WebDevelopment.WebDevelopmentProject',
			'foreignKey'   => 'web_development_project_id',
			'dependent'    => false
		),
		'PaymentInvoice' => array(
			'className'    => 'Payment.PaymentInvoice',
			'foreignKey'   => 'payment_invoice_id',
			'dependent'    => true
		)
	);

	public function getStageByProjectId($id){
		return $this->find('all', array(
			'conditions' => array(
				'web_development_project_id' => $id
			),
			'contain' => false
		));
	}

	public function saveWebDevelopmentStage($data){
		$this->create();
		if($this->saveAll($data, array('validate' => 'first'))){
			return $this->id;
		}else{
			return false;
		}
	}

	public function updateWebDevelopmentStage($id, $data) {
		$this->id = $id;
		$this->contain();

		if(!$this->saveAll($data, array('validate' => 'first'))){
			return false;
		}
		return true;
	}

	public function deleteWebDevelopmentStage($id){

		$TaskUploadedFile = ClassRegistry::init('TaskManagement.TaskManagementTaskUpload');

		$projectStageTasks = $this->browseBy('id', $id, array('TaskManagementTask' => array('TaskManagementTaskUpload')));

		if(!empty($projectStageTasks['TaskManagementTask']) && is_array($projectStageTasks['TaskManagementTask'])){
			foreach($projectStageTasks['TaskManagementTask'] as $task){
				if(!empty($task['TaskManagementTaskUpload']) && is_array($task['TaskManagementTaskUpload'])){
					$TaskUploadedFile->deleteUploadedFileInS3($task['TaskManagementTaskUpload']);
				}
			}
		}

		return $this->delete($id, true);
	}

	public function staffAssignedToStage($stageId, $taskId = null){

		if(empty($stageId)){
			return false;
		}

		$conditions = array(
			'OR' 						=> array(
				'TaskManagementTask.created_by' => CakeSession::read('Auth.User.id'),
				'TaskManagementTask.assignee' 	=> CakeSession::read('Auth.User.id')
			),
			'WebDevelopmentStage.id'	=> $stageId
		);

		if(!empty($taskId)){

			if(!is_numeric($taskId)){
				return false;
			}

			$conditions['TaskManagementTask.id'] = $taskId;
		}

		$stage = $this->find('first', array(
			'conditions' => $conditions,
			'joins' => array(
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

		return !empty($stage['WebDevelopmentStage']);
	}

	public function managerOwnThisStage($stageId){

		// Logic: If the manager is project manager (own this project), then the manager own all the stages under the project.

		$stage = $this->find('first', array(
			'conditions' => array(
				'WebDevelopmentProject.created_by' 	=> CakeSession::read('Auth.User.id'),
				'WebDevelopmentStage.id'			=> $stageId
			),
			'joins' => array(
				array(
					'table' => 'web_development_projects',
					'alias' => 'WebDevelopmentProject',
					'type'  => 'inner',
					'conditions' => array(
						'WebDevelopmentStage.web_development_project_id = WebDevelopmentProject.id',
					)
				),
			),
			'recursive' => -1
		));

		return !empty($stage['WebDevelopmentStage']);
	}

	public function addInvoice($stageId, $invoiceId, $projectOwner, $purchaseCode){

		$stage = $this->find('first', array(
			'conditions' => array(
				'WebDevelopmentStage.id' => $stageId,
				'WebDevelopmentProject.project_owner' => $projectOwner
			),
			'joins' => array(
				array(
					'table' => 'web_development_projects',
					'alias' => 'WebDevelopmentProject',
					'type'  => 'inner',
					'conditions' => array(
						'WebDevelopmentProject.id = WebDevelopmentStage.web_development_project_id',
					)
				),
			),
			'recursive' => -1
		));

		if(!empty($stage['WebDevelopmentStage']['id'])){

			$User = ClassRegistry::init('User');
			$user = $User->find('first', array(
				'conditions' => array(
					'User.id' => $projectOwner,
				),
				'recursive' => -1
			));

			if(!empty($user['User']['parent_id'])){

				$PaymentInvoice = ClassRegistry::init('Payment.PaymentInvoice');
				$invoice = $PaymentInvoice->find('first', array(
					'conditions' => array(
						'PaymentInvoice.id' => $invoiceId,
						'PaymentInvoice.user_id' => $user['User']['parent_id'],
						'PaymentInvoice.number LIKE' => $purchaseCode ."%"
					),
					'recursive' => -1
				));

				if(!empty($invoice['PaymentInvoice']['id'])){

					$stage['WebDevelopmentStage']['payment_invoice_id'] = $invoice['PaymentInvoice']['id'];
					return $this->updateWebDevelopmentStage($stageId, $stage);

				}else{
					return false;
				}

			}else{
				return false;
			}

		}else{
			return false;
		}

	}

}
