<?php
/**
 * EmailMarketingMailingListFixture
 *
 */
class EmailMarketingMailingListFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'EmailMarketingUser.id'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Mark as deleted'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => array('email_marketing_user_id', 'active'), 'unique' => 0),
			'deleted' => array('column' => 'deleted', 'unique' => 0)
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
			'email_marketing_user_id' => '20',
			'name' => 'Test List 1',
			'description' => '',
			'active' => 1,
			'deleted' => 0,
			'created' => '2014-07-22 15:29:57',
			'modified' => '2014-07-22 15:30:31'
		),
		array(
			'id' => '2',
			'email_marketing_user_id' => '20',
			'name' => 'Test 1',
			'description' => '',
			'active' => 0,
			'deleted' => 1,
			'created' => '2015-07-06 16:55:02',
			'modified' => '2015-07-06 17:17:59'
		),
		array(
			'id' => '3',
			'email_marketing_user_id' => '20',
			'name' => 'Test 2',
			'description' => '',
			'active' => 0,
			'deleted' => 1,
			'created' => '2015-07-06 17:01:42',
			'modified' => '2015-07-06 17:17:59'
		),
		array(
			'id' => '4',
			'email_marketing_user_id' => '20',
			'name' => 'Test 3',
			'description' => 'Test',
			'active' => 1,
			'deleted' => 0,
			'created' => '2015-08-19 12:57:22',
			'modified' => '2015-08-19 12:57:24'
		),
		array(
			'id' => '5',
			'email_marketing_user_id' => '20',
			'name' => 'Test 1',
			'description' => '123',
			'active' => 0,
			'deleted' => 1,
			'created' => '2015-08-19 12:42:40',
			'modified' => '2015-08-19 12:43:17'
		),
		array(
			'id' => '6',
			'email_marketing_user_id' => '21',
			'name' => 't22',
			'description' => '123',
			'active' => 1,
			'deleted' => 0,
			'created' => '2015-11-18 16:00:56',
			'modified' => '2015-11-18 16:01:13'
		),
	);

}
