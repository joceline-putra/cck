<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referensi extends MY_Controller{
    /* 
        Table References (ref_type)
        1 = Diagnose
        2 = Jenis Praktik
        3 = Golongan Barang (KLN)
        4 = Jenis Sakit (KLN)
        5 = Jenis Lab (KLN)
        6 = Jenis Inventaris
        7 = Room / Meja
        8 = Due Date / Pay Termin
        9 = Label -> trans_label, order_label
    */
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('User_model');           
        $this->load->model('Branch_model');                   
        // $this->load->model('Satuan_model');
        $this->load->model('Referensi_model');     
        $this->load->model('Ref_model');             
        $this->group_access = array(1,2); //Root, Admin
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
        $data['_view'] = 'layouts/admin/menu/reference/statistic';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/reference/statistic_js',$data);        
    }  
    function pages($identity){
        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
        $data['user_group'] = $data['session']['user_data']['user_group_id'];
        $group_access = $this->group_access;
        $set_view=false;
        if(in_array($data['user_group'],$group_access)){
            $set_view = true;
        }

        if($set_view){
            if($identity == 1){ //Diagnosa
                // $data['satuan'] = $this->Satuan_model->get_all_satuan();  
                $data['title'] = 'Diagnosa';
                $data['_view'] = 'layouts/admin/menu/reference/diagnose';
                $file_js = 'layouts/admin/menu/reference/diagnose_js.php';
            }else if($identity == 2){ //Jenis Praktek
                // $data['satuan'] = $this->Satuan_model->get_all_satuan();              
                // $data['referensi'] = $this->Referensi_model->get_all_referensi(array('tipe'=>6));
                $data['title'] = 'Jenis Praktik';
                $data['_view'] = 'layouts/admin/menu/reference/practice_type';
                $file_js = 'layouts/admin/menu/reference/practice_type_js.php';
            }else if($identity == 3){ //Golongan Obat
                $data['title'] = 'Golongan Barang';
                $data['_view'] = 'layouts/admin/menu/reference/group_of_goods';
                $file_js = 'layouts/admin/menu/reference/group_of_goods_js.php';
            }else if($identity == 7){ //Ruangan / Meja
                $data['title'] = 'Ruangan';
                $data['_view'] = 'layouts/admin/menu/reference/room';
                $file_js = 'layouts/admin/menu/reference/room_js.php';
            }else if($identity == 8){ //Date
                // $data['title'] = 'Label';
                // $data['_view'] = 'layouts/admin/menu/reference/label';
                // $file_js = 'layouts/admin/menu/reference/label_js.php';
                $data['title'] = '404';    
                $data['_view'] = 'layouts/admin/404'; 
                $file_js = 'layouts/admin/js.php';                
            }else if($identity == 9){ //Label
                $data['title'] = 'Label';
                $data['_view'] = 'layouts/admin/menu/reference/label';
                $file_js = 'layouts/admin/menu/reference/label_js.php';
            }else if($identity == 10){ //Room Type
                $data['title'] = 'Jenis Kamar';
                $data['_view'] = 'layouts/admin/menu/reference/room_type';
                $file_js = 'layouts/admin/menu/reference/room_type_js.php';
            }
            $data['identity']   = $identity;            
        }else{
            $data['title'] = '505';    
            $data['_view'] = 'layouts/admin/505'; 
            $file_js = 'layouts/admin/js.php';
        }

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow            = date("Y-m-d"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date']   = $datenow;
        $data['branch'] = $this->Branch_model->get_all_branch(['branch_flag' => 1],null,null,null,'branch_name','asc');
        
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function manage(){
        $session            = $this->session->userdata();
        $session_branch_id  = $session['user_data']['branch']['id'];
        $session_user_id    = $session['user_data']['user_id'];       
        $session_group_id   = intval($session['user_data']['user_group_id']);

        $return = new \stdClass();
        $return->status     = 0;
        $return->message    = '';
        $return->result     = '';
        $user_id            = $session['user_data']['user_id'];

        if($this->input->post('action')){
            $action     = $this->input->post('action');
            $post_data  = $this->input->post('data');
            $data       = json_decode($post_data, TRUE);
            $identity   = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];

            //Produk ID or TIPE
            if($identity == 1){ //Diagnosa
                $set_tipe = 1;
                
                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $status = !empty($data['status']) ? $data['status'] : null;         

                $params = array(
                    'ref_type' => $identity,
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan, 
                    'ref_date_created' => date("YmdHis"),
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status
                );               
                $columns = array(
                    '0' => 'ref_code',
                    '1' => 'ref_name',
                    '2' => 'ref_note'
                );                                      
            }else if($identity == 2){ //Jenis Praktik
                $set_tipe = 2;
                
                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $status = !empty($data['status']) ? $data['status'] : null;       

                $params = array(
                    'ref_type' => $identity,
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan, 
                    'ref_date_created' => date("YmdHis"),
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status
                );
                $columns = array(
                    '0' => 'ref_code',
                    '1' => 'ref_name',
                    '2' => 'ref_note'
                );                     
            }else if($identity == 3){ //Golongan Obat
                $set_tipe = 3;

                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $status = !empty($data['status']) ? $data['status'] : null;       

                $params = array(
                    'ref_type' => $identity,
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan, 
                    'ref_date_created' => date("YmdHis"),
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status
                );
                $columns = array(
                    '0' => 'ref_code',
                    '1' => 'ref_name',
                    '2' => 'ref_note'
                );                      
            }else if($identity == 7){ //Room / Meja
                $set_tipe = 7;

                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $status = !empty($data['status']) ? $data['status'] : null;       

                $params = array(
                    'ref_type' => $identity,
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan,         
                    'ref_date_created' => date("YmdHis"),
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status,
                    'ref_user_id' => $session_user_id,
                    'ref_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                    'ref_icon' => !empty($data['ref_icon']) ? $data['ref_icon'] : 'fas fa-lock',
                    'ref_use_type' => 0               
                );
                $params_update = array(
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan,  
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status,
                    'ref_icon' => !empty($data['ref_icon']) ? $data['ref_icon'] : 'fas fa-lock'                    
                );                                 
                $columns = array(
                    '0' => 'ref_code',
                    '1' => 'ref_name',
                    '2' => 'ref_note'
                );
                $table = 'reference';
            }else if($identity == 8){ //Date
                $set_tipe = 8;

                $kode = !empty($data['kode']) ? $data['kode'] : null;
                $nama = !empty($data['nama']) ? $data['nama'] : null;   
                $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $status = !empty($data['status']) ? $data['status'] : null;       

                $params = array(
                    'ref_type' => $identity,
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan,         
                    'ref_date_created' => date("YmdHis"),
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status,
                    'ref_user_id' => $user_id
                );
                $params_update = array(
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan,  
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status
                );                                 
                $columns = array(
                    '0' => 'ref_code',
                    '1' => 'ref_name',
                    '2' => 'ref_note'
                );
                $table = 'reference';
            }else if($identity == 9){ //Label
                $set_tipe = 9;

                $kode       = !empty($data['ref_name']) ? $data['ref_name'] : '-';
                $nama       = !empty($data['ref_name']) ? $data['ref_name'] : null;   
                $keterangan = !empty($data['ref_note']) ? $data['ref_note'] : null;  
                $status     = !empty($data['status']) ? $data['status'] : null;       

                $data['kode'] = !empty($data['ref_name']) ? $data['ref_name'] : null;
                $data['nama'] = !empty($data['ref_name']) ? $data['ref_name'] : null;
                $data['keterangan'] = $keterangan;

                // var_dump($data['nama']);die;
                $params = array(
                    'ref_type' => $identity,
                    'ref_code' => $kode,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan,         
                    'ref_date_created' => date("YmdHis"),
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status,
                    'ref_user_id' => $user_id,
                    'ref_color' => !empty($data['ref_color']) ? $data['ref_color'] : null,
                    'ref_background' => !empty($data['ref_background']) ? $data['ref_background'] : null
                );
                $params_update = array(
                    'ref_code' => null,
                    'ref_name' => $nama,
                    'ref_note' => $keterangan,  
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $status,
                    'ref_color' => !empty($data['ref_color']) ? $data['ref_color'] : null,
                    'ref_background' => !empty($data['ref_background']) ? $data['ref_background'] : null                    
                );                                 
                $columns = array(
                    '0' => 'ref_code',
                    '1' => 'ref_name',
                    '2' => 'ref_note'
                );
                $table = 'reference';
            }else if($identity == 10){ //Room Type
                $set_tipe = 10;
                $data['nama'] = !empty($data['nama']) ? $data['nama'] : null;
                $data['keterangan'] = !empty($data['keterangan']) ? $data['keterangan'] : null;  
                $data['status'] = !empty($data['status']) ? $data['status'] : null;     
                $data['ref_branch_id'] = !empty($data['ref_branch_id']) ? $data['ref_branch_id'] : null;                                   

                $params = array(
                    'ref_type' => $identity,
                    'ref_branch_id' => $data['ref_branch_id'],
                    'ref_name' => $data['nama'],
                    'ref_note' => $data['keterangan'],         
                    'ref_date_created' => date("YmdHis"),
                );
                $params_update = array(
                    'ref_branch_id' => $data['ref_branch_id'],
                    'ref_name' => $data['nama'],
                    'ref_note' => $data['keterangan'],  
                    'ref_date_updated' => date("YmdHis"),
                    'ref_flag' => $data['status'],
                );                        
                $columns = array(
                    '0' => 'ref_name',
                    '1' => 'ref_note',
                    '2' => 'branch_name',
                );
                $table = 'reference';
            }

            switch($action){
                case "load":
                    $limit      = $this->input->post('length');
                    $start      = $this->input->post('start');
                    $order      = $columns[$this->input->post('order')[0]['column']];
                    $dir        = $this->input->post('order')[0]['dir'];

                    $search = [];
                    if (isset($this->input->post('search')['value'])) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array(
                        'ref_type' => $set_tipe
                    );

                    if($identity == 7){ //Room / Table
                        //Root Access
                        // if($session_group_id > 1){ //If Not Root
                            $params['ref_branch_id'] = intval($session_branch_id);
                        // }
                    }

                    $datas_result = array();
                    $datas_count = $this->Referensi_model->get_all_referensis_count($params,$search);
                    
                    if($datas_count > 0){
                        $datas = $this->Referensi_model->get_all_referensis($params, $search, $limit, $start, $order, $dir);
                        $return->status         = 1; 
                        $return->message        = 'Loaded'; 
                        $return->result         = $datas;        
                    }else{ 
                        $return->message        = 'No data'; 
                        $return->result         = $datas_result;    
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->total_records      = $datas_count;
                    $return->params             = $params;
                    $return->search             = $search;
                    break;              
                case "load_ref_room_type":
                    $limit      = $this->input->post('length');
                    $start      = $this->input->post('start');
                    $order      = $columns[$this->input->post('order')[0]['column']];
                    $dir        = $this->input->post('order')[0]['dir'];

                    $search = [];
                    if (isset($this->input->post('search')['value'])) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    $params = array(
                        'ref_type' => $set_tipe
                    );

                    if($identity == 7){ //Room / Table
                        //Root Access
                        // if($session_group_id > 1){ //If Not Root
                            $params['ref_branch_id'] = intval($session_branch_id);
                        // }
                    }

                    $datas_result = array();
                    $datas_count = $this->Referensi_model->get_all_referensis_count($params,$search);
                    
                    if($datas_count > 0){
                        $datas = $this->Referensi_model->get_all_referensis($params, $search, $limit, $start, $order, $dir);
                        $return->status         = 1; 
                        $return->message        = 'Loaded'; 
                        $return->result         = $datas;        
                    }else{ 
                        $return->message        = 'No data'; 
                        $return->result         = $datas_result;    
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->total_records      = $datas_count;
                    $return->params             = $params;
                    $return->search             = $search;
                    break;                                          
                case "create":
                    //Check Data Exist
                    $params_check = array(
                        'ref_type' => $data['tipe'],
                        // 'ref_code' => $data['kode'],
                        'ref_name' => $data['nama']
                    );

                    if($identity == 10){
                        $params_check = $params_check;
                    }else{
                        $params_check['ref_branch_id'] = intval($session_branch_id);
                    }
                    $check_exists = $this->Referensi_model->check_data_exist($params_check);
                    if($check_exists==false){
                        $set_data=$this->Referensi_model->add_referensi($params);
                        if($set_data==true){
                            if($identity == 10){ //Room Type update price
                                $do0 = $this->Ref_model->add_ref_price(['price_ref_id' => $set_data, 'price_name' => 'Bulanan', 'price_sort' => 0, 'price_value' => $data['order_ref_price_id_0']]);
                                $do1 = $this->Ref_model->add_ref_price(['price_ref_id' => $set_data, 'price_name' => 'Harian', 'price_sort' => 1, 'price_value' => $data['order_ref_price_id_1']]);                            
                                $do2 = $this->Ref_model->add_ref_price(['price_ref_id' => $set_data, 'price_name' => 'Midnight', 'price_sort' => 2, 'price_value' => $data['order_ref_price_id_2']]);
                                $do3 = $this->Ref_model->add_ref_price(['price_ref_id' => $set_data, 'price_name' => '4 Jam', 'price_sort' => 3, 'price_value' => $data['order_ref_price_id_3']]);
                                $do4 = $this->Ref_model->add_ref_price(['price_ref_id' => $set_data, 'price_name' => '2 Jam', 'price_sort' => 4, 'price_value' => $data['order_ref_price_id_4']]);                                                                                    
                            }                            
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 2,
                                'activity_table' => 'reference',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => !empty($data['kode']) ? strtoupper($data['kode']) : '',
                                'activity_text_2' => ucwords(strtolower($data['nama'])),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);                
                            $return->status=1;
                            $return->message='Berhasil menyimpan '.$data['nama'];
                            $return->result= array(
                                'id' => $set_data,
                                'kode' => !empty($data['kode']) ? strtoupper($data['kode']) : ''
                            );                         
                        }
                    }else{
                        $return->message='Kode sudah digunakan';                    
                    }
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $data['id'] = $this->input->post('id');           
                    $datas = $this->Referensi_model->get_referensi($data['id']);
                    if($datas==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 3,
                            'activity_table' => 'reference',
                            'activity_table_id' => $datas['ref_id'],
                            'activity_text_1' => strtoupper($datas['ref_code']),
                            'activity_text_2' => ucwords(strtolower($datas['ref_name'])),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);                    
                        $return->status=1;
                        $return->message='Success';
                        $return->result=$datas;

                        if($identity == 10){
                            $return->result_price = $this->Ref_model->get_ref_price_custom_result(['price_ref_id' => $data['id']]);
                        }
                    }                
                    break;
                case "update":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    // var_dump($data['nama']);die;
                    // $params = array(
                    //     'ref_name' => $data['nama'],
                    //     'ref_note' => $data['keterangan'],
                    //     'ref_date_updated' => date("YmdHis"),
                    //     'ref_flag' => $data['status'],
                    //     'ref_color' => !empty($data['ref_color']) ? $data['ref_color'] : null,
                    //     'ref_background' => !empty($data['ref_background']) ? $data['ref_background'] : null
                    // );
                    // var_dump($params_update);
                    $set_update=$this->Referensi_model->update_referensi($id,$params_update);
                    if($set_update==true){
                        
                        if($identity == 10){ //Room Type update price
                            $do0 = $this->Ref_model->update_ref_price_custom(['price_ref_id' => $id, 'price_sort' => 0],['price_value' => $data['order_ref_price_id_0']]);
                            $do1 = $this->Ref_model->update_ref_price_custom(['price_ref_id' => $id, 'price_sort' => 1],['price_value' => $data['order_ref_price_id_1']]);                            
                            $do2 = $this->Ref_model->update_ref_price_custom(['price_ref_id' => $id, 'price_sort' => 2],['price_value' => $data['order_ref_price_id_2']]);
                            $do3 = $this->Ref_model->update_ref_price_custom(['price_ref_id' => $id, 'price_sort' => 3],['price_value' => $data['order_ref_price_id_3']]);
                            $do4 = $this->Ref_model->update_ref_price_custom(['price_ref_id' => $id, 'price_sort' => 4],['price_value' => $data['order_ref_price_id_4']]);                                                                                    
                        }

                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,                        
                            'activity_action' => 4,
                            'activity_table' => 'reference',
                            'activity_table_id' => $id,
                            'activity_text_1' => !empty($data['kode']) ? strtoupper($data['kode']) : '',
                            'activity_text_2' => ucwords(strtolower($data['nama'])),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        $return->status=1;
                        $return->message='Berhasil memperbarui '.$data['nama'];
                    }
                    break;
                case "delete":
                    $return->message='Delete no action';
                    break;     
                case "set-active":
                    $id = $this->input->post('id');
                    $kode = $this->input->post('kode');        
                    $nama = $this->input->post('nama');                                
                    $flag = $this->input->post('flag');

                    if($flag==1){
                        $msg='aktifkan '.$nama;
                        $act=7;
                    }else{
                        $msg='nonaktifkan  '.$nama;
                        $act=8;
                    }

                    $set_data=$this->Referensi_model->update_referensi($id,array('ref_flag'=>$flag));
                    if($set_data==true){    
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => $act,
                            'activity_table' => 'reference',
                            'activity_table_id' => $id,
                            'activity_text_1' => strtoupper($kode),
                            'activity_text_2' => ucwords(strtolower($nama)),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);                                          
                        $return->status=1;
                        $return->message='Berhasil '.$msg;
                    }                
                    break;
            }
        }else{
            $return->message='No Action';
        }
        $return->action=$action;
        echo json_encode($return);   
    }               
}
