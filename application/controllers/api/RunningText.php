<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class RunningText extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_running_text', 'running_text');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_running_text', array('id' => $id, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $datas = $datas->row();  
                //debug_code($datas); die;
                
                $data = array(
                    'status' => 'success',
                    'message' => 'success fetch data',
                    'items' => $datas,
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => 'ID not found',
                );
            }
        } else {
            $datas = select_all_where('m_running_text', 'delete', '0');			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        }
		header('Content-Type: application/json');
        $this->response($data);
    }
	
	function getRunningTextAktive_get()
    {
        $datas = select_where_array('m_running_text', array('delete' => 0, 'status' => 'Aktive'))->result();	
		
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'num_rows' => count($datas),
			'items' => $datas
		);
		
        $this->response($data);
    }
	
	function addRunningText_post()
    {
        $message = $this->post('message');
        $status = $this->post('status');
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
        $insert = insert_all('m_running_text', array(
            'message' => $message,
        	'status' => $status,                      
            'createdBy' => $createdBy,
            'createdDate' => $createdDate,
        ));
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add Jenis Menu',
            );
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add Jenis Menu',
            );
        }
        $this->response($data);
    }
	
	function updateRunningText_post()
    {
		$idRunningText = $this->post('id');
        $message = $this->post('message');
        $status = $this->post('status');  
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidRunningText = select_where('m_running_text', 'id', $idRunningText)->num_rows();
        
        if ($getidRunningText > 0) {
			$update = update('m_running_text', array(
				'message' => $message,
				'status' => $status,							
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idRunningText);
			if ($update) {
				$data = array(
					'status' => 'success',
					'message' => 'Success update data',
				);
			} else {
				$data = array(
					'status' => 'error',
					'message' => 'Failed update data',
				);
			}
		}
		else {
            $data = array(
                'status' => 'error',
                'message' => 'Gagal! id Akses tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteRunningText_post()
    {
        $idRunningText = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidRunningText = select_where('m_running_text', 'id', $idRunningText)->num_rows();
        
        if ($getidRunningText > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_running_text', 'id', $idRunningText, $update);
			//$delete = $this->super_model->delete_table('m_running_text', 'id', $idRunningText);
            if ($delete) {
                $data = array(
                    'status' => 'success',
                    'message' => 'Success delete data',
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => 'Failed delete data',
                );
            }
        }
        else {
            $data = array(
                'status' => 'error',
                'message' => 'Gagal! id Akses tidak terdaftar'
            );
        }

        $this->response($data);
    }

}
