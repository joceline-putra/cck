<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontak extends MY_Controller{
    var $folder_upload = 'upload/contact/';
    var $image_width   = 240;
    var $image_height  = 240;  
    var $folder_location = array(
        '0' => array(
            'parent_id' => 47,            
            'title' => 'Kontak',
            'view' => 'layouts/admin/menu/contact/contact',
            'javascript' => 'layouts/admin/menu/contact/contact_js'
        ),
        '1' => array( //Supplier
            'parent_id' => 47,
            'title' => 'Supplier',
            'view' => 'layouts/admin/menu/contact/supplier',
            'javascript' => 'layouts/admin/menu/contact/supplier_js'
        ),
        '2' => array( //Customer
            'parent_id' => 47,            
            'title' => 'Customer',
            'view' => 'layouts/admin/menu/contact/customer',
            'javascript' => 'layouts/admin/menu/contact/customer_js'
        ),
        '3' => array( //Karyawan
            'parent_id' => 47,            
            'title' => 'Karyawan',
            'view' => 'layouts/admin/menu/contact/employee',
            'javascript' => 'layouts/admin/menu/contact/employee_js'
        ),
        '4' => array( //Pasien
            'parent_id' => 47,            
            'title' => 'Pasien',
            'view' => 'layouts/admin/menu/contact/patient',
            'javascript' => 'layouts/admin/menu/contact/patient_js'
        ),
        '5' => array( //Insurance
            'parent_id' => 47,            
            'title' => 'Insurance',
            'view' => 'layouts/admin/menu/contact/insurance',
            'javascript' => 'layouts/admin/menu/contact/insurance_js'
        ),
        '00' => array(
            'parent_id' => 47,            
            'title' => 'Statistik',            
            'view' => 'layouts/admin/menu/contact/statistic',
            'javascript' => 'layouts/admin/menu/contact/statistic_js'
        ),
    );
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));              
        }
        $this->load->model('User_model');
        $this->load->model('Kontak_model');
        $this->load->model('Kategori_model');        
        $this->load->model('Account_model');
        $this->load->model('Menu_model');
        $this->load->model('Order_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Journal_model');                
        // $this->load->model('Gudang_model');
        // $this->load->model('Golongan_obat_model');
        // $this->load->model('Diagnosa_model');
        // $this->load->model('Jenis_praktik_model');
        // $this->load->model('Aktivitas_model');
        $this->load->model('Branch_model');          
        $this->load->library('form_validation');
        $this->load->helper('form');        
    }   
    function index(){
        $data['session'] = $this->session->userdata();
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
        $data['all_user_group'] = $this->User_model->get_all_user_group();

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d");
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        $data['title'] = 'Karyawan & Dokter';
        $data['_view'] = 'layouts/admin/menu/user/index';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/user/js.php',$data);
    }
    function pages($identity){

        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['account_payable'] = $this->get_account_map_for_transaction($session_branch_id,4,1); //Account Payable
        $data['account_receivable'] = $this->get_account_map_for_transaction($session_branch_id,4,2); //Account Receivable
        // if($identity == 0){ //All
        //     $data['identity'] = 0;
        //     $data['title'] = 'Kontak';
        //     $data['_view'] = 'contact/contact';
        //     $file_js = 'contact/contact_js.php';
        // }
        // if($identity == 1){ //Supplier
        //     $data['identity'] = 1;
        //     $data['title'] = 'Supplier';
        //     $data['_view'] = 'contact/supplier';
        //     $file_js = 'contact/supplier_js.php';
        // }
        // if($identity == 2){ //Customer
        //     $data['identity'] = 2;
        //     $data['title'] = 'Customer';
        //     $data['_view'] = 'contact/customer';
        //     $file_js = 'contact/customer_js.php';
        // }
        //Account Receivable & Payable
        $params_acc_payable = array(
            'account_branch_id' => $session_branch_id,
            'account_flag' => 1,
            'account_group_sub' => 8
        );
        $params_acc_receivable = array(
            'account_branch_id' => $session_branch_id,
            'account_flag' => 1,
            'account_group_sub' => 3
        );
        // $data['account_payable'] = $this->Account_model->get_all_account($params_acc_payable,null,1,0,null,null);
        // $data['account_receivable'] = $this->Account_model->get_all_account($params_acc_receivable,null,1,0,null,null);

        //Sub Navigation
        $params_menu = array(
            'menu_parent_id' => $this->folder_location[$identity]['parent_id'],
            'menu_flag' => 1
        );
        $get_menu = $this->Menu_model->get_all_menus($params_menu,null,null,null,'menu_sorting','asc');
        $data['navigation'] = !empty($get_menu) ? $get_menu : [];
        
        $data['identity'] = $identity;
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['_view'] = $this->folder_location[$identity]['view'];
        $file_js = $this->folder_location[$identity]['javascript'];

        $data['session'] = $this->session->userdata();
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        $data['image_width'] = intval($this->image_width);
        $data['image_height'] = intval($this->image_height);

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d");
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        // var_dump($data['account_payable']);die;
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

        $identity = $this->input->post('tipe');
        if($identity == 0){ //All Kontak Type
            $set_tipe = 0;
        }else if($identity == 1){ //Supplier
            $set_tipe = 1;
        }else if($identity == 2){ //Customer
            $set_tipe = 2;
        }else if($identity == 3){ //Karyawan
            $set_tipe = 3;
        }else if($identity == 4){ //Pasien
            $set_tipe = 4;
        }else if($identity == 5){ //Insurance
            $set_tipe = 5;
        }

        if($this->input->post('action')){
            $action = $this->input->post('action');
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'contact_code',
                        '1' => 'contact_name',                    
                        '2' => 'contact_phone_1',
                        '3' => 'contact_email_1',
                        '4' => 'contact_identity_number',                    
                        '5' => 'contact_company',
                        '6' => 'contact_address',
                        '7' => 'contact_parent_name'
                    );
                    $limit = $this->input->post('length');
                    $start = $this->input->post('start');
                    $order = $columns[$this->input->post('order')[0]['column']];
                    $dir = $this->input->post('order')[0]['dir'];

                    $filter_category    = !empty($this->input->post('filter_categories')) ? intval($this->input->post('filter_categories')) : 0;   
                    
                    $search = [];
                    // $search['contact_type'] = $set_tipe;

                    // $search['contact_type'] = '1';
                    
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array(
                        // 'contact_branch_id' => intval($session_branch_id),
                        'contact_type' => intval($set_tipe)
                    );

                    if($this->input->post('filter_flag') < 100){
                        $params['contact_flag'] = intval($this->input->post('filter_flag'));
                    }
                    
                    if($this->input->post('filter_kontak') > 0){
                        $params['contact_parent_id'] = intval($this->input->post('filter_kontak'));
                    }

                    $filter_category > 0 ? $params['contact_category_id'] = intval($filter_category) : $params;
                                    
                    //All Kontak
                    if($set_tipe == 0){
                        $params = array(
                            'contact_branch_id' => $session_branch_id
                        );
                        if($this->input->post('type') > 0){
                            $search['contact_type'] = $this->input->post('type');
                        }else{
                            $search['contact_type'] = null;
                        }
                    }

                    // $params['contact_type'] = 'LIKE ""221""';
                    // if ($this->input->post('user_role') && $this->input->post('user_role') > 0) {
                        // $params['user_role'] = $this->input->post('user_role');
                    // }
                    // var_dump($params,$search);die;

                    $datas_count = $this->Kontak_model->get_all_kontak_count($params,$search);
                    if($datas_count > 0){
                        $datas = $this->Kontak_model->get_all_kontaks($params, $search, $limit, $start, $order, $dir);                    
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
                    $return->params = $params;
                    $return->search = $search;
                    break;       
                case "create":
                    $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    $data = json_decode($post_data, TRUE);

                    //Prepare Start
                    // JSON ENCODE
                    /*
                    $contact_type = $data['tipe'];
                    $contact_code = $data['kode'];
                    $contact_name = $data['name'];
                    $is_supplier = $data['supplier'];
                    $is_customer = $data['customer'];
                    $is_employee = $data['karyawan']; */

                    // POST DIRECT
                    $contact_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : null;
                    $contact_code = !empty($this->input->post('kode')) ? $this->input->post('kode') : null;
                    $contact_name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;
                    $contact_identity_number = !empty($this->input->post('identity_number')) ? $this->input->post('identity_number') : null;

                    $is_supplier = ($this->input->post('supplier')==true) ? 1 : '';
                    $is_customer = ($this->input->post('customer')==true) ? 2 : '';
                    $is_employee = ($this->input->post('karyawan')==true) ? 3 : '';
                    $is_patient  = ($this->input->post('pasien')==true) ? 4 : '';
                    $is_insurance  = ($this->input->post('asuransi')==true) ? 5 : '';

                    //Contact Type
                    $set_contact_type = 0;
                    $set_contact_type=$is_supplier.$is_customer.$is_employee.$is_patient.$is_insurance;
                    if($is_patient == 4){
                        $set_contact_type = 4;
                    }
                    $set_contact_type = $identity;

                    //Check Data Exist
                    $params_check = array(
                        'contact_type' => intval($set_contact_type),
                        'contact_code' => $contact_code
                        // 'contact_branch_id' => intval($session_branch_id)
                    );
                    
                    if($identity == 1){
                        $params_check['contact_name'] = $contact_name;
                        $set_message = $contact_name;
                    }else if($identity == 2){
                        $params_check['contact_code'] = $contact_code;
                        $params_check['contact_name'] = $contact_name;                             
                        $set_message = $contact_name;                   
                    }else if($identity == 3){
                        $params_check['contact_identity_number'] = $contact_identity_number;        
                        $set_message = $contact_code;                
                    }
                    $check_exists = $this->Kontak_model->check_data_exist($params_check);
                    // var_dump($params_check);die;
                    if($check_exists==false){
                        /*
                        $params = array(
                            'contact_type' => $set_contact_type,
                            'contact_code' => $data['kode'],
                            'contact_name' => $data['nama'],
                            'contact_address' => $data['alamat'],
                            'contact_company' => $data['perusahaan'],
                            'contact_phone_1' => $data['telepon_1'],
                            'contact_phone_2' => $data['telepon_2'],
                            'contact_email_1' => $data['email_1'],
                            'contact_email_2' => $data['email_2'],
                            'contact_date_created' => date("YmdHis"),
                            'contact_date_updated' => date("YmdHis"),
                            'contact_flag' => $data['status'],
                            'contact_user_id' => $session_user_id,
                            'contact_branch_id' => $session_branch_id,
                            'contact_identity_type' => $data['identity_type'],
                            'contact_identity_number' => $data['identity_number'],
                            'contact_fax' => $data['fax'],
                            'contact_npwp' => $data['npwp'],
                            'contact_handphone' => $data['handphone'],
                            'contact_note' => $data['note']
                        );
                        */
                        $generate_session = $this->random_session(8);
                        $params = array(
                            'contact_type' => $set_contact_type,
                            'contact_category_id' => !empty($this->input->post('categories')) ? $this->input->post('categories') : null,                    
                            'contact_code' => !empty($this->input->post('kode')) ? $this->input->post('kode') : $generate_session,
                            'contact_name' => !empty($this->input->post('nama')) ? $this->input->post('nama') : null,
                            'contact_address' => !empty($this->input->post('alamat')) ? $this->input->post('alamat') : null,
                            'contact_company' => !empty($this->input->post('perusahaan')) ? $this->input->post('perusahaan') : null,
                            'contact_phone_1' => !empty($this->input->post('telepon_1')) ? $this->input->post('telepon_1') : null,
                            'contact_phone_2' => !empty($this->input->post('telepon_2')) ? $this->input->post('telepon_2') : null,
                            'contact_email_1' => !empty($this->input->post('email_1')) ? $this->input->post('email_1') : null,
                            'contact_email_2' => !empty($this->input->post('email_2')) ? $this->input->post('email_2') : null,
                            'contact_date_created' => date("YmdHis"),
                            'contact_date_updated' => date("YmdHis"),
                            'contact_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 1,
                            'contact_user_id' => $session_user_id,
                            'contact_branch_id' => $session_branch_id,
                            'contact_identity_type' => !empty($this->input->post('identity_type')) ? $this->input->post('identity_type') : 1,
                            'contact_identity_number' => !empty($this->input->post('identity_number')) ? $this->input->post('identity_number') : null,
                            'contact_fax' => !empty($this->input->post('fax')) ? $this->input->post('fax') : null,
                            'contact_npwp' => !empty($this->input->post('npwp')) ? $this->input->post('npwp') : null,
                            'contact_handphone' => !empty($this->input->post('handphone')) ? $this->input->post('handphone') : null,
                            // 'contact_note' => !empty($this->input->post('note')) ? $this->input->post('note') : null,
                            'contact_account_receivable_account_id' => !empty($this->input->post('akun_piutang')) ? $this->input->post('akun_piutang') : null,
                            'contact_account_payable_account_id' => !empty($this->input->post('akun_hutang')) ? $this->input->post('akun_hutang') : null,
                            // 'contact_name_mandarin' => $this->input->post('nama_mandarin'),
                            // 'contact_profesi' => $this->input->post('profesi'),
                            // 'contact_birth_of_date' => $this->input->post('tgl'),
                            'contact_url' => str_replace(' ', '-', strtolower($contact_name)),
                            'contact_gender' => !empty($this->input->post('gender')) ? $this->input->post('gender') : null,
                            'contact_birth_date' => !empty($this->input->post('tgl_lahir')) ? $this->input->post('tgl_lahir') : null,
                            'contact_birth_city_id' => !empty($this->input->post('tempat_lahir')) ? $this->input->post('tempat_lahir') : null,
                            'contact_parent_id' => !empty($this->input->post('kontak_parent')) ? $this->input->post('kontak_parent') : null,
                            'contact_city_id' => !empty($this->input->post('kota')) ? $this->input->post('kota') : null,
                            'contact_province_id' => !empty($this->input->post('provinsi')) ? $this->input->post('provinsi') : null,
                            'contact_session' => $this->random_code(8),
                            'contact_termin' => !empty($this->input->post('contact_termin')) ? $this->input->post('contact_termin') : 0,
                            'contact_payable_limit' => !empty($this->input->post('contact_payable_limit')) ? $this->input->post('contact_payable_limit') : '0.00',
                            'contact_receivable_limit' => !empty($this->input->post('contact_receivable_limit')) ? $this->input->post('contact_receivable_limit') : '0.00'                    
                        );                        
                        $set_data=$this->Kontak_model->add_kontak($params);
                        if($set_data==true){
                            $contact_id = $set_data;
                            $get_data = $this->Kontak_model->get_kontak($contact_id);

                            //Croppie Upload Image
                            $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                            if(strlen($post_upload) > 10){
                                $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                                if($upload_process->status == 1){
                                    if ($get_data && $get_data['contact_id']) {
                                        $params_image = array(
                                            'contact_image' => $upload_process->result['file_location']
                                        );
                                        $this->Kontak_model->update_kontak($contact_id, $params_image);
                                    }
                                }else{
                                    $return->message = 'Fungsi Gambar gagal';
                                }
                            }
                            //End of Croppie   
                            
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 2,
                                'activity_table' => 'contacts',
                                'activity_table_id' => $set_data,
                                // 'activity_text_1' => strtoupper($contact_code),
                                'activity_text_2' => ucwords(strtolower($contact_name)).' '.strtoupper($contact_code),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);
                            $return->status=1;
                            $return->message='Berhasil menyimpan '.$contact_name;
                            $return->result= array(
                                'id' => $set_data,
                                'kode' => $contact_code,
                                'nama' => $contact_name
                            );
                        }
                    }else{
                        $return->message='Data '.$set_message.' sudah ada';
                    }
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);
                    $data['id'] = $this->input->post('id');
                    $datas = $this->Kontak_model->get_kontak($data['id']);
                    if($datas==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 3,
                            'activity_table' => 'contacts',
                            'activity_table_id' => $datas['contact_id'],
                            // 'activity_text_1' => strtoupper($datas['contact_code']),
                            'activity_text_2' => ucwords(strtolower($datas['contact_name'])).' '.strtoupper($datas['contact_code']),        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        $return->status=1;
                        $return->message='Success';
                        $return->result=$datas;
                    }
                    break;
                case "update":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);

                    $contact_id = $this->input->post('id');
                    $contact_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : null;
                    $contact_code = !empty($this->input->post('kode')) ? $this->input->post('kode') : null;
                    $contact_name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;
                    $contact_identity_number = !empty($this->input->post('identity_number')) ? $this->input->post('identity_number') : null;

                    $is_supplier = ($this->input->post('supplier')==true) ? 1 : '';
                    $is_customer = ($this->input->post('customer')==true) ? 2 : '';
                    $is_employee = ($this->input->post('karyawan')==true) ? 3 : '';
                    $is_patient  = ($this->input->post('pasien')==true) ? 4 : '';
                    $is_insurance  = ($this->input->post('asuransi')==true) ? 5 : '';

                    //Contact Type
                    $set_contact_type = 0;
                    $set_contact_type=$is_supplier.$is_customer.$is_employee.$is_patient.$is_insurance;
                    // if($set_contact_type = ''){
                    //     $set_contact_type = 0;
                    // }
                    /*
                    $params = array(
                        'contact_type' => $set_contact_type,
                        'contact_code' => $data['kode'],
                        'contact_name' => $data['nama'],
                        'contact_address' => $data['alamat'],
                        'contact_company' => $data['perusahaan'],
                        'contact_phone_1' => $data['telepon_1'],
                        'contact_phone_2' => $data['telepon_2'],
                        'contact_email_1' => $data['email_1'],
                        'contact_email_2' => $data['email_2'],
                        'contact_date_updated' => date("YmdHis"),
                        'contact_flag' => $data['status'],
                        'contact_identity_type' => $data['identity_type'],
                        'contact_identity_number' => $data['identity_number'],
                        'contact_fax' => $data['fax'],
                        'contact_npwp' => $data['npwp'],
                        'contact_handphone' => $data['handphone'],
                        'contact_note' => $data['note']
                    );
                    */
                    //Check Data Exist
                    $params_check = array(
                        'contact_id !=' => intval($contact_id),
                        'contact_type' => intval($set_contact_type),
                        // 'contact_branch_id' => intval($session_branch_id)
                    );

                    if($identity == 1){//Supplier
                        $params_check['contact_name'] = $contact_name;
                        $set_message = $contact_name;
                        $check_exists = $this->Kontak_model->check_data_exist($params_check);
                    }else if($identity == 2){ //Customer
                        $params_check['contact_code'] = $contact_code;                        
                        $params_check['contact_name'] = $contact_name;
                        $set_message = $contact_code;                                            
                        
                        $where_not = [
                            'contact_id' => intval($contact_id)
                        ];
                        $where_in = [
                            'contact_code' => $contact_code
                        ];                        
                        $check_exists = $this->Kontak_model->check_data_exist_items_two_condition($where_not, $where_in);
                        // var_dump($check_exists);die;
                    }else if($identity == 3){ //Karyawan
                        $check_exists = false;
                        $params_check['contact_identity_number'] = $contact_code;                        
                        $set_message = $contact_identity_number;
                        $check_exists = $this->Kontak_model->check_data_exist($params_check);
                    }
                    if($check_exists == false){
                        $params = array(
                            // 'contact_type' => $set_contact_type,
                            'contact_category_id' => !empty($this->input->post('categories')) ? $this->input->post('categories') : null,                    
                            'contact_code' => !empty($this->input->post('kode')) ? $this->input->post('kode') : null,
                            'contact_name' => !empty($this->input->post('nama')) ? $this->input->post('nama') : null,
                            'contact_address' => !empty($this->input->post('alamat')) ? $this->input->post('alamat') : null,
                            'contact_company' => !empty($this->input->post('perusahaan')) ? $this->input->post('perusahaan') : null,
                            'contact_phone_1' => !empty($this->input->post('telepon_1')) ? $this->input->post('telepon_1') : null,
                            'contact_phone_2' => !empty($this->input->post('telepon_2')) ? $this->input->post('telepon_2') : null,
                            'contact_email_1' => !empty($this->input->post('email_1')) ? $this->input->post('email_1') : null,
                            'contact_email_2' => !empty($this->input->post('email_2')) ? $this->input->post('email_2') : null,
                            'contact_date_updated' => date("YmdHis"),
                            'contact_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 1,
                            'contact_user_id' => $session_user_id,
                            'contact_branch_id' => $session_branch_id,
                            'contact_identity_type' => !empty($this->input->post('identity_type')) ? $this->input->post('identity_type') : 1,
                            'contact_identity_number' => !empty($this->input->post('identity_number')) ? $this->input->post('identity_number') : null,
                            'contact_fax' => !empty($this->input->post('fax')) ? $this->input->post('fax') : null,
                            'contact_npwp' => !empty($this->input->post('npwp')) ? $this->input->post('npwp') : null,
                            'contact_handphone' => !empty($this->input->post('handphone')) ? $this->input->post('handphone') : null,
                            // 'contact_note' => !empty($this->input->post('note')) ? $this->input->post('note') : null,
                            'contact_account_receivable_account_id' => !empty($this->input->post('akun_piutang')) ? $this->input->post('akun_piutang') : null,
                            'contact_account_payable_account_id' => !empty($this->input->post('akun_hutang')) ? $this->input->post('akun_hutang') : null,
                            // 'contact_name_mandarin' => $this->input->post('nama_mandarin'),
                            // 'contact_profesi' => $this->input->post('profesi'),
                            // 'contact_birth_of_date' => $this->input->post('tgl'),
                            'contact_url' => str_replace(' ', '-', strtolower($contact_name)),
                            'contact_gender' => !empty($this->input->post('gender')) ? $this->input->post('gender') : null,
                            'contact_birth_date' => !empty($this->input->post('tgl_lahir')) ? $this->input->post('tgl_lahir') : null,
                            'contact_birth_city_id' => !empty($this->input->post('tempat_lahir')) ? $this->input->post('tempat_lahir') : null,
                            'contact_parent_id' => !empty($this->input->post('kontak_parent')) ? $this->input->post('kontak_parent') : null,
                            'contact_city_id' => !empty($this->input->post('kota')) ? $this->input->post('kota') : null,
                            'contact_province_id' => !empty($this->input->post('provinsi')) ? $this->input->post('provinsi') : null,
                            'contact_termin' => !empty($this->input->post('contact_termin')) ? $this->input->post('contact_termin') : 0,
                            'contact_payable_limit' => !empty($this->input->post('contact_payable_limit')) ? $this->input->post('contact_payable_limit') : '0.00',
                            'contact_receivable_limit' => !empty($this->input->post('contact_receivable_limit')) ? $this->input->post('contact_receivable_limit') : '0.00'                    
                        );
                        $set_update=$this->Kontak_model->update_kontak($contact_id,$params);
                        if($set_update){

                            $get_data = $this->Kontak_model->get_kontak($contact_id);

                            //Croppie Upload Image
                            $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                            if(strlen($post_upload) > 10){
                                $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                                if($upload_process->status == 1){
                                    if ($get_data && $get_data['contact_id']) {
                                        $params_image = array(
                                            'contact_image' => $upload_process->result['file_location']
                                        );
                                        if (!empty($get_data['contact_image'])) {
                                            if (file_exists(FCPATH . $get_data['contact_image'])) {
                                                unlink(FCPATH . $get_data['contact_image']);
                                            }
                                        }
                                        $stat = $this->Kontak_model->update_kontak($contact_id, $params_image);
                                    }
                                }else{
                                    $return->message = 'Fungsi Gambar gagal';
                                }
                            }
                            //End of Croppie 

                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'contacts',
                                'activity_table_id' => $contact_id,
                                // 'activity_text_1' => strtoupper($contact_code),
                                'activity_text_2' => ucwords(strtolower($contact_name)).' '.strtoupper($contact_code),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            $return->status=1;
                            $return->message='Berhasil memperbarui '.$contact_name;
                        }
                    }else{
                        $return->message = 'Data '.$set_message.' sudah digunakan';
                    }
                    break;
                case "delete":
                    $return->message = 'Funtion not ready';
                    break;
                case "set-active":
                    $id = $this->input->post('id');
                    $kode = $this->input->post('kode');
                    $user = $this->input->post('user');
                    $flag = $this->input->post('flag');

                    if($flag==1){
                        $msg='aktifkan '.$user;
                        $act=7;
                    }else{
                        $msg='nonaktifkan '.$user;
                        $act=8;
                    }

                    $set_data=$this->Kontak_model->update_kontak($id,array('contact_flag'=>$flag));
                    if($set_data==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => $act,
                            'activity_table' => 'contacts',
                            'activity_table_id' => $id,
                            // 'activity_text_1' => strtoupper($kode),
                            'activity_text_2' => ucwords(strtolower($user)).' '.strtoupper($kode),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        $return->status=1;
                        $return->message='Berhasil '.$msg;
                    }
                    break;
                case "create-from-modal":
                    $post = $this->input->post('data');
                    $datas = json_decode($post, TRUE);

                    $_POST['nama'] = $datas['nama'];
                    $_POST['telepon'] = $datas['telepon_1'];

                    $this->form_validation->set_rules('nama', 'Nama', 'required');
                    $this->form_validation->set_rules('telepon', 'Telepon', 'required');                
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE) {
                        $return->message = validation_errors();
                    } else {
                        $get_account_payable = $this->get_account_map_for_transaction($session_branch_id,4,1); //Account Payable
                        $get_account_receivable = $this->get_account_map_for_transaction($session_branch_id,4,2); //Account Receivable
                        // var_dump($get_account_payable['account_id']);die;
                        $params = array(
                            'contact_type' => $datas['tipe'],
                            'contact_code' => !empty($datas['kode']) ? $datas['kode'] : null,
                            'contact_name' => !empty($datas['nama']) ? $datas['nama'] : null,
                            'contact_address' => !empty($datas['alamat']) ? $datas['alamat'] : null,
                            'contact_company' => !empty($datas['perusahaan']) ? $datas['perusahaan'] : null,
                            'contact_phone_1' => !empty($datas['telepon_1']) ? $datas['telepon_1'] : null,
                            // 'contact_phone_2' => !empty($datas['telepon_2']) ? $datas['telepon_2'] : null,
                            'contact_email_1' => !empty($datas['email_1']) ? $datas['email_1'] : null,
                            // 'contact_email_2' => !empty($datas['email_2']) ? $datas['email_2'] : null,
                            'contact_date_created' => date("YmdHis"),
                            'contact_date_updated' => date("YmdHis"),
                            'contact_flag' => 1,
                            'contact_user_id' => $session_user_id,
                            'contact_branch_id' => $session_branch_id,
                            'contact_account_receivable_account_id' => $get_account_receivable['account_id'],
                            'contact_account_payable_account_id' => $get_account_payable['account_id'],
                            'contact_session' => $this->random_code(8)                                            
                        );
                        // var_dump($params);die;

                        /*
                            1=Supplier, 2=Customer, 3=Karyawan, 4=Patient, 5=Insurance
                        */
                        if($datas['tipe']==1){ $msg = 'Supplier'; }
                        else if($datas['tipe']==2){ $msg = 'Customer'; }
                        else if($datas['tipe']==3){ $msg = 'Karyawan'; }
                        else{ $msg = ''; }
                        $msg = $this->folder_location[$datas['tipe']]['title'];
                                            
                        //Check Data Exist
                        $params_check = array(
                            'contact_type' => $datas['tipe'],
                            'contact_name' => $datas['nama'],
                            'contact_branch_id' => $session_branch_id
                        );
                        // var_dump($params_check);die;
                        $check_exists = $this->Kontak_model->check_data_exist($params_check);
                        if($check_exists==false){
                            $set_data=$this->Kontak_model->add_kontak($params);
                            if($set_data==true){
                                //Aktivitas
                                $params = array(
                                    'activity_user_id' => $session_user_id,
                                    'activity_branch_id' => $session_branch_id,
                                    'activity_action' => 2,
                                    'activity_table' => 'contacts',
                                    'activity_table_id' => $set_data,
                                    // 'activity_text_1' => strtoupper($datas['nama']),
                                    'activity_text_2' => ucwords(strtolower($datas['nama'])),
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 1
                                );
                                $this->save_activity($params);
                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$msg.' '.$datas['nama'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'kode' => !empty($datas['kode']) ? $datas['kode'] : null,
                                    'nama' => !empty($datas['nama']) ? $datas['nama'] : null,
                                    'telepon_1' => !empty($datas['telepon_1']) ? $datas['telepon_1'] : null
                                );
                            }
                        }else{
                            $return->message='Data '.$msg.' sudah ada';
                        }
                    }
                    break;
                case "info":
                    $id = $this->input->post('id');

                    //Contact Data
                    $get_contact = $this->Kontak_model->get_kontak($id);
                    $set_contact = array(
                        'contact_id' => $get_contact['contact_id'],
                        'contact_branch_id' => $get_contact['contact_branch_id'],
                        'contact_type' => $get_contact['contact_type'],
                        'contact_code' => $get_contact['contact_code'],
                        'contact_name' => $get_contact['contact_name'],
                        'contact_address' => $get_contact['contact_address'],
                        'contact_phone_1' => $get_contact['contact_phone_1'],
                        'contact_email_1' => $get_contact['contact_email_1'],
                        'contact_company' => $get_contact['contact_company'],
                        'contact_date_created' => $get_contact['contact_date_created'],
                        'contact_date_updated' => $get_contact['contact_date_updated'],
                        'receivable_account_id' => $get_contact['receivable_account_id'],
                        'receivable_account_code' => $get_contact['receivable_account_code'],
                        'receivable_account_name' => $get_contact['receivable_account_name'],
                        'payable_account_id' => $get_contact['payable_account_id'],
                        'payable_account_code' => $get_contact['payable_account_code'],
                        'payable_account_name' => $get_contact['payable_account_name'],
                    );        

                    //Order Data
                    $get_order = $this->Order_model->get_all_orders(array('order_contact_id'=>$id),null,5,0,'order_date','desc');
                    $set_order = array();
                    if($get_order){
                        foreach($get_order as $o){
                            $set_order[] = array(
                                "order_id" => $o["order_id"],
                                "order_type" => $o["order_type"],
                                "order_number" => $o["order_number"],
                                "order_date" => $o["order_date"],
                                "order_date_format" => date("d-M-Y, H:i", strtotime($o["order_date"])),                            
                                "order_date_due" => $o["order_date_due"],
                                "order_contact_id" => $o["order_contact_id"],
                                "order_ppn" => $o["order_ppn"],
                                "order_discount" => $o["order_discount"],
                                "order_total" => $o["order_total"],
                                "order_total_format" => number_format($o["order_total"],2,',','.'),                            
                                "order_note" => $o["order_note"],
                                "order_user_id" => $o["order_user_id"],
                                "order_ref_id" => $o["order_ref_id"],
                                "order_date_created" => $o["order_date_created"],
                                "order_url" => base_url('order/print/').$o["order_session"]
                            );
                        }
                    }

                    //Trans Data
                    $get_trans = $this->Transaksi_model->get_all_transaksis(array('trans_contact_id'=>$id),null,5,0,'trans_date','asc');
                    $set_trans = array();
                    if($get_trans){
                        foreach($get_trans as $o){
                            $set_trans[] = array(
                                "trans_id" => $o["trans_id"],
                                "trans_type" => $o["trans_type"],
                                "trans_number" => $o["trans_number"],
                                "trans_date" => $o["trans_date"],
                                "trans_date_format" => date("d-M-Y, H:i", strtotime($o["trans_date"])),                                   
                                "trans_date_due" => $o["trans_date_due"],
                                "trans_contact_id" => $o["trans_contact_id"],
                                "trans_ppn" => $o["trans_ppn"],
                                "trans_discount" => $o["trans_discount"],
                                "trans_total" => $o["trans_total"],
                                "trans_total_format" => number_format($o["trans_total"],2,',','.'),                            
                                "trans_note" => $o["trans_note"],
                                "trans_user_id" => $o["trans_user_id"],
                                "trans_ref_id" => $o["trans_ref_id"],
                                "trans_date_created" => $o["trans_date_created"],
                                "trans_url" => base_url('transaksi/print_history/').$o["trans_session"]                    
                            );
                        }
                    }

                    //Journal Data

                    $return->result = array(
                        'contact' => $set_contact,
                        'order' => $set_order,
                        'trans' => $set_trans,
                        'journal' => array('message' => 'Not Ready'),
                    );            
                    $return->status=1;    
                    break;
				case "import_from_excel":
					$this->form_validation->set_rules('contact_type', 'Tipe', 'required');
					$this->form_validation->set_message('required', '{field} wajib diisi');
					if ($this->form_validation->run() == FALSE) {
						$return->message = validation_errors();
					} else {
						$return->data_exists = array();
						$return->total_exists = 0;

						$total_exists = 0;
						$data_exists = array();

						$contact_type = $this->input->post('contact_type');
						$contact_group_session = $this->random_code(8);

						//Excel is comming
						if (isset($_FILES["excel_file"]["name"])) {
							$file_tmp   = $_FILES['excel_file']['tmp_name'];
							$file_name  = $_FILES['excel_file']['name'];
							$file_size  = $_FILES['excel_file']['size'];
							$file_type  = $_FILES['excel_file']['type'];

							$object = PHPExcel_IOFactory::load($file_tmp);
							$sheets = 0;

                            $get_account_payable = $this->get_account_map_for_transaction($session_branch_id,4,1); //Account Payable
                            $get_account_receivable = $this->get_account_map_for_transaction($session_branch_id,4,2); //Account Receivable
                            
							//Foreach
							foreach ($object->getWorksheetIterator() as $worksheet) {

								//Sheet 1
								if ($sheets == 0) {
									$highest_row    = $worksheet->getHighestRow();
									$highest_column = $worksheet->getHighestColumn();
									$numbers = 0;
									for ($row = 3; $row <= $highest_row; $row++) {
										
                                        if ($contact_type == 1) { //Supplier
											$contact_code = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
											$contact_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
											$contact_address = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
											$contact_phone = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
											$contact_email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                                            $contact_employee_number = null;
                                            $params_check = [
                                                'contact_type' => $contact_type,
                                                'contact_branch_id' => $session_branch_id,
                                                'contact_code' => $contact_code
                                            ];
                                        }else if ($contact_type == 2) { //Customer
											$contact_code = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
											$contact_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
											$contact_address = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
											$contact_phone = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
											$contact_email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                                            $contact_employee_number = null;
                                            $params_check = [
                                                'contact_type' => $contact_type,
                                                'contact_branch_id' => $session_branch_id,
                                                'contact_code' => $contact_code
                                            ];                                              
                                        }else if ($contact_type == 3) { //Karyawan
											$contact_code = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
											$contact_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
											$contact_address = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
											$contact_phone = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
											$contact_email = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
											$contact_employee_number = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

                                            $params_check = [
                                                'contact_type' => $contact_type,
                                                'contact_identity_number' => $contact_employee_number,
                                                'contact_branch_id' => $session_branch_id
                                            ];                                                              
                                        }

										//Check data exist
                                        $check_exists = $this->Kontak_model->check_data_exist($params_check, null);
                                        if ($check_exists) {
                                            $data_exists[] = array('contact_identity_number' => $contact_employee_number);
                                            $total_exists++;
                                        } else {
                                            $params[] = array(
                                                'contact_branch_id' => $session_branch_id,
                                                'contact_user_id' => $session_user_id,
                                                'contact_type' => $contact_type,
                                                'contact_code' => !empty($contact_code) ? $contact_code : null,
                                                'contact_name' => !empty($contact_name) ? $contact_name : null,
                                                'contact_address' => !empty($contact_address) ? $contact_address : null,
                                                'contact_phone_1' => !empty($contact_phone) ? $contact_phone : null,
                                                'contact_email_1' => !empty($contact_email) ? $contact_email : null,
                                                'contact_account_receivable_account_id' => $get_account_receivable['account_id'],
                                                'contact_account_payable_account_id' => $get_account_payable['account_id'],
                                                'contact_date_created' => date("YmdHis"),
                                                'contact_flag' => 1,
                                                'contact_identity_type' => 1,
                                                'contact_identity_number' => !empty($contact_employee_number) ? $contact_employee_number : null,
                                                'contact_session' => $this->random_code(6),
                                                'contact_group_session' => $contact_group_session,
                                            );
                                        }
                                        $numbers++;
										// }
									} //End looping
								}
								$sheets++;
							}

							if ($total_exists == 0) {
								// Bulk Insert Process
								if ($this->db->insert_batch('contacts', $params)) {
									$params = array(
										'contact_group_session' => $contact_group_session
									);
									$get_count = $this->Kontak_model->get_all_kontak_count($params, null, null);

									$return->status = 1;
									$return->message = 'Berhasil import ' . $get_count . ' dari ' . $numbers . ' data';
									$return->result = array(
										'contact_group_session' => $contact_group_session
									);
								} else {
									$return->message = 'Gagal import';
								}
							} else {
								$return->message = 'Gagal import, terdapat ' . $total_exists . ' data yg sudah ada, mohon diperiksa kembali';
								$return->data_exists = $data_exists;
								$return->total_exists = $total_exists;
							}
						} else {
							$return->message = 'Excel not found';
						}
					}
					break;                    
                default:
                    $return->message = 'No Action';
                    break;
            }
        }
        $return->action=$action;
        echo json_encode($return);
    }
    function prints(){
        $session = $this->session->userdata();
        $session_branch_id  = intval($session['user_data']['branch']['id']);
        $session_user_id    = intval($session['user_data']['user_id']);      
        $session_user_group = intval($session['user_data']['user_group_id']);

        //Smart Dashboard Rule
        $rule = array('1','3'); //1=Root /SuperAdmin, 3=Director
        if(in_array($session_user_group,$rule)){
            $data['show_price'] = 0;
        }else{
            $data['show_price'] = 0;
        }

        $cat        = $this->input->get('cat');
        $type       = $this->input->get('type');  
        $flag       = $this->input->get('flag');
        $order      = $this->input->get('order'); 
        $dir        = $this->input->get('dir');                        

        $columns = array(
            '1' => 'contact_name',
            '2' => 'contact_code',
            '3' => 'contact_company',
            '4' => 'category_name'
        );
        $columns_dir = array(
            '0' => 'asc',
            '1' => 'desc'
        );
        $column_type = array(
            '0' => 'Semua',
            '1' => 'Supplier',
            '2' => 'Customer',
            '3' => 'Karyawan'
        );

        $data['branch'] = $this->Branch_model->get_branch($session_branch_id);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch($session_branch_id);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $limit  = 0;
        $start  = 0;
        $order  = $columns[$order];
        $dir    = $columns_dir[$dir];
        $search = null;

        $params_datatable = array();  
        $datas            = array();

        if(intval($cat) > 0){
            $get_cat = $this->Kategori_model->get_categories(intval($cat));
            // $search['category_product_id'] = intval($cat);
            $params_datatable['contact_category_id'] = intval($cat);            
            $data['category'] = $get_cat['category_name'];
        }

        if(intval($type) > 0){
            $params_datatable['contact_type'] = intval($type);             
            $data['type'] = $column_type[$type];
        }        

        if((is_numeric($flag)) && (intval($flag) > 0)){
            $params_datatable['contact_flag'] = intval($flag);
            if($flag==1){
                $data['flag'] = 'Aktif';
            }else if($flag==0){
                $data['flag'] = 'NonAktif';
            }
        }       

        $get_count = $this->Kontak_model->get_all_kontak_count($params_datatable,$search);
        $limit = $get_count;
        if($get_count > 0){
            $get_data = $this->Kontak_model->get_all_kontaks($params_datatable, $search, $limit, $start, $order, $dir);
            foreach($get_data as $v){

                $type_name = '-';
                if(intval($v['contact_type']) ==0){
                    $type_name = '-';
                }else if(intval($v['contact_type']) > 0){
                    $type_name = $column_type[$v['contact_type']];
                }

                $flag_name = '-';
                if(intval($v['contact_flag']) ==0){
                    $flag_name = 'Nonaktif';
                }else if(intval($v['contact_flag']) == 1){
                    $flag_name = 'Aktif';
                }else if(intval($v['contact_type']) == 4){
                    $flag_name = 'Terhapus';
                }
                                
                $datas[] = array(
                    'contact_id' => $v['contact_id'],
                    'contact_code' => $v['contact_code'],
                    'contact_name' => $v['contact_name'],
                    'contact_address' => $v['contact_address'],
                    'contact_phone_1' => $v['contact_phone_1'],
                    'contact_email_1' => $v['contact_email_1'],
                    'contact_company' => $v['contact_company'],
                    'contact_identity_number' => $v['contact_identity_number'],                                                                                
                    // 'contact_stock' => $v['contact_stock'],                                        
                    'contact_note' => $v['contact_note'],
                    'contact_url' => site_url().$v['contact_url'],
                    'contact_image' => site_url().$v['contact_image'],
                    'contact_type' => $v['contact_type'],
                    'contact_type_name' => $type_name,
                    // 'contact_price_buy' => $v['contact_price_buy'],
                    // 'contact_price_sell' => $v['contact_price_sell'],
                    'contact_flag' => $v['contact_flag'],
                    'contact_flag_name' => $flag_name,
                    'contact_termin' => $v['contact_termin'],
                    'contact_payable_limit' => $v['contact_payable_limit'],
                    'contact_payable_running' => $v['contact_payable_running'],
                    'contact_payable_paid' => $v['contact_payable_paid'],
                    'contact_payable_balance' => $v['contact_payable_balance'],
                    'contact_receivable_limit' => $v['contact_receivable_limit'],
                    'contact_receivable_running' => $v['contact_receivable_running'],
                    'contact_receivable_paid' => $v['contact_receivable_paid'],
                    'contact_receivable_balance' => $v['contact_receivable_balance'],
                    'category_name' => $v['category_name']
                    // 'category_name' => $v['category_name'],
                    // 'price' => $this->Product_price_model->get_all_product_price(array('product_price_product_id'=>$v['product_id']),null,null,null,'product_price_price','asc'),
                    // 'image' => $this->Product_item_model->get_all_product_item(array('product_item_product_id'=>$v['product_id']),null,null,null,null,null)
                 );
            }

            $params = array(
                'activity_user_id' => $session['user_data']['user_id'],
                'activity_action' => 6,
                'activity_table' => 'contacts',
                'activity_table_id' => null,
                'activity_text_1' => $type_name,
                'activity_text_2' => 'Data '.$type_name,
                'activity_date_created' => date('YmdHis'),
                'activity_flag' => 0
            );
            $this->save_activity($params);                
        }else{
            $datas = array();
        }
        // var_dump($datas);die;
        $data['content'] = $datas;
        $data['title'] = 'Print Data '.$type_name;
        $data['url'] = site_url();
        $data['description'] = '';
        $data['author'] = 'Author';
        $data['image'] = $data['branch_logo'];        
        $data['price_item'] = 1;
        $data['type_name'] = $type_name;
        $this->load->view('layouts/admin/menu/prints/data/print_contact_'.strtolower($type_name),$data);   
    }    
}
