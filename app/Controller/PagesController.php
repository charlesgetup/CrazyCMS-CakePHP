<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		$path = func_get_args();

		$companyName 		= $this->_getSystemDefaultConfigSetting('CompanyName', Configure::read('Config.type.system'));
		$companyWebsiteURL 	= 'http://' .$this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
		$companyLogo 		= $this->_getSystemDefaultConfigSetting('CompanyLogo', Configure::read('Config.type.system'));
		$companyAddress		= $this->_getSystemDefaultConfigSetting('CompanyAddress', Configure::read('Config.type.system'));
		$companyEmail		= $this->_getSystemDefaultConfigSetting('CompanyEmail', Configure::read('Config.type.system'));
		$companyDomain		= $this->_getSystemDefaultConfigSetting('CompanyDomain', Configure::read('Config.type.system'));
		$companyLogoWidth	= $this->_getSystemDefaultConfigSetting('CompanyLogoWidth', Configure::read('Config.type.system'));
		$companyLogoHeight	= $this->_getSystemDefaultConfigSetting('CompanyLogoHeight', Configure::read('Config.type.system'));

		$page = $subpage = $title_for_layout = null;

		$count = count($path);
		if (!$count) {

			$pathStr = '/';
			$pathUrl = "https://" .$companyDomain ."/";
			$breadcrumbList = array($pathUrl);

		}else{

			// Check the request page exists or not. Sometimes visitors directly modified the URL and point it to a folder , not a .ctp file, then CakePHP cannot render and throw 500 error.
			// To avoid this, we check the request page path and find the closest page for the visitor if the path is a folder
			$isFolder 		= true;
			$breadcrumbList = array();
			$pathCopy 		= $path;
			$finalPath 		= array();
			do{

				$implodedPath 	= implode('/', $pathCopy);
				$testPath 		= APP .'View' .DS .'Pages' .DS .$implodedPath .'.ctp';
				$testIndexPath 	= APP .'View' .DS .'Pages' .DS .$implodedPath .DS .'index.ctp'; // When visiting a folder, if there is a index.ctp view, render that view by default.

				if($requestFileExist = file_exists($testPath) || $indexFileExist = file_exists($testIndexPath)){
					$isFolder = false;
					if($indexFileExist){
						$pathCopy[] = 'index';
						if(empty($finalPath)){
							$finalPath = $pathCopy;
						}
						$breadcrumbList[] = "https://" .$companyDomain ."/" .implode('/', $pathCopy);
						array_pop($pathCopy); // Remove just added last element
					}else{
						if(empty($finalPath)){
							$finalPath = $pathCopy;
						}
						$breadcrumbList[] = "https://" .$companyDomain ."/" .implode('/', $pathCopy);
					}
				}

				array_pop($pathCopy); // Because we want to get breadcrumb list, we need to loop though the whole path

			}while (!empty($pathCopy));
			$path = $finalPath;
			if(empty($path)){
				throw new NotFoundException();
				exit();
			}

			if (!empty($path[0])) {
				$page = $path[0];
			}
			if (!empty($path[1])) {
				$subpage = $path[1];
			}

			$pathStr 		= implode('/', $path);
			$pathUrl 		= "https://" .$companyDomain ."/" .($pathStr == "/" ? '' : $pathStr);
		}

		$viewFileName 	= (empty($pathStr) || $pathStr == "/") ? APP .'View' .DS .'Pages' .DS .'home.ctp' : APP .'View' .DS .'Pages' .DS .$pathStr .'.ctp';

		// Set social media settings
		$this->set('sharedTitle',    h($companyName .' ' .__('- your first online solution')));
		$this->set('sharedUrl',      h($companyWebsiteURL));
		$this->set('sharedSummary',  h($companyName));
		$this->set('sharedImage',    h($companyLogo));

		$seoParams = array(
			'content' 	=> null, // This will be set in the view layout
			'keywords' 	=> array( // Here are the keyword generation params
				'min_word_length' 			=> 5,
				'min_word_occur' 			=> 2,
				'min_2words_length' 		=> 3,
				'min_2words_phrase_length' 	=> 10,
				'min_2words_phrase_occur' 	=> 2,
				'min_3words_length' 		=> 3,
				'min_3words_phrase_length' 	=> 10,
				'min_3words_phrase_occur' 	=> 2
			),
			'description'  => array( // Here are the description generation params
				'removeTagsByCss' => array(
					'div.text-3', 	// Blog
					'div.comments'  // Blog
				)
			),
			'companyName' 	 => $companyName,
			'companyAddress' => array(
				'all' 			=> $companyAddress,
				'streetAddress' => $this->_getSystemDefaultConfigSetting('CompanyAddressStreet', Configure::read('Config.type.system')),
				'state'			=> $this->_getSystemDefaultConfigSetting('CompanyAddressState', Configure::read('Config.type.system')),
				'postcode'		=> $this->_getSystemDefaultConfigSetting('CompanyAddressPostcode', Configure::read('Config.type.system')),
				'country'		=> $this->_getSystemDefaultConfigSetting('CompanyAddressCountry', Configure::read('Config.type.system'))
			),
			'companyEmail'  	=> $companyEmail,
			'companyPhone'  	=> $this->_getSystemDefaultConfigSetting('CompanyPhone', Configure::read('Config.type.system')),
			'companyDomain'  	=> "https://" .$companyDomain ."/",
			'companyLogo'  	=> array(
				'url' 		=> "https://" .$companyDomain .$companyLogo,
				'width'		=> $companyLogoWidth,
				'height' 	=> $companyLogoHeight
			),
			'pageURL'		=> $pathUrl,
			'pageTitle'		=> $pathUrl, //TODO this needs to be updated
			'pageCreateTime'	=> file_exists($viewFileName) ? date("Y-m-dTH:i:s+0000", filectime($viewFileName)) : '', // filectime() Not work for Unix/Linux OS
			'pageModifyTime'	=> file_exists($viewFileName) ? date("Y-m-dTH:i:s+0000", filemtime($viewFileName)) : '',
			'breadcrumbList'	=> empty($breadcrumbList) ? array() : array_reverse($breadcrumbList),
			'socialMediaUrls'	=> array(),
			'socialMediaPreviewImage' => array( //TODO this needs to be updated
				'url' 		=> "https://" .$companyDomain .$companyLogo,
				'type'		=> 'image/png',
				'height' 	=> $companyLogoHeight,
				'width'		=> $companyLogoWidth
			),
			'twitterUser'  => '' //{twitter @username}
		);

		$this->set(compact('page', 'subpage', 'companyName', 'companyWebsiteURL', 'companyLogo', 'companyAddress', 'companyEmail', 'seoParams'));
		$this->render($pathStr);
	}
}
