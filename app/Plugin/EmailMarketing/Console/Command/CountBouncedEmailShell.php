<?php
/**
 * This shell should be added into cron job list
 */

App::uses ( 'EmailMarketingAppShell', 'EmailMarketing.Console/Command' );
class CountBouncedEmailShell extends EmailMarketingAppShell {

	public $uses = array(
		'Log',
		'EmailMarketing.EmailMarketingSubscriberBounceRecord'
	);

	public $tasks = array('EmailMarketing.Encryption');

	public $bounceMailboxHost;
	public $bounceMailboxPort;
	public $bounceMailboxUser;
	public $bounceMailboxPassword;

	public $xorMask;

	public $softBounceType;
	public $hardBounceType;
	public $bounceCodeTable;

	public $maxBouncedEmailProcessed;

/**
 * The parser is used after Shell::initialize(), but before Shell::startup().
 * This means if the arguments and options are invalid, only Shell::initialize() is run.
 * @see Shell::getOptionParser()
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		return $parser;
	}

/**
 * This method runs first
 * @see Shell::initialize()
 */
	public function initialize(){
		parent::initialize();
		$this->bounceMailboxHost		= $this->_getSystemDefaultConfigSetting('BounceToMailBoxHost', Configure::read('Config.type.emailmarketing'));
		$this->bounceMailboxPort		= $this->_getSystemDefaultConfigSetting('BounceToMailBoxPort', Configure::read('Config.type.emailmarketing'));
		$this->bounceMailboxUser		= $this->_getSystemDefaultConfigSetting('BounceToMailBoxUsername', Configure::read('Config.type.emailmarketing'));
		$this->bounceMailboxPassword	= $this->_getSystemDefaultConfigSetting('BounceToMailBoxPassword', Configure::read('Config.type.emailmarketing'));

		$this->softBounceType 			= Configure::read('EmailMarketing.email.softBounce.type');
		$this->hardBounceType 			= Configure::read('EmailMarketing.email.hardBounce.type');
		$this->bounceCodeTable 			= array(
			"421" => array($this->softBounceType, __('Service not available')),
			"450" => array($this->softBounceType, __('Mailbox unavailable')),
			"451" => array($this->softBounceType, __('Error in processing')),
			"452" => array($this->softBounceType, __('Insufficient system storage')),
			"500" => array($this->hardBounceType, __('Address does not exist')),
			"510" => array($this->hardBounceType, __('Other address status')),
			"511" => array($this->hardBounceType, __('Bad destination mailbox address')),
			"512" => array($this->hardBounceType, __('Bad destination system address')),
			"513" => array($this->hardBounceType, __('Bad destination mailbox address syntax')),
			"514" => array($this->hardBounceType, __('Destination mailbox address ambiguous')),
			"515" => array($this->hardBounceType, __('Destination mailbox address valid')),
			"516" => array($this->hardBounceType, __('Mailbox has moved')),
			"517" => array($this->hardBounceType, __('Bad sender\'s mailbox address syntax')),
			"518" => array($this->hardBounceType, __('Bad sender\'s system address')),
			"520" => array($this->softBounceType, __('Other or undefined mailbox status')),
			"521" => array($this->softBounceType, __('Mailbox disabled, not accepting messages')),
			"522" => array($this->softBounceType, __('Mailbox full')),
			"523" => array($this->hardBounceType, __('Message length exceeds administrative limit')),
			"524" => array($this->hardBounceType, __('Mailing list expansion problem')),
			"530" => array($this->hardBounceType, __('Other or undefined mail system status')),
			"531" => array($this->softBounceType, __('Mail system full')),
			"532" => array($this->hardBounceType, __('System not accepting network messages')),
			"533" => array($this->hardBounceType, __('System not capable of selected features')),
			"534" => array($this->hardBounceType, __('Message too big for system')),
			"540" => array($this->hardBounceType, __('Other or undefined network or routing status')),
			"541" => array($this->hardBounceType, __('No answer from host')),
			"542" => array($this->hardBounceType, __('Bad connection')),
			"543" => array($this->hardBounceType, __('Routing server failure')),
			"544" => array($this->hardBounceType, __('Unable to route')),
			"545" => array($this->softBounceType, __('Network congestion')),
			"546" => array($this->hardBounceType, __('Routing loop detected')),
			"547" => array($this->hardBounceType, __('Delivery time expired')),
			"550" => array($this->hardBounceType, __('Other or undefined protocol status')),
			"551" => array($this->hardBounceType, __('Invalid command')),
			"552" => array($this->hardBounceType, __('Syntax error')),
			"553" => array($this->softBounceType, __('Too many recipients')),
			"554" => array($this->hardBounceType, __('Invalid command arguments')),
			"555" => array($this->hardBounceType, __('Wrong protocol version')),
			"560" => array($this->hardBounceType, __('Other or undefined media error')),
			"561" => array($this->hardBounceType, __('Media not supported')),
			"562" => array($this->hardBounceType, __('Conversion required and prohibited')),
			"563" => array($this->hardBounceType, __('Conversion required but not supported')),
			"564" => array($this->hardBounceType, __('Conversion with loss performed')),
			"565" => array($this->hardBounceType, __('Conversion failed')),
			"570" => array($this->hardBounceType, __('Other or undefined security status')),
			"571" => array($this->hardBounceType, __('Delivery not authorized, message refused')),
			"572" => array($this->hardBounceType, __('Mailing list expansion prohibited')),
			"573" => array($this->hardBounceType, __('Security conversion required but not possible')),
			"574" => array($this->hardBounceType, __('Security features not supported')),
			"575" => array($this->hardBounceType, __('Cryptographic failure')),
			"576" => array($this->hardBounceType, __('Cryptographic algorithm not supported')),
			"577" => array($this->hardBounceType, __('Message integrity failure')),
			"911" => array($this->hardBounceType, __('Hard bounce with no bounce code found It could be an invalid email or rejected email from your mail server (such as from a sending limit)'))
		);

		$this->maxBouncedEmailProcessed = 10000;
	}

/**
 * This method runs after Shell::initialize()
 * Because the passed arguments and options can be accessed here, we do the preparation for the command.
 * @see Shell::startup()
 */
	public function startup(){
		parent::startup();

		$this->xorMask = $this->_getSystemDefaultConfigSetting('XORMask', Configure::read('Config.type.emailmarketing'));
	}

/**
 * This method runs after Shell::startup()
 * This method defines the main logic, and call different tasks or proviate method to do the actual job.
 */
	public function main() {

		set_time_limit(6000);

		// Open mailbox
		$link = imap_open("{".$this->bounceMailboxHost.":".$this->bounceMailboxPort."/imap/ssl/novalidate-cert}INBOX",$this->bounceMailboxUser,$this->bounceMailboxPassword,CL_EXPUNGE) or die(imap_last_error()) or die("can't connect: ".imap_last_error());


		if (!$link) {
			//TODO error
			$this->out(
				'<error>' .__('Cannot access bounce mail box') .'</error>'
			);
			exit();
		}

		$this->__processMessages($link, $this->maxBouncedEmailProcessed);
	}

