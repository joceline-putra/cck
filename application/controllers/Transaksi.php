<?php
/*
    @AUTHOR: Joe Witaya
*/
// require __DIR__ . '/vendor/autoload.php';
// include APPPATH . 'third_party/mike42/escpos/Printer.php';
// include APPPATH . 'third_party/mike42/escpos/PrintConnectors/FilePrintConnector.php';
// include APPPATH . 'third_party/mike42/escpos/PrintConnectors/WindowsPrintConnector.php';
// include APPPATH . 'third_party/mike42/escpos/CapabilityProfile.php';
// include APPPATH . 'third_party/mike42/escpos/Printer';

// use Escpos\PrintConnectors\FilePrintConnector;
// use Escpos\PrintConnectors\WindowsPrintConnector;
// use Escpos\CapabilityProfile;
// use Escpos\Printer;

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends MY_Controller{
    /*
    var $menu_id = 40; // 39
    var $folder_location = array(
        '1' => array(
            '0' => array( //Statistik
                'title' => 'Statistik',
                'view' => 'layouts/admin/menu/purchase/statistic',
                'javascript' => 'layouts/admin/menu/purchase/statistic_js'
            ),
            '1' => array( //Puchase Order
                'title' => 'Pesanan Penjualan',
                'view' => 'layouts/admin/menu/purchase/purchase_order',
                'javascript' => 'layouts/admin/menu/purchase/purchase_order_js'
            ),
            '2' => array( //Penjualan
                'title' => 'Penjualan',
                'view' => 'layouts/admin/menu/purchase/buy',
                'javascript' => 'layouts/admin/menu/purchase/buy_js'
            ),
            '3' => array( //Return
                'title' => 'Retur Pembelian',
                'view' => 'layouts/admin/menu/purchase/return',
                'javascript' => 'layouts/admin/menu/purchase/return_js'
            ),
            '4' => array( //Bayar Hutang
                'title' => 'Bayar Hutang',
                'view' => 'layouts/admin/menu/finance/account_payable',
                'javascript' => 'layouts/admin/menu/finance/account_payable_js'
            ),
        ),
        '2' => array(
            '0' => array( //Statistik
                'title' => 'Statistik',
                'view' => 'layouts/admin/menu/sales/statistic',
                'javascript' => 'layouts/admin/menu/sales/statistic_js'
            ),
            '1' => array( //Sales Order
                'title' => 'Pesanan Penjualan',
                'view' => 'layouts/admin/menu/sales/sales_order',
                'javascript' => 'layouts/admin/menu/sales/sales_order_js'
            ),
            '2' => array( //Penjualan
                'title' => 'Penjualan',
                'view' => 'layouts/admin/menu/sales/sell',
                'javascript' => 'layouts/admin/menu/sales/sell_js'
            ),
            '3' => array( //Return
                'title' => 'Retur Penjualan',
                'view' => 'layouts/admin/menu/sales/return',
                'javascript' => 'layouts/admin/menu/sales/return_js'
            ),
            '4' => array( //Bayar Putang
                'title' => 'Bayar Piutang',
                'view' => 'layouts/admin/menu/finance/account_receivable',
                'javascript' => 'layouts/admin/menu/finance/account_receivable_js'
            ),
        )
    );  */
    var $folder_location = array(
        '1' => array(
            'parent_id' => 40,              
            'title' => 'Pembelian',
            'view' => 'layouts/admin/menu/purchase/buy',
            'javascript' => 'layouts/admin/menu/purchase/buy_js'
        ),
        '2' => array(
            'parent_id' => 39,              
            'title' => 'Penjualan',
            'view' => 'layouts/admin/menu/sales/sell',
            'javascript' => 'layouts/admin/menu/sales/sell_js'
        ),
        '3' => array(
            'parent_id' => 40,              
            'title' => 'Retur Pembelian',
            'view' => 'layouts/admin/menu/purchase/return',
            'javascript' => 'layouts/admin/menu/purchase/return_js'
        ),
        '4' => array(
            'parent_id' => 39,              
            'title' => 'Retur Penjualan',
            'view' => 'layouts/admin/menu/sales/return',
            'javascript' => 'layouts/admin/menu/sales/return_js'
        ),
        '5' => array(
            'parent_id' => 41,             
            'title' => 'Transfer Stok',
            'view' => 'layouts/admin/menu/inventory/transfer',
            'javascript' => 'layouts/admin/menu/inventory/transfer_js'
        ),
        '6' => array(
            'parent_id' => 41,             
            'title' => 'Stock Opname',
            'view' => 'layouts/admin/menu/inventory/opname',
            'javascript' => 'layouts/admin/menu/inventory/opname_js'
        ),
        '8' => array(
            'parent_id' => 41,
            'title' => 'Produksi',
            'view' => 'layouts/admin/menu/inventory/production',
            'javascript' => 'layouts/admin/menu/inventory/production_js'
        ),
    );
    function __construct(){
        parent::__construct();
        $this->print_directory = 'layouts/admin/menu/prints/';
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));            
        }
        $this->load->model('Aktivitas_model');
        $this->load->model('User_model');
        $this->load->model('Kontak_model');
        $this->load->model('Produk_model');
        $this->load->model('Satuan_model');
        $this->load->model('Referensi_model');
        $this->load->model('Order_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Journal_model');
        $this->load->model('Kategori_model');
        $this->load->model('Print_spoiler_model');
        $this->load->model('Product_recipe_model');
        $this->load->model('Product_price_model');
        $this->load->model('Menu_model');
        $this->load->model('Branch_model');
        $this->load->model('Lokasi_model');
        $this->load->model('Type_model');
        $this->load->model('Tax_model');
        $this->load->model('Printer_model');

        $this->load->library('form_validation');
        $this->load->helper('form');    

        $this->print_to         = 0; //0 = Local, 1 = Bluetooth
        $this->whatsapp_config  = 1;      
        $this->module_approval   = 0; //Approval
        $this->module_attachment = 0; //Attachment                      
    }
    function index(){
        $data['identity'] = 2;
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

        $data['title'] = 'Order';
        $data['_view'] = 'layouts/admin/menu/jual/sales_order';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/jual/sales_order_js.php',$data);
    }
    function pages($identity){

        $data['session'] = $this->session->userdata();
        $data['theme']  = $this->User_model->get_user($data['session']['user_data']['user_id']);

        // $params_menu = array(
        //     'menu_parent_id' => $this->menu_id
        // );
        // $data['navigation'] = $this->Menu_model->get_all_menus($params_menu,null,null,null,null,null);

        $data['identity']   = $identity;
        $data['title']      = $this->folder_location[$identity]['title'];
        $data['_view']      = $this->folder_location[$identity]['view'];
        $file_js            = $this->folder_location[$identity]['javascript'];
        
        $data['operator']        = '';
        $data['post_order']      = 0;
        $data['post_trans']      = 0;
        $data['post_contact']    = 0;
        $data['whatsapp_config'] = $this->whatsapp_config;
        $data['module_approval']    = $this->module_approval;
        $data['module_attachment'] = $this->module_attachment;        
        $data['print_to']        =  $this->print_to; //0 = Local, 1 = Bluetooth

        //Sub Navigation
        $params_menu = array(
            'menu_parent_id' => $this->folder_location[$identity]['parent_id'],
            'menu_flag' => 1
        );
        $get_menu = $this->Menu_model->get_all_menus($params_menu,null,null,null,'menu_sorting','asc');
        $data['navigation'] = !empty($get_menu) ? $get_menu : [];
        
        // Ppn display on Buy and Sell + Type Paid
        if($identity==1 or $identity==2){
            $get_tax            = $this->Tax_model->get_all_tax(array('tax_flag >' => 0, 'tax_flag <' => 4),null,null,null,'tax_id','asc');
            $get_type_paid      = $this->Type_model->get_all_type_paid(array('paid_flag' => 1),null,null,null,'paid_id','asc');
            $data['tax']        = $get_tax;
            $data['type_paid']  = $get_type_paid;
        }
        
        // Date
        $firstdate              = new DateTime('first day of this month');
        $datenow                = date("Y-m-d"); 
        $data['first_date']     = date('d-m-Y', strtotime($firstdate->format('Y-m-d')));
        $data['end_date']       = date('d-m-Y', strtotime($datenow));
        $data['end_date_due']   = date('d-m-Y', strtotime('+30 days',strtotime($datenow)));

        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function action($identity,$operator){

        $data['session'] = $this->session->userdata();
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        //Sub Navigation
        $params_menu = array(
            'menu_parent_id' => $this->folder_location[$identity]['parent_id'],
            'menu_flag' => 1
        );
        $get_menu = $this->Menu_model->get_all_menus($params_menu,null,null,null,'menu_sorting','asc');
        $data['navigation'] = !empty($get_menu) ? $get_menu : [];
        // var_dump($data['navigation']);die;

        $data['identity']   = $identity;
        $data['title']      = $this->folder_location[$identity]['title'];
        $data['_view']      = $this->folder_location[$identity]['view'];
        $file_js            = $this->folder_location[$identity]['javascript'];

        $data['operator']           = $operator;
        $data['post_order']         = 0;
        $data['whatsapp_config']    = $this->whatsapp_config;
        $data['print_to']           = $this->print_to; //0 = Local, 1 = Bluetooth

        //For Purchase Buy & Sales Sell
        $data['order_id'] = !empty($this->input->post('order_id')) ? $this->input->post('order_id') : 0;
        if(intval($data['order_id']) > 0){
            $get_order = $this->Order_model->get_order($data['order_id']);
            $data['post_order'] = $get_order['order_id'];
        }

        //For Purchase Return & Sales Return
        $data['trans']   = !empty($this->input->post('trans')) ? $this->input->post('trans') : 0;
        $data['contact'] = !empty($this->input->post('contact')) ? $this->input->post('contact') : 0;
        if(intval($data['trans']) > 0){
            $get_trans              = $this->Transaksi_model->get_transaksi($data['trans']);
            $get_contact            = $this->Kontak_model->get_kontak($data['contact']);
            $data['post_trans']     = !empty($get_trans) ? $get_trans['trans_id'] : 0;
            $data['post_contact']   = !empty($get_contact) ? $get_contact['contact_id'] : 0;
            // var_dump($data['post_contact']);

            //Prepare Retur Data
            $data['post_result']    = array(
                'trans_id' => $get_trans['trans_id'],
                'trans_session' => $get_trans['trans_session'],
                'trans_number' => $get_trans['trans_number'],
                'trans_date' => $get_trans['trans_date'],       
                'trans_date_format' => date('d-m-Y', strtotime($get_trans['trans_date'])),
                'trans_contact_address' => $get_trans['trans_contact_address'],
                'trans_contact_phone' => $get_trans['trans_contact_phone'],
                'trans_contact_email' => $get_trans['trans_contact_email'],
                'contact_id' => $get_contact['contact_id'],
                'contact_type' => $get_contact['contact_type'],
                'contact_code' => $get_contact['contact_code'],
                'contact_name' => $get_contact['contact_name'],
                'contact_phone' => $get_contact['contact_phone_1'],
                'contact_email' => $get_contact['contact_email_1'],
                'return_data' => $this->Transaksi_model->get_all_transaksi_items_return(
                    $get_trans['trans_type'],
                    array(
                        'trans_item_trans_id' => $get_trans['trans_id']
                    ), $search = null, $limit = 0, $start = null, $order = null, $dir = null
                )
            );
        }

        //Date
        $firstdate              = new DateTime('first day of this month');
        $datenow                = date("Y-m-d"); 
        $data['first_date']     = date('d-m-Y', strtotime($firstdate->format('Y-m-d')));
        $data['end_date']       = date('d-m-Y', strtotime($datenow));
        $data['end_date_due']   = date('d-m-Y', strtotime('+30 days',strtotime($datenow)));

        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function manage(){
        // die;
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        if($this->input->post('action')){
            $action = $this->input->post('action');
            $post_data = $this->input->post('data');
            $data = json_decode($post_data, TRUE);
            $data['tipe'] = !empty($data['tipe']) ? $data['tipe'] : 0;
            $identity = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];

            $config_post_to_journal = true; //True = Call SP_JOURNAL_FROM_TRANS, False = Disabled Function

            //Transaksi Tipe
            if($identity == 1){ //Pembelian
                $set_tipe = 1;
                $set_transaction = 'Pembelian';
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1
                );
                */
                $columns = array(
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );
            }
            if($identity == 2){ //Penjualan
                $set_tipe = 2;
                $set_transaction = 'Penjualan';
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1
                );
                */
                $columns = array(
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total',
                    '4' => 'trans_sales_name'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );
            }
            if($identity == 3){ //Retur Pembelian
                $set_tipe = 3;
                $set_transaction = 'Retur Pembelian';
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1
                );
                */
                $columns = array(
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );
            }
            if($identity == 4){ //Retur Penjualan
                $set_tipe = 4;
                $set_transaction = 'Retur Penjualan';
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1
                );
                */
                $columns = array(
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );
            }
            if($identity == 5){ //Mutasi Stock
                $set_tipe = 5;
                $set_transaction = 'Mutasi Stock';
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1
                );
                */
                $columns = array(
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );
            }
            if($identity == 6){ //Stok Opname Plus
                $set_tipe = 6;
                $set_transaction = 'Stock Opname';
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1
                );
                */
                $columns = array(
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );
            }
            if($identity == 8){ //Produksi
                $set_tipe = 8;
                $set_transaction = 'Rencana Produksi';
                /*
                $params = array(
                    'trans_tipe' => $data['tipe'],
                    // 'trans_nomor' => $data['nomor'],
                    'trans_tgl' => date("YmdHis"),
                    'trans_ppn' => $data['ppn'],
                    'trans_diskon' => $data['diskon'],
                    'trans_total' => $data['total'],
                    'trans_keterangan' => $data['keterangan'],
                    'trans_id_kontak' => $data['kontak'],
                    'trans_date_created' => date("YmdHis"),
                    'trans_date_updated' => date("YmdHis"),
                    'trans_id_user' => $session['user_data']['user_id'],
                    'trans_flag' => 1
                );
                */
                $columns = array(
                    '0' => 'trans_date',
                    '1' => 'trans_number',
                    '2' => 'contact_name',
                    '3' => 'trans_total'
                );
                $columns_items = array(
                    '0' => 'code',
                    '1' => 'name',
                    '2' => 'unit',
                    '3' => 'price'
                );
            }

            /* CRUD orders */
            switch($action){
                case "load":
                    $limit      = $this->input->post('length');
                    $start      = $this->input->post('start');
                    $order      = $columns[$this->input->post('order')[0]['column']];
                    $dir        = $this->input->post('order')[0]['dir'];
                    $kontak     = $this->input->post('kontak');
                    $search     = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    /*
                        if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                            $params['other_column'] = $this->input->post('other_column');
                        }
                    */
                    $date_start     = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end       = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));

                    $location_from  = !empty($this->input->post('location_from')) ? $this->input->post('location_from') : 0;
                    $location_to    = !empty($this->input->post('location_to')) ? $this->input->post('location_to') : 0;
                    $type_paid      = !empty($this->input->post('type_paid')) ? $this->input->post('type_paid') : 0;
                    $sales          = !empty($this->input->post('sales')) ? $this->input->post('sales') : 0;                    

                    $params_datatable = array(
                        'trans.trans_date >' => $date_start,
                        'trans.trans_date <' => $date_end,
                        'trans.trans_type' => intval($identity),
                        'trans.trans_flag <' => 4,
                        'trans.trans_branch_id' => intval($session_branch_id)
                    );
                    if($kontak > 0){
                        $params_datatable = array(
                            'trans.trans_date >' => $date_start,
                            'trans.trans_date <' => $date_end,
                            'trans.trans_type' => intval($identity),
                            'trans.trans_flag <' => 4,
                            'trans.trans_branch_id' => intval($session_branch_id),
                            'trans.trans_contact_id' => intval($kontak)
                        );
                    }

                    if(intval($sales) > 0){
                        $params_datatable['trans.trans_sales_id'] = intval($sales);
                    }

                    if(intval($location_from) > 0){
                        $params_datatable['trans.trans_location_id'] = $location_from;
                    }

                    if(intval($location_to) > 0){
                        $params_datatable['trans.trans_location_to_id'] = $location_to;
                    }
                    
                    if(intval($type_paid) > 0){
                        $params_datatable['trans.trans_paid_type'] = $type_paid;
                    }
                    /*
                        Transaksi.php
                        1 Pembelian
                        2 Penjualan
                        3 Retur Beli
                        4 Retur Jual
                        8 Produksi
                        5 Transfer Stok
                        6 Stok Opname

                        Inventory.php
                        9 Pemakaian Produk
                    */
                    $datas_count = $this->Transaksi_model->get_all_transaksis_count($params_datatable,$search);
                    if($datas_count > 0){
                        $datas = $this->Transaksi_model->get_all_transaksis($params_datatable, $search, $limit, $start, $order, $dir);
                        
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$datas_count;
                        $return->result=$datas;
                    }else{
                        $return->message='No data'; 
                        $return->total_records=$datas_count;
                        $return->result=0;
                    }
                    $return->recordsTotal       = $datas_count;
                    $return->recordsFiltered    = $datas_count;
                    $return->params             = $params_datatable;
                    $return->search             = $search;
                    break;
                case "create":
                    $generate_nomor = $this->request_number_for_transaction($identity);

                    $trans_number = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $trans_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $trans_ref_number = !empty($data['nomor_ref']) ? $data['nomor_ref'] : null;
                    $trans_note = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $trans_contact_address = !empty($data['alamat']) ? $data['alamat'] : null;
                    $trans_contact_phone = !empty($data['telepon']) ? $data['telepon'] : null;
                    $trans_contact_email = !empty($data['email']) ? $data['email'] : null;
                    $trans_location = !empty($data['gudang']) ? $data['gudang'] : null;
                    $trans_location_to = !empty($data['gudang_to']) ? $data['gudang_to'] : null;

                    $jam = date('H:i:s');
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $tgl_tempo = isset($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : $tgl;

                    $set_date = $tgl.' '.$jam;
                    $set_date_due = $tgl_tempo.' '.$jam;

                    //Discount Detect
                    $diskon = !empty($data['diskon']) ? $data['diskon'] : 0;
                    $diskon_baris = !empty($data['diskon_baris']) ? str_replace(',','',$data['diskon_baris']) : 0;                    
                    $discount = $diskon + $diskon_baris;

                    //JSON Strigify Post
                    $params = array(
                        'trans_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'trans_contact_id' => !empty($trans_contact) ? $trans_contact : null,
                        'trans_number' => !empty($trans_number) ? $trans_number : null,
                        'trans_date' => $set_date,
                        'trans_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'trans_discount' => $discount,
                        'trans_total' => !empty($data['total']) ? $data['total'] : 0,
                        'trans_change' => !empty($data['change']) ? $data['change'] : 0,
                        'trans_received' => !empty($data['received']) ? $data['received'] : 0,
                        'trans_fee' => !empty($data['fee']) ? $data['fee'] : 0,
                        'trans_note' => !empty($trans_note) ? $trans_note : null,
                        'trans_date_created' => date("YmdHis"),
                        'trans_date_updated' => date("YmdHis"),
                        'trans_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        'trans_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                        'trans_flag' => 0,
                        // 'trans_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        // 'trans_with_dp' => !empty($data['total_down_payment']) ? str_replace(',','',$data['total_down_payment']) : null,
                        'trans_location_id' => !empty($trans_location) ? $trans_location : null,
                        'trans_location_to_id' => !empty($trans_location_to) ? $trans_location_to : null,
                        'trans_ref_number' => !empty($trans_ref_number) ? $trans_ref_number : null,
                        'trans_date_due' => !empty($set_date_due) ? $set_date_due : null,
                        'trans_contact_address' => !empty($trans_contact_address) ? $trans_contact_address : null,
                        'trans_contact_phone' => !empty($trans_contact_phone) ? $trans_contact_phone : null,
                        'trans_contact_email' => !empty($trans_contact_email) ? $trans_contact_email : null,
                        // 'trans_paid' => !empty($data['paid']) ? $data['paid'] : null,
                        'trans_paid_type' => !empty($data['paid_type']) ? $data['paid_type'] : 0,
                        'trans_bank_name' => !empty($data['bank_name']) ? $data['bank_name'] : null,
                        'trans_bank_number' => !empty($data['bank_number']) ? $data['bank_number'] : null,
                        'trans_card_bank_number' => !empty($data['card_bank_number']) ? $data['card_bank_number'] : null,
                        'trans_card_bank_name' => !empty($data['card_bank_name']) ? $data['card_bank_name'] : null,
                        'trans_card_account_name' => !empty($data['card_account_name']) ? $data['card_account_name'] : null,
                        'trans_card_expired' => !empty($data['card_expired']) ? $data['card_expired'] : null,
                        'trans_card_type' => !empty($data['card_type']) ? $data['card_type'] : null,
                        'trans_digital_provider' => !empty($data['digital_provider']) ? $data['digital_provider'] : null,
                        'trans_session' => $this->random_code(20),
                        'trans_sales_id' => !empty($data['trans_sales_id']) ? $data['trans_sales_id'] : null                        
                    );
                    // var_dump($params);die;
                    $params['trans_vehicle_person']           = !empty($data['trans_vehicle_person']) ? $data['trans_vehicle_person'] : null;

                    //Only Rama Motor
                    $params['trans_vehicle_brand']           = !empty($data['trans_vehicle_brand']) ? $data['trans_vehicle_brand'] : 0;
                    $params['trans_vehicle_brand_type_name'] = !empty($data['trans_vehicle_brand_type_name']) ? $data['trans_vehicle_brand_type_name'] : 0;                
                    $params['trans_vehicle_plate_number']    = !empty($data['trans_vehicle_plate_number']) ? $data['trans_vehicle_plate_number'] : null;
                    $params['trans_vehicle_distance']        = !empty($data['trans_vehicle_distance']) ? $data['trans_vehicle_distance'] : 0;                

                    //Check Data Exist
                    $params_check = array(
                        'trans_number' => $trans_number,
                        'trans_branch_id' => $session_branch_id
                    );
                    // var_dump($params);die;
                    $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                    if($check_exists==false){

                        $set_data=$this->Transaksi_model->add_transaksi($params);
                        if($set_data==true){
                            $trans_id = $set_data;
                            $trans_list = $data['trans_list'];
                            foreach($trans_list as $index => $value){

                                $params_update_trans_item = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_date' => $set_date,
                                    // 'trans_item_location_id' => $trans_location,
                                    'trans_item_flag' => 1
                                );
                                $this->Transaksi_model->update_transaksi_item($value,$params_update_trans_item);

                                //For Transfer Stok
                                if($identity==5){
                                    $get_trans_item_list = $this->Transaksi_model->get_transaksi_item($value);
                                    $where = array(
                                        'trans_item_session' => $get_trans_item_list['trans_item_session']
                                    );
                                    $params_update_trans_item = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_date' => $set_date,
                                        'trans_item_flag' => 1
                                    );
                                    $this->Transaksi_model->update_transaksi_item_custom($where,$params_update_trans_item);
                                }
                            }

                            if(intval($trans_id) > 0){
                                /*
                                if(intval($identity) == 1){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );
                                    $params_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 1
                                    );
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                                    $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price'];
                                    $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                                    $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                    $params_total = array(
                                        'trans_total_dpp' => $set_total
                                    );
                                }
                                if((intval($identity) == 2) or (intval($identity) == 8)){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );
                                    $params_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 1
                                    );
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                                    $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_ppn);

                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price'];
                                    $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];

                                    $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                    $params_total = array(
                                        'trans_total_dpp' => $set_total
                                    );
                                }
                                if((intval($identity) == 5)){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price'];
                                    // $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];
                                    $set_total = $total_no_ppn;
                                    $params_total = array(
                                        'trans_total' => $set_total
                                    );
                                }
                                if(intval($identity) == 6){
                                    $params_no_ppn = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_ppn' => 0
                                    );
                                    // $params_ppn = array(
                                    //     'trans_item_trans_id' => $trans_id,
                                    //     'trans_item_ppn' => 1
                                    // );
                                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                                    // $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price'];
                                    // $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                                    // $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                    $set_total = $total_no_ppn;
                                    $params_total = array(
                                        'trans_total_dpp' => $set_total
                                    );
                                }
                                $update_trans = $this->Transaksi_model->update_transaksi($trans_id,$params_total);
                                */

                                //Set To Journal
                                if($config_post_to_journal==true){
                                    $operator = $this->journal_for_transaction('create',$trans_id);
                                    $return->trans_id = 1;
                                }
                            }
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 2,
                                'activity_table' => 'trans',
                                'activity_table_id' => $set_data,
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $trans_number,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);
                            /* End Activity */
                            $get_trans = $this->Transaksi_model->get_transaksi($trans_id);

                            $return->status=1;
                            $return->message='Success';
                            $return->result= array(
                                'trans_id' => $trans_id,
                                'trans_session' => $get_trans['trans_session'],
                                'trans_number' => $trans_number,
                                'trans_total' => number_format($get_trans['trans_total'],0),
                                'trans_total_raw' => $get_trans['trans_total'],
                                'trans_date' => date("d-F-Y, H:i", strtotime($get_trans['trans_date'])),
                                'contact_id' => $get_trans['contact_id'],
                                'contact_name' => $get_trans['contact_name'],
                                'contact_phone' => $get_trans['contact_phone_1']
                            );
                        }
                    }else{
                        $return->message='Nomor '.$trans_number.' sudah digunakan';
                    }
                    break;
                case "create-production":
                    $next = true;
                    $generate_nomor = $this->request_number_for_transaction($identity);

                    $trans_number = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $trans_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $trans_ref_number = !empty($data['ref_number']) ? $data['ref_number'] : null;
                    $trans_note = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $trans_contact_address = !empty($data['alamat']) ? $data['alamat'] : null;
                    $trans_contact_phone = !empty($data['telepon']) ? $data['telepon'] : null;
                    $trans_contact_email = !empty($data['email']) ? $data['email'] : null;
                    $trans_location = !empty($data['gudang']) ? $data['gudang'] : null;

                    $jam = date('H:i:s');

                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $tgl_tempo = isset($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : $tgl;

                    $set_date = $tgl.' '.$jam;
                    $set_date_due = $tgl_tempo.' '.$jam;

                    //List of Material, Cost, Finish Good
                    $raw_material_list = $data['raw_material_list']; //Raw Material
                    $cost_list = $data['cost_list']; //Cost
                    $finish_list = $data['finish_list']; //Finish Goods

                    //JSON Strigify Post
                    $params = array(
                        'trans_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'trans_contact_id' => !empty($trans_contact) ? $trans_contact : null,
                        'trans_number' => !empty($trans_number) ? $trans_number : null,
                        'trans_date' => $set_date,
                        'trans_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'trans_discount' => !empty($data['diskon']) ? $data['diskon'] : 0,
                        'trans_total' => !empty($data['total']) ? $data['total'] : 0,
                        'trans_change' => !empty($data['change']) ? $data['change'] : 0,
                        'trans_received' => !empty($data['received']) ? $data['received'] : 0,
                        'trans_fee' => !empty($data['fee']) ? $data['fee'] : 0,
                        'trans_note' => !empty($trans_note) ? $trans_note : null,
                        'trans_date_created' => date("YmdHis"),
                        'trans_date_updated' => date("YmdHis"),
                        'trans_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        'trans_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                        'trans_flag' => 0,
                        // 'trans_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        // 'trans_with_dp' => !empty($data['total_down_payment']) ? str_replace(',','',$data['total_down_payment']) : null,
                        'trans_location_id' => !empty($trans_location) ? $trans_location : null,
                        'trans_ref_number' => !empty($trans_ref_number) ? $trans_ref_number : null,
                        'trans_date_due' => !empty($set_date_due) ? $set_date_due : null,
                        'trans_contact_address' => !empty($trans_contact_address) ? $trans_contact_address : null,
                        'trans_contact_phone' => !empty($trans_contact_phone) ? $trans_contact_phone : null,
                        'trans_contact_email' => !empty($trans_contact_email) ? $trans_contact_email : null,
                        // 'trans_paid' => !empty($data['paid']) ? $data['paid'] : null,
                        'trans_paid_type' => !empty($data['paid_type']) ? $data['paid_type'] : null,
                        'trans_bank_name' => !empty($data['bank_name']) ? $data['bank_name'] : null,
                        'trans_bank_number' => !empty($data['bank_number']) ? $data['bank_number'] : null,
                        'trans_card_bank_number' => !empty($data['card_bank_number']) ? $data['card_bank_number'] : null,
                        'trans_card_bank_name' => !empty($data['card_bank_name']) ? $data['card_bank_name'] : null,
                        'trans_card_account_name' => !empty($data['card_account_name']) ? $data['card_account_name'] : null,
                        'trans_card_expired' => !empty($data['card_expired']) ? $data['card_expired'] : null,
                        'trans_card_type' => !empty($data['card_type']) ? $data['card_type'] : null,
                        'trans_digital_provider' => !empty($data['digital_provider']) ? $data['digital_provider'] : null,
                        'trans_session' => $this->random_code(20)
                    );

                    //Check Data Exist
                    $params_check = array(
                        'trans_number' => $trans_number,
                        'trans_type' => $identity,
                        'trans_branch_id' => $session_branch_id
                    );

                    if(count($raw_material_list) < 1){
                        $message = 'Daftar Bahan Baku tidak boleh kosong';
                        $next = false;
                    }

                    if($next){
                        if(count($finish_list) < 1){
                            $message = 'Daftar Produk Jadi tidak boleh kosong';
                            $next = false;
                        }
                    }

                    if($next){
                        $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                        if($check_exists==false){

                            $set_data=$this->Transaksi_model->add_transaksi($params);
                            if($set_data==true){
                                $trans_id = $set_data;

                                //Update Raw Material to Trans
                                foreach($raw_material_list as $index => $value){
                                    $params_update_trans_item_raw = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_date' => $set_date,
                                        // 'trans_item_location_id' => $trans_location,
                                        'trans_item_flag' => 1
                                    );
                                    $this->Transaksi_model->update_transaksi_item($value,$params_update_trans_item_raw);
                                }

                                //Update Cost
                                /*foreach($cost_list as $index => $value){
                                    $params_update_trans_item_cost = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_date' => $set_date,
                                        'trans_item_location_id' => $trans_location,
                                        'trans_item_flag' => 1
                                    );
                                    $this->Transaksi_model->update_transaksi_item($value,$params_update_trans_item_cost);
                                }*/

                                //Update Finish to Trans
                                foreach($finish_list as $index => $value){
                                    $params_update_trans_item_finish = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_date' => $set_date,
                                        // 'trans_item_location_id' => $trans_location,
                                        'trans_item_flag' => 1
                                    );
                                    $this->Transaksi_model->update_transaksi_item($value,$params_update_trans_item_finish);
                                }

                                if(intval($trans_id) > 0){
                                    $params_get_out_total = array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_type' => 8,
                                        'trans_item_position' => 2
                                    );
                                    $get_total = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_get_out_total);
                                    $set_total = $get_total['trans_item_out_price'];  //4000

                                    //Get All Produk Jadi
                                    $params_finish_good=array(
                                        'trans_item_trans_id' => $trans_id,
                                        'trans_item_type' => 8,
                                        'trans_item_branch_id' => $session_branch_id,
                                        'trans_item_position' => 1
                                    );

                                    $get_finish_good_count = $this->Transaksi_model->get_transaksi_item_in_qty_total($trans_id,$params_finish_good);
                                    $set_hpp = $set_total / $get_finish_good_count['trans_item_in_qty_total']; //4000 / 400 = 10

                                    $get_finish_good = $this->Transaksi_model->get_all_transaksi_items($params_finish_good,null,null,null,null,null);
                                    foreach($get_finish_good as $finish => $v){
                                        $params_update_trans_item_cost = array(
                                            'trans_item_in_price' => $set_hpp,
                                            'trans_item_total' => $set_hpp * $v['trans_item_in_qty']
                                        );
                                        $this->Transaksi_model->update_transaksi_item($v['trans_item_id'],$params_update_trans_item_cost);
                                    }

                                    //Set To Journal
                                    if($config_post_to_journal==true){
                                        $operator = $this->journal_for_transaction('create',$trans_id);
                                        $return->trans_id = 1;
                                    }
                                }
                                /* Start Activity */
                                $params = array(
                                    'activity_user_id' => $session_user_id,
                                    'activity_branch_id' => $session_branch_id,
                                    'activity_action' => 2,
                                    'activity_table' => 'trans',
                                    'activity_table_id' => $set_data,
                                    'activity_text_1' => $set_transaction,
                                    'activity_text_2' => $trans_number,
                                    'activity_date_created' => date('YmdHis'),
                                    'activity_flag' => 1,
                                    'activity_transaction' => $set_transaction,
                                    'activity_type' => 2
                                );
                                $this->save_activity($params);
                                /* End Activity */

                                $get_trans = $this->Transaksi_model->get_transaksi($trans_id);

                                $return->status=1;
                                $return->message='Berhasil menyimpan';
                                $return->result= array(
                                    'trans_id' => $trans_id,
                                    'trans_number' => $trans_number,
                                    'trans_session' => $get_trans['trans_session']
                                );
                            }
                        }else{
                            $return->message='Nomor sudah digunakan';
                        }
                    }else{
                        $return->message = $message;
                    }
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);
                    $data['id'] = $this->input->post('id');
                    $datas = $this->Transaksi_model->get_transaksi($data['id']);
                    if($datas==true){
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 3,
                            'activity_table' => 'trans',
                            'activity_table_id' => $data['id'],
                            'activity_text_1' => $set_transaction,
                            'activity_text_2' => $datas['trans_number'],
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);
                        /* End Activity */
                    }

                    if($identity==5){ //Transfer Stock Return Location TO
                        $get_location_to = $this->Lokasi_model->get_lokasi($datas['trans_location_to_id']);
                        $return->location_to = array(
                            'location_id' => $get_location_to['location_id'],
                            'location_code' => $get_location_to['location_code'],
                            'location_name' => $get_location_to['location_name']
                        );
                    }
                    //Get Order on Trans Table
                    $params = array(
                        'trans_item_trans_id' => $data['id']
                    );
                    $get_trans = $this->Transaksi_model->get_all_transaksi_items_count($params);

                    $return->status = 1;
                    $return->message = 'Membuka '.$datas['trans_number'].' - '.$datas['contact_name'];
                    $return->result = $datas;
                    $return->result_trans = $get_trans;
                    $return->result_employee = !empty($datas['trans_vehicle_person']) ? $this->Kontak_model->get_kontak($datas['trans_vehicle_person']) : 0;
                    break;
                case "update":
                    $next = true;
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];

                    //Get Data Before
                    $get_data = $this->Transaksi_model->get_transaksi($id);

                    //Fetch POST
                    $trans_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $trans_ref_number = !empty($data['nomor_ref']) ? $data['nomor_ref'] : null;
                    $trans_note = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $trans_contact_address = !empty($data['alamat']) ? $data['alamat'] : null;
                    $trans_contact_phone = !empty($data['telepon']) ? $data['telepon'] : null;
                    $trans_contact_email = !empty($data['email']) ? $data['email'] : null;
                    $trans_location = !empty($data['gudang']) ? $data['gudang'] : null;
                    $trans_location_to = !empty($data['gudang_to']) ? $data['gudang_to'] : null;
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = substr($get_data['trans_date'],10,9);
                    $set_date = $tgl.$jam;

                    $tgl_tempo = isset($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : $tgl;
                    $set_date_due = $tgl_tempo.$jam;

                    //Discount Detect
                    $diskon = !empty($data['diskon']) ? $data['diskon'] : 0;
                    $diskon_baris = !empty($data['diskon_baris']) ? str_replace(',','',$data['diskon_baris']) : 0;                    
                    $trans_discount = $diskon + $diskon_baris;
                                        
                    //JSON Strigify Post
                    $params = array(
                        // 'trans_type' => !empty($data['tipe']) ? $data['tipe'] : null,
                        'trans_contact_id' => !empty($trans_contact) ? $trans_contact : null,
                        // 'trans_number' => !empty($trans_number) ? $trans_number : null,
                        'trans_date' => $set_date,
                        // 'trans_ppn' => !empty($data['ppn']) ? $data['ppn'] : null,
                        'trans_discount' => $trans_discount,
                        // 'trans_total' => !empty($data['total']) ? $data['total'] : null,
                        'trans_note' => !empty($trans_note) ? $trans_note : null,
                        // 'trans_date_created' => date("YmdHis"),
                        'trans_date_updated' => date("YmdHis"),
                        // 'trans_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        // 'trans_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
                        // 'trans_flag' => 0,
                        // 'trans_ref_id' => !empty($data['ref']) ? $data['ref'] : null,
                        // 'trans_with_dp' => !empty($data['total_down_payment']) ? str_replace(',','',$data['total_down_payment']) : null,
                        'trans_location_id' => !empty($trans_location) ? $trans_location : null,
                        'trans_location_to_id' => !empty($trans_location_to) ? $trans_location_to : null,
                        'trans_ref_number' => !empty($trans_ref_number) ? $trans_ref_number : null,
                        'trans_date_due' => !empty($set_date_due) ? $set_date_due : null,
                        'trans_contact_address' => !empty($trans_contact_address) ? $trans_contact_address : null,
                        'trans_contact_phone' => !empty($trans_contact_phone) ? $trans_contact_phone : null,
                        'trans_contact_email' => !empty($trans_contact_email) ? $trans_contact_email : null,
                        // 'trans_paid' => !empty($data['paid']) ? $data['paid'] : null,
                        'trans_paid_type' => !empty($data['paid_type']) ? $data['paid_type'] : 0,
                        // 'trans_bank_name' => !empty($data['bank_name']) ? $data['bank_name'] : null,
                        // 'trans_bank_number' => !empty($data['bank_number']) ? $data['bank_number'] : null,
                        // 'trans_card_bank_number' => !empty($data['card_bank_number']) ? $data['card_bank_number'] : null,
                        // 'trans_card_bank_name' => !empty($data['card_bank_name']) ? $data['card_bank_name'] : null,
                        // 'trans_card_account_name' => !empty($data['card_account_name']) ? $data['card_account_name'] : null,
                        // 'trans_card_expired' => !empty($data['card_expired']) ? $data['card_expired'] : null,
                        // 'trans_card_type' => !empty($data['card_type']) ? $data['card_type'] : null,
                        // 'trans_digital_provider' => !empty($data['digital_provider']) ? $data['digital_provider'] : null
                        'trans_sales_id' => !empty($data['trans_sales_id']) ? $data['trans_sales_id'] : null
                    );
                    // var_dump($params);die;
                    $params['trans_vehicle_person']           = !empty($data['trans_vehicle_person']) ? $data['trans_vehicle_person'] : null;
                    $params['trans_vehicle_plate_number']     = !empty($data['trans_vehicle_plate_number']) ? $data['trans_vehicle_plate_number'] : null;

                    $params_items = array(
                        'trans_item_date' => $set_date,
                        // 'trans_item_location_id' => $trans_location,
                        'trans_item_date_updated' => date("YmdHis"),
                        'trans_item_flag' => 1
                    );
                    $params_items_remove = array(
                        'trans_item_trans_id'=> $id,
                        'trans_item_branch_id' => $session_branch_id,
                        'trans_item_flag' => 0
                    );

                    //Check if Location change
                    /*
                    if($next){
                        if(intval($get_data['trans_location_id']) != intval($trans_location)){
                            $next=false;
                            $return->message = 'Gudang tidak boleh di ganti';
                        }
                    }
                    */

                    if($next){
                        $params_check = array();
                        $get_journal = 0;
                        //Pembelian / Penjualan sudah pernah terjurnal
                        $trans_type = $get_data['trans_type'];
                        if(intval($trans_type == 1) or intval($trans_type == 2)){
                            
                            $params_check = array(
                                'journal_item_trans_id' => $id
                            );

                            if($trans_type == 1){
                                // $set_trans_type = 10;
                                $set_trans_type = 1;
                                $params_check['journal_item_type'] = $set_trans_type;
                                $params_check['journal_item_debit >'] = '0.00';                            

                            }elseif($trans_type == 2){
                                // $set_trans_type = 11;
                                $set_trans_type = 2;
                                $params_check['journal_item_type'] = $set_trans_type;
                                $params_check['journal_item_credit >'] = '0.00';
                            }

                            $get_journal = $this->Journal_model->get_all_journal_item_custom_count($params_check,$search=null);

                            //Beli dan Jual Sebagian di lunasi
                            if(intval($get_journal) > 0){

                                $next=false;
                                $return->message = 'Transaksi sudah terjurnal tidak dapat di perbarui';

                                //Contact Has Replace
                                if($get_data['trans_contact_id'] != $trans_contact){
                                    $next=false;
                                    // $return->message='Kontak tidak boleh diganti';
                                    $return->message = 'Transaksi ini sudah dibayar sebagian, tidak dapat di perbarui';
                                }
                            }
                        }
                        $return->info = array(
                            'contact' => array(
                                'contact_from' => $get_data['trans_contact_id'],
                                'contact_to' => $trans_contact
                            ),
                            'params_journal_check' => $params_check,
                            'journal' => $get_journal
                        );
                    }

                    if($next){
                        $set_update_trans               = $this->Transaksi_model->update_transaksi($id,$params);
                        $set_update_trans_item          = $this->Transaksi_model->update_transaksi_item_by_trans_id($id,$params_items);
                        // $set_update_trans_item_flag_0   = $this->Transaksi_model->delete_transaksi_item_by_trans_id($params_items_remove);

                        $trans_id=$id;
                        if(intval($trans_id) > 0){
                            //Set To Journal
                            if($config_post_to_journal==true){
                                $operator = $this->journal_for_transaction('update',$trans_id);
                                $return->trans_id = 1;
                            }
                        }
                        if($set_update_trans==true){
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'trans',
                                'activity_table_id' => $id,
                                'activity_text_1' => $set_transaction,
                                // 'activity_text_2' => $generate_nomor,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil memperbarui';
                        }
                    }
                    break;
                case "update-production":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $next = true;

                    $id = $data['id'];
                    $get_data = $this->Transaksi_model->get_transaksi($id);

                    // $order_number = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;
                    $trans_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $trans_ref_number = !empty($data['nomor_ref']) ? $data['nomor_ref'] : null;
                    $trans_note = !empty($data['keterangan']) ? $data['keterangan'] : null;

                    $trans_contact_address = !empty($data['alamat']) ? $data['alamat'] : null;
                    $trans_contact_phone = !empty($data['telepon']) ? $data['telepon'] : null;
                    $trans_contact_email = !empty($data['email']) ? $data['email'] : null;

                    $trans_location = !empty($data['gudang']) ? $data['gudang'] : null;

                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = substr($get_data['trans_date'],10,9);
                    $set_date = $tgl.$jam;

                    $tgl_tempo = isset($data['tgl_tempo']) ? substr($data['tgl_tempo'], 6,4).'-'.substr($data['tgl_tempo'], 3,2).'-'.substr($data['tgl_tempo'], 0,2) : $tgl;
                    $set_date_due = $tgl_tempo.$jam;

                    //List of Material, Cost, Finish Good
                    $raw_material_list = $data['raw_material_list']; //Raw Material
                    $cost_list = $data['cost_list']; //Cost
                    $finish_list = $data['finish_list']; //Finish Goods

                    if(count($raw_material_list) < 1){
                        $message = 'Daftar Bahan Baku tidak boleh kosong';
                        $next = false;
                    }

                    if($next){
                        if(count($finish_list) < 1){
                            $message = 'Daftar Produk Jadi tidak boleh kosong';
                            $next = false;
                        }
                    }

                    if($next){

                        //JSON Strigify Post
                        $params = array(
                            'trans_contact_id' => !empty($trans_contact) ? $trans_contact : null,
                            // 'trans_number' => !empty($trans_number) ? $trans_number : null,
                            'trans_date' => $set_date,
                            'trans_note' => !empty($trans_note) ? $trans_note : null,
                            'trans_date_updated' => date("YmdHis"),
                            'trans_location_id' => !empty($trans_location) ? $trans_location : null,
                            'trans_ref_number' => !empty($trans_ref_number) ? $trans_ref_number : null,
                            'trans_date_due' => !empty($set_date_due) ? $set_date_due : null,
                            'trans_contact_address' => !empty($trans_contact_address) ? $trans_contact_address : null,
                            'trans_contact_phone' => !empty($trans_contact_phone) ? $trans_contact_phone : null,
                            'trans_contact_email' => !empty($trans_contact_email) ? $trans_contact_email : null
                        );
                        $params_items = array(
                            'trans_item_date' => $set_date,
                            // 'trans_item_location_id' => $trans_location,
                            'trans_item_date_updated' => date("YmdHis"),
                            'trans_item_flag' => 1,
                        );
                        $params_remove = array(
                            'trans_item_trans_id'=> $id,
                            'trans_item_branch_id' => $session_branch_id,
                            'trans_item_flag' => 0
                        );

                        $set_update               = $this->Transaksi_model->update_transaksi($id,$params);
                        $set_update_item          = $this->Transaksi_model->update_transaksi_item_by_trans_id($id,$params_items);
                        // $update_trans_item_flag_0 = $this->Transaksi_model->delete_transaksi_item_by_trans_id($params_remove);
                        $trans_id=$id;

                        if(intval($trans_id) > 0){
                            $params_get_out_total = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_type' => 8,
                                'trans_item_position' => 2
                            );
                            $params_debit = array(
                                'journal_item_trans_id' => $trans_id,
                                'journal_item_position' => 3
                            );

                            $get_total = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_get_out_total);
                            $get_cost = $this->Journal_model->get_journal_item_debit_sum($params_debit);

                            $set_total = $get_total['trans_item_out_price'] + $get_cost['journal_item_debit'];  //4000
                            // var_dump($get_total['trans_item_out_price'],$get_cost['journal_item_debit']);die;

                            //Get All Produk Jadi
                            $params_finish_good=array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_type' => 8,
                                'trans_item_branch_id' => $session_branch_id,
                                'trans_item_position' => 1
                            );

                            $get_finish_good_count = $this->Transaksi_model->get_transaksi_item_in_qty_total($trans_id,$params_finish_good);
                            $set_hpp = $set_total / $get_finish_good_count['trans_item_in_qty_total']; //2000 / 6 = 33
                            // var_dump($set_hpp,$set_total);
                            $get_finish_good = $this->Transaksi_model->get_all_transaksi_items($params_finish_good,null,null,null,null,null);
                            foreach($get_finish_good as $finish => $v){
                                $params_update_trans_item_cost = array(
                                    'trans_item_in_price' => $set_hpp, //33
                                    'trans_item_total' => $set_hpp * $v['trans_item_in_qty'] // 33 * 6
                                );
                                $this->Transaksi_model->update_transaksi_item($v['trans_item_id'],$params_update_trans_item_cost);
                            }

                            //Set To Journal
                            if($config_post_to_journal==true){
                                $operator = $this->journal_for_transaction('update',$trans_id);
                                $return->trans_id = 1;
                            }
                        }
                        if($set_update==true){
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'trans',
                                'activity_table_id' => $id,
                                'activity_text_1' => $set_transaction,
                                // 'activity_text_2' => $generate_nomor,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);
                            /* End Activity */
                            $return->status=1;
                            $return->message='Berhasil memperbarui';
                        }
                    }else{
                        $return->message=$message;
                    }
                    break;
                case "delete":
                    $trans_id       = $this->input->post('id');
                    $number         = $this->input->post('number');
                    // $flag           = $this->input->post('flag');

                    $flag           = 4;
                    $next           = true;
                    $set_data       = false;
                    $message        = 'Gagal menghapus';

                    $check_stock    = false;
                    $check_arap     = false;
                    $check_return   = false;

                    // Point 1 = Check the Stock is available to delete
                    $params = array('trans_item_trans_id' => $trans_id);
                    $check_stock_on_trans_item = $this->Transaksi_model->get_all_transaksi_items($params,null,null,null,'trans_item_id','asc');
                    foreach($check_stock_on_trans_item as $v):
                        $set_trans_item_position = ($v['trans_item_position']==1) ? 2 : 1;
                        if(intval($set_trans_item_position) == 2){

                            if($identity==5){//Transfer Stok
                                /*
                                $params_check = array(
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_ref' => $v['trans_item_ref'],
                                    'trans_item_position' => $set_trans_item_position
                                );
                                $check = $this->Transaksi_model->check_stock_is_available_to_delete($params_check);
                                if($check > 0){
                                    $message='Gagal dihapus, Stok sudah digunakan';
                                    $next=false;
                                }
                                */
                                $next=true;
                            }else if($identity==4){ //Retur Jual

                            }else{
                                $params_check = array(
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_ref' => $v['trans_item_ref'],
                                    'trans_item_position' => $set_trans_item_position
                                );
                                // var_dump($params_check);die;
                                $check = $this->Transaksi_model->check_stock_is_available_to_delete($params_check);
                                if($check > 0){
                                    $message='Gagal dihapus, Stok sudah digunakan';
                                    $check_stock = true;
                                    $next=false;

                                    $params_check = array(
                                        'trans_item_branch_id' => $session_branch_id,
                                        'trans_item_ref' => $v['trans_item_ref'],
                                        'trans_item_position' => $set_trans_item_position
                                    );                 
                                    $get_item = $this->Transaksi_model->get_transaksi_item_custom($params_check);
                                    $data_item = array();
                                    foreach($get_item as $v):
                                        $data_item[] = array(
                                            'trans_id' => $v['trans_id'],
                                            'trans_number' => $v['trans_number'],
                                            'trans_session' => $v['trans_session'],
                                            'trans_date' => $v['trans_date'],
                                            'trans_date_format' => date("d-M-Y", strtotime($v['trans_date'])),
                                            'trans_item_id' => $v['trans_item_id'],
                                            'product_id' => $v['product_id'],
                                            'product_name' => $v['product_name'],
                                            'product_unit' => $v['product_unit'],
                                            'trans_url' => site_url().'transaksi/print_history/'.$v['trans_session']
                                        );
                                    endforeach;               
                                    $return->result = $data_item;
                                }
                            }
                        }
                    endforeach;

                    // Point 2 = Check Account Payable & Receivable Has Paid or Termin
                    if($next){
                        $message = 'Check AP / AR';
                        $params  = array(
                            'journal_item_trans_id' => $trans_id,
                            'journal_item_journal_id >' => 0
                        );
                        $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                        if(intval($check_trans_on_journal_item) > 0){
                            $message    = 'Gagal, Transaksi ini sudah terbayar';
                            $next       = false;
                            $set_data   = false;
                            $check_arap = true;

                            if($identity == 1){ //Params Cek Hutang Sudah Lunas Belum
                                $params = array(
                                    'journal_item_trans_id' => $trans_id,
                                    'journal_item_type' => 1,
                                    'journal_item_debit > ' => 0,
                                );
                            }else if ($identity == 2){ //Params Cek Piutang Sudah Lunas Belum
                                $params = array(
                                    'journal_item_trans_id' => $trans_id,
                                    'journal_item_type' => 2,
                                    'journal_item_credit > ' => 0,
                                );
                            }
                            $get_ap = $this->Journal_model->get_all_journal_item_custom($params);
                            $ap_data = array();
                            foreach($get_ap as $v):
                                $set_url = site_url().'keuangan/prints/'.$v['journal_id'];

                                $ap_data[] = array(
                                    'journal_number' => $v['journal_number'],
                                    'journal_date_format' => date("d-M-Y", strtotime($v['journal_date'])),
                                    'journal_url' => $set_url
                                );
                            endforeach;
                            $return->result = $ap_data;
                            $return->status = 4;
                        }
                    }

                    // Point 3 = Check Trans Has Return
                    if($next){
                        $message = 'Check Trans Return';
                        $params = array(
                            'trans_id_source' => $trans_id
                        );
                        $check_trans_have_return = $this->Transaksi_model->get_all_transaksis_count($params,null);
                        // var_dump($check_trans_have_return);die;
                        if(intval($check_trans_have_return) > 0){
                            $message    = 'Gagal, Transaksi ini ada retur';
                            $next       = false;
                            $set_data   = false;
                            $check_return = true;

                            $get_return = $this->Transaksi_model->get_all_transaksis($params,null,null,null,null,null);
                            $return_data = array();
                            foreach($get_return as $v):
                                $set_url = site_url().'transaksi/prints/'.$v['trans_id'];
                                if(($v['trans_type'] == 1) or ($v['trans_type'] == 2)){
                                    $set_url = site_url().'transaksi/print_history/'.$v['trans_session'];
                                }

                                $return_data[] = array(
                                    'trans_number' => $v['trans_number'],
                                    'trans_date_format' => date("d-M-Y", strtotime($v['trans_date'])),
                                    'trans_url' => $set_url
                                );
                            endforeach;
                            $return->result = $return_data; 
                            $return->status = 5;
                        }
                    }

                    $return->check = array(
                        'stock_is_available_to_delete' => $check_stock,
                        'receivable_payable_has_payment' => $check_arap,
                        'trans_has_return' => $check_return
                    );

                    // Final = Delete Journal and Trans
                    if($next){
                        $message = $message.' Delete data';
                        // echo json_encode($return);die;
                        // Set To Journal
                        if($config_post_to_journal==true){
                            $operator = $this->journal_for_transaction('delete',$trans_id);
                            $return->trans_id = 1;
                            $set_data = true;
                        }

                        // $set_data=$this->Transaksi_model->update_transaksi($trans_id,array('trans_flag'=>$flag));
                        // $set_data=$this->Transaksi_model->update_transaksi_item_by_trans_id($trans_id,array('trans_item_flag'=>$flag));
                        // $set_data=$this->Transaksi_model->delete_transaksi($trans_id);
                        // $set_data=$this->Transaksi_model->delete_transaksi_item_by_trans_id(array('trans_item_trans_id'=>$trans_id));
                    }

                    //Save to Activity
                    if($next){
                        if($set_data==true){
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 5,
                                'activity_table' => 'trans',
                                'activity_table_id' => $trans_id,
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $number,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);
                            /* End Activity */
                            $return->status=1;
                            $message='Berhasil menghapus '.$number;
                        }else{
                            $message=$message;
                        }
                    }
                    $return->message=$message;
                    break;
                case "cancel":
                    $set_data=$this->Order_model->reset_order_item($session['user_data']['user_id']);
                    if($set_data==true){
                        /* Start Activity */
                        // $params = array(
                        //     'activity_user_id' => $session_user_id,
                        //     'activity_branch_id' => $session_branch_id,
                        //     'activity_action' => 9,
                        //     'activity_table' => 'orders',
                        //     'activity_table_id' => $set_data,
                        //     'activity_text_1' => $set_transaction,
                        //     'activity_text_2' => $generate_nomor,
                        //     'activity_date_created' => date('YmdHis'),
                        //     'activity_flag' => 1,
                        //     'activity_transaction' => $set_transaction,
                        //     'activity_type' => 2
                        // );
                        // $this->save_activity($params);
                        /* End Activity */

                        $return->status=1;
                        $return->message='Berhasil mereset';
                    }else{
                        $return->message='Tidak ditemukan penghapusan';
                    }
                    break;
                case "journal":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);
                    $id = $this->input->post('id');

                    if($identity == 1){
                        $set_identity = 10;
                    }elseif($identity == 2){
                        $set_identity = 11;
                    }elseif($identity == 6){
                        $set_identity = 14;
                    }elseif($identity == 8){
                        $set_identity = 19;
                    }elseif($identity == 3){
                        $set_identity = 22;
                    }elseif($identity == 4){
                        $set_identity = 23;
                    }elseif($identity == 66){
                        $set_identity = 6;
                    }elseif($identity == 77){
                        $set_identity = 7;
                    }else{
                        $set_identity = null;
                    }
                    $params = array(
                        'journal_item_trans_id' => $id
                    );
                    $params['journal_item_type'] = $set_identity;
                    // var_dump($params);die;
                    $result = array();
                    $datas = $this->Journal_model->get_all_journal_item($params,null,null,null,'journal_item_id','asc');
                    if($datas==true){
                        foreach($datas as $v):

                            $journal_item_note = '-';
                            if($v['journal_item_note'] != null){
                                $journal_item_note = $v['journal_item_note'];
                            }

                            // $journal_item_debit = (floatval($v['journal_item_debit']) > 0) : $v['journal_item_debit'] : '-';
                            // $journal_item_credit = (floatval($v['journal_item_credit']) > 0) : $v['journal_item_credit'] : '-';

                            $result[] = array(
                                'journal_item_group_session' => $v['journal_item_group_session'],
                                'journal_item_journal_id' => $v['journal_item_journal_id'],
                                'journal_item_date' => $v['journal_item_date'],
                                'journal_item_note' => $journal_item_note,
                                'journal_item_debit' => $v['journal_item_debit'],
                                'journal_item_credit' => $v['journal_item_credit'],
                                'account_id' => $v['account_id'],
                                'account_name' => $v['account_name'],
                                'account_code' => $v['account_code'],
                                'account_group' => $v['account_group'],
                                'account_group_sub' => $v['account_group_sub'],
                                'user_username' => $v['user_username']
                            );
                        endforeach;
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 3,
                            'activity_table' => 'journals',
                            'activity_table_id' => $id,
                            'activity_text_1' => $set_transaction,
                            'activity_text_2' => $id,
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);
                        /* End Activity */
                    }

                    //Get Order on Trans Table
                    // $params = array(
                        // 'trans_item_trans_id' => $data['id']
                    // );
                    // $get_trans = $this->Transaksi_model->get_all_transaksi_items_count($params);

                    $return->status = 1;
                    $return->message = 'Success';
                    $return->result = $result;
                    // $return->result_trans = $get_trans;
                    break;
                case "update-label":
                    $id = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : 0;
                    $trans_label = !empty($this->input->post('trans_label')) ? $this->input->post('trans_label') : null;

                    if(strlen(intval($id)) > 0){
                        $get_data = $this->Transaksi_model->get_transaksi($id);
                        
                        $where_type = array(
                            'type_for' => 2,
                            'type_type' => $get_data['trans_type']
                        );
                        $get_type = $this->Type_model->get_type_custom($where_type);
                        
                        $where_label = array(
                            'ref_type' => 9,
                            'ref_name' => $trans_label
                        );
                        $get_label = $this->Referensi_model->get_all_referensi_custom($where_label);
                        // var_dump($get_label);
                        $params = array(
                            'trans_label' => $trans_label,
                        );
                        
                        $set_data = $this->Transaksi_model->update_transaksi($id,$params);
                        if($set_data){

                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 10,
                                'activity_table' => 'trans',
                                'activity_table_id' => $get_data['trans_id'],
                                'activity_text_1' => $trans_label,
                                'activity_text_2' => $get_data['trans_number'],
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $get_type['type_name'],
                                'activity_type' => 2,
                                'activity_icon' => $get_label['ref_note']
                            );
                            $this->save_activity($params);
                            /* End Activity */

                            $return->status  = 1;
                            $return->message = 'Berhasil memperbarui';
                            $return->result = array(
                                'trans_id' => $get_data['trans_id'],
                                'trans_label' => $trans_label 
                            );
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed set label';
                    }
                    break;
                /* CRUD order_items */
                case "create-item":
                    $post_data  = $this->input->post('data');
                    // $data    = base64_decode($post_data);
                    $data       = json_decode($post_data, TRUE);
                    $trans_id   = !empty($data['id']) ? $data['id'] : null;
                    $tipe       = !empty($data['tipe']) ? $data['tipe'] : null;
                    $produk     = !empty($data['produk']) ? $data['produk'] : null;
                    $ppn        = !empty($data['ppn']) ? $data['ppn'] : 0;
                    $satuan     = !empty($data['satuan']) ? $data['satuan'] : null;
                    $harga      = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                    $diskon     = !empty($data['diskon']) ? str_replace(',','',$data['diskon']) : 0.00;
                    $qty        = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                    $qty_kg     = !empty($data['qty_kg']) ? $data['qty_kg'] : 0.00;
                    $qty_pack   = !empty($data['qty_pack']) ? $data['qty_pack'] : 0;
                    $total      = 0;
                    $ref_number = $this->random_code(10);
                    $session_item = $this->random_session(20);
                    $flag       = 0;

                    $product_type   = !empty($data['product_type']) ? intval($data['product_type']) : 1; //1 Barang, 2=Jasa
                    $position       = !empty($data['position']) ? intval($data['position']) : 1;
                    $location       = !empty($data['lokasi']) ? $data['lokasi'] : null;
                    $location_to    = !empty($data['lokasi_tujuan']) ? $data['lokasi_tujuan'] : null;

                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = date('H:i:s');
                    $set_date = $tgl.' '.$jam;
                    $total = $harga*$qty;
                    $next = true;

                    if(intval($data['produk']) > 0){
                        $get_product = $this->Produk_model->get_produk($data['produk']);

                        if(intval($trans_id) > 0){
                            $flag = 1;
                            
                            //Check trans_id Pembelian / Penjualan sdh terbayar ?
                            if(($tipe==1) or ($tipe==2)){
                                $params  = array(
                                    'journal_item_trans_id' => $trans_id,
                                    'journal_item_journal_id >' => 0
                                );
                                $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                if(intval($check_trans_on_journal_item) > 0){
                                    $message    = 'Gagal, Transaksi ini sudah dibayar full/sebagian';
                                    $next = false;
                                }
                            }
                        }

                        if($next){
                            // Ppn Detect
                            $ppn_value = '0.00'; // $ppn = 0;
                            if(floatval($ppn) > 0){
                                $ppn_value = floatval($ppn);
                                $ppn = 1; //true                    
                            }

                            // Prepare Params Insert Trans Items
                            if($tipe == 1){ //Pembelian
                                $params_items = array(
                                    'trans_item_trans_id' => $trans_id,
                                    // 'trans_item_id_order' => $data['order_id'],
                                    // 'trans_item_id_order_detail' => $data['order_detail_id'],
                                    'trans_item_product_id' => $produk,
                                    'trans_item_location_id' => $data['lokasi'],
                                    'trans_item_date' => $set_date,
                                    'trans_item_unit' => $satuan,
                                    'trans_item_in_qty' => $qty,
                                    // 'trans_item_qty_weight' => $qty_kg,
                                    'trans_item_in_price' => $harga,
                                    // 'trans_item_keluar_qty' => $data['qty'],
                                    // 'trans_item_keluar_harga' => $data['harga'],
                                    'trans_item_product_type' => $get_product['product_type'],
                                    'trans_item_type' => $tipe,
                                    'trans_item_discount' => 0,
                                    'trans_item_total' => $total,
                                    'trans_item_date_created' => date("YmdHis"),
                                    'trans_item_date_updated' => date("YmdHis"),
                                    'trans_item_user_id' => $session_user_id,
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_flag' => $flag,
                                    'trans_item_ref' => $ref_number,
                                    'trans_item_ppn' => $ppn,
                                    'trans_item_ppn_value' => $ppn_value,
                                    'trans_item_position' => $position,
                                    'trans_item_pack' => $qty_pack
                                );
                            }else if (($tipe == 2) or ($tipe == 8)){ //2=Penjualan tidak lewat ini, Hanya 8=Produksi
                                $params_items = array(
                                    'trans_item_trans_id' => $trans_id,
                                    // 'trans_item_id_order' => $data['order_id'],
                                    // 'trans_item_id_order_detail' => $data['order_detail_id'],
                                    'trans_item_product_id' => $produk,
                                    // 'trans_item_location_id' => $location,
                                    'trans_item_date' => $set_date,
                                    'trans_item_unit' => $satuan,
                                    // 'trans_item_in_qty' => $qty,
                                    // 'trans_item_in_price' => $harga,
                                    'trans_item_out_qty' => $qty,
                                    // 'trans_item_out_price' => $harga,
                                    'trans_item_sell_price' => $harga,                        
                                    'trans_item_product_type' => $get_product['product_type'],
                                    'trans_item_type' => $tipe,
                                    'trans_item_discount' => $diskon,
                                    'trans_item_total' => $total,
                                    'trans_item_date_created' => date("YmdHis"),
                                    'trans_item_date_updated' => date("YmdHis"),
                                    'trans_item_user_id' => $session_user_id,
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_flag' => $flag,
                                    // 'trans_item_ref' => $ref_number,
                                    'trans_item_ppn' => $ppn,
                                    'trans_item_ppn_value' => $ppn_value,                            
                                    'trans_item_position' => $position
                                );
                            }else if($tipe ==6){ //3=Stok Opname
                                $params_items = array(
                                    'trans_item_trans_id' => $trans_id,
                                    // 'trans_item_id_order' => $data['order_id'],
                                    // 'trans_item_id_order_detail' => $data['order_detail_id'],
                                    'trans_item_product_id' => $produk,
                                    'trans_item_location_id' => $data['lokasi'],
                                    'trans_item_date' => $set_date,
                                    'trans_item_unit' => $satuan,
                                    'trans_item_in_qty' => $qty,
                                    // 'trans_item_qty_weight' => $qty_kg,
                                    'trans_item_in_price' => $harga,
                                    // 'trans_item_keluar_qty' => $data['qty'],
                                    // 'trans_item_keluar_harga' => $data['harga'],
                                    'trans_item_product_type' => $get_product['product_type'],
                                    'trans_item_type' => $tipe,
                                    'trans_item_discount' => 0,
                                    'trans_item_total' => $total,
                                    'trans_item_date_created' => date("YmdHis"),
                                    'trans_item_date_updated' => date("YmdHis"),
                                    'trans_item_user_id' => $session_user_id,
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_flag' => $flag,
                                    'trans_item_ref' => $ref_number,
                                    'trans_item_ppn' => 0,
                                    'trans_item_ppn_value' => 0,                        
                                    'trans_item_position' => $position
                                );
                            }

                            // Check Data Exist Trans Item
                            $params_check = array(
                                'trans_item_type' => $identity,
                                'trans_item_product' => $data['produk']
                            );
                            // $check_exists = $this->Order_model->check_data_exist_item($params_check);
                            $check_exists = false;
                            if($check_exists==false){

                                if($tipe == 1){ // Pembelian insert trans_items
                                    $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                                }else if($tipe == 2){ // Penjualan insert trans_items by CALL PROCEDURE

                                    if(intval($data['lokasi']) > 0){ //Barang FIFO / SP
                                        $type           = $tipe;
                                        $date           = !empty($set_date) ? $set_date : '';
                                        $trans_id       = !empty($data['id']) ? $data['id'] : 0;
                                        $branch_id      = $session_branch_id;
                                        $product_id     = !empty($data['produk']) ? $data['produk'] : null;
                                        $location_id    = !empty($data['lokasi']) ? $data['lokasi'] : null;
                                        $product_unit   = !empty($data['satuan']) ? $data['satuan'] : null;
                                        $out_qty        = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                                        $out_price_sell = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                                        $discount       = !empty($data['diskon']) ? str_replace(',','',$data['diskon']) : 0.00;
                                        // $ppn            = !empty($data['ppn']) ? $data['ppn'] : 0;
                                        $note           = !empty($data['note']) ? $data['note'] : 'A';
                                        $user_id        = $session_user_id;
                                        $flag           = 0;
                                        // $qty_pack       = !empty($data['qty_pack']) ? $data['qty_pack'] : 0;
                                        // var_dump($trans_id,$out_qty,$discount,$out_price_sell);die;
                                        $set_data = $this->trans_item_out($type,$date,$trans_id,$branch_id,$product_id,$location_id,
                                            $product_unit,$out_qty,$out_price_sell,
                                            $discount,$ppn,$ppn_value,$note,$user_id,$flag,$qty_pack);
                                    }else{
                                        $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                                    }
                                }else if($tipe == 5){ // Transfer Stok insert trans_items by CALL PROCEDURE
                                    $type           = $tipe;
                                    $date           = !empty($set_date) ? $set_date : '';
                                    $trans_id       = !empty($data['id']) ? $data['id'] : 0;
                                    $branch_id      = $session_branch_id;
                                    $product_id     = !empty($data['produk']) ? $data['produk'] : null;
                                    $location_id    = !empty($data['lokasi']) ? $data['lokasi'] : null;
                                    $location_to    = !empty($data['lokasi_tujuan']) ? $data['lokasi_tujuan'] : null;
                                    $product_unit   = !empty($data['satuan']) ? $data['satuan'] : null;
                                    $out_qty        = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                                    $out_price_sell = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                                    $discount       = !empty($data['diskon']) ? $data['diskon'] : 0.00;
                                    $ppn            = !empty($data['ppn']) ? $data['ppn'] : 0;
                                    $ppn_value      = '0.00';                            
                                    $note           = !empty($data['note']) ? $data['note'] : 0;
                                    $user_id        = $session_user_id;
                                    $flag           = 0;
                                    $session        = $this->random_code(8);

                                    // var_dump($trans_id);die;
                                    $set_data = $this->trans_item_out_and_in($type,$date,$trans_id,$branch_id,$product_id,$location_id,$location_to,
                                        $product_unit,$out_qty,$out_price_sell,
                                        $discount,$ppn,$ppn_value,$note,$user_id,$flag,$session);
                                    if($set_data){

                                    }
                                }else if ($tipe == 8){ // Produksi
                                    $params_items = array(
                                        'trans_item_trans_id' => $trans_id,
                                        // 'trans_item_id_order' => $data['order_id'],
                                        // 'trans_item_id_order_detail' => $data['order_detail_id'],
                                        'trans_item_product_id' => $produk,
                                        // 'trans_item_location_id' => $location,
                                        'trans_item_date' => $set_date,
                                        'trans_item_unit' => $satuan,
                                        // 'trans_item_in_qty' => $qty,
                                        // 'trans_item_in_price' => $harga,
                                        // 'trans_item_out_qty' => $qty,
                                        // 'trans_item_out_price' => $harga,
                                        'trans_item_product_type' => $get_product['product_type'],
                                        'trans_item_type' => $tipe,
                                        'trans_item_discount' => 0,
                                        'trans_item_total' => $total,
                                        'trans_item_date_created' => date("YmdHis"),
                                        'trans_item_date_updated' => date("YmdHis"),
                                        'trans_item_user_id' => $session_user_id,
                                        'trans_item_branch_id' => $session_branch_id,
                                        'trans_item_flag' => $flag,
                                        // 'trans_item_ref' => $ref_number,
                                        'trans_item_ppn' => $ppn
                                    );

                                    if(intval($position)==1){ //In
                                        $params_items['trans_item_in_qty'] = !empty($qty) ? $qty : '0.00';
                                        $params_items['trans_item_in_price'] = !empty($harga) ? $harga : '0.00';
                                        $params_items['trans_item_ref'] = $ref_number;
                                        $params_items['trans_item_location_id'] = $location;
                                        // var_dump($params_items);die;
                                        $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                                    }elseif(intval($position)==2){ //Out
                                        if($product_type==2){ //Jasa
                                            $params_items['trans_item_out_qty'] = !empty($qty) ? $qty : '0.00';
                                            $params_items['trans_item_out_price'] = !empty($harga) ? $harga : '0.00';
                                            $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                                        }else{ //Bahan/Barang Out BY SP
                                            $type           = $tipe;
                                            $date           = !empty($set_date) ? $set_date : '';
                                            $trans_id       = !empty($data['id']) ? $data['id'] : 0;
                                            $branch_id      = $session_branch_id;
                                            $product_id     = !empty($data['produk']) ? $data['produk'] : null;
                                            $location_id    = !empty($data['lokasi']) ? $data['lokasi'] : null;
                                            $product_unit   = !empty($data['satuan']) ? $data['satuan'] : null;
                                            $out_qty        = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                                            $out_price_sell = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                                            $discount       = !empty($data['diskon']) ? $data['diskon'] : 0.00;
                                            $ppn            = !empty($data['ppn']) ? $data['ppn'] : 0;
                                            $ppn_value      = '0.00';
                                            $note           = !empty($data['note']) ? $data['note'] : 0;
                                            $user_id        = $session_user_id;
                                            $flag           = 0;

                                            $set_data = $this->trans_item_out($type,$date,$trans_id,$branch_id,$product_id,$location_id,
                                                $product_unit,$out_qty,$out_price_sell,
                                                $discount,$ppn,$ppn_value,$note,$user_id,$flag,$qty_pack);
                                            // log_message('debug',$set_data);
                                            // var_dump($type,$date,$trans_id,$branch_id,$product_id,$location_id,
                                                // $product_unit,$out_qty,$out_price_sell,
                                                // $discount,$ppn,$note,$user_id,$flag);
                                            // log_message('debug',$set_data);
                                            // $set_data = ($set_data=1) ? true : false;
                                        }
                                    }
                                }else{
                                    $set_data = $this->Transaksi_model->add_transaksi_item($params_items);
                                }

                                if($set_data){
                                    /* Start Activity */
                                    /*
                                        $params = array(
                                            'activity_user_id' => $session_user_id,
                                            'activity_branch_id' => $session_branch_id,
                                            'activity_action' => 2,
                                            'activity_table' => 'trans_items',
                                            'activity_table_id' => $set_data,
                                            'activity_text_1' => $set_transaction,
                                            'activity_text_2' => $get_product['product_code'].' - '.$get_product['product_name'],
                                            'activity_date_created' => date('YmdHis'),
                                            'activity_flag' => 0,
                                            'activity_transaction' => $set_transaction,
                                            'activity_type' => 2
                                        );
                                        $this->save_activity($params);
                                    */
                                    /* End Activity */
                                    
                                    $get_product = $this->Produk_model->get_produk_quick($data['produk']);

                                    $return->status=1;
                                    $return->message='Menambahkan '.$get_product['product_name'];
                                    $return->result= array(
                                        'id' => $set_data
                                        // 'kode' => $data['kode']
                                    );
                                }

                                /* Command Move to TRIGGER SQL */
                                if(intval($trans_id) > 0){
                                    $return->trans_id = 1;
                                }
                            }else{
                                $return->message='Produk sudah diinput';
                            }
                        }else{
                            $return->message=$message;
                        }
                    }else{
                        $return->message = 'Produk belum dipilih';
                    }
                    break;
                case "read-item":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);
                    $data['id'] = $this->input->post('id');
                    $datas = $this->Order_model->get_order_item($data['id']);
                    if($datas==true){
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 3,
                            'activity_table' => 'order_items',
                            'activity_table_id' => $data['id'],
                            'activity_text_1' => $set_transaction,
                            // 'activity_text_2' => $generate_nomor,
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);
                        /* End Activity */
                        $return->status=1;
                        $return->message='Success';
                        $return->result=$datas;
                    }
                    break;
                case "update-item":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    $next = true;

                    $tipe = !empty($data['tipe']) ? $data['tipe'] : null;
                    $produk = !empty($data['produk']) ? $data['produk'] : null;
                    $satuan = !empty($data['satuan']) ? $data['satuan'] : null;
                    $harga = !empty($data['harga']) ? str_replace(',','',$data['harga']) : 0.00;
                    $diskon = !empty($data['diskon']) ? $data['diskon'] : 0.00;
                    $qty = !empty($data['qty']) ? str_replace(',','',$data['qty']) : 0.00;
                    $qty_kg = !empty($data['qty_kg']) ? $data['qty_kg'] : 0.00;
                    $qty_pack = !empty($data['qty_pack']) ? $data['qty_pack'] : 0;
                    $keterangan = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $ppn = !empty($data['ppn']) ? $data['ppn'] : 0;
                    $total = 0;

                    // if(!empty($)){
                        // $harga = str_replace(',','',$harga);
                        // $qty = str_replace(',','',$qty);
                        $total = $harga*$qty;
                    // }

                    //Ppn Detect
                    $ppn_value = '0.00';
                    if(floatval($ppn) > 0){
                        $ppn_value = floatval($ppn);
                        $ppn = 1; //true                    
                    }

                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */
                    $get_trans = $this->Transaksi_model->get_transaksi_item($id);

                    //Check Trans Item has a Trans
                    if($get_trans){
                        $trans_id = !empty($get_trans['trans_item_trans_id']) ? intval($get_trans['trans_item_trans_id']) : 0;

                        //Check if Trans not in Piutang n Hutang
                        if($trans_id > 0){
                            //Check Trans is Account Payable & Receivable
                            if(($tipe == 1) or ($tipe == 2)){
                                $params = array(
                                    'journal_item_trans_id' => $trans_id
                                );
                                $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                if(intval($check_trans_on_journal_item) > 0){
                                    $return->message='Gagal, Transaksi ini sudah masuk jurnal';
                                    $next=false;
                                }else{
                                    $return->message='Transaksi belum masuk jurnal';
                                }

                                if($next){
                                    $params = array(
                                        'journal_item_trans_id' => $trans_id,
                                        'journal_item_journal_id >' => 0
                                    );
                                    $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                    if(intval($check_trans_on_journal_item) > 0){
                                        $return->message='Gagal, Transaksi ini sudah di bayar';
                                        $next=false;
                                    }{
                                        $return->message='Transaksi belum di bayar';
                                    }
                                }
                            }

                            // var_dump($return->message);die;                        
                        }else{
                            $next=true;
                            $return->message='Item belum masuk transaksi';
                        }

                    }else{
                        $next = false;
                        $return->message='Eror mengambil transaksi';
                    }

                    if($next){
                        $params = array(
                            'trans_item_id' => $id,
                            // 'trans_item_order_id' => $data['order_id'],
                            // 'trans_item_order_item_id' => $data['order_detail_id'],
                            'trans_item_product_id' => $produk,
                            // 'trans_item_location_id' => $data['lokasi_id'],
                            // 'trans_item_date' => date("YmdHis"),
                            'trans_item_unit' => $satuan,
                            'trans_item_in_qty' => $qty,
                            'trans_item_in_price' => $harga,
                            // 'trans_item_discount' => $data['masuk_harga'],
                            'trans_item_total' => $total,
                            'trans_item_note' => $keterangan,
                            // 'trans_item_type' => $data['tipe'],
                            'trans_item_date_updated' => date("YmdHis"),
                            // 'trans_item_flag' => 1,
                            'trans_item_ppn' => $ppn,
                            'trans_item_ppn_value' => $ppn_value,
                            'trans_item_pack' => $qty_pack
                        );
                        $set_update=$this->Transaksi_model->update_transaksi_item($id,$params);


                        $trans_id = $get_trans['trans_item_trans_id'];
                        if(intval($trans_id) > 0){
                            /* Command Move to TRIGGER SQL
                            if(intval($identity) == 1){
                                $params_no_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 0
                                );
                                $params_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 1
                                );
                                $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                                $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                                $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price'];
                                $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                                $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                $params_total = array(
                                    'trans_total' => $set_total
                                );
                            }
                            if(intval($identity) == 2){
                                $params_no_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 0
                                );
                                $params_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 1
                                );
                                $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                                $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_ppn);

                                $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price'];
                                $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];

                                $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                $params_total = array(
                                    'trans_total' => $set_total
                                );
                            }
                            $update_trans = $this->Transaksi_model->update_transaksi($trans_id,$params_total);
                            */
                            $return->trans_id = 1;
                        }

                        if($set_update==true){
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'trans_items',
                                'activity_table_id' => $id,
                                'activity_text_1' => $set_transaction,
                                // 'activity_text_2' => $generate_nomor,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);
                            /* End Activity */
                            $return->status=1;
                            $return->message='Success';
                        }
                    }
                    break;
                case "delete-item":
                    $trans_item_id      = $this->input->post('id');
                    $order_id           = $this->input->post('order_id');
                    $kode               = $this->input->post('kode');
                    $nama               = $this->input->post('nama');
                    $flag               = $this->input->post('flag');
                    $next               = true;
                    $message            = 'Gagal menghapus';

                    if(intval($trans_item_id) > 0){
                        if($flag==1){
                            $msg='aktifkan transaksi '.$nama;
                            $act=7;
                        }else{
                            $msg='nonaktifkan transaksi '.$nama;
                            $act=8;
                        }

                        // $set_data=$this->Order_model->update_order_item($id,array('order_item_flag'=>0));
                        $get_trans = $this->Transaksi_model->get_transaksi_item($trans_item_id);
                        $trans_id = $get_trans['trans_item_trans_id'];
                        $trans_type = $get_trans['trans_item_type'];

                        if(intval($trans_id) > 0){
                            /* Command move to TRIGGER SQL
                            if(intval($identity) == 1){
                                $params_no_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 0
                                );
                                $params_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 1
                                );
                                $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                                $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                                $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price'];
                                $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                                $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                $params_total = array(
                                    'trans_total' => $set_total
                                );
                            }
                            if(intval($identity) == 2){
                                $params_no_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 0
                                );
                                $params_ppn = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_ppn' => 1
                                );
                                $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_no_ppn);
                                $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_out_price_total($trans_id,$params_ppn);

                                $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_out_price'];
                                $total_ppn = $get_total_trans_item_ppn['trans_item_out_price'];

                                $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                                $params_total = array(
                                    'trans_total' => $set_total
                                );
                            }
                            $update_trans = $this->Transaksi_model->update_transaksi($trans_id,$params_total);
                            */

                            //Check Stock is Available to Delete
                            $set_trans_item_position = ($get_trans['trans_item_position']==1) ? 2 : 1;
                            if(intval($set_trans_item_position) == 2){
                                $params_check = array(
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_ref' => $get_trans['trans_item_ref'],
                                    'trans_item_position' => $set_trans_item_position,
                                    // 'trans_item_flag' => 1
                                );
                                $check = $this->Transaksi_model->check_stock_is_available_to_delete($params_check);
                                if($check > 0){
                                    $message='Gagal dihapus, Stok sudah digunakan';
                                    $next=false;                           
                                    $return->result_item = $this->Transaksi_model->get_transaksi_item_custom($params_check);
                                }
                            }

                            if($next){
                                //Check Trans is Account Payable & Receivable
                                if(($trans_type == 1) or ($trans_type == 2)){
                                    $params = array(
                                        'journal_item_trans_id' => $trans_id
                                    );

                                    if($trans_type==1){
                                        $params['journal_item_type'] = 1;
                                        $params['journal_item_debit > '] = '0';
                                    }else if($trans_type==2){
                                        $params['journal_item_type'] = 2;
                                        $params['journal_item_credit > '] = '0';                                
                                    }                            
                                    // var_dump($params);
                                    $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                    if(intval($check_trans_on_journal_item) > 0){
                                        $message='Gagal, Transaksi ini sudah dibayar';

                                        //Params Cek Piutang / Hutang Sudah Lunas Belum
                                        if($identity == 1){
                                            $params = array(
                                                'journal_item_trans_id' => $trans_id,
                                                'journal_item_type' => 1,
                                                'journal_item_debit > ' => 0,
                                            );
                                        }else if ($identity == 2){
                                            $params = array(
                                                'journal_item_trans_id' => $trans_id,
                                                'journal_item_type' => 2,
                                                'journal_item_credit > ' => 0,
                                            );
                                        }
                                        $return->result_item = $this->Journal_model->get_all_journal_item_custom($params);
                                        $return->status = 4;                 
                                                    
                                        $next=false;
                                    }

                                    /*
                                    if($next){
                                        $params = array(
                                            'journal_item_trans_id' => $trans_id,
                                            'journal_item_journal_id >' => 0
                                        );
                                        $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                        if(intval($check_trans_on_journal_item) > 0){
                                            $message='Gagal, Transaksi ini sudah di bayar';
                                            $next=false;
                                        }
                                    }
                                    */
                                }
                            }

                            // var_dump($message);die;
                            if($next){
                                $params_count = array('trans_item_trans_id' => $trans_id);
                                $trans_count = $this->Transaksi_model->get_all_transaksi_items_count($params_count);
                                if(intval($trans_count) > 1){
                                    $delete_data=$this->Transaksi_model->delete_transaksi_item($trans_item_id);
                                }else{
                                    $next = false;
                                    $message = 'Hapus dapat dilakukan satu nota';
                                }
                            }
                            $return->trans_id = intval($trans_id);
                            $return->trans_item_id = $trans_item_id;
                        }else{
                            $delete_data=$this->Transaksi_model->delete_transaksi_item($trans_item_id);
                        }

                        if($next){
                            /* Start Activity */
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 5,
                                'activity_table' => 'trans_items',
                                'activity_table_id' => $trans_item_id,
                                'activity_text_1' => $set_transaction,
                                'activity_text_2' => $kode.' -  '.$nama,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 1,
                                'activity_transaction' => $set_transaction,
                                'activity_type' => 2
                            );
                            $this->save_activity($params);
                            /* End Activity */
                            $return->status=1;
                            $message='Berhasil menghapus '.$nama;
                            $return->result = array(
                                'trans_item_id' => $trans_item_id,
                                'trans_id' => $trans_id
                            );
                        }else{
                            $message=$message;
                            $return->result = array(
                                'trans_item_id' => $trans_item_id,
                                'trans_id' => $trans_id
                            );
                        }
                    }else{
                        $return->message="Data tidak ditemukan";
                    }
                    $return->message=$message;
                    break;
                case "delete-item-session": //For Transfer Stok
                    $return_id = [];
                    $id = $this->input->post('id');
                    $order_id = $this->input->post('order_id');
                    $kode = $this->input->post('kode');
                    $nama = $this->input->post('nama');
                    $flag = $this->input->post('flag');
                    $session = $this->input->post('session');

                    $next=true;
                    $message = 'Gagal menghapus';

                    if($flag==1){
                        $msg='aktifkan transaksi '.$nama;
                        $act=7;
                    }else{
                        $msg='nonaktifkan transaksi '.$nama;
                        $act=8;
                    }

                    // $set_data=$this->Order_model->update_order_item($id,array('order_item_flag'=>0));
                    $where = array(
                        'trans_item_session' => $session
                    );
                    $get_trans = $this->Transaksi_model->get_transaksi_item_custom($where);
                    // var_dump($get_trans);die;
                    foreach($get_trans as $v => $a):
                        // var_dump($get_trans[$v]['trans_item_id']);die;
                        $trans_id = $get_trans[$v]['trans_item_trans_id'];
                        $trans_type = $get_trans[$v]['trans_item_type'];

                        if(intval($trans_id) > 0){

                            //Check Stock is Available to Delete
                            $set_trans_item_position = ($get_trans[$v]['trans_item_position']==1) ? 2 : 1;
                            // $set_trans_item_position = 2;
                            if(intval($set_trans_item_position) == 2){
                                $params_check = array(
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_ref' => $get_trans[$v]['trans_item_ref'],
                                    'trans_item_position' => $set_trans_item_position,
                                    'trans_item_date >' => $get_trans[$v]['trans_item_date'],
                                    'trans_item_location_id' => $get_trans[$v]['trans_item_location_id']
                                    // 'trans_item_flag' => 1
                                );
                                $check = $this->Transaksi_model->check_stock_is_available_to_delete($params_check);
                                if($check > 0){
                                    $return->params_check = $params_check;
                                    $message='Gagal dihapus, Stok sudah digunakan';
                                    $next=false;
                                }
                            }

                            if($next){
                                //Check Trans is Account Payable & Receivable
                                if(($trans_type == 1) or ($trans_type == 2)){
                                    $params = array(
                                        'journal_item_trans_id' => $trans_id
                                    );
                                    $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                    if(intval($check_trans_on_journal_item) > 0){
                                        $message='Gagal, Transaksi ini sudah masuk jurnal';
                                        $next=false;
                                    }

                                    if($next){
                                        $params = array(
                                            'journal_item_trans_id' => $trans_id,
                                            'journal_item_journal_id >' => 0
                                        );
                                        $check_trans_on_journal_item = $this->Journal_model->get_all_journal_item_count($params);
                                        if(intval($check_trans_on_journal_item) > 0){
                                            $message='Gagal, Transaksi ini sudah di bayar';
                                            $next=false;
                                        }
                                    }
                                }
                            }

                            if($next){
                                $params_count = array(
                                    'trans_item_trans_id' => $trans_id,
                                    'trans_item_session' => $get_trans[$v]['trans_item_session'],
                                    'trans_item_type' => $trans_type,
                                    'trans_item_position' => $get_trans[$v]['trans_item_position']
                                );
                                $trans_count = $this->Transaksi_model->get_all_transaksi_items_count($params_count);
                                // var_dump($trans_count);die;
                                if(intval($trans_count) > 0){
                                    $trans_item_id = $get_trans[$v]['trans_item_id'];
                                    $delete_data=$this->Transaksi_model->delete_transaksi_item($trans_item_id);
                                    // var_dump(22);
                                }else{
                                    $next = false;
                                    $message = 'Hapus dapat dilakukan satu nota';
                                }
                            }

                            $return->trans_id = intval($trans_id);
                        }else{
                            $where = array(
                                'trans_item_id' => $get_trans[$v]['trans_item_id'],
                                'trans_item_session' => $session,
                                'trans_item_branch_id' => intval($session_branch_id)
                            );
                            $delete_data=$this->Transaksi_model->delete_transaksi_item_custom($where);
                        }

                        $return_id[] = $get_trans[$v]['trans_item_id'];
                    endforeach;

                    if($next){
                        /* Start Activity */
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 5,
                            'activity_table' => 'order_items',
                            'activity_table_id' => $id,
                            'activity_text_1' => $set_transaction,
                            'activity_text_2' => $kode.' -  '.$nama,
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $set_transaction,
                            'activity_type' => 2
                        );
                        $this->save_activity($params);
                        /* End Activity */
                        $return->status=1;
                        $message='Berhasil menghapus '.$nama;
                        $return->result = array(
                            'trans_item_id' => $id
                        );
                    }else{
                        $message=$message;
                        $return->result = array(
                            'trans_item_id' => $id
                        );
                    }

                    $return->message = $message;
                    $return->return_d = $return_id;
                    break;
                case "load-item":
                    $trans_id = $this->input->post('id');
                    $limit = $this->input->post('length');
                    $start = $this->input->post('start');
                    $order = $columns[$this->input->post('order')[0]['column']];
                    $dir = $this->input->post('order')[0]['dir'];

                    $search = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    /*
                    if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                        $params['other_column'] = $this->input->post('other_column');
                    }
                    */
                    $params_datatable = array(
                        'orders.order_id' => $trans_id,
                        'orders.order_branch_id' => $session_branch_id
                    );
                    $datas = $this->Transaksi_model->get_all_transaksi_items($params_datatable, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Transaksi_model->get_all_transaksi_items_count($params_datatable);
                    // $datas_count = $this->Order_model->get_all_orders_count($params_datatable, $search, $limit, $start, $order, $dir);

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
                    break;
                case "update-item-qty":
                    $id = !empty($this->input->post('trans_item_id')) ? $this->input->post('trans_item_id') : 0;
                    $trans_item_qty = $this->input->post('trans_item_qty');

                    if(strlen(intval($id)) > 0){
                        $get_data = $this->Transaksi_model->get_transaksi_item($id);
                        $next = true;

                        if($get_data['trans_item_position'] == 1){ //In
                            $trans_item_total = ($get_data['trans_item_in_price'] * $trans_item_qty) - $get_data['trans_item_discount'];                        
                            $params = array(
                                'trans_item_in_qty' => $trans_item_qty,
                                'trans_item_total' => $trans_item_total
                            );                        
                        }else if($get_data['trans_item_position'] == 2){ //Out

                            $trans_item_sell_total = ($get_data['trans_item_sell_price'] * $trans_item_qty) - $get_data['trans_item_discount'];
                            $params = array(
                                'trans_item_out_qty' => $trans_item_qty,
                                'trans_item_sell_total' => $trans_item_sell_total
                            );

                            if(floatval($trans_item_qty) >= $get_data['trans_item_out_qty']){
                                $next=false;
                                $return->message = 'Qty tidak boleh lebih besar dari sebelumnya';
                            }
                        }

                        if($next){
                            $set_data = $this->Transaksi_model->update_transaksi_item($id,$params);
                            if($set_data){
                                $return->status  = 1;
                                $return->message = 'Memperbarui qty '.$get_data['product_name'];
                                $return->result = array(
                                    'trans_id' => $get_data['trans_item_trans_id'],
                                    'trans_item_id' => $get_data['trans_item_id'] 
                                );
                            }
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed checked';
                    }
                    break;
                case "update-item-price":
                    $id = !empty($this->input->post('trans_item_id')) ? $this->input->post('trans_item_id') : 0;
                    $trans_item_price = $this->input->post('trans_item_price');

                    if(strlen(intval($id)) > 0){
                        $get_data = $this->Transaksi_model->get_transaksi_item($id);
                        $next = true;

                        if($get_data['trans_item_position'] == 1){ //In
                            $trans_item_total = ($trans_item_price * $get_data['trans_item_in_qty']) - $get_data['trans_item_discount'];                        
                            $params = array(
                                'trans_item_in_price' => $trans_item_price,
                                'trans_item_total' => $trans_item_total
                            );
                        }else if($get_data['trans_item_position'] == 2){ //Out

                            $trans_item_sell_total = ($trans_item_price * $get_data['trans_item_out_qty']) - $get_data['trans_item_discount'];
                            $params = array(
                                'trans_item_sell_price' => $trans_item_price,
                                'trans_item_sell_total' => $trans_item_sell_total
                            );

                            // if(floatval($trans_item_qty) >= $get_data['trans_item_out_qty']){
                            //     $next=false;
                            //     $return->message = 'Qty tidak boleh lebih besar dari sebelumnya';
                            // }
                        }

                        if($next){
                            $set_data = $this->Transaksi_model->update_transaksi_item($id,$params);
                            if($set_data){
                                $return->status  = 1;
                                $return->message = 'Memperbarui harga '.$get_data['product_name'];
                                $return->result = array(
                                    'trans_id' => $get_data['trans_item_trans_id'],
                                    'trans_item_id' => $get_data['trans_item_id'] 
                                );
                            }
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed checked';
                    }
                    break;           
                case "update-item-pack":
                    $id = !empty($this->input->post('trans_item_id')) ? $this->input->post('trans_item_id') : 0;
                    $trans_item_pack = $this->input->post('trans_item_pack');
                    $trans_item_pack_unit = $this->input->post('trans_item_pack_unit');

                    if(strlen(intval($id)) > 0){
                        $get_data = $this->Transaksi_model->get_transaksi_item($id);
                        
                        $params = array(
                            'trans_item_pack' => $trans_item_pack,
                            'trans_item_pack_unit' => $trans_item_pack_unit
                        );
                        
                        $set_data = $this->Transaksi_model->update_transaksi_item($id,$params);
                        if($set_data){
                            $return->status  = 1;
                            $return->message = 'Berhasil memperbarui';
                            $return->result = array(
                                'trans_id' => $get_data['trans_item_trans_id'],
                                'trans_item_id' => $get_data['trans_item_id'] 
                            );
                        }
                    }else{
                        $return->status  = 0;
                        $return->message = 'Failed checked';
                    }
                    break;

                /* OTHER trans and trans_items */
                case "load-trans-items":
                    $trans_id = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : 0;
                    $subtotal = 0;
                    $total_diskon = 0;
                    $total_ppn = 0;
                    $total= 0;
                    $params = array();

                    if(intval($trans_id) > 0){
                        $params = array(
                            'trans_item_trans_id' => intval($trans_id),
                            'trans_item_branch_id' => intval($session_branch_id),
                        );
                        $get_trans = $this->Transaksi_model->get_transaksi($trans_id);
                        $total_diskon = $get_trans['trans_discount'];
                        $product_type = !empty($this->input->post('product_type')) ? $params['trans_item_product_type'] = intval($this->input->post('product_type')) : 0;

                        if($identity==8){ //Produksi
                            $position = !empty($this->input->post('position')) ? intval($this->input->post('position')) : 2; // 1=In, 2=Out
                            // if($position==1){
                            //     $params['trans_item_position'] = 1;
                            // }else{
                            //     $params['trans_item_position'] = 2;
                            // }
                            $params['trans_item_position'] = ($position==1) ? 1 : 2;
                        }
                        if($identity==5){ //Transfer Stok
                            $position = !empty($this->input->post('position')) ? intval($this->input->post('position')) : 2; // 1=In, 2=Out
                            // if($position==1){
                            //     $params['trans_item_position'] = 1;
                            // }else{
                            //     $params['trans_item_position'] = 2;
                            // }
                            $params['trans_item_position'] = ($position==1) ? 1 : 2;
                        }

                        // var_dump($params);die;
                        $return->params = $params;
                        $get_data = $this->Transaksi_model->get_all_transaksi_items($params,null,null,null,null);

                        $return->message='Berhasil memuat data '.$get_trans['trans_number'];
                    }else{
                        $product_type = !empty($this->input->post('product_type')) ? intval($this->input->post('product_type')) : 0;
                        $position = !empty($this->input->post('position')) ? intval($this->input->post('position')) : 1;
                        $get_data = $this->Transaksi_model->check_unsaved_transaksi_item($identity,$session_user_id,$session_branch_id,$product_type,$position);
                    
                        $return->message='Terdapat data yang belum disimpan';
                    }

                    // var_dump($get_data);die;
                    if(!empty($get_data)){
                        $total_qty_in = 0;
                        $total_qty_out = 0;
                        $total_diskon_baris = 0;
                        foreach($get_data as $v){

                            $get_product_price = $this->Product_price_model->get_all_product_price(array('product_price_product_id'=>$v['product_id']),null,null,null,null,null);
                            $product_price_list = array();
                            foreach($get_product_price as $pp){
                                $product_price_list[] = array(
                                    'product_price_id' => $pp['product_price_id'],
                                    'product_price_product_id' => $pp['product_price_product_id'],
                                    'product_price_name' => $pp['product_price_name'],
                                    'product_price_price' => $pp['product_price_price']
                                );
                            }
                            
                            //If Transfer Stok
                            $location_froms = array();
                            if($identity==5){
                                $w = array(
                                    // 'trans_item_flag' => 0,
                                    'trans_item_type' => 5,
                                    'trans_item_position' => 1,
                                    'trans_item_ref' => $v['trans_item_ref']
                                );
                                $get_location_froms = $this->Transaksi_model->get_transaksi_item_custom($w);
                                if($get_location_froms){
                                    $location_froms = array(
                                        'location_id' => !empty($get_location_froms[0]['location_id']) ? $get_location_froms[0]['location_id'] : null,
                                        'location_code' => !empty($get_location_froms[0]['location_code']) ? $get_location_froms[0]['location_code'] : null,
                                        'location_name' => !empty($get_location_froms[0]['location_name']) ? $get_location_froms[0]['location_name'] : null
                                    );
                                }
                            }

                            $datas[] = array(
                                'trans_item_id' => $v['trans_item_id'],
                                'trans_item_order_id' => $v['trans_item_order_id'],
                                'trans_item_unit' => !empty($v['trans_item_unit']) ? $v['trans_item_unit'] : '-',
                                // 'trans_item_qty_weight' => number_format($v['trans_item_qty_weight'],2,'.',','),
                                'trans_item_in_qty' => number_format($v['trans_item_in_qty'],2,'.',','),
                                'trans_item_in_price' => number_format($v['trans_item_in_price'],2,'.',','),
                                'trans_item_out_qty' => number_format($v['trans_item_out_qty'],2,'.',','),
                                'trans_item_out_price' => number_format($v['trans_item_out_price'],2,'.',','),
                                'trans_item_sell_price' => number_format($v['trans_item_sell_price'],2,'.',','),
                                'trans_item_discount' => number_format($v['trans_item_discount'],2,'.',','),
                                'trans_item_total' => number_format($v['trans_item_total'],2,'.',','),
                                'trans_item_total_raw' => $v['trans_item_total'],
                                'trans_item_sell_total' => number_format($v['trans_item_sell_total'],2,'.',','),
                                'trans_item_total_after_discount' => number_format($v['trans_item_total'],2,'.',','),
                                'trans_item_pack' => !empty($v['trans_item_pack']) ? number_format($v['trans_item_pack'],0) : 0,
                                'trans_item_note' => $v['trans_item_note'],
                                // 'trans_item_product_price_id' => $v['trans_item_product_price_id'],
                                'trans_item_user_id' => $v['trans_item_user_id'],
                                'trans_item_branch_id' => $v['trans_item_branch_id'],
                                'trans_item_ppn' => $v['trans_item_ppn'],
                                'trans_item_ppn_value' => number_format($v['trans_item_ppn_value'],0),
                                'trans_item_session' => $v['trans_item_session'],
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'has_other_price' => $product_price_list,
                                'location' => array(
                                    'location_id' => !empty($v['location_id']) ? $v['location_id'] : '-',
                                    'location_code' => !empty($v['location_code']) ? $v['location_code'] : '-',
                                    'location_name' => !empty($v['location_name']) ? $v['location_name'] : '-'
                                ),
                                'location_transfer_to' => $location_froms,
                                'trans_item_pack_unit' => !empty($v['trans_item_pack_unit']) ? $v['trans_item_pack_unit'] : 'Koli'
                            );

                            $total_qty_in = $total_qty_in + $v['trans_item_in_qty'];
                            $total_qty_out = $total_qty_out + $v['trans_item_out_qty'];
                            $total_diskon_baris = $total_diskon_baris + $v['trans_item_discount'];

                            if($identity==2){ //Penjualan
                                $subtotal=$subtotal+$v['trans_item_sell_total'];
                                if($v['trans_item_ppn'] == 1){
                                    $total_ppn = $total_ppn + ($v['trans_item_sell_total']* ($v['trans_item_ppn_value'] / 100));
                                }
                            }else{
                                $subtotal=$subtotal+$v['trans_item_total'];
                                if($v['trans_item_ppn'] == 1){
                                    $total_ppn = $total_ppn + ($v['trans_item_total']*($v['trans_item_ppn_value'] / 100));
                                }
                            }
                        }
                        //End Foreach
                        $subtotal = $subtotal + $total_diskon_baris;

                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 3,
                            'table' => 'transaksi',
                            'table_id' => $datas['id'],
                            'text_1' => strtoupper($datas['kode']),
                            'text_2' => ucwords(strtolower($datas['username'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */

                        //Split Discount from Trans or Items Trans
                        if(floatVal($total_diskon) !== floatVal($total_diskon_baris)){
                            // $total_diskon = $total_diskon_baris - $total_diskon;
                            $total_diskon = $total_diskon;
                            $total_diskon_baris = $total_diskon_baris;
                            if($total_diskon > 0){
                                $total_diskon = $total_diskon - $total_diskon_baris;
                            }
                        }else{
                            $total_diskon = 0;
                            $total_diskon_baris = $total_diskon_baris;
                        }

                        if(isset($datas)){ //Data exist
                            $data_source=$datas; 
                            $total=count($datas);
                            
                            $return->status=1; 
                            $return->total_records =$total;
                            $return->result =$datas;
                            $return->total_produk=count($datas);

                            $return->subtotal           = number_format($subtotal,0,'.',',');
                            $return->total_diskon_baris = number_format($total_diskon_baris,0,'.',',');
                            $return->total_diskon       = number_format($total_diskon,0,'.',',');
                            $return->total_ppn          = number_format($total_ppn,0,'.',',');
                            $return->total              = number_format(($subtotal+$total_ppn)-$total_diskon-$total_diskon_baris,0,'.',',');
                        }else{
                            $data_source=0; $total=0;
                            $return->status=0; 
                            $return->total_records=$total;
                            $return->result=0;
                        }
                        $return->qty_in = $total_qty_in;
                        $return->qty_out = $total_qty_out;

                        $return->status=1;
                        if(intval($trans_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada item temporary';
                        if(intval($trans_id) > 0){
                            $return->message = '-';
                        }
                    }
                    break;
                case "load-trans-items-for-report":
                    $trans_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : 0;
                    $contact_id = !empty($this->input->post('kontak')) ? $this->input->post('kontak') : 0;
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $sales_id = !empty($this->input->post('sales')) ? $this->input->post('sales') : 0;                    
                    $product = !empty($this->input->post('product')) ? $this->input->post('product') : 0;    

                    $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));                    
                    // $subtotal = 0;
                    // $total_diskon = 0;
                    // $total_ppn = 0;
                    // $total= 0;

                    $params = array(
                        'trans_item_type' => intval($trans_type),
                        'trans_item_branch_id' => intval($session_branch_id),
                        'trans_date >' => $date_start,
                        'trans_date <' => $date_end,                         
                    );
                    if(intval($contact_id) > 0){
                        $params['trans_contact_id'] = intval($contact_id);
                    }
                    if(intval($sales_id) > 0){
                        $params['trans_sales_id'] = intval($sales_id);
                    }                    
                    if(intval($product_id) > 0){
                        $params['trans_item_product_id'] = intval($product_id);
                    }

                    if(intval($product) > 0){
                        $params['trans_item_product_id'] = intval($product);
                    }     

                    $search = null;
                    $limit = 1000;
                    $start = 0;
                    $order = 'trans_item_date'; 
                    $dir = 'asc';          

                    // var_dump($params);die;
                    // $get_trans = $this->Transaksi_model->get_transaksi($trans_id);
                    // $total_diskon = $get_trans['trans_discount'];
                    // $product_type = !empty($this->input->post('product_type')) ? $params['trans_item_product_type'] = intval($this->input->post('product_type')) : 0;
                    $datas = $this->Transaksi_model->get_all_transaksi_items_report($params,$search,$limit,$start,$order,$dir);

                    if(!empty($datas)){
                        // foreach($get_data as $v){

                        //     $get_product_price = $this->Product_price_model->get_all_product_price(array('product_price_product_id'=>$v['product_id']),null,null,null,null,null);
                        //     $product_price_list = array();
                        //     foreach($get_product_price as $pp){
                        //         $product_price_list[] = array(
                        //             'product_price_id' => $pp['product_price_id'],
                        //             'product_price_product_id' => $pp['product_price_product_id'],
                        //             'product_price_name' => $pp['product_price_name'],
                        //             'product_price_price' => $pp['product_price_price']
                        //         );
                        //     }

                        //     $datas[] = array(
                        //         'trans_item_id' => $v['trans_item_id'],
                        //         'trans_item_order_id' => $v['trans_item_order_id'],
                        //         'trans_item_unit' => $v['trans_item_unit'],
                        //         // 'trans_item_qty_weight' => number_format($v['trans_item_qty_weight'],2,'.',','),
                        //         'trans_item_in_qty' => number_format($v['trans_item_in_qty'],2,'.',','),
                        //         'trans_item_in_price' => number_format($v['trans_item_in_price'],2,'.',','),
                        //         'trans_item_out_qty' => number_format($v['trans_item_out_qty'],2,'.',','),
                        //         'trans_item_out_price' => number_format($v['trans_item_out_price'],2,'.',','),
                        //         'trans_item_sell_price' => number_format($v['trans_item_sell_price'],2,'.',','),
                        //         'trans_item_discount' => number_format($v['trans_item_discount'],2,'.',','),
                        //         'trans_item_total' => number_format($v['trans_item_total'],2,'.',','),
                        //         'trans_item_sell_total' => number_format($v['trans_item_sell_total'],2,'.',','),
                        //         'trans_item_total_after_discount' => number_format($v['trans_item_total'],2,'.',','),
                        //         'trans_item_note' => $v['trans_item_note'],
                        //         // 'trans_item_product_price_id' => $v['trans_item_product_price_id'],
                        //         'trans_item_user_id' => $v['trans_item_user_id'],
                        //         'trans_item_branch_id' => $v['trans_item_branch_id'],
                        //         'trans_item_ppn' => $v['trans_item_ppn'],
                        //         'product_id' => $v['product_id'],
                        //         'product_code' => $v['product_code'],
                        //         'product_name' => $v['product_name'],
                        //         'has_other_price' => $product_price_list
                        //     );

                        //     if($identity==2){ //Penjualan
                        //         $subtotal=$subtotal+$v['trans_item_sell_total'];
                        //         if($v['trans_item_ppn'] == 1){
                        //             $total_ppn = $total_ppn + ($v['trans_item_sell_total']*0.1);
                        //         }
                        //     }else{
                        //         $subtotal=$subtotal+$v['trans_item_total'];
                        //         if($v['trans_item_ppn'] == 1){
                        //             $total_ppn = $total_ppn + ($v['trans_item_total']*0.1);
                        //         }
                        //     }
                        // }
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 3,
                            'table' => 'transaksi',
                            'table_id' => $datas['id'],
                            'text_1' => strtoupper($datas['kode']),
                            'text_2' => ucwords(strtolower($datas['username'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas;
                            // $return->total_produk=count($datas);
                            // $return->subtotal=number_format($subtotal,0,'.',',');
                            // $return->total_diskon=number_format($total_diskon,0,'.',',');
                            // $return->total_ppn=number_format($total_ppn,0,'.',',');
                            // $return->total=number_format(($subtotal+$total_ppn)-$total_diskon,0,'.',',');
                        }else{
                            $data_source=0; $total=0;
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->result=0;
                        }
                        $return->status=1;
                        $return->message='Data ditemukan';
                        $return->params = $params;
                        // if(intval($trans_id) > 0){
                            // $return->message = 'Berhasil memuat data';
                        // }
                    }else{
                        $total=0;
                        $return->message='Tidak ada item temporary';
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    break;
                case "load-trans-items-for-report-production":
                    $trans_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : 0;
                    // $contact_id = !empty($this->input->post('kontak')) ? $this->input->post('kontak') : 0;
                    $location_id = !empty($this->input->post('location')) ? $this->input->post('location') : 0;                
                    $product_id = !empty($this->input->post('product')) ? $this->input->post('product') : 0;
                    $position = !empty($this->input->post('position')) ? $this->input->post('position') : 2;

                    $params = array(
                        'trans_item_type' => $trans_type,
                        'trans_item_branch_id' => $session_branch_id
                    );
                    // if(intval($contact_id) > 0){
                    //     $params['trans_contact_id'] = $contact_id;
                    // }
                    if(intval($location_id) > 0){
                        $params['trans_item_location_id'] = $location_id;
                    }                
                    if(intval($product_id) > 0){
                        $params['trans_item_product_id'] = $product_id;
                    }

                    if(intval($position) > 0){
                        $params['trans_item_position'] = $position;
                    }

                    $date_start = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));

                    $params['trans_item_date >'] = $date_start;
                    $params['trans_item_date <'] = $date_end;

                    $datas = $this->Transaksi_model->get_all_transaksi_items_report($params,null,null,null,null);
                    // var_dump($params);
                    if(!empty($datas)){
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas;
                        }else{
                            $data_source=0; $total=0;
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->result=0;
                        }
                        $return->status=1;
                        $return->message='Data ditemukan';
                        $return->params = $params;
                    }else{
                        $total=0;
                        $return->message='Tidak ada item temporary';
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    break;
                case "create-item-addon":
                    $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    $data = json_decode($post_data, TRUE);

                    $harga = str_replace(',','', $data['harga']);
                    $qty = str_replace(',','', $data['qty']);
                    $total = $harga*$qty;
                    $params_items = array(
                        'order_item_order_id' => $data['order_id'],
                        // 'order_item_id_order_detail' => $data['order_detail_id'],
                        'order_item_product_id' => $data['produk'],
                        // 'order_item_id_lokasi' => $data['lokasi_id'],
                        'order_item_date' => date("YmdHis"),
                        'order_item_unit' => $data['satuan'],
                        'order_item_qty' => $qty,
                        'order_item_price' => $harga,
                        // 'order_item_keluar_qty' => $data['qty'],
                        // 'order_item_keluar_harga' => $data['harga'],
                        'order_item_type' => $data['tipe'],
                        'order_item_discount' => 0,
                        'order_item_total' => $total,
                        'order_item_date_created' => date("YmdHis"),
                        'order_item_date_updated' => date("YmdHis"),
                        'order_item_user_id' => $session_user_id,
                        'order_item_branch_id' => $session_branch_id,
                        'order_item_flag' => 0
                    );

                    //Check Data Exist Trans Item
                    // $params_check = array(
                        // 'order_item_type' => $identity,
                        // 'order_item_product' => $data['produk']
                    // );
                    // $check_exists = $this->Order_model->check_data_exist_item($params_check);
                    // $check_exists = false;
                    // if($check_exists==false){

                        $set_data=$this->Order_model->add_order_item($params_items);
                        if($set_data==true){
                            /* Start Activity */
                            /*
                            $params = array(
                                'id_user' => $session['user_data']['user_id'],
                                'action' => 2,
                                'table' => 'transaksi',
                                'table_id' => $set_data,
                                'text_1' => strtoupper($data['kode']),
                                'text_2' => ucwords(strtolower($data['nama'])),
                                'date_created' => date('YmdHis'),
                                'flag' => 1
                            );
                            $this->save_activity($params);
                            */
                            /* End Activity */
                            $return->status=1;
                            $return->message='Success';
                            $return->result= array(
                                'id' => $set_data,
                                'order_id' => $data['order_id']
                            );
                        }else{
                            $return->message='Gagal menambahkan produk tambahan';
                        }
                    // }else{
                        // $return->message='Produk sudah diinput';
                    // }
                    break;
                case "create-item-note":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    $params = array(
                        'order_item_note' => $data['note'],
                    );
                    $set_update=$this->Order_model->update_order_item($id,$params);
                    if($set_update==true){
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 4,
                            'table' => 'transaksi',
                            'table_id' => ,
                            'text_1' => strtoupper($data['kode']),
                            'text_2' => ucwords(strtolower($data['nama'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        */
                        // $this->save_activity($params);
                        /* End Activity */
                        $return->status=1;
                        $return->message='Success';
                    }
                    break;
                case "create-item-plus-minus":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    $operator = $data['operator'];
                    $qty= $data['qty'];
                    $price = $data['price'];
                    $discount = $data['discount'];

                    if($operator=='increase'){
                        $set_qty = floatVal($qty)+1;
                        $set_message = 'di tambahkan lagi';
                    }else if($operator=='decrease'){
                        $set_qty = floatVal($qty)-1;
                        $set_message = 'di kurangi lagi';
                    }

                    if($set_qty == 0){
                        $return->message='Tidak bisa '.$set_message;
                    }else{
                        $set_price = $price;
                        $set_discount = $discount;
                        $set_total = ($set_price*$set_qty)-$discount;
                        $params = array(
                            'order_item_qty' => $set_qty,
                            'order_item_price' => $set_price,
                            'order_item_discount' => $set_discount,
                            'order_item_total' => $set_total,
                        );
                        $set_update=$this->Order_model->update_order_item($id,$params);
                        if($set_update==true){
                            /* Activity */
                            /*
                            $params = array(
                                'id_user' => $session['user_data']['user_id'],
                                'action' => 4,
                                'table' => 'transaksi',
                                'table_id' => ,
                                'text_1' => strtoupper($data['kode']),
                                'text_2' => ucwords(strtolower($data['nama'])),
                                'date_created' => date('YmdHis'),
                                'flag' => 0
                            );
                            */
                            // $this->save_activity($params);
                            /* End Activity */
                            $return->status=1;
                            $return->message='Success';
                        }
                    }
                    break;
                case "create-item-discount": die;
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $order_id = $data['order_id'];
                    $id = $data['id'];
                    $qty= $data['qty'];
                    $price = $data['price'];
                    $discount = $data['discount'];
                    $total = $data['total'];

                    if(floatVal($total) < 0){
                        $return->message='Subtotal minus ';
                    }else{
                        $params = array(
                            'order_item_qty' => $qty,
                            'order_item_price' => $price,
                            'order_item_discount' => $discount,
                            'order_item_total' => $total,
                        );
                        $set_update=$this->Order_model->update_order_item($id,$params);
                        if($set_update==true){
                            /* Activity */
                            /*
                            $params = array(
                                'id_user' => $session['user_data']['user_id'],
                                'action' => 4,
                                'table' => 'transaksi',
                                'table_id' => ,
                                'text_1' => strtoupper($data['kode']),
                                'text_2' => ucwords(strtolower($data['nama'])),
                                'date_created' => date('YmdHis'),
                                'flag' => 0
                            );
                            */
                            // $this->save_activity($params);
                            /* End Activity */
                            $return->status=1;
                            $return->message='Success';
                            $return->result = array(
                                'order_id' => $order_id
                            );
                        }
                    }
                    break;
                case "create-print-spoiler":
                    $this->load->model('Print_spoiler');
                    $id=$data['id'];
                    var_dump($id);
                    break;
                case "update-item-product-price":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);

                    $id = $data['order_item_id'];
                    $qty= $data['qty'];
                    $product_price_id = $data['product_price_id'];
                    $product_price_name = $data['product_price_name'];
                    $product_price_price = $data['product_price_price'];
                    $product_id = $data['product_id'];

                    //Set Price Other
                    $set_total = $product_price_price * $qty;
                    $params = array(
                        'order_item_qty' => $qty,
                        'order_item_price' => $product_price_price,
                        'order_item_total' => $set_total,
                        'order_item_product_price_id' => $product_price_id
                    );
                    $set_update=$this->Order_model->update_order_item($id,$params);
                    if($set_update==true){
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 4,
                            'table' => 'transaksi',
                            'table_id' => ,
                            'text_1' => strtoupper($data['kode']),
                            'text_2' => ucwords(strtolower($data['nama'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        */
                        // $this->save_activity($params);
                        /* End Activity */
                        $return->status=1;
                        $return->message='Harga '.$product_price_name.' terpasang';
                    }
                    break;
                case "delete-item-note":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    $params = array(
                        'order_item_note' => '',
                    );
                    $set_update=$this->Order_model->update_order_item($id,$params);
                    if($set_update==true){
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 4,
                            'table' => 'transaksi',
                            'table_id' => ,
                            'text_1' => strtoupper($data['kode']),
                            'text_2' => ucwords(strtolower($data['nama'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        */
                        // $this->save_activity($params);
                        /* End Activity */
                        $return->status=1;
                        $return->message='Success';
                    }
                    break;
                case "delete-item-discount":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $order_id = $data['order_id'];
                    $id = $data['id'];
                    $qty= $data['qty'];
                    $price = $data['price'];

                    $set_price = $price;
                    $set_qty = $qty;
                    $set_total = $set_price*$set_qty;

                    $params = array(
                        'order_item_qty' => $set_qty,
                        'order_item_price' => $set_price,
                        'order_item_discount' => 0,
                        'order_item_total' => $set_total,
                    );
                    $set_update=$this->Order_model->update_order_item($id,$params);
                    if($set_update==true){
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 4,
                            'table' => 'transaksi',
                            'table_id' => ,
                            'text_1' => strtoupper($data['kode']),
                            'text_2' => ucwords(strtolower($data['nama'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        */
                        // $this->save_activity($params);
                        /* End Activity */
                        $return->status=1;
                        $return->message='Success';
                        $return->result = array(
                            'order_id' => $order_id
                        );
                    }
                    break;
                case "load-product-tab":
                    $start = 0;
                    $limit = 100;
                    $order = 'category_name';
                    $dir = 'ASC';
                    $search=null;
                    // $params_datatable = array(
                    //     'ref_type' => $data['ref_type'], //Product Categories
                    //     'ref_flag' => $data['ref_flag']
                    // );
                    $params_datatable = array(
                        'category_type' => 1, //Product Categories
                        'category_flag' => 1,
                        'category_branch_id' => $session_branch_id
                    );
                    // $datas = $this->Referensi_model->get_all_referensis($params_datatable, $search, $limit, $start, $order, $dir);
                    // $datas_count = $this->Referensi_model->get_all_referensis_count($params_datatable);
                    $datas = $this->Kategori_model->get_all_categoriess($params_datatable, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Kategori_model->get_all_categoriess_count($params_datatable);
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
                    break;
                case "load-product-tab-detail":
                    // $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    // $data = json_decode($post_data, TRUE);

                    // $trans_id = $this->input->post('id');
                    $limit = $this->input->post('length');
                    $start = $this->input->post('start');
                    // $order = $columns[$this->input->post('order')[0]['column']];
                    // $dir = $this->input->post('order')[0]['dir'];

                    $search = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }

                    /*
                    if($this->input->post('other_column') && $this->input->post('other_column') > 0) {
                        $params['other_column'] = $this->input->post('other_column');
                    }
                    */
                    $start = 0;
                    $limit = 100;
                    $order = 'product_name';
                    $order = 'product_name';
                    $dir = 'ASC';
                    $category_id = $data['category_id'];
                    if($category_id > 0){
                        $params_datatable = array(
                            'product_flag' => 1,
                            'product_type' => 1, //Barang
                            'product_category_id' => $category_id, //Product Categories
                            'product_branch_id' => $session_branch_id
                        );
                    }else if($category_id == -1){
                        $params_datatable = array(
                            'product_flag' => 1,
                            'product_type' => 1, //Barang
                            'product_price_promo >' => 0, //Product Categories
                            'product_branch_id' => $session_branch_id
                        );
                    }else{
                        $params_datatable = array(
                            'product_flag' => 1,
                            'product_type' => 1, //Barang
                            'product_category_id !=' => 0,
                            'product_branch_id' => $session_branch_id
                            // 'product_ref_id ' => $reference_id //Product Categories
                        );
                    }

                    // var_dump($params_datatable,$search,$limit,$start,$order,$dir);die;
                    $datas = $this->Produk_model->get_all_produks($params_datatable, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Produk_model->get_all_produks_count($params_datatable);
                    // $datas_count = $this->Order_model->get_all_orders_count($params_datatable, $search, $limit, $start, $order, $dir);

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
                    break;
                /* Payment */
                case "load-unpaid-order":
                    $limit = 100;
                    $start = 0;
                    $order = 'order_date';
                    $dir = 'DESC';
                    $search = null;
                    // $search = [];
                    // if ($this->input->post('search')['value']) {
                    //     $s = $this->input->post('search')['value'];
                    //     foreach ($columns as $v) {
                    //         $search[$v] = $s;
                    //     }
                    // }
                    $params_request = array(
                        'orders.order_type' => $identity,
                        'orders.order_flag' => 0,
                        'orders.order_branch_id' => $session_branch_id
                    );
                    $datas = $this->Order_model->get_all_orders($params_request, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Order_model->get_all_orders_count($params_request);

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
                    break;
                case "read-order":
                    // $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    // $data['id'] = $this->input->post('id');
                    $datas = $this->Order_model->get_order($data['id']);
                    $params = array(
                        'order_item_order_id' => $data['id']
                    );
                    $get_data_items = $this->Order_model->get_all_order_items($params,null,100,0,'order_item_id','asc');
                    if($datas==true){

                        $subtotal = 0;
                        $total_diskon = 0;
                        $total= 0;

                        foreach($get_data_items as $v){
                            $data_items[] = array(
                                'order_item_id' => $v['order_item_id'],
                                'order_item_order_id' => $v['order_item_order_id'],
                                'order_item_unit' => $v['order_item_unit'],
                                'order_item_qty' => $v['order_item_qty'],
                                'order_item_price' => number_format($v['order_item_price'],0,'.',','),
                                'order_item_discount' => number_format($v['order_item_discount'],0,'.',','),
                                'order_item_total' => number_format($v['order_item_total'],0,'.',','),
                                'order_item_total_after_discount' => number_format($v['order_item_total'],0,'.',','),
                                'order_item_note' => $v['order_item_note'],
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                            );
                            $subtotal=$subtotal+$v['order_item_total'];
                        }
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 3,
                            'table' => 'transaksi',
                            'table_id' => $datas['id'],
                            'text_1' => strtoupper($datas['kode']),
                            'text_2' => ucwords(strtolower($datas['username'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */
                    if(isset($data_items)){ //Data exist
                        $data_source=$data_items; $total=count($data_items);
                        $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                        $return->result=$datas;
                        $return->total_produk=count($data_items);
                        $return->subtotal=number_format($subtotal,0,'.',',');
                        $return->total_diskon=number_format($total_diskon,0,'.',',');
                        $return->total=number_format($subtotal-$total_diskon,0,'.',',');
                        $return->total_dp = number_format($datas['order_with_dp'],0,'.',',');
                        $return->total_grand = ($subtotal-$total_diskon)-$datas['order_with_dp'];
                    }else{
                        $data_source=0; $total=0;
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;
                    }
                        $return->total_records = $total;
                        $return->status=1;
                        $return->message='Success';
                        $return->result=$datas;
                        $return->result_item = $data_items;
                    }
                    break;
                case "create-item-payment": //Not Used
                    $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    $params_items = array(
                        'order_flag' => 3
                    );
                    $this->Order_model->update_order($id,$params_items);
                    /* Start Activity */
                    /*
                    $params = array(
                        'id_user' => $session['user_data']['user_id'],
                        'action' => 2,
                        'table' => 'transaksi',
                        'table_id' => $set_data,
                        'text_1' => strtoupper($data['kode']),
                        'text_2' => ucwords(strtolower($data['nama'])),
                        'date_created' => date('YmdHis'),
                        'flag' => 1
                    );
                    $this->save_activity($params);
                    */
                    /* End Activity */
                    $return->status=1;
                    $return->message='Success';
                    break;
                case "delete-item-payment": //Not Used
                    $id = $this->input->post('id');
                    $ref = $this->input->post('ref');
                    $number = $this->input->post('number');
                    // $flag = $this->input->post('flag');
                    // die;
                    // if($flag==1){
                    //     $msg='aktifkan transaksi '.$nama;
                    //     $act=7;
                    // }else{
                    //     $msg='nonaktifkan transaksi '.$nama;
                    //     $act=8;
                    // }

                    // $set_data=$this->Order_model->update_order_item($id,array('order_item_flag'=>0));
                    $params = array(
                        'order_flag' => 0
                    );
                    $set_data=$this->Order_model->update_order($id,$params);
                    if($set_data==true){
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => $act,
                            'table' => 'transaksi',
                            'table_id' => $id,
                            'text_1' => strtoupper($kode),
                            'text_2' => ucwords(strtolower($nama)),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        */
                        // $this->save_activity($params);
                        /* End Activity */
                        $return->status=1;
                        $return->message='Berhasil menghapus '.$ref;
                    }
                    break;
                case "check-payment-item":
                    // $get_data = $this->Order_model->check_unsaved_order_item($session['user_data']['user_id']);
                    $params = array(
                        'order_flag' => 3,
                        'order_user_id' => $session_user_id,
                        'order_branch_id' => $session_branch_id
                    );
                    $get_data = $this->Order_model->get_all_orders($params,null,100,0,'order_number','asc');
                    // var_dump($get_data);die;
                    if(!empty($get_data)){
                        $subtotal = 0;
                        $down_payment = 0;
                        $total_diskon = 0;
                        $total= 0;
                        foreach ($get_data as $h) {
                            $order_item_list = $this->Order_model->get_all_order_items(array('order_item_order_id'=>$h['order_id']),null,100,0,'order_item_id','asc');
                            $data_items = array();
                            foreach($order_item_list as $v){
                                $data_items[] = array(
                                    'order_item_id' => $v['order_item_id'],
                                    'order_item_order_id' => $v['order_item_order_id'],
                                    'order_item_unit' => $v['order_item_unit'],
                                    'order_item_qty' => $v['order_item_qty'],
                                    'order_item_price' => number_format($v['order_item_price'],0,'.',','),
                                    'order_item_discount' => number_format($v['order_item_discount'],0,'.',','),
                                    'order_item_total' => number_format($v['order_item_total'],0,'.',','),
                                    'order_item_total_after_discount' => number_format($v['order_item_total'],0,'.',','),
                                    'order_item_note' => $v['order_item_note'],
                                    'product_id' => $v['product_id'],
                                    'product_code' => $v['product_code'],
                                    'product_name' => $v['product_name'],
                                );
                                // $subtotal=$subtotal+$v['order_item_total'];
                            }

                            $datas[] = array(
                                'order_id' => $h['order_id'],
                                'order_number' => $h['order_number'],
                                'order_date' => $h['order_date'],
                                'order_total' => $h['order_subtotal'],
                                'order_total_down_payment' => $h['order_with_dp'],
                                'order_total_grand' => $h['order_subtotal']-$h['order_with_dp'],
                                'ref_name' => $h['ref_name'],
                                'order_items' => $data_items
                            );
                            $subtotal = $subtotal + $h['order_subtotal'];
                            $down_payment = $down_payment + $h['order_with_dp'];
                            $order_ids[] = $h['order_id'];
                        }
                        $order_list_id = implode(', ', $order_ids);


                        /*
                        foreach($get_data as $v){
                            $datas[] = array(
                                'order_item_id' => $v['order_item_id'],
                                'order_item_order_id' => $v['order_item_order_id'],
                                'order_item_unit' => $v['order_item_unit'],
                                'order_item_qty' => $v['order_item_qty'],
                                'order_item_price' => number_format($v['order_item_price'],0,'.',','),
                                'order_item_discount' => number_format($v['order_item_discount'],0,'.',','),
                                'order_item_total' => number_format($v['order_item_total'],0,'.',','),
                                'order_item_total_after_discount' => number_format($v['order_item_total'],0,'.',','),
                                'order_item_note' => $v['order_item_note'],
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                            );
                            $subtotal=$subtotal+$v['order_item_total'];
                        }
                        */
                        // var_dump($datas);die;

                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 3,
                            'table' => 'transaksi',
                            'table_id' => $datas['id'],
                            'text_1' => strtoupper($datas['kode']),
                            'text_2' => ucwords(strtolower($datas['username'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas;
                            $return->total_produk=count($datas);
                            $return->subtotal=number_format($subtotal,0,'.',',');
                            $return->total_down_payment=number_format($down_payment,0,'.',',');
                            $return->total_diskon=number_format($total_diskon,0,'.',',');
                            $return->total=number_format(($subtotal-$total_diskon)-$down_payment,0,'.',',');
                            $return->order_list_id = $order_list_id;
                        }else{
                            $data_source=0; $total=0;
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->result=0;
                        }
                        $return->status=1;
                        $return->message='Terdapat payment temporary';
                    }else{
                        $return->message='Tidak ada payment temporary';
                    }
                    break;
                case "create-payment":
                    $next=true;
                    $type = $data['method']; //1=Tunai, 3=Debit/Kredit, 5=Digital

                    $generate_nomor = $this->request_number_for_transaction(2);
                    if($type==1){
                        $params = array(
                            'trans_type' => 2,
                            'trans_contact_id' => $data['contact'],
                            'trans_paid_type' => $type,
                            // 'trans_card_number' => $data['card_number'],
                            // 'trans_card_bank_name' => $data['card_bank'],
                            // 'trans_card_account_name' => $data['card_name'],
                            // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                            // 'trans_card_type' => $data['card_type'],
                            // 'trans_digital_provider' => $data['digital_type'],
                            'trans_number' => $generate_nomor,
                            'trans_date' => date('Ymdhis'),
                            // 'trans_note' => $data['note'],
                            'trans_user_id' => $session['user_data']['user_id'],
                            'trans_date_created' => date('Ymdhis'),
                            'trans_date_updated' => date('Ymdhis'),
                            'trans_flag' => 1,
                            'trans_total' => str_replace(',','',$data['total']),
                            // 'trans_discount' => $data['discount'],
                            'trans_received' => str_replace(',','',$data['received']),
                            'trans_change' => str_replace(',','',$data['change']),
                        );
                    }
                    else if(($type==3) or ($type==4)){
                        $params = array(
                            'trans_type' => 2,
                            'trans_contact_id' => $data['contact'],
                            'trans_paid_type' => $type,
                            'trans_card_number' => $data['card_number'],
                            'trans_card_bank_name' => $data['card_bank'],
                            'trans_card_account_name' => $data['card_name'],
                            'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                            'trans_card_type' => $data['card_type'],
                            // 'trans_digital_provider' => $data['digital_type'],
                            'trans_number' => $generate_nomor,
                            'trans_date' => date('Ymdhis'),
                            'trans_note' => $data['note'],
                            'trans_user_id' => $session['user_data']['user_id'],
                            'trans_date_created' => date('Ymdhis'),
                            'trans_date_updated' => date('Ymdhis'),
                            'trans_flag' => 1,
                            'trans_total' => str_replace(',','',$data['total']),
                            // 'trans_discount' => $data['discount'],
                            'trans_received' => str_replace(',','',$data['received']),
                            'trans_change' => str_replace(',','',$data['change']),
                        );
                    }
                    else if($type==5){
                        $trans_fee = 0;
                        if($data['digital_type']=='ShopeePAY'){
                            $trans_fee = str_replace(',','',$data['total']) - str_replace(',','',$data['total_before_fee']);
                        }
                        $params = array(
                            'trans_type' => 2,
                            'trans_contact_id' => $data['contact'],
                            'trans_paid_type' => $type,
                            // 'trans_card_number' => $data['card_number'],
                            // 'trans_card_bank_name' => $data['card_bank'],
                            // 'trans_card_account_name' => $data['card_name'],
                            // 'trans_card_expired' => $data['card_year'].'/'.$data['card_month'],
                            // 'trans_card_type' => $data['card_type'],
                            'trans_digital_provider' => $data['digital_type'],
                            'trans_number' => $generate_nomor,
                            'trans_date' => date('Ymdhis'),
                            'trans_note' => $data['note'],
                            'trans_user_id' => $session['user_data']['user_id'],
                            'trans_date_created' => date('Ymdhis'),
                            'trans_date_updated' => date('Ymdhis'),
                            'trans_flag' => 1,
                            'trans_total' => str_replace(',','',$data['total']),
                            // 'trans_discount' => $data['discount'],
                            'trans_received' => str_replace(',','',$data['received']),
                            'trans_change' => str_replace(',','',$data['change']),
                            'trans_fee' => $trans_fee
                        );
                    }
                    else{
                        $return->message='Harap pilih jenis pembayaran';
                        $next=false;
                    }

                    if($next==true){
                        //Check Data Exist
                        $params_check = array(
                            'trans_number' => $generate_nomor,
                            'trans_type' => 2,
                            'trans_branch_id' => $session_branch_id
                        );
                        $check_exists = $this->Transaksi_model->check_data_exist($params_check);
                        if($check_exists==false){

                            $set_data=$this->Transaksi_model->add_transaksi($params);
                            if($set_data==true){
                                $trans_id = $set_data;

                                //Update Flag is Payment
                                $list = $data['order_list_id'];
                                $list_explode = explode(', ', $list);
                                foreach($list_explode as $k => $v){
                                    // echo "AS ".$v;
                                    $params = array(
                                        'order_flag' => 1,
                                        'order_trans_id' => $trans_id
                                    );
                                    $id = $v;
                                    $this->Order_model->update_order($id,$params);

                                    //Prepare Recipe
                                    //Get Order Item by Id
                                    $params_order_item = array(
                                        'order_item_order_id' => $id
                                    );
                                    $get_order_item = $this->Order_model->get_all_order_items($params_order_item,null,null,null,null,null);
                                    foreach($get_order_item as $r){
                                        $product_id = $r['order_item_product_id'];
                                        $params_recipe = array(
                                            'recipe_product_id' => $product_id
                                        );
                                        $check_has_recipe = $this->Product_recipe_model->get_all_recipe_count($params_recipe);
                                        // var_dump($check_has_recipe,',<br>');
                                        if($check_has_recipe > 0){ //Found Recipe On Product
                                            $get_recipe = $this->Product_recipe_model->get_all_recipe($params_recipe,null,null,null,null,null);
                                            foreach($get_recipe as $g){

                                                $recipe_product_id = $g['recipe_product_id_child'];
                                                $recipe_unit = $g['recipe_unit'];
                                                $recipe_qty = $g['recipe_qty'];

                                                $set_recipe_qty = $recipe_qty * $r['order_item_qty'];

                                                $params_add_recipe_to_order = array(
                                                    'order_item_recipe_order_item_id' => $r['order_item_id'],
                                                    // 'order_item_id_order' => $data['order_id'],
                                                    // 'order_item_id_order_detail' => $data['order_detail_id'],
                                                    'order_item_product_id' => $recipe_product_id,
                                                    // 'order_item_id_lokasi' => $data['lokasi_id'],
                                                    'order_item_date' => date("YmdHis"),
                                                    'order_item_unit' => $recipe_unit,
                                                    'order_item_qty' => $set_recipe_qty,
                                                    // 'order_item_price' => $data['harga'],
                                                    // 'order_item_keluar_qty' => $data['qty'],
                                                    // 'order_item_keluar_harga' => $data['harga'],
                                                    'order_item_type' => '6', //Opname
                                                    // 'order_item_discount' => 0,
                                                    // 'order_item_total' => $total,
                                                    'order_item_date_created' => date("YmdHis"),
                                                    'order_item_date_updated' => date("YmdHis"),
                                                    'order_item_user_id' => $session_user_id,
                                                    'order_item_branch_id' => $session_branch_id,
                                                    'order_item_flag' => 0
                                                );
                                                $this->Order_model->add_order_item($params_add_recipe_to_order);
                                            }
                                        }

                                    }
                                }
                                // $trans_list = $data['order_list'];
                                // foreach($trans_list as $index => $value){

                                //     $params_update_trans_item = array(
                                //         'order_item_order_id' => $trans_id,
                                //         'order_item_flag' => 1
                                //     );
                                //     $this->Order_model->update_order_item($value,$params_update_trans_item);
                                // }

                                /* Start Activity */
                                /*
                                $params = array(
                                    'id_user' => $session['user_data']['user_id'],
                                    'action' => 2,
                                    'table' => 'transaksi',
                                    'table_id' => $set_data,
                                    'text_1' => strtoupper($data['kode']),
                                    'text_2' => ucwords(strtolower($data['nama'])),
                                    'date_created' => date('YmdHis'),
                                    'flag' => 1
                                );
                                $this->save_activity($params);
                                */
                                /* End Activity */
                                $return->status=1;
                                $return->message='Success';
                                $return->result= array(
                                    'trans_id' => $trans_id,
                                    'trans_number' => $generate_nomor
                                );
                            }
                        }else{
                            $return->message='Nomor sudah digunakan';
                        }
                    }
                    break;
                case "create-print-payment-spoiler":
                    $this->load->model('Print_spoiler');
                    $id=$data['id'];
                    var_dump($id);
                    break;

                /* Post From : Sales Order / Purchase Order */
                case "load-order":
                    $order_id = !empty($this->input->post('order_id')) ? $this->input->post('order_id') : 0;
                    // var_dump($order_id);
                    $order = 0;
                    $contact = 0;
                    if(intval($order_id) > 0){
                        $get_order = $this->Order_model->get_order($order_id);
                        $get_contact = $this->Kontak_model->get_kontak($get_order['contact_id']);
                        $contact= array(
                            'contact_id' => $get_contact['contact_id'],
                            'contact_code' => $get_contact['contact_code'],
                            'contact_name' => $get_contact['contact_name'],
                            'contact_address' => $get_contact['contact_address'],
                            'contact_email' => $get_contact['contact_email_1'],
                            'contact_phone' => $get_contact['contact_phone_1'],
                        );
                        $order = array(
                            'order_id' => $get_order['order_id'],
                            'order_number' => $get_order['order_number'],
                            'order_date' => $get_order['order_date'],
                            'order_type' => $get_order['order_type']
                        );
                        $params = array(
                            'order_item_branch_id' => $session_branch_id,
                            'order_item_order_id' => $order_id,
                        );
                        $get_data = $this->Order_model->get_all_order_items($params,null,null,null,null);
                    }else{
                        // $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }
                    // var_dump($get_data);
                    if(!empty($get_data)){
                        $total_order = 0;
                        $total_terbayar = 0;
                        $total_sisa = 0;

                        foreach($get_data as $v){
                            // $sisa = 0;
                            // $sisa = $v['trans_total']-$v['total_paid'];
                            $datas[] = array(
                                'order_item_id' => $v['order_item_id'],
                                'order_item_order_id' => $v['order_item_order_id'],
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'product_unit' => $v['product_unit'],
                                'order_item_qty' => number_format($v['order_item_qty'],2,'.',','),
                                'order_item_price' => number_format($v['order_item_price'],2,'.',','),
                                'order_item_total' => number_format($v['order_item_total'],2,'.',','),
                                // 'trans_number' => $v['trans_number'],
                                // 'trans_date' => $v['trans_date'],
                                // 'trans_date_due' => $v['trans_date_due'],
                                // 'trans_date_format' => $v['trans_date_due_format'],
                                // 'trans_date_due_format' => $v['trans_date_due_format'],
                                // 'trans_note' => $v['trans_note'],
                                // 'trans_paid' => $v['trans_paid'],
                                // 'trans_flag' => $v['trans_flag'],
                                // 'trans_total' => number_format($v['trans_total'],2,'.',','),
                                // 'total_paid' => number_format($v['total_paid'],2,'.',','),
                                // 'total_sisa' => number_format($sisa,2,'.',',')
                            );
                            $total_order=$total_order+$v['order_item_total'];
                            // $total_terbayar=$total_terbayar+$v['total_paid'];
                        }
                        // $total_sisa = $total_tagihan-$total_terbayar;
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 3,
                            'table' => 'transaksi',
                            'table_id' => $datas['id'],
                            'text_1' => strtoupper($datas['kode']),
                            'text_2' => ucwords(strtolower($datas['username'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas;
                            $return->total_order=number_format($total_order,2,'.',',');
                            // $return->total_terbayar=number_format($total_terbayar,2,'.',',');
                            // $return->total_sisa=number_format($total_sisa,2,'.',',');
                            $return->contact = $contact;
                            $return->order = $order;
                        }else{
                            $data_source=0; $total=0;
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            // $return->total_tagihan= 0;
                            // $return->total_terbayar= 0;
                            // $return->total_sisa= 0;
                            $return->result=0;
                        }
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($order_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada Order';
                        if(intval($order_id) > 0){
                            $return->message = '-';
                        }
                    }
                    break;
                case "load-retur": die;
                    $order_id = !empty($this->input->post('order_id')) ? $this->input->post('order_id') : 0;
                    // var_dump($order_id);
                    $order = 0;
                    $contact = 0;
                    if(intval($order_id) > 0){
                        $get_order = $this->Order_model->get_order($order_id);
                        $get_contact = $this->Kontak_model->get_kontak($get_order['contact_id']);
                        $contact= array(
                            'contact_id' => $get_contact['contact_id'],
                            'contact_code' => $get_contact['contact_code'],
                            'contact_name' => $get_contact['contact_name'],
                            'contact_address' => $get_contact['contact_address'],
                            'contact_email' => $get_contact['contact_email_1'],
                            'contact_phone' => $get_contact['contact_phone_1'],
                        );
                        $order = array(
                            'order_id' => $get_order['order_id'],
                            'order_number' => $get_order['order_number'],
                            'order_date' => $get_order['order_date'],
                            'order_type' => $get_order['order_type']
                        );
                        $params = array(
                            'order_item_branch_id' => $session_branch_id,
                            'order_item_order_id' => $order_id,
                        );
                        $get_data = $this->Order_model->get_all_order_items($params,null,null,null,null);
                    }else{
                        // $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }
                    // var_dump($get_data);
                    if(!empty($get_data)){
                        $total_order = 0;
                        $total_terbayar = 0;
                        $total_sisa = 0;

                        foreach($get_data as $v){
                            // $sisa = 0;
                            // $sisa = $v['trans_total']-$v['total_paid'];
                            $datas[] = array(
                                'order_item_id' => $v['order_item_id'],
                                'order_item_order_id' => $v['order_item_order_id'],
                                'product_id' => $v['product_id'],
                                'product_code' => $v['product_code'],
                                'product_name' => $v['product_name'],
                                'product_unit' => $v['product_unit'],
                                'order_item_qty' => number_format($v['order_item_qty'],2,'.',','),
                                'order_item_price' => number_format($v['order_item_price'],2,'.',','),
                                'order_item_total' => number_format($v['order_item_total'],2,'.',','),
                                // 'trans_number' => $v['trans_number'],
                                // 'trans_date' => $v['trans_date'],
                                // 'trans_date_due' => $v['trans_date_due'],
                                // 'trans_date_format' => $v['trans_date_due_format'],
                                // 'trans_date_due_format' => $v['trans_date_due_format'],
                                // 'trans_note' => $v['trans_note'],
                                // 'trans_paid' => $v['trans_paid'],
                                // 'trans_flag' => $v['trans_flag'],
                                // 'trans_total' => number_format($v['trans_total'],2,'.',','),
                                // 'total_paid' => number_format($v['total_paid'],2,'.',','),
                                // 'total_sisa' => number_format($sisa,2,'.',',')
                            );
                            $total_order=$total_order+$v['order_item_total'];
                            // $total_terbayar=$total_terbayar+$v['total_paid'];
                        }
                        // $total_sisa = $total_tagihan-$total_terbayar;
                        /* Activity */
                        /*
                        $params = array(
                            'id_user' => $session['user_data']['user_id'],
                            'action' => 3,
                            'table' => 'transaksi',
                            'table_id' => $datas['id'],
                            'text_1' => strtoupper($datas['kode']),
                            'text_2' => ucwords(strtolower($datas['username'])),
                            'date_created' => date('YmdHis'),
                            'flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas;
                            $return->total_order=number_format($total_order,2,'.',',');
                            // $return->total_terbayar=number_format($total_terbayar,2,'.',',');
                            // $return->total_sisa=number_format($total_sisa,2,'.',',');
                            $return->contact = $contact;
                            $return->order = $order;
                        }else{
                            $data_source=0; $total=0;
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            // $return->total_tagihan= 0;
                            // $return->total_terbayar= 0;
                            // $return->total_sisa= 0;
                            $return->result=0;
                        }
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($order_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada Order';
                        if(intval($order_id) > 0){
                            $return->message = '-';
                        }
                    }
                    break;
                /* Return */
                case "create-return":
                    $next=true;
                    $jam = date('H:i:s');
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $set_date = $tgl.' '.$jam;                    
                    // die;
                    $trans_list = !empty($data['trans_list']) ? $data['trans_list'] : null;
                    if($trans_list){
                        foreach($trans_list as $i){
                            
                            //Check Again
                            $item_id            = intval($i['trans_item_id']);
                            $item_input         = $i['qty_input'];
                            $item_price         = floatval($i['trans_item_price']);
                            $item_ref           = $i['trans_item_ref'];
                            $item_unit          = $i['trans_item_unit'];
                            $item_product       = $i['trans_item_product_id'];
                            $item_product_type  = $i['trans_item_product_type'];
                            $item_location      = $i['trans_item_location'];
                            $item_ppn           = intval($i['trans_item_ppn']);
                            $item_ppn_value     = $i['trans_item_ppn_value'];

                            if(floatval($item_input) > 0){
                                //Do Save Trans Item
                                $items = array(
                                    'trans_item_type' => $identity,
                                    'trans_item_date' => $set_date,
                                    'trans_item_unit' => $item_unit,
                                    'trans_item_date_created' => date('YmdHis'),
                                    'trans_item_user_id' => $session_user_id,
                                    'trans_item_branch_id' => $session_branch_id,
                                    'trans_item_ref' => $item_ref,
                                    'trans_item_flag' => 55,
                                    'trans_item_product_id' => $item_product,
                                    'trans_item_product_type' => $item_product_type,
                                    'trans_item_ppn' => $item_ppn,
                                    'trans_item_ppn_value' => $item_ppn_value
                                );

                                if($identity==3){ //Retur Beli, maka Qty OUT
                                    $items['trans_item_out_qty']        = $item_input;
                                    $items['trans_item_out_price']      = $item_price;
                                    $items['trans_item_total']          = $item_price * $item_input;
                                    $items['trans_item_location_id']    = $item_location;
                                }else if($identity==4){
                                    $item_sell_price = !empty(floatval($i['trans_item_sell_price'])) ? floatval($i['trans_item_sell_price']) : '0.00';
                                    $items['trans_item_in_qty']         = $item_input;
                                    $items['trans_item_in_price']       = $item_price;
                                    $items['trans_item_sell_price']     = $item_sell_price;
                                    // $items['trans_item_total']       = $item_price * $item_input;
                                    $items['trans_item_total']          = $item_sell_price * $item_input;
                                    $items['trans_item_sell_total']     = '0.00';

                                    $items['trans_item_location_id']    = $item_location;
                                }
                                $this->Transaksi_model->add_transaksi_item($items);
                            }
                        }

                        if($next){
                            //Save Trans
                            $generate_session = $this->random_session(20);
                            $generate_nomor = $this->request_number_for_transaction($identity);
                            $trans_number = !empty($data['nomor']) ? $data['nomor'] : $generate_nomor;

                            $params = array(
                                'trans_session' => $generate_session,
                                'trans_type' => $identity,
                                'trans_date' => $set_date,
                                'trans_number' => (strlen($data['nomor']) > 0) ? $data['nomor'] : $trans_number,
                                'trans_contact_id' => $data['kontak'],
                                'trans_note' => !empty($data['keterangan']) ? $data['keterangan'] : '',
                                'trans_user_id' => $session_user_id,
                                'trans_branch_id' => $session_branch_id,
                                'trans_date_created' => date('YmdHis'),
                                'trans_id_source' => $data['trans_source_id'],
                            );
                            $set_data = $this->Transaksi_model->add_transaksi($params);
                            
                            //Get ID Trans
                            $trans_id = $set_data;

                            //Update Temp to Trans Id
                            $where = array(
                                'trans_item_type' => $identity,
                                'trans_item_flag' => 55,
                            );
                            $params_update = array(
                                'trans_item_trans_id' => $trans_id,
                                'trans_item_flag' => 1
                            );
                            $this->Transaksi_model->update_transaksi_item_custom($where,$params_update);
                            
                            //Call SP
                            if(intval($trans_id) > 0){
                                
                                if($config_post_to_journal==true){
                                    $operator = $this->journal_for_transaction('CREATE',$trans_id);                           
                                    $return->trans_id = 1;
                                }

                                $return->status=1;
                                $return->message='Berhasil menyimpan';
                                $return->result=array(
                                    'trans_id' => $trans_id,
                                    'trans_number' => $generate_nomor,
                                    'trans_session' => $generate_session
                                );
                            }else{
                                $return->message='Retur tidak dapat diproses';
                            }
                        }
                    }else{
                        $return->message='Data retur bermasalah';
                    }
                    break;
                case "read-return":
                    $data['id'] = $this->input->post('id');
                    $datas = $this->Transaksi_model->get_transaksi($data['id']);

                    //Get Trans Source on Trans Table
                    $params = array(
                        'trans_id' => $datas['trans_id_source']
                    );
                    $get_source = $this->Transaksi_model->get_transaksi_custom($params);

                    $return->status     = 1;
                    $return->message    = 'Success';
                    $return->result     = $datas;
                    $return->result_source = array(
                        'trans_source_id' => $get_source['trans_id'],
                        'trans_source_session' => $get_source['trans_session'],                    
                        'trans_source_number' => $get_source['trans_number'],
                        'trans_source_date' => $get_source['trans_date']
                    );
                    break;
                case "trans-payment-history":
                    $trans_id = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : 0;
                    $trans_type = !empty($this->input->post('trans_type')) ? $this->input->post('trans_type') : 0;

                    if($trans_id > 0){
                        $trans_history = array();
                        $get_trans_history = $this->trans_history($trans_id);
                        foreach($get_trans_history as $r):
                            if($r['temp_transaction_type']=='trans'){
                                $temp_url = base_url().'transaksi/print_history/'.$r['temp_session'];
                            }else if($r['temp_transaction_type']=='journals'){
                                $temp_url = base_url().'keuangan/prints/'.$r['temp_trans_id'];
                            }                        
                            $trans_history[] = array(
                                'temp_url' => $temp_url,
                                'temp_id' => $r['temp_id'],
                                'temp_date' => $r['temp_date'],
                                'temp_trans_id' => $r['temp_trans_id'],
                                'temp_source_id' => $r['temp_source_id'],
                                'temp_session' => $r['temp_session'],
                                'temp_number' => $r['temp_number'],
                                'temp_type' => $r['temp_type'],
                                'temp_transaction_type' => $r['temp_transaction_type'],
                                'temp_debit' => $r['temp_debit'],
                                'temp_credit' => $r['temp_credit'],
                                'temp_note' => $r['temp_note'],
                                'temp_memo' => $r['temp_memo'],
                                'temp_group_session' => $r['temp_group_session'],
                                'temp_balance' => $r['temp_balance']
                            );                
                        endforeach;

                        $return->status = 1;
                        $return->result = $trans_history;
                        $return->total_data = count($trans_history);
                    }else{
                        $return->message='Transaksi tidak ditemukan';
                    }
                    break;           
                case "trans-unpaid-and-overdue":
                    $type = $this->input->post('type');
                    $query = $this->db->query("
                        SELECT COUNT(*) AS c, SUM(trans_total)-SUM(trans_total_paid) AS total, DATEDIFF(trans_date_due,NOW()) AS date_due_over,
                        (SELECT CASE 
                        WHEN date_due_over > 0 THEN 
                            CASE 
                            WHEN trans_type=1 THEN 'Belum membayar Supplier'
                            WHEN trans_type=2 THEN 'Belum dilunasi Customer'
                            END
                        WHEN date_due_over < 0 THEN 
                            CASE 
                            WHEN trans_type=1 THEN 'Supplier yg jatuh tempo'
                            WHEN trans_type=2 THEN 'Customer yg jatuh tempo'
                            END
                        END) AS `label`,
                        fn_time_ago(MAX(trans_date) OVER(PARTITION BY trans_date)) AS last_insert,
                        (SELECT CASE WHEN date_due_over < 1 THEN 'Jatuh Tempo' WHEN date_due_over > 0 THEN 'Belum Jatuh Tempo' END) AS `type_label`
                        FROM trans 
                        WHERE trans_type=$type AND trans_paid=0
                        GROUP BY `type_label`");
                    $return->status=1;
                    $return->message='Loaded';
                    $return->result= $query->result_array();
                    break;
                case "calculate-due-date":
                    $termin = !empty($this->input->post('termin')) ? intval($this->input->post('termin')) : 0;
                    $date = !empty($this->input->post('date_from')) ? $this->input->post('date_from') : date("d-m-Y");

                    $set_date = substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2);
                    // var_dump($date);die;
                    if($termin > 0){
                        $return->result = date("d-m-Y", strtotime('+'.$termin.' day',strtotime(date($set_date))));
                    }else{
                        $return->result = date("d-m-Y");
                    }
                    $return->status=1;
                    break;
                default:
                    $return->message='No Action';
                    break;
            }
        }
        if(empty($action)){
            $action='';
        }
        $return->action=$action;
        echo json_encode($return);
    }
    public function set_line($char, $width) {
        // $lines=array();
        // foreach (explode("\n",wordwrap($kolom1,$len=32)) as $line)
        //     $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        // return implode("\n",$lines)."\n";
        $ret = '';
        for($a=0; $a<$width; $a++){
            $ret .= $char;
        }
        return $ret."\n";
    }
    public function set_wrap_1($kolom1) {
        $lines=array();
        foreach (explode("\n",wordwrap($kolom1,$len=28)) as $line)
            $lines[]=str_pad($line,$len,' ',STR_PAD_BOTH);
        return implode("\n",$lines)."\n";
    }
    public function set_wrap_2($kolom1, $kolom2) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 15;
        $lebar_kolom_2 = 13;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            // $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            // $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    public function set_wrap_3($kolom1, $kolom2, $kolom3) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 14;
        $lebar_kolom_2 = 1;
        $lebar_kolom_3 = 12;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    public function set_wrap_4($kolom1, $kolom2, $kolom3, $kolom4) {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 12;
        $lebar_kolom_2 = 8;
        $lebar_kolom_3 = 8;
        $lebar_kolom_4 = 9;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);
        $kolom4 = wordwrap($kolom4, $lebar_kolom_4, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);
        $kolom4Array = explode("\n", $kolom4);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array), count($kolom4Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");

            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);
            $hasilKolom4 = str_pad((isset($kolom4Array[$i]) ? $kolom4Array[$i] : ""), $lebar_kolom_4, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 . " " . $hasilKolom4;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        return implode($hasilBaris, "\n") . "\n";
    }
    function prints($id){ //Standard Print
        //Header
        $params = array(
            'trans_id' => $id
        );
        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Transaksi_model->get_transaksi($id);
        $data['branch'] = $this->Branch_model->get_branch($data['header']['user_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }
        $data['journal_item'] = array();

        // Transfer Stok Dokumen
        $data['location'] = array();
        if($data['header']['trans_type'] == 1){
            $params_journal = array(
                'journal_item_type' => 1,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 2){
            $params_journal = array(
                'journal_item_type' => 2,
                'journal_item_trans_id' => $id,
                'journal_item_credit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 3){
            $params_journal = array(
                'journal_item_type' => 3,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 5){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                'location_to' => $location_to
            );
        }else if(($data['header']['trans_type'] == 6) or ($data['header']['trans_type'] == 7)){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                // 'location_to' => $location_to
            );
        }else if($data['header']['trans_type'] == 8){
            $params_journal = array(
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['journal_item'] = $journal_item;
        }

        //Content
        $params = array(
            'trans_item_trans_id' => $id
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'trans_item_date_created';
        $dir        = 'ASC';

        $data['content'] = $this->Transaksi_model->get_all_transaksi_items($params,$search,$limit,$start,$order,$dir);

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'location' => $data['location'],
            'content' => $data['content'],
            'journal' => $data['journal_item'],
            'footer' => array(
                'user_creator' => array(
                    'user_id' => !empty($data['header']['trans_user_id']) ? $data['header']['user_id'] : '-',
                    'user_name' => !empty($data['header']['trans_user_id']) ? $data['header']['user_username'] : '-',
                    'user_phone' => !empty($data['header']['trans_user_id']) ? $data['header']['user_phone_1'] : '-',
                    'user_email' => !empty($data['header']['trans_user_id']) ? $data['header']['user_email_1'] : '-',
                ),
                'user_delivered' => !empty($data['header']['trans_vehicle_person']) ? $this->Kontak_model->get_kontak($data['header']['trans_vehicle_person']): '-'
            )
        );

        // echo json_encode($data['result']);die;

        $session = $this->session->userdata();   
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        //Aktivitas
        $params = array(
            'activity_user_id' => !empty($session_user_id) ? $session_user_id : null,
            'activity_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
            'activity_action' => 6,
            'activity_table' => 'trans',
            'activity_table_id' => $id,
            'activity_text_1' => ucwords(strtolower($data['header']['type_name'])),
            'activity_text_2' => ucwords(strtolower($data['header']['trans_number'])),
            'activity_text_3' => ucwords(strtolower($data['header']['contact_name'])),
            'activity_date_created' => date('YmdHis'),
            'activity_flag' => 0
        );
        $this->save_activity($params);

        //Set Layout From Order Type
        if($data['header']['trans_type']==1){
            $data['title'] = 'Pembelian';
            $this->load->view($this->print_directory.'purchase_buy_with_payment_history',$data);
        }
        else if($data['header']['trans_type']==2){
            $data['title'] = 'Invoice';
            $this->load->view($this->print_directory.'sales_sell',$data);
        }
        else if($data['header']['trans_type']==3){
            $data['title'] = 'Retur Pembelian';
            $this->load->view($this->print_directory.'purchase_return',$data);
        }
        else if($data['header']['trans_type']==4){
            $data['title'] = 'Retur Penjualan';
            $this->load->view($this->print_directory.'sales_return',$data);
        }        
        // else if($data['header']['trans_type']==4){
        //     $data['title'] = 'Checkup Medicine';
        //     $this->load->view($this->print_directory.'checkup_medicine',$data);
        // }
        // else if($data['header']['trans_type']==5){
        //     $data['title'] = 'Checkup Laboratory';
        //     $this->load->view($this->print_directory.'checkup_laboratory',$data);
        // }
        else if($data['header']['trans_type']==5){
            $data['title'] = 'Transfer Stok';
            $this->load->view($this->print_directory.'inventory_transfer_stock',$data);
        }
        else if($data['header']['trans_type']==6){
            $data['title'] = 'Stok Opname +';
            $this->load->view($this->print_directory.'inventory_stock_opname_plus',$data);
        }
        else if($data['header']['trans_type']==7){
            $data['title'] = 'Stok Opname -';
            $this->load->view($this->print_directory.'inventory_stock_opname_minus',$data);
        }
        else if($data['header']['trans_type']==8){
            $data['title'] = 'Produksi';
            $this->load->view($this->print_directory.'production_production',$data);
        }
        else if($data['header']['trans_type']==9){
            $data['title'] = 'Pemakaian Barang';
            $this->load->view($this->print_directory.'inventory_goods_out',$data);
        }
        else if($data['header']['trans_type']==10){
            $data['title'] = 'Pemasukan Barang';
            $this->load->view($this->print_directory.'inventory_goods_in',$data);
        }
        else{
            // $this->load->view('prints/sales_order',$data);
        }
    }
    function print_data($session){ //Standard Print Session
        //Header
        $params = array(
            'trans_session' => $session
        );
        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Transaksi_model->get_transaksi_custom($params);
        $id = $data['header']['trans_id'];
        // var_dump($data['header']);die;
        $data['branch'] = $this->Branch_model->get_branch($data['header']['trans_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }
        $data['journal_item'] = array();

        // Transfer Stok Dokumen
        $data['location'] = array();
        if($data['header']['trans_type'] == 1){
            $params_journal = array(
                'journal_item_type' => 1,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 2){
            $params_journal = array(
                'journal_item_type' => 2,
                'journal_item_trans_id' => $id,
                'journal_item_credit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 5){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                'location_to' => $location_to
            );
        }else if(($data['header']['trans_type'] == 6) or ($data['header']['trans_type'] == 7)){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                // 'location_to' => $location_to
            );
        }else if($data['header']['trans_type'] == 8){
            $params_journal = array(
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['journal_item'] = $journal_item;
        }

        //Content
        $params = array(
            'trans_item_trans_id' => $id
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'trans_item_date_created';
        $dir        = 'ASC';

        $data['content'] = $this->Transaksi_model->get_all_transaksi_items($params,$search,$limit,$start,$order,$dir);

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'location' => $data['location'],
            'content' => $data['content'],
            'journal' => $data['journal_item'],
            'footer' => '',
            'footer' => array(
                'user_creator' => array(
                    'user_id' => !empty($data['header']['trans_user_id']) ? $data['header']['user_id'] : '-',
                    'user_name' => !empty($data['header']['trans_user_id']) ? $data['header']['user_username'] : '-',
                    'user_phone' => !empty($data['header']['trans_user_id']) ? $data['header']['user_phone_1'] : '-',
                    'user_email' => !empty($data['header']['trans_user_id']) ? $data['header']['user_email_1'] : '-',
                ),
                'user_delivered' => !empty($data['header']['trans_vehicle_person']) ? $this->Kontak_model->get_kontak($data['header']['trans_vehicle_person']): '-'
            )            
        );

        // echo json_encode($data['header']);die;

        $session = $this->session->userdata();   
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        //Aktivitas
        $params = array(
            'activity_user_id' => !empty($session_user_id) ? $session_user_id : null,
            'activity_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
            'activity_action' => 6,
            'activity_table' => 'trans',
            'activity_table_id' => $id,
            'activity_text_1' => ucwords(strtolower($data['header']['type_name'])),
            'activity_text_2' => ucwords(strtolower($data['header']['trans_number'])),
            'activity_text_3' => ucwords(strtolower($data['header']['contact_name'])),
            'activity_date_created' => date('YmdHis'),
            'activity_flag' => 0
        );
        $this->save_activity($params);

        $data['say_number'] = !empty($data['header']['trans_total']) ? $this->uppercase($this->say_number($data['header']['trans_total'])) : '-';
        
        //Set Layout From Order Type
        if($data['header']['trans_type']==1){
            $data['title'] = 'Pembelian';
            $this->load->view($this->print_directory.'purchase_buy',$data);
        }
        else if($data['header']['trans_type']==2){
            $data['title'] = 'Invoice';
            $this->load->view($this->print_directory.'sales_sell',$data);
        }
        else if($data['header']['trans_type']==3){
            $data['title'] = 'Retur Pembelian';
            $this->load->view($this->print_directory.'purchase_return',$data);
        }        
        else if($data['header']['trans_type']==4){
            $data['title'] = 'Retur Penjualan';
            $this->load->view($this->print_directory.'sales_return',$data);
        }                
        // else if($data['header']['trans_type']==3){
        //     $data['title'] = 'Price Quotation';
        //     $this->load->view($this->print_directory.'purchase_quotation',$data);
        // }
        // else if($data['header']['trans_type']==4){
        //     $data['title'] = 'Checkup Medicine';
        //     $this->load->view($this->print_directory.'checkup_medicine',$data);
        // }
        // else if($data['header']['trans_type']==5){
        //     $data['title'] = 'Checkup Laboratory';
        //     $this->load->view($this->print_directory.'checkup_laboratory',$data);
        // }
        else if($data['header']['trans_type']==5){
            $data['title'] = 'Transfer Stok';
            $this->load->view($this->print_directory.'inventory_transfer_stock',$data);
        }
        else if($data['header']['trans_type']==6){
            $data['title'] = 'Stok Opname +';
            $this->load->view($this->print_directory.'inventory_stock_opname_plus',$data);
        }
        else if($data['header']['trans_type']==7){
            $data['title'] = 'Stok Opname -';
            $this->load->view($this->print_directory.'inventory_stock_opname_minus',$data);
        }
        else if($data['header']['trans_type']==8){
            $data['title'] = 'Produksi';
            $this->load->view($this->print_directory.'production_production',$data);
        }
        else if($data['header']['trans_type']==9){
            $data['title'] = 'Pemakaian Barang';
            $this->load->view($this->print_directory.'inventory_goods_out',$data);
        }
        else if($data['header']['trans_type']==10){
            $data['title'] = 'Pemasukan Barang';
            $this->load->view($this->print_directory.'inventory_goods_in',$data);
        }
        else{
            // $this->load->view('prints/sales_order',$data);
        }
    }
    function print_history($session){ //Standard Print Session with Payment History
        //Header
        $params = array(
            'trans_session' => $session
        );
        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Transaksi_model->get_transaksi_custom($params);
        $data['trans_history'] = array();
        $id = $data['header']['trans_id'];
        
        $data['branch'] = $this->Branch_model->get_branch($data['header']['trans_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }
        $data['journal_item'] = array();

        // Transfer Stok Dokumen
        $data['location'] = array();
        if($data['header']['trans_type'] == 1){
            $params_journal = array(
                'journal_item_type' => 1,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            /*
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            */
            $data['journal_item'] = $journal_items;

            $trans_history = array();
            $get_trans_history = $this->trans_history($id);
            foreach($get_trans_history as $r):
                $trans_history[] = array(
                    'temp_id' => $r['temp_id'],
                    'temp_date' => $r['temp_date'],
                    'temp_trans_id' => $r['temp_trans_id'],
                    'temp_source_id' => $r['temp_source_id'],
                    'temp_session' => $r['temp_session'],
                    'temp_number' => $r['temp_number'],
                    'temp_type' => $r['temp_type'],
                    'temp_transaction_type' => $r['temp_transaction_type'],
                    'temp_debit' => $r['temp_debit'],
                    'temp_credit' => $r['temp_credit'],
                    'temp_note' => $r['temp_note'],
                    'temp_group_session' => $r['temp_group_session'],
                    'temp_balance' => $r['temp_balance']
                );                
            endforeach;
            $data['trans_history'] = $trans_history;
        }else if($data['header']['trans_type'] == 2){
            $params_journal = array(
                'journal_item_type' => 2,
                'journal_item_trans_id' => $id,
                'journal_item_credit > ' => 0
            );
            $journal_items = array();
            // $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            // foreach($journal_item as $v):
            //     $journal_items[] = array(
            //       'journal_item_id' => intval($v['journal_item_id']),
            //       'journal_item_journal_id' => intval($v['journal_item_journal_id']),
            //       'journal_item_group_session' => $v['journal_item_group_session'],
            //       'journal_item_branch_id' => intval($v['journal_item_branch_id']),
            //       'journal_item_account_id' => intval($v['journal_item_account_id']),
            //       'journal_item_trans_id' => $v['journal_item_trans_id'],
            //       'journal_item_date' => $v['journal_item_date'],
            //       'journal_item_type' => intval($v['journal_item_type']),
            //       'journal_item_type_name' => $v['journal_item_type_name'],
            //       'journal_item_debit' => intval($v['journal_item_debit']),
            //       'journal_item_credit' => intval($v['journal_item_credit']),
            //       'journal_item_note' => $v['journal_item_note'],
            //       'journal_item_user_id' => intval($v['journal_item_user_id']),
            //       'journal_item_date_created' => $v['journal_item_date_created'],
            //       'journal_item_date_updated' => $v['journal_item_date_updated'],
            //       'journal_item_flag' => intval($v['journal_item_flag']),
            //       'journal_item_position' => intval($v['journal_item_position']),
            //       'journal_item_journal_session' => $v['journal_item_journal_session'],
            //       'journal_item_session' => $v['journal_item_session'],
            //       'related' => $this->Journal_model->get_all_journal_item(
            //         array(
            //             'journal_item_group_session' => $v['journal_item_group_session'],
            //             'journal_item_id !=' => $v['journal_item_id']
            //         ),null,null,null,'account_name','asc')
            //     );
            // endforeach;
            // $data['journal_item'] = $journal_items;

            $trans_history = array();
            $get_trans_history = $this->trans_history($id);
            foreach($get_trans_history as $r):
                $trans_history[] = array(
                    'temp_id' => $r['temp_id'],
                    'temp_date' => $r['temp_date'],
                    'temp_trans_id' => $r['temp_trans_id'],
                    'temp_source_id' => $r['temp_source_id'],
                    'temp_session' => $r['temp_session'],
                    'temp_number' => $r['temp_number'],
                    'temp_type' => $r['temp_type'],
                    'temp_transaction_type' => $r['temp_transaction_type'],
                    'temp_debit' => $r['temp_debit'],
                    'temp_credit' => $r['temp_credit'],
                    'temp_note' => $r['temp_note'],
                    'temp_group_session' => $r['temp_group_session'],
                    'temp_balance' => $r['temp_balance']
                );                
            endforeach;
            $data['trans_history'] = $trans_history;

        }else if($data['header']['trans_type'] == 5){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                'location_to' => $location_to
            );
        }else if(($data['header']['trans_type'] == 6) or ($data['header']['trans_type'] == 7)){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                // 'location_to' => $location_to
            );
        }else if($data['header']['trans_type'] == 8){
            $params_journal = array(
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['journal_item'] = $journal_item;
        }

        //Content
        $params = array(
            'trans_item_trans_id' => $id
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'trans_item_date_created';
        $dir        = 'ASC';

        $data['content'] = $this->Transaksi_model->get_all_transaksi_items($params,$search,$limit,$start,$order,$dir);

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'location' => $data['location'],
            'content' => $data['content'],
            'journal' => $data['journal_item'],
            'trans_history' => $data['trans_history'],            
            'footer' => ''
        );

        //echo json_encode($data['header']);die;
        $data['say_number'] = !empty($data['header']['trans_total']) ? $this->uppercase($this->say_number($data['header']['trans_total'])) : '-';
        
        //Set Layout From Order Type
        if($data['header']['trans_type']==1){
            $data['title'] = 'Pembelian & Riwayat Pembayaran';
            $this->load->view($this->print_directory.'purchase_buy_with_payment_history',$data);
        }
        else if($data['header']['trans_type']==2){
            $data['title'] = 'Invoice & Riwayat Pelunasan';
            $this->load->view($this->print_directory.'sales_sell_with_payment_history',$data);
        }
        // else if($data['header']['trans_type']==3){
        //     $data['title'] = 'Price Quotation';
        //     $this->load->view($this->print_directory.'purchase_quotation',$data);
        // }
        // else if($data['header']['trans_type']==4){
        //     $data['title'] = 'Checkup Medicine';
        //     $this->load->view($this->print_directory.'checkup_medicine',$data);
        // }
        // else if($data['header']['trans_type']==5){
        //     $data['title'] = 'Checkup Laboratory';
        //     $this->load->view($this->print_directory.'checkup_laboratory',$data);
        // }
        else if($data['header']['trans_type']==5){
            $data['title'] = 'Transfer Stok';
            $this->load->view($this->print_directory.'inventory_transfer_stock',$data);
        }
        else if($data['header']['trans_type']==6){
            $data['title'] = 'Stok Opname +';
            $this->load->view($this->print_directory.'inventory_stock_opname_plus',$data);
        }
        else if($data['header']['trans_type']==7){
            $data['title'] = 'Stok Opname -';
            $this->load->view($this->print_directory.'inventory_stock_opname_minus',$data);
        }
        else if($data['header']['trans_type']==8){
            $data['title'] = 'Produksi';
            $this->load->view($this->print_directory.'production_production',$data);
        }
        else if($data['header']['trans_type']==9){
            $data['title'] = 'Pemakaian Barang';
            $this->load->view($this->print_directory.'inventory_goods_out',$data);
        }
        else if($data['header']['trans_type']==10){
            $data['title'] = 'Pemasukan Barang';
            $this->load->view($this->print_directory.'inventory_goods_in',$data);
        }
        else{
            // $this->load->view('prints/sales_order',$data);
        }
    }
    function print_delivery($session){ //Standard Print Session
        //Header
        $params = array(
            'trans_session' => $session
        );
        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Transaksi_model->get_transaksi_custom($params);
        $id = $data['header']['trans_id'];
        
        $data['branch'] = $this->Branch_model->get_branch($data['header']['trans_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }
        $data['journal_item'] = array();

        // Transfer Stok Dokumen
        $data['location'] = array();
        if($data['header']['trans_type'] == 1){
            $params_journal = array(
                'journal_item_type' => 1,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 2){
            $params_journal = array(
                'journal_item_type' => 2,
                'journal_item_trans_id' => $id,
                'journal_item_credit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 5){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                'location_to' => $location_to
            );
        }else if(($data['header']['trans_type'] == 6) or ($data['header']['trans_type'] == 7)){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                // 'location_to' => $location_to
            );
        }else if($data['header']['trans_type'] == 8){
            $params_journal = array(
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['journal_item'] = $journal_item;
        }

        //Content
        $params = array(
            'trans_item_trans_id' => $id
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'trans_item_date_created';
        $dir        = 'ASC';

        $data['content'] = $this->Transaksi_model->get_all_transaksi_items($params,$search,$limit,$start,$order,$dir);

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'location' => $data['location'],
            'content' => $data['content'],
            'journal' => $data['journal_item'],
            'footer' => array(
                'user_creator' => array(
                    'user_id' => !empty($data['header']['trans_user_id']) ? $data['header']['user_id'] : '-',
                    'user_name' => !empty($data['header']['trans_user_id']) ? $data['header']['user_username'] : '-',
                    'user_phone' => !empty($data['header']['trans_user_id']) ? $data['header']['user_phone_1'] : '-',
                    'user_email' => !empty($data['header']['trans_user_id']) ? $data['header']['user_email_1'] : '-',
                ),
                'user_delivered' => !empty($data['header']['trans_vehicle_person']) ? $this->Kontak_model->get_kontak($data['header']['trans_vehicle_person']): '-'
            )
        );

        // echo json_encode($data['result']['header']);die;
        $data['say_number'] = !empty($data['header']['trans_total']) ? $this->uppercase($this->say_number($data['header']['trans_total'])) : '-';
        
        //Set Layout From Order Type
        if($data['header']['trans_type']==1){
            $data['title'] = 'Pembelian';
            $this->load->view($this->print_directory.'purchase_buy_delivery',$data);
        }
        else if($data['header']['trans_type']==2){
            $data['title'] = 'Invoice Surat Jalan';
            $this->load->view($this->print_directory.'sales_sell_delivery',$data);
        }
        // else if($data['header']['trans_type']==3){
        //     $data['title'] = 'Price Quotation';
        //     $this->load->view($this->print_directory.'purchase_quotation',$data);
        // }
        // else if($data['header']['trans_type']==4){
        //     $data['title'] = 'Checkup Medicine';
        //     $this->load->view($this->print_directory.'checkup_medicine',$data);
        // }
        // else if($data['header']['trans_type']==5){
        //     $data['title'] = 'Checkup Laboratory';
        //     $this->load->view($this->print_directory.'checkup_laboratory',$data);
        // }
        else if($data['header']['trans_type']==5){
            $data['title'] = 'Transfer Stok';
            $this->load->view($this->print_directory.'inventory_transfer_stock',$data);
        }
        else if($data['header']['trans_type']==6){
            $data['title'] = 'Stok Opname +';
            $this->load->view($this->print_directory.'inventory_stock_opname_plus',$data);
        }
        else if($data['header']['trans_type']==7){
            $data['title'] = 'Stok Opname -';
            $this->load->view($this->print_directory.'inventory_stock_opname_minus',$data);
        }
        else if($data['header']['trans_type']==8){
            $data['title'] = 'Produksi';
            $this->load->view($this->print_directory.'production_production',$data);
        }
        else if($data['header']['trans_type']==9){
            $data['title'] = 'Pemakaian Barang';
            $this->load->view($this->print_directory.'inventory_goods_out',$data);
        }
        else if($data['header']['trans_type']==10){
            $data['title'] = 'Pemasukan Barang';
            $this->load->view($this->print_directory.'inventory_goods_in',$data);
        }
        else{
            // $this->load->view('prints/sales_order',$data);
        }
    }
    function print_group($session){ //Standard Print Group By Product
        //Header
        $params = array(
            'trans_session' => $session
        );
        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Transaksi_model->get_transaksi_custom($params);
        $id = $data['header']['trans_id'];
        
        $data['branch'] = $this->Branch_model->get_branch($data['header']['trans_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }
        $data['journal_item'] = array();

        // Transfer Stok Dokumen
        $data['location'] = array();
        if($data['header']['trans_type'] == 1){
            $params_journal = array(
                'journal_item_type' => 1,
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 2){
            $params_journal = array(
                'journal_item_type' => 2,
                'journal_item_trans_id' => $id,
                'journal_item_credit > ' => 0
            );
            $journal_items = array();
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            foreach($journal_item as $v):
                $journal_items[] = array(
                  'journal_item_id' => intval($v['journal_item_id']),
                  'journal_item_journal_id' => intval($v['journal_item_journal_id']),
                  'journal_item_group_session' => $v['journal_item_group_session'],
                  'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                  'journal_item_account_id' => intval($v['journal_item_account_id']),
                  'journal_item_trans_id' => $v['journal_item_trans_id'],
                  'journal_item_date' => $v['journal_item_date'],
                  'journal_item_type' => intval($v['journal_item_type']),
                  'journal_item_type_name' => $v['journal_item_type_name'],
                  'journal_item_debit' => intval($v['journal_item_debit']),
                  'journal_item_credit' => intval($v['journal_item_credit']),
                  'journal_item_note' => $v['journal_item_note'],
                  'journal_item_user_id' => intval($v['journal_item_user_id']),
                  'journal_item_date_created' => $v['journal_item_date_created'],
                  'journal_item_date_updated' => $v['journal_item_date_updated'],
                  'journal_item_flag' => intval($v['journal_item_flag']),
                  'journal_item_position' => intval($v['journal_item_position']),
                  'journal_item_journal_session' => $v['journal_item_journal_session'],
                  'journal_item_session' => $v['journal_item_session'],
                  'related' => $this->Journal_model->get_all_journal_item(
                    array(
                        'journal_item_group_session' => $v['journal_item_group_session'],
                        'journal_item_id !=' => $v['journal_item_id']
                    ),null,null,null,'account_name','asc')
                );
            endforeach;
            $data['journal_item'] = $journal_items;
        }else if($data['header']['trans_type'] == 5){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                'location_to' => $location_to
            );
        }else if(($data['header']['trans_type'] == 6) or ($data['header']['trans_type'] == 7)){
            $location_from = $this->Lokasi_model->get_lokasi($data['header']['trans_location_id']);
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['location'] = array(
                'location_from' => $location_from,
                // 'location_to' => $location_to
            );
        }else if($data['header']['trans_type'] == 8){
            $params_journal = array(
                'journal_item_trans_id' => $id,
                'journal_item_debit > ' => 0
            );
            $journal_item = $this->Journal_model->get_all_journal_item($params_journal,null,null,null,'account_name','asc');
            // $location_to = $this->Lokasi_model->get_lokasi($data['header']['trans_location_to_id']);
            $data['journal_item'] = $journal_item;
        }

        //Content
        $params = array(
            'trans_item_trans_id' => $id
        );
        $search     = null;
        $limit      = null;
        $start      = null;
        $order      = 'trans_item_date_created';
        $dir        = 'ASC';

        $data['content'] = $this->Transaksi_model->get_all_transaksi_items_group_by_product($params,$search,$limit,$start,$order,$dir);

        $data['result'] = array(
            'branch' => $data['branch'],
            'header' => $data['header'],
            'location' => $data['location'],
            'content' => $data['content'],
            'journal' => $data['journal_item'],
            'footer' => '',
            'footer' => array(
                'user_creator' => array(
                    'user_id' => !empty($data['header']['trans_user_id']) ? $data['header']['user_id'] : '-',
                    'user_name' => !empty($data['header']['trans_user_id']) ? $data['header']['user_username'] : '-',
                    'user_phone' => !empty($data['header']['trans_user_id']) ? $data['header']['user_phone_1'] : '-',
                    'user_email' => !empty($data['header']['trans_user_id']) ? $data['header']['user_email_1'] : '-',
                ),
                'user_delivered' => !empty($data['header']['trans_vehicle_person']) ? $this->Kontak_model->get_kontak($data['header']['trans_vehicle_person']): '-'
            )            
        );

        // echo json_encode($data['result']);die;
        $data['say_number'] = !empty($data['header']['trans_total']) ? $this->uppercase($this->say_number($data['header']['trans_total'])) : '-';
        
        $session = $this->session->userdata();   
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        //Aktivitas
        $params = array(
            'activity_user_id' => !empty($session_user_id) ? $session_user_id : null,
            'activity_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,
            'activity_action' => 6,
            'activity_table' => 'trans',
            'activity_table_id' => $id,
            'activity_text_1' => ucwords(strtolower($data['header']['type_name'])),
            'activity_text_2' => ucwords(strtolower($data['header']['trans_number'])),
            'activity_text_3' => ucwords(strtolower($data['header']['contact_name'])),
            'activity_date_created' => date('YmdHis'),
            'activity_flag' => 0
        );
        $this->save_activity($params);

        //Set Layout From Order Type
        if($data['header']['trans_type']==1){
            $data['title'] = 'Pembelian';
            $this->load->view($this->print_directory.'purchase_buy',$data);
        }
        else if($data['header']['trans_type']==2){
            $data['title'] = 'Invoice';
            $this->load->view($this->print_directory.'sales_sell',$data);
        }
        else if($data['header']['trans_type']==3){
            $data['title'] = 'Retur Pembelian';
            $this->load->view($this->print_directory.'purchase_return',$data);
        }        
        else if($data['header']['trans_type']==4){
            $data['title'] = 'Retur Penjualan';
            $this->load->view($this->print_directory.'sales_return',$data);
        }                
        // else if($data['header']['trans_type']==3){
        //     $data['title'] = 'Price Quotation';
        //     $this->load->view($this->print_directory.'purchase_quotation',$data);
        // }
        // else if($data['header']['trans_type']==4){
        //     $data['title'] = 'Checkup Medicine';
        //     $this->load->view($this->print_directory.'checkup_medicine',$data);
        // }
        // else if($data['header']['trans_type']==5){
        //     $data['title'] = 'Checkup Laboratory';
        //     $this->load->view($this->print_directory.'checkup_laboratory',$data);
        // }
        else if($data['header']['trans_type']==5){
            $data['title'] = 'Transfer Stok';
            $this->load->view($this->print_directory.'inventory_transfer_stock',$data);
        }
        else if($data['header']['trans_type']==6){
            $data['title'] = 'Stok Opname +';
            $this->load->view($this->print_directory.'inventory_stock_opname_plus',$data);
        }
        else if($data['header']['trans_type']==7){
            $data['title'] = 'Stok Opname -';
            $this->load->view($this->print_directory.'inventory_stock_opname_minus',$data);
        }
        else if($data['header']['trans_type']==8){
            $data['title'] = 'Produksi';
            $this->load->view($this->print_directory.'production_production',$data);
        }
        else if($data['header']['trans_type']==9){
            $data['title'] = 'Pemakaian Barang';
            $this->load->view($this->print_directory.'inventory_goods_out',$data);
        }
        else if($data['header']['trans_type']==10){
            $data['title'] = 'Pemasukan Barang';
            $this->load->view($this->print_directory.'inventory_goods_in',$data);
        }
        else{
            // $this->load->view('prints/sales_order',$data);
        }
    }    
    function prints_escpos() { //Sample Print With Library, Work on Local
        // https://mike42.me/blog/what-is-escpos-and-how-do-i-use-it
        // me-load library escpos
        $this->load->library('escpos');

        // membuat connector printer ke shared printer bernama "printer_a" (yang telah disetting sebelumnya)
        $connector = new Escpos\PrintConnectors\WindowsPrintConnector("POS58-Printer");

        // membuat objek $printer agar dapat di lakukan fungsinya
        $printer = new Escpos\Printer($connector);


        /* ---------------------------------------------------------
         * Teks biasa | text()
         */
        // $printer->initialize();
        // $printer->text("Ini teks biasa \n");
        // $printer->text("\n");

        /* ---------------------------------------------------------
         * Select print mode | selectPrintMode()
         */
        // Printer::MODE_FONT_A
        // $printer->initialize();
        // $printer->selectPrintMode(Escpos\Printer::MODE_FONT_A);
        // $printer->text("teks dengan MODE_FONT_A \n");
        // $printer->text("\n");

        // Printer::MODE_FONT_B
        // $printer->initialize();
        // $printer->selectPrintMode(Escpos\Printer::MODE_FONT_B);
        // $printer->text("teks dengan MODE_FONT_B \n");
        // $printer->text("\n");

        // Printer::MODE_EMPHASIZED
        // $printer->initialize();
        // $printer->selectPrintMode(Escpos\Printer::MODE_EMPHASIZED);
        // $printer->text("teks dengan MODE_EMPHASIZED \n");
        // $printer->text("\n");

        // Printer::MODE_DOUBLE_HEIGHT
        // $printer->initialize();
        // $printer->selectPrintMode(Escpos\Printer::MODE_DOUBLE_HEIGHT);
        // $printer->text("teks dengan MODE_DOUBLE_HEIGHT \n");
        // $printer->text("\n");

        // Printer::MODE_DOUBLE_WIDTH
        // $printer->initialize();
        // $printer->selectPrintMode(Escpos\Printer::MODE_DOUBLE_WIDTH);
        // $printer->text("teks dengan MODE_DOUBLE_WIDTH \n");
        // $printer->text("\n");

        // Printer::MODE_UNDERLINE
        // $printer->initialize();
        // $printer->selectPrintMode(Escpos\Printer::MODE_UNDERLINE);
        // $printer->text("teks dengan MODE_UNDERLINE \n");
        // $printer->text("\n");


        /* ---------------------------------------------------------
         * Teks dengan garis bawah  | setUnderline()
         */
        // $printer->initialize();
        // $printer->setUnderline(Escpos\Printer::UNDERLINE_DOUBLE);
        // $printer->text("Ini teks dengan garis bawah \n");
        // $printer->text("\n");

        /* ---------------------------------------------------------
         * Rata kiri, tengah, dan kanan (JUSTIFICATION) | setJustification()
         */
        // Teks rata kiri JUSTIFY_LEFT
        // $printer->initialize();
        // $printer->setJustification(Escpos\Printer::JUSTIFY_LEFT);
        // $printer->text("Ini teks rata kiri \n");
        // $printer->text("\n");

        // // Teks rata tengah JUSTIFY_CENTER
        // $printer->initialize();
        // $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
        // $printer->text("Ini teks rata tengah \n");
        // $printer->text("\n");

        // // Teks rata kanan JUSTIFY_RIGHT
        // $printer->initialize();
        // $printer->setJustification(Escpos\Printer::JUSTIFY_RIGHT);
        // $printer->text("Ini teks rata kanan \n");
        // $printer->text("\n");


        /* ---------------------------------------------------------
         * Font A, B dan C | setFont()
         */
        // Teks dengan font A
        // $printer->initialize();
        // $printer->setFont(Escpos\Printer::FONT_A);
        // $printer->text("Ini teks dengan font A \n");
        // $printer->text("\n");

        // // Teks dengan font B
        // $printer->initialize();
        // $printer->setFont(Escpos\Printer::FONT_B);
        // $printer->text("Ini teks dengan font B \n");
        // $printer->text("\n");

        // // Teks dengan font C
        // $printer->initialize();
        // $printer->setFont(Escpos\Printer::FONT_C);
        // $printer->text("Ini teks dengan font C \n");
        // $printer->text("\n");

        /* ---------------------------------------------------------
         * Jarak perbaris 40 (linespace) | setLineSpacing()
         */
        // $printer->initialize();
        // $printer->setLineSpacing(40);
        // $printer->text("Ini paragraf dengan \nline spacing sebesar 40 \ndi printer dotmatrix \n");
        // $printer->text("\n");

        // /* ---------------------------------------------------------
        //  * Jarak dari kiri (Margin Left) | setPrintLeftMargin()
        //  */
        // $printer->initialize();
        // $printer->setPrintLeftMargin(10);
        // $printer->text("Ini teks berjarak 10 dari kiri (Margin left) \n");
        // $printer->text("\n");

        // /* ---------------------------------------------------------
        //  * membalik warna teks (background menjadi hitam) | setReverseColors()
        //  */
        // $printer->initialize();
        // $printer->setReverseColors(TRUE);
        // $printer->text("Warna Teks ini terbalik \n");
        // $printer->text("\n");

        // $printer->text("Kasir : Badar Wildanie\n");
        // $printer->text("Waktu : 13-10-2019 19:23:22\n");

        // Membuat tabel
        // $printer->initialize(); // Reset bentuk/jenis teks
        // $printer->text("----------------------------------------\n");
        // $printer->text($this->set_wrap_4("Barang", "qty", "Harga", "Subtotal"));
        // $printer->text("----------------------------------------\n");
        // $printer->text($this->set_wrap_4("Makaroni 250gr", "2pcs", "15.000", "30.000"));
        // $printer->text($this->set_wrap_4("Telur", "2pcs", "5.000", "10.000"));
        // $printer->text($this->set_wrap_4("Tepung terigu", "1pcs", "8.200", "16.400"));
        // $printer->text("----------------------------------------\n");
        // $printer->text($this->set_wrap_4('', '', "Total", "56.400"));
        // $printer->text("\n");

        /* Barcodes */
        // $barcodes = array(
        //     Printer::BARCODE_UPCA,
        //     Printer::BARCODE_UPCE,
        //     Printer::BARCODE_JAN13,
        //     Printer::BARCODE_JAN8,
        //     Printer::BARCODE_CODE39,
        //     Printer::BARCODE_ITF,
        //     Printer::BARCODE_CODABAR);
        // $printer->setBarcodeHeight(80);
        // $printer->setBarcodeTextPosition(Printer::BARCODE_CODE39);
        // $printer->barcode("9876");        
        // for($i = 0; $i < count($barcodes); $i++) {
            // $printer->text("Barcode $i " . "\n");
            // $printer->barcode("9876", $barcodes[$i]);
            // $printer->feed();
        // }
        // $printer->cut();
        // $printer->close();

         // Pesan penutup
        // $printer->initialize();
        // $printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
        // $printer->text("Terima kasih telah berbelanja\n");
        // $printer->text("https://google.com\n");

        /* ---------------------------------------------------------
         * Menyelesaikan printer
         */
        // $printer->feed(5); // mencetak 5 baris kosong, agar kertas terangkat ke atas
        // $printer->close();
    }
    function prints_struk($id){ //Print Thermal 58mm Done 
        // $tmpdir         = sys_get_temp_dir();
        // $file           = tempnam($tmpdir, 'ctk');
        // $handle         = fopen($file, 'w');
        // $condensed      = Chr(27) . Chr(33) . Chr(4);
        // $bold1          = Chr(27) . Chr(69);
        // $bold0          = Chr(27) . Chr(70);
        // $initialized    = chr(27).chr(64);
        // $condensed1     = chr(15);
        // $condensed0     = chr(18);
    
        // $text  = $initialized;
        // $text .= $condensed1;

        $text = '';
        $word_wrap_width = 29;

        $get_branch = $this->Branch_model->get_branch(1);
        $get_trans  = $this->Transaksi_model->get_transaksi($id);
        $get_items  = $this->Transaksi_model->get_transaksi_item_custom(array('trans_item_trans_id'=>$get_trans['trans_id']));

        //Process if Data Found
        if($get_trans){
            $paid_type_name = '';
            if($get_trans['trans_paid_type'] > 0){
                $get_type_paid = $this->Type_model->get_type_paid($get_trans['trans_paid_type']);
                $paid_type_name = $get_type_paid['paid_name'];
            }else{
                $paid_type_name = '-'; // Piutang
            }       

            //Header
            $text .= $this->set_wrap_1($get_branch['branch_name']);
            $text .= $this->set_wrap_1($get_branch['branch_address']);
            $text .= $this->set_wrap_1($get_branch['branch_phone_1']);                
            $text .= $this->set_wrap_1($get_trans['trans_number']);
            $text .= $this->set_wrap_1(date("d/m/Y - H:i:s", strtotime($get_trans['trans_date'])));    
            // $text .= $this->set_wrap_2('Cashier',$get_trans['contact_name']);

            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);

            //Content
            $text .= $this->set_wrap_2("Item", "Total");
            $text .= $this->set_line('-',$word_wrap_width);
            foreach($get_items as $v):
                $text .= $v['product_name']."\n";
                $text .= $this->set_wrap_2(' '.number_format($v['trans_item_out_qty'],0,'',',') . ' x '. number_format($v['trans_item_sell_price'],0,'',','), number_format($v['trans_item_sell_total'],0,'',','));            
            endforeach;       

            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);
            $text .= $this->set_wrap_3('SubTotal',':',number_format($get_trans['trans_total'],0,'',','));
            $text .= $this->set_wrap_3('Dibayar',':',number_format($get_trans['trans_received'],0,'',','));
            $text .= $this->set_wrap_3('Kembali',':',number_format($get_trans['trans_change'],0,'',','));     
            $text .= $this->set_wrap_3('Pembayaran',':',$paid_type_name);       
            
            //Footer
            $text .= "\n";
            $text .= $this->set_wrap_1("Terima kasih atas kunjungannya");
            $text .= $this->set_wrap_1("Barang yang sudah di beli tidak dapat ditukar kembali");        

            //Save to Print Spoiler
            $params = array(
                'spoiler_content' => $text, 'spoiler_source_table' => 'trans',
                'spoiler_source_id' => $id, 'spoiler_flag' => 0, 'spoiler_date' => date('YmdHis')
            );
            $this->Print_spoiler_model->add_print_spoiler($params);
        }else{
            $text = "Transaksi tidak ditemukan\n";
        }

        //Open / Write to print.txt
        $file = fopen("print.txt", "w") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);

        // $text .= $justify."\n";
        // $text .= "Toko Logam Mulia\n";
        // $text .= "Puri Anjasmoro No 54\n";
        $text .= chr(27).chr(10);

        //Write and Save
        fwrite($file,$text);
        // fclose($file);

        if(fclose($file)){
            echo json_encode(array('status'=>1,'print_url'=>base_url('print.txt')));
        }else{
            echo json_encode(array('status'=>0,'message'=>'Print raw error'));
        }

        //Preview to HTML
        // $this->output->set_content_type('text/plain', 'UTF-8');
        // $this->output->set_output($text);
        
        //Need Activate to Copy File into Print Enqueue
        // copy($file, "//localhost/printer-share-name"); # Do Print
        // unlink($file);
    }
    function prints_struk_bengkel($id){ //Print Thermal 58mm Done
        // $tmpdir         = sys_get_temp_dir();
        // $file           = tempnam($tmpdir, 'ctk');
        // $handle         = fopen($file, 'w');
        // $condensed      = Chr(27) . Chr(33) . Chr(4);
        // $bold1          = Chr(27) . Chr(69);
        // $bold0          = Chr(27) . Chr(70);
        // $initialized    = chr(27).chr(64);
        // $condensed1     = chr(15);
        // $condensed0     = chr(18);
    
        // $text  = $initialized;
        // $text .= $condensed1;

        $text = '';
        $word_wrap_width = 29;

        $get_branch = $this->Branch_model->get_branch(1);
        $get_trans  = $this->Transaksi_model->get_transaksi($id);
        $get_items  = $this->Transaksi_model->get_transaksi_item_custom(array('trans_item_trans_id'=>$get_trans['trans_id']));

        //Process if Data Found
        if($get_trans){
            $paid_type_name = '';
            if($get_trans['trans_paid_type'] > 0){
                $get_type_paid = $this->Type_model->get_type_paid($get_trans['trans_paid_type']);
                $paid_type_name = $get_type_paid['paid_name'];
            }else{
                $paid_type_name = '-'; // Piutang
            }   

            //Header
            $text .= $this->set_wrap_1($get_branch['branch_name']);
            $text .= $this->set_wrap_1($get_branch['branch_address']);
            $text .= $this->set_wrap_1($get_branch['branch_phone_1']);                
            $text .= $this->set_wrap_1($get_trans['trans_number']);
            $text .= $this->set_wrap_1(date("d/m/Y - H:i:s", strtotime($get_trans['trans_date'])));    
            // $text .= $this->set_wrap_2('Cashier',$get_trans['contact_name']);
                                    
            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);

            //Content
            $text .= $this->set_wrap_2("Item", "Total");
            $text .= $this->set_line('-',$word_wrap_width);
            foreach($get_items as $v):
                $text .= $v['product_name']."\n";
                $text .= $this->set_wrap_2(' '.number_format($v['trans_item_out_qty'],0,'',',') . ' x '. number_format($v['trans_item_sell_price'],0,'',','), number_format($v['trans_item_sell_total'],0,'',','));            
            endforeach;       

            $text .= "\n";
            $text .= $this->set_line('-',$word_wrap_width);
            $text .= $this->set_wrap_3('SubTotal',':',number_format($get_trans['trans_total'],0,'',','));
            $text .= $this->set_wrap_3('Dibayar',':',number_format($get_trans['trans_received'],0,'',','));
            $text .= $this->set_wrap_3('Kembali',':',number_format($get_trans['trans_change'],0,'',','));     
            $text .= $this->set_wrap_3('Pembayaran',':',$paid_type_name);       
            
            //Footer
            $text .= "\n";
            // $text .= !empty($get_trans['trans_contact_phone']) ? $this->set_wrap_3('Tel',':',$get_trans['trans_contact_phone']) : ""; 
            $text .= !empty($get_trans['trans_vehicle_brand']) ? $this->set_wrap_3('Merk',':',$get_trans['trans_vehicle_brand']) : "";              
            $text .= !empty($get_trans['trans_vehicle_plate_number']) ? $this->set_wrap_3('Plat',':',$get_trans['trans_vehicle_plate_number']) : "";               
            $text .= ($get_trans['trans_vehicle_distance'] > 0) ? $this->set_wrap_3('KM',':',$get_trans['trans_vehicle_distance']) : "";    
            $text .= "\n";            
            $text .= !empty($get_trans['trans_note']) ? $this->set_wrap_1($get_trans['trans_note']) : "";                    
            $text .= "\n";
            $text .= $this->set_wrap_1("Terima kasih atas kunjungannya");
            $text .= $this->set_wrap_1("Barang yang sudah di beli tidak dapat ditukar kembali");        

            //Save to Print Spoiler
            $params = array(
                'spoiler_content' => $text, 'spoiler_source_table' => 'trans',
                'spoiler_source_id' => $id, 'spoiler_flag' => 0, 'spoiler_date' => date('YmdHis')
            );
            $this->Print_spoiler_model->add_print_spoiler($params);
        }else{
            $text = "Transaksi tidak ditemukan\n";
        }

        //Open / Write to print.txt
        $file = fopen("print.txt", "w") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);

        // $text .= $justify."\n";
        // $text .= "Toko Logam Mulia\n";
        // $text .= "Puri Anjasmoro No 54\n";
        $text .= chr(27).chr(10);

        //Write and Save
        fwrite($file,$text);
        // fclose($file);

        if(fclose($file)){
            echo json_encode(array('status'=>1,'print_url'=>base_url('print.txt')));
        }else{
            echo json_encode(array('status'=>0,'message'=>'Print raw error'));
        }

        //Preview to HTML
        // $this->output->set_content_type('text/plain', 'UTF-8');
        // $this->output->set_output($text);
        
        //Need Activate to Copy File into Print Enqueue
        // copy($file, "//localhost/printer-share-name"); # Do Print
        // unlink($file);
    }
    function prints_share($printer_id, $trans_id){

        // http://localhost/git/jrn/transaksi/prints_struk/2/1
        // https://escpos.readthedocs.io/en/latest/font_cmds.html
        // https://theasciicode.com.ar/ascii-printable-characters/capital-letter-n-uppercase-ascii-code-78.html#:~:text=To%20get%20the%20letter%2C%20character,%22N%22%20in%20ASCII%20table.
        // https://www.sparkag.com.br/wp-content/uploads/2016/06/ESC_POS-AK912-English-Command-Specifications-V1.4.pdf
        
        // Get Necessary Additional
        $get_printer = $this->Printer_model->get_printer_item($printer_id);
        $get_branch  = $this->Branch_model->get_branch(1);
        $get_trans   = $this->Transaksi_model->get_transaksi($trans_id);
        $get_items   = $this->Transaksi_model->get_transaksi_item_custom(array('trans_item_trans_id'=>$get_trans['trans_id']));

        // Initial ESC/POS 
        // ASCI = ESC @
        // DECIMAL = chr(27) . chr(64)
        //ASCI Control 
        $esc_init       = chr(27).chr(64);
        $esc_print_mode = chr(27).chr(33).chr(48);
        $esc_line_break = chr(10); 
        $esc_bell       = chr(07);
        $esc_cut_paper  = chr(27).chr(109);

        $esc_underline = chr(27).chr(45).chr(50);
        // $tmpdir         = sys_get_temp_dir();
        // $file           = tempnam($tmpdir, 'ctk');
        // $handle         = fopen($file, 'w');
        // $condensed      = Chr(27) . Chr(33) . Chr(4);
        // $bold1          = Chr(27) . Chr(69);
        // $bold0          = Chr(27) . Chr(70);
        // $initialized    = chr(27) . chr(64);
        // $condensed1     = chr(15);
        // $condensed0     = chr(18);
        
        $text = '';
        $text .= $esc_init.$esc_line_break;
        // $text .= $esc_print_mode;
        // $text .= chr(27).chr(85);        
        // $text .= chr(27).chr(69).chr(49).'text'.chr(27).chr(69).chr(48).chr(27).chr(52).chr(49).'text'.chr(27).chr(52).chr(48);
        // $text .= chr(76).chr(70).chr(76).chr(70).chr(76).chr(70);
        // $text .= chr(10).chr(10);

        // $text = '';
        $word_wrap_width = 29;

        //Process if Data Found
        if($get_trans){
            $paid_type_name = '';
            if($get_trans['trans_paid_type'] > 0){
                $get_type_paid = $this->Type_model->get_type_paid($get_trans['trans_paid_type']);
                $paid_type_name = $get_type_paid['paid_name'];
            }else{
                $paid_type_name = '-'; // Piutang
            }

            // //Header
            //     $text .= $this->set_wrap_1($get_branch['branch_name']);
            //     $text .= $this->set_wrap_1($get_branch['branch_address']);
            //     $text .= $this->set_wrap_1($get_branch['branch_phone_1']);                
            //     $text .= $this->set_wrap_1($get_trans['trans_number']);
            //     $text .= $this->set_wrap_1(date("d/m/Y - H:i:s", strtotime($get_trans['trans_date'])));    
            //     // $text .= $this->set_wrap_2('Cashier',$get_trans['contact_name']);
            //     $text .= "\n";
            //     $text .= $this->set_line('-',$word_wrap_width);

            // //Content
            //     $text .= $this->set_wrap_2("Item", "Total");
            //     $text .= $this->set_line('-',$word_wrap_width);
            //     foreach($get_items as $v):
            //         $text .= $v['product_name']."\n";
            //         $text .= $this->set_wrap_2(' '.number_format($v['trans_item_out_qty'],0,'',',') . ' x '. number_format($v['trans_item_sell_price'],0,'',','), number_format($v['trans_item_sell_total'],0,'',','));            
            //     endforeach;       
            //     $text .= "\n";
            //     $text .= $this->set_line('-',$word_wrap_width);
            //     $text .= $this->set_wrap_3('SubTotal',':',number_format($get_trans['trans_total'],0,'',','));
            //     $text .= $this->set_wrap_3('Dibayar',':',number_format($get_trans['trans_received'],0,'',','));
            //     $text .= $this->set_wrap_3('Kembali',':',number_format($get_trans['trans_change'],0,'',','));     
            //     $text .= $this->set_wrap_3('Pembayaran',':',$paid_type_name);       
            
            // //Footer
                // $text .= $esc_underline."Works on ".$get_printer['printer_name'].' => '.$get_printer['printer_ip'];
            //     $text .= $this->set_wrap_1("Terima kasih atas kunjungannya");
            //     $text .= $this->set_wrap_1("Barang yang sudah di beli tidak dapat ditukar kembali");      

                //90 Rotation
                // $text .= chr(27).chr(33).chr(49);
                // $text .= chr(27).chr(52).chr(49);
                // $text .= ' UNDERLINE ';
                // $text .= chr(27).chr(52).chr(48);

                $text .= chr(29).chr(125).chr(37).chr(107);
                $text .= ' 123214214';

                $text .= $esc_line_break; 
                // $text .= $esc_bell;  
                // $text .= chr(27).chr(45).chr(49).' ABS '.chr(27).chr(45).chr(48);
                // $text .= $esc_cut_paper;

            //Save to Print Spoiler
            $params = array(
                'spoiler_content' => $text, 'spoiler_source_table' => 'trans',
                'spoiler_source_id' => $trans_id, 'spoiler_flag' => 0, 'spoiler_date' => date('YmdHis')
            );
            $this->Print_spoiler_model->add_print_spoiler($params);
        }else{
            $text = "Transaksi tidak ditemukan\n";
        }

        //Open / Write to print.txt
        $file = fopen("print.txt", "w") or die("Unable to open file");
        // $justify = chr(27) . chr(64) . chr(27) . chr(97). chr(1);

        // $text .= $justify."\n";
        // $text .= "Toko Logam Mulia\n";
        // $text .= "Puri Anjasmoro No 54\n";
        // $text .= chr(27).chr(10);

        //Write and Save
        fwrite($file,$text);

        if(fclose($file)){
            // echo json_encode(array('status'=>1,'print_url'=>base_url('print.txt')));
        }else{
            // echo json_encode(array('status'=>0,'message'=>'Print raw error'));
        }

        //Preview to HTML
        $this->output->set_content_type('text/plain', 'UTF-8');
        $this->output->set_output($text);
        
        //Need Activate to Copy File into Print Enqueue
        // var_dump(base_url('print.txt'));die;
        $file = base_url('print.txt');
        $printer_location = "//".$get_printer['printer_ip']."/".$get_printer['printer_name']."";
        // var_dump(file_get_contents($file),$file,', ',$printer_location);die;
        // copy(file_get_contents($file), $printer_location); # Do Print
        
        // Localhost
        copy($file, $printer_location); # Do Print
        // unlink($file);
    }    
}

?>