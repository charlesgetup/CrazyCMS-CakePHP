<?php
App::uses ( 'EmailMarketingAppShell', 'EmailMarketing.Console/Command' );
class SendCampaignEmailShell extends EmailMarketingAppShell {

	public $uses = array(
		'EmailMarketing.EmailMarketingCampaign',
		'EmailMarketing.EmailMarketingSubscriber',
		'EmailMarketing.EmailMarketingStatistic',
		'EmailMarketing.EmailMarketingSender'
	);

	public $tasks = array('EmailMarketing.Email', 'EmailMarketing.Encryption');

	public $campaign 		= null;
	public $subscriber 		= null;
	public $statistics 		= null;
	public $originalUserId 	= null;

	public $isTest 			= false;

/**
 * The parser is used after Shell::initialize(), but before Shell::startup().
 * This means if the arguments and options are invalid, only Shell::initialize() is run.
 * @see Shell::getOptionParser()
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addArgument('type', array(
			'required'	=> true,
			'help' 		=> 'Email type',
			'default' 	=> 'CAMPAIGN',
			'choices'	=> array("CAMPAIGN", "SYSTEM", "TEST")
		))->addOption('campaign', array(
			'short' 	=> 'c',
			'help' 		=> __('The specific campaign ID.')
		))->addOption('subscriber', array(
			'short' 	=> 's',
			'help' 		=> __('The specific subscriber ID.')
		))->addOption('statistics', array(
			'short' 	=> 't',
			'help' 		=> __('The specific statistics ID.')
		))->addOption('test', array(
			'short' 	=> 'e',
			'default' 	=> '0',
			'help' 		=> __('Send test email or not. (0 => not test or sample subscriber ID)')
		))->description(
			__('Use PHPMailer to send email')
		);

		return $parser;
	}

/**
 * This method runs first
 * @see Shell::initialize()
 */
	public function initialize(){
		parent::initialize();
	}

/**
 * This method runs after Shell::initialize()
 * Because the passed arguments and options can be accessed here, we do the preparation for the command.
 * @see Shell::startup()
 */
	public function startup(){
		parent::startup();

		// validate test arg
		if(!empty($this->params['test'])){
			$this->isTest = $this->params['test'];
		}

		// Validate campaign ID
		$this->campaign 	= $this->__validateOption('campaign', $this->EmailMarketingCampaign, array('EmailMarketingSender', 'EmailMarketingUser' => array('User')));

		// validate subscriber ID
		$this->subscriber 	= $this->__validateOption('subscriber', $this->EmailMarketingSubscriber);

		// validate statistics ID
		$this->statistics 	= $this->__validateOption('statistics', $this->EmailMarketingStatistic);

		// Make sure the statistic is related to the given campaign
		if($this->statistics['EmailMarketingStatistic']['email_marketing_campaign_id'] != $this->campaign['EmailMarketingCampaign']['id']){

			$this->out(
					'<error>' .__('Please provide a valid statistics ID') .'</error>'
			);

			// Log this to client & system
			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.error');
			$logMessage  = __('Email marketing campaign email cannot be sent. This error has been reported and sorry about the inconvenience.');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $this->superUserId);

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.critical');
			$logMessage  = __('Email marketing campaign email cannot be sent, statistics ID was not related to given campaign. (User ID: ' .$this->superUserId .', Campaign ID: ' .$this->campaign['EmailMarketingCampaign']['id'] .', Statistics ID: ' .$this->statistics['EmailMarketingStatistic']['id'] .')');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			exit();
		}

		/* The following process is unnecessary, because the suscriber was found based on campaign-mailing_list DB relationship */
		// Make sure the subscriber is related to the given campaign
// 		$campaignValidateCount = $this->EmailMarketingCampaign->find('count', array(
// 			'joins' => array(
// 	            array(
// 	                'table' => 'email_marketing_campaign_lists',
// 	                'alias' => 'EmailMarketingCampaignList',
// 	                'type' => 'inner',
// 	                'conditions' => array(
// 	                    'EmailMarketingCampaign.id = EmailMarketingCampaignList.email_marketing_campaign_id',
// 	                	'EmailMarketingCampaignList.email_marketing_campaign_id' 	=> $this->campaign['EmailMarketingCampaign']['id'],
// 	                	'EmailMarketingCampaignList.email_marketing_list_id' 		=> $this->subscriber['EmailMarketingSubscriber']['email_marketing_list_id']
// 	                )
// 	            )
// 			)
// 		));
// 		if($campaignValidateCount != 1){
// 			//TODO make this fatal error and logs it and email notification
// 			$this->out(
// 				'<error>' .__('Please provide a valid subscriber ID') .'</error>'
// 			);
// 			exit();
// 		}
	}

