<?php
/**
 * ArosAcoFixture
 *
 */
class ArosAcoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'aco_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'aro_id' => array('column' => 'aro_id', 'unique' => 0),
			'aco_id' => array('column' => 'aco_id', 'unique' => 0)
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
			'aro_id' => '1',
			'aco_id' => '1',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2',
			'aro_id' => '2',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3',
			'aro_id' => '3',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '4',
			'aro_id' => '1',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '5',
			'aro_id' => '2',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '6',
			'aro_id' => '3',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '22',
			'aro_id' => '1',
			'aco_id' => '14',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '23',
			'aro_id' => '2',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '24',
			'aro_id' => '3',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '25',
			'aro_id' => '1',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '26',
			'aro_id' => '2',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '27',
			'aro_id' => '3',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '31',
			'aro_id' => '1',
			'aco_id' => '16',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '32',
			'aro_id' => '2',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '33',
			'aro_id' => '3',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '55',
			'aro_id' => '1',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '56',
			'aro_id' => '2',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '57',
			'aro_id' => '3',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '73',
			'aro_id' => '1',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '74',
			'aro_id' => '2',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '75',
			'aro_id' => '3',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '76',
			'aro_id' => '1',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '77',
			'aro_id' => '2',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '78',
			'aro_id' => '3',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '79',
			'aro_id' => '1',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '80',
			'aro_id' => '2',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '81',
			'aro_id' => '3',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '82',
			'aro_id' => '1',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '83',
			'aro_id' => '2',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '84',
			'aro_id' => '3',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '88',
			'aro_id' => '1',
			'aco_id' => '30',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '89',
			'aro_id' => '2',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '90',
			'aro_id' => '3',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '100',
			'aro_id' => '1',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '101',
			'aro_id' => '2',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '102',
			'aro_id' => '3',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '109',
			'aro_id' => '1',
			'aco_id' => '35',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '110',
			'aro_id' => '2',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '111',
			'aro_id' => '3',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '121',
			'aro_id' => '1',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '122',
			'aro_id' => '2',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '123',
			'aro_id' => '3',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '124',
			'aro_id' => '1',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '125',
			'aro_id' => '2',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '126',
			'aro_id' => '3',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '127',
			'aro_id' => '1',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '128',
			'aro_id' => '2',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '129',
			'aro_id' => '3',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '130',
			'aro_id' => '1',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '131',
			'aro_id' => '2',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '132',
			'aro_id' => '3',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '133',
			'aro_id' => '1',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '134',
			'aro_id' => '2',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '135',
			'aro_id' => '3',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '136',
			'aro_id' => '1',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '137',
			'aro_id' => '2',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '138',
			'aro_id' => '3',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '139',
			'aro_id' => '1',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '140',
			'aro_id' => '2',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '141',
			'aro_id' => '3',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '142',
			'aro_id' => '1',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '143',
			'aro_id' => '2',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '144',
			'aro_id' => '3',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '148',
			'aro_id' => '1',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '149',
			'aro_id' => '2',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '150',
			'aro_id' => '3',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '151',
			'aro_id' => '1',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '152',
			'aro_id' => '2',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '153',
			'aro_id' => '3',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '160',
			'aro_id' => '1',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '161',
			'aro_id' => '2',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '162',
			'aro_id' => '3',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '178',
			'aro_id' => '4',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '180',
			'aro_id' => '9',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '181',
			'aro_id' => '4',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '183',
			'aro_id' => '9',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '199',
			'aro_id' => '4',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '201',
			'aro_id' => '9',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '202',
			'aro_id' => '4',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '204',
			'aro_id' => '9',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '208',
			'aro_id' => '4',
			'aco_id' => '16',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '210',
			'aro_id' => '9',
			'aco_id' => '16',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '232',
			'aro_id' => '4',
			'aco_id' => '83',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '234',
			'aro_id' => '9',
			'aco_id' => '83',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '250',
			'aro_id' => '4',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '252',
			'aro_id' => '9',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '253',
			'aro_id' => '4',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '255',
			'aro_id' => '9',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '256',
			'aro_id' => '4',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '258',
			'aro_id' => '9',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '259',
			'aro_id' => '4',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '261',
			'aro_id' => '9',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '265',
			'aro_id' => '4',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '267',
			'aro_id' => '9',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '277',
			'aro_id' => '4',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '279',
			'aro_id' => '9',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '286',
			'aro_id' => '4',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '288',
			'aro_id' => '9',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '298',
			'aro_id' => '4',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '300',
			'aro_id' => '9',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '301',
			'aro_id' => '4',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '303',
			'aro_id' => '9',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '304',
			'aro_id' => '4',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '306',
			'aro_id' => '9',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '307',
			'aro_id' => '4',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '309',
			'aro_id' => '9',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '310',
			'aro_id' => '4',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '312',
			'aro_id' => '9',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '313',
			'aro_id' => '4',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '315',
			'aro_id' => '9',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '316',
			'aro_id' => '4',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '318',
			'aro_id' => '9',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '319',
			'aro_id' => '4',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '321',
			'aro_id' => '9',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '325',
			'aro_id' => '4',
			'aco_id' => '56',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '327',
			'aro_id' => '9',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '328',
			'aro_id' => '4',
			'aco_id' => '78',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '330',
			'aro_id' => '9',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '337',
			'aro_id' => '4',
			'aco_id' => '80',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '339',
			'aro_id' => '9',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '355',
			'aro_id' => '10',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '356',
			'aro_id' => '11',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '357',
			'aro_id' => '12',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '358',
			'aro_id' => '10',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '359',
			'aro_id' => '11',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '360',
			'aro_id' => '12',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '376',
			'aro_id' => '10',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '377',
			'aro_id' => '11',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '378',
			'aro_id' => '12',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '379',
			'aro_id' => '10',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '380',
			'aro_id' => '11',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '381',
			'aro_id' => '12',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '385',
			'aro_id' => '10',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '386',
			'aro_id' => '11',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '387',
			'aro_id' => '12',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '409',
			'aro_id' => '10',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '410',
			'aro_id' => '11',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '411',
			'aro_id' => '12',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '427',
			'aro_id' => '10',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '428',
			'aro_id' => '11',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '429',
			'aro_id' => '12',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '430',
			'aro_id' => '10',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '431',
			'aro_id' => '11',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '432',
			'aro_id' => '12',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '433',
			'aro_id' => '10',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '434',
			'aro_id' => '11',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '435',
			'aro_id' => '12',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '436',
			'aro_id' => '10',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '437',
			'aro_id' => '11',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '438',
			'aro_id' => '12',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '442',
			'aro_id' => '10',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '443',
			'aro_id' => '11',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '444',
			'aro_id' => '12',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '454',
			'aro_id' => '10',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '455',
			'aro_id' => '11',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '456',
			'aro_id' => '12',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '463',
			'aro_id' => '10',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '464',
			'aro_id' => '11',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '465',
			'aro_id' => '12',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '475',
			'aro_id' => '10',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '476',
			'aro_id' => '11',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '477',
			'aro_id' => '12',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '478',
			'aro_id' => '10',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '479',
			'aro_id' => '11',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '480',
			'aro_id' => '12',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '481',
			'aro_id' => '10',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '482',
			'aro_id' => '11',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '483',
			'aro_id' => '12',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '484',
			'aro_id' => '10',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '485',
			'aro_id' => '11',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '486',
			'aro_id' => '12',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '487',
			'aro_id' => '10',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '488',
			'aro_id' => '11',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '489',
			'aro_id' => '12',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '490',
			'aro_id' => '10',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '491',
			'aro_id' => '11',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '492',
			'aro_id' => '12',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '493',
			'aro_id' => '10',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '494',
			'aro_id' => '11',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '495',
			'aro_id' => '12',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '496',
			'aro_id' => '10',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '497',
			'aro_id' => '11',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '498',
			'aro_id' => '12',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '502',
			'aro_id' => '10',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '503',
			'aro_id' => '11',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '504',
			'aro_id' => '12',
			'aco_id' => '56',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '505',
			'aro_id' => '10',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '506',
			'aro_id' => '11',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '507',
			'aro_id' => '12',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '514',
			'aro_id' => '10',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '515',
			'aro_id' => '11',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '516',
			'aro_id' => '12',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '532',
			'aro_id' => '13',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '533',
			'aro_id' => '14',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '534',
			'aro_id' => '15',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '535',
			'aro_id' => '13',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '536',
			'aro_id' => '14',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '537',
			'aro_id' => '15',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '553',
			'aro_id' => '13',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '554',
			'aro_id' => '14',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '555',
			'aro_id' => '15',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '556',
			'aro_id' => '13',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '557',
			'aro_id' => '14',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '558',
			'aro_id' => '15',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '562',
			'aro_id' => '13',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '563',
			'aro_id' => '14',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '564',
			'aro_id' => '15',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '586',
			'aro_id' => '13',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '587',
			'aro_id' => '14',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '588',
			'aro_id' => '15',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '604',
			'aro_id' => '13',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '605',
			'aro_id' => '14',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '606',
			'aro_id' => '15',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '607',
			'aro_id' => '13',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '608',
			'aro_id' => '14',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '609',
			'aro_id' => '15',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '610',
			'aro_id' => '13',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '611',
			'aro_id' => '14',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '612',
			'aro_id' => '15',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '613',
			'aro_id' => '13',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '614',
			'aro_id' => '14',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '615',
			'aro_id' => '15',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '619',
			'aro_id' => '13',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '620',
			'aro_id' => '14',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '621',
			'aro_id' => '15',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '631',
			'aro_id' => '13',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '632',
			'aro_id' => '14',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '633',
			'aro_id' => '15',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '640',
			'aro_id' => '13',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '641',
			'aro_id' => '14',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '642',
			'aro_id' => '15',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '652',
			'aro_id' => '13',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '653',
			'aro_id' => '14',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '654',
			'aro_id' => '15',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '655',
			'aro_id' => '13',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '656',
			'aro_id' => '14',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '657',
			'aro_id' => '15',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '658',
			'aro_id' => '13',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '659',
			'aro_id' => '14',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '660',
			'aro_id' => '15',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '661',
			'aro_id' => '13',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '662',
			'aro_id' => '14',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '663',
			'aro_id' => '15',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '664',
			'aro_id' => '13',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '665',
			'aro_id' => '14',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '666',
			'aro_id' => '15',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '667',
			'aro_id' => '13',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '668',
			'aro_id' => '14',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '669',
			'aro_id' => '15',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '670',
			'aro_id' => '13',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '671',
			'aro_id' => '14',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '672',
			'aro_id' => '15',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '673',
			'aro_id' => '13',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '674',
			'aro_id' => '14',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '675',
			'aro_id' => '15',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '679',
			'aro_id' => '13',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '680',
			'aro_id' => '14',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '681',
			'aro_id' => '15',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '682',
			'aro_id' => '13',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '683',
			'aro_id' => '14',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '684',
			'aro_id' => '15',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '691',
			'aro_id' => '13',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '692',
			'aro_id' => '14',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '693',
			'aro_id' => '15',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '709',
			'aro_id' => '16',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '710',
			'aro_id' => '17',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '711',
			'aro_id' => '18',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '712',
			'aro_id' => '16',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '713',
			'aro_id' => '17',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '714',
			'aro_id' => '18',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '730',
			'aro_id' => '16',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '731',
			'aro_id' => '17',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '732',
			'aro_id' => '18',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '733',
			'aro_id' => '16',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '734',
			'aro_id' => '17',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '735',
			'aro_id' => '18',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '739',
			'aro_id' => '16',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '740',
			'aro_id' => '17',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '741',
			'aro_id' => '18',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '763',
			'aro_id' => '16',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '764',
			'aro_id' => '17',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '765',
			'aro_id' => '18',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '781',
			'aro_id' => '16',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '782',
			'aro_id' => '17',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '783',
			'aro_id' => '18',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '784',
			'aro_id' => '16',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '785',
			'aro_id' => '17',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '786',
			'aro_id' => '18',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '787',
			'aro_id' => '16',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '788',
			'aro_id' => '17',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '789',
			'aro_id' => '18',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '790',
			'aro_id' => '16',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '791',
			'aro_id' => '17',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '792',
			'aro_id' => '18',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '796',
			'aro_id' => '16',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '797',
			'aro_id' => '17',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '798',
			'aro_id' => '18',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '808',
			'aro_id' => '16',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '809',
			'aro_id' => '17',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '810',
			'aro_id' => '18',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '817',
			'aro_id' => '16',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '818',
			'aro_id' => '17',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '819',
			'aro_id' => '18',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '829',
			'aro_id' => '16',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '830',
			'aro_id' => '17',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '831',
			'aro_id' => '18',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '832',
			'aro_id' => '16',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '833',
			'aro_id' => '17',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '834',
			'aro_id' => '18',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '835',
			'aro_id' => '16',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '836',
			'aro_id' => '17',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '837',
			'aro_id' => '18',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '838',
			'aro_id' => '16',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '839',
			'aro_id' => '17',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '840',
			'aro_id' => '18',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '841',
			'aro_id' => '16',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '842',
			'aro_id' => '17',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '843',
			'aro_id' => '18',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '844',
			'aro_id' => '16',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '845',
			'aro_id' => '17',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '846',
			'aro_id' => '18',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '847',
			'aro_id' => '16',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '848',
			'aro_id' => '17',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '849',
			'aro_id' => '18',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '850',
			'aro_id' => '16',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '851',
			'aro_id' => '17',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '852',
			'aro_id' => '18',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '856',
			'aro_id' => '16',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '857',
			'aro_id' => '17',
			'aco_id' => '56',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '858',
			'aro_id' => '18',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '859',
			'aro_id' => '16',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '860',
			'aro_id' => '17',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '861',
			'aro_id' => '18',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '868',
			'aro_id' => '16',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '869',
			'aro_id' => '17',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '870',
			'aro_id' => '18',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '886',
			'aro_id' => '19',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '887',
			'aro_id' => '20',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '888',
			'aro_id' => '21',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '889',
			'aro_id' => '19',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '890',
			'aro_id' => '20',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '891',
			'aro_id' => '21',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '907',
			'aro_id' => '19',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '908',
			'aro_id' => '20',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '909',
			'aro_id' => '21',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '910',
			'aro_id' => '19',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '911',
			'aro_id' => '20',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '912',
			'aro_id' => '21',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '916',
			'aro_id' => '19',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '917',
			'aro_id' => '20',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '918',
			'aro_id' => '21',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '940',
			'aro_id' => '19',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '941',
			'aro_id' => '20',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '942',
			'aro_id' => '21',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '958',
			'aro_id' => '19',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '959',
			'aro_id' => '20',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '960',
			'aro_id' => '21',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '961',
			'aro_id' => '19',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '962',
			'aro_id' => '20',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '963',
			'aro_id' => '21',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '964',
			'aro_id' => '19',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '965',
			'aro_id' => '20',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '966',
			'aro_id' => '21',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '967',
			'aro_id' => '19',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '968',
			'aro_id' => '20',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '969',
			'aro_id' => '21',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '973',
			'aro_id' => '19',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '974',
			'aro_id' => '20',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '975',
			'aro_id' => '21',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '985',
			'aro_id' => '19',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '986',
			'aro_id' => '20',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '987',
			'aro_id' => '21',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '994',
			'aro_id' => '19',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '995',
			'aro_id' => '20',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '996',
			'aro_id' => '21',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1006',
			'aro_id' => '19',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1007',
			'aro_id' => '20',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1008',
			'aro_id' => '21',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1009',
			'aro_id' => '19',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1010',
			'aro_id' => '20',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1011',
			'aro_id' => '21',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1012',
			'aro_id' => '19',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1013',
			'aro_id' => '20',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1014',
			'aro_id' => '21',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1015',
			'aro_id' => '19',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1016',
			'aro_id' => '20',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1017',
			'aro_id' => '21',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1018',
			'aro_id' => '19',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1019',
			'aro_id' => '20',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1020',
			'aro_id' => '21',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1021',
			'aro_id' => '19',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1022',
			'aro_id' => '20',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1023',
			'aro_id' => '21',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1024',
			'aro_id' => '19',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1025',
			'aro_id' => '20',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1026',
			'aro_id' => '21',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1027',
			'aro_id' => '19',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1028',
			'aro_id' => '20',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1029',
			'aro_id' => '21',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1033',
			'aro_id' => '19',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1034',
			'aro_id' => '20',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1035',
			'aro_id' => '21',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1036',
			'aro_id' => '19',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1037',
			'aro_id' => '20',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1038',
			'aro_id' => '21',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1045',
			'aro_id' => '19',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1046',
			'aro_id' => '20',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1047',
			'aro_id' => '21',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1063',
			'aro_id' => '38',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1064',
			'aro_id' => '38',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1070',
			'aro_id' => '38',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1071',
			'aro_id' => '38',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1073',
			'aro_id' => '38',
			'aco_id' => '16',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1081',
			'aro_id' => '38',
			'aco_id' => '83',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1087',
			'aro_id' => '38',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1088',
			'aro_id' => '38',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1089',
			'aro_id' => '38',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1090',
			'aro_id' => '38',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1092',
			'aro_id' => '38',
			'aco_id' => '30',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1096',
			'aro_id' => '38',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1099',
			'aro_id' => '38',
			'aco_id' => '35',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1103',
			'aro_id' => '38',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1104',
			'aro_id' => '38',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1105',
			'aro_id' => '38',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1106',
			'aro_id' => '38',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1107',
			'aro_id' => '38',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1108',
			'aro_id' => '38',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1109',
			'aro_id' => '38',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1110',
			'aro_id' => '38',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1112',
			'aro_id' => '38',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1113',
			'aro_id' => '38',
			'aco_id' => '78',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1116',
			'aro_id' => '38',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1119',
			'aro_id' => '1',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1120',
			'aro_id' => '2',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1121',
			'aro_id' => '3',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1122',
			'aro_id' => '4',
			'aco_id' => '101',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1124',
			'aro_id' => '9',
			'aco_id' => '101',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1125',
			'aro_id' => '10',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1126',
			'aro_id' => '11',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1127',
			'aro_id' => '12',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1128',
			'aro_id' => '13',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1129',
			'aro_id' => '14',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1130',
			'aro_id' => '15',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1131',
			'aro_id' => '16',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1132',
			'aro_id' => '17',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1133',
			'aro_id' => '18',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1134',
			'aro_id' => '19',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1135',
			'aro_id' => '20',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1136',
			'aro_id' => '21',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1137',
			'aro_id' => '38',
			'aco_id' => '101',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1138',
			'aro_id' => '1',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1139',
			'aro_id' => '2',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1140',
			'aro_id' => '3',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1141',
			'aro_id' => '4',
			'aco_id' => '102',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1143',
			'aro_id' => '9',
			'aco_id' => '102',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1144',
			'aro_id' => '10',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1145',
			'aro_id' => '11',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1146',
			'aro_id' => '12',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1147',
			'aro_id' => '13',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1148',
			'aro_id' => '14',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1149',
			'aro_id' => '15',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1150',
			'aro_id' => '16',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1151',
			'aro_id' => '17',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1152',
			'aro_id' => '18',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1153',
			'aro_id' => '19',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1154',
			'aro_id' => '20',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1155',
			'aro_id' => '21',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1156',
			'aro_id' => '38',
			'aco_id' => '102',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1157',
			'aro_id' => '1',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1158',
			'aro_id' => '2',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1159',
			'aro_id' => '3',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1160',
			'aro_id' => '4',
			'aco_id' => '104',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1162',
			'aro_id' => '9',
			'aco_id' => '104',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1163',
			'aro_id' => '10',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1164',
			'aro_id' => '11',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1165',
			'aro_id' => '12',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1166',
			'aro_id' => '13',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1167',
			'aro_id' => '14',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1168',
			'aro_id' => '15',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1169',
			'aro_id' => '16',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1170',
			'aro_id' => '17',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1171',
			'aro_id' => '18',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1172',
			'aro_id' => '19',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1173',
			'aro_id' => '20',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1174',
			'aro_id' => '21',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1175',
			'aro_id' => '38',
			'aco_id' => '104',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1231',
			'aro_id' => '1',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1232',
			'aro_id' => '2',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1233',
			'aro_id' => '3',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1249',
			'aro_id' => '1',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1250',
			'aro_id' => '2',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1251',
			'aro_id' => '3',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1276',
			'aro_id' => '1',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1277',
			'aro_id' => '2',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1278',
			'aro_id' => '3',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1294',
			'aro_id' => '1',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1295',
			'aro_id' => '2',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1296',
			'aro_id' => '3',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1312',
			'aro_id' => '1',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1313',
			'aro_id' => '2',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1314',
			'aro_id' => '3',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1336',
			'aro_id' => '1',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1337',
			'aro_id' => '2',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1338',
			'aro_id' => '3',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1354',
			'aro_id' => '1',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1355',
			'aro_id' => '2',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1356',
			'aro_id' => '3',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1384',
			'aro_id' => '1',
			'aco_id' => '165',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1385',
			'aro_id' => '2',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1386',
			'aro_id' => '3',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1402',
			'aro_id' => '4',
			'aco_id' => '108',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1404',
			'aro_id' => '9',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1420',
			'aro_id' => '4',
			'aco_id' => '111',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1422',
			'aro_id' => '9',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1447',
			'aro_id' => '4',
			'aco_id' => '114',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1449',
			'aro_id' => '9',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1465',
			'aro_id' => '4',
			'aco_id' => '131',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1467',
			'aro_id' => '9',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1483',
			'aro_id' => '4',
			'aco_id' => '137',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1485',
			'aro_id' => '9',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1507',
			'aro_id' => '4',
			'aco_id' => '143',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1509',
			'aro_id' => '9',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1525',
			'aro_id' => '4',
			'aco_id' => '153',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1527',
			'aro_id' => '9',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1555',
			'aro_id' => '4',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1557',
			'aro_id' => '9',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1573',
			'aro_id' => '10',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1574',
			'aro_id' => '11',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1575',
			'aro_id' => '12',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1591',
			'aro_id' => '10',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1592',
			'aro_id' => '11',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1593',
			'aro_id' => '12',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1618',
			'aro_id' => '10',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1619',
			'aro_id' => '11',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1620',
			'aro_id' => '12',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1636',
			'aro_id' => '10',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1637',
			'aro_id' => '11',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1638',
			'aro_id' => '12',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1654',
			'aro_id' => '10',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1655',
			'aro_id' => '11',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1656',
			'aro_id' => '12',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1678',
			'aro_id' => '10',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1679',
			'aro_id' => '11',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1680',
			'aro_id' => '12',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1696',
			'aro_id' => '10',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1697',
			'aro_id' => '11',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1698',
			'aro_id' => '12',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1726',
			'aro_id' => '10',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1727',
			'aro_id' => '11',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1728',
			'aro_id' => '12',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1745',
			'aro_id' => '13',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1746',
			'aro_id' => '14',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1747',
			'aro_id' => '15',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1763',
			'aro_id' => '13',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1764',
			'aro_id' => '14',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1765',
			'aro_id' => '15',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1790',
			'aro_id' => '13',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1791',
			'aro_id' => '14',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1792',
			'aro_id' => '15',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1808',
			'aro_id' => '13',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1809',
			'aro_id' => '14',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1810',
			'aro_id' => '15',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1826',
			'aro_id' => '13',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1827',
			'aro_id' => '14',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1828',
			'aro_id' => '15',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1850',
			'aro_id' => '13',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1851',
			'aro_id' => '14',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1852',
			'aro_id' => '15',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1868',
			'aro_id' => '13',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1869',
			'aro_id' => '14',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1870',
			'aro_id' => '15',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1898',
			'aro_id' => '13',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1899',
			'aro_id' => '14',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1900',
			'aro_id' => '15',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '1919',
			'aro_id' => '16',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1920',
			'aro_id' => '17',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1921',
			'aro_id' => '18',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1937',
			'aro_id' => '16',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1938',
			'aro_id' => '17',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1939',
			'aro_id' => '18',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1964',
			'aro_id' => '16',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1965',
			'aro_id' => '17',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1966',
			'aro_id' => '18',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1982',
			'aro_id' => '16',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1983',
			'aro_id' => '17',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '1984',
			'aro_id' => '18',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2000',
			'aro_id' => '16',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2001',
			'aro_id' => '17',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2002',
			'aro_id' => '18',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2024',
			'aro_id' => '16',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2025',
			'aro_id' => '17',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2026',
			'aro_id' => '18',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2042',
			'aro_id' => '16',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2043',
			'aro_id' => '17',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2044',
			'aro_id' => '18',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2072',
			'aro_id' => '16',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2073',
			'aro_id' => '17',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2074',
			'aro_id' => '18',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2093',
			'aro_id' => '19',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2094',
			'aro_id' => '20',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2095',
			'aro_id' => '21',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2111',
			'aro_id' => '19',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2112',
			'aro_id' => '20',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2113',
			'aro_id' => '21',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2138',
			'aro_id' => '19',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2139',
			'aro_id' => '20',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2140',
			'aro_id' => '21',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2156',
			'aro_id' => '19',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2157',
			'aro_id' => '20',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2158',
			'aro_id' => '21',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2174',
			'aro_id' => '19',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2175',
			'aro_id' => '20',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2176',
			'aro_id' => '21',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2198',
			'aro_id' => '19',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2199',
			'aro_id' => '20',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2200',
			'aro_id' => '21',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2216',
			'aro_id' => '19',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2217',
			'aro_id' => '20',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2218',
			'aro_id' => '21',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2246',
			'aro_id' => '19',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2247',
			'aro_id' => '20',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2248',
			'aro_id' => '21',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2261',
			'aro_id' => '38',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2267',
			'aro_id' => '38',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2276',
			'aro_id' => '38',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2282',
			'aro_id' => '38',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2288',
			'aro_id' => '38',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2296',
			'aro_id' => '38',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2302',
			'aro_id' => '38',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2312',
			'aro_id' => '38',
			'aco_id' => '165',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2316',
			'aro_id' => '4',
			'aco_id' => '169',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2318',
			'aro_id' => '9',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2334',
			'aro_id' => '38',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2340',
			'aro_id' => '38',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2346',
			'aro_id' => '19',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2347',
			'aro_id' => '20',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2348',
			'aro_id' => '21',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2364',
			'aro_id' => '19',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2365',
			'aro_id' => '20',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2366',
			'aro_id' => '21',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2382',
			'aro_id' => '16',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2383',
			'aro_id' => '17',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2384',
			'aro_id' => '18',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2400',
			'aro_id' => '16',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2401',
			'aro_id' => '17',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2402',
			'aro_id' => '18',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2418',
			'aro_id' => '13',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2419',
			'aro_id' => '14',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2420',
			'aro_id' => '15',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2436',
			'aro_id' => '13',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2437',
			'aro_id' => '14',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2438',
			'aro_id' => '15',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2454',
			'aro_id' => '10',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2455',
			'aro_id' => '11',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2456',
			'aro_id' => '12',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2472',
			'aro_id' => '10',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2473',
			'aro_id' => '11',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2474',
			'aro_id' => '12',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2490',
			'aro_id' => '4',
			'aco_id' => '200',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2492',
			'aro_id' => '9',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2508',
			'aro_id' => '1',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2509',
			'aro_id' => '2',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2510',
			'aro_id' => '3',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2526',
			'aro_id' => '1',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2527',
			'aro_id' => '2',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2528',
			'aro_id' => '3',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2544',
			'aro_id' => '38',
			'aco_id' => '225',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2545',
			'aro_id' => '38',
			'aco_id' => '226',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2546',
			'aro_id' => '38',
			'aco_id' => '227',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2547',
			'aro_id' => '38',
			'aco_id' => '228',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2550',
			'aro_id' => '38',
			'aco_id' => '207',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2551',
			'aro_id' => '38',
			'aco_id' => '208',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2553',
			'aro_id' => '38',
			'aco_id' => '210',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2562',
			'aro_id' => '38',
			'aco_id' => '216',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2563',
			'aro_id' => '38',
			'aco_id' => '346',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '2564',
			'aro_id' => '38',
			'aco_id' => '291',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2565',
			'aro_id' => '38',
			'aco_id' => '314',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2566',
			'aro_id' => '38',
			'aco_id' => '316',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2567',
			'aro_id' => '38',
			'aco_id' => '239',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2571',
			'aro_id' => '1',
			'aco_id' => '274',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2572',
			'aro_id' => '2',
			'aco_id' => '274',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2573',
			'aro_id' => '3',
			'aco_id' => '274',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2574',
			'aro_id' => '1',
			'aco_id' => '275',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2575',
			'aro_id' => '2',
			'aco_id' => '275',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2576',
			'aro_id' => '3',
			'aco_id' => '275',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2577',
			'aro_id' => '1',
			'aco_id' => '276',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2578',
			'aro_id' => '2',
			'aco_id' => '276',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2579',
			'aro_id' => '3',
			'aco_id' => '276',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2580',
			'aro_id' => '1',
			'aco_id' => '277',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2581',
			'aro_id' => '2',
			'aco_id' => '277',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2582',
			'aro_id' => '3',
			'aco_id' => '277',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2583',
			'aro_id' => '1',
			'aco_id' => '358',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2584',
			'aro_id' => '2',
			'aco_id' => '358',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2585',
			'aro_id' => '3',
			'aco_id' => '358',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2586',
			'aro_id' => '1',
			'aco_id' => '363',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2587',
			'aro_id' => '2',
			'aco_id' => '363',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2588',
			'aro_id' => '3',
			'aco_id' => '363',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2595',
			'aro_id' => '1',
			'aco_id' => '262',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2596',
			'aro_id' => '2',
			'aco_id' => '262',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2597',
			'aro_id' => '3',
			'aco_id' => '262',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2598',
			'aro_id' => '1',
			'aco_id' => '263',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2599',
			'aro_id' => '2',
			'aco_id' => '263',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2600',
			'aro_id' => '3',
			'aco_id' => '263',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2601',
			'aro_id' => '1',
			'aco_id' => '264',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2602',
			'aro_id' => '2',
			'aco_id' => '264',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2603',
			'aro_id' => '3',
			'aco_id' => '264',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2604',
			'aro_id' => '1',
			'aco_id' => '265',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2605',
			'aro_id' => '2',
			'aco_id' => '265',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2606',
			'aro_id' => '3',
			'aco_id' => '265',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2607',
			'aro_id' => '1',
			'aco_id' => '266',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2608',
			'aro_id' => '2',
			'aco_id' => '266',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2609',
			'aro_id' => '3',
			'aco_id' => '266',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2610',
			'aro_id' => '1',
			'aco_id' => '354',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2611',
			'aro_id' => '2',
			'aco_id' => '354',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2612',
			'aro_id' => '3',
			'aco_id' => '354',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2613',
			'aro_id' => '1',
			'aco_id' => '355',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2614',
			'aro_id' => '2',
			'aco_id' => '355',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2615',
			'aro_id' => '3',
			'aco_id' => '355',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2616',
			'aro_id' => '1',
			'aco_id' => '357',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2617',
			'aro_id' => '2',
			'aco_id' => '357',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2618',
			'aro_id' => '3',
			'aco_id' => '357',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2619',
			'aro_id' => '1',
			'aco_id' => '364',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2620',
			'aro_id' => '2',
			'aco_id' => '364',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2621',
			'aro_id' => '3',
			'aco_id' => '364',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2625',
			'aro_id' => '1',
			'aco_id' => '233',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2626',
			'aro_id' => '2',
			'aco_id' => '233',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2627',
			'aro_id' => '3',
			'aco_id' => '233',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2631',
			'aro_id' => '1',
			'aco_id' => '272',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2632',
			'aro_id' => '2',
			'aco_id' => '272',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2633',
			'aro_id' => '3',
			'aco_id' => '272',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2637',
			'aro_id' => '1',
			'aco_id' => '273',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2638',
			'aro_id' => '2',
			'aco_id' => '273',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2639',
			'aro_id' => '3',
			'aco_id' => '273',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2640',
			'aro_id' => '1',
			'aco_id' => '225',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2641',
			'aro_id' => '2',
			'aco_id' => '225',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2642',
			'aro_id' => '3',
			'aco_id' => '225',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2643',
			'aro_id' => '1',
			'aco_id' => '226',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2644',
			'aro_id' => '2',
			'aco_id' => '226',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2645',
			'aro_id' => '3',
			'aco_id' => '226',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2646',
			'aro_id' => '1',
			'aco_id' => '227',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2647',
			'aro_id' => '2',
			'aco_id' => '227',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2648',
			'aro_id' => '3',
			'aco_id' => '227',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2649',
			'aro_id' => '1',
			'aco_id' => '228',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2650',
			'aro_id' => '2',
			'aco_id' => '228',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2651',
			'aro_id' => '3',
			'aco_id' => '228',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2652',
			'aro_id' => '1',
			'aco_id' => '239',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2653',
			'aro_id' => '2',
			'aco_id' => '239',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2654',
			'aro_id' => '3',
			'aco_id' => '239',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2664',
			'aro_id' => '1',
			'aco_id' => '293',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2665',
			'aro_id' => '2',
			'aco_id' => '293',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2666',
			'aro_id' => '3',
			'aco_id' => '293',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2673',
			'aro_id' => '1',
			'aco_id' => '300',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2674',
			'aro_id' => '2',
			'aco_id' => '300',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2675',
			'aro_id' => '3',
			'aco_id' => '300',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2676',
			'aro_id' => '1',
			'aco_id' => '301',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2677',
			'aro_id' => '2',
			'aco_id' => '301',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2678',
			'aro_id' => '3',
			'aco_id' => '301',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2679',
			'aro_id' => '1',
			'aco_id' => '302',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2680',
			'aro_id' => '2',
			'aco_id' => '302',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2681',
			'aro_id' => '3',
			'aco_id' => '302',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2682',
			'aro_id' => '1',
			'aco_id' => '303',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2683',
			'aro_id' => '2',
			'aco_id' => '303',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2684',
			'aro_id' => '3',
			'aco_id' => '303',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2685',
			'aro_id' => '1',
			'aco_id' => '304',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2686',
			'aro_id' => '2',
			'aco_id' => '304',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2687',
			'aro_id' => '3',
			'aco_id' => '304',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2688',
			'aro_id' => '1',
			'aco_id' => '305',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2689',
			'aro_id' => '2',
			'aco_id' => '305',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2690',
			'aro_id' => '3',
			'aco_id' => '305',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2694',
			'aro_id' => '1',
			'aco_id' => '295',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2695',
			'aro_id' => '2',
			'aco_id' => '295',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2696',
			'aro_id' => '3',
			'aco_id' => '295',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2697',
			'aro_id' => '1',
			'aco_id' => '296',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2698',
			'aro_id' => '2',
			'aco_id' => '296',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2699',
			'aro_id' => '3',
			'aco_id' => '296',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2700',
			'aro_id' => '1',
			'aco_id' => '297',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2701',
			'aro_id' => '2',
			'aco_id' => '297',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2702',
			'aro_id' => '3',
			'aco_id' => '297',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2703',
			'aro_id' => '1',
			'aco_id' => '298',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2704',
			'aro_id' => '2',
			'aco_id' => '298',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2705',
			'aro_id' => '3',
			'aco_id' => '298',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2706',
			'aro_id' => '1',
			'aco_id' => '299',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2707',
			'aro_id' => '2',
			'aco_id' => '299',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2708',
			'aro_id' => '3',
			'aco_id' => '299',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2712',
			'aro_id' => '1',
			'aco_id' => '320',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2713',
			'aro_id' => '2',
			'aco_id' => '320',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2714',
			'aro_id' => '3',
			'aco_id' => '320',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2715',
			'aro_id' => '1',
			'aco_id' => '321',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2716',
			'aro_id' => '2',
			'aco_id' => '321',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2717',
			'aro_id' => '3',
			'aco_id' => '321',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2718',
			'aro_id' => '1',
			'aco_id' => '322',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2719',
			'aro_id' => '2',
			'aco_id' => '322',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2720',
			'aro_id' => '3',
			'aco_id' => '322',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2721',
			'aro_id' => '1',
			'aco_id' => '323',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2722',
			'aro_id' => '2',
			'aco_id' => '323',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2723',
			'aro_id' => '3',
			'aco_id' => '323',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2724',
			'aro_id' => '1',
			'aco_id' => '324',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2725',
			'aro_id' => '2',
			'aco_id' => '324',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2726',
			'aro_id' => '3',
			'aco_id' => '324',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2727',
			'aro_id' => '1',
			'aco_id' => '325',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2728',
			'aro_id' => '2',
			'aco_id' => '325',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2729',
			'aro_id' => '3',
			'aco_id' => '325',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2733',
			'aro_id' => '1',
			'aco_id' => '327',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2734',
			'aro_id' => '2',
			'aco_id' => '327',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2735',
			'aro_id' => '3',
			'aco_id' => '327',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2739',
			'aro_id' => '1',
			'aco_id' => '333',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2740',
			'aro_id' => '2',
			'aco_id' => '333',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2741',
			'aro_id' => '3',
			'aco_id' => '333',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2742',
			'aro_id' => '1',
			'aco_id' => '334',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2743',
			'aro_id' => '2',
			'aco_id' => '334',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2744',
			'aro_id' => '3',
			'aco_id' => '334',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2745',
			'aro_id' => '1',
			'aco_id' => '335',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2746',
			'aro_id' => '2',
			'aco_id' => '335',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2747',
			'aro_id' => '3',
			'aco_id' => '335',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2748',
			'aro_id' => '1',
			'aco_id' => '336',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2749',
			'aro_id' => '2',
			'aco_id' => '336',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2750',
			'aro_id' => '3',
			'aco_id' => '336',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2751',
			'aro_id' => '1',
			'aco_id' => '337',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2752',
			'aro_id' => '2',
			'aco_id' => '337',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2753',
			'aro_id' => '3',
			'aco_id' => '337',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2757',
			'aro_id' => '1',
			'aco_id' => '281',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2758',
			'aro_id' => '2',
			'aco_id' => '281',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2759',
			'aro_id' => '3',
			'aco_id' => '281',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2760',
			'aro_id' => '1',
			'aco_id' => '282',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2761',
			'aro_id' => '2',
			'aco_id' => '282',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2762',
			'aro_id' => '3',
			'aco_id' => '282',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2763',
			'aro_id' => '1',
			'aco_id' => '283',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2764',
			'aro_id' => '2',
			'aco_id' => '283',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2765',
			'aro_id' => '3',
			'aco_id' => '283',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2766',
			'aro_id' => '1',
			'aco_id' => '284',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2767',
			'aro_id' => '2',
			'aco_id' => '284',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2768',
			'aro_id' => '3',
			'aco_id' => '284',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2775',
			'aro_id' => '1',
			'aco_id' => '286',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2776',
			'aro_id' => '2',
			'aco_id' => '286',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2777',
			'aro_id' => '3',
			'aco_id' => '286',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2778',
			'aro_id' => '1',
			'aco_id' => '287',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2779',
			'aro_id' => '2',
			'aco_id' => '287',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2780',
			'aro_id' => '3',
			'aco_id' => '287',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2781',
			'aro_id' => '1',
			'aco_id' => '288',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2782',
			'aro_id' => '2',
			'aco_id' => '288',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2783',
			'aro_id' => '3',
			'aco_id' => '288',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2784',
			'aro_id' => '1',
			'aco_id' => '289',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2785',
			'aro_id' => '2',
			'aco_id' => '289',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2786',
			'aro_id' => '3',
			'aco_id' => '289',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2787',
			'aro_id' => '1',
			'aco_id' => '290',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2788',
			'aro_id' => '2',
			'aco_id' => '290',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2789',
			'aro_id' => '3',
			'aco_id' => '290',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2790',
			'aro_id' => '1',
			'aco_id' => '291',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2791',
			'aro_id' => '2',
			'aco_id' => '291',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2792',
			'aro_id' => '3',
			'aco_id' => '291',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2793',
			'aro_id' => '1',
			'aco_id' => '292',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2794',
			'aro_id' => '2',
			'aco_id' => '292',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2795',
			'aro_id' => '3',
			'aco_id' => '292',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2799',
			'aro_id' => '1',
			'aco_id' => '328',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2800',
			'aro_id' => '2',
			'aco_id' => '328',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2801',
			'aro_id' => '3',
			'aco_id' => '328',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2802',
			'aro_id' => '1',
			'aco_id' => '329',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2803',
			'aro_id' => '2',
			'aco_id' => '329',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2804',
			'aro_id' => '3',
			'aco_id' => '329',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2805',
			'aro_id' => '1',
			'aco_id' => '330',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2806',
			'aro_id' => '2',
			'aco_id' => '330',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2807',
			'aro_id' => '3',
			'aco_id' => '330',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2808',
			'aro_id' => '1',
			'aco_id' => '331',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2809',
			'aro_id' => '2',
			'aco_id' => '331',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2810',
			'aro_id' => '3',
			'aco_id' => '331',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2811',
			'aro_id' => '1',
			'aco_id' => '332',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2812',
			'aro_id' => '2',
			'aco_id' => '332',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2813',
			'aro_id' => '3',
			'aco_id' => '332',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2817',
			'aro_id' => '1',
			'aco_id' => '311',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2818',
			'aro_id' => '2',
			'aco_id' => '311',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2819',
			'aro_id' => '3',
			'aco_id' => '311',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2823',
			'aro_id' => '1',
			'aco_id' => '313',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2824',
			'aro_id' => '2',
			'aco_id' => '313',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2825',
			'aro_id' => '3',
			'aco_id' => '313',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2826',
			'aro_id' => '1',
			'aco_id' => '314',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2827',
			'aro_id' => '2',
			'aco_id' => '314',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2828',
			'aro_id' => '3',
			'aco_id' => '314',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2829',
			'aro_id' => '1',
			'aco_id' => '315',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2830',
			'aro_id' => '2',
			'aco_id' => '315',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2831',
			'aro_id' => '3',
			'aco_id' => '315',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2832',
			'aro_id' => '1',
			'aco_id' => '316',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2833',
			'aro_id' => '2',
			'aco_id' => '316',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2834',
			'aro_id' => '3',
			'aco_id' => '316',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2835',
			'aro_id' => '1',
			'aco_id' => '317',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2836',
			'aro_id' => '2',
			'aco_id' => '317',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2837',
			'aro_id' => '3',
			'aco_id' => '317',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2838',
			'aro_id' => '1',
			'aco_id' => '318',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2839',
			'aro_id' => '2',
			'aco_id' => '318',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2840',
			'aro_id' => '3',
			'aco_id' => '318',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2841',
			'aro_id' => '1',
			'aco_id' => '319',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2842',
			'aro_id' => '2',
			'aco_id' => '319',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2843',
			'aro_id' => '3',
			'aco_id' => '319',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2847',
			'aro_id' => '1',
			'aco_id' => '306',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2848',
			'aro_id' => '2',
			'aco_id' => '306',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2849',
			'aro_id' => '3',
			'aco_id' => '306',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2850',
			'aro_id' => '1',
			'aco_id' => '307',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2851',
			'aro_id' => '2',
			'aco_id' => '307',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2852',
			'aro_id' => '3',
			'aco_id' => '307',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2853',
			'aro_id' => '1',
			'aco_id' => '308',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2854',
			'aro_id' => '2',
			'aco_id' => '308',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2855',
			'aro_id' => '3',
			'aco_id' => '308',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2856',
			'aro_id' => '1',
			'aco_id' => '309',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2857',
			'aro_id' => '2',
			'aco_id' => '309',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2858',
			'aro_id' => '3',
			'aco_id' => '309',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2859',
			'aro_id' => '1',
			'aco_id' => '310',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2860',
			'aro_id' => '2',
			'aco_id' => '310',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2861',
			'aro_id' => '3',
			'aco_id' => '310',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2865',
			'aro_id' => '1',
			'aco_id' => '278',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2866',
			'aro_id' => '2',
			'aco_id' => '278',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2867',
			'aro_id' => '3',
			'aco_id' => '278',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2868',
			'aro_id' => '1',
			'aco_id' => '279',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2869',
			'aro_id' => '2',
			'aco_id' => '279',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2870',
			'aro_id' => '3',
			'aco_id' => '279',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2874',
			'aro_id' => '1',
			'aco_id' => '267',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2875',
			'aro_id' => '2',
			'aco_id' => '267',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2876',
			'aro_id' => '3',
			'aco_id' => '267',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2877',
			'aro_id' => '1',
			'aco_id' => '268',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2878',
			'aro_id' => '2',
			'aco_id' => '268',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2879',
			'aro_id' => '3',
			'aco_id' => '268',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2880',
			'aro_id' => '1',
			'aco_id' => '269',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2881',
			'aro_id' => '2',
			'aco_id' => '269',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2882',
			'aro_id' => '3',
			'aco_id' => '269',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2883',
			'aro_id' => '1',
			'aco_id' => '270',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2884',
			'aro_id' => '2',
			'aco_id' => '270',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2885',
			'aro_id' => '3',
			'aco_id' => '270',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2886',
			'aro_id' => '1',
			'aco_id' => '271',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2887',
			'aro_id' => '2',
			'aco_id' => '271',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2888',
			'aro_id' => '3',
			'aco_id' => '271',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2889',
			'aro_id' => '1',
			'aco_id' => '359',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2890',
			'aro_id' => '2',
			'aco_id' => '359',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2891',
			'aro_id' => '3',
			'aco_id' => '359',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2892',
			'aro_id' => '1',
			'aco_id' => '207',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2893',
			'aro_id' => '2',
			'aco_id' => '207',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2894',
			'aro_id' => '3',
			'aco_id' => '207',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2895',
			'aro_id' => '1',
			'aco_id' => '208',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2896',
			'aro_id' => '2',
			'aco_id' => '208',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2897',
			'aro_id' => '3',
			'aco_id' => '208',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2901',
			'aro_id' => '1',
			'aco_id' => '338',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2902',
			'aro_id' => '2',
			'aco_id' => '338',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2903',
			'aro_id' => '3',
			'aco_id' => '338',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2904',
			'aro_id' => '1',
			'aco_id' => '210',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2905',
			'aro_id' => '2',
			'aco_id' => '210',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2906',
			'aro_id' => '3',
			'aco_id' => '210',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2910',
			'aro_id' => '1',
			'aco_id' => '339',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2911',
			'aro_id' => '2',
			'aco_id' => '339',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2912',
			'aro_id' => '3',
			'aco_id' => '339',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2919',
			'aro_id' => '1',
			'aco_id' => '342',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2920',
			'aro_id' => '2',
			'aco_id' => '342',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2921',
			'aro_id' => '3',
			'aco_id' => '342',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2922',
			'aro_id' => '1',
			'aco_id' => '343',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2923',
			'aro_id' => '2',
			'aco_id' => '343',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2924',
			'aro_id' => '3',
			'aco_id' => '343',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2925',
			'aro_id' => '1',
			'aco_id' => '344',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2926',
			'aro_id' => '2',
			'aco_id' => '344',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2927',
			'aro_id' => '3',
			'aco_id' => '344',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2931',
			'aro_id' => '1',
			'aco_id' => '346',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2932',
			'aro_id' => '2',
			'aco_id' => '346',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2933',
			'aro_id' => '3',
			'aco_id' => '346',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2934',
			'aro_id' => '1',
			'aco_id' => '347',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2935',
			'aro_id' => '2',
			'aco_id' => '347',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2936',
			'aro_id' => '3',
			'aco_id' => '347',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2937',
			'aro_id' => '1',
			'aco_id' => '216',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2938',
			'aro_id' => '2',
			'aco_id' => '216',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2939',
			'aro_id' => '3',
			'aco_id' => '216',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2943',
			'aro_id' => '1',
			'aco_id' => '348',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2944',
			'aro_id' => '2',
			'aco_id' => '348',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2945',
			'aro_id' => '3',
			'aco_id' => '348',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2946',
			'aro_id' => '1',
			'aco_id' => '349',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2947',
			'aro_id' => '2',
			'aco_id' => '349',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2948',
			'aro_id' => '3',
			'aco_id' => '349',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2949',
			'aro_id' => '1',
			'aco_id' => '350',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2950',
			'aro_id' => '2',
			'aco_id' => '350',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2951',
			'aro_id' => '3',
			'aco_id' => '350',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2952',
			'aro_id' => '1',
			'aco_id' => '351',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2953',
			'aro_id' => '2',
			'aco_id' => '351',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2954',
			'aro_id' => '3',
			'aco_id' => '351',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2955',
			'aro_id' => '1',
			'aco_id' => '352',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2956',
			'aro_id' => '2',
			'aco_id' => '352',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2957',
			'aro_id' => '3',
			'aco_id' => '352',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2958',
			'aro_id' => '1',
			'aco_id' => '353',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2959',
			'aro_id' => '2',
			'aco_id' => '353',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2960',
			'aro_id' => '3',
			'aco_id' => '353',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2973',
			'aro_id' => '1',
			'aco_id' => '360',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2974',
			'aro_id' => '2',
			'aco_id' => '360',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2975',
			'aro_id' => '3',
			'aco_id' => '360',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2976',
			'aro_id' => '1',
			'aco_id' => '361',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2977',
			'aro_id' => '2',
			'aco_id' => '361',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2978',
			'aro_id' => '3',
			'aco_id' => '361',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2979',
			'aro_id' => '1',
			'aco_id' => '396',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2980',
			'aro_id' => '2',
			'aco_id' => '396',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2981',
			'aro_id' => '3',
			'aco_id' => '396',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2982',
			'aro_id' => '1',
			'aco_id' => '398',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2983',
			'aro_id' => '2',
			'aco_id' => '398',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2984',
			'aro_id' => '3',
			'aco_id' => '398',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2985',
			'aro_id' => '1',
			'aco_id' => '399',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2986',
			'aro_id' => '2',
			'aco_id' => '399',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2987',
			'aro_id' => '3',
			'aco_id' => '399',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2988',
			'aro_id' => '1',
			'aco_id' => '400',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2989',
			'aro_id' => '2',
			'aco_id' => '400',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2990',
			'aro_id' => '3',
			'aco_id' => '400',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2991',
			'aro_id' => '1',
			'aco_id' => '401',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2992',
			'aro_id' => '2',
			'aco_id' => '401',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2993',
			'aro_id' => '3',
			'aco_id' => '401',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2994',
			'aro_id' => '1',
			'aco_id' => '405',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2995',
			'aro_id' => '2',
			'aco_id' => '405',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2996',
			'aro_id' => '3',
			'aco_id' => '405',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2997',
			'aro_id' => '1',
			'aco_id' => '406',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2998',
			'aro_id' => '2',
			'aco_id' => '406',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '2999',
			'aro_id' => '3',
			'aco_id' => '406',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3000',
			'aro_id' => '1',
			'aco_id' => '404',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3001',
			'aro_id' => '2',
			'aco_id' => '404',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3002',
			'aro_id' => '3',
			'aco_id' => '404',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3003',
			'aro_id' => '1',
			'aco_id' => '407',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3004',
			'aro_id' => '2',
			'aco_id' => '407',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3005',
			'aro_id' => '3',
			'aco_id' => '407',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3006',
			'aro_id' => '1',
			'aco_id' => '408',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3007',
			'aro_id' => '2',
			'aco_id' => '408',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3008',
			'aro_id' => '3',
			'aco_id' => '408',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3009',
			'aro_id' => '1',
			'aco_id' => '397',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3010',
			'aro_id' => '2',
			'aco_id' => '397',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3011',
			'aro_id' => '3',
			'aco_id' => '397',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3015',
			'aro_id' => '1',
			'aco_id' => '366',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3016',
			'aro_id' => '2',
			'aco_id' => '366',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3017',
			'aro_id' => '3',
			'aco_id' => '366',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3018',
			'aro_id' => '1',
			'aco_id' => '367',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3019',
			'aro_id' => '2',
			'aco_id' => '367',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3020',
			'aro_id' => '3',
			'aco_id' => '367',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3021',
			'aro_id' => '1',
			'aco_id' => '409',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3022',
			'aro_id' => '2',
			'aco_id' => '409',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3023',
			'aro_id' => '3',
			'aco_id' => '409',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3024',
			'aro_id' => '278',
			'aco_id' => '1',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3025',
			'aro_id' => '278',
			'aco_id' => '8',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3026',
			'aro_id' => '4',
			'aco_id' => '274',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3027',
			'aro_id' => '278',
			'aco_id' => '274',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3028',
			'aro_id' => '9',
			'aco_id' => '274',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3029',
			'aro_id' => '4',
			'aco_id' => '275',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3030',
			'aro_id' => '278',
			'aco_id' => '275',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3031',
			'aro_id' => '9',
			'aco_id' => '275',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3032',
			'aro_id' => '4',
			'aco_id' => '276',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3033',
			'aro_id' => '278',
			'aco_id' => '276',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3034',
			'aro_id' => '9',
			'aco_id' => '276',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3035',
			'aro_id' => '4',
			'aco_id' => '277',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3036',
			'aro_id' => '278',
			'aco_id' => '277',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3037',
			'aro_id' => '9',
			'aco_id' => '277',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3038',
			'aro_id' => '4',
			'aco_id' => '358',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3039',
			'aro_id' => '278',
			'aco_id' => '358',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3040',
			'aro_id' => '9',
			'aco_id' => '358',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3041',
			'aro_id' => '4',
			'aco_id' => '363',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3042',
			'aro_id' => '278',
			'aco_id' => '363',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3043',
			'aro_id' => '9',
			'aco_id' => '363',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3044',
			'aro_id' => '278',
			'aco_id' => '14',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3045',
			'aro_id' => '278',
			'aco_id' => '15',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3046',
			'aro_id' => '278',
			'aco_id' => '16',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3047',
			'aro_id' => '278',
			'aco_id' => '83',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3048',
			'aro_id' => '278',
			'aco_id' => '101',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3049',
			'aro_id' => '278',
			'aco_id' => '102',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3050',
			'aro_id' => '278',
			'aco_id' => '104',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3051',
			'aro_id' => '4',
			'aco_id' => '262',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3052',
			'aro_id' => '278',
			'aco_id' => '262',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3053',
			'aro_id' => '9',
			'aco_id' => '262',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3054',
			'aro_id' => '4',
			'aco_id' => '263',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3055',
			'aro_id' => '278',
			'aco_id' => '263',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3056',
			'aro_id' => '9',
			'aco_id' => '263',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3057',
			'aro_id' => '4',
			'aco_id' => '264',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3058',
			'aro_id' => '278',
			'aco_id' => '264',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3059',
			'aro_id' => '9',
			'aco_id' => '264',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3060',
			'aro_id' => '4',
			'aco_id' => '265',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3061',
			'aro_id' => '278',
			'aco_id' => '265',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3062',
			'aro_id' => '9',
			'aco_id' => '265',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3063',
			'aro_id' => '4',
			'aco_id' => '266',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3064',
			'aro_id' => '278',
			'aco_id' => '266',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3065',
			'aro_id' => '9',
			'aco_id' => '266',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3066',
			'aro_id' => '4',
			'aco_id' => '354',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3067',
			'aro_id' => '278',
			'aco_id' => '354',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3068',
			'aro_id' => '9',
			'aco_id' => '354',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3069',
			'aro_id' => '4',
			'aco_id' => '355',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3070',
			'aro_id' => '278',
			'aco_id' => '355',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3071',
			'aro_id' => '9',
			'aco_id' => '355',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3072',
			'aro_id' => '4',
			'aco_id' => '357',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3073',
			'aro_id' => '278',
			'aco_id' => '357',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3074',
			'aro_id' => '9',
			'aco_id' => '357',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3075',
			'aro_id' => '4',
			'aco_id' => '364',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3076',
			'aro_id' => '278',
			'aco_id' => '364',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3077',
			'aro_id' => '9',
			'aco_id' => '364',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3078',
			'aro_id' => '278',
			'aco_id' => '25',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3079',
			'aro_id' => '278',
			'aco_id' => '26',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3080',
			'aro_id' => '278',
			'aco_id' => '27',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3081',
			'aro_id' => '278',
			'aco_id' => '28',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3082',
			'aro_id' => '278',
			'aco_id' => '30',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3083',
			'aro_id' => '4',
			'aco_id' => '233',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3084',
			'aro_id' => '278',
			'aco_id' => '233',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3085',
			'aro_id' => '9',
			'aco_id' => '233',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3086',
			'aro_id' => '4',
			'aco_id' => '272',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3087',
			'aro_id' => '278',
			'aco_id' => '272',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3088',
			'aro_id' => '9',
			'aco_id' => '272',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3089',
			'aro_id' => '278',
			'aco_id' => '33',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3090',
			'aro_id' => '4',
			'aco_id' => '273',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3091',
			'aro_id' => '278',
			'aco_id' => '273',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3092',
			'aro_id' => '9',
			'aco_id' => '273',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3093',
			'aro_id' => '278',
			'aco_id' => '35',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3094',
			'aro_id' => '4',
			'aco_id' => '225',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3095',
			'aro_id' => '278',
			'aco_id' => '225',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3096',
			'aro_id' => '9',
			'aco_id' => '225',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3097',
			'aro_id' => '4',
			'aco_id' => '226',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3098',
			'aro_id' => '278',
			'aco_id' => '226',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3099',
			'aro_id' => '9',
			'aco_id' => '226',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3100',
			'aro_id' => '4',
			'aco_id' => '227',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3101',
			'aro_id' => '278',
			'aco_id' => '227',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3102',
			'aro_id' => '9',
			'aco_id' => '227',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3103',
			'aro_id' => '4',
			'aco_id' => '228',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3104',
			'aro_id' => '278',
			'aco_id' => '228',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3105',
			'aro_id' => '9',
			'aco_id' => '228',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3106',
			'aro_id' => '4',
			'aco_id' => '239',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3107',
			'aro_id' => '278',
			'aco_id' => '239',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3108',
			'aro_id' => '9',
			'aco_id' => '239',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3109',
			'aro_id' => '278',
			'aco_id' => '48',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3110',
			'aro_id' => '278',
			'aco_id' => '49',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3111',
			'aro_id' => '278',
			'aco_id' => '50',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3112',
			'aro_id' => '278',
			'aco_id' => '51',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3113',
			'aro_id' => '278',
			'aco_id' => '52',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3114',
			'aro_id' => '278',
			'aco_id' => '53',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3115',
			'aro_id' => '278',
			'aco_id' => '54',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3116',
			'aro_id' => '278',
			'aco_id' => '55',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3117',
			'aro_id' => '278',
			'aco_id' => '56',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3118',
			'aro_id' => '278',
			'aco_id' => '78',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3119',
			'aro_id' => '4',
			'aco_id' => '293',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3120',
			'aro_id' => '278',
			'aco_id' => '293',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3121',
			'aro_id' => '9',
			'aco_id' => '293',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3125',
			'aro_id' => '278',
			'aco_id' => '80',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3126',
			'aro_id' => '4',
			'aco_id' => '300',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3127',
			'aro_id' => '278',
			'aco_id' => '300',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3128',
			'aro_id' => '9',
			'aco_id' => '300',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3129',
			'aro_id' => '4',
			'aco_id' => '301',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3130',
			'aro_id' => '278',
			'aco_id' => '301',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3131',
			'aro_id' => '9',
			'aco_id' => '301',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3132',
			'aro_id' => '4',
			'aco_id' => '302',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3133',
			'aro_id' => '278',
			'aco_id' => '302',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3134',
			'aro_id' => '9',
			'aco_id' => '302',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3135',
			'aro_id' => '4',
			'aco_id' => '303',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3136',
			'aro_id' => '278',
			'aco_id' => '303',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3137',
			'aro_id' => '9',
			'aco_id' => '303',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3138',
			'aro_id' => '4',
			'aco_id' => '304',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3139',
			'aro_id' => '278',
			'aco_id' => '304',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3140',
			'aro_id' => '9',
			'aco_id' => '304',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3141',
			'aro_id' => '4',
			'aco_id' => '305',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3142',
			'aro_id' => '278',
			'aco_id' => '305',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3143',
			'aro_id' => '9',
			'aco_id' => '305',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3144',
			'aro_id' => '4',
			'aco_id' => '396',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3145',
			'aro_id' => '278',
			'aco_id' => '396',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3146',
			'aro_id' => '9',
			'aco_id' => '396',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3147',
			'aro_id' => '278',
			'aco_id' => '108',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3148',
			'aro_id' => '4',
			'aco_id' => '295',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3149',
			'aro_id' => '278',
			'aco_id' => '295',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3150',
			'aro_id' => '9',
			'aco_id' => '295',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3151',
			'aro_id' => '4',
			'aco_id' => '296',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3152',
			'aro_id' => '278',
			'aco_id' => '296',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3153',
			'aro_id' => '9',
			'aco_id' => '296',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3154',
			'aro_id' => '4',
			'aco_id' => '297',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3155',
			'aro_id' => '278',
			'aco_id' => '297',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3156',
			'aro_id' => '9',
			'aco_id' => '297',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3157',
			'aro_id' => '4',
			'aco_id' => '298',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3158',
			'aro_id' => '278',
			'aco_id' => '298',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3159',
			'aro_id' => '9',
			'aco_id' => '298',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3160',
			'aro_id' => '4',
			'aco_id' => '299',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3161',
			'aro_id' => '278',
			'aco_id' => '299',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3162',
			'aro_id' => '9',
			'aco_id' => '299',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3163',
			'aro_id' => '4',
			'aco_id' => '398',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3164',
			'aro_id' => '278',
			'aco_id' => '398',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3165',
			'aro_id' => '9',
			'aco_id' => '398',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3166',
			'aro_id' => '278',
			'aco_id' => '111',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3167',
			'aro_id' => '4',
			'aco_id' => '320',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3168',
			'aro_id' => '278',
			'aco_id' => '320',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3169',
			'aro_id' => '9',
			'aco_id' => '320',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3170',
			'aro_id' => '4',
			'aco_id' => '321',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3171',
			'aro_id' => '278',
			'aco_id' => '321',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3172',
			'aro_id' => '9',
			'aco_id' => '321',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3173',
			'aro_id' => '4',
			'aco_id' => '322',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3174',
			'aro_id' => '278',
			'aco_id' => '322',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3175',
			'aro_id' => '9',
			'aco_id' => '322',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3176',
			'aro_id' => '4',
			'aco_id' => '323',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3177',
			'aro_id' => '278',
			'aco_id' => '323',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3178',
			'aro_id' => '9',
			'aco_id' => '323',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3179',
			'aro_id' => '4',
			'aco_id' => '324',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3180',
			'aro_id' => '278',
			'aco_id' => '324',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3181',
			'aro_id' => '9',
			'aco_id' => '324',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3182',
			'aro_id' => '4',
			'aco_id' => '325',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3183',
			'aro_id' => '278',
			'aco_id' => '325',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3184',
			'aro_id' => '9',
			'aco_id' => '325',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3185',
			'aro_id' => '4',
			'aco_id' => '327',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3186',
			'aro_id' => '278',
			'aco_id' => '327',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3187',
			'aro_id' => '9',
			'aco_id' => '327',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3188',
			'aro_id' => '4',
			'aco_id' => '399',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3189',
			'aro_id' => '278',
			'aco_id' => '399',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3190',
			'aro_id' => '9',
			'aco_id' => '399',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3191',
			'aro_id' => '278',
			'aco_id' => '114',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3192',
			'aro_id' => '4',
			'aco_id' => '333',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3193',
			'aro_id' => '278',
			'aco_id' => '333',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3194',
			'aro_id' => '9',
			'aco_id' => '333',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3195',
			'aro_id' => '4',
			'aco_id' => '334',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3196',
			'aro_id' => '278',
			'aco_id' => '334',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3197',
			'aro_id' => '9',
			'aco_id' => '334',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3198',
			'aro_id' => '4',
			'aco_id' => '335',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3199',
			'aro_id' => '278',
			'aco_id' => '335',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3200',
			'aro_id' => '9',
			'aco_id' => '335',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3201',
			'aro_id' => '4',
			'aco_id' => '336',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3202',
			'aro_id' => '278',
			'aco_id' => '336',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3203',
			'aro_id' => '9',
			'aco_id' => '336',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3204',
			'aro_id' => '4',
			'aco_id' => '337',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3205',
			'aro_id' => '278',
			'aco_id' => '337',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3206',
			'aro_id' => '9',
			'aco_id' => '337',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3207',
			'aro_id' => '4',
			'aco_id' => '400',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3208',
			'aro_id' => '278',
			'aco_id' => '400',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3209',
			'aro_id' => '9',
			'aco_id' => '400',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3210',
			'aro_id' => '278',
			'aco_id' => '131',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3211',
			'aro_id' => '4',
			'aco_id' => '281',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3212',
			'aro_id' => '278',
			'aco_id' => '281',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3213',
			'aro_id' => '9',
			'aco_id' => '281',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3214',
			'aro_id' => '4',
			'aco_id' => '282',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3215',
			'aro_id' => '278',
			'aco_id' => '282',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3216',
			'aro_id' => '9',
			'aco_id' => '282',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3217',
			'aro_id' => '4',
			'aco_id' => '283',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3218',
			'aro_id' => '278',
			'aco_id' => '283',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3219',
			'aro_id' => '9',
			'aco_id' => '283',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3220',
			'aro_id' => '4',
			'aco_id' => '284',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3221',
			'aro_id' => '278',
			'aco_id' => '284',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3222',
			'aro_id' => '9',
			'aco_id' => '284',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3223',
			'aro_id' => '4',
			'aco_id' => '401',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3224',
			'aro_id' => '278',
			'aco_id' => '401',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3225',
			'aro_id' => '9',
			'aco_id' => '401',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3226',
			'aro_id' => '278',
			'aco_id' => '137',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3227',
			'aro_id' => '4',
			'aco_id' => '286',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3228',
			'aro_id' => '278',
			'aco_id' => '286',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3229',
			'aro_id' => '9',
			'aco_id' => '286',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3230',
			'aro_id' => '4',
			'aco_id' => '287',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3231',
			'aro_id' => '278',
			'aco_id' => '287',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3232',
			'aro_id' => '9',
			'aco_id' => '287',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3233',
			'aro_id' => '4',
			'aco_id' => '288',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3234',
			'aro_id' => '278',
			'aco_id' => '288',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3235',
			'aro_id' => '9',
			'aco_id' => '288',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3236',
			'aro_id' => '4',
			'aco_id' => '289',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3237',
			'aro_id' => '278',
			'aco_id' => '289',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3238',
			'aro_id' => '9',
			'aco_id' => '289',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3239',
			'aro_id' => '4',
			'aco_id' => '290',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3240',
			'aro_id' => '278',
			'aco_id' => '290',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3241',
			'aro_id' => '9',
			'aco_id' => '290',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3242',
			'aro_id' => '4',
			'aco_id' => '291',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3243',
			'aro_id' => '278',
			'aco_id' => '291',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3244',
			'aro_id' => '9',
			'aco_id' => '291',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3245',
			'aro_id' => '4',
			'aco_id' => '292',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3246',
			'aro_id' => '278',
			'aco_id' => '292',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3247',
			'aro_id' => '9',
			'aco_id' => '292',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3248',
			'aro_id' => '4',
			'aco_id' => '405',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3249',
			'aro_id' => '278',
			'aco_id' => '405',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3250',
			'aro_id' => '9',
			'aco_id' => '405',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3251',
			'aro_id' => '4',
			'aco_id' => '406',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3252',
			'aro_id' => '278',
			'aco_id' => '406',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3253',
			'aro_id' => '9',
			'aco_id' => '406',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3254',
			'aro_id' => '278',
			'aco_id' => '143',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3255',
			'aro_id' => '4',
			'aco_id' => '328',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3256',
			'aro_id' => '278',
			'aco_id' => '328',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3257',
			'aro_id' => '9',
			'aco_id' => '328',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3258',
			'aro_id' => '4',
			'aco_id' => '329',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3259',
			'aro_id' => '278',
			'aco_id' => '329',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3260',
			'aro_id' => '9',
			'aco_id' => '329',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3261',
			'aro_id' => '4',
			'aco_id' => '330',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3262',
			'aro_id' => '278',
			'aco_id' => '330',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3263',
			'aro_id' => '9',
			'aco_id' => '330',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3264',
			'aro_id' => '4',
			'aco_id' => '331',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3265',
			'aro_id' => '278',
			'aco_id' => '331',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3266',
			'aro_id' => '9',
			'aco_id' => '331',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3267',
			'aro_id' => '4',
			'aco_id' => '332',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3268',
			'aro_id' => '278',
			'aco_id' => '332',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3269',
			'aro_id' => '9',
			'aco_id' => '332',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3270',
			'aro_id' => '278',
			'aco_id' => '153',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3271',
			'aro_id' => '4',
			'aco_id' => '311',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3272',
			'aro_id' => '278',
			'aco_id' => '311',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3273',
			'aro_id' => '9',
			'aco_id' => '311',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3274',
			'aro_id' => '4',
			'aco_id' => '313',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3275',
			'aro_id' => '278',
			'aco_id' => '313',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3276',
			'aro_id' => '9',
			'aco_id' => '313',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3277',
			'aro_id' => '4',
			'aco_id' => '314',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3278',
			'aro_id' => '278',
			'aco_id' => '314',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3279',
			'aro_id' => '9',
			'aco_id' => '314',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3280',
			'aro_id' => '4',
			'aco_id' => '315',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3281',
			'aro_id' => '278',
			'aco_id' => '315',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3282',
			'aro_id' => '9',
			'aco_id' => '315',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3283',
			'aro_id' => '4',
			'aco_id' => '316',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3284',
			'aro_id' => '278',
			'aco_id' => '316',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3285',
			'aro_id' => '9',
			'aco_id' => '316',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3286',
			'aro_id' => '4',
			'aco_id' => '317',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3287',
			'aro_id' => '278',
			'aco_id' => '317',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3288',
			'aro_id' => '9',
			'aco_id' => '317',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3289',
			'aro_id' => '4',
			'aco_id' => '318',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3290',
			'aro_id' => '278',
			'aco_id' => '318',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3291',
			'aro_id' => '9',
			'aco_id' => '318',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3292',
			'aro_id' => '4',
			'aco_id' => '319',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3293',
			'aro_id' => '278',
			'aco_id' => '319',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3294',
			'aro_id' => '9',
			'aco_id' => '319',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3295',
			'aro_id' => '4',
			'aco_id' => '404',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3296',
			'aro_id' => '278',
			'aco_id' => '404',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3297',
			'aro_id' => '9',
			'aco_id' => '404',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3298',
			'aro_id' => '4',
			'aco_id' => '407',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3299',
			'aro_id' => '278',
			'aco_id' => '407',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3300',
			'aro_id' => '9',
			'aco_id' => '407',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3301',
			'aro_id' => '4',
			'aco_id' => '408',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3302',
			'aro_id' => '278',
			'aco_id' => '408',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3303',
			'aro_id' => '9',
			'aco_id' => '408',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3304',
			'aro_id' => '278',
			'aco_id' => '200',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3305',
			'aro_id' => '4',
			'aco_id' => '306',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3306',
			'aro_id' => '278',
			'aco_id' => '306',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3307',
			'aro_id' => '9',
			'aco_id' => '306',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3308',
			'aro_id' => '4',
			'aco_id' => '307',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3309',
			'aro_id' => '278',
			'aco_id' => '307',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3310',
			'aro_id' => '9',
			'aco_id' => '307',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3311',
			'aro_id' => '4',
			'aco_id' => '308',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3312',
			'aro_id' => '278',
			'aco_id' => '308',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3313',
			'aro_id' => '9',
			'aco_id' => '308',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3314',
			'aro_id' => '4',
			'aco_id' => '309',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3315',
			'aro_id' => '278',
			'aco_id' => '309',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3316',
			'aro_id' => '9',
			'aco_id' => '309',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3317',
			'aro_id' => '4',
			'aco_id' => '310',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3318',
			'aro_id' => '278',
			'aco_id' => '310',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3319',
			'aro_id' => '9',
			'aco_id' => '310',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3320',
			'aro_id' => '4',
			'aco_id' => '397',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3321',
			'aro_id' => '278',
			'aco_id' => '397',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3322',
			'aro_id' => '9',
			'aco_id' => '397',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3323',
			'aro_id' => '278',
			'aco_id' => '165',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3324',
			'aro_id' => '4',
			'aco_id' => '278',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3325',
			'aro_id' => '278',
			'aco_id' => '278',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3326',
			'aro_id' => '9',
			'aco_id' => '278',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3327',
			'aro_id' => '4',
			'aco_id' => '279',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3328',
			'aro_id' => '278',
			'aco_id' => '279',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3329',
			'aro_id' => '9',
			'aco_id' => '279',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3330',
			'aro_id' => '278',
			'aco_id' => '169',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3331',
			'aro_id' => '4',
			'aco_id' => '267',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3332',
			'aro_id' => '278',
			'aco_id' => '267',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3333',
			'aro_id' => '9',
			'aco_id' => '267',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3334',
			'aro_id' => '4',
			'aco_id' => '268',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3335',
			'aro_id' => '278',
			'aco_id' => '268',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3336',
			'aro_id' => '9',
			'aco_id' => '268',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3337',
			'aro_id' => '4',
			'aco_id' => '269',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3338',
			'aro_id' => '278',
			'aco_id' => '269',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3339',
			'aro_id' => '9',
			'aco_id' => '269',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3340',
			'aro_id' => '4',
			'aco_id' => '270',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3341',
			'aro_id' => '278',
			'aco_id' => '270',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3342',
			'aro_id' => '9',
			'aco_id' => '270',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3343',
			'aro_id' => '4',
			'aco_id' => '271',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3344',
			'aro_id' => '278',
			'aco_id' => '271',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3345',
			'aro_id' => '9',
			'aco_id' => '271',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3346',
			'aro_id' => '4',
			'aco_id' => '359',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3347',
			'aro_id' => '278',
			'aco_id' => '359',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3348',
			'aro_id' => '9',
			'aco_id' => '359',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3349',
			'aro_id' => '4',
			'aco_id' => '207',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3350',
			'aro_id' => '278',
			'aco_id' => '207',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3351',
			'aro_id' => '9',
			'aco_id' => '207',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3352',
			'aro_id' => '4',
			'aco_id' => '208',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3353',
			'aro_id' => '278',
			'aco_id' => '208',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3354',
			'aro_id' => '9',
			'aco_id' => '208',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3355',
			'aro_id' => '4',
			'aco_id' => '338',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3356',
			'aro_id' => '278',
			'aco_id' => '338',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3357',
			'aro_id' => '9',
			'aco_id' => '338',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3358',
			'aro_id' => '4',
			'aco_id' => '210',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3359',
			'aro_id' => '278',
			'aco_id' => '210',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3360',
			'aro_id' => '9',
			'aco_id' => '210',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3361',
			'aro_id' => '4',
			'aco_id' => '339',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3362',
			'aro_id' => '278',
			'aco_id' => '339',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3363',
			'aro_id' => '9',
			'aco_id' => '339',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3364',
			'aro_id' => '4',
			'aco_id' => '342',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3365',
			'aro_id' => '278',
			'aco_id' => '342',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3366',
			'aro_id' => '9',
			'aco_id' => '342',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3367',
			'aro_id' => '4',
			'aco_id' => '343',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3368',
			'aro_id' => '278',
			'aco_id' => '343',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3369',
			'aro_id' => '9',
			'aco_id' => '343',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3370',
			'aro_id' => '4',
			'aco_id' => '344',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3371',
			'aro_id' => '278',
			'aco_id' => '344',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3372',
			'aro_id' => '9',
			'aco_id' => '344',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3376',
			'aro_id' => '4',
			'aco_id' => '346',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3377',
			'aro_id' => '278',
			'aco_id' => '346',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3378',
			'aro_id' => '9',
			'aco_id' => '346',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3379',
			'aro_id' => '4',
			'aco_id' => '347',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3380',
			'aro_id' => '278',
			'aco_id' => '347',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3381',
			'aro_id' => '9',
			'aco_id' => '347',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3385',
			'aro_id' => '4',
			'aco_id' => '366',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3386',
			'aro_id' => '278',
			'aco_id' => '366',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3387',
			'aro_id' => '9',
			'aco_id' => '366',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3388',
			'aro_id' => '4',
			'aco_id' => '367',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3389',
			'aro_id' => '278',
			'aco_id' => '367',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3390',
			'aro_id' => '9',
			'aco_id' => '367',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3391',
			'aro_id' => '4',
			'aco_id' => '409',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3392',
			'aro_id' => '278',
			'aco_id' => '409',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3393',
			'aro_id' => '9',
			'aco_id' => '409',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3394',
			'aro_id' => '4',
			'aco_id' => '216',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3395',
			'aro_id' => '278',
			'aco_id' => '216',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3396',
			'aro_id' => '9',
			'aco_id' => '216',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3397',
			'aro_id' => '4',
			'aco_id' => '348',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3398',
			'aro_id' => '278',
			'aco_id' => '348',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3399',
			'aro_id' => '9',
			'aco_id' => '348',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3400',
			'aro_id' => '4',
			'aco_id' => '349',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3401',
			'aro_id' => '278',
			'aco_id' => '349',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3402',
			'aro_id' => '9',
			'aco_id' => '349',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3403',
			'aro_id' => '4',
			'aco_id' => '350',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3404',
			'aro_id' => '278',
			'aco_id' => '350',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3405',
			'aro_id' => '9',
			'aco_id' => '350',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3406',
			'aro_id' => '4',
			'aco_id' => '351',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3407',
			'aro_id' => '278',
			'aco_id' => '351',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3408',
			'aro_id' => '9',
			'aco_id' => '351',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3409',
			'aro_id' => '4',
			'aco_id' => '352',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3410',
			'aro_id' => '278',
			'aco_id' => '352',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3411',
			'aro_id' => '9',
			'aco_id' => '352',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3412',
			'aro_id' => '4',
			'aco_id' => '353',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3413',
			'aro_id' => '278',
			'aco_id' => '353',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3414',
			'aro_id' => '9',
			'aco_id' => '353',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3415',
			'aro_id' => '4',
			'aco_id' => '360',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3416',
			'aro_id' => '278',
			'aco_id' => '360',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3417',
			'aro_id' => '9',
			'aco_id' => '360',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3418',
			'aro_id' => '4',
			'aco_id' => '361',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3419',
			'aro_id' => '278',
			'aco_id' => '361',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3420',
			'aro_id' => '9',
			'aco_id' => '361',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3421',
			'aro_id' => '38',
			'aco_id' => '274',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3422',
			'aro_id' => '38',
			'aco_id' => '275',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3423',
			'aro_id' => '38',
			'aco_id' => '276',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3424',
			'aro_id' => '38',
			'aco_id' => '277',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3425',
			'aro_id' => '38',
			'aco_id' => '358',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3426',
			'aro_id' => '38',
			'aco_id' => '363',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3427',
			'aro_id' => '38',
			'aco_id' => '414',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3428',
			'aro_id' => '38',
			'aco_id' => '416',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3429',
			'aro_id' => '38',
			'aco_id' => '262',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3430',
			'aro_id' => '38',
			'aco_id' => '263',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3431',
			'aro_id' => '38',
			'aco_id' => '264',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3432',
			'aro_id' => '38',
			'aco_id' => '265',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3433',
			'aro_id' => '38',
			'aco_id' => '266',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3434',
			'aro_id' => '38',
			'aco_id' => '354',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3435',
			'aro_id' => '38',
			'aco_id' => '355',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3436',
			'aro_id' => '38',
			'aco_id' => '357',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3437',
			'aro_id' => '38',
			'aco_id' => '364',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3438',
			'aro_id' => '38',
			'aco_id' => '418',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3439',
			'aro_id' => '38',
			'aco_id' => '422',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3440',
			'aro_id' => '38',
			'aco_id' => '233',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3441',
			'aro_id' => '38',
			'aco_id' => '272',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3442',
			'aro_id' => '38',
			'aco_id' => '411',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3443',
			'aro_id' => '38',
			'aco_id' => '273',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3444',
			'aro_id' => '38',
			'aco_id' => '412',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3445',
			'aro_id' => '38',
			'aco_id' => '417',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3446',
			'aro_id' => '38',
			'aco_id' => '420',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3447',
			'aro_id' => '38',
			'aco_id' => '293',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3449',
			'aro_id' => '38',
			'aco_id' => '425',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3450',
			'aro_id' => '38',
			'aco_id' => '300',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3451',
			'aro_id' => '38',
			'aco_id' => '301',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3452',
			'aro_id' => '38',
			'aco_id' => '302',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3453',
			'aro_id' => '38',
			'aco_id' => '303',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3454',
			'aro_id' => '38',
			'aco_id' => '304',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3455',
			'aro_id' => '38',
			'aco_id' => '305',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3456',
			'aro_id' => '38',
			'aco_id' => '396',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3457',
			'aro_id' => '38',
			'aco_id' => '427',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3458',
			'aro_id' => '38',
			'aco_id' => '295',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3459',
			'aro_id' => '38',
			'aco_id' => '296',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3460',
			'aro_id' => '38',
			'aco_id' => '297',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3461',
			'aro_id' => '38',
			'aco_id' => '298',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3462',
			'aro_id' => '38',
			'aco_id' => '299',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3463',
			'aro_id' => '38',
			'aco_id' => '398',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3464',
			'aro_id' => '38',
			'aco_id' => '426',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3465',
			'aro_id' => '38',
			'aco_id' => '320',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3466',
			'aro_id' => '38',
			'aco_id' => '321',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3467',
			'aro_id' => '38',
			'aco_id' => '322',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3468',
			'aro_id' => '38',
			'aco_id' => '323',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3469',
			'aro_id' => '38',
			'aco_id' => '324',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3470',
			'aro_id' => '38',
			'aco_id' => '325',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3471',
			'aro_id' => '38',
			'aco_id' => '327',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3472',
			'aro_id' => '38',
			'aco_id' => '399',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3473',
			'aro_id' => '38',
			'aco_id' => '430',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3474',
			'aro_id' => '38',
			'aco_id' => '333',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3475',
			'aro_id' => '38',
			'aco_id' => '334',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3476',
			'aro_id' => '38',
			'aco_id' => '335',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3477',
			'aro_id' => '38',
			'aco_id' => '336',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3478',
			'aro_id' => '38',
			'aco_id' => '337',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3479',
			'aro_id' => '38',
			'aco_id' => '400',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3480',
			'aro_id' => '38',
			'aco_id' => '432',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3481',
			'aro_id' => '38',
			'aco_id' => '281',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3482',
			'aro_id' => '38',
			'aco_id' => '282',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3483',
			'aro_id' => '38',
			'aco_id' => '283',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3484',
			'aro_id' => '38',
			'aco_id' => '284',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3485',
			'aro_id' => '38',
			'aco_id' => '401',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3486',
			'aro_id' => '38',
			'aco_id' => '423',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3487',
			'aro_id' => '38',
			'aco_id' => '286',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3488',
			'aro_id' => '38',
			'aco_id' => '287',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3489',
			'aro_id' => '38',
			'aco_id' => '288',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3490',
			'aro_id' => '38',
			'aco_id' => '289',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3491',
			'aro_id' => '38',
			'aco_id' => '290',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3492',
			'aro_id' => '38',
			'aco_id' => '292',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3493',
			'aro_id' => '38',
			'aco_id' => '405',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3494',
			'aro_id' => '38',
			'aco_id' => '406',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3495',
			'aro_id' => '38',
			'aco_id' => '424',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3496',
			'aro_id' => '38',
			'aco_id' => '328',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3497',
			'aro_id' => '38',
			'aco_id' => '329',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3498',
			'aro_id' => '38',
			'aco_id' => '330',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3499',
			'aro_id' => '38',
			'aco_id' => '331',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3500',
			'aro_id' => '38',
			'aco_id' => '332',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3501',
			'aro_id' => '38',
			'aco_id' => '431',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3502',
			'aro_id' => '38',
			'aco_id' => '311',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3503',
			'aro_id' => '38',
			'aco_id' => '313',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3504',
			'aro_id' => '38',
			'aco_id' => '315',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3505',
			'aro_id' => '38',
			'aco_id' => '317',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3506',
			'aro_id' => '38',
			'aco_id' => '318',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3507',
			'aro_id' => '38',
			'aco_id' => '319',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3508',
			'aro_id' => '38',
			'aco_id' => '404',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3509',
			'aro_id' => '38',
			'aco_id' => '407',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3510',
			'aro_id' => '38',
			'aco_id' => '408',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3511',
			'aro_id' => '38',
			'aco_id' => '429',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3512',
			'aro_id' => '38',
			'aco_id' => '306',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3513',
			'aro_id' => '38',
			'aco_id' => '307',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3514',
			'aro_id' => '38',
			'aco_id' => '308',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3515',
			'aro_id' => '38',
			'aco_id' => '309',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3516',
			'aro_id' => '38',
			'aco_id' => '310',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3517',
			'aro_id' => '38',
			'aco_id' => '397',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3518',
			'aro_id' => '38',
			'aco_id' => '428',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3519',
			'aro_id' => '38',
			'aco_id' => '278',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3520',
			'aro_id' => '38',
			'aco_id' => '279',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3521',
			'aro_id' => '38',
			'aco_id' => '415',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3522',
			'aro_id' => '38',
			'aco_id' => '267',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3523',
			'aro_id' => '38',
			'aco_id' => '268',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3524',
			'aro_id' => '38',
			'aco_id' => '269',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3525',
			'aro_id' => '38',
			'aco_id' => '270',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3526',
			'aro_id' => '38',
			'aco_id' => '271',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3527',
			'aro_id' => '38',
			'aco_id' => '359',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3528',
			'aro_id' => '38',
			'aco_id' => '410',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3529',
			'aro_id' => '38',
			'aco_id' => '338',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3530',
			'aro_id' => '38',
			'aco_id' => '434',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3531',
			'aro_id' => '38',
			'aco_id' => '339',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3532',
			'aro_id' => '38',
			'aco_id' => '342',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3533',
			'aro_id' => '38',
			'aco_id' => '343',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3534',
			'aro_id' => '38',
			'aco_id' => '344',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3536',
			'aro_id' => '38',
			'aco_id' => '347',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3538',
			'aro_id' => '38',
			'aco_id' => '366',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3539',
			'aro_id' => '38',
			'aco_id' => '367',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3540',
			'aro_id' => '38',
			'aco_id' => '409',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3541',
			'aro_id' => '38',
			'aco_id' => '435',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3542',
			'aro_id' => '38',
			'aco_id' => '348',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3543',
			'aro_id' => '38',
			'aco_id' => '349',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3544',
			'aro_id' => '38',
			'aco_id' => '350',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3545',
			'aro_id' => '38',
			'aco_id' => '351',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3546',
			'aro_id' => '38',
			'aco_id' => '352',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3547',
			'aro_id' => '38',
			'aco_id' => '353',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3548',
			'aro_id' => '38',
			'aco_id' => '436',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3549',
			'aro_id' => '38',
			'aco_id' => '360',
			'_create' => '-1',
			'_read' => '-1',
			'_update' => '-1',
			'_delete' => '-1'
		),
		array(
			'id' => '3550',
			'aro_id' => '38',
			'aco_id' => '361',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3551',
			'aro_id' => '38',
			'aco_id' => '413',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3552',
			'aro_id' => '38',
			'aco_id' => '419',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3553',
			'aro_id' => '38',
			'aco_id' => '421',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3554',
			'aro_id' => '38',
			'aco_id' => '433',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3555',
			'aro_id' => '38',
			'aco_id' => '437',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3556',
			'aro_id' => '4',
			'aco_id' => '414',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3557',
			'aro_id' => '278',
			'aco_id' => '414',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3558',
			'aro_id' => '9',
			'aco_id' => '414',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3559',
			'aro_id' => '4',
			'aco_id' => '416',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3560',
			'aro_id' => '278',
			'aco_id' => '416',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3561',
			'aro_id' => '9',
			'aco_id' => '416',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3562',
			'aro_id' => '4',
			'aco_id' => '418',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3563',
			'aro_id' => '278',
			'aco_id' => '418',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3564',
			'aro_id' => '9',
			'aco_id' => '418',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3565',
			'aro_id' => '4',
			'aco_id' => '422',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3566',
			'aro_id' => '278',
			'aco_id' => '422',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3567',
			'aro_id' => '9',
			'aco_id' => '422',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3568',
			'aro_id' => '4',
			'aco_id' => '411',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3569',
			'aro_id' => '278',
			'aco_id' => '411',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3570',
			'aro_id' => '9',
			'aco_id' => '411',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3571',
			'aro_id' => '4',
			'aco_id' => '412',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3572',
			'aro_id' => '278',
			'aco_id' => '412',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3573',
			'aro_id' => '9',
			'aco_id' => '412',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3574',
			'aro_id' => '4',
			'aco_id' => '417',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3575',
			'aro_id' => '278',
			'aco_id' => '417',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3576',
			'aro_id' => '9',
			'aco_id' => '417',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3577',
			'aro_id' => '4',
			'aco_id' => '420',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3578',
			'aro_id' => '278',
			'aco_id' => '420',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3579',
			'aro_id' => '9',
			'aco_id' => '420',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3580',
			'aro_id' => '4',
			'aco_id' => '425',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3581',
			'aro_id' => '278',
			'aco_id' => '425',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3582',
			'aro_id' => '9',
			'aco_id' => '425',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3583',
			'aro_id' => '4',
			'aco_id' => '427',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3584',
			'aro_id' => '278',
			'aco_id' => '427',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3585',
			'aro_id' => '9',
			'aco_id' => '427',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3586',
			'aro_id' => '4',
			'aco_id' => '426',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3587',
			'aro_id' => '278',
			'aco_id' => '426',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3588',
			'aro_id' => '9',
			'aco_id' => '426',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3589',
			'aro_id' => '4',
			'aco_id' => '430',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3590',
			'aro_id' => '278',
			'aco_id' => '430',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3591',
			'aro_id' => '9',
			'aco_id' => '430',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3592',
			'aro_id' => '4',
			'aco_id' => '432',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3593',
			'aro_id' => '278',
			'aco_id' => '432',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3594',
			'aro_id' => '9',
			'aco_id' => '432',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3595',
			'aro_id' => '4',
			'aco_id' => '423',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3596',
			'aro_id' => '278',
			'aco_id' => '423',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3597',
			'aro_id' => '9',
			'aco_id' => '423',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3598',
			'aro_id' => '4',
			'aco_id' => '424',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3599',
			'aro_id' => '278',
			'aco_id' => '424',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3600',
			'aro_id' => '9',
			'aco_id' => '424',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3601',
			'aro_id' => '4',
			'aco_id' => '431',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3602',
			'aro_id' => '278',
			'aco_id' => '431',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3603',
			'aro_id' => '9',
			'aco_id' => '431',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3604',
			'aro_id' => '4',
			'aco_id' => '429',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3605',
			'aro_id' => '278',
			'aco_id' => '429',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3606',
			'aro_id' => '9',
			'aco_id' => '429',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3607',
			'aro_id' => '4',
			'aco_id' => '428',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3608',
			'aro_id' => '278',
			'aco_id' => '428',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3609',
			'aro_id' => '9',
			'aco_id' => '428',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3610',
			'aro_id' => '4',
			'aco_id' => '415',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3611',
			'aro_id' => '278',
			'aco_id' => '415',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3612',
			'aro_id' => '9',
			'aco_id' => '415',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3613',
			'aro_id' => '4',
			'aco_id' => '410',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3614',
			'aro_id' => '278',
			'aco_id' => '410',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3615',
			'aro_id' => '9',
			'aco_id' => '410',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3616',
			'aro_id' => '4',
			'aco_id' => '434',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3617',
			'aro_id' => '278',
			'aco_id' => '434',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3618',
			'aro_id' => '9',
			'aco_id' => '434',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3619',
			'aro_id' => '4',
			'aco_id' => '435',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3620',
			'aro_id' => '278',
			'aco_id' => '435',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3621',
			'aro_id' => '9',
			'aco_id' => '435',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3622',
			'aro_id' => '4',
			'aco_id' => '436',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3623',
			'aro_id' => '278',
			'aco_id' => '436',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3624',
			'aro_id' => '9',
			'aco_id' => '436',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3625',
			'aro_id' => '4',
			'aco_id' => '413',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3626',
			'aro_id' => '278',
			'aco_id' => '413',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3627',
			'aro_id' => '9',
			'aco_id' => '413',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3628',
			'aro_id' => '4',
			'aco_id' => '419',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3629',
			'aro_id' => '278',
			'aco_id' => '419',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3630',
			'aro_id' => '9',
			'aco_id' => '419',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3631',
			'aro_id' => '4',
			'aco_id' => '421',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3632',
			'aro_id' => '278',
			'aco_id' => '421',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3633',
			'aro_id' => '9',
			'aco_id' => '421',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3634',
			'aro_id' => '4',
			'aco_id' => '433',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3635',
			'aro_id' => '278',
			'aco_id' => '433',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3636',
			'aro_id' => '9',
			'aco_id' => '433',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3637',
			'aro_id' => '4',
			'aco_id' => '437',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3638',
			'aro_id' => '278',
			'aco_id' => '437',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3639',
			'aro_id' => '9',
			'aco_id' => '437',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3640',
			'aro_id' => '4',
			'aco_id' => '438',
			'_create' => '1',
			'_read' => '1',
			'_update' => '1',
			'_delete' => '1'
		),
		array(
			'id' => '3641',
			'aro_id' => '278',
			'aco_id' => '438',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3642',
			'aro_id' => '9',
			'aco_id' => '438',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
		array(
			'id' => '3643',
			'aro_id' => '38',
			'aco_id' => '438',
			'_create' => '0',
			'_read' => '0',
			'_update' => '0',
			'_delete' => '0'
		),
	);

}
