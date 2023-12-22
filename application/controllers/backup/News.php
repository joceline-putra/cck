<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MY_Controller{

    public $folder_upload = 'upload/news/';
    public $allowed_types = 'jpg|png|jpeg|mp4';
    public $image_width   = 250;
    public $image_height  = 250;
    public $allowed_file_size     = 5000; // 5 MB -> 5000 KB

    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('User_model');
        $this->load->model('News_model');
        $this->load->model('Kategori_model');

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->folder_upload = 'upload/news/';
        $this->allowed_types = 'jpg|png|jpeg|mp4';
        $this->image_width   = 250;
        $this->image_height  = 250;
        $this->allowed_file_size     = 5000; // 5 MB -> 5000 KB
    }
    function pages($identity){
        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        $data['allowed_file_type'] = $this->allowed_types;
        $data['allowed_file_size'] = $this->allowed_file_size;

        if($identity == 1){ //Blog / News

            // $params = array(
            //     'category_parent_id' => 0,
            //     'category_type' => 1
            // );
            // $order = 'category_name';
            // $search = null;
            // $data['parent_category'] = $this->Kategori_model->get_all_categoriess($params,$search,10,0,$order,'ASC');
            $data['identity'] = 1;            
            $data['title'] = 'Artikel';
            $data['_view'] = 'layouts/admin/menu/article/article';
            $file_js = 'layouts/admin/menu/article/article_js.php';
        }

        if($identity == 2){ //Template Promo

            // $params = array(
            //     'category_parent_id' => 0,
            //     'category_type' => 2
            // );
            // $order = 'category_name';
            // $search = null;
            // $data['parent_category'] = $this->Kategori_model->get_all_categoriess($params,$search,10,0,$order,'ASC');
            $data['identity'] = 2;
            $data['title'] = 'Template';
            $data['_view'] = 'layouts/admin/menu/message/template';
            $file_js = 'layouts/admin/menu/message/template_js.php';
        }        

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('Y-m-d');

        //Date Now
        $datenow =date("Y-m-d"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function manage(){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
                
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        $action = $this->input->post('action');
        $post_data = $this->input->post('data');
        $post = $this->input->post();
        $data = json_decode($post_data, TRUE);
        $upload_directory = $this->folder_upload;
        
        if($this->input->post('action')){
            $action = $this->input->post('action');
            if($action=='load'){
                $columns = array(
                    '0' => 'news_title',
                    '1' => 'category_name',
                    '2' => 'news_id'
                );
                $limit = $this->input->post('length');
                $start = $this->input->post('start');
                $order = $columns[$this->input->post('order')[0]['column']];
                $dir = $this->input->post('order')[0]['dir'];
                $category = $this->input->post('category');
                $flag = $this->input->post('flag');                
                $search = [];
                if ($this->input->post('search')['value']) {
                    $s = $this->input->post('search')['value'];
                    foreach ($columns as $v) {
                        $search[$v] = $s;
                    }
                }

                $params = array();
                if($session_user_id){
                    $params['news_branch_id'] = $session_branch_id;
                }
                $params['news_type'] = !empty($this->input->post('tipe')) ? intval($this->input->post('tipe')) : 0;                
                if($category > 0){
                    // $params_datatable = array(
                    //     'news.news_category_id' => $category,
                    //     // 'news.news_flag <' => 4
                    //     // 'news.news_branch_id' => $session_branch_id        
                    // );               
                    $params['news_category_id'] = $category;             
                }

                // $params = (intval($flag) < 3) ? $params_datatable['news.news_flag']=$flag : $params_datatable;            
                if($post['flag'] !== "All") {
                    $params['news_flag'] = $post['flag'];
                }else{
                    $params['news_flag <'] = 5;
                }
                /*
                if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                    $params['other_column'] = $this->input->post('other_column');
                }
                */
                
                $datas = $this->News_model->get_all_newss($params, $search, $limit, $start, $order, $dir);
                $datas_count = $this->News_model->get_all_newss_count($params, $search);                
                if(isset($datas)){ //Data exist
                    $data_source=$datas; $total=$datas_count;
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->recordsTotal = $total;
                $return->recordsFiltered = $total;
                $return->params = $params;
            }
            if($action=='create'){
                $next = true;
                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);

                $title = !empty($this->input->post('title')) ? $this->safe($this->input->post('title')) : '';
                $url = $this->generate_seo_link($title);                
                
                if(strlen($title) > 0){
                    $params = array(
                        'news_type' => !empty($this->input->post('tipe')) ? intval($this->input->post('tipe')) : 1,
                        'news_category_id' => !empty($this->input->post('categories')) ? $this->input->post('categories') : null,
                        'news_title' => $title,
                        'news_url' => $url,
                        'news_short' => !empty($this->input->post('short')) ? $this->input->post('short') : null,
                        'news_content' => !empty($this->input->post('content')) ? $this->input->post('content') : null,
                        'news_tags' => !empty($this->input->post('tags')) ? $this->input->post('tags') : null,
                        'news_keywords' => !empty($this->input->post('keywords')) ? $this->input->post('keywords') : null,
                        'news_date_created' => date("YmdHis"),
                        'news_date_updated' => date("YmdHis"),
                        'news_user_id' => $session_user_id,    
                        'news_branch_id' => $session_branch_id,              
                        'news_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 0,
                        'news_position' => !empty($this->input->post('posisi')) ? $this->input->post('posisi') : null
                    );

                    //Check Data Exist
                    $params_check = array(
                        'news_title' => $title,
                        'news_url' => $url
                    );
                    // var_dump($params);die;
                    $check_exists = $this->News_model->check_data_exist($params_check);
                    if($check_exists==false){

                            //Config Upload
                            $upload_directory = $this->folder_upload;
                            $path = FCPATH . $upload_directory;
                            $config['image_library'] = 'gd2';
                            $config['upload_path'] = $path;
                            $config['encrypt_name'] = true;
                            $config['allowed_types'] = $this->allowed_types;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            
                            $do_upload = $this->upload->do_upload('upload1');
                            $upload = $this->upload->data();
                            // $raw_photo = time() . $upload['file_ext'];
                            $raw_photo = $upload['file_name'];                                
                            $old_name = $upload['full_path'];
                            $file_size = $upload['file_size'];
                            $file_ext = $upload['file_ext'];

                            //Proteksi File Size Check
                            if(floatval($file_size) > $this->allowed_file_size){
                                $next = false;
                                $return->message = 'Ukuran file '.$file_ext.' tidak boleh melebihi '.$this->allowed_file_size.' KB';
                            }

                            if($next){

                                // Save
                                $set_data=$this->News_model->add_news($params);
                                if($set_data==true){
                                    $id = $set_data;
                                    $data = $this->News_model->get_news($id);
                                }                               

                                //Save Image if Upload is not null
                                if($do_upload){
                                    // if ($this->upload->do_upload('upload1')) {
                                        $new_name = $path . $raw_photo;
                                        if (rename($old_name, $new_name)) {
                                            //compress
                                            $compress['image_library'] = 'gd2';
                                            $compress['source_image'] = $upload_directory . $raw_photo;
                                            $compress['create_thumb'] = FALSE;
                                            $compress['maintain_ratio'] = TRUE;
                                            $compress['width'] = $this->image_width;
                                            $compress['height'] = $this->image_height;
                                            $compress['new_image'] = $upload_directory . $raw_photo;
                                            $this->load->library('image_lib', $compress);
                                            $this->image_lib->resize();

                                            if ($data && $data['news_id']) {
                                                $params_image = array(
                                                    'news_image' => $upload_directory.$raw_photo
                                                );
                                                if (!empty($data['news_image'])) {
                                                    if (file_exists(FCPATH . $data['news_image'])) {
                                                        unlink(FCPATH . $data['news_image']);
                                                    }
                                                }
                                                $stat = $this->News_model->update_news($id, $params_image);
                                            }
                                        }
                                    // }
                                }
                            
                                /* Start Activity */
                                $params = array(
                                    'activity_user_id' => $session['user_data']['user_id'],
                                    'activity_action' => 2,
                                    'activity_table' => 'news',
                                    'activity_table_id' => $set_data,                            
                                    'activity_text_1' => strtoupper($data['news_title']),
                                    'activity_text_2' => ucwords(strtolower($data['news_title'])),                        
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 1
                                );
                                $this->save_activity($params);
                                /* End Activity */

                                $return->status=1;
                                $return->message='Berhasil menambahkan';
                                $return->result= array(
                                    'id' => $set_data,
                                    'title' => $data['news_title']
                                );
                            }
                    }else{
                        $return->message='Title sudah digunakan';                    
                    }
                }else{
                    $return->message = 'Title harus diisi';
                }
            }
            if($action=='read'){
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE);     
                $data['id'] = $this->input->post('id');           
                $datas = $this->News_model->get_news($data['id']);
                if($datas==true){
                    /* Activity */
                    /*
                    $params = array(
                        'activity_user_id' => $session['user_data']['user_id'],
                        'activity_action' => 3,
                        'activity_table' => 'news',
                        'activity_table_id' => $datas['id'],
                        'activity_text_1' => strtoupper($datas['kode']),
                        'activity_text_2' => ucwords(strtolower($datas['username'])),
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 0
                    );
                    $this->save_activity($params);                    
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                    $return->result=$datas;
                }                
            }
            if($action=='update'){
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE);
                $id = $this->input->post('id');
                $title = !empty($this->input->post('title')) ? $this->safe($this->input->post('title')) : '';            
                $url = $this->generate_seo_link($title);                
                
                if(strlen($title) > 0){
                    $params = array(
                        'news_category_id' => !empty($this->input->post('categories')) ? $this->input->post('categories') : null,
                        'news_title' => $title,
                        'news_url' => $url,
                        'news_short' => !empty($this->input->post('short')) ? $this->input->post('short') : null,
                        'news_content' => !empty($this->input->post('content')) ? $this->input->post('content') : null,
                        'news_tags' => !empty($this->input->post('tags')) ? $this->input->post('tags') : null,
                        'news_keywords' => !empty($this->input->post('keywords')) ? $this->input->post('keywords') : null,
                        'news_date_updated' => date("YmdHis"),
                        'news_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 0,
                        'news_position' => !empty($this->input->post('posisi')) ? $this->input->post('posisi') : null
                    );
                    $get_old_data = $this->News_model->get_news($id);

                    //Check Data Exist
                    $params_check = array(
                        // 'news_title' => $title,
                        'news_url' => 'a'
                    );
                    $check_exists = $this->News_model->check_data_exist($params_check);
                    if($check_exists==false){
                        /*
                        if(!empty($data['password'])){
                            $params['password'] = md5($data['password']);
                        }
                        */
                        $set_update=$this->News_model->update_news($id,$params);
                        if($set_update==true){
                            $data = $this->News_model->get_news($id);

                            //Save Image if Exist
                            $path = FCPATH . $upload_directory;
                            $config['image_library'] = 'gd2';
                            $config['upload_path'] = $path;
                            $config['encrypt_name'] = true;
                            $config['allowed_types'] = $this->allowed_types;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('upload1')) {
                                $upload = $this->upload->data();
                                // $raw_photo = time() . $upload['file_ext'];
                                $raw_photo = $upload['file_name'];
                                $old_name = $upload['full_path'];
                                $new_name = $path . $raw_photo;
                                if (rename($old_name, $new_name)) {
                                    //compress
                                    $compress['image_library'] = 'gd2';
                                    $compress['source_image'] = $upload_directory . $raw_photo;
                                    $compress['create_thumb'] = FALSE;
                                    $compress['maintain_ratio'] = TRUE;
                                    $compress['width'] = $this->image_width;
                                    $compress['height'] = $this->image_height;
                                    $compress['new_image'] = $upload_directory . $raw_photo;
                                    $this->load->library('image_lib', $compress);
                                    $this->image_lib->resize();

                                    if ($data && $data['news_id']) {
                                        $params_image = array(
                                            'news_image' => $upload_directory.$raw_photo
                                        );
                                        if (!empty($data['news_image'])) {
                                            if (file_exists(FCPATH . $data['news_image'])) {
                                                unlink(FCPATH . $data['news_image']);
                                            }
                                        }
                                        $stat = $this->News_model->update_news($id, $params_image);
                                    }
                                }
                            }                              
                            /* Activity */
                            $params = array(
                                'activity_user_id' => $session['user_data']['user_id'],
                                'activity_action' => 4,
                                'activity_table' => 'news',
                                'activity_table_id' => $id,
                                'activity_text_1' => strtoupper($data['news_title']),
                                'activity_text_2' => ucwords(strtolower($data['news_title'])),
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            /* End Activity */
                            
                            $return->status=1;
                            $return->message='Berhasil memperbarui';
                        }                      
                    }else{
                        $return->message='Title sudah digunakan';                    
                    }
                }else{
                    $return->message = 'Title harus diisi';
                }
            }
            if($action=='delete'){
            }                
            if($action=='set-active'){
                $id = $this->input->post('id');
                $kode = $this->input->post('kode');        
                $nama = $this->input->post('nama');                                
                $flag = $this->input->post('flag');

                if($flag==1){
                    $msg='menaktifkan news '.$nama;
                    $act=7;
                }else{
                    $msg='menonaktifkan news '.$nama;
                    $act=8;
                }

                $set_data=$this->News_model->update_news($id,array('news_flag'=>$flag));
                if($set_data==true){
                    /* Activity */
                    /*
                    $params = array(
                        'activity_user_id' => $session['user_data']['user_id'],
                        'activity_action' => $act,
                        'activity_table' => 'news',
                        'activity_table_id' => $id,
                        'activity_text_1' => strtoupper($kode),
                        'activity_text_2' => ucwords(strtolower($nama)),
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 0
                    );
                    */
                    // $this->save_activity($params);                               
                    /* End Activity */
                    $return->status=1;
                    $return->message='Berhasil '.$msg;
                }                
            }             
            if($action=='update_flag'){
                $this->form_validation->set_rules('news_id', 'news_id', 'required');
                $this->form_validation->set_rules('news_flag', 'news_flag', 'required');
                $this->form_validation->set_message('required', '{field} wajib diisi');
                if($this->form_validation->run() == FALSE){
                    $return->message = validation_errors();
                }else{
                    $news_id = !empty($post['news_id']) ? $post['news_id'] : 0;
                    if(intval($news_id) > 0){
                        
                        $params = array(
                            'news_flag' => !empty($post['news_flag']) ? intval($post['news_flag']) : 0,
                        );
                        
                        $where = array(
                            'news_id' => !empty($post['news_id']) ? intval($post['news_id']) : 0,
                        );
                        
                        if($post['news_flag']== 0){
                            $set_msg = 'nonaktifkan';
                        }else if($post['news_flag']== 1){
                            $set_msg = 'mengaktifkan';
                        }else if($post['news_flag']== 4){
                            $set_msg = 'menghapus';
                        }else{
                            $set_msg = 'mendapatkan data';
                        }
                        if($post['news_flag'] == 4){
                            // $params['news_url'] = null;
                        }

                        $get_data = $this->News_model->get_news_custom($where);
                        if($get_data){
                            $set_update=$this->News_model->update_news_custom($where,$params);
                            if($set_update){
                                // if($post['news_flag'] == 4){
                                //     $file = FCPATH . $get_data['news_image'];
                                //     if (file_exists($file)) {
                                //         unlink($file);
                                //     }
                                // }
                                $return->status  = 1;
                                $return->message = 'Berhasil '.$set_msg.' '.$get_data['news_title'];
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
            }
        }
        $return->action=$action;
        echo json_encode($return);
    }     
}
?>