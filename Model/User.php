<?php
	App::uses( 'AppModel', 'Model' );

	/**
	 * User Model
	 *
	 * @property Order $Order
	 * @property Group $Group
	 */
	class User extends AppModel {

		public $actsAs = array( 'Acl' => array( 'type' => 'requester' ) );

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
					'message' => 'Please provide your name',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'alphaNumeric' => array(
					'rule' => array('alphaNumeric'),
					'message' => 'Illegal character(s) in First Name',
				),
			),
			'lastname'  => array(
				'notEmpty' => array(
					'rule' => array( 'notEmpty' ),
					'message' => 'Please provide your name',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'alphaNumeric' => array(
					'rule' => array('alphaNumeric'),
					'message' => 'Illegal character(s) in Last Name',
				),
			),
			'email'     => array(
				'email' => array(
					'rule' => array( 'email' ),
					'message' => 'Please enter a valid e-mail address',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'unique' => array(
					'rule' => array('checkUnique', array('id', 'email'), false),
					'message' => 'Sorry, that email address is already in use.',
				),
			),
			'password'  => array(
				'password' => array(
					'rule' => array('minLength', '8'),
					'message' => 'Password must be between 8 and 60 charcters long.',
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
					//'allowEmpty' => false,
					'required' => false,
					//'last' => false, // Stop validation after this rule
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'facebook_uid'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					'required' => false,
					//'last' => false, // Stop validation after this rule
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'google_uid'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					'required' => false,
					//'last' => false, // Stop validation after this rule
					'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'email_verified'  => array(
				'boolean' => array(
					'rule' => array( 'boolean' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					'required' => false,
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
			),
			'Favourite' => array(
				'className'    => 'Favourite',
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
			),
			'Address' => array(
				'className'    => 'Address',
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
			),

		);

		public function beforeSave($options = array()) {
			if (isset($this->data['User']['password'])) {
				$this->data[ 'User' ][ 'password' ] = AuthComponent::password(
					$this->data[ 'User' ][ 'password' ]
				);
			}

			return true;
		}

		public function parentNode() {
			if ( !$this->id && empty( $this->data ) ) {
				return null;
			}
			if ( isset( $this->data[ 'User' ][ 'group_id' ] ) ) {
				$groupId = $this->data[ 'User' ][ 'group_id' ];
			}
			else {
				$groupId = $this->field( 'group_id' );
			}
			if ( !$groupId ) {
				return null;
			}
			else {
				return array( 'Group' => array( 'id' => $groupId ) );
			}
		}
			
		public function checkUnique($ignoredData, $fields, $or = false) {
			return $this->isUnique($fields, $or);
		}
	}
