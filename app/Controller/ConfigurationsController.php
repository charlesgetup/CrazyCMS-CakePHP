<?php
App::uses('AppController', 'Controller');
/**
 * Configurations Controller
 *
 * @property Configuration $Configuration
 */
class ConfigurationsController extends AppController {

    public $paginate = array(
    	'order' => array("Configuration.id" => "DESC"),
        'limit' => 10,
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
		$this->set('userGroupName', $this->superUserGroup);
	}

/**
 * view function
 * @param string $type
 * @param string $startDate
 * @param string $endDate
 */
	public function admin_view($configurationType){
		$this->Configuration->recursive = 0;

		// Set up fields, add popup link to User ID field
		$this->paginate['fields'] = array(
			'id',
			'user_popup',
			'name',
			'value',
			'modified',
			'modified_by_name'
		);

		// Find certain typs of configurations
		$this->paginate['conditions'] = array('Configuration.type' => $configurationType);

		// Find certain content to display
		if(stristr($this->superUserGroup, Configure::read('System.client.group.name'))){
			$this->paginate['conditions'] = am($this->paginate['conditions'], array('Configuration.user_id' => $this->superUserId));
		}

		$this->Paginator->settings = $this->paginate;

		$this->set(compact('configurationType', 'userGroupName'));

    	$this->DataTable->mDataProp = true;
    	$this->set('response', $this->DataTable->getResponse());
    	$this->set('_serialize','response');
    	$this->set('defaultSortDir', $this->paginate['order']['Configuration.id']);

    	$humanReadableConfigurationType = strtolower($configurationType);
    	$humanReadableConfigurationType = str_replace("_", " ", $humanReadableConfigurationType);

	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add($configurationType) {

		$configurationTypeArr = array_values(Configure::read('Config.type'));

		if ($this->request->is('post') && isset($this->request->data["Configuration"]) && !empty($this->request->data["Configuration"]) && in_array($configurationType, $configurationTypeArr)) {

			$this->request->data["Configuration"]["modified"] = date('Y-m-d H:i:s');
			$this->request->data["Configuration"]["modified_by"] = $this->superUserId;
			$this->request->data["Configuration"]["type"] = $configurationType;
			if ($newConfigurationId = $this->Configuration->saveConfiguration($this->request->data)) {
				$this->_showSuccessFlashMessage($this->Configuration);
				return $this->redirect('/admin/configurations/' .($this->loadInIframe ? 'edit/' .$configurationType .'/' .$newConfigurationId .'?iframe=1' : ''));
			} else {
				$this->_showErrorFlashMessage($this->Configuration);
			}
		}

		$this->set(compact('configurationType'));

		$this->render('admin_edit');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($configurationType, $id = null) {

		$configurationTypeArr = array_values(Configure::read('Config.type'));

        if (!$this->Configuration->exists($id)) {

			throw new NotFoundRecordException($this->modelClass);
		}

		//TODO when client can change his own configurations, please add security check to make sure the client can only edit his own records

		if (($this->request->is('post') || $this->request->is('put')) && isset($this->request->data["Configuration"]) && !empty($this->request->data["Configuration"]) && in_array($configurationType, $configurationTypeArr)) {

			$this->request->data["Configuration"]["modified"] = date('Y-m-d H:i:s');
			$this->request->data["Configuration"]["modified_by"] = $this->superUserId;
			$this->request->data["Configuration"]["type"] = $configurationType;
			if ($this->Configuration->updateConfiguration($id, $this->request->data)) {
				$this->_showSuccessFlashMessage($this->Configuration);
			} else {
				$this->_showErrorFlashMessage($this->Configuration);
			}

		}else{

			$this->request->data = $this->Configuration->browseBy($this->Configuration->primaryKey, $id);
		}

		$this->set(compact('configurationType'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($configurationType, $id = null) {
		if($this->request->is('post') || $this->request->is('delete')){
			$this->Configuration->id = $id;
			if (!$this->Configuration->exists($id)) {

				throw new NotFoundRecordException($this->modelClass);
			}

			//TODO when client can change his own configurations, please add security check to make sure the client can only delete his own records

			if ($this->Configuration->delete()) {
				if($configurationType == Configure::read('Config.type.emailmarketing')){
					$this->_showSuccessFlashMessage($this->Configuration, __("Email configuration has been deleted."));
				}elseif($configurationType == Configure::read('Config.type.payment')){
					$this->_showSuccessFlashMessage($this->Configuration, __("Payment configuration has been deleted."));
				}else{
					$this->_showSuccessFlashMessage($this->Configuration);
				}
			}else{
				$this->_showErrorFlashMessage($this->Configuration);
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
				if($this->Configuration->deleteAll(array('Configuration.id' => $this->request->data['batchIds']))){
					$this->_showSuccessFlashMessage($this->Configuration, __("Selected configurations have been batch deleted."));
				}else{
					$this->_showErrorFlashMessage($this->Configuration, __("Selected configurations cannot be batch deleted."));
				}
			}
		}
	}
}
