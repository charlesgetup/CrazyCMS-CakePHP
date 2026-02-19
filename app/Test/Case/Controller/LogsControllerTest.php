<?php
App::uses('LogsController', 'Controller');

/**
 * LogsController Test Case
 *
 */
require_once 'AppControllerTest.php';
class LogsControllerTest extends AppControllerTest {

	protected $logsController;

	public function setUp() {
		parent::setUp();

		$this->logsController = $this->generate('Logs', array(

		));

		$this->loginUser();
	}

	public function tearDown() {
		parent::tearDown();
	}

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.group',
		'app.aco',
		'app.aro',
		'app.arosaco',
		'plugin.email_marketing.email_marketing_user',
		'plugin.email_marketing.email_marketing_plan',
		'app.address',
		'app.country',
		'app.configuration',
		'app.log'
	);

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->testAction('/admin/logs', array('return' => 'contents'));
		$this->assertContains('User Log', $this->contents);
		$this->assertContains('Email Marketing Log', $this->contents);
		$this->assertContains('Payment Log', $this->contents);
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminViewAllLogsByType() {
		$this->testAction('/admin/logs/view/EMAIL_MARKETING', array('return' => 'vars'));
		$this->assertEqual(count($this->vars['response']['aaData']), 2);
	}

	public function testAdminViewAllLogsByTypeBetweenDateRange() {
		$this->testAction('/admin/logs/view/EMAIL_MARKETING/2014-01-01/2014-01-15', array('return' => 'vars'));
		$this->assertEqual(count($this->vars['response']['aaData']), 1);
	}
}
