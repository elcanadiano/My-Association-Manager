<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class Leagues extends C_Admin {
	static private $user_links = array(
		'title' => 'News Functions',
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
	 * Page to create a user.
	 */
	function new_league($msg='', $name='')
	{
		$data = array(
			'title' => 'Create a New League',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'name' => $name,
			'msg' => $msg,
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/newleague.php', $data);
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
				$this->index('Article added successfully!');
			}
		}
	}
}