<?php

class Callback extends CI_Controller {
	public function __construct(){
		parent::__construct();

        $this->load->config('email');
        $this->load->config('whatsapp');

		$this->load->library('Ruang_mutasi',null, 'mutasi');
		$this->load->model('Mutation_model');
        $this->load->model('Branch_model');  
        $this->load->model('User_model');
        $this->load->model('Flow_model');
        $this->load->model('Vendor_model'); 

        //Get Branch
        $get_branch = $this->Branch_model->get_branch(1);

        $this->app_name = $get_branch['branch_name'];
        $this->app_url  = site_url();  
        $this->app_logo = site_url().$get_branch['branch_logo'];
	}
    public function vendor(){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';
    
        $post = file_get_contents('php://input');
        $datas = json_decode($post);
        if(!empty($datas)){
            $params = array(
                'log_message' => json_encode($datas),
                'log_date_created' => date("YmdHis"),
                'log_flag' => 0
            );
            $insert = $this->Vendor_model->add_vendor_log($params);
            if($insert){
                $return->status=1;
                $return->message='Log Inserted';
            }else{
                $return->message='Error Inserted';
            }
        }else{
            $return->message='Post Data not found';
        }
        echo json_encode($return);
    }    
    public function cekmutasi(){
        // $data = $this->input->post('action');
        $post = file_get_contents('php://input');
        $json = json_decode($post);

        $action = $json->action;
        var_dump($action);
        // log_message('DEBUG','CALLBACK RETURN: '.$json);
    }
    public function whatsapp_sent_mutation($mutation_id){ die; // Not used for new config whatsapp.php
        $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        $return->result = '';

        $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        $whatsapp_server = $this->config->item('whatsapp_server').'send-message.php';
        $whatsapp_token = $this->config->item('whatsapp_token');

        //Fetch From Table Mutation
        $prepare = "SELECT * FROM mutations WHERE mutation_id=$mutation_id";
        $query = $this->db->query($prepare);
        $d = $query->row_array();
        $number = $d['mutation_notif_phone'];

        if(!empty($number)){
            // $number = "+62812-2709-9957";

            /* Prepare Target */
            $sent_to = array(
                'user_phone' => str_replace('+','',str_replace('-','',$number))
            );
            $mutation_type_name = ($d['mutation_type'] == 'D') ? 'DEBET' : 'KREDIT';
            /* Message */
            $body_text  = 'MUTASI *'.$mutation_type_name.'*'."\r\n";
            $body_text .= 'Bank: *'.$d['mutation_api_bank_code']."*"."\r\n";
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

            /* Action */
            $curl = curl_init();
            $content_text = array(
                'token'    => $whatsapp_token,
                'phone'     => $sent_to['user_phone'],
                'message'   => $body_text
            );
            var_dump($whatsapp_server);
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
    public function whatsapp_sent_mutation_test(){ die; // Not used for new config whatsapp.php
        $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        $return->result = '';

        $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        $whatsapp_server = $this->config->item('whatsapp_server').'send-message.php';
        $whatsapp_token = $this->config->item('whatsapp_token');

        //Fetch From Table Mutation
        $number = '6281225518118';

        if(!empty($number)){
            // $number = "+62812-2709-9957";

            /* Prepare Target */
            $sent_to = array(
                'user_phone' => str_replace('+','',str_replace('-','',$number))
            );
            // $mutation_type_name = ($d['mutation_type'] == 'D') ? 'DEBET' : 'KREDIT';
            $mutation_type_name = 'D';

            /* Message */
            $body_text  = 'MUTASI *'.$mutation_type_name.'*'."\r\n";
            $body_text .= 'Bank: *BCA*'."\r\n";
            $body_text .= 'Rekening : *993001212323*'."\r\n";
            $body_text .= 'Tanggal : *2021-06-31 00:00:00*'."\r\n";
            $body_text .= 'Jumlah : *'.number_format(2000000,0,'.',',').'*'."\r\n";
            $body_text .= 'Berita : _Biaya admin ditransfer besok_'."\r\n"."\r\n";
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
        // die;
    }
    public function whatsapp_sent_invoice_test(){ die; // Not used for new config whatsapp.php
        // die; // Not used for new config whatsapp.php
        $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        $return->result = '';

        $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        $whatsapp_server = $this->config->item('whatsapp_server');
        $whatsapp_token  = $this->config->item('whatsapp_token');

        $whatsapp_auth  = $this->config->item('whatsapp_auth');
        $whatsapp_sender  = $this->config->item('whatsapp_sender');        
        $whatsapp_action_send  = $this->config->item('whatsapp_action')['send-message'];  

        // var_dump($whatsapp_action_send);die;

        //Fetch From Table Mutation
        $number = '6281225518118';

        if(!empty($number)){
            // $number = "+62812-2709-9957";

            /* Prepare Target */
            $sent_to = array(
                'user_phone' => str_replace('+','',str_replace('-','',$number))
            );
            // $mutation_type_name = ($d['mutation_type'] == 'D') ? 'DEBET' : 'KREDIT';
            $mutation_type_name = 'D';

            /* Message */
            $body_text  = '📄 *INVOICE*'."\r\n";
            $body_text .= 'Nomor : INV-2101-0001'."\r\n";
            $body_text .= 'Tanggal : 21-Juli-2021'."\r\n";
            $body_text .= 'Perihal : Pembelian Saldo NOTIF'."\r\n\r\n";
            $body_text .= '*Metode Pembayaran:*'."\r\n";
            $body_text .= 'TRANSFER BANK'."\r\n";
            $body_text .= 'Bank : Bank BCA'."\r\n";
            $body_text .= 'Rekening : 993001212323'."\r\n";
            $body_text .= 'Atas Nama : Yoceline Islamwitaya Putra'."\r\n\r\n";
            $body_text .= 'Jumlah : *Rp. '.number_format(100056,0,'.',',').'*'."\r\n\r\n";
            $body_text .= '_Silahkan melakukan pembayaran sesuai nominal diatas hingga angka unik terakhir, Pembayaran akan otomatis diverifikasi oleh Bank_'."\r\n\r\n";
            $body_text .= '_Terimakasih telah melakukan transaksi di platform NOTIF 🙂_'."\r\n\r\n";

            $body_text .= '*Butuh Bantuan*❓'."\r\n";
            $body_text .= 'www.mediadigital.id'."\r\n";
            $body_text .= 'https://wa.me/6289652510558'."\r\n";

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

            /* Action */
            $curl = curl_init();
            $content_text = array(
                'token'    => $whatsapp_token,
                'phone'     => $sent_to['user_phone'],
                'message'   => $body_text
            );
            $whatsapp_server = $whatsapp_server.$whatsapp_action_send.'&sender='.$whatsapp_sender.'&recipient='.$sent_to['user_phone'].'&content='.rawurlencode($body_text);
            var_dump($whatsapp_server);die;
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

            curl_close($curl);

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
        $return->content = array(
            'token' => $whatsapp_token,
            'phone' => $sent_to['user_phone'],
            'message' => $body_text
        );
        // echo json_encode($return);
        return json_encode($return);
        // die;
    }
    public function whatsapp_sent_registration_activation($user_id){ die; // Not used for new config whatsapp.php  
        $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        $return->result = '';

        $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        $whatsapp_server = $this->config->item('whatsapp_server').'send-message.php';
        $whatsapp_token = $this->config->item('whatsapp_token');


        //Get User
        $get_user=$this->User_model->get_user($user_id);

        //Fetch From Table Mutation
        $number = $get_user['user_phone_1'];

        if(!empty($number)){
            // $number = "+62812-2709-9957";

            /* Prepare Target */
            $sent_to = array(
                'user_phone' => str_replace('+','',str_replace('-','',$number))
            );
            // $mutation_type_name = ($d['mutation_type'] == 'D') ? 'DEBET' : 'KREDIT';
            $mutation_type_name = 'D';

            /* Message */
            $body_text  = '*Aktivasi Akun Anda*'."\r\n";
            $body_text .= 'Halo '.ucfirst($get_user['user_fullname'])."\r\n\r\n";
            $body_text .= 'Selamat datang di platform *'.$this->app_name.'*'."\r\n\r\n";

            $body_text .= 'Silahkan klik link dibawah ini untuk konfirmasi pendaftaran anda di platform ini.'."\r\n";
            $body_text .= $this->app_url.'register/activation/'.$get_user['user_activation_code'].'/'.$get_user['user_code']."\r\n";
            
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
            // log_message('debug',$get_response);
            $return->result = $get_response;
            if($get_response['result'] !== false){
                // $return->status = 1;
                // $return->message = 'Success';
            }
        }
        $return->number = $number;
        $return->vendor = $whatsapp_vendor;
        $return->content = array(
            'token' => $whatsapp_token,
            'phone' => $sent_to['user_phone'],
            'message' => $body_text
        );
        echo json_encode($return);
        // return json_encode($return);
        // die;
    }
    /*
    public function callback(){
        $data = $this->mutasi->callback();
        if(isset($data['status']) && $data['status']==true){
            //do something with this data
            file_put_contents('log-mutasi.txt',json_encode($data)."\n", FILE_APPEND);
            $count = count($data['data']);
            foreach($data['data'] as $v):
                $params = array(
                    'mutation_api_bank_id' => $v['account_id'],
                    'mutation_api_date' => $v['created'],
                    'mutation_debit' => $v['debet'],
                    'mutation_credit' => $v['credit'],
                    'mutation_text' => $v['description'],
                    'mutation_api_bank_name' => $v['bank_name'],
                    'mutation_api_bank_code' => $v['bank_code'],
                    'mutation_api_bank_account_number' => $v['account_number']
                );
                $set_data = $this->Mutation_model->add_mutation($params);
                $this->whatsapp_sent_mutation($set_data);
            endforeach;
        }
        log_message('debug','RESPONSE CALLBACK: '.json_encode($data));
    }
    public function get_user_info(){
        $data = $this->mutasi->user_info();
        file_put_contents('log-mutasi.txt',json_encode($data)."\n", FILE_APPEND);
        log_message('debug','RESPONSE USER INFO: '.$data);
    }
    */
    public function live(){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $return->result = $this->Flow_model->get_all_flow('','',100,0,'flow_id','desc');
        
        echo json_encode($return);
    }
    public function sample(){
        $return = new \stdClass();
        $return->status = 1;
        $return->message = 'Berhasil mendapatkan data';
        $return->result = array(
            'data_header' => array(
                'invoice_number' => 'INVOICE-2021-0001',
                'invoice_date' => '17 Sep 2021',
                'invoice_contact_name' => 'Cristiano Ronaldo',
                'invoice_contact_number' => '6281225518118',
                'invoice_contact_address' => 'Jl Jenderal Sudirman No 17'
            ),
            'data_item' => array(
                array(
                    'data_id' => 1,
                    'data_name' => 'Rinso 1Liter Qty 10 Pcs'
                ),
                array(
                    'data_id' => 2,
                    'data_name' => 'Teh Sosro Qty 4 Pcs'
                ),
                array(
                    'data_id' => 3,
                    'data_name' => 'Teh Sosro Qty 2 Pcs'
                )
            ),
            'data_footer' => array(
                'note' => 'Harap membayarkan sesuai nominal yang tertera dibawah ini, Barang yang sudah dibeli tidak dapat ditukar kembali',
                'total' => 'Rp. 200,500',
                'tax' => 'Rp. 200.50',
                'discount' => 'Rp. 50',
                'coupoun' => 'PROMOHEBAT',
                'grand_total' => 'Rp. 220.500'
            ),
        );
        echo json_encode($return);
    }
    public function wati(){
        // $data = $this->input->post('action');
        $post = file_get_contents('php://input');
        $post = $this->input->input_stream('name');
        // $json = json_decode($post);
        $data = '';
        //Type = 1 / Registration
        $flow_type = intval($this->input->get('type'));
        $name = $this->input->get('name');
        $phone = $this->input->get('phone');
        $sender_number = $this->input->get('sender_number');
        $recipient_number = $this->input->get('recipient_number');
        $recipient_name = $this->input->get('recipient_name');
        
        $session_group = !empty($this->input->get('session_group')) ? $this->input->get('session_group') : null;
        
        if($flow_type==1){ //Registration
            $service = $this->input->get('service');
            $day = $this->input->get('day');
            $time = $this->input->get('time');
            $data = $name.', '.$phone.', '.$service.', '.$day.', '.$time;
        }else if($flow_type==2){ //Hotel Booking
            $hotel_adult = $this->input->get('hotel_adult');
            $hotel_child = $this->input->get('hotel_child');
            $hotel_start = $this->input->get('hotel_date_start');
            $hotel_end = $this->input->get('hotel_date_end');
            $hotel_type = $this->input->get('hotel_type');
            $hotel_qty = $this->input->get('hotel_qty');

            $data = $name.', '.$phone.', '.$hotel_type.', '.$hotel_qty.', '.$hotel_adult.', '.$hotel_child.', '.$hotel_start.' sd '.$hotel_end;
        }else if($flow_type==3){ //School Register
            $school_child_name = $this->input->get('school_child_name');
            $school_age = $this->input->get('school_age');
            $school_study = $this->input->get('school_study');

            $data = $name.', '.$phone.', '.$school_child_name.', '.$school_age.', '.$school_study;
        }else if($flow_type==4){ //Restaurant
            $restaurant_capacity = $this->input->get('restaurant_capacity');
            $restaurant_type = $this->input->get('restaurant_type');

            $data = $name.', '.$phone.', '.$restaurant_capacity.', '.$restaurant_type;
        }else if($flow_type==5){ //Order Food
            $order_data = $this->input->get('data');

            $data = $name.', '.$phone.', '.$order_data;
        }else{
            $specialist = $this->input->get('specialist');
            $asisten = $this->input->get('asisten');
            $data = $name.', '.$phone.', '.$specialist.', '.$asisten;
        }
        // $name = $json->name;
        // $phone = $json->phone;
        // var_dump($this->input->post());die;
        // log_message('debug','NAME: '.$name);
        $params = array(
            'flow_type' => !empty($flow_type) ? $flow_type : 0,
            'flow_name' => $name,
            'flow_phone' => $phone,
            'flow_data' => $data,
            'flow_flag' => 0,
            'flow_sender_number' => !empty($sender_number) ? $sender_number : '-',
            'flow_recipient_number' => !empty($sender_number) ? $recipient_number : '-',
            'flow_recipient_name' => !empty($recipient_name) ? $recipient_name : '-',
            'flow_date_created' => date('YmdHis'),
            'flow_session' => md5(date('YmdHis'.$name)),
            'flow_session_group' => $session_group
        );
        $opr = $this->Flow_model->add_flow($params);
        echo json_encode($opr);
    }
    public function register(){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';

        // $post = file_get_contents('php://input');
        // $post = $this->input->input_stream('name');
        $data = '';

        $name = $this->input->get('name');
        $phone = $this->input->get('phone');
        $email = $this->input->get('email');        
        $sender_number = $this->input->get('sender_number');
        $recipient_number = $this->input->get('recipient_number');
        $recipient_name = $this->input->get('recipient_name');
        
        $session_group = !empty($this->input->get('session_group')) ? $this->input->get('session_group') : null;
        
        // var_dump($name);die;
        $pass = $this->random_number(6);
        $params = array(
            'user_user_group_id' => 1,
            'user_code' => $this->random_session(6),
            'user_username' => $phone,
            'user_fullname' => $name,
            'user_phone_1' => $phone,
            'user_email_1' => $email,
            'user_password' => md5($pass),
            'user_theme' => 'black',
            'user_activation_code' => $this->random_session(32),
            'user_date_activation' => '0000-00-00 00:00:00',
            'user_date_created' => date("YmdHis"),
            'user_registration_from_referal' => 'whatsapp_bot',
            'user_session' => $this->random_session(20),
            'user_otp' => $this->random_number(6),
            'user_flag' => 1,
            'user_activation' => 1,
            'user_menu_style' => 0
        );
        // echo json_encode($params);die;
        // $params = array(
        //     'flow_type' => !empty($flow_type) ? $flow_type : 0,
        //     'flow_name' => $name,
        //     'flow_phone' => $phone,
        //     'flow_data' => $data,
        //     'flow_flag' => 0,
        //     'flow_sender_number' => !empty($sender_number) ? $sender_number : '-',
        //     'flow_recipient_number' => !empty($sender_number) ? $recipient_number : '-',
        //     'flow_recipient_name' => !empty($recipient_name) ? $recipient_name : '-',
        //     'flow_date_created' => date('YmdHis'),
        //     'flow_session' => md5(date('YmdHis'.$name)),
        //     'flow_session_group' => $session_group
        // );
        // $opr = $this->Flow_model->add_flow($params);
        $opr = $this->User_model->add_user($params);

        $return->status=1; 
        $return->message='Berhasil mendaftar di Platform ASPRI'; 
        $return->result=0;    
        $return->password=$pass;            
        echo json_encode($return);
                
    }    
    public function xendit(){ die; //Catch from Xendit Log
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';
    
        $post = file_get_contents('php://input');
        $datas = json_decode($post);
        $params = array(
            'log_message' => json_encode($datas),
            'log_date_created' => date("YmdHis")
        );
        $insert = $this->Xendit_model->add_xendit_log($params);
        if($insert){
            $return->status=1;
            $return->message='Log Inserted';
        }else{
            $return->message='Error Inserted';
        }
        echo json_encode($return);
    }
    public function random_number($length){ # JEH3F2
        $text = '1234567890';
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[rand(0, $txtlen)];}
        return $result;
    }
    public function random_session($length){
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ'.time();
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[mt_rand(0, $txtlen)];}
        return $result;
    }    
    public function duitku(){
        // 8552948976da422afbf160bc5c87ba2c  

        $merchantCode = 'DS15272'; // dari duitku
        $apiKey = '8552948976da422afbf160bc5c87ba2c'; // dari duitku
        $paymentAmount = 40000;
        $paymentMethod = 'SP'; // VC = Credit Card , SP = Shopepay
        $merchantOrderId = time() . ''; // dari merchant, unik
        $productDetails = 'Tes pembayaran menggunakan MINIODuitku';
        $email = 'joceline.putra@gmail.com'; // email pelanggan anda
        $phoneNumber = '628989900148'; // nomor telepon pelanggan anda (opsional)
        $additionalParam = ''; // opsional
        $merchantUserInfo = ''; // opsional
        $customerVaName = 'John Doe'; // tampilan nama pada tampilan konfirmasi bank
        $callbackUrl = 'http://example.com/callback'; // url untuk callback
        $returnUrl = 'http://example.com/return'; // url untuk redirect
        $expiryPeriod = 10; // atur waktu kadaluarsa dalam hitungan menit
        $signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

        // Customer Detail
        $firstName = "Joe";
        $lastName = "Witaya";

        // Address
        $alamat = "Jl. Gatot Subroto Blok 11D No 2";
        $city = "Semarang";
        $postalCode = "50131";
        $countryCode = "ID";

        $address = array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'address' => $alamat,
            'city' => $city,
            'postalCode' => $postalCode,
            'phone' => $phoneNumber,
            'countryCode' => $countryCode
        );

        $customerDetail = array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'billingAddress' => $address,
            'shippingAddress' => $address
        );

        $item1 = array(
            'name' => 'Perpanjangan Server',
            'price' => 10000,
            'quantity' => 1);

        $item2 = array(
            'name' => 'Perpanjangan Domain',
            'price' => 30000,
            'quantity' => 3);

        $itemDetails = array(
            $item1, $item2
        );

        /*Khusus untuk metode pembayaran OL dan SL
        $accountLink = array (
            'credentialCode' => '7cXXXXX-XXXX-XXXX-9XXX-944XXXXXXX8',
            'ovo' => array (
                'paymentDetails' => array ( 
                    0 => array (
                        'paymentType' => 'CASH',
                        'amount' => 40000,
                    ),
                ),
            ),
            'shopee' => array (
                'useCoin' => false,
                'promoId' => '',
            ),
        );*/

        /*Khusus untuk metode pembayaran Kartu Kredit
        $creditCardDetail = array (
            'acquirer' => '014',
            'binWhitelist' => array (
                '014',
                '400000'
            )
        );*/

        $params = array(
            'merchantCode' => $merchantCode,
            'paymentAmount' => $paymentAmount,
            'paymentMethod' => $paymentMethod,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => $additionalParam,
            'merchantUserInfo' => $merchantUserInfo,
            'customerVaName' => $customerVaName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            //'accountLink' => $accountLink,
            //'creditCardDetail' => $creditCardDetail,
            'itemDetails' => $itemDetails,
            'customerDetail' => $customerDetail,
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'signature' => $signature,
            'expiryPeriod' => $expiryPeriod
        );

        $params_string = json_encode($params);
        //echo $params_string;
        $url = 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'; // Sandbox
        // $url = 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry'; // Production
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($params_string))                                                                       
        );   
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        //execute post
        $request = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($httpCode == 200)
        {
            $result = json_decode($request, true);
            //header('location: '. $result['paymentUrl']);
            echo "paymentUrl :". $result['paymentUrl'] . "<br />";
            echo "merchantCode :". $result['merchantCode'] . "<br />";
            echo "reference :". $result['reference'] . "<br />";
            echo "vaNumber :". $result['vaNumber'] . "<br />";
            echo "amount :". $result['amount'] . "<br />";
            echo "statusCode :". $result['statusCode'] . "<br />";
            echo "statusMessage :". $result['statusMessage'] . "<br />";
        }
        else
        {
            $request = json_decode($request);
            $error_message = "Server Error " . $httpCode ." ". $request->Message;
            echo $error_message;
        }              
    }
}