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
			),
			array(
				'url' => '/admin/teams/roster_add',
				'desc' => 'Add to Roster'
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('team_m','teams');
		$this->load->model('field_m','fields');
		$this->load->model('roster_m','roster');
		$this->load->model('player_m','players');
		$this->load->model('season_m','seasons');
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

	/**
	 * Function to add a team member to a roster.
	 */
	function roster_add($pid = 0, $tid = 0, $sid = 0)
	{
		// Retrieve all the players, teams, or seasons.
		$players = $this->players->retrieve_roster();
		$teams = $this->teams->retrieve_roster();
		$seasons = $this->seasons->retrieve_roster();

		// If there are no players, teams, or seasons...
		if (!$players || !$teams || !$seasons)
		{
			show_error('At least one team, one player, and one season must be added for roster functions to work.');
		}

		$data = array(
			'form_action' => 'action_add_roster',
			'title' => 'Add to Roster',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'players' => $players,
			'teams' => $teams,
			'seasons' => $seasons,
			'pid' => $pid,
			'tid' => $tid,
			'sid' => $sid,
			'submit_message' => 'Add to Roster',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/add_roster.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action function to add a player to a team for a given season (roster).
	 */
	function action_add_roster()
	{
		// Loading form validation helper.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('pid', 'Player ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('tid', 'Team ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('sid', 'Season ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('squad_number', 'Squad Number', 'trim|required|xss_clean');

		$pid = $this->input->post('pid');
		$tid = $this->input->post('tid');
		$sid = $this->input->post('sid');
		$squad_number = $this->input->post('squad_number');

		if ($this->form_validation->run())
		{
			// Invalid Squad Number
			if ($squad_number < 1 || $squad_number > 99)
			{
				echo json_encode(array(
					'status' => 'danger',
					'message' => 'The squad number must be between 0 and 99.'
				));
				return;
			}

			// If an object is returned, that means either the player is already on the roster
			// or the squad number has been assigned.
			if ($this->roster->is_invalid_player($pid, $tid, $sid, $squad_number))
			{
				echo json_encode(array(
					'status' => 'danger',
					'message' => 'Either the player is already on the roster or the squad number has been assigned.'
				));
				return;
			}

			if ($this->roster->insert($pid, $tid, $sid, $squad_number))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Player successfully added to roster!'
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