<?php 
/* @AUTHOR: Joe Witaya */ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Minio extends MY_Controller{
    var $folder_upload = 'uploads/link/';
    function __construct(){
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->load->model('User_model');        
        $this->load->model('Link_model');
        $this->load->model('Branch_model');
    }
    function index(){

        $data['session'] = $this->session->userdata();  
        $session_user_id = !empty($data['session']['user_data']['user_id']) ? $data['session']['user_data']['user_id'] : null;
        $session_user_session = !empty($data['session']['user_data']['user_session']) ? $data['session']['user_data']['user_session'] : null;            
        $session_is_logged_in = !empty($data['session']['logged_in']) ? true : false;
        // var_dump($session_is_logged_in);die;

        if($session_is_logged_in){
            if ($this->input->post()) {    
                $return = new \stdClass();
                $return->status = 0;
                $return->message = '';
                $return->result = '';

                $upload_directory = $this->folder_upload;     
                $upload_path_directory = FCPATH . $upload_directory;

                $post = $this->input->post();
                $get  = $this->input->get();
                $action = !empty($this->input->post('action')) ? $this->input->post('action') : false;
                switch($action){
                    case "create":
                        $this->form_validation->set_rules('link_name', 'link_name', 'trim|required|min_length[5]');
                        // $this->form_validation->set_rules('captcha', 'captcha', 'trim|required|min_length[6]|max_length[20]');                    
                        $this->form_validation->set_message('required', '{field} wajib diisi');
                        if ($this->form_validation->run() == FALSE){
                            $return->message = validation_errors();
                        }else{

                                $post_captcha = !empty($post['captcha']) ? ltrim(rtrim($post['captcha'])) : 0;
                                $session_captcha = $data['session']['captcha'];
                                $link_name = !empty($post['link_name']) ? $this->safe_url($post['link_name']) : null;
                                $link_label = !empty($post['link_label']) ? $post['link_label'] : null;                            
                                $link_status = !empty($post['link_flag']) ? $post['link_flag'] : 0;

                                $link_name = filter_var($link_name, FILTER_SANITIZE_URL);

                                // if($post_captcha === $session_captcha){

                                    // if (filter_var($link_name, FILTER_VALIDATE_URL) === false) {
                                    // $return->message='URL tidak valid'.$this->safe_url($post['link_name']);
                                    // }else{
                                        $link_name = $this->safe_url($link_name);
                                        // var_dump($link_name);die;
                                        $params = array(
                                            'link_name' => $link_name,
                                            'link_label' => $link_label,
                                            'link_branch_session' => $this->branch_session,
                                            'link_user_session' => !empty($session_user_session) ? $session_user_session : null,
                                        );
                                        // var_dump($session_captcha);die;
                                        //Check Data Exist
                                        /*
                                        $params_check = array(
                                            'link_name' => $link_name,
                                            'link_branch_session' => $this->branch_session
                                        );
                                        $check_exists = $this->Link_model->check_data_exist($params_check);
                                        */
                                        $check_exists = false;
                                        if($check_exists==false){

                                            $set_data=$this->Link_model->add_link($params);
                                            if($set_data==true){

                                                $link_id = $set_data;
                                                $get_data = $this->Link_model->get_link($link_id);

                                                $get_branch = $this->Branch_model->get_branch_custom(array('branch_session'=>$this->branch_session));

                                                $return->status=1;
                                                $return->message='Berhasil menambahkan '.$post['link_name'];
                                                $return->result= array(
                                                    'link_id' => $set_data,
                                                    'link_name' => $link_name,
                                                    'link_session' => $get_data['link_session'],
                                                    'link_url' => $get_branch['branch_url'].'/'.$get_data['link_url'],
                                                    'link_label' =>$get_data['link_label']
                                                ); 
                                                // $return->captcha = array($post_captcha,$session_captcha);
                                                // $this->session->set_userdata('return',$return->result);
                                                $this->session->set_flashdata('return_url',$return->result);
                                                $this->session->set_flashdata('return_url',$return->result); 
                                                // var_dump($post['redirect']);die;                                           
                                                if($post['redirect'] == 'false'){
                                                    $return->message='Successfully';
                                                }else{
                                                    redirect(base_url());
                                                }
                                            }else{
                                                $return->message='Gagal menambahkan '.$post['link_name'];
                                            }
                                        }else{
                                            $return->message='Data sudah ada';
                                        }
                                    // }
                                // }else{
                                    // $return->message='Captcha tidak valid';
                                // }
                        }
                        // $return->action=$action;
                        break;
                    case "read":
                        $this->form_validation->set_rules('link_id', 'link_id', 'required');                
                        if ($this->form_validation->run() == FALSE){
                            $return->message = validation_errors();
                        }else{                
                            $link_id   = !empty($post['link_id']) ? $post['link_id'] : 0;   
                            if(intval(strlen($link_id)) > 0){        
                                $datas = $this->Link_model->get_link($link_id);
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
                        $return->action=$action;                               
                        break;
                    case "update":
                        $this->form_validation->set_rules('link_id', 'link_id', 'required');
                        $this->form_validation->set_message('required', '{field} tidak ditemukan');
                        if ($this->form_validation->run() == FALSE){
                            $return->message = validation_errors();
                        }else{
                            $link_id = !empty($post['link_id']) ? $post['link_id'] : $post['link_id'];
                            $link_name = !empty($post['link_name']) ? $post['link_name'] : $post['link_name'];
                            $link_flag = !empty($post['link_flag']) ? $post['link_flag'] : $post['link_flag'];

                            if(strlen($link_id) > 1){
                                $params = array(
                                    'link_name' => $link_name,
                                    'link_date_updated' => date("YmdHis"),
                                    'link_flag' => $link_flag
                                );

                                /*
                                if(!empty($data['password'])){
                                    $params['password'] = md5($data['password']);
                                }
                                */
                            
                                $set_update=$this->Link_model->update_link($link_id,$params);
                                if($set_update==true){
                                    
                                    $data = $this->Link_model->get_link($link_id);
                                        
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
                                            if ($data && $link_id) {
                                                $params_image = array(
                                                    'link_image' => base_url($upload_directory) . $raw_photo
                                                );
                                                if (!empty($data['link_image'])) {
                                                    if (file_exists($upload_path_directory . $data['link_image'])) {
                                                        unlink($upload_path_directory . $data['link_image']);
                                                    }
                                                }
                                                $stat = $this->Link_model->update_link_custom(array('link_id' => $link_id), $params_image);
                                            }
                                        }
                                    }
                                    //End of Save Image

                                    $return->status  = 1;
                                    $return->message = 'Berhasil memperbarui '.$link_name;
                                }else{
                                    $return->message='Gagal memperbarui '.$link_name;
                                }   
                            }else{
                                $return->message = "Gagal memperbarui";
                            } 
                        }
                        $return->action=$action;
                        break;          
                    case "update-label":
                            $this->form_validation->set_rules('session', 'Session', 'required');
                            $this->form_validation->set_rules('label', 'Label', 'required');                        
                            $this->form_validation->set_message('required', '{field} tidak ditemukan');
                            if ($this->form_validation->run() == FALSE){
                                $return->message = validation_errors();
                            }else{
                                $link_session = !empty($post['session']) ? $post['session'] : $post['session'];
                                $link_label = !empty($post['label']) ? $post['label'] : $post['label'];
                                
                                if(strlen($link_session) > 1){
                                    $params = array(
                                        'link_label' => $link_label
                                    );
                                
                                    $set_update=$this->Link_model->update_link_custom(array('link_session'=>$link_session),$params);
                                    if($set_update==true){
                                        
                                        $data = $this->Link_model->get_link_custom(array('link_session'=>$link_session));
        
                                        $return->status  = 1;
                                        $return->message = 'Update Success';
                                    }else{
                                        $return->message='Unsuccessfuly update';
                                    }   
                                }else{
                                    $return->message = "Failed";
                                } 
                            }
                            $return->action=$action;
                            break;          
                    case "delete":
                        $this->form_validation->set_rules('link_session', 'Link', 'required');                
                        if ($this->form_validation->run() == FALSE){
                            $return->message = validation_errors();
                        }else{
                            $link_session   = !empty($post['link_session']) ? $post['link_session'] : 0;                       
        
                            if(strlen($link_session) > 0){
                                $get_data=$this->Link_model->get_link_custom(array('link_session' => $link_session));
                                $set_data = $this->Link_model->update_link_custom(array('link_session'=>$link_session),array('link_flag'=>4));                
                                if($set_data==true){    
                                    /*
                                    if (file_exists($get_data['product_image'])) {
                                        unlink($get_data['product_image']);
                                    } 
                                    */
                                    $return->status=1;
                                    $return->message='Delete Success';
                                }else{
                                    $return->message='Failed';
                                } 
                            }else{
                                $return->message='Data not found';
                            }
                        }
                        break;               
                    case "load":
                        $columns = array(
                            '0' => 'link_url',
                            '1' => 'link_name',
                            '2' => 'link_visit',
                            '3' => 'link_label',
                            '4' => 'link_date_created',
                            '5' => 'link_visit'
                        );
                        $limit     = $post['length'];
                        $start     = $post['start'];
                        $order     = $columns[$post['order'][0]['column']];
                        $dir       = $post['order'][0]['dir'];
                        $search    = [];
                        if ($post['search']['value']) {
                            $s = $post['search']['value'];
                            foreach ($columns as $v) {
                                $search[$v] = $s;
                            }
                        }

                        $params = array(
                            'link_flag' => 0,
                            'link_user_session' => $session_user_session
                        );
                        
                        /* If Form Mode Transaction CRUD not Master CRUD
                        !empty($post['date_start']) ? $params['link_date >'] = date('Y-m-d H:i:s', strtotime($post['date_start'].' 23:59:59')) : $params;
                        !empty($post['date_end']) ? $params['link_date <'] = date('Y-m-d H:i:s', strtotime($post['date_end'].' 23:59:59')) : $params;                
                        */

                        //Default Params for Master CRUD Form
                        // $params['link_id']   = !empty($post['link_id']) ? $post['link_id'] : $params;
                        // $params['link_name'] = !empty($post['link_name']) ? $post['link_name'] : $params;                

                        if($post['filter_link_label'] && strlen($post['filter_link_label']) > 0) {
                            $params['link_label'] = $post['filter_link_label'];
                        }
                        
                        $get_data = $this->Link_model->get_all_link($params, $search, $limit, $start, $order, $dir);
                        $get_count = $this->Link_model->get_all_link_count($params);
                        $datas = [];
        
                        if(isset($get_data)){
                            foreach($get_data as $v){
                                $datas[] = array(
                                    'link_id' => intval($v['link_id']),
                                    'link_session' => $v['link_session'],
                                    'link_label' => !empty($v['link_label']) ? $v['link_label'] : 0,
                                    'link_url' => $v['link_url'],
                                    'link_url_full' => $v['branch_url'].'/'.$v['link_url'],                                
                                    'link_name' => $v['link_name'],
                                    'link_user_session' => $v['link_user_session'],
                                    'link_branch_session' => $v['link_branch_session'],
                                    'link_date_created' => $v['link_date_created'],
                                    'link_date_updated' => $v['link_date_updated'],
                                    'link_flag' => intval($v['link_flag']),
                                    'link_visit' => intval($v['link_visit']),
                                    'time_ago' => $this->time_ago($v['link_date_created'])
                                );                      
                            }
                            $return->total_records   = $get_count;
                            $return->status          = 1; 
                            $return->result          = $datas;
                        }else{
                            $return->total_records   = 0;
                            $return->result          = $datas;
                        }
                        $return->recordsTotal        = $return->total_records;
                        $return->recordsFiltered     = $return->total_records;
                        $return->action              = $action;
                        $return->params              = $params;
                        break;
                    case "pos_create":
                        // $data = base64_decode($post);
                        // $data = json_decode($post, TRUE);
                        // $return->message=filter_var($this->safe_url($post['link_name']), FILTER_VALIDATE_URL);
                        // echo json_encode($return);die;
                        $this->form_validation->set_rules('link_name', 'link_name', 'trim|required|min_length[5]');
                        // $this->form_validation->set_rules('captcha', 'captcha', 'trim|required|min_length[6]|max_length[20]');                    
                        $this->form_validation->set_message('required', '{field} wajib diisi');
                        if ($this->form_validation->run() == FALSE){
                            $return->message = validation_errors();
                        }else{

                                $post_captcha = !empty($post['captcha']) ? ltrim(rtrim($post['captcha'])) : 0;
                                $session_captcha = $data['session']['captcha'];
                                $link_name = !empty($post['link_name']) ? $this->safe_url($post['link_name']) : null;
                                $link_label = !empty($post['link_label']) ? $post['link_label'] : null;                            
                                $link_status = !empty($post['link_flag']) ? $post['link_flag'] : 0;

                                $link_name = filter_var($link_name, FILTER_SANITIZE_URL);

                                // if($post_captcha === $session_captcha){

                                    // if (filter_var($link_name, FILTER_VALIDATE_URL) === false) {
                                    // $return->message='URL tidak valid'.$this->safe_url($post['link_name']);
                                    // }else{
                                        $link_name = $this->safe_url($link_name);
                                        // var_dump($link_name);die;
                                        $params = array(
                                            'link_name' => $link_name,
                                            'link_label' => $link_label,
                                            'link_branch_session' => $this->branch_session,
                                            'link_user_session' => !empty($session_user_session) ? $session_user_session : null,
                                        );
                                        // var_dump($session_captcha);die;
                                        //Check Data Exist
                                        /*
                                        $params_check = array(
                                            'link_name' => $link_name,
                                            'link_branch_session' => $this->branch_session
                                        );
                                        $check_exists = $this->Link_model->check_data_exist($params_check);
                                        */
                                        $check_exists = false;
                                        if($check_exists==false){

                                            $set_data=$this->Link_model->add_link($params);
                                            if($set_data==true){

                                                $link_id = $set_data;
                                                $get_data = $this->Link_model->get_link($link_id);

                                                $get_branch = $this->Branch_model->get_branch_custom(array('branch_session'=>$this->branch_session));

                                                $return->status=1;
                                                $return->message='Berhasil menambahkan '.$post['link_name'];
                                                $return->result= array(
                                                    'link_id' => $set_data,
                                                    'link_name' => $link_name,
                                                    'link_session' => $get_data['link_session'],
                                                    'link_url' => $get_branch['branch_url'].'/'.$get_data['link_url'],
                                                    'link_label' =>$get_data['link_label']
                                                ); 
                                                // $return->captcha = array($post_captcha,$session_captcha);
                                                // $this->session->set_userdata('return',$return->result);
                                                $this->session->set_flashdata('return_url',$return->result);
                                                $this->session->set_flashdata('return_url',$return->result); 
                                                // var_dump($post['redirect']);die;                                           
                                                if($post['redirect'] == 'false'){
                                                    $return->message='Successfully';
                                                }else{
                                                    redirect(base_url());
                                                }
                                            }else{
                                                $return->message='Gagal menambahkan '.$post['link_name'];
                                            }
                                        }else{
                                            $return->message='Data sudah ada';
                                        }
                                    // }
                                // }else{
                                    // $return->message='Captcha tidak valid';
                                // }
                        }
                        // $return->action=$action;
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

            $data['title'] = 'Minio';
            $data['_view'] = 'layouts/admin/menu/minio/link';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/minio/link_js.php',$data);
            }
        }else{
            redirect(base_url());            
        }
    }
    function request($url){
        $url = $this->safe($url);
        // var_dump('post request: '.$url);die;
        if(is_string($url) and str_word_count($url) < 20){
            
            $params = array(
                'link_url' => $url
            );
            $get_link = $this->Link_model->get_link_custom($params);
            // var_dump($get_link);die;
            if(intval($get_link['link_flag']) < 4){
                if(!empty($get_link['link_session'])){
                    
                    $params_link_hit = array(
                        'hit_remote_addr' => $_SERVER['REMOTE_ADDR'],
                        'hit_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        'hit_http_referer' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null,
                        'hit_date_created' => date('YmdHis'),
                        'hit_link_session' => $get_link['link_session']
                    );
                    $hit_create = $this->Link_model->add_links_hits($params_link_hit);
                    if($hit_create){
                        // redirect($get_link['link_name'],'refresh');
                        // header('location: www.'.$get_link['link_name']);
                        // echo '<script>window.location = "'.$get_link['link_name'].'"</script>';

                        // $data['captcha'] = $this->random_number(6);
                        // $this->session->set_userdata('captcha',$data['captcha']);

                        $this->session->set_flashdata('link_name',$get_link['link_name']);                    
                        // echo $get_link['link_name'];die;

                        // $get_meta = get_meta_tags($get_link['link_name']);
                        // var_dump($get_meta);die;
                        
                        // $data['keywords']       = $get_meta['keywords'];
                        // $data['author']         = $get_meta['author'];                        
                        // $data['description']    = $get_meta['description'];

                        $data['link_name']      = $get_link['link_name'];
                        $this->load->view('layouts/website/architectui/redirect',$data);

                    }else{

                    }

                }
            }else{
                echo 'Not Found';
                redirect(base_url(), 'refresh');
            }
        }else{
            echo 0;
        }
    }    
}
?>