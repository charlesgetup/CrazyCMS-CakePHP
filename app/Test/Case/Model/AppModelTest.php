<?php
App::uses('AppModel', 'Model');

/**
 * AppModel Test Case
 *
 */
class AppModelTest extends CakeTestCase {

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
		$this->AppModel = ClassRegistry::init('AppModel');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->AppModel);

		parent::tearDown();
	}

/**
 * testGetUserFileSavedPath method
 *
 * @return void
 */
	public function testGetUserFileSavedPath() {

		$absolutePath = $this->AppModel->getUserFileSavedPath();
		$this->assertEqual($absolutePath, ROOT .DIRECTORY_SEPARATOR . APP_DIR .DIRECTORY_SEPARATOR . WEBROOT_DIR .DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR);

		$relativePath = $this->AppModel->getUserFileSavedPath(false);
		$this->assertEqual($relativePath, DIRECTORY_SEPARATOR ."files" .DIRECTORY_SEPARATOR);

	}

/**
 * testBrowseBy method
 *
 * @return void
 */
	public function testBrowseBy() {

		$this->AppModel->useTable = 'users'; // This method is only used for child class. Here we use a child class's table

		$onlyUserData = $this->AppModel->browseBy('id', '1');
		$this->assertEqual($onlyUserData['AppModel']['id'], '1');
		$this->assertEqual($onlyUserData['AppModel']['password'], '49429f3d17dd6ed19952cf98b9ef32f47027c731');

		$userModel = ClassRegistry::init('User');
		$userDataAndRelatedData = $userModel->browseBy('id', '1', array('Group', 'Address'));
		$this->assertNotEmpty($userDataAndRelatedData['Group']);
		$this->assertNotEmpty($userDataAndRelatedData['Address']);
		$this->assertEqual($userDataAndRelatedData['Group']['name'], 'Admin');
		$this->assertEqual($userDataAndRelatedData['Address'][0]['type'], 'CONTACT');
		$this->assertEqual($userDataAndRelatedData['Address'][1]['type'], 'BILLING');

	}

/**
 * testFindAll method
 *
 * @return void
 */
	public function testFindAll() {

		$this->AppModel->useTable = 'users'; // This method is only used for child class. Here we use a child class's table

		$allBelindas = $this->AppModel->findAll('first_name', 'Belinda');
		$this->assertCount(2, $allBelindas);

		$userModel = ClassRegistry::init('User');
		$userDataAndRelatedData = $userModel->findAll('first_name', 'Belinda', array('Group', 'Address'));
		$this->assertCount(2, $userDataAndRelatedData);
		$this->assertNotEmpty($userDataAndRelatedData[0]['Group']);
		$this->assertNotEmpty($userDataAndRelatedData[1]['Group']);
		$this->assertEqual($userDataAndRelatedData[0]['Group']['name'], 'Admin');
		$this->assertEqual($userDataAndRelatedData[1]['Group']['name'], 'Email Marketing Clients');
		$this->assertNotEmpty($userDataAndRelatedData[0]['Address']);
		$this->assertEqual($userDataAndRelatedData[0]['Address'][0]['type'], 'CONTACT');
		$this->assertEqual($userDataAndRelatedData[0]['Address'][1]['type'], 'BILLING');
		$this->assertTrue(is_array($userDataAndRelatedData[1]['Address']));
		$this->assertEmpty($userDataAndRelatedData[1]['Address']);
	}

}
