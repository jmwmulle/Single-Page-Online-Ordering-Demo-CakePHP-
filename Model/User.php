<?php
	App::uses( 'AppModel', 'Model' );

	/**
	 * User Model
	 *
	 * @property Order $Order
	 * @property Group $Group
	 */
	class User extends AppModel {

//		public $actsAs = array( 'Acl' => array( 'type' => 'requester' ) );

		/**
		 * Validation rules
		 *
		 * @var array
		 */
		public $validate = array(
			'id' => array(
				'notEmpty' => array(
					'rule' => array('notEmpty'),
				),
			),
			'firstname' => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'lastname'  => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'email'     => array(
				'email' => array(
					'rule' => array( 'email' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'group_id'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'twitter_uid'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					'allowEmpty' => true,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'facebook_uid'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'google_uid'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'email_verified'  => array(
				'boolean' => array(
					'rule' => array( 'boolean' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			)
		);

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * belongsTo associations
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'Group' => array(
				'className'  => 'Group',
				'foreignKey' => 'group_id',
				'conditions' => '',
				'fields'     => '',
				'order'      => ''
			)
		);

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * hasMany associations
		 *
		 * @var array
		 */
		public $hasMany = array(
			'Order' => array(
				'className'    => 'Order',
				'foreignKey'   => 'user_id',
				'dependent'    => false,
				'conditions'   => '',
				'fields'       => '',
				'order'        => '',
				'limit'        => '',
				'offset'       => '',
				'exclusive'    => '',
				'finderQuery'  => '',
				'counterQuery' => ''
			)
		);

//		public function beforeSave($options = array()) {
//			$this->data[ 'User' ][ 'password' ] = AuthComponent::password(
//			                                                   $this->data[ 'User' ][ 'password' ]
//			);
//
//			return true;
//		}

//		public function parentNode() {
//			if ( !$this->id && empty( $this->data ) ) {
//				return null;
//			}
//			if ( isset( $this->data[ 'User' ][ 'group_id' ] ) ) {
//				$groupId = $this->data[ 'User' ][ 'group_id' ];
//			}
//			else {
//				$groupId = $this->field( 'group_id' );
//			}
//			if ( !$groupId ) {
//				return null;
//			}
//			else {
//				return array( 'Group' => array( 'id' => $groupId ) );
//			}
//		}
	}
