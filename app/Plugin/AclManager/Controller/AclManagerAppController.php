<?php
/**
 * Acl Manager
 *
 * A CakePHP Plugin to manage Acl
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author        Frédéric Massart - FMCorz.net
 * @copyright     Copyright 2011, Frédéric Massart
 * @link          http://github.com/FMCorz/AclManager
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

class AclManagerAppController extends AppController {

	/**
	 * beforeFitler
	 */
	public function beforeFilter() {
		/**
		 * Force prefix
		 */
		$prefix = Configure::read('AclManager.prefix');
		$routePrefix = isset($this->request->params['prefix']) ? $this->request->params['prefix'] : false;
		$redirectUrl = null;
		if ($prefix && $prefix != $routePrefix) {
			$redirectUrl = $this->request->referer();
		}elseif ($prefix) {
			$this->request->params['action'] = str_replace($prefix . "_", "", $this->request->params['action']);
			$this->view = str_replace($prefix . "_", "", $this->view);
		}

		parent::beforeFilter();

		if(!empty($redirectUrl)){
			return $this->redirect($redirectUrl);
		}
	}
}

