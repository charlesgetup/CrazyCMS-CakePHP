<?php
class TaskManagementUtilHelper extends AppHelper{

	var $helpers = array('Time', 'ExtendedHtml', 'Session', 'Permissions', 'Util', 'Minify');

	public function taskOrTicket($webDevelopmentStageId = null, $taskType = null){

		if(empty($webDevelopmentStageId) && empty($taskType)){
			return __('Ticket');
		}else{
			if(!empty($webDevelopmentStageId)){
				return __('Task');
			}else{
				return ($taskType == Configure::read('TaskManagement.type.ticket')) ? __('Ticket') : __('Task');
			}
		}
	}

	public function buildTaskList($taskTree, $taskType, $taskActions){

		$html = '<ol class="dd-list">';

		if(!empty($taskType)){

			if(!empty($taskTree) && is_array($taskTree)){

				foreach($taskTree as $task){

					$html .= '<li class="dd-item" data-id="' .$task['TaskManagementTask']['id'] .'">';
					$html .= '	<div class="dd-handle">';
					$html .= '		<span class="task-name">' .$task['TaskManagementTask']['name'] .'</span>';

					if(!empty($taskActions)){
						$html .= '	&nbsp;&nbsp;&nbsp;&nbsp;<span class="label">' .$task['TaskManagementTask']['status'] .'</span>';

						if(!$this->Permissions->isClient() && ($task['TaskManagementTask']['created_by'] != $this->Session->read('Auth.User.id') || $taskType == Configure::read('TaskManagement.type.webdev'))){
							$html .= '  &nbsp;&nbsp;&nbsp;&nbsp;<span class="assignee">' .$task['Assignee']['name'] .'</span>';
							$html .= '  &nbsp;&nbsp;&nbsp;&nbsp;<span class="due-time">' .$this->Time->i18nFormat($task['TaskManagementTask']['end_time'], '%e %h (%R)') .'</span>';
							$html .= '  &nbsp;&nbsp;&nbsp;&nbsp;<span class="priority p-' .$task['TaskManagementTask']['priority'] .'">' .__('Pri:') .' ' .$task['TaskManagementTask']['priority'] .'</span>';
						}

						$html .= str_replace("#progress#", $task['TaskManagementTask']['progress'], $taskActions);
					}

					$html .= '	</div>';

					if(!empty($task['children'])){
						$html .= $this->buildTaskList($task['children'], $taskType, $taskActions);
					}

					$html .= '</li>';

				}

			}else{

				$html .= '<li class="dd-item">';
				$html .= '	' .__('No ' .$this->taskOrTicket(null, $taskType) .' found');
				$html .= '</li>';

			}

		}

		$html .= '</ol>';

		return $html;
	}

