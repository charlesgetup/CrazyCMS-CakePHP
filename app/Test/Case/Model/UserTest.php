<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

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
		'app.job_queue'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

/**
 * testParentNode method
 *
 * @return void
 */
	public function testParentNodeHasNoUserDataOrId() {
		unset($this->User->id);
		unset($this->User->data);
		$this->assertNull($this->User->parentNode());
	}

	public function testParentNodeWithGroupIDInUserData() {
		$this->User->data['User']['group_id'] = 1;
		$this->assertEquals(array('Group'=>array('id'=>1)),$this->User->parentNode());
	}

	public function testParentNodeWithoutGroupIDInUserData() {
		$this->User->id = 1;
		unset($this->User->data['User']['group_id']);
		$this->assertEquals(array('Group'=>array('id'=>1)), $this->User->parentNode());
	}
	public function testParentNodeWhenUserHasNoGroupId() {
		$this->User->id = 1;
		$this->User->recursive = -1;
		$user = $this->User->read();
		$user['User']['group_id'] = 0;
		$user['User']['email_confirm'] = $user['User']['email'];
		$user['User']['security_email_confirm'] = $user['User']['security_email'];
		$user['User']['password_confirm'] = $user['User']['password'];

		$this->User->save($user['User']);
		$this->assertNull($this->User->parentNode());
	}

/**
 * testBindNode method
 *
 * @return void
 */
	public function testBindNode() {
		//Using this function, when ACL gets user node, it will get group data, too
		$this->User->id = 1;
		$this->User->recursive = -1;
		$nodes = $this->User->node();

		$this->assertCount(2, $nodes);
		for($i = 0; $i < 2; $i++){
			if($nodes[$i]['Aro']['id'] == '232'){
				$this->assertEqual($nodes[$i]['Aro']['model'], 'User');
			}else{
				$this->assertEqual($nodes[$i]['Aro']['model'], 'Group');
			}
		}
	}

/**
 * testMatchField method
 *
 * @return void
 */
	public function testMatchField() {

		$data = array(
			'first_name' => 'New',
			'last_name' => 'User',
			'email' => 'new@user.com',
			'security_email' => 'new@usersecure.com',
			'password' => AuthComponent::password('123'),
			'email_confirm' => 'new@user.com',
			'security_email_confirm' => 'new@usersecure.com',
			'password_confirm' => AuthComponent::password('123'),
			'token' => null,
			'phone' => '0404370509',
			'company' => '',
			'abn_acn' => '',
			'group_id' => '19',
			'active' => 0
		);

		$this->User->save($data);
		$this->User->recursive = -1;
		$user = $this->User->read();

		$this->assertEqual($user['User']['email'], 'new@user.com');
		$this->assertEqual($user['User']['security_email'], 'new@usersecure.com');
		$this->assertEqual($user['User']['password'], AuthComponent::password('123'));
	}
	public function testFieldNotMatch() {
		$data = array(
			'first_name' => 'New',
			'last_name' => 'User',
			'email' => 'new@user.com',
			'security_email' => 'new@usersecure.com',
			'password' => AuthComponent::password('123'),
			'email_confirm' => 'new@user1.com',
			'security_email_confirm' => 'new2@usersecure.com',
			'password_confirm' => AuthComponent::password('1233'),
			'token' => null,
			'phone' => '0404370509',
			'company' => '',
			'abn_acn' => '',
			'group_id' => '19',
			'active' => 0
		);

		$this->User->save($data);
		App::uses('CakeSession', 'Model/Datasource');
		$this->assertCount(3, $this->User->validationErrors);
		$this->assertEquals("Your emails do not match", $this->User->validationErrors['email'][0]);
		$this->assertEquals("Your security emails do not match", $this->User->validationErrors['security_email'][0]);
		$this->assertEquals("Your passwords do not match", $this->User->validationErrors['password'][0]);
	}

