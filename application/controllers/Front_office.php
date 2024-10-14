<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Front_office extends MY_Controller{

    public $folder_upload = 'upload/file/';
    public $folder_upload_file = 'upload/file/';    
    public $image_width   = 480;
    public $image_height  = 240;
    public $file_size_limit = 2097152; //in Byte

    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }
        $this->load->helper('form');
        // $this->load->library('form_validation');
        $this->load->model('Kontak_model');    
        $this->load->model('Kategori_model');            
        $this->load->model('Ref_model');    
        $this->load->model('Referensi_model');                
        $this->load->model('Branch_model');
        $this->load->model('Front_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Order_model');          
        $this->load->model('Type_model');                
        $this->load->model('User_model');
        $this->load->model('Produk_model');
        $this->load->model('File_model');                

        $this->print_to         = 0; //0 = Local, 1 = Bluetooth
        $this->whatsapp_config  = 1;
        $this->module_approval   = 0; //Approval
        $this->module_attachment   = 1; //Attachment         

        $this->contact_1_alias  = 'Customer';
        $this->contact_2_alias  = 'Sales By';
        $this->ref_alias        = 'Ruangan';         
        
        $this->order_alias      = 'Order';
        $this->trans_alias      = 'Trans';
        
        $this->payment_alias    = 'Checkout';  
        $this->dp_alias         = 'Down Payment'; 
        $this->product_alias    = 'Makanan';     
        
        $this->form_title       = 'POS 3';

        $this->booking_identity = 222;
        $this->resto_identity = 222;   

        $this->set_minimal_hour_to_checkin = 2; // 2 hour
        /*
            Booking = orders
            Resto = trans
        */
    }
    function booking_type($type_name){
            // Default First Date & End Date of Current Month
            $firstdate = new DateTime('first day of this month');

            $firstdateofmonth = $firstdate->format('d-m-Y');

            $data['session'] = $this->session->userdata();  

            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
            $session_group_id = !empty($data['session']['user_data']['user_group_id']) ? $data['session']['user_data']['user_group_id'] : null;
            $session_branch_id = !empty($data['session']['user_data']['branch']['id']) ? $data['session']['user_data']['branch']['id'] : null;            
            $data['branch'] = $this->Branch_model->get_all_branch(['branch_flag' => 1],null,null,null,'branch_name','asc');
            $data['ref'] = $this->Ref_model->get_all_ref(['references.ref_type' => 10],null,null,null,'ref_name','asc');
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = date("d-m-Y");
            
            $now = new DateTime();
            $date3 = $now->modify('+1 day')->format('Y-m-d H:i:s');

            $data['booking_start_date'] = date("d-M-Y");
            $data['booking_end_date'] = date("d-M-Y", strtotime($date3));              
            
            $data['hour'] = date("H:i");
            $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);

            $data['module_approval']    = $this->module_approval;
            $data['module_attachment'] = $this->module_attachment;                
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */


            // $data['ref'] = $this->Ref_model->get_all_ref_price(['price_flag' => 1],null,null,null,'price_name','asc');    

            $params = array(
                'booking_item_id' => 'value'
            );
            $search = null; $limit = null; $start = null; $order  = 'order_item_id'; $dir = 'desc';
            $get_booking_item = $this->Front_model->get_all_booking_item(null,$search,$limit,$start,$order,$dir);

            // var_dump($get_booking_item);die;
            $data['identity'] = $this->booking_identity;
            if($type_name == 'cece'){
                $branch_params = [
                    'branch_flag' => 1, 
                    'branch_code' => 1
                ];
                if($session_group_id > 2){ // Except Super Admin
                    $branch_params['branch_id'] = intval($session_branch_id);
                }                
                
                $ref_params = [
                    'references.ref_type' => 10,
                    'branchs.branch_code' => 1,
                    'references.ref_flag' => 1
                ];
                
                if($session_group_id > 2){ // Except Super Admin
                    $ref_params['branchs.branch_id'] = intval($session_branch_id);
                }

                $data['branch_cece']      = $this->Branch_model->get_all_branch($branch_params,null, null,null,'branch_name','asc');  
                $data['ref'] = $this->Ref_model->get_all_ref($ref_params,null,null,null,'branch_name','asc');          
                $data['title'] = 'Booking Cece';
                $data['_view'] = 'layouts/admin/menu/front_office/cece';
                $this->load->view('layouts/admin/index',$data);
                $this->load->view('layouts/admin/menu/front_office/cece_js.php',$data);
            }else if($type_name == 'lily'){

                $branch_params = [
                    'branch_flag' => 1, 
                    'branch_code' => 2
                ];
                if($session_group_id > 2){ // Except Super Admin
                    $branch_params['branch_id'] = intval($session_branch_id);
                }                
                
                $ref_params = [
                    'references.ref_type' => 10,
                    'branchs.branch_code' => 2,
                    'references.ref_flag' => 1
                ];
                
                if($session_group_id > 2){ // Except Super Admin
                    $ref_params['branchs.branch_id'] = intval($session_branch_id);
                }
                $data['branch_lily']      = $this->Branch_model->get_all_branch($branch_params,null, null,null,'branch_name','asc');  
                $data['ref'] = $this->Ref_model->get_all_ref($ref_params,null,null,null,'branch_name','asc');            

                $data['title'] = 'Booking Lily';
                $data['_view'] = 'layouts/admin/menu/front_office/lily';
                $this->load->view('layouts/admin/index',$data);
                $this->load->view('layouts/admin/menu/front_office/lily_js.php',$data);
            }
    }
    function booking(){
        if ($this->input->post()) {
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
            $session_user_group_id = intval($data['session']['user_data']['user_group_id']);

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            // die;
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'order_date',
                        '1' => 'order_number',
                        '5' => 'order_contact_name',                        
                        '9' => 'order_item_expired_day',
                    );

                    $limit     = !empty($post['length']) ? $post['length'] : 10;
                    $start     = !empty($post['start']) ? $post['start'] : 0;
                    $order     = !empty($post['order']) ? $columns[$post['order'][0]['column']] : $columns[0];
                    $dir       = !empty($post['order'][0]['dir']) ? $post['order'][0]['dir'] : "asc";
                    
                    $search    = [];
                    if(!empty($post['search']['value'])) {
                        $s = $post['search']['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array(
                        'order_item_type' => intval($post['tipe'])
                    );
                    
                    /* If Form Mode Transaction CRUD not Master CRUD */
                    !empty($post['date_start']) ? $params['order_item_start_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 00:00:00')) : $params;
                    !empty($post['date_end']) ? $params['order_item_start_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;

                    //Default Params for Master CRUD Form
                    // $params['order_id']   = !empty($post['order_id']) ? $post['order_id'] : $params;
                    // $params['order_name'] = !empty($post['order_name']) ? $post['order_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['order_type'] = $post['filter_type'];
                        }
                    */
                    if($post['filter_branch'] !== "All") {
                        $params['order_item_branch_id'] = intval($post['filter_branch']);
                    }
                    if($post['filter_ref'] !== "All") {
                        $params['order_item_ref_id'] = intval($post['filter_ref']);
                    }              
                    if($post['filter_item_type_2'] !== "All") {
                        $params['order_item_type_2'] = $post['filter_item_type_2'];
                    }      
                    if($post['filter_flag_checkin'] !== "All") {
                        $params['order_item_flag_checkin'] = $post['filter_flag_checkin'];
                    }            
                    if($post['filter_payment_method'] !== "All") {
                        $params['paid_payment_method'] = $post['filter_payment_method'];
                    }                          
                    if(!empty($post['filter_user']) && (intval($post['filter_user']) > 0)) {
                        $params['order_item_user_id'] = intval($post['filter_user']);
                    }                                                             
                    // if($post['filter_ref_price'] !== "All") {
                    //     $params['order_item_ref_id'] = $post['filter_ref'];
                    // }            
                    // if(is_numeric($post['filter_paid'])) {
                    //     $params['order_paid'] = intval($post['filter_paid']);
                    // }                                        

                    $get_count = $this->Front_model->get_all_booking_item_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = [];
                    }
                    $return->params = $params;
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "load_checkin":
                    $columns = array(
                        '0' => 'order_item_product_id',
                        '1' => 'order_date',
                        '2' => 'order_number'
                    );

                    $limit     = !empty($post['length']) ? $post['length'] : 10;
                    $start     = !empty($post['start']) ? $post['start'] : 0;
                    $order     = !empty($post['order']) ? $columns[$post['order'][0]['column']] : $columns[0];
                    $dir       = !empty($post['order'][0]['dir']) ? $post['order'][0]['dir'] : "asc";
                    
                    $search    = [];
                    if(!empty($post['search']['value'])) {
                        $s = $post['search']['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array(
                        'order_item_type' => intval($post['tipe'])
                    );
                    
                    !empty($post['date_start']) ? $params['order_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 00:00:00')) : $params;
                    !empty($post['date_end']) ? $params['order_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;

                    //Default Params for Master CRUD Form
                    // $params['order_id']   = !empty($post['order_id']) ? $post['order_id'] : $params;
                    // $params['order_name'] = !empty($post['order_name']) ? $post['order_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['order_type'] = $post['filter_type'];
                        }
                    */
                    if($post['filter_branch'] !== "All") {
                        $params['order_item_branch_id'] = intval($post['filter_branch']);
                    }
                    if($post['filter_ref'] !== "All") {
                        $params['order_item_ref_id'] = intval($post['filter_ref']);
                    }                    
                    if($post['filter_payment_method'] !== "All") {
                        $params['paid_payment_method'] = $post['filter_payment_method'];
                    }     
                                     
                    // if($post['filter_ref_price'] !== "All") {
                    //     $params['order_item_ref_id'] = $post['filter_ref'];
                    // }            
                    // if(is_numeric($post['filter_paid'])) {
                    //     $params['order_paid'] = intval($post['filter_paid']);
                    // }                                        

                    $get_count = $this->Front_model->get_all_booking_item_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = [];
                    }
                    $return->params = $params;
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "load_checkin_cece":
                    $columns = array(
                        '0' => 'order_item_order_id',
                        '1' => 'order_item_flag_checkin',
                        '2' => 'order_date',
                        '3' => 'order_number',
                        '4' => 'order_item_ref_id',
                        '5' => 'product_name',
                        '6' => 'order_contact_name',
                        '7' => 'order_contact_phone',                        
                    );

                    $limit     = !empty($post['length']) ? $post['length'] : 10;
                    $start     = !empty($post['start']) ? $post['start'] : 0;
                    $order     = !empty($post['order']) ? $columns[$post['order'][0]['column']] : $columns[0];
                    $dir       = !empty($post['order'][0]['dir']) ? $post['order'][0]['dir'] : "asc";
                    
                    $search    = [];
                    if(!empty($post['search']['value'])) {
                        $s = $post['search']['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array(
                        'order_item_type' => intval($post['tipe']),
                        'branch_code' => 1,
                    );
                    // 'order_item_flag_checkin <' => 3
                    
                    /* If Form Mode Transaction CRUD not Master CRUD */
                    !empty($post['date_start']) ? $params['order_item_start_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 00:00:00')) : $params;
                    !empty($post['date_end']) ? $params['order_item_start_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;

                    //Default Params for Master CRUD Form
                    // $params['order_id']   = !empty($post['order_id']) ? $post['order_id'] : $params;
                    // $params['order_name'] = !empty($post['order_name']) ? $post['order_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['order_type'] = $post['filter_type'];
                        }
                    */
                    if((!empty($post['fiter_branch'])) && ($post['filter_branch'] !== "All")) {
                        $params['order_item_branch_id'] = intval($post['filter_branch']);
                    }
                    if($post['filter_ref'] !== "All") {
                        $params['order_item_ref_id'] = intval($post['filter_ref']);
                    }                    
                    // if($post['filter_payment_method'] !== "All") {
                    //     $params['paid_payment_method'] = $post['filter_payment_method'];
                    // }
                    if($post['filter_flag_checkin'] !== "All") {
                        $params['order_item_flag_checkin'] = $post['filter_flag_checkin'];
                    }                                                            
                    // if($post['filter_ref_price'] !== "All") {
                    //     $params['order_item_ref_id'] = $post['filter_ref'];
                    // }            
                    if($post['filter_paid_flag'] !== "All") {
                        $params['order_paid'] = intval($post['filter_paid_flag']);
                    }                                        

                    $get_count = $this->Front_model->get_all_booking_item_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = [];
                    }
                    $return->params = $params;
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "load_checkin_lily":
                    $columns = array(
                        '0' => 'order_item_order_id',
                        '1' => 'order_item_flag_checkin',
                        '2' => 'order_date',
                        '3' => 'order_number',
                        '4' => 'order_item_ref_id',
                        '5' => 'product_name',
                        '6' => 'order_contact_name',
                        '7' => 'order_contact_phone',
                    );

                    $limit     = !empty($post['length']) ? $post['length'] : 10;
                    $start     = !empty($post['start']) ? $post['start'] : 0;
                    $order     = !empty($post['order']) ? $columns[$post['order'][0]['column']] : $columns[0];
                    $dir       = !empty($post['order'][0]['dir']) ? $post['order'][0]['dir'] : "asc";
                    
                    $search    = [];
                    if(!empty($post['search']['value'])) {
                        $s = $post['search']['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array(
                        'order_item_type' => intval($post['tipe']),
                        'branch_code' => 2
                    );
                    // 'order_item_flag_checkin <' => 3
                    
                    /* If Form Mode Transaction CRUD not Master CRUD */
                    !empty($post['date_start']) ? $params['order_item_start_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 00:00:00')) : $params;
                    !empty($post['date_end']) ? $params['order_item_start_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params; 

                    //Default Params for Master CRUD Form
                    // $params['order_id']   = !empty($post['order_id']) ? $post['order_id'] : $params;
                    // $params['order_name'] = !empty($post['order_name']) ? $post['order_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['order_type'] = $post['filter_type'];
                        }
                    */
                    if($post['filter_branch'] !== "All") {
                        $params['order_item_branch_id'] = intval($post['filter_branch']);
                    }
                    if($post['filter_ref'] !== "All") {
                        $params['order_item_ref_id'] = intval($post['filter_ref']);
                    }                 
                    if($post['filter_payment_method'] !== "All") {
                        $params['paid_payment_method'] = $post['filter_payment_method'];
                    }           
                    if($post['filter_flag_checkin'] !== "All") {
                        $params['order_item_flag_checkin'] = $post['filter_flag_checkin'];
                    }      
                    if(!empty($post['filter_user']) && (intval($post['filter_user']) > 0)) {
                        $params['order_item_user_id'] = intval($post['filter_user']);
                    }                                                    
                    if($post['filter_ref_price'] !== "All") {
                        $params['order_item_ref_price_sort'] = $post['filter_ref_price'];
                    }            
                    // if(is_numeric($post['filter_paid'])) {
                    //     $params['order_paid'] = intval($post['filter_paid']);
                    // }                                        

                    $get_count = $this->Front_model->get_all_booking_item_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = [];
                    }
                    $return->params = $params;
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "create_update":
                    $this->form_validation->set_rules('order_type', 'Type', 'required');
                    $this->form_validation->set_rules('order_ref_id', 'Jenis Kamar', 'greater_than[0]');                    
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    $this->form_validation->set_message('greater_than', '{field} wajib dipilih');                    
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        if( (!empty($post['order_id'])) && (strlen($post['order_id']) > 0) ){ /* Update if Exist */ // if( (!empty($post['order_session'])) && (strlen($post['order_session']) > 10) ){ /* Update if Exist */      

                            $return->message = 'Update tidak tersedia';
                            echo json_encode($return);
                            return;

                            /* Check Existing Data */
                            $params_check = [
                                'order_number' => !empty($post['order_number']) ? $post['order_number'] : null
                            ];

                            $where_not = [
                                'order_id' => intval($post['order_id']),                   
                            ];                            
                            $where_new = [
                                'order_number' => 'Sel',
                                'order_flag' => 1
                            ];
                            $check_exists = $this->Front_model->check_data_exist_two_condition($where_not,$where_new);

                            /* Continue Update if not exist */
                            if(!$check_exists){
                                $params = array(
                                    'order_name' => !empty($post['order_name']) ? $post['order_name'] : null,
                                    'order_flag' => !empty($post['order_flag']) ? $post['order_flag'] : 0
                                );
                                $create = $this->Front_model->add_booking($params);   
                                if($create){
                                    $get_booking = $this->Front_model->get_booking($create);
               
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$post['order_name'];
                                    $return->result= array(
                                        'order_id' => $create,
                                        'order_name' => $get_booking['order_name'],
                                        'order_session' => $get_booking['order_session']
                                    );                                
                                }else{
                                    $return->message = 'Gagal menambahkan '.$post['order_name'];
                                }
                            }else{
                                $return->message = 'Data sudah digunakan';
                            }
                        }else{ /* Save New Data */
                            $next = true; $message = '';

                            $return->message = 'Simpan tidak tersedia';
                            echo json_encode($return);
                            return;
                            //Check Ref Price ID
                            /*
                            $start_date = $post['order_start_date'];
                            $end_date   = $post['order_end_date'];                            
                            if($post['order_ref_price_id'] == 0){ //Bulanan

                            }else if($post['order_ref_price_id'] == 1){ //harian
                                
                            }else if($post['order_ref_price_id'] == 2){ //midnight
                                $start_date  = $start_date.$post['order_start_hour'].':00';
                                $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                $check_date  = $this->hour_diff($start_date,$end_date);
                                if(intval($check_date) < 0){
                                    $message = 'Jam tidak boleh mundur';
                                    $next = false;
                                }else if(intval($check_date) > 4){
                                    $message = 'Tidak boleh lebih dari 4 jam';
                                    $next = false;
                                }                                
                            }else if($post['order_ref_price_id'] == 3){ //4jam
                                $start_date  = $start_date.$post['order_start_hour'].':00';
                                $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                $check_date  = $this->hour_diff($start_date,$end_date);
                                if(intval($check_date) < 0){
                                    $message = 'Jam tidak boleh mundur';
                                    $next = false;
                                }else if(intval($check_date) > 4){
                                    $message = 'Tidak boleh lebih dari 4 jam';
                                    $next = false;
                                }
                            }else if($post['order_ref_price_id'] == 4){ //2jam
                                $start_date  = $start_date.$post['order_start_hour'].':00';
                                $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                $check_date  = $this->hour_diff($start_date,$end_date);
                                if(intval($check_date) < 0){
                                    $message = 'Jam tidak boleh mundur';
                                    $next = false;
                                }else if(intval($check_date) > 2){
                                    $message = 'Tidak boleh lebih dari 2 jam';
                                    $next = false;
                                }
                            }
                            var_dump($message);die;
                            */

                            $check_date_is_past =$this->day_diff(date("Y-m-d H:i:s"), $post['order_start_date']." ".$post['order_start_hour'].":00");
                            if(intval($check_date_is_past) < 0){
                                $next = false;
                                $message = 'Tanggal Mulai tidak boleh mundur';
                            }

                            if($next){
                                $check_date_is_past2 =$this->day_diff($post['order_start_date']." 00:00:00", $post['order_end_date']." 00:00:00");
                                if(intval($check_date_is_past2) < 0){
                                    $next = false;
                                    $message = 'Tanggal Akhir sudah lewat';
                                }         
                            }
                            
                            if($next){
                                /* Check Existing Data */
                                $params_check = [
                                    'order_item_type_2' => !empty($post['order_type_2']) ? $post['order_type_2'] : null,
                                    'order_item_ref_id' => !empty($post['order_ref_id']) ? $post['order_ref_id'] : null ,
                                    'order_item_start_date' => $post['order_start_date'],                               
                                ];
                                $check_exists = $this->Front_model->check_data_exist_items($params_check);

                                /* Continue Save if not exist */
                                if(!$check_exists){
                                    
                                    $get_set_branch = $this->Produk_model->get_produk_quick($post['order_product_id']);
                                    $get_product_branch = $get_set_branch['product_branch_id'];

                                    $order_session = $this->random_session(20);
                                    $order_number  = $this->request_number_for_booking($post['order_type'],$get_product_branch);
                                    $params = array(
                                        'order_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                        'order_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                        'order_number' => $order_number,
                                        'order_session' => $order_session,
                                        // 'order_date_due' => !empty($post['order_date_due']) ? $post['order_files_count'] : null,
                                        // 'order_contact_id' => !empty($post['order_contact_id']) ? $post['order_files_count'] : null,
                                        // 'order_contact_id_2' => !empty($post['order_contact_id_2']) ? $post['order_files_count'] : null,
                                        // 'order_ppn' => !empty($post['order_ppn']) ? $post['order_files_count'] : null,
                                        // 'order_total_dpp' => !empty($post['order_total_dpp']) ? intval($post['order_total_dpp']) : null,
                                        // 'order_discount' => !empty($post['order_discount']) ? intval($post['order_discount']) : null,
                                        // 'order_voucher' => !empty($post['order_voucher']) ? intval($post['order_voucher']) : null,
                                        // 'order_with_dp' => !empty($post['order_with_dp']) ? intval($post['order_with_dp']) : null,
                                        // 'order_total' => !empty($post['order_total']) ? intval($post['order_total']) : null,
                                        // 'order_with_dp_account' => !empty($post['order_with_dp_account']) ? $post['order_files_count'] : null,
                                        // 'order_note' => !empty($post['order_note']) ? $post['order_files_count'] : null,
                                        'order_user_id' => $session_user_id,
                                        'order_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                        'order_date' => date("YmdHis"),
                                        'order_date_created' => date("YmdHis"),
                                        // 'order_date_updated' => !empty($post['order_date_updated']) ? $post['order_files_count'] : null,
                                        'order_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                                        // 'order_trans_id' => !empty($post['order_trans_id']) ? $post['order_files_count'] : null,
                                        // 'order_ref_number' => !empty($post['order_ref_number']) ? $post['order_files_count'] : null,
                                        // 'order_approval_flag' => !empty($post['order_approval_flag']) ? intval($post['order_approval_flag']) : null,
                                        // 'order_label' => !empty($post['order_label']) ? $post['order_files_count'] : null,
                                        // 'order_sales_id' => !empty($post['order_sales_id']) ? $post['order_files_count'] : null,
                                        // 'order_sales_name' => !empty($post['order_sales_name']) ? $post['order_files_count'] : null,
                                        'order_contact_code' => !empty($post['order_contact_code']) ? str_replace(' ','',$post['order_contact_code']) : null,
                                        'order_contact_name' => !empty($post['order_contact_name']) ? $post['order_contact_name'] : null,
                                        'order_contact_phone' => !empty($post['order_contact_phone']) ? $this->contact_number($post['order_contact_phone']) : null,
                                    );
                                    
                                    $create = $this->Front_model->add_booking($params);   
                                    // $create = true;
                                    if($create){
                                        $get_booking = $this->Front_model->get_booking($create);
                                        
                                        //Croppie Upload Image [Bukti Bayar]
                                        $post_upload = !empty($this->input->post('upload_1')) ? $this->input->post('upload_1') : "";
                                        if(strlen($post_upload) > 10){
                                            $upload_process = $this->file_upload_image($this->folder_upload_file,$post_upload);
                                            if($upload_process->status == 1){
                                                if ($get_booking && $get_booking['order_id']) {
                                                    // $params_image = array(
                                                    //     'product_image' => $upload_process->result['file_location']
                                                    // );
                                                    // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                                    $file_session = $this->random_session(20);
                                                    $params_image = array(
                                                        'file_type' => 1,
                                                        'file_from_table' => 'orders',
                                                        'file_from_id' => $get_booking['order_id'],
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_name' => $upload_process->result['file_name'] . $upload_process->result['file_ext'],
                                                        'file_format' => str_replace(".","",$upload_process->result['file_ext']),
                                                        'file_url' => $upload_process->result['file_location'],
                                                        // 'file_size' => $upload_process['result']['file_size']
                                                    );                                                    
                                                    $stat = $this->File_model->add_file($params_image);
                                                    $set_paid_url = $upload_process->result['file_location'];
                                                    $set_paid_name = $upload_process->result['file_name'] . $upload_process->result['file_ext'];
                                                }
                                            }else{
                                                $return->message = 'Fungsi Gambar gagal';
                                            }
                                        }
                                        //End of Croppie   

                                        //Croppie Upload Image [KTP]
                                        $post_upload = !empty($this->input->post('upload_2')) ? $this->input->post('upload_2') : "";
                                        if(strlen($post_upload) > 10){
                                            $upload_process = $this->file_upload_image($this->folder_upload_file,$post_upload);
                                            if($upload_process->status == 1){
                                                if ($get_booking && $get_booking['order_id']) {
                                                    // $params_image = array(
                                                    //     'product_image' => $upload_process->result['file_location']
                                                    // );
                                                    // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                                    $file_session = $this->random_session(20);
                                                    $params_image = array(
                                                        'file_type' => 1,
                                                        'file_from_table' => 'orders',
                                                        'file_from_id' => $get_booking['order_id'],
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_name' => $upload_process->result['file_name'] . $upload_process->result['file_ext'],
                                                        'file_format' => str_replace(".","",$upload_process->result['file_ext']),
                                                        'file_url' => $upload_process->result['file_location'],
                                                        // 'file_size' => $upload_process['result']['file_size']
                                                    );                                                    
                                                    $stat = $this->File_model->add_file($params_image);
                                                }
                                            }else{
                                                $return->message = 'Fungsi Gambar gagal';
                                            }
                                        }
                                        //End of Croppie                                           
                                        
                                        //Set Paid
                                        if($post['paid_total'] > 0){
                                            $file_session = $this->random_session(20);
                                            $paid_number = $this->request_number_for_order_paid();
                                            $params = array(
                                                // 'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                                                'paid_order_id' => $get_booking['order_id'],
                                                'paid_number' => $paid_number,
                                                'paid_session' => $file_session,
                                                'paid_date_created' => date("YmdHis"),
                                                'paid_date' => date("YmdHis"),                                    
                                                'paid_user_id' => $session_user_id,
                                                'paid_url' => $set_paid_url,
                                                // 'paid_name' => $set_paid_name,
                                                'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_payment_method'] : null,
                                                'paid_total' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : null                           
                                            );
                                            $save_data = $this->Front_model->add_paid($params);
                                        }
                                        //End Set Paid

                                        $params_price = array(
                                            'price_ref_id' => $post['order_ref_id'],
                                            'price_sort' => $post['order_ref_price_id']
                                        );
                                        $get_price = $this->Ref_model->get_ref_price_custom($params_price);
                                        $order_ref_price_id = $get_price['price_id'];
                                        
                                        //Save Item
                                        $params_items = array(
                                            'order_item_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                            'order_item_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                            'order_item_type_name' => !empty($post['order_item_type_name']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_order_id' => $create,
                                            'order_item_product_id' => !empty($post['order_product_id']) ? $post['order_product_id'] : null,
                                            'order_item_qty' => !empty($post['order_item_qty']) ? intval($post['order_item_qty']) : 1,
                                            // 'order_item_unit' => !empty($post['order_item_unit']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_price' => !empty($post['order_price']) ? str_replace(",","",$post['order_price']) : "0.00",
                                            // 'order_item_total' => !empty($post['order_item_total']) ? intval($post['order_item_total']) : null,
                                            // 'order_item_note' => !empty($post['order_item_note']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_user_id' => $session_user_id,
                                            // 'order_item_date' => !empty($post['order_item_date']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_date_created' => date("YmdHis"),
                                            // 'order_item_date_updated' => !empty($post['order_item_date_updated']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_flag' => 0,
                                            // 'order_item_product_price_id' => !empty($post['order_item_product_price_id']) ? $post['order_item_contact_id_2'] : null,
                                            // 'order_item_ppn' => !empty($post['order_item_ppn']) ? intval($post['order_item_ppn']) : null,
                                            'order_item_order_session' => $order_session,
                                            // 'order_item_session' => !empty($post['order_item_session']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                            'order_item_ref_price_id' => $order_ref_price_id,
                                            // 'order_item_ref_price_sort' => $post['order_item_ref_price_sort'],
                                            'order_item_flag_checkin' => 0,
                                        );                      
        
                                        if($post['order_ref_price_id'] == "1"){ // Old 0
                                            // $params_items['order_item_start_hour'] = '14:00:00';
                                            $params_items['order_item_type_2'] = 'Bulanan';
                                            $set_start_hour = '14:00:00';
                                            $set_end_hour = '12:00:00';                                        
                                        }else {
                                            $params_items['order_item_type_2'] = 'Transit';                                        
                                            // $params_items['order_item_start_hour'] = $post['order_start_hour'];

                                            if($post['order_ref_price_id'] == "2"){
                                                // Harian
                                                $set_start_hour = '14:00:00';
                                                $set_end_hour = '12:00:00';                                                
                                            }else{
                                                // Midnight, 4 Jam, 2 Jam
                                                $set_start_hour = $post['order_start_hour'].':00';
                                                $set_end_hour = $post['order_end_hour'].':00';                                       
                                            }                                                                                 
                                        }

                                        $params_items['order_item_start_date'] = $post['order_start_date'] .' '.$set_start_hour; 
                                        $params_items['order_item_end_date'] = $post['order_end_date'] .' '.$set_end_hour;

                                        // var_dump($params_items);die;          
                                        $create_item = $this->Front_model->add_booking_item($params_items);
                                        //End Save Item                                    
                                        $return->status  = 1;
                                        $return->message = 'Berhasil menambahkan '.$order_number;
                                        $return->result= array(
                                            'order_id' => $create,
                                            'order_number' => $get_booking['order_number'],
                                            'order_session' => $get_booking['order_session']
                                        );                                
                                    }else{
                                        $return->message = 'Gagal menambahkan '.$post['order_number'];
                                    }
                                }else{
                                    $return->message = 'Data sudah ada';
                                }  
                            }else{
                                $return->message = $message;
                            }                       
                        }
                    }
                    break;
                case "create_update_lily":
                    $this->form_validation->set_rules('order_type', 'Type', 'required');
                    $this->form_validation->set_rules('order_ref_id', 'Jenis Kamar', 'greater_than[0]');
                    $this->form_validation->set_rules('upload_1', 'Foto Bukti Transfer', 'required');   
                    $this->form_validation->set_rules('upload_2', 'Foto KTP', 'required');                    
                    $this->form_validation->set_rules('paid_total', 'Jumlah Pembayaran', 'required');    
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    $this->form_validation->set_message('greater_than', '{field} wajib dipilih');                    
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        if( (!empty($post['order_id'])) && (strlen($post['order_id']) > 0) ){ /* Update if Exist */ // if( (!empty($post['order_session'])) && (strlen($post['order_session']) > 10) ){ /* Update if Exist */      

                            $return->message = 'Update tidak tersedia';
                            echo json_encode($return);
                            return;

                            /* Check Existing Data */
                            $params_check = [
                                'order_number' => !empty($post['order_number']) ? $post['order_number'] : null
                            ];

                            $where_not = [
                                'order_id' => intval($post['order_id']),                   
                            ];                            
                            $where_new = [
                                'order_number' => 'Sel',
                                'order_flag' => 1
                            ];
                            $check_exists = $this->Front_model->check_data_exist_two_condition($where_not,$where_new);

                            /* Continue Update if not exist */
                            if(!$check_exists){
                                $params = array(
                                    'order_name' => !empty($post['order_name']) ? $post['order_name'] : null,
                                    'order_flag' => !empty($post['order_flag']) ? $post['order_flag'] : 0
                                );
                                $create = $this->Front_model->add_booking($params);   
                                if($create){
                                    $get_booking = $this->Front_model->get_booking($create);
                
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$post['order_name'];
                                    $return->result= array(
                                        'order_id' => $create,
                                        'order_name' => $get_booking['order_name'],
                                        'order_session' => $get_booking['order_session']
                                    );                                
                                }else{
                                    $return->message = 'Gagal menambahkan '.$post['order_name'];
                                }
                            }else{
                                $return->message = 'Data sudah digunakan';
                            }
                        }else{ /* Save New Data */
                            $next = true; $message = '';

                            //Check Ref Price ID
                            /*
                                $start_date = $post['order_start_date'];
                                $end_date   = $post['order_end_date'];                            
                                if($post['order_ref_price_id'] == 0){ //Bulanan

                                }else if($post['order_ref_price_id'] == 1){ //harian
                                    
                                }else if($post['order_ref_price_id'] == 2){ //midnight
                                    $start_date  = $start_date.$post['order_start_hour'].':00';
                                    $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                    $check_date  = $this->hour_diff($start_date,$end_date);
                                    if(intval($check_date) < 0){
                                        $message = 'Jam tidak boleh mundur';
                                        $next = false;
                                    }else if(intval($check_date) > 4){
                                        $message = 'Tidak boleh lebih dari 4 jam';
                                        $next = false;
                                    }                                
                                }else if($post['order_ref_price_id'] == 3){ //4jam
                                    $start_date  = $start_date.$post['order_start_hour'].':00';
                                    $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                    $check_date  = $this->hour_diff($start_date,$end_date);
                                    if(intval($check_date) < 0){
                                        $message = 'Jam tidak boleh mundur';
                                        $next = false;
                                    }else if(intval($check_date) > 4){
                                        $message = 'Tidak boleh lebih dari 4 jam';
                                        $next = false;
                                    }
                                }else if($post['order_ref_price_id'] == 4){ //2jam
                                    $start_date  = $start_date.$post['order_start_hour'].':00';
                                    $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                    $check_date  = $this->hour_diff($start_date,$end_date);
                                    if(intval($check_date) < 0){
                                        $message = 'Jam tidak boleh mundur';
                                        $next = false;
                                    }else if(intval($check_date) > 2){
                                        $message = 'Tidak boleh lebih dari 2 jam';
                                        $next = false;
                                    }
                                }
                                var_dump($message);die;
                            */

                            // $check_date_is_past =$this->day_diff(date("Y-m-d H:i:s"), $post['order_start_date']." ".$post['order_start_hour'].":00");
                            $check_date_is_past =$this->time_diff(date("Y-m-d H:i:s"), $post['order_start_date']." ".$post['order_start_hour'].":00");                            
                            if(intval($check_date_is_past) < 0){
                                $next = false;
                                $message = 'Waktu Checkin tidak boleh mundur';
                                $return->msg = date("Y-m-d H:i:s").' < '.$post['order_start_date']." ".$post['order_start_hour'].":00";
                            }

                            if($next){
                                // $check_date_is_past2 =$this->day_diff($post['order_start_date']." 00:00:00", $post['order_end_date']." 00:00:00");
                                $check_date_is_past2 =$this->time_diff($post['order_start_date']." 00:00:00", $post['order_end_date']." 00:00:00");                                
                                if(intval($check_date_is_past2) < 0){
                                    $next = false;
                                    $message = 'Waktu Akhir sudah lewat';
                                }         
                            }
                            
                            if($session_user_group_id < 3){
                                $next=true;
                            }
                            // var_dump($next);die;
                            if($next){
                                $room_id = !empty($post['order_product_id']) ? $post['order_product_id'] : null;
                                // $sdate = $post['order_start_date']." 00:00:00";
                                // $edate = $post['order_end_date']." 23:59:59";
                                $start_minute = !empty($post['order_start_hour_minute']) ? $post['order_start_hour_minute'] : "00";
                                $end_minute = !empty($post['order_end_hour_minute']) ? $post['order_end_hour_minute'] : "00";

                                $sdate = $post['order_start_date']." ".$post['order_start_hour'].":".$start_minute.":00";
                                $edate = $post['order_end_date']." ".$post['order_end_hour'].":".$end_minute.":00";                                
                                // $check_room_available = $this->Front_model->get_room_available_count($room_id,$sdate,$edate);
                                $check_room_available = $this->Front_model->sp_room_check($room_id,$sdate,$edate);
                                // var_dump($check_room_available['room_is_available'],$room_id,$sdate,$edate);die; 
                                if(intval($check_room_available['room_is_available']) > 0){
                                    $get_product = $this->Produk_model->get_produk_quick($post['order_product_id']);

                                    $next = false;
                                    $message = $get_product['product_name'].' tidak tersedia';
                                }
                            }

                            if($next){
                                /* Check Existing Data */
                                $params_check = [
                                    'order_item_type_2' => !empty($post['order_type_2']) ? $post['order_type_2'] : null,
                                    'order_item_ref_id' => !empty($post['order_ref_id']) ? $post['order_ref_id'] : null ,
                                    'order_item_start_date' => $post['order_start_date'],                               
                                ];
                                // var_dump($params_check);die;
                                $check_exists = $this->Front_model->check_data_exist_items($params_check);

                                /* Continue Save if not exist */
                                if(!$check_exists){

                                    $get_set_branch = $this->Produk_model->get_produk_quick($post['order_product_id']);
                                    $get_product_branch = $get_set_branch['product_branch_id'];

                                    $order_session = $this->random_session(20);
                                    $order_number  = $this->request_number_for_booking($post['order_type'],$get_product_branch);
                                    $params = array(
                                        'order_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                        'order_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                        'order_number' => $order_number,
                                        'order_session' => $order_session,
                                        // 'order_date_due' => !empty($post['order_date_due']) ? $post['order_files_count'] : null,
                                        // 'order_contact_id' => !empty($post['order_contact_id']) ? $post['order_files_count'] : null,
                                        // 'order_contact_id_2' => !empty($post['order_contact_id_2']) ? $post['order_files_count'] : null,
                                        // 'order_ppn' => !empty($post['order_ppn']) ? $post['order_files_count'] : null,
                                        // 'order_total_dpp' => !empty($post['order_total_dpp']) ? intval($post['order_total_dpp']) : null,
                                        // 'order_discount' => !empty($post['order_discount']) ? intval($post['order_discount']) : null,
                                        // 'order_voucher' => !empty($post['order_voucher']) ? intval($post['order_voucher']) : null,
                                        // 'order_with_dp' => !empty($post['order_with_dp']) ? intval($post['order_with_dp']) : null,
                                        // 'order_total' => !empty($post['order_total']) ? intval($post['order_total']) : null,
                                        // 'order_with_dp_account' => !empty($post['order_with_dp_account']) ? $post['order_files_count'] : null,
                                        // 'order_note' => !empty($post['order_note']) ? $post['order_files_count'] : null,
                                        'order_user_id' => $session_user_id,
                                        'order_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                        'order_date' => date("YmdHis"),
                                        'order_date_created' => date("YmdHis"),
                                        // 'order_date_updated' => !empty($post['order_date_updated']) ? $post['order_files_count'] : null,
                                        'order_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                                        // 'order_trans_id' => !empty($post['order_trans_id']) ? $post['order_files_count'] : null,
                                        // 'order_ref_number' => !empty($post['order_ref_number']) ? $post['order_files_count'] : null,
                                        // 'order_approval_flag' => !empty($post['order_approval_flag']) ? intval($post['order_approval_flag']) : null,
                                        // 'order_label' => !empty($post['order_label']) ? $post['order_files_count'] : null,
                                        // 'order_sales_id' => !empty($post['order_sales_id']) ? $post['order_files_count'] : null,
                                        // 'order_sales_name' => !empty($post['order_sales_name']) ? $post['order_files_count'] : null,
                                        'order_contact_code' => !empty($post['order_contact_code']) ? str_replace(' ','',$post['order_contact_code']) : null,
                                        'order_contact_name' => !empty($post['order_contact_name']) ? $post['order_contact_name'] : null,
                                        'order_contact_phone' => !empty($post['order_contact_phone']) ? $this->contact_number($post['order_contact_phone']) : null,
                                    );
                                    
                                    $create = $this->Front_model->add_booking($params);   
                                    // $create = true;
                                    if($create){
                                        $get_booking = $this->Front_model->get_booking($create);
                                        
                                        //Croppie Upload Image [Bukti Bayar]
                                        $set_paid_url = null;
                                        $set_paid_name = null;
                                        $post_upload = !empty($this->input->post('upload_1')) ? $this->input->post('upload_1') : "";
                                        if(strlen($post_upload) > 10){
                                            $upload_process = upload_file_base64($this->folder_upload_file,$post_upload);
                                            if($upload_process['status'] == 1){
                                                if ($get_booking && $get_booking['order_id']) {
                                                    // $params_image = array(
                                                    //     'product_image' => $upload_process->result['file_location']
                                                    // );
                                                    // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                                    $file_session = $this->random_session(20);
                                                    $params_image = array(
                                                        'file_type' => 1,
                                                        'file_from_table' => 'orders',
                                                        'file_from_id' => $get_booking['order_id'],
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_name' => 'Bukti Bayar - '.$upload_process['result']['file_name'],                                                        
                                                        'file_format' => str_replace(".","",$upload_process['result']['file_ext']),
                                                        'file_url' => $upload_process['result']['file_location'],
                                                        'file_size' => $upload_process['result']['file_size'],
                                                        'file_note' => 'Bukti Bayar'
                                                    );                                                    
                                                    $stat = $this->File_model->add_file($params_image);
                                                    $set_paid_url = $upload_process['result']['file_location'];
                                                    $set_paid_name = $upload_process['result']['file_name'] . $upload_process['result']['file_ext'];
                                                }
                                            }else{
                                                $return->message = 'Fungsi Gambar gagal';
                                            }
                                        }
                                        //End of Croppie   

                                        //Croppie Upload Image [KTP]
                                        $post_upload = !empty($this->input->post('upload_2')) ? $this->input->post('upload_2') : "";
                                        if(strlen($post_upload) > 10){
                                            $upload_process = upload_file_base64($this->folder_upload_file,$post_upload);                                               
                                            if($upload_process['status'] == 1){
                                                if ($get_booking && $get_booking['order_id']) {
                                                    // $params_image = array(
                                                    //     'product_image' => $upload_process->result['file_location']
                                                    // );
                                                    // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                                    $file_session = $this->random_session(20);
                                                    $params_image = array(
                                                        'file_type' => 1,
                                                        'file_from_table' => 'orders',
                                                        'file_from_id' => $get_booking['order_id'],
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_name' => 'KTP - '.$upload_process['result']['file_name'],                                                        
                                                        'file_format' => str_replace(".","",$upload_process['result']['file_ext']),
                                                        'file_url' => $upload_process['result']['file_location'],
                                                        'file_size' => $upload_process['result']['file_size'],
                                                        'file_note' => 'KTP'
                                                    );                                                    
                                                    $stat = $this->File_model->add_file($params_image);
                                                }
                                            }else{
                                                $return->message = 'Fungsi Gambar gagal';
                                            }
                                        }
                                        //End of Croppie                                           
                                        
                                        //Set Paid
                                        if($post['paid_total'] > 0){
                                            $file_session = $this->random_session(20);
                                            $paid_number = $this->request_number_for_order_paid();
                                            $params = array(
                                                // 'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                                                'paid_order_id' => $get_booking['order_id'],
                                                'paid_number' => $paid_number,
                                                'paid_session' => $file_session,
                                                'paid_date_created' => date("YmdHis"),
                                                'paid_date' => date("YmdHis"),                                    
                                                'paid_user_id' => $session_user_id,
                                                'paid_url' => $set_paid_url,
                                                'paid_name' => $set_paid_name,
                                                'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_payment_method'] : null,
                                                'paid_total' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : null                           
                                            );
                                            $save_data = $this->Front_model->add_paid($params);
                                        }
                                        //End Set Paid

                                        // $params_price = array(
                                        //     'price_ref_id' => $post['order_ref_id'],
                                        //     'price_sort' => $post['order_ref_price_id']
                                        // );
                                        // $get_price = $this->Ref_model->get_ref_price_custom($params_price);
                                        // $order_ref_price_id = $get_price['price_id'];
                                        
                                        //Save Item
                                        $params_items = array(
                                            'order_item_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                            'order_item_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                            'order_item_type_name' => !empty($post['order_item_type_name']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_order_id' => $create,
                                            'order_item_product_id' => !empty($post['order_product_id']) ? $post['order_product_id'] : null,
                                            'order_item_qty' => !empty($post['order_item_qty']) ? intval($post['order_item_qty']) : 1,
                                            // 'order_item_unit' => !empty($post['order_item_unit']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_price' => !empty($post['order_price']) ? str_replace(",","",$post['order_price']) : "0.00",
                                            // 'order_item_total' => !empty($post['order_item_total']) ? intval($post['order_item_total']) : null,
                                            // 'order_item_note' => !empty($post['order_item_note']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_user_id' => $session_user_id,
                                            // 'order_item_date' => !empty($post['order_item_date']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_date_created' => date("YmdHis"),
                                            // 'order_item_date_updated' => !empty($post['order_item_date_updated']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_flag' => 0,
                                            // 'order_item_product_price_id' => !empty($post['order_item_product_price_id']) ? $post['order_item_contact_id_2'] : null,
                                            // 'order_item_ppn' => !empty($post['order_item_ppn']) ? intval($post['order_item_ppn']) : null,
                                            'order_item_order_session' => $order_session,
                                            // 'order_item_session' => !empty($post['order_item_session']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                            // 'order_item_ref_price_id' => $order_ref_price_id,
                                            'order_item_ref_price_sort' => !empty($post['order_item_ref_price_sort']) ? intval($post['order_item_ref_price_sort']) : null,                                            
                                            'order_item_flag_checkin' => 0,
                                        );                      
        
                                        if($post['order_ref_price_id'] == "1"){ // Old 0
                                            // $params_items['order_item_start_hour'] = '14:00:00';
                                            $params_items['order_item_type_2'] = 'Bulanan';
                                            $set_start_hour = '14:00:00';
                                            $set_end_hour = '12:00:00';                                        
                                        }else {
                                            $params_items['order_item_type_2'] = 'Transit';                                        
                                            // $params_items['order_item_start_hour'] = $post['order_start_hour'];

                                            if($post['order_ref_price_id'] == "1"){ // Old 1
                                                // Harian
                                                // $set_start_hour = '14:00:00';
                                                // $set_end_hour = '12:00:00';

                                                // $start_minute
                                                // $end_minute 

                                                $set_start_hour = $post['order_start_hour'].':'.$start_minute.":00";
                                                $set_end_hour = $post['order_end_hour'].':'.$end_minute .":00";                                                                                          
                                            }else{
                                                // Midnight, 4 Jam, 2 Jam
                                                $set_start_hour = $post['order_start_hour'].':'.$start_minute.":00";
                                                $set_end_hour = $post['order_end_hour'].':'.$end_minute.":00";                                       
                                            }                                                                                 
                                        }

                                        $params_items['order_item_start_date'] = $post['order_start_date'] .' '.$set_start_hour; 
                                        $params_items['order_item_end_date'] = $post['order_end_date'] .' '.$set_end_hour;

                                        // var_dump($params_items);die;          
                                        $create_item = $this->Front_model->add_booking_item($params_items);
                                        //End Save Item                                    
                                        $return->status  = 1;
                                        $return->message = 'Berhasil menambahkan '.$order_number;
                                        $return->result= array(
                                            'order_id' => $create,
                                            'order_number' => $get_booking['order_number'],
                                            'order_session' => $get_booking['order_session'],
                                            'order_total' => $get_booking['order_total'],
                                            'order_date' => $get_booking['order_date'],
                                            'contact_name' => $get_booking['order_contact_name'],
                                            'contact_phone' => $get_booking['order_contact_phone'],
                                            'order_item' => $this->Front_model->get_booking_item_custom(['order_item_order_id' => $create])
                                        );                                
                                    }else{
                                        $return->message = 'Gagal menambahkan '.$post['order_number'];
                                    }
                                }else{
                                    $return->message = 'Data sudah ada';
                                }  
                            }else{
                                $return->message = $message;
                            }                       
                        }
                    }
                    break;
                case "create_update_cece":
                    $this->form_validation->set_rules('order_type', 'Type', 'required');
                    $this->form_validation->set_rules('order_ref_id', 'Jenis Kamar', 'greater_than[0]');
                    $this->form_validation->set_rules('upload_1', 'Foto Bukti Transfer', 'required');   
                    $this->form_validation->set_rules('upload_2', 'Foto KTP', 'required');             
                    $this->form_validation->set_rules('upload_3', 'Foto Plat', 'required');                                        
                    $this->form_validation->set_rules('paid_total', 'Jumlah Pembayaran', 'required');    
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    $this->form_validation->set_message('greater_than', '{field} wajib dipilih');                    
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        if( (!empty($post['order_id'])) && (strlen($post['order_id']) > 0) ){ /* Update if Exist */ // if( (!empty($post['order_session'])) && (strlen($post['order_session']) > 10) ){ /* Update if Exist */      

                            $return->message = 'Update tidak tersedia';
                            echo json_encode($return);
                            return;

                            /* Check Existing Data */
                            $params_check = [
                                'order_number' => !empty($post['order_number']) ? $post['order_number'] : null
                            ];

                            $where_not = [
                                'order_id' => intval($post['order_id']),                   
                            ];                            
                            $where_new = [
                                'order_number' => 'Sel',
                                'order_flag' => 1
                            ];
                            $check_exists = $this->Front_model->check_data_exist_two_condition($where_not,$where_new);

                            /* Continue Update if not exist */
                            if(!$check_exists){
                                $params = array(
                                    'order_name' => !empty($post['order_name']) ? $post['order_name'] : null,
                                    'order_flag' => !empty($post['order_flag']) ? $post['order_flag'] : 0
                                );
                                $create = $this->Front_model->add_booking($params);   
                                if($create){
                                    $get_booking = $this->Front_model->get_booking($create);
                
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$post['order_name'];
                                    $return->result= array(
                                        'order_id' => $create,
                                        'order_name' => $get_booking['order_name'],
                                        'order_session' => $get_booking['order_session']
                                    );                                
                                }else{
                                    $return->message = 'Gagal menambahkan '.$post['order_name'];
                                }
                            }else{
                                $return->message = 'Data sudah digunakan';
                            }
                        }else{ /* Save New Data */
                            $next = true; $message = '';

                            //Check Ref Price ID
                            /*
                                $start_date = $post['order_start_date'];
                                $end_date   = $post['order_end_date'];                            
                                if($post['order_ref_price_id'] == 0){ //Bulanan

                                }else if($post['order_ref_price_id'] == 1){ //harian
                                    
                                }else if($post['order_ref_price_id'] == 2){ //midnight
                                    $start_date  = $start_date.$post['order_start_hour'].':00';
                                    $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                    $check_date  = $this->hour_diff($start_date,$end_date);
                                    if(intval($check_date) < 0){
                                        $message = 'Jam tidak boleh mundur';
                                        $next = false;
                                    }else if(intval($check_date) > 4){
                                        $message = 'Tidak boleh lebih dari 4 jam';
                                        $next = false;
                                    }                                
                                }else if($post['order_ref_price_id'] == 3){ //4jam
                                    $start_date  = $start_date.$post['order_start_hour'].':00';
                                    $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                    $check_date  = $this->hour_diff($start_date,$end_date);
                                    if(intval($check_date) < 0){
                                        $message = 'Jam tidak boleh mundur';
                                        $next = false;
                                    }else if(intval($check_date) > 4){
                                        $message = 'Tidak boleh lebih dari 4 jam';
                                        $next = false;
                                    }
                                }else if($post['order_ref_price_id'] == 4){ //2jam
                                    $start_date  = $start_date.$post['order_start_hour'].':00';
                                    $end_date    = $end_date.$post['order_end_hour'].':00';                                
                                    $check_date  = $this->hour_diff($start_date,$end_date);
                                    if(intval($check_date) < 0){
                                        $message = 'Jam tidak boleh mundur';
                                        $next = false;
                                    }else if(intval($check_date) > 2){
                                        $message = 'Tidak boleh lebih dari 2 jam';
                                        $next = false;
                                    }
                                }
                                var_dump($message);die;
                            */

                            // $check_date_is_past =$this->day_diff(date("Y-m-d H:i:s"), $post['order_start_date']." ".$post['order_start_hour'].":00");
                            $check_date_is_past =$this->time_diff(date("Y-m-d H:i:s"),$post['order_start_date']." ".$post['order_start_hour'].":00");                           
                            if(intval($check_date_is_past) < 0){
                                $next = false;
                                $message = 'Waktu Mulai tidak boleh mundur';
                            }
                            // var_dump(intval($check_date_is_past),$next);
                            if($next){
                                $check_date_is_past2 =$this->day_diff($post['order_start_date']." 00:00:00", $post['order_end_date']." 00:00:00");
                                $check_date_is_past2 =$this->time_diff($post['order_start_date']." 00:00:00", $post['order_end_date']." 00:00:00");                                
                                if(intval($check_date_is_past2) < 0){
                                    $next = false;
                                    $message = 'Waktu Akhir sudah lewat';
                                }         
                            }
                            
                            if($session_user_group_id < 3){
                                $next=true;
                            }
                            // var_dump(date("Y-m-d H:i:s"),$post['order_start_date']." ".$post['order_start_hour'].":00");
                            // var_dump($next);die;
                            if($next){
                                $room_id = !empty($post['order_product_id']) ? $post['order_product_id'] : null;
                                // $sdate = $post['order_start_date']." 00:00:00";
                                // $edate = $post['order_end_date']." 23:59:59";
                                $sdate = $post['order_start_date']." ".$post['order_start_hour'].":00";
                                $edate = $post['order_end_date']." ".$post['order_end_hour'].":00";                                
                                // $check_room_available = $this->Front_model->get_room_available_count($room_id,$sdate,$edate);
                                $check_room_available = $this->Front_model->sp_room_check($room_id,$sdate,$edate);
                                // var_dump($check_room_available['room_is_available'],$room_id,$sdate,$edate);die;
                                if(intval($check_room_available['room_is_available']) > 0){
                                    $get_product = $this->Produk_model->get_produk_quick($post['order_product_id']);

                                    $next = false;
                                    $message = $get_product['product_name'].' tidak tersedia';
                                }
                            }

                            if($next){
                                /* Check Existing Data */
                                $params_check = [
                                    'order_item_type_2' => !empty($post['order_type_2']) ? $post['order_type_2'] : null,
                                    'order_item_ref_id' => !empty($post['order_ref_id']) ? $post['order_ref_id'] : null ,
                                    'order_item_start_date' => $post['order_start_date'],                               
                                ];
                                // var_dump($params_check);die;
                                $check_exists = $this->Front_model->check_data_exist_items($params_check);

                                /* Continue Save if not exist */
                                if(!$check_exists){
                                    $get_set_branch = $this->Produk_model->get_produk_quick($post['order_product_id']);
                                    $get_product_branch = $get_set_branch['product_branch_id'];

                                    $order_session = $this->random_session(20);
                                    $order_number  = $this->request_number_for_booking($post['order_type'],$get_product_branch);
                                    $params = array(
                                        'order_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                        'order_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                        'order_number' => $order_number,
                                        'order_session' => $order_session,
                                        // 'order_date_due' => !empty($post['order_date_due']) ? $post['order_files_count'] : null,
                                        // 'order_contact_id' => !empty($post['order_contact_id']) ? $post['order_files_count'] : null,
                                        // 'order_contact_id_2' => !empty($post['order_contact_id_2']) ? $post['order_files_count'] : null,
                                        // 'order_ppn' => !empty($post['order_ppn']) ? $post['order_files_count'] : null,
                                        // 'order_total_dpp' => !empty($post['order_total_dpp']) ? intval($post['order_total_dpp']) : null,
                                        // 'order_discount' => !empty($post['order_discount']) ? intval($post['order_discount']) : null,
                                        // 'order_voucher' => !empty($post['order_voucher']) ? intval($post['order_voucher']) : null,
                                        // 'order_with_dp' => !empty($post['order_with_dp']) ? intval($post['order_with_dp']) : null,
                                        // 'order_total' => !empty($post['order_total']) ? intval($post['order_total']) : null,
                                        // 'order_with_dp_account' => !empty($post['order_with_dp_account']) ? $post['order_files_count'] : null,
                                        // 'order_note' => !empty($post['order_note']) ? $post['order_files_count'] : null,
                                        'order_user_id' => $session_user_id,
                                        'order_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                        'order_date' => date("YmdHis"),
                                        'order_date_created' => date("YmdHis"),
                                        // 'order_date_updated' => !empty($post['order_date_updated']) ? $post['order_files_count'] : null,
                                        'order_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                                        // 'order_trans_id' => !empty($post['order_trans_id']) ? $post['order_files_count'] : null,
                                        // 'order_ref_number' => !empty($post['order_ref_number']) ? $post['order_files_count'] : null,
                                        // 'order_approval_flag' => !empty($post['order_approval_flag']) ? intval($post['order_approval_flag']) : null,
                                        // 'order_label' => !empty($post['order_label']) ? $post['order_files_count'] : null,
                                        // 'order_sales_id' => !empty($post['order_sales_id']) ? $post['order_files_count'] : null,
                                        // 'order_sales_name' => !empty($post['order_sales_name']) ? $post['order_files_count'] : null,
                                        'order_contact_code' => !empty($post['order_contact_code']) ? str_replace(' ','',$post['order_contact_code']) : null,
                                        'order_contact_name' => !empty($post['order_contact_name']) ? $post['order_contact_name'] : null,
                                        'order_contact_phone' => !empty($post['order_contact_phone']) ? $this->contact_number($post['order_contact_phone']) : null,
                                        'order_vehicle_plate_number' => !empty($post['order_vehicle_plate_number']) ? $post['order_vehicle_plate_number'] : null,    
                                        'order_vehicle_count' => !empty($post['order_vehicle_count']) ? $post['order_vehicle_count'] : '0',    
                                        'order_vehicle_cost' => !empty($post['order_vehicle_cost']) ? $post['order_vehicle_cost'] : '0',                                                                                                                        
                                    );
                                    
                                    $create = $this->Front_model->add_booking($params);   
                                    // $create = true;
                                    if($create){
                                        $get_booking = $this->Front_model->get_booking($create);
                                        
                                        //Croppie Upload Image [Bukti Bayar]
                                        $set_paid_url = null;
                                        $set_paid_name = null;
                                        $post_upload = !empty($this->input->post('upload_1')) ? $this->input->post('upload_1') : "";
                                        if(strlen($post_upload) > 10){
                                            // $upload_process = $this->file_upload_image($this->folder_upload_file,$post_upload);
                                            $upload_process = upload_file_base64($this->folder_upload_file,$post_upload);                                            
                                            if($upload_process['status'] == 1){
                                                if ($get_booking && $get_booking['order_id']) {
                                                    // $params_image = array(
                                                    //     'product_image' => $upload_process->result['file_location']
                                                    // );
                                                    // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                                    $file_session = $this->random_session(20);
                                                    $params_image = array(
                                                        'file_type' => 1,
                                                        'file_from_table' => 'orders',
                                                        'file_from_id' => $get_booking['order_id'],
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_name' => 'Bukti Bayar - '.$upload_process['result']['file_name'],
                                                        'file_format' => str_replace(".","",$upload_process['result']['file_ext']),
                                                        'file_url' => $upload_process['result']['file_location'],
                                                        'file_size' => $upload_process['result']['file_size'],
                                                        'file_note' => 'Bukti Bayar'
                                                    );                                                    
                                                    $stat = $this->File_model->add_file($params_image);
                                                    $set_paid_url = $upload_process['result']['file_location'];
                                                    $set_paid_name = $upload_process['result']['file_name'] . $upload_process['result']['file_ext'];
                                                }
                                            }else{
                                                $return->message = 'Fungsi Gambar gagal';
                                            }
                                        }
                                        //End of Croppie   

                                        //Croppie Upload Image [KTP]
                                        $post_upload = !empty($this->input->post('upload_2')) ? $this->input->post('upload_2') : "";
                                        if(strlen($post_upload) > 10){
                                            // $upload_process = $this->file_upload_image($this->folder_upload_file,$post_upload);
                                            $upload_process = upload_file_base64($this->folder_upload_file,$post_upload);                                               
                                            if($upload_process['status'] == 1){
                                                if ($get_booking && $get_booking['order_id']) {
                                                    // $params_image = array(
                                                    //     'product_image' => $upload_process->result['file_location']
                                                    // );
                                                    // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                                    $file_session = $this->random_session(20);
                                                    $params_image = array(
                                                        'file_type' => 1,
                                                        'file_from_table' => 'orders',
                                                        'file_from_id' => $get_booking['order_id'],
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_name' => 'KTP - '.$upload_process['result']['file_name'],                                                        
                                                        'file_format' => str_replace(".","",$upload_process['result']['file_ext']),
                                                        'file_url' => $upload_process['result']['file_location'],
                                                        'file_size' => $upload_process['result']['file_size'],
                                                        'file_note' => 'KTP'
                                                    );                                                    
                                                    $stat = $this->File_model->add_file($params_image);
                                                }
                                            }else{
                                                $return->message = 'Fungsi Gambar gagal';
                                            }
                                        }
                                        //End of Croppie    
                                        
                                        //Croppie Upload Image [Plat]
                                        $post_upload = !empty($this->input->post('upload_3')) ? $this->input->post('upload_3') : "";
                                        if(strlen($post_upload) > 10){
                                            // $upload_process = $this->file_upload_image($this->folder_upload_file,$post_upload);
                                            $upload_process = upload_file_base64($this->folder_upload_file,$post_upload);                                                      
                                            if($upload_process['status'] == 1){
                                                if ($get_booking && $get_booking['order_id']) {
                                                    // $params_image = array(
                                                    //     'product_image' => $upload_process->result['file_location']
                                                    // );
                                                    // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                                    $file_session = $this->random_session(20);
                                                    $params_image = array(
                                                        'file_type' => 1,
                                                        'file_from_table' => 'orders',
                                                        'file_from_id' => $get_booking['order_id'],
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_name' => 'Form Sewa - '.$upload_process['result']['file_name'],                                        
                                                        'file_format' => str_replace(".","",$upload_process['result']['file_ext']),
                                                        'file_url' => $upload_process['result']['file_location'],
                                                        'file_size' => $upload_process['result']['file_size'],
                                                        'file_note' => 'Form Sewa'
                                                    );
                                                    $stat = $this->File_model->add_file($params_image);
                                                }
                                            }else{
                                                $return->message = 'Fungsi Gambar gagal';
                                            }
                                        }
                                        //End of Croppie                                            
                                        
                                        //Set Paid
                                        if($post['paid_total'] > 0){
                                            $file_session = $this->random_session(20);
                                            $paid_number = $this->request_number_for_order_paid();
                                            $params = array(
                                                // 'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                                                'paid_order_id' => $get_booking['order_id'],
                                                'paid_number' => $paid_number,
                                                'paid_session' => $file_session,
                                                'paid_date_created' => date("YmdHis"),
                                                'paid_date' => date("YmdHis"),                                    
                                                'paid_user_id' => $session_user_id,
                                                'paid_url' => $set_paid_url,
                                                'paid_name' => $set_paid_name,
                                                'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_payment_method'] : null,
                                                'paid_total' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : null                           
                                            );
                                            $save_data = $this->Front_model->add_paid($params);
                                        }
                                        //End Set Paid

                                        $params_price = array(
                                            'price_ref_id' => $post['order_ref_id'],
                                            'price_sort' => $post['order_ref_price_id']
                                        );
                                        $get_price = $this->Ref_model->get_ref_price_custom($params_price);
                                        $order_ref_price_id = $get_price['price_id'];
                                        
                                        //Save Item
                                        $params_items = array(
                                            'order_item_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                            'order_item_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                            'order_item_type_name' => !empty($post['order_item_type_name']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_order_id' => $create,
                                            'order_item_product_id' => !empty($post['order_product_id']) ? $post['order_product_id'] : null,
                                            'order_item_qty' => !empty($post['order_item_qty']) ? intval($post['order_item_qty']) : 1,
                                            // 'order_item_unit' => !empty($post['order_item_unit']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_price' => !empty($post['order_price']) ? str_replace(",","",$post['order_price']) : "0.00",
                                            // 'order_item_total' => !empty($post['order_item_total']) ? intval($post['order_item_total']) : null,
                                            // 'order_item_note' => !empty($post['order_item_note']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_user_id' => $session_user_id,
                                            // 'order_item_date' => !empty($post['order_item_date']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_date_created' => date("YmdHis"),
                                            // 'order_item_date_updated' => !empty($post['order_item_date_updated']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_flag' => 0,
                                            // 'order_item_product_price_id' => !empty($post['order_item_product_price_id']) ? $post['order_item_contact_id_2'] : null,
                                            // 'order_item_ppn' => !empty($post['order_item_ppn']) ? intval($post['order_item_ppn']) : null,
                                            'order_item_order_session' => $order_session,
                                            // 'order_item_session' => !empty($post['order_item_session']) ? $post['order_item_contact_id_2'] : null,
                                            'order_item_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                            'order_item_ref_price_id' => $order_ref_price_id,
                                            'order_item_ref_price_sort' => !empty($post['order_item_ref_price_sort']) ? intval($post['order_item_ref_price_sort']) : null,                                            
                                            'order_item_flag_checkin' => 0,
                                        );                      
        
                                        if($post['order_ref_price_id'] == "1"){ // Old 0
                                            // $params_items['order_item_start_hour'] = '14:00:00';
                                            $params_items['order_item_type_2'] = 'Bulanan';
                                            $set_start_hour = '00:00:00';
                                            $set_end_hour = '23:59:00';                                        
                                        }else {
                                            $params_items['order_item_type_2'] = 'Transit';                                        
                                            // $params_items['order_item_start_hour'] = $post['order_start_hour'];

                                            if($post['order_ref_price_id'] == "1"){ // Old 1
                                                // Harian
                                                $set_start_hour = '14:00:00';
                                                $set_end_hour = '12:00:00';                                                
                                            }else{
                                                // Midnight, 4 Jam, 2 Jam
                                                $set_start_hour = $post['order_start_hour'].':00';
                                                $set_end_hour = $post['order_end_hour'].':00';                                       
                                            }                                                                                 
                                        }

                                        $params_items['order_item_start_date'] = $post['order_start_date'] .' '.$set_start_hour; 
                                        $params_items['order_item_end_date'] = $post['order_end_date'] .' '.$set_end_hour;

                                        // var_dump($params_items);die;          
                                        $create_item = $this->Front_model->add_booking_item($params_items);
                                        //End Save Item                                    
                                        $return->status  = 1;
                                        $return->message = 'Berhasil menambahkan '.$order_number;
                                        $return->result= array(
                                            'order_id' => $create,
                                            'order_number' => $get_booking['order_number'],
                                            'order_session' => $get_booking['order_session'],
                                            'order_total' => $get_booking['order_total'],
                                            'order_date' => $get_booking['order_date'],
                                            'contact_name' => $get_booking['order_contact_name'],
                                            'contact_phone' => $get_booking['order_contact_phone'],
                                            'order_item' => $this->Front_model->get_booking_item_custom(['order_item_order_id' => $create])
                                        );                                
                                    }else{
                                        $return->message = 'Gagal menambahkan '.$post['order_number'];
                                    }
                                }else{
                                    $return->message = 'Data sudah ada';
                                }  
                            }else{
                                $return->message = $message;
                            }                       
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $order_id   = !empty($post['order_id']) ? $post['order_id'] : 0;
                        if(intval(strlen($order_id)) > 0){        
                            $datas = $this->Front_model->get_booking($order_id);
                            if($datas){
                                $datas_items = $this->Front_model->get_booking_item_custom(['order_item_order_id' => $order_id]);
                                $return->status=1;
                                $return->message='Berhasil mendapatkan data';
                                $return->result=$datas;
                                $return->result_item=$datas_items;
                                $return->rebooking_date_cece = array(
                                    'previous_start' => $datas_items['order_item_start_date'],
                                    'previous_end' => $datas_items['order_item_end_date'],                                    
                                    'rebooking_start' => date('Y-m-d 00:00:00', strtotime('+1 days',strtotime($datas_items['order_item_end_date']))),
                                    'rebooking_end' => date('Y-m-d 23:59:59', strtotime('+30 days',strtotime($datas_items['order_item_end_date']))),
                                );
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    break;
                case "update": die; //Not Used
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id = !empty($post['order_id']) ? $post['order_id'] : $post['order_id'];
                        $order_name = !empty($post['order_name']) ? $post['order_name'] : $post['order_name'];
                        $order_flag = !empty($post['order_flag']) ? $post['order_flag'] : $post['order_flag'];

                        if(strlen($order_id) > 1){
                            $params = array(
                                'order_name' => $order_name,
                                'order_date_updated' => date("YmdHis"),
                                'order_flag' => $order_flag
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Front_model->update_booking($order_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Front_model->get_booking($order_id);
                                    
                                //Update Image if Exist
                                $post_files = !empty($_FILES) ? $_FILES['files'] : "";
                                if(!empty($post_files)){
                                    $config['image_library'] = 'gd2';
                                    $config['upload_path'] = $upload_path_directory;
                                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                                    $this->upload->initialize($config);
                                    if ($this->upload->do_upload('files')) {
                                        $upload = $this->upload->data();
                                        $raw_photo = time() . $upload['file_ext'];
                                        $old_name = $upload['full_path'];
                                        $new_name = $upload_path_directory . $raw_photo;
                                        if (rename($old_name, $new_name)) {
                                            $compress['image_library'] = 'gd2';
                                            $compress['source_image'] = $upload_path_directory . $raw_photo;
                                            $compress['create_thumb'] = FALSE;
                                            $compress['maintain_ratio'] = TRUE;
                                            $compress['width'] = $this->image_width;
                                            $compress['height'] = $this->image_height;
                                            $compress['new_image'] = $upload_path_directory . $raw_photo;
                                            $this->load->library('image_lib', $compress);
                                            $this->image_lib->resize();
                                            if ($get_data) {
                                                $params_image = array(
                                                    'order_image' => base_url($upload_directory) . $raw_photo
                                                );
                                                if (!empty($get_data['order_image'])) {
                                                    $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                                    if (file_exists($file)) {
                                                        unlink($file);
                                                    }
                                                }
                                                $stat = $this->Front_model->update_order_custom(array('order_id' => $order_id), $params_image);
                                            }
                                        }
                                    }
                                }
                                //End of Save Image

                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$order_name;
                            }else{
                                $return->message='Gagal memperbarui '.$order_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id   = !empty($post['order_id']) ? $post['order_id'] : 0;
                        $order_item_id   = !empty($post['order_item_id']) ? $post['order_item_id'] : 0;                        
                        $order_name = !empty($post['order_name']) ? $post['order_name'] : null;

                        if(strlen($order_id) > 0){
                            $where = array(
                                'order_item_id' => $order_item_id,
                                'order_item_order_id' => $order_id
                            );
                            $get_data=$this->Front_model->get_booking_previous_custom($where);
                            // var_dump($get_data['order_parent_id']);

                            //Do CheckIn Previous
                            $params_checkout = array(
                                'order_item_flag_checkin'=> 1,
                            );
                            // var_dump($get_data['order_item_parent_id']);die;
                            $this->Front_model->update_booking_item(intval($get_data['order_item_parent_id']),$params_checkout);
                          

                            $set_data = $this->Front_model->delete_booking($order_id);
                            // $set_data = $this->Front_model->delete_booking_custom(array('order_id'=>$order_id),array('order_flag'=>4));       
                            $set_data = $this->Front_model->delete_booking_item_custom(['order_item_order_id'=> $order_id]);         
                            $set_data = $this->Front_model->delete_paid_custom(['paid_order_id'=>$order_id]);

                            if($set_data){
                                $get_file = $this->File_model->get_all_file(['file_from_table'=>'orders','file_from_id'=>$order_id],null,null,null,'file_id','asc');                                
                                foreach($get_file as $v){
                                    if (!empty($v['file_url'])) {
                                        if (file_exists(FCPATH . $v['file_url'])) {
                                            unlink(FCPATH . $v['file_url']);
                                        }
                                    }
                                    $this->File_model->delete_file($v['file_id']);
                                }                                
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$order_name;
                            }else{
                                $return->message='Gagal menghapus '.$order_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_rules('order_flag', 'order_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id = !empty($post['order_id']) ? $post['order_id'] : 0;
                        if(intval($order_id) > 1){
                            
                            $params = array(
                                'order_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                            );
                            
                            $where = array(
                                'order_id' => !empty($post['order_id']) ? intval($post['order_id']) : 0,
                            );
                            
                            if($post['order_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['order_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['order_flag']== 4){
                                $set_msg = 'membatalkan';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $get_data = $this->Front_model->get_booking_custom($where);
                            if($get_data){
                                $set_update=$this->Front_model->update_booking_custom($where,$params);
                                if($set_update){
                                    $params = array(
                                        'order_item_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                                        'order_item_flag_checkin' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                                    );
                                    
                                    $where = array(
                                        'order_item_order_id' => !empty($post['order_id']) ? intval($post['order_id']) : 0,
                                    );                                    
                                    $set_update=$this->Front_model->update_booking_item_custom($where,$params);                                    
                                    if($post['order_flag'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['order_number'];
                                }else{
                                    $return->message='Gagal '.$set_msg;
                                }
                            }else{
                                $return->message='Gagal mendapatkan data';
                            }   
                        }else{
                            $return->message = 'Tidak ada data';
                        } 
                    }
                    break;
                case "update_flag_item": //Old Booking
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_rules('order_item_id', 'order_item_id', 'required');
                    $this->form_validation->set_rules('order_item_flag_checkin', 'order_item_flag_checkin', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_item_id = !empty($post['order_item_id']) ? $post['order_item_id'] : 0;
                        if(intval($order_item_id) > 0){
                            
                            $params = array(
                                'order_item_flag_checkin' => !empty($post['order_item_flag_checkin']) ? intval($post['order_item_flag_checkin']) : 0,
                            );
                            
                            $where = array(
                                'order_item_id' => !empty($post['order_item_id']) ? intval($post['order_item_id']) : 0,
                            );
                            
                            $get_data = $this->Front_model->get_booking_item_custom($where);

                            if($post['order_item_flag_checkin']== 0){
                                $set_msg = 'waiting';
                            }else if($post['order_item_flag_checkin']== 1){
                                $get_product_name = $this->Produk_model->get_produk_quick($post['product_id']);                                
                                $set_msg = 'checkin '.$get_product_name['product_name'];
                                $params['order_item_product_id'] = $post['product_id'];
                            }else if($post['order_item_flag_checkin']== 2){
                                $set_msg = 'checkout '.$post['product_name'];
                            }else if($post['order_item_flag_checkin']== 4){
                                $set_msg = 'batal';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            // if($post['order_flag'] == 4){
                            //     $params['order_url'] = null;
                            // }


                            if($get_data){
                                $set_update=$this->Front_model->update_booking_item_custom($where,$params);
                                if($set_update){
                                    if($post['order_item_flag_checkin'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg;
                                }else{
                                    $return->message='Gagal '.$set_msg;
                                }
                            }else{
                                $return->message='Gagal mendapatkan data';
                            }   
                        }else{
                            $return->message = 'Tidak ada data';
                        } 
                    }
                    break;
                case "update_flag_item_lily": //Cece & Lily deposit
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_rules('order_item_id', 'order_item_id', 'required');
                    $this->form_validation->set_rules('order_item_flag_checkin', 'order_item_flag_checkin', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $next = true;
                        $set_msg = '';
                        $order_item_id = !empty($post['order_item_id']) ? $post['order_item_id'] : 0;
                        if(intval($order_item_id) > 0){
                            
                            $params = array(
                                'order_item_flag_checkin' => !empty($post['order_item_flag_checkin']) ? intval($post['order_item_flag_checkin']) : 0,
                            );
                            
                            $where = array(
                                'order_item_id' => !empty($post['order_item_id']) ? intval($post['order_item_id']) : 0,
                            );
                            
                            $get_data = $this->Front_model->get_booking_item_custom($where);
                            if($post['order_item_flag_checkin']== 0){
                                $set_msg = 'waiting';
                            }else if($post['order_item_flag_checkin']== 1){
                                $now_date = date("Y-m-d H:i:s");
                                // $now_date       = '2024-03-07 13:00:00';
                                $start_date     = $get_data['order_item_start_date'];
                                $end_date       = $get_data['order_item_end_date'];

                                $hour_diff = hour_calculate($start_date,$now_date);
                                $day_diff = day_calculate($start_date,$now_date);

                                if($hour_diff < 0){ //Tgl Checkin Belum Mendekati Tgl Pesan
                                    $hour_diff = abs($hour_diff); //Pembulatan jam yg minus
                                    if(($hour_diff > 0) && ($hour_diff < $this->set_minimal_hour_to_checkin+1)){ //Kurang dari minimal set_jam
                                        $set_msg = 'Boleh checkin';
                                    }else{
                                        if($hour_diff == $this->set_minimal_hour_to_checkin){
                                            $set_msg = 'Boleh checkin';
                                        }else{
                                            if($hour_diff > 24){
                                                $set_msg = "Gagal, checkin ".abs($day_diff)." hari lagi ";
                                                $next=false;
                                            }else{
                                                $set_msg = "Gagal, checkin ".$hour_diff." jam lagi ";
                                                $next=false;                                                
                                            }
                                        }
                                    }
                                }else{ //Tgl Checkin melewati tanggal pesan
                                    
                                    $hour_diff = hour_calculate($end_date,$now_date);
                                    $day_diff = day_calculate($end_date,$now_date);
                                    // var_dump($hour_diff,$day_diff);die;
                                    if($hour_diff < 1){ //Boleh Checkin ?? minimal 1 jam setelah batas checkout
                                        $set_msg = 'Boleh checkin '.$hour_diff;
                                    }
                                    // else{
                                    //     $set_msg = "Tidak boleh checkin, sudah lewat dari tanggal checkout";
                                    //     $next = false;
                                    // }
                                }
                                // var_dump($now_date,$end_date,$set_msg);die;
                                $get_product_name = $this->Produk_model->get_produk_quick($post['product_id']);                                
                                $set_msg = 'checkin '.$get_product_name['product_name'].', '.$set_msg;
                                
                                $params['order_item_product_id'] = $post['product_id'];
                                $params['order_item_checkin_date'] = date("YmdHis");
                            }else if($post['order_item_flag_checkin']== 2){
                                $set_msg = 'checkout '.$post['product_name'];
                                $params['order_item_note'] = $post['file_note'];
                                $params['order_item_checkout_date'] = date("YmdHis");   
                                $params['order_item_expired_time'] = 0;
                            }else if($post['order_item_flag_checkin']== 4){
                                $set_msg = 'batal';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            if(!empty($_FILES['file_key'])){
                                if(intval($_FILES['file_key']['error']) == 1){
                                    $next = false;
                                    $set_msg = 'Foto kunci terlalu besar';
                                }
                            }

                            if(!empty($_FILES['file_deposit'])){
                                if(intval($_FILES['file_deposit']['error']) == 1){
                                    $next = false;
                                    $set_msg = 'Foto deposit terlalu besar';
                                }
                            }                            

                            // var_dump($_FILES['file_key']);die;
                            if($next){
                                if($get_data){
                                    $set_update=$this->Front_model->update_booking_item_custom($where,$params);
                                    if($set_update){
                                        if($post['order_item_flag_checkin'] == 2){ //Checkout
                                            
                                            if(!empty($_FILES['file_key'])){
                                                if(intval($_FILES['file_key']['size']) > 0){
                                                    //Save Data First
                                                    $file_session = $this->random_session(20);
                                                    $file_note = $get_data['product_name'].' Checkout';

                                                    $params = array(
                                                        'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : 'orders-checkouts',
                                                        'file_from_id' => !empty($post['order_id']) ? $post['order_id'] : null,
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_type' => 1,
                                                        'file_note' => !empty($post['file_note']) ? $file_note.' '.$post['file_note'] : $file_note,          
                                                        'file_name' => 'Kunci - '.date("YmdHis")
                                                    );
                                                    // var_dump($params);die;
                                                    $save_data = $this->File_model->add_file($params);

                                                    // Call Helper for upload
                                                    //Process for Upload
                                                    $image_config=array(
                                                        'compress' => 1,
                                                        'width'=>$this->image_width,
                                                        'height'=>$this->image_height
                                                    );                                                    
                                                    // $upload_helper = upload_file_key($this->folder_upload, $this->input->post('file_key'));
                                                    $upload_helper = upload_file_checkout($this->folder_upload, $_FILES['file_key'],$image_config);
                                                    if ($upload_helper['status'] == 1) {
                                                        // 'file_name' => $upload_helper['result']['file_old_name'],
                                                        $params_image = array(
                                                            'file_format' => $upload_helper['result']['file_ext'],
                                                            'file_url' => $upload_helper['result']['file_location'],
                                                            'file_size' => $upload_helper['result']['file_size']
                                                        );
                                                        $stat = $this->File_model->update_file($save_data, $params_image);
                                                        
                                                        $return->message    = $upload_helper['message'];
                                                        $return->status     = $upload_helper['status'];
                                                        $return->raw_file   = $upload_helper['result']['file_old_name'];
                                                        $return->return     = $upload_helper;                            
                                                    }else{
                                                        $return->message = 'Error: '.$upload_helper['message'];
                                                    }    
                                                }
                                            }

                                            if(!empty($_FILES['file_deposit'])){
                                                if(intval($_FILES['file_deposit']['size']) > 0){
                                                    //Save Data First
                                                    $file_session = $this->random_session(20);
                                                    $params = array(
                                                        'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : 'orders',
                                                        'file_from_id' => !empty($post['order_id']) ? $post['order_id'] : null,
                                                        'file_session' => $file_session,
                                                        'file_date_created' => date("YmdHis"),
                                                        'file_user_id' => $session_user_id,
                                                        'file_type' => 1,
                                                        'file_name' => 'Deposit - '.date("YmdHis")                                                                                    
                                                    );
                                                    $save_data = $this->File_model->add_file($params);

                                                    // Call Helper for upload
                                                    $upload_helper = upload_file_deposit($this->folder_upload, $this->input->post('file_deposit'));
                                                    if ($upload_helper['status'] == 1) {
                                                        $params_image = array(
                                                            // 'file_name' => $upload_helper['result']['client_name'],
                                                            'file_format' => str_replace(".","",$upload_helper['result']['file_ext']),
                                                            'file_url' => $upload_helper['file'],
                                                            'file_size' => $upload_helper['result']['file_size']
                                                        );
                                                        $stat = $this->File_model->update_file($save_data, $params_image);
                                                        
                                                        $return->message    = $upload_helper['message'];
                                                        $return->status     = $upload_helper['status'];
                                                        $return->raw_file   = $upload_helper['file'];
                                                        $return->return     = $upload_helper;                            
                                                    }else{
                                                        $return->message = 'Error: '.$upload_helper['message'];
                                                    }    
                                                }
                                            }                                        
                                        }
                                        if($post['order_item_flag_checkin'] == 4){
                                            /*
                                            $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                            if (file_exists($file)) {
                                                unlink($file);
                                            }
                                            */
                                        }
                                        $return->status  = 1;
                                        $return->message = 'Berhasil '.$set_msg;
                                    }else{
                                        $return->message='Gagal '.$set_msg;
                                    }
                                }else{
                                    $return->message='Gagal mendapatkan data';
                                }   
                            }else{
                                $return->message = 'Gagal, '.$set_msg;
                            }
                        }else{
                            $return->message = 'Tidak ada data';
                        } 
                    }
                    break;
                case "load_paid_history":
                    $this->form_validation->set_rules('paid_order_id', 'Paid Order ID', 'required');                                                                        
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $from_id = !empty($this->input->post('paid_order_id')) ? $this->input->post('paid_order_id') : null;
                        $params = array(
                            'paid_order_id' => $from_id
                        );
                        $search = null; $limit = null; $start = null; $order  = 'paid_id'; $dir = 'ASC';
                        $get_count = $this->Front_model->get_all_paid_count($params,$search);
                        if($get_count > 0){
                            $get_file = $this->Front_model->get_all_paid($params,$search,0,1,$order,$dir);
                            foreach($get_file as $v){
                                if($v['paid_format'] == 'pdf'){
                                    $set_color = 'color:white;background-color:#d96a00!important;';
                                }else if(($v['paid_format'] == 'jpg') or ($v['paid_format'] == 'png')){
                                    $set_color = 'color:white;background-color:#3f69c7!important;';
                                }else if(($v['paid_format'] == 'xls') or ($v['paid_format'] == 'xlsx')){
                                    $set_color = 'color:white;background-color:#0aa65e!important;';
                                }else if($v['paid_format'] == 'link'){
                                    $set_color = 'color:white;background-color:#4a4a4a!important;';
                                }else{
                                    $set_color = 'color:white;background-color:#0aa65e!important;';
                                }

                                // $paid_src = site_url() . $this->folder_upload . $v['paid_url'];
                                $paid_src = site_url() . $v['paid_url'];
                                // if($v['file_type'] == 2){
                                    // $file_src = $v['file_url'];
                                // }

                                $datas[] = array(
                                    'paid_id' => intval($v['paid_id']),
                                    'paid_order_id' => intval($v['paid_order_id']),                                    
                                    'paid_number' => $v['paid_number'],
                                    // 'file_from_id' => $v['file_from_id'],
                                    'paid_name' => $v['paid_name'],
                                    // 'file_url' => $v['file_url'],
                                    'paid_total' => $v['paid_total'],
                                    'paid_payment_type' => $v['paid_payment_type'],
                                    'paid_payment_method' => $v['paid_payment_method'],                                    
                                    'paid_session' => $v['paid_session'],
                                    'paid_flag' => intval($v['paid_flag']),
                                    'paid' => array(
                                        // 'name' => $v['file_name'],
                                        'src' => $paid_src,
                                        'format' => $v['paid_format'],
                                        'size' => $v['paid_size'],
                                        'size_unit' => $this->file_unit_size($v['paid_size']),                                        
                                        'format_label' => '<label class="label" style="'.$set_color.'">'.$v['paid_format'].'</label>'
                                    ),
                                    'order' => array(
                                        'order_id' => intval($v['order_id']),
                                        'order_session' => $v['order_session'],
                                        'order_number' => $v['order_number']
                                    ),     
                                    'date' => array(
                                        'time_ago' => $v['time_ago'],
                                        'date_created' => $v['paid_date_created'],
                                        'date' => $v['paid_date'],                                                                          
                                    ),    
                                    'user' => array(
                                        'id' => $v['user_id'],
                                        'user_name' => $v['user_username'],
                                        'full_name' => $v['user_fullname']                                                                                  
                                    ),
                                );                                
                            }
                            $return->status  = 1;
                            $return->message = 'Menemukan data pembayaran';
                            $return->result  = $datas;
                        }else{
                            $return->status  = 0;
                            $return->message = 'Data pembayaran kosong';
                            $return->result  = [];
                        }
                        $return->total_records = $get_count;
                    }
                    break;
                case "paid_rename":
                    $file_id = !empty($this->input->post('paid_id')) ? $this->input->post('paid_id') : null;
                    $file_name = !empty($this->input->post('paid_name')) ? $this->input->post('paid_name') : null;
                    if(intval($file_id) > 0){
                        $where = array(
                            'paid_id' => $file_id
                        );
                        $params = array(
                            'paid_name' => $file_name
                        );
                        $set_update=$this->Front_model->update_paid_custom($where,$params);
                        if($set_update==true){
                            $get_data = $this->Front_model->get_paid($file_id);
                            $return->status  = 1;
                            $return->message = 'Berhasil ganti nama';
                            $return->result  = $get_data;
                        }else{
                            $return->message='Gagal ganti nama';
                        }                        
                    }
                    $return->action=$action;                               
                    break;          
                case "paid_delete":
                    $file_id = !empty($this->input->post('paid_id')) ? $this->input->post('paid_id') : null;
                    $file_name = !empty($this->input->post('file_name')) ? $this->input->post('file_name') : null;                        

                    if(intval($file_id) > 0){
                        // $get_data=$this->Front_model->get_paid($file_id);
                        // $set_data=$this->Front_model->delete_paid($file_id);            
                        $set_data = true;    
                        if($set_data){    
                            // $file = FCPATH . $get_data['paid_url'];
                            // if (file_exists($file)) {
                            //     unlink($file);
                            // }
                            // $return->status=1;
                            // $return->message='Berhasil menghapus '. $get_data['paid_name'];
                            $return->status=1;
                            $return->message='Fitur tidak tersedia';
                        }else{
                            $return->message='Gagal menghapus '. $get_data['paid_name'];
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;                               
                    break;      
                case "paid_create":
                    $this->form_validation->set_rules('paid_order_id', 'ID Dokumen', 'required');                                                                                          
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                 
                        //Save File if Exist        
                        if(!empty($_FILES['source'])){
                            if(intval($_FILES['source']['size']) > 0){
                                //Save Data First
                                $file_session = $this->random_session(20);
                                $paid_number = $this->request_number_for_order_paid();
                                $params = array(
                                    // 'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                                    'paid_order_id' => !empty($post['paid_order_id']) ? $post['paid_order_id'] : null,
                                    'paid_number' => $paid_number,
                                    'paid_session' => $file_session,
                                    'paid_date_created' => date("YmdHis"),
                                    'paid_date' => date("YmdHis"),                                    
                                    'paid_user_id' => $session_user_id,
                                    'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_payment_method'] : null,
                                    'paid_total' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : null                           
                                );
                                $save_data = $this->Front_model->add_paid($params);

                                // Call Helper for upload
                                $upload_helper = upload_file($this->folder_upload, $this->input->post('source'));
                                if ($upload_helper['status'] == 1) {
                                    $params_image = array(
                                        'paid_name' => $upload_helper['file'],
                                        'paid_format' => str_replace(".","",$upload_helper['result']['file_ext']),
                                        'paid_url' => $this->folder_upload . $upload_helper['file'],
                                        'paid_size' => $upload_helper['result']['file_size']
                                    );
                                    /*
                                    if (!empty($data['file_url'])) {
                                        if (file_exists(FCPATH . $data['file_url'])) {
                                            unlink(FCPATH . $data['file_url']);
                                        }
                                    }
                                    */
                                    $stat = $this->Front_model->update_paid($save_data, $params_image);
                                    
                                    $return->message    = $upload_helper['message'];
                                    $return->status     = $upload_helper['status'];
                                    $return->raw_file   = $upload_helper['file'];
                                    $return->return     = $upload_helper;                            
                                }else{
                                    $return->message = 'Error: '.$upload_helper['message'];
                                }     
                            }else{                           
                                $return->message = 'File lebih dari '.($this->file_size_limit / 1048576) .' MB';
                            }
                        }else{
                            $return->message = 'File tidak terbaca';
                        }
                    }   
                    break;
                case "room_get": //Not Used
                    $params = array(
                        'product_type' => 2,
                        'product_branch_id' => intval($post['branch_id']),
                        'product_ref_id' => intval($post['ref_id']),                        
                        // 'product_category_id' => 2,                        
                        'product_flag' => 1,                        
                    );
                    // $params = null;
                    $search = null; $limit = null; $start = null; $order  = 'product_name'; $dir = 'ASC';
                    $get_ref = $this->Produk_model->get_all_produks($params,$search,$limit,$start,$order,$dir);
                    $return->result = $get_ref;
                    $return->status = 1;
                    $return->params = $params;
                    break;  
                case "room_ref":
                    $params = array(
                        'references.ref_type' => 10,
                        'references.ref_branch_id' => $post['branch_id'],
                        'references.ref_flag' => 1    
                    );
                    // $params = null;
                    $search = null; $limit = null; $start = null; $order  = 'ref_name'; $dir = 'ASC';
                    $get_ref = $this->Ref_model->get_all_ref($params,$search,$limit,$start,$order,$dir);
                    $return->result = $get_ref;
                    $return->status = 1;
                    break;                       
                case "room_price":
                    $params = array(
                        'price_ref_id' => $post['ref_id'],
                        'price_sort' => $post['ref_price_sort']                       
                    );
                    
                    // $params = null;
                    $search = null; $limit = null; $start = null; $order  = 'price_name'; $dir = 'ASC';
                    $get_ref = $this->Ref_model->get_ref_price_custom($params);
                    
                    $params = array(
                        'references.ref_id' => $post['ref_id']                    
                    );                    
                    $get_ref_2 = $this->Ref_model->get_ref_custom($params);

                    $params_rooms = array(
                        'product_type' => 2,
                        'product_branch_id' => intval($post['branch_id']), //5
                        'product_ref_id' => intval($post['ref_id']),    //520                     
                        // 'product_category_id' => 2,                        
                        'product_flag' => 1,                        
                    );                    
                    $search = null; $limit = null; $start = null; $order  = 'product_name'; $dir = 'ASC';
                    $get_room = $this->Produk_model->get_all_produks($params_rooms,$search,$limit,$start,$order,$dir);

                    // Search Configuration Price Monday - Sunday
                    $this_day_name = strtolower(date("l"));
                    $where_ref_price = [
                        'price_ref_id' => $post['ref_id'],
                        'price_sort' => $post['ref_price_sort']
                    ];
                    $get_ref_price = $this->Ref_model->get_ref_price_custom_json($where_ref_price,$this_day_name);
                    if(!empty($get_ref_price)){
                        $set_price = intval(str_replace('"','',$get_ref_price['result']));
                        $def = false;
                    }else{
                        //Search Default Price
                        if($post['ref_price_sort'] == 0){ //Not Used / Promo
                            $set_price = 0;
                        }else if($post['ref_price_sort'] == 1){ //Bulanan
                            $set_price = $get_ref_2['ref_price_1'];
                        }else if($post['ref_price_sort'] == 2){ //Harian
                            $set_price = $get_ref_2['ref_price_2'];
                        }else if($post['ref_price_sort'] == 3){ //Midnight
                            $set_price = $get_ref_2['ref_price_3'];
                        }else if($post['ref_price_sort'] == 4){ //4 Jam
                            $set_price = $get_ref_2['ref_price_4'];
                        }else if($post['ref_price_sort'] == 5){ //2 Jam
                            $set_price = $get_ref_2['ref_price_5'];
                        }else if($post['ref_price_sort'] == 6){ //3 Jam
                            $set_price = $get_ref_2['ref_price_6'];
                        }
                        $def = true;
                    }

                    $get_room_status = [];
                    if($post['ref_id'] > 0){
                        $branchs = !empty($this->input->post('branch_id')) ? $post['branch_id'] : null;
                        $refs = !empty($this->input->post('ref_id')) ? $post['ref_id'] : 0; 
                        $where_ref = '';           
                        if($refs > 0){
                            $where_ref = "AND ref_id=$refs";
                        }
                        $pre = "
                            SELECT product_id, product_branch_id, product_category_id, product_ref_id, product_name, product_flag, ref_id, ref_name, branch_id, branch_code, branch_name,
                            c.order_item_id, c.order_item_order_id, c.order_item_product_id, c.order_item_start_date, c.order_item_end_date, 
                            c.order_item_flag_checkin, c.order_item_checkin_date, c.order_item_checkout_date,
                            c.order_id, c.order_session, c.order_contact_name, c.order_contact_phone,
                            c.order_item_expired_day, c.order_item_expired_day_2, c.order_item_expired_time, c.order_item_expired_time_2,
                            c.order_item_ref_price_sort 
                            FROM products
                            LEFT JOIN `references` ON product_ref_id=ref_id
                            LEFT JOIN branchs ON product_branch_id=branch_id
                            LEFT OUTER JOIN (
                                SELECT order_item_id, order_item_order_id, order_item_product_id, order_item_start_date, order_item_end_date, 
                                order_item_flag_checkin, order_item_checkin_date, order_item_checkout_date, order_id, order_session, order_contact_name, order_contact_phone, 
                                order_item_expired_day, DATEDIFF(order_item_end_date, NOW()) AS order_item_expired_day_2, `order_item_expired_time`, 
                                TIMESTAMPDIFF(MINUTE, NOW(), order_item_end_date) AS order_item_expired_time_2, order_item_ref_price_sort                    
                                FROM orders_items 
                                LEFT JOIN orders ON order_item_order_id=order_id
                                WHERE order_item_flag_checkin = 1
                                GROUP BY order_item_product_id ORDER BY order_item_id DESC
                            ) AS c ON product_id=c.order_item_product_id
                            WHERE product_type=2 AND product_branch_id=$branchs AND product_flag=1 $where_ref ORDER BY `references`.`ref_name` ASC, product_name ASC
                        ";                    
                        $query = $this->db->query($pre);  
                        $get_room_status = $query->result_array();
                    }
                    
                    $return->status = 1;        
                    $return->params = $params;     

                    // $return->result = $get_ref; //Not Used
                    $return->result_ref = $get_ref_2;   // To Display Default Price 
                    $return->rooms = $get_room;         // To Display LOOP ROOM
                    $return->rooms_status = $get_room_status;
                    $return->set_pricing = 
                        [
                            "price_sort" => $post['ref_price_sort'],
                            "default_price" => $def,
                            "today" => $this_day_name,
                            "price" => $set_price
                        ];
                    break;    
                case "load_extend_lily_price":
                    $get = $this->Ref_model->get_ref_custom(['references.ref_id'=>$post['ref_id']]);
                    $get_item = $this->Ref_model->get_ref_price_custom(['price_ref_id' => $post['ref_id']]);                    

                    if($post['type'] == 2){ //Harian
                        $return->price = $get['ref_price_2'];
                    }else if($post['type'] == 4){ //4 Jam
                        $return->price = $get['ref_price_4'];
                    }else if($post['type'] == 5){ //2 Jam
                        $return->price = $get['ref_price_5'];
                    }

                    $return->result = $get;
                    $return->result_item = $get_item;                    
                    $return->status = 1;
                    break;
                case "load-order-items-for-report":
                    // $columns = array(
                    //     '0' => 'order_date',
                    //     '1' => 'order_number'
                    // );           
                    
                    // $limit     = !empty($post['length']) ? $post['length'] : 10;
                    // $start     = !empty($post['start']) ? $post['start'] : 0;
                    // $order     = !empty($post['order']) ? $columns[$post['order'][0]['column']] : $columns[0];
                    // $dir       = !empty($post['order'][0]['dir']) ? $post['order'][0]['dir'] : "asc";

                    $trans_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : 0;
                    $contact_id = !empty($this->input->post('kontak')) ? $this->input->post('kontak') : 0;
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $branch = !empty($this->input->post('branch')) ? $this->input->post('branch') : 0;           
                    $ouser = !empty($this->input->post('user')) ? $this->input->post('user') : 0;                                        
                    $product    = !empty($this->input->post('product')) ? $this->input->post('product') : 0;   
                    $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end   = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));
                    $params = array(
                        'order_item_type' => intval($trans_type),
                        'order_date >' => $date_start,
                        'order_date <' => $date_end,                    
                        // 'order_item_branch_id' => intval($session_branch_id),
                    );
                    if(intval($branch) > 0){
                        $params['order_branch_id'] = $branch;
                    }
                    if(intval($ouser) > 0){
                        $params['order_user_id'] = $ouser;
                    }                    
                    // if(intval($product_id) > 0){
                    //     $params['order_item_product_id'] = $product_id;
                    // }

                    // if(intval($product) > 0){
                    //     $params['order_item_product_id'] = $product;
                    // }     
                    // $datas = $this->Order_model->get_all_order_items_report($params,null,1000,0,'order_item_date','asc');
                    $datas = $this->Front_model->get_all_booking_item($params,null,1000,0,'order_date','asc'); 
                    // var_dump($order,$dir);die;                   
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
                case "create_rebooking_cece":
                    $this->form_validation->set_rules('order_id', 'Type', 'required');
                    $this->form_validation->set_rules('order_item_id', 'Jenis Kamar', 'greater_than[0]');
                    $this->form_validation->set_rules('upload_1', 'Foto Bukti Bayar', 'required');   
                    $this->form_validation->set_rules('paid_total', 'Jumlah Pembayaran', 'required');    
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    $this->form_validation->set_message('greater_than', '{field} wajib dipilih');                    
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $next = true;
                        $order_id = intval($post['order_id']);
                        $order_item_id = intval($post['order_item_id']);                        

                        if(strlen($post['upload_1']) < 10){
                            $next = false;
                            $return->message = 'Bukti bayar wajib dipilih';
                        }

                        $check_date_is_past =$this->day_diff(date("Y-m-d H:i:s"), $post['order_start_date']." 14:00:00");
                        if(intval($check_date_is_past) < 0){
                            $next = false;
                            $message = 'Tanggal Mulai tidak boleh mundur';
                        }

                        if($next){
                            $check_date_is_past2 =$this->day_diff($post['order_start_date']." 14:00:00", $post['order_end_date']." 12:00:00");
                            if(intval($check_date_is_past2) < 0){
                                $next = false;
                                $message = 'Tanggal Akhir sudah lewat';
                            }         
                        }
                        
                        if($session_user_group_id < 3){
                            $next=true;
                        }

                        //Continue Save
                        if($next){
                            //Get Previous Order_item 
                            $get_previous = $this->Front_model->get_booking_previous_custom(['order_item_order_id' => $order_id]);
                            
                            $get_set_branch = $this->Produk_model->get_produk_quick($get_previous['order_item_product_id']);
                            $get_product_branch = $get_set_branch['product_branch_id'];
                            // var_dump($get_product_branch);die;
                            $set_order_session = $this->random_session(20);
                            $set_order_number  = $this->request_number_for_booking($this->booking_identity,$get_product_branch);

                            //Set Params
                            $params = array(
                                'order_branch_id' => $get_previous['order_branch_id'],
                                'order_type' => $this->booking_identity,
                                'order_number' => $set_order_number,
                                'order_session' => $set_order_session,
                                'order_total_dpp' => !empty($post['paid_total']) ? intval(str_replace(",","",$post['paid_total'])) : 0,
                                'order_total' => !empty($post['paid_total']) ? intval(str_replace(",","",$post['paid_total'])) : 0,
                                    // 'order_date_due' => !empty($post['order_date_due']) ? $post['order_files_count'] : null,
                                    // 'order_contact_id' => !empty($post['order_contact_id']) ? $post['order_files_count'] : null,
                                    // 'order_contact_id_2' => !empty($post['order_contact_id_2']) ? $post['order_files_count'] : null,
                                    // 'order_ppn' => !empty($post['order_ppn']) ? $post['order_files_count'] : null,
                                    // 'order_discount' => !empty($post['order_discount']) ? intval($post['order_discount']) : null,
                                    // 'order_voucher' => !empty($post['order_voucher']) ? intval($post['order_voucher']) : null,
                                    // 'order_with_dp' => !empty($post['order_with_dp']) ? intval($post['order_with_dp']) : null,
                                    // 'order_with_dp_account' => !empty($post['order_with_dp_account']) ? $post['order_files_count'] : null,
                                    // 'order_note' => !empty($post['order_note']) ? $post['order_files_count'] : null,
                                'order_user_id' => $session_user_id,
                                'order_ref_id' => $get_previous['order_item_ref_id'],
                                'order_date' => date("YmdHis"),
                                'order_date_created' => date("YmdHis"),
                                    // 'order_date_updated' => !empty($post['order_date_updated']) ? $post['order_files_count'] : null,
                                'order_flag' => $get_previous['order_flag'],
                                    // 'order_trans_id' => !empty($post['order_trans_id']) ? $post['order_files_count'] : null,
                                    // 'order_ref_number' => !empty($post['order_ref_number']) ? $post['order_files_count'] : null,
                                    // 'order_approval_flag' => !empty($post['order_approval_flag']) ? intval($post['order_approval_flag']) : null,
                                    // 'order_label' => !empty($post['order_label']) ? $post['order_files_count'] : null,
                                    // 'order_sales_id' => !empty($post['order_sales_id']) ? $post['order_files_count'] : null,
                                    // 'order_sales_name' => !empty($post['order_sales_name']) ? $post['order_files_count'] : null,
                                'order_contact_code' => !empty($post['order_contact_code']) ? str_replace(' ','',$post['order_contact_code']) : null,
                                'order_contact_name' => !empty($post['order_contact_name']) ? $post['order_contact_name'] : $get_previous['order_contact_name'],
                                'order_contact_phone' => !empty($post['order_contact_phone']) ? $this->contact_number($post['order_contact_phone']) : $post['order_contact_phone'],
                                // 'order_vehicle_plate_number' => !empty($post['order_vehicle_plate_number']) ? $post['order_vehicle_plate_number'] : null,    
                                // 'order_vehicle_count' => !empty($post['order_vehicle_count']) ? $post['order_vehicle_count'] : '0',    
                                // 'order_vehicle_cost' => !empty($post['order_vehicle_cost']) ? $post['order_vehicle_cost'] : '0',    
                                'order_parent_id' => intval($post['order_id'])                                                                                                                    
                            );                            

                            $create = $this->Front_model->add_booking($params);
                            
                            if($create){

                                $get_booking = $this->Front_model->get_booking($create);

                                //Croppie Upload Image [Bukti Bayar]
                                $set_paid_url = null;
                                $set_paid_name = null;
                                $post_upload = !empty($this->input->post('upload_1')) ? $this->input->post('upload_1') : "";
                                if(strlen($post_upload) > 10){
                                    // $upload_process = $this->file_upload_image($this->folder_upload_file,$post_upload);
                                    $upload_process = upload_file_base64($this->folder_upload_file,$post_upload);                                            
                                    if($upload_process['status'] == 1){
                                        if ($get_booking && $get_booking['order_id']) {
                                            // $params_image = array(
                                            //     'product_image' => $upload_process->result['file_location']
                                            // );
                                            // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                            $file_session = $this->random_session(20);
                                            $params_image = array(
                                                'file_type' => 1,
                                                'file_from_table' => 'orders',
                                                'file_from_id' => $get_booking['order_id'],
                                                'file_session' => $file_session,
                                                'file_date_created' => date("YmdHis"),
                                                'file_user_id' => $session_user_id,
                                                'file_name' => 'Bukti Bayar - '.$upload_process['result']['file_name'],
                                                'file_format' => str_replace(".","",$upload_process['result']['file_ext']),
                                                'file_url' => $upload_process['result']['file_location'],
                                                'file_size' => $upload_process['result']['file_size'],
                                                'file_note' => 'Bukti Bayar'
                                            );                                                    
                                            $stat = $this->File_model->add_file($params_image);
                                            $set_paid_url = $upload_process['result']['file_location'];
                                            $set_paid_name = $upload_process['result']['file_name'] . $upload_process['result']['file_ext'];
                                        }
                                    }else{
                                        $return->message = 'Fungsi Gambar gagal';
                                    }
                                }
                                //End of Croppie   

                                //Set Paid
                                if($post['paid_total'] > 0){
                                    $file_session = $this->random_session(20);
                                    $paid_number = $this->request_number_for_order_paid();
                                    $params = array(
                                        // 'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                                        'paid_order_id' => $get_booking['order_id'],
                                        'paid_number' => $paid_number,
                                        'paid_session' => $file_session,
                                        'paid_date_created' => date("YmdHis"),
                                        'paid_date' => date("YmdHis"),                                    
                                        'paid_user_id' => $session_user_id,
                                        'paid_url' => $set_paid_url,
                                        'paid_name' => $set_paid_name,
                                        'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_payment_method'] : null,
                                        'paid_total' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : null                           
                                    );
                                    $save_data = $this->Front_model->add_paid($params);
                                }
                                //End Set Paid

                                $params_price = array(
                                    'price_ref_id' => $get_previous['order_item_ref_id'],
                                    'price_sort' => $get_previous['order_item_ref_price_id']
                                );
                                $get_price = $this->Ref_model->get_ref_price_custom($params_price);
                                // $order_ref_price_id = $get_price['price_id'];                                

                                //Save Item
                                $params_items = array(
                                    'order_item_branch_id' => $get_previous['order_branch_id'],
                                    'order_item_type' => $this->booking_identity,
                                    // 'order_item_type_name' => '',
                                    'order_item_order_id' => $create,
                                    'order_item_product_id' => $get_previous['order_item_product_id'],
                                    'order_item_qty' => 1,
                                    'order_item_price' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : "0.00",
                                    'order_item_user_id' => $session_user_id,
                                    'order_item_date_created' => date("YmdHis"),
                                    'order_item_flag' => 0,
                                        // 'order_item_unit' => !empty($post['order_item_unit']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_total' => !empty($post['order_item_total']) ? intval($post['order_item_total']) : null,
                                        // 'order_item_note' => !empty($post['order_item_note']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_date' => !empty($post['order_item_date']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_date_updated' => !empty($post['order_item_date_updated']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_product_price_id' => !empty($post['order_item_product_price_id']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_ppn' => !empty($post['order_item_ppn']) ? intval($post['order_item_ppn']) : null,
                                        // 'order_item_session' => !empty($post['order_item_session']) ? $post['order_item_contact_id_2'] : null,
                                    'order_item_order_session' => $set_order_session,
                                    'order_item_ref_id' => $get_previous['order_item_ref_id'],
                                    'order_item_ref_price_id' => $get_previous['order_item_ref_price_id'],
                                    'order_item_ref_price_sort' => !empty($get_previous['order_item_ref_price_sort']) ? intval($get_previous['order_item_ref_price_sort']) : null,                                            
                                    'order_item_flag_checkin' => 1,
                                    'order_item_type_2' => 'Bulanan',
                                    'order_item_parent_id' => intval($post['order_item_id'])
                                );        
                                $params_items['order_item_start_date'] = $post['order_start_date'] .' 00:00:00'; 
                                $params_items['order_item_end_date'] = $post['order_end_date'] .' 23:59:59';   
                                $params_items['order_item_checkin_date'] = $post['order_start_date'] .' 00:00:00'; 
                                $params_items['order_item_checkout_date'] = $post['order_end_date'] .' 23:59:59';                                                                                              
                                $create_item = $this->Front_model->add_booking_item($params_items);

                                //Do Checkout Previous
                                $params_checkout = array(
                                    'order_item_flag_checkin'=> 2,
                                    'order_item_checkout_date' => date('Y-m-d 23:59:59', strtotime('-1 days',strtotime($post['order_start_date'] .' 00:00:00'))),
                                );
                                $this->Front_model->update_booking_item(intval($post['order_item_id']),$params_checkout);

                                $return->status  = 1;
                                $return->message = 'Berhasil menambahkan '.$set_order_number;
                                $return->result= array(
                                    'order_id' => $create,
                                    'order_number' => $get_booking['order_number'],
                                    'order_session' => $get_booking['order_session'],
                                    'order_date' => $get_booking['order_date'],
                                    'order_total' => $get_booking['order_total'],
                                    'contact_name' => $get_booking['order_contact_name'],
                                    'contact_phone' => $get_booking['order_contact_phone'],
                                    'order_item' => $this->Front_model->get_booking_item_custom(['order_item_order_id' => $create])
                                );                                      
                            }                                 
                        }

                        $return->next = $next;
                    }
                    break;
                case "create_rebooking_lily":
                    $this->form_validation->set_rules('order_id', 'Type', 'required');
                    $this->form_validation->set_rules('order_item_id', 'Jenis Kamar', 'greater_than[0]');
                    $this->form_validation->set_rules('upload_1', 'Foto Bukti Bayar', 'required');   
                    $this->form_validation->set_rules('paid_total', 'Jumlah Pembayaran', 'required');    
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    $this->form_validation->set_message('greater_than', '{field} wajib dipilih');                    
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $next = true;
                        $order_id = intval($post['order_id']);
                        $order_item_id = intval($post['order_item_id']);                        

                        if(strlen($post['upload_1']) < 10){
                            $next = false;
                            $return->message = 'Bukti bayar wajib dipilih';
                        }

                        // $check_date_is_past =$this->day_diff(date("Y-m-d H:i:s"), $post['order_start_date']." 14:00:00");
                        // if(intval($check_date_is_past) < 0){
                        //     $next = false;
                        //     $message = 'Tanggal Mulai tidak boleh mundur';
                        // }

                        // if($next){
                        //     $check_date_is_past2 =$this->day_diff($post['order_start_date']." 14:00:00", $post['order_end_date']." 12:00:00");
                        //     if(intval($check_date_is_past2) < 0){
                        //         $next = false;
                        //         $message = 'Tanggal Akhir sudah lewat';
                        //     }         
                        // }
                        
                        if($session_user_group_id < 3){
                            $next=true;
                        }

                        //Continue Save
                        if($next){
                            //Get Previous Order_item 
                            $get_previous = $this->Front_model->get_booking_previous_custom(['order_item_order_id' => $order_id]);
                            $get_set_branch = $this->Produk_model->get_produk_quick($get_previous['order_item_product_id']);
                            $get_product_branch = $get_set_branch['product_branch_id'];
                            // var_dump($get_product_branch);die;
                            $set_order_session = $this->random_session(20);
                            $set_order_number  = $this->request_number_for_booking($this->booking_identity,$get_product_branch);

                            //Set Params
                            $params = array(
                                'order_branch_id' => $get_previous['order_branch_id'],
                                'order_type' => $this->booking_identity,
                                'order_number' => $set_order_number,
                                'order_session' => $set_order_session,
                                'order_total_dpp' => !empty($post['paid_total']) ? intval(str_replace(",","",$post['paid_total'])) : 0,
                                'order_total' => !empty($post['paid_total']) ? intval(str_replace(",","",$post['paid_total'])) : 0,
                                'order_user_id' => $session_user_id,
                                'order_ref_id' => $get_previous['order_item_ref_id'],
                                'order_date' => date("YmdHis"),
                                'order_date_created' => date("YmdHis"),
                                'order_flag' => $get_previous['order_flag'],
                                'order_contact_code' => !empty($post['order_contact_code']) ? str_replace(' ','',$post['order_contact_code']) : null,
                                'order_contact_name' => !empty($post['order_contact_name']) ? $post['order_contact_name'] : $get_previous['order_contact_name'],
                                'order_contact_phone' => !empty($post['order_contact_phone']) ? $this->contact_number($post['order_contact_phone']) : $post['order_contact_phone'],
                                'order_parent_id' => intval($post['order_id'])                                                                                                                    
                            );                            
                            // var_dump($params);die;
                            $create = $this->Front_model->add_booking($params);
                            
                            if($create){

                                $get_booking = $this->Front_model->get_booking($create);

                                //Croppie Upload Image [Bukti Bayar]
                                $set_paid_url = null;
                                $set_paid_name = null;
                                $post_upload = !empty($this->input->post('upload_1')) ? $this->input->post('upload_1') : "";
                                if(strlen($post_upload) > 10){
                                    // $upload_process = $this->file_upload_image($this->folder_upload_file,$post_upload);
                                    $upload_process = upload_file_base64($this->folder_upload_file,$post_upload);                                            
                                    if($upload_process['status'] == 1){
                                        if ($get_booking && $get_booking['order_id']) {
                                            // $params_image = array(
                                            //     'product_image' => $upload_process->result['file_location']
                                            // );
                                            // $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                            $file_session = $this->random_session(20);
                                            $params_image = array(
                                                'file_type' => 1,
                                                'file_from_table' => 'orders',
                                                'file_from_id' => $get_booking['order_id'],
                                                'file_session' => $file_session,
                                                'file_date_created' => date("YmdHis"),
                                                'file_user_id' => $session_user_id,
                                                'file_name' => 'Bukti Bayar - '.$upload_process['result']['file_name'],
                                                'file_format' => str_replace(".","",$upload_process['result']['file_ext']),
                                                'file_url' => $upload_process['result']['file_location'],
                                                'file_size' => $upload_process['result']['file_size'],
                                                'file_note' => 'Bukti Bayar'
                                            );                                                    
                                            $stat = $this->File_model->add_file($params_image);
                                            $set_paid_url = $upload_process['result']['file_location'];
                                            $set_paid_name = $upload_process['result']['file_name'] . $upload_process['result']['file_ext'];
                                        }
                                    }else{
                                        $return->message = 'Fungsi Gambar gagal';
                                    }
                                }
                                //End of Croppie   

                                //Set Paid
                                if($post['paid_total'] > 0){
                                    $file_session = $this->random_session(20);
                                    $paid_number = $this->request_number_for_order_paid();
                                    $params = array(
                                        // 'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                                        'paid_order_id' => $get_booking['order_id'],
                                        'paid_number' => $paid_number,
                                        'paid_session' => $file_session,
                                        'paid_date_created' => date("YmdHis"),
                                        'paid_date' => date("YmdHis"),                                    
                                        'paid_user_id' => $session_user_id,
                                        'paid_url' => $set_paid_url,
                                        'paid_name' => $set_paid_name,
                                        'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_payment_method'] : null,
                                        'paid_total' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : null                           
                                    );
                                    $save_data = $this->Front_model->add_paid($params);
                                }
                                //End Set Paid

                                // $params_price = array(
                                //     'price_ref_id' => $get_previous['order_item_ref_id'],
                                //     'price_sort' => $get_previous['order_item_ref_price_id']
                                // );
                                // $get_price = $this->Ref_model->get_ref_price_custom($params_price);
                                // $order_ref_price_id = $get_price['price_id'];                                

                                //Save Item
                                $params_items = array(
                                    'order_item_branch_id' => $get_previous['order_branch_id'],
                                    'order_item_type' => $this->booking_identity,
                                    'order_item_order_id' => $create,
                                    'order_item_product_id' => $get_previous['order_item_product_id'],
                                    'order_item_qty' => 1,
                                    'order_item_price' => !empty($post['paid_total']) ? str_replace(",","",$post['paid_total']) : "0.00",
                                    'order_item_user_id' => $session_user_id,
                                    'order_item_date_created' => date("YmdHis"),
                                    'order_item_flag' => 0,
                                    'order_item_order_session' => $set_order_session,
                                    'order_item_ref_id' => $post['order_ref_id'],
                                    // 'order_item_ref_price_id' => $get_previous['order_item_ref_price_id'],
                                    'order_item_ref_price_sort' => $post['order_ref_sort'],                                            
                                    'order_item_flag_checkin' => 1,
                                    'order_item_type_2' => 'Transit',
                                    'order_item_parent_id' => intval($post['order_item_id'])
                                );        
                                $params_items['order_item_start_date']      = $get_previous['order_item_end_date']; 

                                $params_items['order_item_checkin_date']    = $get_previous['order_item_end_date']; 

                                $dtime = new DateTime($params_items['order_item_start_date']);
                                if(intval($post['order_ref_sort']) == 2){ // Harian
                                    $dtime->modify('+24 hours');                                    
                                }else if(intval($post['order_ref_sort']) == 4){ // 4 Jam
                                    $dtime->modify('+4 hours');
                                }else if(intval($post['order_ref_sort']) == 5){ // 2 Jam
                                    $dtime->modify('+2 hours');
                                }
                                $after_add_date = $dtime->format("Y-m-d H:i:s");
                                $params_items['order_item_end_date']        = $after_add_date;   
                                // var_dump($params_items);die;
                                // $params_items['order_item_checkout_date']   = $post['order_end_date'] .' 23:59:59';                                                                                              
                                $create_item = $this->Front_model->add_booking_item($params_items);

                                //Do Checkout Previous
                                $params_checkout = array(
                                    'order_item_flag_checkin'=> 2,
                                    'order_item_checkout_date' => $get_previous['order_item_end_date'],
                                );
                                $this->Front_model->update_booking_item(intval($post['order_item_id']),$params_checkout);

                                $return->status  = 1;
                                $return->message = 'Berhasil menambahkan '.$set_order_number;
                                $return->result= array(
                                    'order_id' => $create,
                                    'order_number' => $get_booking['order_number'],
                                    'order_session' => $get_booking['order_session'],
                                    'order_date' => $get_booking['order_date'],
                                    'order_total' => $get_booking['order_total'],
                                    'contact_name' => $get_booking['order_contact_name'],
                                    'contact_phone' => $get_booking['order_contact_phone'],
                                    'order_item' => $this->Front_model->get_booking_item_custom(['order_item_order_id' => $create])
                                );                                      
                            }                                 
                        }

                        $return->next = $next;
                    }
                    break;
                case "update_contact_info":
                    $this->form_validation->set_rules('order_id', 'Booking ID', 'required');
                    $this->form_validation->set_rules('name', 'Nama Tamu', 'required');
                    $this->form_validation->set_rules('phone', 'Nomor WhatsApp', 'required');                    
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id = !empty($post['order_id']) ? $post['order_id'] : 0;
                        if(intval($order_id) > 1){
                                $where = [
                                    'order_id' => $order_id
                                ];
                                $get_data = $this->Front_model->get_booking_custom($where);

                                $name = !empty($this->input->post('name')) ? $this->input->post('name') : null;
                                $phone = !empty($this->input->post('phone')) ? $this->contact_number($this->input->post('phone')) : null;                                
                                
                                $params = [
                                    'order_contact_name' => $name, 
                                    'order_contact_phone' => $this->contact_number($phone),
                                    'order_note' => $get_data['order_contact_name'].', '.$get_data['order_contact_phone']
                                ];
                                $set_update=$this->Front_model->update_booking_custom($where,$params);
                                if($set_update){
                                    $return->status  = 1;
                                    $return->message = 'Berhasil merubah '.$name.', '.$phone;
                                }else{
                                    $return->message='Gagal merubah kontak';
                                } 
                        }else{
                            $return->message = 'Tidak ada data';
                        } 
                    }
                    break;
                case "room_check_is_checkin_or_not":
                    $room_id = $post['room_id'];
                    $check = $this->Front_model->sp_room_check_only($room_id);
                    if(intval($check['room_is_available']) > 0){
                        //Not Availabe (Is Checkin)
                        $return->message = $check['room'].' '.$check['message'];
                    }else{
                        $return->status = 1;
                        $return->message = $check['room'].' '.$check['message'];
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

            $firstdateofmonth = $firstdate->format('d-m-Y');

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
            $data['branch'] = $this->Branch_model->get_all_branch(['branch_flag' => 1],null,null,null,'branch_name','asc');
            $data['ref'] = $this->Ref_model->get_all_ref(['references.ref_type' => 10],null,null,null,'ref_name','asc');
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = date("d-m-Y");
            
            $now = new DateTime();
            $date3 = $now->modify('+1 day')->format('Y-m-d H:i:s');

            $data['booking_start_date'] = date("d-M-Y");
            $data['booking_end_date'] = date("d-M-Y", strtotime($date3));              
            
            $data['hour'] = date("H:i");
            $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);

            $data['module_approval']    = $this->module_approval;
            $data['module_attachment'] = $this->module_attachment;                
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $data['branch'] = $this->Branch_model->get_all_branch(['branch_flag' => 1],null,null,null,'branch_name','asc');
            $data['ref'] = $this->Ref_model->get_all_ref(['references.ref_type' => 10],null,null,null,'branch_name','asc');            
            // $data['ref'] = $this->Ref_model->get_all_ref_price(['price_flag' => 1],null,null,null,'price_name','asc');    

            $params = array(
                'booking_item_id' => 'value'
            );
            $search = null; $limit = null; $start = null; $order  = 'order_item_id'; $dir = 'desc';
            $get_booking_item = $this->Front_model->get_all_booking_item(null,$search,$limit,$start,$order,$dir);

            // var_dump($get_booking_item);die;
            $data['identity'] = $this->booking_identity;
            $data['title'] = 'Booking';
            $data['_view'] = 'layouts/admin/menu/front_office/booking';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/front_office/booking_js.php',$data);
        }
    }
    function resto(){ 

        $data['session'] = $this->session->userdata();  
        $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
        $session_user_group_id = !empty($data['session']['user_data']['user_group_id']) ? $data['session']['user_data']['user_group_id'] : null;            
        $session_user_branch_id = !empty($data['session']['user_data']['branch']['id']) ? $data['session']['user_data']['branch']['id'] : null;                        


        if ($this->input->post()) {
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
            $session_branch_id = !empty($data['session']['user_data']['branch']['id']) ? $data['session']['user_data']['branch']['id'] : null;
            $data['theme']      = $this->User_model->get_user($data['session']['user_data']['user_id']); 
 
            $identity   = $this->resto_identity;

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load": //Works
                    $columns = array(
                        '0' => 'trans_date',
                        '1' => 'trans_number',
                        '2' => 'branch_name',
                        '3' => 'product_name',
                        '4' => 'trans_total',
                        '5' => 'label'
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
                    $filter_branch  = !empty($this->input->post('filter_branch')) ? $this->input->post('filter_branch') : 0;                    

                    $params_datatable = array(
                        'trans.trans_date >' => $date_start,
                        'trans.trans_date <' => $date_end,
                        'trans.trans_type' => intval($identity),
                        'trans.trans_flag <' => 4,
                        // 'trans.trans_branch_id' => intval($session_branch_id)
                    );
                    if($contact > 0){
                        $params_datatable = array(
                            'trans.trans_date >' => $date_start,
                            'trans.trans_date <' => $date_end,
                            'trans.trans_type' => intval($identity),
                            'trans.trans_flag <' => 4,
                            // 'trans.trans_branch_id' => intval($session_branch_id),
                            'trans.trans_contact_id' => intval($contact)
                        );
                    }

                    if(intval($filter_branch) > 0){
                        $params_datatable['trans.trans_branch_id'] = $filter_branch;
                    }

                    // if(intval($location_to) > 0){
                    //     $params_datatable['trans.trans_location_to_id'] = $location_to;
                    // }
                    
                    if(intval($type_paid) > 0){
                        $params_datatable['trans.trans_paid_type'] = intval($type_paid);
                    }

                    if($session_user_group_id > 2){
                        $params_datatable['trans.trans_branch_id'] = intval($session_user_branch_id);
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
                    $datas_count = $this->Transaksi_model->get_all_transaksis_resto_count($params_datatable,$search);
                    if($datas_count > 0){
                        $datas = $this->Transaksi_model->get_all_transaksis_resto($params_datatable, $search, $limit, $start, $order, $dir);
                        
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
                case "load_categories": //Requires ref_type, Room, Table, Something on table 'reference'
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
                        $trans_product_id       = !empty($post['trans_product_id']) ? $post['trans_product_id'] : null;
                        $trans_branch_id       = !empty($post['trans_branch_id']) ? $post['trans_branch_id'] : null;                        
                        
                        $document_session       = $this->random_code(20);
                        $document_number        = $this->request_number_for_transaction($this->resto_identity);
                        $document_date          = !empty($post['trans_date']) ? date("Y-m-d", strtotime($post['trans_date'])).' '.date("H:i:s") : date("YmdHis");   

                        //Check Operator is CREATE or UPDATE data
                        if(intval($trans_id) > 0){
                            $operator = 'update';
                        }else{
                            $operator = 'create';
                        }

                        $params = array(
                            'trans_branch_id' => $trans_branch_id,
                            'trans_type' => $this->resto_identity,
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
                            // 'trans_ref_id' => $ref_id,
                            'trans_product_id' => $trans_product_id, 
                        );

                        //Customer or Not ?
                        // if(intval($trans_contact_id) > 0){
                        //     $get_contact = $this->Kontak_model->get_kontak($trans_contact_id);
                        //     $params['trans_contact_id'] = $trans_contact_id;
                        //     $params['trans_contact_name'] = $this->sentencecase($get_contact['contact_name']);
                        //     $params['trans_contact_phone'] = $get_contact['contact_phone_1'];  
                        //     $set_contact_id = $get_contact['contact_id'];
                        //     $set_contact_name = $get_contact['contact_name'];
                        //     $set_contact_phone = $get_contact['contact_phone_1'];                            
                        // }else{
                            $params['trans_contact_id'] = null;                            
                            $params['trans_contact_name'] = $this->sentencecase($trans_contact_name);
                            $params['trans_contact_phone'] = $trans_contact_phone;
                            $set_contact_id = $trans_non_contact_id;
                            $set_contact_name = $trans_contact_name;
                            $set_contact_phone = $trans_contact_phone;                            
                        // }

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
                                    'trans_date' => $document_date,                            
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
                                        'trans_item_branch_id' => $trans_branch_id,
                                        'trans_item_type' => $this->resto_identity,
                                        // 'trans_item_type_name' => 'Penjualan',
                                        'trans_item_trans_id' => $set_document_id,
                                        'trans_item_product_id' => $v['product_id'],
                                        'trans_item_location_id' => $trans_branch_id,
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
                case "update_label":
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
                case "load-trans-items-for-report":
                    // $columns = array(
                    //     '0' => 'trans_date',
                    //     '1' => 'trans_number',
                    //     '2' => 'product_name'
                    // );           
                    
                    // $limit     = !empty($post['length']) ? $post['length'] : 10;
                    // $start     = !empty($post['start']) ? $post['start'] : 0;
                    // $order     = !empty($post['order']) ? $columns[$post['order'][0]['column']] : $columns[0];
                    // $dir       = !empty($post['order'][0]['dir']) ? $post['order'][0]['dir'] : "asc";

                    $trans_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : 0;
                    $contact_id = !empty($this->input->post('kontak')) ? $this->input->post('kontak') : 0;
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $branch = !empty($this->input->post('branch')) ? $this->input->post('branch') : 0;                    
                    $sales_id = !empty($this->input->post('sales')) ? $this->input->post('sales') : 0;                    
                    $product = !empty($this->input->post('product')) ? $this->input->post('product') : 0;    

                    $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));                    
                    // $subtotal = 0;
                    // $total_diskon = 0;
                    // $total_ppn = 0;
                    // $total= 0;

                    $params = array(
                        'trans_type' => intval($trans_type),
                        'trans_date >' => $date_start,
                        'trans_date <' => $date_end,                         
                    );
                    if(intval($branch) > 0){
                        $params['trans_branch_id'] = $branch;
                    }
                    // if(intval($contact_id) > 0){
                    //     $params['trans_contact_id'] = intval($contact_id);
                    // }
                    // if(intval($sales_id) > 0){
                    //     $params['trans_sales_id'] = intval($sales_id);
                    // }                    
                    // if(intval($product_id) > 0){
                    //     $params['trans_item_product_id'] = intval($product_id);
                    // }

                    // if(intval($product) > 0){
                    //     $params['trans_item_product_id'] = intval($product);
                    // }     

                    $search = null;
                    $limit = 1000;
                    $start = 0;
                    $order = 'trans_item_date'; 
                    $dir = 'asc';          
                    // var_dump($order,$dir);die;
                    $datas = $this->Transaksi_model->get_all_transaksi_items_report($params,$search,$limit,$start,$order,$dir);

                    if(!empty($datas)){
                        // foreach($get_data as $v){

                        //     $get_product_price = $this->Product_price_model->get_all_product_price(array('product_price_product_id'=>$v['product_id']),null,null,null,null,null);
                        //     $product_price_list = array();
                        //     foreach($get_product_price as $pp){
                        //         $product_price_list[] = array(
                        //             'product_price_id' => $pp['product_price_id'],
                        //             'product_price_product_id' => $pp['product_price_product_id'],
                        //             'product_price_name' => $pp['product_price_name'],
                        //             'product_price_price' => $pp['product_price_price']
                        //         );
                        //     }

                        //     $datas[] = array(
                        //         'trans_item_id' => $v['trans_item_id'],
                        //         'trans_item_order_id' => $v['trans_item_order_id'],
                        //         'trans_item_unit' => $v['trans_item_unit'],
                        //         // 'trans_item_qty_weight' => number_format($v['trans_item_qty_weight'],2,'.',','),
                        //         'trans_item_in_qty' => number_format($v['trans_item_in_qty'],2,'.',','),
                        //         'trans_item_in_price' => number_format($v['trans_item_in_price'],2,'.',','),
                        //         'trans_item_out_qty' => number_format($v['trans_item_out_qty'],2,'.',','),
                        //         'trans_item_out_price' => number_format($v['trans_item_out_price'],2,'.',','),
                        //         'trans_item_sell_price' => number_format($v['trans_item_sell_price'],2,'.',','),
                        //         'trans_item_discount' => number_format($v['trans_item_discount'],2,'.',','),
                        //         'trans_item_total' => number_format($v['trans_item_total'],2,'.',','),
                        //         'trans_item_sell_total' => number_format($v['trans_item_sell_total'],2,'.',','),
                        //         'trans_item_total_after_discount' => number_format($v['trans_item_total'],2,'.',','),
                        //         'trans_item_note' => $v['trans_item_note'],
                        //         // 'trans_item_product_price_id' => $v['trans_item_product_price_id'],
                        //         'trans_item_user_id' => $v['trans_item_user_id'],
                        //         'trans_item_branch_id' => $v['trans_item_branch_id'],
                        //         'trans_item_ppn' => $v['trans_item_ppn'],
                        //         'product_id' => $v['product_id'],
                        //         'product_code' => $v['product_code'],
                        //         'product_name' => $v['product_name'],
                        //         'has_other_price' => $product_price_list
                        //     );

                        //     if($identity==2){ //Penjualan
                        //         $subtotal=$subtotal+$v['trans_item_sell_total'];
                        //         if($v['trans_item_ppn'] == 1){
                        //             $total_ppn = $total_ppn + ($v['trans_item_sell_total']*0.1);
                        //         }
                        //     }else{
                        //         $subtotal=$subtotal+$v['trans_item_total'];
                        //         if($v['trans_item_ppn'] == 1){
                        //             $total_ppn = $total_ppn + ($v['trans_item_total']*0.1);
                        //         }
                        //     }
                        // }
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
                            // $return->total_produk=count($datas);
                            // $return->subtotal=number_format($subtotal,0,'.',',');
                            // $return->total_diskon=number_format($total_diskon,0,'.',',');
                            // $return->total_ppn=number_format($total_ppn,0,'.',',');
                            // $return->total=number_format(($subtotal+$total_ppn)-$total_diskon,0,'.',',');
                        }else{
                            $data_source=0; $total=0;
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->result=0;
                        }
                        $return->status=1;
                        $return->message='Data ditemukan';
                        $return->params = $params;
                        // if(intval($trans_id) > 0){
                            // $return->message = 'Berhasil memuat data';
                        // }
                    }else{
                        $total=0;
                        $return->message='Tidak ada item temporary';
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    break;                                                                                                                                                    
                default:
                    $return->message='No Action';
                    break; 
            }
            echo json_encode($return);
        }else{
            // Default First Date & End Date of Current Month
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('d-m-Y');

            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = date("d-m-Y");
            $data['hour'] = date("H:i");
            $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);

            $data['module_approval']    = $this->module_approval;
            $data['module_attachment']  = $this->module_attachment;   
            $data['identity']           = $this->resto_identity;
            $data['whatsapp_config']  = $this->whatsapp_config;
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $params_datatable = array(
                'category_type' => 1, //Product Categories
                'category_flag' => 1
            );            
            $where_non = array(
                'contact_flag' => 5,
                'contact_type' => 2
            ); 
            $params_product = array(
                'product_type' => 1,
                'product_flag' => 1
            );                          
            $data['products']         = $this->Produk_model->get_all_produks_datatable($params_product,null,6,0,'product_name','asc');            
            $data['product_category'] = $this->Kategori_model->get_all_categoriess($params_datatable,null,null,null,'category_name','asc');            
            $data['non_contact']      = $this->Kontak_model->get_kontak_custom($where_non);    
            
            $branch_params = [
                'branch_flag' => 1
            ];
            if($session_user_group_id > 2){
                $branch_params['branch_id'] = $session_user_branch_id;
            }
            $data['branch']           = $this->Branch_model->get_all_branch($branch_params,null,null,null,'branch_name','asc');

            $data['contact_1_alias']  = 'Customer';
            $data['contact_2_alias']  = 'Sales By';
            $data['ref_alias']        = 'Kamar';         
            $data['order_alias']      = 'Warmindo';
            $data['trans_alias']      = 'Warmindo';
            $data['payment_alias']    = 'Checkout'; 
            $data['dp_alias']         = 'Down Payment'; 
            $data['product_alias']    = 'Makanan';    
                        
            $data['title'] = 'Front';
            $data['_view'] = 'layouts/admin/menu/front_office/resto';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/front_office/resto_js.php',$data);
        }
    }
    function contact_number($contact_phone){ //Contact 0 / +62 to safe
        $contact_phone = str_replace("'","",$contact_phone); //Remove ' if excel format    
        $contact_phone = str_replace('+','',str_replace('-','',$contact_phone)); //Remove + and -
        $contact_phone = ltrim(rtrim(trim($contact_phone))); //Remove space
        $contact_phone = str_replace(' ','',$contact_phone);
        $contact_phone_check = substr($contact_phone,0,1); // First char is 0
        if($contact_phone_check == 0){
            $contact_phone = '62'.substr($contact_phone,1,15); //To 62 81213123
        }else{
            $contact_phone = $contact_phone; //
        }
        return $contact_phone;        
    }
    function request_number_for_order_paid(){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];

        $tgl = date('d-m-Y');
        $tahun = substr($tgl, 6, 4);
        $bulan = substr($tgl, 3, 2);
        $hari = substr($tgl, 0, 2);
        $tahun2 = substr($tgl, 8, 2);

        $query = $this->db->query("SELECT MAX(RIGHT(paid_number,5)) AS last_number
            FROM orders_paids
            WHERE YEAR(paid_date_created)=$tahun
            AND MONTH(paid_date_created)=$bulan");
            // AND paid_branch_id=$session_branch_id
            // AND order_type=$tipe");
        $nomor = "";
        if ($query->num_rows() > 0){
            foreach ($query->result() as $v){
                $temp = ((int) $v->last_number) + 1;
                $nomor = sprintf("%05s", $temp);
            }
        }else{
            $nomor = "00001";
        }  
        $auto_number = 'PAY' . '-' . $tahun2 . $bulan . '-' . $nomor;
        return $auto_number;
    }
    function file_unit_size($bytes){
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 0) . ' TB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 0) . ' GB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 0) . ' MB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' KB';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' KB';
        }
        else
        {
            $bytes = '0 KB';
        }

        return $bytes;
    }
    function time_diff($datetime1, $datetime2) {
        // Create DateTime objects from the provided datetime strings
        $date1 = new DateTime($datetime1);
        $date2 = new DateTime($datetime2);

        // Calculate the difference between the two DateTime objects
        $interval = $date1->diff($date2);

        // Determine if the first date is in the past relative to the second date
        $isPast = $date1 > $date2;

        // Format the difference as a string
        // $difference = $interval->format('%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
        $difference = $interval->format("%h");

        // Add a minus sign if the first date is in the past
        return $isPast ? '-' . $difference : $difference;
        // return $interval->format('%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
    }    
    function hour_diff($d,$p){ //Tgl Terkecil, Tgl Terbesar
        $date1 = new DateTime($d);
        $date2 = new DateTime($p);

        $diff   = $date2->diff($date1);
        // var_dump($diff);die;
        $hours  = $diff->h;
        $mins   = $diff->i;
        // $hours = $hours + ($diff->days*24);
        // $mins = $mins + ($diff->hours*60);
        // return "$hours"." jam"." $mins"." menit";
        if($diff->invert == 1){
            $in = '';
        }else{
            $in = '-';
        }
        return intval($in.$hours);
    }
    function day_diff($d1, $d2){ //Tgl Terbesar, Tgl Terkecil 
        // var_dump($d1,$d2);die;
        $d1 = strtotime($d1);
        $d2 = strtotime($d2);
        return intVal(($d2 - $d1) / (24 *3600));
    }
    function sync_product($branch_id){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';
        $params = array(
            'product_branch_id' => $branch_id,
            'product_type' => 1
        );
        $return->params = $params;
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

        $path = FCPATH . 'download';
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }

        //write json to file
        if (file_put_contents($path."/"."products_".$branch_id.".json", $json)){
            $return->message = "JSON file created successfully...";
            $return->result = $datas;
            $return->status = 1;
        }else{ 
            $return->message = "Oops! Error creating json file...";
        }
        echo json_encode($return);
    }
    function format_byte($bytes) {
        if ($bytes > 0) {
            $i = floor(log($bytes) / log(1024));
            $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
        } else {
            return 0;
        }
    }
    function prints_booking($id){ // ID = TRANS ID Print Thermal 58mm Done 
        $text = '';
        $word_wrap_width = 29;

        $get_trans  = $this->Front_model->get_booking($id);
        $get_branch = $this->Branch_model->get_branch($get_trans['order_branch_id']);
        $get_trans = $this->Front_model->get_booking_item_custom(array('order_item_order_id'=> $id),$search = null,$limit = null,$start = null,$order = null,$dir = null);
        
        $order_data = array();

        //Process if Data Found
        if($get_trans){
            $paid_type_name = '';
            // if($get_trans['trans_paid_type'] > 0){
            //     $get_type_paid = $this->Type_model->get_type_paid($get_trans['trans_paid_type']);
            //     $paid_type_name = $get_type_paid['paid_name'];
            // }else{
            //     $paid_type_name = '-'; // Piutang
            // }

            //Header
            $text .= dot_set_wrap_0($get_branch['branch_name'],' ','BOTH');
            // $text .= dot_set_wrap_1($get_branch['branch_address']);
            // $text .= dot_set_wrap_1($get_branch['branch_phone_1']);                
            $text .= dot_set_wrap_0($get_trans['order_number'],' ','BOTH');
            $text .= dot_set_wrap_0(date("d/m/Y - H:i:s", strtotime($get_trans['order_date'])),' ','BOTH');    
            // $text .= dot_set_wrap_2('Cashier',$get_trans['contact_name']);

            $text .= "\n";

            $date_check = date("d/M/y", strtotime($get_trans['order_item_start_date'])) .' - '. date("d/M/y", strtotime($get_trans['order_item_end_date']));
            $hour_check = date("H:i", strtotime($get_trans['order_item_start_date'])) .' - '. date("H:i", strtotime($get_trans['order_item_end_date']));            
            if($get_trans['order_item_ref_price_sort'] == 0){
                $sort_name = 'PROMO';
            }else if($get_trans['order_item_ref_price_sort'] == 1){
                $sort_name = 'Bulanan';
            }else if($get_trans['order_item_ref_price_sort'] == 2){
                $sort_name = 'Harian';                
            }else if($get_trans['order_item_ref_price_sort'] == 3){
                $sort_name = 'Midnight';                
            }else if($get_trans['order_item_ref_price_sort'] == 4){
                $sort_name = '4 Jam';                
            }else if($get_trans['order_item_ref_price_sort'] == 5){
                $sort_name = '2 Jam';                
            }else{
                $sort_name = '';
            }

            // $text.= dot_set_wrap_2('Kontak', $this->stringToSecret($get_trans['order_contact_name']));
            $text.= dot_set_line('-',$word_wrap_width);
            $text.= dot_set_wrap_0("Check-In",' ','BOTH');
            $text.= dot_set_wrap_0($date_check,' ','BOTH');
            $text.= dot_set_wrap_0($hour_check,' ','BOTH');            
            $text.= dot_set_line('-',$word_wrap_width);            
            $text.= dot_set_wrap_2('Kontak', $get_trans['order_contact_name']);
            $text.= dot_set_wrap_2('Tipe',$sort_name);
            $text.= dot_set_wrap_2('Kamar','['.$get_trans['ref_name'].']');
            $text.= dot_set_wrap_2(' ',$get_trans['product_name']);
            if(!empty($get_trans['order_vehicle_cost'])){
                $text.= dot_set_wrap_2('Jml Kendrn ',$get_trans['order_vehicle_count']);            
            }
            if(!empty($get_trans['order_vehicle_plate_number'])){
                $text.= dot_set_wrap_2('Plat Kendrn ',$get_trans['order_vehicle_plate_number']);            
            }            
            $text .= dot_set_line('-',$word_wrap_width);

            // $text .= "\n";
            if(!empty($get_trans['order_vehicle_cost']) && $get_trans['order_vehicle_cost'] > 0){
                $text .= dot_set_wrap_3('Biaya Parkir',':',''.number_format($get_trans['order_vehicle_cost'],0,'',','));    
            }                        
            if(!empty($get_trans['order_total']) && $get_trans['order_total'] > 0){
                $text .= dot_set_wrap_3('Kamar',':',''.number_format($get_trans['order_total'],0,'',','));    
            }
            $text .= dot_set_line('-',$word_wrap_width);            
            if(!empty($get_trans['order_total_paid']) && $get_trans['order_total_paid'] > 0){
                $text .= dot_set_wrap_3('Dibayar',':',''.number_format($get_trans['order_total_paid'],0,'',','));    
            }    

            if($get_trans['order_paid'] == 1){
                $lunas = 'Lunas';                
            }else{
                $lunas = 'Belum Lunas';
            }
            $text .= dot_set_wrap_3('Status',':',$lunas);

            //Footer
            $text .= "\n";
            $text .= dot_set_wrap_0("-- Terima Kasih --",' ','BOTH');    
            $text .= dot_set_wrap_0("Gratis jika tidak menerima struk",' ','BOTH');                 

            //Save to Print Spoiler
            $params = array(
                'spoiler_content' => $text, 'spoiler_source_table' => 'order',
                'spoiler_source_id' => $id, 'spoiler_flag' => 0, 'spoiler_date' => date('YmdHis')
            );
            // $this->Print_spoiler_model->add_print_spoiler($params);
        }else{
            $text = "Transaksi tidak ditemukan\n";
        }

        
        //Open / Write to print.txt
        $file = fopen("print_booking_".$get_branch['branch_id'].".txt", "w") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);
        // $text .= chr(27).chr(10);

        //Write and Save
        fwrite($file,$text);
        // fclose($file);

        if(fclose($file)){
            echo json_encode(array('status'=>1,'print_url'=>base_url('print_booking_'.$get_branch['branch_id'].'.txt'),'print_to'=>$this->print_to));
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
    function stringToSecret($string){
        if (!$string) {
            return NULL;
        }
        $length = strlen($string);
        $visibleCount = (int) round($length / 4);
        $hiddenCount = $length - ($visibleCount * 2);
        return substr($string, 0, $visibleCount) . str_repeat('*', $hiddenCount) . substr($string, ($visibleCount * -1), $visibleCount);
    } 
    function test(){
        echo json_encode($this->Front_model->get_all_paid(null,null,null,null,'paid_id','asc'));

        $params = array(
            'paid_id' => !empty($post['paid_id']) ? intval($post['paid_id']) : null,
            'paid_order_id' => !empty($post['paid_order_id']) ? intval($post['paid_order_id']) : null,
            'paid_number' => !empty($post['paid_number']) ? $post['paid_session'] : null,
            'paid_date' => !empty($post['paid_date']) ? $post['paid_session'] : null,
            'paid_payment_type' => !empty($post['paid_payment_type']) ? $post['paid_session'] : null,
            'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_session'] : null,
            'paid_total' => !empty($post['paid_total']) ? intval($post['paid_total']) : '0.00',
            'paid_date_created' => date("YmdHis"),
            'paid_url' => !empty($post['paid_url']) ? $post['paid_session'] : null,
            'paid_user_id' => $session_user_id,
            'paid_size' => !empty($post['paid_size']) ? intval($post['paid_size']) : null,
            'paid_session' => !empty($post['paid_session']) ? $post['paid_session'] : null,
        );        
    }
    function test2(){
        // echo date("Y-m-d H:i:s");
        // $td = '2024-09-10 14:26:00';
        $td = date("Y-m-d H:i:s");
        $od = '2024-09-10 14:30:00';
        $check_date_is_past = $this->time_diff($td, $od);
        if(intval($check_date_is_past) < 0){
            echo 'lampau';
        }else{
            echo 'boleh';
        }
        var_dump("Selisih Hari: ".$this->day_diff($td,$od));        
        var_dump('Sekarang: '.$td,'Booking: '.$od,'Selisih: '.$check_date_is_past.' jam');   
    }
}
?>