<?php
/**
 * ConfigurationFixture
 *
 */
class ConfigurationFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'key' => 'index', 'comment' => 'User ID'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'comment' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1000, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'modified_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'User ID'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'type' => array('column' => 'type', 'unique' => 0),
			'name' => array('column' => 'name', 'unique' => 0),
			'modified' => array('column' => 'modified', 'unique' => 0),
			'modified_by' => array('column' => 'modified_by', 'unique' => 0),
			'user_id' => array('column' => 'user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'CompanyName',
			'value' => 'CrazySoft',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '2',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'CompanyLogo',
			'value' => '<span class="color-1">Crazy</span>Soft',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '3',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'CompanyAddress',
			'value' => 'Unit J501, 27-29 George Street, North Strathfield, NSW, 2137, Australia.',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '4',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'CompanyEmail',
			'value' => 'contact@crazycms.net',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '5',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'CompanyPhone',
			'value' => '',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '6',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'CompanyDomain',
			'value' => 'crazycms.loc',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '7',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'AdminSystemName',
			'value' => 'Crazy Platform',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '8',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'AdminSystemLogo',
			'value' => '<span class="red">Crazy</span>&nbsp;<span class="white" id="id-text2">Platform</span>',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '9',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'AdminSystemFooter',
			'value' => '<span class="blue bolder">Crazy</span>&nbsp;Platform',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '10',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'MetaKeywords',
			'value' => 'crazy cms crazysoft web design development website seo email marketing e-commerce',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '11',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'MetaDescription',
			'value' => 'CrazySoft is an Australian professional online CMS company. We develop first-class websites, online apps for our clients and we also provide enterprise level marketing solutions.',
			'comment' => null,
			'modified' => '2015-04-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '13',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'PaymentNoticePeriod',
			'value' => '7',
			'comment' => 'Generate invoice X days ahead of next pay day & warning over due payment X days after the pay day',
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '14',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'PaymentExpiredPeriod',
			'value' => '14',
			'comment' => 'Clear over due payment after X days.Downgrade monthly plan to prepaid plan.',
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '15',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'FreeEmails',
			'value' => '100',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '16',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'XORMask',
			'value' => '76859309656749683645',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '17',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'ParallelTaskType',
			'value' => 'EmailMarketing',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '18',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'UploadfileSizeLimit',
			'value' => '5242880',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '19',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQRunning',
			'value' => '0',
			'comment' => null,
			'modified' => '2014-06-18 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '20',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQMaxParallelThread',
			'value' => '10',
			'comment' => null,
			'modified' => '2014-06-21 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '21',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQJobFetchInterval',
			'value' => '1',
			'comment' => null,
			'modified' => '2014-06-21 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '22',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQPollFetchInterval',
			'value' => '1',
			'comment' => null,
			'modified' => '2014-06-21 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '23',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQMaxFetchAmount',
			'value' => '1',
			'comment' => null,
			'modified' => '2014-06-21 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '24',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQMaxIdelTime',
			'value' => '30',
			'comment' => null,
			'modified' => '2014-06-21 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '25',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQMaxWorkerAmount',
			'value' => '5',
			'comment' => null,
			'modified' => '2014-06-21 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '26',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQDebug',
			'value' => '1',
			'comment' => null,
			'modified' => '2015-04-02 17:22:28',
			'modified_by' => '1'
		),
		array(
			'id' => '27',
			'user_id' => '1',
			'type' => 'SYSTEM',
			'name' => 'ZMQDebugOutputMethod',
			'value' => 'CakeLog::error',
			'comment' => null,
			'modified' => '2014-06-21 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '28',
			'user_id' => '1',
			'type' => 'PAYMENT',
			'name' => 'DefaultEmailFromAddress',
			'value' => 'payment@crazycms.net',
			'comment' => null,
			'modified' => '2014-08-19 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '29',
			'user_id' => '1',
			'type' => 'PAYMENT',
			'name' => 'BounceToMailBoxPassword',
			'value' => null,
			'comment' => null,
			'modified' => '2014-04-02 20:57:13',
			'modified_by' => '1'
		),
		array(
			'id' => '30',
			'user_id' => '1',
			'type' => 'PAYMENT',
			'name' => 'BounceToMailBox',
			'value' => 'payment_bounce@crazycms.net',
			'comment' => null,
			'modified' => '2014-04-02 20:56:45',
			'modified_by' => '1'
		),
		array(
			'id' => '31',
			'user_id' => '1',
			'type' => 'PAYMENT',
			'name' => 'BounceToMailBoxUsername',
			'value' => 'payment_bounce@crazycms.net',
			'comment' => null,
			'modified' => '2014-04-02 20:56:30',
			'modified_by' => '1'
		),
		array(
			'id' => '32',
			'user_id' => '1',
			'type' => 'PAYMENT',
			'name' => 'ParallelTaskType',
			'value' => 'Payment',
			'comment' => null,
			'modified' => '2014-02-13 16:38:34',
			'modified_by' => '1'
		),
		array(
			'id' => '91',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'DKIMSelector',
			'value' => 'DNS',
			'comment' => null,
			'modified' => '2014-06-29 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '92',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'SMTPHostName',
			'value' => 'CrazySoftMail',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '93',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'SMTPHost',
			'value' => 'sg2plcpnl0014.prod.sin2.secureserver.net',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '94',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'SMTPHostPort',
			'value' => '465',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '95',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'SMTPHostUsername',
			'value' => 'marketing@crazysoft.com.au',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '96',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'SMTPHostPassword',
			'value' => 'PPvRv@r]mB@P',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '97',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'DefaultEmailFrom',
			'value' => 'marketing@crazysoft.com.au',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '98',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'CharSet',
			'value' => 'UTF-8',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '99',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'Encoding',
			'value' => '7bit',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '100',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'ContentType',
			'value' => 'text/html',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '101',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'SMTPServerTimeout',
			'value' => '5',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '102',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'WordWrap',
			'value' => '0',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '103',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'SendFormat',
			'value' => 'HTML',
			'comment' => null,
			'modified' => '2014-02-16 00:00:00',
			'modified_by' => '1'
		),
		array(
			'id' => '104',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxPassword',
			'value' => '#r^*PVaHT6ss',
			'comment' => null,
			'modified' => '2014-04-02 20:57:13',
			'modified_by' => '1'
		),
		array(
			'id' => '105',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBox',
			'value' => 'email_marketing_bounce@crazycms.net',
			'comment' => null,
			'modified' => '2014-04-02 20:56:45',
			'modified_by' => '1'
		),
		array(
			'id' => '106',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxUsername',
			'value' => 'email_marketing_bounce@crazycms.net',
			'comment' => null,
			'modified' => '2014-04-02 20:56:30',
			'modified_by' => '1'
		),
		array(
			'id' => '113',
			'user_id' => '132',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBox',
			'value' => null,
			'comment' => null,
			'modified' => '2014-06-29 15:31:10',
			'modified_by' => null
		),
		array(
			'id' => '114',
			'user_id' => '132',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxUsername',
			'value' => null,
			'comment' => null,
			'modified' => '2014-06-29 15:31:10',
			'modified_by' => null
		),
		array(
			'id' => '115',
			'user_id' => '132',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxPassword',
			'value' => null,
			'comment' => null,
			'modified' => '2014-06-29 15:31:10',
			'modified_by' => null
		),
		array(
			'id' => '191',
			'user_id' => '132',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBox',
			'value' => null,
			'comment' => null,
			'modified' => '2015-06-10 17:08:57',
			'modified_by' => null
		),
		array(
			'id' => '192',
			'user_id' => '132',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxUsername',
			'value' => null,
			'comment' => null,
			'modified' => '2015-06-10 17:08:57',
			'modified_by' => null
		),
		array(
			'id' => '193',
			'user_id' => '132',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxPassword',
			'value' => null,
			'comment' => null,
			'modified' => '2015-06-10 17:08:57',
			'modified_by' => null
		),
		array(
			'id' => '194',
			'user_id' => '235',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBox',
			'value' => null,
			'comment' => null,
			'modified' => '2017-08-11 14:33:56',
			'modified_by' => null
		),
		array(
			'id' => '195',
			'user_id' => '235',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxUsername',
			'value' => null,
			'comment' => null,
			'modified' => '2017-08-11 14:33:56',
			'modified_by' => null
		),
		array(
			'id' => '196',
			'user_id' => '235',
			'type' => 'EMAIL_MARKETING',
			'name' => 'BounceToMailBoxPassword',
			'value' => null,
			'comment' => null,
			'modified' => '2017-08-11 14:33:56',
			'modified_by' => null
		),
		array(
			'id' => '197',
			'user_id' => '1',
			'type' => 'EMAIL_MARKETING',
			'name' => 'Introduction',
			'value' => 'This is a email marketing introduction',
			'comment' => null,
			'modified' => '2017-09-11 14:33:56',
			'modified_by' => null
		),
	);

}
