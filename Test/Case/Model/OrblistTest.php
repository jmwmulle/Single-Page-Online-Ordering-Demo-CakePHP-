<?php
App::uses('Orblist', 'Model');

/**
 * Orblist Test Case
 *
 */
class OrblistTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orblist',
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
		'app.orblists_orb'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Orblist = ClassRegistry::init('Orblist');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Orblist);

		parent::tearDown();
	}

}
