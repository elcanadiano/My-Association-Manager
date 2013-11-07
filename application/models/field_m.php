<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Field_m extends CI_Model
{
	/**
	 * Retrieves a list of leagues.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		$query = $this->db->select('id, name, address, city, region, pitch_type')
			->from('field')
			->order_by('name');

		return $query->get()->result();
	}

	/**
	 * Retrieves a league given the ID.
	 *
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('id, name, address, city, region, pitch_type')
			->from('field')
			->where('id', $id)
			->limit(1);

		return $query->get()->result()[0];
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function insert($name, $address, $city, $region, $pitch_type)
	{
		$obj = array(
			'name' => $name,
			'address' => $address,
			'city' => $city,
			'region' => $region,
			'pitch_type' => $pitch_type
		);

		try {
			$this->db->insert('field', $obj);
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		    return FALSE;
		}

		return TRUE;
	}

	/**
	 * Updates a record in the database. Returns TRUE if a successful update was performed.
	 *
	 * @return  boolean
	 */
	function update($id, $name, $address, $city, $region, $pitch_type)
	{
		$obj = array(
			'name' => $name,
			'address' => $address,
			'city' => $city,
			'region' => $region,
			'pitch_type' => $pitch_type
		);

		try {
			$this->db->where('id', $id)->update('field', $obj);
		} catch (Exception $e) {
		    log_message('error', 'Caught exception: ' . $e->getMessage());
		    return FALSE;
		}

		return TRUE;
	}
}