<?php
App::uses('AppController', 'Controller');
/**
 * OrboptsOrbcats Controller
 *
 * @property OrboptsOrbcat $OrboptsOrbcat
 * @property PaginatorComponent $Paginator
 */
class OrboptsOrbcatsController extends AppController {

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
		$this->OrboptsOrbcat->recursive = 0;
		$this->set('orboptsOrbcats', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OrboptsOrbcat->exists($id)) {
			throw new NotFoundException(__('Invalid orbopts orbcat'));
		}
		$options = array('conditions' => array('OrboptsOrbcat.' . $this->OrboptsOrbcat->primaryKey => $id));
		$this->set('orboptsOrbcat', $this->OrboptsOrbcat->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OrboptsOrbcat->create();
			if ($this->OrboptsOrbcat->save($this->request->data)) {
				$this->Session->setFlash(__('The orbopts orbcat has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbopts orbcat could not be saved. Please, try again.'));
			}
		}
		$orbopts = $this->OrboptsOrbcat->Orbopt->find('list');
		$orbcats = $this->OrboptsOrbcat->Orbcat->find('list');
		$this->set(compact('orbopts', 'orbcats'));
	}

	public function ajax_add($orbopt_id) {
		if ($this->request->is('ajax') && $this->request->is('post') ) {
			$response = array('success' => true, 'error' => false, 'submitted_data' => $this->request->data);
			if (!$this->OrboptsOrbcat->deleteAll(array('orbopt_id' => $orbopt_id, 'orbcat_id !=' => array_values($this->request->data['OrboptOrbcat'])) )) {
				$response['error'] = $this->OrboptsOrbcat->validationErrors;
				return $this->render_ajax_response($response);
			}
			$data = array();
			foreach($this->request->data['OrboptOrbcat'] as $og_id => $include) {
				if ($include) array_push($data, array('orbopt_id' => $orbopt_id, 'orbcat_id' => $og_id));
			}
			if ( !$this->OrboptsOrbcat->saveAll($data) ) {
				$response['error'] = $this->OrboptsOrbcat->validationErrors;
			}
			return $this->render_ajax_response($response);
		}
//		$orbopts_optgroups = $this->OrboptsOrbcat->find('all', array('recursive' => -1,
//		                                                             'conditions' => array('orbopt_id' => $orbopt_id)
//			));
		$orbopt_optgroups = $this->OrboptsOrbcat->Orbcat->find('list', array('conditions' => array('orbopt_group' => 1)));
		$orbopt = $this->OrboptsOrbcat->Orbopt->findById($orbopt_id);
		$orbopt['Orbcat'] = Hash::extract($orbopt['Orbcat'], "{n}.id");
		$orbopt = array('Orbopt' => $orbopt['Orbopt'], 'Orbcat' => $orbopt['Orbcat']);
		$this->set(compact('orbopts_optgroups', 'orbopt_optgroups', 'orbopt'));
		$this->render('ajax_add', 'ajax');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->OrboptsOrbcat->exists($id)) {
			throw new NotFoundException(__('Invalid orbopts orbcat'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->OrboptsOrbcat->save($this->request->data)) {
				$this->Session->setFlash(__('The orbopts orbcat has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orbopts orbcat could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('OrboptsOrbcat.' . $this->OrboptsOrbcat->primaryKey => $id));
			$this->request->data = $this->OrboptsOrbcat->find('first', $options);
		}
		$orbopts = $this->OrboptsOrbcat->Orbopt->find('list');
		$orbcats = $this->OrboptsOrbcat->Orbcat->find('list');
		$this->set(compact('orbopts', 'orbcats'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OrboptsOrbcat->id = $id;
		if (!$this->OrboptsOrbcat->exists()) {
			throw new NotFoundException(__('Invalid orbopts orbcat'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->OrboptsOrbcat->delete()) {
			$this->Session->setFlash(__('The orbopts orbcat has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orbopts orbcat could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function beforeFilter() {
		parent::before_filter();
		$this->Auth->allow('ajax_add');
	}
}
