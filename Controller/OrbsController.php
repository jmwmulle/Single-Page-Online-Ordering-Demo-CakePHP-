<?php
App::uses('AppController', 'Controller');
/**
 * Orbs Controller
 *
 * @property Orb $Orb
 * @property PaginatorComponent $Paginator
 */
class OrbsController extends AppController {

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
		$this->Orb->recursive = 0;
		$this->set('orbs', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Orb->exists($id)) {
			throw new NotFoundException(__('Invalid orb'));
		}
		// ajax only if requested as an orbcard
		$options = array('conditions' => array('Orb.' . $this->Orb->primaryKey => $id));
		$this->set('orb', $this->Orb->find('first', $options));
		if ($this->request->is('ajax') ) {
			$this->render('orbcard', 'ajax');
		}
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Orb->create();
			if ($this->Orb->save($this->request->data)) {
				$this->Session->setFlash(__('The orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orb could not be saved. Please, try again.'));
			}
		}
		if  ($this->request->is('ajax') ) $this->layout = "ajax";
		$orbcats = $this->Orb->Orbcat->find('list');
		$orbextras = $this->Orb->Orbextra->find('list');
		$this->set(compact('orbcats', 'orbextras'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Orb->exists($id)) {
			throw new NotFoundException(__('Invalid orb'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Orb->save($this->request->data)) {
				$this->Session->setFlash(__('The orb has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The orb could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Orb.' . $this->Orb->primaryKey => $id));
			$this->request->data = $this->Orb->find('first', $options);
		}
		$orbcats = $this->Orb->Orbcat->find('list');
		$orbextras = $this->Orb->Orbextra->find('list');
		$this->set(compact('orbcats', 'orbextras'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Orb->id = $id;
		if (!$this->Orb->exists()) {
			throw new NotFoundException(__('Invalid orb'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Orb->delete()) {
			$this->Session->setFlash(__('The orb has been deleted.'));
		} else {
			$this->Session->setFlash(__('The orb could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function menu_item($id) {
		if ($this->request->is('ajax') && $this->Orb->exists($id) || true) {
			$filters =  array("premium" => 0, "meat" => 0, "veggie" => 0, "sauce" => 0, "cheese" => 0);
			$this->layout = 'ajax';
			$orb = $this->Orb->findById($id);

			unset($orb['Orbcat']);
			unset($orb['Order']);
			unset($orb['Orb']['created']);
			unset($orb['Orb']['modified']);
			$orb['Orb']['price_table'] = array_filter(array_slice(array_combine($orb['Pricedict'], $orb['Pricelist']), 1));
			$orb['Orb']['Orbopt'] = $orb['Orbopt'];
			$orb = $orb['Orb'];

			foreach($orb['Orbopt'] as $opt) {
				foreach ($filters as $filter => $count) {
					if ($opt[$filter]) $filters[$filter]++;
				}
			}
			foreach ($filters as $filter => $count) {
				if ($count == 0) unset($filters[$filter]);
			}

			$orb['filters'] = array_keys($filters);

			$this->set(compact('orb'));
			$this->render();
		} else {
			$this->redirect(___cakeUrl('orbcats', 'menu'));
		}
	}

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('menu_item');
	}

}
