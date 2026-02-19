<?php

App::uses('TaskManagementAppController', 'TaskManagement.Controller');

/**
 *
 * Tasks Controller
 *
 */

class TaskManagementTasksController extends TaskManagementAppController {

    public function beforeFilter() {
        parent::beforeFilter();

        $this->loadModel ( 'WebDevelopment.WebDevelopmentStage' );
        $this->loadModel ( 'Group' );
    }

/**
 * index method
 *
 * Render tasks kanban view (for web development) and ticket view
 *
 * $returnMyTickets is only applied to TICKET task type
 *
 * @return void
 */
    public function admin_index($webDevelopmentStageId = null, $returnMyTickets = FALSE) {

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) !== FALSE){
    		$webDevelopmentStageId = null;
    	}

    	if(!empty($webDevelopmentStageId)){
    		$taskType = Configure::read('TaskManagement.type.webdev');
    		$returnMyTickets = FALSE;
    	}else{
    		$taskType = Configure::read('TaskManagement.type.ticket');
    		if(!empty($returnMyTickets)){
    			$returnMyTickets = TRUE;
    		}else{
    			$returnMyTickets = FALSE;
    		}
    	}

    	$tasks = $this->TaskManagementTask->getTaskManagementTasks($this->superUserId, $this->superUserGroup, $webDevelopmentStageId, $taskType, $returnMyTickets);

    	$this->set(compact('tasks', 'taskType', 'webDevelopmentStageId', 'returnMyTickets'));

    }

/**
 * add method
 *
 * @return void
 */
    public function admin_add($webDevelopmentStageId = null, $parentId = null){

    	if ($this->request->is('post') && isset($this->request->data["TaskManagementTask"]) && !empty($this->request->data["TaskManagementTask"])) {

    		if(!empty($webDevelopmentStageId) && !$this->WebDevelopmentStage->hasAny(array('id' => $webDevelopmentStageId))){

    			throw new NotFoundRecordException($this->modelClass, "WebDevelopmentStage");
    		}

    		if(empty($this->request->data['TaskManagementTask']['created_by'])){
    			$this->request->data['TaskManagementTask']['created_by'] = $this->Session->read('Auth.User.id');
    		}

    		$ticketType = Configure::read('TaskManagement.type.ticket');
    		$webdevType = Configure::read('TaskManagement.type.webdev');

    		if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){

    			if(stristr($this->superUserGroup, Configure::read('System.staff.group.name')) !== FALSE){

    				// Staff cannot create web development task, they can only create ticket tasks, like the clients
    				$parentId = null;
    				$webDevelopmentStageId = null;
    				$this->request->data['TaskManagementTask']['type'] = $ticketType;

    			}elseif(stristr($this->superUserGroup, Configure::read('System.manager.group.name')) !== FALSE){

    				if(empty($webDevelopmentStageId)){

    					$this->request->data['TaskManagementTask']['type'] = $ticketType;

    				}else{

    					// Manager can create tasks in own managed projects' stages
    					if(!$this->WebDevelopmentStage->managerOwnThisStage($webDevelopmentStageId)){

    						throw new ForbiddenActionException($this->modelClass, "add");
    					}

    					$this->request->data['TaskManagementTask']['web_development_stage_id'] = $webDevelopmentStageId;
    					$this->request->data['TaskManagementTask']['type'] = $webdevType;

    				}

    			}elseif(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) !== FALSE){

    				if(empty($webDevelopmentStageId)){

    					$this->request->data['TaskManagementTask']['type'] = $ticketType;

    				}else{

    					$this->request->data['TaskManagementTask']['web_development_stage_id'] = $webDevelopmentStageId;
    					$this->request->data['TaskManagementTask']['type'] = $webdevType;
    				}

    			}

    		}else{

    			// Client can only create ticket
    			$parentId = null;
    			$webDevelopmentStageId = null;
    			$this->request->data['TaskManagementTask']['type'] = $ticketType;

    		}

    		if(!empty($this->request->data['TaskManagementTask']['end_time'])){
    			$this->request->data['TaskManagementTask']['end_time'] = date('Y-m-d H:i:s', strtotime($this->request->data['TaskManagementTask']['end_time']));
    		}

    		if(!empty($parentId)){

    			if(!is_numeric($parentId) || !$this->TaskManagementTask->hasAny(array('id' => $parentId))){

    				unset($this->request->data['TaskManagementTask']['parent_id']);

    				throw new ForbiddenActionException($this->modelClass, "add");
    			}

    			$this->request->data['TaskManagementTask']['parent_id'] = $parentId;

    		}else{

    			unset($this->request->data['TaskManagementTask']['parent_id']);

    		}

    		if($this->request->data['TaskManagementTask']['type'] == $ticketType && empty($this->request->data['TaskManagementTask']['end_time'])){
    			$this->request->data['TaskManagementTask']['end_time'] = date('Y-m-d H:i:s', strtotime('+2 day')); // Set default end time for tickets
    		}

    		if ($this->TaskManagementTask->saveTaskManagementTask($this->request->data)) {
    			$this->_showSuccessFlashMessage($this->TaskManagementTask);
    		} else {
    			$this->_showErrorFlashMessage($this->TaskManagementTask);
    		}
    	}

    	if(!empty($webDevelopmentStageId) && (stristr($this->superUserGroup, Configure::read('System.manager.group.name')) !== FALSE || stristr($this->superUserGroup, Configure::read('System.admin.group.name')) !== FALSE)){
    		$assignees = $this->__getAssigneeList();
    		$this->set('assignees', $assignees);
    	}

    	if(empty($webDevelopmentStageId)){

    		$this->set('departments', $this->Group->getDepartments());
    	}

    	$this->set('webDevelopmentStageId', $webDevelopmentStageId);

    	$this->render('admin_edit');
    }

