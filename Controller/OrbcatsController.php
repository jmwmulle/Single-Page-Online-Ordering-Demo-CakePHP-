<?php
	App::uses( 'AppController', 'Controller' );

	/**
	 * Orbcats Controller
	 *
	 * @property Orbcat             $Orbcat
	 * @property PaginatorComponent $Paginator
	 */
	class OrbcatsController extends AppController {
		private $empty_orb = array( "id"           => -1,
		                            "title"        => "empty_orb",
		                            "subtitle"     => false,
		                            "description"  => false,
		                            "price_matrix" => false,
		                            "config"       => false,
		                            "url"          => false );

		private $min_orb_count = 5;

		/**
		 * Components
		 *
		 * @var array
		 */
		public $components = array( 'Paginator' );

		/**
		 * index method
		 *
		 * @return void
		 */
		public function index() {
			$this->Orbcat->recursive = 0;
			$this->set( 'orbcats', $this->Paginator->paginate() );
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
			if ( !$this->Orbcat->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid orbcat' ) );
			}
			$options = array( 'conditions' => array( 'Orbcat.' . $this->Orbcat->primaryKey => $id ) );
			$this->set( 'orbcat', $this->Orbcat->find( 'first', $options ) );
		}

		/**
		 * add method
		 *
		 * @return void
		 */
		public function add() {
			if ( $this->request->is( 'post' ) ) {
				$this->Orbcat->create();
				if ( $this->Orbcat->save( $this->request->data ) ) {
					$this->Session->setFlash( __( 'The orbcat has been saved.' ) );

					return $this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Session->setFlash( __( 'The orbcat could not be saved. Please, try again.' ) );
				}
			}
			$orbs = $this->Orbcat->Orb->find( 'list' );
			$this->set( compact( 'orbs' ) );
		}

		/**
		 * edit method
		 *
		 * @throws NotFoundException
		 *
		 * @param string $id
		 *
		 * @return void
		 */
		public function edit($id = null) {
			if ( !$this->Orbcat->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid orbcat' ) );
			}
			if ( $this->request->is( array( 'post', 'put' ) ) ) {
				if ( $this->Orbcat->save( $this->request->data ) ) {
					$this->Session->setFlash( __( 'The orbcat has been saved.' ) );

					return $this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Session->setFlash( __( 'The orbcat could not be saved. Please, try again.' ) );
				}
			}
			else {
				$options             = array( 'conditions' => array( 'Orbcat.' . $this->Orbcat->primaryKey => $id ) );
				$this->request->data = $this->Orbcat->find( 'first', $options );
			}
			$orbs = $this->Orbcat->Orb->find( 'list' );
			$this->set( compact( 'orbs' ) );
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
			$this->Orbcat->id = $id;
			if ( !$this->Orbcat->exists() ) {
				throw new NotFoundException( __( 'Invalid orbcat' ) );
			}
			$this->request->allowMethod( 'post', 'delete' );
			if ( $this->Orbcat->delete() ) {
				$this->Session->setFlash( __( 'The orbcat has been deleted.' ) );
			}
			else {
				$this->Session->setFlash( __( 'The orbcat could not be deleted. Please, try again.' ) );
			}

			return $this->redirect( array( 'action' => 'index' ) );
		}

		/**
		 * Syntactic sugar wrapping OrbcatsController::menu()
		 */
		public function ajax_menu() {
			$this->menu( null, null, true );
		}

		/**
		 *
		 */
		public function resession() {
			$this->Auth->allow();
			$this->Session->destroy();
			$this->redirect( "/menu" );
		}

		/**
		 * @param      $orbcat_id
		 * @param null $orb_id
		 *
		 * @return array
		 */
		private function orbcard_stage($orbcat_id, $orb_id = null) {
			$this->Orbcat->Behaviors->load( 'Containable' );
			$orbcat_title = strtoupper( $this->Orbcat->field( 'title', array( '`Orbcat`.`id`' => $orbcat_id ) ) );
			if ( $orbcat_title != "XTREME SUBS" ) $orbcat_title = str_replace( "XTREME", "", $orbcat_title );
			if ( !$orb_id || $orb_id == -1 ) {
				$orb_id = $this->Orbcat->Orb->find( 'first', array( 'conditions' => ['orbcat_id' => $orbcat_id] ) )['Orb']['id'];
			}

			$conditions = array( 'conditions' => array( 'id' => $orbcat_id ),
			                     'contain'    => array(
				                     'Orb' => array(
					                     'conditions' => array( '`Orb`.`deprecated`' => false ),
					                     'fields'     => array( 'id', 'orbcat_id', 'title', 'description', 'config' ),
					                     'Pricedict',
					                     'Pricelist',
					                     'Orbopt'     => array(
						                     'conditions' => array( '`Orbopt`.`deprecated`' => false ),
						                     'Optflag'    => array( 'fields' => array( 'id', 'title' ) ),
						                     'OrbsOrbopt',
					                     )
				                     )
			                     ),
			);

			$orb_card = $this->requestAction( "orbs/orbcard/$orb_id/0");

			$data       = array( $this->requestAction( "orbs/orbcard/$orb_id/0" ),
			                     $this->Orbcat->find( 'all', $conditions )[ 0 ] );
			foreach ( $data[ 1 ][ 'Orb' ] as $i => $orb ) {
				$prices                                    = array_filter( array_slice( array_combine( $orb[ 'Pricedict' ], array_slice($orb[ 'Pricelist' ],0,-1) ), 1 ) );
				$data[ 1 ][ 'Orb' ][ $i ]             = array_slice( $orb, 0, 5 );
				$data[ 1 ][ 'Orb' ][ $i ][ 'Prices' ] = $prices;
			}
			// pad the orbs list with blanks to make the menu pretty
			while ( count( $data[ 1 ][ 'Orb' ] ) < $this->min_orb_count ) {
				array_push( $data[ 1 ][ 'Orb' ], $this->empty_orb );
			}
			return $data;
		}

		/**
		 * @param $orb_id
		 *
		 * @return mixed
		 */
		private function default_orbcat_id($orb_id) {
			// get the orbcat_id of the passed orb_id if provided;
			if ($orb_id && $this->Orbcat->Orb->exists($orb_id) ) {
				return $this->Orbcat->Orb->field('orbcat_id', array('`Orb`.`id`' => $orb_id));
			} else {
				return $this->Orbcat->field( 'id', array( 'title' => 'PIZZAS', 'subtitle' => 'ORIGINAL' ) );
			}
		}

		/**
		 * @return array
		 */
		private function orbcats_by_formatted_title() {
			$conditions = array( 'recursive' => -1, 'conditions' => array( '`Orbcat`.`primary_menu`' => true ) );
			$orbcats    = array();
			foreach ( $this->Orbcat->find( 'all', $conditions ) as $oc ) {
				$title                              = $oc[ 'Orbcat' ][ 'title' ];
				$subtitle                           = $oc[ 'Orbcat' ][ 'subtitle' ] ? $oc[ 'Orbcat' ][ 'subtitle' ]."&nbsp;" : null;
				$orbcats[ $oc[ 'Orbcat' ][ 'id' ] ] = $subtitle . $title;
			}

			return $orbcats;
		}

		/**
		 * @param null $orbcat_id
		 * @param null $orb_id
		 * @param bool $return
		 */
		public function menu($orbcat_id = null, $orb_id = null, $return = false) {
			$this->set_page_name( 'menu' );
			$refreshing = false;  // ie. refreshing the orbcard menu with a new orbcat
			if ( $this->request->is( "ajax" ) ) {
				$this->layout = 'ajax';
				$refreshing = !$return;
			}

			if ( !$orbcat_id || !$this->Orbcat->exists( $orbcat_id ) ) {
				$orbcat_id = $this->default_orbcat_id($orb_id);
			}
			$this->Orbcat->id = $orbcat_id;
			list($orbcard, $menu) = $this->orbcard_stage( $orbcat_id, $orb_id );

			$orbcats       = $this->orbcats_by_formatted_title();
			$optflags_list = $this->Orbcat->Orb->Orbopt->Optflag->find( 'list' );
			$order         = $this->Session->read( 'Cart.Order' ) ? $this->Session->read( 'Cart.Order' ) : array();

			$this->set( compact( 'orbcard', 'menu', 'orbcats', 'optflags_list', 'order', 'refreshing' ) );
		}

		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow( 'menu' );
		}
	}
