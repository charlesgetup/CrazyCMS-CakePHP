<?php
App::uses('WebDevelopmentAppController', 'WebDevelopment.Controller');
/**
 * Dashboard Controller
 *
 */
class WebDevelopmentDashboardController extends WebDevelopmentAppController {

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