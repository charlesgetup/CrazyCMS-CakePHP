<?php
/**
 * AppShell file
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
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Shell', 'Console');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {

	public $debug 				= 0;

	public $debugOutputMethod	= "error_log";

	public $superUserId;

	public $uses = array(
		'Configuration',
		'Log'
	);

/**
 * The parser is used after Shell::initialize(), but before Shell::startup().
 * This means if the arguments and options are invalid, only Shell::initialize() is run.
 * @see Shell::getOptionParser()
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addOption('user_id', array(
			'short' 	=> 'u',
			'help' 		=> __('Current system user ID (Log purpose).'),
		))->addOption('debug', array(
			'short' 	=> 'd',
			'help' 		=> __('Show debug message (0: no debug; 1: show debug).'),
			'default'	=> 0
		))->addOption('debug_output_method', array(
			'short' 	=> 'o',
			'help' 		=> __('Debug output (php) method, e.g. error_log.'),
			'default'	=> "error_log"
		));

		return $parser;
	}

/**
 * This method runs first
 * @see Shell::initialize()
 */
	public function initialize(){
		parent::initialize();
	}

/**
 * This method runs after Shell::initialize()
 * Because the passed arguments and options can be accessed here, we do the preparation for the command.
 * @see Shell::startup()
 */
	public function startup(){
		$this->debug 					= $this->params['debug'];
		$this->debugOutputMethod 		= $this->params['debug_output_method'];
		$this->superUserId				= $this->params['user_id'];
	}

/**
 * This method runs after Shell::startup()
 * This method defines the main logic, and call different tasks or proviate method to do the actual job.
 *
 * Note:
 * 		This method only does some preparation for the extended shell command
 */
	public function main() {

	}

	protected function _getSystemDefaultConfigSetting($name, $type){

		return $this->Configuration->findConfiguration($name, $type);
	}

/**
 * Display debug info
 * @param string $message
 */
	protected function __debug ($message = ""){
		if(intval($this->debug) == 1 && !empty($message)){
			call_user_func_array($this->debugOutputMethod, array(strval('[' .date("Y-m-d H:i:s") .'] ' .$message)));
		}
	}
}
