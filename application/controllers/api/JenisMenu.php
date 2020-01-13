<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class jenisMenu extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_jenis_menu', 'jenis_menu');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_jenis_menu', array('id' => $id, 'delete' => '0'));
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
            $datas = select_all_where('m_jenis_menu', 'delete', '0');			
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
	
	function addJenisMenu_post()
    {
        $namaJenisMenu = $this->post('namaJenisMenu');
        $cssMenu = $this->post('cssMenu');
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
        $insert = insert_all('m_jenis_menu', array(
            'namaJenisMenu' => $namaJenisMenu,
        	'cssMenu' => $cssMenu,                      
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
	
	function updateJenisMenu_post()
    {
		$idJenisMenu = $this->post('id');
        $namaJenisMenu = $this->post('namaJenisMenu');
        $cssMenu = $this->post('cssMenu');  
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidJenisMenu = select_where('m_jenis_menu', 'id', $idJenisMenu)->num_rows();
        
        if ($getidJenisMenu > 0) {
			$update = update('m_jenis_menu', array(
				'namaJenisMenu' => $namaJenisMenu,
				'cssMenu' => $cssMenu,							
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idJenisMenu);
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
	
	function deleteJenisMenu_post()
    {
        $idJenisMenu = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidJenisMenu = select_where('m_jenis_menu', 'id', $idJenisMenu)->num_rows();
        
        if ($getidJenisMenu > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_jenis_menu', 'id', $idJenisMenu, $update);
			//$delete = $this->super_model->delete_table('m_jenis_menu', 'id', $idJenisMenu);
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
