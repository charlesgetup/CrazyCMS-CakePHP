<?php

App::uses('PaymentAppModel', 'Payment.Model');

class PaymentPayPalGateway extends PaymentAppModel {

	public $useTable = 'payment_pay_pal_gateway';

	public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'payment_invoice_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'amount' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'status' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'intent' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'created' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'PaymentInvoice' => array(
			'className'  => 'Payment.PaymentInvoice',
			'foreignKey' => 'payment_invoice_id',
			'dependent'  => false
		),
		'PaymentPayer' => array(
			'className'  => 'Payment.PaymentPayer',
			'foreignKey' => 'payment_payer_id',
			'dependent'  => false
		),
		'PaymentRecurringAgreement' => array(
			'className'  => 'Payment.PaymentRecurringAgreement',
			'foreignKey' => 'payment_recurring_agreement_id',
			'dependent'  => false
		),
	);

	public function saveTransaction($data){
		$this->create();
		if($this->saveAll($data , array('validate' => 'first'))){
			return true;
		}else{
			return false;
		}
	}

	public function getSaleIdByInvoice($invoiceId, $intent = 'sale'){
		$paymentTransaction = $this->find('first',array(
			'conditions' => array(
				'payment_invoice_id' => $invoiceId,
				'intent' => $intent,
				'status' => Configure::read('Payment.paypal.gateway.status.approved')
			),
			'recursive' => -1
		));
		if(!empty($paymentTransaction['PaymentPayPalGateway']['sale_id'])){
			return $paymentTransaction['PaymentPayPalGateway']['sale_id'];
		}else{
			return false;
		}
	}

	public function getTransactionBySaleIdAndAgreementId($saleId, $agreementId){

		if(empty($saleId) || empty($agreementId) || !is_numeric($saleId) || !is_numeric($agreementId)){
			return false;
		}

		return $this->find('first',array(
			'conditions' => array(
				'payment_recurring_agreement_id' => $agreementId,
				'sale_id' => $saleId
			),
			'recursive' => -1
		));
	}

	public function countRecurringAgreementTransactions($agreementId){
		if(empty($agreementId) || !is_numeric($agreementId)){
			return false;
		}

		return $this->find('count',array(
			'conditions' => array(
				'payment_recurring_agreement_id' => $agreementId
			),
			'recursive' => -1
		));
	}
}
