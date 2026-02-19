<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * EmailMarketingUsers Controller
 *
 */
class EmailMarketingUsersController extends EmailMarketingAppController {

    public $paginate = array(
        'fields' => array(
            'EmailMarketingUser.id',
            'EmailMarketingUser.email_marketing_plan_id',
            'EmailMarketingUser.free_emails',
            'User.parent_id',
            'User.first_name',
            'User.last_name',
            'User.company',
            'User.phone',
        	'User.active'
        ),
        'joins' => array(
            array(
                'table' => 'users',
                'alias' => 'User',
                'type' => 'inner',
                'conditions' => array(
                    'User.id = EmailMarketingUser.user_id'
                )
            )
        ),
    	'order'      => array("EmailMarketingUser.id" => "DESC"),
        'limit'      => 10,
        'contain'    => false
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('EmailMarketing.EmailMarketingPlan');
    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index($planId = null) {

        if (!$this->EmailMarketingPlan->exists($planId)) {

            throw new NotFoundRecordException($this->modelClass);
        }
        $this->set('planId', $planId);

        $plan = $this->EmailMarketingPlan->browseBy($this->EmailMarketingPlan->primaryKey, $planId);
        $this->set('plan', $plan);

        $this->paginate['conditions'] = array(
            'EmailMarketingUser.email_marketing_plan_id' => $planId
        );
        $this->Paginator->settings = $this->paginate;

        $this->DataTable->mDataProp = true;
    	$this->set('response', $this->DataTable->getResponse());
    	$this->set('_serialize','response');
    	$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingUser.id']);

    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->EmailMarketingUser->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

        $user = $this->EmailMarketingUser->browseBy($this->EmailMarketingUser->primaryKey, $id, array('EmailMarketingPlan'));
		$this->set('user', $user);

	}

/**
 * add method
 *
 * @return void
 */
    public function admin_add($planId = null) {

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

    		$this->set('userId', $userServiceAccountId);

    	}else{

	    	if (!$this->EmailMarketingPlan->exists($planId)) {

	            throw new NotFoundRecordException($this->modelClass);
	        }
    	}

        if ($this->request->is('post') && isset($this->request->data["EmailMarketingUser"]) && !empty($this->request->data["EmailMarketingUser"])) {

        	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

        		if(empty($this->request->data["EmailMarketingUser"]["user_id"]) || $this->request->data["EmailMarketingUser"]["user_id"] != $userServiceAccountId){

        			throw new ForbiddenActionException($this->modelClass, "add");
        		}

        	}else{

        		if($this->request->data["EmailMarketingUser"]["user_id"] == $userServiceAccountId){

        			throw new ForbiddenActionException($this->modelClass, "add"); // Staff cannot use service
        		}
        	}

            if ($emailMarketingUserRecordId = $this->EmailMarketingUser->saveUser($this->request->data)) {
                $this->_showSuccessFlashMessage($this->EmailMarketingUser);

                $this->loadModel('User');
                $clientSuperUserId = $this->EmailMarketingUser->emailMarketingUserIdToSuperUserId($emailMarketingUserRecordId);
                $serviceUser = $this->User->find('first', array(
                	'conditions' => array(
	                	'parent_id' => $clientSuperUserId,
                		'group_id'	=> Configure::read('EmailMarketing.client.group.id')
	                ),
                	'contain' => false
                ));
                $serviceUser["User"]['active'] = 0; // Disable the new user before payment
                unset($serviceUser["User"]['password']);
                if($this->User->updateUser($serviceUser['User']['id'], $serviceUser)){
                	$newPlanId = $this->request->data["EmailMarketingUser"]['email_marketing_plan_id'];
                	$planDetails = $this->EmailMarketingPlan->find('all', array('contain' => false));
                	$tempInvoiceId = $this->EmailMarketingUser->switchPlan($clientSuperUserId, $newPlanId, $planDetails);
                	if(!empty($clientSuperUserId) && !empty($tempInvoiceId)){
                		return $this->redirect('/admin/dashboard#/admin/email_marketing/email_marketing_plans/alter/' .$newPlanId .'/' .$tempInvoiceId .'/' .$clientSuperUserId .'?iframe=1&action=no-submit', 301);
                	}else{
                		$this->_showWarningFlashMessage($this->EmailMarketingUser, __("Email marketing user has been created, but we cannot charge the plan fee. This has been reported, sorry about the inconvenience."));

                		$logType 	 = Configure::read('Config.type.emailmarketing');
                		$logLevel 	 = Configure::read('System.log.level.critical');
                		$logMessage  = __('User (#' .$this->superUserId .') cannot charge the plan fee. (Passed email marketing user ID: ' .$emailMarketingUserRecordId .', new plan ID: ' .$newPlanId .')');
                		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
                	}
                }else{

                	$logType 	 = Configure::read('Config.type.emailmarketing');
                	$logLevel 	 = Configure::read('System.log.level.critical');
                	$logMessage  = __('User (#' .$this->superUserId .') cannot disable new service user before payment. (Passed service user ID: ' .$serviceUser['User']['id'] .')');
                	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
                }

            } else {
                $this->_showErrorFlashMessage($this->EmailMarketingUser);
            }
        }

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

