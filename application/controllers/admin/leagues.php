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
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('league_m','league');
	}

	// Gets session data, passes info to header, main, and footer.
	function index($msg = '')
	{
		$leagues = $this->league->retrieve();

		$data = array(
			'title' => 'Leagues',
			'msg' => $msg,
			'leagues' => $leagues,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);
		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/show_all_leagues.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Page to create a league.
	 */
	function new_league($msg='', $name='', $age_cat='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$msg = 'Please enter the following information for the new league.';
		}

		$data = array(
			'form_action' => 'action_create_league',
			'title' => 'Create a New League',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'name' => $name,
			'age_cat' => $age_cat,
			'msg' => $msg,
			'submit_message' => 'Add League',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/league_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
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
	function edit($lid, $msg='', $name='', $age_cat='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$league = $this->league->retrieve_by_id($lid);

			// If there is no league, error out.
			if (!$league)
			{
				show_error('No league was found with this ID.');
			}

			$msg = 'Please enter the following information for the new league.';
			$name = $league->name;
			$age_cat = $league->age_cat;
		}

		$data = array(
			'form_action' => 'action_edit_league',
			'title' => 'Edit a league',
			'js' => array('/js/admin/admin.js'),
			'css' => array('/styles/admin.css'),
			'id' => $lid,
			'name' => $name,
			'age_cat' => $age_cat,
			'msg' => $msg,
			'submit_message' => 'Edit League',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/league_edit.php', $data);
		$this->load->view('admin/footer.php', $data);
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
}