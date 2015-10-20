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
	public $components = array('Paginator', 'Session');

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


	public function delivery_time($id = null) {
		if ($this->is_ajax_post()) {
			if ($id === null) {
				$time = $this->Sysvar->find('first', ['conditions' =>
					                                      ['`Sysvar`.`id` >=' => DELIVERY_TIME_30, '`Sysvar`.`status`' => true],
				                                      'fields' => 'id'
				]);
				// basicaly if this fails, it should fail gracefully
				return !empty($time) ? ($time['Sysvar']['id'] - 5) * 15 : 45;
			}
			$response = ['success' => true,
			             'error' => false,
			             'data' => ['submitted' => ["id" => $id]]];

				for($i = 6; $i <= 11; $i++) {
					if ( !$this->Sysvar->save(['id' => $i, 'status' => $i === (int) $id]) ) {
						$response['success'] = false;
						$response['error'] = $this->Sysvar->getValidationErrors;
						break;
					}
				}
			return $this->render_ajax_response($response);
		} else {
			$this->redirect( ___cakeUrl( "orders", "menu" ) );
		}
	}

	public function sv_config($id, $method, $status=null) {
		$this->autoRender = false;
		$options = [];

		if ( !($status === null) ) $status = (bool) $status;
		$response = [
			'success' => true,
		    'error' => false,
		    'data' => [ 'submitted' => compact('id', 'status', 'method'),
		                'system' => null,
		                'test' => [$id > 0, is_bool($status), !!$method]
		    ]];
		$sysvars = null;
		if ( (int) $id > 0 and is_bool($status) and $method) {
			if ( !$this->Sysvar->save(compact('id', 'status')) ) {
				$response['success'] = false;
				$response['error'] = $this->Sysvar->getValidationErrors;
				return  $this->render_ajax_response($response);
			}
			$sysvars = $this->Sysvar->find('all', $options);
		}
		$response['data']['system'] = $sysvars;

		if ( !$method ) {
			if ($id != -1) $options['conditions'] = ['`Sysvar`.`id`' => $id];
			if ($id > 0 && !$this->Sysvar->exists($id) ) {
				$response['success'] = false;
				$response['error'] = "Sysvar id not found.";
				return  $this->render_ajax_response($response);
			}
			$sysvars = $this->Sysvar->find('all', $options);
		}
		$json_v = Hash::combine( $sysvars,"{n}.Sysvar.name", "{n}.Sysvar");
		$response['data']['system'] =  Hash::remove( Hash::remove($json_v, "{s}.name"), "{s}.id");
		return  $this->request->is('ajax') ? $this->render_ajax_response($response) : $sysvars;
	}
}


