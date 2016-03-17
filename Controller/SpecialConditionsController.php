<?php
App::uses('AppController', 'Controller');
/**
 * SpecialConditions Controller
 *
 * @property SpecialCondition $SpecialCondition
 * @property PaginatorComponent $Paginator
 */
class SpecialConditionsController extends AppController {

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
		$this->SpecialCondition->recursive = 0;
		$this->set('specialConditions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SpecialCondition->exists($id)) {
			throw new NotFoundException(__('Invalid special condition'));
		}
		$options = array('conditions' => array('SpecialCondition.' . $this->SpecialCondition->primaryKey => $id));
		$this->set('specialCondition', $this->SpecialCondition->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SpecialCondition->create();
			if ($this->SpecialCondition->save($this->request->data)) {
				$this->Session->setFlash(__('The special condition has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special condition could not be saved. Please, try again.'));
			}
		}
		$specials = $this->SpecialCondition->Special->find('list');
		$orblists = $this->SpecialCondition->Orblist->find('list');
		$orbcats = $this->SpecialCondition->Orbcat->find('list');
		$orbs = $this->SpecialCondition->Orb->find('list');
		$this->set(compact('specials', 'orblists', 'orbcats', 'orbs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SpecialCondition->exists($id)) {
			throw new NotFoundException(__('Invalid special condition'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SpecialCondition->save($this->request->data)) {
				$this->Session->setFlash(__('The special condition has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special condition could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SpecialCondition.' . $this->SpecialCondition->primaryKey => $id));
			$this->request->data = $this->SpecialCondition->find('first', $options);
		}
		$specials = $this->SpecialCondition->Special->find('list');
		$orblists = $this->SpecialCondition->Orblist->find('list');
		$orbcats = $this->SpecialCondition->Orbcat->find('list');
		$orbs = $this->SpecialCondition->Orb->find('list');
		$this->set(compact('specials', 'orblists', 'orbcats', 'orbs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SpecialCondition->id = $id;
		if (!$this->SpecialCondition->exists()) {
			throw new NotFoundException(__('Invalid special condition'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SpecialCondition->delete()) {
			$this->Session->setFlash(__('The special condition has been deleted.'));
		} else {
			$this->Session->setFlash(__('The special condition could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
