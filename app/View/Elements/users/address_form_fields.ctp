<div class="space-10"></div>

<div class="row">

    <div class="col-xs-12 col-sm-6">
    
        <div class="row">
            <div class="col-xs-12">
                <h4 class="header blue bolder smaller"><?php echo __('Contact Address'); ?></h4>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->hidden('Address.0.id');
                    echo $this->Form->hidden('Address.0.user_id');
                    echo $this->Form->hidden('Address.0.type', array(
                        'value'         => 'CONTACT'
                    ));
                    echo $this->Form->checkbox('Address.0.same_as', array(
                        'value'         => '1',
                        'div'           => false,
                        'style'         => 'position:relative;float:right;margin-right:100px;',
                        'tabindex'      => 1
                    ));
                    echo '<label for="Address0SameAs" style="display:inline;float:right;width:200px;">Same As Billing Address</label>';
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.0.street_address', array(
                        'label'         => array('text' => __('Street Address') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 2
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.0.suburb', array(
                        'label'         => array('text' => __('Suburb/City') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 3
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.0.state', array(
                        'label'         => array('text' => __('State/Province') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 4
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.0.postcode',array(
                        'label'         => array('text' => __('Postcode/Zip') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 5
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.0.country_id', array(
                        'type'          => 'select',
                        'default'       => ((isset($this->request->data["User"]["Address"][0]["country_id"]) && !empty($this->request->data["User"]["Address"][0]["country_id"])) ? $this->request->data["User"]["Address"][0]["country_id"] : 13),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 6
                    ));
                ?>
            </div>
        </div>
    
    </div>
    
    <div class="col-xs-12 col-sm-6">
    
        <div class="row">
            <div class="col-xs-12">
                <h4 class="header blue bolder smaller"><?php echo __('Billing Address'); ?></h4>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->hidden('Address.1.id');
                    echo $this->Form->hidden('Address.1.user_id');
                    echo $this->Form->hidden('Address.1.type', array(
                        'value'         => 'BILLING'
                    ));
                    echo $this->Form->checkbox('Address.1.same_as', array(
                        'value'         => '1',
                        'div'           => false,
                        'style'         => 'position:relative;float:right;margin-right:100px;',
                        'tabindex'      => 7
                    ));
                    echo '<label for="Address1SameAs" style="display:inline;float:right;width:200px;">Same As Contact Address</label>';
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.1.street_address', array(
                        'label'         => array('text' => __('Street Address') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 8
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.1.suburb', array(
                        'label'         => array('text' => __('Suburb/City') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 9
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.1.state', array(
                        'label'         => array('text' => __('State/Province') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 10
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.1.postcode',array(
                        'label'         => array('text' => __('Postcode/Zip') .'&nbsp;<span class="mandatory">*</span>'),
                        'autocomplete'  => 'off',
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 11
                    ));
                ?>
            </div>
        </div>
        
        <div class="space"></div>
            
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('Address.1.country_id', array(
                        'type'          => 'select',
                        'default'       => ((isset($this->request->data["User"]["Address"][0]["country_id"]) && !empty($this->request->data["User"]["Address"][0]["country_id"])) ? $this->request->data["User"]["Address"][0]["country_id"] : 13),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 12
                    ));
                ?>
            </div>
        </div>
    
    </div>

</div>

<?php echo $this->element('users/address.js'); ?>