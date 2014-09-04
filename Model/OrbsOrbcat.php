<?php
App::uses('AppModel', 'Model');
/**
 * OrbsOrbcat Model
 *
 * @property Orb $Orb
 * @property Orbcat $Orbcat
 */
class OrbsOrbcat extends AppModel {

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
		'orbcat_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
		'Orbcat' => array(
			'className' => 'Orbcat',
			'foreignKey' => 'orbcat_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
