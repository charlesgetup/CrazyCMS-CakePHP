<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * MailingList Controller
 *
 */
class EmailMarketingMailingListsController extends EmailMarketingAppController {

    public $paginate = array(
        'fields' => array(
            'EmailMarketingMailingList.*',
            'User.first_name',
            'User.last_name'
        ),
    	'joins' => array(
    		array(
    			'table' => 'email_marketing_users',
    			'alias' => 'EmailMarketingUserJoinTable',
    			'type'  => 'inner',
    			'conditions' => array(
    				'EmailMarketingUserJoinTable.id = EmailMarketingMailingList.email_marketing_user_id'
    			)
    		),
            array(
                'table' => 'users',
                'alias' => 'User',
                'type'  => 'inner',
                'conditions' => array(
                    'User.id = EmailMarketingUserJoinTable.user_id'
                )
            )
        ),
        'limit' => 12,
        'order' => array("EmailMarketingMailingList.id" => "DESC")
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
        		'User.id' 							=> $userServiceAccountId,
        		'EmailMarketingMailingList.deleted' => 0
        	);
        }

        $this->Paginator->settings = $this->paginate;
        $this->DataTable->mDataProp = true;
        $this->set('response', $this->DataTable->getResponse());
        $this->set('_serialize','response');
        $this->set('defaultSortDir', $this->paginate['order']['EmailMarketingMailingList.id']);

    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->EmailMarketingMailingList->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		// Client cannot edit other person's sender
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "view");
			}
		}

        $list = $this->EmailMarketingMailingList->browseBy($this->EmailMarketingMailingList->primaryKey, $id);

        $this->loadModel('EmailMarketing.EmailMarketingUser');
        $user = $this->EmailMarketingUser->find('first',array(
            'fields' => array(
                'SuperUser.first_name',
                'SuperUser.last_name'
            ),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'SuperUser',
                    'type' => 'inner',
                    'conditions' => array(
                        'SuperUser.id = EmailMarketingUser.user_id',
                        'EmailMarketingUser.id = ' .$list['EmailMarketingMailingList']['email_marketing_user_id']
                    )
                )
            ),
        ));

        $list['EmailMarketingMailingList']['user_name'] = $user['SuperUser']['first_name'] .' ' .$user['SuperUser']['last_name'];

        $this->set('list', $list);

	}

/**
 * add method
 *
 * @return void
 */
    public function admin_add() {

        if ($this->request->is('post') && isset($this->request->data["EmailMarketingMailingList"]) && !empty($this->request->data["EmailMarketingMailingList"])) {
            if ($this->EmailMarketingMailingList->saveList($this->request->data)) {
                $this->_showSuccessFlashMessage($this->EmailMarketingMailingList);
            } else {
                $this->_showErrorFlashMessage($this->EmailMarketingMailingList);
            }
        }

        $this->__prepareViewData();

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
        if (!$this->EmailMarketingMailingList->exists($id)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        // Client cannot edit other person's mailing list
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

        		throw new ForbiddenActionException($this->modelClass, "edit");
        	}
        }

        if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["EmailMarketingMailingList"]) && !empty($this->request->data["EmailMarketingMailingList"])) {
            if ($this->EmailMarketingMailingList->updateList($id, $this->request->data)) {
                $this->_showSuccessFlashMessage($this->EmailMarketingMailingList);
            } else {
                $this->_showErrorFlashMessage($this->EmailMarketingMailingList);
            }
        }

        $this->request->data = $this->EmailMarketingMailingList->browseBy($this->EmailMarketingMailingList->primaryKey, $id);
        $this->__prepareViewData();
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
    		$this->EmailMarketingMailingList->id = $id;
    		if (!$this->EmailMarketingMailingList->exists()) {

    			throw new NotFoundRecordException($this->modelClass);
    		}

    		// Client cannot delete other person's list
    		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    			if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

    				throw new ForbiddenActionException($this->modelClass, "delete");
    			}
    		}

    		if($this->EmailMarketingMailingList->deleteList($id)){
    			$this->_showSuccessFlashMessage($this->EmailMarketingMailingList);
    		}else{
    			$this->_showErrorFlashMessage($this->EmailMarketingMailingList);
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

    			$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    			$batchDeleteDone = true;
    			foreach($this->request->data['batchIds'] as $id){
    				$this->EmailMarketingMailingList->id = $id;
    				if (!$this->EmailMarketingMailingList->exists()) {

    					throw new NotFoundRecordException($this->modelClass);
    					$batchDeleteDone = false;
    					break;
    				}

    				// Client cannot delete other person's list
    				if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
    					if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

    						throw new ForbiddenActionException($this->modelClass, "batch delete");
    					}
    				}

    				if (!$this->EmailMarketingMailingList->deleteList($id)) {

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing subscriber list. (Passed email marketing subscriber list ID: ' .$id .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					$batchDeleteDone = false;
    				}
    			}
    			if($batchDeleteDone){
    				$this->_showSuccessFlashMessage($this->EmailMarketingMailingList, __("Selected email marketing lists have been batch deleted."));
    			}else{
    				$this->_showErrorFlashMessage($this->EmailMarketingMailingList, __("Selected email marketing lists cannot be batch deleted."));
    			}
    		}
    	}
    }

    private function __prepareViewData(){

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){
    		$userServiceAccountId = null; // Find all
    	}

    	$this->loadModel('EmailMarketing.EmailMarketingUser');
        $userList = $this->EmailMarketingUser->getUserList($userServiceAccountId);
        $this->set(compact('userList'));
    }
}
