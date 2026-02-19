<?php
App::uses('AppController', 'Controller');
/**
 * ErrorHandler Controller
 *
 * @property ErrorHandler $ErrorHandler
 */
class ErrorHandlerController extends AppController {

    public function beforeFilter() {

        $this->Auth->allow('admin_view');

        parent::beforeFilter();
        $this->layout = "ajax";
    }

/**
 * view method
 *
 * @return void
 */
	public function admin_view() {
		$this->_prepareAjaxPostAction();
        $view = new View($this, false);

        error_log($this->request->data["error_message"]);

        $content = $view->element('page/flash/' .$this->request->data["message_type"], array('message' => $this->request->data["error_message"]));
        echo $content;
        exit();
	}

}
