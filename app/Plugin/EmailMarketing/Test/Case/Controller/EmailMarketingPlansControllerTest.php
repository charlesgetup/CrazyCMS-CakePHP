<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingPlansController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
class EmailMarketingPlansControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingPlansController;
	protected $userModel;
	protected $emailMarketingPlanModel;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingPlansController = $this->generate('EmailMarketing.EmailMarketingPlans', array(

		));

		$this->emailMarketingPlanModel = ClassRegistry::init('EmailMarketing.EmailMarketingPlan');
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
 * testAdminIndexVisitedByAdminUser method
 *
 * @return void
 */
	public function testAdminIndexVisitedByAdminUser() {
		$this->userModel = $this->loginUser(132, 1);

		$this->testAction('/admin/email_marketing/email_marketing_plans', array('return' => 'vars', 'method' => 'get'));
		$this->assertNotEmpty($this->vars['response']['aaData']);
		$this->assertEqual(count($this->vars['response']['aaData']), 4); // Totally 4 plans
	}

/**
 * testAdminIndexVisitedByEmailMarketingClient method
 *
 * @return void
 */
	public function testAdminIndexVisitedByEmailMarketingClient(){
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->testAction('/admin/email_marketing/email_marketing_plans', array('return' => 'vars', 'method' => 'get'));
		$this->assertNotEmpty($this->vars['plan']['EmailMarketingUser']);
		$this->assertNotEmpty($this->vars['plan']['EmailMarketingPlan']);

		foreach($this->emailMarketingPlansController->Session->read('AssociatedUsers') as $account){
			if($account['User']['parent_id'] == $userId && $account['User']['group_id'] == Configure::read('EmailMarketing.client.group.id')){
				$emailMarketingAccountId = $account['User']['id'];
			}
		}
		$this->assertNotEmpty($emailMarketingAccountId);
		$this->assertEqual($emailMarketingAccountId, $this->vars['plan']['EmailMarketingUser']['user_id']);
		$this->assertEqual($this->vars['plan']['EmailMarketingPlan']['id'], $this->vars['plan']['EmailMarketingUser']['email_marketing_plan_id']);

		// Test the view
		$this->testAction('/admin/email_marketing/email_marketing_plans', array('return' => 'contents', 'method' => 'get'));
		$actualPlanAmount = substr_count($this->contents, 'class="col-xs-6 col-sm-3 pricing-box"');

		$emailMarketingPlanModel = ClassRegistry::init('EmailMarketingPlan');
		$plans = $emailMarketingPlanModel->find('all');
		$planAmount = count($plans);

		$this->assertEqual($actualPlanAmount, $planAmount);
	}

/**
 * testAdminView method
 *
 * Plan can only be viewed by admin user and a plan ID must be provided in the URL
 *
 * @return void
 */
	public function testAdminViewWithError() {
		$this->expectException('NotFoundException');

		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->testAction('/admin/email_marketing/email_marketing_plans/view', array('return' => 'vars', 'method' => 'get'));
	}

/**
 * testAdminView method
 *
 * Plan can only be viewed by admin user and a plan ID must be provided in the URL
 *
 * @return void
 */
	public function testAdminView() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$emailMarketingPlanModel = ClassRegistry::init('EmailMarketingPlan');

		$planId = 1;
		$this->testAction('/admin/email_marketing/email_marketing_plans/view/'.$planId, array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual($this->vars['plan']['EmailMarketingPlan']['id'], $planId);

		$planList = $this->emailMarketingPlanModel->findPlanList();

		$this->assertEqual($this->vars['plans'], $planList);
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd(){
		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_plans/add',
				array(
					'data' => array(
						'EmailMarketingPlan' => array(
							'name' 			=> 'Test Plan',
							'description'	=> 'This plan is for testing',
							'email_limit'	=> '999',
							'unit_price' 	=> '0.001',
							'total_price'	=> '10',
						),
					),
					'method' => 'post'
				)
		);

		$this->assertEquals("Email Marketing Plan has been added.", $this->emailMarketingPlansController->Session->read('Message.flash.message'));

		$newPlan = $this->emailMarketingPlanModel->browseBy ( 'name', 'Test Plan', false);
		$this->assertNotEmpty($newPlan['EmailMarketingPlan']['id']);
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit(){
		$existingPlan = $this->emailMarketingPlanModel->browseBy ( 'id', '1', false);

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_plans/edit/1',
				array(
					'data' => array(
						'EmailMarketingPlan' => array(
							'id'			=> 1,
							'name' 			=> 'Test Plan',
							'description'	=> 'This plan is for testing',
							'email_limit'	=> '999',
							'unit_price' 	=> '0.001',
							'total_price'	=> '10',
						),
					),
					'method' => 'post'
				)
		);

		$updatedPlan = $this->emailMarketingPlanModel->browseBy ( 'id', '1', false);

		$this->assertNotEqual('Test Plan', $existingPlan['EmailMarketingPlan']['name']);
		$this->assertEqual('Test Plan', $updatedPlan['EmailMarketingPlan']['name']);
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete(){
		$existingPlans = $this->emailMarketingPlanModel->find ( 'all');
		$currentPlansAmount = count($existingPlans);

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_plans/delete/1',
				array(
					'method' => 'post'
				)
		);

		$currentPlans = $this->emailMarketingPlanModel->find ( 'all');
		$afterDeletePlansAmount = count($currentPlans);

		$this->assertEqual($afterDeletePlansAmount, ($currentPlansAmount - 1));

		$deletedPlan = $this->emailMarketingPlanModel->browseBy ( 'name', 'Starter Plan', false);
		$this->assertEmpty($deletedPlan);
	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete(){
		// Login an admin user
		$userModel = $this->loginUser();

		$existingPlans = $this->emailMarketingPlanModel->find ( 'all');
		$currentPlansAmount = count($existingPlans);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_plans/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '2', '3')
					),
					'method' => 'post'
				)
		);

		$currentPlans = $this->emailMarketingPlanModel->find ( 'all');
		$afterDeletePlansAmount = count($currentPlans);

		$this->assertEqual($afterDeletePlansAmount, ($currentPlansAmount - 3));

		$deletedPlan = $this->emailMarketingPlanModel->findAll ( 'id', [1,2,3], false);
		$this->assertEmpty($deletedPlan);
	}

/**
 * testAdminAlter method
 *
 * @return void
 */
	public function testAdminAlter(){
		$this->markTestSkipped('cannot test it, because the function is not implemented');
// 		var_dump();
	}
}