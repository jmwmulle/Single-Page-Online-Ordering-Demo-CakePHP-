<?php
App::uses('AppController', 'Controller');
/**
 * Pricelists Controller
 *
 * @property Pricelist $Pricelist
 * @property PaginatorComponent $Paginator
 */
class PricelistsController extends AppController {

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
	public function index($opt_id=-1) {
		if ( $this->request->is('ajax') and $opt_id > 0 or true) {
			$pricelists = $this->Pricelist->find('all', ['recursive' => -1]);
			foreach ($pricelists as $index => $pl) {
				if ($pl['Pricelist']['label'] == "") unset($pricelists[$index]);
			}
			$pricelists = array_filter($pricelists);
			$orbopt = $this->Pricelist->Orbopt->find('first', ['recursive' => 0, 'conditions' =>['`Orbopt`.`id`' => $opt_id]]);

			$this->set(compact('orbopt', 'pricelists'));
			$this->render('vendor_index', 'ajax');
		} else {
			$this->Pricelist->recursive = 0;
			$this->set('pricelists', $this->Paginator->paginate());
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
		if (!$this->Pricelist->exists($id)) {
			throw new NotFoundException(__('Invalid pricelist'));
		}
		$options = array('conditions' => array('Pricelist.' . $this->Pricelist->primaryKey => $id));
		$this->set('pricelist', $this->Pricelist->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Pricelist->create();
			if ($this->Pricelist->save($this->request->data)) {
				$this->Session->setFlash(__('The pricelist has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The pricelist could not be saved. Please, try again.'));
			}
		}
	}

	public function ajax_add($opt_id) {
		if ($this->request->is('ajax') && $this->request->is('post') ) {
			$response = ['success' => true,
			             'error' => false,
			             'delegate_route' => "orbopt_pricelist/launch/false/$opt_id",
			             'submitted_data' => $this->request->data];
			if (!$this->Pricelist->save($this->request->data) ) {
				$response['success'] = false;
				$response['error'] = $this->Pricelist->validationErrors;
			}
			$this->render_ajax_response($response);
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
		if (!$this->Pricelist->exists($id)) {
			throw new NotFoundException(__('Invalid pricelist'));
		}
		if ($this->request->is(array('post', 'put')) && !$this->request->is('ajax') && false) {
			if ($this->Pricelist->save($this->request->data)) {
				$this->Session->setFlash(__('The pricelist has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The pricelist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Pricelist.' . $this->Pricelist->primaryKey => $id));
			$this->request->data = $this->Pricelist->find('first', $options);;
		}
	}

	public function ajax_edit($id, $orbopt_id=null) {
		if ($this->request->is('ajax') ) {
			if ( $this->request->is('post') ) {
				$response = ['success' => true,
			                 'error' => false,
			                 'delegate_route' => "orbopt_pricelist/launch/false/$orbopt_id",
			                 'data' => ['submitted_data' => $this->request->data, 'id' => $id]];
				if ( !$this->Pricelist->save($this->request->data) ) {
					$response['success'] = false;
					$response['error'] = $this->Pricelist->validationErrors;
				}
				$this->render_ajax_response($response);
			} else {
				$pricelist =  $this->Pricelist->find('first', ['conditions' => ['id' => $id]]);
				$this->set(compact('pricelist'));
				$this->render('vendor_edit', 'ajax');
			}
		} else {
			return $this->redirect(___cakeUrl("orbcats", "menu") );
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
		$this->Pricelist->id = $id;
		if (!$this->Pricelist->exists()) {
			throw new NotFoundException(__('Invalid pricelist'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Pricelist->delete()) {
			$this->Session->setFlash(__('The pricelist has been deleted.'));
		} else {
			$this->Session->setFlash(__('The pricelist could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function ajax_delete($id) {
		if ( $this->is_ajax_post() ) {
			$opts = $this->Pricelist->Orbopt->find('all', ['recursive' => 0, 'conditions' => ['`Orbopt`.`pricelist_id`' => $id]]);
			$response = ['success' => true, 'error' => false, 'delegate_route' => 'orbopt_pricelist/delete/print_opts', 'data' => compact('id')];
			foreach ($opts as $opt) {
				$opt['Orbopt']['pricelist_id'] = -1;
				if ( !$this->Pricelist->Orbopt->save($opt) ) {
					$response['success'] = false;
					$response['error'] = ['opt' => $this->Pricelist->Orbopt->validationErrors];
					break;
				}
			}
			if ( !$response['error'] ) {
				$this->Pricelist->id = $id;
				if ( !$this->Pricelist->delete() ) {
					$response['success'] = false;
					$response['error'] = ['pricelist' => $this->Pricelist->validationErrors];
				}
			}
			// if error, try to restore opts
//			if ( $response['error'] ) {
//				if (!$this->Pricelist->Orbopt->saveAll($opts) ) {
//					$response['error']['opts'] = $this->Pricelist->Orbopt->validationErrors;
//				}
//			}

			$this->render_ajax_response($response);
		} else {
			$this->redirect(___cakeUrl("orbcats", "menu") );
		}
	}

}
