<?php
App::uses('OrdersOrbsOrbopt', 'Model');

/**
 * OrdersOrbsOrbopt Test Case
 *
 */
class OrdersOrbsOrboptTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orders_orbs_orbopt',
		'app.orders_orbs',
		'app.orbopt',
		'app.pricelist',
		'app.orb',
		'app.orbcat',
		'app.orbopts',
		'app.orbopts_orbcat',
		'app.pricedict',
		'app.orbs_orbopt',
		'app.orblist',
		'app.orblists_orb',
		'app.special',
		'app.special_condition',
		'app.special_feature',
		'app.specials_orb',
		'app.optflag',
		'app.orbopts_optflag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrdersOrbsOrbopt = ClassRegistry::init('OrdersOrbsOrbopt');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrdersOrbsOrbopt);

		parent::tearDown();
	}

}
