<?php
    if(stristr($userGroupName, Configure::read('System.client.group.name')) === FALSE){
    
        $displayFields = array(
            'User.id'                                                   => array('ColumnName' => __('User ID'),                     'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
            'User.first_name'                                           => array('ColumnName' => __('First Name'),                  'Sortable' => true, 'Searchable' => true),
            'User.last_name'                                            => array('ColumnName' => __('Last Name'),                   'Sortable' => true, 'Searchable' => true),
            'User.active'                                               => array('ColumnName' => __('User Status'),                 'Sortable' => true, 'Searchable' => false),
            'PaymentPayer.payment_method'                               => array('ColumnName' => __('Payment Method'),              'Sortable' => true, 'Searchable' => true),
            'PaymentRecurringAgreement.name'                            => array('ColumnName' => __('Paid Plan'),                   'Sortable' => true, 'Searchable' => true),
            'PaymentRecurringAgreement.recurring_agreement_id'          => array('ColumnName' => __('Recurring Agreement ID'),      'Sortable' => true, 'Searchable' => true),
            'PaymentRecurringAgreement.start_time'                      => array('ColumnName' => __('Recurring Start Time'),        'Sortable' => true, 'Searchable' => true),
            'PaymentRecurringAgreement.active'                          => array('ColumnName' => __('Recurring Payment Status'),    'Sortable' => true, 'Searchable' => false),
        );
        
        $actions = array(
            'View'      => array('/admin/payment/payment_recurring_agreements/view/', 'PaymentRecurringAgreement.recurring_agreement_id', null, array('class' => 'blue popup-view'))
        );
        
        echo $this->JqueryDataTable->createTable(
            'PaymentRecurringAgreement',
            $displayFields,
            "/admin/payment/payment_recurring_agreements/index.json",
            $actions,
            __('No recurring payment agreement found'),
            $defaultSortDir,
            'payment'
        );
    }
?>