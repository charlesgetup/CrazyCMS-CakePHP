<?php

App::uses('PaymentAppController', 'Payment.Controller');

class PaymentRecurringAgreementsController extends PaymentAppController {

	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function admin_index() {

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){

			$this->paginate = array(
				'fields' 	=> array('User.id, User.first_name, User.last_name, User.active, PaymentPayer.payment_method, PaymentRecurringAgreement.recurring_agreement_id, PaymentRecurringAgreement.name, PaymentRecurringAgreement.start_time, PaymentRecurringAgreement.active'),
				'joins' 	=> array(
					array(
						'table' => 'payment_payer',
						'alias' => 'PaymentPayer',
						'type' => 'inner',
						'conditions' => array(
							'PaymentPayer.id = PaymentRecurringAgreement.payment_payer_id'
						)
					),
					array(
						'table' => 'users',
						'alias' => 'User',
						'type' => 'inner',
						'conditions' => array(
							'User.id = PaymentPayer.user_id'
						)
					),
				),
				'order'     => array("PaymentRecurringAgreement.start_time" => "DESC"),
				'limit' 	=> 10,
			);
			$this->Paginator->settings = $this->paginate;
			$this->DataTable->mDataProp = true;
			$this->set('response', $this->DataTable->getResponse());
			$this->set('_serialize','response');
			$this->set('defaultSortDir', $this->paginate['order']['PaymentRecurringAgreement.start_time']);
		}

	}

/**
 * index method
 *
 * @return void
 */
	public function admin_view($agreementId) {

		if(stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE){

			if(empty($agreementId) || !$this->PaymentRecurringAgreement->hasAny(array('recurring_agreement_id' => $agreementId))){

				throw new NotFoundRecordException($this->modelClass);
			}

			$agreement = $this->PaymentRecurringAgreement->browseBy('recurring_agreement_id', $agreementId, array('PaymentPayer'));

			$this->set('superUserId', $agreement['PaymentPayer']['user_id']);

			$postData = array(
				'data' => array(
					'PaymentRecurringAgreement' => array(
						'recurring_agreement_id' => $agreementId
					)
				)
			);

			$agreementDetails = array();
			$agreementDetails = $this->requestAction('/admin/payment/payment_pay_pal_gateway/getRecurringAgreementDetail/', $postData);
			$agreementDetails['recurring_agreement_id'] = $agreementId;

			$this->set('agreementDetails', $agreementDetails);
		}

	}
}
?>