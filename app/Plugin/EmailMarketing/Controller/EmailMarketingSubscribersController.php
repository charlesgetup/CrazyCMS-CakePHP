<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * Subscribers Controller
 *
 */
class EmailMarketingSubscribersController extends EmailMarketingAppController {

    public $paginate = array(
    	'fields'	=> array('EmailMarketingSubscriber.*'),
        'limit'     => 7,
        'order'     => array("EmailMarketingSubscriber.id" => "DESC"),
    );

    public function beforeFilter() {

        $this->Auth->allow('unsubscribe');

        parent::beforeFilter();
        $this->loadModel('EmailMarketing.EmailMarketingMailingList');
    }

/**
 * unsubscribe method
 *
 * @param $messageId
 * @return void
 */
    public function unsubscribe($messageId){

    	if(empty($messageId)){

    		$this->set('message', __('Request is invalid.'));

    	}else{

    		$formatedMessageId = sprintf ( '%s', $messageId );
    		if ($formatedMessageId == $messageId) {

    			$xorMask = $this->_getSystemDefaultConfigSetting( 'XORMask', Configure::read('Config.type.emailmarketing') );
    			$track = base64_decode ( $formatedMessageId );
    			$track = $track ^ $xorMask;
    			@list ( $campaignId, $subscriberId, $statisticId ) = explode ( '-', $track );
    			$statisticId = sprintf ( '%d', $statisticId );
    			$subscriberId = sprintf ( '%d', $subscriberId );

    			$this->loadModel ( 'EmailMarketing.EmailMarketingCampaign' );
    			$this->loadModel ( 'EmailMarketing.EmailMarketingStatistic' );
    			if (! empty ( $statisticId ) && $this->EmailMarketingStatistic->exists ( $statisticId ) && ! empty ( $subscriberId ) && $this->EmailMarketingSubscriber->exists ( $subscriberId ) && ! empty ( $campaignId ) && $this->EmailMarketingCampaign->exists ( $campaignId )) {

    				$result = $this->EmailMarketingSubscriber->unsubscribeFromMailingList($subscriberId);
    				if($result){
    					$this->set('message', __('So sorry to see you leave. Hope you will continue enjoying our other services.'));
    				}else{

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (IP: ' .@$this->request->clientIp() .') cannot unsubscribe from email marketing campaign mailong list. (Passed email marketing message ID: ' .$messageId .', subscriber ID: ' .$subscriberId .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$this->set('message', __('Unsubscribe process has failed. This error has been reported to the company. And we will manually unsubscribe for you. Sorry for the inconvenience.'));
    				}

    			}else{

    				if(empty ( $statisticId ) || !$this->EmailMarketingStatistic->exists ( $statisticId )){

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.debug');
    					$logMessage  = __('User (IP: ' .@$this->request->clientIp() .') tried to unsubscribe from email marketing campaign mailing list, statistic missing. (Passed email marketing message ID: ' .$messageId .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    				}elseif(empty ( $subscriberId ) || !$this->EmailMarketingSubscriber->exists ( $subscriberId )){

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.debug');
    					$logMessage  = __('User (IP: ' .@$this->request->clientIp() .') tried to unsubscribe from email marketing campaign mailing list, subscriber missing. (Passed email marketing message ID: ' .$messageId .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    				}elseif(empty ( $campaignId ) || !$this->EmailMarketingCampaign->exists ( $campaignId )){

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.debug');
    					$logMessage  = __('User (IP: ' .@$this->request->clientIp() .') tried to unsubscribe from email marketing campaign mailing list, campaign missing. (Passed email marketing message ID: ' .$messageId .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    				}

    				$this->set('message', __('Request is invalid.'));
    			}

    		}else{

    			$logType 	 = Configure::read('Config.type.emailmarketing');
    			$logLevel 	 = Configure::read('System.log.level.debug');
    			$logMessage  = __('User (IP: ' .@$this->request->clientIp() .') tried to unsubscribe from email marketing campaign mailing list using invalid message ID. (Passed email marketing message ID: ' .$messageId .')');
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    			$this->set('message', __('Request is invalid.'));
    		}

    	}

    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index($listId = null) {
        if (!$this->EmailMarketingMailingList->exists($listId)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        // Client cannot view subscribers in other person's mailing list
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($listId, $userServiceAccountId)){

        		throw new ForbiddenActionException($this->modelClass, 'list');
        	}
        }

		$this->set('listId', $listId);

        $this->paginate['conditions'] = array(
        	'EmailMarketingSubscriber.deleted' 					=> 0,
        	'EmailMarketingSubscriber.unsubscribed' 			=> 0,
            'EmailMarketingSubscriber.email_marketing_list_id' 	=> $listId
        );
        $this->Paginator->settings = $this->paginate;
        $this->DataTable->mDataProp = true;
        $this->set('response', $this->DataTable->getResponse());
        $this->set('_serialize','response');
        $this->set('defaultSortDir', $this->paginate['order']['EmailMarketingSubscriber.id']);

    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		//TODO display extra fields which is added by client
        if (!$this->EmailMarketingSubscriber->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

        $subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $id);

        // Client cannot view subscribers in other person's mailing list
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

        		throw new ForbiddenActionException($this->modelClass, "view");
        	}
        }

		$this->set('subscriber', $subscriber);

	}

/**
 * add method
 *
 * @return void
 */
    public function admin_add($listId = null) {
        if (!$this->EmailMarketingMailingList->exists($listId)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        // Client cannot process other person's mailing list
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($listId, $userServiceAccountId)){

        		throw new ForbiddenActionException($this->modelClass, "add");
        	}
        }

        $list = $this->__prepareViewData($listId);

        if ($this->request->is('post') && isset($this->request->data["EmailMarketingSubscriber"]) && !empty($this->request->data["EmailMarketingSubscriber"])) {

        	if(!$this->__checkSubscriberLimit()){

        		$logType 	 = Configure::read('Config.type.emailmarketing');
        		$logLevel 	 = Configure::read('System.log.level.warning');
        		$logMessage  = __('Exceed email marketing subscriber limit.');
        		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

        		$this->_showErrorFlashMessage($this->EmailMarketingSubscriber, __('Exceed subscriber limit'));

        	}else{

        		$this->request->data["EmailMarketingSubscriber"]["email_marketing_user_id"] = $list["EmailMarketingMailingList"]["email_marketing_user_id"];
        		if ($this->EmailMarketingSubscriber->saveSubscriber($this->request->data)) {
        			$this->_showSuccessFlashMessage($this->EmailMarketingSubscriber);
        		} else {
        			$this->_showErrorFlashMessage($this->EmailMarketingSubscriber);
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
    public function admin_edit($id = null, $listId = null) {
        if (!$this->EmailMarketingSubscriber->exists($id) || !$this->EmailMarketingMailingList->exists($listId)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

        	// Client cannot edit other person's mailing list
        	if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($listId, $userServiceAccountId)){

        		throw new ForbiddenActionException($this->modelClass, "edit");
        	}

        	// Client cannot view subscribers in other person's mailing list
        	$subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $id);
        	if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

        		throw new ForbiddenActionException($this->modelClass, "edit");
        	}

        }

        $list = $this->__prepareViewData($listId);

        if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["EmailMarketingSubscriber"]) && !empty($this->request->data["EmailMarketingSubscriber"])) {
            $this->request->data["EmailMarketingSubscriber"]["email_marketing_user_id"] = $list["EmailMarketingMailingList"]["email_marketing_user_id"];
            if(!empty($this->request->data["EmailMarketingSubscriber"]["extra_attr"]) && is_array($this->request->data["EmailMarketingSubscriber"]["extra_attr"])){
            	$this->request->data["EmailMarketingSubscriber"]["extra_attr"] = serialize($this->request->data["EmailMarketingSubscriber"]["extra_attr"]);
            }
            if ($this->EmailMarketingSubscriber->updateSubscriber($id, $this->request->data)) {
                $this->_showSuccessFlashMessage($this->EmailMarketingSubscriber);
            } else {
                $this->_showErrorFlashMessage($this->EmailMarketingSubscriber);
            }
        }

        $this->request->data = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $id);
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
    		$this->EmailMarketingSubscriber->id = $id;

