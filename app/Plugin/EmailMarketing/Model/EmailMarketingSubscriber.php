<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * Subscriber Model
 *
 */
class EmailMarketingSubscriber extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'email_marketing_list_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                //'message' => 'Your custom message here',
                'allowEmpty' => false,
                //'required' => false,
                //'last' => false, // Stop validation after this rule
                //'on' => 'create', // Limit validation to 'create' or 'update' operations
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
            'list_unique' => array(
                'rule'          => array('isListUniqueEmail'),
                'allowEmpty'    => false,
                'message'       => 'Email address already in the list',
            ),
            'not_blacklisted' => array(
                'rule'          => array('isNotBlacklistedEmail'),
                'allowEmpty'    => false,
                'message'       => 'Email address in blacklist',
            ),
        )
	);

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'EmailMarketingMailingList' => array(
            'className'  => 'EmailMarketing.EmailMarketingMailingList',
            'foreignKey' => 'email_marketing_list_id',
            'dependent'  => false
        )
    );

    public $hasMany = array(
    	'EmailMarketingSubscriberOpenRecord' => array(
    		'className'    => 'EmailMarketing.EmailMarketingSubscriberOpenRecord',
    		'foreignKey'   => 'email_marketing_subscriber_id',
    		'dependent'    => true
    	),
    	'EmailMarketingSubscriberClickRecord' => array(
    		'className'    => 'EmailMarketing.EmailMarketingSubscriberClickRecord',
    		'foreignKey'   => 'email_marketing_subscriber_id',
    		'dependent'    => true
    	),
    	'EmailMarketingSubscriberBounceRecord' => array(
    		'className'    => 'EmailMarketing.EmailMarketingSubscriberBounceRecord',
    		'foreignKey'   => 'email_marketing_subscriber_id',
    		'dependent'    => true
    	)
    );

    public function isListUniqueEmail() {
        $email      = $this->data[$this->alias]['email'];
        $listId     = $this->data[$this->alias]['email_marketing_list_id'];
        $conditions = array("{$this->alias}.email" => $email, "{$this->alias}.email_marketing_list_id" => $listId);
        if(isset($this->data[$this->alias]['id']) && !empty($this->data[$this->alias]['id'])) {
            $conditions = array_merge($conditions, array("{$this->alias}.id !=" => $this->data[$this->alias]['id']));
        }
        return !$this->hasAny($conditions);
    }

    public function isNotBlacklistedEmail() {
    	$email      = $this->data[$this->alias]['email'];
        $userId     = $this->data[$this->alias]['email_marketing_user_id'];
        $EmailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketingBlacklistedSubscriber');
        $conditions = array("EmailMarketingBlacklistedSubscriber.email" => $email, "EmailMarketingBlacklistedSubscriber.email_marketing_user_id" => $userId);
        return !$EmailMarketingBlacklistedSubscriber->hasAny($conditions);
    }

    public function countSubscriberByUser($emailMarketingUserId){

    	if(empty($emailMarketingUserId)){
    		return false;
    	}

    	return $this->find('count', array(
    		'conditions' => array(
    			'EmailMarketingSubscriber.deleted' => 0
	    	),
    		'joins' => array(
    			array(
    				'table' => 'email_marketing_mailing_lists',
    				'alias' => 'EmailMarketingMailingList',
    				'type' => 'inner',
    				'conditions' => array(
    					'EmailMarketingMailingList.id = EmailMarketingSubscriber.email_marketing_list_id',
    					'EmailMarketingMailingList.email_marketing_user_id' => $emailMarketingUserId
    				)
    			)
    		),
    		'contain' => false
    	));
    }

    public function saveSubscriber($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
            return true;
        }else{
            return false;
        }
    }

    public function updateSubscriber($id, $data){
        $this->id = $id;
        $this->contain();
        return $this->saveAll($data, array('validate' => 'first'));
    }

    public function deleteSubscriber($id){
    	unset($this->validate['email']['not_blacklisted']); // no need to check whether this subscriber is black listed or not when deleting it

    	//TODO when implemented the log function, we do real delete here and log the action
        // To keep reference, we don't actually delete the list, we only mark it as deleted
    	$this->read(null, $id);
    	$this->set('deleted', 1);

    	return $this->save();
    }

    public function deleteAllSubscribersInMailingList($mailingListId){
    	if(empty($mailingListId) || !is_numeric($mailingListId)){
    		return false;
    	}
    	return $this->updateAll(
    		array('EmailMarketingSubscriber.deleted' => 1),
    		array('EmailMarketingSubscriber.email_marketing_list_id' => $mailingListId)
    	);
    }

    public function importSubscriber($data, $limit, $extraAttrLimit = 0){
        // Load app's vendor file (cannot use App::import because CakePhp will only look at the plugin's vendor folder not the app's vendor folder)
        require_once ROOT .'/app/Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php';
        require_once ROOT .'/app/Vendor/PHPExcel/Classes/PHPExcel/Cell.php';

        $results = array('saved' => 0, 'duplicated' => 0, 'invalid' => 0, 'blacklist' => 0);

        $userId     = $data["EmailMarketingSubscriber"]["email_marketing_user_id"];
        $listId    	= $data["EmailMarketingSubscriber"]["email_marketing_list_id"];
        $fileName  	= $data["EmailMarketingSubscriber"]["subscriber_file"]['name'];
        $tmpFileName = explode(".", $fileName);
        $ext        = !empty($fileName) ? trim(strtolower(array_pop($tmpFileName))) : null;
        if(empty($ext) || !in_array($ext, array("csv","xlsx","xls"))){
        	return false;
        }else{

            // Parse File
            $inputFileName      = $data["EmailMarketingSubscriber"]["subscriber_file"]['tmp_name'];
            $inputFileType      = PHPExcel_IOFactory::identify($inputFileName);
        	$objReader          = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            $objPHPExcel        = $objReader->load($inputFileName);
            $totalSheets        = $objPHPExcel->getSheetCount(); // There may be multiple sheet within one file
            $allSheetName       = $objPHPExcel->getSheetNames();  // Sheet names

            $objWorksheet       = $objPHPExcel->setActiveSheetIndex(0); // first sheet
            $highestRow         = $objWorksheet->getHighestRow();
            $highestColumn      = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            $data               = array();

            for ($row = 1; $row <= $highestRow; ++$row) {
                for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                    $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
                    if(is_array($data) ) { $data[$row-1][$col]=$value; }
                }
            }

            // Import file
            if(!empty($data)){
            	$header = $data[0];
                unset($data[0]);
                if(!empty($header)){
                	$header = array_map('strtolower', $header);
                	$firstNameIndex = array_search('first-name', $header);
                	$lastNameIndex  = array_search('last-name', $header);
                	$emailIndex     = array_search('email', $header);
                	$extraAttrLimit = intval($extraAttrLimit);
                	if(!empty($extraAttrLimit) && is_numeric($extraAttrLimit) && $extraAttrLimit > 0){
                		$extraAttrIndex = 0;
                		$extraAttrArr = [];
                		foreach($header as $h){
                			$h = trim($h);
                			$h = preg_replace('/[^a-zA-Z0-9\s_\-]/', "", $h);
                			if(!in_array($h, ['first-name', 'last-name', 'email'])){
                				$extraAttrArr[$h] = $extraAttrIndex;
                				if(count($extraAttrArr) >= $extraAttrLimit){
                					break;
                				}
                			}
                			$extraAttrIndex++;
                		}
                	}
                    foreach($data as $row){
                        $firstName          = filter_var($row[$firstNameIndex], FILTER_SANITIZE_STRING);
                        $lastName           = filter_var($row[$lastNameIndex], FILTER_SANITIZE_STRING);
                        $currentTimestamp   = date("Y-m-d H:i:s");

                        // Filter out invalid characters of email address
                        /*
                         * Allowed characters for email address:
                         *  Uppercase and lowercase English letters (a-z, A-Z)
                         *  Digits 0 to 9
                         *  Characters ! # $ % & ' * + - / = ? ^ _ ` { | } ~
                         *  Character . (dot, period, full stop) provided that it is not the first or last character,
                         *  and provided also that it does not appear two or more times consecutively.
                         */
                        $email              = filter_var($row[$emailIndex], FILTER_SANITIZE_EMAIL);

                        $extraAttr = '';
                        if(!empty($extraAttrArr)){
                        	$tempAttrArr = [];
                        	foreach($extraAttrArr as $attr => $key){
                        		$tempAttrArr[$attr] = filter_var($row[$key], FILTER_SANITIZE_STRING);
                        	}
                        	$extraAttr = serialize($tempAttrArr);
                        }

                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                            if ($this->hasAny(array('EmailMarketingSubscriber.email' => $email, 'EmailMarketingSubscriber.email_marketing_list_id' => $listId))){
                                $results["duplicated"]++;
                            }else{
                                $EmailMarketingBlacklistedSubscriber = ClassRegistry::init('EmailMarketingBlacklistedSubscriber');
                                if($EmailMarketingBlacklistedSubscriber->hasAny(array('EmailMarketingBlacklistedSubscriber.email' => $email, 'EmailMarketingBlacklistedSubscriber.email_marketing_user_id' => $userId))){
                                	$results["blacklist"]++;
                                }else{
                                	if($results["saved"] >= $limit){
                                		return $results; // Exit when exceed subscriber limit
                                	}
                                	$valueStr = '"' .$listId .'","' .$firstName .'","' .$lastName .'","' .$email .'",\'' .$extraAttr .'\', "' .$currentTimestamp .'","' .$currentTimestamp .'"';
                                    $this->query('insert into email_marketing_subscribers(email_marketing_list_id, first_name, last_name, email, extra_attr, created, modified) values(' .$valueStr .')');
                                    $results["saved"]++;
                                }
                            }
                        }else{
                        	$results["invalid"]++;
                        }
                    }
                }
            }
        }
        return $results;
    }

    public function exportSubscriber($data){
        if(!isset($data["EmailMarketingSubscriber"]["included_columns"]) || empty($data["EmailMarketingSubscriber"]["included_columns"])){
        	return false;
        }

        $csvContent = "";

        // Generate header
        $header = array();
        foreach($data["EmailMarketingSubscriber"]["included_columns"] as $h){
            array_push($header, Inflector::humanize($h));
        }
        $csvContent .= implode(",", $header) ."\n";

        // Generate content
        $dateFrom = $this->_dateArrayToString($data["EmailMarketingSubscriber"]["date_from"]);
        $dateTo   = $this->_dateArrayToString($data["EmailMarketingSubscriber"]["date_to"]);
        $subscribers = $this->find('all', array(
            'fields' => $data["EmailMarketingSubscriber"]["included_columns"],
            'conditions' => array(
                'EmailMarketingSubscriber.email_marketing_list_id' => $data["EmailMarketingSubscriber"]["email_marketing_list_id"],
                'EmailMarketingSubscriber.created BETWEEN ? AND ?' => array($dateFrom,$dateTo)
            ),
            'contain' => false
        ));
        foreach($subscribers as $s){
        	$csvContent .= implode(",", $s["EmailMarketingSubscriber"]) ."\n";
        }

        return $csvContent;
    }

