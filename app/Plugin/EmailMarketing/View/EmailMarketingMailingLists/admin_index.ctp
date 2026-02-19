<?php
    $displayFields = array(
        'EmailMarketingMailingList.id'                      => array('ColumnName' => __('ID'),              'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingMailingList.user_name'               => array('ColumnName' => __('User Name'),       'Sortable' => false, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
        'EmailMarketingMailingList.name'                    => array('ColumnName' => __('List Name'),       'Sortable' => true, 'Searchable' => true), 
        'EmailMarketingMailingList.description'             => array('ColumnName' => __('Description'),     'Sortable' => true, 'Searchable' => true),
        'EmailMarketingMailingList.active'                  => array('ColumnName' => __('Active'),          'Sortable' => true, 'Searchable' => false), // Only string type field can be searchable
        'EmailMarketingMailingList.deleted'                 => array('ColumnName' => __('Deleted'),         'RestrictToGroups' => array(Configure::read('System.admin.group.name'))), // Only string type field can be searchable
        'EmailMarketingMailingList.total_subscribers_amount'=> array('ColumnName' => __('Total Subscribers Amount'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingMailingList.modified'                => array('ColumnName' => __('Created/Modified'), 'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'      => array('/admin/email_marketing/email_marketing_mailing_lists/view/', 'EmailMarketingMailingList.id', null, array('class' => 'blue popup-view')),
        'Edit'      => array('/admin/email_marketing/email_marketing_mailing_lists/edit/', 'EmailMarketingMailingList.id', null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/email_marketing/email_marketing_mailing_lists/delete/', 'EmailMarketingMailingList.id', null, array('class' => 'red popup-delete')),
    );

    echo $this->JqueryDataTable->createTable('EmailMarketingMailingList',
        $displayFields,
        "/admin/email_marketing/email_marketing_mailing_lists/index.json",
        $actions,
        __('No email marketing mailing lists found'),
        $defaultSortDir,
        'email_marketing'
    );
?>