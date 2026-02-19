<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingMailingListsController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
require_once APP_DIR .DS .'Plugin' .DS .'Emailmarketing' .DS .'Model' .DS .'EmailMarketingMailingList.php';
class EmailMarketingMailingListsControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingMailingListsController;
	protected $userModel;
	protected $emailMarketingMailingListModel;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingMailingListsController = $this->generate('EmailMarketing.EmailMarketingMailingLists', array(

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

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_mailing_lists', array('return' => 'vars', 'method' => 'get'));

		$mailingLists = $this->emailMarketingMailingListModel->find('all', array('contain' => false));
		$this->assertEqual(count($mailingLists), count($this->vars['response']['aaData']));
	}

/**
 * testAdminIndexClientView method
 *
 * @return void
 */
	public function testAdminIndexClientView() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_mailing_lists', array('return' => 'vars', 'method' => 'get'));

		$mailingListCount = $this->emailMarketingMailingListModel->find('count', array(
			'conditions' => array(
				'email_marketing_user_id' => 21,
				'deleted' => 0
			),
			'contain' => false
		));
		$this->assertEqual($mailingListCount, count($this->vars['response']['aaData']));
	}

/**
 * testAdminViewForAdminUser method
 *
 * @return void
 */
	public function testAdminViewForAdminUser() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_mailing_lists/view/1', array('return' => 'vars', 'method' => 'get'));

		$this->assertNotEmpty($this->vars['list']['EmailMarketingMailingList']['total_subscribers_amount']);
	}

/**
 * testAdminViewForClientUser method
 *
 * @return void
 */
	public function testAdminViewForClientUser() {
		$this->expectException('NotFoundException');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_mailing_lists/view/1', array('return' => 'vars', 'method' => 'get'));
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array(
			'EmailMarketingMailingList' => array(
				'email_marketing_user_id' => '21',
				'name' => 'Test List',
				'description' => 'Test purpose',
				'active' => 1
			)
		);

		$this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Mailing List has been added.', $this->emailMarketingMailingListsController->Session->read('Message.flash.message'));

		$lists = $this->emailMarketingMailingListModel->browseBy('email_marketing_user_id', 21);
		$this->assertEqual(count($lists), 1);
	}

/**
 * testAdminAddWithEmptyName method
 *
 * @return void
 */
	public function testAdminAddWithEmptyName() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$data = array(
			'EmailMarketingMailingList' => array(
				'email_marketing_user_id' => '21',
				'name' => '',
				'description' => 'Test purpose',
				'active' => 1
			)
		);

		$this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Name is not empty', $this->emailMarketingMailingListsController->Session->read('Message.flash.message'));
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$list = $this->emailMarketingMailingListModel->browseBy('id', '1');
		$this->assertEmpty($list['EmailMarketingMailingList']['description']);

		$list['EmailMarketingMailingList']['description'] = 'Updated Description';
		$list['EmailMarketingMailingList']['active'] = 1;

		$this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/edit/1',
				array('data' => $list, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Mailing List has been updated.', $this->emailMarketingMailingListsController->Session->read('Message.flash.message'));

		$list = $this->emailMarketingMailingListModel->browseBy('id', '1');
		$this->assertEqual('Updated Description', $list['EmailMarketingMailingList']['description']);
	}

/**
 * testAdminEditOtherPersonMailingList method
 *
 * @return void
 */
	public function testAdminEditOtherPersonMailingList() {
		$this->expectException('NotFoundException');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$list = $this->emailMarketingMailingListModel->browseBy('id', '1');
		$this->assertEmpty($list['EmailMarketingMailingList']['description']);

		$list['EmailMarketingMailingList']['description'] = 'Updated Description';
		$list['EmailMarketingMailingList']['active'] = 1;

		$this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/edit/1',
				array('data' => $list, 'method' => 'post')
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

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$existingLists = $this->emailMarketingMailingListModel->find ( 'all', array('conditions' => array('deleted' => 0, 'active' => 1), 'contain' => false));
		$currentListsAmount = count($existingLists);

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/delete/4',
				array(
					'method' => 'post'
				)
		);

		$currentLists = $this->emailMarketingMailingListModel->find ( 'all', array('conditions' => array('deleted' => 0, 'active' => 1), 'contain' => false));
		$afterDeleteListsAmount = count($currentLists);

		$this->assertEqual($afterDeleteListsAmount, ($currentListsAmount - 1));

		$deletedList = $this->emailMarketingMailingListModel->browseBy ( 'id', '4', false);
		$this->assertEqual($deletedList['EmailMarketingMailingList']['deleted'], 1);
		$this->assertEqual($deletedList['EmailMarketingMailingList']['active'], 0);
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

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/delete/4',
				array(
					'method' => 'post'
				)
		);
	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete(){
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$existingLists = $this->emailMarketingMailingListModel->find ('all', array('conditions' => array('deleted' => 0, 'active' => 1), 'contain' => false));
		$currentListsAmount = count($existingLists);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '4')
					),
					'method' => 'post'
				)
		);

		$currentLists = $this->emailMarketingMailingListModel->find ( 'all', array('conditions' => array('deleted' => 0, 'active' => 1), 'contain' => false));
		$afterDeleteListsAmount = count($currentLists);

		$this->assertEqual($afterDeleteListsAmount, ($currentListsAmount - 2));

		$deletedLists = $this->emailMarketingMailingListModel->findAll ( 'id', [1,4], false);
		foreach($deletedLists as $deletedList){
			$this->assertEqual($deletedList['EmailMarketingMailingList']['deleted'], 1);
			$this->assertEqual($deletedList['EmailMarketingMailingList']['active'], 0);
		}
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

		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_mailing_lists/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '4')
					),
					'method' => 'post'
				)
		);
	}
}