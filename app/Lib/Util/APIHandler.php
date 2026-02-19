<?php
Class APIHandler {

/**
 * Call a third party API function
 * @param string $url (starts with http(s)://)
 * @param string $method
 * @param string/array $payload (json)
 * @param string $username
 * @param string $password
 */

	public function callAPIFunction($url, $method, $payload, $username = null, $password = null){

		if(!empty($username) && !empty($password)){

			if(stristr($url, "https://")){

				$url = "https://" .$username .":" .$password ."@" .substr($url, 8);

			}else{

				$url = "http://" .$username .":" .$password ."@" .substr($url, 7);
			}
		}

		if(!empty($payload) && is_string($payload)){
			$payload = json_decode($payload, true);
		}

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		if(strtoupper($method) == "POST"){

			// POST call

			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

			if(!empty($payload) && is_array($payload)){

				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

			}else{

				$payload = ''; // Clear invalid data for strlen() function
			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		}else{

			// GET call

			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		}

		$result = curl_exec($ch);
		$error  = curl_error($ch);

		if(!empty($error)){

			curl_close($ch);

			return array(
				'success' => false,
				'error'	  => print_r($error, true) // Force convert array to string
			);

		}else{

			$resultDecoded = json_decode($result, true);
			if($resultDecoded != null && $resultDecoded !== false){
				$result = $resultDecoded;
			}
		}

		curl_close($ch);

		return $result;
	}

	public function cryptoJsAesEncrypt($value, $passphrase, $salt){
		$salted = '';
		$dx = '';
		while (strlen($salted) < 48) {
			$dx = md5($dx.$passphrase.$salt, true);
			$salted .= $dx;
		}
		$key = substr($salted, 0, 32);
		$iv  = substr($salted, 32,16);
		$encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
		return base64_encode($iv.$encrypted_data);
	}

	public function cryptoJsAesDecrypt($value, $passphrase, $salt){
		$data = base64_decode($value);
		$iv = substr($data, 0, 16);

		if(strlen($iv) != 16){
			return '';
		}

		$ct = substr($data, 16);

		$concatedPassphrase = $passphrase.$salt;
		$md5 = array();
		$md5[0] = md5($concatedPassphrase, true);
		$result = $md5[0];
		for ($i = 1; $i < 3; $i++) {
			$md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
			$result .= $md5[$i];
		}
		$key = substr($result, 0, 32);
		$data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
		return json_decode($data, true);
	}

}
?>