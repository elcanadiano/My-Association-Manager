<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class C_Admin extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('user_m','user');
		$this->load->model('news_m','news');
	}

	/**
	 * Makes sure that the user is logged in as an admin before he/she can access any of these
	 * functions.
	 */
	function _remap($method, $params = array())
	{
		// If the user is logged in, load the method as usual.
		if ($this->session->userdata('logged_in'))
		{
			call_user_func_array(array($this, $method), $params);
			return false;
		}
		// Otherwise, show them the login form.
		else
		{
			$this->load->helper(array('form'));
			$this->load->view('login_view');
			return false;
		}
	}
}
