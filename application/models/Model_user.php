<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_user extends CI_Model {

  public function __construct() {
		parent::__construct();
	}

  function leaderboard(){
        /*  $this->db->select('
                  a.id,
                  a.username,
                  a.point,
          ');
          $this->db->from('dc_member as a');
          $this->db->where('a.status_member','normal');
          $this->db->order_by('a.point','DESC');
          $this->db->limit('0,50');
          $data=$this->db->get();
          return $data;*/
  }

  public function login($username, $password)
  {
    //$like_username_email = "(username LIKE '%". $email ."%')";

    $this->db
      ->where('password', $password)
      ->where('username', $username);

    return $this->db->get('m_user');
  }
}
