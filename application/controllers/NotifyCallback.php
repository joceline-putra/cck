<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
class NotifyCallback extends CI_Controller {

    public function __construct(){
        parent::__construct();

        $this->load->config('email');
        $this->load->config('whatsapp');

        $this->load->library('Ruang_mutasi',null, 'mutasi');
        $this->load->model('Mutation_model');
        $this->load->model('Bank_model');
        $this->load->model('Branch_model');  
        $this->load->model('User_model');
        $this->load->model('Flow_model');

        //Get Branch
        $get_branch = $this->Branch_model->get_branch(1);

        $this->app_name = $get_branch['branch_name'];
        $this->app_url  = site_url();  
        $this->app_logo = site_url().$get_branch['branch_logo'];
    }

    public function cekmutasi(){
        // $data = $this->input->post('action');
        $post = file_get_contents('php://input');
        $json = json_decode($post);

        $action = $json->action;
        /*
        var_dump($action);
        {
            "action": "payment_report",
            "content": {
                "service_name": "BCA",
                "service_code": "bca",
                "account_number": "12345678",
                "account_name": "Joceline Putra",
                "data": [
                    {
                        "id": 1,
                        "unix_timestamp": 1625299324,
                        "type": "credit",
                        "amount": "125021.00",
                        "description": "Test From Cekmutasi",
                        "balance": "0.00"
                    }
                ],
                "timezone": "Asia\/Jakarta"
            }
        }        
        log_message('DEBUG','CALLBACK RETURN: '.$json);
        */
        $account_number = $json->content->account_number;
        $account_name   = $json->content->account_name;
        $service_name   = $json->content->service_name;
        $service_code   = $json->content->service_code;

        $get_bank = $this->Bank_model->get_bank_custom(array('bank_account_number'=>$account_number));

        foreach($json->content->data as $v):
            $timestamp = $v->unix_timestamp;
            // date_default_timezone_set('Asia/Jakarta');
            $dt = new DateTime('@'.$timestamp);
            $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
            $time = $dt->format('Y-m-d H:i:s');

            $debit = 0;
            $credit = 0;
            if($v->type == 'debit'){
                $debit = $v->amount;
                $type = 'D';
            }else if($v->type == 'credit'){
                $credit = $v->amount;
                $type = 'C';
            }

            $params = array(
                'mutation_api_id' => $v->id,
                'mutation_api_bank_id' => $get_bank['bank_api_id'],
                // 'mutation_api_date' => gmdate("Y-m-d\TH:i:s\Z", $timestamp),
                'mutation_api_date' => $time,
                'mutation_time' => $v->unix_timestamp,
                'mutation_type' => $type,
                'mutation_debit' => $debit,
                'mutation_credit' => $credit,
                'mutation_text' => $v->description,
                'mutation_api_bank_name' => $service_name,
                'mutation_api_bank_code' => $service_code,
                'mutation_api_bank_account_number' => $account_number
            );
            $set_data = $this->Mutation_model->add_mutation($params);
            
            $this->whatsapp_send_message_mutation($set_data);
        
        endforeach;
    }
    // function whatsapp_send_message_mutation($mutation_id = 0, $token = 0, $key = 0){ 
    // function whatsapp_send_message_mutation(){
    public function whatsapp_send_message_mutation($mutation_id){
        // $mutation_id = 11;
        // $token = 21; 
        // $key = 21;
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';

        $mutation_id         = intval($mutation_id);
        // $mutation_session    = $this->input->get('session');
        $token              = 21;
        $key                = 21;

        // $sender             = $this->input->get('sender');
        // $recipient          = $this->input->get('recipient');
        // $content            = $this->input->get('content');

        $whatsapp_vendor    = $this->config->item('whatsapp_vendor');
        $whatsapp_server    = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');
        $whatsapp_token     = $this->config->item('whatsapp_token');
        $whatsapp_key       = $this->config->item('whatsapp_key');
        $whatsapp_auth      = $this->config->item('whatsapp_auth');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');
        
        // var_dump($mutation_id,$mutation_session);die;

        if($whatsapp_vendor == "umbrella.co.id"){
            // if((!empty($token) && intval($token) == 21) && (!empty($key) && intval($key) == 21)){
            if((!empty($whatsapp_token) && intval($whatsapp_token) == $token) && (!empty($whatsapp_key) && intval($whatsapp_key) == $key)){            
                $next=true;
                $url = '';

                //Get Message 
                if( (!empty($mutation_session) && strlen($mutation_session) > 1) or (!empty($mutation_id) && strlen($mutation_id) > 0)){
                    
                    if(intval($mutation_id) > 0){
                        $params = array('mutation_id' => $mutation_id);
                        $url = '&id='.$mutation_id;
                    }
                    // if(strlen($mutation_session) > 1){
                    //     $params = array('mutation_session' => $mutation_session);
                    //     $url = '&session='.$mutation_session;
                    // }

                    $get_mutation = $this->Mutation_model->get_mutation_custom($params);
                    // echo json_encode($get_mutation);die;
                    $recipient_number = str_replace('+','',str_replace('-','',$get_mutation['mutation_notif_phone']));
                    $recipient_name = $get_mutation['bank_account_name'];
                    // $sender_number = $get_message['device_number'];
                    $sender_number = $whatsapp_sender;

                    if(count($get_mutation) > 0){
                        $d=$get_mutation;
                        $mutation_type_name = ($d['mutation_type'] == 'D') ? '*DEBIT*' : '*KREDIT*';
                        $color = !empty($d['mutation_type'] == 'D') ? '🟥' : '🟩';

                        /* Message */
                        $body_text  = $color.' MUTASI '.$mutation_type_name."\r\n";
                        $body_text .= 'Bank: *'.$d['mutation_api_bank_name']."*"."\r\n";
                        $body_text .= 'Rekening : *'.$d['mutation_api_bank_account_number']."*"."\r\n";
                        $body_text .= 'Tanggal : *'.date("d-M-Y, H:i", strtotime($d['mutation_api_date'])).'*'."\r\n";
                        $body_text .= 'Jumlah : *'.number_format($d['mutation_total'],0,'.',',').'*'."\r\n";
                        $body_text .= 'Berita : _'.$d['mutation_text'].'_'."\r\n"."\r\n";
                        $body_text .= '🔒 Pesan hanya anda yang menerimanya.';

                        //Send Message Session & ID
                        $content = $body_text;
                        $url .= '&recipient='.$recipient_number.'&sender='.$sender_number;
                        $url .= '&content='.rawurlencode($content);
                        // $set_url = $whatsapp_server.$url;
                    }else{
                        $return->message='Mutation not found';
                    }
                }else{
                    //Send Message Directly
                    // $url .= '&key=21&token=21&recipient='.$recipient_number.'&sender='.$sender_number;
                    // $url .= '&content='.rawurlencode($content);
                    $return->message='Data not found';
                    $next=false;
                }

                // var_dump($set_url);die;
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
            $get_datas = $this->Message_model->get_message($mutation_id);
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

        // return json_encode($return);
        echo json_encode($return);
    }
    function test(){
        // $as = $this->whatsapp_send_message_mutation(14);
        // var_dump($as);die;
    }
}