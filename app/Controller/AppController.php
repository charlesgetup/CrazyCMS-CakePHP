<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/* Load custom exceptions here */
App::uses('BadControllerRequestException', 	'Lib/Exception');
App::uses('ForbiddenActionException', 		'Lib/Exception');
App::uses('IncorrectUseMethodException', 	'Lib/Exception');
App::uses('NotFoundRecordException', 		'Lib/Exception');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
    	'Security' => array(
    		'className' 	=> 'CrazySecurity',
	        'csrfExpires' 	=> '+10 minutes',
    		'csrfUseOnce' 	=> true
	    ),
        'DebugKit.Toolbar',
        'Acl',
        'Auth' => array(
        	'className' => 'CrazyAuth',
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
            )
        ),
        'Session',
    	'CrazyCookie',
    	'Paginator',
        'Facebook.Connect' => array('model' => 'User'),
        'History',
        'Util',
    	'UrlUtil',
    	'Mpdf',
    	'ZMQMultithreadedService',
    	'DataTable',
    	'RequestHandler'
    );

    public $helpers = array(
        'Html',
    	'ExtendedHtml',
        'Form',
        'Session',
        'Facebook.Facebook' => array('locale' => 'en_US'),
        'Permissions',
    	'JqueryDataTable',
    	'PricingTable',
    	'Util',
    	'Time',
    	'Timeline',
    	'TaskManagement.TaskManagementUtil',
    	'EmailMarketing.EmailMarketingPermissions',
    	'Minify.Minify'
    );

    public $cacheAction = true;

    public $loadInIframe = FALSE;

    public $Log;
    public $superUserGroupId;
    public $superUserGroup;
    public $superUserId;
    public $superUserAssociatedUsers;

    public function beforeFilter() {

    	// Define Cookie settings
    	$this->CrazyCookie->name	= $this->_getSystemDefaultConfigSetting('CompanyNameShort', Configure::read('Config.type.system'));
    	$this->CrazyCookie->time 	= 600;
    	$this->CrazyCookie->path	= '/';
    	$this->CrazyCookie->domain 	= $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
    	$this->CrazyCookie->secure 	= true;
    	$this->CrazyCookie->key 	= Configure::read('Security.cookieKey');
    	$this->CrazyCookie->httpOnly = false;

    	$this->set('csrfCookieName', $this->CrazyCookie->name."[" .Configure::read('System.security.cookie.csrf') ."]");

    	// To handle security issue by ourselves
    	// Because we need to use GET to show controller action view and POST to trigger process, we cannot apply method requirement to all actions. We will apply this in certain controller actions individually
    	$this->Security->blackHoleCallback 	= 'c35e4326a166fa8afa8E183c84Bf20a5b03c50b3';
    	$this->Security->requireSecure 		= array('*');
    	if(empty($this->Security->requireAuth)){
    		$this->Security->requireAuth 	= array('*');
    	}
    	$this->set('security', $this->Security);

    	// Set up a flag to debug the Auth (This flag var only works within this function)
    	$debugAuthByLog = FALSE; // Set this to TRUE, will enable the logging for auth info (the error_log function is only called within this function)

        // Display static pages
        $this->Auth->allow('display');

        // Used to format date in controller, e.g. format date in log records
        App::uses('CakeTime', 'Utility');

        // Get user details for logging
        list($this->superUserGroupId, $this->superUserGroup, $this->superUserId, $this->superUserAssociatedUsers) = $this->_getCurrentUserDetails();

        // Init log model object
        if(empty($this->Log)){
        	if($this->modelClass == "Log"){
        		$this->Log = ClassRegistry::init('Log');
        	}else{
        		$this->Log = $this->{$this->modelClass}->Log;
        	}

        	// Some controller model class is a CakePHP class, not this application class. So that model class doesn't inherited from our AppModel class
        	// And it doesn't have $this->{$this->modelClass}->Log.
        	// In this way, we need to initialise it directly.
        	if(empty($this->Log)){
        		$this->Log = ClassRegistry::init('Log');
        	}
        }

        // Configure AuthComponent
        $this->Auth->authenticate = array(
            AuthComponent::ALL => array('userModel' => 'User'),
            'Form' => array(
                'fields' => array('username' => 'email')
            )
        );
        $this->Auth->loginAction    = array('controller' => 'users',     'action' => 'login', 'admin' => true);
        $this->Auth->logoutRedirect = array('controller' => 'users',     'action' => 'login', 'admin' => true);
        $this->Auth->loginRedirect  = array('controller' => 'dashboard', 'action' => 'index', 'admin' => true);

        // Check login status & Check permission for every action call
        $requestedAction = isset($this->request->params['action']) ? $this->request->params['action'] : '*';
		$requestedPath = ((isset($this->plugin) && !empty($this->plugin)) ? $this->plugin .'/' : '') .$this->name .'/' .$requestedAction;

		// If not logged in
		if(!$this->Auth->loggedIn() && !in_array($requestedAction, $this->Auth->allowedActions)){

			if($debugAuthByLog){
				error_log('requestedAction: ' .$requestedAction .' + ' .print_r($this->Auth->allowedActions, true) .' + ' .print_r($this->request->params, true));
				error_log('called: ' .$requestedPath .' - (login: ' .print_r($this->Auth->loggedIn()) .') - (path: ' .$requestedPath .')');
			}

			$this->_showFlashMessage(Configure::read('System.variable.warning'), null, __('Your session is expired. Please login again.') .' (' .$this->request->clientIp() .')');
			return $this->redirect($this->Auth->logoutRedirect, null, true);
		}

		if($debugAuthByLog){
			error_log('checked auth group id in session: ' .$this->Session->read('Auth.User.Group.id') .' | ' .$requestedPath);
		}

		// If not authorised
        if(!in_array($requestedAction, $this->Auth->allowedActions) && !$this->Acl->check(array('model' => 'Group', 'foreign_key' => $this->Session->read('Auth.User.Group.id')), $requestedPath)){
        	$currentUserAllAccounts = @$this->Session->read('AssociatedUsers');
        	$canAccess = false;
        	if(!empty($currentUserAllAccounts) && is_array($currentUserAllAccounts)){
        		foreach($currentUserAllAccounts as $account){

        			if(empty($account['User']['active'])){
        				// Only active account can be used to check permission
        				continue;
        			}

        			if($debugAuthByLog){
        				error_log('checked auth group id inside loop: ' .$account['User']['group_id'] .' | ' .$requestedPath);
        			}

        			if(!$canAccess && $this->Acl->check(array('model' => 'Group', 'foreign_key' => $account['User']['group_id']), $requestedPath)){
        				$canAccess = true;
        				break;
        			}
        		}
        	}
        	if(!$canAccess){

        		if($debugAuthByLog){
        			error_log('requestedPath: ' .$requestedPath .' - ' .$requestedAction);
        		}

        		throw new UnauthorizedException();
        	}
        }

        // Set an ACL object to view for permission checking purpose
        $this->set('acl', $this->Acl);

        // Set page layout
        $this->loadInIframe = $this->request->query['iframe'] === "1";
        if($this->loadInIframe){

        	$this->layout = "admin_iframe";
        	$this->set('loadInIframe', $this->loadInIframe);

        }elseif(isset($this->params['admin']) && !empty($this->params['admin'])){

        	$this->layout = "admin_content";

        	$companyName  = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
        	$companyLogo  = $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
        	$this->set ( compact ( 'companyName', 'companyLogo' ) );

        }else{

        	$this->layout = "default";

        	$companyName 		= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
        	$companyLogo 		= $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
        	$metaKeywords 		= $this->_getSystemDefaultConfigSetting('MetaKeywords', Configure::read('Config.type.system'));
        	$metaDescription 	= $this->_getSystemDefaultConfigSetting('MetaDescription', Configure::read('Config.type.system'));
        	$this->set ( compact ( 'companyName', 'companyLogo', 'metaKeywords', 'metaDescription' ) );
        }

        $this->set('title_for_layout', $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system')));

        // Set user group name which will be used to determine the view content
        // This is helping ACL to provide more detailed user permission check, especially useful when multiple
        // user groups have the same permission, but they need to see different views
        $this->set('userGroupName', $this->Session->read('Auth.User.Group.name'));

    }

/**
 * Set up some global render configurations
 */
    public function beforeRender() {

        // Override layout for displaying errors
        if($this->name == 'CakeError') {

        	$this->layout = 'error';

        	$companyName = $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
        	$this->set('companyName', $companyName);

        }

        /* Load global features, like contents in top nav bar. These kind of data will be saved in SESSIONS in order to save some compute time */
        $groupId = $this->Session->read('Auth.User.group_id');
        $userId = $this->superUserId;

        // Tasks
        $this->loadModel('TaskManagement.TaskManagementTask');
        $topFiveTasks = $this->TaskManagementTask->getTopFive($this->superUserId, $this->superUserGroup);
        $this->set('topFiveTasks', $topFiveTasks);
        $topFiveTaskCount = $this->TaskManagementTask->getTopFive($this->superUserId, $this->superUserGroup, TRUE);
        $this->set('topFiveTaskCount', $topFiveTaskCount);

        // Email Marketing
        $this->loadModel('EmailMarketing.EmailMarketingCampaign');
        $emailMarketingGroupId = Configure::read('EmailMarketing.client.group.id');
        if($groupId != $emailMarketingGroupId){
        	foreach($this->superUserAssociatedUsers as $associatedUser){
        		if(!empty($associatedUser['User']['active']) && $associatedUser['User']['group_id'] == $emailMarketingGroupId){
        			$userId = $associatedUser['User']['id'];
        		}
        	}
        }
        $emailMarketingCampaignCount = $this->EmailMarketingCampaign->countCampaignByUserId($userId);
        $this->set('emailMarketingCampaignCount', $emailMarketingCampaignCount);

        // Projects
        $this->loadModel('WebDevelopment.WebDevelopmentProject');
        $webDevelopmentGroupId = Configure::read('WebDevelopment.client.group.id');
        if($groupId != $webDevelopmentGroupId){
        	foreach($this->superUserAssociatedUsers as $associatedUser){
        		if(!empty($associatedUser['User']['active']) && $associatedUser['User']['group_id'] == $webDevelopmentGroupId){
        			$userId = $associatedUser['User']['id'];
        		}
        	}
        }
        $webDevProjectCount = $this->WebDevelopmentProject->getProjectsByServiceUserId($userId, $this->superUserGroup, TRUE);
        $this->set('webDevProjectCount', $webDevProjectCount);

    }

/**
 * Security blackhole callback
 * @param string $type
 */
    public function c35e4326a166fa8afa8E183c84Bf20a5b03c50b3($type) {

    	$companyDomain = $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));

    	if($type == Configure::read('System.security.alert.secure')){

    		return $this->redirect('https://' . env('SERVER_NAME') . $this->here); // Force SSL

    	}elseif($type == Configure::read('System.security.alert.auth')){

    		error_log('Bad request: captured a request which failed authentication. (Referer: ' .$this->referer() .', Request method: ' .$this->request->method() .', Request details: ' .json_encode($this->request) .')');

    		throw new BadRequestException(__d($companyDomain, 'The request has been black-holed'));

    	}elseif($type == Configure::read('System.security.alert.csrf')){

    		error_log('Bad request: captured a CSRF invalid request. (Referer: ' .$this->referer() .', Request method: ' .$this->request->method() .', Request details: ' .json_encode($this->request) .')');

    		throw new BadRequestException(__d($companyDomain, 'The request has been black-holed'));

    	}elseif (in_array($type, Configure::read('System.security.alert'))){

    		error_log('Bad request: captured an invalid request which mismatched the required method. (Referer: ' .$this->referer() .', Request method: ' .$this->request->method() .', Request details: ' .json_encode($this->request) .')');

    		throw new BadRequestException(__d($companyDomain, 'The request has been black-holed'));

    	}else{

    		error_log('Bad request: captured an invalid request with unknown security reason. (Referer: ' .$this->referer() .', Request method: ' .$this->request->method() .', Request details: ' .json_encode($this->request) .')');

    		throw new BadRequestException(__d($companyDomain, 'The request has been black-holed'));

    	}
    }