/**
 * Check whether given subscribers are belonged to the certain campaign
 *
 * @param int $campaignId
 * @param array $subscriberIds
 */
    public function checkSubscriberBelongToCampaign($campaignId, $subscriberIds = []){
		if(empty($campaignId) || empty($subscriberIds)){
			return false;
		}

		$validSubscribers = $this->find('all', array(
			'fields' => array(
				$this->alias .'.id'
			),
			'conditions' => array(
				$this->alias .'.id' => $subscriberIds,

			),
			'joins' => array(
				array(
					'table' => 'email_marketing_mailing_lists',
					'alias' => 'MailingList',
					'type' => 'inner',
					'conditions' => array(
						$this->alias .'.email_marketing_list_id = MailingList.id',
					)
				),
				array(
					'table' => 'email_marketing_campaign_lists',
					'alias' => 'CampaignList',
					'type' => 'inner',
					'conditions' => array(
						'CampaignList.email_marketing_list_id = MailingList.id',
						"CampaignList.email_marketing_campaign_id = {$campaignId}"
					)
				)
			),
		));

		$result = false;
		if(!empty($validSubscribers)){
			$result = Set::classicExtract($validSubscribers, '{n}.EmailMarketingSubscriber.id');
		}
		return $result;
    }

    public function unsubscribeFromMailingList($subscriberId){
    	if(empty($subscriberId)){
    		return false;
    	}else{

    		$this->read(null, $subscriberId);
    		$this->set('unsubscribed', 1);
    		return $this->save();

    	}
    }
}
