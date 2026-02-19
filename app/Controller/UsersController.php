<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public $paginate = array(
    	'fields' 	=> array('User.id, User.parent_id, User.first_name, User.last_name, User.active, User.company, User.phone, User.modified, Group.name'),
    	'joins' 	=> array(
    		array(
    			'table' => 'groups',
    			'alias' => 'Group',
    			'type' => 'inner',
    			'conditions' => array(
    				'Group.id = User.group_id'
    			)
    		),
    	),
        'order'     => array("User.id" => "DESC"),
    	'limit' 	=> 10,
    );

    public function beforeFilter() {
        $this->Auth->allow('admin_login');
        $this->Auth->allow('admin_logout');
        $this->Auth->allow('register');
        $this->Auth->allow('activateAccount');
        $this->Auth->allow('forgetPassword');
        $this->Auth->allow('resetPassword');

        $this->Security->unlockedActions = array(
        	'admin_login',
        	'admin_logout',
        	'register',
        	'activateAccount',
        	'forgetPassword',
        	'resetPassword',
        	'activateAccountAfterPayment'
        );

        parent::beforeFilter();
    }

    public function admin_login() {

    	$companyName 		= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    	$companyWebsiteURL 	= 'http://' .$this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    	$companyLogo 		= $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
    	$this->set ( compact ( 'companyName', 'companyWebsiteURL', 'companyLogo' ) );

    	if ($this->request->is('post')) {

            if ($this->Auth->login() && !empty($this->request->data['g-recaptcha-response']) && $this->__validateGreCaptcha($this->request->data)) {

                // Logout user if the account is not active
                if($this->Session->read('Auth.User.active') == 0){
                	$this->admin_logout(__('This account is not active. Please contact ' .$companyName .' for further information.'), 'warning');
                    exit();
                }

                // If the same user has been logged in, log it out before new login. Not allow multiple login.
                $loggedInSession = $this->Session->read('Auth.User.session_id');
                $currentSessionId = $this->Session->id();
                if($loggedInSession && $this->User->checkMultipleLogin($loggedInSession, $currentSessionId)){

					// 1. stop current session
					session_commit();

                	// 2. hijack then destroy session specified.
                	session_id($loggedInSession);
                	session_start();

                	//TODO try to push a warning message to the previous logged in account. The following way is not working.
//                 	$this->_showWarningFlashMessage(null, __('Your account has been logged in somewhere else. You will be logged out. If that is not you, please report this issue. Keep in mind that we do not allow multiple login.'));

                	session_destroy();
                	session_commit();

                	// 3. restore current session id. If don't restore it, your current session will refer to the session you just destroyed!
                	session_id($currentSessionId);
                	session_start();

                }

                $this->User->recordLoginSession($this->Session->read('Auth.User.id'), $currentSessionId);

                $this->User->recordLogin($this->Session->read('Auth.User'), $this->__getUserRemoteIP());

                /*
                 * Map all associated accounts together.
                 * Because one user can only has one group (required by CakePHP ACL),
                 * we have to create multiple user account for each group. And in login process,
                 * we need to log all associate account in.
                 */
                $userParentId = $this->Session->read('Auth.User.parent_id');
                $userParentId = empty($userParentId) ? $this->Session->read('Auth.User.id') : $userParentId;
                $associatedUsers = $this->User->getAssociateUsers($userParentId);
                $this->Session->write('AssociatedUsers', $associatedUsers);

                $redirectUrl = $this->Auth->redirect();

                $logType 	 = Configure::read('Config.type.system');
                $logLevel 	 = Configure::read('System.log.level.info');
                $logMessage  = __('Successfully logged in.');
                $this->Log->addLogRecord($logType, $logLevel, $logMessage);

                return $this->redirect($redirectUrl);

            } else {
                $this->_showErrorFlashMessage(null, __('Your sign in details was incorrect.'));
            }
        }

        $this->__prepareLoginView();
        $this->render('signio_register', 'admin_wrapper');
    }

    public function admin_logout($message = null, $status = null) {

    	if(empty($status)){
    		$status = Configure::read('System.variable.success');
    	}

    	$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    	$companyLogo = $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
    	$this->set ( compact ( 'companyName', 'companyLogo' ) );

        $this->layout = "admin_wrapper";
        $this->Session->destroy();
        $message = (empty($message) ? __('See you next time.') : $message);
        $this->_showFlashMessage($status, null, $message);
        return $this->redirect($this->Auth->logout());
    }

