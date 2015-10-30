<?php
App::uses('OrboptsOptflag', 'Model');

/**
 * OrboptsOptflag Test Case
 *
 */
class OrboptsOptflagTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orbopts_optflag',
		'app.orbopt',
		'app.pricelist',
		'app.orb',
		'app.pricedict',
		'app.orbcat',
		'app.orbs_orbcat',
		'app.orbs_orbopt',
		'app.optflag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrboptsOptflag = ClassRegistry::init('OrboptsOptflag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrboptsOptflag);

		parent::tearDown();
	}

}
