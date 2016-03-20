<?php
/**
 * Ticket Fixture
 */
class TicketFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'ticket';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index', 'comment' => 'Assignd user ID'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'comment' => 'Ticket title', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Ticket description', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'Ticke priority (1(highest) - 5(lowest))'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null, 'comment' => 'Ticket creation time'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'Ticket modification time'),
		'closed' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'Ticket closet time'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0)
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
			'title' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'priority' => 1,
			'created' => '2016-03-19 11:19:13',
			'modified' => '2016-03-19 11:19:13',
			'closed' => '2016-03-19 11:19:13'
		),
	);

}
