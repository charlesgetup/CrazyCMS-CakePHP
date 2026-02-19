<?php
    $isEdit = stristr($this->request->params['action'], "edit");
?>
<div class="row">
    <div class="col-xs-12">
        <div>
            <div id="user-profile-3" class="user-profile row">
                <div class="col-sm-offset-1 col-sm-10">
                
                    <?php if(false): ?>
                        <div class="well well-sm">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            &nbsp;
                            <div class="inline middle blue bigger-110"> Your profile is 70% complete </div>
    
                            &nbsp; &nbsp; &nbsp;
                            <div style="width:200px;" data-percent="70%" class="inline middle no-margin progress progress-striped active">
                                <div class="progress-bar progress-bar-success" style="width:70%"></div>
                            </div>
                        </div><!-- /.well -->
                    <?php endif; ?>

                    <div class="space"></div>

                    <?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
                        
                        <div class="tabbable">
                            <ul class="nav nav-tabs padding-16">
                                <li class="active">
                                    <a data-toggle="tab" href="#edit-basic">
                                        <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                                        <?php echo __('Basic Info'); ?>
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#edit-addresses">
                                        <i class="purple ace-icon fa fa-th-list bigger-125"></i>
                                        <?php echo __('Address Info'); ?>
                                    </a>
                                </li>

                                <li>
                                    <a data-toggle="tab" href="#edit-password">
                                        <i class="blue ace-icon fa fa-key bigger-125"></i>
                                        <?php echo __('Password'); ?>
                                    </a>
                                </li>
                                
                                <?php if(!$isEdit && $this->Permissions->isAdmin()): ?>
                                
                                    <li>
                                        <a data-toggle="tab" href="#edit-group">
                                            <i class="blue ace-icon fa fa-group bigger-125"></i>
                                            <?php echo __('Group Info'); ?>
                                        </a>
                                    </li>
                                
                                <?php endif; ?>
                                
<?php if($isEdit && stristr($this->here, "/admin/users/profile/") && $this->Permissions->isAdmin()): //Hide this feature from Client and staff for now. When we have common settings, then we enable it. ?>
                                <li>
                                    <a data-toggle="tab" href="#edit-settings">
                                        <i class="orange ace-icon fa fa-gear bigger-125"></i>
                                        <?php echo __('Settings'); ?>
                                    </a>
                                </li>
