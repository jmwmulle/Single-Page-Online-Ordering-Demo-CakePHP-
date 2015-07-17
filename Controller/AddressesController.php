<?php
App::uses('AppController', 'Controller');
/**
 * Addresses Controller
 *
 */
class AddressesController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;


	public function validate_session() {
		return $this->Session->check("Cart.User.Address");
	}
}
