<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class Seasons extends C_Admin {
	static private $user_links = array(
		'title' => 'News Functions',
		'links' => array(
			array(
				'url' => '/admin/seasons/new_season',
				'desc' => 'New Season'
			)
		)
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('season_m','seasons');
	}

	// Gets session data, passes info to header, main, and footer.
	function index($msg = '')
	{
		$seasons = $this->seasons->retrieve();

		$data = array(
			'title' => 'Seasons',
			'msg' => $msg,
			'seasons' => $seasons,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'sidenav' => self::$user_links
		);
		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/show_all_seasons.php', $data);
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
		$this->form_validation->set_rules('age_cat', 'Age Category', 'trim|required|xss_clean');

		$name = $this->input->post('name');
		$age_cat = $this->input->post('age_cat');

		if ($this->form_validation->run())
		{
			if ($this->seasons->insert($name, $age_cat))
			{
				$this->index('Article added successfully!');
				return;
			}
		}

		$this->new_season('One or more of the fields are invalid.', $name, $age_cat);
	}

	/**
	 * Function to edit a season.
	 */
	function edit($lid, $msg='', $name='', $age_cat='')
	{
		// If there is no message, set it to the default.
		if (!$msg)
		{
			$season = $this->seasons->retrieve_by_id($lid);

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
		$this->form_validation->set_rules('age_cat', 'Age Category', 'trim|required|xss_clean');

		$lid = $this->input->post('name');
		$name = $this->input->post('name');
		$age_cat = $this->input->post('age_cat');

		if ($this->form_validation->run())
		{
			if ($this->seasons->update_season($lid, $name, $age_cat))
			{
				$this->index('season Updated successfully!');
				return;
			}
		}

		$this->edit($lid, 'One or more of the fields are invalid.', $name, $age_cat);
	}
}