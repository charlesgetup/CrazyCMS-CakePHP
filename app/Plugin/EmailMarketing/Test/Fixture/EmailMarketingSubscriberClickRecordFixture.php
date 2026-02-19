<?php
/**
 * EmailMarketingSubscriberClickRecordFixture
 *
 */
class EmailMarketingSubscriberClickRecordFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_email_link_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_subscriber_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'email_link_id' => array('column' => 'email_marketing_email_link_id', 'unique' => 0),
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
