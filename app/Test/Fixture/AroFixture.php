<?php
/**
 * AroFixture
 *
 */
class AroFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0)
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
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '1',
			'alias' => '',
			'lft' => '1',
			'rght' => '22'
		),
		array(
			'id' => '2',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '2',
			'alias' => '',
			'lft' => '23',
			'rght' => '24'
		),
		array(
			'id' => '3',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '3',
			'alias' => '',
			'lft' => '25',
			'rght' => '26'
		),
		array(
			'id' => '4',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '4',
			'alias' => '',
			'lft' => '27',
			'rght' => '38'
		),
		array(
			'id' => '9',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '6',
			'alias' => '',
			'lft' => '39',
			'rght' => '40'
		),
		array(
			'id' => '10',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '7',
			'alias' => '',
			'lft' => '41',
			'rght' => '42'
		),
		array(
			'id' => '11',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '8',
			'alias' => '',
			'lft' => '43',
			'rght' => '44'
		),
		array(
			'id' => '12',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '9',
			'alias' => '',
			'lft' => '45',
			'rght' => '46'
		),
		array(
			'id' => '13',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '10',
			'alias' => '',
			'lft' => '47',
			'rght' => '48'
		),
		array(
			'id' => '14',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '11',
			'alias' => '',
			'lft' => '49',
			'rght' => '50'
		),
		array(
			'id' => '15',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '12',
			'alias' => '',
			'lft' => '51',
			'rght' => '52'
		),
		array(
			'id' => '16',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '13',
			'alias' => '',
			'lft' => '53',
			'rght' => '54'
		),
		array(
			'id' => '17',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '14',
			'alias' => '',
			'lft' => '55',
			'rght' => '56'
		),
		array(
			'id' => '18',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '15',
			'alias' => '',
			'lft' => '57',
			'rght' => '58'
		),
		array(
			'id' => '19',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '16',
			'alias' => '',
			'lft' => '59',
			'rght' => '60'
		),
		array(
			'id' => '20',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '17',
			'alias' => '',
			'lft' => '61',
			'rght' => '62'
		),
		array(
			'id' => '21',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '18',
			'alias' => '',
			'lft' => '63',
			'rght' => '64'
		),
		array(
			'id' => '38',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '19',
			'alias' => '',
			'lft' => '65',
			'rght' => '116'
		),
		array(
			'id' => '225',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '24',
			'alias' => null,
			'lft' => '117',
			'rght' => '118'
		),
		array(
			'id' => '226',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '25',
			'alias' => null,
			'lft' => '119',
			'rght' => '120'
		),
		array(
			'id' => '227',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '26',
			'alias' => null,
			'lft' => '121',
			'rght' => '122'
		),
		array(
			'id' => '228',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '27',
			'alias' => null,
			'lft' => '123',
			'rght' => '124'
		),
		array(
			'id' => '229',
			'parent_id' => '4',
			'model' => 'User',
			'foreign_key' => '133',
			'alias' => null,
			'lft' => '28',
			'rght' => '29'
		),
		array(
			'id' => '230',
			'parent_id' => '4',
			'model' => 'User',
			'foreign_key' => '175',
			'alias' => null,
			'lft' => '30',
			'rght' => '31'
		),
		array(
			'id' => '231',
			'parent_id' => '4',
			'model' => 'User',
			'foreign_key' => '203',
			'alias' => null,
			'lft' => '32',
			'rght' => '33'
		),
		array(
			'id' => '232',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '1',
			'alias' => null,
			'lft' => '2',
			'rght' => '3'
		),
		array(
			'id' => '233',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '132',
			'alias' => null,
			'lft' => '4',
			'rght' => '5'
		),
		array(
			'id' => '234',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '159',
			'alias' => null,
			'lft' => '6',
			'rght' => '7'
		),
		array(
			'id' => '235',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '179',
			'alias' => null,
			'lft' => '66',
			'rght' => '67'
		),
		array(
			'id' => '236',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '180',
			'alias' => null,
			'lft' => '68',
			'rght' => '69'
		),
		array(
			'id' => '237',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '181',
			'alias' => null,
			'lft' => '70',
			'rght' => '71'
		),
		array(
			'id' => '238',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '182',
			'alias' => null,
			'lft' => '72',
			'rght' => '73'
		),
		array(
			'id' => '239',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '183',
			'alias' => null,
			'lft' => '74',
			'rght' => '75'
		),
		array(
			'id' => '240',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '184',
			'alias' => null,
			'lft' => '76',
			'rght' => '77'
		),
		array(
			'id' => '241',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '185',
			'alias' => null,
			'lft' => '78',
			'rght' => '79'
		),
		array(
			'id' => '242',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '186',
			'alias' => null,
			'lft' => '80',
			'rght' => '81'
		),
		array(
			'id' => '243',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '187',
			'alias' => null,
			'lft' => '82',
			'rght' => '83'
		),
		array(
			'id' => '244',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '188',
			'alias' => null,
			'lft' => '84',
			'rght' => '85'
		),
		array(
			'id' => '245',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '189',
			'alias' => null,
			'lft' => '86',
			'rght' => '87'
		),
		array(
			'id' => '246',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '190',
			'alias' => null,
			'lft' => '88',
			'rght' => '89'
		),
		array(
			'id' => '247',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '191',
			'alias' => null,
			'lft' => '90',
			'rght' => '91'
		),
		array(
			'id' => '248',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '192',
			'alias' => null,
			'lft' => '92',
			'rght' => '93'
		),
		array(
			'id' => '249',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '193',
			'alias' => null,
			'lft' => '8',
			'rght' => '9'
		),
		array(
			'id' => '250',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '194',
			'alias' => null,
			'lft' => '10',
			'rght' => '11'
		),
		array(
			'id' => '251',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '195',
			'alias' => null,
			'lft' => '12',
			'rght' => '13'
		),
		array(
			'id' => '252',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '196',
			'alias' => null,
			'lft' => '14',
			'rght' => '15'
		),
		array(
			'id' => '253',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '197',
			'alias' => null,
			'lft' => '94',
			'rght' => '95'
		),
		array(
			'id' => '254',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '198',
			'alias' => null,
			'lft' => '96',
			'rght' => '97'
		),
		array(
			'id' => '255',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '199',
			'alias' => null,
			'lft' => '98',
			'rght' => '99'
		),
		array(
			'id' => '256',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '200',
			'alias' => null,
			'lft' => '100',
			'rght' => '101'
		),
		array(
			'id' => '257',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '201',
			'alias' => null,
			'lft' => '102',
			'rght' => '103'
		),
		array(
			'id' => '258',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '202',
			'alias' => null,
			'lft' => '104',
			'rght' => '105'
		),
		array(
			'id' => '259',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '204',
			'alias' => null,
			'lft' => '16',
			'rght' => '17'
		),
		array(
			'id' => '260',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '205',
			'alias' => null,
			'lft' => '106',
			'rght' => '107'
		),
		array(
			'id' => '261',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '206',
			'alias' => null,
			'lft' => '18',
			'rght' => '19'
		),
		array(
			'id' => '262',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '207',
			'alias' => null,
			'lft' => '108',
			'rght' => '109'
		),
		array(
			'id' => '263',
			'parent_id' => '1',
			'model' => 'User',
			'foreign_key' => '208',
			'alias' => null,
			'lft' => '20',
			'rght' => '21'
		),
		array(
			'id' => '264',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '125',
			'rght' => '126'
		),
		array(
			'id' => '265',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '127',
			'rght' => '128'
		),
		array(
			'id' => '266',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '129',
			'rght' => '130'
		),
		array(
			'id' => '267',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '131',
			'rght' => '132'
		),
		array(
			'id' => '268',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '133',
			'rght' => '134'
		),
		array(
			'id' => '269',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '135',
			'rght' => '136'
		),
		array(
			'id' => '270',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '137',
			'rght' => '138'
		),
		array(
			'id' => '271',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '139',
			'rght' => '140'
		),
		array(
			'id' => '272',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '141',
			'rght' => '142'
		),
		array(
			'id' => '273',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '143',
			'rght' => '144'
		),
		array(
			'id' => '275',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '145',
			'rght' => '146'
		),
		array(
			'id' => '276',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '147',
			'rght' => '148'
		),
		array(
			'id' => '278',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '5',
			'alias' => null,
			'lft' => '149',
			'rght' => '150'
		),
		array(
			'id' => '279',
			'parent_id' => '4',
			'model' => 'User',
			'foreign_key' => '236',
			'alias' => null,
			'lft' => '34',
			'rght' => '35'
		),
		array(
			'id' => '280',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '216',
			'alias' => null,
			'lft' => '110',
			'rght' => '111'
		),
		array(
			'id' => '281',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '235',
			'alias' => null,
			'lft' => '112',
			'rght' => '113'
		),
		array(
			'id' => '282',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '151',
			'rght' => '152'
		),
		array(
			'id' => '284',
			'parent_id' => '4',
			'model' => 'User',
			'foreign_key' => '238',
			'alias' => null,
			'lft' => '36',
			'rght' => '37'
		),
		array(
			'id' => '285',
			'parent_id' => '38',
			'model' => 'User',
			'foreign_key' => '237',
			'alias' => null,
			'lft' => '114',
			'rght' => '115'
		),
		array(
			'id' => '286',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '153',
			'rght' => '154'
		),
		array(
			'id' => '288',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '155',
			'rght' => '156'
		),
		array(
			'id' => '290',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '157',
			'rght' => '158'
		),
		array(
			'id' => '292',
			'parent_id' => null,
			'model' => 'Group',
			'foreign_key' => '20',
			'alias' => null,
			'lft' => '159',
			'rght' => '160'
		),
	);

}
