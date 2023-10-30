<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_Controller{
    var $folder_location = array(
        '0' => array(
            'title' => 'Login',
            'view' => 'layouts/admin/login/login',  
        ),
        '1' => array(
            'title' => 'Daftar',
            'view' => 'layouts/admin/register/register',  
        ),
        '2' => array(
            'title' => 'Lost Password',
            'view' => 'layouts/admin/login/password_lost',  
        ),
        '3' => array(
            'title' => 'Register Confirmation',
            'view' => 'layouts/admin/register/confirmation',
            'view2' => 'register/confirmation',
        ),
        '4' => array(
            'title' => 'Reset Password',
            'view' => 'layouts/admin/login/password_reset',
        ),
        '5' => array(
            'title' => 'Register Activation',
            'view' => 'layouts/admin/register/activation',   
        ),
        '6' => array(
            'title' => 'Permintaan Lupa Password Telah dikirim',
            'view' => 'layouts/admin/login/password_sent',   
        )
    );
    function __construct(){
        parent::__construct();
        // if(!$this->is_logged_in()){
            // redirect(base_url("login"));
        // }                   
        $this->load->helper('date');       
        $this->load->helper('form');
        $this->load->helper('cookie');        
        $this->load->helper('url');
        
        $this->load->library('form_validation');
        $this->load->library('user_agent');
        $this->load->library('phpmailer_lib');

        $this->load->model('Login_model');
        $this->load->model('Message_model');           
        $this->load->model('User_model');           
        $this->load->model('Aktivitas_model');           
        $this->load->model('Branch_model');
        $this->load->model('App_package_model');

        //Get Branch
        // $get_branch = $this->Branch_model->get_branch(1);
        // $this->app_name = $get_branch['branch_name'];
        $this->app_name     = 'Cloud System';
        $this->app_url      = site_url();  
        $this->package_id   = 3;
        $this->package_name = 'Enterprise';
        // $this->app_logo = site_url().$get_branch['branch_logo'];
        // $this->app_logo_sidebar = site_url().$get_branch['branch_logo_sidebar'];      
        $this->app_logo     = site_url().'upload/branch/default_logo.png';
        $this->app_logo_sidebar = site_url().'upload/branch/default_sidebar.png';                                
    }
    function index(){ //Default Login Index Layout
        //Logo Branch
        // $get_branch = $this->Branch_model->get_branch(1);
        // $data['branch'] = array(
        //     'branch_logo' => !empty($get_branch['branch_logo']) ? site_url().$get_branch['branch_logo'] : site_url().'upload/branch/default_logo.png',
        //     'branch_logo_login' => !empty($get_branch['branch_logo']) ? site_url().$get_branch['branch_logo'] : site_url().'upload/branch/default_logo.png',
        //     'branch_logo_sidebar' => !empty($get_branch['branch_logo_sidebar']) ? site_url().$get_branch['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png',                        
        // );       
        $data['branch'] = array(
            'branch_logo' => $this->app_logo,
            'branch_logo_login' => $this->app_logo,
            'branch_logo_sidebar' => $this->app_logo_sidebar          
        );            
        // var_dump($data['branch']);die;
        $data['title'] = $this->folder_location['0']['title']; //Login
        return $this->load->view($this->folder_location['0']['view'],$data); 
    }
    function pages($identity){
        $data['session'] = $this->session->userdata();
        //Random Session Code
        $data['captcha'] = $this->random_number(6);
        $this->session->set_userdata('captcha',$data['captcha']);

        $data['identity'] = $identity;
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['view'] = $this->folder_location[$identity]['view'];
        // $file_js = $this->folder_location[$identity]['javascript'];

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        //Logo Branch
        // $get_branch = $this->Branch_model->get_branch(1);
        // $data['branch'] = array(
        //     'branch_logo' => !empty($get_branch['branch_logo']) ? site_url().$get_branch['branch_logo'] : site_url().'upload/branch/default_logo.png',
        //     'branch_logo_login' => !empty($get_branch['branch_logo']) ? site_url().$get_branch['branch_logo'] : site_url().'upload/branch/default_logo.png',
        //     'branch_logo_sidebar' => !empty($get_branch['branch_logo_sidebar']) ? site_url().$get_branch['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png',                        
        // );  
        $data['branch'] = array(
            'branch_logo' => $this->app_logo,
            'branch_logo_login' => $this->app_logo,
            'branch_logo_sidebar' => $this->app_logo_sidebar          
        );                       
        $this->load->view($data['view'],$data);
    }
    function manage(){
        $session = $this->session->userdata();   
        // $session_branch_id = $session['user_data']['branch']['id'];
        // $session_user_id = $session['user_data']['user_id'];

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';      
        if($this->input->post('action')){
            $next = true;
            $action = $this->input->post('action');
            $post_data = $this->input->post('data');
            $data = json_decode($post_data, TRUE);
            if($action=='register-create'){
                $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'required');
                // $this->form_validation->set_rules('email', 'Email', 'required');          
                $this->form_validation->set_rules('code', 'Kode Telepon Negara', 'required');
                $this->form_validation->set_rules('telepon', 'Nomor WhatsApp', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required');         
                $this->form_validation->set_rules('password2', 'Password Konfirmasi', 'required');         
                $this->form_validation->set_rules('captcha', 'Captcha', 'required');
                $this->form_validation->set_message('required', '{field} wajib diisi');
                if ($this->form_validation->run() == FALSE){
                    $return->message = validation_errors();
                }else{
                    $session            = $this->session->userdata();                    
                    // $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    // $data = json_decode($post_data, TRUE);
                    $post = $this->input->post();

                    $user_code          = $this->random_code(6);
                    $activation_code    = $this->random_code(32);   
                    $user_session       = $this->random_code(20); 
                    $user_otp           = $this->random_number(6);  

                    $phone = str_replace('+','',$post['code']).$post['telepon'];
                    $email = $this->lowercase($post['email']);
                    $full_name = $this->safe($this->sentencecase($post['fullname']));
                    $captcha            = !empty($post['captcha']) ? $post['captcha'] : false;        
                    $captcha_session    = $session['captcha'];                    
                    $generate_username = $this->generate_username($full_name);
                    
                    $params = array(
                        'user_user_group_id' => 2,
                        // 'user_branch_id' => $post['branch'],
                        // 'user_type' => $post['tipe'],
                        'user_fullname' => $full_name,
                        // 'user_place_birth' => $data['tempat_lahir'],
                        // 'user_birth_of_date' => $data['tgl_lahir'],
                        // 'user_gender' => $data['gender'],
                        // 'user_address' => $data['alamat'],
                        'user_phone_1' => $this->safe($phone),
                        'user_email_1' => $email,
                        'user_username' => $generate_username,
                        'user_password' => md5($post['password']),
                        'user_theme' => 'black',
                        'user_date_created' => date("YmdHis"),
                        'user_date_updated' => date("YmdHis"),
                        'user_date_activation' => '0000-00-00 00:00:00',
                        'user_activation' => 0,
                        'user_flag' => 0,
                        'user_code' => $user_code,
                        'user_activation_code' => $activation_code,
                        'user_session' => $user_session,
                        'user_otp' => $user_otp,
                        'user_menu_style' => 0         
                    );
                    // var_dump($params);die;
                    // Captcha check
                    
                    if($captcha == $captcha_session){ //Captcha Valid
                        $return->message='Captcha sesuai dengan gambar';
                    }else{
                        $return->message='Captcha tidak sesuai dengan gambar';
                        $next=false;
                    }

                    //Password Check
                    if($next){
                        if($post['password'] !== $post['password2']){
                            $next=false;
                            $return->message='Password & Konfirmasi Password tidak sama';
                        }
                    }

                    if($next){
                        // $check_exists = $this->User_model->check_data_exist_register($email,$phone);
                        $check_exists = $this->User_model->check_data_exist(array('user_phone_1' => $phone));
                        if($check_exists==false){

                            // Check Data Exist Username
                            $params_check = array(
                                'user_username' => $generate_username             
                            );
                            $check_exists = $this->User_model->check_data_exist($params_check);
                            if($check_exists==false){
                                $set_data=$this->User_model->add_user($params);
                                if($set_data==true){
                                    $user_id = $set_data;

                                    /* Start Activity */
                                        /*
                                        $params = array(
                                            'activity_user_id' => $session_user_id,
                                            'activity_branch_id' => $session_branch_id,                        
                                            'activity_action' => 2,
                                            'activity_table' => 'users',
                                            'activity_table_id' => $set_data,                            
                                            'activity_text_1' => $set_transaction,
                                            'activity_text_2' => $generate_nomor,                        
                                            'activity_date_created' => date('YmdHis'),
                                            'activity_flag' => 1,
                                            'activity_transaction' => $set_transaction,
                                            'activity_type' => 2
                                        );
                                        $this->save_activity($params);
                                        */  
                                    /* End Activity */            

                                    $return->status=1;
                                    $return->message='Berhasil mendaftar';

                                    $get_user = $this->User_model->get_user($user_id);                                 
                                    $return->result= array(
                                        'user_id' => $get_user['user_id'],
                                        'user_email' => $get_user['user_email_1'],
                                        'user_code' => $get_user['user_code'],
                                        'user_activation' => $get_user['user_activation_code'],
                                        'return_url' => site_url($this->folder_location['3']['view2'])
                                    ); 
                                    $this->session->set_flashdata('message',''.$get_user['user_phone_1'].'');
                                    $this->session->set_flashdata('phone',''.$get_user['user_phone_1'].'');                                    
                                    $this->session->set_flashdata('status',1);                                  
                                    // echo json_encode($return);

                                    $this->whatsapp_template('register-and-confirmation-otp',$get_user['user_id']);
                                    // $this->email_template('register-and-confirmation-code',$get_user['user_id']);                                    
                                }  
                            }else{
                                $return->message='Username sudah digunakan';  
                            }

                        }else{
                            $return->message='Sudah Terdaftar';                    
                        }
                    }
                }
            }
            if($action=='password-update'){
                $this->form_validation->set_rules('activation', 'Nama Lengkap', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required');         
                $this->form_validation->set_rules('password2', 'Password Konfirmasi', 'required');         
                $this->form_validation->set_message('required', '{field} wajib diisi');
                if ($this->form_validation->run() == FALSE){
                    $return->message = validation_errors();
                }else{                
                    $next = true;
                    $post = $this->input->post();
                    $code = substr($post['activation'],0,32);
                    
                    if(!empty($post['password'])){
                        if($post['password'] !== $post['password2']){
                            $return->message='Password tidak sama';
                            $next=false;
                        }
                    }

                    if($next){
                        $get_user = $this->User_model->get_user_custom(array('user_activation_code'=>$code));
                        if($get_user){
                            $params = array(
                                'user_activation_code' => $this->random_code(32),
                                'user_password' => md5($post['password']),
                                'user_date_updated' => date("YmdHis")
                            );
                            // var_dump($params,$get_user['user_id']); die;
                            $set_update=$this->User_model->update_user($get_user['user_id'],$params);
                            if($set_update==true){
                                /* Start Activity */
                                /*
                                    $params = array(
                                        'activity_user_id' => $session_user_id,
                                        'activity_branch_id' => $session_branch_id,                        
                                        'activity_action' => 4,
                                        'activity_table' => 'orders',
                                        'activity_table_id' => $id,                            
                                        'activity_text_1' => $set_transaction,
                                        // 'activity_text_2' => $generate_nomor,                        
                                        'activity_date_created' => date('YmdHis'),
                                        'activity_flag' => 1,
                                        'activity_transaction' => $set_transaction,
                                        'activity_type' => 2
                                    );
                                    $this->save_activity($params);
                                */
                                /* End Activity */

                                $this->whatsapp_template('lost-password-success-recovery',$get_user['user_id']);
                                $this->email_template('lost-password-success-recovery',$get_user['user_id']);

                                $return->status=1;
                                $return->message='Berhasil memperbarui Password';
                                $return->return_url = base_url();
                            }                              
                        }else{
                            $return->message='Kode anda tidak valid';
                        }
                    }
                }              
            }            
            if($action=='register-read'){ die;
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE);     
                $data['id'] = $this->input->post('id');           
                $datas = $this->Order_model->get_order($data['id']);
                if($datas==true){
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 3,
                        'activity_table' => 'orders',
                        'activity_table_id' => $data['id'],                            
                        'activity_text_1' => $set_transaction,
                        'activity_text_2' => $datas['order_number'],                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);    
                    /* End Activity */
                }

                $return->status=1;
                $return->message='Success';
                $return->result=$datas;
            }
            if($action=='register-update'){ die;
                $post_data = $this->input->post('data');
                $data = json_decode($post_data, TRUE);
                $id = $data['id'];
                $params = array(
                    // 'order_tipe' => $data['tipe'],
                    // 'order_nomor' => $data['nomor'],
                    // 'order_tgl' => date("YmdHis"),
                    'order_ppn' => $data['ppn'],
                    'order_discount' => $data['diskon'],
                    'order_total' => $data['total'],
                    'order_note' => $data['keterangan'],
                    'order_date_updated' => date("YmdHis"),
                    'order_contact_id' => $data['kontak'],
                    // 'order_flag' => 1
                );
                /*
                if(!empty($data['password'])){
                    $params['password'] = md5($data['password']);
                }
                */
               
                $set_update=$this->Order_model->update_order($id,$params);
                if($set_update==true){
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 4,
                        'activity_table' => 'orders',
                        'activity_table_id' => $id,                            
                        'activity_text_1' => $set_transaction,
                        // 'activity_text_2' => $generate_nomor,                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                }                
            }    
            if($action=='register-delete'){ die;
                $id = $this->input->post('id');
                $number = $this->input->post('number');                               
                // $flag = $this->input->post('flag');
                $flag=4;

                $set_data=$this->Order_model->update_order($id,array('order_flag'=>$flag));
                if($set_data==true){    
                    /* Start Activity */
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,                        
                        'activity_action' => 5,
                        'activity_table' => 'orders',
                        'activity_table_id' => $id,                            
                        'activity_text_1' => $set_transaction,
                        // 'activity_text_2' => $generate_nomor,                        
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_transaction' => $set_transaction,
                        'activity_type' => 2
                    );
                    $this->save_activity($params);    
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil menghapus '.$number;
                }                
            }                     
            if($action=='register-load'){ die;

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


                
                /*
                if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                    $params['other_column'] = $this->input->post('other_column');
                }
                */
                $params_datatable = array(
                    'orders.order_date >' => $this->input->post('date_start').' 00:00:00',
                    'orders.order_date <' => $this->input->post('date_end').' 23:59:59',
                    'orders.order_type' => $identity,
                    'orders.order_flag <' => 4,
                    'orders.order_branch_id' => $session_branch_id                        
                );
                $datas = $this->Order_model->get_all_orders($params_datatable, $search, $limit, $start, $order, $dir);
                // var_dump($datas);die;
                $datas_count = $this->Order_model->get_all_orders_count($params_datatable);
                // $datas_count = $this->Order_model->get_all_orders_count($params_datatable, $search, $limit, $start, $order, $dir);                                 
                
                if(isset($datas)){ //Data exist
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
            }
            if($action=='change-menu-style'){
                $this->form_validation->set_rules('val', 'Value', 'required');        
                $this->form_validation->set_message('required', '{field} wajib diisi');
                if ($this->form_validation->run() == FALSE){
                    $return->message = validation_errors();
                }else{                
                    $next = true;
                    $post = $this->input->post();
                    $value = $post['val'];
                    
                    if($next){
                        $params = array(
                            'user_menu_style' => intval($value)
                        );
                        $set_update=$this->User_model->update_user($session['user_data']['user_id'],$params);
                        if($set_update){
                            /* Start Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 4,
                                'activity_table' => 'orders',
                                'activity_table_id' => $id,                            
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $generate_nomor,                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);
                            */
                            /* End Activity */
                            $this->session->set_userdata('menu_display', intval($value));
                            $return->status=1;
                            $return->message='Berhasil memperbarui';
                            $return->return_url = base_url('admin');
                        } 
                    }
                }              
            }
        }
        if(empty($action)){
            $action='';
        }
        $return->action=$action;
        echo json_encode($return);        
    }

    /* View / HTML*/
    function register_confirmation(){ //When register is success created
        $data['title'] = $this->folder_location['3']['title']; //Register Confirmation
        $this->load->view($this->folder_location['3']['view'],$data);        
    }
    function register_activation($code){ //When user click link in whatsapp/email
        $user_activation_code       = substr($code,0,32);
        $user_session               = substr($code,32,20);
        $user_code                  = substr($code,52,6); 
        $user_otp                   = substr($code,58,6);

        $data['branch'] = array(
            'branch_logo' => $this->app_logo,
            'branch_logo_login' => $this->app_logo,
            'branch_logo_sidebar' => $this->app_logo_sidebar,
        );

        $params_check = array(
            'user_code' => $this->uppercase($this->safe($user_code)),
            'user_activation_code' => $this->uppercase($this->safe($user_activation_code)),
            'user_session' => $this->uppercase($this->safe($user_session)),
            'user_otp' => $this->safe($user_otp),
            'user_activation' => 0
        );
        $check_exists = $this->User_model->check_data_exist($params_check);
        // var_dump($check_exists);die;
        if($check_exists==true){

            $get_user = $this->User_model->get_all_users($params_check,null,null,null,1,0);
            $data['user_id'] = $get_user[0]['user_id'];
            $data['email'] = $get_user[0]['user_email_1'];
            $data['username'] = $get_user[0]['user_username'];
            $data['fullname'] = $get_user[0]['user_fullname'];

            //Update Activation
            $param_update = array(
                'user_flag' => 1,
                'user_activation' => 1,
                'user_date_activation' => date("YmdHis")
            );
            $opr = $this->User_model->update_user($data['user_id'],$param_update);
            if($opr){

                //Set Cookie
                $cookie = array(
                    'name' => site_url(),
                    'value' => $get_user[0]['user_username'],
                    'expire' => strtotime('+3 day'),
                    'path' => '/'                    
                );
                $this->input->set_cookie($cookie);

                $data['title'] = $this->folder_location['5']['title']; //Register Activation
                $this->load->view($this->folder_location['5']['view'],$data);
            }else{
                redirect(base_url());                
            }
        }else{
            // Activation Not Found
            $this->session->set_flashdata('message','Silahkan masuk, akun anda telah aktif');
            $this->session->set_flashdata('status',1);
            $this->session->set_flashdata('return_url',base_url('login?'));
            redirect(base_url());
        }
    }    
    function register_activation_submit(){
        $this->form_validation->set_rules('phone', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('otp', 'Kode OTP', 'required');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('status',2);
            $this->session->set_flashdata('message',validation_errors());
            $this->session->set_flashdata('return_url',$this->app_url.'register_confirmation');            
            $this->session->set_flashdata('phone',$this->input->post('phone'));             

            $data['title'] = $this->folder_location['3']['title']; //Return Register Confirmation
            $this->load->view($this->folder_location['3']['view'],$data);             
        }else{
            $phone  = !empty($this->input->post('phone')) ? $this->input->post('phone') : null;
            $otp    = !empty($this->input->post('otp')) ? $this->input->post('otp') : null;

            $get_user   = [];
            $next       = true;
            $set_status = 0;                        
            $set_phone  = $phone;
            $set_message = 'Failed';
            if(strlen($phone) < 5){
                $set_message = 'Nomor tidak sesuai';
                $next = false;
            }

            if($next){
                if((strlen($otp) > 6) or (strlen($otp) < 6)){
                    $set_message = 'Kode OTP tidak sesuai, silahkan masukkan Kode OTP yg dikirim ke nomor <br><b>'.$phone.'</b>';
                    $next = false;                
                }
            }

            if($next){
                $where = array(
                    'user_phone_1' => $phone,
                    'user_otp' => $otp
                );
                $get_user = $this->User_model->get_user_custom($where);
                if(!empty($get_user)){
                    // var_dump($get_user['user_activation']);
                    if(intval($get_user['user_activation']) == 0){ //User belum aktif
                        $set_url    = $this->app_url.'register/activation/'.$get_user['user_activation_code'].$get_user['user_session'].$get_user['user_code'].$get_user['user_otp'];
                        $set_status = 3;
                        $set_message = 'Berhasil Aktivasi';
                    }else if(!empty($get_user['user_activation']) && (intval($get_user['user_activation']) == 1)){ //User sudah aktif
                        $set_status = 4;
                        $set_message = 'Sudah Aktivasi';
                        $set_url    = $this->app_url.'login';
                    }
                }else{
                    $set_url = $this->app_url.'register_confirmation';
                    $set_status = 2;
                    $set_message = 'Kode OTP / Nomor Salah, Harap memasukkan yang sesuai';
                }
            }else{
                $set_status = 2;
                $set_url = $this->app_url.'register_confirmation';
                $set_message = $set_message;
            }

            // var_dump($set_status,$set_url,$set_message,$set_phone);die;

            // site_url($this->folder_location['3']['view2']);
            $this->session->set_flashdata('status',$set_status);
            $this->session->set_flashdata('return_url',$set_url);            
            $this->session->set_flashdata('message',$set_message);
            $this->session->set_flashdata('phone',$set_phone);             

            $data['title'] = $this->folder_location['3']['title']; //Return Register Confirmation
            $this->load->view($this->folder_location['3']['view'],$data);            
        }  
    }
    function password_reset_form($code){ //When user click link in whatsapp/email for request password change
        $user_activation_code       = substr($code,0,32);
        $user_session               = substr($code,32,20);                        
        // var_dump($this->safe($user_activation_code),',',$user_session.','.$user_code.','.$user_otp);die;
        // $get_branch = $this->Branch_model->get_branch(1);
        $data['branch'] = array(
            'branch_logo' => $this->app_logo,
            'branch_logo_login' => $this->app_logo,
            'branch_logo_sidebar' => $this->app_logo_sidebar,                        
        );           

        $params_check = array(
            'user_activation_code' => $this->safe($user_activation_code),
            'user_session' => $this->safe($user_session),
            'user_activation' => 1
        );

        $check_exists = $this->User_model->check_data_exist($params_check);
        if($check_exists==true){

            $get_user = $this->User_model->get_user_custom($params_check);
            $data['user_id'] = $get_user['user_id'];
            $data['email'] = $get_user['user_email_1'];
            $data['username'] = $get_user['user_username'];
            $data['fullname'] = $get_user['user_fullname'];

            //Update Activation
            // $param_update = array(
            //     'user_flag' => 1,
            //     'user_activation' => 1,
            //     'user_date_activation' => date("YmdHis")
            // );
            // $opr = $this->User_model->update_user($data['user_id'],$param_update);
            $opr = true;
            if($opr){
                $data['title'] = $this->folder_location['4']['title']; //Reset Password
                $this->session->set_flashdata('message','Silahkan masukkan password baru anda, gunakan password yang kuat');
                $this->session->set_flashdata('status',1);                   
                $this->session->set_flashdata('activation_session',$get_user['user_activation_code'].$get_user['user_session']);                                                   
                $this->load->view($this->folder_location['4']['view'],$data);
            }else{
                redirect(base_url());                
            }
        }else{
            // Activation Not Found
            redirect(base_url());
        }
    }
    function password_sent(){
        $session = $this->session->userdata();      
        // $get_branch = $this->Branch_model->get_branch(1);
        // $data['branch'] = array(
        //     'branch_logo' => site_url().$get_branch['branch_logo'],
        //     'branch_logo_login' => site_url().$get_branch['branch_logo'],
        //     'branch_logo_sidebar' => site_url().$get_branch['branch_logo_sidebar'],                        
        // );          
        $data['branch'] = array(
            'branch_logo' => $this->app_logo,
            'branch_logo_login' => $this->app_logo,
            'branch_logo_sidebar' => $this->app_logo_sidebar          
        );        
        $data['email'] = !empty($session['email_recovery']) ? $session['email_recovery'] : '';
        $data['title'] = $this->folder_location['6']['title'];
        $this->load->view($this->folder_location['6']['view'],$data);
    }

    /* Session */
    function authentication(){ //Post From Login
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        if ($this->form_validation->run() == FALSE){
            $return->message = validation_errors();
        }else{
            $url_before = $this->input->post('url');
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $where = array(
                'user_password' => md5($password),
                'user_flag' => 1
            );

            //Username / Email / Phone (Login)
            $where['user_username'] = $username;
            // $where['user_email_1'] = $username;
            // $where['user_phone_1'] = $username;
            // var_dump($where);die;
            
            $cek = $this->Login_model->cek_login("users",$where)->num_rows();
            if($cek > 0){
                $user_info = $this->Login_model->get_login_info($username);
                if(intval($user_info['user_activation']) == 1){
                    if(intval($user_info['branch_flag']) == 1){
                        $update_user_last_login = $this->User_model->update_user($user_info['user_id'],array('user_date_last_login'=>date("YmdHis")));
                        $user_group = $this->Login_model->get_group_info($user_info['user_user_group_id']); 
                        
                        // Prepare Menu & Submenu by Session
                        $menu_group = $this->Login_model->get_menu_group_by_user_menu($user_info['user_id']);
                        $menus = array();
                        foreach ($menu_group as $r) {
                            if(intval($r['menu_active_count']) > 0){
                                $menus[] = array(
                                    'menu_group_id' => $r['menu_id'],
                                    'menu_group_name' => $r['menu_name'],     
                                    'menu_group_icon' => $r['menu_icon'],
                                    'menu_group_link' => $r['menu_link'],
                                    'menu_group_sorting' => $r['menu_sorting'],
                                    'menu_group_flag' => $r['menu_flag'],
                                    'sub_menu' => $this->Login_model->get_menu_child_by_user_menu($r['menu_id'],$user_info['user_id'])             
                                );
                            }
                        }    
                        $menu_result = $menus;
                        
                        //Session Directory
                        $session_directory = site_url('admin');
                        $session_array=array(
                            'user_directory' => $session_directory,
                            'user_id' => $user_info['user_id'],
                            'user_session' => $user_info['user_session'],
                            'user_name' => $username,
                            'user_fullname' => $user_info['user_fullname'],
                            'user_phone' => $user_info['user_phone_1'],
                            'user_email' => $user_info['user_email_1'],       
                            'user_group' => !empty($user_group['user_group_name']) ? $user_group['user_group_name'] : '-',
                            'user_group_id' => !empty($user_group['user_group_id']) ? intval($user_group['user_group_id']) : 0,
                            'user_type' => $user_info['user_type'],
                            'user_code' => $user_info['user_code'],                
                            'user_activation_code' => $user_info['user_activation_code'],    
                            'user_check_price_buy' => intval($user_info['user_check_price_buy']),
                            'user_check_price_sell' => intval($user_info['user_check_price_sell']),            
                            'last_time' => date('Ymdhis'),
                            'branch' => array(
                                'id' => $user_info['branch_id'],
                                'code' => $user_info['branch_code'],
                                'name' => $user_info['branch_name'],
                                'address' => $user_info['branch_address'],
                                'phone_1' => $user_info['branch_phone_1'],
                                'phone_2' => $user_info['branch_phone_2'],
                                'email_1' => $user_info['branch_email_1'],
                                'email_2' => $user_info['branch_email_2'],
                                // 'specialist' => $user_info['branch_company_specialist'],
                                'slogan' => $user_info['branch_slogan'],                                        
                                'flag' => $user_info['branch_flag'],
                                'branch_logo' => $user_info['branch_logo'],
                                'branch_logo_sidebar' => $user_info['branch_logo_sidebar'],
                                'branch_location_id' => $user_info['branch_location_id']                                              
                            ),
                            'package' => array(
                                'packege_id' => $this->package_id,
                                'package_name' => $this->package_name,
                                'package_date_start' => date('Y-m-d h:i:s'),
                                'package_date_end' => date('Y-m-d h:i:s'),
                                'item' => $this->App_package_model->get_app_package_item($this->package_id)
                            ),
                            'menu_access' => $menu_result
                        );

                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $user_info['user_id'],
                            'activity_branch_id' => $user_info['branch_id'],                
                            'activity_action' => 1,
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_remote_addr' => $_SERVER['REMOTE_ADDR'],
                            'activity_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                            'activity_http_referer' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null
                        );
                        $this->save_activity($params);
                        
                        $set_user_name = $user_info['user_username'];
                        $set_user_mail = $user_info['user_email_1'];

                        //Set Cookie
                        $cookie = array(
                            'name' => site_url(),
                            'value' => $username,
                            'expire' => strtotime('+3 day'),
                            'path' => '/'                    
                        );
                        $this->input->set_cookie($cookie);

                        $this->session->set_userdata('logged_in',true);            
                        $this->session->set_userdata('user_data',$session_array);
                        if($user_info['user_id'] == 1){
                            $this->session->set_userdata('root',true);                                    
                        }else{
                            $this->session->set_userdata('root',false);                                                            
                        }
                        $this->session->set_userdata('menu_display',intval($user_info['user_menu_style'])); 

                        if(!empty($url_before)){
                            $return_url = $url_before;
                            $login_message = 'Akses Diberikan';
                        }else{
                            $return_url = $session_directory;
                            $login_message = 'Akses Diterima';
                        }
                        
                        //Cek User Have a Branch
                        // $user_has_branch = !empty($user_info['user_branch_id']) ? $user_info['user_branch_id'] : 0;
                        // if(intval($user_has_branch) == 0){
                        //     $return_url = $return_url.'/setup';
                        // }
                        if($user_info['user_user_group_id'] == 9){ // Cashier
                            $return_url = site_url('pos');
                        }
                        if($user_info['user_user_group_id'] == 1){
                            $this->session->set_flashdata('switch_branch',1);                                
                        }                        
                        $this->whatsapp_template('login-activity',$user_info['user_id']);

                        $return->status=1;
                        $return->message = $login_message;
                        $return->result = array(
                            'session' => $session_array,
                            'return_url' => $return_url
                        );
                    }else{
                        $user_has_branch = $user_info['user_branch_id'];
                        if($user_has_branch == null){
                            $return->status = 2;
                            $return->message = 'Perusahaan belum di konfigurasi';   
                            $return->result = array(
                                'return_url' => site_url('setup')
                            );  
                            $this->session->set_flashdata('user_session',$user_info['user_session']);
                            $this->session->set_flashdata('user_username',$user_info['user_username']);                                
                            $this->session->set_flashdata('user_id',$user_info['user_id']);  
                            $this->session->set_flashdata('user_phone',$user_info['user_phone_1']);  
                            $this->session->set_flashdata('user_email',$user_info['user_email_1']);  

                            $this->session->set_flashdata('message',$return->message);
                            $this->session->set_flashdata('status',1);
                        }else{
                            $return->message='Perusahaan/Cabang tidak dapat diakses';
                        }
                    }
                }else{
                    $return->message='User anda belum diaktifkan, Silahkan cek email';
                }
            }else{
                $return->message = 'Wrong User Pass or Nonactive User';
            }
        }
        echo json_encode($return);       
    }
    function authentication_switch(){ //Post From Index With User Joe Only
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $url_before = $this->input->post('url');
        $user_id = $this->input->post('user_id');
        // $username = $this->input->post('username');
        // $password = $this->input->post('password');

        $session = $this->session->userdata();
        $session_user_id = intval($session['user_data']['user_id']);
        $session_root = intval($session['root']);

        if($session_root == true){
            $where = array(
                'user_id' => $user_id,
            );
            $cek = $this->Login_model->cek_login("users",$where)->num_rows();
            if($cek > 0){
                $user_info = $this->Login_model->get_login_info_switch_user($user_id);
                // var_dump($user_info);die;
                $username = $user_info['user_username'];
                if(intval($user_info['user_activation']) == 1){
                    if(intval($user_info['branch_flag']) == 1){
                        // $update_user_last_login = $this->User_model->update_user($user_info['user_id'],array('user_date_last_login'=>date("YmdHis")));
                        $user_group = $this->Login_model->get_group_info($user_info['user_user_group_id']); 
                        
                        // Prepare Menu & Submenu by Session
                        $menu_group = $this->Login_model->get_menu_group_by_user_menu($user_info['user_id']);
                        $menus = array();
                        // var_dump($menu_group);die;
                        foreach ($menu_group as $r) {
                            if(intval($r['menu_active_count']) > 0){
                                $menus[] = array(
                                    'menu_group_id' => $r['menu_id'],
                                    'menu_group_name' => $r['menu_name'],     
                                    'menu_group_icon' => $r['menu_icon'],
                                    'menu_group_link' => $r['menu_link'],
                                    'menu_group_sorting' => $r['menu_sorting'],                                                
                                    'menu_group_flag' => $r['menu_flag'],                                                                                
                                    'sub_menu' => $this->Login_model->get_menu_child_by_user_menu($r['menu_id'],$user_info['user_id'])             
                                );                        
                            }
                        }    
                        $menu_result = $menus;
                        
                        //Session Directory
                        $session_directory = site_url('admin');
                        $session_array=array(
                            'user_directory' => $session_directory,
                            'user_id' => $user_info['user_id'],
                            'user_name' => $username,
                            'user_fullname' => $user_info['user_fullname'],
                            'user_phone' => $user_info['user_phone_1'],
                            'user_email' => $user_info['user_email_1'],       
                            'user_group' => !empty($user_group['user_group_name']) ? $user_group['user_group_name'] : '-',
                            'user_group_id' => !empty($user_group['user_group_id']) ? intval($user_group['user_group_id']) : 0,
                            'user_type' => $user_info['user_type'],
                            'user_code' => $user_info['user_code'],                
                            'user_activation_code' => $user_info['user_activation_code'],    
                            'user_check_price_buy' => intval($user_info['user_check_price_buy']),
                            'user_check_price_sell' => intval($user_info['user_check_price_sell']),                                                
                            'last_time' => date('Ymdhis'),
                            'branch' => array(
                                'id' => $user_info['branch_id'],
                                'code' => $user_info['branch_code'],
                                'name' => $user_info['branch_name'],
                                'address' => $user_info['branch_address'],
                                'phone_1' => $user_info['branch_phone_1'],
                                'phone_2' => $user_info['branch_phone_2'],
                                'email_1' => $user_info['branch_email_1'],
                                'email_2' => $user_info['branch_email_2'],
                                // 'specialist' => $user_info['branch_company_specialist'],
                                'slogan' => $user_info['branch_slogan'],                                        
                                'flag' => $user_info['branch_flag'],
                                'branch_logo' => $user_info['branch_logo'],
                                'branch_logo_sidebar' => $user_info['branch_logo_sidebar'],
                                'branch_location_id' => $user_info['branch_location_id']                                                
                            ),
                            'package' => array(
                                'packege_id' => $this->package_id,
                                'package_name' => $this->package_name,
                                'package_date_start' => date('Y-m-d h:i:s'),
                                'package_date_end' => date('Y-m-d h:i:s'),
                                'item' => $this->App_package_model->get_app_package_item($this->package_id)
                            ),
                            'menu_access' => $menu_result
                        );

                        //Aktivitas
                        // $params = array(
                        //     'activity_user_id' => $user_info['user_id'],
                        //     'activity_branch_id' => $user_info['branch_id'],                
                        //     'activity_action' => 1,
                        //     'activity_date_created' => date('YmdHis'),
                        //     'activity_flag' => 1,
                        //     'activity_remote_addr' => $_SERVER['REMOTE_ADDR'],
                        //     'activity_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        //     'activity_http_referer' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null
                        // );
                        // $this->save_activity($params);
                        
                        $set_user_name = $user_info['user_username'];
                        $set_user_mail = $user_info['user_email_1'];

                        //Set Cookie
                        $cookie = array(
                            'name' => site_url(),
                            'value' => $username,
                            'expire' => strtotime('+3 day'),
                            'path' => '/'                    
                        );
                        $this->input->set_cookie($cookie);

                        $this->session->set_userdata('logged_in',true);
                        $this->session->set_userdata('root',true);                                    
                        $this->session->set_userdata('user_data',$session_array);
                        
                        if(!empty($url_before)){
                            $return_url = $url_before;
                            $login_message = 'Akses Diberikan';
                        }else{
                            $return_url = $session_directory;
                            $login_message = 'Akses Diterima';
                        }
                        $this->session->set_userdata('menu_display',intval($user_info['user_menu_style'])); 

                        //Cek User Have a Branch
                        $user_has_branch = !empty($user_info['user_branch_id']) ? $user_info['user_branch_id'] : 0;
                        if(intval($user_has_branch) == 0){
                            $return_url = $return_url.'/welcome';
                        }

                        if($user_info['user_user_group_id'] == 9){ // Cashier
                            $return_url = site_url('pos');
                        }
                        
                        $return->status=1;
                        $return->message = $login_message;
                        $return->result = array(
                            'session' => $session_array,
                            'return_url' => $return_url
                        );
                    }else{
                        $return->message='Perusahaan/Cabang tidak dapat diakses';
                    }
                }else{
                    $return->message='User anda belum diaktifkan, Silahkan cek email';
                }
            }else{
                $return->message = 'User not found';
            }
        }else{
            $return->message = 'Access denied!';            
        }
        echo json_encode($return);       
    }
    function authentication_switch_branch(){ //Post From Index With User Group 1,2,3,4 Only
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $url_before = $this->input->post('url');
        $branch_id = $this->input->post('branch_id');
        // $username = $this->input->post('username');
        // $password = $this->input->post('password');

        $session = $this->session->userdata();
        $session_user_id = intval($session['user_data']['user_id']);
        $session_root = intval($session['root']);

        $where = array(
            'user_id' => $session_user_id,
        );
        $cek = $this->Login_model->cek_login("users",$where)->num_rows();
        if($cek > 0){
            $user_info = $this->Login_model->get_login_info_switch_user($session_user_id);
            // var_dump($user_info);die;
            $username = $user_info['user_username'];
            if(intval($user_info['user_activation']) == 1){
                if(intval($user_info['branch_flag']) == 1){
                    // $update_user_last_login = $this->User_model->update_user($user_info['user_id'],array('user_date_last_login'=>date("YmdHis")));
                    $user_group = $this->Login_model->get_group_info($user_info['user_user_group_id']); 
                    
                    // Prepare Menu & Submenu by Session
                    $menu_group = $this->Login_model->get_menu_group_by_user_menu($user_info['user_id']);
                    $menus = array();
                    foreach ($menu_group as $r) {
                        if(intval($r['menu_active_count']) > 0){
                            $menus[] = array(
                                'menu_group_id' => $r['menu_id'],
                                'menu_group_name' => $r['menu_name'],     
                                'menu_group_icon' => $r['menu_icon'],
                                'menu_group_link' => $r['menu_link'],
                                'menu_group_sorting' => $r['menu_sorting'],                                                
                                'menu_group_flag' => $r['menu_flag'],                                                                                
                                'sub_menu' => $this->Login_model->get_menu_child_by_user_menu($r['menu_id'],$user_info['user_id'])             
                            );                        
                        }
                    }    
                    $menu_result = $menus;
                    
                    $branch_info = $this->Branch_model->get_branch($branch_id);

                    //Session Directory
                    $session_directory = site_url('admin');
                    $session_array=array(
                        'user_directory' => $session_directory,
                        'user_id' => $user_info['user_id'],
                        'user_session' => $user_info['user_session'],
                        'user_name' => $username,
                        'user_fullname' => $user_info['user_fullname'],
                        'user_phone' => $user_info['user_phone_1'],
                        'user_email' => $user_info['user_email_1'],       
                        'user_group' => !empty($user_group['user_group_name']) ? $user_group['user_group_name'] : '-',
                        'user_group_id' => !empty($user_group['user_group_id']) ? intval($user_group['user_group_id']) : 0,
                        'user_type' => $user_info['user_type'],
                        'user_code' => $user_info['user_code'],                
                        'user_activation_code' => $user_info['user_activation_code'],                
                        'last_time' => date('Ymdhis'),
                        'user_check_price_buy' => intval($user_info['user_check_price_buy']),
                        'user_check_price_sell' => intval($user_info['user_check_price_sell']),                                
                        'branch' => array(
                            'id' => $branch_info['branch_id'],
                            'code' => $branch_info['branch_code'],
                            'name' => $branch_info['branch_name'],
                            'address' => $branch_info['branch_address'],
                            'phone_1' => $branch_info['branch_phone_1'],
                            'phone_2' => $branch_info['branch_phone_2'],
                            'email_1' => $branch_info['branch_email_1'],
                            'email_2' => $branch_info['branch_email_2'],
                            // 'specialist' => $user_info['branch_company_specialist'],
                            'slogan' => $branch_info['branch_slogan'],                                        
                            'flag' => $branch_info['branch_flag'],
                            'branch_logo' => $branch_info['branch_logo'],
                            'branch_logo_sidebar' => $branch_info['branch_logo_sidebar'],  
                            'branch_location_id' => $branch_info['branch_location_id']                                                                                       
                        ),
                        'package' => array(
                            'packege_id' => 1,
                            'package_name' => 'Business Plan',
                            'package_date_start' => date('Y-m-d h:i:s'),
                            'package_date_end' => date('Y-m-d h:i:s')
                        ),
                        'menu_access' => $menu_result
                    );

                    //Aktivitas
                    $params = array(
                        'activity_user_id' => $user_info['user_id'],
                        'activity_branch_id' => $branch_info['branch_id'],                
                        'activity_action' => 1,
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 1,
                        'activity_remote_addr' => $_SERVER['REMOTE_ADDR'],
                        'activity_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        'activity_http_referer' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null
                    );
                    $this->save_activity($params);
                    
                    $set_user_name = $user_info['user_username'];
                    $set_user_mail = $user_info['user_email_1'];

                    //Set Cookie
                    $cookie = array(
                        'name' => site_url(),
                        'value' => $username,
                        'expire' => strtotime('+3 day'),
                        'path' => '/'                    
                    );
                    $this->input->set_cookie($cookie);

                    $this->session->set_userdata('logged_in',true);
                    $this->session->set_userdata('root',true);                                    
                    $this->session->set_userdata('user_data',$session_array);
                    
                    if(!empty($url_before)){
                        $return_url = $url_before;
                        $login_message = 'Akses Diberikan';
                    }else{
                        $return_url = $session_directory;
                        $login_message = 'Akses Diterima';
                    }
                    $this->session->set_userdata('menu_display',intval($user_info['user_menu_style'])); 
                    $this->session->set_flashdata('switch_branch',0);

                    //Cek User Have a Branch
                    $user_has_branch = !empty($user_info['user_branch_id']) ? $user_info['user_branch_id'] : 0;
                    if(intval($user_has_branch) == 0){
                        $return_url = $return_url.'/welcome';
                    }

                    if($user_info['user_user_group_id'] == 9){ // Cashier
                        $return_url = site_url('sales/pos');
                    }
                    
                    $return->status=1;
                    $return->message = $login_message;
                    $return->result = array(
                        'session' => $session_array,
                        'return_url' => $return_url
                    );
                }else{
                    $return->message='Perusahaan/Cabang tidak dapat diakses';
                }
            }else{
                $return->message='User anda belum diaktifkan, Silahkan cek email';
            }
        }else{
            $return->message = 'User not found';
        }
        echo json_encode($return);       
    }    
    function logout(){
        $this->session->sess_destroy();
        redirect('login');
    }
    function return_url(){
        return $this->load->view('layouts/admin/login/login');        
    }
    function remove_cookie(){
        $data['session'] = $this->session->userdata();             
        $this->session->sess_destroy();

        $cookie_name   = site_url();
        $get_cookie    = get_cookie($cookie_name);
        $delete_cookie = delete_cookie(site_url());
        if($delete_cookie){ 
            redirect('login'); 
        }else{
            redirect('login'); 
        }    
    }
    function session(){
        $data['session'] = $this->session->userdata();
        // if(!$this->is_logged_in()){
            // redirect(base_url("login"));
        // }else{
            echo json_encode(array('session'=>$data['session']));
        // }
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
    function random_code($length){
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[rand(0, $txtlen)];}
        return $result;
    }

    public function get_email_config(){
        $return = array(
            'protocol' => $this->config->item('protocol'),
            'smtp_auth' => $this->config->item('smtp_auth'),
            'smtp_host' => $this->config->item('smtp_host'),
            'smtp_port' => $this->config->item('smtp_port'),
            'smtp_user' => $this->config->item('smtp_user'),
            'smtp_pass' => $this->config->item('smtp_pass'),
            'mail_set_from' => $this->config->item('mail_set_from'),
            'mail_set_reply_to' => $this->config->item('mail_set_reply_to'),
            'mail_set_from_alias' => $this->config->item('mail_set_from_alias'),
            'smtp_crypto' => $this->config->item('smtp_crypto'),
            'mailtype' => $this->config->item('mailtype'),
            'smtp_timeout' => $this->config->item('smtp_timeout'),
            'charset' => $this->config->item('charset'),
            'wordwrap' => $this->config->item('wordwrap')
        );
        return $return;
    }
    public function get_firebase_config(){
        $return = array(
            'apiKey' => $this->config->item('apiKey'),
            'authDomain' => $this->config->item('authDomain'),
            'databaseURL' => $this->config->item('databaseURL'),
            'projectId' => $this->config->item('projectId'),
            'storageBucket' => $this->config->item('storeageBucket'),
            'messagingSenderId' => $this->config->item('messageSenderId'),
            'appId' => $this->config->item('appId'),
            'measurementId' => $this->config->item('measurementId'),
        );
        return $return;
    }

    /* Process*/
    function tes(){ die;
        $params = array(
            'user_fullname' => 'Calvine',
            'user_activation_code' => '13BSD',
            'user_code' => 'CODE13',
            'user_email_1' => 'joceline.putra@gmail.com',
        );
        $this->email_register_confirmation();
    }
    function email_register_confirmation(){ die; //Works phpmailer
        $this->load->model('User_model');

        $user_activation_code = $this->input->post('activation_code');
        
        // var_dump($this->session());die;     
        // $result = $menu_group;        
        // var_dump($data['menu_by_session']);die;
        // $result = $data['menu_by_session'];
        // $group = $data['menu_group_by_session'];
        // foreach ($data['menu_group_by_session'] as $key => $value) {
            // $dataku = $this->Login_model->get_menu_by_session($value['menu_group_id'],1);
        // }
        // $result = array($group => $dataku);
        // $this->output->set_content_type('application/json');
        // $this->output->set_output(json_encode($result));

        $get_user = $this->User_model->get_user_custom(array('user_activation_code'=>$user_activation_code));
        $data['email'] = $get_user['user_email_1'];
        $data['title'] = 'Register Confirmation';
        $params = $get_user;
        $this->email_send_register_confirmation($params);
        $this->load->view('layouts/admin/register/confirmation',$data);
    }    
    function email_send_register_confirmation($params){ die; // Works; phpmailer 

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        
        $app_name       = $this->app_name;
        $app_link       = $this->app_url.'register/activation/'.$params['user_activation_code'].'/'.$params['user_code'];
        $app_logo       = $this->app_logo;

        $user_name      = $params['user_fullname'];

        $to_address   = $params['user_email_1'];
        $to_subject   = "Aktivasi Akun Anda";
        $to_content   = "";

        $txt = "
        <div style='padding:25px;background-color:#f2f2f2;'>
            <div style='padding:10px;background-color:white;'>
                <div>
                    <p>
                        <img src='#' style='margin:5px 0;width:190px;'>
                    </p>
                    <p>
                        Halo <b>".$user_name."</b><br>
                        Selamat datang di ".$app_name.". 
                    </p>     
                    <p>
                        Terimakasih telah mendaftar di platform ".$app_name.", selangkah lagi untuk memastikan email ini benar milik anda. <br>Silahkan klik link di <a href=".$app_link.">sini</a>.<br><br> Jika tidak dapat di klik, anda bisa mengklik tautan dibawah ini.<br>
                        <a href='".$app_link."'>".$app_link."</a>    
                    </p>
                </div>
            </div>
        </div>";    
        $to_content = $txt;    

        $result = $this->phpmailer_lib->sendMailSMTP($to_address, $to_subject, $to_content);
        if(intval($result['status']) === 1){
            $return->status  = 1;
            $return->message = $result['message'];
        }else{
            $return->message = $result['message'];
        }        
        return $return;
    }
    function process_sent_lost_password(){ //Works WhatsApp, Email phpmailer
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';    
        $return->return_url = site_url('password');          
        
        $next = true;
        $post = $this->input->post();
        $get  = $this->input->get();

        // $this->form_validation->set_rules('email', 'Email / Telepon', 'required');
        // $this->form_validation->set_rules('whatsapp', 'WhatsApp', 'required');        
        $this->form_validation->set_rules('captcha', 'Captcha', 'required');
        $this->form_validation->set_message('required', '{field} wajib diisi');
        if ($this->form_validation->run() == FALSE){
            $return->message = validation_errors();
        }else{        
            $session            = $this->session->userdata();
            $via                = !empty($post['via']) ? $post['via'] : false;
            $whatsapp           = !empty($post['whatsapp']) ? $post['whatsapp'] : false;
            $email              = !empty($post['email']) ? $post['email'] : false;
            $captcha            = !empty($post['captcha']) ? $post['captcha'] : false;        
            $captcha_session    = $session['captcha'];

            //Captcha check
            if($next){
                if($captcha == $captcha_session){ //Captcha Valid
                    $return->message='Captcha sesuai dengan gambar';
                }else{
                    $return->message='Captcha tidak sesuai dengan gambar';
                    $next=false;
                }
            }

            switch($via){
                case "EM": 
                    $via_status = 'Email';
                    $next = true;
                    break;
                case "WA":
                    $via_status = 'WhatsApp';
                    $next = true; 
                    break;
                case "TL":
                    $via_status = 'Telegram';
                    $next = false; 
                    break;
                default: 
                    $next = false;
                    break;
            }

            //Email Check
            if($next){
                if($next == false){
                    $return->message = 'Harap masukkan "'.$via_status.'" anda';
                    $next=false;
                }else{
                    
                    $check_is_numeric = is_numeric($post['email']);
                    $is_email = false;
                    $is_phone = false;

                    if(!$check_is_numeric){
                        $number = str_replace('+','',$post['code']).$post['whatsapp'];
                        $where = array(
                            'user_phone_1' => $this->safe($number),
                            'user_flag' => 1
                        );
                        $is_phone = true;
                    }else{
                        $where = array(
                            'user_email_1' => $this->safe($post['email'])
                        );  
                        $is_email = true;
                    }
                    $get_user = $this->User_model->get_user_custom($where);
                    if($get_user){
                        
                        // $is_email = true;
                        // $is_phone = false;
                        if($is_email){
                            $this->session->set_userdata('email_recovery',$get_user['user_email_1']);

                            $app_name       = $this->app_name;
                            $app_link       = $this->app_url.'password/recovery/'.$get_user['user_activation_code'].'/'.$get_user['user_session'];
                            $app_logo       = $this->app_logo;
                            $user_name      = $get_user['user_fullname'];

                            $to_address        = $get_user['user_email_1'];
                            $to_subject   = "Permintaan Perubahan Password";
                            $to_content   = "";
                            $txt = "
                            <div style='padding:25px;background-color:#f2f2f2;'>
                                <div style='padding:10px;background-color:white;'>
                                    <div>
                                        <p>
                                        </p>
                                        <p>
                                            Halo <b>".$user_name."</b><br>
                                            Anda baru saja melakukan permintaan perubahan password di ".$app_name.". 
                                        </p>     
                                        <p>
                                            Jika memang anda, silahkan ikuti petunjuk perubahan password di ".$app_name.", dengan cara. <br>Silahkan klik link di <a href=".$app_link.">sini</a>.<br><br> Jika tidak dapat di klik, anda bisa mengklik tautan dibawah ini.<br>
                                            <a href='".$app_link."'>".$app_link."</a>    
                                        </p>
                                        <p>
                                            Abaikan permintaan ini jika anda tidak merasa melakukan permintaan perubahan password
                                        </p>                                    
                                    </div>
                                </div>
                            </div>";    
                            $to_content = $txt;    

                            $result = $this->phpmailer_lib->sendMailSMTP($to_address, $to_subject, $to_content);
                            if(intval($result['status']) === 1){
                                $return->status=1;
                                $return->message='Email berhasil dikirim';                                        
                                $return->return_url = site_url('password_sent');

                                $this->session->set_flashdata('message','Silahkan cek Inbox / Spam yang dikirim ke <b style="color:#5ac736;">'.$get_user['user_email_1'].'</b>, untuk panduan pemulihan password anda');
                                $this->session->set_flashdata('status',1);          
                            }else{
                                $this->session->set_flashdata('message','Gagal mengirim pesan');
                                $this->session->set_flashdata('status',0);
                            }    
                                             
                        }

                        if($is_phone){
                            $return->status=1;
                            $return->message = 'Berhasil mengirim pesan';
                            $return->return_url = site_url('password_sent');

                            $response = $this->whatsapp_template('lost-password',$get_user['user_id']);
                            
                            $this->session->set_flashdata('message','Silahkan cek WhatsApp <b style="color:#5ac736;">'.$get_user['user_phone_1'].'</b>, untuk panduan pemulihan password anda');
                            $this->session->set_flashdata('status',1);
                        }
                    }else{
                        $return->status=0;
                        $return->message= $via_status.' belum terdaftar / di nonaktifkan';
                        $next=false;                
                    }
                }
            }
        }
        echo json_encode($return);
    }

    /* Other */
    function whatsapp_send_group($message_group_session){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';
        
        if(strlen($message_group_session) > 0){
        
            $datas = array();
            $where = array(
                'message_group_session' => $message_group_session
            );
            $get_data=$this->Message_model->get_message_custom_result($where);
            if(count($get_data) > 0){
                foreach($get_data as $v){

                    // Fetch Data
                    $datas[] = array(
                        'message_group_session' => $v['message_group_session'],
                        'message_id' => $v['message_id'],
                        'message_session' => $v['message_session'],
                        'message_text' => $v['message_text'],
                        'message_contact_number' => $v['message_contact_number'],
                        'message_url' => $v['message_url']
                    );

                    // WhatsApp Config
                    $whatsapp_vendor = $this->config->item('whatsapp_vendor');
                    $whatsapp_server = $this->config->item('whatsapp_server');
                    $whatsapp_token  = $this->config->item('whatsapp_token');
            
                    $whatsapp_auth  = $this->config->item('whatsapp_auth');
                    $whatsapp_sender  = $this->config->item('whatsapp_sender');        
                    $whatsapp_action_send  = $this->config->item('whatsapp_action')['send-message'];  
                    
                    // Curl Prepare
                    $curl_link = $whatsapp_server.$whatsapp_action_send;
                    $curl_link .= '&sender='.$whatsapp_sender;
                    $curl_link .= '&recipient='.$v['message_contact_number'];
                    $curl_link .= '&content='.rawurlencode($v['message_text']); 

                    // Message has a caption
                    if(strlen($v['message_url']) > 0){
                        $curl_link .= '&file='.$v['message_url'];
                    }

                    // var_dump($curl_link);die;
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $curl_link,
                        CURLOPT_HEADER => 0,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST =>  1,
                        CURLOPT_POSTFIELDS => 1,
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                
                    // Result Response
                    $get_response = json_decode($response,true);
                    if($get_response['result'] !== false){

                        $where = array(
                            'message_id' => $v['message_id']
                        );
                        $params = array(
                            'message_date_sent' => date('YmdHis'),
                            'message_flag' => 1
                        );
                        $this->Message_model->update_message_custom($where,$params);
                        $return->result  = $get_response;
                        $return->status  = $get_response['status']; // 1 
                        $return->message = $get_response['message']; // Berhasil
                    }
                }
            }else{ 
                $return->message='Session not found';                
            }
        }else{
            $return->message='Session not found';
        }
        return json_encode($return);
    }
    function whatsapp_template($action, $user_id){
        $next = true;
        $get_branch = $this->Branch_model->get_branch(1);
        $this->app_name = $get_branch['branch_name'];

        switch($action){
            case "register-and-confirmation-code": die;
                $get_user = $this->User_model->get_user($user_id);

                // $route['register/activation/(:any)/(:any)'] = "login/register_activation/$1/$2"; //http://localhost:8888/git/jrn/register/activation/W82A86WXSJTUYGC2ER7NNP2PAG8SDEPZ/PSR6F7

                $text     = '🔐 *Aktivasi Akun*'."\r\n\r\n";
                $text    .= 'Hai, Selamat datang di platform *'.$this->app_name.'*'."\r\n";
                $text    .= 'Nomor atau Email anda telah di daftarkan, mohon ikuti petunjuk dibawah ini'."\r\n\r\n";
                $text    .= 'Silahkan klik link dibawah ini untuk konfirmasi pendaftaran anda di platform ini.'."\r\n\r\n";
                $text    .= $this->app_url.'register/activation/'.$get_user['user_activation_code'].$get_user['user_session'].$get_user['user_code'].$get_user['user_otp']."\r\n\r\n";
                // $text    .= "Kode OTP:"."\r\n";
                // $text    .= "*".$get_user['user_otp']."*"."\r\n\r\n";
                // $text    .= "Access From:"."\r\n";
                // $text    .= $this->user_agent()."\r\n\r\n";
                $text    .= "📌 Abaikan pesan ini jika tidak merasa mendaftar";
                // $text = rawurlencode($text);
                break;
            case "register-and-confirmation-otp":
                $get_user = $this->User_model->get_user($user_id);

                // $route['register/activation/(:any)/(:any)'] = "login/register_activation/$1/$2"; //http://localhost:8888/git/jrn/register/activation/W82A86WXSJTUYGC2ER7NNP2PAG8SDEPZ/PSR6F7

                $text     = '🔐 *Aktivasi Akun*'."\r\n\r\n";
                $text    .= 'Hai, Selamat datang di platform *'.$this->app_name.'*, Nomor atau Email anda telah di daftarkan, mohon masukkan kode dibawah ini'."\r\n\r\n";
                $text    .= "Kode OTP:"."\r\n";
                $text    .= "*".$get_user['user_otp']."*"."\r\n\r\n";
                $text    .= 'Masukkan kode diatas pada halaman pendaftaran'."\r\n\r\n";
                // $text    .= "Access From:"."\r\n";
                // $text    .= $this->user_agent()."\r\n\r\n";
                $text    .= "📌 Abaikan pesan ini jika tidak merasa mendaftar";
                // $text = rawurlencode($text);
                break;
            case "request-otp":
                $random = '';
                $get_user = $this->User_model->get_user($user_id);
                if($get_user){
                    $random = $this->random_number(6);
                    $this->User_model->update_user($get_user['user_id'],array('user_otp'=> $random));
                }
                $text     = '🗝️ *Kode OTP*'."\r\n\r\n";
                $text    .= "Permintaan One Time Password terbaru, anda dapat menyalinnya dibawah ini"."\r\n\r\n";                
                $text    .= "*".$random."*"."\r\n\r\n";
                $text    .= "📌 Abaikan pesan ini jika bukan permintaan anda";
                break;                                
            case "reset-password-and-activation-code": die;
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
            case "lost-password":
                $get_user = $this->User_model->get_user($user_id);

                $text  = '🔓 *Permintaan Perubahan Password*'."\r\n\r\n";            
                $text .= 'Anda baru saja melakukan permintaan pengaturan ulang password di platform *'.$this->app_name.'*'."\r\n";
                // $text .= 'Nomor anda baru saja di daftarkan pada platform kami'."\r\n";            
                $text .= "\r\n".'Silahkan klik link dibawah ini untuk mereset password anda di platform '."*".$this->app_name."*."."\r\n";
                $text .= $this->app_url.'password/recovery/'.$get_user['user_activation_code'].$get_user['user_session']."\r\n\r\n";
                // $text .= "Kode OTP:"."\r\n";
                // $text .= "*".$get_user['user_activation_code']."*"."\r\n\r\n";
                $text .= "📌 Abaikan jika bukan anda, seseorang mungkin mencoba masuk menggunakan akun anda."."\r\n\r\n";
                $text .= "Jika tautan tidak dapat diklik, mohon balas pesan ini dengan kata *ok*";             
                break;
            case "lost-password-success-recovery":
                $get_user = $this->User_model->get_user($user_id);                
                $text  = '✅ *Password Berhasil Dirubah*'."\r\n\r\n";            
                $text .= 'Anda baru saja memperbarui password di platform *'.$this->app_name.'*'."\r\n\r\n";
                $text .= $get_user['user_username']."\r\n";
                $text .= $get_user['user_phone_1']."\r\n";
                $text .= $this->user_agent()."\r\n\r\n";                                
                // $text .= $this->app_url.'password/recovery/'.$get_user['user_activation_code'].$get_user['user_session']."\r\n\r\n";
                // $text .= "📌 Abaikan jika memang anda, seseorang mungkin mencoba masuk menggunakan akun anda."."\r\n";                 
                break;
            case "login-activity":
                $get_user = $this->User_model->get_user($user_id);

                $text     = '✅ *Login Berhasil*'."\r\n\r\n";
                // $text    .= 'Hi, Welcome to platform *'.$this->app_name.'*'."\r\n";
                $text    .= 'Anda telah login ke *'.$this->app_name.'*'."\r\n\r\n";
                // $text .= "\r\n".'Silahkan klik link dibawah ini untuk konfirmasi pendaftaran anda di platform ini.'."\r\n\r\n";
                // $text .= $this->app_url.'register/activation/'.$get_user['user_session'].'/'.$get_user['user_activation_whatsapp_code']."\r\n\r\n";
                // $text    .= "Reset Code:"."\r\n";
                // $text    .= "*".$get_user['user_activation_whatsapp_code']."*"."\r\n\r\n";
                $text    .= "Perangkat:"."\r\n";
                $text    .= $this->user_agent()."\r\n";
                // $text    .= "\r\n"."📌 Please ignore this message if you don't feel lost your password";
                // $text = rawurlencode($text);
                $next=false;
                break;
            default:
                break;
        }
        if($next){
            // Prepare Message insert
            $session_group      = $this->random_code(20);
            $session_message    = $this->random_code(20);

            $params = array(
                'message_contact_id' => $get_user['user_id'],
                // 'message_contact_name' => $get_user['user_username'],
                'message_contact_number' => $get_user['user_phone_1'],
                'message_text' => $text,
                'message_session' => $session_message,
                'message_group_session' => $session_group,
                // 'message_news_id' => $message_news,
                // 'message_url' => $get_news_url,
                'message_flag' => 0, 'message_device_id' => 1, 'message_date_created' => date("YmdHis")
            );  

            $set_data=$this->Message_model->add_message($params);
            $message_id = $set_data;

            if(intval($message_id) > 0){
                $datas = $this->Message_model->get_message($message_id);
                if($datas==true){
                    //Sent WhatsApp Process
                    $get_execute = $this->whatsapp_send_group($session_group);
                }
            }
        }
    }
    function email_template($action,$user_id){

    }
    function test(){
        $a = $this->whatsapp_template('request-otp',1);
        var_dump($a);die;
    }
}
