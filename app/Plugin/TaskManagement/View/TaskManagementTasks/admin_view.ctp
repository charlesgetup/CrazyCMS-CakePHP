<?php
    $invisibleStatus = Configure::read('TaskManagement.invisible-status');
    $taskOrTicket    = $this->TaskManagementUtil->taskOrTicket($webDevelopmentStageId);
    $isClient        = $this->Permissions->isClient();
    $isStaff         = $this->Permissions->isStaff();
?>
<div class="row task-info">
    <h3><?php echo h($task['TaskManagementTask']['name']); ?></h3>
    <div class="width-24 oneline">
        <span><?php echo __('Created By'); ?></span><br />
        <span><?php echo h($task['Creator']['first_name']) .' ' .h($task['Creator']['last_name']); ?></span>
    </div>
    <div class="width-24 oneline">
        <span><?php echo __('Assigned To'); ?></span><br />
        <?php if($isClient || $isStaff || empty($possibleAssigneeList) || in_array($task['TaskManagementTask']['status'], $invisibleStatus)): ?>
            <span><?php echo empty($task['Assignee']['name']) ? __('Not assigned') : h($task['Assignee']['name']); ?></span>
        <?php else: ?>
            <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-sm btn-primary btn-white dropdown-toggle">
                    <span class="task-assignee"><?php echo empty($task['Assignee']['name']) ? __('Not assigned') : h($task['Assignee']['name']); ?></span>
                    <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                </button>
                <ul class="dropdown-menu assignee-select">
                    <?php foreach($possibleAssigneeList as $id => $a): ?>
                        <li>
                            <a href="javascript:void(0);" data-assignee="<?php echo $id; ?>"><?php echo h($a); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <?php if(!$isClient && !$ownTask): ?>
        <div class="width-24 oneline">
            <span><?php echo __('Task Status'); ?></span><br />
            <?php if($isClient || in_array($task['TaskManagementTask']['status'], $invisibleStatus)): ?>
                <span><?php echo __(Inflector::humanize($task['TaskManagementTask']['status'])); ?></span>
            <?php else: ?>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-sm btn-primary btn-white dropdown-toggle">
                        <span class="task-status"><?php echo __(Inflector::humanize($task['TaskManagementTask']['status'])); ?></span>
                        <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                    </button>
                    <ul class="dropdown-menu status-select">
                        <?php foreach(Configure::read('TaskManagement.status') as $s): ?>
                            <li>
                                <a href="javascript:void(0);" data-status="<?php echo $s; ?>"><?php echo __(Inflector::humanize($s)); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
        <div class="width-24 oneline">
            <span><?php echo __('Due Time'); ?></span><br />
            <span><?php echo $this->Time->i18nFormat($task['TaskManagementTask']['end_time'], '%x %X'); ?></span>
        </div>
    <?php endif; ?>
</div>

<div class="row task-info">
    <div class="width-24 oneline">
        <span><?php echo __('Department'); ?></span><br />
        <?php if(!$isClient && !$ownTask && !$isStaff): ?>
            <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-sm btn-primary btn-white dropdown-toggle">
                    <span class="task-department"><?php echo h($departments[$task['TaskManagementTask']['group_id']]); ?></span>
                    <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                </button>
                <ul class="dropdown-menu department-select">
                    <?php foreach($departments as $id => $d): ?>
                        <li>
                            <a href="javascript:void(0);" data-department="<?php echo $id; ?>"><?php echo h($d); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php else: ?>
            <span><?php echo h($departments[$task['TaskManagementTask']['group_id']]); ?></span>
        <?php endif; ?>
    </div>
    <?php if(!$isClient && !$ownTask): ?>
        <div class="width-24 oneline">
            <span><?php echo __('Priority'); ?></span><br />
            <?php if(!$isStaff): ?>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-sm btn-primary btn-white dropdown-toggle">
                        <span class="task-priority"><?php echo h($task['TaskManagementTask']['priority']); ?></span>
                        <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                    </button>
                    <ul class="dropdown-menu priority-select">
                        <?php 
                            $priorities = $this->TaskManagementUtil->generatePriorityList();
                        ?>
                        <?php foreach($priorities as $id => $p): ?>
                            <li>
                                <a href="javascript:void(0);" data-priority="<?php echo $id; ?>"><?php echo h($p); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: ?>
                <span><?php echo h($task['TaskManagementTask']['priority']); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<div class="row task-info">
    <div class="space-8">&nbsp;</div>
    <div class="width-100">
        <div class="knob-container inline">
            <input type="text" class="input-small knob" value="<?php echo $task['TaskManagementTask']['progress']; ?>" data-min="0" data-max="100" data-width="120" data-height="80" data-thickness=".2" data-fgcolor="#87B87F" data-displayprevious="true" data-anglearc="250" data-angleoffset="-125" <?php echo ($isClient || $ownTask || in_array($task['TaskManagementTask']['status'], $invisibleStatus)) ? 'data-readOnly=true' : ""; ?> />
        </div>
        <blockquote>
            <p><?php echo h($task['TaskManagementTask']['description']); ?></p>
        </blockquote>
    </div>
