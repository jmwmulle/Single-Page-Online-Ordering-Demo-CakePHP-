<?php
App::uses('AppController', 'Controller');
/**
 * OrblistsOrbs Controller
 *
 * @property OrblistsOrb $OrblistsOrb
 * @property PaginatorComponent $Paginator
 */
class OrblistsOrbsController extends AppController {

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
		$this->OrblistsOrb->recursive = 0;
		$this->set('orblistsOrbs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrblistsOrb->exists($id)) {
			throw new NotFoundException(__('Invalid orblists orb'));
		}
		$options = array('conditions' => array('OrblistsOrb.' . $this->OrblistsOrb->primaryKey => $id));
		$this->set('orblistsOrb', $this->OrblistsOrb->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrblistsOrb->create();
			if ($this->OrblistsOrb->save($this->request->data)) {
				$this->Session->setFlash(__('The orblists orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orblists orb could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->OrblistsOrb->Orb->find('list');
		$orblists = $this->OrblistsOrb->Orblist->find('list');
		$this->set(compact('orbs', 'orblists'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrblistsOrb->exists($id)) {
			throw new NotFoundException(__('Invalid orblists orb'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrblistsOrb->save($this->request->data)) {
				$this->Session->setFlash(__('The orblists orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orblists orb could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrblistsOrb.' . $this->OrblistsOrb->primaryKey => $id));
			$this->request->data = $this->OrblistsOrb->find('first', $options);
		}
		$orbs = $this->OrblistsOrb->Orb->find('list');
		$orblists = $this->OrblistsOrb->Orblist->find('list');
		$this->set(compact('orbs', 'orblists'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrblistsOrb->id = $id;
		if (!$this->OrblistsOrb->exists()) {
			throw new NotFoundException(__('Invalid orblists orb'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrblistsOrb->delete()) {
			$this->Session->setFlash(__('The orblists orb has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orblists orb could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
