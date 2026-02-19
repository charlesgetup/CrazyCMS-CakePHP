<?php
    $displayFields = array(
        'User.id'           => array('ColumnName' => __('ID'),                  'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'User.parent_id'    => array('ColumnName' => __('Parent ID'),           'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'User.name'         => array('ColumnName' => __('Name'),                'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
        'User.active'       => array('ColumnName' => __('Active'),              'Sortable' => true, 'Searchable' => false), // Only string type field can be searchable 
        'User.company'      => array('ColumnName' => __('Company'),             'Sortable' => true, 'Searchable' => true),
        'User.phone'        => array('ColumnName' => __('Phone'),               'Sortable' => true, 'Searchable' => true),
        'Group.name'        => array('ColumnName' => __('Group'),               'Sortable' => true, 'Searchable' => true),
        'User.modified'     => array('ColumnName' => __('Created/Modified'),    'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'      => array('/admin/users/view/', 'User.id', null, array('class' => 'blue popup-view')),
    );
    if($this->Permissions->isAdmin()){
        $actions += array(
            'Edit'      => array('/admin/users/edit/', 'User.id', null, array('class' => 'green popup-edit')),
            'Delete'    => array('/admin/users/delete/', 'User.id', null, array('class' => 'red popup-delete')),
        );
    }
    
    echo $this->JqueryDataTable->createTable(
        'User',
        $displayFields,
        "/admin/users/index.json",
        $actions,
        __('No users found'),
        $defaultSortDir
    );
?>
