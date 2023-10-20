<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $route['default_controller'] = 'Index/index';
$route['default_controller']    = 'login';
$route['404_override']          = 'website/notfound';
$route['translate_uri_dashes']  = FALSE;
$route['search']                = "search/index";

/* LOGIN / REGISTER */
	$route['login'] 		= "login/pages/0";
	$route['register'] 		= "login/pages/1";
	$route['password'] 		= "login/pages/2";
	$route['activation'] 	= "login/pages/3";

	//Confirmasi Berhasil Dikirim Ke Email/WA
    // $route['register/confirmation/(:any)'] = "login/register_confirmation/$1"; //http://localhost:8888/git/jrn/register/confirmation/PSR6F7
    $route['register/confirmation'] 		= "login/register_confirmation"; //http://localhost:8888/git/jrn/register/confirmation/PSR6F7
	//Saat User Tekan Konfirmasi di Email/Wa
	$route['register/activation/(:any)'] 	= "login/register_activation/$1"; //http://localhost:8888/git/jrn/register/activation/W82A86WXSJTUYGC2ER7NNP2PAG8SDEPZ/PSR6F7

	//Saat User Mengirim Permintaan Lupa Password
	$route['password_sent'] 			 	= "login/password_sent"; //http://localhost:8888/git/jrn/password_sent
	$route['password/recovery/(:any)']   	= "login/password_reset_form/$1"; //http://localhost:8888/git/jrn/register/activation/W82A86WXSJTUYGC2ER7NNP2PAG8SDEPZ/PSR6F7

	$route['setup'] 				        = "setup/setup_company";	
	// $route['admin/setup_company'] 			= "setup/company";
	// $route['admin/setup_account'] 			= "setup/account";

/* ADMIN */
    $route['admin'] = "index/index";
    $route['session'] = "login/session";
    // $route['login/'] = "auth";
    // $route['report/(:any)'] = "report/manage/$1";

/* MASTER & WhatsApp */
    $route['contact/statistic'] 	= "kontak/pages/00";
    $route['contact'] 				= "kontak/pages/0";
    $route['contact/supplier'] 		= "kontak/pages/1";
    $route['contact/customer'] 		= "kontak/pages/2";
    $route['contact/employee'] 		= "kontak/pages/3";
    // $route['contact/patient'] 		= "kontak/pages/4";
    // $route['contact/insurance'] 	= "kontak/pages/5";
    $route['contact/print'] 	    = "kontak/prints"; 

	$route['product/statistic'] 			= "produk";
	$route['product/product'] 				= "produk/pages/1";
	// $route['product/goods'] 				= "produk/pages/1";
	// $route['product/properti'] 			= "produk/pages/1";	//BestPro
	// $route['product/service'] 				= "produk/pages/2";
	$route['product/asset'] 				= "produk/pages/3";
	// $route['product/tindakan']	 		= "produk/pages/4"; //Deprecated
	// $route['product/laboratory'] 			= "produk/pages/5";
	// $route['product/lain'] 					= "produk/pages/6";
	$route['product/print'] 				= "produk/prints";    
	$route['product/voucher'] 				= "voucher"; 
	$route['product/room'] 					= "produk/pages/2"; //Room	   	

	$route['reference']						= "referensi";
	$route['reference/diagnose'] 			= "referensi/pages/1";
	$route['reference/practice_type'] 		= "referensi/pages/2";
	$route['reference/group_of_goods'] 		= "referensi/pages/3";
	$route['reference/room'] 				= "referensi/pages/7";
	$route['reference/date'] 				= "referensi/pages/8";	
	$route['reference/label'] 				= "referensi/pages/9";
    $route['reference/room_type'] 			= "referensi/pages/10";		
	$route['reference/printer'] 			= "printer";	
	$route['reference/unit'] 				= "konfigurasi/pages/1";
    $route['reference/tax'] 			    = "tax";	

	$route['configuration/statistic'] 		= "konfigurasi";
	$route['product/warehouse'] 			= "konfigurasi/pages/2";
	$route['configuration/account'] 		= "konfigurasi/pages/3";
	$route['configuration/account_map'] 	= "konfigurasi/pages/33";	
	$route['configuration/menu'] 			= "konfigurasi/pages/4";
	$route['configuration/company'] 		= "konfigurasi/pages/5";
	$route['configuration/branch'] 			= "konfigurasi/pages/6";	
    $route['configuration/account/print'] 	= "konfigurasi/prints/3";

	$route['article/article'] 				= "news/pages/1";
	$route['message/template'] 				= "news/pages/2";
	$route['message/device'] 				= "device";    
	$route['message/survey'] 				= "survey";        
    $route['message/recipient']		        = "recipient";    

	$route['category/product'] 				= "kategori/pages/1";
	$route['category/article'] 				= "kategori/pages/2";
	$route['category/contact'] 				= "kategori/pages/4";   

