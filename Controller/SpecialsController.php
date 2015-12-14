<?php
App::uses('AppController', 'Controller');
/**
 * Specials Controller
 *
 * @property Special $Special
 * @property PaginatorComponent $Paginator
 */
class SpecialsController extends AppController {

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
		$this->Special->recursive = 0;
		$this->set('specials', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Special->exists($id)) {
			throw new NotFoundException(__('Invalid special'));
		}
		$options = array('conditions' => array('Special.' . $this->Special->primaryKey => $id));
		$this->set('special', $this->Special->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Special->create();
			if ($this->Special->save($this->request->data)) {
				$this->Session->setFlash(__('The special has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special could not be saved. Please, try again.'));
			}
		}
		$orbs = $this->Special->Orb->find('list');
		$this->set(compact('orbs'));
	}

	public function ajax_add() {
		if ( $this->is_ajax_post() ) {
			$response = ['success' => true,
			             'error' => false,
			             'data' => ['submitted' => $this->request->data]];
			$special = $this->request->data['Special'];
			if ( !$this->Special->save($special) ) {
			    $response['error'] = $this->Special->validationErrors;
				$response['success'] = false;
			} else {
				$this->loadModel('SpecialsOrb');
				$specials_orbs = Hash::insert($this->request->data['SpecialsOrb'], "{n}.special_id", $this->Special->id);
				if ( !$this->SpecialsOrb->saveAll($specials_orbs) ) {
					$this->Special->delete($this->Special->id);
					$response['error'] = ['Specials' => False, 'SpecialsOrb' => $this->SpecialsOrb->validationErrors];
					$response['success'] = false;
				}
			}
			$this->render_ajax_response($response);
		} else if ( $this->is_ajax_get() ) {
			$orbcats = $this->Special->Orb->Orbcat->find('list');
			$orbs = $this->Special->Orb->find('all', ['recursive' => -1, 'order' => '`orbcat_id`'] );
			$specials = $this->Special->find('all');
			$this->set(compact('orbcats', 'orbs', 'specials'));
			$this->render('ajax_add', 'ajax');
		} else {
			$this->redirect(___cakeUrl('orbcats', 'menu'));
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
		if (!$this->Special->exists($id)) {
			throw new NotFoundException(__('Invalid special'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Special->save($this->request->data)) {
				$this->Session->setFlash(__('The special has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The special could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Special.' . $this->Special->primaryKey => $id));
			$this->request->data = $this->Special->find('first', $options);
		}
		$orbs = $this->Special->Orb->find('list');
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
		$this->Special->id = $id;
		if (!$this->Special->exists()) {
			throw new NotFoundException(__('Invalid special'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Special->delete()) {
			$this->Session->setFlash(__('The special has been deleted.'));
		} else {
			$this->Session->setFlash(__('The special could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
