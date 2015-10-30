<?php
/**
 * OrboptsOptflagFixture
 *
 */
class OrboptsOptflagFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'orbopt_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'optflag_id' => array('type' => 'integer', 'null' => true, 'default' => '-1', 'unsigned' => false),
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
			'orbopt_id' => 1,
			'optflag_id' => 1
		),
	);

}
