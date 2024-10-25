<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->config('whatsapp');  
        $this->load->model('File_model');
    }
    function index(){
        echo 11;die;
    }
    function update_booking_expired_day_and_time(){
        $prepare = "CALL sp_cronjob_booking_expired_day_and_time()";
        $query=$this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        // log_message('debug',$prepare);
        echo json_encode($result);
    }
    function remove_checkout_files(){
        $ed     = new DateTime();    
        $ed     = $ed->sub(new DateInterval('P7D'));
        $e      = $ed->format('Y-m-d 23:59:59');        
        
        $p = [
            'file_from_table' => 'orders-checkouts',
            // 'file_date_created >' => $s,
            'file_date_created <' => $e
        ];
        // var_dump($p);die;
        // $get_count = $this->File_model->get_all_file_count($p,null);
        $get = $this->File_model->get_all_file($p,null,null,null,'file_id','desc');
    // $file = FCPATH . $get_data['paid_url'];
    // if (file_exists($file)) {
    //     unlink($file);
    // }        
        $total_delete = 0; $total_delete_not = 0;
        $delete_id = []; $delete_not_id = [];
        foreach($get as $v){

            if(!empty($v['file_url'])){
                $file = FCPATH . $v['file_url'];
                if(file_exists($file)){
                    unlink($file);
                    $this->File_model->delete_file($v['file_id']);
                    // echo 'Exists => '.'<a href="https://app.cecekost.com/'.$v['file_url'].'" target="_blank">'.$v['file_url'].'</a> '.$v['file_date_created'].'<br>'; 
                    array_push($delete_id,$v['file_id']);
                    $total_delete++;      
                }else{
                    // echo 'Not Exists => '.'<a href="https://app.cecekost.com/'.$v['file_url'].'" target="_blank">'.$v['file_url'].'</a><br>';
                    $this->File_model->delete_file($v['file_id']);
                    $total_delete_not++;     
                    array_push($delete_not_id,$v['file_id']);                    
                }
            }else{
                $this->File_model->delete_file($v['file_id']);
                $total_delete_not++;     
                array_push($delete_not_id,$v['file_id']); 
            }
        }
        $ret = [
            'status' => 200,
            'message' => 'Ok',
            'file_exist' => $total_delete,
            'file_not_exist' => $total_delete_not,            
            'delete_id' => $delete_id,
            'delete_not_id' => $delete_not_id
        ];
        echo json_encode($ret);
    }
}