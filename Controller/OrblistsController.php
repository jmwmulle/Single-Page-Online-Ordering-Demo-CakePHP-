<?php
App::uses('AppController', 'Controller');
/**
 * Orblists Controller
 *
 * @property Orblist $Orblist
 * @property PaginatorComponent $Paginator
 */
class OrblistsController extends AppController {

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
		$this->Orblist->recursive = 0;
		$this->set('orblists', $this->Paginator->paginate());
	}

	public function ajax_list() {
		if ( $this->is_ajax_get() ) {
			$orblists = $this->Orblist->find('all', ['recursive' => 1]);
			foreach ($orblists as $i => $ol) {
				$ol['Orblist']['orbs'] = [];
				foreach ($ol['Orb'] as $o) array_push($ol['Orblist']['orbs'], ['id' => $o['id'], 'title' => $o['title']]);
				$orblists[$i] = $ol['Orblist'];
			}
			$this->render_ajax_response(['success' => true, 'error' => false, 'data' => $orblists]);
		}
	}

	public function ajax_add() {
		if ( $this->is_ajax_post() ) {
			$response = ['success' => true,
			             'error' => false,
			             'data' => ['id' => null,
			                        'submitted'=> $this->request->data]];
			if ( !$this->Orblist->save($this->request->data) ) {
			    $response['success'] = false;
			    $response['error'] = $this->Orblist->validationErrors;
			} else {
				$response['data']['id'] = $this->Orblist->getLastInsertID();
			}
			$this->render_ajax_response($response);
		}
	}

	public function ajax_update($id) {
		if ( $this->is_ajax_post() || true) {
			$response = ['success' => true,
			             'error' => false,
			             'data' => ['orbs' => null,
			                        'submitted'=> $this->request->data]];
			$this->Orblist->id = $id;
			$this->loadModel('OrblistsOrb');
			$this->OrblistsOrb->deleteAll(['orblist_id' => $id], false);
			foreach ($this->request->data['Orb'] as $i => $o) {
				if ( !$this->OrblistsOrb->save(['orblist_id' => $id, 'orb_id' => $o['id']]) ) {
				    $response['success'] = false;
				    $response['error'] = $this->OrblistsOrb->validationErrors;
				}
			}
			$orblist = $this->Orblist->find('all', ['conditions' => ['`Orblist`.`id`' => $id]]);
			$response['data']['orbs'] = $orblist[0]['Orb'];
			$this->render_ajax_response($response);
		}
	}



/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Orblist->exists($id)) {
			throw new NotFoundException(__('Invalid orblist'));
		}
		$options = array('conditions' => array('Orblist.' . $this->Orblist->primaryKey => $id));
		$this->set('orblist', $this->Orblist->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Orblist->create();
			if ($this->Orblist->save($this->request->data)) {
				$this->Session->setFlash(__('The orblist has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orblist could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->Orblist->Orb->find('list');
		$this->set(compact('orbs'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Orblist->exists($id)) {
			throw new NotFoundException(__('Invalid orblist'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Orblist->save($this->request->data)) {
				$this->Session->setFlash(__('The orblist has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orblist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Orblist.' . $this->Orblist->primaryKey => $id));
			$this->request->data = $this->Orblist->find('first', $options);
		}
		$orbs = $this->Orblist->Orb->find('list');
		$this->set(compact('orbs'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Orblist->id = $id;
		if (!$this->Orblist->exists()) {
			throw new NotFoundException(__('Invalid orblist'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Orblist->delete()) {
			$this->Session->setFlash(__('The orblist has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orblist could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
