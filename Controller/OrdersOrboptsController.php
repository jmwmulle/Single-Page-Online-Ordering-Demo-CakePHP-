<?php
App::uses('AppController', 'Controller');
/**
 * OrdersOrbopts Controller
 *
 * @property OrdersOrbopt $OrdersOrbopt
 * @property PaginatorComponent $Paginator
 */
class OrdersOrboptsController extends AppController {

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
		$this->OrdersOrbopt->recursive = 0;
		$this->set('ordersOrbopts', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrdersOrbopt->exists($id)) {
			throw new NotFoundException(__('Invalid orders orbopt'));
		}
		$options = array('conditions' => array('OrdersOrbopt.' . $this->OrdersOrbopt->primaryKey => $id));
		$this->set('ordersOrbopt', $this->OrdersOrbopt->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrdersOrbopt->create();
			if ($this->OrdersOrbopt->save($this->request->data)) {
				$this->Session->setFlash(__('The orders orbopt has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orders orbopt could not be saved. Please, try again.'));
			}
		}
		$orders = $this->OrdersOrbopt->Order->find('list');
		$orbopts = $this->OrdersOrbopt->Orbopt->find('list');
		$this->set(compact('orders', 'orbopts'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrdersOrbopt->exists($id)) {
			throw new NotFoundException(__('Invalid orders orbopt'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrdersOrbopt->save($this->request->data)) {
				$this->Session->setFlash(__('The orders orbopt has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orders orbopt could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrdersOrbopt.' . $this->OrdersOrbopt->primaryKey => $id));
			$this->request->data = $this->OrdersOrbopt->find('first', $options);
		}
		$orders = $this->OrdersOrbopt->Order->find('list');
		$orbopts = $this->OrdersOrbopt->Orbopt->find('list');
		$this->set(compact('orders', 'orbopts'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrdersOrbopt->id = $id;
		if (!$this->OrdersOrbopt->exists()) {
			throw new NotFoundException(__('Invalid orders orbopt'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrdersOrbopt->delete()) {
			$this->Session->setFlash(__('The orders orbopt has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orders orbopt could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
