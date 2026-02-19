<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * Templates Controller
 *
 */
class EmailMarketingTemplatesController extends EmailMarketingAppController {

    public $paginate = array(
        'limit'     => 12,
        'order'     => array("EmailMarketingTemplate.id" => "ASC"),
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * index method
 *
 * @return void
 */
    public function admin_index() {

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){
    		$this->Paginator->settings = $this->paginate;
    		$this->DataTable->mDataProp = true;
    		$this->set('response', $this->DataTable->getResponse());
    		$this->set('_serialize','response');
    		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingTemplate.id']);
    	}

    }

/**
 * shoppingIndex method
 *
 * This index file load templates on sale
 *
 * @return void
 */
    public function admin_shoppingIndex() {

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

    		$emailMarketingUserRecordId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userServiceAccountId);

    		// Do not show purchased template
    		$purchasedTemplate = $this->__getPurchasedTemplate($emailMarketingUserRecordId, !empty($this->request->query['forceUpdatePurchasedTemplate']));

    		// Here loads templates which is built by others (not current client)
    		$this->Paginator->settings = array(
    			'conditions' => array(
    				'EmailMarketingTemplate.deleted' 					=> 0,
    				'EmailMarketingTemplate.email_marketing_user_id !=' => $emailMarketingUserRecordId,
    				'EmailMarketingTemplate.for_sale' 					=> 1
    			),
    			'limit'		 => 8,
    			'recursive'  => -1,
    			'order' 	 => array('EmailMarketingTemplate.id' => 'DESC')
    		);

    		if(!empty($purchasedTemplate)){
    			$this->Paginator->settings['conditions']['NOT'] = array('EmailMarketingTemplate.id' => $purchasedTemplate);
    		}

    		$emailMarketingOtherTemplates = $this->Paginator->paginate('EmailMarketingTemplate');
    		$this->set('otherTemplates', $emailMarketingOtherTemplates);

    		$superUserId = $this->Session->read('Auth.User.id');
    		$this->set('userId', $superUserId);
    	}

    }

/**
 * purchasedIndex method
 *
 * This index file load client purchased templates
 *
 * @return void
 */
    public function admin_purchasedIndex() {

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

    		// Here loads templates which is purchased by current client
    		$emailMarketingUserRecordId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userServiceAccountId);
	    	$this->Paginator->settings = array(
	    		'fields' => array(
	    			'EmailMarketingTemplate.name',
	    			'EmailMarketingTemplate.id'
		    	),
	    		'conditions' => array(
	    			'EmailMarketingTemplate.deleted' 							=> 0,
	    			'EmailMarketingPurchasedTemplate.email_marketing_user_id ' 	=> $emailMarketingUserRecordId
	    		),
	    		'joins' => array(
	    			array(
	    				'table' => 'email_marketing_purchased_templates',
	    				'alias' => 'EmailMarketingPurchasedTemplate',
	    				'type' => 'inner',
	    				'conditions' => array(
	    					$this->EmailMarketingTemplate->alias .'.id = EmailMarketingPurchasedTemplate.email_marketing_template_id',
	    				)
	    			),
	    		),
	    		'limit'		 => 8,
	    		'recursive'  => -1,
	    		'order' 	 => array('EmailMarketingTemplate.id' => 'DESC')
	    	);
	    	$emailMarketingPurchasedTemplates = $this->Paginator->paginate('EmailMarketingTemplate');
	    	$this->set('purchasedTemplates', $emailMarketingPurchasedTemplates);

	    	$superUserId = $this->Session->read('Auth.User.id');
	    	$this->set('userId', $superUserId);
    	}
    }

