<?php
App::uses('LiveChatAppModel', 'LiveChat.Model');
/**
 * Plan Model
 *
*/
class LiveChatPlan extends LiveChatAppModel {

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id,$table,$ds);
		$this->virtualFields = array(
			'total_users_amount' => "SELECT COUNT(*) FROM live_chat_users as emu WHERE emu.live_chat_plan_id = LiveChatPlan.id"
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
		'price' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $hasMany = array(
		'LiveChatUser' => array(
			'className'    => 'LiveChat.LiveChatUser',
			'foreignKey'   => 'live_chat_plan_id',
			'dependent'    => true
		)
	);

	public function findPlanList(){
		return $this->find('list', array(
			'fields' 	=> array('LiveChatPlan.id', 'LiveChatPlan.name'),
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

	public function getSlug($id){

		$plan = $this->browseBy($this->primaryKey, $id);
		return $plan['LiveChatPlan']['slug'];
	}

	public function updatePlan($id, $data) {
		$this->id = $id;
		$this->contain();
		return $this->saveAll($data, array('validate' => 'first'));
	}

	public function deletePlan($id){
		$LiveChatUser = ClassRegistry::init('LiveChat.LiveChatUser');
		$users = $LiveChatUser->findAllByLiveChatPlanId($id);

		if(!empty($users)){
			$User = ClassRegistry::init('User');
			foreach($users as $user){
				if(!$User->deleteUser($user['LiveChatUser']['user_id'])){
					return false;
				}
			}
		}

		return $this->delete($id, false);
	}

}