    		// Client cannot delete subscribers in other person's mailing list
    		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    		$subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $id, true);

    		if (empty($subscriber)) {

    			throw new NotFoundRecordException($this->modelClass);
    		}
    		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    			if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

    				throw new ForbiddenActionException($this->modelClass, "delete");
    			}
    		}

    		if($this->EmailMarketingSubscriber->deleteSubscriber($id)){
    			$this->_showSuccessFlashMessage($this->EmailMarketingSubscriber);
    		}else{
    			$this->_showErrorFlashMessage($this->EmailMarketingSubscriber);
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

    				// Client cannot view subscribers in other person's mailing list
    				$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    				$subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $id);
    				if (empty($subscriber)) {

    					throw new NotFoundRecordException($this->modelClass);
    					$batchDeleteDone = false;
    					break;
    				}
    				if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    					if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

    						throw new ForbiddenActionException($this->modelClass, "batch delete");
	    					$batchDeleteDone = false;
	    					break;
    					}
    				}

    				if (!$this->EmailMarketingSubscriber->deleteSubscriber($id)) {

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing subscriber. (Passed subscriber ID: ' .$id .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->EmailMarketingSubscriber, __("Selected email marketing subscribers have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->EmailMarketingSubscriber, __("Selected email marketing subscribers cannot be batch deleted."));
    			}
    		}
    	}
    }

