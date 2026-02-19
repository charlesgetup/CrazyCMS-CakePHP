<?php
/**
 * EmailMarketingStatisticFixture
 *
 */
class EmailMarketingStatisticFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_campaign_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'invalid' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'duplicated' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'blacklisted' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'processed' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'forwarded' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PENDING', 'length' => 50, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PENDING, SENDING, SENT, SCHEDULED, FAILED, PAUSED, CANCELLED', 'charset' => 'utf8'),
		'send_start' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'send_end' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'status' => array('column' => 'status', 'unique' => 0)
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
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 18:21:52',
			'send_end' => null
		),
		array(
			'id' => '2',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 19:59:49',
			'send_end' => null
		),
		array(
			'id' => '3',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:00:52',
			'send_end' => null
		),
		array(
			'id' => '4',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:02:20',
			'send_end' => null
		),
		array(
			'id' => '5',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '0',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:23:30',
			'send_end' => null
		),
		array(
			'id' => '6',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:25:40',
			'send_end' => null
		),
		array(
			'id' => '7',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:28:36',
			'send_end' => null
		),
		array(
			'id' => '8',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:53:15',
			'send_end' => null
		),
		array(
			'id' => '9',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:55:40',
			'send_end' => null
		),
		array(
			'id' => '10',
			'email_marketing_campaign_id' => '1',
			'invalid' => '0',
			'duplicated' => '0',
			'blacklisted' => '0',
			'processed' => '5',
			'forwarded' => '0',
			'status' => 'SENT',
			'send_start' => '2014-07-22 20:57:18',
			'send_end' => null
		),
	);

}
