<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require ROOT .DS .'app' .DS .'Vendor' .DS .'autoload.php';

App::uses ( 'EmailMarketingAppShell', 'EmailMarketing.Console/Command' );
class EmailTask extends EmailMarketingAppShell {

	/**
	 * Send email using SMTP Auth by default.
	 */
	public $CharSet 		= '';
	public $Encoding    	= '';
	public $ContentType 	= '';
	public $MessageID 		= '';
	public $Timeout 		= '';
	public $WordWrap 		= '';
	public $SendFormat      = '';
	public $action_function = ''; // Callback function
	public $TimeStamp		= '';

	// If sender is not from crazycms.net, make sure add the following TXT record in the email domain DNS (Zone) settings.
	// Note: the SPF record content follows the following rules and DKIM record is generated using command: opendkim-genkey
	//
	// Name									TTL		Class	Type	Record
	// aroundyou.info.						14400	IN		TXT		"v=spf1 a mx include:crazycms.net ~all" ( If you have some existing SPF, just add it like this: "v=spf1 a mx include:exampledomain.com include:crazycms.net ip4:198.38.88.22 ~all")
	// crazycms._domainkey					14400	IN		TXT		"v=DKIM1; k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwHCPElbU6vchIvbg7PWLzxYQ/56NSa6zsDyiB0h/ct3u9+JMO+s07FSxuuibw3c7ASJukAqpvOM3UHNdjsIFgxxShoZcfcRBcjWNPhcycKjYx/gS9d0CdvFzShGs9JQRL9436AYWb5Sd9RNZI7MtSDcHEGzOX0LXpf8ye6osGfQIDAQAB;"
	// crazycms._domainkey.aroundyou.info	14400	IN		TXT		"v=DKIM1; k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwHCPElbU6vchIvbg7PWLzxYQ/56NSa6zsDyiB0h/ct3u9+JMO+s07FSxuuibw3c7ASJukAqpvOM3UHNdjsIFgxxShoZcfcRBcjWNPhcycKjYx/gS9d0CdvFzShGs9JQRL9436AYWb5Sd9RNZI7MtSDcHEGzOX0LXpf8ye6osGfQIDAQAB;"

	public $From         	= '';
	public $FromName     	= ''; // Company name or "from" email address
	public $Sender 			= '';
	public $To         		= '';
	public $ToName     		= '';
	public $Subject 		= '';
	public $Body 			= ''; // Html or Text body
	public $AltBody 		= ''; // if user choose "both", then use this var to store text message
	public $Helo 			= '';
	public $Attachments 	= array();

	public $Mailer 			= 'smtp';
	public $Username		= ''; // Default mail server user name
	public $Password		= ''; // Default mail server login password
	public $Host 			= ''; // Default mail server
	public $Hostname 		= '';
	public $Port 			= '';
	public $SMTPKeepAlive 	= true;
	public $SMTPDebug  		= 2;
	public $Debugoutput		= 'error_log';
	public $SMTPAuth		= true;
	public $SMTPSecure		= 'tls';
	public $AuthType		= 'PLAIN LOGIN';

	public $BounceToMailBox = '';

	public $DKIM_selector 		= '';
	public $DKIM_identity 		= 'CrazySoftMail';
	public $DKIM_passphrase 	= ''; // No passphrase
	public $DKIM_domain 		= '';
	public $DKIM_private 		= '';
	public $DKIM_private_string = '';

