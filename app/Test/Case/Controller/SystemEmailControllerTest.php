<?php
App::uses('SystemEmailController', 'Controller');

/**
 * SystemEmailController Test Case
 *
 */
require_once 'AppControllerTest.php';
class SystemEmailControllerTest extends AppControllerTest {

	protected $systemEmailController;

	public function setUp() {
		parent::setUp();

		$this->systemEmailController = $this->generate('SystemEmail', array(

		));
		$this->systemEmailController->Auth->allow('sendContactEmail');
		$this->systemEmailController->Auth->allow('sendNewUserActivateEmail');
		$this->systemEmailController->Auth->allow('sendResetPasswordEmail');
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
		'app.log',
		'app.aco',
		'app.aro',
		'app.arosaco',
		'plugin.email_marketing.email_marketing_user',
		'plugin.email_marketing.email_marketing_plan',
		'app.address',
		'app.country',
		'app.configuration',
		'app.jobqueue'
	);

/**
 * testSendContactEmail method
 *
 * @return void
 */
	public function testSendContactEmail() {
		//TODO below commented code is for view test. maybe move them to the view test later
// 		$pagesController = $this->generate('Pages', array(

// 		));

// 		$this->testAction('/pages/contacts', array('return' => 'contents'));
// 		$this->assertContains("forms.js", $this->contents);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/system_email/sendContactEmail',
				array(
					'data' => array(
						'author' => 'Test User',
						'email' => 'test@user.com',
						'phone' => '0412121212',
						'text' => 'This is a test message',
					),
					'method' => 'post'
				)
		);
		$this->expectOutputString('Email has been sent successfully.');
	}

	public function testSendContactEmailNotFillRequiredFieldError() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/system_email/sendContactEmail',
				array(
					'data' => array(
						'author' => 'Test User',
						'email' => 'test@user.com',
						'phone' => '0412121212',
					),
					'method' => 'post'
				)
		);
		$this->expectOutputString('Please fill in the message field');
	}

/**
 * testSendNewUserActivateEmail method
 *
 * @return void
 */
	public function testSendNewUserActivateEmail() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction(
			'/system_email/sendNewUserActivateEmail/216',
			array(
				'method' => 'post'
			)
		);

		$this->assertEqual($result['status'], 'SUCCESS');
		$this->assertEqual($result['message'], 'Activation email has been sent successfully.');
	}

/**
 * testSendResetPasswordEmail method
 *
 * @return void
 */
	public function testSendResetPasswordEmail() {
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = $this->testAction(
				'/system_email/sendResetPasswordEmail/216',
				array(
					'method' => 'post'
				)
		);

		$this->assertEqual($result, true);
	}

/**
 * testSendInvoiceEmail method
 *
 * @return void
 */
	public function testSendInvoiceEmail() {
		$this->markTestSkipped('Should test this action when test payment plugin');
	}

/**
 * testSendReceiptEmail method
 *
 * @return void
 */
	public function testSendReceiptEmail() {
		$this->markTestSkipped('Should test this action when test EmailMarketing plugin');
	}

}
