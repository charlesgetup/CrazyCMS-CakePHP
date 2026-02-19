<?php 
    $taskOrTicket = $this->TaskManagementUtil->taskOrTicket($webDevelopmentStageId);
    $isAdmin      = $this->Permissions->isAdmin();
    $isManager    = $this->Permissions->isManager();
    $webdevType   = Configure::read('TaskManagement.type.webdev');
    $ticketType   = Configure::read('TaskManagement.type.ticket');

    $taskActions = '       <div class="pull-right action-buttons">';
    $taskActions .= '          <div class="progress pos-rel" data-percent="#progress#%"><div class="progress-bar" style="width:#progress#%;"></div></div>';
    $taskActions .= '          <a class="blue view-task" href="javascript:void(0);">';
    $taskActions .= '              <i class="ace-icon fa fa-search-plus bigger-130"></i>';
    $taskActions .= '          </a>';
    
    if($isAdmin || $isManager){
        if($taskType == $webdevType){
            $taskActions .= '      <a class="green add-sub-task" href="javascript:void(0);">';
            $taskActions .= '          <i class="ace-icon fa fa-plus bigger-130"></i>';
            $taskActions .= '      </a>';
        }
        if($taskType == $webdevType){
            $taskActions .= '      <a class="blue edit-task" href="javascript:void(0);">';
            $taskActions .= '          <i class="ace-icon fa fa-pencil bigger-130"></i>';
            $taskActions .= '      </a>';
        }
        if($isAdmin && $taskType == $ticketType && empty($returnMyTickets)){
            $taskActions .= '      <a class="red delete-task" href="javascript:void(0);">';
            $taskActions .= '          <i class="ace-icon fa fa-trash-o bigger-130"></i>';
            $taskActions .= '      </a>';
        }elseif($taskType == $webdevType){
            $taskActions .= '      <a class="red delete-task" href="javascript:void(0);">';
            $taskActions .= '          <i class="ace-icon fa fa-trash-o bigger-130"></i>';
            $taskActions .= '      </a>';
        }
    }
    
    $taskActions .= '      </div>';
?>
<div class="row <?php echo strtolower($taskOrTicket); ?>">
    <?php if($taskType == $webdevType): ?>
        <menu id="nestable-menu">
            <button class="btn btn-white btn-inverse" type="button" data-action="expand-all"><?php echo __('Expand All'); ?></button>
            <button class="btn btn-white btn-inverse" type="button" data-action="collapse-all"><?php echo __('Collapse All'); ?></button>
        </menu>
    <?php endif; ?>
    <?php if(isset($returnMyTickets) && $returnMyTickets): ?>
        <div class="col-xs-2 add-task" data-id="<?php echo $stage['id']; ?>">
            <button type="button" class="btn btn-success loading-btn" data-loading-text="<?php echo __('Adding...'); ?>"><?php echo __('Add Ticket'); ?></button>
        </div>
    <?php endif; ?>
    <div class="dd nestable" data-id="<?php echo $webDevelopmentStageId; ?>">
        <?php if(!empty($tasks) && is_array($tasks)): ?>
            <?php echo $this->TaskManagementUtil->buildTaskList($tasks, $taskType, $taskActions); ?>
        <?php endif; ?>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $popupClickAddTitleTxt  = __('Add Sub-task');
    $popupClickEditTitleTxt = __('Edit ' .$taskOrTicket);
    $popupClickViewTitleTxt = __('View ' .$taskOrTicket);
    $popupClickDelTitleTxt  = __('Delete ' .$taskOrTicket);
    $submitBtnTxt           = __('Submit');
    $submitCommentBtnTxt    = __('Comment');
    $cancelBtnTxt           = __('Cancel');
    $confirmBtnTxt          = __('Confirmed');
    $closeBtnTxt            = __('Close');
    $noJsonErrorMessage     = __('JSON brower support is required.');
    $reorderSuccessMessage  = __('Task has been re-ordered.');
    $reorderErrorMessage    = __('Task cannot be re-ordered. Please check logs for more details.');
    $canReOrder             = ($isAdmin || $isManager) ? 'true' : 'false';

    $addOrEditTaskFeature   = '';
    $deleteTaskFeature      = '';
    if($isAdmin || $isManager){
        $addOrEditTaskFeature   = <<<ADDOREDIT
            else if($(event.target).hasClass('add-sub-task') || $(event.target).closest('a').hasClass('add-sub-task') || $(event.target).hasClass('edit-task') || $(event.target).closest('a').hasClass('edit-task')){
                bootbox.dialog({
                    message: dialogMessage,
                    title: titleTxt,
                    buttons: {
                        'Submit' : {
                            'label' : '{$submitBtnTxt}',
                            'className' : 'btn-sm btn-success submit-iframe-form-btn',
                            'callback' : function(event){
                                submitIframeForm(event);
                                return false;
                            }
                        },
                        'Cancel' : {
                            'label' : '{$cancelBtnTxt}',
                            'className' : 'btn-sm btn-sm'
                        }
                    }
                });
            }
ADDOREDIT;

        $deleteTaskFeature = <<<DELETE
            else if($(event.target).hasClass('delete-task') || $(event.target).closest('a').hasClass('delete-task')){
                bootbox.dialog({
                    message: '<iframe src="/admin/task_management/task_management_tasks/delete/' + (webDevelopmentStageId ? webDevelopmentStageId : 0) + '/' + taskId + '?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$popupClickDelTitleTxt}',
                    buttons: {
                        'Confirmed' : {
                            'label' : '{$confirmBtnTxt}',
                            'className' : 'btn-sm btn-success submit-iframe-form-btn',
                            'callback' : function(event){
                                submitIframeForm(event);
                                return false;
                            }
                        },
                        'Cancel' : {
                            'label' : '{$cancelBtnTxt}',
                            'className' : 'btn-sm btn-sm'
                        }
                    }
                });
            }
DELETE;
        if(!$isAdmin && $taskType == $ticketType){
            $deleteTaskFeature = ""; // Only admin user can delete client TICKET
        }
    }

    $maxNestedDepth = ($taskType == $webdevType) ? 3 : 0;

    $inlineJS = <<<EOF
        function reloadParentPage(){
            var url = window.location.href;
            if({$maxNestedDepth} > 0){
                if(url.indexOf("?") != -1){
                    url += "&accordion_id={$webDevelopmentStageId}";
                }else{
                    url += "?accordion_id={$webDevelopmentStageId}";
                }
            }
            window.location.href = url;
            return;
        }
        $('.dd').nestable({
            maxDepth: {$maxNestedDepth},
            onStart: function(list, event){
                var webDevelopmentStageId   = list.el.get(0).dataset.id;
                var taskId                  = $(event.target).closest("li.dd-item").get(0).dataset.id;
                var isAdd                   = $(event.target).hasClass('add-sub-task') || $(event.target).closest('a').hasClass('add-sub-task');
                var dialogMessage           = isAdd ? '<iframe src="/admin/task_management/task_management_tasks/add/' + (webDevelopmentStageId ? webDevelopmentStageId : 0) + '/' + taskId + '?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>' : '<iframe src="/admin/task_management/task_management_tasks/edit/' + taskId + '/' + (webDevelopmentStageId ? webDevelopmentStageId : 0) + '?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>';
                var titleTxt                = isAdd ? '{$popupClickAddTitleTxt}' : '{$popupClickEditTitleTxt}';
                if($(event.target).hasClass('view-task') || $(event.target).closest('a').hasClass('view-task')){
                    bootbox.dialog({
                        message: '<iframe src="/admin/task_management/task_management_tasks/view/' + (webDevelopmentStageId ? webDevelopmentStageId : 0) + '/' + taskId + '?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                        title: '{$popupClickViewTitleTxt}',
                        buttons: {
                            'Submit' : {
                                'label' : '{$submitCommentBtnTxt}',
                                'className' : 'btn-sm btn-success submit-iframe-form-btn',
                                'otherBtnAttrs': 'data-submitFormId="TaskManagementTaskCommentAddForm" data-reload=false',
                                'callback' : function(event){
                                    submitIframeForm(event, true);
                                    return false;
                                }
                            },
                            'Cancel' : {
                                'label' : '{$closeBtnTxt}',
                                'className' : 'btn-sm btn-sm',
                                'callback': function(){
                                    reloadParentPage();
                                }
                            }
                        },
                        onEscape: function(){
                            reloadParentPage();
                        }
                    });
                }
                {$addOrEditTaskFeature}
                {$deleteTaskFeature}
            }
        });
