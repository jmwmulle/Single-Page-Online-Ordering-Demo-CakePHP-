<?php
/**
 * Application level Controller
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
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
App::uses( 'Controller', 'Controller' );
App::uses( 'Folder', 'Utility' );
App::uses( 'File', 'Utility' );


/**
 * Application Controller
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package        app.Controller
 * @link           http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = array( "Session", "RequestHandler", 'Acl',
	                            'Auth' => array(
		                            'authorize' => array(
			                            'Actions' => array( 'actionPath' => 'controllers' )
		                            )
	                            ), 'Session');
	public $helpers = array( "Session", "Html", "Form");
	public $actsAs = array('containable');
	protected $topnav = array('Menu','Order','Deals','Favs');

	static function cakeUrl($controller, $action, $params = null) {
		if ( !is_array( $params ) & !empty( $params ) ) {
			return array( "controller" => $controller, "action" => $action, $params );
		}

		if ( is_array( $params ) ) {
			$urlArray = array( "controller" => $controller, "action" => $action );
			foreach ( $params as $pname => $pval ) {
				array_push( $urlArray, $pval );
			}

			return $urlArray;
		}

		return array( "controller" => $controller, "action" => $action );
	}


	static function now($asString = true) {
		$date = new DateTime( 'now' );

		return $asString ? $date->format( "Y-m-d H:i:s" ) : $date;
	}

	static function anchor($string) {
		$name = AppController::as_file_name( $string );

		return "<a name=\"" . $name . "\">$string</a>";
	}


	static function icon($name, $ws, $class = null, $return = true) {
		$class     = is_array( $class ) ? implode( " ", $class ) : $class;
		$direction = $ws > 0 ? 'right' : 'left';
		$ws        = $ws > 0 ? $ws : $ws * -1;
		$wsleft    = null;
		$wsright   = null;
		for ( $i = 0; $i < $ws; $i++ ) {
			$direction == 'left' ? $wsleft .= "&nbsp;" : $wsright .= "&nbsp;";
		}
		$iconStr = $wsleft . "<i class='fi-$name $class'></i>" . $wsright;
		echo $return ? null : $iconStr;

		return $return ? $iconStr : true;
	}

	static function class_declaration($classes) {
		if (!is_array($classes)) $classes = array($classes);
		foreach ( $classes as $i => $class ) {
			if ( is_array( $class ) ) {
				$classes[ $i ] = implode( "-", $class );
			}
		}

		//todo: sanitize first
		return is_array( $classes ) ? 'class="' . implode( " ", $classes ) . '"' : false;
	}

	static function selector_to_str($str) {
		//todo: add regex for finding trailing css attributes
		$first_char = substr( $str, 0, 1 );
		$str        = ( $first_char == "#" || $first_char == "." ) ? substr( $str, 1 ) : $str;

		return ucwords( str_replace( array( "-", "_" ), array( " ", " " ), $str ) );
	}


	static function str_to_selector($parts, $type = null) {
		$pattern = array( "/ /", "/'/", "/_/" );
		$replace = array( "_", null, "-" );

		// if a string is passed, convert to array
		if ( !is_array( $parts ) ) {
			$parts = array( $parts );
		}

		$formatted_parts = array();
		foreach ( $parts as $part ) {
			array_push( $formatted_parts, strtolower( preg_replace( $pattern, $replace, $part ) ) );
		}
		$selector = implode( "_", $formatted_parts );
		if ( $type ) {
			$selector = $type == "id" ? "#$selector" : ".$selector";
		}

		return $selector;
	}

	static function is_associative_array($array) {
		return array_keys( $array ) !== range( 0, count( $array ) - 1 );
	}


	static function data_attr($data, $quotes = "\"", $false_behavior = "int", $true_behavior = "int") {
		// determines how boolean values should be represented in the attribute
		$true_behaviors  = array( "int", "string" );
		$false_behaviors = array( "int", "string", "null", "remove", "attr" );

		//todo: consider throwing errors here; incorrect false behavior could fuck up a lot of stuff...
		if ( !in_array( $true_behavior, $true_behaviors ) ) {
			$true_behavior = "int";
		}
		if ( !in_array( $false_behavior, $false_behaviors ) ) {
			$false_behavior = "int";
		}
		if ( $quotes != "'" && $quotes != "\"" && $quotes != "`" ) { /*todo: throw an error*/;
		}

		$data_attributes = array();
		$switchQuotes    = false;
		foreach ( $data as $attr => $val ) {
			$remove = false;
			if ( $val === true ) {
				if ( $true_behavior == "int" ) {
					$val = "1";
				}
				else {
					$val = "true";
				}
			}
			if ( $val === false ) {
				switch ( $false_behavior ) {
					case "string":
						$val = "false";
						break;
					case "int":
						$val = 0;
						break;
					case "remove":
						$remove = true;
						break;
					case "null":
						$val = "null";
						break;
					default:
						$val = null; // ie. attr; this line does nothing but keep things visually tidy
				}
			}
			if ( is_array( $val ) ) { // assume this should be an js array or object
				$vStr = "";
				if ( AppController::is_associative_array( $val ) ) {
					$kvPairs = array();
					foreach ( $val as $k => $v ) {
						$v          = str_replace( array( "'", '"' ), array( "&#39;", "&quot;" ), $v );
						$kvPairs[ ] = '"' . $k . '":"' . $v . '"';
					}
					$vStr = "{" . implode( ", ", $kvPairs ) . "}";
				}
				else {
					$vStr = '["' . implode( '", "', $val ) . '"]';
				}
				$switchQuotes = true;
				$val          = $vStr;
			}

			if ( !$remove ) {
				if ( $switchQuotes ) {
					$quotes = $quotes === "\"" ? "'" : "\"";
				}
				$attr     = str_replace( array( "_", " " ), array( "-", "-" ), $attr );
				$data_str = $val === null ||
				            !isset( $val ) ? "data-$attr" : str_replace( "%QT", $quotes, "data-$attr=%QT$val%QT" );
				array_push( $data_attributes, $data_str );
				if ( $switchQuotes ) {
					$quotes       = $quotes === "\"" ? "'" : "\"";
					$switchQuotes = false;
				}
			}
		}

		return implode( " ", $data_attributes );
	}


	static function as_file_name($string) {
		$pattern = array( "/ /", "/'/", "/—/" );
		$replace = array( "_", null, "-" );
		$name    = strtolower( preg_replace( $pattern, $replace, $string ) );

		return $name;
	}

	static function cakeforms_name($array) {
		foreach ( $array as $i => $val ) {
			$pattern     = array( "/ /", "/'/", "/-/", "/—/" );
			$replace     = array( "_", null, "_", "-" );
			$array[ $i ] = strtolower( preg_replace( $pattern, $replace, $val ) );
			//if ($i === 0) { $array[$i] = ucfirst($array[$i]);}
		}

		return "data[" . implode( "][", $array ) . "]";
	}

	static function b64JSON($string, $encode = false) {
		return $encode ? base64_encode( json_encode( $string ) ) : base64_decode( json_decode( $string ) );
	}


	/**
	 * asClockTime method
	 *
	 * @param        $time
	 * @param string $unit
	 *
	 * @return string
	 * @throws InvalidArgumentException
	 */
	static function asClockTime($time, $unit = SECS) {
		if ( !is_int( $time ) || !in_array( $unit, array( MSECS, SECS, MINS, HRS ) ) ) {
			throw new InvalidArgumentException;
		}
		$time_string = null;
		$date_time   = new DateTime;
		switch ( $unit ) {
			case MSECS:
				$date_time->setTime( 0, 0, $time / 1000 );
				break;
			case MINS:
				$date_time->setTime( 0, $time, 0 );
				break;
			case HRS:
				$date_time->setTime( $time, 0, 0 );
				break;
			default:
				$date_time->setTime( 0, 0, $time );
				break;
		}

		return $date_time->format( "H:i:s" );
	}

	public function beforeRender() {
		$this->set("topnav", $this->topnav);
	}

	public function beforeFilter() {
		//Configure AuthComponent
		$this->Auth->allow();
		if (isset($this->request->params['pass'][0]) ) {
			if ($this->request->params['pass'][0] == 'home' && $this->Session->read('Auth.User')) {
				$this->redirect(___cakeUrl("users","home"));
			}
		}
		$this->Auth->loginAction = array(
		  'controller' => 'users',
		  'action' => 'login'
		);
		$this->Auth->logoutRedirect = array(
		  'controller' => 'users',
		  'action' => 'logout'
		);
		$this->Auth->loginRedirect = array(
		  'controller' => 'users',
		  'action' => 'home'
		);
		$this->Auth->allow('display');
	}	
}
