<?php
App::uses('EmailMarketingAppController', 'EmailMarketing.Controller');
/**
 * Dashboard Controller
 *
 */
class EmailMarketingDashboardController extends EmailMarketingAppController {

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