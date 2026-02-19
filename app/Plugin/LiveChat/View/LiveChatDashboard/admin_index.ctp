<?php $isClient = stristr($userGroupName, Configure::read('System.client.group.name')); ?>

<div class="row dashboard ">

    <?php if($isClient === FALSE): ?>
        
    <?php else: ?>
           
        <h1 class="grey"><?php echo __('Be avaliable no matter where you go'); ?></h1>
        
        <div class="space-24"></div>

        <div class="row center">
            <img src="/img/admin/sketch-3042584__340.jpg" border="0" />
        </div>

        <div class="space-24"></div>
        
        <?php $hasActivatedAccount = $this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index'); ?>

        <?php if(!$hasActivatedAccount): ?>
            <button class="btn btn-success btn-block activate"><?php echo __('Activate Account'); ?></button>
        <?php endif; ?>
        
    <?php endif; ?>
    
</div>

<!-- page specific plugin scripts -->
<?php
    $submitBtn = __("Submit");
    $resetBtn  = __("Reset");
    $cancelBtn = __("Cancel");
    $title     = __("Live Chat Service");
    
    $inlineJS = '';
    if(!$hasActivatedAccount && $isClient){
        $inlineJS = <<<EOF
            $('button.activate').click(function(){
                bootbox.dialog({
                    message: '<iframe src="/admin/live_chat/live_chat_users/add/?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
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