/**
 * register method
 *
 * @return void
 */
    public function register() {

    	if ($this->request->is('post') && isset($this->request->data["User"]) && !empty($this->request->data["User"]) && !empty($this->request->data['g-recaptcha-response']) && $this->__validateGreCaptcha($this->request->data)) {

    		$allowedServices = array(
    			Configure::read('EmailMarketing.client.group.id'),
    			Configure::read('LiveChat.client.group.id')
    		);
    		if(in_array($this->request->data["User"]["service_id"], $allowedServices)){

    			$this->User->validate = Hash::merge(
    					$this->User->validate,
    					array(
    						'agreement' => array(
    							'rule' 		 => array('notempty'),
    							'allowEmpty' => false,
    							'required'   => true,
    							'last'       => false,
    							'message' 	 => 'Please agree with the User Agreement before register'
    						)
    					)
    			);

    			unset($this->User->validate['email']['confirmed']);
    			unset($this->User->validate['first_name']);
    			unset($this->User->validate['last_name']);
    			unset($this->User->validate['phone']);
    			if ($newUserId = $this->User->saveUser($this->request->data)) {
    				$this->__addNewUserProcess($newUserId, (isset($this->request->data["User"]["service_id"]) ? $this->request->data["User"]["service_id"] : null), true);
    			} else {
    				$this->_showErrorFlashMessage($this->User);
    			}

    		}else{

    			$this->_showErrorFlashMessage($this->User);

    		}

    	}

    	$companyName 		= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    	$companyWebsiteURL 	= 'http://' .$this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    	$companyLogo 		= $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
    	$this->set ( compact ( 'companyName', 'companyWebsiteURL', 'companyLogo' ) );

    	$this->__prepareLoginView();
        $this->render('signio_register', 'admin_wrapper');
    }

    public function activateAccount($token){
    	$user = $this->User->browseBy('token', $token, false);

    	$companyName 		= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    	$companyWebsiteURL 	= 'http://' .$this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    	$companyLogo 		= $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
    	$this->set ( compact ( 'companyName', 'companyWebsiteURL', 'companyLogo' ) );

        if(!empty($user)){
        	if((time() - strtotime($user['User']['modified'])) > 15 * 60 ){
        		$this->admin_logout(__('Activate request is not valid now. Please contact ' .$companyName .' to activate your account.'), Configure::read('System.variable.warning'));
                return;
        	}else{
                $associatedUsers = $this->User->getAssociateUsers($user['User']["id"]);
                foreach($associatedUsers as $user){
                	$this->User->id = $user['User']["id"];
                    $this->User->saveField('active', 1);
                    $this->User->saveField('token', null);
                }

                $this->_showSuccessFlashMessage(null,__('Your account has been activated.'));
                $this->__prepareLoginView();
                $this->render('signio_register', 'admin_wrapper');
        	}
        }else{
        	$this->admin_logout(__('The account you wanted to activate doesn\'t exist. This action has been reported to ' .$companyName .'.'), Configure::read('System.variable.warning'));

        	return;
        }
    }

    public function forgetPassword(){

    	$companyName 		= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    	$companyWebsiteURL 	= 'http://' .$this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    	$companyLogo 		= $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
    	$this->set ( compact ( 'companyName', 'companyWebsiteURL', 'companyLogo' ) );

        if ($this->request->is('post') && isset($this->request->data["User"]["email"]) && !empty($this->request->data["User"]["email"]) && !empty($this->request->data['g-recaptcha-response']) && $this->__validateGreCaptcha($this->request->data)) {
        	$user = $this->User->browseBy("email", $this->request->data["User"]["email"], false);
            if(isset($user["User"]["id"]) && !empty($user["User"]["id"])){
              	if(intval($user["User"]["active"]) == 0){

               		$this->_showErrorFlashMessage(null, __('This account is inactive. Please contact ' .$companyName .' for further information.'));

               	}else{
               		$postData = array(
               			'data' => array(
               				'User' => array(
               					'id' => $user["User"]["id"]
               				)
               			)
               		);

                    if($this->requestAction(DS .'system_email' .DS .'sendResetPasswordEmail' .DS .$user["User"]["id"], $postData)){
                       	$this->_showSuccessFlashMessage(null, __('The reset password email has been sent, please check your email and reset the password within 4 hours.'));
                        return $this->redirect(DS ."login");
                    }else{
                       	$this->_showErrorFlashMessage(null, __('Sorry! We cannot send the reset password email for now, please wait for some time and try again.'));

                       	$logType 	 = Configure::read('Config.type.system');
                       	$logLevel 	 = Configure::read('System.log.level.critical');
                       	$logMessage  = __('Cannot reset user password. (Provided account user: ' .json_encode($user) .')');
                       	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
                    }
              	}
            }else{

            	$this->_showErrorFlashMessage(null, __('The account doesn\'t exist. Please enter the correct account email.'));

            }
        }

        $this->__prepareLoginView();
        $this->render('signio_register', 'admin_wrapper');
    }

    public function resetPassword($token){

    	$companyName 		= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    	$companyWebsiteURL 	= 'http://' .$this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    	$companyLogo 		= $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
    	$this->set ( compact ( 'companyName', 'companyWebsiteURL', 'companyLogo' ) );

        if ($this->request->is('post') && isset($this->request->data["User"]["password"]) && !empty($this->request->data["User"]["password"]) && !empty($this->request->data['g-recaptcha-response']) && $this->__validateGreCaptcha($this->request->data)) {

        	if($this->request->data["User"]["password"] == $this->request->data["User"]["password_confirm"]){
        		$user = $this->User->browseBy("token", $token, false);
        		if(isset($user["User"]["id"]) && !empty($user["User"]["id"])){
        			if((time() - strtotime($user['User']['modified'])) > 60 * 60 * 4 ){
        				$this->_showWarningFlashMessage(null,__('Reset password link has expired, please regenerate the link.'));
        				return $this->redirect(DS ."account" .DS ."forget_password");
        			}else{
        				$user["User"]["password"] = $this->request->data["User"]["password"];
        				if($this->User->resetPassword($user)){
        					$this->_showSuccessFlashMessage(null,__('Password has been reset.'));
        					return $this->redirect(DS ."login");
        				}else{
        					$this->_showErrorFlashMessage(null, __('We have some difficulties to update your new password. Please wait a while and try again.'));

        					$hashedPassword = Security::hash($this->request->data["User"]["password"], null, true);

        					$logType 	 = Configure::read('Config.type.system');
        					$logLevel 	 = Configure::read('System.log.level.critical');
        					$logMessage  = __('Cannot update user password. (Provided account username: ' .$this->request->data["User"]["email"] .', password: ' .$hashedPassword .')');
        					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
        				}
        			}
        		}else{
        			$this->_showErrorFlashMessage(null, __('The reset password token is not valid. Please regenerate the token.'));

        			return $this->redirect(DS ."account" .DS ."forget_password");
        		}
        	}else{
        		$this->_showErrorFlashMessage(null, __('The passwords entered are not matching each other during password reset process.'));
        	}

        }
        $this->set('token', $token);
        $this->__prepareLoginView();
        $this->render('signio_register', 'admin_wrapper');
    }

    public function activateAccountAfterPayment($paymentId, $paymentCode){

    	if(empty($paymentId) || empty($paymentCode)){
    		exit();
    	}

    	$this->_prepareAjaxPostAction();

    	if(($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')){

    		//TODO update this after we add more payment method

    		$this->loadModel('Payment.PaymentPayPalGateway');

    		$paypalPaymentMethods = Configure::read('Payment.method.paypal');

    		$payment = $this->PaymentPayPalGateway->find('first', array(
    			'fields' => array('PaymentPayer.user_id'),
    			'conditions' => array(
	    			'PaymentPayPalGateway.payment_id' => $paymentId,
    				'PaymentPayPalGateway.status' => Configure::read('Payment.paypal.gateway.status.approved'),
    				'PaymentPayPalGateway.sale_id IS NOT NULL',
	    		),
    			'joins' => array(
    				array(
    					'table' => 'payment_payer',
    					'alias' => 'PaymentPayer',
    					'type' => 'inner',
    					'conditions' => array(
    						'PaymentPayer.id = PaymentPayPalGateway.payment_payer_id',
    						'PaymentPayer.payment_method' => strtoupper($paypalPaymentMethods['paypal'])
    					)
    				)
    			),
    			'contain'    => false
    		));
    		$userId = $payment['PaymentPayer']['user_id'];

    		$serviceName 						= null;
    		$groupId 							= null;
    		$websiteGroupPaymentCodes 			= array_values(Configure::read('Payment.code.website'));
    		$emailMarketingGroupPaymentCodes 	= array_values(Configure::read('Payment.code.email_marketing'));
    		$liveChatGroupPaymentCodes 			= array_values(Configure::read('Payment.code.live_chat'));
    		if(in_array($paymentCode, $websiteGroupPaymentCodes)){
    			$groupId 		= Configure::read('WebDevelopment.client.group.id');
    			$serviceName 	= Configure::read('Config.type.webdevelopment');
    		}elseif(in_array($paymentCode, $emailMarketingGroupPaymentCodes)){
    			$groupId 		= Configure::read('EmailMarketing.client.group.id');
    			$serviceName 	= Configure::read('Config.type.emailmarketing');
    		}elseif(in_array($paymentCode, $liveChatGroupPaymentCodes)){
    			$groupId 		= Configure::read('LiveChat.client.group.id');
    			$serviceName 	= Configure::read('Config.type.livechat');
    		}

    		if($groupId && $userId){
    			$conditions = array('User.parent_id' => $userId, 'User.active' => 0, 'User.group_id' => $groupId);
    			if($this->User->hasAny($conditions)){ // Activate service account after payment

    				$db = $this->User->getDataSource();
    				if($db->update($this->User, array('active'), array(1), $conditions)){

    					$logType 	 = Configure::read('Config.type.system');
    					$logLevel 	 = Configure::read('System.log.level.info');
    					$logMessage  = __('User service (' .Inflector::humanize($serviceName) .') has been activated.');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $userId);

    					if(($this->superUserId == $userId)){

    						$this->_updateUserStatus();
    					}

    					$response = array('status' => Configure::read('System.variable.success'), 'message' => __('Service is activated.'));
    					echo json_encode($response);

    				}else{

    					$logType 	 = Configure::read('Config.type.system');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$userId .') service (#' .$groupId .') cannot be activated after payment. (Provided payment ID: ' .$paymentId .', payment code: ' .$paymentCode .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$response = array('status' => Configure::read('System.variable.error'), 'message' => __('Cannot activate service. Please manually activate the service account.'));
    					echo json_encode($response);
    				}

    			}else{

    				$response = array('status' => Configure::read('System.variable.warning'), 'message' => __('Service has already been activated.'));
    				echo json_encode($response);
    			}
    		}else{
    			exit();
    		}

    	}

    	exit();
    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index() {

    	// Manager can see staff & client accounts under own department
    	$managerGroupChecker = Configure::read('System.manager.group.name');
    	if(stristr($this->superUserGroup, $managerGroupChecker)){

    		if(!isset($this->paginate['conditions'])){
    			$this->paginate['conditions'] = array();
    		}
    		$clientGroupChecker = Configure::read('System.client.group.name');
    		$clientGroupName = str_replace($managerGroupChecker, $clientGroupChecker, $this->superUserGroup);
    		$staffGroupChecker = Configure::read('System.staff.group.name');
    		$staffGroupName = str_replace($managerGroupChecker, $staffGroupChecker, $this->superUserGroup);
    		$this->paginate['conditions'] += array(
    			'Group.name' => array($clientGroupName, $staffGroupName)
    		);
    	}

    	$this->User->recursive = 0;
    	$this->Paginator->settings = $this->paginate;

    	$this->DataTable->mDataProp = true;
    	$this->set('response', $this->DataTable->getResponse());
    	$this->set('_serialize','response');
    	$this->set('defaultSortDir', $this->paginate['order']['User.id']);

    }

    public function admin_listGroupUsers($groupId = null) {

    	if (!$this->User->Group->exists($groupId)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	$this->User->recursive = -1;
    	$this->paginate = array(
    		'conditions' => array(
//     			'User.parent_id IS NULL', // Some service account is appended to the super account and those user records have parent ID
    			'User.group_id' => $groupId
    		),
    		'limit'   => $this->paginate['limit'],
    	);
    	$this->Paginator->settings = $this->paginate;

    	$this->DataTable->mDataProp = true;
    	$this->set('response', $this->DataTable->getResponse());
    	$this->set('_serialize','response');

    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_view($id = null) {

    	if (!$this->User->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	// Manager can see staff & client accounts under own department
    	$managerGroupChecker = Configure::read('System.manager.group.name');
    	if(stristr($this->superUserGroup, $managerGroupChecker) && $id != $this->superUserId){

    		$clientGroupChecker = Configure::read('System.client.group.name');
    		$clientGroupName = str_replace($managerGroupChecker, $clientGroupChecker, $this->superUserGroup);
    		$staffGroupChecker = Configure::read('System.staff.group.name');
    		$staffGroupName = str_replace($managerGroupChecker, $staffGroupChecker, $this->superUserGroup);
    		$user = $this->User->find('first', array(
    			'conditions' => array(
    				'User.id' => $id,
    				'Group.name' => array($clientGroupName, $staffGroupName)
	    		),
    			'contain' 	=> array('Group','Address' => array('Country')),
    		));

    		if(empty($user)){

    			throw new NotFoundRecordException($this->modelClass);
    		}

    	}

    	if(!isset($user)){
    		$user = $this->User->browseBy($this->User->primaryKey, $id, $contain = array('Group','Address' => array('Country')));
    	}

    	// Load addresses for service account
    	if(!empty($user['User']['parent_id'])){
    		$this->loadModel('Address');
    		$addresses = $this->Address->find('all', array(
    			'conditions' => array(
	    			'user_id' => $user['User']['parent_id']
	    		),
    			'contain' => false
    		));

    		if($addresses[0]['Address']['type'] != 'CONTACT'){
    			$newAddresses[] = $addresses[1];
    			$newAddresses[] = $addresses[0];
    			$addresses = $newAddresses;
    		}
    		$user['Address'][] = $addresses[0]['Address'];
    		$user['Address'][] = $addresses[1]['Address'];
    	}

    	$this->set('user', $user);

    }

/**
 * add method
 *
 * @return void
 */
	public function admin_add($preloadGroupId = null) {

        if ($this->request->is('post') && isset($this->request->data["User"]) && !empty($this->request->data["User"])) {
        	if ($newUserId = $this->User->saveUser($this->request->data)) {
            	$this->__addNewUserProcess($newUserId, (isset($this->request->data["User"]["service_id"]) ? $this->request->data["User"]["service_id"] : null), false);
				return $this->redirect(($this->loadInIframe ? '/admin/users/edit/' .$newUserId .'?iframe=1' : '/admin/dashboard#/admin/users'));
			} else {
				$this->_showErrorFlashMessage($this->User);
			}
		}
		$this->loadModel ( 'Country' );
		$groups = $this->User->Group->find('list',
			array(
				'conditions' => array(
					'Group.name NOT LIKE "%' .Configure::read('System.client.group.name') .'%"',
					'Group.name NOT LIKE "%' .Configure::read('System.admin.group.name') .'%"'
				),
				'order' => array('Group.name')
			)
		);
        $countries = $this->Country->find('list');
        $this->set(compact('groups','countries','preloadGroupId'));
        $this->__prepareLoginView();
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

		if (!$this->User->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE && $id != $this->superUserId){

			throw new ForbiddenActionException($this->modelClass, 'edit');
		}

		if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["User"]) && !empty($this->request->data["User"])) {

			if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) || stristr($this->superUserGroup, Configure::read('System.staff.group.name'))){
				$this->request->data['User']['debug_log'] = 0; // Client and staff are not allowed to record debug logs
			}

            if ($this->User->updateUser($id, $this->request->data)) {
				$this->_showSuccessFlashMessage($this->User);
			} else {
				$this->_showErrorFlashMessage($this->User);
			}

		} else {
			$this->request->data = $this->User->browseBy($this->User->primaryKey, $id, array('Address'));
		}

		$this->loadModel ( 'Country' );
		$groups = $this->User->Group->find('list', array('order' => array('Group.name')));
        $countries = $this->Country->find('list');

        $this->set('currentUserGroup', $this->superUserGroup);

        $isProfile = $id == $this->superUserId; // Edit own user details will be treated as profile action

        $this->set(compact('groups', 'countries', 'isProfile'));
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
			$this->User->id = $id;
			if (!$this->User->exists()) {

				throw new NotFoundRecordException($this->modelClass);
			}

			if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE && $id != $this->superUserId){

				throw new ForbiddenActionException($this->modelClass, 'delete');
			}

			if ($this->User->deleteUser($id)) {
				$this->_showSuccessFlashMessage($this->User);
			}else{
				$this->_showErrorFlashMessage($this->User);
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
					$this->User->id = $id;
					if (!$this->User->exists()) {

						throw new NotFoundRecordException($this->modelClass);
						$batchDeleteDone = false;
						break;
					}

					if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE && $id != $this->superUserId){

						throw new ForbiddenActionException($this->modelClass, "batch delete");
					}

					if (!$this->User->deleteUser($id)) {

						$logType 	 = Configure::read('Config.type.system');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('User (#' .$this->superUserId .') cannot delete user account. (Passed user ID: ' .$id .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						$batchDeleteDone = false;
					}
				}
				if($batchDeleteDone){
					$this->_showSuccessFlashMessage($this->User, __("Selected users have been batch deleted."));
				}else{
					$this->_showErrorFlashMessage($this->User, __("Selected users cannot be batch deleted."));
				}
			}
		}
	}

