<?php echo $this->Form->create('Group', array('class' => 'form-horizontal')); ?>
    <div class="row">
        <div class="col-xs-12">
        	<?php
        		echo $this->Form->hidden('id');
        		echo $this->Form->input('Group.name',array(
                    'label'         => array('text' => __('Group Name') .'&nbsp;<span class="mandatory">*</span>'),
                    'autocomplete'  => 'off',
                    'class'         => 'required col-xs-12 col-sm-12',
                    'maxlength'     => 255,
                    'div'           => false,
                    'tabindex'      => 1
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
        $('#Group{$actionName}Form').validate({
            rules: {
                "data[Group][name]": {
                    maxlength: 255,
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