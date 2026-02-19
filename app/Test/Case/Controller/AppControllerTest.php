<?php
/**
 * AppController Test Case
 * The base test case of controller tests
 *
 */
class AppControllerTest extends ControllerTestCase {

	protected $userController;

	public function setUp() {
		parent::setUp();

		$this->userController = $this->generate('Users', array(
			'methods' => array(
				'requestAction'
			),
			'components' => array(
				'Facebook.Connect',
				'History',
				'Util',
				'RequestHandler'
			),
			'helpers' => array(
				'Facebook.Facebook'
			)
		));
	}

	public function tearDown() {
		$this->userController->Auth->logout();
		parent::tearDown();
	}

/**
 * Login Admin user by default
 * @param number $userId
 * @param number $userGroupId
 */
	protected function loginUser($userId = 1, $userGroupId = 1){

		$userModel = $this->getMockForModel('User', array('bindNode'));
		$userModel->expects($this->any())->method('bindNode')->will($this->returnValue(array('model' => 'Group', 'foreign_key' => $userGroupId)));
		$user = $userModel->browseBy($userModel->primaryKey, $userId, $contain = array('Group','Address' => array('Country'), 'EmailMarketingUser', 'PaymentPayer'));
		$authUser = $user['User'];
		unset($user['User']);
		$authUser += $user;
		$this->userController->Session->write('Auth.User', $authUser);
		return $userModel;

	}

	protected function updateFixtureData($className, $recordId, $dataArr){

		$model = ClassRegistry::init($className);
		$model->read(null, $recordId);
		$model->save($dataArr, false);
		return $model;

	}

/**
 * testPlaceHolder method
 *
 * @return void
 */
	public function testPlaceHolder() {
		$this->markTestSkipped('This is just a place holder to avoid the `No tests found in class "AppControllerTest".` error');
	}
}
?>