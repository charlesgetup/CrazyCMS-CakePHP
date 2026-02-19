<?php
/**
 * WebmasterAppController
 *
 * PHP 5
 *
 * Copyright 2013, Jad Bitar (http://jadb.io)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2013, Jad Bitar (http://jadb.io)
 * @link          http://github.com/gourmet/affiliates
 * @since         0.1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');
App::uses('CommonEventManager', 'Webmaster.Event');

/**
 * Webmaster App Controller
 *
 * @package       Webmaster.Controller
 */
class WebmasterAppController extends AppController {

/**
 * {@inheritdoc}
 */
	public $plugin = 'Webmaster';

/**
 * Create generic alert message to display.
 *
 * The method can be used in two ways, using the `AppController::$alertMessages` and setting
 * up some defaults or direclty passing the message and options.
 *
 * Common defaults are set up by the `Controller.alertMessages` event and can be overridden
 * at anytime before calling this method.
 *
 *		// manually
 *  	$this->alert('foo bar', array('redirect' => true, 'level' => 'warning'));
 *
 *		// pre-defined
 *  	$this->alertMessages['my_message'] = array(
 *   		'message' => 'foo bar',
 *     	'redirect' => true, // false, '', array() '/url'
 *      'level' => 'warning', // success, error etc
 *    );
 *
 *		$this->alert('my_message');
 *
 *		// pre-defined with custom level option
 *  	$this->alert('my_message', array('level' => 'success'));
 *
 * @param string $msg The message to show to the user.
 * @param array $options Array of configuration options.
 * @return void
 * @see CommonEventListener::controllerAlertMessages()
 */
	public function alert($msg, $options = array()) {
		$defaults = array(
			'level' => 'success',
			'redirect' => false,
			'plugin' => 'Common',
			'code' => 0,
			'element' => 'alerts/default',
			'dismiss' => false,
			'key' => 'flash'
		);

		if ($msg instanceof Exception) {
			$options = array_merge(array('level' => 'error'), $options);
			$msg = $msg->getMessage();
		}

		if (!empty($this->alertMessages[$msg])) {
			if (!is_array($this->alertMessages[$msg])) {
				$msg = $this->alertMessages[$msg];
			} else if (!empty($this->alertMessages[$msg]['message'])) {
				$options = array_merge($this->alertMessages[$msg], $options);
				$msg = $options['message'];
				unset($options['message']);
			}
		}

		$insert = array('modelName' => strtolower($this->modelName));
		$msg = ucfirst(String::insert($msg, array_merge($insert, $options), array('clean' => true)));

		$options = array_merge($defaults, $options);
		$params = array('code' => $options['code'], 'plugin' => $options['plugin']);

		// View element
		$element = $options['element'];
		if (!empty($this->params['prefix'])) {
			$element = explode('/', $options['element']);
			array_push($element, $this->params['prefix'] . '_' . array_pop($element));
			$element = implode('/', $element);
		}

		$elementPath = 'View' . DS . 'Elements' . DS . $element . '.ctp';
		if (!empty($options['plugin']) && CakePlugin::loaded($options['plugin'])) {
			$element = $options['plugin'] . '.' . $element;
			$elementPath = CakePlugin::path($options['plugin']) . $elementPath;
			unset($options['plugin']);
		}

		if (!is_file($elementPath)) {
			$element = $defaults['plugin'] . '.' . $defaults['element'];
		}

		// Redirect URL
		$redirect = $options['redirect'];
		if (true === $redirect) {
			$redirect = $this->referer();
		} else if (
		!empty($this->params['prefix'])
		&& is_array($redirect)
		&& !isset($redirect['prefix'])
		&& !isset($redirect[$this->params['prefix']])
		) {
			$redirect['prefix'] = $this->params['prefix'];
			$redirect[$this->params['prefix']] = true;
		}

		$key = $options['key'];

		unset($options['element'], $options['key'], $options['redirect']);

		// Ajax rendering
		if ($this->request->is('ajax')) {
			$params['level'] = $options['level'];
			$params['message'] = $msg;
			$redirect = !$redirect ? false : Router::url($redirect);
			$this->set('json', compact('params', 'redirect'));
			return;
		}

		// Normal rendering
		$this->Session->setFlash($msg, $element, $options, $key);
		if (!empty($redirect)) {
			$this->redirect($redirect);
		}
	}

	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * Overrides `Controller::getEventManager()`.
 *
 * - Use `CommonEventManager` instead of `CakeEventManager`.
 * - Auto-load the 'Controller' and this controller's specific events (using scopes).
 *
 * @return CommonEventManager
 */
	public function getEventManager() {
		if (empty($this->_eventManager)) {
			$this->_eventManager = new CommonEventManager();
			$this->_eventManager->loadListeners($this->_eventManager, 'Controller');
			$this->_eventManager->loadListeners($this->_eventManager, $this->name);
			$this->_eventManager->attach($this->Components);
			$this->_eventManager->attach($this);
		}
		return $this->_eventManager;
	}

/**
 * Trigger an event using the 'Controller' event manager instance instead of the
 * global one.
 *
 * @param string|CakeEvent $event The event key name or instance of CakeEvent.
 * @param object $subject Optional. Event's subject.
 * @param mixed $data Optional. Event's data.
 * @return mixed Result of the event.
 */
	public function triggerEvent($event, $subject = null, $data = null) {
		return $this->getEventManager()->trigger($event, $subject, $data);
	}
}
