<?php
/**
 * Represents an HTTP 405 error.
 *
 * @package       Cake.Error
 */
class IncorrectUseMethodException extends MethodNotAllowedException {

	/**
	 * Constructor
	 *
	 * @param string $message If no message is given 'Method Not Allowed' will be the message
	 * @param integer $code Status code, defaults to 405
	 */
	public function __construct($message = null, $code = 405) {
		if (empty($message)) {
			$message = __('Method Not Allowed');
		}
		parent::__construct($message, $code);
	}

}
?>