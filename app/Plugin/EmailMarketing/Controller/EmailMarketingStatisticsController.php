<?php
App::uses ( 'EmailMarketingAppController', 'EmailMarketing.Controller' );
App::uses('BrowserDetection', 'Util');
App::uses('IPLocation', 'Util');

/**
 * Statistics Controller
 */
class EmailMarketingStatisticsController extends EmailMarketingAppController {

	public $paginate = array (
		'limit' => 12,
		'order' => array (
			"EmailMarketingStatistic.id" => "DESC"
		)
	);

	public function beforeFilter() {

		$this->Auth->allow ( 'admin_trackOpen' );
		$this->Auth->allow ( 'admin_trackClick' );

		parent::beforeFilter ();
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (! $this->EmailMarketingStatistic->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$contain = array (
			'EmailMarketingCampaign' => array (
				'EmailMarketingUser' => array (
					'EmailMarketingPlan'
				)
			),
			'EmailMarketingEmailLink' => array (
				'EmailMarketingSubscriberClickRecord' => array (
					'EmailMarketingSubscriber'
				)
			),
			'EmailMarketingSubscriberOpenRecord' => array (
				'EmailMarketingSubscriber'
			),
			'EmailMarketingSubscriberBounceRecord' => array (
				'EmailMarketingSubscriber'
			)
		);
		$statistics = $this->EmailMarketingStatistic->browseBy ( $this->EmailMarketingStatistic->primaryKey, $id, $contain );

		$this->loadModel('EmailMarketing.EmailMarketingCampaign');
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($statistics['EmailMarketingCampaign']['id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "view");
			}
		}

		// We calculate actual clicks here. If the user clicks, we just count.
		$totalClicked 	= Set::classicExtract($statistics, 'EmailMarketingEmailLink.{n}.EmailMarketingSubscriberClickRecord.{n}.id');
		$totalClicked	= array_filter($totalClicked);
		$totalClicked	= Hash::flatten($totalClicked);
		$totalClicked	= count($totalClicked);

		// We only count unique opened amount, not actural opened times (You know, some users may open and close the email in the email box many times)
		$totalOpened	= Set::classicExtract($statistics, 'EmailMarketingSubscriberOpenRecord.{n}.email_marketing_subscriber_id');
		$totalOpened	= array_unique($totalOpened);
		$totalOpened	= count($totalOpened);

		// We count unique bounced amount
		$totalBounced	= Set::classicExtract($statistics, 'EmailMarketingSubscriberBounceRecord.{n}.email_marketing_subscriber_id');
		$totalBounced	= array_unique($totalBounced);
		$totalBounced	= count($totalBounced);

		$this->set ( compact ( 'statistics', 'totalClicked', 'totalOpened', 'totalBounced' ) );

	}

/**
 * viewCampaignHistory method
 * @param string $id
 * @throws NotFoundException
 */
	public function admin_viewCampaignHistory($id){
		$this->loadModel('EmailMarketing.EmailMarketingCampaign');
		if (! $this->EmailMarketingCampaign->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($id, $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "view ? campaign history");
			}
		}

		$this->set('campaignId', $id);

		$this->paginate = array(
			'conditions' => array('EmailMarketingStatistic.email_marketing_campaign_id' => $id),
			'limit'		 => 14,
			'recursive'  => -1,
			'order' 	 => array('EmailMarketingStatistic.id' => 'DESC')
		);

		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse());
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingStatistic.id']);

	}

/**
 * viewCampaignSubscriberStatistics method
 * @param string $id
 * @throws NotFoundException
 */
	public function admin_viewCampaignSubscribersStatistics($id){
		if (! $this->EmailMarketingStatistic->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$contain = array ('EmailMarketingCampaign');
		$statistics = $this->EmailMarketingStatistic->browseBy ( $this->EmailMarketingStatistic->primaryKey, $id, $contain );

		$this->loadModel('EmailMarketing.EmailMarketingCampaign');
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($statistics['EmailMarketingCampaign']['id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "view ? of campaign subscribers");
			}
		}

		$this->set('emailMarketingStatisticId', $id);

		$this->loadModel('EmailMarketing.EmailMarketingSubscriber');
		$this->EmailMarketingSubscriber->virtualFields = array(
			'email_marketing_statistic_id'	=> $id,
			'name'	  						=> "CONCAT(EmailMarketingSubscriber.first_name,' ',EmailMarketingSubscriber.last_name)",
			'list_name'						=> "EmailMarketingMailingList.name",
    		'opened'  						=> "SELECT COUNT(*) FROM email_marketing_subscriber_open_records AS s WHERE s.email_marketing_subscriber_id = EmailMarketingSubscriber.id AND s.email_marketing_statistic_id = \"{$id}\"",
    		'bounced'  						=> "SELECT COUNT(*) FROM email_marketing_subscriber_bounce_records AS s WHERE s.email_marketing_subscriber_id = EmailMarketingSubscriber.id AND s.email_marketing_statistic_id = \"{$id}\"",
    		'clicked' 						=> "SELECT COUNT(*) FROM email_marketing_subscriber_click_records AS s LEFT JOIN email_marketing_email_links AS l ON l.id = s.email_marketing_email_link_id WHERE s.email_marketing_subscriber_id = EmailMarketingSubscriber.id AND l.email_marketing_statistic_id = \"{$id}\""
    	);
		$this->paginate = array(
			'fields'	 => array(
				'EmailMarketingSubscriber.*'
			),
			'conditions' => array(
				'EmailMarketingSubscriber.excluded' 		=> 0,
				'EmailMarketingSubscriber.unsubscribed' 	=> 0
			),
			'joins' 	 => array (
				array(
					'table' 	 => 'email_marketing_mailing_lists',
					'alias'	 	 => 'EmailMarketingMailingList',
					'type' 		 => 'inner',
					'conditions' => array(
						'EmailMarketingMailingList.id = EmailMarketingSubscriber.email_marketing_list_id'
					)
				),
				array(
					'table' 	 => 'email_marketing_campaign_lists',
					'alias'	 	 => 'EmailMarketingCampaignLists',
					'type' 		 => 'inner',
					'conditions' => array(
						'EmailMarketingCampaignLists.email_marketing_list_id = EmailMarketingMailingList.id'
					)
				),
				array(
					'table' 	 => 'email_marketing_campaigns',
					'alias'	 	 => 'EmailMarketingCampaign',
					'type' 		 => 'inner',
					'conditions' => array(
						'EmailMarketingCampaignLists.email_marketing_campaign_id = EmailMarketingCampaign.id'
					)
				),
				array (
					'table' 	 => 'email_marketing_statistics',
					'alias'	 	 => 'EmailMarketingStatistic',
					'type' 		 => 'inner',
					'conditions' => array(
						'EmailMarketingStatistic.email_marketing_campaign_id = EmailMarketingCampaign.id',
						'EmailMarketingStatistic.id' => $id
					)
				)
			),
			'limit'		 => 11,
			'contain' 	 => false,
			'order'		 => array('EmailMarketingSubscriber.email_marketing_list_id')
		);
		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse(null, 'EmailMarketingSubscriber'));
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingSubscriber.email_marketing_list_id']);

	}

