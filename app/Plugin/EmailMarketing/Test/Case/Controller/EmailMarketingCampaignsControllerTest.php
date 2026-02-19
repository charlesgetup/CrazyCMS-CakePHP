<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');

/**
 * EmailMarketingCampaignsController Test Case
 *
 */
require_once 'EmailMarketingAppControllerTest.php';
require_once APP_DIR .DS .'Plugin' .DS .'Emailmarketing' .DS .'Model' .DS .'EmailMarketingCampaign.php';
class EmailMarketingCampaignsControllerTest extends EmailMarketingAppControllerTest {

	protected $emailMarketingCampaignsController;
	protected $userModel;
	protected $emailMarketingCampaignModel;
	protected $emailMarketingMailingListModel;

	public function setUp() {
		parent::setUp();

		$this->emailMarketingCampaignsController = $this->generate('EmailMarketing.EmailMarketingCampaigns', array(

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
		'app.configuration',
		'app.log',
		'app.jobqueue',
		'plugin.payment.payment_invoice',
		'plugin.payment.payment_pay_pal_gateway',
		'plugin.payment.payment_payer',
		'plugin.payment.payment_temp_invoice',
	);

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->userModel = $this->loginUser(132, 1);

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_campaigns', array('return' => 'vars', 'method' => 'get'));

		$campaigns = $this->emailMarketingCampaignModel->find('all', array(
			'conditions' => array(
				'deleted' => 0
			),
			'contain' => false
		));
		$this->assertEqual(count($campaigns), count($this->vars['response']['aaData']));
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId);

		$campaignId = 1;

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_campaigns/view/1', array('return' => 'vars', 'method' => 'get'));

		$campaign = $this->emailMarketingCampaignModel->browseBy('id', $campaignId);
		$this->assertEqual($campaign['EmailMarketingCampaign']['name'], $this->vars['campaign']['EmailMarketingCampaign']['name']);
		$this->assertTrue(!isset($this->vars['externalContent']));
	}

/**
 * testAdminViewOtherPersonCampaign method
 *
 * @return void
 */
	public function testAdminViewOtherPersonCampaign() {
		$this->expectException('NotFoundException');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$campaignId = 1;

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$this->testAction('/admin/email_marketing/email_marketing_campaigns/view/1', array('return' => 'vars', 'method' => 'get'));
	}

/**
 * testAdminPreview method
 *
 * @return void
 */
	public function testAdminPreview() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId);

		$campaignId = 1;

		$this->testAction('/admin/email_marketing/email_marketing_campaigns/preview/' .Configure::read('EmailMarketing.email.type.text') .DS .$campaignId, array('return' => 'view', 'method' => 'get'));

		$this->assertContains('Text message 123123123', $this->view);

		$this->testAction('/admin/email_marketing/email_marketing_campaigns/preview/' .Configure::read('EmailMarketing.email.type.html') .DS .$campaignId, array('return' => 'view', 'method' => 'get'));

		$this->assertContains(__('Preview HTML Email'), $this->view);
		$this->assertContains('Look at our promotions!', $this->view);
	}

