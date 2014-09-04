<?php
App::uses('Orbcat', 'Model');

/**
 * Orbcat Test Case
 *
 */
class OrbcatTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orbcat',
		'app.orb',
		'app.orbs_orbcat'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Orbcat = ClassRegistry::init('Orbcat');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Orbcat);

		parent::tearDown();
	}

}
