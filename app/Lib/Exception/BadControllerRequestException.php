<?php
/**
 * Represents an HTTP 400 error.
 *
 * @package       Cake.Error
 */
class BadControllerRequestException extends BadRequestException {

	/**
	 * Constructor
	 *
	 * @param string $message If no message is given 'Not Found' will be the message
	 * @param integer $code Status code, defaults to 400
	 */
	public function __construct($modelName = null, $code = 400) {
		if (empty($modelName)) {
			$message = __('Bad Request');
		}else{
			$message = __(ucwords('Bad request to ' .strtolower(Inflector::humanize(Inflector::underscore($modelName)))));
		}
		parent::__construct($message, $code);
	}

}