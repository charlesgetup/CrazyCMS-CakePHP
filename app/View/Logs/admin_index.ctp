<div class="row">
    <div class="col-xs-12">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-16">
                <?php if($this->Permissions->check($acl, 'Logs/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#user-log">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('User Log'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#web-development-log">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Web Development Log'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#email-marketing-log">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Email Marketing Log'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#live-chat-log">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Live Chat Log'); ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                    <li>
                        <a data-toggle="tab" href="#payment-log">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            <?php echo __('Payment Log'); ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <div class="tab-content">
                <?php if($this->Permissions->check($acl, 'Logs/admin_index')): ?>
                    <div id="user-log" class="tab-pane in active">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.user')));
                        ?>
                        <iframe id="iFrame0" src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_index')): ?>
                    <div id="web-development-log" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.webdevelopment')));
                        ?>
                        <iframe id="iFrame1" src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index')): ?>
                    <div id="email-marketing-log" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.emailmarketing')));
                        ?>
                        <iframe id="iFrame1" src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index')): ?>
                    <div id="live-chat-log" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.livechat')));
                        ?>
                        <iframe id="iFrame1" src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
                    </div>
                <?php endif; ?>
                <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                    <div id="payment-log" class="tab-pane">
                        <?php
                            $iframeUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.payment')));
                        ?>
                        <iframe id="iFrame2" src="<?php echo $iframeUrl; ?>?iframe=1" frameborder="0" width="100%" scrolling="no" seamless="seamless"></iframe>
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