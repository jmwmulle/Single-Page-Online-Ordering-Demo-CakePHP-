<?php
App::uses('AppController', 'Controller');
/**
 * OrbsOrders Controller
 *
 * @property OrbsOrder $OrbsOrder
 * @property PaginatorComponent $Paginator
 */
class OrbsOrdersController extends AppController {

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
		$this->OrbsOrder->recursive = 0;
		$this->set('orbsOrders', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrbsOrder->exists($id)) {
			throw new NotFoundException(__('Invalid orbs order'));
		}
		$options = array('conditions' => array('OrbsOrder.' . $this->OrbsOrder->primaryKey => $id));
		$this->set('orbsOrder', $this->OrbsOrder->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrbsOrder->create();
			if ($this->OrbsOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs order could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->OrbsOrder->Orb->find('list');
		$orders = $this->OrbsOrder->Order->find('list');
		$this->set(compact('orbs', 'orders'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrbsOrder->exists($id)) {
			throw new NotFoundException(__('Invalid orbs order'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrbsOrder->save($this->request->data)) {
				$this->Session->setFlash(__('The orbs order has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbs order could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrbsOrder.' . $this->OrbsOrder->primaryKey => $id));
			$this->request->data = $this->OrbsOrder->find('first', $options);
		}
		$orbs = $this->OrbsOrder->Orb->find('list');
		$orders = $this->OrbsOrder->Order->find('list');
		$this->set(compact('orbs', 'orders'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrbsOrder->id = $id;
		if (!$this->OrbsOrder->exists()) {
			throw new NotFoundException(__('Invalid orbs order'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrbsOrder->delete()) {
			$this->Session->setFlash(__('The orbs order has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbs order could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
