<?php
App::uses('WebDevelopmentAppModel', 'WebDevelopment.Model');

/**
 * Web development user Model
 *
 * No table model for now, because this kind of clients don't have special settings. When they have some in the future, we might add a table for them.
 *
 */
class WebDevelopmentUser extends WebDevelopmentAppModel {

    public $useTable = false;

/**
 * Create associated user
 * @param int|null $rootUser
 * @return boolean
 */
    public function saveUser($rootUserId = null){

    	$User = ClassRegistry::init('User');

    	if(!empty($rootUserId) && is_numeric($rootUserId)){

    		$associatedUser = $User->find('first', array(
    			'conditions' => array(
    				'User.id' => $rootUserId
    			),
    			'contain' => false
    		));

    	}else{
    		return false;
    	}

    	// Create associated user using the root user info
    	$associatedUser["User"]["parent_id"]         = $associatedUser["User"]["id"];
    	$associatedUser["User"]["email_confirm"]     = $associatedUser["User"]["id"] .'-' .time() .'-' .$associatedUser["User"]["email"];
    	$associatedUser["User"]["email"]             = $associatedUser["User"]["email_confirm"];
    	$associatedUser["User"]["password_confirm"]  = $associatedUser["User"]["password"];
    	$associatedUser["User"]["group_id"]  		 = Configure::read('WebDevelopment.client.group.id');
    	unset($associatedUser["User"]["id"]);

    	// Double check whether the user exists.
    	$matchedUser = $User->find('first', array(
    		'conditions' => array(
    			'User.parent_id' => $associatedUser["User"]["parent_id"],
    			'User.group_id'  => $associatedUser["User"]["group_id"]
    		),
    		'recursive' => 0
    	));

    	if(!empty($matchedUser)){
    		return true;
    	}else{
    		return $User->saveUser($associatedUser);
    	}

    }

/**
 * Get all Web Development user accounts
 */
    public function getAllWebDevelopmentUsers($returnList = false){

    	$User = ClassRegistry::init('User');

    	if($returnList){

    		return $User->find('list', array(
    			'fields' => array('User.id', 'User.name'),
    			'conditions' => array(
	    			'User.group_id' => Configure::read('WebDevelopment.client.group.id'),
    				'User.parent_id IS NOT NULL',
    				'User.active' => 1,
	    		),
    			'recursive' => -1
    		));

    	}else{

    		return $User->find('all', array(
    			'conditions' => array(
    				'User.group_id' => Configure::read('WebDevelopment.client.group.id'),
    				'User.parent IS NOT NULL',
    				'User.active' => 1,
    			),
    			'recursive' => 0
    		));

    	}

    }

}
