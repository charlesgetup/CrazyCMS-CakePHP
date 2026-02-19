<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * EmailMarketingPlan Test Case
 *
 */
class EmailMarketingTest extends CakeTestCase {

	protected $EmailMarketingPlan;

	public function setUp() {
		parent::setUp();

		$this->EmailMarketingPlan = ClassRegistry::init('EmailMarketing.EmailMarketingPlan');
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
 * testConstructVirtualField method
 *
 * @return void
 */
	public function testConstructVirtualField() {
		$planId = 3;
		$plan = $this->EmailMarketingPlan->browseBy('id', $planId);
		$this->assertEqual($plan['EmailMarketingPlan']['total_users_amount'], 1);
	}

/**
 * testFindPlanList method
 *
 * @return void
 */
	public function testFindPlanList() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testSavePlan method
 *
 * @return void
 */
	public function testSavePlan() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testUpdatePlan method
 *
 * @return void
 */
	public function testUpdatePlan() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testDeletePlan method
 *
 * Delete plan and also delete user email marketing account
 *
 * @return void
 */
	public function testDeletePlan() {

		$UserModel = ClassRegistry::init('User');
		$userCountBeforeDelete = $UserModel->find('count');

		$EmailMarketingMailingListModel = ClassRegistry::init('EmailMarketingMailingList');
		$emailMarketingMailingListsBeforeDelete = $EmailMarketingMailingListModel->browseBy('email_marketing_user_id', 20);
		$emailMarketingMailingListsBeforeDelete = Set::classicExtract($emailMarketingMailingListsBeforeDelete, '{n}.EmailMarketingMailingList.id');

		$EmailMarketingSubscriberModel = ClassRegistry::init('EmailMarketingSubscriber');
		$emailMarketingSubscribersBeforeDelete = $EmailMarketingSubscriberModel->find('all', array(
			'conditions' => array('email_marketing_list_id' => $emailMarketingMailingListsBeforeDelete),
			'contain' => false
		));
		$emailMarketingSubscribersBeforeDelete = Set::classicExtract($emailMarketingSubscribersBeforeDelete, '{n}.EmailMarketingSubscriber.id');

		$this->EmailMarketingPlan->deletePlan(3);

		$userCountAfterDelete = $UserModel->find('count');
		$this->assertEqual($userCountBeforeDelete, $userCountAfterDelete + 1);

		$plan = $this->EmailMarketingPlan->browseBy('id', 3);
		$this->assertEmpty($plan);

		$user = $UserModel->browseBy('id', 203);
		$this->assertEmpty($user);

		$EmailMarketingUserModel = ClassRegistry::init('EmailMarketingUser');
		$emailMarketingUser = $EmailMarketingUserModel->browseBy('id', 20);
		$this->assertEmpty($emailMarketingUser);

		$mailingLists = $EmailMarketingMailingListModel->browseBy('email_marketing_user_id', 20);
		$this->assertEmpty($mailingLists);

		// Delete Mailing list from DB also deletes the subscribers in the list
		$emailMarketingSubscribers = $EmailMarketingSubscriberModel->find('all', array(
			'conditions' => array('email_marketing_list_id' => $emailMarketingMailingListsBeforeDelete),
			'contain' => false
		));
		$this->assertEmpty($emailMarketingSubscribers);

		// Delete subscriber also deletes the related click records
		$EmailMarketingSubscriberClickRecordModel = ClassRegistry::init('EmailMarketingSubscriberClickRecord');
		$emailMarketingSubscriberClicksBeforeDelete = $EmailMarketingSubscriberClickRecordModel->browseBy('email_marketing_subscriber_id', $emailMarketingSubscribersBeforeDelete);
		$this->assertEmpty($emailMarketingSubscriberClicksBeforeDelete);

		// Delete subscriber also deletes the related open records
		$EmailMarketingSubscriberOpenRecordModel = ClassRegistry::init('EmailMarketingSubscriberOpenRecord');
		$emailMarketingSubscriberOpenRecordsBeforeDelete = $EmailMarketingSubscriberOpenRecordModel->browseBy('email_marketing_subscriber_id', $emailMarketingSubscribersBeforeDelete);
		$this->assertEmpty($emailMarketingSubscriberOpenRecordsBeforeDelete);

		$EmailMarketingBlacklistedSubscriberModel = ClassRegistry::init('EmailMarketingBlacklistedSubscriber');
		$blacklistedSubscribers = $EmailMarketingBlacklistedSubscriberModel->browseBy('email_marketing_user_id', 20);
		$this->assertEmpty($blacklistedSubscribers);

		$EmailMarketingCampaignModel = ClassRegistry::init('EmailMarketingCampaign');
		$emailMarketingCampaigns = $EmailMarketingCampaignModel->browseBy('email_marketing_user_id', 20);
		$this->assertEmpty($emailMarketingCampaigns);

		$EmailMarketingPurchasedTemplateModel = ClassRegistry::init('EmailMarketingPurchasedTemplate');
		$emailMarketingPurchasedTemplates = $EmailMarketingPurchasedTemplateModel->browseBy('email_marketing_user_id', 20);
		$this->assertEmpty($emailMarketingPurchasedTemplates);

		$EmailMarketingTemplateModel = ClassRegistry::init('EmailMarketingTemplate');
		$emailMarketingTemplates = $EmailMarketingTemplateModel->browseBy('email_marketing_user_id', 20);
		$this->assertEmpty($emailMarketingTemplates);

		$EmailMarketingSenderModel = ClassRegistry::init('EmailMarketingSender');
		$emailMarketingSenders = $EmailMarketingSenderModel->browseBy('email_marketing_user_id', 20);
		$this->assertEmpty($emailMarketingSenders);
	}
}