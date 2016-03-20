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

	/**
	* Return user by ID - access with /users/view/ID.json
	* @param id user id
	*/
	public function view($id) {
		$user = $this->User->findById($id);
		$this->set(array(
			'user' => $user,
			'_serialize' => array('user')
		));
	}

	/**
	* Save user - access with /users/save.json + POST DATA
	*/
	public function save() {
		$this->User->save($this->request->data);

		$this->set(array(
			'message' => $this->User->makeMessage($this->User->validationErrors, __('User saved')),
			'_serialize' => array('message')
		));
	}

}
