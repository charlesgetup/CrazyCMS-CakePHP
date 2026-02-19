<?php
/**
 * Represents an HTTP 403 error.
 *
 * @package       Cake.Error
 */
class ForbiddenActionException extends ForbiddenException {

	/**
	 * Constructor
	 *
	 * @param string $message If no message is given 'Forbidden' will be the message
	 * @param integer $code Status code, defaults to 403
	 */
	public function __construct($modelName = null, $actionName = null, $code = 403) {

		if (empty($modelName)) {
			$message = __('Forbidden');
		}else{
			$modelName = Inflector::humanize(Inflector::underscore($modelName));
			if(strstr($actionName, "?")){
				$message = str_replace("?", $modelName, $actionName);
				$message = __(ucwords($message ." is forbidden."));
			}else{
				$message = __(ucwords($actionName .' ' .strtolower(Inflector::humanize(Inflector::underscore($modelName))) ." is forbidden."));
			}
		}
		parent::__construct($message, $code);
	}

}
?>