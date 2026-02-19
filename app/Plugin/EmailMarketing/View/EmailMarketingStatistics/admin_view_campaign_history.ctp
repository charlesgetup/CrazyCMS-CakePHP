<?php
    $displayFields = array(
        'EmailMarketingStatistic.id'                => array('ColumnName' => __('ID'),                  'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingStatistic.send_start'        => array('ColumnName' => __('Sent At'),             'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.send_end'          => array('ColumnName' => __('Sent Finished At'),    'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.total_time_used'   => array('ColumnName' => __('Total Time Used (Sec.)'), 'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.status'            => array('ColumnName' => __('Status'),              'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.invalid'           => array('ColumnName' => __('Invalid'),             'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.duplicated'        => array('ColumnName' => __('Duplicated'),          'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.blacklisted'       => array('ColumnName' => __('Blacklisted'),         'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.processed'         => array('ColumnName' => __('Processed'),           'Sortable' => true, 'Searchable' => true),
        'EmailMarketingStatistic.forwarded'         => array('ColumnName' => __('Forwarded'),           'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'                      => array('/admin/email_marketing/email_marketing_statistics/view/', 'EmailMarketingStatistic.id', null, array('class' => 'pink popup-view')),
        'Statistics-subscriber'     => array('/admin/email_marketing/email_marketing_statistics/viewCampaignSubscribersStatistics/', 'EmailMarketingStatistic.id', null, array('class' => 'pink popup-statistics subscriber-statistics')),
        'Statistics-email-click'    => array('/admin/email_marketing/email_marketing_statistics/viewCampaignEmailLinksStatistics/', 'EmailMarketingStatistic.id', null, array('class' => 'pink popup-statistics email-link-statistics')),
    );
    
    echo $this->JqueryDataTable->createTable('EmailMarketingStatistic',
        $displayFields,
        "/admin/email_marketing/email_marketing_statistics/viewCampaignHistory/{$campaignId}.json",
        $actions,
        __('No email marketing campaign statistics found'),
        $defaultSortDir,
        'email_marketing'
    );
?>