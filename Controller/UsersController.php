<?php
	App::uses( 'AppController', 'Controller' );

	/**
	 * Users Controller
	 *
	 * @property User               $User
	 * @property PaginatorComponent $Paginator
	 */
	class UsersController extends AppController {

		/**
		 * Components
		 *
		 * @var array
		 */
		public $components = array( 'Paginator' );
		public $uses = array( 'User', 'Address', 'Favourite');

		/**
		 * index method
		 *
		 * @return void
		 */
		public function index() {
			$this->User->recursive = 0;
			$this->set( 'users', $this->Paginator->paginate() );
		}

		/**
		 * view method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function view($id = null) {
			if ( !$this->User->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid user' ) );
			}
			$options = array( 'conditions' => array( 'User.' . $this->User->primaryKey => $id ) );
			$this->set( 'user', $this->User->find( 'first', $options ) );
		}

		/**
		 * add method
		 *
		 * @return void
		 */
		public function add() {
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = "ajax";
			}

			if ( $this->request->is( 'post' ) ) {
				$conditions = array( 'User.email' => $this->request->data[ 'User' ][ 'email' ] );
				if ( $this->User->hasAny( $conditions ) ) {
					return json_encode( array( 'success' => false, 'error' => 'That email address is already taken.' )
					);
				}
				else {
					$this->request->data[ 'User' ][ 'group_id' ] = 2;
					$this->User->create();
					if ( $this->User->save( $this->request->data ) ) {
						$this->User->saveAssociated( $this->request->data );
						$this->Session->setFlash( __( 'Account created.' ) );
						$cur_user = $this->User->find( 'first', array( 'recursive' => -1 ) );

						return json_encode( array( 'success' => true, 'User' => $cur_user ) );
					}
					else {
						return json_encode( array( 'success' => false ) );
					}
				}
			}
		}
		/*$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));*/

		/**
		 * edit method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function edit($id) {
			if ( !$this->User->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid user' ) );
			}
			if ( $this->request->is( array( 'post', 'put' ) ) ) {
				if ( $this->User->save( $this->request->data ) ) {
					$this->Session->setFlash( __( 'The user has been saved.' ) );

					return $this->redirect( array( 'controller' => 'menu', 'action' => '') );
				}
				else {
					$this->Session->setFlash( __( 'The user could not be saved. Please, try again.' ) );
				}
			}
			else {
				$options             = array( 'conditions' => array( 'User.' . $this->User->primaryKey => $id ) );
				$this->request->data = $this->User->find( 'first', $options );
			}
			$groups = $this->User->Group->find( 'list' );
			$this->set( compact( 'groups' ) );
		}

		/**
		 * delete method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function delete($id = null) {
			$this->User->id = $id;
			if ( !$this->User->exists() ) {
				throw new NotFoundException( __( 'Invalid user' ) );
			}
			$this->request->allowMethod( 'post', 'delete' );
			if ( $this->User->delete() ) {
				$this->Session->setFlash( __( 'The user has been deleted.' ) );
			}
			else {
				$this->Session->setFlash( __( 'The user could not be deleted. Please, try again.' ) );
			}

			return $this->redirect( array( 'action' => 'index' ) );
		}

		/*add_favourite*/
		public function add_favourite() {
			if ( $this->request->is( 'ajax' ) ) {
				$this->User->id = $this->Auth->user[ 'id' ];
				if ( $this->User->saveAssociated( json_decode( $this->request->data ) ) ) {
					return json_encode( array( 'success' => true ) );
				}
				else {
					return json_encode( array( 'success' => false ) );
				}
			}

			return $this->redirect( array( 'controller' => 'menu', 'action' => 'index' ) );
		}

		/*add_address*/
		public function add_address() {

			if ( $this->request->is( 'ajax' ) ) {
				if ( $this->User->saveAssocaited( $this->request->data ) ) {
					return json_encode( array( 'success' => true, 'error' => false ) );
				}
				else {
					return json_encode( array( 'success' => false, 'error' => 'Could not save address' ) );
				}
			}

			return $this->redirect( array( 'controller' => 'menu', 'action' => 'index' ) );
		}
		
		/*tabletlogin	*/
		public function tabletlogin() {
			if ($this->request->header('User-Agent') == 'xtreme-pos-tablet' && $this->request->data['number'] == 42) {
				if ( $this->Auth->loggedIn() ) {
					return $this->redirect( '/pages/vendor' );
				}
				if ( $this->request->is( 'post' )) {
					$options = array( 'conditions' => array( 'User.email' => 'thisisatablet@xtreme.ca' ) );
					$tmp = $this->User->read('first', $options);
					$this->Auth->login($tmp);
					return $this->redirect('/pages/vendor');
				} else {
					return $this->redirect( ___cakeUrl( 'users', '' ) );
				}
			} else {
				return $this->redirect( ___cakeUrl( 'menu', '' ) );
			}
		}

		/*login	*/
		public function login() {
			if ( $this->Auth->loggedIn() ) {
				$this->Session->setFlash( 'You are already logged in!' );
				return $this->redirect( '/menu' );
			}
			if ( $this->request->is('ajax') ) $this->layout = "ajax";
			if ( $this->request->is( 'post' ) ) {
				if ( $this->Auth->login() ) {
					$this->Session->setFlash( __( 'Welcome' ) );
					if ( $this->Session->check( 'storedUser' ) ) {
						$exUser           = $this->User->find( 'first', array( 'conditions' => array( 'User.email' => $this->request[ 'email' ] ) ) );
						$exUser[ 'User' ] = array_merge( $exUser[ 'User' ], $this->Session->read( 'storedUser' ) );
						$this->Session->delete( 'stashedUser' );
					}

					return $this->redirect( ___cakeUrl( 'users', 'edit', $this->Auth->user('id') ) );
				}
				else {
					$this->Session->setFlash( __( 'Your email or password was incorrect.' ) );

					return $this->redirect( ___cakeUrl( 'menu', '' ) );
				}
			}

			if ( $this->request->is( 'put' ) ) {
				$conditions = array( 'User.email' => $this->request->data[ 'User' ][ 'email' ] );
				if ( $this->User->hasAny( $conditions ) ) {
					$this->Session->setFlash( __( 'That email address is already registered.' ) );

					return $this->redirect( __cakeUrl( 'menu', '' ) );
				}
				else {
					$this->User->create();
					if ( $this->User->save( $this->request->data ) ) {
						$this->Session->setFlash( __( 'Account created.' ) );

						return $this->redirect( __cakeUrl( 'pages', 'splash' ) );
					}
					else {
						$this->Session->setFlash( __( 'The account could not be created. Please try again.' ) );
					}
					$groups = $this->User->Group->find( 'list' );
					$this->set( compact( 'groups' ) );
				}
			}

			if ( $this->Session->read( 'stashedUser' ) != null ) {
				$user = $this->Session->read( 'stashedUser' );
				$this->Session->write( 'stashedUser', null );
				if ( !$this->User->save( $user ) ) {
					$this->log( $this->User->validationErrors );
				}
				$conditions = array();
				if ( array_key_exists( 'google_uid', $user[ 'User' ] ) ) {
					$conditions[ 'User.google_uid' ] = $user[ 'User' ][ 'google_uid' ];
				}
				if ( array_key_exists( 'twitter_uid', $user[ 'User' ] ) ) {
					$conditions[ 'User.twitter_uid' ] = $user[ 'User' ][ 'twitter_uid' ];
				}
				if ( array_key_exists( 'facebook_uid', $user[ 'User' ] ) ) {
					$conditions[ 'User.facebook_uid' ] = $user[ 'User' ][ 'facebook_uid' ];
				}
				$user = $this->User->find( 'first', array( 'conditions' => $conditions ) );
				if ( $this->Auth->login( $user ) ) {
					$this->Session->setFlash( __( 'Welcome to Xtreme Pizza, ' + $user[ 'User' ][ 'firstname' ] ) );

					return $this->redirect( ___cakeUrl( 'users', 'edit', array( 'id' => $user[ 'User' ][ 'id' ] ) ) );
				}
				else {
					db( "Login Failed" );
					$this->Session->setFlash( __( 'Login failed. Please try again. (Stashed1)' ) );

					return $this->redirect( ___cakeUrl( 'menu', '' ) );
				}
			}

		}

		/*logout*/
		public function logout() {
			$this->Session->setFlash( 'Good-Bye' );
			$this->Auth->logout();
			$this->Session->destroy();
			$this->redirect( ___cakeUrl( 'menu', '' ) );
		}

		/*opauth_complete*/
		public function opauth_complete() {
			$this->log( "Openauth complete" );
			if ( $this->data[ 'validated' ] ) {
				$this->log( $this->data );
				$creds = $this->data;
				$prov  = $creds[ 'auth' ][ 'provider' ];
				if ( $prov == 'Google' ) {
					$newUser    = array( 'User' => array(
						'email'          => $creds[ 'auth' ][ 'info' ][ 'email' ],
						'google_uid'     => $creds[ 'auth' ][ 'uid' ],
						'firstname'      => $creds[ 'auth' ][ 'info' ][ 'first_name' ],
						'lastname'       => $creds[ 'auth' ][ 'info' ][ 'last_name' ],
						'email_verified' => $creds[ 'auth' ][ 'raw' ][ 'verified_email' ],
						'group_id'       => 2
					) );
					$conditions = array( 'User.google_uid' => $newUser[ 'User' ][ 'google_uid' ] );
				}
				elseif ( $prov == 'Twitter' ) {
					$split_name = explode( ' ', $creds[ 'auth' ][ 'info' ][ 'name' ], 2 );
					$newUser    = array( 'User' => array(
						'twitter_uid' => $creds[ 'auth' ][ 'uid' ],
						'firstname'   => $split_name[ 0 ],
						'lastname'    => $split_name[ 1 ],
						'group_id'    => 2
					) );
					$conditions = array( 'User.twitter_uid' => $newUser[ 'User' ][ 'twitter_uid' ] );
				}
				elseif ( $prov == 'Facebook' ) {
					$newUser    = array( 'User' => array(
						'email'          => $creds[ 'auth' ][ 'info' ][ 'email' ],
						'facebook_uid'   => $creds[ 'auth' ][ 'uid' ],
						'firstname'      => $creds[ 'auth' ][ 'info' ][ 'first_name' ],
						'lastname'       => $creds[ 'auth' ][ 'info' ][ 'last_name' ],
						'email_verified' => $creds[ 'auth' ][ 'raw' ][ 'verified' ],
						'group_id'       => 2
					) );
					$conditions = array( 'User.facebook_uid' => $newUser[ 'User' ][ 'facebook_uid' ] );
				}
				if ( $this->User->hasAny( $conditions ) ) {
					if ( $this->Session->read( 'stashedUser' ) != null ) {
						$exUser            = $this->User->find( 'first', array( 'conditions' => $conditions ) );
						$newUser           = $this->Session->read( 'stashedUser' );
						$newUser[ 'User' ] = array_merge( $exUser[ 'User' ], $newUser[ 'User' ] );
						if ( !$this->User->save( $newUser ) ) {
							$this->Session->setFlash( __( 'Login failed. Please try again.' ) );

							return $this->redirect( ___cakeUrl( 'menu', '' ) );
						}
						$this->Session->write( 'stashedUser', null );
					}
					$exUser = $this->User->find( 'first', array( 'conditions' => $conditions ) );
					if ( $this->Auth->login( $exUser['User'] ) ) {
						$this->Session->setFlash( __( "Welcome back, " + $exUser[ 'User' ][ 'firstname' ] ) );

						return $this->redirect( ___cakeUrl( 'users', 'edit', $this->Auth->user( 'id' ) ) );
					}
					else {
						db( 'Login failed' );
						$this->Session->setFlash( __( 'Login failed. Please try again.' ) );

						return $this->redirect( ___cakeUrl( 'menu', '' ) );
					}
				}
				else {
					$this->Session->write( 'stashedUser', $newUser );
					$this->render( 'merge-choice' );
				}
			}
			else {
				$this->Session->setFlash( __( 'Login failed. Please try again.' ) );

				return $this->redirect( ___cakeUrl( 'menu', 'index' ) );
			}
		}

		/*view_gdrive*/
		/*public function view_gdrive() {
			$data = $this->GoogleDriveFiles->listItems(array('q' => 'trashed = false'));
			$files = Hash::combine($data['items'], '{n}.id', '{n}.title');
			$this->set(compact('files'));
		}*/

		/*beforeFilter*/
		public function beforeFilter() {
			parent::beforeFilter();

			$this->Auth->allow( 'index', 'view_gdrive', 'view', 'opauth_complete', 'add', 'login', 'tabletlogin', 'logout' ); #, 'initDB');
		}

		public function initDB() {
			$group = $this->User->Group;

			// Allow admins to everything
			$group->id = 2;
			$this->Acl->allow( $group, 'controllers' );

			// allow managers to posts and widgets
			$group->id = 1;
			$this->Acl->deny( $group, 'controllers' );
			$this->Acl->allow( $group, 'controllers/users/home' );
			$this->Acl->allow( $group, 'controllers/users/settings' );
			$this->Acl->allow( $group, 'controllers/users/add_favourite' );
			$this->Acl->allow( $group, 'controllers/users/add_address' );

			$group->id = 3;
			$this->Acl->deny($group, 'controllers');
			$this->Acl->allow($group, 'controllers/orders/getPending');
			$this->Acl->allow($group, 'controllers/pages/vendor');
			$this->Acl->allow($group, 'controllers/orders/setStatus');
			$this->Acl->allow($group, 'controllers/users/tabletlogin');
			// we add an exit to avoid an ugly "missing views" error message
			echo "all done";
			exit;
		}
	}
