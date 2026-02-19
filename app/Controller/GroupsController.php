<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Group $Group
 */
class GroupsController extends AppController {

    public $uses = array('Group','Aco');

    public $paginate = array(
    	'fields' 	=> array('Group.id, Group.modified, Group.name'),
    	'order'     => array("Group.id" => "ASC"),
    	'limit' 	=> 10,
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {

		$this->Group->recursive = 0;
		$this->Paginator->settings = $this->paginate;

		$this->DataTable->mDataProp = true;
		$this->set('response', $this->DataTable->getResponse());
		$this->set('_serialize','response');
		$this->set('defaultSortDir', $this->paginate['order']['Group.id']);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {

		if (!$this->Group->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}
		$group = $this->Group->browseBy($this->Group->primaryKey, $id);

		$this->set('group', $group);
		$this->set('defaultSortDir', 'ASC'); // This is for embeded user data table

	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post') && isset($this->request->data["Group"]) && !empty($this->request->data["Group"])) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->_showSuccessFlashMessage($this->Group);
				return $this->redirect('/admin/groups/' .($this->loadInIframe ? 'edit/' .$this->Group->id .'?iframe=1' : ''));
			} else {
				$this->_showErrorFlashMessage($this->Group);
			}
		}

		$this->render('admin_edit');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Group->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}
		if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["Group"]) && !empty($this->request->data["Group"])) {
			if ($this->Group->save($this->request->data)) {
				$this->_showSuccessFlashMessage($this->Group);
			} else {
				$this->_showErrorFlashMessage($this->Group);
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if($this->request->is('post') || $this->request->is('delete')){
			$this->Group->id = $id;
			if (!$this->Group->exists()) {

				throw new NotFoundRecordException($this->modelClass);
			}
			if ($this->Group->delete($id)) {
				$this->_showSuccessFlashMessage($this->Group);
			}else{
				$this->_showErrorFlashMessage($this->Group);
			}
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @return void
 */
	public function admin_batchDelete() {
		if (($this->request->is('post') || $this->request->is('delete')) && $this->request->is('ajax')){
			if(isset($this->request->data['batchIds']) && !empty($this->request->data['batchIds']) && is_array($this->request->data['batchIds'])){
				if($this->Group->deleteAll(array('Group.id' => $this->request->data['batchIds']))){
					$this->_showSuccessFlashMessage($this->Group, __("Selected groups have been batch deleted."));
				}else{
					$this->_showErrorFlashMessage($this->Group, __("Selected groups cannot be batch deleted."));
				}
			}
		}
	}
}
