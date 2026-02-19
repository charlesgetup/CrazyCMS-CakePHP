<?php
App::uses('PaymentAppController', 'Payment.Controller');

/**
 * PaymentDashboardController Test Case
 *
 */
require_once 'PaymentAppControllerTest.php';
class PaymentDashboardControllerTest extends PaymentAppControllerTest {

	public function setUp() {
		parent::setUp();

		$this->paymentDashboardController = $this->generate('Payment.PaymentDashboard', array(

		));
	}

	public function tearDown() {
		$this->userController->Auth->logout();
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
		'plugin.email_marketing.email_marketing_blacklisted_subscriber',
		'plugin.email_marketing.email_marketing_campaign',
		'plugin.email_marketing.email_marketing_campaign_list',
		'plugin.email_marketing.email_marketing_email_link',
		'plugin.email_marketing.email_marketing_mailing_list',
		'plugin.email_marketing.email_marketing_purchased_template',
		'plugin.email_marketing.email_marketing_sender',
		'plugin.email_marketing.email_marketing_statistic',
		'plugin.email_marketing.email_marketing_subscriber_click_record',
		'plugin.email_marketing.email_marketing_subscriber',
		'plugin.email_marketing.email_marketing_subscriber_open_record',
		'plugin.email_marketing.email_marketing_template',
		'app.address',
		'app.country',
		'app.configuration',
		'plugin.payment.payment_invoice',
		'plugin.payment.payment_pay_pal_gateway',
		'plugin.payment.payment_payer',
		'plugin.payment.payment_temp_invoice'
	);

/**
 * testAdminIndexVisitByClient method
 *
 * @return void
 */
	public function testAdminIndexVisitByAdmin() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$results = $this->testAction('/admin/payment/payment_dashboard/index', array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual($this->vars['totalInvoiceNumber'], 10);
		$this->assertEqual($this->vars['totalInvoiceAmount'], 990.00);
		$this->assertEqual($this->vars['totalPaidAmount'], '10.00');
		$this->assertEqual($this->vars['totalUnpaidAmount'], 980);
		$this->assertEqual($this->vars['thisMonthTotalInvoiceNumber'], 0);
		$this->assertEmpty($this->vars['thisMonthTotalInvoiceAmount']);
		$this->assertEmpty($this->vars['thisMonthTotalPaidAmount']);
		$this->assertEqual($this->vars['thisMonthTotalUnpaidAmount'], 0);
	}

/**
 * testAdminIndexVisitByClient method
 *
 * @return void
 */
	public function testAdminIndexVisitByClient() {
		// Login a client user
		$userModel = $this->loginUser(235);

		$results = $this->testAction('/admin/payment/payment_dashboard/index', array('return' => 'vars', 'method' => 'get'));

		$this->assertEmpty($this->vars['totalInvoiceNumber']);
		$this->assertEmpty($this->vars['totalInvoiceAmount']);
		$this->assertEmpty($this->vars['totalPaidAmount']);
		$this->assertEmpty($this->vars['totalUnpaidAmount']);
		$this->assertEmpty($this->vars['thisMonthTotalInvoiceNumber']);
		$this->assertEmpty($this->vars['thisMonthTotalInvoiceAmount']);
		$this->assertEmpty($this->vars['thisMonthTotalPaidAmount']);
		$this->assertEmpty($this->vars['thisMonthTotalUnpaidAmount']);
	}
}
?>