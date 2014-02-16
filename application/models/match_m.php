<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Match_m extends CI_Model
{
	/**
	 * Retrieves a list of matches.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		$query = $this->db->select('id, name, start_date, end_date')
			->from('game')
			->order_by('start_date');

		return $query->get()->result();
	}

	/**
	 * Retrieves a match given the ID.
	 *
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('id, name, start_date, end_date')
			->from('game')
			->where('id', $id)
			->limit(1);

		$result = $query->get()->result();

		// If there is a record, return the first element. Otherwise, return NULL.
		if ($result)
		{
			return $result[0];
		}

		return NULL;
	}

	/**
	 * Retrieves all the matches for a league during a given season.
	 *
	 * @return  object
	 */
	function retrieve_by_league($sid, $lid)
	{
		$where = array(
			'sid' => $sid,
			'lid' => $lid
		);

		$query = $this->db->select('g.id, f.name AS field_name, s.name AS season_name,
			ht.name AS home_team, at.name AS away_team, h_g, a_g, date, time, has_been_played')
			->from('game g')
			->join('field f', 'g.fid = f.id', 'inner')
			->join('season s', 'g.sid = s.id', 'inner')
			->join('team ht', 'g.htid = ht.id', 'inner')
			->join('team at', 'g.atid = at.id', 'inner')
			->where($where);

		return $query->get()->result();
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function insert($sid, $fid, $lid, $htid, $atid, $date, $time, $h_g, $a_g, $has_been_played)
	{
		$obj = array(
			'sid' => $sid,
			'fid' => $fid,
			'lid' => $lid,
			'htid' => $htid,
			'atid' => $atid,
			'date' => $date,
			'time' => $time
		);
		
		// If the match has been played, add the goals (checked in the controler)
		if ($has_been_played)
		{
			$obj += array(
				'h_g' => $h_g,
				'a_g' => $a_g,
				'has_been_played' => $has_been_played
			);
		}

		try {
			$this->db->insert('game', $obj);
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
			$this->db->where('id', $id)->update('game', $obj);
		} catch (Exception $e) {
		    log_message('error', 'Caught exception: ' . $e->getMessage());
		    return FALSE;
		}

		return TRUE;
	}
}
