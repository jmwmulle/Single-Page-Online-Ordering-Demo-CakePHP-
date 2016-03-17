<?php
/**
 * SpecialFeatureFixture
 *
 */
class SpecialFeatureFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'special_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'choose' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'receive' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'quantity' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'orblist_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'orbcat_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'special_id' => array('column' => 'special_id', 'unique' => 0)
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
			'choose' => 1,
			'receive' => 1,
			'quantity' => 1,
			'orblist_id' => 1,
			'orbcat_id' => 1
		),
	);

}
