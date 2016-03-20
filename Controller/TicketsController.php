<?php
App::uses('AppController', 'Controller');
/**
 * Tickets Controller
 */
class TicketsController extends AppController {
	/**
	* Handles return types - as the client requested (.json)
	*/
	public $components = array('RequestHandler');

	/**
	* Return all tickets - access with /tickets.json
	*/
	public function index() {
		$tickets = $this->Ticket->find('all');
		$this->set(array(
			'tickets' => $tickets,
			'_serialize' => array('tickets')
		));
	}

	/**
	* Return all tickets in range - access with /tickets/range.json
	* @param offset records offset
	* @param limit records limit
	*/
	public function range($offset = 0, $limit = 10, $order_field = 'Ticket.priority', $order_direction = 'asc') {
		$q_params = array(
			'offset' => $offset,
			'limit' => $limit,
			'order' => array($order_field => $order_direction)
		);

		$tickets = $this->Ticket->find('all', $q_params);
		$count_all = $this->Ticket->find('count');
		$this->set(array(
			'tickets' => array('tickets' => $tickets, 'count' => $count_all),
			'_serialize' => array('tickets')
		));
	}

	/**
	* Return ticket by ID - access with /tickets/view/ID.json
	* @param id ticket id
	*/
	public function view($id) {
		$ticket = $this->Ticket->findById($id);
		$this->set(array(
			'ticket' => $ticket,
			'_serialize' => array('ticket')
		));
	}

	/**
	* Save ticket - access with /tickets/save.json + POST DATA
	*/
	public function save() {
		// refresh modified date
		if (isset($this->request->data['id'])) {
			$this->request->data['modified'] = null;
		}

		$this->Ticket->save($this->request->data);

		$this->set(array(
			'message' => $this->Ticket->makeMessage($this->Ticket->validationErrors, __('Ticket saved')),
			'_serialize' => array('message')
		));
	}

	/**
	* Close ticket. Adds closed time - access with /tickets/close/ID.json
	* @param id ticket id
	*/
	public function close($id) {
		$this->request->data['closed'] = date('Y-m-d H:i:s');

		$this->Ticket->save($this->request->data);

		$this->set(array(
			'message' => $this->Ticket->makeMessage($this->Ticket->validationErrors, __('Ticket closed')),
			'_serialize' => array('message')
		));
	}

	/**
	* Open ticket. Removes closed time - access with /tickets/open/ID.json
	* @param id ticket id
	*/
	public function open($id) {
		$this->request->data['closed'] = null;

		$this->Ticket->save($this->request->data);

		$this->set(array(
			'message' => $this->Ticket->makeMessage($this->Ticket->validationErrors, __('Ticket opened')),
			'_serialize' => array('message')
		));
	}

	/**
	* Delete ticket - access with /tickets/delete/ID.json
	*/
	public function delete($id) {
		$errs = array();
		if (!$this->Ticket->delete($id)) {
			$errs = array(__('Error deleting ticket'));
		}

		$this->set(array(
			'message' => $this->Ticket->makeMessage($errs, __('Ticket deleted')),
			'_serialize' => array('message')
		));
	}
}
