<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 */
 CakePlugin::loadAll(array(
 	'DebugKit',
 	'Phpunit',
 	'Facebook',
 	'AmazonS3',
 	'AclExtras',
 	'AclManager'		=> array('bootstrap' => true),
 	'Payment'			=> array('bootstrap' => true),
 	'EmailMarketing' 	=> array('bootstrap' => true),
 	'WebDevelopment' 	=> array('bootstrap' => true),
 	'TaskManagement' 	=> array('bootstrap' => true),
 	'LiveChat' 			=> array('bootstrap' => true),
 	'Composer'			=> array('bootstrap' => true),
 	'Webmaster'			=> array('bootstrap' => true, 'routes' => true),
 	'Minify'			=> array('routes' => true)
 ));

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));


/**
 * Load override configurations
 */

/**
 * System g-recaptcha secret
 */
Configure::write('System.recaptcha.secret', 'yours');
Configure::write('System.recaptcha.verifyURL', 'https://www.google.com/recaptcha/api/siteverify');

/**
 * System security alert
 */
Configure::write('System.security.alert.auth', 		'auth');
Configure::write('System.security.alert.csrf', 		'csrf');
Configure::write('System.security.alert.get', 		'get');
Configure::write('System.security.alert.post', 		'post');
Configure::write('System.security.alert.put', 		'put');
Configure::write('System.security.alert.delete', 	'delete');
Configure::write('System.security.alert.secure', 	'secure');

/**
 * System S3 confidential
 */
Configure::write('System.aws.s3.secret', 'cS1iIWICYMEg9DTGK+RX0Z3HAfV4kEkCCsy6XKLl');
Configure::write('System.aws.s3.accesskey', 'AKIAJNEPC55JMAKNQHIA');
Configure::write('System.aws.s3.bucket.name', 'crazysoft-online-assets-au');
Configure::write('System.aws.s3.bucket.link.prefix', 'https://s3-ap-southeast-2.amazonaws.com/crazysoft-online-assets-au/');
Configure::write('System.aws.s3.action.get', 'GET');
Configure::write('System.aws.s3.action.put', 'PUT');
Configure::write('System.aws.s3.action.delete', 'DELETE');
Configure::write('System.aws.s3.action.list', 'LIST');

/**
 * System variable (flag & constant) definition
 * Only understore can be used within system variable value, and the value must be uppercase.
 */
Configure::write('System.variable.success', 'SUCCESS');
Configure::write('System.variable.error', 	'ERROR');
Configure::write('System.variable.warning', 'WARNING');
Configure::write('System.variable.inherit', 'INHERIT');
Configure::write('System.variable.allow', 	'ALLOW');
Configure::write('System.variable.deny', 	'DENY');
Configure::write('System.variable.debug', 	'DEBUG');

Configure::write('System.log.level.critical', 	'CRITICAL');
Configure::write('System.log.level.error', 		'ERROR');
Configure::write('System.log.level.warning', 	'WARNING');
Configure::write('System.log.level.info', 		'INFO');
Configure::write('System.log.level.debug', 		'DEBUG');


/**
 * System default settings
 */
Configure::write('Config.type.user', 			"USER"); 	// This is used for logging
Configure::write('Config.type.system', 			"SYSTEM");
Configure::write('System.security.cookie.csrf', "CSRF-TOKEN");
Configure::write('System.default.user.id', 		"1"); 		// Use with caution!!! Pre-set DB records are mapped to this default system user
Configure::write('System.admin.group.id', 		"1");
Configure::write('System.admin.group.name', 	"Admin");
Configure::write('System.client.group.id', 		"19");
Configure::write('System.client.group.name', 	"Client");
Configure::write('System.staff.group.name', 	"Staff");
Configure::write('System.manager.group.name', 	"Manager");