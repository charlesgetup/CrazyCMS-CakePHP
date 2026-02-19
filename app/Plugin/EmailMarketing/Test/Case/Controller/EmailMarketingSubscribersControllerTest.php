<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
class EmailMarketingSubscribersControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingSubscribersController;
	protected $userModel;
	protected $emailMarketingSubscriberModel;

	public function setUp() {
		parent::setUp();
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
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/index/' .$emailMarketingListId, array('return' => 'vars', 'method' => 'get'));

		$mailingLists = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($mailingLists), count($this->vars['response']['aaData']));
	}

/**
 * testAdminIndexAccessOtherClientSubscriberError method
 *
 * @return void
 */
	public function testAdminIndexAccessOtherClientSubscriberError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/index/' .$emailMarketingListId, array('method' => 'get'));
	}

/**
 * testAdminIndexAdminUserCanAccessOtherClientSubscribers method
 *
 * @return void
 */
	public function testAdminIndexAdminUserCanAccessOtherClientSubscribers() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 6;
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/index/' .$emailMarketingListId, array('return' => 'vars', 'method' => 'get'));

		$mailingLists = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($mailingLists), count($this->vars['response']['aaData']));
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingSubscriberId = 1;
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/view/' .$emailMarketingSubscriberId, array('return' => 'vars', 'method' => 'get'));

		$emailMarketingSubscriber = $this->emailMarketingSubscriberModel->findById($emailMarketingSubscriberId);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $this->vars['subscriber']['EmailMarketingSubscriber']['email_marketing_list_id']);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['first_name'], $this->vars['subscriber']['EmailMarketingSubscriber']['first_name']);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['last_name'], $this->vars['subscriber']['EmailMarketingSubscriber']['last_name']);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['email'], $this->vars['subscriber']['EmailMarketingSubscriber']['email']);
	}

/**
 * testAdminIndexViewOtherClientSubscriberError method
 *
 * @return void
 */
	public function testAdminIndexViewOtherClientSubscriberError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingSubscriberId = 1;
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/view/' .$emailMarketingSubscriberId, array('method' => 'get'));
	}

