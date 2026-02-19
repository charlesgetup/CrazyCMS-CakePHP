<?php
/**
 * EmailMarketingCampaignListFixture
 *
 */
class EmailMarketingCampaignListFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'email_marketing_campaign_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_list_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'indexes' => array(
			'campaign_list_id' => array('column' => array('email_marketing_campaign_id', 'email_marketing_list_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'email_marketing_campaign_id' => '1',
			'email_marketing_list_id' => '1'
		),
	);

}