/**
 * viewCampaignEmailLinkStatistics method
 * @param string $id
 * @throws NotFoundException
 */
	public function admin_viewCampaignEmailLinksStatistics($id){
		if (! $this->EmailMarketingStatistic->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$contain = array ('EmailMarketingCampaign');
		$statistics = $this->EmailMarketingStatistic->browseBy ( $this->EmailMarketingStatistic->primaryKey, $id, $contain );

		$this->loadModel('EmailMarketing.EmailMarketingCampaign');
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($statistics['EmailMarketingCampaign']['id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "view ? of email links");
			}
		}

		$this->set("statisticId", $id);

		$this->loadModel('EmailMarketing.EmailMarketingEmailLink');
		$this->EmailMarketingEmailLink->virtualFields = array(
			'clicked' 	 => "SELECT COUNT(*) FROM email_marketing_subscriber_click_records AS s LEFT JOIN email_marketing_email_links AS l ON l.id = s.email_marketing_email_link_id WHERE s.email_marketing_email_link_id = EmailMarketingEmailLink.id AND l.email_marketing_statistic_id = \"{$id}\""
		);
		$this->paginate = array(
			'fields'	 => array(
				'EmailMarketingEmailLink.*'
			),
			'conditions' => array (
				'EmailMarketingEmailLink.email_marketing_statistic_id' => $id
			),
			'limit'		 => 11,
			'order'		 => array('EmailMarketingEmailLink.clicked')
		);
		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse(null, 'EmailMarketingEmailLink'));
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingEmailLink.clicked']);

	}

	public function admin_viewStatisticsBySubscriber($subscriberId, $statisticId){
		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriber' );
		if (! $this->EmailMarketingSubscriber->exists ( $subscriberId ) || ! $this->EmailMarketingStatistic->exists ( $statisticId )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $subscriberId, false);

		// Client cannot view other person's subscriber statistics
		$this->loadModel ( 'EmailMarketing.EmailMarketingMailingList' );
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

				throw new ForbiddenActionException ($this->modelClass, "view ? by subscriber");
			}
		}

		$this->set(compact('subscriber', 'subscriberId', 'statisticId'));

	}

	public function admin_getSubscriberClickRecord($subscriberId, $statisticId){

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriber' );
		if (! $this->EmailMarketingSubscriber->exists ( $subscriberId ) || ! $this->EmailMarketingStatistic->exists ( $statisticId )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $subscriberId, false);

		// Client cannot view other person's subscriber statistics
		$this->loadModel ( 'EmailMarketing.EmailMarketingMailingList' );
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "find subscriber click records in ?");
			}
		}

		$this->set(compact('subscriberId', 'statisticId'));

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberClickRecord' );
		$this->paginate = array(
				'fields'	 => array(
						'EmailMarketingSubscriberClickRecord.timestamp',
						'EmailMarketingEmailLink.url'
				),
				'conditions' => array (
						'EmailMarketingSubscriberClickRecord.email_marketing_subscriber_id' => $subscriberId
				),
				'joins' 	 => array(
						array(
								'table' 	 => 'email_marketing_email_links',
								'alias'	 	 => 'EmailMarketingEmailLink',
								'type' 		 => 'inner',
								'conditions' => array(
										'EmailMarketingEmailLink.id = EmailMarketingSubscriberClickRecord.email_marketing_email_link_id',
										'EmailMarketingEmailLink.email_marketing_statistic_id' => $statisticId
								)
						)
				),
				'contain' 	 => false,
				'limit'		 => 10,
				'order'		 => array('EmailMarketingSubscriberClickRecord.timestamp' => 'DESC')
		);
		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse(null, 'EmailMarketingSubscriberClickRecord'));
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingSubscriberClickRecord.timestamp']);

	}

	public function admin_getSubscriberOpenRecord($subscriberId, $statisticId){

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriber' );
		if (! $this->EmailMarketingSubscriber->exists ( $subscriberId ) || ! $this->EmailMarketingStatistic->exists ( $statisticId )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $subscriberId, false);

		// Client cannot view other person's subscriber statistics
		$this->loadModel ( 'EmailMarketing.EmailMarketingMailingList' );
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "find subscriber read email records in ?");
			}
		}

		$this->set(compact('subscriberId', 'statisticId'));

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberOpenRecord' );
		$this->paginate = array (
				'fields'	 => array(
						'EmailMarketingSubscriberOpenRecord.timestamp'
				),
				'conditions' => array (
						'EmailMarketingSubscriberOpenRecord.email_marketing_subscriber_id' => $subscriberId,
						'EmailMarketingSubscriberOpenRecord.email_marketing_statistic_id'  => $statisticId
				),
				'contain' 	 => false,
				'limit'		 => 10,
				'order'		 => array('EmailMarketingSubscriberOpenRecord.timestamp' => 'DESC')
		);
		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse(null, 'EmailMarketingSubscriberOpenRecord'));
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingSubscriberOpenRecord.timestamp']);

	}

	public function admin_getSubscriberBounceRecord($subscriberId, $statisticId){

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriber' );
		if (! $this->EmailMarketingSubscriber->exists ( $subscriberId ) || ! $this->EmailMarketingStatistic->exists ( $statisticId )) {

			throw new NotFoundRecordException ($this->modelClass);
		}
		$subscriber = $this->EmailMarketingSubscriber->browseBy($this->EmailMarketingSubscriber->primaryKey, $subscriberId, false);

		// Client cannot view other person's subscriber statistics
		$this->loadModel ( 'EmailMarketing.EmailMarketingMailingList' );
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingMailingList->checkRecordBelongsEmailMarketingUser($subscriber['EmailMarketingSubscriber']['email_marketing_list_id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "find subscriber bounce record in ?");
			}
		}

		$this->set(compact('subscriberId', 'statisticId'));

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberBounceRecord' );
		$this->paginate = array (
			'fields'	 => array(
				'EmailMarketingSubscriberBounceRecord.email_marketing_subscriber_id as id', // Change column name in order to let batch delete function get the record ID
				'EmailMarketingSubscriberBounceRecord.bounce_type',
				'EmailMarketingSubscriberBounceRecord.bounce_reason',
				'EmailMarketingSubscriberBounceRecord.timestamp'
			),
			'conditions' => array (
				'EmailMarketingSubscriberBounceRecord.email_marketing_subscriber_id' => $subscriberId,
				'EmailMarketingSubscriberBounceRecord.email_marketing_statistic_id'  => $statisticId
			),
			'contain' 	 => false,
			'limit'		 => 10,
			'order'		 => array('EmailMarketingSubscriberBounceRecord.timestamp' => 'DESC')
		);
		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse(null, 'EmailMarketingSubscriberBounceRecord'));
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingSubscriberBounceRecord.timestamp']);

	}

	public function admin_viewStatisticsByEmailLink($emailLinkId){
		$this->loadModel ( 'EmailMarketing.EmailMarketingEmailLink' );
		if (! $this->EmailMarketingEmailLink->exists ( $emailLinkId )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		$emailLink = $this->EmailMarketingEmailLink->browseBy($this->EmailMarketingEmailLink->primaryKey, $emailLinkId, false);
		$this->set(compact('emailLink', 'emailLinkId'));

		$contain = array ('EmailMarketingCampaign');
		$statistics = $this->EmailMarketingStatistic->browseBy ( $this->EmailMarketingStatistic->primaryKey, $emailLink['EmailMarketingEmailLink']['email_marketing_statistic_id'], $contain );
		$this->loadModel('EmailMarketing.EmailMarketingCampaign');
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($statistics['EmailMarketingCampaign']['id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "view ? by email link");
			}
		}

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberClickRecord' );
		$this->paginate = array(
			'fields'	 => array(
				'EmailMarketingSubscriberClickRecord.*',
				'EmailMarketingSubscriber.*'
			),
			'joins' => array(
				array(
					'table' => 'email_marketing_subscribers',
					'alias' => 'EmailMarketingSubscriber',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingSubscriberClickRecord.email_marketing_subscriber_id = EmailMarketingSubscriber.id'
					)
				)
			),
			'conditions' => array (
				'EmailMarketingSubscriberClickRecord.email_marketing_email_link_id' => $emailLinkId
			),
			'limit'		 => 10,
			'order'		 => array('EmailMarketingSubscriberClickRecord.timestamp' => 'DESC')
		);
		$this->Paginator->settings = $this->paginate;
		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse(null, 'EmailMarketingSubscriberClickRecord'));
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['EmailMarketingSubscriberClickRecord.timestamp']);

	}

