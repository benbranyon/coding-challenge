<?php
class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user($id)
    {
    	$query = $this->db->get_where('users', array('id' => $id));
    	return $query->result();
    }

    public function get_user_by_fb_id($fb_uid)
    {
    	$this->db->where('fb_uid', $fb_uid);
		$query = $this->db->get('users');
		return $query->result_array();
    }

	public function get_users()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    public function insert_user($user_data)
    {
    	$this->fb_uid = $user_data['id'];
    	$this->first_name  = $user_data['first_name'];
    	$this->last_name = $user_data['last_name'];
    	$this->gender = $user_data['gender'];
    	$this->locale = $user_data['locale'];
    	$this->created = date('Y-m-d');

    	$this->db->insert('users', $this);
    	return $this->db->insert_id();
    }

    public function insert_batch($users)
    {
    	$this->db->insert_batch('users', $users);
    }

    public function update_user()
    {

    }

    public function get_count()
    {
    	return $this->db->count_all_results('users');
    }

    public function get_count_today()
    {
    	$this->db->where('modified', date('Y-m-d'));
		$this->db->from('users');
		return $this->db->count_all_results();
    }

    public function get_top_ten_players()
    {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('scores', 'scores.id = users.id');
		$this->db->order_by("score", "desc");
		$this->db->limit(10);

		$query = $this->db->get();
		return $query->result();
    }

}