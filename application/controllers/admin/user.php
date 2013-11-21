<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// We need C_admin
require_once('c_admin.php');

class User extends C_Admin {
	static private $user_links = array(
		'title' => 'User Functions',
		'links' => array(
			array(
				'url' => '/admin/user/create',
				'desc' => 'Create User'
			)
		)
	);

	/**
	 * The default function in User simply just lists out all the admins.
	 */
	function index($msg='')
	{
		$query_result = $this->user->retrieve();

		$data = array(
			'title' => 'User Pages',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'msg' => $msg,
			'query_result' => $query_result,
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/show_all.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Page to change a user's password.
	 *
	 * @param	string $username
	 *			The Username.
	 *
	 * @param	string $msg
	 *			An optional message indicating success or whatnot.
	 */
	function change_password($username, $msg = '')
	{
		$data = array(
			'title' => 'Change password for ' . $username,
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'username' => $username,
			'message' => $msg,
			'submit_message' => 'Change Password',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/changepassword.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action function to change an admin's password.
	 */
	function action_change_password()
	{
		//This method will have the credentials validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('confirm', 'Password (confirm)', 'trim|required|xss_clean');

		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$confirm = $this->input->post('confirm');

		if ($password !== $confirm)
		{
			echo json_encode(array(
				'status' => 'danger',
				'message' => 'Passwords do not match.'
			));
			return;
		}

		if ($this->form_validation->run())
		{
			if (!$this->user->change_password($username, $password))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'The username does not exist.'
				));
				return;
			}

			echo json_encode(array(
				'status' => 'success',
				'message' => 'Password changed successfully!'
			));
			return;
		}
		
		echo json_encode(array(
			'status' => 'danger',
			'message' => 'One or more of the fields are invalid.'
		));
	}

	/**
	 * Page to create a user.
	 */
	function create($msg='', $username='')
	{
		$data = array(
			'title' => 'Create a New Admin User',
			'js' => array(),
			'css' => array('/styles/admin.css'),
			'username' => $username,
			'msg' => $msg,
			'submit_message' => 'Create User',
			'sidenav' => self::$user_links
		);

		$this->load->helper(array('form'));

		$this->load->view('admin/header.php', $data);
		$this->load->view('admin/createuser.php', $data);
		$this->load->view('admin/footer.php', $data);
	}

	/**
	 * Action function to create a user.
	 */
	function action_create_user()
	{
		//This method will have the credentials validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if ($this->form_validation->run())
		{
			if ($this->user->create($username, $password))
			{
				echo json_encode(array(
					'status' => 'success',
					'message' => 'User added successfully!'
				));
				return;
			}

			echo json_encode(array(
				'status' => 'danger',
				'message' => 'Username already exists.'
			));
			return;
		}

		echo json_encode(array(
			'status' => 'danger',
			'message' => 'One or more of the fields are invalid.'
		));
	}
}
