<?php
/**
 * EmailMarketingTemplateFixture
 *
 */
class EmailMarketingTemplateFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'If this field is NULL, this means the template is a system template. System template can be used internally or for sale. Check for_sale field for more info.'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'txt_msg' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'html' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'markup_list' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2', 'key' => 'index'),
		'special_price' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2', 'key' => 'index', 'comment' => 'For promotion usage'),
		'for_sale' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Mark as deleted'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'for_sale' => array('column' => 'for_sale', 'unique' => 0),
			'user_id' => array('column' => 'email_marketing_user_id', 'unique' => 0),
			'price' => array('column' => 'price', 'unique' => 0),
			'created' => array('column' => 'created', 'unique' => 0),
			'special_price' => array('column' => 'special_price', 'unique' => 0),
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
	);

}
