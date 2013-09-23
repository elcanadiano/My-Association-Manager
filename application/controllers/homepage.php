<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Homepage extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('news_m','news');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// Retrieve articles
		$articles = $this->news->retrieve_parsed();

		$data = array(
			'title' => 'City of Teufort - TF2\'s Open Website',
			'h1' => 'City of',
			'h1_newline' => 'Teufort',
			'header_navigation' => array(
				array(
					'href' => '#',
					'content' => 'Home'
				),
				array(
					'href' => '#',
					'content' => 'Backpack'
				),
				array(
					'href' => '#',
					'content' => 'Trades'
				),
				array(
					'href' => '#',
					'content' => 'Cats'
				),
				array(
					'href' => '#',
					'content' => 'Dogs'
				),
				array(
					'href' => '#',
					'content' => 'Pyro'
				)
			),
			'sidebar_contents' => array(
				array(
					'color' => 'red',
					'title' => 'Sample Sidebar Title',
					'body' => '<p>This is a sample sidebar. I think we can put some content here if we feel like it.</p>'
				),
			),
			'articles' => $articles,
			'js' => array('js/custom.js'),
			'css' => array('/styles/style.css', '/styles/code.css')
		);

		$this->load->view('header.php', $data);
		$this->load->view('homepage.php', $data);
		$this->load->view('sidebar.php', $data);
		$this->load->view('footer.php', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
