<?php

App::uses('PaymentAppController', 'Payment.Controller');
App::uses('ExtendPHP', 'Util');

class PaymentInvoicesController extends PaymentAppController {

	public $paginate = array(
		'fields' => array(
			'PaymentInvoice.*',
			'User.first_name',
			'User.last_name',
			'AdminCreatedUser.first_name',
			'AdminCreatedUser.last_name',
			'AdminModifiedUser.first_name',
			'AdminModifiedUser.last_name'
		),
		'joins' => array(
			array(
				'table' => 'users',
				'alias' => 'User',
				'type'  => 'inner',
				'conditions' => array(
					'PaymentInvoice.user_id = User.id'
				)
			),
			array(
				'table' => 'users',
				'alias' => 'AdminCreatedUser',
				'type'  => 'inner',
				'conditions' => array(
					'PaymentInvoice.created_by = AdminCreatedUser.id'
				)
			),
			array(
				'table' => 'users',
				'alias' => 'AdminModifiedUser',
				'type'  => 'inner',
				'conditions' => array(
					'PaymentInvoice.modified_by = AdminModifiedUser.id'
				)
			)
		),
		'limit'   => 10,
		'order'   => array("PaymentInvoice.id" => "DESC"),
		'contain' => false
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->loadModel('User');
	}

/**
 * index method
 *
 * @return void
 */
	public function admin_index($type = null){

		$actionPaid 		= Configure::read('Payment.invoice.status.paid');
		$actionUnpaid 		= Configure::read('Payment.invoice.status.unpaid');
		$actionRefund 		= Configure::read('Payment.invoice.status.refund');
		$allowedActionType 	= [$actionPaid, $actionUnpaid, $actionRefund];
		if(!empty($type) && !in_array($type, $allowedActionType)){

			throw new NotFoundRecordException ($this->modelClass);
		}

		if($type == $actionUnpaid){
			$this->paginate['conditions'] = array(
				'NOT' => array("PaymentInvoice.status" => [$actionPaid, $actionRefund])
			);
		}elseif($type == $actionPaid){
			$this->paginate['conditions'] = array(
				'PaymentInvoice.status' => $actionPaid
			);
		}else{
			$this->paginate['conditions'] = array(
				'PaymentInvoice.status' => $actionRefund
			);
		}

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			$this->paginate['conditions'] = am($this->paginate['conditions'], array('PaymentInvoice.user_id' => $this->superUserId));
		}

		$this->Paginator->settings = $this->paginate;

		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse());
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['PaymentInvoice.id']);

		$this->set('actionPaid', 	$actionPaid);
		$this->set('actionUnpaid', 	$actionUnpaid);
		$this->set('actionRefund', 	$actionRefund);
		$this->set('invoiceType', 	$type);
	}

/**
 * Unpaid invoice index method
 *
 * @return void
 */
	public function admin_unpaidInvoiceIndex(){
		$unpaidInvoiceType = Configure::read('Payment.invoice.status.unpaid');
		return $this->redirect("/admin/payment/payment_invoices/index/{$unpaidInvoiceType}");
	}

/**
 * Paid invoice index method
 *
 * @return void
 */
	public function admin_paidInvoiceIndex(){
		$paidInvoiceType = Configure::read('Payment.invoice.status.paid');
		return $this->redirect("/admin/payment/payment_invoices/index/{$paidInvoiceType}");
	}

/**
 * Refund invoice index method
 *
 * @return void
 */
	public function admin_refundInvoiceIndex(){
		$refundInvoiceType = Configure::read('Payment.invoice.status.refund');
		return $this->redirect("/admin/payment/payment_invoices/index/{$refundInvoiceType}");
	}

