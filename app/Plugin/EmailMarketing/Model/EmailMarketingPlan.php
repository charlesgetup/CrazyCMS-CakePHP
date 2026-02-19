<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * Plan Model
 *
 */
class EmailMarketingPlan extends EmailMarketingAppModel {

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id,$table,$ds);
        $this->virtualFields = array(
            'total_users_amount' => "SELECT COUNT(*) FROM email_marketing_users as emu WHERE emu.email_marketing_plan_id = EmailMarketingPlan.id"
        );
    }

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'description' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email_limit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'subscriber_limit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'sender_limit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'extra_attr_limit' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'unit_price' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
        'total_price' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
	);

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = array(
        'EmailMarketingUser' => array(
            'className'    => 'EmailMarketing.EmailMarketingUser',
            'foreignKey'   => 'email_marketing_plan_id',
            'dependent'    => true
        )
    );

    public function findPlanList(){
    	return $this->find('list', array(
    		'fields' 	=> array('EmailMarketingPlan.id', 'EmailMarketingPlan.name'),
    		'recursive' => 0
    	));
    }

    public function savePlan($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return $this->id;
        }else{
            return false;
        }
    }

    public function updatePlan($id, $data) {
        $this->id = $id;
        $this->contain();
        return $this->saveAll($data, array('validate' => 'first'));
    }

    public function deletePlan($id){
        $EmailMarketingUser = ClassRegistry::init('EmailMarketing.EmailMarketingUser');
        $users = $EmailMarketingUser->findAllByEmailMarketingPlanId($id);

        if(!empty($users)){
            $User = ClassRegistry::init('User');
            foreach($users as $user){
            	if(!$User->deleteUser($user['EmailMarketingUser']['user_id'])){
            		return false;
            	}
            }
        }

        return $this->delete($id, false);
    }

/**
 * Delete unused private plan.
 * If transferred from private plan to normal plan or other private plan, the unused private plan will be deleted. So only one private plan will be kept.
 * @param unknown $emailMarketingUserId
 */
    public function deletePrivatePlan($emailMarketingUserId){

    	if(empty($emailMarketingUserId)){
    		return false;
    	}

    	$EmailMarketingUser = ClassRegistry::init('EmailMarketing.EmailMarketingUser');

    	$conditionsSubQuery['EmailMarketingUser.id'] = $emailMarketingUserId;
    	$db = $this->getDataSource();
    	$subQuery = $db->buildStatement(
    			array(
    				'fields'     => array('EmailMarketingUser.email_marketing_plan_id'),
    				'table'      => $db->fullTableName($EmailMarketingUser),
    				'alias'      => 'EmailMarketingUser',
    				'limit'      => null,
    				'offset'     => null,
    				'joins'      => array(),
    				'conditions' => $conditionsSubQuery,
    				'order'      => null,
    				'group'      => null
    			),
    			$EmailMarketingUser
    	);
    	$subQuery = 'EmailMarketingPlan.id NOT IN (' . $subQuery . ') AND EmailMarketingPlan.private_email_user_id = ' .$emailMarketingUserId;
    	$subQueryExpression = $db->expression($subQuery);

    	$conditions[] = $subQueryExpression;

    	$unusedPrivatePlanIds = $this->find('all', compact('conditions'));

    	$unusedPrivatePlanIds = Hash::extract($unusedPrivatePlanIds, '{n}.EmailMarketingPlan.id');

		if(empty($unusedPrivatePlanIds)){
			return true;
		}

    	return $this->deleteAll(array(
    		'id' => $unusedPrivatePlanIds
    	), false);
    }
}
