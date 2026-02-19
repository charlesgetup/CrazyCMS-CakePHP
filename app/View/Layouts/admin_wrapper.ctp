<!--
    This is used by the pages in admin user backend before logged in 
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
        echo $this->Minify->css(array('admin/bootstrap', 'admin/font-awesome', 'admin/ace-fonts', 'admin/jquery-ui', 'admin/jquery.gritter', 'admin/ace'));
    ?>
    
    <!--[if lte IE 9]>
        <?php echo $this->Minify->css('admin/ace-part2'); ?>
    <![endif]-->
    
    <?php echo $this->Minify->css('admin/ace-rtl'); ?>
    
    <!--[if lte IE 9]>
        <?php echo $this->Minify->css('admin/ace-ie'); ?>
    <![endif]-->
    
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
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script(array('admin/jquery.gritter', 'admin/spin'))); ?>');
    </script>
    
    <?php echo $this->Minify->css('admin/style'); ?>
    
    <!-- basic scripts -->
    <script type="text/javascript">
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script(array('admin/jquery-ui', 'admin/bootstrap', 'admin/jquery.validate.min', 'admin/additional-methods.min', 'admin/dropzone'))); ?>');
    </script>
    
    <?php 
        // Because our parser cannot handle a file which is more than 10KB, I have to split large JS files into smaller ones.
        $encryptedJS = $this->Minify->script(array(
            'admin/general-extend.encrypted', 
            'admin/general-base.encrypted',
            'admin/script-override-ACE.encrypted',
            'admin/script.encrypted'
        )); 
    ?>
    <script type="text/javascript">
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $encryptedJS); ?>');
    </script>
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
</head>
<body class="login-layout blur-login">
    <div class="main-container">
        <div class="main-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="login-container">
                        <div class="center">
                            <h1>
                                <img src="<?php echo $companyLogo; ?>" alt="<?php echo $companyName; ?>" class="logo" />
                            </h1>
                            <h4 class="light-blue" id="id-company-text">&copy; <?php echo $companyName; ?></h4>
                        </div>

                        <div class="space-6"></div>

                        <div class="position-relative">
                            <?php echo $this->Session->flash(); ?>
                            <?php echo $this->fetch('content'); ?>
                        </div><!-- /.position-relative -->
                        
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.main-content -->
    </div><!-- /.main-container -->

    <!-- page specific plugin scripts -->
    <?php
        
        $inlineJS = <<<EOF
        
            jQuery(function($) {
                $(document).on('click', '.toolbar a[data-target]', function(e) {
                    e.preventDefault();
                    var target = $(this).data('target');
                    $('.widget-box.visible').removeClass('visible'); // hide others
                    $(target).addClass('visible'); // show target
                });
            });
EOF;
        echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => $inlineJS
        )); 
    ?>
    
    <?php echo $this->Facebook->init(); ?>
</body>
</html>
