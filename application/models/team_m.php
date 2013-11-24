<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Team_m extends CI_Model
{
	/**
	 * Retrieves a list of teams.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		// Retrieve the username and ID of all Admins.
		$query = $this->db->select('id, name, homeid, city, region')
			->from('team')
			->order_by('name');

		return $query->get()->result();
	}

	/**
	 * Retrieves a list of teams along with the associated field name.
	 *
	 * @return  object
	 */
	function retrieve_with_field()
	{
		// Retrieve the username and ID of all Admins.
		$query = $this->db->select('t.id, t.name, homeid, t.city, t.region, f.name AS field_name')
			->from('team t')
			->join('field f', 't.homeid = f.id', 'inner')
			->order_by('name');

		return $query->get()->result();
	}

	/**
	 * Retrieves a team given the ID.
	 *
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('id, name, homeid, city, region')
			->from('team')
			->where('id', $id)
			->limit(1);

		return $query->get()->result()[0];
	}

	/**
	 * Retrieves all the teams for the roster functions.
	 *
	 * @return  object
	 */
	function retrieve_roster()
	{
		$query = $this->db->select('id, name')
			->from('team')
			->order_by('name');

		return $query->get()->result();
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function insert($name, $homeid, $city, $region)
	{
		$obj = array(
			'name' => $name,
			'homeid' => $homeid,
			'city' => $city,
			'region' => $region
		);

		try {
			$this->db->insert('team', $obj);
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
	function update($id, $name, $homeid, $city, $region)
	{
		$obj = array(
			'name' => $name,
			'homeid' => $homeid,
			'city' => $city,
			'region' => $region
		);

		try {
			$this->db->where('id', $id)->update('team', $obj);
		} catch (Exception $e) {
		    log_message('error', 'Caught exception: ' . $e->getMessage());
		    return FALSE;
		}

		return TRUE;
	}
}
