<?php

App::uses('TaskManagementAppController', 'TaskManagement.Controller');

/**
 *
 * Tasks Controller
 *
 */

class TaskManagementTaskCommentsController extends TaskManagementAppController {

    public function beforeFilter() {
        parent::beforeFilter();

        $this->loadModel ( 'TaskManagement.TaskManagementTask' );

        $this->Security->unlockedFields = array('write-comment');
    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index() {

    }

/**
 * add method
 *
 * @return void
 */
    public function admin_add($taskId, $parentTaskId = null){

    	$this->_prepareAjaxPostAction();

    	if (($this->request->is('post') && $this->request->is('ajax')) && !empty($this->request->data['write-comment'])) {

	    	if (!$this->TaskManagementTask->exists($taskId)) {

	    		throw new NotFoundRecordException($this->modelClass, "TaskManagementTask");
	    	}

	    	if(!empty($parentTaskId)){

	    		if (!$this->TaskManagementTask->exists($parentTaskId)) {

	    			throw new NotFoundRecordException($this->modelClass, "TaskManagementTask");
	    		}

	    	}

	    	if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
	    		if(!$this->TaskManagementTask->hasAny(array('id' => $taskId, 'OR' => array('created_by' => $this->superUserId, 'assignee' => $this->superUserId)))){

	    			throw new ForbiddenActionException($this->modelClass, "add");

	    		}
	    	}

			$comment = array('TaskManagementTaskComment' => array(
				'task_management_task_id' 	=> $taskId,
				'created_by' 				=> $this->superUserId,
				'comment' 					=> $this->request->data['write-comment'],
				'type' 						=> Configure::read('TaskManagement.comment.type.manual'),
				'created_at' 				=> date('Y-m-d H:i:s')
			));

			/* The child comment only holds the modification details, like when the cmment was edited/deleted.  */
			if(!empty($parentTaskId)){
				$comment['TaskManagementTaskComment']['created_by'] = Configure::read('System.default.user.id');
				$comment['TaskManagementTaskComment']['type'] 		= Configure::read('TaskManagement.comment.type.autogen');
			}

	    	if($commentId = $this->TaskManagementTaskComment->saveTaskManagementTaskComment($comment)){

	    		App::uses('View', 'View');
	    		$View = new View($this);
	    		App::uses('TaskManagementUtilHelper', 'Plugin/TaskManagement/View/Helper');
	    		$TaskManagementUtilHelper = new TaskManagementUtilHelper($View);

	    		$beforeOpenFunc = 'function(){';
	    		$beforeOpenFunc .='var iframe = $("#gritter-notice-wrapper").closest("body").find("iframe").first();';
	    		$beforeOpenFunc .= 'iframe.contents().find("textarea#write-comment").val("");iframe.contents().find("textarea#write-comment").next(".Editor-container").children(".Editor-editor").html("");';
	    		$newCommentHtml = $TaskManagementUtilHelper->buildCommentList($taskId, $commentId);
	    		$newCommentHtml = str_replace("\n", "", $newCommentHtml);
	    		$newCommentHtml = str_replace("\t", "", $newCommentHtml);
	    		$beforeOpenFunc .= 'var newComment = "' .addcslashes($newCommentHtml, '"\\/') .'";';
	    		$beforeOpenFunc .= 'iframe.contents().find(".row.comments").append(newComment);';
	    		$beforeOpenFunc .= 'iframe.contents().find(".row.comments").removeClass("invisible");';
	    		$beforeOpenFunc .= 'iframe.contents().find(".row.comments").prev("hr").removeClass("invisible");';
	    		$beforeOpenFunc .= '$("#gritter-notice-wrapper").stop().animate({scrollTop: 100}, 1000, "swing");';
	    		$beforeOpenFunc .= '}';
	    		$beforeOpenFunc = addcslashes($beforeOpenFunc, '"\\/');

	    		$afterCloseFunc = 'function(){';
	    		$afterCloseFunc .='var iframe = $("#gritter-notice-wrapper").closest("body").find("iframe").first();';
	    		$afterCloseFunc .='iframe.get(0).contentWindow.location.reload();';
	    		$afterCloseFunc .= '}';
	    		$afterCloseFunc = addcslashes($afterCloseFunc, '"\\/');

	    		$response = json_encode(array('status' => Configure::read('System.variable.success'), 'message' => __('Comment added'), 'time' => '3000', 'sticky' => false, 'before_open' => "%function%", 'after_close' => "#function#"));
	    		$response = str_replace("\"%function%\"", '"' .$beforeOpenFunc .'"', $response);
	    		$response = str_replace("\"#function#\"", '"' .$afterCloseFunc .'"', $response);

	    		echo $response;

	    	}else{
	    		echo json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __('Comment cannot be saved, please try again later.'), 'sticky' => false));
	    	}
    	}

    	exit();
    }

