<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class AksesRole extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_akses_role', 'akses_role');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_akses_role', array('id' => $id, 'delete' => '0'));
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
            $datas = select_all_where('m_akses_role', 'delete', '0');			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        }
        $this->response($data);
    }
	
	function addAksesRole_post()
    {
    	$param = json_decode(file_get_contents("php://input"), true);
    	
		$idRole = $param['idRole'];
		//$namaRole = $param['namaRole'];
		$listMenu = $param['listMenu'];
		//debug_code($listMenu); die;
		$createdBy = $param['createdBy'];
		$createdDate = date("Y-m-d H:i:s");
		
		$getDataRole = select_where('m_akses_role', 'idRole', $idRole)->num_rows();
		if ($getDataRole > 0) {
			$delete = $this->super_model->delete_table('m_akses_role', 'idRole', $idRole);			
		}
		
		foreach ($listMenu as $key=>$val) {
			//echo $val["id"]." ".$val["menu"]." <br>"; 
			$idAkses = $val["id"];
			$status = $val["status"];
			
			$insert = insert_all('m_akses_role', array(
	            'idAkses' => $idAkses,
				'idRole' => $idRole,
				'status' => $status,
	            'createdBy' => $createdBy,
	            'createdDate' => $createdDate
	        ));
			
		}
        
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add Akses Role',
            );
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add Akses Role',
            );
        }
        $this->response($data);
    }
	
//	function updateAksesRole_post()
//    {
////		$idAksesRole = $this->post('id');
////      $idAkses = $this->post('idAkses');
////		$idRole = $this->post('idRole');		
//		
////      $getidAkses = select_where('m_akses_role', 'id', $idAksesRole)->num_rows();
//        
//   	 	$param = json_decode(file_get_contents("php://input"), true);
//    	
//		$idRole = $param['idRole'];
//		//$namaRole = $param['namaRole'];
//		$listMenu = $param['listMenu'];
//		//debug_code($listMenu); die;
//		$createdBy = $param['createdBy'];
//		$createdDate = date("Y-m-d H:i:s");
//				
//		$delete = $this->super_model->delete_table('m_akses_role', 'idRole', $idRole);
//		if ($delete) {
//			foreach ($listMenu as $key=>$val) {			
//				$idAkses = $val["id"];
//				$status = $val["status"];
//				
//				$insert = insert_all('m_akses_role', array(
//		            'idAkses' => $idAkses,
//					'idRole' => $idRole,
//					'status' => $status,
//		            'createdBy' => $createdBy,
//	            	'createdDate' => $createdDate
//		        ));
//				
//			}
//	        
//	    	if ($insert) {
//				$data = array(
//					'status' => 'success',
//					'message' => 'Success update data',
//				);
//			} else {
//				$data = array(
//					'status' => 'error',
//					'message' => 'Failed update data',
//				);
//			}
//		}else{
//			$data = array(
//                    'status' => 'error',
//                    'message' => 'Failed delete data',
//                );
//		}
//        
//        $this->response($data);
//    }
	
//	function deleteAksesRole_post()
//    {
//        $idAksesRole = $this->post('id');
//        $updatedBy = $this->post('updatedBy');
//		
//        $getidAkses = select_where('m_akses_role', 'id', $idAksesRole)->num_rows();
//        
//        if ($getidAkses > 0) {
//			$update['updatedBy'] = $updatedBy;
//            $update['updatedDate'] = date("Y-m-d H:i:s");
//            $update['delete'] = '1';
//            $delete = $this->super_model->update_table('m_akses_role', 'id', $idAksesRole, $update);
//			//$delete = $this->super_model->delete_table('m_akses_role', 'id', $idAksesRole);
//            if ($delete) {
//                $data = array(
//                    'status' => 'success',
//                    'message' => 'Success delete data',
//                );
//            } else {
//                $data = array(
//                    'status' => 'error',
//                    'message' => 'Failed delete data',
//                );
//            }
//        }
//        else {
//            $data = array(
//                'status' => 'error',
//                'message' => 'Gagal! id Akses tidak terdaftar'
//            );
//        }
//
//        $this->response($data);
//    }

}
