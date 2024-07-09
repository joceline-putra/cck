<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller{
    function __construct(){
        parent::__construct();
        
        if(!$this->is_logged_in()){

            //Will Return to Last URL Where session is empty
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));
        }                
        $this->load->model('User_model');    
        $this->load->model('Account_model');
        $this->load->model('Transaksi_model'); 
        $this->load->model('Order_model'); 
        $this->load->model('Journal_model'); 
        $this->load->model('Branch_model');
        $this->load->model('Produk_model');
        $this->load->model('Lokasi_model');              
        $this->load->model('Kontak_model');     
        $this->load->model('Kategori_model');
        $this->load->model('Type_model');
        $this->load->model('Front_model');        

        $this->journal_url = site_url('keuangan/print/');
        $this->trans_url = site_url('transaksi/print_history/');
        $this->order_url = site_url('order/print/');       

        $this->customer_alias   = 'Customer';
        $this->supplier_alias   = 'Supplier';
        $this->employee_alias   = 'Karyawan';

        $this->product_alias    = 'Produk';
        $this->ref_alias        = 'Room';             

        $this->so_alias         = 'Sales Order';
        $this->po_alias         = 'Purchase Order';
        $this->pos_alias        = 'POS';                                    

        $this->buy_alias        = 'Pembelian';
        $this->sell_alias       = 'Penjualan';                                                    
    }
    function index(){
        $data['session'] = $this->session->userdata();  
        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
        $data['identity'] = 0;
        
        if($this->input->post('action')){
            $action         = $this->input->post('action');
            $post_data      = $this->input->post('data');
            $data           = json_decode($post_data, TRUE);
            $identity       = $this->input->post('tipe');
            $trans_type     = $this->input->post('trans_type');

            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            switch($action){
                case "load":
                        $return->message="Not Ready";
                    break;
                case "load-report-purchase-buy-recap":
                        $return->message="Not Ready";                    
                    break;                    
                case "load-report-purchase-buy-detail":
                        $return->message="Not Ready";
                    break;                        
                case "load-report-purchase-buy-account-payable":
                    $columns = array(
                        '0' => 'trans_date',
                        '1' => 'trans_number',
                        '2' => 'trans_total',
                        '3' => 'contact_name'
                    );

                    $limit  = $this->input->post('length');
                    $start  = $this->input->post('start');
                    $order  = !empty($this->input->post('order')) ? $columns[$this->input->post('order')[0]['column']] : $columns[0];
                    $dir    = $this->input->post('order')[0]['dir'];

                    $search = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }
                    $date_start = !empty($this->input->post('date_start')) ? date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00')) : date('Y-m-01');
                    $date_end   = !empty($this->input->post('date_end')) ? date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59')) : date('Y-m-d');

                    $contact_id = !empty($this->input->post('contact')) ? $this->input->post('contact') : 0;
                    
                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_finance(4,$date_start,$date_end,$session_branch_id,$contact_id,$search);
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            $mdatas[] = array(                                 
                                'temp_id' => $v['temp_id'],
                                'type_name' => $v['type_name'],
                                'trans_date' => $v['trans_date'],
                                'trans_date_due' => $v['trans_date_due'],
                                'trans_date_due_over' => $v['trans_date_due_over'],
                                'trans_id' => $v['trans_id'],
                                'trans_note' => $v['trans_note'],
                                'trans_number' => $v['trans_number'],
                                'trans_total' => $v['trans_total'],
                                'trans_total_paid' => $v['trans_total_paid'],
                                'contact_id' => $v['contact_id'],
                                'contact_code' => $v['contact_code'],
                                'contact_name' => $v['contact_name'],
                                'balance' => $v['balance'],
                                'trans_date_format' => date("d/m/Y", strtotime($v['trans_date'])),
                                'trans_date_due_format' => date("d/m/Y", strtotime($v['trans_date_due'])),                                
                                'status' => $v['status'],
                                'message' => $v['message'],
                                'total_data' => $v['total_data'],
                                'trans_session' => $v['trans_session']
                            );
                        }
                        $total_data = $v['total_data'];
                    endforeach;
                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    echo json_encode($return);
                    break;  
                case "load-report-sales-sell-recap":
                    break;                    
                case "load-report-sales-sell-detail":
                    break;   
                case "load-report-sales-sell-account-receivable":
                    $columns = array(
                        '0' => 'trans_date',
                        '1' => 'trans_number',
                        '2' => 'trans_total',
                        '3' => 'contact_name'
                    );

                    $limit  = $this->input->post('length');
                    $start  = $this->input->post('start');
                    $order  = !empty($this->input->post('order')) ? $columns[$this->input->post('order')[0]['column']] : $columns[0];
                    $dir    = $this->input->post('order')[0]['dir'];

                    $search = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }
                    $date_start = !empty($this->input->post('date_start')) ? date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00')) : date('Y-m-01');
                    $date_end   = !empty($this->input->post('date_end')) ? date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59')) : date('Y-m-d');
                    $contact_id = !empty($this->input->post('contact')) ? $this->input->post('contact') : 0;
                    
                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_finance(5,$date_start,$date_end,$session_branch_id,$contact_id,$search);
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            $mdatas[] = array(                                 
                                'temp_id' => $v['temp_id'],
                                'type_name' => $v['type_name'],
                                'trans_date' => $v['trans_date'],
                                'trans_date_due' => $v['trans_date_due'],
                                'trans_date_due_over' => $v['trans_date_due_over'],                                
                                'trans_id' => $v['trans_id'],
                                'trans_note' => $v['trans_note'],
                                'trans_number' => $v['trans_number'],
                                'trans_total' => $v['trans_total'],
                                'trans_total_paid' => $v['trans_total_paid'],
                                'contact_id' => $v['contact_id'],
                                'contact_code' => $v['contact_code'],
                                'contact_name' => $v['contact_name'],
                                'balance' => $v['balance'],
                                'trans_date_format' => date("d/m/Y", strtotime($v['trans_date'])),
                                'trans_date_due_format' => date("d/m/Y", strtotime($v['trans_date_due'])),                                
                                'status' => $v['status'],
                                'message' => $v['message'],
                                'total_data' => $v['total_data'],
                                'trans_session' => $v['trans_session']
                            );
                        }
                        $total_data = $v['total_data'];
                    endforeach;
                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    echo json_encode($return);
                    break;  

                case "load-report-stock-warehouse":
                    break;                      
                case "load-report-stock-moving":
                    break;                                          
                case "load-report-journal":
                    $columns = array(
                        '0' => 'journal_item_group_session',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

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
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $account_id = !empty($this->input->post('account')) ? $this->input->post('account') : 0;
                    
                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_finance(1,$date_start,$date_end,$session_branch_id,$account_id,$search);
                    // log_message('debug',$get_datas);die;
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            if(strlen($v['journal_number']) > 1){
                                $url = $this->journal_url.$v['journal_session'];
                            }else if(strlen($v['trans_number']) > 1){
                                $url = $this->trans_url.$v['trans_session'];
                            }else{
                                $url = '#';
                            }                            
                            // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                            $mdatas[] = array(                            
                                'journal_group_session' => $v['journal_item_group_session'],
                                'journal_text' => $v['journal_text'],
                                'type_name' => $v['type_name'],                                    
                                'temp_id' => $v['temp_id'],
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_note' => $v['journal_item_note'],
                                'journal_number' => $v['journal_number'],                       
                                'trans_id' => $v['trans_id'],
                                'trans_number' => $v['trans_number'],
                                'trans_session' => $v['trans_session'],
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                                'debit' => $v['debit'],
                                'credit' => $v['credit'],
                                'balance' => $v['balance'],
                                'journal_item_date_format' => $v['journal_item_date_format'],
                                'status' => $v['status'],
                                'message' => $v['message'],
                                'total_data' => $v['total_data'],
                                'journal_session' => $v['journal_session'],
                                'trans_session' => $v['trans_session'],
                                'journal_id' => $v['journal_id'],
                                'trans_id' => $v['trans_id'],
                                'order_id' => $v['order_id'],
                                'journal_text' => $v['journal_text'],
                                'contact_name' => $v['contact_name'],
                                'url' => $url                                
                            );
                        }
                        $total_data = $v['total_data'];
                    endforeach;

                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    echo json_encode($return);
                    break;                
                case "load-report-ledger":
                    $columns = array(
                        '0' => 'journal_item_date',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

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
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $account_id = !empty($this->input->post('account')) ? $this->input->post('account') : 0;
                    
                    $total_data = 0;
                    $mdatas = array();
                    if($account_id > 0){
                        $get_datas = $this->report_finance(2,$date_start,$date_end,$session_branch_id,$account_id,$search);
                        foreach($get_datas as $k => $v):
                            if(intval($v['total_data']) > 0){
                                if(strlen($v['journal_number']) > 1){
                                    $url = $this->journal_url.$v['journal_session'];
                                }else if(strlen($v['trans_number']) > 1){
                                    $url = $this->trans_url.$v['trans_session'];
                                }else{
                                    $url = '#';
                                }                                   
                                $mdatas[] = array(
                                    'type_name' => $v['type_name'],                                    
                                    'temp_id' => $v['temp_id'],
                                    'journal_item_id' => $v['journal_item_id'],
                                    'journal_item_note' => $v['journal_item_note'],
                                    'journal_number' => $v['journal_number'],                       
                                    'trans_number' => $v['trans_number'],
                                    'account_id' => $v['account_id'],
                                    'account_code' => $v['account_code'],
                                    'account_name' => $v['account_name'],
                                    'debit' => $v['debit'],
                                    'credit' => $v['credit'],
                                    'balance' => $v['balance'],
                                    'journal_item_date_format' => $v['journal_item_date_format'],
                                    'status' => $v['status'],
                                    'message' => $v['message'],
                                    'total_data' => $v['total_data'],
                                    'journal_session' => $v['journal_session'],
                                    'trans_session' => $v['trans_session'],
                                    'journal_id' => $v['journal_id'],
                                    'trans_id' => $v['trans_id'],
                                    'order_id' => $v['order_id'],
                                    'contact_name' => $v['contact_name'],
                                    'url' => $url      
                                );
                            }
                            $total_data = $v['total_data'];
                        endforeach;
                    }
                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    echo json_encode($return);
                    break;                
                case "load-report-trial-balance":
                    $columns = array(
                        '0' => 'journal_item_group_session',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

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
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $account_id = !empty($this->input->post('account')) ? $this->input->post('account') : 0;
                    
                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_finance(3,$date_start,$date_end,$session_branch_id,$account_id,$search);
                    // log_message('debug',$get_datas);die;
                    // var_dump($date_start,$date_end,$session_branch_id,$account_id,$search);
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                            $mdatas[] = array(
                                'parent_id' => $v['parent_id'],
                                'account_group' => $v['account_group'],
                                'group_sub' => $v['group_sub'],                                
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                                'start_debit' => $v['start_debit'],
                                'start_credit' => $v['start_credit'],
                                'movement_debit' => $v['movement_debit'],
                                'movement_credit' => $v['movement_credit'],
                                'end_debit' => $v['end_debit'],
                                'end_credit' => $v['end_credit'],
                                'profit_loss_debit' => $v['profit_loss_debit'],
                                'profit_loss_credit' => $v['profit_loss_credit'],
                                'balance_debit' => $v['balance_debit'],
                                'balance_credit' => $v['balance_credit'],                                      
                                // 'journal_item_date_format' => $v['journal_item_date_format'],
                                'status' => $v['status'],
                                'message' => $v['message'],
                                'total_data' => $v['total_data']
                            );
                        }
                        $total_data = $v['total_data'];
                    endforeach;

                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    echo json_encode($return);                
                    break;
                case "load-report-profit-loss":
                    $columns = array(
                        '0' => 'journal_item_group_session',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

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
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $account_id = !empty($this->input->post('account')) ? $this->input->post('account') : 0;
                    
                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_finance(3,$date_start,$date_end,$session_branch_id,$account_id,$search);
                    // log_message('debug',$get_datas);die;
                    // var_dump($date_start,$date_end,$session_branch_id,$account_id,$search);
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                            if(intval($v['parent_id']) > 3){
                                $mdatas[] = array(
                                    'parent_id' => $v['parent_id'],
                                    'account_group' => $v['account_group'],
                                    'group_sub' => $v['group_sub'],                                
                                    'account_id' => $v['account_id'],
                                    'account_code' => $v['account_code'],
                                    'account_name' => $v['account_name'],
                                    'start_debit' => $v['start_debit'],
                                    'start_credit' => $v['start_credit'],
                                    'movement_debit' => $v['movement_debit'],
                                    'movement_credit' => $v['movement_credit'],
                                    'end_debit' => $v['end_debit'],
                                    'end_credit' => $v['end_credit'],
                                    'profit_loss_debit' => $v['profit_loss_debit'],
                                    'profit_loss_credit' => $v['profit_loss_credit'],
                                    'profit_loss_end' => $v['profit_loss_end'],                                    
                                    'balance_debit' => $v['balance_debit'],
                                    'balance_credit' => $v['balance_credit'],                                      
                                    'balance_credit' => $v['balance_end'],                                                                          
                                    // 'journal_item_date_format' => $v['journal_item_date_format'],
                                    'status' => $v['status'],
                                    'message' => $v['message'],
                                    'total_data' => $v['total_data']
                                );
                            }
                        }
                        $total_data = $v['total_data'];
                    endforeach;

                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    echo json_encode($return);                    
                    break;                        
                case "load-report-balance":
                    $columns = array(
                        '0' => 'journal_item_group_session',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

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
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $account_id = !empty($this->input->post('account')) ? $this->input->post('account') : 0;
                    
                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_finance(3,$date_start,$date_end,$session_branch_id,$account_id,$search);
                    // log_message('debug',$get_datas);die;
                    // var_dump($date_start,$date_end,$session_branch_id,$account_id,$search);
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                            if( ((intval($v['parent_id']) > 0) and (intval($v['parent_id']) < 4)) or (intval($v['parent_id']) == 6)){
                                $mdatas[] = array(
                                    'parent_id' => $v['parent_id'],
                                    'account_group' => $v['account_group'],
                                    'group_sub' => $v['group_sub'],                                
                                    'account_id' => $v['account_id'],
                                    'account_code' => $v['account_code'],
                                    'account_name' => $v['account_name'],
                                    'start_debit' => $v['start_debit'],
                                    'start_credit' => $v['start_credit'],
                                    'movement_debit' => $v['movement_debit'],
                                    'movement_credit' => $v['movement_credit'],
                                    'end_debit' => $v['end_debit'],
                                    'end_credit' => $v['end_credit'],
                                    'profit_loss_debit' => $v['profit_loss_debit'],
                                    'profit_loss_credit' => $v['profit_loss_credit'],
                                    'profit_loss_end' => $v['profit_loss_end'],                                    
                                    'balance_debit' => $v['balance_debit'],
                                    'balance_credit' => $v['balance_credit'],                             
                                    'balance_end' => $v['balance_end'],                             
                                    // 'journal_item_date_format' => $v['journal_item_date_format'],
                                    'status' => $v['status'],
                                    'message' => $v['message'],
                                    'total_data' => $v['total_data']
                                );
                            }
                        }
                        $total_data = $v['total_data'];
                    endforeach;

                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=$total;
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=$total;
                        $return->result=0;    
                    }
                    $return->recordsTotal = $total;
                    $return->recordsFiltered = $total;
                    echo json_encode($return);                    
                    break;                        
                case "load-report-cash-in":
                    $columns = array(
                        '0' => 'journal_item_date',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

                    $limit = $this->input->post('length');
                    $start = $this->input->post('start');
                    $order = $columns[$this->input->post('order')[0]['column']];
                    $dir = $this->input->post('order')[0]['dir'];

                    /*
                    $search = [];
                    if ($this->input->post('search')['value']) {
                        $s = $this->input->post('search')['value'];
                        foreach ($columns as $v) {
                            $search[$v] = $s;
                        }
                    }
                    */
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $account_id = !empty($this->input->post('account')) ? $this->input->post('account') : 0;
                    $search = !empty($this->input->post('filter_type')) ? $this->input->post('filter_type') : 0;

                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_cashflow(6,$date_start,$date_end,$session_branch_id,$account_id,$search,$start,$limit);
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            if(strlen($v['journal_number']) > 1){
                                $url = $this->journal_url.$v['journal_session'];
                            }else if(strlen($v['trans_number']) > 1){
                                $url = $this->trans_url.$v['trans_session'];
                            }else{
                                $url = '#';
                            }                                   
                            $mdatas[] = array(
                                'type_name' => $v['type_name'],                                    
                                'temp_id' => $v['temp_id'],
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_note' => $v['journal_item_note'],
                                'journal_number' => $v['journal_number'],                       
                                'trans_number' => $v['trans_number'],
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                                'debit' => $v['debit'],
                                'credit' => $v['credit'],
                                'balance' => $v['balance'],
                                'journal_item_date_format' => $v['journal_item_date_format'],
                                'status' => $v['status'],
                                'message' => $v['message'],
                                'total_data' => $v['total_data'],
                                'journal_session' => $v['journal_session'],
                                'trans_session' => $v['trans_session'],
                                'journal_id' => $v['journal_id'],
                                'trans_id' => $v['trans_id'],
                                'order_id' => $v['order_id'],
                                'contact_name' => $v['contact_name'],
                                'url' => $url      
                            );
                        }
                        $total_data = $v['total_data'];
                    endforeach;
                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=intval($total);
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=intval($total);
                        $return->result=0;    
                    }
                    $return->recordsTotal = intval($total);
                    $return->recordsFiltered = intval($total);
                    echo json_encode($return);                        
                    break;
                case "load-report-cash-out":
                    $columns = array(
                        '0' => 'journal_item_date',
                        '1' => 'journal_item_account_id',
                        '2' => 'journal_item_debit',
                        '3' => 'journal_item_credit'
                    );

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
                    $date_start = date('Y-m-d', strtotime($this->input->post('date_start').' 00:00:00'));
                    $date_end = date('Y-m-d', strtotime($this->input->post('date_end').' 23:59:59'));

                    $account_id = !empty($this->input->post('account')) ? $this->input->post('account') : 0;
                    $search = !empty($this->input->post('filter_type')) ? $this->input->post('filter_type') : 0;

                    $total_data = 0;
                    $mdatas = array();
                    $get_datas = $this->report_cashflow(7,$date_start,$date_end,$session_branch_id,$account_id,$search,$start,$limit);
                    foreach($get_datas as $k => $v):
                        if(intval($v['total_data']) > 0){
                            if(strlen($v['journal_number']) > 1){
                                $url = $this->journal_url.$v['journal_session'];
                            }else if(strlen($v['trans_number']) > 1){
                                $url = $this->trans_url.$v['trans_session'];
                            }else{
                                $url = '#';
                            }                                   
                            $mdatas[] = array(
                                'type_name' => $v['type_name'],                                    
                                'temp_id' => $v['temp_id'],
                                'journal_item_id' => $v['journal_item_id'],
                                'journal_item_note' => $v['journal_item_note'],
                                'journal_number' => $v['journal_number'],                       
                                'trans_number' => $v['trans_number'],
                                'account_id' => $v['account_id'],
                                'account_code' => $v['account_code'],
                                'account_name' => $v['account_name'],
                                'debit' => $v['debit'],
                                'credit' => $v['credit'],
                                'balance' => $v['balance'],
                                'journal_item_date_format' => $v['journal_item_date_format'],
                                'status' => $v['status'],
                                'message' => $v['message'],
                                'total_data' => $v['total_data'],
                                'journal_session' => $v['journal_session'],
                                'trans_session' => $v['trans_session'],
                                'journal_id' => $v['journal_id'],
                                'trans_id' => $v['trans_id'],
                                'order_id' => $v['order_id'],
                                'contact_name' => $v['contact_name'],
                                'url' => $url      
                            );
                        }
                        $total_data = $v['total_data'];
                    endforeach;
                    if(intval($total_data) > 0){
                        $total=$total_data;
                        $return->status=1; 
                        $return->message='Loaded'; 
                        $return->total_records=intval($total);
                        $return->result=$mdatas;        
                    }else{ 
                        $data_source=0; $total=0; 
                        $return->status=0; $return->message='No data'; $return->total_records=intval($total);
                        $return->result=0;    
                    }
                    $return->recordsTotal = intval($total);
                    $return->recordsFiltered = intval($total);
                    echo json_encode($return);                        
                break;                 
            }
        }else{

            // Date Now
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('Y-m-d');        
            $datenow =date("d-m-Y");         
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = $datenow;

            /*
            // Reference Model
                $this->load->model('Reference_model');
                $data['reference'] = $this->Reference_model->get_all_reference();
            */
                
            $data['title'] = 'Report';
            $data['_view'] = 'layouts/admin/menu/report/index';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/report/index_js',$data);
        }        
    }
    function pages($request, $identity, $version = null){
        $session = $this->session->userdata(); 
        $data['session'] = $this->session->userdata();     
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);
       
        //Date First of the month
        $firstdate = new DateTime('first day of this month');
        $firstdateofmonth = $firstdate->format('d-m-Y');

        if($request=='purchase'){
            if($identity == 'buy'){ //Purchase Order

                if($version == 1){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Pembelian Rekap';
                    $data['_view']  = 'layouts/admin/menu/report/purchase/recap';
                    $file_js        = 'layouts/admin/menu/report/purchase/recap_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 2){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Pembelian Rinci';
                    $data['_view']  = 'layouts/admin/menu/report/purchase/detail';
                    $file_js        = 'layouts/admin/menu/report/purchase/detail_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 3){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Hutang Supplier';
                    $data['_view']  = 'layouts/admin/menu/report/purchase/account_payable';
                    $file_js        = 'layouts/admin/menu/report/purchase/account_payable_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 4){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Purchase Order Rekap';
                    $data['_view']  = 'layouts/admin/menu/report/purchase/order/recap';
                    $file_js        = 'layouts/admin/menu/report/purchase/order/recap_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 5){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Purchase Order Rinci';
                    $data['_view']  = 'layouts/admin/menu/report/purchase/order/detail';
                    $file_js        = 'layouts/admin/menu/report/purchase/order/detail_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 6){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Retur Pembelian';
                    $data['_view']  = 'layouts/admin/menu/report/purchase/return';
                    $file_js        = 'layouts/admin/menu/report/purchase/return_js.php';
                    // $data['trans_type'] = 1;
                }
            }
        }else if($request=='sales'){
            if($identity == 'sell'){ //Purchase Order

                if($version == 1){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Penjualan Rekap';
                    $data['_view']  = 'layouts/admin/menu/report/sales/recap';
                    $file_js        = 'layouts/admin/menu/report/sales/recap_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 2){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Penjualan Warmindo';
                    $data['_view']  = 'layouts/admin/menu/report/sales/detail';
                    $file_js        = 'layouts/admin/menu/report/sales/detail_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 3){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Piutang Customer';
                    $data['_view']  = 'layouts/admin/menu/report/sales/account_receivable';
                    $file_js        = 'layouts/admin/menu/report/sales/account_receivable_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 4){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Sales Order Rekap';
                    $data['_view']  = 'layouts/admin/menu/report/sales/order/recap';
                    $file_js        = 'layouts/admin/menu/report/sales/order/recap_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 5){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Penjualan Kamar'; // 'Laporan Sales Order Rinci';
                    $data['_view']  = 'layouts/admin/menu/report/sales/order/detail';
                    $file_js        = 'layouts/admin/menu/report/sales/order/detail_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 6){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Retur Penjualan';
                    $data['_view']  = 'layouts/admin/menu/report/sales/return';
                    $file_js        = 'layouts/admin/menu/report/sales/return_js.php';
                    // $data['trans_type'] = 1;
                }   else if ($version == 7){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Prepare Rinci';
                    $data['_view']  = 'layouts/admin/menu/report/sales/order/prepare_detail';
                    $file_js        = 'layouts/admin/menu/report/sales/order/prepare_detail_js.php';
                    // $data['trans_type'] = 1;
                }             
            }
        }else if($request=='production'){
            if($identity == 'product'){ //Product Finish

                if ($version == 1){
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Produksi Produk Jadi Rinci';
                    $data['_view']  = 'layouts/admin/menu/report/production/detail';
                    $file_js        = 'layouts/admin/menu/report/production/detail_js.php';
                    // $data['trans_type'] = 1;
                }
            }
        }else if($request=='inventory'){
            if($identity == 'product'){

                if($version == 1){
                    // $data['identity'] = 1;
                    
                    //Get MIN Date of Trans Items
                    $firstdateofmonth = $this->db->query("SELECT MIN(trans_item_date) AS trans_item_date FROM trans_items")->row_array();
                    $firstdateofmonth = date("d-m-Y", strtotime($firstdateofmonth['trans_item_date']));
                    
                    $data['title']  = 'Laporan Stok Gudang';
                    $data['_view']  = 'layouts/admin/menu/report/inventory/warehouse';
                    $file_js        = 'layouts/admin/menu/report/inventory/warehouse_js.php';
                    // $data['trans_type'] = 1;
                }else if ($version == 2){
                    //Get MIN Date of Trans Items
                    // $firstdateofapp = $this->db->query("SELECT MIN(trans_item_date) AS trans_item_date FROM trans_items")->row_array();
                    // $firstdateofapp = date("d-m-Y", strtotime($firstdateofapp['trans_item_date']));
                    // $data['first_date_app'] = $firstdateofapp;
                    // var_dump($data['first_date_app']);die;
                    $data['branch'] = $this->Branch_model->get_all_branch(['branch_flag'=>1],null,null,null,'branch_name','asc');
                    // $data['identity'] = 1;
                    $data['title']  = 'Laporan Pergerakan Stok';
                    $data['_view']  = 'layouts/admin/menu/report/inventory/moving';
                    $file_js        = 'layouts/admin/menu/report/inventory/moving_js.php';
                    // $data['trans_type'] = 1;
                }else if($version == 3){
                    // $data['identity'] = 1;
                    
                    //Get MIN Date of Trans Items
                    $firstdateofmonth = $this->db->query("SELECT MIN(trans_item_date) AS trans_item_date FROM trans_items")->row_array();
                    $firstdateofmonth = date("d-m-Y", strtotime($firstdateofmonth['trans_item_date']));
                    
                    $data['title']  = 'Laporan Nilai Stok Gudang';
                    $data['_view']  = 'layouts/admin/menu/report/inventory/valuation';
                    $file_js        = 'layouts/admin/menu/report/inventory/valuation_js.php';
                    // $data['trans_type'] = 1;
                }
            }
        }else if($request=='finance'){
            if($identity == 'ledger'){
                
                // $data['identity'] = 1;
                $data['title']  = 'Buku Besar';
                $data['_view']  = 'layouts/admin/menu/report/finance/ledger';
                $file_js        = 'layouts/admin/menu/report/finance/ledger_js.php';
                // $data['trans_type'] = 1;

            }else if($identity == 'journal'){ //Journal
                // $data['identity'] = 1;
                $data['title']  = 'Jurnal';
                $data['_view']  = 'layouts/admin/menu/report/finance/journal';
                $file_js        = 'layouts/admin/menu/report/finance/journal_js.php';
                // $data['trans_type'] = 1;
            }else if($identity == 'trial_balance'){ //Neraca Saldo
                // $data['identity'] = 1;
                $data['title']  = 'Neraca Saldo';
                $data['_view']  = 'layouts/admin/menu/report/finance/trial_balance';
                $file_js        = 'layouts/admin/menu/report/finance/trial_balance_js.php';
                // $data['trans_type'] = 1;            
            }else if($identity == 'worksheet'){ //Neraca Lajur / Kertas Kerja
                // $data['identity'] = 1;
                $data['title']  = 'Kertas Kerja / Neraca Lajur';
                $data['_view']  = 'layouts/admin/menu/report/finance/worksheet';
                $file_js        = 'layouts/admin/menu/report/finance/worksheet_js.php';
                // $data['trans_type'] = 1;                            
            }else if($identity == 'profit_loss'){ //Laba Rugi
                // $data['identity'] = 1;
                $data['title']  = 'Laba Rugi';
                $data['_view']  = 'layouts/admin/menu/report/finance/profit_loss';
                $file_js        = 'layouts/admin/menu/report/finance/profit_loss_js.php';
                // $data['trans_type'] = 1;
            }else if($identity == 'balance'){ //Neraca
                // $data['identity'] = 1;
                $data['title']  = 'Neraca';
                $data['_view']  = 'layouts/admin/menu/report/finance/balance';
                $file_js        = 'layouts/admin/menu/report/finance/balance_js.php';
                // $data['trans_type'] = 1;
            }else if($identity == 'cash_in'){ //Uang Masuk
                // $data['identity'] = 1;
                $data['title']  = 'Pemasukan Uang / Setoran';
                $data['_view']  = 'layouts/admin/menu/report/finance/cash_in';
                $file_js        = 'layouts/admin/menu/report/finance/cash_in_js.php';
                // $data['trans_type'] = 1;
            }else if($identity == 'cash_out'){ //Uang Keluar
                // $data['identity'] = 1;
                $data['title']  = 'Pengeluaran Uang / Biaya';
                $data['_view']  = 'layouts/admin/menu/report/finance/cash_out';
                $file_js        = 'layouts/admin/menu/report/finance/cash_out_js.php';
                // $data['trans_type'] = 1;
            }
        }else if($request=='other'){

        }else{

        } 
        
        //Date Now
        $datenow =date("d-m-Y"); 
        $data['first_date'] = $firstdateofmonth;
        $data['end_date'] = $datenow;

        //Alias
        $data['supplier_alias'] = $this->supplier_alias;
        $data['customer_alias'] = $this->customer_alias;
        $data['employee_alias'] = $this->employee_alias;

        $data['buy_alias']      = $this->buy_alias;
        $data['sell_alias']     = $this->sell_alias;

        $data['product_alias']  = $this->product_alias;
        $data['ref_alias']      = $this->ref_alias;        

        $this->load->view('layouts/admin/index',$data);
        $this->load->view($file_js,$data);
    }
        function report_purchase_buy_recap($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'trans_branch_id' => $session_branch_id,
                'trans_type' => 1,
                'trans_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'trans_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }

            $mdatas = array();
            $datas = $this->Transaksi_model->get_all_transaksis($params_datatable, $search, $limit, $start, $order, $dir);
            // var_dump($params_datatable,$get_contact);die;
            foreach($datas AS $v){ 
                $trans_id = $v['trans_id'];

                // $get_order = $this->Order_model->get_all_orders(array('order_trans_id' => $trans_id),null,null,null,'order_id','asc');
                $get_order_item = '';
                $order_total = 0;
                // echo json_encode($get_order);


                $fee = 0;
                // if($v['trans_paid_type']==1){
                //     $paid_text = 'Tunai';
                // }else if($v['trans_paid_type']==2){
                //     $paid_text = 'Bank';
                // }else if($v['trans_paid_type']==3){
                //     $paid_text = 'Debit';
                // }else if($v['trans_paid_type']==4){
                //     $paid_text = 'Kredit';
                // }else if($v['trans_paid_type']==5){
                //     $paid_text = $v['trans_digital_provider'];
                //     if($v['trans_digital_provider'] == 'ShopeePAY'){
                //         // $fee = $v['trans_total'] * 0.01;
                //         // $fee = $get_order_item['order_item_total'];
                //         $fee = $v['trans_fee'];
                //     }
                // }else{
                //     $paid_text = 'Error';
                // }   
                // $get_order_item = array();
                // foreach ($get_order as $o) {
                //     $order_total = $o['order_subtotal'];
                //     $get_order_item[] = $this->Order_model->get_all_order_items(array('order_item_order_id'=>$o['order_id']),null,null,null,'order_item_id','asc');
                // }

                $mdatas[] = array(
                    'trans_id' => $v['trans_id'],
                    'trans_number' => $v['trans_number'],
                    'trans_date' => date("d-M-Y, H:i", strtotime($v['trans_date'])),
                    'trans_note' => $v['trans_note'],
                    'date_due_over' => $v['date_due_over'],
                    'contact_name' => $v['contact_name'],
                    'contact_address' => $v['contact_address'],
                    'contact_phone' => $v['contact_phone_1'],
                    'contact_email' => $v['contact_email_1'],                                                
                    // 'ref_name' => $v['trans_flag'],
                    'ref_name' => '-',
                    'user_username' => $v['user_username'],
                    'trans_total' => $v['trans_total'],
                    'trans_total_paid' => $v['trans_total_paid'],                
                    // 'trans_received' => $v['trans_received'],
                    // 'trans_change' => $v['trans_change'],        
                    'trans_fee' => $fee,        
                    'order_total' => $order_total,        
                    'order_item' => $get_order_item                                
                );
            }
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;   
            $data['title']          = "Laporan ".$this->buy_alias." Rekap";
            $data['contact_alias']  = $this->supplier_alias;
            // $data['employee_alias'] = $this->employee_alias;
            // $data['ref_alias']      = $this->ref_alias;             
            $this->load->view('layouts/admin/menu/prints/reports/report_purchase_buy_recap',$data);
        }
        function report_purchase_buy_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'trans_branch_id' => $session_branch_id,
                'trans_type' => 1,
                'trans_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'trans_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }
            
            if(intval($product) > 0){
                $params_datatable['product_id'] = intval($product);
                $get_product = $this->Produk_model->get_produk(intval($product));
                $data['product'] = $get_product;
            }

            $mdatas = array();
            $mdatas = $this->Transaksi_model->get_all_transaksi_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title']              = "Laporan ".$this->buy_alias." Rinci";
            $data['contact_alias']      = $this->supplier_alias;
            // $data['employee_alias']     = $this->employee_alias;
            // $data['ref_alias']          = $this->ref_alias;  
            $data['product_alias']      = $this->product_alias;       
            $this->load->view('layouts/admin/menu/prints/reports/report_purchase_buy_detail',$data);
        }
        function report_purchase_return_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'trans_branch_id' => $session_branch_id,
                'trans_type' => 3,
                'trans_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'trans_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }
            
            if(intval($product) > 0){
                $params_datatable['product_id'] = intval($product);
                $get_product = $this->Produk_model->get_produk(intval($product));
                $data['product'] = $get_product;
            }

            $mdatas = array();
            $mdatas = $this->Transaksi_model->get_all_transaksi_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title'] = "Laporan Retur ".$this->buy_alias." Rinci";           
            $data['contact_alias']      = $this->supplier_alias;
            // $data['employee_alias']     = $this->employee_alias;
            // $data['ref_alias']          = $this->ref_alias;  
            $data['product_alias']      = $this->product_alias;            
            $this->load->view('layouts/admin/menu/prints/reports/report_purchase_return_detail',$data);
        }    
        function report_purchase_order_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'order_branch_id' => $session_branch_id,
                'order_type' => 1,
                'order_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'order_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );
            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }
            
            if(intval($product) > 0){
                $params_datatable['product_id'] = intval($product);
                $get_product = $this->Produk_model->get_produk(intval($product));
                $data['product'] = $get_product;
            }

            $mdatas = array();
            $mdatas = $this->Order_model->get_all_order_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;      
            $data['title']          = "Laporan ".$this->po_alias." Rinci";
            $data['contact_alias']      = $this->supplier_alias;
            // $data['employee_alias']     = $this->employee_alias;
            // $data['ref_alias']          = $this->ref_alias;  
            $data['product_alias']      = $this->product_alias;           
            $this->load->view('layouts/admin/menu/prints/reports/report_purchase_order_detail',$data);
        }
        function report_sales_sell_recap($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $order = $this->input->get('order');
            $dir = $this->input->get('dir');
            $type_paid = $this->input->get('type_paid');
            $sales = $this->input->get('sales');            

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'trans_branch_id' => $session_branch_id,
                'trans_type' => 2,
                'trans_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'trans_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }
            
            if(intval($sales) > 0){
                $params_datatable['trans_sales_id'] = intval($sales);
                $get_sales = $this->Kontak_model->get_kontak(intval($sales));
                $data['sales'] = $get_sales;
            }

            if(intval($type_paid) > 0){
                $params_datatable['trans_paid_type'] = intval($type_paid);
                $get_type_paid = $this->Type_model->get_type_paid(intval($type_paid));
                $data['type_paid'] = $get_type_paid;
            }

            $mdatas = array();
            $datas = $this->Transaksi_model->get_all_transaksis($params_datatable, $search, $limit, $start, $order, $dir);
            // var_dump($params_datatable,$get_contact);die;
            foreach($datas AS $v){ 
                $trans_id = $v['trans_id'];

                // $get_order = $this->Order_model->get_all_orders(array('order_trans_id' => $trans_id),null,null,null,'order_id','asc');
                $get_order_item = '';
                $order_total = 0;
                // echo json_encode($get_order);


                $fee = 0;
                // if($v['trans_paid_type']==1){
                //     $paid_text = 'Tunai';
                // }else if($v['trans_paid_type']==2){
                //     $paid_text = 'Bank';
                // }else if($v['trans_paid_type']==3){
                //     $paid_text = 'Debit';
                // }else if($v['trans_paid_type']==4){
                //     $paid_text = 'Kredit';
                // }else if($v['trans_paid_type']==5){
                //     $paid_text = $v['trans_digital_provider'];
                //     if($v['trans_digital_provider'] == 'ShopeePAY'){
                //         // $fee = $v['trans_total'] * 0.01;
                //         // $fee = $get_order_item['order_item_total'];
                //         $fee = $v['trans_fee'];
                //     }
                // }else{
                //     $paid_text = 'Error';
                // }   
                // $get_order_item = array();
                // foreach ($get_order as $o) {
                //     $order_total = $o['order_subtotal'];
                //     $get_order_item[] = $this->Order_model->get_all_order_items(array('order_item_order_id'=>$o['order_id']),null,null,null,'order_item_id','asc');
                // }

                $mdatas[] = array(
                    'trans_id' => $v['trans_id'],
                    'trans_number' => $v['trans_number'],
                    'trans_date' => date("d-M-Y, H:i", strtotime($v['trans_date'])),
                    'trans_note' => $v['trans_note'],
                    'trans_sales_name' => $v['trans_sales_name'],
                    'date_due_over' => $v['date_due_over'],
                    'contact_name' => $v['contact_name'],
                    'contact_address' => $v['contact_address'],
                    'contact_phone' => $v['contact_phone_1'],
                    'contact_email' => $v['contact_email_1'],                
                    // 'ref_name' => $v['trans_flag'],
                    'ref_name' => '-',
                    'user_username' => $v['user_username'],
                        'trans_total_dpp' => $v['trans_total_dpp'],
                        'trans_total_ppn' => $v['trans_total_ppn'],
                        'trans_discount' => $v['trans_discount'],
                        'trans_voucher' => $v['trans_voucher'],
                    'trans_total' => $v['trans_total'],
                    'trans_total_paid' => $v['trans_total_paid'],                
                    // 'trans_received' => $v['trans_received'],
                    // 'trans_change' => $v['trans_change'],        
                    'trans_fee' => $fee,        
                    'order_total' => $order_total,        
                    'order_item' => $get_order_item                                
                );
            }
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title']          = "Laporan ".$this->sell_alias." Rekap";
            $data['contact_alias']  = $this->customer_alias;
            // $data['employee_alias'] = $this->employee_alias;
            // $data['ref_alias']      = $this->ref_alias;     

            $this->load->view('layouts/admin/menu/prints/reports/report_sales_sell_recap',$data);
        }
        function report_sales_sell_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $branch = $this->input->get('branch');
            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');
            $sales = $this->input->get('sales');   
            
            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'trans_type' => 222,
                'trans_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'trans_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($branch) > 0){
                $params_datatable['trans_branch_id'] = intval($branch);
                $get_branch = $this->Branch_model->get_branch(intval($branch));
                $data['branchs'] = $get_branch;
            }
            // if(intval($contact) > 0){
            //     $params_datatable['contact_id'] = intval($contact);
            //     $get_contact = $this->Kontak_model->get_kontak(intval($contact));
            //     $data['contact'] = $get_contact;
            // }
            
            // if(intval($sales) > 0){
            //     $params_datatable['trans_sales_id'] = intval($sales);
            //     $get_sales = $this->Kontak_model->get_kontak(intval($sales));
            //     $data['sales'] = $get_sales;
            // }

            // if(intval($product) > 0){
            //     $params_datatable['product_id'] = intval($product);
            //     $get_product = $this->Produk_model->get_produk(intval($product));
            //     $data['product'] = $get_product;
            // }

            $mdatas = array();
            $mdatas = $this->Transaksi_model->get_all_transaksi_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;     
            // var_dump($data['branch']);die;   
            $data['title']          = "Laporan Warmindo";
            $data['contact_alias']      = $this->customer_alias;
            // $data['employee_alias']     = $this->employee_alias;
            // $data['ref_alias']          = $this->ref_alias;  
            $data['product_alias']      = $this->product_alias;         
            
            $this->load->view('layouts/admin/menu/prints/reports/report_sales_sell_detail',$data);
        }
        function report_sales_order_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];
            $branch = $this->input->get('branch');

            $act = $this->input->get('act');
            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');
            $ouser = $this->input->get('user');            

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                // 'order_branch_id' => $session_branch_id,
                'order_type' => 222,
                'order_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'order_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            // if(intval($contact) > 0){
            //     $params_datatable['contact_id'] = intval($contact);
            //     $get_contact = $this->Kontak_model->get_kontak(intval($contact));
            //     $data['contact'] = $get_contact;
            // }
            
            // if(intval($product) > 0){
            //     $params_datatable['product_id'] = intval($product);
            //     $get_product = $this->Produk_model->get_produk(intval($product));
            //     $data['product'] = $get_product;
            // }
            
            if(intval($branch) > 0){
                $params_datatable['order_branch_id'] = intval($branch);
                $get_branch = $this->Branch_model->get_branch(intval($branch));
                $data['branchs'] = $get_branch;
            }

            if(intval($ouser) > 0){
                $params_datatable['order_user_id'] = intval($ouser);
                $get_user = $this->User_model->get_user(intval($ouser));
                $data['user'] = $get_user;
            }

            $mdatas = array();
            $mdatas = $this->Front_model->get_all_booking_item($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas; 
            $data['title']          = "Laporan Penjualan Kamar";
            $data['contact_alias']      = $this->customer_alias;
            // $data['employee_alias']     = $this->employee_alias;
            // $data['ref_alias']          = $this->ref_alias;  
            $data['product_alias']      = $this->product_alias;            

            $view_url = 'layouts/admin/menu/prints/reports/report_sales_order_detail';
            if($act == 11){
                $view_url = 'layouts/admin/menu/prints/reports/report_sales_order_detail_complete';
                $data['title'] .= ' Complete';
            } 
            // var_dump($view_url);die;
            $this->load->view($view_url,$data);            
        }
        function report_sales_prepare_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'order_branch_id' => $session_branch_id,
                'order_type' => 7,
                'order_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'order_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }
            
            if(intval($product) > 0){
                $params_datatable['product_id'] = intval($product);
                $get_product = $this->Produk_model->get_produk(intval($product));
                $data['product'] = $get_product;
            }

            $mdatas = array();
            $mdatas = $this->Order_model->get_all_order_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title'] = "Laporan Prepare Rinci";  
            $data['product_alias'] = $this->product_alias;
            $data['contact_alias'] = $this->customer_alias;
            $this->load->view('layouts/admin/menu/prints/reports/report_sales_order_prepare_detail',$data);
        }
        function report_sales_return_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'trans_branch_id' => $session_branch_id,
                'trans_type' => 4,
                'trans_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'trans_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }
            
            if(intval($product) > 0){
                $params_datatable['product_id'] = intval($product);
                $get_product = $this->Produk_model->get_produk(intval($product));
                $data['product'] = $get_product;
            }

            $mdatas = array();
            $mdatas = $this->Transaksi_model->get_all_transaksi_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title']          = "Laporan Retur ".$this->sell_alias." Rinci";
            $data['contact_alias']      = $this->customer_alias;
            // $data['employee_alias']     = $this->employee_alias;
            // $data['ref_alias']          = $this->ref_alias;  
            $data['product_alias']      = $this->product_alias;               
            $this->load->view('layouts/admin/menu/prints/reports/report_sales_return_detail',$data);
        }
        function report_point_of_sales_recap($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'order_branch_id' => $session_branch_id,
                'order_type' => 222,
                'order_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'order_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }

            $mdatas = array();
            $datas = $this->Order_model->get_all_orders($params_datatable, $search, $limit, $start, $order, $dir);
            // var_dump($datas);die;
            foreach($datas AS $v){ 
                $order_id = $v['order_id'];

                $get_order = $this->Order_model->get_all_order_items_report(array('order_item_order_id' => $order_id),null,null,null,'order_id','asc');
                $get_order_item = '';
                $order_total = 0;
                $mdatas[] = array(
                    'order_id' => $v['order_id'],
                    'order_type' => $v['order_type'],            
                    'order_number' => $v['order_number'],
                    'order_date' => date("d-M-Y, H:i", strtotime($v['order_date'])),
                    'order_note' => $v['order_note'],
                    'contact_name' => $v['contact_name'],
                    'contact_address' => $v['contact_address'],
                    'contact_phone' => $v['contact_phone_1'],            
                    'employee_code' => $v['employee_code'], //Karyawan
                    'employee_name' => $v['employee_name'],
                    'ref_name' => $v['ref_name'], //Room
                    'ref_id' => $v['ref_id'],
                    'user_username' => $v['user_username'],
                    'order_total_dpp' => $v['order_total_dpp'],
                    'order_total' => $v['order_total'],                
                    // 'trans_received' => $v['trans_received'],
                    // 'trans_change' => $v['trans_change'],        
                    // 'trans_fee' => $fee,        
                    // 'order_total' => $order_total,        
                    // 'order_item' => $get_order_item                                
                );
            }
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title']          = "Laporan ".$this->pos_alias." Rekap";
            $data['contact_alias']  = $this->customer_alias;
            $data['employee_alias'] = $this->employee_alias;
            $data['ref_alias']      = $this->ref_alias;        
            $this->load->view('layouts/admin/menu/prints/reports/report_pos_recap',$data);
        }
        function report_point_of_sales_detail($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'order_branch_id' => $session_branch_id,
                'order_type' => 222,
                'order_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'order_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );
            if(intval($contact) > 0){
                $params_datatable['contact_id'] = intval($contact);
                $get_contact = $this->Kontak_model->get_kontak(intval($contact));
                $data['contact'] = $get_contact;
            }
            
            if(intval($product) > 0){
                $params_datatable['product_id'] = intval($product);
                $get_product = $this->Produk_model->get_produk(intval($product));
                $data['product'] = $get_product;
            }

            $mdatas = array();
            $mdatas = $this->Order_model->get_all_order_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title']              = "Laporan ".$this->pos_alias." Rinci";
            $data['contact_alias']      = $this->customer_alias;
            $data['employee_alias']     = $this->employee_alias;
            $data['ref_alias']          = $this->ref_alias;  
            $data['product_alias']      = $this->product_alias;         
            $this->load->view('layouts/admin/menu/prints/reports/report_pos_detail',$data);
        }
        function report_production_product_detail($date_start,$date_end,$location){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = $this->input->get('product');
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $limit  = null;
            $start  = null;
            $order  = $order;
            $dir    = $dir;
            $search = null;

            $params_datatable = array(
                'trans_branch_id' => $session_branch_id,
                'trans_type' => 8,
                'trans_item_position' => 1,
                'trans_date >' => date("Y-m-d", strtotime($date_start)).' 00:00:00',
                'trans_date <' => date("Y-m-d", strtotime($date_end)).' 23:59:59'
            );

            if(intval($location) > 0){
                $params_datatable['trans_item_location_id'] = intval($location);
                $get_location = $this->Lokasi_model->get_lokasi(intval($location));
                $data['location'] = $get_location;
            }
            
            if(intval($product) > 0){
                $params_datatable['product_id'] = intval($product);
                $get_product = $this->Produk_model->get_produk(intval($product));
                $data['product'] = $get_product;
            }

            $mdatas = array();
            $mdatas = $this->Transaksi_model->get_all_transaksi_items_report($params_datatable, $search, $limit, $start, $order, $dir);
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y, H:i", strtotime($date_start.' 00:00:00')).' sd '.date("d-M-Y, H:i", strtotime($date_end.' 23:59:59')); 
            $data['content'] = $mdatas;
            $data['title']              = "Laporan Produksi ".$this->product_alias." Jadi Rinci";
            $data['product_alias']      = $this->product_alias;            
            $this->load->view('layouts/admin/menu/prints/reports/report_production_product_detail',$data);
        }
        function report_stock_warehouse($date_start,$date_end,$location){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $columns = array(
                '0' => 'product_code',
                '1' => 'product_name'
            ); 

            $product = null;
            $categories = null;        
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');
            $category = $this->input->get('category');        
            $search = null;
            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
            $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

            $location_id = (intval($location) > 0) ? intval($location) : 0;
            $product_id = (intval($this->input->get('product')) > 0) ? $this->input->get('product') : 0;

            if($location_id > 0){
                $location = $this->Lokasi_model->get_lokasi($location_id);
            }
            if($product_id > 0){
                $product = $this->Produk_model->get_produk($product_id);
            }      
            if($category > 0){        
                $categories = $this->Kategori_model->get_categories($category);
            }          
            $mdatas = array();
            $mdatas = $this->report_product_stock(1,$date_start,$date_end,$session_branch_id,$location_id,$product_id,$order,$dir,$search,$category);
                    
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
            // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
            $data['content'] = $mdatas;
            $data['location'] = $location;
            $data['product'] = $product;     
            $data['category'] = $categories;           
            $data['title'] = "Laporan Stok";          
            $data['product_alias']      = $this->product_alias;          
            $this->load->view('layouts/admin/menu/prints/reports/report_stock_warehouse',$data);
        }
        function report_stock_moving($date_start,$date_end,$location){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = null;
            $categories = null;        
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');
            $category = $this->input->get('category');        
            $search = null;
            
            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
            $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

            $location_id = (intval($location) > 0) ? intval($location) : 0;
            $product_id = (intval($this->input->get('product')) > 0) ? $this->input->get('product') : 0;

            if($location_id > 0){
                $location = $this->Lokasi_model->get_lokasi($location_id);
            }
            
            if($product_id > 0){
                $product = $this->Produk_model->get_produk($product_id);
            }        

            if($category > 0){        
                $categories = $this->Kategori_model->get_categories($category);
            }

            $mdatas = array();
            $mdatas = $this->report_product_stock(2,$date_start,$date_end,$session_branch_id,$location_id,$product_id,$order,$dir,$search,$category,$search);
                    
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
            // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
            $data['content'] = $mdatas;
            $data['location'] = $location;
            $data['product'] = $product;       
            $data['category'] = $categories;          
            $data['title'] = "Laporan Pergerakan Stok";   
            $data['product_alias']      = $this->product_alias;               
            $this->load->view('layouts/admin/menu/prints/reports/report_stock_moving',$data);
        }
        function report_stock_valuation($date_start,$date_end,$location){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];
        
            $columns = array(
                '0' => 'product_code',
                '1' => 'product_name',
                '2' => 'category_name',
                '3' => 'qty_balance',
                '5' => 'qty_in_price',
                '6' => 'qty_in_price_total'
            ); 

            $product = null;
            $categories = null;        
            $order = $columns[$this->input->get('order')];
            $dir = $this->input->get('dir');
            $category = $this->input->get('category');
            $location = $this->input->get('location');                
            $search = null;
            // var_dump($order);die;
            
            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
            $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

            $location_id = (intval($location) > 0) ? intval($location) : 0;
            $product_id = (intval($this->input->get('product')) > 0) ? $this->input->get('product') : 0;

            if($location_id > 0){
                $location = $this->Lokasi_model->get_lokasi($location_id);
            }
            if($product_id > 0){
                $product = $this->Produk_model->get_produk($product_id);
            }   
            if($category > 0){        
                $categories = $this->Kategori_model->get_categories($category);
            }
            $mdatas = array();
            $mdatas = $this->report_product_stock(5,$date_start,$date_end,$session_branch_id,$location_id,$product_id,$order,$dir,$search,$category);
                    
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
            // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
            $data['content'] = $mdatas;
            $data['location'] = $location;
            $data['product'] = $product;
            $data['category'] = $categories;                
            $data['title'] = "Laporan Nilai Stok Gudang";  
            $data['product_alias']      = $this->product_alias;                      
            $this->load->view('layouts/admin/menu/prints/reports/report_stock_valuation',$data);
        }
        function report_stock_card($date_start,$date_end,$location){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $product = null;
            $order = $this->input->get('order');
            $dir = $this->input->get('dir');
            $ver = $this->input->get('ver');                 
            $search = null;

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
            $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

            $location_id = (intval($location) > 0) ? intval($location) : 0;
            $product_id = (intval($this->input->get('product')) > 0) ? $this->input->get('product') : 0;

            if($location_id > 0){
                $location = $this->Lokasi_model->get_lokasi($location_id);
            }
            if($product_id > 0){
                $product = $this->Produk_model->get_produk($product_id);
            }        
            $mdatas = array();
            $mdatas = $this->report_product_stock(3,$date_start,$date_end,$session_branch_id,$location_id,$product_id,$order,$dir,$search);
                    
            // echo json_encode($mdatas);die;
            $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
            // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
            $data['content'] = $mdatas;
            $data['location'] = $location;
            $data['product'] = $product;   
            $data['version'] = $ver;     
            $data['title'] = "Kartu Stok : ".$product['product_name'];    
            $data['product_alias']      = $this->product_alias;             
            $this->load->view('layouts/admin/menu/prints/reports/report_stock_card',$data);
        }
        function report_purchase_buy_account_payable($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            // $limit = $this->input->post('length');
            // $start = $this->input->post('start');
            // $order = $columns[$this->input->post('order')[0]['column']];
            // $dir = $this->input->post('order')[0]['dir'];

            $search = [];
            // if ($this->input->post('search')['value']) {
            //     $s = $this->input->post('search')['value'];
            //     foreach ($columns as $v) {
            //         $search[$v] = $s;
            //     }
            // }
            $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
            $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

            $contact_id = $contact;
            if($contact > 0){
                $contact = $this->Kontak_model->get_kontak($contact);
            }

            $total_data = 0;
            $mdatas = array();
            $get_datas = $this->report_finance(4,$date_start,$date_end,$session_branch_id,$contact_id,$search);
            foreach($get_datas as $k => $v):
                if(intval($v['total_data']) > 0){
                    $mdatas[] = array(                                 
                        'temp_id' => $v['temp_id'],
                        'type_name' => $v['type_name'],
                        'trans_date' => $v['trans_date'],
                        'trans_date_due' => $v['trans_date_due'],
                        'trans_date_due_over' => $v['trans_date_due_over'],                    
                        'trans_id' => $v['trans_id'],
                        'trans_note' => $v['trans_note'],
                        'trans_number' => $v['trans_number'],
                        'trans_total' => $v['trans_total'],
                        'trans_total_paid' => $v['trans_total_paid'],
                        'contact_id' => $v['contact_id'],
                        'contact_code' => $v['contact_code'],
                        'contact_name' => $v['contact_name'],
                        'balance' => $v['balance'],
                        'trans_date_format' => date("d/m/Y", strtotime($v['trans_date'])),
                        'trans_date_due_format' => date("d/m/Y", strtotime($v['trans_date_due'])),                                
                        'status' => $v['status'],
                        'message' => $v['message'],
                        'total_data' => $v['total_data']
                    );
                }
                $total_data = $v['total_data'];
            endforeach;
            if(intval($total_data) > 0){
                $total=$total_data;
                $return->status=1; 
                $return->message='Loaded'; 
                $return->total_records=$total;
                $return->result=$mdatas;        
            }else{ 
                $data_source=0; $total=0; 
                $return->status=0; $return->message='No data'; $return->total_records=$total;
                $return->result=0;    
            }
            $return->recordsTotal = $total;
            $return->recordsFiltered = $total;
            $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
            // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
            $data['content'] = $mdatas;
            $data['contact'] = $contact;
            $data['title'] = "Laporan Hutang ".$this->supplier_alias;        
            $data['contact_alias'] = $this->supplier_alias;            
            $this->load->view('layouts/admin/menu/prints/reports/report_purchase_buy_account_payable',$data);             
        }
        function report_sales_sell_account_receivable($date_start,$date_end,$contact){
            $session = $this->session->userdata(); 
            $session_branch_id = $session['user_data']['branch']['id'];
            $session_user_id = $session['user_data']['user_id'];

            $data['branch'] = $this->Branch_model->get_branch(1);
            if($data['branch']['branch_logo'] == null){
                $get_branch = $this->Branch_model->get_branch(1);
                $data['branch_logo'] = site_url().$get_branch['branch_logo'];
            }else{
                $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
            }

            $return = new \stdClass();
            $return->status = 0;
            $return->message = '';
            $return->result = '';

            // $limit = $this->input->post('length');
            // $start = $this->input->post('start');
            // $order = $columns[$this->input->post('order')[0]['column']];
            // $dir = $this->input->post('order')[0]['dir'];

            $search = [];
            // if ($this->input->post('search')['value']) {
            //     $s = $this->input->post('search')['value'];
            //     foreach ($columns as $v) {
            //         $search[$v] = $s;
            //     }
            // }
            $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
            $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

            $contact_id = $contact;
            if($contact > 0){
                $contact = $this->Kontak_model->get_kontak($contact);
            }

            $total_data = 0;
            $mdatas = array();
            $get_datas = $this->report_finance(5,$date_start,$date_end,$session_branch_id,$contact_id,$search);
            foreach($get_datas as $k => $v):
                if(intval($v['total_data']) > 0){
                    $mdatas[] = array(                                 
                        'temp_id' => $v['temp_id'],
                        'type_name' => $v['type_name'],
                        'trans_date' => $v['trans_date'],
                        'trans_date_due' => $v['trans_date_due'],
                        'trans_date_due_over' => $v['trans_date_due_over'],                    
                        'trans_id' => $v['trans_id'],
                        'trans_note' => $v['trans_note'],
                        'trans_number' => $v['trans_number'],
                        'trans_total' => $v['trans_total'],
                        'trans_total_paid' => $v['trans_total_paid'],
                        'contact_id' => $v['contact_id'],
                        'contact_code' => $v['contact_code'],
                        'contact_name' => $v['contact_name'],
                        'balance' => $v['balance'],
                        'trans_date_format' => date("d/m/Y", strtotime($v['trans_date'])),
                        'trans_date_due_format' => date("d/m/Y", strtotime($v['trans_date_due'])),                                
                        'status' => $v['status'],
                        'message' => $v['message'],
                        'total_data' => $v['total_data']
                    );
                }
                $total_data = $v['total_data'];
            endforeach;
            if(intval($total_data) > 0){
                $total=$total_data;
                $return->status=1; 
                $return->message='Loaded'; 
                $return->total_records=$total;
                $return->result=$mdatas;        
            }else{ 
                $data_source=0; $total=0; 
                $return->status=0; $return->message='No data'; $return->total_records=$total;
                $return->result=0;    
            }
            $return->recordsTotal = $total;
            $return->recordsFiltered = $total;
            $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
            // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
            $data['content'] = $mdatas;
            $data['contact'] = $contact;
            $data['title'] = "Laporan Piutang ".$this->customer_alias;    
            $data['contact_alias'] = $this->customer_alias;    
            $this->load->view('layouts/admin/menu/prints/reports/report_sales_sell_account_receivable',$data);             
        }
    function report_finance_journal($date_start,$date_end,$account){
        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $columns = array(
            // '0' => 'journal_item_date',
            // '1' => 'journal_item_account_id',
            // '2' => 'journal_item_debit',
            // '3' => 'journal_item_credit'
        // );

        // $limit = $this->input->post('length');
        // $start = $this->input->post('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        $search = [];
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }
        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;
        
        $total_data = 0;
        $mdatas = array();
        // if($account_id > 0){
            // $account = $this->Account_model->get_account($account_id);
            $get_datas = $this->report_finance(1,$date_start,$date_end,$session_branch_id,$account_id,$search);
            // var_dump($get_datas);
            foreach($get_datas as $k => $v):
                if(intval($v['total_data']) > 0){
                    
                    if(strlen($v['journal_number']) > 1){
                        $url = $this->journal_url.$v['journal_session'];
                    }else if(strlen($v['trans_number']) > 1){
                        $url = $this->trans_url.$v['trans_session'];
                    }else{
                        $url = '#';
                    }
                    
                    $mdatas[] = array(
                        'journal_group_session' => $v['journal_item_group_session'],
                        'type_name' => $v['type_name'],                                    
                        'temp_id' => $v['temp_id'],
                        'journal_item_id' => $v['journal_item_id'],
                        'journal_item_note' => $v['journal_item_note'],
                        'journal_number' => $v['journal_number'],                       
                        'trans_number' => $v['trans_number'],
                        'account_id' => $v['account_id'],
                        'account_code' => $v['account_code'],
                        'account_name' => $v['account_name'],
                        'debit' => $v['debit'],
                        'credit' => $v['credit'],
                        'balance' => $v['balance'],
                        'journal_item_date_format' => $v['journal_item_date_format'],
                        'status' => $v['status'],
                        'message' => $v['message'],
                        'total_data' => $v['total_data'],
                        'journal_session' => $v['journal_session'],
                        'trans_session' => $v['trans_session'],
                        'journal_id' => $v['journal_id'],
                        'trans_id' => $v['trans_id'],
                        'order_id' => $v['order_id'],
                        'journal_text' => $v['journal_text'],
                        'contact_name' => $v['contact_name'],
                        'url' => $url
                    );
                }
                $total_data = $v['total_data'];
            endforeach;
        // }
        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Jurnal Umum";        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_journal',$data);         
    }
    function report_finance_ledger($date_start,$date_end,$account){
        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $columns = array(
            // '0' => 'journal_item_date',
            // '1' => 'journal_item_account_id',
            // '2' => 'journal_item_debit',
            // '3' => 'journal_item_credit'
        // );

        // $limit = $this->input->post('length');
        // $start = $this->input->post('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        $search = [];
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }
        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;
        
        $total_data = 0;
        $mdatas = array();
        $account_name = '';
        if($account_id > 0){
            $account = $this->Account_model->get_account($account_id);
            $get_datas = $this->report_finance(2,$date_start,$date_end,$session_branch_id,$account_id,$search);
            foreach($get_datas as $k => $v):
                if(intval($v['total_data']) > 0){
                    if(strlen($v['journal_number']) > 1){
                        $url = $this->journal_url.$v['journal_session'];
                    }else if(strlen($v['trans_number']) > 1){
                        $url = $this->trans_url.$v['trans_session'];
                    }else{
                        $url = '#';
                    }                    
                    $mdatas[] = array(
                        'type_name' => $v['type_name'],                                    
                        'temp_id' => $v['temp_id'],
                        'journal_item_id' => $v['journal_item_id'],
                        'journal_item_note' => $v['journal_item_note'],
                        'journal_number' => $v['journal_number'],                       
                        'trans_number' => $v['trans_number'],
                        'account_id' => $v['account_id'],
                        'account_code' => $v['account_code'],
                        'account_name' => $v['account_name'],
                        'debit' => $v['debit'],
                        'credit' => $v['credit'],
                        'balance' => $v['balance'],
                        'journal_item_date_format' => $v['journal_item_date_format'],
                        'status' => $v['status'],
                        'message' => $v['message'],
                        'total_data' => $v['total_data'],
                        'journal_session' => $v['journal_session'],
                        'trans_session' => $v['trans_session'],
                        'journal_id' => $v['journal_id'],
                        'trans_id' => $v['trans_id'],
                        'order_id' => $v['order_id'],
                        'contact_name' => $v['contact_name'],
                        'url' => $url                         
                    );
                }
                $total_data = $v['total_data'];
            endforeach;
            $account_name = $account['account_name'].' ['.$account['account_code'].']';
        }
        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Buku Besar: ".$account_name;        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_ledger',$data);         
    }
    function report_finance_trial_balance($date_start,$date_end,$account){
        $data['print_ledger_url']  = site_url('report/report_finance_ledger');
        $data['print_date_period'] = $date_start.'/'.$date_end;
        $data['print_parameter']   = '?format=html&order=journal_item_date&dir=asc';

        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $limit = $this->input->post('length');
        // $start = $this->input->post('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        $search = [];
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }

        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;

        $total_data = 0;
        $mdatas = array();
        $get_datas = $this->report_finance(3,$date_start,$date_end,$session_branch_id,$account_id,$search);
        // log_message('debug',$get_datas);die;
        foreach($get_datas as $k => $v):
            if(intval($v['total_data']) > 0){
                // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                $mdatas[] = array(
                    'temp_id' => $v['temp_id'],
                    'parent_id' => $v['parent_id'],
                    'account_group' => $v['account_group'],
                    'group_sub' => $v['group_sub'],                                
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],
                    'start_debit' => $v['start_debit'],
                    'start_credit' => $v['start_credit'],
                    'movement_debit' => $v['movement_debit'],
                    'movement_credit' => $v['movement_credit'],
                    'end_debit' => $v['end_debit'],
                    'end_credit' => $v['end_credit'], 
                    'profit_loss_debit' => $v['profit_loss_debit'],
                    'profit_loss_credit' => $v['profit_loss_credit'],
                    'balance_debit' => $v['balance_debit'],
                    'balance_credit' => $v['balance_credit'],                          
                    // 'journal_item_date_format' => $v['journal_item_date_format'],
                    'status' => $v['status'],
                    'message' => $v['message'],
                    'total_data' => $v['total_data']
                );
            }
            $total_data = $v['total_data'];
        endforeach;

        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Neraca Saldo";        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_trial_balance',$data);                    
    }
    function report_finance_worksheet($date_start,$date_end,$account){
        $data['print_ledger_url']  = site_url('report/report_finance_ledger');
        $data['print_date_period'] = $date_start.'/'.$date_end;
        $data['print_parameter']   = '?format=html&order=journal_item_date&dir=asc';

        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $limit = $this->input->post('length');
        // $start = $this->input->post('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        $search = [];
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }

        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;

        $total_data = 0;
        $mdatas = array();
        $get_datas = $this->report_finance(3,$date_start,$date_end,$session_branch_id,$account_id,$search);
        // log_message('debug',$get_datas);die;
        foreach($get_datas as $k => $v):
            if(intval($v['total_data']) > 0){
                // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                $mdatas[] = array(
                    'temp_id' => $v['temp_id'],
                    'parent_id' => $v['parent_id'],
                    'account_group' => $v['account_group'],
                    'group_sub' => $v['group_sub'],                                
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],
                    'start_debit' => $v['start_debit'],
                    'start_credit' => $v['start_credit'],
                    'movement_debit' => $v['movement_debit'],
                    'movement_credit' => $v['movement_credit'],
                    'end_debit' => $v['end_debit'],
                    'end_credit' => $v['end_credit'], 
                    'profit_loss_debit' => $v['profit_loss_debit'],
                    'profit_loss_credit' => $v['profit_loss_credit'],
                    'balance_debit' => $v['balance_debit'],
                    'balance_credit' => $v['balance_credit'],                          
                    // 'journal_item_date_format' => $v['journal_item_date_format'],
                    'status' => $v['status'],
                    'message' => $v['message'],
                    'total_data' => $v['total_data']
                );
            }
            $total_data = $v['total_data'];
        endforeach;

        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Kertas Kerja / Neraca Lajur";        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_worksheet',$data);                    
    }
    function report_finance_profit_loss($date_start,$date_end,$account){
        $data['print_ledger_url']  = site_url('report/report_finance_ledger');
        $data['print_date_period'] = $date_start.'/'.$date_end;
        $data['print_parameter']   = '?format=html&order=journal_item_date&dir=asc';

        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $limit = $this->input->post('length');
        // $start = $this->input->post('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        $search = [];
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }

        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;

        $total_data = 0;
        $mdatas = array();
        $get_datas = $this->report_finance(3,$date_start,$date_end,$session_branch_id,$account_id,$search);
        // log_message('debug',$get_datas);die;
        foreach($get_datas as $k => $v):
            if(intval($v['total_data']) > 0){
                // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                $mdatas[] = array(
                    'temp_id' => $v['temp_id'],
                    'parent_id' => $v['parent_id'],
                    'account_group' => $v['account_group'],
                    'group_sub_id' => $v['group_sub_id'],
                    'group_sub' => $v['group_sub'],                                
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],
                    'start_debit' => $v['start_debit'],
                    'start_credit' => $v['start_credit'],
                    'movement_debit' => $v['movement_debit'],
                    'movement_credit' => $v['movement_credit'],
                    'end_debit' => $v['end_debit'],
                    'end_credit' => $v['end_credit'], 
                    'profit_loss_debit' => $v['profit_loss_debit'],
                    'profit_loss_credit' => $v['profit_loss_credit'],
                    'profit_loss_end' => $v['profit_loss_end'],                    
                    'balance_debit' => $v['balance_debit'],
                    'balance_credit' => $v['balance_credit'],
                    'balance_end' => $v['balance_end'],                                              
                    // 'journal_item_date_format' => $v['journal_item_date_format'],
                    'status' => $v['status'],
                    'message' => $v['message'],
                    'total_data' => $v['total_data']
                );
            }
            $total_data = $v['total_data'];
        endforeach;

        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Laba Rugi";        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_profit_loss',$data);                    
    }
    function report_finance_balance($date_start,$date_end,$account){

        $data['print_ledger_url']  = site_url('report/report_finance_ledger');
        $data['print_date_period'] = $date_start.'/'.$date_end;
        $data['print_parameter']   = '?format=html&order=journal_item_date&dir=asc';

        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $limit = $this->input->post('length');
        // $start = $this->input->post('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        $search = [];
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }

        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;

        $total_data = 0;
        $mdatas = array();
        $get_datas = $this->report_finance(3,$date_start,$date_end,$session_branch_id,$account_id,$search);
        // log_message('debug',$get_datas);die;
        foreach($get_datas as $k => $v):
            if(intval($v['total_data']) > 0){
                // $mdatas['"'.$v['journal_item_group_session'].'"'] = array(
                $mdatas[] = array(
                    'temp_id' => $v['temp_id'],
                    'parent_id' => $v['parent_id'],
                    'account_group' => $v['account_group'],
                    'group_sub' => $v['group_sub'],            
                    'group_sub_id' => $v['group_sub_id'],                                        
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],
                    'start_debit' => $v['start_debit'],
                    'start_credit' => $v['start_credit'],
                    'movement_debit' => $v['movement_debit'],
                    'movement_credit' => $v['movement_credit'],
                    'end_debit' => $v['end_debit'],
                    'end_credit' => $v['end_credit'], 
                    'profit_loss_debit' => $v['profit_loss_debit'],
                    'profit_loss_credit' => $v['profit_loss_credit'],
                    'profit_loss_end' => $v['profit_loss_end'],                    
                    'balance_debit' => $v['balance_debit'],
                    'balance_credit' => $v['balance_credit'],
                    'balance_end' => $v['balance_end'],                            
                    // 'journal_item_date_format' => $v['journal_item_date_format'],
                    'status' => $v['status'],
                    'message' => $v['message'],
                    'total_data' => $v['total_data']
                );
            }
            $total_data = $v['total_data'];
        endforeach;

        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));         
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Neraca";        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_balance',$data);                    
    }
    function report_finance_cash_in($date_start,$date_end,$account){
        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $columns = array(
            // '0' => 'journal_item_date',
            // '1' => 'journal_item_account_id',
            // '2' => 'journal_item_debit',
            // '3' => 'journal_item_credit'
        // );

        $limit = $this->input->get('length');
        $start = $this->input->get('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        // $search = [];
        $search = !empty($this->input->post('filter_type')) ? $this->input->post('filter_type') : 0; 
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }
        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;
        
        $total_data = 0;
        $mdatas = array();
        // $account = $this->Account_model->get_account($account_id);
        $get_datas = $this->report_cashflow(6,$date_start,$date_end,$session_branch_id,$account_id,$search,$start,$limit);
        // var_dump($get_datas);
        foreach($get_datas as $k => $v):
            // if(intval($v['total_data']) > 0){
                if(strlen($v['journal_number']) > 1){
                    $url = $this->journal_url.$v['journal_session'];
                }else if(strlen($v['trans_number']) > 1){
                    $url = $this->trans_url.$v['trans_session'];
                }else{
                    $url = '#';
                }                    
                $mdatas[] = array(
                    'type_name' => $v['type_name'],                                    
                    'temp_id' => $v['temp_id'],
                    'journal_item_id' => $v['journal_item_id'],
                    'journal_item_note' => $v['journal_item_note'],
                    'journal_number' => $v['journal_number'],                       
                    'trans_number' => $v['trans_number'],
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],
                    'debit' => $v['debit'],
                    'credit' => $v['credit'],
                    'balance' => $v['balance'],
                    'journal_item_date_format' => $v['journal_item_date_format'],
                    'status' => $v['status'],
                    'message' => $v['message'],
                    'total_data' => $v['total_data'],
                    'journal_session' => $v['journal_session'],
                    'trans_session' => $v['trans_session'],
                    'journal_id' => $v['journal_id'],
                    'trans_id' => $v['trans_id'],
                    'order_id' => $v['order_id'],
                    'contact_name' => $v['contact_name'],
                    'url' => $url                         
                );
            // }
            $total_data = $v['total_data'];
        endforeach;
        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }        
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));   
        $data['result']  = array(
            'start' => $start,
            'limit' => $limit,
            'total' => $total
        );                 
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Pemasukan Uang / Setoran";        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_cash_in',$data);         
    }
    function report_finance_cash_out($date_start,$date_end,$account){
        $session = $this->session->userdata(); 
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $data['branch'] = $this->Branch_model->get_branch(1);
        if($data['branch']['branch_logo'] == null){
            $get_branch = $this->Branch_model->get_branch(1);
            $data['branch_logo'] = site_url().$get_branch['branch_logo'];
        }else{
            $data['branch_logo'] = site_url().$data['branch']['branch_logo'];
        }

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        // $columns = array(
            // '0' => 'journal_item_date',
            // '1' => 'journal_item_account_id',
            // '2' => 'journal_item_debit',
            // '3' => 'journal_item_credit'
        // );

        $limit = $this->input->get('length');
        $start = $this->input->get('start');
        // $order = $columns[$this->input->post('order')[0]['column']];
        // $dir = $this->input->post('order')[0]['dir'];

        // $search = [];
        $search = !empty($this->input->post('filter_type')) ? $this->input->post('filter_type') : 0;        
        // if ($this->input->post('search')['value']) {
        //     $s = $this->input->post('search')['value'];
        //     foreach ($columns as $v) {
        //         $search[$v] = $s;
        //     }
        // }
        $date_start = date('Y-m-d', strtotime($date_start.' 00:00:00'));
        $date_end = date('Y-m-d', strtotime($date_end.' 23:59:59'));

        $account_id = !empty($account) ? $account : 0;
        
        $total_data = 0;
        $mdatas = array();
        // $account = $this->Account_model->get_account($account_id);
        // $get_datas = $this->report_finance(7,$date_start,$date_end,$session_branch_id,$account_id,$search);
        $get_datas = $this->report_cashflow(7,$date_start,$date_end,$session_branch_id,$account_id,$search,$start,$limit);
        // var_dump($get_datas);
        foreach($get_datas as $k => $v):
            // if(intval($v['total_data']) > 0){
                if(strlen($v['journal_number']) > 1){
                    $url = $this->journal_url.$v['journal_session'];
                }else if(strlen($v['trans_number']) > 1){
                    $url = $this->trans_url.$v['trans_session'];
                }else{
                    $url = '#';
                }                    
                $mdatas[] = array(
                    'type_name' => $v['type_name'],                                    
                    'temp_id' => $v['temp_id'],
                    'journal_item_id' => $v['journal_item_id'],
                    'journal_item_note' => $v['journal_item_note'],
                    'journal_number' => $v['journal_number'],                       
                    'trans_number' => $v['trans_number'],
                    'account_id' => $v['account_id'],
                    'account_code' => $v['account_code'],
                    'account_name' => $v['account_name'],
                    'debit' => $v['debit'],
                    'credit' => $v['credit'],
                    'balance' => $v['balance'],
                    'journal_item_date_format' => $v['journal_item_date_format'],
                    'status' => $v['status'],
                    'message' => $v['message'],
                    'total_data' => $v['total_data'],
                    'journal_session' => $v['journal_session'],
                    'trans_session' => $v['trans_session'],
                    'journal_id' => $v['journal_id'],
                    'trans_id' => $v['trans_id'],
                    'order_id' => $v['order_id'],
                    'contact_name' => $v['contact_name'],
                    'url' => $url                         
                );
            // }
            $total_data = $v['total_data'];
        endforeach;
        if(intval($total_data) > 0){
            $total=$total_data;
            $return->status=1; 
            $return->message='Loaded'; 
            $return->total_records=$total;
            $return->result=$mdatas;        
        }else{ 
            $data_source=0; $total=0; 
            $return->status=0; $return->message='No data'; $return->total_records=$total;
            $return->result=0;    
        }
        $return->recordsTotal = $total;
        $return->recordsFiltered = $total;

        // echo json_encode($return);       die;
        // echo json_encode($mdatas);die;
        $data['periode'] = date("d-M-Y", strtotime($date_start)).' sd '.date("d-M-Y", strtotime($date_end));
        // $data['periode'] = ' sd '.date("d-M-Y, H:i", strtotime($date_end));    
        $data['result']  = array(
            'start' => $start,
            'limit' => $limit,
            'total' => $total
        );         
        $data['content'] = $mdatas;
        $data['account'] = $account;
        $data['title'] = "Pengeluaran Uang / Biaya";        
        $this->load->view('layouts/admin/menu/prints/reports/report_finance_cash_out',$data);         
    }
}

?>