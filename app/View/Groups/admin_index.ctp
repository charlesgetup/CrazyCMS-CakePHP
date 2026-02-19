<?php
    $displayFields = array(
        'Group.id'           => array('ColumnName' => __('ID'),                  'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'Group.name'         => array('ColumnName' => __('Name'),                'Sortable' => true, 'Searchable' => true),
        'Group.modified'     => array('ColumnName' => __('Created/Modified'),    'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'      => array('/admin/groups/view/', 'Group.id', null, array('class' => 'blue popup-view')),
        'Edit'      => array('/admin/groups/edit/', 'Group.id', null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/groups/delete/', 'Group.id', null, array('class' => 'red popup-delete')),
    );
    
    echo $this->JqueryDataTable->createTable(
        'Group',
        $displayFields,
        "/admin/groups/index.json",
        $actions,
        __('No groups found'),
        $defaultSortDir
    );
?>