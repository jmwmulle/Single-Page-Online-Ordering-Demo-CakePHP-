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
	Router::connect('/splash-order', array('controller' => 'pages', 'action' => 'display', 'splash_order_modal'));
	Router::connect('/menu/*', array('controller' => 'orbcats', 'action' => 'menu'));
	Router::connect('/users/update', array('controller' => 'users', 'action' => 'edit'));
	Router::connect('/cart/*', array('controller' => 'orders', 'action' => 'cart'));
	Router::connect('/menuitem/*', array('controller' => 'orbs', 'action' => 'menu_item'));
	Router::connect('/register', array('controller' => 'pages', 'action' => 'signup'));
	Router::connect('/order-method/*', array('controller' => 'orders', 'action' => 'order_method'));
	Router::connect('/confirm-address/*', array('controller' => 'orders', 'action' => 'confirm_address'));
	Router::connect('/launch-menu/*', array('controller' => 'orbcats', 'action' => 'menu', null, null, true));
	Router::connect('/favorite/*', array('controller' => 'users', 'action' => 'add_favourite'));
	Router::connect('/favorites/*', array('controller' => 'users', 'action' => 'favorites'));
	Router::connect('/settings', array('controller' => 'users', 'action' => 'account'));
	Router::connect('/sign-up', array('controller' => 'pages', 'action' => 'display', 'sign_up'));
	Router::connect('/login/email', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/login/twitter', array('controller' => 'auth', 'action' => 'twitter'));
	Router::connect('/login/facebook', array('controller' => 'auth', 'action' => 'facebook'));
	Router::connect('/login/gplus', array('controller' => 'auth', 'action' => 'gplus'));
	Router::connect('/opauth-complete/*', array('controller' => 'users', 'action' => 'opauth_complete'));
	Router::connect('/clear-cart', array('controller' => 'orders', 'action' => 'clear'));
	Router::connect('/review-order/*', array('controller' => 'orders', 'action' => 'review'));
	Router::connect('/finalzie-order/*', array('controller' => 'orders', 'action' => 'review'));



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

	
