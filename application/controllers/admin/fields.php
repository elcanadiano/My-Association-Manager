<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class Fields extends C_Admin {
	static private $user_links = array(
		'title' => 'Field Functions',
		'links' => array(
			array(
				'url' => '/admin/fields/new_field',
				'desc' => 'New Field'
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('field_m','fields');
	}

	// Gets session data, passes info to header, main, and footer.
	function index()
	{
		$fields = $this->fields->retrieve();

		$data = array(
			'title' => 'Fields',
			'fields' => $fields,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);

		$this->load->view('admin/show_all_fields.php', $data);
	}

	/**
	 * Page to create a league.
	 */
	function new_field()
	{
		$data = array(
			'form_action' => 'action_create_field',
			'title' => 'Add a new Field',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'name' => '',
			'address' => '',
			'city' => '',
			'region' => '',
			'pitch_type' => '',
			'msg' => 'Please enter the following information for the new field.',
			'submit_message' => 'Add Field',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/field_edit.php', $data);
	}

	/**
	 * Action to add a Field.
	 */
	function action_create_field()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('region', 'Region', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pitch_type', 'Pitch Type', 'trim|required|xss_clean');

		$name = $this->input->post('name');
		$address = $this->input->post('address');
		$city = $this->input->post('city');
		$region = $this->input->post('region');
		$pitch_type = $this->input->post('pitch_type');

		if ($this->form_validation->run())
		{
			if ($this->fields->insert($name, $address, $city, $region, $pitch_type))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Field added successfully!'
				));
				return;
			}
		}

		echo json_encode(array(
			'status' => 'danger',
			'message' => 'One or more of the fields are invalid.'
		));
	}

	/**
	 * Function to edit a field.
	 */
	function edit($fid)
	{
		$field = $this->fields->retrieve_by_id($fid);

		// If there is no field, error out.
		if (!$field)
		{
			show_error('No field was found with this ID.');
		}

		$data = array(
			'form_action' => 'action_edit_field',
			'title' => 'Edit a Field',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'id' => $fid,
			'name' => $field->name,
			'address' => $field->address,
			'city' => $field->city,
			'region' => $field->region,
			'pitch_type' => $field->pitch_type,
			'msg' => 'Please enter the new information for ' . $field->name . '.',
			'submit_message' => 'Edit Field',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/field_edit.php', $data);
	}

	/**
	 * Action to add a field.
	 */
	function action_edit_field()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('region', 'Region', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pitch_type', 'Pitch Type', 'trim|required|xss_clean');

		$fid = $this->input->post('id');
		$name = $this->input->post('name');
		$address = $this->input->post('address');
		$city = $this->input->post('city');
		$region = $this->input->post('region');
		$pitch_type = $this->input->post('pitch_type');

		if ($this->form_validation->run())
		{
			if ($this->fields->update($fid, $name, $address, $city, $region, $pitch_type))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Field updated successfully!'
				));
				return;
			}
		}

		echo json_encode(array(
			'status' => 'danger',
			'message' => 'One or more of the fields are invalid.'
		));
	}
}