<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Id'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($list['EmailMarketingMailingList']['id']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('User Name'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($list['EmailMarketingMailingList']['user_name']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('List Name'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($list['EmailMarketingMailingList']['name']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Description'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($list['EmailMarketingMailingList']['description']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Active'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo empty($list['EmailMarketingMailingList']['active']) ? "<span class=\"red\">No</span>" : "<span class=\"green\">Yes</span>"; ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Created Date'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($list['EmailMarketingMailingList']['created']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Modified Date'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($list['EmailMarketingMailingList']['modified']); ?>
        </div>
    </div>
</div>