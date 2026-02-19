<?php
App::uses('PaymentAppController', 'Payment.Controller');

/**
 * PaymentInvoicesController Test Case
 *
 */
require_once 'PaymentAppControllerTest.php';
require_once APP_DIR .DS .'Plugin' .DS .'Payment' .DS .'Model' .DS .'PaymentInvoice.php';
class PaymentInvoicesControllerTest extends PaymentAppControllerTest {

	public function setUp() {
		parent::setUp();

		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(

		));

		$this->PaymentInvoice = new PaymentInvoice(false, null, 'test');
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
 * testAdminIndexAccessWithWrongInvoiceType method
 *
 * @return void
 */
	public function testAdminIndexAccessWithWrongInvoiceType() {
		$this->expectException('NotFoundException');

		$unpaidInvoiceType = Configure::read('Payment.invoice.status.unpaid');
		$paidInvoiceType = Configure::read('Payment.invoice.status.paid');

		$wrongInvoiceType = "WRONGTYPE";
		$this->assertFalse(in_array($wrongInvoiceType, [$unpaidInvoiceType, $paidInvoiceType]));

		// Login an admin user
		$userModel = $this->loginUser(132);

		$this->testAction('/admin/payment/payment_invoices/index/' .$wrongInvoiceType);
	}

/**
 * testAdminIndexAccessUnpaidInvoices method
 *
 * @return void
 */
	public function testAdminIndexAccessUnpaidInvoices() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$unpaidInvoiceType = Configure::read('Payment.invoice.status.unpaid');

		$this->testAction('/admin/payment/payment_invoices/index/' .$unpaidInvoiceType, array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual(count($this->vars['response']['aaData']), 8);
	}

/**
 * testAdminIndexAccessPaidInvoices method
 *
 * @return void
 */
	public function testAdminIndexAccessPaidInvoices() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$paidInvoiceType = Configure::read('Payment.invoice.status.paid');

