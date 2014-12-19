<?php
App::uses('AppController', 'Controller');
/**
 * Orbcats Controller
 *
 * @property Orbcat $Orbcat
 * @property PaginatorComponent $Paginator
 */
class OrbcatsController extends AppController {
	private $empty_orb = array("id" => -1,
	                   "title" =>
		               "empty_orb",
	                   "subtitle" => false,
	                   "description" => false,
	                   "price_matrix" => false,
	                   "config" => false,
	                   "url" => false);

	private $min_orb_count = 5;

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
		$this->Orbcat->recursive = 0;
		$this->set('orbcats', $this->Paginator->paginate());

	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Orbcat->exists($id)) {
			throw new NotFoundException(__('Invalid orbcat'));
		}
		$options = array('conditions' => array('Orbcat.' . $this->Orbcat->primaryKey => $id));
		$this->set('orbcat', $this->Orbcat->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Orbcat->create();
			if ($this->Orbcat->save($this->request->data)) {
				$this->Session->setFlash(__('The orbcat has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbcat could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->Orbcat->Orb->find('list');
		$this->set(compact('orbs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Orbcat->exists($id)) {
			throw new NotFoundException(__('Invalid orbcat'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Orbcat->save($this->request->data)) {
				$this->Session->setFlash(__('The orbcat has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbcat could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Orbcat.' . $this->Orbcat->primaryKey => $id));
			$this->request->data = $this->Orbcat->find('first', $options);
		}
		$orbs = $this->Orbcat->Orb->find('list');
		$this->set(compact('orbs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Orbcat->id = $id;
		if (!$this->Orbcat->exists()) {
			throw new NotFoundException(__('Invalid orbcat'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Orbcat->delete()) {
			$this->Session->setFlash(__('The orbcat has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbcat could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function menu($orbcat_id = null, $orb_id = null, $return = false) {
		$page_name = 'menu';

		$orbcat_id = (!$orbcat_id || !$this->Orbcat->exists($orbcat_id)) ? 1 : $orbcat_id; // default to pizza if null
		$this->Orbcat->id = $orbcat_id;

		$active_orbcat_title = strtoupper($this->Orbcat->field('title', array('`Orbcat`.`id`' => $orbcat_id)));
		if ($active_orbcat_title != "XTREME SUBS") $active_orbcat_title = str_replace("XTREME", "", $active_orbcat_title);
		$active_orbcat = array(
			"id" => $orbcat_id,
			"name" => $active_orbcat_title,
		    "orbs" => $this->Orbcat->find('all', array('recursive' => 2, "conditions" => array("`Orbcat`.`id`" => $orbcat_id))),
		    "orb_card" => null
		);

		$active_orbcat['orbs'] = $active_orbcat['orbs'][0]['Orb']; // truncate to just orbs, remove OrbCat
		$orbcats_list = $this->Orbcat->find('list', array('conditions' => array('`Orbcat`.`primary_menu`' => true)));  // for actual orbcat menu
		foreach($active_orbcat['orbs'] as $i => $orb) {
			// next line drops the 'id' field after combining the pricelist & pricedict into a table
			$orb['price_table'] = array_filter(array_slice(array_combine($orb['Pricedict'], $orb['Pricelist']), 1));
			$active_orbcat['orbs'][$i] = $orb;
			if ($orb['id'] == $orb_id) $active_orbcat["orb_card"] = $orb; // active orb set here is orb requested
		}
		if ($active_orbcat["orb_card"] == null) { $active_orbcat["orb_card"] = $active_orbcat['orbs'][0];}



		if ( count($active_orbcat['orbs']) < $this->min_orb_count) {
			// fills active orb menu with dummy orbs
			while (count($active_orbcat['orbs']) != $this->min_orb_count) {
				array_push($active_orbcat['orbs'], $this->empty_orb);
			}
		}

		$this->set(compact('active_orbcat','orbcats_list','page_name'));
		if ($this->request->is("ajax")) { $this->render('ajax_menu', 'ajax'); }
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');
	}

}
