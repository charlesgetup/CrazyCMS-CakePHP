<p><?php echo __('Are you sure?'); ?></p>

<!-- Empty form which generate POST request to the server for delete action -->
<?php echo $this->Form->create('EmailMarketing.EmailMarketingSubscriber'); ?>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php echo $this->element('page/admin/load_inline_js'); ?>