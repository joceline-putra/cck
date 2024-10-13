<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('User_model');
        $this->load->model('Aktivitas_model');        
        $this->load->model('Branch_model');

        //Get Branch
        // $get_branch = $this->Branch_model->get_branch(1);
        // $this->app_name = $get_branch['branch_name'];
        $this->app_name = 'Cloud System';            
        $this->app_url  = site_url();  
        // $this->app_logo = site_url().$get_branch['branch_logo'];
        // $this->app_logo_sidebar = site_url().$get_branch['branch_logo_sidebar'];      
        $this->app_logo = site_url().'upload/branch/default_logo.png';
        $this->app_logo_sidebar = site_url().'upload/branch/default_sidebar.png';        
    }

    function index(){
        $data['session'] = $this->session->userdata();
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        $session_user_group = $session['user_data']['user_group_id']; 

        $params = array(
            // 'user_branch_id' => $session_branch_id,
            'user_flag' => 1
        );
        $data['usernya'] = $this->User_model->get_all_users($params,null,null,null,'user_username','asc'); 
        // var_dump($data['usernya']);die;
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
        //ssvar_dump($data['theme']);die;
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        // Date Now
        $datenow =date("d-m-Y");
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        // Logo Branch
        // $get_branch = $this->Branch_model->get_branch(1);
        // $data['branch'] = array(
            // 'branch_logo' => site_url().$get_branch['branch_logo'],
            // 'branch_logo_login' => site_url().$get_branch['branch_logo'],
            // 'branch_logo_sidebar' => site_url().$get_branch['branch_logo_sidebar'],                        
        // );
        $data['branch'] = array(
            'branch_logo' => $this->app_logo,
            'branch_logo_login' => $this->app_logo,
            'branch_logo_sidebar' => $this->app_logo_sidebar          
        );
        // var_dump($data['branch']);die;
        //Smart Dashboard Rule
        $rule = array('1','3','5'); //1=Root, 3=Director, 5=Finance
        if(in_array($session_user_group,$rule)){
            $data['_view'] = 'layouts/admin/menu/dashboard/v1';
            $data['_js']   = 'layouts/admin/menu/dashboard/v1_js';
        }else{
            $data['_view'] = 'layouts/admin/menu/dashboard/v2';
            $data['_js']   = 'layouts/admin/menu/dashboard/v2_js';
        }

        $data['title'] = 'Dashboard';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($data['_js'],$data);        
    }
    function get_session(){
        // return json_decode(json_encode($this->session->userdata()), True);
    }
    function display_404(){
        $this->load->view('webarch/404');
    }
    function display_dashboard(){
        $data['session'] = $this->session->userdata();
        // var_dump($data['session']);die;        
        $this->load->view('webarch/dashboard');
    }

    function whatsapp(){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $whatsapp_server = $this->config->item('whatsapp_server').'send_message.php';
        $whatsapp_token = $this->config->item('whatsapp_token');

        $number = "+62812-2551-8118";

        /* Prepare Target */
        $sent_to = array(
            'user_phone' => str_replace('+','',str_replace('-','',$number)),
            'user_name' => 'Joe Witaya'
        );

        /* Message */
        $body_text = "
            Selamat malam Mr *".$sent_to['user_name']."*
            Harap membuka link dibawah ini
            Terimakasih.
        ";

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
        curl_setopt_array($curl, array(
          CURLOPT_URL => $whatsapp_server,
          CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $body_fields,
          CURLOPT_HTTPHEADER => array("Authorization: ".$whatsapp_token.""),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        /* Result */
        $get_response = json_decode($response,true);
        $return->result = $get_response;
        if($get_response['status'] !== false){
            $return->status = 1;
            $return->message = 'Success';
            $return->message_id = $get_response['id'];
        }
        echo json_encode($return);        
    }
    function whatsapp_get_profile(){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $whatsapp_server = $this->config->item('whatsapp_server').'profile.php';
        $whatsapp_token = $this->config->item('whatsapp_token');
        
        /* Action */
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $whatsapp_server,
          CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_HTTPHEADER => array("Authorization: ".$whatsapp_token.""),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        /* Result */
        $get_response = json_decode($response,true);
        $return->result = $get_response;
        if($get_response['status'] !== false){
            $return->status = 1;
            $return->message = 'Success';
        }
        echo json_encode($return);        
    }
    function whatsapp_check_message_status($id){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $whatsapp_server = $this->config->item('whatsapp_server').'status.php';
        $whatsapp_token = $this->config->item('whatsapp_token');
        
        if(intval($id) > 0){
            /* Action */
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $whatsapp_server,
              CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => array('id' => $id),
              CURLOPT_HTTPHEADER => array("Authorization: ".$whatsapp_token.""),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }

        /* Result */
        $get_response = json_decode($response,true);
        $return->result = $get_response;
        if($get_response['status'] !== false){
            $return->status = 1;
            $return->message = 'Success';
        }
        echo json_encode($return);        
    }
    function whatsapp_test($number,$nama){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        $whatsapp_server = $this->config->item('whatsapp_server');
        $whatsapp_token = $this->config->item('whatsapp_token');

        if(!empty($number)){
            // $number = "+62812-2709-9957";

            /* Prepare Target */
            $sent_to = array(
                'user_phone' => str_replace('+','',str_replace('-','',$number)),
                'user_name' => str_replace('%20',' ',$nama)
            );

            /* Message */
            $body_text = "📄 *Invoice Toko Logam Mulia *
            Hai *".$sent_to['user_name']."*, berikut invoice yang dikirimkan:
            Nomor: *POS-2021-0001*
            Tanggal: *".date("d-M-Y H:i:s", strtotime(date("YmdHis")))."*

            1 Pcs Handel Besi @5.000
            5 Pcs Kawat Besi @10.000

            *Total*
            Rp 15.0000

            _Terimakasih telah bertransaksi di Toko Logam Mulia, ditunggu kedatangannya kembali_ 🙂
            ";

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
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $whatsapp_server,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_POST =>  1,
                    CURLOPT_POSTFIELDS =>  array(
                        'token'    => $whatsapp_token,
                        'phone'     => $sent_to['user_phone'],
                        'message'   => $body_text
                    )
                ));
            }

            $response = curl_exec($curl);

            // curl_close($curl);

            /* Result */
            $get_response = json_decode($response,true);
            $return->result = $get_response;
            if($get_response['status'] !== false){
                $return->status = 1;
                $return->message = 'Success';
                $return->message_id = $get_response['id'];
            }
        }
        $return->number = $number;
        echo json_encode($return);        
    }
}

    

   