/**
 * clientIndex method
 *
 * This index file load client own templates
 *
 * @return void
 */
    public function admin_clientIndex() {

    	$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

    	if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

    		// Here loads templates which is built by current client
    		$emailMarketingUserRecordId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userServiceAccountId);
    		$this->Paginator->settings = array(
    			'conditions' => array(
    				'EmailMarketingTemplate.deleted' 					=> 0,
    				'EmailMarketingTemplate.email_marketing_user_id' 	=> $emailMarketingUserRecordId,
    			),
    			'limit'		 => 8,
    			'recursive'  => -1,
    			'order' 	 => array('EmailMarketingTemplate.id' => 'DESC')
    		);
    		$emailMarketingOwnTemplates = $this->Paginator->paginate('EmailMarketingTemplate');
    		$this->set('ownTemplates', $emailMarketingOwnTemplates);

    		$superUserId = $this->Session->read('Auth.User.id');
    		$this->set('userId', $superUserId);
    	}
    }

/**
 * search method
 *
 * No need to add VIEW method, because the template cannot be viewed in web page (to prevent client directly copied HTML code)
 * Only preview image can be viewd.
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_search($id = null) {
//TODO no view found. Not sure it is done or not

		if (!$this->EmailMarketingTemplate->exists($id)) {
			$template = null;
		}else{
			$template = $this->EmailMarketingTemplate->browseBy($this->EmailMarketingTemplate->primaryKey, $id);
		}

		$this->set('template', $template);

	}

/**
 * buy method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_buy($id = null, $tempInvoiceId = null) {

		if (!$this->EmailMarketingTemplate->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		$userId = $this->Session->read('Auth.User.id'); // Get Client group user ID, not service group user ID

		$this->loadModel('Payment.PaymentTempInvoice');
		$tempInvoice = $this->PaymentTempInvoice->browseBy('user_id', $userId, false);

		if (($this->request->is('post') || $this->request->is('put')) && $this->request->is('ajax')) {

			$tempInvoiceId 	= (isset($tempInvoice['PaymentTempInvoice']['id']) ? $tempInvoice['PaymentTempInvoice']['id'] : null);

			$template = $this->EmailMarketingTemplate->browseBy("id", $id, false);
			$this->loadModel('EmailMarketing.EmailMarketingPurchasedTemplate');
			$result = $this->EmailMarketingPurchasedTemplate->purchaseTemplate($userId, $template, $tempInvoiceId);

			if ($result === FALSE) {
				$this->_showErrorFlashMessage($this->EmailMarketingTemplate, __("Email marketing template purchase failed, please check logs for details."));
				echo json_encode(['result' => false]);
			} else {
				if($result === TRUE){
					$this->_showSuccessFlashMessage($this->EmailMarketingTemplate, __("Email marketing template has been successfully purchased. Enjoy the new template."));
					echo json_encode(['result' => true]);
				}else{
					echo json_encode(['tempInvoiceId' => $result]);
				}
			}

			exit();
		}

		// Show payment popup.
		if(empty($tempInvoiceId)){
			$this->_showErrorFlashMessage($this->EmailMarketingTemplate, __("Email marketing template purchase failed, please re-submit the purchase request by clicking \"Purchase\" button."));
			return $this->redirect(DS .'admin' .DS .'dashboard#' .DS .'admin' .DS .'email_marketing' .DS .'email_marketing_templates');
		}

		$User = ClassRegistry::init('User');
		$userInfo = $User->browseBy($User->primaryKey, $userId, array("Address"));

		$paymentInfo = $tempInvoice['PaymentTempInvoice'] + array('paid_amount' => 0);

		$isTempInvoice = true;

		$billingAddressIndex = ($userInfo['Address'][0]['type'] == 'BILLING') ? 0 : 1;
		$billingAddress = $userInfo['Address'][$billingAddressIndex];
		if($billingAddress['same_as']){
			$billingAddressIndex = ($billingAddressIndex == 1) ? 0 : 1;
			$billingAddress = $userInfo['Address'][$billingAddressIndex];
		}
		if(empty($billingAddress['country_id'])){
			$country = false;
		}else{
			$this->loadModel('Country');
			$Country = ClassRegistry::init("Country");
			$country = $this->Country->browseBy('id', $billingAddress['country_id']);
		}

		$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
		$currency 	 = $this->_getSystemDefaultConfigSetting("Currency", Configure::read('Config.type.payment'));

		$this->set('tempInvoiceId', 	$tempInvoiceId);
		$this->set('userInfo', 			$userInfo['User']);
		$this->set('paymentInfo', 		$paymentInfo);
		$this->set('isTempInvoice', 	$isTempInvoice);
		$this->set('billingAddress', 	$billingAddress);
		$this->set('country', 			$country);
		$this->set('companyName', 		$companyName);
		$this->set('currency', 			$currency);
	}

/**
 * uploadAssets method
 *
 * @return void
 */
	public function admin_uploadAssets() {
		$this->_prepareNoViewAction();
		$result = [];
		if ($this->request->is('post') && !empty($this->request->form) && is_array($this->request->form)){
			$uploadFileMaxSize = $this->_getSystemDefaultConfigSetting('UploadfileSizeLimit', Configure::read('Config.type.system'));
			foreach($this->request->form as $file){
				if($this->EmailMarketingTemplate->isUploadedFile($file)){
					if(intval($this->request->data["EmailMarketingSubscriber"]["subscriber_file"]["size"]) > $uploadFileMaxSize){
						$result['error'] = __('Imported email marketing template asset file (' .$file['name'] .') size is over limit.');

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.error');
						$logMessage  = $result['error'];
						$this->Log->addLogRecord($logType, $logLevel, $logMessage);

						break;
					}else{
						$allowedFileType = ['jpg', 'jpeg', 'png', 'gif'];
						$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
						$ext = strtolower($ext);
						if(!in_array($ext, $allowedFileType)){
							$result['error'] = __('Imported email marketing template asset file (' .$file['name'] .') type is not allowed.');

							$logType 	 = Configure::read('Config.type.emailmarketing');
							$logLevel 	 = Configure::read('System.log.level.error');
							$logMessage  = $result['error'];
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

							break;
						}
						$userId = $this->Session->read('Auth.User.id');
						if($imageUrl = $this->EmailMarketingTemplate->saveTemplateAsset($userId, $file)){
							$result['data'][] = $imageUrl;

							$logType 	 = Configure::read('Config.type.emailmarketing');
							$logLevel 	 = Configure::read('System.log.level.info');
							$logMessage  = __('Email marketing template asset file (' .$file['name'] .') has been uploaded.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

						}else{
							$result['error'] = __('Email marketing template asset file (' .$file['name'] .') cannot be uploaded.');

							$logType 	 = Configure::read('Config.type.emailmarketing');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = $result['error'];
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							break;
						}
					}
				}
			}
		}

		echo json_encode($result);
		exit();
	}

/**
 * customiseTemplateHtml method
 *
 * @return void
 */
	public function admin_customiseTemplateHtml($templateId) {

		if (!$this->EmailMarketingTemplate->exists($templateId)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		$this->loadModel('EmailMarketing.EmailMarketingPurchasedTemplate');

		// Client cannot customize other person's template
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		$emailMarketingUserId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userServiceAccountId);
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingPurchasedTemplate->hasAny(array('email_marketing_template_id' => $templateId, 'email_marketing_user_id' => $emailMarketingUserId))){

				throw new ForbiddenActionException($this->modelClass, "customise");
			}
		}

		$customizedTemplate = $this->EmailMarketingPurchasedTemplate->find('first',array(
			'conditions' => array(
				'email_marketing_template_id' 	=> $templateId,
				'email_marketing_user_id' 		=> $emailMarketingUserId
			),
			'contain' => false
		));

		if ($this->request->is('post') && !empty($this->request->data['gjs-css']) && !empty($this->request->data['gjs-html'])) {

			$this->_prepareNoViewAction();

			$customizedTemplate['EmailMarketingPurchasedTemplate']['customized_html'] = serialize($this->request->data);

			$result = [];
			if ($this->EmailMarketingPurchasedTemplate->customizeTemplate($customizedTemplate['EmailMarketingPurchasedTemplate']['id'], $customizedTemplate)) {
				$result['success'] = $customizedTemplate['EmailMarketingPurchasedTemplate']['id'];

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.info');
				$logMessage  = __('Email marketing template (' .$customizedTemplate['EmailMarketingPurchasedTemplate']['name'] .') has been updated.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			} else {
				$result['error'] = __('Email marketing template cannot be modified.');

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('User (#' .$this->superUserId .') customise email marketing template (' .$customizedTemplate['EmailMarketingPurchasedTemplate']['name'] .') failed. (Passed email marketing template ID: ' .$templateId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
			}
			echo json_encode($result);
			exit();
		}

		$this->layout = 'ajax'; // An absolute empty layout and all the page elements are from embeded editor

		$this->__prepareTemplateEditor();

		$this->set('customiseTemplateId', $templateId);
		$this->set('emailMarketingUserRecordId', $emailMarketingUserId);

		if(empty($customizedTemplate['EmailMarketingPurchasedTemplate']['customized_html'])){
			$template = $this->EmailMarketingTemplate->browseBy($this->EmailMarketingTemplate->primaryKey, $templateId);
			$this->set('template', $template['EmailMarketingTemplate']);
		}else{
			$this->set('template', $customizedTemplate['EmailMarketingPurchasedTemplate']);
		}
	}

/**
 * restore method
 *
 * @return void
 */
	public function admin_restore($customiseTemplateId, $emailMarketingUserRecordId){

		if (!$this->EmailMarketingTemplate->exists($customiseTemplateId)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		$this->loadModel('EmailMarketing.EmailMarketingPurchasedTemplate');

		// Client cannot restore other person's template
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		$emailMarketingUserId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userServiceAccountId);
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if($emailMarketingUserRecordId != $emailMarketingUserId){

				throw new ForbiddenActionException($this->modelClass, "restore");
			}
			if(!$this->EmailMarketingPurchasedTemplate->hasAny(array('email_marketing_template_id' => $customiseTemplateId, 'email_marketing_user_id' => $emailMarketingUserId))){

				throw new ForbiddenActionException($this->modelClass, "restore");
			}
		}

		$this->_prepareNoViewAction();

		if ($this->request->is('post')){

			$template = $this->EmailMarketingPurchasedTemplate->getPurchasedTemplate($emailMarketingUserId, $customiseTemplateId);
			$template['EmailMarketingPurchasedTemplate']['customized_html'] = '';

			$result = [];
			if ($this->EmailMarketingPurchasedTemplate->customizeTemplate($template['EmailMarketingPurchasedTemplate']['id'], $template)) {
				$result['success'] = __('Email marketing template is restored successfully.');

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.info');
				$logMessage  = __('Email marketing template (' .$template['EmailMarketingPurchasedTemplate']['name'] .') has been restored.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			} else {
				$result['error'] = __('Email marketing template cannot be restored.');

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('Restore email marketing template (' .$template['EmailMarketingPurchasedTemplate']['name'] .') failed.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			}
			echo json_encode($result);

		}

		exit();
	}

/**
 * cleanTemplate method
 *
 * @return void
 */
	public function admin_cleanTemplate($templateId){

		if (!$this->EmailMarketingTemplate->exists($templateId)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		// Client cannot clear other person's template
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		$emailMarketingUserId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userServiceAccountId);
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingTemplate->hasAny(array('id' => $templateId, 'email_marketing_user_id' => $emailMarketingUserId))){

				throw new ForbiddenActionException($this->modelClass, "clean");
			}
		}

		$this->_prepareNoViewAction();

		if ($this->request->is('post')){

			$template = $this->EmailMarketingTemplate->getTemplate($templateId, $emailMarketingUserId);
			$template['EmailMarketingTemplate']['html'] = '';
			$template['EmailMarketingTemplate']['markup_list'] = '';

			$result = [];
			if ($this->EmailMarketingTemplate->updateTemplate($templateId, $template)) {
				$result['success'] = __('Email marketing template is cleaned successfully.');

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.info');
				$logMessage  = __('Email marketing template (' .$template['EmailMarketingPurchasedTemplate']['name'] .') has been cleaned.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			} else {
				$result['error'] = __('Email marketing template cannot be cleaned.');

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('Clean email marketing template (' .$template['EmailMarketingTemplate']['name'] .') failed.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			}

			echo json_encode($result);

		}

		exit();
	}


/**
 * savePreviewImage method
 *
 * @return void
 */
	public function admin_savePreviewImage($templateId, $needManuallyOptimise = false){

		if (!$this->EmailMarketingTemplate->exists($templateId)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		$this->_prepareNoViewAction();

		if ($this->request->is('post') && !empty($this->request->data['base64data'])){

			$userId = $this->Session->read('Auth.User.id');

			if($needManuallyOptimise){

				if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){

					$logType 	 = Configure::read('Config.type.emailmarketing');
					$logLevel 	 = Configure::read('System.log.level.warning');
					$logMessage  = __('Saved email marketing template preview image needs manually optimise. (Passed email marketing template ID: ' .$templateId .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				}

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('User (#' .$this->superUserId .') saved email marketing template preview image needs manually optimise. (Passed email marketing template ID: ' .$templateId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			}

			$result = [];
			if ($this->EmailMarketingTemplate->savePreviewImage($templateId, $userId, $this->request->data['base64data'])) {
				$result['success'] = __('Email marketing template preview image is updated successfully.');

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.info');
				$logMessage  = __('Email marketing template preview image has been saved.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			} else {
				$result['error'] = __('Email marketing template preview image cannot be saved.');

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('Save email marketing template preview image failed.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('User (#' .$this->superUserId .') save email marketing template preview image failed. (Passed email marketing template ID: ' .$templateId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
			}
			echo json_encode($result);

		}

		exit();
	}

/**
 * add method
 *
 * @return void
 */
    public function admin_add() {

    	// All POST request is treated as ajax request
        if ($this->request->is('post') && !empty($this->request->data['gjs-css']) && !empty($this->request->data['gjs-html'])) {
        	$this->_prepareNoViewAction();
        	$userId = $this->Session->read('Auth.User.id');
        	$emailMarketingUserId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userId);

        	if(!empty($this->request->data['EmailMarketingTemplate']) && is_string($this->request->data['EmailMarketingTemplate'])){
        		$templateExtraData = json_decode($this->request->data['EmailMarketingTemplate'], true);
        		unset($this->request->data['EmailMarketingTemplate']);
        	}

        	$data = array(
        		'EmailMarketingTemplate' => array(
	        		'email_marketing_user_id' 	=> empty($emailMarketingUserId) ? '' : $emailMarketingUserId,
        			'name' 						=> empty($templateExtraData['name']) ? 'Untitled' : $templateExtraData['name'],
        			'txt_msg' 					=> '',
        			'html' 						=> serialize($this->request->data),
        			'markup_list' 				=> '',
        			'price' 					=> empty($templateExtraData['price']) ? '0.00' : $templateExtraData['price'],
        			'special_price' 			=> '0.00',
        			'for_sale' 					=> empty($templateExtraData['for_sale']) ? 0 : $templateExtraData['for_sale'],
        			'deleted' 					=> empty($templateExtraData['deleted']) ? 0 : $templateExtraData['deleted'],
        			'modified' 					=> date('Y-m-d H:i:s')
	        	)
        	);

        	$result = [];
            if ($insertId = $this->EmailMarketingTemplate->saveTemplate($data)) {
                $result['success'] = $insertId;

                $logType 	 = Configure::read('Config.type.emailmarketing');
                $logLevel 	 = Configure::read('System.log.level.info');
                $logMessage  = __('Email marketing template (' .$data['EmailMarketingTemplate']['name'] .') has been saved.');
                $this->Log->addLogRecord($logType, $logLevel, $logMessage);

            } else {
                $result['error'] = __('Email marketing template cannot be saved.');

                $logType 	 = Configure::read('Config.type.emailmarketing');
                $logLevel 	 = Configure::read('System.log.level.error');
                $logMessage  = __('Save email marketing template (' .$data['EmailMarketingTemplate']['name'] .') failed.');
                $this->Log->addLogRecord($logType, $logLevel, $logMessage);

                $logType 	 = Configure::read('Config.type.emailmarketing');
                $logLevel 	 = Configure::read('System.log.level.critical');
                $logMessage  = __('User (#' .$this->superUserId .') save email marketing template (' .$data['EmailMarketingTemplate']['name'] .') failed.');
                $this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
            }
            echo json_encode($result);
            exit();
        }

        $this->layout = 'ajax'; // An absolute empty layout and all the page elements are from embeded editor

        $this->__prepareTemplateEditor();
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_edit($id = null) {

        if (!$this->EmailMarketingTemplate->exists($id)) {

            throw new NotFoundRecordException($this->modelClass);
        }

        // Client cannot edit other person's template
        $userServiceAccountId = $this->_getCurrentUserServiceAccountId();

        $emailMarketingUserId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($userServiceAccountId);
        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
        	if(!$this->EmailMarketingTemplate->hasAny(array('id' => $id, 'email_marketing_user_id' => $emailMarketingUserId))){

        		throw new ForbiddenActionException($this->modelClass, "edit");
        	}
        }

        if ($this->request->is('post') && !empty($this->request->data['gjs-css']) && !empty($this->request->data['gjs-html'])) {

        	if(!empty($this->request->data['EmailMarketingTemplate']) && is_string($this->request->data['EmailMarketingTemplate'])){
        		$templateExtraData = json_decode($this->request->data['EmailMarketingTemplate'], true);
        		unset($this->request->data['EmailMarketingTemplate']);
        	}

        	$this->_prepareNoViewAction();
        	$data = array(
        		'EmailMarketingTemplate' => array(
        			'id'						=> $id,
	        		'email_marketing_user_id' 	=> empty($emailMarketingUserId) ? '' : $emailMarketingUserId,
        			'name' 						=> empty($templateExtraData['name']) ? 'Untitled' : $templateExtraData['name'],
        			'txt_msg' 					=> '',
        			'html' 						=> serialize($this->request->data),
        			'markup_list' 				=> '',
        			'price' 					=> empty($templateExtraData['price']) ? '0.00' : $templateExtraData['price'],
        			'special_price' 			=> '0.00',
        			'for_sale' 					=> empty($templateExtraData['for_sale']) ? 0 : $templateExtraData['for_sale'],
        			'deleted' 					=> empty($templateExtraData['deleted']) ? 0 : $templateExtraData['deleted'],
        			'modified' 					=> date('Y-m-d H:i:s')
	        	)
        	);

        	$result = [];
            if ($insertId = $this->EmailMarketingTemplate->updateTemplate($id, $data)) {
                $result['success'] = json_encode($templateExtraData);

                $logType 	 = Configure::read('Config.type.emailmarketing');
                $logLevel 	 = Configure::read('System.log.level.info');
                $logMessage  = __('Email marketing template (' .$data['EmailMarketingTemplate']['name'] .') has been updated.');
                $this->Log->addLogRecord($logType, $logLevel, $logMessage);

            } else {
                $result['error'] = __('Email marketing template cannot be modified.');

                $logType 	 = Configure::read('Config.type.emailmarketing');
                $logLevel 	 = Configure::read('System.log.level.error');
                $logMessage  = __('Update email marketing template (' .$data['EmailMarketingTemplate']['name'] .') failed.');
                $this->Log->addLogRecord($logType, $logLevel, $logMessage);

                $logType 	 = Configure::read('Config.type.emailmarketing');
                $logLevel 	 = Configure::read('System.log.level.critical');
                $logMessage  = __('User (#' .$this->superUserId .') update email marketing template (' .$data['EmailMarketingTemplate']['name'] .') failed. (Passed email marketing template ID: ' .$id .')');
                $this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
            }
            echo json_encode($result);
            exit();
        }

        $this->layout = 'ajax'; // An absolute empty layout and all the page elements are from embeded editor

        $this->__prepareTemplateEditor();

        $this->set('templateId', $id);

        $template = $this->EmailMarketingTemplate->browseBy($this->EmailMarketingTemplate->primaryKey, $id);
        $this->set('template', $template['EmailMarketingTemplate']);
    }

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function admin_delete($id = null) {

    	if($this->request->is('post') || $this->request->is('delete')){
	        $this->EmailMarketingTemplate->id = $id;
	        if (!$this->EmailMarketingTemplate->exists()) {

	            throw new NotFoundRecordException($this->modelClass);
	        }

	        // Client cannot delete other person's template
	        $emailMarketingUserId = $this->EmailMarketingTemplate->superUserIdToEmailMarketingUserId($this->superUserId);
	        if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
	        	if(!$this->EmailMarketingTemplate->hasAny(array('id' => $id, 'email_marketing_user_id' => $emailMarketingUserId))){

	        		throw new ForbiddenActionException($this->modelClass, "delete");
	        	}
	        }

	        if($this->EmailMarketingTemplate->deleteTemplate($id)){
	            $this->_showSuccessFlashMessage($this->EmailMarketingTemplate);
	        }else{
	        	$this->_showErrorFlashMessage($this->EmailMarketingTemplate);
	        }
    	}
    }

/**
 * __prepareTemplateEditor method
 *
 * Get necessary data for template editor to initialise
 *
 * @return void
 */
    private function __prepareTemplateEditor(){
    	$userId = $this->Session->read('Auth.User.id');
    	$assets = $this->EmailMarketingTemplate->getTemplateAssets($userId);
    	$this->set('assets', $assets);
    }

/**
 * __getPurchasedTemplate method
 *
 * Get purchased template id array
 *
 * @return purchased template id array
 */
    private function __getPurchasedTemplate($emailMarketingUserRecordId, $forceUpdatePurchasedTemplateArray = false){
    	if($this->Session->check('purchasedTemplate') && !$forceUpdatePurchasedTemplateArray){
    		$purchasedTemplate = $this->Session->read('purchasedTemplate');
    		if(empty($purchasedTemplate) || !is_array($purchasedTemplate)){
    			$this->Session->delete('purchasedTemplate');
    			return $this->__getPurchasedTemplate($emailMarketingUserRecordId);
    		}
    	}else{
    		if($forceUpdatePurchasedTemplateArray){
    			$this->Session->delete('purchasedTemplate');
    		}
    		$this->loadModel('EmailMarketing.EmailMarketingPurchasedTemplate');
    		$purchasedTemplate = $this->EmailMarketingPurchasedTemplate->getPurchasedTemplate($emailMarketingUserRecordId);
    		if(!empty($purchasedTemplate[0]['EmailMarketingPurchasedTemplate'])){
    			$purchasedTemplate = Set::classicExtract($purchasedTemplate, '{n}.EmailMarketingPurchasedTemplate.email_marketing_template_id');
    			$this->Session->write('purchasedTemplate', $purchasedTemplate);
    		}else{
    			$purchasedTemplate = [];
    		}
    	}
    	return $purchasedTemplate;
    }
}
