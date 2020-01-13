<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class Jabatan extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_jabatan', 'jabatan');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_jabatan', array('id' => $id, 'delete' => '0'));
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
            $datas = select_all_where('m_jabatan', 'delete', '0');			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        }
        $this->response($data);
    }
	
	function addJabatan_post()
    {
        $namaJabatan = $this->post('namaJabatan');
        $keterangan = $this->post('keterangan');        
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
        $insert = insert_all('m_jabatan', array(
            'namaJabatan' => $namaJabatan,
        	'keterangan' => $keterangan,                      
            'createdBy' => $createdBy,
            'createdDate' => $createdDate,
        ));
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add Jabatan',
            );
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add Jabatan',
            );
        }
        $this->response($data);
    }
	
	function updateJabatan_post()
    {
		$idJabatan = $this->post('id');
        $namaJabatan = $this->post('namaJabatan');  
        $keterangan = $this->post('keterangan');      
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidJabatan = select_where('m_jabatan', 'id', $idJabatan)->num_rows();
        
        if ($getidJabatan > 0) {
			$update = update('m_jabatan', array(
				'namaJabatan' => $namaJabatan,			
				'keterangan' => $keterangan,					
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idJabatan);
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
                'message' => 'Gagal! id Jabatan tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteJabatan_post()
    {
        $idJabatan = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidJabatan = select_where('m_jabatan', 'id', $idJabatan)->num_rows();
        
        if ($getidJabatan > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_jabatan', 'id', $idJabatan, $update);
			//$delete = $this->super_model->delete_table('m_jabatan', 'id', $idJabatan);
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
                'message' => 'Gagal! id Jabatan tidak terdaftar'
            );
        }

        $this->response($data);
    }

}
