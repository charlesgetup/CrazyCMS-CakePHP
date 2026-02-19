<div>
    <?php
        $displayFields = array(
            'User.parent_id'    => array('ColumnName' => __('ID'),                  'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
            'User.name'         => array('ColumnName' => __('Name'),                'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
            'User.active'       => array('ColumnName' => __('Active'),              'Sortable' => true, 'Searchable' => false), // Only string type field can be searchable 
            'User.company'      => array('ColumnName' => __('Company'),             'Sortable' => true, 'Searchable' => true),
            'User.phone'        => array('ColumnName' => __('Phone'),               'Sortable' => true, 'Searchable' => true),
            'EmailMarketingUser.free_emails'            => array('ColumnName' => __('Free Emails (in total)'),          'Sortable' => true, 'Searchable' => true),
        );
        
        $actions = array(
            'ADD'       => array('/admin/email_marketing/email_marketing_users/add/' .$planId),
            'View'      => array('/admin/email_marketing/email_marketing_users/view/', 'EmailMarketingUser.id', null, array('class' => 'blue popup-view')),
            'Edit'      => array('/admin/email_marketing/email_marketing_users/edit/', array('EmailMarketingUser.id', 'EmailMarketingUser.email_marketing_plan_id'), null, array('class' => 'green popup-edit')),
            'Delete'    => array('/admin/email_marketing/email_marketing_users/delete/', 'EmailMarketingUser.id', null, array('class' => 'red popup-delete')),
        );

        echo $this->JqueryDataTable->createTable('EmailMarketingUser',
            $displayFields,
            "/admin/email_marketing/email_marketing_users/index/{$plan['EmailMarketingPlan']['id']}/{$this->request->params['action']}.json",
            $actions,
            __('No email marketing users found'),
            $defaultSortDir,
            'email_marketing'
        );
    ?>
</div>