/**
 * This method runs after Shell::startup()
 * This method defines the main logic, and call different tasks or proviate method to do the actual job.
 */
	public function main() {
		$textEmailSentFormat = Configure::read('EmailMarketing.email.type.text');
		$xorMask = $this->_getSystemDefaultConfigSetting('XORMask', Configure::read('Config.type.emailmarketing'));

		// Initialize email task & override settings with user specific data
		if(isset($this->campaign ["EmailMarketingCampaign"] ["from_email_address_prefix"]) && isset($this->campaign ["EmailMarketingSender"] ["sender_domain"]) && filter_var($this->campaign ["EmailMarketingCampaign"] ["from_email_address_prefix"] ."@" .$this->campaign ["EmailMarketingSender"] ["sender_domain"], FILTER_VALIDATE_EMAIL)){
			$this->Email->From 			= $this->campaign ["EmailMarketingCampaign"] ["from_email_address_prefix"] ."@" .$this->campaign ["EmailMarketingSender"] ["sender_domain"];
		}else{
			$useDefaultFrom				= true;
			$this->Email->From 			= $this->_getSystemDefaultConfigSetting('DefaultEmailFrom', Configure::read('Config.type.emailmarketing'));
		}

		$superUserId 					= $this->EmailMarketingCampaign->emailMarketingUserIdToSuperUserId($this->campaign ["EmailMarketingCampaign"] ["email_marketing_user_id"]);

		$this->Email->FromName 			= empty ( $this->campaign ["EmailMarketingUser"] ["User"] ["company"] ) ? "" : $this->campaign ["EmailMarketingUser"] ["User"] ["company"];
		$this->Email->Sender 			= $this->Email->From;
		$this->Email->Subject 			= $this->campaign ["EmailMarketingCampaign"] ["subject"];
		if(isset($this->campaign ["EmailMarketingCampaign"] ["send_format"]) && !empty($this->campaign ["EmailMarketingCampaign"] ["send_format"])){
			$this->Email->SendFormat 	= ($this->campaign ["EmailMarketingCampaign"] ["send_format"] == $textEmailSentFormat) ? $textEmailSentFormat : Configure::read('EmailMarketing.email.type.html');
		}
		if(isset($this->Email->SendFormat) && $this->Email->SendFormat == $textEmailSentFormat){
			$this->Email->Body 			= $this->campaign ["EmailMarketingCampaign"] ["text_message"];
		}else{
			$this->Email->Body 			= $this->campaign ["EmailMarketingCampaign"] ["ready_to_go_email_body"];
			if(!empty($this->campaign ["EmailMarketingCampaign"] ["text_message"]) && $this->Email->SendFormat == Configure::read('EmailMarketing.email.type.both')){
				$this->Email->AltBody 	= $this->campaign ["EmailMarketingCampaign"] ["text_message"];
			}
		}
		$this->Email->MessageID 		= $this->Encryption->base64Encode ( $this->campaign ["EmailMarketingCampaign"] ["id"] . '-' . $this->subscriber ["EmailMarketingSubscriber"] ["id"] . '-' . $this->statistics ['EmailMarketingStatistic'] ['id'], $xorMask );
		$this->Email->To 				= $this->subscriber ["EmailMarketingSubscriber"] ["email"];
		$this->Email->ToName 			= $this->subscriber ["EmailMarketingSubscriber"] ["first_name"] . " " . $this->subscriber ["EmailMarketingSubscriber"] ["last_name"];

		if(!isset($useDefaultFrom) || !$useDefaultFrom){
			$this->Email->DKIM_identity		= $this->Email->From;
			$this->Email->DKIM_domain		= array_pop(explode("@", $this->Email->From));
// 			$this->Email->DKIM_passphrase	= $this->_getSystemDefaultConfigSetting('DKIMPassphrase', Configure::read('Config.type.emailmarketing'));

			$sender = $this->EmailMarketingSender->getSenderByDomain($this->Email->DKIM_domain);
			if(!$sender){
				$this->out(
						'<error>' .__('Email Sender Domain is not registered!') .'</error>'
				);
				exit();
			}
			$this->Email->DKIM_private = $sender['EmailMarketingSender']['dkim_privkey'];
			if(!$this->Email->DKIM_private){
				$this->out(
						'<error>' .__('Email Sender Domain DKIM private key is missing!') .'</error>'
				);
				exit();
			}
		}

		$bounceToMailBox 				= $this->_getSystemDefaultConfigSetting('BounceToMailBox', Configure::read('Config.type.emailmarketing'), $this->campaign['EmailMarketingUser']['User']['parent_id']);
		if(!empty($bounceToMailBox)){
			$this->Email->BounceToMailBox 	= $bounceToMailBox;
		}

		if(empty($this->Email->Body)){
			$this->out(
				'<error>' .__('Email body is missing! Please use the UI to generate the email content first.') .'</error>'
			);
			exit();
		}else{
			// Replace marks
			if (! empty ( $this->Email->Body )) {
				$this->Email->Body = $this->__replaceMarks ( $this->Email->Body, $this->Email->MessageID, $this->subscriber ["EmailMarketingSubscriber"] );
			}
			if (! empty ( $this->Email->AltBody )) {
				$this->Email->AltBody = $this->__replaceMarks ( $this->Email->AltBody, $this->Email->MessageID, $this->subscriber ["EmailMarketingSubscriber"] );
			}

			// Remove unused markup
			preg_match_all ( '/\[.*\%\%([^\]]+)\]/Ui', $this->Email->Body, $matches );
			for($i = 0; $i < count ( $matches [0] ); $i ++) {
				$this->Email->Body = str_ireplace ( $matches [0] [$i], $matches [1] [$i], $this->Email->Body );
			}
			if (! empty ( $this->Email->AltBody )) {
				// Remove unused markup
				preg_match_all ( '/\[.*\%\%([^\]]+)\]/Ui', $this->Email->AltBody, $matches );
				for($i = 0; $i < count ( $matches [0] ); $i ++) {
					$this->Email->AltBody = str_ireplace ( $matches [0] [$i], $matches [1] [$i], $this->Email->AltBody );
				}
			}

			// Force add unsubscribe link
			$companyDomain = $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
			if (stripos ( $this->Email->Body, '</body>' )) {
				$this->Email->Body = str_replace ( '</body>', '<div style="display: block !important; width: 100% !important; height: 20px !important; visibility: visible !important; text-align: center; padding-top: 50px; color: black !important; clear: both;">' .__('Click') .' <a href="http://' .$companyDomain .'/email_marketing/email_marketing_subscribers/unsubscribe/' .$this->Email->MessageID .'" target="_blank"  style="display: inline-block !important; visibility: visible !important; color: black !important; font-size: 14px !important;">' .__('here') .'</a> ' .__('to unsubscribe.') .'</div></body>', $this->Email->Body );
			}else{
				$this->Email->Body .= "\n\n" .__("Unsubscribe Link: http://{$companyDomain}/email_marketing/email_marketing_subscribers/unsubscribe/{$this->Mail->MessageID}");
			}

			// In shell task, startup() function is not called. We need to do this manually.
			if(empty($this->Email->superUserId) && !empty($this->params['user_id'])){
				$this->Email->superUserId = $this->params['user_id'];
			}
			$result = $this->Email->execute ();

			if ($result == true) {

				if($this->statistics ['EmailMarketingStatistic'] ['status'] == "PENDING"){
					$this->statistics ['EmailMarketingStatistic'] ['status'] = "SENDING";
					// $this->statistics ['EmailMarketingStatistic'] ['processed'] ++;
					$this->EmailMarketingStatistic->updateStatistic ( $this->statistics ['EmailMarketingStatistic'] ['id'], $this->statistics );
				}

			} else {

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.debug');
				$logMessage  = __('Sending email (' .$this->subscriber ["EmailMarketingSubscriber"] ["email"] .') - failed: ' .print_r($result, true));
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $this->superUserId);

			}

			$this->out($result);
		}
	}

	private function __validateOption($optionName, $optionClassObj, $queryContain = false){

		if($this->isTest && $optionName == "subscriber" && filter_var ( $this->params[$optionName], FILTER_VALIDATE_EMAIL )){
			if(is_numeric($this->isTest)){

				// In test scenario, if client has linked some mailing list which contains valid subscribers to the campaign already, the real subscriber will be used for testing.
				// In this way, client can test the embeded attributes replacement.
				// So if the subscriber ID is provided, use the normal way to get the subscriber. Then later substitute the email with the testing ones.
				$testEmail = $this->params[$optionName];
				$this->params[$optionName] = $this->isTest;
				$this->isTest = $testEmail;

			}else{
				$sampleSubscriber = array(
					'EmailMarketingSubscriber' => array(
						'id'			=> md5($this->params[$optionName]),
						'first_name' 	=> '',
						'last_name' 	=> '',
						'email' 		=> $this->params[$optionName],
						'extra_attr' 	=> '',
						'excluded' 		=> 0,
						'unsubscribed' 	=> 0,
						'deleted' 		=> 0,
					)
				);
				return $sampleSubscriber;
			}
		}

		if(!isset($this->params[$optionName]) || empty($this->params[$optionName])){

			$this->out(
				'<warning>' .__('Please provide a valid ' .$optionName .' ID') .'</warning>'
			);

			// Log this to client & system
			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.error');
			$logMessage  = __('Email marketing campaign email cannot be sent. This error has been reported and sorry about the inconvenience.');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $this->superUserId);

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.critical');
			$logMessage  = __('Email marketing campaign email cannot be sent, ' .$optionName .' ID was missing. (User ID: ' .$this->superUserId .')');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			exit();
		}

		if (! $optionClassObj->exists ( $this->params[$optionName] )) {

			$this->out(
				'<error>' .__('Please provide a valid ' .$optionName .' ID') .'</error>'
			);

			// Log this to client & system
			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.error');
			$logMessage  = __('Email marketing campaign email cannot be sent. This error has been reported and sorry about the inconvenience.');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $this->superUserId);

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.critical');
			$logMessage  = __('Email marketing campaign email cannot be sent, ' .$optionName .' ID value is invalid. (User ID: ' .$this->superUserId .', ' .$optionName .' ID value: ' .$this->params[$optionName] .')');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			exit();
		}

		$optionInstance = $optionClassObj->browseBy ( $optionClassObj->primaryKey, $this->params[$optionName], $queryContain );

		if($this->isTest && $optionName == "subscriber" && filter_var ( $this->isTest, FILTER_VALIDATE_EMAIL )){
			$optionInstance['EmailMarketingSubscriber']['email'] = $this->isTest;
		}

		return $optionInstance;
	}

