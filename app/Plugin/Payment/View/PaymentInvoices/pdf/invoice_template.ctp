<?php
    
    echo $this->element('invoices/invoice_template', array(
        'invoice'   => $invoice,
        'companyAddress'    => $companyAddress,
        'companyName'       => $companyName
    )); 
?>