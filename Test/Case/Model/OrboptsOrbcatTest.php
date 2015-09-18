<?php
App::uses('OrboptsOrbcat', 'Model');

/**
 * OrboptsOrbcat Test Case
 *
 */
class OrboptsOrbcatTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orbopts_orbcat',
		'app.orbopt',
		'app.pricelist',
		'app.orb',
		'app.pricedict',
		'app.orbcat',
		'app.orbs_orbcat',
		'app.orbs_orbopt'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrboptsOrbcat = ClassRegistry::init('OrboptsOrbcat');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrboptsOrbcat);

		parent::tearDown();
	}

}
