<?php
/*
    @AUTHOR: Joe Witaya
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends MY_Controller{

    var $folder_upload = 'uploads/survey/';

    function __construct(){
        parent::__construct();
        // if(!$this->is_logged_in()){
        //     redirect(base_url("login"));
        // }
        /*
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->load->model('Aktivitas_model');
        */
        $this->load->model('User_model');
        $this->load->model('Branch_model');
        $this->load->model('Survey_model');
    }
    function index(){

        $survey_link = base_url().'survey/request/';

        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }

        if ($this->input->post()) {
            $data['session'] = $this->session->userdata();
            $session_user_id = $data['session']['user_data']['user_id'];

            $upload_directory = $this->folder_upload;
            $upload_path_directory = FCPATH . $upload_directory;

            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            switch($action){
                case "create":
                    $contact_id = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : null;
                    $contact_name = !empty($this->input->post('contact_name')) ? $this->input->post('contact_name') : null;
                    $contact_number = !empty($this->input->post('contact_number')) ? $this->input->post('contact_number') : 1;
                    $survey_message = !empty($this->input->post('text')) ? $this->input->post('text') : null;

                    // $survey_session = !empty($this->input->post('session')) ? $this->input->post('session') : null;
                    $survey_note = !empty($this->input->post('note')) ? $this->input->post('note') : '-';
                    $survey_rating = !empty($this->input->post('rating')) ? $this->input->post('rating') : 0;

                    $survey_session = $this->survey_session(20);
                    $set_message_text = str_replace('#nama#',$contact_name,$survey_message);
                    // $set_message_text = str_replace('#link#',$survey_link.$survey_session,$set_message_text);
                    $set_message_text = str_replace('#link#','',$set_message_text);

                    $set_survey_link = $survey_link.$survey_session;

                    $params = array(
                        'survey_session' => $survey_session,
                        'survey_date_created' => date("YmdHis"),
                        'survey_rating' => $survey_rating,
                        'survey_note' => $survey_note,
                        // 'survey_flag' => 0,
                        'survey_contact_id' => $contact_id,
                        'survey_contact_name' => $contact_name,
                        'survey_contact_number' => $contact_number,
                        'survey_text' => $set_message_text,
                        'survey_link' => $set_survey_link
                    );
                    // var_dump($params);die;
                    //Check Data Exist
                    $params_check = array(
                        'survey_session' => $survey_session
                    );
                    $check_exists = $this->Survey_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Survey_model->add_survey($params);
                        if($set_data==true){

                            //Sent WhatsApp Process
                            $get_execute = $this->whatsapp_send_survey($set_data);
                            // $get_execute = $this->whatsapp_sent($set_data);
                            $response = json_decode($get_execute,true);

                            $return->status = ($response['result']['result'] == 'true') ? 1 : 0;
                            // var_dump($response,$return->status);die;
                            // $return->message = ($response['result']['result'] == true) ? $response['result']['message'] : 'Gagal Mengirim';  
                            $return->message = $response['result']['message'];
                            
                            //Update Flag is Sent
                            if($return->status==1){
                                $params = array(
                                    'survey_date_sent' => date("YmdHis"),
                                    'survey_flag' => 0
                                );
                                $set_update=$this->Survey_model->update_survey($set_data,$params);
                            }
                            //End WhatsApp Process

                            $data = $this->Survey_model->get_survey($set_data);
                            $return->status=1;
                            $return->message='Berhasil mengirim';
                            $return->result= array(
                                'id' => $set_data
                            );
                        }
                    }else{
                        $return->message='Data sudah ada';
                    }
                    $return->action=$action;
                    echo json_encode($return);
                    break;                    
                case "read":
                    $survey_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;
                    if(intval($survey) > 0){
                        $datas = $this->Survey_model->get_survey($survey_id);
                        if($datas==true){
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
                case "update":
                    //$post_data = $this->input->post('data');
                    //$data = json_decode($post_data, TRUE);
                    $survey_id = !empty($this->input->post('id')) ? $this->input->post('id') : $data['id'];
                    $survey_name = !empty($this->input->post('name')) ? $this->input->post('name') : $data['name'];
                    $survey_flag = !empty($this->input->post('status')) ? $this->input->post('status') : $data['status'];

                    $params = array(
                        'survey_name' => $survey_name,
                        'survey_date_updated' => date("YmdHis"),
                        'survey_flag' => $survey_flag
                    );

                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */

                    $set_update=$this->Survey_model->update_survey($survey_id,$params);
                    if($set_update==true){

                        $data = $this->Survey_model->get_survey($survey_id);

                        /* Activity */
                        /*
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 4,
                            'activity_table' => 'surveys',
                            'activity_table_id' => $id,
                            'activity_text_1' => '',
                            'activity_text_2' => ucwords(strtolower($survey_name),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        */
                        /* End Activity */
                        $return->status  = 1;
                        $return->message = 'Berhasil memperbarui '.$survey_name;
                    }else{
                        $return->message='Gagal memperbarui '.$survey_name;
                    }
                    $return->action=$action;
                    echo json_encode($return);
                    break;
                case "delete":
                    //$post_data = $this->input->post('data');
                    //$data = json_decode($post_data, TRUE);
                    $survey_id   = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                    $survey_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;

                    if(intval($survey_id) > 0){
                        $get_data=$this->Survey_model->get_survey($survey_id);
                        $set_data=$this->Survey_model->delete_survey($survey_id);
                        if($set_data==true){
                            /*
                            if (file_exists($get_data['survey_image'])) {
                                unlink($get_data['survey_image']);
                            }
                            */
                            /* Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => $act,
                                'activity_table' => 'surveys',
                                'activity_table_id' => $id,
                                'activity_text_1' => '',
                                'activity_text_2' => ucwords(strtolower($survey_name)),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil menghapus'.$survey_name;
                        }else{
                            $return->message='Gagal menghapus '.$survey_name;
                        }
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;
                    echo json_encode($return);
                    break;
                case "load":
                    $columns = array(
                        '0' => 'survey_id',
                        '1' => 'survey_note',
                        '2' => 'survey_rating'
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

                    // If Form Mode Transaction CRUD not Master CRUD
                    !empty($this->input->post('date_start')) ? $params['survey_date_created >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 23:59:59')) : $params;
                    !empty($this->input->post('date_end')) ? $params['survey_date_created <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;


                    //Default Params for Master CRUD Form
                    $params['survey_flag']   = $this->input->post('flag');

                    if($this->input->post('rating') && $this->input->post('rating') > 0) {
                        $params['survey_rating'] = $this->input->post('rating');
                    }
                    
                    $datas = $this->Survey_model->get_all_survey($params, $search, $limit, $start, $order, $dir);

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
                    echo json_encode($return);
                    break;
                case "whatsapp-scan-qrcode":
                    //Get WhatsApp QR Code
                    $get_execute = $this->whatsapp_qrcode();
                    $response = json_decode($get_execute,true);
                    // var_dump($response);die;
                    $return->status = ($response['result']['status'] == true) ? 0 : 1;
                    $return->message = ($response['result']['status'] == true) ? $response['result']['message'] : 'Server Sudah Terhubung ke WhatsApp';  
                    $return->result = $response['result'];
                    echo json_encode($return);
                    break;                
                default:
                    // Date Now
                    $firstdate = new DateTime('first day of this month');
                    $firstdateofmonth = $firstdate->format('Y-m-d');
                    $datenow =date("Y-m-d");
                    $data['first_date'] = $firstdateofmonth;
                    $data['end_date'] = $datenow;
            }
        }else{

            $data['session'] = $this->session->userdata();
            $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

            // $session = $this->session->userdata();
            // $session_branch_id = $session['user_data']['branch']['id'];
            // $session_user_id = $session['user_data']['user_id'];

            // Date Now
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('d-m-Y');
            $datenow =date("d-m-Y");
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = $datenow;

		    $get_branch = $this->Branch_model->get_branch(1);
		    $data['branch'] = array(
		        'branch_logo' => site_url().$get_branch['branch_logo'],
		        'branch_logo_login' => site_url().$get_branch['branch_logo'],
		        'branch_logo_sidebar' => site_url().$get_branch['branch_logo_sidebar'],
		    );
		    // $data['session'] = $this->survey_session(20);

            $data['title'] = 'Survey Kepuasan';
            $data['_view'] = 'layouts/admin/menu/message/survey';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/message/survey_js',$data);
        }
    }
    function whatsapp_send_survey($survey_id){
        $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        $return->result = '';

        $survey_id         = $$survey_id;
        $survey_session    = $this->input->get('session');
        $token              = $this->input->get('token');
        $key                = $this->input->get('key');

        $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        $whatsapp_server = $this->config->item('whatsapp_server');
        $whatsapp_token = $this->config->item('whatsapp_token');
        $whatsapp_key = $this->config->item('whatsapp_key');

        if($whatsapp_vendor == "umbrella.co.id"){
            if((!empty($token) && intval($token) == 21) && (!empty($key) && intval($key) == 21)){
                
                if(!empty($message_id)){
                    // $prepare = "SELECT * FROM messages WHERE message_session='$session'";
                    // $query = $this->db->query($prepare);
                    // $get_message = $query->row_array();
                    $get_survey = $this->Survey_model->get_survey($survey_id);

                    $recipient_number = $get_survey['survey_contact_number'];
                    $recipient_number = str_replace('+','',str_replace('-','',$recipient_number));
                    $recipient_name = $get_survey['survey_contact_name'];

                    if(!empty($get_survey['survey_session']) and strlen($get_survey['survey_session'])){
                        if(!empty($get_survey['survey_contact_number'])){

                            if(!empty($get_survey['survey_text'])){

                                //Prepare Content From Messages Tables
                                $content = $get_message['message_text'];

                                //Prepare Parameter
                                $url = '&key=21';
                                $url .= '&token=21';
                                // $url .= '&session='.$message_session;
                                $url .= '&sender=6289652510558';
                                $url .= '&recipient='.$recipient_number;
                                $url .= '&content='.$content;

                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => $whatsapp_server.$url,
                                    CURLOPT_HEADER => 0,
                                    CURLOPT_RETURNTRANSFER => 1,
                                    CURLOPT_SSL_VERIFYHOST => 2,
                                    CURLOPT_SSL_VERIFYPEER => 0,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_POST =>  1,
                                    CURLOPT_POSTFIELDS => array(
                                        // 'token' => $whatsapp_token,
                                        // 'phone' => $recipient_number,
                                        // 'message' => $content,
                                        // 'action' => 'send',
                                        // 'recipient' => $recipient_number,
                                        // 'content' => 'Test CURL',
                                        // 'session' => $session,
                                        // 'token' => 21,
                                        // 'key' => 21
                                    )
                                ));

                                //Send Link
                                $survey_url = '&recipient='.$recipient_number.'&sender='.$sender_number.'&content='.$get_survey['survey_link'];
                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => $whatsapp_server.$whatsapp_action['send-message'].$survey_url,
                                    CURLOPT_HEADER => 0,
                                    CURLOPT_RETURNTRANSFER => 1,
                                    CURLOPT_SSL_VERIFYHOST => 2,
                                    CURLOPT_SSL_VERIFYPEER => 0,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_POST =>  1,
                                    CURLOPT_POSTFIELDS => array()
                                ));

                                $response = curl_exec($curl);
                                // curl_close($curl);

                                /* Result CURL / API */
                                $get_response = json_decode($response,true);
                                // var_dump($get_response);die;
                                $result = $get_response['result'];
                                $result['message'] = array();
                                $result['message'] = $get_response['message'];

                                $return->status = $get_response['status'];
                                $return->message = $get_response['message'];
                            }else{
                                $return->message='Centent is empty';
                            }
                        }
                    }else{
                        $return->message='Session not found';
                    }
                }else{
                    $return->message='Session invalid';
                }
            }else{
                $return->message='Access Denied';
            }
        }else if ($whatsapp_vendor == 'ruangwa.id'){
            //Fetch From Table Message
            $get_datas = $this->Survey_model->get_survey($survey_id);
            $number = $get_datas['survey_contact_number'];
            $nama = $get_datas['survey_contact_name'];

            if(!empty($number)){
                // $number = "+62812-2709-9957";

                /* Prepare Target */
                $sent_to = array(
                    'user_phone' => str_replace('+','',str_replace('-','',$number)),
                    'user_name' => str_replace('%20',' ',$nama)
                );

                /* Message */
                $body_text = $get_datas['survey_text'];
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

                $content_text = array(
                    'token'    => $whatsapp_token,
                    'phone'     => $sent_to['user_phone'],
                    'message'   => $body_text
                );
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $whatsapp_server,
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
    }    
    function survey_session($length){ die;
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ'.time();
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[mt_rand(0, $txtlen)];}
        return $result;
    }
    function request($session){ die;
        if ($this->input->post()) {
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            switch($action){
                case "update-from-outside":
                    // $survey_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;
                    // $survey_status = !empty($this->input->post('status')) ? $this->input->post('status') : 1;

                    $survey_session = !empty($this->input->post('session')) ? $this->input->post('session') : null;
                    $survey_note = !empty($this->input->post('note')) ? $this->input->post('note') : null;
                    $survey_rating = !empty($this->input->post('rating')) ? $this->input->post('rating') : null;

                    $params = array(
                        'survey_date_updated' => date("YmdHis"),
                        'survey_rating' => $survey_rating,
                        'survey_note' => $survey_note,
                        'survey_flag' => 1
                    );

                    $where = array(
                        'survey_session' => $survey_session
                    );
                    $set_data=$this->Survey_model->update_survey_custom($where,$params);
                    if($set_data==true){
                        $data = $this->Survey_model->get_survey_custom($where);
                        $return->status=1;
                        $return->message='Berhasil mengirim';
                        $return->result = array(
                            'id' => $data['survey_id'],
                            'session' => $data['survey_session']
                        );
                    }
                    $return->action=$action;
                    echo json_encode($return);
                    break;
                default:
                    echo 'Else';
            }        
        }else{        
            $where = array(
                'survey_session' => $session
            );
            $get_survey = $this->Survey_model->get_survey_custom($where);

            // if(intval($get_survey['survey_flag']) == 0){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch'] = array(
                    'branch_logo' => site_url().$get_branch['branch_logo'],
                    'branch_logo_login' => site_url().$get_branch['branch_logo'],
                    'branch_logo_sidebar' => site_url().$get_branch['branch_logo_sidebar'],
                );
                $data['title'] = 'Survey Kepuasan';
                $data['session'] = $get_survey['survey_session']; 
                $data['flag'] = $get_survey['survey_flag'];        
                $this->load->view('layouts/admin/survey/survey',$data);
            // }else{
            //     echo 'Survey sudah di isi';
            // }
        }
    }
}

?>