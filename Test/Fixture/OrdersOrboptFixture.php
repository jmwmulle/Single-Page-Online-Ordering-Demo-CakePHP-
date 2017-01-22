<?php
/**
 * OrdersOrboptFixture
 *
 */
class OrdersOrboptFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'order_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'orbopt_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'orb_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'coverage' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 7, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'order_id' => array('column' => array('order_id', 'orbopt_id', 'orb_id'), 'unique' => 0)
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
			'order_id' => 1,
			'orbopt_id' => 1,
			'orb_id' => 1,
			'coverage' => 'Lorem'
		),
	);

}
