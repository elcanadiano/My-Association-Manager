<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class League_m extends CI_Model
{
	/**
	 * Retrieves a list of leagues.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		$query = $this->db->select('name, age_cat, num_teams, max_roster_size, is_active')
			->from('league')
			->order_by('name');

		return $query->get()->result();
	}

	/**
	 * Inserts a record into the database.
	 */
	function insert_league($name, $age_cat, $no_teams, $max_roster_size, $is_active = TRUE)
	{
		$obj = array(
			'name' => $name,
			'age_cat' => $age_cat,
			'no_teams' => $no_teams,
			'max_roster_size' => $max_roster_size,
			'is_active' => $is_active
		);

		$this->db->insert('news', $obj);
	}
}