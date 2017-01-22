<?php
App::uses('OrdersOrb', 'Model');

/**
 * OrdersOrb Test Case
 *
 */
class OrdersOrbTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orders_orb',
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
		'app.pricedict',
		'app.orblist',
		'app.orblists_orb',
		'app.special',
		'app.special_condition',
		'app.special_feature',
		'app.specials_orb',
		'app.address',
		'app.orders_orbs_orbopt'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrdersOrb = ClassRegistry::init('OrdersOrb');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrdersOrb);

		parent::tearDown();
	}

}
