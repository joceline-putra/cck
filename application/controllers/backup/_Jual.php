<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Jual extends MY_Controller{
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
        $this->load->model('Order_model');        
        $this->load->model('Transaksi_model');
        $this->load->model('Kategori_model');        

    }

    function index(){
        $data['identity'] = 2;
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

        $data['title'] = 'Payment';
        $data['_view'] = 'jual/jual';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('jual/jual_js.php',$data);        
    }
    function prints($id){
        $data['title'] = 'Order';
        
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
        // echo json_encode($data['content']);die;
        $this->load->view('prints/sales_order',$data);        
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
            if($identity == 2){
                $set_tipe = 2;
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
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );                                   
            }

            if($action=='create'){
                $generate_nomor = $this->request_number_for_order(2);

                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);
                $params = array(
                    'order_type' => $data['tipe'],
                    'order_contact_id' => $data['kontak'],
                    'order_number' => $generate_nomor,
                    'order_date' => date("YmdHis"),
                    // 'order_ppn' => $data['ppn'],
                    // 'order_diskon' => $data['diskon'],
                    // 'order_total' => $data['total'],
                    // 'order_keterangan' => $data['keterangan'],
                    'order_date_created' => date("YmdHis"),
                    'order_date_updated' => date("YmdHis"),
                    'order_user_id' => $session['user_data']['user_id'],
                    'order_flag' => 0,
                    'order_ref_id' => $data['meja']
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
                    'orders.order_flag <' => 4
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
                    $total = $data['harga']*$data['qty'];
                }

                $params_items = array(
                    // 'order_item_id_order' => $data['order_id'],
                    // 'order_item_id_order_detail' => $data['order_detail_id'],
                    'order_item_product_id' => $data['produk'],
                    // 'order_item_id_lokasi' => $data['lokasi_id'],
                    'order_item_date' => date("YmdHis"),
                    'order_item_unit' => $data['satuan'],
                    'order_item_qty' => $data['qty'],
                    'order_item_price' => $data['harga'],
                    // 'order_item_keluar_qty' => $data['qty'],
                    // 'order_item_keluar_harga' => $data['harga'],
                    'order_item_type' => $data['tipe'],
                    'order_item_discount' => 0,
                    'order_item_total' => $total,
                    'order_item_date_created' => date("YmdHis"),
                    'order_item_date_updated' => date("YmdHis"),
                    'order_item_user_id' => $session['user_data']['user_id'],
                    'order_item_flag' => 0
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
                    // 'order_item_discount' => $data['masuk_harga'],
                    // 'order_item_total' => $data['masuk_harga'],                                        
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
            if($action=='delete-item'){ //Perlu dicek
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

                // $set_data=$this->Order_model->update_order_item($id,array('order_item_flag'=>0));
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
                $get_data = $this->Order_model->check_unsaved_order_item($session['user_data']['user_id']);
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
            if($action=='create-item-plus-minus'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $operator = $data['operator'];
                $qty= $data['qty'];
                $price = $data['price'];
                $discount = $data['discount'];                

                if($operator=='increase'){
                    $set_qty = floatVal($qty)+1;
                    $set_message = 'di tambahkan lagi';
                }else if($operator=='decrease'){
                    $set_qty = floatVal($qty)-1;
                    $set_message = 'di kurangi lagi';
                }

                if($set_qty == 0){
                    $return->message='Tidak bisa '.$set_message;
                }else{
                    $set_price = $price;
                    $set_discount = $discount;
                    $set_total = ($set_price*$set_qty)-$discount;
                    $params = array(
                        'order_item_qty' => $set_qty,
                        'order_item_price' => $set_price,
                        'order_item_discount' => $set_discount,
                        'order_item_total' => $set_total,
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
            if($action=='create-item-discount'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $qty= $data['qty'];
                $price = $data['price'];
                $discount = $data['discount'];                
                $total = $data['total'];

                if(floatVal($total) < 0){
                    $return->message='Subtotal minus ';
                }else{
                    $params = array(
                        'order_item_qty' => $qty,
                        'order_item_price' => $price,
                        'order_item_discount' => $discount,                        
                        'order_item_total' => $total,
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
            if($action=='delete-item-note'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $params = array(
                    'order_item_note' => '',
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
            if($action=='delete-item-discount'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $qty= $data['qty'];
                $price = $data['price'];

                $set_price = $price;
                $set_qty = $qty;
                $set_total = $set_price*$set_qty;

                $params = array(
                    'order_item_qty' => $set_qty,
                    'order_item_price' => $set_price,
                    'order_item_discount' => 0,
                    'order_item_total' => $set_total,
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
            if($action=='load-product-tab'){
                $start = 0;
                $limit = 100;
                $order = 'category_name';
                $dir = 'ASC';
                $search=null;
                // $params_datatable = array(
                //     'ref_type' => $data['ref_type'], //Product Categories
                //     'ref_flag' => $data['ref_flag']
                // );
                $params_datatable = array(
                    'category_type' => 1, //Product Categories
                    'category_flag' => 1
                );                
                // $datas = $this->Referensi_model->get_all_referensis($params_datatable, $search, $limit, $start, $order, $dir);
                // $datas_count = $this->Referensi_model->get_all_referensis_count($params_datatable);   
                $datas = $this->Kategori_model->get_all_categoriess($params_datatable, $search, $limit, $start, $order, $dir);
                $datas_count = $this->Kategori_model->get_all_categoriess_count($params_datatable);                                
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
    if($action=='load-unpaid-order'){
        // $post_data = $this->input->post('data');
        // $data = base64_decode($post_data);
        // $data = json_decode($post_data, TRUE);

        // $trans_id = $this->input->post('id');
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        $search = [];
        if ($this->input->post('search')['value']) {
            $s = $this->input->post('search')['value'];
            foreach ($columns as $v) {
                $search[$v] = $s;
            }
        }
        $start = 0;
        $limit = 100;
        $order = 'order_number';
        $dir = 'ASC';
        $params_datatable = array(
            'order_type' => 2,
            'order_flag' => 0,
        );
        $datas = $this->Order_model->get_all_orders($params_datatable, $search, $limit, $start, $order, $dir);
        $datas_count = $this->Order_model->get_all_orders_count($params_datatable);
        
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
            if($action=='create-print-spoiler'){
                $this->load->model('Print_spoiler');
                $id=$data['id'];
                var_dump($id);
            }                                                            
        }
        if(empty($action)){
            $action='';
        }
        $return->action=$action;
        echo json_encode($return);        
    }    
    function test($tipe,$id){
        $this->load->model('Print_spoiler_model');
        // $id=$data['id'];
        // var_dump($id);
        $data['title'] = 'Order';
        
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
        echo json_encode($set_header);die;

    } 
}

?>