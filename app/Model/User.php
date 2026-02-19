<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * User Model
 *
 * @property Group $Group
 */
class User extends AppModel {

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct ( $id, $table, $ds );
		$this->virtualFields = array (
			'name' => "CONCAT(`{$this->alias}`.`first_name`,' ',`{$this->alias}`.`last_name`)"
		);
	}

    public $actsAs = array('Acl' => array('type' => 'requester', 'enabled' => false), 'Containable');

    public $transactional = true;

    public $filterArgs = array(
        array('name' => 'first_name',    'type' => 'like'),
        array('name' => 'last_name',     'type' => 'like'),
        array('name' => 'email',         'type' => 'value', 'field' => 'User.email'),
        array('name' => 'email_partial', 'type' => 'like',  'field' => 'User.email'),
    );


/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
        'first_name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            	'message' => 'is not empty',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'maximumLength' => array(
                'rule'       => array('maxLength', '255'),
                'allowEmpty' => false,
            ),
        ),
        'last_name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            	'message' => 'is not empty',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'maximumLength' => array(
                'rule'          => array('maxLength', '255'),
                'allowEmpty'    => false,
            ),
        ),
		'email' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'is not empty',
                'allowEmpty' => false,
                'required'   => true,
                'last'       => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            'valid_email' => array(
                'rule'          => array('email'),
                'allowEmpty'    => false,
                'message'       => 'Please enter a valid email address',
            ),
            'isUnique' => array(
                'rule'          => array('isUniqueEmail'),
                'allowEmpty'    => false,
                'message'       => 'Email address already in use',
            ),
            'confirmed' => array(
                'rule'          => array('matchField', 'email_confirm'),
                'allowEmpty'    => false,
                'message'       => 'Your emails do not match',
            ),
        ),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
            'confirmed' => array(
                'rule'          => array('matchField' , 'password_confirm'),
                'allowEmpty'    => false,
                'message'       => 'Your passwords do not match',
            ),
		),
		'group_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				'required'   => true,
				'last'       => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'staffOrEmployee' => array(
				'rule'          => array('staffOrEmployee', 'group_id'),
                'allowEmpty'    => false,
                'message'       => 'Service group is for Client and employee group is for Employee. You need select only one for a user.',
			),
		),
