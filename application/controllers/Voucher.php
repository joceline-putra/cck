<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends MY_Controller{

    var $folder_upload = 'upload/voucher/';
    var $image_width   = 480;
    var $image_height  = 240;
    // var $menu_id = 43;
    function __construct(){
        parent::__construct();
        if (!$this->is_logged_in()) {
			// redirect(base_url("login"));
			//Will Return to Last URL Where session is empty
			$this->session->set_userdata('url_before',base_url(uri_string()));
			redirect(base_url("login/return_url"));           
		}
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $this->load->model('Voucher_model');
    }
    function index(){
        if ($this->input->post()) {    
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;

            $data['session']    = $this->session->userdata();  
            $session            = $this->session->userdata();
            $session_user_id    = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
            $session_branch_id  = $session['user_data']['branch']['id'];
            $session_user_id    = $session['user_data']['user_id'];

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'voucher_id',
                        '1' => 'voucher_title',
                        '2' => 'voucher_type',
                        '3' => 'voucher_date_end',
                        '4' => 'voucher_flag'
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
                    !empty($post['date_start']) ? $params['voucher_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['voucher_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['voucher_id']   = !empty($post['voucher_id']) ? $post['voucher_id'] : $params;
                    // $params['voucher_name'] = !empty($post['voucher_name']) ? $post['voucher_name'] : $params;

                    if($post['filter_flag'] !== "All") {
                        $params['voucher_flag'] = $post['filter_flag'];
                    }else{
                        $params['voucher_flag <'] = 4;
                    }
                    
                    if($post['filter_type'] !== "All") {
                        $params['voucher_type'] = $post['filter_type'];
                    }

                    $get_count = $this->Voucher_model->get_all_voucher_count($params, $search);
                    if($get_count > 0){
                        $get = $this->Voucher_model->get_all_voucher($params, $search, $limit, $start, $order, $dir);
                        $get_data = array();
                        foreach($get as $v):
                            $get_data[] = array(
                                'voucher_id' => intval($v['voucher_id']),
                                'voucher_type' => intval($v['voucher_type']),
                                'voucher_title' => $v['voucher_title'],
                                'voucher_code' => $v['voucher_code'],
                                'voucher_minimum_transaction' => intval($v['voucher_minimum_transaction']),
                                'voucher_price' => intval($v['voucher_price']),
                                'voucher_discount_percentage' => intval($v['voucher_discount_percentage']),
                                'voucher_date_start' => $v['voucher_date_start'],
                                'voucher_date_end' => $v['voucher_date_end'],
                                'voucher_url' => !empty($v['voucher_url']) ? site_url().$v['voucher_url'] : site_url($this->folder_upload.'noimage.png'),
                                'voucher_flag' => intval($v['voucher_flag']),
                                'voucher_date_created' => $v['voucher_date_created'],
                                'voucher_date_created_format' => $v['voucher_date_created_format'],
                                'voucher_date_created_time_ago' => $v['voucher_date_created_time_ago'],
                                'voucher_date_updated' => $v['voucher_date_updated'],
                                'voucher_session' => $v['voucher_session'],
                                'voucher_trans_count' => intval($v['voucher_trans_count']),
                                'voucher_date_updated_use' => $v['voucher_date_updated_use'],
                                'voucher_type_name' => $v['voucher_type_name'],
                                'voucher_flag_name' => $v['voucher_flag_name'],
                                'voucher_period' => $v['voucher_period'],
                                'voucher_period_end_format' => $v['voucher_period_end_format'],                                
                                'voucher_expired_day' => $v['voucher_expired_day'],
                                'voucher_status' => $v['voucher_status'],
                                'voucher_user_id' => $v['voucher_user_id'],
                                'user_username' => $v['user_username'],
                                'user_fullname' => $v['user_fullname']                                
                            );
                        endforeach;
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
                case "create":
                    // $data = base64_decode($post);
                    // $data = json_decode($post, TRUE);
                    $this->form_validation->set_rules('voucher_type', 'Jenis', 'required');
                    $this->form_validation->set_rules('voucher_code', 'Kode Voucher', 'required');                                        
                    $this->form_validation->set_message('required', '{field} wajib diisi');               
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $next = true;
                        $voucher_type = !empty($post['voucher_type']) ? intval($post['voucher_type']) : null;
                        $voucher_code = !empty($post['voucher_code']) ? str_replace(' ','',$post['voucher_code']) : null;
                        $voucher_flag = !empty($post['voucher_flag']) ? $post['voucher_flag'] : 0;

                        $voucher_date_start = !empty($post['voucher_date_start']) ? substr($post['voucher_date_start'],6,4).'-'.substr($post['voucher_date_start'],3,2).'-'.substr($post['voucher_date_start'],0,2).' 00:00:00' : null;
                        $voucher_date_end = !empty($post['voucher_date_end']) ? substr($post['voucher_date_end'],6,4).'-'.substr($post['voucher_date_end'],3,2).'-'.substr($post['voucher_date_end'],0,2) : null;
                        $voucher_session = $this->random_code(20);

                        $date_diff_start = date_diff(date_create(date("Y-m-d 00:00:00")),date_create($voucher_date_start));
                        $date_diff_end = date_diff(date_create($voucher_date_start),date_create($voucher_date_end.' 00:00:00'));
                        
                        if($voucher_type < 1){
                            $next=false;
                            $return->message = 'Jenis wajib dipilih';
                        }

                        if($date_diff_start->format("%R%a") < 0){
                            $next=false;
                            $return->message = 'Tanggal Berlaku Awal tidak boleh mundur';
                        }

                        if($date_diff_end->format("%R%a") < 0){                            
                            $next=false;
                            $return->message = 'Tanggal Berlaku Berakhir tidak boleh kurang dari Tgl Awal';
                        }
                        
                        if($voucher_type == 1){
                            if(intval(str_replace(',','',$post['voucher_price'])) > intval(str_replace(',','',$post['voucher_minimum_transaction']))){
                                $next=false;
                                $return->message='Nilai voucher lebih besar dari Transaksi';
                            }
                            if(intval(str_replace(',','',$post['voucher_price'])) == intval(str_replace(',','',$post['voucher_minimum_transaction']))){
                                $next=false;
                                $return->message='Nilai voucher tidak boleh sama dengan Transaksi';
                            }                            
                        }
                        // var_dump(intval(str_replace(',','',$post['voucher_price'])).", ".intval(str_replace(',','',$post['voucher_minimum_transaction'])));die;
                        // echo $return->message; die;
                        if($next){
                            $params = array(
                                'voucher_type' => intval($voucher_type),
                                'voucher_title' => !empty($post['voucher_title']) ? $post['voucher_title'] : null,
                                'voucher_code' => !empty($post['voucher_code']) ? $this->uppercase($post['voucher_code']) : null,
                                // 'voucher_minimum_transaction' => !empty($post['voucher_minimum_transaction']) ? str_replace(',','',$post['voucher_minimum_transaction']) : null,
                                // 'voucher_price' => !empty($post['voucher_price']) ? str_replace(',','',$post['voucher_price']) : null,
                                // 'voucher_discount_percentage' => !empty($post['voucher_discount_percentage']) ? str_replace(',','',$post['voucher_discount_percentage']) : null,
                                'voucher_date_start' => $voucher_date_start,
                                'voucher_date_end' => $voucher_date_end.' 23:59:59',
                                // 'voucher_url' => !empty($post['voucher_url']) ? $post['voucher_url'] : null,
                                'voucher_flag' => !empty($post['voucher_flag']) ? $post['voucher_flag'] : 0,
                                'voucher_date_created' => date("YmdHis"),
                                'voucher_date_updated' => date("YmdHis"),
                                'voucher_session' => $voucher_session,
                                'voucher_user_id' => $session_user_id
                                // 'voucher_trans_count' => !empty($post['voucher_trans_count']) ? intval($post['voucher_trans_count']) : null,
                                // 'voucher_date_updated_use' => !empty($post['voucher_date_updated_use']) ? $post['voucher_status'] : null,
                                // 'voucher_type_name' => !empty($post['voucher_type_name']) ? $post['voucher_status'] : null,
                                // 'voucher_flag_name' => !empty($post['voucher_flag_name']) ? $post['voucher_status'] : null,
                                // 'voucher_period' => !empty($post['voucher_period']) ? $post['voucher_status'] : null,
                                // 'voucher_expired_day' => !empty($post['voucher_expired_day']) ? intval($post['voucher_expired_day']) : null,
                                // 'voucher_status' => !empty($post['voucher_status']) ? $post['voucher_status'] : null,
                            );

                            //Params Check Data Exist & Additional Params by Type
                            if($voucher_type == 1){ //Voucher
                                $params['voucher_minimum_transaction'] = !empty($post['voucher_minimum_transaction']) ? str_replace(',','',$post['voucher_minimum_transaction']) : 0;
                                $params['voucher_price'] = !empty($post['voucher_price']) ? str_replace(',','',$post['voucher_price']) : 0;                            
                                $params_check = array(
                                    'voucher_type' => $voucher_type,
                                    'voucher_code' => $voucher_code,
                                    'voucher_flag <' => 4
                                );
                            }else if($voucher_type == 2){ //Promo
                                $voucher_disc = !empty($post['voucher_discount_percentage']) ? str_replace(',','',$post['voucher_discount_percentage']) : 0;
                                $params['voucher_discount_percentage'] = $voucher_disc;     
                                $params_check = array(
                                    'voucher_type' => $voucher_type,
                                    'voucher_code' => $voucher_code,
                                    'voucher_discount_percentage' => $voucher_disc,
                                    'voucher_flag <' => 4
                                );
                            }
                            // var_dump($params_check);die;

                            $check_exists = $this->Voucher_model->check_data_exist($params_check);
                            if(!$check_exists){

                                $set_data=$this->Voucher_model->add_voucher($params);
                                if($set_data){

                                    $voucher_id = $set_data;
                                    $data = $this->Voucher_model->get_voucher($voucher_id);

                                    // $post_files = !empty($_FILES) ? $_FILES['files'] : "";
                                    $post_files = null;
                                    if(!empty($post_files)){
                                        //Save Image if Exist
                                        $config['image_library'] = 'gd2';
                                        $config['upload_path'] = $upload_path_directory;
                                        $config['allowed_types'] = 'gif|jpg|png|jpeg';
                                        $this->upload->initialize($config);
                                        if ($this->upload->do_upload('upload1')) {
                                            $upload = $this->upload->data();
                                            $raw_photo = time() . $upload['file_ext'];
                                            $old_name = $upload['full_path'];
                                            $new_name = $upload_path_directory . $raw_photo;
                                            if (rename($old_name, $new_name)) {
                                                $compress['image_library'] = 'gd2';
                                                $compress['source_image'] = $upload_path_directory . $raw_photo;
                                                $compress['create_thumb'] = FALSE;
                                                $compress['maintain_ratio'] = TRUE;
                                                // $compress['width'] = $this->image_width;
                                                // $compress['height'] = $this->image_height;
                                                $compress['new_image'] = $upload_path_directory . $raw_photo;
                                                $this->load->library('image_lib', $compress);
                                                $this->image_lib->resize();

                                                if ($data && $data['voucher_id']) {
                                                    $params_image = array(
                                                        'voucher_url' => $upload_directory . $raw_photo
                                                    );
                                                    if (!empty($data['voucher_url'])) {
                                                        if (file_exists($upload_path_directory . $data['voucher_url'])) {
                                                            unlink($upload_path_directory . $data['voucher_url']);
                                                        }
                                                    }
                                                    $stat = $this->Voucher_model->update_voucher_custom(array('voucher_id' => $set_data), $params_image);
                                                }
                                            }
                                        }
                                    }
                                    //End of Save Image

                                    //Croppie Upload Image
                                    $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                                    if(strlen($post_upload) > 10){
                                        $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                                        if($upload_process->status == 1){
                                            if ($data && $data['voucher_id']) {
                                                $params_image = array(
                                                    'voucher_url' => $upload_process->result['file_location']
                                                );
                                                if (!empty($data['voucher_url'])) {
                                                    if (file_exists($upload_path_directory . $data['voucher_url'])) {
                                                        unlink($upload_path_directory . $data['voucher_url']);
                                                    }
                                                }
                                                $stat = $this->Voucher_model->update_voucher_custom(array('voucher_id' => $set_data), $params_image);
                                            }
                                        }else{
                                            $return->message = 'Fungsi Gambar gagal';
                                        }
                                    }
                                    //End of Croppie

                                    $return->status=1;
                                    $return->message='Berhasil menambahkan '.$post['voucher_code'];
                                    $return->result= array(
                                        'id' => $set_data,
                                        'name' => $post['voucher_title'],                                    
                                        'session' => $voucher_session
                                    ); 
                                }else{
                                    $return->message='Gagal menambahkan '.$post['voucher_code'];
                                }
                            }else{
                                $return->message= $post['voucher_code'].' sudah ada';
                            }
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('voucher_id', 'voucher_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $voucher_id   = !empty($post['voucher_id']) ? $post['voucher_id'] : 0;
                        if(intval(strlen($voucher_id)) > 0){        
                            $v = $this->Voucher_model->get_voucher($voucher_id);
                            if($v){
                                $set_data = array(
                                    'voucher_id' => intval($v['voucher_id']),
                                    'voucher_type' => intval($v['voucher_type']),
                                    'voucher_title' => $v['voucher_title'],
                                    'voucher_code' => $v['voucher_code'],
                                    'voucher_minimum_transaction' => intval($v['voucher_minimum_transaction']),
                                    'voucher_price' => intval($v['voucher_price']),
                                    'voucher_discount_percentage' => intval($v['voucher_discount_percentage']),
                                    'voucher_date_start' => $v['voucher_date_start'],
                                    'voucher_date_end' => $v['voucher_date_end'],
                                    'voucher_url' => !empty($v['voucher_url']) ? site_url().$v['voucher_url'] : site_url($this->folder_upload.'noimage.png'),
                                    'voucher_flag' => intval($v['voucher_flag']),
                                    'voucher_date_created' => $v['voucher_date_created'],
                                    'voucher_date_created_format' => $v['voucher_date_created_format'],
                                    'voucher_date_created_time_ago' => $v['voucher_date_created_time_ago'],                                    
                                    'voucher_date_updated' => $v['voucher_date_updated'],
                                    'voucher_session' => $v['voucher_session'],
                                    'voucher_trans_count' => intval($v['voucher_trans_count']),
                                    'voucher_date_updated_use' => $v['voucher_date_updated_use'],
                                    'voucher_type_name' => $v['voucher_type_name'],
                                    'voucher_flag_name' => $v['voucher_flag_name'],
                                    'voucher_period' => $v['voucher_period'],
                                    'voucher_period_end_format' => $v['voucher_period_end_format'],
                                    'voucher_expired_day' => $v['voucher_expired_day'],
                                    'voucher_status' => $v['voucher_status'],
                                    'voucher_user_id' => $v['voucher_user_id'],
                                    'user_username' => $v['user_username'],
                                    'user_fullname' => $v['user_fullname']                                     
                                );
                                $return->status=1;
                                $return->message='Berhasil mendapatkan data';
                                $return->result=$set_data;
                            }else{
                                $return->message = 'Data tidak ditemukan';
                            }
                        }else{
                            $return->message='Data tidak ada';
                        }
                    }
                    break;
                case "update": die; //Not Used
                    $this->form_validation->set_rules('voucher_id', 'ID', 'required');
                    $this->form_validation->set_rules('voucher_type', 'Jenis', 'required');
                    $this->form_validation->set_rules('voucher_code', 'Code', 'required');                      
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $voucher_type = !empty($post['voucher_type']) ? intval($post['voucher_type']) : null;
                        $voucher_code = !empty($post['voucher_code']) ? str_replace(' ','',$post['voucher_code']) : null;
                        $voucher_flag = !empty($post['voucher_flag']) ? $post['voucher_flag'] : 0;

                        $voucher_date_start = !empty($post['voucher_date_start']) ? substr($post['voucher_date_start'],6,4).'-'.substr($post['voucher_date_start'],3,2).'-'.substr($post['voucher_date_start'],0,2).' 00:00:00' : null;
                        $voucher_date_end = !empty($post['voucher_date_end']) ? substr($post['voucher_date_end'],6,4).'-'.substr($post['voucher_date_end'],3,2).'-'.substr($post['voucher_date_end'],0,2).' 23:59:59' : null;

                        if(strlen($voucher_id) > 1){
                            $params = array(
                                'voucher_type' => $voucher_type,
                                'voucher_title' => !empty($post['voucher_title']) ? $post['voucher_title'] : null,
                                'voucher_code' => !empty($post['voucher_code']) ? $post['voucher_code'] : null,
                                // 'voucher_minimum_transaction' => !empty($post['voucher_minimum_transaction']) ? str_replace(',','',$post['voucher_minimum_transaction']) : null,
                                // 'voucher_price' => !empty($post['voucher_price']) ? str_replace(',','',$post['voucher_price']) : null,
                                // 'voucher_discount_percentage' => !empty($post['voucher_discount_percentage']) ? str_replace(',','',$post['voucher_discount_percentage']) : null,
                                'voucher_date_start' => $voucher_date_start,
                                'voucher_date_end' => $voucher_date_end,
                                // 'voucher_url' => !empty($post['voucher_url']) ? $post['voucher_url'] : null,
                                'voucher_flag' => !empty($post['voucher_flag']) ? intval($post['voucher_flag']) : 0,
                                // 'voucher_date_created' => date("YmdHis"),
                                'voucher_date_updated' => date("YmdHis"),
                                'voucher_session' => $voucher_session,
                                // 'voucher_trans_count' => !empty($post['voucher_trans_count']) ? intval($post['voucher_trans_count']) : null,
                                // 'voucher_date_updated_use' => !empty($post['voucher_date_updated_use']) ? $post['voucher_status'] : null,
                                // 'voucher_type_name' => !empty($post['voucher_type_name']) ? $post['voucher_status'] : null,
                                // 'voucher_flag_name' => !empty($post['voucher_flag_name']) ? $post['voucher_status'] : null,
                                // 'voucher_period' => !empty($post['voucher_period']) ? $post['voucher_status'] : null,
                                // 'voucher_expired_day' => !empty($post['voucher_expired_day']) ? intval($post['voucher_expired_day']) : null,
                                // 'voucher_status' => !empty($post['voucher_status']) ? $post['voucher_status'] : null,
                            );

                            //Params Check Data Exist & Additional Params by Type
                            if($voucher_type == 1){ //Voucher
                                $params['voucher_minimum_transaction'] = !empty($post['voucher_minimum_transaction']) ? str_replace(',','',$post['voucher_minimum_transaction']) : 0;
                                $params['voucher_price'] = !empty($post['voucher_price']) ? str_replace(',','',$post['voucher_price']) : 0;                            
                                $params_check = array(
                                    'voucher_type' => $voucher_type,
                                    'voucher_code' => $voucher_code,
                                    'voucher_flag <' => 4,
                                    'voucher_id !=' => $voucher_id
                                );
                            }else if($voucher_type == 2){ //Promo
                                $params['voucher_discount_percentage'] = !empty($post['voucher_discount_percentage']) ? str_replace(',','',$post['voucher_discount_percentage']) : 0;     
                                $params_check = array(
                                    'voucher_type' => $voucher_type,
                                    'voucher_discount_percentage' => $voucher_disc,
                                    'voucher_flag <' => 4,
                                    'voucher_id !=' => $voucher_id
                                );
                            }
                            $check_exists = $this->Voucher_model->check_data_exist($params_check);
                            if(!$check_exists){
                                $set_update=$this->Voucher_model->update_voucher($voucher_id,$params);
                                if($set_update){
                                    
                                    $get_data = $this->Voucher_model->get_voucher($voucher_id);
                                        
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
                                                        'voucher_url' => base_url($upload_directory) . $raw_photo
                                                    );
                                                    if (!empty($get_data['voucher_url'])) {
                                                        $file = FCPATH . $this->folder_upload.$get_data['voucher_url'];
                                                        if (file_exists($file)) {
                                                            unlink($file);
                                                        }
                                                    }
                                                    $stat = $this->Voucher_model->update_voucher_custom(array('voucher_id' => $voucher_id), $params_image);
                                                }
                                            }
                                        }
                                    }
                                    //End of Save Image

                                    $return->status  = 1;
                                    $return->message = 'Berhasil memperbarui '.$voucher_code;
                                }else{
                                    $return->message='Gagal memperbarui data';
                                }   
                            }else{
                                $return->message = 'Data sudah ada';
                            }
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":
                    $this->form_validation->set_rules('voucher_id', 'voucher_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $voucher_id   = !empty($post['voucher_id']) ? $post['voucher_id'] : 0;
                        $voucher_code = !empty($post['voucher_code']) ? $post['voucher_code'] : null;

                        if(strlen($voucher_id) > 0){
                            $get_data=$this->Voucher_model->get_voucher($voucher_id);
                            // $set_data=$this->Voucher_model->delete_voucher($voucher_id);
                            $set_data = $this->Voucher_model->update_voucher_custom(array('voucher_id'=>$voucher_id),array('voucher_flag'=>4));                
                            if($set_data){
                                /*
                                    $file = FCPATH . $this->folder_upload.$get_data['voucher_url'];
                                    if (file_exists($file)) {
                                        unlink($file);
                                    }
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$voucher_code;
                            }else{
                                $return->message='Gagal menghapus '.$voucher_code;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":
                    $this->form_validation->set_rules('voucher_id', 'voucher_id', 'required');
                    $this->form_validation->set_rules('voucher_flag', 'voucher_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $voucher_id = !empty($post['voucher_id']) ? $post['voucher_id'] : 0;
                        if(intval($voucher_id) > 0){
                            
                            $params = array(
                                'voucher_flag' => !empty($post['voucher_flag']) ? intval($post['voucher_flag']) : 0,
                            );
                            
                            $where = array(
                                'voucher_id' => !empty($post['voucher_id']) ? intval($post['voucher_id']) : 0,
                            );
                            
                            if($post['voucher_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['voucher_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['voucher_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }
                            if($post['voucher_flag'] == 4){
                                $params['voucher_url'] = null;
                            }

                            $get_data = $this->Voucher_model->get_voucher_custom($where);
                            if($get_data){
                                $set_update=$this->Voucher_model->update_voucher_custom($where,$params);
                                if($set_update){
                                    // if($post['voucher_flag'] == 4){
                                    //     $file = FCPATH . $get_data['voucher_url'];
                                    //     if (file_exists($file)) {
                                    //         unlink($file);
                                    //     }
                                    // }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['voucher_code'];
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
                case "redeem_search":
                    $return = new \stdClass();
                    $return->status = 0;
                    $return->message = '';
                    $return->result = '';        
                    $voucher_id   = !empty($post['voucher_code']) ? str_replace(' ','',$post['voucher_code']) : 0;
                    if(intval(strlen($voucher_id)) > 1){
                        $v = $this->Voucher_model->get_voucher_custom(array('voucher_code' => $voucher_id));
                        if($v){
                            $set_data = array(
                                'voucher_id' => intval($v['voucher_id']),
                                'voucher_type' => intval($v['voucher_type']),
                                'voucher_title' => $v['voucher_title'],
                                'voucher_code' => $v['voucher_code'],
                                'voucher_minimum_transaction' => intval($v['voucher_minimum_transaction']),
                                'voucher_minimum_transaction_format' => number_format($v['voucher_minimum_transaction']),                                
                                'voucher_price' => intval($v['voucher_price']),
                                'voucher_price_format' => number_format($v['voucher_price']),                                
                                'voucher_discount_percentage' => intval($v['voucher_discount_percentage']),
                                'voucher_discount_percentage_format' => number_format($v['voucher_discount_percentage']),                                
                                'voucher_date_start' => $v['voucher_date_start'],
                                'voucher_date_end' => $v['voucher_date_end'],
                                'voucher_url' => !empty($v['voucher_url']) ? site_url().$v['voucher_url'] : site_url($this->folder_upload.'noimage.png'),
                                'voucher_flag' => intval($v['voucher_flag']),
                                'voucher_date_created' => $v['voucher_date_created'],
                                'voucher_date_created_format' => $v['voucher_date_created_format'],
                                'voucher_date_created_time_ago' => $v['voucher_date_created_time_ago'],                                    
                                'voucher_date_updated' => $v['voucher_date_updated'],
                                'voucher_session' => $v['voucher_session'],
                                'voucher_trans_count' => intval($v['voucher_trans_count']),
                                'voucher_date_updated_use' => $v['voucher_date_updated_use'],
                                'voucher_type_name' => $v['voucher_type_name'],
                                'voucher_flag_name' => $v['voucher_flag_name'],
                                'voucher_period' => $v['voucher_period'],
                                'voucher_period_end_format' => $v['voucher_period_end_format'],
                                'voucher_expired_day' => $v['voucher_expired_day'],
                                'voucher_status' => $v['voucher_status'],
                                'voucher_user_id' => $v['voucher_user_id'],
                                'user_username' => $v['user_username'],
                                'user_fullname' => $v['user_fullname']                                     
                            );
            
                            if(($v['voucher_status'] == 'Available') && intval($v['voucher_expired_day']) > -1 ){
                                $return->status=1;
                                $return->message='Voucher Tersedia';
                                $return->result = $set_data;
                            }else{
                                $return->status=4;
                                $return->message = 'Voucher sudah expired';
                                $return->result  = array(
                                    'voucher_type_name' => $v['voucher_type_name'],
                                    'voucher_code' => $v['voucher_code'],
                                    'voucher_period' => $v['voucher_period'],
                                    'voucher_status' => $v['voucher_status'],
                                    'voucher_flag' => intval($v['voucher_flag']),
                                    'voucher_session' => $v['voucher_session']
                                );
                            }
            
                        }else{
                            $return->message = 'Voucher '.$voucher_id.' tidak ada';
                        }
                    }else{
                        $return->message='Voucher tidak ada';
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

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            $data['title'] = 'Voucher';
            $data['_view'] = 'layouts/admin/menu/product/voucher';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/product/voucher_js.php',$data);
        }
    }

    function test($voucher_id){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';        
        // $voucher_id   = !empty($post['voucher_id']) ? $post['voucher_id'] : 0;
        if(intval(strlen($voucher_id)) > 0){        
            $v = $this->Voucher_model->get_voucher_custom(array('voucher_code' => $voucher_id));
            if($v){
                $set_data = array(
                    'voucher_id' => intval($v['voucher_id']),
                    'voucher_type' => intval($v['voucher_type']),
                    'voucher_title' => $v['voucher_title'],
                    'voucher_code' => $v['voucher_code'],
                    'voucher_minimum_transaction' => intval($v['voucher_minimum_transaction']),
                    'voucher_price' => intval($v['voucher_price']),
                    'voucher_discount_percentage' => intval($v['voucher_discount_percentage']),
                    'voucher_date_start' => $v['voucher_date_start'],
                    'voucher_date_end' => $v['voucher_date_end'],
                    'voucher_url' => !empty($v['voucher_url']) ? site_url().$v['voucher_url'] : site_url($this->folder_upload.'noimage.png'),
                    'voucher_flag' => intval($v['voucher_flag']),
                    'voucher_date_created' => $v['voucher_date_created'],
                    'voucher_date_created_format' => $v['voucher_date_created_format'],
                    'voucher_date_created_time_ago' => $v['voucher_date_created_time_ago'],                                    
                    'voucher_date_updated' => $v['voucher_date_updated'],
                    'voucher_session' => $v['voucher_session'],
                    'voucher_trans_count' => intval($v['voucher_trans_count']),
                    'voucher_date_updated_use' => $v['voucher_date_updated_use'],
                    'voucher_type_name' => $v['voucher_type_name'],
                    'voucher_flag_name' => $v['voucher_flag_name'],
                    'voucher_period' => $v['voucher_period'],
                    'voucher_period_end_format' => $v['voucher_period_end_format'],
                    'voucher_expired_day' => $v['voucher_expired_day'],
                    'voucher_status' => $v['voucher_status'],
                    'voucher_user_id' => $v['voucher_user_id'],
                    'user_username' => $v['user_username'],
                    'user_fullname' => $v['user_fullname']                                     
                );

                if(($v['voucher_status'] == 'Available') && intval($v['voucher_expired_day']) > -1 ){
                    $return->status=1;
                    $return->message='Voucher Tersedia';
                    $return->result = $set_data;
                }else{
                    $return->message = 'Voucher sudah expired';
                    $return->result  = array(
                        'voucher_type_name' => $v['voucher_type_name'],
                        'voucher_code' => $v['voucher_code'],
                        'voucher_period' => $v['voucher_period'],
                        'voucher_status' => $v['voucher_status'],
                        'voucher_flag' => intval($v['voucher_flag']),
                        'voucher_session' => $v['voucher_session']
                    );
                }

            }else{
                $return->message = 'Voucher tidak ditemukan';
            }
        }else{
            $return->message='Data tidak ada';
        }     
        echo json_encode($return);           
    }
}

?>