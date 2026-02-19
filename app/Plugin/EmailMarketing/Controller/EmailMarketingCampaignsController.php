<?php
use Jeremeamia\SuperClosure\SerializableClosure;

App::uses ( 'EmailMarketingAppController', 'EmailMarketing.Controller' );
App::uses('DateTimeHandler', 'Util');

/**
 * Campaigns Controller
 */
class EmailMarketingCampaignsController extends EmailMarketingAppController {

	public $paginate = array (
		'conditions'  => array(
			'EmailMarketingCampaign.deleted' => 0
		),
		'limit' 	  => 12,
		'order' 	  => array (
			"EmailMarketingCampaign.id" => "DESC"
		)
	);

	public function beforeFilter() {

		$this->Security->unlockedActions = array(
			'admin_checkSendingProcess'
		);

		parent::beforeFilter ();
	}

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {

		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			$this->paginate['joins'] = array(
				array(
					'table' => 'email_marketing_users',
					'alias' => 'EmailMarketingMiddleUser',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingMiddleUser.id = EmailMarketingCampaign.email_marketing_user_id',
						'EmailMarketingMiddleUser.user_id' => $userServiceAccountId
					)
				),
			);
		}

		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse());
		$this->set('_serialize','response');
		if(!empty($this->paginate['order']['EmailMarketingCampaign.id'])){
			$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingCampaign.id']);
		}

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (! $this->EmailMarketingCampaign->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		// Client cannot view other person's campaign
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "view");
			}
		}

		$contain = array (
			'EmailMarketingMailingList' => array (
				'EmailMarketingSubscriber'
			)
		);
		$campaign = $this->EmailMarketingCampaign->browseBy ( $this->EmailMarketingCampaign->primaryKey, $id, $contain );
		if ($campaign ['EmailMarketingCampaign'] ['use_external_web_page'] == 1) {
			$content = html_entity_decode ( $campaign ['EmailMarketingCampaign'] ['cached_web_page'], ENT_QUOTES, 'UTF-8' );
			$this->set ( 'externalContent', $content );
		}

		$this->set ( compact ( 'campaign', 'userGroupName' ) );

	}

