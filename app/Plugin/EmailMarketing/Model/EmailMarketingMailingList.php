<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * MailingList Model
 *
 */
class EmailMarketingMailingList extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id,$table,$ds);
        $this->virtualFields = array(
            'total_subscribers_amount' => "SELECT COUNT(*) FROM email_marketing_subscribers as ems WHERE ems.email_marketing_list_id = EmailMarketingMailingList.id AND ems.deleted = 0 AND ems.unsubscribed = 0 AND ems.excluded = 0"
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
        'active' => array(
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

    public $belongsTo = array(
        'EmailMarketingUser' => array(
            'className'  => 'EmailMarketing.EmailMarketingUser',
            'foreignKey' => 'email_marketing_user_id',
            'dependent'  => false
        )
    );

    public $hasMany = array(
        'EmailMarketingSubscriber' => array(
            'className'    => 'EmailMarketing.EmailMarketingSubscriber',
            'foreignKey'   => 'email_marketing_list_id',
            'dependent'    => true
        )
    );

    public $hasAndBelongsToMany = array(
        'EmailMarketingCampaign' => array(
            'className' => 'EmailMarketing.EmailMarketingCampaign',
            'joinTable' => 'email_marketing_campaign_lists',
            'foreignKey' => 'email_marketing_list_id',
            'associationForeignKey' => 'email_marketing_campaign_id',
            'unique' => true
        )
    );

    public function saveList($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateList($id, $data){
    	$this->id = $id;
        $this->contain();
        if(isset($data['EmailMarketingMailingList']['active']) && $data['EmailMarketingMailingList']['active'] == 1){
        	$isDeleted = $this->field('deleted');
        	if($isDeleted == 1){
        		$data['EmailMarketingMailingList']['deleted'] = 0;
        	}
        }
        return $this->saveAll($data, array('validate' => 'first'));
    }

    public function deleteList($id){
    	// To keep reference, we don't actually delete the list, we only mark it as deleted
    	$this->id = $id;
    	if($this->saveField('deleted', 1)){
    		$this->saveField('active', 0);
    		$this->query('DELETE FROM `email_marketing_campaign_lists` WHERE `email_marketing_list_id` = '.$id.';'); // Delete the list from campaign
    		$EmailMarketingSubscriber = ClassRegistry::init("EmailMarketing.EmailMarketingSubscriber");
    		return $EmailMarketingSubscriber->deleteAllSubscribersInMailingList($id);
    	}else{
    		return FALSE;
    	}
    }
}
