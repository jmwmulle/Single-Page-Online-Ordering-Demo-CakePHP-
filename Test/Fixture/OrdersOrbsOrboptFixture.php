<?php
/**
 * OrdersOrbsOrboptFixture
 *
 */
class OrdersOrbsOrboptFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'orders_orbs_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'orbopt_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'coverage' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			
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
			'orders_orbs_id' => 1,
			'orbopt_id' => 1,
			'coverage' => 'Lorem ipsum dolor sit ame'
		),
	);

}
