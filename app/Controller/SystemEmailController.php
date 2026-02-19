<?php
/*
 * Copyright 2013, CrazyCMS.net (http://crazycms.net)
 *
 * Author: Charles Li
 *
 * Created on Aug 21, 2013
 */

 App::uses('AppController', 'Controller');
 App::uses('CakeEmail', 'Network/Email');
 App::import('Vendor', 'html2text', array('file' => 'soundasleep' .DS .'html2text' .DS .'html2text.php'));
/**
 * EmailMarketingPlans Controller
 *
 * @property EmailMarketingPlan $EmailMarketingPlan
 */
class SystemEmailController extends AppController {

    public function beforeFilter() {

    	// AppController beforeFilter() function contains permission check. We should let Auth allow the following actions before the permission check.
        $this->Auth->allow('sendContactEmail');
        $this->Auth->allow('sendNewUserActivateEmail');
        $this->Auth->allow('sendResetPasswordEmail');

        parent::beforeFilter();
    }

/**
 * send contact email method
 *
 * @return void
 */
    public function sendContactEmail() {
        $this->_prepareAjaxPostAction();

        if(!isset($this->request->data["author"]) || empty($this->request->data["author"])){
        	echo "Please fill in the name field";
        }else if(!isset($this->request->data["email"]) || empty($this->request->data["email"])){
        	echo "Please fill in the email field";
        }else if(!isset($this->request->data["phone"]) || empty($this->request->data["phone"])){
        	echo "Please fill in the phone field";
        }else if(!isset($this->request->data["text"]) || empty($this->request->data["text"])){
        	echo "Please fill in the message field";
        }else{
            try{
            	$companyEmail = $this->_getSystemDefaultConfigSetting('CompanyEmail', Configure::read('Config.type.system'));

                $Email = new CakeEmail('system');

                $this->request->data['companyEmail'] = $companyEmail;

                $Email->viewVars(array('data' => $this->request->data));

                if($Email->template('contact')->sender($this->request->data["email"], 'Visitor')->to($companyEmail)->subject(__('New quote from ' .$this->request->data["author"]))->send()){

                    echo __("Email has been sent successfully.");

                }else{

                	$logType 	 = Configure::read('Config.type.system');
                	$logLevel 	 = Configure::read('System.log.level.critical');
                	$logMessage  = __('Contact email (' .$this->request->data["email"] .') cannot be sent.');
                	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

                    echo __("Email sending failed.");
                }
            } catch (Exception $e) {
            	echo $e->getMessage();
            	echo $e->getTraceAsString();
            }
        }
    }

/**
 * send new user activate email method
 *
 * invoked by register process UsersController#activeNewUserAfterRegister via Ajax Post call
 * @return void
 */
    public function sendNewUserActivateEmail() {
        $this->_prepareAjaxPostAction();

        $companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
        $companyDomain = $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
        $companyEmail = $this->_getSystemDefaultConfigSetting('CompanyEmail', Configure::read('Config.type.system'));
        if($this->request->is('post') && isset($this->request->data["User"]["id"]) && isset($this->request->params["pass"][0]) && !empty($this->request->params["pass"][0]) && $this->request->data["User"]["id"] == $this->request->params["pass"][0]){
        	$this->loadModel('User', $this->request->params["pass"][0]);
            $token = $this->User->generateToken($this->request->params["pass"][0]);
            $userData = $this->User->read();

            if(!empty($userData["User"]["active"])){
            	// Do not send email when user has been activated.
            	return array('status' => Configure::read('System.variable.success'), 'message' => __("User has been activated already."));
            }

            try{

                $Email = new CakeEmail('system');
                $Email->viewVars(array(
                	'emailTitle' 		=> __('Thank you for signing up!'),
                    'token' 	 		=> $token,
                    'user'  	 		=> $userData,
                	'companyName'		=> $companyName,
                	'companyDomain'		=> $companyDomain,
                	'companyEmail'		=> $companyEmail
                ));

                if($Email->template('activation', 'notification')->to($userData["User"]["email"])->subject(__('Active your ' .$companyName .' account'))->send()){

                	$logType 	 = Configure::read('Config.type.user');
                	$logLevel 	 = Configure::read('System.log.level.info');
                	$logMessage  = __('Activate email (' .$userData["User"]["email"] .') has been sent.');
                	$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $userData["User"]['id']);

                    return array('status' => Configure::read('System.variable.success'), 'message' => __("Activation email has been sent successfully."));

                }else{

                	$logType 	 = Configure::read('Config.type.system');
                	$logLevel 	 = Configure::read('System.log.level.critical');
                	$logMessage  = __('Activate email (' .$userData["User"]["email"] .') cannot be sent.');
                	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

                    return array('status' => Configure::read('System.variable.error'), 'message' => __("Activation email sending failed."));

                }
            } catch (Exception $e) {

            	$logType 	 = Configure::read('Config.type.system');
            	$logLevel 	 = Configure::read('System.log.level.critical');
            	$logMessage  = __("Send Activate Email Exception: ") .'<br />'.
              				   __("Error Message: ") . $e->getMessage() .'<br />'.
		            		   __("Line Number: ") .$e->getLine() .'<br />'.
		            		   __("Trace: ") .$e->getTraceAsString() .'<br />'.
		            		   __('Log Passed Data: ') .json_encode($this->request->data) .'<br />'.
		            		   __('Log passed Params: ') .json_encode($this->request->params);
		        $this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

                return array('status' => Configure::read('System.variable.error'), 'message' => __('Sending user activation email error: ') .$e->getMessage());
            }
        }else{

        	$logType 	 = Configure::read('Config.type.system');
        	$logLevel 	 = Configure::read('System.log.level.critical');
        	$logMessage  = __('Activate email request was invalid. (Passed Activate Email Request Parameters: ' .json_encode($this->request->params) .')');
        	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

        	return array('status' => Configure::read('System.variable.error'), 'message' => __('Cannot send user activation email, please contact ' .$companyName .' Customer Service.'));
        }
    }

