<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class Akses extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_akses', 'akses');
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_akses', array('id' => $id, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $datas = $datas->row();       
                $datas->noUrut = intval($datas->noUrut);
                
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
            //$datas = select_all_where('m_akses', 'delete', '0');
        	$datas = select_where_array_order('m_akses', array('delete' => 0),'noUrut','ASC')->result();	
            
        	foreach ($datas as $key) {
                $key->noUrut = intval($key->noUrut);
                
                if ($key->menuParent){
                	$parent = select_where('m_akses', 'id', $key->menuParent)->row();
	                $key->menuParent = $parent->namaMenu;	
                }                
                
            }			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        }
        $this->response($data);
    }
    
	function getParentMenu_get()
    {
    	$datas = select_where_array('m_akses', array('delete' => 0, 'tipeMenu' => 'header', 'levelMenu' => '1'))->result();	
            
        	foreach ($datas as $key) {
                $key->noUrut = intval($key->noUrut);
            }			
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
        
        $this->response($data);
    }
    
	function getAksesByIdRole_get()
    {
    	$id = $this->get('id');
    	
    	$datas = select_where_array_order('m_akses', array('delete' => 0),'noUrut','ASC')->result();	
            
        	foreach ($datas as $key) {
                $key->noUrut = intval($key->noUrut);
                
                $role = select_where_array('m_akses_role', array('idRole' => $id, 'idAkses' => $key->id));
                if ($role->num_rows() > 0) {
                	$role = $role->row();
                	
                	if ($role->status == 1){
                		$key->status = true;
                	}else{
                		$key->status = false;
                	}
                	
                }else{
                	$key->status = false;
                }
            }			
            
            $data = array(
                'status' => 'success',
                'message' => 'success fetch data',
                'num_rows' => count($datas),
                'items' => $datas
            );
            
    		
        
        $this->response($data);
    }
	
	function addAkses_post()
    {
        $namaMenu = $this->post('namaMenu');
        $url = $this->post('url');
        $menuParent = $this->post('menuParent');
        $noUrut = $this->post('noUrut');
        $levelMenu = $this->post('levelMenu');
        $tipeMenu = $this->post('tipeMenu');
        $target = $this->post('target');
        $icon = $this->post('icon');        
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
        $insert = insert_all('m_akses', array(
            'namaMenu' => $namaMenu,
        	'url' => $url,
        	'menuParent' => $menuParent,
        	'noUrut' => $noUrut,
        	'levelMenu' => $levelMenu,
        	'tipeMenu' => $tipeMenu,
        	'target' => $target,
        	'icon' => $icon,                      
            'createdBy' => $createdBy,
            'createdDate' => $createdDate,
        ));
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add Akses',
            );
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add Akses',
            );
        }
        $this->response($data);
    }
	
	function updateAkses_post()
    {
		$idAkses = $this->post('id');
        $namaMenu = $this->post('namaMenu');
        $url = $this->post('url');
        $menuParent = $this->post('menuParent');
        $noUrut = $this->post('noUrut');
        $levelMenu = $this->post('levelMenu');
        $tipeMenu = $this->post('tipeMenu');
        $target = $this->post('target');        
        $icon = $this->post('icon');  
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidAkses = select_where('m_akses', 'id', $idAkses)->num_rows();
        
        if ($getidAkses > 0) {
			$update = update('m_akses', array(
				'namaMenu' => $namaMenu,
	        	'url' => $url,
	        	'menuParent' => $menuParent,
	        	'noUrut' => $noUrut,
	        	'levelMenu' => $levelMenu,
	        	'tipeMenu' => $tipeMenu,
	        	'target' => $target,
	        	'icon' => $icon,								
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idAkses);
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
	
	function deleteAkses_post()
    {
        $idAkses = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidAkses = select_where('m_akses', 'id', $idAkses)->num_rows();
        
        if ($getidAkses > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_akses', 'id', $idAkses, $update);
			//$delete = $this->super_model->delete_table('m_akses', 'id', $idAkses);
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
