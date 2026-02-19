<?php
/**
 * EmailMarketingUserFixture
 *
 */
class EmailMarketingUserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'unique', 'comment' => '(Child) User.id'),
		'email_marketing_plan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_sender_limit' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
		'email_warning_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'Customise limit to control cash flow. Can be used in "pay per email" plan. 0 no limit.'),
		'free_emails' => array('type' => 'integer', 'null' => false, 'default' => '100', 'length' => 6, 'comment' => 'per month'),
		'payment_cycle' => array('type' => 'string', 'null' => false, 'default' => 'MANUAL', 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'MANUAL, MONTHLY, QUARTERLY, HALF_YEAR, ANNUALLY', 'charset' => 'utf8'),
		'used_email_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'Used to compare with the limit'),
		'total_sent_email_amount' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'Record total sent email amount'),
		'prepaid_amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2'),
		'last_pay_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'next_pay_date' => array('type' => 'date', 'null' => true, 'default' => null, 'comment' => 'This is also the next due date'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 1),
			'plan_id' => array('column' => 'email_marketing_plan_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '20',
			'user_id' => '203',
			'email_marketing_plan_id' => '3',
			'email_sender_limit' => '1',
			'email_warning_limit' => '0',
			'free_emails' => '0',
			'payment_cycle' => 'MANUAL',
			'used_email_count' => '0',
			'total_sent_email_amount' => '195',
			'prepaid_amount' => '0.00',
			'last_pay_date' => null,
			'next_pay_date' => null
		),
		array(
			'id' => '21',
			'user_id' => '236',
			'email_marketing_plan_id' => '4',
			'email_sender_limit' => '1',
			'email_warning_limit' => '0',
			'free_emails' => '100',
			'payment_cycle' => 'MANUAL',
			'used_email_count' => '0',
			'total_sent_email_amount' => '0',
			'prepaid_amount' => '0.00',
			'last_pay_date' => null,
			'next_pay_date' => null
		),
	);

}