<?php
    echo $this->element('payment/payment_confirm_info', array(
        'paymentInvoiceId'  => $paymentInvoiceId,
        'userInfo'          => $userInfo,
        'paymentInfo'       => $paymentInfo,
        'isTempInvoice'     => $isTempInvoice,
        'billingAddress'    => $billingAddress,
        'country'           => $country,
        'currency'          => $currency
    ), array('plugin' => 'payment')); 
?>

<div class="payment-options">
    <?php if($paymentInfo['recurring_amount'] > 0): ?>
        <?php
            echo $this->element('payment/gateways/paypal_recurring', array(
                'pendingInvoiceId' => $paymentInvoiceId,
                'companyName'      => $companyName,
                'isTempInvoice'    => $isTempInvoice,
            ), array('plugin' => 'payment')); 
        ?>
    <?php else: ?>
        <?php
            echo $this->element('payment/gateways/paypal', array(
                'pendingInvoiceId' => $paymentInvoiceId,
                'companyName'      => $companyName
            ), array('plugin' => 'payment')); 
        ?>
    <?php endif; ?>
</div>