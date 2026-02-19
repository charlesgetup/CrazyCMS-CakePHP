<?php
    $this->request->data = empty($this->request->data) ? (isset($plan) ? $plan : array()) : $this->request->data;
    echo $this->Form->hidden('id');
?>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('name', array(
                'label'         => array('text' => __('Name') .'&nbsp;<span class="mandatory">*</span>'),
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 1
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('description', array(
                'label'         => array('text' => __('Description') .'&nbsp;<span class="mandatory">*</span>'),
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
            echo $this->Form->input('price', array(
                'label'         => array('text' => __('Price per month') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 5
            ));
        ?>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        $('form[id^="LiveChatPlan"][id$="Form"]').validate({
            rules: {
                    "data[LiveChatPlan][name]": {
                        required: true,
                        maxlength: 100
                    },
                    "data[LiveChatPlan][description]": {
                        required: true
                    },
                    "data[LiveChatPlan][price]": {
                        required: true,
                        number: true,
                        max: 9999999999.99
                    },
            }
        });
        
        $(document).ready(function(){
            loadTinymce("LiveChatPlanDescription");
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>