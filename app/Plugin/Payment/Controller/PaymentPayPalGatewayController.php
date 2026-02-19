<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;

use PayPal\Api\Refund;
use PayPal\Api\Sale;

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Api\FundingInstrument;
use PayPal\Api\CreditCard;

use PayPal\Api\Agreement;
use PayPal\Api\AgreementDetails;
use PayPal\Api\AgreementStateDescriptor;

use \PayPal\Api\VerifyWebhookSignature;
use \PayPal\Api\VerifyWebhookSignatureResponse;
use \PayPal\Api\WebhookEvent;

App::uses('PaymentAppController', 'Payment.Controller');

App::uses('DateTimeHandler', 'Util');

class PaymentPayPalGatewayController extends PaymentAppController {

	public function beforeFilter() {

		$this->Auth->allow('admin_paypalIPN');

		$this->Security->unlockedActions = array(
			'admin_createPayment',
			'admin_executePayment',
			'admin_executeAgreement',
			'admin_paypalIPN'
		);
		$this->Security->requireAuth = array(
			'admin_expressCheckout',
			'admin_createPayment',
			'admin_recurringPayment',
			'admin_refund',
			'admin_handleRecurringAgreement',
			'admin_getRecurringAgreementDetail',
			'admin_getRecurringAgreementDetails'
		);

		parent::beforeFilter();

	}

	public function admin_expressCheckout ($pendingInvoiceId = null, $isTempInvoice = false){

		// Gether payment info

		$paymentInfo = null;
		$userInfo 	 = null;
		$this->loadModel ( 'Payment.PaymentInvoice' );
		$this->loadModel ( 'Payment.PaymentTempInvoice' );
		if(empty($pendingInvoiceId)){
			$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
			return false;
		}else{
			if($isTempInvoice){
				if ($this->PaymentTempInvoice->exists($pendingInvoiceId)){
					list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
				}else{
					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
					return false;
				}
			}else{
				$isTempInvoice = false;
				if($this->PaymentInvoice->exists($pendingInvoiceId)){
					list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
				}else{
					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
					return false;
				}
			}
		}

		if(empty($userInfo['Address'])){
			$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Billing Address is missing."));
			return false;
		}else{

			list($billingAddress, $country) = $this->__getBillingAddressDetails($userInfo);
			if($country === false){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Billing Address country field is empty."));
				return false;
			}
		}

		$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
		$currency 	 = $this->_getSystemDefaultConfigSetting("Currency", Configure::read('Config.type.payment'));

		$this->set('companyName', 		$companyName);
		$this->set('currency', 			$currency);
		$this->set('userInfo', 			$userInfo);
		$this->set('billingAddress', 	$billingAddress);
		$this->set('country', 			$country);
		$this->set('paymentInfo', 		$paymentInfo);
		$this->set('pendingInvoiceId', 	$pendingInvoiceId);
		$this->set('isTempInvoice', 	$isTempInvoice);
	}

	public function admin_createPayment($pendingInvoiceId = null, $isTempInvoice = false){

		if (!empty($pendingInvoiceId) && $this->RequestHandler->ext == "json"){

			// Gether payment info
			$paymentInfo = null;
			$userInfo 	 = null;
			$this->loadModel ( 'Payment.PaymentInvoice' );
			$this->loadModel ( 'Payment.PaymentTempInvoice' );
			if(empty($pendingInvoiceId)){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
				return false;
			}else{
				if($isTempInvoice){
					if ($this->PaymentTempInvoice->exists($pendingInvoiceId)){
						list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);

						$paymentInfo['paid_amount'] = 0; // Temp invoice doesn't have paid amount field

					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
						return false;
					}
				}else{
					$isTempInvoice = false;
					if($this->PaymentInvoice->exists($pendingInvoiceId)){
						list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
						return false;
					}
				}
			}

			// Create billing address object
			if(empty($userInfo['Address'])){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Billing Address is missing."));
				return false;
			}else{
				list($billingAddress, $country) = $this->__getBillingAddressDetails($userInfo);
				if($country === false){
					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Billing Address country field is empty."));
					return false;
				}

				$addr = new Address();
				$addr->setLine1($billingAddress['street_address'])
					 ->setCity($billingAddress['suburb'])
					 ->setState($billingAddress['state'])
					 ->setPostalCode($billingAddress['postcode'])
					 ->setCountryCode($country['Country']['code']);
				if(!empty($userInfo['phone'])){
					$addr->setPhone($userInfo['phone']);
				}
			}

			// Generate Payer Info
			$payerInfo = new PayerInfo();
			$payerInfo->setBillingAddress($addr)
					  ->setFirstName($userInfo['first_name'])
					  ->setLastName($userInfo['last_name'])
					  ->setEmail($userInfo['email']);

			// A resource representing a Payer that funds a payment
			// For direct credit card payments, set payment method to 'credit_card' and add an array of funding instruments.
			$payer = new Payer();
			$payer->setPaymentMethod("paypal")
				  ->setPayerInfo($payerInfo);

			$payAmount  = $paymentInfo['amount'] - $paymentInfo['paid_amount']; //TODO handle partial paid invoice, not sure how to generate invoice yet
			$taxGSTRate = $this->_getSystemDefaultConfigSetting("TaxGSTRate", Configure::read('Config.type.payment'));
			$tax	    = $payAmount * $taxGSTRate;

			// Use this optional field to set additional payment information such as tax, shipping charges etc.
			$details = new Details();
			$details->setShipping('0')
					->setTax($tax)
					->setSubtotal($payAmount);

			// Lets you specify a payment amount.
			// You can also specify additional details such as shipping, tax.
			$currency = $this->_getSystemDefaultConfigSetting("Currency", Configure::read('Config.type.payment'));
			$amount = new Amount();
			$amount->setCurrency($currency)
				   ->setTotal($payAmount + $tax)
				   ->setDetails($details);

			// A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
			$transaction = new Transaction();
			$transaction->setAmount($amount);
			if($isTempInvoice && !empty($paymentInfo['purchase_code'])){
				$transaction->setDescription($paymentInfo['purchase_code']); //TODO put more meaningful info in there, e.g. add prepaid amount
			}
			if(!$isTempInvoice){
				$transaction->setInvoiceNumber($paymentInfo['number']);
// 				$transaction->setInvoiceNumber(String::uuid()); // Debug code
			}

			$baseUrl = Router::url('/', true);
			$tempInvoiceParam = $isTempInvoice ? '/1' : '';
			$redirectUrls = new RedirectUrls();
			$redirectUrls->setReturnUrl("{$baseUrl}admin/payment/payment_pay_pal_gateway/executePayment/{$pendingInvoiceId}{$tempInvoiceParam}.json?success=true")
						 ->setCancelUrl("{$baseUrl}admin/payment/payment_pay_pal_gateway/executePayment/{$pendingInvoiceId}{$tempInvoiceParam}.json?success=false");

			// A Payment Resource; create one using the above types and intent set to sale 'sale'
			$payment = new Payment();
			$payment->setIntent("sale")
					->setPayer($payer)
					->setRedirectUrls($redirectUrls)
					->setTransactions(array($transaction));

			// Create a payment by calling the payment->create() method with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
			// The return object contains the state.
			try {
				$apiContext = $this->__getApiContext();

				$payment->create($apiContext);

				$paymentId = $payment->getId();

				// Save payment transaction info into DB
				if(!empty($paymentId)){

					$paymentTransactions	= $payment->getTransactions();
					$transactionDone 		= array_shift($paymentTransactions);
					$paidAmount 			= $transactionDone->getAmount()->getTotal();
					$paidTimestamp 			= date('Y-m-d H:i:s', strtotime($payment->getCreateTime()));
					$paymentUpdateTimstamp  = $payment->getUpdateTime();
					$paymentUpdateTimstamp 	= empty($paymentUpdateTimstamp) ? '' : date('Y-m-d H:i:s', strtotime($paymentUpdateTimstamp));
					$paymentState 			= $payment->getState();
					$paymentIntent			= $payment->getIntent();

					$paymentTransaction = array(
						'PaymentPayPalGateway' => array(
							'payment_payer_id' 		=> $userInfo['PaymentPayer']['id'],
							'payment_invoice_id'	=> $paymentInfo['id'],
							'is_temp'				=> $isTempInvoice ? 1 : 0,
							'amount'				=> $paidAmount,
							'tax'					=> $tax,
							'status'				=> $paymentState,
							'payment_id' 			=> $paymentId,
							'intent'				=> $paymentIntent,
							'created'				=> $paidTimestamp,
							'modified'				=> $paymentUpdateTimstamp
						)
					);
					$this->loadModel ( 'Payment.PaymentPayPalGateway' );
					if(!$this->PaymentPayPalGateway->saveTransaction($paymentTransaction)){

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.error');
						$logMessage  = __('Transaction record cannot be saved.');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage);

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('User (#' .$this->superUserId .') transaction record cannot be saved. (Invoice ID: ' .$invoiceId .', payment ID: ' .$paymentId .', payment intent: ' .$paymentIntent .').');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
					}
				}

			} catch (PayPal\Exception\PayPalConnectionException $ex) {

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('PayPal express checkout create payment error occurred. This has been reported and we will look into it ASAP.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("PayPal Create Payment Exception: ") .'<br />'.
							   __("Error Code: ") . $ex->getCode() .'<br />'.
							   __("Error Data: ") . $ex->getData() .'<br />'.
						       __("Error Message: ") . $ex->getMessage() .'<br />'.
						       __("Line Number: ") .$ex->getLine() .'<br />'.
						       __("Trace: ") .$ex->getTraceAsString() .'<br />'.
						       __('Log Data: ') .json_encode(@$ex->getData());
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				exit(1);

			} catch (Exception $e) {

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('PayPal express checkout create payment error occurred. This has been reported and we will look into it ASAP.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("PayPal Create Payment Exception: ") .'<br />'.
						       __("Error Message: ") . $e->getMessage() .'<br />'.
						       __("Line Number: ") .$e->getLine() .'<br />'.
						       __("Trace: ") .$e->getTraceAsString();
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
			}

			foreach ($payment->getLinks() as $link){
				if($link->getRel() == "approval_url"){
					$approvalUrl = $link->getHref();
					break;
				}
			}

			$this->set('payment', $payment->toJSON());
			$this->set('_serialize', array('payment'));

		}else{

			throw new BadControllerRequestException($this->modelClass);
		}
	}

