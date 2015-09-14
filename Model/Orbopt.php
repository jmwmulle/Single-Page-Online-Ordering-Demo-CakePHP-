<?php
App::uses('AppModel', 'Model');
/**
 * Orbopt Model
 *
 * @property Pricelist $Pricelist
 * @property Optflag $Optflag
 * @property Orbcat $Orbcat
 * @property Orb $Orb
 */
class Orbopt extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'pricelist_id' => array(
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
		'Pricelist' => array(
			'className' => 'Pricelist',
			'foreignKey' => 'pricelist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Optflag' => array(
			'className' => 'Optflag',
			'joinTable' => 'orbopts_optflags',
			'foreignKey' => 'orbopt_id',
			'associationForeignKey' => 'optflag_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Orbcat' => array(
			'className' => 'Orbcat',
			'joinTable' => 'orbopts_orbcats',
			'foreignKey' => 'orbopt_id',
			'associationForeignKey' => 'orbcat_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Orb' => array(
			'className' => 'Orb',
			'joinTable' => 'orbs_orbopts',
			'foreignKey' => 'orbopt_id',
			'associationForeignKey' => 'orb_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

}
