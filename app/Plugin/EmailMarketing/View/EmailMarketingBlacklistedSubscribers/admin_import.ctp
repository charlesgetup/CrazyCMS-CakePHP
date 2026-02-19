<?php echo $this->Form->create('EmailMarketing.EmailMarketingBlacklistedSubscriber', array('type' => 'file', 'class' => "dropzone", 'id' => "dropzone")); ?>
    
    <?php
        if(isset($userId)){
            echo $this->Form->hidden('email_marketing_user_id', array('value' => $userId));
        }
    ?>
    
    <?php if(isset($userList) && is_array($userList)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                    echo $this->Form->input('email_marketing_user_id', array(
                        'label'         => array('text' => __('Email Marketing Client') .'&nbsp;<span class="mandatory">*</span>'),
                        'options'       => $userList,
                        'class'         => 'required col-xs-12 col-sm-12',
                        'div'           => false,
                        'tabindex'      => 1
                    ));
                ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-xs-12 fallback">
            <?php
                echo $this->Form->file('subscriber_file', array(
                    'class'         => 'upload_field',
                ));
            ?>
        </div>
    </div>
    
<?php echo $this->Form->end(); ?>

<?php $uploadFileMaxSize = ($uploadFileMaxSize / 1024 / 1024); ?>
<div class="notice-area">
    <h3><?php echo __("Notice"); ?>:</h3>
    <ul>
        <li>
            <?php echo __('We only accept CSV and Spreadsheet (xls,xlsx) file. We prefer to use CSV file format.'); ?>
        </li>
        <li>
            <?php echo __('The max file size will be') .' ' .$uploadFileMaxSize .' MB.'; ?>
        </li>
        <li>
            <?php echo __('You can upload up to <font color="black"><b>' .$remainingSubscriberIncrementAmount .' subscribers</b></font>.'); ?>
        </li>
        <li>
            <?php echo __('The header of the file is <i>Email</i>. <i>Email</i> field is required.'); ?>
        </li>
        <li>
            <?php echo __('File header must be included and it will be in first line of the file.<br />For example:<br />Line 1: <i>Email</i><br />Line 2: <i>test@example.com</i><br />Line ... ...'); ?>
        </li>
        <li>
            <?php echo __('All the duplicated and invalid emails will be automatically ignored during the import process.'); ?>
        </li>
    </ul>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        loadDragNDropZone("#dropzone", "data[EmailMarketingBlacklistedSubscriber][subscriber_file]", {$uploadFileMaxSize}, ".xlsx,.xls,.csv");
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>