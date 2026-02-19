<?php
/**
 * PaymentPayerFixture
 *
 */
class PaymentPayerFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'payment_payer';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'recurring_plan_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'recurring_agreement_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'payer_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0)
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
			'id' => '2',
			'user_id' => '132',
			'recurring_plan_id' => null,
			'recurring_agreement_id' => null,
			'payer_id' => null
		),
		array(
			'id' => '21',
			'user_id' => '216',
			'recurring_plan_id' => null,
			'recurring_agreement_id' => null,
			'payer_id' => null
		),
		array(
			'id' => '40',
			'user_id' => '235',
			'recurring_plan_id' => null,
			'recurring_agreement_id' => null,
			'payer_id' => null
		),
		array(
			'id' => '41',
			'user_id' => '237',
			'recurring_plan_id' => null,
			'recurring_agreement_id' => null,
			'payer_id' => null
		),
	);

}
