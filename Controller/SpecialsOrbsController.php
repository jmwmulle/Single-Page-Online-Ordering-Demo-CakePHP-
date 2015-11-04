<?php
App::uses('AppController', 'Controller');
/**
 * SpecialsOrbs Controller
 *
 * @property SpecialsOrb $SpecialsOrb
 * @property PaginatorComponent $Paginator
 */
class SpecialsOrbsController extends AppController {

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
		$this->SpecialsOrb->recursive = 0;
		$this->set('specialsOrbs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SpecialsOrb->exists($id)) {
			throw new NotFoundException(__('Invalid specials orb'));
		}
		$options = array('conditions' => array('SpecialsOrb.' . $this->SpecialsOrb->primaryKey => $id));
		$this->set('specialsOrb', $this->SpecialsOrb->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SpecialsOrb->create();
			if ($this->SpecialsOrb->save($this->request->data)) {
				$this->Session->setFlash(__('The specials orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The specials orb could not be saved. Please, try again.'));
			}
		}
		$specials = $this->SpecialsOrb->Special->find('list');
		$orbs = $this->SpecialsOrb->Orb->find('list');
		$this->set(compact('specials', 'orbs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SpecialsOrb->exists($id)) {
			throw new NotFoundException(__('Invalid specials orb'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SpecialsOrb->save($this->request->data)) {
				$this->Session->setFlash(__('The specials orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The specials orb could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SpecialsOrb.' . $this->SpecialsOrb->primaryKey => $id));
			$this->request->data = $this->SpecialsOrb->find('first', $options);
		}
		$specials = $this->SpecialsOrb->Special->find('list');
		$orbs = $this->SpecialsOrb->Orb->find('list');
		$this->set(compact('specials', 'orbs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SpecialsOrb->id = $id;
		if (!$this->SpecialsOrb->exists()) {
			throw new NotFoundException(__('Invalid specials orb'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SpecialsOrb->delete()) {
			$this->Session->setFlash(__('The specials orb has been deleted.'));
		} else {
			$this->Session->setFlash(__('The specials orb could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