/**
 * testIsUniqueEmail method
 *
 * @return void
 */
	public function testIsUniqueEmail() {
		$data = array(
			'first_name' => 'Not',
			'last_name' => 'UniqueEmail',
			'email' => 'lyf890@sohu.com',
			'security_email' => 'new@usersecure.com',
			'password' => AuthComponent::password('123'),
			'email_confirm' => 'lyf890@sohu.com',
			'security_email_confirm' => 'new@usersecure.com',
			'password_confirm' => AuthComponent::password('123'),
			'token' => null,
			'phone' => '0404370509',
			'company' => '',
			'abn_acn' => '',
			'group_id' => '19',
			'active' => 0
		);

		$this->User->save($data);
		App::uses('CakeSession', 'Model/Datasource');
		$this->assertCount(1, $this->User->validationErrors);
		$this->assertEquals("Email address already in use", $this->User->validationErrors['email'][0]);
	}

/**
 * testNotTheSameAs method
 *
 * @return void
 */
	public function testNotTheSameAs() {
		$data = array(
			'first_name' => 'Not',
			'last_name' => 'UniqueEmail',
			'email' => 'new@usersecure.com',
			'security_email' => 'new@usersecure.com',
			'password' => AuthComponent::password('123'),
			'email_confirm' => 'new@usersecure.com',
			'security_email_confirm' => 'new@usersecure.com',
			'password_confirm' => AuthComponent::password('123'),
			'token' => null,
			'phone' => '0404370509',
			'company' => '',
			'abn_acn' => '',
			'group_id' => '19',
			'active' => 0
		);

		$this->User->save($data);
		App::uses('CakeSession', 'Model/Datasource');
		$this->assertCount(1, $this->User->validationErrors);
		$this->assertEquals("Your security email cannot be the same as login email", $this->User->validationErrors['security_email'][0]);
	}

/**
 * testListAll method
 *
 * @return void
 */
	public function testListAll() {
		$users = $this->User->listAll();
		$this->assertCount(6, $users);
		$this->assertEquals("New User", $users[216]);

		$users = $this->User->listAll(array('Group.name' => 'Client'));
		$this->assertCount(2, $users);
		$this->assertEquals("New User", $users[216]);
	}

/**
 * testGetAssociateUsers method
 *
 * @return void
 */
	public function testGetAssociateUsers() {
		// Has associated user
		$checkUserId = 132;
		$users = $this->User->getAssociateUsers($checkUserId);
		$this->assertCount(2, $users);
		$users = Set::classicExtract($users, '{n}.User.id');
		$this->assertContains($checkUserId, $users);
		$associatedUserId = 203;
		$this->assertContains($associatedUserId, $users);

		// No associated user
		$checkUserId = 216;
		$users = $this->User->getAssociateUsers($checkUserId);
		$this->assertCount(1, $users);
		$users = Set::classicExtract($users, '{n}.User.id');
		$this->assertContains($checkUserId, $users);
	}

/**
 * testSaveUser method
 *
 * @return void
 */
	public function testSaveUser() {
		// If user data has no group ID, client group will be assigned automatically
		$data = array(
			'User' => array(
				'first_name' => 'Client',
				'last_name' => 'User',
				'email' => 'new@user.com',
				'security_email' => 'new@usersecure.com',
				'password' => AuthComponent::password('123'),
				'email_confirm' => 'new@user.com',
				'security_email_confirm' => 'new@usersecure.com',
				'password_confirm' => AuthComponent::password('123'),
				'token' => null,
				'phone' => '0404370509',
				'company' => '',
				'abn_acn' => '',
				'active' => 0
			)
		);

		$id = $this->User->saveUser($data);
		$this->assertFalse($id == false);
		$user = $this->User->browseBy('id', $id);
		$clientGroupId = '19';
		$this->assertEqual($clientGroupId, $user['User']['group_id']);
	}

