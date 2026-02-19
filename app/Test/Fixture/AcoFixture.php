<?php
/**
 * AcoFixture
 *
 */
class AcoFixture extends CakeTestFixture {

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
			'model' => null,
			'foreign_key' => null,
			'alias' => 'controllers',
			'lft' => '1',
			'rght' => '366'
		),
		array(
			'id' => '8',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Groups',
			'lft' => '2',
			'rght' => '17'
		),
		array(
			'id' => '14',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Pages',
			'lft' => '18',
			'rght' => '23'
		),
		array(
			'id' => '15',
			'parent_id' => '14',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'display',
			'lft' => '19',
			'rght' => '20'
		),
		array(
			'id' => '16',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Users',
			'lft' => '24',
			'rght' => '53'
		),
		array(
			'id' => '25',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'DebugKit',
			'lft' => '54',
			'rght' => '63'
		),
		array(
			'id' => '26',
			'parent_id' => '25',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'ToolbarAccess',
			'lft' => '55',
			'rght' => '62'
		),
		array(
			'id' => '27',
			'parent_id' => '26',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'history_state',
			'lft' => '56',
			'rght' => '57'
		),
		array(
			'id' => '28',
			'parent_id' => '26',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'sql_explain',
			'lft' => '58',
			'rght' => '59'
		),
		array(
			'id' => '30',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Dashboard',
			'lft' => '64',
			'rght' => '71'
		),
		array(
			'id' => '33',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'ErrorHandler',
			'lft' => '72',
			'rght' => '77'
		),
		array(
			'id' => '35',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'SystemEmail',
			'lft' => '78',
			'rght' => '91'
		),
		array(
			'id' => '48',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'AclManager',
			'lft' => '92',
			'rght' => '109'
		),
		array(
			'id' => '49',
			'parent_id' => '48',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Acl',
			'lft' => '93',
			'rght' => '108'
		),
		array(
			'id' => '50',
			'parent_id' => '49',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'drop',
			'lft' => '94',
			'rght' => '95'
		),
		array(
			'id' => '51',
			'parent_id' => '49',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'drop_perms',
			'lft' => '96',
			'rght' => '97'
		),
		array(
			'id' => '52',
			'parent_id' => '49',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'index',
			'lft' => '98',
			'rght' => '99'
		),
		array(
			'id' => '53',
			'parent_id' => '49',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'permissions',
			'lft' => '100',
			'rght' => '101'
		),
		array(
			'id' => '54',
			'parent_id' => '49',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'update_acos',
			'lft' => '102',
			'rght' => '103'
		),
		array(
			'id' => '55',
			'parent_id' => '49',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'update_aros',
			'lft' => '104',
			'rght' => '105'
		),
		array(
			'id' => '56',
			'parent_id' => '1',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketing',
			'lft' => '110',
			'rght' => '281'
		),
		array(
			'id' => '78',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingDashboard',
			'lft' => '111',
			'rght' => '116'
		),
		array(
			'id' => '80',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingPlans',
			'lft' => '117',
			'rght' => '136'
		),
		array(
			'id' => '83',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'register',
			'lft' => '25',
			'rght' => '26'
		),
		array(
			'id' => '101',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'activateAccount',
			'lft' => '27',
			'rght' => '28'
		),
		array(
			'id' => '102',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'forgetPassword',
			'lft' => '29',
			'rght' => '30'
		),
		array(
			'id' => '104',
			'parent_id' => '16',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'resetPassword',
			'lft' => '31',
			'rght' => '32'
		),
		array(
			'id' => '108',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingMailingLists',
			'lft' => '137',
			'rght' => '152'
		),
		array(
			'id' => '111',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingSubscribers',
			'lft' => '153',
			'rght' => '172'
		),
		array(
			'id' => '114',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingUsers',
			'lft' => '173',
			'rght' => '188'
		),
		array(
			'id' => '131',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingBlacklistedSubscribers',
			'lft' => '189',
			'rght' => '202'
		),
		array(
			'id' => '137',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingCampaigns',
			'lft' => '203',
			'rght' => '224'
		),
		array(
			'id' => '143',
			'parent_id' => '56',
			'model' => '',
			'foreign_key' => null,
			'alias' => 'EmailMarketingTemplates',
			'lft' => '225',
			'rght' => '238'
		),
		array(
			'id' => '153',
			'parent_id' => '56',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'EmailMarketingStatistics',
			'lft' => '239',
			'rght' => '264'
		),
		array(
			'id' => '165',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Logs',
			'lft' => '282',
			'rght' => '289'
		),
		array(
			'id' => '169',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Configurations',
			'lft' => '290',
			'rght' => '305'
		),
		array(
			'id' => '200',
			'parent_id' => '56',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'EmailMarketingSenders',
			'lft' => '265',
			'rght' => '280'
		),
		array(
			'id' => '207',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Payment',
			'lft' => '306',
			'rght' => '351'
		),
		array(
			'id' => '208',
			'parent_id' => '207',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'PaymentDashboard',
			'lft' => '307',
			'rght' => '312'
		),
		array(
			'id' => '210',
			'parent_id' => '207',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'PaymentInvoices',
			'lft' => '313',
			'rght' => '334'
		),
		array(
			'id' => '216',
			'parent_id' => '207',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'PaymentPayPalGateway',
			'lft' => '335',
			'rght' => '350'
		),
		array(
			'id' => '225',
			'parent_id' => '35',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'sendContactEmail',
			'lft' => '79',
			'rght' => '80'
		),
		array(
			'id' => '226',
			'parent_id' => '35',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'sendNewUserActivateEmail',
			'lft' => '81',
			'rght' => '82'
		),
		array(
			'id' => '227',
			'parent_id' => '35',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'sendResetPasswordEmail',
			'lft' => '83',
			'rght' => '84'
		),
		array(
			'id' => '228',
			'parent_id' => '35',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'sendInvoiceEmail',
			'lft' => '85',
			'rght' => '86'
		),
		array(
			'id' => '233',
			'parent_id' => '30',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '65',
			'rght' => '66'
		),
		array(
			'id' => '239',
			'parent_id' => '35',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'sendReceiptEmail',
			'lft' => '87',
			'rght' => '88'
		),
		array(
			'id' => '262',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '33',
			'rght' => '34'
		),
		array(
			'id' => '263',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '35',
			'rght' => '36'
		),
		array(
			'id' => '264',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '37',
			'rght' => '38'
		),
		array(
			'id' => '265',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '39',
			'rght' => '40'
		),
		array(
			'id' => '266',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '41',
			'rght' => '42'
		),
		array(
			'id' => '267',
			'parent_id' => '169',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '291',
			'rght' => '292'
		),
		array(
			'id' => '268',
			'parent_id' => '169',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '293',
			'rght' => '294'
		),
		array(
			'id' => '269',
			'parent_id' => '169',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '295',
			'rght' => '296'
		),
		array(
			'id' => '270',
			'parent_id' => '169',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '297',
			'rght' => '298'
		),
		array(
			'id' => '271',
			'parent_id' => '169',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '299',
			'rght' => '300'
		),
		array(
			'id' => '272',
			'parent_id' => '30',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '67',
			'rght' => '68'
		),
		array(
			'id' => '273',
			'parent_id' => '33',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '73',
			'rght' => '74'
		),
		array(
			'id' => '274',
			'parent_id' => '8',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '3',
			'rght' => '4'
		),
		array(
			'id' => '275',
			'parent_id' => '8',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '5',
			'rght' => '6'
		),
		array(
			'id' => '276',
			'parent_id' => '8',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '7',
			'rght' => '8'
		),
		array(
			'id' => '277',
			'parent_id' => '8',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '9',
			'rght' => '10'
		),
		array(
			'id' => '278',
			'parent_id' => '165',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '283',
			'rght' => '284'
		),
		array(
			'id' => '279',
			'parent_id' => '165',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '285',
			'rght' => '286'
		),
		array(
			'id' => '281',
			'parent_id' => '131',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '190',
			'rght' => '191'
		),
		array(
			'id' => '282',
			'parent_id' => '131',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '192',
			'rght' => '193'
		),
		array(
			'id' => '283',
			'parent_id' => '131',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '194',
			'rght' => '195'
		),
		array(
			'id' => '284',
			'parent_id' => '131',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_import',
			'lft' => '196',
			'rght' => '197'
		),
		array(
			'id' => '286',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '204',
			'rght' => '205'
		),
		array(
			'id' => '287',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '206',
			'rght' => '207'
		),
		array(
			'id' => '288',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '208',
			'rght' => '209'
		),
		array(
			'id' => '289',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '210',
			'rght' => '211'
		),
		array(
			'id' => '290',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '212',
			'rght' => '213'
		),
		array(
			'id' => '291',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_sendEmail',
			'lft' => '214',
			'rght' => '215'
		),
		array(
			'id' => '292',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_checkSendingProcess',
			'lft' => '216',
			'rght' => '217'
		),
		array(
			'id' => '293',
			'parent_id' => '78',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '112',
			'rght' => '113'
		),
		array(
			'id' => '295',
			'parent_id' => '108',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '138',
			'rght' => '139'
		),
		array(
			'id' => '296',
			'parent_id' => '108',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '140',
			'rght' => '141'
		),
		array(
			'id' => '297',
			'parent_id' => '108',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '142',
			'rght' => '143'
		),
		array(
			'id' => '298',
			'parent_id' => '108',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '144',
			'rght' => '145'
		),
		array(
			'id' => '299',
			'parent_id' => '108',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '146',
			'rght' => '147'
		),
		array(
			'id' => '300',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '118',
			'rght' => '119'
		),
		array(
			'id' => '301',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '120',
			'rght' => '121'
		),
		array(
			'id' => '302',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '122',
			'rght' => '123'
		),
		array(
			'id' => '303',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '124',
			'rght' => '125'
		),
		array(
			'id' => '304',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '126',
			'rght' => '127'
		),
		array(
			'id' => '305',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_alter',
			'lft' => '128',
			'rght' => '129'
		),
		array(
			'id' => '306',
			'parent_id' => '200',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '266',
			'rght' => '267'
		),
		array(
			'id' => '307',
			'parent_id' => '200',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '268',
			'rght' => '269'
		),
		array(
			'id' => '308',
			'parent_id' => '200',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '270',
			'rght' => '271'
		),
		array(
			'id' => '309',
			'parent_id' => '200',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '272',
			'rght' => '273'
		),
		array(
			'id' => '310',
			'parent_id' => '200',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '274',
			'rght' => '275'
		),
		array(
			'id' => '311',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '240',
			'rght' => '241'
		),
		array(
			'id' => '313',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_viewCampaignSubscribersStatistics',
			'lft' => '242',
			'rght' => '243'
		),
		array(
			'id' => '314',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_viewCampaignEmailLinksStatistics',
			'lft' => '244',
			'rght' => '245'
		),
		array(
			'id' => '315',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_viewStatisticsBySubscriber',
			'lft' => '246',
			'rght' => '247'
		),
		array(
			'id' => '316',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_viewStatisticsByEmailLink',
			'lft' => '248',
			'rght' => '249'
		),
		array(
			'id' => '317',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '250',
			'rght' => '251'
		),
		array(
			'id' => '318',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_trackOpen',
			'lft' => '252',
			'rght' => '253'
		),
		array(
			'id' => '319',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_trackClick',
			'lft' => '254',
			'rght' => '255'
		),
		array(
			'id' => '320',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '154',
			'rght' => '155'
		),
		array(
			'id' => '321',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '156',
			'rght' => '157'
		),
		array(
			'id' => '322',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '158',
			'rght' => '159'
		),
		array(
			'id' => '323',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '160',
			'rght' => '161'
		),
		array(
			'id' => '324',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '162',
			'rght' => '163'
		),
		array(
			'id' => '325',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_import',
			'lft' => '164',
			'rght' => '165'
		),
		array(
			'id' => '327',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_removeInvalidSubscriber',
			'lft' => '166',
			'rght' => '167'
		),
		array(
			'id' => '328',
			'parent_id' => '143',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '226',
			'rght' => '227'
		),
		array(
			'id' => '329',
			'parent_id' => '143',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '228',
			'rght' => '229'
		),
		array(
			'id' => '330',
			'parent_id' => '143',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '230',
			'rght' => '231'
		),
		array(
			'id' => '331',
			'parent_id' => '143',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '232',
			'rght' => '233'
		),
		array(
			'id' => '332',
			'parent_id' => '143',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '234',
			'rght' => '235'
		),
		array(
			'id' => '333',
			'parent_id' => '114',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '174',
			'rght' => '175'
		),
		array(
			'id' => '334',
			'parent_id' => '114',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '176',
			'rght' => '177'
		),
		array(
			'id' => '335',
			'parent_id' => '114',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '178',
			'rght' => '179'
		),
		array(
			'id' => '336',
			'parent_id' => '114',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '180',
			'rght' => '181'
		),
		array(
			'id' => '337',
			'parent_id' => '114',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_delete',
			'lft' => '182',
			'rght' => '183'
		),
		array(
			'id' => '338',
			'parent_id' => '208',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '308',
			'rght' => '309'
		),
		array(
			'id' => '339',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_index',
			'lft' => '314',
			'rght' => '315'
		),
		array(
			'id' => '342',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '316',
			'rght' => '317'
		),
		array(
			'id' => '343',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_add',
			'lft' => '318',
			'rght' => '319'
		),
		array(
			'id' => '344',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_edit',
			'lft' => '320',
			'rght' => '321'
		),
		array(
			'id' => '346',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_email',
			'lft' => '322',
			'rght' => '323'
		),
		array(
			'id' => '347',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_generateInvoiceFile',
			'lft' => '324',
			'rght' => '325'
		),
		array(
			'id' => '348',
			'parent_id' => '216',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_expressCheckout',
			'lft' => '336',
			'rght' => '337'
		),
		array(
			'id' => '349',
			'parent_id' => '216',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_recurringPayment',
			'lft' => '338',
			'rght' => '339'
		),
		array(
			'id' => '350',
			'parent_id' => '216',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_refund',
			'lft' => '340',
			'rght' => '341'
		),
		array(
			'id' => '351',
			'parent_id' => '216',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_storeCreditCard',
			'lft' => '342',
			'rght' => '343'
		),
		array(
			'id' => '352',
			'parent_id' => '216',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_updateCreditCard',
			'lft' => '344',
			'rght' => '345'
		),
		array(
			'id' => '353',
			'parent_id' => '216',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_deleteCreditCard',
			'lft' => '346',
			'rght' => '347'
		),
		array(
			'id' => '354',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_login',
			'lft' => '43',
			'rght' => '44'
		),
		array(
			'id' => '355',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_logout',
			'lft' => '45',
			'rght' => '46'
		),
		array(
			'id' => '357',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '47',
			'rght' => '48'
		),
		array(
			'id' => '358',
			'parent_id' => '8',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '11',
			'rght' => '12'
		),
		array(
			'id' => '359',
			'parent_id' => '169',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '301',
			'rght' => '302'
		),
		array(
			'id' => '360',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Errors',
			'lft' => '352',
			'rght' => '357'
		),
		array(
			'id' => '361',
			'parent_id' => '360',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'display',
			'lft' => '353',
			'rght' => '354'
		),
		array(
			'id' => '363',
			'parent_id' => '8',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_view',
			'lft' => '13',
			'rght' => '14'
		),
		array(
			'id' => '364',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_listGroupUsers',
			'lft' => '49',
			'rght' => '50'
		),
		array(
			'id' => '366',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_unpaidInvoiceIndex',
			'lft' => '326',
			'rght' => '327'
		),
		array(
			'id' => '367',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_paidInvoiceIndex',
			'lft' => '328',
			'rght' => '329'
		),
		array(
			'id' => '396',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '130',
			'rght' => '131'
		),
		array(
			'id' => '397',
			'parent_id' => '200',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '276',
			'rght' => '277'
		),
		array(
			'id' => '398',
			'parent_id' => '108',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '148',
			'rght' => '149'
		),
		array(
			'id' => '399',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '168',
			'rght' => '169'
		),
		array(
			'id' => '400',
			'parent_id' => '114',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '184',
			'rght' => '185'
		),
		array(
			'id' => '401',
			'parent_id' => '131',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '198',
			'rght' => '199'
		),
		array(
			'id' => '404',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_viewCampaignHistory',
			'lft' => '256',
			'rght' => '257'
		),
		array(
			'id' => '405',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_preview',
			'lft' => '218',
			'rght' => '219'
		),
		array(
			'id' => '406',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchDelete',
			'lft' => '220',
			'rght' => '221'
		),
		array(
			'id' => '407',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_getSubscriberClickRecord',
			'lft' => '258',
			'rght' => '259'
		),
		array(
			'id' => '408',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_getSubscriberOpenRecord',
			'lft' => '260',
			'rght' => '261'
		),
		array(
			'id' => '409',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_batchEmail',
			'lft' => '330',
			'rght' => '331'
		),
		array(
			'id' => '410',
			'parent_id' => '169',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '303',
			'rght' => '304'
		),
		array(
			'id' => '411',
			'parent_id' => '30',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '69',
			'rght' => '70'
		),
		array(
			'id' => '412',
			'parent_id' => '33',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '75',
			'rght' => '76'
		),
		array(
			'id' => '413',
			'parent_id' => '360',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '355',
			'rght' => '356'
		),
		array(
			'id' => '414',
			'parent_id' => '8',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '15',
			'rght' => '16'
		),
		array(
			'id' => '415',
			'parent_id' => '165',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '287',
			'rght' => '288'
		),
		array(
			'id' => '416',
			'parent_id' => '14',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '21',
			'rght' => '22'
		),
		array(
			'id' => '417',
			'parent_id' => '35',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '89',
			'rght' => '90'
		),
		array(
			'id' => '418',
			'parent_id' => '16',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '51',
			'rght' => '52'
		),
		array(
			'id' => '419',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'AclExtras',
			'lft' => '358',
			'rght' => '359'
		),
		array(
			'id' => '420',
			'parent_id' => '49',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '106',
			'rght' => '107'
		),
		array(
			'id' => '421',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Composer',
			'lft' => '360',
			'rght' => '361'
		),
		array(
			'id' => '422',
			'parent_id' => '26',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '60',
			'rght' => '61'
		),
		array(
			'id' => '423',
			'parent_id' => '131',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '200',
			'rght' => '201'
		),
		array(
			'id' => '424',
			'parent_id' => '137',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '222',
			'rght' => '223'
		),
		array(
			'id' => '425',
			'parent_id' => '78',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '114',
			'rght' => '115'
		),
		array(
			'id' => '426',
			'parent_id' => '108',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '150',
			'rght' => '151'
		),
		array(
			'id' => '427',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '132',
			'rght' => '133'
		),
		array(
			'id' => '428',
			'parent_id' => '200',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '278',
			'rght' => '279'
		),
		array(
			'id' => '429',
			'parent_id' => '153',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '262',
			'rght' => '263'
		),
		array(
			'id' => '430',
			'parent_id' => '111',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '170',
			'rght' => '171'
		),
		array(
			'id' => '431',
			'parent_id' => '143',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '236',
			'rght' => '237'
		),
		array(
			'id' => '432',
			'parent_id' => '114',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '186',
			'rght' => '187'
		),
		array(
			'id' => '433',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Facebook',
			'lft' => '362',
			'rght' => '363'
		),
		array(
			'id' => '434',
			'parent_id' => '208',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '310',
			'rght' => '311'
		),
		array(
			'id' => '435',
			'parent_id' => '210',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '332',
			'rght' => '333'
		),
		array(
			'id' => '436',
			'parent_id' => '216',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'isAuthorized',
			'lft' => '348',
			'rght' => '349'
		),
		array(
			'id' => '437',
			'parent_id' => '1',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'Phpunit',
			'lft' => '364',
			'rght' => '365'
		),
		array(
			'id' => '438',
			'parent_id' => '80',
			'model' => null,
			'foreign_key' => null,
			'alias' => 'admin_enable',
			'lft' => '134',
			'rght' => '135'
		),
	);

}
