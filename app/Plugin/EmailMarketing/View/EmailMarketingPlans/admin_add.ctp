<?php
    echo $this->Form->create('EmailMarketing.EmailMarketingPlan');
    echo $this->element('plans/email_marketing_plan_form_fields');
    echo $this->Form->end();
?>