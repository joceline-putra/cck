<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        // $this->load->helper('date');
        // $this->load->helper('cookie'); 
        
        // $this->load->library('email');
        // $this->load->library('phpmailer_lib');

        // $this->load->config('email');
        $this->load->config('whatsapp');  
    }
    function index(){
        echo 11;die;
    }
    function update_booking_expired_day(){
        $prepare = "CALL sp_cronjob_booking_expired_day()";
        $query=$this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        // log_message('debug',$prepare);
        echo json_encode($result);
    }
}