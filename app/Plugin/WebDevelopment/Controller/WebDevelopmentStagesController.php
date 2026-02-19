<?php
App::uses('WebDevelopmentAppController', 'WebDevelopment.Controller');
/**
 * Project Controller
 *
 */
class WebDevelopmentStagesController extends WebDevelopmentAppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel ( 'WebDevelopment.WebDevelopmentProject' );
    }

/**
 * add method
 *
 * @return void
 */
    public function admin_add($webDevelopmentProjectId){

    	if (empty($webDevelopmentProjectId) || !$this->WebDevelopmentProject->exists($webDevelopmentProjectId)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
    		if(!$this->WebDevelopmentProject->hasAny(array('id' => $webDevelopmentProjectId, "OR" => array('project_owner' => $this->superUserId, 'created_by' => $this->superUserId)))){

    			throw new ForbiddenActionException($this->modelClass, "add");

    		}
    	}

    	if ($this->request->is('post') && isset($this->request->data["WebDevelopmentStage"]) && !empty($this->request->data["WebDevelopmentStage"])) {
    		if(empty($this->request->data['WebDevelopmentStage']['created_by'])){
    			$this->request->data['WebDevelopmentStage']['created_by'] = $this->Session->read('Auth.User.id');
    		}

    		if(!empty($this->request->data['WebDevelopmentStage']['start_time'])){
    			$this->request->data['WebDevelopmentStage']['start_time'] = date('Y-m-d H:i:s', strtotime($this->request->data['WebDevelopmentStage']['start_time']));
    		}
    		if(!empty($this->request->data['WebDevelopmentStage']['end_time'])){
    			$this->request->data['WebDevelopmentStage']['end_time'] = date('Y-m-d H:i:s', strtotime($this->request->data['WebDevelopmentStage']['end_time']));
    		}

    		$this->request->data['WebDevelopmentStage']['web_development_project_id'] = $webDevelopmentProjectId;

    		if ($this->WebDevelopmentStage->saveWebDevelopmentStage($this->request->data)) {
    			$this->_showSuccessFlashMessage($this->WebDevelopmentStage);
    		} else {
    			$this->_showErrorFlashMessage($this->WebDevelopmentStage);
    		}
    	}

    	$this->set('webDevelopmentProjectId', $webDevelopmentProjectId);

    	$this->render('admin_edit');
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param int $webDevelopmentProjectId
 * @param int $webDevelopmentProjectStageId
 * @return void
 */
    public function admin_edit($webDevelopmentProjectId, $webDevelopmentProjectStageId = null) {

    	if (empty($webDevelopmentProjectId) || !$this->WebDevelopmentProject->exists($webDevelopmentProjectId)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if (!$this->WebDevelopmentStage->exists($webDevelopmentProjectStageId)) {

    		throw new ForbiddenActionException($this->modelClass, "edit");
    	}

    	if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){

    		if(!$this->WebDevelopmentStage->hasAny(array('id' => $webDevelopmentProjectStageId, 'created_by' => $this->superUserId, 'web_development_project_id' => $webDevelopmentProjectId))){

	    		throw new ForbiddenActionException($this->modelClass, "edit");

    		}
    	}

    	if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["WebDevelopmentStage"]) && !empty($this->request->data["WebDevelopmentStage"])) {

    		if(empty($this->request->data['WebDevelopmentStage']['created_by'])){
    			$this->request->data['WebDevelopmentStage']['created_by'] = $this->Session->read('Auth.User.id');
    		}

    		if(!empty($this->request->data['WebDevelopmentStage']['start_time'])){
    			$this->request->data['WebDevelopmentStage']['start_time'] = date('Y-m-d H:i:s', strtotime($this->request->data['WebDevelopmentStage']['start_time']));
    		}
    		if(!empty($this->request->data['WebDevelopmentStage']['end_time'])){
    			$this->request->data['WebDevelopmentStage']['end_time'] = date('Y-m-d H:i:s', strtotime($this->request->data['WebDevelopmentStage']['end_time']));
    		}

    		$this->request->data['WebDevelopmentStage']['web_development_project_id'] = $webDevelopmentProjectId;

    		if ($this->WebDevelopmentStage->updateWebDevelopmentStage($webDevelopmentProjectStageId, $this->request->data)) {
    			$this->_showSuccessFlashMessage($this->WebDevelopmentStage);
    		} else {
    			$this->_showErrorFlashMessage($this->WebDevelopmentStage);
    		}

    	} else {
    		$this->request->data = $this->WebDevelopmentStage->browseBy($this->WebDevelopmentStage->primaryKey, $webDevelopmentProjectStageId);
    	}

    	$projectStageStatus 	= Configure::read('WebDevelopment.stage.status');
    	$projectStageStatusList = array();
    	for($i = 0; $i < count($projectStageStatus); $i++){
    		$projectStageStatusList[$projectStageStatus[$i]] = __(Inflector::humanize($projectStageStatus[$i]));
    	}
    	$this->set('projectStageStatus', $projectStageStatusList);
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param int $webDevelopmentProjectId
 * @param int $webDevelopmentProjectStageId
 * @return void
 */
    public function admin_delete($webDevelopmentProjectId, $webDevelopmentProjectStageId = null) {
    	if($this->request->is('post') || $this->request->is('delete')){

    		if (empty($webDevelopmentProjectId) || !$this->WebDevelopmentProject->exists($webDevelopmentProjectId)) {

    			throw new NotFoundRecordException($this->modelClass, "WebDevelopmentProject");
    		}

    		if (!$this->WebDevelopmentStage->exists($webDevelopmentProjectStageId)) {

    			throw new ForbiddenActionException($this->modelClass, "delete");
    		}

    		if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
    			if(!$this->WebDevelopmentStage->hasAny(array('id' => $webDevelopmentProjectStageId, 'created_by' => $this->superUserId, 'web_development_project_id' => $webDevelopmentProjectId))){

    				throw new ForbiddenActionException($this->modelClass, "delete");

    			}
    		}

    		if ($this->WebDevelopmentStage->deleteWebDevelopmentStage($webDevelopmentProjectStageId)) {
    			$this->_showSuccessFlashMessage($this->WebDevelopmentStage);
    		}else{
    			$this->_showErrorFlashMessage($this->WebDevelopmentStage);
    		}

    	}
    }

    public function admin_addInvoice($stageId, $invoiceId, $projectOwner, $purchaseCode){
    	$this->_prepareAjaxPostAction();

    	if($this->request->is('post') && $this->request->is('ajax') && !empty($stageId) && !empty($invoiceId) && !empty($projectOwner) && !empty($purchaseCode)){
    		if($this->WebDevelopmentStage->addInvoice($stageId, $invoiceId, $projectOwner, $purchaseCode)){

    			echo Configure::read('System.variable.success');

    		}else{
    			echo json_encode(array('status' => Configure::read('System.variable.warning'), 'message' => __('Invoice created, but it didn\'t link to the web development stage.')));

    			$logType 	 = Configure::read('Config.type.webdevelopment');
    			$logLevel 	 = Configure::read('System.log.level.critical');
    			$logMessage  = __('User (#' .$projectOwner .') invoice cannot link to web development project stage. (Passed invoice ID: ' .$invoiceId .', web development project stage ID: ' .$stageId .')');
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
    		}
    	}

    	exit();
    }

    public function admin_payInvoice($stageId, $invoiceId = null){
    	if (!$this->WebDevelopmentStage->hasAny(array('id' => $stageId, 'payment_invoice_id IS NOT NULL', 'paid' => 0))) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	$stage = $this->WebDevelopmentStage->browseBy('id', $stageId, array('PaymentInvoice'));

    	if (($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')) {
    		echo json_encode(['tempInvoiceId' => $stage['WebDevelopmentStage']['payment_invoice_id']]);
    		exit();
    	}

    	// Show payment popup.
    	if(empty($invoiceId)){
    		$this->_showErrorFlashMessage($this->WebDevelopmentStage, __("Web development staging purchase failed, please re-submit the purchase request."));
    		return $this->redirect(DS .'admin' .DS .'dashboard#' .DS .'admin' .DS .'web_development' .DS .'web_development_projects');
    	}

    	$User = ClassRegistry::init('User');
    	$userInfo = $User->browseBy($User->primaryKey, $stage['PaymentInvoice']['user_id'], array("Address"));

    	$paymentInfo = $stage['PaymentInvoice'];

    	$isTempInvoice = false;

    	$billingAddressIndex = ($userInfo['Address'][0]['type'] == 'BILLING') ? 0 : 1;
    	$billingAddress = $userInfo['Address'][$billingAddressIndex];
    	if($billingAddress['same_as']){
    		$billingAddressIndex = ($billingAddressIndex == 1) ? 0 : 1;
    		$billingAddress = $userInfo['Address'][$billingAddressIndex];
    	}
    	if(empty($billingAddress['country_id'])){
    		$country = false;
    	}else{
    		$this->loadModel('Country');
    		$Country = ClassRegistry::init("Country");
    		$country = $this->Country->browseBy('id', $billingAddress['country_id']);
    	}

    	$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    	$currency 	 = $this->_getSystemDefaultConfigSetting("Currency", Configure::read('Config.type.payment'));

    	$this->set('tempInvoiceId', 	$invoiceId);
    	$this->set('userInfo', 			$userInfo['User']);
    	$this->set('paymentInfo', 		$paymentInfo);
    	$this->set('isTempInvoice', 	$isTempInvoice);
    	$this->set('billingAddress', 	$billingAddress);
    	$this->set('country', 			$country);
    	$this->set('companyName', 		$companyName);
    	$this->set('currency', 			$currency);
    }
}