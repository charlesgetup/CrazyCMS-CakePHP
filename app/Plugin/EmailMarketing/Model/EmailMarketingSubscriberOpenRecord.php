<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * SubscriberOpenRecord Model
 *
 */
class EmailMarketingSubscriberOpenRecord extends EmailMarketingAppModel {

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
    	'ip' => array(
    		'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => true,
				'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
    	),
    	'is_mobile' => array(
    		'numeric' => array(
    			'rule' => array('numeric'),
    			//'message' => 'Your custom message here',
    			'allowEmpty' => true,
    			'required'   => false,
    			//'last'       => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'browser_name' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => true,
    			'required' => false,
    			//'last' => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'browser_version' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => true,
    			'required' => false,
    			//'last' => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'platform_name' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => true,
    			'required' => false,
    			//'last' => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'platform_vesion' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => true,
    			'required' => false,
    			//'last' => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'country' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => true,
    			'required' => false,
    			//'last' => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'region' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => true,
    			'required' => false,
    			//'last' => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'city' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => true,
    			'required' => false,
    			//'last' => false, // Stop validation after this rule
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

    public function saveSubscriberOpenRecord($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateSubscriberOpenRecord($id, $data) {
    	$this->id = $id;
    	$this->contain();
    	return $this->saveAll($data, array('validate' => 'first'));
    }
}
