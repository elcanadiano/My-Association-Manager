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
	 * Index page. Default shows all of the news articles written.
	 */
	function index()
	{
		$query_result = $this->news->retrieve();

		$data = array(
			'title' => 'News',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'query_result' => $query_result,
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/show_all_news.php', $data);
	}

	/**
	 * Page to add an article.
	 */
	function new_article($news_title='', $news_message='')
	{
		$data = array(
			'title' => 'Create a New Article',
			'js' => array('/js/admin/admin.js'),
			'css' => array(
				'/styles/admin.css',
				'/styles/news.css'
			),
			'news_title' => $news_title,
			'news_message' => $news_message,
			'submit_message' => 'Submit Article',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/newarticle.php', $data);
	}

	/**
	 * Action function to insert an article.
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
				echo json_encode(array(
					'status' => 'success',
					'message' => 'Article Added Successfully!'
				));
				return;
			}
			else
			{
				echo json_encode(array(
					'status' => 'warning',
					'message' => 'This article has already been added.'
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