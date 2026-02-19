<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * EmailMarketingSender Test Case
 *
 */
class EmailMarketingSenderTest extends CakeTestCase {

	protected $EmailMarketingSender;

	public function setUp() {
		parent::setUp();

		$this->EmailMarketingSender = ClassRegistry::init('EmailMarketing.EmailMarketingSender');
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
 * testConstructVirtualField method
 *
 * @return void
 */
	public function testConstructVirtualField() {
		$senderId = 3;

		App::uses('CakeSession', 'Model/Datasource');
		CakeSession::write('Auth.User.id', 132); // Fake a logged in user

		App::uses('EmailMarketingSender', 'EmailMarketing.Model');
		$this->EmailMarketingSender = new EmailMarketingSender($senderId, 'email_marketing_senders', 'test');

		$this->assertEqual($this->EmailMarketingSender->field('public_key_download_link'), "<a href='" .$this->EmailMarketingSender->getUserFileSavedPath(false) ."132/EmailMarketing/DKIM/aroundyou2.info/DNS.txt' target='_blank'>Download</a>");
	}

/**
 * testGetDKIMSavedPath method
 *
 * @return void
 */
	public function testGetDKIMSavedPath() {
		$this->markTestSkipped('This function only puts argument into simple string.');
	}

/**
 * testGetSenderList method
 *
 * @return void
 */
	public function testGetSenderList() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testGetSenderDetailsById method
 *
 * @return void
 */
	public function testGetSenderDetailsById() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testSaveSender method
 *
 * @return void
 */
	public function testSaveSender() {

		App::uses('CakeSession', 'Model/Datasource');
		CakeSession::write('Auth.User.id', 132); // Fake a logged in user

		// The save method only uses CakePHP default functionalities, no customised logic invloved.
		// So here we only test the related private method __generateKeyFiles()

		$data = [
			'EmailMarketingSender' => [
				'email_marketing_user_id' => 203,
				'sender_domain' => 'crazysoft.com.au'
			]
		];
		$this->EmailMarketingSender->saveSender($data);

		$sender = $this->EmailMarketingSender->browseBy('sender_domain', 'crazysoft.com.au');
		$this->assertEqual($sender['EmailMarketingSender']['email_marketing_user_id'], 203);

		// Test private method __generateKeyFiles()
		// If have mkdir permission error, 1: check the current user using "exec('whoami')". 2: check the DIR created group and add the user to that group and give the group write permission
		$this->assertTrue(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.txt"));
		$this->assertTrue(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.private"));
		$this->assertGreaterThan(0, filesize(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.txt"));
		$this->assertGreaterThan(0, filesize(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.private"));

		// Clean up
		unlink(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.txt");
		unlink(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.private");
		rmdir(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au");
	}

/**
 * testUpdateSender method
 *
 * @return void
 */
	public function testUpdateSender() {

		App::uses('CakeSession', 'Model/Datasource');
		CakeSession::write('Auth.User.id', 132); // Fake a logged in user

		// The update method only uses CakePHP default functionalities, no customised logic invloved.
		// So here we only test the related private method __deleteKeyFiles()

		$data = [
			'EmailMarketingSender' => [
				'email_marketing_user_id' => 203,
				'sender_domain' => 'crazysoft.com.au'
			]
		];
		$this->EmailMarketingSender->saveSender($data);

		$sender = $this->EmailMarketingSender->browseBy('sender_domain', 'crazysoft.com.au');
		$this->assertEqual($sender['EmailMarketingSender']['email_marketing_user_id'], 203);

		// Test private method __generateKeyFiles()
		// If have mkdir permission error, 1: check the current user using "exec('whoami')". 2: check the DIR created group and add the user to that group and give the group write permission
		$this->assertTrue(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.txt"));
		$this->assertTrue(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.private"));
		$this->assertGreaterThan(0, filesize(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.txt"));
		$this->assertGreaterThan(0, filesize(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR ."DNS.private"));

		// Test private method __deleteKeyFiles()
		$data = [
			'EmailMarketingSender' => [
				'id' => $sender['EmailMarketingSender']['id'],
				'email_marketing_user_id' => 203,
				'sender_domain' => 'crazysoft.com'
			]
		];
		$this->EmailMarketingSender->updateSender($sender['EmailMarketingSender']['id'], $data);

		$senderCount = $this->EmailMarketingSender->find('count', array('conditions' => array('sender_domain' => 'crazysoft.com.au')));
		$this->assertEqual($senderCount, 0);

		$sender = $this->EmailMarketingSender->browseBy('sender_domain', 'crazysoft.com');
		$this->assertEqual($sender['EmailMarketingSender']['email_marketing_user_id'], 203);

		$this->assertFalse(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR));
		$this->assertTrue(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com" .DIRECTORY_SEPARATOR ."DNS.txt"));
		$this->assertTrue(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com" .DIRECTORY_SEPARATOR ."DNS.private"));
		$this->assertGreaterThan(0, filesize(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com" .DIRECTORY_SEPARATOR ."DNS.txt"));
		$this->assertGreaterThan(0, filesize(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com" .DIRECTORY_SEPARATOR ."DNS.private"));

		// Clean up
		unlink(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com" .DIRECTORY_SEPARATOR ."DNS.txt");
		unlink(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com" .DIRECTORY_SEPARATOR ."DNS.private");
		rmdir(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com");
	}

/**
 * testDeleteSender method
 *
 * @return void
 */
	public function testDeleteSender() {

		App::uses('CakeSession', 'Model/Datasource');
		CakeSession::write('Auth.User.id', 132); // Fake a logged in user

		$data = [
			'EmailMarketingSender' => [
				'email_marketing_user_id' => 203,
				'sender_domain' => 'crazysoft.com.au'
			]
		];
		$this->EmailMarketingSender->saveSender($data);

		$sender = $this->EmailMarketingSender->browseBy('sender_domain', 'crazysoft.com.au');
		$this->assertEqual($sender['EmailMarketingSender']['email_marketing_user_id'], 203);

		$this->EmailMarketingSender->deleteSender($sender['EmailMarketingSender']['id']);

		$senderCount = $this->EmailMarketingSender->find('count', array('conditions' => array('sender_domain' => 'crazysoft.com.au')));
		$this->assertEqual($senderCount, 0);

		$this->assertFalse(file_exists(ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR ."132" .DIRECTORY_SEPARATOR ."EmailMarketing" .DIRECTORY_SEPARATOR ."DKIM" .DIRECTORY_SEPARATOR ."crazysoft.com.au" .DIRECTORY_SEPARATOR));
	}
}