/**
 * testAdminIndexAdminUserCanViewOtherClientSubscribers method
 *
 * @return void
 */
	public function testAdminIndexAdminUserCanViewOtherClientSubscribers() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingSubscriberId = 10;
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/view/' .$emailMarketingSubscriberId, array('return' => 'vars', 'method' => 'get'));

		$emailMarketingSubscriber = $this->emailMarketingSubscriberModel->findById($emailMarketingSubscriberId);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $this->vars['subscriber']['EmailMarketingSubscriber']['email_marketing_list_id']);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['first_name'], $this->vars['subscriber']['EmailMarketingSubscriber']['first_name']);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['last_name'], $this->vars['subscriber']['EmailMarketingSubscriber']['last_name']);
		$this->assertEqual($emailMarketingSubscriber['EmailMarketingSubscriber']['email'], $this->vars['subscriber']['EmailMarketingSubscriber']['email']);
	}

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminAdd() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = [
			'EmailMarketingSubscriber' => [
				'email_marketing_list_id' => $emailMarketingListId,
				'first_name' => 'Test',
				'last_name' => 'List',
				'email' => 'Test@purpose.com',
				'excluded' => 0,
				'unsubscribed' => 0,
				'deleted' => 0,
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/add/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual('Email Marketing Subscriber has been added.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeAdd), count($subscribersAfterAdd) - 1);
	}

/**
 * testAdminAddSubscriberToOtherClientListByAdminUser method
 *
 * @return void
 */
	public function testAdminAddSubscriberToOtherClientListByAdminUser() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 6;

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = [
			'EmailMarketingSubscriber' => [
				'email_marketing_list_id' => $emailMarketingListId,
				'first_name' => 'Test',
				'last_name' => 'List',
				'email' => 'Test@purpose.com',
				'excluded' => 0,
				'unsubscribed' => 0,
				'deleted' => 0,
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/add/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual('Email Marketing Subscriber has been added.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeAdd), count($subscribersAfterAdd) - 1);
	}

/**
 * testAdminAddSubscriberToOtherClientListByClientError method
 *
 * @return void
 */
	public function testAdminAddSubscriberToOtherClientListByClientError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = [
			'EmailMarketingSubscriber' => [
				'email_marketing_list_id' => $emailMarketingListId,
				'first_name' => 'Test',
				'last_name' => 'List',
				'email' => 'Test@purpose.com',
				'excluded' => 0,
				'unsubscribed' => 0,
				'deleted' => 0,
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/add/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;
		$emailMarketingSubscriberId = 1;

		$subscriberBeforeUpdate = $this->emailMarketingSubscriberModel->findById($emailMarketingSubscriberId);
		$this->assertEqual($subscriberBeforeUpdate['EmailMarketingSubscriber']['email'], 'kerry-fly@163.com');

		$data = [
			'EmailMarketingSubscriber' => [
				'id' => $emailMarketingSubscriberId,
				'email_marketing_list_id' => $emailMarketingListId,
				'first_name' => 'Test',
				'last_name' => 'List',
				'email' => 'Test@purpose.com',
				'excluded' => 0,
				'unsubscribed' => 0,
				'deleted' => 0,
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/edit/' .$emailMarketingSubscriberId .'/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual('Email Marketing Subscriber has been updated.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscriberAfterUpdate = $this->emailMarketingSubscriberModel->findById($emailMarketingSubscriberId);
		$this->assertEqual($subscriberAfterUpdate['EmailMarketingSubscriber']['email'], 'Test@purpose.com');
	}

/**
 * testAdminUpdateSubscriberInOtherClientListByAdminUser method
 *
 * @return void
 */
	public function testAdminUpdateSubscriberInOtherClientListByAdminUser() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 6;
		$emailMarketingSubscriberId = 10;

		$subscriberBeforeUpdate = $this->emailMarketingSubscriberModel->findById($emailMarketingSubscriberId);
		$this->assertEqual($subscriberBeforeUpdate['EmailMarketingSubscriber']['email'], 'test@example.com');

		$data = [
			'EmailMarketingSubscriber' => [
				'id' => $emailMarketingSubscriberId,
				'email_marketing_list_id' => $emailMarketingListId,
				'first_name' => 'Test',
				'last_name' => 'List',
				'email' => 'Test@purpose.com',
				'excluded' => 0,
				'unsubscribed' => 0,
				'deleted' => 0,
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/edit/' .$emailMarketingSubscriberId .'/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual('Email Marketing Subscriber has been updated.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscriberAfterUpdate = $this->emailMarketingSubscriberModel->findById($emailMarketingSubscriberId);
		$this->assertEqual($subscriberAfterUpdate['EmailMarketingSubscriber']['email'], 'Test@purpose.com');
	}

/**
 * testAdminUpdateSubscriberInOtherClientListByClientError method
 *
 * @return void
 */
	public function testAdminUpdateSubscriberInOtherClientListByClientError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;
		$emailMarketingSubscriberId = 1;

		$data = [
			'EmailMarketingSubscriber' => [
				'id' => $emailMarketingSubscriberId,
				'email_marketing_list_id' => $emailMarketingListId,
				'first_name' => 'Test',
				'last_name' => 'List',
				'email' => 'Test@purpose.com',
				'excluded' => 0,
				'unsubscribed' => 0,
				'deleted' => 0,
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/edit/' .$emailMarketingSubscriberId .'/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;
		$emailMarketingSubscriberId = 1;

		$subscribersBeforeDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/delete/' .$emailMarketingSubscriberId , array('method' => 'post'));

		$this->assertEqual('Email Marketing Subscriber has been deleted.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeDel), count($subscribersAfterDel) + 1);

	}

/**
 * testAdminDeleteSubscriberInOtherClientListByAdminUser method
 *
 * @return void
 */
	public function testAdminDeleteSubscriberInOtherClientListByAdminUser() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 6;
		$emailMarketingSubscriberId = 10;

		$subscribersBeforeDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/delete/' .$emailMarketingSubscriberId , array('method' => 'post'));

		$this->assertEqual('Email Marketing Subscriber has been deleted.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeDel), count($subscribersAfterDel) + 1);
	}

/**
 * testAdminUpdateSubscriberInOtherClientListByClientError method
 *
 * @return void
 */
	public function testAdminDeleteSubscriberInOtherClientListByClientError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;
		$emailMarketingSubscriberId = 1;

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/delete/' .$emailMarketingSubscriberId , array('method' => 'post'));
	}

	/**
	 * testAdminBatchDelete method
	 *
	 * @return void
	 */
	public function testAdminBatchDelete() {
		//TODO have to login user using EmailMarketing group ID, not client group ID. Why?
		// If login using client group ID, like $this->loginUser(235, 19), I will got "MissingControllerException: Controller class DashboardController could not be found." error
		$this->userModel = $this->loginUser(235, 4);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 6;

		$subscribersBeforeDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_subscribers/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('10')
					),
					'method' => 'post'
				)
		);

		$this->assertEqual('Selected email marketing subscribers have been batch deleted.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeDel), count($subscribersAfterDel) + 1);

	}

/**
 * testAdminBatchDeleteSubscriberInOtherClientListByAdminUser method
 *
 * @return void
 */
	public function testAdminBatchDeleteSubscriberInOtherClientListByAdminUser() {
		$this->userModel = $this->loginUser(132, 1);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 6;

		$subscribersBeforeDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_subscribers/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('10')
					),
					'method' => 'post'
				)
		);

		$this->assertEqual('Selected email marketing subscribers have been batch deleted.', $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterDel = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId, 'deleted' => 0), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeDel), count($subscribersAfterDel) + 1);

	}

/**
 * testAdminBatchDeleteSubscriberInOtherClientListByClientError method
 *
 * @return void
 */
	public function testAdminBatchDeleteSubscriberInOtherClientListByClientError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 4);
		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');
		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingListId = 1;

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_subscribers/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '2')
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

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$this->emailMarketingSubscriberModel = $this->getMock('EmailMarketingSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingSubscriberModel->useTable = 'email_marketing_subscribers';

		$emailMarketingListId = 1;

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => '',
				'type' => '',
				'tmp_name' => '',
				'error' => 4,
				'size' => 0,
			)
		));

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/import/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("Uploaded file is not valid"), $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeAdd), count($subscribersAfterAdd) );

	}

