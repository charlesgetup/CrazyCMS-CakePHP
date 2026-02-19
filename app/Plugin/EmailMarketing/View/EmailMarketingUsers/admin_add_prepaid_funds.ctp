<?php
    if(empty($tempInvoiceId)){
    
        echo $this->Form->create('EmailMarketing.EmailMarketingUser');
        echo $this->Form->input('deposit_amount', array(
            'label'         => array('text' => __('Deposit amount') .'&nbsp;<span class="mandatory">*</span>'),
            'type'          => 'number',
            'min'           => 0,
            'class'         => 'required col-xs-12 col-sm-12',
            'div'           => false,
            'tabindex'      => 1
        ));
        echo $this->Form->end(); 
        
        $inlineJS = <<<EOF
        $('form[id^="EmailMarketingUser"][id$="Form"]').validate({
            rules: {
                "data[EmailMarketingUser][deposit_amount]": {
                    required: true
                },
            }
        });
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => $inlineJS
        )); 
    
    }else{
        echo $this->element('payment/payment_shield', array(
            'paymentInvoiceId'  => $tempInvoiceId,
            'userInfo'          => $userInfo,
            'paymentInfo'       => $paymentInfo,
            'isTempInvoice'     => $isTempInvoice,
            'billingAddress'    => $billingAddress,
            'country'           => $country,
            'companyName'       => $companyName
        ), array('plugin' => 'payment'));
        
        $inlineJS = <<<EOF
        /* Remove deposit button and let client to use payment gateway button to pay */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").remove();
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => $inlineJS
        )); 
    }
?>