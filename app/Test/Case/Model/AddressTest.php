<?php
App::uses('Address', 'Model');

/**
 * Address Test Case
 *
 */
class AddressTest extends CakeTestCase {

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
		$this->Address = ClassRegistry::init('Address');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Address);

		parent::tearDown();
	}

/**
 * testSaveAddress method
 *
 * @return void
 */
	public function testSaveAddress() {

		$data = array(
			'Address' => array(
				'user_id' 				=> '216',
				'country_id' => '10',
				'type' => 'BILLING',
				'same_as' => '1',
				'street_address' => '1 Test Street',
				'suburb' => null,
				'postcode' => null,
				'state' => null,
			)
		);

		$address = $this->Address->browseBy('user_id', '216');
		$this->assertEmpty($address);

		$result = $this->Address->saveAddress($data);

		$newAddress = $this->Address->browseBy ( 'user_id', '216');
		$this->assertTrue($result);
		$this->assertEqual($newAddress['Address']['street_address'], '1 Test Street');

	}

}