/**
 * edit method
 *
 * @return void
 */
    public function admin_edit($id, $webDevelopmentStageId){

    	if (!$this->TaskManagementTask->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if(!empty($webDevelopmentStageId) && !$this->WebDevelopmentStage->hasAny(array('id' => $webDevelopmentStageId))){

    		throw new NotFoundRecordException($this->modelClass, "WebDevelopmentStage");

    	}

    	if(!empty($webDevelopmentStageId)){

    		if(stristr($this->superUserGroup, Configure::read('System.manager.group.name'))){

    			// Manager can edit tasks in own managed projects' stages
    			if(!$this->WebDevelopmentStage->managerOwnThisStage($webDevelopmentStageId)){

    				throw new ForbiddenActionException($this->modelClass, "edit");
    			}

    		}elseif(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){

    			// Staff & client cannot edit task in any stage
    			throw new ForbiddenActionException($this->modelClass, "edit");
    		}

    	}else{

    		$this->set('departments', $this->Group->getDepartments());
    	}

    	// Ticket cannot be edited
    	if($this->TaskManagementTask->hasAny(array('id' => $id, 'type' => Configure::read('TaskManagement.type.ticket')))){

    		throw new ForbiddenActionException($this->modelClass, "edit");
    	}

    	$task = $this->TaskManagementTask->browseBy($this->TaskManagementTask->primaryKey, $id);

    	if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["TaskManagementTask"]) && !empty($this->request->data["TaskManagementTask"])) {

    		if(empty($this->request->data["TaskManagementTask"]['type'])){
    			$this->request->data["TaskManagementTask"]['type'] = $task["TaskManagementTask"]['type'];
    		}
    		if(empty($this->request->data["TaskManagementTask"]['created_at'])){
    			$this->request->data["TaskManagementTask"]['created_at'] = $task["TaskManagementTask"]['created_at'];
    		}

    		if(!empty($this->request->data['TaskManagementTask']['end_time'])){
    			$this->request->data['TaskManagementTask']['end_time'] = date('Y-m-d H:i:s', strtotime($this->request->data['TaskManagementTask']['end_time']));
    		}

    		if(!empty($webDevelopmentStageId) || !empty($task['TaskManagementTask']['web_development_stage_id'])){
    			$this->request->data["TaskManagementTask"]['web_development_stage_id'] = empty($webDevelopmentStageId) ? $task['TaskManagementTask']['web_development_stage_id'] : $webDevelopmentStageId;
    		}

    		$this->request->data["TaskManagementTask"]['parent_id'] = $task["TaskManagementTask"]['parent_id']; // Parent ID can only be changed by drag and drop

    		if ($this->TaskManagementTask->updateTaskManagementTask($id, $this->request->data)) {
    			$this->_showSuccessFlashMessage($this->TaskManagementTask);
    		} else {
    			$this->_showErrorFlashMessage($this->TaskManagementTask);
    		}

    	} else {

    		$this->request->data = $task;

    	}

    	$assignees = $this->__getAssigneeList();
    	$this->set('assignees', $assignees);

    	$taskStatus 	= Configure::read('TaskManagement.status');
    	$taskStatusList = array();
    	for($i = 0; $i < count($taskStatus); $i++){
    		$taskStatusList[$taskStatus[$i]] = __(Inflector::humanize($taskStatus[$i]));
    	}
    	$this->set('taskStatus', $taskStatusList);

    	$this->set('webDevelopmentStageId', $webDevelopmentStageId);
    }