<?php endif; ?>
                                
                            </ul>

                            <div class="tab-content profile-edit-tab-content">
                                <div id="edit-basic" class="tab-pane in active">
                                    <?php echo $this->element('users/personal_info_form_fields'); ?>
                                </div>

                                <div id="edit-addresses" class="tab-pane">
                                    <?php echo $this->element('users/address_form_fields'); ?>
                                </div>

                                <div id="edit-password" class="tab-pane">
                                    <div class="space-10"></div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="UserPassword"><?php echo __('New Password'); ?><?php echo $isEdit ? "" : '<span class="mandatory">*</span>'; ?></label>
                                            <?php
                                                echo $this->Form->input('password', array(
                                                    'label'         => false,
                                                    'autocomplete'  => 'off',
                                                    'class'         => ($isEdit ? "" : "required") .' col-xs-12 col-sm-12',
                                                    'value'         => '',
                                                    'required'      => false,
                                                    'div'           => false,
                                                    'tabindex'      => 1
                                                ));
                                            ?>
                                        </div>
                                    </div>

                                    <div class="space-4"></div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label for="UserPasswordConfirm"><?php echo __('Confirm Password'); ?><?php echo $isEdit ? "" : '<span class="mandatory">*</span>'; ?></label>
                                            <?php
                                                echo $this->Form->input('password_confirm',array(
                                                    'label'         => false,
                                                    'type'          => 'password',
                                                    'autocomplete'  => 'off',
                                                    'class'         => ($isEdit ? "" : "required") .' col-xs-12 col-sm-12',
                                                    'value'         => '',
                                                    'required'      => false,
                                                    'div'           => false,
                                                    'tabindex'      => 2
                                                ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
                                    if(isset($preloadGroupId) && !empty($preloadGroupId)){
                                        echo $this->Form->input('group_id', array(
                                            'type'          => 'hidden',
                                            'autocomplete'  => 'off',
                                            'value'         => h($preloadGroupId)
                                        ));
                                    }
                                ?>
                                <?php if(!$isEdit && $this->Permissions->isAdmin()): ?>
                                
                                    <div id="edit-group" class="tab-pane">
                                        <div class="space-10"></div>
                                        
                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <label for="UserServiceId"><?php echo __('Client Service Group'); ?></label>
                                                <?php
                                                    echo $this->Form->input('service_id', array(
                                                        'type'          => 'select',
                                                        'options'       => (empty($services) ? array() : $services),
                                                        'class'         => 'col-xs-12 col-sm-12',
                                                        'div'           => false,
                                                        'empty'         => __('Choose service'),
                                                        'label'         => false,
                                                        'tabindex'      => 3
                                                    ));
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <?php if((!isset($preloadGroupId) || empty($preloadGroupId)) && !empty($groups)): ?>
                                            <div class="space-4"></div>
                                            
                                            <div class="form-group">
                                                <div class="col-xs-12">
                                                    <label for="UserGroupId"><?php echo __('Employee Group'); ?></label>
                                                    <?php
                                                        echo $this->Form->input('group_id', array(
                                                            'type'          => 'select',
                                                            'options'       => $groups,
                                                            'class'         => 'col-xs-12 col-sm-12',
                                                            'div'           => false,
                                                            'empty'         => __('Choose group'),
                                                            'required'      => false,
                                                            'label'         => false,
                                                            'tabindex'      => 4
                                                        ));
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                
                                <?php endif; ?>
                                
<?php if($isEdit && stristr($this->here, "/admin/users/profile/") && $this->Permissions->isAdmin()): //Hide this feature from Client and staff for now. When we have common settings, then we enable it. ?>
                                <div id="edit-settings" class="tab-pane">
                                
                                    <?php if($isEdit && (stristr($currentUserGroup, Configure::read('System.client.group.name')) === FALSE && stristr($currentUserGroup, Configure::read('System.staff.group.name')) === FALSE)): ?>
                                    
                                        <div class="space-10"></div>
    
                                        <div class="form-group radio-btn">
                                            <div class="col-xs-12">
                                                <label class="radio-input" for="UserDebugLog">
                                                    <?php echo __('Record Debug Logs'); ?><br />
                                                    <strong class="red"><i><?php echo __('Enable this will generate huge number of log records in database. Please use it in a proper time, not in rush hours. And DO NOT forget to turn it off.'); ?></i></strong>
                                                </label>
                                                <?php
                                                    echo $this->Form->input('debug_log',array(
                                                        'type'          => 'radio',
                                                        'options'       => array('0' => __('No'), '1' => __('Yes')),
                                                        'class'         => 'col-xs-12 col-sm-12',
                                                        'required'      => false,
                                                        'div'           => false,
                                                        'legend'        => false,
                                                        'tabindex'      => 3
                                                    ));
                                                ?>
                                            </div>
                                        </div>
                                        
                                    <?php endif; ?>

                                    <div class="space-4"></div>
                                
                                </div>
<?php endif; ?>
                                
                            </div>
                        </div>

                        <?php if(!isset($loadInIframe) || $loadInIframe === FALSE): ?>
                            <div class="clearfix form-actions">
                                <?php 
                                    echo $this->Form->button('<i class="ace-icon fa fa-check bigger-110"></i>&nbsp;' .__('Save'), array(
                                        'type' => 'submit', 
                                        'class' => 'btn btn-info', 
                                        'div' => false,
                                        'escape' => false
                                    )); 
                                ?>
                                &nbsp; &nbsp;
                                <?php 
                                    echo $this->Form->button('<i class="ace-icon fa fa-undo bigger-110"></i>&nbsp;' .__('Reset'), array(
                                        'type' => 'reset', 
                                        'class' => 'btn', 
                                        'div' => false,
                                        'escape' => false
                                    )); 
                                ?>
                            </div>
                        <?php endif; ?>
                        
                    <?php echo $this->Form->end(); ?>
                </div><!-- /.span -->
            </div><!-- /.user-profile -->
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php 
    $actionName = Inflector::camelize($this->request->params['action']);
    $validatePassword = "";
    if(!$isEdit){
        $validatePassword = <<<EOD
            "data[User][password]": {
                required: true
            },
            "data[User][password_confirm]": {
                required: true,
                equalTo: "#UserPassword"
            },
EOD;
    }
    $switchAddEditTitle = $this->element('page/admin/switch_add_edit_title');
    $inlineJS = <<<EOF
        var validator = $('form#User{$actionName}Form').validate({
            rules: {
                {$validatePassword}
                "data[User][first_name]": {
                    maxlength: 255,
                    required: true
                },
                "data[User][last_name]": {
                    maxlength: 255,
                    required: true
                },
                "data[User][email]": {
                    required: true,
                    email: true
                },
                "data[User][email_confirm]": {
                    required: true,
                    equalTo: "#UserEmail"
                },
                "data[User][phone]": {
                    digits: true,
                    required: true
                },
                "data[Address][0][street_address]": {
                    maxlength: 255,
                    required: true
                },
                "data[Address][0][suburb]": {
                    maxlength: 255,
                    required: true
                },
                "data[Address][0][state]": {
                    required: true
                },
                "data[Address][0][postcode]": {
                    maxlength: 20,
                    digits: true,
                    required: true
                },
                "data[Address][0][country_id]": {
                    digits: true,
                    required: true
                },
                "data[Address][1][street_address]": {
                    maxlength: 255,
                    required: true
                },
                "data[Address][1][suburb]": {
                    maxlength: 255,
                    required: true
                },
                "data[Address][1][state]": {
                    required: true
                },
                "data[Address][1][postcode]": {
                    maxlength: 20,
                    digits: true,
                    required: true
                },
                "data[Address][1][country_id]": {
                    digits: true,
                    required: true
                }
            }
        });
        {$switchAddEditTitle}
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>