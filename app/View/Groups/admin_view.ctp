<div class="row">
    <?php if($userGroupName === Configure::read('System.admin.group.name')): ?>
        <div class="col-xs-12">
            <div class="col-xs-6">
                <?php echo __('Id'); ?>
            </div>
            <div class="col-xs-6">
                <?php echo h($group['Group']['id']); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Name'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($group['Group']['name']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Created'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($group['Group']['created']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Modified'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($group['Group']['modified']); ?>
        </div>
    </div>
</div>
<div class="space"></div>
<?php
    $displayFields = array(
        'User.id'           => array('ColumnName' => __('ID'),                  'Sortable' => true, 'RestrictToGroups' => array(Configure::read('System.admin.group.name'))),
        'User.name'         => array('ColumnName' => __('Name'),                'Sortable' => true, 'Searchable' => true, 'CombineFields' => array('User.first_name', 'User.last_name'), 'CombineGlue' => '" "'),
        'User.active'       => array('ColumnName' => __('Active'),              'Sortable' => true, 'Searchable' => false), // Only string type field can be searchable 
        'User.company'      => array('ColumnName' => __('Company'),             'Sortable' => true, 'Searchable' => true),
        'User.phone'        => array('ColumnName' => __('Phone'),               'Sortable' => true, 'Searchable' => true),
        'User.modified'     => array('ColumnName' => __('Created/Modified'),    'Sortable' => true, 'Searchable' => true),
    );
    
    $actions = array(
        'View'      => array('/admin/users/view/', 'User.id', null, array('class' => 'blue popup-view')),
        'Edit'      => array('/admin/users/edit/', 'User.id', null, array('class' => 'green popup-edit')),
        'Delete'    => array('/admin/users/delete/', 'User.id', null, array('class' => 'red popup-delete')),
    );
    
    echo $this->JqueryDataTable->createTable(
        'User',
        $displayFields,
        "/admin/users/listGroupUsers/{$group['Group']['id']}.json",
        $actions,
        __('No users found'),
        $defaultSortDir
    );
?>