<?php
App::uses('AppController', 'Controller');
/**
 * Orbcats Controller
 *
 * @property Orbcat $Orbcat
 * @property PaginatorComponent $Paginator
 */
class OrbcatsController extends AppController {

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
		if ($this->request->is("ajax")) $this->layout = "ajax";
		$here = 'Menu';
		$active_orb = false;

		$orbcat_id = (!$orbcat_id || !$this->Orbcat->exists($orbcat_id)) ? 1 : $orbcat_id; // default to pizza if null
		$this->Orbcat->id = $orbcat_id;

		$active_orbcat = array(
			"id" => $orbcat_id,
			"name" => str_replace("XTREME", "", ucwords($this->Orbcat->field('title', array('`orbcat`.`id`' => $orbcat_id)))),
		    "orbs" => $this->Orbcat->find('all', array('recursive' => 1, "conditions" => array("`Orbcat`.`id`" => $orbcat_id))),
		    "orb_card" => null
		);
		if (!$orb_id || !$this->Orbcat->Orb->exists($orb_id)  ) {
			$active_orb = $active_orbcat['orbs'][0]; // orbcard is 1st orb if not set; else, see loop below
		}

		$active_orbcat['orbs'] = $active_orbcat['orbs'][0]['Orb']; // truncate to just orbs, remove OrbCat
		$orbcats_list = $this->Orbcat->find('list', array('conditions' => array('`Orbcat`.`primary_menu`' => true)));  // for actual orbcat menu

		// decode json in active orbs list
		foreach($active_orbcat['orbs'] as $i => $orb) {
			$orb['price_matrix'] = json_decode($orb['price_matrix'], true);
			$orb['config'] = json_decode($orb['config'], true);
			$orb['url'] = sprintf("menuitem/%s", $orb['id']);
			$active_orbcat['orbs'][$i] = $orb;
			if ($orb['id'] == $orb_id) $active_orbcat["orb_card"] = $orb; // active orb set here is orb requested
		}
		if ($active_orbcat["orb_card"] == null) { $active_orbcat["orb_card"] = $active_orbcat['orbs'][0];}

		$min_orb_count = 5;
		$empty_orb = array("id" => -1,
		                   "title" =>
			               "empty_orb",
		                   "subtitle" => false,
		                   "description" => false,
		                   "price_matrix" => false,
		                   "config" => false,
		                   "url" => false);
		if ( count($active_orbcat['orbs']) < $min_orb_count) {
			// fills active orb menu with dummy orbs
			while (count($active_orbcat['orbs'])  != $min_orb_count) {
				array_push($active_orbcat['orbs'], $empty_orb);
			}
		}

		// todo: find out where this is called; I think it's for ajax-loading the orbslist of a new orbcat choice?
		if ($return) {
			$this->autorender = false;
			return json_encode($active_orbcat['orbs']);
		}

		$this->set(compact('active_orbcat','orbcats_list','here'));
	}
}