</div>

<hr class="hr hr-10 <?php echo empty($task['TaskManagementTaskComment']) ? 'invisible' : ''; ?>" />
<div class="row comments <?php echo empty($task['TaskManagementTaskComment']) ? 'invisible' : ''; ?>">
    <?php
        if(!empty($task['TaskManagementTaskComment'])){ 
            echo $this->TaskManagementUtil->buildCommentList($task['TaskManagementTask']['id']);
        } 
    ?>
</div>

<?php if(!empty($task['TaskManagementTaskUpload']) && !$isClient): ?>
    <hr class="hr hr-10" />
    <div class="row uploads">
        <?php echo $this->TaskManagementUtil->buildUploadedFilesList($task['TaskManagementTaskUpload']); ?>
    </div>
<?php endif; ?>

<?php if(!in_array($task['TaskManagementTask']['status'], $invisibleStatus)): ?>
    <hr class="hr hr-10" />
    <div class="comment">
        <h4><?php echo __('Write your comment'); ?></h4>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->create(false, array('url' => array('controller' => 'task_management_task_comments', 'action' => 'add', $task['TaskManagementTask']['id']), 'id' => 'TaskManagementTaskCommentAddForm'));
                    echo $this->element('generals/online_editor', array('textareaName' => 'write-comment', 'content' => '', 'customCssStyle' => '.Editor-editor {height: 100px;}'));
                    echo $this->Form->end();
                ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <script>
        window.parent.$('button.submit-iframe-form-btn').remove();
    </script>    
<?php endif; ?>

<?php
    if(!$isClient){ 
        echo $this->Util->setFullpageDragNDropUpload('TaskManagementTask.TaskManagementTaskUpload', array('controller' => 'task_management_task_uploads', 'action' => 'upload', $task['TaskManagementTask']['id']));
    } 
?>

<!-- page specific plugin scripts -->
<?php

    //TODO implement task progess, knob()
    
    $inlineJS .= <<<EOF
        $(".knob").knob();
        
        function ajaxUpdateTaskAttr( dataAttr, eventTrigger, ajaxUrl, callback ) {
            
            eventTrigger.on('click', function(event){
                if(window.JSON){
                
                    var dataObj = new Object();
                    dataObj[dataAttr] = $(event.target).data(dataAttr);
                
                    $.ajax({
                        url: ajaxUrl,
                        type: "POST",
                        dataType: "html",
                        data: dataObj,
                        headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                        beforeSend: function ( xhr ) {
                            $.gritter.removeAll();
                            if($.fn.ace_ajax){
                                $('[data-ajax-content=true]').ace_ajax('startLoading');
                            }
                        }
                    }).done(function ( data ) {
                        callback(data, event);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        ajaxErrorHandler(jqXHR, textStatus, errorThrown);
                    }).always(function() {
                        if($.fn.ace_ajax){
                            $('[data-ajax-content=true]').ace_ajax('stopLoading', true);
                        }
                    });
                }else{
                    messageBox({"status": ERROR, "message": "{$noJsonErrorMessage}"});
                }
            });
            
        }
