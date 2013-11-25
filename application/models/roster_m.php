<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Roster_m extends CI_Model
{
	/**
	 * Retrieves a list of rosters.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		// Retrieve the username and ID of all Admins.
		$query = $this->db->select('pid, tid, sid, squad_number')
			->from('roster')
			->order_by('pid, tid, sid');

		return $query->get()->result();
	}

	/**
	 * Retrieves the player name, team name, and the season name for a given team and season.
	 *
	 * @return  object
	 */
	function view_team_roster($tid, $sid)
	{
		$where = array(
			't.id' => $tid,
			's.id' => $sid
		);

		$query = $this->db->select('coalesce(`preferred_name`, `real_name`) AS player_name, t.name AS `team_name`, s.name AS `season_name`, `squad_number`, `pos1`, `pos2`, `pos3`', FALSE)
			->from('roster r')
			->join('player p', 'r.pid = p.id', 'inner')
			->join('team t', 'r.tid = t.id', 'inner')
			->join('season s', 'r.sid = s.id', 'inner')
			->where($where)
			->order_by('r.squad_number');

		return $query->get()->result();
	}

	/**
	 * An invalid player is one who is already on the roster or is taking an existing
	 * squad number.
	 *
	 * @return  object
	 */
	function is_invalid_player($pid, $tid, $sid, $squad_number)
	{
		// Clause for player already on roster.
		$where = array(
			'pid' => $pid,
			'tid' => $tid,
			'sid' => $sid
		);

		// Clause for existing squad number.
		$or_where = array(
			'tid' => $tid,
			'sid' => $sid,
			'squad_number' => $squad_number
		);

		$query = $this->db->select('pid')
			->from('roster')
			->where($where)
			->or_where($or_where)
			->limit(1);

		return $query->get()->result();
	}


	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function insert($pid, $tid, $sid, $squad_number)
	{
		$obj = array(
			'pid' => $pid,
			'tid' => $tid,
			'sid' => $sid,
			'squad_number' => $squad_number
		);

		try {
			$this->db->insert('roster', $obj);
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
			$this->db->where('id', $id)->update('roster', $obj);
		} catch (Exception $e) {
		    log_message('error', 'Caught exception: ' . $e->getMessage());
		    return FALSE;
		}

		return TRUE;
	}
}
