<div>
    <?php
        $displayFields = array(
            'User.parent_id'    => array('ColumnName' => __('ID'),                  'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
            'User.name'         => array('ColumnName' => __('Name'),                'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
            'User.active'       => array('ColumnName' => __('Active'),              'Sortable' => true, 'Searchable' => false), // Only string type field can be searchable 
            'User.company'      => array('ColumnName' => __('Company'),             'Sortable' => true, 'Searchable' => true),
            'User.phone'        => array('ColumnName' => __('Phone'),               'Sortable' => true, 'Searchable' => true),
            'LiveChatUser.operator_amount' => array('ColumnName' => __('Operator Amount'), 'Sortable' => true, 'Searchable' => true),
        );
        
        $actions = array(
            'ADD'       => array('/admin/live_chat/live_chat_users/add/' .$planId),
            'View'      => array('/admin/live_chat/live_chat_users/view/', 'LiveChatUser.id', null, array('class' => 'blue popup-view')),
            'Edit'      => array('/admin/live_chat/live_chat_users/edit/', array('LiveChatUser.id', 'LiveChatUser.live_chat_plan_id'), null, array('class' => 'green popup-edit')),
            'Delete'    => array('/admin/live_chat/live_chat_users/delete/', 'LiveChatUser.id', null, array('class' => 'red popup-delete')),
        );

        echo $this->JqueryDataTable->createTable('LiveChatUser',
            $displayFields,
            "/admin/live_chat/live_chat_users/index/{$plan['LiveChatPlan']['id']}/{$this->request->params['action']}.json",
            $actions,
            __('No live chat users found'),
            $defaultSortDir,
            'live_chat'
        );
    ?>
</div>