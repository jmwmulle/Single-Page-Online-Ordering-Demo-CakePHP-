<?php
App::uses('OrbsOrbextra', 'Model');

/**
 * OrbsOrbextra Test Case
 *
 */
class OrbsOrbextraTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orbs_orbextra',
		'app.orb',
		'app.category',
		'app.orbcat',
		'app.orbs_orbcat',
		'app.orbextra'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrbsOrbextra = ClassRegistry::init('OrbsOrbextra');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrbsOrbextra);

		parent::tearDown();
	}

}
