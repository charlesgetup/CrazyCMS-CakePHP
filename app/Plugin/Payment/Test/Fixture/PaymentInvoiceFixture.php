<?php
/**
 * PaymentInvoiceFixture
 *
 */
class PaymentInvoiceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'is_auto_created' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'),
		'is_emailed_client' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'paid_amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'due_date' => array('type' => 'date', 'null' => false, 'default' => null, 'key' => 'index'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'manual created staff user id'),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'manual updated staff user id'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'is_auto_created' => array('column' => 'is_auto_created', 'unique' => 0),
			'created_by' => array('column' => 'created_by', 'unique' => 0),
			'modified_by' => array('column' => 'modified_by', 'unique' => 0),
			'due_date' => array('column' => 'due_date', 'unique' => 0),
			'is_emailed_client' => array('column' => 'is_emailed_client', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'user_id' => '132',
			'is_auto_created' => 0,
			'is_emailed_client' => 0,
			'number' => 'SD2014072900000001',
			'amount' => '100.00',
			'paid_amount' => '10.00',
			'content' => '<p>1, web</p>
<p>&nbsp;</p>
<p>2, email</p>
<p>&nbsp;</p>
<p>3, nothing</p>',
			'due_date' => '2014-08-29',
			'status' => 'PARTIAL_PAID',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-07-29 21:35:59',
			'modified' => '2014-07-30 21:50:23'
		),
		array(
			'id' => '4',
			'user_id' => '132',
			'is_auto_created' => 0,
			'is_emailed_client' => 1,
			'number' => 'SD2014073100000001',
			'amount' => '100.00',
			'paid_amount' => '0.00',
			'content' => '<p>123</p><p>456</p>',
			'due_date' => '2014-07-31',
			'status' => 'PENDING',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-07-31 20:32:51',
			'modified' => '2014-08-03 13:26:44'
		),
		array(
			'id' => '10',
			'user_id' => '132',
			'is_auto_created' => 1,
			'is_emailed_client' => 0,
			'number' => 'EMM2014082300000005',
			'amount' => '95.00',
			'paid_amount' => '0.00',
			'content' => '<p>Send up to 100000 emails per month.</p>
<p>Use prepaid method ($0.001 per email) to send over limit emails.</p>
<p>No subscriber limit.</p>',
			'due_date' => '2014-08-30',
			'status' => 'PENDING',
			'created_by' => '133',
			'modified_by' => '133',
			'created' => '2014-08-23 22:30:54',
			'modified' => '2014-08-23 22:30:54'
		),
		array(
			'id' => '11',
			'user_id' => '132',
			'is_auto_created' => 1,
			'is_emailed_client' => 0,
			'number' => 'EMM2014082300000006',
			'amount' => '95.00',
			'paid_amount' => '0.00',
			'content' => '<p>Send up to 100000 emails per month.</p>
<p>Use prepaid method ($0.001 per email) to send over limit emails.</p>
<p>No subscriber limit.</p>',
			'due_date' => '2014-08-30',
			'status' => 'PENDING',
			'created_by' => '133',
			'modified_by' => '133',
			'created' => '2014-08-23 22:31:51',
			'modified' => '2014-08-23 22:31:51'
		),
		array(
			'id' => '62',
			'user_id' => '132',
			'is_auto_created' => 0,
			'is_emailed_client' => 0,
			'number' => 'SD2014082400000049',
			'amount' => '100.00',
			'paid_amount' => '0.00',
			'content' => '&lt;p&gt;test&lt;/p&gt;',
			'due_date' => '2014-08-24',
			'status' => 'PENDING',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-08-24 16:11:03',
			'modified' => '2014-08-24 16:11:23'
		),
		array(
			'id' => '63',
			'user_id' => '132',
			'is_auto_created' => 0,
			'is_emailed_client' => 0,
			'number' => 'SD2014082400000050',
			'amount' => '100.00',
			'paid_amount' => '0.00',
			'content' => '&lt;p&gt;test&lt;/p&gt;',
			'due_date' => '2014-08-24',
			'status' => 'PENDING',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-08-24 16:12:49',
			'modified' => '2014-08-24 16:13:00'
		),
		array(
			'id' => '64',
			'user_id' => '132',
			'is_auto_created' => 0,
			'is_emailed_client' => 0,
			'number' => 'SD2014082400000051',
			'amount' => '100.00',
			'paid_amount' => '0.00',
			'content' => '&lt;p&gt;test&lt;/p&gt;',
			'due_date' => '2014-08-24',
			'status' => 'PENDING',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-08-24 16:14:04',
			'modified' => '2014-08-24 16:14:13'
		),
		array(
			'id' => '65',
			'user_id' => '132',
			'is_auto_created' => 0,
			'is_emailed_client' => 0,
			'number' => 'SD2014082400000052',
			'amount' => '100.00',
			'paid_amount' => '0.00',
			'content' => '&lt;p&gt;test&lt;/p&gt;',
			'due_date' => '2014-08-24',
			'status' => 'PENDING',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-08-24 16:15:28',
			'modified' => '2014-08-24 16:15:36'
		),
		array(
			'id' => '66',
			'user_id' => '132',
			'is_auto_created' => 0,
			'is_emailed_client' => 0,
			'number' => 'SD2014082400000053',
			'amount' => '100.00',
			'paid_amount' => '0.00',
			'content' => '&lt;p&gt;test&lt;/p&gt;',
			'due_date' => '2014-08-24',
			'status' => 'PENDING',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-08-24 16:16:25',
			'modified' => '2014-08-24 16:16:34'
		),
		array(
			'id' => '67',
			'user_id' => '235',
			'is_auto_created' => 0,
			'is_emailed_client' => 0,
			'number' => 'SD2014082400000054',
			'amount' => '100.00',
			'paid_amount' => '0.00',
			'content' => '&lt;p&gt;123&lt;/p&gt;',
			'due_date' => '2014-08-24',
			'status' => 'PENDING',
			'created_by' => '1',
			'modified_by' => '1',
			'created' => '2014-08-24 16:18:35',
			'modified' => '2014-08-24 16:18:44'
		),
	);

}