/**
 * view method (HTML version invoice)
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {

		if (! $this->PaymentInvoice->exists ( $id )) {

			throw new NotFoundRecordException ( $this->modelClass );
		}

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->PaymentInvoice->hasAny(['id' => $id, 'user_id' => $this->superUserId])){

				throw new NotFoundRecordException ($this->modelClass);
			}
		}

		$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $id, array('ClientUser' => array('Address' => array('Country'))));

		if(isset($invoice["PaymentInvoice"]["content"])){
			$invoice["PaymentInvoice"]["content"] = html_entity_decode ( $invoice["PaymentInvoice"]["content"], ENT_QUOTES, 'UTF-8' );
		}

		$companyName 	= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
		$companyAddress = $this->_getSystemDefaultConfigSetting('CompanyAddress', Configure::read('Config.type.system'));
		$companyEmail 	= $this->_getSystemDefaultConfigSetting('companyEmail', Configure::read('Config.type.system'));

		$this->set ( compact ( 'invoice', 'companyName', 'companyAddress', 'companyEmail' ) );

	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add($purchaseCode = "", $payerUserId = null){

		if ($this->request->is('post') && isset($this->request->data["PaymentInvoice"]) && !empty($this->request->data["PaymentInvoice"])) {

			$this->request->data["PaymentInvoice"]["created_by"] 		= $this->Session->read('Auth.User.id'); // Get Client group user ID, not service group user ID
			$this->request->data["PaymentInvoice"]["modified_by"] 		= $this->request->data["PaymentInvoice"]["created_by"]; // Set create invoice user as modified user too by default

			$this->request->data["PaymentInvoice"]["status"] 			= Configure::read('Payment.invoice.status.pending');
			$this->request->data["PaymentInvoice"]["created"] 			= date('Y-m-d H:i:s', strtotime('now'));
			$this->request->data["PaymentInvoice"]["modified"] 			= date('Y-m-d H:i:s', strtotime('now'));

			$this->request->data["PaymentInvoice"]["is_auto_created"] 	= 0;

			if(isset($this->request->data["PaymentInvoice"]["content"])){
				$this->request->data["PaymentInvoice"]["content"] = $this->Util->sanitizeHTML ( $this->request->data["PaymentInvoice"]["content"] );
				$this->request->data["PaymentInvoice"]["content"] = htmlentities ( utf8_encode ( $this->request->data["PaymentInvoice"]["content"] ), ENT_QUOTES, 'UTF-8' );
			}

			$ajaxResult = FALSE;
			if ($this->PaymentInvoice->saveInvoice($this->request->data)) {

				if($this->admin_generateInvoiceFile($this->PaymentInvoice->getInsertID()) === FALSE){
					$errorMsg = __("Payment Invoice details has been saved, but invoice file cannot be created. This issue has been reported and we will start to look into it ASAP. Sorry about the inconvenience.");
					$ajaxResult = json_encode(array('status' => Configure::read('System.variable.error'), 'message' => $errorMsg));
					$this->_showErrorFlashMessage($this->PaymentInvoice, $errorMsg);
				}else{
					$ajaxResult = strval($this->PaymentInvoice->id);
					$this->_showSuccessFlashMessage($this->PaymentInvoice);
				}

			} else {
				$ajaxResult = json_encode(array('status' => Configure::read('System.variable.error'), 'message' => __("Create payment invoice failed")));
				$this->_showErrorFlashMessage($this->PaymentInvoice);
			}

			if($this->request->is('ajax')){
				echo $ajaxResult;
				exit();
			}
		}

		if(!isset($this->request->data['PaymentInvoice']['number']) && !empty($purchaseCode)){
			$this->request->data['PaymentInvoice']['number'] = $this->PaymentInvoice->generateInvoiceNumber($purchaseCode);
		}

		if(isset($this->request->data["PaymentInvoice"]["content"])){
			$this->request->data["PaymentInvoice"]["content"] = html_entity_decode ( $this->request->data["PaymentInvoice"]["content"], ENT_QUOTES, 'UTF-8' );
		}

		$clientList = $this->User->listAll(array('Group.name' => Configure::read('System.client.group.name')), $payerUserId);
		$this->set('userList', $clientList);

		$this->render('admin_edit');
	}

/**
 * edit method (cannot edit after getting paid)
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {

		if (!$this->PaymentInvoice->exists($id)) {

    		throw new NotFoundRecordException($this->modelClass);
    	}

    	if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["PaymentInvoice"]) && !empty($this->request->data["PaymentInvoice"])) {

    		$this->request->data["PaymentInvoice"]["modified_by"] 	= $this->Session->read('Auth.User.id'); // Get Client group user ID, not service group user ID

    		$this->request->data["PaymentInvoice"]["status"] 		= (isset($this->request->data["PaymentInvoice"]["paid_amount"]) && $this->request->data["PaymentInvoice"]["paid_amount"] > 0) ? (($this->request->data["PaymentInvoice"]["paid_amount"] == $this->request->data["PaymentInvoice"]["amount"]) ? Configure::read('Payment.invoice.status.paid') : Configure::read('Payment.invoice.status.partial_paid')) : Configure::read('Payment.invoice.status.pending');
    		$this->request->data["PaymentInvoice"]["created"] 		= date('Y-m-d H:i:s', strtotime('now'));
    		$this->request->data["PaymentInvoice"]["modified"] 		= date('Y-m-d H:i:s', strtotime('now'));
    		$this->request->data["PaymentInvoice"]["id"]			= $id;

    		if(isset($this->request->data["PaymentInvoice"]["content"])){
    			$this->request->data["PaymentInvoice"]["content"] = $this->Util->sanitizeHTML ( $this->request->data["PaymentInvoice"]["content"] );
    			$this->request->data["PaymentInvoice"]["content"] = htmlentities ( utf8_encode ( $this->request->data["PaymentInvoice"]["content"] ), ENT_QUOTES, 'UTF-8' );
    		}

    		if ($this->PaymentInvoice->updateInvoice($id, $this->request->data)) {

    			if($this->admin_generateInvoiceFile($id) === FALSE){
    				$this->_showErrorFlashMessage($this->PaymentInvoice, __("Payment Invoice details has been updated, but invoice file cannot be re-created. This issue has been reported and we will start to look into it ASAP. Sorry about the inconvenience."));
    			}else{
    				$this->_showSuccessFlashMessage($this->PaymentInvoice);
    			}

    		} else {
    			$this->_showErrorFlashMessage($this->PaymentInvoice);
    		}

    	}

    	$userList 	= $this->User->listAll(array('Group.name' => 'Client'));
    	$invoice 	= $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $id);

    	if(isset($invoice["PaymentInvoice"]["content"])){
    		$invoice["PaymentInvoice"]["content"] = html_entity_decode ( $invoice["PaymentInvoice"]["content"], ENT_QUOTES, 'UTF-8' );
    	}

		$this->set(compact('invoice', 'userList'));
	}

	//Invoice should not be deleted. Only can modify it. commented by Charles - 2017-11-27
// /**
//  * delete method (cannot delete after getting paid)
//  *
//  * @throws NotFoundException
//  * @param string $id
//  * @return void
//  */
// 	public function admin_delete($id = null) {

