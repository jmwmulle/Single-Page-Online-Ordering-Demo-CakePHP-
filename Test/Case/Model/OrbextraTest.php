<?php
App::uses('Orbextra', 'Model');

/**
 * Orbextra Test Case
 *
 */
class OrbextraTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orbextra',
		'app.orb',
		'app.orbs_orbextra'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Orbextra = ClassRegistry::init('Orbextra');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Orbextra);

		parent::tearDown();
	}

}
