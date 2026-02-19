<?php
App::uses('SSH2', 'Util');
App::uses('ExtendPHP', 'Util');
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');

/**
 * Sender Model
 *
 */
class EmailMarketingSender extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

    public $currentUserId = null;

    public function __construct($id = false, $table = null, $ds = null) {
    	parent::__construct($id,$table,$ds);

    	// Get main user ID, not service user ID
    	App::uses('CakeSession', 'Model/Datasource');
    	$userId = CakeSession::read('Auth.User.id');

    	$s3Path = Configure::read('System.aws.s3.bucket.link.prefix') .Configure::read('EmailMarketing.email.DKIM.publicKeyFile.path');
    	$s3Path = str_replace("{user_id}", $userId, $s3Path);
    	$s3Path = str_replace(".", "\.", $s3Path);
    	$virtualFieldsQuery = "	SELECT CONCAT(\"<a href='" .$s3Path ."/\", EmailMarketingSender.sender_domain ,\"/" .str_replace(".", "\.", Configure::read('EmailMarketing.email.DKIM.publicKeyFileName')) ."' target='_blank'>Download</a>\") as public_key_download_link ";
    	$virtualFieldsQuery .= "FROM email_marketing_senders AS email_marketing_senders_download_link ";
    	$virtualFieldsQuery .= "WHERE email_marketing_senders_download_link.id = EmailMarketingSender.id";
    	$this->virtualFields = array(
    		'public_key_download_link' => $virtualFieldsQuery,
    	);
    }

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'email_marketing_user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => "Email Marketing service is not enabled for the current user account",
				'allowEmpty' => false,
				'required'   => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'sender_domain' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'is not empty',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule' 			=> array('isUnique'),
				'message'       => 'Domain name is already in use',
				'allowEmpty' 	=> false,
				//'required' 	=> false,
				//'last' =		> false, // Stop validation after this rule
				//'on' 			=> 'create', // Limit validation to 'create' or 'update' operations
			),
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

    public $hasOne = array(
    	'EmailMarketingCampaign' => array(
    		'className'    => 'EmailMarketing.EmailMarketingCampaign',
    		'foreignKey'   => 'email_marketing_sender_id',
    		'dependent'    => true
    	)
    );

    public function countSenderByUser($emailMarketingUserId){

    	if(empty($emailMarketingUserId)){
    		return false;
    	}

    	return $this->find('count', array(
    		'conditions' => array(
    			'EmailMarketingSender.email_marketing_user_id' => $emailMarketingUserId
    		),
    		'contain' => false
    	));
    }

    public function getSenderList($superUserId){
    	return $this->find('list', array(
    		'fields' => array(
    			'EmailMarketingSender.id',
    			'EmailMarketingSender.sender_domain',
    		),
    		'conditions' => array(
    			'EmailMarketingUser.user_id' => $superUserId
    		),
    		'joins' => array(
    			array(
    				'table' => 'email_marketing_users',
    				'alias' => 'EmailMarketingUser',
    				'type'  => 'inner',
    				'conditions' => array(
    					'EmailMarketingUser.id = EmailMarketingSender.email_marketing_user_id'
    				)
    			)
    		),
    	));
    }

    public function getSenderDetailsById($senderId, $superUserId = null){
    	$conditions = array('EmailMarketingSender.id' => $senderId);
    	if(!empty($superUserId)){
    		$conditions['EmailMarketingUser.user_id'] = $superUserId;
    		return $this->find('first', array(
    			'conditions' => $conditions,
    			'joins' => array(
    				array(
    					'table' => 'email_marketing_users',
    					'alias' => 'EmailMarketingUser',
    					'type'  => 'inner',
    					'conditions' => array(
    						'EmailMarketingUser.id = EmailMarketingSender.email_marketing_user_id'
    					)
    				)
    			),
    		));
    	}else{
    		return $this->find("first", array(
    			'conditions' => $conditions,
    			'recursive'  => 0,
    			'contain'	 => false
    		));
    	}
    }

    public function getSenderByDomain($senderDomain){
    	return $this->find("first", array(
    		'conditions' => array('EmailMarketingSender.sender_domain' => $senderDomain),
    		'recursive'  => 0,
    		'contain'	 => false
    	));
    }

    public function saveSender($data){
        $this->create();
        if($this->saveAll($data , array('validate' => 'first'))){
        	return $this->__generateKeyFiles($data['EmailMarketingSender']['sender_domain']);
        }else{
            return false;
        }
    }

    public function updateSender($id, $data) {

    	if(!isset($data['EmailMarketingSender']['id']) || empty($data['EmailMarketingSender']['id'])){
    		$data['EmailMarketingSender']['id'] = $id;
    	}

        $this->id = $id;
        $senderData = $this->read();
        $this->contain();
        if($this->saveAll($data, array('validate' => 'first'))){
        	if($this->__deleteKeyFiles($id, $senderData['EmailMarketingSender']['sender_domain'])){
        		return $this->__generateKeyFiles($data['EmailMarketingSender']['sender_domain']);
        	}
        }

        return false;
    }

    public function deleteSender($id){
		if($this->__deleteKeyFiles($id)){
			return $this->delete($id, false);
		}else{
			return false;
		}
    }

    private function __getLocalTempDKIMSavedPath($superUserId, $senderDomain){
    	return $this->getUserFileSavedPath() .$superUserId ."/EmailMarketing/DKIM/" .$senderDomain ."/";
    }

