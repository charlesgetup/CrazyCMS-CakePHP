<?php
use Monolog\Handler\error_log;
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * Plans Controller
 *
 */
class EmailMarketingPlansController extends EmailMarketingAppController {

    public $paginate = array(
        'limit'     => 12,
        'order'     => array("EmailMarketingPlan.id" => "DESC")
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
        	$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingPlan.id']);

        	$subscriberUnitPrice 		= $this->_getSystemDefaultConfigSetting('SubscriberUnitPrice', Configure::read('Config.type.emailmarketing'));
        	$emailUnitPrice 			= $this->_getSystemDefaultConfigSetting('EmailUnitPrice', Configure::read('Config.type.emailmarketing'));
        	$extraAttributeUnitPrice 	= $this->_getSystemDefaultConfigSetting('ExtraAttributeUnitPrice', Configure::read('Config.type.emailmarketing'));
        	$emailSenderUnitPrice 		= $this->_getSystemDefaultConfigSetting('EmailSenderUnitPrice', Configure::read('Config.type.emailmarketing'));
        	$this->set(compact('subscriberUnitPrice', 'emailUnitPrice', 'extraAttributeUnitPrice', 'emailSenderUnitPrice'));

        }else{

			$emailMarketingUserId = $this->EmailMarketingPlan->emailMarketingUserAccountIdToEmailMarketingUserId($userServiceAccountId);
        	$this->loadModel('EmailMarketing.EmailMarketingUser');
        	$emailMarketingUser = $this->EmailMarketingUser->browseBy("user_id", $userServiceAccountId, array('EmailMarketingPlan'));
        	$this->set('plan', $emailMarketingUser);
        	$this->Paginator->settings = array(
        		'conditions' => array(
        			//'EmailMarketingPlan.active' => 1, //TODO should add this, do soft delete; add create date and order by that date
        			'OR' => array(
        				'EmailMarketingPlan.private_email_user_id = 0',
        				'EmailMarketingPlan.private_email_user_id = ' .$emailMarketingUserId
	        		)
        		),
        		'recursive'  => -1,
        		'order' 	 => array('EmailMarketingPlan.id' => 'ASC')
        	);
        	$emailMarketingPlans = $this->Paginator->paginate('EmailMarketingPlan');
        	$this->set('plans', $emailMarketingPlans);
        }

    }

