<?php
App::uses('AppHelper', 'View/Helper');
App::uses('CakeSession', 'Model/Datasource');
App::import('Vendor', 'Minify.utils', array('file' => 'minify' .DS .'utils.php'));
App::import('Vendor', 'Minify.Helper', array('file' => 'minify' .DS .'lib' .DS .'Minify' .DS .'HTML' .DS .'Helper.php'));
App::import('Vendor', 'Minify.HTML', array('file' => 'minify' .DS .'lib' .DS .'Minify' .DS .'HTML.php'));
App::import('Vendor', 'Minify.JSMin', array('file' => 'minify' .DS .'vendor' .DS .'mrclay' .DS .'jsmin-php' .DS .'src' .DS .'JSMin' .DS .'JSMin.php'));

use JSMin;

/**
 * Cakephp view helper to interface with http://code.google.com/p/minify/ project.
 * Minify: Combines, minifies, and caches JavaScript and CSS files on demand to speed up page loads.
 * Requirements:
 * 		An entry in core.php - "MinifyAsset" - value of which is either set 'true' or 'false'.
 * 		False would be usually set during development and/or debugging. True should be set in production mode.
 * @package		app.View.Helper
 */
class MinifyHelper extends AppHelper {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Session');

	public $view;

	public function __construct($view) {
		$this->view = $view;
		parent::__construct($view);
	}

	public function afterLayout() {
		$this->view->output = $this->_compressHtml($this->view->output);
	}

/**
 * Creates a link element for javascript files.
 *
 * @param string|array $url String or array of javascript files to include
 * @param array $options Array of options, and html attributes
 * @return string
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::script
 */
	public function script($url, $options = array()) {
		if (Configure::read('MinifyAsset') === true) {
			return $this->Html->script($this->_path($url, 'js'), $options);
		} else {
			return $this->Html->script($url, $options);
		}
	}

/**
 * Creates a link element for CSS stylesheets.
 *
 * @param string|array $path The name of a CSS style sheet or an array containing names of CSS stylesheets.
 * @param string $rel Rel attribute. Defaults to "stylesheet". If equal to 'import' the stylesheet will be imported
 * @param array $options Array of options, and html attributes
 * @return string CSS <link /> or <style /> tag, depending on the type of link
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::css
 */
	public function css($path, $rel = null, $options = array()) {
		if (Configure::read('MinifyAsset') === true) {
			return $this->Html->css($this->_path($path, 'css'), $rel, $options);
		} else {
			return $this->Html->css($path, $rel, $options);
		}
	}

	public function assetPath($path, $type){
		if(empty($type) || empty($type) || !in_array($type, ['js', 'css'])){
			return false;
		}

		return $this->_path($path, $type);
	}

/**
 * Minify inline JS code
 * @param string $jsCode
 * @return boolean|string
 */
	public function minifyInlineJS($jsCode){
		if(empty($jsCode)){
			return false;
		}

		$minifiedCode =  JSMin\JSMin::minify($jsCode);

		$obfuscatedCode = Minify_encryptor($minifiedCode, Minify::TYPE_JS, true);

		return $obfuscatedCode;
	}

/**
 * Define a way to compress HTML. This will compress the css in <style> tag and JS in <script> tag.
 *
 * @param string $html
 */
	protected function _compressHtml($html){
		$options = Minify_HTML_Helper::app()->serveOptions;
		return Minify_HTML::minify($html, $options);
	}

/**
 * Build the path for minified files.
 *
 * @param string|array $assets Sring or array containing names of assests files
 * @param string $type js|css
 * @return string JS or CSS tag, depending on the type of link
 */
	private function _path($assets, $type) {
		if (!is_array($assets)) {
			$assets = array($assets);
		}

		if ($type === 'js') {
			$options = array('pathPrefix' => JS_URL, 'ext' => '.js');
		} else if ($type === 'css') {
			$options = array('pathPrefix' => CSS_URL, 'ext' => '.css');
		}

		$assetTimestamp = Configure::read('Asset.timestamp');
		Configure::write('Asset.timestamp', false);

		$files = array();
		foreach ($assets as $asset) {
			array_push($files, "//" .substr($this->assetUrl($asset, $options), 1)); // DO NOT use full base url
		}

		// Set up dynamic assets group
		$dynamicAssetsGroup = CakeSession::read('dynamicAssetsGroup');
		$groupId 			= md5(join(',', $files));
		if(empty($dynamicAssetsGroup) || !is_array($dynamicAssetsGroup)){
			$dynamicAssetsGroup = array();
		}

		if(empty($dynamicAssetsGroup) || !isset($dynamicAssetsGroup[$groupId])){
			$dynamicAssetsGroup[$groupId] = $files;
			CakeSession::write('dynamicAssetsGroup', $dynamicAssetsGroup);
		}

// 		$path = Minify_getUri($groupId); // This will generate a virtual file instead of an URI, and CakePHP cannot find that file and gives 404 error

		$path = '/min/?g='; // Use group to hide assets file names
		$path = $path . $groupId;

// 		$path = '/min/?f=';
// 		$path = $path . join(',', $files);

		return $path;
	}

}

?>