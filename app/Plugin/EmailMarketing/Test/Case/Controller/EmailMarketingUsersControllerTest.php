<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingUsersController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
class EmailMarketingUsersControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingUsersController;
	protected $userModel;
	protected $emailMarketingUserModel;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingUsersController = $this->generate('EmailMarketing.EmailMarketingUsers', array(

		));

		$this->emailMarketingUserModel = ClassRegistry::init('EmailMarketing.EmailMarketingUser');
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
		'app.configuration',
		'app.log',
		'app.jobqueue'
	);

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->userModel = $this->loginUser(132, 1);

		$planId = 3;

		$this->testAction('/admin/email_marketing/email_marketing_users/index/' .$planId, array('return' => 'vars', 'method' => 'get'));

		$emailMarketingUserCount = $this->emailMarketingUserModel->find('count',
			array(
				'conditions' => array(
					'email_marketing_plan_id' => $planId
				),
				'contain' => false
			)
		);
		$this->assertEqual($emailMarketingUserCount, count($this->vars['response']['aaData']));
	}

/**
 * testAdminIndexClientUserPermissionFail method
 *
 * @return void
 */
	public function testAdminIndexClientUserPermissionFail() {
		$this->expectException('UnauthorizedException');

		$this->userModel = $this->loginUser(235, 19);

		$planId = 3;

		$this->testAction('/admin/email_marketing/email_marketing_users/index/' .$planId, array('return' => 'vars', 'method' => 'get'));
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->userModel = $this->loginUser(132, 1);

		$emailMarketingUserId = 21;

		$this->testAction('/admin/email_marketing/email_marketing_users/view/' .$emailMarketingUserId, array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual($this->vars['user']['EmailMarketingUser']['user_id'], 236);
	}

/**
 * testAdminViewOtherClientAccountByClientUserError method
 *
 * @return void
 */
	public function testAdminViewOtherClientAccountByClientUserError() {
		$this->expectException('UnauthorizedException'); // this action is denied in ACL fro now. Is allowed in ACL, we should expect NotFoundException

		$this->userModel = $this->loginUser(235, 19);

		$emailMarketingUserId = 20;

		$this->testAction('/admin/email_marketing/email_marketing_users/view/' .$emailMarketingUserId, array('return' => 'vars', 'method' => 'get'));
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {

		$this->userModel = $this->loginUser(132, 1);

		$emailMarketingPlanId = 1;

		$this->testAction('/admin/email_marketing/email_marketing_users/add/' .$emailMarketingPlanId, array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual($this->vars['clients'], ['216' => 'New User']);

		$emailMarketingUserCountBeforeAdd = $this->emailMarketingUserModel->find('count', array(
			'conditions' => array('email_marketing_plan_id' => $emailMarketingPlanId),
			'contain' => false
		));

		$userId = 216;

		$userdata = array(
			'User' => array(
				'id' => '216',
				'parent_id' => null,
				'first_name' => 'New',
				'last_name' => 'User',
				'email' => 'lyf890@sohu.com',
				'security_email' => null,
				'password' => AuthComponent::password('123'),
				'password_confirm' => AuthComponent::password('123'),
				'token' => 'c4067e27c5a8108af3ebdc852c2231f6',
				'phone' => null,
				'company' => null,
				'abn_acn' => null,
				'group_id' => '19',
				'active' => 1,
				'last_login' => null,
				'created' => '2016-10-19 16:36:35',
				'modified' => date('Y-m-d H:i:s')
			)
		);

		$this->userModel->updateUser($userId, $userdata);

		$data = [
			'EmailMarketingUser' => [
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 100
			]
		];
		$this->testAction('/admin/email_marketing/email_marketing_users/add/' .$emailMarketingPlanId, array('data' => $data, 'method' => 'post'));

		$this->assertEqual('Email Marketing User has been added.', $this->emailMarketingUsersController->Session->read('Message.flash.message'));

		$emailMarketingUserCountAfterAdd = $this->emailMarketingUserModel->find('count', array(
			'conditions' => array('email_marketing_plan_id' => $emailMarketingPlanId),
			'contain' => false
		));

		$this->assertEqual($emailMarketingUserCountBeforeAdd, $emailMarketingUserCountAfterAdd - 1);
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->userModel = $this->loginUser(132, 1);

		$emailMarketingPlanId = 3;

		$emailMarketingUserId = 20;
		$userId = 203;

		$emailMarketingUserBeforeAdd = $this->emailMarketingUserModel->find('first', array(
			'conditions' => array('id' => $emailMarketingUserId),
			'contain' => false
		));

		$this->assertEqual($emailMarketingUserBeforeAdd['EmailMarketingUser']['free_emails'], 0);

		$data = [
			'EmailMarketingUser' => [
				'id' => $emailMarketingUserId,
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 100
			]
		];

		$this->testAction('/admin/email_marketing/email_marketing_users/edit/' .$emailMarketingUserId .'/' .$emailMarketingPlanId, array('data' => $data, 'method' => 'post'));

		$emailMarketingUserAfterAdd = $this->emailMarketingUserModel->find('first', array(
			'conditions' => array('id' => $emailMarketingUserId),
			'contain' => false
		));

		$this->assertEqual($emailMarketingUserAfterAdd['EmailMarketingUser']['free_emails'], 100);
	}

/**
 * testAdminEditOtherUserAccountByClientError method
 *
 * @return void
 */
	public function testAdminEditOtherUserAccountByClientError() {
		$this->expectException('NotFoundException');

		$this->userModel = $this->loginUser(235, 19);

		$emailMarketingPlanId = 3;

		$emailMarketingUserId = 20;
		$userId = 203;

		$data = [
			'EmailMarketingUser' => [
				'id' => $emailMarketingUserId,
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 100
			]
		];

		$this->testAction('/admin/email_marketing/email_marketing_users/edit/' .$emailMarketingUserId .'/' .$emailMarketingPlanId, array('data' => $data, 'method' => 'post'));
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->userModel = $this->loginUser(132, 1);

		$emailMarketingUserId = 21;
		$userId = 236;
		$superUserId = 235;

		$this->testAction('/admin/email_marketing/email_marketing_users/delete/' .$emailMarketingUserId, array('method' => 'post'));

		$emailMarketingGroupUser = $this->userModel->find('first', array(
			'conditions' => array('id' => $userId),
			'contain' => false
		));
		$this->assertEmpty($emailMarketingGroupUser);

		$emailMarketingGroupUser = $this->userModel->find('first', array(
			'conditions' => array(
				'parent_id' => $superUserId,
				'group_id' => Configure::read('EmailMarketing.client.group.id')
			),
			'contain' => false
		));
		$this->assertEmpty($emailMarketingGroupUser);

		$emailMarketingGroupUser = $this->emailMarketingUserModel->find('first', array(
			'conditions' => array(
				'user_id' => $userId
			),
			'contain' => false
		));
		$this->assertEmpty($emailMarketingGroupUser);

		$superUser = $this->userModel->find('first', array(
			'conditions' => array('id' => $superUserId),
			'contain' => false
		));
		$this->assertNotEmpty($superUser);

		$Configuration = ClassRegistry::init('Configuration');
		$relatedConfigurationCount = $Configuration->find('count',
			array(
				'conditions' => array(
					'OR' => array(
						'user_id' => $userId,
						'user_id' => $superUserId,
					),
					'type' => 'EMAIL_MARKETING'
				),
				'contain' => false
			)
		);
		$this->assertEqual($relatedConfigurationCount, 0);

		$EmailmarketingMailingList = ClassRegistry::init('EmailmarketingMailingList');
		$emailmarketingMailingListCount = $EmailmarketingMailingList->find('count', array(
			'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
			'contain' => false
		));
		$this->assertEqual($emailmarketingMailingListCount, 0);

		$EmailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketingBlacklistedSubscriber');
		$emailMarketingBlacklistedSubscriberCount = $EmailMarketingBlacklistedSubscriber->find('count', array(
			'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
			'contain' => false
		));
		$this->assertEqual($emailMarketingBlacklistedSubscriberCount, 0);

		$EmailMarketingCampaign = ClassRegistry::init('EmailMarketingCampaign');
		$emailMarketingCampaignCount = $EmailMarketingCampaign->find('count', array(
			'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
			'contain' => false
		));
		$this->assertEqual($emailMarketingCampaignCount, 0);

		$EmailMarketingPurchasedTemplate = ClassRegistry::init('EmailMarketingPurchasedTemplate');
		$emailMarketingPurchasedTemplateCount = $EmailMarketingPurchasedTemplate->find('count', array(
			'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
			'contain' => false
		));
		$this->assertEqual($emailMarketingPurchasedTemplateCount, 0);

		$EmailMarketingTemplate = ClassRegistry::init('EmailMarketingTemplate');
		$emailMarketingTemplateCount = $EmailMarketingTemplate->find('count', array(
			'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
			'contain' => false
		));
		$this->assertEqual($emailMarketingTemplateCount, 0);
	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete() {
		$this->userModel = $this->loginUser(132, 1);

		$deletedArr = [
			[
				'emailMarketingUserId' => 20,
				'userId' => 203,
				'superUserId' => 132
			],
			[
				'emailMarketingUserId' => 21,
				'userId' => 236,
				'superUserId' => 235
			]
		];

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
			'/admin/email_marketing/email_marketing_mailing_lists/batchDelete',
			array(
				'data' => array(
					'batchIds' => array($deletedArr[0]['emailMarketingUserId'], $deletedArr[1]['emailMarketingUserId'])
				),
				'method' => 'post'
			)
		);

		$Configuration = ClassRegistry::init('Configuration');
		$EmailmarketingMailingList = ClassRegistry::init('EmailmarketingMailingList');
		$EmailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketingBlacklistedSubscriber');
		$EmailMarketingCampaign = ClassRegistry::init('EmailMarketingCampaign');
		$EmailMarketingPurchasedTemplate = ClassRegistry::init('EmailMarketingPurchasedTemplate');
		$EmailMarketingTemplate = ClassRegistry::init('EmailMarketingTemplate');
		foreach($deletedArr as $deletedUser){
			$emailMarketingUserId = $deletedUser['emailMarketingUserId'];
			$userId = $deletedUser['userId'];
			$superUserId = $deletedUser['superUserId'];

			$emailMarketingGroupUser = $this->userModel->find('first', array(
				'conditions' => array('id' => $userId),
				'contain' => false
			));
			$this->assertEmpty($emailMarketingGroupUser);

			$emailMarketingGroupUser = $this->userModel->find('first', array(
				'conditions' => array(
					'parent_id' => $superUserId,
					'group_id' => Configure::read('EmailMarketing.client.group.id')
				),
				'contain' => false
			));
			$this->assertEmpty($emailMarketingGroupUser);

			$emailMarketingGroupUser = $this->emailMarketingUserModel->find('first', array(
				'conditions' => array(
					'user_id' => $userId
				),
				'contain' => false
			));
			$this->assertEmpty($emailMarketingGroupUser);

			$superUser = $this->userModel->find('first', array(
				'conditions' => array('id' => $superUserId),
				'contain' => false
			));
			$this->assertNotEmpty($superUser);

			$relatedConfigurationCount = $Configuration->find('count',
					array(
						'conditions' => array(
							'OR' => array(
								'user_id' => $userId,
								'user_id' => $superUserId,
							),
							'type' => 'EMAIL_MARKETING'
						),
						'contain' => false
					)
			);
			$this->assertEqual($relatedConfigurationCount, 0);

			$emailmarketingMailingListCount = $EmailmarketingMailingList->find('count', array(
				'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
				'contain' => false
			));
			$this->assertEqual($emailmarketingMailingListCount, 0);

			$emailMarketingBlacklistedSubscriberCount = $EmailMarketingBlacklistedSubscriber->find('count', array(
				'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
				'contain' => false
			));
			$this->assertEqual($emailMarketingBlacklistedSubscriberCount, 0);

			$emailMarketingCampaignCount = $EmailMarketingCampaign->find('count', array(
				'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
				'contain' => false
			));
			$this->assertEqual($emailMarketingCampaignCount, 0);

			$emailMarketingPurchasedTemplateCount = $EmailMarketingPurchasedTemplate->find('count', array(
				'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
				'contain' => false
			));
			$this->assertEqual($emailMarketingPurchasedTemplateCount, 0);

			$emailMarketingTemplateCount = $EmailMarketingTemplate->find('count', array(
				'conditions' => array('email_marketing_user_id' => $emailMarketingUserId),
				'contain' => false
			));
			$this->assertEqual($emailMarketingTemplateCount, 0);
		}
	}
}