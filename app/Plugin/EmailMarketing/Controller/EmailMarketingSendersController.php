<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * Senders Controller
 *
 */
class EmailMarketingSendersController extends EmailMarketingAppController {

	public $paginate = array(
		'limit'     => 10,
		'order'     => array("EmailMarketingSender.id" => "DESC"),
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

    	$userId = $this->EmailMarketingSender->superUserIdToEmailMarketingUserId($userServiceAccountId);
    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		$this->paginate['conditions'] = array(
    			'EmailMarketingSender.email_marketing_user_id' => $userId
    		);
    	}

    	$this->Paginator->settings = $this->paginate;
    	$this->DataTable->mDataProp = true;
    	$this->set('response', $this->DataTable->getResponse());
    	$this->set('_serialize','response');
    	$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingSender.id']);

    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_view($id = null) {

    	if (!$this->EmailMarketingSender->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		if(!$this->EmailMarketingSender->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

    			throw new ForbiddenActionException($this->modelClass, "view");
    		}
    	}

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		$sender = $this->EmailMarketingSender->getSenderDetailsById($id, $userServiceAccountId);
    	}else{
    		$sender = $this->EmailMarketingSender->browseBy($this->EmailMarketingSender->primaryKey, $id);
    	}

		$this->set('sender', $sender);

    }

/**
 * add method
 *
 * @return void
 */
    public function admin_add() {
    	if ($this->request->is('post') && isset($this->request->data["EmailMarketingSender"]) && !empty($this->request->data["EmailMarketingSender"])) {

    		if(!$this->__checkSubscriberLimit()){

    			$logType 	 = Configure::read('Config.type.emailmarketing');
    			$logLevel 	 = Configure::read('System.log.level.warning');
    			$logMessage  = __('Exceed email marketing custom brand limit.');
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    			$this->_showErrorFlashMessage($this->EmailMarketingSender, __('Exceed custom brand limit'));

    		}else{

    			$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    			$userId = $this->EmailMarketingSender->superUserIdToEmailMarketingUserId($userServiceAccountId);
    			$this->request->data["EmailMarketingSender"]["email_marketing_user_id"] = $userId;

    			if ($this->EmailMarketingSender->saveSender($this->request->data)) {
    				$this->_showSuccessFlashMessage($this->EmailMarketingSender);
    			} else {
    				$this->_showErrorFlashMessage($this->EmailMarketingSender);
    			}
    		}

    	}

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

    	if (!$this->EmailMarketingSender->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	// Client cannot edit other person's sender
    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	$userId = $this->EmailMarketingSender->superUserIdToEmailMarketingUserId($userServiceAccountId);
    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		if(!$this->EmailMarketingSender->hasAny(array('id' => $id, 'email_marketing_user_id' => $userId))){

    			throw new ForbiddenActionException($this->modelClass, "edit");
    		}
    	}

    	if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["EmailMarketingSender"]) && !empty($this->request->data["EmailMarketingSender"])) {

    		$this->request->data["EmailMarketingSender"]["email_marketing_user_id"] = $userId;

    		if ($this->EmailMarketingSender->updateSender($id, $this->request->data)) {
    			$this->_showSuccessFlashMessage($this->EmailMarketingSender);
    		} else {
    			$this->_showErrorFlashMessage($this->EmailMarketingSender);
    		}

    	}

    	$sender = $this->EmailMarketingSender->browseBy($this->EmailMarketingSender->primaryKey, $id);
		$this->set('sender', $sender);
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
    		$this->EmailMarketingSender->id = $id;
    		if (!$this->EmailMarketingSender->exists()) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		// Client cannot delete other person's sender
    		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    		$userId = $this->EmailMarketingSender->superUserIdToEmailMarketingUserId($userServiceAccountId);
    		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    			if(!$this->EmailMarketingSender->hasAny(array('id' => $id, 'email_marketing_user_id' => $userId))){

    				throw new ForbiddenActionException($this->modelClass, "delete");
    			}
    		}

    		if($this->EmailMarketingSender->deleteSender($id)){
    			$this->_showSuccessFlashMessage($this->EmailMarketingSender);
    		}else{
    			$this->_showErrorFlashMessage($this->EmailMarketingSender);
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
    				$this->EmailMarketingSender->id = $id;
    				if (!$this->EmailMarketingSender->exists()) {

    					throw new NotFoundRecordException($this->modelClass);
    					$batchDeleteDone = false;
    					break;
    				}

    				// Client cannot batch delete other person's sender
    				$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    				$userId = $this->EmailMarketingSender->superUserIdToEmailMarketingUserId($userServiceAccountId);
    				if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    					if(!$this->EmailMarketingSender->hasAny(array('id' => $id, 'email_marketing_user_id' => $userId))){

	    					throw new ForbiddenActionException($this->modelClass, "batch delete");
	    					$batchDeleteDone = false;
	    					break;
    					}
    				}

    				if (!$this->EmailMarketingSender->deleteSender($id)) {

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing sender. (Passed email marketing sender ID: ' .$id .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->EmailMarketingSender, __("Selected email marketing senders have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->EmailMarketingSender, __("Selected email marketing senders cannot be batch deleted."));
    			}
    		}
    	}
    }

    private function __checkSubscriberLimit($increment = 1, $returnRemainingAmount = false){

    	$emailMarketingUserId = $this->EmailMarketingSender->superUserIdToEmailMarketingUserId($this->superUserId);
    	$superUserId		  = $this->EmailMarketingSender->emailMarketingUserIdToSuperUserId($emailMarketingUserId);

    	$this->loadModel('EmailMarketing.EmailMarketingUser');
    	$emailMarketingUser = $this->EmailMarketingUser->getUserBySuperId($superUserId, array('EmailMarketingPlan'));
    	$senderLimit = $emailMarketingUser['EmailMarketingPlan']['sender_limit'];

    	if($returnRemainingAmount){

    		return $senderLimit - ($increment + $this->EmailMarketingSender->countSenderByUser($emailMarketingUserId));

    	}else{

    		return $senderLimit >= ($increment + $this->EmailMarketingSender->countSenderByUser($emailMarketingUserId));
    	}
    }
}
