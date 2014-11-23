<?php
App::uses('AppController', 'Controller');
/**
 * Orbextras Controller
 *
 * @property Orbextra $Orbextra
 * @property PaginatorComponent $Paginator
 */
class OrbextrasController extends AppController {

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
		$this->Orbextra->recursive = 0;
		$this->set('orbextras', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Orbextra->exists($id)) {
			throw new NotFoundException(__('Invalid orbextra'));
		}
		$options = array('conditions' => array('Orbextra.' . $this->Orbextra->primaryKey => $id));
		$this->set('orbextra', $this->Orbextra->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Orbextra->create();
			if ($this->Orbextra->save($this->request->data)) {
				$this->Session->setFlash(__('The orbextra has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbextra could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->Orbextra->Orb->find('list');
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
		if (!$this->Orbextra->exists($id)) {
			throw new NotFoundException(__('Invalid orbextra'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Orbextra->save($this->request->data)) {
				$this->Session->setFlash(__('The orbextra has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbextra could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Orbextra.' . $this->Orbextra->primaryKey => $id));
			$this->request->data = $this->Orbextra->find('first', $options);
		}
		$orbs = $this->Orbextra->Orb->find('list');
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
		$this->Orbextra->id = $id;
		if (!$this->Orbextra->exists()) {
			throw new NotFoundException(__('Invalid orbextra'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Orbextra->delete()) {
			$this->Session->setFlash(__('The orbextra has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbextra could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
