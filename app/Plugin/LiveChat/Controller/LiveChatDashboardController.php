<?php
App::uses('LiveChatAppController', 'LiveChat.Controller');
/**
 * Dashboard Controller
 *
 */
class LiveChatDashboardController extends LiveChatAppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = "ajax";
    }

/**
 * index method
 *
 * Render the dashbord view (a view container for other features)
 *
 * @return void
 */
    public function admin_index() {

    }

}