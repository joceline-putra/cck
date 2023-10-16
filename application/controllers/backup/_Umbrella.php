<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umbrella extends MY_Controller {
    public $asset;
    public $layout;
    public $controller;
    public $product_routing;
    public $blog_routing;
    public $contact_routing;
    public $dir;
    public $header_file;
    public $content_file;
    public $footer_file;
    public $css_file;
    public $js_fle;
    public $blogs_file;
    public $blog_file;    
    public $products_file;
    public $product_file;
    public $contacts_file;    
    public $contact_file;
    public $about_file;
    public $contact_us_file;
    function __construct() {
        parent::__construct();
        // $this->load->model('Product_model');
        $this->layout           = 'umbrella'; /* news, property, store */
        $this->asset            = 'umbrella'; /* homeid, magazine, teamo, webarch */

        $this->product_routing  = 'properti';
        $this->blog_routing     = 'blog';        
        $this->contact_routing  = 'agent';

        //Website Map
        $this->header_file      = '_header';
        $this->content_file     = 'content';
        $this->footer_file      = '_footer';
        $this->css_file         = '_link_css';
        $this->js_file          = '_link_js';

        $this->blogs_file       = 'blogs';
        $this->blog_file        = 'blog';

        $this->products_file    = 'category_product';
        $this->product_file     = 'product';

        $this->contacts_file    = 'agents';
        $this->contact_file     = 'agent';        

        $this->about_file       = 'about';
        $this->contact_us_file  = 'contact_us';        
        $this->dir = array(
            'header' => 'layouts/website/'.$this->layout.'/'.$this->header_file,
            'content' => 'layouts/website/'.$this->layout.'/'.$this->content_file,
            'layout' => 'layouts/website/'.$this->layout.'/',
            'footer' => 'layouts/website/'.$this->layout.'/'.$this->footer_file,
            'asset' => site_url().'assets/'.$this->asset,
            'admin' => 'layouts/admin',
        );

    }	
	public function index()
	{	
		$data['template'] = $this->dir['asset'].'/';    
		$data['title'] = 'Harga';
		$data['content'] = 'home/produk/produk';
		$data['content'] = 'home/_javascript';				
		$this->load->view('layout/index', $data);
	}

	public function pages($url){
		$data['template'] = $this->dir['asset'].'/';    
		$data['title'] = 'Produk ';
		$data['content'] = 'layouts/website/umbrella/home/produk/'.$url;
		// $data['content'] = 'home/_javascript';		
		$this->load->view('layouts/website/umbrella/index', $data);		
	}
	public function price($url){
		$data['template'] = $this->dir['asset'].'/';    		
		$data['title'] = 'Harga ';
		$data['content'] = 'layouts/website/umbrella/home/harga/'.$url;		
		// $data['content'] = 'home/_javascript';		
		$this->load->view('layouts/website/umbrella/index', $data);		
	}
	public function portofolio($url){
		$data['template'] = $this->dir['asset'].'/';    		
		$data['title'] = 'Portofolio';
		$data['content'] = 'layouts/website/umbrella/home/portofolio/'.$url;
		// $data['content'] = 'home/_javascript';		
		$this->load->view('layouts/website/umbrella/index', $data);		
	}	
	public function article($value1,$value2){
		$data['template'] = $this->dir['asset'].'/';    		
		$data['title'] = 'Artikel';
		$data['content'] = 'layouts/website/umbrella/home/artikel/'.$value1;	
		// $data['content'] = 'home/_javascript';				
		$this->load->view('layouts/website/umbrella/index', $data);		
	}
	public function company($url){
		$data['template'] = $this->dir['asset'].'/';    	
		$get_title = true;	
		if($get_title){
			$data['title'] = 'Company';
			$data['content'] = 'layouts/website/umbrella/home/company/'.$url;
			// $data['content'] = 'home/_javascript';		
			$this->load->view('layouts/website/umbrella/index', $data);		
		}else{
			$data['title'] = '404 Not Found';			
			$this->load->view('layouts/website/umbrella/index', $data);			
		}
	}		
}
