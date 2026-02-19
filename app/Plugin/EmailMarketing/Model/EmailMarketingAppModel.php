<?php

App::uses('AppModel', 'Model');

class EmailMarketingAppModel extends AppModel {

/**
 * checkRecordBelongsEmailMarketingUser method
 *
 * The $userId is the ID field value of users table, not the ID field value of email_marketing_users table
 * This method cannot be used to check EmailMarketingUser model record
 *
 * @param int $recordId
 * @param int $userId
 */
	public function checkRecordBelongsEmailMarketingUser($recordId, $superUserId){
		if(!empty($recordId) && is_numeric($recordId) && !empty($superUserId) && is_numeric($superUserId)){

			$count = $this->find('all', array(
				'conditions' => array(
					$this->alias .'.id' => $recordId
				),
				'joins' => array(
					array(
						'table' => 'email_marketing_users',
						'alias' => 'User',
						'type' => 'inner',
						'conditions' => array(
							$this->alias .'.email_marketing_user_id = User.id',
						)
					),
					array(
						'table' => 'users',
						'alias' => 'SuperUser',
						'type' => 'inner',
						'conditions' => array(
							'SuperUser.id = User.user_id',
							'SuperUser.active = 1',
							'SuperUser.parent_id IS NOT NULL',
							'SuperUser.group_id = ' .Configure::read('EmailMarketing.client.group.id'),
							"SuperUser.id = {$superUserId}"
						)
					)
				),
			));

			return !empty($count);

		}

		return false;
	}

/**
 * Get related email makreting user account ID (it is the foreign key "user_id", not that record "id") based on the User record ID
 * @param string/numberic $superUserId
 * @return mix (integer for related email makreting user record ID; if not found, return NULL; if param not valid, return FALSE)
 */
	public function superUserIdToEmailMarketingUserAccountId($superUserId, $ignoreActiveStatus = FALSE){
		if(empty($superUserId)){
			return false;
		}

		$emailMarketingGroupId = Configure::read('EmailMarketing.client.group.id');
		$emailMarketingUserAccountId = null;

		$User = ClassRegistry::init('User');
		$userDetail = $User->browseBy('id', $superUserId);

		if(empty($userDetail['User']['active']) && !$ignoreActiveStatus){
			return false;
		}

		if($userDetail['User']['group_id'] != $emailMarketingGroupId){
			$conditions = array(
				'parent_id' => $userDetail['User']['id'],
				'group_id' => $emailMarketingGroupId
			);
			if(!$ignoreActiveStatus){ $conditions['active'] = 1; }
			$userDetail = $User->find('first', array(
				'conditions' => $conditions,
				'contain' => false
			));
			$emailMarketingUserAccountId = @$userDetail['User']['id'];
		}else{
			$emailMarketingUserAccountId = $userDetail['User']['id'];
		}

		return $emailMarketingUserAccountId;
	}

/**
 * Get related email makreting user record ID (not foreign key "user_id") based on the email makreting user account record ID (foreign key "user_id")
 * @param string/numberic $superUserId
 * @return mix (integer for related email marketing user record ID; if not found, return NULL; if param not valid, return FALSE)
 */
	public function emailMarketingUserAccountIdToEmailMarketingUserId($emailMarketingUserAccountId){

		if(empty($emailMarketingUserAccountId)){
			return false;
		}

		$EmailMarketingUser = ClassRegistry::init('EmailMarketing.EmailMarketingUser');

		$emailMarketingUserObj = $EmailMarketingUser->browseBy('user_id', $emailMarketingUserAccountId);

		return @$emailMarketingUserObj['EmailMarketingUser']['id'];
	}

/**
 * Get related email makreting user record ID (not foreign key "user_id") based on the User record ID
 * @param string/numberic $superUserId
 * @return mix (integer for related email marketing user record ID; if not found, return NULL; if param not valid, return FALSE)
 */
	public function superUserIdToEmailMarketingUserId($superUserId){
		if(empty($superUserId)){
			return false;
		}

		$emailMarketingUserAccountId = $this->superUserIdToEmailMarketingUserAccountId($superUserId);

		return $this->emailMarketingUserAccountIdToEmailMarketingUserId($emailMarketingUserAccountId);
	}

/**
 * Get User record ID based on the related email marketing user record ID
 * @param string/numberic $emailMarketingUserId
 * @return mix (integer for User record ID; if not found, return NULL; if param not valid, return FALSE)
 */
	public function emailMarketingUserIdToSuperUserId($emailMarketingUserId){
		if(empty($emailMarketingUserId)){
			return false;
		}

		$User = ClassRegistry::init('User');

		$superUser = $User->find('first', array(
			'fields' => array('User.parent_id'),
			'conditions' => array(
				'EmailMarketingUserAlias.id' => $emailMarketingUserId
			),
			'joins' => array(
				array(
					'table' => 'email_marketing_users',
					'alias' => 'EmailMarketingUserAlias',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingUserAlias.user_id = User.id',
					)
				)
			),
		));

		return @$superUser['User']['parent_id'];
	}
}
