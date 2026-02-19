<?php
/**
 * UserFixture
 *
 */
App::uses('AuthComponent', 'Controller/Component');
class UserFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'security_email' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'token' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'abn_acn' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'group_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'last_login' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'username' => array('column' => 'email', 'unique' => 1),
			'last_login' => array('column' => 'last_login', 'unique' => 0),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0),
			'active' => array('column' => 'active', 'unique' => 0),
			'token' => array('column' => 'token', 'unique' => 0),
			'created' => array('column' => 'created', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public function init(){
		$this->records = array(
			array(
				'id' => '1',
				'parent_id' => null,
				'first_name' => 'Charles',
				'last_name' => 'Li',
				'email' => 'charles@crazycms.net',
				'security_email' => 'kerry-fly@163.com',
				'password' => AuthComponent::password('anyegongjue'),
				'token' => null,
				'phone' => '0413156097',
				'company' => '123',
				'abn_acn' => '',
				'group_id' => '1',
				'active' => 1,
				'last_login' => '2016-10-19 16:35:44',
				'created' => '2013-08-13 21:45:56',
				'modified' => '2016-10-19 16:35:44'
			),
			array(
				'id' => '132',
				'parent_id' => null,
				'first_name' => 'Belinda',
				'last_name' => 'Li',
				'email' => 'kerry-fly@163.com',
				'security_email' => 'lyf890@sohu.com',
				'password' => AuthComponent::password('123'),
				'token' => null,
				'phone' => '0404370509',
				'company' => '',
				'abn_acn' => '',
				'group_id' => '1',
				'active' => 1,
				'last_login' => '2016-10-19 21:29:30',
				'created' => '2014-06-29 15:31:10',
				'modified' => '2016-10-19 21:29:30'
			),
			array(
				'id' => '203',
				'parent_id' => '132',
				'first_name' => 'Belinda',
				'last_name' => 'Li',
				'email' => '1323kerry-fly@163.com',
				'security_email' => 'lyf890@sohu.com',
				'password' => AuthComponent::password('123'),
				'token' => null,
				'phone' => '0404370509',
				'company' => '',
				'abn_acn' => '',
				'group_id' => '4',
				'active' => 1,
				'last_login' => '2015-06-10 10:55:34',
				'created' => '2014-06-29 15:31:10',
				'modified' => '2015-06-10 10:55:34'
			),
			array(
				'id' => '235',
				'parent_id' => null,
				'first_name' => 'Email',
				'last_name' => 'User',
				'email' => 'charles.li@velosure.com.au',
				'security_email' => null,
				'password' => AuthComponent::password('123'),
				'token' => null,
				'phone' => null,
				'company' => null,
				'abn_acn' => null,
				'group_id' => '19',
				'active' => 1,
				'last_login' => null,
				'created' => '2017-10-19 16:36:35',
				'modified' => '2017-10-19 16:36:35'
			),
			array(
				'id' => '236',
				'parent_id' => '235',
				'first_name' => 'New',
				'last_name' => 'User',
				'email' => '2354charles.li@velosure.com.au',
				'security_email' => 'charles.li@velosure.com.au',
				'password' => AuthComponent::password('123'),
				'token' => null,
				'phone' => null,
				'company' => null,
				'abn_acn' => null,
				'group_id' => '4',
				'active' => 1,
				'last_login' => null,
				'created' => '2017-10-19 16:36:35',
				'modified' => '2017-10-19 16:36:35'
			),
			array(
				'id' => '216',
				'parent_id' => null,
				'first_name' => 'New',
				'last_name' => 'User',
				'email' => 'lyf890@sohu.com',
				'security_email' => null,
				'password' => AuthComponent::password('123'),
				'token' => 'c4067e27c5a8108af3ebdc852c2231f6',
				'phone' => null,
				'company' => null,
				'abn_acn' => null,
				'group_id' => '19',
				'active' => 0,
				'last_login' => null,
				'created' => '2016-10-19 16:36:35',
				'modified' => '2016-10-19 16:36:35'
			),
		);
		parent::init();
	}

}
