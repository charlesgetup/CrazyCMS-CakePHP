<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * SubscriberOpenRecord Model
 *
 */
class EmailMarketingSubscriberBounceRecord extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'email_marketing_statistic_id' => array(
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
    	),
    	'bounce_type' => array(
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
        'EmailMarketingStatistic' => array(
            'className'  => 'EmailMarketing.EmailMarketingStatistic',
            'foreignKey' => 'email_marketing_statistic_id',
            'dependent'  => true
        ),
    	'EmailMarketingSubscriber' => array(
    		'className'  => 'EmailMarketing.EmailMarketingSubscriber',
    		'foreignKey' => 'email_marketing_subscriber_id',
    		'dependent'  => true
    	)
    );

    public function saveSubscriberBounceRecord($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateSubscriberBounceRecord($id, $data) {
    	$this->id = $id;
    	$this->contain();
    	return $this->saveAll($data, array('validate' => 'first'));
    }

    public function checkExistingBounceRecord($statisticId, $subscriberId){
    	$record = $this->find('first', array(
    		'fields' => array('id'),
    		'conditions' => array(
	    		'email_marketing_statistic_id' => $statisticId,
    			'email_marketing_subscriber_id' => $subscriberId
	    	),
    		'contain' => false
    	));

    	return @$record['EmailMarketingSubscriberBounceRecord']['id'];
    }
}
