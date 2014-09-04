<?php
/**
 * OrbsOrbextraFixture
 *
 */
class OrbsOrbextraFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'orb_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'orbextra_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'orb_id' => array('column' => 'orb_id', 'unique' => 0),
			'orbextra_id' => array('column' => 'orbextra_id', 'unique' => 0)
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
			'orb_id' => 1,
			'orbextra_id' => 1
		),
	);

}
