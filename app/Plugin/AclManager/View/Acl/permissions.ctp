<div class="form">
    <div class="paging">
        <?php echo $this->Paginator->first('first', array('class' => 'sortable')); ?>
        <?php echo $this->Paginator->prev( __('previous'), array('class' => 'sortable'), null, array('class'=>'disabled'));?>
        <?php echo $this->Paginator->numbers(array('class' => 'sortable', 'separator' => ' '));?>
        <?php echo $this->Paginator->next(__('next'), array('class' => 'sortable'), null, array('class' => 'disabled'));?>
        <?php echo $this->Paginator->last('last', array('class' => 'sortable')); ?>
    </div>
    <br />
    <?php echo $this->Form->create('Perms'); ?>
        <div class="clearfix">
            <div class="pull-right tableTools-container"></div>
        </div>
        <div>
            <table id="permission-data-table" class="table table-striped table-bordered table-hover permission-data-table">
                <thead>
                    <tr>
                        <th><?php echo __("Action"); ?></th>
                        <?php foreach ($aros as $aro): ?>
                            <?php $aro = array_shift($aro); ?>
                            <th><?php echo h($aro[$aroDisplayField]); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $lastIdent = null;
                        foreach ($acos as $id => $aco) {
                            
                            echo '<tr>';
                        
                            $action = $aco['Action'];
                            $alias = $aco['Aco']['alias'];
                            $ident = substr_count($action, '/');
                            
                            // Set first column of a row, and this is the action name
                            $actionCell = (($ident == 1 || $alias == "controllers") ? "<strong>" : "" ) . h((($alias == "controllers") ? "All Permissions" : $alias)) . (($ident == 1 || $alias == "controllers") ? "</strong>" : "" );
                            echo '<td>' .$actionCell .'</td>';
                            
                            // Set the rest columns of the row. These are the permissions for each group
                            foreach ($aros as $aro){
                                $inherit = $this->Form->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}-inherit");
                                $allowed = $this->Form->value("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}");
                                $value = $inherit ? 'inherit' : ($allowed ? 'allow' : 'deny'); 
                                $icon = $this->Html->image(($allowed ? 'valid.png' : 'error.png'));
                                $permissionCell = $this->Form->select("Perms." . str_replace("/", ":", $action) . ".{$aroAlias}:{$aro[$aroAlias]['id']}", array(array('inherit' => __('Inherit'), 'allow' => __('Allow'), 'deny' => __('Deny'))), array('empty' => __('No change'), 'value' => $value, 'class' => 'dropdown_list_2 permission no-selectmenu', 'style' => 'margin-bottom:0px;')) . "&nbsp;&nbsp;" .$icon;
                                echo '<td>' .$permissionCell .'</td>';
                            }
                            
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php echo $this->Form->end(); ?>
    <br />
    <div class="paging">
    	<?php echo $this->Paginator->first('first', array('class' => 'sortable')); ?>
        <?php echo $this->Paginator->prev( __('previous'), array('class' => 'sortable'), null, array('class'=>'disabled'));?>
        <?php echo $this->Paginator->numbers(array('class' => 'sortable', 'separator' => ' '));?>
        <?php echo $this->Paginator->next(__('next'), array('class' => 'sortable'), null, array('class' => 'disabled'));?>
        <?php echo $this->Paginator->last('last', array('class' => 'sortable')); ?>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    $tableToolsSettings = $this->JqueryDataTable->getTableToolsSettings('permissionDataTable');
    $inlineJS = <<<EOF
    
        var dataColumnAmount = $('.permission-table thead tr th').length - 1;
        var dataColumnDefTargetsArr = [];
        for(var i = 1; i <= dataColumnAmount; i++){
            dataColumnDefTargetsArr.push(i);
        }
        var permissionDataTable = $('#permission-data-table').DataTable({
            "bAutoWidth": false,
            "bStateSave": false,
            "bRegex": true,
            "bSort": false,
            "iDisplayLength": -1,
            "sDom": '<"row"<"col-xs-6"l><"col-xs-6"f>r>t<"row"<"col-xs-6 left"i>>',
            "columnDefs": [
                {"orderable": false, "searchable": true, "bVisible": true, "targets": 0},
                {"orderable": false, "searchable": false, "className": "center", "targets": dataColumnDefTargetsArr}
            ]
        });
        {$tableToolsSettings}
        $.fn.dataTableExt.sErrMode = 'throw';
        
        $('select.permission').change(function(event){
        
            var permName = event.target.name;
            var permVal = event.target.value;
            var changedPerm = permName + "=" + permVal;
            
            var trigger = this;
            $.ajax({
                url: $('form#PermsPermissionsForm').attr("action"),
                type: "POST",
                data: changedPerm, /*$('form#PermsPermissionsForm').serialize(), // Instead of send the whole form to controller, we only send changed perm. */
                beforeSend: function ( xhr ) {
                    $.gritter.removeAll();
                    if($.fn.ace_ajax){
                        $('[data-ajax-content=true]').ace_ajax('startLoading');
                    }
                }
            }).done(function ( data ) {
                if(data != undefined && data != null && data != ""){
                    data = jQuery.parseJSON(data);
                    messageBox(data);
                    switch($(trigger).val().toUpperCase()){
                        case INHERIT:
                            var permName = trigger.name;
                            var permRegexp = /data\[(.+)\]\[controllers:(.+):(.+)\]\[(.+)\]/g;
                            var permSections = permRegexp.exec(permName);
                            if(permSections.length == 5){
                                var permIdentifier = permSections[1];
                                var permController = permSections[2];
                                var permControllerView = permSections[3];
                                var permControllerEntityId = permSections[4];
                                var topPermName = 'data['+permIdentifier+'][controllers:'+permController+']['+permControllerEntityId+']';
                                if($('select[name="'+topPermName+'"]').length == 1){
                                   var parentImgSrc = $('select[name="'+topPermName+'"]').next('img').attr('src');
                                   $(trigger).next('img').attr('src', parentImgSrc);
                                }
                            }
                            break;
                        case ALLOW:
                            $(trigger).next('img').attr('src', '/img/valid.png');
                            break;
                        case DENY:
                            $(trigger).next('img').attr('src', '/img/error.png');
                            break;
                    }
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                ajaxErrorHandler(jqXHR, textStatus, errorThrown);
            }).always(function() {
                if($.fn.ace_ajax){
                    $('[data-ajax-content=true]').ace_ajax('stopLoading', true);
                }
                window.location.reload(); /* reload to get new CSRF token */
            });
        });
EOF;

    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    ));
?>