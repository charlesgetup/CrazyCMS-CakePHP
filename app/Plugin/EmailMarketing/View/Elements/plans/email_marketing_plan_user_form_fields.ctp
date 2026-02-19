<?php echo $this->Form->hidden('id'); ?>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('email_marketing_plan_id', array(
                'label'         => array('text' => __('Email Marketing Plan') .'&nbsp;<span class="mandatory">*</span>'),
                'options'       => $planList,
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 2
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('free_emails', array(
                'label'         => array('text' => __('Free Emails (per month)')),
                'value'			=> isset($this->request->data["EmailMarketingUser"]["free_emails"]) ? $this->request->data["EmailMarketingUser"]["free_emails"] : $freeEmails,
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'col-xs-12 col-sm-12',
                'required'      => false,
                'div'           => false,
                'tabindex'      => 3
            ));
        ?>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $validateUser = '';
    if(empty($userId)){
        $validateUser = <<<VALIDATE
            "data[EmailMarketingUser][user_id]": {
                required: true
            },
VALIDATE;
    }

    $inlineJS = <<<EOF
        $('form[id^="EmailMarketingUser"][id$="Form"]').validate({
            rules: {
                    {$validateUser}
                    "data[EmailMarketingUser][email_marketing_plan_id]": {
                        required: true
                    }
            }
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        var closePopupOrNot = function(planId){
            if(planId != '{$this->request->data["EmailMarketingUser"]["email_marketing_plan_id"]}'){
                $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").removeClass("close-popup-after-submit");
            }else{
                $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
            }
        };
        closePopupOrNot($('#EmailMarketingUserEmailMarketingPlanId').val());
        $('#EmailMarketingUserEmailMarketingPlanId').on('selectmenuchange', function(){
            var planId = $(this).val();
            closePopupOrNot(planId);
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>