	public $Mail 			= null;

/**
 * @see Shell::initialize()
 *
 */
    public function initialize() {
    	parent::initialize();

    	// Set up default settings
    	$this->CharSet			= $this->_getSystemDefaultConfigSetting('CharSet', Configure::read('Config.type.emailmarketing'));
    	$this->Encoding			= $this->_getSystemDefaultConfigSetting('Encoding', Configure::read('Config.type.emailmarketing'));
    	$this->ContentType		= $this->_getSystemDefaultConfigSetting('ContentType', Configure::read('Config.type.emailmarketing'));
    	$this->Timeout			= $this->_getSystemDefaultConfigSetting('SMTPServerTimeout', Configure::read('Config.type.emailmarketing'));
    	$this->WordWrap			= $this->_getSystemDefaultConfigSetting('WordWrap', Configure::read('Config.type.emailmarketing'));
//     	$this->SendFormat 		= $this->_getSystemDefaultConfigSetting('SendFormat', Configure::read('Config.type.emailmarketing'));

    	$this->From				= $this->_getSystemDefaultConfigSetting('DefaultEmailFrom', Configure::read('Config.type.emailmarketing'));

    	$this->Username 		= $this->_getSystemDefaultConfigSetting('SMTPHostUsername', Configure::read('Config.type.emailmarketing'));
    	$this->Password 		= $this->_getSystemDefaultConfigSetting('SMTPHostPassword', Configure::read('Config.type.emailmarketing'));
    	$this->Host				= $this->_getSystemDefaultConfigSetting('SMTPHost', Configure::read('Config.type.emailmarketing'));
    	$this->Hostname 		= $this->_getSystemDefaultConfigSetting('SMTPHostName', Configure::read('Config.type.emailmarketing'));
    	$this->Port				= $this->_getSystemDefaultConfigSetting('SMTPHostPort', Configure::read('Config.type.emailmarketing'));

		$this->BounceToMailBox 	= $this->_getSystemDefaultConfigSetting('BounceToMailBox', Configure::read('Config.type.emailmarketing'));

		$this->DKIM_selector	= $this->_getSystemDefaultConfigSetting('DKIMSelector', Configure::read('Config.type.emailmarketing'));

    	// Use PHPMailer to send email
    	$this->Mail = new EmailEngine(true);
    }

/**
 * Send email
 * @return boolean
 */
	public function execute(){
		if(empty($this->Body) || empty($this->Subject) || empty($this->Username) || empty($this->Password)){
			return false;
		}

		$this->Mail->Sender				= $this->Sender;

		$this->Mail->CharSet  			= $this->CharSet;
		$this->Mail->WordWrap 			= $this->WordWrap;
		$this->Mail->ContentType 		= $this->ContentType;
		$this->Mail->Encoding 			= $this->Encoding;
		$this->Mail->Host 				= $this->Host;
		$this->Mail->Hostname 			= $this->Hostname;
		$this->Mail->MessageID 			= $this->MessageID;
		$this->Mail->Timeout 			= $this->Timeout;
		$this->Mail->Helo 				= $this->Helo;
		$this->Mail->action_function 	= $this->action_function;
		$this->Mail->TimeStamp			= $this->TimeStamp;
		$this->Mail->ReturnPath			= $this->BounceToMailBox;

		$this->Mail->DKIM_selector 		= $this->DKIM_selector;
		$this->Mail->DKIM_identity 		= $this->DKIM_identity;
		$this->Mail->DKIM_passphrase 	= $this->DKIM_passphrase;
		$this->Mail->DKIM_domain 		= $this->DKIM_domain;
		$this->Mail->DKIM_private 		= $this->DKIM_private;
		$this->Mail->DKIM_private_string = $this->DKIM_private_string;

		switch ($this->Mailer){
			case 'smtp':
				$this->Mail->SMTPKeepAlive		= $this->SMTPKeepAlive;
				$this->Mail->Port				= $this->Port;
				$this->Mail->SMTPDebug  		= $this->SMTPDebug;
				$this->Mail->Debugoutput		= $this->Debugoutput;
				$this->Mail->SMTPSecure			= $this->SMTPSecure;
				$this->Mail->SMTPAuth			= $this->SMTPAuth;
				$this->Mail->Username			= $this->Username;
				$this->Mail->Password			= $this->Password;
				$this->Mail->AuthType			= $this->AuthType;
				$this->Mail->isSMTP();
				break;
			case 'mail':
				$this->Mail->isMail();
				break;
			case 'sendmail':
				$this->Mail->isSendmail();
				break;
			default:
				$this->Mail->isSMTP();
				break;
		}

		if (!empty($this->From) && method_exists($this->Mail,'setFrom')) {
			$this->Mail->setFrom($this->From, $this->FromName, false);
		} else {
			$this->Mail->From     	= $this->From;
			$this->Mail->FromName 	= $this->FromName;
		}

		if(!empty($this->Attachments)){
			foreach($this->Attachments as $attachedFilePath){
				$this->Mail->addAttachment($attachedFilePath);
			}
		}

		$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
		$companyDomain = $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));

		$this->Mail->addAddress($this->To, $this->ToName );
		$this->Mail->addReplyTo($this->Mail->From, $this->FromName );
		$this->Mail->addCustomHeader("X-Mailer: " .$companyName);
		$this->Mail->addCustomHeader("X-MessageID: {$this->Mail->MessageID}");
		$this->Mail->addCustomHeader("X-ListMember: {$this->To}");
		$this->Mail->addCustomHeader("Precedence: bulk");
		$this->Mail->addCustomHeader("List-Unsubscribe: <http://{$companyDomain}/email_marketing/email_marketing_subscribers/unsubscribe/{$this->Mail->MessageID}>");
