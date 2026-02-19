<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * Statistic Model
 *
 */
class EmailMarketingStatistic extends EmailMarketingAppModel {

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id,$table,$ds);
		$this->virtualFields = array(
			'total_time_used' => "TIMESTAMPDIFF(SECOND, `{$this->alias}`.`send_start`,`{$this->alias}`.`send_end`)"
		);
	}

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'email_marketing_campaign_id' => array(
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
        'EmailMarketingCampaign' => array(
            'className'  => 'EmailMarketing.EmailMarketingCampaign',
            'foreignKey' => 'email_marketing_campaign_id',
            'dependent'  => false
        )
    );

    public $hasMany = array(
    	'EmailMarketingEmailLink' => array(
    		'className'    => 'EmailMarketing.EmailMarketingEmailLink',
    		'foreignKey'   => 'email_marketing_statistic_id',
    		'dependent'    => true
    	),
    	'EmailMarketingSubscriberOpenRecord' => array(
    		'className'    => 'EmailMarketing.EmailMarketingSubscriberOpenRecord',
    		'foreignKey'   => 'email_marketing_statistic_id',
    		'dependent'    => true
    	),
    	'EmailMarketingSubscriberBounceRecord' => array(
    		'className'    => 'EmailMarketing.EmailMarketingSubscriberBounceRecord',
    		'foreignKey'   => 'email_marketing_statistic_id',
    		'dependent'    => true
    	)
    );

    public function saveStatistic($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateStatistic($id, $data) {
    	$this->id = $id;
    	$this->contain();
    	return $this->saveAll($data, array('validate' => 'first'));
    }

    public function deleteStatistic($id){
    	return $this->delete($id);
    }
}