/**
 * Get login, forget password and register view element content
 */
    private function __prepareLoginView(){
    	$this->loadModel ( 'Group' );
    	$services = $this->Group->find('list', array(
    		'fields' => array(
    			$this->Group->primaryKey,
    			'name'
    		),
    		'conditions' => array(
    			$this->Group->primaryKey => array(
    				//Configure::read('WebDevelopment.client.group.id'), //Only staff can register a user as Web Development User, client cannot register it by themselves
    				Configure::read('EmailMarketing.client.group.id'),
    				Configure::read('LiveChat.client.group.id')
	    		)
    		),
    		'contain' => false
    	));

    	// Remove "Clients" from group name
    	foreach($services as $key => &$groupName){
    		$groupName = str_replace(" Clients", "", $groupName);
    	}

    	$this->set(compact('services'));

    	if(!array_key_exists('token', $this->viewVars)){
    		$this->set('token', null);
    	}
    }

/**
 * Save user selected service
 */
    private function __saveServiceForNewUser($userId, $serviceId, $planId = null){

    	if(empty($userId) || empty($serviceId)){
    		return false;
    	}else{
    		// Create associated user(s)
    		$result = true;
    		switch(strval($serviceId)){
    			case Configure::read('WebDevelopment.client.group.id'): // Web Development Clients

    				$this->loadModel ( 'WebDevelopment.WebDevelopmentUser' );
    				$result = $this->WebDevelopmentUser->saveUser($userId);

    				if($result){

    					$logType 	 = Configure::read('Config.type.user');
    					$logLevel 	 = Configure::read('System.log.level.info');
    					$logMessage  = __('Successfully enable web development services.');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $userId);

    				}else{

    					$logType 	 = Configure::read('Config.type.user');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('Cannot enable web development services. (User: #' .$userId .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    				}

    				break;
    			case Configure::read('LiveChat.client.group.id'): // Live Chat Clients

    				// Save assiciated and service user
    				$this->loadModel ( 'LiveChat.LiveChatUser' );
    				$this->loadModel ( 'LiveChat.LiveChatPlan' );
    				$plan 	= $this->LiveChatPlan->browseBy("id", 1, false); //TODO Assign the user to the trial plan by default for now. Use $planid to assign selected plan
    				$data 	= array(
    					"LiveChatUser" => array(
    						"user_id" 					=> $userId,
    						"live_chat_plan_id" 		=> $plan['LiveChatPlan']['id'],
    						"payment_cycle"				=> Configure::read('Payment.pay.cycle.manual'),
    						"last_pay_date"				=> date('Y-m-d'),
    						"next_pay_date"				=> date('Y-m-d', strtotime('+30 days')), // Let client to have 30 days free trial
    						'live_chat_plan_slug'		=> $this->LiveChatPlan->getSlug($plan['LiveChatPlan']['id'])
    					)
    				);

    				$result = $this->LiveChatUser->saveUser($data);

    				if($result){

    					$logType 	 = Configure::read('Config.type.user');
    					$logLevel 	 = Configure::read('System.log.level.info');
    					$logMessage  = __('Successfully enable live chat services.');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $userId);

    				}else{

    					$logType 	 = Configure::read('Config.type.user');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('Cannot enable live chat services. (User: #' .$userId .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    				}

    				break;
    			case Configure::read('EmailMarketing.client.group.id'): // Email Marketing Clients

    				// Save assiciated and service user
    				$this->loadModel ( 'EmailMarketing.EmailMarketingUser' );
    				$this->loadModel ( 'EmailMarketing.EmailMarketingPlan' );
    				$plan 	= $this->EmailMarketingPlan->browseBy("pay_per_email", 1, false); // Assign the user to the prepaid plan by default
    				$data 	= array(
    					"EmailMarketingUser" => array(
    						"user_id" 					=> $userId,
    						"email_marketing_plan_id" 	=> $plan['EmailMarketingPlan']['id'],
    						"free_emails"				=> $this->_getSystemDefaultConfigSetting("FreeEmails", Configure::read('Config.type.emailmarketing')),
    						"payment_cycle"				=> Configure::read('Payment.pay.cycle.manual')
    					)
    				);

    				$result = $this->EmailMarketingUser->saveUser($data);

    				if($result){

    					$logType 	 = Configure::read('Config.type.user');
    					$logLevel 	 = Configure::read('System.log.level.info');
    					$logMessage  = __('Successfully enable email marketing services.');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $userId);

    				}else{

    					$logType 	 = Configure::read('Config.type.user');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('Cannot enable email marketing services. (User: #' .$userId .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    				}

    				break;
    		}
    		return $result;
    	}
    }

