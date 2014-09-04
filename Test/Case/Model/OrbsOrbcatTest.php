<?php
App::uses('OrbsOrbcat', 'Model');

/**
 * OrbsOrbcat Test Case
 *
 */
class OrbsOrbcatTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orbs_orbcat',
		'app.orb',
		'app.category',
		'app.orbcat',
		'app.orbextra',
		'app.orbs_orbextra'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrbsOrbcat = ClassRegistry::init('OrbsOrbcat');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrbsOrbcat);

		parent::tearDown();
	}

}
