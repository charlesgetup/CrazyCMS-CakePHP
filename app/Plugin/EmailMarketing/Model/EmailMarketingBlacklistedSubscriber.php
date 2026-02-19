<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * Blacklisted Subscriber Model
 *
 */
class EmailMarketingBlacklistedSubscriber extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'email_marketing_user_id' => array(
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
            )
        )
	);

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'EmailMarketingUser' => array(
            'className'  => 'EmailMarketing.EmailMarketingUser',
            'foreignKey' => 'email_marketing_user_id',
            'dependent'  => false
        )
    );

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
        return $this->delete($id, false);
    }

    public function importSubscriber($data, $limit){
        // Load app's vendor file (cannot use App::import because CakePhp will only look at the plugin's vendor folder not the app's vendor folder)
        require_once ROOT .'/app/Vendor/PHPExcel/Classes/PHPExcel/IOFactory.php';
        require_once ROOT .'/app/Vendor/PHPExcel/Classes/PHPExcel/Cell.php';

        $results = array('saved' => 0, 'duplicated' => 0, 'invalid' => 0);

        $userId    = $data["EmailMarketingBlacklistedSubscriber"]["email_marketing_user_id"];
        $fileName  = $data["EmailMarketingBlacklistedSubscriber"]["subscriber_file"]['name'];
        $tmpFileName = explode(".", $fileName);
        $ext       = !empty($fileName) ? trim(strtolower(array_pop($tmpFileName))) : null;
        if(empty($ext) || !in_array($ext, array("csv","xlsx","xls"))){
        	return false;
        }else{

            // Parse File
            $inputFileName      = $data["EmailMarketingBlacklistedSubscriber"]["subscriber_file"]['tmp_name'];
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
                    $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();//getValue();
                    if(is_array($data) ) { $data[$row-1][$col]=$value; }
                }
            }

            // Import file
            if(!empty($data)){
                $header = $data[0];
                unset($data[0]);
                if(!empty($header)){
                	$header = array_map('strtolower', $header);
                    foreach($data as $row){
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
                        $email              = filter_var($row[array_search('email', $header)], FILTER_SANITIZE_EMAIL);

                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                            if ($this->hasAny(array('EmailMarketingBlacklistedSubscriber.email' => $email, 'EmailMarketingBlacklistedSubscriber.email_marketing_user_id' => $userId))){
                                $results["duplicated"]++;
                            }else{
                            	if($results["saved"] >= $limit){
                            		return $results; // Exit when exceed limit
                            	}
                            	$valueStr = '"' .$userId .'","' .$email .'","' .$currentTimestamp .'"';
                                $this->query('insert into email_marketing_blacklisted_subscribers(email_marketing_user_id, email, created) values(' .$valueStr .')');
                                $results["saved"]++;
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
        if(!isset($data["EmailMarketingBlacklistedSubscriber"]["included_columns"]) || empty($data["EmailMarketingBlacklistedSubscriber"]["included_columns"])){
        	return false;
        }

        $csvContent = "";

        // Generate header
        $header = array();
        foreach($data["EmailMarketingBlacklistedSubscriber"]["included_columns"] as $h){
            array_push($header, Inflector::humanize($h));
        }
        $csvContent .= implode(",", $header) ."\n";

        // Generate content
        $dateFrom = $this->_dateArrayToString($data["EmailMarketingBlacklistedSubscriber"]["date_from"]);
        $dateTo   = $this->_dateArrayToString($data["EmailMarketingBlacklistedSubscriber"]["date_to"]);
        $subscribers = $this->find('all', array(
            'fields' => $data["EmailMarketingBlacklistedSubscriber"]["included_columns"], // can only be "email" for now
            'conditions' => array(
                'EmailMarketingBlacklistedSubscriber.email_marketing_user_id' => $data["EmailMarketingBlacklistedSubscriber"]["email_marketing_user_id"],
                'EmailMarketingBlacklistedSubscriber.created BETWEEN ? AND ?' => array($dateFrom,$dateTo)
            ),
            'contain' => false
        ));
        foreach($subscribers as $s){
        	$csvContent .= implode(",", $s["EmailMarketingBlacklistedSubscriber"]) ."\n";
        }

        return $csvContent;
    }
}
