<?php
App::uses('SecurityComponent', 'Controller/Component');
class CrazySecurityComponent extends SecurityComponent {

/**
 * Other components used by the Security component
 *
 * @var array
 */
	public $components = array('CrazyCookie', 'Session');

/**
 * Component startup. All security checking happens here.
 *
 * @param Controller $controller Instantiating controller
 * @return void
 */
	public function startup(Controller $controller) {

		$this->request = $controller->request;
		$this->_action = $this->request->params['action'];
		$isPost = $this->request->is(array('post', 'put'));

		// Add token in request data for AJAX auth check

		if($this->request->is('ajax') && $isPost){

			$requestUrl 	= Router::url( $controller->request->here, true);
			$Configuration 	= ClassRegistry::init('Configuration');
			$domain 		= $Configuration->findConfiguration('CompanyDomain', Configure::read('Config.type.system'));
			$urlArr 		= parse_url($requestUrl);
			if((empty($urlArr) || $urlArr['host'] != $domain || substr($urlArr['path'], 0, 7) != "/admin/") && !in_array($this->_action, (array)$this->unlockedActions)){
				return $this->blackHole($controller, 'auth');
			}

			$csrfToken 		= $controller->request->header('X-CSRF-Token');
			$csrfTokenTemp  = json_decode($csrfToken, true);
			if(empty($csrfTokenTemp)){
				$csrfToken = urldecode($csrfToken);
				$csrfToken = $this->CrazyCookie->decryptCookieContent($csrfToken);
// 				$csrfToken = json_decode($csrfToken, true);
// 				$csrfToken = array('_Token' => $csrfToken);
			}else{
				$csrfToken = $csrfTokenTemp;
			}

			if(empty($csrfToken) && empty($controller->request->data['_Token']) && !in_array($this->_action, (array)$this->unlockedActions)){

				return $this->blackHole($controller, 'auth');

			}else{

				if(empty($controller->request->data['_Token'])){

					$fields = $controller->request->data; // Save original fields here
					if(empty($csrfToken['_Token']['fields'])){

						// Token "unlocked" attribute could be empty, but "fields" attribute should always have some data. Otherwise _validatePost() function will fail for sure
						list($csrfToken['_Token']['fields'], $csrfToken['_Token']['unlocked']) = $this->__generateFieldsAndUnlockedAttrs($fields);

					}
					$controller->request->data['_Token'] = $csrfToken['_Token'];

					if(empty($this->_unlockedFields) && !empty($controller->request->data['_Token']['unlockedFields'])){
						$this->_unlockedFields = $controller->request->data['_Token']['unlockedFields'];
					}

				}
			}
		}

		$this->_methodsRequired($controller);
		$this->_secureRequired($controller);

		// No auth required if request is sent via `requestAction()` function
		if($this->request->params['requested'] !== 1){
			$this->_authRequired($controller);
		}

		$isNotRequestAction = (
			!isset($controller->request->params['requested']) ||
			$controller->request->params['requested'] != 1
		);

		if ($this->_action == $this->blackHoleCallback) {
			return $this->blackHole($controller, 'auth');
		}

		if (!in_array($this->_action, (array)$this->unlockedActions) && $isPost && $isNotRequestAction) {
			if ($this->validatePost && $this->_validatePost($controller) === false) {
				return $this->blackHole($controller, 'auth');
			}
			if ($this->csrfCheck && $this->_validateCsrf($controller) === false) {
				return $this->blackHole($controller, 'csrf');
			}
		}

		$this->generateToken($controller->request); // Generate the CSRF token and the new token is saved by $this->Session->write('_Token', $token);

		if ($isPost && is_array($controller->request->data)) {
			unset($controller->request->data['_Token']);
		}
	}


/**
 * Manually add CSRF token information into the provided request object & the cookie.
 *
 * @param CakeRequest $request The request object to add into.
 * @return boolean
 */
	public function generateToken(CakeRequest $request) {

		if(parent::generateToken($request)){

			$request['_Token'] = $request->params['_Token'];

			// CakePHP Cookie component is not working here. It cannot update cookie value multiple times in a very short time.

			setcookie(
				$this->CrazyCookie->name.'['.Configure::read('System.security.cookie.csrf').']',
				$this->CrazyCookie->encryptCookieContent(json_encode($request->params['_Token'])),
				time()+$this->CrazyCookie->time,
				$this->CrazyCookie->path,
				$this->CrazyCookie->domain,
				$this->CrazyCookie->secure,
				$this->CrazyCookie->httpOnly
			);

			return true;
		}

		return false;
	}

/**
 * Generate "fields" & "unlocked" attributes
 *
 * This function is a modified version of FormHelper::secure() function. (lib/Cake/View/Helper/FormHelper.php Line: 545)
 *
 * @param unknown $fields
 */
	private function __generateFieldsAndUnlockedAttrs($fields = array()) {

		$locked = array();
		$unlockedFields = $this->_unlockedFields;

		if(isset($fields['_Token'])){
			unset($fields['_Token']);
		}

		$fields = Hash::flatten($fields);
		$fieldList = array_keys($fields);
		$multi = array();

		foreach ($fieldList as $i => $key) {
			if (preg_match('/(\.\d+)+$/', $key)) {
				$multi[$i] = preg_replace('/(\.\d+)+$/', '', $key);
				unset($fieldList[$i]);
			}
		}
		if (!empty($multi)) {
			$fieldList += array_unique($multi);
		}

		foreach ($fieldList as $i => $key) {
			$isLocked = (is_array($locked) && in_array($key, $locked));

			if (!empty($unlockedFields)) {
				foreach ($unlockedFields as $off) {
					$off = explode('.', $off);
					$field = array_values(array_intersect(explode('.', $key), $off));
					$isUnlocked = ($field === $off);
					if ($isUnlocked) {
						break;
					}
				}
			}

			if ($isUnlocked || $isLocked) {
				unset($fieldList[$i]);
				if ($isLocked) {
					$lockedFields[$key] = $fields[$key];
				}
			}
		}

		sort($unlockedFields, SORT_STRING);
		sort($fieldList, SORT_STRING);
		ksort($locked, SORT_STRING);
		$fieldList += $locked;

		$locked = implode('|', array_keys($locked));
		$unlocked = implode('|', $unlockedFields);
		$fields = Security::hash(serialize($fieldList) . $unlocked . Configure::read('Security.salt'), 'sha1');

		$fields = urlencode($fields . ':' . $locked);
		$unlocked = urlencode($unlocked);

		return array($fields, $unlocked);
	}
}
?>