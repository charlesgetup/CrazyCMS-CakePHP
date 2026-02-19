<?php
class EmailMarketingPermissionsHelper extends PermissionsHelper {

	public function check($acl, $path){

		$valid = parent::check($acl, $path);

		if($valid && $this->isClient()){

			$EmailMarketingUser = ClassRegistry::init('EmailMarketing.EmailMarketingUser');
			$emailMarketingUser = $EmailMarketingUser->getUserBySuperId(AuthComponent::user('id'));
			if(empty($emailMarketingUser)){

				return false;
			}

			// Apply some special rules for Email Marketing Plugin

			// 1, check email sender

			if(in_array($emailMarketingUser['EmailMarketingUser']['email_marketing_plan_id'], Configure::read('EmailMarketing.plan.no.sender')) && $this->extendPHP->startsWith($path, "EmailMarketing/EmailMarketingSenders/")){

				return false;
			}
		}

		return $valid;
	}

}

?>