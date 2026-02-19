<p>Are you sure you want to email this invoice to <?php echo __($userType); ?>?</p>

<!-- Empty form which generate POST request to the server for email action -->
<?php echo $this->Form->create('PaymentInvoice'); ?>
<?php echo $this->Form->end(); ?>

<!-- page specific plugin scripts -->
<?php echo $this->element('page/admin/load_inline_js'); ?>