/* TRANSACTION */
    // Purchase
	$route['purchase']				 		= "order/pages/11";
	$route['purchase/order'] 				= "order/pages/1";
	$route['purchase/quotation'] 			= "order/pages/3";
	$route['purchase/buy'] 					= "transaksi/pages/1";	
	$route['purchase/return'] 				= "transaksi/pages/3";
	$route['purchase/return/new'] 			= "transaksi/action/3/create";

    // Sales
	$route['sales']					 		= "order/pages/22";	
	$route['sales/order'] 					= "order/pages/2";
	$route['sales/quotation'] 				= "order/pages/4";
	$route['sales/prepare']		 		    = "order/pages/7"; // Custom    	
	$route['sales/sell'] 					= "transaksi/pages/2";	
	$route['sales/return'] 					= "transaksi/pages/4";
	$route['sales/return/new'] 				= "transaksi/action/4/create";	
	$route['sales/pos']			 			= "order/pages/222";
	$route['sales/pos2']			 		= "pos";
	// $route['sales/pos3']			 		= "pos";    

	// $route['checkup']					= "order/pages/56"; UI Bagus
	// $route['checkup/medicine']			= "order/pages/5";
	// $route['checkup/laboratory']		    = "order/pages/6";

    // Transaction Auto NewForm
	$route['purchase/quotation/new'] 		    = "order/action/3/create";
	$route['purchase/order/new'] 				= "order/action/1/create";	
	$route['sales/order/new'] 					= "order/action/2/create";
	$route['sales/quotation/new'] 				= "order/action/4/create";	
	$route['purchase/buy/new'] 					= "transaksi/action/1/create";
	$route['sales/sell/new'] 					= "transaksi/action/2/create";	

	// $route['checkup/medicine/new'] 				= "order/action/5/create";
	// $route['checkup/laboratory/new'] 			= "order/action/6/create";


/* MANUFACTURE */
	$route['manufacture'] 						= "";
	$route['manufacture/production'] 			= "transaksi/pages/8";	
	// $route['manufacture/quality'] 				= "manufacture/quality";

/* INVENTORY */
	$route['inventory/stock_transfer'] 	    = "transaksi/pages/5";
	// $route['inventory/stok_opname_plus'] 	= "transaksi/pages/6";
	$route['inventory/stock_opname'] 		= "transaksi/pages/6";
	// $route['inventory/stok_opname_minus'] 	= "transaksi/pages/7";
	// $route['gudang/pemeriksaan'] 			= "transaksi/pages/8";    	
	$route['inventory/goods_out'] 	    	= "inventory/pages/9";
	$route['inventory/goods_out_request'] 	= "inventory/pages/99";
	$route['inventory/goods_in'] 			= "inventory/pages/10";

/* INVENTARIS */
	$route['asset/statistic']				= "asset/pages/11";
	$route['asset/buy'] 					= "asset/pages/1";
	$route['asset/sell'] 					= "asset/pages/2";	
	$route['asset/depreciation'] 			= "asset/pages/3";

/* JOURNAL FINANCE */
	$route['finance']				 		= "keuangan";
	$route['finance/account_payable'] 		= "keuangan/pages/1";
	$route['finance/account_receivable'] 	= "keuangan/pages/2";
	$route['finance/cash_in'] 				= "keuangan/pages/3"; // Terima Uang
	$route['finance/cash_out'] 				= "keuangan/pages/9"; // Kirim Uang
	$route['finance/cost_out'] 				= "keuangan/pages/4"; // Biaya
	$route['finance/bank_statement'] 		= "keuangan/pages/5"; // Transfer Uang
	$route['finance/prepaid_expense']  		= "keuangan/pages/6"; // Uang Muka Pembelian
	$route['finance/down_payment']  		= "keuangan/pages/7"; // Uang Muka Penjualan
	$route['finance/general_journal'] 		= "keuangan/pages/8";
	$route['finance/opening_balance'] 		= "keuangan/pages/0";

	$route['finance/account_payable/new'] 		= "keuangan/action/1/create";
	$route['finance/account_receivable/new'] 	= "keuangan/action/2/create";
	$route['finance/cash_in/new'] 				= "keuangan/action/3/create"; // Terima Uang
	$route['finance/cash_out/new'] 				= "keuangan/action/9/create"; // Kirim Uang
	$route['finance/cost_out/new'] 				= "keuangan/action/4/create"; // Biaya
	$route['finance/bank_statement/new'] 		= "keuangan/action/5/create"; // Transfer Uang
	$route['finance/prepaid_expense/new']  		= "keuangan/action/6/create";
	$route['finance/down_payment/new']  		= "keuangan/action/7/create";
	$route['finance/general_journal/new'] 		= "keuangan/action/8/create";	

