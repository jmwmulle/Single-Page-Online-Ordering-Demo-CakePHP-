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
		$options = array('conditions'=>array('User.' . $this->User->primaryKey=>$id));
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
			$conditions = array('User.email'=>$this->request->data['User']['email']);    
			if ($this->User->hasAny($conditions)) {
				$this->Session->setFlash(__('That email address is already taken.'));
				return $this->redirect(___cakeUrl('pages', 'splash'));
			} else {
				$this->User->create();
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('Account created.'));
					return $this->redirect(___cakeUrl('pages','splash'));
				} else {
					$this->Session->setFlash(__('The account could not be created. Please, try again.'));
				}
				$groups = $this->User->Group->find('list');
				$this->set(compact('groups'));
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
	public function edit($id) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions'=>array('User.' . $this->User->primaryKey=>$id));
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
		return $this->redirect(array('action'=>'index'));
	}

/*ordering*/
	public function ordering($status) {
		if ($this->request->is('ajax')) {
			if (!$this->Session->check($address_checked)) {
				$this->Session->write('User.address_checked', False);
			}
			if ($status == 'delivery' or $status=='pickup') {
				$this->Session->write("Cart.order_type", $status);
				if ($this->Auth->loggedIn()) {
					$options = array('conditions'=>array('User.id'=>$this->Auth->user('id')));
					$this->User->find('first', $options);
					$add_matches = ($this->Session->read('User.address') == $this->User->address);
					$pc_matches = ($this->Session->read('User.postal_code') == $this->User->postal_code);
				} else {
					$add_matches = null;
					$pc_matches = null;
				}
				$this->set("response", json_encode(array("address_checked"=>$this->Session->read("User.address_checked"),
					"logged_in"=>$this->Auth->loggedIn(),
					"address_matches_db"=>$add_matches,
					"postal_code_matches_db"=>$pc_matches)));
			} else {
				return null;
			}
		} else {
			return $this->redirect(array('controller'=>'menu', 'action'=>'index'));
		}
	}

/*confirm_address*/
	public function confirm_address($command, $address = null) {
		if ($this->request->is('ajax')) {
			if (!$this->Session->check($address_checked)) {
				$this->Session->write('User.address_checked', False);
			}
			if ($command=='database') {
				if ($this->Auth->loggedIn()) {
					$options = array('conditions'=>array('User.id'=>$this->Auth->user('id')));
					$this->User->find('first', $options);
					$this->Session->write('User.address_checked', True);
					$this->Session->write('User.address', $this->User->address);
					$this->Session->write('User.postal_code', $this->User->postal_code);
				} else {
					$this->set("response", json_encode(array("address"=>null, "success"=>false,
						"error"=>"User not logged in.")));
				}
			} elseif ($command=='session') {
				if ($this->Session->check('address') & $this->Session->check('postal_code')) {
					$this->Session->write('User.address_checked', True);
					$this->set("response", json_encode(array("address"=>$this->Session->read('User.address'),
						"postal_code"=>$this->Session->read('User.postal_code'),"success"=>True)));
				} else {
					$this->set("response", json_encode(array("address"=>null, "success"=>False,
						"error"=>"Address not set.")));
				}
			} elseif ($command=='update_database') {
				if ($this->Auth->loggedIn()) {
					$options = array('conditions'=>array('User.id'=>$this->Auth->user('id')));
					$this->User->find('first', $options);
					$this->User->address = $this->Session->read('User.address');
					$this->User->postal_code = $this->Session->read('User.postal_code');
					if ($this->User->save()) {
						$this->set("response", json_encode(array("address"=>$this->User->address,
							"postal_code"=>$this->User->postal_code, "success"=>True)));
					} else {
						$this->set("response", json_encode(array("address"=>null, "success"=>false,
							"error"=>"Data could not be saved.")));
					}
				} else {
					$this->set("response", json_encode(array("address"=>null, "success"=>false,
						"error"=>"User not logged in.")));
				}
			} elseif ($command=='update_session') {
				if ($this->Auth->loggedIn()) {
					$data = json_decode($address);
					$this->Session->write("User.address",$data['address']);
					$this->Session->write("User.postal_code",$data['postal_code']);
					$this->Session->write('User.address_checked', True);
					$this->set("response", json_encode(array("address"=>$data['address'],
						"postal_code"=>$data['postal_code'],
						"success"=>True)));
				} else {
					$this->set("response", json_encode(array("address"=>null, "success"=>false,
						"error"=>"User not logged in.")));
				}
			} else {
					return null;
			}
		} else {
			return $this->redirect(array('controller'=>'menu', action='index'))
		}
	}


