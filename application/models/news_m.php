<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
Class News_m extends CI_Model
{
	/**
	 * Attempt to grab the news ID, the author, and the title.
	 *
	 * @return  object
	 */
	function retrieve()
	{
		// Retrieve the username and ID of all Admins.
		$query = $this->db->select('news.id, username, title, date')
			->from('news')
			->join('admin', 'news.aid = admin.id', 'inner')
			->order_by('date');

		return $query->get()->result();
	}

	/**
	 * Similar to retrieve, but retrieves the parsed message as well.
	 *
	 * @return  object
	 */
	function retrieve_parsed()
	{
		$query = $this->db->select('username, title, date, parsed')
			->from('news')
			->join('admin', 'news.aid = admin.id', 'inner')
			->order_by('date', 'desc');

		return $query->get()->result();
	}

	/**
	 * Returns a given news object.
	 * 
	 * @return  object
	 */
	function retrieve_by_id($id)
	{
		$query = $this->db->select('*')
			->from('news')
			->where('id', $id);


		$result = $query->get()->result();

		// If there is a record, return the first element. Otherwise, return NULL.
		if ($result)
		{
			return $result[0];
		}

		return NULL;
	}

	/**
	 * Inserts a new article with the immediate date. If an article
	 * has the exact same title, Author ID, and Message, do not insert
	 * the article into the database as it is treated as a duplicate.
	 */
	function new_article($title, $message, $parsed)
	{
		$login_info = $this->session->userdata('logged_in');
		$date = date("Y-m-d H:i:s");

		$check_obj = array(
			'aid' => $login_info['id'],
			'title' => $title,
			'message' => $message
		);

		$this->db->select('id')
			->from('news')
			->where($check_obj)
			->limit(1);


		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			return FALSE;
		}

		$obj = array(
			'aid' => $login_info['id'],
			'title' => $title,
			'date' => $date,
			'message' => $message,
			'parsed' => $parsed
		);

		$this->db->insert('news', $obj);

		return TRUE;
	}
}