/**
 * preview method
 *
 * @throws NotFoundException
 * @param string $type (TXT | HTML)
 * @param string $id
 * @return void
 */
	public function admin_preview($type, $id = null){
		if (empty($type) || !$this->EmailMarketingCampaign->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "preview");
			}
		}

		$this->layout = "ajax";

		$contain = array (
			'EmailMarketingTemplate',
			'EmailMarketingPurchasedTemplate'
		);

		$campaign = $this->EmailMarketingCampaign->browseBy ( $this->EmailMarketingCampaign->primaryKey, $id, $contain );
		if ($campaign ['EmailMarketingCampaign'] ['use_external_web_page'] == 1) {
			$content = html_entity_decode ( $campaign ['EmailMarketingCampaign'] ['cached_web_page'], ENT_QUOTES, 'UTF-8' );
			$this->set ( 'externalContent', $content );
		}elseif(!empty($campaign['EmailMarketingCampaign']['email_marketing_template_id']) || !empty($campaign['EmailMarketingCampaign']['email_marketing_purchased_template_id'])){
			$emailContentHeader = $this->__getEmailHtmlHeader();
			$this->set ( 'emailContentHeader', $emailContentHeader );
		}

		$this->set ( compact ( 'campaign', 'type' ) );

	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is ( 'post' ) && isset ( $this->request->data ["EmailMarketingCampaign"] ) && ! empty ( $this->request->data ["EmailMarketingCampaign"] )) {

			$createCampaign = function ($externalWebPageContent = null) {
				$this->__campaignAction ( 'add', $this->request->data, $externalWebPageContent );
			};

			if (isset ( $this->request->data ['EmailMarketingCampaign'] ['use_external_web_page'] ) && $this->request->data ['EmailMarketingCampaign'] ['use_external_web_page'] == 1) {
				$this->__cacheExternalWebPage ( $this->request->data ['EmailMarketingCampaign'] ['html_url'], $createCampaign );
			} else {
				$createCampaign ();
			}
		}

		$this->__prepareViewData ();

		$this->render('admin_edit');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (! $this->EmailMarketingCampaign->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		// Client cannot edit other person's campaign
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "edit");
			}
		}

		if (($this->request->is ( 'post' ) || $this->request->is ( 'put' )) && isset ( $this->request->data ["EmailMarketingCampaign"] ) && ! empty ( $this->request->data ["EmailMarketingCampaign"] )) {

			if(empty($this->request->data['EmailMarketingCampaign']['scheduled_time'])){

				$updateCampaign = function ($campaignId, $externalWebPageContent = null) {
					$this->__campaignAction ( 'edit', $this->request->data, $externalWebPageContent, $campaignId );
				};

				if (isset ( $this->request->data ['EmailMarketingCampaign'] ['use_external_web_page'] ) && $this->request->data ['EmailMarketingCampaign'] ['use_external_web_page'] == 1) {
					$this->__cacheExternalWebPage ( $this->request->data ['EmailMarketingCampaign'] ['html_url'], $updateCampaign, $id );
				} else {
					$updateCampaign ( $id );
				}

			}else{

				$this->_showWarningFlashMessage($this->EmailMarketingCampaign, __("Cannot edit scheduled campaign. Please remove the scheduler and try again."));
			}

		} else {
			$this->request->data = $this->EmailMarketingCampaign->browseBy ( $this->EmailMarketingCampaign->primaryKey, $id, $contain = array (
				'EmailMarketingMailingList'
			) );
		}
		$this->__prepareViewData ();
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
			$this->EmailMarketingCampaign->id = $id;
			if (!$this->EmailMarketingCampaign->exists()) {

				throw new NotFoundRecordException($this->modelClass);
			}

			// Client cannot delete other person's campaign
			$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

			if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
				if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

					throw new ForbiddenActionException($this->modelClass, "delete");
				}
			}

			$campaign = $this->EmailMarketingCampaign->browseBy('id', $id);

			if(empty($campaign['EmailMarketingCampaign']['scheduled_time'])){
				if($this->EmailMarketingCampaign->deleteCampaign($id)){
					$this->_showSuccessFlashMessage($this->EmailMarketingCampaign);
				}else{
					$this->_showErrorFlashMessage($this->EmailMarketingCampaign);
				}
			}else{
				$this->_showWarningFlashMessage($this->EmailMarketingCampaign, __("Cannot delete scheduled campaign. Please remove the scheduler and try again."));
			}
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @return void
 */
	public function admin_batchDelete() {
		if (($this->request->is('post') || $this->request->is('delete')) && $this->request->is('ajax')){
			if(isset($this->request->data['batchIds']) && !empty($this->request->data['batchIds']) && is_array($this->request->data['batchIds'])){

				$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

				$batchDeleteDone = true;
				foreach($this->request->data['batchIds'] as $id){
					$this->EmailMarketingCampaign->id = $id;
					if (!$this->EmailMarketingCampaign->exists()) {

						throw new NotFoundRecordException($this->modelClass);
						$batchDeleteDone = false;
						break;
					}

					// Client cannot delete other person's campaign
					if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
						if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

							throw new ForbiddenActionException($this->modelClass, "batch delete");
							$batchDeleteDone = false;
							break;
						}
					}

					$campaign = $this->EmailMarketingCampaign->browseBy('id', $id);

					if(empty($campaign['EmailMarketingCampaign']['scheduled_time'])){

						if (!$this->EmailMarketingCampaign->deleteCampaign($id)) {

							$logType 	 = Configure::read('Config.type.system');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') cannot delete email marketing campaign. (Passed campaign ID: ' .$id .')');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							$batchDeleteDone = false;
						}

					}else{
						// Cannot delete scheduled campaign
						$batchDeleteDone = 1;
						break;
					}
				}
				if($batchDeleteDone === true){
					$this->_showSuccessFlashMessage($this->EmailMarketingCampaign, __("Selected email marketing campaigns have been batch deleted."));
				}elseif($batchDeleteDone){
					$this->_showWarningFlashMessage($this->EmailMarketingCampaign, __("Cannot delete scheduled campaign. Please remove the scheduler and try again."));
				}else{
					$this->_showErrorFlashMessage($this->EmailMarketingCampaign, __("Selected email marketing campaigns cannot be batch deleted."));
				}
			}
		}
	}

	public function admin_sendTestEmail($id = null){

		if (! $this->EmailMarketingCampaign->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		// Client cannot send other person's campaign
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "send test");
			}
		}

		$contain = array (
			'EmailMarketingMailingList' => array (
				'conditions' => array (
					'active' 	=> 1,
					'deleted' 	=> 0
				),
				'EmailMarketingSubscriber' => array (
					'conditions' => array (
						'excluded' 		=> 0,
						'unsubscribed' 	=> 0,
						'deleted' 		=> 0
					)
				)
			)
		);
		$campaign = $this->EmailMarketingCampaign->browseBy ( $this->EmailMarketingCampaign->primaryKey, $id, $contain);

		// Send email
		if ($this->request->is ( 'post' ) && $this->request->is ( 'ajax' )) {
			$testEmail1 = $this->request->data['EmailMarketingCampaign']['test_email_1'];
			$testEmail2 = $this->request->data['EmailMarketingCampaign']['test_email_2'];
			$testEmail3 = $this->request->data['EmailMarketingCampaign']['test_email_3'];
			$testEmail4 = $this->request->data['EmailMarketingCampaign']['test_email_4'];
			$testEmail5 = $this->request->data['EmailMarketingCampaign']['test_email_5'];
			if(empty($testEmail1) && empty($testEmail2) && empty($testEmail3) && empty($testEmail4) && empty($testEmail5)){
				echo json_encode ( array (
					'status' => Configure::read('System.variable.error'),
					'message' => __ ( 'Please enter at least one email address.' )
				) );
			}else{

				$duplicated 			= 0;
				$blacklisted 			= 0;
				$invalid 				= 0;
				$total					= 0;
				$emails = [$testEmail1, $testEmail2, $testEmail3, $testEmail4, $testEmail5];

				$this->loadModel ( 'EmailMarketing.EmailMarketingBlacklistedSubscriber' );
				$blacklistedEmails 		= $this->EmailMarketingBlacklistedSubscriber->browseBy('email_marketing_user_id', $campaign ["EmailMarketingCampaign"] ["email_marketing_user_id"]);
				$blacklistedEmails 		= Set::classicExtract($blacklistedEmails, '{n}.EmailMarketingBlacklistedSubscriber.email');
				$subscribers 			= array ();

				$sampleSubscriber 		= array();
				if(!empty($campaign ["EmailMarketingMailingList"])){
					foreach($campaign ["EmailMarketingMailingList"] as $list){
						if (isset ( $list ["EmailMarketingSubscriber"] ) && ! empty ( $list ["EmailMarketingSubscriber"] ) && is_array ( $list ["EmailMarketingSubscriber"] )) {
							$r = rand(0, count($list ["EmailMarketingSubscriber"]));
							$sampleSubscriber = $list ["EmailMarketingSubscriber"][$r];
							break;
						}
					}
				}

				$processedEmails = array();
				foreach ( $emails as $s ) {
					if(empty($s)){
						continue;
					}
					if (! filter_var ( $s, FILTER_VALIDATE_EMAIL )) {
						$invalid++;
						continue;
					}
					if (in_array($s, $blacklistedEmails)) {
						$blacklisted++;
						continue;
					}
					if(in_array($s, $processedEmails)){
						$duplicated++;
						continue;
					}else{
						$processedEmails[] = $s;
					}
					$subscriber 			= $sampleSubscriber;
					$subscriber['email'] 	= $s;
					if(empty($subscriber['id'])){
						$subscriber['id'] = $subscriber['email']; // Use email address as subscriber ID for testing
					}
					$subscribers[] 			= $subscriber;
				}
				$total = count($processedEmails);

				if(count($subscribers) > 0){

					// Save statistic data before sending. This statistic record will be deleted after sending
					$this->loadModel ( 'EmailMarketing.EmailMarketingStatistic' );
					$statistic = array (
						'EmailMarketingStatistic' => array (
							'email_marketing_campaign_id' 	=> $campaign ["EmailMarketingCampaign"] ["id"],
							'xor_mask' 						=> md5 ( uniqid ( rand (), true ) ),
							'duplicated' 					=> $duplicated,
							'invalid' 						=> count ( $invalid ),
							'blacklisted' 					=> count ( $blacklisted ),
							'processed'						=> 0,
							'send_start' 					=> date ( 'Y-m-d H:i:s', time () ),
							'status'						=> 'PENDING'
						)
					);
					if ($this->EmailMarketingStatistic->saveStatistic ( $statistic )) {

						// Load just saved statistic
						$statistic = $this->EmailMarketingStatistic->browseBy ( $this->EmailMarketingStatistic->primaryKey, $this->EmailMarketingStatistic->getInsertID (), false);

						// Apply email sending jobs
						$jobApplyResult = $this->__doSendEmail ( $campaign, $subscribers, $statistic, true );

						if($jobApplyResult){
							echo json_encode ( array (
								'status' 		=> Configure::read('System.variable.success'),
								'message' 		=> 'TRIGGER_CHECKING',
								'duplicated' 	=> $duplicated,
								'blacklisted' 	=> $blacklisted,
								'invalid' 		=> $invalid,
								'total'			=> $total
							) );
						}else{

							$logType 	 = Configure::read('Config.type.emailmarketing');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('Campaign (' .$campaign ["EmailMarketingCampaign"] ["name"] .' | #' .$campaign ["EmailMarketingCampaign"] ["id"] .') test emails sending process stopped extraordinarily.');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							echo json_encode ( array (
								'status' => Configure::read('System.variable.error'),
								'message' => __ ( 'Campaign test emails sending process stopped extraordinarily, this has been reported to ' .$this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system')) .'. We will look into it ASAP and sorry about the inconvenience.' )
							) );
						}

					}else{

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('Cannot set up campaign (' .$campaign ["EmailMarketingCampaign"] ["name"] .' | #' .$campaign ["EmailMarketingCampaign"] ["id"] .') test emails sending process monitor.');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						echo json_encode ( array (
							'status' => Configure::read('System.variable.error'),
							'message' => __ ( 'Cannot set up campaign test emails sending process monitor. This has been reported to ' .$this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system')) .'. We will look into it ASAP and sorry about the inconvenience.' )
						) );

					}
				}else{

					echo json_encode ( array (
						'status' 		=> Configure::read('System.variable.warning'),
						'message' 		=> __ ( 'No valid email address found' ),
						'duplicated' 	=> $duplicated,
						'blacklisted' 	=> $blacklisted,
						'invalid' 		=> $invalid,
						'total'			=> $total
					) );
				}
			}

			exit();
		}

		// Render send email page
		$this->set ( compact ( 'campaign' ) );
	}

/**
 * send email method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_sendEmail($id = null, $setUpScheduler = FALSE) {

		if (! $this->EmailMarketingCampaign->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		// Client cannot send other person's campaign
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "send");
			}
		}

		$this->loadModel ( 'EmailMarketing.EmailMarketingBlacklistedSubscriber' );

		$contain = array (
			'EmailMarketingMailingList' => array (
				'conditions' => array (
					'active' 	=> 1,
					'deleted' 	=> 0
				),
				'EmailMarketingSubscriber' => array (
					'conditions' => array (
						'excluded' 		=> 0,
						'unsubscribed' 	=> 0,
						'deleted' 		=> 0
					)
				)
			),
			'EmailMarketingUser' => array (
				'EmailMarketingPlan',
				'User'
			)
		);
		$campaign 				= $this->EmailMarketingCampaign->browseBy ( $this->EmailMarketingCampaign->primaryKey, $id, $contain );

		if(!empty($campaign['EmailMarketingCampaign']['scheduled_time']) && empty($setUpScheduler)){
			$this->_showWarningFlashMessage($this->EmailMarketingCampaign, __("Cannot send scheduled campaign emails manually. Please remove the scheduler and try again."));
			return;
		}

		$blacklistedEmails 		= $this->EmailMarketingBlacklistedSubscriber->browseBy('email_marketing_user_id', $campaign ["EmailMarketingCampaign"] ["email_marketing_user_id"]);
		$blacklistedEmails 		= Set::classicExtract($blacklistedEmails, '{n}.EmailMarketingBlacklistedSubscriber.email');
		$subscribers 			= array ();
		$subscribersRawCount 	= 0;
		$duplicated 			= 0;
		$blacklisted 			= array ();
		$invalid 				= array ();
		if (isset ( $campaign ["EmailMarketingMailingList"] ) && ! empty ( $campaign ["EmailMarketingMailingList"] ) && is_array ( $campaign ["EmailMarketingMailingList"] )) {

			//TODO do not pre-check all the emails, check the email address when sending it
			foreach ( $campaign ["EmailMarketingMailingList"] as $list ) {
				if (isset ( $list ["EmailMarketingSubscriber"] ) && ! empty ( $list ["EmailMarketingSubscriber"] ) && is_array ( $list ["EmailMarketingSubscriber"] )) {
					$newSubscribersRawCount = count ( $list ["EmailMarketingSubscriber"] );
					$newSubscribers = array_unique ( $list ["EmailMarketingSubscriber"], SORT_REGULAR );
					$duplicated += $newSubscribersRawCount - count ( $newSubscribers );
					foreach ( $newSubscribers as &$s ) {
						if (! filter_var ( $s ["email"], FILTER_VALIDATE_EMAIL )) {
							array_push ( $invalid, $s ["id"] );
							unset ( $s );
							continue;
						}
						if (in_array($s ["email"], $blacklistedEmails)) {
							array_push ( $blacklisted, $s ["id"] );
							unset ( $s );
							continue;
						}
						$subscribers[] = $s;
					}
				}
			}

		}

		// Send email
		if ($this->request->is ( 'post' ) && ($this->request->is ( 'ajax' ) || $this->request->data['EmailMarketingCampaign']['schedule-action'] === TRUE)) {
			$this->_prepareAjaxPostAction ();

			// Check email usage (if sending email amount is greater than the usage left, show an alert message)
			$this->loadModel ( 'EmailMarketing.EmailMarketingUser' );
			$isOutOfLimit = $this->EmailMarketingUser->exceededLimit($campaign['EmailMarketingUser'], count($subscribers));
			if($isOutOfLimit == false){

				// Save statistic data before sending
				$this->loadModel ( 'EmailMarketing.EmailMarketingStatistic' );
				$statistic = array (
					'EmailMarketingStatistic' => array (
						'email_marketing_campaign_id' 	=> $campaign ["EmailMarketingCampaign"] ["id"],
						'xor_mask' 						=> md5 ( uniqid ( rand (), true ) ),
						'duplicated' 					=> $duplicated,
						'invalid' 						=> count ( $invalid ),
						'blacklisted' 					=> count ( $blacklisted ),
						'processed'						=> 0,
						'send_start' 					=> date ( 'Y-m-d H:i:s', time () ),
						'status'						=> 'PENDING'
					)
				);
				if ($this->EmailMarketingStatistic->saveStatistic ( $statistic )) {

					// Load just saved statistic
					$statistic = $this->EmailMarketingStatistic->browseBy ( $this->EmailMarketingStatistic->primaryKey, $this->EmailMarketingStatistic->getInsertID (), false);

					// Apply email sending jobs
					if(empty($setUpScheduler)){
						$jobApplyResult = $this->__doSendEmail ( $campaign, $subscribers, $statistic );
					}else{
						$jobApplyResult = $this->__doSendEmail ( $campaign, $subscribers, $statistic, FALSE, $campaign['EmailMarketingCampaign']['scheduled_time'] );
					}

					if($jobApplyResult){
						if($this->request->is ( 'ajax' )){
							echo json_encode ( array (
								'status' 	=> Configure::read('System.variable.success'),
								'message' 	=> 'TRIGGER_CHECKING'
							) );
						}else{
							return true;
						}
					}else{

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('Campaign (' .$campaign ["EmailMarketingCampaign"] ["name"] .' | #' .$campaign ["EmailMarketingCampaign"] ["id"] .') emails sending process stopped extraordinarily.');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

						if($this->request->is ( 'ajax' )){
							echo json_encode ( array (
								'status' => Configure::read('System.variable.error'),
								'message' => __ ( 'Campaign emails sending process stopped extraordinarily, this has been reported to ' .$this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system')) .'. We will look into it ASAP and sorry about the inconvenience.' )
							) );
						}else{
							return false;
						}
					}

				} else {

					$logType 	 = Configure::read('Config.type.emailmarketing');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __('Campaign (' .$campaign ["EmailMarketingCampaign"] ["name"] .' | #' .$campaign ["EmailMarketingCampaign"] ["id"] .') emails statistics cannot be collected.');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					// Save email marketing statistic failed
					if($this->request->is ( 'ajax' )){
						echo json_encode ( array (
							'status' => Configure::read('System.variable.error'),
							'message' => __ ( 'Campaign emails statistics cannot be collected, this has been reported to ' .$this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system')) .'. We will get it fixed ASAP and sorry about the inconvenience.' )
						) );
					}else{
						return false;
					}
				}

			}else{

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.warning');
				$logMessage  = __('Insufficent email sending usage left while sending email marketing campaign (' .$campaign ["EmailMarketingCampaign"] ["name"] .') emails.');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				// Insufficent usage left
				if($this->request->is ( 'ajax' )){
					echo json_encode ( array (
						'status' => Configure::read('System.variable.warning'),
						'message' => __ ( 'Insufficent email sending usage left. Please reduce the email sending amount or add funds to your account.' )
					) );
				}else{
					return false;
				}
			}

			if($this->request->is ( 'ajax' )){
				exit ();
			}

		} else {

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.info');
			$logMessage  = __('Campaign (' .$campaign ["EmailMarketingCampaign"] ["name"] .') emails sending process is ready. Email send summary: valid subscribers: ' .count($subscribers) .', duplicated subscribers: ' .$duplicated .', blacklisted subscribers: ' .count($blacklisted) .', invalid subscribers: ' .count($invalid));
			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			// Render send email page
			$this->set ( compact ( 'campaign', 'subscribers', 'duplicated', 'blacklisted', 'invalid' ) );
		}
	}

/**
 * This AJAX checking is only used for immediately sending, not scheduled sending.
 * @param string $campaignId
 */
	public function admin_checkSendingProcess($campaignId, $totalSubscribersAmount, $isTest = FALSE) {

		if (! $this->EmailMarketingCampaign->exists ( $campaignId )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		// Client cannot check other person's campaign email sending process
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($campaignId, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "check ? sending process");
			}
		}

		if($isTest && $totalSubscribersAmount > 5){

			throw new IncorrectUseMethodException(__('Tried to send more emails using test method.'));
		}

		$this->_prepareAjaxPostAction ();
		$this->loadModel ( 'EmailMarketing.EmailMarketingStatistic' );
		$statistic = $this->EmailMarketingStatistic->browseBy ( "email_marketing_campaign_id", $campaignId, false, array (
			'EmailMarketingStatistic.id DESC'
		) );

		if(!empty($statistic)){

			// Sync processed count after emails start sending
			$this->loadModel ( 'JobQueue' );
			$jobType 		= $this->_getSystemDefaultConfigSetting('ParallelTaskType', Configure::read('Config.type.emailmarketing'));
			$excutionTime 	= "NOW";
			$processedCount = $this->JobQueue->countProcessedJobs($statistic ['EmailMarketingStatistic'] ['send_start'], $jobType, $this->superUserId, $excutionTime, "BEFORE");

			if($processedCount && $processedCount != $statistic["EmailMarketingStatistic"]["processed"]){
				$statistic["EmailMarketingStatistic"]["processed"] = $processedCount;
				$this->EmailMarketingStatistic->updateStatistic ( $statistic ['EmailMarketingStatistic'] ['id'], $statistic );
			}

			// Finish sending process
			if($statistic["EmailMarketingStatistic"]["processed"] >= $totalSubscribersAmount){
				$statistic ['EmailMarketingStatistic'] ['status'] = "SENT";
				$statistic ['EmailMarketingStatistic'] ['send_end'] = date('Y-m-d H:i:s');
				$this->EmailMarketingStatistic->updateStatistic ( $statistic ['EmailMarketingStatistic'] ['id'], $statistic );

				// Update email usage
				if($isTest){

					$statistic ['EmailMarketingStatistic'] ['send_start'] = CakeTime::i18nFormat($statistic ['EmailMarketingStatistic'] ['send_start'], '%x %X');
					$statistic ['EmailMarketingStatistic'] ['send_end'] = CakeTime::i18nFormat($statistic ['EmailMarketingStatistic'] ['send_end'], '%x %X');

					$logType 	 = Configure::read('Config.type.emailmarketing');
					$logLevel 	 = Configure::read('System.log.level.info');
					$logMessage  = __('Email marketing test campaign emails are sent. (sent started at: ' .$statistic ['EmailMarketingStatistic'] ['send_start'] .', sent ended at: ' .$statistic ['EmailMarketingStatistic'] ['send_end'] .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

					$this->EmailMarketingStatistic->deleteStatistic($statistic ['EmailMarketingStatistic'] ['id']);

				}else{

					$this->loadModel ( 'EmailMarketing.EmailMarketingUser' );

					if($this->EmailMarketingUser->recordUsage($userServiceAccountId, $totalSubscribersAmount) == false){

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('Sending email marketing campaign emails usage cannot be recorded. (User ID: ' .$userServiceAccountId .', total subscriber amount (this needs to append to existing usage amount): ' .$totalSubscribersAmount .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					}else{

						$statistic ['EmailMarketingStatistic'] ['send_start'] = CakeTime::i18nFormat($statistic ['EmailMarketingStatistic'] ['send_start'], '%x %X');
						$statistic ['EmailMarketingStatistic'] ['send_end'] = CakeTime::i18nFormat($statistic ['EmailMarketingStatistic'] ['send_end'], '%x %X');

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.info');
						$logMessage  = __('Email marketing campaign emails are sent. (sent started at: ' .$statistic ['EmailMarketingStatistic'] ['send_start'] .', sent ended at: ' .$statistic ['EmailMarketingStatistic'] ['send_end'] .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage);
					}

				}

			}else{

				// Format initial send start date
				if(!empty($statistic ['EmailMarketingStatistic'] ['send_start'])){
					$statistic ['EmailMarketingStatistic'] ['send_start'] = CakeTime::i18nFormat($statistic ['EmailMarketingStatistic'] ['send_start'], '%x %X');
				}
			}

			echo json_encode ( $statistic );

		}else{

			// Because all the jobs are processing in one queue, user may wait for a long long time.
			echo json_encode ( array('EmailMarketingStatistic' => 'PENDING') );
		}

		exit ();
	}

	public function admin_getExternalWebPageContent($externalWebPageUrl = null){

		$this->_prepareNoViewAction();

		if(empty($externalWebPageUrl) && $this->request->is('ajax')){

			$externalWebPageUrl = $this->request->data['externalWebPageUrl'];
		}

		if ($this->UrlUtil->validate ( $externalWebPageUrl ) == false) {

			$this->_showErrorFlashMessage ( $this->EmailMarketingCampaign, __ ( 'External URL is invalid. Please double check it in browser and re-enter it.' ) );

		} else {

			$content = $this->UrlUtil->fetchContent ( $externalWebPageUrl );
			if ($this->request->is('ajax')) {

				echo $content;
				exit();

			} else {

				return $content;
			}
		}

		return false;
	}

	public function admin_getTemplateContentById($templateId, $emailMarketinguserId, $templateType){

		$this->_prepareAjaxPostAction();

		if ($this->request->is('ajax')) {

			if(empty($templateId) || !is_numeric($templateId) || !in_array($templateType, array("OWN", "PURCHASED"))){

				$this->_showErrorFlashMessage ( $this->EmailMarketingCampaign, __ ( 'External URL is invalid. Please double check it in browser and re-enter it.' ) );
			}

			$content = '';
			switch($templateType){
				case 'OWN':
					$this->loadModel ( 'EmailMarketing.EmailMarketingTemplate' );
					$template = $this->EmailMarketingTemplate->getTemplate($templateId, $emailMarketinguserId);
					$content = unserialize($template['EmailMarketingTemplate']['html']);
					break;
				case 'PURCHASED':
					$this->loadModel ( 'EmailMarketing.EmailMarketingPurchasedTemplate' );
					$template = $this->EmailMarketingPurchasedTemplate->getPurchasedTemplateById($emailMarketinguserId, $templateId);
					$content = unserialize($template['EmailMarketingPurchasedTemplate']['customized_html']);
					break;
			}

			if(is_array($content) || (strtolower(substr($content, 0, 5)) !== "<html" && strtolower(substr($content, 0, 9)) !== "<!DOCTYPE")){
				$templateCss = empty($content['gjs-css']) ? '' : '<style type="text/css">' .$content['gjs-css'] .'</style>';
				$emailContentHeader = $this->__getEmailHtmlHeader();
				$content = $emailContentHeader .(empty($content['gjs-html']) ? $content : $content['gjs-html'] .$templateCss) ."</body></html>";
			}

			echo $content;

			exit();
		}

		return false;
	}

	public function admin_setScheduleTime($id){

		if (! $this->EmailMarketingCampaign->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		// Client cannot edit other person's campaign
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "set ? schedule time");
			}
		}

		$campaign = $this->EmailMarketingCampaign->browseBy ( $this->EmailMarketingCampaign->primaryKey, $id );

		if ($this->request->is ( 'post' ) || $this->request->is ( 'put' )) {

			$dateTimeHandler = new DateTimeHandler();

			if(!empty($this->request->data['EmailMarketingCampaign']['remove'])){

				$scheduledTime = $campaign['EmailMarketingCampaign']['scheduled_time'];
				$campaign['EmailMarketingCampaign']['scheduled_time'] = null;
				if($this->EmailMarketingCampaign->updateCampaign($id, $campaign)){

					$jobType 			  = $this->_getSystemDefaultConfigSetting('ParallelTaskType', Configure::read('Config.type.emailmarketing'));
					$emailMarketingUserId = $campaign['EmailMarketingCampaign']['email_marketing_user_id'];
					$userId 			  = $this->EmailMarketingCampaign->emailMarketingUserIdToSuperUserId($emailMarketingUserId);
					$this->loadModel('JobQueue');
					$result = $this->JobQueue->deleteAll(array(
						'JobQueue.type' 		 => $jobType,
						'JobQueue.user_id'  	 => $userId,
						'JobQueue.status' 		 => 'PENDING',
						'JobQueue.excution_time' => $scheduledTime,
					), false);

					if($result){
						$this->_showSuccessFlashMessage($this->EmailMarketingCampaign, __('Campaign email scheduler has been removed.'));
					}else{
						$this->_showErrorFlashMessage($this->EmailMarketingCampaign, __('Scheduled campaign email sending procedure cannot be removed. This has been reported. Before the issue is solved, please do not set up schedule time for this campaign. Sorry about the inconvenience.'));

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('User (#' .$this->superUserId .') cannot remove schedule time for email marketing campaign. (Passed email marketing campaign ID: ' .$id .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
					}

				}else{
					$this->_showErrorFlashMessage($this->EmailMarketingCampaign, __('Campaign email scheduler cannot be removed. This has been reported. Sorry about the inconvenience.'));

					$logType 	 = Configure::read('Config.type.emailmarketing');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __('User (#' .$this->superUserId .') cannot remove schedule time for email marketing campaign. (Passed email marketing campaign ID: ' .$id .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
				}

			}else{

				$campaign['EmailMarketingCampaign']['scheduled_time'] = $this->request->data['EmailMarketingCampaign']['schedule_date'] .' ' .$this->request->data['EmailMarketingCampaign']['schedule_time'] .':00';
				if(!$dateTimeHandler->validateDate($campaign['EmailMarketingCampaign']['scheduled_time'])){
					$this->_showErrorFlashMessage($this->EmailMarketingCampaign, __('Schedule Time is invalid'));
				}else{
					if($this->EmailMarketingCampaign->updateCampaign($id, $campaign)){

						$extra = array(
							'data' => array(
								'EmailMarketingCampaign' => array(
									'schedule-action' => TRUE
								)
							)
						);

						$scheduleResult = $this->requestAction('/admin/email_marketing/email_marketing_campaigns/sendEmail/' .$id .'/1', $extra);
						if($scheduleResult === TRUE){

							$schedulePeriod = strtotime($campaign['EmailMarketingCampaign']['scheduled_time']) - time();
							$this->set('schedulePeriod', $schedulePeriod);
							list($hours, $minutes, $seconds) = $dateTimeHandler->secToHumanReadable($schedulePeriod);
							$this->_showSuccessFlashMessage($this->EmailMarketingCampaign, __('Campaign email will be sent after ') .($hours > 0 ? ($hours > 1 ? "$hours " .__("hours") ." " : "$hours " .__("hour") ." ") : "") .($minutes > 0 ? ($minutes > 1 ? "$minutes " .__("minutes") ." " : "$minutes " .__("minute") ." ") : "") .($seconds > 0 ? ($seconds > 1 ? "$seconds " .__("seconds") ." " : "$seconds " .__("second") ." ") : "") );

						}else{

							$logType 	 = Configure::read('Config.type.emailmarketing');
							$logLevel 	 = Configure::read('System.log.level.critical');
							$logMessage  = __('User (#' .$this->superUserId .') cannot set scheduler jobs for email marketing campaign. (Passed email marketing campaign ID: ' .$id .')');
							$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

							$campaign['EmailMarketingCampaign']['scheduled_time'] = null;
							if($this->EmailMarketingCampaign->updateCampaign($id, $campaign)){
								$this->_showErrorFlashMessage($this->EmailMarketingCampaign, __('Campaign email scheduler cannot be set. All the actions have been rolled back, please check logs for more details. Sorry about the inconvenience.'));
							}else{
								$this->_showErrorFlashMessage($this->EmailMarketingCampaign, __('Campaign email scheduler cannot be removed after setting up scheduler failed. This has been reported. Sorry about the inconvenience.'));

								$logType 	 = Configure::read('Config.type.emailmarketing');
								$logLevel 	 = Configure::read('System.log.level.critical');
								$logMessage  = __('User (#' .$this->superUserId .') cannot remove schedule time for email marketing campaign after setting up scheduler failed. (Passed email marketing campaign ID: ' .$id .')');
								$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
							}
						}

					}else{
						$this->_showErrorFlashMessage($this->EmailMarketingCampaign, __('Campaign email scheduler cannot be set. This has been reported. Sorry about the inconvenience.'));

						$logType 	 = Configure::read('Config.type.emailmarketing');
						$logLevel 	 = Configure::read('System.log.level.critical');
						$logMessage  = __('User (#' .$this->superUserId .') cannot set schedule time for email marketing campaign. (Passed email marketing campaign ID: ' .$id .')');
						$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);
					}
				}

			}

		}

		$schedulePeriod = 0;
		if(!empty($campaign['EmailMarketingCampaign']['scheduled_time'])){
			$schedulePeriod = strtotime($campaign['EmailMarketingCampaign']['scheduled_time']) - time();
			if($schedulePeriod < 0){
				$schedulePeriod = 0;
			}
		}
		$this->set('schedulePeriod', $schedulePeriod);

		$this->set('campaign', $campaign);
	}

/**
 *
 * @param array $campaign
 * @param array $subscribers
 * @param string $statistic
 * @param boolean $isNewJob (if some jobs are not done at the first round, we use this flag to trigger some extra rounds to make sure all the jobs are done.)
 * @return boolean|string
 */
	private function __doSendEmail($campaign, $subscribers = array(), $statistic = null, $isTest = FALSE, $excutionTime = "NOW") {
		if (empty ( $campaign ) || empty ( $subscribers ) || empty ( $statistic )) {

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.critical');
			$logMessage  = __('Sending email marketing campaign emails error. (User ID: ' .$this->superUserId .', Campaign ID: ' .@$campaign ["EmailMarketingCampaign"] ["id"] .')');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			return false;
		}

		$txtEmailType = Configure::read('EmailMarketing.email.type.text');

		// Fetch HTML email content
		$emailBody = null;
		if ($campaign ["EmailMarketingCampaign"] ["send_format"] != $txtEmailType) {
			if ($campaign ['EmailMarketingCampaign'] ['use_external_web_page'] == 1) {
				// Use cached external web page content
				$emailBody = html_entity_decode ( $campaign ['EmailMarketingCampaign'] ['cached_web_page'], ENT_QUOTES, 'UTF-8' );
			} else {
				// Send template content
				if(!empty($campaign ["EmailMarketingCampaign"] ["email_marketing_template_id"])){
					$this->loadModel ( 'EmailMarketing.EmailMarketingTemplate' );
					$template = $this->EmailMarketingTemplate->getTemplate($campaign ["EmailMarketingCampaign"] ["email_marketing_template_id"], $campaign ["EmailMarketingCampaign"] ["email_marketing_user_id"]);
					$emailBody = unserialize($template['EmailMarketingTemplate']['html']);
				}elseif (!empty($campaign ["EmailMarketingCampaign"] ["email_marketing_purchased_template_id"])){
					$this->loadModel ( 'EmailMarketing.EmailMarketingPurchasedTemplate' );
					$template = $this->EmailMarketingPurchasedTemplate->getPurchasedTemplateById($campaign ["EmailMarketingCampaign"] ["email_marketing_user_id"], $campaign ["EmailMarketingCampaign"] ["email_marketing_purchased_template_id"]);
					$emailBody = unserialize($template['EmailMarketingPurchasedTemplate']['customized_html']);
				}else{
					$emailBody = $campaign ['EmailMarketingCampaign'] ['template_data'];
				}

				if(is_array($emailBody) || (strtolower(substr($emailBody, 0, 5)) !== "<html" && strtolower(substr($emailBody, 0, 9)) !== "<!DOCTYPE")){
					$templateCss = empty($emailBody['gjs-css']) ? '' : '<style type="text/css">' .$emailBody['gjs-css'] .'</style>';
					$emailContentHeader = $this->__getEmailHtmlHeader();
					$emailBody = $emailContentHeader .(empty($emailBody['gjs-html']) ? $emailBody : $emailBody['gjs-html'] .$templateCss) ."</body></html>";
				}
			}
		}

		// Add more features into email HTML content
		if (! empty ( $emailBody ) && $campaign ["EmailMarketingCampaign"] ["send_format"] != $txtEmailType) {

			/*
			 * Optimize email HTML content
			 */

			// TODO uncomment the following code when we have templates (All email component methods are in email task now!)
			// $this->Email->Mail->findHTMLImages($templateId);

			/*
			 * Add track to HTML email (make sure to only include usertrack once, otherwise the stats would go silly)
			 */

			// Add open track
			if(!$isTest){
				if (stripos ( $emailBody, '</body>' )) {
					$emailBody = str_replace ( '</body>', '<img src="' . Router::url ( '/', true ) . 'admin/email_marketing/email_marketing_statistics/trackOpen/[EMAIL-MESSAGE-ID]' . '" width="1" height="1" border="0" alt=" " /></body>', $emailBody );
				} else {
					$emailBody .= '<img src="' . Router::url ( '/', true ) . 'admin/email_marketing/email_marketing_statistics/trackOpen/[EMAIL-MESSAGE-ID]' . '" width="1" height="1" alt=" " border="0" />';
				}
			}

			// Add click track
			if(!$isTest){
				$this->loadModel ( 'EmailMarketing.EmailMarketingEmailLink' );
				preg_match_all ( '/<a (.*)href=["\'](.*)["\']([^>]*)>(.*)<\/a>/Umis', $emailBody, $links );
				$clickTrackRoot = sprintf ( '%sadmin/email_marketing/email_marketing_statistics/trackClick/', Router::url ( '/', true ) );
				for($i = 0; $i < count ( $links [2] ); $i ++) {
					$link = $this->UrlUtil->cleanUrl ( $links [2] [$i] );
					$link = str_replace ( '"', '', $link );
					if (preg_match ( '/\.$/', $link )) {
						$link = substr ( $link, 0, - 1 );
					}
					$linkText = $links [4] [$i];

					// if the link text is containing a "protocol" eg http:// then do not track it, otherwise
					// it will look like Phishing
					// it's ok when the link is an image
					$linkText = strip_tags ( $linkText );
					$looksLikePhishing = stripos ( $linkText, 'https://' ) !== false || stripos ( $linkText, 'http://' ) !== false;

					// let's leave this for now
					$urlBase = '';
					/*
					 * if (preg_match('/<base href="(.*)"([^>]*)>/Umis',$htmlmessage,$regs)) { $urlbase = $regs[1]; } else { $urlbase = ''; } # print "URLBASE: $urlbase<br/>";
					*/
					if (! $looksLikePhishing && (preg_match ( '/^http|ftp/', $link ) || preg_match ( '/^http|ftp/', $urlBase )) && ! strpos ( $link, $clickTrackRoot )) {
						// Record the original link which will be used as forward link later
						$emailLinkData = array (
							'email_marketing_statistic_id' 	=> $statistic ['EmailMarketingStatistic'] ['id'],
							'url' 							=> $link
						);
						$this->EmailMarketingEmailLink->saveEmailLink ( $emailLinkData ); // Because we create a new statistic instance for each email sending, it won't be associated with any email link. In this way, we can direct save email link and make it associates with the newly created statistic instance
						$emailLinkId = $this->EmailMarketingEmailLink->getInsertID ();

						// Add encrypted email link id to the link
						App::uses ('Shell', 'Console');
						App::uses ( 'EncryptionTask', 'EmailMarketing.Console/Command/Task' );
						$Encryption 	= new EncryptionTask();
						$emailLinkId 	= $Encryption->base64Encode ( $emailLinkId, $this->_getSystemDefaultConfigSetting('XORMask', Configure::read('Config.type.emailmarketing')) );
						$newLink 		= sprintf ( '<a %shref="%sadmin/email_marketing/email_marketing_statistics/trackClick/' . $emailLinkId . '/[SUBSCRIBER-ENCRYPTED-ID]" %s>%s</a>', $links [1] [$i], Router::url ( '/', true ), $links [3] [$i], $links [4] [$i] );
						$emailBody 		= str_replace ( $links [0] [$i], $newLink, $emailBody );
					}
				}
			}

			// Save ready-to-go email body
			$campaign['EmailMarketingCampaign']['ready_to_go_email_body'] = $emailBody;
			$campaign = $this->__campaignAction('set_ready_to_go_email_body', $campaign, null, $campaign['EmailMarketingCampaign']['id']);
		}

		// Send email to subscribers using multi-thread process
		$jobType 		= $this->_getSystemDefaultConfigSetting('ParallelTaskType', Configure::read('Config.type.emailmarketing'));
		$userId 		= $this->Session->read('Auth.User.id');
		try{

			// Before add job to the queue, check whether the ZMQ client is running or not.
			// If the client is down, re-start it now. (Client will check the server status and make sure the server is running)

			$isZMQRunning = $this->_getSystemDefaultConfigSetting('ZMQRunning', Configure::read('Config.type.system'));
			if(empty($isZMQRunning)){
				$zmqMaxParallelThread	= $this->_getSystemDefaultConfigSetting('ZMQMaxParallelThread', Configure::read('Config.type.system'));
				$zmqJobFetchInterval 	= $this->_getSystemDefaultConfigSetting('ZMQJobFetchInterval', Configure::read('Config.type.system'));
				$zmqPollFetchInterval 	= $this->_getSystemDefaultConfigSetting('ZMQPollFetchInterval', Configure::read('Config.type.system'));
				$zmqMaxFetchAmount 		= $this->_getSystemDefaultConfigSetting('ZMQMaxFetchAmount', Configure::read('Config.type.system'));
				$zmqMaxIdelTime 		= $this->_getSystemDefaultConfigSetting('ZMQMaxIdelTime', Configure::read('Config.type.system'));
				$zmqDebug 				= $this->_getSystemDefaultConfigSetting('ZMQDebug', Configure::read('Config.type.system'));
				$zmqDebugOutputMethod 	= $this->_getSystemDefaultConfigSetting('ZMQDebugOutputMethod', Configure::read('Config.type.system'));
				$startClientCommand = "zmq_multithreaded_client --max_parallel_threads $zmqMaxParallelThread --job_fetch_interval $zmqJobFetchInterval --poll_fetch_interval $zmqPollFetchInterval --max_fetch_amount $zmqMaxFetchAmount --max_idel_time $zmqMaxIdelTime --user_id $this->superUserId --debug $zmqDebug --debug_output_method $zmqDebugOutputMethod";
				passthru(APP ."Console" . DS ."cake " .$startClientCommand ." > /dev/null 2>&1 &");
			}

			$jobTaskFunc = new SerializableClosure( function ($campaignId, $subscriberId, $statisticId, $superUserId, $test = 0) {
				$command 	= APP ."Console" .DS ."cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId} -e {$test} -u {$superUserId}";
				$response 	= system($command);
				return $response;
			});
			foreach ( $subscribers as $s ) {

				if($isTest){

					$testArg = 'TEST'; // Just some non-digit data which make this field not empty
					if(is_numeric($s['id'])){
						$testArg = $s['id'];
						$s['id'] = $s['email'];
					}
					$jobTaskFuncParams = array($campaign ["EmailMarketingCampaign"] ["id"], $s['id'], $statistic['EmailMarketingStatistic'] ['id'], $this->superUserId, $testArg);

				}else{
					$jobTaskFuncParams = array($campaign ["EmailMarketingCampaign"] ["id"], $s['id'], $statistic['EmailMarketingStatistic'] ['id'], $this->superUserId);
				}

				if($this->ZMQMultithreadedService->addJob($jobType, $userId, $excutionTime, $jobTaskFunc, $jobTaskFuncParams)){

					$logType 	 = Configure::read('Config.type.emailmarketing');
					$logLevel 	 = Configure::read('System.log.level.debug');
					$logMessage  = __('Email marketing campaign sending task has been added. (User ID: ' .$this->superUserId .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				}else{

					$logType 	 = Configure::read('Config.type.emailmarketing');
					$logLevel 	 = Configure::read('System.log.level.critical');
					$logMessage  = __('Email marketing campaign sending task cannot be added. (User ID: ' .$this->superUserId .')');
					$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

					throw new FatalErrorException(__("Cannot add email sending job"), 500, ROOT . DS . APP_DIR . DS ."Plugin" .DS ."Controller" .DS .$this->name ."Controller.php", 1165);
				}
			}

		}catch(Exception $e){

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.critical');
			$logMessage  = __("Email Marketing Campaign Sending Exception: ") .'<br />'.
						   __("Error Message: ") . $e->getMessage() .'<br />'.
						   __("Line Number: ") .$e->getLine() .'<br />'.
						   __("Trace: ") .$e->getTraceAsString() .'<br />'.
						   __('User ID: ') .$this->superUserId;
			$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

			return false;
		}

		return true;
	}

/**
 * Process campaign
 *
 * @return Object
 */
	private function __campaignAction($action, $campaignData, $externalWebPageContent = null, $campaignId = null) {
		if (is_string ( $externalWebPageContent )) {
			$externalWebPageContent = $this->Util->sanitizeHTML ( $externalWebPageContent );
			$externalWebPageContent = preg_replace ( '#</html>.*#msi', '</html>', $externalWebPageContent );
			$externalWebPageContent = str_ireplace ( '<p><!DOCTYPE', '<!DOCTYPE', $externalWebPageContent );
			$externalWebPageContent = str_ireplace ( '</html></p>', '</html>', $externalWebPageContent );
			$campaignData ['EmailMarketingCampaign'] ['cached_web_page'] = htmlentities ( utf8_encode ( $externalWebPageContent ), ENT_QUOTES, 'UTF-8' );
		} else {
			$campaignData ['EmailMarketingCampaign'] ['cached_web_page'] = isset($campaignData ['EmailMarketingCampaign'] ['cached_web_page']) ? $campaignData ['EmailMarketingCampaign'] ['cached_web_page'] : null;
		}

		// Keep the assigned mailing list
		if (isset ( $campaignData ["EmailMarketingMailingList"] ) && ! isset ( $campaignData ["EmailMarketingMailingList"] ["EmailMarketingMailingList"] )) {
			$emailMarketingMailingLists = Set::classicExtract ( $campaignData ["EmailMarketingMailingList"], '{n}.id' );
			$campaignData ["EmailMarketingMailingList"] = array (
				"EmailMarketingMailingList" => $emailMarketingMailingLists
			);
		}

		// Record correct template ID
		if(!empty($campaignData ['EmailMarketingCampaign'] ['use_external_web_page'])){
			$campaignData['EmailMarketingCampaign'] ['email_marketing_template_id'] = null;
			$campaignData['EmailMarketingCampaign'] ['email_marketing_purchased_template_id'] = null;
		}
		if(empty($campaignData ['EmailMarketingCampaign'] ['use_template']) && $action != 'set_ready_to_go_email_body'){
			$campaignData['EmailMarketingCampaign'] ['email_marketing_template_id'] = null;
		}
		if(empty($campaignData ['EmailMarketingCampaign'] ['use_purchased_template']) && $action != 'set_ready_to_go_email_body'){
			$campaignData['EmailMarketingCampaign'] ['email_marketing_purchased_template_id'] = null;
		}

		if($action == "add"){
			$processRes = $this->EmailMarketingCampaign->saveCampaign ( $campaignData );
		}else{
			if(empty($campaignId) && isset($campaignData['EmailMarketingCampaign']['id'])){
				$campaignId = $campaignData['EmailMarketingCampaign']['id'];
			}
			if(!empty($campaignId)){
				$processRes = $this->EmailMarketingCampaign->updateCampaign ( $campaignId, $campaignData );
			}
		}

		if ($processRes) {
			$this->_showSuccessFlashMessage ( $this->EmailMarketingCampaign );
		} else {
			$this->_showErrorFlashMessage ( $this->EmailMarketingCampaign );
		}
		return $campaignData;
	}

/**
 * reload and cache external web page
 *
 * @return void
 */
	private function __cacheExternalWebPage($externalWebPageUrl, $processExternalWebPageContentFunc, $campaignId = null) {

		$content = $this->admin_getExternalWebPageContent($externalWebPageUrl);
		if($content){

			if (empty ( $campaignId )) {
				$processExternalWebPageContentFunc ( $content );
			} else {
				$processExternalWebPageContentFunc ( $campaignId, $content );
			}
		}
	}

	private function __prepareViewData() {

		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		$this->loadModel ( 'EmailMarketing.EmailMarketingUser' );
		$userList = $this->EmailMarketingUser->getUserList($userServiceAccountId);

		$this->loadModel ( 'EmailMarketing.EmailMarketingTemplate' );
		$templateList = $this->EmailMarketingTemplate->getTemplateList($userServiceAccountId);

		$this->loadModel ( 'EmailMarketing.EmailMarketingPurchasedTemplate' );
		$purchasedTemplateList = $this->EmailMarketingPurchasedTemplate->getPurchasedTemplateList($userServiceAccountId);

		$this->loadModel ( 'EmailMarketing.EmailMarketingSender' );
		$senderList = $this->EmailMarketingSender->getSenderList($userServiceAccountId);

		$findSubscriberListConditions = array();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			$findSubscriberListConditions = array(
				'fields' => array(
					'EmailMarketingMailingList.id',
					'EmailMarketingMailingList.name'
				),
				'conditions' => array(
					'EmailMarketingMailingList.deleted' => 0,
					'EmailMarketingMailingList.active' => 1,
				),
				'joins' => array(
					array(
						'table' => 'email_marketing_users',
						'alias' => 'EmailMarketingMiddleUser',
						'type'  => 'inner',
						'conditions' => array(
							'EmailMarketingMiddleUser.id = EmailMarketingMailingList.email_marketing_user_id',
							'EmailMarketingMiddleUser.user_id' => $userServiceAccountId
						)
					)
				)
			);
		}
		$subscriberList = $this->EmailMarketingCampaign->EmailMarketingMailingList->find( 'list', $findSubscriberListConditions );

		$this->set ( compact ( 'userList', 'templateList', 'purchasedTemplateList', 'subscriberList', 'senderList' ) );
	}

	private function __getEmailHtmlHeader(){
		$emailContentHeader = <<<HEADER
						<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
							<head>
								<!--[if (gte mso 9)|(IE)]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
								<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
								<meta name="viewport" content="width=device-width, initial-scale=1" />
								<meta http-equiv="X-UA-Compatible" content="IE=edge" />
								<meta name="format-detection" content="telephone=no" />
								<meta name="format-detection" content="date=no" />
								<meta name="format-detection" content="address=no" />
								<meta name="format-detection" content="email=no" />

								<!--[if (gte mso 9)|(IE)]>
									<style type="text/css">
									  table, tr, td, div, span, p, a {font-family: Arial,Helvetica,sans-serif !important;}
									  td a {color: inherit !important; text-decoration: none !important;}
									  a {color: inherit !important; text-decoration: none !important;}
									</style>
								<![endif]-->
							</head>
							<body marginwidth="0" marginheight="0" style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;" offset="0" topmargin="0" leftmargin="0">


HEADER;
		return $emailContentHeader;
	}

}
