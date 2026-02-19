<?php
App::uses('AppModel', 'Model');
class Country extends AppModel {

    public $actsAs = array('Containable');

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
        ),
    	'code' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => false,
    			'required'   => true,
    			'last'       => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = array(
        'Address' => array(
            'className'    => 'Address',
            'foreignKey'   => 'country_id',
            'dependent'    => false
        )
    );

    public function findCodeByName($name){
    	if(empty($name)){
    		return false;
    	}
    	$code = $this->browseBy('name', $name);
    	return $code['Country']['code'];
    }

    public function saveCountry($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateCountry($id, $data) {
        $this->id = $id;
        $this->contain();
        return $this->saveAll($data, array('validate' => 'first'));
    }

    public function deleteCountry($id){
        return $this->delete($id);
    }
}
?>
