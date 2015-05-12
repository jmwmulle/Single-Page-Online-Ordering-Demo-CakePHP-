<?php
App::uses('AppController', 'Controller');
/**
 * OrboptsOptflags Controller
 *
 * @property OrboptsOptflag $OrboptsOptflag
 * @property PaginatorComponent $Paginator
 */
class OrboptsOptflagsController extends AppController {

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
		$this->OrboptsOptflag->recursive = 0;
		$this->set('orboptsOptflags', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrboptsOptflag->exists($id)) {
			throw new NotFoundException(__('Invalid orbopts optflag'));
		}
		$options = array('conditions' => array('OrboptsOptflag.' . $this->OrboptsOptflag->primaryKey => $id));
		$this->set('orboptsOptflag', $this->OrboptsOptflag->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrboptsOptflag->create();
			if ($this->OrboptsOptflag->save($this->request->data)) {
				$this->Session->setFlash(__('The orbopts optflag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbopts optflag could not be saved. Please, try again.'));
			}
		}
		$orbopts = $this->OrboptsOptflag->Orbopt->find('list');
		$optflags = $this->OrboptsOptflag->Optflag->find('list');
		$this->set(compact('orbopts', 'optflags'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrboptsOptflag->exists($id)) {
			throw new NotFoundException(__('Invalid orbopts optflag'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrboptsOptflag->save($this->request->data)) {
				$this->Session->setFlash(__('The orbopts optflag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbopts optflag could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrboptsOptflag.' . $this->OrboptsOptflag->primaryKey => $id));
			$this->request->data = $this->OrboptsOptflag->find('first', $options);
		}
		$orbopts = $this->OrboptsOptflag->Orbopt->find('list');
		$optflags = $this->OrboptsOptflag->Optflag->find('list');
		$this->set(compact('orbopts', 'optflags'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrboptsOptflag->id = $id;
		if (!$this->OrboptsOptflag->exists()) {
			throw new NotFoundException(__('Invalid orbopts optflag'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrboptsOptflag->delete()) {
			$this->Session->setFlash(__('The orbopts optflag has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbopts optflag could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
