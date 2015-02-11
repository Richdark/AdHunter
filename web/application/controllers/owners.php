<?php

class Owners extends MY_Controller
{
	public function index()
	{
		//
	}

	/**
	 * Return current list of billboard owners from database.
	 *
	 * @param string $order Ascending (asc) or descending (desc) order
	 * @param string $sortby Column to be sorted by (possible values - id, name)
	*/
	public function current_list($order = 'asc', $sortby = 'name')
	{
		$this->load->model('Owner_model');

		// get list of all billboard owners
		$owners = $this->Owner_model->get_all($order, $sortby);
		$data   = array('owners' => $owners);

		// load view to display owners list
		$this->load->view('owners_list_serialized', $data);
	}
}

?>