EOF;

    if(!$isClient && (!$ownTask || (!$isStaff && !$isClient)) && !in_array($task['TaskManagementTask']['status'], $invisibleStatus)){
        $noJsonErrorMessage             = __('JSON brower support is required.');
        $updateAssigneeSuccessMessage   = __($taskOrTicket .' assignee has been updated.');
        $updateAssigneeErrorMessage     = __($taskOrTicket .' assignee cannot be updated. Please check logs for more details.');
        $updateStatusSuccessMessage     = __($taskOrTicket .' status has been updated.');
        $updateStatusErrorMessage       = __($taskOrTicket .' status cannot be updated. Please check logs for more details.');
        
        $invisibleStatusStr             = implode("','", $invisibleStatus);
        
        $inlineJS .= <<<EOF
            
            var updateAssigneeCallback = function(data, event){
                if(data){
                    var newAssignee = $(event.target).text();
                    $(event.target).closest('.btn-group').children('button').children('span.task-assignee').html(newAssignee);
                    messageBox({"status": SUCCESS, "message": "{$updateAssigneeSuccessMessage}"});
                }else{
                    messageBox({"status": ERROR, "message": "{$updateAssigneeErrorMessage}"});
                }
            };
            ajaxUpdateTaskAttr('assignee', $('.assignee-select li a'), "/admin/task_management/task_management_tasks/updateAssignee/{$task['TaskManagementTask']['id']}", updateAssigneeCallback);
            
            var updateStatusCallback = function(data, event){
                if(data){
                    var newStatus = $(event.target).data('status');
                    $(event.target).closest('.btn-group').children('button').children('span.task-status').html(newStatus);
                    if($.inArray(newStatus, ['{$invisibleStatusStr}']) >= 0){
                        $('div.comment').last().css({"display": "none"});
                        $('div.comment').last().prev("hr").css({"display": "none"});
                        window.parent.$('button.submit-iframe-form-btn').css({"display": "none"});
                    }else{
                        $('div.comment').last().css({"display": "block"});
                        $('div.comment').last().prev("hr").css({"display": "block"});
                        window.parent.$('button.submit-iframe-form-btn').css({"display": "inline-block"});
                    }
                    messageBox({"status": SUCCESS, "message": "{$updateStatusSuccessMessage}"});
                }else{
                    messageBox({"status": ERROR, "message": "{$updateStatusErrorMessage}"});
                }
            };
            ajaxUpdateTaskAttr('status', $('.status-select li a'), "/admin/task_management/task_management_tasks/updateStatus/{$task['TaskManagementTask']['id']}", updateStatusCallback);
        
EOF;
    } 
    
    if(!$isClient && !$ownTask && !$isStaff) {
        
        $updateDeptSuccessMessage = __($taskOrTicket .' department has been updated.');
        $updateDeptErrorMessage   = __($taskOrTicket .' department cannot be updated. Please check logs for more details.');
        $updatePrioSuccessMessage = __($taskOrTicket .' priority has been updated.');
        $updatePrioErrorMessage   = __($taskOrTicket .' priority cannot be updated. Please check logs for more details.');
        
        $inlineJS .= <<<EOF
        
            var updateDepartmentCallback = function(data, event){
                if(data){
                    var newDept = $(event.target).text();
                    $(event.target).closest('.btn-group').children('button').children('span.task-department').html(newDept);
                    messageBox({"status": SUCCESS, "message": "{$updateDeptSuccessMessage}"});
                }else{
                    messageBox({"status": ERROR, "message": "{$updateDeptErrorMessage}"});
                }
            };
            ajaxUpdateTaskAttr('department', $('.department-select li a'), "/admin/task_management/task_management_tasks/updateDepartment/{$task['TaskManagementTask']['id']}", updateDepartmentCallback);
            
            var updatePriorityCallback = function(data, event){
                if(data){
                    var newPrio = $(event.target).text();
                    $(event.target).closest('.btn-group').children('button').children('span.task-priority').html(newPrio);
                    messageBox({"status": SUCCESS, "message": "{$updatePrioSuccessMessage}"});
                }else{
                    messageBox({"status": ERROR, "message": "{$updatePrioErrorMessage}"});
                }
            };
            ajaxUpdateTaskAttr('priority', $('.priority-select li a'), "/admin/task_management/task_management_tasks/updatePriority/{$task['TaskManagementTask']['id']}", updatePriorityCallback);
EOF;
    }
    
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('jquery.knob.js'),
        'inlineJS' => $inlineJS
    ));
?>