/**
 * edit method
 *
 * @return void
 */
    public function admin_edit($id){

    	if (!$this->TaskManagementTaskComment->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
    		if(!$this->TaskManagementTaskComment->hasAny(array('id' => $id, 'created_by' => $this->superUserId))){

    			throw new ForbiddenActionException($this->modelClass, "edit");

    		}
    	}

    	$existingComment = $this->TaskManagementTaskComment->browseBy('id', $id);

    	if (($this->request->is('post') || $this->request->is('put')) && !empty($this->request->data['write-comment'])) {

    		$existingComment['TaskManagementTaskComment']['comment'] = $this->request->data['write-comment'];
    		if($this->TaskManagementTaskComment->updateTaskManagementTaskComment($id, $existingComment)){

    			$logComment = $existingComment;
    			unset($logComment['TaskManagementTaskComment']['id']);
    			$logComment['TaskManagementTaskComment']['parent_id'] 	= $id;
    			$logComment['TaskManagementTaskComment']['comment'] 	= __('edited comment.');
    			$logComment['TaskManagementTaskComment']['type'] 		= Configure::read('TaskManagement.comment.type.autogen');
    			$logComment['TaskManagementTaskComment']['created_at'] 	= date('Y-m-d H:i:s');
    			$logComment['TaskManagementTaskComment']['created_by'] 	= $this->superUserId;
    			$logComment['TaskManagementTaskComment']['deleted'] 	= 0;
    			$this->TaskManagementTaskComment->saveTaskManagementTaskComment($logComment);

    			$this->_showSuccessFlashMessage($this->TaskManagementTaskComment);

    		}else{
    			$this->_showErrorFlashMessage($this->TaskManagementTaskComment);
    		}

    	}

    	$this->set('comment', $existingComment);

    }

/**
 * delete method
 *
 * @return void
 */
    public function admin_delete($id){

    	if (!$this->TaskManagementTaskComment->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){
    		if(!$this->TaskManagementTaskComment->hasAny(array('id' => $id, 'created_by' => $this->superUserId))){

    			throw new ForbiddenActionException($this->modelClass, "delete");

    		}
    	}

    	if($this->request->is('post') || $this->request->is('delete')){

    		if ($this->TaskManagementTaskComment->deleteTaskManagementTaskComment($id)) {

    			$existingComment = $this->TaskManagementTaskComment->browseBy('id', $id);

    			$logComment = $existingComment;
    			unset($logComment['TaskManagementTaskComment']['id']);
    			$logComment['TaskManagementTaskComment']['parent_id'] 	= $id;
    			$logComment['TaskManagementTaskComment']['comment'] 	= __('deleted comment.');
    			$logComment['TaskManagementTaskComment']['type'] 		= Configure::read('TaskManagement.comment.type.autogen');
    			$logComment['TaskManagementTaskComment']['created_at'] 	= date('Y-m-d H:i:s');
    			$logComment['TaskManagementTaskComment']['created_by'] 	= $this->superUserId;
    			$logComment['TaskManagementTaskComment']['deleted'] 	= 0;
    			$this->TaskManagementTaskComment->saveTaskManagementTaskComment($logComment);

    			$this->_showSuccessFlashMessage($this->TaskManagementTaskComment);
    		}else{
    			$this->_showErrorFlashMessage($this->TaskManagementTaskComment);
    		}

    	}
    }

}
