<?php
App::uses('AppController', 'Controller');
/**
 * Sysvars Controller
 *
 * @property Sysvar $Sysvar
 * @property PaginatorComponent $Paginator
 */
class SysvarsController extends AppController {

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
		$this->Sysvar->recursive = 0;
		$this->set('sysvars', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Sysvar->exists($id)) {
			throw new NotFoundException(__('Invalid sysvar'));
		}
		$options = array('conditions' => array('Sysvar.' . $this->Sysvar->primaryKey => $id));
		$this->set('variable', $this->Sysvar->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Sysvar->create();
			if ($this->Sysvar->save($this->request->data)) {
				$this->Session->setFlash(__('The sysvar has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The sysvar could not be saved. Please, try again.'));
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
		if (!$this->Sysvar->exists($id)) {
			throw new NotFoundException(__('Invalid sysvar'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Sysvar->save($this->request->data)) {
				$this->Session->setFlash(__('The sysvar has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The sysvar could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Sysvar.' . $this->Sysvar->primaryKey => $id));
			$this->request->data = $this->Sysvar->find('first', $options);
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
		$this->Sysvar->id = $id;
		if (!$this->Sysvar->exists()) {
			throw new NotFoundException(__('Invalid variable'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Sysvar->delete()) {
			$this->Session->setFlash(__('The variable has been deleted.'));
		} else {
			$this->Session->setFlash(__('The variable could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	public function delivery_time($time) {
		if ($this->is_ajax_post() ) {
			$time = $time * 1;
			$response = ['success' => true,
			             'error' => false,
			             'data' => ['submitted' => ["time" => $time],
			                        'times' => []
			             ]];

			$found =  false;
			for ($i = DELIVERY_TIME_15; $i<= DELIVERY_TIME_90_PLUS; $i++) {
				$response['data']['times'][$i] = ["time" => 15 * ( $i - 5 ), "check" => $i  == 15 * ( $i - 5 )];
				$this->Sysvar->id = $i;
				if ( !($this->Sysvar->exists() ) ) {
					$response[ 'success' ] = false;
					$response[ 'error' ]   = "Sysvar not found";
					break;
				}
				$this->Sysvar->save( [ "state" => $time == 15 * ( $i - 5 ) ] );
			}
			$this->render_ajax_response($response);
		} else {
			$this->redirect( ___cakeUrl( "orders", "menu" ) );
		}
	}

	public function config($id, $method, $status=null) {
		$this->autoRender = false;
//		if ( !$this->request->is('ajax') ) return $this->redirect(___cakeUrl('orbcats', 'menu'));
		$options = [];

		$response = [
			'success' => true,
		    'error' => false,
		    'data' => [ 'submitted' => compact('id', 'status', 'method'),
		                'system' => null
		    ]];
		$sysvars = null;
		if ( $id > 0 and $status != null and $method == 1) {
			if ( !$status === true and !$status === false ) {
				$response['success'] = false;
				$response['error'] = "No value to set.";
				return  $this->render_ajax_response($response);
			}
			if ( !$this->Sysvar->save(compact('id', 'status')) ) {
				$response['success'] = false;
				$response['error'] = $this->Sysvar->getValidationErrors;
				return  $this->render_ajax_response($response);
			}
			$sysvars = $this->Sysvar->find('all', $options);
		}

		if ( $method == 0) {
			if ($id != -1) $options['conditions'] = ['`variable`.`id`' => $id];
			if ($id > 0 && !$this->Sysvar->exists($id) ) {
				$response['success'] = false;
				$response['error'] = "Sysvar id not found.";
				return  $this->render_ajax_response($response);
			}
			$sysvars = $this->Sysvar->find('all', $options);
		}
		$response['data']['system'] = Hash::remove(Hash::remove( Hash::combine( $sysvars,
					"{n}.Sysvar.name", "{n}.Sysvar"),
				"{s}.name"),
		"{s}.id");
		return  $this->request->is('ajax') ? $this->render_ajax_response($response) : $sysvars;
	}
}


