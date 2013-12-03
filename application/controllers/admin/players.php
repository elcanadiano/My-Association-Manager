<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class Players extends C_Admin {
	static private $user_links = array(
		'title' => 'Player Functions',
		'links' => array(
			array(
				'url' => '/admin/players/new_player',
				'desc' => 'New Player'
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
		$this->load->model('player_m','players');
	}

	/**
	 * Index function that lists all the players in the database.
	 */
	function index()
	{
		$players = $this->players->retrieve();

		$data = array(
			'title' => 'Players',
			'players' => $players,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);

		$this->load->view('admin/show_all_players.php', $data);
	}

	/**
	 * Page to add a player.
	 */
	function new_player()
	{
		$data = array(
			'form_action' => 'action_create_player',
			'title' => 'Create a New Player',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'real_name' => '',
			'preferred_name' => '',
			'pos1' => '',
			'pos2' => '',
			'pos3' => '',
			'email' => '',
			'msg' => 'Please enter the information regarding the new player.',
			'submit_message' => 'Add Player',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/player_edit.php', $data);
	}

	/**
	 * Action function to add a player.
	 */
	function action_create_player()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('real_name', 'Real Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('preferred_name', 'Nickname', 'trim|xss_clean');
		$this->form_validation->set_rules('pos1', 'Position 1', 'trim|xss_clean');
		$this->form_validation->set_rules('pos2', 'Position 2', 'trim|xss_clean');
		$this->form_validation->set_rules('pos3', 'Position 3', 'trim|xss_clean');
		$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('confirm', 'Confirm Password', 'trim|required|xss_clean');

		$real_name = $this->input->post('real_name');
		$preferred_name = $this->input->post('preferred_name');

		// If the preferred name field is blank, set it to NULL because we will need to use NULL fields
		// for coalesce.
		if (!$preferred_name)
		{
			$preferred_name = NULL;
		}

		$pos1 = $this->input->post('pos1');
		$pos2 = $this->input->post('pos2');
		$pos3 = $this->input->post('pos3');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$confirm = $this->input->post('confirm');

		// No password?
		if (!$password)
		{
			echo json_encode(array(
				'status' => 'danger',
				'message' => 'The password field is required.'
			));
			return;
		}

		// If the passwords don't match.
		if ($password !== $confirm)
		{
			echo json_encode(array(
				'status' => 'danger',
				'message' => 'The password fields must match.'
			));
			return;
		}

		if ($this->form_validation->run())
		{
			if ($this->players->insert($real_name, $preferred_name, $pos1, $pos2, $pos3, $email, $password))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Player added successfully!'
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
	 * Function to edit a player.
	 */
	function edit($pid)
	{
		$player = $this->players->retrieve_by_id($pid);

		// If there is no player, error out.
		if (!$player)
		{
			show_error('No player was found with this ID.');
		}

		$data = array(
			'form_action' => 'action_edit_player',
			'title' => 'Edit a player',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'id' => $pid,
			'real_name' => $player->real_name,
			'preferred_name' => $player->preferred_name,
			'pos1' => $player->pos1,
			'pos2' => $player->pos2,
			'pos3' => $player->pos3,
			'email' => $player->email,
			'password' => '',
			'msg' => 'Please enter the new information for ' . $player->real_name . '.',
			'submit_message' => 'Edit Player',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/player_edit.php', $data);
	}

	/**
	 * Action to add a player.
	 */
	function action_edit_player()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'trim|required|xss_clean');
		$this->form_validation->set_rules('real_name', 'Real Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('preferred_name', 'Nickname', 'trim|xss_clean');
		$this->form_validation->set_rules('pos1', 'Position 1', 'trim|xss_clean');
		$this->form_validation->set_rules('pos2', 'Position 2', 'trim|xss_clean');
		$this->form_validation->set_rules('pos3', 'Position 3', 'trim|xss_clean');
		$this->form_validation->set_rules('email', 'email', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
		$this->form_validation->set_rules('confirm', 'Confirm Password', 'trim|xss_clean');

		$pid = $this->input->post('id');
		$real_name = $this->input->post('real_name');
		$preferred_name = $this->input->post('preferred_name');
		$pos1 = $this->input->post('pos1');
		$pos2 = $this->input->post('pos2');
		$pos3 = $this->input->post('pos3');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$confirm = $this->input->post('confirm');

		// If the passwords don't match
		if ($password !== $confirm)
		{
			echo json_encode(array(
				'status' => 'danger',
				'message' => 'The passwords must match.'
			));
		}

		if ($this->form_validation->run())
		{
			if ($this->players->update($pid, $real_name, $preferred_name, $pos1, $pos2, $pos3, $email, $password))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Player updated successfully!'
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