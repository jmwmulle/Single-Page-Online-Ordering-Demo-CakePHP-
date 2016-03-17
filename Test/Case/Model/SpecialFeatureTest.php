<?php
App::uses('SpecialFeature', 'Model');

/**
 * SpecialFeature Test Case
 *
 */
class SpecialFeatureTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.special_feature',
		'app.special',
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
		'app.specials_orb'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->SpecialFeature = ClassRegistry::init('SpecialFeature');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->SpecialFeature);

		parent::tearDown();
	}

}
