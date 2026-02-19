<?php
/**
 * EmailMarketingPurchasedTemplateFixture
 *
 */
class EmailMarketingPurchasedTemplateFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_template_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2', 'comment' => 'Purchased price'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PURCHASED', 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PURCHASED,REFUNDED,GIFT', 'charset' => 'utf8'),
		'purchased_timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'status' => array('column' => 'status', 'unique' => 0),
			'user_id' => array('column' => 'email_marketing_user_id', 'unique' => 0),
			'template_id' => array('column' => 'email_marketing_template_id', 'unique' => 0)
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
