<?php
    $displayFields = array(
        'Configuration.id'                  => array('ColumnName' => __('ID'),          'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'Configuration.user_popup'          => array('ColumnName' => __('User ID'),     'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'Configuration.name'                => array('ColumnName' => __('Name'),        'Sortable' => true, 'Searchable' => true),
        'Configuration.value'               => array('ColumnName' => __('Value'),       'Sortable' => true, 'Searchable' => true),
        'Configuration.modified'            => array('ColumnName' => __('Modified'),    'Sortable' => true, 'Searchable' => true),
        'Configuration.modified_by_name'    => array('ColumnName' => __('Modified By'), 'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'ADD'       => array('/admin/configurations/add/' .$configurationType, null),
        'Edit'      => array('/admin/configurations/edit/' .$configurationType .'/', 'Configuration.id', null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/configurations/delete/' .$configurationType .'/', 'Configuration.id', null, array('class' => 'red popup-delete')),
    );
    
    echo $this->JqueryDataTable->createTable(
        'Configuration',
        $displayFields,
        "/admin/configurations/view/{$configurationType}.json",
        $actions,
        __('No configurations found'),
        $defaultSortDir
    );
?>