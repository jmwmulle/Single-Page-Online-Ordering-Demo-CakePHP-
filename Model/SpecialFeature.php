<?php
App::uses('AppModel', 'Model');
/**
 * SpecialFeature Model
 *
 * @property Special $Special
 * @property Orblist $Orblist
 * @property Orbcat $Orbcat
 */
class SpecialFeature extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'special_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
//				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'choose' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
//				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'receive' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'quantity' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'orblist_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'orbcat_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
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
		'Special' => array(
			'className' => 'Special',
			'foreignKey' => 'special_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Orblist' => array(
			'className' => 'Orblist',
			'foreignKey' => 'orblist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Orbcat' => array(
			'className' => 'Orbcat',
			'foreignKey' => 'orbcat_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
