<?php 
    echo $this->element('users/login');
    echo $this->element('users/forget_password', array(
    
    ));
    echo $this->element('users/register', array(
        'services' => $services
    ));
    echo $this->element('users/reset_password', array(
        'token' => $token
    ));
?>