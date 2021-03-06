<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class Leagues extends C_Admin {
	static private $user_links = array(
		'title' => 'League Functions',
		'links' => array(
			array(
				'url' => '/admin/leagues/new_league',
				'desc' => 'New League'
			),
			array(
				'url' => '/admin/leagues/add_team',
				'desc' => 'Add Team to League'
			),
			array(
				'url' => '/admin/leagues/show_standings',
				'desc' => 'View Standings'
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('team_m','teams');
		$this->load->model('league_m','league');
		$this->load->model('season_m','seasons');
		$this->load->model('standings_m','standings');
	}

	/**
	 * Loads an index page that displays all the leagues in the organization (defunct or not)
	 */
	function index()
	{
		$leagues = $this->league->retrieve();

		$data = array(
			'title' => 'Leagues',
			'leagues' => $leagues,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);

		$this->load->view('admin/show_all_leagues.php', $data);
	}

	/**
	 * Page to create a league.
	 */
	function new_league()
	{
		$data = array(
			'form_action' => 'action_create_league',
			'title' => 'Create a New League',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'name' => '',
			'age_cat' => '',
			'msg' => 'Please enter the following information for the new league.',
			'submit_message' => 'Add League',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/league_edit.php', $data);
	}

	/**
	 * Action to add a league.
	 */
	function action_create_league()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('age_cat', 'Age Category', 'trim|required|xss_clean');

		$name = $this->input->post('name');
		$age_cat = $this->input->post('age_cat');

		if ($this->form_validation->run())
		{
			if ($this->league->insert_league($name, $age_cat))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'League added successfully!'
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
	 * Function to edit a league.
	 */
	function edit($lid)
	{
		$league = $this->league->retrieve_by_id($lid);

		// If there is no league, error out.
		if (!$league)
		{
			show_error('No league was found with this ID.');
		}

		$data = array(
			'form_action' => 'action_edit_league',
			'title' => 'Edit a league',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'id' => $lid,
			'name' => $league->name,
			'age_cat' => $league->age_cat,
			'msg' => 'Please enter the new information for ' . $league->name . '.',
			'submit_message' => 'Edit League',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/league_edit.php', $data);
	}

	/**
	 * Action to add a league.
	 */
	function action_edit_league()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('age_cat', 'Age Category', 'trim|required|xss_clean');

		$lid = $this->input->post('name');
		$name = $this->input->post('name');
		$age_cat = $this->input->post('age_cat');

		if ($this->form_validation->run())
		{
			if ($this->league->update_league($lid, $name, $age_cat))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'League updated successfully!'
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
	 * Function to add a team to a league for a season.
	 */
	function add_team($tid = 0, $lid = 0, $sid = 0)
	{
		// Retrieve all the players, teams, or seasons.
		$teams = $this->teams->retrieve_roster();
		$leagues = $this->league->retrieve_roster();
		$seasons = $this->seasons->retrieve_roster();

		// If there are no players, teams, or seasons...
		if (!$teams || !$leagues || !$seasons)
		{
			show_error('At least one team, one league, and one season must be added for standings functions to work.');
		}

		$data = array(
			'form_action' => 'action_add_team',
			'title' => 'Add to League',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'teams' => $teams,
			'leagues' => $leagues,
			'seasons' => $seasons,
			'tid' => $tid,
			'lid' => $lid,
			'sid' => $sid,
			'msg' => 'Please select the team that you wish to add to the league for the season.',
			'submit_message' => 'Add to League',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/add_standings.php', $data);
	}

	/**
	 * Action function to add a team to a league for a season.
	 */
	function action_add_team()
	{
		// Loading form validation helper.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('tid', 'Team ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('lid', 'Player ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('sid', 'Season ID', 'trim|required|xss_clean');

		$tid = $this->input->post('tid');
		$lid = $this->input->post('lid');
		$sid = $this->input->post('sid');

		if ($this->form_validation->run())
		{
			if ($this->standings->insert($tid, $lid, $sid))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'The team has successfully been added to the league.'
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
	 * Function for an admin to view a standings.
	 */
	function show_standings()
	{
		// Retrieve all the players, teams, or seasons.
		$leagues = $this->league->retrieve_roster();
		$seasons = $this->seasons->retrieve_roster();

		// If there are no players, teams, or seasons...
		if (!$leagues || !$seasons)
		{
			show_error('At least one league, one team, and one season must be added for standings functions to work.');
		}

		$data = array(
			'form_action' => 'action_show_standings',
			'title' => 'View Standings',
			'js' => array('/js/admin/show_roster.js'),
			'css' => array('/styles/admin.css'),
			'leagues' => $leagues,
			'seasons' => $seasons,
			'msg' => 'Please select the league and the season for the standings you wish to see.',
			'submit_message' => 'Show Standings',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/show_standings.php', $data);
	}

	/**
	 * Action function to fetch the standings for a league for a given season.
	 *
	 * Returns a friendly message if there are no teams in that league for a given season.
	 */
	function action_show_standings()
	{
		// Loading form validation helper.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('lid', 'League ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('sid', 'Season ID', 'trim|required|xss_clean');

		$lid = $this->input->post('lid');
		$sid = $this->input->post('sid');

		if ($this->form_validation->run())
		{
			// Get the team roster.
			$data = array(
				'teams' => $this->standings->retrieve($lid, $sid)
			);

			$this->load->view('admin/action_show_standings.php', $data);
			return;
		}

		echo 'Please select a Team and a Season for the given team.';
	}
}