<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class Image extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_image', 'image');
		
		$this->path = 'assets/uploads/image/';
        
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id != '') {
            $datas = select_where_array('m_image', array('id' => $id, 'delete' => '0'));
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
            //$datas = select_all_where('m_image', 'delete', '0');			
			$datas = select_where_array('m_image', array('delete' => 0))->result();	
			
			foreach ($datas as $key) {
				$key->url = base_url().$this->path.$key->id."/".$key->file;
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
	
	function getImageAktive_get()
    {
        $datas = select_where_array('m_image', array('delete' => 0, 'status' => 'Aktive'))->result();	
			
		foreach ($datas as $key) {
			$key->url = base_url().$this->path.$key->id."/".$key->file;
		}
		
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'num_rows' => count($datas),
			'items' => $datas
		);
		
        $this->response($data);
    }
	
	function addImage_post()
    {
        $keteranganImage = $this->post('keteranganImage');
        $status = $this->post('status');        
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');			
		
		if(!$_FILES) {
			//$this->response('Please choose a files', 500);
			
			$data = array(
				'status' => 'error',
				'message' => 'Please choose a files'
			);				
			
		}else{
			$filename = str_replace(" ","_",$_FILES['file']['name']) ;
			$insert = insert_all('m_image', array(
				'keteranganImage' => $keteranganImage,
				'file' => $filename,
				'status' => $status,                      
				'createdBy' => $createdBy,
				'createdDate' => $createdDate,
			));
			if ($insert) {
				
				$idImage = $insert->id;
				
				$upload_path = $this->path.$idImage."/";
				
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
				
				$data = array(
					'status' => 'success',
					'message' => 'success add Image',
				);
			} else {
				$data = array(
					'status' => 'failed',
					'message' => 'failed add Image',
				);
			}
		}
		
		
        
        $this->response($data);
    }
	
	function updateImage_post()
    {
		$idImage = $this->post('id');
        $keteranganImage = $this->post('keteranganImage');
        $status = $this->post('status');      
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidImage = select_where('m_image', 'id', $idImage)->num_rows();
        
        if ($getidImage > 0) {
				
			if($_FILES) {
				$filename = str_replace(" ","_",$_FILES['file']['name']) ;
				
				$update = update('m_image', array(
					'keteranganImage' => $keteranganImage,
					'file' => $filename,			
					'status' => $status,					
					'updatedBy' => $updatedBy,
					'updatedDate' => $updatedDate
				), 'id', $idImage);
				
				if ($update) {
					$upload_path = $this->path.$idImage."/";
				
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
							
							$data = array(
								'status' => 'success',
								'message' => 'Success update data',
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
				} else {
					$data = array(
						'status' => 'error',
						'message' => 'Failed update data',
					);
				}
				
				
			}else{
				$update = update('m_image', array(
					'keteranganImage' => $keteranganImage,
					//'file' => $filename,			
					'status' => $status,					
					'updatedBy' => $updatedBy,
					'updatedDate' => $updatedDate
				), 'id', $idImage);
				
				if ($update) {
					$data = array(
						'status' => 'success',
						'message' => 'Success update data',
					);
				}else{
					$data = array(
						'status' => 'error',
						'message' => 'Failed update data',
					);
				}
			}			
		}
		else {
            $data = array(
                'status' => 'error',
                'message' => 'Gagal! id Image tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteImage_post()
    {
        $idImage = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidImage = select_where('m_image', 'id', $idImage)->num_rows();
        
        if ($getidImage > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_image', 'id', $idImage, $update);
			//$delete = $this->super_model->delete_table('m_image', 'id', $idImage);
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
                'message' => 'Gagal! id Image tidak terdaftar'
            );
        }

        $this->response($data);
    }

}
