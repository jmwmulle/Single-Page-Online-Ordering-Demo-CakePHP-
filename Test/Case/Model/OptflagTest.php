<?php
App::uses('Optflag', 'Model');

/**
 * Optflag Test Case
 *
 */
class OptflagTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.optflag',
		'app.orbopt',
		'app.pricelist',
		'app.orb',
		'app.pricedict',
		'app.orbcat',
		'app.orbs_orbcat',
		'app.orbs_orbopt',
		'app.orbopts_optflag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Optflag = ClassRegistry::init('Optflag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Optflag);

		parent::tearDown();
	}

}
