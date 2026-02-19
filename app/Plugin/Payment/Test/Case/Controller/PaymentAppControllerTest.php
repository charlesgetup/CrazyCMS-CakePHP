<?php
/**
 * PaymentAppController Test Case
 * The base test case of controller tests
 *
 */
class PaymentAppControllerTest extends ControllerTestCase {

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

		$userParentId = $this->userController->Session->read('Auth.User.parent_id');
		$userParentId = empty($userParentId) ? $this->userController->Session->read('Auth.User.id') : $userParentId;
		$associatedUsers = $userModel->getAssociateUsers($userParentId);
		$this->userController->Session->write('AssociatedUsers', $associatedUsers);

		return $userModel;

	}

	protected function updateFixtureData($className, $recordId, $dataArr){

		$model = ClassRegistry::init($className);
		$model->read(null, $recordId);
		$model->save($dataArr, false);
		return $model;

	}

/**
 * Call protected/private method of a class.
 *
 * @param object &$object    Instantiated object that we will run method on.
 * @param string $methodName Method name to call
 * @param array  $parameters Array of parameters to pass into method.
 *
 * @return mixed Method return.
 */
	protected function invokeMethod(&$object, $methodName, array $parameters = array()){
		$reflection = new ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $parameters);
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