//         'phone' => array(
//             'notempty' => array(
//                 'rule' => array('notempty'),
//                 'message' => 'is not empty',
//                 'allowEmpty' => false,
//                 'required'   => true,
//                 'last'       => false, // Stop validation after this rule
//                 //'on' => 'create', // Limit validation to 'create' or 'update' operations
//             ),
//         ),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = array(
    	'Children' => array(
    		'className'    => 'User',
    		'foreignKey'   => 'parent_id',
    		'dependent'    => true
	    ),
        'Address' => array(
            'className'    => 'Address',
            'foreignKey'   => 'user_id',
            'dependent'    => true
        ),
    	'Log' => array(
    		'className'    => 'Log',
    		'foreignKey'   => 'user_id',
    		'dependent'    => true
    	),
    	'JobQueue' => array(
    		'className'    => 'JobQueue',
    		'foreignKey'   => 'user_id',
    		'dependent'    => true
    	),
    	'Configuration' => array(
    		'className'    => 'Configuration',
    		'foreignKey'   => 'user_id',
    		'dependent'    => true
    	),
    	'PaymentInvoice' => array(
    		'className'    => 'Payment.PaymentInvoice',
    		'foreignKey'   => 'user_id',
    		'dependent'    => true
    	),
    	'PaymentTempInvoice' => array(
    		'className'    => 'Payment.PaymentTempInvoice',
    		'foreignKey'   => 'user_id',
    		'dependent'    => true
    	),
    	'TaskManagementCreateTask' => array(
	    	'className'    => 'TaskManagement.TaskManagementTask',
	    	'foreignKey'   => 'created_by',
	    	'dependent'    => false
    	),
    	'TaskManagementWorkOnTask' => array(
    		'className'    => 'TaskManagement.TaskManagementTask',
    		'foreignKey'   => 'assignee',
    		'dependent'    => false
    	),
    	'TaskManagementTaskComment' => array(
    		'className'    => 'TaskManagement.TaskManagementTaskComment',
    		'foreignKey'   => 'created_by',
    		'dependent'    => false
    	),
    	'TaskManagementTaskUpload' => array(
    		'className'    => 'TaskManagement.TaskManagementTaskUpload',
    		'foreignKey'   => 'uploaded_by',
    		'dependent'    => false
    	),
    	'WebDevelopmentProjectOwner' => array(
    		'className'    => 'WebDevelopment.WebDevelopmentProject',
    		'foreignKey'   => 'project_owner',
    		'dependent'    => false
    	),
    	'WebDevelopmentProjectCreator' => array(
    		'className'    => 'WebDevelopment.WebDevelopmentProject',
    		'foreignKey'   => 'created_by',
    		'dependent'    => false
    	),
    	'WebDevelopmentStage' => array(
    		'className'    => 'WebDevelopment.WebDevelopmentStage',
    		'foreignKey'   => 'created_by',
    		'dependent'    => false
    	),
    );

    public $hasOne = array(
        'EmailMarketingUser' => array(
            'className'    => 'EmailMarketing.EmailMarketingUser',
            'foreignKey'   => 'user_id',
            'dependent'    => true
        ),
    	'LiveChatUser' => array(
    		'className'    => 'LiveChat.LiveChatUser',
    		'foreignKey'   => 'user_id',
    		'dependent'    => true
    	),
    	'PaymentPayer' => array(
    		'className'    => 'Payment.PaymentPayer',
    		'foreignKey'   => 'user_id',
    		'dependent'    => true
    	),
    	'PaymentRecurringAgreement' => array(
    		'className'    => 'Payment.PaymentRecurringAgreement',
    		'foreignKey'   => 'service_account_user_id',
    		'dependent'    => true
    	)
    );

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Parent' => array(
			'className'    => 'User',
			'foreignKey'   => 'parent_id',
			'dependent'    => false
		),
		'Group' => array(
			'className' 	=> 'Group',
			'foreignKey' 	=> 'group_id',
			'conditions' 	=> '',
			'fields' 		=> '',
			'order' 		=> ''
		)
	);

    public function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        if (isset($this->data['User']['group_id'])) {
            $groupId = $this->data['User']['group_id'];
        } else {
            $groupId = $this->field('group_id');
        }
        if (!$groupId) {
            return null;
        } else {
            return array('Group' => array('id' => $groupId));
        }
    }

    public function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    }

    public function matchField($data , $fieldName, $ruleDefinition){

        if('password_confirm' == $fieldName){
            if($this->data[$this->alias][key($data)] == Security::hash($this->data[$this->alias][$fieldName],null,true) ){
                return true;
            }
        }

        if(!empty($this->data[$this->alias][$fieldName]) && 'email_confirm' == $fieldName){
        	if($this->data[$this->alias][key($data)] == Security::hash($this->data[$this->alias][$fieldName],null,true) ){
        		return true;
        	}
        }

        if($this->data[$this->alias][key($data)] == $this->data[$this->alias][$fieldName]){
            return true;
        }

        return false;
    }

    public function isUniqueEmail() {
        $email      = $this->data[$this->alias]['email'];
        $conditions = array("{$this->alias}.email" => $email);
        if(isset($this->data[$this->alias]['id']) && !empty($this->data[$this->alias]['id'])) {
            $conditions = array_merge($conditions, array("{$this->alias}.id !=" => $this->data[$this->alias]['id']));
        }
        return !$this->hasAny($conditions);
    }

    public function notTheSameAs($data , $fieldName, $ruleDefinition){
        if($data[key($data)] == $this->data[$this->alias][$fieldName]){
            return false;
        }

        return true;
    }

    public function staffOrEmployee($data , $fieldName, $ruleDefinition){
    	if(!empty($this->data[$this->alias][$fieldName]) && empty($this->data[$this->alias]['service_id'])){
    		return true;
    	}elseif (!empty($this->data[$this->alias]['service_id'])){
    		return ($this->data[$this->alias][$fieldName] == Configure::read('System.client.group.id'));
    	}
    	return false;
    }

    public function listAll($conditions = array(), $selectedUserId = null) {
    	$joins = array(
        	array(
        		'table' => 'groups',
        		'alias' => 'Group',
        		'type'  => 'inner',
        		'conditions' => array(
        			'User.group_id = Group.id'
        		)
        	)
        );
    	if(!empty($selectedUserId)){
    		$originalConditions = $conditions;
    		$conditions['OR'] = array(
    			'User.id' => $selectedUserId,
    			'User.parent_id' => $selectedUserId
    		);
    	}
        $data = $this->find('all', array(
        	'conditions' 	=> $conditions,
        	'joins' 		=> $joins,
        	'contain' 		=> false
        ));

        if(empty($data) && !empty($selectedUserId) && !empty($originalConditions)){
        	$conditions = $originalConditions;
        	$joins[] = array(
        		'table' => 'users',
        		'alias' => 'ChildUser',
        		'type'  => 'inner',
        		'conditions' => array(
        			'ChildUser.parent_id = User.id'
        		)
        	);
        	$conditions['ChildUser.id'] = $selectedUserId;
        	$data = $this->find('all', array(
        		'conditions' 	=> $conditions,
        		'joins' 		=> $joins,
        		'contain' 		=> false
        	));
        }
        return Set::combine($data, '/User/id', '/User/name');
    }

    public function getAssociateUsers($userId){
    	return $this->find("all", array(
            'conditions' => array(
                'OR' => array(
                    'parent_id' => $userId,
                    'id'        => $userId
                )
            ),
            'contain' => false
        ));
    }

    public function saveUser($data){

    	// Double check user password
        if(isset($data['User']['password']) && !empty($data['User']['password'])){
            $data['User']['password'] = Security::hash($data['User']['password'], null, true);
            $data['User']['password_confirm'] = Security::hash($data['User']['password_confirm'], null, true);
        }else{
            $data['User']['password'] = Security::hash($this->__generatePassword(), null, true);
            $data['User']['password_confirm'] = $data['User']['password'];
        }

        // Check user group
        // In order to hide client group id value in registration form, we use it as default group id for user to register.
        if(!isset($data["User"]["group_id"]) || empty($data["User"]["group_id"])){
        	$groups 		= $this->Group->find('list', array('conditions' => array('Group.name' => Configure::read('System.client.group.name'))));
        	$clientGroupId 	= array_search(Configure::read('System.client.group.name'), $groups);
			$data["User"]["group_id"] = intval($clientGroupId);
		}

		// Save the requested user
        $this->create();
        if($this->saveAll($data, array('validate' => 'first'))){
            return $this->id;
        }else{
            return false;
        }
    }

    public function updateUser($id, $data) {
        unset($this->validate['email']['confirm_email']);
        unset($this->validate['email']['confirmed']);

        if(isset($data['User']['password']) && !empty($data['User']['password'])){
            $data['User']['password'] = Security::hash($data['User']['password'], null, true);
        }else{
        	unset($data['User']['password']);
        	unset($this->validate['password']['confirmed']);
        	unset($this->validate['password']);
        }

        $this->id = $id;
        $this->contain();

        // Check user group
        // In order to hide client group id value in edit form, we get it druing the update process.
        if(!isset($data["User"]["group_id"]) || empty($data["User"]["group_id"])){
        	$user = $this->read('group_id', $id);
        	$data["User"]["group_id"] = @$user['User']['group_id'];
        }

        if(!$this->saveAll($data, array('validate' => 'first'))){
        	return false;
        }else{

        	// Update some fields on all associated accounts
        	$db = $this->getDataSource();

        	// 1, debug log
        	$db->execute(
        		'UPDATE users SET debug_log = ' .(empty($data["User"]["debug_log"]) ? 0 : 1) .' WHERE id = ' .$id .' or parent_id = ' .$id
        	);

        }

        return true;
    }

    public function deleteUser($id){

    	//NOTE: Client cannot set up any service configurations, and all the service configurtions cannot be deleted

		// Delete related configuration (Configuration is related to the root user, not service user. If delete service user, get service type, like EMAIL_MARKETING, and delete related configuration records.)
//     	$userDetail = $this->browseBy($this->primaryKey, $id, array('Group'));

// 		if(isset($userDetail['Group']['name']) && !empty($userDetail['Group']['name'])){
// 			if(stristr($userDetail['Group']['name'], "Email Marketing")){
// 				$Configuration = ClassRegistry::init('Configuration');
// 				if(!$Configuration->deleteAll(array('user_id' => $userDetail['User']['parent_id'], 'type' => Configure::read('Config.type.emailmarketing')), false)){
// 					return false;
// 				}
// 			}
// 			//TODO check other group here
// 		}

        return $this->delete($id, true);
    }

    public function checkMultipleLogin($loggedInSession, $currentSessionId){

    	return (!empty($loggedInSession) && $currentSessionId != $loggedInSession);
    }

    public function recordLoginSession($userId, $sessionId){
    	if(empty($userId) || empty($sessionId)){
    		return false;
    	}
    	$this->id = $userId;
    	return $this->saveField('session_id', $sessionId);
    }

    public function recordLogin($user, $remoteIP = null){
        $this->id = $user["id"];
        $this->saveField('last_login', date("Y-m-d H:i:s"));
        if(!empty($remoteIP) && $remoteIP != "127.0.0.1" && strtoupper($remoteIP) != "LOCALHOST" && filter_var($remoteIP, FILTER_VALIDATE_IP)){
        	$this->saveField('last_login_ip', $remoteIP);
        }
    }

    public function activateAccount($userId){
    	$this->id = $userId;
    	$this->saveField('active', 1);
    	$this->saveField('modified', date("Y-m-d H:i:s"));
    }

    public function deactivateAccount($userId){
    	$this->id = $userId;
    	$this->saveField('active', 0);
    	$this->saveField('modified', date("Y-m-d H:i:s"));
    }

    /*
     * Generate token for active user account and reset password
     *
     * Generate a random MD5 string and modify the modified (timestamp).
     * If user use this string in 15 mins, that is fine. If after 15 mins, expired the token.
     */
    public function generateToken($userId){
    	$randomStr = $userId .$this->__generatePassword(20) .time();
        $token = md5($randomStr);
        $this->id = $userId;
        $this->saveField('modified', date("Y-m-d H:i:s"));
        $this->saveField('token', $token);
        return $token;
    }

    public function resetPassword($data){

        $this->id = $data['User']['id'];
        $pass = $data['User']['password'];
        $hashed = Security::hash($pass, null, true);

        if($this->saveField('password',  $hashed)){
            $this->saveField('token',  null);
            return true;
        }

        return false;
    }

    public function getUserModelByServiceAccount($serviceAccountId){

    	if(empty($serviceAccountId) || !is_numeric($serviceAccountId)){
    		return false;
    	}

    	$modelInfo = $this->find('first', array(
    		'fields' => array(
    			'Group.model',
    			'Group.plugin'
    		),
    		'conditions' => array(
	    		'User.id' => $serviceAccountId
	    	),
    		'joins' => array(
    			array(
    				'table' => 'groups',
    				'alias' => 'Group',
    				'type' => 'inner',
    				'conditions' => array(
    					'User.group_id = Group.id'
    				)
    			)
    		),
    		'contain' => false
    	));

    	if(!empty($modelInfo['Group']) && !empty($modelInfo['Group']['model'])){

    		return array('plugin' => (empty($modelInfo['Group']['plugin']) ? '' : $modelInfo['Group']['plugin'] .'.'), 'class' => $modelInfo['Group']['model']);
    	}

    	return false;
    }

    private function __generatePassword($length = 8){
        $conso = array("b","c","d","f","g","h","j","k","l",
        "m","n","p","r","s","t","v","w","x","y","z");
        $vocal = array("a","e","i","o","u");
        $password = "";
        srand ((double)microtime()*1000000);
        $max = $length/2;
        for($i=1; $i<=$max; $i++){
            $password.=$conso[rand(0,19)];
            $password.=$vocal[rand(0,4)];
        }
        return $password;
    }
}
