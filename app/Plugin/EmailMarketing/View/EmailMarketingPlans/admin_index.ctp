<?php 
    if(stristr($userGroupName, Configure::read('System.client.group.name')) === FALSE){
        echo $this->element('plans/email_marketing_plan_admin_index', array(
            'subscriberUnitPrice'      => $subscriberUnitPrice,
            'emailUnitPrice'           => $emailUnitPrice,
            'extraAttributeUnitPrice'  => $extraAttributeUnitPrice,
            'emailSenderUnitPrice'     => $emailSenderUnitPrice
        )); 
    }else{
        echo $this->element('plans/email_marketing_plan_client_index', array(
            'plan'  => $plan
        )); 
    }
?>