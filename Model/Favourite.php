<?php
	App::uses( 'AppModel', 'Model' );

	/**
	 * User Model
	 *
	 * @property Order $Order
	 * @property Group $Group
	 */
	class Favourite extends AppModel {

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
			'orb_id'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'user_id'  => array(
				'numeric' => array(
					'rule' => array( 'numeric' ),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'orbopts_arrangment'  => array(
				'numeric' => array(
					//'message' => 'Your custom message here',
					'allowEmpty' => false,
					'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'preparation_instructions'  => array(
				'numeric' => array(
					'rule' => array( 'maxlength', 140 ),
					//'message' => 'Your custom message here',
					'allowEmpty' => true,
					'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);

		//The Associations below have been created with all possible keys, those that are not needed can be removed

		/**
		 * belongsTo associations
		 *
		 * @var array
		 */
		public $belongsTo = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'user_id',
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
		public $hasOne = 'Orb';
		

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
		public function checkUnique($ignoredData, $fields, $or = false) {
			return $this->isUnique($fields, $or);
		}
	}