		$this->testAction('/admin/payment/payment_invoices/index/' .$paidInvoiceType, array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual(count($this->vars['response']['aaData']), 0);
	}

/**
 * testAdminIndexAccessUnpaidInvoicesByClient method
 *
 * Client can only see his own invoices
 *
 * @return void
 */
	public function testAdminIndexAccessUnpaidInvoicesByClient() {
		// Login a client user
		$userModel = $this->loginUser(235);

		$unpaidInvoiceType = Configure::read('Payment.invoice.status.unpaid');

		$this->testAction('/admin/payment/payment_invoices/index/' .$unpaidInvoiceType, array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual(count($this->vars['response']['aaData']), 1);
	}

/**
 * testAdminIndexAccessUnpaidInvoicesByClient method
 *
 * @see testAdminIndexAccessUnpaidInvoices()
 *
 * @return void
 */
	public function testAdminUnpaidInvoiceIndex() {
		$this->markTestSkipped('This function only creates an URL for front end use, it doesn\'t contain any logic');
	}

/**
 * testAdminPaidInvoiceIndex method
 *
 * @see testAdminIndexAccessPaidInvoices()
 *
 * @return void
 */
	public function testAdminPaidInvoiceIndex() {
		$this->markTestSkipped('This function only creates an URL for front end use, it doesn\'t contain any logic');
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		// Login an admin user
		$userModel = $this->loginUser(132); // This user is also the invoice owner

		$invoiceId = 1;
		$this->testAction('/admin/payment/payment_invoices/view/' .$invoiceId, array('return' => 'vars', 'method' => 'get'));

		$this->assertEqual($this->vars['invoice']['PaymentInvoice']['id'], $invoiceId);
	}

/**
 * testAdminViewClientCannotSeeOtherPersonsInvoice method
 *
 * @return void
 */
	public function testAdminViewClientCannotSeeOtherPersonsInvoice() {
		$this->expectException('NotFoundException');

		// Login a client user
		$userModel = $this->loginUser(235);

		$invoiceId = 1;
		$this->testAction('/admin/payment/payment_invoices/view/' .$invoiceId);
	}

/**
 * testAdminAddCanBeDoneByAdmin method
 *
 * @return void
 */
	public function testAdminAddCanBeDoneByAdmin() {
		// Login an admin user
		$userModel = $this->loginUser(1);

		$this->testAction('/admin/payment/payment_invoices/add/', array('return' => 'view', 'method' => 'get'));
		$this->assertContains('id="PaymentInvoiceAdminAddForm" method="post"', $this->view);
	}

/**
 * testAdminAddCanNotBeDoneByClient method
 *
 * @return void
 */
	public function testAdminAddCanNotBeDoneByClient() {
		$this->expectException('UnauthorizedException');

		// Login a client user
		$userModel = $this->loginUser(235);

		$this->testAction('/admin/payment/payment_invoices/add/', array('return' => 'view', 'method' => 'get'));
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		// Login an admin user
		$userModel = $this->loginUser(1);

		$invoiceNumber = $this->PaymentInvoice->generateInvoiceNumber();

		$data = array(
			'PaymentInvoice' => array(
				'is_auto_created' => 0,
				'number' => $invoiceNumber,
				'status' => Configure::read('Payment.invoice.status.pending'),
				'created' => date('Y-m-d H:i:s', strtotime('now')),
				'modified' => date('Y-m-d H:i:s', strtotime('now')),
				'user_id' => 132,
				'amount' => 12.12,
				'paid_amount' => 0,
				'due_date' => array('day' => '01', 'month' => '01', 'year' => '2018'),
				'content' => '<h1>This is a test invoice</h1>',
			)
		);

		// Mock method in component and model
		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(
			'models' => array(
				'PaymentInvoice' => array('outputFile')
			),
			'components' => array(
				'Mpdf'
			)
		));

		// Because the entired component is mocked, there is no need to use the following code
// 		$this->paymentInvoicesController->Mpdf = $this->getMock('MpdfComponent', array('__getFileContent'), array(), '', FALSE);
// 		$this->paymentInvoicesController->Mpdf->expects($this->any())->method('__getFileContent')->with($this->anything())->will($this->returnValue(true));

		// Because the model has been declared as mocked above, there is no need to get mock object again
// 		$this->PaymentInvoice = $this->getMock('PaymentInvoice', array('__outputFile'));

		// Because we only declared that we want to mock "outputFile" method in model PaymentInvoice, we will use the following code to define the mocked method return value
		$this->paymentInvoicesController->PaymentInvoice->expects($this->any())->method('outputFile')->will($this->returnValue(true));

		$this->testAction('/admin/payment/payment_invoices/add/', array('data' => $data, 'method' => 'post'));

		$this->assertEquals("Payment Invoice has been added.", $this->paymentInvoicesController->Session->read('Message.flash.message'));

		$newInvoice = $this->PaymentInvoice->browseBy ( 'number', $invoiceNumber, false);
		$this->assertNotEmpty($newInvoice['PaymentInvoice']['id']);
	}

/**
 * testAdminEditCanBeDoneByAdmin method
 *
 * @return void
 */
	public function testAdminEditCanBeDoneByAdmin() {
		// Login an admin user
		$userModel = $this->loginUser(1);

		$this->testAction('/admin/payment/payment_invoices/edit/1', array('return' => 'view', 'method' => 'get'));
		$this->assertContains('id="PaymentInvoiceAdminEditForm" method="post"', $this->view);
	}

/**
 * testAdminEditCanNotBeDoneByClient method
 *
 * @return void
 */
	public function testAdminEditCanNotBeDoneByClient() {
		$this->expectException('UnauthorizedException');

		// Login a client user
		$userModel = $this->loginUser(235);

		$this->testAction('/admin/payment/payment_invoices/edit/1', array('return' => 'view', 'method' => 'get'));
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminEdit() {
		// Login an admin user
		$userModel = $this->loginUser(1);

		$invoiceId = 1;

		$currentInvoice = $this->PaymentInvoice->browseBy ( 'id', $invoiceId, false);

		$now = date('Y-m-d H:i:s', strtotime('now'));

		$this->assertFalse($currentInvoice['PaymentInvoice']['created'] == $now);
		$this->assertFalse($currentInvoice['PaymentInvoice']['modified'] == $now);

		$data = array(
			'PaymentInvoice' => array(
				'id' => $invoiceId,
				'is_auto_created' => 0,
				'number' => $currentInvoice['PaymentInvoice']['number'],
				'status' => Configure::read('Payment.invoice.status.pending'),
				'created' => $now,
				'modified' => $now,
				'user_id' => $currentInvoice['PaymentInvoice']['user_id'],
				'created_by' => $currentInvoice['PaymentInvoice']['created_by'],
				'amount' => 12.12,
				'paid_amount' => 10,
				'due_date' => array('day' => '01', 'month' => '01', 'year' => '2018'),
				'content' => '<h1>This is a test invoice</h1>',
			)
		);

		// Mock method in component and model
		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(
			'methods' => array(
				'requestAction'
			),
			'models' => array(
				'PaymentInvoice' => array('outputFile')
			),
			'components' => array(
				'Mpdf'
			)
		));

		// Because we only declared that we want to mock "outputFile" method in model PaymentInvoice, we will use the following code to define the mocked method return value
		$this->paymentInvoicesController->PaymentInvoice->expects($this->any())->method('outputFile')->will($this->returnValue(true));

		$this->testAction('/admin/payment/payment_invoices/edit/' .$invoiceId, array('data' => $data, 'method' => 'post'));

		$this->assertEquals("Payment Invoice has been updated.", $this->paymentInvoicesController->Session->read('Message.flash.message'));

		$newInvoice = $this->PaymentInvoice->browseBy ( 'id', $invoiceId, false);
		$this->assertNotEmpty($newInvoice['PaymentInvoice']['id']);
		$this->assertNotEmpty($newInvoice['PaymentInvoice']['status']);
		$this->assertNotEmpty($newInvoice['PaymentInvoice']['created']);
		$this->assertNotEmpty($newInvoice['PaymentInvoice']['modified']);
		$this->assertEqual($newInvoice['PaymentInvoice']['is_auto_created'], 0);
		$this->assertEqual($newInvoice['PaymentInvoice']['status'], Configure::read('Payment.invoice.status.partial_paid'));

		$this->assertTrue($newInvoice['PaymentInvoice']['created'] == $now);
		$this->assertTrue($newInvoice['PaymentInvoice']['modified'] == $now);
	}

/**
 * testAdminEmail method
 *
 * @return void
 */
	public function testAdminEmailWithoutInvoiceFile() {
		// Login a client user
		$userModel = $this->loginUser(235);

		$invoiceId = 67;

		$Configuration = ClassRegistry::init('Configuration');
		$companyName    = $Configuration->findConfiguration('CompanyName', Configure::read('Config.type.system'));

		$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
		$invoiceDir = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number']);
		if(file_exists($invoiceDir)){
			$invoiceFile = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);
			if(file_exists($invoiceFile)){
				unlink($invoiceFile);
			}
		}