/**
 * Use ACL to manage authorization
 * @param array $user
 * @return boolean
 */
    public function isAuthorized($user) {
    	return false;
    }

/**
 * Run this when there is any changes made to user model or associated user model
 */
    protected function _updateUserStatus(){

    	$this->loadModel('User');

    	$currentUser = $this->User->find('first', array(
    		'conditions' => array('User.id' => $this->Session->read('Auth.User.id')),
    		'contain'  => true,
    	));
    	$currentUserAuth = array();
    	foreach($currentUser as $key => $value){

    		if($key == "User"){

    			$currentUserAuth[$key] = $value;

    		}else{

    			$currentUserAuth['User'][$key] = $value;
    		}
    	}
    	$this->Session->write('Auth', $currentUserAuth);

    	$userParentId = $this->Session->read('Auth.User.parent_id');
    	$userParentId = empty($userParentId) ? $this->Session->read('Auth.User.id') : $userParentId;
    	$associatedUsers = $this->User->getAssociateUsers($userParentId);
    	$this->Session->write('AssociatedUsers', $associatedUsers);

    	list($this->superUserGroupId, $this->superUserGroup, $this->superUserId, $this->superUserAssociatedUsers) = $this->_getCurrentUserDetails();
    }

/**
 * Set up no-view action
 */
    protected function _prepareNoViewAction(){
    	Configure::write('debug',0);
    	$this->autoRender = false;
    	$this->disableCache();
    }

