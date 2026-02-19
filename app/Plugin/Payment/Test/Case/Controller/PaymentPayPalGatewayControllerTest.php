<?php
App::uses('PaymentAppController', 'Payment.Controller');

/**
 * PaymentDashboardController Test Case
 *
 */
require_once 'PaymentAppControllerTest.php';
require_once APP_DIR .DS .'Plugin' .DS .'Payment' .DS .'Model' .DS .'PaymentInvoice.php';
require_once APP_DIR .DS .'Plugin' .DS .'Payment' .DS .'Model' .DS .'PaymentTempInvoice.php';
class PaymentPayPalGatewayControllerTest extends PaymentAppControllerTest {

	public function setUp() {
		parent::setUp();

		$this->paymentPayPalGatewayController = $this->generate('Payment.PaymentPayPalGateway', array(
			'methods' => array(
				'requestAction'
			),
		));

		$this->paymentPayPalGateway = ClassRegistry::init('Payment.PaymentPayPalGateway');

		$this->paymentInvoice = new PaymentInvoice(false, null, 'test');

		$this->paymentTempInvoice = new PaymentTempInvoice(false, null, 'test');

		// Use sandbox settings
		Configure::write('Payment.paypal.rest.api.app.mode', 			"sandbox");
		Configure::write('Payment.paypal.rest.api.app.client.id', 		"yours");
		Configure::write('Payment.paypal.rest.api.app.client.secret', 	"yours");

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
		'plugin.payment.payment_temp_invoice',
		'app.log',
		'app.jobqueue'
	);

/**
 * testAdminExpressCheckout method
 *
 * @return void
 */
	public function testAdminExpressCheckout() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$invoiceId = 4; // unpaid

		$result = $this->testAction('/admin/payment/payment_pay_pal_gateway/expressCheckout/' .$invoiceId, array('return' => 'vars', 'method' => 'get'));

		$this->assertNotEmpty($result['userInfo']);
		$this->assertEqual($result['userInfo']['email'], 'kerry-fly@163.com');
		$this->assertNotEmpty($result['billingAddress']);
		$this->assertEqual($result['billingAddress']['street_address'], '20 George ST');
		$this->assertNotEmpty($result['country']);
		$this->assertEqual($result['country']['Country']['code'], 'AU');
		$this->assertNotEmpty($result['paymentInfo']);
		$this->assertEqual($result['paymentInfo']['amount'], '100.00');
		$this->assertEqual($result['pendingInvoiceId'], 4);
		$this->assertFalse($result['isTempInvoice']);
	}

/**
 * testAdminExpressCheckoutPayRealInvoice method
 *
 * @return void
 */
	public function testAdminExpressCheckoutPayRealInvoice() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$invoiceId = 4; // unpaid

		$currentInvoice = $this->paymentInvoice->browseBy('id', $invoiceId);

		$this->assertEqual($currentInvoice['PaymentInvoice']['paid_amount'], '0.00');
		$this->assertEqual($currentInvoice['PaymentInvoice']['due_date'], '2014-07-31');
		$this->assertEqual($currentInvoice['PaymentInvoice']['status'], 'PENDING');
		$this->assertEqual($currentInvoice['PaymentInvoice']['modified'], '2014-08-03 13:26:44');

		/*
		 * Create payment
		 */

		$result = $this->testAction('/admin/payment/payment_pay_pal_gateway/createPayment/' .$invoiceId .'.json', array('return' => 'vars', 'method' => 'post'));

		$this->assertNotEmpty($result['payment']);

		$transaction = $this->paymentPayPalGateway->find('first', array(
			'conditions' => array(
				'payment_invoice_id' => $invoiceId
			),
			'order' => array('PaymentPayPalGateway.id DESC'),
			'recursive' => -1
		));
		$this->assertEqual($transaction['PaymentPayPalGateway']['is_temp'], 0);
		$this->assertEqual($transaction['PaymentPayPalGateway']['amount'], '100.00');
		$this->assertNotEmpty($transaction['PaymentPayPalGateway']['payment_id']);
		$this->assertEqual($transaction['PaymentPayPalGateway']['status'], 'created');
		$this->assertEqual($transaction['PaymentPayPalGateway']['intent'], 'sale');
		$this->assertEqual($transaction['PaymentPayPalGateway']['transaction_id'], $currentInvoice['PaymentInvoice']['number']);
		$this->assertContains(date('Y-m-d'), $transaction['PaymentPayPalGateway']['created']);
		$this->assertEmpty($transaction['PaymentPayPalGateway']['modified']);

		$createdPaymentId = $transaction['PaymentPayPalGateway']['payment_id'];

		/*
		 * Authorise payment
		 */

		//TODO cannot authrise payment, then cannot get payer ID and then cannot execute payment

		/*
		 * Execute payment
		 */

// 		$result = $this->testAction('/admin/payment/payment_pay_pal_gateway/executePayment/' .$invoiceId .'.json', array('return' => 'vars', 'method' => 'post'));

