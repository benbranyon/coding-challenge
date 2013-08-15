<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kixeye Coding Challenge
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Ben Branyon
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Kixeye extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('score_model');
		//$this->load->spark('curl');
	}

/**
 * User Get function : get the user's data based on id
 * 
 * @param int id
 */
	function user_get()
    {

        if(!$this->get('id'))
        {
        	$this->response(NULL, 400);
        }

    	$user = $this->user_model->get_user($this->get('id'));
    	
        if($user)
        {
            $this->response($user, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
    
/**
 * User POST function: instert user and/or score into the database
 * 
 * @param string signed_request
 * @param int score
 */
    function user_post()
    {
    	if(!$this->get('signed_request'))
    	{
    		$this->response(NULL, 400);
    	}

    	if(!$this->get('score'))
    	{
    		$this->response(NULL, 400);
    	}

		list($encoded_sig, $payload) = explode('.', $this->get('signed_request'), 2); 

  		// decode the data
  		$sig = $this->base64_url_decode($encoded_sig);
  		$data = json_decode($this->base64_url_decode($payload), true);

  		if($current_user = $this->user_model->get_user_by_fb_id($data['user_id']))
  		{
  			$score_data = array(
  				'score' => $this->get('score'),
  				'user_id' => $current_user[0]['fb_uid']
  			);

  			$this->score_model->insert_score($score_data);

    		$message = array('current_user' => $current_user, 'score' => $this->get('score'));
  		}	
  		else
  		{
  			$user_data = json_decode($this->curl->simple_get('http://graph.facebook.com/'.$data['user_id']), true);
  			$id = $this->user_model->insert_user($user_data);
   			$score_data = array(
  				'score' => $this->get('score'),
  				'user_id' => $id
  			);

  			$this->score_model->insert_score($score_data);

    		$message = array('user_data' => $user_data, 'score' => $this->get('score'));
  		}

        //$this->some_model->updateUser( $this->get('id') );
        //$message = array('id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }

/**
 * Users Get function : get all the user's data
 * 
 */
    function users_get()
    {
    	set_time_limit(300);
		ini_set("memory_limit","2048M");

        $users = $this->user_model->get_users();
        
        if($users)
        {
            $this->response($users, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
    }

/**
 * Helper function for decoding the signed_request
 * 
 */
	function base64_url_decode($input) {
  		return base64_decode(strtr($input, '-_', '+/'));
	}
}