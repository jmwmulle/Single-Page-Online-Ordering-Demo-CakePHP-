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
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/splash.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'splash'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/menu/*', array('controller' => 'orbcats', 'action' => 'menu'));
	Router::connect('/users/update', array('controller' => 'users', 'action' => 'edit'));
	Router::connect('/cart/*', array('controller' => 'orders', 'action' => 'cart'));
	Router::connect('/menuitem/*', array('controller' => 'orbs', 'action' => 'menu_item'));
	Router::connect('/register', array('controller' => 'pages', 'action' => 'signup'));
	Router::connect('/order_method/*', array('controller' => 'users', 'action' => 'order_method'));
	Router::connect('/confirm_address/*', array('controller' => 'users', 'action' => 'confirm_address'));
	Router::connect('/launch_menu/*', array('controller' => 'orbcats', 'action' => 'menu', null, null, true));
	Router::connect('/favorite/*', array('controller' => 'users', 'action' => 'add_favourite'));


	Router::connect('/opauth-complete/*', array('controller' => 'users', 'action' => 'opauth_complete'));

	Router::connect('/auth/email', array('controller' => 'users', 'action' => 'login'));
	
	#Router::connect('/confirm/*', array('controller' => 'users', 'action' => 'edit'));

 //implementing RESTful API
	Router::mapResources(array('orbs', 'orbcats'));
	Router::parseExtensions();
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

	
