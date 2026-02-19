<?php
    $displayFields = array(
        'LiveChatPlan.id'                 => array('ColumnName' => __('ID'),                    'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'LiveChatPlan.name'               => array('ColumnName' => __('Name'),                  'Sortable' => true, 'Searchable' => true),
        'LiveChatPlan.price'              => array('ColumnName' => __('Price per month'),       'Sortable' => true, 'Searchable' => true),
        'LiveChatPlan.total_users_amount' => array('ColumnName' => __('Total Users Amount'),    'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'      => array('/admin/live_chat/live_chat_plans/view/', 'LiveChatPlan.id', null, array('class' => 'blue popup-view')),
        'Edit'      => array('/admin/live_chat/live_chat_plans/edit/', 'LiveChatPlan.id', null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/live_chat/live_chat_plans/delete/', 'LiveChatPlan.id', null, array('class' => 'red popup-delete')),
    );
    
    echo $this->JqueryDataTable->createTable(
        'LiveChatPlan',
        $displayFields,
        "/admin/live_chat/live_chat_plans/index.json",
        $actions,
        __('No live chat plans found'),
        $defaultSortDir,
        'live_chat'
    );
?>