// 		$this->assertNotEmpty($result['payment']);

// 		$updatedInvoice = $this->paymentInvoice->browseBy('id', $invoiceId);
// 		$this->assertEqual($updatedInvoice['PaymentInvoice']['paid_amount'], '100.00');
// 		$this->assertEqual($updatedInvoice['PaymentInvoice']['status'], 'PAID');
// 		$this->assertContains(date('Y-m-d'), $updatedInvoice['PaymentInvoice']['modified']);

// 		$transaction = $this->paymentPayPalGateway->find('first', array(
// 			'conditions' => array(
// 				'payment_invoice_id' => $invoiceId
// 			),
// 			'order' => array('PaymentPayPalGateway.id DESC'),
// 			'recursive' => -1
// 		));
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['is_temp'], 0);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['amount'], '100.00');
// 		$this->assertNotEmpty($transaction['PaymentPayPalGateway']['payment_id']);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['payment_id'], $createdPaymentId);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['status'], 'approved');
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['intent'], 'sale');
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['transaction_id'], $updatedInvoice['PaymentInvoice']['number']);
// 		$this->assertContains(date('Y-m-d'), $transaction['PaymentPayPalGateway']['created']);
// 		$this->assertEmpty($transaction['PaymentPayPalGateway']['modified']);
	}

	/**
	 * testAdminExpressCheckoutPayRealPartialPaidInvoice method
	 *
	 * @return void
	 */
	//TODO This is not used for now
	public function testAdminExpressCheckoutPayRealPartialPaidInvoice() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$invoiceId = 1; // partial paid

		$currentInvoice = $this->paymentInvoice->browseBy('id', $invoiceId);

		$this->assertEqual($currentInvoice['PaymentInvoice']['paid_amount'], '10.00');
		$this->assertEqual($currentInvoice['PaymentInvoice']['status'], 'PARTIAL_PAID');
		$this->assertEqual($currentInvoice['PaymentInvoice']['modified'], '2014-07-30 21:50:23');

		/*
		 * Create payment
		 */

		$result = $this->testAction('/admin/payment/payment_pay_pal_gateway/createPayment/' .$invoiceId .'.json', array('return' => 'vars', 'method' => 'post'));

		$this->assertNotEmpty($result['payment']);

		$transaction = $this->paymentPayPalGateway->find('first', array(
			'conditions' => array(
				'payment_invoice_id' => $invoiceId
			),
			'order' => array('PaymentPayPalGateway.id DESC'),
			'recursive' => -1
		));
		$this->assertEqual($transaction['PaymentPayPalGateway']['is_temp'], 0);
		$this->assertEqual($transaction['PaymentPayPalGateway']['amount'], '90.00');
		$this->assertNotEmpty($transaction['PaymentPayPalGateway']['payment_id']);
		$this->assertEqual($transaction['PaymentPayPalGateway']['status'], 'created');
		$this->assertEqual($transaction['PaymentPayPalGateway']['intent'], 'sale');
		$this->assertEqual($transaction['PaymentPayPalGateway']['transaction_id'], $currentInvoice['PaymentInvoice']['number']);
		$this->assertContains(date('Y-m-d'), $transaction['PaymentPayPalGateway']['created']);
		$this->assertEmpty($transaction['PaymentPayPalGateway']['modified']);

		$createdPaymentId = $transaction['PaymentPayPalGateway']['payment_id'];

		/*
		 * Execute payment
		 */

// 		$result = $this->testAction('/admin/payment/payment_pay_pal_gateway/executePayment/' .$invoiceId .'.json', array('return' => 'vars', 'method' => 'post'));

// 		$this->assertNotEmpty($result['payment']);

// 		$updatedInvoice = $this->paymentInvoice->browseBy('id', $invoiceId);
// 		$this->assertEqual($updatedInvoice['PaymentInvoice']['paid_amount'], '100.00');
// 		$this->assertEqual($updatedInvoice['PaymentInvoice']['status'], 'PAID');
// 		$this->assertContains(date('Y-m-d'), $updatedInvoice['PaymentInvoice']['modified']);

