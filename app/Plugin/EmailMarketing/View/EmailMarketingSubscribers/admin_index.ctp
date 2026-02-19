<?php
    $displayFields = array(
        'EmailMarketingSubscriber.id'           => array('ColumnName' => __('ID'),          'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingSubscriber.name'         => array('ColumnName' => __('Name'),        'Sortable' => false, 'Searchable' => true, 'CombineFields' => array('EmailMarketingSubscriber.first_name', 'EmailMarketingSubscriber.last_name'), 'CombineGlue' => '" "'),
        'EmailMarketingSubscriber.email'        => array('ColumnName' => __('Email'),       'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriber.excluded'     => array('ColumnName' => __('Excluded'),     'Sortable' => true, 'Searchable' => true), // Only string type field can be searchable
    );
    
    $actions = array(
        'ADD'       => array('/admin/email_marketing/email_marketing_subscribers/add/' .$listId),
        'View'      => array('/admin/email_marketing/email_marketing_subscribers/view/', 'EmailMarketingSubscriber.id', null, array('class' => 'blue popup-view')),
        'Edit'      => array('/admin/email_marketing/email_marketing_subscribers/edit/', array('EmailMarketingSubscriber.id','EmailMarketingSubscriber.email_marketing_list_id'), null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/email_marketing/email_marketing_subscribers/delete/', 'EmailMarketingSubscriber.id', null, array('class' => 'red popup-delete')),
    );
    
    echo $this->JqueryDataTable->createTable('EmailMarketingSubscriber',
        $displayFields,
        "/admin/email_marketing/email_marketing_subscribers/index/{$listId}.json",
        $actions,
        __('No email marketing subscribers found'),
        $defaultSortDir,
        'email_marketing'
    );
?>