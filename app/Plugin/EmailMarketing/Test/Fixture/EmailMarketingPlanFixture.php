<?php
/**
 * EmailMarketingPlanFixture
 *
 */
class EmailMarketingPlanFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'pay_per_email' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'email_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'Max email send amount per month. 0 no limit'),
		'unit_price' => array('type' => 'float', 'null' => false, 'default' => '0.000', 'length' => '12,3', 'comment' => 'per email'),
		'total_price' => array('type' => 'float', 'null' => false, 'default' => '0.000', 'length' => '12,3', 'comment' => 'monthly package cost'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'id' => '1',
			'name' => 'Starter Plan',
			'description' => '<p>Send up to 10000 emails per month.</p>
<p>Use prepaid method ($0.001 per email) to send over limit emails.</p>
<p>No subscriber limit.</p>',
			'pay_per_email' => 0,
			'email_limit' => '10000',
			'unit_price' => '0.001',
			'total_price' => '9.500'
		),
		array(
			'id' => '2',
			'name' => 'Mid-level Plan',
			'description' => '<p>Send up to 100000 emails per month.</p>
<p>Use prepaid method ($0.001 per email) to send over limit emails.</p>
<p>No subscriber limit.</p>',
			'pay_per_email' => 0,
			'email_limit' => '100000',
			'unit_price' => '0.001',
			'total_price' => '95.000'
		),
		array(
			'id' => '3',
			'name' => 'Advanced Plan',
			'description' => '<p>Send up to 1000000 emails per month.</p>
<p>Use prepaid method ($0.001 per email) to send over limit emails.</p>
<p>No subscriber limit.</p>',
			'pay_per_email' => 0,
			'email_limit' => '1000000',
			'unit_price' => '0.001',
			'total_price' => '950.000'
		),
		array(
			'id' => '4',
			'name' => 'Prepaid Plan',
			'description' => '<p>Prepaid $1 for 1000 emails ($0.001 per email).</p>
<p>No contract, no prepaid amount limit, no subscriber limit.</p>',
			'pay_per_email' => 1,
			'email_limit' => '0',
			'unit_price' => '0.001',
			'total_price' => '0.000'
		),
	);

}
