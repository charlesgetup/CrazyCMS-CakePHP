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
            echo $this->Form->input('email_limit', array(
                'label'         => array('text'  => __('Email Limit (per month)') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 3
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('subscriber_limit', array(
                'label'         => array('text'  => __('Subscriber Limit') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 4
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('sender_limit', array(
                'label'         => array('text'  => __('Custom Domain Limit') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 5
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('extra_attr_limit', array(
                'label'         => array('text'  => __('Extra Attribute Limit') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 6
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('unit_price', array(
                'label'         => array('text' => __('Price per email') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 7
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('total_price', array(
                'label'         => array('text' => __('Price per month') .'&nbsp;<span class="mandatory">*</span>'),
                'type'          => 'number',
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 8
            ));
        ?>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
    
        $('form[id^="EmailMarketingPlan"][id$="Form"]').validate({
            rules: {
                    "data[EmailMarketingPlan][name]": {
                        required: true,
                        maxlength: 100
                    },
                    "data[EmailMarketingPlan][description]": {
                        required: true
                    },
                    "data[EmailMarketingPlan][email_limit]": {
                        required: true,
                        number: true,
                        max: 99999999999
                    },
                    "data[EmailMarketingPlan][extra_attr_limit]": {
                        required: true,
                        number: true,
                        max: 99999999999
                    },
                    "data[EmailMarketingPlan][sender_limit]": {
                        required: true,
                        number: true,
                        max: 99999999999
                    },
                    "data[EmailMarketingPlan][subscriber_limit]": {
                        required: true,
                        number: true,
                        max: 99999999999
                    },
                    "data[EmailMarketingPlan][unit_price]": {
                        required: true,
                        number: true,
                        max: 9999999999.99
                    },
                    "data[EmailMarketingPlan][total_price]": {
                        required: true,
                        number: true,
                        max: 9999999999.99
                    }
            }
        });

        $(document).ready(function(){
            loadTinymce("EmailMarketingPlanDescription");
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>