/**
 * Set up some rules for ajax (get / post) method
 * @throws NotFoundException
 */
    protected function _prepareAjaxAction(){
    	$this->_prepareNoViewAction();
    	if(!$this->request->is('ajax')){
    		//TODO implement log (ajax call) in JS
    		throw new NotFoundException();
    	}
    }

/**
 * Set up some rules for ajax post only method
 * @throws NotFoundException
 */
    protected function _prepareAjaxPostAction(){
    	$this->_prepareNoViewAction();
        if(!$this->request->is('ajax') || !$this->request->is('post')){
        	// Ajax post request always triggered in JavaScript and let the JS code to handle the error output.
        	//TODO implement log (ajax call) in JS
        	throw new NotFoundException();
        }
    }

/**
 * Generate error flash message
 * @param string $model
 * @param string $specialMsg
 * @param string $msgPrefix
 * @param string $msgSuffix
 * @return string
 */
    protected function _showErrorFlashMessage($model = null, $specialMsg = "", $msgPrefix = "", $msgSuffix = ""){

        $msgProcedure = function($errMsg, $model){

            // Add validation errors
        	if(isset($model->validationErrors) && !empty($model->validationErrors) && is_array($model->validationErrors)){
        		$message_amount = count($model->validationErrors);
        		if($message_amount > 1){
        			$errMsg = '<ul>';
        		}
                foreach($model->validationErrors as $field => $errors){
                	if($message_amount > 1){
                		$errMsg .= '<li>';
                	}
                    if(is_array($errors)){
                    	$message = empty($errors) ? "" : __($errors[0]);
                    	if(!empty($message) && !ctype_upper($message[0])){
                    		$errMsg .= __(Inflector::humanize($field)) ." ";
                    	}
                    	$errMsg .= $message ." ";
                    }else{
                    	if(!empty($errors) && !ctype_upper($errors[0])){
                    		$errMsg .= __(Inflector::humanize($field)) ." ";
                    	}
                    	$errMsg .= __($errors) ." ";
                    }
                    if($message_amount > 1){
                    	$errMsg .= '</li>';
                    }
                }
                if($message_amount > 1){
                	$errMsg .= '</ul>';
                }
            }

            return $errMsg;
        };

        $this->_showFlashMessage(Configure::read('System.variable.error'), $model, $specialMsg, $msgPrefix, $msgSuffix, $msgProcedure);
    }

