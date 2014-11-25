<?php
App::uses('AppModel', 'Model');
/**
 * Orb Model
 *
 * @property Orbcat $Orbcat
 * @property Orbopt $Orbopt
 */
class Orb extends AppModel {

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
		'subtitle' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'description' => array(
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
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'pricedict_id' => array(
					'notEmpty' => array(
						'rule' => array('notEmpty'),
						//'message' => 'Your custom message here',
						//'allowEmpty' => false,
						//'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
				)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsToMany associations
 *
 * @var array
 */
	public $belongsToMany = array(
		'Orbit' => array(
					'className' => 'Orbit',
					'joinTable' => 'orbs_orbits',
					'foreignKey' => 'orb_id',
					'associationForeignKey' => 'orbit_id',
					'unique' => 'keepExisting',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'finderQuery' => '',
				)
	);

	public $belongsTo = array(
		'Pricelist' => array(
			'className' => 'Pricelist',
			'foreign_key' => 'pricelist_id'
		),
		'Pricedict' => array(
			'className' => 'Pricedict',
			'foreign_key' => 'pricedict_id'
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Orbcat' => array(
			'className' => 'Orbcat',
			'joinTable' => 'orbs_orbcats',
			'foreignKey' => 'orb_id',
			'associationForeignKey' => 'orbcat_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),
		'Orbopt' => array(
			'className' => 'Orbopt',
			'joinTable' => 'orbs_orbopts',
			'foreignKey' => 'orb_id',
			'associationForeignKey' => 'orbopt_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		),

	);

}
