<?php
App::uses('AuthComponent', 'Controller/Component');
class CrazyAuthComponent extends AuthComponent {

/**
 * Override isAuthorized function
 *
 * This is the default method to check controller action permissions
 *
 * @see AuthComponent::isAuthorized()
 */
	public function isAuthorized($user = null, CakeRequest $request = null) {
		if (empty($user) && !$this->user()) {
			return false;
		}
		if (empty($user)) {
			$user = $this->user();
		}
		if (empty($request)) {
			$request = $this->request;
		}
		if (empty($this->_authorizeObjects)) {
			$this->constructAuthorize();
		}
		foreach ($this->_authorizeObjects as $authorizer) {
			if ($authorizer->authorize($user, $request) === true) {
				return true;
			}else{
				// If this user is not valid, check the associated user accounts
				$User 			 = ClassRegistry::init('User');
				$userParentId 	 = $user['parent_id'] || $user['id'];
				$associatedUsers = $User->getAssociateUsers($userParentId);
				foreach($associatedUsers as $associatedUser){
					if(empty($associatedUser['User']['active'])){
						// Only active account can be used to check permission
						continue;
					}
					if($authorizer->authorize($associatedUser['User'], $request) === true){
						return true;
					}
				}
			}
		}
		return false;
	}

}
?>