<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * EmailMarketingSubscriber Test Case
 *
 */
class EmailMarketingSubscriberTest extends CakeTestCase {

	protected $EmailMarketingSubscriber;

	public function setUp() {
		parent::setUp();

		$this->EmailMarketingSubscriber = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
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
 * testIsListUniqueEmail method
 *
 * @return void
 */
	public function testIsListUniqueEmail() {
		$this->markTestSkipped('This function defines the model validation rule.');
	}

/**
 * testIsNotBlacklistedEmail method
 *
 * @return void
 */
	public function testIsNotBlacklistedEmail() {
		$this->markTestSkipped('This function defines the model validation rule.');
	}

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
		// Test soft-delete
		$subscriber = $this->EmailMarketingSubscriber->browseBy('id', 1);
		$this->assertEqual($subscriber['EmailMarketingSubscriber']['deleted'], 0);

		$this->EmailMarketingSubscriber->deleteSubscriber(1);

		$subscriber = $this->EmailMarketingSubscriber->browseBy('id', 1);
		$this->assertEqual($subscriber['EmailMarketingSubscriber']['deleted'], 1);
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
		$result = $this->EmailMarketingSubscriber->isUploadedFile($subscribedFile);
		$this->assertFalse($result);

		// Upload file with no name
		$subscribedFile = array(
			'name' => 'test_file',
			'type' => 'text/csv',
			'tmp_name' => '',
			'error' => 0,
			'size' => 471,
		);
		$result = $this->EmailMarketingSubscriber->isUploadedFile($subscribedFile);
		$this->assertFalse($result);

		// Upload empty file
		$subscribedFile = array(
			'name' => 'Subscriber-import-file-sample.csv',
			'type' => 'text/csv',
			'tmp_name' => 'asdasd33f',
			'error' => 0,
			'size' => 0,
		);
		$result = $this->EmailMarketingSubscriber->isUploadedFile($subscribedFile);
		$this->assertFalse($result);
	}

/**
 * testImportSubscriber method
 *
 * @return void
 */
	public function testImportSubscriber() {

		$emailMarketingUserId = 20;
		$emailMarketingListId = 1;

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_user_id' => $emailMarketingUserId,
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Blacklisted-Subscriber-import-file-sample.csv',
				'error' => 0,
				'size' => 471,
			)
		));

		$result = $this->EmailMarketingSubscriber->importSubscriber($data);
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

		$emailMarketingListId = 1;

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_user_id' => 20,
			'email_marketing_list_id' => $emailMarketingListId,
			'date_from' => array('year' => 2014, 'month' => 7, 'day' => 19, 'hour' => 0, 'min' => 0, 'meridian' => 'am'),
			'date_to' => array('year' => 2015, 'month' => 8, 'day' => 23, 'hour' => 11, 'min' => 0, 'meridian' => 'pm'),
			'included_columns' => array('email')
		));
		$result = $this->EmailMarketingSubscriber->exportSubscriber($data);
		$lines = substr_count($result, "\n");
		$this->assertEqual($lines, 6);
		$this->assertContains('kerry-fly@163.com', $result);

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_user_id' => 20,
			'email_marketing_list_id' => $emailMarketingListId,
			'date_from' => array('year' => 2014, 'month' => 7, 'day' => 19, 'hour' => 0, 'min' => 0, 'meridian' => 'am'),
			'date_to' => array('year' => 2015, 'month' => 8, 'day' => 22, 'hour' => 11, 'min' => 0, 'meridian' => 'pm'),
			'included_columns' => array('email')
		));
		$result = $this->EmailMarketingSubscriber->exportSubscriber($data);
		$lines = substr_count($result, "\n");
		$this->assertEqual($lines, 6);
	}

/**
 * testCheckSubscriberBelongToCampaign method
 *
 * @return void
 */
	public function testCheckSubscriberBelongToCampaign() {
		// Filter the given subscriber list and return the subscriber IDs which belongs to the given campagin

		$campaignId = 1;
		$subscriberIds = [1, 2, 6];

		$subscribersBelongToCampaign = $this->EmailMarketingSubscriber->checkSubscriberBelongToCampaign($campaignId, $subscriberIds);

		$this->assertEqual($subscribersBelongToCampaign, [1, 2]);
	}
}