<?php
App::uses('Country', 'Model');

/**
 * Country Test Case
 *
 */
class CountryTest extends CakeTestCase {

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
		$this->Country = ClassRegistry::init('Country');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Country);

		parent::tearDown();
	}

/**
 * testSaveCountry method
 *
 * @return void
 */
	public function testSaveCountry() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testUpdateCountry method
 *
 * @return void
 */
	public function testUpdateCountry() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

/**
 * testDeleteCountry method
 *
 * @return void
 */
	public function testDeleteCountry() {
		$this->markTestSkipped('This function only uses CakePHP default functionalities, no customised logic invloved.');
	}

}
