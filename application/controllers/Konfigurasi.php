<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfigurasi extends MY_Controller{
    var $folder_upload = 'upload/branch/';
    var $image_width   = 480;
    var $image_height  = 120;     
    /* 
        1 = Satuan / units
        2 = Gudang / locations
        3 = Akun Perkiraan / accounts
        33 = Akun Peta / accounts_maps
        4 = Menu / menus
        6 = Cabang / branchs
    */   
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('User_model');           
        $this->load->model('Satuan_model');
        $this->load->model('Lokasi_model');
        $this->load->model('Account_model'); 
        $this->load->model('Account_map_model');                  
        $this->load->model('Menu_model');                   
        $this->load->model('Konfigurasi_model');        
        $this->load->model('Branch_model');

        $this->load->library('form_validation');
        $this->load->helper('form');   

        $this->group_access = array(1,2); //Root / Super Administrator & Administrator
        $this->group_access_menu = array(1); //Root / Super Administrator            
    } 
    function index(){
        $data['identity'] = 0;
        $data['session'] = $this->session->userdata();
        $data['usernya'] = $this->User_model->get_all_user();
        // var_dump($data['usernya']);die;        
        
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
                
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        //Date Now
        $datenow =date("d-m-Y");         
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        $data['title'] = 'Statistik';
        $data['_view'] = 'reference/statistic';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('reference/statistic_js',$data);        
    }   
    function pages($identity){

        
        //Group Access View
        $data['session'] = $this->session->userdata();     
        $data['user_group'] = $data['session']['user_data']['user_group_id'];
        $group_access = $this->group_access;
        $set_view=false;
        if(in_array($data['user_group'],$group_access)){
            $set_view = true;
        }

        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        if($identity == 1){ //Satuan
            // $data['satuan'] = $this->Satuan_model->get_all_satuan();  
            if($set_view){                            
                $data['identity'] = 1;
                $data['title'] = 'Satuan';
                $data['_view'] = 'layouts/admin/menu/reference/unit';
                $file_js = 'layouts/admin/menu/reference/unit_js.php';
            }else{
                $data['title'] = 'Menu';
                $data['_view'] = 'layouts/admin/505'; $file_js = 'layouts/admin/js.php';
            }                   
        }
        if($identity == 2){ //Lokasi
            // $data['user_all'] = $this->User_model->get_all_user();    
            // $data['referensi'] = $this->Referensi_model->get_all_referensi(array('tipe'=>6));
            if($set_view){           
                $data['identity'] = 2;            
                $data['title'] = 'Gudang';
                $data['_view'] = 'layouts/admin/menu/product/warehouse';
                $file_js = 'layouts/admin/menu/product/warehouse_js.php';
            }else{
                $data['title'] = '505';                
                $data['_view'] = 'layouts/admin/505'; $file_js = 'layouts/admin/js.php';                
            }
        }
        if($identity == 3){ //Akun
            if($set_view){              
                $data['identity'] = 3;
                $data['title'] = 'Akun Perkiraan';
                $data['_view'] = 'layouts/admin/menu/configuration/account';
                $file_js = 'layouts/admin/menu/configuration/account_js.php';
            }else{
                $data['title'] = '505';                
                $data['_view'] = 'layouts/admin/505'; $file_js = 'layouts/admin/js.php';
            }                 
        }
        if($identity == 33){ //Akun Mapping
            if($set_view){              
                $data['identity'] = 3;
                $data['title'] = 'Pemetaan Akun Perkiraan';
                $data['_view'] = 'layouts/admin/menu/configuration/mapping';
                $file_js = 'layouts/admin/menu/configuration/mapping_js.php';
            }else{
                $data['title'] = '505';                
                $data['_view'] = 'layouts/admin/505'; $file_js = 'layouts/admin/js.php';
            }                 
        }        
        if($identity == 4){ //Menu
            $set_view = false;
            // $data['group'] = $this->Konfigurasi_model->get_all_data('menu_groups');   
            $group_access_menu = $this->group_access_menu;
            if(in_array($data['user_group'],$group_access_menu)){
                $set_view = true;
            }
            // var_dump($set_view);die;
            if($set_view){
                $data['identity'] = 4;
                $data['title'] = 'Menu';
                $data['_view'] = 'layouts/admin/menu/configuration/menu';
                $file_js = 'layouts/admin/menu/configuration/menu_js.php';
            }else{
                $data['title'] = 'Menu';
                $data['_view'] = 'layouts/admin/505'; $file_js = 'layouts/admin/js.php';
            }                
        }
        if($identity == 5){ //Perusahaan
            // $data['group'] = $this->Konfigurasi_model->get_all_data('menu_groups');     
            if($set_view){         
                $data['identity'] = 5;
                $data['title'] = 'Perusahaan';
                $data['_view'] = 'layouts/admin/menu/setup/company';
                $file_js = 'layouts/admin/menu/setup/company_js.php';
            }else{
                $data['title'] = '505';                
                $data['_view'] = 'layouts/admin/505'; $file_js = 'layouts/admin/js.php';
            }
        }
        if($identity == 6){ //Branch

            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);

            // $data['group'] = $this->Konfigurasi_model->get_all_data('menu_groups');    
            if($set_view){                               
                $data['identity'] = 6;
                $data['title'] = 'Cabang';
                $data['_view'] = 'layouts/admin/menu/configuration/branch';
                $file_js = 'layouts/admin/menu/configuration/branch_js.php';
            }else{
                $data['title'] = 'Cabang';    
                $data['_view'] = 'layouts/admin/505'; $file_js = 'layouts/admin/js.php';
            }
        }        

        // var_dump($data['satuan']);die;
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function manage(){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];       
        $session_user_group_id = $session['user_data']['user_group_id'];       
         
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';
        $user_id = $session['user_data']['user_id'];
        
        if($this->input->post('action')){
            $action = $this->input->post('action');
            $post_data = $this->input->post('data');
            $data = json_decode($post_data, TRUE);
            $identity = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];

            //Produk ID or TIPE
            if($identity == 1){ //Satuan
                $set_tipe = 1;

                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $status = !empty($data['status']) ? $data['status'] : null;         

                $params_check = array(
                    'unit_name' => $nama
                );
                $params = array(
                    'unit_name' => $nama,
                    'unit_note' => $keterangan,         
                    'unit_date_created' => date("YmdHis"),
                    'unit_date_updated' => date("YmdHis"),
                    'unit_flag' => $status,
                    'unit_user_id' => $user_id,
                    'unit_branch_id' => $session_branch_id
                );
                $params_update = array(
                    'unit_name' => $nama,
                    'unit_note' => $keterangan,
                    'unit_date_updated' => date("YmdHis"),
                    'unit_flag' => $status
                );                                 
                $columns = array(
                    '0' => 'units.unit_name',
                    '1' => 'units.unit_note'
                );                           
                $table = 'units';                                 
            }
            if($identity == 2){ //Gudang
                $set_tipe = 2;

                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $status = !empty($data['status']) ? $data['status'] : null;  
                $users = !empty($data['user']) ? $data['user'] : null;                  

                $params = array(
                    'location_code' => $kode,
                    'location_name' => $nama,
                    'location_note' => $keterangan, 
                    'location_user_id' => $session_user_id,          
                    'location_branch_id' => $session_branch_id,                                         
                    'location_date_created' => date("YmdHis"),
                    'location_date_updated' => date("YmdHis"),
                    'location_flag' => $status
                );
                $params_update = array(
                    'location_name' => $nama,
                    'location_note' => $keterangan,
                    'location_date_updated' => date("YmdHis"),                    
                    'location_flag' => $status               
                );             
                $params_check = array(
                    'location_code' => $kode,
                    'location_branch_id' => $session_branch_id
                );                        
                $columns = array(
                    '0' => 'locations.location_code',
                    '1' => 'locations.location_name',
                    // '2' => 'user.user_username'
                ); 
                $table = 'locations';  
            }
            if($identity == 3){ //Akun Perkiraan
                $set_tipe = 3;
                
                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $group = !empty($data['group']) ? $data['group'] : null;
                $group_sub = !empty($data['group_sub']) ? $data['group_sub'] : null;    
                $group_sub_name = !empty($data['group_sub_name']) ? $data['group_sub_name'] : null;                                  
                $status = !empty($data['status']) ? $data['status'] : null;        
                $account_locked = !empty($data['account_locked']) ? $data['account_locked'] : 0;                                                        
                
                $params = array(
                    'account_code' => $kode,
                    'account_name' => $nama,
                    'account_group' => $group, 
                    'account_group_sub' => $group_sub,
                    'account_group_sub_name' => $group_sub_name,                    
                    'account_date_created' => date("YmdHis"),
                    'account_date_updated' => date("YmdHis"),
                    'account_flag' => $status,
                    'account_user_id' => $session_user_id,          
                    'account_branch_id' => $session_branch_id,
                    'account_locked' => $account_locked   
                );
                $params_modal = array(
                    'account_code' => $kode,
                    'account_name' => $nama,
                    'account_group' => $group, 
                    'account_group_sub' => $group_sub,                     
                    'account_date_created' => date("YmdHis"),
                    'account_date_updated' => date("YmdHis"),
                    'account_flag' => 1,
                    'account_user_id' => $session_user_id,          
                    'account_branch_id' => $session_branch_id,
                    'account_locked' => 0   
                );      
                $params_check = array(
                    'account_code' => $kode,
                    'account_branch_id' => $session_branch_id
                );                          
                $params_update = array(
                    'account_code' => $kode,
                    'account_name' => $nama,
                    'account_group' => $group,
                    'account_group_sub' => $group_sub,   
                    'account_group_sub_name' => $group_sub_name,                                   
                    'account_date_updated' => date("YmdHis"),
                    'account_flag' => $status,
                    'account_locked' => $account_locked
                );  
                $columns = array(
                    '0' => 'account_code',
                    '1' => 'account_name',
                );
                $table = 'accounts';                                       
            }
            if($identity == 33){ //Accounts Maps
                $table = 'accounts_maps';
            }
            if($identity == 4){ //Menu
                $set_tipe = 4;

                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $link = !empty($data['link']) ? $data['link'] : null;  
                $icon = !empty($data['icon']) ? $data['icon'] : null;                  
                $group = !empty($data['group']) ? $data['group'] : 0;                  
                $status = !empty($data['status']) ? $data['status'] : null;      

                $params_check = array(
                    'menu_name' => $nama
                );                
                $params = array(
                    'menu_parent_id' => $group,
                    'menu_name' => $nama,
                    'menu_link' => $link, 
                    'menu_icon' => $icon,
                    'menu_date_created' => date("YmdHis"),
                    'menu_flag' => $status
                );
                $params_update = array(
                    'menu_parent_id' => $group,
                    'menu_name' => $nama,
                    'menu_link' => $link,
                    'menu_icon' => $icon,                    
                    'menu_flag' => $status
                );   
                // var_dump($params_update);die;
                $params_update_user_menu = array(
                    'user_menu_menu_parent_id' => $group,
                );                                  

                if($group > 0){
                    $params['menu_sorting'] = 99;
                }
                
                $columns = array(
                    '0' => 'parent.menu_name',
                    '1' => 'child.menu_name'
                ); 
                $table = 'menus';         
                $table_user_menu = 'user_menus';                            
            }
            if($identity == 6){ //Cabang
                $set_tipe = 6;

                $kode       = !empty($this->input->post('kode')) ? $this->input->post('kode') : null;
                $nama       = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;   
                $address    = !empty($this->input->post('alamat')) ? $this->input->post('alamat') : null;   
                $phone      = !empty($this->input->post('telepon_1')) ? $this->input->post('telepon_1') : null;   
                $email      = !empty($this->input->post('email_1')) ? $this->input->post('email_1') : null;   
                $keterangan = !empty($this->input->post('keterangan')) ? $this->input->post('keterangan') : null;  
                $status     = !empty($this->input->post('status')) ? $this->input->post('status') : null;         
                $specialist = !empty($this->input->post('specialist')) ? $this->input->post('specialist') : null;  
                $user       = !empty($this->input->post('user')) ? $this->input->post('user') : null;     
                $with_stock = !empty($this->input->post('with_stock')) ? $this->input->post('with_stock') : null;  
                $with_journal = !empty($this->input->post('with_journal')) ? $this->input->post('with_journal') : null;     

                $province = !empty($this->input->post('provinsi')) ? $this->input->post('provinsi') : null;  
                $city = !empty($this->input->post('kota')) ? $this->input->post('kota') : null;  
                $district = !empty($this->input->post('kecamatan')) ? $this->input->post('kecamatan') : null;
                
                $header = !empty($this->input->post('header')) ? $this->input->post('header') : null;
                $footer = !empty($this->input->post('footer')) ? $this->input->post('footer') : null;

                $create_session = $this->random_session(20);
                if(intval($user) > 0){
                    $user = $user;
                }else{
                    $user = null;
                }
                $params_check = array(
                    // 'branch_code' => $kode,
                    'branch_name' => $nama
                );
                $params = array(
                    'branch_code' => $kode,
                    'branch_name' => $nama,
                    'branch_address' => $address,
                    'branch_phone_1' => $phone,
                    'branch_email_1' => $email,
                    'branch_note' => $keterangan,         
                    'branch_date_created' => date("YmdHis"),
                    'branch_date_updated' => date("YmdHis"),
                    'branch_flag' => $status,
                    'branch_user_id' => $user,
                    'branch_specialist_id' => $specialist,
                    // 'branch_transaction_with_stock' => $with_stock,
                    // 'branch_transaction_with_journal' => $with_journal,
                    'branch_transaction_with_stock' => 'Yes',
                    'branch_transaction_with_journal' => 'Yes',                    
                    'branch_province_id' => $province,
                    'branch_city_id' => $city,
                    'branch_district_id' => $district,
                    // 'branch_document_header' => $header,
                    // 'branch_document_footer' => $footer
                    'branch_session' => $create_session
                );
                $params_update = array(
                    'branch_name' => $nama,
                    'branch_address' => $address,
                    'branch_phone_1' => $phone,
                    'branch_email_1' => $email,                    
                    'branch_note' => $keterangan,
                    'branch_date_updated' => date("YmdHis"),
                    'branch_flag' => $status,
                    'branch_user_id' => $user,                    
                    'branch_specialist_id' => $specialist,
                    // 'branch_transaction_with_stock' => $with_stock,
                    // 'branch_transaction_with_journal' => $with_journal                          
                    'branch_province_id' => $province,
                    'branch_city_id' => $city,
                    'branch_district_id' => $district,
                    // 'branch_document_header' => $header,
                    // 'branch_document_footer' => $footer
                );
                // $params_check = array(
                    // 'branch_user_id' => $user
                // );                                       
                $columns = array(
                    '0' => 'branchs.branch_id',
                    '1' => 'branchs.branch_code',
                    '2' => 'branchs.branch_name',
                    '3' => 'branchs.branch_phone_1',
                    '4' => 'branchs.branch_email_1',                    
                    '5' => 'branchs.branch_address',
                );                           
                $table = 'branchs';                                 
            } 

            //Convert Table Name
            $table_to_prefix = substr($table, 0, -1);
            switch($action){
                case "load":
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

                    if($identity==1){ //Satuan
                        $params_datatable = array(
                            // 'units.unit_branch_id' => $session_branch_id
                        );   
                        $datas = $this->Satuan_model->get_all_satuans($params_datatable, $search, $limit, $start, $order, $dir);
                        $datas_count = $this->Satuan_model->get_all_satuans_count($params_datatable,$search);                    
                    }
                    if($identity==2){ //Lokasi
                        $params_datatable = array(
                            'locations.location_branch_id' => $session_branch_id
                        );   
                        $datas = $this->Lokasi_model->get_all_lokasis($params_datatable, $search, $limit, $start, $order, $dir);
                        $datas_count = $this->Lokasi_model->get_all_lokasis_count($params_datatable,$search);                     
                    }    
                    if($identity==3){ //Akun
                        $params_datatable = array(
                            'account_branch_id' => intval($session_branch_id)
                        );   

                        if($this->input->post('account_group') > 0){
                            if($this->input->post('account_group') == 100){
                                $params_datatable['account_group'] = (NULL);
                            }else{
                                $params_datatable['account_group'] = intval($this->input->post('account_group'));
                            }
                        }

                        if($this->input->post('account_group_sub') > 0){
                            $params_datatable['account_group_sub'] = intval($this->input->post('account_group_sub'));
                        }

                        if($this->input->post('account_flag') !== 'ALL'){
                            $params_datatable['account_flag'] = intval($this->input->post('account_flag'));
                        }else{
                            $params_datatable['account_flag <'] = 5; 
                        }

                        $datas = $this->Account_model->get_all_account($params_datatable, $search, $limit, $start, $order, $dir);
                        $datas_count = $this->Account_model->get_all_account_count($params_datatable,$search);                       
                    }              
                    if($identity==4){ //Menu
                        $params_datatable = array(
                            // 'menu_parent_id > ' => 0
                        );   
                        if($this->input->post('filter_parent') > 0){
                            // $params_datatable['menu_parent_id'] = intval($this->input->post('filter_parent'));
                            $params_datatable['parent.menu_id'] = intval($this->input->post('filter_parent'));                        
                        }   

                        if($this->input->post('filter_flag') !== 'ALL'){
                            $params_datatable['menu_flag'] = intval($this->input->post('filter_flag'));
                        }else{
                            $params_datatable['menu_flag <'] = 5; 
                        }                               
                    
                        $datas = $this->Menu_model->get_all_menus_custom($params_datatable, $search, $limit, $start, $order, $dir);
                        $datas_count = $this->Menu_model->get_all_menus_custom_count($params_datatable,$search);                       
                    }         
                    if($identity==6){ //Cabang
                        $params_datatable = array(
                            // 'flag' => 1
                        );   

                        if($session_user_group_id > 1){
                            $params_datatable['branch_id'] = intval($session_branch_id);
                        }else{
                            $params_datatable['branch_id >'] = 0;
                        }
                    
                        if($this->input->post('filter_specialist') > 0){
                            $params_datatable['branch_specialist_id'] = intval($this->input->post('filter_specialist'));
                        }                    
                    
                        if($this->input->post('filter_province') > 0){
                            $params_datatable['branch_province_id'] = intval($this->input->post('filter_province'));
                        }                    

                        if($this->input->post('filter_city') > 0){
                            $params_datatable['branch_city_id'] = intval($this->input->post('filter_city'));
                        }                    

                        $datas = $this->Branch_model->get_all_branch($params_datatable, $search, $limit, $start, $order, $dir);
                        $datas_count = $this->Branch_model->get_all_branch_count($params_datatable,$search);                    
                    }                                

                    if($datas_count > 0){ //Data exist
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->result=$datas;        
                    }else{ 
                        $return->status  = 0; 
                        $return->message = 'No data'; 
                        $return->result  = array();    
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->total_records      = $datas_count;                    
                    $return->params             = $params_datatable;
                    $return->search             = $search;
                    break;                
                case "create": //Checked 
                    //Check Data Exist
                    $check_exists = $this->Konfigurasi_model->check_data_exist($table,$params_check);
                    if($check_exists==false){
                        $set_data=$this->Konfigurasi_model->add_data($table,$params);

                        if($set_data==true){

                            //Call SP To Setup Account & Account Map
                            $setup_result = 0;
                            if($table=='branchs'){
                                $set_branch = $set_data;
                                $setup = $this->set_account_from_setup_accounts_items($set_branch,$specialist);
                                if($setup){
                                    $setup_result = 1;
                                }

                                $id = $set_data;
                                // $data = $this->Konfigurasi_model->get_data($table,$id);                            

                                // //Save Image if Exist
                                // $upload_directory = $this->folder_upload;                            
                                // $path = FCPATH . $upload_directory;
                                // $config['image_library'] = 'gd2';
                                // $config['upload_path'] = $path;
                                // $config['allowed_types'] = 'gif|jpg|png|jpeg';
                                // $this->load->library('upload', $config);
                                // $this->upload->initialize($config);

                                // if ($this->upload->do_upload('upload1')) {
                                //     $upload = $this->upload->data();
                                //     $raw_photo = time() . $upload['file_ext'];
                                //     $old_name = $upload['full_path'];
                                //     $new_name = $path . $raw_photo;
                                //     if (rename($old_name, $new_name)) {
                                //         $compress['image_library'] = 'gd2';
                                //         $compress['source_image'] = $upload_directory . $raw_photo;
                                //         $compress['create_thumb'] = FALSE;
                                //         $compress['maintain_ratio'] = TRUE;
                                //         $compress['width'] = $this->image_width;
                                //         $compress['height'] = $this->image_height;
                                //         $compress['new_image'] = $upload_directory . $raw_photo;
                                //         $this->load->library('image_lib', $compress);
                                //         $this->image_lib->resize();

                                //         if ($data && $data['branch_id']) {
                                //             $params_image = array(
                                //                 'branch_logo' => $upload_directory.$raw_photo
                                //             );
                                //             if (!empty($data['branch_logo'])) {
                                //                 if (file_exists(FCPATH . $data['branch_logo'])) {
                                //                     unlink(FCPATH . $data['branch_logo']);
                                //                 }
                                //             }
                                //             $stat=$this->Konfigurasi_model->update_data($table,$id,$params_image);    
                                //         }
                                //     }
                                // }

                                $branch_id = $id;
                                $get_data = $this->Branch_model->get_branch($id);

                                if(!empty($user)){
                                    $this->User_model->update_user($user,array('user_branch_id'=>$branch_id));
                                }

                                //Croppie Upload Image
                                $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                                if(strlen($post_upload) > 10){
                                    $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                                    if($upload_process->status == 1){
                                        if ($get_data && $get_data['branch_id']) {
                                            $params_image = array(
                                                'branch_logo' => $upload_process->result['file_location'],
                                                'branch_logo_sidebar' => $upload_process->result['file_location'],                                        
                                            );
                                            if($get_data['branch_logo'] !== 'upload/branch/default_logo.png'){
                                                if (!empty($get_data['branch_logo'])) {
                                                    if (file_exists(FCPATH . $get_data['branch_logo'])) {
                                                        unlink(FCPATH . $get_data['branch_logo']);
                                                    }
                                                }
                                            }
                                            $stat = $this->Branch_model->update_branch($branch_id, $params_image);
                                        }
                                    }else{
                                        $return->message = 'Fungsi Gambar gagal';
                                    }
                                }
                                //End of Croppie                                 
                            }

                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => 2,
                                'activity_table' => $table,
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => strtoupper($nama),
                                'activity_text_2' => ucwords(strtolower($nama)),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);                
                            $return->status=1;
                            $return->message='Berhasil menambahkan '.$nama;
                            $return->result= array(
                                'id' => $set_data,
                                'nama' => $nama,
                                'setup_account' => $setup_result
                            );                         
                        }
                    }else{
                        $return->message='Data sudah ada';   
                        $return->params_check = $params_check;                 
                    }
                    break;
                case "read": //Checked 
                    $data['id'] = $this->input->post('id');           
                    $datas = $this->Konfigurasi_model->get_data($table,$data['id']);
                    if($datas==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 3,
                            'activity_table' => $table,
                            'activity_table_id' => $datas[$table_to_prefix.'_id'],
                            'activity_text_1' => strtoupper($datas[$table_to_prefix.'_name']),
                            'activity_text_2' => ucwords(strtolower($datas[$table_to_prefix.'_name'])),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);        

                        $parent=array();
                        if($identity == 4){ //Menu            
                            $parent = $this->Konfigurasi_model->get_data($table,$datas['menu_parent_id']);
                        }
                        $return->status=1;
                        $return->message='Success';
                        $return->result=$datas;
                        $return->parent=$parent;
                    }                
                    break;
                case "update": //Checked
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = !empty($data['id']) ? $data['id'] : $this->input->post('id');
                    if($identity==4){
                        $set_update_user_menu=$this->Menu_model->update_data_user_menu($id,$params_update_user_menu);
                    }
                    $set_update=$this->Konfigurasi_model->update_data($table,$id,$params_update);                
                    if($set_update==true){
                        if($identity==6){ //Branch

                            // $data = $this->Branch_model->get_branch($id);                              
                            //Save Image if Exist
                            // $path = FCPATH . $this->folder_upload;
                            // $config['image_library'] = 'gd2';
                            // $config['upload_path'] = $path;
                            // $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            // $this->load->library('upload', $config);
                            // $this->upload->initialize($config);

                            // if ($this->upload->do_upload('upload1')) {
                            //     $upload = $this->upload->data();
                            //     $raw_photo = time() . $upload['file_ext'];
                            //     $old_name = $upload['full_path'];
                            //     $new_name = $path . $raw_photo;
                            //     if (rename($old_name, $new_name)) {
                            //         $compress['image_library'] = 'gd2';
                            //         $compress['source_image'] = $this->folder_upload . $raw_photo;
                            //         $compress['create_thumb'] = FALSE;
                            //         $compress['maintain_ratio'] = TRUE;
                            //         $compress['width'] = $this->image_width;
                            //         $compress['height'] = $this->image_height;                                    
                            //         $compress['new_image'] = $this->folder_upload . $raw_photo;
                            //         $this->load->library('image_lib', $compress);
                            //         $this->image_lib->resize();

                            //         if ($data && $data['branch_id']) {
                            //             $params_image = array(
                            //                 'branch_logo' => $this->folder_upload.$raw_photo
                            //             );
                            //             if (!empty($data['branch_logo'])) {
                            //                 if (file_exists(FCPATH . $data['branch_logo'])) {
                            //                     unlink(FCPATH . $data['branch_logo']);
                            //                 }
                            //             }
                            //             $stat=$this->Konfigurasi_model->update_data($table,$id,$params_image);                                      
                            //         }
                            //     }
                            // }    

                            $branch_id = $id;
                            $get_data = $this->Branch_model->get_branch($id);
                            
                            if(!empty($user)){
                                $this->User_model->update_user($user,array('user_branch_id'=>$branch_id));
                            }
                            
                            //Croppie Upload Image
                            $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                            if(strlen($post_upload) > 10){
                                $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                                if($upload_process->status == 1){
                                    if ($get_data && $get_data['branch_id']) {
                                        $params_image = array(
                                            'branch_logo' => $upload_process->result['file_location'],
                                            'branch_logo_sidebar' => $upload_process->result['file_location'],                                        
                                        );
                                        if($get_data['branch_logo'] !== 'upload/branch/default_logo.png'){
                                            if (!empty($get_data['branch_logo'])) {
                                                if (file_exists(FCPATH . $get_data['branch_logo'])) {
                                                    unlink(FCPATH . $get_data['branch_logo']);
                                                }
                                            }
                                        }
                                        $stat = $this->Branch_model->update_branch($branch_id, $params_image);
                                    }
                                }else{
                                    $return->message = 'Fungsi Gambar gagal';
                                }
                            }
                            //End of Croppie                             
                        }

                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 4,
                            'activity_table' => $table,
                            'activity_table_id' => $id,
                            'activity_text_1' => strtoupper($nama),
                            'activity_text_2' => ucwords(strtolower($nama)),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        $return->status=1;
                        $return->message='Berhasil memperbarui '.$nama;
                    }else{
                        $return->message='Gagal memperbarui';
                    }           
                    break;
                case "delete":
                    $id = $this->input->post('id');
                    $nama = $this->input->post('nama');                                      
                    $set_data=$this->Konfigurasi_model->delete_data($table,$id);
                    if($set_data==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 5,
                            'activity_table' => $table,
                            'activity_table_id' => $id,
                            'activity_text_1' => 'menghapus',
                            'activity_text_2' => ucwords(strtolower($nama)),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);                                          
                            $return->status=1;
                            $return->message='Berhasil menghapus '.$nama;                        
                    }else{
                        $return->message='Gagal menghapus '.$nama;                    
                    }
                    break;
                case "set-active":
                    $id = $this->input->post('id');
                    $kode = $this->input->post('kode');        
                    $nama = $this->input->post('nama');                                
                    $flag = $this->input->post('flag');

                    if($flag==1){
                        $msg='aktifkan '.$nama;
                        $act=7;
                    }else if($flag==4){
                        $msg='menghapus '.$nama;
                        $act=5;
                    }else{
                        $msg='nonaktifkan  '.$nama;
                        $act=8;
                    }

                    $set_data=$this->Konfigurasi_model->update_data($table,$id,array($table_to_prefix.'_flag'=>$flag));
                    if($set_data==true){    
                        //Aktivitas
                        if($identity == 4){
                            $text_code = 'Menu ';
                        }else{
                            $text_code = strtoupper($kode);
                        }
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => $act,
                            'activity_table' => $table,
                            'activity_table_id' => $id,
                            'activity_text_1' => $text_code,
                            'activity_text_2' => ucwords(strtolower($nama)),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);                                          
                        $return->status=1;
                        $return->message='Berhasil '.$msg;
                    }                
                    break;
                case "create-from-modal": //Checked
                    //Check Data Exist
                    $check_exists = $this->Konfigurasi_model->check_data_exist($table,$params_check);
                    if($check_exists==false){
                        $set_data=$this->Konfigurasi_model->add_data($table,$params_modal);
                        if($set_data==true){
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => 2,
                                'activity_table' => $table,
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => strtoupper($data['nama']),
                                'activity_text_2' => ucwords(strtolower($data['nama'])),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);                
                            $return->status=1;
                            $return->message='Success';
                            $return->result= array(
                                'id' => $set_data,
                                'nama' => $data['nama']
                            );                         
                        }
                    }else{
                        $return->message='Kode sudah digunakan';                    
                    }
                    break;
                case "load-account-map":
                    $params = array(
                        'account_map_branch_id' => $session_branch_id,
                        'account_map_flag' => 1
                    );
                    $get_account_map=$this->Account_map_model->get_all_account_map($params,null,null,null,null,null);
                    $result_account_map = array();
                    $map_pembelian      = array(); 
                    $map_penjualan      = array();
                    $map_ar             = array();
                    $map_persediaan     = array(); 
                    $map_lain           = array();
                    $map_payment        = array();                    
                    foreach($get_account_map as $o => $i):

                        //Pembelian
                        if($i['account_map_for_transaction'] == 1){
                            $map_pembelian[$i['account_map_type']] = array(
                                'map_id' => $i['account_map_id'],
                                'map_branch_id' => $i['account_map_branch_id'],
                                'map_for_transaction' => $i['account_map_for_transaction'],
                                'map_type' => $i['account_map_type'],
                                'map_note' => $i['account_map_note'],       
                                'map_flag' => $i['account_map_flag'],          
                                'map_account_id' => $i['account_map_account_id'],
                                'account_id' => $i['account_id'],
                                'account_code' => $i['account_code'],
                                'account_name' => $i['account_name'] 
                            );
                        }

                        //Penjualan
                        if($i['account_map_for_transaction'] == 2){
                            $map_penjualan[$i['account_map_type']] = array(
                                'map_id' => $i['account_map_id'],
                                'map_branch_id' => $i['account_map_branch_id'],
                                'map_for_transaction' => $i['account_map_for_transaction'],
                                'map_type' => $i['account_map_type'],
                                'map_note' => $i['account_map_note'],       
                                'map_flag' => $i['account_map_flag'],          
                                'map_account_id' => $i['account_map_account_id'],
                                'account_id' => $i['account_id'],
                                'account_code' => $i['account_code'],
                                'account_name' => $i['account_name']      
                            );
                        }

                        //Persediaan
                        if($i['account_map_for_transaction'] == 3){
                            $map_persediaan[$i['account_map_type']] = array(
                                'map_id' => $i['account_map_id'],
                                'map_branch_id' => $i['account_map_branch_id'],
                                'map_for_transaction' => $i['account_map_for_transaction'],
                                'map_type' => $i['account_map_type'],
                                'map_note' => $i['account_map_note'],       
                                'map_flag' => $i['account_map_flag'],          
                                'map_account_id' => $i['account_map_account_id'],
                                'account_id' => $i['account_id'],
                                'account_code' => $i['account_code'],
                                'account_name' => $i['account_name']          
                            );
                        }    

                        //AR / AP
                        if($i['account_map_for_transaction'] == 4){
                            $map_ar[$i['account_map_type']] = array(
                                'map_id' => $i['account_map_id'],
                                'map_branch_id' => $i['account_map_branch_id'],
                                'map_for_transaction' => $i['account_map_for_transaction'],
                                'map_type' => $i['account_map_type'],
                                'map_note' => $i['account_map_note'],       
                                'map_flag' => $i['account_map_flag'],          
                                'map_account_id' => $i['account_map_account_id'],
                                'account_id' => $i['account_id'],
                                'account_code' => $i['account_code'],
                                'account_name' => $i['account_name']          
                            );
                        }    

                        //Lain
                        if($i['account_map_for_transaction'] == 10){
                            $map_lain[$i['account_map_type']] = array(
                                'map_id' => $i['account_map_id'],
                                'map_branch_id' => $i['account_map_branch_id'],
                                'map_for_transaction' => $i['account_map_for_transaction'],
                                'map_type' => $i['account_map_type'],
                                'map_note' => $i['account_map_note'],       
                                'map_flag' => $i['account_map_flag'],          
                                'map_account_id' => $i['account_map_account_id'],
                                'account_id' => $i['account_id'],
                                'account_code' => $i['account_code'],
                                'account_name' => $i['account_name']        
                            );
                        }

                        //Payment (Cash / Bank)
                        if($i['account_map_for_transaction'] == 11){
                            $map_payment[$i['account_map_type']] = array(
                                'map_id' => $i['account_map_id'],
                                'map_branch_id' => $i['account_map_branch_id'],
                                'map_for_transaction' => $i['account_map_for_transaction'],
                                'map_type' => $i['account_map_type'],
                                'map_note' => $i['account_map_note'],       
                                'map_flag' => $i['account_map_flag'],          
                                'map_account_id' => $i['account_map_account_id'],
                                'account_id' => $i['account_id'],
                                'account_code' => $i['account_code'],
                                'account_name' => $i['account_name']        
                            );
                        } 
                    endforeach;
                    $result_account_map = array(
                        'purchase' => $map_pembelian,
                        'sales' => $map_penjualan,
                        'inventory' => $map_persediaan,
                        'receivable_payable' => $map_ar,
                        'other' => $map_lain,
                        'payment_method' => $map_payment                        
                    );
                    $return->status = !empty($result_account_map) ? 1 : 0;
                    $return->message = !empty($result_account_map) ? 'Fetch Account Maps' : 'Fail to fetch data';                
                    $return->result=$result_account_map;
                    break;
                case "update-account-map": //Checked
                    $this->form_validation->set_rules('map_id', 'ID', 'required');
                    $this->form_validation->set_rules('account_id', 'Akun', 'required');                
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        // $post_data = $this->input->post('data');
                        // $data = json_decode($post_data, TRUE);
                        $map_id         = !empty($data['map_id']) ? $data['map_id'] : $this->input->post('map_id');
                        $account_id     = !empty($data['account_id']) ? $data['account_id'] : $this->input->post('account_id');                
                        $params = array(
                            'account_map_account_id' => $account_id
                        );
                        // var_dump($params,$map_id);die;
                        $set_update = $this->Account_map_model->update_account_map($map_id, $params);
                        if($set_update){
                            $return->status = 1;
                            $return->message = 'Berhasil memperbarui';
                        }else{
                            $return->message = 'Gagal memperbarui';
                        }
                    }
                    break;
                default:
                    $this->message = 'No Action';
                    break;
            }
        }
        $return->action=$action;
        echo json_encode($return);
        
    }
    function prints($identity){
        $session = $this->session->userdata();
        $session_branch_id  = intval($session['user_data']['branch']['id']);
        $session_user_id    = intval($session['user_data']['user_id']);      
        $session_user_group = intval($session['user_data']['user_group_id']);

        //Smart Dashboard Rule
        // $rule = array('1','3'); //1=Root /SuperAdmin, 3=Director
        // if(in_array($session_user_group,$rule)){
        //     $data['show_price'] = 1;
        // }else{
        //     $data['show_price'] = 0;
        // }

        $group      = $this->input->get('group');
        $group_sub  = $this->input->get('group_sub');        
        $type       = $this->input->get('type');  
        $flag       = $this->input->get('flag');
        $order      = $this->input->get('order'); 
        $dir        = $this->input->get('dir');                        

        $columns = array(
            '1' => 'account_code',            
            '2' => 'account_name',
            '3' => 'account_group_id',
        );
        $columns_dir = array(
            '0' => 'asc',
            '1' => 'desc'
        );
        // $column_type = array(
        //     '0' => 'Barang dan Jasa',
        //     '1' => 'Barang',
        //     '2' => 'Jasa'
        // );

        $data['branch'] = $this->Branch_model->get_branch($session_branch_id);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch($session_branch_id);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $limit  = 0;
        $start  = 0;
        $order  = $columns[$order];
        $dir    = $columns_dir[$dir];
        $search = null;

        $params_datatable = array(
            'account_branch_id' => $session_branch_id
        );  
        $datas            = array();

        if(intval($group) > 0){
            $params_datatable['account_group'] = intval($group);
            $group_array = array(
                '1' => 'Asset',
                '2' => 'Liabilitas',
                '3' => 'Ekuitas',
                '4' => 'Pendapatan',
                '5' => 'Biaya'
            );            
            $data['group'] = $group_array[$group];
        }

        if(intval($group_sub) > 0){
            $get_sub = $this->Account_model->get_account_group($group_sub);
            // $search['category_product_id'] = intval($cat);
            $params_datatable['account_group_sub'] = intval($group_sub);            
            $data['group_sub'] = $get_sub['group_name'];
        }     

        if((is_numeric($flag)) && (intval($flag) > 0)){
            $params_datatable['account_flag'] = intval($flag);
            if($flag==1){
                $data['flag'] = 'Aktif';
            }else if($flag==0){
                $data['flag'] = 'NonAktif';
            }
        }       

        $get_count = $this->Account_model->get_all_account_count($params_datatable,$search);
        // var_dump($params_datatable);die;
        $limit = $get_count;
        if($get_count > 0){
            $get_data = $this->Account_model->get_all_account($params_datatable, $search, $limit, $start, $order, $dir);
            $params = array(
                'activity_user_id' => $session['user_data']['user_id'],
                'activity_action' => 6,
                'activity_table' => 'accounts',
                'activity_table_id' => null,
                'activity_text_1' => '1',
                'activity_text_2' => 'Data Akun Perkiraan',
                'activity_date_created' => date('YmdHis'),
                'activity_flag' => 0
            );
            $this->save_activity($params);                
        }else{
            $get_data = array();
        }
        $data['content'] = $get_data;
        $data['title'] = 'Print Data Akun';
        $data['url'] = site_url();
        $data['description'] = '';
        $data['author'] = 'Author';
        $data['image'] = $data['branch_logo'];        
        // $data['price_item'] = 1;
        $this->load->view('layouts/admin/menu/prints/data/print_account',$data);  
    }
}
