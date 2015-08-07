<?php
	/**
	 * Routes configuration
	 * In this file, you set up routes to your controllers and their actions.
	 * Routes are very important mechanism that allows you to freely connect
	 * different URLs to chosen controllers and their actions (functions).
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
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
	// I know this is stupid, I just think it's prettier. Blame my IDE.
	define('CTR', 'controller');
	define('ATN', 'action');

	/**  PAGES CONTROLLER */
	Router::connect( '/',                           array( CTR => 'pages', ATN => 'display', 'splash' ) );
	Router::connect( '/pages/*',                    array( CTR => 'pages', ATN => 'display' ) );
	Router::connect( '/register',                   array( CTR => 'pages', ATN => 'signup' ) );
	Router::connect( '/splash-order',               array( CTR => 'pages', ATN => 'display', 'splash_order_modal' ) );
	Router::connect( '/sign-up',                    array( CTR => 'pages', ATN => 'display', 'sign_up' ) );
	Router::connect( '/order-accepted',             array( CTR => 'pages', ATN => 'display', "order_accepted" ) );
	Router::connect( '/launch-apology',             array( CTR => 'pages', ATN => 'display', 'launch_apology' ) );

	/** USERS CONTROLLER */
	Router::connect( '/users/update',               array( CTR => 'users', ATN => 'edit' ) );
	Router::connect( '/favorite/*',                 array( CTR => 'users', ATN => 'add_favourite' ) );
	Router::connect( '/favorites/*',                array( CTR => 'users', ATN => 'favorites' ) );
	Router::connect( '/settings',                   array( CTR => 'users', ATN => 'account' ) );
	Router::connect( '/login/email',                array( CTR => 'users', ATN => 'login' ) );
	Router::connect( '/opauth-complete/*',          array( CTR => 'users', ATN => 'opauth_complete' ) );
	Router::connect( '/tablet/login',               array( CTR => 'users', ATN => 'tabletlogin' ) );

	/** ORBS CONTROLLER */
	Router::connect( '/menu-item/*',                array( CTR => 'orbs', ATN => 'orbcard' ) );
	Router::connect( '/vendor-ui/opts',             array( CTR => 'orbs', ATN => 'vendor_ui', 'opts' ) );
	Router::connect( '/vendor-ui/menu',             array( CTR => 'orbs', ATN => 'vendor_ui', 'menu' ) );
	Router::connect( '/vendor-ui',                  array( CTR => 'orbs', ATN => 'vendor_ui' ) );
	Router::connect( '/orbopt-config/*',            array( CTR => 'orbs', ATN => 'orbopt_config' ) );
	Router::connect( '/add-price-labels',           array( CTR => 'orbs', ATN => 'pricedict_add' ) );
	Router::connect( '/add-menu-item',              array( CTR => 'orbs', ATN => 'add' ) );
	Router::connect( '/delete-menu-item/*',         array( CTR => 'orbs', ATN => 'deprecate' ) );

	/** ORBCATS CONTROLLER */
	Router::connect( '/menu/*',                     array( CTR => 'orbcats', ATN => 'menu' ) );
	Router::connect( '/launch-menu/*',              array( CTR => 'orbcats', ATN => 'menu', null, null, true ) );

	/** ORDERS CONTROLLER */
	Router::connect( '/add-to-cart/*',              array( CTR => 'orders', ATN => 'add_to_cart' ) );
	Router::connect( '/review-cart/*',              array( CTR => 'orders', ATN => 'review_cart' ) );
	Router::connect( '/confirm-address/*',          array( CTR => 'orders', ATN => 'confirm_address' ) );
	Router::connect( '/clear-cart',                 array( CTR => 'orders', ATN => 'clear_cart' ) );
	Router::connect( '/finish-ordering/*',          array( CTR => 'orders', ATN => 'review' ) );
	Router::connect( '/order-confirmation/*',       array( CTR => 'orders', ATN => 'get_status' ) );
	Router::connect( '/order-method/*',             array( CTR => 'orders', ATN => 'order_method' ) );
	Router::connect( '/pending',                    array( CTR => 'orders', ATN => 'get_pending', false ) );
	Router::connect( '/pending/refreshed',          array( CTR => 'orders', ATN => 'get_pending', true ) );
	Router::connect( '/print_response/*',           array( CTR => 'orders', ATN => 'log_printer' ) );
	Router::connect( '/review-order',               array( CTR => 'orders', ATN => 'review_order' ) );
	Router::connect( '/update-cart',                array( CTR => 'orders', ATN => 'update_cart' ) );
	Router::connect( '/vendor',                     array( CTR => 'orders', ATN => 'vendor' ) );
	Router::connect( '/vendor-accept/*',            array( CTR => 'orders', ATN => 'set_status' ) );
	Router::connect( '/vendor-reject/*',            array( CTR => 'orders', ATN => 'set_status' ) );

	/** ORBOPTS-OPTFLAGS CONTROLLER */
	Router::connect( '/optflag-config/*',           array( CTR => 'orboptsOptflags', ATN => 'ajax_add' ) );

	/** ORBOPTS-ORBCATS CONTROLLER */
	Router::connect( '/orbopt-optgroup-config/*',   array( CTR => 'orboptsOrbcats', ATN => 'ajax_add' ) );

	/** PRICELISTS CONTROLLER */
	Router::connect( '/launch-orbopt-pricelist-config/*',   array( CTR => 'pricelists', ATN => 'index' ) );
	Router::connect( '/edit-orbopt-pricing/*',   array( CTR => 'pricelists', ATN => 'ajax_edit' ) );
	Router::connect( '/save-orbopt-pricing-edit/*',   array( CTR => 'pricelists', ATN => 'ajax_edit' ) );
	Router::connect( '/orbopt-pricing-delete/*',   array( CTR => 'pricelists', ATN => 'ajax_delete' ) );
	Router::connect( '/add-orbopt-pricelist/*',     array( CTR => 'pricelists', ATN => 'ajax_add' ) );


	/** ORBOPTS CONTROLLER */
	Router::connect( '/add-menu-option',            array( CTR => 'orbopts', ATN => 'add' ) );
	Router::connect( '/delete-menu-option/*',       array( CTR => 'orbopts', ATN => 'deprecate' ) );
	Router::connect( '/update-orbopt/*',            array( CTR => 'orbopts', ATN => 'update' ) );


	Router::connect( '/auth/email',                 array( CTR => 'users', ATN => 'login' ) );

	#Router::connect('/confirm/*', array(CTR => 'users', ATN => 'edit'));

	//implementing RESTful API
	Router::mapResources( array( 'orbs', 'orbcats' ) );
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

	
