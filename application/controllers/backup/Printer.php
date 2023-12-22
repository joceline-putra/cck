<?php 
// https://social.technet.microsoft.com/Forums/windows/en-US/3b85dc4f-95b0-41f3-806e-c6f0cbe963a8/print-from-network-shared-printer-using-command-prompt?forum=w7itproinstall
defined('BASEPATH') OR exit('No direct script access allowed');

class Printer extends MY_Controller{

    var $folder_upload = 'uploads/printer/';

    function __construct(){
        parent::__construct();

        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Printer_model');
        $this->load->model('Transaksi_model');        
    }
    function index(){
        if ($this->input->post()) {    
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = FCPATH . $upload_directory;

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'printer_id',
                        '1' => 'printer_name'
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

                    $params = array(
                        'printer_type >' => 0
                    );
                    
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($post['date_start']) ? $params['printer_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['printer_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['printer_id']   = !empty($post['printer_id']) ? $post['printer_id'] : $params;
                    // $params['printer_name'] = !empty($post['printer_name']) ? $post['printer_name'] : $params;                

                    if($post['filter_flag'] && intval($post['filter_flag']) > 0) {
                        $params['printer_flag'] = $post['filter_flag'];
                    }
                    
                    $get_data = $this->Printer_model->get_all_printer($params, $search, $limit, $start, $order, $dir);
                    $get_count = $this->Printer_model->get_all_printer_count($params);

                    if(isset($get_data)){
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = $get_data;
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "create":
                    // $data = base64_decode($post);
                    // $data = json_decode($post, TRUE);

                    $this->form_validation->set_rules('printer_name', 'printer_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $printer_name = !empty($post['printer_name']) ? $post['printer_name'] : null;
                        $printer_flag = !empty($post['printer_flag']) ? $post['printer_flag'] : 0;
                        $printer_session = $this->random_code(20);

                        $params = array(
                            'printer_name' => $printer_name,
                            'printer_flag' => $printer_flag
                        );
                        $params = array(
                            // 'printer_id' => !empty($post['printer_id']) ? intval($post['printer_id']) : null,
                            // 'printer_parent_id' => !empty($post['printer_parent_id']) ? $post['printer_parent_id'] : null,
                            'printer_type' => !empty($post['printer_type']) ? intval($post['printer_type']) : null,
                            'printer_ip' => !empty($post['printer_ip']) ? $post['printer_ip'] : null,
                            'printer_name' => !empty($post['printer_name']) ? $post['printer_name'] : null,
                            'printer_flag' => !empty($post['printer_flag']) ? intval($post['printer_flag']) : 0,
                            'printer_session' => $printer_session,
                            'printer_date_created' => date("YmdHis"),
                            // 'printer_paper_design' => !empty($post['printer_paper_design']) ? $post['printer_paper_design'] : null,
                            // 'printer_paper_width' => !empty($post['printer_paper_width']) ? intval($post['printer_paper_width']) : null,
                            // 'printer_paper_height' => !empty($post['printer_paper_height']) ? intval($post['printer_paper_height']) : null,
                        );

                        //Check Data Exist
                        $params_check = array(
                            'printer_name' => $printer_name
                        );
                        $check_exists = $this->Printer_model->check_data_exist($params_check);
                        if(!$check_exists){

                            $set_data=$this->Printer_model->add_printer($params);
                            if($set_data){

                                $printer_id = $set_data;
                                $data = $this->Printer_model->get_printer($printer_id);

                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['printer_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['printer_name'],
                                    'session' => $printer_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['printer_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $printer_id   = !empty($post['printer_id']) ? $post['printer_id'] : 0;
                        if(intval(strlen($printer_id)) > 0){        
                            $datas = $this->Printer_model->get_printer($printer_id);
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
                case "update":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $printer_id = !empty($post['printer_id']) ? $post['printer_id'] : $post['printer_id'];
                        $printer_name = !empty($post['printer_name']) ? $post['printer_name'] : $post['printer_name'];
                        $printer_flag = !empty($post['printer_flag']) ? $post['printer_flag'] : $post['printer_flag'];

                        if(strlen($printer_id) > 0){
                            $params = array(
                                // 'printer_id' => !empty($post['printer_id']) ? intval($post['printer_id']) : null,
                                // 'printer_parent_id' => !empty($post['printer_parent_id']) ? $post['printer_parent_id'] : null,
                                'printer_type' => !empty($post['printer_type']) ? intval($post['printer_type']) : null,
                                'printer_ip' => !empty($post['printer_ip']) ? $post['printer_ip'] : null,
                                'printer_name' => !empty($post['printer_name']) ? $post['printer_name'] : null,
                                'printer_flag' => !empty($post['printer_flag']) ? intval($post['printer_flag']) : 0,
                                // 'printer_date_created' => date("YmdHis"),
                                // 'printer_paper_design' => !empty($post['printer_paper_design']) ? $post['printer_paper_design'] : null,
                                // 'printer_paper_width' => !empty($post['printer_paper_width']) ? intval($post['printer_paper_width']) : null,
                                // 'printer_paper_height' => !empty($post['printer_paper_height']) ? intval($post['printer_paper_height']) : null,
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Printer_model->update_printer($printer_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Printer_model->get_printer($printer_id);
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$printer_name;
                            }else{
                                $return->message='Gagal memperbarui '.$printer_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $printer_id   = !empty($post['printer_id']) ? $post['printer_id'] : 0;
                        $printer_name = !empty($post['printer_name']) ? $post['printer_name'] : null;

                        if(strlen($printer_id) > 0){
                            $get_data=$this->Printer_model->get_printer($printer_id);
                            // $set_data=$this->Printer_model->delete_printer($printer_id);
                            $set_data = $this->Printer_model->update_printer_custom(array('printer_id'=>$printer_id),array('printer_flag'=>4));                
                            if($set_data){
                                /*
                                if (file_exists($get_data['printer_image'])) {
                                    unlink($get_data['printer_image']);
                                } 
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$printer_name;
                            }else{
                                $return->message='Gagal menghapus '.$printer_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    $this->form_validation->set_rules('printer_flag', 'printer_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $printer_id = !empty($post['printer_id']) ? $post['printer_id'] : 0;
                        if(strlen(intval($printer_id)) > 0){
                            
                            $params = array(
                                'printer_flag' => !empty($post['printer_flag']) ? intval($post['printer_flag']) : 0,
                            );
                            
                            $where = array(
                                'printer_id' => !empty($post['printer_id']) ? intval($post['printer_id']) : 0,
                            );
                            
                            if($post['printer_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['printer_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['printer_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $set_update=$this->Printer_model->update_printer_custom($where,$params);
                            if($set_update){
                                $get_data = $this->Printer_model->get_printer_custom($where);
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['printer_name'];
                            }else{
                                $return->message='Gagal '.$set_msg;
                            }   
                        }else{
                            $return->message = 'Gagal mendapatkan data';
                        } 
                    }
                    break;
                case "create_printer_item":
                    // $data = base64_decode($post);
                    // $data = json_decode($post, TRUE);

                    $this->form_validation->set_rules('printer_parent_id', 'Header Printer', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $printer_item_name = !empty($post['printer_name']) ? $post['printer_name'] : null;
                        $printer_item_flag = !empty($post['printer_flag']) ? $post['printer_flag'] : 0;
                        $printer_item_session = $this->random_code(20);

                        $params = array(
                            // 'printer_id' => !empty($post['printer_id']) ? intval($post['printer_id']) : null,
                            'printer_parent_id' => !empty($post['printer_parent_id']) ? $post['printer_parent_id'] : null,
                            // 'printer_type' => !empty($post['printer_type']) ? intval($post['printer_type']) : null,
                            // 'printer_ip' => !empty($post['printer_ip']) ? $post['printer_ip'] : null,
                            'printer_name' => !empty($post['printer_paper_name']) ? $post['printer_paper_name'] : null,
                            'printer_flag' => 0,
                            'printer_session' => $printer_item_session,                            
                            'printer_date_created' => date("YmdHis"),
                            'printer_paper_design' => !empty($post['printer_paper_design']) ? $post['printer_paper_design'] : null,
                            'printer_paper_width' => !empty($post['printer_paper_width']) ? intval($post['printer_paper_width']) : null,
                            'printer_paper_height' => !empty($post['printer_paper_height']) ? intval($post['printer_paper_height']) : null,
                        );
                        //Check Data Exist
                        $params_check = array(
                            'printer_paper_width' => $post['printer_paper_width'],
                            'printer_paper_height' => $post['printer_paper_height']
                        );
                        $check_exists = $this->Printer_model->check_data_exist($params_check);
                        if(!$check_exists){

                            $set_data=$this->Printer_model->add_printer($params);
                            if($set_data){

                                $printer_item_id = $set_data;
                                $data = $this->Printer_model->get_printer($printer_item_id);
                                $return->status=1;
                                $return->message='Berhasil menambahkan';
                                $return->result= array(
                                    'id' => $set_data,
                                    'session' => $printer_item_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan';
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read_printer_item":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $printer_item_id   = !empty($post['printer_id']) ? $post['printer_id'] : 0;
                        if(intval(strlen($printer_item_id)) > 0){        
                            $datas = $this->Printer_model->get_printer($printer_item_id);
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
                case "update_printer_item":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $printer_item_id = !empty($post['printer_id']) ? $post['printer_id'] : $post['printer_id'];

                        if(strlen($printer_item_id) > 0){
                            $params = array(
                                // 'printer_id' => !empty($post['printer_id']) ? intval($post['printer_id']) : null,
                                // 'printer_parent_id' => !empty($post['printer_parent_id']) ? $post['printer_parent_id'] : null,
                                // 'printer_type' => !empty($post['printer_type']) ? intval($post['printer_type']) : null,
                                // 'printer_ip' => !empty($post['printer_ip']) ? $post['printer_ip'] : null,
                                'printer_name' => !empty($post['printer_paper_name']) ? $post['printer_paper_name'] : null,
                                'printer_paper_design' => !empty($post['printer_paper_design']) ? $post['printer_paper_design'] : null,
                                'printer_paper_width' => !empty($post['printer_paper_width']) ? intval($post['printer_paper_width']) : null,
                                'printer_paper_height' => !empty($post['printer_paper_height']) ? intval($post['printer_paper_height']) : null,
                            );
                           
                            $set_update=$this->Printer_model->update_printer($printer_item_id,$params);
                            if($set_update){
                                $get_data = $this->Printer_model->get_printer($printer_item_id);
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui';
                            }else{
                                $return->message='Gagal memperbarui';
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "update_printer_item_flag":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    $this->form_validation->set_rules('printer_flag', 'printer_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $printer_item_id = !empty($post['printer_id']) ? $post['printer_id'] : 0;
                        if(strlen(intval($printer_item_id)) > 0){
                            
                            $params = array(
                                'printer_flag' => !empty($post['printer_flag']) ? intval($post['printer_flag']) : 0,
                            );
                            
                            $where = array(
                                'printer_id' => !empty($post['printer_id']) ? intval($post['printer_id']) : 0,
                            );
                            
                            if($post['printer_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['printer_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['printer_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $set_update=$this->Printer_model->update_printer_custom($where,$params);
                            if($set_update){
                                $get_data = $this->Printer_model->get_printer_custom($where);
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['printer_name'];
                            }else{
                                $return->message='Gagal '.$set_msg;
                            }   
                        }else{
                            $return->message = 'Gagal mendapatkan data';
                        } 
                    }
                    break;
                case "delete_printer_item":
                    $this->form_validation->set_rules('printer_id', 'printer_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $printer_item_id   = !empty($post['printer_id']) ? $post['printer_id'] : 0;
                        $printer_item_name = !empty($post['printer_name']) ? $post['printer_name'] : null;                                

                        if(strlen($printer_item_id) > 0){
                            $get_data=$this->Printer_model->get_printer($printer_item_id);
                            $set_data=$this->Printer_model->delete_printer($printer_item_id);
                            // $set_data = $this->Printer_model->update_printer_custom(array('printer_id'=>$printer_item_id),array('printer_flag'=>4));                
                            if($set_data){
                                /*
                                if (file_exists($get_data['printer_item_image'])) {
                                    unlink($get_data['printer_item_image']);
                                } 
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$printer_item_name;
                            }else{
                                $return->message='Gagal menghapus '.$printer_item_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "load_printer_item":
                    $columns = array(
                        '0' => 'printer_id',
                        '1' => 'printer_name'
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

                    //Default Params for Master CRUD Form
                    // $params['printer_item_id']   = !empty($post['printer_item_id']) ? $post['printer_item_id'] : $params;
                    // $params['printer_item_name'] = !empty($post['printer_item_name']) ? $post['printer_item_name'] : $params;

                    /*
                    if($post['other_item_column'] && $post['other_item_column'] > 0) {
                        $params['other_item_column'] = $post['other_item_column'];
                    }
                    */
                    
                    $get_data = $this->Printer_model->get_all_printer($params, $search, $limit, $start, $order, $dir);
                    $get_count = $this->Printer_model->get_all_printer_count($params);

                    if(isset($get_data)){
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = $get_data;
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "load_printer_item_2":
                    $params = array(); $total  = 0;
                    $this->form_validation->set_rules('printer_item_printer_id', 'printer_item_printer_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $printer_item_printer_id   = !empty($post['printer_item_printer_id']) ? $post['printer_item_printer_id'] : 0;
                        if(intval(strlen($printer_item_printer_id)) > 0){
                            $params = array(
                                'printer_parent_id' => $printer_item_printer_id
                            );
                            $search = null;
                            $start  = null;
                            $limit  = null;
                            $order  = "printer_id";
                            $dir    = "asc";
                            $get_data = $this->Printer_model->get_all_printer($params, $search, $limit, $start, $order, $dir);
                            if($get_data){
                                $total = count($get_data);
                                $return->status=1;
                                $return->message='Berhasil mendapatkan data';
                                $return->result=$get_data;
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    $return->params          =$params;
                    $return->total_records   = $total;
                    $return->recordsTotal    = $total;
                    $return->recordsFiltered = $total;
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

            $data['title'] = 'Printer';
            $data['_view'] = 'layouts/admin/menu/reference/printer';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/reference/printer_js.php',$data);
        }
    }
    function matrix(){ // Bisa A4

        $trans_items = $this->Transaksi_model->get_all_transaksi_items(null,null,5,0,null,null);
        $data = array(
            'header' => array(
                'title' => 'Print Dot Matrik 21x14',
                'author' => 'Joe Mpdf',
                'subject' => '',
                'description' => '',
                'keywords' => '',
            ),
            'content' => $trans_items,
            'footer' => array(
                'user' => 1
            )
        );

        $data['title']              = 'Print Dot Matrik 21x14';
        $data['set_paper_width']    = 210;
        $data['set_paper_height']   = 140;
        $data['set_font_size']      = 10;
        $data['set_font_family']    = 'monospace';

        // Concept MPDF
        $pdf_config = [
            'orientation' => 'P', // P = 'portrait', L = 'landscape'
            'allow_charset_conversion' => true, // Parse kumpulan karakter teks masukan apa pun dari HTML
            'charset_in' => 'UTF-8', //character set
            'margin_top' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_bottom' => 15,
            // 'format' => 'A4',
            'format' => [$data['set_paper_width'], $data['set_paper_height']], // mengatur ukuran kertas, coba ganti A4, A5,
            'default_font_size' => $data['set_font_size'],
            'default_font' => $data['set_font_family']
        ];     
        $mpdf = new \Mpdf\Mpdf($pdf_config);

        //Configuration
        $header_config = [
            'L' => [
                'content' => 'Header Left',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'C' => [
                'content' => 'Header Center',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'R' => [
                'content' => 'Header Right',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'line' => 0,
        ];
        $footer_config = [
            'L' => [
                'content' => 'Print on '.date("d-M-Y, H:i"),
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],             
            ],
            'C' => [
                'content' => "Halaman {PAGENO} dari {nb}",
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],              
            ],
            'R' => [
                'content' => 'JRN Cloud System',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],            
            ],
            'line' => 0, // That's the relevant parameter
        ];

        $mpdf->SetTitle($data['title']);
        // $mpdf->SetHeader($header_config, 'O');             
        $mpdf->SetFooter($footer_config,'O');
        
        //Watermark
        // $mpdf->watermark_font = 'DejaVuSansCondensed';
        // $mpdf->watermarkTextAlpha = 0.3;            
        // $mpdf->showWatermarkText = true;        
        // $mpdf->SetWatermarkText("Preview Surat Jalan");    

        // Additional Option
        // $mpdf->SetProtection(array('print-highres','print'), 'userpassword', 'mypassword');
        // $mpdf->SetVisibility('visible'); // parameter 'visible', 'hidden', 'printonly', 'screenonly'

        //View
        $view = $this->load->view("layouts/admin/menu/prints/pdf/printer_pdf", $data, true);        
        // $css = file_get_contents('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');        
        // $css = file_get_contents(base_url('assets/core/plugins/bootstrapv3/css/bootstrap.min.css'));
        // $mpdf->WriteHTML($css,1);
        // $mpdf->WriteHTML($view,2);
        $mpdf->WriteHTML($view);        

  
        $mpdf->Output("Printer_.pdf", 'I');
        exit();
    }
    function barcode(){
        $data['title']              = 'Print Barcode 200 x 50';
        $data['set_paper_width']    = 100; //100
        $data['set_paper_height']   = 50; //50
        $data['set_font_size']      = 10;
        $data['set_font_family']    = 'monospace';

        // Concept MPDF
        $pdf_config = [
            'orientation' => 'P', // P = 'portrait', L = 'landscape'
            'allow_charset_conversion' => true, // Parse kumpulan karakter teks masukan apa pun dari HTML
            'charset_in' => 'UTF-8', //character set
            'margin_top' => 6,
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'format' => [$data['set_paper_width'], $data['set_paper_height']], // mengatur ukuran kertas, coba ganti A4, A5,
            'default_font_size' => $data['set_font_size'],
            'default_font' => $data['set_font_family']
        ];     
        $mpdf = new \Mpdf\Mpdf($pdf_config);    

        //Configuration
        $header_config = [
            'L' => [
                'content' => 'Header Left',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'C' => [
                'content' => 'Header Center',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'R' => [
                'content' => 'Header Right',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'line' => 0,
        ];
        $footer_config = [
            'L' => [
                'content' => 'Print on '.date("d-M-Y, H:i"),
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],             
            ],
            'C' => [
                'content' => "Halaman {PAGENO} dari {nb}",
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],              
            ],
            'R' => [
                'content' => 'JRN Cloud System',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],            
            ],
            'line' => 0, // That's the relevant parameter
        ];

        $mpdf->SetTitle($data['title']);
        // $mpdf->SetHeader($header_config, 'O');             
        // $mpdf->SetFooter($footer_config,'O');

        $data['barcode'] = array(
            array('id' => 1, 'code' => '20230526121239'),
            array('id' => 2, 'code' => '20230526121240'), 
            array('id' => 3, 'code' => '20230526121241'), 
            array('id' => 4, 'code' => '20230526121242'),                                    
        );
        $view = '';
        foreach($data['barcode'] as $v){
            $view .= '<table>
                <tbody>
                    <tr>
                        <td>
                            <div style="vertical-align: middle;" class="barcode11">
                                <barcode code="'.$v['code'].'" type="C128C" height="3" size="2" style="padding-right:20px;"/>
                            </div>
                        </td>
                        <td>
                            <div style="vertical-align: middle;" class="barcode11">
                                <barcode code="'.$v['code'].'" type="C128C" height="3" size="2" style="padding-left:20px;"/>
                            </div>
                        </td>                                      
                    </tr>
                    <tr>
                        <td style="text-align:center;font-size:32px;"><b>'.$v['code'].'</b></td>
                        <td style="text-align:center;font-size:34px;"><b>'.$v['code'].'</b></td>                 
                    </tr>
                </tbody>
            </table>';
        }
        
        //View
        // $view = $this->load->view("layouts/admin/menu/prints/pdf/printer_barcode", $data, true); 
        $mpdf->WriteHTML($view);
        $mpdf->Output(); // opens in browser
        // $mpdf->Output('nama_dokumen.pdf', 'I'); // jika file ingin di download gandi "I" ke "D"      
        exit();
    }  
    function struk(){
        $data['title']              = 'Print Struk 58 x auto';
        $data['set_paper_width']    = 56; //100
        $data['set_paper_height']   = 100; //50
        $data['set_font_size']      = 10;
        $data['set_font_family']    = 'monospace';

        // Concept MPDF
        $pdf_config = [
            'orientation' => 'P', // P = 'portrait', L = 'landscape'
            'allow_charset_conversion' => true, // Parse kumpulan karakter teks masukan apa pun dari HTML
            'charset_in' => 'UTF-8', //character set
            'margin_top' => 6,
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_bottom' => 0,
            'format' => [$data['set_paper_width'], $data['set_paper_height']], // mengatur ukuran kertas, coba ganti A4, A5,
            'default_font_size' => $data['set_font_size'],
            'default_font' => $data['set_font_family']
        ];     
        $mpdf = new \Mpdf\Mpdf($pdf_config);    

        //Configuration
        $header_config = [
            'L' => [
                'content' => 'Header Left',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'C' => [
                'content' => 'Header Center',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'R' => [
                'content' => 'Header Right',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],
                'color'=>'#000000'
            ],
            'line' => 0,
        ];
        $footer_config = [
            'L' => [
                'content' => 'Print on '.date("d-M-Y, H:i"),
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],             
            ],
            'C' => [
                'content' => "Halaman {PAGENO} dari {nb}",
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],              
            ],
            'R' => [
                'content' => 'JRN Cloud System',
                'font-size' => $data['set_font_size'],
                'font-style' => '',
                'font-family' => $data['set_font_family'],            
            ],
            'line' => 0, // That's the relevant parameter
        ];

        $mpdf->SetTitle($data['title']);
        // $mpdf->SetHeader($header_config, 'O');             
        // $mpdf->SetFooter($footer_config,'O');

        $data['barcode'] = array(
            array('id' => 1, 'code' => '20230526121239'),
            array('id' => 2, 'code' => '20230526121240'), 
            array('id' => 3, 'code' => '20230526121241'), 
            array('id' => 4, 'code' => '20230526121242'),                                    
        );
        // $view = '';
        // foreach($data['barcode'] as $v){
        //     $view .= '<table>
        //         <tbody>
        //             <tr>
        //                 <td>
        //                     <div style="vertical-align: middle;" class="barcode11">
        //                         <barcode code="'.$v['code'].'" type="C128C" height="3" size="2" style="padding-right:20px;"/>
        //                     </div>
        //                 </td>                                     
        //             </tr>
        //         </tbody>
        //     </table>';
        // }
        $this->load->model('Branch_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Type_model');
        $this->load->model('Print_spoiler_model');        
        $text = '';
        $word_wrap_width = 29;
        $id = 30;
        $get_branch = $this->Branch_model->get_branch(1);
        $get_trans  = $this->Transaksi_model->get_transaksi($id);
        $get_items  = $this->Transaksi_model->get_transaksi_item_custom(array('trans_item_trans_id'=>$get_trans['trans_id']));

        //Process if Data Found
        if($get_trans){
            $paid_type_name = '';
            if($get_trans['trans_paid_type'] > 0){
                $get_type_paid = $this->Type_model->get_type_paid($get_trans['trans_paid_type']);
                $paid_type_name = $get_type_paid['paid_name'];
            }else{
                $paid_type_name = '-'; // Piutang
            }       

            //Header
            $text .= $this->set_wrap_1($get_branch['branch_name']);
            $text .= $this->set_wrap_1($get_branch['branch_address']);
            $text .= $this->set_wrap_1($get_branch['branch_phone_1']);                
            $text .= $this->set_wrap_1($get_trans['trans_number']);
            $text .= $this->set_wrap_1(date("d/m/Y - H:i:s", strtotime($get_trans['trans_date'])));    
            // $text .= $this->set_wrap_2('Cashier',$get_trans['contact_name']);

            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);

            //Content
            $text .= $this->set_wrap_2("Item", "Total");
            $text .= $this->set_line('-',$word_wrap_width);
            foreach($get_items as $v):
                $text .= $v['product_name']."\n";
                $text .= $this->set_wrap_2(' '.number_format($v['trans_item_out_qty'],0,'',',') . ' x '. number_format($v['trans_item_sell_price'],0,'',','), number_format($v['trans_item_sell_total'],0,'',','));            
            endforeach;       

            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);
            $text .= $this->set_wrap_3('SubTotal',':',number_format($get_trans['trans_total'],0,'',','));
            $text .= $this->set_wrap_3('Dibayar',':',number_format($get_trans['trans_received'],0,'',','));
            $text .= $this->set_wrap_3('Kembali',':',number_format($get_trans['trans_change'],0,'',','));     
            $text .= $this->set_wrap_3('Pembayaran',':',$paid_type_name);       
            
            //Footer
            $text .= "\n";
            $text .= $this->set_wrap_1("Terima kasih atas kunjungannya");
            $text .= $this->set_wrap_1("Barang yang sudah di beli tidak dapat ditukar kembali");        

            //Save to Print Spoiler
            $params = array(
                'spoiler_content' => $text, 'spoiler_source_table' => 'trans',
                'spoiler_source_id' => $id, 'spoiler_flag' => 0, 'spoiler_date' => date('YmdHis')
            );
            $this->Print_spoiler_model->add_print_spoiler($params);
        }else{
            $text = "Transaksi tidak ditemukan\n";
        }        
        //View
        // $view = $this->load->view("layouts/admin/menu/prints/pdf/printer_barcode", $data, true); 
        $mpdf->WriteHTML($text);
        $mpdf->Output(); // opens in browser
        // $mpdf->Output('nama_dokumen.pdf', 'I'); // jika file ingin di download gandi "I" ke "D"      
        exit();
    }        

    public function set_line($char, $width) {
        // $lines=array();
        // foreach (explode("\n",wordwrap($kolom1,$len=32)) as $line)
        //     $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        // return implode("\n",$lines)."\n";
        $ret = '';
        for($a=0; $a<$width; $a++){
            $ret .= $char;
        }
        return $ret."\n";
    }
    public function set_wrap_1($kolom1) {
        $lines=array();
        foreach (explode("\n",wordwrap($kolom1,$len=28)) as $line)
            $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        return implode("\n",$lines)."\n";
    }
    public function set_wrap_2($kolom1, $kolom2) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 15;
        $lebar_kolom_2 = 13;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            // $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            // $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    public function set_wrap_3($kolom1, $kolom2, $kolom3) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 14;
        $lebar_kolom_2 = 1;
        $lebar_kolom_3 = 12;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    public function set_wrap_4($kolom1, $kolom2, $kolom3, $kolom4) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 12;
        $lebar_kolom_2 = 8;
        $lebar_kolom_3 = 8;
        $lebar_kolom_4 = 9;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
        $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);
        $kolom4Array = explode("\n", $kolom4);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }    
}

?>