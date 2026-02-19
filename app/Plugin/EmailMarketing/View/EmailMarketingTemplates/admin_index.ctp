<?php if(stristr($userGroupName, Configure::read('System.client.group.name')) === FALSE): ?>

    <?php
        $displayFields = array(
            'EmailMarketingTemplate.id'                 => array('ColumnName' => __('ID'),      'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
            'EmailMarketingTemplate.name'               => array('ColumnName' => __('Name'),    'Sortable' => true, 'Searchable' => true),
            'EmailMarketingTemplate.owner'              => array('ColumnName' => __('Owner'),   'Sortable' => true, 'Searchable' => true),
            'EmailMarketingTemplate.price'              => array('ColumnName' => __('Price'),   'Sortable' => true, 'Searchable' => true),
            'EmailMarketingTemplate.for_sale'           => array('ColumnName' => __('For Sale'),'Sortable' => true, 'Searchable' => true),
            'EmailMarketingTemplate.deleted'            => array('ColumnName' => __('Deleted'), 'Sortable' => true),
            'EmailMarketingTemplate.created'            => array('ColumnName' => __('Created'), 'Sortable' => true, 'Searchable' => true),
            'EmailMarketingTemplate.modified'           => array('ColumnName' => __('Modified'),'Sortable' => true, 'Searchable' => true),
        );
        
        $actions = array(
            'Add'           => array('/admin/email_marketing/email_marketing_templates/add/', null, null, array('class' => "purple", 'target' => '_blank')),
            'Edit'          => array('/admin/email_marketing/email_marketing_templates/edit/', 'EmailMarketingTemplate.id', null, array('class' => 'green', 'target' => '_blank')),
            'Delete'        => array('/admin/email_marketing/email_marketing_templates/delete/', 'EmailMarketingTemplate.id', null, array('class' => 'red popup-delete'))
        );
        
        echo $this->JqueryDataTable->createTable('EmailMarketingTemplate',
            $displayFields,
            "/admin/email_marketing/email_marketing_templates/index.json",
            $actions,
            __('No email marketing templates found'),
            $defaultSortDir,
            'email_marketing'
        );
    ?>
    
    <!-- page specific plugin scripts -->
    <?php
        echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => ''
        )); 
    ?>

<?php else: ?>
    <iframe src="/admin/email_marketing/email_marketing_templates/shoppingIndex/?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
    <iframe src="/admin/email_marketing/email_marketing_templates/purchasedIndex/?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
    <iframe src="/admin/email_marketing/email_marketing_templates/clientIndex/?iframe=1" frameborder="0" scrolling="no" seamless="seamless" width="100%" onload="initIframe(this);"></iframe>
    
    <!-- page specific plugin scripts -->
    <?php
        echo $this->element('page/admin/load_inline_js', array(
            'loadedScripts' => array('zoom.min.js'),
            'inlineJS' => ''
        )); 
    ?>
<?php endif; ?>