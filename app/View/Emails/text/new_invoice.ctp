<?php echo __('Hi'); ?>, <?php echo @$invoice["ClientUser"]["name"]; ?>

<?php echo __('Thank you for using ' .$companyName .' online service!'); ?>

<?php echo __('New invoice has arrived.'); ?>

<?php echo __('Invoice download link:') .' ' .$invoiceFile; ?>

<?php echo __('To view your invoice online, please follow this link (http://<?php echo $companyDomain; ?>/login) to login and you will find it in the Payment section.'); ?>
			
<?php if(isset($invoice['PaymentInvoice']['is_auto_created']) && empty($invoice['PaymentInvoice']['is_auto_created'])): ?>
    <?php echo __("Due Date"); ?>:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @$invoice['PaymentInvoice']['due_date']; ?>
<?php else: ?>
    <?php echo __("Transaction ID"); ?>:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo @$invoice['PaymentPayPalGateway']['transaction_id']; ?>
<?php endif; ?>				

<?php echo __("Invoice Content"); ?>:

<?php echo @$invoice['PaymentInvoice']['plain_text_content']; ?>