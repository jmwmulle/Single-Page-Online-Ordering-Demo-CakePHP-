<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

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
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

#register	
	public function register() {
		// don't know enough about oAuth to know if this is 1 function or 4 (FB/Twitter/G+/e-mail)
	}

#login	
	public function login() {
	    if ($this->Session->read('Auth.User')) {
		$this->Session->setFlash('You are logged in!');
		return $this->redirect('/');
	    }
	    if ($this->request->is('post')) {
		if ($this->Auth->login()) {
		    return $this->redirect($this->Auth->redirect());
		}
		$this->Session->setFlash(__('Your username or password was incorrect.'));
	    }
	}

#logout	
	public function logout() {
		$this->Session->setFlash('Good-Bye');
		$this->redirect($this->Auth->logout());$this->Session->setFlash('Good-Bye');
		$this->redirect($this->Auth->logout());
	}

/*opauth_complete_google*/	
	public function opauth_complete() {
		if ($this->data['validated']) {
			$prov = $this->data['auth']['provider'];
			if ($prov == 'Google') {
				$this->proc_google_login($this->data);
			} elseif ($prov == 'Twitter') {
				$this->proc_twitter_login($this->data);
			} elseif ($prov == 'Facebook') {
				$this->proc_facebook_login($this->data);
			}
		} else {
			db('Failed\n'+$this->data);
			$this->Session->setFlash(__('Login failed. Please try again.'));
			return $this->redirect($this->redirect(___cakeUrl('pages','splash')));
		}
	}

/*proc_google_login*/
	public function proc_google_login($data) {
		if ($this->Auth->loggedIn()) {
			$this->User->set('google_uid', $data['auth']['uid']);
			$this->User->save();
		} else {
			$conditions = array('User.google_uid' => $data['auth']['uid']);
			if ($this->User->hasAny($conditions)){
				if ($this->Auth->login(array('google_uid' => $this->data['auth']['uid']))) {
					return $this->redirect($this->Auth->redirect());
				} else {
					db("Login Failed");
				}
			} else {
				$newUser = array('User' => array(
					'email' => $data['auth']['info']['email'],
					'google_uid' => $data['auth']['uid'],
					'firstname' => $data['auth']['info']['first_name'],
					'lastname' => $data['auth']['info']['last_name'],
					'email_verified' => $data['raw']['verified_email'],
					'group_id' => 1
				));
				if ($this->User->save($newUser)) {
				} else {
					db("Failed to Create User");
				}
				$this->Session->setFlash(__('Logged in. Welcome ' + $data['auth']['info']['name'] + '.'));
				return $this->redirect($this->Auth->redirect());
			}
		}
	}

/*proc_twitter_login*/
	public function proc_twitter_login($data) {
		if ($this->Auth->loggedIn()) {
			$this->User-set('User.twitter_uid' => $data['auth']['uid']);
			$this->User->save();
		} else {
			$conditions = array('User.twitter_uid' => $data['auth']['uid']);
			if ($this->User->hasAny($conditions)){
				if ($this->Auth->login(array('twitter_uid' => $data['auth']['uid']))) {
					return $this->redirect($this->Auth->redirect());
				} else {
					db("Login Failed");
				}
			} else {
				$split_name = explode(' ', $data['auth']['info']['name'], 2);
				$newUser = array('User' => array(
					'twitter_uid' => $data['auth']['uid'],
					'firstname' => $split_name[0],
					'lastname' => $split_name[1],
					'group_id' => 1
				));
				if ($this->User->save($newUser)) {
				} else {
					db("Failed to Create User");
				}
				$this->Session->setFlash(__('Logged in. Welcome ' + $data['auth']['info']['name'] + '.'));
				return $this->redirect($this->Auth->redirect());
			}
		}
	}

#home	
	public function home() {
		$this->redirect(___cakeUrl('pages','splash'));
	}

#beforeFilter	
	public function beforeFilter() {
	    parent::beforeFilter();

	    // For CakePHP 2.1 and up
	    $this->Auth->allow();
	}
}
