<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * emailMarketingUser Test Case
 *
 */
class EmailMarketingUserTest extends CakeTestCase {

	protected $emailMarketingUser;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingUser = ClassRegistry::init('EmailMarketing.EmailMarketingUser');
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
 * testSaveUserWithoutRootUser method
 *
 * @return void
 */
	public function testSaveUserWithoutRootUser() {
		$userId = 216;
		$emailMarketingPlanId = 1;

		$User = ClassRegistry::init('User');

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

		$User->updateUser($userId, $userdata);

		$emailMarketingUserCountBeforeAdd = $this->emailMarketingUser->find('count', array(
			'conditions' => array('email_marketing_plan_id' => $emailMarketingPlanId),
			'contain' => false
		));

		$data = [
			'EmailMarketingUser' => [
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 10,
				'free_emails' => 100
			]
		];

		$r = $this->emailMarketingUser->saveUser($data);
		$this->assertTrue($r);

		$emailMarketingUserCountAfterAdd = $this->emailMarketingUser->find('count', array(
			'conditions' => array('email_marketing_plan_id' => $emailMarketingPlanId),
			'contain' => false
		));

		$this->assertEqual($emailMarketingUserCountBeforeAdd, $emailMarketingUserCountAfterAdd - 1);

		$createdAssociatedUserAmount = $User->find('count',array(
			'conditions' => array('parent_id' => $userId, 'group_id' => Configure::read('EmailMarketing.client.group.id')),
			'contain' => false
		));
		$this->assertEqual($createdAssociatedUserAmount, 1);
	}

/**
 * testSaveUserWithRootUser method
 *
 * @return void
 */
	public function testSaveUserWithRootUser() {
		$userId = 216;
		$emailMarketingPlanId = 1;

		$User = ClassRegistry::init('User');

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

		$User->updateUser($userId, $userdata);

		$emailMarketingUserCountBeforeAdd = $this->emailMarketingUser->find('count', array(
			'conditions' => array('email_marketing_plan_id' => $emailMarketingPlanId),
			'contain' => false
		));

		$data = [
			'EmailMarketingUser' => [
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 100
			]
		];

		$r = $this->emailMarketingUser->saveUser($data, $userdata);
		$this->assertTrue($r);

		$emailMarketingUserCountAfterAdd = $this->emailMarketingUser->find('count', array(
			'conditions' => array('email_marketing_plan_id' => $emailMarketingPlanId),
			'contain' => false
		));

		$this->assertEqual($emailMarketingUserCountBeforeAdd, $emailMarketingUserCountAfterAdd - 1);

		$createdAssociatedUserAmount = $User->find('count',array(
			'conditions' => array('parent_id' => $userId, 'group_id' => Configure::read('EmailMarketing.client.group.id')),
			'contain' => false
		));
		$this->assertEqual($createdAssociatedUserAmount, 1);
	}

/**
 * testSaveUserRelatedToInactiveUser method
 *
 * @return void
 */
	public function testSaveUserRelatedToInactiveUser() {
		$userId = 216;
		$emailMarketingPlanId = 1;

		$data = [
			'EmailMarketingUser' => [
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 100
			]
		];

		$r = $this->emailMarketingUser->saveUser($data);
		$this->assertTrue($r);

		$User = ClassRegistry::init('User');
		$superUser = $User->find('first', array(
			'conditions' => array(
				'User.id' => $userId,
			),
			'contain' => false
		));
		$this->assertEqual($superUser['User']['active'], 0);

		$associatedUser = $User->find('first', array(
    		'conditions' => array(
    			'User.parent_id' => $userId,
    			'User.group_id' => Configure::read('EmailMarketing.client.group.id')
    		),
    		'contain' => false
    	));
		$this->assertEqual($associatedUser['User']['active'], 0);
	}

/**
 * testSaveUserWhenFailAssociatedUserWillBeRemoved method
 *
 * @return void
 */
	public function testSaveUserWhenFailAssociatedUserWillBeRemoved() {
		$userId = 216;
		$emailMarketingPlanId = 1;

		$data = [
			'EmailMarketingUser' => [
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 'A Error'
			]
		];

		$r = $this->emailMarketingUser->saveUser($data);
		$this->assertFalse($r);

		$User = ClassRegistry::init('User');
		$associatedUser = $User->find('first', array(
			'conditions' => array(
				'User.parent_id' => $userId,
				'User.group_id' => Configure::read('EmailMarketing.client.group.id')
			),
			'contain' => false
		));
		$this->assertEmpty($associatedUser);
	}

/**
 * testSaveUserWhenAlreadyExistUpdateUser method
 *
 * @return void
 */
	public function testSaveUserWhenAlreadyExistUpdateUser() {
		// Make an existing email marketing user
		$userId = 216;
		$emailMarketingPlanId = 1;

		$data = [
			'EmailMarketingUser' => [
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 100
			]
		];

		$r = $this->emailMarketingUser->saveUser($data);
		$this->assertTrue($r);

		// Try to created it again
		$data = [
			'EmailMarketingUser' => [
				'user_id' => $userId,
				'email_marketing_plan_id' => $emailMarketingPlanId,
				'email_warning_limit' => 0,
				'free_emails' => 500
			]
		];

		$r = $this->emailMarketingUser->saveUser($data);
		$this->assertTrue($r);

		// Tests
		$User = ClassRegistry::init('User');
		$associatedUser = $User->find('all', array(
			'conditions' => array(
				'User.parent_id' => $userId,
				'User.group_id' => Configure::read('EmailMarketing.client.group.id')
			),
			'contain' => false
		));
		$this->assertEqual(count($associatedUser), 1);

		$emailMarketingUser = $this->emailMarketingUser->find('all', array(
			'conditions' => array(
				'user_id' => $associatedUser[0]['User']['id'],
			),
			'contain' => false
		));
		$this->assertEqual(count($emailMarketingUser), 1);
		$this->assertEqual($emailMarketingUser[0]['EmailMarketingUser']['free_emails'], 500);
	}

/**
 * testUpdateUserWithRelatedUserRecord method
 *
 * @return void
 */
	public function testUpdateUserWithRelatedUserRecord() {
		// Test update email marketing user with related user record
		$emailMarketingUserId = 20;
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, true);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['payment_cycle'], 'MANUAL');
		$emailMarketingUser['EmailMarketingUser']['payment_cycle'] = 'ANNUALLY';
		if(!empty($emailMarketingUser['User']['email'])){
			$emailMarketingUser['User']['email_confirm'] = $emailMarketingUser['User']['email'];
		}
		if(!empty($emailMarketingUser['User']['security_email'])){
			$emailMarketingUser['User']['security_email_confirm'] = $emailMarketingUser['User']['security_email'];
		}
		if(!empty($emailMarketingUser['User']['password'])){
			$emailMarketingUser['User']['password_confirm'] = $emailMarketingUser['User']['password'];
		}
		$this->emailMarketingUser->updateUser($emailMarketingUser['EmailMarketingUser']['id'], $emailMarketingUser);

		$emailMarketingUserUpdated = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['payment_cycle'], 'ANNUALLY');
	}

