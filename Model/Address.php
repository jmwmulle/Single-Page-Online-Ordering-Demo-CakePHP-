<?php
App::uses('AppModel', 'Model');
/**
 * Address Model
 *
 * @property User $User
 */
class Address extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'address'   => array( 
			'notEmpty' => array( 
				'rule' => array('notEmpty'), 
				'message' => 'You must supply your address.', 
			), 
		),
		'address_2'   => array(),
		'city'   => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'You must supply a city.',
			),
		),
		'province'   => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'You must supply a province.',
			),
		),
		'postal_code' => array(
			'postal' => array(
				'rule' => array( 'postal', null, 'ca' ),
				'message' => 'You must supply a valid Postal Code.',
			),
		),
		'details' => array(),

	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
