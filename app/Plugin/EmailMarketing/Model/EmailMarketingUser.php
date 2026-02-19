<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
App::uses('DateTimeHandler', 'Util');

/**
 * User Model
 *
 * @property Group $Group
 * @property Address $Address
 */
class EmailMarketingUser extends EmailMarketingAppModel {

	public $dateTimeHandler = null;

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id,$table,$ds);

        $this->dateTimeHandler = new DateTimeHandler();
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
        'email_marketing_plan_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
		'free_emails' => array(
				'numeric' => array(
						'rule' => array('numeric'),
						//'message' => 'Your custom message here',
						//'allowEmpty' => false,
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
        'EmailMarketingPlan' => array(
            'className'  => 'EmailMarketing.EmailMarketingPlan',
            'foreignKey' => 'email_marketing_plan_id',
            'dependent'  => false
        )
	);

    public $hasMany = array(
        'EmailMarketingMailingList' => array(
            'className'    => 'EmailMarketing.EmailMarketingMailingList',
            'foreignKey'   => 'email_marketing_user_id',
            'dependent'    => true
        ),
        'EmailMarketingBlacklistedSubscriber' => array(
            'className'    => 'EmailMarketing.EmailMarketingBlacklistedSubscriber',
            'foreignKey'   => 'email_marketing_user_id',
            'dependent'    => true
        ),
        'EmailMarketingCampaign' => array(
            'className'    => 'EmailMarketing.EmailMarketingCampaign',
            'foreignKey'   => 'email_marketing_user_id',
            'dependent'    => true
        ),
        'EmailMarketingPurchasedTemplate' => array(
            'className'    => 'EmailMarketing.EmailMarketingPurchasedTemplate',
            'foreignKey'   => 'email_marketing_user_id',
            'dependent'    => true
        ),
        'EmailMarketingTemplate' => array(
            'className'    => 'EmailMarketing.EmailMarketingTemplate',
            'foreignKey'   => 'email_marketing_user_id',
            'dependent'    => true
        )
    );

    public function getUserBySuperId($superUserId, $contain = false){

    	if(empty($superUserId)){

    		return false;
    	}

    	$emailMarketingUser = $this->find('first', array(
    		'conditions' => array(
    			'SuperUserParent.id' => $superUserId
	    	),
    		'joins' => array(
    			array(
    				'table' => 'users',
    				'alias' => 'SuperUserChild',
    				'type' => 'inner',
    				'conditions' => array(
    					'SuperUserChild.id = EmailMarketingUser.user_id',
    					//'SuperUserChild.active = 1', //The activation is required at the dashboard, if the service account is not activated
    				)
    			),
    			array(
    				'table' => 'users',
    				'alias' => 'SuperUserParent',
    				'type' => 'inner',
    				'conditions' => array(
    					'SuperUserParent.id = SuperUserChild.parent_id',
    					//'SuperUserParent.active = 1',
    				)
    			)
	    	),
    		'contain' => $contain
    	));

    	return $emailMarketingUser;
    }

/**
 * Save user steps:
 * 		1, Get the root user details in "users" table, and this root user belongs to "Client" group
 * 		2, Use root user info to build the associated user which is also saved in "users" table but it belongs to the service group, like "Web Development Clients", "SEO Clients" and "Email Marketing Clients"
 * 		3, Check whether the associated user exists. If so, only update service user in the service plugin users table.
 * 		4, If associated user not found, save this associated user in "users" table.
 * 		5, If save action succeed, create service user and save in the service plugin users table.
 * 		   Here, the service plugin users table is "email_marketing_users".
 *
 * @param array $data
 * @param array $rootUser
 * @return boolean
 */
    public function saveUser($data, $rootUser = array()){

    	$Configuration = ClassRegistry::init('Configuration');
    	$User = ClassRegistry::init('User');

    	// Fetch the linked root user details to init the new (email marketing) associated user
    	// Allow inactive user to register email marketing service, because while a new user registered, the user has not click the activation link in welcome email.
    	// And at this point, the user is inactive and we still allow the user to register the email marketing user at that time. And after the register, the super user account
    	// And the email marketing user account are all inavtive. And the user cannot use any service until the user click the activation link in the welcome email.
    	if(empty($rootUser)){
    		$associatedUser = $User->find('first', array(
    			'conditions' => array(
    				'User.id' => $data["EmailMarketingUser"]["user_id"]
    			),
    			'contain' => false
    		));
    	}else{
    		$associatedUser = $rootUser;
    	}

        // Create associated user using the root user info
        $associatedUser["User"]["parent_id"]         = $associatedUser["User"]["id"];
        $associatedUser["User"]["email_confirm"]     = $associatedUser["User"]["id"] .$data["EmailMarketingUser"]["email_marketing_plan_id"] .time() .$associatedUser["User"]["email"]; // Add time to make the email unique
        $associatedUser["User"]["email"]             = $associatedUser["User"]["email_confirm"];
        $associatedUser["User"]["password_confirm"]  = $associatedUser["User"]["password"];
        $associatedUser["User"]["group_id"]  		 = Configure::read('EmailMarketing.client.group.id');
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
        	'contain' 	=> array('EmailMarketingUser')
       	));

        // Do not have name when directly register as EmailMarketing User
        unset($User->validate['first_name']);
        unset($User->validate['last_name']);
        if(!empty($matchedUser)){

        	// Double check whether the user exists. If exists, only update user details
        	if(isset($data['EmailMarketingUser']['user_id']) && $data['EmailMarketingUser']['user_id'] != $matchedUser['EmailMarketingUser']['user_id']){
        		$data['EmailMarketingUser']['user_id'] = $matchedUser['EmailMarketingUser']['user_id'];
        	}
        	$data['EmailMarketingUser']['id'] = $matchedUser['EmailMarketingUser']['id'];

        	if($this->updateUser($matchedUser['EmailMarketingUser']['id'], $data)){
        		return $matchedUser['EmailMarketingUser']['id'];
        	}else{
        		return false;
        	}

        }elseif($User->saveUser($associatedUser)){

        	// Save associated user and if this success, create the email marketing user
        	$associatedUserId = $User->getInsertID();
            $data["EmailMarketingUser"]["user_id"] = $associatedUserId;
            $this->create();

            // Fix saveAll function issue.
            // The saveAll data need to contain the parent record, saveall cannot only add or update a child row, otherwise it will cause database FK constrain failure.
            $saveFuncName = 'saveAll';
            $saveRecordModels = array_keys($data);
            if(count($saveRecordModels) == 1 && $saveRecordModels[0] == "EmailMarketingUser"){
            	$saveFuncName = 'save';
            	$data = $data['EmailMarketingUser'];
            }

            if($this->$saveFuncName($data, array('validate' => 'first'))){

            	/*
            	 * If the user is set up successfully, we need to set up more user settings
            	 */

            	// Set user configurations
            	// Comment: 06/03/2018. We wil use our own bounce mail box to handle this and the bounce mail box will be added to the mail header and it's transparent to the users.
//             	$Configuration = ClassRegistry::init('Configuration');
//             	$userConfigData = array(
//             		array(
// 	            		'Configuration' => array(
// 	            			'user_id' => $associatedUser["User"]["parent_id"],
// 	            			'type'	  => Configure::read('Config.type.emailmarketing'),
// 	            			'name'	  => "BounceToMailBox",
// 			            )
// 	            	),
//             		array(
//             			'Configuration' => array(
//             				'user_id' => $associatedUser["User"]["parent_id"],
//             				'type'	  => Configure::read('Config.type.emailmarketing'),
//             				'name'	  => "BounceToMailBoxUsername",
//             			)
//             		),
//             		array(
//             			'Configuration' => array(
//             				'user_id' => $associatedUser["User"]["parent_id"],
//             				'type'	  => Configure::read('Config.type.emailmarketing'),
//             				'name'	  => "BounceToMailBoxPassword",
//             			)
//             		)
//             	);
//             	$Configuration->saveConfiguration($userConfigData);

                return $this->getInsertID();
            }else{
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
        if(count($updateRecordModels) == 1 && $updateRecordModels[0] == "EmailMarketingUser"){
        	$data = $data['EmailMarketingUser'];
        	return $this->save($data, array('validate' => 'first'));
        }else{
        	return $this->saveAll($data, array('validate' => 'first'));
        }
    }

    public function deleteUser($id){
    	//TODO test delete email marketing user can delete all the related records, such as campaign, mailing llist and so on (test this method in model unit test)

    	// When delete EmailMarketingUser account, we didn't call this method. The super User delete method is called. And that method can delete all related model records
		return $this->delete($id, true);
    }

    public function getUserList($superUserId = null, $conditions = array()){

    	$queryConditions = array(

            'fields' => array(
                'EmailMarketingUser.id',
                'SuperUser.first_name',
                'SuperUser.last_name',
            	'SuperParentUser.email',
            ),
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'SuperUser',
                    'type' => 'inner',
                    'conditions' => array(
                        'SuperUser.id = EmailMarketingUser.user_id',
                        'SuperUser.active = 1',
                    	(empty($superUserId) ? "" : "SuperUser.id = {$superUserId}")
                    )
                ),
                array(
                	'table' => 'users',
                	'alias' => 'SuperParentUser',
                	'type' => 'inner',
                	'conditions' => array(
                		'SuperParentUser.id = SuperUser.parent_id',
                		'SuperParentUser.active = 1'
                	)
                )
            ),
        );

        if(!empty($conditions)){

        	$queryConditions['conditions'] = $conditions;
        }

        $userList = $this->find('all', $queryConditions);

        //TODO create component to do this manual concat job (find list ?)
        $temp = array();
        foreach($userList as $u){
            $temp[$u['EmailMarketingUser']['id']] = $u['SuperUser']['first_name'] .' ' .$u['SuperUser']['last_name'];
        }
        $userList = $temp;

        return $userList;
    }

    public function recordUsage ($superUserId, $usageAmount) {
		$userDetail = $this->find('first', array(
			'conditions' => array('user_id' => $superUserId),
			'recursive'  => 0,
			'contain' 	 => array('EmailMarketingPlan')
		));

		$userDetail['EmailMarketingUser']['total_sent_email_amount'] += $usageAmount;

		// 1, If user has free emails, deduct free emails first
		if(isset($userDetail['EmailMarketingUser']['free_emails']) && is_numeric($userDetail['EmailMarketingUser']['free_emails'])){
			$userDetail['EmailMarketingUser']['free_emails'] -= $usageAmount;
			$usageAmount = 0;
		}

		// 2, If free email cannot cover the usage, check monthly limit and record the usage at "used_email_count" field.
		//
		// Note: no matter which payment cycle the client choose, usage is calculated MONTHLY.
		// For example, Jack has a "Starter Plan" and payment cycle is QUARTERLY and "Starter Plan" monthly limit is 10000. And Jack has 100 free emails when he purchased the plan.
		// Now in the first month, Jack sent 12000 emails. So the exceeded email amount is 12000 - 100 - 10000 = 1900, and those email needs to be paid. And in the next (2nd) month (30 days),
		// Jack will have a fresh start without any payment.
		//
		if($userDetail['EmailMarketingUser']['free_emails'] <= 0){
			$usageAmount = abs($userDetail['EmailMarketingUser']['free_emails']);
			$userDetail['EmailMarketingUser']['free_emails'] = 0;
			$usageAmount = $usageAmount - $userDetail['EmailMarketingPlan']['email_limit'];

			// Notice: "used_email_count" field record how many email client sent beyond the free email amount, even if the sent email amount exceed the monthly limit
			// Keep the "if" condition here is only to tell how to check the client usage is under limit or not. And in future, we may insert other logic here.
			if($usageAmount < 0){
				// Still within the limit
				$userDetail['EmailMarketingUser']['used_email_count'] += $userDetail['EmailMarketingPlan']['email_limit'] + $usageAmount;
			}else{
				// Reached limit
				$userDetail['EmailMarketingUser']['used_email_count'] += $userDetail['EmailMarketingPlan']['email_limit'] + $usageAmount;
			}
		}

		// 3, If the usage is over the monthly limit, use prepaid credit (deduct prepaid credit according to the email sending unit price).
		if($usageAmount > 0){
			if(empty($userDetail['EmailMarketingUser']['prepaid_amount'])){
				$userDetail['EmailMarketingUser']['prepaid_amount'] = 0;
			}

			$userDetail['EmailMarketingUser']['prepaid_amount'] -= $userDetail['EmailMarketingPlan']['unit_price'] * $usageAmount;
			if($userDetail['EmailMarketingUser']['prepaid_amount'] < 0){
				$this->updateUser($userDetail['EmailMarketingUser']['id'], $userDetail);

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('User (#' .$userDetail['EmailMarketingUser']['user_id'] .') email marketing prepaid amount is negative value.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				return null;
			}
		}

		return $this->updateUser($userDetail['EmailMarketingUser']['id'], $userDetail);
    }

    public function exceededLimit ($userDetails, $sendingAmount = 0) {
		$Plan = ClassRegistry::init("EmailMarketingPlan");
		$planDetails = $Plan->find("first", array(
			"conditions" => array(
				"EmailMarketingPlan.id" => $userDetails['email_marketing_plan_id']
			),
			'recursive' => 0,
			'contain' => false
		));

		$isOutOfLimit = false;

		$limit = $planDetails['EmailMarketingPlan']['email_limit'];
		if($userDetails['prepaid_amount'] > 0 && $planDetails['EmailMarketingPlan']['unit_price'] > 0){
			$limit += floor($userDetails['prepaid_amount'] / $planDetails['EmailMarketingPlan']['unit_price']);
		}

		$isOutOfLimit = ($limit + intval($userDetails['free_emails'])) <= ($userDetails['used_email_count'] + $sendingAmount);

		return $isOutOfLimit;
    }

    // During the process, we not only update plan ID, we may need to update email_sender_limit, payment cycle, last_pay_date and next_pay_date but also
    // clear the usage, like used_email_count, total_sent_email_amount and re-calculate prepaid_amount.
    // And we will check benefits field, like free_emails. If new plan can give new benefits, the benefits can be added together, and usually these benefits is a one-time-benefit, it is not given
    // periodically.
    //
    // Most important note: How to calculate switch plan cost. !!!
    // Switch plan can only be one of the following 3 scenarios.
    // 1, prepaid plan to paid plan
    // 		In this scenario, switch plan cost will be the new plan fee minus prepaid amount. And if client has some prepaid amount left after purchase, then the prepaid amount left will still be
    //		there and it will be used when the client sent emails exceed the plan limit.
    // 2, paid plan to prepaid plan
    //		In this scenario, switch plan cost will be none if the client's paid plan has already expired, but we can still ask client to add some fund to prepaid amount. And if the client did
    //		this during the plan period, we will follow the rules in `How to calculate client credits` below to get the client credits. If the client has credits left,
    //		the credits will be added to prepaid amount.
    // 3, having a paid plan and want to upgrade/downgrade the plan
    //		In this scenario, we check whether the client is still in the curent plan period. If so, we will follow the rules in `How to calculate client credits` below to get the client credits.
    //		If the client has credits, the credits will be used to deduct the new plan fee, and if the credits are greater than the new plan fee, the credits left will be added to prepaid amount.
    //
    // return value: true => switch plan is done, no further payment is needed; false => switch plan process failed; integer => (temp) invoice ID, a switch fee needs to be paid
    public function switchPlan($userId, $newPlanId, $allPlans, $tempInvoiceId = null){
    	if(empty($userId) || empty($newPlanId) || empty($allPlans)){

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.debug');
			$logMessage  = __('User (#' .$userId .') switch plan with empty parameters. (Passed user ID: ' .$userId .', new plan ID: ' .$newPlanId .')');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		return false;
    	}

    	$emailMarketingUser = $this->browseBy("user_id", $userId, false);
    	$oldPlanId = $emailMarketingUser['EmailMarketingUser']['email_marketing_plan_id'];
    	if(empty($oldPlanId)){
    		$ignoreActiveStatus = true;
    		$emailMarketingAccountId = $this->superUserIdToEmailMarketingUserAccountId($userId, $ignoreActiveStatus);
    		$emailMarketingUser = $this->browseBy("user_id", $emailMarketingAccountId, false);
    		$oldPlanId = $emailMarketingUser['EmailMarketingUser']['email_marketing_plan_id'];
    	}

    	$oldPlan = null;
    	$newPlan = null;
    	foreach($allPlans as $p){
    		if($p['EmailMarketingPlan']['id'] == $oldPlanId){
    			$oldPlan = $p;
    		}
    		if ($p['EmailMarketingPlan']['id'] == $newPlanId){
    			$newPlan = $p;
    		}
    	}

    	// Determine how to charge the client
    	$needToPay 				= false;

    	// The real payment frequency is sent to payment gateway directly. We will update the payment frequency and recurring amount, calculate recurring discount together at payment gateway
    	$paymentFrequency 		= Configure::read('Payment.pay.cycle.monthly');
    	$nextPayDate 			= $this->dateTimeHandler->getSameDayInFollowingMonth(date('Y-m-d')); // PayPal set the same day in next month as next pay day. Because each month has different days, if client paid bill close to the end of month, we need to update this field when we get exact next pay day in PayPal API return data.
    	$paymentCode 			= Configure::read('Payment.code.email_marketing.monthly_recurring');
    	$paymentPeriodMonths 	= 1; // How many month is included in the payment cycle. We need this because our payment amount unit is MONTH
    	$paymentCode 			= Configure::read('Payment.code.email_marketing.monthly_recurring');

    	if(!empty($oldPlan) && !empty($newPlan)){
    		if($oldPlan['EmailMarketingPlan']['pay_per_email'] != $newPlan['EmailMarketingPlan']['pay_per_email']){
    			if(empty($newPlan['EmailMarketingPlan']['pay_per_email'])){
    				// 1, Prepaid plan to paid plan
    				$emailMarketingUser['EmailMarketingUser']['payment_cycle'] = $paymentFrequency;
    				$emailMarketingUser['EmailMarketingUser']['last_pay_date'] = date('Y-m-d');
    				$emailMarketingUser['EmailMarketingUser']['next_pay_date'] = $nextPayDate;
    				$needToPayAmount 										   = $paymentPeriodMonths * $newPlan['EmailMarketingPlan']['total_price'];
    				if($emailMarketingUser['EmailMarketingUser']['prepaid_amount'] >= $needToPayAmount){
    					$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] -= $needToPayAmount;
    				}else{
    					$needToPay = true;
    				}
    			}else{
    				// 2, Paid plan to prepaid plan
    				if(!empty($emailMarketingUser['EmailMarketingUser']['next_pay_date']) && strtotime($emailMarketingUser['EmailMarketingUser']['next_pay_date']) > time()){
    					// Switch before the current payment cycle ends, transfer the money left to prepaid amount
    					$currentPlan = $oldPlan;
    					$finalUsedPercentage = $this->calculateUsedPercentage($emailMarketingUser, $currentPlan);
    					if(!$finalUsedPercentage && !is_numeric($finalUsedPercentage)){

    						$logType 	 = Configure::read('Config.type.emailmarketing');
    						$logLevel 	 = Configure::read('System.log.level.critical');
    						$logMessage  = __('User (#' .$userId .') switch plan, but we cannot calculate the usage percentage. (#2 Calculated percentage: ' .$finalUsedPercentage .')');
    						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    						return false; // Terminate the process if we cannot calculate the usage percentage
    					}
    					$leftMoney = $this->__calculateCreditLeftAfterSwitchPlan($emailMarketingUser, $finalUsedPercentage, $currentPlan);
    					if(!is_numeric($leftMoney)){

    						$logType 	 = Configure::read('Config.type.emailmarketing');
    						$logLevel 	 = Configure::read('System.log.level.critical');
    						$logMessage  = __('User (#' .$userId .') switch plan, but we cannot get left credit. (#2 Left credit: ' .$leftMoney .')');
    						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    						return false; // Terminate the process if we cannot get left credit
    					}
    					$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] += ($leftMoney > 0) ? $leftMoney : 0;
    				}else{
    					// Switch after the payment cycle and before the new payment comes (current plan expired), do nothing
    					// Can give client an advice of adding the prepaid amount now
    				}
    				$emailMarketingUser['EmailMarketingUser']['payment_cycle'] = Configure::read('Payment.pay.cycle.manual');
    				$emailMarketingUser['EmailMarketingUser']['last_pay_date'] = null;
    				$emailMarketingUser['EmailMarketingUser']['next_pay_date'] = null;
    			}
    		}else{
    			// 3, Paid plan to paid plan
    			if(!empty($emailMarketingUser['EmailMarketingUser']['next_pay_date']) && strtotime($emailMarketingUser['EmailMarketingUser']['next_pay_date']) > time()){
	    			$currentPlan = $oldPlan;
    				$finalUsedPercentage = $this->calculateUsedPercentage($emailMarketingUser, $currentPlan);
    				if(!$finalUsedPercentage && !is_numeric($finalUsedPercentage)){

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$userId .') switch plan, but we cannot calculate the usage percentage. (#3 Calculated percentage: ' .$finalUsedPercentage .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					return false; // Terminate the process if we cannot calculate the usage percentage
    				}
    				$leftMoney = $this->__calculateCreditLeftAfterSwitchPlan($emailMarketingUser, $finalUsedPercentage, $currentPlan);
    				if(!is_numeric($leftMoney)){

    					$logType 	 = Configure::read('Config.type.emailmarketing');
    					$logLevel 	 = Configure::read('System.log.level.critical');
    					$logMessage  = __('User (#' .$userId .') switch plan, but we cannot get left credit. (#3 Left credit: ' .$leftMoney .')');
    					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    					return false; // Terminate the process if we cannot get left credit
    				}
	    			$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] += ($leftMoney > 0) ? $leftMoney : 0;
	    			$needToPayAmount 											 = $paymentPeriodMonths * $newPlan['EmailMarketingPlan']['total_price'];
	    			if($emailMarketingUser['EmailMarketingUser']['prepaid_amount'] >= $needToPayAmount){
	    				$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] -= $needToPayAmount;
	    			}else{
	    				$needToPay = true;
	    			}
    			}else{
    				$needToPay = true;
    			}
    			$emailMarketingUser['EmailMarketingUser']['payment_cycle'] = $paymentFrequency;
    			$emailMarketingUser['EmailMarketingUser']['last_pay_date'] = date('Y-m-d');
    			$emailMarketingUser['EmailMarketingUser']['next_pay_date'] = $nextPayDate;
    		}

    	}else{

    		$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.error');
			$logMessage  = __('Cannot find email marketing plan details during plan switch process.');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		return false;
    	}

    	// Clear usage
    	$emailMarketingUser['EmailMarketingUser']['used_email_count'] 			= 0;
    	$emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'] 	= 0;
    	$emailMarketingUser['EmailMarketingUser']['email_marketing_plan_id'] 	= $newPlanId;

    	$normalPayAmount = $newPlan['EmailMarketingPlan']['total_price'] * $paymentPeriodMonths;

    	if($needToPay){

    		$needToPayAmount = $normalPayAmount - $emailMarketingUser['EmailMarketingUser']['prepaid_amount'];
    		if($needToPayAmount > 0){

    			if($emailMarketingUser['EmailMarketingUser']['prepaid_amount'] > 0){
    				$deductMsg = '<p><strong style="color: red;">' .__('Deduct $' .$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] .' (prepaid credit) from the original price.') .'</strong></p>';
    			}
    			$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] = 0; // Prepaid amount is used to deduct the payment amount

	    		$receipt = array(
	    			'PaymentTempInvoice' => array(
	    				'user_id'				=> $userId,
	    				'is_auto_created' 		=> 1,
	    				'purchase_code' 		=> $paymentCode,
	    				'amount'				=> $needToPayAmount,
	    				'content'				=> $newPlan['EmailMarketingPlan']['description'] .((isset($deductMsg)) ? $deductMsg : ""),
	    				'created_by'			=> $userId,
	    				'created' 				=> date('Y-m-d H:i:s'),
	    				'due_date'				=> date('Y-m-d'),
	    				'related_update_data' 	=> serialize(array('plugin' => 'EmailMarketing', 'class' => 'EmailMarketingUser', 'id' => $emailMarketingUser['EmailMarketingUser']['id'], 'data' => $emailMarketingUser))
	    			)
	    		);
	    		if(!empty($tempInvoiceId)){
	    			$receipt['PaymentTempInvoice']['id'] = $tempInvoiceId;
	    		}
	    		if(Configure::read('Payment.pay.cycle.manual') != $emailMarketingUser['EmailMarketingUser']['payment_cycle']){
	    			$receipt['PaymentTempInvoice']['recurring_amount'] 		= $normalPayAmount;
	    			$receipt['PaymentTempInvoice']['recurring_plan_name'] 	= __('Email Marketing ' .$newPlan['EmailMarketingPlan']['name']);
	    			$receipt['PaymentTempInvoice']['payment_cycle'] 		= $emailMarketingUser['EmailMarketingUser']['payment_cycle'];
	    		}

	    		$TempInvoice = ClassRegistry::init("Payment.PaymentTempInvoice");

	    		return empty($tempInvoiceId) ? $TempInvoice->saveTempInvoice($receipt) : $TempInvoice->updateTempInvoice($tempInvoiceId, $receipt);

    		}else{

    			// The following IF clause should not be run, because we have checked whether the prepaid amount can fully cover the fee or not.
    			if($emailMarketingUser['EmailMarketingUser']['prepaid_amount'] > 0){
    				$emailMarketingUser['EmailMarketingUser']['prepaid_amount'] -= $newPlan['EmailMarketingPlan']['total_price'];
    				$this->updateUser($userId, $emailMarketingUser);
    			}

    			return true;
    		}

    	}else{

    		// If client's prepaid amount can cover the first payment cycle, we still want the client to subscribe the recurring payment agreement
    		if(Configure::read('Payment.pay.cycle.manual') != $emailMarketingUser['EmailMarketingUser']['payment_cycle']){

    			$needToPayAmount = 0; // prepaid amount already covered the first payment cycle
    			$deductMsg = '<p><strong style="color: red;">' .__("The first payment cycle has been paid using your prepaid amount.") .'.</strong></p>';
    			$receipt = array(
	    			'PaymentTempInvoice' => array(
		    			'user_id'				=> $userId,
		    			'is_auto_created' 		=> 1,
		    			'purchase_code' 		=> $paymentCode,
	    				'payment_cycle'			=> $emailMarketingUser['EmailMarketingUser']['payment_cycle'],
		    			'amount'				=> $needToPayAmount, //TODO need to see whether PayPal accepts this. If not, find out the min charge amount and charge it
	    				'recurring_amount'		=> $normalPayAmount,
	    				'recurring_plan_name'	=> __("Email Marketing") ." " .$newPlan['EmailMarketingPlan']['name'],
		    			'content'				=> $newPlan['EmailMarketingPlan']['description'] .((isset($deductMsg)) ? $deductMsg : ""),
		    			'created_by'			=> $userId,
		    			'created' 				=> date('Y-m-d H:i:s'),
		    			'due_date'				=> date('Y-m-d'),
		    			'related_update_data' 	=> serialize(array('plugin' => 'EmailMarketing', 'class' => 'EmailMarketingUser', 'id' => $emailMarketingUser['EmailMarketingUser']['id'], 'data' => $emailMarketingUser))
	    			)
    			);
    			if(!empty($tempInvoiceId)){
    				$receipt['PaymentTempInvoice']['id'] = $tempInvoiceId;
    			}

    			$TempInvoice = ClassRegistry::init("Payment.PaymentTempInvoice");

    			return empty($tempInvoiceId) ? $TempInvoice->saveTempInvoice($receipt) : $TempInvoice->updateTempInvoice($tempInvoiceId, $receipt);

    		}else{

    			$result = $this->updateUser($userId, $emailMarketingUser);
    			return !($result === FALSE); // Force to return TRUE when succeed and FALSE on failure

    		}

    	}
    }

    // Even more important note: How to calculate client credits. !!!!
    //		Before talking about the calculation, let's define the payment cycle length. Choose a longer payment cycle, client can get certain discount and save some money when purchasing the plan.
    //		We use payment gateway's next pay day and last pay day to get payment period.
    //
    //		If the client has a paid plan and want to switch to other plans, we calculate the plan used percentage in the following 2 ways:
    //		1, Calculated by passed days:
    //			In this method, the used percentage will be [ passed days / payment cycle length ]. e.g. Jack has a "Starter Plan" and payment cycle is QUARTERLY and only 10 days left. Then the used
    //			percentage is (next pay day - last pay day - 10 days left) / (next pay day - last pay day) = 88.89% (a sample value, not exact value)
    //
    //		One important thing to notice that is payment cycle has nothing to do with plan monthly limit. Take that Jack as an example, his payment cycle is QUARTERLY, that doesn't mean he can sent
    //		30000 emails at first month without extra payment. (Let's assume "Starter Plan" monthly limit is 10000).
    //
    //		2, Calculated by email sent amount
    //			This method can be used as a "double check" of the first method. It checks the usage month by month. If a whole month has passed, then the usage for that month will be the plan's monthly
    //			limit, because the exceeded emails are already charged and next month all usages are cleared. If a month has not fully paassed, we check the usage for that month, and if the monthly
    //			limit has been reached, we will treat that month as a passed month. This is to prevent that a client used all the monthly limit at the beginning of the month and then switch to other plan
    //			and still gets a lot "credits".
    //			To make things clear, let's still use Jack above as an example. Let's assume "Starter Plan" monthly limit is 10000, and Jack has reached the 10th day of the second month with no free emails.
    //			His 2nd month usage is 8000 emails. So in this method, we calculate the usage percentage by the following steps.
    //				a) First month usage is 8000 emails
    //				b) Second month usage is 8000 emails
    //				c) Total limit during the payment cycle is 10000 emails monthly x 3 months (QUARTER) = 30000 emails
    //				d) used percentage is (10000 emails + 8000 emails) / 30000 emails = 60%
    //				   [Note: because the first month was passed, no matter how many email was sent, we all treat it as a fully usage, we never pass the un-used email limit to the next month]
    //			In this example, if we use method 1 (Calculated by passed days) to calculate, the usage percentage will be
    //				(30 days (first month) + 10 days (second month)) / (30 days x 3 months (QUARTER)) = 44.44% (here we assume that each month has 30 days)
    //			Now you can see, there are huge differences.
    //
    //		After calculated by the above 2 methods, we will choose a greater value as the final used percentage
    //
    public function calculateUsedPercentage($emailMarketingUser, $currentPlan){

    	// This calculation method can only be used against the paid plan user
    	if(
    		empty($emailMarketingUser['EmailMarketingUser']['payment_cycle']) ||
    		empty($emailMarketingUser['EmailMarketingUser']['last_pay_date']) ||
    		empty($emailMarketingUser['EmailMarketingUser']['next_pay_date']) ||
    		empty($emailMarketingUser['EmailMarketingUser']['email_marketing_plan_id']) ||
    		!isset($currentPlan['EmailMarketingPlan']['pay_per_email']) ||
    		!empty($currentPlan['EmailMarketingPlan']['pay_per_email']) ||
    		$currentPlan['EmailMarketingPlan']['id'] != $emailMarketingUser['EmailMarketingUser']['email_marketing_plan_id']
    	){
    		$logType 	 = Configure::read('Config.type.emailmarketing');
    		$logLevel 	 = Configure::read('System.log.level.error');
    		$logMessage  = __('Email Marketing User data is corrupted in plan switch process.');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		$logType 	 = Configure::read('Config.type.emailmarketing');
    		$logLevel 	 = Configure::read('System.log.level.critical');
    		$logMessage  = __('User (#' .$userId .') switch plan, but Email Marketing User data corrupted. (Email marketing User: ' .json_encode($emailMarketingUser) .')');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    		return false;
    	}

    	// Calculate method 1: Calculated by passed days
    	if(strtotime($emailMarketingUser['EmailMarketingUser']['next_pay_date']) <= time()){
    		return 1; // If the plan has expired, then the used percetage will be 100%, no calculation needed.
    	}

    	$nextPayDateSec = strtotime($emailMarketingUser['EmailMarketingUser']['next_pay_date']);
    	$lastPayDateSec = strtotime($emailMarketingUser['EmailMarketingUser']['last_pay_date']);
    	$totalPaymentPeriodDays = floor(($nextPayDateSec - $lastPayDateSec) / 3600 / 24);
    	switch($emailMarketingUser['EmailMarketingUser']['payment_cycle']){
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

    		$logType 	 = Configure::read('Config.type.emailmarketing');
    		$logLevel 	 = Configure::read('System.log.level.error');
    		$logMessage  = __('Cannot calculate email marketing plan usage based on invalid payment cycle.');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		$logType 	 = Configure::read('Config.type.emailmarketing');
    		$logLevel 	 = Configure::read('System.log.level.critical');
    		$logMessage  = __('User (#' .$userId .') switch plan, but we cannot get correct payment cycle. (Payment Cycle: ' .$emailMarketingUser['EmailMarketingUser']['payment_cycle'] .', Last Pay Date: ' .$emailMarketingUser['EmailMarketingUser']['last_pay_date'] .', Next Pay Date: ' .$emailMarketingUser['EmailMarketingUser']['next_pay_date'] .').');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    		return false; // Payment cycle has to be one of the pre-defined payment cycle
    	}
    	$passedDays = floor((time() - $lastPayDateSec) / 3600 / 24);
    	if(empty($passedDays)){
    		$usedPercentage1 = 0;
    	}else{
    		$usedPercentage1 = round(($passedDays / $totalPaymentPeriodDays), 2, PHP_ROUND_HALF_UP);
    	}

    	// Calculate method 2: Calculated by usage
    	$totalLimits 	= $currentPlan['EmailMarketingPlan']['email_limit'] * $totalPaymentPeriodMonth;
    	$startMonth 	= intval(date('m', $lastPayDateSec));
    	$startYear 		= intval(date('Y', $lastPayDateSec));
    	$curMonth 		= intval(date('m'));
    	$curYear 		= intval(date('Y'));
    	$passedMonths = ($curYear == $startYear) ? ($curMonth - $startMonth) : (12 - $startMonth + $curMonth);
    	if($passedMonths >= $totalPaymentPeriodMonth){
    		return 1; // If the plan has expired, then the used percetage will be 100%, no further calculation needed.
    	}
    	$usedLimits = $passedMonths * $currentPlan['EmailMarketingPlan']['email_limit'] + $emailMarketingUser['EmailMarketingUser']['used_email_count'];
    	$usedPercentage2 = round(($usedLimits / $totalLimits), 2, PHP_ROUND_HALF_UP);

    	// Final used percentage
    	$finalUsedPercentage = ($usedPercentage1 > $usedPercentage2) ? $usedPercentage1 : $usedPercentage2;

    	return $finalUsedPercentage;
    }

    // We query the client's last payment and get the credits.
    // 		Credits = last payment x (100% - final used percentage)
    //
    private function __calculateCreditLeftAfterSwitchPlan($emailMarketingUser, $finalUsedPercentage, $currentPlan){
    	if($finalUsedPercentage >= 1){
    		return 0; // If client has used all limits, no credit left.
    	}

    	$superUserId = $this->emailMarketingUserIdToSuperUserId($emailMarketingUser['EmailMarketingUser']['id']);

    	if(empty($superUserId) || empty($currentPlan)){
    		return false;
    	}

    	// Get last fully paid amount
    	// The last fully paid amount will be the paid plan price. The prepaid amount is calculated separately, so it is not included here.
    	$lastFullyPaidAmount = @$currentPlan['EmailMarketingPlan']['total_price'];
    	if(empty($lastFullyPaidAmount) && !is_numeric($lastFullyPaidAmount)){

    		$logType 	 = Configure::read('Config.type.emailmarketing');
    		$logLevel 	 = Configure::read('System.log.level.error');
    		$logMessage  = __('Cannot get last payment information during plan switch process.');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage);

    		$logLevel 	 = Configure::read('System.log.level.critical');
    		$logMessage  = __('User (#' .$superUserId .') switch plan, but we cannot get client\'s last payment information.');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    		return false;
    	}

    	return $lastFullyPaidAmount * (1 - $finalUsedPercentage);
    }
}
