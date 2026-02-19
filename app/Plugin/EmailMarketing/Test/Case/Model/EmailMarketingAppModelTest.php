<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * EmailMarketingAppModel Test Case
 *
 */
class EmailMarketingAppModelTest extends CakeTestCase {

	protected $EmailMarketingAppModel;

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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->EmailMarketingAppModel = ClassRegistry::init('EmailMarketing.EmailMarketingAppModel');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->EmailMarketingAppModel);

		parent::tearDown();
	}

/**
 * testCheckRecordBelongsEmailMarketingUser method
 *
 *	In some EmailMarketing tables, e.g. email_marketing_mailing_lists, we use the EmailMarketingUser ID field value as the value of "email_marketing_user_id".
 *	But in the code, we only can get the ID value of User table. Then we need to use this function to link the EmailMarketingUser ID field to the User ID field and then
 *	validate whether the email marketing record is belonged to that user or not.
 *
 * @return void
 */
	public function testCheckRecordBelongsEmailMarketingUser() {

		$this->EmailMarketingAppModel->useTable = 'email_marketing_mailing_lists';

		$userMainAccountId = 132;
		$userEmailMarketingGroupAccount = 203;
		$emailMarketingMailingListValidRecordId = 5;
		$emailMarketingMailingListInValidRecordId = 6;

		$result = $this->EmailMarketingAppModel->checkRecordBelongsEmailMarketingUser($emailMarketingMailingListValidRecordId, $userMainAccountId);
		$this->assertFalse($result);

		$result = $this->EmailMarketingAppModel->checkRecordBelongsEmailMarketingUser($emailMarketingMailingListInValidRecordId, $userEmailMarketingGroupAccount);
		$this->assertFalse($result);

		$result = $this->EmailMarketingAppModel->checkRecordBelongsEmailMarketingUser($emailMarketingMailingListValidRecordId, $userEmailMarketingGroupAccount);
		$this->assertTrue($result);
	}

/**
 * testSuperUserIdToEmailMarketingUserId method
 * @return void
 */
	public function testSuperUserIdToEmailMarketingUserId() {

		$userMainAccountId = 132;
		$userEmailMarketingGroupAccount = 203;

		$emailMarketingUserId = $this->EmailMarketingAppModel->superUserIdToEmailMarketingUserId($userMainAccountId);
		$this->assertEqual($emailMarketingUserId, 20);

		$emailMarketingUserId = $this->EmailMarketingAppModel->superUserIdToEmailMarketingUserId($userEmailMarketingGroupAccount);
		$this->assertEqual($emailMarketingUserId, 20);

		$inactiveUserMainAccountId = 216;
		$emailMarketingUserId = $this->EmailMarketingAppModel->superUserIdToEmailMarketingUserId($inactiveUserMainAccountId);
		$this->assertFalse($emailMarketingUserId);

		// Client who doesn't have an Email Marketing Account cannot get the ID
		$User = ClassRegistry::init('User');
		$userId = '216';
		$userdata = array(
			'User' => array(
				'id' => $userId,
				'parent_id' => null,
				'first_name' => 'New',
				'last_name' => 'User',
				'email' => 'lyf890@sohu.com',
				'security_email' => 'slyf890@sohu.com',
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
		$result = $User->updateUser($userId, $userdata);
		$this->assertTrue($result);
		$userNewData = $User->browseBy('id', $userId);
		$this->assertEqual($userNewData['User']['active'], 1);
		$emailMarketingUserId = $this->EmailMarketingAppModel->superUserIdToEmailMarketingUserId($userId);
		$this->assertFalse($emailMarketingUserId);
	}

/**
 * testEmailMarketingUserIdToSuperUserId method
 * @return void
 */
	public function testEmailMarketingUserIdToSuperUserId() {
		$userMainAccountId = 132;
		$userEmailMarketingGroupAccount = 203;
		$emailMarketingUserId = 20;

		$superUserId = $this->EmailMarketingAppModel->emailMarketingUserIdToSuperUserId($emailMarketingUserId);
		$this->assertEqual($superUserId, $userMainAccountId);

		// This emailMarketingUserIdToSuperUserId() only queries against the "id" field of email_marketing_users table.
		// Using "user_id" field value cannot get super user ID
		$superUserId = $this->EmailMarketingAppModel->emailMarketingUserIdToSuperUserId($userEmailMarketingGroupAccount);
		$this->assertNotEqual($superUserId, $userMainAccountId);

		$superUserId = $this->EmailMarketingAppModel->emailMarketingUserIdToSuperUserId(null);
		$this->assertFalse($superUserId);
	}
}
