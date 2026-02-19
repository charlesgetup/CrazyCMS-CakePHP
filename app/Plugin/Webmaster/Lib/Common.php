<?php
/**
 * Common class
 *
 * PHP 5
 *
 * Copyright 2013, Jad Bitar (http://jadb.io)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2013, Jad Bitar (http://jadb.io)
 * @link          http://github.com/gourmet/common
 * @since         0.1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('ClassRegistry', 'Utility');
App::uses('Reveal', 'Webmaster.Lib');
/**
 * Common
 *
 * @package       Webmaster.Lib
 */
class Common {

/**
 * Get configuration environment variable ($_SERVER[...]) and overwrites it
 * with equivalent key from runtime configuration. If none found, uses
 * `$default` value.
 *
 * Example:
 *
 *     `$_SERVER['REDIS_HOST']` would be defined in runtinme as `Redis.host`
 *
 * @param string $name Variable to obtain. Use '.' to access array elements.
 * @param mixed $default Optional. Default value to return if variable not configured.
 * @param string $plugin Optional. Name of plugin that may have over-written the configuration key.
 * @return mixed Variable's value.
 */
	public static function read($name, $default = null, $plugin = null) {
		if (!is_null($plugin)) {
			$result = Common::read("$plugin.$name");
			if (!is_null($result)) {
				return $result;
			}
		}
		$key = str_replace('.', '_', strtoupper($name));
		if (isset($_SERVER[$key])) {
			return $_SERVER[$key];
		}
		if (Configure::check($name)) {
			return Configure::read($name);
		}
		return $default;
	}

}