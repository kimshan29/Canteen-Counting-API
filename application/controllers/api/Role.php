<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class Role extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_role', 'role');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_role', array('id' => $id, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $datas = $datas->row();                
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
            $datas = select_all_where('m_role', 'delete', '0');			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        }
        $this->response($data);
    }
	
	function addRole_post()
    {
        $namaRole = $this->post('namaRole');
        $keterangan = $this->post('keterangan');        
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
        $insert = insert_all('m_role', array(
            'namaRole' => $namaRole,
        	'keterangan' => $keterangan,                      
            'createdBy' => $createdBy,
            'createdDate' => $createdDate,
        ));
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add Role',
            );
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add Role',
            );
        }
        $this->response($data);
    }
	
	function updateRole_post()
    {
		$idRole = $this->post('id');
        $namaRole = $this->post('namaRole');  
        $keterangan = $this->post('keterangan');      
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidRole = select_where('m_role', 'id', $idRole)->num_rows();
        
        if ($getidRole > 0) {
			$update = update('m_role', array(
				'namaRole' => $namaRole,			
				'keterangan' => $keterangan,					
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idRole);
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
                'message' => 'Gagal! id Role tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteRole_post()
    {
        $idRole = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidRole = select_where('m_role', 'id', $idRole)->num_rows();
        
        if ($getidRole > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_role', 'id', $idRole, $update);
			//$delete = $this->super_model->delete_table('m_role', 'id', $idRole);
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
                'message' => 'Gagal! id Role tidak terdaftar'
            );
        }

        $this->response($data);
    }

}
