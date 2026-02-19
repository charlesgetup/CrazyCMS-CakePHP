<?php
    echo $this->element('payment/payment_shield', array(
        'paymentInvoiceId'  => $tempInvoiceId,
        'userInfo'          => $userInfo,
        'paymentInfo'       => $paymentInfo,
        'isTempInvoice'     => $isTempInvoice,
        'billingAddress'    => $billingAddress,
        'country'           => $country,
        'companyName'       => $companyName
    ), array('plugin' => 'payment'));
?>