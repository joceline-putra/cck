<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends MY_Controller{

    var $menu_id = 43;
    var $folder_location = array(
        // '0' => array(
        //     'title' => 'Statistik',
        //     'view' => 'layouts/admin/menu/finance/statistic',
        //     'javascript' => 'layouts/admin/menu/finance/statistic_js'
        // ),
        '0' => array( //Saldo Awal
            'parent_id' => 43,
            'title' => 'Saldo Awal',
            'view' => 'layouts/admin/menu/finance/opening_balance',
            'javascript' => 'layouts/admin/menu/finance/opening_balance_js',
            'print_title' => 'Saldo Awal',
            'print' => 'layouts/admin/menu/prints/finance_opening_balance'
        ),        
        '1' => array( //Bayar Hutang (Pembelian)
            'parent_id' => 40,
            'title' => 'Bayar Hutang Pembelian',
            'view' => 'layouts/admin/menu/purchase/account_payable',
            'javascript' => 'layouts/admin/menu/purchase/account_payable_js',
            'print_title' => 'Pembayaran Hutang Pembelian',
            'print' => 'layouts/admin/menu/prints/finance_account_payable'
        ),
        '2' => array( //Terima Piutang (Penjualan)
            'parent_id' => 39,             
            'title' => 'Bayar Piutang Penjualan',
            'view' => 'layouts/admin/menu/sales/account_receivable',
            'javascript' => 'layouts/admin/menu/sales/account_receivable_js',
            'print_title' => 'Pelunasan Piutang Penjualan',
            'print' => 'layouts/admin/menu/prints/finance_account_receivable'
        ),
        '3' => array( //Terima Uang
            'parent_id' => 43,             
            'title' => 'Terima Uang',
            'view' => 'layouts/admin/menu/finance/cash_in',
            'javascript' => 'layouts/admin/menu/finance/cash_in_js',
            'print_title' => 'Penerimaan Uang',
            'print' => 'layouts/admin/menu/prints/finance_cash_in'
        ),
        '4' => array( //Biaya
            'parent_id' => 43,
            'title' => 'Biaya',
            'view' => 'layouts/admin/menu/finance/cost_out',
            'javascript' => 'layouts/admin/menu/finance/cost_out_js',
            'print_title' => 'Transaksi Biaya',
            'print' => 'layouts/admin/menu/prints/finance_cost_out'
        ),
        '5' => array( //Mutasi Kas Bank
            'parent_id' => 43,
            'title' => 'Transfer Bank',
            'view' => 'layouts/admin/menu/finance/bank_statement',
            'javascript' => 'layouts/admin/menu/finance/bank_statement_js',
            'print_title' => 'Transfer Uang',
            'print' => 'layouts/admin/menu/prints/finance_bank_statement'
        ),        
        '6' => array( //Uang Muka Beli
            'parent_id' => 40,
            'title' => 'Down Payment Pembelian',
            'view' => 'layouts/admin/menu/purchase/prepaid_expense',
            'javascript' => 'layouts/admin/menu/purchase/prepaid_expense_js',
            'print_title' => 'Uang Muka Pembelian',
            'print' => 'layouts/admin/menu/prints/finance_prepaid_expense'            
        ),        
        '7' => array( //Uang Muka Jual
            'parent_id' => 39,
            'title' => 'Down Payment Penjualan',
            'view' => 'layouts/admin/menu/sales/down_payment',
            'javascript' => 'layouts/admin/menu/sales/down_payment_js',
            'print_title' => 'Uang Muka Penjualan',
            'print' => 'layouts/admin/menu/prints/finance_down_payment'            
        ),
        '8' => array( //Jurnal Umum
            'parent_id' => 43,
            'title' => 'Jurnal Umum',
            'view' => 'layouts/admin/menu/finance/general_journal',
            'javascript' => 'layouts/admin/menu/finance/general_journal_js',
            'print_title' => 'Jurnal Umum',
            'print' => 'layouts/admin/menu/prints/finance_general_journal'
        ),
        '9' => array( //Kirim Uang DIE;
            'parent_id' => 43,
            'title' => 'Kirim Uang',
            'view' => 'layouts/admin/menu/finance/cash_out',
            'javascript' => 'layouts/admin/menu/finance/cash_out_js',
            'print_title' => 'Pengiriman Uang',
            'print' => 'layouts/admin/menu/prints/finance_cash_out'
        )
    );

    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));            
        }
        // $autoload['helper'] = array('csrf', 'form', 'security');        
        // $this->load->model('Satuan_model');
        // $this->load->model('Gudang_model');
        // $this->load->model('Golongan_obat_model');
        // $this->load->model('Diagnosa_model');
        // $this->load->model('Jenis_praktik_model');
        $this->load->model('User_model');     
        $this->load->model('Kontak_model');             
        $this->load->model('Menu_model');             
        $this->load->model('Account_model');
        $this->load->model('Account_map_model');        
        $this->load->model('Journal_model');             
        $this->load->model('Aktivitas_model');             
        $this->load->model('Print_spoiler_model'); 
        $this->load->model('Branch_model');    
        $this->load->model('Transaksi_model');            

        $this->load->library('form_validation');
        $this->load->helper('form');
    } 
    function tes(){
        $action = $this->input->post('action');
        $datass = json_decode($action, TRUE);  
        print_r($this->input->post());
        // var_dump($datass,$action,$_POST);die;
    }
    function index(){
        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        //Date Now
        $datenow =date("d-m-Y"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        
        //Sub Navigation
        $params_menu = array(
            'menu_parent_id' => $this->folder_location[$identity]['parent_id'],
            'menu_flag' => 1
        );
        $get_menu = $this->Menu_model->get_all_menus($params_menu,null,null,null,'menu_sorting','asc');
        $data['navigation'] = !empty($get_menu) ? $get_menu : [];

        // var_dump($this->folder_location[$identity]['title']);die;
        // var_dump($data['navigation']);die;
        
        $data['identity'] = 0;        
        $data['title'] = 'Statistik';
        $data['_view'] = 'layouts/admin/menu/finance/statistic';
        $this->load->view('layouts/admin/index',$data);
        $this->load->view('layouts/admin/menu/finance/statistic_js.php',$data);
    }
    function pages($identity){
        $session = $this->session->userdata();        
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        $session_group_id = !empty($session['user_data']['user_group_id']) ? $session['user_data']['user_group_id'] : null;

        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

        //Sub Navigation
        $params_menu = array(
            'menu_parent_id' => $this->folder_location[$identity]['parent_id'],
            'menu_flag' => 1
        );
        $get_menu = $this->Menu_model->get_all_menus($params_menu,null,null,null,'menu_sorting','asc');
        $data['navigation'] = !empty($get_menu) ? $get_menu : [];

        $params_branch = [
            'branch_flag' => 1
        ];

        if($session_group_id > 2){ //Kecuali Super Admin
            $params_branch['branch_id'] = $session_branch_id;
        }
        // var_dump($params_branch,$session_group_id);die;
        $data['branch'] = $this->Branch_model->get_all_branch($params_branch,null,null,null,'branch_name','asc');
        
        // var_dump($this->folder_location[$identity]['title']);die;
        // var_dump($data['navigation']);die;
        if($identity == 7){
            $params = array(
                'account_map_branch_id' => $session_branch_id,
                'account_map_for_transaction' => 2,
                'account_map_type' => 5
            );
            $data['account_cashback'] = $this->Account_map_model->get_all_account_map($params,null,null,null,null,null);
        }
        $data['identity'] = $identity;
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['_view'] = $this->folder_location[$identity]['view'];
        $file_js = $this->folder_location[$identity]['javascript'];
        $data['operator'] = '';
        $data['post_contact'] = 0;        
        // var_dump($data['identity']);die;
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        //Date Now
        $datenow =date("d-m-Y"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        // var_dump($data['first_date'],$data['end_date']);die;
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

        // var_dump($this->folder_location[$identity]['title']);die;
        // var_dump($data['navigation']);die;
        
        $data['identity'] = $identity;
        $data['title'] = $this->folder_location[$identity]['title'];
        $data['_view'] = $this->folder_location[$identity]['view'];
        $file_js = $this->folder_location[$identity]['javascript'];
        
        $data['operator'] = $operator;
        $data['post_contact'] = 0;

        //For Account Payable & Account Receivable
        $data['contact'] = !empty($this->input->post('contact')) ? $this->input->post('contact') : 0;
        if(intval($data['contact']) > 0){
            $get_contact = $this->Kontak_model->get_kontak($data['contact']);
            $data['post_contact'] = $get_contact['contact_id'];
        }

        // var_dump($data['post_contact']);die;
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        //Date Now
        $datenow =date("d-m-Y"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;
        
        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
    function manage(){
        $session = $this->session->userdata();        
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];
        $session_group_id = !empty($session['user_data']['user_group_id']) ? $session['user_data']['user_group_id'] : null;

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';      

        if($this->input->post('action')){
            $action = $this->input->post('action');
            $post_data = $this->input->post('data');
            $data = json_decode($post_data, TRUE);            
            $identity = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];
            $journal_id = !empty($this->input->post('journal_id')) ? $this->input->post('journal_id') : 0;            

            //Journal Tipe
            if($identity == 0){ // Saldo Awal
                $set_tipe = 0;
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1
                );                       
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                );                                   
            }            
            if($identity == 1){ // Bayar Hutang / Account Payable 
                $set_tipe = 1;
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
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1                    
                );                             
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note',
                    '4' => 'contact_name'
                );                                        
            }
            if($identity == 2){ // Bayar Piutang / Account Receivable 
                $set_tipe = 2;
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
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1                    
                );                             
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note',
                    '4' => 'contact_name'                    
                );                                        
            }            
            if($identity == 3){ // Terima Uang / Cash In 
                $set_tipe = 3;
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
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1                    
                );                  
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note',
                    '4' => 'contact_name'                    
                );                                        
            }
            if($identity == 4){ // Biaya / Cost Out
                $set_tipe = 4;
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
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    // 'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1                    
                );                             
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note',
                    '4' => 'contact_name'
                );                            
            }
            if($identity == 5){ // Mutasi Kas Bank / Bank Statement 
                $set_tipe = 5;
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
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note'                  
                );                            
            }
            if($identity == 6){ // Uang Muka Beli / Prepaid Expense 
                $set_tipe = 6;
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
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1                    
                );                  
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note',
                    '4' => 'contact_name'                    
                );                                        
            }
            if($identity == 7){ // Uang Muka Jual / Down Payment 
                $set_tipe = 7;
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
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1                    
                );                  
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note',
                    '4' => 'contact_name'                    
                );                                        
            }                        
            if($identity == 8){ // Jurnal Umum / General Journal 
                $set_tipe = 34;
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1
                );                       
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                );                                   
            }
            if($identity == 9){ // Kirim Uang / Cash Out
                $set_tipe = 9;
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
                $params_journal_items = array(
                    'journal_item_journal_id' => $journal_id,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_position' => 2,
                    'journal_item_flag' => 1                    
                );                      
                $columns = array(
                    '0' => 'journal_date',
                    '1' => 'journal_number',
                    '2' => 'journal_total',
                    '3' => 'journal_note',
                    '4' => 'contact_name'  
                );                                        
            }
                
            switch($action){
                case "create": /* Last Check: Add Branch_id */
                    // die;
                    // $journal_item_debit = $data['jumlah'];
                    // $journal_item_credit = $data['jumlah'];                
                    $journal_number = !empty($data['nomor']) ? $data['nomor'] : $this->request_number_for_journal($identity);                
                    $journal_date = date('Y-m-d H:i:s', strtotime($data['tgl'].date('H:i:s')));
                    
                    $journal_account = !empty($data['akun']) ? $data['akun'] : null;
                    $journal_paid_type = !empty($data['cara_pembayaran']) ? $data['cara_pembayaran'] : null;
                    $journal_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                    $journal_note = !empty($data['keterangan']) ? $data['keterangan'] : null;
                    $journal_total = !empty($data['jumlah']) ? str_replace(',','',$data['jumlah']) : null;

                    $journal_session = $this->random_session(20);
                    //For Account Payable / Receivable 
                    $journal_trans_list = !(empty($data['trans_list'])) ? $data['trans_list'] : null;

                    //Cashback
                    $cashback = !empty($data['cashback_account']) ? intval($data['cashback_account']) : 0;
                    $cashback_value = !empty($data['cashback_value']) ? str_replace(',','',$data['cashback_value']) : 0;

                    //JSON Strigify Post
                    $params = array(
                        'journal_type' => !empty($identity) ? $identity : null,
                        'journal_contact_id' => !empty($journal_contact) ? $journal_contact : null,
                        'journal_number' => !empty($journal_number) ? $journal_number : null,
                        'journal_date' => !empty($journal_date) ? $journal_date : null,
                        'journal_note' => !empty($journal_note) ? $journal_note : null,
                        'journal_account_id' => !empty($journal_account) ? $journal_account : null,
                        'journal_total' => !empty($journal_total) ? $journal_total : 0,
                        'journal_paid_type' => !empty($journal_paid_type) ? $journal_paid_type : null,
                        'journal_date_created' => date("YmdHis"),
                        'journal_date_updated' => date("YmdHis"),
                        'journal_user_id' => !empty($session_user_id) ? $session_user_id : null,
                        'journal_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,                    
                        'journal_flag' => 1,
                        'journal_session' => $journal_session,
                        'journal_cashback' => $cashback
                    );
                    // var_dump($params);die;

                    //Check Data Exist
                    $params_check = array(
                        'journal_number' => $journal_number,
                        'journal_branch_id' => $session_branch_id,
                        'journal_type' => $identity
                    );

                    $check_exists = $this->Journal_model->check_data_exist($params_check);
                    if($check_exists==false){
                        $set_data=$this->Journal_model->add_journal($params);
                        if($set_data==true){
                            $journal_id = $set_data;
                            $insert_journal_item_debit = 0;             
                            $insert_journal_item_kredit = 0;                     

                            // Save Journal Item From Journal Header
                            if($identity == 4 or $identity == 9){ // Biaya , Kirim Uang
                                $get_journal_item = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id);
                                $total = 0;
                                foreach($get_journal_item as $c){
                                    // $params_kredit = array(
                                    //     'journal_item_journal_id' => $journal_id,
                                    //     'journal_item_branch_id' => $session_branch_id,
                                    //     'journal_item_account_id' => $c['journal_item_account_id'],
                                    //     'journal_item_type' => $identity,                            
                                    //     // 'journal_item_trans_id' => '',
                                    //     'journal_item_date' => date('YmdHis'),
                                    //     'journal_item_date_created' => date('YmdHis'),                    
                                    //     'journal_item_date_updated' => date('YmdHis'),
                                    //     'journal_item_user_id' => $session_user_id,
                                    //     'journal_item_flag' => 1,
                                    //     'journal_item_note' => $c['journal_item_note'],
                                    //     'journal_item_debit' => $c['journal_item_debit'],
                                    //     'journal_item_credit' => 0,
                                    //     'journal_item_position' => 2
                                    // );  
                                    // $insert_journal_item_debit = $this->Journal_model->add_journal_item($params_kredit);
                                    $total = $total + $c['journal_item_debit'];                                
                                    $params_kredit = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_date' => $journal_date,
                                        'journal_item_flag' => 1
                                    );
                                    $update_journal_item = $this->Journal_model->update_journal_item($c['journal_item_id'],$params_kredit);
                                }                            
                                $params_kredit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_type' => $identity,
                                    // 'journal_item_trans_id' => '',
                                    'journal_item_date' => $journal_date,
                                    'journal_item_date_created' => date('YmdHis'),
                                    'journal_item_date_updated' => date('YmdHis'),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_debit' => 0,
                                    'journal_item_credit' => $total,
                                    'journal_item_position' => 1
                                );                               
                                $insert_journal_item_kredit = $this->Journal_model->add_journal_item($params_kredit);
                                $update_journal = $this->Journal_model->update_journal($journal_id,array('journal_total'=>$total));
                            }
                            
                            if($identity == 3){ // Terima Uang
                                $get_journal_item = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id);
                                $total = 0;
                                foreach($get_journal_item as $c){
                                    // $params_kredit = array(
                                    //     'journal_item_journal_id' => $journal_id,
                                    //     'journal_item_branch_id' => $session_branch_id,
                                    //     'journal_item_account_id' => $c['journal_item_account_id'],
                                    //     'journal_item_type' => $identity,                            
                                    //     // 'journal_item_trans_id' => '',
                                    //     'journal_item_date' => date('YmdHis'),
                                    //     'journal_item_date_created' => date('YmdHis'),                    
                                    //     'journal_item_date_updated' => date('YmdHis'),
                                    //     'journal_item_user_id' => $session_user_id,
                                    //     'journal_item_flag' => 1,
                                    //     'journal_item_note' => $c['journal_item_note'],
                                    //     'journal_item_debit' => $c['journal_item_debit'],
                                    //     'journal_item_credit' => 0,
                                    //     'journal_item_position' => 2
                                    // );  
                                    // $insert_journal_item_debit = $this->Journal_model->add_journal_item($params_kredit);
                                    $total = $total + $c['journal_item_credit'];                                
                                    $params_kredit = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_date' => $journal_date,
                                        'journal_item_flag' => 1
                                    );
                                    $update_journal_item = $this->Journal_model->update_journal_item($c['journal_item_id'],$params_kredit);
                                }                            
                                $params_kredit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_type' => $identity,
                                    // 'journal_item_trans_id' => '',
                                    'journal_item_date' => $journal_date,
                                    'journal_item_date_created' => date('YmdHis'),
                                    'journal_item_date_updated' => date('YmdHis'),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_debit' => $total,
                                    'journal_item_credit' => 0,
                                    'journal_item_position' => 1
                                );                               
                                $insert_journal_item_kredit = $this->Journal_model->add_journal_item($params_kredit);
                                $update_journal = $this->Journal_model->update_journal($journal_id,array('journal_total'=>$total));
                            }

                            if($identity == 5){ // Transfer Uang
                                $journal_account_debit = !empty($data['akun_debit']) ? $data['akun_debit'] : null;
                                $journal_account_credit = !empty($data['akun_kredit']) ? $data['akun_kredit'] : null;
                                $journal_total = !empty($data['jumlah']) ? str_replace(',','',$data['jumlah']) : 0;

                                $params_debit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,
                                    'journal_item_account_id' => $journal_account_debit,
                                    'journal_item_type' => $identity,                            
                                    // 'journal_item_trans_id' => '',
                                    'journal_item_date' => $journal_date,
                                    'journal_item_date_created' => date('YmdHis'),                    
                                    'journal_item_date_updated' => date('YmdHis'),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_debit' => $journal_total,
                                    'journal_item_credit' => 0,
                                    'journal_item_position' => 2
                                );
                                $params_kredit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,
                                    'journal_item_account_id' => $journal_account_credit,
                                    'journal_item_type' => $identity,                            
                                    // 'journal_item_trans_id' => '',
                                    'journal_item_date' => $journal_date,
                                    'journal_item_date_created' => date('YmdHis'),                    
                                    'journal_item_date_updated' => date('YmdHis'),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_debit' => 0,
                                    'journal_item_credit' => $journal_total,
                                    'journal_item_position' => 2
                                );                                                           
                                $insert_journal_item_debit = $this->Journal_model->add_journal_item($params_debit);
                                $insert_journal_item_kredit = $this->Journal_model->add_journal_item($params_kredit);  

                                $journal_item_debit_id = $insert_journal_item_debit;
                                $journal_item_kredit_id = $insert_journal_item_kredit;
                                $return->result_detail = array(
                                    'journal_item_debit_id' => $journal_item_debit_id,
                                    'journal_item_credit_id' => $journal_item_kredit_id
                                );                            
                            }                        

                            if($identity == 8){ // Jurnal Umum
                                $get_journal_item = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id);
                                $total = 0;
                                foreach($get_journal_item as $i){
                                    $journal_item_id = $i['journal_item_id'];
                                    $params_item = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_flag' => 1
                                    );  
                                    $update_journal_item = $this->Journal_model->update_journal_item($journal_item_id,$params_item);      
                                    $total = $total + $i['journal_item_debit'];                                                         
                                }                            
                                // $insert_journal_item = $this->Journal_model->add_journal_item($params_item);
                                $update_journal = $this->Journal_model->update_journal($journal_id,array('journal_total'=>$total));                             
                            }

                            if($identity == 1){ // Bayar Hutang / Account Payable
                                
                                // Kas / Bank / Cek
                                $params_items_credit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,  
                                    // 'journal_item_trans_id' => $i['trans_id'],                  
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_type' => $identity,   
                                    'journal_item_date' => $journal_date,
                                    'journal_item_debit' => '0.00',
                                    'journal_item_credit' => $journal_total,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_date_created' => date("YmdHis"),
                                    'journal_item_date_updated' => date("YmdHis"),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_position' => 1
                                );                
                                $this->Journal_model->add_journal_item($params_items_credit);
                                
                                // Hutang Usaha From Kontak
                                // $params_ap = array(
                                    // 'account_map_for_transaction' => 1,
                                    // 'account_map_type' => 1,
                                    // 'account_map_branch_id' => $session_branch_id
                                // );
                                // $account_payable = $this->Account_map_model->get_account_map_where($params_ap);                           
                                // $params_ap = array(
                                    // 'account_map_for_transaction' => 1,
                                    // 'account_map_type' => 1,
                                    // 'account_map_branch_id' => $session_branch_id
                                    // 'contact-'
                                // );
                                $params_ap = $journal_contact;
                                $account_payable = $this->Kontak_model->get_kontak($params_ap);
                                foreach($journal_trans_list as $i){
                                    $params_items_debit = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_branch_id' => $session_branch_id,  
                                        'journal_item_trans_id' => $i['trans_id'],                  
                                        'journal_item_account_id' => $account_payable['contact_account_payable_account_id'],
                                        'journal_item_type' => $identity,   
                                        'journal_item_date' => $journal_date,
                                        'journal_item_debit' => $i['trans_total_paid'],
                                        'journal_item_credit' => '0.00',
                                        'journal_item_note' => $journal_note,
                                        'journal_item_date_created' => date("YmdHis"),
                                        'journal_item_date_updated' => date("YmdHis"),
                                        'journal_item_user_id' => $session_user_id,
                                        'journal_item_flag' => 1,
                                        'journal_item_position' => 2
                                    );

                                    if(floatVal($i['trans_total_paid']) > 0){
                                        $this->Journal_model->add_journal_item($params_items_debit);
                                    }
                                }                  
                            }

                            if($identity == 2){ // Bayar Piutang / Account Receivable
                                
                                // Kas / Bank / Cek
                                $params_items_credit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,  
                                    // 'journal_item_trans_id' => $i['trans_id'],                  
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_type' => $identity,   
                                    'journal_item_date' => $journal_date,
                                    'journal_item_credit' => '0.00',
                                    'journal_item_debit' => $journal_total,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_date_created' => date("YmdHis"),
                                    'journal_item_date_updated' => date("YmdHis"),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_position' => 1
                                );                
                                $this->Journal_model->add_journal_item($params_items_credit);
                                
                                // Hutang Usaha
                                // $params_ap = array(
                                //     'account_map_for_transaction' => 1,
                                //     'account_map_type' => 1,
                                //     'account_map_branch_id' => $session_branch_id
                                // );
                                // $account_payable = $this->Account_map_model->get_account_map_where($params_ap);  
                                $params_ap = $journal_contact;
                                $account_receivable = $this->Kontak_model->get_kontak($params_ap);                                                     
                                foreach($journal_trans_list as $i){
                                    $params_items_debit = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_branch_id' => $session_branch_id,  
                                        'journal_item_trans_id' => $i['trans_id'],                  
                                        'journal_item_account_id' => $account_receivable['contact_account_receivable_account_id'],
                                        'journal_item_type' => $identity,   
                                        'journal_item_date' => $journal_date,
                                        'journal_item_debit' => '0.00',
                                        'journal_item_credit' => $i['trans_total_paid'],
                                        'journal_item_note' => $journal_note,
                                        'journal_item_date_created' => date("YmdHis"),
                                        'journal_item_date_updated' => date("YmdHis"),
                                        'journal_item_user_id' => $session_user_id,
                                        'journal_item_flag' => 1,
                                        'journal_item_position' => 2
                                    );

                                    if(floatVal($i['trans_total_paid']) > 0){
                                        $this->Journal_model->add_journal_item($params_items_debit);
                                    }
                                }                  
                            }

                            if($identity == 6){ // Uang Muka Beli / Prepaid Expense
                                $get_contact = $this->Kontak_model->get_kontak($journal_contact);                            
                                $get_journal_item = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id);
                                $total = 0;
                                foreach($get_journal_item as $c){
                                    // $params_kredit = array(
                                    //     'journal_item_journal_id' => $journal_id,
                                    //     'journal_item_branch_id' => $session_branch_id,
                                    //     'journal_item_account_id' => $c['journal_item_account_id'],
                                    //     'journal_item_type' => $identity,                            
                                    //     // 'journal_item_trans_id' => '',
                                    //     'journal_item_date' => date('YmdHis'),
                                    //     'journal_item_date_created' => date('YmdHis'),                    
                                    //     'journal_item_date_updated' => date('YmdHis'),
                                    //     'journal_item_user_id' => $session_user_id,
                                    //     'journal_item_flag' => 1,
                                    //     'journal_item_note' => $c['journal_item_note'],
                                    //     'journal_item_debit' => $c['journal_item_debit'],
                                    //     'journal_item_credit' => 0,
                                    //     'journal_item_position' => 2
                                    // );  
                                    // $insert_journal_item_debit = $this->Journal_model->add_journal_item($params_kredit);
                                    $total = $total + $c['journal_item_debit'];                                
                                    $params_kredit = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_date' => $journal_date,
                                        'journal_item_flag' => 1,
                                        'journal_item_ref' => $get_contact['contact_session']                                        
                                    );
                                    $update_journal_item = $this->Journal_model->update_journal_item($c['journal_item_id'],$params_kredit);
                                }                            
                                $params_kredit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_type' => $identity,
                                    // 'journal_item_trans_id' => '',
                                    'journal_item_date' => $journal_date,
                                    'journal_item_date_created' => date('YmdHis'),
                                    'journal_item_date_updated' => date('YmdHis'),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_debit' => 0,
                                    'journal_item_credit' => $total,
                                    'journal_item_position' => 1
                                );                               
                                $insert_journal_item_kredit = $this->Journal_model->add_journal_item($params_kredit);
                                $update_journal = $this->Journal_model->update_journal($journal_id,array('journal_total'=>$total));
                                $update_journal_item = $this->Journal_model->update_journal_item_custom(array('journal_item_journal_id'=>$journal_id),array('journal_item_date_updated'=> date("YmdHis")));
                            }

                            if($identity == 7){ // Uang Muka Jual / Down Payment

                                $get_contact = $this->Kontak_model->get_kontak($journal_contact);
                                $get_journal_item = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id);
                                $total = 0;

                                //Cashback Detected
                                if(intval($cashback) > 0){
                                    $params_kredit = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_branch_id' => $session_branch_id,
                                        'journal_item_account_id' => $cashback,
                                        'journal_item_type' => $identity,
                                        // 'journal_item_trans_id' => '',
                                        'journal_item_date' => $journal_date,
                                        'journal_item_date_created' => date('YmdHis'),
                                        'journal_item_date_updated' => date('YmdHis'),
                                        'journal_item_user_id' => $session_user_id,
                                        'journal_item_flag' => 1,
                                        'journal_item_note' => $journal_note,
                                        'journal_item_debit' => $cashback_value,
                                        'journal_item_credit' => 0,
                                        'journal_item_position' => 3
                                    );
                                    $insert_journal_item_kredit = $this->Journal_model->add_journal_item($params_kredit);
                                }

                                foreach($get_journal_item as $c){
                                    // $params_kredit = array(
                                    //     'journal_item_journal_id' => $journal_id,
                                    //     'journal_item_branch_id' => $session_branch_id,
                                    //     'journal_item_account_id' => $c['journal_item_account_id'],
                                    //     'journal_item_type' => $identity,                            
                                    //     // 'journal_item_trans_id' => '',
                                    //     'journal_item_date' => date('YmdHis'),
                                    //     'journal_item_date_created' => date('YmdHis'),                    
                                    //     'journal_item_date_updated' => date('YmdHis'),
                                    //     'journal_item_user_id' => $session_user_id,
                                    //     'journal_item_flag' => 1,
                                    //     'journal_item_note' => $c['journal_item_note'],
                                    //     'journal_item_debit' => $c['journal_item_debit'],
                                    //     'journal_item_credit' => 0,
                                    //     'journal_item_position' => 2
                                    // );  
                                    // $insert_journal_item_debit = $this->Journal_model->add_journal_item($params_kredit);
                                    $total = $total + $c['journal_item_credit'];
                                    $params_kredit = array(
                                        'journal_item_journal_id' => $journal_id,
                                        'journal_item_date' => $journal_date,
                                        'journal_item_flag' => 1,
                                        'journal_item_ref' => $get_contact['contact_session'],
                                        'journal_item_credit' => $total+$cashback_value
                                    );
                                    $update_journal_item = $this->Journal_model->update_journal_item($c['journal_item_id'],$params_kredit);
                                }

                                $params_kredit = array(
                                    'journal_item_journal_id' => $journal_id,
                                    'journal_item_branch_id' => $session_branch_id,
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_type' => $identity,
                                    // 'journal_item_trans_id' => '',
                                    'journal_item_date' => $journal_date,
                                    'journal_item_date_created' => date('YmdHis'),
                                    'journal_item_date_updated' => date('YmdHis'),
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_flag' => 1,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_debit' => $total,
                                    'journal_item_credit' => 0,
                                    'journal_item_position' => 1
                                );                               
                                $insert_journal_item_kredit = $this->Journal_model->add_journal_item($params_kredit);
                                $update_journal = $this->Journal_model->update_journal($journal_id,array('journal_total'=>$total));
                                $update_journal_item = $this->Journal_model->update_journal_item_custom(array('journal_item_journal_id'=>$journal_id),array('journal_item_date_updated'=> date("YmdHis")));                                
                            }

                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 2,
                                'activity_table' => 'journals',
                                'activity_table_id' => $journal_id,
                                'activity_text_2' => $journal_number,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            /* End Activity */

                            $return->status=1;
                            $return->message='Berhasil menyimpan '.$journal_number;
                            $return->result= array(
                                'journal_id' => $journal_id,
                                'journal_number' => $journal_number,
                                'journal_session' => $journal_session
                                // 'journal_item_debit_id' => $insert_journal_item_debit,
                                // 'journal_item_kredit_id' => $insert_journal_item_kredit                            
                            ); 
                        }
                    }else{
                        $return->message='Nomor sudah digunakan';                    
                    }
                    $return->params=$params_check;
                    break;
                case "read":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $data['id'] = $this->input->post('id');           
                    $datas = $this->Journal_model->get_journal($data['id']);
                    if($datas==true){

                        if($identity==5){
                            $params_debit = array(
                                'journal_item_journal_id' => $datas['journal_id'],
                                'journal_item_debit >' => 0
                            );
                            $params_credit = array(
                                'journal_item_journal_id' => $datas['journal_id'],
                                'journal_item_credit >' => 0
                            );                        
                            $datas_detail_debit = $this->Journal_model->get_all_journal_item($params_debit,null,null,null,null,null);
                            $datas_detail_credit = $this->Journal_model->get_all_journal_item($params_credit,null,null,null,null,null);                        
                            
                            $return->result_detail_debit=$datas_detail_debit;
                            $return->result_detail_credit=$datas_detail_credit;
                            // var_dump($datas_detail);
                        }
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 3,
                            'activity_table' => 'journals',
                            'activity_table_id' => $data['id'],
                            'activity_text_2' => $datas['journal_number'],
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */

                        $return->status=1;
                        $return->message='Success';
                        $return->result=$datas;
                    }                
                    break;
                case "update":
                    if($identity == 7){
                        $return->message = 'Update tidak tersedia';
                    }else{
                        $id = $data['id'];
                        $journal_account = !empty($data['akun']) ? $data['akun'] : null;
                        $journal_number = !empty($data['nomor']) ? $data['nomor'] : null;
                        $journal_paid_type = !empty($data['cara_pembayaran']) ? $data['cara_pembayaran'] : null;              
                        // $journal_total = str_replace(',','',$data['jumlah']);
                        $journal_total = !empty($data['jumlah']) ? str_replace(',','',$data['jumlah']) : null;

                        $journal_date = date('Y-m-d H:i:s', strtotime($data['tgl'].date('H:i:s')));
                        $journal_contact = !empty($data['kontak']) ? $data['kontak'] : null;
                        $journal_note = !empty($data['keterangan']) ? $data['keterangan'] : null;

                        //For Account Payable / Receivable 
                        $journal_trans_list = !(empty($data['trans_list'])) ? $data['trans_list'] : false;

                        //Cashback
                        $cashback = !empty($data['cashback_account']) ? intval($data['cashback_account']) : 0;
                        $cashback_value = !empty($data['cashback_value']) ? str_replace(',','',$data['cashback_value']) : 0;


                        //JSON Strigify Post
                        $params = array(
                            // 'journal_type' => !empty($identity) ? $identity : null,
                            'journal_contact_id' => !empty($journal_contact) ? $journal_contact : null,
                            'journal_number' => !empty($journal_number) ? $journal_number : null,
                            'journal_date' => !empty($journal_date) ? $journal_date : null,
                            'journal_note' => !empty($journal_date) ? $journal_note : null,
                            'journal_account_id' => !empty($journal_account) ? $journal_account : null,
                            // 'journal_total' => !empty($journal_total) ? $journal_total : 0,
                            'journal_paid_type' => !empty($journal_paid_type) ? $journal_paid_type : null,
                            // 'journal_date_created' => date("YmdHis"),
                            'journal_date_updated' => date("YmdHis"),
                            // 'journal_user_id' => !empty($session_user_id) ? $session_user_id : null,
                            // 'journal_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,                    
                            'journal_flag' => 1,
                            'journal_cashback' => (intval($cashback) > 0) ? 1 : 0
                        );

                        //Get Data Journal Before 
                        $get_data_journal = $this->Journal_model->get_journal($id);
                        $journal_number_before = $get_data_journal['journal_number'];

                        //Check Data Exist
                        $params_check = array(
                            'journal_number !=' => $journal_number_before,
                            'journal_number' => $journal_number,
                            'journal_branch_id' => $session_branch_id,
                            'journal_type' => $identity
                        );
                        $check_exists = $this->Journal_model->check_data_exist($params_check);
                        $set_update =false;
                        if($check_exists==false){
                            $set_update=$this->Journal_model->update_journal($id,$params);

                            //Get Data Journal Before 
                            $get_data_journal_after = $this->Journal_model->get_journal($id);
                            $journal_date = $get_data_journal_after['journal_date'];
                            // var_dump($journal_date);
                            if($identity==5){ //Mutasi Kas / Transfer Uang
                                $journal_item_debit_id = !empty($data['journal_item_debit_id']) ? $data['journal_item_debit_id'] : null;
                                $journal_item_credit_id = !empty($data['journal_item_kredit_id']) ? $data['journal_item_kredit_id'] : null;

                                $journal_account_debit = !empty($data['akun_debit']) ? $data['akun_debit'] : null;
                                $journal_account_credit = !empty($data['akun_kredit']) ? $data['akun_kredit'] : null;

                                $params_update_journal_item_debit = array(
                                    'journal_item_account_id' => $journal_account_debit,
                                    'journal_item_date' => $journal_date,
                                    'journal_item_debit' => $journal_total,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_date_updated' => date("YmdHis")
                                );
                                $params_update_journal_item_credit = array(
                                    'journal_item_account_id' => $journal_account_credit,                            
                                    'journal_item_date' => $journal_date,
                                    'journal_item_credit' => $journal_total,
                                    'journal_item_note' => $journal_note,                            
                                    'journal_item_date_updated' => date("YmdHis")
                                );                        
                                // var_dump($journal_date);die;
                                $this->Journal_model->update_journal_item($journal_item_debit_id,$params_update_journal_item_debit);
                                $this->Journal_model->update_journal_item($journal_item_credit_id,$params_update_journal_item_credit);
                            }
                            else if(($identity == 3) or $identity == 8){ //Cash In & General Journal
                                $journal_item_journal_id = $id;
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_credit_total($journal_item_journal_id,$params);

                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_credit']
                                );                       
                                $params_update = array(
                                    'journal_item_account_id' => $journal_account,                            
                                    'journal_item_date' => $journal_date,                            
                                    'journal_item_debit' => $get_total_journal_item['journal_item_credit'] 
                                );
                                $params_update_for_date = array(
                                    'journal_item_date' => $journal_date,                            
                                    'journal_item_date_updated' => date("YmdHis")                            
                                );

                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $update_journal_item = $this->Journal_model->update_journal_item_by_journal_id($id,$params_update_for_date);
                                $return->journal_id = 1;
                            }   
                            else if(($identity == 4) or $identity == 9){ //Cash Out & Cost Out 
                                $journal_item_journal_id = $id;
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_debit_total($journal_item_journal_id,$params);

                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_debit']
                                );                       
                                $params_update = array(
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_date' => $journal_date,                            
                                    'journal_item_credit' => $get_total_journal_item['journal_item_debit']
                                );
                                $params_update_for_date = array(
                                    'journal_item_date' => $journal_date,                            
                                    'journal_item_date_updated' => date("YmdHis")                            
                                );
                                                        
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $update_journal_item = $this->Journal_model->update_journal_item_by_journal_id($id,$params_update_for_date);
                                $return->journal_id = 1;
                            }
                            else if($identity == 6){ //Uang Muka Beli / Prepaid Expense
                                $get_contact = $this->Kontak_model->get_kontak($journal_contact);                                
                                $journal_item_journal_id = $id;
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_debit_total($journal_item_journal_id,$params);

                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_debit']
                                );                       
                                $params_update_one = array(
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_date' => $journal_date,                            
                                    'journal_item_credit' => $get_total_journal_item['journal_item_debit']
                                );
                                $params_update_two = array(
                                    'journal_item_date' => $journal_date,
                                    'journal_item_ref' => $get_contact['contact_session'],
                                    'journal_item_date_updated' => date("YmdHis")
                                );                            
                                $params_update_for_date = array(
                                    'journal_item_date' => $journal_date,                            
                                    'journal_item_date_updated' => date("YmdHis")                            
                                );
                                                        
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update_one);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_two($journal_item_journal_id,$params_update_two);                            
                                // $update_journal_item = $this->Journal_model->update_journal_item_by_journal_id($id,$params_update_for_date);
                                $return->journal_id = 1;
                            }  
                            else if($identity == 7){ //Uang Muka Jual / Down Payment
                                $get_contact = $this->Kontak_model->get_kontak($journal_contact);                          
                                $journal_item_journal_id = $id;

                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_credit_total($journal_item_journal_id,$params);

                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_credit']
                                );                       
                                

                                $params_update_one = array(
                                    'journal_item_account_id' => $journal_account,                            
                                    'journal_item_date' => $journal_date,                            
                                    // 'journal_item_debit' => $set_journal_debit, 
                                    'journal_item_date_updated' => date("YmdHis")
                                );
                                $params_update_two = array(
                                    'journal_item_date' => $journal_date,
                                    'journal_item_ref' => $get_contact['contact_session'],
                                    // 'journal_item_credit' => $set_journal_credit,                                 
                                    'journal_item_date_updated' => date("YmdHis")
                                );

                                //Cashback Detected
                                $set_journal_debit = $get_total_journal_item['journal_item_credit'];
                                if(intval($cashback) > 0){


                                    //Update when ready
                                    $set_journal_debit = $get_total_journal_item['journal_item_credit'] - $cashback_value; //1.2jt - cashbackvalue
                                    $params_update_one['journal_item_debit'] = $set_journal_debit;
                                }else{ //Remove Cashback

                                    // //Check journal cashback input already ?
                                    // $params_exist = array(
                                    //     'journal_item_journal_id' => $journal_item_journal_id,
                                    //     'journal_item_position' => 3
                                    // );
                                    // $get_cashback_exist = $this->Journal_model->get_all_journal_item_count($params_exist);
                                    // $get_cashback_exist_data = $this->Journal_model->get_journal_item_custom_row($params_exist);
                                    
                                    // if($get_cashback_exist > 0){
                                    //     //Update
                                    // }else{
                                    //     //Input when not ready
                                    //     $params_kredit = array(
                                    //         'journal_item_journal_id' => $journal_item_journal_id,
                                    //         'journal_item_branch_id' => $session_branch_id,
                                    //         'journal_item_account_id' => $cashback,
                                    //         'journal_item_type' => $identity,
                                    //         // 'journal_item_trans_id' => '',
                                    //         'journal_item_date' => $journal_date,
                                    //         'journal_item_date_created' => date('YmdHis'),
                                    //         'journal_item_date_updated' => date('YmdHis'),
                                    //         'journal_item_user_id' => $session_user_id,
                                    //         'journal_item_flag' => 1,
                                    //         'journal_item_note' => $journal_note,
                                    //         'journal_item_debit' => $cashback_value,
                                    //         'journal_item_credit' => 0,
                                    //         'journal_item_position' => 3
                                    //     );
                                    //     $insert_journal_item_kredit = $this->Journal_model->add_journal_item($params_kredit);
                                    // }                            
                                    $where_delete = array(
                                        'journal_item_journal_id' => $journal_item_journal_id,
                                        'journal_item_position' => 3
                                    );
                                    $delete_cashback = $this->Journal_model->delete_journal_item_custom($where_delete);

                                    $params_update_one['journal_item_debit'] = $set_journal_debit;
                                    $params_update_two['journal_item_credit'] = $set_journal_debit - $cashback_value;                                
                                }

                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update_one);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_two($journal_item_journal_id,$params_update_two);
                                
                                $return->journal_id = 1;
                                $return->cashback = $cashback;
                                $return->debit = $set_journal_debit;
                            }                                                
                            else if($identity == 1){ // Bayar Hutang / Account Payable
                                
                                // Kas / Bank / Cek
                                // $params_items_credit = array(
                                //     'journal_item_journal_id' => $journal_id,
                                //     'journal_item_branch_id' => $session_branch_id,  
                                //     // 'journal_item_trans_id' => $i['trans_id'],                  
                                //     'journal_item_account_id' => $journal_account,
                                //     'journal_item_type' => $identity,   
                                //     'journal_item_date' => $journal_date,
                                //     'journal_item_debit' => '0.00',
                                //     'journal_item_credit' => $journal_total,
                                //     'journal_item_note' => $journal_note,
                                //     'journal_item_date_created' => date("YmdHis"),
                                //     'journal_item_date_updated' => date("YmdHis"),
                                //     'journal_item_user_id' => $session_user_id,
                                //     'journal_item_flag' => 1,
                                //     'journal_item_position' => 1
                                // );                
                                // $this->Journal_model->add_journal_item($params_items_credit);
                                
                                // Hutang Usaha
                                $params_ap = array(
                                    'account_map_for_transaction' => 1,
                                    'account_map_type' => 1,
                                    'account_map_branch_id' => $session_branch_id
                                );
                                $account_payable = $this->Account_map_model->get_account_map_where($params_ap);     
                                /*
                                foreach($journal_trans_list as $i){
                                    $journal_item_id = $i['journal_item_id'];
                                    $params_items_update_debit = array(
                                        // 'journal_item_journal_id' => $journal_id,
                                        // 'journal_item_branch_id' => $session_branch_id,  
                                        // 'journal_item_trans_id' => $i['trans_id'],                  
                                        // 'journal_item_account_id' => $account_payable['account_map_account_id'],
                                        // 'journal_item_type' => $identity,   
                                        'journal_item_date' => $journal_date,
                                        'journal_item_debit' => $i['trans_total_paid'],
                                        'journal_item_credit' => '0.00',
                                        'journal_item_note' => $journal_note,
                                        // 'journal_item_date_created' => date("YmdHis"),
                                        'journal_item_date_updated' => date("YmdHis"),
                                        // 'journal_item_user_id' => $session_user_id,
                                        // 'journal_item_flag' => 1,
                                        'journal_item_position' => 2
                                    );
                                    // $this->Journal_model->add_journal_item($params_items_debit);
                                    if(floatVal($i['trans_total_paid']) > 0){
                                        $this->Journal_model->update_journal_item($journal_item_id,$params_items_update_debit);
                                    }
                                }  
                                */
                                $params_sum = array(
                                    'journal_item_journal_id' => $id,
                                    'journal_item_debit >' => 0
                                );
                                $get_journal = $this->Journal_model->get_journal_item_debit_total(null,$params_sum);
                                $params_update = array(
                                    'journal_total'=>$get_journal['journal_item_debit']
                                );
                                $params_update_one = array(
                                    'journal_item_account_id' => $journal_account,
                                    'journal_item_date' => $journal_date,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_debit' => '0.00',
                                    'journal_item_credit'=>$get_journal['journal_item_debit']
                                );
                                $params_update_two = array(
                                    'journal_item_date' => $journal_date,
                                    'journal_item_note' => $journal_note
                                );                        
                                $this->Journal_model->update_journal_item_for_position_one($id,$params_update_one);
                                $this->Journal_model->update_journal_item_for_position_two($id,$params_update_two);
                                $set_update=$this->Journal_model->update_journal($id,$params_update);
                            }                                    
                            else{
                                $params_update_journal_item = array(
                                    'journal_item_date' => $journal_date,
                                    'journal_item_note' => $journal_note,
                                    'journal_item_date_updated' => date("YmdHis")
                                );
                                $this->Journal_model->update_journal_item_by_journal_id($id,$params_update_journal_item);
                            }
                        }else{
                            $return->message='Data sudah digunakan';                    
                        }               

                        if($set_update==true){
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'journals',
                                'activity_table_id' => $id,
                                'activity_text_2' => $get_data_journal_after['journal_number'],
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            /* End Activity */

                            $return->status=1;
                            $return->message='Berhasil memperbarui '.$journal_number;
                        }        
                    }        
                    break;  
                case "delete":
                    $id     = $this->input->post('id');
                    $number = $this->input->post('number');                               
                    // $flag = $this->input->post('flag');
                    $flag   = 4;

                    // $set_data=$this->Journal_model->update_journal($id,array('journal_flag'=>$flag));
                    // $set_data=$this->Journal_model->update_journal_item_by_journal_id($id,array('journal_item_flag'=>$flag));                    
                    
                    $set_data=$this->Journal_model->delete_journal_item_by_journal_id($id);    
                    $set_data=$this->Journal_model->delete_journal($id);                 
                    if($set_data==true){
                        //Aktivitas
                        $params = array(
                            'activity_user_id' => $session_user_id,
                            'activity_branch_id' => $session_branch_id,
                            'activity_action' => 5,
                            'activity_table' => 'journals',
                            'activity_table_id' => $id,
                            'activity_text_2' => $number,
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 0
                        );
                        $this->save_activity($params);
                        /* End Activity */

                        $return->status=1;
                        $return->message='Berhasil menghapus '.$number;
                    }                
                    break;
                case "load":

                    $limit      = $this->input->post('length');
                    $start      = $this->input->post('start');
                    $order      = $columns[$this->input->post('order')[0]['column']];
                    $dir        = $this->input->post('order')[0]['dir'];
                    $search     = [];
                    
                    $kontak     = !empty($this->input->post('kontak')) ? $this->input->post('kontak') : 0;
                    $paid_type  = !empty($this->input->post('paid_type')) ? $this->input->post('paid_type') : 0;
                    $account    = !empty($this->input->post('account')) ? $this->input->post('account') : 0;

                    $akun_kredit    = !empty($data['akun_kredit']) ? $data['akun_kredit'] : null;
                    $akun_debit     = !empty($data['akun_debit']) ? $data['akun_debit'] : null;

                    $date_start     = date('Y-m-d H:i:s', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end       = date('Y-m-d H:i:s', strtotime($this->input->post('date_end').' 23:59:59'));   

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
                    
                    // 'journals.journal_branch_id' => intval($session_branch_id),
                    $params_datatable = array(
                        'journals.journal_date >' => $date_start,
                        'journals.journal_date <' => $date_end,
                        'journals.journal_type' => intval($identity),
                        'journals.journal_flag' => 1
                    );
                    
                    if($kontak > 0){ $params_datatable['journals.journal_contact_id'] = $kontak; }
                    if($account > 0){ $params_datatable['journals.journal_account_id'] = $account; }
                    if($paid_type > 0){ $params_datatable['journals.journal_paid_type'] = $paid_type; }

                    $branch = !empty($this->input->post('branch')) ? $this->input->post('branch') : 0;
                    if($session_group_id > 2){ //Kecuali Super Admin
                        $params_datatable['journals.journal_branch_id'] = $session_branch_id;
                    }else{
                        if($branch > 0){
                            $params_datatable['journals.journal_branch_id'] = $branch;
                        }
                    }

                    if($identity == 5){ //Transfer Uang
                        $params_datatable = array(
                            'journals_items.journal_item_date >' => $date_start,
                            'journals_items.journal_item_date <' => $date_end,
                            'journals_items.journal_item_branch_id' => intval($session_branch_id),
                            'journals_items.journal_item_type' => intval($identity),
                            'journals_items.journal_item_flag' => 1,
                            // 'journals.journal_contact_id' => $kontak                   
                        );                    
                        $datas = $this->Journal_model->get_all_journal_item_custom($params_datatable, $search, $limit, $start, $order, $dir);
                        $datas_count = $this->Journal_model->get_all_journal_item_custom_count($params_datatable,$search);
                    }else{
                        $datas = $this->Journal_model->get_all_journal($params_datatable, $search, $limit, $start, $order, $dir);
                        $datas_count = $this->Journal_model->get_all_journal_count($params_datatable,$search);
                        // $datas_count = $this->Order_model->get_all_orders_count($params_datatable, $search, $limit, $start, $order, $dir);
                    }                                 
                    
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
                    $return->search = $search;
                    $return->params = $params_datatable;
                    break;

                case "create-item":
                    $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    $data = json_decode($post_data, TRUE);

                    //Check All Input Come From
                    $journal_item_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];

                    // var_dump($this->input->post('journal_trans'), $data['journal_trans']);die;
                    // $journal_item_type = 19;                
                    $journal_item_journal_id = !empty($data['journal_id']) ? $data['journal_id'] : null;
                    $journal_item_account_id = !empty($this->input->post('journal_account')) ? $this->input->post('journal_account') : $data['journal_account'];
                    $journal_item_trans_id = !empty($this->input->post('journal_trans')) ? $this->input->post('journal_trans') : null;
                    $journal_item_note = !empty($this->input->post('keterangan')) ? $this->input->post('keterangan') : $data['keterangan']; 

                    $journal_item_debit = !empty($data['debit']) ? str_replace(',','',$data['debit']) : 0;
                    $journal_item_credit = !empty($data['kredit']) ? str_replace(',','',$data['kredit']) : 0;  
                    
                    // var_dump($journal_item_trans_id,$journal_item_account_id);die;
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = date('h:i:s');
                    $set_date = $tgl.' '.$jam;

                    // $debit = $data['debit'];
                    // $kredit = $data['kredit'];


                    // if(!empty($data['debit'])){
                    //     $debit = str_replace(',','',$data['debit']);
                    // }

                    // if(!empty($data['kredt'])){
                    //     $kredit = str_replace(',','',$data['kredit']);
                    // }
                    $set_flag=0;
                    if(intval($journal_item_journal_id) > 0){
                        $set_flag = 1;
                    }    

                    $params_items = array(
                        'journal_item_journal_id' => $journal_item_journal_id,
                        'journal_item_branch_id' => $session_branch_id,                    
                        'journal_item_account_id' => $journal_item_account_id,
                        'journal_item_trans_id' => $journal_item_trans_id,
                        'journal_item_type' => $journal_item_type,                    
                        // 'journal_item_product_id' => $data['produk'],
                        // 'journal_item_id_lokasi' => $data['lokasi_id'],
                        'journal_item_date' => $set_date,
                        // 'journal_item_unit' => $data['satuan'],
                        // 'journal_item_qty' => $data['qty'],
                        // 'journal_item_price' => str_replace('.','',$data['harga']),
                        'journal_item_debit' => $journal_item_debit,
                        'journal_item_credit' => $journal_item_credit,
                        'journal_item_note' => $journal_item_note,
                        'journal_item_date_created' => date("YmdHis"),
                        'journal_item_date_updated' => date("YmdHis"),
                        'journal_item_user_id' => $session_user_id,
                        'journal_item_flag' => $set_flag
                    );
                    // var_dump($params_items);die;
                    //Check Data Exist Trans Item
                    // $params_check = array(
                    //     'order_item_type' => $identity,
                    //     'order_item_product' => $data['produk']
                    // );
                    // $check_exists = $this->Order_model->check_data_exist_item($params_check);
                    $check_exists = false;
                    if($check_exists==false){

                        $set_data=$this->Journal_model->add_journal_item($params_items);
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
                            $return->message='Berhasil menambah journal item';
                            $return->result= array(
                                'id' => $set_data
                                // 'kode' => $data['kode']
                            ); 
                        }
                        if(intval($journal_item_journal_id) > 0){
                            if($journal_item_type == 4 or $journal_item_type == 9){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_debit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_debit']
                                );                       
                                $params_update = array(
                                    'journal_item_credit' => $get_total_journal_item['journal_item_debit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }
                            if($journal_item_type == 3){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_credit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_credit']
                                );                       
                                $params_update = array(
                                    'journal_item_debit' => $get_total_journal_item['journal_item_credit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }                        
                        }                    
                    }else{
                        $return->message='Journal Item sudah diinput';                    
                    }
                    break;
                case "create-item-production-cost": 
                    // die;
                    $post_data = $this->input->post('data');
                    // $data = base64_decode($post_data);
                    $data = json_decode($post_data, TRUE);

                    //Check All Input Come From
                    // var_dump($this->input->post('journal_trans'), $data['journal_trans']);die;
                    $journal_item_type = 19;                
                    $journal_item_journal_id = !empty($data['journal_id']) ? $data['journal_id'] : null;
                    $journal_item_account_id = !empty($this->input->post('journal_account')) ? $this->input->post('journal_account') : $data['journal_account'];
                    $journal_item_trans_id = !empty($this->input->post('journal_trans')) ? $this->input->post('journal_trans') : $data['journal_trans'];
                    $journal_item_note = !empty($this->input->post('keterangan')) ? $this->input->post('keterangan') : null; 
                    $journal_item_position = !empty($this->input->post('position')) ? $this->input->post('position') : 3;

                    $journal_item_debit = !empty($data['debit']) ? str_replace(',','',$data['debit']) : 0;
                    $journal_item_credit = !empty($data['kredit']) ? str_replace(',','',$data['kredit']) : 0;  
                    
                    // var_dump($journal_item_trans_id,$journal_item_account_id);die;
                    $tgl = substr($data['tgl'], 6,4).'-'.substr($data['tgl'], 3,2).'-'.substr($data['tgl'], 0,2);
                    $jam = date('h:i:s');
                    $set_date = $tgl.' '.$jam;

                    $set_flag=0;
                    if(intval($journal_item_journal_id) > 0){
                        $set_flag = 1;
                    }    

                    $params_items = array(
                        'journal_item_journal_id' => $journal_item_journal_id,
                        'journal_item_branch_id' => $session_branch_id,                    
                        'journal_item_account_id' => $journal_item_account_id,
                        'journal_item_trans_id' => $journal_item_trans_id,
                        'journal_item_type' => $journal_item_type,                    
                        // 'journal_item_product_id' => $data['produk'],
                        // 'journal_item_id_lokasi' => $data['lokasi_id'],
                        'journal_item_date' => $set_date,
                        // 'journal_item_unit' => $data['satuan'],
                        // 'journal_item_qty' => $data['qty'],
                        // 'journal_item_price' => str_replace('.','',$data['harga']),
                        'journal_item_debit' => $journal_item_debit,
                        'journal_item_credit' => $journal_item_credit,
                        'journal_item_note' => $journal_item_note,
                        'journal_item_date_created' => date("YmdHis"),
                        'journal_item_date_updated' => date("YmdHis"),
                        'journal_item_user_id' => $session_user_id,
                        'journal_item_flag' => $set_flag,
                        'journal_item_position' => 3
                    );

                    //Check Data Exist Trans Item
                    // $params_check = array(
                    //     'order_item_type' => $identity,
                    //     'order_item_product' => $data['produk']
                    // );
                    // $check_exists = $this->Order_model->check_data_exist_item($params_check);
                    $check_exists = false;
                    if($check_exists==false){

                        $set_data=$this->Journal_model->add_journal_item($params_items);
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
                            $return->message='Berhasil menambah biaya produksi';
                            $return->result= array(
                                'id' => $set_data
                                // 'kode' => $data['kode']
                            ); 
                        }
                        if(intval($journal_item_journal_id) > 0){
                            /*
                            if($journal_item_type == 4 or $journal_item_type == 9){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_debit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_debit']
                                );                       
                                $params_update = array(
                                    'journal_item_credit' => $get_total_journal_item['journal_item_debit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }
                            if($journal_item_type == 3){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_credit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_credit']
                                );                       
                                $params_update = array(
                                    'journal_item_debit' => $get_total_journal_item['journal_item_credit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }       
                            */                 
                        }                    
                    }else{
                        $return->message='Biaya Produksi sudah diinput';                    
                    }
                    break;            
                case "read-item":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $data['id'] = $this->input->post('id');           
                    $datas = $this->Journal_model->get_journal_item($data['id']);
                    if($datas==true){
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
                        $return->status=1;
                        $return->message='Success';
                        $return->result=$datas;
                    }                
                    break;
                case "update-item":
                    $post_data = $this->input->post('data');
                    $data = json_decode($post_data, TRUE);
                    $id = $data['id'];
                    
                    // if(!empty($data['debit'])){
                    //     $debit = str_replace('.','',$data['debit']);
                    // }else{ $debit = 0; }

                    // if(!empty($data['kredit'])){
                    //     $kredit = str_replace('.','',$data['kredit']);
                    // }else{ $kredit = 0; }

                    //Check All Input Come From
                    $journal_item_type = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : $data['tipe'];
                    // $journal_item_journal_id = !empty($this->input->post('journal_id')) ? $this->input->post('journal_id') : null;
                    $journal_item_account_id = !empty($data['account']) ? $data['account'] : null;
                    $journal_item_trans_id = !empty($data['journal_trans_id']) ? $data['journal_trans_id'] : null;
                    $journal_item_note = !empty($data['keterangan']) ? $data['keterangan'] : null;                

                    $journal_item_debit = !empty($data['debit']) ? str_replace(',','',$data['debit']) : 0;
                    $journal_item_credit = !empty($data['credit']) ? str_replace(',','',$data['credit']) : 0;  

                    if($identity == 4 or $identity == 9){
                        $params_update = array(
                            // 'journal_item_journal_id' => $journal_item_journal_id,
                            // 'journal_item_branch_id' => $session_branch_id,                    
                            'journal_item_account_id' => $journal_item_account_id,
                            'journal_item_trans_id' => $journal_item_trans_id,
                            'journal_item_type' => $journal_item_type,                    
                            // 'journal_item_product_id' => $data['produk'],
                            // 'journal_item_id_lokasi' => $data['lokasi_id'],
                            // 'journal_item_date' => date("YmdHis", strtotime(date("d-M-Y"),$data['tgl'])),
                            // 'journal_item_unit' => $data['satuan'],
                            // 'journal_item_qty' => $data['qty'],
                            // 'journal_item_price' => str_replace('.','',$data['harga']),
                            'journal_item_debit' => $journal_item_debit,
                            // 'journal_item_credit' => $journal_item_credit,
                            'journal_item_note' => $journal_item_note,
                            // 'journal_item_date_created' => date("YmdHis"),
                            'journal_item_date_updated' => date("YmdHis"),
                            // 'journal_item_user_id' => $session_user_id,
                            // 'journal_item_flag' => 0
                        );
                    }else if($identity == 8){
                        $params_update = array(
                            // 'journal_item_journal_id' => $journal_item_journal_id,
                            // 'journal_item_branch_id' => $session_branch_id,                    
                            'journal_item_account_id' => $journal_item_account_id,
                            'journal_item_trans_id' => $journal_item_trans_id,
                            'journal_item_type' => $journal_item_type,                    
                            // 'journal_item_product_id' => $data['produk'],
                            // 'journal_item_id_lokasi' => $data['lokasi_id'],
                            // 'journal_item_date' => date("YmdHis", strtotime(date("d-M-Y"),$data['tgl'])),
                            // 'journal_item_unit' => $data['satuan'],
                            // 'journal_item_qty' => $data['qty'],
                            // 'journal_item_price' => str_replace('.','',$data['harga']),
                            'journal_item_debit' => $journal_item_debit,
                            'journal_item_credit' => $journal_item_credit,
                            'journal_item_note' => $journal_item_note,
                            // 'journal_item_date_created' => date("YmdHis"),
                            'journal_item_date_updated' => date("YmdHis"),
                            // 'journal_item_user_id' => $session_user_id,
                            // 'journal_item_flag' => 0
                        );
                    }else if($identity == 3){
                        $params_update = array(
                            // 'journal_item_journal_id' => $journal_item_journal_id,
                            // 'journal_item_branch_id' => $session_branch_id,                    
                            'journal_item_account_id' => $journal_item_account_id,
                            'journal_item_trans_id' => $journal_item_trans_id,
                            'journal_item_type' => $journal_item_type,                    
                            // 'journal_item_product_id' => $data['produk'],
                            // 'journal_item_id_lokasi' => $data['lokasi_id'],
                            // 'journal_item_date' => date("YmdHis", strtotime(date("d-M-Y"),$data['tgl'])),
                            // 'journal_item_unit' => $data['satuan'],
                            // 'journal_item_qty' => $data['qty'],
                            // 'journal_item_price' => str_replace('.','',$data['harga']),
                            'journal_item_debit' => $journal_item_debit,
                            'journal_item_credit' => $journal_item_credit,
                            'journal_item_note' => $journal_item_note,
                            // 'journal_item_date_created' => date("YmdHis"),
                            'journal_item_date_updated' => date("YmdHis"),
                            // 'journal_item_user_id' => $session_user_id,
                            // 'journal_item_flag' => 0
                        );
                    }

                    /*
                    if(!empty($data['password'])){
                        $params['password'] = md5($data['password']);
                    }
                    */
                
                    $set_update=$this->Journal_model->update_journal_item($id,$params_update);

                    //Get ID Journal
                    $get_journal_id = $this->Journal_model->get_all_journal_item(array('journal_item_id'=>$id),null,0,1,null,null);
                    // var_dump($get_journal_id[0]['journal_item_journal_id']);die;
                    $journal_item_journal_id = $get_journal_id[0]['journal_item_journal_id']; 

                    if($set_update==true){
                        if(intval($journal_item_journal_id) > 0){
                            if($journal_item_type == 4 or $journal_item_type == 9){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_debit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_debit']
                                );                       
                                $params_update = array(
                                    'journal_item_credit' => $get_total_journal_item['journal_item_debit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }
                            if($journal_item_type == 3){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_credit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_credit']
                                );                       
                                $params_update = array(
                                    'journal_item_debit' => $get_total_journal_item['journal_item_credit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }                             
                        }                                    
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
                case "delete-item":
                    $id = $this->input->post('id');
                    $kode = $this->input->post('kode');        
                    $nama = $this->input->post('nama');                                
                    $flag = $this->input->post('flag');
                    $journal_item_type = $this->input->post('tipe');

                    // if($flag==1){
                    //     $msg='aktifkan transaksi '.$nama;
                    //     $act=7;
                    // }else{
                    //     $msg='nonaktifkan transaksi '.$nama;
                    //     $act=8;
                    // }

                    //Get ID Journal
                    $get_journal_id = $this->Journal_model->get_all_journal_item(array('journal_item_id'=>$id),null,0,1,null,null);
                    // var_dump($get_journal_id[0]['journal_item_journal_id']);die;
                    $journal_item_journal_id = $get_journal_id[0]['journal_item_journal_id']; 
                    // $set_flag = 4;
                    // $set_data=$this->Journal_model->update_journal_item($id,array('journal_item_flag' => $set_flag));
                    $set_data=$this->Journal_model->delete_journal_item($id);


                    if($set_data==true){

                        // if(intval($get_journal_id) > 0){
                        //     if($journal_item_type == 4){
                        
                        //         $params_datatable = array(
                        //             'journals_items.journal_item_journal_id' => $get_journal_id[0]['journal_item_journal_id'],
                        //             'journals_items.journal_item_position' => 2,
                        //             'journals_items.journal_item_flag' => 1
                        //         );
                        //         $get_journal_item = $this->Journal_model->get_all_journal_item($params_datatable, null, null, null, null, null);
                        //         $total = 0;
                        //         foreach($get_journal_item as $c){
                        //             $total = $total + $c['journal_item_debit'];                                
                        //         }
                        //     }          
                        //     $update_journal = $this->Journal_model->update_journal($journal_id,array('journal_total'=>$total));
                        //     $return->journal_id=1;
                        // }
                        if(intval($journal_item_journal_id) > 0){
                            if($journal_item_type == 4 or $journal_item_type == 9){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_debit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_debit']
                                );                       
                                $params_update = array(
                                    'journal_item_credit' => $get_total_journal_item['journal_item_debit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }
                            if($journal_item_type == 3 or $journal_item_type == 8){
                                $params = array(
                                    'journal_item_journal_id' => $journal_item_journal_id,
                                    'journal_item_position' => 2
                                );                         
                                $get_total_journal_item = $this->Journal_model->get_journal_item_credit_total($journal_item_journal_id,$params);
                                $params_total = array(
                                    'journal_total' => $get_total_journal_item['journal_item_credit']
                                );                       
                                $params_update = array(
                                    'journal_item_debit' => $get_total_journal_item['journal_item_credit']
                                );
                                $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                $return->journal_id = 1;
                            }                             
                        }                    
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
                        $return->message='Berhasil menghapus '.$nama;
                    }                
                    break;
                case "delete-item-production-cost": //Using production.js
                    $id = $this->input->post('id');
                    $kode = $this->input->post('kode');        
                    $nama = $this->input->post('nama');                                
                    $flag = $this->input->post('flag');
                    // $journal_item_type = $this->input->post('tipe');
                    
                    // die;
                    // if($flag==1){
                    //     $msg='aktifkan transaksi '.$nama;
                    //     $act=7;
                    // }else{
                    //     $msg='nonaktifkan transaksi '.$nama;
                    //     $act=8;
                    // }

                    if(intval($id) > 10){
                        //Get ID Journal
                        // $get_journal_id = $this->Journal_model->get_all_journal_item(array('journal_item_id'=>$id),null,0,1,null,null);
                        // $get_journal_id = $this->Journal_model->get_journal_item($id);                    
                        $set_data=$this->Journal_model->delete_journal_item($id);
                        
                        if($set_data==true){
                            /*
                            if(intval($journal_item_journal_id) > 0){
                                if($journal_item_type == 4 or $journal_item_type == 9){
                                    $params = array(
                                        'journal_item_journal_id' => $journal_item_journal_id,
                                        'journal_item_position' => 2
                                    );                         
                                    $get_total_journal_item = $this->Journal_model->get_journal_item_debit_total($journal_item_journal_id,$params);
                                    $params_total = array(
                                        'journal_total' => $get_total_journal_item['journal_item_debit']
                                    );                       
                                    $params_update = array(
                                        'journal_item_credit' => $get_total_journal_item['journal_item_debit']
                                    );
                                    $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                    $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                    $return->journal_id = 1;
                                }
                                if($journal_item_type == 3 or $journal_item_type == 8){
                                    $params = array(
                                        'journal_item_journal_id' => $journal_item_journal_id,
                                        'journal_item_position' => 2
                                    );                         
                                    $get_total_journal_item = $this->Journal_model->get_journal_item_credit_total($journal_item_journal_id,$params);
                                    $params_total = array(
                                        'journal_total' => $get_total_journal_item['journal_item_credit']
                                    );                       
                                    $params_update = array(
                                        'journal_item_debit' => $get_total_journal_item['journal_item_credit']
                                    );
                                    $update_journal = $this->Journal_model->update_journal($journal_item_journal_id,$params_total);
                                    $update_journal_item = $this->Journal_model->update_journal_item_for_position_one($journal_item_journal_id,$params_update);
                                    $return->journal_id = 1;
                                }                             
                            }         
                            */
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
                            $return->message='Berhasil menghapus '.$nama;
                        }         
                    }else{
                        $return->message='Data '.$nama.' tidak ditemukan';
                    }       
                    break;
                case "load-item":
                    $journal_id = $this->input->post('id');
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
                        'journal_items.journal_item_journal_id' => $journal_id
                    );
                    $datas = $this->Journal_model->get_all_journal_items($params_datatable, $search, $limit, $start, $order, $dir);
                    $datas_count = $this->Journal_model->get_all_journal_items_count($params_datatable);
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
                case "check-last-item":
                    $get_data = $this->Order_model->check_unsaved_journal_item($identity,$session['user_data']['user_id']);
                    if(!empty($get_data)){
                        $subtotal_debit = 0;
                        $subtotal_credit = 0;

                        $total_debit = 0;
                        $total_credit = 0;

                        foreach($get_data as $v){
                            $datas[] = array(
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_journal_id' => $v['journal_item_journal_id'],
                                'journal_item_branch_id' => $v['journal_item_branch_id'],
                                'journal_item_trans_id' => $v['journal_item_trans_id'],
                                'journal_item_account_id' => $v['journal_item_account_id'],
                                'journal_item_date' => $v['journal_item_date'],
                                'journal_item_type' => $v['journal_item_type'],
                                'journal_item_debit' => number_format($v['journal_item_debit'],0,'.',','),
                                'journal_item_credit' => number_format($v['journal_item_credit'],0,'.',','),
                                'journal_item_note' => $v['journal_item_note'],
                                'journal_item_flag' => $v['journal_item_flag'],
                                'journal_item_date_created' => $v['journal_item_date_created'],
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                            );
                            $total_debit=$subtotal_debit+$v['journal_item_debit'];
                            $total_credit=$subtotal_credit+$v['journal_item_credit'];
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
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas; 
                            $return->total_journal=count($datas);
                            $return->subtotal_debit=number_format($subtotal_debit,0,'.',',');
                            $return->subtotal_credit=number_format($subtotal_credit,0,'.',',');                        
                            $return->total_debit=number_format($total_debit,0,'.',',');
                            $return->total_credit=number_format($total_credit,0,'.',',');                        
                        }else{ 
                            $data_source=0; $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Terdapat item temporary';
                    }else{
                        $return->message='Tidak ada item temporary';
                    }
                    break;

                case "journal":
                    // $post_data = $this->input->post('data');
                    // $data = json_decode($post_data, TRUE);     
                    $id = $this->input->post('id');           
                    $tipe = $this->input->post('tipe');                           
                    $params = array(
                        'journal_item_journal_id' => $id
                    );

                    $datas = $this->Journal_model->get_all_journal_item($params,null,null,null,'journal_item_debit','desc');
                    if($datas==true){
                        $result = array();
                        foreach($datas as $v):

                            $journal_item_note = '-';
                            if($v['journal_item_note'] != null){
                                $journal_item_note = $v['journal_item_note'];
                            }

                            // $journal_item_debit = (floatval($v['journal_item_debit']) > 0) : $v['journal_item_debit'] : '-';
                            // $journal_item_credit = (floatval($v['journal_item_credit']) > 0) : $v['journal_item_credit'] : '-';                        
                            
                            $result[] = array(
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
                            'activity_text_1' => $tipe,
                            'activity_text_2' => $id,                        
                            'activity_date_created' => date('YmdHis'),
                            'activity_flag' => 1,
                            'activity_transaction' => $tipe,
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
                case "load-journal-items":
                    $journal_id = !empty($this->input->post('journal_id')) ? $this->input->post('journal_id') : 0;
                    // var_dump($params_journal_items);die;
                    if(intval($journal_id) > 0){
                        $get_data = $this->Journal_model->get_all_journal_item($params_journal_items,null,null,null,null);
                    }else{
                        $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }

                    if(!empty($get_data)){
                        $subtotal = 0;
                        $total_debit = 0;
                        $total_credit = 0;
                        $total_diskon = 0;
                        $total= 0;

                        foreach($get_data as $v){

                            // $get_product_price = $this->Product_price_model->get_all_product_price(array('product_price_product_id'=>$v['product_id']),null,null,null,null,null);
                            // $product_price_list = array();
                            // foreach($get_product_price as $pp){
                            //     $product_price_list[] = array(
                            //         'product_price_id' => $pp['product_price_id'],
                            //         'product_price_product_id' => $pp['product_price_product_id'],                                
                            //         'product_price_name' => $pp['product_price_name'],
                            //         'product_price_price' => $pp['product_price_price']
                            //     );
                            // }

                            $datas[] = array(
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_journal_id' => $v['journal_item_journal_id'],                            
                                // 'journal_item_unit' => $v['journal_item_unit'],
                                'journal_item_debit' => number_format($v['journal_item_debit'],2,'.',','),
                                'journal_item_credit' => number_format($v['journal_item_credit'],2,'.',','),
                                // 'journal_item_discount' => number_format($v['journal_item_discount'],2,'.',','),
                                // 'journal_item_total' => number_format($v['journal_item_total'],2,'.',','),       
                                'journal_item_note' => $v['journal_item_note'],
                                // 'journal_item_product_price_id' => $v['journal_item_product_price_id'],
                                'journal_item_user_id' => $v['journal_item_user_id'],
                                'journal_item_branch_id' => $v['journal_item_branch_id'],
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name']
                            );
                            $total_debit=$total_debit+$v['journal_item_debit'];
                            $total_credit=$total_credit+$v['journal_item_credit'];                        
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
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas; 
                            // $return->total_produk=count($datas);
                            // $return->subtotal=number_format($subtotal,0,'.',',');
                            $return->total_debit=number_format($total_debit,2,'.',',');
                            $return->total_credit=number_format($total_credit,2,'.',',');                        
                            // $return->total=number_format($subtotal-$total_diskon,0,'.',',');
                        }else{ 
                            $data_source=0; $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->total_debit= 0;
                            $return->total_credit= 0;                         
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($journal_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada item temporary';
                        if(intval($journal_id) > 0){
                            $return->message = '-';
                        }                    
                    }
                    break;
                case "load-journal-items-down-payment":
                    $journal_id = !empty($this->input->post('journal_id')) ? $this->input->post('journal_id') : 0;
                    $get_cashback = array();
                    $is_cashback = 0;                    
                    if(intval($journal_id) > 0){
                        $get_data = $this->Journal_model->get_all_journal_item($params_journal_items,null,null,null,null);
                        $get_journal = $this->Journal_model->get_journal($journal_id);
                        if(intval($get_journal['journal_cashback']) > 0){
                            $get_cashback = $this->Journal_model->get_journal_item_custom_row(array('journal_item_journal_id' => $journal_id,'journal_item_position'=>3));
                            $is_cashback = 1;
                        }
                    }else{
                        $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }

                    if(!empty($get_data)){
                        $total_debit    = 0;
                        $total_credit   = 0;
                        $total          = 0;

                        foreach($get_data as $v){
                            $datas[] = array(
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_journal_id' => $v['journal_item_journal_id'],                            
                                // 'journal_item_unit' => $v['journal_item_unit'],
                                'journal_item_debit' => number_format($v['journal_item_debit'],2,'.',','),
                                'journal_item_credit' => number_format($v['journal_item_credit'],2,'.',','),
                                // 'journal_item_discount' => number_format($v['journal_item_discount'],2,'.',','),
                                // 'journal_item_total' => number_format($v['journal_item_total'],2,'.',','),       
                                'journal_item_note' => $v['journal_item_note'],
                                // 'journal_item_product_price_id' => $v['journal_item_product_price_id'],
                                'journal_item_user_id' => $v['journal_item_user_id'],
                                'journal_item_branch_id' => $v['journal_item_branch_id'],
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name']
                            );
                            $total_debit=$total_debit+$v['journal_item_debit'];
                            $total_credit=$total_credit+$v['journal_item_credit'];                        
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
                        if(isset($datas)){ //Data exist
                            $return->status=1; 
                            $return->message='Loaded'; 
                            $return->total_records=count($datas);
                            $return->result=$datas; 
                            $return->total_debit=number_format($total_debit,2,'.',',');
                            $return->total_credit=number_format($total_credit,2,'.',','); 
                            $return->result_cashback = $get_cashback;           
                            $return->is_cashback = $is_cashback;
                        }else{ 
                            $return->status=0; 
                            $return->message='No data'; 
                            $return->total_records = 0;
                            $return->total_debit = 0;
                            $return->total_credit = 0;                         
                            $return->result = array();
                            $return->result_cashback = $get_cashback;
                            $return->is_cashback = $is_cashback;                            
                        }                    
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($journal_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada item temporary';
                        if(intval($journal_id) > 0){
                            $return->message = '-';
                        }                    
                    }
                    break;                
                case "load-journal-items-production-cost": //Use in production_js
                    
                    $trans_id = !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : 0;
                    $tipe = !empty($this->input->post('tipe')) ? $this->input->post('tipe') : null;                
                    $position = !empty($this->input->post('position')) ? $this->input->post('position') : 3;                                
                    
                    if(intval($trans_id) > 0){
                        $params_journal_items = array(
                            'journal_item_trans_id' => $trans_id,
                            'journal_item_position' => 3
                        );
                        $get_data = $this->Journal_model->get_all_journal_item($params_journal_items,null,null,null,null);
                    }else{
                        if($tipe == 8){ //Produksi
                            $identity=19;
                        }
                        $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }

                    if(!empty($get_data)){
                        $subtotal = 0;
                        $total_debit = 0;
                        $total_credit = 0;
                        $total_diskon = 0;
                        $total= 0;

                        foreach($get_data as $v){
                            $datas[] = array(
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_journal_id' => $v['journal_item_journal_id'],                            
                                // 'journal_item_unit' => $v['journal_item_unit'],
                                'journal_item_debit' => number_format($v['journal_item_debit'],2,'.',','),
                                'journal_item_credit' => number_format($v['journal_item_credit'],2,'.',','),
                                // 'journal_item_discount' => number_format($v['journal_item_discount'],2,'.',','),
                                // 'journal_item_total' => number_format($v['journal_item_total'],2,'.',','),       
                                'journal_item_note' => $v['journal_item_note'],
                                // 'journal_item_product_price_id' => $v['journal_item_product_price_id'],
                                'journal_item_user_id' => $v['journal_item_user_id'],
                                'journal_item_branch_id' => $v['journal_item_branch_id'],
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                            );
                            $total_debit=$total_debit+$v['journal_item_debit'];
                            $total_credit=$total_credit+$v['journal_item_credit'];                        
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
                        if(isset($datas)){ //Data exist
                            $data_source=$datas; $total=count($datas);
                            $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                            $return->result=$datas; 
                            // $return->total_produk=count($datas);
                            // $return->subtotal=number_format($subtotal,0,'.',',');
                            $return->total_debit=number_format($total_debit,0,'.',',');
                            $return->total_credit=number_format($total_credit,0,'.',',');                        
                            // $return->total=number_format($subtotal-$total_diskon,0,'.',',');
                        }else{ 
                            $data_source=0; $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->total_debit= 0;
                            $return->total_credit= 0;                         
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
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
                case "load-account-payable":
                    
                    $contact_id = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : 0;
                    // var_dump($order_id);
                    $contact=0;
                    if(intval($contact_id) > 0){
                        $get_contact = $this->Kontak_model->get_kontak($contact_id);
                        $contact= array(
                            'contact_id' => $get_contact['contact_id'],
                            'contact_code' => $get_contact['contact_code'],
                            'contact_name' => $get_contact['contact_name'],                                        
                        );                    
                        $params = array(
                            'trans_branch_id' => $session_branch_id,
                            'trans_type' => 1,
                            'trans_paid' => 0,
                            'trans_contact_id' => $contact_id,
                            'trans_flag' => 0
                        ); 
                        $get_data = $this->Journal_model->get_all_account_payable($params,null,null,null,null);
                    }else{
                        // $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }

                    if(!empty($get_data)){
                        $total_tagihan = 0;
                        $total_terbayar = 0;
                        $total_sisa = 0;

                        foreach($get_data as $v){
                            $sisa = 0;
                            $sisa = $v['trans_total']-$v['trans_total_paid'];
                            $datas[] = array(
                                'contact_name' => $get_contact['contact_name'],
                                'trans_id' => $v['trans_id'],
                                'trans_session' => $v['trans_session'],
                                'trans_number' => $v['trans_number'],                            
                                'trans_date' => $v['trans_date'],
                                'trans_date_due' => $v['trans_date_due'],
                                'trans_date_format' => $v['trans_date_format'],
                                'trans_date_due_format' => $v['trans_date_due_format'],
                                'trans_note' => $v['trans_note'],
                                'trans_paid' => $v['trans_paid'],
                                'trans_flag' => $v['trans_flag'],
                                'trans_total' => number_format($v['trans_total'],2,'.',','),       
                                'total_paid' => number_format($v['trans_total_paid'],2,'.',','),
                                'total_sisa' => number_format($sisa,2,'.',',')
                            );
                            $total_tagihan=$total_tagihan+$v['trans_total'];
                            $total_terbayar=$total_terbayar+$v['trans_total_paid'];                        
                        }
                        $total_sisa = $total_tagihan-$total_terbayar;
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
                            $return->total_tagihan=number_format($total_tagihan,2,'.',',');
                            $return->total_terbayar=number_format($total_terbayar,2,'.',',');
                            $return->total_sisa=number_format($total_sisa,2,'.',',');  
                            $return->contact = $contact;
                        }else{ 
                            $data_source=0; $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->total_tagihan= 0;
                            $return->total_terbayar= 0;
                            $return->total_sisa= 0;
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($contact_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada hutang';
                        if(intval($contact_id) > 0){
                            $return->message = '-';
                        }                    
                    }
                    break;
                case "load-account-receivable":
                    
                    $contact_id = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : 0;
                    // var_dump($order_id);
                    $contact= 0;
                    if(intval($contact_id) > 0){
                        $get_contact = $this->Kontak_model->get_kontak($contact_id);
                        $contact= array(
                            'contact_id' => $get_contact['contact_id'],
                            'contact_code' => $get_contact['contact_code'],
                            'contact_name' => $get_contact['contact_name'],                                        
                        );                    
                        $params = array(
                            'trans_branch_id' => $session_branch_id,
                            'trans_type' => 2,
                            'trans_paid' => 0,
                            'trans_contact_id' => $contact_id,
                            'trans_flag' => 0
                        );                    
                        $get_data = $this->Journal_model->get_all_account_receivable($params,null,null,null,null);                    
                        // var_dump($get_data);die;
                    }else{
                        // $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }

                    if(!empty($get_data)){
                        $total_tagihan = 0;
                        $total_terbayar = 0;
                        $total_sisa = 0;

                        foreach($get_data as $v){
                            $sisa = 0;
                            $sisa = $v['trans_total']-$v['trans_total_paid'];
                            $datas[] = array(
                                'contact_name' => $get_contact['contact_name'],
                                'trans_id' => $v['trans_id'],
                                'trans_session' => $v['trans_session'],                            
                                'trans_number' => $v['trans_number'],                            
                                'trans_date' => $v['trans_date'],
                                'trans_date_due' => $v['trans_date_due'],
                                'trans_date_format' => $v['trans_date_format'],
                                'trans_date_due_format' => $v['trans_date_due_format'],
                                'trans_note' => $v['trans_note'],
                                'trans_paid' => $v['trans_paid'],
                                'trans_flag' => $v['trans_flag'],
                                'trans_total' => number_format($v['trans_total'],2,'.',','),       
                                'total_paid' => number_format($v['trans_total_paid'],2,'.',','),
                                'total_sisa' => number_format($sisa,2,'.',',')
                            );
                            $total_tagihan=$total_tagihan+$v['trans_total'];
                            $total_terbayar=$total_terbayar+$v['trans_total_paid'];                        
                        }
                        $total_sisa = $total_tagihan-$total_terbayar;
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
                            $return->total_tagihan=number_format($total_tagihan,2,'.',',');
                            $return->total_terbayar=number_format($total_terbayar,2,'.',',');
                            $return->total_sisa=number_format($total_sisa,2,'.',',');  
                            $return->contact=$contact;
                        }else{ 
                            $data_source=0; $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->total_tagihan= 0;
                            $return->total_terbayar= 0;
                            $return->total_sisa= 0;
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($contact_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada piutang';
                        if(intval($contact_id) > 0){
                            $return->message = '-';
                        }                    
                    }
                    break;
                case "load-account-payable-or-receivable-journal":
                    
                    $journal_id = !empty($this->input->post('journal_id')) ? $this->input->post('journal_id') : 0;
                    $contact_id = !empty($this->input->post('contact_id')) ? $this->input->post('contact_id') : 0;
                    $contact = array();
                    
                    if(intval($contact_id) > 0){
                        $get_contact = $this->Kontak_model->get_kontak($contact_id);
                        $contact= array(
                            'contact_id' => $get_contact['contact_id'],
                            'contact_code' => $get_contact['contact_code'],
                            'contact_name' => $get_contact['contact_name'],                                        
                        );
                        /*                    
                        $params = array(
                            'trans_branch_id' => $session_branch_id,
                            'trans_type' => 1,
                            'trans_paid' => 0,
                            'trans_contact_id' => $contact_id,
                            'trans_flag' => 0
                        ); 
                        */
                        $params = array(
                            'journal_item_journal_id' => $journal_id,
                            'journal_item_position' => 2
                        );                     
                        $get_data = $this->Journal_model->get_all_journal_item($params,null,null,null,null);
                        // $get_data = $this->Journal_model->get_all_account_payable($params,null,null,null,null);
                    }else{
                        // $get_data = $this->Journal_model->check_unsaved_journal_item($identity,$session_user_id,$session_branch_id);
                    }

                    // echo json_encode($get_data);die;
                    if(!empty($get_data)){
                        $total_tagihan = 0;
                        $total_terbayar = 0;
                        $total_sisa = 0;

                        foreach($get_data as $v){
                            $params_trans = array(
                                'trans_id' => $v['journal_item_trans_id'],
                                'trans_branch_id' => $session_branch_id
                            );
                            $prepare_trans = $this->Journal_model->get_all_account_payable($params_trans,null,null,null,null);
                            $get_trans = $prepare_trans[0];
                            // var_dump($get_trans);die;
                            $sisa = 0;
                            $sisa = $get_trans['trans_total']-$get_trans['trans_total_paid'];

                            $datas[] = array(
                                // 'contact_name' => $v['contact_name'],
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_debit' => $v['journal_item_debit'],
                                // 'journal_item_credit' => $v['journal_item_credit'],                            
                                'trans_id' => $get_trans['trans_id'],
                                'trans_session' => $get_trans['trans_session'],                            
                                'trans_number' => $get_trans['trans_number'],                            
                                'trans_date' => $get_trans['trans_date'],
                                'trans_date_due' => $get_trans['trans_date_due'],
                                'trans_date_format' => $get_trans['trans_date_format'],
                                'trans_date_due_format' => $get_trans['trans_date_due_format'],
                                'trans_note' => $get_trans['trans_note'],
                                'trans_paid' => $get_trans['trans_paid'],
                                'trans_flag' => $get_trans['trans_flag'],
                                'trans_total' => number_format($get_trans['trans_total'],2,'.',','),       
                                'total_paid' => number_format($get_trans['trans_total_paid'],2,'.',','),
                                'total_sisa' => number_format($sisa,2,'.',',')
                            );

                            $total_tagihan=$total_tagihan+$get_trans['trans_total'];
                            $total_terbayar=$total_terbayar+$get_trans['trans_total_paid'];                        
                        }
                        $next=false;
                        if($next){
                            foreach($get_data as $v){
                                $sisa = 0;
                                $sisa = $v['trans_total']-$v['trans_total_paid'];
                                $datas[] = array(
                                    'trans_id' => $v['trans_id'],
                                    'trans_session' => $v['trans_session'],
                                    'trans_number' => $v['trans_number'],                            
                                    'trans_date' => $v['trans_date'],
                                    'trans_date_due' => $v['trans_date_due'],
                                    'trans_date_format' => $v['trans_date_due_format'],
                                    'trans_date_due_format' => $v['trans_date_due_format'],
                                    'trans_note' => $v['trans_note'],
                                    'trans_paid' => $v['trans_paid'],
                                    'trans_flag' => $v['trans_flag'],
                                    'trans_total' => number_format($v['trans_total'],2,'.',','),       
                                    'total_paid' => number_format($v['trans_total_paid'],2,'.',','),
                                    'total_sisa' => number_format($sisa,2,'.',',')
                                );
                                $total_tagihan=$total_tagihan+$v['trans_total'];
                                $total_terbayar=$total_terbayar+$v['trans_total_paid'];                        
                            }
                        }
                        $total_sisa = $total_tagihan-$total_terbayar;
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
                            $return->total_tagihan=number_format($total_tagihan,2,'.',',');
                            $return->total_terbayar=number_format($total_terbayar,2,'.',',');
                            $return->total_sisa=number_format($total_sisa,2,'.',',');  
                            $return->contact = $contact;
                        }else{ 
                            $data_source=0; $total=0; 
                            $return->status=0; $return->message='No data'; $return->total_records=$total;
                            $return->total_tagihan= 0;
                            $return->total_terbayar= 0;
                            $return->total_sisa= 0;
                            $return->result=0;
                        }                    
                        $return->status=1;
                        $return->message='Terdapat data yang belum disimpan';
                        if(intval($contact_id) > 0){
                            $return->message = 'Berhasil memuat data';
                        }
                    }else{
                        $return->message='Tidak ada hutang';
                        if(intval($contact_id) > 0){
                            $return->message = '-';
                        }                    
                    }
                    break;
                case "create-from-sales-sell": //Direct Penjualan to Payment POS (From : sell_js)
                    $this->form_validation->set_rules('trans_id', 'Transaksi', 'required');
                    $this->form_validation->set_rules('modal_metode_pembayaran', 'Metode Pembayaran', 'required');
                    $this->form_validation->set_rules('modal_total_bayar', 'Jumlah Pembayaran', 'required');                         
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE) {
                        $return->message = validation_errors();
                    }else{
                        $post = $this->input->post();
                        $next = true;                        
                        $trans_id                = !empty($post['trans_id']) ? $post['trans_id'] : null;
                        $trans_paid_type         = !empty($post['modal_metode_pembayaran']) ? $post['modal_metode_pembayaran'] : null;
                        $trans_paid_type_name    = $post['metode_pembayaran'];
                        $journal_paid_type       = $trans_paid_type;

                        $trans_bank_ref          = !empty($post['modal_nomor_ref_transfer']) ? $post['modal_nomor_ref_transfer'] : null;
                        $trans_bank_account_name = !empty($post['modal_nama_pengirim']) ? $post['modal_nama_pengirim'] : null;

                        $trans_card_expired_year = !empty($post['modal_valid_tahun']) ? $post['modal_valid_tahun'] : null;
                        $trans_card_expired_mnth = !empty($post['modal_valid_bulan']) ? $post['modal_valid_bulan'] : null;

                        $trans_card_type         = !empty($post['modal_jenis_kartu']) ? $post['modal_jenis_kartu'] : null;
                        $trans_card_number       = !empty($post['modal_nomor_kartu']) ? $post['modal_nomor_kartu'] : null;
                        $trans_card_bank_name    = !empty($post['modal_bank_penerbit_nama']) ? $post['modal_bank_penerbit_nama'] : null;
                        $trans_card_account_name = !empty($post['modal_nama_pemilik']) ? $post['modal_nama_pemilik'] : null;
                        $trans_card_expired      = $trans_card_expired_year.'-'.$trans_card_expired_mnth;

                        $trans_total_before      = !empty($post['modal_total_before']) ? str_replace(',','',$post['modal_total_before']) : 0;
                        $trans_total             = !empty($post['modal_total']) ? str_replace(',','',$post['modal_total']) : 0;
                        $trans_total_paid        = !empty($post['modal_total_bayar']) ? str_replace(',','',$post['modal_total_bayar']) : 0;
                        $trans_total_change      = $trans_total_paid - $trans_total;

                        $journal_number          = !empty($post['nomor']) ? $post['nomor'] : $this->request_number_for_journal($identity);                
                        $journal_date            = date('YmdHis');
                                        
                        $voucher_id              = !empty($post['voucher_id']) ? $post['voucher_id'] : 0;

                        // $journal_item_account    = !empty($post['akun']) ? $post['akun'] : null;
                        // $journal_paid_type  = !empty($data['cara_pembayaran']) ? $data['cara_pembayaran'] : null;

                        $journal_note       = null;
                        $journal_total      = $trans_total;

                        //For Account Payable / Receivable 
                        // $journal_trans_list = !(empty($data['trans_list'])) ? $data['trans_list'] : null;
                        // var_dump(floatval($trans_total_paid),floatval($trans_total),floatval($trans_total_change));die;
                        
                        if(intval($trans_paid_type) < 1){ /* Metode Pembayaran wajib dipilih */
                            $next =false;
                            $return->message = 'Metode pembayaran belum dipilih';
                        }
                        
                        if(floatval($trans_total_paid) < 1){ /* Jumlah harus sesuai */
                            $next=false;
                            $return->message = 'Masukkan jumlah yang sesuai';
                        }

                        if(floatval($trans_total) < 1){ /* Tagihan tidak sesuai */
                            $next=false;
                            $return->message = 'Total Tagihan tidak sesuai';      
                        }

                        //Check apakah Transaksi POS sudah pernah dicicil JIKA Voucher Detected
                        if(intval($voucher_id) > 0){
                            if($next){  
                                $params_check_ap = array(
                                    'journal_item_trans_id' => intval($trans_id),
                                    'journal_item_type' => 2, //2=Jual, 1=Beli
                                    'journal_item_credit > ' => 0
                                );
                                $check_have_termin_payment = $this->Journal_model->get_all_journal_item_count($params_check_ap);
                                if(intval($check_have_termin_payment) > 0){
                                    $next = false;
                                    $return->message = 'Gagal, Voucher tidak berlaku di invoice yg sdh pernah dibayar sebagian';
                                }else{
                                    $next = true;
                                }
                            }
                        }

                        if($next){
                            if(floatval($trans_total_paid) - floatval($trans_total) > -1){
                                
                                //Account
                                if($journal_paid_type == 1){ // Cash
                                    $journal_item_account = $post['modal_akun_cash'];
                                }else if($journal_paid_type == 2){ // Transfer
                                    $journal_item_account = $post['modal_akun_transfer'];
                                }else if($journal_paid_type == 3){ // EDC
                                    $journal_item_account = $post['modal_akun_edc'];
                                }else if($journal_paid_type == 4){ // Gratis
                                    $journal_item_account = $post['modal_akun_gratis'];
                                }else if($journal_paid_type == 5){ // QRIS
                                    $journal_item_account = $post['modal_akun_qris'];
                                }

                                //Voucher Detected Update
                                if(intval($voucher_id) > 0){
                                    $params_update['trans_voucher_id'] = intval($voucher_id);
                                    $params_update['trans_voucher'] = $trans_total_before - $trans_total;
                                    $this->Transaksi_model->update_transaksi($trans_id,$params_update);
                                }
                                
                                //Get Contact From Trans
                                $get_trans = $this->Transaksi_model->get_transaksi($trans_id);

                                $create_journal_session = $this->random_session(20);
                                $create_group_session   = $this->random_session(12);

                                //JSON Strigify Post
                                $params = array(
                                    'journal_type' => !empty($identity) ? $identity : null,
                                    'journal_contact_id' => $get_trans['trans_contact_id'],
                                    'journal_number' => !empty($journal_number) ? $journal_number : null,
                                    'journal_date' => !empty($journal_date) ? $journal_date : null,
                                    'journal_note' => !empty($journal_note) ? $journal_note : null,
                                    'journal_account_id' => !empty($journal_item_account) ? $journal_item_account : null,
                                    'journal_total' => !empty($journal_total) ? $journal_total : 0,
                                    'journal_paid_type' => !empty($journal_paid_type) ? $journal_paid_type : null,
                                    'journal_date_created' => date("YmdHis"),
                                    'journal_date_updated' => date("YmdHis"),
                                    'journal_user_id' => !empty($session_user_id) ? $session_user_id : null,
                                    'journal_branch_id' => !empty($session_branch_id) ? $session_branch_id : null,                    
                                    'journal_flag' => 1,
                                    'journal_session' => $create_journal_session
                                );
                                // var_dump($params);die;

                                //Check Data Exist
                                $params_check = array(
                                    'journal_number' => $journal_number,
                                    'journal_branch_id' => $session_branch_id,
                                    'journal_type' => $identity
                                );

                                $check_exists = $this->Journal_model->check_data_exist($params_check);
                                if($check_exists==false){
                                    $set_data=$this->Journal_model->add_journal($params);
                                    if($set_data==true){
                                        $journal_id = $set_data;

                                        /*
                                        if($identity == 1){ // Bayar Hutang / Account Payable
                                            
                                            // Kas / Bank / Cek
                                            $params_items_credit = array(
                                                'journal_item_journal_id' => $journal_id,
                                                'journal_item_branch_id' => $session_branch_id,  
                                                // 'journal_item_trans_id' => $i['trans_id'],                  
                                                'journal_item_account_id' => $journal_item_account,
                                                'journal_item_type' => $identity,   
                                                'journal_item_date' => $journal_date,
                                                'journal_item_debit' => '0.00',
                                                'journal_item_credit' => $journal_total,
                                                'journal_item_note' => $journal_note,
                                                'journal_item_date_created' => date("YmdHis"),
                                                'journal_item_date_updated' => date("YmdHis"),
                                                'journal_item_user_id' => $session_user_id,
                                                'journal_item_flag' => 1,
                                                'journal_item_position' => 1
                                            );                
                                            $this->Journal_model->add_journal_item($params_items_credit);
                                            
                                            // Hutang Usaha From Kontak
                                            // $params_ap = array(
                                                // 'account_map_for_transaction' => 1,
                                                // 'account_map_type' => 1,
                                                // 'account_map_branch_id' => $session_branch_id
                                            // );
                                            // $account_payable = $this->Account_map_model->get_account_map_where($params_ap);                           
                                            // $params_ap = array(
                                                // 'account_map_for_transaction' => 1,
                                                // 'account_map_type' => 1,
                                                // 'account_map_branch_id' => $session_branch_id
                                                // 'contact-'
                                            // );
                                            $params_ap = $journal_contact;
                                            $account_payable = $this->Kontak_model->get_kontak($params_ap);
                                            foreach($journal_trans_list as $i){
                                                $params_items_debit = array(
                                                    'journal_item_journal_id' => $journal_id,
                                                    'journal_item_branch_id' => $session_branch_id,  
                                                    'journal_item_trans_id' => $i['trans_id'],                  
                                                    'journal_item_account_id' => $account_payable['contact_account_payable_account_id'],
                                                    'journal_item_type' => $identity,   
                                                    'journal_item_date' => $journal_date,
                                                    'journal_item_debit' => $i['trans_total_paid'],
                                                    'journal_item_credit' => '0.00',
                                                    'journal_item_note' => $journal_note,
                                                    'journal_item_date_created' => date("YmdHis"),
                                                    'journal_item_date_updated' => date("YmdHis"),
                                                    'journal_item_user_id' => $session_user_id,
                                                    'journal_item_flag' => 1,
                                                    'journal_item_position' => 2
                                                );

                                                if(floatVal($i['trans_total_paid']) > 0){
                                                    $this->Journal_model->add_journal_item($params_items_debit);
                                                }
                                            }                  
                                        }
                                        */
                                        if($identity == 2){ // Bayar Piutang / Account Receivable
                                            
                                            // Kas / Bank / Cek (DEBIT)
                                            $params_items_credit = array(
                                                'journal_item_journal_id' => $journal_id,
                                                'journal_item_branch_id' => $get_trans['trans_branch_id'],  
                                                // 'journal_item_trans_id' => $trans_id,
                                                'journal_item_account_id' => $journal_item_account,
                                                'journal_item_type' => $identity,   
                                                'journal_item_date' => $journal_date,
                                                'journal_item_debit' => $journal_total,
                                                'journal_item_credit' => '0.00',
                                                // 'journal_item_note' => $journal_note,
                                                'journal_item_date_created' => date("YmdHis"),
                                                'journal_item_date_updated' => date("YmdHis"),
                                                'journal_item_user_id' => $session_user_id,
                                                'journal_item_flag' => 1,
                                                'journal_item_position' => 1,
                                                'journal_item_group_session' => $create_group_session
                                            );
                                            $this->Journal_model->add_journal_item($params_items_credit);
                                            
                                            //Detect have Voucher
                                            if(floatval($get_trans['trans_voucher']) > 0){
                                                $account_voucher = $this->get_account_map_for_transaction($session_branch_id,2,9); //Voucher Penjualan
                                                // Discount Debit
                                                $params_items_voucher = array(
                                                    'journal_item_journal_id' => $journal_id,
                                                    'journal_item_branch_id' => $get_trans['trans_branch_id'],  
                                                    // 'journal_item_trans_id' => $i['trans_id'],
                                                    'journal_item_trans_id' => $trans_id,
                                                    'journal_item_account_id' => $account_voucher['account_id'],
                                                    'journal_item_type' => 2,   
                                                    'journal_item_date' => $journal_date,
                                                    'journal_item_debit' => floatVal($get_trans['trans_voucher']),
                                                    'journal_item_credit' => '0.00',
                                                    'journal_item_date_created' => date("YmdHis"),
                                                    'journal_item_date_updated' => date("YmdHis"),
                                                    'journal_item_user_id' => $session_user_id,
                                                    'journal_item_flag' => 1,
                                                    'journal_item_position' => 1,
                                                    'journal_item_group_session' => $create_group_session
                                                );
                                                $this->Journal_model->add_journal_item($params_items_voucher);    
                                                $journal_total = $journal_total + $get_trans['trans_voucher'];                                                    
                                            }
                                                                                        
                                            // Pendapatan, Tidak lewat piutang usaha (CREDIT)
                                            $account_receivable = $this->Kontak_model->get_kontak($get_trans['trans_contact_id']);
                                            $params_items_debit = array(
                                                'journal_item_journal_id' => $journal_id,
                                                'journal_item_branch_id' => $get_trans['trans_branch_id'],  
                                                'journal_item_trans_id' => $trans_id,
                                                'journal_item_account_id' => $account_receivable['contact_account_receivable_account_id'],
                                                'journal_item_type' => $identity,   
                                                'journal_item_date' => $journal_date,
                                                'journal_item_debit' => '0.00',
                                                'journal_item_credit' => $journal_total,
                                                // 'journal_item_note' => $journal_note,
                                                'journal_item_date_created' => date("YmdHis"),
                                                'journal_item_date_updated' => date("YmdHis"),
                                                'journal_item_user_id' => $session_user_id,
                                                'journal_item_flag' => 1,
                                                'journal_item_position' => 2,
                                                'journal_item_group_session' => $create_group_session
                                            );
                                            $this->Journal_model->add_journal_item($params_items_debit);

                                            //Update Trans for info
                                            $params_update = array(
                                                    // 'trans_total_dpp' => floatVal($total_before),
                                                    // 'trans_down_payment' => '0.00',
                                                    // 'trans_discount' => $data['discount'],
                                                    // 'trans_return' => '0.00',
                                                    // 'trans_total' => floatVal($total_after),
                                                    // 'trans_total_paid' => floatVal($total_after),
                                                    // 'trans_change' => floatVal($total_change),
                                                'trans_received' => floatVal($trans_total_paid),
                                                'trans_change' => floatVal($trans_total_change),
                                                    // 'trans_paid' => 1,            
                                                'trans_paid_type' => $trans_paid_type,
                                                // 'trans_bank_name' => $trans, //Transfer
                                                // 'trans_bank_number' => $trans, //Transfer
                                                'trans_bank_account_name' => $trans_bank_account_name,
                                                'trans_bank_ref' => $trans_bank_ref,
                                                'trans_card_number' => $trans_card_number,
                                                'trans_card_bank_name' => $trans_card_bank_name,
                                                'trans_card_bank_number' => $trans_card_number,
                                                'trans_card_account_name' => $trans_card_account_name,
                                                'trans_card_expired' => $trans_card_expired,
                                                'trans_card_type' => $trans_card_type,
                                            );
                                            $this->Transaksi_model->update_transaksi($trans_id,$params_update);
                                        }

                                        $return->status=1;
                                        $return->message='Berhasil menyimpan '.$journal_number;
                                        $return->result= array(
                                            'journal_id' => $journal_id,
                                            'journal_number' => $journal_number,
                                            'contact_id' => $get_trans['trans_contact_id'],
                                            'contact_code' => $get_trans['contact_code'],
                                            'contact_name' => $get_trans['contact_name'],
                                            'contact_address' => $get_trans['contact_address'],
                                            'contact_phone' => $get_trans['contact_phone_1'],
                                            'contact_email' => $get_trans['contact_email_1'],                                                                                                                                                                
                                            'trans_id' => $trans_id,
                                            'trans_number' => $get_trans['trans_number'],
                                            'trans_session' => $get_trans['trans_session'],
                                            'trans_date' => date("d-M-Y, H:i", strtotime($get_trans['trans_date'])),                                        
                                            'payment_method' => array(
                                                'trans_paid_type' => $trans_paid_type,
                                                'trans_paid_type_name' => $trans_paid_type_name
                                            ),
                                            'total' => array(
                                                'sales' => floatval($trans_total),
                                                'sales_before' => floatval($trans_total_before),
                                                'voucher' => floatval($trans_total_paid - $trans_total),
                                                'payment' => floatval($trans_total_paid),
                                                'change' => floatval($trans_total_paid - $trans_total),
                                            )
                                            // 'journal_item_debit_id' => $insert_journal_item_debit,
                                            // 'journal_item_kredit_id' => $insert_journal_item_kredit
                                        ); 
                                    }
                                }else{
                                    $return->message='Nomor sudah digunakan';
                                }
                                $return->params=$params_check;
                            }else{
                                $return->message = 'Mohon masukkan angka pembayaran lebih besar';
                            }
                        }
                    }
                    break;
                case "account-info":
                    $id = $this->input->post('id');
                    $get_account = $this->Account_model->get_account($id);
                    $params = array(
                        'journal_item_account_id' => $id,
                        'journal_item_flag' => 1
                    );
                    $get_journal_item = $this->Journal_model->get_all_journal_item($params,null,10,0,'journal_item_date','desc');
                    $return->status = 1;
                    $return->message = 'Loaded';
                    $return->result = array(
                        'account' => array(
                            'account_id' => $get_account['account_id'],
                            'account_code' => $get_account['account_code'],
                            'account_name' => trim($get_account['account_name']),
                        ),
                        'journals' => $get_journal_item
                    );
                    $return->url = base_url('keuangan/print/');
                    break;
                case "load-account-for-opening-balance":
                    $post = $this->input->post();
                    $total_debit = 0;
                    $total_credit = 0;
    
                    $params = array(
                        'account_branch_id' => $session_branch_id
                    );
                    
                    //Read edit mode
                    $journal_id = !empty($post['journal_id']) ? $post['journal_id'] : 0;
                    if(intval($journal_id) > 0){
                        $params = array(
                            'journal_item_journal_id' => $post['journal_id']
                        );
                        $get_data = array();
                        $get_journal_item = $this->Journal_model->get_all_journal_item($params,null,null,null,'account_code','asc');
                        foreach($get_journal_item as $v){
                            if(intval($v['account_group']) == 1){
                                $group_name = 'Asset';
                            }else if(intval($v['account_group']) ==2){
                                $group_name = 'Liabilitas';
                            }else if(intval($v['account_group']) ==3){
                                $group_name = 'Ekuitas';
                            }else if(intval($v['account_group']) ==4){
                                $group_name = 'Pendapatan';
                            }else if(intval($v['account_group']) ==5){
                                $group_name = 'Biaya';
                            }
    
                            $get_data[] = array(
                                'journal_item_id' => intval($v['journal_item_id']),
                                'journal_item_journal_id' => $v['journal_item_journal_id'],
                                // 'journal_item_group_session' => $v['journal_item_group_session'],
                                // 'journal_item_branch_id' => intval($v['journal_item_branch_id']),
                                // 'journal_item_account_id' => intval($v['journal_item_account_id']),
                                // 'journal_item_trans_id' => intval($v['journal_item_trans_id']),
                                // 'journal_item_order_id' => $v['journal_item_order_id'],
                                // 'journal_item_date' => $v['journal_item_date'],
                                // 'journal_item_type' => intval($v['journal_item_type']),
                                // 'journal_item_type_name' => $v['journal_item_type_name'],
                                'journal_item_debit' => floatval($v['journal_item_debit']),
                                'journal_item_credit' => floatval($v['journal_item_credit']),
                                // 'journal_item_note' => $v['journal_item_note'],
                                // 'journal_item_user_id' => intval($v['journal_item_user_id']),
                                // 'journal_item_date_created' => $v['journal_item_date_created'],
                                // 'journal_item_date_updated' => $v['journal_item_date_updated'],
                                // 'journal_item_flag' => intval($v['journal_item_flag']),
                                // 'journal_item_position' => intval($v['journal_item_position']),
                                // 'journal_item_journal_session' => $v['journal_item_journal_session'],
                                // 'journal_item_session' => $v['journal_item_session'],
                                'account_id' => intval($v['account_id']),
                                'account_branch_id' => intval($v['account_branch_id']),
                                'account_group' => intval($v['account_group']),
                                'account_group_name' => $group_name,                                
                                'account_group_sub' => intval($v['account_group_sub']),
                                'account_group_sub_name' => $v['account_group_sub_name'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                                'account_side' => intval($v['account_side']),
                                'account_show' => intval($v['account_show']),
                                'account_info' => $v['account_info'],
                                'account_flag' => intval($v['account_flag']),
                            );
                            $total_debit = $total_debit + floatval($v['journal_item_debit']);
                            $total_credit = $total_credit + floatval($v['journal_item_credit']);                            
                        }
                        if(count($get_data) > 0){
                            $return->status = 1;
                            $return->message = 'Found account datas';
                            $datas = $get_data;
                        }else{
                            $return->message = 'Accounts not found';
                        }                        
                    }else{
                        $datas = array();
                        $get_account = $this->Account_model->get_all_account($params,null,null,null,'account_code','asc');
                        foreach($get_account as $v){    
    
                            if(intval($v['account_group']) == 1){
                                $group_name = 'Asset';
                            }else if(intval($v['account_group']) ==2){
                                $group_name = 'Liabilitas';
                            }else if(intval($v['account_group']) ==3){
                                $group_name = 'Ekuitas';
                            }else if(intval($v['account_group']) ==4){
                                $group_name = 'Pendapatan';
                            }else if(intval($v['account_group']) ==5){
                                $group_name = 'Biaya';
                            }
    
                            $datas[] = array(
                                'account_id' => intval($v['account_id']),
                                'account_branch_id' => intval($v['account_branch_id']),
                                // 'account_parent_id' => $v['account_parent_id'],
                                'account_group' => intval($v['account_group']),
                                'account_group_name' => $group_name,
                                'account_group_sub' => intval($v['account_group_sub']),
                                'account_group_sub_name' => $v['account_group_sub_name'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                                'account_side' => intval($v['account_side']), //1=Debit, 2=Kredit
                                'account_show' => intval($v['account_show']), //1=Show, 2=Hide
                                // 'account_tree' => intval($v['account_tree']),
                                // 'account_saldo' => intval($v['account_saldo']),
                                'account_info' => $v['account_info'],
                                // 'account_user_id' => intval($v['account_user_id']),
                                // 'account_date_created' => $v['account_date_created'],
                                // 'account_date_updated' => $v['account_date_updated'],
                                'account_flag' => intval($v['account_flag']),
                                // 'account_session' => $v['account_session'],
                                // 'account_locked' => intval($v['account_locked']),
                                'journal_item_debit' => '0.00',
                                'journal_item_credit' => '0.00'
                            );
    
                            // $total_debit = $total_debit + floatval($v['journal_item_debit']);
                            // $total_credit = $total_credit + floatval($v['journal_item_credit']);                            
                        }
    
                        if(count($datas) > 0){
                            $return->status = 1;
                            $return->message = 'Found account datas';
                        }else{
                            $return->message = 'Accounts not found';
                        }
                    }
                    $return->result = $datas;
                    $return->total_records = count($datas);
                    $return->total_debit = $total_debit;
                    $return->total_credit = $total_credit;
                    break;
                case "create-opening-balance":
                    $this->form_validation->set_rules('journal_item_date', 'Tanggal', 'required');
                    $this->form_validation->set_rules('journal_item_list_data', 'Entrian Akun', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $post = $this->input->post();
                        $journal_item_list = !(empty($post['journal_item_list_data'])) ? json_decode($post['journal_item_list_data'], TRUE) : null;
                        $journal_item_date = date('Y-m-d H:i:s', strtotime($post['journal_item_date'].date('H:i:s')));
                        
                        //Save Journal
                        $journal_session = $this->random_code(20);
                        $journal_group_session = substr($journal_session,0,8);
                        $journal_number = $this->request_number_for_journal(0);
                        $params = array(
                            'journal_branch_id' => $session_branch_id,
                            'journal_type' => 0,
                            'journal_number' => $journal_number,
                            'journal_date' => $journal_item_date,
                            'journal_total' => !empty($post['journal_total']) ? intval($post['journal_total']) : null,
                            // 'journal_contact_id' => !empty($post['journal_contact_id']) ? intval($post['journal_contact_id']) : null,
                            // 'journal_paid_type' => !empty($post['journal_paid_type']) ? intval($post['journal_paid_type']) : null,
                            'journal_note' => !empty($post['journal_item_note']) ? $post['journal_item_note'] : null,
                            'journal_date_created' => date("YmdHis"),
                            'journal_date_updated' => date("YmdHis"),
                            'journal_user_id' => $session_user_id,
                            'journal_flag' => 1,
                            'journal_session' => $journal_session,
                        );
                        $save_journal = $this->Journal_model->add_journal($params);

                        if($save_journal){
                            //Save Journal Item
                            foreach($journal_item_list as $i){
                                $item_session = $this->random_code(20);
                                $params_items= array(
                                    'journal_item_journal_id' => $save_journal,
                                    'journal_item_group_session' => $journal_group_session,
                                    'journal_item_branch_id' => $session_branch_id,
                                    'journal_item_account_id' => $i['account_id'],
                                    // 'journal_item_trans_id' => !empty($post['journal_item_trans_id']) ? intval($post['journal_item_trans_id']) : null,
                                    // 'journal_item_order_id' => !empty($post['journal_item_order_id']) ? $post['journal_item_session'] : null,
                                    'journal_item_date' => $journal_item_date,
                                    'journal_item_type' => 0,
                                    'journal_item_type_name' => 'Saldo Awal',
                                    'journal_item_debit' => $i['journal_item_debit'],
                                    'journal_item_credit' => $i['journal_item_credit'],
                                    'journal_item_note' => !empty($post['journal_item_note']) ? $post['journal_item_note'] : null,
                                    'journal_item_user_id' => $session_user_id,
                                    'journal_item_date_created' => date("YmdHis"),
                                    'journal_item_date_updated' => date("YmdHis"),
                                    'journal_item_flag' => 1,
                                    // 'journal_item_position' => !empty($post['journal_item_position']) ? intval($post['journal_item_position']) : null,
                                    'journal_item_journal_session' => $journal_session,
                                    'journal_item_session' => $item_session,
                                );
                                $this->Journal_model->add_journal_item($params_items);
                            }
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 2,
                                'activity_table' => 'journals',
                                'activity_table_id' => $save_journal,
                                'activity_text_2' => $journal_number,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            /* End Activity */
                        }

                        if($save_journal){
                            $return->status=1;
                            $return->message='Berhasil menyimpan';
                            $return->result = array(
                                'journal_id' => $save_journal,
                                'journal_number' => $journal_number,
                                'journal_session' => $journal_session
                            );
                        }
                    }                    
                    break;
                case "update-opening-balance":
                    $this->form_validation->set_rules('journal_id', 'Id', 'required');
                    $this->form_validation->set_rules('journal_item_list_data', 'Entrian Akun', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();
                    }else{
                        $post = $this->input->post();
                        $journal_id = $post['journal_id'];
                        $journal_item_list = !(empty($post['journal_item_list_data'])) ? json_decode($post['journal_item_list_data'], TRUE) : null;
                        $journal_item_date = date('Y-m-d H:i:s', strtotime($post['journal_item_date'].date('H:i:s')));
                        
                        //Update Journal
                        $journal_session = $this->random_code(20);
                        $journal_group_session = substr($journal_session,0,8);
                        $journal_number = $this->request_number_for_journal(0);
                        $params = array(
                            // 'journal_number' => $journal_number,
                            'journal_date' => $journal_item_date,
                            'journal_note' => !empty($post['journal_item_note']) ? $post['journal_item_note'] : null,
                            'journal_date_updated' => date("YmdHis"),
                        );
                        // var_dump($params);die;
                        $update_journal = $this->Journal_model->update_journal($journal_id,$params);

                        if($update_journal){
                            //Save Journal Item
                            foreach($journal_item_list as $i){
                                // $item_session = $this->random_code(20);
                                $item_id = $i['journal_item_id'];
                                $params_items= array(
                                    'journal_item_date' => $journal_item_date,
                                    'journal_item_debit' => $i['journal_item_debit'],
                                    'journal_item_credit' => $i['journal_item_credit'],
                                    'journal_item_note' => !empty($post['journal_item_note']) ? $post['journal_item_note'] : null,
                                    'journal_item_date_updated' => date("YmdHis"),
                                );
                                // var_dump($item_id);die;
                                $this->Journal_model->update_journal_item($item_id,$params_items);
                            }
                            //Aktivitas
                            $params = array(
                                'activity_user_id' => $session_user_id,
                                'activity_branch_id' => $session_branch_id,
                                'activity_action' => 4,
                                'activity_table' => 'journals',
                                'activity_table_id' => $journal_id,
                                'activity_text_2' => $journal_number,
                                'activity_date_created' => date('YmdHis'),
                                'activity_flag' => 0
                            );
                            $this->save_activity($params);
                            /* End Activity */
                        }

                        if($update_journal){
                            $get_journal = $this->Journal_model->get_journal($journal_id);
                            $return->status=1;
                            $return->message='Berhasil memperbarui';
                            $return->result = array(
                                'journal_id' => $get_journal['journal_id'],
                                'journal_number' => $get_journal['journal_number'],
                                'journal_session' => $get_journal['journal_session']
                            );
                        }
                    }                    
                    break;    
                case "down-payment-history":
                    $return->message ='Action not ready';
                    break;
                case "down-payment-balance":
                    $this->form_validation->set_rules('contact_id', 'Kontak', 'required');
                    $this->form_validation->set_rules('journal_type', 'Tipe Jurnal', 'required');
                    $this->form_validation->set_message('required', '{field} wajib diisi');
                    if ($this->form_validation->run() == FALSE){
                        $return->message = validation_errors();                        
                    }else{
                        $balance      = 0;
                        $journal_type = $this->input->post('journal_type');
                        $contact  = $this->input->post('contact_id');
                        
                        $get_contact = $this->Kontak_model->get_kontak($contact);
                        if($journal_type == 6){ //Prepaid Expense
                            $params = array(
                                'journal_item_ref' => $get_contact['contact_session'],
                            );                        
                            $get_balance = $this->Journal_model->get_journal_item_debit_credit_sum($params);                        
                        }else if($journal_type == 7){ //Down Payment
                            $params = array(
                                'journal_item_ref' => $get_contact['contact_session'],
                            );             
                            $get_balance = $this->Journal_model->get_journal_item_credit_debit_sum($params);
                        }
                        
                        if(!empty($get_balance['journal_item_balance'])){
                            $balance = $get_balance['journal_item_balance'];
                        }

                        $return->status  = 1;
                        $return->result  = array(
                            'contact_id' => $get_contact['contact_id'],
                            'contact_type' => $get_contact['contact_type'],                            
                            'contact_name' => $get_contact['contact_name'],
                            'contact_session' => $get_contact['contact_session'],
                            'balance' => $balance,
                            'balance_format' => number_format($balance)
                        );
                        if($balance > 0){
                            $return->total_data = 1;
                        }                        
                        $return->message = 'Mendapatkan Data';
                    }                
                    break;                    
                default:
                    $return->message="No Action";
                    break;
            }
        }
        if(empty($action)){
            $action='';
        }
        $return->action=$action;
        echo json_encode($return);
    }  
    function prints($id){
        $session = $this->session->userdata();        
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        // $this->load->model('Print_spoiler_model');
        // $id=$data['id'];
        // var_dump($id);

        //Header
        $params = array(
            'journal_id' => $id
        );

        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Journal_model->get_journal($id);

        $data['branch'] = $this->Branch_model->get_branch($data['header']['user_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }


        $data['tipe'] = $data['header']['journal_type'];
        $data['title'] = $this->folder_location[$data['tipe']];
    

        $set_header = '';
        $set_header .= '      '.$data['header']['journal_number'];
        $set_header .= '      '.$data['header']['journal_date'];        

        //Content
        $params_items = array(
            'journal_item_journal_id' => $id,
            'journal_item_branch_id' => $session_branch_id,
            'journal_item_position' => 2            
        );
        $search = '';
        $limit  = null;
        $start = 0;
        $order = 'journal_item_date_created';
        $dir = 'ASC';

        $data['content'] = $this->Journal_model->get_all_journal_item($params_items,$search,$limit,$start,$order,$dir);
        
        //Voucher Detect
        $params_items_voucher = array(
            'journal_item_journal_id' => intval($data['header']['journal_id']),
            'journal_item_branch_id' => $session_branch_id,
            'journal_item_position' => 1,
            'account_group' => 4
        );        
        $get_voucher_discount = $this->Journal_model->get_all_journal_item($params_items_voucher,$search,$limit,$start,$order,$dir);
        if(!empty($get_voucher_discount)){
            array_push($data['content'],$get_voucher_discount[0]);
        }

        $data['result'] = array(
            'title' => $data['title']['print_title'],
            'branch' => $data['branch'],
            'header' => $data['header'],
            'content' => $data['content'],
            'footer' => ''
        );

        // echo json_encode($data['result']);die;
        // $content = '';    
        //Set Layout From Order Type
        // if($data['header']['order_type']==1){

        $data['journal_total'] = strtoupper($this->say_number($data['header']['journal_total']).' RUPIAH');
        $data['journal_total_raw'] = !empty($data['header']['journal_total']) ? number_format($data['header']['journal_total'],2,'.',',') : '-';      

        // 1=BayarHutang,2=BayarPiutang,3=KasMasuk,4=KasKeluar,5=MutasiKas,6=UangMukaBeli,7=UangMukaJual        
        //Set Layout From Order Type
        if($data['header']['journal_type']==1){ //Bayar Hutang
            $set_content = array();
            foreach($data['content'] as $v){
                $params_trans = array(
                    'trans_id' => $v['journal_item_trans_id'],
                    'trans_branch_id' => $session_branch_id
                );
                $prepare_trans = $this->Journal_model->get_all_account_payable($params_trans,null,null,null,null);
                $get_trans = $prepare_trans[0];

                $set_content[] = array(
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],                                        
                    'journal_item_id' => $v['journal_item_id'],
                    'journal_item_journal_id' => $v['journal_item_journal_id'],
                    'journal_item_type' => $v['journal_item_type'],
                    'journal_item_trans_id' => $v['journal_item_trans_id'],
                    'journal_item_debit' => $v['journal_item_debit'],
                    'journal_item_credit' => $v['journal_item_credit'],
                    'journal_item_note' => $v['journal_item_note'], 
                    'trans' => $get_trans                   
                );
            }
            $data['content'] = $set_content;
            $data['title'] = $data['header']['journal_number'];            
        }
        else if($data['header']['journal_type']==2){ //Bayar Piutang
            $set_content = array();
            foreach($data['content'] as $v){
                $params_trans = array(
                    'trans_id' => $v['journal_item_trans_id'],
                    'trans_branch_id' => $session_branch_id
                );
                $prepare_trans = $this->Journal_model->get_all_account_payable($params_trans,null,null,null,null);
                $get_trans = $prepare_trans[0];

                $set_content[] = array(
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],  
                    'account_group' => $v['account_group'],                                        
                    'journal_item_id' => $v['journal_item_id'],
                    'journal_item_journal_id' => $v['journal_item_journal_id'],
                    'journal_item_type' => $v['journal_item_type'],
                    'journal_item_trans_id' => $v['journal_item_trans_id'],
                    'journal_item_debit' => $v['journal_item_debit'],
                    'journal_item_credit' => $v['journal_item_credit'],
                    'journal_item_note' => $v['journal_item_note'], 
                    'trans' => $get_trans                   
                );
            }
            $data['content'] = $set_content;            
            $data['title'] = $data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==3){ //Terima Uang
            $data['title'] = $data['header']['journal_number'];      
        }
        else if($data['header']['journal_type']==4){ //Biaya
            $data['title'] = $data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==5){ //Transfer Uang
            $data['title'] = $data['header']['journal_number'];            
        }
        else if($data['header']['journal_type']==6){
            $data['title'] = 'Uang Muka Penjualan - '.$data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==7){
            $data['title'] = 'Uang Muka Pembelian - '.$data['header']['journal_number'];
        }        
        else if($data['header']['journal_type']==8){ //Jurnal Umum
            $data['title'] = $data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==9){ //Kirim Uang
            $data['title'] = $data['header']['journal_number'];
        }                

            $this->load->view($this->folder_location[$data['tipe']]['print'],$data);
        // }   
        // else{
            // $this->load->view('prints/sales_order',$data);
        // }                

        // echo json_encode($set_header);die;
        // 
        // echo "<img src='".base_url('assets/webarch/img/logo/foodpedia_print.png')."' style='width:150px;'>"."\r\n";
        // echo nl2br("\r\n");
        // echo nl2br("OPERASIONAL"."\r\n");
        // $content .= "OPERASIONAL\r\n";
        // echo "\r\n";       
        // echo nl2br($data['header']['journal_number']."\r\n");
        // $content .= $data['header']['journal_number']."\r\n";
        // echo nl2br(date("d-M-Y, H:i", strtotime($data['header']['journal_date']))."\r\n");
        // $content .= date("d-M-Y, H:i", strtotime($data['header']['journal_date']))."\r\n";        
        // // echo nl2br($data['header']['contact_name']."\r\n");
        // echo nl2br("------------------------------"."\r\n");
        // $content .= "-----------------------------------"."\r\n";        
        // foreach($data['content'] AS $v){
        //     // echo $v['product_name']."         ".number_format($v['order_item_total'])."\r\n";
        //     echo nl2br($v['account_name']."\r\n");
        //     $content .= $v['account_name']."\r\n"; 

        //     if($v['journal_item_debit'] > 0){
        //         $total = $v['journal_item_debit'];
        //     }else{
        //         $total = $v['journal_item_credit'];
        //     }
            
        //     echo nl2br("Rp. ".number_format($total)."\r\n");
        //     $content .= "Rp. ".number_format($total)."\r\n";            

        //     echo nl2br("\r\n");
        //     $content .= "\r\n";            
        // }     
        // echo nl2br("------------------------------"."\r\n");
        // $content .= "-----------------------------------"."\r\n";   
        // echo nl2br($data['header']['journal_note']."\r\n");
        // $content .= $data['header']['journal_note']."\r\n";
        // // echo "Terima Kasih atas Kedatangannya"."\r\n";
        // // echo "Di tunggu orderan berikutnya :)"."\r\n";

        // $params = array(
        //     'spoiler_source_table' => 'journals',
        //     'spoiler_source_id' => $id,
        //     'spoiler_content' => $content,
        //     'spoiler_date' => date('YmdHis'),
        //     'spoiler_flag' => 0
        // );
        // $this->Print_spoiler_model->add_print_spoiler($params);
        // echo $content;        
    }
    function print_data($session_data){
        $session = $this->session->userdata();        
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        // $this->load->model('Print_spoiler_model');
        // $id=$data['id'];
        // var_dump($id);

        //Header
        $params = array(
            'journal_session' => $session_data
        );

        // $get_header = $this->Order_model->get_all_orders($params,null,null,null,null,null);
        // $data['header'] = array(
        //     'order_number' => $get_header['order_number'],
        //     'contact_name' => $get_header['contact_name'],
        //     'ref_name' => $get_header['ref_name']
        // );
        $data['header'] = $this->Journal_model->get_journal_custom($params);
        $id = $data['header']['journal_id'];
            // var_dump($id);
        $data['branch'] = $this->Branch_model->get_branch($data['header']['user_branch_id']);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }else{
            $data['branch_logo'] = !empty($data['branch']['branch_logo_sidebar']) ? site_url().$data['branch']['branch_logo_sidebar'] : site_url().'upload/branch/default_sidebar.png';
        }


        $data['tipe'] = $data['header']['journal_type'];
        $data['title'] = $this->folder_location[$data['tipe']];
    

        $set_header = '';
        $set_header .= '      '.$data['header']['journal_number'];
        $set_header .= '      '.$data['header']['journal_date'];        

        //Content
        $params_items = array(
            'journal_item_journal_id' => $id,
            'journal_item_branch_id' => $session_branch_id,
            'journal_item_position' => 2            
        );
        $search = '';
        $limit  = null;
        $start = 0;
        $order = 'journal_item_date_created';
        $dir = 'ASC';

        $data['content'] = $this->Journal_model->get_all_journal_item($params_items,$search,$limit,$start,$order,$dir);
        
        //Voucher Detect
        $params_items_voucher = array(
            'journal_item_journal_id' => intval($data['header']['journal_id']),
            'journal_item_branch_id' => $session_branch_id,
            'journal_item_position' => 1,
            'account_group' => 4
        );        
        $get_voucher_discount = $this->Journal_model->get_all_journal_item($params_items_voucher,$search,$limit,$start,$order,$dir);
        if(!empty($get_voucher_discount)){
            array_push($data['content'],$get_voucher_discount[0]);
        }

        $data['result'] = array(
            'title' => $data['title']['print_title'],
            'branch' => $data['branch'],
            'header' => $data['header'],
            'content' => $data['content'],
            'footer' => ''
        );

        // echo json_encode($data['result']);die;
        // $content = '';    
        //Set Layout From Order Type
        // if($data['header']['order_type']==1){

        $data['journal_total'] = strtoupper($this->say_number($data['header']['journal_total']).' RUPIAH');
        $data['journal_total_raw'] = !empty($data['header']['journal_total']) ? number_format($data['header']['journal_total'],2,'.',',') : '-';      

        // 1=BayarHutang,2=BayarPiutang,3=KasMasuk,4=KasKeluar,5=MutasiKas,6=UangMukaBeli,7=UangMukaJual        
        //Set Layout From Order Type
        if($data['header']['journal_type']==1){ //Bayar Hutang
            $set_content = array();
            foreach($data['content'] as $v){
                $params_trans = array(
                    'trans_id' => $v['journal_item_trans_id'],
                    'trans_branch_id' => $session_branch_id
                );
                $prepare_trans = $this->Journal_model->get_all_account_payable($params_trans,null,null,null,null);
                $get_trans = $prepare_trans[0];

                $set_content[] = array(
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],
                    'account_group' => $v['account_group'],                                        
                    'journal_item_id' => $v['journal_item_id'],
                    'journal_item_journal_id' => $v['journal_item_journal_id'],
                    'journal_item_type' => $v['journal_item_type'],
                    'journal_item_trans_id' => $v['journal_item_trans_id'],
                    'journal_item_debit' => $v['journal_item_debit'],
                    'journal_item_credit' => $v['journal_item_credit'],
                    'journal_item_note' => $v['journal_item_note'], 
                    'trans' => $get_trans                   
                );
            }
            $data['content'] = $set_content;
            $data['title'] = $data['header']['journal_number'];            
        }
        else if($data['header']['journal_type']==2){ //Bayar Piutang
            $set_content = array();
            foreach($data['content'] as $v){
                $params_trans = array(
                    'trans_id' => $v['journal_item_trans_id'],
                    'trans_branch_id' => $session_branch_id
                );
                $prepare_trans = $this->Journal_model->get_all_account_payable($params_trans,null,null,null,null);
                $get_trans = $prepare_trans[0];

                $set_content[] = array(
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],   
                    'account_group' => $v['account_group'],
                    'journal_item_id' => $v['journal_item_id'],
                    'journal_item_journal_id' => $v['journal_item_journal_id'],
                    'journal_item_type' => $v['journal_item_type'],
                    'journal_item_trans_id' => $v['journal_item_trans_id'],
                    'journal_item_debit' => $v['journal_item_debit'],
                    'journal_item_credit' => $v['journal_item_credit'],
                    'journal_item_note' => $v['journal_item_note'], 
                    'trans' => $get_trans                   
                );
            }
            $data['content'] = $set_content;            
            $data['title'] = $data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==3){ //Terima Uang
            $data['title'] = $data['header']['journal_number'];      
        }
        else if($data['header']['journal_type']==4){ //Biaya
            $data['title'] = $data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==5){ //Transfer Uang
            $data['title'] = $data['header']['journal_number'];            
        }
        else if($data['header']['journal_type']==6){ //Uang Muka Beli
            $data['title'] = $data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==7){ //Uang Muka Jual
            $data['title'] = $data['header']['journal_number'];
        }        
        else if($data['header']['journal_type']==8){ //Jurnal Umum
            $data['title'] = $data['header']['journal_number'];
        }
        else if($data['header']['journal_type']==9){ //Kirim Uang
            $data['title'] = $data['header']['journal_number'];
        }                

            $this->load->view($this->folder_location[$data['tipe']]['print'],$data);
        // }   
        // else{
            // $this->load->view('prints/sales_order',$data);
        // }                

        // echo json_encode($set_header);die;
        // 
        // echo "<img src='".base_url('assets/webarch/img/logo/foodpedia_print.png')."' style='width:150px;'>"."\r\n";
        // echo nl2br("\r\n");
        // echo nl2br("OPERASIONAL"."\r\n");
        // $content .= "OPERASIONAL\r\n";
        // echo "\r\n";       
        // echo nl2br($data['header']['journal_number']."\r\n");
        // $content .= $data['header']['journal_number']."\r\n";
        // echo nl2br(date("d-M-Y, H:i", strtotime($data['header']['journal_date']))."\r\n");
        // $content .= date("d-M-Y, H:i", strtotime($data['header']['journal_date']))."\r\n";        
        // // echo nl2br($data['header']['contact_name']."\r\n");
        // echo nl2br("------------------------------"."\r\n");
        // $content .= "-----------------------------------"."\r\n";        
        // foreach($data['content'] AS $v){
        //     // echo $v['product_name']."         ".number_format($v['order_item_total'])."\r\n";
        //     echo nl2br($v['account_name']."\r\n");
        //     $content .= $v['account_name']."\r\n"; 

        //     if($v['journal_item_debit'] > 0){
        //         $total = $v['journal_item_debit'];
        //     }else{
        //         $total = $v['journal_item_credit'];
        //     }
            
        //     echo nl2br("Rp. ".number_format($total)."\r\n");
        //     $content .= "Rp. ".number_format($total)."\r\n";            

        //     echo nl2br("\r\n");
        //     $content .= "\r\n";            
        // }     
        // echo nl2br("------------------------------"."\r\n");
        // $content .= "-----------------------------------"."\r\n";   
        // echo nl2br($data['header']['journal_note']."\r\n");
        // $content .= $data['header']['journal_note']."\r\n";
        // // echo "Terima Kasih atas Kedatangannya"."\r\n";
        // // echo "Di tunggu orderan berikutnya :)"."\r\n";

        // $params = array(
        //     'spoiler_source_table' => 'journals',
        //     'spoiler_source_id' => $id,
        //     'spoiler_content' => $content,
        //     'spoiler_date' => date('YmdHis'),
        //     'spoiler_flag' => 0
        // );
        // $this->Print_spoiler_model->add_print_spoiler($params);
        // echo $content;        
    }
    function test(){

        $session = $this->session->userdata();        
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';   

        $params = array(
            'account_branch_id' => $session_branch_id
        );
        $datas = array();
        $get_account = $this->Account_model->get_all_account($params,null,null,null,'account_code','asc');
        foreach($get_account as $v){    

            if(intval($v['account_group']) == 1){
                $group_name = 'Asset';
            }else if(intval($v['account_group']) ==2){
                $group_name = 'Liabilitas';
            }else if(intval($v['account_group']) ==3){
                $group_name = 'Ekuitas';
            }else if(intval($v['account_group']) ==4){
                $group_name = 'Pendapatan';
            }else if(intval($v['account_group']) ==5){
                $group_name = 'Biaya';
            }

            $datas[] = array(
                'account_id' => intval($v['account_id']),
                'account_branch_id' => intval($v['account_branch_id']),
                // 'account_parent_id' => $v['account_parent_id'],
                'account_group' => intval($v['account_group']),
                'account_group_name' => $group_name,
                'account_group_sub' => intval($v['account_group_sub']),
                'account_group_sub_name' => $v['account_group_sub_name'],
                'account_code' => $v['account_code'],
                'account_name' => $v['account_name'],
                'account_side' => intval($v['account_side']), //1=Debit, 2=Kredit
                'account_show' => intval($v['account_show']), //1=Show, 2=Hide
                // 'account_tree' => intval($v['account_tree']),
                // 'account_saldo' => intval($v['account_saldo']),
                'account_info' => $v['account_info'],
                // 'account_user_id' => intval($v['account_user_id']),
                // 'account_date_created' => $v['account_date_created'],
                // 'account_date_updated' => $v['account_date_updated'],
                'account_flag' => intval($v['account_flag']),
                // 'account_session' => $v['account_session'],
                // 'account_locked' => intval($v['account_locked']),
            );
        }

        if(count($datas) > 0){
            $return->status = 1;
            $return->message = 'Found account datas';
        }else{
            $return->message = 'Accounts not found';
        }
        $return->result = $datas;
        $return->total_records = count($datas);
        echo json_encode($return);
    }
}