        	$plan = $clients = null;

        }else{

        	$this->loadModel('User');
        	$query = $this->paginate;
        	unset($query["limit"]);
        	$query['fields'] = array('User.parent_id');
        	$emailPlanUsers = $this->EmailMarketingUser->find('all',$query);
        	$emailPlanSuperUserIds = Set::classicExtract($emailPlanUsers, '{n}.User.parent_id');
        	$clients = $this->User->find('list', array(
        		'fields' => array('User.id','User.name'),
        		'conditions' => array(
        			'User.group_id' => Configure::read('System.client.group.id'),
        			'User.active'	=> 1,
        			'NOT' 			=> array('User.id' => $emailPlanSuperUserIds)
        		)
        	));

        	$plan = $this->EmailMarketingPlan->browseBy($this->EmailMarketingPlan->primaryKey, $planId);
        }

        $planList = $this->EmailMarketingPlan->findPlanList();

        $freeEmails = $this->_getSystemDefaultConfigSetting("FreeEmails", Configure::read('Config.type.emailmarketing'));

        $this->set(compact('plan', 'planList', 'clients', 'freeEmails'));
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_edit($id = null, $planId = null) {

        if (!$this->EmailMarketingPlan->exists($planId)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        // Client cannot edit other email marketing user account
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	$emailMarketingUserId = $this->EmailMarketingUser->superUserIdToEmailMarketingUserId($userServiceAccountId);
        	if(empty($emailMarketingUserId) || $id != $emailMarketingUserId){

        		throw new ForbiddenActionException($this->modelClass, "edit");
        	}
        }

        if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["EmailMarketingUser"]) && !empty($this->request->data["EmailMarketingUser"])) {

        	// To prevent client directly calling this action from URL, with a plan switch
        	$newPlanId = '';
        	$existingEmailMarketingUser = $this->EmailMarketingUser->browseBy($this->EmailMarketingUser->primaryKey, $id);
        	if(!empty($this->request->data["EmailMarketingUser"]['email_marketing_plan_id']) && $this->request->data["EmailMarketingUser"]['email_marketing_plan_id'] != $existingEmailMarketingUser["EmailMarketingUser"]['email_marketing_plan_id']){
        		$newPlanId = $this->request->data["EmailMarketingUser"]['email_marketing_plan_id'];
        		$this->request->data["EmailMarketingUser"]['email_marketing_plan_id'] = $existingEmailMarketingUser["EmailMarketingUser"]['email_marketing_plan_id'];
        	}

            if ($this->EmailMarketingUser->updateUser($id, $this->request->data)) {

//                 $this->_showSuccessFlashMessage($this->EmailMarketingUser);

                if(!empty($newPlanId)){
                	$clientSuperUserId = $this->EmailMarketingUser->emailMarketingUserIdToSuperUserId($this->request->data["EmailMarketingUser"]['id']);
                	$planDetails = $this->EmailMarketingPlan->find('all', array('contain' => false));
                	$tempInvoiceId = $this->EmailMarketingUser->switchPlan($clientSuperUserId, $newPlanId, $planDetails);
                	if(!empty($clientSuperUserId) && !empty($tempInvoiceId)){
                		return $this->redirect('/admin/dashboard#/admin/email_marketing/email_marketing_plans/alter/' .$newPlanId .'/' .$tempInvoiceId .'/' .$clientSuperUserId .'?iframe=1');
                	}else{
                		$this->_showWarningFlashMessage($this->EmailMarketingUser, __("Email marketing user has been updated, but we cannot switch the plan. This has ben reported, sorry about the inconvenience."));

                		$logType 	 = Configure::read('Config.type.emailmarketing');
                		$logLevel 	 = Configure::read('System.log.level.critical');
                		$logMessage  = __('User (#' .$this->superUserId .') cannot switch client plan. (Passed email marketing user ID: ' .$id .', new plan ID: ' .$newPlanId .')');
                		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
                	}
                }
                return $this->redirect('/admin/dashboard#/email_marketing/email_marketing_users/index/' .$planId .'/edit');
            } else {
                $this->_showErrorFlashMessage($this->EmailMarketingUser);
            }
        }

        $this->request->data = $this->EmailMarketingUser->browseBy($this->EmailMarketingUser->primaryKey, $id);
        $plan = $this->EmailMarketingPlan->browseBy($this->EmailMarketingPlan->primaryKey, $planId);

        $planList = $this->EmailMarketingPlan->findPlanList();

        $freeEmails = $this->_getSystemDefaultConfigSetting("FreeEmails", Configure::read('Config.type.emailmarketing'));

        $this->set(compact('plan', 'planList', 'freeEmails'));
    }