/* REPORT */
	$route['report'] 			= "report/index";

	/* Report Purchase */
	$route['report/purchase/buy/recap']     = "report/pages/purchase/buy/1";
	$route['report/purchase/buy/detail'] 	= "report/pages/purchase/buy/2";
	$route['report/purchase/buy/account_payable'] 	= "report/pages/purchase/buy/3";	
	$route['report/purchase/order/recap'] 	= "report/pages/purchase/buy/4";
	$route['report/purchase/order/detail'] 	= "report/pages/purchase/buy/5";	
	$route['report/purchase/return/detail'] = "report/pages/purchase/buy/6";

	/* Report Sales */
	$route['report/sales/sell/recap'] 		= "report/pages/sales/sell/1";	
	$route['report/sales/sell/detail'] 		= "report/pages/sales/sell/2";
	$route['report/sales/sell/account_receivable'] 		= "report/pages/sales/sell/3";
	$route['report/sales/order/recap'] 	    = "report/pages/sales/sell/4";
	$route['report/sales/order/detail'] 	= "report/pages/sales/sell/5";	
	$route['report/sales/return/detail'] 	= "report/pages/sales/sell/6";
	$route['report/sales/prepare/detail'] 	= "report/pages/sales/sell/7";

	/* Report Production */
	$route['report/production/product/detail']     = "report/pages/production/product/1";
	// $route['report/purchase/buy/detail'] 	= "report/pages/purchase/buy/2";

	/* Report Stock & Inventory */
	$route['report/inventory/product/stock_warehouse'] 		= "report/pages/inventory/product/1";	
	$route['report/inventory/product/stock_moving'] 		= "report/pages/inventory/product/2";
	$route['report/inventory/product/stock_valuation'] 		= "report/pages/inventory/product/3";	

	/* Report Finance */
	$route['report/finance/journal'] 		= "report/pages/finance/journal/1";
	$route['report/finance/ledger'] 		= "report/pages/finance/ledger/1";
	$route['report/finance/trial_balance'] 	= "report/pages/finance/trial_balance/1";
	$route['report/finance/worksheet'] 		= "report/pages/finance/worksheet/1";	
	$route['report/finance/profit_loss'] 	= "report/pages/finance/profit_loss/1";
	$route['report/finance/balance'] 		= "report/pages/finance/balance/1";
	$route['report/finance/business'] 		= "report/pages/finance/business/1";
	$route['report/finance/cash_in'] 		= "report/pages/finance/cash_in/1";
	$route['report/finance/cash_out'] 		= "report/pages/finance/cash_out/1";		

	$route['transaksi/print/(:any)']  = "transaksi/print_data/$1";
	$route['keuangan/print/(:any)']  = "keuangan/print_data/$1";	

/* Cronjob n Other */
	$route['flow/register']					= "flow/pages/1";
    $route['cronjob/(:any)']                = "cronjob/index/$1";

/* WEBSITE */
    // Home
	$route['about']                 = "website/about";
	$route['contact_us']            = "website/contact_us";	
	$route['masuk']                 = "website/signin";
	$route['faqs']                  = "website/faqs";
	$route['firebase']              = "website/firebase";
	// $route['map']                = "website/map";		

    // Shop
	$route['cart']      	        = "website/cart";
	$route['checkout']  	        = "website/checkout";
	$route['wishlist']   	        = "website/wishlist";	
	$route['payment']   	        = "website/payment";	
    
    // Pages View
	$route['products']              = "website/products";
	$route['articles']              = "website/articles";
	$route['service/(:any)'] 		= "website/service/$1";	
	// $route['agents']             = "website/agents";
        
    // Single View
    $route['product']               = "website/product";
	$route['produk/(:any)/(:any)']  = "website/product/$1/$2";	
	$route['article']               = "website/article";
    $route['article/(:any)']        = "website/blog/$1";
    $route['article/(:any)/(:any)'] = "website/blog/$1/$2";	

    //Not Used / OLD Concept
    // $route['blog'] 					= "website/blog";
	// $route['agent']              = "website/agent";      
	// $route['blog/(:any)'] 							= "website/blog/$1";
	// $route['blog/(:any)/(:any)'] 					= "website/blog/$1/$2";
	// $route['product/kategori_produk'] 	            = "produk/pages/7";
	// $route['agent'] 								    = "website/agent";
	// $route['agent/(:any)'] 							= "website/agent/$1";
	// $route['properti'] 								= "website/product"; //properti
	// $route['properti/(:any)'] 						= "website/product/$1"; //properti/jual
	// $route['properti/(:any)/(:any)'] 				= "website/product/$1/$2"; //properti/jual/apartemen
	// $route['properti/(:any)/(:any)/(:any)'] 		    = "website/product/$1/$2/$3"; //properti/jual/apartemen/semarang
	// $route['properti/(:any)/(:any)/(:any)/(:any)'] 	= "website/product/$1/$2/$3/$4"; //properti/jual/apartemen/semarang/namaproduct
	// $route['product'] 
         
/* Notify */
	// $route['notify/bank'] 					= "notify/pages/1";
	// $route['notify/mutation'] 				= "notify/pages/2"; 
	// $route['notify/balance'] 				= "notify/pages/3";
	// $route['notify/deposit'] 				= "notify/pages/4";           
/* Minio */
	// $route['minio/shortlink'] 					= "minio";    