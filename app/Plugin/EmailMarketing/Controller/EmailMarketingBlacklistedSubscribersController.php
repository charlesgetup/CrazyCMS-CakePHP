<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * Blacklisted Subscribers Controller
 *
 */
class EmailMarketingBlacklistedSubscribersController extends EmailMarketingAppController {

    public $paginate = array(
        'fields' => array(
            'EmailMarketingBlacklistedSubscriber.*',
            'User.first_name',
            'User.last_name',
        	'User.id'
        ),
        'joins' => array(
            array(
                'table' => 'email_marketing_users',
                'alias' => 'EmailMarketingMiddleUser',
                'type' => 'inner',
                'conditions' => array(
                    'EmailMarketingMiddleUser.id = EmailMarketingBlacklistedSubscriber.email_marketing_user_id'
                )
            ),
            array(
                'table' => 'users',
                'alias' => 'User',
                'type' => 'inner',
                'conditions' => array(
                    'User.id = EmailMarketingMiddleUser.user_id'
                )
            )
        ),
        'limit'     => 9,
        'order'     => array("EmailMarketingBlacklistedSubscriber.id" => "DESC"),
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

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	$this->paginate['conditions'] = array(
        		'User.id' => $userServiceAccountId
        	);
        }

        $this->Paginator->settings = $this->paginate;
        $this->DataTable->mDataProp = true;
        $this->set('response', $this->DataTable->getResponse());
        $this->set('_serialize','response');
        $this->set('defaultSortDir', $this->paginate['order']['EmailMarketingBlacklistedSubscriber.id']);

    }

