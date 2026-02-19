<?php
App::uses('WebDevelopmentAppController', 'WebDevelopment.Controller');

/**
 * Project Controller
 *
 */
class WebDevelopmentProjectsController extends WebDevelopmentAppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * index method
 *
 * Render project kanban view
 *
 * @return void
 */
    public function admin_index() {

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	$projects = $this->WebDevelopmentProject->getProjectsByServiceUserId($userServiceAccountId, $this->superUserGroup);

    	$this->set(compact('projects'));
    }

/**
 * add method
 *
 * @return void
 */
    public function admin_add(){
    	if ($this->request->is('post') && isset($this->request->data["WebDevelopmentProject"]) && !empty($this->request->data["WebDevelopmentProject"])) {
    		if(empty($this->request->data['WebDevelopmentProject']['created_by'])){
    			$this->request->data['WebDevelopmentProject']['created_by'] = $this->Session->read('Auth.User.id');
    		}

    		if ($this->WebDevelopmentProject->saveWebDevelopmentProject($this->request->data)) {
    			$this->_showSuccessFlashMessage($this->WebDevelopmentProject);
    		} else {
    			$this->_showErrorFlashMessage($this->WebDevelopmentProject);
    		}
    	}

    	$projectOwners = $this->__getProjectOwnerList();
    	$this->set('projectOwners', $projectOwners);

    	$this->render('admin_edit');
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_edit($id = null) {

    	if (!$this->WebDevelopmentProject->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){

    		if(!$this->WebDevelopmentProject->hasAny(array('id' => $id, "OR" => array('project_owner' => $this->superUserId, 'created_by' => $this->superUserId)))){

	    		throw new ForbiddenActionException($this->modelClass, "edit");

    		}
    	}

    	if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["WebDevelopmentProject"]) && !empty($this->request->data["WebDevelopmentProject"])) {

    		if ($this->WebDevelopmentProject->updateWebDevelopmentProject($id, $this->request->data)) {
    			$this->_showSuccessFlashMessage($this->WebDevelopmentProject);
    		} else {
    			$this->_showErrorFlashMessage($this->WebDevelopmentProject);
    		}

    	} else {
    		$this->request->data = $this->WebDevelopmentProject->browseBy($this->WebDevelopmentProject->primaryKey, $id);

    		$projectOwners = $this->__getProjectOwnerList();
    		$this->set('projectOwners', $projectOwners);
    	}

    }

/**
 *
 * @param int $projectId
 */
    public function admin_view($id){

    	if (!$this->WebDevelopmentProject->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	// Client uses service account ID as project Owner and staff & manager use super account ID as `created_by`. (Employee doesn't have service accounts.)
    	$serviceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){

    		if(!$this->WebDevelopmentProject->hasAny(array('id' => $id, "OR" => array('project_owner' => $serviceAccountId, 'created_by' => $this->superUserId)))){

    			$canViewProject = false;
    			if(stristr($this->superUserGroup, Configure::read('System.staff.group.name'))){

    				// If user is staff, he is definitely not a owner and creator. But we still need he to view the project of there is some tasks being assigned to him
    				$canViewProject = $this->WebDevelopmentProject->staffAssignedToProject($id);

    			}

    			if(!$canViewProject){

    				throw new ForbiddenActionException($this->modelClass, "view");
    			}

    		}
    	}

    	if(!empty($this->request->query['paidInvoice']) && stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		$stageId = $this->request->query['paidInvoice'];
    		$this->loadModel('WebDevelopment.WebDevelopmentStage');
    		if(!$this->WebDevelopmentStage->hasAny(array('id' => $stageId, "web_development_project_id" => $id))){

    			throw new NotFoundRecordException($this->modelClass, "WebDevelopmentStage");

    		}

    		$stage = $this->WebDevelopmentStage->browseBy('id', $stageId, array('PaymentInvoice'));
    		$this->loadModel('Payment.PaymentInvoice');
    		if(!$this->PaymentInvoice->hasAny(array('id' => $stage['PaymentInvoice']['id'], "user_id" => $this->Session->read('Auth.User.id'), 'amount = paid_amount', 'refund_amount' => 0, 'status' => Configure::read('Payment.invoice.status.paid'), 'modified_by = user_id'))){

    			throw new NotFoundRecordException($this->modelClass, "PaymentInvoice");
    		}

    		$stage['WebDevelopmentStage']['paid'] = 1;
    		if(!$this->WebDevelopmentStage->updateWebDevelopmentStage($stageId, array('WebDevelopmentStage' => $stage['WebDevelopmentStage']))){

    			$this->_showErrorFlashMessage($this->WebDevelopmentProject);

    			$logType 	 = Configure::read('Config.type.webdevelopment');
    			$logLevel 	 = Configure::read('System.log.level.critical');
    			$logMessage  = __('User (#' .$this->superUserId .') cannot update web development stage payment details. (Passed web development project ID: ' .$id .', web development stage ID: ' .$stageId .')');
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    		}
    	}

    	$project = $this->WebDevelopmentProject->getProjectbyId($id, $serviceAccountId, $this->superUserId, $this->superUserGroup);

    	$this->set('project', $project);

    	$this->set('accordionOpenId', @$this->request->query['accordion_id']);

    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_delete($id = null) {
    	if($this->request->is('post') || $this->request->is('delete')){

    		if (!$this->WebDevelopmentProject->exists($id)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
    			if(!$this->WebDevelopmentProject->hasAny(array('id' => $id, "OR" => array('project_owner' => $this->superUserId, 'created_by' => $this->superUserId)))){

    				throw new ForbiddenActionException($this->modelClass, "delete");

    			}
    		}

    		if ($this->WebDevelopmentProject->deleteWebDevelopmentProject($id)) {
    			$this->_showSuccessFlashMessage($this->WebDevelopmentProject);
    		}else{
    			$this->_showErrorFlashMessage($this->WebDevelopmentProject);
    		}

    	}
    }

/**
 * Update project status via ajax
 * @param int $projectId
 * @param string $newStatus
 * @return void
 */
    public function admin_updateStatus(){

    	$this->_prepareAjaxPostAction();

    	if($this->request->is('ajax') && $this->request->is('post')){
    		if(empty($this->request->data['projectId']) || empty($this->request->data['status'])){
    			return false;
    		}
    		$result = $this->WebDevelopmentProject->updateProjectStatus($this->request->data['projectId'], $this->request->data['status']);
    		if(is_array($result)){
    			$result = json_encode($result);
    		}
    		return $result;
    	}

    }

/**
 * Get all Web Development user accounts
 */
    private function __getProjectOwnerList(){
    	$this->loadModel ( 'WebDevelopment.WebDevelopmentUser' );
    	return $this->WebDevelopmentUser->getAllWebDevelopmentUsers(true);
    }
}