/**
 * Additional process need to be done when new user is created
 */
    private function __addNewUserProcess($newUserId, $serviceId, $isRegisterProcess = true, $planId = null){

    	$userCreated = true;

    	// Record user service
    	if(!empty($serviceId) && $userCreated){
    		$userCreated = $this->__saveServiceForNewUser($newUserId, $serviceId, $planId);
    	}

    	// Send activate email
    	$emailSendingResultMessage = null;
    	if($userCreated){
    		$postData = array(
    			'data' => array(
    				'User' => array(
    					'id' => $newUserId
    				)
    			)
    		);

    		$response = $this->requestAction('/system_email/sendNewUserActivateEmail/' .$newUserId, $postData);
    		$userCreated = (isset($response['status']) && $response['status'] == Configure::read('System.variable.success')) ? true : false;
    		$emailSendingResultMessage = $response['message'];
    	}

    	if($userCreated){
    		$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    		$this->_showSuccessFlashMessage($this->User, ($isRegisterProcess ? __("Thank you for joining " .$companyName) : $emailSendingResultMessage));

    		if($isRegisterProcess){
    			return $this->redirect(DS .'admin' .DS .'users' .DS .'login');
    		}
    	}else{

    		if(null != $emailSendingResultMessage){

    			// If user is created successfully and we just cannot send activation email, notifiy the IT and support and manually sending that email
    			$responseMessage = empty($emailSendingResultMessage) ? "" : $emailSendingResultMessage ."<br />";
    			$responseMessage .= $isRegisterProcess ? __('Your account has been set up correctly. Due to sending activation email issue, we will manually send that email again in the next hour. Sorry about the inconvience.') : __("Please manually send the activation email to the added user within one hour.");
    			$this->_showErrorFlashMessage($this->User, $responseMessage);
	    		if($isRegisterProcess){
	    			return $this->redirect(DS .'admin' .DS .'users' .DS .'login');
	    		}

    		}else{
    			// Roll back (delete user in cascade way, and it will auto delete all the associated records, like addresses)
    			$this->User->deleteUser($newUserId);
    			$this->_showErrorFlashMessage($this->User, __('The user cannot be set up correctly. To protect client privacy, all the data saved previously has been cleared.'));
    		}
    	}
    }

