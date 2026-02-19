<?php

App::uses('PaymentAppModel', 'Payment.Model');

class PaymentRecurringAgreement extends PaymentAppModel {

	public $useTable = 'payment_recurring_agreement';

	public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'payment_payer_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'recurring_plan_id' => array(
			'unique' => array(
				'rule' => 'isUnique',
				'required' => true
			),
		),
		'recurring_agreement_id' => array(
			'unique' => array(
				'rule' => 'isUnique',
				'required' => true
			),
		),
		'service_account_user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ServiceUserAccount' => array(
			'className'  => 'User',
			'foreignKey' => 'service_account_user_id',
			'dependent'  => true
		),
		'PaymentPayer' => array(
			'className'  => 'Payment.PaymentPayer',
			'foreignKey' => 'payment_payer_id',
			'dependent'  => false
		),
		'PaymentTempInvoice' => array(
			'className'  => 'Payment.PaymentTempInvoice',
			'foreignKey' => 'payment_temp_invoice_id',
			'dependent'  => false
		),
	);

	public $hasMany = array(
		'PaymentPayPalGateway' => array(
			'className'    => 'Payment.PaymentPayPalGateway',
			'foreignKey'   => 'payment_recurring_agreement_id',
			'dependent'    => true
		)
	);

	public function saveRecurringAgreement($data){
		$this->create();
		if($this->saveAll($data , array('validate' => 'first'))){
			return true;
		}else{
			return false;
		}
	}

	public function updateRecurringAgreement($id, $data){
		$this->id = $id;
		$this->contain();
		return $this->saveAll($data, array('validate' => 'first'));
	}

	public function getRecurringAgreementByPayerIdAndFakeAgreementId($payerId, $uniqueFakeAgreementId){

		if(empty($payerId) || !is_numeric($payerId) || empty($uniqueFakeAgreementId)){
			return false;
		}

		return $this->find('first', array(
			'conditions' => array(
				'payment_payer_id' 			=> $payerId,
				'recurring_agreement_id'	=> $uniqueFakeAgreementId
			),
			'contain' => false
		));
	}

	public function getRecurringAgreementByUserIdAndPaymentMethod($superUserId, $paymentMethod){

		if(empty($superUserId) || !is_numeric($superUserId) || empty($paymentMethod)){
			return false;
		}

		return $this->find('all', array(
			'conditions' => array(
				'PaymentPayer.user_id' 		  => $superUserId,
				'PaymentPayer.payment_method' => $paymentMethod
			),
			'joins' => array(
				array(
					'table' => 'payment_payer',
					'alias' => 'PaymentPayer',
					'type' => 'inner',
					'conditions' => array(
						'PaymentRecurringAgreement.payment_payer_id = PaymentPayer.id'
					)
				)
			),
			'contain' => false,
			'order'   => array('PaymentRecurringAgreement.start_time DESC')
		));
	}

	public function removeProcessedTempInvoiceId($id){
    	$this->id = $id;
    	return $this->saveField('payment_temp_invoice_id', '');
    }

    public function getPreviousAgreementsByNewAgreement($recurringAgreement){

    	if(empty($recurringAgreement['PaymentRecurringAgreement'])){
    		return false;
    	}

    	return $this->find('all', array(
    		'conditions' => array(
    			'payment_payer_id' 			=> $recurringAgreement['PaymentRecurringAgreement']['payment_payer_id'],
    			'currency'					=> $recurringAgreement['PaymentRecurringAgreement']['currency'],
    			'status !='					=> Configure::read('Payment.paypal.gateway.agreement.status.cancelled'),
    			'status !='					=> Configure::read('Payment.paypal.gateway.agreement.status.expired'),
    			'service_account_user_id' 	=> $recurringAgreement['PaymentRecurringAgreement']['service_account_user_id'],
    			'recurring_agreement_id !=' => $recurringAgreement['PaymentRecurringAgreement']['recurring_agreement_id']
	    	),
    		'contain' => false
    	));
    }
}