// 		$transaction = $this->paymentPayPalGateway->find('first', array(
// 			'conditions' => array(
// 				'payment_invoice_id' => $invoiceId
// 			),
// 			'order' => array('PaymentPayPalGateway.id DESC'),
// 			'recursive' => -1
// 		));
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['is_temp'], 0);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['amount'], '90.00');
// 		$this->assertNotEmpty($transaction['PaymentPayPalGateway']['payment_id']);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['payment_id'], $createdPaymentId);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['status'], 'approved');
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['intent'], 'sale');
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['transaction_id'], $updatedInvoice['PaymentInvoice']['number']);
// 		$this->assertContains(date('Y-m-d'), $transaction['PaymentPayPalGateway']['created']);
// 		$this->assertEmpty($transaction['PaymentPayPalGateway']['modified']);
	}

	/**
	 * testAdminExpressCheckoutPayTempInvoice method
	 *
	 * @return void
	 */
	public function testAdminExpressCheckoutPayTempInvoice() {
		// Login an admin user
		$userId = 132;
		$userModel = $this->loginUser($userId);

		$invoiceId = 1; // temp invoice
		$isTempInvoice = 1; // This means TRUE

		$currentInvoice = $this->paymentTempInvoice->browseBy('id', $invoiceId);

		$this->assertEqual($currentInvoice['PaymentTempInvoice']['amount'], '950.00');
		$this->assertNotEmpty($currentInvoice['PaymentTempInvoice']['related_update_data']);

		$User = ClassRegistry::init('User');
		$user = $User->find('first', array(
			'fields' 		=> array('id'),
			'conditions' 	=> array(
				'parent_id' => $userId,
				'group_id' 	=> Configure::read('EmailMarketing.client.group.id')
			),
			'recursive' => -1
		));
		$EmailMarketingUser = ClassRegistry::init('EmailMarketingUser');
		$emailMarketingUser = $EmailMarketingUser->find('first', array(
			'conditions' => array(
				'user_id' => $user['User']['id']
			),
		));
		$this->assertEmpty($emailMarketingUser['EmailMarketingUser']['last_pay_date']);
		$this->assertEmpty($emailMarketingUser['EmailMarketingUser']['next_pay_date']);
		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'], '195');

		/*
		 * Create payment
		 */

		$result = $this->testAction('/admin/payment/payment_pay_pal_gateway/createPayment/' .$invoiceId .'/' .$isTempInvoice .'.json', array('return' => 'vars', 'method' => 'post'));

		$this->assertNotEmpty($result['payment']);

		$transaction = $this->paymentPayPalGateway->find('first', array(
			'conditions' => array(
				'payment_invoice_id' => $invoiceId
			),
			'order' => array('PaymentPayPalGateway.id DESC'),
			'recursive' => -1
		));
		$this->assertEqual($transaction['PaymentPayPalGateway']['is_temp'], 1);
		$this->assertEqual($transaction['PaymentPayPalGateway']['amount'], '950.00');
		$this->assertNotEmpty($transaction['PaymentPayPalGateway']['payment_id']);
		$this->assertEqual($transaction['PaymentPayPalGateway']['status'], 'created');
		$this->assertEqual($transaction['PaymentPayPalGateway']['intent'], 'sale');
		$this->assertEmpty($transaction['PaymentPayPalGateway']['transaction_id']);
		$this->assertContains(date('Y-m-d'), $transaction['PaymentPayPalGateway']['created']);
		$this->assertEmpty($transaction['PaymentPayPalGateway']['modified']);

		$createdPaymentId = $transaction['PaymentPayPalGateway']['payment_id'];

		/*
		 * Execute payment
		 */

// 		$result = $this->testAction('/admin/payment/payment_pay_pal_gateway/executePayment/' .$invoiceId .'/' .$isTempInvoice .'.json', array('return' => 'vars', 'method' => 'post'));

// 		$this->assertNotEmpty($result['payment']);

// 		$updatedInvoice = $this->paymentInvoice->browseBy('id', $invoiceId);
// 		$this->assertEqual($updatedInvoice['PaymentInvoice']['paid_amount'], '950.00');
// 		$this->assertEqual($updatedInvoice['PaymentInvoice']['status'], 'PAID');
// 		$this->assertContains(date('Y-m-d'), $updatedInvoice['PaymentInvoice']['modified']);

// 		$transaction = $this->paymentPayPalGateway->find('first', array(
// 			'conditions' => array(
// 				'payment_invoice_id' => $invoiceId
// 			),
// 			'order' => array('PaymentPayPalGateway.id DESC'),
// 			'recursive' => -1
// 		));
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['is_temp'], 0);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['amount'], '950.00');
// 		$this->assertNotEmpty($transaction['PaymentPayPalGateway']['payment_id']);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['payment_id'], $createdPaymentId);
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['status'], 'approved');
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['intent'], 'sale');
// 		$this->assertEqual($transaction['PaymentPayPalGateway']['transaction_id'], $updatedInvoice['PaymentInvoice']['number']);
// 		$this->assertContains(date('Y-m-d'), $transaction['PaymentPayPalGateway']['created']);
// 		$this->assertEmpty($transaction['PaymentPayPalGateway']['modified']);

// 		$emailMarketingUser = $EmailMarketingUser->find('first', array(
// 			'conditions' => array(
// 				'user_id' => $user['User']['id']
// 			),
// 			'recursive' => -1
// 		));
// 		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['last_pay_date'], '2014-08-25');
// 		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['next_pay_date'], '2014-09-25');
// 		$this->assertEqual($emailMarketingUser['EmailMarketingUser']['total_sent_email_amount'], '0');

		//TODO test soft delete temp invoice and while doing this, we need to keep a reference between real invoice and temp invoice

	}
}
?>