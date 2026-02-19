<?php
    $displayFields = array(
        'EmailMarketingSubscriber.id'           => array('ColumnName' => __('ID'),     'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingSubscriber.name'         => array('ColumnName' => __('Name'),   'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriber.email'        => array('ColumnName' => __('Email'),  'Sortable' => true, 'Searchable' => true), // Only string type field can be searchable
        'EmailMarketingSubscriber.list_name'    => array('ColumnName' => __('List'),   'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriber.opened'       => array('ColumnName' => __('Opened'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriber.clicked'      => array('ColumnName' => __('Clicked'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriber.bounced'      => array('ColumnName' => __('Bounced'), 'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'      => array('/admin/email_marketing/email_marketing_statistics/viewStatisticsBySubscriber/', array('EmailMarketingSubscriber.id', 'EmailMarketingSubscriber.email_marketing_statistic_id'), null, array('class' => 'pink popup-view')),
    );

    echo $this->JqueryDataTable->createTable('EmailMarketingSubscriber',
        $displayFields,
        "/admin/email_marketing/email_marketing_statistics/viewCampaignSubscribersStatistics/{$emailMarketingStatisticId}.json",
        $actions,
        __('No email marketing subscribers found'),
        $defaultSortDir,
        'email_marketing'
    );
?>