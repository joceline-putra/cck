<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Front_office extends MY_Controller{

    var $folder_upload = 'upload/booking/';
    var $image_width   = 250;
    var $image_height  = 250;

    function __construct(){
        parent::__construct();
        /*
        */
        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }
        // $this->load->helper('form');
        // $this->load->library('form_validation');
        $this->load->model('Ref_model');    
        $this->load->model('Referensi_model');                
        $this->load->model('Branch_model');
        $this->load->model('Front_model');
        $this->load->model('User_model');

        $this->print_to         = 1; //0 = Local, 1 = Bluetooth
        $this->whatsapp_config  = 1;
        $this->module_approval   = 0; //Approval
        $this->module_attachment   = 1; //Attachment         

        $this->booking_identity = 222;
        $this->resto_identity = 2222;        
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
                        '0' => 'order_date',
                        '1' => 'order_number'
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
                    !empty($post['date_start']) ? $params['order_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['order_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;
                    */

                    //Default Params for Master CRUD Form
                    // $params['order_id']   = !empty($post['order_id']) ? $post['order_id'] : $params;
                    // $params['order_name'] = !empty($post['order_name']) ? $post['order_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['order_type'] = $post['filter_type'];
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
                        $return->result          = $get_data;
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "create_update":
                    $this->form_validation->set_rules('order_type', 'order_type', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        if( (!empty($post['order_id'])) && (strlen($post['order_id']) > 0) ){ /* Update if Exist */ // if( (!empty($post['order_session'])) && (strlen($post['order_session']) > 10) ){ /* Update if Exist */      

                            $return->message = 'Update tidak tersedia';
                            echo json_encode($return);
                            return;

                            /* Check Existing Data */
                            $params_check = [
                                'order_number' => !empty($post['order_number']) ? $post['order_number'] : null
                            ];

                            $where_not = [
                                'order_id' => intval($post['order_id']),                   
                            ];                            
                            $where_new = [
                                'order_number' => 'Sel',
                                'order_flag' => 1
                            ];
                            $check_exists = $this->Front_model->check_data_exist_two_condition($where_not,$where_new);

                            /* Continue Update if not exist */
                            if(!$check_exists){
                                $params = array(
                                    'order_name' => !empty($post['order_name']) ? $post['order_name'] : null,
                                    'order_flag' => !empty($post['order_flag']) ? $post['order_flag'] : 0
                                );
                                $create = $this->Front_model->add_booking($params);   
                                if($create){
                                    $get_booking = $this->Front_model->get_booking($create);
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$post['order_name'];
                                    $return->result= array(
                                        'order_id' => $create,
                                        'order_name' => $get_booking['order_name'],
                                        'order_session' => $get_booking['order_session']
                                    );                                
                                }else{
                                    $return->message = 'Gagal menambahkan '.$post['order_name'];
                                }
                            }else{
                                $return->message = 'Data sudah digunakan';
                            }
                        }else{ /* Save New Data */

                            /* Check Existing Data */
                            $params_check = [
                                'order_id' => !empty($post['order_id']) ? $post['order_id'] : null
                            ];
                            $check_exists = $this->Front_model->check_data_exist($params_check);

                            /* Continue Save if not exist */
                            if(!$check_exists){
                                $order_session = $this->random_session(20);
                                $order_number  = $this->request_number_for_order($post['order_type']);
                                $params = array(
                                    'order_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                    'order_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                    'order_number' => $order_number,
                                    'order_session' => $order_session,
                                    // 'order_date_due' => !empty($post['order_date_due']) ? $post['order_files_count'] : null,
                                    // 'order_contact_id' => !empty($post['order_contact_id']) ? $post['order_files_count'] : null,
                                    // 'order_contact_id_2' => !empty($post['order_contact_id_2']) ? $post['order_files_count'] : null,
                                    // 'order_ppn' => !empty($post['order_ppn']) ? $post['order_files_count'] : null,
                                    // 'order_total_dpp' => !empty($post['order_total_dpp']) ? intval($post['order_total_dpp']) : null,
                                    // 'order_discount' => !empty($post['order_discount']) ? intval($post['order_discount']) : null,
                                    // 'order_voucher' => !empty($post['order_voucher']) ? intval($post['order_voucher']) : null,
                                    // 'order_with_dp' => !empty($post['order_with_dp']) ? intval($post['order_with_dp']) : null,
                                    // 'order_total' => !empty($post['order_total']) ? intval($post['order_total']) : null,
                                    // 'order_with_dp_account' => !empty($post['order_with_dp_account']) ? $post['order_files_count'] : null,
                                    // 'order_note' => !empty($post['order_note']) ? $post['order_files_count'] : null,
                                    'order_user_id' => $session_user_id,
                                    'order_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                    'order_date' => date("YmdHis"),
                                    'order_date_created' => date("YmdHis"),
                                    // 'order_date_updated' => !empty($post['order_date_updated']) ? $post['order_files_count'] : null,
                                    // 'order_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : null,
                                    // 'order_trans_id' => !empty($post['order_trans_id']) ? $post['order_files_count'] : null,
                                    // 'order_ref_number' => !empty($post['order_ref_number']) ? $post['order_files_count'] : null,
                                    // 'order_approval_flag' => !empty($post['order_approval_flag']) ? intval($post['order_approval_flag']) : null,
                                    // 'order_label' => !empty($post['order_label']) ? $post['order_files_count'] : null,
                                    // 'order_sales_id' => !empty($post['order_sales_id']) ? $post['order_files_count'] : null,
                                    // 'order_sales_name' => !empty($post['order_sales_name']) ? $post['order_files_count'] : null,
                                    'order_contact_code' => !empty($post['order_contact_code']) ? str_replace(' ','',$post['order_contact_code']) : null,
                                    'order_contact_name' => !empty($post['order_contact_name']) ? $post['order_contact_name'] : null,
                                    'order_contact_phone' => !empty($post['order_contact_phone']) ? $this->contact_number($post['order_contact_phone']) : null,
                                );
                                // var_dump($params);die;
                                $create = $this->Front_model->add_booking($params);   
                                if($create){
                                    $get_booking = $this->Front_model->get_booking($create);
                                    
                                    //Save Item
                                    $params_items = array(
                                        'order_item_branch_id' => !empty($post['order_branch_id']) ? intval($post['order_branch_id']) : null,
                                        'order_item_type' => !empty($post['order_type']) ? intval($post['order_type']) : null,
                                        'order_item_type_name' => !empty($post['order_item_type_name']) ? $post['order_item_contact_id_2'] : null,
                                        'order_item_order_id' => $create,
                                        // 'order_item_product_id' => !empty($post['order_item_product_id']) ? $post['order_item_contact_id_2'] : null,
                                        'order_item_qty' => !empty($post['order_item_qty']) ? intval($post['order_item_qty']) : 1,
                                        // 'order_item_unit' => !empty($post['order_item_unit']) ? $post['order_item_contact_id_2'] : null,
                                        'order_item_price' => !empty($post['order_price']) ? floatval($post['order_price']) : "0.00",
                                        // 'order_item_total' => !empty($post['order_item_total']) ? intval($post['order_item_total']) : null,
                                        // 'order_item_note' => !empty($post['order_item_note']) ? $post['order_item_contact_id_2'] : null,
                                        'order_item_user_id' => $session_user_id,
                                        // 'order_item_date' => !empty($post['order_item_date']) ? $post['order_item_contact_id_2'] : null,
                                        'order_item_date_created' => date("YmdHis"),
                                        // 'order_item_date_updated' => !empty($post['order_item_date_updated']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_flag' => !empty($post['order_item_flag']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_product_price_id' => !empty($post['order_item_product_price_id']) ? $post['order_item_contact_id_2'] : null,
                                        // 'order_item_ppn' => !empty($post['order_item_ppn']) ? intval($post['order_item_ppn']) : null,
                                        'order_item_order_session' => $order_session,
                                        // 'order_item_session' => !empty($post['order_item_session']) ? $post['order_item_contact_id_2'] : null,
                                        'order_item_type_2' => !empty($post['order_type_2']) ? $post['order_type_2'] : null,
                                        'order_item_ref_id' => !empty($post['order_ref_id']) ? intval($post['order_ref_id']) : null,
                                        'order_item_ref_price_id' => !empty($post['order_ref_price_id']) ? intval($post['order_ref_price_id']) : null,
                                    );                      
                                    $params_items['order_item_start_date'] = $post['order_start_date']; 
                                    $params_items['order_item_end_date'] = $post['order_end_date'];
    
                                    if($post['order_type_2'] == "Bulanan"){
                                        $params_items['order_item_start_hour'] = '14:00:00';
                                    }else if($post['order_type_2'] == "Transit"){
                                        $params_items['order_item_start_hour'] = $post['order_start_hour']; 
                                    }
    
                                    // var_dump($params_items);die;          
                                    $create_item = $this->Front_model->add_booking_item($params_items);
                                    //End Save Item                                    
                                    $return->status  = 1;
                                    $return->message = 'Berhasil menambahkan '.$order_number;
                                    $return->result= array(
                                        'order_id' => $create,
                                        'order_number' => $get_booking['order_number'],
                                        'order_session' => $get_booking['order_session']
                                    );                                
                                }else{
                                    $return->message = 'Gagal menambahkan '.$post['order_number'];
                                }
                            }else{
                                $return->message = 'Data sudah ada';
                            }                         
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $order_id   = !empty($post['order_id']) ? $post['order_id'] : 0;
                        if(intval(strlen($order_id)) > 0){        
                            $datas = $this->Front_model->get_booking($order_id);
                            if($datas){
                                $datas_items = $this->Front_model->get_booking_item_custom(['order_item_order_id' => $order_id]);
                                $return->status=1;
                                $return->message='Berhasil mendapatkan data';
                                $return->result=$datas;
                                $return->result_item=$datas_items;
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    break;
                case "update": die; //Not Used
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id = !empty($post['order_id']) ? $post['order_id'] : $post['order_id'];
                        $order_name = !empty($post['order_name']) ? $post['order_name'] : $post['order_name'];
                        $order_flag = !empty($post['order_flag']) ? $post['order_flag'] : $post['order_flag'];

                        if(strlen($order_id) > 1){
                            $params = array(
                                'order_name' => $order_name,
                                'order_date_updated' => date("YmdHis"),
                                'order_flag' => $order_flag
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Front_model->update_booking($order_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Front_model->get_booking($order_id);
                                    
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
                                                    'order_image' => base_url($upload_directory) . $raw_photo
                                                );
                                                if (!empty($get_data['order_image'])) {
                                                    $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                                    if (file_exists($file)) {
                                                        unlink($file);
                                                    }
                                                }
                                                $stat = $this->Front_model->update_order_custom(array('order_id' => $order_id), $params_image);
                                            }
                                        }
                                    }
                                }
                                //End of Save Image

                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$order_name;
                            }else{
                                $return->message='Gagal memperbarui '.$order_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id   = !empty($post['order_id']) ? $post['order_id'] : 0;
                        $order_name = !empty($post['order_name']) ? $post['order_name'] : null;

                        if(strlen($order_id) > 0){
                            $get_data=$this->Front_model->get_booking($order_id);
                            // $set_data=$this->Front_model->delete_booking($order_id);
                            $set_data = $this->Front_model->update_order_custom(array('order_id'=>$order_id),array('order_flag'=>4));                
                            if($set_data){
                                /*
                                $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                if (file_exists($file)) {
                                    unlink($file);
                                }
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$order_name;
                            }else{
                                $return->message='Gagal menghapus '.$order_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_rules('order_flag', 'order_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id = !empty($post['order_id']) ? $post['order_id'] : 0;
                        if(intval($order_id) > 1){
                            
                            $params = array(
                                'order_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                            );
                            
                            $where = array(
                                'order_id' => !empty($post['order_id']) ? intval($post['order_id']) : 0,
                            );
                            
                            if($post['order_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['order_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['order_flag']== 4){
                                $set_msg = 'membatalkan';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $get_data = $this->Front_model->get_booking_custom($where);
                            if($get_data){
                                $set_update=$this->Front_model->update_booking_custom($where,$params);
                                if($set_update){
                                    $params = array(
                                        'order_item_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                                        'order_item_flag_checkin' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                                    );
                                    
                                    $where = array(
                                        'order_item_order_id' => !empty($post['order_id']) ? intval($post['order_id']) : 0,
                                    );                                    
                                    $set_update=$this->Front_model->update_booking_item_custom($where,$params);                                    
                                    if($post['order_flag'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['order_number'];
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
                case "update_flag_item":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_rules('order_item_id', 'order_item_id', 'required');
                    $this->form_validation->set_rules('order_item_flag_checkin', 'order_item_flag_checkin', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_item_id = !empty($post['order_item_id']) ? $post['order_item_id'] : 0;
                        if(intval($order_item_id) > 1){
                            
                            $params = array(
                                'order_item_flag_checkin' => !empty($post['order_item_flag_checkin']) ? intval($post['order_item_flag_checkin']) : 0,
                            );
                            
                            $where = array(
                                'order_item_id' => !empty($post['order_item_id']) ? intval($post['order_item_id']) : 0,
                            );
                            
                            if($post['order_item_flag_checkin']== 0){
                                $set_msg = 'waiting';
                            }else if($post['order_item_flag_checkin']== 1){
                                $set_msg = 'checkin';
                            }else if($post['order_item_flag_checkin']== 2){
                                $set_msg = 'checkout';
                            }else if($post['order_item_flag_checkin']== 4){
                                $set_msg = 'batal';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            // if($post['order_flag'] == 4){
                            //     $params['order_url'] = null;
                            // }

                            $get_data = $this->Front_model->get_booking_item_custom($where);
                            if($get_data){
                                $set_update=$this->Front_model->update_booking_item_custom($where,$params);
                                if($set_update){
                                    if($post['order_item_flag_checkin'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['order_number'];
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

            $data['module_approval']    = $this->module_approval;
            $data['module_attachment'] = $this->module_attachment;                
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $data['branch'] = $this->Branch_model->get_all_branch(['branch_flag' => 1],null,null,null,'branch_name','asc');
            $data['ref'] = $this->Ref_model->get_all_ref(['references.ref_type' => 10],null,null,null,'ref_name','asc');            
            // $data['ref'] = $this->Ref_model->get_all_ref_price(['price_flag' => 1],null,null,null,'price_name','asc');    

            $params = array(
                'booking_item_id' => 'value'
            );
            $search = null; $limit = null; $start = null; $order  = 'order_item_id'; $dir = 'desc';
            $get_booking_item = $this->Front_model->get_all_booking_item(null,$search,$limit,$start,$order,$dir);

            // var_dump($get_booking_item);die;
            $data['identity'] = $this->booking_identity;
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
                        '0' => 'order_id',
                        '1' => 'order_name'
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
                    !empty($post['date_start']) ? $params['order_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['order_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;
                    */

                    //Default Params for Master CRUD Form
                    $params['order_id']   = !empty($post['order_id']) ? $post['order_id'] : $params;
                    $params['order_name'] = !empty($post['order_name']) ? $post['order_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        if($post['filter_type'] !== "All") {
                            $params['order_type'] = $post['filter_type'];
                        }
                    */
                    
                    $get_count = $this->Front_model->get_all_order_count($params, $search);
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

                    $this->form_validation->set_rules('order_name', 'order_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $order_name = !empty($post['order_name']) ? $post['order_name'] : null;
                        $order_flag = !empty($post['order_flag']) ? $post['order_flag'] : 0;
                        $order_session = $this->random_code(20);

                        $params = array(
                            'order_name' => $order_name,
                            'order_flag' => $order_flag
                        );

                        //Check Data Exist
                        $params_check = array(
                            'order_name' => $order_name
                        );
                        $check_exists = $this->Front_model->check_data_exist($params_check);
                        if(!$check_exists){

                            $set_data=$this->Front_model->add_booking($params);
                            if($set_data){

                                $order_id = $set_data;
                                $data = $this->Front_model->get_booking($order_id);

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

                                            if ($data && $data['order_id']) {
                                                $params_image = array(
                                                    'order_image' => $upload_directory . $raw_photo
                                                );
                                                if (!empty($data['order_image'])) {
                                                    if (file_exists($upload_path_directory . $data['order_image'])) {
                                                        unlink($upload_path_directory . $data['order_image']);
                                                    }
                                                }
                                                $stat = $this->Front_model->update_order_custom(array('order_id' => $set_data), $params_image);
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
                                        if ($data && $data['order_id']) {
                                            $params_image = array(
                                                'order_url' => $upload_process->result['file_location']
                                            );
                                            if (!empty($data['order_url'])) {
                                                if (file_exists($upload_path_directory . $data['order_url'])) {
                                                    unlink($upload_path_directory . $data['order_url']);
                                                }
                                            }
                                            $stat = $this->Front_model->update_order_custom(array('order_id' => $set_data), $params_image);
                                        }
                                    }else{
                                        $return->message = 'Fungsi Gambar gagal';
                                    }
                                }
                                //End of Croppie

                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['order_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['order_name'],
                                    'session' => $order_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['order_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $order_id   = !empty($post['order_id']) ? $post['order_id'] : 0;
                        if(intval(strlen($order_id)) > 0){        
                            $datas = $this->Front_model->get_booking($order_id);
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
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id = !empty($post['order_id']) ? $post['order_id'] : $post['order_id'];
                        $order_name = !empty($post['order_name']) ? $post['order_name'] : $post['order_name'];
                        $order_flag = !empty($post['order_flag']) ? $post['order_flag'] : $post['order_flag'];

                        if(strlen($order_id) > 1){
                            $params = array(
                                'order_name' => $order_name,
                                'order_date_updated' => date("YmdHis"),
                                'order_flag' => $order_flag
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Front_model->update_booking($order_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Front_model->get_booking($order_id);
                                    
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
                                                    'order_image' => base_url($upload_directory) . $raw_photo
                                                );
                                                if (!empty($get_data['order_image'])) {
                                                    $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                                    if (file_exists($file)) {
                                                        unlink($file);
                                                    }
                                                }
                                                $stat = $this->Front_model->update_order_custom(array('order_id' => $order_id), $params_image);
                                            }
                                        }
                                    }
                                }
                                //End of Save Image

                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$order_name;
                            }else{
                                $return->message='Gagal memperbarui '.$order_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id   = !empty($post['order_id']) ? $post['order_id'] : 0;
                        $order_name = !empty($post['order_name']) ? $post['order_name'] : null;

                        if(strlen($order_id) > 0){
                            $get_data=$this->Front_model->get_booking($order_id);
                            // $set_data=$this->Front_model->delete_booking($order_id);
                            $set_data = $this->Front_model->update_order_custom(array('order_id'=>$order_id),array('order_flag'=>4));                
                            if($set_data){
                                /*
                                $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                if (file_exists($file)) {
                                    unlink($file);
                                }
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$order_name;
                            }else{
                                $return->message='Gagal menghapus '.$order_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":
                    $this->form_validation->set_rules('order_id', 'order_id', 'required');
                    $this->form_validation->set_rules('order_flag', 'order_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_id = !empty($post['order_id']) ? $post['order_id'] : 0;
                        if(strlen(intval($order_id)) > 1){
                            
                            $params = array(
                                'order_flag' => !empty($post['order_flag']) ? intval($post['order_flag']) : 0,
                            );
                            
                            $where = array(
                                'order_id' => !empty($post['order_id']) ? intval($post['order_id']) : 0,
                            );
                            
                            if($post['order_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['order_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['order_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            if($post['order_flag'] == 4){
                                $params['order_url'] = null;
                            }

                            $get_data = $this->Front_model->get_order_custom($where);
                            if($get_data){
                                $set_update=$this->Front_model->update_order_custom($where,$params);
                                if($set_update){
                                    if($post['order_flag'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['order_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['order_name'];
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
                case "create_order_item":
                    // $data = base64_decode($post);
                    // $data = json_decode($post, TRUE);

                    $this->form_validation->set_rules('order_item_name', 'order_item_name', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $order_item_name = !empty($post['order_item_name']) ? $post['order_item_name'] : null;
                        $order_item_flag = !empty($post['order_item_flag']) ? $post['order_item_flag'] : 0;
                        $order_item_session = $this->random_code(20);

                        $params = array(
                            'order_item_name' => $order_item_name,
                            'order_item_flag' => $order_item_flag
                        );

                        //Check Data Exist
                        $params_check = array(
                            'order_item_name' => $order_item_name
                        );
                        $check_exists = $this->Front_model->check_data_exist_order_item($params_check);
                        if(!$check_exists){

                            $set_data=$this->Front_model->add_order_item($params);
                            if($set_data){

                                $order_item_id = $set_data;
                                $data = $this->Front_model->get_order_item($order_item_id);
                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['order_item_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['order_item_name'],
                                    'session' => $order_item_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['order_item_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read_order_item":
                    $this->form_validation->set_rules('order_item_id', 'order_item_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $order_item_id   = !empty($post['order_item_id']) ? $post['order_item_id'] : 0;
                        if(intval(strlen($order_item_id)) > 0){        
                            $datas = $this->Front_model->get_order_item($order_item_id);
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
                case "update_order_item":
                    $this->form_validation->set_rules('order_item_id', 'order_item_id', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_item_id = !empty($post['order_item_id']) ? $post['order_item_id'] : $post['order_item_id'];
                        $order_item_name = !empty($post['order_item_name']) ? $post['order_item_name'] : $post['order_item_name'];
                        $order_item_flag = !empty($post['order_item_flag']) ? $post['order_item_flag'] : $post['order_item_flag'];

                        if(strlen($order_item_id) > 0){
                            $params = array(
                                'order_item_name' => $order_item_name,
                                'order_item_date_updated' => date("YmdHis"),
                                'order_item_flag' => $order_item_flag
                            );
                           
                            $set_update=$this->Front_model->update_order_item($order_item_id,$params);
                            if($set_update){
                                $get_data = $this->Front_model->get_order_item($order_item_id);
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$order_item_name;
                            }else{
                                $return->message='Gagal memperbarui '.$order_item_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "update_order_item_flag":
                    $this->form_validation->set_rules('order_item_id', 'order_item_id', 'required');
                    $this->form_validation->set_rules('order_item_flag', 'order_item_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_item_id = !empty($post['order_item_id']) ? $post['order_item_id'] : 0;
                        if(strlen(intval($order_item_id)) > 0){
                            
                            $params = array(
                                'order_item_flag' => !empty($post['order_item_flag']) ? intval($post['order_item_flag']) : 0,
                            );
                            
                            $where = array(
                                'order_item_id' => !empty($post['order_item_id']) ? intval($post['order_item_id']) : 0,
                            );
                            
                            if($post['order_item_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['order_item_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['order_item_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $set_update=$this->Front_model->update_order_item_custom($where,$params);
                            if($set_update){
                                $get_data = $this->Front_model->get_order_item_custom($where);
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['order_item_name'];
                            }else{
                                $return->message='Gagal '.$set_msg;
                            }   
                        }else{
                            $return->message = 'Gagal mendapatkan data';
                        } 
                    }
                    break;
                case "delete_order_item":
                    $this->form_validation->set_rules('order_item_id', 'order_item_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_item_id   = !empty($post['order_item_id']) ? $post['order_item_id'] : 0;
                        $order_item_name = !empty($post['order_item_name']) ? $post['order_item_name'] : null;                                

                        if(strlen($order_item_id) > 0){
                            $get_data=$this->Front_model->get_order_item($order_item_id);
                            // $set_data=$this->Front_model->delete_order_item($order_item_id);
                            $set_data = $this->Front_model->update_order_item_custom(array('order_item_id'=>$order_item_id),array('order_item_flag'=>4));                
                            if($set_data){
                                /*
                                if (file_exists($get_data['order_item_image'])) {
                                    unlink($get_data['order_item_image']);
                                } 
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$order_item_name;
                            }else{
                                $return->message='Gagal menghapus '.$order_item_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "load_order_item":
                    $columns = array(
                        '0' => 'order_item_id',
                        '1' => 'order_item_name'
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
                    $params['order_item_id']   = !empty($post['order_item_id']) ? $post['order_item_id'] : $params;
                    $params['order_item_name'] = !empty($post['order_item_name']) ? $post['order_item_name'] : $params;

                    /*
                    if($post['other_item_column'] && $post['other_item_column'] > 0) {
                        $params['other_item_column'] = $post['other_item_column'];
                    }
                    */
                    
                    $get_count = $this->Front_model->get_all_order_item_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Front_model->get_all_order_item($params, $search, $limit, $start, $order, $dir);
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
                case "load_order_item_2":
                    $params = array(); $total  = 0;
                    $this->form_validation->set_rules('order_item_order_id', 'order_item_order_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $order_item_order_id   = !empty($post['order_item_order_id']) ? $post['order_item_order_id'] : 0;
                        if(intval(strlen($order_item_order_id)) > 0){
                            $params = array(
                                'order_item_order_id' => $order_item_order_id
                            );
                            $search = null;
                            $start  = null;
                            $limit  = null;
                            $order  = "order_item_id";
                            $dir    = "asc";
                            $get_data = $this->Front_model->get_all_order_item($params, $search, $limit, $start, $order, $dir);
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

            $data['module_approval']    = $this->module_approval;
            $data['module_attachment'] = $this->module_attachment;   
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $data['identity'] = $this->resto_identity;
            $data['title'] = 'Front';
            $data['_view'] = 'layouts/admin/menu/front_office/resto';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/front_office/resto_js.php',$data);
        }
    }
    function contact_number($contact_phone){ //Contact 0 / +62 to safe
        $contact_phone = str_replace("'","",$contact_phone); //Remove ' if excel format    
        $contact_phone = str_replace('+','',str_replace('-','',$contact_phone)); //Remove + and -
        $contact_phone = ltrim(rtrim(trim($contact_phone))); //Remove space
        $contact_phone = str_replace(' ','',$contact_phone);
        $contact_phone_check = substr($contact_phone,0,1); // First char is 0
        if($contact_phone_check == 0){
            $contact_phone = '62'.substr($contact_phone,1,15); //To 62 81213123
        }else{
            $contact_phone = $contact_phone; //
        }
        return $contact_phone;        
    }     
    function request_number_for_order_paid(){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];

        $tgl = date('d-m-Y');
        $tahun = substr($tgl, 6, 4);
        $bulan = substr($tgl, 3, 2);
        $hari = substr($tgl, 0, 2);
        $tahun2 = substr($tgl, 8, 2);

        $query = $this->db->query("SELECT MAX(RIGHT(paid_number,5)) AS last_number
            FROM orders_paids
            WHERE YEAR(paid_date_created)=$tahun
            AND MONTH(paid_date_created)=$bulan");
            // AND paid_branch_id=$session_branch_id
            // AND order_type=$tipe");
        $nomor = "";
        if ($query->num_rows() > 0){
            foreach ($query->result() as $v){
                $temp = ((int) $v->last_number) + 1;
                $nomor = sprintf("%05s", $temp);
            }
        }else{
            $nomor = "00001";
        }  
        $auto_number = 'PAY' . '-' . $tahun2 . $bulan . '-' . $nomor;
        return $auto_number;
    }       
    function test(){
        echo json_encode($this->Front_model->get_all_paid(null,null,null,null,'paid_id','asc'));

        $params = array(
            'paid_id' => !empty($post['paid_id']) ? intval($post['paid_id']) : null,
            'paid_order_id' => !empty($post['paid_order_id']) ? intval($post['paid_order_id']) : null,
            'paid_number' => !empty($post['paid_number']) ? $post['paid_session'] : null,
            'paid_date' => !empty($post['paid_date']) ? $post['paid_session'] : null,
            'paid_payment_type' => !empty($post['paid_payment_type']) ? $post['paid_session'] : null,
            'paid_payment_method' => !empty($post['paid_payment_method']) ? $post['paid_session'] : null,
            'paid_total' => !empty($post['paid_total']) ? intval($post['paid_total']) : '0.00',
            'paid_date_created' => date("YmdHis"),
            'paid_url' => !empty($post['paid_url']) ? $post['paid_session'] : null,
            'paid_user_id' => $session_user_id,
            'paid_size' => !empty($post['paid_size']) ? intval($post['paid_size']) : null,
            'paid_session' => !empty($post['paid_session']) ? $post['paid_session'] : null,
        );        
    }
}
?>