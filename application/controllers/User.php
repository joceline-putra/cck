<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller{
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));              
        }
        $this->load->model('User_model');
        $this->load->model('Menu_model');        
        $this->load->model('User_menu_model');  
        $this->load->model('User_group_model');                        
        // $this->load->model('Satuan_model');
        // $this->load->model('Gudang_model');
        // $this->load->model('Golongan_obat_model');
        // $this->load->model('Diagnosa_model');
        // $this->load->model('Jenis_praktik_model');
        // $this->load->model('Aktivitas_model');      
        $this->group_access = array(1,2); //Root / Super Administrator & Administrator
    } 
    function index(){
        //Group Access View
        $data['session'] = $this->session->userdata();     
        $data['user_group'] = $data['session']['user_data']['user_group_id'];
        $group_access = $this->group_access;
        $set_view=true;
        if(in_array($data['user_group'],$group_access)){
            $set_view = true;
        }
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        
        if($set_view){    
            $data['title'] = 'User';
            $data['_view'] = 'layouts/admin/menu/configuration/user';
        }else{
            $data['title'] = 'User';                
            $data['_view'] = 'layouts/admin/505'; 
            $file_js = 'layouts/admin/js.php';
        }

        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/configuration/user_js.php',$data);
    }
    function manage(){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        $session_group_id = intval($session['user_data']['user_group_id']);

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        if($this->input->post('action')){
            $action = $this->input->post('action');
            switch($action){    
                case "create":
                    $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    $data = json_decode($post_data, TRUE);
                    $random_session = $this->random_session(20);

                    //JSON Post
                    $params = array(
                        'user_user_group_id' => !empty($data['group']) ? $data['group'] : null,
                        'user_branch_id' => !empty($data['branch']) ? $data['branch'] : null,
                        'user_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'user_code' => !empty($data['kode']) ? $data['kode'] : null,
                        'user_fullname' => !empty($data['nama']) ? ucwords(strtolower($data['nama'])) : null,
                        'user_place_birth' => !empty($data['tempat_lahir']) ? $data['tempat_lahir'] : null,
                        'user_birth_of_date' => !empty($data['tgl_lahir']) ? $data['tgl_lahir'] : null,
                        'user_gender' => !empty($data['gender']) ? $data['gender'] : null,
                        'user_address' => !empty($data['alamat']) ? $data['alamat'] : null,
                        'user_phone_1' => !empty($data['telepon_1']) ? $data['telepon_1'] : null,
                        'user_email_1' => !empty($data['email_1']) ? $data['email_1'] : null,
                        'user_username' => !empty($data['username']) ? ltrim(rtrim(trim(strtolower(str_replace(' ','',$data['username']))))) : null,
                        'user_password' => !empty($data['password']) ? md5($data['password']) : null,
                        'user_date_created' => date("YmdHis"),
                        // 'user_date_updated' => date("YmdHis"),
                        'user_flag' => !empty($data['status']) ? $data['status'] : 1,
                        'user_activation' => 1,
                        'user_session' => $random_session,
                        'user_menu_style' => !empty($data['user_menu_style']) ? $data['user_menu_style'] : 0,
                        'user_theme' => !empty($data['user_theme']) ? $data['user_theme'] : 'black',               
                        'user_check_price_buy' => !empty($data['user_check_price_buy']) ? $data['user_check_price_buy'] : 0,
                        'user_check_price_sell' => !empty($data['user_check_price_sell']) ? $data['user_check_price_sell'] : 0                        
                    );
                    // var_dump($params);die;
                    //Direct Post
                    /*
                    $params = array(
                        'user_user_group_id' => !empty($this->input->post('group')) ? $this->input->post('group') : null,
                        'user_branch_id' => !empty($this->input->post('branch')) ? $this->input->post('branch') : null,
                        'user_type' => !empty($this->input->post('tipe')) ? $this->input->post('tipe') : null,
                        'user_code' => !empty($this->input->post('kode')) ? $this->input->post('kode') : nul,
                        'user_fullname' => !empty($this->input->post('nama')) ? ucwords($this->input->post('nama')) : null,
                        'user_place_birth' => !empty($this->input->post('tempat_lahir')) ? $this->input->post('tempat_lahir') : null,
                        'user_birth_of_date' => !empty($this->input->post('tgl_lahir')) ? $this->input->post('tgl_lahir') : null,
                        'user_gender' => !empty($this->input->post('gender')) ? $this->input->post('gender') : null,
                        'user_address' => !empty($this->input->post('alamat')) ? $this->input->post('alamat') : null,
                        'user_phone_1' => !empty($this->input->post('telepon_1')) ? $this->input->post('telepon_1') : null,
                        'user_email_1' => !empty($this->input->post('email_1')) ? $this->input->post('email_1') : null,
                        'user_username' => !empty($this->input->post('username')) ? $this->input->post('username') : null,
                        'user_password' => !empty($this->input->post('password')) ? md5($this->input->post('password')) : null,
                        'user_theme' => 'orange',
                        'user_date_created' => date("YmdHis"),
                        'user_date_updated' => date("YmdHis"),
                        'user_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 1
                    );
                    */

                    //Check Data Exist
                    $params_check = array(
                        // 'user_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'user_username' => !empty($data['username']) ? ltrim(rtrim(trim(strtolower(str_replace(' ','',$data['username']))))) : null,
                        'user_branch_id' => $session_branch_id                    
                    );
                    $check_exists = $this->User_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->User_model->add_user($params);
                        if($set_data==true){
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 2,
                                'activity_table' => 'users',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => !empty($data['kode']) ? strtoupper($data['kode']) : null,
                                'activity_text_2' => ucwords(strtolower($data['username'])),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);                
                            $return->status=1;
                            $return->message='Berhasil menambahkan '.$data['username'];
                            $return->result= array(
                                'id' => $set_data,
                                'kode' => !empty($data['kode']) ? strtoupper($data['kode']) : null,
                                'username' => $data['username'],
                                'session' => $random_session
                            ); 
                        }
                    }else{
                        $return->message='Data sudah ada';                    
                    }
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $data['id'] = $this->input->post('id');  
                    $datas = $this->User_model->get_user($data['id']);
                    if($datas==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 3,
                            'activity_table' => 'users',
                            'activity_table_id' => $datas['user_id'],
                            'activity_text_1' => strtoupper($datas['user_code']),
                            'activity_text_2' => ucwords(strtolower($datas['user_username'])),
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
                    $id = !empty($data['id']) ? $data['id'] : $this->input->post('id');

                    if($id > 0){
                        $get_user = $this->User_model->get_user($id);
                        // var_dump($id);die;
                        
                        //JSON Post
                        $params = array(
                            'user_user_group_id' => !empty($data['group']) ? $data['group'] : null,
                            'user_branch_id' => !empty($data['branch']) ? $data['branch'] : null,
                            'user_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                            'user_code' => !empty($data['kode']) ? $data['kode'] : null,
                            'user_fullname' => !empty($data['nama']) ? ucwords(strtolower($data['nama'])) : null,
                            'user_place_birth' => !empty($data['tempat_lahir']) ? $data['tempat_lahir'] : null,
                            'user_birth_of_date' => !empty($data['tgl_lahir']) ? $data['tgl_lahir'] : null,
                            'user_gender' => !empty($data['gender']) ? $data['gender'] : null,
                            'user_address' => !empty($data['alamat']) ? $data['alamat'] : null,
                            'user_phone_1' => !empty($data['telepon_1']) ? $data['telepon_1'] : null,
                            'user_email_1' => !empty($data['email_1']) ? $data['email_1'] : null,
                            'user_username' => !empty($data['username']) ? ltrim(rtrim(trim(strtolower(str_replace(' ','',$data['username']))))) : null,
                            'user_password' => !empty($data['password']) ? md5($data['password']) : $get_user['user_password'],
                            // 'user_date_created' => date("YmdHis"),
                            'user_date_updated' => date("YmdHis"),
                            'user_flag' => !empty($data['status']) ? $data['status'] : 1,
                            'user_theme' => !empty($data['user_theme']) ? $data['user_theme'] : 'black',
                            'user_menu_style' => !empty($data['user_menu_style']) ? $data['user_menu_style'] : 0,
                            'user_check_price_buy' => !empty($data['user_check_price_buy']) ? $data['user_check_price_buy'] : 0,
                            'user_check_price_sell' => !empty($data['user_check_price_sell']) ? $data['user_check_price_sell'] : 0,                                                        
                        );
                        // var_dump($params);
                        //Direct Post
                        /*
                        $params = array(
                            'user_user_group_id' => !empty($this->input->post('group')) ? $this->input->post('group') : null,
                            'user_branch_id' => !empty($this->input->post('branch')) ? $this->input->post('branch') : null,
                            'user_type' => !empty($this->input->post('tipe')) ? $this->input->post('tipe') : null,
                            'user_code' => !empty($this->input->post('kode')) ? $this->input->post('kode') : nul,
                            'user_fullname' => !empty($this->input->post('nama')) ? ucwords($this->input->post('nama')) : null,
                            'user_place_birth' => !empty($this->input->post('tempat_lahir')) ? $this->input->post('tempat_lahir') : null,
                            'user_birth_of_date' => !empty($this->input->post('tgl_lahir')) ? $this->input->post('tgl_lahir') : null,
                            'user_gender' => !empty($this->input->post('gender')) ? $this->input->post('gender') : null,
                            'user_address' => !empty($this->input->post('alamat')) ? $this->input->post('alamat') : null,
                            'user_phone_1' => !empty($this->input->post('telepon_1')) ? $this->input->post('telepon_1') : null,
                            'user_email_1' => !empty($this->input->post('email_1')) ? $this->input->post('email_1') : null,
                            'user_username' => !empty($this->input->post('username')) ? $this->input->post('username') : null,
                            'user_password' => !empty($this->input->post('password')) ? md5($this->input->post('password')) : null,
                            'user_theme' => 'orange',
                            'user_date_created' => date("YmdHis"),
                            'user_date_updated' => date("YmdHis"),
                            'user_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 1
                        );
                        */
                        if(!empty($data['password'])){
                            $params['user_password'] = md5($data['password']);
                        }
                        //Check Data Exist
                        $get_old_data=$this->User_model->get_user($id);
                        $params_check = array(
                            // 'user_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                            'user_username !=' => $get_old_data['user_username'],
                            'user_username' => !empty($data['username']) ? ltrim(rtrim(trim(strtolower(str_replace(' ','',$data['username']))))) : null,                    
                        );
                        $check_exists = $this->User_model->check_data_exist($params_check);
                        if($check_exists==false){

                            $set_data=$this->User_model->update_user($id,$params);
                            if($set_data==true){
                                //Aktivitas
                                $params = array(
                                    'activity_user_id' => $session_user_id,
                                    'activity_branch_id' => $session_branch_id,
                                    'activity_action' => 4,
                                    'activity_table' => 'users',
                                    'activity_table_id' => $id,
                                    // 'activity_text_1' => strtoupper($data['nama']),
                                    // 'activity_text_2' => ucwords(strtolower($data['nama'])),
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 0
                                );
                                $this->save_activity($params);
                                $return->status=1;
                                $return->message='Berhasil memperbarui '.$data['nama'];  
                                $return->result= array(
                                    'id' => $id,
                                    'nama' => $data['nama']
                                ); 
                            }
                        }else{
                            $return->message='Username sudah digunakan';                    
                        }      
                    }else{
                        $return->message='User tidak ditemukan';
                    }   
                    $return->params_check = $params_check;
                    break;
                case "delete":
                    break;                
                case "set-active":
                    $id = $this->input->post('id');
                    $kode = $this->input->post('kode');        
                    $user = $this->input->post('user');                                
                    $flag = $this->input->post('flag');

                    if($flag==1){
                        $msg='aktifkan user '.$user;
                        $act=7;
                    }else{
                        $msg='nonaktifkan user '.$user;
                        $act=8;
                    }

                    $set_data=$this->User_model->update_user($id,array('user_flag'=>$flag));
                    if($set_data==true){    
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => $act,
                            'activity_table' => 'users',
                            'activity_table_id' => $id,
                            'activity_text_1' => strtoupper($kode),
                            'activity_text_2' => ucwords(strtolower($user)),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);                                          
                        $return->status=1;
                        $return->message='Berhasil '.$msg;
                    }                
                    break;                                    
                case "change-theme":
                    $theme = $this->input->post('theme');
                    $user_id= $session['user_data']['user_id'];
                    $set_theme=$this->User_model->update_user($user_id,array('user_theme'=>$theme));
                    if($set_theme==true){
                        $return->status=1;
                        $return->message='Success';
                        $return->url = base_url('admin');
                    }
                    break;
                case "change-password":
                    $password = $this->input->post('password');
                    $user_id= $session['user_data']['user_id'];
                    $set_data=$this->User_model->update_user($user_id,array('user_password'=> md5($password)));
                    if($set_data==true){
                        $return->status=1;
                        $return->message='Berhasil ganti password';
                    }
                    break;           
                case "create-user-menu":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['user_id'];
                    // var_dump($data);die;
                    $parent_id = $this->Menu_model->get_menu($data['menu_id']);
                    $params = array(
                        'user_menu_user_id' => $data['user_id'],
                        'user_menu_menu_id' => $data['menu_id'],
                        'user_menu_menu_parent_id' => $parent_id['menu_parent_id'], 
                        'user_menu_action' => $data['action'],
                        'user_menu_date_created' => date('YmdHis'),
                        'user_menu_date_updated' => date('YmdHis'),
                        'user_menu_flag' => 1,                                      
                    );

                    //Check Data Exist
                    // $get_old_data=$this->User_model->get_user($id);
                    $params_check = array(
                        'user_menu_user_id' => $data['user_id'],
                        'user_menu_menu_id' => $data['menu_id'],
                        'user_menu_action' => $data['action']                    
                    );
                    $check_exists = $this->Menu_model->check_data_exist_user_menu($params_check);
                    if($check_exists==false){

                        $set_data=$this->User_model->add_user_menu($params);
                        if($set_data==true){
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'users',
                                'activity_table_id' => $id,
                                'activity_text_1' => strtoupper($data['user_id']),
                                'activity_text_2' => ucwords(strtolower($data['action_name'])),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            $return->status=1;
                            $return->message='Success memberikan akses '.$data['action_name'];  
                            $return->result= array(
                                'id' => $id
                            ); 
                        }
                    }else{
                        $return->message='User sudah ada akses '.$data['action_name'];                    
                    }                  
                    break;
                case "remove-akses": die;
                    //not used
                    break;
                case "load":
                    $columns = array(
                        '0' => 'user_username',
                        '1' => 'user_fullname',
                        '2' => 'branch_name',
                        '3' => 'user_date_last_login'
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

                    //Root Access
                    $params = array();
                    if($session_group_id > 1){ //If Not Root
                        $params = array(
                            // 'user_flag > ' => 0
                            'user_branch_id' => intval($session_branch_id),                    
                        );
                    }else{
                        $params = array('user_branch_id >' => 0);
                    }

                    if ($this->input->post('filter_branch') && $this->input->post('filter_branch') > 0) {
                        $params['user_branch_id'] = $this->input->post('filter_branch');
                    }

                    if ($this->input->post('filter_group') && $this->input->post('filter_group') > 0) {
                        $params['user_user_group_id'] = $this->input->post('filter_group');
                    }

                    // var_dump($limit,$start,$order,$dir);die;
                    $datas = $this->User_model->get_all_users($params, $search, $limit, $start, $order, $dir);
                    // $datas_count = $this->User_model->get_all_users_count($params, $search, $limit, $start, $order, $dir);                
                    if(isset($datas)){ //Data exist
                        $data_source=$datas; $total=count($datas);
                        $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                        $return->result=$datas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->params = $params;
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    break;
                case "load-user-access":
                    $data['id'] = $this->input->post('id');  
                    $get_user = $this->User_model->get_user($data['id']);

                    //Menu Parent        
                    $parent_data = array();
                    $params_parent = array(
                        'menu_parent_id'=> 0,
                        'menu_flag' => 1
                    );
                    $get_parent_menu = $this->Menu_model->get_all_menus($params_parent,null,null,null,'menu_sorting','asc');
                    foreach($get_parent_menu as $p):
                        $parent_id      = intval($p['menu_id']);
                        $parent_sorting = intval($p['menu_sorting']);
                        $parent_flag    = intval($p['menu_flag']); 
                        
                        //Menu Child
                        $child_data = array();
                        $get_child_menu = $this->Menu_model->get_all_menus(array('menu_parent_id'=>$parent_id),null,null,null,'menu_sorting','asc');
                        foreach ($get_child_menu as $v):
                            $child_data[] = array(
                                'child_id' => intval($v['menu_id']),
                                'child_name' => $v['menu_name'],
                                'child_icon' => $v['menu_icon'],
                                'child_link' => $v['menu_link'],
                                'child_sorting' => intval($v['menu_sorting']),
                                'child_flag' => intval($v['menu_flag']),
                                'child_access' => array(
                                    'view' => array('random'=> $this->random_code(8), 'flag'=>1),
                                    'create' => array('random'=> $this->random_code(8), 'flag'=>0),
                                    'read' => array('random'=> $this->random_code(8), 'flag'=>0),
                                    'update' => array('random'=> $this->random_code(8), 'flag'=>0),
                                    'delete' => array('random'=> $this->random_code(8), 'flag'=>1)
                                )  
                            );
                        endforeach;

                        $parent_data[] = array(
                            'parent_id' => $parent_id,
                            'parent_name' => $p['menu_name'],
                            'parent_icon' => $p['menu_icon'],
                            'parent_link' => $p['menu_link'],
                            'parent_sorting' => $parent_sorting,
                            'parent_flag' => $parent_flag,
                            'parent_child' => $child_data
                        );
                    endforeach;

                    $return->result = array(
                        'user' => $get_user,
                        'menu' => $parent_data
                    );   
                    // if($datas==true){                   
                        $return->status=1;
                        $return->message='Success';
                    // }    
                    break;
                case "load-user-access-sp":
                    $user_id = $this->input->post('id');  
                    $get_user = $this->User_model->get_user($user_id);
                    
                    $prepare="CALL sp_user_menu($user_id)";
                    $query=$this->db->query($prepare);
                    $result = $query->result_array();
                    mysqli_next_result($this->db->conn_id); 
                    $query->free_result();

                    $menu_data = array();    
                    foreach($result as $v):
                        $menu_data[] = array(
                            'menu_parent_id' => intval($v['MENU_PARENT_ID']),
                            'menu_parent_name' => $v['MENU_PARENT_NAME'],
                            'menu_child_id' => intval($v['MENU_CHILD_ID']),
                            'menu_child_name' => $v['MENU_CHILD_NAME'],                        
                            'menu_icon' => $v['MENU_ICON'],
                            'menu_link' => $v['MENU_LINK'],
                            'menu_sorting' => intval($v['MENU_SORTING']),
                            'menu_flag' => intval($v['MENU_FLAG']),
                            'menu_random' => $this->random_code(6),                    
                            'menu_access' => array(
                                'view' => array('random'=> $this->random_code(8), 'flag'=>$v['ACCESS_VIEW']),
                                'create' => array('random'=> $this->random_code(8), 'flag'=>$v['ACCESS_CREATE']),
                                'read' => array('random'=> $this->random_code(8), 'flag'=>$v['ACCESS_READ']),
                                'update' => array('random'=> $this->random_code(8), 'flag'=>$v['ACCESS_UPDATE']),
                                'delete' => array('random'=> $this->random_code(8), 'flag'=>$v['ACCESS_DELETE']),
                                'print' => array('random'=> $this->random_code(8), 'flag'=>$v['ACCESS_PRINT']),
                                'approval' => array('random'=> $this->random_code(8), 'flag'=>$v['ACCESS_APPROVAL'])                            
                            )  
                        );
                    endforeach;

                    $return->result = array(
                        'user' => $get_user,
                        'menu' => $menu_data
                    );   
                    // if($datas==true){                   
                        $return->status=1;
                        $return->message='Success';
                    // }    
                    break;
                case "update-user-menu":
                    $flag = intval($this->input->post('flag'));
                    $menu = intval($this->input->post('menu'));                
                    $menu_action = intval($this->input->post('menu_action'));
                    $set_user = intval($this->input->post('user'));                
                    
                    $set_flag = (intval($flag) == 0) ? 1 : 0; 
                    
                    //Check Data Exist
                    $params_check = array(
                        'user_menu_menu_id' => $menu,
                        'user_menu_action' => $menu_action,
                        'user_menu_user_id' => $set_user                    
                    );
                    $check_exists = $this->User_menu_model->check_data_exist($params_check);
                    if($check_exists==false){
                        
                        //Insert
                        $params = array(
                            'user_menu_menu_id' => $menu,
                            'user_menu_action' => $menu_action,
                            'user_menu_user_id' => $set_user,
                            'user_menu_date_created' => date('YmdHis'),                                
                            'user_menu_date_updated' => date('YmdHis'),                                                        
                            'user_menu_flag' => $set_flag,                        
                        );
                        $set_data=$this->User_model->add_user_menu($params);

                        $return->status=1;
                        $return->message='Created Data';                
                        $return->flag= array(
                            'from' => $flag,
                            'to' => $set_flag
                        ); 
                        $return->operator='insert';               
                    }else{
                        
                        $where = array(
                            'user_menu_menu_id' => $menu,
                            'user_menu_action' => $menu_action,
                            'user_menu_user_id' => $set_user
                        );
                        $params = array(
                            'user_menu_flag' => $set_flag
                        );

                        $set_data = $this->User_menu_model->update_user_menu($where,$params);
                        if($set_data){
                            $return->status=1;
                            $return->message='Updated Data';                
                            $return->flag= array(
                                'from' => $flag,
                                'to' => $set_flag
                            );                         
                        }else{
                            $return->message='Data found';
                        }
                        $return->operator='update';                    
                        
                    }                
                    break;
            }
        }
        $return->action=$action;
        echo json_encode($return);
    }
    function group(){
        if ($this->input->post()) {    
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'user_group_id',
                        '1' => 'user_group_name'
                    );

                    $limit     = !empty($post['length']) ? $post['length'] : 10;
                    $start     = !empty($post['start']) ? $post['start'] : 0;
                    $order     = !empty($post['order']) ? $columns[$post['order'][0]['column']] : $columns[0];
                    $dir       = !empty($post['order'][0]['dir']) ? $post['order'][0]['dir'] : "asc";
                    
                    $search    = [];
                    if(!empty($post['search']['value'])) {
                        $s = $post['search']['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array();
                    $params['user_group_flag'] = 1;
                    $get_count = $this->User_group_model->get_all_user_group_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->User_group_model->get_all_user_group($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = [];
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "create_update":
                    $this->form_validation->set_rules('group_id', 'group_id', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $group_name = !empty($post['group_name']) ? $post['group_name'] : null;

                        //Check Data Exist
                        $params_check = array(
                            'user_group_name' => $group_name
                        );

                        if(intval($post['group_id']) > 0){ /* Update if Exist */ // if( (!empty($post['group_session'])) && (strlen($post['group_session']) > 10) ){ /* Update if Exist */

                            /* Check Existing Data */
                            $where_not = [
                                'user_group_id' => intval($post['group_id']),
                            ];
                            $where_new = [
                                'user_group_name' => $group_name
                            ];
                            $check_exists = $this->User_group_model->check_data_exist_two_condition($where_not,$where_new);

                            /* Continue Update if not exist */
                            if(!$check_exists){
                                $where = array(
                                    'user_group_id' => intval($post['group_id']),
                                );
                                $params = array(
                                    'user_group_name' => $group_name,
                                    'user_group_flag' => !empty($post['group_flag']) ? $post['group_flag'] : 1,
                                    'user_group_date_updated' => date("YmdHis")
                                );
                                $update = $this->User_group_model->update_user_group_custom($where,$params);
                                if($update){
                                    $get_group = $this->User_group_model->get_user_group_custom($where);
                                    $return->status  = 1;
                                    $return->message = 'Berhasil memperbarui '.$group_name;
                                    $return->result= array(
                                        'group_id' => $update,
                                        'group_name' => $get_group['user_group_name']
                                    );
                                }else{
                                    $return->message = 'Gagal memperbarui '.$group_name;
                                }
                            }else{
                                $return->message = 'Data sudah digunakan';
                            }
                        }else{ /* Save New Data */

                            /* Check Existing Data */
                            $params_check = [
                                'user_group_name' => $group_name
                            ];
                            $check_exists = $this->User_group_model->check_data_exist($params_check);

                            /* Continue Save if not exist */
                            if(!$check_exists){
                                $group_session = $this->random_session(20);
                                $params = array(
                                    'user_group_name' => $group_name,
                                    'user_group_flag' => !empty($post['group_flag']) ? $post['group_flag'] : 1,
                                    'user_group_date_created' => date("YmdHis")
                                );
                                $create = $this->User_group_model->add_user_group($params);
                                if($create){
                                    $get_group = $this->User_group_model->get_user_group($create);
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$group_name;
                                    $return->result= array(
                                        'group_id' => $create,
                                        'group_name' => $get_group['user_group_name']
                                    );
                                }else{
                                    $return->message = 'Gagal menambahkan '.$group_name;
                                }
                            }else{
                                $return->message = 'Data sudah ada';
                            }
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('group_id', 'group_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $group_id   = !empty($post['group_id']) ? $post['group_id'] : 0;
                        if(intval(strlen($group_id)) > 0){        
                            $datas = $this->User_group_model->get_user_group($group_id);
                            if($datas){
                                $return->status=1;
                                $return->message='Berhasil mendapatkan data';
                                $return->result=$datas;
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('group_id', 'group_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $group_id   = !empty($post['group_id']) ? $post['group_id'] : 0;
                        $group_name = !empty($post['group_name']) ? $post['group_name'] : null;

                        if(strlen($group_id) > 0){
                            //Check Count of users have this group
                            $check = $this->User_model->get_all_users_count(['user_user_group_id' => $group_id]);
                            if($check < 1){
                                $get_data=$this->User_group_model->get_user_group($group_id);
                                // $set_data=$this->User_group_model->delete_user_group($group_id);
                                $set_data = $this->User_group_model->update_user_group_custom(array('user_group_id'=>$group_id),array('user_group_flag'=>4));                
                                if($set_data){
                                    /*
                                    $file = FCPATH.$this->folder_upload.$get_data['group_image'];
                                    if (file_exists($file)) {
                                        unlink($file);
                                    }
                                    */
                                    $return->status=1;
                                    $return->message='Berhasil menghapus '.$group_name;
                                }else{
                                    $return->message='Gagal menghapus '.$group_name;
                                } 
                            }else{
                                $return->message = 'Gagal, Group "'.$group_name.'" digunakan '.$check.' orang'; 
                            }
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
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

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            $data['title'] = 'Group User';
            $data['_view'] = 'layouts/admin/menu/configuration/group_user';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/configuration/group_user_js.php',$data);
        }
    }
}
