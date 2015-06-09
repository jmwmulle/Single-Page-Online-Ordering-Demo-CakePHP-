<?php
App::uses('AppController', 'Controller');
/**
 * Optflags Controller
 *
 * @property Optflag $Optflag
 * @property PaginatorComponent $Paginator
 */
class OptflagsController extends AppController {

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
		$this->Optflag->recursive = 0;
		$this->set('optflags', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Optflag->exists($id)) {
			throw new NotFoundException(__('Invalid optflag'));
		}
		$options = array('conditions' => array('Optflag.' . $this->Optflag->primaryKey => $id));
		$this->set('optflag', $this->Optflag->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Optflag->create();
			if ($this->Optflag->save($this->request->data)) {
				$this->Session->setFlash(__('The optflag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The optflag could not be saved. Please, try again.'));
			}
		}
		$orbopts = $this->Optflag->Orbopt->find('list');
		$this->set(compact('orbopts'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Optflag->exists($id)) {
			throw new NotFoundException(__('Invalid optflag'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Optflag->save($this->request->data)) {
				$this->Session->setFlash(__('The optflag has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The optflag could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Optflag.' . $this->Optflag->primaryKey => $id));
			$this->request->data = $this->Optflag->find('first', $options);
		}
		$orbopts = $this->Optflag->Orbopt->find('list');
		$this->set(compact('orbopts'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Optflag->id = $id;
		if (!$this->Optflag->exists()) {
			throw new NotFoundException(__('Invalid optflag'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Optflag->delete()) {
			$this->Session->setFlash(__('The optflag has been deleted.'));
		} else {
			$this->Session->setFlash(__('The optflag could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function price_factors() {
		if ( $this->request->is('ajax') ) {
			$this->render_ajax_response($this->Optflag->find('list', array('conditions' => array('price_factor' => true))));
			return;
		}
		$this->redirect(___cakeUrl('orbcats', 'menu'));
	}
}
