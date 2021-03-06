<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

Class Standings_m extends CI_Model
{
	/**
	 * Retrieves the standings for a league given the League ID and the Season ID.
	 *
	 * @return  object
	 */
	function retrieve($lid, $sid)
	{
		$where = array(
			'r.lid' => $lid,
			'r.sid' => $sid
		);

		// Retrieve the Team ID/Name.
		$query = $this->db->select('r.tid, t.name AS team_name, r.sid, se.name AS season_name,
			r.lid, l.name AS league_name, s.h_w + s.h_t + s.h_l + s.a_w + s.a_t + s.a_l AS pld,
			3 * (s.h_w + s.a_w) + s.h_t + s.a_t AS pts, s.h_w + s.a_w AS wins, s.h_l + s.a_l AS losses,
			s.h_t + s.a_t AS ties, s.h_gf + s.a_gf AS goals, s.h_ga + s.a_ga AS allowed', false)
			->from('standings s')
			->join('league_reg r', 's.tid = r.tid and s.sid = r.sid and s.lid = r.lid', 'right outer')
			->join('team t', 'r.tid = t.id', 'inner')
			->join('league l', 'r.lid = l.id', 'inner')
			->join('season se', 'r.sid = se.id', 'inner')
			->where($where)
			->order_by('pts desc, goals - allowed desc, goals desc');

		return $query->get()->result();
	}

	/**
	 * Inserts a record into the database. Returns TRUE if a successful insert was added.
	 *
	 * @return  boolean
	 */
	function insert($tid, $lid, $sid)
	{
		$obj = array(
			'tid' => $tid,
			'lid' => $lid,
			'sid' => $sid
		);

		try {
			$this->db->insert('league_reg', $obj);
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		    return FALSE;
		}

		return TRUE;
	}
}
