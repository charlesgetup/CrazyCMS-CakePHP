<?php
/**
 * EmailMarketingEmailLinkFixture
 *
 */
class EmailMarketingEmailLinkFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_statistic_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'url' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'statistic_id' => array('column' => 'email_marketing_statistic_id', 'unique' => 0)
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
			'email_marketing_statistic_id' => '203',
			'url' => 'http://google.com'
		),
		array(
			'id' => '2',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6b6306333118a193a6fe1f73b929691e2d2bbee338333e0c454f98f599baf31ca1'
		),
		array(
			'id' => '3',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6b2857c16eb1b203a5d854cf23b64c68fabc18a623ab5a729de875482d282ee687'
		),
		array(
			'id' => '4',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6ba6e59acce46d20d85852c4ab6894e5543c9b4333bcc4ccdfe841944cd1aa0cc2'
		),
		array(
			'id' => '5',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6b2857c16eb1b203a5d854cf23b64c68fabc18a623ab5a729de875482d282ee687'
		),
		array(
			'id' => '6',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6b2857c16eb1b203a5d854cf23b64c68fabc18a623ab5a729de875482d282ee687'
		),
		array(
			'id' => '7',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6b2857c16eb1b203a5d854cf23b64c68fabc18a623ab5a729de875482d282ee687'
		),
		array(
			'id' => '8',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6b2857c16eb1b203a5d854cf23b64c68fabc18a623ab5a729de875482d282ee687'
		),
		array(
			'id' => '9',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6ba18ac8b0f1814dc0d888669f12fcb12173b39336854b1f673498e7625a298baf'
		),
		array(
			'id' => '10',
			'email_marketing_statistic_id' => '203',
			'url' => 'http://click.email.amfbowling.com.au/?qs=1b64b17ee77a4f6ba18ac8b0f1814dc0d888669f12fcb12173b39336854b1f673498e7625a298baf'
		),
	);

}
