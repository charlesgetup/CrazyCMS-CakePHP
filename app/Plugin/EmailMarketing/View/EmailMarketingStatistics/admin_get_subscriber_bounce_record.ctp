<?php
    $displayFields = array(
        'EmailMarketingSubscriberBounceRecord.bounce_type'      => array('ColumnName' => __('Bounce Type'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSubscriberBounceRecord.bounce_reason'    => array('ColumnName' => __('Bounce Reason'), 'Sortable' => false, 'Searchable' => true),
        'EmailMarketingSubscriberBounceRecord.timestamp'        => array('ColumnName' => __('Timestamp'), 'Sortable' => true, 'Searchable' => true)
    );
    $actions = array(
        'Delete' => array('/admin/email_marketing/email_marketing_subscribers/delete/', 'EmailMarketingSubscriberBounceRecord.id', null, array('class' => 'red popup-delete')),
    );
    echo $this->JqueryDataTable->createTable('EmailMarketingSubscriberBounceRecord',
        $displayFields,
        "/admin/email_marketing/email_marketing_statistics/getSubscriberBounceRecord/{$subscriberId}/{$statisticId}.json",
        $actions,
        __('No bounce campaign email details found'),
        $defaultSortDir,
        'email_marketing'
    );
?>