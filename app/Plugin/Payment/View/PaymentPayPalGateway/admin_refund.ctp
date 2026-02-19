<?php
    echo $this->element('payment/payment_confirm_info', array(
        'paymentInvoiceId'  => $paymentInvoiceId,
        'userInfo'          => $userInfo,
        'paymentInfo'       => $paymentInfo,
        'isTempInvoice'     => $isTempInvoice,
        'billingAddress'    => $billingAddress,
        'country'           => $country,
        'currency'          => $currency
    )); 
?>

<?php echo $this->Form->create('Payment.PaymentPayPalGateway', array('url' => array('controller' => 'PaymentPayPalGateway', 'action' => 'refund', 'admin' => true, $paymentInvoiceId))); ?> 
    <?php
        echo $this->Form->input('amount', array(
            'label' => array('text' => __('Refund Amount') .'&nbsp;<span class="mandatory">*</span>'),
            'autocomplete' => 'off',
            'class' => 'required input_field_2',
            'div' => false,
            'step' => '.01',
            'min' => 0,
            'tabindex' => 1
        ));
    ?>
    
    <div class="cleaner_h10"></div>
    
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    
    $inlineJS = <<<EOF
    
        $(document).ready(function(){
            $('form[id^="PaymentPayPalGateway"][id$="Form"]').validate({
                rules: {
                    "data[PaymentPayPalGateway][amount]": {
                        number: true,
                        required: true
                    }
                }
            });
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>