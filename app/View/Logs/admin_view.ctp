<div class="class="col-xs-12">
    <div class="align-right">
        <span class="green middle bolder"><?php echo __('Choose display type'); ?>: &nbsp;</span>

        <div class="btn-toolbar inline middle no-margin">
            <div data-toggle="buttons" class="btn-group no-margin">
                <label class="btn btn-sm btn-yellow <?php echo ($displayType == "PREMIUM") ? "active" : ""; ?>" data-displayType="PREMIUM">
                    <span class="bigger-110"><?php echo __('Timeline premium'); ?></span>
                    <input type="radio" name="log_display" value="log-timeline-1" />
                </label>

                <label class="btn btn-sm btn-yellow <?php echo ($displayType == "SIMPLE") ? "active" : ""; ?>" data-displayType="SIMPLE">
                    <span class="bigger-110"><?php echo __('Timeline simple'); ?></span>
                    <input type="radio" name="log_display" value="log-timeline-2" />
                </label>
                <?php if(!$this->Permissions->isClient()): ?>
                    <label class="btn btn-sm btn-yellow <?php echo (empty($displayType) || $displayType == "TABLE") ? "active" : ""; ?>" data-displayType="TABLE">
                        <span class="bigger-110"><?php echo __('Log Table'); ?></span>
                        <input type="radio" name="log_display" value="log-table" />
                    </label>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <br class="log-spacer" />
    
    <?php
        
        // Timeline display general settings
        $dataCollection                 = $logs; 
        $displayTypeCheckerCssSelector  = '.btn-group .btn-yellow';
        $pageRedirectBaseUrl            = '/admin/dashboard#/admin/logs/view/' .strtoupper($type) .'/';
        
        $modelName                      = 'Log';
        $displayFields                  = array(
            'LEVEL'     => 'level',
            'TITLE'     => 'user_name',
            'TIMESTAMP' => 'timestamp',
            'CONTENT'   => 'message'
        );
        
        $timelineContainerCssSelector   = ($displayType == "PREMIUM") ? '#log-timeline-1' : '#log-timeline-2';
        
        $timelineLogs                   = $this->Timeline->createTimeline($modelName, $displayFields, $dataCollection, $timelineContainerCssSelector, $displayTypeCheckerCssSelector, $pageRedirectBaseUrl, $level, $startDate, $endDate, $displayType);
    ?>
    
    <div id="log-timeline-1" class="log-display hide">
    
        <?php
            if($displayType == "PREMIUM") { echo $timelineLogs; }
        ?>
    
    </div>

    <div id="log-timeline-2" class="log-display hide">
    
        <?php
            if($displayType == "SIMPLE") { echo $timelineLogs; }
        ?>
    
    </div>
    
    <div id="log-table" class="log-display hide">
    
        <?php
            $displayFields = array(
                'Log.id'           => array('ColumnName' => __('ID'),           'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                'Log.user_name'    => array('ColumnName' => __('User Name'),    'Searchable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                'Log.level'        => array('ColumnName' => __('Level'),        'Searchable' => true),
                'Log.message'      => array('ColumnName' => __('Message'),      'Searchable' => true),
                'Log.timestamp'    => array('ColumnName' => __('Timestamp'),    'Searchable' => true),
            );
            
            $actions = array();
            
            $hasSelectionBox = FALSE;
            
            echo $this->JqueryDataTable->createTable(
                'Log',
                $displayFields,
                "/admin/logs/view/{$type}.json",
                $actions,
                __('No logs found'),
                $defaultSortDir,
                null,
                $hasSelectionBox
            );
        ?>
    
    </div>
    
</div>

<!-- page specific plugin scripts -->
<?php 
    $inlineJS = <<<EOF
        if(window != top){
            $('.align-right').next('.log-spacer').css('display', 'none');
        }
        
        $('.align-right').children('.btn-toolbar').children('.btn-group').children('label').each(function(){
            if($(this).hasClass('active')){
                var id = $(this).children('input[name="log_display"]').val();
                $('#' + id).removeClass('hide');
            }
        });
        
        $('.align-right').children('.btn-toolbar').children('.btn-group').children('label').children('span').click(function(){
            var displayType = $(this).closest('label').attr('data-displayType');
            
            var url = window.location.pathname;
            var anchor = document.URL.split(url).last();
            var anchorElements = anchor.split("/");
            
            var reloadUrl = "{$pageRedirectBaseUrl}" + displayType + "/";
            
            var timelineLevelValue = '';
            if(anchorElements && anchorElements.length > 6){
                timelineLevelValue = anchorElements[6];
                reloadUrl += timelineLevelValue;
                if(anchorElements.length > 7){
                    reloadUrl += (anchorElements[anchorElements.length - 2] ? "/" + anchorElements[anchorElements.length - 2] : "") + (anchorElements[anchorElements.length - 1] ? "/" + anchorElements[anchorElements.length - 1] : "");
                } 
            }
            window.location.href = reloadUrl; 
            
            $('.align-right').children('.btn-toolbar').children('.btn-group').children('label').removeClass('active');    
            $(this).closest('label').addClass('active');
            $('.log-display').addClass('hide');
            $('#' + $(this).next('input[name="log_display"]').val()).removeClass('hide');
        });
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>