<?php
App::uses('Configuration', 'Model');

/**
 * Configuration Test Case
 *
 */
class ConfigurationTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		$this->Configuration = ClassRegistry::init('Configuration');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Configuration);

		parent::tearDown();
	}

/**
 * testFindConfiguration method
 *
 * @return void
 */
	public function testFindConfiguration() {

		$defaultConfiguration = $this->Configuration->findConfiguration('BounceToMailBox', 'EMAIL_MARKETING');
		$this->assertEqual($defaultConfiguration, "email_marketing_bounce@crazycms.net");

		$defaultConfigurationObj = $this->Configuration->findConfiguration('BounceToMailBox', 'EMAIL_MARKETING', 1, true);
		$this->assertEqual($defaultConfigurationObj['value'], "email_marketing_bounce@crazycms.net");

	}

/**
 * testSaveConfiguration method
 *
 * @return void
 */
	public function testSaveConfiguration() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testUpdateConfiguration method
 *
 * @return void
 */
	public function testUpdateConfiguration() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testDeleteConfiguration method
 *
 * @return void
 */
	public function testDeleteConfiguration() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

}
