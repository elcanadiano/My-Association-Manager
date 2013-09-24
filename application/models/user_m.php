<?php
Class User_m extends CI_Model
{
	/**
	 * Attempt to login using the following username/password credentials.
	 *
	 * @param   string $username
	 * 			The username.
	 *
	 * @param	string $password
	 *			The password.
	 */
	function login($username, $password)
	{
		$this->db->select('id, username, password')
			->from('admin')
			->where('username', $username)
			->where('password', $this->encr($password))
			->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() === 1)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Attempt to grab the ID and Username of each admin user.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		// Retrieve the username and ID of all Admins.
		$query = $this->db->select('id, username')
					->from('admin');

		return $query->get()->result();
	}


	/**
	 * Attempts to create a new admin account using the following username/password credentials.
	 *
	 * @param   string $username
	 * 			The username.
	 *
	 * @param	string $password
	 *			The password.
	 *
	 * @return	boolean
	 *			The status of the creation (success or failure)
	 */
	function create($username, $password)
	{
		$this->db->select('username')
			->from('admin')
			->where('username', $username)
			->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			return FALSE;
		}

		$obj = array(
			'username' => $username,
			'password' => $this->encr($password)
		);

		$this->db->insert('admin', $obj);

		return TRUE;
	}

	/**
	 * Changes the password of a given username to $password.
	 *
	 * @param   string $username
	 * 			The username.
	 *
	 * @param	string $password
	 *			The password.
	 *
	 * @return	boolean
	 *			The status of whether or not the password was successfully changed.
	 */
	function change_password($username, $password)
	{
		$obj = array(
			'password' => $this->encr($password)
		);

		$this->db->where('username', $username)
			->update('admin', $obj);

		return $this->db->affected_rows() > 0;
	}

	/**
	 * Encrypts a password.
	 *
	 * @param	string $password
	 *			The password
	 *
	 * @return	string
	 *			The encrypted version of the password.
	 */
	private function encr($password)
	{
		return hash('sha256', hash('sha256', $password));
	}
}