	private function __decodeBody($header, $body){
	    $transfer_encoding = '';
	    if (preg_match('/Content-Transfer-Encoding: ([\w-]+)/i', $header, $regs)) {
	        $transfer_encoding = strtolower($regs[1]);
	    }
	    switch ($transfer_encoding) {
	        case 'quoted-printable':
	            $decoded_body = @imap_qprint($body);
	            break;
	        case 'base64':
	            $decoded_body = @imap_base64($body);
	            break;
	        case '7bit':
	        case '8bit':
	        default:

	    }
	    if (!empty($decoded_body)) {
	        return $decoded_body;
	    } else {
	        return $body;
	    }
	}

	private function __parseBounceBody($body, $pattern){
		$result = preg_match($pattern, $body, $matches);
		if($result > 0){
			return $matches[1];
		}else{
			return false;
		}
	}

	private function __processImapBounce ($link, $emailNum, $emailHeader) {
		$headerInfo = imap_headerinfo($link,$emailNum);
		$bounceDate = @strtotime($headerInfo->date);
		$body 		= imap_body ($link, $emailNum);
		$body 		= $this->__decodeBody($emailHeader, $body);

		$msgId 	= $this->__parseBounceBody($body, "/X-MessageID: (.+)\\n/");
		$msgId = $this->Encryption->base64Decode($msgId, $this->xorMask);
		list ( $campaignId, $subscriberId, $statisticId ) = explode ( '-', $msgId );

		$bounceCode = $this->__parseBounceBody($body, "/Status: (.+)\\n/");
		$bounceCode = trim($bounceCode);
		if(!empty($bounceCode)){
			$bounceCode = str_replace(".", "", $bounceCode);
			$bounceCode = intval($bounceCode);
			$bounceReason = @$this->bounceCodeTable[$bounceCode][1];
			if(!empty($bounceReason)){
				$bounceReason = "[" .__("Error code") .": {$bounceCode}] " .$bounceReason;
				$bounceType = $this->bounceCodeTable[$bounceCode][0];
			}
		}else{
			$logData = array(
				'Log' => array(
					'user_id' => $this->superUserId,
					'type' => Configure::read('Config.type.emailmarketing'),
					'message' => __('Error: Cannot get bounce error code.') .'<br /><br />' .$body,
					'timestamp' => date('Y-m-d H:i:s')
				)
			);
			$this->Log->saveLog($logData);
			return false;
		}
		$bounceData = array(
			'EmailMarketingSubscriberBounceRecord' => array(
				'email_marketing_statistic_id' 	=> $statisticId,
				'email_marketing_subscriber_id' => $subscriberId,
				'bounce_type' 					=> @$bounceType,
				'bounce_reason' 				=> @$bounceReason,
				'timestamp' 					=> date('Y-m-d H:i:s')
			)
		);

		$existingBounceRecordId = $this->EmailMarketingSubscriberBounceRecord->checkExistingBounceRecord($statisticId, $subscriberId);
		if(empty($existingBounceRecordId)){
			$this->EmailMarketingSubscriberBounceRecord->saveSubscriberBounceRecord($bounceData);
		}else{
			$bounceData['EmailMarketingSubscriberBounceRecord']['id'] = $existingBounceRecordId;
			$this->EmailMarketingSubscriberBounceRecord->updateSubscriberBounceRecord($existingBounceRecordId, $bounceData);
		}

		return true;
	}

