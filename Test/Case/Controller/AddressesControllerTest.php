<?php
App::uses('AddressesController', 'Controller');

/**
 * AddressesController Test Case
 *
 */
class AddressesControllerTest extends ControllerTestCase {

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

}
