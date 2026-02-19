<?php echo $this->Form->create('EmailMarketing.EmailMarketingSubscriber', array('type' => 'file', 'class' => "dropzone", 'id' => "dropzone")); ?>
    
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo $this->Form->input('email_marketing_list_id', array(
                    'label'         => array('text' => __('Mailing List') .'&nbsp;<span class="mandatory">*</span>'),
                    'options'       => array($list['EmailMarketingMailingList']['id'] => $list['EmailMarketingMailingList']['name']),
                    'class'         => 'required col-xs-12 col-sm-12',
                    'div'           => false,
                    'tabindex'      => 1
                ));
            ?>
        </div>
    </div>
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
            <?php echo __('The default header of the file is <i>EMAIL,FIRST-NAME,LAST-NAME</i>. Only <i>EMAIL</i> field is required.<br />If your plan supports extra attributes, you can add extra attributes in new columns, e.g. <i>EMAIL,FIRST-NAME,LAST-NAME,Gender,Date-of-birth</i>'); ?>
        </li>
        <li>
            <?php echo __('Only letters, white space, numbers, underscore and hyphen are allowed in header. Other characters will be stripped.'); ?>
        </li>
        <li>
            <?php echo __('When add attributes in email template, please use exactly the same text in header columns wrapped by square brackets.<br />For example, you put "Gender" as an extra attrbute in the header of uploaded file, then later you can use "[Gender]" as a placeholder in email template.<br />The default attributes (EMAIL,FIRST-NAME,LAST-NAME) will be always used in capital letters and under the same format.<br />For example, "Email,First Name,Last Name", "email,first Name,last Name" are NOT acceptable. Only "EMAIL,FIRST-NAME,LAST-NAME" is acceptable.'); ?>
        </li>
        <li>
            <?php echo __('File header must be included and it will be in first line of the file.<br />For example:<br />Line 1: <i>EMAIL,FIRST-NAME,LAST-NAME</i><br />Line 2: <i>test@example.com,Tom,Green</i><br />Line ... ...'); ?>
        </li>
        <li>
            <?php echo __('All the duplicated, invalid and blacklisted emails will be automatically ignored during the import process.'); ?>
        </li>
    </ul>
</div>

<!-- page specific plugin scripts -->
<?php
    $inlineJS = <<<EOF
        loadDragNDropZone("#dropzone", "data[EmailMarketingSubscriber][subscriber_file]", {$uploadFileMaxSize}, ".xlsx,.xls,.csv");
EOF;
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => $inlineJS
    )); 
?>