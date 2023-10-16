<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends My_Controller{ 
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
        }
        $this->load->model('User_model');        
        $this->load->model('Branch_model');                   
        $this->load->model('Aktivitas_model');           
        $this->load->helper('date');
    }
    function index(){
    }
    function manage(){
        $session = $this->session->userdata(); 
        // $session_branch_id = $session['user_data']['branch']['id'];
        // $session_user_id = $session['user_data']['user_id'];
        // var_dump($session_user_id);die;
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';      
        if($this->input->post('action')){
            $action = $this->input->post('action');
            // $post_data = $this->input->post('data');
            // $data = json_decode($post_data, TRUE);            
            // $identity = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];

            if($action=='create-branch'){
                
                $nama       = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;   
                $specialist = !empty($this->input->post('specialist')) ? $this->input->post('specialist') : null;
                $address    = !empty($this->input->post('alamat')) ? $this->input->post('alamat') : null;   
                $phone      = !empty($this->input->post('telepon')) ? $this->input->post('telepon') : null;   
                $email      = !empty($this->input->post('email')) ? $this->input->post('email') : null;   
                $provinsi   = !empty($this->input->post('provinsi')) ? $this->input->post('provinsi') : null;
                $kota       = !empty($this->input->post('kota')) ? $this->input->post('kota') : null;  
                $kecamatan  = !empty($this->input->post('kecamatan')) ? $this->input->post('kecamatan') : null;
                $status     = !empty($this->input->post('status')) ? $this->input->post('status') : 1;         
                $with_stock  = !empty($this->input->post('with_stock')) ? $this->input->post('with_stock') : 'No';
                $with_journal  = !empty($this->input->post('with_journal')) ? $this->input->post('with_journal') : 'No';

                $session_user_id  = !empty($this->input->post('ui')) ? $this->input->post('ui') : null;
                $session_user_session     = !empty($this->input->post('us')) ? $this->input->post('us') : 1;                         
                //Check Data Exist
                // $params_check = array(
                    // 'branch_name' => $contact_name,
                // );
                // $check_exists = $this->Branch_model->check_data_exist($params_check);
                $check_exists = false;
                if($check_exists==false){
                    $params = array(
                        'branch_name' => $this->sentencecase($nama),
                        'branch_address' => $address,
                        // 'branch_phone_1' => $phone,
                        // 'branch_email_1' => $email,
                        'branch_specialist_id' => $specialist,
                        'branch_province_id' => $provinsi,
                        'branch_city_id' => $kota,
                        'branch_district_id' => $kecamatan,
                        // 'branch_note' => $keterangan,         
                        'branch_date_created' => date("YmdHis"),
                        'branch_date_updated' => date("YmdHis"),
                        'branch_flag' => $status,
                        'branch_user_id' => $session_user_id,
                        'branch_transaction_with_stock' => $with_stock,
                        'branch_transaction_with_journal' => $with_journal
                    );
                    // var_dump($params);die;
                    $set_data=$this->Branch_model->add_branch($params);
                    if($set_data==true){

                        $id = $set_data;
                        $data = $this->Branch_model->get_branch($id);

                        //Set Branch To User
                        $params_user = array(
                            'user_branch_id' => $id
                        );
                        $this->User_model->update_user($session_user_id,$params_user);
                        
                        //Set Setup Accounts Items to Account
                        $get_branch = $this->Branch_model->get_branch($set_data);
                        $branch_id = $get_branch['branch_id'];
                        $branch_specialist_id = $get_branch['branch_specialist_id'];
                        $setup_account = $this->set_account_from_setup_accounts_items($branch_id,$branch_specialist_id);
                        $setup_user_menu = $this->set_user_menu_from_setup_branch($session_user_id);                        
                        // $setup_account = true;

                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $set_data,
                            'activity_action' => 1,
                            'activity_table' => 'branchs',
                            'activity_table_id' => $set_data,                            
                            'activity_text_1' => strtoupper($nama),
                            'activity_text_2' => ucwords(strtolower($nama)),                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1
                        );
                        $this->save_activity($params);

                        $get_user = $this->User_model->get_user($session_user_id);

                        //Set Cookie
                        $cookie = array(
                            'name' => site_url(),
                            'value' => $get_user['user_username'],
                            'expire' => strtotime('+3 day'),
                            'path' => '/'                    
                        );
                        $this->input->set_cookie($cookie);
                        
                        $return->status=1;
                        $return->message='Sukses mendaftarkan perusahaan';
                        $return->result= array(
                            'user_id' => $session_user_id,
                            'branch_id' => $branch_id,
                            'setup_account_from_branch' => $setup_account,
                            'setup_user_menu' => $setup_user_menu,
                            'return_url' => site_url()
                        );                         
                    }
                }else{
                    $return->message='Gagal mendaftarkan perusahaan';                    
                }
            }
        }else{

        }
        echo json_encode($return);
    }
    function setup_company(){
        // $session = $this->session->userdata(); 
        // $session_branch_id = !empty($session['user_data']['branch']['id']) ? intval($session['user_data']['branch']['id']) : 0;
        // $session_user_id = !empty($session['user_data']['user_id']) ? intval($session['user_data']['user_id']) : 0;
        // $next = true;

        // $get_user = $this->User_model->get_user($session_user_id);
        
        // if($session_user_id == 0){
        //     $next=false;
        // }

        // if($get_user['user_branch_id'] == 0){
            $next=false;
            $data['title'] = 'Register Perusahaan';
            // $data['fullname'] = $session['user_data']['user_fullname'];
            // $data['phone'] = $session['user_data']['user_phone'];
            // $data['email'] = $session['user_data']['user_email'];      
            
            //Logo Branch
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch'] = array(
                'branch_logo' => !empty($get_branch['branch_logo']) ? site_url().$get_branch['branch_logo'] : site_url().'upload/branch/default_logo.png',
                'branch_logo_login' => !empty($get_branch['branch_logo']) ? site_url().$get_branch['branch_logo'] : site_url().'upload/branch/default_logo.png',
                'branch_logo_sidebar' => !empty($get_branch['branch_logo_sidebar']) ? site_url().$get_branch['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png',                        
            );                  
            
            return $this->load->view('layouts/admin/register/setup_company',$data);            
        // }else{
        //     redirect(site_url());
        // }
    }
    /*
    function setup_account(){
        $session = $this->session->userdata(); 
        $session_branch_id = !empty($session['user_data']['branch']['id']) ? intval($session['user_data']['branch']['id']) : 0;
        $session_user_id = !empty($session['user_data']['user_id']) ? intval($session['user_data']['user_id']) : 0;
        $next = true;

        $get_user = $this->User_model->get_user($session_user_id);
        
        if($session_user_id == 0){
            $next=false;
        }

        if($get_user['user_branch_id'] == 0){
            $next=false;
            $data['title'] = 'Register Perusahaan';
            $data['fullname'] = $session['user_data']['user_fullname'];
            $data['phone'] = $session['user_data']['user_phone'];
            $data['email'] = $session['user_data']['user_email'];            
            return $this->load->view('layouts/admin/register/setup_account',$data);            
        }else{
            redirect(site_url());
        }
    }  
    */  
}
