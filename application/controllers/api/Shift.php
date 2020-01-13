<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class Shift extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_shift', 'shift');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_shift', array('id' => $id, 'delete' => '0'));
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
            $datas = select_all_where('m_shift', 'delete', '0');			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        }
        $this->response($data);
    }
	
	function addShift_post()
    {
        $namaShift = $this->post('namaShift');
        $jamMulai = $this->post('jamMulai');
		$jamSelesai = $this->post('jamSelesai');
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
        $insert = insert_all('m_shift', array(
            'namaShift' => $namaShift,
        	'jamMulai' => $jamMulai,
			'jamSelesai' => $jamSelesai,
            'createdBy' => $createdBy,
            'createdDate' => $createdDate,
        ));
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add Shift',
            );
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add Shift',
            );
        }
        $this->response($data);
    }
	
	function updateShift_post()
    {
		$idShift = $this->post('id');
        $namaShift = $this->post('namaShift');  
        $jamMulai = $this->post('jamMulai');
		$jamSelesai = $this->post('jamSelesai');
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidShift = select_where('m_shift', 'id', $idShift)->num_rows();
        
        if ($getidShift > 0) {
			$update = update('m_shift', array(
				'namaShift' => $namaShift,			
				'jamMulai' => $jamMulai,
				'jamSelesai' => $jamSelesai,
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idShift);
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
                'message' => 'Gagal! id Shift tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteShift_post()
    {
        $idShift = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidShift = select_where('m_shift', 'id', $idShift)->num_rows();
        
        if ($getidShift > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_shift', 'id', $idShift, $update);
			//$delete = $this->super_model->delete_table('m_shift', 'id', $idShift);
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
                'message' => 'Gagal! id Shift tidak terdaftar'
            );
        }

        $this->response($data);
    }

}