/**
 * addPrepaidFunds method
 *
 * @throws NotFoundException
 * @param int $id
 * @return void
 */
    public function admin_addPrepaidFunds($id = null, $tempInvoiceId = null) {
    	if (!$this->EmailMarketingUser->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	// Client cannot edit other email marketing user account
    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		$emailMarketingUserId = $this->EmailMarketingUser->superUserIdToEmailMarketingUserId($userServiceAccountId);
    		if(empty($emailMarketingUserId) || $id != $emailMarketingUserId){

    			throw new ForbiddenActionException($this->modelClass, "add ? prepaid fund");
    		}
    	}

    	$userId = $this->superUserId; // Get Client group user ID, not service group user ID

    	if ($this->request->is('post') || $this->request->is('put')) {
    		if(empty($tempInvoiceId)){
    			$paymentCode = Configure::read('Payment.code.email_marketing.prepaid');
    			$emailMarketingAccountId = $this->EmailMarketingUser->superUserIdToEmailMarketingUserAccountId($userId);
    			$emailMarketingUser = $this->EmailMarketingUser->browseBy("user_id", $emailMarketingAccountId, false);

    			$needToPayAmount = $this->request->data['EmailMarketingUser']['deposit_amount'];
    			$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] += $needToPayAmount;

    			$receipt = array(
	    			'PaymentTempInvoice' => array(
	    				'user_id'				=> $userId,
	    				'is_auto_created' 		=> 1,
	    				'purchase_code' 		=> $paymentCode,
	    				'amount'				=> $needToPayAmount,
	    				'content'				=> __('Add prepaid funds'),
	    				'created_by'			=> $userId,
	    				'created' 				=> date('Y-m-d H:i:s'),
	    				'due_date'				=> date('Y-m-d'),
	    				'related_update_data' 	=> serialize(array('plugin' => 'EmailMarketing', 'class' => 'EmailMarketingUser', 'id' => $emailMarketingUser['EmailMarketingUser']['id'], 'data' => $emailMarketingUser))
	    			)
	    		);

    			$this->loadModel('Payment.PaymentTempInvoice');
    			$tempInvoiceId = $this->PaymentTempInvoice->saveTempInvoice($receipt);
    		}
    	}

    	if(empty($tempInvoiceId)){

    		// At first, only show a popup window asking for deposit funds amount

    	}else{
	    	$User = ClassRegistry::init('User');
	    	$userInfo = $User->browseBy($User->primaryKey, $userId, array("Address"));

	    	$tempInvoice = $this->PaymentTempInvoice->browseBy('id', $tempInvoiceId, false);
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
	    	$this->EmailMarketingUser->id = $id;
	        if (!$this->EmailMarketingUser->exists()) {

	            throw new NotFoundRecordException($this->modelClass);
	        }

	        $user = $this->EmailMarketingUser->browseBy("id", $id);
	        $planId = $user['EmailMarketingUser']['email_marketing_plan_id'];

	        $this->loadModel('User');
	        if($this->User->deleteUser($user['EmailMarketingUser']['user_id'])){
	            $this->_showSuccessFlashMessage($this->EmailMarketingUser);
	        }else{
	        	$this->_showErrorFlashMessage($this->EmailMarketingUser);
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
    			$this->loadModel('User');
    			foreach($this->request->data['batchIds'] as $id){

    				$this->EmailMarketingUser->id = $id;
			        if (!$this->EmailMarketingUser->exists()) {

			            throw new NotFoundRecordException($this->modelClass);
			        }

			        $user = $this->EmailMarketingUser->browseBy("id", $id);
			        $planId = $user['EmailMarketingUser']['email_marketing_plan_id'];

			        if(!$this->User->deleteUser($user['EmailMarketingUser']['user_id'])){

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing user. (Passed email marketing user ID: ' .$user['EmailMarketingUser']['user_id'] .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			        	$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->EmailMarketingUser, __("Selected email marketing users have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->EmailMarketingUser, __("Selected email marketing users cannot be batch deleted."));
    			}
    		}
    	}
    }

    public function admin_getClients(){

    	$this->_prepareAjaxPostAction();

    	if(($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')){

    		$term = $this->request->data['term'];

    		$conditions = array(
    			'OR' => array(
	    			'SuperUser.first_name LIKE' => '%' .$term .'%',
    				'SuperUser.last_name LIKE' => '%' .$term .'%',
    				'SuperParentUser.email LIKE' => '%' .$term .'%',
	    		)
    		);
    		$userList = $this->EmailMarketingUser->getUserList(null, $conditions);
    		echo json_encode($userList);
    	}

    	exit();
    }
}
