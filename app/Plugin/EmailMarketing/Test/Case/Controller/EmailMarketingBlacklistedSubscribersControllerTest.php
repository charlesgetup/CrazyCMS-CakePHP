<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
class EmailMarketingBlacklistedSubscribersControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingBlacklistedSubscribersController;
	protected $userModel;
	protected $emailMarketingBlacklistedSubscriberModel;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array(

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
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketing.EmailMarketingBlacklistedSubscriber');

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers', array('return' => 'vars', 'method' => 'get'));

		$blacklistedSubscribers = $this->emailMarketingBlacklistedSubscriber->find('all', array(
			'conditions' => array(
					'User.id' => 203 // This is the email marketing account of user #132
			),
			'joins' => array(
				array(
	                'table' => 'email_marketing_users',
	                'alias' => 'EmailMarketingMiddleUser',
	                'type' => 'inner',
	                'conditions' => array(
	                    'EmailMarketingMiddleUser.id = EmailMarketingBlacklistedSubscriber.email_marketing_user_id'
	                )
	            ),
	            array(
	                'table' => 'users',
	                'alias' => 'User',
	                'type' => 'inner',
	                'conditions' => array(
	                    'User.id = EmailMarketingMiddleUser.user_id'
	                )
	            )
			),
			'contain' => false
		));
		$this->assertEqual(count($blacklistedSubscribers), count($this->vars['response']['aaData']));
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd(){
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketing.EmailMarketingBlacklistedSubscriber');

		$emailMarketingUserId = 20;

		$blackListedSubscriberCountBeforeAdd = $this->emailMarketingBlacklistedSubscriber->find('count', array(
			'conditions' => array(
				'email_marketing_user_id' => $emailMarketingUserId
			)
		));

		$data = [
			'EmailMarketingBlacklistedSubscriber' => [
				'email_marketing_user_id' => $emailMarketingUserId,
				'email' => 'Test@purpose.com'
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/add/' , array('data' => $data, 'method' => 'post'));

		$this->assertEqual('Email Marketing Blacklisted Subscriber has been added.', $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscriberCountAfterAdd = $this->emailMarketingBlacklistedSubscriber->find('count', array(
			'conditions' => array(
				'email_marketing_user_id' => $emailMarketingUserId
			)
		));

		$this->assertEqual($blackListedSubscriberCountBeforeAdd, $blackListedSubscriberCountAfterAdd - 1);
	}

/**
 * testAdminAddSubscriberToOtherClientsBlackListError method
 *
 * @return void
 */
	public function testAdminAddSubscriberToOtherClientsBlackListError(){
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 1);

		$emailMarketingUserId = 20;

		$data = [
			'EmailMarketingBlacklistedSubscriber' => [
				'email_marketing_user_id' => $emailMarketingUserId,
				'email' => 'Test@purpose.com'
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/add/' , array('data' => $data, 'method' => 'post'));
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete(){
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketing.EmailMarketingBlacklistedSubscriber');

		$emailMarketingUserId = 20;

		$blackListedSubscriberCountBeforeDelete = $this->emailMarketingBlacklistedSubscriber->find('count', array(
			'conditions' => array(
				'email_marketing_user_id' => $emailMarketingUserId
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/delete/1' , array('method' => 'post'));

		$this->assertEqual('Email Marketing Blacklisted Subscriber has been deleted.', $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscriberCountAfterDelete = $this->emailMarketingBlacklistedSubscriber->find('count', array(
			'conditions' => array(
				'email_marketing_user_id' => $emailMarketingUserId
			)
		));

		$this->assertEqual($blackListedSubscriberCountBeforeDelete, $blackListedSubscriberCountAfterDelete + 1);
	}

/**
 * testAdminDeleteSubscriberInOtherClientsBlackListError method
 *
 * @return void
 */
	public function testAdminDeleteSubscriberInOtherClientsBlackListError(){
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 1);

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/delete/1' , array('method' => 'post'));
	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete(){
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketing.EmailMarketingBlacklistedSubscriber');

		$emailMarketingUserId = 20;

		$blackListedSubscriberCountBeforeDelete = $this->emailMarketingBlacklistedSubscriber->find('count', array(
			'conditions' => array(
				'email_marketing_user_id' => $emailMarketingUserId
			)
		));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
			'/admin/email_marketing/email_marketing_blacklisted_subscribers/batchDelete',
			array(
				'data' => array(
					'batchIds' => array('1', '4')
				),
				'method' => 'post'
			)
		);

		$this->assertEqual('Selected email marketing subscribers have been batch deleted.', $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscriberCountAfterDelete = $this->emailMarketingBlacklistedSubscriber->find('count', array(
			'conditions' => array(
				'email_marketing_user_id' => $emailMarketingUserId
			)
		));

		$this->assertEqual($blackListedSubscriberCountBeforeDelete, $blackListedSubscriberCountAfterDelete + 2);
	}

/**
 * testAdminBatchDeleteSubscriberInOtherClientsBlackListError method
 *
 * @return void
 */
	public function testAdminBatchDeleteSubscriberInOtherClientsBlackListError(){
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 1);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
			'/admin/email_marketing/email_marketing_blacklisted_subscribers/batchDelete',
			array(
				'data' => array(
					'batchIds' => array('1', '4')
				),
				'method' => 'post'
			)
		);
	}

/**
 * testAdminImportEmptyFile method
 *
 * @return void
 */
	public function testAdminImportEmptyFile() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array());

		$this->emailMarketingBlacklistedSubscriberModel = $this->getMock('EmailMarketingBlacklistedSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingBlacklistedSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingBlacklistedSubscriberModel->useTable = 'email_marketing_blacklisted_subscribers';

		$emailMarketingUserId = 20;

		$data = array('EmailMarketingBlacklistedSubscriber' => array(
			'email_marketing_user_id' => $emailMarketingUserId,
			'subscriber_file' => array(
				'name' => '',
				'type' => '',
				'tmp_name' => '',
				'error' => 4,
				'size' => 0,
			)
		));

		$blackListedSubscribersCountBeforeAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/' , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("Uploaded file is not valid"), $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscribersCountAfterAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));
		$this->assertEqual($blackListedSubscribersCountBeforeAdd, $blackListedSubscribersCountAfterAdd);

	}

/**
 * testAdminImportEmptyFileInAnotherWay method
 *
 * @return void
 */
	public function testAdminImportEmptyFileInAnotherWay() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array());

		$this->emailMarketingBlacklistedSubscriberModel = $this->getMockForModel('EmailMarketingBlacklistedSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingBlacklistedSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingBlacklistedSubscriberModel->useTable = 'email_marketing_blacklisted_subscribers';

		$emailMarketingUserId = 20;

		$blackListedSubscribersCountBeforeAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));

		$data = array('EmailMarketingBlacklistedSubscriber' => array(
			'email_marketing_user_id' => $emailMarketingUserId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample-empty.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Subscriber-import-file-sample-empty.csv',
				'error' => 0,
				'size' => 0,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/' , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("Uploaded file is not valid"), $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscribersCountAfterAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));
		$this->assertEqual($blackListedSubscribersCountBeforeAdd, $blackListedSubscribersCountAfterAdd );

	}

/**
 * testAdminImport method
 *
 * @return void
 */
	public function testAdminImport() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array());

		$this->emailMarketingBlacklistedSubscriberModel = $this->getMockForModel('EmailMarketingBlacklistedSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingBlacklistedSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingBlacklistedSubscriberModel->useTable = 'email_marketing_blacklisted_subscribers';

		$emailMarketingUserId = 20;

		$blackListedSubscribersCountBeforeAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));

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

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/' , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("(Saved: 12, Duplicated: 0, Invalid: 0)"), $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscribersCountAfterAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));
		$this->assertEqual($blackListedSubscribersCountBeforeAdd, $blackListedSubscribersCountAfterAdd - 12);

	}

/**
 * testAdminImportOverSizedFile method
 *
 * @return void
 */
	public function testAdminImportOverSizedFile() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array());

		$this->emailMarketingBlacklistedSubscriberModel = $this->getMockForModel('EmailMarketingBlacklistedSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingBlacklistedSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingBlacklistedSubscriberModel->useTable = 'email_marketing_blacklisted_subscribers';

		$emailMarketingUserId = 20;

		$data = array('EmailMarketingBlacklistedSubscriber' => array(
			'email_marketing_user_id' => $emailMarketingUserId,
			'subscriber_file' => array(
				'name' => 'Blacklisted-Subscriber-import-file-sample-oversize.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Blacklisted-Subscriber-import-file-sample-oversize.csv',
				'error' => 0,
				'size' => 8956805,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/' , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("Import file size is over limit."), $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

	}

/**
 * testAdminImportXlsxFile method
 *
 * @return void
 */
	public function testAdminImportXlsxFile() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array());

		$this->emailMarketingBlacklistedSubscriberModel = $this->getMockForModel('EmailMarketingBlacklistedSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingBlacklistedSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingBlacklistedSubscriberModel->useTable = 'email_marketing_blacklisted_subscribers';

		$emailMarketingUserId = 20;

		$blackListedSubscribersCountBeforeAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));

		$data = array('EmailMarketingBlacklistedSubscriber' => array(
			'email_marketing_user_id' => $emailMarketingUserId,
			'subscriber_file' => array(
				'name' => 'Blacklisted-Subscriber-import-file-sample.xlsx',
				'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Blacklisted-Subscriber-import-file-sample.xlsx',
				'error' => 0,
				'size' => 3892,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/' , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("(Saved: 12, Duplicated: 0, Invalid: 0)"), $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscribersCountAfterAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));
		$this->assertEqual($blackListedSubscribersCountBeforeAdd, $blackListedSubscribersCountAfterAdd - 12);

	}

/**
 * testAdminImportIntoOtherClientBlacklistedSubscriberListByAdminUser method
 *
 * @return void
 */
	public function testAdminImportIntoOtherClientBlacklistedSubscriberListByAdminUser() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array());

		$this->emailMarketingBlacklistedSubscriberModel = $this->getMockForModel('EmailMarketingBlacklistedSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingBlacklistedSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingBlacklistedSubscriberModel->useTable = 'email_marketing_blacklisted_subscribers';

		$emailMarketingUserId = 21;

		$blackListedSubscribersCountBeforeAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));

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

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/' , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("(Saved: 12, Duplicated: 0, Invalid: 0)"), $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message'));

		$blackListedSubscribersCountAfterAdd = $this->emailMarketingBlacklistedSubscriberModel->find('count', array('conditions' => array('email_marketing_user_id' => $emailMarketingUserId), 'contain' => false));
		$this->assertEqual($blackListedSubscribersCountBeforeAdd, $blackListedSubscribersCountAfterAdd - 12);

	}

/**
 * testAdminImportIntoOtherClientBlacklistedSubscriberListByClientUserError method
 *
 * @return void
 */
	public function testAdminImportIntoOtherClientBlacklistedSubscriberListByClientUserError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);

		$this->emailMarketingBlacklistedSubscribersController = $this->generate('EmailMarketing.EmailMarketingBlacklistedSubscribers', array());

		$this->emailMarketingBlacklistedSubscriberModel = $this->getMockForModel('EmailMarketingBlacklistedSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingBlacklistedSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingBlacklistedSubscriberModel->useTable = 'email_marketing_blacklisted_subscribers';

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

		$this->testAction('/admin/email_marketing/email_marketing_blacklisted_subscribers/import/' , array('data' => $data, 'method' => 'post'));
echo $this->emailMarketingBlacklistedSubscribersController->Session->read('Message.flash.message');
	}
}