/**
 * testUpdateUser method
 *
 * @return void
 */
	public function testUpdateUser() {
		$userId = '132';
		$user = $this->User->browseBy('id', $userId);
		$rememberGroupId = $user['User']['group_id'];
		unset($user['User']['group_id']); // without group id, user can still be updated
		$newEmail = 'new_email@new.com';
		$user['User']['email'] = $newEmail;
		unset($user['User']['password']);
		$result = $this->User->updateUser($userId, $user);
		$this->assertTrue($result);
		$userNewData = $this->User->browseBy('id', $userId);
		$this->assertEqual($userNewData['User']['email'], $newEmail);
		$this->assertEqual($userNewData['User']['group_id'], $rememberGroupId);

	}

/**
 * testDeleteChildUserAndAssociatedGroupConfigurationsGetDeletedTogether method
 *
 * @return void
 */
	public function testDeleteChildUserAndAssociatedGroupConfigurationsGetDeletedTogether() {
		$deleteUserId = 203;
		$user = $this->User->browseBy('id', $deleteUserId, array('Group', 'Configuration'));
		$parentId = $user['User']['parent_id'];
		$parentUser = $this->User->browseBy('id', $parentId, array('Group', 'Configuration'));
		$this->assertCount(6, $parentUser['Configuration']);

		$result = $this->User->deleteUser($deleteUserId);
		$this->assertTrue($result);
		$parentUser = $this->User->browseBy('id', $parentId, array('Group', 'Configuration'));
		$this->assertCount(0, $parentUser['Configuration']);
		$this->assertEqual($parentUser['User']['id'], $parentId);
		$user = $this->User->browseBy('id', $deleteUserId, array('Group', 'Configuration'));
		$this->assertEmpty($user);
	}

/**
 * testDeleteParentUserAndDeleteEverythingRelatedTogether method
 *
 * @return void
 */
	public function testDeleteParentUserAndDeleteEverythingRelatedTogether() {
		$deleteUserId = 132;
		$user = $this->User->browseBy('id', $deleteUserId, array('Group', 'Configuration'));
		$this->assertCount(6, $user['Configuration']);

		$childUserId = 203;
		$childUser = $this->User->browseBy('id', $childUserId, array('Group', 'Configuration'));
		$this->assertEqual($childUser['User']['id'], '203');

		$result = $this->User->deleteUser($deleteUserId);
		$this->assertTrue($result);
		$user = $this->User->browseBy('id', $deleteUserId, array('Group', 'Configuration'));
		$this->assertEmpty($user);
		$childUser = $this->User->browseBy('id', $childUserId, array('Group', 'Configuration'));
		$this->assertEmpty($childUser);
		$Configuration = ClassRegistry::init("Configuration");
		$configurations = $Configuration->browseBy('user_id', $deleteUserId);
	}

/**
 * testRecordLogin method
 *
 * @return void
 */
	public function testRecordLogin() {
		$userId = 132;
		$user = $this->User->browseBy('id', $userId);
		$this->assertNotContains(date('Y-m-d'), $user['User']['last_login']);
		$this->User->recordLogin($user['User']);
		$user = $this->User->browseBy('id', $userId);
		$this->assertContains(date('Y-m-d'), $user['User']['last_login']);
	}

/**
 * testGenerateToken method
 *
 * @return void
 */
	public function testGenerateToken() {
		$userId = 216;
		$user = $this->User->browseBy('id', $userId);
		$this->assertNotEmpty($user['User']['token']);
		$oldToken = $user['User']['token'];
		$token = $this->User->generateToken($userId);
		$user = $this->User->browseBy('id', $userId);
		$this->assertNotEqual($oldToken, $token);
		$this->assertEqual($user['User']['token'], $token);
	}

/**
 * testResetPassword method
 *
 * @return void
 */
	public function testResetPassword() {
		$userId = 216;
		$user = $this->User->browseBy('id', $userId);
		$this->assertNotEmpty($user['User']['token']);
		$this->assertNotEmpty($user['User']['password']);
		$oldPassword = $user['User']['password'];

		$result = $this->User->resetPassword($user);
		$this->assertTrue($result);
		$user = $this->User->browseBy('id', $userId);
		$this->assertEmpty($user['User']['token']);
		$this->assertNotEmpty($user['User']['password']);
		$this->assertNotEqual($oldPassword, $user['User']['password']);
	}

}
