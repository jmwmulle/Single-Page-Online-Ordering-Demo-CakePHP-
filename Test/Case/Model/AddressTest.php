<?php
App::uses('Address', 'Model');

/**
 * Address Test Case
 *
 */
class AddressTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.address',
		'app.user',
		'app.group',
		'app.order',
		'app.favourite',
		'app.orb',
		'app.pricelist',
		'app.orbopt',
		'app.orbs_orbopt',
		'app.pricedict',
		'app.orbcat',
		'app.orbs_orbcat'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Address = ClassRegistry::init('Address');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Address);

		parent::tearDown();
	}

}
