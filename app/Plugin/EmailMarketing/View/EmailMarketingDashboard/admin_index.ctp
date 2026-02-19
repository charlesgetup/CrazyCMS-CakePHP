<?php $isClient = stristr($userGroupName, Configure::read('System.client.group.name')); ?>

<div class="row dashboard ">

    <?php if($isClient === FALSE): ?>
        
    <?php else: ?>
           
        <h1 class="grey"><?php echo __('Email Marketing is Super Simple'); ?></h1>
        
        <div class="space-24"></div>
        
        <?php $hasActivatedAccount = $this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index'); ?>
                
        <div class="row">
            <h2 class="grey"><strong><?php echo __('Step 1'); ?></strong>&nbsp;&nbsp;<?php echo ($hasActivatedAccount ? '<a href="/admin/dashboard#/admin/email_marketing/email_marketing_mailing_lists">' : '') .__('Set up mailing list') .($hasActivatedAccount ? '</a>' : ''); ?></h2>
            <p>
                <?php echo __("Import your contacts in CSV format or manually put them in a list. While importing, you also can import other attributes together with the email addresses and these attributes can be embeded in the email body seamlessly."); ?>
            </p>
        </div>
        
        <div class="row">
            <h2 class="grey"><strong><?php echo __('Step 2'); ?></strong>&nbsp;&nbsp;<?php echo ($hasActivatedAccount ? '<a href="/admin/dashboard#/admin/email_marketing/email_marketing_campaigns">' : '') .__('Create campaign') .($hasActivatedAccount ? '</a>' : ''); ?></h2>
            <p>
                <?php echo __("With our campaign editor, you can drag and drop all email elements to every where you want. Or you can find a well designed template directly from our online store. If you have already designed the template somewhere else, just provide the web page URL, and we will load the template in campaign editor for you."); ?>
            </p>
        </div>
        
        <div class="row">
            <h2 class="grey"><strong><?php echo __('Step 3'); ?></strong>&nbsp;&nbsp;<?php echo ($hasActivatedAccount ? '<a href="/admin/dashboard#/admin/email_marketing/email_marketing_campaigns">' : '') .__('Send email and done') .($hasActivatedAccount ? '</a>' : ''); ?></h2>
            <p>
                <?php echo __("When the campaign is created, all you need to do is press the send button. After email is sent, all recepients' response will be tracked. Don't want to send the email right away? Fine. You can schedule the sending time. And all the reports and statistics will be on their way after the email is sent."); ?>
            </p>
        </div>

        <div class="space-24"></div>

        <div class="row">
            <h2 class="grey"><strong><?php echo __('More helpful features'); ?></strong></h2>
        </div>
        
        <div class="space-12"></div>
        
        <div class="row height-220">
            <div class="col-xs-12 col-sm-6 height-100-p">
                <div class="box box-solid bg-primary height-100-p">
                    <div class="box-header">
                        <h4 class="box-title"><strong><?php echo __('Create Sender'); ?></strong></h4>
                    </div>
                    <div class="box-body">
                        <p><?php echo __("Instead of using our default sender domain, you can use your own domain as email sender. The configuration can be done in just 1 minute."); ?></p>
                    </div>
                    <?php if($hasActivatedAccount): ?>
                        <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingSenders/admin_index')): ?>
                            <a href="/admin/dashboard#/admin/email_marketing/email_marketing_senders" class="box-footer">
                                <?php echo __('View Senders'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                            </a>
                        <?php else: ?>
                            <a href="/admin/dashboard#/admin/email_marketing/email_marketing_plans" class="box-footer">
                                <?php echo __('Activate This Feature'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 height-100-p">
                <div class="box box-solid bg-info height-100-p">
                    <div class="box-header">
                        <h4 class="box-title"><strong><?php echo __('Track The Result'); ?></strong></h4>
                    </div>
                    <div class="box-body">
                        <p><?php echo __("Open, link click and bounce action will be reported in our statistics section in full details."); ?></p>
                    </div>
                    <?php if($hasActivatedAccount): ?>
                        <a href="/admin/dashboard#/admin/email_marketing/email_marketing_campaigns" class="box-footer">
                            <?php echo __('View Statistics'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="space-12"></div>
        
        <div class="row height-220">
            <div class="col-xs-12 col-sm-6 height-100-p">
                <div class="box box-solid bg-black height-100-p">
                    <div class="box-header">
                        <h4 class="box-title"><strong><?php echo __('Subscribers Management'); ?></strong></h4>
                    </div>
                    <div class="box-body">
                        <p><?php echo __("Subscriber can unsubscribe the mailing list, and you also can blacklist some of them based on bounce report, too. With one click, you can decide who will receive the email."); ?></p>
                    </div>
                    <?php if($hasActivatedAccount): ?>
                        <a href="/admin/dashboard#/admin/email_marketing/email_marketing_mailing_lists" class="box-footer">
                            <?php echo __('View Subscribers'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 height-100-p">
                <div class="box box-solid bg-warning height-100-p">
                    <div class="box-header">
                        <h4 class="box-title"><strong><?php echo __('Professional Templates Avaliable'); ?></strong></h4>
                    </div>
                    <div class="box-body">
                        <p><?php echo __("Our designers are always on your side. We will periodically put new templates in our online store. Check them regularly, and we are sure that you will find what you need."); ?></p>
                    </div>
                    <?php if($hasActivatedAccount): ?>
                        <a href="/admin/dashboard#/admin/email_marketing/email_marketing_templates" class="box-footer">
                            <?php echo __('View Templates'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
                
        <?php if(!$hasActivatedAccount): ?>
            <div class="space-24"></div>
            <div class="row center">
                <h2><?php echo __('One Click To Activate Your Account Now'); ?></h2>
                <button class="btn btn-success btn-block activate"><?php echo __('Activate Account'); ?></button>
            </div>
        <?php endif; ?>
        
    <?php endif; ?>
    
</div>

<!-- page specific plugin scripts -->
<?php
    $submitBtn = __("Submit");
    $resetBtn  = __("Reset");
    $cancelBtn = __("Cancel");
    $title     = __("Email Marketing Service");
    
    $inlineJS = '';
    if(!$hasActivatedAccount && $isClient){
        $inlineJS = <<<EOF
            $('button.activate').click(function(){
                bootbox.dialog({
                    message: '<iframe src="/admin/email_marketing/email_marketing_users/add/?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                    title: "{$title}",
                    buttons: {
                        "Submit" : {
                            "label" : "{$submitBtn}",
                            "className" : "btn-sm btn-success submit-iframe-form-btn",
                            "callback" : function(event){
                                submitIframeForm(event);
                                return false;
                            }
                        },
                        "Reset" : {
                            "label" : "{$resetBtn}",
                            "className" : "btn-sm btn-danger reset-iframe-form-btn",
                            "callback" : function(event){
                                resetIframeForm(event);
                                return false;
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
    }
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>