/**
 * Generate success flash message
 * @param string $model
 * @param string $specialMsg
 * @param string $msgPrefix
 * @param string $msgSuffix
 */
    protected function _showSuccessFlashMessage($model = null, $specialMsg = "", $msgPrefix = "", $msgSuffix = ""){
    	$this->_showFlashMessage(Configure::read('System.variable.success'), $model, $specialMsg, $msgPrefix, $msgSuffix, null);
    }

/**
 * Generate warning flash message
 * @param string $model
 * @param string $specialMsg
 * @param string $msgPrefix
 * @param string $msgSuffix
 */
    protected function _showWarningFlashMessage($model = null, $specialMsg = "", $msgPrefix = "", $msgSuffix = ""){
        $this->_showFlashMessage(Configure::read('System.variable.warning'), $model, $specialMsg, $msgPrefix, $msgSuffix, null);
    }

/**
 * Show flash message
 * @param unknown $messageType
 * @param string $model
 * @param string $specialMsg
 * @param string $msgPrefix
 * @param string $msgSuffix
 * @param string $msgProcedure
 * @throws NotFoundException
 */
    protected function _showFlashMessage($messageType, $model = null, $specialMsg = "", $msgPrefix = "", $msgSuffix = "", $msgProcedure = null){
    	$messageType = strtoupper($messageType);

    	$systemSuccessFlag 	= Configure::read('System.variable.success');
    	$systemErrorFlag 	= Configure::read('System.variable.error');
    	$systemWarningFlag 	= Configure::read('System.variable.warning');

    	if (empty($messageType) || !is_string($messageType) || !in_array($messageType, array($systemSuccessFlag,$systemErrorFlag,$systemWarningFlag))) {

            throw new FatalErrorException(__('Invalid Flash Message Type: ' .print_r($messageType, true)), 500, ROOT . DS . APP_DIR . DS ."Controller" .DS .$this->name ."Controller.php", 435);
        }

        switch($messageType){
        	case $systemSuccessFlag:
        		$logLevel 	 = Configure::read('System.log.level.info');
        		break;
        	case $systemErrorFlag:
        		$logLevel 	 = Configure::read('System.log.level.error');
        		break;
        	case $systemWarningFlag:
        		$logLevel 	 = Configure::read('System.log.level.warning');
        		break;
        }

        if(!empty($specialMsg)){

        	$logType 	 = empty($this->superUserId) ? Configure::read('Config.type.system') : Configure::read('Config.type.user');
        	$logMessage  = $specialMsg;
        	$this->Log->addLogRecord($logType, $logLevel, $logMessage);

            $this->Session->setFlash($specialMsg, 'page/flash/' .$messageType);
        }else{

            if (empty($model)) {

                throw new MissingModelException('Controller which sent the flash message does not have a MODEL. (Controller: ' .$this->name .')');
            }

            $msg = "";

            // Run procedure
            if($msgProcedure != null){
            	$msg = $msgProcedure($msg, $model);
            }

            // Show customised or default message
            $modelName              = __(Inflector::humanize(Inflector::underscore($model->alias)));
            $actionName             = str_replace("admin_", "", $this->params["action"]);
            $actionName				= str_replace("_", " ", Inflector::underscore($actionName));
            $actionName				= strtolower(trim($actionName));
            $actionNamePastTense    = __($this->Util->changeToPastTense($actionName));
            switch($messageType){
            	case $systemSuccessFlag:
                    $defaultMsg = trim("{$msgPrefix} {$modelName} " .__('has been') ." " .$actionNamePastTense .". {$msgSuffix}");
                    break;
                case $systemErrorFlag:
                    $defaultMsg = trim("{$msgPrefix} {$modelName} " .__('could not be') ." " .$actionNamePastTense .". {$msgSuffix}");
                    break;
                case $systemWarningFlag:
                    $defaultMsg = trim("{$msgPrefix} " .__('Pay attention to') ." {$actionName} {$modelName}. {$msgSuffix}");
                    break;
            }
            $msg = trim($msgPrefix ." " .$msg ." " .$msgSuffix);

            // Add record ID to the generated log record for staffs
            $msg = (empty($msg) ? $defaultMsg : $msg);
            if((empty($this->superUserId) || stristr($this->superUserGroup, Configure::read('System.client.group.name')) === FALSE) && !empty($msg) && !empty($model->id)){
            	$msg .= ' (' .__('Affected record ID:') .' ' .$model->id .')';
            }

            // Some controller action name cannot be correctly tranlated to human readable language. We have to fix some grammar error manually here
            if(strstr($msg, "send emailed")){
            	$msg = str_replace("send emailed", "sent", $msg);
            }

            if($systemErrorFlag == $messageType){

            	// Don't log validation errors, log something useful.
            	if(empty($this->{$this->modelClass}->validationErrors)){
            		$logType 	 = empty($this->superUserId) ? Configure::read('Config.type.system') : Configure::read('Config.type.user');
	            	$logMessage  = $msg;
	            	$this->Log->addLogRecord($logType, $logLevel, $logMessage);
            	}

            }else{

            	$logType 	 = empty($this->superUserId) ? Configure::read('Config.type.system') : Configure::read('Config.type.user');
            	$logMessage  = $msg;
            	$this->Log->addLogRecord($logType, $logLevel, $logMessage);
            }

            $this->Session->setFlash($logMessage, 'page/flash/' .$messageType);
        }

    }

/**
 * Get current user details
 * @return multitype:mixed
 */
    protected function _getCurrentUserDetails() {
    	$userGroupName 		= $this->Session->read('Auth.User.Group.name');
    	$userGroupId 		= $this->Session->read('Auth.User.Group.id');
        $userId 			= $this->Session->read('Auth.User.id');
        $associatedUsers 	= $this->Session->read('AssociatedUsers');
    	return array($userGroupId, $userGroupName, $userId, $associatedUsers);
    }

    protected function _getSystemDefaultConfigSetting($name, $type){
    	$this->loadModel("Configuration");
    	return $this->Configuration->findConfiguration($name, $type);
    }
}
