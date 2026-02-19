<?php
    echo $this->Form->create('LiveChat.LiveChatPlan');
    echo $this->element('plans/live_chat_plan_form_fields');
    echo $this->Form->end();
?>