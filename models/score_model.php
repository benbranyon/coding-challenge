<?php
class Score_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_score($id)
    {
    	$query = $this->db->get_where('scores', array('id' => $id));
    	return $query->result();
    }

    public function insert_score($score_data)
    {
    	$this->score = $score_data['score'];
    	$this->user_id  = $score_data['user_id'];
    	$this->created = date('Y-m-d');

    	$this->db->insert('scores', $this);
    }

    public function insert_batch($scores)
    {
    	$this->db->insert_batch('scores', $scores);
    }

    public function get_this_week_scores()
    {
    	$this->db->select('user_id, scores.created, score, users.first_name, users.last_name');
    	$this->db->join('users', 'users.id = scores.id');
    	$this->db->group_by('user_id');
    	$this->db->where('scores.created >', date('Y-m-d', strtotime("-1 week")));
    	$this->db->from('scores');
    	$this->db->limit(10000);
    	$query = $this->db->get();
		return $query->result_array();
    }

    public function get_last_week_scores()
    {
    	$this->db->select('user_id, scores.created, score, users.first_name, users.last_name');
    	$this->db->join('users', 'users.id = scores.id');
    	$this->db->group_by('user_id');
    	$this->db->where('scores.created <', date('Y-m-d', strtotime("-1 week")));
    	$this->db->from('scores');
    	$this->db->limit(10000);
    	$query = $this->db->get();
		return $query->result_array();
    }
}