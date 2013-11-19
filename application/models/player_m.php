<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// If we are not using PHP 5.5 or greater, we need a
// supplementary file for all things bcrypt.
if (PHP_VERSION_ID < 50500)
{
	require('application/libraries/password.php');
}

Class Player_m extends CI_Model
{
	/**
	 * Retrieves a list of players.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		// Retrieve the username and ID of all Admins.
		$query = $this->db->select('id, real_name, preferred_name, pos1, pos2, pos3')
			->from('player')
			->order_by('preferred_name');

		return $query->get()->result();
	}

	/**
	 * Retrieves a player given the ID.
	 *
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('id, real_name, preferred_name, pos1, pos2, pos3, email')
			->from('player')
			->where('id', $id)
			->limit(1);

		return $query->get()->result()[0];
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function insert($real_name, $preferred_name, $pos1, $pos2, $pos3, $email, $password)
	{
		$obj = array(
			'real_name' => $real_name,
			'preferred_name' => $preferred_name,
			'pos1' => $pos1,
			'pos2' => $pos2,
			'pos3' => $pos3,
			'email' => $email,
			'password' => $this->encr($password)
		);

		try {
			$this->db->insert('player', $obj);
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		    return FALSE;
		}

		return TRUE;
	}

	/**
	 * Updates a record in the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function update($id, $real_name, $preferred_name, $pos1, $pos2, $pos3, $email, $password)
	{
		$obj = array(
			'real_name' => $real_name,
			'preferred_name' => $preferred_name,
			'pos1' => $pos1,
			'pos2' => $pos2,
			'pos3' => $pos3,
			'email' => $email
		);

		// Adding in a password is optional.
		if ($password)
		{
			$obj['password'] = $this->encr($password);
		}

		try {
			$this->db->where('id', $id)->update('player', $obj);
		} catch (Exception $e) {
		    log_message('error', 'Caught exception: ' . $e->getMessage());
		    return FALSE;
		}

		return TRUE;
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
		return password_hash($password, PASSWORD_BCRYPT);
		//return hash('sha256', hash('sha256', $password));
	}
}
