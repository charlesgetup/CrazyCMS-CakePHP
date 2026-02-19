<?php 
    $isClient   = stristr($userGroupName, Configure::read('System.client.group.name'));
?>

<div class="space-10"></div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
        <?php
            echo $this->Form->hidden('id');
            echo $this->Form->input('first_name', array(
                'label'         => array('text' => __('First Name') .'&nbsp;<span class="mandatory">*</span>'),
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'maxlength'     => 255,
                'div'           => false,
                'tabindex'      => 1
            ));
        ?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <?php
            echo $this->Form->input('last_name', array(
                'label'         => array('text' => __('Last Name') .'&nbsp;<span class="mandatory">*</span>'),
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'maxlength'     => 255,
                'div'           => false,
                'tabindex'      => 2
            ));
        ?>
    </div>
    
</div>
    
<div class="space"></div>
    
<div class="row">
    
    <div class="col-xs-12 col-sm-6">
        <?php
            echo $this->Form->input('email', array(
                'label'         => array(
                    'text'  => '<span data-rel="tooltip" data-placement="right" data-original-title="' .__('Email is used as user name and it cannot be changed. Ask our staff if you really want to change it.') .'" class="tooltip-text">' .__('Email') .'</span>&nbsp;<span class="mandatory">*</span>',
                ),
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'maxlength'     => 255,
                'div'           => false,
                'tabindex'      => 3,
                'disabled'      => ($isClient || $isProfile) ? 'disabled' : ''
            ));
            if($isClient || $isProfile){
                echo $this->Form->input('email', array(
                    'type'  => 'hidden',
                    'value' => $this->request->data['User']['email'] 
                ));
            }
        ?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <?php
            echo $this->Form->input('email_confirm', array(
                'label'         => array('text' => __('Email Confirm') .'&nbsp;<span class="mandatory">*</span>'),
                'autocomplete'  => 'off',
                'class'         => 'required col-xs-12 col-sm-12',
                'div'           => false,
                'value'         => (isset($this->request->data['User']['email']) ? $this->request->data['User']['email'] : ""),
                'tabindex'      => 4,
                'disabled'      => ($isClient || $isProfile) ? 'disabled' : ''
            ));
            if($isClient || $isProfile){
                echo $this->Form->input('email_confirm', array(
                    'type'  => 'hidden',
                    'value' => $this->request->data['User']['email'] 
                ));
            }
        ?>
    </div>
</div>

<div class="space"></div>

<div class="row">
    
    <div class="col-xs-12 col-sm-6">
        <?php
            if($userGroupName == Configure::read('System.admin.group.name')){
                echo $this->Form->input('active',array(
                    'label'         => array('text' => __('Active') .'&nbsp;<span class="mandatory">*</span>'),
                    'options'       => array("1" => __('Active'), "0" => __('Inactive')),
                    'default'       => (isset($this->request->data['User']['active']) ? $this->request->data['User']['active'] : ""),
                    'autocomplete'  => 'off',
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 7
                ));
            }else{
                echo $this->Form->input('phone', array(
                    'label'         => array('text' => __('Contact Phone Number') .'&nbsp;<span class="mandatory">*</span>'),
                    'autocomplete'  => 'off',
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 8
                ));
            }
        ?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <?php
            if($userGroupName == Configure::read('System.admin.group.name')){
                echo $this->Form->input('phone', array(
                    'label'         => array('text' => __('Contact Phone Number') .'&nbsp;<span class="mandatory">*</span>'),
                    'autocomplete'  => 'off',
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 8
                ));
            }else{
                echo $this->Form->input('company', array(
                    'label'         => array('text' => '<span data-rel="tooltip" data-placement="right" data-original-title="' .__('Used for invoicing purpose') .'" class="tooltip-text">' .__('Company') .'</span>'),
                    'autocomplete'  => 'off',
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 9
                ));
            }
        ?>
    </div>
</div>

<div class="space"></div>
    
<div class="row">
    
    <div class="col-xs-12 col-sm-6">
        <?php
            if($userGroupName == Configure::read('System.admin.group.name')){
                echo $this->Form->input('company', array(
                    'label'         => array('text' => '<span data-rel="tooltip" data-placement="right" data-original-title="' .__('Used for invoicing purpose') .'" class="tooltip-text">' .__('Company') .'</span>'),
                    'autocomplete'  => 'off',
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 9
                ));
            }else{
                echo $this->Form->input('abn_acn', array(
                    'label'         => array(
                        'text'  => '<span data-rel="tooltip" data-placement="right" data-original-title="' .__('Used for invoicing purpose') .'" class="tooltip-text">' .__('ABN/ACN') .'</span>&nbsp;(' .__('Australian business only') .')',
                        'style' => 'width:300px;',
                    ),
                    'autocomplete'  => 'off',
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 10
                ));
            }
        ?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <?php
            if($userGroupName == Configure::read('System.admin.group.name')){
                echo $this->Form->input('abn_acn', array(
                    'label'         => array(
                        'text'  => '<span data-rel="tooltip" data-placement="right" data-original-title="' .__('Used for invoicing purpose') .'" class="tooltip-text">' .__('ABN/ACN') .'</span>&nbsp;(' .__('Australian business only') .')',
                        'style' => 'width:300px;',
                    ),
                    'autocomplete'  => 'off',
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 10
                ));
            }
        ?>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = "";
    if(isset($this->request->data['User']['active'])){
        if($this->request->data['User']['active'] == 0){
            $inlineJS = '$("#UserActive").val(0);';
        }else{
            $inlineJS = '$("#UserActive").val(1);';
        }
    }
    
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    ));
?>