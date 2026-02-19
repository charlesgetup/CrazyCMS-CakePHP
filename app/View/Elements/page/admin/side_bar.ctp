<div id="sidebar" class="sidebar responsive">
    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
    </script>
    
    <?php
        $featureLink1 = $this->HTML->url(array('plugin' => 'web_development',   'controller' => 'web_development_dashboard',    'action' => 'index', 'prefix' => 'admin'));
        $featureLink2 = $this->HTML->url(array('plugin' => 'email_marketing',   'controller' => 'email_marketing_dashboard',    'action' => 'index', 'prefix' => 'admin')); 
        $featureLink3 = $this->HTML->url(array('plugin' => 'payment',           'controller' => 'payment_dashboard',            'action' => 'index', 'prefix' => 'admin'));
        $featureLink4 = $this->HTML->url(array('plugin' => 'task_management',   'controller' => 'task_management_tasks',        'action' => 'index', 'prefix' => 'admin', 0, 1)); 
    ?>
    
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentDashboard/admin_index')): ?>
                <button class="btn btn-success" onclick='window.location.href="/admin/dashboard#<?php echo $featureLink1; ?>";'>
                    <!-- Web development -->
                    <i class="ace-icon fa fa-image"></i>
                </button>
            <?php endif; ?>
            
            <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingDashboard/admin_index')): ?>
                <button class="btn btn-info" onclick='window.location.href="/admin/dashboard#<?php echo $featureLink2; ?>";'>
                    <!-- Email Marketing -->
                    <i class="ace-icon fa fa-envelope-o"></i>
                </button>
            <?php endif; ?>
    
            <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                <button class="btn btn-warning" onclick='window.location.href="/admin/dashboard#<?php echo $featureLink3; ?>";'>
                    <!-- Payment -->
                    <i class="ace-icon fa fa-credit-card"></i>
                </button>
            <?php endif; ?>
    
            <button class="btn btn-danger" onclick='window.location.href="/admin/dashboard#<?php echo $featureLink4; ?>";'>
                <!-- Tickets -->
                <i class="ace-icon fa fa-edit"></i>
            </button>
        </div>
    
        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentDashboard/admin_index')): ?>
                <a href="/admin/dashboard#<?php echo $featureLink1; ?>">
                    <span class="btn btn-success"></span>
                </a>
            <?php endif; ?>
    
            <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingDashboard/admin_index')): ?>
                <a href="/admin/dashboard#<?php echo $featureLink2; ?>">
                    <span class="btn btn-info"></span>
                </a>
            <?php endif; ?>
    
            <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                <a href="/admin/dashboard#<?php echo $featureLink3; ?>">
                    <span class="btn btn-warning"></span>
                </a>
            <?php endif; ?>
    
            <a href="/admin/dashboard#<?php echo $featureLink4; ?>">
                <span class="btn btn-danger"></span>
            </a>
        </div>
    </div><!-- /.sidebar-shortcuts -->
    
    <ul class="nav nav-list">
    
        <?php if($this->Permissions->check($acl, 'Dashboard/admin_index')): ?>
            <li class="">
                <?php 
                    $linkUrl = $this->HTML->url(array('controller' => 'dashboard', 'action' => 'view', 'prefix' => 'admin'));
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-tachometer')) 
                        .$this->Html->tag('span', __('Dashboard'), array('class' => 'menu-text')),
                        '#' .$linkUrl,
                        array(
                            'data-url' => $linkUrl,
                            'escapeTitle' => false
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
            </li>
        <?php endif; ?>
        
        <?php if($this->Permissions->check($acl, 'Users/admin_index')): ?>
            <li class="">
                <?php 
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-group')) 
                        .$this->Html->tag('span', __('Users Summary'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
        
                <ul class="submenu">
                    <li class="">
                        <?php 
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Users')
                                .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                                "#",
                                array(
                                    'escapeTitle' => false,
                                    'class' => 'dropdown-toggle'
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                        
                        <ul class="submenu">
                            <li class="">
                                <?php 
                                    $linkUrl = $this->HTML->url(array('controller' => 'users', 'action' => 'index', 'prefix' => 'admin'));
                                    echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-user blue')) 
                                        .__('List User'),
                                        '#' .$linkUrl,
                                        array(
                                            'data-url' => $linkUrl,
                                            'escapeTitle' => false
                                        )
                                    ); 
                                ?>
        
                                <b class="arrow"></b>
                            </li>
                            
                            <?php if($this->Permissions->check($acl, 'Users/admin_add')): ?>
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('controller' => 'users', 'action' => 'add', 'prefix' => 'admin'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-user blue')) 
                                            .__('Add User'),
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false
                                            )
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                            <?php endif; ?>
                            
                        </ul>
                    </li>
        
                    <?php if($this->Permissions->check($acl, 'Groups/admin_index')): ?>
                        <li class="">
                            <?php 
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Groups')
                                    .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                                    '#',
                                    array(
                                        'escapeTitle' => false,
                                        'class' => 'dropdown-toggle'
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                            
                            <ul class="submenu">
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('controller' => 'groups', 'action' => 'index', 'prefix' => 'admin'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-group blue')) 
                                            .__('List Group'),
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false
                                            )
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('controller' => 'groups', 'action' => 'add', 'prefix' => 'admin'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-group blue')) 
                                            .__('Add Group'),
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false
                                            )
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Acl/permissions')): ?>
                        <li class="">
                            <?php 
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Group Permissions')
                                    .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                                    '#',
                                    array(
                                        'escapeTitle' => false,
                                        'class' => 'dropdown-toggle'
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                            
                            <ul class="submenu">
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('plugin' => 'acl_manager', 'controller' => 'acl', 'action' => 'permissions', 'prefix' => 'admin', 'aro:Group'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-user blue')) .__('List Permission'), 
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false
                                            )
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('plugin' => 'acl_manager', 'controller' => 'acl', 'action' => 'update_acos', 'prefix' => 'admin'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-user blue')) .__('Update ACOs'), 
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false
                                            )
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('plugin' => 'acl_manager', 'controller' => 'acl', 'action' => 'update_aros', 'prefix' => 'admin'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-user blue'))  .__('Update AROs'), 
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false
                                            )
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('plugin' => 'acl_manager', 'controller' => 'acl', 'action' => 'drop', 'prefix' => 'admin'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-user blue'))  .__('Drop All ACOs/AROs'), 
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false,
                                                'style' => 'color:red;'
                                            ), 
                                            __("Do you want to drop all ACOs and AROs?")
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                                <li class="">
                                    <?php 
                                        $linkUrl = $this->HTML->url(array('plugin' => 'acl_manager', 'controller' => 'acl', 'action' => 'drop_perms', 'prefix' => 'admin'));
                                        echo $this->Html->link(
                                            $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-user blue'))  .__('Drop All Permissions'), 
                                            '#' .$linkUrl,
                                            array(
                                                'data-url' => $linkUrl,
                                                'escapeTitle' => false,
                                                'style' => 'color:red;'
                                            ), 
                                            __("Do you want to drop all the permissions?")
                                        ); 
                                    ?>
            
                                    <b class="arrow"></b>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </li>
        <?php endif; ?>
        
        <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentDashboard/admin_index')): ?>
            <li class="">
                <?php 
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-image')) 
                        .$this->Html->tag('span', __('Web Development'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
                
                <ul class="submenu">
                
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('plugin' => 'web_development', 'controller' => 'web_development_dashboard', 'action' => 'index', 'prefix' => 'admin'));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Introduction'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                    
                    <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_index')): ?>
                    
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'web_development', 'controller' => 'web_development_projects', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Projects'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Configurations/admin_view')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.webdevelopment')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </li>
        <?php endif; ?>
        
        <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingDashboard/admin_index')): ?>
            <li class="">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-envelope-o')) 
                        .$this->Html->tag('span', __('Email Marketing'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
                
                <ul class="submenu">
                
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('plugin' => 'email_marketing', 'controller' => 'email_marketing_dashboard', 'action' => 'index', 'prefix' => 'admin'));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Introduction'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                    
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index')): ?>
                    
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'email_marketing', 'controller' => 'email_marketing_plans', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Service Plans'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                        
                    <?php endif; ?>
                    
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingSenders/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'email_marketing', 'controller' => 'email_marketing_senders', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Manage Senders'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingMailingLists/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'email_marketing', 'controller' => 'email_marketing_mailing_lists', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Manage Mailing Lists'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingBlacklistedSubscribers/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'email_marketing', 'controller' => 'email_marketing_blacklisted_subscribers', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Manage Blacklist'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingCampaigns/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'email_marketing', 'controller' => 'email_marketing_campaigns', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Manage Campaigns'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingTemplates/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'email_marketing', 'controller' => 'email_marketing_templates', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Manage Templates'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Configurations/admin_view')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.emailmarketing')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </li>
        <?php endif; ?>
        
        <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatDashboard/admin_index')): ?>
            <li class="">
                <?php 
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-comments-o')) 
                        .$this->Html->tag('span', __('Live Chat'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
                
                <ul class="submenu">
                
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('plugin' => 'live_chat', 'controller' => 'live_chat_dashboard', 'action' => 'index', 'prefix' => 'admin'));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Introduction'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                    
                    <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index')): ?>
                    
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'live_chat', 'controller' => 'live_chat_plans', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Service Plans'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Configurations/admin_view')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.livechat')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </li>
                
        <?php endif; ?>
        
        <?php if(false): ?>
            <li class="">
                <?php 
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-search')) 
                        .$this->Html->tag('span', __('SEO'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
            </li>
            
            <li class="">
                <?php 
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-link')) 
                        .$this->Html->tag('span', __('Social Media'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
            </li>
        <?php endif; ?>
        
        <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
            <li class="">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-credit-card')) 
                        .$this->Html->tag('span', __('Payment'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
                
                <ul class="submenu">
        
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('plugin' => 'payment', 'controller' => 'payment_dashboard', 'action' => 'index', 'prefix' => 'admin'));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Payment Summary'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                    
                    <li class="">
                        <?php
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Manage Invoices')
                                .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                                "#",
                                array(
                                    'escapeTitle' => false,
                                    'class' => 'dropdown-toggle'
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                        
                        <ul class="submenu">
                            <li class="">
                                <?php 
                                    $linkUrl = $this->HTML->url(array('plugin' => 'payment', 'controller' => 'payment_invoices', 'action' => 'unpaidInvoiceIndex', 'prefix' => 'admin'));
                                    echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-file-text-o blue')) 
                                        .__('Unpaid Invoices'),
                                        '#' .$linkUrl,
                                        array(
                                            'data-url' => $linkUrl,
                                            'escapeTitle' => false
                                        )
                                    ); 
                                ?>
        
                                <b class="arrow"></b>
                            </li>
                            <li class="">
                                <?php 
                                    $linkUrl = $this->HTML->url(array('plugin' => 'payment', 'controller' => 'payment_invoices', 'action' => 'paidInvoiceIndex', 'prefix' => 'admin'));
                                    echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-file-text blue')) 
                                        .__('Paid Invoices'),
                                        '#' .$linkUrl,
                                        array(
                                            'data-url' => $linkUrl,
                                            'escapeTitle' => false
                                        )
                                    ); 
                                ?>
        
                                <b class="arrow"></b>
                            </li>
                            <?php if($this->Permissions->showRefundSection()): ?>
                            <li class="">
                                <?php 
                                    $linkUrl = $this->HTML->url(array('plugin' => 'payment', 'controller' => 'payment_invoices', 'action' => 'refundInvoiceIndex', 'prefix' => 'admin'));
                                    echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-file-o blue')) 
                                        .__('Refunded Invoices'),
                                        '#' .$linkUrl,
                                        array(
                                            'data-url' => $linkUrl,
                                            'escapeTitle' => false
                                        )
                                    ); 
                                ?>
        
                                <b class="arrow"></b>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('plugin' => 'payment', 'controller' => 'payment_dashboard', 'action' => 'manageRecurringPayments', 'prefix' => 'admin'));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Manage Recurring Payments'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                    
                    <?php if($this->Permissions->check($acl, 'Configurations/admin_view')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.payment')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </li>
        <?php endif; ?>
        
        <?php if($this->Permissions->check($acl, 'Logs/admin_index')): ?>
            <li class="">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-edit')) 
                        .$this->Html->tag('span', __('Logs'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
                
                <ul class="submenu">
                    <?php if($userGroupName === Configure::read('System.admin.group.name')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Show All Logs'),  
                                    '#'. $linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.user')));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('User Log'),  
                                '#'. $linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                    
                    <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.webdevelopment')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Web Development Log'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.emailmarketing')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Email Marketing Log'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.livechat')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Live Chat Log'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'TaskManagement/TaskManagementTasks/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.taskmanagement')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .($this->Permissions->isClient() ? __('Ticket Log') : __('Task Log')),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'logs', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.payment')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Payment Log'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </li>
        <?php endif; ?>
        
        <?php if($this->Permissions->check($acl, 'TaskManagement/TaskManagementTasks/admin_index')): ?>
            <li class="">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-ticket')) 
                        .$this->Html->tag('span', __('Tickets'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
                
                <ul class="submenu">
                
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('plugin' => 'task_management', 'controller' => 'task_management_tasks', 'action' => 'index', 'prefix' => 'admin', 0, 1));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('My Tickets'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                
                    <?php if(!$this->Permissions->isClient()): ?>
                    
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('plugin' => 'task_management', 'controller' => 'task_management_tasks', 'action' => 'index', 'prefix' => 'admin'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Client Tickets'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Configurations/admin_view')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.taskmanagement')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                
                </ul>
            </li>
        <?php endif; ?>
        
        <?php if($this->Permissions->check($acl, 'Configurations/admin_index') && $userGroupName === Configure::read('System.admin.group.name')): ?>
            <li class="">
                <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-cogs')) 
                        .$this->Html->tag('span', __('Configurations'), array('class' => 'menu-text'))
                        .$this->Html->tag('b', '', array('class' => 'arrow fa fa-angle-down')), 
                        '#',
                        array(
                            'escapeTitle' => false,
                            'class' => 'dropdown-toggle'
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
                
                <ul class="submenu">
                
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'index', 'prefix' => 'admin'));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('Show All Configurations'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                    <li class="">
                        <?php 
                            $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.system')));
                            echo $this->Html->link(
                                $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                .__('System Configurations'),  
                                '#' .$linkUrl,
                                array(
                                    'data-url' => $linkUrl,
                                    'escapeTitle' => false
                                )
                            ); 
                        ?>
        
                        <b class="arrow"></b>
                    </li>
                
                    <?php if($this->Permissions->check($acl, 'WebDevelopment/WebDevelopmentProjects/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.webdevelopment')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Web Development Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                
                    <?php if($this->EmailMarketingPermissions->check($acl, 'EmailMarketing/EmailMarketingPlans/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.emailmarketing')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Email Marketing Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'LiveChat/LiveChatPlans/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.livechat')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Live Chat Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'TaskManagement/TaskManagementTasks/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.taskmanagement')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Task Management Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Payment/PaymentDashboard/admin_index')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'configurations', 'action' => 'view', 'prefix' => 'admin', Configure::read('Config.type.payment')));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Payment Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                    <?php if($this->Permissions->check($acl, 'Webmaster/WebmasterConfigurations/admin_robots')): ?>
                        <li class="">
                            <?php 
                                $linkUrl = $this->HTML->url(array('controller' => 'webmaster_configurations', 'action' => 'robots', 'prefix' => 'admin', plugin => 'webmaster'));
                                echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-caret-right')) 
                                    .__('Robots.txt Configurations'),  
                                    '#' .$linkUrl,
                                    array(
                                        'data-url' => $linkUrl,
                                        'escapeTitle' => false
                                    )
                                ); 
                            ?>
            
                            <b class="arrow"></b>
                        </li>
                    <?php endif; ?>
                    
                </ul>
            </li>
        <?php endif; ?>

        <?php if($this->Permissions->check($acl, 'Dashboard/admin_faq')): ?>
            <li class="">
                <?php 
                    $linkUrl = $this->HTML->url(array('controller' => 'dashboard', 'action' => 'faq', 'prefix' => 'admin'));
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'menu-icon fa fa-question-circle')) 
                        .$this->Html->tag('span', __('FAQ'), array('class' => 'menu-text')),
                        '#' .$linkUrl,
                        array(
                            'data-url' => $linkUrl,
                            'escapeTitle' => false
                        )
                    ); 
                ?>
        
                <b class="arrow"></b>
            </li>
        <?php endif; ?>

    </ul>
    
    <!-- #section:basics/sidebar.layout.minimize -->
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>

    <!-- /section:basics/sidebar.layout.minimize -->
    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
    </script>
</div>