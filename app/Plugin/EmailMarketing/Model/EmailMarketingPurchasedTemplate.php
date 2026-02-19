<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * PurchasedTemplate Model
 *
 */
class EmailMarketingPurchasedTemplate extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'email_marketing_user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'email_marketing_template_id' => array(
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

    public $belongsTo = array(
        'EmailMarketingUser' => array(
            'className'  => 'EmailMarketing.EmailMarketingUser',
            'foreignKey' => 'email_marketing_user_id',
            'dependent'  => false
        ),
        'EmailMarketingTemplate' => array(
            'className'  => 'EmailMarketing.EmailMarketingTemplate',
            'foreignKey' => 'email_marketing_template_id',
            'dependent'  => true
        )
    );
    public $hasMany = array(
    	'EmailMarketingCampaign' => array(
    		'className'    => 'EmailMarketing.EmailMarketingCampaign',
    		'foreignKey'   => 'email_marketing_purchased_template_id',
    		'dependent'    => false
    	)
    );

    public function getPurchasedTemplate($emailMarketingUserRecordId, $templateId = null){
    	if(empty($templateId)){
    		return $this->find('all', array(
    			'fields' => array('id', 'email_marketing_user_id', 'email_marketing_template_id'),
    			'conditions' => array(
    				'email_marketing_user_id' => $emailMarketingUserRecordId
    			),
    			'contain' => false
    		));
    	}else{
    		return $this->find('first', array(
    			'conditions' => array(
    				'email_marketing_user_id' 		=> $emailMarketingUserRecordId,
    				'email_marketing_template_id' 	=> $templateId
    			),
    			'contain' => false
    		));
    	}
    }

    public function getPurchasedTemplateById($emailMarketingUserRecordId, $id){
    	return $this->find('first', array(
    		'conditions' => array(
    			'email_marketing_user_id' 		=> $emailMarketingUserRecordId,
    			'id' 							=> $id
    		),
    		'contain' => false
    	));
    }

    // Get template list based on user ID
    public function getPurchasedTemplateList($superUserId){
    	$templateList = $this->find('list', array(
    		'fields' => array(
    			'EmailMarketingPurchasedTemplate.id',
    			'EmailMarketingTemplate.name',
    		),
    		'conditions' => array(
    			'EmailMarketingUser.user_id' => $superUserId,
    			'EmailMarketingTemplate.deleted' => 0
    		),
    		'joins' => array(
    			array(
    				'table' => 'email_marketing_users',
    				'alias' => 'EmailMarketingUser',
    				'type'  => 'inner',
    				'conditions' => array(
    					'EmailMarketingUser.id = EmailMarketingPurchasedTemplate.email_marketing_user_id'
    				)
    			),
    			array(
    				'table' => 'email_marketing_templates',
    				'alias' => 'EmailMarketingTemplate',
    				'type'  => 'inner',
    				'conditions' => array(
    					'EmailMarketingTemplate.id = EmailMarketingPurchasedTemplate.email_marketing_template_id'
    				)
    			)
    		),
    	));

    	return $templateList;
    }

    public function savePurchasedTemplate($data){
    	$this->create();
    	if($this->saveAll($data , array('validate' => 'first'))){
    		return true;
    	}else{
    		return false;
    	}
    }

    // Update method
    public function customizeTemplate($id, $data) {
        $this->id = $id;
        $this->contain();
        return $this->saveAll($data, array('validate' => 'first'));
    }

    public function purchaseTemplate($userId, $template, $tempInvoiceId = null){
    	if(empty($userId) || empty($template) || empty($template['EmailMarketingTemplate']['for_sale']) || !empty($template['EmailMarketingTemplate']['deleted'])){
    		return false;
    	}

    	$emailMarketingUserId = $this->superUserIdToEmailMarketingUserId($userId);

    	$purchasedTemplate = array(
    		'EmailMarketingPurchasedTemplate' => array(
	    		'email_marketing_user_id' 		=> $emailMarketingUserId,
    			'email_marketing_template_id' 	=> $template['EmailMarketingTemplate']['id'],
    			'price' 						=> $template['EmailMarketingTemplate']['price'],
    			'status' 						=> 'PURCHASED',
    			'purchased_timestamp' 			=> date('Y-m-d H:i-s')
	    	)
    	);

    	if(!empty($template['EmailMarketingTemplate']['price'])){
    		$paymentCode = Configure::read('Payment.code.email_marketing.template_purchase');

    		$needToPayAmount = $template['EmailMarketingTemplate']['price'];

    		//TODO Can use prepaid amount to pay for template
//     		$EmailMarketingUserModel = ClassRegistry::init("EmailMarketing.EmailMarketingUser");
//     		$emailMarketingUser = $EmailMarketingUserModel->browseBy("id", $emailMarketingUserId, false);
//     		if(is_numeric($emailMarketingUser['EmailMarketingUser']['prepaid_amount']) && $emailMarketingUser['EmailMarketingUser']['prepaid_amount'] > 0){
//     			if($emailMarketingUser['EmailMarketingUser']['prepaid_amount'] > $needToPayAmount){
//     				//TODO ask client permission or at least give client an alert before purchasing when using prepaid credits
//     				$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] -= $needToPayAmount;
//     				if($EmailMarketingUserModel->updateUser($emailMarketingUserId, $emailMarketingUser)){
//     					$result = $this->savePurchasedTemplate($purchasedTemplate);
//     					return !($result === FALSE); // Force to return TRUE when succeed and FALSE on failure
//     				}else{
//     					$logData = array(
//     						'Log' => array(
//     							'user_id' => $userId,
//     							'type' => Configure::read('Config.type.payment'),
//     							'message' => __('Error: Cannot pay with prepaid credit.'),
//     							'timestamp' => date('Y-m-d H:i:s')
//     						)
//     					);
//     					$this->Log->saveLog($logData);
//     					return false;
//     				}
//     			}else{
//     				$deductMsg = '<p><strong style="color: red;">Deduct $' .$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] .' (prepaid credit) from the original price.</strong></p>';

//     			}
//     		}
//     		if($emailMarketingUser['EmailMarketingUser']['prepaid_amount'] > 0){
//     			$deductMsg = '<p><strong style="color: red;">Deduct $' .$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] .' (prepaid credit) from the original price.</strong></p>';
//     		}
//     		$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] = 0; // Prepaid amount is used to deduct the payment amount

    		$receipt = array(
    			'PaymentTempInvoice' => array(
    				'user_id'				=> $userId,
    				'is_auto_created' 		=> 1,
    				'purchase_code' 		=> $paymentCode,
    				'amount'				=> $needToPayAmount,
    				'content'				=> __('Purchase template') .' "' .$template['EmailMarketingTemplate']['name'] .'" ' .((isset($deductMsg)) ? $deductMsg : ""),
    				'created_by'			=> $userId,
    				'created' 				=> date('Y-m-d H:i:s'),
    				'due_date'				=> date('Y-m-d'),
    				'related_update_data' 	=> serialize(array('plugin' => 'EmailMarketing', 'class' => 'EmailMarketingPurchasedTemplate', 'id' => null, 'data' => $purchasedTemplate))
    			)
    		);
    		if(!empty($tempInvoiceId)){
    			$receipt['PaymentTempInvoice']['id'] = $tempInvoiceId;
    		}

    		$TempInvoice = ClassRegistry::init("Payment.PaymentTempInvoice");

    		return empty($tempInvoiceId) ? $TempInvoice->saveTempInvoice($receipt) : $TempInvoice->updateTempInvoice($tempInvoiceId, $receipt);

    	}else{
    		$result = $this->savePurchasedTemplate($purchasedTemplate);
    		return !($result === FALSE); // Force to return TRUE when succeed and FALSE on failure
    	}
    }
}
