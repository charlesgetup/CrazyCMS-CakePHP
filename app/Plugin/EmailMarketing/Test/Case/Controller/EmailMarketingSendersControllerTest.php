<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
require_once APP_DIR .DS .'Plugin' .DS .'Emailmarketing' .DS .'Model' .DS .'EmailMarketingSender.php';
class EmailMarketingSendersControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingController;
	protected $userModel;
	protected $emailMarketingSenderModel;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingSendersController = $this->generate('EmailMarketing.EmailMarketingSenders', array(

		));
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
 * testAdminIndexAdminView method
 *
 * @return void
 */
	public function testAdminIndexAdminView() {
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_senders', array('return' => 'vars', 'method' => 'get'));

		$senders = $this->emailMarketingSenderModel->find('all', array('contain' => false));
		$this->assertEqual(count($senders), count($this->vars['response']['aaData']));
	}

/**
 * testAdminIndexClientView method
 *
 * @return void
 */
	public function testAdminIndexClientView() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_senders', array('return' => 'vars', 'method' => 'get'));
		$this->assertEmpty($this->vars['response']['aaData']);
	}

/**
 * testAdminViewForAdminUser method
 *
 * @return void
 */
	public function testAdminViewForAdminUser() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_senders/view/3', array('return' => 'vars', 'method' => 'get'));

		$this->assertNotEmpty($this->vars['sender']['EmailMarketingSender']['public_key_download_link']);
		$this->assertContains('/files/132/EmailMarketing/DKIM/aroundyou2.info/DNS.txt', $this->vars['sender']['EmailMarketingSender']['public_key_download_link']);
	}

/**
 * testAdminViewForClientUser method
 *
 * @return void
 */
	public function testAdminViewForClientUser() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_senders/view/3', array('return' => 'vars', 'method' => 'get'));
		$this->assertEmpty($this->vars['sender']);

		$this->testAction('/admin/email_marketing/email_marketing_senders/view/3', array('return' => 'contents', 'method' => 'get'));
		$this->assertContains('No sender details found', $this->contents);
	}

/**
 * testAdminViewForClientUser method
 *
 * @return void
 */
	public function testAdminAdd() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array('EmailMarketingSender' => array('sender_domain' => 'test.com'));

		$dnsFileSaveDir =  WWW_ROOT .'files' .DS .'235';
		if(is_dir($dnsFileSaveDir)){
			system('rm -rf ' .escapeshellarg($dnsFileSaveDir), $retval);
			$this->assertEqual($retval, 0);
		}

		$this->testAction(
			'/admin/email_marketing/email_marketing_senders/add',
			array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Sender has been added.', $this->emailMarketingSendersController->Session->read('Message.flash.message'));
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.txt'));
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.private'));
	}

/**
 * testAdminAddDuplicatedDomain method
 *
 * @return void
 */
	public function testAdminAddDuplicatedDomain() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array('EmailMarketingSender' => array('sender_domain' => 'test.com'));

		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Sender has been added.', $this->emailMarketingSendersController->Session->read('Message.flash.message'));

		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Domain name is already in use', $this->emailMarketingSendersController->Session->read('Message.flash.message'));
	}

