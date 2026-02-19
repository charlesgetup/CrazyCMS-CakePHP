<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * EmailMarketingMailingList Test Case
 *
 */
class EmailMarketingMailingListTest extends CakeTestCase {

	protected $EmailMarketingMailingList;

	public function setUp() {
		parent::setUp();

		$this->EmailMarketingMailingList = ClassRegistry::init('EmailMarketing.EmailMarketingMailingList');
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
 * testSaveList method
 *
 * @return void
 */
	public function testSaveList() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testUpdateList method
 *
 * @return void
 */
	public function testUpdateList() {

		// Set the deleted list to active will bring the list back
		$data = [
			'EmailMarketingMailingList' => [
				'id' => '2',
				'email_marketing_user_id' => '20',
				'name' => 'Test 1',
				'description' => '',
				'active' => 1, // Change from 0 to 1
				'deleted' => 1, // Auto change this field to 0
				'created' => '2015-07-06 16:55:02',
				'modified' => '2015-07-06 17:17:59'
			]
		];
		$this->EmailMarketingMailingList->updateList(2, $data);

		$list = $this->EmailMarketingMailingList->browseBy('id', 2);
		$this->assertEqual($list['EmailMarketingMailingList']['created'], '2015-07-06 16:55:02');
		$this->assertEqual($list['EmailMarketingMailingList']['deleted'], 0);

	}

/**
 * testDeleteList method
 *
 * This is actually a soft-delete
 *
 * @return void
 */
	public function testDeleteList() {

		$listId = 1;
		$list = $this->EmailMarketingMailingList->browseBy('id', $listId);
		$this->assertEqual($list['EmailMarketingMailingList']['active'], 1);
		$this->assertEqual($list['EmailMarketingMailingList']['deleted'], 0);

		$this->EmailMarketingMailingList->deleteList($listId);

		$list = $this->EmailMarketingMailingList->browseBy('id', $listId);
		$this->assertEqual($list['EmailMarketingMailingList']['active'], 0);
		$this->assertEqual($list['EmailMarketingMailingList']['deleted'], 1);

	}
}