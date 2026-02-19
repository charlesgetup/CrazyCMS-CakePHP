<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingDashboardController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
class EmailMarketingDashboardControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingDashboardController;
	protected $userModel;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingDashboardController = $this->generate('EmailMarketing.EmailMarketingDashboard', array(

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
 * testAdminIndexForClient method
 *
 * @return void
 */
	public function testAdminIndexForClient() {
		$this->userModel = $this->loginUser(235, 1);

		$this->testAction('/admin/email_marketing/email_marketing_dashboard', array('return' => 'contents', 'method' => 'get'));
		$this->assertEqual(trim($this->contents), 'This is a email marketing introduction');
	}

/**
 * testAdminIndexForAdmin method
 *
 * @return void
 */
	public function testAdminIndexForAdmin() {
		$this->userModel = $this->loginUser(132, 1);

		$emailmarketingConfigurationIntroductionFieldName = Configure::read('EmailMarketing.configuration.introduction.field.name');

		$this->testAction('/admin/email_marketing/email_marketing_dashboard', array('return' => 'contents', 'method' => 'get'));
		$this->assertContains('<textarea id="Introduction" name="Introduction">', $this->contents);
		$this->assertContains('This is a email marketing introduction', $this->contents);

		// Update the introduction content
		$data = array($emailmarketingConfigurationIntroductionFieldName => 'This is a new email marketing introduction');
		$this->testAction(
			'/admin/email_marketing/email_marketing_dashboard',
			array('data' => $data, 'method' => 'post')
		);
		$this->assertEquals("Email Marketing Introduction has been updated.", $this->emailMarketingDashboardController->Session->read('Message.flash.message'));
		$this->testAction('/admin/email_marketing/email_marketing_dashboard', array('return' => 'contents', 'method' => 'get'));
		$this->assertNotContains('This is a email marketing introduction', $this->contents);
		$this->assertContains('This is a new email marketing introduction', $this->contents);

		// Update failed - empty content
		$data = array($emailmarketingConfigurationIntroductionFieldName => '');
		$this->testAction(
				'/admin/email_marketing/email_marketing_dashboard',
				array('data' => $data, 'method' => 'post')
		);
		$this->assertEquals("Email Marketing Introduction content cannot be empty.", $this->emailMarketingDashboardController->Session->read('Message.flash.message'));

		// Update failed - Email Marketing Configuration is deleted
		Configure::delete('Config.type.emailmarketing');
		$data = array($emailmarketingConfigurationIntroductionFieldName => 'This is a new email marketing introduction');
		$this->testAction(
				'/admin/email_marketing/email_marketing_dashboard',
				array('data' => $data, 'method' => 'post')
		);
		$this->assertEquals("Type is not empty", $this->emailMarketingDashboardController->Session->read('Message.flash.message'));
	}


/**
 * testAdminEnable method
 *
 * @return void
 */
	//TODO cannot test it, because the payment gateway is not implemented
	public function testAdminEnable() {

		$this->markTestSkipped('cannot test it, because the function is moved to plan controller');

		$emailMarketingPlanModel = ClassRegistry::init('EmailMarketingPlan');
		$loggedInUser = $this->userController->Session->read('Auth.User');
		$associlatedUsers = $this->userModel->getAssociateUsers($loggedInUser['id']);
		$emailMarketingUser = null;
		foreach($associlatedUsers as $u){
			if(@$u['User']['parent_id'] == $loggedInUser['id'] && @$u['User']['group_id'] == Configure::read('EmailMarketing.client.group.id')){
				$emailMarketingUser = $u;
			}
		}
		$this->assertNotEmpty($emailMarketingUser);

		$emailMarketingUserModel = ClassRegistry::init('EmailMarketingUser');
		$emailMarketingUserDetails = $emailMarketingUserModel->browseBy('user_id', $emailMarketingUser['User']['id']);
		$existingPlanId = $emailMarketingUserDetails['EmailMarketingUser']['email_marketing_plan_id'];

		$newPlanId = 1;
		$this->assertNotEqual($newPlanId, $existingPlanId);

		$this->testAction('/admin/email_marketing/email_marketing_dashboard/enable/' .$newPlanId, array('method' => 'get'));

		$emailMarketingUserDetailsAgain = $emailMarketingUserModel->browseBy('user_id', $emailMarketingUser['User']['id']);
		$existingPlanId = $emailMarketingUserDetailsAgain['EmailMarketingUser']['email_marketing_plan_id'];
		$this->assertEqual($newPlanId, $existingPlanId);
	}

/**
 * testAdminEnableCannotSwitchPlan method
 *
 * @return void
 */
	public function testAdminEnableCannotSwitchPlan(){
		$this->markTestSkipped('cannot test it, because the payment gateway is not implemented');
	}
}