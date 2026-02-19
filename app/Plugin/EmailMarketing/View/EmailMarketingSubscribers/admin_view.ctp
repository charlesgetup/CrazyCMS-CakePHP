<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Name'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($subscriber['EmailMarketingSubscriber']['first_name'] .' ' .$subscriber['EmailMarketingSubscriber']['last_name']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Email'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($subscriber['EmailMarketingSubscriber']['email']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Excluded'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo empty($subscriber['EmailMarketingSubscriber']['excluded']) ? "<span class=\"red\">No</span>" : "<span class=\"green\">Yes</span>"; ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Unsubscribed'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo empty($subscriber['EmailMarketingSubscriber']['unsubscribed']) ? "<span class=\"red\">No</span>" : "<span class=\"green\">Yes</span>"; ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Deleted'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo empty($subscriber['EmailMarketingSubscriber']['deleted']) ? "<span class=\"red\">No</span>" : "<span class=\"green\">Yes</span>"; ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Created'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($subscriber['EmailMarketingSubscriber']['created']); ?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="col-xs-6">
            <?php echo __('Modified'); ?>
        </div>
        <div class="col-xs-6">
            <?php echo h($subscriber['EmailMarketingSubscriber']['modified']); ?>
        </div>
    </div>
</div>

<!-- page specific plugin scripts -->
<?php
    echo $this->element('page/admin/load_inline_js', array(
        'inlineJS' => ''
    )); 
?>