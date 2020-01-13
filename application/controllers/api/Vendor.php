<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

//use Restserver\Libraries\REST_Controller;

class Vendor extends REST_Controller
{

    //private $path;
    //private $path_do;
    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_vendor', 'vendor');
        
    }

    function index_get()
    {
        $id = $this->get('id');
		$username = $this->get('username');
		
        if ($id != '') {
            $datas = select_where_array('m_vendor', array('id' => $id, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $datas = $datas->row();         

                //$datas->tglBergabung = date("d-m-Y", strtotime($datas->tglBergabung));
                
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
		}elseif ($username != '') {
			$dataUser = select_where_array('m_user', array('username' => $username, 'delete' => '0'));
            if ($dataUser->num_rows() > 0) {
				$dataUser = $dataUser->row(); 
				
				if ($dataUser->roleUser == 1 || $dataUser->roleUser == 2){
					$datas = select_where_array('m_vendor', array('delete' => 0))->result();
				}else{
					$datas = select_where_array('m_vendor', array('id' => $dataUser->vendor, 'delete' => '0'))->result();	
				}
                
				foreach ($datas as $key) {
					$key->tglBergabung = date("d-m-Y", strtotime($key->tglBergabung));
				}
							
				$data = array(
					'status' => 'success',
					'message' => 'success fetch data',
					'num_rows' => count($datas),
					'items' => $datas
				);
               
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => 'username not found',
                );
            }
        } else {
            //$datas = select_all_where('m_vendor', 'delete', '0');
         	$datas = select_where_array('m_vendor', array('delete' => 0))->result();	
            
        	foreach ($datas as $key) {
                $key->tglBergabung = date("d-m-Y", strtotime($key->tglBergabung));
            }
            			
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
	
	function addVendor_post()
    {
        $namaVendor = $this->post('namaVendor');
        $initial = $this->post('initial');
		$alamatVendor = $this->post('alamatVendor');
		$pic = $this->post('pic');
		$telepon = $this->post('telepon');
		$teleponPic = $this->post('teleponPic');
		$tglBergabung = $this->post('tglBergabung');
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
		$getNamaVendor = select_where('m_vendor', 'namaVendor', $namaVendor)->num_rows();
		$getInitial = select_where('m_vendor', 'initial', $initial)->num_rows();
		
		if ($getNamaVendor > 0 || $getInitial > 0){
			$data = array(
                'status' => 'failed',
                'message' => 'nama vendor or initial already exists',
            );
		}else{
			$insert = insert_all('m_vendor', array(
				'namaVendor' => $namaVendor,            
				'initial' => $initial,
				'alamatVendor' => $alamatVendor,
				'pic' => $pic,            
				'telepon' => $telepon,
				'teleponPic' => $teleponPic,
				'tglBergabung' => $tglBergabung,            
				'createdBy' => $createdBy,
				'createdDate' => $createdDate,
			));
			if ($insert) {
				$data = array(
					'status' => 'success',
					'message' => 'success add vendor',
				);
			} else {
				$data = array(
					'status' => 'failed',
					'message' => 'failed add vendor',
				);
			}
		}
		
        
        $this->response($data);
    }
	
	function updateVendor_post()
    {
		$idVendor = $this->post('id');
        $namaVendor = $this->post('namaVendor');
        $initial = $this->post('initial');
		$alamatVendor = $this->post('alamatVendor');
		$pic = $this->post('pic');
		$telepon = $this->post('telepon');
		$teleponPic = $this->post('teleponPic');
		$tglBergabung = $this->post('tglBergabung');
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidvendor = select_where('m_vendor', 'id', $idVendor)->num_rows();
        
        if ($getidvendor > 0) {
			
			//$getNamaVendor = select_where_array('m_vendor', array('id' => $id, 'namaVendor' => $namaVendor))->num_rows();
			//$getInitial = select_where_array('m_vendor', 'initial', $initial)->num_rows();
			
			$update = update('m_vendor', array(
				'namaVendor' => $namaVendor,
				'initial' => $initial,
				'alamatVendor' => $alamatVendor,
				'pic' => $pic,
				'telepon' => $telepon,
				'teleponPic' => $teleponPic,
				'tglBergabung' => $tglBergabung,				
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idVendor);
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
                'message' => 'Gagal! id vendor tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteVendor_post()
    {
        $idVendor = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidvendor = select_where('m_vendor', 'id', $idVendor)->num_rows();
        
        if ($getidvendor > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_vendor', 'id', $idVendor, $update);
			//$delete = $this->super_model->delete_table('m_vendor', 'id', $idVendor);
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
                'message' => 'Gagal! id vendor tidak terdaftar'
            );
        }

        $this->response($data);
    }

}
