<?php
App::uses('AppModel', 'Model');
/**
 * Pricelist Model
 *
 * @property Orbopt $Orbopt
 * @property Orb $Orb
 */
class Pricelist extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'label' => array(
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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Orbopt' => array(
			'className' => 'Orbopt',
			'foreignKey' => 'pricelist_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Orb' => array(
			'className' => 'Orb',
			'foreignKey' => 'pricelist_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
