<?php

App::uses('AppController', 'Controller');

class WebDevelopmentAppController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * Get current web development user service account ID
 *
 * Service account ID is ID column in users table
 *
 * If the current user is not a Client, then the service account ID will fall back to super user ID
 *
 * @return int
 */
    protected function _getCurrentUserServiceAccountId() {

    	$userId			= $this->Session->read('Auth.User.id'); // Super user ID, not web development account/user ID

    	// Find the web development account ONLY
    	$groupId = $this->Session->read('Auth.User.group_id');
    	$webDevelopmentGroupId = Configure::read('WebDevelopment.client.group.id');
    	if($groupId != $webDevelopmentGroupId){
    		$userAssociatedAccounts = Set::classicExtract($this->Session->read('AssociatedUsers'));
    		foreach($userAssociatedAccounts as $associatedUser){
	    		if(!empty($associatedUser['User']['active']) && $associatedUser['User']['group_id'] == $webDevelopmentGroupId){
	    			$userId = $associatedUser['User']['id'];
	    		}
    		}
    	}

    	return $userId;
    }
}
