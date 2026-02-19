<?php
App::uses('CookieComponent', 'Controller/Component');
class CrazyCookieComponent extends CookieComponent {

/**
 * Offer a way to decrypt encrypted cookie data in AJAX Header
 *
 */
	public function decryptCookieContent($content) {

		if(empty($content)){
			return false;
		}

		$token = $this->_decrypt($content);

		if(is_array($token) && !empty($token[0])){
			return array('_Token' => $token[0]);
		}

		return false;
	}

/**
 * Encrypt cookie content
 *
 * @param String $content
 * @return String
 */
	public function encryptCookieContent($content){

		if(empty($content)){
			return '';
		}

		$this->_encrypted = true;

		return $this->_encrypt($content);
	}
}
?>