	private function __processMessages($link, $maxBouncedEmailProcessed = 3000){

		// Get total number of bounced emails system need to process
		$totalBouncedEmailNum = imap_num_msg($link);

		// How many emails the system can process at one time
		if($maxBouncedEmailProcessed < $totalBouncedEmailNum){
			$totalBouncedEmailNum = $maxBouncedEmailProcessed;
		}

		if ($totalBouncedEmailNum == 0) {
			imap_close($link);
			return '';
		}

		if($totalBouncedEmailNum > $maxBouncedEmailProcessed){
			$totalBouncedEmailNum = $maxBouncedEmailProcessed;
		}

		// Loop process bounced emails
		for($i = 1; $i <= $totalBouncedEmailNum; $i++){

			// Fetch the email header
			$header = imap_fetchheader($link, $i);

			// Output progress after processed 25 messages
			if ($i % 25 == 0) {
	            $this->out(
					'<success>' .__('Processed ' .$i .' messages') .'</success>'
				);
	        }
	        echo PHP_EOL;
	        flush();

			// Process the fetched email
			$processed = $this->__processImapBounce($link, $i, $header);

			if($processed){
				imap_delete($link, $i); // Delete processed bounce email
			}else{
				imap_delete($link, $i); // Record the email body in logs for further investigation
			}

			flush();
		}

		flush();
		set_time_limit(60 * $totalBouncedEmailNum);
		imap_close($link);
	}
}
?>