/**
 * send reset password email method
 *
 * @return Boolean
 */
    public function sendResetPasswordEmail(){
        if($this->request->is('post') && isset($this->request->data["User"]["id"]) && isset($this->request->params["pass"][0]) && !empty($this->request->params["pass"][0]) && $this->request->data["User"]["id"] == $this->request->params["pass"][0]){
            $this->loadModel('User', $this->request->params["pass"][0]);
            $token = $this->User->generateToken($this->request->params["pass"][0]);
            $userData = $this->User->read();

            $companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
            $companyDomain = $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
            $companyEmail = $this->_getSystemDefaultConfigSetting('CompanyEmail', Configure::read('Config.type.system'));

            try{
                $Email = new CakeEmail('system');
                $Email->viewVars(array(
                	'emailTitle' 	=> __('Reset password'),
                    'token' 		=> $token,
                    'user'  		=> $userData,
                	'companyName'	=> $companyName,
                	'companyDomain'	=> $companyDomain,
                	'companyEmail'	=> $companyEmail
                ));
                if($Email->template('reset_password', 'notification')->to($userData["User"]["email"])->subject(__('Reset your ' .$companyName .' account login password'))->send()){

                	$logType 	 = Configure::read('Config.type.user');
                	$logLevel 	 = Configure::read('System.log.level.info');
                	$logMessage  = __('Reset password email (' .$userData["User"]["email"] .') has been sent.');
                	$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $userData["User"]['id']);

                    return true;

                }else{

                	$logType 	 = Configure::read('Config.type.system');
                	$logLevel 	 = Configure::read('System.log.level.critical');
                	$logMessage  = __('Reset password email (' .$userData["User"]["email"] .') cannot be sent.');
                	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

                    return false;
                }
            } catch (Exception $e) {

            	$logType 	 = Configure::read('Config.type.system');
            	$logLevel 	 = Configure::read('System.log.level.critical');
            	$logMessage  = __("Send Reset Password Email Exception: ") .'<br />'.
            				   __("Error Message: ") . $e->getMessage() .'<br />'.
            				   __("Line Number: ") .$e->getLine() .'<br />'.
            				   __("Trace: ") .$e->getTraceAsString() .'<br />'.
            				   __('Log Passed Data: ') .json_encode($this->request->data) .'<br />'.
		            		   __('Log passed Params: ') .json_encode($this->request->params);
            	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

                return false;
            }
        }else{

        	$logType 	 = Configure::read('Config.type.system');
        	$logLevel 	 = Configure::read('System.log.level.critical');
        	$logMessage  = __('Reset password email request was invalid. (Passed Reset Password Email Request Parameters: ' .json_encode($this->request->params) .')');
        	$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

            return false;
        }
    }

