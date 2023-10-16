<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends MY_Controller{
    // var $menu_id = 40; // 39
    // var $folder_location = array(
    //     '1' => array(
    //         '0' => array( //Statistik
    //             'title' => 'Statistik',
    //             'view' => 'purchase/statistic',
    //             'javascript' => 'purchase/statistic_js'
    //         ),
    //         '1' => array( //Puchase Order
    //             'title' => 'Pesanan Penjualan',
    //             'view' => 'purchase/purchase_order',
    //             'javascript' => 'purchase/purchase_order_js'
    //         ),
    //         '2' => array( //Penjualan
    //             'title' => 'Penjualan',
    //             'view' => 'purchase/buy',
    //             'javascript' => 'purchase/buy_js'
    //         ),
    //         '3' => array( //Return
    //             'title' => 'Retur Pembelian',
    //             'view' => 'purchase/return',
    //             'javascript' => 'purchase/return_js'
    //         ),
    //         '4' => array( //Bayar Hutang
    //             'title' => 'Bayar Hutang',
    //             'view' => 'finance/account_payable',
    //             'javascript' => 'finance/account_payable_js'
    //         ),                  
    //     ),
    //     '2' => array(
    //         '0' => array( //Statistik
    //             'title' => 'Statistik',
    //             'view' => 'sales/statistic',
    //             'javascript' => 'sales/statistic_js'
    //         ),
    //         '1' => array( //Sales Order
    //             'title' => 'Pesanan Penjualan',
    //             'view' => 'sales/sales_order',
    //             'javascript' => 'sales/sales_order_js'
    //         ),
    //         '2' => array( //Penjualan
    //             'title' => 'Penjualan',
    //             'view' => 'sales/sell',
    //             'javascript' => 'sales/sell_js'
    //         ),
    //         '3' => array( //Return
    //             'title' => 'Retur Penjualan',
    //             'view' => 'sales/return',
    //             'javascript' => 'sales/return_js'
    //         ),
    //         '4' => array( //Bayar Putang
    //             'title' => 'Bayar Piutang',
    //             'view' => 'finance/account_receivable',
    //             'javascript' => 'finance/account_receivable_js'
    //         ),                  
    //     )
    // );  
    var $folder_location = array(
        '11' => array(
            'title' => 'Statistik',
            'view' => 'asset/statistic',
            'javascript' => 'asset/statistic_js'    
        ),
        '1' => array(
            'title' => 'Pembelian Aset',
            'view' => 'asset/buy',
            'javascript' => 'asset/buy_js'
        ),
        '2' => array(
            'title' => 'Penjualan Aset',
            'view' => 'asset/sell',
            'javascript' => 'asset/sell_js'
        ),
        '3' => array(
            'title' => 'Penyusutan Aset',
            'view' => 'asset/depreciation',
            'javascript' => 'asset/depreciation_js'
        ),  
    );            
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
        $this->load->model('Print_spoiler_model');
        $this->load->model('Product_recipe_model');        
        $this->load->model('Product_price_model');
        $this->load->model('Menu_model');        
        $this->load->model('Branch_model');                       

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

        $data['title'] = 'Daftar Asset';
        $data['_view'] = 'layouts/admin/menu/asset/statistic';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/asset/statistic_js.php',$data);        
    }
    function pages($identity){

        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        // $params_menu = array(
        //     'menu_parent_id' => $this->menu_id
        // );
        // $data['navigation'] = $this->Menu_model->get_all_menus($params_menu,null,null,null,null,null);
        
        $data['identity'] = $identity;
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['_view'] = $this->folder_location[$identity]['view'];
        $file_js = $this->folder_location[$identity]['javascript'];
        $data['operator'] = '';
        // $params_menu = array(
        //     'menu_parent_id' => $this->menu_id
        // );
        // $data['navigation'] = $this->Menu_model->get_all_menus($params_menu,null,null,null,null,null);

        // var_dump($identity);die;
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d"); 
        $data['first_date'] = date('d-m-Y', strtotime($firstdateofmonth));
        $data['end_date'] = date('d-m-Y', strtotime($datenow));
        $data['end_date_due'] = date('d-m-Y', strtotime('+30 days',strtotime($datenow)));       
         
        // var_dump($data['first_date'],$data['end_date']);
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function action($identity,$operator){

        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        // $params_menu = array(
        //     'menu_parent_id' => $this->menu_id
        // );
        // $data['navigation'] = $this->Menu_model->get_all_menus($params_menu,null,null,null,null,null);
        
        $data['identity'] = $identity;
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['_view'] = $this->folder_location[$identity]['view'];
        $file_js = $this->folder_location[$identity]['javascript'];
        $data['operator'] = $operator;
        // $params_menu = array(
        //     'menu_parent_id' => $this->menu_id
        // );
        // $data['navigation'] = $this->Menu_model->get_all_menus($params_menu,null,null,null,null,null);

        // var_dump($identity);die;
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d"); 
        $data['first_date'] = date('d-m-Y', strtotime($firstdateofmonth));
        $data['end_date'] = date('d-m-Y', strtotime($datenow));
        $data['end_date_due'] = date('d-m-Y', strtotime('+30 days',strtotime($datenow)));       
         
        // var_dump($data['first_date'],$data['end_date']);
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }    
    function manage(){
        $session = $this->session->userdata();   
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

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
            if($identity == 1){ //Purchase Order
                $set_tipe = 1;
                $set_transaction = 'Purchase Order';
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
            if($identity == 2){ //Sales Order
                $set_tipe = 2;
                $set_transaction = 'Sales Order';                
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
            if($identity == 3){ //Sales Order
                $set_tipe = 2;
                $set_transaction = 'Quotation';                
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

            /* CRUD orders */
            if($action=='create'){
                $generate_nomor = $this->request_number_for_order($identity);
                $jam = date('h:i:s');
                
                $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                $tgl_tempo = substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2);
                
                $set_date = $tgl.' '.$jam;
                $set_date_due = $tgl_tempo.' '.$jam;

                // var_dump($data['kontak']);die;
                $params = array(
                    'order_type' => $data['tipe'],
                    'order_contact_id' => $data['kontak'],
                    'order_number' => $generate_nomor,
                    'order_date' => $set_date,
                    // 'order_ppn' => $data['ppn'],
                    // 'order_diskon' => $data['diskon'],
                    // 'order_total' => $data['total'],
                    'order_note' => $data['keterangan'],
                    'order_date_created' => date("YmdHis"),
                    'order_date_updated' => date("YmdHis"),
                    'order_user_id' => $session_user_id,
                    'order_branch_id' => $session_branch_id,
                    'order_flag' => 0,
                    // 'order_ref_id' => $data['meja'],
                    // 'order_with_dp' => str_replace(',','',$data['total_down_payment'])
                    'order_ref_number' => $data['nomor_ref'],
                    'order_date_due' => $set_date_due
                );

                //Check Data Exist
                $params_check = array(
                    'order_number' => $generate_nomor,
                    'order_branch_id' => $session_branch_id
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
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 2,
                            'activity_table' => 'orders',
                            'activity_table_id' => $set_data,                            
                            'activity_text_1' => $set_transaction,
                            'activity_text_2' => $generate_nomor,                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);    
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
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 3,
                        'activity_table' => 'orders',
                        'activity_table_id' => $data['id'],                            
                        'activity_text_1' => $set_transaction,
                        'activity_text_2' => $datas['order_number'],                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);    
                    /* End Activity */
                }

                //Get Order on Trans Table
                $params = array(
                    'trans_item_order_id' => $data['id']
                );
                $get_trans = $this->Transaksi_model->get_all_transaksi_items_count($params);

                $return->status = 1;
                $return->message = 'Success';
                $return->result = $datas;
                $return->result_trans = $get_trans;
            }
            if($action=='update'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $get_data = $this->Order_model->get_order($id);

                $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                $jam = substr($get_data['order_date'],10,9);
                $set_date = $tgl.$jam;
                $params = array(
                    // 'order_tipe' => $data['tipe'],
                    // 'order_nomor' => $data['nomor'],
                    'order_date' => $set_date,
                    // 'order_ppn' => $data['ppn'],
                    // 'order_discount' => $data['diskon'],
                    // 'order_total' => $data['total'],
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
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 4,
                        'activity_table' => 'orders',
                        'activity_table_id' => $id,                            
                        'activity_text_1' => $set_transaction,
                        // 'activity_text_2' => $generate_nomor,                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil memperbarui';
                }                
            }    
            if($action=='delete'){
                $id = $this->input->post('id');
                $number = $this->input->post('number');                               
                // $flag = $this->input->post('flag');
                $flag=4;

                $set_data=$this->Order_model->update_order($id,array('order_flag'=>$flag));
                $set_data=$this->Order_model->update_order_item_by_order_id($id,array('order_item_flag'=>$flag));

                if($set_data==true){    
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 5,
                        'activity_table' => 'orders',
                        'activity_table_id' => $id,                            
                        'activity_text_1' => $set_transaction,
                        // 'activity_text_2' => $generate_nomor,                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);    
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil menghapus '.$number;
                }                
            }        
            if($action=='cancel'){
                $set_data=$this->Order_model->reset_order_item($session['user_data']['user_id']);
                if($set_data==true){    
                    /* Start Activity */
                    // $params = array(
                    //     'activity_user_id' => $session_user_id,
                    //     'activity_branch_id' => $session_branch_id,                        
                    //     'activity_action' => 9,
                    //     'activity_table' => 'orders',
                    //     'activity_table_id' => $set_data,                            
                    //     'activity_text_1' => $set_transaction,
                    //     'activity_text_2' => $generate_nomor,                        
                    //     'activity_date_created' => date('YmdHis'),
                    //     'activity_flag' => 1,
                    //     'activity_transaction' => $set_transaction,
                    //     'activity_type' => 2
                    // );
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
                $kontak = $this->input->post('kontak');
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
                $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                $params_datatable = array(
                    'orders.order_date >' => $date_start,
                    'orders.order_date <' => $date_end,
                    'orders.order_type' => $identity,
                    'orders.order_flag <' => 4,
                    'orders.order_branch_id' => $session_branch_id
                );
                if($kontak > 0){
                    $params_datatable = array(
                        'orders.order_date >' => $date_start,
                        'orders.order_date <' => $date_end,
                        'orders.order_type' => $identity,
                        'orders.order_flag <' => 4,
                        'orders.order_branch_id' => $session_branch_id,
                        'orders.order_contact_id' => $kontak                   
                    );                    
                }
                $datas = $this->Order_model->get_all_orders($params_datatable, $search, $limit, $start, $order, $dir);
                // var_dump($params_datatable);die;
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

            /* CRUD order_items */
            if($action=='create-item'){

                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);

                $harga=0;
                $qty=0;
                if(empty($data['total'])){
                    $harga = str_replace(',','',$data['harga']);
                    $qty = str_replace(',','',$data['qty']);                    
                    $total = $harga*$qty;
                }

                $params_items = array(
                    // 'order_item_id_order' => $data['order_id'],
                    // 'order_item_id_order_detail' => $data['order_detail_id'],
                    'order_item_product_id' => $data['produk'],
                    // 'order_item_id_lokasi' => $data['lokasi_id'],
                    'order_item_date' => date("YmdHis"),
                    'order_item_unit' => $data['satuan'],
                    'order_item_qty' => $data['qty'],
                    'order_item_price' => $harga,
                    // 'order_item_keluar_qty' => $data['qty'],
                    // 'order_item_keluar_harga' => $data['harga'],
                    'order_item_type' => $data['tipe'],
                    'order_item_discount' => 0,
                    'order_item_total' => $total,
                    'order_item_date_created' => date("YmdHis"),
                    'order_item_date_updated' => date("YmdHis"),
                    'order_item_user_id' => $session_user_id,
                    'order_item_branch_id' => $session_branch_id,
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

                        //Check the Product is Stock Card Mode ?
                        $get_product = $this->Produk_model->get_produk($data['produk']);
                        $product_stock_mode = $get_product['product_with_stock']; 
                        if($product_stock_mode == 1){ //Stock Card is Activated

                            $stock_start = $get_product['product_stock_start'];
                            $stock_in = $get_product['product_stock_in'];
                            $stock_out = $get_product['product_stock_out'];
                            $stock_end = $get_product['product_stock_end'];

                            $set_stock_out = $stock_out + 1;
                            $params_stock = array(
                                'product_stock_out' => $stock_out + 1
                            );
                            $this->Produk_model->update_produk($data['produk'],$params_stock);
                        }
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 2,
                            'activity_table' => 'order_items',
                            'activity_table_id' => $set_data,                            
                            'activity_text_1' => $set_transaction,
                            'activity_text_2' => $get_product['product_code'].' - '.$get_product['product_name'],                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);    
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
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 3,
                        'activity_table' => 'order_items',
                        'activity_table_id' => $data['id'],                            
                        'activity_text_1' => $set_transaction,
                        // 'activity_text_2' => $generate_nomor,                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 0,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
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

                $harga=0;
                $qty=0;
                if(empty($data['total'])){
                    $harga = str_replace(',','',$data['harga']);
                    $qty = str_replace(',','',$data['qty']);                    
                    $total = $harga*$qty;
                }

                $params = array(
                    'order_item_id' => $id,
                    // 'order_item_order_id' => $data['order_id'],
                    // 'order_item_order_item_id' => $data['order_detail_id'],
                    'order_item_product_id' => $data['produk'],
                    // 'order_item_location_id' => $data['lokasi_id'],
                    // 'order_item_date' => date("YmdHis"),
                    'order_item_unit' => $data['satuan'],
                    'order_item_qty' => $qty,
                    'order_item_price' => $harga,
                    // 'order_item_discount' => $data['masuk_harga'],
                    'order_item_total' => $total,                                        
                    // 'order_item_type' => $data['tipe'],
                    'order_item_date_updated' => date("YmdHis"),
                    // 'order_item_flag' => 1
                );
                /*
                if(!empty($data['password'])){
                    $params['password'] = md5($data['password']);
                }
                */
               
                $set_update=$this->Order_model->update_order_item($id,$params);
                if($set_update==true){
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 4,
                        'activity_table' => 'order_items',
                        'activity_table_id' => $id,                            
                        'activity_text_1' => $set_transaction,
                        // 'activity_text_2' => $generate_nomor,                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 0,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);    
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                }                
            }    
            if($action=='delete-item'){ //Perlu dicek
                $id = $this->input->post('id');
                $order_id = $this->input->post('order_id');                
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
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 5,
                        'activity_table' => 'order_items',
                        'activity_table_id' => $id,                            
                        'activity_text_1' => $set_transaction,
                        'activity_text_2' => $kode.' -  '.$nama,                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);    
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil menghapus '.$nama;
                    $return->result = array(
                        'order_id' => $order_id
                    );
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
                    'orders.order_id' => $trans_id,
                    'orders.order_branch_id' => $session_branch_id
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

            /* OTHER orders and order_items */
            if($action=='load-order-items'){
                
                $order_id = !empty($this->input->post('order_id')) ? $this->input->post('order_id') : 0;
                // var_dump($order_id);
                if(intval($order_id) > 0){
                    $params = array(
                        'order_item_order_id' => $order_id,
                        'order_item_branch_id' => $session_branch_id
                    );
                    $get_data = $this->Order_model->get_all_order_items($params,null,null,null,null);
                }else{
                    $get_data = $this->Order_model->check_unsaved_order_item($identity,$session_user_id,$session_branch_id);
                }

                if(!empty($get_data)){
                    $subtotal = 0;
                    $total_diskon = 0;
                    $total= 0;

                    foreach($get_data as $v){

                        $get_product_price = $this->Product_price_model->get_all_product_price(array('product_price_product_id'=>$v['product_id']),null,null,null,null,null);
                        $product_price_list = array();
                        foreach($get_product_price as $pp){
                            $product_price_list[] = array(
                                'product_price_id' => $pp['product_price_id'],
                                'product_price_product_id' => $pp['product_price_product_id'],                                
                                'product_price_name' => $pp['product_price_name'],
                                'product_price_price' => $pp['product_price_price']
                            );
                        }

                        $datas[] = array(
                            'order_item_id' => $v['order_item_id'],
                            'order_item_order_id' => $v['order_item_order_id'],                            
                            'order_item_unit' => $v['order_item_unit'],
                            'order_item_qty' => number_format($v['order_item_qty'],2,'.',','),
                            'order_item_price' => number_format($v['order_item_price'],2,'.',','),
                            'order_item_discount' => number_format($v['order_item_discount'],2,'.',','),
                            'order_item_total' => number_format($v['order_item_total'],2,'.',','),
                            'order_item_total_after_discount' => number_format($v['order_item_total'],2,'.',','),       
                            'order_item_note' => $v['order_item_note'],
                            'order_item_product_price_id' => $v['order_item_product_price_id'],
                            'order_item_user_id' => $v['order_item_user_id'],
                            'order_item_branch_id' => $v['order_item_branch_id'],
                            'product_id' => $v['product_id'],
                            'product_code' => $v['product_code'],
                            'product_name' => $v['product_name'],
                            'has_other_price' => $product_price_list
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
                    $return->message='Terdapat data yang belum disimpan';
                    if(intval($order_id) > 0){
                        $return->message = 'Berhasil memuat data';
                    }
                }else{
                    $return->message='Tidak ada item temporary';
                    if(intval($order_id) > 0){
                        $return->message = '-';
                    }                    
                }
            }
            if($action=='create-item-addon'){
                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);

                $harga = str_replace(',','', $data['harga']);
                $qty = str_replace(',','', $data['qty']);                
                $total = $harga*$qty;
                $params_items = array(
                    'order_item_order_id' => $data['order_id'],
                    // 'order_item_id_order_detail' => $data['order_detail_id'],
                    'order_item_product_id' => $data['produk'],
                    // 'order_item_id_lokasi' => $data['lokasi_id'],
                    'order_item_date' => date("YmdHis"),
                    'order_item_unit' => $data['satuan'],
                    'order_item_qty' => $qty,
                    'order_item_price' => $harga,
                    // 'order_item_keluar_qty' => $data['qty'],
                    // 'order_item_keluar_harga' => $data['harga'],
                    'order_item_type' => $data['tipe'],
                    'order_item_discount' => 0,
                    'order_item_total' => $total,
                    'order_item_date_created' => date("YmdHis"),
                    'order_item_date_updated' => date("YmdHis"),
                    'order_item_user_id' => $session_user_id,
                    'order_item_branch_id' => $session_branch_id,
                    'order_item_flag' => 0
                );

                //Check Data Exist Trans Item
                // $params_check = array(
                    // 'order_item_type' => $identity,
                    // 'order_item_product' => $data['produk']
                // );
                // $check_exists = $this->Order_model->check_data_exist_item($params_check);
                // $check_exists = false;
                // if($check_exists==false){

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
                            'id' => $set_data,
                            'order_id' => $data['order_id']
                        ); 
                    }else{
                        $return->message='Gagal menambahkan produk tambahan';
                    }
                // }else{
                    // $return->message='Produk sudah diinput';                    
                // }
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
                $order_id = $data['order_id'];
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
                        $return->result = array(
                            'order_id' => $order_id
                        );
                    }
                }            
            }
            if($action=='create-print-spoiler'){
                $this->load->model('Print_spoiler');
                $id=$data['id'];
                var_dump($id);
            }
            if($action=='update-item-product-price'){
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);

                $id = $data['order_item_id'];
                $qty= $data['qty'];
                $product_price_id = $data['product_price_id'];
                $product_price_name = $data['product_price_name'];                
                $product_price_price = $data['product_price_price'];                                
                $product_id = $data['product_id'];                

                //Set Price Other
                $set_total = $product_price_price * $qty;
                $params = array(
                    'order_item_qty' => $qty,
                    'order_item_price' => $product_price_price,
                    'order_item_total' => $set_total,
                    'order_item_product_price_id' => $product_price_id
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
                    $return->message='Harga '.$product_price_name.' terpasang';
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
                $order_id = $data['order_id'];
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
                    $return->result = array(
                        'order_id' => $order_id
                    );
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
                    'category_flag' => 1,
                    'category_branch_id' => $session_branch_id
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
            if($action=='load-product-tab-detail'){
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

                /*
                if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                    $params['other_column'] = $this->input->post('other_column');
                }
                */
                $start = 0;
                $limit = 100;
                $order = 'product_name';
                $order = 'product_name';
                $dir = 'ASC';
                $category_id = $data['category_id'];
                if($category_id > 0){
                    $params_datatable = array(
                        'product_flag' => 1,
                        'product_type' => 1, //Barang
                        'product_category_id' => $category_id, //Product Categories
                        'product_branch_id' => $session_branch_id
                    );
                }else if($category_id == -1){
                    $params_datatable = array(
                        'product_flag' => 1,
                        'product_type' => 1, //Barang
                        'product_price_promo >' => 0, //Product Categories
                        'product_branch_id' => $session_branch_id                            
                    );
                }else{                    
                    $params_datatable = array(
                        'product_flag' => 1,
                        'product_type' => 1, //Barang
                        'product_category_id !=' => 0,
                        'product_branch_id' => $session_branch_id                            
                        // 'product_ref_id ' => $reference_id //Product Categories
                    );
                }

                // var_dump($params_datatable,$search,$limit,$start,$order,$dir);die;
                $datas = $this->Produk_model->get_all_produks($params_datatable, $search, $limit, $start, $order, $dir);
                $datas_count = $this->Produk_model->get_all_produks_count($params_datatable);
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

            //Payment
            if($action=='load-unpaid-order'){

                $limit = 100;
                $start = 0;
                $order = 'order_date';
                $dir = 'DESC';
                $search = null;
                // $search = [];
                // if ($this->input->post('search')['value']) {
                //     $s = $this->input->post('search')['value'];
                //     foreach ($columns as $v) {
                //         $search[$v] = $s;
                //     }
                // }
                $params_request = array(
                    'orders.order_type' => $identity,
                    'orders.order_flag' => 0,
                    'orders.order_branch_id' => $session_branch_id
                );
                $datas = $this->Order_model->get_all_orders($params_request, $search, $limit, $start, $order, $dir);
                $datas_count = $this->Order_model->get_all_orders_count($params_request);
                
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
            if($action=='read-order'){
                // $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);     
                // $data['id'] = $this->input->post('id');           
                $datas = $this->Order_model->get_order($data['id']);
                $params = array(
                    'order_item_order_id' => $data['id']
                );
                $get_data_items = $this->Order_model->get_all_order_items($params,null,100,0,'order_item_id','asc');
                if($datas==true){

                    $subtotal = 0;
                    $total_diskon = 0;
                    $total= 0;

                    foreach($get_data_items as $v){
                        $data_items[] = array(
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
                if(isset($data_items)){ //Data exist
                    $data_source=$data_items; $total=count($data_items);
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                    $return->total_produk=count($data_items);
                    $return->subtotal=number_format($subtotal,0,'.',',');
                    $return->total_diskon=number_format($total_diskon,0,'.',',');
                    $return->total=number_format($subtotal-$total_diskon,0,'.',',');      
                    $return->total_dp = number_format($datas['order_with_dp'],0,'.',',');   
                    $return->total_grand = ($subtotal-$total_diskon)-$datas['order_with_dp'];
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                    $return->total_records = $total;                 
                    $return->status=1;
                    $return->message='Success';
                    $return->result=$datas;
                    $return->result_item = $data_items;
                }                
            }   
            if($action=='create-item-payment'){

                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $params_items = array(
                    'order_flag' => 3
                );
                $this->Order_model->update_order($id,$params_items);
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
            }              
            if($action=='delete-item-payment'){
                $id = $this->input->post('id');
                $ref = $this->input->post('ref');        
                $number = $this->input->post('number');                                
                // $flag = $this->input->post('flag');
                // die;
                // if($flag==1){
                //     $msg='aktifkan transaksi '.$nama;
                //     $act=7;
                // }else{
                //     $msg='nonaktifkan transaksi '.$nama;
                //     $act=8;
                // }

                // $set_data=$this->Order_model->update_order_item($id,array('order_item_flag'=>0));
                $params = array(
                    'order_flag' => 0
                );
                $set_data=$this->Order_model->update_order($id,$params);                
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
                    $return->message='Berhasil menghapus '.$ref;
                }                
            }          
            if($action=='check-payment-item'){
                // $get_data = $this->Order_model->check_unsaved_order_item($session['user_data']['user_id']);
                $params = array(
                    'order_flag' => 3,
                    'order_user_id' => $session_user_id,
                    'order_branch_id' => $session_branch_id
                );
                $get_data = $this->Order_model->get_all_orders($params,null,100,0,'order_number','asc');             
                // var_dump($get_data);die;   
                if(!empty($get_data)){
                    $subtotal = 0;
                    $down_payment = 0;
                    $total_diskon = 0;
                    $total= 0;                        
                    foreach ($get_data as $h) {                    
                        $order_item_list = $this->Order_model->get_all_order_items(array('order_item_order_id'=>$h['order_id']),null,100,0,'order_item_id','asc');
                        $data_items = array();
                        foreach($order_item_list as $v){
                            $data_items[] = array(
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
                            // $subtotal=$subtotal+$v['order_item_total'];
                        }

                        $datas[] = array(
                            'order_id' => $h['order_id'],
                            'order_number' => $h['order_number'],
                            'order_date' => $h['order_date'],
                            'order_total' => $h['order_subtotal'],
                            'order_total_down_payment' => $h['order_with_dp'],
                            'order_total_grand' => $h['order_subtotal']-$h['order_with_dp'],                            
                            'ref_name' => $h['ref_name'],
                            'order_items' => $data_items
                        );
                        $subtotal = $subtotal + $h['order_subtotal'];
                        $down_payment = $down_payment + $h['order_with_dp'];
                        $order_ids[] = $h['order_id'];
                    }
                    $order_list_id = implode(', ', $order_ids);


                    /*
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
                    */
                    // var_dump($datas);die;

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
                        $return->total_down_payment=number_format($down_payment,0,'.',',');                        
                        $return->total_diskon=number_format($total_diskon,0,'.',',');
                        $return->total=number_format(($subtotal-$total_diskon)-$down_payment,0,'.',',');
                        $return->order_list_id = $order_list_id;
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;
                    }                    
                    $return->status=1;
                    $return->message='Terdapat payment temporary';
                }else{
                    $return->message='Tidak ada payment temporary';
                }
            }               
            if($action=='create-payment'){
                $next=true;
                $type = $data['method']; //1=Tunai, 3=Debit/Kredit, 5=Digital

                $generate_nomor = $this->request_number_for_transaction(2);
                if($type==1){
                    $params = array(
                        'trans_type' => 2,
                        'trans_contact_id' => $data['contact'],
                        'trans_paid_type' => $type,
                        // 'trans_card_number' => $data['card_number'], 
                        // 'trans_card_bank_name' => $data['card_bank'],
                        // 'trans_card_account_name' => $data['card_name'],
                        // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                        // 'trans_card_type' => $data['card_type'],
                        // 'trans_digital_provider' => $data['digital_type'],
                        'trans_number' => $generate_nomor,
                        'trans_date' => date('Ymdhis'),
                        // 'trans_note' => $data['note'],
                        'trans_user_id' => $session['user_data']['user_id'],
                        'trans_date_created' => date('Ymdhis'),
                        'trans_date_updated' => date('Ymdhis'),
                        'trans_flag' => 1,
                        'trans_total' => str_replace(',','',$data['total']),
                        // 'trans_discount' => $data['discount'],
                        'trans_received' => str_replace(',','',$data['received']),
                        'trans_change' => str_replace(',','',$data['change']),
                    );
                }
                else if(($type==3) or ($type==4)){
                    $params = array(
                        'trans_type' => 2,
                        'trans_contact_id' => $data['contact'],
                        'trans_paid_type' => $type,
                        'trans_card_number' => $data['card_number'], 
                        'trans_card_bank_name' => $data['card_bank'],
                        'trans_card_account_name' => $data['card_name'],
                        'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                        'trans_card_type' => $data['card_type'],
                        // 'trans_digital_provider' => $data['digital_type'],
                        'trans_number' => $generate_nomor,
                        'trans_date' => date('Ymdhis'),
                        'trans_note' => $data['note'],
                        'trans_user_id' => $session['user_data']['user_id'],
                        'trans_date_created' => date('Ymdhis'),
                        'trans_date_updated' => date('Ymdhis'),
                        'trans_flag' => 1,
                        'trans_total' => str_replace(',','',$data['total']),
                        // 'trans_discount' => $data['discount'],
                        'trans_received' => str_replace(',','',$data['received']),
                        'trans_change' => str_replace(',','',$data['change']),
                    );
                }                    
                else if($type==5){
                    $trans_fee = 0;
                    if($data['digital_type']=='ShopeePAY'){
                        $trans_fee = str_replace(',','',$data['total']) - str_replace(',','',$data['total_before_fee']);
                    }
                    $params = array(
                        'trans_type' => 2,
                        'trans_contact_id' => $data['contact'],
                        'trans_paid_type' => $type,
                        // 'trans_card_number' => $data['card_number'], 
                        // 'trans_card_bank_name' => $data['card_bank'],
                        // 'trans_card_account_name' => $data['card_name'],
                        // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                        // 'trans_card_type' => $data['card_type'],
                        'trans_digital_provider' => $data['digital_type'],
                        'trans_number' => $generate_nomor,
                        'trans_date' => date('Ymdhis'),
                        'trans_note' => $data['note'],
                        'trans_user_id' => $session['user_data']['user_id'],
                        'trans_date_created' => date('Ymdhis'),
                        'trans_date_updated' => date('Ymdhis'),
                        'trans_flag' => 1,
                        'trans_total' => str_replace(',','',$data['total']),
                        // 'trans_discount' => $data['discount'],
                        'trans_received' => str_replace(',','',$data['received']),
                        'trans_change' => str_replace(',','',$data['change']),
                        'trans_fee' => $trans_fee                    
                    );
                }
                else{
                    $return->message='Harap pilih jenis pembayaran';
                    $next=false;
                }

                if($next==true){
                    //Check Data Exist
                    $params_check = array(
                        'trans_number' => $generate_nomor,
                        'trans_type' => 2,
                        'trans_branch_id' => $session_branch_id
                    );
                    $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Transaksi_model->add_transaksi($params);
                        if($set_data==true){
                            $trans_id = $set_data;
                            
                            //Update Flag is Payment
                            $list = $data['order_list_id'];
                            $list_explode = explode(', ', $list);
                            foreach($list_explode as $k => $v){
                                // echo "AS ".$v;
                                $params = array(
                                    'order_flag' => 1,
                                    'order_trans_id' => $trans_id
                                );
                                $id = $v;
                                $this->Order_model->update_order($id,$params);

                                //Prepare Recipe
                                //Get Order Item by Id
                                $params_order_item = array(
                                    'order_item_order_id' => $id
                                );
                                $get_order_item = $this->Order_model->get_all_order_items($params_order_item,null,null,null,null,null);
                                foreach($get_order_item as $r){
                                    $product_id = $r['order_item_product_id'];
                                    $params_recipe = array(
                                        'recipe_product_id' => $product_id
                                    );
                                    $check_has_recipe = $this->Product_recipe_model->get_all_recipe_count($params_recipe); 
                                    // var_dump($check_has_recipe,',<br>');
                                    if($check_has_recipe > 0){ //Found Recipe On Product
                                        $get_recipe = $this->Product_recipe_model->get_all_recipe($params_recipe,null,null,null,null,null);
                                        foreach($get_recipe as $g){

                                            $recipe_product_id = $g['recipe_product_id_child'];
                                            $recipe_unit = $g['recipe_unit'];
                                            $recipe_qty = $g['recipe_qty'];

                                            $set_recipe_qty = $recipe_qty * $r['order_item_qty'];
                                            
                                            $params_add_recipe_to_order = array(
                                                'order_item_recipe_order_item_id' => $r['order_item_id'],
                                                // 'order_item_id_order' => $data['order_id'],
                                                // 'order_item_id_order_detail' => $data['order_detail_id'],
                                                'order_item_product_id' => $recipe_product_id,
                                                // 'order_item_id_lokasi' => $data['lokasi_id'],
                                                'order_item_date' => date("YmdHis"),
                                                'order_item_unit' => $recipe_unit,
                                                'order_item_qty' => $set_recipe_qty,
                                                // 'order_item_price' => $data['harga'],
                                                // 'order_item_keluar_qty' => $data['qty'],
                                                // 'order_item_keluar_harga' => $data['harga'],
                                                'order_item_type' => '6', //Opname
                                                // 'order_item_discount' => 0,
                                                // 'order_item_total' => $total,
                                                'order_item_date_created' => date("YmdHis"),
                                                'order_item_date_updated' => date("YmdHis"),
                                                'order_item_user_id' => $session_user_id,
                                                'order_item_branch_id' => $session_branch_id,
                                                'order_item_flag' => 0
                                            );
                                            $this->Order_model->add_order_item($params_add_recipe_to_order);                
                                        }
                                    }

                                }                                
                            }                     
                            // $trans_list = $data['order_list'];
                            // foreach($trans_list as $index => $value){
                                
                            //     $params_update_trans_item = array(
                            //         'order_item_order_id' => $trans_id,
                            //         'order_item_flag' => 1                                
                            //     );
                            //     $this->Order_model->update_order_item($value,$params_update_trans_item);
                            // }

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
                                'trans_id' => $trans_id,
                                'trans_number' => $generate_nomor
                            ); 
                        }
                    }else{
                        $return->message='Nomor sudah digunakan';                    
                    }               
                } 
            }      
            if($action=='create-print-payment-spoiler'){
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
    function prints($id){        
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
        $data['branch'] = $this->Branch_model->get_branch($data['header']['user_branch_id']);

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

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'content' => $data['content'],
            'footer' => ''
        );

        // echo json_encode($data['result']);die;

        //Set Layout From Order Type
        if($data['header']['order_type']==1){
            $data['title'] = 'Purchase Order - '.$data['header']['order_number'];
            $this->load->view('prints/purchase_order',$data);
        }   
        else if($data['header']['order_type']==2){
            $data['title'] = 'Sales Order - '.$data['header']['order_number'];
            $this->load->view('prints/sales_order',$data);
        }           
        else{
            // $this->load->view('prints/sales_order',$data);
        }                
    }        
    function print_order(){ //Not Used
        $id = $this->input->post('id');
        // echo 'ID '.$id; die;
        // $this->load->model('Print_spoiler_model');
        // $id=$data['id'];
        // var_dump($id);
        $data['title'] = 'Order';
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
        echo nl2br("ORDER"."\r\n");
        $content .= "ORDER\r\n";
        echo "\r\n";       
        echo nl2br($data['header']['order_number']."\r\n");
        $content .= $data['header']['order_number']."\r\n";
        echo nl2br(date("d-M-Y, H:i", strtotime($data['header']['order_date']))."\r\n");
        $content .= date("d-M-Y, H:i", strtotime($data['header']['order_date'])).", ".$data['header']['ref_name']."]\r\n";        
        echo nl2br($data['header']['ref_name']."\r\n");
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";        
        foreach($data['content'] AS $v){
            // echo $v['product_name']."         ".number_format($v['order_item_total'])."\r\n";
            echo nl2br($v['product_name']."\r\n");
            $content .= $v['product_name']."\r\n"; 
            echo nl2br("Rp. ".number_format($v['order_item_price'])." x ".$v['order_item_qty']." (Rp. ".number_format($v['order_item_total']).")"."\r\n");
            $content .= "Rp. ".number_format($v['order_item_price'])." x ".$v['order_item_qty']." (Rp. ".number_format($v['order_item_total']).")"."\r\n";            
            if(!empty($v['order_item_note'])){
                echo nl2br($v['order_item_note']."\r\n");
                $content .= $v['order_item_note']."\r\n";                
            }
            echo nl2br("\r\n");
            $content .= "\r\n";            
        }     
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";        

        if($data['header']['order_with_dp'] > 0){
            echo nl2br("Down Payment Rp. ".number_format($data['header']['order_with_dp'])."\r\n");
            $content .= "Down Payment Rp. ".number_format($data['header']['order_with_dp'])."\r\n";            
        }
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
    function print_payment($id){ //Not Used
        // $this->load->model('Print_spoiler_model');
        // $id=$data['id'];
        // var_dump($id);
        $data['title'] = 'Order';
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
        $params = array(
            'trans_id' => $id
        );

        $data['header'] = $this->Transaksi_model->get_all_transaksis($params,null,100,0,'trans_id','asc');
        $payment_type = $data['header'][0]['trans_paid_type'];

        if($payment_type==1){
            $paid_with = 'Cash';
        }else if($payment_type ==3){
            $paid_with = 'Credit Card';
        }else if($payment_type ==4){
            $paid_with = 'Debit Card';
        }else if($payment_type ==5){
            $paid_with = $data['header'][0]['trans_digital_provider'];
        }

        //Content
        $params = array(
            'order_trans_id' => $id
        );
        $search = '';
        $limit  = null;
        $start = 0;
        $order = 'order_date_created';
        $dir = 'ASC';

        $data['content'] = $this->Order_model->get_all_orders($params,$search,$limit,$start,$order,$dir);

        // echo json_encode($data['header']);die;
        // echo json_encode($data['content']);die;

        // echo "<img src='".base_url('assets/webarch/img/logo/foodpedia_print.png')."' style='width:150px;'>"."\r\n";
        echo nl2br("\r\n");
        echo nl2br("FOODPEDIA BANYUMANIK"."\r\n"); 
        $content .= "FOODPEDIA BANYUMANIK"."\r\n";
        echo nl2br("\r\n");     
        $content .= "\r\n";
        echo nl2br($data['header'][0]['trans_number']."\r\n");
        $content .= $data['header'][0]['trans_number']."\r\n";
        echo nl2br(date("d-M-Y, H:i", strtotime($data['header'][0]['trans_date']))."\r\n");
        $content .= date("d-M-Y, H:i", strtotime($data['header'][0]['trans_date']))."\r\n";
        // echo $data['header']['ref_name']."\r\n";
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";
        foreach($data['content'] AS $v){

            $order_id = $v['order_id'];
            echo nl2br($v['order_number'].' - '.$v['ref_name']."\r\n");
            $content .= $v['order_number'].' - '.$v['ref_name']."\r\n";            
            $data['order_item'] = $this->Order_model->get_all_order_items(array('order_item_order_id'=>$order_id),null,100,0,'order_item_id','desc');
            foreach($data['order_item'] AS $i){
                echo nl2br(" - ".$i['product_name']."\r\n");
                $content .= " - ".$i['product_name']."\r\n";
                echo nl2br("   Rp. ".number_format($i['order_item_price'])." x ".$i['order_item_qty']." (Rp. ".number_format($i['order_item_total']).")"."\r\n");
                if($i['order_item_discount'] > 0){
                    echo nl2br("Disc:".number_format($i['order_item_discount'])."\r\n");
                }
                $content .= "   Rp. ".number_format($i['order_item_price'])." x ".$i['order_item_qty']." (Rp. ".number_format($i['order_item_total']).")"."\r\n";                
            }
            echo nl2br("\r\n");
            $content .= "\r\n";
            echo nl2br("Subtotal Rp: ".number_format($v['order_subtotal'])."\r\n");
            $content .= "Subtotal Rp: ".number_format($v['order_subtotal'])."\r\n";            
            if($v['order_with_dp'] > 0){
                echo nl2br("DP Rp. ".number_format($v['order_with_dp'])."\r\n");                
                $content .= "DP Rp. ".number_format($v['order_with_dp'])."\r\n";
            }
            echo nl2br("\r\n");
            $content .= "\r\n";
        }     
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";
        echo nl2br("Pembayaran: ".$paid_with."\r\n");
        $content .= "Pembayaran: ".$paid_with."\r\n";
        echo nl2br("Total: Rp. ".number_format($data['header'][0]['trans_total'])."\r\n");
        $content .= "Total: Rp. ".number_format($data['header'][0]['trans_total'])."\r\n";
        echo nl2br("Bayar: Rp. ".number_format($data['header'][0]['trans_received'])."\r\n");
        $content .= "Bayar: Rp. ".number_format($data['header'][0]['trans_received'])."\r\n";        
        echo nl2br("Kembali: Rp. ".number_format($data['header'][0]['trans_change'])."\r\n");
        $content .= "Kembali: Rp. ".number_format($data['header'][0]['trans_change'])."\r\n";                
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";        
        echo nl2br("Terima Kasih atas Kedatangannya"."\r\n");
        $content .= "Terima Kasih atas Kedatangannya"."\r\n";        
        echo nl2br("Di tunggu orderan berikutnya :)"."\r\n");
        $content .= "Di tunggu orderan berikutnya :)"."\r\n";        
        echo nl2br("\r\n");
        $content .= "\r\n";
        echo nl2br("\r\n");
        $content .= "\r\n";        

        $params = array(
            'spoiler_source_table' => 'orders',
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