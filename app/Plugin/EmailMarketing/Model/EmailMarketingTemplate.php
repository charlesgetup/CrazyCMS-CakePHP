<?php
App::uses('EmailMarketingAppModel', 'EmailMarketing.Model');
/**
 * Template Model
 *
 */
class EmailMarketingTemplate extends EmailMarketingAppModel {

    public $actsAs = array('Containable');

    public function __construct($id = false, $table = null, $ds = null) {
    	parent::__construct($id,$table,$ds);
    	$this->virtualFields = array(
    		'owner' => "SELECT CONCAT(u.first_name,\" \",u.last_name) FROM users as u LEFT JOIN email_marketing_users as emu ON emu.user_id = u.id WHERE emu.id = EmailMarketingTemplate.email_marketing_user_id",
    	);
    }

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
        ),
		// HTML can be empty when user clean the canvas
// 		'html' => array(
// 			'notempty' => array(
// 				'rule' => array('notempty'),
// 				'message' => 'is not empty',
// 				'allowEmpty' => false,
// 				'required'   => true,
// 				'last'       => false, // Stop validation after this rule
// 				//'on' => 'create', // Limit validation to 'create' or 'update' operations
// 			),
// 		)
	);

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $belongsTo = array(
        'EmailMarketingUser' => array(
            'className'  => 'EmailMarketing.EmailMarketingUser',
            'foreignKey' => 'email_marketing_user_id',
            'dependent'  => false
        )
    );

    public $hasMany = array(
        'EmailMarketingCampaign' => array(
            'className'    => 'EmailMarketing.EmailMarketingCampaign',
            'foreignKey'   => 'email_marketing_template_id',
            'dependent'    => false
        ),
        'EmailMarketingPurchasedTemplate' => array(
            'className'    => 'EmailMarketing.EmailMarketingPurchasedTemplate',
            'foreignKey'   => 'email_marketing_template_id',
            'dependent'    => false
        )
    );

    // Get template list based on user ID
    public function getTemplateList($superUserId){
        $templateList = $this->find('list', array(
            'fields' => array(
                'EmailMarketingTemplate.id',
                'EmailMarketingTemplate.name',
            ),
            'conditions' => array(
                'EmailMarketingUser.user_id' => $superUserId,
            	'EmailMarketingTemplate.deleted' => 0
            ),
            'joins' => array(
                array(
                    'table' => 'email_marketing_users',
                    'alias' => 'EmailMarketingUser',
                    'type'  => 'inner',
                    'conditions' => array(
                        'EmailMarketingUser.id = EmailMarketingTemplate.email_marketing_user_id'
                    )
                )
            ),
        ));

        return $templateList;
    }

    public function getSystemTemplateByName($name){
    	return $this->find('first', array(
    		"conditions" => array(
	    		"name" 		=> $name,
    			"for_sale" 	=> 0,
    			"email_marketing_user_id IS NULL"
	    	),
    		"contain" => false
    	));
    }

    public function getTemplate($id, $emailMarketingUserId){
    	return $this->find('first', array(
    		"conditions" => array(
    			"id" 						=> $id,
    			"email_marketing_user_id" 	=> $emailMarketingUserId
    		),
    		"contain" => false
    	));
    }

    public function getTemplateAssets($superUserId){

    	if(!empty($superUserId) && is_numeric($superUserId)){

    		try{

    			$path = Configure::read('EmailMarketing.email.html.template.assets.path');
    			$path = str_replace("{user_id}", $superUserId, $path);
    			$path = '?prefix=' .$path .'/&delimiter=/';
    			$fileList = $this->amazonS3StorageManagement(Configure::read('System.aws.s3.action.list'), $path);
    			$assets = [];
    			if(!empty($fileList) && is_array($fileList)){
    				foreach($fileList as $filename => $downloadLink){
    					$assets[] = $downloadLink;
    				}
    			}
    			return $assets;

    		}catch(AmazonS3Exception $exception){
    			return false;
    		}

    	}
    	return false;
    }

    public function savePreviewImage($templateId, $superUserId, $imageData){
    	if(!empty($templateId) && is_numeric($templateId) && !empty($superUserId) && is_numeric($superUserId)){

    		$s3Path 	= Configure::read('EmailMarketing.email.html.template.preview.path');
    		$s3Path 	= str_replace("{user_id}", $superUserId, $s3Path);
    		$s3Path 	= str_replace("{template_id}", $templateId, $s3Path);
    		$s3Action 	= Configure::read('System.aws.s3.action.put');

    		$data 		= base64_decode($imageData);
    		$tmpFile	= WWW_ROOT .'files' .DS .'tmp' .DS .$templateId .".jpg";
    		if(file_put_contents($tmpFile, $data)){

    			try{

    				if ($this->amazonS3StorageManagement($s3Action, $s3Path, array($tmpFile))) {

    					@unlink($tmpFile);
    					$previewImgPath = Configure::read('System.aws.s3.bucket.link.prefix') .$s3Path .'/' .$templateId .".jpg";
    					return $previewImgPath;

    				}else{
    					return false;
    				}

    			}catch(AmazonS3Exception $exception){
    				return false;
    			}

    		}else{
    			return false;
    		}

    	}
    	return false;
    }

    public function saveTemplateAsset($superUserId, $asset){
    	if(!empty($superUserId) && is_numeric($superUserId) && !empty($asset)){

    		try{

    			$s3Path 	= Configure::read('EmailMarketing.email.html.template.assets.path');
    			$s3Path 	= str_replace("{user_id}", $superUserId, $s3Path);
				$s3Action 	= Configure::read('System.aws.s3.action.put');
				$file 		= $asset['tmp_name'];

				$newFile	= WWW_ROOT .'files' .DS .'tmp' .DS .str_replace(" ", "", $asset["name"]);

				if(!file_exists($newFile) && $this->moveUploadedFile($file, $newFile)){
					if ($this->amazonS3StorageManagement($s3Action, $s3Path, array($newFile))) {

						@unlink($file);
						unlink($newFile);
						$assetPath = Configure::read('System.aws.s3.bucket.link.prefix') .$s3Path .'/' .basename($newFile);
						return $assetPath;

					}else{
						return false;
					}
				}else{
					return false;
				}

    		}catch(AmazonS3Exception $exception){
    			return false;
    		}

    	}
    	return false;
    }

    public function saveTemplate($data){
    	$this->create();
    	if($this->saveAll($data , array('validate' => 'first'))){
    		return $this->getInsertID();
    	}else{
    		return false;
    	}
    }

    public function updateTemplate($id, $data){
    	$this->id = $id;
    	$this->contain();
    	return $this->saveAll($data, array('validate' => 'first'));
    }

    public function deleteTemplate($id){
    	// If the template has been purchased before, it cannot be deleted from DB.
    	// We only mark it as deleted. Then it will be removed from the list, but already purchased user can still use it.
    	$this->read(null, $id);
    	$this->set('deleted', 1);
    	return $this->save();
    }
}
