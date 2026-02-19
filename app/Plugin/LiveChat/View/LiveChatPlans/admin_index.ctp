<?php 
    if(stristr($userGroupName, Configure::read('System.client.group.name')) === FALSE){
        echo $this->element('plans/live_chat_plan_admin_index'); 
    }else{
        echo $this->element('plans/live_chat_plan_client_index', array(
            'plan'  => $plan
        )); 
    }
?>