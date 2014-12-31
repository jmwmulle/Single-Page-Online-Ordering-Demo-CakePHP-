<?php
App::uses('FavouritesController', 'Controller');

/**
 * FavouritesController Test Case
 *
 */
class FavouritesControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.favourite',
		'app.user',
		'app.group',
		'app.order',
		'app.orb',
		'app.pricelist',
		'app.orbopt',
		'app.orbs_orbopt',
		'app.pricedict',
		'app.orbcat',
		'app.orbs_orbcat'
	);

}
