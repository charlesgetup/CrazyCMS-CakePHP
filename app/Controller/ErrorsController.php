<?php
/**
 * Static content controller.
 *
 * This file will render error views by throwing exceptions
 *
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 */
class ErrorsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Errors';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display($errorCode = 404) {

		switch($errorCode){
			case 404:
				throw new NotFoundException();
				break;
			case 500:
				throw new InternalErrorException();
				break;
			default:
				throw new NotFoundException();
				break;
		}

	}
}