		// Mock method in component and model
		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(
			'methods' => array(
				'requestAction'
			),
		));
		$this->paymentInvoicesController->expects($this->any())->method('requestAction')->will($this->returnValue(true));

		$this->testAction('/admin/payment/payment_invoices/email/' .$invoiceId, array('data' => array('id' => $invoiceId), 'method' => 'post'));

		$this->assertEquals(__('Sorry! Invoice file cannot be found, please contact ' .$companyName .' for more details.'), $this->paymentInvoicesController->Session->read('Message.flash.message'));
	}

/**
 * testAdminEmail method
 *
 * @return void
 */
	public function testAdminEmail() {
		// Login a client user
		$userModel = $this->loginUser(235);

		$invoiceId = 67;

		$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
		$this->assertEqual($invoice['PaymentInvoice']['is_emailed_client'], 0);

		$invoiceDir = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number']);
		if(!file_exists($invoiceDir)){
			system('mkdir -p ' .$invoiceDir);
		}
		$invoiceFile = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);
		touch($invoiceFile);

		// Mock method in component and model
		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(
			'methods' => array(
				'requestAction'
			),
		));
		$this->paymentInvoicesController->expects($this->any())->method('requestAction')->will($this->returnValue(true));

		$this->testAction('/admin/payment/payment_invoices/email/' .$invoiceId, array('data' => array('id' => $invoiceId), 'method' => 'post'));

		$this->assertEquals(__('The invoice has been sent to your security email address as an attachment.'), $this->paymentInvoicesController->Session->read('Message.flash.message'));

		$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
		$this->assertEqual($invoice['PaymentInvoice']['is_emailed_client'], 1);

		unlink($invoiceFile);
	}

/**
 * testAdminEmailCannotEmailOtherClientInvoice method
 *
 * @return void
 */
	public function testAdminEmailCannotEmailOtherClientInvoice() {
		$this->expectException('NotFoundException');

		// Login a client user
		$userModel = $this->loginUser(235);

		$invoiceId = 66;

		$this->testAction('/admin/payment/payment_invoices/email/' .$invoiceId, array('data' => array('id' => $invoiceId), 'method' => 'post'));
	}

/**
 * testAdminBatchEmailByClientUser method
 *
 * @return void
 */
	public function testAdminBatchEmailByClientUser() {
		$this->expectException('UnauthorizedException');

		// Login a client user
		$userModel = $this->loginUser(235);

		$invoiceIds = [65,66,67];

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction('/admin/payment/payment_invoices/batchEmail/', array('data' => array('batchIds' => $invoiceIds), 'method' => 'post'));
	}

