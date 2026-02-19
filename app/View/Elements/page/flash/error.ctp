<script type="text/javascript">

    <?php
        $alert = __('Alert');
        $inlineJavaScript = <<<JS
        
            $(document).ready(function(){
                if($.gritter == undefined){
                    loadAndRunJSScript('/js/admin/jquery.gritter.js');
                }
                $.gritter.add({
                    title: '{$alert}',
                    text: '{$message}',
                    image: '',
                    sticky: false,
                    time: '8000',
                    class_name: 'gritter-error gritter-center',
                    after_close: function(){
                        
                    }
                });
            });
    
JS;
        echo $this->Minify->minifyInlineJS($inlineJavaScript);
    ?>
    
</script>