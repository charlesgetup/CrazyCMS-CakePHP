<?php
App::uses('TaskManagementAppModel', 'TaskManagement.Model');

/**
 * Task Comment Model
 *
 */
class TaskManagementTaskComment extends TaskManagementAppModel {

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
		'comment' => array(
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
		'Parent' => array(
			'className'    => 'TaskManagement.TaskManagementTaskComment',
			'foreignKey'   => 'parent_id',
			'dependent'    => false
		),
		'TaskManagementTask' => array(
			'className'    => 'TaskManagement.TaskManagementTask',
			'foreignKey'   => 'task_management_task_id',
			'dependent'    => false
		),
	);

    public $hasMany = array(
        'Children' => array(
    		'className'    => 'TaskManagement.TaskManagementTaskComment',
    		'foreignKey'   => 'parent_id',
    		'dependent'    => true
	    ),
    );

    public function saveTaskManagementTaskComment($data){
    	$this->create();
    	if($this->saveAll($data, array('validate' => 'first'))){
    		return $this->id;
    	}else{
    		return false;
    	}
    }

    public function updateTaskManagementTaskComment($id, $data){
    	$this->id = $id;
    	$this->contain();

    	if(!$this->saveAll($data, array('validate' => 'first'))){
    		return false;
    	}
    	return true;
    }

    public function deleteTaskManagementTaskComment($id){
    	return $this->updateAll(
		    array('TaskManagementTaskComment.deleted' => 1),
		    array('OR' => array('TaskManagementTaskComment.parent_id' => $id, 'TaskManagementTaskComment.id' => $id))
		);
    }

    public function getCommentsByTaskId($taskId, $commentId = null){

    	$conditions = array(
    		'task_management_task_id' => $taskId
    	);

    	if(!empty($commentId) && is_numeric($commentId)){
    		$conditions['TaskManagementTaskComment.id'] = $commentId;
    	}

    	return $this->find('threaded', array(
    		'conditions' => $conditions,
    		'contain' => array('Creator'),
    		'order' => array('created_at ASC')
    	));

    }
}
