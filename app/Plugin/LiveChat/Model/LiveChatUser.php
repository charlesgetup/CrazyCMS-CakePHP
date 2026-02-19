<?php
App::uses('LiveChatAppModel', 'LiveChat.Model');
App::uses('APIHandler', 'Util');
App::uses('DateTimeHandler', 'Util');

/**
 * User Model
 *
*/
class LiveChatUser extends LiveChatAppModel {

	public $dateTimeHandler = null;

	private $apiHandler = null;

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id,$table,$ds);

		$this->dateTimeHandler = new DateTimeHandler();

		$this->apiHandler = new APIHandler();
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
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'livechat_system_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'live_chat_plan_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className'  => 'User',
			'foreignKey' => 'user_id',
			'dependent'  => true
		),
		'LiveChatPlan' => array(
			'className'  => 'LiveChat.LiveChatPlan',
			'foreignKey' => 'live_chat_plan_id',
			'dependent'  => false
		)
	);

/**
 * Save user steps:
 * 		1, Get the root user details in "users" table, and this root user belongs to "Client" group
 * 		2, Use root user info to build the associated user which is also saved in "users" table but it belongs to the service group, like "Web Development Clients", "SEO Clients" and "Email Marketing Clients"
 * 		3, Check whether the associated user exists. If so, only update service user in the service plugin users table.
 * 		4, If associated user not found, save this associated user in "users" table.
 * 		5, If save action succeed, create service user and save in the service plugin users table.
 * 		   Here, the service plugin users table is "live_chat_users".
 *
 * @param array $data
 * @param array $rootUser
 * @return boolean
 */
	public function saveUser($data, $rootUser = array()){

		$Configuration = ClassRegistry::init('Configuration');
		$User = ClassRegistry::init('User');

		// Fetch the linked root user details to init the new (live chat) associated user
		// Allow inactive user to register live chat service, because while a new user registered, the user has not click the activation link in welcome email.
		// And at this point, the user is inactive and we still allow the user to register the live chat user at that time. And after the register, the super user account
		// And the live chat user account are all inavtive. And the user cannot use any service until the user click the activation link in the welcome email.
		if(empty($rootUser)){
			$associatedUser = $User->find('first', array(
				'conditions' => array(
					'User.id' => $data["LiveChatUser"]["user_id"]
				),
				'contain' => false
			));
			$rootUser = $associatedUser;
		}else{
			$associatedUser = $rootUser;
		}

		// Create associated user using the root user info
		$associatedUser["User"]["parent_id"]         = $associatedUser["User"]["id"];
		$associatedUser["User"]["email_confirm"]     = $associatedUser["User"]["id"] .$data["LiveChatUser"]["live_chat_plan_id"] .time() .$associatedUser["User"]["email"];
		$associatedUser["User"]["email"]             = $associatedUser["User"]["email_confirm"];
		$associatedUser["User"]["password_confirm"]  = $associatedUser["User"]["password"];
		$associatedUser["User"]["group_id"]  		 = Configure::read('LiveChat.client.group.id');
		unset($associatedUser["User"]["id"]);
		if(!isset($associatedUser["User"]["agreement"]) || empty($associatedUser["User"]["agreement"])){
			$associatedUser["User"]["agreement"] = 1; // We have validation method to check agreement while directly registering the user
		}

		$matchedUser = $User->find('first', array(
			'conditions' => array(
				'User.parent_id' => $associatedUser["User"]["parent_id"],
				'User.group_id'  => $associatedUser["User"]["group_id"]
			),
			'recursive' => 0,
			'contain' 	=> array('LiveChatUser')
		));

		// Do not have name when directly register as LiveChat User
		unset($User->validate['first_name']);
		unset($User->validate['last_name']);
		if(!empty($matchedUser)){

			// Double check whether the user exists. If exists, only update user details
			if(isset($data['LiveChatUser']['user_id']) && $data['LiveChatUser']['user_id'] != $matchedUser['LiveChatUser']['user_id']){
				$data['LiveChatUser']['user_id'] = $matchedUser['LiveChatUser']['user_id'];
			}
			$data['LiveChatUser']['id'] = $matchedUser['LiveChatUser']['id'];

			if($this->updateUser($matchedUser['LiveChatUser']['id'], $data)){
				return $matchedUser['LiveChatUser']['id'];
			}else{
				return false;
			}

		}elseif($User->saveUser($associatedUser)){

			// Before create live chat ADMIN user in CrazySoft system, we need to create it in LiveChat system

			$clientAPICodeLength = intval(Configure::read('LiveChat.api.code.length'));
			$clientAPICode		 = substr(str_shuffle(str_repeat($input = '012_345!67+89@0qwe=rtyui]opa$sdfghj{klzxc%vbnmQW^ERTY}UIOPAS*DFG[HJ(KLZXC)VB-NM', ceil($clientAPICodeLength/strlen($input)))), 1, $clientAPICodeLength);

			$queryStr			 = $this->apiHandler->cryptoJsAesEncrypt("api-create-admin", Configure::read('LiveChat.default.passpharse'), Configure::read('LiveChat.default.salt'));
			$url 				 = Configure::read('LiveChat.api.url') ."?{$queryStr}&" .Configure::read('LiveChat.api.code.admin');
			$method 			 = "POST";
			$payload 			 = array(
				'name'	 	=> $rootUser["User"]["first_name"] .' ' .$rootUser["User"]["last_name"],
				'mail' 		=> $rootUser["User"]["email"],
				'password' 	=> $rootUser["User"]["password"],
				'apiCode'	=> $clientAPICode,
				'planSlug'  => $data['LiveChatUser']['live_chat_plan_slug']
			);

			$result 			 = $this->apiHandler->callAPIFunction($url, $method, $payload);

			if($result['success'] === TRUE){

				// Save associated user and if this success, create the live chat ADMIN user
				$associatedUserId = $User->getInsertID();
				$data["LiveChatUser"]["user_id"] 				= $associatedUserId;
				$data["LiveChatUser"]["livechat_api_code"]  	= $clientAPICode;
				$data["LiveChatUser"]["livechat_passphrase"]  	= $result['passphrase'];
				$data["LiveChatUser"]["livechat_salt"]  		= $result['salt'];
				$this->create();

				// Fix saveAll function issue.
				// The saveAll data need to contain the parent record, saveall cannot only add or update a child row, otherwise it will cause database FK constrain failure.
				$saveFuncName = 'saveAll';
				$saveRecordModels = array_keys($data);
				if(count($saveRecordModels) == 1 && $saveRecordModels[0] == "LiveChatUser"){
					$saveFuncName = 'save';
					$data = $data['LiveChatUser'];
				}

				if($this->$saveFuncName($data, array('validate' => 'first'))){
					return $this->getInsertID();
				}else{
					$User->deleteUser($associatedUserId); // Rollback saved associated user
					return false;
				}

			}else{

				if(empty($result['message'])){
					$result['message'] = __('Unknown');
				}elseif (!empty($result['error'])){
					$result['message'] = $result['error'];
				}

				$logType 	 = Configure::read('Config.type.livechat');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("Create LiveChat Admin user failed. (API URL: {$url}, payload: " .json_encode($payload) .", error_message: {$result['message']})");
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				$User->deleteUser($associatedUserId); // Rollback saved associated user
				return false;
			}

        }else{
        	return false;
        }
    }

    public function updateUser($id, $data) {
    	$this->id = $id;
    	$this->contain();

    	// Fix saveAll function issue.
    	// The saveAll data need to contain the parent record, saveall cannot only add or update a child row, otherwise it will cause database FK constrain failure.
    	$updateRecordModels = array_keys($data);
    	if(count($updateRecordModels) == 1 && $updateRecordModels[0] == "LiveChatUser"){
    		$data = $data['LiveChatUser'];
    		return $this->save($data, array('validate' => 'first'));
    	}else{
    		return $this->saveAll($data, array('validate' => 'first'));
    	}
    }

    public function getLiveChatSystemOperatorAmount($liveChatSystemUserAPICode, $passphrase, $salt){

    	if(!empty($liveChatSystemUserAPICode) && !empty($passphrase) && !empty($salt) ){

    		$queryStr			 = $this->apiHandler->cryptoJsAesEncrypt("api-get-operator-amount", $passphrase, $salt);
    		$url 				 = Configure::read('LiveChat.api.url') ."?{$queryStr}&" .$liveChatSystemUserAPICode;
    		$method 			 = "POST";
    		$payload 			 = array();
    		$result 			 = $this->apiHandler->callAPIFunction($url, $method, $payload);

    		if($result['success'] === TRUE){

    			return $result['amount'];

    		}else{

    			if(empty($result['message'])){
    				$result['message'] = __('Unknown');
    			}elseif (!empty($result['error'])){
					$result['message'] = $result['error'];
				}

    			$logType 	 = Configure::read('Config.type.livechat');
    			$logLevel 	 = Configure::read('System.log.level.critical');
    			$logMessage  = __("Get LiveChat operator amount failed. (API URL: {$url}, payload: " .json_encode($payload) .", error_message: {$result['message']})");
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
    		}

    	}

    	return false;
    }

    public function deleteLiveChatSystemUser($liveChatSystemUserAPICode, $passphrase, $salt){
    	if(!empty($liveChatSystemUserAPICode) && !empty($passphrase) && !empty($salt) ){

    		$queryStr			 = $this->apiHandler->cryptoJsAesEncrypt("api-delete-admin", $passphrase, $salt);
    		$url 				 = Configure::read('LiveChat.api.url') ."?{$queryStr}&" .$liveChatSystemUserAPICode;
    		$method 			 = "POST";
    		$payload 			 = array();
    		$result 			 = $this->apiHandler->callAPIFunction($url, $method, $payload);

    		if($result['success'] === TRUE){

    			return true;

    		}else{

    			if(empty($result['message'])){
    				$result['message'] = __('Unknown');
    			}elseif (!empty($result['error'])){
					$result['message'] = $result['error'];
				}

    			$logType 	 = Configure::read('Config.type.livechat');
    			$logLevel 	 = Configure::read('System.log.level.critical');
    			$logMessage  = __("Delete admin user failed. (API URL: {$url}, payload: " .json_encode($payload) .", error_message: {$result['message']})");
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
    		}

    	}else{

    		return false;
    	}

    	return false;
    }

    public function generateDownloadableChatHistory($liveChatSystemUserAPICode){
    	if(!empty($liveChatSystemUserAPICode)){

    		$liveChatUser = $this->browseBy('livechat_api_code', $liveChatSystemUserAPICode, array('LiveChatPlan'));

    		$queryStr			 = $this->apiHandler->cryptoJsAesEncrypt("api-download-chat-history", $liveChatUser['LiveChatUser']['livechat_passphrase'], $liveChatUser['LiveChatUser']['livechat_salt']);
    		$url 				 = Configure::read('LiveChat.api.url') ."?{$queryStr}&" .$liveChatSystemUserAPICode;
    		$method 			 = "POST";
    		$payload 			 = ($liveChatUser['LiveChatPlan']['slug'] == 'starter') ? array('from' => date('Y-m-d', strtotime('-60 days')), 'to' => date('Y-m-d')) : array();
    		$result 			 = $this->apiHandler->callAPIFunction($url, $method, $payload);

    		return $result;

    	}else{

    		return array('status' => Configure::read('System.variable.warning'), 'message' => __('Your live chat service account was not set up correctly. Please create a ticket to report this issue.'));
    	}
    }

    public function getUserList($superUserId = null){
    	$userList = $this->find('all', array(
    		'fields' => array(
    			'LiveChatUser.id',
    			'SuperUser.first_name',
    			'SuperUser.last_name',
    		),
    		'joins' => array(
    			array(
    				'table' => 'users',
    				'alias' => 'SuperUser',
    				'type' => 'inner',
    				'conditions' => array(
    					'SuperUser.id = LiveChatUser.user_id',
    					'SuperUser.active = 1',
    					(empty($superUserId) ? "" : "SuperUser.id = {$superUserId}")
    				)
    			)
    		),
    	));

    	//TODO create component to do this manual concat job (find list ?)
    	$temp = array();
    	foreach($userList as $u){
    		$temp[$u['LiveChatUser']['id']] = $u['SuperUser']['first_name'] .' ' .$u['SuperUser']['last_name'];
    	}
    	$userList = $temp;

    	return $userList;
    }

    /*
     * Notice: No refund will be made for client close/downgrade account before the current paymemt period ends.
     *
     * Based on the note, when the user changes plan, the current plan will be ended and a new plan will be assigned to the client. All client's data will be kept and transfer to the new plan.
     * Only last_pay_date and next_pay_date will be updated.
     */
    public function switchPlan($userId, $newPlanId, $allPlans, $tempInvoiceId = null){
    	if(empty($userId) || empty($newPlanId) || empty($allPlans)){

    		$logType 	 = Configure::read('Config.type.livechat');
    		$logLevel 	 = Configure::read('System.log.level.debug');
    		$logMessage  = __('User (#' .$userId .') switch plan with empty parameters. (Passed user ID: ' .$userId .', new plan ID: ' .$newPlanId .')');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		return false;
    	}

    	$liveChatUser = $this->browseBy("user_id", $userId, false);
    	$oldPlanId = $liveChatUser['LiveChatUser']['live_chat_plan_id'];
    	if(empty($oldPlanId)){
    		$ignoreActiveStatus = true;
    		$liveChatAccountId = $this->superUserIdToLiveChatUserAccountId($userId, $ignoreActiveStatus);
    		$liveChatUser = $this->browseBy("user_id", $liveChatAccountId, false);
    		$oldPlanId = $liveChatUser['LiveChatUser']['live_chat_plan_id'];
    	}

    	$oldPlan = null;
    	$newPlan = null;
    	foreach($allPlans as $p){
    		if($p['LiveChatPlan']['id'] == $oldPlanId){
    			$oldPlan = $p;
    		}
    		if ($p['LiveChatPlan']['id'] == $newPlanId){
    			$newPlan = $p;
    		}
    	}

    	// The real payment frequency is sent to payment gateway directly. We will update the payment frequency and recurring amount, calculate recurring discount together at payment gateway
    	$paymentFrequencyMonthly 	= Configure::read('Payment.pay.cycle.monthly');
    	$paymentFrequencyAnnually 	= Configure::read('Payment.pay.cycle.annually');
    	$paymentFrequency 			= $paymentFrequencyMonthly;
    	$nextPayDate 				= $this->dateTimeHandler->getSameDayInFollowingMonth(date('Y-m-d')); // PayPal set the same day in next month as next pay day. Because each month has different days, if client paid bill close to the end of month, we need to update this field when we get exact next pay day in PayPal API return data.
    	$paymentCode 				= Configure::read('Payment.code.live_chat.monthly_recurring');
    	$paymentPeriodMonths 		= 1; // How many month is included in the payment cycle. We need this because our payment amount unit is MONTH
    	$paymentCode = Configure::read('Payment.code.live_chat.monthly_recurring');

    	// Determine how to charge the client
    	$usedPercentage = 0;
    	if(!empty($oldPlan)){

    		$currentPlan = $oldPlan;
    		$usedPercentage = $this->calculateUsedPercentage($liveChatUser, $currentPlan);
    	}

    	if(!empty($oldPlan) && !empty($newPlan)){

    		$liveChatUser['LiveChatUser']['payment_cycle'] = $paymentFrequency;
    		$liveChatUser['LiveChatUser']['last_pay_date'] = date('Y-m-d');
    		$liveChatUser['LiveChatUser']['next_pay_date'] = $nextPayDate;

    	}else{

    		$logType 	 = Configure::read('Config.type.livechat');
			$logLevel 	 = Configure::read('System.log.level.error');
			$logMessage  = __('Cannot find live chat plan details during plan switch payment process.');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		return false;
    	}

    	$liveChatUser['LiveChatUser']['live_chat_plan_id'] = $newPlanId;

    	$payForOperatorAmount = $liveChatUser['LiveChatUser']['operator_amount'];
    	if(!empty($liveChatUser['LiveChatUser']['chargeable_operator_amount'])){

    		$discountAmount = 0;
    		if(!empty($oldPlan) && $usedPercentage < 1){

    			$previousPaymentPeriodMonths = 1;
    			if($oldPlan['LiveChatPlan']['payment_cycle'] == $paymentFrequencyAnnually){
    				$previousPaymentPeriodMonths = 12;
    			}
    			$discountAmount = $oldPlan['LiveChatPlan']['price'] * $previousPaymentPeriodMonths * $liveChatUser['LiveChatUser']['operator_amount'] * (1 - $usedPercentage);
    		}

    		// We always charge all operator accounts at the same time. We will calculate previous usage and give discount when downgrade plan or reduce operator amounts within the current payment cycle
    		$liveChatUser['LiveChatUser']['operator_amount'] += $liveChatUser['LiveChatUser']['chargeable_operator_amount'];
    		if($liveChatUser['LiveChatUser']['operator_amount'] < 0){
    			$liveChatUser['LiveChatUser']['operator_amount'] = 0;
    		}
    		$liveChatUser['LiveChatUser']['chargeable_operator_amount'] = 0;
    	}

    	if(empty($liveChatUser['LiveChatUser']['operator_amount'])){
    		return false;
    	}

    	$recurringAmount = $newPlan['LiveChatPlan']['price'] * $paymentPeriodMonths * $liveChatUser['LiveChatUser']['operator_amount'];
    	$needToPayAmount = $recurringAmount - $discountAmount;
    	if($needToPayAmount < 0){
    		$needToPayAmount = 0; //TODO need to see whether PayPal accepts this. If not, find out the min charge amount and charge it
    	}

    	$receipt = array(
    		'PaymentTempInvoice' => array(
    			'user_id'				=> $userId,
    			'is_auto_created' 		=> 1,
    			'purchase_code' 		=> $paymentCode,
    			'payment_cycle'			=> $liveChatUser['LiveChatUser']['payment_cycle'],
    			'amount'				=> $needToPayAmount,
    			'recurring_amount'		=> $recurringAmount,
    			'recurring_plan_name'	=> __("Live Chat") ." " .$newPlan['LiveChatPlan']['name'],
    			'content'				=> $newPlan['LiveChatPlan']['description'],
    			'created_by'			=> $userId,
    			'created' 				=> date('Y-m-d H:i:s'),
    			'due_date'				=> date('Y-m-d'),
    			'related_update_data' 	=> serialize(array('plugin' => 'LiveChat', 'class' => 'LiveChatUser', 'id' => $liveChatUser['LiveChatUser']['id'], 'data' => $liveChatUser))
    		)
    	);
    	if(!empty($tempInvoiceId)){
    		$receipt['PaymentTempInvoice']['id'] = $tempInvoiceId;
    	}

    	$TempInvoice = ClassRegistry::init("Payment.PaymentTempInvoice");

    	return empty($tempInvoiceId) ? $TempInvoice->saveTempInvoice($receipt) : $TempInvoice->updateTempInvoice($tempInvoiceId, $receipt);

    }

    public function calculateUsedPercentage($liveChatUser, $currentPlan){

    	// This calculation method can only be used against the paid plan user
    	if(
	    	empty($liveChatUser['LiveChatUser']['payment_cycle']) ||
	    	empty($liveChatUser['LiveChatUser']['last_pay_date']) ||
	    	empty($liveChatUser['LiveChatUser']['next_pay_date']) ||
	    	empty($liveChatUser['LiveChatUser']['live_chat_plan_id']) ||
	    	$currentPlan['LiveChatPlan']['id'] != $liveChatUser['LiveChatUser']['live_chat_plan_id']
    	){
    		$logType 	 = Configure::read('Config.type.livechat');
    		$logLevel 	 = Configure::read('System.log.level.error');
    		$logMessage  = __('Live Chat User data is corrupted while calculating usage.');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		$logType 	 = Configure::read('Config.type.livechat');
    		$logLevel 	 = Configure::read('System.log.level.critical');
    		$logMessage  = __('Live Chat User ((Super User ID: ' .$userId .')) data corrupted while calculating usage, "user_id" is missing.');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    		return false;
    	}

    	// Calculated by passed days
    	if(strtotime($liveChatUser['LiveChatUser']['next_pay_date']) <= time()){
    		return 1; // If the plan has expired, then the used percetage will be 100%, no calculation needed.
    	}

    	$nextPayDateSec = strtotime($liveChatUser['LiveChatUser']['next_pay_date']);
    	$lastPayDateSec = strtotime($liveChatUser['LiveChatUser']['last_pay_date']);
    	$totalPaymentPeriodDays = floor(($nextPayDateSec - $lastPayDateSec) / 3600 / 24);
    	switch($liveChatUser['LiveChatUser']['payment_cycle']){
    		case Configure::read('Payment.pay.cycle.monthly'):
    			$totalPaymentPeriodMonth = 1;
    			break;
    		case Configure::read('Payment.pay.cycle.quarterly'):
    			$totalPaymentPeriodMonth = 3;
    			break;
    		case Configure::read('Payment.pay.cycle.half_year'):
    			$totalPaymentPeriodMonth = 6;
    			break;
    		case Configure::read('Payment.pay.cycle.annually'):
    			$totalPaymentPeriodMonth = 12;
    			break;
    	}
    	if(empty($totalPaymentPeriodDays)){

    		$logType 	 = Configure::read('Config.type.livechat');
    		$logLevel 	 = Configure::read('System.log.level.error');
    		$logMessage  = __('Cannot calculate live chat plan usage based on invalid payment cycle.');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		$logType 	 = Configure::read('Config.type.livechat');
    		$logLevel 	 = Configure::read('System.log.level.critical');
    		$logMessage  = __('We cannot calculate live chat plan usage for user (#' .$userId .'). (Payment Cycle: ' .$liveChatUser['LiveChatUser']['payment_cycle'] .', Last Pay Date: ' .$liveChatUser['LiveChatUser']['last_pay_date'] .', Next Pay Date: ' .$liveChatUser['LiveChatUser']['next_pay_date'] .').');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    		return false; // Payment cycle has to be one of the pre-defined payment cycle
    	}
    	$passedDays = floor((time() - $lastPayDateSec) / 3600 / 24);
    	if(empty($passedDays)){
    		$usedPercentage = 0;
    	}else{
    		$usedPercentage = round(($passedDays / $totalPaymentPeriodDays), 2, PHP_ROUND_HALF_UP);
    	}

    	return $usedPercentage;
    }
}