EOF;

    // Only show nestable feature when task is under web development project stage
    if($taskType == $webdevType){
        
        $webdevTaskFeature = <<<WEBDEV
        
            $('#nestable-menu').on('click', function(e){
                var target = $(e.target), 
                    action = target.data('action');
                if(action == "expand-all"){
                    $('.dd').nestable('expandAll');
                }
                if(action == "collapse-all"){
                    $('.dd').nestable('collapseAll');
                }
            });
            
            if(!{$canReOrder}){
                
                $('.dd').on('mousedown', function(e){
                    e.preventDefault();
                    return false;
                });
                
                $('.dd').on('mousemove', function(e){
                    e.preventDefault();
                    return false;
                });
                
                $('.dd').on('mouseup', function(e){
                    e.preventDefault();
                    return false;
                });
                
            }else{
                
                $('.dd').on('change', function(e){
                
                    if(window.JSON){
                        
                        var target = $(e.target);
    
                        $.ajax({
                            url: "/admin/task_management/task_management_tasks/reorder/"+target.get(0).dataset.id+".json",
                            type: "POST",
                            contentType: "application/json",
                            dataType: "json",
                            headers: {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                            data: window.JSON.stringify(target.nestable('serialize')),
                            beforeSend: function ( xhr ) {
                                $.gritter.removeAll();
                                if($.fn.ace_ajax){
                                    $('[data-ajax-content=true]').ace_ajax('startLoading');
                                }
                            }
                        }).done(function ( data ) {
                            if(data){
                                /*messageBox({"status": SUCCESS, "message": "{$reorderSuccessMessage}"});*/
                            }else{
                                messageBox({"status": ERROR, "message": "{$reorderErrorMessage}"});
                            }
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
WEBDEV;
        $inlineJS .= $webdevTaskFeature;
        
    }

    if(isset($returnMyTickets) && $returnMyTickets){
        $popupClickAddTicketTitleTxt  = __('Add Ticket');
        $addTicket = <<<TICKET
            $('.loading-btn').on(ace.click_event, function () {
                var btn = $(this);
                btn.button('loading');
                var dialogMessage = '<iframe src="/admin/task_management/task_management_tasks/add/?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>'; 
                var titleTxt      = '{$popupClickAddTicketTitleTxt}';
                bootbox.dialog({
                    message: dialogMessage,
                    title: titleTxt,
                    buttons: {
                        'Submit' : {
                            'label' : '{$submitBtnTxt}',
                            'className' : 'btn-sm btn-success submit-iframe-form-btn',
                            'callback' : function(event){
                                submitIframeForm(event);
                                return false;
                            }
                        },
                        'Cancel' : {
                            'label' : '{$cancelBtnTxt}',
                            'className' : 'btn-sm btn-sm'
                        }
                    }
                });
                setTimeout(function () {
                    btn.button('reset');
                }, 2000)
            });
TICKET;
        $inlineJS .= $addTicket;
    }

    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
    
    
?>