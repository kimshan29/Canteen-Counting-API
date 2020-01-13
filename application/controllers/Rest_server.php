<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_server extends CI_Controller {
    
    public function __countsruct($config = 'rest'){
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH");
		parent::__countsruct();
	}

    public function index()
    {
        $this->load->helper('url');

        $this->load->view('rest_server');
    }
}
