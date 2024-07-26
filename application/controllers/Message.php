<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller{

    public $app_name;
    public $app_url;
    public $app_logo;
    var $folder_upload = 'uploads/message/';

    function __construct(){
        parent::__construct();
        // if(!$this->is_logged_in()){
            // redirect(base_url("login"));
        // }
        $this->load->helper('url');        
        $this->load->helper('date');       
        $this->load->helper('cookie'); 
        
        $this->load->library('email');
        $this->load->library('phpmailer_lib');
        $this->load->library('user_agent');

        $this->load->config('email');
        $this->load->config('whatsapp');
        $this->load->config('firebase');

        $this->load->model('Login_model');   
        $this->load->model('Branch_model');
        $this->load->model('User_model');
        $this->load->model('Kontak_model');
        $this->load->model('News_model');
        $this->load->model('Kategori_model');
        $this->load->model('Message_model');
        $this->load->model('Device_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Type_model');        
        $this->load->model('Order_model');        
        $this->load->model('Branch_model');
        $this->load->model('Recipient_model');
        $this->load->model('Front_model');        

        
        //Get Branch
        $get_branch = $this->Branch_model->get_branch(1);

        $this->app_name = $get_branch['branch_name'];
        $this->app_url  = site_url();  
        $this->app_logo = site_url().$get_branch['branch_logo'];          
    }
    function index(){
        $session            = $this->session->userdata();
        $session_branch_id  = !empty($session['user_data']['branch']['id']) ? $session['user_data']['branch']['id'] : null;
        $session_user_id    = !empty($session['user_data']['user_id']) ? $session['user_data']['user_id'] : null;
        $data['session']    = $this->session->userdata();  
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
        
        if ($this->input->post()) {
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;

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
                        '0' => 'message_id',
                        '1' => 'message_contact_number',
                        '2' => 'message_contact_name',
                        '3' => 'message_session',
                        '4' => 'message_group_session',                                                                        
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
                        // $params['message_branch_id'] = $session_branch_id;
                    }                    
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($this->input->post('date_start')) ? $params['message_date >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 23:59:59')) : $params;
                    !empty($this->input->post('date_end')) ? $params['message_date <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['message_id']   = !empty($this->input->post('message_id')) ? $this->input->post('message_id') : $params;
                    // $params['message_name'] = !empty($this->input->post('message_name')) ? $this->input->post('message_name') : $params;                

                    if($this->input->post('platform') && $this->input->post('platform') > 0) {
                        $params['message_platform'] = intval($this->input->post('platform'));
                    }
                    
                   $get_count = $this->Message_model->get_all_message_count($params, $search);                    
                    if($get_count > 0){
                        $datas = $this->Message_model->get_all_message($params, $search, $limit, $start, $order, $dir);            
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
                    $message_branch     = !empty($this->input->post('branch')) ? $this->input->post('branch') : 0;
                    $message_contact    = !empty($this->input->post('contact_list')) ? json_decode($this->input->post('contact_list')) : 0;
                    $message_contact_type = !empty($this->input->post('contact_type_list')) ? json_decode($this->input->post('contact_type_list')) : 0;
                    $message_contact_cat = !empty($this->input->post('contact_cat_list')) ? json_decode($this->input->post('contact_cat_list')) : 0;                                        
                    $message_recipient_group = !empty($this->input->post('recipient_group_list')) ? json_decode($this->input->post('recipient_group_list')) : 0;                                                            
                    $message_recipient_birthday = !empty($this->input->post('recipient_birthday_list')) ? json_decode($this->input->post('recipient_birthday_list')) : 0;                                                            
                    
                    // $message_contact    = !empty($this->input->post('contact')) ? $this->input->post('contact') : 1;
                    $message_subject    = !empty($this->input->post('subject')) ? $this->input->post('subject') : null;
                    $message_text       = !empty($this->input->post('text')) ? $this->input->post('text') : null;
                    $message_platform   = !empty($this->input->post('platform')) ? $this->input->post('platform') : 1;
                    $message_news       = !empty($this->input->post('news')) ? $this->input->post('news') : null;
                    $message_device     = !empty($this->input->post('device')) ? $this->input->post('device') : null;                    
                    $send_mode          = !empty($this->input->post('send_now')) ? $this->input->post('send_now') : 0;
                    
                    $contact_count              = count($message_contact);
                    $contact_type_count         = count($message_contact_type);
                    $contact_cat_count          = count($message_contact_cat);                       
                    $recipient_group_count      = count($message_recipient_group);
                    $recipient_birthday_count   = count($message_recipient_birthday);                       

                    $get_news_url = null;
                    if(intval($message_news) > 0){
                        $get_news = $this->News_model->get_news($message_news);
                        if(!empty($get_news['news_image'])){
                            $get_news_url = site_url().$get_news['news_image'];
                        }else{
                            $get_news_url = null;
                        }
                    }

                    if(intval($message_branch) > 0){
                        $operator = 'send-branch';
                    }

                    if(intval($contact_count) > 0){
                        $operator = 'send-contact';
                    }else if(intval($contact_cat_count) > 0){
                        $operator = 'send-contact';
                    }else if(intval($contact_type_count) > 0){
                        $operator = 'send-contact';
                    }else if(intval($recipient_group_count) > 0){
                        $operator = 'send-contact';
                    }else if(intval($recipient_birthday_count) > 0){
                        $operator = 'send-contact';
                    }  

                    switch($operator){
                        case "send-branch": die; // Fitur Kirim Broadcast
                            $params_contact = array(
                                'contact_branch_id' => $message_branch,
                                // 'contact_type' => 2
                            );
                            $a=10;
                            $get_contact = $this->Kontak_model->get_all_kontaks($params_contact,null,null,null,null,'contact_name','asc');
                            // var_dump($get_contact);
                            foreach($get_contact as $m):
                                // sleep(5);
                                $set_message_text = str_replace('#nama#',$m['contact_name'],$message_text);
                                $interval = '+'.intval($a).' second';
                                $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                
                                $params = array(
                                    'message_platform' => $message_platform,
                                    'message_contact_id' => $m['contact_id'],
                                    'message_contact_name' => $m['contact_name'],
                                    'message_contact_number' => $m['contact_phone_1'],
                                    'message_text' => $set_message_text,
                                    'message_session' => $this->random_code(20),
                                    'message_date_created' => $set_enque_date,
                                    'message_news_id' => $message_news,
                                    'message_url' => $get_news_url,
                                    'message_flag' => 0,
                                    'message_device_id' => $message_device
                                );
                                $set_data=$this->Message_model->add_message($params);
                                $message_id = $set_data;

                                //Sent WhatsApp Process
                                $get_execute = $this->whatsapp_send_message($message_id);
                                // $get_execute = $this->whatsapp_sent($set_data);
                                $response = json_decode($get_execute,true);

                                $return->status = ($response['status'] == 1) ? 1 : 0;
                                // var_dump($response,$return->status);die;
                                // $return->message = ($response['result']['result'] == true) ? $response['result']['message'] : 'Gagal Mengirim';  
                                $return->message = $response['message'];
                                
                                //Update Flag is Sent
                                if($return->status==1){
                                    $params = array(
                                        'message_date_sent' => date("YmdHis"),
                                        'message_flag' => 1
                                    );
                                    $set_update=$this->Message_model->update_message($message_id,$params);
                                }
                                $a = $a+10;
                            endforeach;
                            // $return->status=1;
                            // $return->message='Berhasil mengirim pesan';                  
                            break;
                        case "send-contact": //Kirim Satu Persatu yg terpilih

                            //Kontak Terpilih
                            $m=$message_contact;
                            for($a=0; $a < intval($contact_count); $a++){

                                $set_message_text = str_replace('#nama#',$m[$a]->contact_name,$message_text);
                                $interval = '+'.intval($a).' minute';
                                $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                
                                $set_contact_id = !empty($m[$a]->contact_id) ? $m[$a]->contact_id : null;
                                $set_contact_name = !empty($m[$a]->contact_name) ? $m[$a]->contact_name : null;
                                $set_contact_phone = !empty($m[$a]->contact_phone) ? $this->contact_number($m[$a]->contact_phone) : null;
                                $set_contact_email = !empty($m[$a]->contact_email) ? $m[$a]->contact_email : null;                                                                                                            
                                
                                $params = array(
                                    'message_platform' => $message_platform,
                                    'message_contact_id' => $set_contact_id,
                                    'message_contact_name' => $set_contact_name,
                                    'message_contact_number' => $set_contact_phone,
                                    'message_contact_email' => $set_contact_email,   
                                    'message_subject' => $message_subject,
                                    'message_session' => $this->random_code(20),
                                    'message_date_created' => $set_enque_date,
                                    'message_news_id' => $message_news,
                                    'message_url' => $get_news_url,
                                    'message_flag' => 0,
                                    'message_device_id' => $message_device,
                                    'message_branch_id' => $session_branch_id
                                );

                                if($message_platform == 1){
                                    $params['message_contact_number'] = $set_contact_phone;
                                    $params['message_text'] = strip_tags($set_message_text);
                                }else if($message_platform == 4){
                                    $params['message_contact_email'] = $set_contact_email;
                                    $params['message_text'] = $set_message_text;
                                }
                                
                                $set_data=$this->Message_model->add_message($params);
                                $message_id = $set_data;

                                //Sent WhatsApp Process
                                // $do = $this->whatsapp_send_id($message_id);
                                // $res = json_decode($do,true);      
                                // $return->message = $res['message'];
                                // $return->status = $res['status'];
                                // $return->result = $res['result'];   
                                
                                // //Update Flag is Sent
                                // if($return->status==1){
                                //     $params = array(
                                //         'message_date_sent' => date("YmdHis"),
                                //         'message_flag' => 1
                                //     );
                                //     $set_update=$this->Message_model->update_message($message_id,$params);
                                // }
                            }

                            //Kontak Terpilih by contact_type
                            $m=$message_contact_type;
                            for($a=0; $a < intval($contact_type_count); $a++){
                                $params_kontak = array(
                                    'contact_category_id'=> $m[$a]->contact_type_id
                                );
                                $get_all_contact = $this->Kontak_model->get_all_kontaks_nojoin($params_kontak,null,null,null,null,null,null);
                                // var_dump($get_all_contact);die;
                                foreach($get_all_contact as $v){
                                    // var_dump($v['contact_name']);die;
                                    $set_message_text = str_replace('#nama#',$v['contact_name'],$message_text);
                                    $interval = '+'.intval($a).' minute';
                                    $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                    
                                    $set_contact_id = !empty($v['contact_id']) ? $v['contact_id'] : null;
                                    $set_contact_name = !empty($v['contact_name']) ? $v['contact_name'] : null;
                                    $set_contact_phone = !empty($v['contact_phone_1']) ? $this->contact_number($v['contact_phone_1']) : null;
                                    $set_contact_email = !empty($v['contact_email_1']) ? $v['contact_email_1'] : null;                                                                                                            
                                    
                                    $params = array(
                                        'message_platform' => $message_platform,
                                        'message_contact_id' => $set_contact_id,
                                        'message_contact_name' => $set_contact_name,
                                        'message_subject' => $message_subject,
                                        // 'message_text' => $set_message_text,
                                        'message_session' => $this->random_code(20),
                                        'message_date_created' => $set_enque_date,
                                        'message_news_id' => $message_news,
                                        // 'message_url' => $get_news_url,
                                        'message_flag' => 0,
                                        'message_device_id' => $message_device
                                    );
                                    // var_dump($params);die;
                                    if($message_platform == 1){
                                        $params['message_contact_number'] = $set_contact_phone;
                                        $params['message_text'] = strip_tags($set_message_text);
                                    }else if($message_platform == 4){
                                        $params['message_contact_email'] = $set_contact_email;
                                        $params['message_text'] = $set_message_text;
                                    }
                                    // var_dump($params);die;
                                    $set_data=$this->Message_model->add_message($params);
                                    // $message_id = $set_data;

                                    //Sent WhatsApp Process
                                    // $do = $this->whatsapp_send_id($message_id);
                                    // $res = json_decode($do,true);      
                                    // $return->message = $res['message'];
                                    // $return->status = $res['status'];
                                    // $return->result = $res['result'];   
                                    
                                    // //Update Flag is Sent
                                    // if($return->status==1){
                                    //     $params = array(
                                    //         'message_date_sent' => date("YmdHis"),
                                    //         'message_flag' => 1
                                    //     );
                                    //     $set_update=$this->Message_model->update_message($message_id,$params);
                                    // }  
                                }                     
                            }

                            //Kontak Terpilih by contact_category_id // Not Used
                            $m=$message_contact_cat;
                            for($a=0; $a < intval($contact_cat_count); $a++){
                                $params_kontak = array(
                                    'contact_type'=> $m[$a]->contact_type_id
                                );
                                $get_all_contact = $this->Kontak_model->get_all_kontaks_nojoin($params_kontak,null,null,null,null,null,null);
                                // var_dump($get_all_contact);die;
                                foreach($get_all_contact as $v){
                                    // var_dump($v['contact_name']);die;
                                    $set_message_text = str_replace('#nama#',$v['contact_name'],$message_text);
                                    $interval = '+'.intval($a).' minute';
                                    $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                    
                                    $set_contact_id = !empty($v['contact_id']) ? $v['contact_id'] : null;
                                    $set_contact_name = !empty($v['contact_name']) ? $v['contact_name'] : null;
                                    $set_contact_phone = !empty($v['contact_phone_1']) ? $this->contact_number($v['contact_phone_1']) : null;
                                    $set_contact_email = !empty($v['contact_email_1']) ? $v['contact_email_1'] : null;                                                                                                            
                                    
                                    $params = array(
                                        'message_platform' => $message_platform,
                                        'message_contact_id' => $set_contact_id,
                                        'message_contact_name' => $set_contact_name,
                                        'message_subject' => $message_subject,
                                        'message_session' => $this->random_code(20),
                                        'message_date_created' => $set_enque_date,
                                        'message_news_id' => $message_news,
                                        // 'message_url' => $get_news_url,
                                        'message_flag' => 0,
                                        'message_device_id' => $message_device
                                    );
                                    // var_dump($params);die;
                                    if($message_platform == 1){
                                        $params['message_contact_number'] = $set_contact_phone;
                                        $params['message_text'] = strip_tags($set_message_text);
                                    }else if($message_platform == 4){
                                        $params['message_contact_email'] = $set_contact_email;
                                        $params['message_text'] = $set_message_text;
                                    }
                                    // var_dump($params);die;
                                    $set_data=$this->Message_model->add_message($params);
                                    // $message_id = $set_data;

                                    //Sent WhatsApp Process
                                    // $do = $this->whatsapp_send_id($message_id);
                                    // $res = json_decode($do,true);      
                                    // $return->message = $res['message'];
                                    // $return->status = $res['status'];
                                    // $return->result = $res['result'];   
                                    
                                    // //Update Flag is Sent
                                    // if($return->status==1){
                                    //     $params = array(
                                    //         'message_date_sent' => date("YmdHis"),
                                    //         'message_flag' => 1
                                    //     );
                                    //     $set_update=$this->Message_model->update_message($message_id,$params);
                                    // }  
                                }                     
                            }

                            //Kontak dari recipients_groups -> recipients
                            $m=$message_recipient_group;
                            for($a=0; $a < intval($recipient_group_count); $a++){
                                $params_rec = array(
                                    'recipient_group_id'=> $m[$a]->group_id
                                );
                                $get_all_recipient = $this->Recipient_model->get_all_recipient($params_rec,null,null,null,null,null,null);
                                // var_dump($get_all_recipient);die;
                                foreach($get_all_recipient as $v){
                                    // var_dump($v['contact_name']);die;
                                    $set_message_text = str_replace('#nama#',$v['recipient_name'],$message_text);
                                    $interval = '+'.intval($a).' minute';
                                    $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                    
                                    $set_contact_id = !empty($v['recipient_id']) ? $v['recipient_id'] : null;
                                    $set_contact_name = !empty($v['recipient_name']) ? $v['recipient_name'] : null;
                                    $set_contact_phone = !empty($v['recipient_phone']) ? $this->contact_number($v['recipient_phone']) : null;
                                    $set_contact_email = !empty($v['recipient_email']) ? $v['recipient_email'] : null;                                                                                                            
                                    
                                    $params = array(
                                        'message_platform' => $message_platform,
                                        'message_contact_id' => $set_contact_id,
                                        'message_contact_name' => $set_contact_name,
                                        'message_subject' => $message_subject,
                                        'message_session' => $this->random_code(20),
                                        'message_date_created' => $set_enque_date,
                                        'message_news_id' => $message_news,
                                        // 'message_url' => $get_news_url,
                                        'message_flag' => 0,
                                        'message_device_id' => $message_device
                                    );
                                    // var_dump($params);die;
                                    if($message_platform == 1){
                                        $params['message_contact_number'] = $set_contact_phone;
                                        $params['message_text'] = strip_tags($set_message_text);
                                    }else if($message_platform == 4){
                                        $params['message_contact_email'] = $set_contact_email;
                                        $params['message_text'] = $set_message_text;
                                    }
                                    // var_dump($params);die;
                                    $set_data=$this->Message_model->add_message($params);
                                    // $message_id = $set_data;

                                    //Sent WhatsApp Process
                                    // $do = $this->whatsapp_send_id($message_id);
                                    // $res = json_decode($do,true);      
                                    // $return->message = $res['message'];
                                    // $return->status = $res['status'];
                                    // $return->result = $res['result'];   
                                    
                                    // //Update Flag is Sent
                                    // if($return->status==1){
                                    //     $params = array(
                                    //         'message_date_sent' => date("YmdHis"),
                                    //         'message_flag' => 1
                                    //     );
                                    //     $set_update=$this->Message_model->update_message($message_id,$params);
                                    // }  
                                }                     
                            }

                            //Kontak dari recipients (Birthday)
                            $m=$message_recipient_birthday;
                            for($a=0; $a < intval($recipient_birthday_count); $a++){
                                $params_rec = array(
                                    'recipient_id'=> $m[$a]->recipient_id
                                );
                                $get_recipient = $this->Recipient_model->get_recipient($m[$a]->recipient_id);

                                $set_message_text = str_replace('#nama#',$get_recipient['recipient_name'],$message_text);
                                $interval = '+'.intval($a).' minute';
                                $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                
                                $set_contact_id = !empty($get_recipient['recipient_id']) ? $get_recipient['recipient_id'] : null;
                                $set_contact_name = !empty($get_recipient['recipient_name']) ? $get_recipient['recipient_name'] : null;
                                $set_contact_phone = !empty($get_recipient['recipient_phone']) ? $this->contact_number($get_recipient['recipient_phone']) : null;
                                $set_contact_email = !empty($get_recipient['recipient_email']) ? $get_recipient['recipient_email'] : null;                                                                                                            
                                
                                $params = array(
                                    'message_platform' => $message_platform,
                                    'message_contact_id' => $set_contact_id,
                                    'message_contact_name' => $set_contact_name,
                                    'message_subject' => $message_subject,
                                    'message_session' => $this->random_code(20),
                                    'message_date_created' => $set_enque_date,
                                    'message_news_id' => $message_news,
                                    // 'message_url' => $get_news_url,
                                    'message_flag' => 0,
                                    'message_device_id' => $message_device
                                );
                                // var_dump($params);die;
                                if($message_platform == 1){
                                    $params['message_contact_number'] = $set_contact_phone;
                                    $params['message_text'] = strip_tags($set_message_text);
                                }else if($message_platform == 4){
                                    $params['message_contact_email'] = $set_contact_email;
                                    $params['message_text'] = $set_message_text;
                                }

                                $set_data=$this->Message_model->add_message($params);
                                // $message_id = $set_data;

                                //Sent WhatsApp Process
                                // $do = $this->whatsapp_send_id($message_id);
                                // $res = json_decode($do,true);      
                                // $return->message = $res['message'];
                                // $return->status = $res['status'];
                                // $return->result = $res['result'];   
                                
                                // //Update Flag is Sent
                                // if($return->status==1){
                                //     $params = array(
                                //         'message_date_sent' => date("YmdHis"),
                                //         'message_flag' => 1
                                //     );
                                //     $set_update=$this->Message_model->update_message($message_id,$params);
                                // }                
                            }                            

                            $return->status=1;
                            $return->message='Sukses, Pesan akan dikirim';
                            break;
                        default:
                    }
                    $return->action=$action;              
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $message_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;   
                    if(intval($message_id) > 0){        
                        $datas = $this->Message_model->get_message($message_id);
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'messages',
                                'actvity_table_id' => $message_id,
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
                    $message_id = !empty($this->input->post('id')) ? $this->input->post('id') : $data['id'];
                    $message_name = !empty($this->input->post('name')) ? $this->input->post('name') : $data['name'];
                    $message_flag = !empty($this->input->post('status')) ? $this->input->post('status') : $data['status'];

                    $params = array(
                        'message_name' => $message_name,
                        'message_date_updated' => date("YmdHis"),
                        'message_flag' => $message_flag
                    );

                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */
                   
                    $set_update=$this->Message_model->update_message($message_id,$params);
                    if($set_update==true){
                        
                        $data = $this->Message_model->get_message($message_id);
                            
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
                                if ($data && $message_id) {
                                    $params_image = array(
                                        'message_image' => base_url($upload_directory) . $raw_photo
                                    );
                                    if (!empty($data['message_image'])) {
                                        if (file_exists($upload_path_directory . $data['message_image'])) {
                                            unlink($upload_path_directory . $data['message_image']);
                                        }
                                    }
                                    $stat = $this->Message_model->update_message(array('message_id' => $message_id), $params_image);
                                }
                            }
                        }
                        //End of Save Image
                        /* Activity */
                            /*
                                $params = array(
                                    'activity_user_id' => $session['user_data']['user_id'],
                                    'activity_action' => 4,
                                    'activity_table' => 'messages',
                                    'activity_table_id' => $id,
                                    'activity_text_1' => '',
                                    'activity_text_2' => ucwords(strtolower($message_name),
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 0
                                );
                                $this->save_activity($params);
                            */                    
                        /* End Activity */
                        $return->status  = 1;
                        $return->message = 'Berhasil memperbarui '.$message_name;
                    }else{
                        $return->message='Gagal memperbarui '.$message_name;
                    }    
                    $return->action=$action;                                
                    break;          
                case "delete":  
                    $message_id   = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                    $message_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;                                

                    if(intval($message_id) > 0){
                        $get_data=$this->Message_model->get_message($message_id);
                        $set_data=$this->Message_model->delete_message($message_id);                
                        if($set_data==true){    
                            /*
                            if (file_exists($get_data['message_image'])) {
                                unlink($get_data['message_image']);
                            } 
                            */                            
                            /* Activity */
                                /*
                                $params = array(
                                    'activity_user_id' => $session['user_data']['user_id'],
                                    'activity_action' => $act,
                                    'activity_table' => 'messages',
                                    'activity_table_id' => $id,
                                    'activity_text_1' => '',
                                    'activity_text_2' => ucwords(strtolower($message_name)),
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 0
                                );
                                $this->save_activity($params);                               
                                */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil menghapus'.$message_name;
                        }else{
                            $return->message='Gagal menghapus '.$message_name;
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;                              
                    break;             
                case "contact-count":
                    $branch = !empty($this->input->post('branch')) ? $this->input->post('branch') : null;
                    if(intval($branch) > 0){
                        $params = array(
                            'contact_branch_id' => $branch
                        );
                        $get_kontak = $this->Kontak_model->get_all_kontak_count($params,null);
                        $return->result = $get_kontak;
                        $return->message = 'Kontak '.$get_kontak.' Data ditemukan';
                        if($get_kontak > 0){
                            $return->status  = 1;
                        }
                    }else{
                        $return->message = 'Kontak tidak ditemukan';
                    }
                    break;                    
                case "whatsapp-info": die;
                    //Get WhatsApp Info
                    $get_execute = $this->whatsapp_info();
                    $response = json_decode($get_execute,true);
                    // var_dump($response);die;
                    $return->status = ($response['status'] == 1) ? 1 : 0;
                    $return->message = $response['message'];  
                    $return->result = $response['result'];
                    break;
                case "whatsapp-scan-qrcode": die; //belum selesai
                    //Get WhatsApp QR Code
                    $get_execute = $this->whatsapp_qrcode();
                    $response = json_decode($get_execute,true);
                    // var_dump($response);die;
                    $return->status = ($response['status'] == 1) ? 1 : 0;
                    $return->message = $response['message'];  
                    $return->result = $response['result'];
                    $return->sender = $response['sender'];
                    break;
                case "whatsapp-send-message":  
                    $message_id = !empty($this->input->post('id')) ? $this->input->post('id') : null; 
                    $message_name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null; 
                    $message_number = !empty($this->input->post('nomor')) ? $this->input->post('nomor') : null; 
                    $message_text = !empty($this->input->post('teks')) ? json_decode($this->input->post('teks')) : null;
                    $contact_phone = $this->contact_number($message_number);
                    if(intval($contact_phone)){
                        if(intval($message_id) > 0){
                            $datas = $this->Message_model->get_message($message_id);
                            if($datas==true){
                                $interval = '+1 minute';                            
                                $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                $params = array(
                                    'message_platform' => 1,
                                    'message_contact_id' => $datas['contact_id'],
                                    'message_contact_name' => $message_name,                                
                                    'message_contact_number' => $contact_phone,
                                    'message_text' => $message_text,
                                    'message_session' => $this->random_code(20),
                                    'message_date_created' => $set_enque_date,
                                    'message_flag' => 0,
                                    'message_device_id' => $datas['message_device_id'],
                                    'message_branch_id' => $session_branch_id
                                );
                                $set_data=$this->Message_model->add_message($params);
                                // $set_data=$this->Message_model->update_message($message_id,$params);                            
                                $message_id = $set_data;

                                //Sent WhatsApp Process
                                $do = $this->whatsapp_send_id($message_id);
                                $res = json_decode($do,true);
                                
                                $return->status  = $res['status'];
                                $return->message = $res['message'];
                                $return->result = $res['result'];                            
                                
                                // //Update Flag is Sent
                                // if($return->status==1){
                                //     $params = array(
                                //         'message_date_sent' => date("YmdHis"),
                                //         'message_flag' => 1
                                //     );
                                //     $set_update=$this->Message_model->update_message($message_id,$params);
                                // }
                                if($message_id){
                                    $return->status = 1;
                                    $return->message = 'Pesan akan segera dikirim';
                                }
                                // echo json_encode($return);die;
                            }
                        }else{
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message = 'Nomor tidak valid';
                    }                                        
                    break;
                case "whatsapp-send-message-invoice":
                    $trans_id       = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : null; 
                    $contact_id     = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : null; 
                    $contact_name   = !empty($this->input->post('contact_name')) ? $this->input->post('contact_name') : null;
                    $contact_phone  = !empty($this->input->post('contact_phone')) ? $this->input->post('contact_phone') : null;                    
                    $device_id      = !empty($this->input->post('device_id')) ? $this->input->post('device_id') : null;
                    if(intval($contact_phone)){                              
                        if($trans_id > 0){
                            $contact_phone = $this->contact_number($contact_phone);
                            $params = array(
                                'trans_contact_name' => $contact_name,
                                'trans_contact_phone' => $contact_phone
                            );
                            // var_dump($params);die;
                            $set_update = $this->Transaksi_model->update_transaksi($trans_id,$params);
                            if($set_update){
                                $params = array(
                                    'user_id' => $session_user_id,
                                    'contact_id' => $contact_id,
                                    'trans_id' => $trans_id,
                                    'device_id' => $device_id
                                );
                                $do = $this->whatsapp_template('sales-sell-invoice',1,$params);
                                $res = json_decode($do,true);      
                                $return->message = $res['message'];
                                $return->status = $res['status'];
                                $return->result = $res['result'];                                                  
                            }
                        }else{
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message = 'Nomor tidak valid'; 
                    }                        
                    break;
                case "whatsapp-send-message-inventory-goods-out":
                    $trans_id       = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : null; 
                    $contact_id     = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : null; 
                    $contact_name   = !empty($this->input->post('contact_name')) ? $this->input->post('contact_name') : null;
                    $contact_phone  = !empty($this->input->post('contact_phone')) ? $this->input->post('contact_phone') : null;                    
                    $device_id      = !empty($this->input->post('device_id')) ? $this->input->post('device_id') : null;
                    if(intval($contact_phone)){                              
                        if($trans_id > 0){
                            $contact_phone = $this->contact_number($contact_phone);
                            $params = array(
                                'trans_contact_name' => $contact_name,
                                'trans_contact_phone' => $contact_phone
                            );
                            $set_update = $this->Transaksi_model->update_transaksi($trans_id,$params);
                            if($set_update){
                                $params = array(
                                    'user_id' => $session_user_id,
                                    'contact_id' => $contact_id,
                                    'trans_id' => $trans_id,
                                    'device_id' => $device_id
                                );
                                $do = $this->whatsapp_template('inventory-goods-out',1,$params);
                                $res = json_decode($do,true);      
                                $return->message = $res['message'];
                                $return->status = $res['status'];
                                $return->result = $res['result'];                                                  
                            }
                        }else{
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message = 'Nomor tidak valid'; 
                    }                        
                    break;
                                    
                case "whatsapp-send-message-invoice-trans-order":
                    $trans_id       = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : null; 
                    $contact_id     = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : null; 
                    $contact_name   = !empty($this->input->post('contact_name')) ? $this->input->post('contact_name') : null;
                    $contact_phone  = !empty($this->input->post('contact_phone')) ? $this->input->post('contact_phone') : null;       
                    $device_id      = !empty($this->input->post('device_id')) ? $this->input->post('device_id') : null;                                        
                    if(intval($contact_phone)){
                        if($trans_id > 0){
                            $contact_phone = $this->contact_number($contact_phone);
                            $params = array(
                                'trans_contact_name' => $contact_name,
                                'trans_contact_phone' => $contact_phone
                            );
                            $set_update = $this->Transaksi_model->update_transaksi($trans_id,$params);
                            if($set_update){
                                $params = array(
                                    'user_id' => $session_user_id,
                                    'contact_id' => $contact_id,
                                    'trans_id' => $trans_id,
                                    'device_id' => $device_id
                                );
                                $do = $this->whatsapp_template('sales-sell-invoice-trans-order',1,$params);
                                $res = json_decode($do,true);      
                                $return->message = $res['message'];
                                $return->status = $res['status'];
                                $return->result = $res['result'];                               
                            }
                        }else{
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message = 'Nomor tidak valid';
                    }                        
                    break;    
                case "whatsapp-send-message-invoice-booking":
                    $trans_id       = !empty($this->input->post('order_id')) ? $this->input->post('order_id') : null; 
                    $contact_name   = !empty($this->input->post('contact_name')) ? $this->input->post('contact_name') : null;
                    $contact_phone  = !empty($this->input->post('contact_phone')) ? $this->input->post('contact_phone') : null;                                     
                    if(intval($contact_phone)){
                        if($trans_id > 0){
                            // $contact_phone = $this->contact_number($contact_phone);
                            // $params = array(
                            //     'trans_contact_name' => $contact_name,
                            //     'trans_contact_phone' => $contact_phone
                            // );
                            // $set_update = $this->Transaksi_model->update_transaksi($trans_id,$params);
                            // if($set_update){
                                $params = array(
                                    'order_id' => $trans_id,
                                    'contact_name' => $contact_name,
                                    'contact_phone' => $contact_phone,
                                );
                                // var_dump($params);die;
                                $do = $this->whatsapp_template('sales-sell-invoice-booking',1,$params);
                                $res = json_decode($do,true);      
                                $return->message = $res['message'];
                                $return->status = $res['status'];
                                $return->result = $res['result'];                               
                            // }
                        }else{
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message = 'Nomor tidak valid';
                    }                        
                    break;    
                case "whatsapp-send-message-reminder-rebooking":
                    $trans_id       = !empty($this->input->post('order_id')) ? $this->input->post('order_id') : null; 
                    $contact_name   = !empty($this->input->post('contact_name')) ? $this->input->post('contact_name') : null;
                    $contact_phone  = !empty($this->input->post('contact_phone')) ? $this->input->post('contact_phone') : null;                                     
                    if(intval($contact_phone)){
                        if($trans_id > 0){
                            // $contact_phone = $this->contact_number($contact_phone);
                            // $params = array(
                            //     'trans_contact_name' => $contact_name,
                            //     'trans_contact_phone' => $contact_phone
                            // );
                            // $set_update = $this->Transaksi_model->update_transaksi($trans_id,$params);
                            // if($set_update){
                                $params = array(
                                    'order_id' => $trans_id,
                                    'contact_name' => $contact_name,
                                    'contact_phone' => $contact_phone,
                                );
                                // var_dump($params);die;
                                $do = $this->whatsapp_template('sales-sell-invoice-rebooking',1,$params);
                                $res = json_decode($do,true);      
                                $return->message = $res['message'];
                                $return->status = $res['status'];
                                $return->result = $res['result'];                               
                            // }
                        }else{
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message = 'Nomor tidak valid';
                    }                        
                    break;    
                case "request-session-for-broadcast":
                    $return->status = 1;
                    $return->result = $this->create_session(10);
                    // $return->result = date("YmdHis");           
                    break;           
                case "create-whatsapp-excel-broadcast":
                    $broadcast_text = !empty($this->input->post('broadcast_text')) ? $this->input->post('broadcast_text') : '-';                           
                    $broadcast_session = !empty($this->input->post('broadcast_session')) ? $this->input->post('broadcast_session') : '';
                    $broadcast_file = !empty($this->input->post('broadcast_file')) ? $this->input->post('broadcast_file') : '';
                    $broadcast_group = !empty($this->input->post('broadcast_group')) ? $this->input->post('broadcast_group') : '';                            
                    $broadcast_delay = !empty($this->input->post('broadcast_delay')) ? $this->input->post('broadcast_delay') : '';

                    if(isset($_FILES["broadcast_file"]["name"])){
                        // upload
                        $file_tmp   = $_FILES['broadcast_file']['tmp_name'];
                        $file_name  = $_FILES['broadcast_file']['name'];
                        $file_size  = $_FILES['broadcast_file']['size'];
                        $file_type  = $_FILES['broadcast_file']['type'];
                        // move_uploaded_file($file_tmp,"uploads/".$file_name); // simpan filenya di folder uploads
                        
                        $object = PHPExcel_IOFactory::load($file_tmp);
                        $number = 1;
                        foreach($object->getWorksheetIterator() as $worksheet){

                            $highestRow    = $worksheet->getHighestRow();
                            $highestColumn = $worksheet->getHighestColumn();

                            for($row=3; $row <= $highestRow; $row++){
                                // $params[] = array(
                                //     'contact_phone_1' => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                                //     'contact_name' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                //     'contact_type' => 2,
                                //     'contact_group_session' => $broadcast_track,                           
                                // );
                                $contact_name = !empty($worksheet->getCellByColumnAndRow(1, $row)->getValue()) ? ucwords($worksheet->getCellByColumnAndRow(1, $row)->getValue()) : null;
                                
                                $set_broadcast_text = str_replace('#nama#',$contact_name,$broadcast_text);

                                $params[] = array(
                                    'message_contact_number' => $this->contact_number($worksheet->getCellByColumnAndRow(0, $row)->getValue()),
                                    'message_contact_name' => $contact_name,
                                    'message_group_session' => $broadcast_session,   
                                    'message_text' => $set_broadcast_text,
                                    'message_session' => $this->create_session(20),    
                                    'message_date_created' => date("YmdHis"),
                                    'message_flag' => 0,
                                    // 'message_source' => 2,
                                    'message_platform' => 1,
                                    'message_branch_id' => $session_branch_id,
                                    'message_device_id' => !empty($this->input->post('broadcast_device')) ? $this->input->post('broadcast_device') : null
                                );                                
                            }
                        }
                        $this->db->insert_batch('messages', $params);
                        /*
                            $prepare = array(
                                'prepare_message_track_session' => $broadcast_track,
                                'prepare_message_group_session' => $broadcast_session,
                                'prepare_device_group_id' => $broadcast_group,
                                'prepare_date_created' => date('YmdHis'),
                                'prepare_text' => $broadcast_text
                            );
                            $this->Message_model->add_message_prepare($prepare);
                        */
                        
                        // CALL SP For Generate Message Broadcast
                        // $this->whatsapp_broadcast_prepare($broadcast_session);
                        
                        //Opsi 1 - CURL
                        // $do = $this->whatsapp_send_group($broadcast_track);
                        // $res = json_decode($do,true);  

                        // $return->broadcast = array(
                        //     'status' => !empty($res['status']) ? 1 : 1,
                        //     'message' => !empty($res['status']) ? 'Broadcast dilaksanakan' : 'Gagal Broadcast',
                        //     'group_session' => $broadcast_session,
                        //     'track_session' => $broadcast_track
                        // );
                        // $return->message = $res['message'];
                        // $return->status = $res['status'];
                        // $return->result = $res['result'];                            

                        //Opsi 2 - Biarkan Cronjob Bekerja
                        $return->message = 'Berhasil menyimpan broadcast whatsapp';
                        $return->status = 1;     
                        $return->result = array();     
                        $return->broadcast = array(
                            'status' => 1,
                            'message' => 'Broadcast dilaksanakan',
                            'group_session' => $broadcast_session
                        );                                
                    }                            
                    break;   
                case "create-email-excel-broadcast":
                    $broadcast_text = !empty($this->input->post('broadcast_text')) ? $this->input->post('broadcast_text') : '-';                           
                    $broadcast_session = !empty($this->input->post('broadcast_session')) ? $this->input->post('broadcast_session') : '';
                    $broadcast_file = !empty($this->input->post('broadcast_file')) ? $this->input->post('broadcast_file') : '';
                    $broadcast_group = !empty($this->input->post('broadcast_group')) ? $this->input->post('broadcast_group') : '';                            
                    $broadcast_delay = !empty($this->input->post('broadcast_delay')) ? $this->input->post('broadcast_delay') : '';

                    if(isset($_FILES["broadcast_file"]["name"])){
                        // upload
                        $file_tmp   = $_FILES['broadcast_file']['tmp_name'];
                        $file_name  = $_FILES['broadcast_file']['name'];
                        $file_size  = $_FILES['broadcast_file']['size'];
                        $file_type  = $_FILES['broadcast_file']['type'];
                        // move_uploaded_file($file_tmp,"uploads/".$file_name); // simpan filenya di folder uploads
                        
                        $object = PHPExcel_IOFactory::load($file_tmp);
                        $number = 1;
                        foreach($object->getWorksheetIterator() as $worksheet){

                            $highestRow    = $worksheet->getHighestRow();
                            $highestColumn = $worksheet->getHighestColumn();

                            for($row=3; $row <= $highestRow; $row++){
                                // $params[] = array(
                                //     'contact_phone_1' => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                                //     'contact_name' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                //     'contact_type' => 2,
                                //     'contact_group_session' => $broadcast_track,                           
                                // );
                                $contact_name = !empty($worksheet->getCellByColumnAndRow(1, $row)->getValue()) ? ucwords($worksheet->getCellByColumnAndRow(1, $row)->getValue()) : null;
                                
                                $set_broadcast_text = str_replace('#nama#',$contact_name,$broadcast_text);

                                $params[] = array(
                                    'message_contact_email' => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                                    'message_contact_name' => $contact_name,
                                    'message_group_session' => $broadcast_session,   
                                    'message_text' => $set_broadcast_text,
                                    'message_session' => $this->create_session(20),    
                                    'message_date_created' => date("YmdHis"),
                                    'message_flag' => 0,
                                    // 'message_source' => 2,
                                    'message_platform' => 4,
                                    'message_branch_id' => $session_branch_id,
                                    'message_device_id' => !empty($this->input->post('broadcast_device')) ? $this->input->post('broadcast_device') : null                                    
                                );                                
                            }
                        }
                        $this->db->insert_batch('messages', $params);
                        /*
                            $prepare = array(
                                'prepare_message_track_session' => $broadcast_track,
                                'prepare_message_group_session' => $broadcast_session,
                                'prepare_device_group_id' => $broadcast_group,
                                'prepare_date_created' => date('YmdHis'),
                                'prepare_text' => $broadcast_text
                            );
                            $this->Message_model->add_message_prepare($prepare);
                        */
                        
                        // CALL SP For Generate Message Broadcast
                        // $this->whatsapp_broadcast_prepare($broadcast_session);
                        
                        //Opsi 1 - CURL
                        // $do = $this->whatsapp_send_group($broadcast_track);
                        // $res = json_decode($do,true);  

                        // $return->broadcast = array(
                        //     'status' => !empty($res['status']) ? 1 : 1,
                        //     'message' => !empty($res['status']) ? 'Broadcast dilaksanakan' : 'Gagal Broadcast',
                        //     'group_session' => $broadcast_session,
                        //     'track_session' => $broadcast_track
                        // );
                        // $return->message = $res['message'];
                        // $return->status = $res['status'];
                        // $return->result = $res['result'];                            

                        //Opsi 2 - Biarkan Cronjob Bekerja
                        $return->message = 'Berhasil menyimpan broadcast email';
                        $return->status = 1;     
                        $return->result = array();     
                        $return->broadcast = array(
                            'status' => 1,
                            'message' => 'Broadcast dilaksanakan',
                            'group_session' => $broadcast_session
                        );                                
                    }                            
                    break;                                                                
                case "email-send-message":  
                    $message_id = !empty($this->input->post('id')) ? $this->input->post('id') : null; 
                    $message_name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null; 
                    $message_email = !empty($this->input->post('email')) ? $this->input->post('email') : null; 
                    $message_text = !empty($this->input->post('teks')) ? json_decode($this->input->post('teks')) : null;
                    // $contact_phone = $this->contact_number($message_number);
                    if(strlen($message_email) > 0){
                        if(intval($message_id) > 0){
                            $datas = $this->Message_model->get_message($message_id);
                            if($datas==true){
                                $interval = '+1 minute';                            
                                $set_enque_date = date("Y-m-d H:i:s", strtotime($interval,strtotime(date("Y-m-d H:i:s")))); 
                                $message_session = $this->random_code(20);
                                $params = array(
                                    'message_platform' => 4,
                                    'message_contact_id' => $datas['contact_id'],
                                    'message_contact_name' => $message_name,                                
                                    'message_contact_email' => $message_email,
                                    'message_text' => $message_text,
                                    'message_session' => $message_session,
                                    'message_date_created' => $set_enque_date,
                                    'message_flag' => 0,
                                    'message_branch_id' => $session_branch_id
                                    // 'message_device_id' => $datas['message_device_id']
                                );
                                $set_data=$this->Message_model->add_message($params);                      
                                $message_id = $set_data;

                                //Sent Email Process
                                // $do = $this->email_send_message(array('message_session' => $message_session,'message_platform'=>4));
                                // $res = json_decode($do,true);

                                // $return->status  = $res['status'];
                                // $return->message = $res['message'];
                                // $return->result = $res['result'];                            
                                
                                // //Update Flag is Sent
                                // if($return->status==1){
                                //     $params = array(
                                //         'message_date_sent' => date("YmdHis"),
                                //         'message_flag' => 1
                                //     );
                                //     $set_update=$this->Message_model->update_message($message_id,$params);
                                // }
                                if($message_id){
                                    $return->status = 1;
                                    $return->message = 'Pesan akan segera dikirim';
                                }
                            }
                        }else{
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message = 'Nomor tidak valid';
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
     
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            $data['title'] = 'Pesan';
            $data['_view'] = 'layouts/admin/menu/message/message';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/message/message_js.php',$data);         
        }
    }

    function whatsapp_info(){ die;
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_server    = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');
        $whatsapp_token     = $this->config->item('whatsapp_token');
        $whatsapp_key       = $this->config->item('whatsapp_key');
        $whatsapp_auth      = $this->config->item('whatsapp_auth');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');

        /* Configuration */
        $url = $whatsapp_server.$whatsapp_action['check-status'].'&sender='.$whatsapp_sender;
        // var_dump($url);die;
        /* Action */
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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
        curl_close($curl);

        /* Result */
        $get_response = json_decode($response,true);
        if($get_response['status'] == 1){
            $return->status = 1;
            $return->message = $get_response['message'];
            $return->result = array(
                'auth' => $whatsapp_auth,
                'sender' => $whatsapp_sender,
                'info' => $get_response['message']
            );
        }else{
            $return->message = 'Gagal check';
            $return->result = array(
                'auth' => $whatsapp_auth,
                'sender' => $whatsapp_sender,
                'info' => 'Tidak diketahui'
            );            
        }

        return json_encode($return);
    }
    function whatsapp_qrcode(){ die;
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_server    = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');
        $whatsapp_token     = $this->config->item('whatsapp_token');
        $whatsapp_key       = $this->config->item('whatsapp_key');
        $whatsapp_auth      = $this->config->item('whatsapp_auth');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');

        /* Configuration */
        $url = $whatsapp_server.$whatsapp_action['request-qrcode'].'&sender='.$whatsapp_sender;
        // var_dump($url);die;
        /* Action */
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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
        curl_close($curl);

        /* Result */
        $get_response = json_decode($response,true);
        $return->status = $get_response['status'];
        $return->message = $get_response['message'];
        $return->result = $get_response['result'];
        $return->sender = $whatsapp_sender;
        return json_encode($return);
    }
    function whatsapp_restart(){ die;
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_server    = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');
        $whatsapp_token     = $this->config->item('whatsapp_token');
        $whatsapp_key       = $this->config->item('whatsapp_key');
        $whatsapp_auth      = $this->config->item('whatsapp_auth');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');

        /* Configuration */
        $url = $whatsapp_server.$whatsapp_action['restart'].'&sender='.$whatsapp_sender;
        // var_dump($url);die;
        /* Action */
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
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
        curl_close($curl);

        /* Result */
        $get_response = json_decode($response,true);
        if($get_response['status'] == 1){
            $return->status = 1;
            $return->message = $get_response['message'];
            $return->result = array(
                'auth' => $whatsapp_auth,
                'sender' => $whatsapp_sender,
                'info' => $get_response['message']
            );
        }else{
            $return->message = 'Gagal check';
            $return->result = array(
                'auth' => $whatsapp_auth,
                'sender' => $whatsapp_sender,
                'info' => 'Tidak diketahui'
            );            
        }

        return json_encode($return);
    }

    //Sending Gateway WhatsApp
    function whatsapp_send($params){ //Main Send Message
        /* Example
            $params = array(
                'header' => 'Judul',	
                'file' => 'https://www.planetware.com/wpimages/2020/02/france-in-pictures-beautiful-places-to-photograph-eiffel-tower.jpg',
                'content' => 'Isi Pesan',	
                'recipient' => array(
                    array('number' => '6281225518118', 'name' => 'Joe'),                                                                                                                                                             
                ),
                'footer' => '🖥️ Pesan ini dikirim oleh System'
            );
        */       
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';

        $whatsapp_server    = $this->config->item('whatsapp_server');        
        $whatsapp_action    = $this->config->item('whatsapp_action');  
        $whatsapp_action_v1    = $this->config->item('whatsapp_action_v1');                 
        $whatsapp_sender    = $this->config->item('whatsapp_sender');         
        $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_token     = $this->config->item('whatsapp_token');
        $whatsapp_key       = $this->config->item('whatsapp_key');
        $whatsapp_auth      = $this->config->item('whatsapp_auth');

        if(count($params) > 0){
            $content    = $params['content'];
            $recipient  = $params['recipient'];
            $file       = $params['file'];
        
            if(count($recipient) > 0){
                
                $header = (strlen($params['header']) > 2) ? '*'.$params['header']."*"."\r\n\r\n" : '';
                $footer = (strlen($params['footer']) > 2) ? "\r\n".$params['footer']."\r\n" : '';  
                // var_dump($recipient);die;
                for($i=0; $i<count($recipient); $i++){
                    if($whatsapp_vendor == 'umbrella.co.id'){
                        $set_content = rawurlencode($header.$content.$footer);

                        // Detect Message have a Caption
                        if(!empty($file)){
                            $caption = "Attachment";
                            $url_file = '&auth='.$whatsapp_auth.'&recipient='.$recipient[$i]['number'].'&sender='.$whatsapp_sender.'&content='.$set_content.'&file='.$file;
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $whatsapp_server.'devices?action=send-message'.$url_file,
                                CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_SSL_VERIFYPEER => FALSE
                            ));              
                            $response = curl_exec($curl);                                        
                        }else{ //Dont have a caption
                            $url = '&auth='.$whatsapp_auth.'&recipient='.$recipient[$i]['number'].'&sender='.$whatsapp_sender.'&content='.$set_content;

                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $whatsapp_server.'devices?action=send-message'.$url,
                                CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_SSL_VERIFYPEER => FALSE
                            ));       
                            $response = curl_exec($curl);
                        }

                        /* Result CURL / API */
                        $get_response = json_decode($response,true);
                        
                        //Do Update if have message_id
                        if(($get_response) && ($get_response['status'] == 1)){
                            if(!empty($recipient[$i]['message_id'])){
                                $this->Message_model->update_message($recipient[$i]['message_id'],array('message_flag'=>1,'message_date_sent'=>date("YmdHis")));
                            }
                        }

                        $return->result  = $get_response['result']; // Result
                        $return->status  = $get_response['status']; // 1 / 0
                        $return->message = $get_response['message']; // Berhasil / Gagal 
                    }elseif($whatsapp_vendor=='wam.umbrella.co.id'){

                        //Send if have Client ID
                        if(!empty($recipient[$i]['message_device_number'])){
                            $client = $recipient[$i]['message_device_client'];
                            $bearer = array(
                                'Authorization: Bearer '.$recipient[$i]['message_device_token']
                            );
                        }else{ //From whatsapp.php config
                            $client = $whatsapp_key;
                            $bearer = array(
                                'Authorization: Bearer '.$whatsapp_token
                            );
                        }

                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $whatsapp_server.$whatsapp_action_v1['send-message'],
                            CURLOPT_RETURNTRANSFER => 1,
                            // CURLOPT_TIMEOUT => 0,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array(
                                'mobile' => $this->contact_number($recipient[$i]['number']),
                                'text' => $header.$content.$footer,
                                'key' => $client
                            ),
                            CURLOPT_HTTPHEADER => $bearer,
                        ));
                        $response = curl_exec($curl); 
                        curl_close($curl);
                        $get_response = json_decode($response, true);                                               
                        // var_dump($get_response);die;
                        if ($get_response) {                       
                            $return->status = ($get_response['status'] == 1) ? 1 : 0;
                            $return->message = ($return->status == 1) ? 'Success' : $get_response['message'];
                            $return->result = [];

                            //Do Update if have message_id
                            if(!empty($recipient[$i]['message_id'])){
                                $this->Message_model->update_message($recipient[$i]['message_id'],array('message_flag'=>1,'message_date_sent'=>date("YmdHis")));
                            }
                        } else {                            
                            $return->message = 'Not Connected';
                        }                         
                    }elseif($whatsapp_vendor=='fonnte.com'){

                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $whatsapp_server,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array(
                                'target' => $this->contact_number($recipient[$i]['number']),
                                'message' => $header.$content.$footer,
                                'country_code' => '62'
                            ), 
                            CURLOPT_HTTPHEADER => array(
                                'Authorization: '.$whatsapp_token
                            ),
                        ));                       
                        $response = curl_exec($curl); 
                        curl_close($curl);
                        $get_response = json_decode($response, true);                                               
                        // var_dump($whatsapp_server);die;
                        if ($get_response) {                       
                            $return->status = ($get_response['status'] == 1) ? 1 : 0;
                            $return->message = ($return->status == 1) ? 'Success' : $get_response['message'];
                            $return->result = [];

                            //Do Update if have message_id
                            if(!empty($recipient[$i]['message_id'])){
                                $this->Message_model->update_message($recipient[$i]['message_id'],array('message_flag'=>1,'message_date_sent'=>date("YmdHis")));
                            }
                        } else {                            
                            $return->message = 'Not Connected';
                        }                         
                    }
                }               
            }else{
                $return->message='Penerima tidak ada';
            }
        }else{
          $return->message='Params doest exist';
        }
        $return->params = $params;
        return $return;
    }    
    function whatsapp_send_message($params){ //Works
        /* Example
            $params = array(
                'header' => 'Judul',	
                'file' => 'https://www.planetware.com/wpimages/2020/02/france-in-pictures-beautiful-places-to-photograph-eiffel-tower.jpg',
                'content' => 'Isi Pesan',	
                'recipient' => array(
                    array('number' => '6281225518118', 'name' => 'Joe'),                                                                                                                                                             
                ),
                'footer' => '🖥️ Pesan ini dikirim oleh System'
            );
        */       
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';

        // $whatsapp_server    = $this->config->item('whatsapp_server').'devices?action=send-message';
        // $whatsapp_auth      = $this->config->item('whatsapp_auth');
        // $whatsapp_sender    = $this->config->item('whatsapp_sender');

        $whatsapp_server    = $this->config->item('whatsapp_server');        
        $whatsapp_action    = $this->config->item('whatsapp_action');         
        $whatsapp_sender    = $this->config->item('whatsapp_sender');         
        $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_token     = $this->config->item('whatsapp_token');
        $whatsapp_key       = $this->config->item('whatsapp_key');
        $whatsapp_auth      = $this->config->item('whatsapp_auth');

        if(count($params) > 0){
            $content    = $params['content'];
            $recipient  = $params['recipient'];
            $file       = $params['file'];
        
            if(count($recipient) > 0){
                $header = '*'.$params['header']."*"."\r\n\r\n";
                $footer = "\r\n".$params['footer']."\r\n";
                for($i=0; $i<count($recipient); $i++){
                    
                    if($whatsapp_vendor == 'umbrella.co.id'){
                        $set_content = rawurlencode($header.$content.$footer);

                        // Detect Message have a Caption
                        if(!empty($file)){
                            $caption = "Attachment";
                            $url_file = '&auth='.$whatsapp_auth.'&recipient='.$recipient[$i]['number'].'&sender='.$whatsapp_sender.'&content='.$set_content.'&file='.$file;
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $whatsapp_server.'devices?action=send-message'.$url_file,
                                CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_SSL_VERIFYPEER => FALSE
                            ));              
                            $response = curl_exec($curl);                                        
                        }else{ //Dont have a caption
                            $url = '&auth='.$whatsapp_auth.'&recipient='.$recipient[$i]['number'].'&sender='.$whatsapp_sender.'&content='.$set_content;

                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $whatsapp_server.'devices?action=send-message'.$url,
                                CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYHOST => FALSE, CURLOPT_SSL_VERIFYPEER => FALSE
                            ));       
                            $response = curl_exec($curl);
                        }

                    }elseif($whatsapp_vendor=='fonnte.com'){

                    }elseif($whatsapp_vendor=='wam.umbrella.co.id'){
                        // $queryParams = http_build_query([
                        //     'client_id' => $whatsapp_key,
                        //     'mobile' => $recipient[$i]['number'],
                        //     'text' => $header.$content.$footer,
                        //     'token' => $whatsapp_token
                        // ]);
                        // $apiUrl = $whatsapp_server . '?' . $queryParams;
        
                        // $response = file_get_contents($apiUrl);
                        // $get_response = json_decode($response, true);

                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://wam.umbrella.co.id/api/user/v1/send',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => array(
                                    'mobile' => '6281225518118',
                                    'text' => 'Isi Pesan 33',
                                    'key' => 'eyJ1aWQiOiJzUTB5WWJjcGJPdGM5NTJkVzcyem41RTZ6eEdGT1RhWiIsImNsaWVudF9pZCI6IjYyODk4OTkwMDE0OCJ9'
                                ),
                                CURLOPT_HTTPHEADER => array(
                                    'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiJzUTB5WWJjcGJPdGM5NTJkVzcyem41RTZ6eEdGT1RhWiIsInJvbGUiOiJ1c2VyIiwiaWF0IjoxNzA5NzI0MjI5fQ.zI2zYduQJ_JGl8xKKdm94_m9wPDijH0oT2YdsNacSt0'
                                ),
                            ));
                            $response = curl_exec($curl); curl_close($curl);
                        $get_response = json_decode($response, true);                                               
                        var_dump($get_response);die;
                        if ($get_response) {                       
                            $get_response['status'] = ($get_response['success'] == true) ? 1 : 0;
                            $get_response['message'] = ($get_response['status'] == 1) ? 'Success' : 'Failed';
                            $get_response['result'] = [];
                        } else {
                            $get_response['result'] = [];                            
                            $get_response['message'] = 'Not Connected';
                        }                         
                    }
                }
                /* Result CURL / API */
                $get_response = json_decode($response,true);
                $return->result  = $get_response['result']; // Result
                $return->status  = $get_response['status']; // 1 / 0
                $return->message = $get_response['message']; // Berhasil / Gagal                
            }else{
                $return->message='Penerima tidak ada';
            }
        }else{
          $return->message='Params doest exist';
        }
        $return->params = $params;
        // return json_encode($get_response);
        echo json_encode($return);
    }
    function whatsapp_send_id($message_id = 0){
        $return             = new \stdClass();
        $return->status     = 0;
        $return->message    = 'Failed';
        $return->result     = '';

        $message_id         = intval($message_id);

        if(!empty($message_id) && strlen($message_id) > 0){
            $get_message = $this->Message_model->get_message($message_id);
            $params = array(
                'header' => '',
                'file' => '',
                'content' => $get_message['message_text'],
                'recipient' => array(
                    array(
                        'number'=> $this->contact_number($get_message['message_contact_number']),
                        'name' => $get_message['message_contact_name'],
                        'message_id' => $get_message['message_id']                  
                    )
                ),
                'footer' => ''
            );
            // var_dump($params);die;
            $send = $this->whatsapp_send($params);
            $return->status     = $send->status;
            $return->message    = $send->message;  
            $return->result     = $send->result; 
            $return->params     = $send->params;                         
        }else{
            $return->message='ID Not ready';
        }
        return json_encode($return);
    }        
    function whatsapp_send_group($message_group_session){
        $return             = new \stdClass();
        $return->status     = 0;
        $return->message    = '';
        $return->result     = '';
        // var_dump($message_group_session);die;
        if(strlen($message_group_session) > 0){
        
            $datas = array();
            $where = array(
                'message_group_session' => $message_group_session
            );
            $get_data=$this->Message_model->get_message_custom_result($where);
            if(count($get_data) > 0){
                $recipient = [];
                foreach($get_data as $v){
                    $recipient[] = array('message_id' => $v['message_id'], 'number'=> $this->contact_number($v['message_contact_number']),'name' => $v['message_contact_name']);                                 
                }   
                if($recipient > 0){
                    $params = array(
                        'header' => '',
                        'file' => '',
                        'content' => $v['message_text'],
                        'recipient' => $recipient,
                        'footer' => ''
                    );
                    $send = $this->whatsapp_send($params);
                    $return->status     = $send->status;
                    $return->message    = $send->message;  
                    $return->result     = $send->result;                     
                } 
            }else{ 
                $return->message='Session not found';                
            }
        }else{
            $return->message='Session not found';
        }
        return json_encode($return);
    }    
    function whatsapp_send_flag_0(){
        $return             = new \stdClass();
        $return->status     = 0;
        $return->message    = '';
        $return->result     = '';
        $where = array(
            'message_platform' => 1,
            'message_flag' => 0
        );
        $get_data=$this->Message_model->get_all_message($where,null,15,0,'message_id','asc');      
        // var_dump($get_data);die;
        if(count($get_data) > 0){
            foreach($get_data as $v){
                $recipient[] = array(
                    'number'=> $this->contact_number($v['message_contact_number']),
                    'name' => $v['message_contact_name'],
                    'message_id' => $v['message_id'], 
                    'message_device_number' => $v['device_number'],
                    'message_device_client' => null,    
                    'message_device_token' => $v['device_token']                     
                );
            }
            if($recipient > 0){
                $params = array(
                    'header' => '',
                    'file' => '',
                    'content' => $v['message_text'],
                    'recipient' => $recipient,
                    'footer' => ''
                );
                $send = $this->whatsapp_send($params);
                $return->status     = $send->status;
                $return->message    = $send->message;  
                $return->result     = $send->result;                                
            }               
        }else{ 
            $return->message='No Message enqueue';                
        }
        echo json_encode($return);
    }

    function whatsapp_template($action, $send_now_or_later, $params){
        /*
            $send_now_or_later ? 1 = Langsung dikirim , 0 = Server yg Mengirim
        */
        $next               = true;
        $set_user_id        = null;
        $set_user_username  = null;
        $set_user_phone     = null;        
        
        switch($action){
            case "register-and-activation-code": die; //From minio
                $get_user = $this->User_model->get_user($user_id);

                $text     = '🔐 *Activated Account*'."\r\n\r\n";
                $text    .= 'Hi, Welcome to platform *'.$this->app_name.'*'."\r\n";
                $text    .= 'Your number has been register on our platform, Please enter the code below on your screen'."\r\n\r\n";
                // $text .= "\r\n".'Silahkan klik link dibawah ini untuk konfirmasi pendaftaran anda di platform ini.'."\r\n\r\n";
                // $text .= $this->app_url.'register/activation/'.$get_user['user_session'].'/'.$get_user['user_activation_whatsapp_code']."\r\n\r\n";
                $text    .= "Activation Code:"."\r\n";
                $text    .= "*".$get_user['user_activation_whatsapp_code']."*"."\r\n\r\n";
                // $text    .= "Access From:"."\r\n";
                // $text    .= $this->user_agent()."\r\n\r\n";
                $text    .= "📌 Please ignore this message if you don't feel registered";
                // $text = rawurlencode($text);
                break;
            case "reset-password-and-activation-code": die; //From minio
                $get_user = $this->User_model->get_user($user_id);

                $text     = '🔓 *Reset Password*'."\r\n\r\n";
                // $text    .= 'Hi, Welcome to platform *'.$this->app_name.'*'."\r\n";
                $text    .= 'Hi, Did you ask for a password change on *'.$this->app_name.'*, Please enter the code below on your screen'."\r\n\r\n";
                // $text .= "\r\n".'Silahkan klik link dibawah ini untuk konfirmasi pendaftaran anda di platform ini.'."\r\n\r\n";
                // $text .= $this->app_url.'register/activation/'.$get_user['user_session'].'/'.$get_user['user_activation_whatsapp_code']."\r\n\r\n";
                $text    .= "Reset Code:"."\r\n";
                $text    .= "*".$get_user['user_activation_whatsapp_code']."*"."\r\n";
                // $text    .= "Access From:"."\r\n";
                // $text    .= $this->user_agent()."\r\n";                
                $text    .= "\r\n"."📌 Please ignore this message if you don't feel lost your password";
                // $text = rawurlencode($text);                
                break;                
            case "lost-password": die;
                $get_user = $this->User_model->get_user($user_id);

                $text  = '🔓 *Permintaan Perubahan Password*'."\r\n\r\n";            
                $text .= 'Anda baru saja meminta reset password di platform *'.$this->app_name.'*'."\r\n";
                // $text .= 'Nomor anda baru saja di daftarkan pada platform kami'."\r\n";            
                $text .= "\r\n".'Silahkan klik link dibawah ini untuk mereset password anda di platform ini.'."\r\n";
                $text .= $this->app_url.'password/recovery/'.$get_user['user_activation_code'].$get_user['user_session']."\r\n\r\n";
                // $text .= "Kode OTP:"."\r\n";
                // $text .= "*".$get_user['user_activation_code']."*"."\r\n\r\n";
                $text .= "📌 Abaikan jika bukan anda, seseorang mungkin mencoba masuk menggunakan akun anda."."\r\n";
                $text .= "Mohon segera ganti password anda secara berkala";                
                break;
            case "login-activity": die; //From minio
                $get_user = $this->User_model->get_user($user_id);

                $text     = '✅ *Verified Login*'."\r\n\r\n";
                // $text    .= 'Hi, Welcome to platform *'.$this->app_name.'*'."\r\n";
                $text    .= 'You have been logged in *'.$this->app_name.'*'."\r\n\r\n";
                // $text .= "\r\n".'Silahkan klik link dibawah ini untuk konfirmasi pendaftaran anda di platform ini.'."\r\n\r\n";
                // $text .= $this->app_url.'register/activation/'.$get_user['user_session'].'/'.$get_user['user_activation_whatsapp_code']."\r\n\r\n";
                // $text    .= "Reset Code:"."\r\n";
                // $text    .= "*".$get_user['user_activation_whatsapp_code']."*"."\r\n\r\n";
                $text    .= "Access From:"."\r\n";
                $text    .= $this->user_agent()."\r\n";                
                // $text    .= "\r\n"."📌 Please ignore this message if you don't feel lost your password";
                // $text = rawurlencode($text);                
                break;         
            case "sales-sell-invoice":
                $a = '';
                $b = '';
                $c = '';

                //Get Trans
                $trans = $this->Transaksi_model->get_transaksi($params['trans_id']);
                if($trans['trans_id'] > 0){
                    $branch = $this->Branch_model->get_branch($trans['trans_branch_id']);
                    $where = array(
                        'trans_item_trans_id' => $trans['trans_id'],
                    );
                    $trans_item = $this->Transaksi_model->get_transaksi_item_custom($where);
                    foreach($trans_item as $v):
                        $b .= number_format($v['trans_item_out_qty'],0).' '.$v['trans_item_unit'];
                        $b .=' '.$v['product_name'].' @'.number_format($v['trans_item_sell_price'],0)."\r\n";
                    endforeach;
                    
                    $a .= '📄 *Invoice '.$branch['branch_name']."*"."\r\n\r\n";
                    $a .= 'Halo Bpk/Ibu *'.$trans['trans_contact_name'].'*, berikut Invoice yang kami kirimkan'."\r\n\r\n";
                    $a .= '*- Invoice:*'."\r\n";
                    $a .= $trans['trans_number']."\r\n";
                    $a .= date("d-F-Y, H:i", strtotime($trans['trans_date']))."\r\n"."\r\n";

                    $c .= $a;

                    //Deskripsi
                    $c .= '*- Deskripsi:*'."\r\n";
                    if(count($trans_item) > 0){
                        $c .= $b."\r\n";
                    }else{
                        $c .= '-'."\r\n";
                    }

                    $c .= "*- Total*"."\r\n";
                    // $c .= "Subtotal : Rp. ".number_format($trans['trans_total_dpp'],0)."\r\n";
                    // $c .= "Diskon : Rp. ".number_format($trans['trans_discount'],0)."\r\n";
                    // $c .= "Grand Total : *Rp. ".number_format($trans['trans_total'],0)."*"."\r\n"."\r\n";
                    
                    $c .= "Subtotal : Rp. ".number_format($trans['trans_total_dpp'],0)."\r\n";
                    if(!empty($trans['trans_voucher']) && $trans['trans_voucher'] > 0){
                        $c .= "Voucher : - Rp. ".number_format($trans['trans_voucher'],0)."\r\n";
                    }
                    if(!empty($trans['trans_discount']) && $trans['trans_discount'] > 0){
                        $c .= "Diskon : - Rp. ".number_format($trans['trans_discount'],0)."\r\n";                        
                    }
                    if((!empty($trans['trans_voucher'])) or (!empty($trans['trans_discount']))){
                        $c .= "Grand Total : Rp. ".number_format($trans['trans_total'],0)."\r\n";   
                    }   
                    // if(!empty($trans['trans_received']) && $trans['trans_received'] > 0){
                    //     $c .= "Dibayar : Rp. ".number_format($trans['trans_received'],0)."\r\n";                        
                    // }      
                    // if(!empty($trans['trans_change']) && $trans['trans_change'] > 0){
                    //     $c .= "Kembali : Rp. ".number_format($trans['trans_change'],0)."\r\n";
                    // }          
                    $c .= "\r\n";        

                    //Pembayaran
                    $c .= '*- Status Pembayaran:*'."\r\n";
                    if($trans['trans_paid'] == 1){
                        $c .= 'LUNAS'."\r\n";
                        // if($trans['trans_paid_type']==1){ //Tunai/Cash
                        //     $c .= 'Cash'."\r\n";
                        // }else if($trans['trans_paid_type']==2){ //Transfer
                        //     $c .= 'Transfer'."\r\n";
                        // }else if($trans['trans_paid_type']==3){ //EDC
                        //     $c .= 'EDC Card'."\r\n";
                        // }else if($trans['trans_paid_type']==4){ //Gratis
                        //     $c .= 'GRATIS'."\r\n";
                        // }else if($trans['trans_paid_type']==5){ //QRIS
                        //     $c .= 'QRIS'."\r\n";
                        // }
                        if($trans['trans_paid_type'] > 0){
                            $get_type_paid = $this->Type_model->get_type_paid($trans['trans_paid_type']);
                            $c .= $get_type_paid['paid_name']."\r\n";
                        }
                        // else{
                            // $c .= 'Belum Terbayar'."\r\n"; // Piutang
                        // }                        
                    }else{
                        $c .= 'Belum Terbayar'."\r\n";
                    }
                    
                    //Keterangan
                    $c .= "\r\n"."*- Keterangan:*"."\r\n";
                    if(!empty($trans['trans_note'])){
                        $c .= $trans['trans_note'].""."\r\n"."\r\n";   
                    }else{
                        $c .= '-'."\r\n";
                    }

                    // $set_message .= 'Anda juga dapat mendownload invoice ini melalui link dibawah ini'."\r\n";
                    // $set_message .= 'https://minio.id/AuSDil'."\r\n\r\n";
                    $c .= "\r\n".'_Terimakasih atas kepercayaan anda_'."\r\n";
                    $text = $c;

                    $set_user_id = !empty($trans['trans_contact_id']) ? $trans['trans_contact_id'] : '';
                    $set_user_name = !empty($trans['trans_contact_name']) ? $trans['trans_contact_name'] : '';
                    $set_user_phone = !empty($trans['trans_contact_phone']) ? $trans['trans_contact_phone'] : '';                    
                }else{
                    $next = false;
                }
                break;   
            case "sales-sell-invoice-trans-order":
                $a = '';
                $b = '';
                $c = '';

                //Get Trans
                $trans = $this->Transaksi_model->get_transaksi($params['trans_id']);
                if($trans['trans_id'] > 0){
                    $branch = $this->Branch_model->get_branch($trans['trans_branch_id']);
                    $where = array(
                        'trans_item_trans_id' => $trans['trans_id'],
                    );
                    $get_trans_items = $this->Transaksi_model->get_transaksi_item_custom($where);
                    // var_dump($get_trans_items);die;
                    $get_order = $this->Order_model->get_all_orders(array('order_trans_id'=> $trans['trans_id']),$search = null,$limit = null,$start = null,$order = null,$dir = null);
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
                                'order_items' => $get_order_item
                            );
                        }
                    }

                    //Content Order Items
                    foreach($order_data as $i => $v):
                        //$text .= $v['order_number']."\n";
                        $b .= $v['order_number'].', '.$v['order_date']."\n";                          
                        $b .= $v['ref_name']."\n";
                        // $b .= $v['employee_name']."\n";       
                        $b .= "\n";                      
                        foreach($v['order_items'] as $i):
                            $b .= '*'.$i['product_name']."*";
                            // $b .= '_@'.number_format($i['order_item_price'],0,'',',') . ' x '. number_format($i['order_item_qty'],0,'',',').','. number_format($i['order_item_total'],0,'',',')."_"."\n";
                            $b .= ' *@'.number_format($i['order_item_total'],0,'',',')."*"."\n";                            
                        endforeach;            
                        $b .= "\n"; 
                    endforeach;
                                    
                    //Content Trans Items
                    // foreach($get_trans_items as $v):
                    //     $b .= '~'.$v['product_name'].'~'."\n";
                    //     $b .= '_@'.number_format($v['trans_item_out_qty'],0,'',',') . ' x '. number_format($v['trans_item_sell_price'],0,'',',').', '.number_format($v['trans_item_sell_total'],0,'',',').'_';            
                    // endforeach;    
                    
                    $a .= '📄 *Invoice '.$branch['branch_name']."*"."\r\n\r\n";
                    $a .= 'Halo Bpk/Ibu *'.$trans['trans_contact_name'].'*, berikut Invoice yang kami kirimkan'."\r\n\r\n";
                    $a .= '*#Invoice:*'."\r\n";
                    $a .= $trans['trans_number'].", ".date("d-m-Y, H:i", strtotime($trans['trans_date']))."\r\n"."\r\n";

                    $c .= $a;

                    //Deskripsi
                    $c .= '*#Item:*'."\r\n";
                    // if(count($get_trans_items) > 0){
                        $c .= $b;
                    // }else{
                        // $c .= '-'."\r\n";
                    // }

                    // $c .= "\r\n";
                    $c .= "*#Total*"."\r\n";
                    $c .= "Subtotal : Rp. ".number_format($trans['trans_total_dpp'],0)."\r\n";
                    if(!empty($trans['trans_voucher']) && $trans['trans_voucher'] > 0){
                        $c .= "Voucher : - Rp. ".number_format($trans['trans_voucher'],0)."\r\n";
                    }
                    if(!empty($trans['trans_discount']) && $trans['trans_discount'] > 0){
                        $c .= "Diskon : - Rp. ".number_format($trans['trans_discount'],0)."\r\n";                        
                    }
                    if((!empty($trans['trans_voucher'])) or (!empty($trans['trans_discount']))){
                        $c .= "Grand Total : Rp. ".number_format($trans['trans_total'],0)."\r\n";   
                    }   
                    if(!empty($trans['trans_received']) && $trans['trans_received'] > 0){
                        $c .= "Dibayar : Rp. ".number_format($trans['trans_received'],0)."\r\n";                        
                    }      
                    if(!empty($trans['trans_change']) && $trans['trans_change'] > 0){
                        $c .= "Kembali : Rp. ".number_format($trans['trans_change'],0)."\r\n";
                    }          
                    $c .= "\r\n";                                                              
                    // $c .= "Grand Total : *Rp. ".number_format($trans['trans_total'],0)."*"."\r\n"."\r\n";
                    
                    //Pembayaran
                    $c .= '*#Status Pembayaran:*'."\r\n";
                    if($trans['trans_paid'] == 1){
                        $c .= 'LUNAS'."\r\n";
                        // if($trans['trans_paid_type']==1){ //Tunai/Cash
                        //     $c .= 'Cash'."\r\n";
                        // }else if($trans['trans_paid_type']==2){ //Transfer
                        //     $c .= 'Transfer'."\r\n";
                        // }else if($trans['trans_paid_type']==3){ //EDC
                        //     $c .= 'EDC Card'."\r\n";
                        // }else if($trans['trans_paid_type']==4){ //Gratis
                        //     $c .= 'GRATIS'."\r\n";
                        // }else if($trans['trans_paid_type']==5){ //QRIS
                        //     $c .= 'QRIS'."\r\n";
                        // }
                        if($trans['trans_paid_type'] > 0){
                            $get_type_paid = $this->Type_model->get_type_paid($trans['trans_paid_type']);
                            $c .= $get_type_paid['paid_name']."\r\n";
                        }else{
                            $c .= 'Belum Terbayar'; // Piutang
                        }                         
                    }else{
                        $c .= 'Belum Terbayar'."\r\n";
                    }
                    
                    //Keterangan
                    $c .= "\r\n"."*#Keterangan:*"."\r\n";
                    if(!empty($trans['trans_note'])){
                        $c .= $trans['trans_note'].""."\r\n"."\r\n";   
                    }else{
                        $c .= '-'."\r\n";
                    }

                    // $set_message .= 'Anda juga dapat mendownload invoice ini melalui link dibawah ini'."\r\n";
                    // $set_message .= 'https://minio.id/AuSDil'."\r\n\r\n";
                    $c .= "\r\n".'_Terimakasih atas kepercayaan anda_'."\r\n";
                    $text = $c;

                    $set_user_id = !empty($trans['trans_contact_id']) ? $trans['trans_contact_id'] : '';
                    $set_user_name = !empty($trans['trans_contact_name']) ? $trans['trans_contact_name'] : '';
                    $set_user_phone = !empty($trans['trans_contact_phone']) ? $trans['trans_contact_phone'] : '';                    
                }else{
                    $next = false;
                }
                break;       
            case "sales-sell-invoice-booking":
                $a = '';
                $b = '';
                $c = '';
                // var_dump($params);die;
                if($params['order_id'] > 0){
                    $god  = $this->Front_model->get_booking($params['order_id']);
                    // $get_branch = $this->Branch_model->get_branch($trans['order_branch_id']);                    
                    $get_trans = $this->Front_model->get_booking_item_custom(array('order_item_order_id'=> $params['order_id']),$search = null,$limit = null,$start = null,$order = null,$dir = null);
                    $text = '';
                    $text .= "*Booking*"."\r\n";
                    $text .= $get_trans['order_number']."\r\n";
                    $text .= date("d/m/Y - H:i", strtotime($get_trans['order_date']))."\r\n\r\n";    
            

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
                    $word_wrap_width = 20;

                // $text .= $get_trans['order_number']."\r\n";
                $text .= "Checkin: ".$date_check." - ".$hour_check."\r\n";        
                $text .= "Kamar: ".$get_trans['ref_name']." - ".$get_trans['product_name']."\r\n";    
                $text .= "Tipe: ".$sort_name."\r\n";        
                $text .= "Kontak: ".$get_trans['order_contact_name']."\r\n\r\n";        

                // $text.= dot_set_wrap_2('Kontak', $this->stringToSecret($get_trans['order_contact_name']));
                // $text.= dot_set_line('-',$word_wrap_width);
                // $text.= dot_set_wrap_0("Check-In",' ','BOTH');
                // $text.= dot_set_wrap_0($date_check,' ','BOTH');
                // $text.= dot_set_wrap_0($hour_check,' ','BOTH');            
                // $text.= dot_set_line('-',$word_wrap_width);            
                // $text.= dot_set_wrap_2('Kontak', $get_trans['order_contact_name']);
                // $text.= dot_set_wrap_2('Tipe',$sort_name);
                // $text.= dot_set_wrap_2('Kamar','['.$get_trans['ref_name'].']');
                // $text.= dot_set_wrap_2(' ',$get_trans['product_name']);
                if(!empty($get_trans['order_vehicle_cost'])){            
                    $text .= "Jml Kndraan: ".$get_trans['order_vehicle_count']."\r\n";        
                }
                if(!empty($get_trans['order_vehicle_plate_number'])){
                    $text .= "Plat Kndraan: ".$get_trans['order_vehicle_plate_number']."\r\n";                  
                }            

                if(!empty($get_trans['order_vehicle_cost']) && $get_trans['order_vehicle_cost'] > 0){
                    $text .= "Biaya Parkir: ".number_format($get_trans['order_vehicle_cost'])."\r\n";                                  
                    // $text .= dot_set_wrap_3('Biaya Parkir',':',''.number_format($get_trans['order_vehicle_cost'],0,'',','));    
                }                        
                if(!empty($get_trans['order_total']) && $get_trans['order_total'] > 0){
                    $text .= "Kamar: ".number_format($get_trans['order_total'])."\r\n";                                                  
                    // $text .= dot_set_wrap_3('Kamar',':',''.number_format($get_trans['order_total'],0,'',','));    
                }
                // $text .= dot_set_line('-',$word_wrap_width);            
                if(!empty($get_trans['order_total_paid']) && $get_trans['order_total_paid'] > 0){
                    $text .= "Dibayar: ".number_format($get_trans['order_total_paid'])."\r\n";                                                                  
                    // $text .= dot_set_wrap_3('Dibayar',':',''.number_format($get_trans['order_total_paid'],0,'',','));    
                }    

                if($get_trans['order_paid'] == 1){
                    $lunas = 'Lunas';                
                }else{
                    $lunas = 'Belum Lunas';
                }
                // $text .= dot_set_wrap_3('Status',':',$lunas);
                $text .= $lunas."\r\n";                                                                  

                // //Footer
                // // $text .= "\n";
                // // $text .= dot_set_wrap_0("-- Terima Kasih --",' ','BOTH');    
                // // $text .= dot_set_wrap_0("Gratis jika tidak menerima struk",' ','BOTH');                 
            
                //         $text = $a.$b.$c;
                    $set_user_id = null;
                    $set_user_name = $params['contact_name'];
                    $set_user_phone = $params['contact_phone'];
                    $trans['trans_branch_id'] = $get_trans['order_item_branch_id'];                 
                }else{
                    $next = false;
                }
                break;       
            case "sales-sell-invoice-rebooking":
                $a = '';
                $b = '';
                $c = '';
                // var_dump($params);die;
                if($params['order_id'] > 0){
                    $god  = $this->Front_model->get_booking($params['order_id']);
                    // $get_branch = $this->Branch_model->get_branch($trans['order_branch_id']);                    
                    $get_trans = $this->Front_model->get_booking_item_custom(array('order_item_order_id'=> $params['order_id']),$search = null,$limit = null,$start = null,$order = null,$dir = null);
                    $text = '';
                    $text .= "*Pengingat ReBooking*"."\r\n";
                    // $text .= $get_trans['order_number']."\r\n";
                    // $text .= date("d/m/Y - H:i", strtotime($get_trans['order_date']))."\r\n\r\n";    
                    $text .= "Permisi Bpk/Ibu ".$params['contact_name']."\r\n";
                    $text .= $params['contact_phone']."\r\n\r\n";                    
                    $text .= "Apakah anda ingin memperpanjang masa sewa yang akan berakhir dalam *".$get_trans['order_item_expired_day_2']." hari lagi*, hubungi kami untuk pembayaran perpanjangan"."\r\n\r\n";

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
                    $word_wrap_width = 20;

                    // $text .= $get_trans['order_number']."\r\n";
                    $text .= "Checkin: ".$date_check." - ".$hour_check."\r\n";        
                    $text .= "Kamar: ".$get_trans['ref_name']." - ".$get_trans['product_name']."\r\n";    
                    $text .= "Tipe: ".$sort_name."\r\n";        
                    // $text .= "Kontak: ".$get_trans['order_contact_name']."\r\n\r\n";        
                    $text .= "\r\n"."Mohon mengiriman bukti transer, abaikan jika sudah lunas"."\r\n"."Terimakasih"."\r\n";
                    // if(!empty($get_trans['order_vehicle_cost'])){            
                    //     $text .= "Jml Kndraan: ".$get_trans['order_vehicle_count']."\r\n";        
                    // }
                    // if(!empty($get_trans['order_vehicle_plate_number'])){
                    //     $text .= "Plat Kndraan: ".$get_trans['order_vehicle_plate_number']."\r\n";                  
                    // }            

                    // if(!empty($get_trans['order_vehicle_cost']) && $get_trans['order_vehicle_cost'] > 0){
                    //     $text .= "Biaya Parkir: ".number_format($get_trans['order_vehicle_cost'])."\r\n";                                  
                    //     // $text .= dot_set_wrap_3('Biaya Parkir',':',''.number_format($get_trans['order_vehicle_cost'],0,'',','));    
                    // }                        
                    // if(!empty($get_trans['order_total']) && $get_trans['order_total'] > 0){
                    //     $text .= "Kamar: ".number_format($get_trans['order_total'])."\r\n";                                                  
                    //     // $text .= dot_set_wrap_3('Kamar',':',''.number_format($get_trans['order_total'],0,'',','));    
                    // }
                    // // $text .= dot_set_line('-',$word_wrap_width);            
                    // if(!empty($get_trans['order_total_paid']) && $get_trans['order_total_paid'] > 0){
                    //     $text .= "Dibayar: ".number_format($get_trans['order_total_paid'])."\r\n";                                                                  
                    //     // $text .= dot_set_wrap_3('Dibayar',':',''.number_format($get_trans['order_total_paid'],0,'',','));    
                    // }    

                    // if($get_trans['order_paid'] == 1){
                    //     $lunas = 'Lunas';                
                    // }else{
                    //     $lunas = 'Belum Lunas';
                    // }
                    // $text .= $lunas."\r\n";            

                    // $set_user_id = null;
                    $set_user_name = $params['contact_name'];
                    $set_user_phone = $params['contact_phone'];
                    // var_dump($set_user_phone);die;
                    $trans['trans_branch_id'] = $get_trans['order_item_branch_id'];                 
                }else{
                    $next = false;
                }
                break;       
            case "inventory-goods-out":
                $a = '';
                $b = '';
                $c = '';

                //Get Trans
                $trans = $this->Transaksi_model->get_transaksi($params['trans_id']);
                if($trans['trans_id'] > 0){
                    $branch = $this->Branch_model->get_branch($trans['trans_branch_id']);
                    $where = array(
                        'trans_item_trans_id' => $trans['trans_id'],
                    );
                    $trans_item = $this->Transaksi_model->get_transaksi_item_custom($where);
                    $b .= '*- Produk*'."\r\n";
                    foreach($trans_item as $v):
                        $b .=number_format($v['trans_item_out_qty'],0).' '.$v['trans_item_unit'].' '.$v['product_name']."\r\n";
                    endforeach;
                    
                    $a .= '📄 *Stock Request '.$branch['branch_name']."*"."\r\n\r\n";
                    // $a .= 'Halo Bpk/Ibu *'.$trans['trans_contact_name'].'*, berikut permintaan kami'."\r\n\r\n";
                    $a .= '*- Pemakaian Produk Cabang:*'."\r\n";
                    $a .= $trans['trans_number']."\r\n";
                    $a .= date("d-F-Y, H:i", strtotime($trans['trans_date']))."\r\n"."\r\n";
                    $a .= '*- Cabang Peminta:*'."\r\n";
                    $a .= $trans['branch_2_name']."\r\n";
                    $a .= $trans['user_username']."\r\n\r\n";

                    $c .= $a;
                    $c .= $b;

                    //Keterangan
                    $c .= "\r\n"."*- Keterangan:*"."\r\n";
                    if(!empty($trans['trans_note'])){
                        $c .= $trans['trans_note'].""."\r\n"."\r\n";   
                    }else{
                        $c .= '-'."\r\n";
                    }
                    $text = $c;

                    $set_user_id = !empty($trans['trans_contact_id']) ? $trans['trans_contact_id'] : '';
                    $set_user_name = !empty($trans['trans_contact_name']) ? $trans['trans_contact_name'] : '';
                    $set_user_phone = !empty($trans['trans_contact_phone']) ? $trans['trans_contact_phone'] : '';                    
                }else{
                    $next = false;
                }
                break;                                   
            default:
                break;
        }
        if($next){
            $session_group      = $this->random_code(20);
            $session_message    = $this->random_code(20);

            $whatsapp_watermark    = $this->config->item('whatsapp_watermark'); 
            if(strlen($whatsapp_watermark) > 1){
                $text .= "\r\n".$whatsapp_watermark;
            }   

            $params = array(
                'message_contact_id' => $set_user_id,
                'message_contact_name' => $set_user_name,
                'message_contact_number' => $set_user_phone,
                'message_text' => $text,
                'message_session' => $session_message,
                'message_group_session' => $session_group,
                // 'message_news_id' => $message_news,
                // 'message_url' => $get_news_url,
                'message_flag' => 0, 
                'message_date_created' => date("YmdHis"),
                'message_branch_id' => $trans['trans_branch_id'],
                'message_device_id' => !empty($params['device_id']) ? $params['device_id'] : null 
            );  

            $set_data=$this->Message_model->add_message($params);
            $message_id = $set_data;

            if(intval($message_id) > 0){
                $datas = $this->Message_model->get_message($message_id);
                if($datas==true){
                    if($send_now_or_later == 1){
                        return $this->whatsapp_send_group($session_group);
                    }else{
                        $r = array(
                            'status' => 1,
                            'message' => 'Sukses, Pesan akan di kirimkan melalui server sesuai jadwal',
                            'result' => []
                        );
                        return json_encode($r);
                    }
                }
            }
        }
    }
    function whatsapp_api(){
        /*  
            POST Example

            text : 'Isi pesan',
            contact_number : '6281225518118',
            url: 'www.google.com'
        */
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';

        $post = $this->input->post();
        $get  = $this->input->get();

        $this->form_validation->set_rules('text', 'Teks', 'required');
        // $this->form_validation->set_rules('contact_name', 'Nama Tertuju', 'required');
        $this->form_validation->set_rules('contact_number', 'Nomor Tujuan', 'required');
        // $this->form_validation->set_rules('message_api_id', 'MESSAGE_API_ID', 'required');
        // $this->form_validation->set_rules('message_api_flag', 'MESSAGE_API_FLAG', 'required');
        // $this->form_validation->set_rules('message_device_id', 'MESSAGE_DEVICE_ID', 'required');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        if ($this->form_validation->run() == FALSE){
            $return->message = validation_errors();
        }else{
            
            $session_group = $this->create_session(20);
            $session_message = $this->create_session(20);

            $message_text = !empty($post['text']) ? $post['text'] : null;
            $message_url = !empty($post['url']) ? $post['url'] : null;

            $contact_number = !empty($post['contact_number']) ? $post['contact_number'] : null;
            $contact_name = !empty($post['contact_name']) ? $post['contact_name'] : null;

            // Do Save/Create Row
            $params = array(
                'message_type' => 1,
                // 'message_news_id' => !empty($post['message_news_id']) ? intval($post['message_news_id']) : null,
                // 'message_platform' => !empty($post['message_platform']) ? intval($post['message_platform']) : null,
                // 'message_category_id' => !empty($post['message_category_id']) ? $post['message_device_id'] : null,
                'message_session' => $session_message,
                'message_group_session' => $session_group,
                'message_date_created' => date('YmdHis'),
                'message_flag' => 0,
                'message_text' => $message_text,
                'message_url' => $message_url,
                'message_contact_name' => $contact_name,
                'message_contact_number' => $contact_number,
                // 'message_api_id' => !empty($post['message_api_id']) ? $post['message_device_id'] : null,
                // 'message_api_flag' => !empty($post['message_api_flag']) ? $post['message_device_id'] : null,
                // 'message_device_id' => !empty($post['message_device_id']) ? intval($post['message_device_id']) : null,
            );
            // var_dump($params);die;
            $set_data = $this->Message_model->add_message($params);
            $message_id = $set_data;
            if(intval($message_id) > 0){
                $this->whatsapp_send_group($session_group);
                die;
            }else{
                $return->message='Gagal menambahkan pesan';
            }
        }
    }
    function whatsapp_prepare_rebooking(){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';

        $params = array(
            'order_item_type' => 222,
            'order_item_expired_day' => 1,
            'order_item_flag_checkin' => 1
        );
        $search = null; $limit=null; $start=null;$order=null;$dir=null;
        $get_count = $this->Front_model->get_all_booking_item_count($params, $search);
        if($get_count > 0){
            $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
            foreach($get_data as $v){
                $contact_params = array(
                    'order_id' => $v['order_id'],
                    'contact_name' => $v['order_contact_name'],
                    'contact_phone' => $v['order_contact_phone'],
                );
                // 'contact_phone' => '6281225518118'
                $this->whatsapp_template('sales-sell-invoice-rebooking',0,$contact_params);
            }
            $return->status = 1;
            $return->params = $contact_params;
            $return->message = 'Found '.count($get_data).' datas';
        }else{
            $return->message = 'No Rebooking Reminder Data';
        }  
        echo json_encode($return);
    }

    //Sending Gateway Email
    function email_template($action, $params){
        // die;
    }   
    function email_send_message($params){ //Works 
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';

        // Default Value before Changes
        $to_address = 'joceline.putra@gmail.com';
        $to_subject = 'Email Subject';
        $to_content = 'Content Email';

        $datas = array();
        $get_data=$this->Message_model->get_all_message($params,null,null,null,'message_id','asc');
        if(count($get_data) > 0){
            foreach($get_data as $v){

                $datas[] = array(
                    'message_group_session' => $v['message_group_session'],
                    'message_id' => $v['message_id'],
                    'message_session' => $v['message_session'],
                    'message_text' => $v['message_text'],
                    'message_contact_name' => $v['message_contact_name'],
                    'message_contact_email' => $v['message_contact_email'],
                    // 'message_url' => $v['message_url'],
                    'message_device_id' => $v['message_device_id']
                );
                // var_dump($v['message_text']);die;
                $prepare_text = "
                <div style='padding:25px;background-color:#f2f2f2;'>
                    <div style='padding:10px;background-color:white;'>
                        <div>
                            <p>
                                <img src='https://app.aspri.cloud/upload/branch/default_logo.png' style='margin:5px 0;width:190px;'>
                            </p>
                            <p>".nl2br($v['message_text'])."</p>
                        </div>
                    </div>
                </div>";    
                $to_content = $prepare_text;    
                $to_address = $v['message_contact_email'];

                $result = $this->phpmailer_lib->sendMailSMTP($to_address, $to_subject, $to_content);
                if(intval($result['status']) === 1){

                    $where = array(
                        'message_id' => $v['message_id']
                    );
                    $params = array(
                        'message_date_sent' => date('YmdHis'),
                        'message_flag' => 1
                    );
                    $this->Message_model->update_message_custom($where,$params);

                    $return->status  = 1;
                    $return->message = $result['message'];
                }else{
                    $return->message = $result['message'];
                }

                // Plan B
                /*
                $to_header = '';
                $to_header .= "From: ".$this->config->item('mail_set_from_alias')." <".$this->config->item('mail_set_from').">\r\n";
                $to_header .= "Reply-To:  <".$this->config->item('mail_set_reply_to').">\r\n"; 
                $to_header .= "MIME-Version: 1.0\r\n";
                $to_header .= "Content-type: text/html\r\n";
                // var_dump($to_header);die;
                $send_mail = mail($to_address,$to_subject,$to_content,$to_header);
                var_dump($send_mail);die;
                */
            }
        }else{ 
            $return->message='No Email enqueue';                
        }
        return json_encode($return);        
    }     
    function email_send_flag_0(){ //Only cronjob running this function
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';

        // Default Value before Changes
        $to_address = 'joceline.putra@gmail.com';
        $to_subject = 'Email Subject';
        $to_content = 'Content Email';

        $datas = array();
        $where = array(
            'message_platform' => 4,
            'message_flag' => 0
        );
        $get_data=$this->Message_model->get_all_message($where,null,5,0,'message_id','asc');      
        // var_dump($get_data);die; 
        if(count($get_data) > 0){
            foreach($get_data as $v){

                $datas[] = array(
                    'message_group_session' => $v['message_group_session'],
                    'message_id' => $v['message_id'],
                    'message_session' => $v['message_session'],
                    'message_text' => $v['message_text'],
                    'message_contact_name' => $v['message_contact_name'],
                    'message_contact_email' => $v['message_contact_email'],
                    // 'message_url' => $v['message_url'],
                    'message_device_id' => $v['message_device_id']
                );
                // var_dump($v['message_text']);die;
                $prepare_text = "
                <div style='padding:25px;background-color:#f2f2f2;'>
                    <div style='padding:10px;background-color:white;'>
                        <div>
                            <p>
                                <img src='https://app.aspri.cloud/upload/branch/default_logo.png' style='margin:5px 0;width:190px;'>
                            </p>
                            <p>".nl2br($v['message_text'])."</p>
                        </div>
                    </div>
                </div>";    
                $to_content = $prepare_text;    
                $to_address = $v['message_contact_email'];

                $result = $this->phpmailer_lib->sendMailSMTP($to_address, $to_subject, $to_content);
                if(intval($result['status']) === 1){
                    $where = array(
                        'message_id' => $v['message_id']
                    );
                    $params = array(
                        'message_date_sent' => date('YmdHis'),
                        'message_flag' => 1
                    );
                    $this->Message_model->update_message_custom($where,$params);                    
                    $return->status  = 1;
                    $return->message = $result['message'];
                }else{
                    $return->message = $result['message'];
                }

                // Plan B
                /*
                $to_header = '';
                $to_header .= "From: ".$this->config->item('mail_set_from_alias')." <".$this->config->item('mail_set_from').">\r\n";
                $to_header .= "Reply-To:  <".$this->config->item('mail_set_reply_to').">\r\n"; 
                $to_header .= "MIME-Version: 1.0\r\n";
                $to_header .= "Content-type: text/html\r\n";
                // var_dump($to_header);die;
                $send_mail = mail($to_address,$to_subject,$to_content,$to_header);
                var_dump($send_mail);die;
                */
            }
        }else{ 
            $return->message='No Email enqueue';                
        }
        // var_dump($mail->ErrorInfo);die;        
        // return json_encode($return);
        echo json_encode($return);        
    }

    //Other
    function create_session($length){
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ'.time();
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[mt_rand(0, $txtlen)];}
        return $result;
    }        
    function api(){ die; //Not Used
        
        $action=$this->input->get('action');
        $device_number=$this->input->get('device-number');
        $device_token=$this->input->get('device-token');
        $message_text =$this->input->get('message');
        $message_to_number = '';

        $from_post = array();
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';
        
        switch($action){
            case "create-device": //Pengecekan apakah number sudah terdaftar di table devices
                $params = array(
                    'device_number' => $device_number
                );
                $check_exists = $this->Device_model->check_data_exist($params);
                if($check_exists==false){
                    $return->message = 'Nomor belum terdaftar';
                    $return->result = array(
                        'generate_qrcode_token' => sha1('ABC')
                    );
                }else{
                    $get_data = $this->Device_model->get_device_custom($params);
                    $return->message='Nomor sudah terdaftar';
                    $return->result = array(
                        'device_id' => $get_data['device_id'],
                        'device_number' => $get_data['device_number'],
                        'device_token' => $get_data['device_token'],
                        'device_flag' => $get_data['device_flag']
                    );
                }
                break;
            case "read-device": //Membaca apakah device punya token
                $params = array(
                    'device_number' => $device_number
                );
                $check_exists = $this->Device_model->check_data_exist($params);
                if($check_exists==false){
                    $return->message = 'Nomor belum terdaftar';
                }else{
                    $get_data = $this->Device_model->get_device_custom($params);
                    $return->message='Nomor sudah terdaftar';
                    $return->result = array(
                        'device_id' => $get_data['device_id'],
                        'device_number' => $get_data['device_number'],
                        'device_token' => $get_data['device_token'],
                        'device_flag' => $get_data['device_flag']
                    );
                }           
                break;
            case "delete-device":
                break;
            case "restart-device":
                break;   
            case "send-message":
                $from_post['action'] = $action;
                $from_post['number'] = $device_number;
                $from_post['token'] = $device_token;
                $from_post['message_text'] = $message_text;
                $from_post['message_to_number'] = $message_to_number;

                $return->status=1;
                $return->message='Berhasil mengirim';
                $return->result = $from_post;
                break;
            default:
                $return->message='Default';
        }

        $return->from_post = $from_post;
        echo json_encode($return);
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
    function random_code($length){ # JEH3F2
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[rand(0, $txtlen)];}
        return $result;
    }      
    function user_agent(){
        if ($this->agent->is_browser()){
            $agent = $this->agent->browser(); 
            /* '.$this->agent->version(); */
        } elseif ($this->agent->is_robot()){
            $agent = $this->agent->robot();
        } elseif ($this->agent->is_mobile()){
            $agent = $this->agent->mobile();
        } else{
            $agent = 'Unidentified User Agent';
        }
        // return array('ip' => $this->input->ip_address(),'browser' => $agent,'os' => $this->agent->platform());
        return $agent.', '.$this->agent->platform();
    }
    function safe($var){
        $v = trim($var);
        $v = strip_tags($v);
        $v = htmlentities($v);
        $v = strtolower($v);
        $v = ucwords($v);
        return $v;
    }
    function search(){
        $terms      = $this->input->get("search");      
        $source     = $this->input->get("source"); //nama table
        
        $result = array();  
        if($source=="countries"){
            if(!empty($terms)){                            
                $query = $this->db->query("
                    SELECT country_id AS id, country_name AS nama,
                        (SELECT CONCAT(`country_name`,' +',`country_phone`)) AS `text`, country_phone
                    FROM countries
                    WHERE country_name LIKE '%".$terms."%' OR country_short LIKE '%".$terms."%' OR country_phone LIKE '%".$terms."%'
                    ORDER BY country_name ASC
                ");
            }else{
                $query = $this->db->query("
                SELECT country_id AS id, country_name AS nama,
                    (SELECT CONCAT(`country_name`,' +',`country_phone`)) AS `text`, country_phone
                FROM countries
                ORDER BY country_name ASC LIMIT 0, 25
                ");                    
            }
            $result = $query->result();
            $json = array_push($result,array(
                'id' => "0",
                'nama' => '-- Ketik yg ingin di cari --',
                'text' => '-- Ketik yg ingin di cari --'
            ));                
        }    
        echo json_encode($result);            
    }    

    function test(){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';
        
        $params = array(
            'header' => 'Judul',	
            'file' => 'https://www.planetware.com/wpimages/2020/02/france-in-pictures-beautiful-places-to-photograph-eiffel-tower.jpg',
            'content' => 'Isi Pesan',	
            'recipient' => array(
                array('number' => '6281225518118', 'name' => 'Joe'),                                                                                                                                                             
            ),
            'sender' => array(
                array('number' => '628989900149', 'auth' => '7Ho5mMjZMKELeLiqaZd5MK3NIjw6TM'),
                array('number' => '628979761512', 'auth' => 'uHr8UhBYZX2ob7BZUSMi3vyDENtCx8'),                                       
            ),            
            'footer' => '🖥️ Pesan ini dikirim oleh System'
        );

        // $params = array('checkup_id'=> 597,'device_id'=>1);
        // $params = array('checkup_id'=> 601);        
        // $do = $this->whatsapp_template('checkup-register',0,$params);          
        $do = $this->whatsapp_send_message($params);
        // $do = $this->whatsapp_send_group('ABC');
        // $do = $this->whatsapp_send_flag_0();
        $res = json_decode($do,true);
        $return->message = $res['message'];
        $return->status = $res['status'];
        $return->result = $res['result'];
        echo json_encode($return);
    }
    function callback(){
        // $return          = new \stdClass();
        // $return->status  = 0;
        // $return->message = '';
        // $return->result  = '';
    
        //$post   = json_decode(file_get_contents('php://input'));
        $post = $this->input->get();
        // var_dump($post);die;     

        $set_user_name      = $post['name'];
        $set_user_phone     = $post['phone'];
        $set_message        = $post['message'];                  
        $params = array(
            // 'message_contact_id' => $set_user_id,
            'message_contact_name' => $set_user_name,
            'message_contact_number' => $this->contact_number($set_user_phone),
            'message_text' => $set_message,
            'message_session' => $this->create_session(20),
            // 'message_group_session' => $session_group,
            // 'message_news_id' => $message_news,
            // 'message_url' => $get_news_url,
            'message_flag' => 0, 
            'message_date_created' => date("YmdHis"),
            // 'message_device_id' => !empty($params['device_id']) ? $params['device_id'] : null 
        );  
        // print_r($params);die;
        $this->Message_model->add_message($params);
        // log_message('DEBUG','CALLBACK RETURN: '.$post); 
    }
    function test2(){   
    }
}

?>