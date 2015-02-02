<?php
App::uses('AppController', 'Controller');
/**
 * Feedbacks Controller
 *
 * @property Feedback $Feedback
 * @property PaginatorComponent $Paginator
 */
class FeedbackController extends AppController {

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
		$this->Feedback->recursive = 0;
		$this->set('feedbacks', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Feedback->exists($id)) {
			throw new NotFoundException(__('Invalid feedback'));
		}
		$options = array('conditions' => array('Feedback.' . $this->Feedback->primaryKey => $id));
		$this->set('feedback', $this->Feedback->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Feedback->create();
			if ($this->Feedback->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feedback could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Feedback->exists($id)) {
			throw new NotFoundException(__('Invalid feedback'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Feedback->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feedback could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Feedback.' . $this->Feedback->primaryKey => $id));
			$this->request->data = $this->Feedback->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Feedback->id = $id;
		if (!$this->Feedback->exists()) {
			throw new NotFoundException(__('Invalid feedback'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Feedback->delete()) {
			$this->Session->setFlash(__('The feedback has been deleted.'));
		} else {
			$this->Session->setFlash(__('The feedback could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

//	public function beforeFilter() {
//	    parent::beforeFilter();
//
//	    // For CakePHP 2.1 and up
//	    $this->Auth->allow("*");
//	}
}
