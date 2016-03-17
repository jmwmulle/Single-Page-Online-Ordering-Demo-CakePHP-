<?php
	App::uses( 'AppController', 'Controller', 'ConnectionManager' );

	/**
	 * Orbs Controller
	 *
	 * @property Orb                $Orb
	 * @property PaginatorComponent $Paginator
	 */
	class OrbsController extends AppController {

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
			$this->Orb->recursive = 0;
			$this->set( 'orbs', $this->Paginator->paginate() );
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
			if ( !$this->Orb->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid orb' ) );
			}
			// ajax only if requested as an orbcard
			$options = array( 'conditions' => array( 'Orb.' . $this->Orb->primaryKey => $id ) );
			$this->set( 'orb', $this->Orb->find( 'first', $options ) );
			if ( $this->request->is( 'ajax' ) ) {
				$this->render( 'orbcard', 'ajax' );
			}
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
			if ( $this->request->is( 'post' ) && $this->request->is( 'ajax' ) ) {
				$response = array( "success" => true, "error" => false, "submitted_data" => $this->request->data );
				$orb_id   = null;
				if ( !$this->Orb->save( $this->request->data[ 'Orb' ] ) ) {
					$response[ 'error' ][ 'Orb' ] = $this->Orb->validationErrors;
					$response[ 'success' ]        = false;
				}

				return $this->render_ajax_response( $response );
				$this->set( compact( 'response' ) );
			}

			$orbcats = $this->Orb->Orbcat->find( 'list' );
			$this->set( compact( 'orbcats' ) );
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
			if ( !$this->Orb->exists( $id ) ) {
				throw new NotFoundException( __( 'Invalid orb' ) );
			}
			if ( $this->request->is( array( 'post', 'put' ) ) ) {
				if ( $this->Orb->save( $this->request->data ) ) {
					$this->Session->setFlash( __( 'The orb has been saved.' ) );

					return $this->redirect( array( 'action' => 'index' ) );
				}
				else {
					$this->Session->setFlash( __( 'The orb could not be saved. Please, try again.' ) );
				}
			}
			else {
				$options             = array( 'conditions' => array( 'Orb.' . $this->Orb->primaryKey => $id ) );
				$this->request->data = $this->Orb->find( 'first', $options );
			}
			$orbcats   = $this->Orb->Orbcat->find( 'list' );
			$orbextras = $this->Orb->Orbextra->find( 'list' );
			$this->set( compact( 'orbcats', 'orbextras' ) );
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
			$this->Orb->id = $id;
			if ( !$this->Orb->exists() ) {
				throw new NotFoundException( __( 'Invalid orb' ) );
			}
			$this->request->allowMethod( 'post', 'delete' );
			if ( $this->Orb->delete() ) {
				$this->Session->setFlash( __( 'The orb has been deleted.' ) );
			}
			else {
				$this->Session->setFlash( __( 'The orb could not be deleted. Please, try again.' ) );
			}

			return $this->redirect( array( 'action' => 'index' ) );
		}

		/**
		 * deprecate method - basically a soft delete, orb remains to prevent errors with favorite orders and past orders
		 * but won't be visible in vendor_ui or public menu
		 *
		 * @param null $id
		 */
		public function deprecate($id = null) {
			if ( $this->request->is( 'ajax' ) && $this->request->is( 'post' ) ) {
				$this->Orb->id = $id;
				$response      = array( 'success' => array( 'orb' => true, 'orb_orbopt' => true ),
				                        'error'   => false, 'submitted_data' => array( 'id' => $id ) );
				if ( !$this->Orb->save( array( 'deprecated' => true ) ) ) {
					$response[ 'success' ][ 'orb' ] = false;
					$response[ 'error' ]            = $this->Orb->validationErrors;
				}
				$this->loadModel( 'OrbsOrbopt' );
				if ( !$this->OrbsOrbopt->updateAll( array( 'deprecated' => true ), array( 'orb_id' => $id ) ) ) {
					$response[ 'success' ][ 'orbs_orbopt' ] = false;
					$response[ 'error' ]                    = $this->OrbsOrbopt->validationErrors;
				}
				$this->render_ajax_response( $response );
			}
			else {
				$this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		public function orbcard($id, $render = true) {
			$submitted_data = compact('id', 'render');
			if ( ($this->request->is( 'ajax' ) or in_array('bare', $this->request->params)) and $this->Orb->exists( $id ) or true) {
				$this->set('ajax', true);
				$this->Orb->Behaviors->load( 'Containable' );
				$orb_conditions = [ 'conditions' => [ '`Orb`.`id`' => $id ],
				                                    'contain'    => [
				                                        'Pricedict',
				                                        'Pricelist',
				                                        'Orbopt' => [
				                                            'conditions' => [ '`Orbopt`.`deprecated`' => false],
				                                            'Optflag'    => ['fields' => ['id','title' ]],
				                                            'OrbsOrbopt'
				                                        ]
				                                    ]];
				$orb = Hash::remove( $this->Orb->find( 'all', $orb_conditions), "{n}.Orbopt.{n}.Optflag.{n}.OrboptsOptflag")[0];
				$portionable = $this->Orb->Orbcat->field( 'portionable', [ 'id' => $orb[ 'Orb' ][ 'orbcat_id' ] ] );
				$orb[ 'Orb' ][ 'Optflag' ] = [];

				if ( !array_key_exists( 'Orbopt', $orb ) ) $orb[ 'Orbopt' ] = [];

				foreach ( $orb[ 'Orbopt' ] as $i => $opt ) {
					$orb[ 'Orbopt' ][ $i ][ 'default' ] = $opt[ 'OrbsOrbopt' ][ 'default' ];
					if (array_key_exists('Optflag', $opt) ) {
						foreach ( $opt[ 'Optflag' ] as $optflag ) {
							$orb[ 'Orb' ][ 'Optflag' ][ $optflag[ 'id' ] ] = $optflag[ 'title' ];
						}
					}
				}
				$orb[ 'Orb' ][ 'Orbopt' ] = $orb[ 'Orbopt' ];
				$orb[ 'Prices' ]          = array_filter( array_slice( array_combine( $orb[ 'Pricedict' ], array_slice($orb[ 'Pricelist' ],0, -1) ), 1 ) );
				unset( $orb[ 'Pricelist' ] );
				unset( $orb[ 'Pricedict' ] );
				unset( $orb[ 'Orbopt' ] );
				$orb                            = array_filter( $orb );
				$orb[ 'Orb' ][ 'default_opts' ] = $this->requestAction( "OrbsOrbopts/fetch_default_opts/$id" );
				$online_ordering = $this->system_status(ONLINE_ORDERING, false, null, false);
				$this->set( compact('orb', 'portionable', 'submitted_data', 'online_ordering') );

				return $render ? $this->render( 'menu_item', 'ajax' ) : $orb;
			}
			else {
				$this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		public function beforeFilter() {
			parent::beforeFilter();
			$this->Auth->allow( 'menu_item', 'upload_menu' );
		}

		public function vendor_ui($refreshing = false) {
			$this->layout = 'vendor_ui';
			$this->set( 'page_name', "Vendor Interface" );
			$this->system_status();
			$orbs    = $this->Orb->find( 'all', array( 'recursive' => 1 ) );
			$orbopts = Hash::remove( $this->Orb->Orbopt->find( 'all' ), "{n}.Orb" );
			foreach ( $orbopts as $i => $opt ) {
				$orbopts[ $i ] = Hash::insert( $opt, "Orbopt.flags", Hash::extract( $opt, "Optflag.{n}.id" ) );
			}
			$orbopts_groups = $this->Orb->Orbcat->find( 'list', array( 'conditions' => array( '`Orbcat`.`orbopt_group`' => 1 ) ) );
			$oc_cond        = array( 'recursive'  => -1, 'fields' => array( 'id', 'full_title' ),
			                         'conditions' => array( 'primary_menu' => 1 ) );
			$orbcats        = Hash::combine( $this->Orb->Orbcat->find( 'all', $oc_cond ), "{n}.Orbcat.id", "{n}.Orbcat.full_title" );
			$optflags       = $this->Orb->Orbopt->Optflag->find( 'list' );
			$pricedicts = $this->build_pricedicts();
			$opt_pricelists = $this->build_opt_pricelists();
			$this->Orb->Special->Behaviors->load( 'Containable' );
			$sp_cond = [ 'conditions' => ['`Special`.`deprecated`' => false],
			             'recursive' => 2,
                        'contain'    => [
                            'SpecialCondition' => [
	                            'Orbcat',
	                            'Orblist'
			                 ],
                            'SpecialFeature' => [
                                'Orbcat',
                                'Orblist'
                             ],
                            'Orb' => [
                                'SpecialsOrb',
                                'Pricedict'
                            ]
                        ]];
			$specials = $this->Orb->Special->find('all', $sp_cond);
			foreach ($specials as $i => $sp) {
				$sp['SpecialFeature']['Orb'] = [];
				$sp['SpecialCondition']['Orb'] = [];
				foreach ($sp['Orb'] as $o) {
					for ($j = 1; $j < 6; $j++) {
						$o["price_$j"] = $o['SpecialsOrb']["price_$j"] ? $o['Pricedict']["l$j"] : false;
					}
					$dest = $o['SpecialsOrb']['condition'] ? 'SpecialCondition' : 'SpecialFeature';
					unset($o['Pricedict']);
					unset($o['SpecialsOrb']);
					$o = array_filter($o);
					array_push($sp[$dest]['Orb'], $o);
					unset($sp['Orb']);
				}
				$specials[$i] = array_filter($sp);
			}
			$this->set( compact( 'orbs', 'orbcats', 'orbopts', 'orbopts_groups', 'optflags', 'pricedicts', 'opt_pricelists', 'specials') );
			switch ($refreshing) {
				case "menu":
					return $this->render( "/Elements/vendor_ui/menu_table", "ajax" );
				case "opts":
					return $this->render( "/Elements/vendor_ui/menu_options", "ajax" );
				case "specials":
					return $this->render( "/Elements/vendor_ui/specials", "ajax" );
			}
		}

		private function build_opt_pricelists() {
			$this->loadModel( 'Pricelist' );
			$opt_pricelist_ids = Hash::extract(
			                         $this->Orb->Orbopt->find('all', ['recursive' => -1, 'fields' => ['pricelist_id']]),
				                         "{n}.Orbopt.pricelist_id");
			return Hash::extract( $this->Pricelist->find('all', ['recursive' => -1,
			                                      'conditions' => ['NOT' => ['label' => '']]]),
			       "{n}.Pricelist");
		}

		private function build_pricedicts() {
			$this->loadModel( 'Pricedict' );
			$pricedict_options = array( 'recursive' => -1, 'fields' => array( 'l1', 'l2', 'l3', 'l4', 'l5' ) );
			$pricedicts        = $this->Pricedict->find( 'all', $pricedict_options );

			$pricedict_strings  = array();
			$reduced_pricedicts = array();
			foreach ( $pricedicts as $i => $pd ) {
				foreach ( $pd[ 'Pricedict' ] as $i => $label ) {
					if ( !in_array( $label, $reduced_pricedicts ) ) {
						array_push( $reduced_pricedicts, $label );
					}
				}
			}


			$pricedicts = array_filter( $reduced_pricedicts );
			asort( $pricedicts );

			return $pricedicts;
		}

		public function orbopt_config($orb_id) {
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = "ajax";
				$orb          = $this->Orb->find( 'all', array( 'conditions' => array( '`Orb`.`id`' => $orb_id ) ) );
				$this->loadModel( 'OrbsOrbopt' );
				$default_opts = $this->OrbsOrbopt->find( 'all', array( 'conditions' => array( 'orb_id'     => $orb_id,
				                                                                              'default'    => true,
				                                                                              'deprecated' => 0 ),
				                                                       'fields'     => array( 'orbopt_id' ) )
				);
				$default_opts = Hash::extract( $default_opts, "{n}.OrbsOrbopt.orbopt_id" );

				$orb            = $orb[ 0 ];
				$active_orbopts = Hash::extract( $orb[ 'Orbopt' ], "{n}.id" );
				$orbcats        = $this->Orb->Orbcat->find( 'all', ['recursive' => -1] );
				$optflags       = $this->Orb->Orbopt->Optflag->find( 'list' );
				$orbopts_groups = $this->Orb->Orbcat->find( 'list', ['conditions' => ['`Orbcat`.`orbopt_group`' => 1 ]] );
				$orbopts        = Hash::remove( $this->Orb->Orbopt->find( 'all' ), "{n}.Orb" );
				foreach ( $orbopts as $i => $opt ) {
					if ($opt['Orbopt']['deprecated'] == true) {
						unset($orbopts[$i]);
					} else {
						$orbopts[ $i ] = Hash::insert( $opt, "Orbopt.flags", Hash::extract( $opt, "Optflag.{n}.id" ) );
					}
				}
				$orbopts = array_filter($orbopts);

				$this->set( compact( 'orb', 'orbcats', 'optflags', 'orbopts_groups', 'orbopts', 'active_orbopts', 'default_opts' ) );
			}
			else {
				$this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		public function update_menu($orb_id, $attribute) {
			if ( $this->request->is( 'ajax' ) && $this->request->is( 'post' ) ) {
				$this->layout = "ajax";
				$response     = ['success' => true, 'error' => false, 'submitted_data' => $this->request->data];
				$data         = $this->request->data;
				try {
					switch ( $attribute ) {
						case "orbopts":
							$response['success'] = ['orb' => true, 'orbopt' => true];
							$response['error'] = ['orb' => false, 'orbopt' => false];
							$this->Orb->id = $orb_id;
							if ( !$this->Orb->save($this->request->data['Orb']) ) {
								$response['success'][ 'orb' ] = false;
								$response['success'][ 'orb' ]   = $this->Orb->validationErrors;
							}

							$this->loadModel( 'OrbsOrbopt' );
							$this->OrbsOrbopt->deleteAll( array( "`OrbsOrbopt`.`orb_id`" => $orb_id ) );
							$response[ 'orbs_orbopts_ids' ] = array();
							$save_data                      = array();
							foreach ( $this->request->data[ 'Orbopt' ] as $opt_id => $state ) {
								if ( $state ) {
									array_push( $save_data, array( 'orb_id'    => $orb_id,
									                               'orbopt_id' => $opt_id,
									                               'default'   => $state == 2 ? 1 : 0 )
									);
								}
							}
							if ( empty($save_data) ) break; // ie. orb has no opts
							if ( $this->OrbsOrbopt->saveMany( $save_data, array( 'atomic' => true ) ) ) {
								array_push( $response[ 'orbs_orbopts_ids' ], $this->OrbsOrbopt->id );
							}
							else {
								$response[ 'success' ]['orbopt'] = false;
								$response[ 'error' ]['orbopt']   = $this->OrbsOrbopt->validationErrors;
							}
							break;
						case "orbcat":
							$this->Orb->id = $orb_id;
							$this->Orb->set( array( 'orbcat_id' => $data[ 'Orb' ][ 'orbcat' ] ) );
							if ( !$this->Orb->save() ) {
								$response[ 'success' ] = false;
								$response[ 'error' ]   = $this->Orb->validationErrors;
							}
							break;
						case "prices":
							$result   = $this->update_prices( $data[ 'Orb' ][ 'id' ], $data[ 'Pricedict' ], $data[ 'Pricelist' ] );
							$response = array_merge( $response, $result );
							break;
						default:
							$this->Orb->id = $orb_id;
							if ( !$this->Orb->saveField( $attribute, $data[ 'Orb' ][ $attribute ] ) ) {
								$response[ 'success' ] = false;
								$response[ 'error' ]   = $this->Orb->validationErrors;
							}
							break;
					}
				} catch ( Exception $e ) {
					$response[ 'error' ]   = $e;
					$response[ 'success' ] = false;
				}
				$this->set( 'response', $response );
			}
			else {
				$this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}

		private function update_prices($orb_id, $pricedict, $pricelist) {
			$this->loadModel( 'Pricedict' );
			$this->loadModel( 'Pricelist' );
			$pricedict_keys = array( 'l1', 'l2', 'l3', 'l4', 'l5' );
			$pricelist_keys = array( 'p1', 'p2', 'p3', 'p4', 'p5' );

			$created_pricedict = false;
			$created_pricelist = false;
			$pricedict_id      = null;
			$pricelist_id      = null;
			$response          = array();

			// format pricedict & pricelist values from AJAX submission
			foreach ( $pricedict as $key => $val ) {
				$pricedict[ $key ] = $val ? $val : null;
			}
			foreach ( $pricelist as $key => $val ) {
				$val               = (float)preg_replace( "/([^0-9\\.])/i", "", $val );
				$pricelist[ $key ] = $val ? (float)$val : null;
			}

			$pricedict = array_filter( $pricedict );
			$pricelist = array_filter( $pricelist );
			while ( count( $pricedict ) < 5 ) {
				array_push( $pricedict, null );
			};
			while ( count( $pricelist ) < 5 ) {
				array_push( $pricelist, null );
			};

			// ensure no one's set non-sequential entries (ie. p1, p2 & p5) as this breaks the views on the public site
			$pricedict                         = array_combine( $pricedict_keys, $pricedict );
			$pricelist                         = array_combine( $pricelist_keys, $pricelist );
			$response[ 'modified_submission' ] = array( 'pricedict' => $pricedict, 'pricelist' => $pricelist );

			// look for matching lists & dicts
			$pricedict_res = array_values( $this->Pricedict->find( 'list', array( 'conditions' => $pricedict ) ) );
			$pricelist_res = array_values( $this->Pricelist->find( 'list', array( 'conditions' => $pricelist ) ) );

			$response[ 'search_res' ] = array( 'pricedict' => $pricedict_res, 'pricelist' => $pricelist_res );
			// create new dicts & lists if need be
			if ( count( $pricedict_res ) < 1 ) {
				if ( $this->Pricedict->save( $pricedict ) ) {
					$created_pricedict = true;
					$pricedict_id      = $this->Pricedict->getInsertId();
				}
				else {
					$response[ 'success' ] = false;
					$response[ 'error' ]   = $this->Pricedict->validationErrors;

					return $response;
				}
			}
			else {
				$pricedict_id = $pricedict_res[ 0 ];
			};

			if ( count( $pricelist_res ) < 1 ) {
				if ( $this->Pricelist->save( $pricelist ) ) {
					$created_pricelist = true;
					$pricelist_id      = $this->Pricelist->getInsertId();
				}
				else {
					$response[ 'success' ] = false;
					$response[ 'error' ]   = $this->Pricelist->validationErrors;

					return $response;
				}
			}
			else {
				$pricelist_id = $pricelist_res[ 0 ];
			};

			// update the orb
			$update_data          = array( '`Orb`.`pricedict_id`' => $pricedict_id,
			                               '`Orb`.`pricelist_id`' => $pricelist_id );
			$conditions           = array( '`Orb`.`id`' => $orb_id );
			$response[ 'submit' ] = array( $update_data, $conditions );
			if ( !$this->Orb->updateAll( $update_data, $conditions ) ) {
				$response[ 'success' ] = false;
				$response[ 'error' ]   = $this->Pricedict->validationErrors;
				if ( $created_pricedict ) {
					$this->Pricedict->delete( $pricedict_id );
				}
				if ( $created_pricelist ) {
					$this->Pricelist->delete( $pricelist_id );
				}
			}
			else {
				$orb                   = $this->Orb->find( 'all', array( 'conditions' => array( '`Orb`.`id`' => $orb_id ) ) );
				$this->set('price_buttons', false);
				$response[ 'replace' ] = array( 'element' => 'vendor_ui/prices',
				                                'options' => array( 'orb'        => $orb[ 0 ],
				                                                    'pricedicts' => $this->build_pricedicts() ) );
			}

			return $response;
		}

		public function pricedict_add() {
			// yes I know how fucking stupid this is, I didn't feel like making a controller for one function
			if ( $this->request->is( 'ajax' ) ) {
				$this->layout = "ajax";
				if ( $this->request->is( 'post' ) ) {
					$response = array( "success" => true, "error" => false, "submitted_data" => $this->request->data );
					$this->loadModel( 'Pricedict' );
					if ( count( array_filter( $this->request->data[ 'Pricedict' ] ) ) > 0 ) {
						if ( !$this->Pricedict->save( $this->request->data ) ) {
							$response[ 'success' ] = false;
							$response[ 'error' ]   = $this->Pricedict->validationErrors();
						}
					}
					$this->set( 'response', $response );
				}
			}
			else {
				$this->redirect( ___cakeUrl( 'orbcats', 'menu' ) );
			}
		}


		public function before_filter() {
			parent::before_filter();
			$this->Auth->allow( 'csv_to_menu', 'upload_menu', 'menu_item' );
		}
	}