// 		$this->Mail->addCustomHeader("List-Subscribe: <http://{$companyDomain}/email_marketing/subscribe)>");
		$this->Mail->addCustomHeader("List-Owner: <mailto:{$this->Mail->From}>");

		$this->Mail->addCustomHeader('Error-To: '.$this->BounceToMailBox);
		$this->Mail->addCustomHeader('Bounces-To: '.$this->BounceToMailBox);

		# Add a line like Received: from [10.1.2.3] by website.example.com with HTTP; 01 Jan 2003 12:34:56 -0000
		# more info: http://www.spamcop.net/fom-serve/cache/369.html
		if(empty($this->Mail->TimeStamp)){
			$clientIp = env('REMOTE_ADDR');
			$clientIp = empty($clientIp) ? '127.0.0.1' : $clientIp;
			$clientDomain = env('REMOTE_HOST');
			if ( empty($clientDomain) ) {
				$clientDomain = gethostbyaddr($clientIp);
			}

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.debug');
			$logMessage  = __('Sending email (' .$this->To .') - client info: ' .$clientDomain .' - ' .$clientIp .' - ' .$this->Mail->MessageID);
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $this->superUserId);

			if(!empty($clientIp) && !empty($clientDomain)){
				$emailServerHostName = env("HTTP_HOST");
				$requestTime = date('r',env('REQUEST_TIME'));
				$sTimeStamp = "from $clientDomain [$clientIp] by $emailServerHostName with HTTP; $requestTime";
				$this->Mail->TimeStamp = $sTimeStamp;
			}
		}

		$logType 	 = Configure::read('Config.type.emailmarketing');
		$logLevel 	 = Configure::read('System.log.level.debug');
		$logMessage  = __('Sending email (' .$this->To .') - timestamp: ' .print_r($this->Mail->TimeStamp, true));
		$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $this->superUserId);

		$isHTML = $this->SendFormat == Configure::read('EmailMarketing.email.type.html') || $this->SendFormat == Configure::read('EmailMarketing.email.type.both');
		$this->Mail->IsHTML($isHTML);

		$this->Mail->Subject = $this->Subject;
		if($isHTML){
			$this->Mail->msgHTML($this->Body);

			// User can specify the alt text, otherwise the alt text will be generated based on the HTML
			if(!empty($this->AltBody)){
				$this->Mail->AltBody = $this->AltBody;
			}
		}else{
			$this->Mail->Body    = $this->Body;
			$this->Mail->AltBody = $this->AltBody;
		}

		try{
			$result = $this->Mail->Send();
		}catch(Exception $e){

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.critical');
			$logMessage  = __("User (#' .$this->superUserId .') email (' .$this->To .') sending process failed.") .'<br />'.
						   __("Error Message: ") . $e->getMessage() .'<br />'.
						   __("Line Number: ") .$e->getLine() .'<br />'.
						   __("Trace: ") .$e->getTraceAsString();
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

		}

		$logType 	 = Configure::read('Config.type.emailmarketing');
		$logLevel 	 = Configure::read('System.log.level.debug');
		$logMessage  = __('Sending email (' .$this->To .') - result: ' .($result ? "SENT" : "FAILED"));
		$this->Log->addLogRecord($logType, $logLevel, $logMessage, false, $this->superUserId);

		if($result == false ) { $result = $this->Mail->ErrorInfo; }

		return $result;
	}
}

