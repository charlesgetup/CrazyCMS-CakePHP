<?php

    $taskOrTicket = $this->TaskManagementUtil->taskOrTicket($webDevelopmentStageId);

?>
<?php echo $this->Form->create('TaskManagement.TaskManagementTask'); ?>
    <?php echo $this->Form->hidden('id'); ?>
   
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('name', array(
                    'label'         => array('text' => $taskOrTicket .' ' .__('Name') .'&nbsp;<span class="mandatory">*</span>'),
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
                    'label'         => array('text' => $taskOrTicket .' ' .__('Description') .'&nbsp;<span class="mandatory">*</span>'),
                    'class'         => 'col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 2
                ));
            ?>
        </div>
    </div>
    <?php if(!empty($webDevelopmentStageId)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('assignee', array(
                        'label'         => array('text' => __('Assigned To') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => $assignees,
                        'empty'         => __('Choose project type'),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 3
                    ));
                ?>
            </div>
        </div>
        <?php if(stristr($this->request->params['action'], "edit")): ?>
            <div class="row">
                <div class="col-xs-12">
                    <?php
                        echo $this->Form->input('status', array(
                            'label'         => array('text' => __('Task Status') .'&nbsp;<span class="mandatory">*</span>'),
                            'options'       => $taskStatus,
                            'empty'         => __('Choose task status'),
                            'class'         => 'col-xs-12 col-sm-11',
                            'div'           => false,
                            'tabindex'      => 4
                        ));
                    ?>
                </div>
            </div>
        <?php endif; ?>
    <?php elseif(!empty($departments)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('group_id', array(
                        'label'         => array('text' => __('Department') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => $departments,
                        'empty'         => __('Choose department'),
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 3
                    ));
                ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
                $isAdd = stristr($this->request->params['action'], "add"); 
                if($isAdd){
                
                    echo $this->Form->input('priority', array(
                        'type'  => 'hidden',
                        'value' => '5' // Set default priority when create ticket
                    ));
                    
                }else{
                    
                    $priorities = $this->TaskManagementUtil->generatePriorityList();
                    echo $this->Form->input('priority', array(
                        'label'         => array('text' => __('Priority') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => $priorities,
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'empty'         => __('Choose priority'),
                        'tabindex'      => 5
                    ));
                }
            ?>
        </div>
    </div>
    <?php if(!empty($webDevelopmentStageId)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('end_time', array(
                        'type'          => 'text',
                        'label'         => array('text' => __('Task End Time')),
                        'class'         => 'col-xs-12 col-sm-11',
                        'div'           => false,
                        'tabindex'      => 6
                    ));
                ?>
                <span class="input-group-addon datepicker-icon">
                    <i class="ace-icon fa fa-calendar"></i>
                </span>
            </div>
        </div>
    <?php endif; ?>
    
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    
    $taskSpecialValidations = '';
    if(!empty($webDevelopmentStageId)){
        $taskSpecialValidations = <<<TASK
              "data[TaskManagementTask][assignee]": {
                    required: true
              }, 
              "data[TaskManagementTask][end_time]": {
                    required: true
              }, 
TASK;
        if(stristr($this->request->params['action'], "edit")){
            $taskSpecialValidations .= <<<TASK
                  "data[TaskManagementTask][status]": {
                        required: true
                  },
TASK;
        }
    }

    $minDate = $this->Time->i18nFormat(time(), '%x %X');
    $inlineJS = <<<EOF
        $('form[id^="TaskManagementTask"][id$="Form"]').validate({
            rules: {
                "data[TaskManagementTask][name]": {
                    required: true
                },
                "data[TaskManagementTask][description]": {
                    required: true
                },
                {$taskSpecialValidations}
                "data[TaskManagementTask][priority]": {
                    required: true
                }
            }
        });
        
        $('#TaskManagementTaskEndTime').datetimepicker({
            minDate: '{$minDate}'
            /*'locale': 'au'*/
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>