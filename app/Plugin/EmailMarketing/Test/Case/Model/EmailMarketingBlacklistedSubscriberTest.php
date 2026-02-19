<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * EmailMarketingBlacklistedSubscriber Test Case
 *
 */
class EmailMarketingBlacklistedSubscriberTest extends CakeTestCase {

	protected $EmailMarketingBlacklistedSubscriber;

	public function setUp() {
		parent::setUp();

		$this->EmailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketing.EmailMarketingBlacklistedSubscriber');
	}

	public function tearDown() {
		parent::tearDown();
	}

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.group',
		'app.aco',
		'app.aro',
		'app.arosaco',
		'plugin.email_marketing.email_marketing_user',
		'plugin.email_marketing.email_marketing_plan',
		'plugin.email_marketing.email_marketing_blacklisted_subscriber',
		'plugin.email_marketing.email_marketing_campaign',
		'plugin.email_marketing.email_marketing_campaign_list',
		'plugin.email_marketing.email_marketing_email_link',
		'plugin.email_marketing.email_marketing_mailing_list',
		'plugin.email_marketing.email_marketing_purchased_template',
		'plugin.email_marketing.email_marketing_sender',
		'plugin.email_marketing.email_marketing_statistic',
		'plugin.email_marketing.email_marketing_subscriber_click_record',
		'plugin.email_marketing.email_marketing_subscriber',
		'plugin.email_marketing.email_marketing_subscriber_open_record',
		'plugin.email_marketing.email_marketing_template',
		'app.address',
		'app.country',
		'app.configuration'
	);

/**
 * testSaveSubscriber method
 *
 * @return void
 */
	public function testSaveSubscriber() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testUpdateSubscriber method
 *
 * @return void
 */
	public function testUpdateSubscriber() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testDeleteSubscriber method
 *
 * @return void
 */
	public function testDeleteSubscriber() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testCheckUploadedTempFile method
 *
 * @return void
 */
	public function testCheckUploadedTempFile() {
		$this->markTestSkipped('This function is a wrapper of PHP function "is_uploaded_file", no customised logic invloved.');
	}

/**
 * testMoveUploadedFile method
 *
 * @return void
 */
	public function testMoveUploadedFile() {
		$this->markTestSkipped('This function is a wrapper of PHP function "move_uploaded_file", no customised logic invloved.');
	}

/**
 * testIsUploadedFile method
 *
 * @return void
 */
	public function testIsUploadedFile() {

		// Upload file with error
		$subscribedFile = array(
			'name' => '',
			'type' => '',
			'tmp_name' => '',
			'error' => 4,
			'size' => 0,
		);
		$result = $this->EmailMarketingBlacklistedSubscriber->isUploadedFile($subscribedFile);
		$this->assertFalse($result);

		// Upload file with no name
		$subscribedFile = array(
			'name' => 'test_file',
			'type' => 'text/csv',
			'tmp_name' => '',
			'error' => 0,
			'size' => 471,
		);
		$result = $this->EmailMarketingBlacklistedSubscriber->isUploadedFile($subscribedFile);
		$this->assertFalse($result);

		// Upload empty file
		$subscribedFile = array(
			'name' => 'Blacklisted-Subscriber-import-file-sample.csv',
			'type' => 'text/csv',
			'tmp_name' => 'asdasd33f',
			'error' => 0,
			'size' => 0,
		);
		$result = $this->EmailMarketingBlacklistedSubscriber->isUploadedFile($subscribedFile);
		$this->assertFalse($result);
	}

/**
 * testImportSubscriber method
 *
 * @return void
 */
	public function testImportSubscriber() {

		$emailMarketingUserId = 20;

		$data = array('EmailMarketingBlacklistedSubscriber' => array(
			'email_marketing_user_id' => $emailMarketingUserId,
			'subscriber_file' => array(
				'name' => 'Blacklisted-Subscriber-import-file-sample.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Blacklisted-Subscriber-import-file-sample.csv',
				'error' => 0,
				'size' => 471,
			)
		));

		$result = $this->EmailMarketingBlacklistedSubscriber->importSubscriber($data);
		$this->assertEqual($result['saved'], 12);
		$this->assertEqual($result['duplicated'], 0);
		$this->assertEqual($result['invalid'], 0);

	}

/**
 * testExportSubscriber method
 *
 * @return void
 */
	public function testExportSubscriber() {
		$data = array('EmailMarketingBlacklistedSubscriber' => array(
			'email_marketing_user_id' => 20,
			'date_from' => array('year' => 2015, 'month' => 8, 'day' => 19, 'hour' => 0, 'min' => 0, 'meridian' => 'am'),
			'date_to' => array('year' => 2015, 'month' => 8, 'day' => 20, 'hour' => 11, 'min' => 0, 'meridian' => 'pm'),
			'included_columns' => array('email')
		));
		$result = $this->EmailMarketingBlacklistedSubscriber->exportSubscriber($data);
		$lines = substr_count($result, "\n");
		$this->assertEqual($lines, 3);
		$expectCsvContent = 'Email' .PHP_EOL . 'test@example.com'.PHP_EOL .'test123@example.com'.PHP_EOL;
		$this->assertEqual($result, $expectCsvContent);

		$data = array('EmailMarketingBlacklistedSubscriber' => array(
			'email_marketing_user_id' => 20,
			'date_from' => array('year' => 2015, 'month' => 8, 'day' => 19, 'hour' => 0, 'min' => 0, 'meridian' => 'am'),
			'date_to' => array('year' => 2015, 'month' => 8, 'day' => 19, 'hour' => 11, 'min' => 0, 'meridian' => 'pm'),
			'included_columns' => array('email')
		));
		$result = $this->EmailMarketingBlacklistedSubscriber->exportSubscriber($data);
		$lines = substr_count($result, "\n");
		$this->assertEqual($lines, 2);
		$expectCsvContent = 'Email' .PHP_EOL . 'test@example.com'.PHP_EOL;
		$this->assertEqual($result, $expectCsvContent);
	}
}