/**
 * Replace place holder mark with real data in email body
 *
 * Known marks are below
 * [FIRST-NAME]
 * [LAST-NAME]
 * [EMAIL]
 * [EMAIL-MESSAGE-ID]
 * [SUBSCRIBER-ID]
 * [SUBSCRIBER-ENCRYPTED-ID]
 *
 * @param string $emailContent
 * @param string $messageId
 * @param string $subscriberId
 */
	//TODO let it support more client defined marks
	private function __replaceMarks($emailContent, $messageId, $subscriber) {
		$xorMask = $this->_getSystemDefaultConfigSetting('XORMask', Configure::read('Config.type.emailmarketing'));

		$emailContent = preg_replace ( '/[\[]FIRST-NAME[\]]/', $subscriber ["first_name"], $emailContent );
		$emailContent = preg_replace ( '/[\[]LAST-NAME[\]]/', $subscriber ["last_name"], $emailContent );
		$emailContent = preg_replace ( '/[\[]EMAIL[\]]/', $subscriber ["email"], $emailContent );
		$emailContent = preg_replace ( '/[\[]EMAIL-MESSAGE-ID[\]]/', $messageId, $emailContent );
		$emailContent = preg_replace ( '/[\[]SUBSCRIBER-ID[\]]/', $subscriber ["id"], $emailContent );
		$emailContent = preg_replace ( '/[\[]SUBSCRIBER-ENCRYPTED-ID[\]]/', $this->Encryption->base64Encode ( $subscriber ["id"], $xorMask ), $emailContent );

		if(!empty($subscriber['extra_attr']) && is_string($subscriber['extra_attr'])){
			$subscriber['extra_attr'] = unserialize($subscriber['extra_attr']);
			if(!empty($subscriber['extra_attr']) && is_array($subscriber['extra_attr'])){
				foreach($subscriber['extra_attr'] as $search => $replacement){
					if(!empty($search) && !empty($replacement)){
						$emailContent = preg_replace ( '/[\[]' .$search .'[\]]/', $replacement, $emailContent );
					}
				}
			}
		}

		return $emailContent;
	}
}
?>