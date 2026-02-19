<?php
    echo $this->Form->create('EmailMarketing.EmailMarketingUser');
    echo $this->element('plans/email_marketing_plan_user_form_fields', array(
        "plan"      => $plan
    )); 
    echo $this->Form->end();
?>