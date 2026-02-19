<?php if(!empty($plans)): ?>
    <?php
        echo $this->element('plans/live_chat_plan_table', array(
            'plans' => $plans,
            'plan'  => $plan
        )); 
    ?>
<?php endif; ?>

<?php if(!empty($plan['LiveChatUser'])): ?>
    <hr />
    <section>
        <h3><?php echo __('Current Plan Settings'); ?></h3>
        <dl class="dl-horizontal">
            <div class="space-12">&nbsp;</div>
            <dt><?php echo __('Payment Cycle'); ?></dt>
            <dd><?php echo Inflector::humanize(Inflector::underscore(strtolower($plan['LiveChatUser']['payment_cycle']))); ?></dd>
            <div class="space-8">&nbsp;</div>
            <?php if(!empty($plan['LiveChatUser']['last_pay_date'])): ?>
                <dt><?php echo __('Last Payment Date'); ?></dt>
                <dd><?php echo $this->Time->i18nFormat($plan['LiveChatUser']['last_pay_date'], '%x'); ?></dd>
                <div class="space-8">&nbsp;</div>
            <?php endif; ?>
            <?php if(!empty($plan['LiveChatUser']['next_pay_date'])): ?>
                <dt><?php echo __('Next Payment Date'); ?></dt>
                <dd><?php echo $this->Time->i18nFormat($plan['LiveChatUser']['next_pay_date'], '%x'); ?></dd>
                <div class="space-8">&nbsp;</div>
            <?php endif; ?>
            <div class="space-12">&nbsp;</div>
            <dt><?php echo __('Adjust Operator Amount'); ?></dt>
            <dd>
                <input type="text" id="operator-amount" />
                <button class="btn btn-sm btn-primary ace-spinner-submit">
                    <span class="bigger-110 no-text-shadow"><?php echo __('Submit'); ?></span>
                </button>
            </dd>
            <div class="space-12">&nbsp;</div>
            <dt>&nbsp;</dt>
            <dd>
                <button class="btn btn-sm btn-success backup-chat-history">
                    <i class="ace-icon fa fa-cloud-download bigger-110"></i>
                    <span class="bigger-110 no-text-shadow"><?php echo __('Backup Chat History'); ?></span>
                </button>
            </dd>
            <div class="space-8">&nbsp;</div>
            <dt>&nbsp;</dt>
            <dd>
                <button class="btn btn-sm btn-danger cancel-account">
                    <i class="ace-icon fa fa-eraser bigger-110"></i>
                    <span class="bigger-110 no-text-shadow"><?php echo __('Cancel Account'); ?></span>
                </button>
            </dd>
        </dl>
    </section>
    
    <!-- page specific plugin scripts -->
    <?php

        $successMsg     = __('Operator amount has been updated.');
        $warningMsg     = __('Somthing went wrong. Please try again later.');
        $payForNewOpAcc = __('New operator accounts'); 
        $cancelAccTitle = __('Cancel Account');
        $confirmBtn     = __('Confirm');
        $cancelBtn      = __('Cancel');
        $inlineJS = <<<EOF
            $('#operator-amount').ace_spinner({
                value:{$plan['LiveChatUser']['operator_amount']},
                min:1,
                step:1, 
                on_sides: true, 
                icon_up:'ace-icon fa fa-plus bigger-110', 
                icon_down:'ace-icon fa fa-minus bigger-110', 
                btn_up_class:'btn-success' , 
                btn_down_class:'btn-danger'
            }).closest('.ace-spinner')
            .on('changed.fu.spinbox', function(){
                
            });
            $('.ace-spinner-submit').on('click', function(event){
            
                $.ajax({
                    async:      false,
                    cache:      false,
                    url:        "/admin/live_chat/live_chat_users/adjustOperatorAmount.json",
                    method:     "POST",
                    dataType:   "json",
                    headers:    {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                    data:       {"user_id": {$plan['LiveChatUser']['user_id']}, "operator_amount": $('#operator-amount').val()}
                }).done(function( data ) {
                    if(data){
                        if(data.status && data.message){
                            messageBox({"status": data.status, "message": data.message});
                            if(data.charge){
                                if($('a.extra-charge-data').length){
                                    $('a.extra-charge-data').remove();
                                }
                                $('<a class="extra-charge-data invisible" data-link-url="/admin/live_chat/live_chat_plans/alter/'+data.charge+'" data-title="{$payForNewOpAcc}">&nbsp;</a>').insertAfter($('.ace-spinner-submit'));
                                showPaymentBoxIniFrame(event, $('a.extra-charge-data').get(0), '{$csrfCookieName}');
                            }
                        }else{
                            messageBox({"status": SUCCESS, "message": "{$successMsg}"});
                        }
                    }else{
                        messageBox({"status": WARNING, "message": "{$warningMsg}"});
                    }
                }).fail(function( jqxhr, settings, exception ) {
                    ajaxErrorHandler( jqxhr, settings, exception );
                });
            });
            
            $('.backup-chat-history').on('click', function(event){
            
                $.ajax({
                    async:      false,
                    cache:      false,
                    url:        "/admin/live_chat/live_chat_users/backupAccount.json",
                    method:     "POST",
                    dataType:   "json",
                    headers:    {"X-CSRF-Token" : window.getCookie('{$csrfCookieName}')},
                }).done(function( data ) {
                    if(data && data.status && data.message){
                        messageBox({"status": data.status, "message": data.message, "sticky": true, "after_close": "function(){ window.location.reload();}"});
                    }else{
                        messageBox({"status": WARNING, "message": "{$warningMsg}", "after_close": "function(){ window.location.reload();}"});
                    }
                }).fail(function( jqxhr, settings, exception ) {
                    ajaxErrorHandler( jqxhr, settings, exception );
                });
            });
            
            $('.cancel-account').on('click', function(){
                bootbox.dialog({
                    message: '<iframe src="/admin/live_chat/live_chat_users/delete/'+{$plan['LiveChatUser']['id']}+'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: "{$cancelAccTitle}",
                    onEscape: false,
                    buttons: {
                        "Confirm" : {
                            "label" : "{$confirmBtn}",
                            "className" : "btn-sm btn-success submit-iframe-form-btn",
                            "callback" : function(event){
                                submitIframeForm(event);
                                setTimeout(function(){
                                    window.location.href = "/admin/dashboard#/admin/live_chat/live_chat_dashboard";
                                    window.location.reload();
                                    return false;
                                }, 2000);
                            }
                        },
                        "Cancel" : {
                            "label" : "{$cancelBtn}",
                            "className" : "btn-sm btn-sm"
                         }
                    }
                });
            });
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'loadedScripts' => array('fuelux/fuelux.spinner.js'),
            'inlineJS' => $inlineJS
        )); 
    ?>
<?php endif; ?>