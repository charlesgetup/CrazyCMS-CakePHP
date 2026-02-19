<?php
App::uses('GroupsController', 'Controller');

/**
 * GroupsController Test Case
 *
 */
require_once 'AppControllerTest.php';
class GroupsControllerTest extends AppControllerTest {

	protected $groupsController;

	public function setUp() {
		parent::setUp();

		$this->groupsController = $this->generate('Groups', array(

		));

		$this->loginUser();
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
	public function testAdminIndex() {
		$this->testAction('/admin/groups', array('return' => 'vars'));
		$this->assertEqual(count($this->vars['response']['aaData']), 10); //first page display 10 records
	}

/**
 * testAdminView method
 *
 * @return void
 */
	public function testAdminView() {
		$this->testAction('/admin/groups/view/1', array('return' => 'vars', 'method' => 'get'));
		$this->assertEqual($this->vars['group']['Group']['id'], '1');
	}

/**
 * testAdminAdd method
 *
 * @return void
 */
	public function testAdminAdd() {
		$this->testAction('/admin/groups/add', array(
			'data' => array(
				'Group' => array('name' => 'unit_test_group')
			),
			'method' => 'post'
		));

		$groupModel = ClassRegistry::init('Group');
		$group = $groupModel->browseBy('name', 'unit_test_group', false);
		$this->assertNotEmpty($group['Group']['id']);
		$this->assertEqual($this->groupsController->Session->read('Message.flash.message'), 'Group has been added.');
	}

/**
 * testAdminEdit method
 *
 * @return void
 */
	public function testAdminEdit() {

		$groupModel = ClassRegistry::init('Group');
		$group = $groupModel->browseBy($groupModel->primaryKey, '1', false);
		$this->assertEqual($group['Group']['name'], 'Admin');

		$this->testAction('/admin/groups/edit/1', array(
			'data' => array(
				'Group' => array(
					'id' => '1',
					'name' => 'administrator')
			),
			'method' => 'post'
		));

		$group = $groupModel->browseBy($groupModel->primaryKey, '1', false);
		$this->assertEqual($group['Group']['name'], 'administrator');
	}

/**
 * testAdminDelete method
 *
 * @return void
 */
	public function testAdminDelete() {

		$this->testAction('/admin/groups/add', array(
			'data' => array(
				'Group' => array('name' => 'unit_test_group')
			),
			'method' => 'post'
		));

		$groupModel = ClassRegistry::init('Group');
		$groups = $groupModel->find('all');
		$this->assertEqual(count($groups), 20);

		$group = $groupModel->browseBy($groupModel->primaryKey, '20', false);
		$this->assertEqual($group['Group']['name'], 'unit_test_group');

		$this->testAction(
				'/admin/groups/delete/20',
				array(
					'method' => 'post'
				)
		);

		$groups = $groupModel->find('all');
		$this->assertEqual(count($groups), 19);

		$group = $groupModel->browseBy('name', 'unit_test_group', false);
		$this->assertEmpty($group);
	}

/**
 * testAdminBatchDelete method
 *
 * @return void
 */
	public function testAdminBatchDelete() {
		$groupModel = ClassRegistry::init('Group');
		$groups = $groupModel->find('all');
		$this->assertEqual(count($groups), 19);

		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$this->testAction(
				'/admin/groups/batchDelete',
				array(
					'data' => array(
						'batchIds' => array('5', '6', '8'),
					),
					'method' => 'post'
				)
		);

		$groups = $groupModel->find('all');
		$this->assertEqual(count($groups), 16);
	}

}