/**
 * testAdminBatchEmail method
 *
 * @return void
 */
	public function testAdminBatchEmail() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$invoiceIds = [65,66,67];

		foreach($invoiceIds as $invoiceId){
			$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
			$this->assertEqual($invoice['PaymentInvoice']['is_emailed_client'], 0);

			$invoiceDir = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number']);
			if(!file_exists($invoiceDir)){
				system('mkdir -p ' .$invoiceDir);
			}
			$invoiceFile = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);
			touch($invoiceFile);
		}

		// Mock method in component and model
		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(
			'methods' => array(
				'requestAction'
			),
		));
		$this->paymentInvoicesController->expects($this->any())->method('requestAction')->will($this->returnValue(true));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction('/admin/payment/payment_invoices/batchEmail/', array('data' => array('batchIds' => $invoiceIds), 'method' => 'post'));

		$this->assertEquals(__('Selected invoices have been batch emailed.'), $this->paymentInvoicesController->Session->read('Message.flash.message'));

		foreach($invoiceIds as $invoiceId){
			$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
			$this->assertEqual($invoice['PaymentInvoice']['is_emailed_client'], 1);
			@unlink($invoiceFile);
		}
	}

/**
 * testAdminBatchEmailWithWrongInvoiceId method
 *
 * @return void
 */
	public function testAdminBatchEmailWithWrongInvoiceId() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$invoiceIds = [65,66,67,9999];

		foreach($invoiceIds as $invoiceId){
			if($invoiceId == 9999){
				continue;
			}

			$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
			$this->assertEqual($invoice['PaymentInvoice']['is_emailed_client'], 0);

			$invoiceDir = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number']);
			if(!file_exists($invoiceDir)){
				system('mkdir -p ' .$invoiceDir);
			}
			$invoiceFile = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);
			touch($invoiceFile);
		}

		// Mock method in component and model
		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(
			'methods' => array(
				'requestAction'
			),
		));
		$this->paymentInvoicesController->expects($this->any())->method('requestAction')->will($this->returnValue(true));

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction('/admin/payment/payment_invoices/batchEmail/', array('data' => array('batchIds' => $invoiceIds), 'method' => 'post'));

		$this->assertEquals(__('Invoice (#9999) cannot be found.'), $this->paymentInvoicesController->Session->read('Message.flash.message'));

		foreach($invoiceIds as $invoiceId){
			if($invoiceId == 9999){
				continue;
			}
			$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
			$this->assertEqual($invoice['PaymentInvoice']['is_emailed_client'], 1);
			@unlink($invoiceFile);
		}
	}

/**
 * testAdminGenerateInvoiceFileByClientUser method
 *
 * @return void
 */
	public function testAdminGenerateInvoiceFileByClientUser() {
		$this->expectException('UnauthorizedException');

		// Login a client user
		$userModel = $this->loginUser(235);

		$invoiceId = 67;

		$this->testAction('/admin/payment/payment_invoices/generateInvoiceFile/' .$invoiceId);
	}

	/**
	 * testAdminGenerateInvoiceFile method
	 *
	 * @return void
	 */
	public function testAdminGenerateInvoiceFile() {
		// Login an admin user
		$userModel = $this->loginUser(132);

		$invoiceId = 66;

		$invoice = $this->PaymentInvoice->browseBy($this->PaymentInvoice->primaryKey, $invoiceId, false);
		$this->assertEqual($invoice['PaymentInvoice']['is_emailed_client'], 0);

		$invoiceDir = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number']);
		if(!file_exists($invoiceDir)){
			system('mkdir -p ' .$invoiceDir);
		}
		$invoiceFile = $this->PaymentInvoice->getInvoiceSavedPath($invoice['PaymentInvoice']['user_id'], $invoice['PaymentInvoice']['number'], false, true);
		if(file_exists($invoiceFile)){
			unlink($invoiceFile);
		}

		// Mock method in component and model
		$this->paymentInvoicesController = $this->generate('Payment.PaymentInvoices', array(
			'methods' => array('_prepareNoViewAction'),
			'models' => array(
				'PaymentInvoice' => array('outputFile')
			),
			'components' => array(
				'RequestHandler'
			),
		));
		function mockFilePutContents($fileName, $fileContent){
			file_put_contents($fileName, $fileContent);
			return true;
		}
// 		$this->paymentInvoicesController->PaymentInvoice->expects($this->any())->method('outputFile')->will($this->returnCallback('mockFilePutContents'));

		Router::parseExtensions();

		Configure::write('debug',0);
		$this->paymentInvoicesController->autoRender = false;
		$this->paymentInvoicesController->disableCache();
		$this->paymentInvoicesController->plugin = "Payment"; // This fixed the missing template issue

		$this->paymentInvoicesController->admin_generateInvoiceFile($invoiceId);

		$this->assertFalse(file_exists($invoiceFile));
		if(file_exists($invoiceFile)){
			unlink($invoiceFile);
		}
	}
}
?>