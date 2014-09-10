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

/*opauth_complete*/	
	public function opauth_complete() {
		if ($this->data['validated']) {
			if (!$this->Session['authloop']):
			$this->login($this->data);	
			$this->Session->set('creds'=>$this->data);
			$this->Session->set('auths'=>$this->Session['authsloop']+1)
		} else {
			db('Failed\n'+$this->data);
			$this->Session->setFlash(__('Login failed. Please try again.'));
			return $this->redirect($this->redirect(___cakeUrl('pages','splash')));
		}
	}

/*login*/
	public function login($creds) {
		if ($this->Session['merging']) {
			$altUser = $this->Session['altUser'];
			$this->Session['altUser'] = null;
		}
		$prov = $creds['auth']['provider']
		if ($prov == 'Google') {
			$newUser = array('User' => array(
				'email' => $creds['auth']['info']['email'],
				'google_uid' => $creds['auth']['uid'],
				'firstname' => $creds['auth']['info']['first_name'],
				'lastname' => $creds['auth']['info']['last_name'],
				'email_verified' => $creds['raw']['verified_email'],
				'group_id' => 1));
			$conditions = array('User.google_uid' => $newUser['google_uid']);
		} elseif ($prov == 'Twitter') {
			$split_name = explode(' ', $creds['auth']['info']['name'], 2);
			$newUser = array('User' => array(
				'twitter_uid' => $creds['auth']['uid'],
				'firstname' => $split_name[0],
				'lastname' => $split_name[1],
				'group_id' => 1
			));
			$conditions = array('User.twitter_uid' => $newUser['twitter_uid']);
		} elseif ($prov == 'Facebook') {
			db($creds);
		}
		if (!$this->User->hasAny($conditions)){
			if ($this->Session['merging']) {
				$newUser = $newUser + $altUser;
				$this->Session['merging'] = false;
				$this->Session->setFlash(__("That account wasn't found, either, but we merged them and made a new one for you. Welcome to Xtreme Pizza, " + $newUser['firstName']));
				$this->User->save($newUser);
				$this->Auth->login($newUser);
			} elseif ($this->Session['decline-merge']) {
				$this->User->save($newUser);
				$this->Session->setFlash(__("Welcome to Xtreme Pizza, " + $newUser['firstName']));
				$this->Auth->login($newUser);
			} else {
				$this->Session['altUser'] = $newUser;
				$this->Session['merging'] = true; #TODO Figure out how to set this from a view
				$this->Render('merge-choice');
			}
		} elseif ($this->Session['merging']) {
			$newUser = $newUser + $altUser;
			$this->User->save($newUser);
			$this->Session['merging'] = false;
			if ($this->Auth->login($newUser)) {
                                $this->Session->setFlash(__("That account wasn't found either, but both are valid. Merging and creating a new account. Contact support if you think this was performed in error."));
				return $this->redirect($this->Auth->redirect());
                        } else {
                                $this->Session->setFlash(__('Login failed. Please contact support.'));
                                
				$this->redirect(___cakeUrl('pages', 'splash');
                        }
		}
		if ($this->Auth->login($newUser)) {
			return $this->redirect($this->Auth->redirect());
		} else {
			$this->Session->setFlash(__('Login failed. Please contact support.'));
			$this->redirect(___cakeUrl('pages', 'splash');
		}
	}

#beforeFilter	
	public function beforeFilter() {
	    parent::beforeFilter();

	    // For CakePHP 2.1 and up
	    $this->Auth->allow();
	}
}
