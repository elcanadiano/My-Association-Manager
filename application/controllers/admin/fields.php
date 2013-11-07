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
	function index($msg = '')
	{
		$fields = $this->fields->retrieve();

		$data = array(
			'title' => 'Fields',
			'msg' => $msg,
			'fields' => $fields,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);
		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/show_all_fields.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Page to create a league.
	 */
	function new_field($msg='', $name='', $address='', $city='', $region='', $pitch_type='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$msg = 'Please enter the required fields of the new fields.';
		}

		$data = array(
			'form_action' => 'action_create_field',
			'title' => 'Add a new Field',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'name' => $name,
			'address' => $address,
			'city' => $city,
			'region' => $region,
			'pitch_type' => $pitch_type,
			'msg' => $msg,
			'submit_message' => 'Add Season',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/field_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action to add a season.
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
				$this->index('Season added successfully!');
				return;
			}
		}

		$this->new_field('One or more of the fields are invalid.', $name, $address, $city, $region, $pitch_type);
	}

	/**
	 * Function to edit a season.
	 */
	function edit($fid, $msg='', $name='', $address='', $city='', $region='', $pitch_type='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$field = $this->fields->retrieve_by_id($fid);

			// If there is no season, error out.
			if (!$field)
			{
				show_error('No season was found with this ID.');
			}

			$msg = 'Please enter the following information for the new season.';
			$name = $field->name;
			$address = $field->address;
			$city = $field->city;
			$region = $field->region;
			$pitch_type = $field->pitch_type;
		}

		$data = array(
			'form_action' => 'action_edit_field',
			'title' => 'Edit a Field',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'id' => $fid,
			'name' => $name,
			'address' => $address,
			'city' => $city,
			'region' => $region,
			'pitch_type' => $pitch_type,
			'msg' => $msg,
			'submit_message' => 'Edit Field',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/field_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
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
				$this->index('Field Updated successfully!');
				return;
			}
		}

		$this->edit($fid, 'One or more of the fields are invalid.', $name, $address, $city, $region, $pitch_type);
	}
}