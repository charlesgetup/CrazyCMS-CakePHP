<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * SubscriberClickRecord Model
 *
 */
class EmailMarketingSubscriberClickRecord extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'email_marketing_email_link_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    	'email_marketing_subscriber_id' => array(
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
        'EmailMarketingEmailLink' => array(
            'className'  => 'EmailMarketing.EmailMarketingEmailLink',
            'foreignKey' => 'email_marketing_email_link_id',
            'dependent'  => true
        ),
    	'EmailMarketingSubscriber' => array(
    		'className'  => 'EmailMarketing.EmailMarketingSubscriber',
    		'foreignKey' => 'email_marketing_subscriber_id',
    		'dependent'  => true
    	)
    );

    public function saveSubscriberClickRecord($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateSubscriberClickRecord($id, $data) {
    	$this->id = $id;
    	$this->contain();
    	return $this->saveAll($data, array('validate' => 'first'));
    }
}
