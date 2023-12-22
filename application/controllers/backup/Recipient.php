<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls as readerXls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as readerXlsx;

class Recipient extends MY_Controller{

    var $folder_upload = 'upload/recipient/';
    var $image_width   = 250;
    var $image_height  = 250;

    function __construct(){
        parent::__construct();
		if (!$this->is_logged_in()) {
			// redirect(base_url("login"));
			//Will Return to Last URL Where session is empty
			$this->session->set_userdata('url_before', base_url(uri_string()));
			$this->session->set_userdata('url_before_params',$this->input->post());
			redirect(base_url("login/return_url"));
		}
        $this->load->model('User_model');
        $this->load->model('Recipient_model');
    }
    function index(){
        if ($this->input->post()) {    
            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            $upload_directory = $this->folder_upload;     
            $upload_path_directory = $upload_directory;

            $data['session'] = $this->session->userdata();  
            $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
            $session_branch_id = (!empty($data['session']['user_data']['branch']['id']) ? intval($data['session']['user_data']['branch']['id']) : 0);
            
            $post = $this->input->post();
            $get  = $this->input->get();
            $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
            
            switch($action){
                case "load":
                    $columns = array(
                        '0' => 'recipient_name',
                        '1' => 'recipient_phone',
                        '2' => 'recipient_email',                                                
                        '3' => 'group_name'
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
                    if($session_user_id){
                        $params['recipient_branch_id'] = $session_branch_id;
                    }                    
                    /* If Form Mode Transaction CRUD not Master CRUD
                    !empty($post['date_start']) ? $params['recipient_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                    !empty($post['date_end']) ? $params['recipient_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;
                    */

                    //Default Params for Master CRUD Form
                    // $params['recipient_id']   = !empty($post['recipient_id']) ? $post['recipient_id'] : $params;
                    // $params['recipient_name'] = !empty($post['recipient_name']) ? $post['recipient_name'] : $params;

                    /*
                        if($post['other_column'] && $post['other_column'] > 0) {
                            $params['other_column'] = $post['other_column'];
                        }
                        
                    */
                        if($post['filter_flag'] !== "All") {
                            $params['recipient_flag'] = $post['filter_flag'];
                        }
                    
                    $get_count = $this->Recipient_model->get_all_recipient_count($params, $search);
                    if($get_count > 0){
                        $get_data = $this->Recipient_model->get_all_recipient($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = array();
                    }
                    $return->search              = $search;
                    $return->params              = $params;
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "create":               
                    // $data = base64_decode($post);
                    // $data = json_decode($post, TRUE);
                    // $this->form_validation->set_rules('recipient_group_id', 'RECIPIENT_GROUP_ID', 'required');
                    $this->form_validation->set_rules('recipient_name', 'Nama', 'required');
                    // $this->form_validation->set_rules('recipient_phone', 'RECIPIENT_PHONE', 'required');
                    // $this->form_validation->set_rules('recipient_email', 'RECIPIENT_EMAIL', 'required');
                    // $this->form_validation->set_rules('recipient_birth', 'RECIPIENT_BIRTH', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $recipient_name = !empty($post['recipient_name']) ? $post['recipient_name'] : null;
                        $recipient_flag = !empty($post['recipient_flag']) ? $post['recipient_flag'] : 0;
                        $recipient_session = $this->random_code(8);

                        $params = array(
                            'recipient_branch_id' => !empty($session_branch_id) ? intval($session_branch_id) : null,
                            'recipient_name' => !empty($post['recipient_name']) ? $post['recipient_name'] : null,
                            'recipient_phone' => !empty($post['recipient_phone']) ? $post['recipient_phone'] : null,
                            'recipient_email' => !empty($post['recipient_email']) ? $post['recipient_email'] : null,
                            'recipient_session' => $recipient_session,
                            'recipient_date_created' => date("YmdHis"),
                            'recipient_flag' => $recipient_flag,
                            'recipient_birth' => !empty($post['recipient_birth']) ? substr($post['recipient_birth'],6,4).'-'.substr($post['recipient_birth'],3,2).'-'.substr($post['recipient_birth'],0,2) : null,                                                        
                        );

                        if($post['recipient_group_id'] > 0){
                            $params['recipient_group_id'] = intval($post['recipient_group_id']);                        
                        }

                        // var_dump($params);die;
                        //Check Data Exist
                        // $params_check = array(
                            // 'recipient_name' => $recipient_name
                        // );
                        // $check_exists = $this->Recipient_model->check_data_exist($params_check);
                        $check_exists = false;
                        if(!$check_exists){

                            $set_data=$this->Recipient_model->add_recipient($params);
                            if($set_data){

                                $recipient_id = $set_data;
                                $data = $this->Recipient_model->get_recipient($recipient_id);

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

                                            if ($data && $data['recipient_id']) {
                                                $params_image = array(
                                                    'recipient_image' => $upload_directory . $raw_photo
                                                );
                                                if (!empty($data['recipient_image'])) {
                                                    if (file_exists($upload_path_directory . $data['recipient_image'])) {
                                                        unlink($upload_path_directory . $data['recipient_image']);
                                                    }
                                                }
                                                $stat = $this->Recipient_model->update_recipient_custom(array('recipient_id' => $set_data), $params_image);
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
                                        if ($data && $data['recipient_id']) {
                                            $params_image = array(
                                                'recipient_url' => $upload_process->result['file_location']
                                            );
                                            if (!empty($data['recipient_url'])) {
                                                if (file_exists($upload_path_directory . $data['recipient_url'])) {
                                                    unlink($upload_path_directory . $data['recipient_url']);
                                                }
                                            }
                                            $stat = $this->Recipient_model->update_recipient_custom(array('recipient_id' => $set_data), $params_image);
                                        }
                                    }else{
                                        $return->message = 'Fungsi Gambar gagal';
                                    }
                                }
                                //End of Croppie

                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$post['recipient_name'];
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['recipient_name'],
                                    'session' => $recipient_session
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$post['recipient_name'];
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read":              
                    $this->form_validation->set_rules('recipient_id', 'recipient_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $recipient_id   = !empty($post['recipient_id']) ? $post['recipient_id'] : 0;
                        if(intval(strlen($recipient_id)) > 0){        
                            $datas = $this->Recipient_model->get_recipient($recipient_id);
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
                    $this->form_validation->set_rules('recipient_id', 'ID', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $recipient_id = !empty($post['recipient_id']) ? $post['recipient_id'] : $post['recipient_id'];
                        $recipient_name = !empty($post['recipient_name']) ? $post['recipient_name'] : $post['recipient_name'];
                        $recipient_flag = !empty($post['recipient_flag']) ? $post['recipient_flag'] : $post['recipient_flag'];

                        if(strlen($recipient_id) > 0){
                            $params = array(
                                'recipient_branch_id' => !empty($session_branch_id) ? intval($session_branch_id) : null,
                                'recipient_name' => !empty($post['recipient_name']) ? $post['recipient_name'] : null,
                                'recipient_phone' => !empty($post['recipient_phone']) ? $post['recipient_phone'] : null,
                                'recipient_email' => !empty($post['recipient_email']) ? $post['recipient_email'] : null,
                                'recipient_flag' => $recipient_flag,
                                'recipient_birth' => !empty($post['recipient_birth']) ? substr($post['recipient_birth'],6,4).'-'.substr($post['recipient_birth'],3,2).'-'.substr($post['recipient_birth'],0,2) : null,                                                        
                            );

                            if($post['recipient_group_id'] > 0){
                                $params['recipient_group_id'] = intval($post['recipient_group_id']);                        
                            }                     
                            // var_dump($params);die;       
                            /*
                            if(!empty($data['password'])){
                                $params['password'] = md5($data['password']);
                            }
                            */
                            
                            $set_update=$this->Recipient_model->update_recipient($recipient_id,$params);
                            if($set_update){
                                
                                $get_data = $this->Recipient_model->get_recipient($recipient_id);
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$recipient_name;
                            }else{
                                $return->message='Gagal memperbarui '.$recipient_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "delete":               
                    $this->form_validation->set_rules('recipient_id', 'recipient_id', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $recipient_id   = !empty($post['recipient_id']) ? $post['recipient_id'] : 0;
                        $recipient_name = !empty($post['recipient_name']) ? $post['recipient_name'] : null;

                        if(strlen($recipient_id) > 0){
                            $get_data=$this->Recipient_model->get_recipient($recipient_id);
                            // $set_data=$this->Recipient_model->delete_recipient($recipient_id);
                            $set_data = $this->Recipient_model->update_recipient_custom(array('recipient_id'=>$recipient_id),array('recipient_flag'=>4));                
                            if($set_data){
                                /*
                                $file = FCPATH.$this->folder_upload.$get_data['recipient_image'];
                                if (file_exists($file)) {
                                    unlink($file);
                                }
                                */
                                $return->status=1;
                                $return->message='Berhasil menghapus '.$recipient_name;
                            }else{
                                $return->message='Gagal menghapus '.$recipient_name;
                            } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    }
                    break;
                case "update_flag":             
                    $this->form_validation->set_rules('recipient_id', 'recipient_id', 'required');
                    $this->form_validation->set_rules('recipient_flag', 'recipient_flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $recipient_id = !empty($post['recipient_id']) ? $post['recipient_id'] : 0;
                        if(intval($recipient_id) > 0){
                            
                            $params = array(
                                'recipient_flag' => !empty($post['recipient_flag']) ? intval($post['recipient_flag']) : 0,
                            );
                            
                            $where = array(
                                'recipient_id' => !empty($post['recipient_id']) ? intval($post['recipient_id']) : 0,
                            );
                            
                            if($post['recipient_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['recipient_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['recipient_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            if($post['recipient_flag'] == 4){
                                $params['recipient_url'] = null;
                            }

                            $get_data = $this->Recipient_model->get_recipient_custom($where);
                            if($get_data){
                                $set_update=$this->Recipient_model->update_recipient_custom($where,$params);
                                if($set_update){
                                    if($post['recipient_flag'] == 4){
                                        /*
                                        $file = FCPATH.$this->folder_upload.$get_data['recipient_image'];
                                        if (file_exists($file)) {
                                            unlink($file);
                                        }
                                        */
                                    }
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['recipient_name'];
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
                case "create_recipient_group":                    
                    $this->form_validation->set_rules('group_name', 'Nama Group', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{

                        $group_name = !empty($post['group_name']) ? $post['group_name'] : null;
                        $group_session = $this->random_code(20);

                        $params = array(
                            'group_branch_id' => !empty($session_branch_id) ? intval($session_branch_id) : null,
                            'group_name' => $group_name,
                            'group_date_created' => date("YmdHis"),
                        );
                        //Check Data Exist
                        $params_check = array(
                            'group_branch_id' => !empty($session_branch_id) ? intval($session_branch_id) : null,                            
                            'group_name' => $group_name
                        );
                        $check_exists = $this->Recipient_model->check_data_exist_recipient_group($params_check);
                        if(!$check_exists){

                            $set_data=$this->Recipient_model->add_recipient_group($params);
                            if($set_data){

                                $recipient_item_id = $set_data;
                                $data = $this->Recipient_model->get_recipient_group($recipient_item_id);
                                $return->status=1;
                                $return->message='Berhasil menambahkan '.$group_name;
                                $return->result= array(
                                    'id' => $set_data,
                                    'name' => $post['group_name'],
                                ); 
                            }else{
                                $return->message='Gagal menambahkan '.$group_name;
                            }
                        }else{
                            $return->message='Data sudah ada';
                        }
                    }
                    break;
                case "read_recipient_group":                   
                    $this->form_validation->set_rules('group_id', 'ID Group', 'required');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                
                        $group_id   = !empty($post['group_id']) ? $post['group_id'] : 0;
                        if(intval($group_id) > 0){        
                            $datas = $this->Recipient_model->get_recipient_group($group_id);
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
                case "update_recipient_group":                      
                    $this->form_validation->set_rules('group_id', 'ID Group', 'required');
                    $this->form_validation->set_message('required', '{field} tidak ditemukan');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $group_id = !empty($post['group_id']) ? $post['group_id'] : $post['group_id'];
                        $group_name = !empty($post['group_name']) ? $post['group_name'] : $post['group_name'];

                        if(intval($group_id) > 0){
                            $params = array(
                                'group_name' => $group_name,
                            );
                           
                            $set_update=$this->Recipient_model->update_recipient_group($group_id,$params);
                            if($set_update){
                                $get_data = $this->Recipient_model->get_recipient_group($group_id);
                                $return->status  = 1;
                                $return->message = 'Berhasil memperbarui '.$group_name;
                            }else{
                                $return->message='Gagal memperbarui '.$group_name;
                            }   
                        }else{
                            $return->message = "Gagal memperbarui";
                        } 
                    }
                    break;
                case "load_recipient_group":
                    $columns = array(
                        '0' => 'group_id',
                        '1' => 'group_name'
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
                        'group_branch_id' => $session_branch_id
                    );

                    //Default Params for Master CRUD Form
                    // $params['recipient_item_id']   = !empty($post['recipient_item_id']) ? $post['recipient_item_id'] : $params;
                    // $params['recipient_item_name'] = !empty($post['recipient_item_name']) ? $post['recipient_item_name'] : $params;

                    if($post['filter_group_flag'] && $post['filter_group_flag'] !== 'All') {
                        $params['group_flag'] = $post['filter_group_flag'];
                    }
                    $get_count = $this->Recipient_model->get_all_recipient_group_count($params,$search);                    
                    if($get_count > 0){
                        $get_data = $this->Recipient_model->get_all_recipient_group($params, $search, $limit, $start, $order, $dir);
                        $return->total_records   = $get_count;
                        $return->status          = 1; 
                        $return->result          = $get_data;
                    }else{
                        $return->total_records   = 0;
                        $return->result          = array();
                    }
                    $return->params              = $params;
                    $return->message             = 'Load '.$return->total_records.' data';
                    $return->recordsTotal        = $return->total_records;
                    $return->recordsFiltered     = $return->total_records;
                    break;
                case "update_flag_group":              
                    $this->form_validation->set_rules('group_id', 'ID', 'required');
                    $this->form_validation->set_rules('group_flag', 'Flag', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $group_id = !empty($post['group_id']) ? $post['group_id'] : 0;
                        if(intval($group_id) > 0){
                            
                            $params = array(
                                'group_flag' => !empty($post['group_flag']) ? intval($post['group_flag']) : 0,
                            );
                            
                            $where = array(
                                'group_id' => !empty($post['group_id']) ? intval($post['group_id']) : 0,
                            );
                            
                            if($post['group_flag']== 0){
                                $set_msg = 'nonaktifkan';
                            }else if($post['group_flag']== 1){
                                $set_msg = 'mengaktifkan';
                            }else if($post['group_flag']== 4){
                                $set_msg = 'menghapus';
                            }else{
                                $set_msg = 'mendapatkan data';
                            }

                            $get_data = $this->Recipient_model->get_recipient_group_custom($where);
                            if($get_data){
                                $set_update=$this->Recipient_model->update_recipient_group_custom($where,$params);
                                if($set_update){
                                    $return->status  = 1;
                                    $return->message = 'Berhasil '.$set_msg.' '.$get_data['group_name'];
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
                case "import_recipient_from_excel":           
					$this->form_validation->set_rules('group_id', 'ID Group', 'required');
					$this->form_validation->set_message('required', '{field} wajib diisi');
					if ($this->form_validation->run() == FALSE) {
						$return->message = validation_errors();
					} else {
						$return->data_exists = array();
						$return->total_exists = 0;

						$total_exists = 0;
						$data_exists = array();

						$contact_type = $this->input->post('contact_type');
						$contact_group_session = $this->random_code(8);

						//Excel is comming
						if (isset($_FILES["excel_file"]["name"])) {
							$file_tmp   = $_FILES['excel_file']['tmp_name'];
							$file_name  = $_FILES['excel_file']['name'];
							$file_size  = $_FILES['excel_file']['size'];
							$file_type  = $_FILES['excel_file']['type'];

							$object = PHPExcel_IOFactory::load($file_tmp);
							$sheets = 0;

                            $get_account_payable = $this->get_account_map_for_transaction($session_branch_id,4,1); //Account Payable
                            $get_account_receivable = $this->get_account_map_for_transaction($session_branch_id,4,2); //Account Receivable
                            
							//Foreach
							foreach ($object->getWorksheetIterator() as $worksheet) {

								//Sheet 1
								if ($sheets == 0) {
									$highest_row    = $worksheet->getHighestRow();
									$highest_column = $worksheet->getHighestColumn();
									$numbers = 0;
									for ($row = 3; $row <= $highest_row; $row++) {
										
											$contact_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
											$contact_phone = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
											$contact_email = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
											$contact_birth = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

                                            // $params_check = [
                                            //     'recipient_name' => $contact_name,
                                            // ];

										//Check data exist
                                        // $check_exists = $this->Recipient_model->check_data_exist_recipient($params_check, null);
                                        $check_exists = false;
                                        if ($check_exists) {
                                            // $data_exists[] = array('contact_identity_number' => $contact_employee_number);
                                            $total_exists++;
                                        } else {
                                            $recipient_session = $this->random_code(8);
                                            $params = array(
                                                'recipient_group_id' => !empty($post['group_id']) ? $post['group_id'] : null,
                                                'recipient_branch_id' => !empty($session_branch_id) ? intval($session_branch_id) : null,
                                                'recipient_name' => !empty($contact_name) ? $contact_name : null,
                                                'recipient_phone' => !empty($contact_phone) ? $contact_phone : null,
                                                'recipient_email' => !empty($contact_email) ? $contact_email : null,
                                                'recipient_session' => $recipient_session,
                                                'recipient_date_created' => date("YmdHis"),
                                                'recipient_flag' => 1,
                                                'recipient_birth' => $contact_birth
                                                // 'recipient_birth' => !empty($contact_birth) ? substr($contact_birth,6,4).'-'.substr($contact_birth,3,2).'-'.substr($contact_birth,0,2) : null,                                                        
                                            );
                                            var_dump($params);die;
                                        }
                                        $numbers++;
										// }
									} //End looping
								}
								$sheets++;
							}

							if ($total_exists == 0) {
								// Bulk Insert Process
								if ($this->db->insert_batch('recipients', $params)) {
									$return->status = 1;
									$return->message = 'Berhasil import data';
									$return->result = array();
								} else {
									$return->message = 'Gagal import';
								}
							} else {
								$return->message = 'Gagal import';
								$return->data_exists = $data_exists;
								$return->total_exists = $total_exists;
							}
						} else {
							$return->message = 'Excel not found';
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
            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            
            $data['title'] = 'Kontak Broadcast';
            $data['_view'] = 'layouts/admin/menu/message/recipient';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/message/recipient_js.php',$data);
        }
    }
}

?>