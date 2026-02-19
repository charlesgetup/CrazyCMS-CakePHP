<?php

App::uses('ExtendPHP', 'Util');

/**
 * This helper is used by explicitly checking permission in the view
 * @author yanfengli
 *
 */
class PermissionsHelper extends AppHelper {

    var $helpers = array('Session');

    protected  $extendPHP;

    public function __construct(View $View, $settings = array()) {

    	parent::__construct($View, $settings);

    	$this->extendPHP = new ExtendPHP();
    }

    public function check($acl, $path){

        $valid = $acl->check(
            array(
                'model' 		=> 'Group',
                'foreign_key' 	=> AuthComponent::user('group_id')
            ),
            $path
        );

        if(!$valid && $this->Session->check('AssociatedUsers')){
        	$associatedUsers = $this->Session->read('AssociatedUsers');
            if(!empty($associatedUsers) && is_array($associatedUsers)){
            	foreach($associatedUsers as $associatedUser){
                    if(empty($associatedUser['User']['active'])){
                        // Only active account can be used to check permission
                    	continue;
                    }
            		$valid = $acl->check(
                        array(
                            'model' => 'Group',
                            'foreign_key' => $associatedUser['User']['group_id']
                        ),
                        $path
                    );
                    if($valid){
                    	break;
                    }
            	}
            }
        }

        return $valid;
    }

    public function isAdmin(){
    	return AuthComponent::user('group_id') === Configure::read('System.admin.group.id');
    }

    public function isClient(){
    	$clientGroupId = Configure::read('System.client.group.id');
    	if(AuthComponent::user('group_id') === $clientGroupId){
    		return true;
    	}else{
    		$associatedUsers = $this->Session->read('AssociatedUsers');
    		$isClient = false;
    		foreach($associatedUsers as $associatedUser){
    			if($associatedUser['User']['group_id'] == $clientGroupId){
    				$isClient = true;
    				break;
    			}
    		}
    		return $isClient;
    	}
    }

    public function isStaff(){
    	$userGroupInfo = AuthComponent::user('Group');
    	if(stristr($userGroupInfo['name'], Configure::read('System.staff.group.name'))){
    		return true;
    	}else{
    		$associatedUsers = $this->Session->read('AssociatedUsers');
    		$isStaff = false;
    		$Group = ClassRegistry::init('Group');
    		foreach($associatedUsers as $associatedUser){
    			$groupName = $Group->getGroupName($associatedUser['User']['group_id']);
    			if(!empty($groupName) && stristr($groupName, Configure::read('System.staff.group.name'))){
    				$isStaff = true;
    				break;
    			}
    		}
    		return $isStaff;
    	}
    }

    public function isManager(){
    	$userGroupInfo = AuthComponent::user('Group');
    	if(stristr($userGroupInfo['name'], Configure::read('System.manager.group.name'))){
    		return true;
    	}else{
    		$associatedUsers = $this->Session->read('AssociatedUsers');
    		$isManager = false;
    		$Group = ClassRegistry::init('Group');
    		foreach($associatedUsers as $associatedUser){
    			$groupName = $Group->getGroupName($associatedUser['User']['group_id']);
    			if(!empty($groupName) && stristr($groupName, Configure::read('System.manager.group.name'))){
    				$isManager = true;
    				break;
    			}
    		}
    		return $isManager;
    	}
    }


    /*
     *	Following methods are used to handle special requirement
     */

    public function showRefundSection(){
    	$authUserGroupId = AuthComponent::user('group_id');

    	if($this->isAdmin()){
    		return true;
    	}

    	// If user is a client, don't show refund section until the client made a refund
    	if($this->isClient()){
    		$PaymentInvoice = ClassRegistry::init('PaymentInvoice');
    		$conditions = array(
    			'PaymentInvoice.user_id' => AuthComponent::user('id'),
    			'PaymentInvoice.status'	 => Configure::read('Payment.invoice.status.refund')
    		);
    		if($PaymentInvoice->hasAny($conditions)){
    			return true;
    		}
    	}

    	return false;
    }
}
?>