/**
 * Extend PHPMailer Class and override its functions
 */
class EmailEngine extends PHPMailer {

// 	public $do_verp = true; // Always enable VERP in order to receive bounced email

	public $TimeStamp 	= "";
	public $ImageTypes  = array(
		'gif'  => 'image/gif',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpe'  => 'image/jpeg',
		'bmp'  => 'image/bmp',
		'png'  => 'image/png',
		'tif'  => 'image/tiff',
		'tiff' => 'image/tiff',
		'swf'  => 'application/x-shockwave-flash'
	);

/**
 * Constructor
 * @param bool $exceptions Should we throw external exceptions?
 */
	public function __construct($exceptions = false){
		parent::__construct($exceptions);
	}

/**
 * @see PHPMailer::__destruct()
 */
	public function __destruct(){
		parent::__destruct();
	}

/**
 * @see PHPMailer::createHeader()
 */
	public function createHeader() {
		$parentHeader = parent::createHeader();
		$header = $parentHeader;
		if (!empty($this->TimeStamp)) {
			$header = 'Received: ' .$this->TimeStamp .static::$LE .$parentHeader;
		}
		return $header;
	}

	public function findHTMLImages($templateId) {
		#if (!$templateId) return;
		## no template can be templateid 0, find the powered by image
		$templateId = sprintf('%d',$templateId);

		// Build the list of image extensions
		while(list($key,) = each($this->imageTypes)) {
			$extensions[] = $key;
		}
		$htmlImages = array();
		$fileSystemImages = array();

		preg_match_all('/"([^"]+\.('.implode('|', $extensions).'))"/Ui', $this->Body, $images);

		for($i=0; $i<count($images[1]); $i++) {
			if($this->imageExists($templateId,$images[1][$i])){
				$htmlImages[] = $images[1][$i];
				$this->Body = str_replace($images[1][$i], basename($images[1][$i]), $this->Body);
			}

			//TODO no file system file enabled
			## addition for filesystem images
			//     		if (EMBEDUPLOADIMAGES) {
			// 	    		if($this->fileSystemImageExists($images[1][$i])){
			// 	    			$fileSystemImages[] = $images[1][$i];
			// 	    			$this->Body = str_replace($images[1][$i], basename($images[1][$i]), $this->Body);
			// 	    		}
			//     		}
			## end addition
		}
		if(!empty($htmlImages)){
			// If duplicate images are embedded, they may show up as attachments, so remove them.
			$htmlImages = array_unique($htmlImages);
			sort($htmlImages);
			for($i=0; $i<count($htmlImages); $i++){
				if($image = $this->getTemplateImage($templateId,$htmlImages[$i])){
					$contentType = $this->ImageTypes[strtolower(substr($htmlImages[$i], strrpos($htmlImages[$i], '.') + 1))];
					$cid = $this->addHTMLImage($image, basename($htmlImages[$i]), $contentType);
					if (!empty($cid)) {
						$this->Body = str_replace(basename($htmlImages[$i]), "cid:$cid", $this->Body);
					}
				}
			}
		}

		//TODO no file system file enabled
		## addition for filesystem images
		//     	if(!empty($fileSystemImages)){
		//     		// If duplicate images are embedded, they may show up as attachments, so remove them.
		//     		$fileSystemImages = array_unique($fileSystemImages);
		//     		sort($fileSystemImages);
		//     		for($i=0; $i<count($fileSystemImages); $i++){
		//     			if($image = $this->getFileSystemImage($fileSystemImages[$i])){
		//     				$contentType = $this->imageTypes[strtolower(substr($fileSystemImages[$i], strrpos($fileSystemImages[$i], '.') + 1))];
		//     				$cid = $this->addHTMLImage($image, basename($fileSystemImages[$i]), $contentType);
		//     				if (!empty($cid)) {
		//     					$this->Body = str_replace(basename($fileSystemImages[$i]), "cid:$cid", $this->Body);#@@@
		//     				}
		//     			}
		//     		}
		//     	}
		## end addition
	}

