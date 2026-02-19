<?php
    echo $this->Form->hidden('id');
    echo $this->Form->input('created' , array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', strtotime('now'))));
?>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('email_marketing_user_id', array(
                'label'         => array('text' => __('Owner') .'&nbsp;<span class="mandatory">*</span>'),
                'options'       => $userList,
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
            echo $this->Form->input('name', array(
                'label'         => array('text' => __('Name') .'&nbsp;<span class="mandatory">*</span>'),
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
            echo $this->Form->input('description', array(
                'label'         => array('text' => __('Description')),
                'class'         => 'col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 3
            ));
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('active', array(
                'label'         => array('text' => __('Active') .'&nbsp;<span class="mandatory">*</span>'),
                'options'       => array("1" => 'Active', "0" => 'Inactive'),
                'default'       => (isset($this->request->data['User']['active']) ? $this->request->data['User']['active'] : ""),
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 4
            ));
        ?>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        $('form[id^="EmailMarketingMailingList"][id$="Form"]').validate({
            rules: {
                    "data[EmailMarketingMailingList][email_marketing_user_id]": {
                        required: true
                    },
                    "data[EmailMarketingMailingList][name]": {
                        required: true
                    },
                    "data[EmailMarketingMailingList][active]": {
                        required: true
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