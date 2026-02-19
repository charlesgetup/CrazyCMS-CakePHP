<div class="container_12 row">
    <!-- RD Navbar -->
    <div class="rd-navbar-wrap">
        <nav class="rd-navbar" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static" data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static" data-xl-device-layout="rd-navbar-fixed" data-lg-stick-up-offset="46px" data-xl-stick-up-offset="46px" data-xxl-stick-up-offset="46px" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">
            <div class="rd-navbar-outer">
                <div class="rd-navbar-inner">
                    <div class="rd-navbar-subpanel">
                    
                        <!-- RD Navbar Panel-->
                        <div class="rd-navbar-panel">
                            <button class="rd-navbar-toggle toggle-original" data-rd-navbar-toggle=".rd-navbar-nav-wrap"><span></span></button>
                            <a class="rd-navbar-brand" href="/"><img src="<?php echo $companyLogo; ?>" alt="<?php echo $companyName; ?>" class="logo" /></a>
                        </div>
                        <!-- END RD Navbar Panel-->
                    
                        <div class="rd-navbar-nav-wrap">
                            <!-- RD Navbar Nav -->
                            <ul class="rd-navbar-nav">
                                <li class="<?php echo ($page == "home") ? "active" : ""; ?>">
                                    <a href="/"><?php echo __('Home'); ?></a>
                                </li>
                                <li class="<?php echo ($page == "about") ? "active" : ""; ?>">
                                    <a href="/pages/about"><?php echo __('About'); ?></a>
                                </li>
                                <li class="<?php echo stristr($page, "services") ? "active" : ""; ?>">
                                    <a href="/pages/services"><?php echo __('Services'); ?></a>
                                    
                                    <!-- RD Navbar Dropdown -->
                                    <ul class="rd-navbar-dropdown">
                                        <li>
                                            <a href="/pages/services/email_marketing"><?php echo __('Email Marketing'); ?></a>
                                        </li>
                                        <li>
                                            <a href="/pages/services/web_dev"><?php echo __('Web Development'); ?></a>
                                        </li>
                                        <li>
                                            <a href="/pages/services/live_chat"><?php echo __('Live Chat'); ?></a>
                                        </li>
                                        <li>
                                            <a href="/pages/services/seo"><?php echo __('Search Engine Optimisation'); ?></a>
                                        </li>
                                        <li>
                                            <a href="/pages/services/web_hosting"><?php echo __('Web Hosting'); ?></a>
                                        </li>
                                        <li>
                                            <a href="/pages/services/it_support"><?php echo __('IT support services'); ?></a>
                                        </li>
                                    </ul>
                                    <!-- END RD Navbar Dropdown -->
                                    
                                </li>
                                <li class="<?php echo ($page == "blog") ? "active" : ""; ?>">
                                    <a href="/pages/blog"><?php echo __('Blog'); ?></a>
                                </li>
                                <li class="<?php echo ($page == "projects") ? "active" : ""; ?>">
                                    <a href="/pages/projects"><?php echo __('Projects'); ?></a>
                                </li>
                                <li class="<?php echo ($page == "contacts") ? "active" : ""; ?>">
                                    <a href="/pages/contacts"><?php echo __('Contacts'); ?></a>
                                </li>
                                <li>
                                    <a href="/login"><?php echo __('Sign In'); ?></a>
                                </li>
                            </ul>
                            <!-- END RD Navbar Nav -->
                            
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- END RD Navbar -->
</div>

<?php if($page == "home"){ echo $this->element('page/slider'); } ?>  