<?php
    $isEdit = stristr($this->request->params['action'], "edit"); 
    
    if(!empty($this->request->data['WebDevelopmentStage']['start_time'])){
        $this->request->data['WebDevelopmentStage']['start_time'] = $this->Time->i18nFormat($this->request->data['WebDevelopmentStage']['start_time'], '%x %X');
    }
    if(!empty($this->request->data['WebDevelopmentStage']['end_time'])){
        $this->request->data['WebDevelopmentStage']['end_time'] = $this->Time->i18nFormat($this->request->data['WebDevelopmentStage']['end_time'], '%x %X');
    }
    
    echo $this->Form->create('WebDevelopment.WebDevelopmentStage'); 
?>
    <?php
        echo $this->Form->hidden('id');
    ?>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('name', array(
                    'label'         => array('text' => __('Project Stage Name') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 1
                ));
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('description', array(
                    'label'         => array('text' => __('Project Stage Description')),
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 2
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
                    'tabindex'      => 3
                ));
            ?>
        </div>
    </div>
    <?php if($isEdit): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('status', array(
                        'label'         => array('text' => __('Project Stage Status') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => $projectStageStatus,
                        'empty'         => __('Choose project stage status'),
                        'class'         => 'col-xs-12 col-sm-11',
                        'div'           => false,
                        'tabindex'      => 4
                    ));
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('start_time', array(
                    'type'          => 'text',
                    'label'         => array('text' => __('Project Stage Start Time') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'required col-xs-12 col-sm-11',
                    'div'           => false,
                    'tabindex'      => ($isEdit ? 5 : 4)
                ));
            ?>
            <span class="input-group-addon datepicker-icon">
                <i class="ace-icon fa fa-calendar"></i>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('end_time', array(
                    'type'          => 'text',
                    'label'         => array('text' => __('Project Stage End Time') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'required col-xs-12 col-sm-11',
                    'div'           => false,
                    'tabindex'      => ($isEdit ? 6 : 5)
                ));
            ?>
            <span class="input-group-addon datepicker-icon">
                <i class="ace-icon fa fa-calendar"></i>
            </span>
        </div>
    </div>
    
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php

    $minDate = $this->Time->i18nFormat(time(), '%x %X');

    $inlineJS = <<<EOF
        $('form[id^="WebDevelopmentStage"][id$="Form"]').validate({
            rules: {
                "data[WebDevelopmentStage][name]": {
                    required: true
                },
                "data[WebDevelopmentStage][priority]": {
                    required: true
                },
                "data[WebDevelopmentStage][start_time]": {
                    required: true
                },
                "data[WebDevelopmentStage][end_time]": {
                    required: true
                }
            }
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
        $('#WebDevelopmentStageStartTime').datetimepicker({
            minDate: '{$minDate}'
            /*'locale': 'au'*/
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
        
        $('#WebDevelopmentStageEndTime').datetimepicker({
            minDate: '{$minDate}'
            /*'locale': 'au'*/
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>