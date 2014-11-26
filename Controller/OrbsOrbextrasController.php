<?php
App::uses('AppController', 'Controller');
/**
 * OrbsOrbextras Controller
 *
 * @property OrbsOrbextra $OrbsOrbextra
 * @property PaginatorComponent $Paginator
 */
class OrbsOrbextrasController extends AppController {

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
		$this->OrbsOrbextra->recursive = 0;
		$this->set('orbsOrbextras', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrbsOrbextra->exists($id)) {
			throw new NotFoundException(__('Invalid orbs orbextra'));
		}
		$options = array('conditions' => array('OrbsOrbextra.' . $this->OrbsOrbextra->primaryKey => $id));
		$this->set('orbsOrbextra', $this->OrbsOrbextra->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrbsOrbextra->create();
			if ($this->OrbsOrbextra->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs orbextra has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs orbextra could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->OrbsOrbextra->Orb->find('list');
		$orbextras = $this->OrbsOrbextra->Orbextra->find('list');
		$this->set(compact('orbs', 'orbextras'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrbsOrbextra->exists($id)) {
			throw new NotFoundException(__('Invalid orbs orbextra'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrbsOrbextra->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs orbextra has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs orbextra could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrbsOrbextra.' . $this->OrbsOrbextra->primaryKey => $id));
			$this->request->data = $this->OrbsOrbextra->find('first', $options);
		}
		$orbs = $this->OrbsOrbextra->Orb->find('list');
		$orbextras = $this->OrbsOrbextra->Orbextra->find('list');
		$this->set(compact('orbs', 'orbextras'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrbsOrbextra->id = $id;
		if (!$this->OrbsOrbextra->exists()) {
			throw new NotFoundException(__('Invalid orbs orbextra'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrbsOrbextra->delete()) {
			$this->Session->setFlash(__('The orbs orbextra has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbs orbextra could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
