<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	public $Log;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id,$table,$ds);

		if($this->name != "Log"){
			$this->Log = ClassRegistry::init ( 'Log' ); // We need to log all the actions
		}
	}

/**
 * Return the absolute path which stores user personal files.
 *
 * Note: this path is currently used to store temporary files. All permanent files are stored in AWS S3 (public files), or DB (private files)
 */
	public function getUserFileSavedPath($returnAbsolutePath = true) {
		return ($returnAbsolutePath ? ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR : "") .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR;
	}

/**
 * Separate this function is for unit test. In unit test, we can create a stub of the model which has file upload function and override this method
 */
	public function checkUploadedTempFile($tmp_name) {
		return is_uploaded_file($tmp_name);
	}

/**
 * Separate this function is for unit test. In unit test, we can create a stub of the model which has file upload function and override this method
 */
	public function moveUploadedFile($from, $to) {
		return move_uploaded_file($from, $to);
	}

	public function isUploadedFile($params) {
		if ((isset($params['error']) && $params['error'] == 0) &&
		(!empty( $params['tmp_name']) && $params['tmp_name'] != 'none') &&
		(intval($params['size']) > 0 && filesize($params['tmp_name']) > 0)
		) {
			return $this->checkUploadedTempFile($params['tmp_name']);
		}
		return false;
	}

/**
 *
 * @param string $action
 * @param string $path (if get, it is local path; if put, it is remote path)
 * @param array $files
 * @param bool $isPublic
 * @return boolean
 */
	public function amazonS3StorageManagement($action, $path, $files, $isPublic = true){

		$allowedActions = array(
			'get' 		=> Configure::read('System.aws.s3.action.get'),
			'put' 		=> Configure::read('System.aws.s3.action.put'),
			'delete' 	=> Configure::read('System.aws.s3.action.delete'),
			'list'		=> Configure::read('System.aws.s3.action.list')
		);

		if(!in_array($action, array_values($allowedActions)) || empty($path) || ($action != Configure::read('System.aws.s3.action.list') && (empty($files) || !is_array($files)))){
			return false;
		}

		App::uses('AmazonS3', 'AmazonS3.Lib');
		$AmazonS3 = new AmazonS3(array(Configure::read('System.aws.s3.accesskey'), Configure::read('System.aws.s3.secret'), Configure::read('System.aws.s3.bucket.name')));

		$result = true;

		switch($action){
			case $allowedActions['get']:

				if(!file_exists($path)){
					return false;
				}
				foreach($files as $file){
					$AmazonS3->get($file, $path);
				}

				break;
			case $allowedActions['put']:

				if(substr($path, 0, 1) == "/"){
					$path = substr($path, 1);
				}

				if($isPublic === true){
					$AmazonS3->amazonHeaders = array(
						'x-amz-acl' => 'public-read'
					);
				}

				foreach($files as $file){
					$AmazonS3->put($file, $path);
				}

				break;
			case $allowedActions['delete']:

				if(substr($path, -1) != "/"){
					$path .= '/';
				}

				foreach($files as $file){
					$AmazonS3->delete($path .$file);
				}

				break;
			case $allowedActions['list']:
				$result = $AmazonS3->listBucket($path);
				break;
		}

		return $result;
	}

/**
 * Find single record based on one field value
 * @param string $field
 * @param string $value
 * @param string $contain
 * @param string $order
 * @return mixed <multitype:, NULL, mixed>
 */
    public function browseBy($field, $value, $contain = FALSE, $order = array()){
        return $this->__fetchDataFromDB('first', $field, $value, $contain, $order);
    }

/**
 * Find multiple records based on one field value
 * @param string $field
 * @param string $value
 * @param string $contain
 * @param string $order
 * @return mixed <multitype:, NULL, mixed>
 */
    public function findAll($field, $value, $contain = FALSE, $order = array()){
        return $this->__fetchDataFromDB('all', $field, $value, $contain, $order);
    }

/**
 * Unbinds validation rules and optionally sets the remaining rules to required.
 *
 * @param string $type 'Remove' = removes $fields from $this->validate
 *                       'Keep' = removes everything EXCEPT $fields from $this->validate
 * @param array $fields
 * @param bool $require Whether to set 'required'=>true on remaining fields after unbind
 * @return null
 * @access public
 */
    protected function _unbindValidation($type, $fields, $require=false){
        if ($type === 'remove'){
            $this->validate = array_diff_key($this->validate, array_flip($fields));
        }else if ($type === 'keep'){
            $this->validate = array_intersect_key($this->validate, array_flip($fields));
        }

        if ($require === true){
            foreach ($this->validate as $field=>$rules){
                if (is_array($rules)){
                    $rule = key($rules);

                    $this->validate[$field][$rule]['required'] = true;
                }else{
                    $ruleName = (ctype_alpha($rules)) ? $rules : 'required';

                    $this->validate[$field] = array($ruleName=>array('rule'=>$rules,'required'=>true));
                }
            }
        }
    }

    protected function _dateArrayToString($dateArr){
    	$dateArr['month'] 	= str_pad($dateArr['month'], 2, "0", STR_PAD_LEFT);
    	$dateArr['day'] 	= str_pad($dateArr['day'], 2, "0", STR_PAD_LEFT);
    	$dateArr['hour'] 	= str_pad($dateArr['hour'], 2, "0", STR_PAD_LEFT);
    	$dateArr['min'] 	= str_pad($dateArr['min'], 2, "0", STR_PAD_LEFT);

        $dateStr =  $dateArr['year'] .'-' .
                    $dateArr['month'] .'-' .
                    $dateArr['day'] .' ' .
                    ($dateArr['meridian'] == "pm" ? $dateArr['hour'] + 12 : $dateArr['hour']) .':' .
                    $dateArr['min'] .':00';
        return $dateStr;
    }

/**
 * Used to lock MYSQL tables
 */
    protected function _lockTableRW() {
        $this->_lockTable('READ');
        $this->_lockTable('WRITE');
    }

/**
 * Used to lock MYSQL tables
 */
    protected function _lockTable($type = 'READ') {
        $dbo = $this->getDataSource();
        $dbo->execute('LOCK TABLES '.$this->table.' '.$type);
    }

/**
 * Used to lock MYSQL tables
 */
    protected function _unlockTables() {
        $dbo = $this->getDataSource();
        $dbo->execute('UNLOCK TABLES');
    }

/**
 * Fetch data from DB
 * $type -> 'all', 'first'
 */
    private function __fetchDataFromDB($type, $field, $value, $contain = array(), $order = array()){
    	$field = strtolower($field);
    	return $this->find($type, array(
                'conditions' => array(
                    "{$this->alias}.{$field}" => $value,
                ),
                'contain' => $contain,
                'order'   => $order
            )
        );
    }
}
