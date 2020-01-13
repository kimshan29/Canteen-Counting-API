<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

class Counting extends REST_Controller
{

    //private $path;
    //private $path_do;
    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_counting', 'counting');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_counting', array('id' => $id, 'delete' => '0'));
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
            $datas = select_all_where('m_counting', 'delete', '0');			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        }
        $this->response($data);
    }
	
	function addCounting_post()
    {
        $namaLengkap = $this->post('namaLengkap');        
		$jabatan = $this->post('jabatan');
		$status = $this->post('status');
		$periodeFrom = $this->post('periodeFrom');
		$periodeEnd = $this->post('periodeEnd');
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
        $insert = insert_all('m_counting', array(
            'namaLengkap' => $namaLengkap,            
            'jabatan' => $jabatan,
			'status' => $status,
			'periodeFrom' => $periodeFrom,
			'periodeEnd' => $periodeEnd,
            'createdBy' => $createdBy,
            'createdDate' => $createdDate,
        ));
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add Counting',
            );
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add Counting',
            );
        }
        $this->response($data);
    }
	
	function updateCounting_post()
    {
		$idCounting = $this->post('id');
        $namaLengkap = $this->post('namaLengkap');        
		$jabatan = $this->post('jabatan');
		$status = $this->post('status');
		$periodeFrom = $this->post('periodeFrom');
		$periodeEnd = $this->post('periodeEnd');
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidCounting = select_where('m_counting', 'id', $idCounting)->num_rows();
        
        if ($getidCounting > 0) {
			$update = update('m_counting', array(
				'namaLengkap' => $namaLengkap,				
				'jabatan' => $jabatan,
				'status' => $status,
				'periodeFrom' => $periodeFrom,
				'periodeEnd' => $periodeEnd,
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idCounting);
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
                'message' => 'Gagal! id Counting tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteCounting_post()
    {
        $idCounting = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidCounting = select_where('m_counting', 'id', $idCounting)->num_rows();
        
        if ($getidCounting > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_counting', 'id', $idCounting, $update);
			//$delete = $this->super_model->delete_table('m_counting', 'id', $idCounting);
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
                'message' => 'Gagal! id Counting tidak terdaftar'
            );
        }

        $this->response($data);
    }

}
