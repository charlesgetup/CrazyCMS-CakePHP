<?php
    $displayFields = array(
        'EmailMarketingSubscriberOpenRecord.ip'                 => array('ColumnName' => __('IP'),              'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.is_mobile'          => array('ColumnName' => __('Mobile'),          'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.browser_name'       => array('ColumnName' => __('Browser Name'),    'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.browser_version'    => array('ColumnName' => __('Browser Version'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.platform_name'      => array('ColumnName' => __('Platform Name'),   'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.platform_vesion'    => array('ColumnName' => __('Platform Vesion'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.country'            => array('ColumnName' => __('Country'),         'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.region'             => array('ColumnName' => __('Region'),          'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.city'               => array('ColumnName' => __('City'),            'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberOpenRecord.timestamp'          => array('ColumnName' => __('Timestamp'),       'Sortable' => true, 'Searchable' => true)
    );
    $actions = array();
    echo $this->JqueryDataTable->createTable('EmailMarketingSubscriberOpenRecord',
        $displayFields,
        "/admin/email_marketing/email_marketing_statistics/getSubscriberOpenRecord/{$subscriberId}/{$statisticId}.json",
        $actions,
        __('No open campaign email details found'),
        $defaultSortDir,
        'email_marketing'
    );
?>