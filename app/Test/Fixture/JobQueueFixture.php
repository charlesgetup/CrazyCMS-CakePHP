<?php
/**
 * JobQueueFixture
 *
 */
class JobQueueFixture extends CakeTestFixture {

	public $table = "job_queue";

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index', 'comment' => 'User ID'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'function' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'function_params' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'status' => array('type' => 'string', 'null' => false, 'default' => 'PENDING', 'length' => 20, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'PENDING, PROCESSING, DONE, FAILED', 'charset' => 'utf8'),
		'excution_time' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'Now or 2014-02-05 20:00:00', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'finished' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'type' => array('column' => 'type', 'unique' => 0),
			'excution_time' => array('column' => 'excution_time', 'unique' => 0),
			'done' => array('column' => 'status', 'unique' => 0),
			'finished' => array('column' => 'finished', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	public function init(){
		$this->records = array(
			array(
				'id' => '1',
				'user_id' => '1',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:2:"53";}',
				'status' => 'DONE',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 9:52:47',
				'finished' => date('Y-m-d') .' 9:53:08'
			),
			array(
				'id' => '2',
				'user_id' => '1',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:2:"53";}',
				'status' => 'DONE',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 9:52:47',
				'finished' => date('Y-m-d') .' 9:53:08'
			),
			array(
				'id' => '3',
				'user_id' => '203',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"3";i:2;s:2:"53";}',
				'status' => 'DONE',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 10:52:47',
				'finished' => date('Y-m-d') .' 10:53:08'
			),
			array(
				'id' => '4',
				'user_id' => '1',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"4";i:2;s:2:"53";}',
				'status' => 'PROCESSING',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 11:02:47',
				'finished' => null
			),
			array(
				'id' => '5',
				'user_id' => '203',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"5";i:2;s:2:"53";}',
				'status' => 'PROCESSING',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 12:52:47',
				'finished' => null
			),
			array(
				'id' => '6',
				'user_id' => '1',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"1";i:2;s:2:"54";}',
				'status' => 'PENDING',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 15:16:55',
				'finished' => null
			),
			array(
				'id' => '7',
				'user_id' => '1',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:2:"54";}',
				'status' => 'PENDING',
				'excution_time' => 'PENDING',
				'created' => date('Y-m-d') .' 15:16:55',
				'finished' => null
			),
			array(
				'id' => '8',
				'user_id' => '1',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"3";i:2;s:2:"54";}',
				'status' => 'PENDING',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 15:16:55',
				'finished' => null
			),
			array(
				'id' => '9',
				'user_id' => '203',
				'type' => 'EmailMarketing',
				'function' => 'C:43:"Jeremeamia\\SuperClosure\\SerializableClosure":285:{a:2:{i:0;s:256:"function ($campaignId, $subscriberId, $statisticId) {
	    $command = APP . \'Console\' . DS . "cake EmailMarketing.send_campaign_email CAMPAIGN -c {$campaignId} -s {$subscriberId} -t {$statisticId}";
	    $response = system($command);
	    return $response;
	};";i:1;a:0:{}}}',
				'function_params' => 'a:3:{i:0;s:1:"1";i:1;s:1:"4";i:2;s:2:"54";}',
				'status' => 'PENDING',
				'excution_time' => 'NOW',
				'created' => date('Y-m-d') .' 14:16:55',
				'finished' => null
			),
		);
		parent::init();
	}

}
