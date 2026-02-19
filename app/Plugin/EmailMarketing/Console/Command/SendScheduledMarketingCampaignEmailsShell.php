<?php
/**
 * This shell should be added into cron job list
 *
 * Cron job: 0,15,30,45 * * * * cd /full/path/to/app && Console/cake EmailMarketing.send_scheduled_marketing_campaign_emails -u 1 (User ID 1 is a system admin user)
 */

App::uses ( 'EmailMarketingAppShell', 'EmailMarketing.Console/Command' );
class SendScheduledMarketingCampaignEmailsShell extends EmailMarketingAppShell {

	public $uses = array(
		'JobQueue'
	);

	public function initialize(){
		parent::initialize();
	}

	public function startup(){
		parent::startup();

		$jobCreatedTime = date('Y-m-d H:i:s');
		$type = $this->_getSystemDefaultConfigSetting('ParallelTaskType', Configure::read('Config.type.emailmarketing'));
		$userId = null;
		$excutionTime = date('Y-m-d H:i:00'); // Schedule time is set at every 15 mins time. And the cron job will run at the exact the same minute. So here we have no need to use the incorrect seconds value.
		$pendingEmailCount = $this->JobQueue->countPendingJobs($jobCreatedTime, $type, $userId, $excutionTime);

		if($pendingEmailCount > 0){

			if($this->JobQueue->prepareScheduledPendingJobsForExecution($jobCreatedTime, $type, $userId, $excutionTime)){

				// Trigger AMQ client
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

				$this->out(
					'<success>' .__('Scheduled marketing campaign emails were sent. (Excution time: ' .$excutionTime .')') .'</success>'
				);

			}else{

				$logType 	 = Configure::read('Config.type.emailmarketing');
				$logLevel 	 = Configure::read('System.log.level.critical');
				$logMessage  = __('Scheduled marketing campaign email cannot be sent. (Excution time: ' .$excutionTime .', User ID: ' .$userId .', Type: ' .$type .')');
				$this->Log->addLogRecord($logType, $logLevel, $logMessage, true);

				$this->out(
					'<error>' .__('Scheduled marketing campaign email cannot be sent. (Excution time: ' .$excutionTime .')') .'</error>'
				);

			}

		}

	}

}