// 		if($this->request->is('post') || $this->request->is('delete')){
// 			$this->PaymentInvoice->id = $id;
// 	    	if (!$this->PaymentInvoice->exists()) {
// 	    		throw new NotFoundException(__('Invalid invoice'));
// 	    	}

// 	    	if($this->PaymentInvoice->deleteInvoice($id)){
// 	    		$this->_showSuccessFlashMessage($this->PaymentInvoice);
// 	    	}else{
// 	    		$this->_showErrorFlashMessage($this->PaymentInvoice);
// 	    	}
// 		}
// 	}

// /**
//  * delete method
//  *
//  * @throws NotFoundException
//  * @return void
//  */
// 	public function admin_batchDelete() {
// 		if (($this->request->is('post') || $this->request->is('delete')) && $this->request->is('ajax')){
// 			if(isset($this->request->data['batchIds']) && !empty($this->request->data['batchIds']) && is_array($this->request->data['batchIds'])){
// 				$batchDeleteDone = true;
// 				foreach($this->request->data['batchIds'] as $id){
// 					$this->PaymentInvoice->id = $id;
// 					if (!$this->PaymentInvoice->exists()) {
// 						throw new NotFoundException(__('Invalid invoice'));
// 						$batchDeleteDone = false;
// 						break;
// 					}
// 					if (!$this->PaymentInvoice->deleteInvoice($id)) {
// 						$batchDeleteDone = false;
// 					}
// 				}
// 				if($batchDeleteDone){
// 					$this->_showSuccessFlashMessage($this->PaymentInvoice, __("Selected invoices have been batch deleted."));
// 				}else{
// 					$this->_showErrorFlashMessage($this->PaymentInvoice, __("Selected invoices cannot be batch deleted."));
// 				}
// 			}
// 		}
// 	}