/**
 * send invoice email method
 *
 * @return Boolean
 */
    public function sendInvoiceEmail(){

    	if($this->request->is('post') && isset($this->request->data["PaymentInvoice"]["id"]) && isset($this->request->params["pass"][0]) && !empty($this->request->params["pass"][0]) && $this->request->params["pass"][0] == $this->request->data["PaymentInvoice"]["id"]){
    		$this->loadModel('Payment.PaymentInvoice');
    		$invoiceId 	= $this->request->params["pass"][0];
    		$invoice 	= $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, array('ClientUser', 'Payment.PaymentPayPalGateway'));

    		try{
    			$invoiceFileName = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], true);
    			$invoiceFile 	 = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);

    			$invoice['PaymentInvoice']['plain_text_content'] = convert_html_to_text($invoice['PaymentInvoice']['content']);

    			$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
    			$companyDomain = $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    			$companyEmail = $this->_getSystemDefaultConfigSetting('CompanyEmail', Configure::read('Config.type.system'));

    			$Email = new CakeEmail('system');
    			$Email->viewVars(array(
    				'emailTitle' 		=> __('Payment Invoice'),
    				'invoice' 			=> $invoice,
    				'companyName'		=> $companyName,
                	'companyDomain'		=> $companyDomain,
                	'companyEmail'		=> $companyEmail,
    				'invoiceFileName' 	=> $invoiceFileName,
    				'invoiceFile' 		=> $invoiceFile
    			));
    			$Email->template('new_invoice')->to($invoice["ClientUser"]["email"])->subject(__('Invoice (#' .$invoice['PaymentInvoice']['number'] .') from ' .$companyName));

    			// Leave the code here as an example of using CakePHP attachments
//     			$Email->attachments(array(
//     				$invoiceFileName => array(
//     					'file' 		=>  $invoiceFile,
//     					'mimetype' 	=> 'application/pdf',
//     					'contentId' => $invoice['PaymentInvoice']['number']
//     				)
//     			));

    			if($Email->send()){

    				$logType 	 = Configure::read('Config.type.payment');
    				$logLevel 	 = Configure::read('System.log.level.info');
    				$logMessage  = __('Invoice email (' .$invoice["ClientUser"]["email"] .') has been sent. (Invoice NO. #' .$invoice['PaymentInvoice']['number'] .')');
    				$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $invoice['PaymentInvoice']['user_id']);

    				return true;
    			}else{

    				$logType 	 = Configure::read('Config.type.system');
    				$logLevel 	 = Configure::read('System.log.level.critical');
    				$logMessage  = __('Invoice email (' .$invoice["ClientUser"]["email"] .') cannot be sent. (Invoice NO. #' .$invoice['PaymentInvoice']['number'] .')');
    				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    				return false;
    			}
    		} catch (Exception $e) {

    			$logType 	 = Configure::read('Config.type.system');
    			$logLevel 	 = Configure::read('System.log.level.critical');
    			$logMessage  = __("Send Invoice Email Exception: ") .'<br />'.
    					__("Error Message: ") . $e->getMessage() .'<br />'.
    					__("Line Number: ") .$e->getLine() .'<br />'.
    					__("Trace: ") .$e->getTraceAsString() .'<br />'.
    					__('Invoice Data: ') .json_encode($invoice) .'<br />'.
    					__('Log Passed Data: ') .json_encode($this->request->data) .'<br />'.
    					__('Log passed Params: ') .json_encode($this->request->params);
    			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    			return false;
    		}
    	}else{

    		$logType 	 = Configure::read('Config.type.system');
    		$logLevel 	 = Configure::read('System.log.level.critical');
    		$logMessage  = __('Invoice email request was invalid. (Invoice Email Request Parameters: ' .json_encode($this->request->params) .')');
    		$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

    		return false;
    	}
    }

}
?>
