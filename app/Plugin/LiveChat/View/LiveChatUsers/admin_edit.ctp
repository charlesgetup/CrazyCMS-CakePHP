<?php
    echo $this->Form->create('LiveChat.LiveChatUser');
    echo $this->element('plans/live_chat_plan_user_form_fields', array(
        "plan"      => $plan
    )); 
    echo $this->Form->end();
?>