/**
 * edit method
 *
 * This function only accepts Ajax request, it means user cannot edit it over the browser form, only system ajax request can update it.
 *
 * @throws NotFoundException
 * @param string $id
 * @param boolean $silentUpdate
 *        	(some ajax call is one-time-call and it won't required any response)
 * @param boolean $isReferenced
 *        	(if call this action method from another ajax action, set this to true and we will exit the process in the caller's method)
 * @return void
 */
	public function admin_edit($id = null, $silentUpdate = false, $isReferenced = false) {
		if (! $this->EmailMarketingStatistic->exists ( $id )) {

			throw new NotFoundRecordException ($this->modelClass);
		}

		// Because this action should always be performed by the system, not the client, we only log the error and keep this action transparent to the client
		$contain = array ('EmailMarketingCampaign');
		$statistics = $this->EmailMarketingStatistic->browseBy ( $this->EmailMarketingStatistic->primaryKey, $id, $contain );
		$this->loadModel('EmailMarketing.EmailMarketingCampaign');
		$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(!$this->EmailMarketingCampaign->checkRecordBelongsEmailMarketingUser($statistics['EmailMarketingCampaign']['id'], $userServiceAccountId)){

				throw new ForbiddenActionException($this->modelClass, "edit");
			}
		}

		$this->_prepareAjaxPostAction ();

		$response = null;

		if (($this->request->is ( 'post' ) || $this->request->is ( 'put' )) && $this->request->is ( 'ajax' ) && isset ( $this->request->data ["EmailMarketingStatistic"] ) && ! empty ( $this->request->data ["EmailMarketingStatistic"] )) {
			if ($this->EmailMarketingStatistic->updateStatistic ( $id, $this->request->data )) {
				$response = json_encode ( array (
					'status' => Configure::read('System.variable.success'),
					'message' => __ ( "Statistic has been successfully updated." )
				) );
			} else {
				$response = json_encode ( array (
					'status' => Configure::read('System.variable.error'),
					'message' => __ ( "Update statistic failed." )
				) );
			}
		} else {
			$response = json_encode ( array (
				'status' => Configure::read('System.variable.error'),
				'message' => __ ( "Invalid request or corrupted data given." )
			) );
		}

		if (! $silentUpdate && ! empty ( $response )) {
			echo $response;
		}

		if (! $isReferenced) {
			exit ();
		}
	}

