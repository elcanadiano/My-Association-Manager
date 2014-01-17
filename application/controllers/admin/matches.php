<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class Matches extends C_Admin {
	static private $user_links = array(
		'title' => 'Match Functions',
		'links' => array(
			array(
				'url' => '/admin/matches/new_match',
				'desc' => 'New Match'
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('match_m','matches');
		$this->load->model('field_m','fields');
		$this->load->model('season_m','seasons');
		$this->load->model('team_m','teams');
		$this->load->model('league_m','leagues');
	}

	/**
	 * Index function to show all the matches.
	 */
	function index()
	{
		$data = array(
			'title' => 'Matches',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);

		$this->load->view('admin/show_all_matches.php', $data);
	}

	/**
	 * Page to create a match.
	 */
	function new_match($sid=0, $lid=0)
	{
		// Get the seasons, leagues, and fields.
		$seasons = $this->seasons->retrieve_roster();
		$leagues = $this->leagues->retrieve();
		$fields = $this->fields->retrieve_id_name();

		// There has to be at least one of each to work.
		if (!$fields || !$seasons || !$leagues)
		{
			show_error('At least one season, one league, and one field must be added for matches to work.');
		}

		$data = array(
			'form_action' => 'action_new_match',
			'title' => 'Create a New Match',
			'js' => array(
				'/js/admin/admin.js',
				'/js/admin/game.js',
			),
			'css' => array(
				'/styles/admin.css',
				'/styles/jquery-ui-1.10.3.custom.min.css'
			),
			'seasons' => $seasons,
			'leagues' => $leagues,
			'sid' => $sid,
			'lid' => $lid,
			'fields' => $fields,
			'name' => NULL,
			'date' => NULL,
			'time' => NULL,
			'htid' => NULL,
			'atid' => NULL,
			'h_g' => NULL,
			'a_g' => NULL,
			'has_been_played' => NULL,
			'msg' => 'Please enter the league, season, and field information. The team list will show when these are selected.',
			'submit_message' => 'Add Match',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));
		$this->load->view('admin/match_edit.php', $data);
	}

	/**
	 * Gets all the teams for a given league when the season, league, and field are selected.
	 */
	function action_get_teams_for_league($sid, $lid)
	{
		if (!$sid || !$lid)
		{
			echo 'Something was incorrectly passed in.';
			return;
		}

		$teams = $this->teams->retrieve_by_league($sid, $lid);

		$data = array(
			'teams' => $teams
		);

		$this->load->view('admin/action_get_teams_for_league.php', $data);
	}

	/**
	 * Action to add a match.
	 */
	function action_new_match()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('sid', 'Season ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('fid', 'Field ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('lid', 'League ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('htid', 'Home Team ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('atid', 'Away Team ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('date', 'Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('time', 'Time', 'trim|required|xss_clean');
		$this->form_validation->set_rules('h_g', 'Home Goals', 'trim|xss_clean');
		$this->form_validation->set_rules('a_g', 'Away Goals', 'trim|xss_clean');
		$this->form_validation->set_rules('has_been_played', 'Has Been Played', 'trim|xss_clean');

		$sid = $this->input->post('sid');
		$fid = $this->input->post('fid');
		$lid = $this->input->post('lid');
		$htid = $this->input->post('htid');
		$atid = $this->input->post('atid');
		$date = $this->input->post('date');
		$time = $this->input->post('time');
		$h_g = $this->input->post('h_g');
		$a_g = $this->input->post('a_g');
		// === 'true' because the value will pass in as the string 'true'.
		$has_been_played = $this->input->post('has_been_played') === 'true';

		// If has_been_played is set, then the goals must be entered.
		if ($has_been_played && !($h_g && $a_g))
		{
			echo json_encode(array(
				'status' => 'danger',
				'message' => "The match's score must be entered if it has been played."
			));
			return;
		}

		if ($this->form_validation->run())
		{
			log_message('debug', 'Verified');

			if ($this->matches->insert($sid, $fid, $lid, $htid, $atid, $date, $time, $h_g, $a_g, $has_been_played))
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
}