<?php

App::uses('PaymentAppModel', 'Payment.Model');

class PaymentInvoice extends PaymentAppModel {

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
		'modified_by' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'number' => array(
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
		'due_date' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
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
		'created' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'modified' => array(
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
			'dependent'  => true
		),
		'AdminCreatedUser' => array(
			'className'  => 'User',
			'foreignKey' => 'created_by',
			'dependent'  => false
		),
		'AdminModifiedUser' => array(
			'className'  => 'User',
			'foreignKey' => 'modified_by',
			'dependent'  => false
		),
	);

	public $hasOne = array(
		'WebDevelopmentStage' => array(
			'className'    => 'WebDevelopment.WebDevelopmentStage',
			'foreignKey'   => 'payment_invoice_id',
			'dependent'    => false
		),
	);

	public $hasMany = array(
		'PaymentPayPalGateway' => array(
			'className'    => 'Payment.PaymentPayPalGateway',
			'foreignKey'   => 'payment_invoice_id',
			'dependent'    => true
		),
	);

	public function getInvoiceSavedPath($userId, $invoiceNumber, $onlyReturnFileName = false, $returnFile = false){
		$invoiceFileName = $invoiceNumber .".pdf";
		$invoiceFilePath = Configure::read('Payment.invoice.path') .DS;
		$invoiceFilePath = str_replace("{user_id}", $userId, $invoiceFilePath);
		return $onlyReturnFileName ? $invoiceFileName : ($returnFile ? Configure::read('System.aws.s3.bucket.link.prefix') .$invoiceFilePath .$invoiceFileName : $invoiceFilePath);
	}

	public function getSummary($userId = null){

		$conditions 		= empty($userId) ? array() : array('PaymentInvoice.user_id' => $userId);
		$totalInvoiceNumber = $this->find('count', array('conditions' => $conditions, 'recursive' => -1));
		$totalInvoiceAmount = $this->find('first', array(
			'fields' 	 => 'SUM(PaymentInvoice.amount) as amount',
			'conditions' => $conditions,
			'recursive'  => -1
		));
		$totalInvoiceAmount = $totalInvoiceAmount[0]['amount'];
		$totalPaidAmount 	= $this->find('first', array(
			'fields' 	 => 'SUM(PaymentInvoice.paid_amount) as paid_amount',
			'conditions' => $conditions,
			'recursive'  => -1
		));
		$totalPaidAmount 	= $totalPaidAmount[0]['paid_amount'];
		$totalUnpaidAmount 	=  $totalInvoiceAmount - $totalPaidAmount;

		$conditions 				 = am($conditions, array('PaymentInvoice.created LIKE "' .date("Y-m") .'%"'));
		$thisMonthTotalInvoiceNumber = $this->find('count', array('conditions' => $conditions, 'recursive'  => -1));
		$thisMonthTotalInvoiceAmount = $this->find('first', array(
			'fields' 	 => 'SUM(PaymentInvoice.amount) as amount',
			'conditions' => $conditions,
			'recursive'  => -1
		));
		$thisMonthTotalInvoiceAmount = $thisMonthTotalInvoiceAmount[0]['amount'];
		$thisMonthTotalPaidAmount 	 = $this->find('first', array(
			'fields' 	 => 'SUM(PaymentInvoice.paid_amount) as paid_amount',
			'conditions' => $conditions,
			'recursive'  => -1
		));
		$thisMonthTotalPaidAmount 	 = $thisMonthTotalPaidAmount[0]['paid_amount'];
		$thisMonthTotalUnpaidAmount  =  $thisMonthTotalInvoiceAmount - $thisMonthTotalPaidAmount;

		return array($totalInvoiceNumber, $totalInvoiceAmount, $totalPaidAmount, $totalUnpaidAmount, $thisMonthTotalInvoiceNumber, $thisMonthTotalInvoiceAmount, $thisMonthTotalPaidAmount, $thisMonthTotalUnpaidAmount);
	}

	public function getPendingInvoice($userId, $service){

	}

	public function generateInvoiceNumber($purchaseCode){
		if(empty($purchaseCode)){
			return false;
		}

		$todayTotalInvoiceNumber = $this->find('count', array('conditions' => array(
			'PaymentInvoice.created LIKE "' .date("Y-m-d") .'%"'
		)));

		$timeTxt = strval(microtime(true)); // Include micro seconds to avoid duplicate invoice number. Invoice number DB field is unique.
		$timeTxt = str_replace(".", "-", $timeTxt);

		return $purchaseCode .'-' .$timeTxt .str_pad(($todayTotalInvoiceNumber + 1), 8, "0", STR_PAD_LEFT);
	}

	public function saveInvoice($data){

		unset($this->validate['modified_by']);

		if(!isset($data['PaymentInvoice']['paid_amount']) || empty($data['PaymentInvoice']['paid_amount'])){
			$data['PaymentInvoice']['paid_amount'] = 0;
		}

		$this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateInvoice($id, $data){
    	$this->id = $id;
    	$this->contain();
    	return $this->saveAll($data, array('validate' => 'first'));
    }

    //Invoice should not be deleted. Only can modify it.
//     public function deleteInvoice($id){
//     	return $this->delete($id, true);
//     }

    public function outputFile($s3Path, $fileName = null, $fileContent = ''){
    	if(empty($fileName) || empty($s3Path)){
    		return FALSE;
    	}
    	$fileNameDetails = pathinfo($fileName);
    	if(!isset($fileNameDetails['filename']) || empty($fileNameDetails['filename'])){
    		return FALSE;
    	}

    	if(file_put_contents($fileName, $fileContent)){

    		$s3Action 			= Configure::read('System.aws.s3.action.put');

    		try{

    			// Only save public key in S3
    			if($this->amazonS3StorageManagement($s3Action, $s3Path, array($fileName))){
    				unlink($fileName);
    				return true;
    			}

    			return false;

    		}catch(AmazonS3Exception $exception){

    			return false;
    		}

    	}else{
    		return FALSE;
    	}
    }
}
