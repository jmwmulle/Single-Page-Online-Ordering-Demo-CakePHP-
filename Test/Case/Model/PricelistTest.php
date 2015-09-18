<?php
App::uses('Pricelist', 'Model');

/**
 * Pricelist Test Case
 *
 */
class PricelistTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.pricelist',
		'app.orbopt',
		'app.optflag',
		'app.orbopts_optflag',
		'app.orbcat',
		'app.orb',
		'app.pricedict',
		'app.orbs_orbopt',
		'app.orbopts',
		'app.orbopts_orbcat',
		'app.output'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Pricelist = ClassRegistry::init('Pricelist');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Pricelist);

		parent::tearDown();
	}

}
