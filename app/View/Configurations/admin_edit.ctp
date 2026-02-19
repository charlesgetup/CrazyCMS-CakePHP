<?php echo $this->Form->create('Configuration', array('class' => 'form-horizontal')); ?>
    <?php echo $this->Form->hidden('Configuration.id'); ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('Configuration.name', array(
                    'label'         => array('text' => __('Configuration Setting Name') .'&nbsp;<span class="mandatory">*</span>'),
                    'autocomplete'  => 'off',
                    'class'         => 'required col-xs-12 col-sm-12',
                    'maxlength'     => 255,
                    'div'           => false,
                    'tabindex'      => 1
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('Configuration.value', array(
                    'label'         => array('text' => __('Configuration Setting Value') .'&nbsp;<span class="mandatory">*</span>'),
                    'autocomplete'  => 'off',
                    'class'         => 'required col-xs-12 col-sm-12',
                    'maxlength'     => 255,
                    'div'           => false,
                    'tabindex'      => 2
                ));  
            ?>
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

<!-- page specific plugin scripts -->
<?php
    $actionName = Inflector::camelize($this->request->params['action']);
    $switchAddEditTitle = $this->element('page/admin/switch_add_edit_title');
    $inlineJS = <<<EOF
        $('#Configuration{$actionName}Form').validate({
            rules: {
                "data[Configuration][name]": {
                    maxlength: 255,
                    required: true
                },
                "data[Configuration][value]": {
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