	public function buildCommentList($taskId, $commentId = null){

		App::import('Model', 'TaskManagement.TaskManagementTaskComment');
		$TaskManagementTaskComment = new TaskManagementTaskComment();
		$comments = $TaskManagementTaskComment->getCommentsByTaskId($taskId, $commentId);

		if(!empty($comments) && is_array($comments)){

			$html = '';

			$index = 0;
			$displayedIndex = 0; // Only display last 5 comments
			$displayCommentAmount = 5;
			if(count($comments) > $displayCommentAmount){
				$displayedIndex = count($comments) - $displayCommentAmount;
			}

			foreach($comments as $comment){

				$isEdited 	= false;
				$isDeleted 	= false;
				if(empty($comment['TaskManagementTaskComment']['deleted'])){

					$isEdited = !empty($comment['children']);

				}else{

					$isDeleted = true;
				}

				if($index == $displayedIndex && $displayedIndex > 0){
					$html .= '<div>';
					$html .= '	<a class="show-comments" href="javascript:void(0);">' .(($displayedIndex > 1) ? __('show %s more comments', strval($index + 1)) : __('show 1 more comment')) .'</a>';
					$html .= '</div>';
				}

				$html .= '<div class="comment" style="display: ' .(($index >= $displayedIndex) ? 'block' : 'none') .';">';
				$html .= '	<div class="comment-block">'; //TODO Use this block to wrap all the content, then later we can use another div to display user profile image at the left side
				$html .= '		<div class="header">';
				$html .= '			' .$comment['Creator']['first_name'] .' ' .$comment['Creator']['last_name'] .'<span class="added-date">' .$this->Util->formatCreatedDate($comment['TaskManagementTaskComment']['created_at']) .($isEdited ? ' (' .__('edited') .')' : '') .'</span>';
				$html .= '		</div>';

				if(!$isDeleted){

					$html .= '	<div class="content ' .strtolower($comment['TaskManagementTaskComment']['type']) .'">';
					$html .= '		' .$comment['TaskManagementTaskComment']['comment'];
					$html .= '	</div>';

				}

				// Because there is only one level deep, no need to use recursive method to load all children comments
				if(!empty($comment['children'])){

					foreach($comment['children'] as $child){

						if(empty($child['TaskManagementTaskComment']['deleted'])){
							$html .= '<div class="content ' .strtolower($child['TaskManagementTaskComment']['type']) .'">';
							$html .= '	' .$child['Creator']['first_name'] .' ' .$child['Creator']['last_name'] .' ' .$child['TaskManagementTaskComment']['comment'] .'&nbsp;&nbsp;&nbsp;&nbsp;' .$this->Util->formatCreatedDate($child['TaskManagementTaskComment']['created_at']);
							$html .= '</div>';
						}

					}

				}

				$html .= '	</div>';
				$html .= '	<div class="comment-actions">';
				$html .= '		<button data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><i class="ace-icon fa fa-angle-down"></i></button>';
				$html .= '		<ul class="dropdown-menu">';
				$html .= '			<li>';
				$html .= '				<span class="clickable">';
				$html .= '					<i class="ace-icon fa fa-edit"></i>';
				$html .= '					&nbsp;&nbsp;';
				$html .= '					<span class="edit-comment" data-id="' .$comment['TaskManagementTaskComment']['id'] .'">' .__("Edit") .'</span>';
				$html .= '				</span>';
				$html .= '			</li>';
				$html .= '			<li>';
				$html .= '				<span class="clickable">';
				$html .= '					<i class="ace-icon fa fa-trash"></i>';
				$html .= '					&nbsp;&nbsp;';
				$html .= '					<span class="delete-comment" data-id="' .$comment['TaskManagementTaskComment']['id'] .'">' .__("Delete") .'</span>';
				$html .= '				</span>';
				$html .= '			</li>';
				$html .= '		</ul>';
				$html .= '	</div>';
				$html .= '</div>';

				$index++;

			}

			$html .= '<script type="text/javascript">';
			$inlineJavaScript = '	$(\'span.edit-comment\').off(ace.click_event);';
			$inlineJavaScript .= '	$(\'span.edit-comment\').on(ace.click_event, function () {';
			$inlineJavaScript .= '		bootbox.dialog({';
			$inlineJavaScript .= '			message: \'<iframe src="/admin/task_management/task_management_task_comments/edit/\' + this.dataset.id + \'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
			$inlineJavaScript .= '			title: "' .__('Edit Comment') .'",';
			$inlineJavaScript .= '			buttons: {';
			$inlineJavaScript .= '				\'Submit\' : {';
			$inlineJavaScript .= '					\'label\' : "' .__('Submit') .'",';
			$inlineJavaScript .= '					\'className\' : \'btn-sm btn-success submit-iframe-form-btn\',';
			$inlineJavaScript .= '					\'callback\' : function(event){';
			$inlineJavaScript .= '						submitIframeForm(event);';
			$inlineJavaScript .= '						return false;';
			$inlineJavaScript .= '					}';
			$inlineJavaScript .= '				},';
			$inlineJavaScript .= '				\'Cancel\' : {';
			$inlineJavaScript .= '					\'label\' : "' .__('Cancel') .'",';
			$inlineJavaScript .= '					\'className\' : \'btn-sm btn-sm\'';
			$inlineJavaScript .= '				}';
			$inlineJavaScript .= '			}';
			$inlineJavaScript .= '		});';
			$inlineJavaScript .= '	});';
			$inlineJavaScript .= '	$(\'span.delete-comment\').off(ace.click_event);';
			$inlineJavaScript .= '	$(\'span.delete-comment\').on(ace.click_event, function () {';
			$inlineJavaScript .= '		bootbox.dialog({';
			$inlineJavaScript .= '			message: \'<iframe src="/admin/task_management/task_management_task_comments/delete/\' + this.dataset.id + \'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
			$inlineJavaScript .= '			title: "' .__('Delete Comment') .'",';
			$inlineJavaScript .= '			buttons: {';
			$inlineJavaScript .= '				\'Confirmed\' : {';
			$inlineJavaScript .= '					\'label\' : "' .__('Confirmed') .'",';
			$inlineJavaScript .= '					\'className\' : \'btn-sm btn-success submit-iframe-form-btn\',';
			$inlineJavaScript .= '					\'callback\' : function(event){';
			$inlineJavaScript .= '						submitIframeForm(event);';
			$inlineJavaScript .= '						return false;';
			$inlineJavaScript .= '					}';
			$inlineJavaScript .= '				},';
			$inlineJavaScript .= '				\'Cancel\' : {';
			$inlineJavaScript .= '					\'label\' : "' .__('Cancel') .'",';
			$inlineJavaScript .= '					\'className\' : \'btn-sm btn-sm\'';
			$inlineJavaScript .= '				}';
			$inlineJavaScript .= '			}';
			$inlineJavaScript .= '		});';
			$inlineJavaScript .= '	});';
			$html .= $this->Minify->minifyInlineJS($inlineJavaScript);
			$html .= '</script>';
			return $html;

		}else{
			return "";
		}

	}

