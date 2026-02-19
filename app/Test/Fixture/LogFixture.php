<?php
/**
 * LogFixture
 *
 */
class LogFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'message' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'type' => array('column' => 'type', 'unique' => 0)
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
			'user_id' => '1',
			'type' => 'USER',
			'message' => 'User "Charles Li" has been registered as "Admin"',
			'timestamp' => '2014-01-11 07:00:00'
		),
		array(
			'id' => '3',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'message' => 'purchase plan',
			'timestamp' => '2014-01-13 06:00:00'
		),
		array(
			'id' => '5',
			'user_id' => '1',
			'type' => 'PAYMENT',
			'message' => 'payment paid',
			'timestamp' => '2014-01-16 09:00:00'
		),
		array(
			'id' => '24',
			'user_id' => '26',
			'type' => 'EMAIL_MARKETING',
			'message' => 'Create Campaign',
			'timestamp' => '2014-01-28 11:00:00'
		),
	);

}
