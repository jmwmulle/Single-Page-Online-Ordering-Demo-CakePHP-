<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $components = array( "Session", "RequestHandler", 'Acl',
	                            'Auth' => array('authorize' => array(
			                            'Actions' => array( 'actionPath' => 'controllers' )
					    ), 'authenticate' => array(
						    'Form' => array(
							    'fields' => array('username' => 'email')
						    )
					    )
				    ),);
	public $helpers = array( "Session", "Html", "Form");
	public $actsAs = array('containable');
	protected $topnav = array('Menu','Deals','Favs', 'Order',);

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
		$pattern = array( "/ /", "/'/", "/_/","/&/" );
		$replace = array( "_", null, "-","and" );

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

	public function setOpenStatus($status) {
		$store_status = $status;	
	}

	public function setClosesAt($date_time) {
		$sfile = new File('../status/sfile');
		$data = json_decode($sfile->read());
		$data['closes_at'] = $date_time;
		$sfile->write(json_encode($data));
		$sfile->close();
	}

	public function setOpensAt($date_time) {
		$sfile = new File('../status/sfile');
		$data = json_decode($sfile->read());
		$data['opens_at'] = $date_time;
		$sfile->write(json_encode($data));
		$sfile->close();
	}

	public function beforeRender() {
		if (preg_match('/(?i)msie [0-9]/',$_SERVER['HTTP_USER_AGENT'])) return $this->redirect( 'pages/no_service' );

		$statusFile = new File(APP.'status/sfile');
		$status = $statusFile->read(true, 'r');
		$this->set("store_status", $status); 
		$this->set("topnav", $this->topnav);
	}

	public function beforeFilter() {
		//Configure AuthComponent
		if (isset($this->request->params['pass'][0]) ) {
			if ($this->request->params['pass'][0] == 'home' && $this->Session->read('Auth.User')) {
				$this->redirect(___cakeUrl("users","home"));
			}
		}
		$this->Auth->loginAction = array(
		  'controller' => 'menu',
		  'action' => ''
		);
		$this->Auth->logoutRedirect = array(
		  'controller' => 'menu',
		  'action' => ''
	  	);
		$this->Auth->loginRedirect = ___cakeUrl("users", "edit", array('id' => $this->Auth->user('id')));
		$this->Auth->allow();
	}	

	static function verbose_query($db, $query, $fetch_all=false) {
		$result = $db->query( $query );
		switch ( gettype($result) ) {
			case 'object':
				if ( !get_class($result) || get_class($result) != 'mysqli_result' ) throw new Exception( mysqli_error($db) );
				break;
			case 'boolean':
				if ( $result !== true ) throw new Exception( mysqli_error($db) );
				break;
		}
		if ( $fetch_all ) {
			$result_array = array();
			for ( $i = 0; $i < $result->num_rows; $i++ ) {
				$result_array[ ] = $result->fetch_assoc();
			}
			return $result_array;
		} else {
			return $result;
		}
	}


	static function update_tables_from_file($opts_file, $menu_file) {
		$db = null;
		switch ($_SERVER['HTTP_ORIGIN']) {
			case 'http://localhost':
				$db = mysqli_connect( 'localhost', 'root', 'fr0gstar', 'xtreme' );
				break;
			case 'http://development-xtreme-pizza.ca':
				$db = mysqli_connect( 'development-xtreme.cvvd66fye9y7.us-east-1.rds.amazonaws.com', 'xtremeAdmin', 'xtremePizzaDBDB!', 'development_xtreme' );
				break;
			case 'http://www.xtreme-pizza.ca':
				$db = mysqli_connect( 'xtreme.cvvd66fye9y7.us-east-1.rds.amazonaws.com', 'xtremeAdmin', 'xtremePizzaDBDB!', 'xtreme' );
				break;
			default:
				echo "got to default :(";
				die();
		}
		$primary_orbcats = array('APPETIZERS', 'ASSORTED FINGERS', 'DRINKS & SAUCES', 'BURGERS', 'CHICKEN & CHIPS', 'DESSERTS', 'DONAIRS', 'FISH & CHIPS', 'FRIES & POUTINES', 'PANZAROTTIS', 'PASTA', 'PITAS & SANDWICHES', 'SALADS', 'SUBS' => array('', 'XTREME'), 'PIZZAS' => array('ORIGINAL', 'SUPER', 'SPECIALTY'));
		$drop_tables_query = "DROP TABLES `orbs`, `orbopts`, `orbs_orbopts`, `orbcats`, `orbs_orbcats`, `pricedicts`, `pricelists`;";
		$create_queries = array("CREATE TABLE IF NOT EXISTS `orbcats` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `primary_menu` tinyint(1) NOT NULL,
		  `title` varchar(255) NOT NULL,
		  `subtitle` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1",
		"CREATE TABLE IF NOT EXISTS `orbopts` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `pricelist_id` int(11) NOT NULL,
		  `title` varchar(255) DEFAULT NULL,
		  `meat` tinyint(1) DEFAULT NULL,
		  `veggie` tinyint(1) DEFAULT NULL,
		  `sauce` tinyint(1) DEFAULT NULL,
		  `cheese` tinyint(1) DEFAULT NULL,
		  `condiment` tinyint(1) DEFAULT NULL,
		  `burger` tinyint(1) DEFAULT NULL,
		  `salad` tinyint(1) DEFAULT NULL,
		  `pizza` tinyint(1) DEFAULT NULL,
		  `premium` tinyint(1) DEFAULT NULL,
		  `pita` tinyint(1) DEFAULT NULL,
		  `subs` tinyint(1) DEFAULT NULL,
		  `donair` tinyint(1) DEFAULT NULL,
		  `nacho` tinyint(1) DEFAULT NULL,
		  `poutines` tinyint(1) NOT NULL,
		  `fingers` tinyint(1) NOT NULL,
		  `exception_products` varchar(255) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1",
		"CREATE TABLE IF NOT EXISTS `orbs` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(255) NOT NULL,
		  `description` text NOT NULL,
		  `pricedict_id` int(11) NOT NULL,
		  `pricelist_id` int(11) NOT NULL,
		  `opt_count` int(11) NOT NULL,
		  `premium_count` int(11) NOT NULL,
		  `config` text NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `pricelabels_id` (`pricedict_id`),
		  KEY `pricelist_id` (`pricelist_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1",
		"CREATE TABLE IF NOT EXISTS `orbs_orbcats` (
		  `orb_id` int(11) NOT NULL,
		  `orbcat_id` int(11) NOT NULL,
		  KEY `orb_id` (`orb_id`,`orbcat_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1",
		"CREATE TABLE IF NOT EXISTS `orbs_orbopts` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `orb_id` int(11) NOT NULL,
		  `orbopt_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `orb_id` (`orb_id`),
		  KEY `orbextra_id` (`orbopt_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1",
		"CREATE TABLE IF NOT EXISTS `pricedicts` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `l1` varchar(32) NOT NULL,
		  `l2` varchar(32) DEFAULT NULL,
		  `l3` varchar(32) DEFAULT NULL,
		  `l4` varchar(32) DEFAULT NULL,
		  `l5` varchar(32) DEFAULT NULL,
		  `l6` varchar(32) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1",
		"CREATE TABLE IF NOT EXISTS `pricelists` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `p1` float NOT NULL,
		  `p2` float DEFAULT NULL,
		  `p3` float DEFAULT NULL,
		  `p4` float DEFAULT NULL,
		  `p5` float DEFAULT NULL,
		  `p6` float DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");

		$result = AppController::verbose_query( $db, $drop_tables_query );

		foreach ($create_queries as $q) { AppController::verbose_query($db, $q); }

		// ORBOPTS
		$opts = explode("\n", file_get_contents($opts_file));
		$opts = array_slice($opts, 1, 56);

		foreach($opts as $opt) {
			$opt = explode("\t", $opt);
			$opt_query_str = "INSERT INTO `orbopts` (`pricelist_id`, `title`, `meat`, `veggie`,`sauce`, `cheese`,`condiment`,`burger`,`salad`, `pizza`,`premium`,`pita`, `subs`, `donair`, `nacho`, `poutines`, `fingers`, `exception_products`) VALUES (-1, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '')";
			$opt_query_str = sprintf($opt_query_str, $opt[0], 	$opt[1], $opt[2], $opt[3], $opt[4], $opt[5], $opt[6], $opt[7], $opt[8], $opt[9], $opt[10], $opt[11], $opt[12], $opt[13], $opt[14], $opt[15]);
			AppController::verbose_query($db, $opt_query_str);
		}

		$xtreme_data = explode("\n", file_get_contents($menu_file));
		$xtreme_data = array_slice($xtreme_data, 2);
		foreach ($xtreme_data as $i => $row) {
			$orb = explode("\t", $row);

			// ORBS
			if ($orb[13] == "FALSE") $orb[13] = ""; // description set to blank str.
			$orb_query_str = "INSERT INTO `orbs` (`title`,`description`,`pricedict_id`, `pricelist_id`, `opt_count`, `premium_count`, `config`) VALUES ('%s','%s', %s, %s, %s, %s, '')";
			$orb_query = sprintf($orb_query_str, $orb[0], mysqli_real_escape_string($db, $orb[13]), -1, -1, 0, 0);
			AppController::verbose_query($db, $orb_query);
			$orb_id = $db->insert_id;

			// ORBCATS & ORBS_ORBCATS
			if ($orb[7] == "FALSE") $orb[7] = '';
			$orbcat_query_str = sprintf("SELECT `id` FROM `orbcats` WHERE `orbcats`.`title` = '%s' AND `orbcats`.`subtitle` = '%s'", $orb[6], $orb[7]);
			$orbcat_id = null;
			$orbcat_id = AppController::verbose_query($db, $orbcat_query_str, true);
			if ( !empty($orbcat_id) ) {
				$orbcat_id = $orbcat_id[0]['id'];
			} else {
				$q = sprintf("INSERT INTO `orbcats` (`primary_menu`, `title`, `subtitle`) VALUES (0, '%s', '%s')", $orb[6], $orb[7]);
				AppController::verbose_query($db, $q);
				$orbcat_id = $db->insert_id;
			}
			$db->query(sprintf("INSERT INTO `orbs_orbcats` (`orb_id`, `orbcat_id`) VALUES (%s, %s)", $orb_id, $orbcat_id));

			// ORBS_ORBOPTS
			$orb_flag_labels = array("burger", "salad",	"pizza", "pita", "subs", "donair", "nacho", "poutines", "fingers");
			$orb_flags = array_slice($orb, -9);
			$orb_flags = array_combine($orb_flag_labels, $orb_flags);
			$opt_search_str = "SELECT `id` FROM `orbopts` WHERE ";
			$included_flags = 0;
			foreach ($orb_flags as $flag => $value) {
				if ($value == "TRUE")  {
					$included_flags++;
					$opt_search_str .= $included_flags > 1 ? " AND `orbopts`.`$flag` = 1" : "`orbopts`.`$flag` = 1";
				}
			}
			if ($included_flags > 0) {
				$matched_opts = AppController::verbose_query($db, $orbcat_query_str, true);
				foreach($matched_opts as $opt) {
					$opt_q = sprintf('INSERT INTO `orbs_orbopts` (`orb_id`, `orbopt_id`) VALUES (%s, %s)', $orb_id, $opt['id']);
					AppController::verbose_query($db, $opt_q);
				}
			}
			// PRICELISTS
			$pl = array_slice($orb, 1,5);
			$price_list_query_str = "SELECT	`id` FROM `pricelists` WHERE ";
			$price_list_vals = 0;
			foreach ($pl as $i => $val) {
				if ($pl[$i] == "FALSE") {
					$pl[$i] = null;
				} else {
					$price_list_vals++;
					$j = $i + 1;
					$price_list_query_str .= 	$price_list_vals > 1 ? " AND `p$j` = $val" : "`p$j` = $val";
				}
			}

			$price_list_id = AppController::verbose_query($db, $price_list_query_str, true);
			if ( !empty($price_list_id) ) {
				$price_list_id = $price_list_id[0]['id'];
			} else {
				$price_list_query_str = "INSERT INTO `pricelists` %s VALUES % s";
				$fields = "(";
				$field_count = 0;
				$values = "(";
				foreach($pl as $i => $val) {
					if ($val) {
						$field_count++;
						$j = $i + 1;
						$fields .= $field_count > 1 ? ", `p$j`" :  "`p$j`";
						$values .= $field_count > 1 ? ", $val" : "$val";
					}
				}
				$fields .= ") ";
				$values .= ") ";
				$price_list_query_str = sprintf($price_list_query_str, $fields, $values);
				AppController::verbose_query($db, $price_list_query_str);
				$price_list_id = $db->insert_id;
			}
			$update_orb_price_list = sprintf("UPDATE `orbs` SET `orbs`.`pricelist_id` = %s WHERE `orbs`.`id` = %s", $price_list_id, $orb_id);
			AppController::verbose_query($db, $update_orb_price_list);

			// PRICEDICTS
			$pd = array_slice($orb, 8,5);

			$price_dict_query_str = "SELECT	`id` FROM `pricedicts` WHERE ";
			$price_dict_vals = 0;
			foreach ($pd as $i => $val) {
				if ($pd[$i] == "FALSE") {
					$pd[$i] = '';
				} else {
					$price_dict_vals++;
					$j = $i + 1;
					$price_dict_query_str .= $price_dict_vals > 1 ? " AND `l$j` = '$val'" : "`l$j` = '$val'";
				}
			}

			$price_dict_id =  AppController::verbose_query($db, $price_dict_query_str, true);
			if ( !empty($price_dict_id) ) {
				$price_dict_id = $price_dict_id[0]['id'];
			} else {
				$price_dict_query_str = "INSERT INTO `pricedicts` (`l1`, `l2`, `l3`, `l4`, `l5`, `l6`) VALUES ('%s', '%s', '%s', '%s', '%s', '')";
				AppController::verbose_query($db, sprintf($price_dict_query_str, $pd[0], $pd[1], $pd[2], $pd[3], $pd[4]));
				$price_dict_id = $db->insert_id;
			}
			AppController::verbose_query($db, "UPDATE `orbs` SET `pricedict_id` = $price_dict_id WHERE `orbs`.`id` = $orb_id");
		}
		// reset primary menu orbcats
		foreach ($primary_orbcats as $key => $val) {
			$query = "";
			$title = null;
			$subtitles = null;
			if ( !is_array($val) ) {
				$title = $val;
				$subtitles = array('');
			} else {
				$title = $key;
				$subtitles = $val;
			}
			for ($i=0; $i<count($subtitles); $i++) {
				$query = sprintf( "UPDATE `orbcats` SET `primary_menu` = 1 WHERE `title` = '%s' AND `subtitle` = '%s'", $title, $subtitles[ $i ] );
				AppController::verbose_query($db, $query );
			}
		}
		return true;
	}
}
