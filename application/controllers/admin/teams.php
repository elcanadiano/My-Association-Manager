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
	}

	// Gets session data, passes info to header, main, and footer.
	function index($msg = '')
	{
		$teams = $this->teams->retrieve();

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
	function new_season($msg='', $name='', $start_date='', $end_date='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$msg = 'Please enter the following information for the new season.';
		}

		$data = array(
			'form_action' => 'action_create_season',
			'title' => 'Create a New Season',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'name' => $name,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'msg' => $msg,
			'submit_message' => 'Add Season',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/season_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action to add a season.
	 */
	function action_create_season()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean');

		$name = $this->input->post('name');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		if (strtotime($start_date) > strtotime($end_date))
		{
			$this->edit($sid, 'The end date must exceed the start date.', $name, $start_date, $end_date);
			return;
		}

		if ($this->form_validation->run())
		{
			if ($this->teams->insert($name, $start_date, $end_date))
			{
				$this->index('Season added successfully!');
				return;
			}
		}

		$this->new_season('One or more of the fields are invalid.', $name, $start_date, $end_date);
	}

	/**
	 * Function to edit a season.
	 */
	function edit($lid, $msg='', $name='', $start_date='', $end_date='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$season = $this->teams->retrieve_by_id($lid);

			// If there is no season, error out.
			if (!$season)
			{
				show_error('No season was found with this ID.');
			}

			$msg = 'Please enter the following information for the new season.';
			$name = $season->name;
			$start_date = $season->start_date;
			$end_date = $season->end_date;
		}

		$data = array(
			'form_action' => 'action_edit_season',
			'title' => 'Edit a Season',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'id' => $lid,
			'name' => $name,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'msg' => $msg,
			'submit_message' => 'Edit Season',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/season_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action to add a season.
	 */
	function action_edit_season()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean');

		$sid = $this->input->post('id');
		$name = $this->input->post('name');
		$start_date = $this->input->post('start_date');
		$end_date = $this->input->post('end_date');

		if (strtotime($start_date) > strtotime($end_date))
		{
			$this->edit($sid, 'The end date must exceed the start date.', $name, $start_date, $end_date);
			return;
		}

		if ($this->form_validation->run())
		{
			if ($this->teams->update($sid, $name, $start_date, $end_date))
			{
				$this->index('season Updated successfully!');
				return;
			}
		}

		$this->edit($sid, 'One or more of the fields are invalid.', $name, $start_date, $end_date);
	}
}