<?php

App::uses('AppModel', 'Model');

class LiveChatAppModel extends AppModel {

/**
 * checkRecordBelongsLiveChatUser method
 *
 * The $userId is the ID field value of users table, not the ID field value of live_chat_users table
 * This method cannot be used to check LiveChatUser model record
 *
 * @param int $recordId
 * @param int $userId
 */
	public function checkRecordBelongsLiveChatUser($recordId, $superUserId){
		if(!empty($recordId) && is_numeric($recordId) && !empty($superUserId) && is_numeric($superUserId)){

			$count = $this->find('all', array(
				'conditions' => array(
					$this->alias .'.id' => $recordId
				),
				'joins' => array(
					array(
						'table' => 'live_chat_users',
						'alias' => 'User',
						'type' => 'inner',
						'conditions' => array(
							$this->alias .'.live_chat_user_id = User.id',
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
							'SuperUser.group_id = ' .Configure::read('LiveChat.client.group.id'),
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
 * Get related live chat user account ID (it is the foreign key "user_id", not that record "id") based on the User record ID
 * @param string/numberic $superUserId
 * @return mix (integer for related live chat user record ID; if not found, return NULL; if param not valid, return FALSE)
 */
	public function superUserIdToLiveChatUserAccountId($superUserId, $ignoreActiveStatus = FALSE){
		if(empty($superUserId)){
			return false;
		}

		$liveChatGroupId = Configure::read('LiveChat.client.group.id');
		$liveChatUserAccountId = null;

		$User = ClassRegistry::init('User');
		$userDetail = $User->browseBy('id', $superUserId);

		if(empty($userDetail['User']['active']) && !$ignoreActiveStatus){
			return false;
		}

		if($userDetail['User']['group_id'] != $liveChatGroupId){
			$conditions = array(
				'parent_id' => $userDetail['User']['id'],
				'group_id' => $liveChatGroupId
			);
			if(!$ignoreActiveStatus){ $conditions['active'] = 1; }
			$userDetail = $User->find('first', array(
				'conditions' => $conditions,
				'contain' => false
			));
			$liveChatUserAccountId = @$userDetail['User']['id'];
		}else{
			$liveChatUserAccountId = $userDetail['User']['id'];
		}

		return $liveChatUserAccountId;
	}

/**
 * Get related live chat user record ID (not foreign key "user_id") based on the User record ID
 * @param string/numberic $superUserId
 * @return mix (integer for related live chat user record ID; if not found, return NULL; if param not valid, return FALSE)
 */
	public function superUserIdToLiveChatUserId($superUserId){
		if(empty($superUserId)){
			return false;
		}

		$liveChatUserAccountId = $this->superUserIdToLiveChatUserAccountId($superUserId);

		if(empty($liveChatUserAccountId)){
			return false;
		}

		$LiveChatUser = ClassRegistry::init('LiveChat.LiveChatUser');

		$liveChatUserObj = $LiveChatUser->browseBy('user_id', $liveChatUserAccountId);

		return @$liveChatUserObj['LiveChatUser']['id'];
	}

/**
 * Get User record ID based on the related live chat user record ID
 * @param string/numberic $liveChatUserId
 * @return mix (integer for User record ID; if not found, return NULL; if param not valid, return FALSE)
 */
	public function liveChatUserIdToSuperUserId($liveChatUserId){
		if(empty($liveChatUserId)){
			return false;
		}

		$User = ClassRegistry::init('User');

		$superUser = $User->find('first', array(
			'fields' => array('User.parent_id'),
			'conditions' => array(
				'LiveChatUserAlias.id' => $liveChatUserId
			),
			'joins' => array(
				array(
					'table' => 'live_chat_users',
					'alias' => 'LiveChatUserAlias',
					'type' => 'inner',
					'conditions' => array(
						'LiveChatUserAlias.user_id = User.id',
					)
				)
			),
		));

		return @$superUser['User']['parent_id'];
	}
}
