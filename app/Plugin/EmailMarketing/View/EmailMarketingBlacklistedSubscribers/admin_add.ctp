<?php echo $this->Form->create('EmailMarketing.EmailMarketingBlacklistedSubscriber'); ?>
    <?php 
        echo $this->Form->hidden('id');
        if(isset($userId)){
            echo $this->Form->hidden('email_marketing_user_id', array('value' => $userId));
        } 
        echo $this->Form->input('created' , array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', strtotime('now'))));
    ?>
    <?php if(isset($userList) && is_array($userList)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('email_marketing_user_id', array(
                        'label'         => array('text' => __('Email Marketing Client') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => $userList,
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 1
                    ));
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('email', array(
                    'label'         => array('text' => __('Email') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 2
                ));
            ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        $('form[id^="EmailMarketingMailingList"][id$="Form"]').validate({
            rules: {
                    "data[EmailMarketingBlacklistedSubscriber][email_marketing_user_id]": {
                        required: true
                    },
                    "data[EmailMarketingBlacklistedSubscriber][email]": {
                        required: true,
                        email: true
                    }
            }
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>