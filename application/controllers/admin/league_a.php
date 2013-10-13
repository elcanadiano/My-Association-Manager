<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
include('c_admin.php');

class League_a extends C_Admin {
	static private $user_links = array(
		'title' => 'News Functions',
		'links' => array(
			array(
				'url' => '/admin/news/new_league',
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
}