/**
 * email method (HTML version invoice)
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_email($id = null) {

		$userType = stristr($this->superUserGroup, Configure::read('System.client.group.name')) ? __('yourself') : __('client');
		$this->set('userType', $userType);

		if($this->request->is('post')){

			if (! $this->PaymentInvoice->exists ( $id )) {

				throw new NotFoundRecordException ( $this->modelClass );
			}

			$invoice 		= $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $id, false);

			if($this->superUserId != $invoice['PaymentInvoice']['user_id']){

				throw new ForbiddenActionException ( $this->modelClass, "email" );
			}

			$invoiceFile 	= $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);

			// Step 1: Search for invoice
			$extendPHP = new ExtendPHP();
			if(!$extendPHP->isUrlExists($invoiceFile)){

				$this->_showErrorFlashMessage(null, __('Sorry! Invoice file cannot be found. This issue has been reported and we will start to look into it ASAP.'));

				$logLevel 	 = Configure::read('System.log.level.critical');
				$logType 	 = Configure::read('Config.type.payment');
				$logMessage  = __('Invoice file cannot be found. (Invoice File: ' .$invoiceFile .', Invoice ID: ' .$id .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			}else{

				// Step 2: Email the invoice file
				$postData = array(
					'data' => array(
						'PaymentInvoice' => array(
							'id' => $id
						)
					)
				);

				if($this->requestAction(DS .'system_email' .DS .'sendInvoiceEmail' .DS .$id, $postData)){
					$invoice['PaymentInvoice']['is_emailed_client'] = 1;
					if ($this->PaymentInvoice->updateInvoice($id, array('PaymentInvoice' => $invoice['PaymentInvoice']))) {
						$this->_showSuccessFlashMessage(null,__('The invoice has been sent to your security email address as an attachment.'));
					}else{
						$this->_showErrorFlashMessage(null, __('Sorry! We cannot send the invoice email for now. This issue has been reported and we will start to look into it ASAP.'));

						$logLevel 	 = Configure::read('System.log.level.critical');
						$logType 	 = Configure::read('Config.type.payment');
						$logMessage  = __('Invoice cannot be updated. (Invoice: ' .json_encode($invoice) .', Invoice ID: ' .$id .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
					}
				}else{
					$this->_showErrorFlashMessage(null, __('We cannot send the invoice email for now. This issue has been reported and we will start to look into it ASAP. Sorry about the inconvenience.'));

					$logLevel 	 = Configure::read('System.log.level.critical');
					$logType 	 = Configure::read('Config.type.payment');
					$logMessage  = __('Cannot send the invoice email. (Invoice: ' .json_encode($invoice) .', Invoice ID: ' .$id .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
				}

			}

		}

	}

/**
 * email method
 *
 * @throws NotFoundException
 * @return void
 */
	public function admin_batchEmail() {
		if ($this->request->is('post') && $this->request->is('ajax')){
			if(isset($this->request->data['batchIds']) && !empty($this->request->data['batchIds']) && is_array($this->request->data['batchIds'])){
				$batchDeleteDone = true;
				$errorMessage = [];
				$extendPHP = new ExtendPHP();
				foreach($this->request->data['batchIds'] as $id){
					if (! $this->PaymentInvoice->exists ( $id )) {
						$errorMessage[] = __('Invoice cannot be found.');

						throw new NotFoundRecordException($this->modelClass);

						$batchDeleteDone = false;
						break;
					}

					$invoice 		= $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $id, false);
					$invoiceFile 	= $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);

					// Step 1: Search for invoice
					if(!$extendPHP->isUrlExists($invoiceFile)){

						$batchDeleteDone = false;
						$errorMessage[] = __('Sorry! Invoice file (#' .$invoice['PaymentInvoice']['number'] .') cannot be found. This issue has been reported and we will start to look into it ASAP.');

						$logLevel 	 = Configure::read('System.log.level.critical');
						$logType 	 = Configure::read('Config.type.payment');
						$logMessage  = __('Invoice file cannot be found. (Invoice File: ' .$invoiceFile .', Invoice ID: ' .$id .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					}else{

						// Step 2: Email the invoice file
						$postData = array(
							'data' => array(
								'PaymentInvoice' => array(
									'id' => $id
								)
							)
						);

						if($this->requestAction(DS .'system_email' .DS .'sendInvoiceEmail' .DS .$id, $postData)){
							$invoice['PaymentInvoice']['is_emailed_client'] = 1;
							if (!$this->PaymentInvoice->updateInvoice($id, array('PaymentInvoice' => $invoice['PaymentInvoice']))) {
								$errorMessage[] =  __('Sorry! We cannot send the invoice (#' .$invoice['PaymentInvoice']['number'] .') email for now. This issue has been reported and we will start to look into it ASAP.');
								$batchDeleteDone = false;

								$logLevel 	 = Configure::read('System.log.level.critical');
								$logType 	 = Configure::read('Config.type.payment');
								$logMessage  = __('Invoice cannot be updated. (Invoice: ' .json_encode($invoice) .', Invoice ID: ' .$id .')');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							}
						}else{
							$errorMessage[] = __('We cannot send the invoice (#' .$invoice['PaymentInvoice']['number'] .') email for now. This issue has been reported and we will start to look into it ASAP. Sorry about the inconvenience.');
							$batchDeleteDone = false;

							$logLevel 	 = Configure::read('System.log.level.critical');
							$logType 	 = Configure::read('Config.type.payment');
							$logMessage  = __('Cannot send the invoice email. (Invoice: ' .json_encode($invoice) .', Invoice ID: ' .$id .')');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
						}

					}
				}
				if($batchDeleteDone){
					$this->_showSuccessFlashMessage($this->PaymentInvoice, __("Selected invoices have been batch emailed."));
				}else{
					$errorMessage = empty($errorMessage) ? __('Sorry! Error occurred while sending invoices. If there is any invoice missing, please try re-email it.') : implode("<br />", $errorMessage);
					$this->_showErrorFlashMessage(null, $errorMessage);
				}
			}
		}
	}