/**
 * testAdminPreview method
 *
 * @return void
 */
	public function testAdminPreviewWithIncorrectType() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId);

		$campaignId = 1;

		$this->testAction('/admin/email_marketing/email_marketing_campaigns/preview/TEXT' .DS .$campaignId, array('return' => 'view', 'method' => 'get'));

		$this->assertEmpty($this->view);
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run
		$this->emailMarketingMailingListModel = new EmailMarketingMailingList(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$listsBeforeAdd = $this->emailMarketingCampaignModel->find('all', array(
			'conditions' => array(
				'deleted' => 0,
				'email_marketing_user_id' => 21
			),
			'contain' => false
		));

		$db = $this->emailMarketingCampaignModel->getDataSource();
		$relationsBeforeAdd = $db->fetchAll(
			'SELECT * from email_marketing_campaign_lists '
		);

		$htmlEmailContent = <<<HTML
			<p>Hi [FIRST-NAME] [LAST-NAME],</p>
			<h1><strong>Look at our promotions!</strong></h1>
			<p>&nbsp;</p>
			<table style="height: 86px; float: left;" width="726">
			<tbody>
			<tr>
			<td style="text-align: left;"><img src="http://crazycms.loc/files/132/EmailMarketing/media/alpha_industries_a-2_leather_jacket_1.png" alt="" width="200" /></td>
			<td><img src="http://crazycms.loc/files/132/EmailMarketing/media/alpha_n-2b_short_waist_parka_1.png" alt="" width="200" /></td>
			</tr>
			<tr>
			<td><img src="http://crazycms.loc/files/132/EmailMarketing/media/alpha_industries_a-2_leather_jacket_2.png" alt="" width="200" /></td>
			<td><img src="http://crazycms.loc/files/132/EmailMarketing/media/alpha_n-2b_short_waist_parka_2.png" alt="" width="200" /></td>
			</tr>
			<tr>
			<td><img src="http://crazycms.loc/files/132/EmailMarketing/media/alpha_industries_a-2_leather_jacket_3.png" alt="" width="200" /></td>
			<td><img src="http://crazycms.loc/files/132/EmailMarketing/media/alpha_n-2b_short_waist_parka_3.png" alt="" width="200" /></td>
			</tr>
			<tr>
			<td><a href="http://google.com" target="_blank">Buy</a></td>
			<td><a href="http://baidu.com" target="_blank">Buy</a></td>
			</tr>
			</tbody>
			</table>
HTML;

		$data = array(
			'EmailMarketingCampaign' => array(
				'email_marketing_user_id' 	=> '21',
				'name' 						=> 'Test Campaign',
				'send_format' 				=> 'BOTH',
				'from_email_address_prefix' => 'test',
				'email_marketing_sender_id' => '1',
				'subject' 					=> 'Hello World',
				'use_external_web_page' 	=> 0,
				'template_data' 			=> $htmlEmailContent,
				'text_message' 				=> ''
			),
			'EmailMarketingMailingList' => array(
				'EmailMarketingMailingList' => array(6)
			)
		);

		$this->testAction(
				'/admin/email_marketing/email_marketing_campaigns/add',
				array('data' => $data, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Campaign has been added.', $this->emailMarketingCampaignsController->Session->read('Message.flash.message'));

		$lists = $this->emailMarketingCampaignModel->find('all', array(
			'conditions' => array(
				'deleted' => 0,
				'email_marketing_user_id' => 21
			),
			'contain' => false
		));
		$this->assertEqual(count($lists), count($listsBeforeAdd) + 1);

		//TODO not sure why this relation cannot be created in test
// 		$relationsAfterAdd = $db->fetchAll(
// 				'SELECT * from email_marketing_campaign_lists '
// 		);
// 		$this->assertEqual(count($relationsAfterAdd), count($relationsBeforeAdd) + 1);

		//TODO implement function to auto generate text email content from HTML
		// If select email format as "BOTH" and not enter text version content, we automatically generate it from HTML version content
// 		$addedCampaign = $this->emailMarketingCampaignModel->find('first', array(
// 			'conditions' => array(
// 				'EmailMarketingCampaign.deleted' => 0,
// 				'EmailMarketingCampaign.email_marketing_user_id' => 21,
// 			),
// 			'order' => array('EmailMarketingCampaign.id DESC')
// 		));
// 		$this->assertNotEmpty($addedCampaign['EmailMarketingCampaign']['text_message']);
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$userId = 132;
		$this->userModel = $this->loginUser($userId, 1);

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$campaign = $this->emailMarketingCampaignModel->browseBy('id', '1');
		$this->assertEqual($campaign['EmailMarketingCampaign']['name'], 'Test Compaign (Edited)');

		$campaign['EmailMarketingCampaign']['name'] = 'Test Compaign (Updated)';

		$this->testAction(
				'/admin/email_marketing/email_marketing_campaigns/edit/1',
				array('data' => $campaign, 'method' => 'post')
		);

		$this->assertEqual('Email Marketing Campaign has been updated.', $this->emailMarketingCampaignsController->Session->read('Message.flash.message'));

		$campaign = $this->emailMarketingCampaignModel->browseBy('id', '1');
		$this->assertEqual('Test Compaign (Updated)', $campaign['EmailMarketingCampaign']['name']);
	}

/**
 * testAdminEditOtherPersonMailingList method
 *
 * @return void
 */
	public function testAdminEditOtherPersonCampaign() {
		$this->expectException('NotFoundException');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$campaign = $this->emailMarketingCampaignModel->browseBy('id', '1');
		$this->assertEqual($campaign['EmailMarketingCampaign']['name'], 'Test Compaign (Edited)');

		$campaign['EmailMarketingCampaign']['name'] = 'Test Compaign (Updated)';

		$this->testAction(
				'/admin/email_marketing/email_marketing_campaigns/edit/1',
				array('data' => $campaign, 'method' => 'post')
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

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$existingCampaigns = $this->emailMarketingCampaignModel->find ( 'all', array('conditions' => array('deleted' => 0), 'contain' => false));
		$currentCampaignsAmount = count($existingCampaigns);

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_campaigns/delete/1',
				array(
					'method' => 'post'
				)
		);

		$currentCampaigns = $this->emailMarketingCampaignModel->find ( 'all', array('conditions' => array('deleted' => 0), 'contain' => false));
		$afterDeleteCampaignsAmount = count($currentCampaigns);

		$this->assertEqual($afterDeleteCampaignsAmount, ($currentCampaignsAmount - 1));

		$deletedList = $this->emailMarketingCampaignModel->browseBy ( 'id', '1', false);
		$this->assertEqual($deletedList['EmailMarketingCampaign']['deleted'], 1); // soft delete
	}

/**
 * testAdminDeleteOthersCampaign method
 *
 * @return void
 */
	public function testAdminDeleteOthersCampaign(){
		$this->expectException('NotFoundException');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$results = $this->testAction(
				'/admin/email_marketing/email_marketing_campaigns/delete/1',
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

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$existingCampaigns = $this->emailMarketingCampaignModel->find ('all', array('conditions' => array('deleted' => 0), 'contain' => false));
		$currentCampaignsAmount = count($existingCampaigns);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_campaigns/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '3')
					),
					'method' => 'post'
				)
		);

		$currentCampaigns = $this->emailMarketingCampaignModel->find ( 'all', array('conditions' => array('deleted' => 0), 'contain' => false));
		$afterDeleteCampaignsAmount = count($currentCampaigns);

		$this->assertEqual($afterDeleteCampaignsAmount, ($currentCampaignsAmount - 2));

		$deletedCampaigns = $this->emailMarketingCampaignModel->findAll ( 'id', [1,3], false);
		foreach($deletedCampaigns as $deletedCampaign){
			$this->assertEqual($deletedCampaign['EmailMarketingCampaign']['deleted'], 1);
		}
	}

/**
 * testAdminBatchDeleteOthersCampaign method
 *
 * @return void
 */
	public function testAdminBatchDeleteOthersCampaign(){
		$this->expectException('Exception');

		$userId = 235;
		$this->userModel = $this->loginUser($userId, 19);

		$this->emailMarketingCampaignModel = new EmailMarketingCampaign(false, null, 'test'); // If Model has virtual field, we need to manually require the model file and new the model class to make sure the constructor is run

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/email_marketing/email_marketing_campaigns/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('1', '3')
					),
					'method' => 'post'
				)
		);
	}

/**
 * testAdminSendEmail method
 *
 * @return void
 */
	public function testAdminSendEmail(){

	}
}