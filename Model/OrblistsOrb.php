<?php
App::uses('AppModel', 'Model');
/**
 * OrblistsOrb Model
 *
 * @property Orb $Orb
 * @property Orblist $Orblist
 */
class OrblistsOrb extends AppModel {

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
		'orblist_id' => array(
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
		'Orblist' => array(
			'className' => 'Orblist',
			'foreignKey' => 'orblist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
