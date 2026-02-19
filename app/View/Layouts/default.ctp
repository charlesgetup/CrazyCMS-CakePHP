<!--
    This is used as main layout by the pages in front end website
-->
<?php
    App::uses('GenerateMetaTag', 'Seo');
    $pageContent = $this->fetch('content');
?>
<!DOCTYPE html>
<?php echo $this->Facebook->html(); ?>
<html lang="en">
    <head>
        <title>
            <?php echo $title_for_layout; ?>
        </title>
        <?php echo $this->Html->charset(); ?>
        <?php
            echo $this->Html->meta('favicon.ico', '/favicon.ico?' .time(), array('type' => 'icon'));
        
            $seoParams['content'] = $pageContent;
            $seoMetaTagGenerator = new GenerateMetaTag($seoParams, "utf-8");
            $seoMetaTags = $seoMetaTagGenerator->getSEOMetaTags();

            if($seoMetaTags){
                echo $seoMetaTags;
            }else{
                // Assign keywords and description in each page. If not, use the default keywords and description
                echo $this->Html->meta(array('name' => 'referrer', 'content' => 'always'));
                echo $this->Html->meta(array('name' => 'robots', 'content' => 'all'));
                if($this->fetch('meta')){
                    echo $this->fetch('meta'); 
                }else{
                    echo $this->Html->meta(
                        'keywords',
                        $metaKeywords
                    );
                    echo $this->Html->meta(
                        'description',
                        $metaDescription
                    );
                }
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
            echo $this->Minify->css(array('admin/bootstrap', 'admin/font-awesome', 'admin/ace-part2', 'rd-navbar', 'camera', 'style'));
            echo $this->fetch('css');
        
            echo $this->Minify->script(array('jquery.min', 'jquery-migrate-1.1.1.min', 'jquery.easing.1.3', 'jquery.rd-navbar', 'jquery.mobilemenu', 'camera', 'jquery.equalheights', 'jquery.ui.totop', 'admin/jquery.raty', 'design-script'));
            echo $this->fetch('script');
        ?>
    
        <!--[if (gt IE 9)|!(IE)]><!-->
            <?php echo $this->Minify->script(array('jquery.mobile.customized.min')); ?>
        <!--<![endif]-->
        <!--[if lt IE 9]>
            <?php echo $this->Minify->script(array('html5shiv.min')); ?>
            <?php echo $this->Minify->css(array('ie')); ?>
        <![endif]-->
        
    </head>
    <body>
    
        <!--==============================header=================================-->
        <header>
            <?php echo $this->element('page/header', array('companyLogo' => $companyLogo)); ?>  
        </header>
        
        <!--=======content================================-->
        
        <section class="content">
            <?php echo $this->Session->flash(); ?>
            <?php echo $pageContent; ?>
        </section>
        
        <!--=======footer=================================-->
        <footer>
            <?php echo $this->element('page/footer', array('companyName' => $companyName)); ?>  
        </footer>
        
        <?php echo $this->Facebook->init(); ?>

        <script>(function(d,t,u,s,e){e=d.getElementsByTagName(t)[0];s=d.createElement(t);s.src=u;s.async=1;e.parentNode.insertBefore(s,e);})(document,'script','//livechat.crazysoft.com.au/index?3qJtj3dJUlpVLkM+OsbiSSfg9c/47vI/a7IrH3NGjaA6rYHAh+QY2LMXLO19U+cP&1');</script>
    </body>
</html>