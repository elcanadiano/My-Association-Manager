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
	 * Retrieves a league given the ID.
	 *
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('id, name, age_cat')
			->from('league')
			->where('id', $id)
			->limit(1);

		return $query->get()->result()[0];
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
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

		return TRUE;
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function update_league($id, $name, $age_cat)
	{
		$obj = array(
			'name' => $name,
			'age_cat' => $age_cat
		);

		try {
			$this->db->where('id', $id)->update('league', $obj);
		} catch (Exception $e) {
		    log_message('error', 'Caught exception: ' . $e->getMessage());
		    return FALSE;
		}

		return TRUE;
	}
}
