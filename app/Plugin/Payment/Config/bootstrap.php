<?php
/**
 * Payment plugin
 *
 * A CakePHP Plugin to manage payment stuff
 *
 */

/**
 * System default setting type
 */
Configure::write('Config.type.payment', "PAYMENT");
Configure::write('Finance.staff.group.id', "17");
Configure::write('Finance.manager.group.id', "18");

Configure::write('Payment.method.paypal.paypal', 				"paypal");
Configure::write('Payment.method.paypal.credit_card', 			"credit_card");
Configure::write('Payment.method.paypal.direct_debit', 			"bank");

Configure::write('Payment.method.paypal.allowed_cc_type', 		array('visa' => "Visa", 'mastercard' => "MasterCard", "discover" => "Discover", 'amex' => "American Express"));

Configure::write('Payment.pay.cycle.manual', 	"MANUAL");
Configure::write('Payment.pay.cycle.monthly', 	"MONTHLY");
Configure::write('Payment.pay.cycle.quarterly', "QUARTERLY");
Configure::write('Payment.pay.cycle.half_year', "HALF_YEAR");
Configure::write('Payment.pay.cycle.annually', 	"ANNUALLY");

Configure::write('Payment.invoice.status.unpaid', 			"UNPAID"); // This status is not used in DB, it only works as an identifier in the code for unpaid invoices
Configure::write('Payment.invoice.status.pending', 			"PENDING");
Configure::write('Payment.invoice.status.partial_paid', 	"PARTIAL_PAID");
Configure::write('Payment.invoice.status.paid', 			"PAID");
Configure::write('Payment.invoice.status.refund', 			"REFUND");

Configure::write('Payment.paypal.gateway.status.created', 		"created");
Configure::write('Payment.paypal.gateway.status.approved', 		"approved");
Configure::write('Payment.paypal.gateway.status.failed', 		"failed");
Configure::write('Payment.paypal.gateway.status.canceled', 		"canceled");
Configure::write('Payment.paypal.gateway.status.expired', 		"expired");
Configure::write('Payment.paypal.gateway.status.completed', 	"completed");

Configure::write('Payment.paypal.gateway.agreement.status.pending', 	"Pending");
Configure::write('Payment.paypal.gateway.agreement.status.active', 		"Active");
Configure::write('Payment.paypal.gateway.agreement.status.suspended', 	"Suspended");
Configure::write('Payment.paypal.gateway.agreement.status.cancelled', 	"Cancelled");
Configure::write('Payment.paypal.gateway.agreement.status.expired', 	"Expired");

Configure::write('Payment.invoice.path', "payment" .DS ."{user_id}" .DS ."invoices");

Configure::write('Payment.paypal.rest.api.app.mode', 			"sandbox");

// Test Business account
// Configure::write('Payment.paypal.rest.api.app.client.id', 		"yours");
// Configure::write('Payment.paypal.rest.api.app.client.secret', 	"yours");

// Test Business Pro account
Configure::write('Payment.paypal.rest.api.app.client.id', 		"yours");
Configure::write('Payment.paypal.rest.api.app.client.secret', 	"yours");

// If need to change the following purchase code, please double check the payment code in Payment Payment{PayPal}GatewayController.php file

Configure::write('Payment.code.website.development', 					'WD');

Configure::write('Payment.code.email_marketing.prepaid', 				'EMP');
Configure::write('Payment.code.email_marketing.monthly_recurring', 		'EMMR');
Configure::write('Payment.code.email_marketing.quarterly_recurring', 	'EQUR');
Configure::write('Payment.code.email_marketing.half_yearly_recurring', 	'EHYR');
Configure::write('Payment.code.email_marketing.annually_recurring', 	'EANR');
Configure::write('Payment.code.email_marketing.template_purchase', 		'ET');

Configure::write('Payment.code.live_chat.monthly_recurring', 			'LCMR');
Configure::write('Payment.code.live_chat.quarterly_recurring', 			'LQUR');
Configure::write('Payment.code.live_chat.half_yearly_recurring', 		'LHYR');
Configure::write('Payment.code.live_chat.annually_recurring', 			'LANR');

Configure::write('Payment.template.invoice', 	'Payment Invoice');
Configure::write('Payment.template.reminder', 	'Payment Reminder');
Configure::write('Payment.template.downgrade', 	'Payment Downgrade');