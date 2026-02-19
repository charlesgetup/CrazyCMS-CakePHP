<?php
class EmailMarketingSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {

		if (isset($event['create'])) {

			App::uses('ClassRegistry', 'Utility');

			switch ($event['create']) {
				case 'email_marketing_plans':
					$EmailMarketingPlan = ClassRegistry::init('EmailMarketingPlan');
					$EmailMarketingPlan->create();
					$EmailMarketingPlan->save(
							array('EmailMarketingPlan' =>
								array('id' => 1, 'private_email_user_id' => '0', 'name' => 'Prepaid Plan', 'description' => '<ul><li>Cost per email is only $%unit-price%</li><li>Store up to %subscriber-limit% subscribers</li><li>Blacklist up to %subscriber-limit% subscribers</li><li>Live chat and ticket support</li><li>Email template editor</li><li>Bulk responsive templates on sale</li><li>Email campaign, mailing list management</li><li>Blacklist subscribers capability</li><li>Real-time analytics and statistics summary</li><li>Delivered, click, open, unsubscribe tracking</li><li>Bounce rate reporting</li><li>Bulk subscribers email upload</li></ul>', 'pay_per_email' => '1', 'email_limit' => '0', 'subscriber_limit' => '2000', 'sender_limit' => '0', 'extra_attr_limit' => '0', 'unit_price' => '0.00050', 'total_price' => '0.00000'),
								array('id' => 2, 'private_email_user_id' => '0', 'name' => 'Starter Plan', 'description' => '<ul><li>Send up to %email-limit% emails per month</li><li>Store up to %subscriber-limit% subscribers</li><li>Blacklist up to %subscriber-limit% subscribers</li><li>Live chat and ticket support</li><li>Scheduled sending capability</li><li>Email template editor</li><li>Bulk responsive templates on sale</li><li>Email campaign, mailing list management</li><li>Blacklist subscribers capability</li> <li>Real-time analytics and statistics summary</li><li>Delivered, click, open, unsubscribe tracking</li><li>Bounce rate reporting</li><li>Bulk subscribers email upload</li><li>Cost per extra email is $%unit-price%</li></ul>', 'pay_per_email' => '0', 'email_limit' => '20000', 'subscriber_limit' => '4000', 'sender_limit' => '0', 'extra_attr_limit' => '0', 'unit_price' => '0.00050', 'total_price' => '9.95000'),
								array('id' => 3, 'private_email_user_id' => '0', 'name' => 'Mid-level Plan', 'description' => '<ul><li>Send up to %email-limit% emails per month</li><li>Store up to %subscriber-limit% subscribers</li><li>Blacklist up to %subscriber-limit% subscribers</li><li>Live chat and ticket support</li><li>Scheduled sending capability</li><li>Email template editor</li><li>Bulk responsive templates on sale</li><li>Email campaign, mailing list management</li><li>Blacklist subscribers capability</li> <li>Real-time analytics and statistics summary</li><li>Delivered, click, open, unsubscribe tracking</li><li>Bounce rate reporting</li><li>Bulk subscribers data (including email, name and %extra-attr-limit% extra attributes) upload</li><li>Device, browser, geolocation tracking</li><li>Custom branding for %sender-limit% domain(s)</li><li>Cost per extra email is $%unit-price%</li></ul>', 'pay_per_email' => '0', 'email_limit' => '200000', 'subscriber_limit' => '40000', 'sender_limit' => '1', 'extra_attr_limit' => '2', 'unit_price' => '0.00050', 'total_price' => '119.95000'),
								array('id' => 4, 'private_email_user_id' => '0', 'name' => 'Advanced Plan', 'description' => '<ul><li>Send up to %email-limit% emails per month</li><li>Store up to %subscriber-limit% subscribers</li><li>Blacklist up to %subscriber-limit% subscribers</li><li>Live chat and ticket support</li><li>Scheduled sending capability</li><li>Email template editor</li><li>Bulk responsive templates on sale</li><li>Email campaign, mailing list management</li><li>Blacklist subscribers capability</li> <li>Real-time analytics and statistics summary</li><li>Delivered, click, open, unsubscribe tracking</li><li>Bounce rate reporting</li><li>Bulk subscribers data (including email, name and %extra-attr-limit% extra attributes) upload</li><li>Device, browser, geolocation tracking</li><li>Custom branding for up to %sender-limit% domain(s)</li><li>Email campaign, mailing list and subscribers management API</li><li>Cost per extra email is $%unit-price%</li></ul>', 'pay_per_email' => '0', 'email_limit' => '1200000', 'subscriber_limit' => '100000', 'sender_limit' => '3', 'extra_attr_limit' => '10', 'unit_price' => '0.00050', 'total_price' => '499.95000'),
							)
					);
					break;
			}
		}

	}

	public $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'index'),
		'model' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'parent_id' => array('column' => 'parent_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'aco_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'aro_id' => array('column' => 'aro_id', 'unique' => 0),
			'aco_id' => array('column' => 'aco_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_blacklisted_subscribers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'EmailMarketingUser.id'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'email' => array('column' => 'email', 'unique' => 0),
			'user_id' => array('column' => 'email_marketing_user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_campaign_lists = array(
		'email_marketing_campaign_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_list_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'indexes' => array(
			'campaign_list_id' => array('column' => array('email_marketing_campaign_id', 'email_marketing_list_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_campaigns = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'Email Marketing User ID'),
		'email_marketing_sender_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'email_marketing_template_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'email_marketing_purchased_template_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'from_email_address_prefix' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'send_format' => array('type' => 'string', 'null' => false, 'default' => 'BOTH', 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'TXT,HTML,BOTH', 'charset' => 'utf8'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PENDING', 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PENDING,SENDING,SENT,CANCELLED,SCHEDULED', 'charset' => 'utf8'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'text_message' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'use_external_web_page' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'html_url' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 512, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'cached_web_page' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Cache external web page content', 'charset' => 'utf8'),
		'template_data' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'ready_to_go_email_body' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'scheduled_time' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'Send email at scheduled time'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Mark as deleted'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'email_marketing_user_id', 'unique' => 0),
			'template_id' => array('column' => 'email_marketing_template_id', 'unique' => 0),
			'send_format' => array('column' => 'send_format', 'unique' => 0),
			'status' => array('column' => 'status', 'unique' => 0),
			'deleted' => array('column' => 'deleted', 'unique' => 0),
			'email_marketing_sender_id' => array('column' => 'email_marketing_sender_id', 'unique' => 0),
			'scheduled_time' => array('column' => 'scheduled_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_email_links = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_statistic_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'url' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'statistic_id' => array('column' => 'email_marketing_statistic_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_mailing_lists = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'EmailMarketingUser.id'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Mark as deleted'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => array('email_marketing_user_id', 'active'), 'unique' => 0),
			'deleted' => array('column' => 'deleted', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_plans = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'private_email_user_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Email marketing user private plan'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'pay_per_email' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'email_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'Max email send amount per month. 0 no limit'),
		'subscriber_limit' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'sender_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
		'extra_attr_limit' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3),
		'unit_price' => array('type' => 'float', 'null' => false, 'default' => '0.00000', 'length' => '12,5', 'comment' => 'per email'),
		'total_price' => array('type' => 'float', 'null' => false, 'default' => '0.00000', 'length' => '12,5', 'comment' => 'monthly package cost'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'private_email_user_id' => array('column' => 'private_email_user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_purchased_templates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_template_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'customized_html' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => 'Client modified version', 'charset' => 'latin1'),
		'customized_txt' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2', 'comment' => 'Purchased price'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PURCHASED', 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PURCHASED,REFUNDED,GIFT', 'charset' => 'utf8'),
		'purchased_timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'purchase_once' => array('column' => array('email_marketing_user_id', 'email_marketing_template_id'), 'unique' => 1),
			'status' => array('column' => 'status', 'unique' => 0),
			'user_id' => array('column' => 'email_marketing_user_id', 'unique' => 0),
			'template_id' => array('column' => 'email_marketing_template_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_senders = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'sender_domain' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'dkim_privkey' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'DKIM private key', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'sender_domain' => array('column' => 'sender_domain', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_statistics = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_campaign_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'invalid' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'duplicated' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'blacklisted' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'processed' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'forwarded' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PENDING', 'length' => 50, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PENDING, SENDING, SENT, SCHEDULED, FAILED, PAUSED, CANCELLED', 'charset' => 'utf8'),
		'send_start' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'send_end' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'status' => array('column' => 'status', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_subscriber_bounce_records = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_statistic_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_subscriber_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'bounce_reason' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'timestamp' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'one_subscriber_per_statistic_record' => array('column' => array('email_marketing_statistic_id', 'email_marketing_subscriber_id'), 'unique' => 1),
			'statistic_id' => array('column' => 'email_marketing_statistic_id', 'unique' => 0),
			'subscriber_id' => array('column' => 'email_marketing_subscriber_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_subscriber_click_records = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_email_link_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_subscriber_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'email_link_id' => array('column' => 'email_marketing_email_link_id', 'unique' => 0),
			'subscriber_id' => array('column' => 'email_marketing_subscriber_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_subscriber_open_records = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_statistic_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_subscriber_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'ip' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'is_mobile' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'browser_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'browser_version' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'platform_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'platform_vesion' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'country' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'region' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'city' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'timestamp' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'statistic_id' => array('column' => 'email_marketing_statistic_id', 'unique' => 0),
			'subscriber_id' => array('column' => 'email_marketing_subscriber_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_subscribers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_list_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'extra_attr' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'excluded' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'unsubscribed' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Mark as deleted'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'email' => array('column' => 'email', 'unique' => 0),
			'excluded' => array('column' => 'excluded', 'unique' => 0),
			'list_id' => array('column' => 'email_marketing_list_id', 'unique' => 0),
			'unsubscribed' => array('column' => 'unsubscribed', 'unique' => 0),
			'deleted' => array('column' => 'deleted', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_templates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'If this field is NULL, this means the template is a system template. System template can be used internally or for sale. Check for_sale field for more info.'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'txt_msg' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'html' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'markup_list' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'price' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2', 'key' => 'index'),
		'special_price' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '5,2', 'key' => 'index', 'comment' => 'For promotion usage'),
		'for_sale' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index', 'comment' => 'Mark as deleted'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'for_sale' => array('column' => 'for_sale', 'unique' => 0),
			'user_id' => array('column' => 'email_marketing_user_id', 'unique' => 0),
			'price' => array('column' => 'price', 'unique' => 0),
			'created' => array('column' => 'created', 'unique' => 0),
			'special_price' => array('column' => 'special_price', 'unique' => 0),
			'deleted' => array('column' => 'deleted', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public $email_marketing_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'unique', 'comment' => '(Child) User.id'),
		'email_marketing_plan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_sender_limit' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4),
		'free_emails' => array('type' => 'integer', 'null' => false, 'default' => '100', 'length' => 6, 'comment' => 'per month'),
		'payment_cycle' => array('type' => 'string', 'null' => false, 'default' => 'MANUAL', 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'MANUAL, MONTHLY, QUARTERLY, HALF_YEAR, ANNUALLY', 'charset' => 'utf8'),
		'used_email_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'Used to compare with the limit'),
		'total_sent_email_amount' => array('type' => 'integer', 'null' => false, 'default' => '0', 'comment' => 'Record total sent email amount'),
		'prepaid_amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '11,2'),
		'last_pay_date' => array('type' => 'date', 'null' => true, 'default' => null),
		'next_pay_date' => array('type' => 'date', 'null' => true, 'default' => null, 'comment' => 'This is also the next due date'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 1),
			'plan_id' => array('column' => 'email_marketing_plan_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

}
