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

	public function menu($orbcat) {
		}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'view');
	}
}
