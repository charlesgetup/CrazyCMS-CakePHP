<?php
/**
 *  This model stores the auto payment details, like "add prepaid credit to account".
 *  This is a temperary record, and at one time, one user can only have one auto payment record saved, because auto payment needs to be paid right away.
 *
 *  If user trigger an auto payment, check temp (PaymentTempInvoice) record.
 *  If the temp record is not there, save it; if it is there, update it no matter what the existing record is.
 *  After the payment, no matter the payment is successful or not, delete the temp record.
 *  If the payment is successfully done, transfer the temp record to real invoice table (PaymentInvoice).
 */

App::uses('PaymentAppModel', 'Payment.Model');

class PaymentTempInvoice extends PaymentAppModel {

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
		'created_by' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'purchase_code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
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
		'content' => array(
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

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ClientUser' => array(
			'className'  => 'User',
			'foreignKey' => 'user_id',
			'dependent'  => false
		)
	);

	public $hasOne = array(
		'PaymentRecurringAgreement' => array(
			'className'    => 'Payment.PaymentRecurringAgreement',
			'foreignKey'   => 'payment_temp_invoice_id',
			'dependent'    => false
		)
	);

	public function transferTempInvoice($id){

	}

	public function saveTempInvoice($data){
		$this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return $this->getInsertID();
        }else{
            return false;
        }
    }

    public function updateTempInvoice($id, $data){
    	$this->id = $id;
    	$this->contain();
    	if($this->saveAll($data, array('validate' => 'first'))){
    		return $id;
    	}else{
    		return false;
    	}
    }

    public function deleteTempInvoice($id){
    	return $this->delete($id, true);
    }
}
