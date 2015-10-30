<?php
App::uses('AppController', 'Controller');
/**
 * Specials Controller
 *
 * @property Special $Special
 * @property PaginatorComponent $Paginator
 */
class SpecialsController extends AppController {

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
		$this->Special->recursive = 0;
		$this->set('specials', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Special->exists($id)) {
			throw new NotFoundException(__('Invalid special'));
		}
		$options = array('conditions' => array('Special.' . $this->Special->primaryKey => $id));
		$this->set('special', $this->Special->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Special->create();
			if ($this->Special->save($this->request->data)) {
				$this->Session->setFlash(__('The special has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->Special->Orb->find('list');
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
		if (!$this->Special->exists($id)) {
			throw new NotFoundException(__('Invalid special'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Special->save($this->request->data)) {
				$this->Session->setFlash(__('The special has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Special.' . $this->Special->primaryKey => $id));
			$this->request->data = $this->Special->find('first', $options);
		}
		$orbs = $this->Special->Orb->find('list');
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
		$this->Special->id = $id;
		if (!$this->Special->exists()) {
			throw new NotFoundException(__('Invalid special'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Special->delete()) {
			$this->Session->setFlash(__('The special has been deleted.'));
		} else {
			$this->Session->setFlash(__('The special could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
