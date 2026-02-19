<?php
    $this->request->data = empty($this->request->data) ? (isset($sender) ? $sender : array()) : $this->request->data;
?>
<?php echo $this->Form->create('EmailMarketing.EmailMarketingSender'); ?>
<div class="row">
    <div class="col-xs-12">
        <?php
            echo $this->Form->input('sender_domain', array(
                'label'         => array('text' => __('Sender Domain') .'&nbsp;<span class="mandatory">*</span>'),
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'tabindex'      => 1
            ));
        ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        $('form[id^="EmailMarketingSender"][id$="Form"]').validate({
            rules: {
                    "data[EmailMarketingSender][sender_domain]": {
                        required: true,
                        maxlength: 200
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