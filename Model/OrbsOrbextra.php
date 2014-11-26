<?php
App::uses('AppModel', 'Model');
/**
 * OrbsOrbextra Model
 *
 * @property Orb $Orb
 * @property Orbextra $Orbextra
 */
class OrbsOrbextra extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'orb_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'orbextra_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'pricing_matrix' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
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
		'Orb' => array(
			'className' => 'Orb',
			'foreignKey' => 'orb_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Orbextra' => array(
			'className' => 'Orbextra',
			'foreignKey' => 'orbextra_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
