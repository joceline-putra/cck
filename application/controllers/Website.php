<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends MY_Controller{

    public $asset; public $layout; public $controller;
    
    public $product_routing; public $blog_routing; public $contact_routing;
    public $dir; public $nav;
    
    public $header_file; public $content_file; public $footer_file;
    public $css_file; public $js_file;
    public $site_dir;
    
    public $blogs_dir; public $blogs_file; public $blog_file;    
    public $products_dir; public $products_file; public $product_file;
    
    public $contacts_dir; public $contacts_file; public $contact_file;
    
    public $about_file; public $cart_file; public $checkout_file; public $contact_us_file;

    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');                  
        $this->load->helper('form');        
        $this->load->helper(array('cookie', 'url'));         

        //Configuration
        $this->layout           = 'porto'; /* news, property, store */
        $this->asset            = 'porto'; /* homeid, magazine, teamo, webarch */

        //Routing
        $this->product_routing  = 'produk';
        $this->blog_routing     = 'article'; //blog
        $this->contact_routing  = 'agent';

        //Website Map
        $this->header_file      = 'header';
        $this->content_file     = 'content';
        $this->footer_file      = 'footer';
        $this->css_file         = 'link_css';
        $this->js_file          = 'link_js';

        //Sub Folder
        $this->site_dir         = 'home';

        $this->blogs_dir        = 'articles';
        $this->blogs_file       = 'articles';
        $this->blog_file        = 'article';

        $this->products_dir     = 'products';
        $this->products_file    = 'products';
        $this->product_file     = 'product_v2';

        $this->contacts_dir     = 'contacts'; //Not Used
        $this->contacts_file    = 'agents';   //Not Used
        $this->contact_file     = 'agent';    //Not Used

        $this->about_file       = 'about';
        $this->contact_us_file  = 'contact_us';
        $this->cart_file        = 'cart';
        $this->checkout_file    = 'checkout';        
        $this->login_file       = 'login';
        $this->wishlist_file    = 'wishlist';        

        //Old Concept
        $this->dir = array(
            'header' => 'layouts/website/'.$this->layout.'/'.$this->header_file,
            'content' => 'layouts/website/'.$this->layout.'/'.$this->content_file,
            'layout' => 'layouts/website/'.$this->layout.'/',
            'footer' => 'layouts/website/'.$this->layout.'/'.$this->footer_file,
            'asset' => site_url().'assets/'.$this->asset,
            'admin' => 'layouts/admin',
        );

        //New Concept
		$this->nav = array(
			'web' => array(
				'asset' => array(
					'folder' => $this->asset,
					'dir' => site_url().'assets/'
				),
                'header' => 'layouts/website/'.$this->layout.'/'.$this->header_file,
                'footer' => 'layouts/website/'.$this->layout.'/'.$this->footer_file, 
                'layout' => 'layouts/website/'.$this->layout.'/',               
				'index' => 'layouts/website/'.$this->layout.'/index',
				'js' => 'layouts/website/'.$this->layout.'/'.$this->js_file,
				'content' => 'layouts/website/'.$this->layout.'/'.$this->content_file
			),
			'admin' => array(
				'asset' => array(
					'folder' => 'webarch',
					'dir' => site_url().'assets/'
				),				
				'index' => 'layouts/admin/index',
				'js' => 'layouts/admin/index_js'
			)
		);        
        // var_dump($this->dir);die;
        $this->load->model('Aktivitas_model');
        $this->load->model('News_model');
        $this->load->model('Produk_model');
        $this->load->model('Product_item_model');
        $this->load->model('Kontak_model');
        $this->load->model('User_model');
        $this->load->model('Kategori_model');
        $this->load->model('Map_model');
        $this->load->model('Transaksi_model');

        //Set Cookie if Not Exists
        if(empty($this->input->cookie('trans_session'))){
            $cookie = array(
                'name' => 'trans_session',
                'value' => $this->random_code(20),
                'expire' => strtotime('+30 day'),
                'path' => '/'                    
            );
            $this->input->set_cookie($cookie);    
        }
    }
    function sitelink(){ //Sitelink Directory for FrontEnd
        $a = array(
            'home' => site_url(),
                'products' => site_url('products'),
                'product' => site_url('product'),
                'articles' => site_url('articles'),
                'article' => site_url('article'),
            'about' => site_url('about'),//NotUsed
            'contact_us' => site_url('contact-us'),//NotUsed
            'career' => site_url('career'),//NotUsed
            'term_and_condition' => site_url('term-and-condition'),//NotUsed            
            'faqs' => site_url('faqs'),//NotUsed                        
            'tracking' => site_url('tracking'), //NotUsed
            'shipping' => site_url('shipping'),//NotUsed
            'history' => site_url('history'),//NotUsed             
            'signin' => site_url('masuk'),
            'forgot' => site_url('lupa_password'),            
            'register' => site_url('daftar'),
            'account' => site_url('account'),  
            'wishlist' => site_url('wishlist'),            
            'checkout' => site_url('checkout'),          
            'cart' => site_url('cart')
        );
        return $a;
    }
    function index(){
        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        // $this->load->view($this->nav['web']['index'],$data);
        
        $data['controller'] = $this->blog_routing;

        $action = $this->input->post('action');
        $post = $this->input->post();        
        
        // var_dump($action);die;
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';   
             
        switch ($action){
            case "search_product": //Product = Properti, Barang, Jasa, Other 
                $filter_search      = !empty($this->input->get('search')) ? $this->input->get('search') : null;

                // GET Method
                $datas = json_decode($this->input->post('data'), TRUE);
                // var_dump($datas);
                $filter_sort        = !empty($datas['filter_sort']) ? intval($datas['filter_sort']) : 0;
                $filter_city        = !empty($datas['filter_city']) ? intval($datas['filter_city']) : 0;
                $filter_type        = !empty($datas['filter_type']) ? intval($datas['filter_type']) : 0;
                $filter_ref         = !empty($datas['filter_ref']) ? intval($datas['filter_ref']) : 0;
                $filter_contact     = !empty($datas['filter_contact']) ? intval($datas['filter_contact']) : 0;
                $filter_bedroom     = !empty($datas['filter_bedroom']) ? intval($datas['filter_bedroom']) : 0;
                $filter_bathroom    = !empty($datas['filter_bathroom']) ? intval($datas['filter_bathroom']) : 0;
                $filter_price       = !empty($datas['filter_price']) ? $datas['filter_price'] : 0;
                $filter_square      = !empty($datas['filter_square']) ? $datas['filter_square'] : 0;

                //POST Method
                // $filter_sort        = !empty($this->input->get('filter_sort')) ? intval($this->input->get('filter_sort')) : 0;
                // $filter_city        = !empty($this->input->get('filter_city')) ? intval($this->input->get('filter_city')) : 0;
                // $filter_type        = !empty($this->input->get('filter_type')) ? intval($this->input->get('filter_type')) : 0;
                // $filter_ref         = !empty($this->input->get('filter_ref')) ? intval($this->input->get('filter_ref')) : 0;
                // $filter_bedroom     = !empty($this->input->get('filter_bedroom')) ? intval($this->input->get('filter_bedroom')) : 0;
                // $filter_bathroom    = !empty($this->input->get('filter_bathroom')) ? intval($this->input->get('filter_bathroom')) : 0;
                // $filter_price       = !empty($this->input->get('filter_price')) ? $this->input->get('filter_bathroom') : null;
                // $filter_square      = !empty($this->input->get('filter_square')) ? $this->input->get('filter_square') : null;
                
                $filter_price = str_replace('$', '', $filter_price);
                $filter_price = str_replace(' to ', '-', $filter_price);
                $filter_price = str_replace(',', '', $filter_price);
                $filter_price = explode('-', $filter_price);
                // var_dump($filter_price);die;

                $filter_square = str_replace('m2', '', $filter_square);
                $filter_square = str_replace(' ', '', $filter_square);
                $filter_square = str_replace('sd', '-', $filter_square);
                $filter_square = explode('-', $filter_square);
                // var_dump($filter_square);die;

                $params = array(
                    // 'product_type' => 1,
                    'product_flag' => 1
                );
                $search = null;
                $limit  = null;
                $start  = null;
                $order  = null;
                $dir    = null;

                !empty($filter_search) ? $search['product_name']=$filter_search : $search=null;
                // if($filter_sort > 0){
                //     $params['product_sort'] = $filter_sort;
                // }
                $filter_city > 0 ? $params['product_city_id'] = $filter_city : $params;
                $filter_type > 0 ? $params['product_type'] = $filter_type : $params;
                $filter_ref > 0 ? $params['product_ref_id'] = $filter_ref : $params;
                $filter_contact > 0 ? $params['product_contact_id'] = $filter_contact : $params;
                $filter_bedroom > 0 ? $params['product_bedroom'] = $filter_bedroom : $params;
                $filter_bathroom > 0 ? $params['product_bathroom'] = $filter_bathroom : $params;
                !empty($filter_price[0]) ? $params['product_price_sell >='] = $filter_price[0] : $params;
                !empty($filter_price[1]) ? $params['product_price_sell <='] = $filter_price[1] : $params;
                !empty($filter_square[0]) ? $params['product_square_size >='] = $filter_square[0] : $params;
                !empty($filter_square[1]) ? $params['product_square_size <='] = $filter_square[1] : $params;

                if($filter_sort == 1){
                    $order = 'product_visitor'; $dir='desc';
                }
                else if($filter_sort == 2){
                    $order = 'product_price_sell'; $dir='asc';
                }
                else{
                    $order = 'product_price_sell'; $dir='desc';
                }   

                // var_dump($order,$dir);die;
                $get_data=$this->Produk_model->get_all_produks($params,$search,$limit,$start,$order,$dir);
                $data_source = array();
                foreach($get_data AS $v){
                    $data_source[]=array(
                        'product_id' => $v['product_id'],
                        'product_title' => $v['product_name'],
                        'category' => array(
                            'name' => $v['category_name'],
                            'url' => site_url($this->product_routing).'/'.$v['category_url']
                        ),
                        'product_url' => site_url($this->product_routing).'/'.$v['category_url'].'/'.$v['product_url'],
                        'product_image' => site_url().$v['product_image'],
                        'product_price' => 'Rp. '.number_format($v['product_price_sell'],0),
                        'product_price_promo' => 'Rp. '.number_format($v['product_price_promo'],0),
                        'product_flag' => $v['product_flag'] == 1 ? 'Tersedia' : 'Tidak Tersedia',
                        'product_ref' => $v['product_ref_id'] == 1 ? 'Jual' : 'Sewa',
                        'product_type' => $this->product_type($v['product_type'],null)
                    );
                }
                // echo json_encode($data_source);die;
                // var_dump(count($data_source));die;
                // var_dump($get_data);die;
                if(!empty($get_data)){ //Data exist
                    $data_source=$data_source;$total=count($get_data);$return->status=1;
                    $return->message='Loaded';$return->total_records=$total;$return->result=$data_source;
                }else{ 
                    $data_source=0;$total=0;$return->status=0; 
                    $return->message='No data';$return->total_records=$total;$return->result=0;
                }
                $return->params = $params;
                $return->search = $search; 
                $return->order = $order;
                $return->dir = $dir;      
                echo json_encode($return);
                // $this->load->view($this->dir['layout'].'products',$data_source);
                break;    
            case "search_blog": //News = Blog, Article, Banner, Slider 
                //POST Method
                $filter_search      = !empty($this->input->get('search')) ? $this->input->get('search') : null;      
                $filter_type        = !empty($this->input->get('filter_type')) ? intval($this->input->get('filter_type')) : 0;
                
                $search = array();
                $params = array(
                    'news_flag' => 1
                );
                !empty($filter_search) ? $search['news_title']=$filter_search : $search=null;
                // $filter_type > 0 ? $params['news_category_type'] = $filter_type : $params;

                $get_data=$this->News_model->get_all_newss($params,$search,null,null,null,null);
                foreach($get_data AS $n){
                    $data_source[] = array(
                            'id' => $n['news_id'],
                            'url' => site_url($this->blog_routing).'/'.$n['category_url'].'/'.$n['news_url'],
                            'category' => $n['category_name'],
                            'title' => $this->capitalize($n['news_title']),
                            'image' => $n['news_image'],
                            'flag' => $n['news_flag'] == 1 ? 'Publish' : 'Unpublish',
                            'date_created' => date("d-F-Y", strtotime($n['news_date_created']))
                    );
                }
                if(isset($get_data)){ //Data exist
                    $data_source=$data_source; $total=count($data_source);
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$data_source;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->params = $params;
                $return->search = $search;
                echo json_encode($return);
                break;               
            case "search_contact": //Contact = Supplier, Customer, Employee, Agent 
                //POST Method
                $filter_search      = !empty($this->input->get('search')) ? $this->input->get('search') : null;      
                $filter_type        = !empty($this->input->get('filter_type')) ? intval($this->input->get('filter_type')) : 0;
                
                $search = array();
                $params = array(
                    'contact_flag' => 1
                );
                !empty($filter_search) ? $search['contact_name']=$filter_search : $search=null;
                // $filter_type > 0 ? $params['news_category_type'] = $filter_type : $params;

                $get_data=$this->Kontak_model->get_all_kontaks($params,$search,null,null,null,null);
                foreach($get_data AS $n){
                    $data_source[] = array(
                        'id' => $n['contact_id'],
                        'type' => 'Agent BESTPRO',
                        'name' => $n['contact_name'],
                        'phone' => $n['contact_phone_1'],
                        'email' => $n['contact_email_1'],
                        'url' => site_url($this->contact_routing).'/'.$n['contact_url'],
                        'image' => site_url().$n['contact_image'] 
                    );                                  
                }
                if(isset($get_data)){ //Data exist
                    $data_source=$data_source; $total=count($data_source);
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$data_source;        
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->params = $params;
                $return->search = $search;                       
                echo json_encode($return);
                break;              
            case "subscribe": die;
                $return->status = 1;
                $return->action = $action;
                echo json_encode($return);
                break;
            case "search": die;
                $return->status = 1;
                $return->action = $action;
                echo json_encode($return);
                break;
            case "signin": die;
                $return->status = 1;
                $return->action = $action;
                echo json_encode($return);
                break;
            case "signup": die;
                $return->status = 1;
                $return->action = $action;
                echo json_encode($return);
                break;  
            case "cart":
                $return->status = 0;
                $trans_session = !empty(get_cookie('trans_session')) ? get_cookie('trans_session') : 0;
                if(strlen($trans_session) > 2){
                    $params = array(
                        'trans_item_trans_session' => $trans_session,
                        'trans_item_flag' => 0
                    );
                    $get_data = $this->Transaksi_model->get_all_transaksi_items($params,$search=null,100,0,'trans_item_id','asc');
                    if($get_data){
                        $datas = [];
                        foreach($get_data as $v):
                            $datas[] = array(
                                'item_id' => $v['trans_item_id'],
                                'item_trans_session' => $v['trans_item_trans_session'],
                                'item_qty' => $v['trans_item_out_qty'],
                                'item_price' => $v['trans_item_out_price'],
                                'item_discount' => $v['trans_item_discount'],
                                'item_total' => $v['trans_item_total'],
                                'item_sell_total' => $v['trans_item_sell_total'],
                                'product_name' => $v['product_name'],
                                'product_unit' => $v['product_unit'],
                                'product_image' => !empty($v['product_image']) ? base_url().$v['product_image'] : base_url('upload/noimage.png'),
                            );
                        endforeach;
                        $return->result  = $datas;
                        $return->status  = 1;
                        $return->message = 'Cart loaded';
                        $return->total_records = count($get_data);                        
                    }else{
                        $return->total_records = 0;
                        $return->status  = 0;
                        $return->message = 'Cart kosong';                        
                    }
                }
                $return->action = $action;
                echo json_encode($return);
                break;
            case "cart_add":
                $this->form_validation->set_rules('product_id', 'Produk', 'required');    
                $this->form_validation->set_message('required', '{field} wajib diisi');
                if ($this->form_validation->run() == FALSE){
                    $return->message = validation_errors();
                }else{

                    $session = get_cookie('trans_session');
                    // $trans_number = $this->request_number_for_transaction(2);

                    //Get Product 
                    $get_product = $this->Produk_model->get_produk($post['product_id']);

                    $params_items = array(
                        'trans_item_trans_id' => $trans_id,
                        // 'trans_item_id_order' => $data['order_id'],
                        // 'trans_item_id_order_detail' => $data['order_detail_id'],
                        'trans_item_product_id' => !empty($post['product_id']) ? $post['product_id'] : null,
                        'trans_item_location_id' => 1,
                        'trans_item_date' => date('YmdHis'),
                        'trans_item_unit' => $get_product['product_unit'],
                        // 'trans_item_in_qty' => $qty,
                        // 'trans_item_in_price' => $harga,
                        'trans_item_out_qty' => 1,
                        'trans_item_out_price' => $get_product['product_price_sell'],
                        'trans_item_sell_price' => $get_product['product_price_sell'],   
                        'trans_item_total' => $get_product['product_price_sell']*1,
                        'trans_item_sell_total' => $get_product['product_price_sell']*1,                                                
                        'trans_item_product_type' => 1,
                        'trans_item_type' => 2,
                        'trans_item_discount' => 0,
                        'trans_item_total' => $total,
                        'trans_item_date_created' => date("YmdHis"),
                        'trans_item_date_updated' => date("YmdHis"),
                        'trans_item_user_id' => $session_user_id,
                        'trans_item_branch_id' => $session_branch_id,
                        'trans_item_flag' => $flag,
                        // 'trans_item_ref' => $ref_number,
                        'trans_item_trans_session' => $session,
                        'trans_item_ppn' => 0,
                        'trans_item_position' => 2
                    );
                    $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                    $trans_id = $set_data;

                    $where_update = array(
                        'trans_item_trans_session' => $session
                    );
                    $params_update = array(
                        'trans_item_trans_id' => $trans_id,
                        'trans_item_date' => date('YmdHis'),
                    );

                    $update_item = $this->Transaksi_model->update_transaksi_item_custom($where_update,$params_update);
                        
                    $get_trans = $this->Transaksi_model->get_transaksi($trans_id);                    
                    
                    $return->status = 1;
                    $return->message = 'Berhasil checkout';
                    $return->trans = array(
                        'trans_id' => $trans_id,
                        'trans_number' => $trans_number,
                        'trans_total' => number_format($get_trans['trans_total'],0),
                        'trans_date' => date("d-F-Y, H:i", strtotime($get_trans['trans_date'])),
                        'contact_address' => $get_trans['trans_contact_address'],
                        'contact_name' => $get_trans['trans_contact_name'],
                        'contact_phone' => $get_trans['trans_contact_phone']
                    );
                }
                echo json_encode($return);
                break;                                           
            case "checkout":
                $this->form_validation->set_rules('checkout_name', 'Nama', 'required');
                $this->form_validation->set_rules('checkout_number', 'Nomor WhatsApp', 'required');
                $this->form_validation->set_rules('checkout_address', 'Alamat', 'required');
                $this->form_validation->set_rules('checkout_city', 'Kota', 'required');    
                $this->form_validation->set_message('required', '{field} wajib diisi');
                if ($this->form_validation->run() == FALSE){
                    $return->message = validation_errors();
                }else{

                    $session = get_cookie('trans_session');
                    $trans_number = $this->request_number_for_transaction(2);

                    $params = array(
                        'trans_number' => $trans_number,
                        'trans_session' => $session,
                        'trans_type' => 2,
                        'trans_date' => date('YmdHis'),
                        'trans_date_created' => date('YmdHis'),
                        'trans_date_udpated' => date('YmdHis'),
                        'trans_flag' => 0,
                        'trans_contact_name' => $post['checkout_name'],
                        'trans_contact_phone' => $post['checkout_number'],
                        'trans_contact_address' => $post['checkout_address'],
                        'trans_user_id' => 0,
                    );
                    $set_data = $this->Transaksi_model->add_transaksi($params);
                    $trans_id = $set_data;

                    $where_update = array(
                        'trans_item_trans_session' => $session
                    );
                    $params_update = array(
                        'trans_item_trans_id' => $trans_id,
                        'trans_item_date' => date('YmdHis'),
                    );

                    $update_item = $this->Transaksi_model->update_transaksi_item_custom($where_update,$params_update);
                        
                    $get_trans = $this->Transaksi_model->get_transaksi($trans_id);                    
                    
                    $return->status = 1;
                    $return->message = 'Berhasil checkout';
                    $return->trans = array(
                        'trans_id' => $trans_id,
                        'trans_number' => $trans_number,
                        'trans_total' => number_format($get_trans['trans_total'],0),
                        'trans_date' => date("d-F-Y, H:i", strtotime($get_trans['trans_date'])),
                        'contact_address' => $get_trans['trans_contact_address'],
                        'contact_name' => $get_trans['trans_contact_name'],
                        'contact_phone' => $get_trans['trans_contact_phone']
                    );
                }
                echo json_encode($return);
                break;                                           
            default:
                $this_file              = $this->content_file;
                $data['title']          = 'Website Template';
                $data['author']         = 'John Doe';
                $data['description']    = 'Its not about news, talk to each other something special from this site';
                $data['keywords']       = 'website, john doe, homepage';

                $data['_header']        = $this->nav['web']['header'];
                $data['_footer']        = $this->nav['web']['footer'];
                
                $product_data           = array();
                $category_data          = array();
                $menu_data              = array();
                $news_banner_data       = array();
                $news_popular_data      = array();
                $news_new_data          = array();

                /* News */
                $get_news_banner = $this->News_model->get_all_newss(array('news_flag'=> 1),null,8,0,'news_date_created','asc');
                foreach($get_news_banner as $b):
                    $url_category = $b['category_url'];
                    $url_news = $b['news_url'];
                    $news_banner_data[] = array(
                        'title' => $b['news_title'],
                        'url' => site_url($this->blog_routing).'/'.$url_category.'/'.$url_news,
                        'category' => array(
                            'category_name' => $b['category_name'],
                            'category_url' => site_url($this->blog_routing).'/'.$b['category_url']
                        ),
                        'author' => ucfirst($b['user_username']),
                        'image' => site_url().$b['news_image'],
                        'short' => $b['news_short'],
                        // 'content' => $b['news_content'],
                        'tags' => $b['news_tags'],
                        'keywords' => $b['news_tags'],
                        'visitor' => intval($b['news_visitor']),
                        'created' => date('d-F-Y', strtotime($b['news_date_created'])),
                        'publish' => ($b['news_flag'] == 1) ? 'Publish' : '',
                        // 'other_news' => $this->News_model->get_all_newss(array('news_flag'=>1),'',2,0,'news_visitor','asc')
                    );
                endforeach;

                /*
                $get_news_new = $this->News_model->get_all_newss(array('news_flag'=> 1),null,4,0,'news_date_created','desc');
                foreach($get_news_new as $b):
                    $url_category = $b['category_url'];
                    $url_news = $b['news_url'];
                    $news_new_data[] = array(
                        'title' => $b['news_title'],
                        'url' => site_url($this->blog_routing).'/'.$url_category.'/'.$url_news,
                        'category' => array(
                            'category_name' => $b['category_name'],
                            'category_url' => site_url($this->blog_routing).'/'.$b['category_url']   
                        ),
                        'author' => ucfirst($b['user_username']),
                        'image' => site_url().$b['news_image'],
                        'short' => $b['news_short'],
                        // 'content' => $b['news_content'],
                        'tags' => $b['news_tags'],
                        'keywords' => $b['news_tags'],
                        'visitor' => intval($b['news_visitor']),
                        'created' => date('d-F-Y', strtotime($b['news_date_created'])),
                        'publish' => ($b['news_flag'] == 1) ? 'Publish' : '',
                        // 'other_news' => $this->News_model->get_all_newss(array('news_flag'=>1),'',2,0,'news_visitor','asc')
                    );
                endforeach; 

                $get_news_popular = $this->News_model->get_all_newss(array('news_flag'=> 1),null,8,0,'news_visitor','desc');
                foreach($get_news_popular as $b):
                    $url_category = $b['category_url'];
                    $url_news = $b['news_url'];
                    $news_popular_data[] = array(
                        'title' => $b['news_title'],
                        'url' => site_url($this->blog_routing).'/'.$url_category.'/'.$url_news,
                        'category' => array(
                            'category_name' => $b['category_name'],
                            'category_url' => site_url($this->blog_routing).'/'.$b['category_url']   
                        ),
                        'author' => ucfirst($b['user_username']),
                        'image' => site_url().$b['news_image'],
                        'short' => $b['news_short'],
                        // 'content' => $b['news_content'],
                        'tags' => $b['news_tags'],
                        'keywords' => $b['news_tags'],
                        'visitor' => intval($b['news_visitor']),
                        'created' => date('d-F-Y', strtotime($b['news_date_created'])),
                        'publish' => ($b['news_flag'] == 1) ? 'Publish' : '',
                        // 'other_news' => $this->News_model->get_all_newss(array('news_flag'=>1),'',2,0,'news_visitor','asc')
                    );
                endforeach; 
                //End of News / Blog Data
                */

                /* Categories */
                $params_category = array('category_type' => 1,'category_flag'=>1);
                $get_category = $this->Kategori_model->get_all_categoriess($params_category,null,10,0,'category_name','asc');
                foreach($get_category as $v):
                    $category_data[] = array(
                            'id' => $v['category_id'],
                            'url' => site_url($this->product_routing).'/'.$v['category_url'],
                            'title' => $v['category_name'],
                            'flag' => intval($v['category_flag']),
                            'product_count' => intval($v['category_count'])
                    );
                endforeach;
                //End of Categories Data 

                /* Product */
                $params_product = array('product_flag' => 1);
                $get_product = $this->Produk_model->get_all_produks($params_product,null,30,0,'product_id','asc');
                foreach($get_product as $v):
                    $product_data[] = array(
                            'id' => $v['product_id'],
                            'url' => site_url($this->product_routing).'/'.$v['category_url'].'/'.$v['product_url'],
                            'code' => $v['product_code'],
                            'title' => $v['product_name'],
                            'flag' => $v['product_flag'] == 1 ? 'Tersedia' : 'Tidak Tersedia',
                            // 'ref' => $v['product_ref_id'],
                            'price' => !empty($v['product_price_sell']) ? floatval($v['product_price_sell']) : 0,
                            'price_discount' => !empty($v['product_price_promo']) ? floatval($v['product_price']) : 0,
                            // 'note' => $v['product_note'],
                            'type' => $this->product_type($v['product_type'],null),
                            // 'bedroom' =>  $v['product_bedroom'] > 0 ? $v['product_bedroom'] : '-',
                            // 'bathroom' => $v['product_bathroom'] > 0 ? $v['product_bathroom'] : '-',
                            // 'garage' => $v['product_garage'] > 0 ? $v['product_garage'] : '-',
                            // 'square_size' => $v['product_square_size'] > 0 ? $v['product_square_size'].' m2' : '-',
                            // 'building_size' => $v['product_building_size'] > 0 ? $v['product_building_size'].' m2' : '-',
                            // 'location' => $map = $this->Map_model->get_city(
                            //     array(
                            //         'city_id' => $v['product_city_id']
                            //     )
                            // ),
                            'image' => site_url().$v['product_image']
                    );
                endforeach;
                //End of Product Data 

                //Final Data to Front End
                // $data['navigation'] = $this->product_type(null,null);
                // $data['menus'] = $menu_data;
                $data['product'] = $product_data;
                $data['category'] = $category_data;
                $data['news'] = array(
                    'news_banner' => $news_banner_data,
                    'news_new' => $news_new_data,
                    'news_popular' => $news_popular_data 
                );
                $data['result'] = array(
                    'product' => $data['product'],
                    'category' => $data['category'],
                    'news' => $data['news']
                );
                
                // echo json_encode($data['result']);die;
                $data['_content']       = $this->nav['web']['layout'].$this_file;
                $this->load->view($this->nav['web']['index'],$data);
                
            // End of default
        } // Enf of switch()
    }
    function about(){
        $this_file              = 'home/about';

        $data['title']          = 'About';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }
    function contact_us(){ //Not
        $this_file              = $this->site_dir.'/contact_us';

        $data['title']          = 'Hubungi Kami';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }
    function faqs(){ //Not
        $this_file              = $this->site_dir.'/contact_us';

        $data['title']          = 'Hubungi Kami';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }        
    function signin(){
        $this_file              = $this->site_dir.'/login';

        $data['title']          = 'Masuk';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }

    function products(){ //Works //Template List HTML
        $this_file              = $this->products_dir.'/'.$this->products_file;

        $data['title']          = 'Products';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }
    function product(){ //Works //Template Single HTML
        $this_file              = $this->products_dir.'/'.$this->product_file;

        $data['title']          = 'Produk';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }

    function articles(){ //Works //Template List HTML
        $this_file              = $this->blogs_dir.'/'.$this->blogs_file;

        $data['title']          = 'Artikels';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }
    function article(){ //Works //Template Single HTML, production move to 
        $this_file              = $this->blogs_dir.'/'.$this->blog_file;

        $data['title']          = 'Artikel';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;
        // var_dump($data);die;
        $this->load->view($this->nav['web']['index'],$data);
    }    

    function cart(){ //Works
        $this_file              = $this->site_dir.'/'.$this->cart_file;

        $data['title']          = 'Cart';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }
    function checkout(){ //Works
        $this_file              = $this->site_dir.'/'.$this->checkout_file;

        $data['title']          = 'Checkout';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }
    function wishlist(){ //Works
        $this_file              = $this->site_dir.'/'.$this->wishlist_file;

        $data['title']          = 'Wishlist';
        $data['author']         = 'John Doe';
        $data['description']    = 'Its not about news, talk to each other something special from this site';
        $data['keywords']       = 'website, john doe, homepage';

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_content']       = $this->nav['web']['layout'].$this_file;

        $this->load->view($this->nav['web']['index'],$data);
    }

    //Other
    function notfound(){
        show_404();
        $data['link'] = $this->sitelink();

        $data['template'] = $this->dir['asset'].'/';        
        $data['_content']   = $this->dir['layout'].'home/login';
        $this->load->view($this->dir['layout'].'index',$data);
    }
    function payment(){ die;
        $data['template'] = $this->dir['asset'].'/';
        $data['title'] = 'Selamat Datang di Tabloid Cempaka';
        $data['author'] = 'John Doe';
        $data['description'] = 'Its not about news, talk to each other something special from this site';
        $data['keywords'] = 'website, john doe, homepage';

        $data['_header'] = $this->dir['header'];
        $data['_footer'] = $this->dir['footer'];
        $data['_content']   = $this->dir['layout'].'order/payment';
        $this->load->view($this->dir['layout'].'index',$data); 
    }
    function map(){ die;
        $data['template'] = $this->dir['asset'].'/';
        // $data['controller'] = $this->about_routing;        
        $data['navigation'] = $this->product_type(null,null);        
        $data['title'] = 'Contact Us';
        $data['author'] = 'John Doe';
        $data['description'] = 'Its not about news, talk to each other something special from this site';
        $data['keywords'] = 'website, john doe, homepage';

        $data['_header'] = $this->dir['header'];
        $data['_view']   = $this->dir['content'];        
        $data['_footer'] = $this->dir['footer'];

        // $this->load->view($this->dir['layout'].'about',$data);        
        // $data['_content']   = $this->dir['layout'].'/about';
        // $this->load->view($this->dir['layout'].'/index',$data);        
        $this->load->view($this->dir['layout'].'/map',$data);            
    }
    function firebase(){
        $data['template'] = $this->dir['asset'].'/';
        // $data['controller'] = $this->about_routing;        
        $data['navigation'] = $this->product_type(null,null);        
        $data['title'] = 'Contact Us';
        $data['author'] = 'John Doe';
        $data['description'] = 'Its not about news, talk to each other something special from this site';
        $data['keywords'] = 'website, john doe, homepage';

        $data['_header'] = $this->dir['header'];
        $data['_view']   = $this->dir['content'];        
        $data['_footer'] = $this->dir['footer'];
   
        $data['firebase'] = $this->get_firebase_config();
        $this->load->view($this->dir['admin'].'/firebase',$data);            
    }
    function service($page){ die;
        $data['_header'] = $this->dir['header'];
        $data['_view']   = $this->dir['content'];        
        $data['_footer'] = $this->dir['footer'];
        $data['template'] = $this->dir['asset'].'/';
        // $data['controller'] = $this->about_routing;        
        
        if($page == 'cleaning'){
            $data['title'] = 'Cleaning Service - BPU Cleaning';
            $data['author'] = 'John Doe';
            $data['description'] = 'Its not about news, talk to each other something special from this site';
            $data['keywords'] = 'website, john doe, homepage';
        }
        else if($page == 'security'){
            $data['title'] = 'Security Service - BPU Cleaning';
            $data['author'] = 'John Doe';
            $data['description'] = 'Its not about news, talk to each other something special from this site';
            $data['keywords'] = 'website, john doe, homepage';
        }
        else if($page == 'mustclean'){
            $data['title'] = 'Must Clean - BPU Cleaning';
            $data['author'] = 'John Doe';
            $data['description'] = 'Its not about news, talk to each other something special from this site';
            $data['keywords'] = 'website, john doe, homepage';
        }
        $set_page = 'service_'.$page;
        $this->load->view($this->dir['layout'].$set_page,$data);            
    }

    function blog($categories_url = '',$news_url = ''){ // Production
        $data['navigation'] = $this->product_type(null,null);
        $data['template'] = $this->dir['asset'].'/';

        $view = '';
        $news_short = ''; 
        $news_content = ''; 
        $news_tags = ''; 
        $news_keywords = ''; 
        $news_image = ''; 
        $news_visitor = ''; 
        $news_created = ''; 
        $news_author = ''; 
        $news_status = '';
        
        $other_category = array(); 
        $other_news     = array(); 
        $final_url      = '';

        $url_news = '';
        $url_news_title ='';

        //Get All Categories
        $category_data = array();
        $params = array(
            'category_type' => 2 // 2=News, 
        );
        $get_all_category = $this->Kategori_model->get_all_categoriess($params,null,null,null,'category_name','asc');
        foreach ($get_all_category as $value) {
            $category_data[] = array(
                'category_name' => $value['category_name'],
                'category_url' => $value['category_url'],
                'category_url' => site_url($this->blog_routing).'/'.$value['category_url'],
                'category_count' => $value['category_count']                
            );
        }

        if(empty($categories_url) and empty($news_url)){
            // $other_category = $this->News_model->get_all_newss(array('news_flag'=>1),'',20,0,'news_date_created','asc');            
            // $view = 'blog';
        }

        //Param URL Categories not Empty
        if(!empty($categories_url)){
            $params_check = array(
                'category_parent_id' => 0,
                'category_flag' => 1,
                'category_url' => $categories_url
            );
            // $url_news_category = $categories_url;
            // $url_news_category_title = '';                
            $get_categories = $this->Kategori_model->get_categories_by_params($params_check);
            if($get_categories){
                $url_news_category          = '/'.$get_categories['category_url'];
                $url_news_category_title    = ucwords($get_categories['category_name']);      
                $final_url                  = '/'.$get_categories['category_url'];
                // $other_category = $this->News_model->get_all_newss(array('category_id'=>$get_categories['category_id']),'',4,0,'news_visitor','asc');
                // $other_new = $this->News_model->get_all_newss(array('news_flag'=>1),'',8,0,'news_date_created','desc');                
            }
            $view = 'categories';
        }

        //Param URL News not Empty
        if(!empty($news_url)){
            $params_check = array(
                // 'news_flag' => 0,
                'news_category_id' => $get_categories['category_id'],
                'news_url' => $news_url
            );
            $get_news = $this->News_model->get_news_by_url($params_check);

            if($get_news){
                $this->News_model->update_news($get_news['news_id'],array('news_visitor' => $get_news['news_visitor']+1));
                
                $url_news       = '/'.$get_news['news_url'];
                $url_news_title = ucwords($get_news['news_title']);
                $final_url = $final_url.$url_news;           
                
                $author = $this->User_model->get_user($get_news['news_user_id']);
                $news_short = $get_news['news_short'];
                $news_content = $get_news['news_content'];
                $news_tags = $get_news['news_tags'];
                $news_keywords = $get_news['news_keywords'];
                $news_image = $get_news['news_image'];
                $news_visitor = $get_news['news_visitor'];
                $news_created = date("d-M-Y",strtotime($get_news['news_date_created']));
                $news_author = ucwords($author['user_username']);
                $news_status = $get_news['news_flag'];

                $params_check = array(
                    'category_parent_id' => 0,
                    'category_flag' => 1,
                    'category_id' => $get_news['news_category_id']
                );
                $url_news_category                  = $categories_url;
                $url_news_category_title            = '';                
                $get_categories                     = $this->Kategori_model->get_categories_by_params($params_check);
                if($get_categories){
                    $url_news_category              = '/'.$get_categories['category_url'];
                    $url_news_category_title        = ucwords($get_categories['category_name']);     
                }
            }else{
                if(!empty($categories_url)){
                    $params_check = array(
                        'category_parent_id' => 0,
                        'category_flag' => 1,
                        'category_url' => $categories_url
                    );
                    $url_news_category              = $categories_url;
                    $url_news_category_title        = '';                
                    $get_categories                 = $this->Kategori_model->get_categories_by_params($params_check);
                    if($get_categories){
                        $url_news_category          = '/'.$get_categories['category_url'];
                        $url_news_category_title    = ucwords($get_categories['category_name']);                
                    }
                }  
            }
            $view = 'article';
        }

        $final_url = site_url($this->blog_routing).$final_url;
        $data['pages'] = array(
            'sitelink' => array(
                'home' => array(
                    'title' => 'Home',
                    'url' => site_url()
                ),
                'categories' => array(
                    'url' => site_url($this->blog_routing).$url_news_category,
                    'title' => $url_news_category_title,
                    'result' => $category_data,
                    'other_news' => $other_category,
                ),
                'article' => array(
                    'url' => site_url($this->blog_routing).$url_news_category.$url_news,
                    'title' => $url_news_title,
                    'author' => $news_author,
                    'image' => site_url($news_image),
                    'short' => $news_short,
                    'content' => $news_content,
                    'tags' => $news_tags,
                    'description' => '',
                    'keywords' => $news_keywords,
                    'visitor' => $news_visitor,
                    'created' => $news_created,
                    'publish' => ($news_status == 1) ? 'Publish' : '',
                    'other_news' => $this->News_model->get_all_newss(array('news_flag'=>1),'',2,0,'news_visitor','asc')
                ),
            ),
            'final_url' => $final_url,
            'view' => ''
        );
        //echo json_encode($data['pages']);die;

        $data['_header']        = $this->nav['web']['header'];
        $data['_footer']        = $this->nav['web']['footer'];
        $data['_view']          = $this->dir['content'];

        $data['asset_folder']   = $this->nav['web']['asset']['folder'];
		$data['asset_dir']      = $this->nav['web']['asset']['dir'];		
		$data['asset']          = $this->nav['web']['asset']['dir'].$this->nav['web']['asset']['folder'].'/';
        $data['link']           = $this->sitelink();

        if($view == 'article'){ //www.any.com/article/param1/param2
            $data['title']          = $data['pages']['sitelink']['article']['title'];
            $data['author']         = $data['pages']['sitelink']['article']['author'];
            $data['description']    = substr($data['pages']['sitelink']['article']['content'],0,20);
            $data['keywords']       = $data['pages']['sitelink']['article']['content'];

            $data['image']          = $data['pages']['sitelink']['article']['image'];
            $data['url']            = $final_url;   

            $data['_content']       = $this->nav['web']['layout'].$this->blogs_dir.'/'.$this->blog_file;
            $this->load->view($this->nav['web']['index'],$data);                         
        
        }else if($view == 'categories'){ //www.any.com/article/param1
            $data['title']          = $data['pages']['sitelink']['categories']['title'];
            $data['author']         = 'John Doe';
            $data['description']    = 'Its not about news, talk to each other something special from this site';
            $data['keywords']       = 'website, john doe, homepage';

            $data['url'] = $final_url;

            $data['_content']       = $this->nav['web']['layout'].$this->blogs_dir.'/'.$this->blogs_file;
            $this->load->view($this->nav['web']['index'],$data);                          
        
        }else{
            echo 1;die;
        }
    }
    function agent($contact_url = ''){ die;
        
        $routing = $this->contact_agent;
        $data['navigation'] = $this->product_type(null,null);
        $data['template'] = $this->dir['asset'].'/';

        $next = true; $status = 0;

        $view = ''; 
        // $news_short = ''; $news_content = ''; $news_tags = ''; $news_keywords = ''; $news_image = ''; $news_visitor = ''; $news_created = ''; $news_author = ''; $news_status = '';
        $other_category = array(); $other_news = array(); $final_url = '';
        $get_contact=false;
        
        //Param URL Contact not Empty
        if(!empty($contact_url)){
            $params_check = array(
                'contact_url' => $contact_url
            );
            $url_contact = '/'.$contact_url;
            $url_contact_title = '';
            $get_contact = $this->Kontak_model->get_kontak_by_url($params_check);
            
            if($get_contact==true){
                $visitor_additional = $get_contact['contact_visitor'];
                
                $this->Kontak_model->update_kontak($get_contact['contact_id'],array('contact_visitor' => $visitor_additional+1));
                $url_contact = '/'.$get_contact['contact_url'];
                $url_contact_title = ucwords($get_contact['contact_name']);
                $final_url = $final_url.$url_contact;           
                $status = 1;
                $view = 'contact';         
            }
        }else{ // List of All Agent
            $url_contact = '';
            $next = false;
            $params_check = array(
                'contact_flag' => 1,
            );
            $url_news_category = '';
            $url_news_category_title = '';                
            $get_categories = $this->Kontak_model->get_all_kontaks($params_check,null,20,0,'contact_date_created','asc');
            
            if($get_categories==true){
                $url_news_category = '/';
                $url_news_category_title ='Daftar Agen BestPro';                
            }else{
                $status=0;
                $next = false;
            }
        }
        // var_dump($get_contact);die;
        $final_url = site_url($routing).$final_url;

        $data['pages'] = array(
            'sitelink' => array(
                'home' => array(
                    'title' => 'Home',
                    'url' => site_url($routing)
                ),
                'categories' => array(
                    'url' => $get_contact==false ? site_url($routing).$url_news_category : null,
                    'title' => $get_contact==false ? $url_news_category_title : null,
                    'result' => $get_contact==false ? $get_categories : null,
                ),
                'contact' => array(
                    'url' => site_url($routing).$url_contact,
                    'id' => isset($get_contact['contact_id']) ? $get_contact['contact_id']: null,
                    'type' => 'Agen BestPro',
                    'code' => isset($get_contact['contact_code']) ? $get_contact['contact_code']: null,
                    'name' => isset($get_contact['contact_name']) ? $get_contact['contact_name']: null,
                    'image' => isset($get_contact['contact_image']) ? site_url().$get_contact['contact_image']: null,
                    'email' => isset($get_contact['contact_email_1']) ? $get_contact['contact_email_1']: null,
                    'phone' => isset($get_contact['contact_phone_1']) ? $get_contact['contact_phone_1']: null,
                    'visitor' => isset($get_contact['contact_visitor']) ? $get_contact['contact_visitor']: 0,
                    'created' => isset($get_contact['contact_date_created']) ? $get_contact['contact_date_created']: 0,
                    'publish' => isset($get_contact['contact_flag']) ? 'Publish' : '',
                    'has_data' => $get_contact==true ? $this->Produk_model->get_all_produks_count(array('product_user_id'=>$get_contact['contact_id'])) : 0,
                    // 'other_data' => $get_contact==true ? $this->Produk_model->get_all_produks(
                        // array('product_contact_id' => $get_contact['contact_id']
                        // ),null,8,0,'product_date_created','asc') : null
                ),
            ),
            'final_url' => $final_url,
            'status' => $status,
            'view' => ''
        );
        // echo json_encode($data['pages']);die;
        
        $data['_header'] = $this->dir['header'];
        $data['_footer'] = $this->dir['footer'];
        if($view == 'contact'){
            $data['title'] = 'Agent '.ucwords(strtolower($data['pages']['sitelink']['contact']['name']));
            $data['author'] = 'Agent '.ucwords(strtolower($data['pages']['sitelink']['contact']['name']));
            $data['description'] = 'Agent '.ucwords(strtolower($data['pages']['sitelink']['contact']['name']));
            $data['keywords'] = 'Agent '.ucwords(strtolower($data['pages']['sitelink']['contact']['name']));
            $data['image'] = $data['pages']['sitelink']['contact']['image'];
            $data['url'] = $final_url;

            $data['_content']   = $this->dir['layout'].'/'.$this->agent_file;     
        }else{
            $data['title'] = 'Agent BestPro';
            $data['author'] = 'BestPro';
            $data['description'] = 'Daftar agen resmi BestPro yang dapat ditemukan pada situs ini';
            $data['keywords'] = 'agen, bestpro, property';
            $data['content'] = '';

            $data['_content']   = $this->dir['layout'].'/'.$this->agents_file;     
        }
        $this->load->view($this->dir['layout'].'/index',$data);        
    }
    function produk($product_categories = '', $product_type = '', $product_location = '', $product_url = ''){
        //jual/apartemen/kota%20semarang/dimenhydrinate-erlimpex
        $data['navigation'] = $this->product_type(null,null);        
        $next =true;
        $routing = $this->product_routing;

        $data['template'] = $this->dir['asset'].'/';
        $data['_header'] = $this->dir['header'];     
        $data['_footer'] = $this->dir['footer'];

        if(!empty($product_url) and $product_url !== ''){ // jual/tanah/kabupaten-mimika/rumah-mewah
            $catch_url = $product_categories.'/'.$product_type.'/'.$product_location.'/'.$product_url;
            // var_dump($catch_url);
            $params = array(
                // 'product_type' => 1,
                'product_url' => $catch_url
            );
            $get_product = $this->Produk_model->get_all_produks($params,null,1,0,'product_url','asc');
            $get_contact = $this->Kontak_model->get_kontak($get_product[0]['product_contact_id']);

            // var_dump(site_url($this->contact->routing).'/'.$get_product[0]['product_url'],$params);die;
            $data['pages'] = array(
                'sitelink' => array(
                    'home' => array(
                        'title' => 'Home',
                        'url' => site_url($this->contact_routing)
                    ),
                    'categories' => array(
                        'url' => site_url($this->contact_routing).'/'.$product_categories,
                        'title' => $this->capitalize($product_categories),
                        // 'result' => $get_categories,
                    ),
                    'type' => array(
                        'url' => site_url($this->contact_routing).'/'.$product_categories.'/'.$product_type,
                        'title' => $this->capitalize($product_type),
                        // 'result' => $get_categories,
                    ),  
                    'location' => array(
                        'url' => site_url($this->contact_routing).'/'.$product_categories.'/'.$product_type.'/'.$product_location,
                        'title' => $this->capitalize(str_replace('-', ' ', $product_location)),
                        'data' => $map = $this->Map_model->get_city(
                            array(
                                'city_name' => $this->capitalize(str_replace('-', ' ', $product_location))
                            )
                        ),
                        'result' => $this->Produk_model->get_all_produks(
                            array(
                                'product_type' => $get_product[0]['product_type'],   
                                'product_city_id' => $map['city_id'],
                            ),null,4,0,'RAND()','asc'
                        ),
                    ),
                    'product' => array(
                        'id' => $get_product[0]['product_id'],
                        'url' => site_url($this->contact_routing).$get_product[0]['product_url'],
                        'title' => $get_product[0]['product_name'],
                        'description' => $get_product[0]['product_name'],
                        'keywords' => '',
                        'author' => $get_product[0]['user_username'],
                        'created' => $get_product[0]['product_date_created'],
                        'image' => array(
                            'primary' => site_url().$get_product[0]['product_image'],
                            'other' => $this->Product_item_model->get_all_product_item(array('product_item_product_id' => $get_product[0]['product_id']),null,null,null,null,null)
                        ),
                        'note' => $get_product[0]['product_note'],
                        'type' => $this->product_type($get_product[0]['product_type'],null),
                        //
                        'result' => null,
                        'flag' => $get_product[0]['product_flag'] == 1 ? 'Tersedia' : 'Tidak Tersedia',
                        'price' => 'Rp. '.number_format($get_product[0]['product_price_sell'],0),
                        'bedroom' =>  $get_product[0]['product_bedroom'] > 0 ? $get_product[0]['product_bedroom'] : '-',
                        'bathroom' => $get_product[0]['product_bathroom'] > 0 ? $get_product[0]['product_bathroom'] : '-',
                        'garage' => $get_product[0]['product_garage'] > 0 ? $get_product[0]['product_garage'] : '-',
                        'square_size' => $get_product[0]['product_square_size'] > 0 ? $get_product[0]['product_square_size'].' m2' : '-',
                        'building_size' => $get_product[0]['product_building_size'] > 0 ? $get_product[0]['product_building_size'].' m2' : '-',
                        'contact' => array(
                            'id' => $get_contact['contact_id'],
                            'type' => 'Agent BestPro',
                            'name' => $get_contact['contact_name'],
                            'phone' => $get_contact['contact_phone_1'],
                            'email' => $get_contact['contact_email_1'],
                            'url' => site_url($this->contact_routing).'/'.$get_contact['contact_url'],
                            'image' => site_url().$get_contact['contact_image'] 
                        ),
                        'location' => $map = $this->Map_model->get_city(
                            array(
                                'city_id' => $get_product[0]['product_city_id']
                            )
                        ),                        
                        'amenities' =>
                            "Balcony,
                            Fireplace,
                            Balcony,
                            Fireplace,
                            Basement,
                            Cooling,
                            Basement,
                            Cooling,
                            Dining,
                            Dishwasher,
                            Dining,
                            Dishwasher"
                    )    
                ),
                // 'final_url' => $final_url,
                // 'status' => $status,
                'view' => ''
            );

            $data['title'] = 'Properti '.ucwords(strtolower($data['pages']['sitelink']['product']['title']));
            $data['author'] = ucwords(strtolower($data['pages']['sitelink']['product']['author']));
            $data['description'] = ucwords(strtolower($data['pages']['sitelink']['product']['description']));
            $data['keywords'] = ucwords(strtolower($data['pages']['sitelink']['product']['keywords']));
            $data['image'] = $data['pages']['sitelink']['product']['image'];

            // echo json_encode($data['pages']);die;
            // $this->load->view($this->dir['layout'].$this->product_file,$data);    
            $data['_content']   = $this->dir['layout'].'/'.$this->product_file;                    
            $this->load->view($this->dir['layout'].'/index',$data);

            $next=false;
        }        

        if($next==true){
            if(!empty($product_location)){ // jual/tanah/semarang
                $catch_url = $product_categories.'/'.$product_type.'/'.$product_location;
                // var_dump('Not Ready',$catch_url);
                // $params = array(
                //     'product_type' => 1,
                //     'product_url' => $catch_url
                // );            
                // $this->load->view($this->dir['layout'].'products',$data);            
            }    
        }

        if($next==true){
            if(!empty($product_type)){ // jual/tanah 
                $catch_url = $product_categories.'/'.$product_type;
                $set_product_type = $this->product_type(null,$this->sentencecase($product_type));  
                $params = array(
                    'product_type' => $set_product_type,
                    'product_flag' => 1
                );
                $get_product = $this->Produk_model->get_all_produks($params,null,10,0,'product_id','asc');
                $get_product_count = $this->Produk_model->get_all_produks_count($params);
                $get_product_price_min = $this->Produk_model->get_all_produks_min('product_price_sell');
                $get_product_price_max = $this->Produk_model->get_all_produks_max('product_price_sell');
                $get_product_square_min = $this->Produk_model->get_all_produks_min('product_square_size');
                $get_product_square_max = $this->Produk_model->get_all_produks_max('product_square_size');            

                $get_news = $this->News_model->get_all_newss(array('news_flag'=>1),'',3,0,'news_visitor','asc');

                //Fetch Product Type
                $product_data = array();
                foreach($get_product as $v):
                    $product_data[] = array(
                            'id' => $v['product_id'],
                            'url' => site_url($this->product_routing).'/'.$v['product_url'],
                            'title' => $v['product_name'],            
                            'flag' => $v['product_flag'] == 1 ? 'Tersedia' : 'Tidak Tersedia',
                            'ref' => $v['product_ref_id'] == 1 ? 'Jual' : 'Sewa',
                            'price' => 'Rp. '.number_format($v['product_price_sell'],0),
                            'note' => $v['product_note'],
                            'type' => $this->product_type($v['product_type'],null),                        
                            'bedroom' =>  $v['product_bedroom'] > 0 ? $v['product_bedroom'] : '-',
                            'bathroom' => $v['product_bathroom'] > 0 ? $v['product_bathroom'] : '-',
                            'garage' => $v['product_garage'] > 0 ? $v['product_garage'] : '-',
                            'square_size' => $v['product_square_size'] > 0 ? $v['product_square_size'].' m2' : '-',
                            'building_size' => $v['product_building_size'] > 0 ? $v['product_building_size'].' m2' : '-',
                            'location' => $map = $this->Map_model->get_city(
                                array(
                                    'city_id' => $v['product_city_id']
                                )
                            )                                           
                    );
                endforeach;

                //Fetch News Data
                $news_data = array();
                foreach($get_news as $n):
                    $news_data[] = array(
                            'id' => $n['news_id'],
                            'url' => site_url($this->blog_routing).'/'.$n['category_url'].'/'.$n['news_url'],
                            'category' => $n['category_name'],
                            'title' => $this->capitalize($n['news_title']),            
                            'image' => $n['news_image'],
                            'flag' => $n['news_flag'] == 1 ? 'Publish' : 'Unpublish',            
                            'date_created' => date("d-F-Y", strtotime($n['news_date_created']))                          
                    );
                endforeach;

                $data['pages'] = array(
                    'sitelink' => array(
                        'home' => array(
                            'title' => 'Home',
                            'url' => site_url($this->product_routing)
                        ),
                        'categories' => array(
                            'id' => $product_categories == "jual" ? 1 : 0,
                            'url' => site_url($this->product_routing).'/'.$product_categories,
                            'title' => $this->capitalize($product_categories),
                            // 'result' => $get_categories,
                        ),
                        'type' => array(
                            'id' => $this->product_type(null,$this->capitalize($product_type)),
                            'url' => site_url($this->product_routing).'/'.$product_categories.'/'.$product_type,
                            'title' => $this->capitalize($product_type),
                            'result' => $product_data,
                            'result_total' => $get_product_count,
                            'price_min' => $get_product_price_min,
                            'price_max' => $get_product_price_max,
                            'square_min' => $get_product_square_min,
                            'square_max' => $get_product_square_max,                        
                        ),
                        'news' => $news_data
                    ),
                    'view' => ''
                );            
                $data['title'] = 'Properti '.ucwords(strtolower($data['pages']['sitelink']['categories']['title'])).' '.ucwords(strtolower($data['pages']['sitelink']['type']['title']));
                $data['author'] = 'BestPro';
                $data['description'] = ucwords(strtolower($data['pages']['sitelink']['type']['title']));
                $data['keywords'] = 'BestPro, '.$data['pages']['sitelink']['categories']['title'].', '.$data['pages']['sitelink']['type']['title'];
                $data['image'] = '';

                // echo json_encode($data['pages']);die;
                $data['_content']   = $this->dir['layout'].'/'.$this->products_file;                    
                $this->load->view($this->dir['layout'].'/index',$data);
            }     
        }

        if(!empty($product_categories)){ // jual
            // $this->load->view($this->dir['layout'].'products',$data);    
            // var_dump('Website Line 989');        
        }
    }

}