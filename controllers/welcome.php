<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('score_model');
		$this->load->helper('url');
	}

	function index()
	{
		$this->load->view('welcome_message');
	}

	function build_test_data()
	{
		$this->build_user_test_data();
		$this->build_score_test_data();
	}

/**
 * Create one million test records for users
 *
 */
	function build_user_test_data()
	{
		set_time_limit(300);
		ini_set('memory_limit','2048M');

		$i = 1;
		while($i <= 1000000)
		{
			$user_data = array(
				'fb_uid' => rand(100000000000000, 199999999999999),
				'first_name' => 'James',
				'last_name' => 'Chengman' . $i,
				'gender' => 'male',
				'locale' => 'en_US',
				'created' => date('Y-m-d'),
				'modified' => date('Y-m-d', strtotime( '-'.mt_rand(0,14).' days'))
			);

			$users[] = $user_data;

			$i++;
		}
		$this->user_model->insert_batch($users);

		return;
	}

/**
 * Create one million test records for scores
 *
 */
function build_score_test_data()
{
		set_time_limit(300);
		ini_set("memory_limit","2048M");

		$i = 1;
		while($i <= 1000000)
		{
			$score_data = array(
				'score' => rand(0, 10000),
				'user_id' => rand(0, 1000000),
				'created' => date('Y-m-d', strtotime( '-'.mt_rand(0,14).' days'))
			);
			$scores[] = $score_data;

			$i++;
		}
		$this->score_model->insert_batch($scores);
}

/**
 * Create and display the leaderboard_report
 *
 */
function leaderboard_report()
{
	$total_players = $this->user_model->get_count();
	$total_players_today = $this->user_model->get_count_today();
	$top_ten_players = $this->user_model->get_top_ten_players();
	$top_ten_players_improved = $this->get_top_ten_players_improved();

	$data = array(
               'total_players' => $total_players,
               'total_players_today' => $total_players_today,
               'top_ten_players' => $top_ten_players,
               'top_ten_players_improved' => $top_ten_players_improved
    );

	$this->load->view('leaderboard_report', $data);
}

/**
 * Get the top ten players that have improved their score
 *
 */
function get_top_ten_players_improved()
{
	set_time_limit(300);
	ini_set("memory_limit","2048M");

	$this_week_scores = $this->score_model->get_this_week_scores();
	$last_week_scores = $this->score_model->get_last_week_scores();

	$improved_scores = array();
	foreach($this_week_scores as $score)
	{

		foreach($last_week_scores as $old_score)
		{
			if($score['user_id'] == $old_score['user_id'] && $score['score'] > $old_score['score'])
			{
				$improved_scores[] = array('score' => $score['score'],'user_id' => $score['user_id'], 'first_name' => $score['first_name'], 'last_name' => $score['last_name']);
			}
		}
	}
	rsort($improved_scores);
	return $improved_scores;
}

}
/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */