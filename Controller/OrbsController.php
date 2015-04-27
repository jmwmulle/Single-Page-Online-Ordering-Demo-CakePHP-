<?php
App::uses('AppController', 'Controller', 'ConnectionManager');
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
			$filters =  array("premium" => 0, "meat" => 0, "veggie" => 0, "sauce" => 0, "cheese" => 0, "condiment" => 0);
			$this->layout = 'ajax';
			$orb = $this->Orb->findById($id);
			unset($orb['Orbcat']);
			unset($orb['Order']);
			unset($orb['Orb']['created']);
			unset($orb['Orb']['modified']);
			$orb['Orb']['price_table'] = array_filter(array_slice(array_combine($orb['Pricedict'], $orb['Pricelist']), 1));
			$orb['Orb']['Orbopt'] = $orb['Orbopt'];
			$orb = $orb['Orb'];
//			echo "<pre>";
//			var_export($orb['Orbopt']);
//			echo "</pre>";
//			pr($orb['Orbopt']);
			usort($orb['Orbopt'],
				function($opt_a, $opt_b) {
					$scores = array("a" =>  0, "b" => 0);
					foreach( array("a" => $opt_a, "b" => $opt_b) as $index => $opt) {
						if ($opt['meat']) $scores[$index] = 5;
						if ($opt['veggie']) $scores[$index] = 4;
						if ($opt['sauce']) $scores[$index] = 3;
						if ($opt['cheese']) $scores[$index] = 2;
						if ($opt['condiment']) $scores[$index] = 1;
					}

					return $scores['b'] - $scores['a'];
				}
			);
//			echo "<h1>".$worked."</h1>";
//			echo "<br  /><pre>";
//			var_export($orb['Orbopt']);
//			echo "</pre>";
//			die();
//			db($orb);
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
		$this->Auth->allow('menu_item', 'upload_menu');
	}

/**
 * csv_to_menu
 */
	public function csv_to_menu($menu_file, $opts_file) { 

	}


/**
 * upload a csv to the database to set menu parameters
 */
	public function upload_menu() {
		$this->layout = 'vendor_ui';
		if ($this->request->is('post')) {
			$files = $this->request->data['menu_upload'];
			$this->Session->write('Upload.attempting', true);
			if ( AppController::update_tables_from_file($files['opts']['tmp_name'], $files['menu']['tmp_name']) ) {
				$this->Session->write('Upload.successful', true);
			} else {
				$this->Session->write('Upload.successful', false);
			}
		} else {
			$this->Session->delete( 'Upload' );
		}

	}

	public function before_filter() {
		parent::before_filter();
		$this->Auth->allow('csv_to_menu', 'upload_menu', 'menu_item');
	}
}
