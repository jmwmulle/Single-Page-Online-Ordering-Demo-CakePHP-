<?php
App::uses('OrblistsOrb', 'Model');

/**
 * OrblistsOrb Test Case
 *
 */
class OrblistsOrbTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orblists_orb',
		'app.orb',
		'app.orbcat',
		'app.orbopts',
		'app.orbopts_orbcat',
		'app.pricelist',
		'app.orbopt',
		'app.optflag',
		'app.orbopts_optflag',
		'app.orbs_orbopt',
		'app.pricedict',
		'app.special',
		'app.specials_orb',
		'app.orblist'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrblistsOrb = ClassRegistry::init('OrblistsOrb');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrblistsOrb);

		parent::tearDown();
	}

}
