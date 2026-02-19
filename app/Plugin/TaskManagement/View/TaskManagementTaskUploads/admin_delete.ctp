<p><?php echo __('Are you sure?'); ?></p>

<!-- Empty form which generate POST request to the server for delete action -->
<?php echo $this->Form->create('TaskManagementTaskUpload'); ?>
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