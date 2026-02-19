<?php
    $canAddStage    = $this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentStages/admin_add');
    $canEditStage   = $this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentStages/admin_edit');
    $canDeleteStage = $this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentStages/admin_delete');
    $canAddInvoice  = $this->Permissions->check($acl, 'Payment/PaymentInvoices/admin_add') && $this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentStages/admin_addInvoice');
    $isClient       = $this->Permissions->isClient();
    $isStaff        = $this->Permissions->isStaff();
?>
<div class="row project-info">
    <h3><?php echo h($project['WebDevelopmentProject']['name']); ?></h3>
    <div class="width-30 oneline">
        <span><?php echo __('Project Owner'); ?></span><br />
        <span><?php echo h($project['Owner']['first_name']) .' ' .h($project['Owner']['last_name']); ?></span>
    </div>
    <div class="width-30 oneline">
        <span><?php echo __('Project Manager'); ?></span><br />
        <span><?php echo h($project['Creator']['first_name']) .' ' .h($project['Creator']['last_name']); ?></span>
    </div>
    <div class="width-30 oneline">
        <span><?php echo __('Project Status'); ?></span><br />
        <span><?php echo __(Inflector::humanize($project['WebDevelopmentProject']['status'])); ?></span>
    </div>
    <div class="space-8">&nbsp;</div>
    <div class="width-100">
        <blockquote>
            <p><?php echo h($project['WebDevelopmentProject']['description']); ?></p>
        </blockquote>
    </div>
