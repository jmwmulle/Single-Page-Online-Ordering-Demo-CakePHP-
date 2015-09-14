<?php
App::uses('AppModel', 'Model');
/**
 * Orbcat Model
 *
 * @property Orb $Orb
 */
class Orbcat extends AppModel {

	public $virtualFields = array('full_title' => 'CONCAT(Orbcat.title, " ", Orbcat.subtitle)',
	                              'menu_title' => 'CONCAT(Orbcat.subtitle, " ", Orbcat.title)'
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'primary_menu' => array(
					'notEmpty' => array(
						'rule' => array('notEmpty'),
						//'message' => 'Your custom message here',
						//'allowEmpty' => false,
						//'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
				),
		'orbopt_group' => array(
					'notEmpty' => array(
						'rule' => array('notEmpty'),
						//'message' => 'Your custom message here',
						//'allowEmpty' => false,
						//'required' => false,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
				),
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
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Orb' => array(
					'className' => 'Orb',
					'foreignKey' => 'orbcat_id',
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
	public $hasAndBelongsToMany = array(
		'Orbopts' => array(
			'className' => 'Orbopts',
			'joinTable' => 'orbopts_orbcats',
			'foreignKey' => 'orbcat_id',
			'associationForeignKey' => 'orbopt_id',
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
