<div class="row">
    <div class="col-xs-12">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-16">
                <?php if($userGroupName === Configure::read('System.admin.group.name')): ?>
                    <li>
                        <a data-toggle="tab" href="#system-configurations">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('System Configurations'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#web-development-configurations">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Web Development Configurations'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#email-marketing-configurations">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Email Marketing Configurations'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#live-chat-configurations">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Live Chat Configurations'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'TaskManagement/TaskManagementTasks/admin_index')): ?>
                     <li>
                        <a data-toggle="tab" href="#task-management-configurations">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Task Management Configurations'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#payment-configurations">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Payment Configurations'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <div class="tab-content">
                <?php if($userGroupName === Configure::read('System.admin.group.name')): ?>
                    <div id="system-configurations" class="tab-pane in active">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.system')));
                        ?>
                        <iframe src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_index')): ?>
                    <div id="web-development-configurations" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.webdevelopment')));
                        ?>
                        <iframe src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index')): ?>
                    <div id="email-marketing-configurations" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.emailmarketing')));
                        ?>
                        <iframe src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index')): ?>
                    <div id="web-development-configurations" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.livechat')));
                        ?>
                        <iframe src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'TaskManagement/TaskManagementTasks/admin_index')): ?>
                     <div id="task-management-configurations" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.taskmanagement')));
                        ?>
                        <iframe src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                    <div id="payment-configurations" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.payment')));
                        ?>
                        <iframe src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php 
    $inlineJS = <<<EOF
        $('ul.nav-tabs li:first a[data-toggle="tab"]').tab('show');
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>