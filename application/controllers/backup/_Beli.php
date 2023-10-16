<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Beli extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('Aktivitas_model');
        $this->load->model('User_model');           
        $this->load->model('Kontak_model');                   
        $this->load->model('Produk_model');                   
        $this->load->model('Satuan_model');
        $this->load->model('Referensi_model');          
        $this->load->model('Transaksi_model');
        $this->load->model('Order_model');      
        $this->load->model('Print_spoiler_model');  
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
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('beli/beli_js.php',$data);        
    }
    function manage(){
        $session = $this->session->userdata();        
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
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1                    
                );
                
                */             
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contact_name',
                    '3' => 'order_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
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
                $generate_nomor = $this->request_number_for_order(1);

                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);
                $params = array(
                    'order_type' => $data['tipe'],
                    'order_contact_id' => $data['kontak'],
                    'order_number' => $generate_nomor,
                    'order_date' => date("YmdHis"),
                    'order_ref_id' => 0,
                    // 'order_ppn' => $data['ppn'],
                    // 'order_diskon' => $data['diskon'],
                    // 'order_total' => $data['total'],
                    // 'order_keterangan' => $data['keterangan'],
                    'order_date_created' => date("YmdHis"),
                    'order_date_updated' => date("YmdHis"),
                    'order_user_id' => $session['user_data']['user_id'],
                    'order_ref_id' => 458,
                    'order_flag' => 1
                );

                //Check Data Exist
                $params_check = array(
                    'order_number' => $generate_nomor
                );
                $check_exists = $this->Order_model->check_data_exist($params_check);
                if($check_exists==false){

                    $set_data=$this->Order_model->add_order($params);
                    if($set_data==true){
                        $trans_id = $set_data;
                        $trans_list = $data['order_list'];
                        foreach($trans_list as $index => $value){
                            
                            $params_update_trans_item = array(
                                'order_item_order_id' => $trans_id,
                                'order_item_flag' => 1                                
                            );
                            $this->Order_model->update_order_item($value,$params_update_trans_item);
                        }

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
                            'order_id' => $trans_id,
                            'order_number' => $generate_nomor
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
                $datas = $this->Order_model->get_order($data['id']);
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
                    // 'order_tipe' => $data['tipe'],
                    // 'order_nomor' => $data['nomor'],
                    // 'order_tgl' => date("YmdHis"),
                    'order_ppn' => $data['ppn'],
                    'order_discount' => $data['diskon'],
                    'order_total' => $data['total'],
                    'order_note' => $data['keterangan'],
                    'order_date_updated' => date("YmdHis"),
                    'order_contact_id' => $data['kontak'],
                    // 'order_flag' => 1
                );
                /*
                if(!empty($data['password'])){
                    $params['password'] = md5($data['password']);
                }
                */
               
                $set_update=$this->Order_model->update_order($id,$params);
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
                $number = $this->input->post('number');                               
                // $flag = $this->input->post('flag');
                $flag=4;

                $set_data=$this->Order_model->update_order($id,array('order_flag'=>$flag));
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
                    // $this->save_activity($params);                               
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil menghapus '.$number;
                }                
            }        
            if($action=='cancel'){
                $set_data=$this->Order_model->reset_order_item($session['user_data']['user_id']);
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
                    // $this->save_activity($params);                               
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil mereset';
                }else{
                    $return->message='Tidak ditemukan penghapusan';
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
                    'orders.order_type' => $identity,
                    'orders.order_flag' => 1
                );
                $datas = $this->Order_model->get_all_orders($params_datatable, $search, $limit, $start, $order, $dir);
                // var_dump($datas);die;
                $datas_count = $this->Order_model->get_all_orders_count($params_datatable);
                // $datas_count = $this->Order_model->get_all_orders_count($params_datatable, $search, $limit, $start, $order, $dir);                                 
                
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

                if(empty($data['total'])){
                    $total = str_replace('.','',$data['harga'])*$data['qty'];
                }

                $set_keterangan = $data['keterangan'];
                if($data['produk'] !== 0){
                    $get_product = $this->Produk_model->get_produk($data['produk']);
                    $set_keterangan = $get_product['product_name'];
                }
                
                $params_items = array(
                    // 'order_item_id_trans' => $data['order_id'],
                    // 'order_item_id_order' => $data['order_id'],
                    // 'order_item_id_order_detail' => $data['order_detail_id'],
                    'order_item_product_id' => $data['produk'],
                    // 'order_item_id_lokasi' => $data['lokasi_id'],
                    'order_item_date' => date("YmdHis"),
                    'order_item_unit' => $data['satuan'],
                    'order_item_qty' => $data['qty'],
                    'order_item_price' => str_replace('.','',$data['harga']),
                    // 'order_item_keluar_qty' => $data['qty'],
                    // 'order_item_keluar_harga' => $data['harga'],
                    'order_item_type' => $data['tipe'],
                    'order_item_discount' => 0,
                    'order_item_total' => $total,
                    'order_item_date_created' => date("YmdHis"),
                    'order_item_date_updated' => date("YmdHis"),
                    'order_item_user_id' => $session['user_data']['user_id'],
                    'order_item_flag' => 0,
                    'order_item_note' => $set_keterangan
                );

                //Check Data Exist Trans Item
                $params_check = array(
                    'order_item_type' => $identity,
                    'order_item_product' => $data['produk']
                );
                // $check_exists = $this->Order_model->check_data_exist_item($params_check);
                $check_exists = false;
                if($check_exists==false){

                    $set_data=$this->Order_model->add_order_item($params_items);
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
                            'id' => $set_data
                            // 'kode' => $data['kode']
                        ); 
                    }
                }else{
                    $return->message='Produk sudah diinput';                    
                }
            }
            if($action=='read-item'){
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE);     
                $data['id'] = $this->input->post('id');           
                $datas = $this->Order_model->get_order_item($data['id']);
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
                    'order_item_order_id' => $data['order_id'],
                    'order_item_order_item_id' => $data['order_detail_id'],
                    'order_item_product_id' => $data['produk_id'],
                    // 'order_item_location_id' => $data['lokasi_id'],
                    'order_item_date' => date("YmdHis"),
                    'order_item_unit' => $data['satuan'],
                    'order_item_qty' => $data['masuk_qty'],
                    'order_item_price' => $data['masuk_harga'],
                    'order_item_type' => $data['tipe'],
                    'order_item_date_updated' => date("YmdHis"),
                    'order_item_flag' => 1
                );
                /*
                if(!empty($data['password'])){
                    $params['password'] = md5($data['password']);
                }
                */
               
                $set_update=$this->Order_model->update_order_item($id,$params);
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
                    // $this->save_activity($params);
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

                // $set_data=$this->Order_model->update_order_item($id,array('trans_item_flag'=>0));
                $set_data=$this->Order_model->delete_order_item($id);                      
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
                    // $this->save_activity($params);                               
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil menghapus '.$nama;
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
                    'orders.order_id' => $trans_id
                );
                $datas = $this->Order_model->get_all_order_items($params_datatable, $search, $limit, $start, $order, $dir);
                $datas_count = $this->Order_model->get_all_order_items_count($params_datatable);
                // $datas_count = $this->Order_model->get_all_orders_count($params_datatable, $search, $limit, $start, $order, $dir);                                 
                
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
            if($action=='check-last-item'){
                $get_data = $this->Order_model->check_unsaved_order_item($identity,$session['user_data']['user_id']);
                if(!empty($get_data)){
                    $subtotal = 0;
                    $total_diskon = 0;
                    $total= 0;

                    foreach($get_data as $v){
                        $datas[] = array(
                            'order_item_id' => $v['order_item_id'],
                            'order_item_order_id' => $v['order_item_order_id'],                            
                            'order_item_unit' => $v['order_item_unit'],
                            'order_item_qty' => $v['order_item_qty'],                            
                            'order_item_price' => number_format($v['order_item_price'],0,'.',','),
                            'order_item_discount' => number_format($v['order_item_discount'],0,'.',','),
                            'order_item_total' => number_format($v['order_item_total'],0,'.',','),
                            'order_item_total_after_discount' => number_format($v['order_item_total'],0,'.',','),                              
                            'order_item_note' => $v['order_item_note'],                          
                            'product_id' => $v['product_id'],
                            'product_code' => $v['product_code'],
                            'product_name' => $v['product_name'],
                        );
                        $subtotal=$subtotal+$v['order_item_total'];
                    }
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
                    if(isset($datas)){ //Data exist
                        $data_source=$datas; $total=count($datas);
                        $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                        $return->result=$datas; 
                        $return->total_produk=count($datas);
                        $return->subtotal=number_format($subtotal,0,'.',',');
                        $return->total_diskon=number_format($total_diskon,0,'.',',');
                        $return->total=number_format($subtotal-$total_diskon,0,'.',',');
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;
                    }                    
                    $return->status=1;
                    $return->message='Terdapat item temporary';
                }else{
                    $return->message='Tidak ada item temporary';
                }
            }
            if($action=='create-item-note'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $params = array(
                    'order_item_note' => $data['note'],
                );
                $set_update=$this->Order_model->update_order_item($id,$params);
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
                    // $this->save_activity($params);
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                }                
            }                                                 
        }
        if(empty($action)){
            $action='';
        }
        $return->action=$action;
        echo json_encode($return);        
    }  
    function print_beli($id){
        // $this->load->model('Print_spoiler_model');
        // $id=$data['id'];
        // var_dump($id);
        $data['title'] = 'PEMBELIAN';
        $content = '';

        //Header
        $params = array(
            'order_id' => $id
        );
        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Order_model->get_order($id);
        $data['contact'] = $this->Kontak_model->get_kontak($data['header']['order_contact_id']);
        $set_header = '';
        $set_header .= '      '.$data['header']['order_number'];
        $set_header .= '      '.$data['header']['order_date'];        


        //Content
        $params = array(
            'order_item_order_id' => $id
        );
        $search = '';
        $limit  = null;
        $start = 0;
        $order = 'order_item_date_created';
        $dir = 'ASC';

        $data['content'] = $this->Order_model->get_all_order_items($params,$search,$limit,$start,$order,$dir);
        // echo json_encode($set_header);die;
        // 
        // echo "<img src='".base_url('assets/webarch/img/logo/foodpedia_print.png')."' style='width:150px;'>"."\r\n";
        echo nl2br("\r\n");
        echo nl2br("PEMBELIAN"."\r\n");
        $content .= "PEMBELIAN\r\n";
        echo "\r\n";       
        echo nl2br($data['header']['order_number']."\r\n");
        $content .= $data['header']['order_number']."\r\n";
        echo nl2br(date("d-M-Y, H:i", strtotime($data['header']['order_date']))."\r\n");
        $content .= date("d-M-Y, H:i", strtotime($data['header']['order_date'])).", ".$data['header']['ref_name']."]\r\n";        
        echo nl2br($data['contact']['contact_name']."\r\n");
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";        
        foreach($data['content'] AS $v){
            // echo $v['product_name']."         ".number_format($v['order_item_total'])."\r\n";
            // echo nl2br($v['product_name']."\r\n");
            // $content .= $v['product_name']."\r\n"; 
            if(!empty($v['order_item_note'])){
                echo nl2br($v['order_item_note']."\r\n");
                $content .= $v['order_item_note']."\r\n";                
            }
            echo nl2br("Rp. ".number_format($v['order_item_price'])." x ".$v['order_item_qty']." ".$v['order_item_unit']." (Rp. ".number_format($v['order_item_total']).")"."\r\n");
            $content .= "Rp. ".number_format($v['order_item_price'])." x ".$v['order_item_qty']." ".$v['order_item_unit']." (Rp. ".number_format($v['order_item_total']).")"."\r\n";            

            echo nl2br("\r\n");
            $content .= "\r\n";            
        }     
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";        
        // echo "Terima Kasih atas Kedatangannya"."\r\n";
        // echo "Di tunggu orderan berikutnya :)"."\r\n";

        $params = array(
            'spoiler_source_table' => 'trans',
            'spoiler_source_id' => $id,
            'spoiler_content' => $content,
            'spoiler_date' => date('YmdHis'),
            'spoiler_flag' => 0
        );
        $this->Print_spoiler_model->add_print_spoiler($params);
        // echo $content;        
    }       
}

?>