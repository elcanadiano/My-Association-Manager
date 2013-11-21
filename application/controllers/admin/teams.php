<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class Teams extends C_Admin {
	static private $user_links = array(
		'title' => 'Team Functions',
		'links' => array(
			array(
				'url' => '/admin/teams/new_team',
				'desc' => 'New Team'
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('team_m','teams');
		$this->load->model('field_m','fields');
	}

	// Gets session data, passes info to header, main, and footer.
	function index($msg = '')
	{
		$teams = $this->teams->retrieve_with_field();

		$data = array(
			'title' => 'Teams',
			'msg' => $msg,
			'teams' => $teams,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);
		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/show_all_teams.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Page to create a league.
	 */
	function new_team($msg='', $name='', $homeid='', $city='', $region='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$msg = 'Please enter the information regarding the new team.';
		}

		// Grab the list of fields for the dropdown.
		$fields = $this->fields->retrieve_id_name();

		$data = array(
			'form_action' => 'action_create_team',
			'title' => 'Create a New Team',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'fields' => $fields,
			'name' => $name,
			'homeid' => $homeid,
			'city' => $city,
			'region' => $region,
			'msg' => $msg,
			'submit_message' => 'Add Team',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/team_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action to add a team.
	 */
	function action_create_team()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('homeid', 'Field', 'trim|required|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('region', 'Region', 'trim|required|xss_clean');

		$name = $this->input->post('name');
		$homeid = $this->input->post('homeid');
		$city = $this->input->post('city');
		$region = $this->input->post('region');

		if ($this->form_validation->run())
		{
			if ($this->teams->insert($name, $homeid, $city, $region))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Team added successfully!'
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
	 * Function to edit a team.
	 */
	function edit($tid, $msg='', $name='', $homeid='', $city='', $region='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$team = $this->teams->retrieve_by_id($tid);

			// If there is no team, error out.
			if (!$team)
			{
				show_error('No team was found with this ID.');
			}

			$msg = 'Please enter the following information for the new team.';
			$name = $team->name;
			$homeid = $team->homeid;
			$city = $team->city;
			$region = $team->region;
		}

		// Grab the list of fields for the dropdown.
		$fields = $this->fields->retrieve_id_name();

		$data = array(
			'form_action' => 'action_edit_team',
			'title' => 'Edit a Team',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'fields' => $fields,
			'id' => $tid,
			'name' => $name,
			'homeid' => $homeid,
			'city' => $city,
			'region' => $region,
			'msg' => $msg,
			'submit_message' => 'Edit Team',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/team_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action to add a team.
	 */
	function action_edit_team()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('homeid', 'Field', 'trim|required|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
		$this->form_validation->set_rules('region', 'Region', 'trim|required|xss_clean');

		$tid = $this->input->post('id');
		$name = $this->input->post('name');
		$homeid = $this->input->post('homeid');
		$city = $this->input->post('city');
		$region = $this->input->post('region');

		if ($this->form_validation->run())
		{
			if ($this->teams->update($tid, $name, $homeid, $city, $region))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Team updated successfully!'
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