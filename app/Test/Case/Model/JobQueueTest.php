<?php
use Jeremeamia\SuperClosure\SerializableClosure;

App::uses('JobQueue', 'Model');

/**
 * JobQueue Test Case
 *
 */
class JobQueueTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.job_queue',
		'app.address',
		'app.user',
		'app.group',
		'app.log',
		'app.configuration',
		'app.country'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->JobQueue = ClassRegistry::init('JobQueue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->JobQueue);

		parent::tearDown();
	}

/**
 * testGetJob method
 *
 * @return void
 */
	public function testGetJob() {

		$job = $this->JobQueue->browseBy($this->JobQueue->primaryKey, 6);
		$this->assertEqual($job['JobQueue']['id'], '6');
		$this->assertEqual($job['JobQueue']['status'], 'PENDING');

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$type = 'EmailMarketing';
		$firstJobInThePendingList = $this->JobQueue->getJob($jobCreatedTime, $type);
		$this->assertEqual($firstJobInThePendingList['id'], "6");
		$this->assertEqual($firstJobInThePendingList['status'], "PROCESSING"); // Get the pending job and then directly process it
		$this->assertTrue(strtotime(date('Y-m-d 00:00:00', strtotime("+1 day"))) >= strtotime($firstJobInThePendingList['created'])); // Default select condition is that given time should be AFTER job created time

		$job = $this->JobQueue->browseBy($this->JobQueue->primaryKey, 7);
		$this->assertEqual($job['JobQueue']['id'], '7');
		$this->assertEqual($job['JobQueue']['status'], 'PENDING');

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("-1 day"));
		$type = 'EmailMarketing';
		$userId = 1;
		$timeStampDirection = "BEFORE";
		$firstJobInThePendingList = $this->JobQueue->getJob($jobCreatedTime, $type, $userId, null, $timeStampDirection);
		$this->assertEqual($firstJobInThePendingList['id'], "7");
		$this->assertEqual($firstJobInThePendingList['status'], "PROCESSING"); // Get the pending job and then directly process it
		$this->assertTrue(strtotime(date('Y-m-d 00:00:00', strtotime("-1 day"))) <= strtotime($firstJobInThePendingList['created'])); // We can change the date compare operator by passing $timeStampDirection variable.

	}

/**
 * testCountPendingJobs method
 *
 * @return void
 */
	public function testCountPendingJobs() {

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$type = 'EmailMarketing';
		$countAllPendingJobs = $this->JobQueue->countPendingJobs($jobCreatedTime, $type);
		$this->assertEqual($countAllPendingJobs, 4);

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$type = 'EmailMarketing';
		$userId = 203;
		$countOneUserPendingJobs = $this->JobQueue->countPendingJobs($jobCreatedTime, $type, $userId);
		$this->assertEqual($countOneUserPendingJobs, 1);

		$jobCreatedTime = date('Y-m-d 14:30:00');
		$type = 'EmailMarketing';
		$countPendingJobsBasedOnTime = $this->JobQueue->countPendingJobs($jobCreatedTime, $type);
		$this->assertEqual($countPendingJobsBasedOnTime, 1);

		$jobCreatedTime = date('Y-m-d 14:30:00');
		$type = 'EmailMarketing';
		$timeStampDirection = "BEFORE";
		$countPendingJobsInOtherDirection = $this->JobQueue->countPendingJobs($jobCreatedTime, $type, null, null, $timeStampDirection);
		$this->assertEqual($countPendingJobsInOtherDirection, 3);

	}

/**
 * testCountUndoneJobs method
 *
 * @return void
 */
	public function testCountUndoneJobs() {

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$type = 'EmailMarketing';
		$countAllUndoneJobs = $this->JobQueue->countUndoneJobs($jobCreatedTime, $type);
		$this->assertEqual($countAllUndoneJobs, 6);

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$type = 'EmailMarketing';
		$userId = 203;
		$countOneUserUndoneJobs = $this->JobQueue->countUndoneJobs($jobCreatedTime, $type, $userId);
		$this->assertEqual($countOneUserUndoneJobs, 2);

		$jobCreatedTime = date('Y-m-d 11:30:00');
		$type = 'EmailMarketing';
		$countUndoneJobsBasedOnTime = $this->JobQueue->countUndoneJobs($jobCreatedTime, $type);
		$this->assertEqual($countUndoneJobsBasedOnTime, 1);

		$jobCreatedTime = date('Y-m-d 11:30:00');
		$type = 'EmailMarketing';
		$timeStampDirection = "BEFORE";
		$countUndoneJobsInOtherDirection = $this->JobQueue->countUndoneJobs($jobCreatedTime, $type, null, null, $timeStampDirection);
		$this->assertEqual($countUndoneJobsInOtherDirection, 5);

	}

/**
 * testCountProcessedJobs method
 *
 * @return void
 */
	public function testCountProcessedJobs() {

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$type = 'EmailMarketing';
		$countAllFinishedJobs = $this->JobQueue->countProcessedJobs($jobCreatedTime, $type);
		$this->assertEqual($countAllFinishedJobs, 3);

		$jobCreatedTime = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$type = 'EmailMarketing';
		$userId = 203;
		$countOneUserFinishedJobs = $this->JobQueue->countProcessedJobs($jobCreatedTime, $type, $userId);
		$this->assertEqual($countOneUserFinishedJobs, 1);

		$jobCreatedTime = date('Y-m-d 10:00:00');
		$type = 'EmailMarketing';
		$countFinishedJobsBasedOnTime = $this->JobQueue->countProcessedJobs($jobCreatedTime, $type);
		$this->assertEqual($countFinishedJobsBasedOnTime, 2);

		$jobCreatedTime = date('Y-m-d 10:00:00');
		$type = 'EmailMarketing';
		$timeStampDirection = "BEFORE";
		$countFinishedJobsInOtherDirection = $this->JobQueue->countProcessedJobs($jobCreatedTime, $type, null, null, $timeStampDirection);
		$this->assertEqual($countFinishedJobsInOtherDirection, 1);

	}

/**
 * testSaveJob method
 *
 * @return void
 */
	public function testSaveJob() {

		$allJobs = $this->JobQueue->findAll('user_id', 1);
		$this->assertCount(6, $allJobs);

		$jobTaskFunc = new SerializableClosure( function () {
			echo 'Hello Word!';
		});

		$jobTaskFuncParams = array();

		$excutionTime = 'NOW';

		$job = array(
			'user_id'			=> 1,
			'type'				=> 'EmailMarketing',
			'function' 			=> serialize($jobTaskFunc),
			'function_params' 	=> serialize($jobTaskFuncParams),
			'status'			=> 'PENDING',
			'excution_time'		=> $excutionTime,
			'created'			=> date('Y-m-d H:i:s')
		);

		$this->JobQueue->saveJob($job);

		$allJobs = $this->JobQueue->findAll('user_id', 1);
		$this->assertCount(7, $allJobs);

	}

/**
 * testUpdateJob method
 *
 * @return void
 */
	public function testUpdateJob() {

		$job = $this->JobQueue->browseBy($this->JobQueue->primaryKey, 9);
		$this->assertEqual($job['JobQueue']['excution_time'], 'NOW');
		$date = date('Y-m-d 00:00:00', strtotime("+1 day"));
		$job['JobQueue']['excution_time'] = $date;

		$result = $this->JobQueue->updateJob(9, $job);
		$this->assertTrue($result);

		$job = $this->JobQueue->browseBy($this->JobQueue->primaryKey, 9);
		$this->assertEqual($job['JobQueue']['excution_time'], $date);
	}

}
