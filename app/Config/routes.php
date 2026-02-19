<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

	$prefix = "admin";

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', 											array('controller' => 'pages', 		  'action' => 'display', 'home'));
	Router::connect('/errors/**', 									array('controller' => 'errors', 	  'action' => 'display'));

/**
 * ...and system emails which will be sent in the front end website or sent in JS (Ajax)
 */
 	Router::connect('/system_email/sendContactEmail', 				array('controller' => 'system_email', 'action' => 'sendContactEmail'));
 	Router::connect('/system_email/sendNewUserActivateEmail/**', 	array('controller' => 'system_email', 'action' => 'sendNewUserActivateEmail'));
 	Router::connect('/system_email/sendResetPasswordEmail/**', 		array('controller' => 'system_email', 'action' => 'sendResetPasswordEmail'));
 	Router::connect('/system_email/sendInvoiceEmail/**', 			array('controller' => 'system_email', 'action' => 'sendInvoiceEmail'));
 	Router::connect('/system_email/sendReceiptEmail/**', 			array('controller' => 'system_email', 'action' => 'sendReceiptEmail'));

/**
 * ...and user sign in/out/up urls
 */
    Router::connect('/login', array('controller' => 'users', 'action' => 'login', 'prefix' => $prefix, $prefix => true));
    Router::connect('/logout', array('controller' => 'users', 'action' => 'logout', 'prefix' => $prefix, $prefix => true));
    Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
    Router::connect('/account/activate/**', array('controller' => 'users', 'action' => 'activateAccount'));
    Router::connect('/account/forget_password', array('controller' => 'users', 'action' => 'forgetPassword'));
    Router::connect('/account/reset_password/**', array('controller' => 'users', 'action' => 'resetPassword'));


/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));


/**
 * ...and connect the admin pages controller's URLs.
 */
    Router::connect( "/{$prefix}/users/profile/*", array('controller' => 'users', 'action' => 'edit', 'prefix' => $prefix, $prefix => true) );


/**
 * Enable special routing, like json, pdf and so on.
 */
    Router::parseExtensions('json');


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();


/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';