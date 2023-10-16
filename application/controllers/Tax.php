<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax extends MY_Controller{

    var $folder_upload = 'uploads/tax/';

    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));              
        }        
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('Tax_model');
        $this->load->model('User_model');

        $this->group_access = array(1); //Super Admin        
    }
    function index(){
        //Group Access View
        $data['session'] = $this->session->userdata();     
        $data['user_group'] = $data['session']['user_data']['user_group_id'];
        $group_access = $this->group_access;
        $set_view=false;
        if(in_array($data['user_group'],$group_access)){
            $set_view = true;
        }        
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

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
            // die;
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'tax_id',
                        '1' => 'tax_name'
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
                        'tax_id >' => 1
                    );
                    
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($post['date_start']) ? $params['tax_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['tax_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['tax_id']   = !empty($post['tax_id']) ? $post['tax_id'] : $params;
                    // $params['tax_name'] = !empty($post['tax_name']) ? $post['tax_name'] : $params;                

                    if($post['filter_flag'] && $post['filter_flag'] > 0) {
                        $params['tax_flag'] = $post['filter_flag'];
                    }
                    
                    $get_data = $this->Tax_model->get_all_tax($params, $search, $limit, $start, $order, $dir);
                    $get_count = $this->Tax_model->get_all_tax_count($params);

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
                    $this->form_validation->set_rules('tax_name', 'Nama', 'required');
                    $this->form_validation->set_rules('tax_percent', 'Persentase', 'required');                    
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $tax_name = !empty($post['tax_name']) ? $post['tax_name'] : null;
                        $tax_percent = !empty($post['tax_percent']) ? $post['tax_percent'] : null;                        
                        $tax_flag = !empty($post['tax_flag']) ? $post['tax_flag'] : 0;

                        $params = array(
                            // 'tax_id' => !empty($post['tax_id']) ? intval($post['tax_id']) : null,
                            'tax_name' => !empty($post['tax_name']) ? $post['tax_name'] : null,
                            'tax_percent' => !empty($post['tax_percent']) ? floatval($post['tax_percent']) : null,
                            // 'tax_decimal_0' => !empty($post['tax_decimal_0']) ? intval($post['tax_decimal_0']) : null,
                            // 'tax_decimal_1' => !empty($post['tax_decimal_1']) ? intval($post['tax_decimal_1']) : null,
                            'tax_flag' => !empty($post['tax_flag']) ? intval($post['tax_flag']) : 0,
                            // 'tax_date_created' => !empty($post['tax_date_created']) ? $post['tax_session'] : null,
                            // 'tax_date_updated' => !empty($post['tax_date_updated']) ? $post['tax_session'] : null,
                            // 'tax_session' => !empty($post['tax_session']) ? $post['tax_session'] : null,
                        );

                        //Check Data Exist
                        $params_check = array(
                            'tax_percent' => $tax_percent
                        );
                        $check_exists = $this->Tax_model->check_data_exist($params_check);
                        if(!$check_exists){

                            $set_data=$this->Tax_model->add_tax($params);
                            if($set_data){

                                $tax_id = $set_data;
                                $get_data = $this->Tax_model->get_tax($tax_id);

                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['tax_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['tax_name'],
                                    'session' => $get_data['tax_session']
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['tax_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('tax_id', 'tax_id', 'required');                
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $tax_id   = !empty($post['tax_id']) ? $post['tax_id'] : 0;   
                        if(intval(strlen($tax_id)) > 0){        
                            $datas = $this->Tax_model->get_tax($tax_id);
                            if($datas==true){
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
                    $this->form_validation->set_rules('tax_name', 'Nama', 'required');
                    $this->form_validation->set_rules('tax_percent', 'Persentase', 'required');                        
                    $this->form_validation->set_rules('tax_id', 'ID', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $tax_id = !empty($post['tax_id']) ? $post['tax_id'] : $post['tax_id'];
                        $tax_name = !empty($post['tax_name']) ? $post['tax_name'] : null;
                        $tax_percent = !empty($post['tax_percent']) ? $post['tax_percent'] : null;                        
                        $tax_flag = !empty($post['tax_flag']) ? $post['tax_flag'] : 0;

                        if(strlen($tax_id) > 0){
                            $params = array(
                                // 'tax_id' => !empty($post['tax_id']) ? intval($post['tax_id']) : null,
                                'tax_name' => !empty($post['tax_name']) ? $post['tax_name'] : null,
                                'tax_percent' => !empty($post['tax_percent']) ? floatval($post['tax_percent']) : null,
                                // 'tax_decimal_0' => !empty($post['tax_decimal_0']) ? intval($post['tax_decimal_0']) : null,
                                // 'tax_decimal_1' => !empty($post['tax_decimal_1']) ? intval($post['tax_decimal_1']) : null,
                                // 'tax_flag' => !empty($post['tax_flag']) ? intval($post['tax_flag']) : 0,
                                // 'tax_date_created' => !empty($post['tax_date_created']) ? $post['tax_session'] : null,
                                // 'tax_date_updated' => !empty($post['tax_date_updated']) ? $post['tax_session'] : null,
                                // 'tax_session' => !empty($post['tax_session']) ? $post['tax_session'] : null,
                            );

                            $set_update=$this->Tax_model->update_tax($tax_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Tax_model->get_tax($tax_id);

                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$tax_name;
                            }else{
                                $return->message='Gagal memperbarui '.$tax_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;          
                case "delete":
                    $this->form_validation->set_rules('tax_id', 'tax_id', 'required');                
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $tax_id   = !empty($post['tax_id']) ? $post['tax_id'] : 0;
                        $tax_name = !empty($post['tax_name']) ? $post['tax_name'] : null;                                

                        if(strlen($tax_id) > 0){
                            $get_data=$this->Tax_model->get_tax($tax_id);
                            // $set_data=$this->Tax_model->delete_tax($tax_id);
                            $set_data = $this->Tax_model->update_tax_custom(array('tax_id'=>$tax_id),array('tax_flag'=>4));                
                            if($set_data==true){    
                                /*
                                if (file_exists($get_data['tax_image'])) {
                                    unlink($get_data['tax_image']);
                                } 
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$tax_name;
                            }else{
                                $return->message='Gagal menghapus '.$tax_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update-flag":
                    $this->form_validation->set_rules('tax_id', 'tax_id', 'required');
                    $this->form_validation->set_rules('tax_flag', 'tax_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $tax_id = !empty($post['tax_id']) ? $post['tax_id'] : 0;
                        if(strlen(intval($tax_id)) > 0){
                            
                            $params = array(
                                'tax_flag' => !empty($post['tax_flag']) ? intval($post['tax_flag']) : 0,
                            );
                            
                            $where = array(
                                'tax_id' => !empty($post['tax_id']) ? intval($post['tax_id']) : 0,
                            );
                            
                            if($post['tax_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['tax_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['tax_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $set_update=$this->Tax_model->update_tax_custom($where,$params);
                            if($set_update==true){
                                $get_data = $this->Tax_model->get_tax_custom($where);
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['tax_name'];
                            }else{
                                $return->message='Gagal '.$set_msg;
                            }   
                        }else{
                            $return->message = 'Gagal mendapatkan data';
                        } 
                        // $return->params = $params;
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
            
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = date("d-m-Y");

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            $data['title'] = 'Pajak';
            $data['_view'] = 'layouts/admin/menu/reference/tax';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/reference/tax_js.php',$data);
        }
    }
}

?>