	public function addHTMLImage($contents, $name = '', $contentType='application/octet-stream') {
		## We cannot use AddStringAttachment, because that doesn't use a cid
		## we can't write to "attachment" either, because it's private

		/* one way to do it, is using a temporary file, but that'll have
		 * quite an effect on performance and also isn't backward compatible,
		 * because EncodeFile would need to be reverted to the default

		 file_put_contents('/tmp/'.$name,base64_decode($contents));
		 $cid = md5(uniqid(time()));
		 $this->AddEmbeddedImage('/tmp/'.$name, $cid, $name,'base64', $contentType);
		 */

		/* So, for now the only way to get this working or up is to make
		 * the attachment array public or add the AddEmbeddedImageString method
		 * we need to add instructions how to patch phpMailer for that.
		 * find out here whether it's been done and give an error if not
		 *
		 * it's been added to phpMailer 5.2.2
		 * http://code.google.com/a/apache-extras.org/p/phpmailer/issues/detail?id=119
		 *
		 *
		 */

		/* @@TODO additional optimisation:
		 *
		 * - we store the image base64 encoded
		 * - then we decode it to pass it back to phpMailer
		 * - when then encodes it again
		 * - best would be to take out a step in there, but that would require more modifications
		 * to phpMailer
		 */

		$cid = md5(uniqid(time()));
		if (method_exists($this,'addEmbeddedImage')) {
			$this->addEmbeddedImage(base64_decode($contents), $cid, $name, $this->encoding, $contentType);
		} elseif (method_exists($this,'addStringEmbeddedImage')) {
			$this->addStringEmbeddedImage(base64_decode($contents), $cid, $name, $this->encoding, $contentType);
		} elseif (isset($this->attachment) && is_array($this->attachment)) {
			// Append to $attachment array
			$cur = count($this->attachment);
			$this->attachment[$cur][0] = base64_decode($contents);
			$this->attachment[$cur][1] = '';#$filename;
			$this->attachment[$cur][2] = $name;
			$this->attachment[$cur][3] = 'base64';
			$this->attachment[$cur][4] = $contentType;
			$this->attachment[$cur][5] = true; // isStringAttachment
			$this->attachment[$cur][6] = "inline";
			$this->attachment[$cur][7] = $cid;
		} else {
			//TODO no log enabled for now
			//     		logEvent("phpMailer needs patching to be able to use inline images from templates");
			//     		print Error("phpMailer needs patching to be able to use inline images from templates");
			return;
		}
		return $cid;
	}

	// Addition for filesystem images
	// TODO build this when we get the template done (at least tinymce insert image done)
	public function fileSystemImageExists($filename) {
	//     	##  find the image referenced and see if it's on the server
	//     	$imageroot = getConfig('uploadimageroot');
	//     	#   cl_output('fileSystemImageExists '.$docroot.' '.$filename);

	//     	$elements = parse_url($filename);
	//     	$localfile = basename($elements['path']);

	// 		$localfile = urldecode($localfile);
	// 		#   cl_output('CHECK'.$localfile);

	// 		if (defined('UPLOADIMAGES_DIR')) {
	// 			#  print $_SERVER['DOCUMENT_ROOT'].$localfile;
	//     		return
	//     			is_file($_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/image/'.$localfile)
	//     			|| is_file($_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/'.$localfile)
	//     			## commandline
	//     			|| is_file($imageroot.'/'.$localfile);
	//     	} else {
	//     		return
	//     			is_file($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/image/'.$localfile)
	//     			|| is_file($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/'.$localfile)
	//     			## commandline
	//     			|| is_file('../'.FCKIMAGES_DIR.'/image/'.$localfile)
	//     			|| is_file('../'.FCKIMAGES_DIR.'/'.$localfile);
	//     	}
	}

