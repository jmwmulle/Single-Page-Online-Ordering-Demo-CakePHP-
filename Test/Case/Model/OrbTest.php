<?php
App::uses('Orb', 'Model');

/**
 * Orb Test Case
 *
 */
class OrbTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.orb',
		'app.category',
		'app.orbcat',
		'app.orbs_orbcat',
		'app.orbextra',
		'app.orbs_orbextra'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Orb = ClassRegistry::init('Orb');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Orb);

		parent::tearDown();
	}

}
