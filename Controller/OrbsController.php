<?php
App::uses('AppController', 'Controller', 'ConnectionManager');
/**
 * Orbs Controller
 *
 * @property Orb $Orb
 * @property PaginatorComponent $Paginator
 */
class OrbsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Orb->recursive = 0;
		$this->set('orbs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Orb->exists($id)) {
			throw new NotFoundException(__('Invalid orb'));
		}
		// ajax only if requested as an orbcard
		$options = array('conditions' => array('Orb.' . $this->Orb->primaryKey => $id));
		$this->set('orb', $this->Orb->find('first', $options));
		if ($this->request->is('ajax') ) {
			$this->render('orbcard', 'ajax');
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Orb->create();
			if ($this->Orb->save($this->request->data)) {
				$this->Session->setFlash(__('The orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orb could not be saved. Please, try again.'));
			}
		}
		if  ($this->request->is('ajax') ) $this->layout = "ajax";
		$orbcats = $this->Orb->Orbcat->find('list');
		$orbextras = $this->Orb->Orbextra->find('list');
		$this->set(compact('orbcats', 'orbextras'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Orb->exists($id)) {
			throw new NotFoundException(__('Invalid orb'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Orb->save($this->request->data)) {
				$this->Session->setFlash(__('The orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orb could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Orb.' . $this->Orb->primaryKey => $id));
			$this->request->data = $this->Orb->find('first', $options);
		}
		$orbcats = $this->Orb->Orbcat->find('list');
		$orbextras = $this->Orb->Orbextra->find('list');
		$this->set(compact('orbcats', 'orbextras'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Orb->id = $id;
		if (!$this->Orb->exists()) {
			throw new NotFoundException(__('Invalid orb'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Orb->delete()) {
			$this->Session->setFlash(__('The orb has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orb could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function menu_item($id) {
		if ($this->request->is('ajax') && $this->Orb->exists($id) || true) {
			$filters =  array("premium" => 0, "meat" => 0, "veggie" => 0, "sauce" => 0, "cheese" => 0);
			$this->layout = 'ajax';
			$orb = $this->Orb->findById($id);

			unset($orb['Orbcat']);
			unset($orb['Order']);
			unset($orb['Orb']['created']);
			unset($orb['Orb']['modified']);
			$orb['Orb']['price_table'] = array_filter(array_slice(array_combine($orb['Pricedict'], $orb['Pricelist']), 1));
			$orb['Orb']['Orbopt'] = $orb['Orbopt'];
			$orb = $orb['Orb'];

			foreach($orb['Orbopt'] as $opt) {
				foreach ($filters as $filter => $count) {
					if ($opt[$filter]) $filters[$filter]++;
				}
			}
			foreach ($filters as $filter => $count) {
				if ($count == 0) unset($filters[$filter]);
			}

			$orb['filters'] = array_keys($filters);

			$this->set(compact('orb'));
			$this->render();
		} else {
			$this->redirect(___cakeUrl('orbcats', 'menu'));
		}
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('menu_item, upload_menu');
	}

/**
 * csv_to_menu
 */
	public function csv_to_menu($menu_file, $opts_file) { 
		$db = mysqli_connect('development-xtreme.cvvd66fye9y7.us-east-1.rds.amazonaws.com','xtremeAdmin','xtremePizzaDBDB!','development_xtreme');
		//ConnectionManager::getDataSource('default');
		$db->query("START TRANSACTION;");

		try {	
			// ORBOPTS
			$opts = explode("\n", file_get_contents($opts_file));
			$opts = array_slice($opts, 1, 56);
			foreach($opts as $opt) {
				$opt = explode("\t", $opt);
				$opt_query_str = "INSERT INTO `orbopts` (`pricelist_id`, `title`, `meat`, `veggie`,`sauce`, `cheese`,`condiment`,`burger`,`salad`, `pizza`,`premium`,`pita`, `subs`, `donair`, `nacho`, `poutines`, `fingers`, `exception_products`) VALUES (-1, '%s', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '')";

				$opt_query_str = sprintf($opt_query_str, $opt[0], 	$opt[1], $opt[2], $opt[3], $opt[4], $opt[5], $opt[6], $opt[7], $opt[8], $opt[9], $opt[10], $opt[11], $opt[12], $opt[13], $opt[14], $opt[15]);				
				$db->query($opt_query_str);
			}

			$xtreme_data = explode("\n", file_get_contents($menu_file));
			$xtreme_data = array_slice($xtreme_data, 2); 
			foreach ($xtreme_data as $i => $row) {
				$orb = explode("\t", $row);
				
				// ORBS
				if ($orb[13] == "FALSE") $orb[13] = ""; // description set to blank str.
				$orb_query_str = "INSERT INTO `orbs` (`title`,`description`,`pricedict_id`, `pricelist_id`, `opt_count`, `premium_count`, `config`) VALUES ('%s','%s', %s, %s, %s, %s, '')";
				$orb_query = sprintf($orb_query_str, $orb[0], $orb[13], -1, -1, 0, 0);
				$db->query($orb_query);
				$orb_id = $db->insert_id;
				
				// ORBCATS & ORBS_ORBCATS
				if ($orb[7] == "FALSE") $orb[7] = '';
				$orbcat_query_str = sprintf("SELECT `id` FROM `orbcats` WHERE `orbcats`.`title` = '%s' AND `orbcats`.`subtitle` = '%s'", $orb[6], $orb[7]);
				$orbcat_id = null;
				$orbcat_id = $db->query($orbcat_query_str)->fetch_all();
				if ( !empty($orbcat_id) ) {
					$orbcat_id = $orbcat_id[0][0];
				} else {
					$db->query(sprintf("INSERT INTO `orbcats` (`primary_menu`, `title`, `subtitle`) VALUES (1, '%s', '%s')", $orb[6], $orb[7]));
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
					$matched_opts = $db->query($opt_search_str)->fetch_all();
					foreach($matched_opts as $opt) {
						$db->query(sprintf('INSERT INTO `orbs_orbopts` (`orb_id`, `orbopt_id`) VALUES (%s, %s)', $orb_id, $opt[0]));
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

				$price_list_id =  $db->query($price_list_query_str)->fetch_all();
				echo "after fetch all: <br />";
				pr($price_list_id);
				if ( !empty($price_list_id) ) {
					echo "fetchall() !empty: <br />";
					$price_list_id = $price_list_id[0][0];
					pr($price_list_id);
				} else {
						echo "fetchall()   WAS empty: <br />";
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
					pr($price_list_query_str);
					$db->query($price_list_query_str);
					$price_list_id = $db->insert_id;
					pr($price_list_id);
				}
				$update_orb_price_list = "UPDATE `orbs` SET `pricelist_id` = $price_list_id WHERE `orbs`.`id` = $orb_id";
				pr($update_orb_price_list);
				echo "<hr />";
				$db->query($update_orb_price_list);
				
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

				$price_dict_id =  $db->query($price_dict_query_str)->fetch_all();
				if ( !empty($price_dict_id) ) {
					$price_dict_id = $price_dict_id[0][0];
				} else {
					$price_dict_query_str = "INSERT INTO `pricedicts` (`l1`, `l2`, `l3`, `l4`, `l5`, `l6`) VALUES ('%s', '%s', '%s', '%s', '%s', '')";
					$db->query(sprintf($price_dict_query_str, $pd[0], $pd[1], $pd[2], $pd[3], $pd[4]));
					$price_dict_id = $db->insert_id;
				}
				$db->query("UPDATE `orbs` SET `pricedict_id` = $price_dict_id WHERE `orbs`.`id` = $orb_id");
			}
		} catch (Exception $e) {
			db($db);
			$db->query("ROLLBACK;");
			return('{success: false, error: '+$e+'}');
		}

		$db->query("ROLLBACK;");
		db('Success');
		return('{success: true, error: null}');
	}


/**
 * upload a csv to the database to set menu parameters
 */
	public function upload_menu() {
		//$this->set('response', 
		if ($this->request->is('post')) {
			$this->csv_to_menu($this->request->data['Orb']['Menu File']['tmp_name'],
				$this->request->data['Orb']['Opts File']['tmp_name']);
		}
	}

	public function before_filter() {
		parent::before_filter();

		$this->Auth->allow('csv_to_menu', 'upload_menu', 'menu_item');
	}
}
