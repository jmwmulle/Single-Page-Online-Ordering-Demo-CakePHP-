<?php
App::uses('OrbsOrder', 'Model');

/**
 * OrbsOrder Test Case
 *
 */
class OrbsOrderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orbs_order',
		'app.orb',
		'app.orbcat',
		'app.orbs_orbcat',
		'app.orbextra',
		'app.orbs_orbextra',
		'app.order',
		'app.client'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrbsOrder = ClassRegistry::init('OrbsOrder');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrbsOrder);

		parent::tearDown();
	}

}
