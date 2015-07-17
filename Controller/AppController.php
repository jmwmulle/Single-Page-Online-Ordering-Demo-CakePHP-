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

	App::uses( 'AppController', 'Controller' );

	/**
	 * Application Controller
	 * Add your application-wide methods in the class below, your controllers
	 * will inherit them.
	 *
	 * @package        app.Controller
	 * @link           http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
	 */
	class AppController extends Controller {
		public $components = array( "Session", "RequestHandler", 'Acl', 'DebugKit.Toolbar',
		                            'Auth' => array( 'authorize' => array(
						    'Actions' => array( 'actionPath' => 'controllers' )
						    ), 'authenticate' => array(
							    'Form' => array(
								    'userModel' => 'User',
								    'passwordHasher' => 'Blowfish',
								    'fields' => array( 'username' => 'User.email', 'password' => 'User.password' )
							    )
						    ), 'loginAction' => array(
							    'controller' => 'users',
							    'action' => 'login'
						    ), 'logoutRedirect' => array(
							    'controller' => 'menu',
							    'action' => ''
						    ), 
		                            ), 
		);
		public $helpers = array( "Session", "Html", "Form" );
		protected $topnav = array( 'Menu', 'Deals', 'Favs', 'Order', );

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

			return "<a class=\"hidden\" name=\"" . $name . "\">$string</a>";
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
			if ( !is_array( $classes ) ) {
				$classes = array( $classes );
			}
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
			$pattern = array( "/ /", "/'/", "/_/", "/&/" );
			$replace = array( "_", null, "-", "and" );

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
				if ( is_array( $val ) ) { // assume this should be a js array or object
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


		public function set_page_name($name) { $this->set('page_name', $name);}

		public function is_ajax_post() {
			return $this->request->is('ajax') && $this->request->is('post');
		}

		public function is_ajax_get() {
			return $this->request->is('ajax') && $this->request->is('get');
		}

		public function render_ajax_response($response) {
			$this->set(compact('response'));
			$this->render("/Elements/ajax_response", "ajax");
		}

		public function setClosesAt($date_time) {
			$sfile               = new File( '../status/sfile' );
			$data                = json_decode( $sfile->read() );
			$data[ 'closes_at' ] = $date_time;
			$sfile->write( json_encode( $data ) );
			$sfile->close();
		}

		public function setOpensAt($date_time) {
			$sfile              = new File( '../status/sfile' );
			$data               = json_decode( $sfile->read() );
			$data[ 'opens_at' ] = $date_time;
			$sfile->write( json_encode( $data ) );
			$sfile->close();
		}

		public function beforeFilter() {
			parent::beforeFilter();
			if ( preg_match( '/(?i)msie [0-9]/', $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
				return $this->redirect( 'pages/no_service' );
			}

			$statusFile = new File( APP . 'status/sfile' );
			$status     = $statusFile->read( true, 'r' );
			$this->set( "store_status", $status );
			$this->set( "topnav", $this->topnav );
			
			$this->Auth->loginRedirect = ___cakeUrl(
				"users", "edit", array( 'id' => $this->Auth->user( 'id' ) ) );
			$this->Auth->allow();
		}

		static function getOpenStatus() {
			$sfile		= new File( APP.'status/sfile' );
			$data		= json_decode( $sfile->read() );
			$sfile->close();
			if ($data->override) {
				return $data->override;
			} else {
				return $data->open;
			}
		}
		
		/**
		 * Pass "open", "closed", or "false" to reset to the normal schedule
		 * If set to "open" or "closed" it will reset back to the normal schedule the next
		 * time the store would naturally open or close, respectively.
		 */
		static function setOpenStatus($status) {
			$sfile          = new File( APP.'status/sfile' );
			$data           = json_decode( $sfile->read() );
			#db($data);
			$data->override = $status;
			$sfile->write( json_encode( $data ) );
			$sfile->close();
		}

		static function getHoursOfOperation() {
			$hrsfile = new File( APP.'status/hoursofoperation' );
			$hrs     = json_decode( $hrsfile->read() );
			$hrsfile->close();
			return $hrs;
		}

		static function setHoursOfOperation($hours) {
			$hrsfile = new File( APP.'status/hoursofoperation' );
			$hrsfile->write( json_encode( $hours ) );
			$hrsfile->close();
		}

		public function beforeRender() {
			if (preg_match('/(?i)msie [0-9]/',$_SERVER['HTTP_USER_AGENT'])) return $this->redirect( 'pages/no_service' );

			$statusFile = new File(APP.'status/sfile');
			$status = $statusFile->read(true, 'r');
			$statusFile->close();
			$this->set("store_status", $status);
			$this->set("topnav", $this->topnav);

			//Configure AuthComponent
			if ( isset( $this->request->params[ 'pass' ][ 0 ] ) ) {
				if ( $this->request->params[ 'pass' ][ 0 ] == 'home' && $this->Session->read( 'Auth.User' ) ) {
					$this->redirect( ___cakeUrl( "users", "home" ) );
				}
			}
			
		$this->Auth->loginAction    = array(
			'controller' => 'menu',
			'action'     => ''
		);
		$this->Auth->logoutRedirect = array(
			'controller' => 'menu',
			'action'     => ''
		);
		$this->Auth->loginRedirect  = ___cakeUrl( "users", "edit", array( 'id' => $this->Auth->user( 'id' ) ) );
	}

}
