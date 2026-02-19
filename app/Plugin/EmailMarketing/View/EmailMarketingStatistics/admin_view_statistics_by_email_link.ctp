<div class="row">
    <div class="col-xs-12">
        <dl>
            <dt><?php echo __('Link URL'); ?></dt>
            <dd><?php echo $emailLink['EmailMarketingEmailLink']['url']; ?></dd>
        </dl>
    </div>
</div>
<div class="row hr hr-16 hr-dotted"></div>
<div class="row">
    <div class="col-xs-12">
        <h5><?php echo __("Click campaign email link details"); ?></h5>
    </div>
    <div class="col-xs-12">
        <?php
            $displayFields = array(
                'EmailMarketingSubscriberClickRecord.subscriber_name'     => array('ColumnName' => __('Subscriber Name'),   'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('EmailMarketingSubscriber.first_name', 'EmailMarketingSubscriber.last_name'), 'CombineGlue' => '" "'),
                'EmailMarketingSubscriberClickRecord.timestamp'           => array('ColumnName' => __('Timestamp'),         'Sortable' => true, 'Searchable' => true),
            );
            $actions = array();
            echo $this->JqueryDataTable->createTable('EmailMarketingSubscriberClickRecord',
                $displayFields,
                "/admin/email_marketing/email_marketing_statistics/viewStatisticsByEmailLink/{$emailLinkId}.json",
                $actions,
                __('No email link statistic details found'),
                $defaultSortDir,
                'email_marketing'
            );
        ?>
    </div>
</div>