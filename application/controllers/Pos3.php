<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos3 extends MY_Controller{

    var $folder_upload = 'upload/trans/';
    var $image_width   = 250;
    var $image_height  = 250;

    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));              
        }        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Order_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Print_spoiler_model');        
        $this->load->model('Kategori_model'); 
        $this->load->model('Branch_model');  
        $this->load->model('Produk_model');                  
        $this->load->model('Journal_model');        
        $this->load->model('Tax_model');
        $this->load->model('User_model');  
        $this->load->model('Kontak_model');   
        $this->load->model('Type_model');
        $this->load->model('Account_map_model'); 
        $this->load->model('Referensi_model');         

        $this->print_to         = 0; //0 = Local, 1 = Bluetooth
        $this->whatsapp_config  = 1;          

        $this->contact_1_alias  = 'Customer';
        $this->contact_2_alias  = 'Sales By';
        $this->ref_alias        = 'Ruangan';         
        
        $this->order_alias      = 'Order';
        $this->trans_alias      = 'Trans';
        
        $this->payment_alias    = 'Checkout';  
        $this->dp_alias         = 'Down Payment'; 
        $this->product_alias    = 'Jasa';     
        
        $this->form_title       = 'POS 3';
        $this->form_type        = 222;    

        $this->file_view        = 'layouts/admin/menu/sales/pos/pos_v3';
        $this->file_js          = 'layouts/admin/menu/sales/pos/pos_v3_js';
    }
    function index(){
        $data['session'] = $this->session->userdata();  
        $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
        $session_branch_id = !empty($data['session']['user_data']['branch']['id']) ? $data['session']['user_data']['branch']['id'] : null;
        $data['theme']      = $this->User_model->get_user($data['session']['user_data']['user_id']); 

        if ($this->input->post()) {    
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;            

            $post       = $this->input->post();
            $post_data  = $this->input->post('data');
            $get        = $this->input->get();
            $action     = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            $identity   = $this->form_type;
            $datas      = json_decode($post_data, TRUE);

            switch($action){
                case "load": //Works
                    $columns = array(
                        '0' => 'trans_date',
                        '1' => 'trans_number',
                        '2' => 'contact_name',
                        '3' => 'trans_total',
                        '4' => 'trans_sales_name',
                        '5' => 'trans_contact_name'
                    );                
                    $limit      = $this->input->post('length');
                    $start      = $this->input->post('start');
                    $order      = $columns[$this->input->post('order')[0]['column']];
                    $dir        = $this->input->post('order')[0]['dir'];
                    $search     = [];
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
                    //Datepicker
                    // $date_start     = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    // $date_end       = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));

                    //Moment Date
                    $date_start     = date('Y-m-d H:i:s', strtotime($this->input->post('date_start')));
                    $date_end       = date('Y-m-d H:i:s', strtotime($this->input->post('date_end')));

                    // $location_from  = !empty($this->input->post('location_from')) ? $this->input->post('location_from') : 0;
                    // $location_to    = !empty($this->input->post('location_to')) ? $this->input->post('location_to') : 0;
                    $contact        = !empty($this->input->post('filter_contact')) ? $this->input->post('filter_contact') : 0;
                    $type_paid      = !empty($this->input->post('filter_type_paid')) ? $this->input->post('filter_type_paid') : 0;

                    $params_datatable = array(
                        'trans.trans_date >' => $date_start,
                        'trans.trans_date <' => $date_end,
                        'trans.trans_type' => intval($identity),
                        'trans.trans_flag <' => 4,
                        'trans.trans_branch_id' => intval($session_branch_id)
                    );
                    if($contact > 0){
                        $params_datatable = array(
                            'trans.trans_date >' => $date_start,
                            'trans.trans_date <' => $date_end,
                            'trans.trans_type' => intval($identity),
                            'trans.trans_flag <' => 4,
                            'trans.trans_branch_id' => intval($session_branch_id),
                            'trans.trans_contact_id' => intval($contact)
                        );
                    }

                    // if(intval($location_from) > 0){
                    //     $params_datatable['trans.trans_location_id'] = $location_from;
                    // }

                    // if(intval($location_to) > 0){
                    //     $params_datatable['trans.trans_location_to_id'] = $location_to;
                    // }
                    
                    if(intval($type_paid) > 0){
                        $params_datatable['trans.trans_paid_type'] = intval($type_paid);
                    }
                    /*
                        Transaksi.php
                        1 Pembelian
                        2 Penjualan
                        3 Retur Beli
                        4 Retur Jual
                        8 Produksi
                        5 Transfer Stok
                        6 Stok Opname

                        Inventory.php
                        9 Pemakaian Produk
                    */
                    $datas_count = $this->Transaksi_model->get_all_transaksis_count($params_datatable,$search);
                    if($datas_count > 0){
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
                    $return->search             = $search;
                    break;
                case "load_ref": //Requires ref_type, Room, Table, Something on table 'reference'
                    $ref_type = $datas['ref_type'];
                    $search = array();
                    if(strlen($datas['search']) > 0){
                        $search['ref_name'] = $datas['search'];
                    }
                    $start = 0;
                    $limit = 100;
                    $order = 'ref_name';
                    $dir = 'ASC';

                    $params_datatable = array(
                        'references.ref_type' => intval($ref_type),                    
                        'references.ref_flag' => 1,
                        'references.ref_branch_id' => intval($session_branch_id),
                        'references.ref_parent_id > ' => 0
                    );      
                    $datas_count = $this->Referensi_model->get_all_referensis_count($params_datatable,$search);                           
                    if($datas_count > 0){
                        //Initial Group n Group Data
                        $group      = array();
                        $group_data = array();
                        $set_data   = array();
                        $get_data = $this->Referensi_model->get_all_referensis_join_ref($params_datatable, $search, $limit, $start, $order, $dir);
                        foreach($get_data as $v){     

                            $get_order = array();
                            if(intval($v['ref_use_type']) == 1){
                                $where = array(
                                    'order_flag =' => 0,
                                    'order_branch_id' => $session_branch_id,
                                    'order_ref_id' => intval($v['ref_id'])
                                );
                                $get_order = $this->Order_model->get_order_ref_custom($where);
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
                            $datas = array(
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
                                'order' => $set_data,
                                'parent_id' => $v['parent_id'],
                                'parent_name' => $v['parent_name']
                            );

                            //Grouping Data
                            $group_data[$v['ref_parent_id']][] = $datas;                                                                   
                        }


                        //Group Data to Multidimensional Array
                        foreach($group_data as $x => $x_value) {
                            $group[] = array(
                                'index'=> $x,
                                'name' => $x_value[0]['parent_name'],
                                'data' => $x_value
                            );
                        }

                        $return->status=1; $return->message='Loaded';
                        $return->result=$get_data; 
                        $return->result_group=$group;    
                        $datas_count = count($group);    
                    }else{  
                        $return->status=0; $return->message= $this->ref_alias.' belum di konfigurasi';
                        $return->result=array();    
                    }
                    $return->total_records=$datas_count;
                    $return->recordsTotal = $datas_count;
                    $return->recordsFiltered = $datas_count;
                    $return->params = $params_datatable;
                    break;
                case "load_product_tab_detail":
                    $data_search = !empty($datas['search']) ? $datas['search'] : '0';
                    $data_category_id = !empty($datas['category_id']) ? $datas['category_id'] : '0';                    
                    $search = array();
                    if(strlen($data_search) > 2){
                        $search['product_name'] = $datas['search'];
                    }
                    $start          = 0;
                    $limit          = 100;
                    $category_id    = $data_category_id;
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

                    $datas_count = $this->Produk_model->get_all_produks_count($params_datatable,$search);
                    if($datas_count > 0){
                        $datas = $this->Produk_model->get_all_produks($params_datatable, $search, $limit, $start, 'product_name', 'ASC');
                        $return->status=1; $return->message='Loaded';
                        $return->result=$datas;        
                    }else{ 
                        $return->message='Produk tidak tersedia';
                        $return->result=[];    
                    }
                    $return->total_records=$datas_count;
                    $return->recordsTotal = $datas_count;
                    $return->recordsFiltered = $datas_count;
                    $return->params = $params_datatable;                    
                    break;
                case "create": //Works
                    $this->form_validation->set_rules('trans_item_list', 'Item', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $next                   = true;
                        $trans_id               = !empty($post['trans_id']) ? $post['trans_id'] : 0;                     
                        $trans_non_contact_id   = !empty($post['trans_non_contact_id']) ? $post['trans_non_contact_id'] : 0;
                        $trans_contact_id       = !empty($post['trans_contact_id']) ? $post['trans_contact_id'] : 0;
                        $trans_contact_name     = !empty($post['trans_contact_name']) ? $post['trans_contact_name'] : '-';
                        $trans_contact_phone    = !empty($post['trans_contact_phone']) ? $post['trans_contact_phone'] : '-';
                        // $total                  = !empty($datas['payment_total']) ? floatval($datas['payment_total']) : 0;
                        // $total_received         = !empty($datas['payment_total_received']) ? floatval($datas['payment_total_received']) : 0;
                        // $total_change           = !empty($datas['payment_total_change']) ? floatval($datas['payment_total_change']) : 0;
                        $post_items             = json_decode($post['trans_item_list'],true);
                        $ref_id                 = !empty($post['ref_id']) ? $post['ref_id'] : null;
                        $sales_id               = !empty($post['sales_id']) ? $post['sales_id'] : null;                                                
                        // $payment_method         = !empty($datas['payment_method']) ? intval($datas['payment_method']) : 0;
                        
                        $document_session       = $this->random_code(20);
                        $document_number        = $this->request_number_for_transaction($this->form_type);
                        $document_date          = !empty($post['trans_date']) ? date("Y-m-d", strtotime($post['trans_date'])).' '.date("H:i:s") : date("YmdHis");   

                        //Check Operator is CREATE or UPDATE data
                        if(intval($trans_id) > 0){
                            $operator = 'update';
                        }else{
                            $operator = 'create';
                        }

                        $params = array(
                            'trans_branch_id' => $session_branch_id,
                            'trans_type' => $this->form_type,
                            'trans_number' => $document_number,
                            'trans_date' => $document_date,
                            'trans_user_id' => $session_user_id,
                            'trans_date_created' => date("YmdHis"),
                            'trans_flag' => 1,
                            // 'trans_total_dpp' => $total,
                            // 'trans_total_ppn' => 0,
                            // 'trans_discount' => 0,
                            // 'trans_voucher' => 0,
                            // 'trans_total' => $total,
                            // 'trans_total_paid' => $total,
                            // 'trans_change' => $total_change,
                            // 'trans_received' => $total_received,
                            // 'trans_paid' => 1,
                            // 'trans_paid_type' => $payment_method,
                            'trans_session' => $document_session,
                            //   'trans_ppn' => !empty($post['trans_ppn']) ? $post['trans_voucher_id'] : null,
                                'trans_sales_id' => $sales_id,
                            //   'trans_voucher_id' => null,
                                'trans_ref_id' => $ref_id,
                        );

                        //Customer or Not ?
                        if(intval($trans_contact_id) > 0){
                            $get_contact = $this->Kontak_model->get_kontak($trans_contact_id);
                            $params['trans_contact_id'] = $trans_contact_id;
                            $params['trans_contact_name'] = $this->sentencecase($get_contact['contact_name']);
                            $params['trans_contact_phone'] = $get_contact['contact_phone_1'];  
                            $set_contact_id = $get_contact['contact_id'];
                            $set_contact_name = $get_contact['contact_name'];
                            $set_contact_phone = $get_contact['contact_phone_1'];                            
                        }else{
                            $params['trans_contact_id'] = $trans_non_contact_id;                            
                            $params['trans_contact_name'] = $this->sentencecase($trans_contact_name);
                            $params['trans_contact_phone'] = $trans_contact_phone;
                            $set_contact_id = $trans_non_contact_id;
                            $set_contact_name = $trans_contact_name;
                            $set_contact_phone = $trans_contact_phone;                            
                        }

                        //Check Data Exist
                        /*
                            $params_check = array(
                                'trans_name' => $trans_name
                            );
                            $check_exists = $this->Trans_model->check_data_exist($params_check);
                        */
                        $check_exists = false;
                        if(!$check_exists){

                            if($operator == 'create'){
                                $set_data = $this->Transaksi_model->add_transaksi($params);
                            }else if($operator == 'update'){
                                $get_data = $this->Transaksi_model->get_transaksi_nojoin_custom(['trans_id' => $trans_id]);

                                $trans_date = date("Y-m-d", strtotime($get_data['trans_date']));
                                $trans_time = date("H:i:s", strtotime($get_data['trans_date']));

                                // 
                                // if($trans_date !== date("Y-m-d", strtotime($post['trans_date']))){
                                    $document_date = date("Y-m-d", strtotime($post['trans_date'])).' '.$trans_time;
                                // }else{
                                    // $document_date = 
                                // }

                                $params_update = array(
                                    'trans_sales_id' => $sales_id,
                                    'trans_ref_id' => $ref_id,
                                    'trans_contact_id' => $set_contact_id,
                                    'trans_contact_name' => $set_contact_name,
                                    'trans_contact_phone' => $set_contact_phone,                                           
                                    'trans_date' => $document_date                             
                                );                               
                                // var_dump($params_update);die; 
                                $set_delete = $this->Transaksi_model->delete_transaksi_item_custom(['trans_item_trans_id' => $trans_id]);
                                $set_data = $this->Transaksi_model->update_transaksi($trans_id,$params);

                            }

                            if($set_data){
                                
                                if($operator == 'create'){                                                                    
                                    $set_document_id = $set_data;
                                    $message = 'Menyimpan';
                                }else if($operator == 'update'){
                                    $set_document_id = $trans_id;
                                    $message = 'Memperbarui';
                                }
                                
                                //Insert trans_item
                                foreach($post_items as $v){
                                    $random_item_session     = $this->random_code(20);
                                    $params_items = array(
                                        'trans_item_branch_id' => $session_branch_id,
                                        'trans_item_type' => $this->form_type,
                                        // 'trans_item_type_name' => 'Penjualan',
                                        'trans_item_trans_id' => $set_document_id,
                                        'trans_item_product_id' => $v['product_id'],
                                        // 'trans_item_location_id' => NULL,
                                        'trans_item_product_type' => $v['product_type'],
                                        'trans_item_date' => $document_date,
                                        'trans_item_unit' => $v['product_unit'],
                                        'trans_item_out_qty' => $v['product_qty'],
                                        'trans_item_out_price' => $v['product_price'],
                                        'trans_item_sell_price' => $v['product_price'],
                                        // 'trans_item_discount' => intval($v['trans_item_discount']),
                                        // 'trans_item_ppn' => intval($v['trans_item_ppn']),
                                        // 'trans_item_ppn_value' => intval($v['trans_item_ppn_value']),
                                        'trans_item_total' => $v['product_total'],
                                        'trans_item_sell_total' => $v['product_total'],
                                        'trans_item_date_created' => date("YmdHis"),
                                        'trans_item_user_id' => $session_user_id,
                                        'trans_item_flag' => 1,
                                        // 'trans_item_ref' => NULL,
                                        'trans_item_position' => 2,
                                        'trans_item_note' => $v['product_note'],
                                        'trans_item_trans_session' => $document_session,
                                        'trans_item_session' => $random_item_session,
                                    );
                                    $this->Transaksi_model->add_transaksi_item($params_items);
                                }

                                $get_trans = $this->Transaksi_model->get_transaksi($set_document_id);
                                // $get_trans_items = $this->Transaksi_model->get_all_transaksi_items($params_items,null,null,null,null,null);
                                $return->status=1;
                                $return->message='Berhasil '.$message;
                                $return->result= array(
                                    'id' => $set_document_id,
                                    'number' => $get_trans['trans_number'],
                                    'date' => $get_trans['trans_date'],                                    
                                    'session' => $get_trans['trans_session'],
                                    'contact' => array(
                                        'id' => $set_contact_id,
                                        'name' => $set_contact_name,
                                        'phone' => $set_contact_phone
                                    )
                                );                              
                            }else{
                                $return->message='Gagal Simpan';
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;                        
                case "read": //Works
                    $this->form_validation->set_rules('trans_id', 'ID', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $trans_id   = !empty($post['trans_id']) ? $post['trans_id'] : 0;
                        if(intval(strlen($trans_id)) > 0){        
                            $datas = $this->Transaksi_model->get_transaksi($trans_id);
                            if($datas){
                                $get_trans_items = $this->Transaksi_model->get_all_transaksi_items(['trans_item_trans_id'=>$trans_id],null,null,null,'trans_item_id','asc');
                                $return->status=1;
                                $return->message='Berhasil mendapatkan data';
                                $return->result= [
                                    'trans_id' => $datas['trans_id'],
                                    'trans_session' => $datas['trans_session'],
                                    'trans_date' => $datas['trans_date'],
                                    'trans_number' => $datas['trans_number'],
                                    'ref_id' => $datas['ref_id'],
                                    'ref_name' => $datas['ref_name'],
                                    'sales_id' => $datas['sales_id'],
                                    'sales_fullname' => $datas['sales_fullname'],
                                    'contact_id' => $datas['contact_id'],
                                    'contact_name' => !empty($datas['contact_name']) ? $datas['contact_name'] : $datas['trans_contact_name'],                                                                                                            
                                    'contact_phone' => !empty($datas['contact_phone_1']) ? $datas['contact_phone_1'] : $datas['trans_contact_phone'],
                                ];
                                $return->result_item=$get_trans_items;                                
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    break;
                case "delete": //Works
                    $this->form_validation->set_rules('trans_id', 'trans_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $trans_id   = !empty($post['trans_id']) ? $post['trans_id'] : 0;
                        $trans_name = !empty($post['trans_number']) ? $post['trans_number'] : null;

                        if(strlen($trans_id) > 0){
                            $get_data=$this->Transaksi_model->get_transaksi($trans_id);
                            if($get_data){
                                $set_data=$this->Transaksi_model->delete_transaksi($trans_id);
                                $set_data=$this->Transaksi_model->delete_transaksi_item_custom(['trans_item_trans_id'=>$trans_id]);
                                // $set_data = $this->Transaksi_model->update_trans_custom(array('trans_id'=>$trans_id),array('trans_flag'=>4));                
                                /*
                                $file = FCPATH.$this->folder_upload.$get_data['trans_image'];
                                if (file_exists($file)) {
                                    unlink($file);
                                }
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$trans_name;
                            }else{
                                $return->message='Gagal menghapus '.$trans_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                default:
                    $return->message='No Action';
                    break; 
            }
            echo json_encode($return);
        }else{
            // Default First Date & End Date of Current Month
            $firstdate = new DateTime('first day of this month');
            // $data['first_date'] = $firstdate->format('d-m-Y');
            $data['first_date'] = date("d-m-Y");
            $data['end_date'] = date("d-m-Y");

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $params_product = array(
                'product_branch_id' => $session_branch_id,
                // 'product_type' => 
                'product_flag' => 1
            );            
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
            $where_non = array(
                'contact_branch_id' => $session_branch_id,
                'contact_flag' => 5,
                'contact_type' => 2
            );            
          
            $data['contact_1_alias']  = $this->contact_1_alias;
            $data['contact_2_alias']  = $this->contact_2_alias;
            $data['ref_alias']        = $this->ref_alias;         
            $data['order_alias']      = $this->order_alias;
            $data['trans_alias']      = $this->trans_alias;
            $data['payment_alias']    = $this->payment_alias;  
            $data['dp_alias']         = $this->dp_alias;
            $data['product_alias']    = $this->product_alias;            
            
            $data['product_category'] = $this->Kategori_model->get_all_categoriess($params_datatable,null,null,null,'category_name','asc');
            $data['products']         = $this->Produk_model->get_all_produks_datatable($params_product,null,6,0,'product_name','asc');
            $data['type_paid']        = $this->Type_model->get_all_type_paid($params_type_paid,null,null,null,'paid_id','asc');
            $data['account_payment']  = $this->Account_map_model->get_all_account_map($params_account_map,null,null,null,'account_map_type','asc');
            $data['non_contact']      = $this->Kontak_model->get_kontak_custom($where_non);            
            
            $data['whatsapp_config']  = $this->whatsapp_config;
            $data['title']            = $this->form_title;
            $data['identity']         = $this->form_type;

            $data['_view'] = $this->file_view;
            $this->load->view('layouts/admin/index',$data);
            $this->load->view($this->file_js,$data);                  
        }
    }
    function rawbt(){
        // $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/sales/pos/printer.php');    
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
        foreach (explode("\n",wordwrap($kolom1,$len=120)) as $line)
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
    function prints_transaction($id){ // ID = TRANS ID Print Thermal 58mm Done 
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
            $text .= $this->set_wrap_1("-- Terima Kasih --");     

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
    function report(){
        $request = $this->input->get('request');
        $format  = $this->input->get('format');        
        /* 
            trans_recap
            trans_detail
        */
        $date_start = date('Y-m-d H:i:s', strtotime($this->input->get('start_date').' 00:00:00'));
        $date_end   = date('Y-m-d H:i:s', strtotime($this->input->get('end_date').' 23:59:59'));
        $contact    = intval($this->input->get('contact'));
        $product    = intval($this->input->get('product'));
        $sales      = intval($this->input->get('sales'));
        $ref        = intval($this->input->get('ref'));                
        $type_paid  = intval($this->input->get('type_paid'));

        $order      = $this->input->get('order');
        $dir        = $this->input->get('dir');

        $session            = $this->session->userdata(); 
        $session_branch_id  = $session['user_data']['branch']['id'];
        $session_user_id    = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch($session_branch_id);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $limit  = null;
        $start  = null;
        $order  = $order;
        $dir    = $dir;
        $search = null;

        $params_datatable = array(
            'trans_branch_id' => $session_branch_id,
            'trans_type' => 2,
            'trans_date >' => $date_start,
            'trans_date <' => $date_end
        );
        if(intval($contact) > 0){
            $params_datatable['contact_id'] = intval($contact);
            $get_contact = $this->Kontak_model->get_kontak(intval($contact));
            $data['contact'] = $get_contact;
        }
        
        if(intval($type_paid) > 0){
            $params_datatable['trans_paid_type'] = intval($type_paid);
            $get_type_paid = $this->Type_model->get_type_paid(intval($type_paid));
            $data['type_paid'] = $get_type_paid;
        }
        
        $mdatas = array();
        $paid_column = array();
        $get_type_paid = $this->Type_model->get_all_type_paid(array('paid_flag' => 1),null,null,null,'paid_id','ASC');
        foreach($get_type_paid as $p){
            $paid_column[0] = '-';
            $paid_column[$p['paid_id']] = $p['paid_name'];
        }

        //Content Generate
        if(($request=='trans_recap') && ($format=='html')){
            $mdatas = array();
            $datas = $this->Transaksi_model->get_all_transaksis($params_datatable, $search, $limit, $start, $order, $dir);
            foreach($datas AS $v){ 
                $trans_id = $v['trans_id'];

                // $get_order = $this->Order_model->get_all_orders(array('order_trans_id' => $trans_id),null,null,null,'order_id','asc');
                $get_order_item = '';
                $order_total = 0;
                // echo json_encode($get_order);


                $fee = 0;
                // $get_order_item = array();
                // foreach ($get_order as $o) {
                //     $order_total = $o['order_subtotal'];
                //     $get_order_item[] = $this->Order_model->get_all_order_items(array('order_item_order_id'=>$o['order_id']),null,null,null,'order_item_id','asc');
                // }

                $mdatas[] = array(
                    'trans_id' => $v['trans_id'],
                    'trans_number' => $v['trans_number'],
                    'trans_date' => date("d-M-Y, H:i", strtotime($v['trans_date'])),
                    'trans_note' => $v['trans_note'],
                    'trans_sales_name' => $v['trans_sales_name'],
                    'date_due_over' => $v['date_due_over'],
                    'contact_name' => $v['contact_name'],
                    'contact_address' => $v['contact_address'],
                    'contact_phone' => $v['contact_phone_1'],
                    'contact_email' => $v['contact_email_1'],                
                    'ref_name' => '-',
                    'user_username' => $v['user_username'],
                        'trans_total_dpp' => $v['trans_total_dpp'],
                        'trans_total_ppn' => $v['trans_total_ppn'],
                        'trans_discount' => $v['trans_discount'],
                        'trans_voucher' => $v['trans_voucher'],
                    'trans_total' => $v['trans_total'],
                    'trans_total_paid' => $v['trans_total_paid'],
                    'trans_paid_type' => $v['trans_paid_type'],
                    'trans_paid_type_name' => !empty($v['trans_paid_type']) ? $paid_column[$v['trans_paid_type']] : $paid_column[0],
                    'trans_fee' => $fee,        
                    'order_total' => $order_total,        
                    'order_item' => $get_order_item                                
                );
            }
        }else if(($request == 'trans_detail') && ($format=='receipt')){

            $total_sell = 0;
            
            $total_start_cash = 500000;
            $total_expected = 0;
            $total_actual = 0;
            $total_different = 0;

            $total_paid_cash = 0;
            $total_paid_transfer = 0;
            $total_paid_edc = 0;
            $total_paid_qris = 0;
            $total_paid_dp = 0;
            $total_paid_voucher = 0;    
            $total_paid_free = 0;
            $total_paid = 0;            
            $text = '';
            $word_wrap_width = 29;
            $params = array(
                'trans_branch_id' => $session_branch_id,
                'trans_type' => 2,
                'trans_date >' => $date_start,
                'trans_date <' => $date_end                
            );
            $params_items = array(
                'trans_item_branch_id' => $session_branch_id,            
                'trans_item_type' => 2,
                'trans_item_date >' => $date_start,
                'trans_item_date <' => $date_end
            );
            // var_dump($params_items);die;
            $get_trans = $this->Transaksi_model->get_all_transaksi_group_by_paid_type($params,null,null,null,'trans_id','asc');
            $get_voucher = $this->Transaksi_model->get_transaksi_sum_by_column($params,'trans_voucher');                    
            $get_t_out = $this->Transaksi_model->get_transaksi_item_out_qty_total($params_items);            
        
            // $get_t_item = $this->Transaksi_model->get_all_transaksi_items($params,null,null,null,'product_name','asc');
            $get_t_item = $this->Transaksi_model->get_all_transaksi_items_report($params_items,null,null,null,'product_name','asc');            
        
            // var_dump($get_voucher);die;

            //Payment Summary
            foreach($get_trans as $v):    
                if($v['trans_paid_type'] == 1){ //Cash
                    $total_paid_cash = $total_paid_cash + $v['trans_total_paid'];
                }else if($v['trans_paid_type'] == 2){ //Transfer
                    $total_paid_transfer = $total_paid_transfer + $v['trans_total_paid'];
                }else if($v['trans_paid_type'] == 3){ //EDC
                    $total_paid_edc = $total_paid_edc + $v['trans_total_paid'];
                }else if($v['trans_paid_type'] == 4){ //Gratis
                    $total_paid_free = $total_paid_free + $v['trans_total_paid'];
                }else if($v['trans_paid_type'] == 5){ //QRIS
                    $total_paid_qris = $total_paid_qris + $v['trans_total_paid'];
                }else if($v['trans_paid_type'] == 8){ //Deposit
                    $total_paid_dp = $total_paid_dp + $v['trans_total_paid'];
                }        
            endforeach;    
            $total_paid = $total_paid_cash + $total_paid_transfer + $total_paid_qris + $total_paid_edc + $total_paid_free + $total_paid_dp;
            if($get_voucher['trans_voucher'] > 0){
                $total_paid_voucher = $get_voucher['trans_voucher'];
                $total_paid = $total_paid + $total_paid_voucher;
            }

            //Sales Summary
            foreach($get_t_item as $v):        
                $total_sell = $total_sell + $v['trans_item_sell_total'];
            endforeach;
            
            $total_expected = $total_paid;
            // $total_actual = $total_paid - $total_paid_cash;
            $total_actual = $total_start_cash + $total_paid_cash;        
            $total_different = $total_expected - $total_actual;

            //Branch
            $text .= $this->set_wrap_1($data['branch']['branch_name']);    
            $text .= $this->set_wrap_1($data['branch']['branch_address']);
            $text .= $this->set_wrap_1($data['branch']['branch_phone_1']);            
            
            //User Print
            $text .= $this->set_line('-',$word_wrap_width);
            $text .= $this->set_wrap_1('PRINT BY');       
            $text .= $this->set_line('-',$word_wrap_width);        
            $text .= $this->set_wrap_2('User',$session['user_data']['user_name']);
            $text .= $this->set_wrap_0('Start Date '.date("d-M-Y, H:i",strtotime($date_start))," ","RIGHT");
            $text .= $this->set_wrap_0('End Date   '.date("d-M-Y, H:i",strtotime($date_end))," ","RIGHT");
            $text .= $this->set_wrap_2('Sold Items',number_format($get_t_out['trans_item_out_qty']));                                  
            $text .= $this->set_line('-',$word_wrap_width);

            //Cash Management
            // $text .= $this->set_wrap_1('CASH MANAGEMENT');       
            // $text .= $this->set_line('-',$word_wrap_width); 
            // $text .= $this->set_wrap_2('Start Cash',number_format($total_start_cash));
            // $text .= $this->set_wrap_2('Cash Payment',number_format($total_paid_cash));
            // $text .= $this->set_wrap_2('Expected',number_format($total_expected));                   
            // $text .= $this->set_wrap_2('Actual',number_format($total_actual));                           
            // $text .= $this->set_wrap_2('Different',number_format($total_different));                                   
            // $text .= $this->set_line('-',$word_wrap_width);

            //Order Details
            $text .= $this->set_wrap_1('ITEM DETAIL');    
            $text .= $this->set_line('-',$word_wrap_width);        
            $text .= $this->set_wrap_2('SOLD ITEM','');
            foreach($get_t_item as $v):        
                $text .= $this->set_wrap_2($v['product_name'],number_format($v['trans_item_sell_total']));
                $text .= $this->set_wrap_0(number_format($v['trans_item_out_qty']).' x '.number_format($v['trans_item_out_price'])," ",'RIGHT');            
                $text .= $this->set_wrap_1(" ");
            endforeach;    
            $text .= $this->set_line(' ',$word_wrap_width);        
            $text .= $this->set_wrap_2('TOTAL ITEM',number_format($total_sell));              
            $text .= $this->set_line('-',$word_wrap_width);        

            //Payment Details
            $text .= $this->set_wrap_1('PAYMENT DETAIL');       
            $text .= $this->set_line('-',$word_wrap_width);  
            $text .= $this->set_wrap_2('Cash',number_format($total_paid_cash));
            $text .= $this->set_wrap_2('Bank Transfer',number_format($total_paid_transfer));        
            $text .= $this->set_wrap_2('EDC Card',number_format($total_paid_edc));        
            $text .= $this->set_wrap_2('QRIS',number_format($total_paid_qris));
            $text .= $this->set_wrap_2('Deposit',number_format($total_paid_dp));
            $text .= $this->set_wrap_2('Gratis',number_format($total_paid_free));
            $text .= $this->set_wrap_2('Voucher',number_format($total_paid_voucher));                                
            $text .= $this->set_line('-',$word_wrap_width);

            //Summary
            $text .= $this->set_wrap_2('TOTAL PAYMENT',number_format($total_paid));             
        }
        // echo json_encode($mdatas);die;
        // echo print('<pre>'.$text.'</pre>');die;

        $data['periode']        = date("d-M-Y, H:i", strtotime($date_start)).' sd '.date("d-M-Y, H:i", strtotime($date_end)); 
        $data['content']        = $mdatas;
        $data['title']          = "Laporan ".$this->trans_alias." Rekap";
        $data['contact_alias']  = $this->contact_1_alias;
        $data['employee_alias'] = $this->contact_2_alias;
        $data['ref_alias']      = $this->ref_alias;

        //Set Layout
        if($format=='html'){
            $this->load->view('layouts/admin/menu/prints/reports/report_sales_pos_recap',$data);
        }else if($format=='receipt'){
            // $text = '_';
            $file = fopen("print_business_".$data['branch']['branch_id'].".txt", "w") or die("Unable to open file");
            fwrite($file,$text);
            if(fclose($file)){
                echo json_encode(array('status'=>1,'print_url'=>base_url('print_business_'.$data['branch']['branch_id'].'.txt'),'print_to'=>$this->print_to));
            }else{
                echo json_encode(array('status'=>0,'message'=>'Print raw error','print_to'=>$this->print_to));
            }            
        }
    } 
    function sync_product(){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';
        $params = array(
            'product_type' => 1
        );
        $get_all_product = $this->Produk_model->get_all_produks($params,null,null,null,'product_name','asc');
        $datas = array();
        foreach($get_all_product as $v){
            $datas[] = array(
                'product_id' => intval($v['product_id']),
                'product_barcode' => $v['product_barcode'],
                'product_code' => !empty($v['product_code']) ? $v['product_code'] : '',
                'product_name' => $v['product_name'],
                'product_unit' => $v['product_unit'],   
                'product_category_id' => intval($v['product_category_id']),                             
                'product_image' => !empty($v['product_image']) ? $v['product_image'] : 'upload/noimage.png',
                'product_price_sell_format' => !empty($v['product_price_sell_format']) ? $v['product_price_sell_format'] : '0',
                'product_price_sell' => $v['product_price_sell'],
                'product_price_promo' => $v['product_price_promo'],
                'product_type' => $v['product_type']
            );
        }
        
        // encode array to json
        $json = json_encode($datas);

        //write json to file
        if (file_put_contents("data_products.json", $json)){
            $return->message = "JSON file created successfully...";
            $return->result = $datas;
            $return->status = 1;
        }else{ 
            $return->message = "Oops! Error creating json file...";
        }
        echo json_encode($return);
    }
    function prints_transaction_dot($id){ // ID = TRANS ID Print Thermal 58mm Done 
        $text = '';
        $word_wrap_width = 100;

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
            $text .= dot_set_wrap_1($word_wrap_width,$get_branch['branch_name']);
            $text .= dot_set_wrap_1($word_wrap_width,$get_branch['branch_address']);
            $text .= dot_set_wrap_1($word_wrap_width,$get_branch['branch_phone_1']);                
            $text .= dot_set_wrap_1($word_wrap_width,$get_trans['trans_number']);
            $text .= dot_set_wrap_1($word_wrap_width,date("d/m/Y - H:i:s", strtotime($get_trans['trans_date'])));    
            // $text .= $this->set_wrap_2('Cashier',$get_trans['contact_name']);

            $text .= "\n";
            $text .= dot_set_line('-',$word_wrap_width);

            $text .= dot_set_wrap_4($word_wrap_width,"Item", "Total","A","B");
            $text .= dot_set_line('-',$word_wrap_width);

            
            //Content Order Items
            $num = 0;
            foreach($order_data as $i => $v):
                //$text .= $v['order_number']."\n";
                $text .= dot_set_wrap_0($word_wrap_width,$v['order_number'],'-','BOTH');
                $text .= $v['ref_name']."\n";
                $text .= $v['order_date']."\n";  
                $text .= $v['employee_name']."\n";
                $text .= $v['user_name']."\n";         
                $text .= "\n";                      
                foreach($v['order_items'] as $i):
                    $text .= dot_set_wrap_0($word_wrap_width,$i['product_name'],' ','RIGHT');
                    $text .= dot_set_wrap_2($word_wrap_width,' '.number_format($i['order_item_price'],0,'',',') . ' x '. number_format($i['order_item_qty'],0,'',','), number_format($i['order_item_total'],0,'',','));
                    if($i['order_item_discount'] > 0){
                        $text.= dot_set_wrap_2($word_wrap_width,' Dis. '.number_format($i['order_item_discount']),' ');
                    }
                    $num++;
                endforeach;            
                $text .= "\n"; 
            endforeach;

            //Content Trans Items / Hanya tampil jika order_item kosong, maka ambil dari trans_items
            if($num < 1){
                foreach($get_trans_items as $v):
                    $text .= $v['product_name']."\n";
                    $text .= dot_set_wrap_2($word_wrap_width,' '.number_format($v['trans_item_out_qty'],0,'',',') . ' x '. number_format($v['trans_item_sell_price'],0,'',','), number_format($v['trans_item_sell_total'],0,'',','));            
                endforeach;  
            }     

            // $text .= "\n";
            $text .= dot_set_line('-',$word_wrap_width);
            $text .= dot_set_wrap_3($word_wrap_width,'Subtotal',':',number_format($get_trans['trans_total_dpp'],0,'',','));
            if(!empty($get_trans['trans_voucher']) && $get_trans['trans_voucher'] > 0){
                $text .= dot_set_wrap_3($word_wrap_width,'Voucher',':','-'.number_format($get_trans['trans_voucher'],0,'',','));    
            }
            if(!empty($get_trans['trans_discount']) && $get_trans['trans_discount'] > 0){
                $text .= dot_set_wrap_3($word_wrap_width,'Diskon',':','-'.number_format($get_trans['trans_discount'],0,'',','));    
            }            
            if((!empty($get_trans['trans_voucher'])) or (!empty($get_trans['trans_discount']))){
                $text .= dot_set_wrap_3($word_wrap_width,'Grand Total',':',number_format($get_trans['trans_total'],0,'',','));    
            }            
            $text .= dot_set_wrap_3($word_wrap_width,'Dibayar',':',number_format($get_trans['trans_received'],0,'',','));
            if(!empty($get_trans['trans_change']) && $get_trans['trans_change'] > 0){
                $text .= dot_set_wrap_3($word_wrap_width,'Kembali',':',number_format($get_trans['trans_change'],0,'',','));
            }     
            $text .= dot_set_wrap_3($word_wrap_width,'Pembayaran',':',$paid_type_name);

            //Footer
            $text .= "\n";
            $text .= dot_set_wrap_1($word_wrap_width,"-- Terima Kasih --");     

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
        // fwrite($file,$text);
        // // fclose($file);

        // if(fclose($file)){
        //     echo json_encode(array('status'=>1,'print_url'=>base_url('print_transaction_'.$get_branch['branch_id'].'.txt'),'print_to'=>$this->print_to));
        // }else{
        //     echo json_encode(array('status'=>0,'message'=>'Print raw error','print_to'=>$this->print_to));
        // }

        //Preview to HTML
        $this->output->set_content_type('text/plain', 'UTF-8');
        $this->output->set_output($text);
        
    }       
}
?>