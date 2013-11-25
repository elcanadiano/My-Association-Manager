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
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('player_m','players');
	}

	// Gets session data, passes info to header, main, and footer.
	function index($msg = '')
	{
		$players = $this->players->retrieve();

		$data = array(
			'title' => 'Players',
			'msg' => $msg,
			'players' => $players,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);
		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/show_all_players.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Page to create a league.
	 */
	function new_player($msg='', $real_name='', $preferred_name='', $pos1='', $pos2='', $pos3='', $email='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$msg = 'Please enter the information regarding the new player.';
		}

		$data = array(
			'form_action' => 'action_create_player',
			'title' => 'Create a New Player',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'real_name' => $real_name,
			'preferred_name' => $preferred_name,
			'pos1' => $pos1,
			'pos2' => $pos2,
			'pos3' => $pos3,
			'email' => $email,
			'msg' => $msg,
			'submit_message' => 'Add Player',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/player_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action to add a player.
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
	function edit($pid, $msg='', $real_name='', $preferred_name='', $pos1='', $pos2='', $pos3='', $email='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$player = $this->players->retrieve_by_id($pid);

			// If there is no player, error out.
			if (!$player)
			{
				show_error('No player was found with this ID.');
			}

			$msg = 'Please enter the following information for the new player.';
			$real_name = $player->real_name;
			$preferred_name = $player->preferred_name;
			$pos1 = $player->pos1;
			$pos2 = $player->pos2;
			$pos3 = $player->pos3;
			$email = $player->email;
		}

		$data = array(
			'form_action' => 'action_edit_player',
			'title' => 'Edit a player',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'id' => $pid,
			'real_name' => $real_name,
			'preferred_name' => $preferred_name,
			'pos1' => $pos1,
			'pos2' => $pos2,
			'pos3' => $pos3,
			'email' => $email,
			'password' => '',
			'msg' => $msg,
			'submit_message' => 'Edit Player',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/player_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
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