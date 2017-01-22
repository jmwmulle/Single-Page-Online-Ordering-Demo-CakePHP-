<?php
App::uses('AppController', 'Controller');
/**
 * OrdersOrbs Controller
 *
 * @property OrdersOrb $OrdersOrb
 * @property PaginatorComponent $Paginator
 */
class OrdersOrbsController extends AppController {

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
		$this->OrdersOrb->recursive = 0;
		$this->set('ordersOrbs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrdersOrb->exists($id)) {
			throw new NotFoundException(__('Invalid orders orb'));
		}
		$options = array('conditions' => array('OrdersOrb.' . $this->OrdersOrb->primaryKey => $id));
		$this->set('ordersOrb', $this->OrdersOrb->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrdersOrb->create();
			if ($this->OrdersOrb->save($this->request->data)) {
				$this->Session->setFlash(__('The orders orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orders orb could not be saved. Please, try again.'));
			}
		}
		$orders = $this->OrdersOrb->Order->find('list');
		$orbs = $this->OrdersOrb->Orb->find('list');
		$this->set(compact('orders', 'orbs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrdersOrb->exists($id)) {
			throw new NotFoundException(__('Invalid orders orb'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrdersOrb->save($this->request->data)) {
				$this->Session->setFlash(__('The orders orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orders orb could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrdersOrb.' . $this->OrdersOrb->primaryKey => $id));
			$this->request->data = $this->OrdersOrb->find('first', $options);
		}
		$orders = $this->OrdersOrb->Order->find('list');
		$orbs = $this->OrdersOrb->Orb->find('list');
		$this->set(compact('orders', 'orbs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrdersOrb->id = $id;
		if (!$this->OrdersOrb->exists()) {
			throw new NotFoundException(__('Invalid orders orb'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrdersOrb->delete()) {
			$this->Session->setFlash(__('The orders orb has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orders orb could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
