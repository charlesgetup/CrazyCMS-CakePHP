<?php

App::uses('TaskManagementAppController', 'TaskManagement.Controller');

/**
 *
 * Tasks Controller
 *
*/

class TaskManagementTaskUploadsController extends TaskManagementAppController {

	public function beforeFilter() {
		parent::beforeFilter();

		$this->loadModel ( 'TaskManagement.TaskManagementTask' );
	}


/**
 * index method
 *
 * @return void
 */
	public function admin_index($taskId = null) {

		if(empty($taskId)){

		}else{

		}

	}

/**
 * upload method
 *
 * @return void
 */
	public function admin_upload($taskId) {

		$this->_prepareNoViewAction();

		if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
			if(!$this->TaskManagementTask->hasAny(array('id' => $taskId, 'OR' => array('created_by' => $this->superUserId, 'assignee' => $this->superUserId)))){

				echo json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __('File upload failed.')));
				exit();

			}
		}

		set_time_limit(120); //TODO this need to be tested in live and adjust it if necessary

		if ($this->request->is('post') && $this->request->is('ajax') && isset($this->request->form["upload_file"]) && !empty($this->request->form["upload_file"]) && $this->TaskManagementTaskUpload->isUploadedFile($this->request->form["upload_file"])) {

			$uploadFileMaxSize = $this->_getSystemDefaultConfigSetting('UploadfileSizeLimit', Configure::read('Config.type.system'));

			if(intval($this->request->form["upload_file"]["size"]) > $uploadFileMaxSize){
				echo json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __('Import email marketing blacklisted subscribers file size is over limit.')));
			}else{
				$s3Path 	= Configure::read('TaskManagement.aws.s3.path') .'/' .$taskId;
				$s3Action 	= Configure::read('System.aws.s3.action.put');
				$file 		= $this->request->form["upload_file"]["tmp_name"];
				$newFile	= WWW_ROOT .'files' .DS .'tmp' .DS .str_replace(" ", "", $this->request->form["upload_file"]["name"]);
				if(!file_exists($newFile) &&$this->TaskManagementTaskUpload->moveUploadedFile($file, $newFile)){

					if ($this->TaskManagementTaskUpload->amazonS3StorageManagement($s3Action, $s3Path, array($newFile))) {

						@unlink($file);
						unlink($newFile);
						$data = array(
							'TaskManagementTaskUpload' => array(
								'task_management_task_id' 		=> $taskId,
								'uploaded_by' 					=> $this->superUserId,
								'uploaded_file_download_url' 	=> Configure::read('System.aws.s3.bucket.link.prefix') .$s3Path .'/' .basename($newFile)
							)
						);
						if($this->TaskManagementTaskUpload->saveUploadedFile($data)){
							echo json_encode(array('status' => Configure::read('System.variable.success'), 'message' => __('File is uploaded successfully.')));
						}else{
							echo json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __('File upload failed.')));
						}

					}else{
						echo json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __('File upload failed.')));
					}

				} else {
					echo json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __('File upload failed.')));
				}
			}

		}

		exit();
	}

/**
 * remove method
 *
 * This is an alias of delete function
 *
 * @return void
 */
	public function admin_remove() {

		$this->_prepareNoViewAction();

		if($this->request->is('post') || $this->request->is('ajax')){

			$result = [];

			//NOTICE: email marketing template assets are shared between all template the same user created. We didn't allow the asset to be deleted, because the same assets any used in other template
			// Or in the sold templates

			// When later we need this function, we will come back to finish it

			// Remove email marketing template assest
// 			$templateId = @$this->request->data['templateId'];
// 			if(!empty($templateId)){

// 				$this->loadModel("EmailMarketing.EmailMarketingTemplate");

// 				if (!$this->EmailMarketingTemplate->exists($templateId)) {

// 					$logType 	 = Configure::read('Config.type.taskmanagement');
// 					$logLevel 	 = Configure::read('System.log.level.critical');
// 					$logMessage  = __('Tried to delete assets of a non-existing email marketing template.');
// 					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

// 					$logType 	 = Configure::read('Config.type.taskmanagement');
// 					$logLevel 	 = Configure::read('System.log.level.critical');
// 					$logMessage  = __('User (#' .$this->superUserId .') tried to delete assets of a non-existing email marketing template. (Passed email marketing template ID: ' .$templateId .')');
// 					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

// 					throw new NotFoundException(__('Invalid template'));
// 				}

// 				$emailMarketingUserId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($this->superUserId);
// 				if(!empty($emailMarketingUserId)){

// 					if(!$this->EmailMarketingTemplate->hasAny(array('id' => $templateId, 'email_marketing_user_id' => $emailMarketingUserId))){

// 						$this->loadModel("EmailMarketing.EmailMarketingPurchasedTemplate");
// 						if(!$this->EmailMarketingTemplate->hasAny(array('id' => $templateId, 'email_marketing_user_id' => $emailMarketingUserId))){

// 						}

// 					}

// 				}

// 			}else{

// 			}

			echo json_encode($result);
		}

		exit();
	}

/**
 * delete method
 *
 * @return void
 */
	public function admin_delete($id) {

		if (!$this->TaskManagementTaskUpload->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
			if(!$this->TaskManagementTaskUpload->hasAny(array('id' => $id, 'uploaded_by' => $this->superUserId))){

				throw new ForbiddenActionException($this->modelClass, "delete");

			}
		}

		if($this->request->is('post') || $this->request->is('delete')){

			$uploadedFile = $this->TaskManagementTaskUpload->browseBy('id', $id);

			if ($this->TaskManagementTaskUpload->deleteTaskManagementTaskUpload($id)) {

				$this->TaskManagementTaskUpload->deleteUploadedFileInS3($uploadedFile);

				$this->_showSuccessFlashMessage($this->TaskManagementTaskUpload, __('Uploaded file has been deleted successfully.'));
			}else{
				$this->_showErrorFlashMessage($this->TaskManagementTaskUpload, __('Failed to delete uploaded file.'));
			}

		}

	}

}