<?php
    $displayFields = array(
        'EmailMarketingSender.id'                       => array('ColumnName' => __('ID'),                  'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'EmailMarketingSender.sender_domain'            => array('ColumnName' => __('Sender Domain'),       'Sortable' => true, 'Searchable' => true),
        'EmailMarketingSender.public_key_download_link' => array('ColumnName' => __('Download Public Key'), 'Searchable' => false), // Only string type field can be searchable
    );
    
    $actions = array(
        'View'      => array('/admin/email_marketing/email_marketing_senders/view/', 'EmailMarketingSender.id', null, array('class' => 'blue popup-view')),
        'Edit'      => array('/admin/email_marketing/email_marketing_senders/edit/', 'EmailMarketingSender.id', null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/email_marketing/email_marketing_senders/delete/', 'EmailMarketingSender.id', null, array('class' => 'red popup-delete')),
    );

    echo $this->JqueryDataTable->createTable('EmailMarketingSender',
        $displayFields,
        "/admin/email_marketing/email_marketing_senders/index.json",
        $actions,
        __('No email marketing senders found'),
        $defaultSortDir,
        'email_marketing'
    );
?>
<div class="notice-area">
    <h3><?php echo __("Notice"); ?>:</h3>
    <ul>
        <li>
            <?php echo __('Please replace all white spaces inside double quotes in the downloaded file while creating DNS record.'); ?>
        </li>
        <li>
            <?php 
                echo __('We strongly suggest that you add SPF record in DNS.') .'<br /><i>TXT @ "v=spf1 a mx ptr include:mail.crazysoft.com.au include:_spf.google.com ~all"</i>'; 
            ?>
        </li>
        <li>
            <?php 
                echo __('We also suggest that you add DMARC record in DNS, too.') .'<br /><i>TXT _dmarc "v=DMARC1; p=none; rua=mailto:reports@your-domain.com"</i><br /><i>(Please replace reports@your-domain.com with your own email address to receive aggregate report.)</i>'; 
            ?>
        </li>
    </ul>
</div>