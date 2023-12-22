<?php
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Device extends MY_Controller{

    var $folder_upload = 'uploads/device/';

    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }           
        $this->load->library('form_validation');                  
        $this->load->helper('form');

        $this->load->model('Aktivitas_model');
        $this->load->model('User_model');       
        $this->load->model('Device_model');               
    }

    function index(){
        $session            = $this->session->userdata();
        $session_branch_id  = !empty($session['user_data']['branch']['id']) ? $session['user_data']['branch']['id'] : null;
        $session_user_id    = !empty($session['user_data']['user_id']) ? $session['user_data']['user_id'] : null;
        $data['session']    = $this->session->userdata();  
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        if ($this->input->post()) {    
            $post = $this->input->post();

            $upload_directory       = $this->folder_upload;     
            $upload_path_directory  = FCPATH . $upload_directory;

            $return             = new \stdClass();
            $return->status     = 0;
            $return->message    = '';
            $return->result     = '';      

            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'device_number',
                        '1' => 'device_token',
                        // '2' => 'device_session',
                        // '3' => 'group_name'
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
                    if($session_user_id){
                        $params['device_branch_id'] = $session_branch_id;
                    }
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($this->input->post('date_start')) ? $params['device_date >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 23:59:59')) : $params;
                    !empty($this->input->post('date_end')) ? $params['device_date <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['device_id']   = !empty($this->input->post('device_id')) ? $this->input->post('device_id') : $params;
                    // $params['device_name'] = !empty($this->input->post('device_name')) ? $this->input->post('device_name') : $params;                

                    /*
                    if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                        $params['other_column'] = $this->input->post('other_column');
                    }
                    */    
                    if($this->input->post('media') && $this->input->post('media') !== 'ALL') {
                        $params['device_media'] = $this->input->post('media');
                    }                    
                    $get_count = $this->Device_model->get_all_device_count($params, $search);
                    if($get_count > 0){
                        $datas = $this->Device_model->get_all_device($params, $search, $limit, $start, $order, $dir);
                        $return->status          = 1; 
                        $return->message         = 'Load '.$get_count.' datas'; 
                        $return->result          = $datas;
                    }else{
                        $return->message         = 'Load '.$get_count.' datas'; 
                        $return->result          = array();                
                    }
                    $return->total_records       = $get_count;
                    $return->recordsTotal        = $get_count;
                    $return->recordsFiltered     = $get_count;
                    $return->action              = $action;
                    $return->params              = $params;
                    break;                
                case "create":
                    $next = true;
                    $device_number = !empty($this->input->post('device_number')) ? $this->input->post('device_number') : null;
                    $device_label = !empty($this->input->post('device_label')) ? $this->input->post('device_label') : null;
                    // $device_auth = !empty($this->input->post('device_auth')) ? $this->input->post('device_auth') : null;
                    $device_status = !empty($this->input->post('device_flag')) ? $this->input->post('device_flag') : 1;
                    // $device_group = !empty($this->input->post('device_group')) ? $this->input->post('device_group') : 0;
                    $device_media = !empty($this->input->post('device_media')) ? $this->input->post('device_media') : null;
                    $device_email = !empty($this->input->post('device_mail_email')) ? $this->input->post('device_mail_email') : null;
                    
                    $device_number = $this->contact_number($device_number);

                    $params = array(
                        'device_number' => $device_number,
                        'device_label' => $device_label,
                        'device_token' => $this->random_token(),
                        'device_flag' => $device_status,
                        'device_date_created' => date("YmdHis"),
                        // 'device_group_id' => $device_group
                        'device_branch_id' => !empty($post['device_branch_id']) ? intval($post['device_branch_id']) : null,
                        'device_media' => !empty($post['device_media']) ? $post['device_media'] : null,
                        'device_mail_host' => !empty($post['device_mail_host']) ? $post['device_mail_host'] : null,
                        'device_mail_port' => !empty($post['device_mail_port']) ? $post['device_mail_port'] : null,
                        'device_mail_email' => !empty($post['device_mail_email']) ? $post['device_mail_email'] : null,
                        'device_mail_password' => !empty($post['device_mail_password']) ? $post['device_mail_password'] : null,
                        'device_mail_from_alias' => !empty($post['device_mail_from_alias']) ? $post['device_mail_from_alias'] : null,
                        'device_mail_reply_alias' => !empty($post['device_mail_reply_alias']) ? $post['device_mail_reply_alias'] : null,
                        'device_mail_label_alias' => !empty($post['device_mail_label_alias']) ? $post['device_mail_label_alias'] : null                        
                    );
                    // var_dump($params);die;
                    //Check Data Exist
                    if($device_media=='WhatsApp'){
                        $params_check = array(
                            'device_number' => $device_number
                        );
                    }else if($device_media=='Email'){
                        $params_check = array(
                            'device_mail_email' => $device_email
                        );
                    }                    
                    $check_exists = $this->Device_model->check_data_exist($params_check);
                    if($check_exists==false){

                        if($device_media=='WhatsApp'){
                            $params_register = array(
                                'device_branch_id' => $session_branch_id
                            );
                            $get_register = $this->Device_model->get_all_device_count($params_register);
                            if($get_register > 0){
                                $next=false;
                                $return->message = 'Nomor tidak dapat didaftarkan, maksimal 1';
                            }
                        }

                        if($next){
                            $set_data=$this->Device_model->add_device($params);
                            if($set_data==true){
                                /* Start Activity */
                                /*
                                $params = array(
                                    'activity_user_id' => $session['user_data']['user_id'],
                                    'activity_action' => 2,
                                    'activity_table' => 'device',
                                    'activity_table_id' => $set_data,                            
                                    'activity_text_1' => strtoupper($data['kode']),
                                    'activity_text_2' => ucwords(strtolower($data['nama'])),                        
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 1
                                );
                                $this->save_activity($params);    
                                */
                                /* End Activity */   
                                $get_data = $this->Device_model->get_device($set_data);
                                if($device_media=='WhatsApp'){
                                    $opr = $this->device_curl('register',$get_data);
                                    // $opr = json_encode($opr);
                                    $return->result = $opr['result'];
                                    $return->status =$opr['status'];
                                    $return->message= $opr['message'];
                                    // $return->result= array(
                                    //     'id' => $set_data,
                                    //     'number' => $device_number
                                    // ); 
                                    if($return->status == 1){
                                        $this->Device_model->update_device($set_data,array('device_token'=>$opr['result']['device_auth']));
                                    }
                                }else if($device_media=='Email'){
                                    $return->result = $get_data;
                                    $return->status = 1;
                                    $return->message= 'Berhasil menambahkan '.$device_email;                            
                                }
                            }
                        }
                    }else{
                        $return->message='Data sudah ada';
                    }
                    $return->action=$action;
                    break;
                case "read":
                    $device_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;
                    if(intval($device_id) > 0){
                        $datas = $this->Device_model->get_device($device_id);
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'devices',
                                'actvity_table_id' => $device_id,
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
                    break;
                case "update":
                    $device_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;
                    $device_number = !empty($this->input->post('device_number')) ? $this->input->post('device_number') : null;
                    // $device_auth = !empty($this->input->post('device_auth')) ? $this->input->post('device_auth') : null;
                    $device_label = !empty($this->input->post('device_label')) ? $this->input->post('device_label') : null;
                    $device_status = !empty($this->input->post('device_flag')) ? $this->input->post('device_flag') : 1;
                    // $device_group = !empty($this->input->post('device_group')) ? $this->input->post('device_group') : 0;
                    $device_media = !empty($this->input->post('device_media')) ? $this->input->post('device_media') : null;
                    $device_email = !empty($this->input->post('device_mail_email')) ? $this->input->post('device_mail_email') : null;

                    $params = array(
                        'device_number' => $device_number,
                        'device_label' => $device_label,
                        // 'device_auth' => $device_auth,
                        'device_date_updated' => date("YmdHis"),
                        'device_flag' => $device_status,
                        // 'device_group_id' => $device_group
                        // 'device_branch_id' => !empty($post['device_branch_id']) ? intval($post['device_branch_id']) : null,
                        'device_media' => !empty($post['device_media']) ? $post['device_media'] : null,
                        'device_mail_host' => !empty($post['device_mail_host']) ? $post['device_mail_host'] : null,
                        'device_mail_port' => !empty($post['device_mail_port']) ? $post['device_mail_port'] : null,
                        'device_mail_email' => !empty($post['device_mail_email']) ? $post['device_mail_email'] : null,
                        'device_mail_password' => !empty($post['device_mail_password']) ? $post['device_mail_password'] : null,
                        'device_mail_from_alias' => !empty($post['device_mail_from_alias']) ? $post['device_mail_from_alias'] : null,
                        'device_mail_reply_alias' => !empty($post['device_mail_reply_alias']) ? $post['device_mail_reply_alias'] : null,
                        'device_mail_label_alias' => !empty($post['device_mail_label_alias']) ? $post['device_mail_label_alias'] : null                      
                    );
                    // var_dump($params);die;
                    if(!empty($post['device_mail_password'])){
                        $params['device_mail_password'] = $post['device_mail_password'];
                    }
                    // var_dump($params);die;
                    $set_update=$this->Device_model->update_device($device_id,$params);
                    if($set_update==true){
                        
                        $data = $this->Device_model->get_device($device_id);
                        /* Activity */
                        /*
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 4,
                            'activity_table' => 'devices',
                            'activity_table_id' => $id,
                            'activity_text_1' => '',
                            'activity_text_2' => ucwords(strtolower($device_name),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        */                    
                        /* End Activity */
                        $return->status  = 1;
                        $return->message = 'Berhasil memperbarui ';
                    }else{
                        $return->message='Gagal memperbarui ';
                    }    
                    $return->action=$action;                           
                    break;        
                case "delete":            
                    $device_id   = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                    $device_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;                                
                    $return->message = 'Action belum tersedia';
                    // if(intval($device_id) > 0){
                    //     $get_data=$this->Device_model->get_device($device_id);
                    //     $set_data=$this->Device_model->delete_device($device_id);                
                    //     if($set_data==true){    

                    //         if($get_data['device_media']=='WhatsApp'){
                    //             $opr = $this->device_curl('unregister',$get_data);
                    //             $return->result = $opr['result'];
                    //             $return->status =$opr['status'];
                    //             $return->message= $opr['message'];
                    //         }
                    //         /* Activity */
                    //         /*
                    //         $params = array(
                    //             'activity_user_id' => $session['user_data']['user_id'],
                    //             'activity_action' => $act,
                    //             'activity_table' => 'devices',
                    //             'activity_table_id' => $id,
                    //             'activity_text_1' => '',
                    //             'activity_text_2' => ucwords(strtolower($device_name)),
                    //             'activity_date_created' => date('YmdHis'),
                    //             'activity_flag' => 0
                    //         );
                    //         $this->save_activity($params);                               
                    //         */
                    //         /* End Activity */
                    //         $return->status=1;
                    //         $return->message='Berhasil menghapus'.$device_name;
                    //     }else{
                    //         $return->message='Gagal menghapus '.$device_name;
                    //     } 
                    // }else{
                    //     $return->message='Data tidak ditemukan';
                    // }
                    $return->action=$action;                             
                    break;
                case "set_flag":
                    $device_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;
                    $device_number = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;
                    $device_flag = !empty($this->input->post('flag')) ? $this->input->post('flag') : 0;

                    $params = array(
                        'device_flag' => $device_flag
                    );
                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */
                    // var_dump($params);die;
                    $set_update=$this->Device_model->update_device($device_id,$params);
                    if($set_update==true){
                        
                        $data = $this->Device_model->get_device($device_id);
                        /* Activity */
                        /*
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 4,
                            'activity_table' => 'devices',
                            'activity_table_id' => $id,
                            'activity_text_1' => '',
                            'activity_text_2' => ucwords(strtolower($device_name),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        */                    
                        /* End Activity */
                        $return->status  = 1;
                        $return->message = 'Berhasil memperbarui '.$device_number;
                    }else{
                        $return->message='Gagal memperbarui '.$device_number;
                    }    
                    $return->action=$action;                             
                    break;     
                case "restart":
                    $this->form_validation->set_rules('device_id', 'device_id', 'required');                
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $device_id   = !empty($post['device_id']) ? $post['device_id'] : 0;   
                        if(intval($device_id) > 0){        
                            $datas = $this->Device_model->get_device($device_id);
                            if($datas){
                                $return->status = 1;
                                $return->message = 'Berhasil merestart, silahkan check 30 detik lagi';
                                $curl = $this->device_curl('restart',$datas);
                                // $return->result = $curl;    
                                $return->result = $curl['message'];
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    break;   
                case "request_qr_code":
                    $this->form_validation->set_rules('device_id', 'device_id', 'required');                
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $device_id   = !empty($post['device_id']) ? $post['device_id'] : 0;   
                        if(intval($device_id) > 0){        
                            $datas = $this->Device_model->get_device($device_id);
                            if($datas){

                                // Live 
                                $curl = $this->device_curl('request-qrcode',$datas);
                                $return->result = $curl;
                                $return->status = $curl['status']; 
                                $return->message = $curl['message'];

                                // Demo
                                // $return->status = 1; 
                                // $return->message = 'Scan Kode QR Berikut';
                                // $return->result = array(
                                //     'status' => 1,
                                //     'message' => 'Scan Kode QR Berikut',
                                //     'result' => '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'
                                // );
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }                
                    break;   
                case "check_status":
                    $this->form_validation->set_rules('device_id', 'device_id', 'required');                
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $device_id   = !empty($post['device_id']) ? $post['device_id'] : 0;   
                        if(intval($device_id) > 0){        
                            $datas = $this->Device_model->get_device($device_id);
                            if($datas){
                                // $return->status = 1;
                                // $return->message = 'Cek status nomor';
                                $curl = $this->device_curl('check-status',$datas); 
                                // $return->result = $curl['message'];
                                $return->status  = $curl['status'];
                                $return->message = $curl['message'];
                                if($return->status==1){
                                    $return->message = 'Device telah tersambung ke server';
                                }
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    break;
                                                                            
                default:
                    // Date Now
                    $firstdate = new DateTime('first day of this month');
                    $firstdateofmonth = $firstdate->format('Y-m-d');        
                    $datenow =date("Y-m-d");         
                    $data['first_date'] = $firstdateofmonth;
                    $data['end_date'] = $datenow;      
            }
            echo json_encode($return);
        }else{
            // Date Now
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('Y-m-d');        
            $datenow =date("Y-m-d");         
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = $datenow;

            $data['identity'] = 1;
            $data['title'] = 'Device';
            $data['_view'] = 'layouts/admin/menu/message/device';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/message//device_js.php',$data);         
        }
    }
    function random_token(){
        $text = 'qpArnmsBCtDEguFhGHveJfKwdjcMxNOPybaQzRSTkUVWXYZ'.time();
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=30; $i++){
        $result .= $text[mt_rand(0, $txtlen)];}
        return $result;
    }
    function device_curl($action,$params){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_server    = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');
        // $whatsapp_token     = $this->config->item('whatsapp_token');
        // $whatsapp_key       = $this->config->item('whatsapp_key');
        // $whatsapp_auth      = $this->config->item('whatsapp_auth');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');

        /* Configuration */
        if($action == 'restart'){
            $url = $whatsapp_server.'devices?action='.$action.'&auth='.$params['device_token'].'&sender='.$params['device_number'];    
        }else if($action == 'request-qrcode'){
            $url = $whatsapp_server.'devices/new/'.$params['device_number'].'?type=base64';    
        }else if($action == 'check-status'){
            $url = $whatsapp_server.'devices?action='.$action.'&auth='.$params['device_token'].'&sender='.$params['device_number'];    
        }else if($action == 'register'){
            $url = $whatsapp_server.'devices/register/'.$params['device_number'];        
        }
        // var_dump($url);die;

        /* Action */
        $curl = curl_init();
        // log_message('debug',$url);die;
        $curl_option = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST =>  1,
            CURLOPT_POSTFIELDS => array(
            )
        );
        curl_setopt_array($curl, $curl_option);
        $response = curl_exec($curl);
        curl_close($curl);
        $get_response = json_decode($response,true);

        // if($get_response['status'] == 1){
        //     $return->status = 1;
        //     $return->message = $get_response['message'];
        //     $return->result = array(
        //         // 'auth' => $whatsapp_auth,
        //         'sender' => $whatsapp_sender,
        //         'info' => $get_response['message']
        //     );
        // }else{
        //     $return->message = 'Gagal check';
        //     // $return->result = array(
        //     //     // 'auth' => $whatsapp_auth,
        //     //     'sender' => $whatsapp_sender,
        //     //     'info' => 'Tidak diketahui'
        //     // );            
        //     $return->result = $get_response;        
        // }
        return $get_response;
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
}

?>