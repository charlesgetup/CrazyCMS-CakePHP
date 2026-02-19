<?php echo $this->Form->create('WebDevelopment.WebDevelopmentProject'); ?>
    <?php echo $this->Form->hidden('id'); ?>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('project_owner', array(
                    'label'         => array('text' => __('Project Owner') .'&nbsp;<span class="mandatory">*</span>'),
                    'options'       => $projectOwners,
                    'empty'         => __('Choose project owner'),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 1
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('name', array(
                    'label'         => array('text' => __('Project Name') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 2
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('description', array(
                    'label'         => array('text' => __('Project Description')),
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 3
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                $projectType = array();
                foreach(Configure::read('WebDevelopment.project.type') as $type){
                    $projectType[$type] = ucfirst($type);
                }
                echo $this->Form->input('type', array(
                    'label'         => array('text' => __('Project Type') .'&nbsp;<span class="mandatory">*</span>'),
                    'options'       => $projectType,
                    'empty'         => __('Choose project type'),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 4
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                $priorities = array();
                for($i = 1; $i < 10; $i++){
                    $priorities[$i] = $i;
                }
                echo $this->Form->input('priority', array(
                    'label'         => array('text' => __('Priority') .'&nbsp;<span class="mandatory">*</span>'),
                    'options'       => $priorities,
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'empty'         => __('Choose priority'),
                    'tabindex'      => 5
                ));
            ?>
        </div>
    </div>
    
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        $('form[id^="WebDevelopmentProject"][id$="Form"]').validate({
            rules: {
                "data[WebDevelopmentProject][name]": {
                    required: true
                },
                "data[WebDevelopmentProject][type]": {
                    required: true
                },
                "data[WebDevelopmentProject][project_owner]": {
                    required: true
                },
                "data[WebDevelopmentProject][priority]": {
                    required: true
                }
            }
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>