<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset extends MY_Controller{

    var $folder_upload = 'upload/product/';
    var $image_width   = 240;
    var $image_height  = 240;    
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $this->load->model('Asset_model');
        $this->load->model('User_model');
    }
    function index(){
        $session                = $this->session->userdata();
        $session_branch_id      = $session['user_data']['branch']['id'];
        $session_user_id        = $session['user_data']['user_id'];
        $session_group_id       = intval($session['user_data']['user_group_id']);
        
        $data['session']        = $session;
        $data['user_group']     = $session['user_data']['user_group_id'];
        $data['theme']          = $this->User_model->get_user($session['user_data']['user_id']);

        if ($this->input->post()) {    
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = FCPATH . $upload_directory;

            $session_user_id = !empty($session['user_data']['user_id']) ? $session['user_data']['user_id'] : null;
            
            $config_post_to_journal = true; //True = Call SP_JOURNAL_FROM_TRANS, False = Disabled Function

            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'product_id',
                        '1' => 'product_name'
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
                    !empty($post['date_start']) ? $params['product_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['product_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['product_id']   = !empty($post['product_id']) ? $post['product_id'] : $params;
                    // $params['product_name'] = !empty($post['product_name']) ? $post['product_name'] : $params;                

                    /*
                    if($post['other_column'] && $post['other_column'] > 0) {
                        $params['other_column'] = $post['other_column'];
                    }
                    */
                    
                    $get_data = $this->Asset_model->get_all_asset($params, $search, $limit, $start, $order, $dir);
                    $get_count = $this->Asset_model->get_all_asset_count($params);
                    $datas = [];

                    if(isset($get_data)){
                        foreach($get_data as $v){
                            $datas[] = array(
                                'product_id' => intval($v['product_id']),
                                `product_type` => $v['product_type'],
                                'product_type_name' => $v['product_type_name'],
                                'product_flag' => intval($v['product_flag']),
                                'product_date_created' => $v['product_date_created'],
                                'product_date_updated' => $v['product_date_updated'],                                                                
                                'product_asset_name' => $v['product_asset_name'],
                                'product_asset_code' => $v['product_asset_code'],
                                'product_asset_note' => $v['product_asset_note'],
                                'product_asset_acquisition_date' => $v['product_asset_acquisition_date'],
                                'product_asset_acquisition_value' => intval($v['product_asset_acquisition_value']),
                                'product_asset_dep_flag' => intval($v['product_asset_dep_flag']),
                                'product_asset_dep_method' => intval($v['product_asset_dep_method']),
                                'product_asset_dep_period' => intval($v['product_asset_dep_period']),
                                'product_asset_dep_percentage' => intval($v['product_asset_dep_percentage']),
                                'product_asset_fixed_account_id' => intval($v['product_asset_fixed_account_id']),
                                'product_asset_cost_account_id' => intval($v['product_asset_cost_account_id']),
                                'product_asset_depreciation_account_id' => intval($v['product_asset_depreciation_account_id']),
                                'product_asset_accumulated_depreciation_account_id' => intval($v['product_asset_accumulated_depreciation_account_id']),
                                'product_asset_accumulated_depreciation_value' => intval($v['product_asset_accumulated_depreciation_value']),
                                'product_asset_accumulated_depreciation_date' => $v['product_asset_accumulated_depreciation_date'],
                                'fixed_account_id' => intval($v['fixed_account_id']),
                                'fixed_account_code' => $v['fixed_account_code'],
                                'fixed_account_name' => $v['fixed_account_name'],
                                'cost_account_id' => intval($v['cost_account_id']),
                                'cost_account_code' => $v['cost_account_code'],
                                'cost_account_name' => $v['cost_account_name'],
                                'depreciation_account_id' => intval($v['depreciation_account_id']),
                                'depreciation_account_code' => $v['depreciation_account_code'],
                                'depreciation_account_name' => $v['depreciation_account_name'],
                                'accumulated_account_id' => intval($v['accumulated_account_id']),
                                'accumulated_account_code' => $v['accumulated_account_code'],
                                'accumulated_account_name' => $v['accumulated_account_name'],
                            );                            
                        }
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $datas;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = $datas;
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "load-depreciation":
                    $columns = array(
                        '0' => 'journal_date',
                        '1' => 'journal_number'
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
                    !empty($post['date_start']) ? $params['product_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['product_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    // $params['product_id']   = !empty($post['product_id']) ? $post['product_id'] : $params;
                    // $params['product_name'] = !empty($post['product_name']) ? $post['product_name'] : $params;                

                    if($post['asset_id'] && $post['asset_id'] > 0) {
                        $params['journal_asset_id'] = $post['asset_id'];
                    }
                    $params['journal_type'] = 17;
                    
                    $get_data = array();

                    if($post['asset_id'] > 0){
                        $get_data = $this->Asset_model->get_all_depreciation($params, $search, $limit, $start, $order, $dir);
                        $get_count = $this->Asset_model->get_all_depreciation_count($params);
                        $datas = [];
                    }

                    if(count($get_data) > 0){
                        foreach($get_data as $v){
                            $datas[] = array(
                                'journal_id' => intval($v['journal_id']),
                                'journal_branch_id' => intval($v['journal_branch_id']),
                                'journal_type' => intval($v['journal_type']),
                                'journal_number' => $v['journal_number'],
                                'journal_date' => $v['journal_date'],
                                'journal_date_format' => date("d-M-Y", strtotime($v['journal_date'])),
                                'journal_total' => intval($v['journal_total']),
                                'journal_note' => $v['journal_note'],
                                'journal_flag' => intval($v['journal_flag']),
                                'journal_session' => $v['journal_session'],
                                'journal_id_source' => $v['journal_id_source'],
                                'journal_asset_id' => intval($v['journal_asset_id']),
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_debit' => $v['journal_item_debit'], 
                                'journal_item_credit' => $v['journal_item_credit'],
                                'journal_item_date' => $v['journal_item_date'],
                                'journal_item_group_session' => $v['journal_item_group_session'],                                                                                                                                
                            );    
                        }
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $datas;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = $get_data;
                    }
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    $return->params = $params;
                    break;                    
                case "create":
                    // $this->form_validation->set_rules('product_id', 'PRODUCT_ID', 'required');
                    // $this->form_validation->set_rules('product_flag', 'PRODUCT_FLAG', 'required');
                    // $this->form_validation->set_rules('product_date_created', 'PRODUCT_DATE_CREATED', 'required');
                    $this->form_validation->set_rules('product_asset_name', 'PRODUCT_ASSET_NAME', 'required');
                    $this->form_validation->set_rules('product_asset_code', 'PRODUCT_ASSET_CODE', 'required');
                    $this->form_validation->set_rules('product_asset_note', 'PRODUCT_ASSET_NOTE', 'required');
                    $this->form_validation->set_rules('product_asset_acquisition_date', 'PRODUCT_ASSET_ACQUISITION_DATE', 'required');
                    $this->form_validation->set_rules('product_asset_acquisition_value', 'PRODUCT_ASSET_ACQUISITION_VALUE', 'required');
                    // $this->form_validation->set_rules('product_asset_dep_flag', 'PRODUCT_ASSET_DEP_FLAG', 'required');
                    // $this->form_validation->set_rules('product_asset_dep_method', 'PRODUCT_ASSET_DEP_METHOD', 'required');
                    // $this->form_validation->set_rules('product_asset_dep_period', 'PRODUCT_ASSET_DEP_PERIOD', 'required');
                    // $this->form_validation->set_rules('product_asset_dep_percentage', 'PRODUCT_ASSET_DEP_PERCENTAGE', 'required');
                    // $this->form_validation->set_rules('product_asset_fixed_account_id', 'PRODUCT_ASSET_FIXED_ACCOUNT_ID', 'required');
                    // $this->form_validation->set_rules('product_asset_cost_account_id', 'PRODUCT_ASSET_COST_ACCOUNT_ID', 'required');
                    // $this->form_validation->set_rules('product_asset_depreciation_account_id', 'PRODUCT_ASSET_DEPRECIATION_ACCOUNT_ID', 'required');
                    // $this->form_validation->set_rules('product_asset_accumulated_depreciation_account_id', 'PRODUCT_ASSET_ACCUMULATED_DEPRECIATION_ACCOUNT_ID', 'required');
                    // $this->form_validation->set_rules('product_asset_accumulated_depreciation_value', 'PRODUCT_ASSET_ACCUMULATED_DEPRECIATION_VALUE', 'required');
                    // $this->form_validation->set_rules('product_asset_accumulated_depreciation_date', 'PRODUCT_ASSET_ACCUMULATED_DEPRECIATION_DATE', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $product_name = !empty($post['product_asset_name']) ? $post['product_asset_name'] : null;
                        // $product_flag = !empty($post['product_flag']) ? $post['product_flag'] : 0;

                        $asset_acquisition_value = str_replace(',','',$post['product_asset_acquisition_value']);
                        $asset_dep_percentage = str_replace(',','',$post['product_asset_dep_percentage']);
                        $asset_accumulated_depreciation_value = str_replace(',','',$post['product_asset_accumulated_depreciation_value']);

                        $asset_acquisition_date = substr($post['product_asset_acquisition_date'],6,4).'-'.substr($post['product_asset_acquisition_date'],3,2).'-'.substr($post['product_asset_acquisition_date'],0,2);
                        $asset_accumulated_depreciation_date = substr($post['product_asset_accumulated_depreciation_date'],6,4).'-'.substr($post['product_asset_accumulated_depreciation_date'],3,2).'-'.substr($post['product_asset_accumulated_depreciation_date'],0,2);                        
                        
                        $params = array(
                            'product_type' => 3,       
                            'product_branch_id' => $session_branch_id,
                            'product_user_id' => $session_user_id,                     
                            'product_flag' => !empty($post['product_flag']) ? intval($post['product_flag']) : 1,
                            'product_date_created' => date("YmdHis"),
                            // 'product_date_updated' => !empty($post['product_date_updated']) ? $post['product_date_updated'] : null,
                            'product_asset_name' => !empty($post['product_asset_name']) ? $post['product_asset_name'] : null,
                            'product_asset_code' => !empty($post['product_asset_code']) ? $post['product_asset_code'] : null,
                            'product_asset_note' => !empty($post['product_asset_note']) ? $post['product_asset_note'] : null,
                            'product_asset_acquisition_date' => $asset_acquisition_date,
                            'product_asset_acquisition_value' => $asset_acquisition_value,
                            'product_asset_fixed_account_id' => !empty($post['product_asset_fixed_account_id']) ? intval($post['product_asset_fixed_account_id']) : null,
                            'product_asset_cost_account_id' => !empty($post['product_asset_cost_account_id']) ? intval($post['product_asset_cost_account_id']) : null                            
                        );

                        $asset_is_depreciation = ($post['product_asset_dep_flag'] == 1) ? 0 : 1;
                        if($asset_is_depreciation == 1){
                                $params['product_asset_dep_flag'] = $asset_is_depreciation;
                                $params['product_asset_dep_method'] = !empty($post['product_asset_dep_method']) ? $post['product_asset_dep_method'] : null;
                                $params['product_asset_dep_period'] = !empty($post['product_asset_dep_period']) ? $post['product_asset_dep_period'] : null;
                                $params['product_asset_dep_percentage'] = $asset_dep_percentage;
                                $params['product_asset_depreciation_account_id'] = !empty($post['product_asset_depreciation_account_id']) ? intval($post['product_asset_depreciation_account_id']) : null;
                                $params['product_asset_accumulated_depreciation_account_id'] = !empty($post['product_asset_accumulated_depreciation_account_id']) ? intval($post['product_asset_accumulated_depreciation_account_id']) : null;
                                $params['product_asset_accumulated_depreciation_value'] = $asset_accumulated_depreciation_value;
                                $params['product_asset_accumulated_depreciation_date'] = $asset_accumulated_depreciation_date;
                        }
                        // var_dump($params);die;
                        //Check Data Exist
                        $params_check = array(
                            'product_asset_name' => $product_name
                        );
                        $check_exists = $this->Asset_model->check_data_exist($params_check);
                        if(!$check_exists){

                            $set_data=$this->Asset_model->add_asset($params);
                            if($set_data){

                                $product_id = $set_data;
                                $get_data = $this->Asset_model->get_asset($product_id);

                                //Croppie Upload Image
                                $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                                if(strlen($post_upload) > 10){
                                    $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                                    if($upload_process->status == 1){
                                        if ($get_data && $get_data['product_id']) {
                                            $params_image = array(
                                                'product_image' => $upload_process->result['file_location']
                                            );
                                            // if (!empty($get_data['product_image'])) {
                                            //     if (file_exists(FCPATH . $get_data['product_image'])) {
                                            //         unlink(FCPATH . $get_data['product_image']);
                                            //     }
                                            // }
                                            $stat = $this->Asset_model->update_asset($product_id, $params_image);
                                        }
                                    }else{
                                        $return->message = 'Fungsi Gambar gagal';
                                    }
                                }

                                //Save Image if Exist
                                // $config['image_library'] = 'gd2';
                                // $config['upload_path'] = $upload_path_directory;
                                // $config['allowed_types'] = 'gif|jpg|png|jpeg';
                                // $this->upload->initialize($config);
                                // if (!$this->upload->do_upload('files')) {
                                //     $upload = $this->upload->data();
                                //     $raw_photo = time() . $upload['file_ext'];
                                //     $old_name = $upload['full_path'];
                                //     $new_name = $upload_path_directory . $raw_photo;
                                //     if (rename($old_name, $new_name)) {
                                //         $compress['image_library'] = 'gd2';
                                //         $compress['source_image'] = $upload_path_directory . $raw_photo;
                                //         $compress['create_thumb'] = FALSE;
                                //         $compress['maintain_ratio'] = TRUE;
                                //         $compress['width'] = 640;
                                //         $compress['height'] = 640;
                                //         $compress['new_image'] = $upload_path_directory . $raw_photo;
                                //         $this->load->library('image_lib', $compress);
                                //         $this->image_lib->resize();

                                //         if ($data && $data['product_id']) {
                                //             $params_image = array(
                                //                 'product_image' => base_url($upload_directory) . $raw_photo
                                //             );
                                //             if (!empty($data['product_image'])) {
                                //                 if (file_exists($upload_path_directory . $data['product_image'])) {
                                //                     unlink($upload_path_directory . $data['product_image']);
                                //                 }
                                //             }
                                //             $stat = $this->Asset_model->update_asset_custom(array('product_id' => $set_data), $params_image);
                                //         }
                                //     }
                                // }
                                //End of Save Image

                                //Set To Journal
                                if($config_post_to_journal==true){
                                    // $operator = $this->journal_for_asset('create',$set_data);
                                    $return->product_id = $set_data;
                                }

                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['product_asset_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['product_asset_name']
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['product_asset_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read":
                    $this->form_validation->set_rules('product_id', 'product_id', 'required');                
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $product_id   = !empty($post['product_id']) ? $post['product_id'] : 0;   
                        if(intval(strlen($product_id)) > 0){        
                            $set_data = array();
                            $datas = $this->Asset_model->get_asset($product_id);
                            if($datas){
                                $v = $datas;
                                $set_data = array(
                                    'product_id' => intval($v['product_id']),
                                    'product_flag' => intval($v['product_flag']),
                                    'product_date_created' => $v['product_date_created'],
                                    'product_date_updated' => $v['product_date_updated'],
                                    'product_type' => intval($v['product_type']),
                                    'product_image' => !empty($v['product_image']) ? $v['product_image'] : base_url('upload').'noimage.png',
                                    'product_type_name' => $v['product_type_name'],
                                    'product_asset_name' => $v['product_asset_name'],
                                    'product_asset_code' => $v['product_asset_code'],
                                    'product_asset_note' => $v['product_asset_note'],
                                    'product_asset_acquisition_date' => $v['product_asset_acquisition_date'],
                                    'product_asset_acquisition_value' => intval($v['product_asset_acquisition_value']),
                                    'product_asset_dep_flag' => intval($v['product_asset_dep_flag']),
                                    'product_asset_dep_method' => intval($v['product_asset_dep_method']),
                                    'product_asset_dep_period' => intval($v['product_asset_dep_period']),
                                    'product_asset_dep_percentage' => intval($v['product_asset_dep_percentage']),
                                    'product_asset_fixed_account_id' => intval($v['product_asset_fixed_account_id']),
                                    'product_asset_cost_account_id' => intval($v['product_asset_cost_account_id']),
                                    'product_asset_depreciation_account_id' => intval($v['product_asset_depreciation_account_id']),
                                    'product_asset_accumulated_depreciation_account_id' => intval($v['product_asset_accumulated_depreciation_account_id']),
                                    'product_asset_accumulated_depreciation_value' => intval($v['product_asset_accumulated_depreciation_value']),
                                    'product_asset_accumulated_depreciation_date' => $v['product_asset_accumulated_depreciation_date'],
                                    'fixed_account_id' => intval($v['fixed_account_id']),
                                    'fixed_account_code' => $v['fixed_account_code'],
                                    'fixed_account_name' => $v['fixed_account_name'],
                                    'cost_account_id' => intval($v['cost_account_id']),
                                    'cost_account_code' => $v['cost_account_code'],
                                    'cost_account_name' => $v['cost_account_name'],
                                    'depreciation_account_id' => intval($v['depreciation_account_id']),
                                    'depreciation_account_code' => $v['depreciation_account_code'],
                                    'depreciation_account_name' => $v['depreciation_account_name'],
                                    'accumulated_account_id' => intval($v['accumulated_account_id']),
                                    'accumulated_account_code' => $v['accumulated_account_code'],
                                    'accumulated_account_name' => $v['accumulated_account_name'],
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
                case "update":
                    $this->form_validation->set_rules('product_id', 'PRODUCT_ID', 'required');
                    $this->form_validation->set_rules('product_flag', 'PRODUCT_FLAG', 'required');
                    $this->form_validation->set_rules('product_asset_name', 'PRODUCT_ASSET_NAME', 'required');
                    $this->form_validation->set_rules('product_asset_code', 'PRODUCT_ASSET_CODE', 'required');
                    $this->form_validation->set_rules('product_asset_note', 'PRODUCT_ASSET_NOTE', 'required');
                    $this->form_validation->set_rules('product_asset_acquisition_date', 'PRODUCT_ASSET_ACQUISITION_DATE', 'required');
                    $this->form_validation->set_rules('product_asset_acquisition_value', 'PRODUCT_ASSET_ACQUISITION_VALUE', 'required');
                    $this->form_validation->set_rules('product_asset_dep_flag', 'PRODUCT_ASSET_DEP_FLAG', 'required');
                    $this->form_validation->set_rules('product_asset_dep_method', 'PRODUCT_ASSET_DEP_METHOD', 'required');
                    $this->form_validation->set_rules('product_asset_dep_period', 'PRODUCT_ASSET_DEP_PERIOD', 'required');
                    $this->form_validation->set_rules('product_asset_dep_percentage', 'PRODUCT_ASSET_DEP_PERCENTAGE', 'required');
                    $this->form_validation->set_rules('product_asset_fixed_account_id', 'PRODUCT_ASSET_FIXED_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('product_asset_cost_account_id', 'PRODUCT_ASSET_COST_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('product_asset_depreciation_account_id', 'PRODUCT_ASSET_DEPRECIATION_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('product_asset_accumulated_depreciation_account_id', 'PRODUCT_ASSET_ACCUMULATED_DEPRECIATION_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('product_asset_accumulated_depreciation_value', 'PRODUCT_ASSET_ACCUMULATED_DEPRECIATION_VALUE', 'required');
                    $this->form_validation->set_rules('product_asset_accumulated_depreciation_date', 'PRODUCT_ASSET_ACCUMULATED_DEPRECIATION_DATE', 'required');
                    $this->form_validation->set_rules('fixed_account_id', 'FIXED_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('fixed_account_code', 'FIXED_ACCOUNT_CODE', 'required');
                    $this->form_validation->set_rules('fixed_account_name', 'FIXED_ACCOUNT_NAME', 'required');
                    $this->form_validation->set_rules('cost_account_id', 'COST_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('cost_account_code', 'COST_ACCOUNT_CODE', 'required');
                    $this->form_validation->set_rules('cost_account_name', 'COST_ACCOUNT_NAME', 'required');
                    $this->form_validation->set_rules('depreciation_account_id', 'DEPRECIATION_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('depreciation_account_code', 'DEPRECIATION_ACCOUNT_CODE', 'required');
                    $this->form_validation->set_rules('depreciation_account_name', 'DEPRECIATION_ACCOUNT_NAME', 'required');
                    $this->form_validation->set_rules('accumulated_account_id', 'ACCUMULATED_ACCOUNT_ID', 'required');
                    $this->form_validation->set_rules('accumulated_account_code', 'ACCUMULATED_ACCOUNT_CODE', 'required');
                    $this->form_validation->set_rules('accumulated_account_name', 'ACCUMULATED_ACCOUNT_NAME', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $product_id = !empty($post['product_id']) ? $post['product_id'] : $post['product_id'];
                        $product_name = !empty($post['product_name']) ? $post['product_name'] : $post['product_name'];
                        $product_flag = !empty($post['product_flag']) ? $post['product_flag'] : $post['product_flag'];

                        if(strlen($product_id) > 1){

                            $params = array(
                                'product_flag' => !empty($post['product_flag']) ? intval($post['product_flag']) : null,
                                'product_date_created' => !empty($post['product_date_created']) ? $post['accumulated_account_name'] : null,
                                'product_date_updated' => !empty($post['product_date_updated']) ? $post['accumulated_account_name'] : null,
                                'product_asset_name' => !empty($post['product_asset_name']) ? $post['accumulated_account_name'] : null,
                                'product_asset_code' => !empty($post['product_asset_code']) ? $post['accumulated_account_name'] : null,
                                'product_asset_note' => !empty($post['product_asset_note']) ? $post['accumulated_account_name'] : null,
                                'product_asset_acquisition_date' => !empty($post['product_asset_acquisition_date']) ? $post['accumulated_account_name'] : null,
                                'product_asset_acquisition_value' => !empty($post['product_asset_acquisition_value']) ? intval($post['product_asset_acquisition_value']) : null,
                                'product_asset_dep_flag' => !empty($post['product_asset_dep_flag']) ? intval($post['product_asset_dep_flag']) : null,
                                'product_asset_dep_method' => !empty($post['product_asset_dep_method']) ? intval($post['product_asset_dep_method']) : null,
                                'product_asset_dep_period' => !empty($post['product_asset_dep_period']) ? intval($post['product_asset_dep_period']) : null,
                                'product_asset_dep_percentage' => !empty($post['product_asset_dep_percentage']) ? intval($post['product_asset_dep_percentage']) : null,
                                'product_asset_fixed_account_id' => !empty($post['product_asset_fixed_account_id']) ? intval($post['product_asset_fixed_account_id']) : null,
                                'product_asset_cost_account_id' => !empty($post['product_asset_cost_account_id']) ? intval($post['product_asset_cost_account_id']) : null,
                                'product_asset_depreciation_account_id' => !empty($post['product_asset_depreciation_account_id']) ? intval($post['product_asset_depreciation_account_id']) : null,
                                'product_asset_accumulated_depreciation_account_id' => !empty($post['product_asset_accumulated_depreciation_account_id']) ? intval($post['product_asset_accumulated_depreciation_account_id']) : null,
                                'product_asset_accumulated_depreciation_value' => !empty($post['product_asset_accumulated_depreciation_value']) ? intval($post['product_asset_accumulated_depreciation_value']) : null,
                                'product_asset_accumulated_depreciation_date' => !empty($post['product_asset_accumulated_depreciation_date']) ? $post['accumulated_account_name'] : null,
                                'fixed_account_id' => !empty($post['fixed_account_id']) ? intval($post['fixed_account_id']) : null,
                                'fixed_account_code' => !empty($post['fixed_account_code']) ? $post['accumulated_account_name'] : null,
                                'fixed_account_name' => !empty($post['fixed_account_name']) ? $post['accumulated_account_name'] : null,
                                'cost_account_id' => !empty($post['cost_account_id']) ? intval($post['cost_account_id']) : null,
                                'cost_account_code' => !empty($post['cost_account_code']) ? $post['accumulated_account_name'] : null,
                                'cost_account_name' => !empty($post['cost_account_name']) ? $post['accumulated_account_name'] : null,
                                'depreciation_account_id' => !empty($post['depreciation_account_id']) ? intval($post['depreciation_account_id']) : null,
                                'depreciation_account_code' => !empty($post['depreciation_account_code']) ? $post['accumulated_account_name'] : null,
                                'depreciation_account_name' => !empty($post['depreciation_account_name']) ? $post['accumulated_account_name'] : null,
                                'accumulated_account_id' => !empty($post['accumulated_account_id']) ? intval($post['accumulated_account_id']) : null,
                                'accumulated_account_code' => !empty($post['accumulated_account_code']) ? $post['accumulated_account_name'] : null,
                                'accumulated_account_name' => !empty($post['accumulated_account_name']) ? $post['accumulated_account_name'] : null,
                            );

                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                           
                            $set_update=$this->Asset_model->update_asset($product_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Asset_model->get_asset($product_id);
                                    
                                //Update Image if Exist
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
                                        $compress['width'] = 640;
                                        $compress['height'] = 640;
                                        $compress['new_image'] = $upload_path_directory . $raw_photo;
                                        $this->load->library('image_lib', $compress);
                                        $this->image_lib->resize();
                                        if ($get_data) {
                                            $params_image = array(
                                                'product_image' => base_url($upload_directory) . $raw_photo
                                            );
                                            if (!empty($get_data['product_image'])) {
                                                if (file_exists($upload_path_directory . $get_data['product_image'])) {
                                                    unlink($upload_path_directory . $get_data['product_image']);
                                                }
                                            }
                                            $stat = $this->Asset_model->update_asset_custom(array('product_id' => $product_id), $params_image);
                                        }
                                    }
                                }
                                //End of Save Image

                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$product_name;
                            }else{
                                $return->message='Gagal memperbarui '.$product_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;          
                case "delete":
                    $this->form_validation->set_rules('product_id', 'product_id', 'required');                
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $product_id   = !empty($post['product_id']) ? $post['product_id'] : 0;
                        $product_name = !empty($post['product_name']) ? $post['product_name'] : null;                                

                        if(strlen($product_id) > 0){
                            $get_data=$this->Asset_model->get_asset($product_id);
                            // $set_data=$this->Asset_model->delete_product($product_id);
                            $set_data = $this->Asset_model->update_asset_custom(array('product_id'=>$product_id),array('product_flag'=>4));                
                            if($set_data==true){    
                                /*
                                if (file_exists($get_data['product_image'])) {
                                    unlink($get_data['product_image']);
                                } 
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$product_name;
                            }else{
                                $return->message='Gagal menghapus '.$product_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update-flag":
                    $this->form_validation->set_rules('product_id', 'product_id', 'required');
                    $this->form_validation->set_rules('product_flag', 'product_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $product_id = !empty($post['product_id']) ? $post['product_id'] : 0;
                        if(strlen(intval($product_id)) > 1){
                            
                            $params = array(
                                'product_flag' => !empty($post['product_flag']) ? intval($post['product_flag']) : 0,
                            );
                            
                            $where = array(
                                'product_id' => !empty($post['product_id']) ? intval($post['product_id']) : 0,
                            );
                            
                            if($post['product_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['product_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['product_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $set_update=$this->Asset_model->update_asset_custom($where,$params);
                            if($set_update==true){
                                $get_data = $this->Asset_model->get_asset_custom($where);
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['product_name'];
                            }else{
                                $return->message='Gagal '.$set_msg;
                            }   
                        }else{
                            $return->message = 'Gagal mendapatkan data';
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
            
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = date("d-m-Y");

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            // $params = array();
            // $search = null;
            // $limit = 10;
            // $start = 0;
            // $order = null;
            // $dir = null;
            // $get_asset = $this->Asset_model->get_all_asset($params,$search,$limit,$start,$order,$dir);
            // var_dump($get_asset);die;
            $data['image_width'] = intval($this->image_width);
            $data['image_height'] = intval($this->image_height);
            
            $data['title'] = 'Asset';
            $data['_view'] = 'layouts/admin/menu/asset/index';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/asset/index_js.php',$data);
        }
    }
}

?>