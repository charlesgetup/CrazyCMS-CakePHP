<?php
    $displayFields = array(
        'User.id'                                          => array('ColumnName' => __('User ID'),     'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingBlacklistedSubscriber.user_name'    => array('ColumnName' => __('User Name'),   'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "', 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingBlacklistedSubscriber.email'        => array('ColumnName' => __('Email'),       'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'ADD'       => array('/admin/email_marketing/email_marketing_blacklisted_subscribers/add/'),
        'Import'    => array('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/', null, null, array('class' => 'pink popup-import')),
        'Delete'    => array('/admin/email_marketing/email_marketing_blacklisted_subscribers/delete/', 'EmailMarketingBlacklistedSubscriber.id', null, array('class' => 'red popup-delete')),
    );
    
    echo $this->JqueryDataTable->createTable('EmailMarketingBlacklistedSubscriber',
        $displayFields,
        "/admin/email_marketing/email_marketing_blacklisted_subscribers/index.json",
        $actions,
        __('No email marketing blacklisted subscribers found'),
        $defaultSortDir,
        'email_marketing'
    );
?>