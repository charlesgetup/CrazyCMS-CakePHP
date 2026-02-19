<?php echo $this->Form->hidden('id'); ?>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('live_chat_plan_id', array(
                'label'         => array('text' => __('Live Chat Plan') .'&nbsp;<span class="mandatory">*</span>'),
                'options'       => $planList,
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 2
            ));
        ?>
    </div>
<div class="row">
</div>
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('operator_amount', array(
                'label'         => array(
                    'text'  => '<span data-rel="tooltip" data-placement="right" data-original-title="' .__('Max number of agents who will use LiveChat service.') .'" class="tooltip-text">' .__('Operator Amount') .'</span>&nbsp;<span class="mandatory">*</span>',
                ),
                'type'          => 'number',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'min'           => 1,
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
            "data[LiveChatUser][user_id]": {
                required: true
            },
VALIDATE;
    }
    
    $inlineJS = <<<EOF
        $('form[id^="LiveChatUser"][id$="Form"]').validate({
            rules: {
                    {$validateUser}
                    "data[LiveChatUser][live_chat_plan_id]": {
                        required: true
                    },
                    "data[LiveChatUser][operator_amount]": {
                        required: true,
                        min: 1
                    }
            }
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        var closePopupOrNot = function(planId){
            if(planId != '{$this->request->data["LiveChatUser"]["live_chat_plan_id"]}'){
                $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").removeClass("close-popup-after-submit");
            }else{
                $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
            }
        };
        closePopupOrNot($('#LiveChatUserLiveChatPlanId').val());
        $('#LiveChatUserLiveChatPlanId').on('selectmenuchange', function(){
            var planId = $(this).val();
            closePopupOrNot(planId);
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>