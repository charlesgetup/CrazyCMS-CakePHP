<?php
App::uses('DashboardController', 'Controller');

/**
 * DashboardController Test Case
 *
 */
require_once 'AppControllerTest.php';
class DashboardControllerTest extends AppControllerTest {

	public function setUp() {
		parent::setUp();
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
		'app.configuration'
	);

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->markTestSkipped('Should test this action in the view');
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->markTestSkipped('Should test this action in the view');
	}

}
