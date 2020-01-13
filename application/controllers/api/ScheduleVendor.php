<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

//use Restserver\Libraries\REST_Controller;

class ScheduleVendor extends REST_Controller
{

    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();        
        //$this->load->model('Model_schedule_kantin', 'schedule_kantin');
        
    }

    function index_get()
    {
        $id = $this->get('id');
		$username = $this->get('username');
		
        if ($id != '') {
            $datas = select_where_array('t_schedule_vendor', array('id' => $id, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $datas = $datas->row();         

                $datas->date = date("d-m-Y", strtotime($datas->date));
				
				$dataDetail = select_where_array('t_schedule_vendor_detail', array('idSchedule' => $datas->id, 'delete' => '0'))->result();
				if ($dataDetail) {
					foreach ($dataDetail as $key) {
						$key->idJenisMenu = $key->jenisMenu;
						
						$jenisMenu = select_where('m_jenis_menu', 'id', $key->jenisMenu)->row();                
						$key->jenisMenu = $jenisMenu->namaJenisMenu;
						
						$menu = select_where('m_menu', 'id', $key->idMenu)->row();                  
						$key->namaMenu = $menu->namaMenu;				
						
					}
				}
				
				$datas->detailMenuMakanan = $dataDetail;
				                
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
					$datas = select_where_array('t_schedule_vendor', array('delete' => '0'))->result();
				}else{
					$datas = select_where_array('t_schedule_vendor', array('vendor' => $dataUser->vendor, 'delete' => '0'))->result();	
				}
                
				foreach ($datas as $key) {
					$key->date = date("d-m-Y", strtotime($key->date));		

					$vendor = select_where('m_vendor', 'id', $key->vendor)->row();                  
					$key->namaVendor = $vendor->namaVendor;
					
					$shift = select_where('m_shift', 'id', $key->shift)->row();                  
					$key->shift = $shift->namaShift;
					$key->jadwal = $shift->namaShift;
					
					/*$menuUtama = select_where('m_menu', 'id', $key->menuUtama)->row();                  
					$key->menuUtama = $menuUtama->namaMenu;
					
					$menuTambahan = select_where('m_menu', 'id', $key->menuTambahan)->row();                  
					$key->menuTambahan = $menuTambahan->namaMenu;
					
					$sayur = select_where('m_menu', 'id', $key->sayur)->row();                  
					$key->sayur = $sayur->namaMenu;
					
					$buah = select_where('m_menu', 'id', $key->buah)->row();                  
					$key->buah = $buah->namaMenu;*/
							
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
            //$datas = select_all_where('t_schedule_vendor', 'delete', '0');
         	$datas = select_where_array('t_schedule_vendor', array('delete' => 0))->result();	
            
        	foreach ($datas as $key) {
                $key->date = date("d-m-Y", strtotime($key->date));		

				$vendor = select_where('m_vendor', 'id', $key->vendor)->row();                  
				$key->namaVendor = $vendor->namaVendor;
                
                $shift = select_where('m_shift', 'id', $key->shift)->row();                  
                $key->shift = $shift->namaShift;
				$key->jadwal = $shift->namaShift;
				
				/*$menuUtama = select_where('m_menu', 'id', $key->menuUtama)->row();                  
				$key->menuUtama = $menuUtama->namaMenu;
				
				$menuTambahan = select_where('m_menu', 'id', $key->menuTambahan)->row();                  
				$key->menuTambahan = $menuTambahan->namaMenu;
				
				$sayur = select_where('m_menu', 'id', $key->sayur)->row();                  
				$key->sayur = $sayur->namaMenu;
				
				$buah = select_where('m_menu', 'id', $key->buah)->row();                  
				$key->buah = $buah->namaMenu;*/
            			
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
	
	function detailMenuMakanan_get()
    {
        $idSchedule = $this->get('idSchedule');	
        
		$datas = select_where_array('t_schedule_vendor_detail', array('idSchedule' => $idSchedule, 'delete' => '0'))->result();
		
		if ($datas) {
			foreach ($datas as $key) {
				$key->idJenisMenu = $key->jenisMenu;
				
				$jenisMenu = select_where('m_jenis_menu', 'id', $key->jenisMenu)->row();                
				$key->jenisMenu = $jenisMenu->namaJenisMenu;
				
				$menu = select_where('m_menu', 'id', $key->idMenu)->row();                  
				$key->namaMenu = $menu->namaMenu;				
				
			}
		}			
            
        
		header('Content-Type: application/json');
        $this->response($datas);
    }
	
	function addScheduleVendor_post()
    {
		
		$param = json_decode(file_get_contents("php://input"), true);
				
        $date = $param['date'];
		$vendor = $param['vendor'];
		$shift = $param['shift'];		
		$createdBy = $param['createdBy']; 
		$createdDate = date("Y-m-d H:i:s");
		$detailMenuMakanan = $param['detailMenuMakanan'];
		
		$dateinput = date("Y-m-d", strtotime($date));
		
		$getData = select_where_array('t_schedule_vendor', array('DATE(date)' => $dateinput, 'shift' => $shift))->num_rows();
		//echo $getData; die;
		
		if ($getData > 0){
			$data = array(
                'status' => 'failed',
                'message' => 'Date & Shift already exists',
            );
		}else{
			$insert = insert_all('t_schedule_vendor', array(
				'date' => $dateinput,            			
				'vendor' => $vendor,
				'shift' => $shift,          				
				'createdBy' => $createdBy,
				'createdDate' => $createdDate,
			));
			if ($insert) {
				$idSchedule = $insert->id;
				foreach ($detailMenuMakanan as $key=>$val) {
					//echo $val["id"]." ".$val["menu"]." <br>"; 
					
					$jenisMenu = $val["idJenisMenu"];
					$idMenu = $val["idMenu"];
					$stok = $val["stok"];
					
					$insert = insert_all('t_schedule_vendor_detail', array(
						'idSchedule' => $idSchedule,
						'jenisMenu' => $jenisMenu,
						'idMenu' => $idMenu,
						'stok' => $stok,
						'createdBy' => $createdBy,
						'createdDate' => $createdDate
					));
					
				}
				
				$data = array(
					'status' => 'success',
					'message' => 'success add Schedule Vendor',
				);
			} else {
				$data = array(
					'status' => 'failed',
					'message' => 'failed add Schedule Vendor',
				);
			}
		}
		
		        
        $this->response($data);
    }
	
	function updateScheduleVendor_post()
    {
		$param = json_decode(file_get_contents("php://input"), true);
				
		$idSchedule = $param['id'];
        $date = $param['date'];
		$vendor = $param['vendor'];
		$shift = $param['shift'];			
		$detailMenuMakanan = $param['detailMenuMakanan'];	
		$createdBy = $param['createdBy']; 
		$createdDate = date("Y-m-d H:i:s");
		$dateinput = date("Y-m-d", strtotime($date));
		
        $getidscheduler = select_where('t_schedule_vendor', 'id', $idSchedule)->num_rows();
        
        if ($getidscheduler > 0) {
			
			$update = update('t_schedule_vendor', array(
				'date' => $dateinput,            				
				'vendor' => $vendor,
				'shift' => $shift,							
				'updatedBy' => $createdBy,
				'updatedDate' => $createdDate
			), 'id', $idSchedule);
			if ($update) {
				
				$getDataDetail = select_where('t_schedule_vendor_detail', 'idSchedule', $idSchedule)->num_rows();
				if ($getDataDetail > 0) {
					$delete = $this->super_model->delete_table('t_schedule_vendor_detail', 'idSchedule', $idSchedule);			
				}
				//$idSchedule = $insert->id;
				foreach ($detailMenuMakanan as $key=>$val) {
					$jenisMenu = $val["idJenisMenu"];
					$idMenu = $val["idMenu"];
					$stok = $val["stok"];
					
					$insert = insert_all('t_schedule_vendor_detail', array(
						'idSchedule' => $idSchedule,
						'jenisMenu' => $jenisMenu,
						'idMenu' => $idMenu,
						'stok' => $stok,
						'createdBy' => $createdBy,
						'createdDate' => $createdDate
					));
					
				}
				
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
                'message' => 'Gagal! id schedule tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteScheduleVendor_post()
    {
        $idSchedule = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidscheduler = select_where('t_schedule_vendor', 'id', $idSchedule)->num_rows();
        
        if ($getidscheduler > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('t_schedule_vendor', 'id', $idSchedule, $update);
			//$delete = $this->super_model->delete_table('t_schedule_vendor', 'id', $idSchedule);
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
                'message' => 'Gagal! id schedule tidak terdaftar'
            );
        }

        $this->response($data);
    }
	
	function getDate_get()
    {
		$idVendor = $this->get('idVendor');
		$shift = $this->get('shift');
		$idSchedule = $this->get('idSchedule');
		
		$dateNow = date('d-m-Y');
						
		$sql = "SELECT 
						id, startDate, endDate, timestampdiff(DAY,startDate,endDate) AS selisih
					FROM 
						t_schedule_kantin
					WHERE 
						`delete` = 0
						AND vendor = ".$idVendor."
						AND shift = ".$shift."
						AND DATE(endDate) >= date(NOW())";		
		$getdata = $this->db->query($sql);
		
		$datas = array();
		if ($getdata->num_rows() > 0) {				
			$data=$getdata->row();
			
			$sqlSchedVendor = "SELECT 
						date
					FROM 
						t_schedule_vendor
					WHERE 
						`delete` = 0
						AND vendor = ".$idVendor."
						AND shift = ".$shift."
						";		
			$getdataSchedVendor = $this->db->query($sqlSchedVendor);

			if ($getdataSchedVendor->num_rows() > 0) {
				$dates = array();
				foreach($getdataSchedVendor->result() as $key) {	
					$key->date = date("d-m-Y", strtotime($key->date));
					$dates[] = $key->date;
				}
			}else{
				$dates = "";
			}
			
			for ($i=0; $i<=$data->selisih; $i++){
				$startDate = $data->startDate;
				$startDateAdd = date('d-m-Y', strtotime($startDate . ' +'.$i.' day'));
				$selisih = datediff($startDateAdd, $dateNow);
				
							
				if (!$idSchedule){ //add
					if ($selisih['days_total'] >= 0){ // jika tanggal jadwal lebih besar dari tanggal hari ini
						if ($dates){
							if (!in_array($startDateAdd, $dates)) {
								$datas[] = array('date' => $startDateAdd);
							}
						}else{
							$datas[] = array('date' => $startDateAdd);
						}
					}					
				}else{ // edit (munculkan semua)
					if ($selisih['days_total'] >= 0){ // jika tanggal jadwal lebih besar dari tanggal hari ini
						$datas[] = array('date' => $startDateAdd);
					}					
				}
				
								
			}			
		}		
		
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'items' => $datas
		);
        
		header('Content-Type: application/json');
        $this->response($data);
    }
	
	function getShift_get()
    {
		$idVendor = $this->get('idVendor');
				
		//$datas = select_where_array('t_schedule_kantin', array('vendor' => $idVendor, 'delete' => '0'))->result();	
		$sql = "SELECT 
						id, shift
					FROM 
						t_schedule_kantin
					WHERE 
						`delete` = 0
						AND vendor = ".$idVendor."						
						AND DATE(endDate) >= date(NOW())";		
		$getdata = $this->db->query($sql);
		
		$datas = array();
		if ($getdata->num_rows() > 0) {				
			foreach($getdata->result() as $key) {					
				$datas[] = array('shift' => $key->shift);					
			}
			
		}
		
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'items' => $datas
		);
		
        header('Content-Type: application/json');
        $this->response($data);
    }
	
	function getVendorByDate_get()
    {        
		$shift = $this->get('shift');
		$date = $this->get('date');
		$dateinput = date("Y-m-d", strtotime($date));

		$datas = select_where_array('t_schedule_vendor', array('delete' => 0, 'DATE(date)' => $dateinput, 'shift' => $shift));

		if ($datas->num_rows() > 0) {
            $datas = $datas->row();		
			
			$datas->date = date("d-m-Y", strtotime($datas->date));		

			$vendor = select_where('m_vendor', 'id', $datas->vendor)->row();                  
			$datas->namaVendor = $vendor->namaVendor;
			$datas->pic = $vendor->pic;
			$datas->telepon = $vendor->telepon;
			$datas->teleponPic = $vendor->teleponPic;
			
			$shift = select_where('m_shift', 'id', $datas->shift)->row();                  
			$datas->shift = $shift->namaShift;
			$datas->jadwal = $shift->namaShift;
			
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
		
        $this->response($data);
    }
	
	function getMenuUtama_get()
    {
		$idVendor = $this->get('idVendor');
				
		$datas = select_where_array('m_menu', array('idVendor' => $idVendor, 'delete' => '0', 'jenisMenu' => '1'))->result();	
				
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'items' => $datas
		);
        
		header('Content-Type: application/json');
        $this->response($data);
    }
	
	function getMenuTambahan_get()
    {
		$idVendor = $this->get('idVendor');
				
		$datas = select_where_array('m_menu', array('idVendor' => $idVendor, 'delete' => '0', 'jenisMenu' => '4'))->result();	
				
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'items' => $datas
		);
        
		header('Content-Type: application/json');
        $this->response($data);
    }
	
	function getSayur_get()
    {
		$idVendor = $this->get('idVendor');
				
		$datas = select_where_array('m_menu', array('idVendor' => $idVendor, 'delete' => '0', 'jenisMenu' => '3'))->result();	
				
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'items' => $datas
		);
        
		header('Content-Type: application/json');
        $this->response($data);
    }
	
	function getBuah_get()
    {
		$idVendor = $this->get('idVendor');
				
		$datas = select_where_array('m_menu', array('idVendor' => $idVendor, 'delete' => '0', 'jenisMenu' => '2'))->result();	
				
		$data = array(
			'status' => 'success',
			'message' => 'success fetch data',
			'items' => $datas
		);
        
		header('Content-Type: application/json');
        $this->response($data);
    }
	
	

}
