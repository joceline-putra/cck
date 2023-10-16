<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends MY_Controller{

    var $folder_upload = 'upload/notify/';

    var $api_url_callback   = 'https://notif.mediadigital.id/callback/cekmutasi';
    var $api_url_create     = 'https://api.cekmutasi.co.id/v1/bank/add';
    var $api_url_update     = 'https://api.cekmutasi.co.id/v1/bank/update';
    var $api_url_delete     = 'https://api.cekmutasi.co.id/v1/bank/delete';    
    var $api_token          = 'e24d433c603dc30507fa9165bc4309d060dfd50d423f5'; //NotUsed
    
    var $image_width   = 250;
    var $image_height  = 250;
    var $folder_location = array(
        '1' => array(
            'parent_id' => 1,              
            'title' => 'Bank',
            'view' => 'layouts/admin/menu/notify/bank',
            'javascript' => 'layouts/admin/menu/notify/bank_js'
        ),
        '2' => array(
            'parent_id' => 2,              
            'title' => 'Mutasi',
            'view' => 'layouts/admin/menu/notify/mutation',
            'javascript' => 'layouts/admin/menu/notify/mutation_js'
        ),        
        '3' => array(
            'parent_id' => 3,              
            'title' => 'Saldo',
            'view' => 'layouts/admin/menu/notify/balance',
            'javascript' => 'layouts/admin/menu/notify/balance_js'
        ),
        '4' => array(
            'parent_id' => 4,              
            'title' => 'Deposit',
            'view' => 'layouts/admin/menu/notify/deposit',
            'javascript' => 'layouts/admin/menu/notify/deposit_js'
        )        
    );
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }
        $this->print_to         = 0; //0 = Local, 1 = Bluetooth
        $this->whatsapp_config  = 1;

        $this->load->model('User_model');
        $this->load->model('Bank_model');  
        $this->load->model('Mutation_model');
        $this->load->model('Balance_model');        
    }
    function pages($identity){

        $data['session'] = $this->session->userdata();
        $data['theme']  = $this->User_model->get_user($data['session']['user_data']['user_id']);

        $data['identity']   = $identity;
        $data['title']      = $this->folder_location[$identity]['title'];
        $data['_view']      = $this->folder_location[$identity]['view'];
        $file_js            = $this->folder_location[$identity]['javascript'];
        
        $data['operator']        = '';
        $data['post_order']      = 0;
        $data['post_trans']      = 0;
        $data['post_contact']    = 0;
        $data['whatsapp_config'] = $this->whatsapp_config;
        $data['print_to']        = $this->print_to; //0 = Local, 1 = Bluetooth

        // Date
        $firstdate              = new DateTime('first day of this month');
        $datenow                = date("Y-m-d"); 
        $data['first_date']     = date('d-m-Y', strtotime($firstdate->format('Y-m-d')));
        $data['end_date']       = date('d-m-Y', strtotime($datenow));
        $data['end_date_due']   = date('d-m-Y', strtotime('+30 days',strtotime($datenow)));

        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }    
    function index(){
        if ($this->input->post()) {    
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
            $session_user_group_id = !empty($data['session']['user_data']['user_group_id']) ? $data['session']['user_data']['user_group_id'] : null;            
            $session_user_session = !empty($data['session']['user_data']['user_session']) ? $data['session']['user_data']['user_session'] : null;

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "bank_load":
                    $columns = array(
                        '0' => 'bank_id',
                        '1' => 'bank_name'
                    );
                    $limit     = $this->input->post('length');
                    $start     = $this->input->post('start');
                    $order     = $columns[$this->input->post('order')[0]['column']];
                    $dir       = $this->input->post('order')[0]['dir'];
                    $search    = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array();
                    if(intval($session_user_id) > 1){// Only Root
                        $params['bank_user_session'] = $session_user_session;
                    }
                    // $params['bank_category_id'] = !empty($this->input->post('filter_category')) ? $this->input->post('filter_category') : $params;                

                    $params['bank_flag']   = $this->input->post('filter_flag');
                    if($this->input->post('filter_category')) {
                        $params['bank_category_id']   = !empty($this->input->post('filter_category')) ? $this->input->post('filter_category') : $params;
                    }
                    // var_dump($params);die;
                    $datas = array();
                    $get_datas = $this->Bank_model->get_all_bank($params, $search, $limit, $start, $order, $dir);
                    foreach($get_datas as $v):
                        $datas[] = array(
                            'category_name' => $v['category_name'],
                            'bank_account_number' => $v['bank_account_number'],
                            'bank_account_name' => $v['bank_account_name'],
                            'bank_flag' => intval($v['bank_flag']),
                            'bank_session' => $v['bank_session'],
                            'user_access' => intval($session_user_id),
                            'bank_api_id' => $v['bank_api_id'],
                            'bank_api_package' => $v['bank_api_package'],
                            'bank_api_last_check' => !empty($v['bank_api_last_check']) ? $v['bank_api_last_check'] : 0,
                            'bank_api_last_check_format' => $this->time_ago($v['bank_api_last_check'])
                        );
                    endforeach;

                    if(isset($datas)){ //Fetch All Datatable if exist
                        $return->total_records   = !empty($datas) ? count($datas) : 0;                
                        $return->status          = 1; 
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;
                    }else{ // Datatable not found
                        $return->total_records   = !empty($datas) ? count($datas) : 0;                
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;                
                    }
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    $return->action              = $action;
                    $return->params              = $params;
                    break;     
                case "bank_create":

                    // $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    // $data = json_decode($post_data, TRUE);

                    $name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;
                    $number = !empty($this->input->post('nomor_rekening')) ? $this->input->post('nomor_rekening') : null;
                    $bisnis = !empty($this->input->post('account_bisnis')) ? $this->input->post('account_bisnis') : null;
                    $username = !empty($this->input->post('username')) ? $this->input->post('username') : null;
                    $password = !empty($this->input->post('password')) ? $this->input->post('password') : null;
                    $phone = !empty($this->input->post('telepon')) ? $this->input->post('telepon') : null;
                    $email = !empty($this->input->post('email')) ? $this->input->post('email') : null;
                    $category = !empty($this->input->post('category')) ? $this->input->post('category') : null;
                    // $interval = !empty($this->input->post('interval')) ? $this->input->post('interval') : null;
                    $interval = 3;                                                                                                                                                                                    
                    $status = !empty($this->input->post('status')) ? $this->input->post('status') : 1;

                    $params = array(
                        'bank_session' => $this->random_session(20),
                        'bank_category_id' => $category,
                        'bank_account_name' => $name,
                        'bank_account_number' => $number,
                        'bank_account_username' => $username,
                        'bank_account_password' => $password,
                        'bank_account_business' => $bisnis,
                        'bank_minute_interval' => $interval,
                        'bank_email_notification' => $email,
                        'bank_phone_notification' => $phone,
                        'bank_user_session' => $session_user_session,
                        'bank_date_created' => date("YmdHis"),
                        'bank_flag' => $status
                    );

                    //Check Data Exist
                    $params_check = array(
                        'bank_account_number' => $number
                    );
                    $check_exists = $this->Bank_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Bank_model->add_bank($params);
                        if($set_data==true){


                            $data = $this->Bank_model->get_bank($set_data);

                            /* Start Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => 2,
                                'activity_table' => 'bank',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => strtoupper($data['kode']),
                                'activity_text_2' => ucwords(strtolower($data['nama'])),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);    
                            */
                            /* End Activity */            
                            $return->status=1;
                            $return->message='Berhasil menambahkan';
                            $return->result= array(
                                'id' => $set_data,
                                'session' => $data['bank_session']
                            ); 
                        }
                    }else{
                        $return->message='Data sudah ada';                    
                    }
                    $return->action=$action;
                    echo json_encode($return);                   
                    break;
                case "bank_read":  
                    $bank_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;   
                    $bank_session = !empty($this->input->post('session')) ? $this->input->post('session') : null;                       
                    if(intval(strlen($bank_session)) > 0){        
                        $where = array(
                            'bank_session' => $bank_session
                        );
                        $datas = $this->Bank_model->get_bank_custom($where);
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'banks',
                                'actvity_table_id' => $bank_id,
                                'actvity_text_1' => '',
                                'actvity_text_2' => ucwords(strtolower($datas['username'])),
                                'actvity_date_created' => date('YmdHis'),
                                'actvity_flag' => 0
                            );
                            $this->save_activity($params);                    
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil mendapatkan data';
                            $return->result=$datas;
                        }else{
                            $message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message='Data tidak ditemukan ';
                    }
                    $return->action=$action;
                    echo json_encode($return);                               
                    break;
                case "bank_update":
                    $session = !empty($this->input->post('session')) ? $this->input->post('session') : null;
                    $name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;
                    $number = !empty($this->input->post('nomor_rekening')) ? $this->input->post('nomor_rekening') : null;
                    $bisnis = !empty($this->input->post('account_bisnis')) ? $this->input->post('account_bisnis') : null;
                    $username = !empty($this->input->post('username')) ? $this->input->post('username') : null;
                    $password = !empty($this->input->post('password')) ? $this->input->post('password') : null;
                    $phone = !empty($this->input->post('telepon')) ? $this->input->post('telepon') : null;
                    $email = !empty($this->input->post('email')) ? $this->input->post('email') : null;
                    $category = !empty($this->input->post('category')) ? $this->input->post('category') : null;
                    // $interval = !empty($this->input->post('interval')) ? $this->input->post('interval') : null;                                                                                                                                                                                    
                    $status = !empty($this->input->post('status')) ? $this->input->post('status') : 1;

                    //For API
                    $set_status = !empty($status == 1) ? 'ACTIVE' : 'INACTIVE';
                    $set_password = !empty(strlen($password) > 0) ? true : false;

                    $where = array(
                        'bank_session' => $session
                    );

                    //Get Old Data 
                    $get_data = $this->Bank_model->get_bank_custom($where);

                    //Password
                    if($set_password==true){
                        $set_password = $password;
                    }else{
                        $set_password = $get_data['bank_account_password'];
                    }

                    $params = array(
                        'bank_category_id' => $category,
                        'bank_account_name' => $name,
                        'bank_account_number' => $number,
                        // 'bank_account_username' => $username,
                        'bank_account_password' => $set_password,
                        'bank_account_business' => $bisnis,
                        // 'bank_minute_interval' => $interval,
                        'bank_email_notification' => $email,
                        'bank_phone_notification' => $phone,
                        // 'bank_user_session' => $session_user_session,
                        'bank_date_updated' => date("YmdHis"),
                        'bank_flag' => $status
                    );


                    $set_update=$this->Bank_model->update_bank_custom($where,$params);
                    if($set_update==true){
                        
                        $data = array(
                            // "service_code"      => $get_bank['category_code'],
                            // "package_code"      => "basic",
                            "id"                => $get_data['bank_api_id'],
                            // "username"          => $username,
                            "password"          => $set_password,
                            "account_number"    => $number,
                            "account_name"      => $name,
                            "status"            => "ACTIVE"
                        );

                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL             => $this->api_url_update,
                            CURLOPT_POST            => true,
                            CURLOPT_POSTFIELDS      => http_build_query($data),
                            CURLOPT_HTTPHEADER      => ["Api-Key: e24d433c603dc30507fa9165bc4309d060dfd50d423f5", "Accept: application/json"], // tanpa tanda kurung
                            CURLOPT_RETURNTRANSFER  => true,
                            CURLOPT_HEADER          => false,
                            CURLOPT_IPRESOLVE       => CURL_IPRESOLVE_V4,
                        ));
                        $res = curl_exec($ch);
                        //curl_close($ch);
                        
                        $result = json_decode($res,true);

                        if($result['success']==true){
                            $where = array(
                                'bank_session' => $session,
                                'bank_account_number' => $result['data']['account_number']
                            );
                            // $params = array(
                                // 'bank_api_id' => $result['data']['id'],
                                // 'bank_api_package' => $result['data']['package']
                            // );
                            // $update=$this->Bank_model->update_bank_custom($where,$params);
                            $return->status = 1;
                            $return->result = array(
                                'id' => $result['data']['id'],
                                'session' => $session
                            );
                            $return->message='Berhasil memperbarui';
                        }else{
                            $return->message= !empty($result['error_message']) ? $result['error_message'] : 'Gagal memperbarui';
                        }
                        // var_dump('S'.$result);die;  

                        $return->status  = 1;
                        $return->message = 'Berhasil memperbarui '.$number.' ['.$name.']';
                    }else{
                        $return->message='Gagal memperbarui '.$number.' ['.$name.']';
                    }    
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;          
                case "bank_delete":
                    //$post_data = $this->input->post('data');
                    //$data = json_decode($post_data, TRUE);            
                    $bank_session   = !empty($this->input->post('session')) ? $this->input->post('session') : 0;
                    $bank_number = !empty($this->input->post('number')) ? $this->input->post('number') : null;

                    if(intval($bank_number) > 0){
                        $get_data=$this->Bank_model->get_bank_custom(array('bank_session'=>$bank_session));
                        $where = array(
                            'bank_session' => $get_data['bank_session'],
                            'bank_account_number' => $get_data['bank_account_number']
                        );
                        $set_data=$this->Bank_model->delete_bank_custom($where);
                        if($set_data==true){
                            $data = array(
                                "id"      => $set_data['bank_api_id']
                            );

                            $ch = curl_init();
                            curl_setopt_array($ch, array(
                                CURLOPT_URL             => $this->api_url_delete,
                                CURLOPT_POST            => true,
                                CURLOPT_POSTFIELDS      => http_build_query($data),
                                CURLOPT_HTTPHEADER      => ["Api-Key: e24d433c603dc30507fa9165bc4309d060dfd50d423f5", "Accept: application/json"], // tanpa tanda kurung
                                CURLOPT_RETURNTRANSFER  => true,
                                CURLOPT_HEADER          => false,
                                CURLOPT_IPRESOLVE       => CURL_IPRESOLVE_V4,
                            ));
                            $res = curl_exec($ch);                            
                            /*
                            if (file_exists($get_data['bank_image'])) {
                                unlink($get_data['bank_image']);
                            } 
                            */                            
                            /* Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => $act,
                                'activity_table' => 'banks',
                                'activity_table_id' => $id,
                                'activity_text_1' => '',
                                'activity_text_2' => ucwords(strtolower($bank_name)),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);                               
                            */
                            /* End Activity */
                            $result = json_decode($res,true);
                            if($result['success']==true){
                                // $where = array(
                                //     'bank_session' => $session,
                                //     'bank_account_number' => $result['data']['account_number']
                                // );
                                // $params = array(
                                //     'bank_api_id' => $result['data']['id'],
                                //     'bank_api_package' => $result['data']['package']
                                // );
                                // $update=$this->Bank_model->update_bank_custom($where,$params);
                                $return->status = 1;
                                $return->result = array(
                                    'id' => $result['data']['id'],
                                    'session' => $session
                                );
                                $return->message='Berhasil menghapus';
                            }else{
                                $return->message= !empty($result['error_message']) ? $result['error_message'] : 'Gagal menghapus';
                            }                            
                            $return->status=1;
                            $return->message='Berhasil menghapus'.$bank_number;
                        }else{
                            $return->message='Gagal menghapus '.$bank_number;
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;             
                case "bank_register_cek_mutasi": die;
                    $session = !empty($this->input->post('session')) ? $this->input->post('session') : null;
                    // die;
                    if(!empty($session)){
                        $get_bank = $this->Bank_model->get_bank_custom(array('bank_session' => $session));
                        $data = array(
                            "service_code"      => $get_bank['category_code'],
                            "package_code"      => "basic",
                            "username"          => $get_bank['bank_account_username'],
                            "password"          => $get_bank['bank_account_password'],
                            "account_number"    => $get_bank['bank_account_number'],
                            "account_name"      => $get_bank['bank_account_name'],
                            "ipn_url"           => $this->api_url_callback,
                            "status"            => "ACTIVE"
                        );

                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL             => $this->api_url_create,
                            CURLOPT_POST            => true,
                            CURLOPT_POSTFIELDS      => http_build_query($data),
                            CURLOPT_HTTPHEADER      => ["Api-Key: e24d433c603dc30507fa9165bc4309d060dfd50d423f5", "Accept: application/json"], // tanpa tanda kurung
                            CURLOPT_RETURNTRANSFER  => true,
                            CURLOPT_HEADER          => false,
                            CURLOPT_IPRESOLVE       => CURL_IPRESOLVE_V4,
                        ));
                        $res = curl_exec($ch);
                        
                        $result = json_decode($res,true);
                        if($result['success']==true){
                            $where = array(
                                'bank_session' => $session,
                                'bank_account_number' => $result['data']['account_number']
                            );
                            $params = array(
                                'bank_api_id' => $result['data']['id'],
                                'bank_api_package' => $result['data']['package']
                            );
                            $update=$this->Bank_model->update_bank_custom($where,$params);
                            $return->status = 1;
                            $return->result = array(
                                'id' => $result['data']['id'],
                                'session' => $session
                            );
                            $return->message='Berhasil mendaftarkan';
                        }else{
                            $return->message= !empty($result['error_message']) ? $result['error_message'] : 'Gagal mendaftar';
                        }
                        echo json_encode($return);
                    }
                    break;   
                case "mutation_create":

                    // $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    // $data = json_decode($post_data, TRUE);

                    $mutation_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;
                    $mutation_status = !empty($this->input->post('status')) ? $this->input->post('status') : 1;

                    $params = array(
                        'mutation_name' => $mutation_name,
                        'mutation_flag' => $mutation_status
                    );

                    //Check Data Exist
                    $params_check = array(
                        'mutation_name' => $mutation_name
                    );
                    $check_exists = $this->Mutation_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Mutation_model->add_mutation($params);
                        if($set_data==true){


                            $data = $this->Mutation_model->get_mutation($mutation_id);
                            //Save Image if Exist
                            $config['image_library'] = 'gd2';
                            $config['upload_path'] = $upload_path_directory;
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $this->load->library('upload', $config);
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
                                    $compress['width'] = 640;
                                    $compress['height'] = 640;
                                    $compress['new_image'] = $upload_path_directory . $raw_photo;
                                    $this->load->library('image_lib', $compress);
                                    $this->image_lib->resize();

                                    if ($data && $data['mutation_id']) {
                                        $params_image = array(
                                            'mutation_image' => base_url($upload_directory) . $raw_photo
                                        );
                                        if (!empty($data['mutation_image'])) {
                                            if (file_exists($upload_path_directory . $data['mutation_image'])) {
                                                unlink($upload_path_directory . $data['mutation_image']);
                                            }
                                        }
                                        $stat = $this->Mutation_model->update_mutation_no_sp(array('mutation_id' => $set_data), $params_image);
                                    }
                                }
                            }
                            //End of Save Image

                            /* Start Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => 2,
                                'activity_table' => 'mutation',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => strtoupper($data['kode']),
                                'activity_text_2' => ucwords(strtolower($data['nama'])),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
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
                        $return->message='Data sudah ada';                    
                    }
                    $return->action=$action;
                    echo json_encode($return);                   
                    break;
                case "mutation_read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $mutation_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;   
                    if(intval($mutation) > 0){        
                        $datas = $this->Mutation_model->get_mutation($mutation_id);
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'mutations',
                                'actvity_table_id' => $mutation_id,
                                'actvity_text_1' => '',
                                'actvity_text_2' => ucwords(strtolower($datas['username'])),
                                'actvity_date_created' => date('YmdHis'),
                                'actvity_flag' => 0
                            );
                            $this->save_activity($params);                    
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil mendapatkan data';
                            $return->result=$datas;
                        }else{
                            $message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message='Data tidak ditemukan ';
                    }
                    $return->action=$action;
                    echo json_encode($return);                               
                    break;
                case "mutation_update":
                    //$post_data = $this->input->post('data');
                    //$data = json_decode($post_data, TRUE);
                    $mutation_id = !empty($this->input->post('id')) ? $this->input->post('id') : $data['id'];
                    $mutation_name = !empty($this->input->post('name')) ? $this->input->post('name') : $data['name'];
                    $mutation_flag = !empty($this->input->post('status')) ? $this->input->post('status') : $data['status'];

                    $params = array(
                        'mutation_name' => $mutation_name,
                        'mutation_date_updated' => date("YmdHis"),
                        'mutation_flag' => $mutation_flag
                    );

                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */
                   
                    $set_update=$this->Mutation_model->update_mutation($mutation_id,$params);
                    if($set_update==true){
                        
                        $data = $this->Mutation_model->get_mutation(mutation_id);
                            
                        //Update Image if Exist
                        $config['image_library'] = 'gd2';
                        $config['upload_path'] = $upload_path_directory;
                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                        $this->load->library('upload', $config);
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
                                $compress['width'] = 640;
                                $compress['height'] = 640;
                                $compress['new_image'] = $upload_path_directory . $raw_photo;
                                $this->load->library('image_lib', $compress);
                                $this->image_lib->resize();
                                if ($data && $mutation_id) {
                                    $params_image = array(
                                        'mutation_image' => base_url($upload_directory) . $raw_photo
                                    );
                                    if (!empty($data['mutation_image'])) {
                                        if (file_exists($upload_path_directory . $data['mutation_image'])) {
                                            unlink($upload_path_directory . $data['mutation_image']);
                                        }
                                    }
                                    $stat = $this->Mutation_model->update_mutation(array('mutation_id' => $mutation_id), $params_image);
                                }
                            }
                        }
                        //End of Save Image

                        /* Activity */
                        /*
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 4,
                            'activity_table' => 'mutations',
                            'activity_table_id' => $id,
                            'activity_text_1' => '',
                            'activity_text_2' => ucwords(strtolower($mutation_name),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        */                    
                        /* End Activity */
                        $return->status  = 1;
                        $return->message = 'Berhasil memperbarui '.$mutation_name;
                    }else{
                        $return->message='Gagal memperbarui '.$mutation_name;
                    }    
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;          
                case "mutation_delete":
                    //$post_data = $this->input->post('data');
                    //$data = json_decode($post_data, TRUE);            
                    $mutation_id   = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                    $mutation_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;                                

                    if(intval($mutation_id) > 0){
                        $get_data=$this->Mutation_model->get_mutation($mutation_id);
                        $set_data=$this->Mutation_model->delete_mutation($mutation_id);                
                        if($set_data==true){    
                            /*
                            if (file_exists($get_data['mutation_image'])) {
                                unlink($get_data['mutation_image']);
                            } 
                            */                            
                            /* Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => $act,
                                'activity_table' => 'mutations',
                                'activity_table_id' => $id,
                                'activity_text_1' => '',
                                'activity_text_2' => ucwords(strtolower($mutation_name)),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);                               
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil menghapus'.$mutation_name;
                        }else{
                            $return->message='Gagal menghapus '.$mutation_name;
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;             
                case "mutation_load":
                    $columns = array(
                        '0' => 'mutation_total',
                        '1' => 'mutation_text',
                        '2' => 'mutation_api_bank_account_number'
                    );
                    $limit     = $this->input->post('length');
                    $start     = $this->input->post('start');
                    $order     = $columns[$this->input->post('order')[0]['column']];
                    $dir       = $this->input->post('order')[0]['dir'];
                    $search    = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array();
                    if(intval($session_user_id) > 1){ // Only Root
                        $params['mutation_user_session'] = $session_user_session;
                    }                    
                    
                    /* If Form Mode Transaction CRUD not Master CRUD*/
                    !empty($this->input->post('date_start')) ? $params['mutation_date >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00')) : $params;
                    !empty($this->input->post('date_end')) ? $params['mutation_date <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;                
                    if($this->input->post() && $this->input->post('filter_bank')){
                        if(intval($this->input->post('filter_bank') > 0)){ 
                            !empty($this->input->post('filter_bank')) ? $params['mutation_bank_id '] = intval($this->input->post('filter_bank')) : $params;
                        }
                    }

                    $get_datas = $this->Mutation_model->get_all_mutation($params, $search, $limit, $start, $order, $dir);
                    $datas = array();
                    foreach($get_datas as $v):
                        $datas[] = array(
                            'mutation_id' => $v['mutation_id'],
                            'mutation_session' => $v['mutation_session'],
                            'mutation_date' => $v['mutation_date'],
                            'mutation_date_time_ago' => $v['mutation_date_time_ago'],
                            'mutation_text' => $v['mutation_text'],
                            'mutation_total' => $v['mutation_total'],
                            'mutation_api_bank_code' => $v['mutation_api_bank_code'],
                            'mutation_api_bank_name' => $v['mutation_api_bank_name'],
                            'mutation_api_bank_account_number' => $v['mutation_api_bank_account_number'],
                            'bank_account_name' => $v['bank_account_name'],
                            'mutation_type' => $v['mutation_type'],
                            'type_name' => ($v['mutation_type'] == 'D') ? 'Debet' : 'Kredit',                      
                        );
                    endforeach;
                    if(isset($datas)){ //Fetch All Datatable if exist
                        $return->total_records   = !empty($datas) ? count($datas) : 0;                
                        $return->status          = 1; 
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;
                    }else{ // Datatable not found
                        $return->total_records   = !empty($datas) ? count($datas) : 0;                
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;                
                    }
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    $return->action              = $action;
                    $return->params              = $params;
                    break;    
                case "deposit_create_debit": die; // use whatsapp_send_message_balance
                    $balance_debit = !empty($this->input->post('debit')) ? $this->input->post('debit') : 0;
                    $balance_credit = !empty($this->input->post('credit')) ? $this->input->post('credit') : 0;
                    $balance_note = !empty($this->input->post('note')) ? $this->input->post('note') : null;
                    $balance_flag = !empty($this->input->post('flag')) ? $this->input->post('flag') : 0;

                    $balance_type = 1;
                    $balance_number = $this->request_number_for_balance($balance_type);
                    $balance_session = $this->random_session(20);

                    $balance_random_uniq = $this->random_nominal(3);
                    $balance_debit = $balance_debit + intval($balance_random_uniq);

                    $params = array(
                        'balance_type' => $balance_type,
                        'balance_number' => $balance_number,
                        'balance_debit' => $balance_debit,
                        'balance_credit' => $balance_credit,
                        'balance_note' => $balance_note,
                        'balance_flag' => $balance_flag,
                        'balance_session' => $balance_session,
                        'balance_user_session' => $session_user_session,
                        // 'balance_bank_session' => $session_bank
                        'balance_date' => date("YmdHis"),
                        'balance_date_created' => date("YmdHis"),
                        'balance_date_due' => date("Y-m-d H:i:s", strtotime('+1 hour',strtotime(date("Y-m-d H:i:s")))),
                        'balance_uniq' => $balance_random_uniq
                    );

                    //Check Data Exist
                    // $balance_debit = 500115;
                    $params_check = array(
                        'balance_debit' => $balance_debit,
                        'balance_flag' => 0,
                    );
                    $check_exists = $this->Balance_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Balance_model->add_balance($params);
                        if($set_data==true){


                            $data = $this->Balance_model->get_balance($set_data);

                            //WhatsApp Send
                            $whatsapp = $this->whatsapp_send_message_balance('create-invoice',$data['balance_session']);
                            /* Start Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => 2,
                                'activity_table' => 'balance',
                                'activity_table_id' => $set_data,
                                'activity_text_1' => strtoupper($data['kode']),
                                'activity_text_2' => ucwords(strtolower($data['nama'])),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil';
                            $return->result= array(
                                'id' => $set_data,
                                'balance_number' => $data['balance_number'],
                                'balance_session' => $data['balance_session']
                            ); 
                            $return->wa = $whatsapp;
                        }
                    }else{
                        $return->message='Silahkan ulangi kembali';
                    }
                    $return->action=$action;
                    echo json_encode($return);
                    break;
                case "deposit_read":
                    $balance_session = !empty($this->input->post('s')) ? $this->input->post('s') : null; //Session
                    if(intval(strlen($balance_session)) > 0){        
                        // $datas = $this->Balance_model->get_balance_custom(array('balance_session'=>$balance_session));
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'balances',
                                'actvity_table_id' => $balance_id,
                                'actvity_text_1' => '',
                                'actvity_text_2' => ucwords(strtolower($datas['username'])),
                                'actvity_date_created' => date('YmdHis'),
                                'actvity_flag' => 0
                            );
                            $this->save_activity($params);                    
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil mendapatkan data';
                            $return->result=$datas;
                        }else{
                            $message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message='Data tidak ditemukan ';
                    }
                    $return->action=$action;
                    echo json_encode($return);                               
                    break;
                case "deposit_update":// use whatsapp_send_message_balance
                    $balance_session = !empty($this->input->post('session')) ? $this->input->post('session') : $data['session'];
                    $balance_flag = !empty($this->input->post('status')) ? $this->input->post('status') : $data['status'];

                    $get_deposit = $this->Balance_model->get_balance_custom(array('balance_session'=> $balance_session));
                    // var_dump($get_deposit);die;
                    if($get_deposit['balance_flag'] == 0){
                        if(intval($session_user_id) == 1){// Only Root
                            $params = array(
                                'balance_flag' => $balance_flag
                            );
                            if(intval($session_user_group_id) == 1){// Only Group Root
                                $set_update=$this->Balance_model->update_balance_custom(array('balance_session'=>$balance_session),$params);
                                if($set_update==true){
                                    $whatsapp = $this->whatsapp_send_message_balance('approval-invoice',$balance_session);
                                    // $data = $this->Balance_model->get_balance($balance_id);
                                        
                                    /* Activity */
                                    /*
                                    $params = array(
                                        'activity_user_id' => $session['user_data']['user_id'],
                                        'activity_action' => 4,
                                        'activity_table' => 'balances',
                                        'activity_table_id' => $id,
                                        'activity_text_1' => '',
                                        'activity_text_2' => ucwords(strtolower($balance_name),
                                        'activity_date_created' => date('YmdHis'),
                                        'activity_flag' => 0
                                    );
                                    $this->save_activity($params);
                                    */
                                    /* End Activity */
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menerima pembayaran ';
                                }else{
                                    $return->message='Gagal menerima pembayaran';
                                }    
                            }else{
                                $return->message ='Group anda di tolak';
                            }
                        }else{
                            $return->message='Akses anda di tolak';
                        }
                    }else{
                        $return->message = 'Deposit sudah diterima';
                    }
                    $return->action=$action;
                    break;
                case "deposit_delete":
                    $balance_session   = !empty($this->input->post('session')) ? $this->input->post('session') : 0;
                    $balance_flag = !empty($this->input->post('status')) ? $this->input->post('status') : $data['status'];
                    if(strlen($balance_session) > 0){
                        $params = array(
                            'balance_flag' => $balance_flag
                        );

                        $set_data=$this->Balance_model->update_balance_custom(array('balance_session'=>$balance_session),$params);
                        // $get_data=$this->Balance_model->get_balance($balance_id);
                        // $set_data=$this->Balance_model->delete_balance_custom(array('balance_session'=>$balance_session));
                        if($set_data==true){    
                            /*
                            if (file_exists($get_data['balance_image'])) {
                                unlink($get_data['balance_image']);
                            } 
                            */                            
                            /* Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => $act,
                                'activity_table' => 'balances',
                                'activity_table_id' => $id,
                                'activity_text_1' => '',
                                'activity_text_2' => ucwords(strtolower($balance_name)),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil menghapus';
                        }else{
                            $return->message='Gagal menghapus ';
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;
                    break;
                case "deposit_load":
                    $columns = array(
                        '0' => 'balance_id',
                        '1' => 'balance_note',
                        '2' => 'balance_debit',
                        '3' => 'user_phone_1',
                        '4' => 'user_fullname'
                    );
                    $limit     = $this->input->post('length');
                    $start     = $this->input->post('start');
                    $order     = $columns[$this->input->post('order')[0]['column']];
                    $dir       = $this->input->post('order')[0]['dir'];
                    $search    = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array();
                    if(intval($session_user_id) > 1){// Only Root
                        $params['balance_user_session'] = $session_user_session;
                    }                    
                    // $params['balance_flag'] = 1;

                    // If Form Mode Transaction CRUD not Master CRUD
                    !empty($this->input->post('date_start')) ? $params['balance_date >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00')) : $params;
                    !empty($this->input->post('date_end')) ? $params['balance_date <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;                
                    

                    //Default Params for Master CRUD Form
                    // $params['balance_id']   = !empty($this->input->post('balance_id')) ? $this->input->post('balance_id') : $params;
                    // $params['balance_name'] = !empty($this->input->post('balance_name')) ? $this->input->post('balance_name') : $params;                

                    if($this->input->post('filter_flag') && $this->input->post('filter_flag') > 0) {
                        $params['balance_flag'] = $this->input->post('filter_flag');
                    }
                    
                    $datas = $this->Balance_model->get_all_balance($params, $search, $limit, $start, $order, $dir);
                    
                    if(isset($datas)){ //Fetch All Datatable if exist
                        $return->total_records   = !empty($datas) ? count($datas) : 0;
                        $return->status          = 1; 
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;
                    }else{ // Datatable not found
                        $return->total_records   = !empty($datas) ? count($datas) : 0;
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;                
                    }
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    $return->action              = $action;
                    $return->params              = $params;
                    break;   
                case "balance_create_debit": // use whatsapp_send_message_balance
                    $balance_debit = !empty($this->input->post('debit')) ? $this->input->post('debit') : 0;
                    $balance_credit = !empty($this->input->post('credit')) ? $this->input->post('credit') : 0;
                    $balance_note = !empty($this->input->post('note')) ? $this->input->post('note') : null;
                    $balance_flag = !empty($this->input->post('flag')) ? $this->input->post('flag') : 0;

                    $balance_type = 1;
                    $balance_number = $this->request_number_for_balance($balance_type);
                    $balance_session = $this->random_session(20);

                    $balance_random_uniq = $this->random_nominal(3);
                    $balance_debit = $balance_debit + intval($balance_random_uniq);

                    $params = array(
                    	'balance_type' => $balance_type,
                    	'balance_number' => $balance_number,
                        'balance_debit' => $balance_debit,
                        'balance_credit' => $balance_credit,
                        'balance_note' => $balance_note,
                        'balance_flag' => $balance_flag,
                        'balance_session' => $balance_session,
                        'balance_user_session' => $session_user_session,
                        // 'balance_bank_session' => $session_bank
                        'balance_date' => date("YmdHis"),
                        'balance_date_created' => date("YmdHis"),
                        'balance_date_due' => date("Y-m-d H:i:s", strtotime('+1 hour',strtotime(date("Y-m-d H:i:s")))),
                        'balance_uniq' => $balance_random_uniq
                    );

                    //Check Data Exist
                    // $balance_debit = 500115;
                    $params_check = array(
                        'balance_debit' => $balance_debit,
                        'balance_flag' => 0,
                    );
                    $check_exists = $this->Balance_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Balance_model->add_balance($params);
                        if($set_data==true){


                            $data = $this->Balance_model->get_balance($set_data);

                            //WhatsApp Send
                            $whatsapp = $this->whatsapp_send_message_balance('create-invoice',$data['balance_session']);
                            /* Start Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => 2,
                                'activity_table' => 'balance',
                                'activity_table_id' => $set_data,
                                'activity_text_1' => strtoupper($data['kode']),
                                'activity_text_2' => ucwords(strtolower($data['nama'])),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil';
                            $return->result= array(
                                'id' => $set_data,
                                'balance_number' => $data['balance_number'],
                                'balance_session' => $data['balance_session']
                            ); 
                            $return->wa = $whatsapp;
                        }
                    }else{
                        $return->message='Silahkan ulangi kembali';
                    }
                    $return->action=$action;
                    break;
                case "balance_read":
                    $balance_session = !empty($this->input->post('s')) ? $this->input->post('s') : null; //Session
                    if(intval(strlen($balance_session)) > 0){        
                        $datas = $this->Balance_model->get_balance_custom(array('balance_session'=>$balance_session));
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'balances',
                                'actvity_table_id' => $balance_id,
                                'actvity_text_1' => '',
                                'actvity_text_2' => ucwords(strtolower($datas['username'])),
                                'actvity_date_created' => date('YmdHis'),
                                'actvity_flag' => 0
                            );
                            $this->save_activity($params);                    
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil mendapatkan data';
                            $return->result=$datas;
                        }else{
                            $message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message='Data tidak ditemukan ';
                    }
                    $return->action=$action;
                    echo json_encode($return);                               
                    break;
                case "balance_load":
                    $columns = array(
                        '0' => 'balance_id',
                        '1' => 'balance_note',
                        '2' => 'balance_debit',
                        '3' => 'balance_credit'
                    );
                    $limit     = $this->input->post('length');
                    $start     = $this->input->post('start');
                    $order     = $columns[$this->input->post('order')[0]['column']];
                    $dir       = $this->input->post('order')[0]['dir'];
                    $search    = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array();
                    
                    // $params['balance_flag'] = 1;
                    
                    // If Form Mode Transaction CRUD not Master CRUD
                    !empty($this->input->post('date_start')) ? $params['balance_date >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 23:59:59')) : $params;
                    !empty($this->input->post('date_end')) ? $params['balance_date <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;                
                    

                    //Default Params for Master CRUD Form
                    // $params['balance_id']   = !empty($this->input->post('balance_id')) ? $this->input->post('balance_id') : $params;
                    // $params['balance_name'] = !empty($this->input->post('balance_name')) ? $this->input->post('balance_name') : $params;                

                    /*
                    if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                        $params['other_column'] = $this->input->post('other_column');
                    }
                    */
                    
                    $datas = $this->Balance_model->get_all_balance($params, $search, $limit, $start, $order, $dir);
                    
                    if(isset($datas)){ //Fetch All Datatable if exist
                        $return->total_records   = !empty($datas) ? count($datas) : 0;                
                        $return->status          = 1; 
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;
                    }else{ // Datatable not found
                        $return->total_records   = !empty($datas) ? count($datas) : 0;                
                        $return->message         = 'Load '.$return->total_records.' datas'; 
                        $return->result          = $datas;                
                    }
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    $return->action              = $action;
                    $return->params              = $params;
                    break;
                case "balance_load_report":
                    $columns = array(
                        '0' => 'journal_item_date',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

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
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $total_data = 0;
                    $mdatas = array();
                    if($session_user_id > 0){
                        $get_datas = $this->report_balance($date_start,$date_end,$session_user_id,$search);
                        foreach($get_datas as $k => $v):
                            if(intval($v['total_data']) > 0){
                                $mdatas[] = array(
                                    'temp_id' => $v['temp_id'],
                                    'balance_type_name' => $v['balance_type_name'],
                                    'balance_session' => $v['balance_session'],
                                    'balance_date' => $v['balance_date'],
                                    'balance_date_time_ago' => $v['balance_date_time_ago'],
                                    'balance_note' => $v['balance_note'],
                                    'balance_number' => $v['balance_number'],  
                                    'debit' => $v['debit'],
                                    'credit' => $v['credit'],
                                    'balance' => $v['balance'],
                                    'balance_date_format' => $v['balance_date_format'],
                                    'balance_position' => $v['balance_position'],
                                    'status' => $v['status'],
                                    'message' => $v['message'],
                                    'total_data' => $v['total_data']
                                );
                            }
                            $total_data = $v['total_data'];
                        endforeach;
                    }
                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->action = $action;
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    break;
                case "balance_load_user_balance":
			    	// $ses = '6COM9H51X13ZJ7AFXQOS';
			    	$arr = array(
			    		'balance_user_session' => $session_user_session,
			    		'balance_flag' => 1
			    	);
			    	$get_data = $this->Balance_model->get_current_balance_custom($arr);
			    	if($get_data){
			    		$return->status=1;
			    		$return->message='Balance loaded';
			    		$return->result = $get_data;
                        $return->user_session = $session_user_session;
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

            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = date("d-m-Y");
            $data['hour'] = date("H:i");
            $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            $data['title'] = 'Notify';
            $data['_view'] = 'layouts/admin/menu/notify/notify';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/notify/notify_js.php',$data);
        }
    }

    function whatsapp_send_message_balance($action,$balance_session){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';

        // $token              = $this->input->get('token');
        // $key                = $this->input->get('key');

        // $recipient          = $this->input->get('recipient');
        // $content            = $this->input->get('content');

        $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_server    = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');
        $whatsapp_token     = $this->config->item('whatsapp_token');
        $whatsapp_key       = $this->config->item('whatsapp_key');
        $whatsapp_auth      = $this->config->item('whatsapp_auth');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');

        if($whatsapp_vendor == "umbrella.co.id"){
            if((!empty($whatsapp_token) && intval($whatsapp_token) == 21) && (!empty($whatsapp_key) && intval($whatsapp_key) == 21)){
                $next=true;
                $url = '';
                $body = '';

                //Get Balance 
                if(!empty($balance_session) && strlen($balance_session) > 1){
                    
                    if(strlen($balance_session) > 1){
                        $params = array('balance_session' => $balance_session);
                        // $url = '&session='.$balance_session;
                    }

                    $get_balance = $this->Balance_model->get_balance_custom($params);
                    $recipient_number = str_replace('+','',str_replace('-','',$get_balance['user_phone_1']));
                    $recipient_name = $get_balance['user_fullname'];
                    // $sender_number = $get_balance['device_number'];
                    $sender_number = $whatsapp_sender;

                    if($action == 'create-invoice'){
                        $body  = '📄 *Invoice Pembelian Poin*'."\r\n";
                        $body .= 'Nomor : '.$get_balance['balance_number']."\r\n";
                        $body .= 'Tanggal : '.date("d-M-Y, H:i", strtotime($get_balance['balance_date']))."\r\n";
                        $body .= 'Keterangan : Pembelian Poin NOTIF'."\r\n\r\n";
                        $body .= '*Metode Pembayaran:*'."\r\n";
                        $body .= 'TRANSFER BANK'."\r\n";
                        $body .= 'Bank : Bank BCA'."\r\n";
                        $body .= 'Rekening : 993001212323'."\r\n";
                        $body .= 'Atas Nama : Yoceline Islamwitaya Putra'."\r\n\r\n";
                        $body .= 'Jumlah : *Rp. '.number_format($get_balance['balance_debit'],0,'.',',').'*'."\r\n\r\n";
                        $body .= '_Silahkan melakukan pembayaran sesuai jumlah diatas hingga angka unik terakhir, Invoice ini berlaku hingga *'.date("d-M-Y, H:i", strtotime($get_balance['balance_date_due'])).'*, Pembayaran akan otomatis diverifikasi oleh Bank_'."\r\n\r\n";
                        // $body .= '_Terimakasih telah melakukan transaksi di platform NOTIF 🙂_'."\r\n\r\n";
                        // $body .= '*Butuh Bantuan*❓'."\r\n";
                        // $body .= 'www.mediadigital.id'."\r\n";
                        // $body .= 'https://wa.me/6289652510558'."\r\n";
                    }else if ($action == 'approval-invoice'){
                        $body  = '✅ *Pembayaran Invoice Diterima*'."\r\n";
                        $body .= 'Nomor : '.$get_balance['balance_number']."\r\n";
                        $body .= 'Tanggal : '.date("d-M-Y, H:i", strtotime($get_balance['balance_date']))."\r\n";
                        $body .= 'Keterangan : Pembelian Poin'."\r\n\r\n";
                        $body .= 'Jumlah Poin: *Rp. '.number_format($get_balance['balance_debit'],0,'.',',').'*'."\r\n\r\n";
                        $body .= '_Telah diterima oleh sistem, Terimakasih telah melakukan transaksi di platform NOTIF 🙂_'."\r\n\r\n";                
                    }else{
                        $body = 'Unknown';
                        // $body .= 'Bank: *'.$d['mutation_api_bank_name']."*"."\r\n";
                        // $body .= 'Rekening : *'.$d['mutation_api_bank_account_number']."*"."\r\n";
                        // $body .= 'Tanggal : *'.$d['mutation_api_date'].'*'."\r\n";
                        // $body .= 'Jumlah : *'.number_format($d['mutation_total'],0,'.',',').'*'."\r\n";
                        // $body .= 'Berita : _'.$d['mutation_text'].'_'."\r\n"."\r\n";
                        // $body .= '🔒 Pesan terenkripsi dan hanya anda yang menerimanya.';
                    }
                    // var_dump($sender_number);
                    //Send Message Session & ID
                    $content = $body;
                    // $url .= '&key=21&token=21&recipient='.$recipient_number.'&sender='.$sender_number;
                // }else{
                    //Send Message Directly
                    $url .= '&recipient='.$recipient_number.'&sender='.$sender_number;
                    $url .= '&content='.rawurlencode($content);
                }
                // var_dump($whatsapp_server.$whatsapp_action['send-message'].$url);die;
                //CURL If Completed URL Prepare
                if($next){
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $whatsapp_server.$whatsapp_action['send-message'].$url,
                        CURLOPT_HEADER => 0,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST =>  1,
                        CURLOPT_POSTFIELDS => array(
                        )
                    ));
                    $response = curl_exec($curl);

                    /* Result CURL / API */
                    $get_response = json_decode($response,true);
                    $result = $get_response['result'];
                    $result['message'] = $get_response['message'];

                    $return->status = $get_response['status'];
                    $return->message = $get_response['message'];
                    $return->result = $result;
                }

            }else{
                $return->message='Access Denied';
            }
        }else if ($whatsapp_vendor == 'ruangwa.id'){
            //Fetch From Table Message
            $get_datas = $this->Message_model->get_message($message_id);
            $number = $get_datas['message_contact_number'];
            $nama = $get_datas['message_contact_name'];

            if(!empty($number)){
                // $number = "+62812-2709-9957";

                /* Prepare Target */
                $sent_to = array(
                    'user_phone' => str_replace('+','',str_replace('-','',$number)),
                    'user_name' => str_replace('%20',' ',$nama)
                );

                /* Message */
                // $body_text = 'Hai '.$sent_to['user_name']."\r\n";
                // $body_text .= $get_datas['message_text'];
                $body_text = $get_datas['message_text'];
                /* Configuration */
                $body_fields = array(
                    "phone" => $sent_to['user_phone'],
                    "type" => "text",
                    // "url" => "https://www.umbrella.co.id/demo/jurnal/upload/branch/logo.png",
                    // "caption" => "Ini Logo Jurnal",
                    "text" => $body_text,
                    "delay" => "1",
                    "delay_req" => "1",
                    "schedule" => "0"
                );
                // var_dump($body_fields,$body_text,$sent_to,$whatsapp_server,$whatsapp_token);
                // echo '<br><br>';

                /* Action */
                $curl = curl_init();

                if($whatsapp_vendor=='fonnte.com'){
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $whatsapp_server,
                        CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $body_fields,
                        CURLOPT_HTTPHEADER => array("Authorization: ".$whatsapp_token.""),
                    ));
                }else if($whatsapp_vendor=='ruangwa.id'){

                    $content_text = array(
                        'token'    => $whatsapp_token,
                        'phone'     => $sent_to['user_phone'],
                        'message'   => $body_text
                    );

                    $content_image = array(
                        'token' => $whatsapp_token,
                        'phone' => $sent_to['user_phone'],
                        'image' => $get_datas['message_url'],
                        'filename' => 'Gambar',
                        'caption' => $body_text
                    );

                    $content_video = array(
                        'token' => $whatsapp_token,
                        'phone' => $sent_to['user_phone'],
                        'video' => 'https://umbrella.co.id/demo/sweethome/upload/news/d7e9db6878566902065c86a9fcbe9cd0.jpg',
                        'filename' => 'promo.png',
                        'caption' => $body_text
                    );

                    $content_document = array(
                        'token' => $whatsapp_token,
                        'phone' => $sent_to['user_phone'],
                        'document' => 'https://umbrella.co.id/demo/sweethome/upload/news/d7e9db6878566902065c86a9fcbe9cd0.jpg',
                        'filename' => 'promo.png',
                        'caption' => $body_text
                    );

                    $content_link = array(
                        'token' => $whatsapp_token,
                        'phone' => $sent_to['user_phone'],
                        'link' => 'https://umbrella.co.id/demo/sweethome/upload/news/d7e9db6878566902065c86a9fcbe9cd0.jpg',
                        'text' => $body_text
                    );

                    $set_content = $content_text;
                    $set_action = 'send-message.php';
                    if(strlen($get_datas['message_url']) > 0){
                        $set_content = $content_image;
                        $set_action = 'send-image.php';
                    }

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $whatsapp_server.$set_action,
                        CURLOPT_HEADER => 0,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST =>  1,
                        CURLOPT_POSTFIELDS => $set_content
                    ));
                }

                $response = curl_exec($curl);

                // curl_close($curl);

                /* Result */
                $get_response = json_decode($response,true);
                $return->result = $get_response;
                if($get_response['result'] !== false){
                    // $return->status = 1;
                    // $return->message = 'Success';
                }
            }
            $return->number = $number;
            $return->vendor = $whatsapp_vendor;
        }

        return json_encode($return);
        // echo json_encode($return);
    }    
    function report_balance($start,$end,$user_id,$search){
        $prepare="CALL sp_report_balance('$start','$end',$user_id,'')";
        // log_message('debug',$prepare);
        // var_dump($prepare);die;
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        // $query->free_result();
        $result = $query->result_array();
        return $result;
    }    
    function request_number_for_balance($tipe){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];

        $tgl = date('d-m-Y');
        $tahun = substr($tgl, 6, 4);
        $bulan = substr($tgl, 3, 2);
        $hari = substr($tgl, 0, 2);
        $tahun2 = substr($tgl, 8, 2);

        $query = $this->db->query("SELECT MAX(RIGHT(balance_number,5)) AS last_number
            FROM balances
            WHERE YEAR(balance_date_created)=$tahun
            AND MONTH(balance_date_created)=$bulan
            AND balance_type=$tipe");
        $nomor = "";
        if ($query->num_rows() > 0){
            foreach ($query->result() as $v){
                $temp = ((int) $v->last_number) + 1;
                $nomor = sprintf("%05s", $temp);
            }
        }else{
            $nomor = "00001";
        }

        $inisial = array(
            '1' => 'Deposit', //Pembelian
            '2' => 'Withdraw', //Penjualan
            '3' => 'Biaya', //Retur Beli
        );
        $auto_number = $inisial[$tipe] . '-' . $tahun2 . $bulan . '-' . $nomor;
        return $auto_number;
    }  
    function random_nominal($length){ # JEH3F2
        $result = rand(101,502);
        return $result;
    }      
    function whatsapp_sent_mutation($mutation_id){// Not used for new config whatsapp.php
        $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        $return->result = '';

        $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        $whatsapp_server = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');        
        $whatsapp_token = $this->config->item('whatsapp_token');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');
        //Fetch From Table Mutation
        $prepare = "SELECT * FROM mutations WHERE mutation_id=$mutation_id";
        $query = $this->db->query($prepare);
        $d = $query->row_array();
        $number = $d['mutation_notif_phone'];
        // var_dump($d);die;
        if(!empty($number)){
            // $number = "+62812-2709-9957";

            /* Prepare Target */
            $sent_to = array(
                'user_phone' => str_replace('+','',str_replace('-','',$number))
            );
            $mutation_type_name = ($d['mutation_type'] == 'D') ? '🔴 Mutasi DEBIT' : '🟢 Mutasi KREDIT';
            /* Message */
            $body_text  = $mutation_type_name."\r\n";
            $body_text .= 'Bank: *'.strtoupper($d['mutation_api_bank_code'])."*"."\r\n";
            $body_text .= 'Rekening : *'.$d['mutation_api_bank_account_number']."*"."\r\n";
            $body_text .= 'Tanggal : *'.$d['mutation_api_date'].'*'."\r\n";
            $body_text .= 'Jumlah : *'.number_format($d['mutation_total'],0,'.',',').'*'."\r\n";
            $body_text .= 'Berita : _'.$d['mutation_text'].'_'."\r\n"."\r\n";
            $body_text .= '🔒 Pesan ini dienkripsi dan hanya anda yang menerimanya.';

            /* Configuration */
            $body_fields = array(
                "phone" => $sent_to['user_phone'],
                "type" => "text",
                "text" => $body_text,
                "delay" => "1",
                "delay_req" => "1",
                "schedule" => "0"
            );
            // var_dump($body_fields,$body_text,$sent_to,$whatsapp_server,$whatsapp_token);
            // echo '<br><br>';
            $url = '&recipient='.$d['mutation_notif_phone'].'&sender='.$whatsapp_sender;
            $url .= '&content='.rawurlencode($body_text);
            /* Action */
            $curl = curl_init();
            $content_text = array(
                'token'    => $whatsapp_token,
                'phone'     => $sent_to['user_phone'],
                'message'   => $body_text
            );
            // var_dump($whatsapp_server.$whatsapp_action['send-message'].$url);die;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $whatsapp_server.$whatsapp_action['send-message'].$url,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_POST =>  1,
                CURLOPT_POSTFIELDS => $content_text
            ));

            $response = curl_exec($curl);

            // curl_close($curl);

            /* Result */
            $get_response = json_decode($response,true);
            // log_message('debug',$get_response);
            $return->result = $get_response;
            if($get_response['result'] !== false){
                // $return->status = 1;
                // $return->message = 'Success';
            }
        }
        $return->number = $number;
        $return->vendor = $whatsapp_vendor;
        return json_encode($return);
    }    
}

?>