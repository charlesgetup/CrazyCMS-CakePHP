<?php 
class PaymentSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
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

	public $payment_invoices = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'is_auto_created' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'),
		'is_emailed_client' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'number' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'currency' => array('type' => 'string', 'null' => false, 'default' => 'AUD', 'length' => 3, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'paid_amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'refund_amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'recurring_plan_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'due_date' => array('type' => 'date', 'null' => false, 'default' => null, 'key' => 'index'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'manual created staff user id'),
		'modified_by' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'manual updated staff user id'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'number' => array('column' => 'number', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'is_auto_created' => array('column' => 'is_auto_created', 'unique' => 0),
			'created_by' => array('column' => 'created_by', 'unique' => 0),
			'modified_by' => array('column' => 'modified_by', 'unique' => 0),
			'due_date' => array('column' => 'due_date', 'unique' => 0),
			'is_emailed_client' => array('column' => 'is_emailed_client', 'unique' => 0),
			'currency' => array('column' => 'currency', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $payment_pay_pal_gateway = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'payment_payer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'payment_invoice_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'payment_recurring_agreement_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index'),
		'is_temp' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'whether this a temp invoice or not'),
		'currency' => array('type' => 'string', 'null' => false, 'default' => 'AUD', 'length' => 3, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => '0.000', 'length' => '12,3'),
		'transaction_fee' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'tax' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'payment_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'sale_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'intent' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'payment_payer_id' => array('column' => 'payment_payer_id', 'unique' => 0),
			'payment_invoice_id' => array('column' => 'payment_invoice_id', 'unique' => 0),
			'payment_recurring_agreement_id' => array('column' => 'payment_recurring_agreement_id', 'unique' => 0),
			'currency' => array('column' => 'currency', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $payment_payer = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'payment_method' => array('type' => 'string', 'null' => false, 'default' => 'PAYPAL', 'length' => 20, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $payment_recurring_agreement = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'payment_payer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'recurring_plan_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'recurring_agreement_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'purchase_code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'currency' => array('type' => 'string', 'null' => false, 'default' => 'AUD', 'length' => 3, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'recurring_amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'payment_cycle' => array('type' => 'string', 'null' => false, 'default' => 'MONTHLY', 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'MONTHLY, QUARTERLY, HALF_YEAR, ANNUALLY', 'charset' => 'utf8'),
		'start_time' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Agreement status', 'charset' => 'utf8'),
		'service_account_user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'Recurring payment related service user record ID'),
		'payment_temp_invoice_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'comment' => 'Need to process payment temp invoice when initial amount is charged'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'recurring_agreement_id' => array('column' => 'recurring_agreement_id', 'unique' => 1),
			'recurring_plan_id' => array('column' => 'recurring_plan_id', 'unique' => 1),
			'user_id' => array('column' => 'payment_payer_id', 'unique' => 0),
			'active' => array('column' => 'active', 'unique' => 0),
			'service_account_user_id' => array('column' => 'service_account_user_id', 'unique' => 0),
			'currency' => array('column' => 'currency', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $payment_temp_invoices = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'is_auto_created' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'),
		'purchase_code' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'currency' => array('type' => 'string', 'null' => false, 'default' => 'AUD', 'length' => 3, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'payment_cycle' => array('type' => 'string', 'null' => false, 'default' => 'MANUAL', 'length' => 20, 'collate' => 'utf8_general_ci', 'comment' => 'MANUAL, MONTHLY, QUARTERLY, HALF_YEAR, ANNUALLY', 'charset' => 'utf8'),
		'amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2'),
		'recurring_amount' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '12,2', 'comment' => 'Record payment amount in each recurring cycle'),
		'recurring_plan_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'due_date' => array('type' => 'date', 'null' => false, 'default' => null, 'key' => 'index'),
		'related_update_data' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => null, 'key' => 'index', 'comment' => 'manual created staff user id'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'is_auto_created' => array('column' => 'is_auto_created', 'unique' => 0),
			'created_by' => array('column' => 'created_by', 'unique' => 0),
			'due_date' => array('column' => 'due_date', 'unique' => 0),
			'currency' => array('column' => 'currency', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

}
