<?php

App::uses('AppController', 'Controller');

class EmailMarketingAppController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * Get current email marketing user service account ID
 *
 * Service account ID is ID column in users table, not ID column in email_marketing_users table
 *
 * If the current user is not a Client, then the service account ID will fall back to super user ID
 *
 * @return int
 */
    protected function _getCurrentUserServiceAccountId() {

    	$userId = $this->Session->read('Auth.User.id'); // Super user ID, not email marketing account/user ID

    	// Find the email marketing account ONLY
    	$groupId = $this->Session->read('Auth.User.group_id');
    	$emailMarketingGroupId = Configure::read('EmailMarketing.client.group.id');
    	if($groupId != $emailMarketingGroupId){
    		$userAssociatedAccounts = Set::classicExtract($this->Session->read('AssociatedUsers'));
    		foreach($userAssociatedAccounts as $associatedUser){
	    		if(!empty($associatedUser['User']['active']) && $associatedUser['User']['group_id'] == $emailMarketingGroupId){
	    			$userId = $associatedUser['User']['id'];
	    		}
    		}
    	}

    	return $userId;
    }
}
