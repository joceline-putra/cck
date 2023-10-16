<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Prints extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        /*
        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }           
        $this->load->library('form_validation');                  
        $this->load->helper('form');   
        */        
        $this->print_directory = 'layouts/admin/menu/prints/';        
        $this->load->model('Aktivitas_model');
        $this->load->model('Branch_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Journal_model');        
    }

    function index(){
        $data['session'] = $this->session->userdata();  

        // Date Now
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('Y-m-d');        
            $datenow =date("d-m-Y");         
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = $datenow;

        /*
        // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
        */

        $data['title'] = 'Your Title Here';
        $data['_view'] = 'print/index';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('print/js.php',$data);        
    }
    function pages($transaksi,$nomor){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        $format='';
        $tipe='';

        // if($format==1){ $format='html';}elseif($format==2){ $format='xls';}
        // if($tipe==1){ $tipe='rekap';}elseif($tipe==2){ $tipe='rinci';}              
       
        $return->request= array(
            'transaksi' => $transaksi,
            'format' => $format,
            'tipe' => $tipe,
            'nomor' => $nomor
        );
        $data = json_encode($return);   
        echo json_encode($return);
        // $this->load->view('prints/stok_opname',$data);
        // $this->load->view('prints/js.php',$data);               
    }     
    function manage($transaksi,$format,$tipe,$start,$end){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        if($format==1){ $format='html';}elseif($format==2){ $format='xls';}
        if($tipe==1){ $tipe='rekap';}elseif($tipe==2){ $tipe='rinci';}              
        // if($this->input->post('action')){
        //     $action = $this->input->post('action');
        //     if($action=='create'){

        //         $post_data = $this->input->post('data');
        //         // $data = base64_decode($post_data);
        //         $data = json_decode($post_data, TRUE);
        //         $params = array(
        //             'tipe' => $data['tipe'],
        //             'kode' => $data['kode'],
        //             'nama' => $data['nama'],
        //             'date_created' => date("YmdHis"),
        //             'date_updated' => date("YmdHis"),
        //             'flag' => $data['status']
        //         );

        //         //Check Data Exist
        //         $params_check = array(
        //             'kode' => $data['kode']
        //         );
        //         $check_exists = $this->Prints_model->check_data_exist($params_check);
        //         if($check_exists==false){

        //             $set_data=$this->Prints_model->add_print($params);
        //             if($set_data==true){
        //                 /* Start Activity */
        //                 /*
        //                 $params = array(
        //                     'id_user' => $session['user_data']['user_id'],
        //                     'action' => 2,
        //                     'table' => 'print',
        //                     'table_id' => $set_data,                            
        //                     'text_1' => strtoupper($data['kode']),
        //                     'text_2' => ucwords(strtolower($data['nama'])),                        
        //                     'date_created' => date('YmdHis'),
        //                     'flag' => 1
        //                 );
        //                 $this->save_activity($params);    
        //                 */
        //                 /* End Activity */            
        //                 $return->status=1;
        //                 $return->message='Success';
        //                 $return->result= array(
        //                     'id' => $set_data,
        //                     'kode' => $data['kode']
        //                 ); 
        //             }
        //         }else{
        //             $return->message='Kode sudah digunakan';                    
        //         }
        //     }
        //     if($action=='read'){
        //         // $post_data = $this->input->post('data');
        //         // $data = json_decode($post_data, TRUE);     
        //         $data['id'] = $this->input->post('id');           
        //         $datas = $this->Prints_model->get_print($data['id']);
        //         if($datas==true){
        //             /* Activity */
        //             /*
        //             $params = array(
        //                 'id_user' => $session['user_data']['user_id'],
        //                 'action' => 3,
        //                 'table' => 'print',
        //                 'table_id' => $datas['id'],
        //                 'text_1' => strtoupper($datas['kode']),
        //                 'text_2' => ucwords(strtolower($datas['username'])),
        //                 'date_created' => date('YmdHis'),
        //                 'flag' => 0
        //             );
        //             $this->save_activity($params);                    
        //             /* End Activity */
        //             $return->status=1;
        //             $return->message='Success';
        //             $return->result=$datas;
        //         }                
        //     }
        //     if($action=='update'){
        //         $post_data = $this->input->post('data');
        //         $data = json_decode($post_data, TRUE);
        //         $id = $data['id'];
        //         $params = array(
        //             'tipe' => $data['tipe'],
        //             'kode' => $data['kode'],
        //             'nama' => $data['nama'],
        //             'date_updated' => date("YmdHis"),
        //             'flag' => $data['status']
        //         );

        //         /*
        //         if(!empty($data['password'])){
        //             $params['password'] = md5($data['password']);
        //         }
        //         */
               
        //         $set_update=$this->Prints_model->update_print($id,$params);
        //         if($set_update==true){
        //             /* Activity */
        //             /*
        //             $params = array(
        //                 'id_user' => $session['user_data']['user_id'],
        //                 'action' => 4,
        //                 'table' => 'print',
        //                 'table_id' => ,
        //                 'text_1' => strtoupper($data['kode']),
        //                 'text_2' => ucwords(strtolower($data['nama'])),
        //                 'date_created' => date('YmdHis'),
        //                 'flag' => 0
        //             );
        //             */
        //             $this->save_activity($params);
        //             /* End Activity */
        //             $return->status=1;
        //             $return->message='Success';
        //         }                
        //     }
        //     if($action=='delete'){
        //     }                
        //     if($action=='set-active'){
        //         $id = $this->input->post('id');
        //         $kode = $this->input->post('kode');        
        //         $nama = $this->input->post('nama');                                
        //         $flag = $this->input->post('flag');

        //         if($flag==1){
        //             $msg='aktifkan print '.$nama;
        //             $act=7;
        //         }else{
        //             $msg='nonaktifkan print '.$nama;
        //             $act=8;
        //         }

        //         $set_data=$this->Prints_model->update_print($id,array('flag'=>$flag));
        //         if($set_data==true){    
        //             /* Activity */
        //             /*
        //             $params = array(
        //                 'id_user' => $session['user_data']['user_id'],
        //                 'action' => $act,
        //                 'table' => 'print',
        //                 'table_id' => $id,
        //                 'text_1' => strtoupper($kode),
        //                 'text_2' => ucwords(strtolower($nama)),
        //                 'date_created' => date('YmdHis'),
        //                 'flag' => 0
        //             );
        //             */
        //             $this->save_activity($params);                               
        //             /* End Activity */
        //             $return->status=1;
        //             $return->message='Berhasil '.$msg;
        //         }                
        //     }             
        //     if($action=='load'){
        //         $columns = array(
        //             '0' => 'kode',
        //             '1' => 'tipe',
        //             '2' => 'nama'
        //         );
        //         $limit = $this->input->post('length');
        //         $start = $this->input->post('start');
        //         $order = $columns[$this->input->post('order')[0]['column']];
        //         $dir = $this->input->post('order')[0]['dir'];

        //         $search = [];
        //         if ($this->input->post('search')['value']) {
        //             $s = $this->input->post('search')['value'];
        //             foreach ($columns as $v) {
        //                 $search[$v] = $s;
        //             }
        //         }

        //         $params = array(
        //             // 'flag > ' => 0
        //         );

        //         /*
        //         if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
        //             $params['other_column'] = $this->input->post('other_column');
        //         }
        //         */
                
        //         $datas = $this->Prints_model->get_all_prints($params, $search, $limit, $start, $order, $dir);
        //         $datas_count = $this->Prints_model->get_all_prints_count($params, $search, $limit, $start, $order, $dir);                
        //         if(isset($datas)){ //Data exist
        //             $data_source=$datas; $total=$datas_count;
        //             $return->status=1; $return->message='Loaded'; $return->total_records=$total;
        //             $return->result=$datas;        
        //         }else{ 
        //             $data_source=0; $total=0; 
        //             $return->status=0; $return->message='No data'; $return->total_records=$total;
        //             $return->result=0;    
        //         }
        //         $return->recordsTotal = $total;
        //         $return->recordsFiltered = $total;
        //     }
        // }
        $return->request= array(
            'transaksi' => $transaksi,
            'format' => $format,
            'tipe' => $tipe,
            'start' => $start,
            'end' => $end
        );
        $data = json_encode($return);   
        echo json_encode($return);
        // $this->load->view('prints/stok_opname',$data);
        // $this->load->view('prints/js.php',$data);               
    }    
    function print_qrcode($code){
        //Header
        $where = array(
            'trans_session' => $code
        );
        $data['header'] = $this->Transaksi_model->get_transaksi_custom($where);
        // var_dump($data['header']);die;
        $data['branch'] = $this->Branch_model->get_branch($data['header']['user_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }
        $data['journal_item'] = array();

        // Transfer Stok Dokumen
        $data['location'] = array();
        if($data['header']['trans_type'] == 5){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);            
            $data['location'] = array(
                'location_from' => $location_from,
                'location_to' => $location_to
            );
        }else if(($data['header']['trans_type'] == 6) or ($data['header']['trans_type'] == 7)){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);            
            $data['location'] = array(
                'location_from' => $location_from,
                // 'location_to' => $location_to
            );
        }else if($data['header']['trans_type'] == 8){
            $params_journal = array(
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);            
            $data['journal_item'] = $journal_item;
        }        

        //Content
        $params = array(
            'trans_item_trans_id' => $data['header']['trans_id']
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'trans_item_date_created';
        $dir        = 'ASC';

        $data['content'] = $this->Transaksi_model->get_all_transaksi_items($params,$search,$limit,$start,$order,$dir);

        $params_journal = array(
            'journal_item_trans_id' => $data['header']['trans_id']
        );
        $data_journal = array();
        $data['journal_item'] = $this->Journal_model->get_all_journal_item($params_journal,$search,$limit,$start,'journal_item_id',$dir);
        if(count($data['journal_item']) > 0){
            foreach($data['journal_item'] as $v):
                $data_journal[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session'=>$v['journal_item_group_session'], 
                        'journal_item_trans_id ' => NULL
                    ),$search,$limit,$start,'journal_item_id',$dir)
                );
            endforeach;
        }

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'location' => $data['location'],
            'content' => $data['content'],
            'journal' => $data_journal,
            'footer' => ''
        );

        // echo json_encode($data['result']);die;

        //Set Layout From Order Type
        if($data['header']['trans_type']==1){
            $data['title'] = 'Pembelian';
            $this->load->view($this->print_directory.'purchase_buy_with_payment_history',$data);
        }   
        else if($data['header']['trans_type']==2){
            $data['title'] = 'Penjualan';
            $this->load->view($this->print_directory.'sales_sell',$data);
        }    
        // else if($data['header']['trans_type']==3){
        //     $data['title'] = 'Price Quotation';
        //     $this->load->view($this->print_directory.'purchase_quotation',$data);
        // }                   
        // else if($data['header']['trans_type']==4){
        //     $data['title'] = 'Checkup Medicine';
        //     $this->load->view($this->print_directory.'checkup_medicine',$data);
        // }         
        // else if($data['header']['trans_type']==5){
        //     $data['title'] = 'Checkup Laboratory';
        //     $this->load->view($this->print_directory.'checkup_laboratory',$data);
        // }   
        else if($data['header']['trans_type']==5){
            $data['title'] = 'Transfer Stok';
            $this->load->view($this->print_directory.'inventory_transfer_stock',$data);
        }   
        else if($data['header']['trans_type']==6){
            $data['title'] = 'Stok Opname +';
            $this->load->view($this->print_directory.'inventory_stock_opname_plus',$data);
        }   
        else if($data['header']['trans_type']==7){
            $data['title'] = 'Stok Opname -';
            $this->load->view($this->print_directory.'inventory_stock_opname_minus',$data);
        }                   
        else if($data['header']['trans_type']==8){
            $data['title'] = 'Produksi';
            $this->load->view($this->print_directory.'production_production',$data);
        }                                          
        else{
            // $this->load->view('prints/sales_order',$data);
        }                
    }     
}

?>