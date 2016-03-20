<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $ticket = array(
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

	public $user = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'User created time'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'User modified time'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

}