/**
 * testAdminImportEmptyFileInAnotherWay method
 *
 * @return void
 */
	public function testAdminImportEmptyFileInAnotherWay() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$this->emailMarketingSubscriberModel = $this->getMockForModel('EmailMarketingSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingSubscriberModel->useTable = 'email_marketing_subscribers';

		$emailMarketingListId = 1;

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample-empty.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Subscriber-import-file-sample-empty.csv',
				'error' => 0,
				'size' => 0,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/import/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("Uploaded file is not valid"), $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeAdd), count($subscribersAfterAdd) );

	}

/**
 * testAdminImport method
 *
 * @return void
 */
	public function testAdminImport() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$this->emailMarketingSubscriberModel = $this->getMockForModel('EmailMarketingSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingSubscriberModel->useTable = 'email_marketing_subscribers';

		$emailMarketingListId = 1;

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Subscriber-import-file-sample.csv',
				'error' => 0,
				'size' => 471,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/import/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("(Saved: 12, Duplicated: 0, Invalid: 0, Blacklist filtered: 0)"), $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeAdd), count($subscribersAfterAdd) - 12);

	}

/**
 * testAdminImportOverSizedFile method
 *
 * @return void
 */
	public function testAdminImportOverSizedFile() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$this->emailMarketingSubscriberModel = $this->getMockForModel('EmailMarketingSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingSubscriberModel->useTable = 'email_marketing_subscribers';

		$emailMarketingListId = 1;

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample-oversize.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Subscriber-import-file-sample-oversize.csv',
				'error' => 0,
				'size' => 5558906,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/import/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("Import file size is over limit."), $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

	}

/**
 * testAdminImportXlsxFile method
 *
 * @return void
 */
	public function testAdminImportXlsxFile() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$this->emailMarketingSubscriberModel = $this->getMockForModel('EmailMarketingSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingSubscriberModel->useTable = 'email_marketing_subscribers';

		$emailMarketingListId = 1;

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample.xlsx',
				'type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Subscriber-import-file-sample.xlsx',
				'error' => 0,
				'size' => 1109194,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/import/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("(Saved: 292, Duplicated: 69788, Invalid: 1441, Blacklist filtered: 0)"), $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeAdd), count($subscribersAfterAdd) - 292);

	}