/**
 * Generate invoice in controller because the library we use generates PDF while render HTML
 *
 * @param string $invoiceId
 */
	public function admin_generateInvoiceFile($invoiceId){

		$this->_prepareNoViewAction();

		$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, array('ClientUser' => array('Address' => array('Country'))));

		$s3Path 			= $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number']);
		$s3Path 			= $s3Path. '/';
		$invoiceFile 		= $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);
		$invoiceFileName 	= basename($invoiceFile);

		$extendPHP = new ExtendPHP();

		$absoluteFilePath	= WWW_ROOT .'files' .DS .'tmp' .DS;

		if (!is_dir($absoluteFilePath)) {
			if (!mkdir($absoluteFilePath, 0755, true)) {

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('User (#' .$this->superUserId .') invoice file temporary creation path (' .$absoluteFilePath .') cannot be created. (Passed payment invoice ID: ' .$invoiceId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				return false;
			}
		}else{
			if($extendPHP->isUrlExists($invoiceFile)){

				$s3Action 	= Configure::read('System.aws.s3.action.delete');

				try{

					$this->amazonS3StorageManagement($s3Action, $s3Path, array($invoiceFileName));

				}catch(AmazonS3Exception $exception){

					return false;
				}
			}
		}

		if(isset($invoice["PaymentInvoice"]["content"])){
			$invoice["PaymentInvoice"]["content"] = html_entity_decode ( $invoice["PaymentInvoice"]["content"], ENT_QUOTES, 'UTF-8' );
		}

		$companyName 	= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
		$companyAddress = $this->_getSystemDefaultConfigSetting('CompanyAddress', Configure::read('Config.type.system'));
		$companyEmail 	= $this->_getSystemDefaultConfigSetting('CompanyEmail', Configure::read('Config.type.system'));
		$taxGSTRate 	= $this->_getSystemDefaultConfigSetting("TaxGSTRate", Configure::read('Config.type.payment'));

		// Get PDF file content and assign it into the controller response object for PDF component to use
		$view = new View($this, false);
		$view->set ( compact ( 'invoice', 'companyName', 'companyAddress', 'companyEmail', 'taxGSTRate' ) );
		$view->viewPath = 'PaymentInvoices' .DS .'pdf';
		$viewOutput = $view->render('invoice_template', 'ajax');
		$this->response->body($viewOutput);

		// Write HTML content into PDF file
		$configuration = array(
			'format' 		=> 'A4',
			'font_size' 	=> 5,
			'font' 			=> 'Open Sans',
			'margin_left' 	=> 0,
			'margin_right' 	=> 0,
			'margin_top' 	=> 0,
			'margin_bottom' => 0,
			'margin_header' => 0,
			'margin_footer' => 0
		);
		if(!isset($this->Mpdf) || empty($this->Mpdf)){
			App::import('Component', 'Mpdf');
			$this->Mpdf = new MpdfComponent(new ComponentCollection($this));
		}
		$this->Mpdf->init($configuration);
		$this->Mpdf->CSSselectMedia = 'min-width: 768px';
		$this->Mpdf->setFilename($invoiceFileName);
		$this->Mpdf->setOutput('S');
		$fileContent = $this->Mpdf->saveFile($this);

		return $this->PaymentInvoice->outputFile($s3Path, $absoluteFilePath.$invoiceFileName, $fileContent);
	}