	// TODO build this when we get the template done (at least tinymce insert image done)
	function getFileSystemImage($filename) {
	//     	## get the image contents
	//     	$localfile = basename(urldecode($filename));
	//     	#	cl_output('get file system image'.$filename.' '.$localfile);
	//     	if (defined('UPLOADIMAGES_DIR')) {
	//     		#       print 'UPLOAD';
	//     		$imageroot = getConfig('uploadimageroot');
	//     		if (is_file($imageroot.$localfile)) {
	//     			return base64_encode( file_get_contents($imageroot.$localfile));
	//     		} else {
	//     			if (is_file($_SERVER['DOCUMENT_ROOT'].$localfile)) {
	//     				## save the document root to be able to retrieve the file later from commandline
	//     				SaveConfig("uploadimageroot",$_SERVER['DOCUMENT_ROOT'],0,1);
	//     				return base64_encode( file_get_contents($_SERVER['DOCUMENT_ROOT'].$localfile));
	//     			} elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/image/'.$localfile)) {
	//     				SaveConfig("uploadimageroot",$_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/image/',0,1);
	//     				return base64_encode( file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/image/'.$localfile));
	//     			} elseif (is_file($_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/'.$localfile)) {
	//     				SaveConfig("uploadimageroot",$_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/',0,1);
	//     				return base64_encode( file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.UPLOADIMAGES_DIR.'/'.$localfile));
	//     			}
	//     		}
	//     	} elseif (is_file($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/'.$localfile)) {
	//     		$elements = parse_url($filename);
	//     		$localfile = basename($elements['path']);
	//     		return base64_encode( file_get_contents($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/'.$localfile));
	//     	} elseif (is_file($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/image/'.$localfile)) {
	//         	return base64_encode( file_get_contents($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/image/'.$localfile));
	//     	} elseif (is_file('../'.FCKIMAGES_DIR.'/'.$localfile)) {   ## commandline
	//     		return base64_encode( file_get_contents('../'.FCKIMAGES_DIR.'/'.$localfile));
	//     	} elseif (is_file('../'.FCKIMAGES_DIR.'/image/'.$localfile)) {
	//     		return base64_encode( file_get_contents('../'.FCKIMAGES_DIR.'/image/'.$localfile));
	//     	}
	//     	return '';
	}

	function imageExists($templateId,$filename) {
		//     	if (basename($filename) == 'powerphplist.png'){ $templateId = 0; }
		//     	$query = ' select *'
		//     					. ' from ' . $GLOBALS['tables']['templateimage']
		//     									. ' where template = ?'
		//     									. '   and (filename = ? or filename = ?)';
		//     	$rs = Sql_Query_Params($query, array($templateId, $filename, basename($filename)));
		//     	return Sql_Num_Rows($rs);
	}

	function getTemplateImage($templateId,$filename){
		//         if (basename($filename) == 'powerphplist.png'){ $templateId = 0; }
		//     									$query = ' select data'
		//     									. ' from ' . $GLOBALS['tables']['templateimage']
		//     									. ' where template = ?'
		//     									. '   and (filename = ? or filename= ?)';
		//     									$rs = Sql_Query_Params($query, array($templateId, $filename, basename($filename)));
		//       	$req = Sql_Fetch_Row($rs);
		//     	return $req[0];
	}

	function EncodeFile ($path, $encoding = "base64") {
			# as we already encoded the contents in $path, return $path
    	return chunk_split($path, 76, static::$LE);
    }
}
?>
