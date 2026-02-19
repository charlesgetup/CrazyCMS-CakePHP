<?php
    echo $this->element('payment/payment_shield', array(
        'paymentInvoiceId'  => $pendingInvoiceId,
        'userInfo'          => $userInfo,
        'paymentInfo'       => $paymentInfo,
        'isTempInvoice'     => $isTempInvoice,
        'billingAddress'    => $billingAddress,
        'country'           => $country,
        'companyName'       => $companyName
    )); 
?>