<?php
/**
 * PaymentPayPalGatewayFixture
 *
 */
class PaymentPayPalGatewayFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'payment_pay_pal_gateway';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'payment_payer_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'payment_invoice_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'is_recurring' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'is_temp' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => '0.000', 'length' => '12,3'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'payment_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'transaction_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'intent' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'payment_payer_id' => array('column' => 'payment_payer_id', 'unique' => 0),
			'payment_invoice_id' => array('column' => 'payment_invoice_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
	);

}
