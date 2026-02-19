<?php
/**
 * usersController Test Case
 *
 */
require_once 'AppControllerTest.php';
class UsersControllerTest extends AppControllerTest {

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
		'app.configuration',
		'app.log',
		'app.jobqueue'
	);

/**
 * testAdminLogin method
 *
 * @return void
 */
	public function testAdminLogin() {

		$data = array(
			'User' => array(
				'email' => 'kerry-fly@163.com',
				'password' => '123'
			)
		);

		$this->testAction(
			'/admin/users/login',
			array('data' => $data, 'method' => 'post')
		);

		$result = $this->headers;

		$this->assertStringEndsWith("/admin/dashboard", $result['Location']);
	}

	public function testFailedAdminLogin(){

		$data = array(
			'User' => array(
				'email' => 'kerry-fly@163.com',
				'password' => 'wrong_password'
			)
		);

		$this->testAction(
			'/admin/users/login',
			array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Your sign in details was incorrect.", $this->userController->Session->read('Message.flash.message'));
	}

	/*
	 * Service Account means the account client use to access a certain web service, like Email marketing.
	 * When client starts to use a web service, system will create a service account for the client.
	 */
	public function testUseServiceAccountToLogin(){

		$data = array(
			'User' => array(
				'email' => '1323kerry-fly@163.com',
				'password' => '123'
			)
		);

		$this->testAction(
			'/admin/users/login',
			array('data' => $data, 'method' => 'post')
		);

		$result = $this->headers;

		$this->assertStringEndsWith("/admin/dashboard", $result['Location']);
	}

/**
 * testAdminLogout method
 * @depends testUseServiceAccountToLogin
 *
 * @return void
 */
	public function testAdminLogout() {

		$this->testAction('/admin/users/logout');

		$this->assertEquals("See you next time.", $this->userController->Session->read('Message.flash.message'));

	}

/**
 * testRegister method
 *
 * @return void
 */
	public function testRegisterClient() {

		$data = array(
			'User' => array(
				'email' => 'new_user@crazysoft.com.au',
				'password' => 'new_password',
				'password_confirm' => 'new_password',
				'agreement' => 1
			)
		);

		$this->userController->expects($this->any())->method('requestAction')->will($this->returnValue( array('status' => Configure::read('System.variable.success'), 'message' => 'Activation email has been sent successfully.') ));

		$this->testAction(
			'/users/register',
			array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Thank you for joining CrazySoft", $this->userController->Session->read('Message.flash.message'));
	}

/**
 * testRegisterEmailMarketingClient method
 *
 * @return void
 */
	public function testRegisterEmailMarketingClient() {

		$data = array(
			'User' => array(
				'email' => 'new_email_marketing_user@crazysoft.com.au',
				'password' => 'new_email_marketing_password',
				'password_confirm' => 'new_email_marketing_password',
				'service_id' => Configure::read('EmailMarketing.client.group.id'),
				'agreement' => 1
			)
		);

		$this->userController->expects($this->any())->method('requestAction')->will($this->returnValue( array('status' => Configure::read('System.variable.success'), 'message' => 'Activation email has been sent successfully.') ));

		$this->testAction(
				'/users/register',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Thank you for joining CrazySoft", $this->userController->Session->read('Message.flash.message'));
	}

	public function testRegisterAgreementError(){

		$data = array(
			'User' => array(
				'email' => 'new_user@crazysoft.com.au',
				'password' => 'new_password',
				'password_confirm' => 'new_password'
			)
		);

		$this->testAction(
			'/users/register',
			array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Please agree with the User Agreement before register", $this->userController->Session->read('Message.flash.message'));
	}

	public function testRegisterEmailError(){

		$data = array(
			'User' => array(
				'email' => 'kerry-fly@163.com',
				'password' => 'new_password',
				'password_confirm' => 'new_password',
				'agreement' => 1
			)
		);

		$this->testAction(
			'/users/register',
			array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Email address already in use", $this->userController->Session->read('Message.flash.message'));
	}

	public function testRegisterPasswordError(){

		$data = array(
			'User' => array(
				'email' => 'kerry-fly@1631.com',
				'password' => 'new_password',
				'password_confirm' => 'not_new_password',
				'agreement' => 1
			)
		);

		$this->testAction(
			'/users/register',
			array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Your passwords do not match", $this->userController->Session->read('Message.flash.message'));
	}

/**
 * testActivateAccount method
 *
 * @return void
 */
	public function testActivateAccount() {

		// Update fixture data
		$userModel = $this->updateFixtureData('User', 216, array('modified' => date('Y-m-d H:i:s'))); // 216 is user ID in fixture

		$this->testAction('/account/activate/c4067e27c5a8108af3ebdc852c2231f6');

		$this->assertEquals("Your account has been activated. You can login using the registered information.", $this->userController->Session->read('Message.flash.message'));

	}

	public function testActivateAccountNotExist() {

		// Update fixture data
		$userModel = $this->updateFixtureData('User', 216, array('modified' => date('Y-m-d H:i:s'))); // 216 is user ID in fixture

		$this->testAction('/account/activate/fake_token');

		$this->assertEquals("The account you wanted to activate doesn't exist. This action has been reported to CrazySoft.", $this->userController->Session->read('Message.flash.message'));

	}

	public function testActivateAccountAfterFifteenMins() {

		$this->testAction('/account/activate/c4067e27c5a8108af3ebdc852c2231f6');

		$this->assertEquals('Activate request is not valid now. Please contact CrazySoft to activate your account.', $this->userController->Session->read('Message.flash.message'));

	}

/**
 * testForgetPassword method
 *
 * @return void
 */
	public function testForgetPassword() {

		$data = array(
			'User' => array(
				'email' => 'kerry-fly@163.com'
			)
		);

		$this->userController->expects($this->any())->method('requestAction')->will($this->returnValue( true ));

		$this->testAction(
			'/account/forget_password',
			array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("The reset password email has been sent, please check your email and reset the password within 4 hours.", $this->userController->Session->read('Message.flash.message'));
		$this->assertStringEndsWith("/login", $this->headers['Location']);
	}

	public function testForgetPasswordWithInactiveAccount() {

		$data = array(
			'User' => array(
				'email' => 'lyf890@sohu.com'
			)
		);

		$this->testAction(
				'/account/forget_password',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("This account is inactive. Please contact CrazySoft for further information.", $this->userController->Session->read('Message.flash.message'));

		$this->assertEmpty($this->headers);
	}

	public function testForgetPasswordAccountNotExist() {
		$data = array(
			'User' => array(
				'email' => 'not_account_email@unknown.com'
			)
		);

		$this->testAction(
				'/account/forget_password',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals('The account doesn\'t exist. Please enter the correct account email.', $this->userController->Session->read('Message.flash.message'));

		$this->assertEmpty($this->headers);
	}

/**
 * testResetPassword method
 *
 * @return void
 */
	public function testResetPassword() {

		// Update fixture data
		$userModel = $this->updateFixtureData('User', 216, array('modified' => date('Y-m-d H:i:s'))); // 216 is user ID in fixture

		$data = array(
			'User' => array(
				'password' => 'new_password',
				'password_confirm' => 'new_password'
			)
		);

		$this->testAction(
				'/account/reset_password/c4067e27c5a8108af3ebdc852c2231f6',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Password has been reset. Now you can use the new password to login.", $this->userController->Session->read('Message.flash.message'));
		$this->assertStringEndsWith("/login", $this->headers['Location']);
	}

	public function testResetPasswordInvalidToken() {

		$data = array(
			'User' => array(
				'password' => 'new_password',
				'password_confirm' => 'new_password'
			)
		);

		$this->testAction(
				'/account/reset_password/fake_token',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("The reset password token is not valid. Please regenerate the token here and this action will be recorded for investigation.", $this->userController->Session->read('Message.flash.message'));
		$this->assertStringEndsWith("/account/forget_password", $this->headers['Location']);
	}

	public function testResetPasswordPasswordNotMatch() {

		// Update fixture data
		$userModel = $this->updateFixtureData('User', 216, array('modified' => date('Y-m-d H:i:s'))); // 216 is user ID in fixture

		$data = array(
			'User' => array(
				'password' => 'new_password',
				'password_confirm' => 'new_password_typo'
			)
		);

		$this->testAction(
				'/account/reset_password/c4067e27c5a8108af3ebdc852c2231f6',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("The passwords entered are not matching each other.", $this->userController->Session->read('Message.flash.message'));
		$this->assertEmpty($this->headers);

	}

	public function testResetPasswordAfterFourHours() {

		$data = array(
			'User' => array(
				'password' => 'new_password',
				'password_confirm' => 'new_password'
			)
		);

		$this->testAction(
				'/account/reset_password/c4067e27c5a8108af3ebdc852c2231f6',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("Reset password link has expired, please regenerate the link here.", $this->userController->Session->read('Message.flash.message'));
		$this->assertStringEndsWith("/account/forget_password", $this->headers['Location']);
	}

	public function testResetPasswordServerSideError() {

		// Update fixture data
		$userModel = $this->updateFixtureData('User', 216, array('modified' => date('Y-m-d H:i:s'))); // 216 is user ID in fixture

		$data = array(
			'User' => array(
				'password' => 'new_password',
				'password_confirm' => 'new_password'
			)
		);

		$userModel = $this->getMockForModel("User", array("resetPassword"));
		$userModel->expects($this->once())->method("resetPassword")->will($this->returnValue(false));

		$this->testAction(
				'/account/reset_password/c4067e27c5a8108af3ebdc852c2231f6',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEquals("We have some difficulties to update your new password. Please wait a while and try again.", $this->userController->Session->read('Message.flash.message'));
		$this->assertEmpty($this->headers);
	}

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {

		// Login an admin user
		$this->loginUser();

		$results = $this->testAction('/admin/users');

		$this->assertEqual($this->vars['_serialize'], 'response');
		$this->assertEqual($this->vars['defaultSortDir'], 'DESC');
		$this->assertEqual(count($this->vars['response']['aaData']), 6); // User amount in user fixture
	}

/**
 * testAdminListGroupUsers method
 *
 * @return void
 */
	public function testAdminListGroupUsers() {

		// Login an admin user
		$this->loginUser();

		$results = $this->testAction('/admin/users/listGroupUsers/1.json'); // 1 is admin group ID

		$this->assertEqual(count($this->vars['response']['aaData']), 2); // Only 2 admin users under admin group in user fixtures
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {

		// Login an admin user
		$this->loginUser();

		$results = $this->testAction('/admin/users/view/1'); // 1 is admin user ID

		$this->assertEqual($this->vars['user']['User']['id'], 1);
		$this->assertEqual($this->vars['user']['User']['email'], "charles@crazycms.net");
		$this->assertEqual($this->vars['user']['User']['security_email'], "kerry-fly@163.com");
		$this->assertEqual($this->vars['user']['Group']['id'], "1");
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testLoadAdminAddPage() {

		// Login an admin user
		$this->loginUser();

		$results = $this->testAction('/admin/users/add');

		$this->assertEqual(count($this->vars['groups']), 14); // Total 14 groups in fixture
		$this->assertEqual(count($this->vars['countries']), 10); // Total 10 countries in fixtures
		$this->assertContains('id="UserAdminAddForm" method="post"', $this->contents);
		$this->assertContains('<input type="hidden" name="data[User][id]" id="UserId"/>', $this->contents); //TODO need some more view tests
	}

	public function testAdminAddAction() {

		// Login an admin user
		$userModel = $this->loginUser();

		$this->userController->expects($this->any())->method('requestAction')->will($this->returnValue( array('status' => Configure::read('System.variable.success'), 'message' => 'Activation email has been sent successfully.') ));

		$results = $this->testAction(
			'/admin/users/add',
			array(
				'data' => array(
					'User' => array(
						'first_name' 				=> 'Jim',
						'last_name'	 				=> 'watts',
						'email'		 				=> 'jim.watts@crazycms.com.au',
						'email_confirm' 			=> 'jim.watts@crazycms.com.au',
						'security_email'			=> 'jim.watts@secure.com.au',
						'security_email_confirm'	=> 'jim.watts@secure.com.au',
						'active'					=> '1',
						'phone'						=> '0412121212',
						'password'					=> 'testpass',
						'password_confirm'			=> 'testpass',
					),
					'Address' => array(
						array(
							'street_address' => 'test street',
							'suburb'		 => 'test suburb',
							'state'			 => 'test state',
							'postcode'		 => 'test postcode',
							'country_id'	 => '1'
						),
						array(
							'street_address' => 'test street',
							'suburb'		 => 'test suburb',
							'state'			 => 'test state',
							'postcode'		 => 'test postcode',
							'country_id'	 => '1'
						)
					)
				),
				'method' => 'post'
			)
		);

		$newUser = $userModel->browseBy ( 'email', 'jim.watts@crazycms.com.au', false);
		$this->assertEqual(count($newUser), 1);
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEditPageLoading() {

		// Login an admin user
		$userModel = $this->loginUser();

		$results = $this->testAction('/admin/users/edit/1', array('method' => 'get'));

		$this->assertEqual($this->userController->request->data['User']['id'], '1');
	}

	public function testAdminEditAction() {

		// Login an admin user
		$userModel = $this->loginUser();

		$results = $this->testAction(
			'/admin/users/edit/1',
			array(
				'data' => array(
					'User' => array(
						'id'						=> '1',
						'first_name' 				=> 'Jim',
						'last_name'	 				=> 'watts',
						'email'		 				=> 'jim.watts@crazycms.com.au',
						'email_confirm' 			=> 'jim.watts@crazycms.com.au',
						'security_email'			=> 'jim.watts@secure.com.au',
						'security_email_confirm'	=> 'jim.watts@secure.com.au',
						'active'					=> '1',
						'phone'						=> '0412121212',
						'password'					=> 'testpass',
						'password_confirm'			=> 'testpass',
					)
				),
				'method' => 'post'
			)
		);

		$existingUser = $userModel->browseBy ( $userModel->primaryKey, '1');
		$this->assertEqual($existingUser['User']['email'], 'jim.watts@crazycms.com.au');

	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {

		// Login an admin user
		$userModel = $this->loginUser();

		$users = $userModel->find('all');
		$this->assertEqual(count($users), 6);

		$this->testAction(
			'/admin/users/delete/216',
			array(
				'method' => 'post'
			)
		);

		$users = $userModel->find('all');
		$this->assertEqual(count($users), 5);

	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete() {
		// Login an admin user
		$userModel = $this->loginUser();

		$users = $userModel->find('all');
		$this->assertEqual(count($users), 6);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
			'/admin/users/batchDelete',
			array(
				'data' => array(
					'batchIds' => array('216', '203', '132'),
				),
				'method' => 'post'
			)
		);

		$users = $userModel->find('all');
		$this->assertEqual(count($users), 3);
	}

}
