<?php
    $isClient = $this->Permissions->isClient();
?>
<div id="navbar" class="navbar navbar-default">

    <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>

    <div class="navbar-container" id="navbar-container">
    
        <!-- #section:basics/sidebar.mobile.toggle -->
        <button type="button" class="navbar-toggle menu-toggler pull-left display" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only"><?php echo __("Toggle sidebar"); ?></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <!-- /section:basics/sidebar.mobile.toggle -->
        <div class="navbar-header pull-left">
            <!-- #section:basics/navbar.layout.brand -->
            <a href="/admin/dashboard#/admin/dashboard/view" class="navbar-brand">
                <small>
                    <img src="<?php echo $companyLogo; ?>" alt="<?php echo $companyName; ?>" class="logo" />
                </small>
            </a>

            <!-- /section:basics/navbar.layout.brand -->

            <!-- #section:basics/navbar.toggle -->

            <!-- /section:basics/navbar.toggle -->
        </div>

        <!-- #section:basics/navbar.dropdown -->
        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                
                <li class="grey">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa <?php echo $isClient ? 'fa-ticket' : 'fa-tasks'; ?>"></i>
                        <span class="badge badge-grey">
                            <?php  
                                if($isClient){
                                    $topFiveTaskCount = empty($topFiveTaskCount) ? 0 : $topFiveTaskCount;
                                }else{
                                    $ticketTaskType = Configure::read('TaskManagement.type.ticket');
                                    $webdevTaskType = Configure::read('TaskManagement.type.webdev');
                                    $topFiveTaskCount = (empty($topFiveTaskCount[$ticketTaskType]) ? 0 : $topFiveTaskCount[$ticketTaskType]) + (empty($topFiveTaskCount[$webdevTaskType]) ? 0 : $topFiveTaskCount[$webdevTaskType]);
                                }
                                echo $topFiveTaskCount;
                            ?>
                        </span>
                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <?php if(empty($topFiveTaskCount)): ?>
                            <?php if($isClient): ?>
                                <li class="dropdown-footer">
                                    <a href="/admin/dashboard#/admin/task_management/task_management_tasks/index/0/1">
                                        <?php echo __('Create ticket now'); ?>
                                        <i class="ace-icon fa fa-arrow-right"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="dropdown-header">
                                    <i class="ace-icon fa fa-trophy"></i>
                                    <?php echo __('Great! All jobs are done.'); ?>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                        
                            <?php if($isClient): ?>
                            
                                <li class="dropdown-header">
                                    <i class="ace-icon fa fa-check"></i>
                                    <?php echo $topFiveTaskCount .' ' .__('Tickets to review'); ?>
                                </li>
                                <?php foreach($topFiveTasks as $task): ?>
                                    
                                    <li>
                                        <a href="#">
                                            <div class="clearfix">
                                                <span class="pull-left"><?php echo $task['TaskManagementTask']['name']; ?></span>
                                                <span class="pull-right"><?php echo $task['TaskManagementTask']['progress']; ?>%</span>
                                            </div>
            
                                            <div class="progress progress-mini">
                                                <div style="width:<?php echo $task['TaskManagementTask']['progress']; ?>%; background-color:<?php echo $this->Util->randomColor(true, 0.7); ?>;" class="progress-bar"></div>
                                            </div>
                                        </a>
                                    </li>
                                    
                                <?php endforeach; ?>
                                <li class="dropdown-footer">
                                    <a href="/admin/dashboard#/admin/task_management/task_management_tasks/index/0/1">
                                        <?php echo __('See all tickets with details'); ?>
                                        <i class="ace-icon fa fa-arrow-right"></i>
                                    </a>
                                </li>
                                    
                            <?php else: ?>
                            
                                <?php if(!empty($topFiveTasks[$ticketTaskType])): ?>
                                    <li class="dropdown-header">
                                        <i class="ace-icon fa fa-check"></i>
                                        <?php echo $topFiveTaskCount[$ticketTaskType] .' ' .__('Tickets to review'); ?>
                                    </li>
                                    <?php foreach($topFiveTasks[$ticketTaskType] as $task): ?>
                                        
                                        <li>
                                            <a href="#">
                                                <div class="clearfix">
                                                    <span class="pull-left"><?php echo $task['TaskManagementTask']['name']; ?></span>
                                                    <span class="pull-right"><?php echo $task['TaskManagementTask']['progress']; ?>%</span>
                                                </div>
                
                                                <div class="progress progress-mini">
                                                    <div style="width:<?php echo $task['TaskManagementTask']['progress']; ?>%; background-color:<?php echo $this->Util->randomColor(true, 0.7); ?>;" class="progress-bar"></div>
                                                </div>
                                            </a>
                                        </li>
                                        
                                    <?php endforeach; ?>
                                    <li class="dropdown-footer">
                                        <a href="/admin/dashboard#/admin/task_management/task_management_tasks">
                                            <?php echo __('See all tickets with details'); ?>
                                            <i class="ace-icon fa fa-arrow-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if(!empty($topFiveTasks[$webdevTaskType])): ?>
                                    <li class="dropdown-header">
                                        <i class="ace-icon fa fa-check"></i>
                                        <?php echo $topFiveTaskCount[$webdevTaskType] .' ' .__('Tasks to complete'); ?>
                                    </li>
                                    <?php foreach($topFiveTasks[$webdevTaskType] as $task): ?>
                                        
                                        <li>
                                            <a href="#">
                                                <div class="clearfix">
                                                    <span class="pull-left"><?php echo $task['TaskManagementTask']['name']; ?></span>
                                                    <span class="pull-right"><?php echo $task['TaskManagementTask']['progress']; ?>%</span>
                                                </div>
                
                                                <div class="progress progress-mini">
                                                    <div style="width:<?php echo $task['TaskManagementTask']['progress']; ?>%; background-color:<?php echo $this->Util->randomColor(true, 0.7); ?>;" class="progress-bar"></div>
                                                </div>
                                            </a>
                                        </li>
                                        
                                    <?php endforeach; ?>
                                    <li class="dropdown-footer">
                                        <a href="/admin/dashboard#/admin/web_development/web_development_projects">
                                            <?php echo __('See all tasks with details'); ?>
                                            <i class="ace-icon fa fa-arrow-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            
                            <?php endif; ?>
                            
                        <?php endif; ?>
                    </ul>
                </li>

                <?php if(false): ?>

                    <li class="purple">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                            <span class="badge badge-important">8</span>
                        </a>
    
                        <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                            <li class="dropdown-header">
                                <i class="ace-icon fa fa-exclamation-triangle"></i>
                                8 <?php echo __("Notifications"); ?>
                            </li>
    
                            <li>
                                <a href="#">
                                    <div class="clearfix">
                                        <span class="pull-left">
                                            <i class="btn btn-xs no-hover btn-pink fa fa-comment"></i>
                                            New Comments
                                        </span>
                                        <span class="pull-right badge badge-info">+12</span>
                                    </div>
                                </a>
                            </li>
    
                            <li>
                                <a href="#">
                                    <i class="btn btn-xs btn-primary fa fa-user"></i>
                                    Bob just signed up as an editor ...
                                </a>
                            </li>
    
                            <li>
                                <a href="#">
                                    <div class="clearfix">
                                        <span class="pull-left">
                                            <i class="btn btn-xs no-hover btn-success fa fa-shopping-cart"></i>
                                            New Orders
                                        </span>
                                        <span class="pull-right badge badge-success">+8</span>
                                    </div>
                                </a>
                            </li>
    
                            <li>
                                <a href="#">
                                    <div class="clearfix">
                                        <span class="pull-left">
                                            <i class="btn btn-xs no-hover btn-info fa fa-twitter"></i>
                                            Followers
                                        </span>
                                        <span class="pull-right badge badge-info">+11</span>
                                    </div>
                                </a>
                            </li>
    
                            <li class="dropdown-footer">
                                <a href="#">
                                    See all notifications
                                    <i class="ace-icon fa fa-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
    
                    <li class="green">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
                            <span class="badge badge-success">5</span>
                        </a>
    
                        <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                            <li class="dropdown-header">
                                <i class="ace-icon fa fa-envelope-o"></i>
                                5 <?php echo __("Messages"); ?>
                            </li>
    
                            <li class="dropdown-content">
                                <ul class="dropdown-menu dropdown-navbar">
                                    <li>
                                        <a href="#">
                                            <img src="/img/admin/avatars/avatar.png" class="msg-photo" alt="Alex's Avatar" />
                                            <span class="msg-body">
                                                <span class="msg-title">
                                                    <span class="blue">Alex:</span>
                                                    Ciao sociis natoque penatibus et auctor ...
                                                </span>
    
                                                <span class="msg-time">
                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                    <span>a moment ago</span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
    
                                    <li>
                                        <a href="#">
                                            <img src="/img/admin/avatars/avatar3.png" class="msg-photo" alt="Susan's Avatar" />
                                            <span class="msg-body">
                                                <span class="msg-title">
                                                    <span class="blue">Susan:</span>
                                                    Vestibulum id ligula porta felis euismod ...
                                                </span>
    
                                                <span class="msg-time">
                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                    <span>20 minutes ago</span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
    
                                    <li>
                                        <a href="#">
                                            <img src="/img/admin/avatars/avatar4.png" class="msg-photo" alt="Bob's Avatar" />
                                            <span class="msg-body">
                                                <span class="msg-title">
                                                    <span class="blue">Bob:</span>
                                                    Nullam quis risus eget urna mollis ornare ...
                                                </span>
    
                                                <span class="msg-time">
                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                    <span>3:15 pm</span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
    
                                    <li>
                                        <a href="#">
                                            <img src="/img/admin/avatars/avatar2.png" class="msg-photo" alt="Kate's Avatar" />
                                            <span class="msg-body">
                                                <span class="msg-title">
                                                    <span class="blue">Kate:</span>
                                                    Ciao sociis natoque eget urna mollis ornare ...
                                                </span>
    
                                                <span class="msg-time">
                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                    <span>1:33 pm</span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
    
                                    <li>
                                        <a href="#">
                                            <img src="/img/admin/avatars/avatar5.png" class="msg-photo" alt="Fred's Avatar" />
                                            <span class="msg-body">
                                                <span class="msg-title">
                                                    <span class="blue">Fred:</span>
                                                    Vestibulum id penatibus et auctor  ...
                                                </span>
    
                                                <span class="msg-time">
                                                    <i class="ace-icon fa fa-clock-o"></i>
                                                    <span>10:09 am</span>
                                                </span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
    
                            <li class="dropdown-footer">
                                <a href="inbox.html">
                                    See all messages
                                    <i class="ace-icon fa fa-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </li>

                <?php endif; ?>

                <!-- #section:basics/navbar.user_menu -->
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="/img/admin/avatars/user.jpg" alt="Jason's Photo" style="visibility: hidden; width: 4px;" />
                        <span class="user-info">
                            <small><?php echo __("Welcome,"); ?></small>
                            <?php 
                                $userFirstName = $this->Session->read('Auth.User.first_name');
                                echo empty($userFirstName) ? __("Client") : $userFirstName; 
                            ?>
                        </span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        
                        <?php if(false): ?>
                            <li>
                                <a href="#">
                                    <i class="ace-icon fa fa-cog"></i>
                                    Settings
                                </a>
                            </li>
                        <?php endif; ?>

                        <li>
                            <a href="<?php echo DS ."admin" .DS ."users" .DS ."profile" .DS .$this->Session->read('Auth.User.id') ; ?>">
                                <i class="ace-icon fa fa-user"></i>
                                <?php echo __("Profile"); ?>
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="<?php echo DS ."logout"; ?>">
                                <i class="ace-icon fa fa-power-off"></i>
                                <?php echo __("Logout"); ?>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- /section:basics/navbar.user_menu -->
            </ul>
        </div>

        <!-- /section:basics/navbar.dropdown -->
    </div><!-- /.navbar-container -->
</div>