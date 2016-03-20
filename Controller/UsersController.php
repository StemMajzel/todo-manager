<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 */
class UsersController extends AppController {
  /**
	* Handles return types - as the client requested (.json)
	*/
	public $components = array('RequestHandler');

	/**
	* Return all users - access with /users.json
	*/
	public function index() {
    $q_params = array(
      'recursive' => -1,
      'fields' => array('id', 'username')
    );

		$users = $this->User->find('all', $q_params);
		$this->set(array(
			'users' => $users,
			'_serialize' => array('users')
		));
	}

}
