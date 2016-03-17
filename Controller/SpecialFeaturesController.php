<?php
App::uses('AppController', 'Controller');
/**
 * SpecialFeatures Controller
 *
 * @property SpecialFeature $SpecialFeature
 * @property PaginatorComponent $Paginator
 */
class SpecialFeaturesController extends AppController {

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
		$this->SpecialFeature->recursive = 0;
		$this->set('specialFeatures', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SpecialFeature->exists($id)) {
			throw new NotFoundException(__('Invalid special feature'));
		}
		$options = array('conditions' => array('SpecialFeature.' . $this->SpecialFeature->primaryKey => $id));
		$this->set('specialFeature', $this->SpecialFeature->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->SpecialFeature->create();
			if ($this->SpecialFeature->save($this->request->data)) {
				$this->Session->setFlash(__('The special feature has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special feature could not be saved. Please, try again.'));
			}
		}
		$specials = $this->SpecialFeature->Special->find('list');
		$orblists = $this->SpecialFeature->Orblist->find('list');
		$orbcats = $this->SpecialFeature->Orbcat->find('list');
		$this->set(compact('specials', 'orblists', 'orbcats'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SpecialFeature->exists($id)) {
			throw new NotFoundException(__('Invalid special feature'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->SpecialFeature->save($this->request->data)) {
				$this->Session->setFlash(__('The special feature has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special feature could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SpecialFeature.' . $this->SpecialFeature->primaryKey => $id));
			$this->request->data = $this->SpecialFeature->find('first', $options);
		}
		$specials = $this->SpecialFeature->Special->find('list');
		$orblists = $this->SpecialFeature->Orblist->find('list');
		$orbcats = $this->SpecialFeature->Orbcat->find('list');
		$this->set(compact('specials', 'orblists', 'orbcats'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SpecialFeature->id = $id;
		if (!$this->SpecialFeature->exists()) {
			throw new NotFoundException(__('Invalid special feature'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->SpecialFeature->delete()) {
			$this->Session->setFlash(__('The special feature has been deleted.'));
		} else {
			$this->Session->setFlash(__('The special feature could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
