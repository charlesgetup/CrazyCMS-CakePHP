<?php

App::uses('AppController', 'Controller');

class LiveChatAppController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * Get current live chat user service account ID
 *
 * Service account ID is ID column in users table, not ID column in live_chat_users table
 *
 * If the current user is not a Client, then the service account ID will fall back to super user ID
 *
 * @return int
 */
    protected function _getCurrentUserServiceAccountId() {

    	$userId			= $this->Session->read('Auth.User.id'); // Super user ID, not live chat account/user ID

    	// Find the live chat account ONLY
    	$groupId = $this->Session->read('Auth.User.group_id');
    	$liveChatGroupId = Configure::read('LiveChat.client.group.id');
    	if($groupId != $liveChatGroupId){
    		$userAssociatedAccounts = Set::classicExtract($this->Session->read('AssociatedUsers'));
    		foreach($userAssociatedAccounts as $associatedUser){
	    		if(!empty($associatedUser['User']['active']) && $associatedUser['User']['group_id'] == $liveChatGroupId){
	    			$userId = $associatedUser['User']['id'];
	    		}
    		}
    	}

    	return $userId;
    }
}
