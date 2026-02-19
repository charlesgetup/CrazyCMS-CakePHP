<?php 
    $textareaName = empty($textareaName) ? 'content' : $textareaName;
    if(!empty($content)){
        $content= preg_replace('/\r\n|\r|\n/', " ", $content);
        $content = addslashes($content);   
    }else{
        $content = '';   
    }
     
?>
<style type="text/css">
    @import url("/css/admin/editor.css?<?php echo time(); ?>");
    <?php  
        if(!empty($customCssStyle)){
            echo $customCssStyle;
        }
    ?>
</style>
<textarea id="<?php echo $textareaName; ?>" name="<?php echo $textareaName; ?>"><?php echo @$content; ?></textarea>
<?php

    $inlineJS = <<<EOF
        $(document).ready(function(){
            $("#{$textareaName}").Editor();
            $("#{$textareaName}").Editor("setText", "{$content}");
        });
        $(document).submit(function(){
            $("#{$textareaName}").val($("#{$textareaName}").siblings(".Editor-container").children(".Editor-editor").html());
        });
EOF;

    //page specific plugin scripts
    echo $this->element('page/admin/load_inline_js', array(
        'loadedScripts' => array('editor.js'),
        'inlineJS' => $inlineJS
    )); 
?>