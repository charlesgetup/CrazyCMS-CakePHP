<?php
App::uses('AppModel', 'Model');
class Address extends AppModel {

    public $actsAs = array('Containable');

    public $validate = array(
    	'user_id' => array(
    		'numeric' => array(
    			'rule' => array('numeric'),
    			//                'message' => 'Your custom message here',
    			'allowEmpty' => true,
    			'required'   => false,
    			'last'       => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
        'country_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
//                'message' => 'Your custom message here',
                'allowEmpty' => true,
                'required'   => false,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'User' => array(
            'className'     => 'User',
            'foreignKey'    => 'user_id',
            'dependent'     => true
        ),
        'Country' => array(
            'className'     => 'Country',
            'foreignKey'    => 'country_id',
            'dependent'     => false
        )
    );

    public function saveAddress($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }
}
?>
