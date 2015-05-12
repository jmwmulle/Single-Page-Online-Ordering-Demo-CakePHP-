<?php
App::uses('AppModel', 'Model');
/**
 * OrboptsOptflag Model
 *
 * @property Orbopt $Orbopt
 * @property Optflag $Optflag
 */
class OrboptsOptflag extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'orbopt_id' => array(
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
		'Orbopt' => array(
			'className' => 'Orbopt',
			'foreignKey' => 'orbopt_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Optflag' => array(
			'className' => 'Optflag',
			'foreignKey' => 'optflag_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
