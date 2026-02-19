<?php
App::uses('TaskManagementAppModel', 'TaskManagement.Model');

/**
 * Task Upload Model
 *
 */
class TaskManagementTaskUpload extends TaskManagementAppModel {

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id,$table,$ds);

    }

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'uploaded_file_download_url' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required' => true,
				'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Uploader' => array(
			'className'  => 'User',
			'foreignKey' => 'uploaded_by',
            'dependent'  => false
		),
		'TaskManagementTask' => array(
			'className'    => 'TaskManagement.TaskManagementTask',
			'foreignKey'   => 'task_management_task_id',
			'dependent'    => false
		),
	);

	public function saveUploadedFile($data){
		if(!empty($data['TaskManagementTaskUpload']['uploaded_file_download_url']) && !$this->hasAny(array('uploaded_file_download_url' => $data['TaskManagementTaskUpload']['uploaded_file_download_url']))){
			$this->create();
			if($this->saveAll($data , array('validate' => 'first'))){
				return true;
			}else{
				return false;
			}
		}
		return false;
	}

	public function deleteTaskManagementTaskUpload($id){
		return $this->delete($id, true);
	}

	public function deleteUploadedFileInS3($filesData){

		if(empty($filesData)){
			return false;
		}else{
			if(isset($filesData['TaskManagementTaskUpload'])){
				$filesData = array($filesData);
			}

			foreach($filesData as $uploadedFile){

				if(isset($uploadedFile['TaskManagementTaskUpload'])){
					$uploadedFile = $uploadedFile['TaskManagementTaskUpload'];
				}

				try{

					$file = basename($uploadedFile['uploaded_file_download_url']);
					$path = explode(Configure::read('System.aws.s3.bucket.name') ."/", $uploadedFile['uploaded_file_download_url']);
					$path = array_pop($path);
					$path = str_replace($file, "", $path);

					$this->amazonS3StorageManagement(Configure::read('System.aws.s3.action.delete'), $path, array($file));

				}catch(AmazonS3Exception $exception){

					$logType 	 = Configure::read('Config.type.taskmanagement');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("Delete Task Uploaded File Exception: ") .'<br />'.
								   __("Error Message: ") . $exception->getMessage() .'<br />'.
								   __("Line Number: ") .$exception->getLine() .'<br />'.
								   __("Trace: ") .$exception->getTraceAsString() .'<br />'.
      							   __('Log Data: Passed uploaded file ID #') .$uploadedFile['id'];
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					return false;

				}catch(Exception $e){

					$logType 	 = Configure::read('Config.type.taskmanagement');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("Delete Task Uploaded File Exception: ") .'<br />'.
								   __("Error Message: ") . $e->getMessage() .'<br />'.
								   __("Line Number: ") .$e->getLine() .'<br />'.
								   __("Trace: ") .$e->getTraceAsString();
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					return false;

				}

			}
		}

		return true;

	}
}
