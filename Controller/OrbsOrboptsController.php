<?php
App::uses('AppController', 'Controller');
/**
 * OrbsOrbopts Controller
 *
 * @property OrbsOrbopt $OrbsOrbopt
 * @property PaginatorComponent $Paginator
 */
class OrbsOrboptsController extends AppController {

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
		$this->OrbsOrbopt->recursive = 0;
		$this->set('orbsOrbopts', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrbsOrbopt->exists($id)) {
			throw new NotFoundException(__('Invalid orbs orbopt'));
		}
		$options = array('conditions' => array('OrbsOrbopt.' . $this->OrbsOrbopt->primaryKey => $id));
		$this->set('orbsOrbopt', $this->OrbsOrbopt->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrbsOrbopt->create();
			if ($this->OrbsOrbopt->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs orbopt has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs orbopt could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->OrbsOrbopt->Orb->find('list');
		$orbopts = $this->OrbsOrbopt->Orbopt->find('list');
		$this->set(compact('orbs', 'orbopts'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrbsOrbopt->exists($id)) {
			throw new NotFoundException(__('Invalid orbs orbopt'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrbsOrbopt->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs orbopt has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs orbopt could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrbsOrbopt.' . $this->OrbsOrbopt->primaryKey => $id));
			$this->request->data = $this->OrbsOrbopt->find('first', $options);
		}
		$orbs = $this->OrbsOrbopt->Orb->find('list');
		$orbopts = $this->OrbsOrbopt->Orbopt->find('list');
		$this->set(compact('orbs', 'orbopts'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrbsOrbopt->id = $id;
		if (!$this->OrbsOrbopt->exists()) {
			throw new NotFoundException(__('Invalid orbs orbopt'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrbsOrbopt->delete()) {
			$this->Session->setFlash(__('The orbs orbopt has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbs orbopt could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function fetch_default_opts($orb_id) {
		if ( $this->request->is('get') ) {
			$conditions =  array('conditions' => array('orb_id' => $orb_id,  'default' => true), 'recursive' => -1);
			return Hash::extract($this->OrbsOrbopt->find('all', $conditions), "{n}.OrbsOrbopt.id");
		} else {
			$this->redirect(array('orbcats', 'menu'));
		}
	}
}
