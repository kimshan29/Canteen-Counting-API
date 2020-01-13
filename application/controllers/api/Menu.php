<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';


header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

//use Restserver\Libraries\REST_Controller;

class Menu extends REST_Controller
{
	
    function __construct($config = 'rest')
    {
        parent::__construct($config);		
        $this->load->database();        
        //$this->load->model('Model_menu', 'menu');
		
		$this->path = 'assets/uploads/menu/';        
    }

    function index_get()
    {
        $id = $this->get('id');
		$username = $this->get('username');
		
        if ($id != '') {
            $datas = select_where_array('m_menu', array('id' => $id, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $datas = $datas->row();  
                $vendor = select_where('m_vendor', 'id', $datas->idVendor)->row();       
                $datas->namaVendor = $datas->idVendor;
                
//                $jenisMenu = select_where('m_jenis_menu', 'id', $datas->jenisMenu)->row();
//                $datas->jenisMenu = $jenisMenu->namaJenisMenu;
                
                $datas->createdDate = date("d-m-Y", strtotime($datas->createdDate));
                
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
					$datas = select_where_array('m_menu', array('delete' => 0))->result();	
				}else{
					$datas = select_where_array('m_menu', array('idVendor' => $dataUser->vendor, 'delete' => '0'))->result();	
				}
                
				foreach ($datas as $key) {
					
					$vendor = select_where('m_vendor', 'id', $key->idVendor)->row();                  
					$key->namaVendor = $vendor->namaVendor;
					
					$jenisMenu = select_where('m_jenis_menu', 'id', $key->jenisMenu)->row();                
					$key->jenisMenu = $jenisMenu->namaJenisMenu;
					$key->cssMenu = $jenisMenu->cssMenu;
					
					$key->createdDate = date("d-m-Y", strtotime($key->createdDate));
					
					$key->url = base_url().$this->path.$key->id."/".$key->file;
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
            //$datas = select_all_where('m_menu', 'delete', '0');
            $datas = select_where_array('m_menu', array('delete' => 0))->result();	
            
        	foreach ($datas as $key) {
                
                $vendor = select_where('m_vendor', 'id', $key->idVendor)->row();                  
                $key->namaVendor = $vendor->namaVendor;
                
                $jenisMenu = select_where('m_jenis_menu', 'id', $key->jenisMenu)->row();                
                $key->jenisMenu = $jenisMenu->namaJenisMenu;
                $key->cssMenu = $jenisMenu->cssMenu;
                
                $key->createdDate = date("d-m-Y", strtotime($key->createdDate));
				
				$key->url = $this->path.$key->id."/";
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
	
	function searchMenuMakanan_get(){
		$namaMenu = $this->get('namaMenu');
		$idVendor = $this->get('idVendor');
		$jenisMenu = $this->get('jenisMenu');
		
		//$voucher = select_where_like_array('dc_voucher', array('expired>=' => date('Y-m-d')), 'name', $name)->result();
		$datas = select_where_like_array('m_menu', array('delete' => '0', 'jenisMenu' => $jenisMenu, 'idVendor' => $idVendor), 'namaMenu', $namaMenu) ->result();
		
		if ($datas) {
			foreach ($datas as $key) {
				
				$vendor = select_where('m_vendor', 'id', $key->idVendor)->row();                  
				$key->namaVendor = $vendor->namaVendor;
				
				$jenisMenu = select_where('m_jenis_menu', 'id', $key->jenisMenu)->row();                
				$key->jenisMenu = $jenisMenu->namaJenisMenu;
				$key->cssMenu = $jenisMenu->cssMenu;
				
				$key->createdDate = date("d-m-Y", strtotime($key->createdDate));
				
				$key->url = base_url().$this->path.$key->id."/".$key->file;
			}	
		}
		
		/*$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			//'num_rows' => count($datas),
			'items' => $datas
		);*/
		
		header('Content-Type: application/json');
		$this->response($datas);
	}
	
	function searchMenuTambahan_get(){
		$namaTambahan = $this->get('namaTambahan');
		
		//$voucher = select_where_like_array('dc_voucher', array('expired>=' => date('Y-m-d')), 'name', $name)->result();
		$datas = select_where_like_array('m_menu', array('delete' => '0', 'jenisMenu' => '4'), 'namaMenu', $namaTambahan) ->result();
		
		if ($datas) {
			foreach ($datas as $key) {
				
				$vendor = select_where('m_vendor', 'id', $key->idVendor)->row();                  
				$key->namaVendor = $vendor->namaVendor;
				
				$jenisMenu = select_where('m_jenis_menu', 'id', $key->jenisMenu)->row();                
				$key->jenisMenu = $jenisMenu->namaJenisMenu;
				$key->cssMenu = $jenisMenu->cssMenu;
				
				$key->createdDate = date("d-m-Y", strtotime($key->createdDate));
				
				$key->url = base_url().$this->path.$key->id."/".$key->file;
			}	
		}
		
		/*$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			//'num_rows' => count($datas),
			'items' => $datas
		);*/
		
		header('Content-Type: application/json');
		$this->response($datas);
	}
	
	function addMenu_post()
    {
		$jenisMenu = $this->post('jenisMenu');
        $namaMenu = $this->post('namaMenu');        
		$infoKalori = $this->post('infoKalori');
		$infoAlergi = $this->post('infoAlergi');
		//$file = $this->post('file');
		$namaVendor = $this->post('namaVendor');
		//$stok = $this->post('stok');		
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
				
        $insert = insert_all('m_menu', array(
			'jenisMenu' => $jenisMenu,
            'namaMenu' => $namaMenu,            
            'infoKalori' => $infoKalori,
			'infoAlergi' => $infoAlergi,            
            //'file' => $file,
        	'idVendor' => $namaVendor,                     
			//'stok' => $stok,                     
            'createdBy' => $createdBy,
            'createdDate' => $createdDate,
        ));
        if ($insert) {
            $data = array(
                'status' => 'success',
                'message' => 'success add menu',
				'id' => $insert->id,
            );
			
        } else {
            $data = array(
                'status' => 'failed',
                'message' => 'failed add menu',
            );
        }
        $this->response($data);
    }
	
	function updateMenu_post()
    {
		$idMenu = $this->post('id');
        $jenisMenu = $this->post('jenisMenu');
        $namaMenu = $this->post('namaMenu');        
		$infoKalori = $this->post('infoKalori');
		$infoAlergi = $this->post('infoAlergi');
		//$file = $this->post('file');
		$namaVendor = $this->post('namaVendor');
		//$stok = $this->post('stok');		
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidMenu = select_where('m_menu', 'id', $idMenu)->num_rows();
        
        if ($getidMenu > 0) {
			$update = update('m_menu', array(
				'jenisMenu' => $jenisMenu,
				'namaMenu' => $namaMenu,            
				'infoKalori' => $infoKalori,
				'infoAlergi' => $infoAlergi,            
				//'file' => $file,
				'idVendor' => $namaVendor,
				//'stok' => $stok,          
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idMenu);
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
                'message' => 'Gagal! id menu tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteMenu_post()
    {
        $idMenu = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidMenu = select_where('m_menu', 'id', $idMenu)->num_rows();
        
        if ($getidMenu > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_menu', 'id', $idMenu, $update);
			//$delete = $this->super_model->delete_table('m_menu', 'id', $idMenu);
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
                'message' => 'Gagal! id menu tidak terdaftar'
            );
        }

        $this->response($data);
    }
	
	function uploadMenu_post()
    {
		$idMenu = $this->post('id');
		
		$getidMenu = select_where('m_menu', 'id', $idMenu)->num_rows();        
		if ($getidMenu > 0) {
			if(!$_FILES) {
				//$this->response('Please choose a files', 500);
				
				$data = array(
					'status' => 'error',
					'message' => 'Please choose a files'
				);				
				
			}else{
				$upload_path = $this->path.$idMenu."/";
				//file upload destination
				$config['upload_path'] = $upload_path;
				//allowed file types. * means all types
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				//allowed max file size. 0 means unlimited file size
				$config['max_size'] = '0';
				//max file name size
				$config['max_filename'] = '255';
				//whether file name should be encrypted or not
				$config['encrypt_name'] = FALSE;
				
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				
				if (file_exists($upload_path . $_FILES['file']['name'])) {
					//$this->response('File already exists => ' . $upload_path . $_FILES['file']['name']);
					//return;
					
					$data = array(
						'status' => 'error',
						'message' => 'File already exists => ' . $upload_path . $_FILES['file']['name']
					);	
					
				} else {
					if (!file_exists($upload_path)) {
						mkdir($upload_path, 0777, true);
					}
					
					if($this->upload->do_upload('file')) {
						//$this->response('File successfully uploaded => "' . $upload_path . $_FILES['file']['name']);
						//return;						
						
						$fileName = str_replace(" ","_",$_FILES['file']['name']) ;
						$update = update('m_menu', array(							            
							'file' => $fileName							
						), 'id', $idMenu);
						
						$data = array(
							'status' => 'success',
							'message' => 'Success upload file',
						);
						
					} else {
						//$this->response('Error during file upload => "' . $this->upload->display_errors(), 500);
						//return;
						
						$data = array(
							'status' => 'error',
							'message' => 'Error during file upload => "' . $this->upload->display_errors(),
						);
					}
				}
			}		
			
		}else{
			 $data = array(
                'status' => 'error',
                'message' => 'Gagal! id menu tidak terdaftar'
            );
		}
		
		$this->response($data);
    }
}
