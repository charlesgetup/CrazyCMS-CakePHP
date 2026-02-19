<?php
    $displayFields = array(
        'EmailMarketingEmailLink.url'               => array('ColumnName' => __('Link URL'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingEmailLink.clicked'           => array('ColumnName' => __('Clicked'),  'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View' => array('/admin/email_marketing/email_marketing_statistics/viewStatisticsByEmailLink/', 'EmailMarketingEmailLink.id', null, array('class' => 'pink popup-view')),
    );
    
    echo $this->JqueryDataTable->createTable('EmailMarketingEmailLink',
        $displayFields,
        "/admin/email_marketing/email_marketing_statistics/viewCampaignEmailLinksStatistics/{$statisticId}.json",
        $actions,
        __('No email marketing campaign statistics found'),
        $defaultSortDir,
        'email_marketing'
    );
?>