	public function admin_executePayment($pendingInvoiceId = null, $isTempInvoice = false){

		if (!empty($pendingInvoiceId) && $this->RequestHandler->ext == "json" && !empty($this->request->data['paymentID']) && !empty($this->request->data['payerID']) && $this->request->is('post')){

			if(!empty($this->request->query['success']) && $this->request->query['success'] === "true"){

				$apiContext = $this->__getApiContext();
				$payment = Payment::get($this->request->data['paymentID'], $apiContext);

				$execution = new PaymentExecution();
				$execution->setPayerId($this->request->data['payerID']);

				try {
					$result = $payment->execute($execution, $apiContext);

					$payment = Payment::get($this->request->data['paymentID'], $apiContext);
				} catch (Exception $ex) {

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.error');
					$logMessage  = __('PayPal express checkout execute payment error occurred. This has been reported and we will look into it ASAP.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("PayPal Execute Payment Exception: ") .'<br />'.
							       __("Error Message: ") . $ex->getMessage() .'<br />'.
							       __("Line Number: ") .$ex->getLine() .'<br />'.
							       __("Trace: ") .$ex->getTraceAsString();
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
					exit(1);

				} catch (Exception $e) {

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.error');
					$logMessage  = __('PayPal express checkout execute payment error occurred. This has been reported and we will look into it ASAP.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("PayPal Execute Payment Exception: ") .'<br />'.
							       __("Error Message: ") . $e->getMessage() .'<br />'.
							       __("Line Number: ") .$e->getLine() .'<br />'.
							       __("Trace: ") .$e->getTraceAsString();
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
				}

				// Update system - payment success
				$this->loadModel ( 'Payment.PaymentInvoice' );
				$this->loadModel ( 'Payment.PaymentTempInvoice' );
				$this->loadModel ( 'Log' );
				$paymentInfo = null;
				$userInfo 	 = null;
				$systemUpdateError = false;
				if($isTempInvoice){
					if ($this->PaymentTempInvoice->exists($pendingInvoiceId)){
						list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
						return false;
					}
				}else{
					$isTempInvoice = false;
					if($this->PaymentInvoice->exists($pendingInvoiceId)){
						list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
						return false;
					}
				}

				$paypalPayment = clone $payment;
				$paymentId = $payment->getId();
				if(!empty($paymentId)){

					// Step 2: delete temp invoice and created paid real invoice
					$invoiceId 				= null;
					$paymentTransactions	= $payment->getTransactions();
					$transactionDone 		= array_shift($paymentTransactions);
					$paidAmount 			= $transactionDone->getAmount()->getTotal();
					$transactionId 			= @$paymentInfo['number']; // Only real invoice has an invoice number
					$paidTimestamp 			= date('Y-m-d H:i:s', strtotime($payment->getCreateTime()));
					$paymentUpdateTimstamp  = $payment->getUpdateTime();
					$paymentUpdateTimstamp 	= empty($paymentUpdateTimstamp) ? '' : date('Y-m-d H:i:s', strtotime($paymentUpdateTimstamp));
					$paymentState 			= $payment->getState();
					$paymentIntent			= $payment->getIntent();
					$tax					= floatval($transactionDone->getAmount()->getDetails()->getTax());
					$paymentStatus  		= (floatval($paymentInfo['amount']) == floatval($paidAmount + $paymentInfo['paid_amount'] - $tax)) ? Configure::read('Payment.invoice.status.paid') : Configure::read('Payment.invoice.status.partial_paid');

					$invoice 				= array(
						'PaymentInvoice' => array(
							'user_id'				=> $userInfo['id'],
							'is_auto_created' 		=> 1,
							'is_emailed_client'		=> 0,
							'number' 				=> empty($paymentInfo['number']) ? $this->PaymentInvoice->generateInvoiceNumber($paymentInfo['purchase_code']) : $paymentInfo['number'],
							'amount'				=> $paymentInfo['amount'],
							'paid_amount'			=> ($paidAmount + $paymentInfo['paid_amount'] - $tax),  //TODO handle partial paid invoice, not sure how to generate invoice yet
							'content'				=> $paymentInfo['content'],
							'due_date'				=> $paymentInfo['due_date'],
							'status'				=> $paymentStatus,
							'created_by'			=> $paymentInfo['created_by'],
							'created' 				=> $paymentInfo['created'],
							'modified_by'			=> $userInfo['id'],
							'modified' 				=> $paidTimestamp,
						)
					);
					if($isTempInvoice){

						$transactionId = $invoice['PaymentInvoice']['number']; // Temp invoice doesn't have an invoice number, because it is not a real invoice

						if ($this->PaymentInvoice->saveInvoice($invoice)) {

							$invoiceId = $this->PaymentInvoice->getInsertID();

						} else {
							if(!$systemUpdateError){ $systemUpdateError = true; }
							$invoiceFileGenerated = false;

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.error');
							$logMessage  = __('Cannot generate invoice record (' .$invoice['PaymentInvoice']['number'] .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') cannot generate invoice record (' .$invoice['PaymentInvoice']['number'] .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
						}

						// Update related data
						if(isset($paymentInfo['related_update_data']) && !empty($paymentInfo['related_update_data'])){

							if(!$this->__updateRelatedData($paymentInfo)){
								if(!$systemUpdateError){ $systemUpdateError = true; }
							}
						}

					}else{
						$invoiceId = $paymentInfo['id']; // real payment invoice ID
						$invoice['PaymentInvoice']['id'] = $invoiceId;
						if($this->PaymentInvoice->updateInvoice($invoiceId, $invoice)){

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.info');
							$logMessage  = __('Invoice (' .$invoice['PaymentInvoice']['number'] .') updated.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

						}else{
							if(!$systemUpdateError){ $systemUpdateError = true; }

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.error');
							$logMessage  = __('Cannot update invoice record (' .$invoice['PaymentInvoice']['number'] .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') cannot update invoice record (' .$invoice['PaymentInvoice']['number'] .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
						}

					}

					App::uses('CakeTime', 'Utility');

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.info');
					$logMessage  = __('Invoice (' .$invoice['PaymentInvoice']['number'] .') has been paid at ' .CakeTime::i18nFormat(date('Y-m-d H:i:s'), '%x %X') .'.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

					// Step 3: Generate invoice file
					$invoiceFileGenerated = false;
					if(!empty($invoiceId)){

						$invoiceFileGenerated = $this->__generateInvoiceFile($invoiceId, $invoice);
						if(!$invoiceFileGenerated){

							if(!$systemUpdateError){ $systemUpdateError = true; }
						}
					}

					// Step 4: Email client receipt/invoice
					$isEmailSent = false;
					if($invoiceFileGenerated && !empty($invoiceId)){

						if(!$this->__sendInvoiceMail($invoiceId, $invoice)){

							if(!$systemUpdateError){ $systemUpdateError = true; }
						}
					}

					// Step 5: save payment info into DB
					if(!empty($invoiceId)){
						$relatedResources 	= $transactionDone->getRelatedResources();
						$relatedResource	= $relatedResources[0];
						$sale 				= $relatedResource->getSale();

						$paymentTransaction = array(
							'PaymentPayPalGateway' => array(
								'payment_payer_id' 		=> $userInfo['PaymentPayer']['id'],
								'payment_invoice_id'	=> $invoiceId,
								'is_temp'				=> ($isTempInvoice && empty($invoice['PaymentInvoice']['id'])) ? 1 : 0,
								'amount'				=> $paidAmount,
								'transaction_fee'		=> $sale->getTransactionFee()->getValue(),
								'tax'					=> $tax,
								'status'				=> $paymentState,
								'payment_id' 			=> $paymentId,
								'sale_id'				=> $sale->getId(),
								'intent'				=> $paymentIntent,
								'created'				=> $paidTimestamp,
								'modified'				=> $paymentUpdateTimstamp
							)
						);
						$this->loadModel ( 'Payment.PaymentPayPalGateway' );
						if(!$this->PaymentPayPalGateway->saveTransaction($paymentTransaction)){
							if(!$systemUpdateError){ $systemUpdateError = true; }

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.error');
							$logMessage  = __('Payment transaction record cannot be saved.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') payment transaction record cannot be saved (Invoice ID: ' .$invoiceId .', transaction details: ' .json_encode($paymentTransaction) .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
						}
					}
				}

				$this->set('systemUpdateError', $systemUpdateError);
				$this->set('payment', $payment->toJSON());
				$this->set('_serialize', array('payment', 'systemUpdateError'));

			}else{
				//TODO client cancel payment
			}

		}else{

			throw new BadControllerRequestException($this->modelClass);
		}
	}

/**
 * Details see PayPal REST API ready (https://github.com/paypal/rest-api-sdk-php)
 * @param string $pendingInvoiceId
 * @param string $isTempInvoice
 */
	public function admin_recurringPayment ($pendingInvoiceId = null, $isTempInvoice = false){

		$this->_prepareAjaxPostAction();

		$paymentCycle = array(
			Configure::read('Payment.pay.cycle.monthly'),
			Configure::read('Payment.pay.cycle.quarterly'),
			Configure::read('Payment.pay.cycle.half_year'),
			Configure::read('Payment.pay.cycle.annually'),
		);

		if($this->request->is('post') && $this->request->is('ajax') && !empty($this->request->data['payment_cycle']) && in_array($this->request->data['payment_cycle'], $paymentCycle)){

			$paymentInfo = null;
			$userInfo 	 = null;
			$this->loadModel ( 'Payment.PaymentInvoice' );
			$this->loadModel ( 'Payment.PaymentTempInvoice' );
			if(empty($pendingInvoiceId)){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
				return false;
			}else{
				if($isTempInvoice){
					if ($this->PaymentTempInvoice->exists($pendingInvoiceId)){
						list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
						return false;
					}
				}else{
					$isTempInvoice = false;
					if($this->PaymentInvoice->exists($pendingInvoiceId)){
						list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing, please re-issue the payment."));
						return false;
					}
				}
			}

			if(empty($paymentInfo['recurring_plan_name'])){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Recurring payment plan is missing."));
				return false;
			}

			if(empty($paymentInfo['recurring_amount'])){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Recurring payment amount is invalid."));
				return false;
			}

			$userServiceAccountId = null;
			if(isset($paymentInfo['related_update_data']) && !empty($paymentInfo['related_update_data'])){
				$updateRelatedData = unserialize($paymentInfo['related_update_data']);
				if(!empty($updateRelatedData) && is_array($updateRelatedData)){

					if(empty($updateRelatedData['data'][$updateRelatedData['class']]['user_id']) || empty($updateRelatedData['data'][$updateRelatedData['class']]['next_pay_date']) || empty($updateRelatedData['data'][$updateRelatedData['class']]['payment_cycle'])){

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('Payment related data structure is wrong. Cannot find user serivce account ID. (' .json_encode($paymentInfo) .').');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment data is corrupted. Cannot process recurring payment."));
						return false;

					}else{
						$userServiceAccountId = $updateRelatedData['data'][$updateRelatedData['class']]['user_id'];
					}
				}else{

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __('Payment data is corrupted. Cannot process recurring payment. (' .json_encode($paymentInfo) .').');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment data is corrupted. Cannot process recurring payment."));
					return false;
				}
			}else{

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('Payment data is corrupted. Cannot process recurring payment. (' .json_encode($paymentInfo) .').');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment data is corrupted. Cannot process recurring payment."));
				return false;
			}

			$plan = new Plan();
			$plan->setName($paymentInfo['recurring_plan_name'])
			     ->setDescription($paymentInfo['recurring_plan_name'])
			     ->setType('INFINITE');

			//TODO need to change this part if we support multiple currencies
			$currency = $this->_getSystemDefaultConfigSetting("Currency", Configure::read('Config.type.payment'));

			$paymentCycleInMonth 	= 1; // Use this var to calculate correct recurring amount, because in the init set up, all the recurring amount is monthly amount
			$frequencyInterval 		= "1";
			$frequency		   		= 'MONTH';
			$discount		   		= $this->_getSystemDefaultConfigSetting("MonthlyDiscount", Configure::read('Config.type.payment'));
			$purchaseCode			= @$paymentInfo['purchase_code'];
			if($isTempInvoice){

				if(empty($purchaseCode)){

					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment data is corrupted. Payment purchase code is missing. Cannot process recurring payment."));
					return false;
				}

			}else{

				if(empty($paymentInfo['number'])){

					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment data is corrupted. Payment Invoice number is missing. Cannot process recurring payment."));
					return false;
				}

				preg_match('/^([A-Z]+)[\d]+$/', $paymentInfo['number'], $matches);
				$purchaseCode = @$matches[1][0];
			}
			if(empty($purchaseCode)){

				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment data is corrupted. Payment purchase code is missing. Cannot process recurring payment."));
				return false;

			}else{

				// If need to modify this part, please double check the related info in payment bootstrap.php file
				$emailMarketingPurchaseCodes = array_values(Configure::read('Payment.code.email_marketing'));
				$liveChatPurchaseCodes 		= array_values(Configure::read('Payment.code.live_chat'));
				if(in_array($purchaseCode, $emailMarketingPurchaseCodes)){
					$purchaseCodePrefix = 'Payment.code.email_marketing.';
				}elseif(in_array($purchaseCode, $liveChatPurchaseCodes)){
					$purchaseCodePrefix = 'Payment.code.live_chat.';
				}
			}
			if(!isset($purchaseCodePrefix) || empty($purchaseCodePrefix)){

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('Purchase code is invalid. (Purchase code: ' .$purchaseCode .', Payment Info: ' .json_encode($paymentInfo) .').');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment data is corrupted. Cannot process recurring payment."));
				return false;
			}
			if($this->request->data['payment_cycle'] == Configure::read('Payment.pay.cycle.monthly')){
				$frequencyInterval 		= "1";
				$purchaseCode			= Configure::read($purchaseCodePrefix .'monthly_recurring');
			}elseif($this->request->data['payment_cycle'] == Configure::read('Payment.pay.cycle.quarterly')){
				$frequencyInterval 		= "3";
				$paymentCycleInMonth 	= 3;
				$discount		   		= $this->_getSystemDefaultConfigSetting("QuarterlyDiscount", Configure::read('Config.type.payment'));
				$purchaseCode			= Configure::read($purchaseCodePrefix .'quarterly_recurring');
			}elseif($this->request->data['payment_cycle'] == Configure::read('Payment.pay.cycle.half_year')){
				$frequencyInterval 		= "6";
				$paymentCycleInMonth 	= 6;
				$discount		   		= $this->_getSystemDefaultConfigSetting("HalfYearDiscount", Configure::read('Config.type.payment'));
				$purchaseCode			= Configure::read($purchaseCodePrefix .'half_yearly_recurring');
			}elseif($this->request->data['payment_cycle'] == Configure::read('Payment.pay.cycle.annually')){
				$frequency		   		= 'YEAR';
				$frequencyInterval 		= "1";
				$paymentCycleInMonth 	= 12;
				$discount		   		= $this->_getSystemDefaultConfigSetting("AnnuallyDiscount", Configure::read('Config.type.payment'));
				$purchaseCode			= Configure::read($purchaseCodePrefix .'annually_recurring');
			}

			if($discount > 0){
				$paymentInfo['recurring_amount'] *= $paymentCycleInMonth * (1 - $discount);
			}

			$taxRate = $this->_getSystemDefaultConfigSetting("TaxGSTRate", Configure::read('Config.type.payment'));

			$paymentDefinitions = array();

			//TODO could use the following code to create a trial period. Then the set up fee should be 0
// 			if($paymentInfo['amount'] != $paymentInfo['recurring_amount']){ // Create a trial payment if client deducted some amount using saved credits, like prepaid money

// 				$paymentTrialDefinition = new PaymentDefinition();

// 				$paymentTrialDefinition->setName(__('First deducted Payments'))
// 									   ->setType('TRIAL')
// 									   ->setFrequency($frequency)
// 									   ->setFrequencyInterval("1")
// 									   ->setCycles("1")
// 									   ->setAmount(new Currency(array('value' => $paymentInfo['amount'], 'currency' => $currency)));

// 				$chargeModel = new ChargeModel();
// 				$chargeModel->setType('TAX')
// 							->setAmount(new Currency(array('value' => $taxRate * $paymentInfo['amount'], 'currency' => $currency)));

// 				$paymentTrialDefinition->setChargeModels(array($chargeModel));

// 				$paymentDefinitions[] = $paymentTrialDefinition;
// 			}

			$paymentDefinition = new PaymentDefinition();

			$paymentDefinition->setName(__('Regular Payments'))
							  ->setType('REGULAR')
							  ->setFrequency($frequency)
							  ->setFrequencyInterval($frequencyInterval)
							  ->setAmount(new Currency(array('value' => $paymentInfo['recurring_amount'], 'currency' => $currency)));

			$chargeModel = new ChargeModel();
			$chargeModel->setType('TAX')
						->setAmount(new Currency(array('value' => $taxRate * $paymentInfo['recurring_amount'], 'currency' => $currency)));

			$paymentDefinition->setChargeModels(array($chargeModel));

			$paymentDefinitions[] = $paymentDefinition;

			$uniqueFakeAgreementId = time();

			$merchantPreferences = new MerchantPreferences();

			$recurringPaymentFailAttempts = $this->_getSystemDefaultConfigSetting("RecurringPaymentFailAttempts", Configure::read('Config.type.payment'));

			$baseUrl = Router::url('/', true);
			$tempInvoiceParam = $isTempInvoice ? '/1' : '';
			$merchantPreferences->setReturnUrl("{$baseUrl}admin/payment/payment_pay_pal_gateway/executeAgreement/{$pendingInvoiceId}/{$uniqueFakeAgreementId}{$tempInvoiceParam}?success=true")
								->setCancelUrl("{$baseUrl}admin/payment/payment_pay_pal_gateway/executeAgreement/{$pendingInvoiceId}/{$uniqueFakeAgreementId}{$tempInvoiceParam}?success=false")
								->setAutoBillAmount("NO")
								->setInitialFailAmountAction("CANCEL")
								->setMaxFailAttempts($recurringPaymentFailAttempts)
								->setSetupFee(new Currency(array('value' => $paymentInfo['amount'], 'currency' => $currency))); // Charge first cycle as set up fee

			$plan->setPaymentDefinitions($paymentDefinitions);
			$plan->setMerchantPreferences($merchantPreferences);

			try {

				$apiContext = $this->__getApiContext();

				$createdPlan = $plan->create($apiContext);

				// Activate plan

				$patch = new Patch();

				$value = new PayPalModel('{
					"state":"ACTIVE"
				}');

				$patch->setOp('replace')
					  ->setPath('/')
					  ->setValue($value);
				$patchRequest = new PatchRequest();
				$patchRequest->addPatch($patch);

				$createdPlan->update($patchRequest, $apiContext);

				// Create agreement

				$agreement = new Agreement();

				$startDate = date('Y-m-d') ."T" .date('H:i:s') .'Z';

				$agreement->setName($paymentInfo['recurring_plan_name'] .' - ' .__('Recurring Payment Agreement'))
						  ->setDescription($paymentInfo['recurring_plan_name'])
						  ->setStartDate($startDate);

				$plan = new Plan();
				$plan->setId($createdPlan->getId());
				$agreement->setPlan($plan);

				$payer 					= new Payer();
				$paymentMethod 			= empty($this->request->data['payment_method']) ? 'paypal' : $this->request->data['payment_method'];
				$payer->setPaymentMethod($paymentMethod);
				$paypalPaymentMethods 	= Configure::read('Payment.method.paypal');
				if($paymentMethod == $paypalPaymentMethods['credit_card']){

					$creditCard = new CreditCard();
					$creditCard->setCvv2($this->request->data['cc_cvv']);
					$creditCard->setExpireMonth($this->request->data['cc_exp_month']);
					$creditCard->setExpireYear($this->request->data['cc_exp_year']);
					$creditCard->setFirstName($this->request->data['cc_first_name']);
					$creditCard->setLastName($this->request->data['cc_last_name']);
					$creditCard->setNumber($this->request->data['cc_number']);
					$creditCard->setType($this->request->data['cc_type']);

					if(empty($userInfo['Address'])){
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Billing Address is missing."));
						return false;
					}else{
						list($billingAddress, $country) = $this->__getBillingAddressDetails($userInfo);
						if($country === false){
							$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Billing Address country field is empty."));
							return false;
						}

						$addr = new Address();
						$addr->setLine1($billingAddress['street_address'])
						->setCity($billingAddress['suburb'])
						->setState($billingAddress['state'])
						->setPostalCode($billingAddress['postcode'])
						->setCountryCode($country['Country']['code']);
						if(!empty($userInfo['phone'])){
							$addr->setPhone($userInfo['phone']);
						}
					}
					$creditCard->setBillingAddress($addr);

					$fundingInstrument = new FundingInstrument();
					$fundingInstrument->setCreditCard($creditCard);

					$payer->setFundingInstruments(array($fundingInstrument));

				}elseif ($paymentMethod == $paypalPaymentMethods['direct_debit']){

//TODO we have DPRP_DISABLED issue for CC transaction. When this is fixed, we will continue working on DD payment
//{"name":"DPRP_DISABLED","message":"DPRP is disabled for this merchant.","information_link":"https://developer.paypal.com/docs/api/payments.billing-agreements#errors","debug_id":"1b54ebf38f6f9"}
error_log(print_r($this->request->data, true));exit();

				}
				$agreement->setPayer($payer);

				try{

					$agreement = $agreement->create($apiContext);

				}catch (PayPal\Exception\PayPalConnectionException $ex){

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.error');
					$logMessage  = __('PayPal create recurring billing agreement error occurred. This has been reported and we will look into it ASAP.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("PayPal Create Recurring Payment Exception: ") .'<br />'.
								   __("Error Code: ") . $ex->getCode() .'<br />'.
								   __("Error Data: ") . $ex->getData() .'<br />'.
							       __("Error Message: ") . $ex->getMessage() .'<br />'.
							       __("Line Number: ") .$ex->getLine() .'<br />'.
							       __("Trace: ") .$ex->getTraceAsString();
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
					exit(1);

				}catch(Exception $e){

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.error');
					$logMessage  = __('PayPal create recurring billing agreement error occurred. This has been reported and we will look into it ASAP.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("PayPal Create Recurring Payment Exception: ") .'<br />'.
								   __("Error Message: ") . $e->getMessage() .'<br />'.
								   __("Line Number: ") .$e->getLine() .'<br />'.
								   __("Trace: ") .$e->getTraceAsString();
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					exit(1);
				}

				$approvalUrl = $agreement->getApprovalLink();

				if(filter_var($approvalUrl, FILTER_VALIDATE_URL)){

					$status = $agreement->getState();

					$this->loadModel("Payment.PaymentRecurringAgreement");
					$this->PaymentRecurringAgreement->saveRecurringAgreement(
						array(
							'PaymentRecurringAgreement' => array(
								'payment_payer_id'			=> $userInfo['PaymentPayer']['id'],
								'name'						=> $paymentInfo['recurring_plan_name'],
								'purchase_code'				=> $purchaseCode,
								'recurring_amount'			=> $paymentInfo['recurring_amount'],
								'payment_cycle'				=> $this->request->data['payment_cycle'],
								'recurring_plan_id' 		=> $createdPlan->getId(),
								'recurring_agreement_id' 	=> $uniqueFakeAgreementId,
								'start_time' 				=> str_replace("Z", "", str_replace("T", " ", $agreement->getStartDate())),
								'status'					=> empty($status) ? '' : $status,
								'service_account_user_id'	=> $userServiceAccountId,
								'payment_temp_invoice_id'	=> $pendingInvoiceId
							)
						)
					);

					echo $approvalUrl;
					exit();

				}else{

					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Create recurring payment agreement failed."));
					return false;
				}

			} catch (PayPal\Exception\PayPalConnectionException $ex) {

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('PayPal create recurring subscription error occurred. This has been reported and we will look into it ASAP.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("PayPal Create Recurring Payment Exception: ") .'<br />'.
							   __("Error Code: ") . $ex->getCode() .'<br />'.
							   __("Error Data: ") . $ex->getData() .'<br />'.
						       __("Error Message: ") . $ex->getMessage() .'<br />'.
						       __("Line Number: ") .$ex->getLine() .'<br />'.
						       __("Trace: ") .$ex->getTraceAsString();
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
				exit(1);

			} catch (Exception $e) {

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('PayPal create recurring subscription error occurred. This has been reported and we will look into it ASAP.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("PayPal Create Recurring Payment Exception: ") .'<br />'.
						       __("Error Message: ") . $e->getMessage() .'<br />'.
						       __("Line Number: ") .$e->getLine() .'<br />'.
						       __("Trace: ") .$e->getTraceAsString();
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				exit(1);
			}

		}

	}

	public function admin_executeAgreement($pendingInvoiceId = null, $uniqueFakeAgreementId = null, $isTempInvoice = false){

		$this->_prepareNoViewAction();

		if (!empty($pendingInvoiceId) && isset($this->request->query['success']) && !empty($this->request->query['token'])){

			try {

				if(!empty($this->request->query['success']) && $this->request->query['success'] === "true"){

					$agreement = new \PayPal\Api\Agreement();

					$apiContext = $this->__getApiContext();

					$agreement->execute($this->request->query['token'], $apiContext);

					// Update system - payment success

					$this->loadModel ( 'Payment.PaymentInvoice' );
					$this->loadModel ( 'Payment.PaymentTempInvoice' );
					$this->loadModel ( 'Log' );
					$paymentInfo = null;
					$userInfo 	 = null;
					$systemUpdateError = false;
					if($isTempInvoice){
						if ($this->PaymentTempInvoice->exists($pendingInvoiceId)){
							list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
						}else{
							$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing. This has been reported, we will solve it ASAP."));

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') payment related data is missing when update executed recurring agreement. Please check the DB record and fix data issue. (Agreement ID: ' .$agreement->getId() .', Agreement Details: ' .json_encode($agreement->getAgreementDetails()) .')');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							return false;
						}
					}else{
						$isTempInvoice = false;
						if($this->PaymentInvoice->exists($pendingInvoiceId)){
							list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($pendingInvoiceId, $isTempInvoice);
						}else{
							$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment information is missing. This has been reported, we will solve it ASAP."));

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') payment related data is missing when update executed recurring agreement. Please check the DB record and fix data issue. (Agreement ID: ' .$agreement->getId() .', Agreement Details: ' .json_encode($agreement->getAgreementDetails()) .')');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							return false;
						}
					}

					// Update recurring payer info - add agreement ID

					$this->loadModel("Payment.PaymentRecurringAgreement");

					if(!empty($uniqueFakeAgreementId)){
						$recurringAgreement 														= $this->PaymentRecurringAgreement->getRecurringAgreementByPayerIdAndFakeAgreementId($userInfo['PaymentPayer']['id'], $uniqueFakeAgreementId);
						$recurringAgreement['PaymentRecurringAgreement']['recurring_agreement_id'] 	= $agreement->getId();
						$recurringAgreement['PaymentRecurringAgreement']['active'] 					= 1;
						$recurringAgreement['PaymentRecurringAgreement']['status'] 					= $agreement->getState();

						$this->PaymentRecurringAgreement->updateRecurringAgreement($recurringAgreement['PaymentRecurringAgreement']['id'], $recurringAgreement);
					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Recurring payment agreement status cannot be updated. This has been reported and sorry about the inconvenience."));

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('User (#' .$this->superUserId .') PayPal recurring payment agreement status cannot be updated. Please double check payment in PayPal account and update system manually. (Agreement ID: ' .$agreement->getId() .', Agreement Details: ' .json_encode($agreement->getAgreementDetails()) .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						return $this->redirect('/admin/dashboard');
					}

					// Cancel previous recurring agreement
					$previousAgreements = $this->PaymentRecurringAgreement->getPreviousAgreementsByNewAgreement($recurringAgreement);
					if(!empty($previousAgreements)){

						foreach($previousAgreements as $pa){
							if(is_numeric($pa['PaymentRecurringAgreement']['recurring_agreement_id'])){
								continue; // Do not cancel invalid agreement
							}
							$postData = array(
								'data' => array(
									'PaymentRecurringAgreement' => array(
										'recurring_agreement_id' => $pa['PaymentRecurringAgreement']['recurring_agreement_id']
									)
								)
							);

							$this->requestAction('/admin/payment/payment_pay_pal_gateway/handleRecurringAgreement/cancel/' .$userInfo['PaymentPayer']['user_id'] .'/' .$pa['PaymentRecurringAgreement']['recurring_agreement_id'], $postData);
						}
					}

					// Update service account
					if(isset($paymentInfo['related_update_data']) && !empty($paymentInfo['related_update_data'])){
						$updateRelatedData = unserialize($paymentInfo['related_update_data']);
						$this->loadModel ( $updateRelatedData['plugin'] .((isset($updateRelatedData['plugin']) && !empty($updateRelatedData['plugin'])) ? '.' : '') .$updateRelatedData['class'] );

						$relatedObj 	 = $updateRelatedData['data'];

						$dateTimeHandler = new DateTimeHandler();

						$frequencyInterval = "1";
						if($recurringAgreement['PaymentRecurringAgreement']['payment_cycle'] == Configure::read('Payment.pay.cycle.monthly')){
							$frequencyInterval = "1";
						}elseif($recurringAgreement['PaymentRecurringAgreement']['payment_cycle'] == Configure::read('Payment.pay.cycle.quarterly')){
							$frequencyInterval = "3";
						}elseif($recurringAgreement['PaymentRecurringAgreement']['payment_cycle'] == Configure::read('Payment.pay.cycle.half_year')){
							$frequencyInterval = "6";
						}elseif($recurringAgreement['PaymentRecurringAgreement']['payment_cycle'] == Configure::read('Payment.pay.cycle.annually')){
							$frequencyInterval = "12";
						}

						$relatedObj[$updateRelatedData['class']]['next_pay_date'] = $dateTimeHandler->getSameDayInFollowingMonth(date('Y-m-d', strtotime($agreement->getStartDate())), $frequencyInterval);
						$relatedObj[$updateRelatedData['class']]['payment_cycle'] = $recurringAgreement['PaymentRecurringAgreement']['payment_cycle'];
						$updateRelatedData['data'] = $relatedObj;

						if(!$this->__updateRelatedData(null, TRUE, $userInfo['PaymentPayer']['user_id'], $updateRelatedData)){

							return false;
						}
					}

					$this->_showSuccessFlashMessage($this->PaymentPayPalGateway, __("Recurring payment agreement has been created. Initial payment will be charged in a few minutes, and after that you can enjoy the purchased service.<br /><br />Thank you for your business."));
					return $this->redirect('/admin/dashboard#/admin/payment/payment_dashboard/manageRecurringPayments');

				}else{

					//TODO client cancel recurring payment
					$this->_showWarningFlashMessage($this->PaymentPayPalGateway, __("Recurring payment agreement has been cancelled."));
					return $this->redirect('/admin/dashboard');
				}

			} catch (Exception $e) {

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('User (#' .$this->superUserId .') PayPal execute recurring subscription exception occurred.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("PayPal Execute Recurring Payment Exception: ") .'<br />'.
						       __("Error Message: ") . $e->getMessage() .'<br />'.
						       __("Line Number: ") .$e->getLine() .'<br />'.
						       __("Trace: ") .$e->getTraceAsString() .'<br />'.
      						   __('Log Data: ') .__('User ID #') .$this->superUserId;
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				exit(1);
			}

		}else{

			throw new BadControllerRequestException($this->modelClass);

		}
	}

	public function admin_refund ($paymentInvoiceId = null){

		$currency = $this->_getSystemDefaultConfigSetting("Currency", Configure::read('Config.type.payment'));

		if(empty($paymentInvoiceId)){
			$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Invoice ID is missing."));
			return false;
		}else{
			$this->loadModel ( 'Payment.PaymentInvoice' );
			$isTempInvoice = false;
			if ($this->PaymentInvoice->exists($paymentInvoiceId)){
				list($userInfo, $paymentInfo) = $this->__getInvoiceDetails($paymentInvoiceId, $isTempInvoice);
// 				$this->set('maxRefundAmount', $paymentInfo['amount']); // jQuery Validate cannot validate decimal range
			}else{
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Payment invoice doesn't exist."));

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('User (#' .$this->superUserId .') tried to refund non-existing invoice. (Passed invoice ID: ' .$paymentInvoiceId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				return false;
			}

			list($billingAddress, $country) = $this->__getBillingAddressDetails($userInfo);
			if($country === false){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Billing Address country field is empty."));
				return false;
			}

			$this->set('userInfo', $userInfo);
			$this->set('billingAddress', $billingAddress);
			$this->set('country', $country);
			$this->set('paymentInfo', $paymentInfo);
			$this->set('paymentInvoiceId', $paymentInvoiceId);
			$this->set('isTempInvoice', $isTempInvoice);
			$this->set('currency', $currency);
		}


		if($this->request->is('post') && $this->request->is('ajax') && $this->RequestHandler->ext == "json"){
			if(empty($this->request->data['PaymentPayPalGateway']['amount']) || !is_numeric($this->request->data['PaymentPayPalGateway']['amount'])){
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Refund amount is missing."));
				return false;
			}elseif(!is_numeric($this->request->data['PaymentPayPalGateway']['amount']) || $this->request->data['PaymentPayPalGateway']['amount'] < 0 || $this->request->data['PaymentPayPalGateway']['amount'] > $paymentInfo['amount'] ){
				// jQuery Validate cannot validate decimal range
				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Refund amount is not valid."));
				return false;
			}else{

				$this->loadModel ( 'Payment.PaymentPayPalGateway' );
				$saleId = $this->PaymentPayPalGateway->getSaleIdByInvoice($paymentInvoiceId);
				if(empty($saleId)){
					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Cannot find payment transaction."));

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __('User (#' .$this->superUserId .') payment transaction cannot be found. (Passed invoice ID: ' .$paymentInvoiceId .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					return false;
				}

				$amt = new Amount();
				$amt->setTotal(floatval($this->request->data['PaymentPayPalGateway']['amount']))
				->setCurrency($currency);

				$refund = new Refund();
				$refund->setAmount($amt);

				$sale = new Sale();
				$sale->setId($saleId);

				$apiContext = $this->__getApiContext();

				try {
					$refundedSale = $sale->refund($refund, $apiContext);

					if($refundedSale->getState() == Configure::write('Payment.paypal.gateway.status.completed')){

						$this->loadModel ( 'Log' );

						// Record transaction
						$paymentId				= $refundedSale->getParentPayment();
						$saleId					= $refundedSale->getSaleId();
						$refundAmount			= floatval($refundedSale->getAmount()->getTotal());
						$paymentCreateTimstamp  = $refundedSale->getCreateTime();
						$paymentCreateTimstamp 	= empty($paymentCreateTimstamp) ? '' : date('Y-m-d H:i:s', strtotime($paymentCreateTimstamp));
						$paymentUpdateTimstamp  = $refundedSale->getUpdateTime();
						$paymentUpdateTimstamp 	= empty($paymentUpdateTimstamp) ? '' : date('Y-m-d H:i:s', strtotime($paymentUpdateTimstamp));
						$paymentTransaction = array(
							'PaymentPayPalGateway' => array(
								'payment_payer_id' 		=> $userInfo['PaymentPayer']['id'],
								'payment_invoice_id'	=> $paymentInvoiceId,
								'is_temp'				=> 0, // Only can refund real invoice
								'amount'				=> $refundAmount,
								'status'				=> $refundedSale->getState(),
								'payment_id' 			=> $paymentId,
								'sale_id'				=> $refundedSale->getSaleId(),
								'intent'				=> 'refund',
								'created'				=> $paymentCreateTimstamp,
								'modified'				=> $paymentUpdateTimstamp
							)
						);
						if(!$this->PaymentPayPalGateway->saveTransaction($paymentTransaction)){
							if(!$systemUpdateError){ $systemUpdateError = true; }

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.error');
							$logMessage  = __('Refund transaction record cannot be saved.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') refund transaction record cannot be saved (Invoice ID: ' .$paymentInvoiceId .', payment ID: ' .$paymentId .', sale ID: ' .$saleId .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						}

						// Update invoice
						$invoiceId 			= $paymentInfo['id'];
						$totalRefundAmount 	= $paymentInfo['refund_amount'] + $refundAmount;
						$invoice = array(
							'PaymentInvoice' => array(
								'id'					=> $paymentInfo['id'],
								'user_id'				=> $userInfo['id'],
								'is_auto_created' 		=> $paymentInfo['is_auto_created'],
								'is_emailed_client'		=> 0, // Need to re-email the latest invoice
								'number' 				=> $paymentInfo['number'],
								'amount'				=> $paymentInfo['amount'],
								'paid_amount'			=> $paymentInfo['paid_amount'],
								'refund_amount'			=> $totalRefundAmount,
								'content'				=> $paymentInfo['content'],
								'due_date'				=> $paymentInfo['due_date'],
								'status'				=> Configure::read('Payment.invoice.status.refund'), // No partial refund. If we did a partial refund and that is because client may already used some of the service and we cannot do a full refund. And then that "partial refund" is the final refund.
								'created_by'			=> $paymentInfo['created_by'],
								'created' 				=> $paymentInfo['created'],
								'modified_by'			=> AuthComponent::user('id'), // record the who actually did refund operation
								'modified' 				=> empty($paymentCreateTimstamp) ? $paymentUpdateTimstamp : $paymentCreateTimstamp,
							)
						);
						if($this->PaymentInvoice->updateInvoice($paymentInfo['id'], $invoice)){

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.info');
							$logMessage  = __('Invoice (' .$invoice['PaymentInvoice']['number'] .') refunded.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

						}else{
							if(!$systemUpdateError){ $systemUpdateError = true; }

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.error');
							$logMessage  = __('Cannot update invoice record (' .$invoice['PaymentInvoice']['number'] .') for refund purpose.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') cannot update invoice record (' .$invoice['PaymentInvoice']['number'] .') for refund purpose.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
						}

						// Generate invoice
						App::uses('CakeRequest', 'Network');
						App::uses('CakeResponse', 'Network');
						App::uses('PaymentInvoicesController', 'Payment.Controller');
						$request = new CakeRequest();
						$request->addParams(array('plugin' => 'Payment'));
						$PaymentInvoices = new PaymentInvoicesController($request, new CakeResponse());
						if(!$PaymentInvoices->admin_generateInvoiceFile($invoiceId)){ //No view controller action cannot be called using requestAction, no data returned.
							if(!$systemUpdateError){ $systemUpdateError = true; }
							$invoiceFileGenerated = false;

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.error');
							$logMessage  = __('Cannot generate invoice file (' .$invoice['PaymentInvoice']['number'] .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage);

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') cannot generate invoice file (' .$invoice['PaymentInvoice']['number'] .').');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						}else{
							$invoiceFileGenerated = true;
						}

						// Email client receipt/invoice
						$isEmailSent = false;
						if($invoiceFileGenerated){
							$postData = array(
								'data' => array(
									'PaymentInvoice' => array(
										'id' => $invoiceId
									)
								)
							);

							if($this->requestAction(DS .'system_email' .DS .'sendInvoiceEmail' .DS .$invoiceId, $postData)){

								$isEmailSent 									= true;
								$invoice['PaymentInvoice']['id'] 				= $invoiceId;
								$invoice['PaymentInvoice']['is_emailed_client'] = 1;
								$this->PaymentInvoice->updateInvoice($invoiceId, $invoice);

							}else{
								if(!$systemUpdateError){ $systemUpdateError = true; }

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.error');
								$logMessage  = __('Cannot send invoice file (' .$invoice['PaymentInvoice']['number'] .') via email.');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage);

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.critical');
								$logMessage  = __('User (#' .$this->superUserId .') cannot send invoice file (' .$invoice['PaymentInvoice']['number'] .') via email.');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							}
						}

						// Return result
						if($systemUpdateError){
							if(!$isEmailSent){
								if($invoiceFileGenerated){
									$message = __('Refund is successfully done, but updated invoice is not sent via email. Please go to paid invoice section and try to send it manually.');
								}else{
									$message = __('Refund is successfully done, but updated invoice cannot be generated.');
								}
							}else{
								$message = __('Refund is successfully done, but the system is not updated correctly. Please check the logs for details.');
							}
							$this->_showErrorFlashMessage($this->PaymentPayPalGateway, $message);
						}else{
							$this->_showSuccessFlashMessage($this->PaymentPayPalGateway, __('Refund is successfully done.'));
						}

					}else{
						$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __('Error occurred during refund. This issue has been reported. We will look into this ASAP.'));

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('User (#' .$this->superUserId .') refunded sale state (' .$refundedSale->getState() .') is not "' .Configure::write('Payment.paypal.gateway.status.completed') .'".');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						return false;
					}

				} catch (PayPal\Exception\PayPalConnectionException $ex) {

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("PayPal Refund Payment Exception: ") .'<br />'.
								   __("Error Code: ") . $ex->getCode() .'<br />'.
								   __("Error Data: ") . $ex->getData() .'<br />'.
							       __("Error Message: ") . $ex->getMessage() .'<br />'.
							       __("Line Number: ") .$ex->getLine() .'<br />'.
							       __("Trace: ") .$ex->getTraceAsString() .'<br />'.
      							   __('Log Data: ') .json_encode($ex->getCode()) .'<br />'.
      							   json_encode($ex->getData());
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					$message = json_decode($ex->getData());
					$this->_showErrorFlashMessage($this->PaymentPayPalGateway, $message->message);
					exit(1);

				} catch (Exception $e) {

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __("PayPal Refund Payment Exception: ") .'<br />'.
							       __("Error Message: ") . $e->getMessage() .'<br />'.
							       __("Line Number: ") .$e->getLine() .'<br />'.
							       __("Trace: ") .$e->getTraceAsString();
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
				}

				exit(1);
			}
		}
	}

	public function admin_getRecurringAgreementDetails ($superUserId){

		if(empty($superUserId)){
			$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Request is invalid."));
			return false;
		}

		if($superUserId != $this->superUserId && stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

			throw new NotFoundRecordException($this->modelClass, 'RecurringAgreementDetails');
		}

		$this->loadModel("Payment.PaymentRecurringAgreement");
		$paypalPaymentMethods 		= Configure::read('Payment.method.paypal');
		$recurringAgreements 		= $this->PaymentRecurringAgreement->getRecurringAgreementByUserIdAndPaymentMethod($superUserId, strtoupper($paypalPaymentMethods['paypal']));
		$recurringAgreementsDetails = array();

		foreach($recurringAgreements as $a){

			if(is_numeric($a['PaymentRecurringAgreement']['recurring_agreement_id'])){
				continue; // Do not get invalid agreement
			}

			$details = $this->__getAgreementDetailsByRecurringAgreementId($a['PaymentRecurringAgreement']['recurring_agreement_id']);

			if(!empty($details)){

				$recurringAgreementsDetails[$details['name']][] = $details;

			}else{

				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Cannot get PayPal recurring agreement details.") ." (" .$a['PaymentRecurringAgreement']['recurring_agreement_id'] .")");
				$recurringAgreementsDetails = array();
				continue;
			}

		}

		$this->set('recurringAgreementsDetails', $recurringAgreementsDetails);

	}

	public function admin_getRecurringAgreementDetail (){

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE && /*$this->request->is('post') &&*/ !empty($this->request->data["PaymentRecurringAgreement"]["recurring_agreement_id"])){

			$agreementId = $this->request->data["PaymentRecurringAgreement"]["recurring_agreement_id"];

			if(is_numeric($agreementId)){
				return false; // Do not get invalid agreement
			}

			$this->loadModel('Payment.PaymentRecurringAgreement');
			if(!$this->PaymentRecurringAgreement->hasAny(array('recurring_agreement_id' => $agreementId))){

				throw new NotFoundRecordException($this->modelClass, 'RecurringAgreementDetail');
			}

			return $this->__getAgreementDetailsByRecurringAgreementId($agreementId);
		}
	}

/**
 * Handle recurring agreement
 * The action could be: suspend, reactivate, cancel
 *
 * @param String $action
 * @param int $superUserId
 * @param String $agreementId
 * @throws NotFoundException
 * @return boolean
 */
	public function admin_handleRecurringAgreement ($action, $superUserId, $agreementId){
//TODO list agreement transactions. add it to the view

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){

			if($superUserId != $this->superUserId){

				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Request is invalid."));
				return false;
			}

		}elseif(stristr($this->superUserGroup, Configure::read('System.admin.group.name')) === FALSE){

			$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Request is invalid."));
			return false;
		}

		$action = strtoupper($action);
		$actionReadable = strtolower($action);
		if(empty($superUserId) || empty($agreementId) || empty($action) || !in_array($action, ["SUSPEND", "REACTIVATE", "CANCEL"])){
			$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Request is invalid."));
			return false;
		}

		if($superUserId == $this->superUserId && stristr($this->superUserGroup, Configure::read('System.client.group.name')) == false && stristr($this->superUserGroup, Configure::read('System.admin.group.name')) == false){

			$logType 	 = Configure::read('Config.type.payment');
			$logLevel 	 = Configure::read('System.log.level.warning');
			$logMessage  = __('Staff (#' .$this->superUserId .') tried to ' .$actionReadable .' own PayPal recurring agreement (Passed super user ID: ' .$superUserId .').');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __("Request is invalid."));
			return false;
		}

		$this->loadModel("Payment.PaymentRecurringAgreement");
		$agreement = $this->PaymentRecurringAgreement->browseBy('recurring_agreement_id', $agreementId);
		if(empty($agreement)){

			throw new NotFoundRecordException($this->modelClass, 'RecurringAgreement');

		}

		$this->loadModel("Payment.PaymentPayer");
		if(!$this->PaymentPayer->hasAny(array('id' => $agreement['PaymentRecurringAgreement']['payment_payer_id'], 'user_id' => $superUserId))){

			throw new NotFoundRecordException($this->modelClass, 'RecurringAgreement');

		}

		if($this->request->is('post') || (!empty($this->request->data['PaymentRecurringAgreement']['recurring_agreement_id']) && $this->request->data['PaymentRecurringAgreement']['recurring_agreement_id'] == $agreementId)){

			try {

				$apiContext = $this->__getApiContext();
				$recurringAgreement  = Agreement::get($agreementId, $apiContext);

				$agreementStateDescriptor = new AgreementStateDescriptor();

				switch ($action){

					case "SUSPEND":
						$agreementStateDescriptor->setNote(__("Suspending the recurring agreement"));
						$recurringAgreement->suspend($agreementStateDescriptor, $apiContext);

						$agreement['PaymentRecurringAgreement']['active'] = 0;
						$agreement['PaymentRecurringAgreement']['status'] = Configure::read('Payment.paypal.gateway.agreement.status.suspended');
						$this->PaymentRecurringAgreement->updateRecurringAgreement($agreementId, $agreement);

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.info');
						$logMessage  = __('System is suspending PayPal recurring agreement.') .' (' .__($agreement['PaymentRecurringAgreement']['name']) .')';
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $superUserId);

						break;

					case "REACTIVATE":
						$agreementStateDescriptor->setNote(__("Reactivating the recurring agreement"));
						$recurringAgreement->reActivate($agreementStateDescriptor, $apiContext);

						$agreement['PaymentRecurringAgreement']['active'] = 1;
						$agreement['PaymentRecurringAgreement']['status'] = Configure::read('Payment.paypal.gateway.agreement.status.active');
						$this->PaymentRecurringAgreement->updateRecurringAgreement($agreementId, $agreement);

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.info');
						$logMessage  = __('System is reactivating PayPal recurring agreement.') .' (' .__($agreement['PaymentRecurringAgreement']['name']) .')';
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $superUserId);

						break;

					case "CANCEL":
						$agreementStateDescriptor->setNote(__("Cancelling the recurring agreement"));
						$a = $recurringAgreement->cancel($agreementStateDescriptor, $apiContext);

						$agreement['PaymentRecurringAgreement']['active'] = 0;
						$agreement['PaymentRecurringAgreement']['status'] = Configure::read('Payment.paypal.gateway.agreement.status.cancelled');
						$this->PaymentRecurringAgreement->updateRecurringAgreement($agreementId, $agreement);

						$logType 	 = Configure::read('Config.type.payment');
						$logLevel 	 = Configure::read('System.log.level.info');
						$logMessage  = __('System is cancelling PayPal recurring agreement.') .' (' .__($agreement['PaymentRecurringAgreement']['name']) .')';
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $superUserId);

						break;
				}

				$this->_showSuccessFlashMessage($this->PaymentPayPalGateway, __('Successfully ' .$actionReadable .' recurring agreement'));

			} catch (Exception $e) {

				$this->_showErrorFlashMessage($this->PaymentPayPalGateway, __('Failed to ' .$actionReadable .' recurring agreement. Please double check the recurring payment status in your PayPal account.'));

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("PayPal Handle (' .$actionReadable .') Recurring Payment Agreement Exception: ") .'<br />'.
						       __("Error Message: ") . $e->getMessage() .'<br />'.
						       __("Line Number: ") .$e->getLine() .'<br />'.
						       __("Trace: ") .$e->getTraceAsString();
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
			}

		}

	}

	public function admin_paypalIPN (){

		$rawPostData = file_get_contents("php://input");
		if(!empty($rawPostData)){

			// Validate message first
			$requestBody = $rawPostData;

			$headers = array (
				'Client-Pid' 				=> CakeRequest::header('HTTP_CLIENT_PID') 				? CakeRequest::header('HTTP_CLIENT_PID') 				: $_SERVER['HTTP_CLIENT_PID'],
				'Cal-Poolstack' 			=> CakeRequest::header('HTTP_CAL_POOLSTACK') 			? CakeRequest::header('HTTP_CAL_POOLSTACK') 			: $_SERVER['HTTP_CAL_POOLSTACK'],
				'Correlation-Id' 			=> CakeRequest::header('HTTP_CORRELATION_ID') 			? CakeRequest::header('HTTP_CORRELATION_ID') 			: $_SERVER['HTTP_CORRELATION_ID'],
				'Host' 						=> CakeRequest::header('HTTP_HOST') 					? CakeRequest::header('HTTP_HOST') 						: $_SERVER['HTTP_HOST'],
				'User-Agent' 				=> CakeRequest::header('HTTP_USER_AGENT') 				? CakeRequest::header('HTTP_USER_AGENT') 				: $_SERVER['HTTP_USER_AGENT'],
				'Paypal-Auth-Algo' 			=> CakeRequest::header('HTTP_PAYPAL_AUTH_ALGO') 		? CakeRequest::header('HTTP_PAYPAL_AUTH_ALGO') 			: $_SERVER['HTTP_PAYPAL_AUTH_ALGO'],
				'Paypal-Cert-Url' 			=> CakeRequest::header('HTTP_PAYPAL_CERT_URL') 			? CakeRequest::header('HTTP_PAYPAL_CERT_URL') 			: $_SERVER['HTTP_PAYPAL_CERT_URL'],
				'Paypal-Auth-Version' 		=> CakeRequest::header('HTTP_PAYPAL_AUTH_VERSION') 		? CakeRequest::header('HTTP_PAYPAL_AUTH_VERSION') 		: $_SERVER['HTTP_PAYPAL_AUTH_VERSION'],
				'Paypal-Transmission-Sig' 	=> CakeRequest::header('HTTP_PAYPAL_TRANSMISSION_SIG') 	? CakeRequest::header('HTTP_PAYPAL_TRANSMISSION_SIG') 	: $_SERVER['HTTP_PAYPAL_TRANSMISSION_SIG'],
				'Paypal-Transmission-Time' 	=> CakeRequest::header('HTTP_PAYPAL_TRANSMISSION_TIME') ? CakeRequest::header('HTTP_PAYPAL_TRANSMISSION_TIME') 	: $_SERVER['HTTP_PAYPAL_TRANSMISSION_TIME'],
				'Paypal-Transmission-Id' 	=> CakeRequest::header('HTTP_PAYPAL_TRANSMISSION_ID') 	? CakeRequest::header('HTTP_PAYPAL_TRANSMISSION_ID') 	: $_SERVER['HTTP_PAYPAL_TRANSMISSION_ID'],
				'Accept' 					=> CakeRequest::header('HTTP_ACCEPT') 					? CakeRequest::header('HTTP_ACCEPT') 					: $_SERVER['HTTP_ACCEPT'],
			);

			$headers = array_change_key_case($headers, CASE_UPPER);

			$signatureVerification = new \PayPal\Api\VerifyWebhookSignature();
			$signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
			$signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
			$signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
			$signatureVerification->setWebhookId($this->_getSystemDefaultConfigSetting('PayPalIPNWebhokId', Configure::read('Config.type.payment')));
			$signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
			$signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);

			$signatureVerification->setRequestBody($requestBody);
			$request = clone $signatureVerification;

			try {

				$apiContext = $this->__getApiContext();
				$output = $signatureVerification->post($apiContext);

				// Process response
				if($output->getVerificationStatus() == "SUCCESS"){

					$rawPostArr = json_decode($rawPostData, true);

					$this->loadModel('Payment.PaymentRecurringAgreement');
					$agreement = $this->PaymentRecurringAgreement->browseBy('recurring_agreement_id', $rawPostArr['resource']['id'], array('Payment.PaymentPayer'));

					$this->loadModel('User');
					switch ($rawPostArr['event_type']){

						case "BILLING.SUBSCRIPTION.CANCELLED":

							$this->User->deactivateAccount($agreement['PaymentRecurringAgreement']['service_account_user_id']);

							$agreement['PaymentRecurringAgreement']['status'] = Configure::read('Payment.paypal.gateway.agreement.status.cancelled');
							$agreement['PaymentRecurringAgreement']['active'] = 0;
							$this->PaymentRecurringAgreement->updateRecurringAgreement($agreement['PaymentRecurringAgreement']['id'], array('PaymentRecurringAgreement' => $agreement['PaymentRecurringAgreement']));

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.success');
							$logMessage  = __('PayPal recurring agreement has been cancelled.') .' (' .__($agreement['PaymentRecurringAgreement']['name']) .')';
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

							break;

						case "BILLING.SUBSCRIPTION.CREATED":

							// Wait until the initial payment is received, then activate the account

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.success');
							$logMessage  = __('PayPal recurring agreement has been created.') .' (' .__($agreement['PaymentRecurringAgreement']['name']) .')';
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

							break;

						case "BILLING.SUBSCRIPTION.RE-ACTIVATED":

							$this->User->activateAccount($agreement['PaymentRecurringAgreement']['service_account_user_id']);

							$agreement['PaymentRecurringAgreement']['status'] = Configure::read('Payment.paypal.gateway.agreement.status.active');
							$agreement['PaymentRecurringAgreement']['active'] = 1;
							$this->PaymentRecurringAgreement->updateRecurringAgreement($agreement['PaymentRecurringAgreement']['id'], array('PaymentRecurringAgreement' => $agreement['PaymentRecurringAgreement']));

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.success');
							$logMessage  = __('PayPal recurring agreement has been reactivated.') .' (' .__($agreement['PaymentRecurringAgreement']['name']) .')';
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

							break;

						case "BILLING.SUBSCRIPTION.SUSPENDED":

							$this->User->deactivateAccount($agreement['PaymentRecurringAgreement']['service_account_user_id']);

							$agreement['PaymentRecurringAgreement']['status'] = Configure::read('Payment.paypal.gateway.agreement.status.suspended');
							$agreement['PaymentRecurringAgreement']['active'] = 0;
							$this->PaymentRecurringAgreement->updateRecurringAgreement($agreement['PaymentRecurringAgreement']['id'], array('PaymentRecurringAgreement' => $agreement['PaymentRecurringAgreement']));

							$logType 	 = Configure::read('Config.type.payment');
							$logLevel 	 = Configure::read('System.log.level.success');
							$logMessage  = __('PayPal recurring agreement has been suspended.') .' (' .__($agreement['PaymentRecurringAgreement']['name']) .')';
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

							break;
						case "BILLING.SUBSCRIPTION.UPDATED":
							// No need to update agreement (shipping address) right now
							break;
						case "PAYMENT.SALE.COMPLETED":

							if(empty($rawPostArr['resource']['billing_agreement_id'])){
								break; // One time sale payment is also notified using this event type
							}

							$this->loadModel('Payment.PaymentPayPalGateway');
							$this->loadModel('Payment.PaymentInvoice');

							$processedTransaction = $this->PaymentPayPalGateway->getTransactionBySaleIdAndAgreementId($rawPostArr['resource']['id'], $agreement['PaymentRecurringAgreement']['id']);
							if(!empty($processedTransaction)){
								break; // Sometimes PayPal didn't receive our response in time, it will continue re-send the same transaction details over IPN. If we have processed it, then ignore it.
							}

							$recurringTransactionAmount = $this->PaymentPayPalGateway->countRecurringAgreementTransactions($rawPostArr['resource']['billing_agreement_id']);

							$systemUserId = Configure::read('System.default.user.id');

							$paidTimestamp = date("Y-m-d H:i:s", strtotime($rawPostArr['create_time']));

							$taxRate = $this->_getSystemDefaultConfigSetting("TaxGSTRate", Configure::read('Config.type.payment'));

							$tax = round($agreement['PaymentRecurringAgreement']['recurring_amount'], 0) * $taxRate;

							// Step 1: save invoice record
							$invoice = array(
								'PaymentInvoice' => array(
									'user_id'				=> $agreement['PaymentPayer']['user_id'],
									'is_auto_created' 		=> 1,
									'is_emailed_client'		=> 0,
									'number' 				=> $this->PaymentInvoice->generateInvoiceNumber($agreement['PaymentRecurringAgreement']['purchase_code']),
									'currency'				=> $rawPostArr['resource']['amount']['currency'],
									'amount'				=> $agreement['PaymentRecurringAgreement']['recurring_amount'],
									'paid_amount'			=> $rawPostArr['resource']['amount']['total'],
									'recurring_plan_name'	=> $agreement['PaymentRecurringAgreement']['name'],
									'content'				=> $agreement['PaymentRecurringAgreement']['name'],
									'due_date'				=> date("Y-m-d", strtotime($rawPostArr['create_time'])),
									'status'				=> ($agreement['PaymentRecurringAgreement']['recurring_amount'] == ($rawPostArr['resource']['amount']['total'] - $tax)) ? Configure::read('Payment.invoice.status.paid') : Configure::read('Payment.invoice.status.partial_paid'),
									'created_by'			=> $systemUserId,
									'created' 				=> $paidTimestamp,
									'modified_by'			=> $systemUserId,
									'modified' 				=> $paidTimestamp,
								)
							);

							if ($this->PaymentInvoice->saveInvoice($invoice)) {

								$invoiceId = $this->PaymentInvoice->getInsertID();

							} else {

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.error');
								$logMessage  = __('Cannot generate recurring payment invoice record (Sale ID: ' .$rawPostArr['resource']['id'] .', Paid amount: (' .$rawPostArr['resource']['amount']['currency'] .') ' .$rawPostArr['resource']['amount']['total'] .', Paid timestamp: ' .$paidTimestamp .').');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.critical');
								$logMessage  = __('Cannot generate recurring payment invoice record (Sale ID: ' .$rawPostArr['resource']['id'] .', Paid amount: (' .$rawPostArr['resource']['amount']['currency'] .') ' .$rawPostArr['resource']['amount']['total'] .', Paid timestamp: ' .$paidTimestamp .').');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
							}

							// Step 2: update related record
							$invoiceRecordSaved = false;
							if($agreementDetails = $this->__getAgreementDetailsByRecurringAgreementId($agreement['PaymentRecurringAgreement']['recurring_agreement_id'])){
								if(!empty($invoiceId) && empty($recurringTransactionAmount) && !empty($agreement['PaymentRecurringAgreement']['payment_temp_invoice_id'])){

									$agreement['PaymentRecurringAgreement']['status'] = Configure::read('Payment.paypal.gateway.agreement.status.active');
									$agreement['PaymentRecurringAgreement']['active'] = 1;
									$this->PaymentRecurringAgreement->updateRecurringAgreement($agreement['PaymentRecurringAgreement']['id'], array('PaymentRecurringAgreement' => $agreement['PaymentRecurringAgreement']));

									$this->loadModel('Payment.PaymentTempInvoice');
									$paymentTempInvoice = $this->PaymentTempInvoice->browseBy('id', $agreement['PaymentRecurringAgreement']['payment_temp_invoice_id']);
									if(!empty($paymentTempInvoice) && !empty($paymentTempInvoice['PaymentTempInvoice']['related_update_data'])){

										if($this->__updateRelatedData($paymentTempInvoice, TRUE, $agreement['PaymentPayer']['user_id'])){

											$invoiceRecordSaved = $this->PaymentRecurringAgreement->removeProcessedTempInvoiceId($agreement['PaymentRecurringAgreement']['id']);
										}
									}

								}else{

									$invoiceRecordSaved = true;
								}

								// Update next pay date based on service user account ID
								$this->loadModel("User");
								if($modelInfo = $this->User->getUserModelByServiceAccount($agreement['PaymentRecurringAgreement']['service_account_user_id'])){

									$this->loadModel(((isset($modelInfo['plugin']) && !empty($modelInfo['plugin'])) ? $modelInfo['plugin'] .'.' : '') .$modelInfo['class']);
									$modelData = $this->$modelInfo['class']->browseBy('user_id', $agreement['PaymentRecurringAgreement']['service_account_user_id']);
									$modelData[$modelInfo['class']]['next_pay_date'] = date('Y-m-d', strtotime($agreementDetails['nextPaymentDate']));
									if($modelData[$modelInfo['class']]['payment_cycle'] != $agreement['PaymentRecurringAgreement']['payment_cycle']){
										// The service user data in temp invoice is set up before client choose payment cycle. So the correct payment cycle is saved in agreement record.
										// When we update service user data using the related data in temp invoice record, we may sve the wrong payment cycle in service user record.
										// So we need to use the correct payment cycle in agreement to correct the wrong one in service user record.
										$modelData[$modelInfo['class']]['payment_cycle'] = $agreement['PaymentRecurringAgreement']['payment_cycle'];
									}
									$relatedData = am($modelData, array('id' => $modelData[$modelInfo['class']]['id'], 'data' => $modelData));
									$this->__updateRelatedData(null, TRUE, $agreement['PaymentPayer']['user_id'], $relatedData);

								}else{

									$logType 	 = Configure::read('Config.type.payment');
									$logLevel 	 = Configure::read('System.log.level.critical');
									$logMessage  = __('Cannot update user (#' .$agreement['PaymentPayer']['user_id'] .') service account next pay date (Recurring agreement ID: ' .$agreement['PaymentRecurringAgreement']['recurring_agreement_id'] .', Service Account ID: ' .$agreement['PaymentRecurringAgreement']['service_account_user_id'] .').');
									$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
								}

							}else{
								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.error');
								$logMessage  = __('Cannot get recurring agreement details.');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.critical');
								$logMessage  = __('Cannot get user (#' .$agreement['PaymentPayer']['user_id'] .') recurring agreement details (Recurring agreement ID: ' .$agreement['PaymentRecurringAgreement']['recurring_agreement_id'] .').');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
							}

							// Step 3: generate invoice file
							$invoiceFileGenerated = false;
							if(!empty($invoiceId) && $invoiceRecordSaved){

								$invoiceFileGenerated = $this->__generateInvoiceFile($invoiceId, $invoice, TRUE);

							}else{

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.error');
								$logMessage  = __('Cannot update user details related to recurring payment (' .$agreement['PaymentRecurringAgreement']['name'] .').');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.critical');
								$logMessage  = __('Cannot update user (#' .$agreement['PaymentPayer']['user_id'] .') record after recurring agreement received (Recurring agreement ID: ' .$agreement['PaymentRecurringAgreement']['recurring_agreement_id'] .').');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
							}

							// Step 4: send invoice file to client
							$isEmailSent = false;
							if($invoiceFileGenerated && !empty($invoiceId)){

								$isEmailSent = $this->__sendInvoiceMail($invoiceId, $invoice, TRUE);

							}else{

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.error');
								$logMessage  = __('Cannot generate recurring payment (' .$agreement['PaymentRecurringAgreement']['name'] .') invoice.');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.critical');
								$logMessage  = __('Cannot generate user (#' .$agreement['PaymentPayer']['user_id'] .') recurring agreement invoice file (Recurring agreement ID: ' .$agreement['PaymentRecurringAgreement']['recurring_agreement_id'] .', Invoice ID: ' .$invoiceId .').');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							}

							// Step 5: save payment info into DB
							$transactionSaved = false;
							if(!empty($invoiceId)){

								$paymentTransaction = array(
									'PaymentPayPalGateway' => array(
										'payment_payer_id' 				 => $agreement['PaymentPayer']['id'],
										'payment_invoice_id'			 => $invoiceId,
										'payment_recurring_agreement_id' => $agreement['PaymentRecurringAgreement']['id'],
										'is_temp'						 => 0,
										'amount'				 		 => $rawPostArr['resource']['amount']['total'],
										'tax'							 => $tax,
										'transaction_fee'				 => $rawPostArr['resource']['transaction_fee']['value'],
										'currency'						 => $rawPostArr['resource']['amount']['currency'],
										'status'						 => $rawPostArr['resource']['state'],
										'payment_id' 					 => $rawPostArr['id'], //This is actually the webhook event ID. This can be used to resend the notification
										'sale_id'						 => $rawPostArr['resource']['id'],
										'intent'						 => $rawPostArr['resource']['resource_type'],
										'created'						 => $paidTimestamp,
										'modified'						 => $paidTimestamp
									)
								);
								$this->loadModel ( 'Payment.PaymentPayPalGateway' );
								if(!$this->PaymentPayPalGateway->saveTransaction($paymentTransaction)){

									$logType 	 = Configure::read('Config.type.payment');
									$logLevel 	 = Configure::read('System.log.level.error');
									$logMessage  = __('Recurring payment transaction record cannot be saved.');
									$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

									$logType 	 = Configure::read('Config.type.payment');
									$logLevel 	 = Configure::read('System.log.level.critical');
									$logMessage  = __('User (#' .$agreement['PaymentPayer']['user_id'] .') recurring payment transaction record cannot be saved (Invoice ID: ' .$invoiceId .', transaction details: ' .json_encode($paymentTransaction) .').');
									$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

								}else{

									$transactionSaved = true;
								}
							}

							if($transactionSaved && $isEmailSent){

								$this->User->activateAccount($agreement['PaymentRecurringAgreement']['service_account_user_id']); // Activate the service user account when everything is correctly done

							}else{

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.error');
								$logMessage  = __('Cannot send invoice email. Please go to <a href="/admin/dashboard#/admin/payment/payment_invoices/paidInvoiceIndex" target="_blank">payment paid invoice section</a> to check the invoice.');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $agreement['PaymentPayer']['user_id']);

								$logType 	 = Configure::read('Config.type.payment');
								$logLevel 	 = Configure::read('System.log.level.critical');
								$logMessage  = __('User (#' .$agreement['PaymentPayer']['user_id'] .') recurring payment transaction cannot be fully processed. Please check the log for this user, fix the issue and manually activate this user\'s service account');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
							}

							break;
					}

				}else{

					$logType 	 = Configure::read('Config.type.payment');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __('PayPal IPN validation failed. <br /><br />Validation request: ' .print_r($request->toJSON(), true) .'<br /><br />Validation response: ' .print_r($output, true) .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
				}

			} catch (Exception $ex) {

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __("PayPal Execute Payment Exception: ") .'<br />'.
						       __("Error Message: ") . $ex->getMessage() .'<br />'.
						       __("Line Number: ") .$ex->getLine() .'<br />'.
						       __("Trace: ") .$ex->getTraceAsString() .'<br />'.
      						   __('Log Data: ') .json_encode($ex->getData());
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				exit(1);
			}

		}
		exit();
	}

	private function __getApiContext() {

		// ### Api context
		// Use an ApiContext object to authenticate
		// API calls.

		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				Configure::read('Payment.paypal.rest.api.app.client.id'),
				Configure::read('Payment.paypal.rest.api.app.client.secret')
			)
		);

		// #### SDK configuration

		// Comment this line out and uncomment the PP_CONFIG_PATH
		// 'define' block if you want to use static file
		// based configuration

		$apiContext->setConfig(
			array(
				'mode' => Configure::read('Payment.paypal.rest.api.app.mode'),
				'http.ConnectionTimeOut' => 30,
				'http.Retry' => 3,
				'log.LogEnabled' => true,
				'log.FileName' => ROOT . DS . APP_DIR . DS .'tmp' .DS .'logs' .DS .'PayPal.log',
				'log.LogLevel' => 'FINE'
			)
		);

		if(!defined("PP_CONFIG_PATH")) {
			define("PP_CONFIG_PATH", ROOT . DS . APP_DIR . DS .'Plugin' .DS .'Payment' .DS .'Config' .DS .'paypal_sdk_config.ini');
		}

		return $apiContext;
	}

	private function __getInvoiceDetails($pendingInvoiceId, $isTempInvoice){
		$modelName = '';
		$model = null;

		if($isTempInvoice){
			$this->loadModel ( 'Payment.PaymentTempInvoice' );
			$model = $this->PaymentTempInvoice;
			$modelName = 'PaymentTempInvoice';
		}else{
			$this->loadModel ( 'Payment.PaymentInvoice' );
			$model = $this->PaymentInvoice;
			$modelName = 'PaymentInvoice';
		}

		$paymentInfo = $model->browseBy($model->primaryKey, $pendingInvoiceId, array("ClientUser" => array("Address")));
		$userInfo	 = $paymentInfo['ClientUser'];
		$paymentInfo = $paymentInfo[$modelName];

		$this->loadModel ( 'Payment.PaymentPayer' );
		$paypalPaymentMethods 	= Configure::read('Payment.method.paypal');
		$payer 					= $this->PaymentPayer->getPayerByPaymentMethod($userInfo['id'], strtoupper($paypalPaymentMethods['paypal']));
		if(!$payer){

			// Initial payment payer when client uses PayPal for the first time
			$this->PaymentPayer->savePayer(array(
				'PaymentPayer' => array(
					'user_id' 			=> $userInfo['id'],
					'payment_method' 	=> strtoupper($paypalPaymentMethods['paypal'])
				)
			));
			$payer = $this->PaymentPayer->getPayerByPaymentMethod($userInfo['id'], strtoupper($paypalPaymentMethods['paypal']));
		}
		$userInfo['PaymentPayer'] = $payer['PaymentPayer'];

		return [$userInfo, $paymentInfo];
	}

	private function __getBillingAddressDetails($userInfo){
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

		return [$billingAddress, $country];
	}

	private function __generateInvoiceFile($invoiceId, $invoice = null, $isRecurring = FALSE){

		if(empty($invoiceId) || !is_numeric($invoiceId)){
			return false;
		}

		$this->loadModel('Payment.PaymentInvoice');

		if(empty($invoice)){
			$invoice = $this->PaymentInvoice->browseBy('id', $invoiceId);
			if(empty($invoice)){
				return false;
			}
		}

		App::uses('CakeRequest', 'Network');
		App::uses('CakeResponse', 'Network');
		App::uses('PaymentInvoicesController', 'Payment.Controller');
		$request = new CakeRequest();
		$request->addParams(array('plugin' => 'Payment'));
		$PaymentInvoices = new PaymentInvoicesController($request, new CakeResponse());
		if(!$PaymentInvoices->admin_generateInvoiceFile($invoiceId)){ //No view controller action cannot be called using requestAction, no data returned.

			if(!$isRecurring){

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('Cannot generate invoice file (' .$invoice['PaymentInvoice']['number'] .').');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);
			}

			$logType 	 = Configure::read('Config.type.payment');
			$logLevel 	 = Configure::read('System.log.level.critical');
			if($isRecurring){
				$logMessage  = __('Cannot generate recurring payment invoice file (' .$invoice['PaymentInvoice']['number'] .').');
			}else{
				$logMessage  = __('User (#' .$this->superUserId .') cannot generate invoice file (' .$invoice['PaymentInvoice']['number'] .').');
			}

			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			return false;

		}else{
			return true;
		}
	}

	private function __sendInvoiceMail($invoiceId, $invoice = null, $isRecurring = FALSE){

		if(empty($invoiceId) || !is_numeric($invoiceId)){
			return false;
		}

		$this->loadModel('Payment.PaymentInvoice');

		if(empty($invoice)){
			$invoice = $this->PaymentInvoice->browseBy('id', $invoiceId);
			if(empty($invoice)){
				return false;
			}
		}

		if(!empty($invoice['PaymentInvoice']['is_emailed_client'])){

			if(!$isRecurring){

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('Cannot re-send invoice file via email (' .$invoice['PaymentInvoice']['number'] .').');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);
			}

			return true;
		}

		$postData = array(
			'data' => array(
				'PaymentInvoice' => array(
					'id' => $invoiceId
				)
			)
		);

		if($this->requestAction(DS .'system_email' .DS .'sendInvoiceEmail' .DS .$invoiceId, $postData)){

			$isEmailSent 									= true;
			$invoice['PaymentInvoice']['id'] 				= $invoiceId;
			$invoice['PaymentInvoice']['is_emailed_client'] = 1;
			$this->PaymentInvoice->updateInvoice($invoiceId, $invoice);

			return true;

		}else{

			if(!$isRecurring){

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				$logMessage  = __('Cannot send invoice file via email (' .$invoice['PaymentInvoice']['number'] .').');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);
			}

			$logType 	 = Configure::read('Config.type.payment');
			$logLevel 	 = Configure::read('System.log.level.critical');
			if($isRecurring){
				$logMessage  = __('Cannot send recurring payment invoice file via email (' .$invoice['PaymentInvoice']['number'] .').');
			}else{
				$logMessage  = __('User (#' .$this->superUserId .') cannot send invoice file via email (' .$invoice['PaymentInvoice']['number'] .').');
			}
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			return false;
		}
	}

	private function __updateRelatedData($payment, $isRecurring = FALSE, $recurringUserId = null, $updateRelatedData = null){

		if(empty($payment) && empty($updateRelatedData)){

			return false;
		}

		if(!empty($payment)){

			$data = isset($payment['related_update_data']) ? $payment['related_update_data'] : $payment['PaymentTempInvoice']['related_update_data'];
			$updateRelatedData = unserialize($data);

		}else{

			$payment = $updateRelatedData; // For log purpose
		}

		if(!empty($updateRelatedData) && is_array($updateRelatedData)){
			$this->loadModel ( $updateRelatedData['plugin'] .((isset($updateRelatedData['plugin']) && !empty($updateRelatedData['plugin'])) ? '.' : '') .$updateRelatedData['class'] );
			if(!empty($updateRelatedData['id'])){
				$this->$updateRelatedData['class']->id = $updateRelatedData['id'];
				$this->$updateRelatedData['class']->contain();
			}else{
				$this->$updateRelatedData['class']->create();
			}

			if(!$this->$updateRelatedData['class']->saveAll($updateRelatedData['data'], array('validate' => 'first'))){

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.error');
				if($isRecurring){
					$logMessage  = __('Cannot finish update process after creating recurring payment.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);
				}else{
					$logMessage  = __('Cannot finish update process after payment.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);
				}

				$logType 	 = Configure::read('Config.type.payment');
				$logLevel 	 = Configure::read('System.log.level.critical');
				if($isRecurring){
					$logMessage  = __('Cannot finish update process after creating recurring payment (' .json_encode($payment) .') for user (#' .$recurringUserId .').');
				}else{
					$logMessage  = __('User (#' .$this->superUserId .') cannot finish update process after payment (' .json_encode($payment) .').');
				}
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				return false;

			}else{
				//TODO soft delete tmp invoice
			}

			return true;

		}else{

			$logType 	 = Configure::read('Config.type.payment');
			$logLevel 	 = Configure::read('System.log.level.error');
			$logMessage  = __('Cannot finish corrupted update process after recurring payment.');
			if($isRecurring){
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);
			}else{
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);
			}

			$logType 	 = Configure::read('Config.type.payment');
			$logLevel 	 = Configure::read('System.log.level.critical');
			if($isRecurring){
				$logMessage  = __('Cannot finish corrupted update process after creating recurring payment (' .json_encode($payment) .') for user (#' .$recurringUserId .').');
			}else{
				$logMessage  = __('User (#' .$this->superUserId .') cannot finish corrupted update process after payment (' .json_encode($payment) .').');
			}
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			return false;
		}
	}

	private function __getAgreementDetailsByRecurringAgreementId($recurringAgreementId){

		if(empty($recurringAgreementId)){
			return false;
		}

		$a = $this->PaymentRecurringAgreement->browseBy('recurring_agreement_id', $recurringAgreementId);

		try {

			$apiContext = $this->__getApiContext();
			$agreement  = Agreement::get($recurringAgreementId, $apiContext);
			$details 	= $agreement->getAgreementDetails();

			$finalPaymentDate 	= $details->getFinalPaymentDate();
			$cyclesRemaining 	= $details->getCyclesRemaining();
			$outstandingBalance = $details->getOutstandingBalance();
			$lastPaymentAmount 	= $details->getLastPaymentAmount();
			$state				= $agreement->getState();

			$stateActive 		= Configure::read('Payment.paypal.gateway.agreement.status.active');

			if($a['PaymentRecurringAgreement']['active'] && ucfirst($state) != $stateActive){
				$a['PaymentRecurringAgreement']['active'] = 0;
				$a['PaymentRecurringAgreement']['status'] = ucfirst($state);
				$this->PaymentRecurringAgreement->updateRecurringAgreement($a['PaymentRecurringAgreement']['id'], $a);
			}elseif(empty($a['PaymentRecurringAgreement']['active']) && ucfirst($state) == $stateActive){
				$a['PaymentRecurringAgreement']['active'] = 1;
				$a['PaymentRecurringAgreement']['status'] = ucfirst($state);
				$this->PaymentRecurringAgreement->updateRecurringAgreement($a['PaymentRecurringAgreement']['id'], $a);
			}

			return array(
				'name'					=> $a['PaymentRecurringAgreement']['name'],
				'startTime' 			=> CakeTime::i18nFormat($a['PaymentRecurringAgreement']['start_time'], '%x %X'),
				'active' 				=> $a['PaymentRecurringAgreement']['active'],
				'outstandingBalance' 	=> $outstandingBalance->getCurrency() .' ' .$outstandingBalance->getValue(),
				'lastPaymentDate' 		=> CakeTime::i18nFormat(str_replace("Z", "", str_replace("T", " ", $details->getLastPaymentDate())), '%x %X'),
				'lastPaymentAmount' 	=> empty($lastPaymentAmount) ? '' : ($lastPaymentAmount->getCurrency() .' ' .$lastPaymentAmount->getValue()),
				'nextPaymentDate' 		=> CakeTime::i18nFormat(str_replace("Z", "", str_replace("T", " ", $details->getNextBillingDate())), '%x %X'),
				'finalPaymentDate' 		=> ($finalPaymentDate == "1970-01-01T00:00:00Z") ? "INFINITE" : CakeTime::i18nFormat(str_replace("Z", "", str_replace("T", " ", $agreement->getStartDate())), '%x %X'),
				'failedPaymentCount' 	=> $details->getFailedPaymentCount(),
				'cyclesCompleted' 		=> $details->getCyclesCompleted(),
				'cyclesRemaining' 		=> empty($cyclesRemaining) ? "INFINITE" : $cyclesRemaining,
				'state'					=> $state
			);

		} catch (Exception $e) {

			$logType 	 = Configure::read('Config.type.payment');
			$logLevel 	 = Configure::read('System.log.level.critical');
			$logMessage  = __("PayPal Get Agreement Details By Recurring Agreement ID Exception: ") .'<br />'.
					       __("Error Message: ") . $e->getMessage() .'<br />'.
					       __("Line Number: ") .$e->getLine() .'<br />'.
					       __("Trace: ") .$e->getTraceAsString();
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
		}

		return false;
	}
}
