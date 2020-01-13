<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

//use Restserver\Libraries\REST_Controller;

class ScheduleKantin extends REST_Controller
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
        if ($id != '') {
            $datas = select_where_array('t_schedule_kantin', array('id' => $id, 'delete' => '0'));
            if ($datas->num_rows() > 0) {
                $datas = $datas->row();         

                //$datas->startDate = date("d-m-Y", strtotime($datas->startDate));
				//$datas->endDate = date("d-m-Y", strtotime($datas->endDate));
                
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
            //$datas = select_all_where('t_schedule_kantin', 'delete', '0');
         	$datas = select_where_array('t_schedule_kantin', array('delete' => 0))->result();	
            
        	foreach ($datas as $key) {
                $key->startDate = date("d-m-Y", strtotime($key->startDate));
				$key->endDate = date("d-m-Y", strtotime($key->endDate));
				
				if ($key->vendor){
					$vendor = select_where('m_vendor', 'id', $key->vendor)->row();                  
					$key->vendor = $vendor->namaVendor;
				}
                
                $shift = select_where('m_shift', 'id', $key->shift)->row();                  
                $key->shift = $shift->namaShift;
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
	
	function addScheduleKantin_post()
    {
        $startDate = $this->post('startDate');
		$endDate = $this->post('endDate');
		$vendor = $this->post('vendor');
		$shift = $this->post('shift');
		$createdBy = $this->post('createdBy');
		$createdDate = $this->post('createdDate');
		
		
		$insert = insert_all('t_schedule_kantin', array(
			'startDate' => $startDate,            
			'endDate' => $endDate,
			'vendor' => $vendor,
			'shift' => $shift,            			           
			'createdBy' => $createdBy,
			'createdDate' => $createdDate,
		));
		if ($insert) {
			$data = array(
				'status' => 'success',
				'message' => 'success add Schedule Kantin',
			);
		} else {
			$data = array(
				'status' => 'failed',
				'message' => 'failed add Schedule Kantin',
			);
		}
		
		
        
        $this->response($data);
    }
	
	function updateScheduleKantin_post()
    {
		$idSchedule = $this->post('id');
        $startDate = $this->post('startDate');
		$endDate = $this->post('endDate');
		$vendor = $this->post('vendor');
		$shift = $this->post('shift');
		$updatedBy = $this->post('updatedBy');
		$updatedDate = $this->post('updatedDate');
		
        $getidscheduler = select_where('t_schedule_kantin', 'id', $idSchedule)->num_rows();
        
        if ($getidscheduler > 0) {
			
			//$getNamaVendor = select_where_array('t_schedule_kantin', array('id' => $id, 'namaVendor' => $namaVendor))->num_rows();
			//$getInitial = select_where_array('t_schedule_kantin', 'initial', $initial)->num_rows();
			
			$update = update('t_schedule_kantin', array(
				'startDate' => $startDate,            
				'endDate' => $endDate,
				'vendor' => $vendor,
				'shift' => $shift,				
				'updatedBy' => $updatedBy,
				'updatedDate' => $updatedDate
			), 'id', $idSchedule);
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
                'message' => 'Gagal! id schedule tidak terdaftar'
            );
        }
        
        $this->response($data);
    }
	
	function deleteScheduleKantin_post()
    {
        $idSchedule = $this->post('id');
        $updatedBy = $this->post('updatedBy');
		
        $getidscheduler = select_where('t_schedule_kantin', 'id', $idSchedule)->num_rows();
        
        if ($getidscheduler > 0) {
			$update['updatedBy'] = $updatedBy;
            $update['updatedDate'] = date("Y-m-d H:i:s");
            $update['delete'] = '1';
            $delete = $this->super_model->update_table('t_schedule_kantin', 'id', $idSchedule, $update);
			//$delete = $this->super_model->delete_table('t_schedule_kantin', 'id', $idSchedule);
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

}
