<?php
App::uses('AppModel', 'Model');
class Log extends AppModel {

	private $debugLogLevel;
	private $errorLogLevel;
	private $criticalLogLevel;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct ( $id, $table, $ds );
		$this->virtualFields = array (
			'user_name' => "Select CONCAT(User.first_name,' ',User.last_name) From users AS User Where User.id = Log.user_id"
		);

		$this->debugLogLevel 	= Configure::read('System.log.level.debug');
		$this->errorLogLevel = Configure::read('System.log.level.error');
		$this->criticalLogLevel = Configure::read('System.log.level.critical');
	}

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
    	'level' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => false,
    			'required'   => true,
    			'last'       => false, // Stop validation after this rule
    			//'on' => 'create', // Limit validation to 'create' or 'update' operations
    		),
    	),
    	'message' => array(
    		'notempty' => array(
    			'rule' => array('notempty'),
    			'message' => 'is not empty',
    			'allowEmpty' => false,
    			'required'   => true,
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
        )
    );

/**
 * CakePHP default save method implementation
 * @param array $data
 * @return boolean
 */
    public function saveLog($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

/**
 * Add a log record with log level
 *
 *  Log level description:
 *  	1, debug:    Put this log as many as we can and this will be used to give IT team feedback about certain procedure
 *      2, info:     Record significant actions
 *      3, warning:  Record incorrect action (including any kind of hacking actions)
 *      4, error:    Record system error which caused by user's incorrect input or wrong action. This kind of error won't need to check. That is normal system feedback.
 *      5, critical: Record system error which caused by network, third party API, and database. This kind of error needs to be investigated ASAP. And create a ticket to IT team about it automatically.
 *
 * @param String $logType
 * @param String $logLevel
 * @param String $logMessage
 * @param bool $isSystemLog
 */
    public function addLogRecord($logType, $logLevel, $logMessage, $isSystemLog = false, $specifyLogUserId = null){

    	$superUser = CakeSession::read('Auth.User');

    	if(empty($superUser) && !empty($specifyLogUserId)){

    		$User = ClassRegistry::init('User');
    		$superUser = $User->browseBy('id', $specifyLogUserId, false);
    		if(isset($superUser['User'])){
    			$superUser = $superUser['User'];
    		}
    	}

    	if((empty($superUser) && !$isSystemLog) || empty($logType) || empty($logLevel) || empty($logMessage)){

    		// Sometimes when client or staff directly call or use CakePHP functions or scripts, the log function cannot gether all information, like user ID, and this will cause log issues.
    		$logType 	 = Configure::read('Config.type.system');
    		$logLevel 	 = Configure::read('System.log.level.error');
    		$logMessage  = __('Cannot save log record. Log info missing. (User: ' .json_encode($superUser) .', Log Type: ' .$logType .', Log Level: ' .$logLevel .', Log Message: ' .$logMessage .')');

    	}else{

    		$logMessage = $this->filterLogMessage($logMessage); // We might disable some logs in the filter

    		// Whether record debug logs or not.
    		if($logLevel == $this->debugLogLevel && empty($superUser['debug_log'])){

    			return false;
    		}

    		if($logLevel == $this->criticalLogLevel && $isSystemLog){

    			$taskStatus = Configure::read('TaskManagement.status');

    			$postData = array(
    				'data' => array(
    					'TaskManagementTask' => array(
    						'parent_id' 				=> null,
    						'web_development_stage_id' 	=> null,
    						'created_by' 				=> null, // set to default in task management
    						'name' 						=> __("Runtime Error"),
    						'description' 				=> $logMessage,
    						'end_time' 					=> date('Y-m-d H:i:s', strtotime("+2 days")),
    						'assignee' 					=> null, // Let staff to pick it up
    						'type' 						=> Configure::read('TaskManagement.type.ticket'),
    						'status' 					=> $taskStatus[0], // NEW
    						'progress' 					=> 0,
    						'priority' 					=> 1,
    						'created_at' 				=> date('Y-m-d H:i:s')
    					)
    				)
    			);

    			App::import('Component','CrazySecurity');
    			$securityComponent = new CrazySecurityComponent(new ComponentCollection);

    			$this->requestAction('/admin/task_management/task_management_tasks/add/', $postData); //TODO try to add this into a runtime error project later - better orgnise tickets
    		}

    	}

    	if(!empty($logMessage)){

    		$logData = array(
    			'Log' => array(
    				'user_id' 	=> $isSystemLog ? Configure::read('System.default.user.id') : ((empty($specifyLogUserId) || !is_numeric($specifyLogUserId)) ? $superUser['id'] : $specifyLogUserId),
    				'type' 		=> $logType,
    				'level' 	=> $logLevel,
    				'message' 	=> $logMessage,
    				'timestamp' => date('Y-m-d H:i:00') // To prevent duplicated logs, we won't log the same thing within 1 min
    			)
    		);

    		try {

    			if(empty($logData['Log']['user_id'])){
    				if(in_array($logLevel, [$this->errorLogLevel, $this->criticalLogLevel])){
    					$logData['Log']['user_id'] = Configure::read('System.default.user.id'); // We have to log the error even the user is not logged in
    				}else{
    					return false; // If not important, we just return a FALSE
    				}
    			}

    			if($this->hasAny($logData['Log'])){
    				return true; // To avoid to log the same thing again and again
    			}

    			return $this->saveLog($logData);

    		} catch (Exception $e) {

    			if(strstr($e->getMessage(), "Integrity constraint violation: 1062 Duplicate entry ") === FALSE){

    				$logType 	 = Configure::read('Config.type.system');
    				$logLevel 	 = $this->criticalLogLevel;
    				$logMessage  = __("Add Log Record Exception: ") .'<br />'.
      							   __("Error Message: ") . $e->getMessage() .'<br />'.
      							   __("Line Number: ") .$e->getLine() .'<br />'.
      							   __("Trace: ") .$e->getTraceAsString() .'<br />'.
      							   __('Log Data: ') .json_encode($logData);
    				$this->addLogRecord($logType, $logLevel, $logMessage, true);

    				return false;

    			}else{

    				// Sometimes the same log message could be logged more than once and this will cause the "Duplicate entry".
    				// Becasue the same log has already been logged, we won't log it again and we also have no reason to log this error in our error log.
    				return true;
    			}
    		}

    	}

    }

/**
 * Handle some special log messages
 * @param String $logMessage
 * @return String
 */
    private function filterLogMessage($logMessage){
    	switch($logMessage){
    		case __('See you next time.'):
    			$logMessage = __('Successfully logged out.');
    			break;
    		case __('Sorry! We cannot send the reset password email for now, please wait for some time and try again.'):
    			$logMessage = ''; // This has been logged in SystemEmailController
    			break;
    		case __('The reset password email has been sent, please check your email and reset the password within 4 hours.'):
    			$logMessage = ''; // This has been logged in SystemEmailController
    			break;
    		case __('We have some difficulties to update your new password. Please wait a while and try again.'):
    			$logMessage = __('Update user password failed.');
    			break;
    	}
    	return $logMessage;
    }
}
?>
