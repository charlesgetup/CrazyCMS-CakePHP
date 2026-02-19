<?php echo $this->Form->create('TaskManagement.TaskManagementTaskComment'); ?>

<?php
    echo $this->Form->hidden('id');
    echo $this->element('generals/online_editor', array(
        'textareaName'  => 'write-comment', 
        'content'       => $comment['TaskManagementTaskComment']['comment'], 
        'customCssStyle'=> '.Editor-editor {height: 100%;}'
    ));
?>

<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        
        /* Close popup window after submit */
        $(parent.document).find("div.modal-dialog").filter(function(){ return $(this).css("display") == "block"; }).children(".modal-content").children(".modal-footer").children(".submit-iframe-form-btn").addClass("close-popup-after-submit");
        
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>