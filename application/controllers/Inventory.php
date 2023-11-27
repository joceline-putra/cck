<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends MY_Controller{
    var $menu_id = 41;
    var $folder_location = array(
        '0' => array(
            'parent_id' => 41,            
            'title' => 'Statistik',
            'view' => 'layouts/admin/menu/inventory/statistic',
            'javascript' => 'layouts/admin/menu/inventory/statistic_js'
        ),        
        '6' => array( //Stok Opname
            'parent_id' => 41,            
            'title' => 'Stok Opname',
            'view' => 'layouts/admin/menu/inventory/opname',
            'javascript' => 'layouts/admin/menu/inventory/opname_js'
        ),        
        '7' => array( //Stok Opname Minus
            'parent_id' => 41,            
            'title' => 'Stok Opname Minus',
            'view' => 'layouts/admin/menu/inventory/opname_minus',
            'javascript' => 'layouts/admin/menu/inventory/opname_minus_js'
        ),
        '8' => array( //Mutasi Stok
            'parent_id' => 41,            
            'title' => 'Transfer Stok',
            'view' => 'layouts/admin/menu/inventory/transfer',
            'javascript' => 'layouts/admin/menu/inventory/transfer_js'
        ),        
        '99' => array( //Pemakaian Barang
            'parent_id' => 41,            
            'title' => 'Pemakaian Produk',
            'view' => 'layouts/admin/menu/inventory/goods_out',
            'javascript' => 'layouts/admin/menu/inventory/goods_out_js'
        ),
        '10' => array( //Pemasukan Barang
            'parent_id' => 41,            
            'title' => 'Pemasukan Produk',
            'view' => 'layouts/admin/menu/inventory/goods_in',
            'javascript' => 'layouts/admin/menu/inventory/goods_in_js'
        ),
        '9' => array( //Pemakaian Produk Cabang
            'parent_id' => 41,            
            'title' => 'Pemakaian Produk Cabang',
            'view' => 'layouts/admin/menu/inventory/goods_out_request',
            'javascript' => 'layouts/admin/menu/inventory/goods_out_request_js'
        ),        
    );        
    function __construct(){
        parent::__construct();
        $this->print_directory = 'layouts/admin/menu/prints/';
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('Aktivitas_model');
        $this->load->model('User_model');    
        $this->load->model('Menu_model');       
        $this->load->model('Produk_model');                   
        $this->load->model('Satuan_model');
        $this->load->model('Referensi_model');          
        $this->load->model('Transaksi_model');
        $this->load->model('Journal_model');        
        $this->load->model('Order_model');      
        $this->load->model('Print_spoiler_model');  
        $this->load->model('Branch_model'); 
        $this->load->model('Type_model');               
        
        $this->print_to         = 0; //0 = Local, 1 = Bluetooth    
        $this->whatsapp_config  = 1;      
        $this->module_approval   = 0; //Approval
        $this->module_attachment = 0; //Attachment             
    }
    function index(){
        $data['identity'] = 6;
        $data['session'] = $this->session->userdata();
        $data['usernya'] = $this->User_model->get_all_user();
        // var_dump($data['usernya']);die;        
        
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
                
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d");         
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        $data['title'] = 'Stok Opname';
        $data['_view'] = 'gudang/opname';
        // $this->load->view('layouts/admin/index',$data);
        // $this->load->view('gudang/opname_js.php',$data);        
    }
    function pages($identity){

        $data['session'] = $this->session->userdata();     
        // $session = $this->session->userdata();
        // $session_branch_id = $session['user_data']['branch']['id'];
        // $session_user_id = $session['user_data']['user_id'];        
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        //Sub Navigation
        $params_menu = array(
            'menu_parent_id' => $this->folder_location[$identity]['parent_id'],
            'menu_flag' => 1
        );
        $get_menu = $this->Menu_model->get_all_menus($params_menu,null,null,null,'menu_sorting','asc');
        $data['navigation'] = !empty($get_menu) ? $get_menu : [];
        // var_dump($data['navigation']);die;
                
        $data['identity'] = $identity;
        $data['post_order'] = 0;
        $data['operator'] = null;
        $data['whatsapp_config'] = $this->whatsapp_config;
        $data['module_approval']    = $this->module_approval;
        $data['module_attachment'] = $this->module_attachment;        
        $data['print_to']        =  $this->print_to; //0 = Local, 1 = Bluetooth        
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['_view'] = $this->folder_location[$identity]['view'];
        $file_js = $this->folder_location[$identity]['javascript'];

        // var_dump($data['satuan']);die;
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        //Date Now
        $datenow =date("d-m-Y"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        
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

            $config_post_to_journal = true; //True = Call SP_JOURNAL_FROM_TRANS, False = Dsabled 
            $set_transaction = $identity;

            //Transaksi Tipe
            if($identity == 6){ //Stok Opname
                $set_tipe = 6;
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
            }else if(($identity == 9) or ($identity == 99)){ //Pemakaian Barang
                $set_tipe = 9;
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
            }else if($identity == 10){ //Pemasukan Barang
                $set_tipe = 10;
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

            switch($action){
                case "load":
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
                    $date_start     = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end       = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $location_from  = !empty($this->input->post('location_from')) ? $this->input->post('location_from') : 0;
                    $location_to    = !empty($this->input->post('location_to')) ? $this->input->post('location_to') : 0;

                    $params_datatable = array(
                        'trans.trans_date >' => $date_start,
                        'trans.trans_date <' => $date_end,
                        'trans.trans_type' => intval($identity),
                        'trans.trans_flag <' => 4,
                        'trans.trans_branch_id' => intval($session_branch_id)
                    );
                    if(intval($kontak) > 0){
                        $params_datatable = array(
                            'trans.trans_date >' => $date_start,
                            'trans.trans_date <' => $date_end,
                            'trans.trans_type' => intval($identity),
                            'trans.trans_flag <' => 4,
                            'trans.trans_branch_id' => intval($session_branch_id),
                            'trans.trans_contact_id' => intval($kontak)                   
                        );                    
                    }

                    if(intval($location_from) > 0){
                        $params_datatable['trans.trans_location_id'] = intval($location_from);
                    }
                    
                    if(intval($location_to) > 0){
                        $params_datatable['trans.trans_location_to_id'] = intval($location_to);
                    }
                    /*
                        9 Pemakaian Produk
                    */
                    $datas_count = $this->Transaksi_model->get_all_transaksis_count($params_datatable,$search);
                    // $datas_count = $this->Order_model->get_all_orders_count($params_datatable, $search, $limit, $start, $order, $dir);                                 
                    if($datas_count > 0){ //Data exist
                        $datas = $this->Transaksi_model->get_all_transaksis($params_datatable, $search, $limit, $start, $order, $dir);
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$datas_count;
                        $return->result=$datas;        
                    }else{
                        $return->message='No data'; 
                        $return->total_records=$datas_count;
                        $return->result=0;    
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->params             = $params_datatable;
                    break;
                case "load_goods_out":
                    $limit = $this->input->post('length');
                    $start = $this->input->post('start');
                    $order = $columns[$this->input->post('order')[0]['column']];
                    $dir = $this->input->post('order')[0]['dir'];
                    $user = $this->input->post('user');
                    $search = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }
                    $date_start     = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end       = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $branch_id_2  = !empty($this->input->post('branch_id_2')) ? $this->input->post('branch_id_2') : 0;
                    // $location_to    = !empty($this->input->post('location_to')) ? $this->input->post('location_to') : 0;

                    $params_datatable = array(
                        'trans.trans_date >' => $date_start,
                        'trans.trans_date <' => $date_end,
                        'trans.trans_type' => intval($identity),
                        'trans.trans_flag <' => 4,
                        'trans.trans_branch_id' => intval($session_branch_id)
                    );
                    if(intval($user) > 0){
                        $params_datatable = array(
                            'trans.trans_date >' => $date_start,
                            'trans.trans_date <' => $date_end,
                            'trans.trans_type' => intval($identity),
                            'trans.trans_flag <' => 4,
                            'trans.trans_branch_id' => intval($session_branch_id),
                            'trans.trans_user_id' => intval($user)                   
                        );                    
                    }

                    if(intval($branch_id_2) > 0){
                        $params_datatable['trans.trans_branch_id_2'] = intval($branch_id_2);
                    }
                    
                    // if(intval($location_to) > 0){
                    //     $params_datatable['trans.trans_location_to_id'] = intval($location_to);
                    // }

                    $datas_count = $this->Transaksi_model->get_all_transaksis_goods_out_count($params_datatable,$search);                                
                    if($datas_count > 0){
                        $datas = $this->Transaksi_model->get_all_transaksis_goods_out($params_datatable, $search, $limit, $start, $order, $dir);
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$datas_count;
                        $return->result=$datas;        
                    }else{
                        $return->message='No data'; 
                        $return->total_records=$datas_count;
                        $return->result=0;    
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->params             = $params_datatable;
                    break;                        
                /* CRUD orders */
                case "create":
                    $generate_nomor = $this->request_number_for_transaction($identity);

                    $trans_number = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $trans_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $trans_ref_number = !empty($data['ref_number']) ? $data['ref_number'] : null;
                    $trans_note = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $trans_contact_address = !empty($data['alamat']) ? $data['alamat'] : null;                
                    $trans_contact_phone = !empty($data['telepon']) ? $data['telepon'] : null;
                    $trans_contact_email = !empty($data['email']) ? $data['email'] : null;
                    $trans_location = !empty($data['gudang']) ? $data['gudang'] : null;
                    $trans_location_to = !empty($data['gudang_to']) ? $data['gudang_to'] : null;                

                    $jam = date('H:i:s');
                    
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $tgl_tempo = isset($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : $tgl;
                    
                    $set_date = $tgl.' '.$jam;
                    $set_date_due = $tgl_tempo.' '.$jam;

                    //JSON Strigify Post
                    $params = array(
                        'trans_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'trans_contact_id' => !empty($trans_contact) ? $trans_contact : null,
                        'trans_number' => !empty($trans_number) ? $trans_number : null,
                        'trans_date' => $set_date,
                        'trans_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'trans_discount' => !empty($data['diskon']) ? $data['diskon'] : 0,
                        'trans_total' => !empty($data['total']) ? $data['total'] : 0,
                        'trans_change' => !empty($data['change']) ? $data['change'] : 0,
                        'trans_received' => !empty($data['received']) ? $data['received'] : 0,
                        'trans_fee' => !empty($data['fee']) ? $data['fee'] : 0,
                        'trans_note' => !empty($trans_note) ? $trans_note : null,
                        'trans_date_created' => date("YmdHis"),
                        'trans_date_updated' => date("YmdHis"),
                        'trans_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        'trans_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                        'trans_flag' => 0,
                        // 'trans_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        // 'trans_with_dp' => !empty($data['total_down_payment']) ? str_replace(',','',$data['total_down_payment']) : null,
                        'trans_location_id' => !empty($trans_location) ? $trans_location : null,
                        'trans_location_to_id' => !empty($trans_location_to) ? $trans_location_to : null,
                        'trans_ref_number' => !empty($trans_ref_number) ? $trans_ref_number : null,
                        'trans_date_due' => !empty($set_date_due) ? $set_date_due : null,
                        'trans_contact_address' => !empty($trans_contact_address) ? $trans_contact_address : null,
                        'trans_contact_phone' => !empty($trans_contact_phone) ? $trans_contact_phone : null,
                        'trans_contact_email' => !empty($trans_contact_email) ? $trans_contact_email : null,
                        // 'trans_paid' => !empty($data['paid']) ? $data['paid'] : null,
                        'trans_paid_type' => !empty($data['paid_type']) ? $data['paid_type'] : 0,
                        'trans_bank_name' => !empty($data['bank_name']) ? $data['bank_name'] : null,
                        'trans_bank_number' => !empty($data['bank_number']) ? $data['bank_number'] : null,  
                        'trans_card_bank_number' => !empty($data['card_bank_number']) ? $data['card_bank_number'] : null,
                        'trans_card_bank_name' => !empty($data['card_bank_name']) ? $data['card_bank_name'] : null,
                        'trans_card_account_name' => !empty($data['card_account_name']) ? $data['card_account_name'] : null,
                        'trans_card_expired' => !empty($data['card_expired']) ? $data['card_expired'] : null,
                        'trans_card_type' => !empty($data['card_type']) ? $data['card_type'] : null,
                        'trans_digital_provider' => !empty($data['digital_provider']) ? $data['digital_provider'] : null,
                        'trans_session' => $this->random_code(20)
                    );

                    //Check Data Exist
                    $params_check = array(
                        'trans_number' => $trans_number,
                        'trans_branch_id' => $session_branch_id
                    );
                    $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Transaksi_model->add_transaksi($params);
                        if($set_data==true){
                            $trans_id = $set_data;
                            $trans_list = $data['trans_list'];
                            foreach($trans_list as $index => $value){
                                
                                $params_update_trans_item = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_date' => $set_date,
                                    // 'trans_item_location_id' => $trans_location,
                                    'trans_item_flag' => 1                                
                                );
                                $this->Transaksi_model->update_transaksi_item($value,$params_update_trans_item);

                                //For Transfer Stok
                                if($identity==5){
                                    $get_trans_item_list = $this->Transaksi_model->get_transaksi_item($value);
                                    $where = array(
                                        'trans_item_session' => $get_trans_item_list['trans_item_session']
                                    );
                                    $params_update_trans_item = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_date' => $set_date,
                                        'trans_item_flag' => 1                
                                    );
                                    $this->Transaksi_model->update_transaksi_item_custom($where,$params_update_trans_item);                                
                                }
                            }

                            if(intval($trans_id) > 0){
                                /*
                                if(intval($identity) == 1){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );
                                    $params_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 1
                                    );                            
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                                    $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price']; 
                                    $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                                    $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                    $params_total = array(
                                        'trans_total_dpp' => $set_total
                                    );
                                }
                                if((intval($identity) == 2) or (intval($identity) == 8)){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );
                                    $params_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 1
                                    );                            
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                                    $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_ppn);

                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price']; 
                                    $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];

                                    $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                    $params_total = array(
                                        'trans_total_dpp' => $set_total
                                    );
                                }
                                if((intval($identity) == 5)){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );                      
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price']; 
                                    // $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];
                                    $set_total = $total_no_ppn;
                                    $params_total = array(
                                        'trans_total' => $set_total
                                    );                                
                                }
                                if(intval($identity) == 6){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );
                                    // $params_ppn = array(
                                    //     'trans_item_trans_id' => $trans_id,
                                    //     'trans_item_ppn' => 1
                                    // );                            
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                                    // $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price']; 
                                    // $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                                    // $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                    $set_total = $total_no_ppn;
                                    $params_total = array(
                                        'trans_total_dpp' => $set_total
                                    );
                                }
                                $update_trans = $this->Transaksi_model->update_transaksi($trans_id,$params_total);
                                */

                                //Set To Journal
                                if($config_post_to_journal==true){
                                    $operator = $this->journal_for_transaction('create',$trans_id);
                                    $return->trans_id = 1;
                                }
                            }
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 2,
                                'activity_table' => 'trans',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $trans_number,                        
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
                                'trans_id' => $trans_id,
                                'trans_number' => $trans_number
                            ); 
                        }
                    }else{
                        $return->message='Nomor sudah digunakan';                    
                    }
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $data['id'] = $this->input->post('id');           
                    $datas = $this->Transaksi_model->get_transaksi($data['id']);
                    if($datas==true){
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 3,
                            'activity_table' => 'trans',
                            'activity_table_id' => $data['id'],                            
                            'activity_text_1' => $set_transaction,
                            'activity_text_2' => $datas['trans_number'],                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);    
                        /* End Activity */
                    }

                    if($identity==5){ //Transfer Stock Return Location TO
                        $get_location_to = $this->Lokasi_model->get_lokasi($datas['trans_location_to_id']);
                        $return->location_to = array(
                            'location_id' => $get_location_to['location_id'],
                            'location_code' => $get_location_to['location_code'],
                            'location_name' => $get_location_to['location_name']
                        );
                    }
                    //Get Order on Trans Table
                    $params = array(
                        'trans_item_trans_id' => $data['id']
                    );
                    $get_trans = $this->Transaksi_model->get_all_transaksi_items_count($params);

                    $return->status = 1;
                    $return->message = 'Success';
                    $return->result = $datas;
                    $return->result_trans = $get_trans;
                    break;
                case "update":
                    $next = true;
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];

                    //Get Data Before
                    $get_data = $this->Transaksi_model->get_transaksi($id);
                    
                    //Fetch POST
                    $trans_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $trans_ref_number = !empty($data['nomor_ref']) ? $data['nomor_ref'] : null;
                    $trans_note = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $trans_contact_address = !empty($data['alamat']) ? $data['alamat'] : null;                
                    $trans_contact_phone = !empty($data['telepon']) ? $data['telepon'] : null;
                    $trans_contact_email = !empty($data['email']) ? $data['email'] : null;
                    $trans_location = !empty($data['gudang']) ? $data['gudang'] : null;
                    $trans_discount = !empty($data['diskon']) ? $data['diskon'] : 0;
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = substr($get_data['trans_date'],10,9);
                    $set_date = $tgl.$jam;

                    $tgl_tempo = isset($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : $tgl;
                    $set_date_due = $tgl_tempo.$jam;

                    //JSON Strigify Post
                    $params = array(
                        // 'trans_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'trans_contact_id' => !empty($trans_contact) ? $trans_contact : null,
                        // 'trans_number' => !empty($trans_number) ? $trans_number : null,
                        'trans_date' => $set_date,
                        // 'trans_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'trans_discount' => $trans_discount,
                        // 'trans_total' => !empty($data['total']) ? $data['total'] : null,
                        'trans_note' => !empty($trans_note) ? $trans_note : null,
                        // 'trans_date_created' => date("YmdHis"),
                        'trans_date_updated' => date("YmdHis"),
                        // 'trans_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        // 'trans_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                        // 'trans_flag' => 0,
                        // 'trans_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        // 'trans_with_dp' => !empty($data['total_down_payment']) ? str_replace(',','',$data['total_down_payment']) : null,
                        'trans_location_id' => !empty($trans_location) ? $trans_location : null,
                        'trans_ref_number' => !empty($trans_ref_number) ? $trans_ref_number : null,
                        'trans_date_due' => !empty($set_date_due) ? $set_date_due : null,
                        'trans_contact_address' => !empty($trans_contact_address) ? $trans_contact_address : null,
                        'trans_contact_phone' => !empty($trans_contact_phone) ? $trans_contact_phone : null,
                        'trans_contact_email' => !empty($trans_contact_email) ? $trans_contact_email : null,
                        // 'trans_paid' => !empty($data['paid']) ? $data['paid'] : null,
                        'trans_paid_type' => !empty($data['paid_type']) ? $data['paid_type'] : 0,
                        // 'trans_bank_name' => !empty($data['bank_name']) ? $data['bank_name'] : null,
                        // 'trans_bank_number' => !empty($data['bank_number']) ? $data['bank_number'] : null,  
                        // 'trans_card_bank_number' => !empty($data['card_bank_number']) ? $data['card_bank_number'] : null,
                        // 'trans_card_bank_name' => !empty($data['card_bank_name']) ? $data['card_bank_name'] : null,
                        // 'trans_card_account_name' => !empty($data['card_account_name']) ? $data['card_account_name'] : null,
                        // 'trans_card_expired' => !empty($data['card_expired']) ? $data['card_expired'] : null,
                        // 'trans_card_type' => !empty($data['card_type']) ? $data['card_type'] : null,
                        // 'trans_digital_provider' => !empty($data['digital_provider']) ? $data['digital_provider'] : null                                                         
                    );                         
                    $params_items = array(
                        'trans_item_date' => $set_date, 
                        'trans_item_location_id' => $trans_location,
                        'trans_item_date_updated' => date("YmdHis")
                    );
                    $params_items_remove = array(
                        'trans_item_trans_id'=> $id,
                        'trans_item_branch_id' => $session_branch_id,
                        'trans_item_flag' => 0
                    );

                    if($next){
                        $params_check = array();
                        $get_journal = 0;
                        //Pembelian / Penjualan sudah pernah terjurnal
                        $trans_type = $get_data['trans_type'];
                        if(intval($trans_type == 1) or intval($trans_type == 2)){
                            if($trans_type == 1){
                                $set_trans_type = 10;
                            }elseif($trans_type == 2){
                                $set_trans_type = 11;
                            }
                            $params_check = array(
                                'journal_item_trans_id' => $id,
                                'journal_item_type' => $set_trans_type
                            );
                            $get_journal = $this->Journal_model->get_all_journal_item_custom_count($params_check,$search=null);
                        
                            //Beli dan Jual Sebagian di lunasi
                            if(intval($get_journal) > 0){

                                //Contact Has Replace
                                if($get_data['trans_contact_id'] != $trans_contact){
                                    $next=false;
                                    // $return->message='Kontak tidak boleh diganti';
                                    $return->message = 'Transaksi ini sudah dibayar sebagian, tidak dapat di perbarui';
                                } 
                            }
                        }
                        $return->info = array(
                            'contact' => array(
                                'contact_from' => $get_data['trans_contact_id'],
                                'contact_to' => $trans_contact
                            ),
                            'params_journal_check' => $params_check,
                            'journal' => $get_journal
                        );                    
                    }

                    if($next){
                        $set_update_trans               = $this->Transaksi_model->update_transaksi($id,$params);          
                        $set_update_trans_item          = $this->Transaksi_model->update_transaksi_item_by_trans_id($id,$params_items);
                        // $set_update_trans_item_flag_0   = $this->Transaksi_model->delete_transaksi_item_by_trans_id($params_items_remove);

                        $trans_id=$id;
                        if(intval($trans_id) > 0){
                            //Set To Journal
                            if($config_post_to_journal==true){
                                $operator = $this->journal_for_transaction('update',$trans_id);
                                $return->trans_id = 1;
                            }                    
                        }
                        if($set_update_trans==true){
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 4,
                                'activity_table' => 'trans',
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
                    break;    
                case "delete":
                    $trans_id = $this->input->post('id');
                    $number = $this->input->post('number');                               
                    // $flag = $this->input->post('flag');
                    $flag=4;
                    $next = true;
                    $set_data = false;
                    $message = 'Gagal menghapus';

                    //Check the Stock is available to delete
                    $params = array('trans_item_trans_id' => $trans_id);
                    $check_stock_on_trans_item = $this->Transaksi_model->get_all_transaksi_items($params,null,null,null,'trans_item_id','asc');
                    foreach($check_stock_on_trans_item as $v):
                        $set_trans_item_position = ($v['trans_item_position']==1) ? 2 : 1;
                        if(intval($set_trans_item_position) == 2){
                            
                            if($identity==5){//Transfer Stok
                                /*
                                $params_check = array(
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_ref' => $v['trans_item_ref'],
                                    'trans_item_position' => $set_trans_item_position
                                );
                                $check = $this->Transaksi_model->check_stock_is_available_to_delete($params_check);
                                if($check > 0){
                                    $message='Gagal dihapus, Stok sudah digunakan';
                                    $next=false;
                                }       
                                */     
                                $next=true;                
                            }else{
                                $params_check = array(
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_ref' => $v['trans_item_ref'],
                                    'trans_item_position' => $set_trans_item_position
                                );
                                $check = $this->Transaksi_model->check_stock_is_available_to_delete($params_check);
                                if($check > 0){
                                    $message='Gagal dihapus, Stok sudah digunakan';
                                    $next=false;
                                }
                            }
                        }
                    endforeach;

                    //Account Payable & Receivable
                    if($next){
                        $message='Check AP / AR';
                        $params = array(
                            'journal_item_trans_id' => $trans_id,
                            'journal_item_journal_id >' => 0
                        );
                        $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                        if(intval($check_trans_on_journal_item) > 0){
                            $message='Gagal, Transaksi ini sudah terbayar';
                            $next=false;
                            $set_data=false;
                        }
                    }
                    // var_dump($message);die;
                    if($next){
                        $message = $message.' Delete data';

                        //Set To Journal
                        if($config_post_to_journal==true){
                            $operator = $this->journal_for_transaction('delete',$trans_id);
                            $return->trans_id = 1;
                            $set_data = true;
                        } 

                        // $set_data=$this->Transaksi_model->update_transaksi($trans_id,array('trans_flag'=>$flag));
                        // $set_data=$this->Transaksi_model->update_transaksi_item_by_trans_id($trans_id,array('trans_item_flag'=>$flag));
                        // $set_data=$this->Transaksi_model->delete_transaksi($trans_id);
                        // $set_data=$this->Transaksi_model->delete_transaksi_item_by_trans_id(array('trans_item_trans_id'=>$trans_id));
                    }

                    if($next){
                        if($set_data==true){    
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 5,
                                'activity_table' => 'trans',
                                'activity_table_id' => $trans_id,                            
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $number,                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);    
                            /* End Activity */
                            $return->status=1;
                            $message='Berhasil menghapus '.$number;
                        }else{
                            $message=$message;
                        }      
                    }
                    $return->message=$message;
                    break;
                case "cancel":
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
                    break;
                case "journal":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $id = $this->input->post('id');           

                    if($identity == 9){ 
                        $set_identity = 20; 
                    }elseif($identity == 10){ 
                        $set_identity = 21; 
                    }
                    $params = array(
                        'journal_item_trans_id' => $id
                    );
                    $params['journal_item_type'] = $set_identity;
                    // var_dump($params);die;
                    $result = array();
                    $datas = $this->Journal_model->get_all_journal_item($params,null,null,null,'journal_item_credit','asc');
                    if($datas==true){
                        foreach($datas as $v):

                            $journal_item_note = '-';
                            if($v['journal_item_note'] != null){
                                $journal_item_note = $v['journal_item_note'];
                            }

                            // $journal_item_debit = (floatval($v['journal_item_debit']) > 0) : $v['journal_item_debit'] : '-';
                            // $journal_item_credit = (floatval($v['journal_item_credit']) > 0) : $v['journal_item_credit'] : '-';                        
                            
                            $result[] = array(
                                'journal_item_journal_id' => $v['journal_item_journal_id'],
                                'journal_item_date' => $v['journal_item_date'],
                                'journal_item_note' => $journal_item_note,
                                'journal_item_debit' => $v['journal_item_debit'],
                                'journal_item_credit' => $v['journal_item_credit'],
                                'account_id' => $v['account_id'],
                                'account_name' => $v['account_name'],
                                'account_code' => $v['account_code'],
                                'account_group' => $v['account_group'],
                                'account_group_sub' => $v['account_group_sub'],
                                'user_username' => $v['user_username']
                            );
                        endforeach;                    
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id, 
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 3,
                            'activity_table' => 'journals',
                            'activity_table_id' => $id,                            
                            'activity_text_1' => $set_transaction,
                            'activity_text_2' => $id,                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);    
                        /* End Activity */
                    }

                    //Get Order on Trans Table
                    // $params = array(
                        // 'trans_item_trans_id' => $data['id']
                    // );
                    // $get_trans = $this->Transaksi_model->get_all_transaksi_items_count($params);

                    $return->status = 1;
                    $return->message = 'Success';
                    $return->result = $result;
                    // $return->result_trans = $get_trans;
                    break;
                /* CRUD order_items */
                case "create-item":
                    $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    $data = json_decode($post_data, TRUE);
                    $trans_id = !empty($data['id']) ? $data['id'] : null;                
                    $tipe = !empty($data['tipe']) ? $data['tipe'] : null;
                    $produk = !empty($data['produk']) ? $data['produk'] : null;
                    $ppn = !empty($data['ppn']) ? $data['ppn'] : 0;                
                    $satuan = !empty($data['satuan']) ? $data['satuan'] : null;                
                    $harga = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                    $diskon = !empty($data['diskon']) ? str_replace(',','',$data['diskon']) : 0.00;                 
                    $qty = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                    $qty_kg = !empty($data['qty_kg']) ? $data['qty_kg'] : 0.00;  
                    $qty_pack = !empty($data['qty_pack']) ? $data['qty_pack'] : 0;                
                    $total = 0;
                    $ref_number = $this->random_code(6);
                    $flag = 0;

                    // $store_procedure_out = isset($data['sp']) ? $data['sp'] : true;       
                    $product_type = !empty($data['product_type']) ? intval($data['product_type']) : 1; //1 Barang, 2=Jasa          
                    $position = !empty($data['position']) ? intval($data['position']) : 1;
                    $location = !empty($data['lokasi']) ? $data['lokasi'] : null;
                    $location_to = !empty($data['lokasi_tujuan']) ? $data['lokasi_tujuan'] : null;                

                    // var_dump($store_procedure_out);die;

                    // $sp = !empty($data['sp']) ? $data['sp'] : true;

                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = date('H:i:s');
                    $set_date = $tgl.' '.$jam;

                    $total = $harga*$qty;
                    // if(intval($ppn) > 0){
                    //     $total = $total + ($total*0.1);
                    // }
                    $get_product = $this->Produk_model->get_produk($data['produk']);

                    if(intval($trans_id) > 0){
                        $flag = 1;
                    }
                    // echo 'Ganti SP Dulu'; die;
                    if($tipe == 10){
                        $params_items = array(
                            'trans_item_trans_id' => $trans_id,
                            // 'trans_item_id_order' => $data['order_id'],
                            // 'trans_item_id_order_detail' => $data['order_detail_id'],
                            'trans_item_product_id' => $produk,
                            // 'trans_item_id_lokasi' => $data['lokasi_id'],
                            'trans_item_date' => $set_date,
                            'trans_item_unit' => $satuan,
                            'trans_item_in_qty' => $qty,
                            // 'trans_item_qty_weight' => $qty_kg,                    
                            'trans_item_in_price' => $harga,
                            // 'trans_item_keluar_qty' => $data['qty'],
                            // 'trans_item_keluar_harga' => $data['harga'],
                            'trans_item_product_type' => $get_product['product_type'],
                            'trans_item_type' => $tipe,
                            'trans_item_discount' => 0,
                            'trans_item_total' => $total,
                            'trans_item_date_created' => date("YmdHis"),
                            'trans_item_date_updated' => date("YmdHis"),
                            'trans_item_user_id' => $session_user_id,
                            'trans_item_branch_id' => $session_branch_id,
                            'trans_item_flag' => $flag,
                            'trans_item_ref' => $ref_number,
                            'trans_item_ppn' => $ppn,
                            'trans_item_position' => $position,
                            'trans_item_pack' => $qty_pack
                        );
                    }else if ($tipe == 9){ //Pemakaian Barang
                        $params_items = array(
                            'trans_item_trans_id' => $trans_id,
                            // 'trans_item_id_order' => $data['order_id'],
                            // 'trans_item_id_order_detail' => $data['order_detail_id'],
                            'trans_item_product_id' => $produk,
                            // 'trans_item_location_id' => $location,
                            'trans_item_date' => $set_date,
                            'trans_item_unit' => $satuan,
                            // 'trans_item_in_qty' => $qty,                 
                            // 'trans_item_in_price' => $harga,
                            'trans_item_out_qty' => $qty,
                            'trans_item_out_price' => $harga,
                            'trans_item_product_type' => $get_product['product_type'],
                            'trans_item_type' => $tipe,
                            'trans_item_discount' => 0,
                            'trans_item_total' => $total,
                            'trans_item_date_created' => date("YmdHis"),
                            'trans_item_date_updated' => date("YmdHis"),
                            'trans_item_user_id' => $session_user_id,
                            'trans_item_branch_id' => $session_branch_id,
                            'trans_item_flag' => $flag,
                            // 'trans_item_ref' => $ref_number,
                            'trans_item_ppn' => $ppn,
                            'trans_item_position' => $position
                        );
                    }

                    //Check Data Exist Trans Item
                    $params_check = array(
                        'trans_item_type' => $identity,
                        'trans_item_product' => $data['produk']
                    );
                    // $check_exists = $this->Order_model->check_data_exist_item($params_check);
                    $check_exists = false;
                    if($check_exists==false){
                        if($tipe == 10){
                            $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                        }else if($tipe == 9){
                            $type           = $tipe;
                            $date           = !empty($set_date) ? $set_date : '';
                            $trans_id       = !empty($data['id']) ? $data['id'] : 0;
                            $branch_id      = $session_branch_id;
                            $product_id     = !empty($data['produk']) ? $data['produk'] : null;
                            $location_id    = !empty($data['lokasi']) ? $data['lokasi'] : null;
                            $product_unit   = !empty($data['satuan']) ? $data['satuan'] : null;
                            $out_qty        = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                            $out_price_sell = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                            $discount       = !empty($data['diskon']) ? str_replace(',','',$data['diskon']) : 0.00;
                            $ppn            = !empty($data['ppn']) ? $data['ppn'] : 0;
                            $ppn_value      = '0.00';
                            $note           = !empty($data['note']) ? $data['note'] : 0;
                            $user_id        = $session_user_id;
                            $flag           = 0;
                            // var_dump($trans_id,$out_qty,$discount,$out_price_sell);die;
                            $set_data = $this->trans_item_out($type,$date,$trans_id,$branch_id,$product_id,$location_id,
                                $product_unit,$out_qty,$out_price_sell,
                                $discount,$ppn,$ppn_value,$note,$user_id,$flag,0);
                        }else{
                            $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                        }
                        
                        if($set_data==true){

                            //Check the Product is Stock Card Mode ?
                            // $product_stock_mode = $get_product['product_with_stock']; 
                            // if($product_stock_mode == 1){ //Stock Card is Activated

                            //     $stock_start = $get_product['product_stock_start'];
                            //     $stock_in = $get_product['product_stock_in'];
                            //     $stock_out = $get_product['product_stock_out'];
                            //     $stock_end = $get_product['product_stock_end'];

                            //     $set_stock_out = $stock_out + 1;
                            //     $params_stock = array(
                            //         'product_stock_out' => $stock_out + 1
                            //     );
                            //     $this->Produk_model->update_produk($data['produk'],$params_stock);
                            // }
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 2,
                                'activity_table' => 'trans_items',
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

                        if(intval($trans_id) > 0){
                            /* Command Move to TRIGGER SQL
                            if(intval($identity) == 1){
                                $params_no_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 0
                                );
                                $params_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 1
                                );                            
                                $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                                $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                                $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price']; 
                                $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                                $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                $params_total = array(
                                    'trans_total' => $set_total
                                );
                            }
                            if(intval($identity) == 2){
                                $params_no_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 0
                                );
                                $params_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 1
                                );                            
                                $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                                $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_ppn);

                                $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price']; 
                                $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];

                                $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                $params_total = array(
                                    'trans_total' => $set_total
                                );
                            }                        
                            $update_trans = $this->Transaksi_model->update_transaksi($trans_id,$params_total);
                            */
                            $return->trans_id = 1;
                        }
                    }else{
                        $return->message='Produk sudah diinput';                    
                    }
                    break;
                case "read-item":
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
                    break;
                case "update-item":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    $tipe = !empty($data['tipe']) ? $data['tipe'] : null;
                    $produk = !empty($data['produk']) ? $data['produk'] : null;
                    $satuan = !empty($data['satuan']) ? $data['satuan'] : null;                
                    $harga = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                    $diskon = !empty($data['diskon']) ? $data['diskon'] : 0.00;                 
                    $qty = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                    $qty_kg = !empty($data['qty_kg']) ? $data['qty_kg'] : 0.00;            
                    $qty_pack = !empty($data['qty_pack']) ? $data['qty_pack'] : 0;                
                    $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;   
                    $ppn = !empty($data['ppn']) ? $data['ppn'] : 0;                                        
                    $total = 0;

                    // if(!empty($)){
                        // $harga = str_replace(',','',$harga);
                        // $qty = str_replace(',','',$qty);                    
                        $total = $harga*$qty;
                    // }
                    
                    $params = array(
                        'trans_item_id' => $id,
                        // 'trans_item_order_id' => $data['order_id'],
                        // 'trans_item_order_item_id' => $data['order_detail_id'],
                        'trans_item_product_id' => $produk,
                        // 'trans_item_location_id' => $data['lokasi_id'],
                        // 'trans_item_date' => date("YmdHis"),
                        'trans_item_unit' => $satuan,
                        'trans_item_in_qty' => $qty,
                        'trans_item_in_price' => $harga,
                        // 'trans_item_discount' => $data['masuk_harga'],
                        'trans_item_total' => $total,
                        'trans_item_note' => $keterangan,                                                            
                        // 'trans_item_type' => $data['tipe'],
                        'trans_item_date_updated' => date("YmdHis"),
                        // 'trans_item_flag' => 1,
                        'trans_item_ppn' => $ppn,
                        'trans_item_pack' => $qty_pack           
                    );
                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */
                
                    $set_update=$this->Transaksi_model->update_transaksi_item($id,$params);

                    $get_trans = $this->Transaksi_model->get_transaksi_item($id);
                    $trans_id = $get_trans['trans_item_trans_id'];
                    if(intval($trans_id) > 0){
                        /* Command Move to TRIGGER SQL
                        if(intval($identity) == 1){
                            $params_no_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 0
                            );
                            $params_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 1
                            );                            
                            $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                            $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                            $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price']; 
                            $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                            $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                            $params_total = array(
                                'trans_total' => $set_total
                            );
                        }
                        if(intval($identity) == 2){
                            $params_no_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 0
                            );
                            $params_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 1
                            );                            
                            $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                            $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_ppn);

                            $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price']; 
                            $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];

                            $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                            $params_total = array(
                                'trans_total' => $set_total
                            );
                        }                        
                        $update_trans = $this->Transaksi_model->update_transaksi($trans_id,$params_total);
                        */
                        $return->trans_id = 1;
                    }

                    if($set_update==true){
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 4,
                            'activity_table' => 'trans_items',
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
                    break;
                case "delete-item":
                    $id = $this->input->post('id');
                    $order_id = $this->input->post('order_id');                
                    $kode = $this->input->post('kode');        
                    $nama = $this->input->post('nama');                                
                    $flag = $this->input->post('flag');
                    $next=true;
                    $message = 'Gagal menghapus';

                    if($flag==1){
                        $msg='aktifkan transaksi '.$nama;
                        $act=7;
                    }else{
                        $msg='nonaktifkan transaksi '.$nama;
                        $act=8;
                    }

                    // $set_data=$this->Order_model->update_order_item($id,array('order_item_flag'=>0));
                    $get_trans = $this->Transaksi_model->get_transaksi_item($id);
                    $trans_id = $get_trans['trans_item_trans_id'];
                    $trans_type = $get_trans['trans_item_type'];

                    if(intval($trans_id) > 0){
                        /* Command move to TRIGGER SQL
                        if(intval($identity) == 1){
                            $params_no_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 0
                            );
                            $params_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 1
                            );                            
                            $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                            $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                            $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price']; 
                            $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                            $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                            $params_total = array(
                                'trans_total' => $set_total
                            );
                        }
                        if(intval($identity) == 2){
                            $params_no_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 0
                            );
                            $params_ppn = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_ppn' => 1
                            );                            
                            $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                            $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_ppn);

                            $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price']; 
                            $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];

                            $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                            $params_total = array(
                                'trans_total' => $set_total
                            );
                        }                        
                        $update_trans = $this->Transaksi_model->update_transaksi($trans_id,$params_total);
                        */

                        //Check Stock is Available to Delete
                        $set_trans_item_position = ($get_trans['trans_item_position']==1) ? 2 : 1;
                        if(intval($set_trans_item_position) == 2){
                            $params_check = array(
                                'trans_item_branch_id' => $session_branch_id,
                                'trans_item_ref' => $get_trans['trans_item_ref'],
                                'trans_item_position' => $set_trans_item_position,
                                // 'trans_item_flag' => 1
                            );
                            $check = $this->Transaksi_model->check_stock_is_available_to_delete($params_check);
                            if($check > 0){
                                $message='Gagal dihapus, Stok sudah digunakan';
                                $next=false;
                            }
                        }
                        
                        if($next){
                            //Check Trans is Account Payable & Receivable
                            if(($trans_type == 1) or ($trans_type == 2)){
                                $params = array(
                                    'journal_item_trans_id' => $trans_id
                                );
                                $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                if(intval($check_trans_on_journal_item) > 0){
                                    $message='Gagal, Transaksi ini sudah masuk jurnal';
                                    $next=false;
                                }

                                if($next){
                                    $params = array(
                                        'journal_item_trans_id' => $trans_id,
                                        'journal_item_journal_id >' => 0
                                    );
                                    $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                    if(intval($check_trans_on_journal_item) > 0){
                                        $message='Gagal, Transaksi ini sudah di bayar';
                                        $next=false;
                                    }                                   
                                }
                            }
                        }

                        if($next){
                            $params_count = array('trans_item_trans_id' => $trans_id);
                            $trans_count = $this->Transaksi_model->get_all_transaksi_items_count($params_count);
                            if(intval($trans_count) > 1){
                                $delete_data=$this->Transaksi_model->delete_transaksi_item($id);
                            }else{
                                $next = false;
                                $message = 'Hapus dapat dilakukan satu nota';
                            }
                        }
                        $return->trans_id = intval($trans_id);
                    }else{
                        $delete_data=$this->Transaksi_model->delete_transaksi_item($id);
                    }
                    if($next){
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
                        $message='Berhasil menghapus '.$nama;
                        $return->result = array(
                            'trans_item_id' => $id
                        );
                    }else{
                        $message=$message;
                        $return->result = array(
                            'trans_item_id' => $id
                        );                    
                    }
                    $return->message=$message;           
                    break;       
                case "load-item":
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
                    $datas = $this->Transaksi_model->get_all_transaksi_items($params_datatable, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Transaksi_model->get_all_transaksi_items_count($params_datatable);
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
                    break;
                /* OTHER trans and trans_items */
                case "load-trans-items":
                    $trans_id = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : 0;
                    $subtotal = 0;
                    $total_diskon = 0;
                    $total_ppn = 0;                    
                    $total= 0;
                    $params = array();

                    if(intval($trans_id) > 0){
                        $params = array(
                            'trans_item_trans_id' => intval($trans_id),
                            'trans_item_branch_id' => intval($session_branch_id),
                        );
                        $get_trans = $this->Transaksi_model->get_transaksi($trans_id);
                        $total_diskon = $get_trans['trans_discount'];
                        $product_type = !empty($this->input->post('product_type')) ? $params['trans_item_product_type'] = intval($this->input->post('product_type')) : 0;
                        
                        if($identity==9){ //Pemakaian Barang
                            $position = !empty($this->input->post('position')) ? intval($this->input->post('position')) : 2; // 1=In, 2=Out
                            // if($position==1){
                            //     $params['trans_item_position'] = 1;
                            // }else{
                            //     $params['trans_item_position'] = 2;
                            // }
                            $params['trans_item_position'] = ($position==1) ? 1 : 2;
                        }
                        if($identity==10){ //DEPRECATED Belum Teruji Pemasukan Barang
                            $position = !empty($this->input->post('position')) ? intval($this->input->post('position')) : 2; // 1=In, 2=Out
                            // if($position==1){
                            //     $params['trans_item_position'] = 1;
                            // }else{
                            //     $params['trans_item_position'] = 2;
                            // }
                            $params['trans_item_position'] = ($position==1) ? 1 : 2;
                        }

                        // var_dump($params);die;
                        $return->params = $params;
                        $get_data = $this->Transaksi_model->get_all_transaksi_items($params,null,null,null,null);                
                    }else{
                        $product_type = !empty($this->input->post('product_type')) ? intval($this->input->post('product_type')) : 1;
                        $position = !empty($this->input->post('position')) ? intval($this->input->post('position')) : 2;
                        $get_data = $this->Transaksi_model->check_unsaved_transaksi_item($identity,$session_user_id,$session_branch_id,$product_type,$position);
                    }

                    if(!empty($get_data)){
                        foreach($get_data as $v){
                            $datas[] = array(
                                'trans_item_id' => $v['trans_item_id'],
                                'trans_item_order_id' => $v['trans_item_order_id'],                            
                                'trans_item_unit' => $v['trans_item_unit'],
                                // 'trans_item_qty_weight' => number_format($v['trans_item_qty_weight'],2,'.',','),                            
                                'trans_item_in_qty' => number_format($v['trans_item_in_qty'],2,'.',','),
                                'trans_item_in_price' => number_format($v['trans_item_in_price'],2,'.',','),
                                'trans_item_out_qty' => number_format($v['trans_item_out_qty'],2,'.',','),
                                'trans_item_out_price' => number_format($v['trans_item_out_price'],2,'.',','),
                                'trans_item_sell_price' => number_format($v['trans_item_sell_price'],2,'.',','),
                                'trans_item_discount' => number_format($v['trans_item_discount'],2,'.',','),
                                'trans_item_total' => number_format($v['trans_item_total'],2,'.',','),
                                'trans_item_sell_total' => number_format($v['trans_item_sell_total'],2,'.',','),
                                'trans_item_total_after_discount' => number_format($v['trans_item_total'],2,'.',','),    
                                'trans_item_pack' => $v['trans_item_pack'],   
                                'trans_item_note' => $v['trans_item_note'],
                                // 'trans_item_product_price_id' => $v['trans_item_product_price_id'],
                                'trans_item_user_id' => $v['trans_item_user_id'],
                                'trans_item_branch_id' => $v['trans_item_branch_id'],
                                'trans_item_ppn' => $v['trans_item_ppn'],
                                'trans_item_session' => $v['trans_item_session'],
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'location' => array(
                                    'location_id' => !empty($v['location_id']) ? $v['location_id'] : '-',
                                    'location_code' => !empty($v['location_code']) ? $v['location_code'] : '-',
                                    'location_name' => !empty($v['location_name']) ? $v['location_name'] : '-'
                                ),
                            );

                            // if($identity==9){ //Penjualan
                                // $subtotal=$subtotal+$v['trans_item_sell_total'];
                                // if($v['trans_item_ppn'] == 1){
                                    // $total_ppn = $total_ppn + ($v['trans_item_sell_total']*0.1);
                                // }
                            // }else{
                                $subtotal=$subtotal+$v['trans_item_total'];
                                // if($v['trans_item_ppn'] == 1){
                                    // $total_ppn = $total_ppn + ($v['trans_item_total']*0.1);
                                // }                            
                            // }
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
                            $return->total_ppn=number_format($total_ppn,0,'.',',');                        
                            $return->total=number_format(($subtotal+$total_ppn)-$total_diskon,0,'.',',');
                        }else{ 
                            $data_source=0; $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($trans_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada item temporary';
                        if(intval($trans_id) > 0){
                            $return->message = '-';
                        }                    
                    }
                    break;
                case "load-product":
                    $search = null;
                    if(!empty($this->input->post('product_name'))){
                        // $search = array(
                        //     'product_name' => $this->input->post('product_name')
                        // );
                        //Default
                        /*
                        $params = array(
                            'product_category_id' => 0,
                            'product_type' => 1,
                            'product_name LIKE ' => "%".$this->input->post('product_name')."%"
                        );
                        */
                        $params = array(
                            'product_type' => 1,
                            'product_with_stock' => 1,
                            'product_name LIKE ' => "%".$this->input->post('product_name')."%"
                        );                    
                    }else{
                        
                        //Default
                        /*
                        $params = array(
                            'product_category_id' => 0,
                            'product_type' => 1
                        );
                        */

                        $params = array(
                            'product_with_stock' => 1,
                            'product_type' => 1
                        );                    
                    }                
                    $get_datas = $this->Produk_model->get_all_produks($params, $search, null, null, 'product_name', 'asc');
                    $datas = array();
                    foreach ($get_datas as $v) {

                        $start = '2020-09-01 00:00:00';
                        $end = date('Y-m-d H:i:s');
                        $result_stock = $this->Produk_model->get_product_stock($start,$end,$v['product_id']);

                        $datas[] = array(
                            'product_id' => $v['product_id'],
                            'product_name' => $v['product_name'],
                            'product_unit' => $v['product_unit'],
                            'product_stock_start' => $result_stock['product_stock_start'],
                            'product_stock_in' => $result_stock['product_stock_in'],
                            'product_stock_out' => $result_stock['product_stock_out'],
                            'product_stock_end' => $result_stock['product_stock_end']
                        );
                    }
                    $datas_count = $this->Produk_model->get_all_produks_count($params);
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
                    break;
                case "load-stock-warehouse":
                    $columns = array(
                        '0' => 'product_code',
                        '1' => 'product_name'
                    );

                    $order = $columns[$this->input->post('order')[0]['column']];
                    $dir = $this->input->post('order')[0]['dir'];
                    $search = !empty($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

                    $table_reload = !empty($this->input->post('table_reload')) ? $this->input->post('table_reload') : 0;
                    // $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    // $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $date_start = !empty($this->input->post('date_start')) ? date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00')) : '';
                    $date_end = !empty($this->input->post('date_end')) ? date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59')) : '';

                    $location_id = !empty($this->input->post('location')) ? $this->input->post('location') : 0;
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $category_id = !empty($this->input->post('category')) ? $this->input->post('category') : 0;

                    $datas = array();
                    if($table_reload == 1){
                        $get_datas = $this->report_product_stock(1,$date_start,$date_end,$session_branch_id,$location_id,$product_id,$order,$dir,$search,$category_id);
                        $datas_count = count($get_datas);
                    }
                    if(isset($get_datas)){ //Data exist
                        foreach($get_datas as $v):
                            $datas[] = array(
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'product_unit' => $v['product_unit'],
                                'in_qty' => number_format($v['in_qty'],2,'.',''),
                                'out_qty' => number_format($v['out_qty'],2,'.',''),
                                'balance' => number_format($v['balance'],2,'.',''),
                                'category_id' => $v['category_id'],
                                'category_name' => $v['category_name']                            
                            );
                        endforeach;
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
                    break;
                case "load-stock-moving":
                    $columns = array(
                        '0' => 'product_code',
                        '1' => 'product_name'
                    );

                    $order = $columns[$this->input->post('order')[0]['column']];
                    $dir = $this->input->post('order')[0]['dir'];
                    $search = null;
                    
                    // $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    // $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $location_id = !empty($this->input->post('location')) ? $this->input->post('location') : 0;
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $category_id = !empty($this->input->post('category')) ? $this->input->post('category') : 0;

                    $datas = array();
                    $get_datas = $this->report_product_stock(2,$date_start,$date_end,$session_branch_id,$location_id,$product_id,$order,$dir,$search,$category_id);
                    $datas_count = count($get_datas);
                    if(isset($get_datas)){ //Data exist
                        foreach($get_datas as $v):
                            $datas[] = array(
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'product_unit' => $v['product_unit'],
                                'location_name' => $v['location_name'],
                                'start_qty' => number_format($v['start_qty'],2,'.',''),
                                'in_qty' => number_format($v['in_qty'],2,'.',''),
                                'out_qty' => number_format($v['out_qty'],2,'.',''),
                                'balance' => number_format($v['balance'],2,'.',''),
                                'category_id' => $v['category_id'],
                                'category_name' => $v['category_name']                            
                            );
                        endforeach;                    
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
                    break;
                case "load-stock-valuation":
                    $columns = array(
                        '0' => 'product_code',
                        '1' => 'product_name',
                        '2' => 'category_name',
                        '3' => 'qty_balance',
                        '5' => 'qty_in_price',
                        '6' => 'qty_in_price_total'
                    );     

                    $order = $columns[$this->input->post('order')[0]['column']];
                    $dir = $this->input->post('order')[0]['dir'];
                    $search = !empty($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';
                    $table_reload = !empty($this->input->post('table_reload')) ? $this->input->post('table_reload') : 0;
                    // $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    // $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $date_start = !empty($this->input->post('date_start')) ? date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00')) : '';
                    $date_end = !empty($this->input->post('date_end')) ? date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59')) : '';

                    $location_id = !empty($this->input->post('location')) ? $this->input->post('location') : 0;
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $category_id = !empty($this->input->post('category')) ? $this->input->post('category') : 0;

                    $datas = array();
                    if($table_reload == 1){
                        $get_datas = $this->report_product_stock(5,$date_start,$date_end,$session_branch_id,$location_id,$product_id,$order,$dir,$search,$category_id);
                        $datas_count = count($get_datas);
                    }
                    if(isset($get_datas)){ //Data exist
                        foreach($get_datas as $v):
                            $datas[] = array(
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'product_unit' => $v['product_unit'],
                                'category_id' => $v['category_id'],
                                'category_name' => $v['category_name'],                        
                                'qty_balance' => number_format($v['qty_balance'],2,'.',','),
                                'qty_in_price' => number_format($v['qty_in_price'],2,'.',','),
                                'qty_in_price_total' => number_format($v['qty_in_price_total'],2,'.',',')                    
                            );
                        endforeach;
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
                    break;
                case "load_goods_out_item":
                    $this->form_validation->set_rules('trans_id', 'Trans ID', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                    
                        $id = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : null;
                        $params = array(
                            'trans_item_trans_id' => $id,
                        );                           
                        // $get_datas = $this->Produk_model->get_all_produks($params, null, 5, 0, 'category_name', 'asc');
                        $get_datas = $this->Transaksi_model->get_all_transaksi_items_with_category($params,null,null,null,'category_name','asc');
                        $datas = array();
                        if(count($get_datas)){
                            $return->status=1; 
                            $return->message='Loaded'; 
                            $return->total_records=count($get_datas);
                            $datas = $get_datas;
                        }else{ 
                            $return->message='No data'; 
                            $return->total_records=0;
                        }       
                        $return->result=$datas;        
                        $return->recordsTotal = $return->total_records;
                        $return->recordsFiltered = $return->total_records;    
                    }                             
                    break;
                case "create_goods_out":
                    $generate_nomor = $this->request_number_for_transaction($identity);

                    $trans_number = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $trans_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $trans_ref_number = !empty($data['ref_number']) ? $data['ref_number'] : null;
                    $trans_note = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $trans_contact_address = !empty($data['alamat']) ? $data['alamat'] : null;                
                    $trans_contact_phone = !empty($data['telepon']) ? $data['telepon'] : null;
                    $trans_contact_email = !empty($data['email']) ? $data['email'] : null;
                    $trans_location = !empty($data['gudang']) ? $data['gudang'] : null;
                    $trans_location_to = !empty($data['gudang_to']) ? $data['gudang_to'] : null;                
                    $trans_branch_id_2 = !empty($data['trans_branch_id_2']) ? $data['trans_branch_id_2'] : null;                                    

                    $jam = date('H:i:s');
                    
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $tgl_tempo = isset($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : $tgl;
                    
                    $set_date = $tgl.' '.$jam;
                    $set_date_due = $tgl_tempo.' '.$jam;

                    $set_branch = !empty($data['trans_branch_id_2']) ? $data['trans_branch_id_2'] : null;
                    // $set_branch = $session_branch_id;

                    //JSON Strigify Post
                    $params = array(
                        'trans_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'trans_contact_id' => !empty($trans_contact) ? $trans_contact : null,
                        'trans_number' => !empty($trans_number) ? $trans_number : null,
                        'trans_date' => $set_date,
                        'trans_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'trans_discount' => !empty($data['diskon']) ? $data['diskon'] : 0,
                        'trans_total' => !empty($data['total']) ? $data['total'] : 0,
                        'trans_change' => !empty($data['change']) ? $data['change'] : 0,
                        'trans_received' => !empty($data['received']) ? $data['received'] : 0,
                        'trans_fee' => !empty($data['fee']) ? $data['fee'] : 0,
                        'trans_note' => !empty($trans_note) ? $trans_note : null,
                        'trans_date_created' => date("YmdHis"),
                        'trans_date_updated' => date("YmdHis"),
                        'trans_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        'trans_branch_id' => $session_branch_id,
                        'trans_flag' => 0,
                        // 'trans_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        // 'trans_with_dp' => !empty($data['total_down_payment']) ? str_replace(',','',$data['total_down_payment']) : null,
                        'trans_location_id' => !empty($trans_location) ? $trans_location : null,
                        'trans_location_to_id' => !empty($trans_location_to) ? $trans_location_to : null,
                        'trans_ref_number' => !empty($trans_ref_number) ? $trans_ref_number : null,
                        'trans_date_due' => !empty($set_date_due) ? $set_date_due : null,
                        'trans_contact_address' => !empty($trans_contact_address) ? $trans_contact_address : null,
                        'trans_contact_phone' => !empty($trans_contact_phone) ? $trans_contact_phone : null,
                        'trans_contact_email' => !empty($trans_contact_email) ? $trans_contact_email : null,
                        // 'trans_paid' => !empty($data['paid']) ? $data['paid'] : null,
                        'trans_paid_type' => !empty($data['paid_type']) ? $data['paid_type'] : 0,
                        'trans_bank_name' => !empty($data['bank_name']) ? $data['bank_name'] : null,
                        'trans_bank_number' => !empty($data['bank_number']) ? $data['bank_number'] : null,  
                        'trans_card_bank_number' => !empty($data['card_bank_number']) ? $data['card_bank_number'] : null,
                        'trans_card_bank_name' => !empty($data['card_bank_name']) ? $data['card_bank_name'] : null,
                        'trans_card_account_name' => !empty($data['card_account_name']) ? $data['card_account_name'] : null,
                        'trans_card_expired' => !empty($data['card_expired']) ? $data['card_expired'] : null,
                        'trans_card_type' => !empty($data['card_type']) ? $data['card_type'] : null,
                        'trans_digital_provider' => !empty($data['digital_provider']) ? $data['digital_provider'] : null,
                        'trans_session' => $this->random_code(20),
                        'trans_branch_id_2' => $set_branch
                    );

                    //Check Data Exist
                    $params_check = array(
                        'trans_number' => $trans_number,
                        'trans_branch_id' => $session_branch_id
                    );
                    $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Transaksi_model->add_transaksi($params);
                        if($set_data){
                            $trans_id = $set_data;
                            $trans_list = $data['trans_list'];
                            
                            //Foreach Not Used
                            foreach($trans_list as $index => $value){
                                
                                // $params_update_trans_item = array(
                                //     'trans_item_trans_id' => $trans_id,
                                //     'trans_item_date' => $set_date,
                                //     // 'trans_item_location_id' => $trans_location,
                                //     'trans_item_flag' => 1                                
                                // );
                                // $this->Transaksi_model->update_transaksi_item($value,$params_update_trans_item);

                                // //For Transfer Stok
                                // if($identity==5){
                                //     $get_trans_item_list = $this->Transaksi_model->get_transaksi_item($value);
                                //     $where = array(
                                //         'trans_item_session' => $get_trans_item_list['trans_item_session']
                                //     );
                                //     $params_update_trans_item = array(
                                //         'trans_item_trans_id' => $trans_id,
                                //         'trans_item_date' => $set_date,
                                //         'trans_item_flag' => 1                
                                //     );
                                //     $this->Transaksi_model->update_transaksi_item_custom($where,$params_update_trans_item);                                
                                // }
                            }
                            //Endforeach

                            $params = array(
                                'product_with_stock' => 1,
                                'product_type' => 1,
                                'product_flag' => 1
                            );                           
                            $get_product = $this->Produk_model->get_all_produks($params, null, null, null, 'category_name', 'asc');
                            foreach($get_product as $v){
                                $params_items = array(
                                    'trans_item_trans_id' => $trans_id,
                                    // 'trans_item_id_order' => $data['order_id'],
                                    // 'trans_item_id_order_detail' => $data['order_detail_id'],
                                    'trans_item_product_id' => $v['product_id'],
                                    'trans_item_location_id' => $trans_location,
                                    'trans_item_date' => $set_date,
                                    'trans_item_unit' => $v['product_unit'],
                                    // 'trans_item_in_qty' => $qty,                 
                                    // 'trans_item_in_price' => $harga,
                                    // 'trans_item_out_qty' => $qty,
                                    // 'trans_item_out_price' => $harga,
                                    'trans_item_product_type' => $v['product_type'],
                                    'trans_item_type' => $data['tipe'],
                                    // 'trans_item_discount' => 0,
                                    // 'trans_item_total' => $total,
                                    'trans_item_date_created' => date("YmdHis"),
                                    'trans_item_date_updated' => date("YmdHis"),
                                    'trans_item_user_id' => $session_user_id,
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_flag' => 0,
                                    // 'trans_item_ref' => $ref_number,
                                    // 'trans_item_ppn' => $ppn,
                                    'trans_item_position' => 2
                                );
                                $this->Transaksi_model->add_transaksi_item($params_items);
                            }

                            if(intval($trans_id) > 0){
                                if($config_post_to_journal==true){
                                    $operator = $this->journal_for_transaction('create',$trans_id);
                                    $return->trans_id = 1;
                                }
                            }
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 2,
                                'activity_table' => 'trans',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $trans_number,                        
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
                                'trans_id' => $trans_id,
                                'trans_number' => $trans_number
                            ); 
                        }
                    }else{
                        $return->message='Nomor sudah digunakan';                    
                    }
                    break;  
                case "update_goods_out_item":
                    $this->form_validation->set_rules('trans_item_id', 'Trans ID', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{   
                        $post = $this->input->post();                    
                        $id = !empty($post['trans_item_id']) ? $post['trans_item_id'] : null;
                        $pn = !empty($post['product_name']) ? $post['product_name'] : null;                        
                        $qty = !empty($post['trans_item_out_qty']) ? $post['trans_item_out_qty'] : '0.00';
                        
                        $params = array(
                            // 'trans_item_id' => $id,
                            // 'trans_item_order_id' => $data['order_id'],
                            // 'trans_item_order_item_id' => $data['order_detail_id'],
                            // 'trans_item_product_id' => $produk,
                            // 'trans_item_location_id' => $data['lokasi_id'],
                            // 'trans_item_date' => date("YmdHis"),
                            // 'trans_item_unit' => $satuan,
                            'trans_item_out_qty' => $qty,
                            // 'trans_item_in_price' => $harga,
                            // 'trans_item_total' => $total,
                            // 'trans_item_note' => $keterangan,                                                            
                            // 'trans_item_type' => $data['tipe'],
                            'trans_item_date_updated' => date("YmdHis"),
                            'trans_item_flag' => 1,
                            // 'trans_item_ppn' => $ppn,
                            // 'trans_item_pack' => $qty_pack           
                        );
                        // var_dump($id);die;
                        $set_update=$this->Transaksi_model->update_transaksi_item($id,$params);

                        $get_trans = $this->Transaksi_model->get_transaksi_item($id);
                        $trans_id = $get_trans['trans_item_trans_id'];
                        if(intval($trans_id) > 0){
                            $return->trans_id = 1;
                        }

                        if($set_update==true){
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 4,
                                'activity_table' => 'trans_items',
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
                            $return->message= $pn.' => '.$qty;
                        }     
                    }           
                    break;
                case "update-label":
                    $id = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : 0;
                    $trans_label = !empty($this->input->post('trans_label')) ? $this->input->post('trans_label') : null;

                    if(strlen(intval($id)) > 0){
                        $get_data = $this->Transaksi_model->get_transaksi($id);
                        
                        $where_type = array(
                            'type_for' => 2,
                            'type_type' => $get_data['trans_type']
                        );
                        $get_type = $this->Type_model->get_type_custom($where_type);
                        
                        $where_label = array(
                            'ref_type' => 9,
                            'ref_name' => $trans_label
                        );
                        $get_label = $this->Referensi_model->get_all_referensi_custom($where_label);
                        // var_dump($get_label);
                        $params = array(
                            'trans_label' => $trans_label,
                        );
                        
                        $set_data = $this->Transaksi_model->update_transaksi($id,$params);
                        if($set_data){

                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 10,
                                'activity_table' => 'trans',
                                'activity_table_id' => $get_data['trans_id'],
                                'activity_text_1' => $trans_label,
                                'activity_text_2' => $get_data['trans_number'],
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $get_type['type_name'],
                                'activity_type' => 2,
                                'activity_icon' => $get_label['ref_note']
                            );
                            $this->save_activity($params);
                            /* End Activity */

                            $return->status  = 1;
                            $return->message = 'Berhasil memperbarui';
                            $return->result = array(
                                'trans_id' => $get_data['trans_id'],
                                'trans_label' => $trans_label 
                            );
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed set label';
                    }
                    break;                                                                  
                default:
                    $return->message='No Action';
                    break;                    
            }
        }
        if(empty($action)){
            $action='';
        }
        $return->action=$action;
        echo json_encode($return);        
    }  
    function prints($id){ //Standard Print
        //Header
        $params = array(
            'trans_id' => $id
        );
        $data['header'] = $this->Transaksi_model->get_transaksi($id);
        $data['branch'] = $this->Branch_model->get_branch($data['header']['user_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }
        $data['journal_item'] = array();

        // Transfer Stok Dokumen
        $data['location'] = array();
        if($data['header']['trans_type'] == 1){
            $params_journal = array(
                'journal_item_type' => 1,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
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
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 2){
            $params_journal = array(
                'journal_item_type' => 2,
                'journal_item_trans_id' => $id,
                'journal_item_credit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
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
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 3){
            $params_journal = array(
                'journal_item_type' => 3,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
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
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 5){
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
            'trans_item_trans_id' => $id
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'trans_item_date_created';
        $dir        = 'ASC';

        if($data['header']['trans_type'] == 9){
            $order = 'category_name';
            $data['content'] = $this->Transaksi_model->get_all_transaksi_items_goods_out($params,$search,$limit,$start,$order,$dir);
        }else{
            $data['content'] = $this->Transaksi_model->get_all_transaksi_items($params,$search,$limit,$start,$order,$dir);
        }

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'location' => $data['location'],
            'content' => $data['content'],
            'journal' => $data['journal_item'],
            'footer' => array(
                'user_creator' => array(
                    'user_id' => !empty($data['header']['trans_user_id']) ? $data['header']['user_id'] : '-',
                    'user_name' => !empty($data['header']['trans_user_id']) ? $data['header']['user_username'] : '-',
                    'user_phone' => !empty($data['header']['trans_user_id']) ? $data['header']['user_phone_1'] : '-',
                    'user_email' => !empty($data['header']['trans_user_id']) ? $data['header']['user_email_1'] : '-',
                )
            )
        );

        // echo json_encode($data['result']);die;

        $session = $this->session->userdata();   
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        //Aktivitas
        $params = array(
            'activity_user_id' => !empty($session_user_id) ? $session_user_id : null,
            'activity_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
            'activity_action' => 6,
            'activity_table' => 'trans',
            'activity_table_id' => $id,
            'activity_text_1' => ucwords(strtolower($data['header']['type_name'])),
            'activity_text_2' => ucwords(strtolower($data['header']['trans_number'])),
            'activity_text_3' => ucwords(strtolower($data['header']['contact_name'])),
            'activity_date_created' => date('YmdHis'),
            'activity_flag' => 0
        );
        $this->save_activity($params);

        //Set Layout From Order Type
        if($data['header']['trans_type']==5){
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
        else if($data['header']['trans_type']==9){
            $data['title'] = 'Pemakaian Barang';
            $this->load->view($this->print_directory.'inventory_goods_out_request',$data);
        }
        else if($data['header']['trnetans_type']==10){
            $data['title'] = 'Pemasukan Barang';
            $this->load->view($this->print_directory.'inventory_goods_in',$data);
        }
        else{
            // $this->load->view('prints/sales_order',$data);
        }
    }    
    function print_opname($id){
        // $this->load->model('Print_spoiler_model');
        // $id=$data['id'];
        // var_dump($id);
        $data['title'] = 'OPNAME';
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
        echo nl2br("OPNAME"."\r\n");
        $content .= "OPNAME\r\n";
        echo "\r\n";       
        echo nl2br($data['header']['order_number']."\r\n");
        $content .= $data['header']['order_number']."\r\n";
        echo nl2br(date("d-M-Y, H:i", strtotime($data['header']['order_date']))."\r\n");
        $content .= date("d-M-Y, H:i", strtotime($data['header']['order_date']))."\r\n";        
        // echo nl2br($data['header']['contact_name']."\r\n");
        echo nl2br("------------------------------"."\r\n");
        $content .= "-----------------------------------"."\r\n";        
        foreach($data['content'] AS $v){
            // echo $v['product_name']."         ".number_format($v['order_item_total'])."\r\n";
            echo nl2br($v['product_name']."\r\n");
            $content .= $v['product_name']."\r\n"; 
            // echo nl2br("Rp. ".number_format($v['order_item_price'])." x ".$v['order_item_qty']." ".$v['order_item_unit']." (Rp. ".number_format($v['order_item_total']).")"."\r\n");
            echo nl2br($v['order_item_qty']." ".$v['order_item_unit']."\r\n");            
            $content .= "Rp. ".number_format($v['order_item_price'])." x ".$v['order_item_qty']." ".$v['order_item_unit']." (Rp. ".number_format($v['order_item_total']).")"."\r\n";            
            if(!empty($v['order_item_note'])){
                echo nl2br($v['order_item_note']."\r\n");
                $content .= $v['order_item_note']."\r\n";                
            }
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
        // $this->Print_spoiler_model->add_print_spoiler($params);
        // echo $content;        
    }
    function prints_struk($id){ //Print Thermal 58mm Done 
        // $tmpdir         = sys_get_temp_dir();
        // $file           = tempnam($tmpdir, 'ctk');
        // $handle         = fopen($file, 'w');
        // $condensed      = Chr(27) . Chr(33) . Chr(4);
        // $bold1          = Chr(27) . Chr(69);
        // $bold0          = Chr(27) . Chr(70);
        // $initialized    = chr(27).chr(64);
        // $condensed1     = chr(15);
        // $condensed0     = chr(18);
    
        // $text  = $initialized;
        // $text .= $condensed1;

        $text = '';
        $word_wrap_width = 29;

        $get_branch = $this->Branch_model->get_branch(1);
        $get_trans  = $this->Transaksi_model->get_transaksi($id);
        $get_items  = $this->Transaksi_model->get_transaksi_item_custom(array('trans_item_trans_id'=>$get_trans['trans_id']));

        //Process if Data Found
        if($get_trans){
            $paid_type_name = '';
            if($get_trans['trans_paid_type'] > 0){
                $get_type_paid = $this->Type_model->get_type_paid($get_trans['trans_paid_type']);
                $paid_type_name = $get_type_paid['paid_name'];
            }else{
                $paid_type_name = '-'; // Piutang
            }       

            //Header
            $text .= $this->set_wrap_1($get_branch['branch_name']);
            // $text .= $this->set_wrap_1($get_branch['branch_address']);
            // $text .= $this->set_wrap_1($get_branch['branch_phone_1']);                
            $text .= $this->set_wrap_1($get_trans['trans_number']);
            $text .= $this->set_wrap_1(date("d/m/Y - H:i:s", strtotime($get_trans['trans_date'])));    

            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);

            //Content
            $text .= $this->set_wrap_2("Produk", "Qty");
            $text .= $this->set_line('-',$word_wrap_width);
            foreach($get_items as $v):
                // $text .= $v['product_name']."\n";
                $text .= $this->set_wrap_2($v['product_name'], number_format($v['trans_item_out_qty'],0,'',','));            
            endforeach;       

            $text .= "\n";         
            $text .= $this->set_line('-',$word_wrap_width);  
            $text .= $this->set_wrap_2('Cabang',$get_trans['branch_2_name']);
            $text .= $this->set_wrap_2('User',$get_trans['user_username']);

            //Footer
            $text .= "\n";                            
            $text .= $this->set_wrap_0("Print On","-","BOTH");
            $text .= $this->set_wrap_0(date("d/m/Y - H:i"),"-","BOTH");            
            
            //Save to Print Spoiler
            $params = array(
                'spoiler_content' => $text, 'spoiler_source_table' => 'trans',
                'spoiler_source_id' => $id, 'spoiler_flag' => 0, 'spoiler_date' => date('YmdHis')
            );
            $this->Print_spoiler_model->add_print_spoiler($params);
        }else{
            $text = "Transaksi tidak ditemukan\n";
        }

        //Open / Write to print.txt
        $file = fopen("print.txt", "w") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);

        // $text .= chr(27).chr(10);

        //Write and Save
        fwrite($file,$text);
        // fclose($file);

        if(fclose($file)){
            echo json_encode(array('status'=>1,'print_url'=>base_url('print.txt'),'print_to'=>$this->print_to));
        }else{
            echo json_encode(array('status'=>0,'message'=>'Print raw error','print_to'=>$this->print_to));
        }

        //Preview to HTML
        // $this->output->set_content_type('text/plain', 'UTF-8');
        // $this->output->set_output($text);
        
        //Need Activate to Copy File into Print Enqueue
        // copy($file, "//localhost/printer-share-name"); # Do Print
        // unlink($file);
    }    
    function test(){
        $params = array();        
        $datas = $this->Transaksi_model->get_all_stock($params,null, 10, 0, 'product_name', 'asc');
        echo json_encode($datas);die; 
    }
    public function set_line($char, $width) {
        // $lines=array();
        // foreach (explode("\n",wordwrap($kolom1,$len=32)) as $line)
        //     $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        // return implode("\n",$lines)."\n";
        $ret = '';
        for($a=0; $a<$width; $a++){
            $ret .= $char;
        }
        return $ret."\n";
    }
    public function set_wrap_0($kolom1,$separator,$padding) {
        if($padding=='BOTH'){ $set_padding = STR_PAD_BOTH ;
        }else if($padding=='LEFT'){ $set_padding = STR_PAD_LEFT;
        }else if($padding=='RIGHT'){ $set_padding = STR_PAD_RIGHT;
        }
        $lines=array();
        foreach (explode("\n",wordwrap($kolom1,$len=29)) as $line)
            $lines[]=str_pad($line,$len,$separator,$set_padding);
        return implode("\n",$lines)."\n";
    }    
    public function set_wrap_1($kolom1) {
        $lines=array();
        foreach (explode("\n",wordwrap($kolom1,$len=28)) as $line)
            $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        return implode("\n",$lines)."\n";
    }
    public function set_wrap_2($kolom1, $kolom2) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 15;
        $lebar_kolom_2 = 13;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            // $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            // $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    public function set_wrap_3($kolom1, $kolom2, $kolom3) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 14;
        $lebar_kolom_2 = 1;
        $lebar_kolom_3 = 12;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    public function set_wrap_4($kolom1, $kolom2, $kolom3, $kolom4) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 12;
        $lebar_kolom_2 = 8;
        $lebar_kolom_3 = 8;
        $lebar_kolom_4 = 9;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
        $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);
        $kolom4Array = explode("\n", $kolom4);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }    
}

?>