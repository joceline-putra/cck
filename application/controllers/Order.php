<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller{
    public $print_directory;
    var $folder_location = array(
        '11' => array(
            'parent_id' => 40,            
            'title' => 'Statistik',
            'view' => 'layouts/admin/menu/purchase/statistic',
            'javascript' => 'layouts/admin/menu/purchase/statistic_js'    
        ),
        '1' => array( //Puchase Order
            'parent_id' => 40,
            'title' => 'Purchase Order',
            'view' => 'layouts/admin/menu/purchase/order',
            'javascript' => 'layouts/admin/menu/purchase/order_js'
        ),
        '2' => array( //Sales Order
            'parent_id' => 39,            
            'title' => 'Sales Order',
            'view' => 'layouts/admin/menu/sales/order',
            'javascript' => 'layouts/admin/menu/sales/order_js'             
        ),
        '3' => array(
            'parent_id' => 40,            
            'title' => 'Penawaran Pembelian',
            'view' => 'layouts/admin/menu/purchase/quotation',
            'javascript' => 'layouts/admin/menu/purchase/quotation_js'             
        ),        
        '4' => array(
            'parent_id' => 39,            
            'title' => 'Penawaran Penjualan',
            'view' => 'layouts/admin/menu/sales/quotation',
            'javascript' => 'layouts/admin/menu/sales/quotation_js'             
        ),                
        '22' => array(
            'parent_id' => 39,              
            'title' => 'Statistik',
            'view' => 'layouts/admin/menu/sales/statistic',
            'javascript' => 'layouts/admin/menu/sales/statistic_js'
        ),
        '222' => array(
            'parent_id' => 39,             
            'title' => 'POS 1',
            'view' => 'layouts/admin/menu/sales/pos/pos_v1',
            'javascript' => 'layouts/admin/menu/sales/pos/pos_v1_js'
        ),        
        '56' => array(
            'title' => 'Checkup',
            'view' => 'layouts/admin/menu/checkup/statistic',
            'javascript' => 'layouts/admin/menu/checkup/statistic_js'
        ),
        '5' => array(
            'title' => 'Checkup Medicine',
            'view' => 'layouts/admin/menu/checkup/medicine',
            'javascript' => 'layouts/admin/menu/checkup/medicine_js'
        ),
        '6' => array(
            'title' => 'Checkup Laboratory',
            'view' => 'layouts/admin/menu/checkup/laboratory',
            'javascript' => 'layouts/admin/menu/checkup/laboratory_js'
        ),
        '7' => array(
            'parent_id' => 39,            
            'title' => 'Prepare',
            'view' => 'layouts/admin/menu/sales/prepare',
            'javascript' => 'layouts/admin/menu/sales/prepare_js'             
        ),               
    );
    function __construct(){
        parent::__construct();
        $this->print_directory = 'layouts/admin/menu/prints/';
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));            
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
        $this->load->model('Journal_model');
        $this->load->model('Kontak_model');   
        $this->load->model('Type_model');
        $this->load->model('Account_map_model');

        $this->contact_1_alias  = 'Customer'; /* contact_type = 2  - Customer, Member*/
        $this->contact_2_alias  = 'Waitress'; /* contact_type = 3  - Karyawan Terapis, Waitress*/
        $this->ref_alias        = 'Meja'; /* ref_type = 7 */       
        $this->order_alias      = 'Order';
        $this->payment_alias    = 'Payment'; 
        $this->dp_alias         = 'Deposit';

        $this->print_to         = 1; //0 = Local, 1 = Bluetooth
        $this->whatsapp_config  = 1;
        $this->module_approval   = 1; //Approval
        $this->module_attachment   = 1; //Attachment     
    }   
    function index(){
        $data['identity'] = 2;
        $data['session'] = $this->session->userdata();
        $data['usernya'] = $this->User_model->get_all_user();
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
                
        //Date
        $firstdate = new DateTime('first day of this month');
        $data['first_date'] = $firstdate->format('d-M-Y');
        $data['end_date'] = date("d-M-Y");

        $data['title'] = 'Order';
        $data['_view'] = 'layouts/admin/menu/jual/sales_order';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/jual/sales_order_js.php',$data);        
    }
    function pages($identity){
        $session = $this->session->userdata();  

        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        // $params_menu = array(
        //     'menu_parent_id' => $this->menu_id
        // );
        // $data['navigation'] = $this->Menu_model->get_all_menus($params_menu,null,null,null,null,null);
        
        $data['identity']   = $identity;
        $data['title']      = $this->folder_location[$identity]['title'];
        $data['_view']      = $this->folder_location[$identity]['view'];
        $file_js            = $this->folder_location[$identity]['javascript'];

        $data['operator']           = '';
        $data['module_approval']    = $this->module_approval;
        $data['module_attachment'] = $this->module_attachment;    
        $data['whatsapp_config']    = $this->whatsapp_config;
        $data['print_to']           = $this->print_to; //0 = Local, 1 = Bluetooth

        //Sub Navigation
        $params_menu = array(
            'menu_parent_id' => $this->folder_location[$identity]['parent_id'],
            'menu_flag' => 1
        );
        $get_menu = $this->Menu_model->get_all_menus($params_menu,null,null,null,'menu_sorting','asc');
        $data['navigation'] = !empty($get_menu) ? $get_menu : [];

        if($identity == 222){ // POS / Point of Sales (Order -> Trans)
            $params_datatable = array(
                'category_type' => 1, //Product Categories
                'category_flag' => 1,
                'category_branch_id' => $session_branch_id
            );
            $params_type_paid = array(
                'paid_flag' => 1
            );
            $params_account_map = array(
                'account_map_branch_id' => $session_branch_id,
                'account_map_for_transaction' => 11
            );
            $data['contact_1_alias']  = $this->contact_1_alias;
            $data['contact_2_alias']  = $this->contact_2_alias;
            $data['ref_alias']        = $this->ref_alias;         
            $data['order_alias']      = $this->order_alias;
            $data['payment_alias']    = $this->payment_alias;  
            $data['dp_alias']         = $this->dp_alias;                                                
            $data['product_category'] = $this->Kategori_model->get_all_categoriess($params_datatable,null,null,null,'category_name','asc');
            $data['type_paid']        = $this->Type_model->get_all_type_paid($params_type_paid,null,null,null,'paid_id','asc');
            $data['account_payment']  = $this->Account_map_model->get_all_account_map($params_account_map,null,null,null,'account_map_type','asc');                  
        }

        //Date
        $firstdate              = new DateTime('first day of this month');
        $data['first_date']     = date('d-m-Y', strtotime($firstdate->format('Y-m-d')));
        $data['end_date']       = date('d-m-Y', strtotime(date("Y-m-d")));
        $data['end_date_due']   = date('d-m-Y', strtotime('+30 days',strtotime(date("Y-m-d"))));

        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function action($identity,$operator){
        $data['session']    = $this->session->userdata();     
        $data['theme']      = $this->User_model->get_user($data['session']['user_data']['user_id']);

        $data['identity']   = $identity;
        $data['title']      = $this->folder_location[$identity]['title'];
        $data['_view']      = $this->folder_location[$identity]['view'];
        $file_js            = $this->folder_location[$identity]['javascript'];
        $data['operator']   = $operator;

        //Date
        $firstdate              = new DateTime('first day of this month');
        $data['first_date']     = date('d-m-Y', strtotime($firstdate->format('Y-m-d')));
        $data['end_date']       = date('d-m-Y', strtotime(date("Y-m-d")));
        $data['end_date_due']   = date('d-m-Y', strtotime('+30 days',strtotime(date("Y-m-d"))));

        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }    
    function manage(){
        $session                = $this->session->userdata();   
        $session_branch_id      = $session['user_data']['branch']['id'];
        $session_user_id        = $session['user_data']['user_id'];
        $session_location_id    = $session['user_data']['branch']['branch_location_id'];        
        $config_post_to_journal = false; //True = Call SP_JOURNAL_FROM_TRANS, False = Disabled Function

        $return             = new \stdClass();
        $return->status     = 0;
        $return->message    = '';
        $return->result     = array();

        if($this->input->post('action')){
            $action         = $this->input->post('action');
            $post_data      = $this->input->post('data');
            $data           = json_decode($post_data, TRUE);
            $data['tipe']   = !empty($data['tipe']) ? $data['tipe'] : 0;            
            $identity       = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];
            
            //Transaksi Tipe
            if($identity == 1){ //Purchase Order
                $set_tipe = 1;
                $set_transaction = 'Purchase Order';    
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contact_name',
                    '3' => 'order_total'
                );
            }else if($identity == 2){ //Sales Order
                $set_tipe = 2;
                $set_transaction = 'Sales Order';
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contacts.contact_name',
                    '3' => 'order_total',
                    '4' => 'order_sales_name'
                );
            }else if($identity == 222){ //POS
                $set_tipe = 222;
                $set_transaction = 'Point of Sales';
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contact_name',
                    '3' => 'order_total',
                    '4' => 'order_sales_name'
                );
            }else if($identity == 3){ //Penawaran Beli
                $set_tipe = 1;
                $set_transaction = 'Quotation';
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contact_name',
                    '3' => 'order_total'
                );
            }else if($identity == 4){ //Penawaran Jual
                $set_tipe = 2;
                $set_transaction = 'Quotation';
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contact_name',
                    '3' => 'order_total'
                );
            }else if(($identity == 5) or ($identity == 6)){ //Checkup Medicine
                $set_tipe = $identity;
                $set_transaction = 'Checkup Medicine';
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contact_name',
                    '3' => 'order_total'
                );
            }else if($identity == 7){ //Prepare
                $set_tipe = 7;
                $set_transaction = 'Prepare';
                $columns = array(
                    '0' => 'order_date',
                    '1' => 'order_number',
                    '2' => 'contact_name',
                    '3' => 'order_total'
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
                            // $search['contacts.'.$v] = $s;
                            $search[$v] = $s;                            
                        }
                    }
                    // var_dump($search);die;
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
                        'orders.order_type' => intval($identity),
                        'orders.order_branch_id' => intval($session_branch_id)
                    );
                    if($kontak > 0){
                        $params_datatable = array(
                            'orders.order_date >' => $date_start,
                            'orders.order_date <' => $date_end,
                            'orders.order_type' => intval($identity),
                            'orders.order_branch_id' => intval($session_branch_id),
                            'orders.order_contact_id' => intval($kontak)                   
                        );                    
                    }

                    //POS Filter
                    if($identity == 222){
                        // $params_datatable['orders.order_flag >'] = 0;
                    }else{
                        $params_datatable['orders.order_flag <'] = 4;
                    }
                    $datas_count = $this->Order_model->get_all_orders_count($params_datatable,$search);                               
                    if($datas_count > 0){ //Data exist
                        $datas       = $this->Order_model->get_all_orders_datatable($params_datatable, $search, $limit, $start, $order, $dir);
                        // $datas       = $this->Order_model->get_all_orders($params_datatable, $search, $limit, $start, $order, $dir);
                        
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$datas_count;
                        $return->result=$datas;        
                    }else{ 
                        $return->status=0; 
                        $return->message='No data'; 
                        $return->total_records=$datas_count;
                        $return->result=array();    
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->params             = $params_datatable;
                    $return->search             = $search;                       
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

                    $params_datatable = array(
                        'orders.order_id' => $trans_id,
                        'orders.order_branch_id' => $session_branch_id
                    );
                    $datas_count = $this->Order_model->get_all_order_items_count($params_datatable);
                    if($datas_count > 0){
                        $datas = $this->Order_model->get_all_order_items($params_datatable, $search, $limit, $start, $order, $dir);
                        $return->status=1; $return->message='Loaded'; $return->total_records=$datas_count;
                        $return->result=$datas;        
                    }else{ 
                        $return->status=0; $return->message='No data'; $return->total_records=$datas_count;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $datas_count;
                    $return->recordsFiltered = $datas_count;
                    break;                     
                case "load-order-items": //PO, SO, POS, POS required ref_id (Room, Table)
                    $order_id   = !empty($this->input->post('order_id')) ? intval($this->input->post('order_id')) : 0;
                    $ref_id     = !empty($this->input->post('ref_id')) ? intval($this->input->post('ref_id')) : 0;                
                    $params     = array();
                    $return->total_dp = 0;

                    if(intval($order_id) > 0){
                        $params = array(
                            'order_item_order_id' => $order_id,
                            'order_item_branch_id' => $session_branch_id
                        );
                        $get_data = $this->Order_model->get_all_order_items($params,null,null,null,null);
                        $get_order = $this->Order_model->get_order($order_id);
                    }else{
                        if(intval($identity) == 222){ //POS Here
                            $params = array(
                                'order_item_branch_id' => $session_branch_id,
                                'order_item_flag' => 0
                            );                        
                            if(intval($ref_id) > 0){ //Room/Table Used
                                $params['order_item_ref_id'] = $ref_id;
                                $get_ref        = $this->Referensi_model->get_referensi_custom(array('ref_id'=>$ref_id));
                                $return->ref    = $get_ref;
                            }
                            $get_data       = $this->Order_model->get_all_order_items($params,null,null,null,null);
                        }else{
                            $get_data = $this->Order_model->check_unsaved_order_item($identity,$session_user_id,$session_branch_id);
                            if(!empty($get_data)){
                                if(intval($get_data[0]['order_item_ref_id']) > 0){
                                    $get_ref = $this->Referensi_model->get_referensi_custom(array('ref_id'=>$get_data[0]['order_item_ref_id']));
                                    $return->ref = $get_ref; 
                                }
                            }
                        }
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
                                'order_item_id' => intval($v['order_item_id']),
                                'order_item_order_id' => $v['order_item_order_id'],                            
                                'order_item_unit' => $v['order_item_unit'],
                                'order_item_qty' => number_format($v['order_item_qty'],2,'.',','),
                                'order_item_qty_format' => number_format($v['order_item_qty'],0,'',''),                                
                                'order_item_qty_weight' => number_format($v['order_item_qty_weight'],2,'.',','),                            
                                'order_item_price' => number_format($v['order_item_price'],2,'.',','),
                                'order_item_discount' => number_format($v['order_item_discount'],2,'.',','),
                                'order_item_total' => number_format($v['order_item_total'],2,'.',','),
                                'order_item_total_after_discount' => number_format($v['order_item_total'],2,'.',','),       
                                'order_item_note' => $v['order_item_note'],
                                'order_item_product_price_id' => $v['order_item_product_price_id'],
                                'order_item_user_id' => intval($v['order_item_user_id']),
                                'order_item_branch_id' => intval($v['order_item_branch_id']),
                                'order_item_flag' => intval($v['order_item_flag']),
                                'product_id' => intval($v['product_id']),
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'has_other_price' => $product_price_list,
                                'order_item_ref_id' => intval($v['order_item_ref_id'])
                            );
                            $subtotal=$subtotal+$v['order_item_total'];
                        }

                        if(isset($datas)){ //Data exist
                            $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas; 
                            $return->total_produk=count($datas);
                            $return->subtotal     = number_format($subtotal,2,'.','');
                            $return->total_diskon = number_format($total_diskon,2,'.','');
                            // $return->total_dp = !empty($get_order) ? (floatVal($get_order['order_with_dp']) > 0) ? number_format($get_order['order_with_dp'],2,'.','') : '0.00' : '0.00';
                            $return->total_dp     = !empty($get_order) ? number_format($get_order['order_with_dp'],2,'.','') : '0.00';
                            $return->total        = number_format(($subtotal-$total_diskon)-$return->total_dp,2,'.','');                       
                        }else{ 
                            $total=0; 
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
                    break;
                case "load-order-items-for-report":
                    $trans_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : 0;
                    $contact_id = !empty($this->input->post('kontak')) ? $this->input->post('kontak') : 0;
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $product    = !empty($this->input->post('product')) ? $this->input->post('product') : 0;   
                    $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end   = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $params = array(
                        'order_item_type' => intval($trans_type),
                        'order_item_branch_id' => intval($session_branch_id),
                        'order_date >' => $date_start,
                        'order_date <' => $date_end,                    
                    );
                    if(intval($contact_id) > 0){
                        $params['order_contact_id'] = $contact_id;
                    }
                    if(intval($product_id) > 0){
                        $params['order_item_product_id'] = $product_id;
                    }

                    if(intval($product) > 0){
                        $params['order_item_product_id'] = $product;
                    }     
                    $datas = $this->Order_model->get_all_order_items_report($params,null,1000,0,'order_item_date','asc');
                    if(!empty($datas)){
                        /*  Activity
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
                            $total=count($datas);
                            $return->status=1; 
                            $return->message='Loaded'; 
                            $return->total_records=$total;
                            $return->result=$datas;
                        }else{
                            $return->status=0; 
                            $return->message='No data'; 
                            $return->total_records=0;
                            $return->result=0;
                        }
                        $return->status=1;
                        $return->message='Data ditemukan';
                    }else{
                        $total=0;
                        $return->message='Tidak ada item temporary';
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    $return->params = $params;
                    break;                                                        
                case "create":
                    $generate_nomor         = $this->request_number_for_order($identity);
                    $order_number           = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $order_contact          = !empty($data['kontak']) ? $data['kontak'] : null;
                    $order_ref_number       = !empty($data['ref_number']) ? $data['ref_number'] : null;
                    $order_note             = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    
                    $order_contact_name     = !empty($data['order_contact_name']) ? $data['order_contact_name'] : null;
                    $order_contact_phone    = !empty($data['order_contact_phone']) ? $data['order_contact_phone'] : null;

                    $jam                    = date('H:i:s');
                    $tgl            = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $tgl_tempo      = !empty($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : null;
                    
                    $set_date       = $tgl.' '.$jam;
                    $set_date_due   = $tgl_tempo.' '.$jam;

                    //JSON Stringify POST
                    $params = array(
                        'order_session' => $this->random_code(20),
                        'order_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                        'order_type' => !empty($data['tipe']) ?$data['tipe'] : null,
                        'order_number' => !empty($order_number) ? $order_number : null,
                        'order_date' => !empty($set_date) ? $set_date : date('YmdHis'),
                        'order_contact_id' => !empty($order_contact) ? $order_contact : null,
                        'order_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'order_discount' => !empty($data['diskon']) ? $data['diskon'] : 0,
                        'order_total_dpp' => !empty($data['total']) ? $data['total'] : 0,
                        'order_total' => !empty($data['total']) ? $data['total'] : 0,
                        'order_note' => !empty($order_note) ? $order_note : null,
                        'order_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        'order_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        'order_date_created' => date("YmdHis"),
                        'order_date_updated' => date("YmdHis"),                    
                        'order_flag' => !empty($data['status']) ? 1 : 0,
                        'order_trans_id' => !empty($data['trans_id']) ? $data['trans_id'] : null,
                        'order_ref_number' => !empty($order_ref_number) ? $order_ref_number : null,
                        'order_with_dp' => !empty($data['order_with_dp']) ? str_replace(',','',$data['order_with_dp']) : '0.00',
                        'order_with_dp_account' => !empty($data['order_with_dp']) ? $data['order_with_dp_account'] : null,                    
                        'order_date_due' => !empty($set_date_due) ? $set_date_due : null,
                        'order_sales_id' => !empty($data['order_sales_id']) ? $data['order_sales_id'] : null,
                        'order_contact_name' => $order_contact_name,
                        'order_contact_phone' => $order_contact_phone                        
                    );
                    // var_dump($params);die;
                    //Check Data Exist
                    $params_check = array(
                        'order_number' => $order_number,
                        'order_branch_id' => $session_branch_id
                    );
                    $check_exists = $this->Order_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Order_model->add_order($params);
                        if($set_data==true){
                            $trans_id = $set_data;
                            $trans_list = $data['order_list'];

                            $get_order = $this->Order_model->get_order($trans_id);
                            foreach($trans_list as $index => $value){
                                
                                $params_update_trans_item = array(
                                    'order_item_date' => $get_order['order_date'],
                                    'order_item_order_id' => $trans_id,
                                    'order_item_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                                    'order_item_contact_id' => !empty($order_contact) ? $order_contact : null,
                                    'order_item_contact_id_2' => !empty($data['order_sales_id']) ? $data['order_sales_id'] : null,                                   
                                );

                                if($identity !== 7){
                                    $params_update_trans_item['order_item_flag'] = 1;
                                }else{
                                    $params_update_trans_item['order_item_flag'] = 1;
                                }

                                $this->Order_model->update_order_item($value,$params_update_trans_item);
                            }
                            
                            $order_id = $trans_id;
                            /*
                            if(intval($order_id) > 0){
                                $params = array(
                                    'order_item_order_id' => $order_id
                                );                         
                                $get_total_order_item = $this->Order_model->get_order_item_price_total($params);
                                $params_total = array(
                                    'order_total' => $get_total_order_item['order_item_total']
                                );                       
                                $update_order = $this->Order_model->update_order($order_id,$params_total);
                                $return->order_id = 1;
                            } 
                            */
                            //Set To Journal
                            if($config_post_to_journal==true){
                                if($identity==1 or $identity==2){
                                    $operator = $this->journal_for_order("create",$trans_id);
                                }
                                $return->journal_for_order = 1;
                            }

                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 2,
                                'activity_table' => 'orders',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $order_number,                        
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
                                'order_number' => $order_number
                            ); 
                        }
                    }else{
                        $return->message='Nomor sudah digunakan';                    
                    }
                    break;
                case "create-item":
                    $post_data  = $this->input->post('data');
                    // $data    = base64_decode($post_data);
                    $data       = json_decode($post_data, TRUE);
                    $order_id   = !empty($data['id']) ? $data['id'] : null;
                    $tipe       = !empty($data['tipe']) ? $data['tipe'] : null;
                    $produk     = !empty($data['produk']) ? $data['produk'] : null;
                    $satuan     = !empty($data['satuan']) ? $data['satuan'] : null;                
                    $harga      = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                    $diskon     = !empty($data['diskon']) ? $data['diskon'] : 0.00;                 
                    $qty        = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                    $qty_kg     = !empty($data['qty_kg']) ? $data['qty_kg'] : 0.00;                
                    $total      = 0;
                    $tgl        = !empty($data['tgl']) ? $data['tgl'] : false;

                    if($tgl !== false){
                        $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                        $jam = date('h:i:s');
                        $set_date = $tgl.' '.$jam;
                    }else{
                        $set_date = date('YmdHis');
                    }

                    // if(!empty($)){
                        // $harga = str_replace(',','',$harga);
                        // $qty = str_replace(',','',$qty);                    
                        $total = $harga*$qty;
                    // }
                    $set_flag=0;
                    if(intval($order_id) > 0){
                        $set_flag = 1;
                    }    

                    $params_items = array(
                        'order_item_order_id' => $order_id, 
                        // 'order_item_id_order' => $data['order_id'],
                        // 'order_item_id_order_detail' => $data['order_detail_id'],
                        'order_item_product_id' => $produk,
                        // 'order_item_id_lokasi' => $data['lokasi_id'],
                        'order_item_date' => $set_date,
                        'order_item_unit' => $satuan,
                        'order_item_qty' => $qty,
                        'order_item_qty_weight' => $qty_kg,                    
                        'order_item_price' => $harga,
                        // 'order_item_keluar_qty' => $data['qty'],
                        // 'order_item_keluar_harga' => $data['harga'],
                        'order_item_type' => $tipe,
                        'order_item_discount' => 0,
                        'order_item_total' => $total,
                        'order_item_date_created' => date("YmdHis"),
                        'order_item_date_updated' => date("YmdHis"),
                        'order_item_user_id' => $session_user_id,
                        'order_item_branch_id' => $session_branch_id,
                        'order_item_flag' => $set_flag,
                        'order_item_ref_id' => !empty($data['ref_id']) ? $data['ref_id'] : null,
                        'order_item_contact_id' => !empty($data['contact_id']) ? $data['contact_id'] : null,
                        'order_item_contact_id_2' => !empty($data['contact_id_2']) ? $data['contact_id_2'] : null                        
                    );

                    //Check Data Exist Trans Item
                    $params_check = array(
                        'order_item_type' => $identity,
                        'order_item_product_id' => $data['produk'],
                        'order_item_branch_id' => $session_branch_id,
                        'order_item_flag' => 0
                    );
                    $check_exists = $this->Order_model->check_data_exist_item($params_check);
                    // var_dump($check_exists);die;
                    $check_exists = false;
                    if($check_exists==false){

                        $set_data=$this->Order_model->add_order_item($params_items);
                        if($set_data==true){

                            //Check the Product is Stock Card Mode ?
                            $get_product = $this->Produk_model->get_produk($data['produk']);
                            /*
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
                            */
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
                            $return->message='Berhasil menambah '.$get_product['product_name'];
                            $return->result= array(
                                'id' => $set_data
                                // 'kode' => $data['kode']
                            ); 
                        }
                        /*
                        if(intval($order_id) > 0){
                            $params = array(
                                'order_item_order_id' => $order_id
                            );                         
                            $get_total_order_item = $this->Order_model->get_order_item_price_total($params);
                            $params_total = array(
                                'order_total' => $get_total_order_item['order_item_total']
                            );                       
                            $update_order = $this->Order_model->update_order($order_id,$params_total);
                            $return->order_id = 1;
                        }
                        */             
                    }else{
                        $get_order_item = $this->Order_model->get_order_item_params($params_check);
                        $item_id = $get_order_item['order_item_id'];
                        $item_qty = $get_order_item['order_item_qty'];
                        $item_price = $get_order_item['order_item_price'];
                        
                        $update_qty = floatval($item_qty + 1);
                        $params_update = array(
                            'order_item_qty' => $update_qty,
                            'order_item_total' => floatval($item_price * $update_qty)
                        );
                        
                        $this->Order_model->update_order_item($item_id,$params_update);
                        $return->status=1;
                        $return->message='Memperbarui Quantity';
                        $return->result= array(
                            'id' => $item_id
                            // 'kode' => $data['kode']
                        );                     
                        // $return->message='Produk sudah diinput';                    
                    }
                    break;
                case "create-item-note": //POS
                    $id = intval($data['id']);
                    $params = array(
                        'order_item_note' => $data['note'],
                    );
                    if(intval($id) > 0){
                        $set_update=$this->Order_model->update_order_item($id,$params);
                        if($set_update==true){
                            $return->status=1;
                            $return->message='Success';
                        }
                    }                
                    break;
                case "cancel": //Not Used
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
                                                                            
                case "read":
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
                    break;
                case "read-item": die;
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
                case "update":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);

                    $id = $data['id'];
                    $get_data = $this->Order_model->get_order($id);
                    
                    // $order_number = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $order_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $order_ref_number = !empty($data['nomor_ref']) ? $data['nomor_ref'] : null;
                    $order_note = !empty($data['keterangan']) ? $data['keterangan'] : null;

                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = substr($get_data['order_date'],10,9);
                    $set_date = $tgl.$jam;

                    $tgl_tempo = !empty($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : null;
                    $set_date_due = $tgl_tempo.$jam;

                    //JSON Stringify POST
                    $params = array(
                        // 'order_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,                    
                        // 'order_type' => !empty($data['tipe']) ?$data['tipe'] : null,
                        // 'order_number' => !empty($order_number) ? $order_number : null,
                        'order_date' => !empty($set_date) ? $set_date : null,
                        'order_contact_id' => !empty($order_contact) ? $order_contact : null,                    
                        // 'order_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,                    
                        // 'order_discount' => !empty($data['diskon']) ? $data['diskon'] : 0,
                        // 'order_total' => !empty($data['total']) ? $data['total'] : 0,               
                        'order_note' => !empty($order_note) ? $order_note : null,                    
                        // 'order_user_id' => !empty($session_user_id) ? $session_user_id : null,                    
                        // 'order_ref_id' => !empty($data['ref']) ? $data['ref'] : null,                         
                        // 'order_date_created' => date("YmdHis"),
                        'order_date_updated' => date("YmdHis"),                    
                        // 'order_flag' => !empty($data['status']) ? 1 : 1,
                        // 'order_trans_id' => !empty($data['trans_id']) ? $data['trans_id'] : null,
                        'order_ref_number' => !empty($order_ref_number) ? $order_ref_number : null,
                        'order_with_dp' => !empty($data['order_with_dp']) ? str_replace(',','',$data['order_with_dp']) : '0.00',
                        'order_with_dp_account' => !empty($data['order_with_dp']) ? $data['order_with_dp_account'] : null,
                        'order_date_due' => !empty($set_date_due) ? $set_date_due : null
                    );                
                    // var_dump($params);die;
                    if(!empty($data['order_sales_id'])){
                        $params['order_sales_id'] = $data['order_sales_id'];
                    }

                    // var_dump($params);die;
                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */
                
                    $set_update=$this->Order_model->update_order($id,$params);
                    if($set_update==true){
                        $get_order_items = $this->Order_model->get_all_order_items(array('order_item_order_id'=>$id),null,null,null,null,null);
                        foreach($get_order_items as $index => $value){
                            
                            $params_update_order_item = array(
                                'order_item_date' => $set_date                       
                            );
                            $this->Order_model->update_order_item_by_order_id($id,$params_update_order_item);
                        }
                        /*
                        $order_id = $id;
                        if(intval($order_id) > 0){
                            $params = array(
                                'order_item_order_id' => $order_id
                            );                         
                            $get_total_order_item = $this->Order_model->get_order_item_price_total($params);
                            $params_total = array(
                                'order_total' => $get_total_order_item['order_item_total']
                            );                       
                            $update_order = $this->Order_model->update_order($order_id,$params_total);
                            $return->order_id = 1;
                        } 
                        */          

                        if($config_post_to_journal==true){
                            if($identity==1 or $identity==2){
                                $operator = $this->journal_for_order("create",$id);
                            }
                        }

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
                    break;
                case "update-label":
                    $id = !empty($this->input->post('order_id')) ? $this->input->post('order_id') : 0;
                    $order_label = !empty($this->input->post('order_label')) ? $this->input->post('order_label') : null;

                    if(strlen(intval($id)) > 0){
                        $get_data = $this->Order_model->get_order($id);
                        
                        $params = array(
                            'order_label' => $order_label,
                        );
                        
                        $set_data = $this->Order_model->update_order($id,$params);
                        if($set_data){
                            $return->status  = 1;
                            $return->message = 'Berhasil memperbarui';
                            $return->result = array(
                                'order_id' => $get_data['order_id'],
                                'order_label' => $order_label 
                            );
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed set label';
                    }
                    break;
                case "update-order-ref":
                    $id = $data['id'];
                    $ref_id_old = $data['ref_id_old'];
                    $ref_id_new = $data['ref_id_new'];                    

                    if(intval($id) > 0){
                        $get_data = $this->Order_model->get_order($id);
                        
                        $params = array(
                            'order_ref_id' => $ref_id_new,
                        );
                        
                        $set_data = $this->Order_model->update_order($id,$params);
                        if($set_data){

                            //Update Room / Table NEW
                            if(!empty($ref_id_new)){
                                $where_ref = array(
                                    'ref_id' => $ref_id_new
                                );
                                $params_ref = array(
                                    'ref_use_type' => 1
                                );
                                $this->Referensi_model->update_referensi_custom($where_ref,$params_ref);
                                $get_ref = $this->Referensi_model->get_referensi($ref_id_new);
                            }

                            //Update Room / Table OLD
                            if(!empty($ref_id_new)){
                                $where_ref = array(
                                    'ref_id' => $ref_id_old
                                );
                                $params_ref = array(
                                    'ref_use_type' => 0
                                );
                                $this->Referensi_model->update_referensi_custom($where_ref,$params_ref);
                            }

                            $return->status  = 1;
                            $return->message = 'Berhasil Memindahkan ke '.$get_ref['ref_name'];
                            // $return->result = array(
                            //     'order_id' => $get_data['order_id'],
                            //     'order_label' => $order_label 
                            // );
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed set label';
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
                    $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;                                
                    $total = 0;

                    // if(!empty($)){
                        // $harga = str_replace(',','',$harga);
                        // $qty = str_replace(',','',$qty);                    
                        $total = $harga*$qty;
                    // }
                    
                    $params = array(
                        'order_item_id' => $id,
                        // 'order_item_order_id' => $data['order_id'],
                        // 'order_item_order_item_id' => $data['order_detail_id'],
                        'order_item_product_id' => $produk,
                        // 'order_item_location_id' => $data['lokasi_id'],
                        // 'order_item_date' => date("YmdHis"),
                        'order_item_unit' => $satuan,
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
                    $get_order = $this->Order_model->get_order_item($id);       
                    $order_id = $get_order['order_item_order_id'];     
                    /*                    
                    if(intval($order_id) > 0){
                        $params = array(
                            'order_item_order_id' => $order_id
                        );                         
                        $get_total_order_item = $this->Order_model->get_order_item_price_total($params);
                        $params_total = array(
                            'order_total' => $get_total_order_item['order_item_total']
                        );                       
                        $update_order = $this->Order_model->update_order($order_id,$params_total);
                        $return->order_id = 1;
                    } 
                    */                 
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
                    break;           
                case "update-item-flag":
                    $id = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                    $flag = $this->input->post('flag');

                    if(strlen(intval($id)) > 0){
                        $set_flag = ($flag==0) ? 1 : 0;
                        $get_data = $this->Order_model->get_order_item($id);
                        
                        $params = array('order_item_flag'=>$set_flag);
                        // var_dump($params);
                        
                        $set_data = $this->Order_model->update_order_item($id,$params);
                        if($set_data){
                            $return->status  = 1;
                            $return->message = ($flag==0) ? 'Checked' : 'Unchecked';
                            $return->result = array(
                                'order_id' => $get_data['order_item_order_id'],
                                'order_item_id' => $get_data['order_item_id'] 
                            );
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed checked';
                    }
                    break;
                case "update-item-qty":
                    $id = !empty($this->input->post('order_item_id')) ? $this->input->post('order_item_id') : 0;
                    $order_item_qty = $this->input->post('order_item_qty');

                    if(strlen(intval($id)) > 0){
                        $get_data = $this->Order_model->get_order_item($id);
                        
                        $params = array(
                            'order_item_qty' => $order_item_qty,
                            'order_item_flag' => 0
                        );
                        
                        $set_data = $this->Order_model->update_order_item($id,$params);
                        if($set_data){
                            $return->status  = 1;
                            $return->message = 'Berhasil memperbarui';
                            $return->result = array(
                                'order_id' => $get_data['order_item_order_id'],
                                'order_item_id' => $get_data['order_item_id'] 
                            );
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed checked';
                    }
                    break;
                case "delete":
                    $id = $this->input->post('id');
                    $number = $this->input->post('number');                               
                    // $flag = $this->input->post('flag');
                    $flag=4;

                    // $set_data=$this->Order_model->update_order($id,array('order_flag'=>$flag));
                    // $set_data=$this->Order_model->update_order_item_by_order_id($id,array('order_item_flag'=>$flag));
                    $set_data=$this->Order_model->delete_order_item_by_order_id($id);    
                    $set_data=$this->Order_model->delete_order($id); 

                    if($set_data==true){  
                        
                        if($config_post_to_journal==true){
                            if($identity==1 or $identity==2){
                                $operator = $this->journal_for_order("delete",$id);
                            }
                        }

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
                    break;
                case "delete-item": //Perlu dicek PO, SO, POS
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
                    $get_order = $this->Order_model->get_order_item($id);
                    $order_id = $get_order['order_item_order_id'];

                    $set_data=$this->Order_model->delete_order_item($id);       
                    /*
                    //Move to Triger
                    // update order_total_dpp, order_discount, order_total
                    if(intval($order_id) > 0){
                        $params = array(
                            'order_item_order_id' => $order_id
                        );                         
                        $get_total_order_item = $this->Order_model->get_order_item_price_total($params);
                        $params_total = array(
                            'order_total' => $get_total_order_item['order_item_total']
                        );                       
                        $update_order = $this->Order_model->update_order($order_id,$params_total);
                        $return->order_id = 1;
                    }
                    */
                    if($set_data==true){
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 5,
                            'activity_table' => 'order_items',
                            'activity_table_id' => $id,                            
                            'activity_text_1' => $msg,
                            'activity_text_2' => $kode.' -  '.$nama,                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $msg,
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
                    break;                    
                case "journal":
                    $id = $this->input->post('id');

                    if($identity == 6){
                        $set_identity = 6;
                    }elseif($identity == 7){
                        $set_identity = 7;
                    }else{
                        $set_identity = null;
                    }
                    $params = array(
                        'journal_item_order_id' => $id
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
                /* POS (Booking n Payment) */
                case "pos-create":
                    $generate_nomor         = $this->request_number_for_order($identity);
                    $order_number           = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $order_contact          = !empty($data['kontak']) ? $data['kontak'] : null;
                    $order_ref_number       = !empty($data['ref_number']) ? $data['ref_number'] : null;
                    $order_note             = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $order_contact_name     = !empty($data['order_contact_name']) ? $data['order_contact_name'] : null;
                    $order_contact_phone    = !empty($data['order_contact_phone']) ? $data['order_contact_phone'] : null;
                    $jam                    = date('h:i:s');
                    $tgl                    = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2)." ".$jam;

                    if(strlen($order_contact) < 1){ // When Non Kontak
                        $where_non = array(
                            'contact_branch_id' => $session_branch_id,
                            'contact_flag' => 5,
                            'contact_type' => 2
                        );
                        $get_non_contact = $this->Kontak_model->get_kontak_custom($where_non);
                        $order_contact = $get_non_contact['contact_id'];
                    }

                    //JSON Stringify POST
                    $params = array(
                        'order_session' => $this->random_code(20),
                        'order_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                        'order_type' => !empty($data['tipe']) ?$data['tipe'] : null,
                        'order_number' => !empty($order_number) ? $order_number : null,
                        'order_date' => !empty($tgl) ? $tgl : date('YmdHis'),
                        'order_contact_id' => !empty($order_contact) ? $order_contact : null,
                        'order_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'order_discount' => !empty($data['diskon']) ? $data['diskon'] : 0,
                        'order_total_dpp' => !empty($data['total']) ? $data['total'] : 0,
                        'order_total' => !empty($data['total']) ? $data['total'] : 0,
                        'order_note' => !empty($order_note) ? $order_note : null,
                        'order_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        'order_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        'order_date_created' => date("YmdHis"),
                        'order_date_updated' => date("YmdHis"),                    
                        'order_flag' => !empty($data['status']) ? 1 : 0,
                        'order_trans_id' => !empty($data['trans_id']) ? $data['trans_id'] : null,
                        'order_ref_number' => !empty($order_ref_number) ? $order_ref_number : null,
                        'order_with_dp' => !empty($data['order_with_dp']) ? str_replace(',','',$data['order_with_dp']) : '0.00',
                        'order_with_dp_account' => !empty($data['order_with_dp']) ? $data['order_with_dp_account'] : null,                    
                        'order_date_due' => !empty($tgl) ? $tgl : date('YmdHis'),
                        'order_sales_id' => !empty($data['order_sales_id']) ? $data['order_sales_id'] : null,
                        'order_contact_name' => $order_contact_name,
                        'order_contact_phone' => $order_contact_phone                        
                    );
                    // var_dump($params);die;
                    //Check Data Exist
                    $params_check = array(
                        'order_number' => $order_number,
                        'order_branch_id' => $session_branch_id
                    );
                    $check_exists = $this->Order_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Order_model->add_order($params);
                        if($set_data){
                            $order_id = $set_data;
                            $get_order = $this->Order_model->get_order($order_id);
                            foreach($data['order_list'] as $value){
                                $params_update_trans_item = array(
                                    'order_item_date' => $get_order['order_date'],
                                    'order_item_order_id' => $order_id,
                                    'order_item_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                                    'order_item_contact_id' => !empty($order_contact) ? $order_contact : null,
                                    'order_item_contact_id_2' => !empty($data['order_sales_id']) ? $data['order_sales_id'] : null,
                                    'order_item_flag' => 1                           
                                );
                                $this->Order_model->update_order_item($value,$params_update_trans_item);
                            }

                            //Set To Journal
                            if($config_post_to_journal==true){
                                if($identity==1 or $identity==2){
                                    $operator = $this->journal_for_order("create",$order_id);
                                }
                                $return->journal_for_order = 1;
                            }

                            //Update Room / Table
                            if(!empty($data['ref'])){
                                $where_ref = array(
                                    'ref_id' => $data['ref'] 
                                );
                                $params_ref = array(
                                    'ref_use_type' => 1
                                );
                                $this->Referensi_model->update_referensi_custom($where_ref,$params_ref);
                            }
                            
                            //Update Employee (Waitress / Terapis)
                            /*
                            if(!empty($data['order_sales_id'])){
                                $where_con = array(
                                    'contact_id' => $data['order_sales_id'] 
                                );
                                $params_con = array(
                                    'contact_use_type' => 1
                                );
                                $this->Kontak_model->update_kontak_custom($where_con,$params_con);
                            }
                            */

                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 2,
                                'activity_table' => 'orders',
                                'activity_table_id' => $order_id,                            
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $order_number,                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);    
                            /* End Activity */            

                            $return->status=1;
                            $return->message='Berhasil menyimpan '.$order_number;
                            $return->result= array(
                                'order_id' => $order_id,
                                'order_number' => $order_number
                            ); 
                        }
                    }else{
                        $return->message='Nomor sudah digunakan';                    
                    }
                    break;                
                case "pos-create-item": //Required ref_id
                    $order_id   = !empty($data['id']) ? $data['id'] : null;
                    $tipe       = !empty($data['tipe']) ? $data['tipe'] : null;
                    $produk     = !empty($data['produk']) ? $data['produk'] : null;
                    $satuan     = !empty($data['satuan']) ? $data['satuan'] : null;                
                    $harga      = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                    $diskon     = !empty($data['diskon']) ? $data['diskon'] : 0.00;                 
                    $qty        = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                    $qty_kg     = !empty($data['qty_kg']) ? $data['qty_kg'] : 0.00;                
                    $total      = 0;
                    $tgl        = !empty($data['tgl']) ? $data['tgl'] : false;
                    $ref_id     = !empty($data['ref_id']) ? intval($data['ref_id']) : 0;

                    $set_date = date('YmdHis');
                    if($tgl !== false){
                        $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                        $jam = date('h:i:s');
                        $set_date = $tgl.' '.$jam;
                    }
                                      
                    $total = $harga*$qty;
                    $set_flag=0;
                    if(intval($order_id) > 0){
                        $set_flag = 1;
                    }    

                    $params_items = array(
                        'order_item_order_id' => $order_id, 
                        // 'order_item_id_order' => $data['order_id'],
                        // 'order_item_id_order_detail' => $data['order_detail_id'],
                        'order_item_product_id' => $produk,
                        'order_item_location_id' => $session_location_id,
                        'order_item_date' => $set_date,
                        'order_item_unit' => $satuan,
                        'order_item_qty' => $qty,
                        'order_item_qty_weight' => $qty_kg,                    
                        'order_item_price' => $harga,
                        // 'order_item_keluar_qty' => $data['qty'],
                        // 'order_item_keluar_harga' => $data['harga'],
                        'order_item_type' => $tipe,
                        'order_item_discount' => 0,
                        'order_item_total' => $total,
                        'order_item_date_created' => date("YmdHis"),
                        'order_item_date_updated' => date("YmdHis"),
                        'order_item_user_id' => $session_user_id,
                        'order_item_branch_id' => $session_branch_id,
                        'order_item_flag' => $set_flag,
                        // 'order_item_ref_id' => !empty($data['ref_id']) ? $data['ref_id'] : null,
                        'order_item_contact_id' => !empty($data['contact_id']) ? $data['contact_id'] : null,
                        'order_item_contact_id_2' => !empty($data['contact_id_2']) ? $data['contact_id_2'] : null                        
                    );

                    //Check Data Exist Trans Item
                    $params_check = array(
                        'order_item_type' => intval($identity),
                        'order_item_product_id' => intval($data['produk']),
                        'order_item_branch_id' => intval($session_branch_id),
                        'order_item_ref_id' => intval($data['ref_id']),
                        'order_item_flag' => 0
                    );

                    if($ref_id > 0){
                        $params_items['order_item_ref_id'] = $ref_id;
                        $params_check['order_item_ref_id'] = $ref_id;                        
                    }

                    $check_exists = $this->Order_model->check_data_exist_item($params_check);
                    if($check_exists==false){

                        $set_data=$this->Order_model->add_order_item($params_items);
                        if($set_data==true){

                            //Check the Product is Stock Card Mode ?
                            $get_product = $this->Produk_model->get_produk($data['produk']);
                            /*
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
                            */
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
                            $return->message=$get_product['product_name'];
                            $return->result= array(
                                'id' => $set_data
                                // 'kode' => $data['kode']
                            ); 
                        }           
                    }else{
                        $get_order_item = $this->Order_model->get_order_item_params($params_check);
                        $params_update = array(
                            'order_item_qty' => floatval($get_order_item['order_item_qty'] + 1),
                            'order_item_total' => floatval($get_order_item['order_item_price'] * floatval($get_order_item['order_item_qty'] + 1))
                        );
                        $this->Order_model->update_order_item($get_order_item['order_item_id'],$params_update);
                        $return->status = 1;
                        $return->message = 'Memperbarui Data';
                        $return->result = array(
                            'id' => $get_order_item['order_item_id']
                            // 'kode' => $data['kode']
                        );
                        // $return->message='Produk sudah diinput';                    
                    }
                    break;                       
                case "pos-create-item-addon": //Required order_id
                    $harga = str_replace(',','', $data['harga']);
                    $qty = str_replace(',','', $data['qty']);                
                    $total = $harga*$qty;

                    if(intval($data['order_id']) > 0){
                        $get_order = $this->Order_model->get_order(intval($data['order_id']));
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
                            'order_item_flag' => 1,
                            'order_item_ref_id' => $get_order['order_ref_id'],
                            'order_item_contact_id' => $get_order['order_contact_id']

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
                                $return->message='Berhasil Menambahkan';
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
                    }else{
                        $return->message = 'Data tidak ditemukan';
                    }
                    break;                                  
                case "pos-create-item-plus-minus": //Required order_item_id
                    $id         = intval($data['id']);
                    $operator   = $data['operator'];
                    $qty        = $data['qty'];
                    $price      = $data['price'];
                    $discount   = $data['discount'];                

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
                    break;
                case "pos-create-item-discount":
                    $order_id       = $data['order_id'];
                    $id             = $data['id'];
                    $qty            = $data['qty'];
                    $price          = $data['price'];
                    $discount       = $data['discount'];                
                    $total          = $data['total'];

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
                        if($set_update){
                            //Move to Triger
                            // update order_total_dpp, order_discount, order_total
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
                    break;
                case "pos-cancel":
                    $id     = intval($data['id']);
                    $ref    = intval($data['ref_id']);       
                    $usern    = $data['user'];       
                    $passs    = $data['password'];                                                
                    $number = $this->input->post('number');
                    if((intval($id) > 0) && (intval($ref) > 0)){
                        $params_user = array(
                            'user_branch_id' => $session_branch_id,
                            'user_username' => $usern,
                            'user_password' => md5($passs),
                            'user_user_group_id <' => 4
                        );
                        $auth_user = $this->User_model->check_data_exist($params_user);
                        if($auth_user==true){
                            $params = array(
                                'order_flag' => 4
                            );
                            $set_data   = $this->Order_model->update_order($id,$params);
                            $get_order  = $this->Order_model->get_order($id);
                            $set_data   = $this->Order_model->update_order_item_custom(array('order_item_order_id'=>$id),array('order_item_flag'=>4));
                            
                            if($set_data){
                                //Found Room / Table ? Update Reference
                                if(!empty($ref)){
                                    $where_ref = array(
                                        'ref_id' => $ref
                                    );
                                    $params_ref = array(
                                        'ref_use_type' => 0,
                                        'ref_date_updated' => date("YmdHis")
                                    );
                                    $this->Referensi_model->update_referensi_custom($where_ref,$params_ref);
                                }             

                                //Update Employee (Waitress / Terapis)
                                if(!empty($get_order['order_sales_id'])){
                                    $where_con = array(
                                        'contact_id' => $get_order['order_sales_id'] 
                                    );
                                    $params_con = array(
                                        'contact_use_type' => 0
                                    );
                                    $this->Kontak_model->update_kontak_custom($where_con,$params_con);
                                }                                              
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
                                $return->message='Berhasil membatalkan';
                            }
                        }else{
                            $return->message='Gagal otorisasi user';
                        }                
                    }else{
                        $return->message= $this->order_alias.' tidak ditemukan';
                    }
                    break;                    
                case "pos-read":
                    $order_id = !empty($data['order_id']) ? $data['order_id'] : 0;
                    if($order_id > 0){
                        $params = array(
                            'order_item_order_id' => intval($order_id)
                        );
                        $get_data_items = $this->Order_model->get_all_order_items($params,null,100,0,'order_item_id','asc');
                        $datas = $this->Order_model->get_order($order_id);;
                        if($datas){
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
                            if(isset($data_items)){
                                $total=count($data_items);
                                $return->status     = 1; 
                                $return->message    = 'Loaded'; 
                                $return->total_records=$total;
                                $return->result = $datas;        
                                $return->total_produk   = count($data_items);
                                $return->subtotal       = number_format($subtotal,0,'.',',');
                                $return->total_diskon   = number_format($total_diskon,0,'.',',');
                                $return->total          = number_format($subtotal-$total_diskon,0,'.',',');      
                                $return->total_dp       = number_format($datas['order_with_dp'],0,'.',',');   
                                $return->total_grand    = ($subtotal-$total_diskon)-$datas['order_with_dp'];
                                $return->result_item    = $data_items;
                            }else{ 
                                $return->status         = 0; 
                                $return->message        = 'Tidak ada item Produk'; 
                                $return->total_records  = 0;
                                $return->result         = $datas;   
                            }
                            // $return->total_records = $total;                 
                            // $return->status=1;
                            // $return->message='Success';
                            // $return->result=$datas;
                        }               
                        $return->params = $params;
                    }                     
                    break;                    
                case "pos-update-item-product-price":
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
                    break;
                case "pos-delete-item-note": //Required order_item_id
                    $id = $data['id'];
                    $params = array(
                        'order_item_note' => '',
                    );
                    if(intval($id) > 0){
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
                    break;
                case "pos-delete-item-discount":
                    $order_id   = $data['order_id'];
                    $id         = $data['id'];
                    $qty        = $data['qty'];
                    $price      = $data['price'];

                    $set_price = $price;
                    $set_qty = $qty;
                    $set_total = $set_price*$set_qty;
                    if(intval($id) > 0){
                        $params = array(
                            'order_item_qty' => $set_qty,
                            'order_item_price' => $set_price,
                            'order_item_discount' => 0,
                            'order_item_total' => $set_total,
                        );
                        $set_update=$this->Order_model->update_order_item($id,$params);
                        if($set_update){
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
                    break;
                case "pos-load":
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
                    $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $params_datatable = array(
                        'orders.order_date >' => $date_start,
                        'orders.order_date <' => $date_end,
                        'orders.order_type' => intval($identity),
                        'orders.order_branch_id' => intval($session_branch_id)
                    );
                    if($kontak > 0){
                        $params_datatable = array(
                            'orders.order_date >' => $date_start,
                            'orders.order_date <' => $date_end,
                            'orders.order_type' => intval($identity),
                            'orders.order_branch_id' => intval($session_branch_id),
                            'orders.order_contact_id' => intval($kontak)                   
                        );                    
                    }

                    //POS Filter
                    if($identity == 222){
                        // $params_datatable['orders.order_flag >'] = 0;
                    }else{
                        $params_datatable['orders.order_flag <'] = 4;
                    }
                    $datas_count = $this->Order_model->get_all_orders_pos_count($params_datatable,$search);                               
                    if($datas_count > 0){ //Data exist
                        $datas       = $this->Order_model->get_all_orders_pos($params_datatable, $search, $limit, $start, $order, $dir);
                        
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$datas_count;
                        $return->result=$datas;        
                    }else{ 
                        $return->status=0; 
                        $return->message='No data'; 
                        $return->total_records=$datas_count;
                        $return->result=array();    
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->params             = $params_datatable;
                    $return->search             = $search;                       
                    break;                        
                case "pos-load-product-tab":
                    $start = 0;
                    $limit = 100;
                    $order = 'category_name';
                    $dir = 'ASC';
                    $search=null;
                    $params_datatable = array(
                        'category_type' => 1, //Product Categories
                        'category_flag' => 1,
                        'category_branch_id' => $session_branch_id
                    );                
                    $datas = $this->Kategori_model->get_all_categoriess($params_datatable, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Kategori_model->get_all_categoriess_count($params_datatable,$search);                                
                    if(isset($datas)){ //Data exist
                        $total=$datas_count;
                        $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                        $return->result=$datas;        
                    }else{ 
                        $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    $return->params = $params_datatable;
                    break;
                case "pos-load-product-tab-detail":
                    $limit = $this->input->post('length');
                    $start = $this->input->post('start');
                    $search = array();
                    if(strlen($data['search']) > 2){
                        $search['product_name'] = $data['search'];
                    }
                    $start = 0;
                    $limit = 100;
                    $order = 'product_name';
                    $dir = 'ASC';
                    $category_id = $data['category_id'];
                    if($category_id > 0){
                        $params_datatable = array(
                            'product_branch_id' => intval($session_branch_id),          
                            'product_category_id' => intval($category_id), //Product Categories
                            'product_flag' => 1,
                            // 'product_type' => 2, //1=Barang, 2=Jasa
                        );
                    }else if($category_id == -1){
                        $params_datatable = array(
                            'product_flag' => 1,
                            'product_type' => 1, //Barang
                            'product_price_promo >' => 0, //Product Categories
                            'product_branch_id' => intval($session_branch_id)                            
                        );
                    }else{                    
                        $params_datatable = array(
                            'product_branch_id' => intval($session_branch_id),                            
                            'product_flag' => 1,
                            // 'product_type' => 2, 1=Barang, 2=Jasa
                            // 'product_category_id !=' => 0,                    
                            // 'product_ref_id ' => $reference_id //Product Categories
                        );
                    }

                    // var_dump($params_datatable,$search,$limit,$start,$order,$dir);die;
                    $datas_count = $this->Produk_model->get_all_produks_count($params_datatable,$search);
                    if($datas_count > 0){
                        $datas = $this->Produk_model->get_all_produks($params_datatable, $search, $limit, $start, $order, $dir);
                        $total=$datas_count;
                        $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                        $return->result=$datas;        
                    }else{ 
                        $total=0; 
                        $return->status=0; $return->message='Produk tidak tersedia'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    $return->params = $params_datatable;                    
                    break;                
                case "pos-load-ref": //Requires ref_type, Room, Table, Something on table 'reference'
                    $ref_type = $data['ref_type'];
                    $search = array();
                    if(strlen($data['search']) > 0){
                        $search['ref_name'] = $data['search'];
                    }
                    $start = 0;
                    $limit = 100;
                    $order = 'ref_name';
                    $dir = 'ASC';

                    $params_datatable = array(
                        'ref_type' => intval($ref_type),                    
                        'ref_flag' => 1,
                        'ref_branch_id' => intval($session_branch_id),
                        'ref_parent_id >' => 0
                    );
                    $datas_count = $this->Referensi_model->get_all_referensis_count($params_datatable,$search);                           
                    if($datas_count > 0){
                        $datas = array();
                        $set_data = array();                        
                        $get_data = $this->Referensi_model->get_all_referensis($params_datatable, $search, $limit, $start, $order, $dir);
                        foreach($get_data as $v){

                            $get_order = array();
                            $set_data = array();
                            if(intval($v['ref_use_type']) == 1){
                                $where = array(
                                    'order_flag =' => 0,
                                    'order_branch_id' => intval($session_branch_id),
                                    'order_ref_id' => intval($v['ref_id'])
                                );
                                $get_order = $this->Order_model->get_order_ref_custom($where);
                                // var_dump($get_order);die;
                                if(!empty($get_order)){
                                    $set_data = array(
                                        'order_id' => intval($get_order['order_id']),
                                        'order_number' => $get_order['order_number'],
                                        'order_total' => $get_order['order_total'],
                                        'order_flag' => $get_order['order_flag'],
                                        'ref_id' => intval($get_order['order_ref_id']),
                                        'ref_name' => $get_order['ref_name'],
                                        'contact_id' => intval($get_order['order_contact_id']),
                                        'contact_type' => intval($get_order['contact_type']),                                    
                                        'contact_name' => $get_order['contact_name'],
                                        'contact_session' => $get_order['contact_session'],                                                                        
                                        'sales_id' => intval($get_order['order_sales_id']),
                                        'sales_name' => $get_order['order_sales_name'],                                    
                                    );
                                }
                            }
                            $datas[] = array(
                                'ref_id' => intval($v['ref_id']),
                                'ref_branch_id' => intval($v['ref_branch_id']),
                                'ref_type' => intval($v['ref_type']),
                                'ref_code' => $v['ref_code'],
                                'ref_name' => $v['ref_name'],
                                'ref_note' => $v['ref_note'],
                                'ref_user_id' => intval($v['ref_user_id']),
                                'ref_date_created' => $v['ref_date_created'],
                                'ref_date_updated' => $v['ref_date_updated'],
                                'ref_flag' => intval($v['ref_flag']),
                                'ref_color' => $v['ref_color'],
                                'ref_background' => $v['ref_background'],
                                'ref_icon' => $v['ref_icon'],
                                'ref_use_type' => intval($v['ref_use_type']),
                                'order' => $set_data
                            );                            
                        }
                        $return->status=1; $return->message='Loaded';
                        $return->result=$datas;        
                    }else{  
                        $return->status=0; $return->message= $this->ref_alias.' belum di konfigurasi';
                        $return->result=array();    
                    }
                    $return->total_records=$datas_count;
                    $return->recordsTotal = $datas_count;
                    $return->recordsFiltered = $datas_count;
                    $return->params = $params_datatable;
                    break;                
                case "pos-load-unpaid": die;
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
                        'orders.order_type' => intval($identity),
                        'orders.order_flag' => 0,
                        'orders.order_branch_id' => intval($session_branch_id)
                    );
                    $datas = $this->Order_model->get_all_orders($params_request, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Order_model->get_all_orders_count($params_request,$search);
                    
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
                    $return->params = $params_request;                    
                    break;
                case "bill-add":
                    $id = !empty($data['id']) ? intval($data['id']) : 0;
                    if($id > 0){
                        $params_items = array(
                            'order_flag' => 3
                        );
                        $this->Order_model->update_order($id,$params_items);        
                        $return->status=1;
                        $return->message='Menambahkan '.$this->payment_alias;
                    }else{
                        $return->message = 'Gagal menambahkan';
                    }
                    break;
                case "bill-remove":
                    $id     = $this->input->post('id');
                    $ref    = $this->input->post('ref');        
                    $number = $this->input->post('number');                                
                    $params = array(
                        'order_flag' => 0
                    );
                    if($id > 0){
                        $set_data=$this->Order_model->update_order($id,$params);                
                        if($set_data){
                            $return->status=1;
                            $return->message='Membatalkan '.$this->payment_alias.' '.$ref;
                        }
                    }else{
                        $return->message = 'Gagal membatalkan';
                    }                
                    break;
                case "bill-load":
                    $params = array(
                        'order_flag' => 3,
                        'order_user_id' => intval($session_user_id),
                        'order_branch_id' => intval($session_branch_id)
                    );
                    $get_data = $this->Order_model->get_all_orders($params,null,100,0,'order_number','asc');             
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
                                    'order_item_qty' => number_format($v['order_item_qty'],0,'.',','),                            
                                    'order_item_price' => number_format($v['order_item_price'],0,'.',','),
                                    'order_item_discount' => number_format($v['order_item_discount'],0,'.',','),
                                    'order_item_total' => number_format($v['order_item_total'],0,'.',','),
                                    // 'order_item_total_after_discount' => number_format($v['order_item_total'],0,'.',','),                 
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
                                'order_date_format' => date("H:i, d-M-Y", strtotime($h['order_date'])),
                                'order_contact_id' => $h['order_contact_id'],
                                'order_total' => $h['order_total'],
                                'order_total_down_payment' => $h['order_with_dp'],
                                'order_total_grand' => $h['order_total']-$h['order_with_dp'],                            
                                'ref_id' => $h['ref_id'],
                                'ref_name' => $h['ref_name'],
                                'contact_is_member' => !empty($h['order_contact_name']) ? 0 : 1,  
                                'contact_code' => !empty($h['contact_code']) ? $h['contact_code'] : '',
                                'contact_phone' => !empty($h['order_contact_name']) ? $h['order_contact_phone'] : $h['contact_phone_1'],                                
                                'contact_name' => !empty($h['order_contact_name']) ? $h['order_contact_name'] : $h['contact_name'],
                                'contact_session' => $h['contact_session'],
                                'employee_name' => $h['employee_name'],                                
                                'user_name' => $h['user_fullname'],                          
                                'order_items' => $data_items
                            );
                            $subtotal = $subtotal + $h['order_total'];
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
                            $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas; 
                            $return->total_produk=count($datas);
                            $return->subtotal=number_format($subtotal,0,'.',',');
                            $return->total_down_payment=number_format($down_payment,0,'.',',');                        
                            $return->total_diskon=number_format($total_diskon,0,'.',',');
                            $return->total=number_format(($subtotal-$total_diskon)-$down_payment,0,'.',',');
                            $return->total_raw=($subtotal-$total_diskon)-$down_payment;
                            $return->order_list_id = $order_list_id;
                        }else{ 
                            $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Menunggu Pembayaran';
                    }else{
                        // $return->message= $this->order_alias.' yg akan dibayar tidak ditemukan';
                    }
                    $return->params = $params;
                    break;
                case "bill-create": //For Sales of POS / order->trans->journal // Penjualan Langsung Lunas
                    $next           = true;
                    $message        = '';
                    $type           = intval($data['method']); //1=Tunai, 3=Debit/Kredit, 5=Digital
                    $type_name      = $data['method_name'];

                    $total_before   = !empty($data['total_before']) ? str_replace(',','',$data['total_before']) : 0;
                    $total_after    = !empty($data['total']) ? str_replace(',','',$data['total']) : 0;
                    $total_received = !empty($data['received']) ? str_replace(',','',$data['received']) : 0;
                    $total_change   = !empty($data['change']) ? str_replace(',','',$data['change']) : 0;

                    $voucher_id     = !empty($data['voucher_id']) ? $data['voucher_id'] : 0;
                    
                    //Contact
                    $contact_id     = !empty($data['payment_contact_id']) ? $data['payment_contact_id'] : 0;
                    $contact_name   = !empty($data['payment_contact_name']) ? $data['payment_contact_name'] : null; //NonMember Used
                    $contact_phone  = !empty($data['payment_contact_phone']) ? $data['payment_contact_phone'] : null;                                        
                    // die;
                    if(intval($type) < 1){
                        $next =false;
                        $return->message = 'Metode pembayaran belum dipilih';
                    }
                    if(floatval($total_received) < 1){
                        $next=false;
                        $return->message = 'Masukkan jumlah yang sesuai';
                    }
                    if(floatval($total_after) < 1){
                        $next=false;
                        $return->message = 'Total Tagihan tidak sesuai';                        
                    }
                    // var_dump($next);die;
                    if($next){
                        // Make Params Transaction
                        $generate_session   = $this->random_session(20);
                        $generate_nomor     = $this->request_number_for_transaction(2);
                        $journal_number     = $this->request_number_for_journal(2);                
                        $journal_date       = date('YmdHis');
                        $journal_paid_type  = $type;
                        $journal_item_account = null;
                        $journal_item_ref     = !empty($data['journal_item_ref']) ? $data['journal_item_ref'] : null;

                        //Member ?
                        if(intval($contact_id) > 0){
                            $get_contact = $this->Kontak_model->get_kontak($contact_id);
                            $params['trans_contact_name'] = $contact_name;
                            $params['trans_contact_phone'] = $contact_phone;
                            $set_contact_id = $contact_id;
                            $set_contact_name = $get_contact['contact_name'];
                            $set_contact_phone = $get_contact['contact_phone_1'];                            
                        }else{
                            // When Non Kontak
                            $where_non = array(
                                'contact_branch_id' => $session_branch_id,
                                'contact_flag' => 5,
                                'contact_type' => 2
                            );
                            $get_contact = $this->Kontak_model->get_kontak_custom($where_non);
                            $params['trans_contact_name'] = $contact_name;
                            $params['trans_contact_phone'] = $contact_phone;
                            
                            $contact_id = $get_contact['contact_id'];
                            $set_contact_id = $contact_id;
                            $set_contact_name = $contact_name;
                            $set_contact_phone = $contact_phone;                            
                        }

                        if($type==1){ // 1=Cash 
                            $journal_item_account = $data['modal_akun_cash'];
                            $params = array(
                                'trans_type' => 2,
                                'trans_contact_id' => $contact_id,
                                'trans_branch_id' => $session_branch_id,
                                // 'trans_card_number' => $data['card_number'], 
                                // 'trans_card_bank_name' => $data['card_bank'],
                                // 'trans_card_account_name' => $data['card_name'],
                                // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                                // 'trans_card_type' => $data['card_type'],
                                // 'trans_digital_provider' => $data['digital_type'],
                                'trans_number' => $generate_nomor,
                                'trans_date' => date('YmdHis'),
                                // 'trans_note' => $data['note'],
                                'trans_user_id' => $session['user_data']['user_id'],
                                'trans_date_created' => date('YmdHis'),
                                'trans_date_updated' => date('YmdHis'),
                                'trans_flag' => 1,
                                'trans_total_dpp' => floatVal($total_before),                            
                                // 'trans_down_payment' => '0.00',          
                                // 'trans_discount' => $data['discount'],
                                // 'trans_return' => '0.00',                            
                                'trans_total' => floatVal($total_after),
                                'trans_total_paid' => floatVal($total_after),
                                'trans_change' => floatVal($total_change),
                                'trans_received' => floatVal($total_received),
                                'trans_paid' => 1,
                                'trans_paid_type' => $type,                                                                                                            
                                'trans_session' => $generate_session
                            );
                        }else if($type==2){ // 2=Bank
                            $journal_item_account = $data['modal_akun_transfer'];
                            $params = array(
                                'trans_type' => 2,
                                'trans_contact_id' => $contact_id,
                                'trans_branch_id' => $session_branch_id,                                
                                'trans_bank_account_name' => $data['transfer_nama_pengirim'],
                                'trans_bank_ref' => $data['transfer_nomor_ref_transfer'],
                                // 'trans_card_number' => $data['card_number'], 
                                // 'trans_card_bank_name' => $data['card_bank'],
                                // 'trans_card_account_name' => $data['card_name'],
                                // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                                // 'trans_card_type' => $data['card_type'],
                                // 'trans_digital_provider' => $data['digital_type'],
                                'trans_number' => $generate_nomor,
                                'trans_date' => date('YmdHis'),
                                // 'trans_note' => $data['note'],
                                'trans_user_id' => $session['user_data']['user_id'],
                                'trans_date_created' => date('YmdHis'),
                                'trans_date_updated' => date('YmdHis'),
                                'trans_flag' => 1,
                                'trans_total_dpp' => floatVal($total_before),
                                // 'trans_total_ppn' => '0.00',
                                // 'trans_down_payment' => '0.00',
                                // 'trans_discount' => $data['discount'],
                                // 'trans_return' => '0.00',                                                          
                                'trans_total' => floatVal($total_after),
                                'trans_total_paid' => floatVal($total_after),
                                'trans_change' => floatVal($total_change),
                                'trans_received' => floatVal($total_received),
                                'trans_paid' => 1,
                                'trans_paid_type' => $type,                               
                                'trans_session' => $generate_session
                            );
                        }else if($type==3){ //3=EDC
                            $journal_item_account = $data['modal_akun_edc'];
                            $params = array(
                                'trans_type' => 2,
                                'trans_contact_id' => $contact_id,
                                'trans_branch_id' => $session_branch_id,                                
                                'trans_card_number' => $data['card_number'], 
                                'trans_card_bank_name' => $data['card_bank'],
                                'trans_card_account_name' => $data['card_name'],
                                'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                                'trans_card_type' => $data['card_type'],
                                // 'trans_digital_provider' => $data['digital_type'],
                                'trans_number' => $generate_nomor,
                                'trans_date' => date('YmdHis'),
                                // 'trans_note' => $data['note'],
                                'trans_user_id' => $session['user_data']['user_id'],
                                'trans_date_created' => date('YmdHis'),
                                'trans_date_updated' => date('YmdHis'),
                                'trans_flag' => 1,
                                'trans_total_dpp' => floatVal($total_before),
                                // 'trans_down_payment' => '0.00',
                                // 'trans_discount' => $data['discount'],
                                // 'trans_return' => '0.00',                                 
                                'trans_total' => floatVal($total_after),
                                'trans_total_paid' => floatVal($total_after),
                                'trans_change' => floatVal($total_change),
                                'trans_received' => floatVal($total_received),
                                'trans_paid' => 1,
                                'trans_paid_type' => $type,                               
                                'trans_session' => $generate_session
                            );
                        }else if($type==4){ //4=Gratis not used
                            $journal_item_account = $data['modal_akun_gratis'];
                            $params = array(
                                'trans_type' => 2,
                                'trans_contact_id' => $contact_id,
                                'trans_branch_id' => $session_branch_id,                                
                                // 'trans_bank_account_name' => $data['transfer_nama_pengirim'],
                                // 'trans_bank_ref' => $data['transfer_nomor_ref_transfer'],
                                // 'trans_card_number' => $data['card_number'], 
                                // 'trans_card_bank_name' => $data['card_bank'],
                                // 'trans_card_account_name' => $data['card_name'],
                                // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                                // 'trans_card_type' => $data['card_type'],
                                // 'trans_digital_provider' => $data['digital_type'],
                                'trans_number' => $generate_nomor,
                                'trans_date' => date('YmdHis'),
                                // 'trans_note' => $data['note'],
                                'trans_user_id' => $session['user_data']['user_id'],
                                'trans_date_created' => date('YmdHis'),
                                'trans_date_updated' => date('YmdHis'),
                                'trans_flag' => 1,
                                'trans_total_dpp' => floatVal($total_before),
                                // 'trans_down_payment' => '0.00',
                                // 'trans_discount' => $data['discount'],
                                // 'trans_return' => '0.00',                            
                                'trans_total' => floatVal($total_after),
                                'trans_total_paid' => floatVal($total_after),
                                // 'trans_change' => floatVal($total_change),
                                // 'trans_received' => floatVal($total_received),
                                'trans_paid' => 1,
                                'trans_paid_type' => $type,                               
                                'trans_session' => $generate_session
                            );
                        }else if($type==5){ //5=QRIS
                            $journal_item_account = $data['modal_akun_qris'];
                            $params = array(
                                'trans_type' => 2,
                                'trans_contact_id' => $contact_id,
                                'trans_branch_id' => $session_branch_id,                                
                                // 'trans_card_number' => $data['card_number'], 
                                // 'trans_card_bank_name' => $data['card_bank'],
                                // 'trans_card_account_name' => $data['card_name'],
                                // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                                // 'trans_card_type' => $data['card_type'],
                                // 'trans_digital_provider' => $data['digital_type'],
                                'trans_digital_provider' => 'QRIS',                            
                                'trans_number' => $generate_nomor,
                                'trans_date' => date('YmdHis'),
                                // 'trans_note' => $data['note'],
                                'trans_user_id' => $session['user_data']['user_id'],
                                'trans_date_created' => date('YmdHis'),
                                'trans_date_updated' => date('YmdHis'),
                                'trans_flag' => 1,
                                'trans_total_dpp' => floatVal($total_before),
                                // 'trans_down_payment' => '0.00',
                                // 'trans_discount' => $data['discount'],
                                // 'trans_return' => '0.00',                            
                                'trans_total' => floatVal($total_after),
                                'trans_total_paid' => floatVal($total_after),
                                'trans_change' => floatVal($total_change),
                                'trans_received' => floatVal($total_received),                          
                                // 'trans_fee' => $trans_fee,
                                'trans_paid' => 1,
                                'trans_paid_type' => $type,                         
                                'trans_session' => $generate_session                  
                            );
                        }else if($type==8){ //8=Deposit
                            $where = array(
                                'account_map_branch_id' => $session_branch_id,
                                'account_map_for_transaction' => 2,
                                'account_map_type' => 3
                            );
                            $get_account_map = $this->Account_map_model->get_account_map_custom($where);
                            $journal_item_account = $get_account_map['account_map_account_id'];
                            if(!empty($journal_item_account)){
                                $params = array(
                                    'trans_type' => 2,
                                    'trans_contact_id' => $contact_id,
                                    'trans_branch_id' => $session_branch_id,                                
                                    // 'trans_bank_account_name' => $data['transfer_nama_pengirim'],
                                    // 'trans_bank_ref' => $data['transfer_nomor_ref_transfer'],
                                    // 'trans_card_number' => $data['card_number'], 
                                    // 'trans_card_bank_name' => $data['card_bank'],
                                    // 'trans_card_account_name' => $data['card_name'],
                                    // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                                    // 'trans_card_type' => $data['card_type'],
                                    // 'trans_digital_provider' => $data['digital_type'],
                                    'trans_number' => $generate_nomor,
                                    'trans_date' => date('YmdHis'),
                                    // 'trans_note' => $data['note'],
                                    'trans_user_id' => $session['user_data']['user_id'],
                                    'trans_date_created' => date('YmdHis'),
                                    'trans_date_updated' => date('YmdHis'),
                                    'trans_flag' => 1,
                                    'trans_total_dpp' => floatVal($total_before),
                                    // 'trans_total_ppn' => '0.00',
                                    // 'trans_down_payment' => '0.00',
                                    // 'trans_discount' => $data['discount'],
                                    // 'trans_return' => '0.00',                                                          
                                    'trans_total' => floatVal($total_after),
                                    'trans_total_paid' => floatVal($total_after),
                                    'trans_change' => floatVal($total_change),
                                    'trans_received' => floatVal($total_received),
                                    'trans_paid' => 1,
                                    'trans_paid_type' => $type,                               
                                    'trans_session' => $generate_session
                                );
                            }else{
                                $message = 'Pemetaan Akun Penjualan:Pendapatan Di Muka belum di konfigurasi';
                                $next = false;
                            }
                        }else{
                            $return->message = 'Harap pilih jenis pembayaran';
                            $next = false;
                        }
                        // var_dump($params);die;
                        //Voucher Detected
                        if(intval($voucher_id) > 0){
                            $params['trans_voucher_id'] = intval($voucher_id);
                            $params['trans_voucher'] = $total_before - $total_after;
                        }

                        //Params Complete Lanjut
                        if($next){
                            $set_data = $this->Transaksi_model->add_transaksi($params);
                            if($set_data){
                                
                                $trans_id = $set_data;
                                $get_trans = $this->Transaksi_model->get_transaksi($set_data);

                                //Update Flag order = 1 to trans_id
                                $list = $data['order_list_id'];
                                $list_explode = explode(', ', $list);
                                foreach($list_explode as $k => $v):
                                    
                                    $order_id = $v;

                                    // Update Order 
                                    $params = array(
                                        'order_flag' => 1,
                                        'order_trans_id' => $trans_id
                                    );
                                    $this->Order_model->update_order($order_id,$params);

                                    $get_order_list = $this->Order_model->get_order_nojoin(array('order_id'=>$order_id));

                                    //Found Room / Table ? Update Reference
                                    if((!empty($get_order_list['order_ref_id'])) and (intval($get_order_list['order_ref_id']) > 0)){
                                        $where_ref = array(
                                            'ref_id' => $get_order_list['order_ref_id'] 
                                        );
                                        $params_ref = array(
                                            'ref_use_type' => 0,
                                            'ref_date_updated' => date("YmdHis")
                                        );
                                        $this->Referensi_model->update_referensi_custom($where_ref,$params_ref);
                                    }

                                    //Update Employee (Waitress / Terapis)\
                                    /*
                                    if((!empty($get_order_list['order_sales_id'])) and (intval($get_order_list['order_sales_id']) > 0)){
                                        $where_con = array(
                                            'contact_id' => $get_order_list['order_sales_id'] 
                                        );
                                        $params_con = array(
                                            'contact_use_type' => 0
                                        );
                                        $this->Kontak_model->update_kontak_custom($where_con,$params_con);
                                    }   
                                    */
                                    
                                    /* Set To Journal
                                    if($config_post_to_journal==true){
                                        if($identity==1 or $identity==2){
                                            $operator = $this->journal_for_order("create",$order_id);
                                        } $return->journal_for_order = 1;
                                    }
                                    */

                                    //Get Order Item by Id
                                    $params_order_item = array(
                                        'order_item_order_id' => $order_id
                                    );
                                    $get_order_item = $this->Order_model->get_all_order_items($params_order_item,null,null,null,null,null);
                                    foreach($get_order_item as $r):

                                        // //Found Room / Table ? Update Reference
                                        // if(!empty($r['order_item_ref_id'])){
                                        //     $where_ref = array(
                                        //         'ref_id' => $r['order_item_ref_id'] 
                                        //     );
                                        //     $params_ref = array(
                                        //         'ref_use_type' => 0,
                                        //         'ref_date_updated' => date("YmdHis")
                                        //     );
                                        //     $this->Referensi_model->update_referensi_custom($where_ref,$params_ref);
                                        // }

                                        // //Update Employee (Waitress / Terapis)
                                        // if(!empty($data['order_sales_id'])){
                                        //     $where_con = array(
                                        //         'contact_id' => $data['order_sales_id'] 
                                        //     );
                                        //     $params_con = array(
                                        //         'contact_use_type' => 1
                                        //     );
                                        //     $this->Kontak_model->update_kontak_custom($where_con,$params_con);
                                        // }                                            
                                        
                                        /*  
                                            // Found Product Recipe
                                            $product_id = $r['order_item_product_id'];
                                            $params_recipe = array(
                                                'recipe_product_id' => $product_id
                                            );
                                            $check_has_recipe = $this->Product_recipe_model->get_all_recipe_count($params_recipe);
                                            if($check_has_recipe > 0){ //Found Recipe On Product
                                                $get_recipe = $this->Product_recipe_model->get_all_recipe($params_recipe,null,null,null,null,null);
                                                foreach($get_recipe as $g){

                                                    $recipe_product_id = $g['recipe_product_id_child'];
                                                    $recipe_unit = $g['recipe_unit'];
                                                    $recipe_qty = $g['recipe_qty'];

                                                    $set_recipe_qty = $recipe_qty * $r['order_item_qty'];
                                                    
                                                    $params_add_recipe_to_order = array(
                                                        'order_item_recipe_order_item_id' => $r['order_item_id'],
                                                        'order_item_product_id' => $recipe_product_id,
                                                        'order_item_date' => date("YmdHis"),
                                                        'order_item_unit' => $recipe_unit,
                                                        'order_item_qty' => $set_recipe_qty,
                                                        'order_item_type' => '6', //Opname
                                                        'order_item_date_created' => date("YmdHis"),
                                                        'order_item_date_updated' => date("YmdHis"),
                                                        'order_item_user_id' => $session_user_id,
                                                        'order_item_branch_id' => $session_branch_id,
                                                        'order_item_flag' => 0
                                                    );
                                                    $this->Order_model->add_order_item($params_add_recipe_to_order);                
                                                }
                                            }
                                        */

                                        //Cloning orders_items to trans_items
                                        $params_trans_item = array(
                                            'trans_item_branch_id' => $r['order_item_branch_id'],
                                            'trans_item_type' => 2,
                                            'trans_item_trans_id' => $r['order_trans_id'],
                                            'trans_item_order_id' => $r['order_item_order_id'],
                                            'trans_item_order_item_id' => $r['order_item_id'],
                                            'trans_item_product_id' => $r['order_item_product_id'],
                                            'trans_item_product_type' => $r['product_type'],
                                            'trans_item_date' => $get_trans['trans_date'],
                                            'trans_item_unit' => $r['order_item_unit'],
                                            'trans_item_date_created' => date("YmdHis"),
                                            'trans_item_user_id' => $r['order_item_user_id'],
                                            'trans_item_flag' => $r['order_item_flag'],
                                            'trans_item_position' => 2,                                                
                                            'trans_item_out_qty' => $r['order_item_qty'],
                                            'trans_item_out_price' => $r['order_item_price'],
                                            'trans_item_sell_price' => $r['order_item_price'],
                                            // 'trans_item_total' => $r['order_item_price']*$r['order_item_qty'],
                                            'trans_item_total' => $r['order_item_total'],
                                            'trans_item_sell_total' => $r['order_item_total'],
                                            'trans_item_discount' => $r['order_item_discount'],
                                            'trans_item_location_id' => $r['order_item_location_id']
                                        );
                                        $this->Transaksi_model->add_transaksi_item($params_trans_item);
                                        $set_trans_branch_id = $r['order_item_branch_id'];

                                    endforeach; //End Foreach 2       
                                endforeach; //End Foreach 1  

                                // Update Branch to Trans
                                /*
                                    $params_branch = array(
                                        'trans_branch_id' => $set_trans_branch_id
                                    );
                                    $opr = $this->Transaksi_model->update_transaksi($get_trans['trans_id'],$params_branch);
                                */

                                // Membuat Jurnal Penjualan dan Pelunasan
                                $opr = true;
                                if($opr){
                                    // Trans Dipastikan tersimpan
                                    if($get_trans['trans_id'] > 0){
                                        $params = array(
                                            'journal_type' => 2,
                                            'journal_contact_id' => $get_trans['trans_contact_id'],
                                            'journal_number' => $journal_number,
                                            'journal_date' => $journal_date,
                                            'journal_note' => !empty($journal_note) ? $journal_note : null,
                                            'journal_account_id' => !empty($journal_item_account) ? $journal_item_account : null,
                                            'journal_total' => floatVal($total_after),
                                            'journal_paid_type' => $journal_paid_type,
                                            'journal_date_created' => date("YmdHis"),
                                            'journal_date_updated' => date("YmdHis"),
                                            'journal_user_id' => !empty($session_user_id) ? $session_user_id : null,
                                            'journal_branch_id' => $get_trans['trans_branch_id'],                    
                                            'journal_flag' => 1
                                        );
                                        $set_data=$this->Journal_model->add_journal($params);
                                        if($set_data){
                                            $journal_id = $set_data;

                                            if($type==4){ //Bayar Gratis
                                                $total_after = $total_before;
                                            }
                                            
                                            // Kas / Transfer / EDC / QRIS (DEBIT)
                                            $params_items_debit = array(
                                                'journal_item_journal_id' => $journal_id,
                                                'journal_item_branch_id' => $get_trans['trans_branch_id'],  
                                                // 'journal_item_trans_id' => $i['trans_id'],
                                                'journal_item_trans_id' => $trans_id,
                                                'journal_item_account_id' => $journal_item_account,
                                                'journal_item_type' => 11,   
                                                'journal_item_date' => $journal_date,
                                                'journal_item_debit' => floatVal($total_after),
                                                'journal_item_credit' => '0.00',
                                                'journal_item_date_created' => date("YmdHis"),
                                                'journal_item_date_updated' => date("YmdHis"),
                                                'journal_item_user_id' => $session_user_id,
                                                'journal_item_flag' => 1,
                                                'journal_item_position' => 1,
                                                'journal_item_ref' => $journal_item_ref
                                            );
                                            $this->Journal_model->add_journal_item($params_items_debit);
                                        
                                            //Detect have Discount
                                            if(floatval($get_trans['trans_discount']) > 0){
                                                $account_discount = $this->get_account_map_for_transaction($session_branch_id,2,5); //Diskon Penjualan
                                                // Discount Debit
                                                $params_items_discount = array(
                                                    'journal_item_journal_id' => $journal_id,
                                                    'journal_item_branch_id' => $get_trans['trans_branch_id'],  
                                                    // 'journal_item_trans_id' => $i['trans_id'],
                                                    'journal_item_trans_id' => $trans_id,
                                                    'journal_item_account_id' => $account_discount['account_id'],
                                                    'journal_item_type' => 11,   
                                                    'journal_item_date' => $journal_date,
                                                    'journal_item_debit' => floatVal($get_trans['trans_discount']),
                                                    'journal_item_credit' => '0.00',
                                                    'journal_item_date_created' => date("YmdHis"),
                                                    'journal_item_date_updated' => date("YmdHis"),
                                                    'journal_item_user_id' => $session_user_id,
                                                    'journal_item_flag' => 1,
                                                    'journal_item_position' => 1
                                                );
                                                $this->Journal_model->add_journal_item($params_items_debit);                                                        
                                            }

                                            //Detect have Voucher
                                            if(floatval($get_trans['trans_voucher']) > 0){
                                                $account_voucher = $this->get_account_map_for_transaction($session_branch_id,2,9); //Voucher Penjualan
                                                // Discount Debit
                                                $params_items_voucher = array(
                                                    'journal_item_journal_id' => $journal_id,
                                                    'journal_item_branch_id' => $get_trans['trans_branch_id'],  
                                                    // 'journal_item_trans_id' => $i['trans_id'],
                                                    'journal_item_trans_id' => $trans_id,
                                                    'journal_item_account_id' => $account_voucher['account_id'],
                                                    'journal_item_type' => 11,   
                                                    'journal_item_date' => $journal_date,
                                                    'journal_item_debit' => floatVal($get_trans['trans_voucher']),
                                                    'journal_item_credit' => '0.00',
                                                    'journal_item_date_created' => date("YmdHis"),
                                                    'journal_item_date_updated' => date("YmdHis"),
                                                    'journal_item_user_id' => $session_user_id,
                                                    'journal_item_flag' => 1,
                                                    'journal_item_position' => 1
                                                );
                                                $this->Journal_model->add_journal_item($params_items_voucher);                                                        
                                            }

                                            // Penjualan / Pendapatan (Produk, Jasa) (CREDIT)
                                            $params_items = array(
                                                'trans_item_trans_id' => $trans_id
                                            );
                                            $get_trans_items = $this->Transaksi_model->get_all_transaksi_items($params_items,null,null,null,null,null);
                                            foreach($get_trans_items as $v){
                                                $params_items_credit = array(
                                                    'journal_item_journal_id' => $journal_id,
                                                    'journal_item_branch_id' => $get_trans['trans_branch_id'],  
                                                    'journal_item_trans_id' => $trans_id,
                                                    'journal_item_account_id' => $v['product_sell_account_id'],
                                                    'journal_item_type' => 11,   
                                                    'journal_item_date' => $journal_date,
                                                    'journal_item_debit' => '0.00',
                                                    'journal_item_credit' => $v['trans_item_sell_total'],
                                                    'journal_item_date_created' => date("YmdHis"),
                                                    'journal_item_date_updated' => date("YmdHis"),
                                                    'journal_item_user_id' => $session_user_id,
                                                    'journal_item_flag' => 1,
                                                    'journal_item_position' => 2
                                                );
                                                $this->Journal_model->add_journal_item($params_items_credit);
                                            }
                                            // Update Trans for info
                                            /*
                                                $params_update = array(
                                                    'trans_received' => $trans_total_paid,
                                                    'trans_change' => $trans_total_change,
                                                    'trans_paid_type' => $trans_paid_type,
                                                    // 'trans_bank_name' => $trans, //Transfer
                                                    // 'trans_bank_number' => $trans, //Transfer
                                                    'trans_bank_account_name' => $trans_bank_account_name,
                                                    'trans_bank_ref' => $trans_bank_ref,
                                                    'trans_card_number' => $trans_card_number,
                                                    'trans_card_bank_name' => $trans_card_bank_name,
                                                    'trans_card_bank_number' => $trans_card_number,
                                                    'trans_card_account_name' => $trans_card_account_name,
                                                    'trans_card_expired' => $trans_card_expired,
                                                    'trans_card_type' => $trans_card_type,
                                                );
                                                $this->Transaksi_model->update_transaksi($trans_id,$params_update);             
                                            */
                                        }
                                    }

                                    /* Start Activity
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
                                        End Activity 
                                    */            
                                    $return->status=1;
                                    $return->message='Pembayaran '.$type_name.' berhasil untuk '.$generate_nomor;
                                    $return->result= array(
                                        'trans_id' => $trans_id,
                                        'trans_number' => $generate_nomor,
                                        'trans_date' => date("d-M-Y, H:i", strtotime($get_trans['trans_date'])),
                                        'trans_session' => $get_trans['trans_session'],
                                        'trans_total' => floatVal($total_after),
                                        'trans_total_received' => floatVal($total_received),
                                        'trans_total_change' => floatVal($total_change),
                                        'trans_paid_type_name' => $type_name,
                                        'contact_id' => $set_contact_id,
                                        'contact_name' => $set_contact_name,
                                        'contact_phone' => $set_contact_phone
                                    ); 
                                }
                            }               
                        }else{
                            $return->message = 'Terjadi kesalahan, '.$message;
                        }
                    }else{
                        // $return->message ='Terjadi kesalahan';
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
        foreach (explode("\n",wordwrap($kolom1,$len=29)) as $line)
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
    function prints($id){ // not used
        $session = $this->session->userdata();   
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

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
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];            
        }
        else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        //Content
        $params = array(
            'order_item_order_id' => $id
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'order_item_date_created';
        $dir        = 'ASC';
        $data['content'] = $this->Order_model->get_all_order_items($params,$search,$limit,$start,$order,$dir);

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'content' => $data['content'],
            'footer' => ''
        );

        //Aktivitas
        $params = array(
            'activity_user_id' => $session_user_id,
            'activity_branch_id' => $session_branch_id,
            'activity_action' => 6,
            'activity_table' => 'orders',
            'activity_table_id' => $id,
            'activity_text_1' => ucwords(strtolower($data['header']['type_name'])),
            'activity_text_2' => ucwords(strtolower($data['header']['order_number'])),
            'activity_text_3' => ucwords(strtolower($data['header']['contact_name'])),
            'activity_date_created' => date('YmdHis'),
            'activity_flag' => 0
        );
        $this->save_activity($params);

        //Set Layout From Order Type
        if($data['header']['order_type']==1){
            $data['title'] = 'Purchase Order';
            $this->load->view($this->print_directory.'purchase_order',$data);
        }   
        else if($data['header']['order_type']==2){
            $data['title'] = 'Sales Order';
            $this->load->view($this->print_directory.'sales_order',$data);
        }    
        else if($data['header']['order_type']==3){
            $data['title'] = 'Penawaran Pembelian';
            $this->load->view($this->print_directory.'purchase_quotation',$data);
        }
        else if($data['header']['order_type']==4){
            $data['title'] = 'Penawaran Penjualan';
            $this->load->view($this->print_directory.'sales_quotation',$data);
        }                           
        else if($data['header']['order_type']==5){
            $data['title'] = 'Checkup Medicine';
            $this->load->view($this->print_directory.'checkup_medicine',$data);
        }         
        else if($data['header']['order_type']==6){
            $data['title'] = 'Checkup Laboratory';
            $this->load->view($this->print_directory.'checkup_laboratory',$data);
        } 
        else if($data['header']['order_type']==7){
            $data['title'] = 'Prepare';
            $this->load->view($this->print_directory.'sales_prepare',$data);
        }                                           
        else{
            // $this->load->view('prints/sales_order',$data);
        }                
    } 
    function prints_orders($id){ // ID = ORDER ID Print Thermal 58mm Done 
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

        $get_order  = $this->Order_model->get_order($id);
        $get_branch = $this->Branch_model->get_branch($get_order['order_branch_id']);
        $order_data = array();
        if(count($get_order)>0){
            $get_order_item = $this->Order_model->get_all_order_items(array('order_item_order_id'=> $get_order['order_id']),$search = null,$limit = null,$start = null,$order = null,$dir = null);
            $order_data[] = array(
                'order_id' => $get_order['order_id'],
                'order_number' => $get_order['order_number'],
                'order_date' => $get_order['order_date'],
                'order_total' => $get_order['order_total'],
                'order_total_down_payment' => $get_order['order_with_dp'],
                'order_total_grand' => $get_order['order_total']-$get_order['order_with_dp'],                            
                'ref_id' => $get_order['ref_id'],
                'ref_name' => $get_order['ref_name'],
                'contact_name' => $get_order['contact_name'],
                'employee_name' => $get_order['employee_name'],
                'user_name' => $get_order['user_fullname'],   
                'order_contact_name' => $get_order['order_contact_name'],
                'order_contact_phone' => $get_order['order_contact_phone'],                                         
                'order_items' => $get_order_item
            );
        }

        //Process if Data Found
        if($get_order){

            //Header
            $text .= $this->set_wrap_1($get_branch['branch_name']);
            // $text .= $this->set_wrap_1($get_branch['branch_address']);
            // $text .= $this->set_wrap_1($get_branch['branch_phone_1']); 
            // $text .= $this->set_wrap_1(date("d/m/Y - H:i:s", strtotime($get_order['order_date'])));    
            // $text .= $this->set_wrap_2('Cashier',$get_trans['contact_name']);

            // $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);

            $text .= $this->set_wrap_0($get_order['order_number'],' ','BOTH');
            $text .= $this->set_wrap_0(date("d/m/Y - H:i:s", strtotime($get_order['order_date']))," ","BOTH");  
            if($get_order['order_flag'] == 4){
                $text .= $this->set_wrap_0('BATAL',' ','BOTH'); 
            }          
            $text .= $this->set_line('-',$word_wrap_width);

            // var_dump($order_data);die;
            //Content Order Items
            foreach($order_data as $i => $v):
                //$text .= $v['order_number']."\n";
                $text .= $v['ref_name']."\n";    
                $text .= $v['contact_name']."\n";
                $text .= !empty($v['order_contact_name']) ? $v['order_contact_name']."\n" : '';
                $text .= $v['employee_name']."\n";
                $text .= "\n";
                foreach($v['order_items'] as $i):
                    $text .= $this->set_wrap_0($i['product_name'],' ','RIGHT');
                    $text .= $this->set_wrap_2(' '.number_format($i['order_item_price'],0,'',',') . ' x '. number_format($i['order_item_qty'],0,'',','), number_format($i['order_item_total'],0,'',','));
                    if(!empty($i['order_item_note'])){
                        $text .= $this->set_wrap_0($i['order_item_note'],' ','RIGHT')."\n";
                    }
                endforeach;            
                $text .= "\n"; 
            endforeach;

            // $text .= "\n";
            // $text .= $this->set_line('-',$word_wrap_width);
            // $text .= $this->set_wrap_3('SubTotal',':',number_format($get_trans['trans_total'],0,'',','));
            // $text .= $this->set_wrap_3('Dibayar',':',number_format($get_trans['trans_received'],0,'',','));
            // $text .= $this->set_wrap_3('Kembali',':',number_format($get_trans['trans_change'],0,'',','));     
            // $text .= $this->set_wrap_3('Pembayaran',':',$paid_type_name);       
            
            //Footer
            // $text .= "\n";
            $text .= $this->set_wrap_0("Print On","-","BOTH");
            $text .= $this->set_wrap_0(date("d/m/Y - H:i"),"-","BOTH");            
            // $text .= $this->set_wrap_1("Barang yang sudah di beli tidak dapat ditukar kembali");        

            //Save to Print Spoiler
            $params = array(
                'spoiler_content' => $text, 'spoiler_source_table' => 'trans',
                'spoiler_source_id' => $id, 'spoiler_flag' => 0, 'spoiler_date' => date('YmdHis')
            );
            $this->Print_spoiler_model->add_print_spoiler($params);
        }else{
            $text = "Order tidak ditemukan\n";
        }

        //Open / Write to print.txt
        $file = fopen("print_order_".$get_branch['branch_id'].".txt", "w") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);

        //Write and Save
        fwrite($file,$text);
        // fclose($file);

        if(fclose($file)){
            echo json_encode(array('status'=>1,'print_url'=>base_url('print_order_'.$get_branch['branch_id'].'.txt'),'print_to'=>$this->print_to));
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
    function prints_transaction($id){ // ID = TRANS ID Print Thermal 58mm Done 
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

        $get_trans  = $this->Transaksi_model->get_transaksi($id);
        $get_branch = $this->Branch_model->get_branch($get_trans['trans_branch_id']);
        $get_trans_items  = $this->Transaksi_model->get_transaksi_item_custom(array('trans_item_trans_id'=>$get_trans['trans_id']));

        $get_order = $this->Order_model->get_all_orders(array('order_trans_id'=> $id),$search = null,$limit = null,$start = null,$order = null,$dir = null);
        $order_data = array();
        if(count($get_order)>0){
            foreach($get_order as $h){
                $get_order_item = $this->Order_model->get_all_order_items(array('order_item_order_id'=> $h['order_id']),$search = null,$limit = null,$start = null,$order = null,$dir = null);
                $order_data[] = array(
                    'order_id' => $h['order_id'],
                    'order_number' => $h['order_number'],
                    'order_date' => $h['order_date'],
                    'order_total' => $h['order_total'],
                    'order_total_down_payment' => $h['order_with_dp'],
                    'order_total_grand' => $h['order_total']-$h['order_with_dp'],                            
                    'ref_id' => $h['ref_id'],
                    'ref_name' => $h['ref_name'],
                    'contact_name' => $h['contact_name'],
                    'employee_name' => $h['employee_name'],                    
                    'user_name' => $h['user_fullname'],           
                    'order_contact_name' => $h['order_contact_name'],
                    'order_contact_phone' => $h['order_contact_phone'],                                         
                    'order_items' => $get_order_item
                );
            }
        }

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
            $text .= $this->set_wrap_1($get_branch['branch_address']);
            $text .= $this->set_wrap_1($get_branch['branch_phone_1']);                
            $text .= $this->set_wrap_1($get_trans['trans_number']);
            $text .= $this->set_wrap_1(date("d/m/Y - H:i:s", strtotime($get_trans['trans_date'])));    
            // $text .= $this->set_wrap_2('Cashier',$get_trans['contact_name']);

            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);

            $text .= $this->set_wrap_2("Item", "Total");
            $text .= $this->set_line('-',$word_wrap_width);

            
            //Content Order Items
            $num = 0;
            foreach($order_data as $i => $v):
                //$text .= $v['order_number']."\n";
                $text .= $this->set_wrap_0($v['order_number'],'-','BOTH');
                $text .= $v['ref_name']."\n";
                $text .= $v['order_date']."\n";  
                $text .= $v['employee_name']."\n";
                $text .= $v['user_name']."\n";         
                $text .= "\n";                      
                foreach($v['order_items'] as $i):
                    $text .= $this->set_wrap_0($i['product_name'],' ','RIGHT');
                    $text .= $this->set_wrap_2(' '.number_format($i['order_item_price'],0,'',',') . ' x '. number_format($i['order_item_qty'],0,'',','), number_format($i['order_item_total'],0,'',','));
                    if($i['order_item_discount'] > 0){
                        $text.= $this->set_wrap_2(' Dis. '.number_format($i['order_item_discount']),' ');
                    }
                    $num++;
                endforeach;            
                $text .= "\n"; 
            endforeach;

            //Content Trans Items / Hanya tampil jika order_item kosong, maka ambil dari trans_items
            if($num < 1){
                foreach($get_trans_items as $v):
                    $text .= $v['product_name']."\n";
                    $text .= $this->set_wrap_2(' '.number_format($v['trans_item_out_qty'],0,'',',') . ' x '. number_format($v['trans_item_sell_price'],0,'',','), number_format($v['trans_item_sell_total'],0,'',','));            
                endforeach;  
            }     

            // $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);
            $text .= $this->set_wrap_3('Subtotal',':',number_format($get_trans['trans_total_dpp'],0,'',','));
            if(!empty($get_trans['trans_voucher']) && $get_trans['trans_voucher'] > 0){
                $text .= $this->set_wrap_3('Voucher',':','-'.number_format($get_trans['trans_voucher'],0,'',','));    
            }
            if(!empty($get_trans['trans_discount']) && $get_trans['trans_discount'] > 0){
                $text .= $this->set_wrap_3('Diskon',':','-'.number_format($get_trans['trans_discount'],0,'',','));    
            }            
            if((!empty($get_trans['trans_voucher'])) or (!empty($get_trans['trans_discount']))){
                $text .= $this->set_wrap_3('Grand Total',':',number_format($get_trans['trans_total'],0,'',','));    
            }            
            $text .= $this->set_wrap_3('Dibayar',':',number_format($get_trans['trans_received'],0,'',','));
            if(!empty($get_trans['trans_change']) && $get_trans['trans_change'] > 0){
                $text .= $this->set_wrap_3('Kembali',':',number_format($get_trans['trans_change'],0,'',','));
            }     
            $text .= $this->set_wrap_3('Pembayaran',':',$paid_type_name);

            //Footer
            $text .= "\n";
            $text .= $this->set_wrap_1("Terima kasih atas kunjungannya".$num);
            $text .= $this->set_wrap_1("Barang yang sudah di beli tidak dapat ditukar kembali");        

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
        $file = fopen("print_transaction_".$get_branch['branch_id'].".txt", "w") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);

        $text .= chr(27).chr(10);

        //Write and Save
        fwrite($file,$text);
        // fclose($file);

        if(fclose($file)){
            echo json_encode(array('status'=>1,'print_url'=>base_url('print_transaction_'.$get_branch['branch_id'].'.txt'),'print_to'=>$this->print_to));
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
    function prints_order($id){ // NOT USED ID = ORDER ID Print Thermal 58mm Done 
        // $tmpdir         = sys_get_temp_dir();
        // $file           = tempnam($tmpdir, 'ctk');
        // $handle         = fopen($file, 'w');
        // $condensed      = Chr(27) . Chr(33) . Chr(4);
        // $bold1          = Chr(27) . Chr(69);
        // $bold0          = Chr(27) . Chr(70);
        // $initialized    = chr(27).chr(64);
        // $condensed1     = chr(15);
        // $condensed0     = chr(18);


        //Open / Write to print.txt
        $file = fopen("print_order_".$get_branch['branch_id'].".txt", "wb+") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);

        // $text .= $justify."\n";
        // $text .= "Toko Logam Mulia\n";
        // $text .= "Puri Anjasmoro No 54\n";
        $text = chr(0x1B).chr(0x21).chr(0x30)."\n";
        $text = chr(0x1B).chr(0x50)."\n";        
        $text .= chr(0x1B).chr(0x2D).chr(0x31)."\n";
        $text .= 'Undeerline'."\n";
        // $text .= chr(27).' '.chr(52).' '.chr(48)."\n";        
        // $text .= 'Hai'."\n";

        //Write and Save
        fwrite($file,$text);
        // fclose($file);

        // if(fclose($file)){
        //     echo json_encode(array('status'=>1,'print_url'=>base_url('print_order.txt')));
        // }else{
        //     echo json_encode(array('status'=>0,'message'=>'Print raw error'));
        // }

        if(fclose($file)){
            echo json_encode(array('status'=>1,'print_url'=>base_url('print_order_'.$get_branch['branch_id'].'.txt'),'print_to'=>$this->print_to));
        }else{
            echo json_encode(array('status'=>0,'message'=>'Print raw error','print_to'=>$this->print_to));
        }        
    }
} //3880
?>