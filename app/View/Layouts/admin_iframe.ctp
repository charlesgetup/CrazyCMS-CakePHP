<!--
    This is used by iframe in admin user backend popup box 
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	
    <?php
        echo $this->Html->meta(array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge,chrome=1'));
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
        echo $this->Minify->css(array('admin/chosen', 'admin/pace', 'admin/bootstrap', 'admin/font-awesome', 'admin/jquery-ui', 'admin/jquery-ui.custom', 'admin/bootstrap-datetimepicker', 'admin/ace-fonts', 'admin/jquery.gritter', 'admin/dataTables/extensions/Responsive/css/dataTables.responsive', 'admin/ace', 'admin/dropzone', 'admin/zoom'));
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
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script(array('admin/jquery.gritter', 'admin/spin'))); ?>');
    </script>
	
	<?php echo $this->Minify->css('admin/style'); ?>
	
	<!-- basic scripts -->
    
    <!--[if lte IE 8]>
      <?php echo $this->Minify->script('admin/excanvas'); ?>
    <![endif]-->
    
    <script type="text/javascript">
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script(array('admin/jquery-ui', 'admin/jquery-ui.custom', 'admin/jquery.ui.touch-punch'))); ?>');
    </script>
	
	<script>
        /*** Handle jQuery plugin naming conflict between jQuery UI and Bootstrap ***/
        $.widget.bridge('uitooltip', $.ui.tooltip);
    </script>
	
	<!-- basic scripts -->
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.56/pdfmake.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.56/vfs_fonts.js"></script>
	<script type="text/javascript">
	    window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script(array('admin/bootstrap', 'admin/date-time/moment', 'admin/date-time/bootstrap-datetimepicker', 'admin/dataTables/jquery.dataTables', 'admin/dataTables/jquery.dataTables.bootstrap', 'admin/dataTables/extensions/buttons/dataTables.buttons', 'admin/dataTables/extensions/buttons/buttons.flash', 'admin/dataTables/extensions/buttons/jszip.min', 'admin/dataTables/extensions/buttons/buttons.html5', 'admin/dataTables/extensions/buttons/buttons.print', 'admin/dataTables/extensions/buttons/buttons.colVis', 'admin/dataTables/extensions/select/dataTables.select', 'admin/dataTables/extensions/Responsive/js/dataTables.responsive', 'admin/ace-elements', 'admin/ace', 'admin/bootbox', 'admin/jquery.validate.min', 'admin/additional-methods.min', 'admin/jquery.nestable', 'admin/zoom', 'admin/dropzone', 'admin/chosen.jquery', 'admin/ajax-chosen', 'admin/fuelux/fuelux.spinner', 'admin/numeral.min'))); ?>');
    </script>
	
	<?php 
        // Because our parser cannot handle a file which is more than 10KB, I have to split large JS files into smaller ones.
        $encryptedJS = $this->Minify->script(array(
            'admin/general-extend.encrypted', 
            'admin/general-base.encrypted'
        )); 
    ?>
    <script type="text/javascript">
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $encryptedJS); ?>');
    </script>
	
	<script type="text/javascript" src="/assets/tinymce/tinymce.min.js"></script>
	
</head>
<body class="no-skin">
    <div class="main-container" id="main-container">
    
        <script type="text/javascript">
            try{ace.settings.check('main-container' , 'fixed')}catch(e){}
        </script>
        
        <div class="main-content">
            <div class="page-content">
                <div class="page-content-area">
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>        
        </div>
        
   </div> 
   
   <script type="text/javascript">
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $this->Minify->script(array('admin/iframeResizer/iframeResizer.min', 'admin/iframeResizer/iframeResizer.contentWindow.min'))); ?>');
    </script>
   <?php 
        // Because our parser cannot handle a file which is more than 10KB, I have to split large JS files into smaller ones.
        $encryptedJS = $this->Minify->script(array(
            'admin/script-override-ACE.encrypted',
            'admin/script.encrypted'
        )); 
    ?>
    <script type="text/javascript">
        window.jQuery && document.write('<?php echo str_replace("</script>", "<'+'/script>", $encryptedJS); ?>');
    </script>
   
   <!-- page specific plugin scripts -->
    <?php
    
        /* 
         * The following PHP code can only run once when the page is loaded. But sometimes in this iframe, client needs to send multiple AJAX requests.
         * So in each AJAX requests, the global token in ajaxSetup will be the same. But this is obviously not correct.
         * To fix this, we manually updated X-CSRF-Token header when new token is generated in security component
         * 
         * Use $.ajaxSetup({ headers: {"X-CSRF-Token" : {token}} }); to manually override the following token before multiple ajax call
         */
        
        $inlineJS = <<<EOF
        
           var globalSecToken = window.getCookie('{$csrfCookieName}');
           $.ajaxSetup({
               headers: {"X-CSRF-Token" : globalSecToken}
           });
           $( document ).ajaxSend( function (event, jqxhr, settings) {
               if(settings.data){
                   var data = settings.data.deserialize();
                   for(var d in data){
                        var key = Object.keys(data[d]).pop();
                        if(key == "data[_Token][key]"){
                            jqxhr.setRequestHeader("X-CSRF-Token", null); // Remove global token if form data has it already
                            break;
                        }
                   }
               }
           });
EOF;
       /* echo $this->element('page/admin/load_inline_js', array(
            'inlineJS' => $inlineJS
        )); */
    ?>
   
</body>
</html>