/**
 * testAdminAddDEmptyDomain method
 *
 * @return void
 */
	public function testAdminAddDEmptyDomain() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array('EmailMarketingSender' => array('sender_domain' => ''));

		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Sender Domain is not empty', $this->emailMarketingSendersController->Session->read('Message.flash.message'));
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array('EmailMarketingSender' => array('sender_domain' => 'test.com'));

		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Sender has been added.', $this->emailMarketingSendersController->Session->read('Message.flash.message'));

		$dnsFileSaveDir =  WWW_ROOT .'files' .DS .'235';

		$sender = $this->emailMarketingSenderModel->browseBy('sender_domain', 'test.com');
		$this->assertNotEmpty($sender['EmailMarketingSender']['id']);
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.txt'));
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.private'));

		$data = array('EmailMarketingSender' => array('sender_domain' => 'test1.com'));

		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/edit/' .$sender['EmailMarketingSender']['id'],
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Sender has been updated.', $this->emailMarketingSendersController->Session->read('Message.flash.message'));

		$sender = $this->emailMarketingSenderModel->browseBy('sender_domain', 'test.com');
		$this->assertEmpty($sender);
		$this->assertFalse(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.txt'));
		$this->assertFalse(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.private'));

		$sender = $this->emailMarketingSenderModel->browseBy('sender_domain', 'test1.com');
		$this->assertNotEmpty($sender['EmailMarketingSender']['id']);
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test1.com' .DS .'DNS.txt'));
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test1.com' .DS .'DNS.private'));
	}

/**
 * testAdminEditOtherPersonSenderError method
 *
 * @return void
 */
	public function testAdminEditOtherPersonSenderError() {
		$this->expectException('NotFoundException');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array('EmailMarketingSender' => array('id' => 1, 'sender_domain' => 'test.com'));

		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/edit/1',
				array('data' => $data, 'method' => 'post')
		);
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete(){
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$existingSenders = $this->emailMarketingSenderModel->find ( 'all', array('contain' => false));
		$currentSendersAmount = count($existingSenders);

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_senders/delete/3',
				array(
					'method' => 'post'
				)
		);

		$currentSenders = $this->emailMarketingSenderModel->find ( 'all', array('contain' => false));
		$afterDeleteSendersAmount = count($currentSenders);

		$this->assertEqual($afterDeleteSendersAmount, ($currentSendersAmount - 1));

		$deletedSender = $this->emailMarketingSenderModel->browseBy ( 'id', '3', false);
		$this->assertEmpty($deletedSender);
	}

/**
 * testAdminDeleteOthersDomain method
 *
 * @return void
 */
	public function testAdminDeleteOthersDomain(){
		$this->expectException('NotFoundException');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_senders/delete/3',
				array(
					'method' => 'post'
				)
		);
	}

/**
 * testAdminDeleteDomainAlsoDeleteFiles method
 *
 * @return void
 */
	public function testAdminDeleteDomainAlsoDeleteFiles(){
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array('EmailMarketingSender' => array('sender_domain' => 'test.com'));

		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Sender has been added.', $this->emailMarketingSendersController->Session->read('Message.flash.message'));

		$dnsFileSaveDir =  WWW_ROOT .'files' .DS .'235';

		$sender = $this->emailMarketingSenderModel->browseBy('sender_domain', 'test.com');
		$this->assertNotEmpty($sender['EmailMarketingSender']['id']);
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.txt'));
		$this->assertTrue(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.private'));

		$existingSenders = $this->emailMarketingSenderModel->find ( 'all', array('contain' => false));
		$currentSendersAmount = count($existingSenders);

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_senders/delete/' .$sender['EmailMarketingSender']['id'],
				array(
					'method' => 'post'
				)
		);

		$currentSenders = $this->emailMarketingSenderModel->find ( 'all', array('contain' => false));
		$afterDeleteSendersAmount = count($currentSenders);

		$this->assertEqual($afterDeleteSendersAmount, ($currentSendersAmount - 1));
		$this->assertEqual('Email Marketing Sender has been deleted.', $this->emailMarketingSendersController->Session->read('Message.flash.message'));

		$deletedSender = $this->emailMarketingSenderModel->browseBy ( 'id', $sender['EmailMarketingSender']['id'], false);
		$this->assertEmpty($deletedSender);

		$this->assertFalse(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.txt'));
		$this->assertFalse(file_exists($dnsFileSaveDir .DS .'EmailMarketing' .DS .'DKIM' .DS .'test.com' .DS .'DNS.private'));
	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete(){
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$existingSenders = $this->emailMarketingSenderModel->find ( 'all');
		$currentSendersAmount = count($existingSenders);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '2')
					),
					'method' => 'post'
				)
		);

		$currentSenders = $this->emailMarketingSenderModel->find ( 'all');
		$afterDeleteSendersAmount = count($currentSenders);

		$this->assertEqual($afterDeleteSendersAmount, ($currentSendersAmount - 2));

		$deletedSender = $this->emailMarketingSenderModel->findAll ( 'id', [1,2], false);
		$this->assertEmpty($deletedSender);
	}

/**
 * testAdminBatchDeleteOthersDomain method
 *
 * @return void
 */
	public function testAdminBatchDeleteOthersDomain(){
		$this->expectException('Exception');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingSenderModel = new EmailMarketingSender(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_senders/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '2')
					),
					'method' => 'post'
				)
		);
	}
}