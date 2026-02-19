<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * Campaign Model
 *
 */
class EmailMarketingCampaign extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id,$table,$ds);
        $this->virtualFields = array(
            'user_name'     		=> "SELECT CONCAT(u.first_name,\" \",u.last_name) FROM users as u LEFT JOIN email_marketing_users as emu ON emu.user_id = u.id WHERE emu.id = EmailMarketingCampaign.email_marketing_user_id",
            'template_name' 		=> "SELECT t.name FROM email_marketing_templates as t WHERE t.id = EmailMarketingCampaign.email_marketing_template_id",
        	'from_email_address' 	=> "SELECT CONCAT(c.from_email_address_prefix,\"@\",s.sender_domain) FROM email_marketing_campaigns as c LEFT JOIN email_marketing_senders as s ON s.id = c.email_marketing_sender_id WHERE c.id = EmailMarketingCampaign.id"
        );
    }

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
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'is not empty',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'subject' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'is not empty',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum length of 255 characters'
            )
        ),
        'html_url' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 512),
                'message' => 'Maximum length of 512 characters'
            )
        ),
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
            'dependent'  => false
        ),
    	'EmailMarketingPurchasedTemplate' => array(
    		'className'  => 'EmailMarketing.EmailMarketingPurchasedTemplate',
    		'foreignKey' => 'email_marketing_purchased_template_id',
    		'dependent'  => false
    	),
    	'EmailMarketingSender' => array(
    		'className'  => 'EmailMarketing.EmailMarketingSender',
    		'foreignKey' => 'email_marketing_sender_id',
    		'dependent'  => false
    	),
    );

    public $hasMany = array(
        'EmailMarketingStatistic' => array(
            'className'    => 'EmailMarketing.EmailMarketingStatistic',
            'foreignKey'   => 'email_marketing_campaign_id',
            'dependent'    => true
        )
    );

    public $hasAndBelongsToMany = array(
        'EmailMarketingMailingList' => array(
            'className' => 'EmailMarketing.EmailMarketingMailingList',
            'joinTable' => 'email_marketing_campaign_lists',
            'foreignKey' => 'email_marketing_campaign_id',
            'associationForeignKey' => 'email_marketing_list_id',
            'unique' => true,
        )
    );

    public function countCampaignByUserId($userServiceId){

    	if(empty($userServiceId) || !is_numeric($userServiceId)){
    		return false;
    	}

    	return $this->find('count', array(
    		'conditions' => array(
    			'User.id' => $userServiceId,
    		),
    		'joins' 	=> array(
    			array(
    				'table' => 'email_marketing_users',
    				'alias' => 'EmailMarketingUsers',
    				'type' => 'inner',
    				'conditions' => array(
    					'EmailMarketingUsers.id = EmailMarketingCampaign.email_marketing_user_id'
    				)
    			),
    			array(
    				'table' => 'users',
    				'alias' => 'User',
    				'type' => 'inner',
    				'conditions' => array(
    					'User.id = EmailMarketingUsers.user_id'
    				)
    			),
    		),
    	));
    }

    public function saveCampaign($data){
    	return $this->saveAll($data, array('validate' => 'first'));
    }

    public function updateCampaign($id, $data){
        return $this->saveCampaign($data);
    }

    public function deleteCampaign($id){
        // To keep reference, we don't actually delete the list, we only mark it as deleted
    	$this->read(null, $id);
    	$this->set('deleted', 1);
    	return $this->save();
    }
}
