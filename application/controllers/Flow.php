<?php
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Flow extends MY_Controller{

    var $folder_upload = 'uploads/flow/';
    var $folder_location = array(
        '1' => array(
            'title' => 'Pendaftaran WhatsApp',
            'view' => 'layouts/admin/menu/flow/register',
            'javascript' => 'layouts/admin/menu/flow/register_js'
        ),
    );
    function __construct(){
        parent::__construct();

        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Aktivitas_model');
        $this->load->model('User_model');
        $this->load->model('Flow_model');
    }
    function pages($identity){

        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        // $data['account_payable'] = $this->get_account_map_for_transaction($session_branch_id,4,1); //Account Payable
        // $data['account_receivable'] = $this->get_account_map_for_transaction($session_branch_id,4,2); //Account Receivable
        // if($identity == 0){ //All
        //     $data['identity'] = 0;
        //     $data['title'] = 'Kontak';
        //     $data['_view'] = 'contact/contact';
        //     $file_js = 'contact/contact_js.php';
        // }
        // if($identity == 1){ //Supplier
        //     $data['identity'] = 1;
        //     $data['title'] = 'Supplier';
        //     $data['_view'] = 'contact/supplier';
        //     $file_js = 'contact/supplier_js.php';
        // }
        // if($identity == 2){ //Customer
        //     $data['identity'] = 2;
        //     $data['title'] = 'Customer';
        //     $data['_view'] = 'contact/customer';
        //     $file_js = 'contact/customer_js.php';
        // }
        //Account Receivable & Payable
        $params_acc_payable = array(
            'account_branch_id' => $session_branch_id,
            'account_flag' => 1,
            'account_group_sub' => 8
        );
        $params_acc_receivable = array(
            'account_branch_id' => $session_branch_id,
            'account_flag' => 1,
            'account_group_sub' => 3
        );
        // $data['account_payable'] = $this->Account_model->get_all_account($params_acc_payable,null,1,0,null,null);
        // $data['account_receivable'] = $this->Account_model->get_all_account($params_acc_receivable,null,1,0,null,null);

        $data['identity'] = $identity;
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['_view'] = $this->folder_location[$identity]['view'];
        $file_js = $this->folder_location[$identity]['javascript'];

        $data['session'] = $this->session->userdata();
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d");
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        if($identity == 4){
            $data['end_date'] = date('d-m-Y');
        }
        // var_dump($data['account_payable']);die;
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function index(){
        if ($this->input->post()) {    
            $data['session'] = $this->session->userdata();  
            $session_user_id = $data['session']['user_data']['user_id'];

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = FCPATH . $upload_directory;

            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';      

            $post = $this->input->post();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            switch($action){
                case "create":

                    // $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    // $data = json_decode($post_data, TRUE);

                    $this->form_validation->set_rules('flow_name', 'flow_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $this->message = validation_errors();
                    }else{

                        $flow_name = !empty($this->input->post('flow_name')) ? $this->input->post('flow_name') : null;
                        $flow_status = !empty($this->input->post('flow_flag')) ? $this->input->post('flow_flag') : 0;

                        $params = array(
                            'flow_name' => $flow_name,
                            'flow_flag' => $flow_flag
                        );

                        //Check Data Exist
                        $params_check = array(
                            'flow_name' => $flow_name
                        );
                        $check_exists = $this->Flow_model->check_data_exist($params_check);
                        if($check_exists==false){

                            $set_data=$this->Flow_model->add_flow($params);
                            if($set_data==true){

                                $flow_id = $set_data;
                                $data = $this->Flow_model->get_flow($flow_id);
                                //Save Image if Exist
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

                                        if ($data && $data['flow_id']) {
                                            $params_image = array(
                                                'flow_image' => base_url($upload_directory) . $raw_photo
                                            );
                                            if (!empty($data['flow_image'])) {
                                                if (file_exists($upload_path_directory . $data['flow_image'])) {
                                                    unlink($upload_path_directory . $data['flow_image']);
                                                }
                                            }
                                            $stat = $this->Flow_model->update_flow_custom(array('flow_id' => $set_data), $params_image);
                                        }
                                    }
                                }
                                //End of Save Image

                                /* Start Activity */
                                /*
                                $params = array(
                                    'activity_user_id' => $session['user_data']['user_id'],
                                    'activity_action' => 2,
                                    'activity_table' => 'flow',
                                    'activity_table_id' => $set_data,
                                    'activity_text_1' => strtoupper($data['kode']),
                                    'activity_text_2' => ucwords(strtolower($data['nama'])),                        
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 1
                                );
                                $this->save_activity($params);
                                */
                                /* End Activity */
                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['flow_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['flow_name']
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['flow_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    $return->action=$action;
                    echo json_encode($return);
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $flow_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;   
                    if(intval(strlen($flow_id)) > 0){        
                        $datas = $this->Flow_model->get_flow($flow_id);
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'flows',
                                'actvity_table_id' => $flow_id,
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
                            $return->message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message='Data tidak ada';
                    }
                    $return->action=$action;
                    echo json_encode($return);                               
                    break;
                case "update":
                    $this->form_validation->set_rules('flow_id', 'flow_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        //$post_data = $this->input->post('data');
                        //$data = json_decode($post_data, TRUE);
                        $flow_id = !empty($this->input->post('flow_id')) ? $this->input->post('flow_id') : $post['flow_id'];
                        $flow_name = !empty($this->input->post('flow_name')) ? $this->input->post('flow_name') : $post['flow_name'];
                        $flow_flag = !empty($this->input->post('flow_flag')) ? $this->input->post('flow_flag') : $post['flow_flag'];

                        if(strlen($flow_id) > 1){
                            $params = array(
                                'flow_name' => $flow_name,
                                'flow_date_updated' => date("YmdHis"),
                                'flow_flag' => $flow_flag
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Flow_model->update_flow($flow_id,$params);
                            if($set_update==true){
                                
                                $data = $this->Flow_model->get_flow($flow_id);
                                    
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
                                        if ($data && $flow_id) {
                                            $params_image = array(
                                                'flow_image' => base_url($upload_directory) . $raw_photo
                                            );
                                            if (!empty($data['flow_image'])) {
                                                if (file_exists($upload_path_directory . $data['flow_image'])) {
                                                    unlink($upload_path_directory . $data['flow_image']);
                                                }
                                            }
                                            $stat = $this->Flow_model->update_flow_custom(array('flow_id' => $flow_id), $params_image);
                                        }
                                    }
                                }
                                //End of Save Image

                                /* Activity */
                                /*
                                $params = array(
                                    'activity_user_id' => $session['user_data']['user_id'],
                                    'activity_action' => 4,
                                    'activity_table' => 'flows',
                                    'activity_table_id' => $id,
                                    'activity_text_1' => '',
                                    'activity_text_2' => ucwords(strtolower($flow_name),
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 0
                                );
                                $this->save_activity($params);
                                */                    
                                /* End Activity */
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$flow_name;
                            }else{
                                $return->message='Gagal memperbarui '.$flow_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;          
                case "delete":
                    //$post_data = $this->input->post('data');
                    //$data = json_decode($post_data, TRUE);            
                    $flow_id   = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                    $flow_name = !empty($this->input->post('flow_name')) ? $this->input->post('flow_name') : null;                                

                    if(strlen($flow_id) > 0){
                        $get_data=$this->Flow_model->get_flow($flow_id);
                        $set_data=$this->Flow_model->delete_flow($flow_id);
                        if($set_data==true){
                            /*
                            if (file_exists($get_data['flow_image'])) {
                                unlink($get_data['flow_image']);
                            } 
                            */                            
                            /* Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => $act,
                                'activity_table' => 'flows',
                                'activity_table_id' => $id,
                                'activity_text_1' => '',
                                'activity_text_2' => ucwords(strtolower($flow_name)),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);                               
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil menghapus'.$flow_name;
                        }else{
                            $return->message='Gagal menghapus '.$flow_name;
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;
                    echo json_encode($return);
                    break;
                case "load":
                    $columns = array(
                        '0' => 'flow_name',
                        '1' => 'flow_phone'
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
                    
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($this->input->post('date_start')) ? $params['flow_date >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 23:59:59')) : $params;
                    !empty($this->input->post('date_end')) ? $params['flow_date <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['flow_id']   = !empty($this->input->post('flow_id')) ? $this->input->post('flow_id') : $params;
                    // $params['flow_name'] = !empty($this->input->post('flow_name')) ? $this->input->post('flow_name') : $params;                

                    /*
                    if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                        $params['other_column'] = $this->input->post('other_column');
                    }
                    */
                    
                    $get_datas = $this->Flow_model->get_all_flow($params, $search, $limit, $start, $order, $dir);
                    $datas = [];
                    foreach($get_datas AS $v):
                      $datas[] = array(
                        'flow_id' => intval($v['flow_id']),
                        'flow_type' => intval($v['flow_type']),
                        'flow_name' => $v['flow_name'],
                        'flow_phone' => $v['flow_phone'],
                        'flow_data' => $v['flow_data'],
                        'flow_flag' => intval($v['flow_flag']),
                        'flow_date_created' => date("d-M-Y, H:i", strtotime($v['flow_date_created'])),
                        'flow_session' => $v['flow_session'],
                      );
                    endforeach;
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
                default:
                    // Date Now
                    $firstdate = new DateTime('first day of this month');
                    $firstdateofmonth = $firstdate->format('d-m-Y');        
                    $datenow =date("d-m-Y");         
                    $data['first_date'] = $firstdateofmonth;
                    $data['end_date'] = $datenow;      
            }
        }else{
            // Date Now
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('d-m-Y');        
            $datenow =date("d-m-Y");         
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = $datenow;

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            $data['title'] = 'Flow';
            $data['_view'] = 'layouts/admin/menu/folder/flow';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/folder/flow_js.php',$data);
        }
    }
}

?>