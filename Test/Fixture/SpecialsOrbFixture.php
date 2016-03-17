<?php
/**
 * SpecialsOrbFixture
 *
 */
class SpecialsOrbFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'special_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'orb_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'condition' => array('type' => 'boolean', 'null' => false, 'default' => null, 'key' => 'index'),
		'feature' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'price_1' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'price_2' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'price_3' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'price_4' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'price_5' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'special_id' => array('column' => array('special_id', 'orb_id'), 'unique' => 0),
			'condition' => array('column' => array('condition', 'feature'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'special_id' => 1,
			'orb_id' => 1,
			'condition' => 1,
			'feature' => 1,
			'price_1' => 1,
			'price_2' => 1,
			'price_3' => 1,
			'price_4' => 1,
			'price_5' => 1
		),
	);

}