/**
 * view method
 *
 * @return void
 */
    public function admin_view($webDevelopmentStageId, $id){

    	if (!$this->TaskManagementTask->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if(!empty($webDevelopmentStageId) && stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE && !$this->WebDevelopmentStage->hasAny(array('id' => $webDevelopmentStageId))){

    		$canViewTask = false;
    		if(stristr($this->superUserGroup, Configure::read('System.staff.group.name'))){

    			// If user is staff, he is definitely not a owner and creator. But we still need he to view the project because there is some tasks being assigned to him
    			$canViewTask = $this->WebDevelopmentStage->staffAssignedToStage($webDevelopmentStageId, $id);

    		}

    		if($canViewTask && stristr($this->superUserGroup, Configure::read('System.manager.group.name'))){

    			$canViewTask = $this->WebDevelopmentStage->managerOwnThisStage($webDevelopmentStageId);
    		}

    		if(!$canViewTask || stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

	    		throw new ForbiddenActionException($this->modelClass, "view");
    		}

    	}

    	$task = $this->TaskManagementTask->findById($id);

    	if(!empty($task['TaskManagementTask']['web_development_stage_id']) && empty($webDevelopmentStageId)){

    		throw new ForbiddenActionException($this->modelClass, "view");
    	}

    	$ownTask = ($task['TaskManagementTask']['created_by'] == $this->superUserId) && $task['TaskManagementTask']['type'] == Configure::read('TaskManagement.type.ticket');

    	$possibleAssigneeList = $this->TaskManagementTask->getAssigneeList($this->superUserGroupId);

    	if(empty($webDevelopmentStageId)){

    		$this->set('departments', $this->Group->getDepartments());
    	}

    	$this->set(compact('task', 'ownTask', 'possibleAssigneeList'));
    }

/**
 * delete method
 *
 * @return void
 */
    public function admin_delete($webDevelopmentStageId, $id){

    	if($this->request->is('post') || $this->request->is('delete')){

    		if (!$this->TaskManagementTask->exists($id)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		if(!empty($webDevelopmentStageId) && !$this->WebDevelopmentStage->hasAny(array('id' => $webDevelopmentStageId))){

    			throw new NotFoundRecordException($this->modelClass, "WebDevelopmentStage");

    		}

    		if(!empty($webDevelopmentStageId)){

    			if(stristr($this->superUserGroup, Configure::read('System.manager.group.name'))){

    				// Manager can delete tasks in own managed projects' stages
    				if(!$this->WebDevelopmentStage->managerOwnThisStage($webDevelopmentStageId)){

    					throw new ForbiddenActionException($this->modelClass, "delete");
    				}

    			}elseif(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){

    				// Staff and client cannot delete task in any stage
    				throw new ForbiddenActionException($this->modelClass, "delete");
    			}

    		}

    		// Ticket cannot be deleted
    		if($this->TaskManagementTask->hasAny(array('id' => $id, 'type' => Configure::read('TaskManagement.type.ticket')))){

    			throw new ForbiddenActionException($this->modelClass, "delete");
    		}

    		if ($this->TaskManagementTask->deleteTaskManagementTask($id)) {
    			$this->_showSuccessFlashMessage($this->TaskManagementTask);
    		}else{
    			$this->_showErrorFlashMessage($this->TaskManagementTask);
    		}

    	}
    }

/**
 * Re-order task and sub-tasks
 * @param int $webDevelopmentStageId
 * @throws NotFoundException
 */
    public function admin_reorder($webDevelopmentStageId){

    	$this->_prepareAjaxPostAction();

    	if($this->request->is('post') && $this->request->is('ajax')){

    		if(!$this->WebDevelopmentStage->hasAny(array('id' => $webDevelopmentStageId))){

    			throw new NotFoundRecordException($this->modelClass, "WebDevelopmentStage");

    		}

    		return $this->__reorderTasks($this->request->data, $webDevelopmentStageId);

    	}

    	exit();
    }

    public function admin_updateAssignee($id){

    	$this->_prepareAjaxPostAction();

    	if($this->request->is('post') && $this->request->is('ajax')){

    		if (!$this->TaskManagementTask->exists($id)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		if(
    			(
    				stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE &&
    				stristr($this->superUserGroup, Configure::read('System.manager.group.name')) === FALSE 		// Staff & client cannot assign task to anyone
	    		)
    			||
    			(
    				stristr($this->superUserGroup, Configure::read('System.manager.group.name')) !== FALSE &&
    				!$this->TaskManagementTask->hasAny(array('id' => $id, 'group_id' => $this->superUserGroupId))  // Manager can only assign task in own dept; admin can do anything
    			)
    		){
    			throw new ForbiddenActionException($this->modelClass, "update ? assignee");
    		}

    		if(!empty($this->request->data['assignee'])){
    			echo $this->TaskManagementTask->updateTaskManagementTaskAssignee($id, $this->request->data['assignee']);
    		}

    	}

    	exit();
    }


    //TODO update admin_updateStatus, add update dept / priority functions and fix pri always = 1 issue; then add validation to make sure admin / employee cannot registed as client


    public function admin_updateStatus($id){

    	$this->_prepareAjaxPostAction();

    	if($this->request->is('post') && $this->request->is('ajax')){

    		if (!$this->TaskManagementTask->exists($id)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		if(
    			stristr($this->superUserGroup, Configure::read('System.client.group.name')) // Admin, Manager & staff can all update ticket / task status
    			||
    			(
    				stristr($this->superUserGroup, Configure::read('System.manager.group.name')) &&
    				!$this->TaskManagementTask->hasAny(array('id' => $id, 'group_id' => $this->superUserGroupId))  // Manager can only update status of task in own dept
	    		)
	    		||
	    		(
	    			stristr($this->superUserGroup, Configure::read('System.staff.group.name')) &&
    				!$this->TaskManagementTask->hasAny(array('id' => $id, 'assignee' => $this->superUserId))  // Staff can only update status of assigned task
    			)
    		){

    			throw new ForbiddenActionException($this->modelClass, "update ? status");
    		}

    		if(!empty($this->request->data['status'])){
    			echo $this->TaskManagementTask->updateTaskManagementTaskStatus($id, $this->request->data['status']);
    		}

    	}

    	exit();
    }

    public function admin_updateDepartment($id){

    	$this->_prepareAjaxPostAction();

    	if($this->request->is('post') && $this->request->is('ajax')){

    		if (!$this->TaskManagementTask->exists($id)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		if(
	    		(
	    			stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE &&
	    			stristr($this->superUserGroup, Configure::read('System.manager.group.name')) === FALSE 		// Staff & client cannot update department of task
	    		)
	    		||
	    		(
	    			stristr($this->superUserGroup, Configure::read('System.manager.group.name')) !== FALSE &&
	    			!$this->TaskManagementTask->hasAny(array('id' => $id, 'group_id' => $this->superUserGroupId))  // Manager can only update task department when it is in own dept now; admin can do anything
	    		)
    		){
    			throw new ForbiddenActionException($this->modelClass, "update ? department");
    		}

    		if(!empty($this->request->data['department'])){
    			echo $this->TaskManagementTask->updateTaskManagementTaskDepartment($id, $this->request->data['department']);
    		}

    	}

    	exit();
    }

    public function admin_updatePriority($id){

    	$this->_prepareAjaxPostAction();

    	if($this->request->is('post') && $this->request->is('ajax')){

    		if (!$this->TaskManagementTask->exists($id)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		if(
	    		(
	    			stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE &&
	    			stristr($this->superUserGroup, Configure::read('System.manager.group.name')) === FALSE 		// Staff & client cannot update priority of task
	    		)
	    		||
	    		(
	    			stristr($this->superUserGroup, Configure::read('System.manager.group.name')) !== FALSE &&
	    			!$this->TaskManagementTask->hasAny(array('id' => $id, 'group_id' => $this->superUserGroupId))  // Manager can only update priority of task in own dept now; admin can do anything
	    		)
    		){
    			throw new ForbiddenActionException($this->modelClass, "update ? assignee");
    		}

    		if(!empty($this->request->data['priority'])){
    			echo $this->TaskManagementTask->updateTaskManagementTaskPriority($id, $this->request->data['priority']);
    		}

    	}

    	exit();
    }

    private function __reorderTasks($taskIdArr, $webDevelopmentStageId, $parentId = null){

    	// Only tasks under project stage can be re-ordered
    	if(empty($taskIdArr) || !is_array($taskIdArr) || empty($webDevelopmentStageId)){
    		return false;
    	}

    	$result = true;
    	foreach($taskIdArr as $taskId){

    		$task = $this->TaskManagementTask->browseBy('id', $taskId['id']);

    		$isAdmin 				= stristr($this->superUserGroup, Configure::read('System.admin.group.name'));
    		$isUnderManagerControl 	= stristr($this->superUserGroup, Configure::read('System.manager.group.name')) && $this->WebDevelopmentStage->managerOwnThisStage($webDevelopmentStageId);

    		if(($isAdmin || $isUnderManagerControl) && $task['TaskManagementTask']['web_development_stage_id'] == $webDevelopmentStageId){

    			if($parentId != $task['TaskManagementTask']['parent_id']){

    				$task['TaskManagementTask']['parent_id'] = $parentId;
    				$result = $this->TaskManagementTask->updateTaskManagementTask($taskId['id'], $task);

    				if($result === FALSE){

    					break;

    				}

    			}

    			if(!empty($taskId['children']) && is_array($taskId['children'])){

    				if(FALSE === $this->__reorderTasks($taskId['children'], $webDevelopmentStageId, $task['TaskManagementTask']['id'])){

    					$result = false;
    					break;

    				}

    			}

    		}else{

    			throw new ForbiddenActionException($this->modelClass, "re-order");

    		}

    	}

    	return $result;

    }

/**
 * This function only get employee in web development department
 *
 * @return mixed
 */
    private function __getAssigneeList(){

    	$this->loadModel ( 'User' );

    	if(stristr($this->superUserGroup, Configure::read('System.manager.group.name')) !== FALSE){
    		$conditions = array(
    			'OR' => array(
    				'Group.id' 	=> Configure::read('WebDevelopment.staff.group.id'),
    				'User.id' 	=> $this->superUserId // Manager user ID. Manager can assign tasks to himself
    			)
    		);
    	}elseif(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) !== FALSE){
    		$conditions = array(
    			'Group.id' 	=> array(
    				Configure::read('WebDevelopment.staff.group.id'),
    				Configure::read('WebDevelopment.manager.group.id')
	    		)
    		);
    	}

    	return (!isset($conditions) || empty($conditions)) ? false : $this->User->listAll($conditions);

    }
}
