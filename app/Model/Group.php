<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 * @property User $User
 */
class Group extends AppModel {

    public $actsAs = array('Acl' => array('type' => 'requester'));

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
                'required'   => true,
                'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'unique' => array(
                'rule' => 'isUnique',
            	'message' => 'is not unique',
            )
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'User' => array(
			'className'      => 'User',
			'foreignKey'     => 'group_id',
			'dependent'      => false,
			'conditions'     => '',
			'fields'         => '',
			'order'          => '',
			'limit'          => '',
			'offset'         => '',
			'exclusive'      => '',
			'finderQuery'    => '',
			'counterQuery'   => ''
		)
	);

    public function parentNode() {
        return null;
    }

    public function getGroupName($id){
    	$groupFields = $this->read('name', $id);
    	return @$groupFields['Group']['name'];
    }

/**
 * This is only used in Model. In controller, $this->superUserGroupId is the group ID.
 * @param string $name
 * @return int
 */
    public function getGroupId($name){
    	$groupId = $this->find('first', array(
    		'fields' => array('Group.id'),
    		'conditions' => array(
    			'Group.name' => $name
    		),
    		'recursive' => -1,
    	));
    	return $groupId['Group']['id'];
    }

    public function getDepartments(){

    	$managerGroupIdentifier = Configure::read('System.manager.group.name');

    	$list = $this->find('list', array(
    		'fields' => array('Group.id', 'Group.name'),
    		'conditions' => array(
	    		'Group.name like' => '%'.$managerGroupIdentifier
	    	),
    		'recursive' => 1,
    	));

    	foreach ($list as $id => &$name){
    		$name = str_replace(" {$managerGroupIdentifier}", "", $name);
    	}

    	return $list;
    }
}
