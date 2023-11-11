<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends MY_Controller{
    var $folder_upload = 'upload/product/';
    var $image_width   = 240;
    var $image_height  = 240;    
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
        $this->load->model('User_model');           
        $this->load->model('Produk_model');                   
        $this->load->model('Satuan_model');
        $this->load->model('Referensi_model');   
        $this->load->model('Kategori_model');               
        $this->load->model('Map_model'); 
        $this->load->model('Kontak_model');
        $this->load->model('Product_item_model');    
        $this->load->model('Product_recipe_model');
        $this->load->model('Product_price_model');  
        $this->load->model('Branch_model');          
    }             
    function index(){
        $data['identity'] = 0;
        $data['session'] = $this->session->userdata();
        $data['usernya'] = $this->User_model->get_all_user();
        // var_dump($data['usernya']);die;        
        
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
                
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        //Date Now
        $datenow =date("d-m-Y");         
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        $data['title'] = 'Statistik';
        $data['_view'] = 'layouts/admin/menu/product/statistic';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/product/statistic_js',$data);        
    }    
    function pages($identity){
        $session = $this->session->userdata();
        $data['session'] = $this->session->userdata();  
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        if($identity == 1){ //Makanan -- Barang & Produk
            $data['account_purchase'] = $this->get_account_map_for_transaction($session_branch_id,1,1); //Pembelian
            $data['account_sales'] = $this->get_account_map_for_transaction($session_branch_id,2,1); //Penjualan
            $data['account_inventory'] = $this->get_account_map_for_transaction($session_branch_id,3,1); //Inventory            
            // $data['satuan'] = $this->Satuan_model->get_all_satuan();  

            $data['identity'] = 1;
            $data['title'] = 'Makanan';
            $data['_view'] = 'layouts/admin/menu/product/product';
            $file_js = 'layouts/admin/menu/product/product_js.php';            
        }else if($identity == 2){ //Kamar -- Jasa // Move to product_type = 2
            $data['account_purchase'] = $this->get_account_map_for_transaction($session_branch_id,1,1); //Pembelian
            $data['account_sales'] = $this->get_account_map_for_transaction($session_branch_id,2,1); //Penjualan

            // $data['satuan'] = $this->Satuan_model->get_all_satuan();  
            $data['identity'] = 2;
            $data['title'] = 'Kamar';
            $data['_view'] = 'layouts/admin/menu/product/room';
            $file_js = 'layouts/admin/menu/product/room_js.php';
        }        
        else if($identity == 3){ //Inventaris
            $data['satuan'] = $this->Satuan_model->get_all_satuan();              
            $data['referensi'] = $this->Referensi_model->get_all_referensi(array('ref_type'=>6));

            $data['identity'] = 3;            
            $data['title'] = 'Aset';
            $data['_view'] = 'layouts/admin/menu/product/asset';
            $file_js = 'layouts/admin/menu/product/asset_js.php';
        }
        /*
            else if($identity == 2){ //Jasa // Move to product_type = 2
                $data['account_purchase'] = $this->get_account_map_for_transaction($session_branch_id,1,1); //Pembelian
                $data['account_sales'] = $this->get_account_map_for_transaction($session_branch_id,2,1); //Penjualan

                // $data['satuan'] = $this->Satuan_model->get_all_satuan();  
                $data['identity'] = 2;
                $data['title'] = 'Jasa';
                $data['_view'] = 'layouts/admin/menu/product/service';
                $file_js = 'layouts/admin/menu/product/service_js.php';
            }
            else if($identity == 4){ //Tindakan & DEPRECATED
                $data['referensi'] = $this->Referensi_model->get_all_referensi(array('ref_type'=>2));
                
                $data['identity'] = 4;    
                $data['title'] = 'Tindakan';
                $data['_view'] = 'layouts/admin/menu/product/tindakan';
                $file_js = 'layouts/admin/menu/product/tindakan_js.php';
            } 
            else if($identity == 5){ //Laboratorium
                // $data['referensi'] = $this->Referensi_model->get_all_referensi(array('ref_type'=>5));
                $data['account_purchase'] = $this->get_account_map_for_transaction($session_branch_id,1,1); //Pembelian
                $data['account_sales'] = $this->get_account_map_for_transaction($session_branch_id,2,1); //Penjualan

                $data['identity'] = 5;
                $data['title'] = 'Laboratorium';
                $data['_view'] = 'layouts/admin/menu/product/laboratory';
                $file_js = 'layouts/admin/menu/product/laboratory_js.php';
            }
            else if($identity == 6){ //Lain
                $data['referensi'] = $this->Referensi_model->get_all_referensi(array('ref_type'=>6));

                $data['identity'] = 6;
                $data['title'] = 'Lain';
                $data['_view'] = 'product/lain';
                $file_js = 'product/lain_js.php';
            } 
            else if($identity == 7){ //Kategori Produk

                $params = array(
                    'category_parent_id' => 0
                );
                $search = null;
                $order = 'category_name';

                $data['parent_category'] = $this->Kategori_model->get_all_categoriess($params,$search,100,0,$order,'ASC');
                $data['identity'] = 7;
                $data['title'] = 'Kategori Produk';
                $data['_view'] = 'reference/kategori_produk';
                $file_js = 'reference/kategori_produk_js.php';
            }
        */
       
        $data['image_width'] = intval($this->image_width);
        $data['image_height'] = intval($this->image_height);
                
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
        $session_branch_id  = intval($session['user_data']['branch']['id']);
        $session_user_id    = intval($session['user_data']['user_id']);        
        $as = '';
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        $upload_directory = $this->folder_upload;     
        $upload_path_directory = $upload_directory;
        
        if($this->input->post('action')){
            $action = $this->input->post('action');
            $post_data = $this->input->post('data');
            $data = json_decode($post_data, TRUE);
            $identity = !empty($this->input->post('tipe')) ? intval($this->input->post('tipe')) : null;

            //Produk ID or TIPE
            if($identity == 1){ //Barang & Obat
                $columns = array(
                    '0' => 'product_code',
                    '1' => 'product_name',
                    '2' => 'product_stock',
                    '3' => 'product_price_sell'                    
                );                                      
            }else if($identity == 2){ //Jasa
                $columns = array(
                    '0' => 'product_code',
                    '1' => 'product_name',
                    '2' => 'ref_name',
                    '3' => 'product_price_sell'
                ); 
            }else if($identity == 3){ //Inventaris 
                $columns = array(
                    '0' => 'product_code',
                    '1' => 'product_name',
                    '2' => 'ref_name',
                    '3' => 'product_price_sell'
                ); 
            }else if($identity == 5){ //Laboratorium
                $columns = array(
                    '0' => 'product_code',
                    '1' => 'product_name',
                    '2' => 'ref_name',
                    '3' => 'product_price_sell'
                );                       
            }

            /*if($identity == 6){ //Lain
                $columns = array(
                    '0' => 'product_code',
                    '1' => 'product_name',
                    '2' => 'group',
                    '3' => 'product_price_sell'
                );                       
            }
            if($identity == 7){ //kategori produk
                $set_tipe = 7;
                $params = array(
                    'product_type' => !empty($this->input->post('tipe')) ? $this->input->post('tipe') : null,
                    'product_code' => !empty($this->input->post('kode')) ? $this->input->post('kode') : null,
                    'product_name' => !empty($this->input->post('nama')) ? $this->input->post('nama') : null,
                    'product_ref_id' => !empty($this->input->post('referensi')) ? $this->input->post('referensi') : null,   
                    'product_price_sell' => !empty($this->input->post('harga_jual')) ? $this->input->post('harga_jual') : null, 
                    'product_date_created' => date("YmdHis"),
                    'product_date_updated' => date("YmdHis"),
                    'product_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 1,
                    'product_user_id' => $session_user_id,
                    'product_branch_id' => $session_branch_id
                );   
                $params_update = array(
                    'product_category_code' => $data['kode'],
                    'product_category_name' => $data['nama'],
                    'product_category_parent_id' => $data['parent'],
                    'product_category_url' => $data['url'],   
                    'product_category_icon' => $data['icon'],
                    'product_category_date_updated' => date("YmdHis"),
                    'product_category_flag' => $data['status']
                );                                 
                $columns = array(
                    '0' => 'product_category_code',
                    '1' => 'product_category_name'
                );
                $table = 'product_categories';
            }
            */
            if($action=='create'){
                //Set Product
                $product_type = !empty($this->input->post('tipe')) ? intval($this->input->post('tipe')) : null;
                $product_code = !empty($this->input->post('kode')) ? $this->input->post('kode') : null;
                $product_name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;
                $ref = !empty($this->input->post('ref')) ? $this->input->post('ref') : null;
                // $ref = $ref == 1 ? "jual" : "sewa";
                // $tipe = !empty() ? strtolower($this->product_type($this->input->post('tipe_properti'),null)) : ;
                $tipe = $identity;
                $lokasi = $this->Map_model->get_city(array('city_id'=> $this->input->post('kota')));
                // $product_url = $ref.'/'.$tipe.'/'.str_replace(' ','-',strtolower($lokasi['city_name'])).'/'.str_replace(' ', '-', strtolower($this->input->post('nama'))); //jual-murah-meriah
                // $product_url = 'Perlu diperbaiki Produk Like 237';
                $product_url = $this->generate_seo_link($product_name);
                $params = array(
                    'product_type' => $product_type,
                    'product_code' => $product_code,
                    'product_name' => $product_name,
                    'product_ref_id' => !empty($this->input->post('referensi')) ? $this->input->post('referensi') : null,                           
                    'product_note' => !empty($this->input->post('keterangan')) ? $this->input->post('keterangan') : null,                    
                    'product_price_buy' => !empty($this->input->post('harga_beli')) ? str_replace( ',', '',$this->input->post('harga_beli')) : 0,
                    'product_price_sell' => !empty($this->input->post('harga_jual')) ? str_replace( ',', '',$this->input->post('harga_jual')) : 0,
                    'product_price_promo' => !empty($this->input->post('harga_promo')) ? str_replace( ',', '',$this->input->post('harga_promo')) : 0,                    
                    'product_date_created' => date("YmdHis"),
                    'product_date_updated' => date("YmdHis"),
                    'product_unit' => !empty($this->input->post('satuan')) ? $this->input->post('satuan') : null,
                    'product_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 1,
                    'product_category_id' => !empty($this->input->post('categories')) ? $this->input->post('categories') : null,
                    'product_manufacture' => !empty($this->input->post('manufacture')) ? $this->input->post('manufacture') : null,
                    'product_user_id' => $session_user_id,
                    'product_branch_id' => $session_branch_id,
                    'product_with_stock' => !empty($this->input->post('with_stock')) ? $this->input->post('with_stock') : 0,
                    'product_min_stock_limit' => !empty($this->input->post('stok_minimal')) ? str_replace( ',', '',$this->input->post('stok_minimal')) : 0,
                    'product_max_stock_limit' => !empty($this->input->post('stok_maksimal')) ? str_replace( ',', '',$this->input->post('stok_maksimal')) : 0,
                    'product_square_size' => !empty($this->input->post('luas_tanah')) ? $this->input->post('luas_tanah') : 0,
                    'product_building_size' => !empty($this->input->post('luas_bangunan')) ? $this->input->post('luas_bangunan') : 0,                
                    'product_bedroom' => !empty($this->input->post('kamar_tidur')) ? $this->input->post('kamar_tidur') : 0,
                    'product_bathroom' => !empty($this->input->post('kamar_mandi')) ? $this->input->post('kamar_mandi') : 0,
                    'product_garage' => !empty($this->input->post('garasi')) ? $this->input->post('garasi') : 0,
                    'product_contact_id' => !empty($this->input->post('contact')) ? $this->input->post('contact') : null,
                    // 'product_ref_id' => !empty($this->input->post('ref')) ? $this->input->post('ref') : null,
                    'product_city_id' => !empty($this->input->post('kota')) ? $this->input->post('kota') : null,
                    // 'product_type' => !empty($this->input->post('tipe_properti')) ? $this->input->post('tipe_properti') : null,
                    'product_visitor' => 0,
                    'product_url' => $product_url,
                    'product_buy_account_id' => !empty($this->input->post('akun_beli')) ? $this->input->post('akun_beli') : null, 
                    'product_sell_account_id' => !empty($this->input->post('akun_jual')) ? $this->input->post('akun_jual') : null,
                    'product_inventory_account_id' => !empty($this->input->post('akun_inventory')) ? $this->input->post('akun_inventory') : null,                        
                    'product_fee_1' => !empty($this->input->post('fee_1')) ? str_replace( ',', '',$this->input->post('fee_1')) : 0,
                    'product_fee_2' => !empty($this->input->post('fee_2')) ? str_replace( ',', '',$this->input->post('fee_2')) : 0,
                    'product_reminder' => !empty($this->input->post('product_reminder')) ? $this->input->post('product_reminder') : null,
                    'product_reminder_date' => !empty($this->input->post('product_reminder_date')) ? $this->input->post('product_reminder_date') : null,                                    
                ); 

                // var_dump($params);die;
                //Check Data Exist
                // if($identity != 7 && $identity != 1){    
                    // $params_check = array(
                        // 'product_type' => $data['tipe'],
                        // 'product_code' => $data['kode'],
                        // 'product_branch_id' => $session_branch_id
                    // );
                // }
                // if($identity == 1){
                    // $params_check = array(
                        // 'product_type' => $this->input->post('tipe'),
                        // 'product_name' => $nama,
                        // 'product_branch_id' => $session_branch_id
                    // );
                // }

                //Set Params
                $params_check = [];
                $product_type > 0  ? $params_check['product_type'] = $product_type : $params_check;                    
                !empty($product_code) ? $params_check['product_code'] = $product_code : $params_check;
                !empty($product_name) ? $params_check['product_name'] = $product_name : $params_check;
                !empty($session_branch_id) ? $params_check['product_branch_id'] = $session_branch_id : $params_check;
                
                $check_exists = $this->Produk_model->check_data_exist($params_check);
                // var_dump($params,$product_type,$params_check,$check_exists);die;
                /*
                if($identity == 7){    
                    $params_check = array(
                        'product_category_code' => $data['kode'],
                        'product_category_name' => $data['nama']
                    );
                    $check_exists = $this->Produk_model->check_data_category_exist($params_check);
                }
                */
                if($check_exists==false){
                    if($identity != 7){    
                        $set_data=$this->Produk_model->add_produk($params);
                        $table = 'products';
                    }
                    /*
                    if($identity == 7){   
                        $set_data=$this->Produk_model->add_produk_kategori($params);
                        $table = 'product_categories';
                    }
                    */
                    $product_id = $set_data;
                    $get_data = $this->Produk_model->get_produk($product_id);

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
                                $stat = $this->Produk_model->update_produk($product_id, $params_image);
                            }
                        }else{
                            $return->message = 'Fungsi Gambar gagal';
                        }
                    }
                    //End of Croppie   

                    if($set_data){
                        if($identity == 1){
                            $id = $set_data;
                            $data = $this->Produk_model->get_produk($id);

                            //Save Image if Exist
                            $upload_directory = $this->folder_upload;                            
                            $path = FCPATH . $upload_directory;
                            $config['image_library'] = 'gd2';
                            $config['upload_path'] = $path;
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('upload1')) {
                                $upload = $this->upload->data();
                                $raw_photo = time() . $upload['file_ext'];
                                $old_name = $upload['full_path'];
                                $new_name = $path . $raw_photo;
                                if (rename($old_name, $new_name)) {
                                    $compress['image_library'] = 'gd2';
                                    $compress['source_image'] = $upload_directory . $raw_photo;
                                    $compress['create_thumb'] = FALSE;
                                    $compress['maintain_ratio'] = TRUE;
                                    $compress['width'] = $this->image_width;
                                    $compress['height'] = $this->image_height;
                                    $compress['new_image'] = $upload_directory . $raw_photo;
                                    $this->load->library('image_lib', $compress);
                                    $this->image_lib->resize();

                                    if ($data && $data['product_id']) {
                                        $params_image = array(
                                            'product_image' => $upload_directory.$raw_photo
                                        );
                                        if (!empty($data['product_image'])) {
                                            if (file_exists(FCPATH . $data['product_image'])) {
                                                unlink(FCPATH . $data['product_image']);
                                            }
                                        }
                                        $stat = $this->Produk_model->update_produk($id, $params_image);

                                        $params_item = array(
                                            'product_item_product_id' => $id,
                                            'product_item_type' => 1,
                                            'product_item_image' => $upload_directory.$raw_photo,
                                            'product_item_date_created' => date("YmdHis"),
                                            'product_item_date_updated' => date("YmdHis"),
                                            'product_item_flag' => 1                       
                                        );
                                        $insert_item = $this->Product_item_model->add_product_item($params_item);
                                    }
                                }
                            }    
                            /*                    
                            if ($this->upload->do_upload('upload2')) {
                                $upload = $this->upload->data();
                                $raw_photo = time() . $upload['file_ext'];
                                $old_name = $upload['full_path'];
                                $new_name = $path . $raw_photo;
                                if (rename($old_name, $new_name)) {
                                    $compress['image_library'] = 'gd2';
                                    $compress['source_image'] = $upload_directory . $raw_photo;
                                    $compress['create_thumb'] = FALSE;
                                    $compress['maintain_ratio'] = TRUE;
                                    $compress['width'] = $this->image_width;
                                    $compress['height'] = $this->image_height;
                                    $compress['new_image'] = $upload_directory . $raw_photo;
                                    $this->load->library('image_lib', $compress);
                                    $this->image_lib->resize();

                                    if ($data && $data['product_id']) {
                                        $params_image = array(
                                            'product_image' => $upload_directory.$raw_photo
                                        );
                                        if (!empty($data['product_image'])) {
                                            if (file_exists(FCPATH . $data['product_image'])) {
                                                unlink(FCPATH . $data['product_image']);
                                            }
                                        }

                                        $params_item = array(
                                            'product_item_product_id' => $id,
                                            'product_item_type' => 1,
                                            'product_item_image' => $upload_directory.$raw_photo,
                                            'product_item_date_created' => date("YmdHis"),
                                            'product_item_date_updated' => date("YmdHis"),
                                            'product_item_flag' => 1                       
                                        );
                                        $insert_item = $this->Product_item_model->add_product_item($params_item);
                                    }
                                }
                            }                                  
                            if ($this->upload->do_upload('upload3')) {
                                $upload = $this->upload->data();
                                $raw_photo = time() . $upload['file_ext'];
                                $old_name = $upload['full_path'];
                                $new_name = $path . $raw_photo;
                                if (rename($old_name, $new_name)) {
                                    $compress['image_library'] = 'gd2';
                                    $compress['source_image'] = $upload_directory . $raw_photo;
                                    $compress['create_thumb'] = FALSE;
                                    $compress['maintain_ratio'] = TRUE;
                                    $compress['width'] = $this->image_width;
                                    $compress['height'] = $this->image_height;
                                    $compress['new_image'] = $upload_directory . $raw_photo;
                                    $this->load->library('image_lib', $compress);
                                    $this->image_lib->resize();

                                    if ($data && $data['product_id']) {
                                        $params_image = array(
                                            'product_image' => $upload_directory.$raw_photo
                                        );
                                        if (!empty($data['product_image'])) {
                                            if (file_exists(FCPATH . $data['product_image'])) {
                                                unlink(FCPATH . $data['product_image']);
                                            }
                                        }
                                        
                                        $params_item = array(
                                            'product_item_product_id' => $id,
                                            'product_item_type' => 1,
                                            'product_item_image' => $upload_directory.$raw_photo,
                                            'product_item_date_created' => date("YmdHis"),
                                            'product_item_date_updated' => date("YmdHis"),
                                            'product_item_flag' => 1                       
                                        );
                                        $insert_item = $this->Product_item_model->add_product_item($params_item);
                                    }
                                }
                            }            
                            if ($this->upload->do_upload('upload4')) {
                                $upload = $this->upload->data();
                                $raw_photo = time() . $upload['file_ext'];
                                $old_name = $upload['full_path'];
                                $new_name = $path . $raw_photo;
                                if (rename($old_name, $new_name)) {
                                    $compress['image_library'] = 'gd2';
                                    $compress['source_image'] = $upload_directory . $raw_photo;
                                    $compress['create_thumb'] = FALSE;
                                    $compress['maintain_ratio'] = TRUE;
                                    $compress['width'] = $this->image_width;
                                    $compress['height'] = $this->image_height;
                                    $compress['new_image'] = $upload_directory . $raw_photo;
                                    $this->load->library('image_lib', $compress);
                                    $this->image_lib->resize();

                                    if ($data && $data['product_id']) {
                                        $params_image = array(
                                            'product_image' => $upload_directory.$raw_photo
                                        );
                                        if (!empty($data['product_image'])) {
                                            if (file_exists(FCPATH . $data['product_image'])) {
                                                unlink(FCPATH . $data['product_image']);
                                            }
                                        }

                                        $params_item = array(
                                            'product_item_product_id' => $id,
                                            'product_item_type' => 1,
                                            'product_item_image' => $upload_directory.$raw_photo,
                                            'product_item_date_created' => date("YmdHis"),
                                            'product_item_date_updated' => date("YmdHis"),
                                            'product_item_flag' => 1                       
                                        );
                                        $insert_item = $this->Product_item_model->add_product_item($params_item);
                                    }
                                }
                            }                        
                            */
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,                        
                                'activity_action' => 2,
                                'activity_table' => 'products',
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => strtoupper($product_name),
                                'activity_text_2' => ucwords(strtolower($product_name)),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);
                            /* End Activity */

                            $return->status=1;
                            $return->message='Berhasil menambahkan '.$product_name;
                            $return->result= array(
                                'id' => $set_data,
                                'kode' => $product_code,
                                'name' => $product_name
                            );
                        }else{
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 2,
                                'activity_table' => $table,
                                'activity_table_id' => $set_data,                            
                                'activity_text_1' => strtoupper($product_name),
                                'activity_text_2' => ucwords(strtolower($product_name)),                            
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);                
                            $return->status=1;
                            $return->message='Berhasil menambahkan '.$product_name;
                            $return->result= array(
                                'id' => $set_data,
                                'kode' => $product_code,
                                'name' => $product_name
                            );
                        }                         
                    }
                }else{
                    $return->message='Data '.$product_name.' sudah ada ';                    
                }
            }else if($action=='read'){
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE); 
                $data['product_id'] = $this->input->post('id');       
                $data['location_id'] = !empty($this->input->post('location')) ? $this->input->post('location') : 0;    
                if($identity != 7){
                    $datas = $this->Produk_model->get_produk($data['product_id']);
                    if($datas){
                        $stock_result = array();
                        $get_product_has_other_price = array();
                        if($identity == 1){
                            if($data['location_id'] > 0){
                                $product_stock=$this->product_stock(1,$data['product_id'],$data['location_id']);
                            }else{
                                $product_stock=false;
                            }

                            if($product_stock){
                                $stock_result = array(
                                    'product_id' => $product_stock[0]['product_id'],
                                    'product_name' => $product_stock[0]['product_name'],   
                                    'product_qty' => $product_stock[0]['product_qty'],   
                                    'product_unit' => $product_stock[0]['product_unit'],
                                    'location_id' => $product_stock[0]['location_id'],
                                    'location_name' => $product_stock[0]['location_name']                                                                          
                                );
                            }else{
                                $stock_result = array(
                                    'product_qty' => 0
                                );
                            }

                            $price_params = array('product_price_product_id'=>$data['product_id']);
                            $get_product_has_other_price = $this->Product_price_model->get_all_product_price_count($price_params);
                        }
                    }
                }
                /*
                if($identity == 7){
                    $datas = $this->Produk_model->get_produk_kategori($data['id']);
                }
                */
                if($datas==true){
                    //Aktivitas
                    if($identity != 7){
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 3,
                            'activity_table' => 'products',
                            'activity_table_id' => $datas['product_id'],
                            'activity_text_1' => strtoupper($datas['product_code']),
                            'activity_text_2' => ucwords(strtolower($datas['product_name'])),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                    }
                    if($identity == 7){
                        $params = array(
                            'activity_user_id' => $session['user_data']['user_id'],
                            'activity_action' => 3,
                            'activity_table' => 'categories',
                            'activity_table_id' => $datas['category_id'],
                            'activity_text_1' => strtoupper($datas['category_code']),
                            'activity_text_2' => ucwords(strtolower($datas['category_name'])),
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                    }
                    // $this->save_activity($params);              
                    $return->status   = 1;
                    $return->message  = 'Success';
                    $return->result   = $datas;
                    $return->stock    = $stock_result;
                    $return->product_has_other_price    = $get_product_has_other_price;
                    // $return->location = $this->Map_model->get_city(array('city_id'=>$datas['product_city_id']));
                    // $return->contact = $this->Kontak_model->get_kontak($datas['product_contact_id']);   
                    // $return->image = $this->Product_item_model->get_all_product_item(array('product_item_product_id'=>$datas['product_id']),null,null,null,null,null);                                     
                    // $return->property_type = $this->product_type($datas['product_type'],null);                    
                }                
            }else if($action=='update'){
                // $post_data = $this->input->post('data');
                // $data = json_decode($post_data, TRUE);
                // $id = $data['id'];
                $id = !empty($this->input->post('id')) ? intval($this->input->post('id')) : false;
                if($id==true){
                    //Set Product URL
                    $product_type = !empty($this->input->post('tipe')) ? intval($this->input->post('tipe')) : null;
                    $product_name = !empty($this->input->post('nama')) ? $this->input->post('nama') : null;
                    $ref = !empty($this->input->post('ref')) ? $this->input->post('ref') : null;
                    // $ref = $ref == 1 ? "jual" : "sewa";
                    $tipe = !empty($this->input->post('tipe_properti')) ? strtolower($this->product_type($this->input->post('tipe_properti'),null)) : null;
                    $lokasi = $this->Map_model->get_city(array('city_id'=> $this->input->post('kota')));
                    // var_dump($this->input->post('status'));
                    $product_url = $this->generate_seo_link($product_name);
                    // $product_url = $ref.'/'.$tipe.'/'.str_replace(' ','-',strtolower($lokasi['city_name'])).'/'.str_replace(' ', '-', strtolower($this->input->post('nama'))); //jual-murah-meriah
                    $params = array(
                        'product_type' => $product_type,
                        'product_ref_id' => !empty($this->input->post('referensi')) ? $this->input->post('referensi') : null,
                        'product_code' => !empty($this->input->post('kode')) ? $this->input->post('kode') : null,
                        'product_name' => $product_name,
                        'product_note' => !empty($this->input->post('keterangan')) ? $this->input->post('keterangan') : null,
                        'product_price_buy' => !empty($this->input->post('harga_beli')) ? str_replace( ',', '',$this->input->post('harga_beli')) : 0,
                        'product_price_sell' => !empty($this->input->post('harga_jual')) ? str_replace( ',', '',$this->input->post('harga_jual')) : 0,
                        'product_price_promo' => !empty($this->input->post('harga_promo')) ? str_replace( ',', '',$this->input->post('harga_promo')) : 0,
                        'product_min_stock_limit' => !empty($this->input->post('stok_minimal')) ? str_replace( ',', '',$this->input->post('stok_minimal')) : 0,
                        'product_max_stock_limit' => !empty($this->input->post('stok_maksimal')) ? str_replace( ',', '',$this->input->post('stok_maksimal')) : 0,  
                        'product_date_created' => date("YmdHis"),
                        'product_date_updated' => date("YmdHis"),
                        'product_unit' => !empty($this->input->post('satuan')) ? $this->input->post('satuan') : null,
                        'product_flag' => !empty($this->input->post('status')) ? $this->input->post('status') : 1,
                        'product_category_id' => !empty($this->input->post('categories')) ? $this->input->post('categories') : null,
                        'product_manufacture' => !empty($this->input->post('manufacture')) ? $this->input->post('manufacture') : null,
                        'product_user_id' => $session_user_id,
                        'product_branch_id' => $session_branch_id,
                        'product_with_stock' => !empty($this->input->post('with_stock')) ? $this->input->post('with_stock') : 0,
                        'product_square_size' => !empty($this->input->post('luas_tanah')) ? $this->input->post('luas_tanah') : 0,
                        'product_building_size' => !empty($this->input->post('luas_bangunan')) ? $this->input->post('luas_bangunan') : 0,
                        'product_bedroom' => !empty($this->input->post('kamar_tidur')) ? $this->input->post('kamar_tidur') : 0,
                        'product_bathroom' => !empty($this->input->post('kamar_mandi')) ? $this->input->post('kamar_mandi') : 0,
                        'product_garage' => !empty($this->input->post('garasi')) ? $this->input->post('garasi') : 0,
                        'product_contact_id' => !empty($this->input->post('contact')) ? $this->input->post('contact') : null,
                        // 'product_ref_id' => !empty($this->input->post('ref')) ? $this->input->post('ref') : null,
                        'product_city_id' => !empty($this->input->post('kota')) ? $this->input->post('kota') : null,
                        // 'product_type' => !empty($this->input->post('tipe_properti')) ? $this->input->post('tipe_properti') : null,
                        'product_visitor' => 0,
                        'product_url' => $product_url,
                        'product_buy_account_id' => !empty($this->input->post('akun_beli')) ? $this->input->post('akun_beli') : null, 
                        'product_sell_account_id' => !empty($this->input->post('akun_jual')) ? $this->input->post('akun_jual') : null,
                        'product_inventory_account_id' => !empty($this->input->post('akun_inventory')) ? $this->input->post('akun_inventory') : null,
                        'product_fee_1' => !empty($this->input->post('fee_1')) ? str_replace( ',', '',$this->input->post('fee_1')) : 0,
                        'product_fee_2' => !empty($this->input->post('fee_2')) ? str_replace( ',', '',$this->input->post('fee_2')) : 0,
                        'product_reminder' => !empty($this->input->post('product_reminder')) ? $this->input->post('product_reminder') : null,
                        'product_reminder_date' => !empty($this->input->post('product_reminder_date')) ? $this->input->post('product_reminder_date') : null,
                    );

                    $set_update=$this->Produk_model->update_produk($id,$params);
                    $table = 'products';
                    if($set_update==true){

                        $product_id = $id;
                        $get_data = $this->Produk_model->get_produk($product_id);

                        //Croppie Upload Image
                        $post_upload = !empty($this->input->post('upload1')) ? $this->input->post('upload1') : "";
                        if(strlen($post_upload) > 10){
                            $upload_process = $this->file_upload_image($this->folder_upload,$post_upload);
                            if($upload_process->status == 1){
                                if ($get_data && $get_data['product_id']) {
                                    $params_image = array(
                                        'product_image' => $upload_process->result['file_location']
                                    );
                                    if (!empty($get_data['product_image'])) {
                                        if (file_exists(FCPATH . $get_data['product_image'])) {
                                            unlink(FCPATH . $get_data['product_image']);
                                        }
                                    }
                                    $stat = $this->Produk_model->update_produk($product_id, $params_image);
                                }
                            }else{
                                $return->message = 'Fungsi Gambar gagal';
                            }
                        }
                        //End of Croppie         
                                                
                        if($identity == 1){
                            //Save Image 
                                // $id = $set_update;
                                // $data = $this->Produk_model->get_produk($id);
                                // $path = FCPATH . $this->folder_upload;
                                // $config['image_library'] = 'gd2';
                                // $config['upload_path'] = $path;
                                // $config['allowed_types'] = 'gif|jpg|png|jpeg';
                                // $this->load->library('upload', $config);
                                // $this->upload->initialize($config);
                                // if ($this->upload->do_upload('upload1')) {
                                //     $upload = $this->upload->data();
                                //     $raw_photo = time() . $upload['file_ext'];
                                //     $old_name = $upload['full_path'];
                                //     $new_name = $path . $raw_photo;
                                //     if (rename($old_name, $new_name)) {
                                //         $compress['image_library'] = 'gd2';
                                //         $compress['source_image'] = $this->folder_upload . $raw_photo;
                                //         $compress['create_thumb'] = FALSE;
                                //         $compress['maintain_ratio'] = TRUE;
                                //         $compress['width'] = $this->image_width;
                                //         $compress['height'] = $this->image_height;                                    
                                //         $compress['new_image'] = $this->folder_upload . $raw_photo;
                                //         $this->load->library('image_lib', $compress);
                                //         $this->image_lib->resize();

                                //         if ($data && $data['product_id']) {
                                //             $params_image = array(
                                //                 'product_image' => $this->folder_upload.$raw_photo
                                //             );
                                //             if (!empty($data['product_image'])) {
                                //                 if (file_exists(FCPATH . $data['product_image'])) {
                                //                     unlink(FCPATH . $data['product_image']);
                                //                 }
                                //             }
                                //             $stat = $this->Produk_model->update_produk($id, $params_image);
                                //         }
                                //     }
                                // }           
                            //End of Save Image                                
                            
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'products',
                                'activity_table_id' => $set_update,                            
                                'activity_text_1' => strtoupper($product_name),
                                'activity_text_2' => ucwords(strtolower($product_name)),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);
                            /* End Activity */

                            $return->status=1;
                            $return->message='Berhasil memperbarui '.$product_name;
                        }else{
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => $table,
                                'activity_table_id' => $set_update,                            
                                'activity_text_1' => strtoupper($product_name),
                                'activity_text_2' => ucwords(strtolower($product_name)),                        
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1
                            );
                            $this->save_activity($params);                
                            $return->status=1;
                            $return->message='Berhasil memperbarui '.$product_name;
                        }  
                    }       
                }else{
                    $return->status  = 1;
                    $return->message = 'Error update '.$product_name;                    
                }         
            }else if($action=='delete'){
                $return->message = 'Function not ready';
            }else if($action=='set-active'){
                $id = $this->input->post('id');
                $kode = $this->input->post('kode');        
                $nama = $this->input->post('nama');                                
                $flag = $this->input->post('flag');

                if($flag==1){
                    $msg='aktifkan '.$nama;
                    $act=7;
                }else{
                    $msg='nonaktifkan  '.$nama;
                    $act=8;
                }

                if($identity != 7){    
                    $set_data=$this->Produk_model->update_produk($id,array('product_flag'=>$flag));
                    $table = 'products';
                }
                if($identity == 7){
                    $set_data=$this->Produk_model->update_produk_kategori($id,array('product_category_flag'=>$flag));
                    $table= 'product_categories';
                }
                
                if($set_data==true){    
                    //Aktivitas
                    $params = array(
                        'activity_user_id' => $session_user_id,
                        'activity_branch_id' => $session_branch_id,
                        'activity_action' => $act,
                        'activity_table' => $table,
                        'activity_table_id' => $id,
                        'activity_text_1' => strtoupper($kode),
                        'activity_text_2' => ucwords(strtolower($nama)),
                        'activity_date_created' => date('YmdHis'),
                        'activity_flag' => 0
                    );
                    $this->save_activity($params);                                          
                    $return->status=1;
                    $return->message='Berhasil '.$msg;
                }                
            }else if($action=='load'){
                $column = [];
                $search = [];

                $limit = $this->input->post('length');
                $start = $this->input->post('start');
                $order = $columns[$this->input->post('order')[0]['column']];
                $dir = $this->input->post('order')[0]['dir'];
                if ($this->input->post('search')['value']) {
                    $s = $this->input->post('search')['value'];
                    foreach ($columns as $v) {
                        $search[$v] = $s;
                    }
                }

                $params = array(
                    'product_branch_id' => $session_branch_id,
                );
                if($identity > 0){ //Form Admin Panel (Barang, Jasa, Inventaris)
                    // var_dump($identity);
                    // $params = array(
                    //     'product_branch_id' => $session_branch_id,
                    //     // 'product_type' => $identity
                    // );    
                    $filter_ref         = !empty($this->input->post('filter_ref')) ? intval($this->input->post('filter_ref')) : 0;
                    $filter_category    = !empty($this->input->post('filter_categories')) ? intval($this->input->post('filter_categories')) : 0;                    
                    $filter_type        = !empty($this->input->post('filter_type')) ? intval($this->input->post('filter_type')) : 0;
                    $filter_contact     = !empty($this->input->post('filter_contact')) ? intval($this->input->post('filter_contact')) : 0;     
                    $filter_city        = !empty($this->input->post('filter_city')) ? intval($this->input->post('filter_city')) : 0;                
                    $filter_flag        = !empty($this->input->post('filter_flag')) ? intval($this->input->post('filter_flag')) : 0;
                    
                    $filter_ref > 0 ? $params['product_ref_id'] = $filter_ref : $params;
                    $filter_category > 0 ? $params['product_category_id'] = $filter_category : $params;                    
                    $filter_type > 0 ? $params['product_type'] = $filter_type : $params;
                    $filter_contact > 0 ? $params['product_contact_id'] = $filter_contact : $params;
                    $filter_city > 0 ? $params['product_city_id'] = $filter_city : $params;
                    // $filter_flag > 0 ? $params['product_flag'] = $filter_flag : $params;
                    if($filter_flag == 100){

                    }else{
                        $params['product_flag'] = $filter_flag;                           
                    }

                    if($identity==1){
                        $params['product_type <'] = 3;
                    }else if($identity==3){ //Inventaris
                        $params['product_type'] = 3;
                    }
                }

                $datas_count = $this->Produk_model->get_all_produks_count($params,$search);
                if($datas_count > 0){ //Data exist
                    $datas = $this->Produk_model->get_all_produks($params, $search, $limit, $start, $order, $dir);
                    $data_source=$datas; $total= $datas_count;
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->params = $params;
                $return->limit = array(
                    'start' => intval($start),
                    'length' => intval($limit)
                );                
                // $return->search = $search;
                // $return->paramss = var_dump($params, $search, $limit, $start, $order, $dir);
                $return->recordsTotal = $total;
                $return->recordsFiltered = $total;
            }else if($action=='create-from-modal'){

                $post_data = $this->input->post('data');
                // $data = base64_decode($post_data);
                $data = json_decode($post_data, TRUE);

                $get_account_buy = $this->get_account_map_for_transaction($session_branch_id,1,1); //Pembelian / Beban Pokok Pembelian
                $get_account_sell = $this->get_account_map_for_transaction($session_branch_id,2,1); //Penjualan / Pendapatan
                $get_account_inventory = $this->get_account_map_for_transaction($session_branch_id,3,1);
                
                $params = array(
                    'product_type' => $data['tipe'],
                    'product_code' => $data['kode'],
                    'product_category_id' => $data['category'],
                    'product_name' => $data['nama'],
                    'product_unit' => $data['satuan'],                    
                    'product_unit' => $data['satuan'],    
                    'product_date_created' => date("YmdHis"),
                    'product_date_updated' => date("YmdHis"),
                    'product_flag' => $data['status'],
                    'product_user_id' => $session_user_id,
                    'product_branch_id' => $session_branch_id,
                    'product_buy_account_id' => $get_account_buy['account_id'], 
                    'product_sell_account_id' => $get_account_sell['account_id'],
                    'product_inventory_account_id' => $get_account_inventory['account_id'],
                    'product_visitor' => 0,
                    'product_with_stock' => 1
                );
                //Check Data Exist
                $params_check = array(
                    'product_type' => $data['tipe'],
                    'product_code' => $data['kode'],
                    'product_branch_id' => $session_branch_id
                );
                $check_exists = $this->Produk_model->check_data_exist($params_check);
                if($check_exists==false){
                    $set_data=$this->Produk_model->add_produk($params);
                    if($set_data==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 1,
                            'activity_table' => 'products',
                            'activity_table_id' => $set_data,                            
                            'activity_text_1' => strtoupper($data['kode']),
                            'activity_text_2' => ucwords(strtolower($data['nama'])),                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1
                        );
                        $this->save_activity($params);                
                        $return->status=1;
                        $return->message='Berhasil menambahkan '.$data['nama'];
                        $return->result= array(
                            'id' => $set_data,
                            'kode' => $data['kode']
                        );                         
                    }
                }else{
                    $return->message='Data sudah ada';                    
                }
            }else if($action=='stock'){
                $firstdate = new DateTime('first day of this month');
                $date_start = $firstdate->format('d-m-Y');
                $date_end = date('d-m-Y');
                $search = null;
                $product_id = !empty($this->input->post('id')) ? $this->input->post('id') : 0;
                $category_id = !empty($this->input->post('category_id')) ? $this->input->post('category_id') : 0;                
                $total_data = 0;
                $return->product = [];
                if(intval($product_id) > 0){
                    $get_product = $this->Produk_model->get_produk($product_id);
                    $datas = array();
                    $get_product_stock=$this->report_product_stock(4,'','',$session_branch_id,0,$product_id,'','',$search);
                    foreach($get_product_stock as $v):
                        if($v['total_data'] > 0){
                            $datas[] = array(
                                'location_name' => $v['location_name'],
                                'product_unit' => $v['product_unit'],
                                'qty_balance' => $v['qty_balance'],
                                'stock_card_url' => base_url('report/report_stock_card').'/'.$date_start.'/'.$date_end.'/'.$v['location_id'].'?product='.$v['product_id'].'&format=html&order=product_name&ver=1&dir=asc'
                            );
                            $total_data = intval($v['total_data']);
                            $return->status = 1;
                        }else{
                            $datas = array();
                            break;
                        }
                    endforeach;
                    $return->result = $datas;
                    $return->product = array(
                        'product_id' => $get_product['product_id'],
                        'product_code' => $get_product['product_code'],
                        'product_name' => $get_product['product_name'],
                        'product_flag' => $get_product['product_flag'],
                        'product_stock' => $get_product['product_stock'],
                        'product_flag' => $get_product['product_flag'],
                        'product_min_stock_limit' => $get_product['product_min_stock_limit'],
                        'product_unit' => $get_product['product_unit'],
                        
                    );
                }else{
                    $return->message = 'Produk harus dipilih';
                }
                $return->total_data = $total_data;
            }else if($action=='create-item-recipe'){
                $product_id = !empty($this->input->post('product_id')) ? intval($this->input->post('product_id')) : 0;
                $recipe_product_id = !empty($this->input->post('recipe_product_id')) ? intval($this->input->post('recipe_product_id')) : null;
                $qty = !empty($this->input->post('qty')) ? str_replace(',','',$this->input->post('qty')) : 0.00;
                $unit = !empty($this->input->post('unit')) ? $this->input->post('unit') : null;
                $note = !empty($this->input->post('note')) ? $this->input->post('note') : null;
                
                if(intval($product_id) > 0){
                    $params = array(
                        'recipe_product_id' => $product_id,
                        'recipe_product_id_child' => $recipe_product_id,
                        'recipe_note' => $note,
                        'recipe_unit' => $unit,
                        'recipe_qty' => $qty,
                        'recipe_date_created' => date('Ymdhis'),
                        'recipe_flag' => 1,
                        'recipe_user_id' => $session_user_id
                    );
                    $set_data=$this->Product_recipe_model->add_recipe($params);                
                    $return->status = 1;
                    $return->message = 'Berhasil menambahkan';
                    $return->result = array(
                        'recipe_id' => $set_data,
                        'product_id' => $product_id,
                        'recipe_product_id' => $recipe_product_id
                    );
                }else{
                    $return->status  = 0;
                    $return->message = 'Gagal menambahkan';
                }
            }else if($action=='delete-item-recipe'){
                
                $product_id = !empty($this->input->post('product_id')) ? intval($this->input->post('product_id')) : $data['product_id'];
                $recipe_id = !empty($this->input->post('recipe_id')) ? intval($this->input->post('recipe_id')) : $data['recipe_id'];
                // var_dump($recipe_id);die;
                if(intval($recipe_id) > 0){
                    $delete_data=$this->Product_recipe_model->delete_recipe($recipe_id);
                    $return->message='Berhasil menghapus';
                    $return->status=1;
                }else{
                    $next = false;
                    $return->message = 'Tidak dapat dihapus';
                }                
                $return->result = array(
                    'recipe_id' => $recipe_id,
                    'product_id' => $product_id
                );
            }else if($action=='load-recipe'){
                $product_id = !empty($this->input->post('product_id')) ? intval($this->input->post('product_id')) : null;                
                $params = array(
                    'recipe_product_id' => $product_id
                );
                $datas = $this->Product_recipe_model->get_all_recipe($params,null,null,null,'product_name','asc');
                $datas_count = count($datas);
                if(isset($datas)){ //Data exist
                    $data_source=$datas; $total= $datas_count;
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->params = $params;
                $return->recordsTotal = $total;
                $return->recordsFiltered = $total;                
            }else if($action=='create-item-price'){
                $product_price_product_id = !empty($this->input->post('product_price_product_id')) ? intval($this->input->post('product_price_product_id')) : 0;
                if(intval($product_price_product_id) > 0){
                    $params = array(
                        'product_price_product_id' => $product_price_product_id,
                        'product_price_name' => !empty($this->input->post('product_price_name')) ? $this->input->post('product_price_name') : null,
                        'product_price_price' => !empty($this->input->post('product_price_price')) ? str_replace(',','',$this->input->post('product_price_price')) : '0.00',
                        'product_price_date_created' => date('Ymdhis'),
                        'product_price_flag' => 1,
                        'product_price_user_id' => $session_user_id
                    );
                    $set_data=$this->Product_price_model->add_product_price($params);                
                    $return->status = 1;
                    $return->message = 'Berhasil menambahkan '.$this->input->post('product_price_name');
                    $return->result = array(
                        'product_price_id' => $set_data,
                        'product_price_product_id' => $product_price_product_id,
                    );
                }else{
                    $return->status  = 0;
                    $return->message = 'Gagal menambahkan';
                }
            }else if($action=='delete-item-price'){
                $product_price_id = !empty($this->input->post('product_price_id')) ? intval($this->input->post('product_price_id')) : $data['product_price_id'];
                $product_price_product_id = !empty($this->input->post('product_price_product_id')) ? intval($this->input->post('product_price_product_id')) : $data['product_price_product_id'];
                
                if(intval($product_price_id) > 0){
                    // var_dump($product_price_id);die;
                    $delete_data=$this->Product_price_model->delete_product_price($product_price_id);
                    $return->message='Berhasil menghapus';
                    $return->status=1;
                }else{
                    $next = false;
                    $return->message = 'Tidak dapat dihapus';
                }                
                $return->result = array(
                    'product_price_product_id' => $product_price_product_id
                );
            }else if($action=='load-price'){
                $product_id = !empty($this->input->post('product_id')) ? intval($this->input->post('product_id')) : null;                
                $params = array(
                    'product_price_product_id' => $product_id
                );
                $datas = $this->Product_price_model->get_all_product_price($params,null,null,null,'product_price_price','asc');
                $datas_count = count($datas);
                if(isset($datas)){ //Data exist
                    $data_source=$datas; $total= $datas_count;
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->params = $params;
                $return->recordsTotal = $total;
                $return->recordsFiltered = $total;                
            }else if($action=='product-history'){
                $product_id = !empty($this->input->post('product_id')) ? $this->input->post('product_id') : 0;
                $contact_id = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : 0;

                if(intval($product_id)>0){ 
                    $where_and = '';
                    if(intval($contact_id) > 0){
                        $where_and = 'AND trans_contact_id = '.$contact_id;
                    }

                    $prepare = "
                        SELECT trans_type, type_name, trans_date, trans_date_due, trans_number, contact_name, 
                        trans_item_in_qty, trans_item_in_price, trans_item_out_qty, trans_item_out_price, trans_item_sell_price,
                        trans_item_unit
                        FROM trans_items
                        LEFT JOIN trans ON trans_item_trans_id=trans_id
                        LEFT JOIN contacts ON trans_contact_id=contact_id
                        LEFT JOIN products ON trans_item_product_id=product_id
                        LEFT JOIN `types` ON trans_type=type_type AND type_for=2
                        WHERE trans_item_product_id=$product_id 
                        AND trans_item_branch_id=$session_branch_id 
                        AND trans_type IN(1,2)
                        $where_and
                        ORDER BY trans_date DESC
                        LIMIT 100
                    ";
                    $query = $this->db->query($prepare);
                    $result_array = $query->result_array();
                    $datas = array();
                    foreach($result_array as $v):
                        $datas[] = array(
                            'trans_type' => intval($v['trans_type']),
                            'type_name' => $v['type_name'],
                            'trans_date' => $v['trans_date'],
                            'trans_date_format' => date('d-M-y', strtotime($v['trans_date'])),
                            'trans_date_due' => $v['trans_date_due'],
                            'trans_number' => $v['trans_number'], 
                            'contact_name' => $v['contact_name'], 
                            'trans_item_in_qty' => ($v['trans_item_in_qty'] > 0) ? number_format($v['trans_item_in_qty'],2,'.',',') : '-', 
                            'trans_item_in_price' => ($v['trans_item_in_price'] > 0) ? number_format($v['trans_item_in_price'],2,'.',',') : '-', 
                            'trans_item_out_qty' => ($v['trans_item_out_qty'] > 0) ? number_format($v['trans_item_out_qty'],2,',','.') : '-', 
                            'trans_item_out_price' => ($v['trans_item_out_price'] > 0) ? number_format($v['trans_item_out_price'],2,',','.') : '-',
                            'trans_item_sell_price' => ($v['trans_item_sell_price'] > 0) ? number_format($v['trans_item_sell_price'],2,',','.') : '-',
                            'trans_item_unit' => $v['trans_item_unit']
                        );
                    endforeach;
                    $return->total_data = count($query->result_array());
                    $return->status=1;
                    $return->message='Loaded';
                    $return->result= $datas;
                }else{
                    $return->total_data = 0;
                    $return->message = 'Produk harus dipilih';
                }
                $return->query = $prepare;
            }else if($action=="product-min-stock"){
                $method = !empty($this->input->post('method')) ? $this->input->post('method') : 'count';
                if($method=='count'){
                    $query = $this->db->query("
                        SELECT COUNT(*) AS total FROM products 
                        WHERE product_min_stock_limit > 0 
                        AND product_stock < product_min_stock_limit
                    ");
                    $row = $query->row_array();
                    if($row['total'] > 0){
                        $return->status=1;
                        $return->message='Loaded';
                        $return->result= $row['total'];
                    }else{
                        $return->message='No Data';
                    }
                }else if($method=='load-data'){
                    $query = $this->db->query("
                        SELECT product_id, product_code, product_name, product_unit, category_name, product_flag,
                        product_min_stock_limit, product_stock
                        FROM products 
                        LEFT JOIN categories ON product_category_id=category_id
                        WHERE product_min_stock_limit > 0 
                        AND product_stock < product_min_stock_limit 
                        ORDER BY product_stock DESC
                    ");
                    $result= $query->result_array();
                    if($result){
                        $return->status=1;
                        $return->message='Loaded';
                        $return->result= $result;
                        $return->total_records = count($result);
                    }else{
                        $return->total_records = 0;                        
                        $return->message='No Data';
                    }
                }
            }else if($action=="import_from_excel"){
                $this->form_validation->set_rules('product_type', 'Tipe', 'required');
                $this->form_validation->set_message('required', '{field} wajib diisi');
                if ($this->form_validation->run() == FALSE) {
                    $return->message = validation_errors();
                } else {
                    $return->data_exists = array();
                    $return->total_exists = 0;

                    $total_exists = 0;
                    $data_exists = array();

                    $product_type = $this->input->post('product_type');
                    
                    //Excel is comming
                    if (isset($_FILES["excel_file"]["name"])) {
                        $file_tmp   = $_FILES['excel_file']['tmp_name'];
                        $file_name  = $_FILES['excel_file']['name'];
                        $file_size  = $_FILES['excel_file']['size'];
                        $file_type  = $_FILES['excel_file']['type'];

                        $object = PHPExcel_IOFactory::load($file_tmp);
                        $sheets = 0;

                        $get_account_buy = $this->get_account_map_for_transaction($session_branch_id,1,1); //Pembelian / Beban Pokok Pembelian
                        $get_account_sell = $this->get_account_map_for_transaction($session_branch_id,2,1); //Penjualan / Pendapatan
                        $get_account_inventory = $this->get_account_map_for_transaction($session_branch_id,3,1);
                        
                        //Foreach
                        foreach ($object->getWorksheetIterator() as $worksheet) {

                            //Sheet 1
                            if ($sheets == 0) {
                                $highest_row    = $worksheet->getHighestRow();
                                $highest_column = $worksheet->getHighestColumn();
                                $numbers = 0;
                                for ($row = 3; $row <= $highest_row; $row++) {
                                    
                                    if ($product_type == 1) { //Barang
                                        $product_code = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                                        $product_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                        $product_unit = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                        $product_price_buy = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                                        $product_price_sell = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                                        $params_check = [
                                            'product_type' => $product_type,
                                            'product_branch_id' => $session_branch_id,
                                            'product_code' => $product_code
                                        ];
                                    }else if ($product_type == 2) { //Jasa
                                        $product_code = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                                        $product_name = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                                        $product_unit = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                        $product_price_buy = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                                        $product_price_sell = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                                        $params_check = [
                                            'product_type' => $product_type,
                                            'product_branch_id' => $session_branch_id,
                                            'product_code' => $product_code
                                        ];                                           
                                    }

                                    //Check data exist
                                    $check_exists = $this->Produk_model->check_data_exist($params_check, null);
                                    // $check_exists = false;
                                    if ($check_exists) {
                                        $data_exists[] = array('product_code' => $product_code);
                                        $total_exists++;
                                    } else {

                                        if($product_type==1){                                        
                                            $params[] = array(
                                                'product_branch_id' => $session_branch_id,
                                                'product_user_id' => $session_user_id,
                                                'product_type' => $product_type,
                                                'product_code' => !empty($product_code) ? $product_code : null,
                                                'product_name' => !empty($product_name) ? $product_name : null,
                                                'product_unit' => !empty($product_unit) ? $product_unit : 'pcs',
                                                'product_price_buy' => !empty($product_price_buy) ? $product_price_buy : null,
                                                'product_price_sell' => !empty($product_price_sell) ? $product_price_sell : null,
                                                'product_buy_account_id' => $get_account_buy['account_id'],
                                                'product_sell_account_id' => $get_account_sell['account_id'],
                                                'product_date_created' => date("YmdHis"),
                                                'product_flag' => 1,
                                                'product_barcode' => $this->random_code(8),
                                                'product_inventory_account_id' => $get_account_inventory['account_id'],
                                                'product_with_stock' => 1
                                            );
                                        }
                                    }
                                    $numbers++;
                                    // }
                                } //End looping
                            }
                            $sheets++;
                        }
                        // var_dump($params);die;
                        if ($total_exists == 0) {
                            // Bulk Insert Process
                            if ($this->db->insert_batch('products', $params)) {
                                $return->status = 1;
                                $return->message = 'Berhasil import data';
                            } else {
                                $return->message = 'Gagal import';
                            }
                        } else {
                            $return->message = 'Gagal import, terdapat ' . $total_exists . ' data yg sudah ada, mohon diperiksa kembali';
                            $return->data_exists = $data_exists;
                            $return->total_exists = $total_exists;
                        }
                    } else {
                        $return->message = 'Excel not found';
                    }
                }                
            }
        }
        $return->action=$action;
        echo json_encode($return);
    }
    function prints(){
        $session = $this->session->userdata();
        $session_branch_id  = intval($session['user_data']['branch']['id']);
        $session_user_id    = intval($session['user_data']['user_id']);      
        $session_user_group = intval($session['user_data']['user_group_id']);

        //Smart Dashboard Rule
        $rule = array('1','3'); //1=Root /SuperAdmin, 3=Director
        if(in_array($session_user_group,$rule)){
            $data['show_price'] = 1;
        }else{
            $data['show_price'] = 0;
        }

        $cat        = $this->input->get('cat');
        $type       = $this->input->get('type');  
        $flag       = $this->input->get('flag');
        $order      = $this->input->get('order'); 
        $dir        = $this->input->get('dir');                        

        $columns = array(
            '1' => 'product_name',
            '2' => 'product_code',
            '3' => 'product_category_id',
            '4' => 'product_price_buy',
            '5' => 'product_price_sell',
            '6' => 'product_stock',
        );
        $columns_dir = array(
            '0' => 'asc',
            '1' => 'desc'
        );
        $column_type = array(
            '0' => 'Barang dan Jasa',
            '1' => 'Barang',
            '2' => 'Jasa'
        );

        $data['branch'] = $this->Branch_model->get_branch($session_branch_id);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch($session_branch_id);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $limit  = 0;
        $start  = 0;
        $order  = $columns[$order];
        $dir    = $columns_dir[$dir];
        $search = null;

        $params_datatable = array();  
        $datas            = array();

        if(intval($cat) > 0){
            $get_cat = $this->Kategori_model->get_categories(intval($cat));
            // $search['category_product_id'] = intval($cat);
            $params_datatable['product_category_id'] = intval($cat);            
            $data['category'] = $get_cat['category_name'];
        }
        if(intval($type) > 0){
            $params_datatable['product_type'] = intval($type);             
            $data['type'] = $column_type[$type];
        }        

        if((is_numeric($flag)) && (intval($flag) > 0)){
            $params_datatable['product_flag'] = intval($flag);
            if($flag==1){
                $data['flag'] = 'Aktif';
            }else if($flag==0){
                $data['flag'] = 'NonAktif';
            }
        }       

        $get_count = $this->Produk_model->get_all_produks_count($params_datatable,$search);
        $limit = $get_count;
        if($get_count > 0){
            $get_data = $this->Produk_model->get_all_produks($params_datatable, $search, $limit, $start, $order, $dir);
            foreach($get_data as $v){

                $type_name = '-';
                if(intval($v['product_type']) ==0){
                    $type_name = '-';
                }else if(intval($v['product_type']) == 1){
                    $type_name = 'Barang';
                }else if(intval($v['product_type']) == 2){
                    $type_name = 'Jasa';
                }

                $flag_name = '-';
                if(intval($v['product_flag']) ==0){
                    $flag_name = 'Nonaktif';
                }else if(intval($v['product_flag']) == 1){
                    $flag_name = 'Aktif';
                }else if(intval($v['product_type']) == 4){
                    $flag_name = 'Terhapus';
                }
                                
                $datas[] = array(
                    'product_id' => $v['product_id'],
                    'product_code' => $v['product_code'],
                    'product_name' => $v['product_name'],
                    'product_unit' => $v['product_unit'],
                    'product_stock' => $v['product_stock'],                                        
                    'product_note' => $v['product_note'],
                    'product_url' => site_url().$v['product_url'],
                    'product_image' => site_url().$v['product_image'],
                    'product_type' => $v['product_type'],
                    'product_type_name' => $type_name,
                    'product_price_buy' => $v['product_price_buy'],
                    'product_price_sell' => $v['product_price_sell'],
                    'product_flag' => $v['product_flag'],
                    'product_flag_name' => $flag_name,
                    'category_name' => $v['category_name'],
                    'price' => $this->Product_price_model->get_all_product_price(array('product_price_product_id'=>$v['product_id']),null,null,null,'product_price_price','asc'),
                    // 'image' => $this->Product_item_model->get_all_product_item(array('product_item_product_id'=>$v['product_id']),null,null,null,null,null)
                 );
            }

            $params = array(
                'activity_user_id' => $session['user_data']['user_id'],
                'activity_action' => 6,
                'activity_table' => 'products',
                'activity_table_id' => null,
                'activity_text_1' => '1',
                'activity_text_2' => 'Data Produk',
                'activity_date_created' => date('YmdHis'),
                'activity_flag' => 0
            );
            $this->save_activity($params);                
        }else{
            $datas = array();
        }
        $data['content'] = $datas;
        $data['title'] = 'Print Data Produk';
        $data['url'] = site_url();
        $data['description'] = '';
        $data['author'] = 'Author';
        $data['image'] = $data['branch_logo'];        
        $data['price_item'] = 1;
        $this->load->view('layouts/admin/menu/prints/data/print_product',$data);               
    }
}
