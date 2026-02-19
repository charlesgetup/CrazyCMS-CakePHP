<?php
/**
 * EmailMarketingCampaignFixture
 *
 */
class EmailMarketingCampaignFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'email_marketing_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'Email Marketing User ID'),
		'email_marketing_sender_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'email_marketing_template_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'from_email_address_prefix' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'send_format' => array('type' => 'string', 'null' => false, 'default' => 'BOTH', 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'TXT,HTML,BOTH', 'charset' => 'utf8'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PENDING', 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PENDING,SENDING,SENT,CANCELLED,SCHEDULED', 'charset' => 'utf8'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'text_message' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'use_external_web_page' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'html_url' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 512, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'cached_web_page' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Cache external web page content', 'charset' => 'utf8'),
		'template_data' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'ready_to_go_email_body' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
			'email_marketing_sender_id' => array('column' => 'email_marketing_sender_id', 'unique' => 0)
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
			'id' => '1',
			'email_marketing_user_id' => '20',
			'email_marketing_sender_id' => '3',
			'email_marketing_template_id' => null,
			'name' => 'Test Compaign (Edited)',
			'from_email_address_prefix' => 'contact',
			'send_format' => 'BOTH',
			'status' => 'PENDING',
			'subject' => 'Email Campaign',
			'text_message' => 'Text message 123123123',
			'use_external_web_page' => 0,
			'html_url' => '',
			'cached_web_page' => null,
			'template_data' => '<p>Hi [FIRST-NAME] [LAST-NAME],</p>
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
</table>',
			'ready_to_go_email_body' => '<html><head></head><body><p>Hi [FIRST-NAME] [LAST-NAME],</p>
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
<td><a href="http://crazycms.loc/email_marketing/email_marketing_statistics/trackClick/BgMKAAs/[SUBSCRIBER-ENCRYPTED-ID]"  target="_blank">Buy</a></td>
<td><a href="http://crazycms.loc/email_marketing/email_marketing_statistics/trackClick/BgMKAAo/[SUBSCRIBER-ENCRYPTED-ID]"  target="_blank">Buy</a></td>
</tr>
</tbody>
</table><img src="http://crazycms.loc/email_marketing/email_marketing_statistics/trackOpen/[EMAIL-MESSAGE-ID]" width="1" height="1" border="0" /></body></html>',
			'deleted' => 0,
			'created' => '2014-07-22 17:09:28',
			'modified' => '2015-11-18 17:01:07'
		),
		array(
			'id' => '2',
			'email_marketing_user_id' => '20',
			'email_marketing_sender_id' => '3',
			'email_marketing_template_id' => null,
			'name' => 'T12',
			'from_email_address_prefix' => 'contact',
			'send_format' => 'BOTH',
			'status' => '',
			'subject' => 'T1',
			'text_message' => 'Test1',
			'use_external_web_page' => 0,
			'html_url' => '',
			'cached_web_page' => null,
			'template_data' => '<p>test</p>
<p>test1</p>
<p>1</p>
<p>1</p>
<p>1</p>
<p>1</p>',
			'ready_to_go_email_body' => null,
			'deleted' => 1,
			'created' => '2015-08-26 14:28:25',
			'modified' => '2015-08-26 17:05:19'
		),
		array(
			'id' => '3',
			'email_marketing_user_id' => '20',
			'email_marketing_sender_id' => '3',
			'email_marketing_template_id' => null,
			'name' => 'T3',
			'from_email_address_prefix' => 'test',
			'send_format' => 'BOTH',
			'status' => '',
			'subject' => 'T3',
			'text_message' => 'test
test',
			'use_external_web_page' => 1,
			'html_url' => 'http://test.com',
			'cached_web_page' => '&lt;!DOCTYPE HTML PUBLIC &quot;-//W3C//DTD HTML 4.01//EN&quot; &quot;http://www.w3.org/TR/html4/strict.dtd&quot;&gt;&lt;html&gt;&lt;head&gt;&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html; charset=iso-8859-1&quot;&gt;&lt;meta http-equiv=&quot;Content-Script-Type&quot; content=&quot;text/javascript&quot;&gt; &lt;/head&gt;&lt;body&gt; &lt;/body&gt;&lt;/html&gt;',
			'template_data' => '',
			'ready_to_go_email_body' => null,
			'deleted' => 0,
			'created' => '2015-09-01 17:05:55',
			'modified' => '2015-09-01 17:04:54'
		),
	);

}
