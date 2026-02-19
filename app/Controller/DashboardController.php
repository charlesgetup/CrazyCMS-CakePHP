<?php
App::uses('AppController', 'Controller');
class DashboardController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
    }

/**
 * index method
 *
 * This action shows the backend/admin site management pages access point.
 * It is a empty page which only initialise the JS ajax page load function and other JS functions.
 * The real page content will be loaded using ajax.
 *
 * @return void
 */
    public function admin_index() {

    }

/**
 * view method
 *
 * This action shows the dashboard view page content.
 *
 * @return void
 */
    public function admin_view() {

    }

/**
 * faq method
 *
 * This action shows the FAQ page content.
 * Put FAQ page in Dashboard instead of create another controller for it. And each service plugins will create an element for its own FAQ and those elements will be loaded here
 *
 * @return void
 */
    public function admin_faq() {

    }
}