/**
 * add method
 *
 * @return void
 */
    public function admin_add() {
        if ($this->request->is('post') && isset($this->request->data["EmailMarketingBlacklistedSubscriber"]) && !empty($this->request->data["EmailMarketingBlacklistedSubscriber"])) {

        	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        		$emailMarketingUserId = $this->EmailMarketingBlacklistedSubscriber->superUserIdToEmailMarketingUserId($userServiceAccountId);
        		if(empty($emailMarketingUserId) || $this->request->data["EmailMarketingBlacklistedSubscriber"]['email_marketing_user_id'] != $emailMarketingUserId){

        			throw new ForbiddenActionException($this->modelClass, "add");
        		}
        	}

        	if(!$this->__checkSubscriberLimit()){

        		$logType 	 = Configure::read('Config.type.emailmarketing');
        		$logLevel 	 = Configure::read('System.log.level.warning');
        		$logMessage  = __('Exceed blacklisted subscriber limit.');
        		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

        		$this->_showErrorFlashMessage($this->EmailMarketingBlacklistedSubscriber, __('Exceed blacklisted subscriber limit'));

        	}else{

	            if ($this->EmailMarketingBlacklistedSubscriber->saveSubscriber($this->request->data)) {
	                $this->_showSuccessFlashMessage($this->EmailMarketingBlacklistedSubscriber);
	            } else {
	                $this->_showErrorFlashMessage($this->EmailMarketingBlacklistedSubscriber);
	            }
        	}
        }
        $this->__setUserDataInView();
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
    		$this->EmailMarketingBlacklistedSubscriber->id = $id;
    		if (!$this->EmailMarketingBlacklistedSubscriber->exists()) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    			$emailMarketingUserId = $this->EmailMarketingBlacklistedSubscriber->superUserIdToEmailMarketingUserId($userServiceAccountId);
    			if(empty($emailMarketingUserId) || !$this->EmailMarketingBlacklistedSubscriber->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

    				throw new ForbiddenActionException($this->modelClass, "delete");
    			}
    		}

    		if($this->EmailMarketingBlacklistedSubscriber->deleteSubscriber($id)){
    			$this->_showSuccessFlashMessage($this->EmailMarketingBlacklistedSubscriber);
    		}else{
    			$this->_showErrorFlashMessage($this->EmailMarketingBlacklistedSubscriber);
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
    				$this->EmailMarketingBlacklistedSubscriber->id = $id;
    				if (!$this->EmailMarketingBlacklistedSubscriber->exists()) {

    					throw new NotFoundRecordException($this->modelClass);
    					$batchDeleteDone = false;
    					break;
    				}

    				$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    				if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    					$emailMarketingUserId = $this->EmailMarketingBlacklistedSubscriber->superUserIdToEmailMarketingUserId($userServiceAccountId);
    					if(empty($emailMarketingUserId) || !$this->EmailMarketingBlacklistedSubscriber->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

    						throw new ForbiddenActionException($this->modelClass, "batch delete");
    					}
    				}

    				if (!$this->EmailMarketingBlacklistedSubscriber->deleteSubscriber($id)) {

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing blacklisted subscriber. (Passed blacklisted subscriber ID: ' .$id .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->EmailMarketingBlacklistedSubscriber, __("Selected email marketing subscribers have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->EmailMarketingBlacklistedSubscriber, __("Selected email marketing subscribers cannot be batch deleted."));
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
    public function admin_import() {
    	set_time_limit(120); //TODO this need to be tested in live and adjust it if necessary

    	$uploadFileMaxSize = $this->_getSystemDefaultConfigSetting('UploadfileSizeLimit', Configure::read('Config.type.system'));
    	$remainingSubscriberIncrementAmount = $this->__checkSubscriberLimit(0, true);

        if ($this->request->is('post') && isset($this->request->data["EmailMarketingBlacklistedSubscriber"]["subscriber_file"]) && !empty($this->request->data["EmailMarketingBlacklistedSubscriber"]["subscriber_file"]) && $this->EmailMarketingBlacklistedSubscriber->isUploadedFile($this->request->data["EmailMarketingBlacklistedSubscriber"]["subscriber_file"])) {

        	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        		$emailMarketingUserId = $this->EmailMarketingBlacklistedSubscriber->superUserIdToEmailMarketingUserId($userServiceAccountId);
        		$submittedEmailMarketingUserId = @$this->request->data["EmailMarketingBlacklistedSubscriber"]["email_marketing_user_id"];
        		if(empty($emailMarketingUserId) || empty($submittedEmailMarketingUserId) || $emailMarketingUserId != $submittedEmailMarketingUserId){

        			throw new ForbiddenActionException($this->modelClass, 'import');
        		}
        	}

            if(intval($this->request->data["EmailMarketingBlacklistedSubscriber"]["subscriber_file"]["size"]) > $uploadFileMaxSize){
            	$this->_showErrorFlashMessage(null, __('Import email marketing blacklisted subscribers file size is over limit.'));
            }else{
            	$results = $this->EmailMarketingBlacklistedSubscriber->importSubscriber($this->request->data, $remainingSubscriberIncrementAmount);
                if (is_array($results) && !empty($results)) {
                    $this->_showSuccessFlashMessage($this->EmailMarketingBlacklistedSubscriber, "", "", '(Saved: ' .$results['saved'] .', Duplicated: ' .$results['duplicated'] .', Invalid: ' .$results['invalid'] .')');
                } else {
                    $this->_showErrorFlashMessage($this->EmailMarketingBlacklistedSubscriber);
                }
            }

            if($this->request->is('ajax') && $this->Session->check('Message.flash.message')){
            	$this->_prepareNoViewAction();
            	echo $this->Session->read('Message.flash.message');
            	$this->Session->delete('Message.flash'); // Don't show flash message again in PHP
            	exit();
            }
        }else{
        	$this->_showSuccessFlashMessage($this->EmailMarketingBlacklistedSubscriber, "", "", __("Uploaded email marketing blacklisted subscribers file is not valid"));
        }

        $this->__setUserDataInView();
        $this->set(compact('uploadFileMaxSize', 'remainingSubscriberIncrementAmount'));
    }

    private function __setUserDataInView(){

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	$clientUserId = null;

    	$this->loadModel('EmailMarketing.EmailMarketingUser');
    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){
    		$clients = $this->EmailMarketingUser->find('all', array(
	            'contain' => array('User')
	        ));
    		$clientList = array();
    		if(is_array($clients) && !empty($clients)){
    			foreach($clients as $c){
    				$clientList[$c['EmailMarketingUser']['id']] = $c['User']['name'];
    			}
    		}
    		$this->set('userList', $clientList);
    	}else{
    		$emailMarketingClientDetails = $this->EmailMarketingUser->browseBy('user_id', $userServiceAccountId, false);
    		$clientUserId = $emailMarketingClientDetails['EmailMarketingUser']['id'];
    		$this->set('userId', $clientUserId);
    	}

        return $clientUserId;
    }

/**
 * Blacklist subscriber amount cannot exceed subscriber limit, too.
 * @param number $increment
 * @param string $returnRemainingAmount
 * @return number|boolean
 */
    private function __checkSubscriberLimit($increment = 1, $returnRemainingAmount = false){

    	$emailMarketingUserId = $this->EmailMarketingBlacklistedSubscriber->superUserIdToEmailMarketingUserId($this->superUserId);
    	$superUserId		  = $this->EmailMarketingBlacklistedSubscriber->emailMarketingUserIdToSuperUserId($emailMarketingUserId);

    	$this->loadModel('EmailMarketing.EmailMarketingUser');
    	$emailMarketingUser = $this->EmailMarketingUser->getUserBySuperId($superUserId, array('EmailMarketingPlan'));
    	$subscriberLimit = $emailMarketingUser['EmailMarketingPlan']['subscriber_limit'];

    	$this->loadModel('EmailMarketing.EmailMarketingSubscriber');

    	if($returnRemainingAmount){

    		return $subscriberLimit - ($increment + $this->EmailMarketingSubscriber->countSubscriberByUser($emailMarketingUserId));

    	}else{

    		return $subscriberLimit >= ($increment + $this->EmailMarketingSubscriber->countSubscriberByUser($emailMarketingUserId));
    	}
    }
}
