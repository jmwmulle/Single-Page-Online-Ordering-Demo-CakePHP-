<?php
App::uses('SpecialsOrb', 'Model');

/**
 * SpecialsOrb Test Case
 *
 */
class SpecialsOrbTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.specials_orb',
		'app.special',
		'app.eligibility_rule',
		'app.orb',
		'app.orbcat',
		'app.orbopts',
		'app.orbopts_orbcat',
		'app.pricelist',
		'app.orbopt',
		'app.optflag',
		'app.orbopts_optflag',
		'app.orbs_orbopt',
		'app.pricedict'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SpecialsOrb = ClassRegistry::init('SpecialsOrb');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SpecialsOrb);

		parent::tearDown();
	}

}
