<?php
App::uses('AppController', 'Controller');
/**
 * OrbsOrbcats Controller
 *
 * @property OrbsOrbcat $OrbsOrbcat
 * @property PaginatorComponent $Paginator
 */
class OrbsOrbcatsController extends AppController {

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
		$this->OrbsOrbcat->recursive = 0;
		$this->set('orbsOrbcats', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrbsOrbcat->exists($id)) {
			throw new NotFoundException(__('Invalid orbs orbcat'));
		}
		$options = array('conditions' => array('OrbsOrbcat.' . $this->OrbsOrbcat->primaryKey => $id));
		$this->set('orbsOrbcat', $this->OrbsOrbcat->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrbsOrbcat->create();
			if ($this->OrbsOrbcat->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs orbcat has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs orbcat could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->OrbsOrbcat->Orb->find('list');
		$orbcats = $this->OrbsOrbcat->Orbcat->find('list');
		$this->set(compact('orbs', 'orbcats'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrbsOrbcat->exists($id)) {
			throw new NotFoundException(__('Invalid orbs orbcat'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrbsOrbcat->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs orbcat has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs orbcat could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrbsOrbcat.' . $this->OrbsOrbcat->primaryKey => $id));
			$this->request->data = $this->OrbsOrbcat->find('first', $options);
		}
		$orbs = $this->OrbsOrbcat->Orb->find('list');
		$orbcats = $this->OrbsOrbcat->Orbcat->find('list');
		$this->set(compact('orbs', 'orbcats'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrbsOrbcat->id = $id;
		if (!$this->OrbsOrbcat->exists()) {
			throw new NotFoundException(__('Invalid orbs orbcat'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrbsOrbcat->delete()) {
			$this->Session->setFlash(__('The orbs orbcat has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbs orbcat could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
