<?php
/**
 * PaymentTempInvoiceFixture
 *
 */
class PaymentTempInvoiceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'is_auto_created' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'),
		'purchase_code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'due_date' => array('type' => 'date', 'null' => false, 'default' => null, 'key' => 'index'),
		'related_update_data' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'manual created staff user id'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'is_auto_created' => array('column' => 'is_auto_created', 'unique' => 0),
			'created_by' => array('column' => 'created_by', 'unique' => 0),
			'due_date' => array('column' => 'due_date', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'user_id' => '132',
			'is_auto_created' => 1,
			'purchase_code' => 'EMM',
			'amount' => '950.00',
			'content' => '<p>Send up to 1000000 emails per month.</p>
<p>Use prepaid method ($0.001 per email) to send over limit emails.</p>
<p>No subscriber limit.</p>',
			'due_date' => '2014-08-23',
			'related_update_data' => 'a:4:{s:6:"plugin";s:14:"EmailMarketing";s:5:"class";s:18:"EmailMarketingUser";s:2:"id";s:2:"20";s:4:"data";a:1:{s:18:"EmailMarketingUser";a:12:{s:2:"id";s:2:"20";s:7:"user_id";s:3:"203";s:23:"email_marketing_plan_id";s:1:"3";s:18:"email_sender_limit";s:1:"1";s:19:"email_warning_limit";s:1:"0";s:11:"free_emails";s:1:"0";s:13:"payment_cycle";s:6:"MANUAL";s:16:"used_email_count";s:1:"0";s:23:"total_sent_email_amount";s:1:"0";s:14:"prepaid_amount";s:4:"0.00";s:13:"last_pay_date";s:10:"2014-08-25";s:13:"next_pay_date";s:10:"2014-09-25";}}}',
			'created_by' => '132',
			'created' => '2014-08-25 20:51:02'
		),
	);

}
