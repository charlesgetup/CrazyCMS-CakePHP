<div class="row">
    <div class="col-xs-12">
        <dl id="dt-list-1" class="dl-horizontal">
            <dt><?php echo __("Description"); ?></dt>
            <dd><?php echo $agreementDetails['name']; ?></dd>
            <dt><?php echo __("Active"); ?></dt>
            <dd><?php echo empty($agreementDetails['active']) ? '<span class="red">' .__("No") .'</span>' : '<span class="green">' .__("Yes") .'</span>'; ?></dd>
            <dt><?php echo __("State"); ?></dt>
            <dd><?php echo __($agreementDetails['state']); ?></dd>
            <dt><?php echo __("Start Time"); ?></dt>
            <dd><?php echo $agreementDetails['startTime']; ?></dd>
            <dt><?php echo __("Final Payment Date"); ?></dt>
            <dd><?php echo $agreementDetails['finalPaymentDate']; ?></dd>
            <dt><?php echo __("Outstanding Balance"); ?></dt>
            <dd><?php echo $agreementDetails['outstandingBalance']; ?></dd>
            <dt><?php echo __("Last Payment Date"); ?></dt>
            <dd><?php echo $agreementDetails['lastPaymentDate']; ?></dd>
            <dt><?php echo __("Last Payment Amount"); ?></dt>
            <dd><?php echo $agreementDetails['lastPaymentAmount']; ?></dd>
            <dt><?php echo __("Next Payment Date"); ?></dt>
            <dd><?php echo $agreementDetails['nextPaymentDate']; ?></dd>
            <dt><?php echo __("Failed Payment Count"); ?></dt>
            <dd><?php echo $agreementDetails['failedPaymentCount']; ?></dd>
            <dt><?php echo __("Cycles Completed"); ?></dt>
            <dd><?php echo $agreementDetails['cyclesCompleted']; ?></dd>
            <dt><?php echo __("Cycles Remaining"); ?></dt>
            <dd><?php echo $agreementDetails['cyclesRemaining']; ?></dd>
        </dl>
        <br />
        <div class="center">
            <?php if(!in_array(strtoupper($agreementDetails['state']), ["SUSPENDED", "CANCELLED"])): ?>
                <button class="btn btn-white btn-warning btn-bold suspend-agreement">
                    <i class="ace-icon fa fa-pause bigger-120 orange"></i>
                    <?php echo __("Suspend"); ?>
                </button>
            <?php endif; ?>
            <?php if(!in_array(strtoupper($agreementDetails['state']), ["ACTIVE", "CANCELLED"])): ?>
                <button class="btn btn-white btn-info btn-bold reactive-agreement">
                    <i class="ace-icon fa fa-refresh bigger-120 blue"></i>
                    <?php echo __("Reactivate"); ?>
                </button>
            <?php endif; ?>
            <?php if(strtoupper($agreementDetails['state']) != "CANCELLED"): ?>
                <button class="btn btn-white btn-info btn-bold cancel-agreement">
                    <i class="ace-icon fa fa-trash bigger-120 blue"></i>
                    <?php echo __("Cancel"); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    
    $suspendPopupTitle = __('Suspend Recurring Agreement');
    $reactivatePopupTitle = __('Reactivate Recurring Agreement');
    $cancelPopupTitle = __('Cancel Recurring Agreement');
    $suspendBtn = __('Suspend');
    $reactivateBtn = __('Reactivate');
    $cancelBtn = __('Cancel');
    $closeBtn = __('Close');
    
    $suspendJS = '';
    if(!in_array(strtoupper($agreementDetails['state']), ["SUSPENDED", "CANCELLED"])){
    
        $suspendJS = <<<EOF
        
            $('.suspend-agreement').on('click', function(){
                bootbox.dialog({
                    message: '<iframe src="/admin/payment/payment_pay_pal_gateway/handleRecurringAgreement/suspend/{$superUserId}/{$agreementDetails['recurring_agreement_id']}?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$suspendPopupTitle}',
                    onEscape: false,
                    buttons: {
                        "Update" : {
                            "label" : "{$suspendBtn}",
                            "className" : "btn-sm btn-warning submit-iframe-form-btn",
                            "callback" : function(event){
                                submitIframeForm(event);
                                setTimeout(function(){actuateLink($(event.target).closest(".modal-footer").siblings(".modal-header").children(".bootbox-close-button.close"));}, 4000);
                                window.location.reload(); /* Reload window to show flash message */
                                return false;
                            }
                        },
                        "Close" : {
                            "label" : '{$closeBtn}',
                            "className" : "btn-sm btn-sm",
                            "callback" : function(event){
                                window.location.reload(); /* Reload window to show flash message */
                            }
                        }
                    }
                });
            });
        
EOF;
    }
    
    $reactivateJS = '';
    if(!in_array(strtoupper($agreementDetails['state']), ["ACTIVE", "CANCELLED"])){
    
        $reactivateJS = <<<EOF
        
            $('.reactive-agreement').on('click', function(){
                bootbox.dialog({
                    message: '<iframe src="/admin/payment/payment_pay_pal_gateway/handleRecurringAgreement/reactivate/{$superUserId}/{$agreementDetails['recurring_agreement_id']}?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$reactivatePopupTitle}',
                    onEscape: false,
                    buttons: {
                        "Update" : {
                            "label" : "{$reactivateBtn}",
                            "className" : "btn-sm btn-success submit-iframe-form-btn",
                            "callback" : function(event){
                                submitIframeForm(event);
                                setTimeout(function(){actuateLink($(event.target).closest(".modal-footer").siblings(".modal-header").children(".bootbox-close-button.close"));}, 4000);
                                window.location.reload(); /* Reload window to show flash message */
                                return false;
                            }
                        },
                        "Close" : {
                            "label" : '{$closeBtn}',
                            "className" : "btn-sm btn-sm",
                            "callback" : function(event){
                                window.location.reload(); /* Reload window to show flash message */
                            }
                        }
                    }
                });
            });
        
EOF;
    }
    
    $cancelJS = '';
    if(strtoupper($agreementDetails['state']) != "CANCELLED"){
    
        $reactivateJS = <<<EOF
        
            $('.cancel-agreement').on('click', function(){
                bootbox.dialog({
                    message: '<iframe src="/admin/payment/payment_pay_pal_gateway/handleRecurringAgreement/cancel/{$superUserId}/{$agreementDetails['recurring_agreement_id']}?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: '{$cancelPopupTitle}',
                    onEscape: false,
                    buttons: {
                        "Update" : {
                            "label" : "{$cancelBtn}",
                            "className" : "btn-sm btn-danger submit-iframe-form-btn",
                            "callback" : function(event){
                                submitIframeForm(event);
                                setTimeout(function(){actuateLink($(event.target).closest(".modal-footer").siblings(".modal-header").children(".bootbox-close-button.close"));}, 4000);
                                window.location.reload(); /* Reload window to show flash message */
                                return false;
                            }
                        },
                        "Close" : {
                            "label" : '{$closeBtn}',
                            "className" : "btn-sm btn-sm",
                            "callback" : function(event){
                                window.location.reload(); /* Reload window to show flash message */
                            }
                        }
                    }
                });
            });
        
EOF;
    }
    
    $inlineJS = $suspendJS .$reactivateJS .$cancelJS;
        
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>