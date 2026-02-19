<?php

App::uses('PaymentAppController', 'Payment.Controller');

class PaymentDashboardController extends PaymentAppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * index method
 *
 * Render the dashbord view (a view container for other features)
 *
 * @return void
 */
	public function admin_index() {

		$this->loadModel('Payment.PaymentInvoice');

		list($totalInvoiceNumber, $totalInvoiceAmount, $totalPaidAmount, $totalUnpaidAmount, $thisMonthTotalInvoiceNumber, $thisMonthTotalInvoiceAmount, $thisMonthTotalPaidAmount, $thisMonthTotalUnpaidAmount) = $this->PaymentInvoice->getSummary((stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE) ? null : $this->superUserId);

		$this->set(compact('totalInvoiceNumber', 'totalInvoiceAmount', 'totalPaidAmount', 'totalUnpaidAmount', 'thisMonthTotalInvoiceNumber', 'thisMonthTotalInvoiceAmount', 'thisMonthTotalPaidAmount', 'thisMonthTotalUnpaidAmount'));

	}

	public function admin_manageRecurringPayments(){

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){

			// load iframe

		}else{

			$userId = $this->superUserId;

			// Just to display an index page for all payment gateways recurring payment sub pages

			$this->set ( compact ( 'userId' ) );
		}

	}
}
