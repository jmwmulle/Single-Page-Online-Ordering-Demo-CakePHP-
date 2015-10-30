<?php
App::uses('Variable', 'Model');

/**
 * Variable Test Case
 *
 */
class VariableTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.variable'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Variable = ClassRegistry::init('Variable');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Variable);

		parent::tearDown();
	}

}