	public function buildUploadedFilesList($uploadedFiles){

		$html = '';
		if(!empty($uploadedFiles) && is_array($uploadedFiles)){

			foreach($uploadedFiles as $file){

				$html .= '<div class="uploaded-file">';
				$html .= '	<div class="uploaded-file-block">'; //TODO Use this block to wrap all the content, then later we can use another div to display user profile image at the left side
				$html .= '		<a href="' .$file['uploaded_file_download_url'] .'" target="_blank">' .basename($file['uploaded_file_download_url']) .'</a>';
				$html .= '	</div>';
				$html .= '	<div class="uploaded-file-actions">';
				$html .= '		<a href="javascript:void(0)" data-id="' .$file['id'] .'"><i class="ace-icon fa fa-trash"></i></a>';
				$html .= '	</div>';
				$html .= '</div>';

			}

			$html .= '<script>';
			$inlineJavaScript = '	$(\'.uploaded-file\').hover(';
			$inlineJavaScript .= '		function(){';
			$inlineJavaScript .= '			$(this).children(".uploaded-file-actions").css("display", "inline-block")';
			$inlineJavaScript .= '		},';
			$inlineJavaScript .= '		function(){';
			$inlineJavaScript .= '			$(this).children(".uploaded-file-actions").css("display", "none")';
			$inlineJavaScript .= '		}';
			$inlineJavaScript .= '	);';
			$inlineJavaScript .= '	$(\'.uploaded-file-actions a\').on(ace.click_event, function () {';
			$inlineJavaScript .= '		$(this).closest("body").stop().animate({scrollTop: 100}, 1000, "swing");';
			$inlineJavaScript .= '		bootbox.dialog({';
			$inlineJavaScript .= '			message: \'<iframe src="/admin/task_management/task_management_task_uploads/delete/\' + this.dataset.id + \'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>\',';
			$inlineJavaScript .= '			title: "' .__('Delete Uploaded File') .'",';
			$inlineJavaScript .= '			buttons: {';
			$inlineJavaScript .= '				\'Confirmed\' : {';
			$inlineJavaScript .= '					\'label\' : "' .__('Confirmed') .'",';
			$inlineJavaScript .= '					\'className\' : \'btn-sm btn-success submit-iframe-form-btn\',';
			$inlineJavaScript .= '					\'callback\' : function(event){';
			$inlineJavaScript .= '						submitIframeForm(event);';
			$inlineJavaScript .= '						return false;';
			$inlineJavaScript .= '					}';
			$inlineJavaScript .= '				},';
			$inlineJavaScript .= '				\'Cancel\' : {';
			$inlineJavaScript .= '					\'label\' : "' .__('Cancel') .'",';
			$inlineJavaScript .= '					\'className\' : \'btn-sm btn-sm\'';
			$inlineJavaScript .= '				}';
			$inlineJavaScript .= '			}';
			$inlineJavaScript .= '		});';
			$inlineJavaScript .= '	});';
			$html .= '$(function(){' .$this->Minify->minifyInlineJS($inlineJavaScript) .'});';
			$html .= '</script>';

		}
		return $html;
	}

	public function generatePriorityList(){
		$priorities = array();
		for($i = 1; $i < 10; $i++){
			$priorities[$i] = $i;
			if($i == 9){
				$priorities[$i] .= ' ' .__('(Low)');
			}elseif($i == 5){
				$priorities[$i] .= ' ' .__('(Normal)');
			}elseif($i == 1){
				$priorities[$i] .= ' ' .__('(Urgent)');
			}
		}
		return $priorities;
	}

}
?>