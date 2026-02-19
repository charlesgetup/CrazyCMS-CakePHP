<?php
class EncryptionTask extends Shell {

/**
 * @see Shell::initialize()
 *
 */
	public function initialize() {

	}

/**
 * @see Shell::startup()
 */
	public function startup() {
		parent::startup();
	}

	public function base64Encode($originalData, $xorMask){
		$encryptedData 	= $originalData ^ $xorMask;
		$encryptedData 	= base64_encode($encryptedData);

		// 15254- the encoding adds one or two extraneous = signs, take them off
		$encryptedData 	= preg_replace('/=$/','',$encryptedData);
		$encryptedData 	= preg_replace('/=$/','',$encryptedData);

		$encryptedData 	= urlencode($encryptedData);
		return $encryptedData;
	}

	public function base64Decode($encryptedData, $xorMask){
		$encryptedData 	= urldecode($encryptedData);

		$encryptedData = base64_decode ( $encryptedData );
		$data = $encryptedData ^ $xorMask;

		return $data;
	}

}