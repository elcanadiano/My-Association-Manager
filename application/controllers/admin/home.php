<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
include('c_admin.php');

class Home extends C_Admin {

	// Gets session data, passes info to header, main, and footer.
	function index()
	{
		$session_data = $this->session->userdata('logged_in');
		$data = array(
			'username' => $session_data['username'],
			'title' => 'Welcome, ' . $session_data['username'] . '!',
			'js' => array(),
			'css' => array('/styles/admin.css')
		);
		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/main.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	// Logout function.
	function logout()
	{
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('home', 'refresh');
	}
}
