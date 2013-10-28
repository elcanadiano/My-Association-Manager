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
		$query = $this->db->select('id, name, start_time, end_time')
			->from('season')
			->order_by('start_time');

		return $query->get()->result();
	}

	/**
	 * Retrieves a season given the ID.
	 *
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('id, name, start_time, end_time')
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
	function insert($name, $start_time, $end_time)
	{
		$obj = array(
			'name' => $name,
			'start_time' => $start_time,
			'end_time' => $end_time
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
	function update($id, $name, $start_time, $end_time)
	{
		$obj = array(
			'name' => $name,
			'start_time' => $start_time,
			'end_time' => $end_time
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
