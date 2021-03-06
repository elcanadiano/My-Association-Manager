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
			),
			array(
				'url' => '/admin/teams/show_roster',
				'desc' => 'Show a Roster'
			),
			array(
				'url' => '/admin/leagues/add_team',
				'desc' => 'Add Team to League'
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

	/**
	 * The index function lists all of the teams, along with its home field name.
	 */
	function index()
	{
		$teams = $this->teams->retrieve_with_field();

		$data = array(
			'title' => 'Teams',
			'teams' => $teams,
			'js' => array(''),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);

		$this->load->view('admin/show_all_teams.php', $data);
	}

	/**
	 * Page to create a league.
	 */
	function new_team()
	{
		// Grab the list of fields for the dropdown.
		$fields = $this->fields->retrieve_id_name();

		// If there were no fields, then error out.
		if (!$fields)
		{
			show_error('You must add a field before adding a team.');
		}

		$data = array(
			'form_action' => 'action_create_team',
			'title' => 'Create a New Team',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'fields' => $fields,
			'name' => '',
			'homeid' => '',
			'city' => '',
			'region' => '',
			'msg' => 'Please enter the information regarding the new team.',
			'submit_message' => 'Add Team',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/team_edit.php', $data);
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
	function edit($tid)
	{
		$team = $this->teams->retrieve_by_id($tid);

		// If there is no team, error out.
		if (!$team)
		{
			show_error('No team was found with this ID.');
		}

		// Grab the list of fields for the dropdown.
		$fields = $this->fields->retrieve_id_name();

		// If there were no fields, then error out.
		if (!$fields)
		{
			show_error('You must add a field before adding a team.');
		}

		$data = array(
			'form_action' => 'action_edit_team',
			'title' => 'Edit a Team',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'fields' => $fields,
			'id' => $tid,
			'name' => $team->name,
			'homeid' => $team->homeid,
			'city' => $team->city,
			'region' => $team->region,
			'msg' => 'Please enter the following information for the new team.',
			'submit_message' => 'Edit Team',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/team_edit.php', $data);
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
			'js' => array('/js/admin/admin.js'),
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

		$this->load->view('admin/add_roster.php', $data);
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

	/**
	 * Function to show the roster.
	 */
	function show_roster()
	{
		// Retrieve all the players, teams, or seasons.
		$teams = $this->teams->retrieve_roster();
		$seasons = $this->seasons->retrieve_roster();

		// If there are no players, teams, or seasons...
		if (!$teams || !$seasons)
		{
			show_error('At least one team, one player, and one season must be added for roster functions to work.');
		}

		$data = array(
			'form_action' => 'action_show_roster',
			'title' => 'Show Roster',
			'js' => array('/js/admin/show_roster.js'),
			'css' => array('/styles/admin.css'),
			'teams' => $teams,
			'seasons' => $seasons,
			'submit_message' => 'Show Roster',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/show_roster.php', $data);
	}

	/**
	 * Action function to completely show a roster. If no roster exists for a team for that season,
	 * a friendly error is shown.
	 */
	function action_show_roster()
	{
		// Loading form validation helper.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tid', 'Team ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('sid', 'Season ID', 'trim|required|xss_clean');

		$tid = $this->input->post('tid');
		$sid = $this->input->post('sid');

		if ($this->form_validation->run())
		{
			// Get the team roster.
			$data = array(
				'players' => $this->roster->view_team_roster($tid, $sid)
			);

			$this->load->view('admin/show_teams.php', $data);
			return;
		}

		echo 'Please select a Team and a Season for the given team.';
	}
}