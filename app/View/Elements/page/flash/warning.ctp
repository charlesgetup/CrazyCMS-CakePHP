<script type="text/javascript">

    <?php
        $hey = __('Hey');
        $inlineJavaScript = <<<JS
        
            $(document).ready(function(){
                if($.gritter == undefined){
                    loadAndRunJSScript('/js/admin/jquery.gritter.js');
                }
                $.gritter.add({
                    title: '{$hey}',
                    text: '{$message}',
                    image: '',
                    sticky: false,
                    time: '8000',
                    class_name: 'gritter-warning gritter-center',
                    after_close: function(){
                        
                    }
                });
            });
    
JS;
        echo $this->Minify->minifyInlineJS($inlineJavaScript);
    ?>
    
</script>