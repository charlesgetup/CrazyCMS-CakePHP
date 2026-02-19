<link href="/css/admin/jkanban.css" rel="stylesheet" title="kanban" />

<?php
    $editLinkSelector   = 'edit-link';
    $deleteLinkSelector = 'delete-link';
    $debug              = Configure::read('debug');
?>
<style>
    .<?php echo $editLinkSelector; ?>, .<?php echo $deleteLinkSelector; ?> {
        cursor: pointer;
    }
</style>

<div id="kanban"></div>

<?php

    $dragFunc = <<<FUNC
        function (el, source) {
            if({$debug}){
                console.log('START DRAG: ');
                console.log(el);
                console.log(source);
            }
        }
FUNC;
    $dragFuncStr = str_replace("\n", "", $dragFunc);

    $cannotUpdateStatusErr = __("Cannot save project status change.");
    $dragendFunc = <<<FUNC
        function (el) {
            var projectId = el.dataset.eid.split('_').pop();
            var projectStatus = $('div.kanban-item[data-eid='+el.dataset.eid+']').closest('.kanban-drag').prev('header.kanban-board-header').children('.kanban-title-board').text();
            $.ajax({
                url: '/admin/web_development/web_development_projects/updateStatus',
                type: 'POST',
                data: {'projectId': projectId, 'status': projectStatus},
                async: false
            }).done(function ( data ) {
                if(!data){
                    messageBox({'status': ERROR, 'message': '{$cannotUpdateStatusErr}'});
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                messageBox({'status': ERROR, 'message': '{$cannotUpdateStatusErr}'});
            }).always(function() { 
                
            });
        }
FUNC;
    $dragendFuncStr = str_replace("\n", "", $dragendFunc);

    $dropFunc = <<<FUNC
        function (el, source) {
            if({$debug}){
                console.log('DROP: ');
                console.log(el );
                console.log(source);
            }
        }
FUNC;
    $dropFuncStr = str_replace("\n", "", $dropFunc);
    
    $submitBtnTxt               = __("Submit");
    $confirmBtnTxt              = __("Confirmed");
    $cancelBtnTxt               = __("Cancel");
    $closeBtnTxt                = __("Close");
    $popupClickAddTitleTxt      = __("Add project");
    $popupClickEditTitleTxt     = __("Edit project");
    $popupClickViewTitleTxt     = __("View project");
    $popupClickDeleteTitleTxt   = __("Delete project");
    
    $clickAddFunc = <<<FUNC
        bootbox.dialog({
            message: '<iframe src="/admin/web_development/web_development_projects/add?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
            title: '{$popupClickAddTitleTxt}',
            buttons: {
                'Submit' : {
                    'label' : '{$submitBtnTxt}',
                    'className' : 'btn-sm btn-success submit-iframe-form-btn',
                    'callback' : function(event){
                        submitIframeForm(event);
                        return false;
                    }
                },
                'Cancel' : {
                    'label' : '{$cancelBtnTxt}',
                    'className' : 'btn-sm btn-sm'
                }
            }
        });
FUNC;
    $clickAddFuncStr = str_replace("\n", "", $clickAddFunc);
    
    $clickEditFunc = <<<FUNC
        function (el) {
            var projectId = el.dataset.eid.split('_').pop();
            bootbox.dialog({
                message: '<iframe src="/admin/web_development/web_development_projects/edit/'+projectId+'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                title: '{$popupClickEditTitleTxt}',
                buttons: {
                    'Submit' : {
                        'label' : '{$submitBtnTxt}',
                        'className' : 'btn-sm btn-success submit-iframe-form-btn',
                        'callback' : function(event){
                            submitIframeForm(event);
                            return false;
                        }
                    },
                    'Cancel' : {
                        'label' : '{$cancelBtnTxt}',
                        'className' : 'btn-sm btn-sm'
                    }
                }
            });
        }
FUNC;
    $clickEditFuncStr = str_replace("\n", "", $clickEditFunc);
    
    $clickDeleteFunc = <<<FUNC
        function (el) {
            var projectId = el.dataset.eid.split('_').pop();
            bootbox.dialog({
                message: '<iframe src="/admin/web_development/web_development_projects/delete/'+projectId+'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                title: '{$popupClickDeleteTitleTxt}',
                buttons: {
                    'Confirmed' : {
                        'label' : '{$confirmBtnTxt}',
                        'className' : 'btn-sm btn-success submit-iframe-form-btn',
                        'callback' : function(event){
                            submitIframeForm(event);
                            setTimeout(function(){
                                actuateLink($(event.target).closest(".modal-footer").siblings(".modal-header").children(".bootbox-close-button.close"));
                                window.location.reload();
                            }, 4000);
                            return false;
                        }
                    },
                    'Cancel' : {
                        'label' : '{$cancelBtnTxt}',
                        'className' : 'btn-sm btn-sm'
                    }
                }
            });
        }
FUNC;
    $clickDeleteFuncStr = str_replace("\n", "", $clickDeleteFunc);
    
    $clickViewFunc = <<<FUNC
        function (el) {
            var projectId = el.dataset.eid.split('_').pop();
            bootbox.dialog({
                message: '<iframe src="/admin/web_development/web_development_projects/view/'+projectId+'?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless" onload="initIframe(this);"></iframe>',
                title: '{$popupClickViewTitleTxt}',
                buttons: {
                    'Cancel' : {
                        'label' : '{$closeBtnTxt}',
                        'className' : 'btn-sm btn-sm'
                    }
                }
            });
        }
FUNC;
    $clickViewFuncStr = str_replace("\n", "", $clickViewFunc);

    $boards = array();
    
    if(!empty($projects) && is_array($projects)){
        
        foreach($projects as $project){
            if(!isset(${$project['WebDevelopmentProject']['status']})){
                ${$project['WebDevelopmentProject']['status']} = array($project);
            }else{
                array_push(${$project['WebDevelopmentProject']['status']}, $project);
            }
        }
        
    }
    
    $editTxt            = __('Edit');
    $deleteTxt          = __('Delete');
    $canEditProject     = $this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_edit') ? 'true' : 'false';
    foreach(Configure::read('WebDevelopment.project.status') as $status){
    
        $items = array();
        
        if(!empty(${$status})){
            foreach(${$status} as $project){
                $itemId = str_replace(" ", "_", $project['WebDevelopmentProject']['name']) .'_' .$project['WebDevelopmentProject']['id'];
                $item = array(
                    'id'            => $itemId,
                    'title'         => $project['WebDevelopmentProject']['name'],
                    'status'        => __(Inflector::humanize($project['WebDevelopmentProject']['status'])),
                    'click'         => '#CLICKVIEW#'
                );
                if($canEditProject == 'true'){
                    $item['drag']               = '#DRAG#';
                    $item['dragend']            = '#DRAGEND#';
                    $item['drop']               = '#DROP#';
                    $item['editLinkSelector']   = $editLinkSelector;
                    $item['deleteLinkSelector'] = $deleteLinkSelector;
                    $item['editTxt']            = $editTxt;
                    $item['deleteTxt']          = $deleteTxt;
                }
                array_push($items, $item);
            }
        }
        
        $board = array(
            'id' => "_" .strtolower($status),
            'title' => ucfirst($status),
            //'class' => 'info', // Used to define board background
            'item' => $items,
        );
        array_push($boards, $board);
    }

    $boardsJson = json_encode($boards); 
    $boardsJson = str_replace('"#DRAG#"',           $dragFuncStr,           $boardsJson);
    $boardsJson = str_replace('"#DRAGEND#"',        $dragendFuncStr,        $boardsJson);
    $boardsJson = str_replace('"#DROP#"',           $dropFuncStr,           $boardsJson);
    $boardsJson = str_replace('"#CLICKVIEW#"',      $clickViewFuncStr,      $boardsJson);

    $addNewProject = '';
    if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_add')){
        $addNewProject = <<<FEATURE
            buttonClick: function (el, boardId) {
                {$clickAddFuncStr}
            },
            addItemButton: true,
FEATURE;
    }
    
    $inlineJS = <<<EOF
        var projectKanban = new jKanban({
            element: '#kanban',
            gutter: '10px',
            widthBoard: '300px',
            click: function (el) {
                if($(el).hasClass('kanban-item')){
                    
                }
            },
            {$addNewProject}
            dragBoards: false,
            dragItems: {$canEditProject},
            boards: {$boardsJson}
        });
EOF;

    if($canEditProject){
        $inlineJS .= <<<EOF
            var projectKanbanEditFunc   = {$clickEditFuncStr};
            var projectKanbanDeleteFunc = {$clickDeleteFuncStr};
            $('.{$editLinkSelector}').on('click', function(event){
                var el = $(event.target).closest('.kanban-item').get(0);
                projectKanbanEditFunc(el);
            });
            $('.{$deleteLinkSelector}').on('click', function(event){
                var el = $(event.target).closest('.kanban-item').get(0);
                projectKanbanDeleteFunc(el);
            });
EOF;
    }
    
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('jkanban.js'),
        'inlineJS' => $inlineJS
    )); 

?>