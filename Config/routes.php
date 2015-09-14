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

	Router::connect( '/!',                           [ CTR => 'orders', ATN => 'init_cart', 'true' ] );
	/**  PAGES CONTROLLER */
	Router::connect( '/',                           [ CTR => 'pages', ATN => 'display', 'countdown' ] );
	Router::connect( '/pages/*',                    [ CTR => 'pages', ATN => 'display' ] );
	Router::connect( '/register',                   [ CTR => 'pages', ATN => 'signup' ] );
	Router::connect( '/splash-order',               [ CTR => 'pages', ATN => 'display', 'splash_order_modal' ] );
	Router::connect( '/sign-up',                    [ CTR => 'pages', ATN => 'display', 'sign_up' ] );
	Router::connect( '/order-accepted',             [ CTR => 'pages', ATN => 'display', "order_accepted" ] );
	Router::connect( '/launch-apology',             [ CTR => 'pages', ATN => 'display', 'launch_apology' ] );

	/** USERS CONTROLLER */
	Router::connect( '/users/update',               [ CTR => 'users', ATN => 'edit' ] );
	Router::connect( '/favorite/*',                 [ CTR => 'users', ATN => 'add_favourite' ] );
	Router::connect( '/favorites/*',                [ CTR => 'users', ATN => 'favorites' ] );
	Router::connect( '/settings',                   [ CTR => 'users', ATN => 'account' ] );
	Router::connect( '/login/email',                [ CTR => 'users', ATN => 'login' ] );
	Router::connect( '/opauth-complete/*',          [ CTR => 'users', ATN => 'opauth_complete' ] );
	Router::connect( '/tablet/login',               [ CTR => 'users', ATN => 'tabletlogin' ] );

	/** ORBS CONTROLLER */
	Router::connect( '/menu-item/*',                [ CTR => 'orbs', ATN => 'orbcard' ] );
	Router::connect( '/vendor-ui/opts',             [ CTR => 'orbs', ATN => 'vendor_ui', 'opts' ] );
	Router::connect( '/vendor-ui/menu',             [ CTR => 'orbs', ATN => 'vendor_ui', 'menu' ] );
	Router::connect( '/vendor-ui',                  [ CTR => 'orbs', ATN => 'vendor_ui' ] );
	Router::connect( '/orbopt-config/*',            [ CTR => 'orbs', ATN => 'orbopt_config' ] );
	Router::connect( '/add-price-labels',           [ CTR => 'orbs', ATN => 'pricedict_add' ] );
	Router::connect( '/add-menu-item',              [ CTR => 'orbs', ATN => 'add' ] );
	Router::connect( '/delete-menu-item/*',         [ CTR => 'orbs', ATN => 'deprecate' ] );

	/** ORBCATS CONTROLLER */
	Router::connect( '/menu',                     [ CTR => 'pages', ATN => 'display', 'countdown' ] );
	Router::connect( '/menu/*',                     [ CTR => 'orbcats', ATN => 'menu'] );
	Router::connect( '/private-menu/*',             [ CTR => 'orbcats', ATN => 'menu' ] );

	/** ORDERS CONTROLLER */
	Router::connect( '/load-cart/*',                [ CTR => 'orders', ATN => 'read_cart' ] );
	Router::connect( '/update/*',                   [ CTR => 'orders', ATN => 'edit_cart' ] );
	Router::connect( '/add-to-cart/*',              [ CTR => 'orders', ATN => 'add_to_cart' ] );
	Router::connect( '/review-cart/*',              [ CTR => 'orders', ATN => 'review_cart' ] );
	Router::connect( '/set-address/*',              [ CTR => 'orders', ATN => 'set_address_form' ] );
	Router::connect( '/set-pickup-information/*',   [ CTR => 'orders', ATN => 'set_address_form' ] );
	Router::connect( '/confirm-address/*',          [ CTR => 'orders', ATN => 'confirm_address' ] );
	Router::connect( '/confirm-pickup-info/*',      [ CTR => 'orders', ATN => 'confirm_pickup_info' ] );
	Router::connect( '/clear-cart',                 [ CTR => 'orders', ATN => 'clear_cart' ] );
	Router::connect( '/finish-ordering/*',          [ CTR => 'orders', ATN => 'finalize' ] );
	Router::connect( '/order-confirmation/*',       [ CTR => 'orders', ATN => 'user_order_confirmation' ] );
	Router::connect( '/order-timeout/*',            [ CTR => 'orders', ATN => 'user_order_confirmation' ] );
	Router::connect( '/order-method/*',             [ CTR => 'orders', ATN => 'order_method' ] );
	Router::connect( '/pending',                    [ CTR => 'orders', ATN => 'get_pending', false ] );
	Router::connect( '/pending/refreshed',          [ CTR => 'orders', ATN => 'get_pending', true ] );
	Router::connect( '/print_response/*',           [ CTR => 'orders', ATN => 'log_printer' ] );
	Router::connect( '/review-order',               [ CTR => 'orders', ATN => 'review_order' ] );
	Router::connect( '/pos',                        [ CTR => 'orders', ATN => 'pos' ] );
	Router::connect( '/resolve-order/*',            [ CTR => 'orders', ATN => 'pos_resolve' ] );

	/** OPTFLAGS CONTROLLER */
	Router::connect( '/flagmap',                    [ CTR => 'optflags', ATN => 'ajax_list' ] );

	/** ORBOPTS-OPTFLAGS CONTROLLER */
	Router::connect( '/optflag-config/*',           [ CTR => 'orboptsOptflags', ATN => 'ajax_add' ] );

	/** ORBOPTS-ORBCATS CONTROLLER */
	Router::connect( '/orbopt-optgroup-config/*',   [ CTR => 'orboptsOrbcats', ATN => 'ajax_add' ] );

	/** PRICELISTS CONTROLLER */
	Router::connect( '/launch-orbopt-pricelist-config/*',   [ CTR => 'pricelists', ATN => 'index' ] );
	Router::connect( '/edit-orbopt-pricing/*',   [ CTR => 'pricelists', ATN => 'ajax_edit' ] );
	Router::connect( '/save-orbopt-pricing-edit/*',   [ CTR => 'pricelists', ATN => 'ajax_edit' ] );
	Router::connect( '/orbopt-pricing-delete/*',   [ CTR => 'pricelists', ATN => 'ajax_delete' ] );
	Router::connect( '/add-orbopt-pricelist/*',     [ CTR => 'pricelists', ATN => 'ajax_add' ] );


	/** ORBOPTS CONTROLLER */
	Router::connect( '/add-menu-option',            [ CTR => 'orbopts', ATN => 'add' ] );
	Router::connect( '/delete-menu-option/*',       [ CTR => 'orbopts', ATN => 'deprecate' ] );
	Router::connect( '/update-orbopt/*',            [ CTR => 'orbopts', ATN => 'update' ] );

	/** VARIABLES CONTROLLER */
	Router::connect( '/system/*',            [ CTR => 'sysvars', ATN => 'sv_config' ] );
	Router::connect( '/set-delivery-time/*',            [ CTR => 'sysvars', ATN => 'delivery_time' ] );


	Router::connect( '/auth/email',                 [ CTR => 'users', ATN => 'login' ] );

	#Router::connect('/confirm/*', [CTR => 'users', ATN => 'edit'] );

	//implementing RESTful API
	Router::mapResources( [ 'orbs', 'orbcats' ] );
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

	
