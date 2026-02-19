<!-- PAGE CONTENT BEGINS -->
<?php if($this->Permissions->check($acl, 'Dashboard/admin_index')): ?>

    <?php if($this->Permissions->isClient()): ?>
        <?php
            $topFiveTaskCount = empty($topFiveTaskCount) ? 0 : $topFiveTaskCount;
        ?>
        <div class="row dashboard ">
            
            <h1 class="grey"><?php echo __('See what we can do'); ?></h1>
            
            <div class="space-24"></div>
            
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="small-box bg-aqua">
                        <div class="inner ui-sortable-handle">
                            <h3><?php echo $emailMarketingCampaignCount; ?></h3>
                            <p><?php echo __('Open Campaigns'); ?></p>
                        </div>
                        <div class="icon"><i class="fa fa-envelope-o"></i></div>
                        <a href="<?php echo empty($emailMarketingCampaignCount) ? '/admin/dashboard#/admin/email_marketing/email_marketing_dashboard' : '/admin/dashboard#/admin/email_marketing/email_marketing_campaigns'; ?>" class="small-box-footer">
                            <?php echo empty($emailMarketingCampaignCount) ? __('Create Campaign') : __('View Campaigns'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-6">
                    <div class="small-box bg-purple">
                        <div class="inner ui-sortable-handle">
                            <h3><?php echo $webDevProjectCount; ?></h3>
                            <p><?php echo __('Open Web Dev Projects'); ?></p>
                        </div>
                        <div class="icon"><i class="fa fa-image"></i></div>
                        <a href="<?php echo empty($webDevProjectCount) ? '/admin/dashboard#/admin/web_development/web_development_dashboard' : '/admin/dashboard#/admin/web_development/web_development_projects'; ?>" class="small-box-footer">
                            <?php echo empty($webDevProjectCount) ? __('Create Project') : __('View Projects'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="space-24"></div>
            
            <p class="center">
                The services we provide is flexible and customizable.
                If you need more, just let us know. Our can-do attitude will guarantee that all you needs will be satisfied.
            </p>
            
            <div class="space-24"></div>
            
            <div class="small-box bg-green">
                <div class="inner ui-sortable-handle">
                    <h3><?php echo $topFiveTaskCount; ?></h3>
                    <p><?php echo __('Open Tickets'); ?></p>
                </div>
                <div class="icon"><i class="fa fa-ticket"></i></div>
                <a href="/admin/dashboard#/admin/task_management/task_management_tasks/index/0/1" class="small-box-footer">
                    <?php echo empty($topFiveTaskCount) ? __('Create Ticket') : __('View Tickets'); ?>&nbsp;&nbsp;<i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
            
        </div>
    <?php else: ?>
        
        <?php 
            if($this->Permissions->isAdmin()){
                echo $this->element('Webmaster.webmaster_monitors');
            } 
        ?>
    
    <?php endif; ?>

<?php endif; ?>

<!-- inline scripts related to this page -->
<?php
    $inlineJS = <<<EOF

        $('.page-content-area > .page-header').css('display', 'none');

EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    ));
?>