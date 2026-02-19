<?php
/**
 * GroupFixture
 *
 */
class GroupFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'name' => 'Admin',
			'created' => '2013-08-13 21:31:19',
			'modified' => '2013-09-03 14:31:08'
		),
		array(
			'id' => '2',
			'name' => 'Web Development Clients',
			'created' => '2013-08-01 21:41:50',
			'modified' => '2015-03-19 14:25:53'
		),
		array(
			'id' => '3',
			'name' => 'SEO Clients',
			'created' => '2013-08-01 21:43:36',
			'modified' => '2013-12-27 17:26:12'
		),
		array(
			'id' => '4',
			'name' => 'Email Marketing Clients',
			'created' => '2013-08-01 21:41:25',
			'modified' => '2013-12-30 11:34:36'
		),
		array(
			'id' => '5',
			'name' => 'Social Media Clients',
			'created' => '2013-08-28 09:52:57',
			'modified' => '2013-12-30 11:35:20'
		),
		array(
			'id' => '6',
			'name' => 'Other Services Clients',
			'created' => '2013-08-28 09:54:09',
			'modified' => '2013-12-27 17:23:26'
		),
		array(
			'id' => '7',
			'name' => 'Web Development Staff',
			'created' => '2013-08-28 10:07:50',
			'modified' => '2013-12-27 16:40:54'
		),
		array(
			'id' => '8',
			'name' => 'SEO Staff',
			'created' => '2013-08-28 10:08:03',
			'modified' => '2013-12-30 10:00:45'
		),
		array(
			'id' => '9',
			'name' => 'Email Marketing Staff',
			'created' => '2013-08-28 10:08:18',
			'modified' => '2013-12-27 15:48:46'
		),
		array(
			'id' => '10',
			'name' => 'Social Media Staff',
			'created' => '2013-08-28 10:08:43',
			'modified' => '2013-12-27 15:53:37'
		),
		array(
			'id' => '11',
			'name' => 'Other Services Staff',
			'created' => '2013-08-28 10:08:55',
			'modified' => '2013-12-27 17:03:41'
		),
		array(
			'id' => '12',
			'name' => 'Web Development Manager',
			'created' => '2013-08-28 10:09:07',
			'modified' => '2013-12-27 14:44:25'
		),
		array(
			'id' => '13',
			'name' => 'SEO Manager',
			'created' => '2013-08-28 10:09:19',
			'modified' => '2013-08-28 10:09:19'
		),
		array(
			'id' => '14',
			'name' => 'Email Marketing Manager',
			'created' => '2013-08-28 10:09:32',
			'modified' => '2013-08-28 10:09:32'
		),
		array(
			'id' => '15',
			'name' => 'Social Media Manager',
			'created' => '2013-08-28 10:09:43',
			'modified' => '2013-08-28 10:09:43'
		),
		array(
			'id' => '16',
			'name' => 'Other Services Manager',
			'created' => '2013-08-28 10:09:56',
			'modified' => '2013-08-28 10:09:56'
		),
		array(
			'id' => '17',
			'name' => 'Accounting Staff',
			'created' => '2013-08-28 10:10:08',
			'modified' => '2014-07-09 19:19:54'
		),
		array(
			'id' => '18',
			'name' => 'Accounting Manager',
			'created' => '2013-08-28 10:10:19',
			'modified' => '2014-07-09 19:20:10'
		),
		array(
			'id' => '19',
			'name' => 'Client',
			'created' => '2013-09-06 10:23:35',
			'modified' => '2013-09-06 10:23:35'
		),
	);

}
