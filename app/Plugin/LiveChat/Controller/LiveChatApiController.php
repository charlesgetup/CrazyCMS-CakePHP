<?php
App::uses('ApiController', 'Controller');

class LiveChatApiController extends ApiController {

	public function beforeFilter() {

		parent::beforeFilter();

		$this->loadModel('LiveChat.LiveChatUser');

		$isValid = $this->_verifyAPICode($apiCode = $this->request->pass[0], $this->plugin, $this->LiveChatUser->table, $apiCodeDBColumn = 'livechat_api_code');

		if(!$isValid){

			exit();
		}
	}

	public function getPurchasedOperatorAmount($apiCode){

		$liveChatUser = $this->LiveChatUser->browseBy('livechat_api_code', $apiCode, false);

		echo json_encode(array(
			'success' => true,
			'amount'  => intval($liveChatUser['LiveChatUser']['operator_amount'])
		));

		exit();
	}
}

?>