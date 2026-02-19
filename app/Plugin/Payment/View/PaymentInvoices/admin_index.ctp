<div class="row">
    <div class="col-xs-12">
    
        <?php
            $unpaidInvoiceType = $actionUnpaid;
            if($invoiceType == $unpaidInvoiceType){
                $displayFields = array(
                    'PaymentInvoice.id'                 => array('ColumnName' => __('ID'),         'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                    'PaymentInvoice.user_name'          => array('ColumnName' => __('Client'),     'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
                    'PaymentInvoice.number'             => array('ColumnName' => __('Number'),     'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.amount'             => array('ColumnName' => __('Amount'),     'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.paid_amount'        => array('ColumnName' => __('Paid Amount'),'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.status'             => array('ColumnName' => __('Status'),     'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.due_date'           => array('ColumnName' => __('Due Date'),   'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.is_emailed_client'  => array('ColumnName' => __('Emailed'),    'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.created_user_name'  => array('ColumnName' => __('Created By'), 'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('AdminCreatedUser.first_name', 'AdminCreatedUser.last_name'), 'CombineGlue' => '" "', 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                    'PaymentInvoice.modified_user_name' => array('ColumnName' => __('Modified By'),'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('AdminModifiedUser.first_name', 'AdminModifiedUser.last_name'), 'CombineGlue' => '" "', 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                );
                
                $actions = array(
                    'View'      => array('/admin/payment/payment_invoices/view/', 'PaymentInvoice.id', null, array('class' => 'blue popup-view')),
                    'Edit'      => array('/admin/payment/payment_invoices/edit/', 'PaymentInvoice.id', null, array('class' => 'green popup-edit')),
                    //'Delete'    => array('/admin/payment/payment_invoices/delete/', 'PaymentInvoice.id', null, array('class' => 'red popup-delete')),
                    'Pay'       => array('/admin/payment/payment_pay_pal_gateway/expressCheckout/', 'PaymentInvoice.id', null, array('class' => 'orange popup-pay')),
                    'Email'     => array('/admin/payment/payment_invoices/email/', 'PaymentInvoice.id', null, array('class' => 'pink popup-email')),
                );
                
                echo $this->JqueryDataTable->createTable(
                    'PaymentInvoice',
                    $displayFields,
                    "/admin/payment/payment_invoices/index/{$unpaidInvoiceType}.json",
                    $actions,
                    __('No payment invoice found'),
                    $defaultSortDir,
                    "payment"
                );
            }
            
            $paidInvoiceType = $actionPaid;
            if($invoiceType == $paidInvoiceType){
                $displayFields = array(
                    'PaymentInvoice.id'                 => array('ColumnName' => __('ID'),         'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                    'PaymentInvoice.user_name'          => array('ColumnName' => __('Client'),     'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
                    'PaymentInvoice.number'             => array('ColumnName' => __('Number'),     'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.amount'             => array('ColumnName' => __('Amount'),     'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.paid_amount'        => array('ColumnName' => __('Paid Amount'),'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.status'             => array('ColumnName' => __('Status'),     'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.due_date'           => array('ColumnName' => __('Due Date'),   'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.is_emailed_client'  => array('ColumnName' => __('Emailed'),    'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.created_user_name'  => array('ColumnName' => __('Created By'), 'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('AdminCreatedUser.first_name', 'AdminCreatedUser.last_name'), 'CombineGlue' => '" "', 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                    'PaymentInvoice.modified_user_name' => array('ColumnName' => __('Modified By'),'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('AdminModifiedUser.first_name', 'AdminModifiedUser.last_name'), 'CombineGlue' => '" "', 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                );
                
                $actions = array(
                    'View'      => array('/admin/payment/payment_invoices/view/', 'PaymentInvoice.id', null, array('class' => 'blue popup-view')),
                    'Email'     => array('/admin/payment/payment_invoices/email/', 'PaymentInvoice.id', null, array('class' => 'purple popup-email')),
                    'Refund'    => array('/admin/payment/payment_pay_pal_gateway/refund/', 'PaymentInvoice.id', null, array('class' => 'red popup-refund')),
                );
                
                echo $this->JqueryDataTable->createTable(
                    'PaymentInvoice',
                    $displayFields,
                    "/admin/payment/payment_invoices/index/{$paidInvoiceType}.json",
                    $actions,
                    __('No payment invoice found'),
                    $defaultSortDir,
                    "payment"
                );
            }
            
            $refundInvoiceType = $actionRefund;
            if($invoiceType == $refundInvoiceType){
                $displayFields = array(
                    'PaymentInvoice.id'                 => array('ColumnName' => __('ID'),              'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                    'PaymentInvoice.user_name'          => array('ColumnName' => __('Client'),          'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
                    'PaymentInvoice.number'             => array('ColumnName' => __('Number'),          'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.amount'             => array('ColumnName' => __('Amount'),          'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.paid_amount'        => array('ColumnName' => __('Paid Amount'),     'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.paid_amount'        => array('ColumnName' => __('Refund Amount'),   'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.status'             => array('ColumnName' => __('Status'),          'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.is_emailed_client'  => array('ColumnName' => __('Emailed'),         'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.modified'           => array('ColumnName' => __('Refund Timestamp'),'Sortable' => true, 'Searchable' => true),
                    'PaymentInvoice.modified_user_name' => array('ColumnName' => __('Modified By'),     'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('AdminModifiedUser.first_name', 'AdminModifiedUser.last_name'), 'CombineGlue' => '" "', 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
                );
                
                $actions = array(
                    'View'      => array('/admin/payment/payment_invoices/view/', 'PaymentInvoice.id', null, array('class' => 'blue popup-view')),
                    'Email'     => array('/admin/payment/payment_invoices/email/', 'PaymentInvoice.id', null, array('class' => 'purple popup-email')),
                );
                
                echo $this->JqueryDataTable->createTable(
                    'PaymentInvoice',
                    $displayFields,
                    "/admin/payment/payment_invoices/index/{$refundInvoiceType}.json",
                    $actions,
                    __('No payment invoice found'),
                    $defaultSortDir,
                    "payment"
                );
            }
        ?>
        
    </div>
</div>
<!-- page specific plugin scripts -->
<?php
    $inlineJS = '';
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>