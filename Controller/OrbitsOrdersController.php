<?php
App::uses('AppController', 'Controller');
/**
 * OrbitsOrders Controller
 *
 * @property OrbitsOrder $OrbitsOrder
 * @property PaginatorComponent $Paginator
 */
class OrbitsOrdersController extends AppController {

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
		$this->OrbitsOrder->recursive = 0;
		$this->set('orbitsOrders', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrbitsOrder->exists($id)) {
			throw new NotFoundException(__('Invalid orbits order'));
		}
		$options = array('conditions' => array('OrbitsOrder.' . $this->OrbitsOrder->primaryKey => $id));
		$this->set('orbitsOrder', $this->OrbitsOrder->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrbitsOrder->create();
			if ($this->OrbitsOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The orbits order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbits order could not be saved. Please, try again.'));
			}
		}
		$orbits = $this->OrbitsOrder->Orbit->find('list');
		$orders = $this->OrbitsOrder->Order->find('list');
		$this->set(compact('orbits', 'orders'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrbitsOrder->exists($id)) {
			throw new NotFoundException(__('Invalid orbits order'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrbitsOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The orbits order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbits order could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrbitsOrder.' . $this->OrbitsOrder->primaryKey => $id));
			$this->request->data = $this->OrbitsOrder->find('first', $options);
		}
		$orbits = $this->OrbitsOrder->Orbit->find('list');
		$orders = $this->OrbitsOrder->Order->find('list');
		$this->set(compact('orbits', 'orders'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrbitsOrder->id = $id;
		if (!$this->OrbitsOrder->exists()) {
			throw new NotFoundException(__('Invalid orbits order'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrbitsOrder->delete()) {
			$this->Session->setFlash(__('The orbits order has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbits order could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
