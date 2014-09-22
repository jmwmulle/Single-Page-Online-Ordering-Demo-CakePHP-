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
		if ($this->request->is('ajax')) { $this->layout =  "ajax";}
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
	public function edit($id) {
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

/*login	*/
	public function login() {
	    if ($this->Session->read('Auth.User')) {
		$this->Session->setFlash('You are logged in!');
		return $this->redirect('/');
	    }
	    if ($this->request->is('post')) {
		if ($this->Auth->login()) {
		    $this->Session->setFlash(__('Welcome'));
		    return $this->redirect($this->Auth->redirectUrl());
		} else {
			$this->Session->setFlash(__('Your username or password was incorrect.'));
			return $this->redirect(___cakeUrl('pages', 'splash'));
		}
	    }
		if ($this->Session->read('stashedUser') != null) {
			$user = $this->Session->read('stashedUser');
			$this->Session->write('stashedUser', null);
			if (!$this->User->save($user)) {
				$this->log($this->User->validationErrors);
			}
			$conditions = array();
			if (array_key_exists('google_uid', $user['User'])) {
				$conditions['User.google_uid'] = $user['User']['google_uid'];
			}
			if (array_key_exists('twitter_uid', $user['User'])) {
				$conditions['User.twitter_uid'] = $user['User']['twitter_uid'];
			}
			if (array_key_exists('facebook_uid', $user['User'])) {
				$conditions['User.facebook_uid'] = $user['User']['facebook_uid'];
			}
			$user = $this->User->find('first', array('conditions' => $conditions));
			if ($this->Auth->login($user)) {
				$this->Session->setFlash(__('Welcome to Xtreme Pizza, ' + $user['User']['firstname']));
				return $this->redirect(___cakeUrl('users','edit',array('id' => $user['User']['id'])));
			} else {
				db("Login Failed");
				$this->Session->setFlash(__('Login failed. Please try again.'));
				return $this->redirect(___cakeUrl('pages', 'splash'));
			}
		} else {
			$this->Session->setFlash(__('Login failed. Please try again.'));
			return $this->redirect(___cakeUrl('pages', 'splash'));
		}
	}

/*logout*/	
	public function logout() {
		$this->Session->setFlash('Good-Bye');
		$this->Auth->logout();
		$this->Session->write('stashedUser', null);
		$this->redirect(___cakeUrl('pages', 'splash'));
	}

/*opauth_complete*/	
	public function opauth_complete() {
		$this->log("Opauth complete");
		if ($this->data['validated']) {
			$this->log($this->data);
			$creds = $this->data;	
			$prov = $creds['auth']['provider'];
			if ($prov == 'Google') {
				$newUser = array('User' => array(
					'email' => $creds['auth']['info']['email'],
					'google_uid' => $creds['auth']['uid'],
					'firstname' => $creds['auth']['info']['first_name'],
					'lastname' => $creds['auth']['info']['last_name'],
					'email_verified' => $creds['auth']['raw']['verified_email'],
					'group_id' => 1
				));
				$conditions = array('User.google_uid' => $newUser['User']['google_uid']);
			} elseif ($prov == 'Twitter') {
				$split_name = explode(' ', $creds['auth']['info']['name'], 2);
				$newUser = array('User' => array(
					'twitter_uid' => $creds['auth']['uid'],
					'firstname' => $split_name[0],
					'lastname' => $split_name[1],
					'group_id' => 1
				));
				$conditions = array('User.twitter_uid' => $newUser['User']['twitter_uid']);
			} elseif ($prov == 'Facebook') {
				$newUser = array('User' => array(
					'email' => $creds['auth']['info']['email'],
					'facebook_uid' => $creds['auth']['uid'],
					'firstname' => $creds['auth']['info']['first_name'],
					'lastname' => $creds['auth']['info']['last_name'],
					'email_verified' => $creds['auth']['raw']['verified'],
					'group_id' => 1
				));
				$conditions = array('User.facebook_uid' => $newUser['User']['facebook_uid']);
			}
			if ($this->User->hasAny($conditions)) {
				if ($this->Session->read('stashedUser') != null) {
					$exUser = $this->User->find('first', array('conditions' => $conditions));
					$newUser = $this->Session->read('stashedUser');
					$newUser['User'] = array_merge($exUser['User'], $newUser['User']);
					if (!$this->User->save($newUser))
					{
						$this->Session->setFlash(__('Login failed. Please try again.'));
						return $this->redirect(___cakeUrl('pages', 'splash'));
					}
					$this->Session->write('stashedUser', null);
				}
				$exUser = $this->User->find('first', array('conditions' => $conditions));
				if ($this->Auth->login($exUser)) {
					$this->Session->setFlash(__("Welcome back, " + $exUser['User']['firstname']));
					return $this->redirect(___cakeUrl('users', 'edit', array('id' => $exUser['User']['id'])));
				} else {
					db('Login failed');
					$this->Session->setFlash(__('Login failed. Please try again.'));
					return $this->redirect(___cakeUrl('pages', 'splash'));
				}
			} else {
				$this->Session->write('stashedUser',$newUser);
				$this->render('merge-choice');
			}
		} else {
			$this->Session->setFlash(__('Login failed. Please try again.'));
			return $this->redirect(___cakeUrl('pages','splash'));
		}
	}

/*beforeFilter*/	
	public function beforeFilter() {
	    parent::beforeFilter();

	    // For CakePHP 2.1 and up
	    $this->Auth->allow();
	}
}