/**
 * testUpdateUserWithoutRelatedUserRecord method
 *
 * @return void
 */
	public function testUpdateUserWithoutRelatedUserRecord() {
		// Test update email marketing user without related user record
		$emailMarketingUserId = 20;
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['payment_cycle'], 'MANUAL');
		$emailMarketingUser['EmailMarketingUser']['payment_cycle'] = 'ANNUALLY';
		$this->emailMarketingUser->updateUser($emailMarketingUser['EmailMarketingUser']['id'], $emailMarketingUser);

		$emailMarketingUserUpdated = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['payment_cycle'], 'ANNUALLY');
	}

/**
 * testdeleteUser method
 *
 * @return void
 */
	public function testDeleteUser() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testGetUserList method
 *
 *	Link EmailMarketingUserId to Super user name (first name + last name)
 *
 * @return void
 */
	public function testGetUserList() {
		$userId = 203; // EmailMarketingGroup Account user ID is used, not the main/super user ID
		$list = $this->emailMarketingUser->getUserList($userId);
		$this->assertEqual($list, [20 => 'Belinda Li']);
	}

/**
 * testRecordUsageUsingFreeEmail method
 *
 * @return void
 */
	public function testRecordUsageUsingFreeEmail() {
		$superUserId = 203; // EmailMarketingAccount user, not super user account
		$emailMarketingUserId = 20;

		// Reset usage from fixture
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$emailMarketingUser['EmailMarketingUser']['free_emails'] = 100;
		$emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'] = 0;
		$this->emailMarketingUser->updateUser($emailMarketingUser['EmailMarketingUser']['id'], $emailMarketingUser);

		$usageAmount = 50;
		$this->emailMarketingUser->recordUsage($superUserId, $usageAmount);

		// Check results
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['free_emails'], 50);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'], 50);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['used_email_count'], 0);
	}

/**
 * testRecordUsageUsedAllFreeEmail method
 *
 * This function testes usage beyond free email amount and under monthly limit
 *
 * @return void
 */
	public function testRecordUsageUsedAllFreeEmail() {
		$superUserId = 203; // EmailMarketingAccount user, not super user account
		$emailMarketingUserId = 20;

		// Reset usage from fixture
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$emailMarketingUser['EmailMarketingUser']['free_emails'] = 100;
		$emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'] = 0;
		$this->emailMarketingUser->updateUser($emailMarketingUser['EmailMarketingUser']['id'], $emailMarketingUser);

		$usageAmount = 150;
		$this->emailMarketingUser->recordUsage($superUserId, $usageAmount);

		// Check results
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['free_emails'], 0);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'], 150);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['used_email_count'], 50);
	}

