<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * EmailLink Model
 *
 */
class EmailMarketingEmailLink extends EmailMarketingAppModel {

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
        )
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'EmailMarketingStatistic' => array(
            'className'  => 'EmailMarketing.EmailMarketingStatistic',
            'foreignKey' => 'email_marketing_statistic_id',
            'dependent'  => false
        )
    );

    public $hasMany = array(
    	'EmailMarketingSubscriberClickRecord' => array(
    		'className'    => 'EmailMarketing.EmailMarketingSubscriberClickRecord',
    		'foreignKey'   => 'email_marketing_email_link_id',
    		'dependent'    => true
    	)
    );

    public function saveEmailLink($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateEmailLink($id, $data) {
    	$this->id = $id;
    	$this->contain();
    	return $this->saveAll($data, array('validate' => 'first'));
    }
}