/**
 * Track email has been opened/read or not
 *
 * @param string $messageId
 */
	public function admin_trackOpen($messageId) {

		$this->_prepareNoViewAction ();
		$formatedMessageId = sprintf ( '%s', $messageId );
		if ($formatedMessageId == $messageId) {
			$xorMask 			= $this->_getSystemDefaultConfigSetting( 'XORMask', Configure::read('Config.type.emailmarketing') );
			$track 				= base64_decode ( $formatedMessageId );
			$track 				= $track ^ $xorMask;
			@list ( $campaignId, $subscriberId, $statisticId ) = explode ( '-', $track );
			$statisticId 		= sprintf ( '%d', $statisticId );
			$subscriberId 		= sprintf ( '%d', $subscriberId );
			$clientIP			= $this->request->clientIp(false);
			$mobileDevice 		= $this->RequestHandler->isMobile();
			$userAgent 			= env('HTTP_USER_AGENT');
			$BrowserDetection 	= new BrowserDetection($userAgent);
			$IPLocation 		= new IPLocation();
			$location			= $IPLocation->getLocationByIP($clientIP);

			$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriber' );
			if (! empty ( $statisticId ) && $this->EmailMarketingStatistic->exists ( $statisticId ) && ! empty ( $subscriberId ) && $this->EmailMarketingSubscriber->exists ( $subscriberId )) {

				$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberOpenRecord' );
				$data = array (
					'EmailMarketingSubscriberOpenRecord' => array (
						'email_marketing_statistic_id' 	=> $statisticId,
						'email_marketing_subscriber_id' => $subscriberId,
						'ip'							=> $clientIP,
						'is_mobile'						=> empty($mobileDevice) ? 0 : 1,
						'browser_name'					=> $BrowserDetection->getName(),
						'browser_version'				=> $BrowserDetection->getVersion(),
						'platform_name'					=> $BrowserDetection->getPlatform(),
						'platform_vesion'				=> $BrowserDetection->getPlatformVersion(),
						'country'						=> @$location['country'],
						'region'						=> @$location['region'],
						'city'							=> @$location['city'],
						'timestamp' 					=> date ( 'Y-m-d H:i:s' )
					)
				);
				$this->EmailMarketingSubscriberOpenRecord->saveSubscriberOpenRecord ( $data );

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.debug');
				$logMessage  = __("Track opened email marketing campaign email (message ID): {$messageId}");
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			}elseif(empty ( $statisticId ) || !$this->EmailMarketingStatistic->exists ( $statisticId )){

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.debug');
				$logMessage  = __('User (#' .$this->superUserId .') tried to track email has been opened or not using invalid statistic ID, contained statistic ID invalid. (Passed email marketing message ID: ' .$messageId .', Statistic ID: ' .$statisticId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			}elseif (empty ( $subscriberId ) || !$this->EmailMarketingSubscriber->exists ( $subscriberId )){

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.debug');
				$logMessage  = __('User (#' .$this->superUserId .') tried to track email has been opened or not using invalid subscriber ID, contained subscriber ID invalid. (Passed email marketing message ID: ' .$messageId .', Subscriber ID: ' .$subscriberId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			}
		}else{

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.debug');
			$logMessage  = __('User (#' .$this->superUserId .') tried to track email has been opened or not using invalid message ID. (Passed email marketing message ID: ' .$messageId .')');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

		}

		// Because the track URL is embeded in image, return an image to avoid broken link
		header('Content-Type: image/png');
		$this->response->file(Configure::read('EmailMarketing.email.track.image'));
		return $this->response;
	}

/**
 * Track which link has been clicked in email content
 *
 * @param string $messageId
 * @param string $forwardUrl
 */
	public function admin_trackClick($emailLinkId, $subscriberId) {
		$this->_prepareNoViewAction ();
		$formatedEmailLinkId = sprintf ( '%s', $emailLinkId );
		$formatedSubscriberId = sprintf ( '%s', $subscriberId );
		if ($formatedEmailLinkId == $emailLinkId && $formatedSubscriberId == $subscriberId) {
			$xorMask = $this->_getSystemDefaultConfigSetting( 'XORMask', Configure::read('Config.type.emailmarketing') );
			$track = base64_decode ( $formatedEmailLinkId );
			$emailLinkId = $track ^ $xorMask;
			$emailLinkId = sprintf ( '%d', $emailLinkId );
			$track = base64_decode ( $formatedSubscriberId );
			$subscriberId = $track ^ $xorMask;
			$subscriberId = sprintf ( '%d', $subscriberId );

			$this->loadModel ( 'EmailMarketing.EmailMarketingEmailLink' );
			$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriber' );
			if (! empty ( $emailLinkId ) && $this->EmailMarketingEmailLink->exists ( $emailLinkId ) && ! empty ( $subscriberId ) && $this->EmailMarketingSubscriber->exists ( $subscriberId )) {

				$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberClickRecord' );
				$data = array (
					'EmailMarketingSubscriberClickRecord' => array (
						'email_marketing_email_link_id' => $emailLinkId,
						'email_marketing_subscriber_id' => $subscriberId,
						'timestamp' 					=> date ( 'Y-m-d H:i:s' )
					)
				);
				$this->EmailMarketingSubscriberClickRecord->saveSubscriberClickRecord ( $data );

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.debug');
				$logMessage  = __("Track clicked link in email marketing campaign email (link ID / subscriber ID): {$emailLinkId} / {$subscriberId}");
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

				// Forward to real linked URL
				$emailLink = $this->EmailMarketingEmailLink->browseBy ( $this->EmailMarketingEmailLink->primaryKey, $emailLinkId, false );
				header ( "Location: {$emailLink['EmailMarketingEmailLink']['url']}" );
				exit ();

			}elseif(empty ( $emailLinkId ) || !$this->EmailMarketingEmailLink->exists ( $emailLinkId )){

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.debug');
				$logMessage  = __('User (#' .$this->superUserId .') tried to track email has been clicked or not using invalid link ID. (Passed email marketing link ID: ' .$emailLinkId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			}elseif(empty ( $subscriberId ) || !$this->EmailMarketingSubscriber->exists ( $subscriberId )){

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.debug');
				$logMessage  = __('User (#' .$this->superUserId .') tried to track email has been clicked or not using invalid subscriber ID. (Passed email marketing subscriber ID: ' .$subscriberId .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage);

			}
		}else{

			$logType 	 = Configure::read('Config.type.emailmarketing');
			$logLevel 	 = Configure::read('System.log.level.debug');
			$logMessage  = __('User (#' .$this->superUserId .') tried to track email link has been clicked or not using invalid IDs. (Passed email marketing link ID / subscriber ID: ' .$emailLinkId .' / ' .$subscriberId .')');
			$this->Log->addLogRecord($logType, $logLevel, $logMessage);

		}
	}

	public function admin_getStatisticsReport(){

		$this->_prepareAjaxPostAction();

		$this->loadModel('EmailMarketing.EmailMarketingCampaign');

		if(!empty($this->request->data['campaignIds'])){

			foreach($this->request->data['campaignIds'] as $id){

				if(!is_numeric($id) || !$this->EmailMarketingCampaign->exists ( $id )){

					throw new NotFoundRecordException ($this->modelClass);
				}
			}
		}

		if(empty($this->request->data['startDate']) || empty($this->request->data['endDate'])){

			echo '{}';
			exit();
		}

		$periodsQuery = array(
			'fields'	 => array(
				'COUNT(*) AS amount',
				'DATE(EmailMarketingStatistic.send_start) AS period'
			),
			'conditions' => array(
				'EmailMarketingStatistic.send_start BETWEEN ? AND ? ' =>  array($this->request->data['startDate'], $this->request->data['endDate']),
				'EmailMarketingCampaign.deleted !=' => 1
			),
			'joins' => array(
				array(
					'table' => 'email_marketing_campaigns',
					'alias' => 'EmailMarketingCampaign',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingCampaign.id = EmailMarketingStatistic.email_marketing_campaign_id'
					)
				)
			),
			'recursive'  => -1,
			'group'		 => array('DATE(EmailMarketingStatistic.send_start)'),
			'order'		 => array('EmailMarketingStatistic.send_start' => 'ASC')
		);

		if(!empty($this->request->data['campaignIds'])){
			$periodsQuery['conditions']['EmailMarketingStatistic.email_marketing_campaign_id'] = $this->request->data['campaignIds'];
		}

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
			$emailMarketingUserId = $this->EmailMarketingStatistic->superUserIdToEmailMarketingUserId($userServiceAccountId);
			$periodsQuery['conditions']['EmailMarketingCampaign.email_marketing_user_id'] = $emailMarketingUserId;
		}

		// Get period points (day)

		$periodsWithAllRequests = $this->EmailMarketingStatistic->find('all', $periodsQuery);

		if(empty($periodsWithAllRequests)){

			echo '{}';
			exit();
		}

		$statistics = array(
			'overview' 	=> array(),
			'geo'		=> array(),
			'devices'	=> array(),
			'countryMap'=> array()
		);

		$this->loadModel('Country');
		$countryMap = array();

		foreach($periodsWithAllRequests as $requests){
			foreach($requests as $request){
				if(empty($statistics['overview'][$request['period']])){
					$statistics['overview'][$request['period']] = array('requests' => intval($request['amount']));
				}else{
					$statistics['overview'][$request['period']]['requests'] += intval($request['amount']);
				}
			}
		}

		// Get sent email amount on period

		$periodsQuery['conditions']['EmailMarketingStatistic.status'] = 'SENT';
		$periodsWithSent = $this->EmailMarketingStatistic->find('all', $periodsQuery);

		if(!empty($periodsWithSent) && is_array($periodsWithSent)){

			foreach($periodsWithSent as $sents){
				foreach($sents as $sent){
					if(empty($statistics['overview'][$sent['period']])){
						$statistics['overview'][$sent['period']] = array('sents' => intval($sent['amount']));
					}else{
						if(empty($statistics['overview'][$sent['period']]['sents'])){
							$statistics['overview'][$sent['period']]['sents'] = intval($sent['amount']);
						}else{
							$statistics['overview'][$sent['period']]['sents'] += intval($sent['amount']);
						}
					}
				}
			}
		}

		// Get email opened amount on period

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberOpenRecord' );

		$openQuery = array(
			'fields'	 => array(
				'COUNT(*) AS amount',
				'DATE(EmailMarketingSubscriberOpenRecord.timestamp) AS period'
			),
			'conditions' => array(
				'EmailMarketingStatistic.send_start BETWEEN ? AND ? ' =>  array($this->request->data['startDate'], $this->request->data['endDate']),
				'EmailMarketingCampaign.deleted !=' => 1
			),
			'joins' => array(
				array(
					'table' => 'email_marketing_statistics',
					'alias' => 'EmailMarketingStatistic',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingStatistic.id = EmailMarketingSubscriberOpenRecord.email_marketing_statistic_id'
					)
				),
				array(
					'table' => 'email_marketing_campaigns',
					'alias' => 'EmailMarketingCampaign',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingCampaign.id = EmailMarketingStatistic.email_marketing_campaign_id'
					)
				)
			),
			'recursive'  => -1,
			'group'		 => array('DATE(EmailMarketingSubscriberOpenRecord.timestamp)'),
			'order'		 => array('EmailMarketingSubscriberOpenRecord.timestamp' => 'ASC')
		);

		$openByDeviceQuery = $openQuery;
		$openByDeviceQuery['fields'] = array(
			'COUNT(*) AS amount',
			'CONCAT(EmailMarketingSubscriberOpenRecord.browser_name) AS browser_name',
			'CONCAT(EmailMarketingSubscriberOpenRecord.is_mobile) AS is_mobile',
		);
		$openByDeviceQuery['group']  = array('EmailMarketingSubscriberOpenRecord.browser_name', 'EmailMarketingSubscriberOpenRecord.is_mobile');
		$openByDeviceQuery['order']  = array('EmailMarketingSubscriberOpenRecord.browser_name' => 'ASC');

		$openByRegionQuery = $openQuery;
		$openByRegionQuery['fields'] = array('COUNT(*) AS amount', 'CONCAT(EmailMarketingSubscriberOpenRecord.country) AS country');
		$openByRegionQuery['group']  = array('EmailMarketingSubscriberOpenRecord.country');
		$openByRegionQuery['order']  = array('EmailMarketingSubscriberOpenRecord.country' => 'ASC');

		if(!empty($this->request->data['campaignIds'])){
			$openQuery['conditions']['EmailMarketingStatistic.email_marketing_campaign_id'] = $this->request->data['campaignIds'];
			$openByDeviceQuery['conditions']['EmailMarketingStatistic.email_marketing_campaign_id'] = $this->request->data['campaignIds'];
			$openByRegionQuery['conditions']['EmailMarketingStatistic.email_marketing_campaign_id'] = $this->request->data['campaignIds'];
		}

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(empty($emailMarketingUserId)){
				$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
				$emailMarketingUserId = $this->EmailMarketingStatistic->superUserIdToEmailMarketingUserId($userServiceAccountId);
			}
			$openQuery['conditions']['EmailMarketingCampaign.email_marketing_user_id'] = $emailMarketingUserId;
			$openByDeviceQuery['conditions']['EmailMarketingCampaign.email_marketing_user_id'] = $emailMarketingUserId;
			$openByRegionQuery['conditions']['EmailMarketingCampaign.email_marketing_user_id'] = $emailMarketingUserId;
		}

		$periodsWithOpens = $this->EmailMarketingSubscriberOpenRecord->find('all', $openQuery);

		if(!empty($periodsWithOpens) && is_array($periodsWithOpens)){

			foreach($periodsWithOpens as $opens){
				foreach($opens as $open){
					if(empty($statistics['overview'][$open['period']])){
						$statistics['overview'][$open['period']] = array('opens' => intval($open['amount']));
					}else{
						if(empty($statistics['overview'][$open['period']]['opens'])){
							$statistics['overview'][$open['period']]['opens'] = intval($open['amount']);
						}else{
							$statistics['overview'][$open['period']]['opens'] += intval($open['amount']);
						}
					}
				}
			}
		}

		$opensByDevices = $this->EmailMarketingSubscriberOpenRecord->find('all', $openByDeviceQuery);

		if(!empty($opensByDevices) && is_array($opensByDevices)){

			foreach($opensByDevices as $opens){
				foreach($opens as $open){

					if(empty($statistics['devices'][$open['browser_name']])){
						$statistics['devices'][$open['browser_name']] = array($open['is_mobile'] => intval($open['amount']));
					}else{
						if(empty($statistics['devices'][$open['browser_name']][$open['is_mobile']])){
							$statistics['devices'][$open['browser_name']][$open['is_mobile']] = intval($open['amount']);
						}else{
							$statistics['devices'][$open['browser_name']][$open['is_mobile']] += intval($open['amount']);
						}
					}
				}
			}
		}

		$opensByRegions = $this->EmailMarketingSubscriberOpenRecord->find('all', $openByRegionQuery);

		if(!empty($opensByRegions) && is_array($opensByRegions)){

			foreach($opensByRegions as $opens){
				foreach($opens as $open){
					if(empty($countryMap[$open['country']])){
						$countryMap[$open['country']] = $this->Country->findCodeByName($open['country']);
					}
					if(empty($statistics['geo']['open'][$countryMap[$open['country']]])){
						$statistics['geo']['open'][$countryMap[$open['country']]] = intval($open['amount']);
					}else{
						$statistics['geo']['open'][$countryMap[$open['country']]] += intval($open['amount']);
					}
				}
			}
		}

		// Get link clicked amount on period
		// NOTE:
		// 	No matter how many links in one email, if client clicked a link, then this email is clicked. If client clicked multiple links in one email, we only calculate once.
		//	This means one email can only be clicked or not clicked. Although we know which link is clicked and hwo many times it is clicked, we don't summarise that here.

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberClickRecord' );

		$clickQuery = array(
			'fields'	 => array(
				'COUNT( DISTINCT EmailMarketingStatistic.id ) AS amount',
				'DATE(EmailMarketingSubscriberClickRecord.timestamp) AS period'
			),
			'conditions' => array(
				'EmailMarketingStatistic.send_start BETWEEN ? AND ? ' =>  array($this->request->data['startDate'], $this->request->data['endDate']),
				'EmailMarketingCampaign.deleted !=' => 1
			),
			'joins' => array(
				array(
					'table' => 'email_marketing_email_links',
					'alias' => 'EmailMarketingEmailLink',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingEmailLink.id = EmailMarketingSubscriberClickRecord.email_marketing_email_link_id'
					)
				),
				array(
					'table' => 'email_marketing_statistics',
					'alias' => 'EmailMarketingStatistic',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingStatistic.id = EmailMarketingEmailLink.email_marketing_statistic_id'
					)
				),
				array(
					'table' => 'email_marketing_campaigns',
					'alias' => 'EmailMarketingCampaign',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingCampaign.id = EmailMarketingStatistic.email_marketing_campaign_id'
					)
				)
			),
			'recursive'  => -1,
			'group'		 => array('DATE(EmailMarketingSubscriberClickRecord.timestamp)'),
			'order'		 => array('EmailMarketingSubscriberClickRecord.timestamp' => 'ASC')
		);

		$clickByRegionQuery = $clickQuery;
		$clickByRegionQuery['fields'] = array('COUNT(*) AS amount', 'CONCAT(EmailMarketingSubscriberOpenRecord.country) AS country');
		$clickByRegionQuery['joins']  = array( // The $clickQuery get all clicks based on statistic_id. Here the clicks is based on the opens. (Client must OPEN the email first and then CLICK)
			array(
				'table' => 'email_marketing_email_links',
				'alias' => 'EmailMarketingEmailLink',
				'type' => 'inner',
				'conditions' => array(
					'EmailMarketingEmailLink.id = EmailMarketingSubscriberClickRecord.email_marketing_email_link_id'
				)
			),
			array(
				'table' => 'email_marketing_subscriber_open_records',
				'alias' => 'EmailMarketingSubscriberOpenRecord',
				'type' => 'inner',
				'conditions' => array(
					'EmailMarketingSubscriberOpenRecord.email_marketing_statistic_id = EmailMarketingEmailLink.email_marketing_statistic_id'
				)
			),
			array(
				'table' => 'email_marketing_statistics',
				'alias' => 'EmailMarketingStatistic',
				'type' => 'inner',
				'conditions' => array(
					'EmailMarketingStatistic.id = EmailMarketingSubscriberOpenRecord.email_marketing_statistic_id'
				)
			),
			array(
				'table' => 'email_marketing_campaigns',
				'alias' => 'EmailMarketingCampaign',
				'type' => 'inner',
				'conditions' => array(
					'EmailMarketingCampaign.id = EmailMarketingStatistic.email_marketing_campaign_id'
				)
			)
		);
		$clickByRegionQuery['group']  = array('EmailMarketingSubscriberOpenRecord.country');
		$clickByRegionQuery['order']  = array('EmailMarketingSubscriberOpenRecord.country' => 'ASC');

		if(!empty($this->request->data['campaignIds'])){
			$clickQuery['conditions']['EmailMarketingStatistic.email_marketing_campaign_id'] = $this->request->data['campaignIds'];
			$clickByRegionQuery['conditions']['EmailMarketingStatistic.email_marketing_campaign_id'] = $this->request->data['campaignIds'];
		}

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(empty($emailMarketingUserId)){
				$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
				$emailMarketingUserId = $this->EmailMarketingStatistic->superUserIdToEmailMarketingUserId($userServiceAccountId);
			}
			$clickQuery['conditions']['EmailMarketingCampaign.email_marketing_user_id'] = $emailMarketingUserId;
			$clickByRegionQuery['conditions']['EmailMarketingCampaign.email_marketing_user_id'] = $emailMarketingUserId;
		}

		$periodsWithClicks = $this->EmailMarketingSubscriberClickRecord->find('all', $clickQuery);

		if(!empty($periodsWithClicks) && is_array($periodsWithClicks)){

			foreach($periodsWithClicks as $clicks){
				foreach($clicks as $click){
					if(empty($statistics['overview'][$click['period']])){
						$statistics['overview'][$click['period']] = array('clicks' => intval($click['amount']));
					}else{
						if(empty($statistics['overview'][$click['period']]['clicks'])){
							$statistics['overview'][$click['period']]['clicks'] = intval($click['amount']);
						}else{
							$statistics['overview'][$click['period']]['clicks'] += intval($click['amount']);
						}
					}
				}
			}
		}

		$clicksByRegions = $this->EmailMarketingSubscriberClickRecord->find('all', $clickByRegionQuery);

		if(!empty($clicksByRegions) && is_array($clicksByRegions)){

			foreach($clicksByRegions as $clicks){
				foreach($clicks as $click){
					if(empty($countryMap[$click['country']])){
						$countryMap[$click['country']] = $this->Country->findCodeByName($click['country']);
					}
					if(empty($statistics['geo']['click'][$countryMap[$click['country']]])){
						$statistics['geo']['click'][$countryMap[$click['country']]] = intval($click['amount']);
					}else{
						$statistics['geo']['click'][$countryMap[$click['country']]] += intval($click['amount']);
					}
				}
			}
		}

		// Get email bounced amount on period

		$this->loadModel ( 'EmailMarketing.EmailMarketingSubscriberBounceRecord' );

		$bounceQuery = array(
			'fields'	 => array(
				'COUNT(*) AS amount',
				'DATE(EmailMarketingSubscriberBounceRecord.timestamp) AS period'
			),
			'conditions' => array(
				'EmailMarketingStatistic.send_start BETWEEN ? AND ? ' =>  array($this->request->data['startDate'], $this->request->data['endDate']),
				'EmailMarketingCampaign.deleted !=' => 1
			),
			'joins' => array(
				array(
					'table' => 'email_marketing_statistics',
					'alias' => 'EmailMarketingStatistic',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingStatistic.id = EmailMarketingSubscriberBounceRecord.email_marketing_statistic_id'
					)
				),
				array(
					'table' => 'email_marketing_campaigns',
					'alias' => 'EmailMarketingCampaign',
					'type' => 'inner',
					'conditions' => array(
						'EmailMarketingCampaign.id = EmailMarketingStatistic.email_marketing_campaign_id'
					)
				)
			),
			'recursive'  => -1,
			'group'		 => array('DATE(EmailMarketingSubscriberBounceRecord.timestamp)'),
			'order'		 => array('EmailMarketingSubscriberBounceRecord.timestamp' => 'ASC')
		);

		if(!empty($this->request->data['campaignIds'])){
			$bounceQuery['conditions']['EmailMarketingStatistic.email_marketing_campaign_id'] = $this->request->data['campaignIds'];
		}

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			if(empty($emailMarketingUserId)){
				$userServiceAccountId = $this->_getCurrentUserServiceAccountId();
				$emailMarketingUserId = $this->EmailMarketingStatistic->superUserIdToEmailMarketingUserId($userServiceAccountId);
			}
			$bounceQuery['conditions']['EmailMarketingCampaign.email_marketing_user_id'] = $emailMarketingUserId;
		}

		$periodsWithBounces = $this->EmailMarketingSubscriberBounceRecord->find('all', $bounceQuery);

		if(!empty($periodsWithBounces) && is_array($periodsWithBounces)){

			foreach($periodsWithBounces as $bounces){
				foreach($bounces as $bounce){
					if(empty($statistics['overview'][$bounce['period']])){
						$statistics['overview'][$bounce['period']] = array('bounces' => intval($bounce['amount']));
					}else{
						if(empty($statistics['overview'][$bounce['period']]['bounces'])){
							$statistics['overview'][$bounce['period']]['bounces'] = intval($bounce['amount']);
						}else{
							$statistics['overview'][$bounce['period']]['bounces'] += intval($bounce['amount']);
						}
					}
				}
			}
		}

		$statistics['countryMap'] = $countryMap;

		echo json_encode($statistics);

		exit();
	}

/**
 * Split fetched records by timestamp field (timestamp field is json array)
 * @param array $records
 * @param string $modelName
 * @return number|multitype:
 */
	private function __splitRecordByTimestampArr($records, $modelName) {
		$newRecords = array();
		foreach($records as $r){
			$r[$modelName]['timestamp'] = json_decode($r[$modelName]['timestamp']);
			if(is_array($r[$modelName]['timestamp'])){
				foreach($r[$modelName]['timestamp'] as $t){
					$separatedRecord = $r;
					$separatedRecord[$modelName]['timestamp'] = $t;
					array_push($newRecords, $separatedRecord);
				}
			}else{
				array_push($newRecords, $r);
			}
		}

		return $newRecords;
	}
}
