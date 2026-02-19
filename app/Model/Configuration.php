<?php
App::uses('AppModel', 'Model');
/**
 * Configuration Model
 *
*/
class Configuration extends AppModel {

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct ( $id, $table, $ds );
		$this->virtualFields = array (
			'modified_by_name' => "Select CONCAT(User.first_name,' ',User.last_name) From users AS User Where User.id = Configuration.modified_by",
			'user_popup' =>  'CONCAT(\'<a href="#" data-link="/admin/users/view/\',user_id,\'" class="blue popup-view title-view-user" >\',user_id,\'</a>\')',
		);
	}

	public $actsAs = array('Containable');

	/**
	 * Validation rules
	 *
	 * @var array
	*/
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				'required'   => false,
				'last'       => false, // Stop validation after this rule
				'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
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
			)
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public $belongsTo = array(
		'User' => array(
			'className'     => 'User',
			'foreignKey'    => 'user_id',
			'dependent'     => false
		)
	);

	public function findConfiguration($name, $type, $userId = null, $returnObj = false){
		$conditions = array(
			'conditions' => array(
				'type' 		=> $type,
				'name' 		=> $name,
				'user_id' 	=> empty($userId) ? 1 : $userId //FIXME if a manager create a (common) config and manager user id is not 1. how can we get this config value. (can use user group in query)
			)
		);

		$setting = $this->find('first', $conditions);

		if(empty($setting)){
			return null;
		}else{
			if($returnObj){
				return isset($setting['Configuration']) ? $setting['Configuration'] : null;
			}else{
				return isset($setting['Configuration']['value']) ? $setting['Configuration']['value'] : null;
			}
		}
	}

	public function saveConfiguration($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return $this->id;
        }else{
            return false;
        }
    }

    public function updateConfiguration($id, $data){
    	$this->id = $id;
    	$this->contain();
        return $this->saveAll($data, array('validate' => 'first'));
    }

    public function deleteConfiguration($id){
    	return $this->delete($id, false);
    }
}
?>