/**
 * Get user remote IP address.
 * Put this into a function is because this process might be different among hosting servers.
 */
    private function __getUserRemoteIP(){
    	return $this->request->clientIp();
    }

/**
 * Verify user recaptcha
 * @param string $response
 */
    private function __validateGreCaptcha($response){

    	$payload = array(
    		'secret' 	=> Configure::read('System.recaptcha.secret'),
    		'response' 	=> $response['g-recaptcha-response']
    	);

    	$remoteIP = $this->__getUserRemoteIP();
    	if(!empty($remoteIP) && $remoteIP != "127.0.0.1" && strtoupper($remoteIP) != "LOCALHOST" && filter_var($remoteIP, FILTER_VALIDATE_IP)){
    		$payload['remoteip'] = $remoteIP;
    	}

    	$options = array(
    		'http' => array(
	    		'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
    			'method' => 'POST',
    			'content' => http_build_query($payload)
	    	)
    	);
    	$context = stream_context_create($options);
    	$result = file_get_contents(Configure::read('System.recaptcha.verifyURL'), false, $context);

    	if($result === FALSE){

    		return false;

    	}else{

    		$result = json_decode($result, true);

    		if(empty($result['error-codes'])){

    			if($result['success']){

    				return true;

    			}else{

    				$logType 	 = Configure::read('Config.type.user');
    				$logLevel 	 = Configure::read('System.log.level.critical');
    				$logMessage  = __('reCaptcha request failed: unknown error') .' (' .__('Username: ') .$response['User']['email'] .', ' .__('Timestamp: ') .CakeTime::i18nFormat(date('Y-m-d H:i:s'), '%x %X') .', ' .__("Returned Results: ") .json_encode($result) .')';
    				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    				return false;
    			}

    		}else{

    			$logType 	 = Configure::read('Config.type.user');
    			$logLevel 	 = Configure::read('System.log.level.warning');
    			$logMessage = is_array($result['error-codes']) ? implode(" | ", $result['error-codes']) : $result['error-codes'];
    			$logMessage = __('reCaptcha validation failed: ') .$logMessage .' (' .__('username: ') .$response['User']['email'] .', ' .__('timestamp: ') .CakeTime::i18nFormat(date('Y-m-d H:i:s'), '%x %X') .')';
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    			return false;
    		}
    	}

    }

}
