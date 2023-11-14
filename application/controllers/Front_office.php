<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Front_office extends MY_Controller{

    var $folder_upload = 'upload/booking/';
    var $image_width   = 250;
    var $image_height  = 250;

    function __construct(){
        parent::__construct();
        /*
        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
        */
        $this->load->model('Front_model');
        $this->load->model('User_model');
    }
    function booking(){
        if ($this->input->post()) {
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'booking_id',
                        '1' => 'booking_name'
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
                    
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($post['date_start']) ? $params['booking_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['booking_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;
                    */

                    //Default Params for Master CRUD Form
                    $params['booking_id']   = !empty($post['booking_id']) ? $post['booking_id'] : $params;
                    $params['booking_name'] = !empty($post['booking_name']) ? $post['booking_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['booking_type'] = $post['filter_type'];
                        }
                    */
                    
                    $get_count = $this->Front_model->get_all_booking_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking($params, $search, $limit, $start, $order, $dir);
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
                    $this->form_validation->set_rules('booking_id', 'booking_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        //Check Data Exist
                        $params_check = array(
                            'booking_name' => !empty($post['booking_name']) ? $post['booking_name'] : null
                        );

                        if(intval($post['booking_id']) > 0){ /* Update if Exist */ // if( (!empty($post['booking_session'])) && (strlen($post['booking_session']) > 10) ){ /* Update if Exist */      

                            /* Check Existing Data */
                            $params_check = [
                                'booking_name' => !empty($post['booking_name']) ? $post['booking_name'] : null
                            ];

                            $where_not = [
                                'booking_id' => intval($post['booking_id']),                   
                            ];                            
                            $where_new = [
                                'booking_name' => 'Sel',
                                'booking_flag' => 1
                            ];
                            $check_exists = $this->Front_model->check_data_exist_two_condition($where_not,$where_new);

                            /* Continue Update if not exist */
                            if(!$check_exists){
                                $params = array(
                                    'booking_name' => !empty($post['booking_name']) ? $post['booking_name'] : null,
                                    'booking_flag' => !empty($post['booking_flag']) ? $post['booking_flag'] : 0
                                );
                                $create = $this->Front_model->add_booking($params);   
                                if($create){
                                    $get_booking = $this->Front_model->get_booking($create);
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$post['booking_name'];
                                    $return->result= array(
                                        'booking_id' => $create,
                                        'booking_name' => $get_booking['booking_name'],
                                        'booking_session' => $get_booking['booking_session']
                                    );                                
                                }else{
                                    $return->message = 'Gagal menambahkan '.$post['booking_name'];
                                }
                            }else{
                                $return->message = 'Data sudah digunakan';
                            }
                        }else{ /* Save New Data */

                            /* Check Existing Data */
                            $params_check = [
                                'booking_name' => !empty($post['booking_name']) ? $post['booking_name'] : null
                            ];
                            $check_exists = $this->Front_model->check_data_exist($params_check);

                            /* Continue Save if not exist */
                            if(!$check_exists){
                                $booking_session = $this->random_session(20);
                                $params = array(
                                    'booking_session' => $booking_session,
                                    'booking_name' => !empty($post['booking_name']) ? $post['booking_name'] : null,
                                    'booking_flag' => !empty($post['booking_flag']) ? $post['booking_flag'] : 0
                                );
                                $create = $this->Front_model->add_booking($params);   
                                if($create){
                                    $get_booking = $this->Front_model->get_booking($create);
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$post['booking_name'];
                                    $return->result= array(
                                        'booking_id' => $create,
                                        'booking_name' => $get_booking['booking_name'],
                                        'booking_session' => $get_booking['booking_session']
                                    );                                
                                }else{
                                    $return->message = 'Gagal menambahkan '.$post['booking_name'];
                                }
                            }else{
                                $return->message = 'Data sudah ada';
                            }                         
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $booking_id   = !empty($post['booking_id']) ? $post['booking_id'] : 0;
                        if(intval(strlen($booking_id)) > 0){        
                            $datas = $this->Front_model->get_booking($booking_id);
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
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_id = !empty($post['booking_id']) ? $post['booking_id'] : $post['booking_id'];
                        $booking_name = !empty($post['booking_name']) ? $post['booking_name'] : $post['booking_name'];
                        $booking_flag = !empty($post['booking_flag']) ? $post['booking_flag'] : $post['booking_flag'];

                        if(strlen($booking_id) > 1){
                            $params = array(
                                'booking_name' => $booking_name,
                                'booking_date_updated' => date("YmdHis"),
                                'booking_flag' => $booking_flag
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Front_model->update_booking($booking_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Front_model->get_booking($booking_id);
                                    
                                //Update Image if Exist
                                $post_files = !empty($_FILES) ? $_FILES['files'] : "";
                                if(!empty($post_files)){
                                    $config['image_library'] = 'gd2';
                                    $config['upload_path'] = $upload_path_directory;
                                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
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
                                            $compress['width'] = $this->image_width;
                                            $compress['height'] = $this->image_height;
                                            $compress['new_image'] = $upload_path_directory . $raw_photo;
                                            $this->load->library('image_lib', $compress);
                                            $this->image_lib->resize();
                                            if ($get_data) {
                                                $params_image = array(
                                                    'booking_image' => base_url($upload_directory) . $raw_photo
                                                );
                                                if (!empty($get_data['booking_image'])) {
                                                    $file = FCPATH.$this->folder_upload.$get_data['booking_image'];
                                                    if (file_exists($file)) {
                                                        unlink($file);
                                                    }
                                                }
                                                $stat = $this->Front_model->update_booking_custom(array('booking_id' => $booking_id), $params_image);
                                            }
                                        }
                                    }
                                }
                                //End of Save Image

                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$booking_name;
                            }else{
                                $return->message='Gagal memperbarui '.$booking_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_id   = !empty($post['booking_id']) ? $post['booking_id'] : 0;
                        $booking_name = !empty($post['booking_name']) ? $post['booking_name'] : null;

                        if(strlen($booking_id) > 0){
                            $get_data=$this->Front_model->get_booking($booking_id);
                            // $set_data=$this->Front_model->delete_booking($booking_id);
                            $set_data = $this->Front_model->update_booking_custom(array('booking_id'=>$booking_id),array('booking_flag'=>4));                
                            if($set_data){
                                /*
                                $file = FCPATH.$this->folder_upload.$get_data['booking_image'];
                                if (file_exists($file)) {
                                    unlink($file);
                                }
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$booking_name;
                            }else{
                                $return->message='Gagal menghapus '.$booking_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    $this->form_validation->set_rules('booking_flag', 'booking_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_id = !empty($post['booking_id']) ? $post['booking_id'] : 0;
                        if(strlen(intval($booking_id)) > 1){
                            
                            $params = array(
                                'booking_flag' => !empty($post['booking_flag']) ? intval($post['booking_flag']) : 0,
                            );
                            
                            $where = array(
                                'booking_id' => !empty($post['booking_id']) ? intval($post['booking_id']) : 0,
                            );
                            
                            if($post['booking_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['booking_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['booking_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            if($post['booking_flag'] == 4){
                                $params['booking_url'] = null;
                            }

                            $get_data = $this->Front_model->get_booking_custom($where);
                            if($get_data){
                                $set_update=$this->Front_model->update_booking_custom($where,$params);
                                if($set_update){
                                    if($post['booking_flag'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['booking_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['booking_name'];
                                }else{
                                    $return->message='Gagal '.$set_msg;
                                }
                            }else{
                                $return->message='Gagal mendapatkan data';
                            }   
                        }else{
                            $return->message = 'Tidak ada data';
                        } 
                    }
                    break;
                case "create_booking_item":
                    // $data = base64_decode($post);
                    // $data = json_decode($post, TRUE);

                    $this->form_validation->set_rules('booking_item_name', 'booking_item_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $booking_item_name = !empty($post['booking_item_name']) ? $post['booking_item_name'] : null;
                        $booking_item_flag = !empty($post['booking_item_flag']) ? $post['booking_item_flag'] : 0;
                        $booking_item_session = $this->random_code(20);

                        $params = array(
                            'booking_item_name' => $booking_item_name,
                            'booking_item_flag' => $booking_item_flag
                        );

                        //Check Data Exist
                        $params_check = array(
                            'booking_item_name' => $booking_item_name
                        );
                        $check_exists = $this->Front_model->check_data_exist_booking_item($params_check);
                        if(!$check_exists){

                            $set_data=$this->Front_model->add_booking_item($params);
                            if($set_data){

                                $booking_item_id = $set_data;
                                $data = $this->Front_model->get_booking_item($booking_item_id);
                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['booking_item_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['booking_item_name'],
                                    'session' => $booking_item_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['booking_item_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read_booking_item":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $booking_item_id   = !empty($post['booking_item_id']) ? $post['booking_item_id'] : 0;
                        if(intval(strlen($booking_item_id)) > 0){        
                            $datas = $this->Front_model->get_booking_item($booking_item_id);
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
                case "update_booking_item":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_id = !empty($post['booking_item_id']) ? $post['booking_item_id'] : $post['booking_item_id'];
                        $booking_item_name = !empty($post['booking_item_name']) ? $post['booking_item_name'] : $post['booking_item_name'];
                        $booking_item_flag = !empty($post['booking_item_flag']) ? $post['booking_item_flag'] : $post['booking_item_flag'];

                        if(strlen($booking_item_id) > 0){
                            $params = array(
                                'booking_item_name' => $booking_item_name,
                                'booking_item_date_updated' => date("YmdHis"),
                                'booking_item_flag' => $booking_item_flag
                            );
                           
                            $set_update=$this->Front_model->update_booking_item($booking_item_id,$params);
                            if($set_update){
                                $get_data = $this->Front_model->get_booking_item($booking_item_id);
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$booking_item_name;
                            }else{
                                $return->message='Gagal memperbarui '.$booking_item_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "update_booking_item_flag":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    $this->form_validation->set_rules('booking_item_flag', 'booking_item_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_id = !empty($post['booking_item_id']) ? $post['booking_item_id'] : 0;
                        if(strlen(intval($booking_item_id)) > 0){
                            
                            $params = array(
                                'booking_item_flag' => !empty($post['booking_item_flag']) ? intval($post['booking_item_flag']) : 0,
                            );
                            
                            $where = array(
                                'booking_item_id' => !empty($post['booking_item_id']) ? intval($post['booking_item_id']) : 0,
                            );
                            
                            if($post['booking_item_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['booking_item_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['booking_item_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $set_update=$this->Front_model->update_booking_item_custom($where,$params);
                            if($set_update){
                                $get_data = $this->Front_model->get_booking_item_custom($where);
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['booking_item_name'];
                            }else{
                                $return->message='Gagal '.$set_msg;
                            }   
                        }else{
                            $return->message = 'Gagal mendapatkan data';
                        } 
                    }
                    break;
                case "delete_booking_item":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_id   = !empty($post['booking_item_id']) ? $post['booking_item_id'] : 0;
                        $booking_item_name = !empty($post['booking_item_name']) ? $post['booking_item_name'] : null;                                

                        if(strlen($booking_item_id) > 0){
                            $get_data=$this->Front_model->get_booking_item($booking_item_id);
                            // $set_data=$this->Front_model->delete_booking_item($booking_item_id);
                            $set_data = $this->Front_model->update_booking_item_custom(array('booking_item_id'=>$booking_item_id),array('booking_item_flag'=>4));                
                            if($set_data){
                                /*
                                if (file_exists($get_data['booking_item_image'])) {
                                    unlink($get_data['booking_item_image']);
                                } 
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$booking_item_name;
                            }else{
                                $return->message='Gagal menghapus '.$booking_item_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "load_booking_item":
                    $columns = array(
                        '0' => 'booking_item_id',
                        '1' => 'booking_item_name'
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
                    $params['booking_item_id']   = !empty($post['booking_item_id']) ? $post['booking_item_id'] : $params;
                    $params['booking_item_name'] = !empty($post['booking_item_name']) ? $post['booking_item_name'] : $params;

                    /*
                    if($post['other_item_column'] && $post['other_item_column'] > 0) {
                        $params['other_item_column'] = $post['other_item_column'];
                    }
                    */
                    
                    $get_count = $this->Front_model->get_all_booking_item_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = array();
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "load_booking_item_2":
                    $params = array(); $total  = 0;
                    $this->form_validation->set_rules('booking_item_booking_id', 'booking_item_booking_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_booking_id   = !empty($post['booking_item_booking_id']) ? $post['booking_item_booking_id'] : 0;
                        if(intval(strlen($booking_item_booking_id)) > 0){
                            $params = array(
                                'booking_item_booking_id' => $booking_item_booking_id
                            );
                            $search = null;
                            $start  = null;
                            $limit  = null;
                            $order  = "booking_item_id";
                            $dir    = "asc";
                            $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
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

            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $data['identity'] = 100;
            $data['title'] = 'Booking';
            $data['_view'] = 'layouts/admin/menu/front_office/booking';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/front_office/booking_js.php',$data);
        }
    }
    function resto(){
        if ($this->input->post()) {
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'booking_id',
                        '1' => 'booking_name'
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
                    
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($post['date_start']) ? $params['booking_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['booking_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;
                    */

                    //Default Params for Master CRUD Form
                    $params['booking_id']   = !empty($post['booking_id']) ? $post['booking_id'] : $params;
                    $params['booking_name'] = !empty($post['booking_name']) ? $post['booking_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['booking_type'] = $post['filter_type'];
                        }
                    */
                    
                    $get_count = $this->Front_model->get_all_booking_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking($params, $search, $limit, $start, $order, $dir);
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

                    $this->form_validation->set_rules('booking_name', 'booking_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $booking_name = !empty($post['booking_name']) ? $post['booking_name'] : null;
                        $booking_flag = !empty($post['booking_flag']) ? $post['booking_flag'] : 0;
                        $booking_session = $this->random_code(20);

                        $params = array(
                            'booking_name' => $booking_name,
                            'booking_flag' => $booking_flag
                        );

                        //Check Data Exist
                        $params_check = array(
                            'booking_name' => $booking_name
                        );
                        $check_exists = $this->Front_model->check_data_exist($params_check);
                        if(!$check_exists){

                            $set_data=$this->Front_model->add_booking($params);
                            if($set_data){

                                $booking_id = $set_data;
                                $data = $this->Front_model->get_booking($booking_id);

                                // Image Save Upload
                                $post_files = !empty($_FILES) ? $_FILES['files'] : "";
                                if(!empty($post_files)){
                                    //Save Image if Exist
                                    $config['image_library'] = 'gd2';
                                    $config['upload_path'] = $upload_path_directory;
                                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
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
                                            $compress['width'] = $this->image_width;
                                            $compress['height'] = $this->image_height;
                                            $compress['new_image'] = $upload_path_directory . $raw_photo;
                                            $this->load->library('image_lib', $compress);
                                            $this->image_lib->resize();

                                            if ($data && $data['booking_id']) {
                                                $params_image = array(
                                                    'booking_image' => $upload_directory . $raw_photo
                                                );
                                                if (!empty($data['booking_image'])) {
                                                    if (file_exists($upload_path_directory . $data['booking_image'])) {
                                                        unlink($upload_path_directory . $data['booking_image']);
                                                    }
                                                }
                                                $stat = $this->Front_model->update_booking_custom(array('booking_id' => $set_data), $params_image);
                                            }
                                        }
                                    }
                                }
                                //End of Save Image

                                //Croppie Upload Image
                                $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                                if(!empty($post_upload)){
                                    $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                                    if($upload_process->status == 1){
                                        if ($data && $data['booking_id']) {
                                            $params_image = array(
                                                'booking_url' => $upload_process->result['file_location']
                                            );
                                            if (!empty($data['booking_url'])) {
                                                if (file_exists($upload_path_directory . $data['booking_url'])) {
                                                    unlink($upload_path_directory . $data['booking_url']);
                                                }
                                            }
                                            $stat = $this->Front_model->update_booking_custom(array('booking_id' => $set_data), $params_image);
                                        }
                                    }else{
                                        $return->message = 'Fungsi Gambar gagal';
                                    }
                                }
                                //End of Croppie

                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['booking_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['booking_name'],
                                    'session' => $booking_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['booking_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $booking_id   = !empty($post['booking_id']) ? $post['booking_id'] : 0;
                        if(intval(strlen($booking_id)) > 0){        
                            $datas = $this->Front_model->get_booking($booking_id);
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
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_id = !empty($post['booking_id']) ? $post['booking_id'] : $post['booking_id'];
                        $booking_name = !empty($post['booking_name']) ? $post['booking_name'] : $post['booking_name'];
                        $booking_flag = !empty($post['booking_flag']) ? $post['booking_flag'] : $post['booking_flag'];

                        if(strlen($booking_id) > 1){
                            $params = array(
                                'booking_name' => $booking_name,
                                'booking_date_updated' => date("YmdHis"),
                                'booking_flag' => $booking_flag
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Front_model->update_booking($booking_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Front_model->get_booking($booking_id);
                                    
                                //Update Image if Exist
                                $post_files = !empty($_FILES) ? $_FILES['files'] : "";
                                if(!empty($post_files)){
                                    $config['image_library'] = 'gd2';
                                    $config['upload_path'] = $upload_path_directory;
                                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
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
                                            $compress['width'] = $this->image_width;
                                            $compress['height'] = $this->image_height;
                                            $compress['new_image'] = $upload_path_directory . $raw_photo;
                                            $this->load->library('image_lib', $compress);
                                            $this->image_lib->resize();
                                            if ($get_data) {
                                                $params_image = array(
                                                    'booking_image' => base_url($upload_directory) . $raw_photo
                                                );
                                                if (!empty($get_data['booking_image'])) {
                                                    $file = FCPATH.$this->folder_upload.$get_data['booking_image'];
                                                    if (file_exists($file)) {
                                                        unlink($file);
                                                    }
                                                }
                                                $stat = $this->Front_model->update_booking_custom(array('booking_id' => $booking_id), $params_image);
                                            }
                                        }
                                    }
                                }
                                //End of Save Image

                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$booking_name;
                            }else{
                                $return->message='Gagal memperbarui '.$booking_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_id   = !empty($post['booking_id']) ? $post['booking_id'] : 0;
                        $booking_name = !empty($post['booking_name']) ? $post['booking_name'] : null;

                        if(strlen($booking_id) > 0){
                            $get_data=$this->Front_model->get_booking($booking_id);
                            // $set_data=$this->Front_model->delete_booking($booking_id);
                            $set_data = $this->Front_model->update_booking_custom(array('booking_id'=>$booking_id),array('booking_flag'=>4));                
                            if($set_data){
                                /*
                                $file = FCPATH.$this->folder_upload.$get_data['booking_image'];
                                if (file_exists($file)) {
                                    unlink($file);
                                }
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$booking_name;
                            }else{
                                $return->message='Gagal menghapus '.$booking_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":
                    $this->form_validation->set_rules('booking_id', 'booking_id', 'required');
                    $this->form_validation->set_rules('booking_flag', 'booking_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_id = !empty($post['booking_id']) ? $post['booking_id'] : 0;
                        if(strlen(intval($booking_id)) > 1){
                            
                            $params = array(
                                'booking_flag' => !empty($post['booking_flag']) ? intval($post['booking_flag']) : 0,
                            );
                            
                            $where = array(
                                'booking_id' => !empty($post['booking_id']) ? intval($post['booking_id']) : 0,
                            );
                            
                            if($post['booking_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['booking_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['booking_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            if($post['booking_flag'] == 4){
                                $params['booking_url'] = null;
                            }

                            $get_data = $this->Front_model->get_booking_custom($where);
                            if($get_data){
                                $set_update=$this->Front_model->update_booking_custom($where,$params);
                                if($set_update){
                                    if($post['booking_flag'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['booking_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['booking_name'];
                                }else{
                                    $return->message='Gagal '.$set_msg;
                                }
                            }else{
                                $return->message='Gagal mendapatkan data';
                            }   
                        }else{
                            $return->message = 'Tidak ada data';
                        } 
                    }
                    break;
                case "create_booking_item":
                    // $data = base64_decode($post);
                    // $data = json_decode($post, TRUE);

                    $this->form_validation->set_rules('booking_item_name', 'booking_item_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $booking_item_name = !empty($post['booking_item_name']) ? $post['booking_item_name'] : null;
                        $booking_item_flag = !empty($post['booking_item_flag']) ? $post['booking_item_flag'] : 0;
                        $booking_item_session = $this->random_code(20);

                        $params = array(
                            'booking_item_name' => $booking_item_name,
                            'booking_item_flag' => $booking_item_flag
                        );

                        //Check Data Exist
                        $params_check = array(
                            'booking_item_name' => $booking_item_name
                        );
                        $check_exists = $this->Front_model->check_data_exist_booking_item($params_check);
                        if(!$check_exists){

                            $set_data=$this->Front_model->add_booking_item($params);
                            if($set_data){

                                $booking_item_id = $set_data;
                                $data = $this->Front_model->get_booking_item($booking_item_id);
                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['booking_item_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['booking_item_name'],
                                    'session' => $booking_item_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['booking_item_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read_booking_item":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $booking_item_id   = !empty($post['booking_item_id']) ? $post['booking_item_id'] : 0;
                        if(intval(strlen($booking_item_id)) > 0){        
                            $datas = $this->Front_model->get_booking_item($booking_item_id);
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
                case "update_booking_item":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_id = !empty($post['booking_item_id']) ? $post['booking_item_id'] : $post['booking_item_id'];
                        $booking_item_name = !empty($post['booking_item_name']) ? $post['booking_item_name'] : $post['booking_item_name'];
                        $booking_item_flag = !empty($post['booking_item_flag']) ? $post['booking_item_flag'] : $post['booking_item_flag'];

                        if(strlen($booking_item_id) > 0){
                            $params = array(
                                'booking_item_name' => $booking_item_name,
                                'booking_item_date_updated' => date("YmdHis"),
                                'booking_item_flag' => $booking_item_flag
                            );
                           
                            $set_update=$this->Front_model->update_booking_item($booking_item_id,$params);
                            if($set_update){
                                $get_data = $this->Front_model->get_booking_item($booking_item_id);
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$booking_item_name;
                            }else{
                                $return->message='Gagal memperbarui '.$booking_item_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "update_booking_item_flag":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    $this->form_validation->set_rules('booking_item_flag', 'booking_item_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_id = !empty($post['booking_item_id']) ? $post['booking_item_id'] : 0;
                        if(strlen(intval($booking_item_id)) > 0){
                            
                            $params = array(
                                'booking_item_flag' => !empty($post['booking_item_flag']) ? intval($post['booking_item_flag']) : 0,
                            );
                            
                            $where = array(
                                'booking_item_id' => !empty($post['booking_item_id']) ? intval($post['booking_item_id']) : 0,
                            );
                            
                            if($post['booking_item_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['booking_item_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['booking_item_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $set_update=$this->Front_model->update_booking_item_custom($where,$params);
                            if($set_update){
                                $get_data = $this->Front_model->get_booking_item_custom($where);
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['booking_item_name'];
                            }else{
                                $return->message='Gagal '.$set_msg;
                            }   
                        }else{
                            $return->message = 'Gagal mendapatkan data';
                        } 
                    }
                    break;
                case "delete_booking_item":
                    $this->form_validation->set_rules('booking_item_id', 'booking_item_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_id   = !empty($post['booking_item_id']) ? $post['booking_item_id'] : 0;
                        $booking_item_name = !empty($post['booking_item_name']) ? $post['booking_item_name'] : null;                                

                        if(strlen($booking_item_id) > 0){
                            $get_data=$this->Front_model->get_booking_item($booking_item_id);
                            // $set_data=$this->Front_model->delete_booking_item($booking_item_id);
                            $set_data = $this->Front_model->update_booking_item_custom(array('booking_item_id'=>$booking_item_id),array('booking_item_flag'=>4));                
                            if($set_data){
                                /*
                                if (file_exists($get_data['booking_item_image'])) {
                                    unlink($get_data['booking_item_image']);
                                } 
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$booking_item_name;
                            }else{
                                $return->message='Gagal menghapus '.$booking_item_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "load_booking_item":
                    $columns = array(
                        '0' => 'booking_item_id',
                        '1' => 'booking_item_name'
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
                    $params['booking_item_id']   = !empty($post['booking_item_id']) ? $post['booking_item_id'] : $params;
                    $params['booking_item_name'] = !empty($post['booking_item_name']) ? $post['booking_item_name'] : $params;

                    /*
                    if($post['other_item_column'] && $post['other_item_column'] > 0) {
                        $params['other_item_column'] = $post['other_item_column'];
                    }
                    */
                    
                    $get_count = $this->Front_model->get_all_booking_item_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = array();
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "load_booking_item_2":
                    $params = array(); $total  = 0;
                    $this->form_validation->set_rules('booking_item_booking_id', 'booking_item_booking_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $booking_item_booking_id   = !empty($post['booking_item_booking_id']) ? $post['booking_item_booking_id'] : 0;
                        if(intval(strlen($booking_item_booking_id)) > 0){
                            $params = array(
                                'booking_item_booking_id' => $booking_item_booking_id
                            );
                            $search = null;
                            $start  = null;
                            $limit  = null;
                            $order  = "booking_item_id";
                            $dir    = "asc";
                            $get_data = $this->Front_model->get_all_booking_item($params, $search, $limit, $start, $order, $dir);
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

            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $data['identity'] = 200;
            $data['title'] = 'Front';
            $data['_view'] = 'layouts/admin/menu/front_office/resto';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/front_office/resto_js.php',$data);
        }
    }    
}
?>