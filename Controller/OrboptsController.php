<?php
App::uses('AppController', 'Controller');
/**
 * Orbopts Controller
 *
 * @property Orbopt $Orbopt
 * @property PaginatorComponent $Paginator
 */
class OrboptsController extends AppController {

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
		$this->Orbopt->recursive = 0;
		$this->set('orbopts', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Orbopt->exists($id)) {
			throw new NotFoundException(__('Invalid orbopt'));
		}
		$options = array('conditions' => array('Orbopt.' . $this->Orbopt->primaryKey => $id));
		$this->set('orbopt', $this->Orbopt->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if ( $this->request->is('ajax') ) {
				$response = array('success' => true, 'error' => false, 'submitted_data' => $this->request->data);
//				return $this->render_ajax_response($response);
				$data = $this->request->data;
				$data['Orbopt']['pricelist_id'] = -1;
				if ( !$this->Orbopt->save($data) ) {
					$response['success'] = false;
					$response['error'] = $this->Orbopt->validationErrors;
				}
				return $this->render_ajax_response($response);
			}

			if ($this->Orbopt->save($this->request->data)) {
				$this->Session->setFlash(__('The orbopt has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbopt could not be saved. Please, try again.'));
			}
		}
		$pricelists = $this->Orbopt->Pricelist->find('list');
		$orbs = $this->Orbopt->Orb->find('list');
		$orbcats = $this->Orbopt->Orbcat->find('list');
		$optflags = $this->Orbopt->Optflag->find('list');
		$this->set(compact('pricelists', 'orbs', 'orbcats', 'optflags'));
	}


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Orbopt->exists($id)) {
			throw new NotFoundException(__('Invalid orbopt'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Orbopt->save($this->request->data)) {
				$this->Session->setFlash(__('The orbopt has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbopt could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Orbopt.' . $this->Orbopt->primaryKey => $id));
			$this->request->data = $this->Orbopt->find('first', $options);
		}
		$pricelists = $this->Orbopt->Pricelist->find('list');
		$orbs = $this->Orbopt->Orb->find('list');
		$orbcats = $this->Orbopt->Orbcat->find('list');
		$optflags = $this->Orbopt->Optflag->find('list');
		$this->set(compact('pricelists', 'orbs', 'orbcats', 'optflags'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Orbopt->id = $id;
		if (!$this->Orbopt->exists()) {
			throw new NotFoundException(__('Invalid orbopt'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Orbopt->delete()) {
			$this->Session->setFlash(__('The orbopt has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbopt could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	/**
	 * deprecate method - basically a soft delete, orbopt remains to prevent errors with favorite orders and past orders
	 * but won't be visible in vendor_ui or public menu
	 *
	 * @param null $id
	 */
	public function deprecate($id = null) {
		if ($this->request->is('ajax') && $this->request->is('post') ) {
			$this->Orbopt->id = $id;
			$response =  array('success' => array('orbopt' => true, 'orbs_orbopt' => true), 'error' => false, 'submitted_data' => array('id' => $id));
			if ( !$this->Orbopt->save(array('deprecated' => true)) ) {
				$response['success']['orbopt'] = false;
				$response['error'] = $this->Orbopt->validationErrors;
			}
			$this->loadModel('OrbsOrbopt');
			if (!$this->OrbsOrbopt->updateAll(array('deprecated' => true), array('orbopt_id' => $id)) ) {
				$response['success']['orbs_orbopt'] = false;
				$response['error'] = $this->OrbsOrbopt->validationErrors;
			}
			$this->render_ajax_response($response);

		} else {
			$this->redirect(___cakeUrl('orbcats', 'menu'));
		}
	}

	public function update($id, $attribute) {
		if ($this->request->is('ajax') && $this->request->is('post') ) {
			$response = ['success' => true,
			             'error' => false,
			             'delegate_route' => null,
			             'submitted_data' => ['id' => $id,
                                                   'attribute' => $attribute,
                                                   'data' => $this->request->data]];
			if ( !$this->Orbopt->exists($id) && $attribute != "pricing" ) {
				$response['success'] = false;
				$response['error'] = "Orbopt doesn't exist!";
			}

			if ($attribute == "pricing") $response['delegate_route'] = "close_modal/primary";


			$this->Orbopt->id = $id;
			if (!$this->Orbopt->save($this->request->data) ) {
				$response['error']  = $this->Orbopt->validationErrors;
				$response['success'] = false;
			}
			$this->render_ajax_response($response);
		} else {
			$this->redirect(___cakeUrl('orbcats', 'menu'));
		}
	}



}
