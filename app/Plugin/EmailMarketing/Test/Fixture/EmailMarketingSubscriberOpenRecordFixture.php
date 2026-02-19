<?php
/**
 * EmailMarketingSubscriberOpenRecordFixture
 *
 */
class EmailMarketingSubscriberOpenRecordFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_statistic_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_subscriber_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'statistic_id' => array('column' => 'email_marketing_statistic_id', 'unique' => 0),
			'subscriber_id' => array('column' => 'email_marketing_subscriber_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
	);

}
