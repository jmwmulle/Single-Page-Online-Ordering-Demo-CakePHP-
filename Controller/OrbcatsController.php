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

	public function menu($id = null) {
		$active = (!$id || !$this->Orbcat->exists()) ? 1 : $id;
		$orbs = $this->Orbcat->find('all', array('recursive' => 1, "conditions" => array("`Orbcat`.`id`" => $active)));
		$subnav = $this->Orbcat->find('list');
		$toc = array();
		foreach($orbs[0]['Orb'] as $i => $o) {
			$toc[$o['id']] = $o['title'];
			$o['price_matrix'] = json_decode($o['price_matrix'], true);
			$o['config'] = json_decode($o['config'], true);
			$orbs[0]['Orb'][$i] = $o;
		}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');
		$orbs = $orbs[0]['Orb'];
		foreach($subnav as $i => $oc) {
			$subnav[$i] = array('label' => $oc,
			                     'url' => ___cakeUrl('orbcats','menu', $i),
			                     'active' => $i == $id ? true : false
			);
		}
		$here = 'Menu';
		$topnav = array("Menu", "Favs");
		$this->set(compact('active','orbs','here','topnav','subnav','toc'));
	}
}
