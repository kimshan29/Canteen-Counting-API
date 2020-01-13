<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

//use Restserver\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

class User extends REST_Controller
{
    
    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        $this->load->model('Model_user', 'user');
        
    }

    function index_get()
    {
        $id = $this->get('id');
		$username = $this->get('username');
		
        if ($id != '') {
            $datas = select_where_array('m_user', array('id' => $id, 'delete' => '0'));
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
		}elseif ($username != '') {
            $datas = select_where_array('m_user', array('username' => $username, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $data = array(
                    'status' =>  true,
                    'message' => 'username already exists',                    
                );
            } else {
                $data = array(
                    'status' => 'error',
                    'message' => 'ID not found',
                );
            }
        } else {
//            $datas = select_all_where('m_user', 'delete', '0');		
            $datas = select_where_array('m_user', array('delete' => 0))->result();
            
        	foreach ($datas as $key) {                
				if ($key->jabatan){
					$jabatan = select_where('m_jabatan', 'id', $key->jabatan)->row();                  
					$key->namaJabatan = $jabatan->namaJabatan;
				}
                
                $role = select_where('m_role', 'id', $key->roleUser)->row();                  
                $key->namaRole = $role->namaRole;
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
	
	function addUser_post()
    {
        $namaLengkap = $this->post('namaLengkap');
        $username = $this->post('username');
        $email = $this->post('email');
        $password = $this->post('password');
		$jabatan = $this->post('jabatan');
		$roleUser = $this->post('roleUser');		
		$vendor = $this->post('vendor');
		$nip = $this->post('nip');
		
		if ($vendor){
			$vendor = $vendor;
		}else{
			$vendor = 0;
		}
		
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
		$getUsername = select_where('m_user', 'username', $username)->num_rows();
		$getEmail = select_where('m_user', 'email', $email)->num_rows();
		
		if ($getUsername > 0 || $getEmail > 0){
			$data = array(
                'status' => 'failed',
                'message' => 'username or email already exists',
            );
		}else{
			$insert = insert_all('m_user', array(
				'namaLengkap' => $namaLengkap,            
				'username' => $username,
				'email' => $email,
				'password' => $password,
				'jabatan' => $jabatan,
				'roleUser' => $roleUser,                                   
				'vendor' => $vendor,
				'nip' => $nip,
				'createdBy' => $createdBy,
				'createdDate' => $createdDate,
			));
			if ($insert) {
				$data = array(
					'status' => 'success',
					'message' => 'success add User',
				);
			} else {
				$data = array(
					'status' => 'failed',
					'message' => 'failed add User',
				);
			}
		}		
        
        $this->response($data);
    }
	
	function updateUser_post()
    {
		$idUser = $this->post('id');
        $namaLengkap = $this->post('namaLengkap');
        $username = $this->post('username');
        $email = $this->post('email');
        $password = $this->post('password');
		$jabatan = $this->post('jabatan');
		$roleUser = $this->post('roleUser');
		$vendor = $this->post('vendor');		
		$nip = $this->post('nip');
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
		if ($vendor){
			$vendor = $vendor;
		}else{
			$vendor = 0;
		}
		
        $getidUser = select_where('m_user', 'id', $idUser)->num_rows();
        
        if ($getidUser > 0) {
			$update = update('m_user', array(
				'namaLengkap' => $namaLengkap,            
				'username' => $username,
				'email' => $email,
        		'password' => $password,
				'jabatan' => $jabatan,
				'roleUser' => $roleUser,		
				'vendor' => $vendor,              
				'nip' => $nip,
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idUser);
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
                'message' => 'Gagal! id User tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteUser_post()
    {
        $idUser = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidUser = select_where('m_user', 'id', $idUser)->num_rows();
        
        if ($getidUser > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('m_user', 'id', $idUser, $update);
			//$delete = $this->super_model->delete_table('m_user', 'id', $idUser);
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
                'message' => 'Gagal! id User tidak terdaftar'
            );
        }

        $this->response($data);
    }
    
	function login_post()
    {
        $username = $this->post('username');
//        $password = md5($this->post('password'));
        $password = $this->post('password');
        
        $userLogin = $this->user->login($username, $password);
        
        if ($userLogin->num_rows() > 0) {
            $users = $userLogin->row();
			
			if ($users->jabatan){
				$jabatan = select_where('m_jabatan', 'id', $users->jabatan)->row();                  
				$users->jabatan = $jabatan->namaJabatan;
			}
			
			$role = select_where('m_role', 'id', $users->roleUser)->row();                  
			$users->roleUser = $role->namaRole;
            
            if ($users->islogin == 1){
            	$data = array(
	                'status' => 'user_islogin',
	                'message' => 'Username is login',
	                'items' => $users,
	            );
            }else{
            	$update['islogin'] = '1';
	            $islogin = $this->super_model->update_table('m_user', 'id', $users->id, $update);
	            $userislogin = select_where_array('m_user', array('id' => $users->id))->row();
				
				if ($userislogin->jabatan){
					$jabatan = select_where('m_jabatan', 'id', $userislogin->jabatan)->row();                  
					$userislogin->jabatan = $jabatan->namaJabatan;
				}
				
				$role = select_where('m_role', 'id', $userislogin->roleUser)->row();
				$userislogin->roleUser = $role->namaRole;
				
	            $data = array(
	                'status' => 'success',
	                'message' => 'Success login',
	                'items' => $userislogin,
	            );
            }
            
        }else{
        	$data = array(
                'status' => 'not_found',
                'message' => 'Username is not exists',
                'items' => array('username' => $username)
            );
        }
        
        $this->response($data);
    }
    
	function logout_post()
    {
   	 	$username = $this->post('username');
        $user = select_where_array('m_user', array('username' => $username));
        
        if ($user->num_rows() > 0) {
            $users = $user->row();
            
            $update['islogin'] = '0';
            $islogin = $this->super_model->update_table('m_user', 'id', $users->id, $update);
            $userislogin = select_where_array('m_user', array('id' => $users->id));
            $data = array(
                'status' => 'success',
                'message' => 'Success logout',
                'items' => $userislogin->row(),
            );
            
        }else{
        	$data = array(
                'status' => 'not_found',
                'message' => 'Username is not exists',
                'items' => array('username' => $username)
            );
        }
        $this->response($data);
    }
	
	function GetHakAksesMenu_get()
    {
    	$username = $this->get('username');
    	
		$getRoleUser = select_where('m_user', 'username', $username);		
		
		if ($getRoleUser->num_rows() > 0) {
			$getRoleUser = $getRoleUser->row();
			$roleUser = $getRoleUser->roleUser;
			
			$sql = "SELECT a.id as idAkses, icon, namaMenu FROM m_akses a
				INNER JOIN m_akses_role b ON a.id = b.idAkses
				WHERE b.idRole = ".$roleUser."
				AND a.menuParent is NULL AND levelMenu = 1
				AND b.status = 1
				order by noUrut";
			$getdataParent = $this->db->query($sql);
			
			$datas = array();
			if ($getdataParent->num_rows() > 0) {				
				 foreach($getdataParent->result() as $key) {					
					
					$sqlMenu = "SELECT 
									url, icon, namaMenu 
								FROM 
									m_akses a 
									INNER JOIN m_akses_role b ON a.id = b.idAkses
								WHERE 
									a.menuParent = ".$key->idAkses."
									and b.status = 1
									and b.idRole = ".$roleUser."
								order by noUrut";				
					
					$getdata = $this->db->query($sqlMenu);
					$menuItem = $getdata->result();
					
					$datas[] = array('icon' => $key->icon, 'namaMenu' => $key->namaMenu, 'menuItem' => $menuItem);
					
				 }
			}				
			
			$data = array(
				'status' => 'success',
				'message' => 'success fetch data',				
				'items' => $datas
			);
		}else{
			
			$data = array(
				'status' => 'not_found',
				'message' => 'Username is not exists',			
				'items' => array('username' => $username)
			);
		}
        $this->response($data);
    }

}
