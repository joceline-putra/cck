<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends MY_Controller{

    public $folder_upload = 'upload/file/';
    public $image_width   = 240;
    public $image_height  = 480;    
    public $file_size_limit = 1024; //in Byte
    
    function __construct(){
        parent::__construct();
        
        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }           
        $this->load->library('form_validation');                  
        $this->load->helper('form');

        $this->load->model('Aktivitas_model');
        $this->load->model('Approval_model');
        $this->load->model('File_model');                           
        $this->load->model('Order_model'); 
        $this->load->model('Transaksi_model');
        $this->load->model('Journal_model');                                      
    }

    function index(){
        if ($this->input->post()) {
            $data['session'] = $this->session->userdata();  
            $session_user_id = $data['session']['user_data']['user_id'];
            $session_branch_id = $data['session']['user_data']['branch']['id'];

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
                    $this->form_validation->set_rules('user_id', 'User Tujuan', 'required');
                    $this->form_validation->set_rules('trans_id', 'ID Dokumen', 'required');
                    $this->form_validation->set_rules('from_table', 'Table', 'required');                                        
                    $this->form_validation->set_rules('approval_level', 'Urutan Level', 'required');                                                            
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $next = true;
                        $approval_user      = !empty($this->input->post('user_id')) ? $this->input->post('user_id') : null;
                        $approval_session   = !empty($this->input->post('trans_session')) ? $this->input->post('trans_session') : null;
                        $approval_from_id   = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : null;                        
                        $from_table         = !empty($this->input->post('from_table')) ? $this->input->post('from_table') : null;
                        $approval_level     = !empty($this->input->post('approval_level')) ? intval($this->input->post('approval_level')) : 1;

                        //Pastikan Level Approval Urut dari angka kecil  1 - 4
                        $where_max = array(
                            'approval_from_table' => $from_table,
                            'approval_from_id' => $approval_from_id
                        );
                        $get_level = $this->Approval_model->get_approval_max('approval_level',$where_max);
                        // var_dump($get_level);die;
                        // if((!empty($get_level) || intval($get_level) > 0) and ($approval_level == 1)){
                        $approval_column = array(
                            '1' => 'Pertama', '2' => 'Kedua', '3' => 'Ketiga', '4' => 'Keempat'
                        );
                        if(!empty($get_level['approval_level']) and (intval($get_level['approval_level']))){                            
                            //Approval melebihi 1
                            if($approval_level == intval($get_level['approval_level'])){
                                $approval_level = $approval_level+4;
                            }
                        }else{ //Belum ada approval
                            // $approval_level = 11;
                            if($approval_level > 1){
                                $next = false;
                                $return->message = 'Harap mengajukan persetujuan urutan <b>'.$approval_column[$approval_level].'</b> dahulu';
                            }else{ 
                                $approval_level = 1;                                 
                            }
                        }

                        // var_dump($return->message);die;
                        if($next){
                            $params = array(
                                'approval_user_id' => $approval_user,
                                'approval_user_from' => $session_user_id,
                                'approval_level' => $approval_level,
                                'approval_session' => $this->random_code(20),
                                'approval_from_table' => $from_table,
                                'approval_from_id' => $approval_from_id,
                                'approval_date_created' => date('YmdHis'),
                                'approval_flag' => 0
                            );

                            if($from_table == 'orders'){
                                $get_data = $this->Order_model->get_order_custom(array('order_session'=>$approval_session));
                                $text_2 = $get_data['order_number'];
                                $text_3 = $get_data['contact_name'];
                            }else if($from_table == 'trans'){
                                $get_data = $this->Transaksi_model->get_transaksi_custom(array('trans_session'=>$approval_session));
                                $text_2 = $get_data['trans_number'];
                                $text_3 = $get_data['contact_name'];                        
                            }else if($from_table == 'journals'){
                                $get_data = $this->Journal_model->get_journal_custom(array('journal_session'=>$approval_session)); 
                                $text_2 = $get_data['journal_number'];
                                $text_3 = $get_data['contact_name'];                                               
                            }
                            // var_dump($params);die;
                            
                            //Check Data Exist
                            $params_check = array(
                                'approval_from_table' => $from_table,
                                'approval_from_id' => $approval_from_id,
                                'approval_level' => $approval_level
                            );
                            $check_exists = $this->Approval_model->check_data_exist($params_check);
                            // $check_exists = false;
                            if($check_exists==false){

                                $set_data=$this->Approval_model->add_approval($params);
                                if($set_data==true){
                                    /* Start Activity */
                                    $params = array(
                                        'activity_user_id' => $session_user_id,
                                        'activity_branch_id' => $session_branch_id,
                                        'activity_action' => 9,
                                        'activity_table' => 'approvals',
                                        'activity_table_id' => $set_data,                            
                                        'activity_text_1' => 'Persetujuan',
                                        'activity_text_2' => $text_2,      
                                        'activity_text_3' => $text_3,                                                        
                                        'activity_date_created' => date('YmdHis'),
                                        'activity_flag' => 1
                                    );
                                    // var_dump($params);die;
                                    $this->save_activity($params);    
                                    /* End Activity */            
                                    $return->status=1;
                                    $return->message='Berhasil mengirim permintaan persetujuan';
                                    $return->result= array(
                                        'id' => $set_data
                                    ); 
                                }
                            }else{
                                $return->message='Persetujuan sudah pernah diajukan';                    
                            }
                        }
                    }
                    $return->action=$action;
                    echo json_encode($return);                   
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $approval_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;   
                    if(intval($approval) > 0){        
                        $datas = $this->Approval_model->get_approval($approval_id);
                        if($datas==true){
                            /* Activity */
                            /*
                            $params = array(
                                'actvity_user_id' => $session['user_data']['user_id'],
                                'actvity_action' => 3,
                                'actvity_table' => 'approvals',
                                'actvity_table_id' => $approval_id,
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
                            $message = 'Data tidak ditemukan';
                        }
                    }else{
                        $return->message='Data tidak ditemukan ';
                    }
                    $return->action=$action;
                    echo json_encode($return);                               
                    break;
                case "update":
                    //$post_data = $this->input->post('data');
                    //$data = json_decode($post_data, TRUE);
                    $approval_id = !empty($this->input->post('id')) ? $this->input->post('id') : null;
                    $approval_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;
                    $approval_flag = !empty($this->input->post('approval_flag')) ? $this->input->post('approval_flag') : 0;
                    $approval_session = !empty($this->input->post('approval_session')) ? $this->input->post('approval_session') : null;
                    $approval_comment = !empty($this->input->post('approval_comment')) ? $this->input->post('approval_comment') : null;                    

                    if($approval_session != null){
                        $get_approval = $this->Approval_model->get_approval_custom(array('approval_session'=>$approval_session));
                        // var_dump($get_approval);die;
                        $where = array(
                            'approval_session' => $approval_session
                        );
                        $params = array(
                            'approval_date_action' => date("YmdHis"),
                            'approval_flag' => $approval_flag,
                            'approval_comment' => $approval_comment
                        );
                        $set_update=$this->Approval_model->update_approval_custom($where,$params);
                        if($set_update==true){
                            
                            $data = $this->Approval_model->get_approval_custom(array('approval_session'=>$approval_session));
                            if($data['approval_from_table'] == 'orders'){
                                $get_data = $this->Order_model->get_order_ref_custom(array('order_id'=> $data['approval_from_id']));
                                $doc_number = $get_data['order_number'];
                                $doc_contact = $get_data['contact_name'];
                            }
                            else if($data['approval_from_table'] == 'trans'){
                                $get_data = $this->Transaksi_model->get_transaksi_custom(array('trans_id'=> $data['approval_from_id']));
                                $doc_number = $get_data['trans_number'];
                                $doc_contact = $get_data['contact_name'];                                
                            }
                            else if($data['approval_from_table'] == 'journals'){
                                $get_data = $this->Journal_model->get_journal_custom(array('journal_id'=> $data['approval_from_id']));
                                $doc_number = $get_data['journal_number'];
                                $doc_contact = $get_data['contact_name'];                                
                            }

                            switch($approval_flag){
                                case 1: $set_message='menyetujui'; $set_text = 'Approve'; break;
                                case 2: $set_message='menunda'; $set_text = 'Tunda'; break;
                                case 3: $set_message='menolak'; $set_text = 'Tolak'; break;
                                case 4: $set_message='menghapus permintaan'; $set_text = 'Hapus'; break;
                                default: $set_message = '';
                            }

                            /* Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 9,
                                'activity_table' => 'approvals',
                                'activity_table_id' => $data['approval_id'],
                                'activity_text_1' => 'Persetujuan',
                                'activity_text_2' => $doc_number,
                                'activity_text_3' => $doc_contact,
                                'activity_text_4' => $set_text,    
                                'activity_text_5' => $approval_comment,                                                                
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);
                            /* End Activity */

                            $return->status  = 1;
                            $return->message = 'Berhasil '.$set_message;
                        }else{
                            $return->message='Gagal memproses '.$approval_session;
                        }                        
                    }
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;          
                case "delete":
                    $approval_id   = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                    $approval_name = !empty($this->input->post('name')) ? $this->input->post('name') : null;                                

                    if(intval($approval_id) > 0){
                        $get_data=$this->Approval_model->get_approval($approval_id);
                        $set_data=$this->Approval_model->delete_approval($approval_id);                
                        if($set_data==true){    
                            /*
                            if (file_exists($get_data['approval_image'])) {
                                unlink($get_data['approval_image']);
                            } 
                            */                            
                            /* Activity */
                            /*
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => $act,
                                'activity_table' => 'approvals',
                                'activity_table_id' => $id,
                                'activity_text_1' => '',
                                'activity_text_2' => ucwords(strtolower($approval_name)),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);                               
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil menghapus'.$approval_name;
                        }else{
                            $return->message='Gagal menghapus '.$approval_name;
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;             
                case "load":
                    $columns = array(
                        '0' => 'approval_id',
                        '1' => 'approval_name'
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
                    !empty($this->input->post('date_start')) ? $params['approval_date >'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 23:59:59')) : $params;
                    !empty($this->input->post('date_end')) ? $params['approval_date <'] = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59')) : $params;                
                    */

                    //Default Params for Master CRUD Form
                    $params['approval_id']   = !empty($this->input->post('approval_id')) ? $this->input->post('approval_id') : $params;
                    $params['approval_name'] = !empty($this->input->post('approval_name')) ? $this->input->post('approval_name') : $params;                

                    /*
                    if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                        $params['other_column'] = $this->input->post('other_column');
                    }
                    */
                    
                    $datas = $this->Approval_model->get_all_approvals($params, $search, $limit, $start, $order, $dir);
                    
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
                case "load-approval-list": //For Dashboard
                    $return->total_records = 0;
                    $return->user = $session_user_id;
                    
                    // $query = $this->db->query("
                    //     SELECT approvals.*, trans_number, contact_id, contact_name, fn_time_ago(`approval_date_created`) AS time_ago, 
                    //     CASE WHEN `approval_flag` = 0 THEN 'Pengajuan'
                    //         WHEN `approval_flag` = 1 THEN 'Disetujui'
                    //         WHEN `approval_flag` = 2 THEN 'Pending'
                    //         WHEN `approval_flag` = 3 THEN 'Tolak'
                    //         WHEN `approval_flag` = 4 THEN 'Dihapus'
                    //         ELSE '-'
                    //         END AS `approval_flag_name`,
                    //         fn_capitalize(`user_username`) AS user_from_username,
                    //         CONCAT(' mengajukan persetujuan ') AS `text_short`,
                    //         CONCAT(fn_capitalize(`user_username`),' mengajukan persetujuan ',`trans_number`,' @',`trans_total`) AS `text_full`,  
                    //         contact_name,
                    //         trans_id,              
                    //         IFNULL(trans_total,0) AS trans_total
                    //     FROM `approvals`
                    //     LEFT JOIN `trans` ON approval_from_id=trans_id
                    //     LEFT JOIN `contacts` ON trans_contact_id=contact_id
                    //     LEFT JOIN `users` ON approval_user_from=user_id
                    //     LEFT JOIN `types` ON trans_type=type_type AND type_for=2
                    //     WHERE approval_user_id='$session_user_id' AND approval_flag=0
                    //     ORDER BY approval_date_created DESC
                    // ");
                    // $query = $this->db->query("
                    //     SELECT approvals.*, order_number AS trans_number, contact_id, contact_name, fn_time_ago(`approval_date_created`) AS time_ago, 
                    //     CASE WHEN `approval_flag` = 0 THEN 'Pengajuan'
                    //         WHEN `approval_flag` = 1 THEN 'Disetujui'
                    //         WHEN `approval_flag` = 2 THEN 'Pending'
                    //         WHEN `approval_flag` = 3 THEN 'Tolak'
                    //         WHEN `approval_flag` = 4 THEN 'Dihapus'
                    //         ELSE '-'
                    //         END AS `approval_flag_name`,
                    //         fn_capitalize(`user_username`) AS user_from_username,
                    //         CONCAT(' mengajukan persetujuan ') AS `text_short`,
                    //         CONCAT(fn_capitalize(`user_username`),' mengajukan persetujuan ',`order_number`,' @',`order_total`) AS `text_full`,  
                    //         contact_name,
                    //         order_id AS trans_id,              
                    //         IFNULL(order_total,0) AS trans_total
                    //     FROM `approvals`
                    //     LEFT JOIN `orders` ON approval_from_id=order_id
                    //     LEFT JOIN `contacts` ON order_contact_id=contact_id
                    //     LEFT JOIN `users` ON approval_user_from=user_id
                    //     LEFT JOIN `types` ON order_type=type_type AND type_for=1
                    //     WHERE approval_user_id='$session_user_id' AND approval_flag=0
                    //     ORDER BY approval_date_created DESC
                    // ");                    
                    // $return->result = $query->result();   
                    
                    $prepare = "CALL sp_approval_list($session_user_id)";
                    $query=$this->db->query($prepare);
                    $result = $query->result_array();
                    mysqli_next_result($this->db->conn_id);          
                    $return->result = $result;  

                    if(count($result) > 0){
                        $return->total_records = count($result);
                    }
                    echo json_encode($return);                
                    break;
                case "load_approval_history":
                    $this->form_validation->set_rules('approval_from_table', 'Table Session', 'required');
                    $this->form_validation->set_rules('approval_from_id', 'Data ID', 'required');                                                                                
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $from_table = !empty($this->input->post('approval_from_table')) ? $this->input->post('approval_from_table') : null;
                        $from_id = !empty($this->input->post('approval_from_id')) ? $this->input->post('approval_from_id') : null;
                        $params = array(
                            'approval_from_table' => $from_table,
                            'approval_from_id' => $from_id
                        );
                        $search = null; $limit = null; $start = null; $order  = 'approval_date_created'; $dir = 'ASC';
                        $get_count = $this->Approval_model->get_all_approval_count($params);
                        if($get_count > 0){
                            $get_approval = $this->Approval_model->get_all_approval($params,$search,$limit,$start,$order,$dir);
                            foreach($get_approval as $v){
                                
                                $flag_name = '';
                                if($v['approval_flag'] == 0){ $flag_name = 'Pengajuan'; $flag_label = '<i class="fas fa-sticky-note"></i> '.$flag_name;
                                }else if($v['approval_flag'] == 1){ $flag_name = 'Disetujui'; $flag_label = '<i class="fas fa-check-square"></i> '.$flag_name;
                                }else if($v['approval_flag'] == 2){ $flag_name = 'Pending'; $flag_label = '<i class="fas fa-hand-paper"></i> '.$flag_name;
                                }else if($v['approval_flag'] == 3){ $flag_name = 'Tolak'; $flag_label = '<i class="fas fa-times"></i> '.$flag_name;
                                }else if($v['approval_flag'] == 4){ $flag_name = 'Dihapus'; $flag_label = '<i class="fas fa-trash"></i> '.$flag_name;
                                }

                                if($v['approval_from_table'] == 'orders'){ 
                                    $query = $this->db->query("
                                        SELECT order_id AS document_id, order_number AS document_number, order_session AS document_session FROM orders WHERE order_id='".$v['approval_from_id']."'
                                    "); $get_data = $query->row_array();                                                                    
                                }else if($v['approval_from_table'] == 'trans'){ 
                                    $query = $this->db->query("
                                        SELECT trans_id AS document_id, trans_number AS document_number, trans_session AS document_session FROM trans WHERE trans_id='".$v['approval_from_id']."'
                                    "); $get_data = $query->row_array();                           
                                }else if($v['approval_from_table'] == 'journals'){ 
                                    $query = $this->db->query("
                                        SELECT journal_id AS document_id, journal_number AS document_number, journal_session AS document_session FROM journals WHERE journal_id='".$v['approval_from_id']."'
                                    "); $get_data = $query->row_array();                                                                                           
                                }                
                                
                                $datas[] = array(
                                    'approval_id' => intval($v['approval_id']),
                                    'approval_level' => intval($v['approval_level']),
                                    'approval_session' => $v['approval_session'],
                                    'approval_from_table' => intval($v['approval_from_table']),
                                    'approval_from_id' => $v['approval_from_id'],
                                    'approval_date_created' => $v['approval_date_created'],
                                    'approval_comment' => !empty($v['approval_comment']) ? $v['approval_comment'] : '-',
                                    'flag' => array(
                                        'id' => $v['approval_flag'],
                                        'name' => $flag_name,
                                        'label' => $flag_label
                                    ),
                                    'approval_date_action' => $v['approval_date_action'],
                                    'user_from' => array(
                                        'id' => $v['from_user_id'],
                                        'username' => $v['from_user_username']
                                    ),
                                    'user_to' => array(
                                        'id' => $v['to_user_id'],
                                        'username' => $v['to_user_username']                                        
                                    ),
                                    'table' => array(
                                        'table_id' => intval($get_data['document_id']),
                                        'table_name' => $v['approval_from_table'],
                                        'table_session' => $get_data['document_session'],
                                        'table_number' => $get_data['document_number']
                                    ),
                                    'date' => array(
                                        'time_ago' => $v['time_ago'],
                                        'date_created' => $v['approval_date_created'],
                                        'date_action' => $v['approval_date_action'],                                        
                                    )
                                );
                            }
                            $return->status  = 1;
                            $return->message = 'Found Approval Data';
                            $return->result  = $datas;
                        }else{
                            $return->status  = 0;
                            $return->message = 'Approval Data Not Found';
                            $return->result  = [];
                        }
                        $return->total_records = $get_count;
                        echo json_encode($return);
                    }                    
                    break;
                case "load_file_history":
                    $this->form_validation->set_rules('file_from_table', 'Table Session', 'required');
                    $this->form_validation->set_rules('file_from_id', 'Data ID', 'required');                                                                                
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $from_table = !empty($this->input->post('file_from_table')) ? $this->input->post('file_from_table') : null;
                        $from_id = !empty($this->input->post('file_from_id')) ? $this->input->post('file_from_id') : null;
                        $params = array(
                            'file_from_table' => $from_table,
                            'file_from_id' => $from_id
                        );
                        $search = null; $limit = null; $start = null; $order  = 'file_name'; $dir = 'ASC';
                        $get_count = $this->File_model->get_all_file_count($params,$search);
                        if($get_count > 0){
                            $get_file = $this->File_model->get_all_file($params,$search,$limit,$start,$order,$dir);
                            foreach($get_file as $v){

                                if($v['file_from_table'] == 'orders'){ 
                                    $query = $this->db->query("
                                        SELECT order_id AS document_id, order_number AS document_number, order_session AS document_session FROM orders WHERE order_id='".$v['file_from_id']."'
                                    "); $get_data = $query->row_array();                                                                    
                                }else if($v['file_from_table'] == 'trans'){ 
                                    $query = $this->db->query("
                                        SELECT trans_id AS document_id, trans_number AS document_number, trans_session AS document_session FROM trans WHERE trans_id='".$v['file_from_id']."'
                                    "); $get_data = $query->row_array();                           
                                }else if($v['file_from_table'] == 'journals'){ 
                                    $query = $this->db->query("
                                        SELECT journal_id AS document_id, journal_number AS document_number, journal_session AS document_session FROM journals WHERE journal_id='".$v['file_from_id']."'
                                    "); $get_data = $query->row_array();                                                                                           
                                }else{
                                    $get_data['document_session'] = null;
                                    $get_data['document_number'] = null;                                    
                                }                

                                if($v['file_format'] == 'pdf'){
                                    $set_color = 'color:white;background-color:#d96a00!important;';
                                }else if(($v['file_format'] == 'jpg') or ($v['file_format'] == 'png')){
                                    $set_color = 'color:white;background-color:#3f69c7!important;';
                                }else if(($v['file_format'] == 'xls') or ($v['file_format'] == 'xlsx')){
                                    $set_color = 'color:white;background-color:#0aa65e!important;';
                                }else if($v['file_format'] == 'link'){
                                    $set_color = 'color:white;background-color:#4a4a4a!important;';
                                }else{
                                    $set_color = 'color:white;background-color:#0aa65e!important;';
                                }

                                // $file_src = site_url() . $this->folder_upload . $v['file_url'];
                                $file_src = site_url() . $v['file_url'];                                
                                if($v['file_type'] == 2){
                                    $file_src = $v['file_url'];
                                }

                                $datas[] = array(
                                    'file_id' => intval($v['file_id']),
                                    // 'file_from_table' => $v['file_from_table'],
                                    // 'file_from_id' => $v['file_from_id'],
                                    // 'file_name' => $v['file_name'],
                                    // 'file_url' => $v['file_url'],
                                    // 'file_format' => $v['file_format'],
                                    'file_type' => $v['file_type'],
                                    'file_session' => $v['file_session'],
                                    'file_flag' => intval($v['file_flag']),
                                    'file' => array(
                                        'name' => substr($v['file_name'],0,12),
                                        'src' => $file_src,
                                        'format' => $v['file_format'],
                                        'size' => $v['file_size'],
                                        'size_new' => $this->file_size_kb($v['file_size']),                                        
                                        'size_unit' => $this->file_unit_size(intval($v['file_size'] / 1024)),                                                                                
                                        'format_label' => '<label class="label" style="'.$set_color.'">'.$v['file_format'].'</label>'
                                    ),
                                    'table' => array(
                                        'table_id' => intval($v['file_from_table']),
                                        'table_name' => $v['file_from_table'],
                                        'table_session' => $get_data['document_session'],
                                        'table_number' => $get_data['document_number']
                                    ),     
                                    'date' => array(
                                        'time_ago' => $v['time_ago'],
                                        'date_created' => $v['file_date_created'],                                  
                                    ),    
                                    'user' => array(
                                        'id' => $v['user_id'],
                                        'username' => $v['user_username']                                        
                                    ),
                                );                                
                            }
                            $return->status  = 1;
                            $return->message = 'Found Attachment Data';
                            $return->result  = $datas;
                        }else{
                            $return->status  = 0;
                            $return->message = 'Attachment Data Not Found';
                            $return->result  = [];
                        }
                        $return->total_records = $get_count;
                        echo json_encode($return);
                    }
                    break;
                case "file_rename":
                    $file_id = !empty($this->input->post('file_id')) ? $this->input->post('file_id') : null;
                    $file_name = !empty($this->input->post('file_name')) ? $this->input->post('file_name') : null;
                    if(intval($file_id) > 0){
                        $where = array(
                            'file_id' => $file_id
                        );
                        $params = array(
                            'file_name' => $file_name
                        );
                        $set_update=$this->File_model->update_file_custom($where,$params);
                        if($set_update==true){
                            $get_data = $this->File_model->get_file($file_id);
                            $return->status  = 1;
                            $return->message = 'Berhasil ganti nama';
                            $return->result  = $get_data;
                        }else{
                            $return->message='Gagal ganti nama';
                        }                        
                    }
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;          
                case "file_delete":
                    $file_id = !empty($this->input->post('file_id')) ? $this->input->post('file_id') : null;
                    $file_name = !empty($this->input->post('file_name')) ? $this->input->post('file_name') : null;                        

                    if(intval($file_id) > 0){
                        $get_data=$this->File_model->get_file($file_id);
                        $set_data=$this->File_model->delete_file($file_id);      
                        $set_data = true;          
                        if($set_data){    
                            if($get_data['file_type'] == 1){
                                $file = FCPATH . $get_data['file_url'];
                                // var_dump($file);die;
                                if (file_exists($file)) {
                                    unlink($file);
                                }
                                $this->File_model->delete_file($file_id);
                            }
                            $return->status=1;
                            $return->message='Berhasil menghapus '. $get_data['file_name'];
                        }else{
                            $return->message='Gagal menghapus '. $get_data['file_name'];
                        } 
                        }else{
                            $return->message='Data tidak ditemukan';
                        }
                    $return->action=$action;
                    echo json_encode($return);                                
                    break;      
                case "file_create":
                    $this->form_validation->set_rules('trans_id', 'ID Dokumen', 'required');
                    $this->form_validation->set_rules('from_table', 'Table', 'required');                                                                                          
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                 
                        //Save File if Exist        
                        if(!empty($_FILES['source'])){
                            if(intval($_FILES['source']['size']) > 0){
                                //Save Data First
                                $file_session = $this->random_session(20);
                                $params = array(
                                    'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                                    'file_from_id' => !empty($post['trans_id']) ? $post['trans_id'] : null,
                                    'file_session' => $file_session,
                                    'file_date_created' => date("YmdHis"),
                                    'file_user_id' => $session_user_id,
                                    'file_type' => 1,
                                    'file_note' => !empty($post['note']) ? $post['note'] : 'Attachment'                            
                                );
                                $save_data = $this->File_model->add_file($params);

                                // Call Helper for upload
                                $image_config=array(
                                    'compress' => 1,
                                    'width'=>$this->image_width,
                                    'height'=>$this->image_height
                                );   
                                $upload_helper = upload_file_source($this->folder_upload, $this->input->post('source'),$image_config);
                                // var_dump($upload_helper);die;
                                if ($upload_helper['status'] == 1) {
                                    $params_image = array(
                                        'file_name' => !empty($post['note']).' - '.$upload_helper['result']['file_old_name'],
                                        'file_format' => str_replace(".","",$upload_helper['result']['file_ext']),
                                        'file_url' => $upload_helper['result']['file_location'],
                                        'file_size' => $upload_helper['result']['file_size']
                                    );
                                    /*
                                    if (!empty($data['file_url'])) {
                                        if (file_exists(FCPATH . $data['file_url'])) {
                                            unlink(FCPATH . $data['file_url']);
                                        }
                                    }
                                    */
                                    $stat = $this->File_model->update_file($save_data, $params_image);
                                    
                                    $return->message    = $upload_helper['message'];
                                    $return->status     = $upload_helper['status'];
                                    // $return->raw_file   = $upload_helper['file'];
                                    $return->return     = $upload_helper;                            
                                }else{
                                    $return->message = 'Error: '.$upload_helper['message'];
                                }     
                            }else{                           
                                $return->message = 'Gagal, File lebih dari '.($this->file_size_limit / 1024) .' MB';
                            }
                        }else{
                            $return->message = 'File tidak terbaca';
                        }
                    }   
                    echo json_encode($return);
                    break; 
                case "file_create_link":
                    $this->form_validation->set_rules('trans_id', 'ID Dokumen', 'required');
                    $this->form_validation->set_rules('from_table', 'Table', 'required');         
                    $this->form_validation->set_rules('file_url', 'URL', 'required');                                                                                                              
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{                 
                        //Save Data First
                        $file_session = $this->random_session(20);
                        $params = array(
                            'file_from_table' => !empty($post['from_table']) ? $post['from_table'] : null,
                            'file_from_id' => !empty($post['trans_id']) ? $post['trans_id'] : null,
                            'file_session' => $file_session,
                            'file_date_created' => date("YmdHis"),
                            'file_user_id' => $session_user_id,
                            'file_type' => 2,
                            'file_format' => 'link'                            
                        );

                        $params['file_url'] = !empty($this->input->post('file_url')) ? $this->input->post('file_url') : null;
                        $params['file_name'] = !empty($this->input->post('file_name')) ? $this->input->post('file_name') : $params['file_url'];

                        $save_data = $this->File_model->add_file($params);
                        if ($save_data) {
                            $return->status     = 1;
                            $return->message    = 'Berhasil menyimpan';
                            $return->result     = array(
                                'id' => $save_data,
                                'name' => $params['file_name']
                            );                            
                        }else{
                            $return->message = 'Gagal menyimpan';
                        }  
                    }   
                    echo json_encode($return);
                    break;                                                         
                default:
                    // Date Now
                    $firstdate = new DateTime('first day of this month');
                    $firstdateofmonth = $firstdate->format('Y-m-d');        
                    $datenow =date("Y-m-d");         
                    $data['first_date'] = $firstdateofmonth;
                    $data['end_date'] = $datenow;      
            }
        }else{
            // Date Now
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('Y-m-d');        
            $datenow =date("Y-m-d");         
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = $datenow;

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */

            $data['title'] = 'Approval';
            $data['_view'] = 'approval/index';
            $this->load->view('layouts/index',$data);
            $this->load->view('approval/js.php',$data);         
        }
    }
    /*
    function file_unit_size($bytes){
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 0) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 0) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 0) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    } 
    */
    function file_unit_size($bytes){
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 0) . ' TB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 0) . ' GB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 0) . ' MB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' KB';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' KB';
        }
        else
        {
            $bytes = '0 KB';
        }

        return $bytes;
    }    
    function file_size_kb($a){
        if($a > 1024){
            $b = number_format($a/1024,0).' mb';
        }else{
            $b = number_format($a,0).' kb';
        }
        return $b;
    } 
    function format_byte($bytes) {
        if ($bytes > 0) {
            $i = floor(log($bytes) / log(1024));
            $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
            return sprintf('%.02F', round($bytes / pow(1024, $i),1)) * 1 . ' ' . @$sizes[$i];
        } else {
            return 0;
        }
    }   
}

?>