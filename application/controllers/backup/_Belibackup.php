<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Belibackup extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('Aktivitas_model');
        $this->load->model('User_model');           
        $this->load->model('Produk_model');                   
        $this->load->model('Satuan_model');
        $this->load->model('Referensi_model');          
        $this->load->model('Transaksi_model');
    }

    function index(){
        $data['identity'] = 1;
        $data['session'] = $this->session->userdata();
        $data['usernya'] = $this->User_model->get_all_user();
        // var_dump($data['usernya']);die;        
        
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
                
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        //Date Now
        $datenow =date("d-m-Y");         
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        $data['title'] = 'Pembelian';
        $data['_view'] = 'beli/beli';
        $this->load->view('layouts/index',$data);
        $this->load->view('beli/beli_js.php',$data);        
    }
    function manage(){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';      
        if($this->input->post('action')){
            $action = $this->input->post('action');
            $post_data = $this->input->post('data');
            $data = json_decode($post_data, TRUE);            
            $identity = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];

            //Transaksi Tipe
            if($identity == 1){
                $set_tipe = 1;
                $params = array(
                    'tipe' => $data['tipe'],
                    'kode' => $data['kode'],
                    'nama' => $data['nama'],
                    'keterangan' => $data['keterangan'],                    
                    'harga_beli' => $data['harga_beli'],
                    'harga_jual' => $data['harga_jual'],
                    'stok_minimal' => $data['stok_minimal'],
                    'stok_maksimal' => $data['stok_maksimal'],  
                    'date_created' => date("YmdHis"),
                    'date_updated' => date("YmdHis"),
                    'satuan' => $data['satuan'],
                    'flag' => $data['status']
                );               
                $columns = array(
                    '0' => 'kode',
                    '1' => 'nama',
                    '2' => 'satuan',
                    '3' => 'harga_jual'
                );                   
            }                                                   
            if($identity == 6){ // Stok Opname Plus
                $set_tipe = 6;
                $params = array(
                    'tipe' => $data['tipe'],
                    'kode' => $data['kode'],
                    'nama' => $data['nama'],
                    'keterangan' => $data['keterangan'], 
                    'date_created' => date("YmdHis"),
                    'date_updated' => date("YmdHis"),
                    'flag' => $data['status']
                );    
                $params_update = array(
                    'kode' => $data['kode'],
                    'nama' => $data['nama'],
                    'keterangan' => $data['keterangan'],
                    'date_updated' => date("YmdHis"),
                    'flag' => $data['status']
                );                           
                $columns = array(
                    '0' => 'nomor',
                    '1' => 'tgl',
                    '2' => 'keterangan'
                );                                      
                $params_datatable = array(
                    'tipe' => 6,
                    'tgl >' => $this->input->post('date_start'),
                    'tgl <' => $this->input->post('date_end')
                );
            }

            if($action=='create'){

                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_user_id' => $session['user_data']['user_id'],
                    'trans_kontak_id' => $data['kontak'],
                    'trans_flag' => 1
                );

                //Check Data Exist
                $params_check = array(
                    'trans_nomor' => $data['nomor']
                );
                $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                if($check_exists==false){

                    $set_data=$this->Transaksi_model->add_transaksi($params);
                    if($set_data==true){
                        /* Start Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 2,
                            'table' => 'transaksi',
                            'table_id' => $set_data,                            
                            'text_1' => strtoupper($data['kode']),
                            'text_2' => ucwords(strtolower($data['nama'])),                        
                            'date_created' => date('YmdHis'),
                            'flag' => 1
                        );
                        $this->save_activity($params);    
                        */
                        /* End Activity */            
                        $return->status=1;
                        $return->message='Success';
                        $return->result= array(
                            'id' => $set_data,
                            'kode' => $data['kode']
                        ); 
                    }
                }else{
                    $return->message='Nomor sudah digunakan';                    
                }
            }
            if($action=='read'){
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE);     
                $data['id'] = $this->input->post('id');           
                $datas = $this->Transaksi_model->get_transaksi($data['id']);
                if($datas==true){
                    /* Activity */
                    /*
                    $params = array(
                        'id_user' => $session['user_data']['user_id'],
                        'action' => 3,
                        'table' => 'transaksi',
                        'table_id' => $datas['id'],
                        'text_1' => strtoupper($datas['kode']),
                        'text_2' => ucwords(strtolower($datas['username'])),
                        'date_created' => date('YmdHis'),
                        'flag' => 0
                    );
                    $this->save_activity($params);                    
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                    $return->result=$datas;
                }                
            }
            if($action=='update'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $params = array(
                    // 'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    // 'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_date_updated' => date("YmdHis"),
                    'trans_kontak_id' => $data['kontak'],
                    // 'trans_flag' => 1
                );
                /*
                if(!empty($data['password'])){
                    $params['password'] = md5($data['password']);
                }
                */
               
                $set_update=$this->Transaksi_model->update_transaksi($id,$params);
                if($set_update==true){
                    /* Activity */
                    /*
                    $params = array(
                        'id_user' => $session['user_data']['user_id'],
                        'action' => 4,
                        'table' => 'transaksi',
                        'table_id' => ,
                        'text_1' => strtoupper($data['kode']),
                        'text_2' => ucwords(strtolower($data['nama'])),
                        'date_created' => date('YmdHis'),
                        'flag' => 0
                    );
                    */
                    $this->save_activity($params);
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                }                
            }    
            if($action=='delete'){
                $id = $this->input->post('id');
                $kode = $this->input->post('kode');        
                $nama = $this->input->post('nama');                                
                $flag = $this->input->post('flag');

                if($flag==1){
                    $msg='aktifkan transaksi '.$nama;
                    $act=7;
                }else{
                    $msg='nonaktifkan transaksi '.$nama;
                    $act=8;
                }

                $set_data=$this->Transaksi_model->update_transaksi($id,array('trans_flag'=>$flag));
                if($set_data==true){    
                    /* Activity */
                    /*
                    $params = array(
                        'id_user' => $session['user_data']['user_id'],
                        'action' => $act,
                        'table' => 'transaksi',
                        'table_id' => $id,
                        'text_1' => strtoupper($kode),
                        'text_2' => ucwords(strtolower($nama)),
                        'date_created' => date('YmdHis'),
                        'flag' => 0
                    );
                    */
                    $this->save_activity($params);                               
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil '.$msg;
                }                
            }             
            if($action=='load'){

                $limit = $this->input->post('length');
                $start = $this->input->post('start');
                $order = $columns[$this->input->post('order')[0]['column']];
                $dir = $this->input->post('order')[0]['dir'];

                $search = [];
                if ($this->input->post('search')['value']) {
                    $s = $this->input->post('search')['value'];
                    foreach ($columns as $v) {
                        $search[$v] = $s;
                    }
                }

                /*
                if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                    $params['other_column'] = $this->input->post('other_column');
                }
                */
                $params_datatable = array(
                    'trans.trans_tipe' => $identity
                );
                $datas = $this->Transaksi_model->get_all_transaksis($params_datatable, $search, $limit, $start, $order, $dir);
                $datas_count = $this->Transaksi_model->get_all_transaksis_count($params_datatable);
                // $datas_count = $this->Transaksi_model->get_all_transaksis_count($params_datatable, $search, $limit, $start, $order, $dir);                                 
                
                if(isset($datas)){ //Data exist
                    $data_source=$datas; $total=$datas_count;
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->recordsTotal = $total;
                $return->recordsFiltered = $total;
            }

            if($action=='create-item'){

                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);
                $params = array(
                    'trans_item_trans_id' => $data['trans_id'],
                    'trans_item_order_id' => $data['order_id'],
                    'trans_item_order_detail_id' => $data['order_detail_id'],
                    'trans_item_produk_id' => $data['produk_id'],
                    'trans_item_lokasi_id' => $data['lokasi_id'],
                    'trans_item_tgl' => date("YmdHis"),
                    'trans_item_satuan' => $data['satuan'],
                    'trans_item_masuk_qty' => $data['masuk_qty'],
                    'trans_item_masuk_harga' => $data['masuk_harga'],
                    'trans_item_keluar_qty' => $data['keluar_qty'],
                    'trans_item_keluar_harga' => $data['keluar_harga'],
                    'trans_item_tipe' => $data['tipe'],
                    'trans_item_date_created' => date("YmdHis"),
                    'trans_item_date_updated' => date("YmdHis"),
                    'trans_item_user_id' => $session['user_data']['user_id'],
                    'trans_item_flag' => 1
                );

                //Check Data Exist
                $params_check = array(
                    'trans_nomor' => $data['nomor']
                );
                $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                if($check_exists==false){

                    $set_data=$this->Transaksi_model->add_transaksi_item($params);
                    if($set_data==true){
                        /* Start Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 2,
                            'table' => 'transaksi',
                            'table_id' => $set_data,                            
                            'text_1' => strtoupper($data['kode']),
                            'text_2' => ucwords(strtolower($data['nama'])),                        
                            'date_created' => date('YmdHis'),
                            'flag' => 1
                        );
                        $this->save_activity($params);    
                        */
                        /* End Activity */            
                        $return->status=1;
                        $return->message='Success';
                        $return->result= array(
                            'id' => $set_data,
                            'kode' => $data['kode']
                        ); 
                    }
                }else{
                    $return->message='Item sudah digunakan';                    
                }
            }
            if($action=='read-item'){
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE);     
                $data['id'] = $this->input->post('id');           
                $datas = $this->Transaksi_model->get_transaksi_item($data['id']);
                if($datas==true){
                    /* Activity */
                    /*
                    $params = array(
                        'id_user' => $session['user_data']['user_id'],
                        'action' => 3,
                        'table' => 'transaksi',
                        'table_id' => $datas['id'],
                        'text_1' => strtoupper($datas['kode']),
                        'text_2' => ucwords(strtolower($datas['username'])),
                        'date_created' => date('YmdHis'),
                        'flag' => 0
                    );
                    $this->save_activity($params);                    
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                    $return->result=$datas;
                }                
            }
            if($action=='update-item'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $params = array(
                    'trans_item_trans_id' => $data['trans_id'],
                    'trans_item_order_id' => $data['order_id'],
                    'trans_item_order_detail_id' => $data['order_detail_id'],
                    'trans_item_produk_id' => $data['produk_id'],
                    'trans_item_lokasi_id' => $data['lokasi_id'],
                    'trans_item_tgl' => date("YmdHis"),
                    'trans_item_satuan' => $data['satuan'],
                    'trans_item_masuk_qty' => $data['masuk_qty'],
                    'trans_item_masuk_harga' => $data['masuk_harga'],
                    'trans_item_keluar_qty' => $data['keluar_qty'],
                    'trans_item_keluar_harga' => $data['keluar_harga'],
                    'trans_item_tipe' => $data['tipe'],
                    'trans_item_date_updated' => date("YmdHis"),
                    'trans_item_flag' => 1
                );
                /*
                if(!empty($data['password'])){
                    $params['password'] = md5($data['password']);
                }
                */
               
                $set_update=$this->Transaksi_model->update_transaksi_item($id,$params);
                if($set_update==true){
                    /* Activity */
                    /*
                    $params = array(
                        'id_user' => $session['user_data']['user_id'],
                        'action' => 4,
                        'table' => 'transaksi',
                        'table_id' => ,
                        'text_1' => strtoupper($data['kode']),
                        'text_2' => ucwords(strtolower($data['nama'])),
                        'date_created' => date('YmdHis'),
                        'flag' => 0
                    );
                    */
                    $this->save_activity($params);
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                }                
            }    
            if($action=='delete-item'){
                $id = $this->input->post('id');
                $kode = $this->input->post('kode');        
                $nama = $this->input->post('nama');                                
                $flag = $this->input->post('flag');

                if($flag==1){
                    $msg='aktifkan transaksi '.$nama;
                    $act=7;
                }else{
                    $msg='nonaktifkan transaksi '.$nama;
                    $act=8;
                }

                $set_data=$this->Transaksi_model->update_transaksi_item($id,array('trans_item_flag'=>$flag));
                if($set_data==true){    
                    /* Activity */
                    /*
                    $params = array(
                        'id_user' => $session['user_data']['user_id'],
                        'action' => $act,
                        'table' => 'transaksi',
                        'table_id' => $id,
                        'text_1' => strtoupper($kode),
                        'text_2' => ucwords(strtolower($nama)),
                        'date_created' => date('YmdHis'),
                        'flag' => 0
                    );
                    */
                    $this->save_activity($params);                               
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil '.$msg;
                }                
            }             
            if($action=='load-item'){
                $trans_id = $this->input->post('id');
                $limit = $this->input->post('length');
                $start = $this->input->post('start');
                $order = $columns[$this->input->post('order')[0]['column']];
                $dir = $this->input->post('order')[0]['dir'];

                $search = [];
                if ($this->input->post('search')['value']) {
                    $s = $this->input->post('search')['value'];
                    foreach ($columns as $v) {
                        $search[$v] = $s;
                    }
                }

                /*
                if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                    $params['other_column'] = $this->input->post('other_column');
                }
                */
                $params_datatable = array(
                    'trans.trans_id' => $trans_id
                );
                $datas = $this->Transaksi_model->get_all_transaksi_items($params_datatable, $search, $limit, $start, $order, $dir);
                $datas_count = $this->Transaksi_model->get_all_transaksi_items_count($params_datatable);
                // $datas_count = $this->Transaksi_model->get_all_transaksis_count($params_datatable, $search, $limit, $start, $order, $dir);                                 
                
                if(isset($datas)){ //Data exist
                    $data_source=$datas; $total=$datas_count;
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->recordsTotal = $total;
                $return->recordsFiltered = $total;
            }                                                             
        }
        if(empty($action)){
            $action='';
        }
        $return->action=$action;
        echo json_encode($return);        
    }     
}

?>