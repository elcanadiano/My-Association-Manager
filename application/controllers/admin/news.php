<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class News extends C_Admin {
	static private $user_links = array(
		'title' => 'News Functions',
		'links' => array(
			array(
				'url' => '/admin/news/new_article',
				'desc' => 'New Article'
			)
		)
	);

	/**
	 * Index page.
	 */
	function index($msg='')
	{
		$query_result = $this->news->retrieve();

		$data = array(
			'title' => 'News',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'msg' => $msg,
			'query_result' => $query_result,
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/show_all_news.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Page to create a user.
	 */
	function new_article($news_title='', $news_message='')
	{
		$data = array(
			'title' => 'Create a New Admin User',
			'js' => array(),
			'css' => array(
				'/styles/admin.css',
				'/styles/news.css'
			),
			'news_title' => $news_title,
			'news_message' => $news_message,
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/newarticle.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action to store an article.
	 */
	function action_add_article()
	{
		// Loading form validation helper and the Markdown parser.
		$this->load->library('form_validation');
		$this->load->library('markdown');

		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');

		$news_title = $this->input->post('title');
		$news_message = $this->input->post('message');
		$parsed = $this->markdown->parse($news_message);

		if ($this->form_validation->run())
		{
			if ($this->news->new_article($news_title, $news_message, $parsed))
			{
				$this->index('Article added successfully!');
			}
			else
			{
				$this->index('The article has already been added.');
			}
		}
		else
		{
			$this->new_article($news_title, $news_message);
			return;
		}
	}
}