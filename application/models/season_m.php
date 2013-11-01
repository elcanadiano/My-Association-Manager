<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Season_m extends CI_Model
{
	/**
	 * Retrieves a list of seasons.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		$query = $this->db->select('id, name, start_date, end_date')
			->from('season')
			->order_by('start_date');

		return $query->get()->result();
	}

	/**
	 * Retrieves a season given the ID.
	 *
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('id, name, start_date, end_date')
			->from('season')
			->where('id', $id)
			->limit(1);

		return $query->get()->result()[0];
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function insert($name, $start_date, $end_date)
	{
		$obj = array(
			'name' => $name,
			'start_date' => $start_date,
			'end_date' => $end_date
		);

		try {
			$this->db->insert('season', $obj);
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		    return FALSE;
		}

		return TRUE;
	}

	/**
	 * Updates a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function update($id, $name, $start_date, $end_date)
	{
		$obj = array(
			'name' => $name,
			'start_date' => $start_date,
			'end_date' => $end_date
		);

		try {
			$this->db->where('id', $id)->update('season', $obj);
		} catch (Exception $e) {
		    log_message('error', 'Caught exception: ' . $e->getMessage());
		    return FALSE;
		}

		return TRUE;
	}
}
