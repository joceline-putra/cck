<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        // $this->load->model('Satuan_model');
        // $this->load->model('Gudang_model');
        // $this->load->model('Golongan_obat_model');
        // $this->load->model('Diagnosa_model');
        // $this->load->model('Jenis_praktik_model');
        // $this->load->model('Aktivitas_model');                   
    } 

    function index()
    {
        $data['session'] = $this->session->userdata();
        $data['barang'] = $this->Barang_model->get_all_barang();
        $data['all_barang_kategori'] = $this->Barang_kategori_model->get_all_barang_kategori();
        $data['all_barang_stok'] = $this->Barang_stok_model->get_all_barang_stok();
        $data['all_satuan'] = $this->Satuan_model->get_all_satuan();
        $data['all_kemasan'] = $this->Kemasan_model->get_all_kemasan();
        
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        
        $data['title'] = 'Barang';
        $data['_view'] = 'barang/index';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('barang/js.php',$data);
    }

    function manage(){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $get_data=$this->Model_model->get_($id);
        foreach($get_data AS $v){
            $datas[]=array(
                'id' => $v['id'],
                'text1' => $v['text1'],
                'text2' => $v['text2'],
                'text3' => $v['text3'],
                'text4' => $v['text4'],
                'text5' => $v['text5']
            );
        }
        if(isset($datas)){ //Data exist
            $data_source=$datas; $total=count($datas);
            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
            $return->result=$datas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        echo json_encode($return);
        
    }                
}
