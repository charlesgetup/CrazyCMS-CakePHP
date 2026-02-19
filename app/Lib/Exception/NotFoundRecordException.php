<?php
/**
 * Represents an HTTP 404 error.
 *
 * @package       Cake.Error
 */
class NotFoundRecordException extends NotFoundException {

	/**
	 * Constructor
	 *
	 * @param string $modelName If no message is given 'Not Found' will be the message
	 * @param integer $code Status code, defaults to 404
	 * @param string $specialModelName If the $modelName is not related to the error object, we will send a specified name for error object
	 */
	public function __construct($modelName = null, $specialModelName = null, $code = 404) {
		if (empty($modelName)) {
			$message = __('Not Found');
		}else{
			$message = Inflector::humanize(Inflector::underscore(empty($specialModelName) ? $modelName : $specialModelName) .' not found');
			$message = __(ucwords($message));
		}
		parent::__construct($message, $code);
	}

}