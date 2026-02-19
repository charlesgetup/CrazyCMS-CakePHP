<?php
App::uses('AppController', 'Controller');
/**
 * API Controller
 *
 * This is the basic controller for all API functionalities. All other API controller in each plugin should extends it
*/
class ApiController extends AppController {

	public function beforeFilter() {

		$this->Auth->allow();

		parent::beforeFilter();

		$this->_prepareNoViewAction();
	}

	protected function _verifyAPICode($apiCode, $plugin, $dbTableName, $apiCodeDBColumn){

		if(empty($apiCode) || empty($plugin) || empty($dbTableName) || empty($apiCodeDBColumn)){
			return false;
		}

		$this->loadModel('User');
		$user = $this->User->find('first', array(
			'conditions' => array(
				$plugin .'.' .$apiCodeDBColumn => $apiCode
			),
			'joins' => array(
    			array(
    				'table' => $dbTableName,
    				'alias' => $plugin,
    				'type' => 'inner',
    				'conditions' => array(
    					$plugin .'.user_id = User.id'
    				)
    			)
    		),
    		'contain' => false
		));

		return empty($user['User']['active']) ? false : true;
	}
}
?>