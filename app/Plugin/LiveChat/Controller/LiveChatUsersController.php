<?php
App::uses('LiveChatAppController', 'LiveChat.Controller');
/**
 * LiveChatUsers Controller
 *
 */
class LiveChatUsersController extends LiveChatAppController {

    public $paginate = array(
        'fields' => array(
            'LiveChatUser.id',
            'LiveChatUser.live_chat_plan_id',
            'LiveChatUser.operator_amount',
            'LiveChatUser.payment_cycle',
        	'LiveChatUser.last_pay_date',
        	'LiveChatUser.next_pay_date',
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
                    'User.id = LiveChatUser.user_id'
                )
            )
        ),
    	'conditions' => array(
        	'LiveChatUser.user_id IS NOT NULL'
        ),
    	'order'      => array("LiveChatUser.id" => "DESC"),
        'limit'      => 10,
        'contain'    => false
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->loadModel('LiveChat.LiveChatPlan');
    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index($planId = null) {

        if (!$this->LiveChatPlan->exists($planId)) {

            throw new NotFoundRecordException($this->modelClass);
        }
        $this->set('planId', $planId);

        $plan = $this->LiveChatPlan->browseBy($this->LiveChatPlan->primaryKey, $planId);
        $this->set('plan', $plan);

        $this->paginate['conditions'] += array(
            'LiveChatUser.live_chat_plan_id' => $planId
        );
        $this->Paginator->settings = $this->paginate;

        $this->DataTable->mDataProp = true;
    	$this->set('response', $this->DataTable->getResponse());
    	$this->set('_serialize','response');
    	$this->set('defaultSortDir', $this->paginate['order']['LiveChatUser.id']);

    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->LiveChatUser->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

        $user = $this->LiveChatUser->browseBy($this->LiveChatUser->primaryKey, $id, array('LiveChatPlan'));
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

    		if (!$this->LiveChatPlan->exists($planId)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}
    	}

        if ($this->request->is('post') && isset($this->request->data["LiveChatUser"]) && !empty($this->request->data["LiveChatUser"])) {

        	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

        		if(empty($this->request->data["LiveChatUser"]["user_id"]) || $this->request->data["LiveChatUser"]["user_id"] != $userServiceAccountId){

        			throw new ForbiddenActionException($this->modelClass, "add");
        		}

        	}else{

        		if($this->request->data["LiveChatUser"]["user_id"] == $userServiceAccountId){

        			throw new ForbiddenActionException($this->modelClass, "add"); // Staff cannot use service
        		}
        	}

        	if(empty($planId)){
        		$planId = @$this->request->data["LiveChatUser"]['live_chat_plan_id'];
        	}

        	if(empty($this->request->data["LiveChatUser"]['live_chat_plan_slug'])){
        		$this->request->data["LiveChatUser"]['live_chat_plan_slug'] = $this->LiveChatPlan->getSlug($planId);
        	}

            if ($liveChatUserRecordId = $this->LiveChatUser->saveUser($this->request->data)) {
                $this->_showSuccessFlashMessage($this->LiveChatUser, __("Live Chat service is about to enabled. Please pay for the service fee to activate it."));

                $this->loadModel('User');
                $clientSuperUserId = $this->LiveChatUser->liveChatUserIdToSuperUserId($liveChatUserRecordId);
                $serviceUser = $this->User->find('first', array(
                	'conditions' => array(
                		'parent_id' => $clientSuperUserId,
                		'group_id'	=> Configure::read('LiveChat.client.group.id')
                	),
                	'contain' => false
                ));
                $serviceUser["User"]['active'] = 0; // Disable the new user before payment
                unset($serviceUser["User"]['password']);
                if($this->User->updateUser($serviceUser['User']['id'], $serviceUser)){
                	$newPlanId = $this->request->data["LiveChatUser"]['live_chat_plan_id'];
                	$planDetails = $this->LiveChatPlan->find('all', array('contain' => false));
                	$tempInvoiceId = $this->LiveChatUser->switchPlan($clientSuperUserId, $newPlanId, $planDetails);
                	if(!empty($clientSuperUserId) && !empty($tempInvoiceId)){

                		return $this->redirect('/admin/dashboard#/admin/live_chat/live_chat_plans/alter/' .$newPlanId .'/' .$tempInvoiceId .'/' .$clientSuperUserId .'?iframe=1&action=no-submit', 301);

                	}else{
                		$this->_showWarningFlashMessage($this->LiveChatUser, __("Live chat user has been created, but we cannot charge the plan fee. This has been reported, sorry about the inconvenience."));

                		$logType 	 = Configure::read('Config.type.livechat');
                		$logLevel 	 = Configure::read('System.log.level.critical');
                		$logMessage  = __('User (#' .$this->superUserId .') cannot charge the plan fee. (Passed live chat user ID: ' .$liveChatUserRecordId .', new plan ID: ' .$newPlanId .')');
                		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
                	}

                }else{

                	$logType 	 = Configure::read('Config.type.livechat');
                	$logLevel 	 = Configure::read('System.log.level.critical');
                	$logMessage  = __('User (#' .$this->superUserId .') cannot disable new service user before payment. (Passed service user ID: ' .$serviceUser['User']['id'] .')');
                	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
                }

            } else {
                $this->_showErrorFlashMessage($this->LiveChatUser);
            }
        }

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

        	$plan = $clients = null;

        }else{

        	$this->loadModel('User');
        	$query = $this->paginate;
        	unset($query["limit"]);
        	$query['fields'] = array('User.parent_id');
        	$liveChatPlanUsers = $this->LiveChatUser->find('all',$query);
        	$liveChatPlanSuperUserIds = Set::classicExtract($liveChatPlanUsers, '{n}.User.parent_id');
        	$clients = $this->User->find('list', array(
        		'fields' => array('User.id','User.name'),
        		'conditions' => array(
        			'User.group_id' => Configure::read('System.client.group.id'),
        			'User.active'	=> 1,
        			'NOT' 			=> array('User.id' => $liveChatPlanSuperUserIds)
        		)
        	));

        	$plan = $this->LiveChatPlan->browseBy($this->LiveChatPlan->primaryKey, $planId);
        }

        $planList = $this->LiveChatPlan->findPlanList();

        $this->set(compact('plan', 'planList', 'clients'));
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_edit($id = null, $planId = null) {

        if (!$this->LiveChatPlan->exists($planId)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        // Client cannot edit other live chat user account
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	$liveChatUserId = $this->LiveChatUser->superUserIdToLiveChatUserId($userServiceAccountId);
        	if(empty($liveChatUserId) || $id != $liveChatUserId){

        		throw new ForbiddenActionException($this->modelClass, "edit");
        	}
        }

        if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["LiveChatUser"]) && !empty($this->request->data["LiveChatUser"])) {

        	// To prevent client directly calling this action from URL, with a plan switch
        	$newPlanId = '';
        	$existingLiveChatUser = $this->LiveChatUser->browseBy($this->LiveChatUser->primaryKey, $id);
        	if(!empty($this->request->data["LiveChatUser"]['live_chat_plan_id']) && $this->request->data["LiveChatUser"]['live_chat_plan_id'] != $existingLiveChatUser["LiveChatUser"]['live_chat_plan_id']){
        		$newPlanId = $this->request->data["LiveChatUser"]['live_chat_plan_id'];
        		$this->request->data["LiveChatUser"]['live_chat_plan_id'] = $existingLiveChatUser["LiveChatUser"]['live_chat_plan_id'];
        	}

            if ($this->LiveChatUser->updateUser($id, $this->request->data)) {
                $this->_showSuccessFlashMessage($this->LiveChatUser);

                if(!empty($newPlanId)){
                	$clientSuperUserId = $this->LiveChatUser->liveChatUserIdToSuperUserId($this->request->data["LiveChatUser"]['id']);
                	$planDetails = $this->LiveChatPlan->find('all', array('contain' => false));
                	$tempInvoiceId = $this->LiveChatUser->switchPlan($clientSuperUserId, $newPlanId, $planDetails);
                	if(!empty($clientSuperUserId) && !empty($tempInvoiceId)){
                		return $this->redirect('/admin/dashboard#/admin/live_chat/live_chat_plans/alter/' .$newPlanId .'/' .$tempInvoiceId .'/' .$clientSuperUserId .'?iframe=1');
                	}else{
                		$this->_showWarningFlashMessage($this->LiveChatUser, __("Live chat user has been updated, but we cannot switch the plan. This has ben reported, sorry about the inconvenience."));

                		$logType 	 = Configure::read('Config.type.livechat');
                		$logLevel 	 = Configure::read('System.log.level.critical');
                		$logMessage  = __('User (#' .$this->superUserId .') cannot switch client plan. (Passed live chat user ID: ' .$id .', new plan ID: ' .$newPlanId .')');
                		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
                	}
                }
                return $this->redirect('/admin/dashboard#/live_chat/live_chat_users/index/' .$planId .'/edit');

            } else {
                $this->_showErrorFlashMessage($this->LiveChatUser);
            }
        }

        $this->request->data = $this->LiveChatUser->browseBy($this->LiveChatUser->primaryKey, $id);
        $plan = $this->LiveChatPlan->browseBy($this->LiveChatPlan->primaryKey, $planId);

        $planList = $this->LiveChatPlan->findPlanList();

        $this->set(compact('plan', 'planList'));
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
	    	$this->LiveChatUser->id = $id;
	        if (!$this->LiveChatUser->exists()) {

	            throw new NotFoundRecordException($this->modelClass);
	        }

	        // Client can only delete own account
	        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

	        	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
	        	if(!$this->LiveChatUser->hasAny(array('id' => $id, 'user_id' => $userServiceAccountId))){

	        		throw new ForbiddenActionException($this->modelClass, "delete");
	        	}
	        }

	        $user = $this->LiveChatUser->browseBy("id", $id);
	        $planId = $user['LiveChatUser']['live_chat_plan_id'];

	        $this->loadModel('User');
	        if($this->User->deleteUser($user['LiveChatUser']['user_id'])){
	            $this->_showSuccessFlashMessage($this->LiveChatUser);
	            $liveChatSystemUserAPICode 		= @$user['LiveChatUser']['livechat_api_code'];
	            $liveChatSystemUserPassphrase 	= @$user['LiveChatUser']['livechat_passphrase'];
	            $liveChatSystemUserSalt 		= @$user['LiveChatUser']['livechat_salt'];
	            if($this->LiveChatUser->deleteLiveChatSystemUser($liveChatSystemUserAPICode, $liveChatSystemUserPassphrase, $liveChatSystemUserSalt)){

	            	if(($this->superUserId == $user['LiveChatUser']['user_id'])){

	            		$this->_updateUserStatus();
	            	}

	            	$this->_showSuccessFlashMessage($this->LiveChatUser, __('Live chat account has been cancelled successfully.'));
	            }else{
	            	$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
	            	$this->_showErrorFlashMessage($this->LiveChatUser, __('Live chat account has been removed in ' .$companyName .', but it cannot be removed in live chat system.'));

	            	$logType 	 = Configure::read('Config.type.livechat');
	            	$logLevel 	 = Configure::read('System.log.level.critical');
	            	$logMessage  = __('User (#' .$this->superUserId .') cannot remove user account in live chat system. (Passed live chat user ID: ' .$liveChatSystemUserId .')');
	            	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
	            }
	        }else{
	        	$this->_showErrorFlashMessage($this->LiveChatUser);
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

    				$this->LiveChatUser->id = $id;
			        if (!$this->LiveChatUser->exists()) {

			        	throw new NotFoundRecordException($this->modelClass);
			        }

			        $user = $this->LiveChatUser->browseBy("id", $id);
			        $planId = $user['LiveChatUser']['live_chat_plan_id'];

			        if(!$this->User->deleteUser($user['LiveChatUser']['user_id'])){

			        	throw new ForbiddenActionException($this->modelClass, "batch delete");

			        	$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->LiveChatUser, __("Selected live chat users have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->LiveChatUser, __("Selected live chat users cannot be batch deleted."));
    			}
    		}
    	}
    }

    public function admin_adjustOperatorAmount(){

    	$this->_prepareAjaxPostAction();
    	if (($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')){

    		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    		if(!empty($this->request->data['user_id']) && !empty($this->request->data['operator_amount']) && is_numeric($this->request->data['operator_amount']) && stristr($this->superUserGroup, Configure::read('System.client.group.name')) && $userServiceAccountId == $this->request->data['user_id']){

    			$liveChatUser = $this->LiveChatUser->browseBy('user_id', $userServiceAccountId);
    			$liveChatUser['LiveChatUser']['chargeable_operator_amount'] = 0; // Clear unpaid chargeable operators

    			$existingLiveChatOperatorAmount = $this->LiveChatUser->getLiveChatSystemOperatorAmount($liveChatUser['LiveChatUser']['livechat_api_code'], $liveChatUser['LiveChatUser']['livechat_passphrase'], $liveChatUser['LiveChatUser']['livechat_salt']);

    			if(is_numeric($existingLiveChatOperatorAmount) && $existingLiveChatOperatorAmount < $this->request->data['operator_amount']){

    				// Charge new opeator fees
    				$needToCharge = false;
    				if($this->request->data['operator_amount'] > $liveChatUser['LiveChatUser']['operator_amount']){

    					$liveChatUser['LiveChatUser']['chargeable_operator_amount'] = $this->request->data['operator_amount'] - $liveChatUser['LiveChatUser']['operator_amount'];
    					$needToCharge = true;

    				}else{

    					if($this->request->data['operator_amount'] < $liveChatUser['LiveChatUser']['operator_amount']){

    						$liveChatUser['LiveChatUser']['chargeable_operator_amount'] = $this->request->data['operator_amount'] - $liveChatUser['LiveChatUser']['operator_amount'];
    						$needToCharge = true; // Need to re-generate recurring plan with new operator amount
    					}
    					$liveChatUser['LiveChatUser']['operator_amount'] = $this->request->data['operator_amount']; // Remove unused operator account, no charge
    				}

    				if($this->LiveChatUser->updateUser($liveChatUser['LiveChatUser']['id'], $liveChatUser)){

    					if($needToCharge){

    						echo json_encode(array('status' => Configure::read('System.variable.success'), 'message' => __('Operator amount is updated. Please pay for the ' .$liveChatUser['LiveChatUser']['chargeable_operator_amount'] .' newly added operator account(s).'), 'charge' => $liveChatUser['LiveChatUser']['live_chat_plan_id']));

    					}else{

    						echo 1;
    					}

    				}else{

    					echo 0;
    				}

    			}else{

    				echo json_encode(array('status' => Configure::read('System.variable.warning'), 'message' => __('Reduce operator amount failed. Please delete operator account first and then reduce the amount.')));
    			}

    		}else{

    			$logType 	 = Configure::read('Config.type.livechat');
    			$logLevel 	 = Configure::read('System.log.level.critical');
    			$logMessage  = __('User (#' .$this->superUserId .') cannot change live chat operator amount. (Passed live chat user ID: ' .$this->request->data['user_id'] .', operator amount: ' .$this->request->data['operator_amount'] .')');
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
    		}

    	}
    	exit();
    }

    public function admin_backupAccount(){
    	$this->_prepareAjaxPostAction();
    	if (($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')){
    		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
    		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

    			$liveChatUser = $this->LiveChatUser->browseBy('user_id', $userServiceAccountId);
    			$result = $this->LiveChatUser->generateDownloadableChatHistory($liveChatUser['LiveChatUser']['livechat_api_code']);
    			if(is_array($result)){

    				if(!empty($result['success'])){
    					echo json_encode(array('status' => Configure::read('System.variable.success'), 'message' => __('Chat back up file has been successfully generated. It could be downloaded from the following URL.') .'<br /><br /><a href="' .$result['url'] .'" target="_blank">' .$result['url'] .'</a>'));
    				}else{
    					echo json_encode($result);
    				}

    			}else{

    				$logType 	 = Configure::read('Config.type.livechat');
    				$logLevel 	 = Configure::read('System.log.level.critical');
    				$logMessage  = __('User (#' .$this->superUserId .') cannot back up chat history. (Live chat user ID: ' .$userServiceAccountId .')');
    				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    				echo json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __('Chat back up file cannot be generated. We will look into this ASAP. Sorry about the inconvenience.')));
    			}

    		}else{

    			$logType 	 = Configure::read('Config.type.livechat');
    			$logLevel 	 = Configure::read('System.log.level.warning');
    			$logMessage  = __('Staff (#' .$this->superUserId .') cannot backup live chat account.');
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage);
    		}
    	}
    	exit();
    }
}
