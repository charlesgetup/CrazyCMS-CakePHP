<?php
/**
 * EmailMarketingSenderFixture
 *
 */
class EmailMarketingSenderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'sender_domain' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'email_marketing_user_id' => '203',
			'sender_domain' => 'aroundyou0.info'
		),
		array(
			'id' => '2',
			'email_marketing_user_id' => '203',
			'sender_domain' => 'aroundyou1.info'
		),
		array(
			'id' => '3',
			'email_marketing_user_id' => '203',
			'sender_domain' => 'aroundyou2.info'
		),
	);

}
