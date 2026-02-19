<?php
/**
 * EmailMarketingSubscriberFixture
 *
 */
class EmailMarketingSubscriberFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_list_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'excluded' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'unsubscribed' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Mark as deleted'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'email' => array('column' => 'email', 'unique' => 0),
			'excluded' => array('column' => 'excluded', 'unique' => 0),
			'list_id' => array('column' => 'email_marketing_list_id', 'unique' => 0),
			'unsubscribed' => array('column' => 'unsubscribed', 'unique' => 0),
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
			'email_marketing_list_id' => '1',
			'first_name' => 'Charles',
			'last_name' => 'Li',
			'email' => 'kerry-fly@163.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 0,
			'created' => '2014-07-22 15:30:55',
			'modified' => '2014-07-22 15:47:57'
		),
		array(
			'id' => '2',
			'email_marketing_list_id' => '1',
			'first_name' => 'Charles',
			'last_name' => 'Li',
			'email' => 'lyf890@sohu.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 0,
			'created' => '2014-07-22 15:47:57',
			'modified' => '2014-07-22 15:48:20'
		),
		array(
			'id' => '3',
			'email_marketing_list_id' => '1',
			'first_name' => 'Charles',
			'last_name' => 'Li',
			'email' => 'tomgreen.test123@gmail.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 0,
			'created' => '2014-07-22 15:48:20',
			'modified' => '2014-07-22 15:48:39'
		),
		array(
			'id' => '4',
			'email_marketing_list_id' => '1',
			'first_name' => 'Charles',
			'last_name' => 'Li',
			'email' => 'tomgreen.test123@outlook.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 0,
			'created' => '2014-07-22 15:48:39',
			'modified' => '2014-07-22 15:48:53'
		),
		array(
			'id' => '5',
			'email_marketing_list_id' => '1',
			'first_name' => 'Charles',
			'last_name' => 'Li',
			'email' => 'charlesli.yanfeng@yahoo.com.au',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 0,
			'created' => '2014-07-22 15:48:53',
			'modified' => '2014-07-22 15:49:11'
		),
		array(
			'id' => '6',
			'email_marketing_list_id' => '4',
			'first_name' => 'First name',
			'last_name' => 'Last name',
			'email' => '123@123.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 1,
			'created' => '2015-07-21 16:30:20',
			'modified' => '2015-07-21 16:29:55'
		),
		array(
			'id' => '7',
			'email_marketing_list_id' => '4',
			'first_name' => '1st',
			'last_name' => 'Real',
			'email' => '123@1234.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 1,
			'created' => '2015-08-10 12:03:41',
			'modified' => '2015-08-10 12:18:25'
		),
		array(
			'id' => '8',
			'email_marketing_list_id' => '4',
			'first_name' => 'To',
			'last_name' => 'del',
			'email' => '123@1231.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 1,
			'created' => '2015-08-10 12:20:19',
			'modified' => '2015-08-10 12:20:04'
		),
		array(
			'id' => '9',
			'email_marketing_list_id' => '4',
			'first_name' => 'Tom1',
			'last_name' => 'Green',
			'email' => 'test@example.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 0,
			'created' => '2015-08-11 17:00:35',
			'modified' => '2015-08-19 13:52:48'
		),
		array(
			'id' => '10',
			'email_marketing_list_id' => '6',
			'first_name' => 'Jim',
			'last_name' => 'Green',
			'email' => 'test@example.com',
			'excluded' => 0,
			'unsubscribed' => 0,
			'deleted' => 0,
			'created' => '2015-08-11 17:00:35',
			'modified' => '2015-08-19 13:52:48'
		),
	);

}
