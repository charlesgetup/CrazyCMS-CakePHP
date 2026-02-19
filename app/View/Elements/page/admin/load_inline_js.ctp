<?php
    if(isset($loadedScripts) && is_array($loadedScripts) && !empty($loadedScripts)){
        array_walk($loadedScripts, function(&$item) { 
            $item = 'admin/' .$item; 
            $item = $this->Minify->assetPath($item, 'js');
            $item = '"' .$item .'"';
        });
        $loadedScripts = implode(",", $loadedScripts) .",";
    }else{
        $loadedScripts = "";
    }
    
    $randomStr = mt_rand(); // Use this to allow embed multiple inline JS functions
?>
<script type="text/javascript"> 

    <?php
        $inlineJS = empty($inlineJS) ? '' : $inlineJS;
        $javaScripts = "inlineJS_{$randomStr}();";
        if(!empty($loadedScripts)){
            
            $javaScripts = <<<JSS
                scripts = scripts.filter(function(e){return e}); 
                for(var i = 0; i < scripts.length; i++){
                    var scriptUrl = scripts[i]; 
                    if((i + 1) == scripts.length){
                        loadAndRunJSScript(scriptUrl, null, inlineJS_{$randomStr}); /* Run inline JS code as callback after the last script file is loaded */
                    }else{
                        loadAndRunJSScript(scriptUrl);
                    }
                }
JSS;
            
        }
        $inlineJavaScript = <<<JS
            
            var scripts = [null, {$loadedScripts} null];
            var inlineJS_{$randomStr} = function(){
                {$inlineJS}
            };
        
            if($.fn.ace_ajax && $('[data-ajax-content="true"]').length){
                $('[data-ajax-content="true"]').ace_ajax('loadScripts', scripts, function() {
                    jQuery(function($) {
                        inlineJS_{$randomStr}();
                    });
                });
            }else{
                {$javaScripts}
            }
    
JS;
        echo $this->Minify->minifyInlineJS($inlineJavaScript);
    ?>

</script>