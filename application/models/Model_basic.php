<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_basic extends CI_Model {
	
        public function __construct() {
		parent::__construct();
	}

	function get_menu($user_groups){
                $this->db->select($this->tbl_menu.'.*');
                $this->db->from($this->tbl_menu);
                $this->db->join($this->tbl_user_accsess,$this->tbl_menu.'.id = '.$this->tbl_user_accsess.'.id_menu');
                $this->db->where($this->tbl_user_accsess.'.id_group',$user_groups);
                $this->db->where($this->tbl_user_accsess.'.accsess',1);
                $this->db->order_by($this->tbl_menu.'.position','ASC');
                $data=$this->db->get();
                return $data->result();
	}

	function get_menu_access($user_groups){
		$this->db->select($this->tbl_menu.'.*');
                $this->db->from($this->tbl_menu);
                $this->db->join($this->tbl_user_accsess,$this->tbl_menu.'.id = '.$this->tbl_user_accsess.'.id_menu');
                $this->db->where($this->tbl_user_accsess.'.id_group',$user_groups);
                $this->db->where($this->tbl_user_accsess.'.accsess',0);
                $this->db->order_by($this->tbl_menu.'.position','ASC');
                $data=$this->db->get();
                return $data->result();
	}

        

}