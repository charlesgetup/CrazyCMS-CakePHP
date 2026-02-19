<!--
    This is used by error pages in admin user backend after logged in
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo $this->Facebook->html(); ?>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $title_for_layout; ?>
    </title>
    <?php
        echo $this->Html->meta(array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge,chrome=1'));
        echo $this->Html->meta('favicon.ico', '/favicon.ico?' .time(), array('type' => 'icon'));
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0, maximum-scale=1.0'));
        echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex,nofollow'));
        
        if($this->fetch('meta')){
            echo $this->fetch('meta');  
        }
    ?>
    
    <script type="text/javascript">
        <?php
            /* Global JS settings */
            foreach(Configure::read('System.variable') as $systemVar){
            
                if($systemVar == Configure::read('System.variable.debug')){
                
                    $debug = (Configure::read('debug') > 0) ? 'true' : 'false';
                    echo "var {$systemVar} = {$debug};";
                    
                }else{
                    
                    echo "var {$systemVar} = '{$systemVar}';";
                }
            }
        ?>
    </script>
    
    <?php
        echo $this->Minify->css(array('admin/bootstrap', 'admin/font-awesome', 'admin/jquery-ui', 'admin/ace-fonts', 'admin/jquery.gritter', 'admin/ace', 'admin/dropzone'));
    ?>
    
    <!--[if lte IE 9]>
        <?php echo $this->Minify->css('admin/ace-part2'); ?>
    <![endif]-->
    
    <?php echo $this->Minify->css(array('admin/ace-skins', 'admin/ace-rtl')); ?>
    
    <!--[if lte IE 9]>
        <?php echo $this->Minify->css('admin/ace-ie'); ?>
    <![endif]-->
    
    <?php echo $this->Minify->script(array('admin/ace-extra')); ?>
    
    <!--[if lt IE 8]>
        <?php echo $this->Minify->script(array('admin/html5shiv', 'admin/respond')); ?>
    <![endif]-->
    
    <!--[if !IE]> -->
        <script type="text/javascript">
            window.jQuery || document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script('admin/jquery.min')); ?>');
        </script>
    <![endif]-->
    
    <!--[if IE]>
        <script type="text/javascript">
            window.jQuery || document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script('admin/jquery1x')); ?>');
        </script>
    <![endif]-->
    
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script('admin/jquery.mobile.custom')); ?>');
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script('admin/jquery.gritter')); ?>');
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script('admin/spin')); ?>');
    </script>
    
    <?php echo $this->Minify->css('admin/style'); ?>
    
    <!-- basic scripts -->
    <?php echo $this->Minify->script('admin/bootstrap'); ?>
    
    <!--[if lte IE 8]>
      <?php echo $this->Minify->script('admin/excanvas'); ?>
    <![endif]-->
    
    <?php echo $this->Minify->script(array('admin/jquery-ui.custom', 'admin/jquery.ui.touch-punch', 'admin/ace-elements', 'admin/ace', 'admin/bootbox', 'admin/jquery.validate.min', 'admin/additional-methods.min', 'admin/dropzone')); ?>
    
    <?php 
        // Because our parser cannot handle a file which is more than 10KB, I have to split large JS files into smaller ones.
        echo $this->Minify->script(array(
            'admin/general-extend.encrypted', 
            'admin/general-base.encrypted'
        )); 
    ?>
    
</head>
<body class="no-skin">
    
    <div class="main-container" id="main-container">
    
        <script type="text/javascript">
            try{ace.settings.check('main-container' , 'fixed')}catch(e){}
        </script>
        
        <div class="main-content">
        
            <div class="page-content">
            
                <!-- /section:settings.box -->
                <div class="page-content-area">
                    <?php echo $this->fetch('content'); ?>
                </div>
                
            </div>
            
        </div>
        
        <div class="footer">
            <div class="footer-inner">
            
                <!-- #section:basics/footer -->
                <div class="footer-content">
                    <span class="bigger-120">
                       <?php echo $companyName; ?>&nbsp;&copy;&nbsp;<?php echo date('Y'); ?>
                    </span>
                </div>
                <!-- /section:basics/footer -->
                
            </div>
        </div>

        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
                
    </div>    
    
    <?php 
        // Because our parser cannot handle a file which is more than 10KB, I have to split large JS files into smaller ones.
        echo $this->Minify->script(array(
            'admin/script-override-ACE.encrypted',
            'admin/script.encrypted'
        )); 
    ?>
    
</body>
</html>
