<?php echo $this->Form->create('EmailMarketing.EmailMarketingCampaign'); ?>
    <div class="row">
        <div class="col-xs-12 marketing-campaign-schedule">
            <?php if(empty($campaign['EmailMarketingCampaign']['scheduled_time'])): ?>
            
                <link rel="stylesheet" type="text/css" href="/css/admin/bootstrap-timepicker.css" />
                <link rel="stylesheet" type="text/css" href="/css/admin/bootstrap-datepicker3.css" />
            
                <label for="schedule-date-picker"><?php echo __("Schedule Time"); ?></label>
                
                <br />
                
                <div class="input-group">
                    <?php
                        echo $this->Form->input('schedule_date', array(
                            'label'         => false,
                            'class'         => 'required form-control date-picker',
                            'id'            => 'schedule-date-picker',
                            'div'           => false,
                            'default'       => date('Y-m-d'),
                            'tabindex'      => 1
                        ));
                    ?>
                    <span class="input-group-addon">
                        <i class="fa fa-calendar bigger-110"></i>
                    </span>
                </div>
                
                <div class="input-group bootstrap-timepicker">
                    <?php
                        echo $this->Form->input('schedule_time', array(
                            'label'         => false,
                            'class'         => 'required form-control',
                            'id'            => 'timepicker',
                            'div'           => false,
                            'tabindex'      => 2
                        ));
                    ?>
                    <span class="input-group-addon">
                        <i class="fa fa-clock-o bigger-110"></i>
                    </span>
                </div>
            
            <?php else: ?>
            
                <link rel="stylesheet" type="text/css" href="/css/admin/flipclock.css"" />
                
                <?php if(!empty($schedulePeriod)): ?>
                    <label><?php echo __("Campaign emails will be sent after"); ?></label>
                    
                    <br />
                
                    <div class="clock"></div>
                <?php else: ?>
                    <h3><?php echo __("Campaign emails have been sent."); ?></h3>
                <?php endif; ?>
                
                <?php
                    echo $this->Form->input('remove', array(
                        'label'         => false,
                        'class'         => 'hidden-field',
                        'div'           => false
                    ));
                ?>
            
            <?php endif; ?>
        </div>
    </div>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php

    if(empty($campaign['EmailMarketingCampaign']['scheduled_time'])){
        $inlineJS = <<<EOF
    
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                startDate: '0'
            })
            /* show datepicker when clicking on the icon */
            .next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
            
            $('#timepicker').timepicker({
                template: 'dropdown',
                minuteStep: 15,
                showSeconds: false,
                showMeridian: false,
                showInputs: false
            }).next().on(ace.click_event, function(){
                $(this).prev().focus();
            });
    
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'loadedScripts' => array('date-time/bootstrap-datepicker.js', 'date-time/bootstrap-timepicker.js'),
            'inlineJS' => $inlineJS
        ));
    
    }else{
        $inlineJS = <<<EOF
            if($('.clock').length){
                var clock = $('.clock').FlipClock({$schedulePeriod}, {
                    countdown: true
                });
            }
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'loadedScripts' => array('flipclock.min.js'),
            'inlineJS' => $inlineJS
        ));
    }

?>