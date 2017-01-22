<?php
App::uses('OrdersOrbopt', 'Model');

/**
 * OrdersOrbopt Test Case
 *
 */
class OrdersOrboptTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orders_orbopt',
		'app.order',
		'app.user',
		'app.group',
		'app.favourite',
		'app.orb',
		'app.orbcat',
		'app.orbopts',
		'app.orbopts_orbcat',
		'app.pricelist',
		'app.orbopt',
		'app.optflag',
		'app.orbopts_optflag',
		'app.orbs_orbopt',
		'app.orders_orb',
		'app.orders_orbs_orbopt',
		'app.pricedict',
		'app.orblist',
		'app.orblists_orb',
		'app.special',
		'app.special_condition',
		'app.special_feature',
		'app.specials_orb',
		'app.address'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrdersOrbopt = ClassRegistry::init('OrdersOrbopt');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrdersOrbopt);

		parent::tearDown();
	}

}