/**
 * import method
 *
 * @throws NotFoundException
 * @param string $listId
 * @return void
 */
    public function admin_import($listId = null) {
    	set_time_limit(600); //TODO this need to be tested in live and adjust it if necessary

        if (!$this->EmailMarketingMailingList->exists($listId)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        // Client cannot process other person's mailing list
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($listId, $userServiceAccountId)){

        		throw new ForbiddenActionException($this->modelClass, "import");
        	}
        }

        $list = $this->__prepareViewData($listId);
        $uploadFileMaxSize = $this->_getSystemDefaultConfigSetting('UploadfileSizeLimit', Configure::read('Config.type.system'));
        $remainingSubscriberIncrementAmount = $this->__checkSubscriberLimit(0, true);
        $extraAttrLimit = $this->__getCurrentUserPlan();

        if($this->request->is('post')){
        	if (isset($this->request->data["EmailMarketingSubscriber"]["subscriber_file"]) && !empty($this->request->data["EmailMarketingSubscriber"]["subscriber_file"]) && $this->EmailMarketingSubscriber->isUploadedFile($this->request->data["EmailMarketingSubscriber"]["subscriber_file"])) {

        		if(intval($this->request->data["EmailMarketingSubscriber"]["subscriber_file"]["size"]) > $uploadFileMaxSize){
        			$this->_showErrorFlashMessage(null, __('Imported email marketing subscriber file size is over limit.'));
        		}else{
        			$this->request->data["EmailMarketingSubscriber"]["email_marketing_user_id"] = $list["EmailMarketingMailingList"]["email_marketing_user_id"];
        			$results = $this->EmailMarketingSubscriber->importSubscriber($this->request->data, $remainingSubscriberIncrementAmount, $extraAttrLimit);

        			if (is_array($results) && !empty($results)) {
        				$this->_showSuccessFlashMessage($this->EmailMarketingSubscriber, "", "", '(Saved: ' .$results['saved'] .', Duplicated: ' .$results['duplicated'] .', Invalid: ' .$results['invalid'] .', Blacklist filtered: ' .$results['blacklist'] .')');
        			} else {
        				$this->_showErrorFlashMessage($this->EmailMarketingSubscriber);
        			}
        		}

        		if($this->request->is('ajax') && $this->Session->check('Message.flash.message')){
        			$this->_prepareNoViewAction();
        			echo $this->Session->read('Message.flash.message');
        			$this->Session->delete('Message.flash'); // Don't show flash message again in PHP
        			exit();
        		}
        	}else{
        		$this->_showSuccessFlashMessage($this->EmailMarketingSubscriber, "", "", __("Uploaded email marketing subscriber file is not valid"));
        	}
        }

        $this->set(compact('uploadFileMaxSize', 'remainingSubscriberIncrementAmount'));
    }