/**
 * Generate sender domain name
 * @param string $senderDomain
 */
    private function __generateKeyFiles($senderDomain){

    	// Get main user ID, not service user ID
    	App::uses('CakeSession', 'Model/Datasource');
    	$userId = CakeSession::read('Auth.User.id');

    	// Get path to store the files
    	$absoluteFilePath = $this->__getLocalTempDKIMSavedPath($userId, $senderDomain);
    	if (!is_dir($absoluteFilePath)) {
    		if (!mkdir($absoluteFilePath, 0777, true)) {
    			return false;
    		}
    	}

    	// Generate the key files

    	$host = Configure::read('EmailMarketing.email.server.host');
    	$user = Configure::read('EmailMarketing.email.server.user');
    	$port = Configure::read('EmailMarketing.email.server.port');
    	$pass = Configure::read('EmailMarketing.email.server.priv');
    	$pubk = Configure::read('EmailMarketing.email.server.pubk');
    	$ssh2 = new SSH2($host, $user, $pass, $port, $debug, $pubk);

    	if($ssh2->isConnected()){

    		$ssh2->cmd('sudo bash create-client-dns.sh ' .$senderDomain);
    		$rawPublicKey 	= $ssh2->cmd('sudo cat /etc/opendkim/keys/' .$senderDomain .'/' .Configure::read('EmailMarketing.email.DKIM.publicKeyFileName'), true);
    		$rawPrivateKey 	= $ssh2->cmd('sudo cat /etc/opendkim/keys/' .$senderDomain .'/' .Configure::read('EmailMarketing.email.DKIM.privateKeyFileName'), true);
    		$ssh2->disconnect();

    		// Save private key content in DB
    		$sender = $this->getSenderByDomain($senderDomain);
    		if($sender){
    			$this->read(null, $sender['EmailMarketingSender']['id']);
    			$this->set('dkim_privkey', $rawPrivateKey);
    			if(!$this->save()){
    				return false;
    			}
    		}

    		// Format the public key
    		$publicKey		= str_replace("\t", " ", $rawPublicKey);
    		$publicKey		= str_replace("\n", "", $publicKey);
    		$keyPieces		= explode("\"", $publicKey);
    		$keyPieces		= array_map("trim", $keyPieces);
    		$keyPieces		= array_filter($keyPieces);
    		array_pop($keyPieces);
    		$publicKey		= implode(" ", $keyPieces) ."\"";
    		$publicKey		= str_replace("( ", "\"", $publicKey);

    		// Write the well formatted public key back to public key file
    		$localDKIMPublicKeyFileName = Configure::read('EmailMarketing.email.DKIM.publicKeyFileName');
    		$localDKIMPublicKeyFile 	= $absoluteFilePath .$localDKIMPublicKeyFileName;
    		if(file_put_contents($localDKIMPublicKeyFile, $publicKey, LOCK_EX)){

    			// Use the web server as a backup and push public key file to AWS S3 and public will access S3 for the file
    			$s3Path 	= Configure::read('EmailMarketing.email.DKIM.publicKeyFile.path');
    			$s3Path 	= str_replace("{user_id}", $userId, $s3Path);
    			$s3Path 	= $s3Path. DS .$senderDomain .DS;
    			$s3Action 	= Configure::read('System.aws.s3.action.put');

    			try{

    				// Only save public key in S3
    				if($this->amazonS3StorageManagement($s3Action, $s3Path, array($localDKIMPublicKeyFile))){

    					$absoluteFilePathArr = explode($userId, $absoluteFilePath);
    					$userDir = $absoluteFilePathArr[0] .$userId;
    					if(is_dir($userDir)){
    						$extendPHP = new ExtendPHP();
    						$extendPHP->rrmdir($userDir); // Remove the whole user local tmp path
    					}

    					return true;

    				}else{

    					return false;
    				}

    			}catch(AmazonS3Exception $exception){

    				return false;
    			}

    		}else{

    			return false;
    		}

    	}else{

    		return false;
    	}

    }

/**
 * Delete all the generate public / private key file
 * @param string $path
 */
    private function __deleteKeyFiles($senderId, $senderDomain = null){

    	$sender 			= $this->browseBy($this->primaryKey, $senderId, false);
    	$senderDomain		= empty($senderDomain) ? $sender['EmailMarketingSender']['sender_domain'] : $senderDomain;
    	$userId				= $this->emailMarketingUserIdToSuperUserId($sender['EmailMarketingSender']['email_marketing_user_id']);
    	if(empty($userId)){
    		return false;
    	}

    	// Delete private key in DB
    	$this->read(null, $senderId);
    	$this->set('dkim_privkey', null);
    	if(!$this->save()){
    		return false;
    	}

    	$localDKIMPublicKeyFileName = Configure::read('EmailMarketing.email.DKIM.publicKeyFileName');

    	// Delete public file in AWS S3, too
    	$s3Path 	= Configure::read('EmailMarketing.email.DKIM.publicKeyFile.path');
    	$s3Path 	= str_replace("{user_id}", $userId, $s3Path);
    	$s3Path 	= $s3Path. '/' .$senderDomain .'/';
    	$s3Action 	= Configure::read('System.aws.s3.action.delete');

    	try{

    		$this->amazonS3StorageManagement($s3Action, $s3Path, array($localDKIMPublicKeyFileName));

    		return true;

    	}catch(AmazonS3Exception $exception){

    		return false;
    	}
    }
}
