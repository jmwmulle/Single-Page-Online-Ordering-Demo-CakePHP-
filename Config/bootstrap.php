<?php
	/**
	 * This file is loaded automatically by the app/webroot/index.php file after core.php
	 * This file should load/create any application wide configuration settings, such as
	 * Caching, Logging, loading additional configuration files.
	 * You should also use this file to include any files that provide global functions/constants
	 * that your application uses.
	 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
	 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
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
	Cache::config( 'default', array( 'engine' => 'File' ) );
	/**
	 * The settings below can be used to set additional paths to models, views and controllers.
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

	 */

	/**
	 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
	 * string is passed to the inflection functions
	 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
	 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));

	 */

	/**
	 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
	 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
	 * advanced ways of loading plugins
	 * CakePlugin::loadAll(); // Loads all plugins at once
	 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit

	 */

	/**
	 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
	 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
	 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
	 * Feel free to remove or add filters as you see fit for your application. A few examples:
	 * Configure::write('Dispatcher.filters', array(
	 *        'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
	 *        'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
	 *        array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
	 *        array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
	 * ));
	 */
	Configure::write( 'Dispatcher.filters', array(
			'AssetDispatcher',
			'CacheDispatcher'
		)
	);

	/**
	 * Configures default file logging options
	 */
	App::uses( 'CakeLog', 'Log' );
	CakeLog::config( 'debug', array(
			'engine' => 'File',
			'types'  => array( 'notice', 'info', 'debug' ),
			'file'   => 'debug',
		)
	);
	CakeLog::config( 'error', array(
			'engine' => 'File',
			'types'  => array( 'warning', 'error', 'critical', 'alert', 'emergency' ),
			'file'   => 'error',
		)
	);


	function db($var) {
		pr( $var );
		exit();
	}


	/* These are syntactic sugar; all of these methods belong to AppController but wrapping them here
	   results in cleaner code in views. */
	function ___cakeUrl($controller, $action, $params = null) {
		return AppController::cakeUrl( $controller, $action, $params );
	}

	function ___i($name, $ws, $class = null, $return = true) {
		return AppController::icon( $name, $ws, $class, $return );
	}

	function ___selToStr($str) {
		return AppController::selector_to_str( $str );
	}

	function ___strToSel($parts, $type = null) {
		return AppController::str_to_selector( $parts, $type );
	}

	function ___dA($data, $quotes = "\"", $false_behavior = "int", $true_behavior = "int") {
		return AppController::data_attr( $data, $quotes, $false_behavior, $true_behavior );
	}

	function ___asClockTime($time, $unit = SECS) {
		return AppController::asClockTime( $time, $unit );
	}

	function ___b64JSON($string, $encode = false) {
		return AppController::b64JSON( $string, $encode );
	}

	function ___cD($classes) {
		return AppController::class_declaration( $classes );
	}

	function ___as_file_name($str) {
		return AppController::as_file_name( $str );
	}

	function ___cfName($data) {
		return AppController::cakeforms_name( $data );
	}



//	CakePlugin::load( 'AclExtras' );
//	CakePlugin::load( 'Migrations' );
	CakePlugin::load( 'Opauth', array( 'routes' => true, 'bootstrap' => true ) );
//	CakePlugin::load( 'DebugKit' );


	Configure::write( 'Opauth.Strategy.Google', array(
			'client_id'     => '355448840085-0b92qc1ksodcd03ca2fidmbfu2iv5f3l.apps.googleusercontent.com',
			'client_secret' => 'feuihy7Mm0yQ8IXePb6OjcIT'
		)
	);

	Configure::write( 'Opauth.Strategy.Facebook', array(
			'app_id'     => '765798036795267',
			'app_secret' => '57dfbdff219046eec4c396e52644494c',
			'scope'      => 'email'
		)
	);

	Configure::write( 'Opauth.Strategy.Twitter', array(
			'key'    => 'sgFHVzP4WePdImPOxsqF2gAaR',
			'secret' => 'IFBJPR4NWyS74yNJ0TwZNdy8dnUOnV55RHrHIJ7ayFlvK4GYYM'
		)
	);

define('ORDER_PENDING', 0);
define('ORDER_ACCEPTED', 1);
define('ORDER_REJECTED', 2);

define('JUST_BROWSING', 'just_browsing');
define('DELIVERY', 'delivery');
define('PICKUP', 'pickup');
define('ADDRESS_VALID', true);
define('ADDRESS_INVALID', false);
define('ADDRESS_INCOMPLETE', null);
define('CONF_ADR_DB', 'database');
define('CONF_ADR_DB_UPD', 'update_database');
define('CONF_ADR_SESSION', 'session');

define('CREDIT_CARD', 'credit_card');
define('PAYPAL', 'paypal');
define('CASH', 'cash');
define('DEBIT', 'debit');

define("USER_ACCOUNTS_ACTIVE", false);
define("SOCIAL_ACTIVE", false);

define("STORE_OPEN", 1);
define("DEBIT_AVAILABLE", 2);
define("POS_AVAILABLE", 3);
define("DELIVERY_AVAILABLE", 4);
define("CREDIT_AVAILABLE", 5);
define("DELIVERY_TIME_15", 6);
define("DELIVERY_TIME_30", 7);
define("DELIVERY_TIME_45", 8);
define("DELIVERY_TIME_60", 9);
define("DELIVERY_TIME_75", 10);
define("DELIVERY_TIME_90", 11);
define("DELIVERY_TIME_90_PLUS", 12);