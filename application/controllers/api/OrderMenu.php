<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

//use Restserver\Libraries\REST_Controller;

class OrderMenu extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_order_menu', 'order_menu');
		
		$this->path = 'assets/uploads/menu/';    
        
    }	
	
	function addOrderMenu_post()
    {
		
		$param = json_decode(file_get_contents("php://input"), true);
		        
		$username = $param['username'];
		$shift = $param['shift'];
		$date = $param['date'];		
		$createdBy = $param['createdBy']; 
		$createdDate = date("Y-m-d H:i:s");
		$detailOrder = $param['detailOrder'];
		
		$dateinput = date("Y-m-d", strtotime($date));
		
		foreach ($detailOrder as $key=>$val) {				
				$idMenuOrdered[] = $val["id"];
		}
		
		$idMenuOrdered = json_encode($idMenuOrdered);
		$string = array("[","]");
		$replace = array("(",")");
		$idMenuOrdered = str_replace($string,$replace,$idMenuOrdered);
		
		//$getData = select_where_array('t_order_menu', array('DATE(date)' => $dateinput, 'username' => $username))->num_rows();
		//echo $getData; die;
		
		$sql = "SELECT 
					*
				FROM 
					t_order_menu
				WHERE 
					`delete` = 0
					AND username = '".$username."'
					AND DATE(date) = '".$dateinput."'
					AND shift = ".$shift."
					AND idMenu in ".$idMenuOrdered."
				";		
		$getdata = $this->db->query($sql);
		
		if ($getdata->num_rows() > 0) {		
			$data = array(
                'status' => 'failed',
                'message' => 'Username already ordered today',
            );
		}else{
			foreach ($detailOrder as $key=>$val) {				
				$idMenu = $val["id"];				
				
				$insert = insert_all('t_order_menu', array(
					'date' => $dateinput,
					'shift' => $shift,
					'username' => $username,
					'idMenu' => $idMenu,					
					'createdBy' => $createdBy,
					'createdDate' => $createdDate
				));
				
				// update stok
				/*$sql = "SELECT 
						a.date, a.vendor, a.shift, b.id AS idScheduleDetail, b.jenisMenu, b.idMenu, b.stok
					FROM 
						t_schedule_vendor a
					INNER JOIN 
						t_schedule_vendor_detail b
					ON a.id = b.idSchedule
					WHERE 
						a.`delete` = 0
						AND DATE(a.date) = '".$dateinput."'
						AND b.idMenu = ".$idMenu."
					";						
				$getdata = $this->db->query($sql);
				
				if ($getdata->num_rows() > 0) {				
					$data=$getdata->row();
					$idScheduleDetail = $data->idScheduleDetail;
					$stok = $data->stok;
					$stokupdate = $stok - 1;
					
					$updateStok = update('t_schedule_vendor_detail', array(
						'stok' => $stokupdate						
					), 'id', $idScheduleDetail);
				}*/
			}
			
			if ($insert) {
				$data = array(
					'status' => 'success',
					'message' => 'success add Order Menu',
				);
			} else {
				$data = array(
					'status' => 'failed',
					'message' => 'failed add Order Menu',
				);
			}
		}		
		        
        $this->response($data);
    }
	
	function getListDaftarMenu_get()
    {		
		$shift = $this->get('shift');
		$date = $this->get('date');
		$dateinput = date("Y-m-d", strtotime($date));
		
		$sql = "SELECT 
						b.idMenu as id, a.date, a.vendor, a.shift, b.id AS idScheduleDetail, b.jenisMenu, b.stok
					FROM 
						t_schedule_vendor a
					INNER JOIN 
						t_schedule_vendor_detail b
					ON a.id = b.idSchedule
					WHERE 
						a.`delete` = 0
						AND DATE(a.date) = '".$dateinput."'
						AND a.shift = ".$shift."
					";				
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {			
				$menu = select_where('m_menu', 'id', $key->id)->row();             
				$key->namaMenu = $menu->namaMenu;
				$key->urlImage = base_url().$this->path.$key->id."/".$menu->file;				
				$key->infoKalori = $menu->infoKalori;
				$key->infoAlergi = $menu->infoAlergi;
			
				$datas[] = array('id' => $key->id, 'namaMenu' => $key->namaMenu, 'urlImage' => $key->urlImage, 'infoKalori' => $key->infoKalori, 'infoAlergi' => $key->infoAlergi);				
			}			
		}
					
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'num_rows' => count($datas),
			'items' => $datas
		);
		
		header('Content-Type: application/json');
		$this->response($data);
	}
	
	function getListOrdered_get()
    {
		$username = $this->get('username');
		$shift = $this->get('shift');
		$date = $this->get('date');
		$dateinput = date("Y-m-d", strtotime($date));
		
		$sql = "SELECT 
						*
					FROM 
						t_order_menu
					WHERE 
						`delete` = 0
						AND username = '".$username."'
						AND DATE(date) = '".$dateinput."'
						AND shift = ".$shift."
					";
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {			
				$menu = select_where('m_menu', 'id', $key->idMenu)->row();             
				$key->namaMenu = $menu->namaMenu;
				$key->urlImage = base_url().$this->path.$key->idMenu."/".$menu->file;				
				$key->infoKalori = $menu->infoKalori;
				$key->infoAlergi = $menu->infoAlergi;
			
				$datas[] = array('id' => $key->idMenu, 'namaMenu' => $key->namaMenu, 'urlImage' => $key->urlImage, 'infoKalori' => $key->infoKalori, 'infoAlergi' => $key->infoAlergi);				
			}			
		}
					
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'num_rows' => count($datas),
			'items' => $datas
		);
		
		header('Content-Type: application/json');
		$this->response($data);
	}
	
	function getCountingCanteen_get()
    {
		$namaRole = array("Employee", "Third Party", "Visitor");
		$namaRoles = json_encode($namaRole);
		$string = array("[","]");
		$replace = array("(",")");
		$namaRoles = str_replace($string,$replace,$namaRoles);
		
		$username = $this->get('username');
		$shift = $this->get('shift');
		$date = $this->get('date');
		$dateinput = date("Y-m-d", strtotime($date));
		
		$sql = "SELECT 
					*
				FROM 
					m_role
				WHERE 
					`delete` = 0
					AND namaRole in ".$namaRoles."		
				order by namaRole asc
				";		
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {
									
			$getdataresult = $getdata->result();
			foreach($getdataresult as $key) {
				$roleName = str_replace(' ', '', $key->namaRole);
				$cssRole = $key->cssRole;
				
				$userCount = select_where_array('m_user', array('roleUser' => $key->id))->num_rows();
				
				$sqlOrder = "SELECT 
						a.*, u.roleUser
					FROM 
						t_order_menu a
					INNER JOIN m_user u
						on a.username = u.username
					WHERE 
						a.`delete` = 0
						
						AND DATE(a.date) = '".$dateinput."'
						AND a.shift = ".$shift."
						AND u.roleUser = ".$key->id."
					GROUP by 
						date, shift, username
					";				
				$getdataOder = $this->db->query($sqlOrder);			
				$countOrder = $getdataOder->num_rows();
				
				if ($countOrder != 0 && $userCount !=0){
					$presentaseOrder = $countOrder / $userCount * 100;
				}else{
					$presentaseOrder = 0;
				}
				
				$datas[] = array('jml' => $countOrder, 'presentase' => $presentaseOrder, 'namaRole' => $roleName, 'cssRole' => $cssRole);	
				
				//$key->jml = $countOrder;	
				//$key->presentase = $presentaseOrder;	
			}
			
		}
					
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			//'num_rows' => count($datas),
			'items' => $datas
		);
		
		header('Content-Type: application/json');
		$this->response($data);
	}
	
	function getListUserOrder_get()
    {		
		$shift = $this->get('shift');
		$date = $this->get('date');
		$dateinput = date("Y-m-d", strtotime($date));
		
		$sql = "SELECT 
						a.*, u.roleUser, u.nip, u.namaLengkap
						FROM 
							t_order_menu a
						INNER JOIN m_user u
							on a.username = u.username
						WHERE 
							a.`delete` = 0							
							AND DATE(a.date) = '".$dateinput."'
							AND a.shift = ".$shift."						
						GROUP by 
							date, shift, username
						Order by id desc
						LIMIT 5
						";		
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {
			foreach($getdata->result() as $key) {			
				$datas[] = array('nip' => $key->nip, 'namaKaryawan' => $key->namaLengkap);
			}			
		}
					
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			//'num_rows' => count($datas),
			'items' => $datas
		);
		
		header('Content-Type: application/json');
		$this->response($data);
	}
	
	function getStokMenu_get()
    {		
		$shift = $this->get('shift');
		$date = $this->get('date');
		$dateinput = date("Y-m-d", strtotime($date));
		
		$sql = "SELECT 
						b.idMenu as id, a.date, a.vendor, a.shift, b.id AS idScheduleDetail, b.jenisMenu, b.stok
					FROM 
						t_schedule_vendor a
					INNER JOIN 
						t_schedule_vendor_detail b
					ON a.id = b.idSchedule
					WHERE 
						a.`delete` = 0
						AND DATE(a.date) = '".$dateinput."'
						AND a.shift = ".$shift."
					";				
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {			
				$menu = select_where('m_menu', 'id', $key->id)->row();             
				$key->namaMenu = $menu->namaMenu;
				$stokAwal = $key->stok;
				
				$sqlOrder = "SELECT 
						*
					FROM 
						t_order_menu a					
					WHERE 
						a.`delete` = 0						
						AND DATE(a.date) = '".$dateinput."'
						AND a.shift = ".$shift."
						AND a.idMenu = ".$key->id."
					GROUP by 
						date, shift, username
					";				
				$getdataOder = $this->db->query($sqlOrder);			
				$countOrder = $getdataOder->num_rows();
				$sisaStok = $stokAwal - $countOrder;
			
				$datas[] = array('id' => $key->id, 'namaMenu' => $key->namaMenu, 'stok' => $sisaStok);				
			}			
		}
					
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'num_rows' => count($datas),
			'items' => $datas
		);
		
		header('Content-Type: application/json');
		$this->response($data);
	}

}
