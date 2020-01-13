<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

//use Restserver\Libraries\REST_Controller;

class Report extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_report', 'report');
		
		$this->path = 'assets/uploads/menu/';    
        
    }
	
	function getReportAll_get()
    {		
		//$shift = $this->get('shift');
		$getStartDate = $this->get('startDate');
		$getEndDate = $this->get('endDate');
		$startDate = date("Y-m-d", strtotime($getStartDate));
		$endDate = date("Y-m-d", strtotime($getEndDate));
		
		if (!empty($getStartDate) || !empty($getEndDate)){
			$sql = "SELECT 
						a.*, u.roleUser, u.nip, u.namaLengkap
						FROM 
							t_order_menu a
						INNER JOIN m_user u
							on a.username = u.username
						WHERE 
							a.`delete` = 0							
							AND DATE(a.date) >= '".$startDate."'
							AND DATE(a.date) <= '".$endDate."'											
						GROUP by 
							date, shift, username						
						";	
		}else{
			$sql = "SELECT 
						a.*, u.roleUser, u.nip, u.namaLengkap
						FROM 
							t_order_menu a
						INNER JOIN m_user u
							on a.username = u.username
						WHERE 
							a.`delete` = 0					
						GROUP by 
							date, shift, username						
						";	
		}		
					
		$getdata = $this->db->query($sql);
		//echo $sql; die;
		$datas = array();
        if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {	
			
				$menu = select_where('m_menu', 'id', $key->idMenu)->row();             
				//$key->namaMenu = $menu->namaMenu;
			
				$vendor = select_where('m_vendor', 'id', $menu->idVendor)->row();                  
				$key->namaVendor = $vendor->namaVendor;
								
				$role = select_where('m_role', 'id', $key->roleUser)->row();                  
                $key->namaRole = $role->namaRole;			
				
				//get Menu Utama
				$sqlMenuUtama = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 1
						";								
				$getMenuUtama = $this->db->query($sqlMenuUtama);
				
				if ($getMenuUtama->num_rows() > 0) {		
					$menuUtama = $getMenuUtama->row();
					$menuUtamaView = $menuUtama->namaMenu;
					$kaloriMenuUtama = $menuUtama->infoKalori;
				}else{
					$menuUtamaView = "-";
					$kaloriMenuUtama = 0;
				}
				
				//get Buah
				$sqlBuah = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 2
						";								
				$getBuah = $this->db->query($sqlBuah);
				
				if ($getBuah->num_rows() > 0) {		
					$buah = $getBuah->row();
					$buahView = $buah->namaMenu;
					$kaloriBuah = $buah->infoKalori;
				}else{
					$buahView = "-";
					$kaloriBuah = 0;
				}
				
				//get Sayur
				$sqlSayur = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 3
						";								
				$getSayur = $this->db->query($sqlSayur);
				
				if ($getSayur->num_rows() > 0) {		
					$sayur = $getSayur->row();
					$sayurView = $sayur->namaMenu;
					$kaloriSayur = $sayur->infoKalori;
				}else{
					$sayurView = "-";
					$kaloriSayur = 0;
				}
				
				//get Menu Tambahan
				$sqlMenuTambahan = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 4
						";								
				$getMenuTambahan = $this->db->query($sqlMenuTambahan);
				
				if ($getMenuTambahan->num_rows() > 0) {		
					$menuTambahan = $getMenuTambahan->row();
					$menuTambahanView = $menuTambahan->namaMenu;
					$kaloriMenuTambahan = $menuTambahan->infoKalori;
				}else{
					$menuTambahanView = "-";
					$kaloriMenuTambahan = 0;
				}
				
				$totalKalori = $kaloriMenuUtama + $kaloriBuah + $kaloriSayur + $kaloriMenuTambahan;
			
				$datas[] = array('date' => $key->date = date("d-m-Y", strtotime($key->date)), 
								 'status' => $key->namaRole, 
								 'namaLengkap' => $key->namaLengkap, 
								 'vendor' => $key->namaVendor, 
								 'menuUtama' => $menuUtamaView,
								 'menuTambahan' => $menuTambahanView,
								 'sayur' => $sayurView,
								 'buah' => $buahView,
								 'totalKalori' => $totalKalori
								);				
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
	
	function getReportCountingVendor_get()
    {		
		$idVendor = $this->get('idVendor');
		$getStartDate = $this->get('startDate');
		$getEndDate = $this->get('endDate');
		$startDate = date("Y-m-d", strtotime($getStartDate));
		$endDate = date("Y-m-d", strtotime($getEndDate));
		
		if (!empty($idVendor)){			
			$filterIdVendor = " AND a.vendor = ".$idVendor." ";
		}else{
			$filterIdVendor = "";
		}
		
		if (!empty($getStartDate) || !empty($getEndDate)){			
			$filterDate = " AND DATE(a.date) >= '".$startDate."'
							AND DATE(a.date) <= '".$endDate."'";
		}else{
			$filterDate = "";
		}
		
		$sql = "SELECT 
				*
				FROM 
					t_schedule_vendor a				
				WHERE 
					a.`delete` = 0".$filterIdVendor.$filterDate;		
		
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {			
						
				$vendor = select_where('m_vendor', 'id', $key->vendor)->row();                  
				$key->namaVendor = $vendor->namaVendor;
				
				//get Menu Utama
				$sqlMenuUtama = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_schedule_vendor_detail a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0                            
                            AND a.idSchedule = ".$key->id."
							AND u.jenisMenu = 1
						";	
				
				$getMenuUtama = $this->db->query($sqlMenuUtama);
				
				if ($getMenuUtama->num_rows() > 0) {		
					$menuUtama = $getMenuUtama->row();
					$menuUtamaView = $menuUtama->namaMenu;					
				}else{
					$menuUtamaView = "-";					
				}
				
				//get Buah
				$sqlBuah = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_schedule_vendor_detail a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0                            
                            AND a.idSchedule = ".$key->id."
							AND u.jenisMenu = 2
						";								
				$getBuah = $this->db->query($sqlBuah);
				
				if ($getBuah->num_rows() > 0) {		
					$buah = $getBuah->row();
					$buahView = $buah->namaMenu;					
				}else{
					$buahView = "-";					
				}
				
				//get Sayur
				$sqlSayur = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_schedule_vendor_detail a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0                            
                            AND a.idSchedule = ".$key->id."
							AND u.jenisMenu = 3
						";								
				$getSayur = $this->db->query($sqlSayur);
				
				if ($getSayur->num_rows() > 0) {		
					$sayur = $getSayur->row();
					$sayurView = $sayur->namaMenu;					
				}else{
					$sayurView = "-";					
				}
				
				//get Menu Tambahan
				$sqlMenuTambahan = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_schedule_vendor_detail a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0                            
                            AND a.idSchedule = ".$key->id."
							AND u.jenisMenu = 4
						";								
				$getMenuTambahan = $this->db->query($sqlMenuTambahan);
				
				if ($getMenuTambahan->num_rows() > 0) {		
					$menuTambahan = $getMenuTambahan->row();
					$menuTambahanView = $menuTambahan->namaMenu;					
				}else{
					$menuTambahanView = "-";					
				}
				
				//$totalKalori = $kaloriMenuUtama + $kaloriBuah + $kaloriSayur + $kaloriMenuTambahan;
			
				$datas[] = array('date' => $key->date = date("d-m-Y", strtotime($key->date)), 
								 'vendor' => $key->namaVendor, 
								 'menuUtama' => $menuUtamaView,
								 'menuTambahan' => $menuTambahanView,
								 'sayur' => $sayurView,
								 'buah' => $buahView,
								 //'totalKalori' => $totalKalori
								);				
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
	
	function reportCountingEmployee_get()
    {		
		$idRole = $this->get('idRole');
		$nama = $this->get('nama');
		$getStartDate = $this->get('startDate');
		$getEndDate = $this->get('endDate');
		$startDate = date("Y-m-d", strtotime($getStartDate));
		$endDate = date("Y-m-d", strtotime($getEndDate));
		
		if (!empty($idRole)){			
			$filterIdRole = " AND u.roleUser = ".$idRole." ";
		}else{
			$filterIdRole = "";
		}
		
		if (!empty($nama)){			
			$filterNama = " AND u.namaLengkap like '%".$nama."%' ";
		}else{
			$filterNama = "";
		}
		
		if (!empty($getStartDate) || !empty($getEndDate)){			
			$filterDate = " AND DATE(a.date) >= '".$startDate."'
							AND DATE(a.date) <= '".$endDate."'";
		}else{
			$filterDate = "";
		}
		
		$sql = "SELECT 
					a.*, u.roleUser, u.nip, u.namaLengkap
				FROM 
					t_order_menu a
				INNER JOIN m_user u
					on a.username = u.username
				WHERE 
					a.`delete` = 0".$filterIdRole.$filterNama.$filterDate."					
				GROUP by 
					date, shift, username						
				";			
		//echo $sql; die;			
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {	
			
				$menu = select_where('m_menu', 'id', $key->idMenu)->row();             
				//$key->namaMenu = $menu->namaMenu;
			
				//$vendor = select_where('m_vendor', 'id', $menu->idVendor)->row();                  
				//$key->namaVendor = $vendor->namaVendor;
								
				$role = select_where('m_role', 'id', $key->roleUser)->row();                  
                $key->namaRole = $role->namaRole;			
				
				//get Menu Utama
				$sqlMenuUtama = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 1
						";								
				$getMenuUtama = $this->db->query($sqlMenuUtama);
				
				if ($getMenuUtama->num_rows() > 0) {		
					$menuUtama = $getMenuUtama->row();
					$menuUtamaView = $menuUtama->namaMenu;
					$kaloriMenuUtama = $menuUtama->infoKalori;
				}else{
					$menuUtamaView = "-";
					$kaloriMenuUtama = 0;
				}
				
				//get Buah
				$sqlBuah = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 2
						";								
				$getBuah = $this->db->query($sqlBuah);
				
				if ($getBuah->num_rows() > 0) {		
					$buah = $getBuah->row();
					$buahView = $buah->namaMenu;
					$kaloriBuah = $buah->infoKalori;
				}else{
					$buahView = "-";
					$kaloriBuah = 0;
				}
				
				//get Sayur
				$sqlSayur = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 3
						";								
				$getSayur = $this->db->query($sqlSayur);
				
				if ($getSayur->num_rows() > 0) {		
					$sayur = $getSayur->row();
					$sayurView = $sayur->namaMenu;
					$kaloriSayur = $sayur->infoKalori;
				}else{
					$sayurView = "-";
					$kaloriSayur = 0;
				}
				
				//get Menu Tambahan
				$sqlMenuTambahan = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 4
						";								
				$getMenuTambahan = $this->db->query($sqlMenuTambahan);
				
				if ($getMenuTambahan->num_rows() > 0) {		
					$menuTambahan = $getMenuTambahan->row();
					$menuTambahanView = $menuTambahan->namaMenu;
					$kaloriMenuTambahan = $menuTambahan->infoKalori;
				}else{
					$menuTambahanView = "-";
					$kaloriMenuTambahan = 0;
				}
				
				$totalKalori = $kaloriMenuUtama + $kaloriBuah + $kaloriSayur + $kaloriMenuTambahan;
			
				$datas[] = array('date' => $key->date = date("d-m-Y", strtotime($key->date)), 
								 'status' => $key->namaRole, 
								 'namaLengkap' => $key->namaLengkap, 								 
								 'menuUtama' => $menuUtamaView,
								 'menuTambahan' => $menuTambahanView,
								 'sayur' => $sayurView,
								 'buah' => $buahView,
								 'totalKalori' => $totalKalori
								);				
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
	
	function reportTotalCounting_get()
    {		
		$idRole = $this->get('idRole');		
		$getStartDate = $this->get('startDate');
		$getEndDate = $this->get('endDate');
		$startDate = date("Y-m-d", strtotime($getStartDate));
		$endDate = date("Y-m-d", strtotime($getEndDate));
		
		if (!empty($idRole)){			
			$filterIdRole = " AND u.roleUser = ".$idRole." ";
		}else{
			$filterIdRole = "";
		}
		
		if (!empty($getStartDate) || !empty($getEndDate)){			
			$filterDate = " AND DATE(a.date) >= '".$startDate."'
							AND DATE(a.date) <= '".$endDate."'";
		}else{
			$filterDate = "";
		}
		
		$sql = "SELECT 
					a.*, u.roleUser, u.nip, u.namaLengkap
				FROM 
					t_order_menu a
				INNER JOIN m_user u
					on a.username = u.username
				WHERE 
					a.`delete` = 0".$filterIdRole.$filterDate."					
				GROUP by 
					date, shift, username						
				";			
		//echo $sql; die;			
		$getdata = $this->db->query($sql);
		
		$datas = array();
        if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {	
			
				$menu = select_where('m_menu', 'id', $key->idMenu)->row();             
				//$key->namaMenu = $menu->namaMenu;
			
				$vendor = select_where('m_vendor', 'id', $menu->idVendor)->row();                  
				$key->namaVendor = $vendor->namaVendor;
								
				$role = select_where('m_role', 'id', $key->roleUser)->row();                  
                $key->namaRole = $role->namaRole;			
				
				//get Menu Utama
				$sqlMenuUtama = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 1
						";								
				$getMenuUtama = $this->db->query($sqlMenuUtama);
				
				if ($getMenuUtama->num_rows() > 0) {		
					$menuUtama = $getMenuUtama->row();
					$menuUtamaView = $menuUtama->namaMenu;
					$kaloriMenuUtama = $menuUtama->infoKalori;
				}else{
					$menuUtamaView = "-";
					$kaloriMenuUtama = 0;
				}
				
				//get Buah
				$sqlBuah = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 2
						";								
				$getBuah = $this->db->query($sqlBuah);
				
				if ($getBuah->num_rows() > 0) {		
					$buah = $getBuah->row();
					$buahView = $buah->namaMenu;
					$kaloriBuah = $buah->infoKalori;
				}else{
					$buahView = "-";
					$kaloriBuah = 0;
				}
				
				//get Sayur
				$sqlSayur = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 3
						";								
				$getSayur = $this->db->query($sqlSayur);
				
				if ($getSayur->num_rows() > 0) {		
					$sayur = $getSayur->row();
					$sayurView = $sayur->namaMenu;
					$kaloriSayur = $sayur->infoKalori;
				}else{
					$sayurView = "-";
					$kaloriSayur = 0;
				}
				
				//get Menu Tambahan
				$sqlMenuTambahan = "SELECT 
						a.*, u.jenisMenu, u.namaMenu, u.infoKalori
						FROM 
							t_order_menu a
						INNER JOIN m_menu u
							on a.idMenu = u.id
						WHERE 
							a.`delete` = 0
                            AND DATE(a.date) = '".$key->date."'
                            AND a.username = '".$key->username."'
                            AND a.shift = ".$key->shift."
							AND u.jenisMenu = 4
						";								
				$getMenuTambahan = $this->db->query($sqlMenuTambahan);
				
				if ($getMenuTambahan->num_rows() > 0) {		
					$menuTambahan = $getMenuTambahan->row();
					$menuTambahanView = $menuTambahan->namaMenu;
					$kaloriMenuTambahan = $menuTambahan->infoKalori;
				}else{
					$menuTambahanView = "-";
					$kaloriMenuTambahan = 0;
				}
				
				$jumlahKedatangan = 0;
			
				$datas[] = array('date' => $key->date = date("d-m-Y", strtotime($key->date)), 
								 'status' => $key->namaRole, 
								 'vendor' => $key->namaVendor,								 
								 'jumlahKedatangan' => $jumlahKedatangan
								);				
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
