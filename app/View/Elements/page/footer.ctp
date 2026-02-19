<div class="container_12">
    <div class="row">
        <div class="grid_3">
            <h3><?php echo __('LATEST PROJECTS'); ?></h3>
            <ul class="list-1">
                <li><a href="http://formyloves.com.au">For My Loves</a></li>
                <li><a href="http://laitif.com">Laitif.com</a></li>
                <li><a href="http://uggforever.com.au">UGG Forever</a></li>
                <li><a href="http://fastrackexpress.com.au">FastrackExpress</a></li>
            </ul>
        </div>
        <div class="grid_3">
            <h3><?php echo __('USEFUL LINKS'); ?></h3>
            <ul class="list-1">
                <li><a href="/pages/services/online_marketing/email_marketing"><?php echo __('Email Marketing'); ?></a></li>
                <li><a href="/pages/services/web_dev"><?php echo __('Web Development'); ?></a></li>
                <li><a href="/pages/services/live_chat"><?php echo __('Live Chat'); ?></a></li>
                <li><a href="/pages/services/web_hosting"><?php echo __('Web Hosting'); ?></a></li>
            </ul>
        </div>
        <div class="grid_3">
            <h3><?php echo __('From the blog'); ?></h3>
            <ul class="list-1">
                <li><a href="/pages/blogs/en/our_approach" target="_blank"><?php echo __('Our approach'); ?></a></li>
                <li><a href="/pages/blogs/en/email_marketing/the_main_marketing_channel" target="_blank"><?php echo __('Email Marketing Tips'); ?></a></li>
                <li><a href="/pages/blogs/en/live_chat/how_important_it_is" target="_blank"><?php echo __('Live Chat is key'); ?></a></li>
                <li><a href="/pages/blogs/en/web_hosting/compare_cheap_hosting_company_service" target="_blank"><?php echo __('Compare web hosting'); ?></a></li>
            </ul>
        </div>
        <div class="grid_3">
            <h3><?php echo __('follow us'); ?></h3>
            <?php echo $this->element('page/social_media'); ?>  
        </div>
    </div>
    <div class="f-bot">
        <div class="row">
            <div class="grid_12">
                <a href="/" class="logo"><?php echo $companyName; ?></a>
                <span>&copy; <?php echo date('Y'); ?> | </span>
                <a href="/pages/privacy_policy" class="h-underline"><?php echo __('Privacy Policy'); ?></a> | 
                <a href="/pages/terms_of_service" class="h-underline"><?php echo __('Terms of Service'); ?></a><!--{%FOOTER_LINK} -->
            </div>
        </div>
    </div>
</div>