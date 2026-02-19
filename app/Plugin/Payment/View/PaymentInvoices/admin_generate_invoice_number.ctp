<?php echo $this->Form->create('Payment.PaymentInvoice'); ?>

    <?php 
        echo $this->Form->input('purchaseCode', array(
            'label'         => array('text' => __('Purchase Code') .'&nbsp;<span class="mandatory">*</span>'),
            'options'       => $purchaseCodeList,
            'class'         => 'required col-xs-12 col-sm-12',
            'div'           => false,
            'empty'         => __('Choose purchase code'),
            'tabindex'      => 1
        ));
     ?>

<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php

    $inlineJS = <<<EOF
    
        $('form[id^="PaymentInvoice"][id$="Form"]').validate({
            rules: {
                "data[PaymentInvoice][purchaseCode]": {
                    required: true
                }
            }
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>