/**
 * Generate invoice number
 *
 * @param string $invoiceId
 */
	public function admin_generateInvoiceNumber(){

		if($this->request->is('post') || $this->request->is('put')){

			$invoiceNumber = $this->PaymentInvoice->generateInvoiceNumber($this->request->data['PaymentInvoice']['purchaseCode']);
			if(empty($invoiceNumber)){
				echo json_encode(array(
					'status' 	=> Configure::read('System.log.level.error'),
					'message' 	=> __('Generate invoice number failed. This error has been reported automatically.')
				));

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('User (#' .$this->superUserId .') cannot generate invoice number. (Passed payment purchase code: ' .$this->request->data['PaymentInvoice']['purchaseCode'] .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			}else{
				$callbackJSFunction = <<<CALLBACK
					var invoiceNumber = '{$invoiceNumber}';
					var generateInvoiceNumberBtn = $('#PaymentInvoiceButton');
					generateInvoiceNumberBtn.next('label').removeClass('hide');
					generateInvoiceNumberBtn.next('label').next('input').removeClass('hide');
					generateInvoiceNumberBtn.next('label').next('input').val(invoiceNumber);
					generateInvoiceNumberBtn.closest('form').find('input[type="hidden"][name="data[PaymentInvoice][number]"]').val(invoiceNumber);
					generateInvoiceNumberBtn.css('display', 'none');
					bootbox.hideAll();
CALLBACK;
				echo $callbackJSFunction;
			}
			exit();
		}

		$paymentCodes = Configure::read('Payment.code');
		$purchaseCodeList = $this->__generatePurchaseCodeList($paymentCodes);

		$this->set('purchaseCodeList', $purchaseCodeList);
	}

/**
 * Recursively generate purchase code list from PHP configuration
 *
 * @param string $invoiceId
 */
	private function __generatePurchaseCodeList($paymentCodes, $prefixName = null, $result = array()){

		if(!empty($paymentCodes) && is_array($paymentCodes)){

			foreach($paymentCodes as $name => $code){
				if(!empty($code) && is_array($code)){
					$prefixName = $name;
					$result = $this->__generatePurchaseCodeList($code, $prefixName, $result);
					unset($prefixName);
				}else{
					$result[$code] = (empty($prefixName) ? '' : $prefixName .'_') . $name;
					$result[$code] = Inflector::humanize($result[$code]);
					$result[$code] = __($result[$code]);
				}
			}

		}

		return $result;
	}

}
