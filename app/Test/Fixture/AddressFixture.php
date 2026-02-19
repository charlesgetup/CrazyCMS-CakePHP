<?php
/**
 * AddressFixture
 *
 */
class AddressFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'country_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'CONTACT', 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'BILLING,CONTACT', 'charset' => 'utf8'),
		'same_as' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'BILLING,CONTACT', 'charset' => 'utf8'),
		'street_address' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'suburb' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'postcode' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'state' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'country_id' => array('column' => 'country_id', 'unique' => 0)
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
			'country_id' => '13',
			'type' => 'CONTACT',
			'same_as' => '0',
			'street_address' => 'Unit 501J, 27-29 George Street',
			'suburb' => 'North Strathfield',
			'postcode' => '2137',
			'state' => 'NSW',
			'created' => '2013-08-27 21:03:19',
			'modified' => '2015-03-11 13:32:58'
		),
		array(
			'id' => '2',
			'user_id' => '1',
			'country_id' => null,
			'type' => 'BILLING',
			'same_as' => '1',
			'street_address' => null,
			'suburb' => null,
			'postcode' => null,
			'state' => null,
			'created' => '2013-08-27 22:18:20',
			'modified' => '2015-03-11 13:32:58'
		),
		array(
			'id' => '62',
			'user_id' => '132',
			'country_id' => '13',
			'type' => 'CONTACT',
			'same_as' => '0',
			'street_address' => '20 George ST',
			'suburb' => 'Sydney',
			'postcode' => '2000',
			'state' => 'NSW',
			'created' => '2014-06-29 15:31:10',
			'modified' => '2015-02-25 22:18:01'
		),
		array(
			'id' => '63',
			'user_id' => '132',
			'country_id' => null,
			'type' => 'BILLING',
			'same_as' => '1',
			'street_address' => null,
			'suburb' => null,
			'postcode' => null,
			'state' => null,
			'created' => '2014-06-29 15:31:10',
			'modified' => '2015-02-25 22:18:01'
		),
	);

}