/**
 * testAdminImportIntoOtherClientMailingListByAdminUser method
 *
 * @return void
 */
	public function testAdminImportIntoOtherClientMailingListByAdminUser() {

		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$this->emailMarketingSubscriberModel = $this->getMockForModel('EmailMarketingSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingSubscriberModel->useTable = 'email_marketing_subscribers';

		$emailMarketingListId = 6;

		$subscribersBeforeAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Subscriber-import-file-sample.csv',
				'error' => 0,
				'size' => 471,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/import/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

		$this->assertEqual(__("(Saved: 12, Duplicated: 0, Invalid: 0, Blacklist filtered: 0)"), $this->emailMarketingSubscribersController->Session->read('Message.flash.message'));

		$subscribersAfterAdd = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeAdd), count($subscribersAfterAdd) - 12);

	}

/**
 * testAdminImportIntoOtherClientMailingListByClientUserError method
 *
 * @return void
 */
	public function testAdminImportIntoOtherClientMailingListByClientUserError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$this->emailMarketingSubscriberModel = $this->getMockForModel('EmailMarketingSubscriber', array('checkUploadedTempFile'));
		$this->emailMarketingSubscriberModel->expects($this->any())->method('checkUploadedTempFile')->will($this->returnValue(true));
		$this->emailMarketingSubscriberModel->useTable = 'email_marketing_subscribers';

		$emailMarketingListId = 1;

		$data = array('EmailMarketingSubscriber' => array(
			'email_marketing_list_id' => $emailMarketingListId,
			'subscriber_file' => array(
				'name' => 'Subscriber-import-file-sample.csv',
				'type' => 'text/csv',
				'tmp_name' => ROOT .DS .'app' .DS .'Plugin' .DS .'EmailMarketing' .DS .'Test' .DS .'UploadFiles' .DS .'Subscriber-import-file-sample.csv',
				'error' => 0,
				'size' => 471,
			)
		));

		$this->testAction('/admin/email_marketing/email_marketing_subscribers/import/' .$emailMarketingListId , array('data' => $data, 'method' => 'post'));

	}

/**
 * testAdminImportIntoOtherClientMailingListByClientUserError method
 *
 * @return void
 */
	public function testAdminRemoveInvalidSubscribers() {
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingCampaignId = 1;

		$emailMarketingListId = 1;

		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');

		$subscribersBeforeRemove = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = array('EmailMarketingCampaign' => array(
			'id' => $emailMarketingCampaignId,
			'invalid_subscriber' => array(1, 2)
		));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/removeInvalidSubscriber/' , array('data' => $data, 'return' => 'contents', 'method' => 'post'));

		$subscribersAfterRemove = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeRemove), count($subscribersAfterRemove) + 2);

		$this->assertEqual($this->contents, 'The invalid subscribers have been removed successfully');
	}

/**
 * testAdminPartialyRemoveInvalidSubscribers method
 *
 * @return void
 */
	public function testAdminPartialyRemoveInvalidSubscribers() {
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingSubscribersController = $this->generate('EmailMarketing.EmailMarketingSubscribers', array());

		$emailMarketingCampaignId = 1;

		$emailMarketingListId = 1;

		$this->emailMarketingSubscriberModel = ClassRegistry::init('EmailMarketing.EmailMarketingSubscriber');

		$subscribersBeforeRemove = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));

		$data = array('EmailMarketingCampaign' => array(
			'id' => $emailMarketingCampaignId,
			'invalid_subscriber' => array(1, 2, 6)
		));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction('/admin/email_marketing/email_marketing_subscribers/removeInvalidSubscriber/' , array('data' => $data, 'return' => 'contents', 'method' => 'post'));

		$subscribersAfterRemove = $this->emailMarketingSubscriberModel->find('all', array('conditions' => array('email_marketing_list_id' => $emailMarketingListId), 'contain' => false));
		$this->assertEqual(count($subscribersBeforeRemove), count($subscribersAfterRemove) + 2);

		$this->assertEqual($this->contents, 'The invalid subscribers have been partially removed, some of the invalid subscriber(s) does/do not belong to this campaign');
	}
}