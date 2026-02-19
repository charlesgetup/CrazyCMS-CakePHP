<?php
App::uses('ConfigurationsController', 'Controller');

/**
 * ConfigurationsController Test Case
 *
 */
require_once 'AppControllerTest.php';
class ConfigurationsControllerTest extends AppControllerTest {

	protected $configurationsController;

	public function setUp() {
		parent::setUp();

		$this->configurationsController = $this->generate('Configurations', array(

		));
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
	public function testAdminIndexAdminUserToSeeAllConfigurations() {
		$this->loginUser();
		$this->testAction('/admin/configurations', array('return' => 'contents'));
		$this->assertContains('System Configurations', $this->contents);
		$this->assertContains('Email Marketing Configurations', $this->contents);
		$this->assertContains('Payment Configurations', $this->contents);
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->loginUser();

		$this->testAction('/admin/configurations/view/EMAIL_MARKETING', array('return' => 'vars'));
		$this->assertEqual(count($this->vars['response']['aaData']), 10); //first page display 10 records

		$this->testAction('/admin/configurations/view/SYSTEM', array('return' => 'vars'));
		$this->assertEqual(count($this->vars['response']['aaData']), 10); //first page display 10 records

		$this->testAction('/admin/configurations/view/PAYMENT', array('return' => 'vars'));
		$this->assertEqual(count($this->vars['response']['aaData']), 5); //first page display 5 out of 10 records
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		$this->loginUser();

		$this->testAction('/admin/configurations/add/EMAIL_MARKETING', array(
			'data' => array(
				'Configuration' => array(
					'name' => 'unit_test',
					'value' => 'support'
				)
			),
			'return' => 'contents',
			'method' => 'post'
		));

		$this->assertEqual($this->configurationsController->Session->read('Message.flash.message'), 'Configuration has been added.');
		$this->assertStringEndsWith('/admin/configurations/', $this->headers['Location']);

		$this->testAction('/admin/configurations/add/SYSTEM?iframe=1', array(
			'data' => array(
				'Configuration' => array(
					'name' => 'unit_test',
					'value' => 'support'
				)
			),
			'return' => 'contents',
			'method' => 'post'
		));

		$this->assertContains('/admin/configurations/edit/SYSTEM/', $this->headers['Location']);
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {
		$this->loginUser();
		$configurationModel = ClassRegistry::init('Configuration');
		$configuration = $configurationModel->browseBy($configurationModel->primaryKey, 1, false);
		$this->assertEqual($configuration['Configuration']['value'], 'CrazySoft');

		$this->testAction('/admin/configurations/edit/SYSTEM/1', array(
			'data' => array(
				'Configuration' => array(
					'id' => '1',
					'name' => 'CompanyName',
					'value' => 'support'
				)
			),
			'method' => 'post'
		));

		$configuration = $configurationModel->browseBy($configurationModel->primaryKey, 1, false);
		$this->assertEqual($configuration['Configuration']['value'], 'support');
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->loginUser();

		$configurationModel = ClassRegistry::init('Configuration');
		$configurations = $configurationModel->find('all');
		$this->assertEqual(count($configurations), 57);

		$this->testAction(
			'/admin/configurations/delete/SYSTEM/1',
			array(
				'method' => 'post'
			)
		);

		$configurations = $configurationModel->find('all');
		$this->assertEqual(count($configurations), 56);
	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete() {
		$this->loginUser();

		$configurationModel = ClassRegistry::init('Configuration');
		$configurations = $configurationModel->find('all');
		$this->assertEqual(count($configurations), 57);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
			'/admin/configurations/batchDelete',
			array(
				'data' => array(
					'batchIds' => array('1', '2', '3'),
				),
				'method' => 'post'
			)
		);

		$configurations = $configurationModel->find('all');
		$this->assertEqual(count($configurations), 54);
	}

}
