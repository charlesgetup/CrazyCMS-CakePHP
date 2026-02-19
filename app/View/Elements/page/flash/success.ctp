<script type="text/javascript">

    <?php
        $success = __('Success');
        $inlineJavaScript = <<<JS
        
            $(document).ready(function(){
                if($.gritter == undefined){
                    loadAndRunJSScript('/js/admin/jquery.gritter.js');
                }
                $.gritter.add({
                    title: '{$success}',
                    text: '{$message}',
                    image: '',
                    sticky: false,
                    time: '8000',
                    class_name: 'gritter-success gritter-center',
                    after_close: function(){
                        $("div.ui-widget").trigger('afterSuccess');
                    }
                });
            });
    
JS;
        echo $this->Minify->minifyInlineJS($inlineJavaScript);
    ?>
    
</script>