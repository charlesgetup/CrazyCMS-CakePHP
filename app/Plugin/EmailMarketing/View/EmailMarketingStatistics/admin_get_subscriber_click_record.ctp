<?php
    $displayFields = array(
        'EmailMarketingEmailLink.url'                   => array('ColumnName' => __('Link URL'),  'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberClickRecord.timestamp' => array('ColumnName' => __('Timestamp'), 'Sortable' => true, 'Searchable' => true)
    );
    $actions = array();
    echo $this->JqueryDataTable->createTable('EmailMarketingSubscriberClickRecord',
        $displayFields,
        "/admin/email_marketing/email_marketing_statistics/getSubscriberClickRecord/{$subscriberId}/{$statisticId}.json",
        $actions,
        __('No click campaign email details found'),
        $defaultSortDir,
        'email_marketing'
    );
?>