/*login	*/
	public function login() {
	    if ($this->Session->read('Auth.User')) {
		$this->Session->setFlash('You are already logged in!');
		return $this->redirect('/');
	    }
	    
	    if ($this->request->is('post')) {
		$conditions = array('User.email'=>$this->request->data['User']['email']);    
		if ($this->User->hasAny($conditions)) {    
			if ($this->Auth->login()) {
			    $this->Session->setFlash(__('Welcome'));
			    if ($this->Session->read('storedUser') != null) {
			    	$exUser = $this->User->find('first', array('conditions'=>array('User.email'=>$this->request['email'])));
			    	$exUser['User'] = array_merge($exUser['User'], $this->Session->read('storedUser'));
			    	$this->Session->write('stashedUser', null);
			    }
			    return $this->redirect($this->redirect->__cakeUrl('user', 'edit'));
			} else {
			    $this->Session->setFlash(__('Your email or password was incorrect. (Post1)'));
			    return $this->redirect(___cakeUrl('pages', 'splash'));
			}
		} else {
			$this->Session->setFlash(__('Your email or password was incorrect. (Post2)'));
			return $this->redirect(___cakeUrl('pages', 'splash'));
		}
	    }
	    
	    if ($this->request->is('put')) {
	    	$conditions = array('User.email'=>$this->request->data['User']['email']);    
		if ($this->User->hasAny($conditions)) {
			$this->Session->setFlash(__('That email address is already registered.'));
			return $this->redirect(__cakeUrl('pages', 'splash'));
		} else {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('Account created.'));
				return $this->redirect(__cakeUrl('pages','splash'));
			} else {
				$this->Session->setFlash(__('The account could not be created. Please try again.'));
			}
			$groups = $this->User->Group->find('list');
			$this->set(compact('groups'));
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
			$user = $this->User->find('first', array('conditions'=>$conditions));
			if ($this->Auth->login($user)) {
				$this->Session->setFlash(__('Welcome to Xtreme Pizza, ' + $user['User']['firstname']));
				return $this->redirect(___cakeUrl('users','edit',array('id'=>$user['User']['id'])));
			} else {
				db("Login Failed");
				$this->Session->setFlash(__('Login failed. Please try again. (Stashed1)'));
				return $this->redirect(___cakeUrl('pages', 'splash'));
			}
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
				$newUser = array('User'=>array(
					'email'=>$creds['auth']['info']['email'],
					'google_uid'=>$creds['auth']['uid'],
					'firstname'=>$creds['auth']['info']['first_name'],
					'lastname'=>$creds['auth']['info']['last_name'],
					'email_verified'=>$creds['auth']['raw']['verified_email'],
					'group_id'=>1
				));
				$conditions = array('User.google_uid'=>$newUser['User']['google_uid']);
			} elseif ($prov == 'Twitter') {
				$split_name = explode(' ', $creds['auth']['info']['name'], 2);
				$newUser = array('User'=>array(
					'twitter_uid'=>$creds['auth']['uid'],
					'firstname'=>$split_name[0],
					'lastname'=>$split_name[1],
					'group_id'=>1
				));
				$conditions = array('User.twitter_uid'=>$newUser['User']['twitter_uid']);
			} elseif ($prov == 'Facebook') {
				$newUser = array('User'=>array(
					'email'=>$creds['auth']['info']['email'],
					'facebook_uid'=>$creds['auth']['uid'],
					'firstname'=>$creds['auth']['info']['first_name'],
					'lastname'=>$creds['auth']['info']['last_name'],
					'email_verified'=>$creds['auth']['raw']['verified'],
					'group_id'=>1
				));
				$conditions = array('User.facebook_uid'=>$newUser['User']['facebook_uid']);
			}
			if ($this->User->hasAny($conditions)) {
				if ($this->Session->read('stashedUser') != null) {
					$exUser = $this->User->find('first', array('conditions'=>$conditions));
					$newUser = $this->Session->read('stashedUser');
					$newUser['User'] = array_merge($exUser['User'], $newUser['User']);
					if (!$this->User->save($newUser))
					{
						$this->Session->setFlash(__('Login failed. Please try again.'));
						return $this->redirect(___cakeUrl('pages', 'splash'));
					}
					$this->Session->write('stashedUser', null);
				}
				$exUser = $this->User->find('first', array('conditions'=>$conditions));
				if ($this->Auth->login($exUser)) {
					$this->Session->setFlash(__("Welcome back, " + $exUser['User']['firstname']));
					return $this->redirect(___cakeUrl('users', 'edit', $this->Auth->user('id')));#array('id'=>$exUser['User']['id'])));
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
	    $this->Auth->allow('add', 'login','logout');
	}
}
