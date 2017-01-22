<?php
/**
 * OrderFixture
 *
 */
class OrderFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'address_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'state' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => true),
		'subtotal' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'total' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'hst' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'delivery' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'cash' => array('type' => 'boolean', 'null' => false, 'default' => null),
		'detail' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'invoice' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'client_id' => array('column' => 'user_id', 'unique' => 0),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'id' => array('column' => 'id', 'unique' => 0),
			'address_id' => array('column' => 'address_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'address_id' => 1,
			'state' => 1,
			'subtotal' => 1,
			'total' => 1,
			'hst' => 1,
			'delivery' => 1,
			'cash' => 1,
			'detail' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'invoice' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created' => '2016-03-22 18:28:40',
			'modified' => '2016-03-22 18:28:40'
		),
	);

}
