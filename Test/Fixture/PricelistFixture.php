<?php
/**
 * PricelistFixture
 *
 */
class PricelistFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'p1' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'p2' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'p3' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'p4' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'p5' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'p6' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'p1' => 1,
			'p2' => 1,
			'p3' => 1,
			'p4' => 1,
			'p5' => 1,
			'p6' => 1
		),
	);

}
