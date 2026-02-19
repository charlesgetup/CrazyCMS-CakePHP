<?php

App::uses('PaymentAppModel', 'Payment.Model');

class PaymentPayer extends PaymentAppModel {

	public $useTable = 'payment_payer';

	public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ClientUser' => array(
			'className'  => 'User',
			'foreignKey' => 'user_id',
			'dependent'  => true
		),
	);

	public $hasMany = array(
		'PaymentPayPalGateway' => array(
			'className'    => 'Payment.PaymentPayPalGateway',
			'foreignKey'   => 'payment_payer_id',
			'dependent'    => true
		),
		'PaymentRecurringAgreement' => array(
			'className'    => 'Payment.PaymentRecurringAgreement',
			'foreignKey'   => 'payment_payer_id',
			'dependent'    => true
		),
	);

	public function savePayer($data){
		$this->create();
		if($this->saveAll($data , array('validate' => 'first'))){
			return true;
		}else{
			return false;
		}
	}

	public function updatePayer($id, $data){
		$this->id = $id;
		$this->contain();
		return $this->saveAll($data, array('validate' => 'first'));
	}

	public function getPayerByPaymentMethod($superUserId, $paymentMethod = null){

		if(empty($superUserId) || !is_numeric($superUserId) || empty($paymentMethod)){
			return false;
		}

		return $this->find('first', array(
			'conditions' => array(
				'user_id' 		 => $superUserId,
				'payment_method' => $paymentMethod
			),
			'contain' => false
		));
	}
}