/**
 * removeInvalidSubscriber method
 *
 * @throws NotFoundException
 * @return void
 */
    public function admin_removeInvalidSubscriber() {
    	if(empty($this->request->data['EmailMarketingCampaign']['id'])){

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	// Client cannot process other person's campaign
    	$this->loadModel('EmailMarketing.EmailMarketingCampaign');
    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    		if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($this->request->data['EmailMarketingCampaign']['id'], $userServiceAccountId)){

    			throw new ForbiddenActionException($this->modelClass, "remove");
    		}
    	}

    	// Check submitted invalid subscribers are all belongs to the provided campaign
    	$partiallyRemoved = false;
    	$subscriberIds = $this->EmailMarketingSubscriber->checkSubscriberBelongToCampaign($this->request->data['EmailMarketingCampaign']['id'], $this->request->data['EmailMarketingCampaign']['invalid_subscriber']);
    	if(!$subscriberIds){

    		throw new ForbiddenActionException($this->modelClass, "remove");

    	}else{
    		if(count($subscriberIds) != count($this->request->data['EmailMarketingCampaign']['invalid_subscriber'])){
    			$partiallyRemoved = true;
    		}
    	}

    	$this->_prepareAjaxPostAction();
    	if($this->EmailMarketingSubscriber->deleteAll(array('EmailMarketingSubscriber.id' => $subscriberIds))){
    		if($partiallyRemoved){
    			$this->response->body(__('The invalid subscribers have been partially removed, some of the invalid subscriber(s) does/do not belong to this campaign'));
    		}else{
    			$this->response->body(__('The invalid subscribers have been removed successfully'));
    		}
    	}else{
    		$this->response->body(__('The invalid subscribers cannot be removed'));
    	}
    }

    private function __prepareViewData($listId){
    	$list = $this->EmailMarketingMailingList->browseBy($this->EmailMarketingMailingList->primaryKey, $listId);
        $this->set(compact('list'));
        return $list;
    }

    private function __getCurrentUserPlan($emailMarketingUserId = null){
    	if(empty($emailMarketingUserId)){
    		$emailMarketingUserId = $this->EmailMarketingMailingList->superUserIdToEmailMarketingUserId($this->superUserId);
    	}
    	$superUserId		  = $this->EmailMarketingMailingList->emailMarketingUserIdToSuperUserId($emailMarketingUserId);

    	$this->loadModel('EmailMarketing.EmailMarketingUser');
    	$emailMarketingUser = $this->EmailMarketingUser->getUserBySuperId($superUserId, array('EmailMarketingPlan'));

    	return $emailMarketingUser['EmailMarketingPlan'];
    }

    private function __checkSubscriberLimit($increment = 1, $returnRemainingAmount = false){

    	$emailMarketingUserId = $this->EmailMarketingMailingList->superUserIdToEmailMarketingUserId($this->superUserId);
    	$plan = $this->__getCurrentUserPlan($emailMarketingUserId);
    	$subscriberLimit = $plan['subscriber_limit'];

    	if($returnRemainingAmount){

    		return $subscriberLimit - ($increment + $this->EmailMarketingSubscriber->countSubscriberByUser($emailMarketingUserId));

    	}else{

    		return $subscriberLimit >= ($increment + $this->EmailMarketingSubscriber->countSubscriberByUser($emailMarketingUserId));
    	}
    }
}