/**
 * testRecordUsageBeyondMonthlyLimit method
 *
 * @return void
 */
	public function testRecordUsageBeyondMonthlyLimit() {
		$superUserId = 203; // EmailMarketingAccount user, not super user account
		$emailMarketingUserId = 20;

		// Reset usage from fixture
		$prepaidAmount = 10;
		$freeEmails = 100;
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$emailMarketingUser['EmailMarketingUser']['free_emails'] = $freeEmails;
		$emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'] = 0;
		$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] = $prepaidAmount;
		$this->emailMarketingUser->updateUser($emailMarketingUser['EmailMarketingUser']['id'], $emailMarketingUser);

		$monthlyLimit = 1000000;
		$usageAmount = 1000150;
		$overUsedEmailAmount = $usageAmount - $monthlyLimit - $freeEmails;
		$this->emailMarketingUser->recordUsage($superUserId, $usageAmount);

		// Check results
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['free_emails'], 0);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'], 1000150);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['used_email_count'], 1000050);
		$planEmailUnitPrice = 0.001;
		$overUsedEmailCost = $planEmailUnitPrice * $overUsedEmailAmount;
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['prepaid_amount'], ($prepaidAmount - $overUsedEmailCost));
	}

/**
 * testRecordUsageBeyondMonthlyLimitWithNotEnoughPrepaidCredit method
 *
 * @return void
 */
	public function testRecordUsageBeyondMonthlyLimitWithNotEnoughPrepaidCredit() {

		$superUserId = 203; // EmailMarketingAccount user, not super user account
		$emailMarketingUserId = 20;

		// Reset usage from fixture
		$prepaidAmount = 10;
		$freeEmails = 100;
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$emailMarketingUser['EmailMarketingUser']['free_emails'] = $freeEmails;
		$emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'] = 0;
		$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] = $prepaidAmount;
		$this->emailMarketingUser->updateUser($emailMarketingUser['EmailMarketingUser']['id'], $emailMarketingUser);

		$monthlyLimit = 1000000;
		$usageAmount = 1100150;
		$overUsedEmailAmount = $usageAmount - $monthlyLimit - $freeEmails;
		$nullResult = $this->emailMarketingUser->recordUsage($superUserId, $usageAmount);

		// Check results
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['free_emails'], 0);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'], 1100150);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['used_email_count'], 1100050);
		$planEmailUnitPrice = 0.001;
		$overUsedEmailCost = $planEmailUnitPrice * $overUsedEmailAmount;
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['prepaid_amount'], ($prepaidAmount - $overUsedEmailCost));
		$this->assertNull($nullResult); // This validation need to be updated when the process is updated
	}

/**
 * testRecordUsageBeyondMonthlyLimitWithEmptyPrepaidCredit method
 *
 * @return void
 */
	public function testRecordUsageBeyondMonthlyLimitWithEmptyPrepaidCredit() {

		$superUserId = 203; // EmailMarketingAccount user, not super user account
		$emailMarketingUserId = 20;

		// Reset usage from fixture
		$prepaidAmount = 0;
		$freeEmails = 100;
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId, false);
		$emailMarketingUser['EmailMarketingUser']['free_emails'] = $freeEmails;
		$emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'] = 0;
		$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] = $prepaidAmount;
		$this->emailMarketingUser->updateUser($emailMarketingUser['EmailMarketingUser']['id'], $emailMarketingUser);

		$monthlyLimit = 1000000;
		$usageAmount = 1100150;
		$overUsedEmailAmount = $usageAmount - $monthlyLimit - $freeEmails;
		$nullResult = $this->emailMarketingUser->recordUsage($superUserId, $usageAmount);

		// Check results
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['free_emails'], 0);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'], 1100150);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['used_email_count'], 1100050);
		$planEmailUnitPrice = 0.001;
		$overUsedEmailCost = $planEmailUnitPrice * $overUsedEmailAmount;
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['prepaid_amount'], ($prepaidAmount - $overUsedEmailCost));
		$this->assertNull($nullResult); // This validation need to be updated when the process is updated
	}

/**
 * testExceededLimit method
 *
 * @return void
 */
	public function testExceededLimit() {
		$emailMarketingUserId = 20;
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId);

		$sendingAmount = 100;
		$result = $this->emailMarketingUser->exceededLimit($emailMarketingUser['EmailMarketingUser'], $sendingAmount);
		$this->assertFalse($result);

		$sendingAmount = 1000000;
		$emailMarketingUser = $this->emailMarketingUser->browseBy('id', $emailMarketingUserId); // Get email marketing user details every time before check
		$result = $this->emailMarketingUser->exceededLimit($emailMarketingUser['EmailMarketingUser'], $sendingAmount);
		$this->assertTrue($result);
	}
}