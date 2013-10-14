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
		$query = $this->db->select('id, name, age_cat')
			->from('league')
			->order_by('age_cat');

		return $query->get()->result();
	}

	/**
	 * Inserts a record into the database.
	 */
	function insert_league($name, $age_cat)
	{
		$obj = array(
			'name' => $name,
			'age_cat' => $age_cat
		);

		try {
			$this->db->insert('league', $obj);
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		    return FALSE;
		}
	}
}