/**
 * alter method
 *
 * @throws NotFoundException
 * @param string $id
 * @param string $tempInvoiceId
 * @param int $userId (allow staff/manager enable email marketing service to other clients)
 * @return void
 */
    public function admin_alter($id = null, $tempInvoiceId = null, $userId = null) {

    	if (!$this->EmailMarketingPlan->exists($id)) {

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

    		$this->loadModel('EmailMarketing.EmailMarketingUser');
    		$planDetails = $this->EmailMarketingPlan->find('all', array('contain' => false));
    		$tempInvoiceId = $this->EmailMarketingUser->switchPlan($userId, $id, $planDetails);
    	}

    	// Show payment popup. After payment, the plan will be updated automatically.
    	if(empty($tempInvoiceId)){
    		$this->_showErrorFlashMessage($this->EmailMarketingPlan, __("Plan switch failed, please re-submit the plan switch request by clicking \"Switch to this plan\" button."));
    		return $this->redirect(DS .'admin' .DS .'dashboard#' .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_plans');
    	}

    	$tempInvoice = $this->PaymentTempInvoice->browseBy('id', $tempInvoiceId, false);

    	if (($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')) { // This is used by one time payment (express checkout)

    		$planDetails = $this->EmailMarketingPlan->find('all', array('contain' => false));

    		$this->loadModel('EmailMarketing.EmailMarketingUser');

    		$tempInvoiceId 	= (isset($tempInvoice['PaymentTempInvoice']['id']) ? $tempInvoice['PaymentTempInvoice']['id'] : null);

    		$switchResult = $this->EmailMarketingUser->switchPlan($userId, $id, $planDetails, $tempInvoiceId);

    		if ($switchResult === FALSE) {
    			$this->_showErrorFlashMessage($this->EmailMarketingPlan, __("Plan switch failed, please check logs for details."));
    			echo json_encode(['result' => false]);
    		} else {
    			if($switchResult === TRUE){
    				$this->_showSuccessFlashMessage($this->EmailMarketingPlan, __("Plan has been successfully switched. Enjoy the new plan service.") .'<br /><br />' .__('If this has been done by accident, please switch back IMMEDIATELY and no fee will be charged.'));
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
    	if(!empty($paymentInfo['content'])){
    		if(!empty($planDetails)){
    			foreach($planDetails as $p){
    				if($p['EmailMarketingPlan']['id'] == $id){
    					$plan = $p;
    					break;
    				}
    			}
    		}
    		if(!empty($plan)){
    			$plan = $this->EmailMarketingPlan->browseBy('id', $id);
    		}
    		$paymentInfo['content'] = str_replace("%email-limit%", $plan['EmailMarketingPlan']['email_limit'], $paymentInfo['content']);
    		$paymentInfo['content'] = str_replace("%unit-price%", $plan['EmailMarketingPlan']['unit_price'], $paymentInfo['content']);
    		$paymentInfo['content'] = str_replace("%subscriber-limit%", $plan['EmailMarketingPlan']['subscriber_limit'], $paymentInfo['content']);
    		$paymentInfo['content'] = str_replace("%sender-limit%", $plan['EmailMarketingPlan']['sender_limit'], $paymentInfo['content']);
    		$paymentInfo['content'] = str_replace("%extra-attr-limit%", $plan['EmailMarketingPlan']['extra_attr_limit'], $paymentInfo['content']);
    	}

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

        if (!$this->EmailMarketingPlan->exists($id)) {

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

        if ($this->request->is('post') && isset($this->request->data["EmailMarketingPlan"]) && !empty($this->request->data["EmailMarketingPlan"])) {

        	if(!empty($this->request->data["EmailMarketingPlan"]['private_email_user_id'])){
            	$this->EmailMarketingPlan->deletePrivatePlan($this->request->data["EmailMarketingPlan"]['private_email_user_id']);
        	}

            if ($newPlanId = $this->EmailMarketingPlan->savePlan($this->request->data)) {
                $this->_showSuccessFlashMessage($this->EmailMarketingPlan);
            } else {
                $this->_showErrorFlashMessage($this->EmailMarketingPlan);
            }

            if(!empty($this->request->data["EmailMarketingPlan"]['private_email_user_id'])){
            	return $this->redirect($this->request->referer() ."#/admin/email_marketing/email_marketing_plans");
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

        if (!$this->EmailMarketingPlan->exists($id)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["EmailMarketingPlan"]) && !empty($this->request->data["EmailMarketingPlan"])) {

            if ($this->EmailMarketingPlan->updatePlan($id, $this->request->data)) {
                $this->_showSuccessFlashMessage($this->EmailMarketingPlan);
            } else {
                $this->_showErrorFlashMessage($this->EmailMarketingPlan);
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
	    	$this->EmailMarketingPlan->id = $id;
	        if (!$this->EmailMarketingPlan->exists()) {

	            throw new NotFoundRecordException($this->modelClass);
	        }

	        if($this->EmailMarketingPlan->deletePlan($id)){
	            $this->_showSuccessFlashMessage($this->EmailMarketingPlan);
	        }else{
	        	$this->_showErrorFlashMessage($this->EmailMarketingPlan);

	        	$logType 	 = Configure::read('Config.type.emailmarketing');
	        	$logLevel 	 = Configure::read('System.log.level.critical');
	        	$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing plan. (Passed email marketing plan ID: ' .$id .')');
	        	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
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
    				$this->EmailMarketingPlan->id = $id;
    				if (!$this->EmailMarketingPlan->exists()) {

    					throw new NotFoundRecordException($this->modelClass);
    					$batchDeleteDone = false;
    					break;
    				}
    				if (!$this->EmailMarketingPlan->deletePlan($id)) {

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing plan. (Passed email marketing plan ID: ' .$id .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->EmailMarketingPlan, __("Selected email marketing plans have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->EmailMarketingPlan, __("Selected email marketing plans cannot be batch deleted."));
    			}
    		}
    	}
    }

    private function __prepareViewData($planId){

        $this->loadModel('EmailMarketing.EmailMarketingUser');

        $plan = $this->EmailMarketingPlan->browseBy($this->EmailMarketingPlan->primaryKey, $planId);
        $plans = $this->EmailMarketingPlan->findPlanList();

        $this->set(compact('plan', 'plans'));

        return $plan;
    }
}