</div>
<?php if((!empty($project['WebDevelopmentStage']) && is_array($project['WebDevelopmentStage'])) || $canAddStage): ?>
    <h4 class="project-stages-title"><?php echo __('Project Stages'); ?></h4>
    <div class="accordion-style1 panel-group accordion-style2 project-stages-list" id="accordion">
        <?php foreach($project['WebDevelopmentStage'] as $stage): ?>
            <?php if(empty($stage)){ continue; } ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <?php $nameSlug = Inflector::slug($stage['name']); ?>
                        <a class="accordion-toggle collapsed" data-id="<?php echo $stage['id']; ?>" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $nameSlug; ?>">
                            <i data-icon-show="ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" class="bigger-110 ace-icon fa fa-angle-right"></i>
                            <?php echo h($stage['name']); ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span class="label"><?php echo __(Inflector::humanize($stage['status'])); ?></span>
                            <div class="stage-status item-actions">
                                <div class="stage-status-bar">
                                    <i class="ace-icon fa fa-calendar"></i>
                                    &nbsp;&nbsp;
                                    <span>
                                        <?php echo __('START TIME') .': ' .$this->Time->i18nFormat($stage['start_time'], '%x %X') .'&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;' .__('END TIME') .': ' .$this->Time->i18nFormat($stage['end_time'], '%x %X'); ?>
                                    </span>
                                    <?php if(!$isClient && !$isStaff): ?>
                                        <?php if(empty($stage['payment_invoice_id'])): ?>
                                            <?php if(!$isClient && $canAddInvoice): ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <i class="ace-icon fa fa-credit-card"></i>
                                                &nbsp;&nbsp;
                                                <span>
                                                    <?php echo '<span class="orange clickable create-invoice" data-id="' .$stage['id'] .'">' .__('Create invoice') .'</span>'; ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <i class="ace-icon fa fa-credit-card"></i>
                                            &nbsp;&nbsp;
                                            <span>
                                                <?php echo $stage['paid'] ? '<span class="green">' .__('PAID') .'</span>' : ($isClient ? '<span class="red clickable pay-invoice" data-link-url="/admin/web_development/web_development_stages/payInvoice/' .$stage['id'] .'" data-reload-params="paidInvoice=' .$stage['id'] .'">' .__('NOT PAID') .'</span>' : '<span class="red">' .__('NOT PAID') .'</span>'); ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <?php if($canEditStage || $canDeleteStage): ?>
                                    <button data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true"><i class="ace-icon fa fa-angle-down"></i></button>
                                    <ul class="dropdown-menu">
                                        <?php if($canEditStage): ?>
                                            <li>
                                                <span class="clickable">
                                                    <i class="ace-icon fa fa-edit"></i>
                                                    &nbsp;&nbsp;
                                                    <span class="edit-stage" data-id="<?php echo $stage['id']; ?>"><?php echo __("Edit"); ?></span>
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($canDeleteStage): ?>
                                            <li>
                                                <span class="clickable">
                                                    <i class="ace-icon fa fa-trash"></i>
                                                    &nbsp;&nbsp;
                                                    <span class="delete-stage" data-id="<?php echo $stage['id']; ?>"><?php echo __("Delete"); ?></span>
                                                </span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </a>
                    </h4>
                </div>
                <div id="<?php echo $nameSlug; ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php if(!empty($stage['description'])): ?>
                            <div class="stage-status-bar-mobile">
                                    <i class="ace-icon fa fa-calendar"></i>
                                    &nbsp;&nbsp;
                                    <span>
                                        <?php echo '<span class="time">' .__('START TIME') .':</span>' .$this->Time->i18nFormat($stage['start_time'], '%x %X') .'<br /><i class="ace-icon fa fa-calendar invisible"></i>&nbsp;&nbsp;&nbsp;&nbsp;<span class="time">' .__('END TIME') .':</span>' .$this->Time->i18nFormat($stage['end_time'], '%x %X'); ?>
                                    </span>
                                    <?php if(!$isClient && !$isStaff): ?>
                                        <?php if(empty($stage['payment_invoice_id'])): ?>
                                            <?php if(!$isClient && $canAddInvoice): ?>
                                                <br />
                                                <i class="ace-icon fa fa-credit-card"></i>
                                                &nbsp;&nbsp;
                                                <span>
                                                    <?php echo '<span class="orange clickable create-invoice" data-id="' .$stage['id'] .'">' .__('Create invoice') .'</span>'; ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <br />
                                            <i class="ace-icon fa fa-credit-card"></i>
                                            &nbsp;&nbsp;
                                            <span>
                                                <?php echo $stage['paid'] ? '<span class="green">' .__('PAID') .'</span>' : ($isClient ? '<span class="red clickable pay-invoice" data-link-url="/admin/web_development/web_development_stages/payInvoice/' .$stage['id'] .'" data-reload-params="paidInvoice=' .$stage['id'] .'">' .__('NOT PAID') .'</span>' : '<span class="red">' .__('NOT PAID') .'</span>'); ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <div class="stage-desc">
                                <?php if(!$isClient && !$isStaff): ?>
                                    <div class="col-xs-2 add-task" data-id="<?php echo $stage['id']; ?>">
                                        <button type="button" class="btn btn-success loading-btn" data-loading-text="<?php echo __('Adding...'); ?>"><?php echo __('Add Task'); ?></button>
                                    </div>
                                <?php endif; ?>
                                <blockquote class="col-xs-10">
                                    <p><?php echo h($stage['description']); ?></p>
                                </blockquote>
                            </div>
                        <?php endif; ?>
                        <?php
                            if(!$isClient){
                                echo $this->element('tasks/task_list', array(
                                    'tasks'                 => $stage['tasks'],
                                    'taskType'              => Configure::read('TaskManagement.type.webdev'),
                                    'webDevelopmentStageId' => $stage['id']
                                ), array(
                                    'plugin'                => 'TaskManagement',
                                ));
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if($canAddStage): ?>
    <div class="row project-info">
        <button type="button" class="btn btn-success loading-btn" data-loading-text="<?php echo __('Adding...'); ?>"><?php echo __('Add Stage'); ?></button>
    </div>
<?php endif; ?>
<?php
    
    $inlineJS = <<<EOF
    
        /* TODO not sure why [Bootstrap] CSS/JS not support the HTML. I write the animation myself. */
        var accordionClick = function (event) {
            var trigger = event.target;
            if(trigger.dataset.toggle != 'dropdown' && !trigger.classList.contains('fa-angle-down')){
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                var targetObj = event.target;
                $('#accordion a.accordion-toggle').each(function(){
                    if(!$(this).hasClass('collapsed') && this != targetObj){
                        $(this).addClass('collapsed', 200);
                    }
                    if($(this).children('i').get(0).style.transform == 'rotate(90deg)' && this != targetObj){
                        $(this).children('i').get(0).style.transform = 'rotate(0deg)';
                    }
                });
                $('#accordion div.panel-collapse').each(function(){
                    if($(this).is(':visible') && this != $(targetObj).closest('.panel-heading').next('.panel-collapse.collapse').get(0)){
                        $(this).slideUp(200);
                    }
                });
    
                var panelContentCssId = $(event.target).attr('href');
                if(!$(panelContentCssId).is(':visible')){
                    $(targetObj).removeClass('collapsed');
                    if($(targetObj).children('i').first().hasClass('fa-angle-right')){
                        $(targetObj).children('i').get(0).style.transform = 'rotate(90deg)';
                    }
                    $(panelContentCssId).slideDown(200);
                }else{
                    $(targetObj).addClass('collapsed');
                    if($(targetObj).children('i').get(0).style.transform == 'rotate(90deg)'){
                        $(targetObj).children('i').get(0).style.transform = 'rotate(0deg)';
                    }
                    $(panelContentCssId).slideUp(200);
                }
            }else{
                var dropDownList = event.target;
                if(trigger.classList.contains('fa-angle-down')){
                    dropDownList = $(trigger).closest('button').get(0);
                }
                $(dropDownList).trigger('click');
            }
        };
        $('#accordion a[data-parent="#accordion"]').each(function(){
            if(!$(this).isBound('click', accordionClick)){
                $(this).unbind('click');
                $(this).bind('click', accordionClick);
            }
            
            if($(this).data('id') == "{$accordionOpenId}"){
                $(this).click();
            }
        });
        
        $('span.clickable.pay-invoice').on('click', function(event){
            showPaymentBoxIniFrame(event, null, '{$csrfCookieName}');
        });
EOF;

    if($canAddInvoice){
        $submitBtnTxt               = __('Submit');
        $cancelBtnTxt               = __('Cancel');
        $titleTxt                   = __('Create Payment Invoice');
        $purchaseCode               = Configure::read('Payment.code.website.development');
        $inlineJS .= <<<EOF
            $('.create-invoice').on('click', function(){
                var stageId = $(this).data('id');
                bootbox.dialog({
                    message: '<iframe src="/admin/payment/payment_invoices/add/{$purchaseCode}/{$project['WebDevelopmentProject']['project_owner']}?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$titleTxt}',
                    buttons: {
                        'Submit' : {
                            'label' : '{$submitBtnTxt}',
                            'className' : 'btn-sm btn-success submit-iframe-form-btn',
                            'otherBtnAttrs': 'data-reload=false',
                            'callback' : function(event){
                                if($(event.target).hasClass('close-popup-after-submit')){
                                    $(event.target).removeClass('close-popup-after-submit');
                                }
                                submitIframeForm(event, true);
                                var invoiceId = $(event.target).data("ajax-return-data");
                                if(invoiceId){
                                    $.ajax({
                                        url: "/admin/web_development/web_development_stages/addInvoice/"+stageId+"/"+invoiceId+"/{$project['WebDevelopmentProject']['project_owner']}/{$purchaseCode}.json",
                                        type: "POST",
                                        contentType: "application/json",
                                        dataType: "json",
                                        beforeSend: function ( xhr ) {
                                            $.gritter.removeAll();
                                            if($.fn.ace_ajax){
                                                $('[data-ajax-content=true]').ace_ajax('startLoading');
                                            }
                                        }
                                    }).done(function ( data ) {
                                        try{
                                            data = $.parseJSON(data);
                                            messageBox(data);
                                        }catch(err){
                                            /* Success, do nothing */
                                        }
                                    }).fail(function(jqXHR, textStatus, errorThrown) {
                                        ajaxErrorHandler(jqXHR, textStatus, errorThrown);
                                    }).always(function() {
                                        if($.fn.ace_ajax){
                                            $('[data-ajax-content=true]').ace_ajax('stopLoading', true);
                                        }
                                    });
                                }
                                window.location.reload(); 
                                return false;
                            }
                        },
                        'Cancel' : {
                            'label' : '{$cancelBtnTxt}',
                            'className' : 'btn-sm btn-sm'
                        }
                    }
                });
            });
EOF;
    }

    if($canAddStage){
        $popupClickAddTitleTxt      = __('Add Project Stage');
        $popupClickAddTaskTitleTxt  = __('Add Task');
        $submitBtnTxt               = __('Submit');
        $cancelBtnTxt               = __('Cancel');
        $inlineJS .= <<<EOF
        
            $('.loading-btn').on(ace.click_event, function () {
                var btn = $(this);
                btn.button('loading');
                var dialogMessage = btn.parent().hasClass('add-task') ? '<iframe src="/admin/task_management/task_management_tasks/add/' + btn.parent().get(0).dataset.id + '?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>' : '<iframe src="/admin/web_development/web_development_stages/add/{$project['WebDevelopmentProject']['id']}?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>'; 
                var titleTxt      = btn.parent().hasClass('add-task') ? '{$popupClickAddTaskTitleTxt}' : '{$popupClickAddTitleTxt}';
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
            
EOF;
    }
    
    if($canEditStage){
        $popupClickEditTitleTxt = __('Edit Project Stage');
        $inlineJS .= <<<EOF
            $('span.edit-stage').on(ace.click_event, function () {
                bootbox.dialog({
                    message: '<iframe src="/admin/web_development/web_development_stages/edit/{$project['WebDevelopmentProject']['id']}/' + this.dataset.id + '?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$popupClickEditTitleTxt}',
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
            });
EOF;
    }
    
    if($canDeleteStage){
        $popupClickEditTitleTxt = __('Delete Project Stage');
        $confirmBtnTxt          = __('Confirmed');
        $inlineJS .= <<<EOF
            $('span.delete-stage').on(ace.click_event, function () {
                bootbox.dialog({
                    message: '<iframe src="/admin/web_development/web_development_stages/delete/{$project['WebDevelopmentProject']['id']}/' + this.dataset.id + '?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$popupClickEditTitleTxt}',
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
            });
EOF;
    }

    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>