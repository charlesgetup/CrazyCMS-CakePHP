<?php
/**
 * EmailMarketingBlacklistedSubscriberFixture
 *
 */
class EmailMarketingBlacklistedSubscriberFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'EmailMarketingUser.id'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'email' => array('column' => 'email', 'unique' => 0),
			'user_id' => array('column' => 'email_marketing_user_id', 'unique' => 0)
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
			'email' => 'test@example.com',
			'created' => '2015-08-19 00:00:00'
		),
		array(
			'id' => '4',
			'email_marketing_user_id' => '20',
			'email' => 'test123@example.com',
			'created' => '2015-08-20 14:56:02'
		),
	);

}
