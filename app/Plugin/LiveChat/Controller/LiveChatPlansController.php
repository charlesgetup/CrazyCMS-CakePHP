<?php
App::uses('LiveChatAppController', 'LiveChat.Controller');
/**
 * Plans Controller
 *
 */
class LiveChatPlansController extends LiveChatAppController {

    public $paginate = array(
        'limit'     => 12,
        'order'     => array("LiveChatPlan.id" => "DESC"),
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index() {

        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){
        	$this->Paginator->settings = $this->paginate;
        	$this->DataTable->mDataProp = true;
        	$this->set('response', $this->DataTable->getResponse());
        	$this->set('_serialize','response');
        	$this->set('defaultSortDir', $this->paginate['order']['LiveChatPlan.id']);
        }else{
        	$this->loadModel('LiveChat.LiveChatUser');
        	$liveChatUser = $this->LiveChatUser->browseBy("user_id", $userServiceAccountId, array('LiveChatPlan'));
        	$this->set('plan', $liveChatUser);
        	$this->Paginator->settings = array(
        		//     			'conditions' => array('LiveChatPlan.active' => 1), //TODO should add this, do soft delete; add create date and order by that date
        		'limit'		 => 14,
        		'recursive'  => -1,
        		'order' 	 => array('LiveChatPlan.id' => 'ASC')
        	);
        	$liveChatPlans = $this->Paginator->paginate('LiveChatPlan');
        	$this->set('plans', $liveChatPlans);
        }

    }

/**
 * alter method
 *
 * @throws NotFoundException
 * @param string $id
 * @param string $tempInvoiceId
 * @param int $userId (allow staff/manager enable live chat service to other clients)
 * @return void
 */
    public function admin_alter($id = null, $tempInvoiceId = null, $userId = null) {

    	if (!$this->LiveChatPlan->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if(!empty($userId)){
    		$referUrl = $this->referer();
    		$companyDomain = $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    		if(stristr($referUrl, $companyDomain) === FALSE || stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    			$userId = $this->Session->read('Auth.User.id'); // Get Client group user ID, not service group user ID
    		}
    	}else{
    		$userId = $this->Session->read('Auth.User.id'); // Get Client group user ID, not service group user ID
    	}

    	$this->loadModel('Payment.PaymentTempInvoice');

    	if(empty($tempInvoiceId)){

    		// Create new temp invoice if client wants to switch plan

    		$this->loadModel('LiveChat.LiveChatUser');
    		$planDetails = $this->LiveChatPlan->find('all', array('contain' => false));
    		$tempInvoiceId = $this->LiveChatUser->switchPlan($userId, $id, $planDetails);
    	}

    	// Show payment popup. After payment, the plan will be updated automatically.
    	if(empty($tempInvoiceId)){
    		$this->_showErrorFlashMessage($this->LiveChatPlan, __("Plan switch failed, please re-submit the plan switch request by clicking \"Switch to this plan\" button."));
    		return $this->redirect(DS .'admin' .DS .'dashboard#' .DS .'admin' .DS .'live_chat' .DS .'live_chat_plans');
    	}

    	$tempInvoice = $this->PaymentTempInvoice->browseBy('id', $tempInvoiceId, false);

    	if (($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')) {

    		$planDetails = $this->LiveChatPlan->find('all', array('contain' => false));

    		$this->loadModel('LiveChat.LiveChatUser');

    		$tempInvoiceId 	= (isset($tempInvoice['PaymentTempInvoice']['id']) ? $tempInvoice['PaymentTempInvoice']['id'] : null);

    		$switchResult = $this->LiveChatUser->switchPlan($userId, $id, $planDetails, $tempInvoiceId);

    		if ($switchResult === FALSE) {
    			$this->_showErrorFlashMessage($this->LiveChatPlan, __("Plan switch failed, please check logs for details."));
    			echo json_encode(['result' => false]);
    		} else {
    			if($switchResult === TRUE){
    				$this->_showSuccessFlashMessage($this->LiveChatPlan, __("Plan has been successfully switched. Enjoy the new plan service.") .'<br /><br />' .__('If this has been done by accident, please switch back IMMEDIATELY and no fee will be charged.'));
    				echo json_encode(['result' => true]);
    			}else{
    				echo json_encode(['tempInvoiceId' => $switchResult]);
    			}
    		}

    		exit();
    	}

    	$User = ClassRegistry::init('User');
    	$userInfo = $User->browseBy($User->primaryKey, $userId, array("Address"));

    	$paymentInfo = $tempInvoice['PaymentTempInvoice'] + array('paid_amount' => 0);

    	$isTempInvoice = true;

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

    	$this->set('tempInvoiceId', 	$tempInvoiceId);
    	$this->set('userInfo', 			$userInfo['User']);
    	$this->set('paymentInfo', 		$paymentInfo);
    	$this->set('isTempInvoice', 	$isTempInvoice);
    	$this->set('billingAddress', 	$billingAddress);
    	$this->set('country', 			$country);
    	$this->set('companyName', 		$companyName);
    	$this->set('currency', 			$currency);
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {

        if (!$this->LiveChatPlan->exists($id)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        $plan = $this->__prepareViewData($id);

	}

/**
 * add method
 *
 * @return void
 */
    public function admin_add() {

        if ($this->request->is('post') && isset($this->request->data["LiveChatPlan"]) && !empty($this->request->data["LiveChatPlan"])) {
            if ($newPlanId = $this->LiveChatPlan->savePlan($this->request->data)) {
                $this->_showSuccessFlashMessage($this->LiveChatPlan);
            } else {
                $this->_showErrorFlashMessage($this->LiveChatPlan);
            }
        }

    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_edit($id = null) {

        if (!$this->LiveChatPlan->exists($id)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["LiveChatPlan"]) && !empty($this->request->data["LiveChatPlan"])) {

            if ($this->LiveChatPlan->updatePlan($id, $this->request->data)) {
                $this->_showSuccessFlashMessage($this->LiveChatPlan);
            } else {
                $this->_showErrorFlashMessage($this->LiveChatPlan);
            }

        }

        $this->__prepareViewData($id);
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
	    	$this->LiveChatPlan->id = $id;
	        if (!$this->LiveChatPlan->exists()) {

	            throw new NotFoundRecordException($this->modelClass);
	        }

	        if($this->LiveChatPlan->deletePlan($id)){
	            $this->_showSuccessFlashMessage($this->LiveChatPlan);
	        }else{
	        	$this->_showErrorFlashMessage($this->LiveChatPlan);
	        }
    	}

    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @return void
 */
    public function admin_batchDelete() {
    	if (($this->request->is('post') || $this->request->is('delete')) && $this->request->is('ajax')){
    		if(isset($this->request->data['batchIds']) && !empty($this->request->data['batchIds']) && is_array($this->request->data['batchIds'])){
    			$batchDeleteDone = true;
    			foreach($this->request->data['batchIds'] as $id){
    				$this->LiveChatPlan->id = $id;
    				if (!$this->LiveChatPlan->exists()) {

    					throw new NotFoundRecordException($this->modelClass);
    					$batchDeleteDone = false;
    					break;
    				}
    				if (!$this->LiveChatPlan->deletePlan($id)) {

    					$logType 	 = Configure::read('Config.type.livechat');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$this->superUserId .') cannot delete live chat plan. (Passed live chat plan ID: ' .$id .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->LiveChatPlan, __("Selected live chat plans have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->LiveChatPlan, __("Selected live chat plans cannot be batch deleted."));
    			}
    		}
    	}
    }

    private function __prepareViewData($planId){

        $this->loadModel('LiveChat.LiveChatUser');

        $plan = $this->LiveChatPlan->browseBy($this->LiveChatPlan->primaryKey, $planId);
        $plans = $this->LiveChatPlan->findPlanList();

        $this->set(compact('plan', 'plans'));

        return $plan;
    }
}
