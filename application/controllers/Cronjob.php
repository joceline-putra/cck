<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller{

    public $app_name;
    public $app_url;
    public $app_logo;
    var $folder_upload = 'uploads/message/';

    function __construct(){
        parent::__construct();
        $this->load->helper('url');        
        $this->load->helper('date');       
        $this->load->helper('cookie'); 
        
        $this->load->library('email');
        $this->load->library('phpmailer_lib');

        $this->load->config('email');
        $this->load->config('whatsapp');
        $this->load->config('firebase');

        $this->load->model('Login_model');   
        $this->load->model('Branch_model');
        $this->load->model('User_model');
        $this->load->model('Kontak_model');
        $this->load->model('News_model');
        $this->load->model('Kategori_model');
        $this->load->model('Message_model');
        $this->load->model('Device_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Branch_model');
        $this->load->model('Account_map_model');
        
        //Get Branch
        $get_branch = $this->Branch_model->get_branch(1);
        $this->app_name = $get_branch['branch_name'];
        $this->app_url  = site_url();  
        $this->app_logo = site_url().$get_branch['branch_logo'];
    }
    function index($action){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        /* Initializing */
        $result             = array();
        $set_group_session  = $this->random_code(8);
        $set_header         = "🖥️".$this->app_name." Cron Job"."\r\n";
        $set_text           = '';
        $set_url            = $this->app_url;
        $set_footer         = '🖥 Server Cloud '.$this->app_name."\r\n"."Mohon untuk tidak dibagikan diluar perusahaan";
        $set_file           = null;
        $recipient_name    = 'Joe';
        $recipient_number   = '628989900148';

        if($action){
            switch($action){
                case "example":
                    /* Call
                    $prepare = "CALL sp_test()";
                    $query   = $this->db->query($prepare); mysqli_next_result($this->db->conn_id);
                    $row     = $query->result_array();  */

                    /* Query */
                    $prepare = "SELECT * FROM `users` ORDER BY `user_id` ASC";
                    $query   = $this->db->query($prepare);
                    $row     = $query->result_array();

                    /* Model
                    $row     = $this->User_model->get_all_users(null,null,null,null,null,null);  */
                                        
                    // Fetch Row
                    foreach($row as $v){
                        $result[] = array(
                            'user_id' => $v['user_id'],
                            'user_name' => $v['user_username'],
                        );

                        //Text Writing
                        $set_text .= $v['user_username'] . "\r\n";
                    }
                    $set_text .= $set_footer;

                    break;
                case "product-top-buy":
                        $session_branch_id = 1;
                        $type = 1;
                        $start = date('Y-m-01').' 00:00:00';
                        $end = date('Y-m-d').' 23:59:59';                    
                        $where_and = "
                            AND trans_item_date > '$start'
                            AND trans_item_date < '$end'
                        ";
    
                        /* Query */
                        $prepare = "SELECT
                            product_id,
                            product_name,
                            trans_item_in_price,
                            FORMAT(SUM(trans_item_in_qty),2) AS total_item_qty,
                            trans_item_unit,
                            trans_date_created,
                            (SELECT fn_time_ago(trans_date)) AS time_ago
                        FROM products
                        LEFT JOIN trans_items ON trans_item_product_id = product_id
                        LEFT JOIN trans ON trans_item_trans_id = trans_id
                        WHERE trans_item_branch_id=$session_branch_id AND trans_item_flag=1 AND trans_type =$type $where_and
                        GROUP BY `product_name`
                        ORDER BY total_item_qty DESC
                        LIMIT 3";
                        $query   = $this->db->query($prepare);
                        $row     = $query->result_array();
                        // var_dump($row);die;
                        // Fetch Row
                        $set_text .= '*3 Pembelian Produk Periode* '."\r\n".date('d-M-Y', strtotime($start))." sd ".date('d-M-Y', strtotime($end))."\r\n\r\n";
                        $num = 1;
                        foreach($row as $v){
                            //Text Writing
                            $set_text .= "*Qty:* ".number_format($v['total_item_qty'],2,'.',',')." ".$v['trans_item_unit']."\r\n";
                            $set_text .= "*Harga Beli:* ".number_format($v['trans_item_in_price'],0,'.',',') . "\r\n";
                            $set_text .= "*Produk:* ".ltrim($v['product_name']) . "\r\n"; 
                            $set_text .= "*Terakhir:* ".ltrim($v['time_ago']) . "\r\n\r\n";             
                            $num++;
                        }               
                        $set_url = $set_url.'r/product-top-buy?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                        $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                        
                        $set_text .= $set_footer;  
                    break;         
                case "product-top-sell":
                    $session_branch_id = 1;
                    $type = 2;
                    $start = date('Y-m-01').' 00:00:00';
                    $end = date('Y-m-d').' 23:59:59';                    
                    $where_and = "
                        AND trans_item_date > '$start'
                        AND trans_item_date < '$end'
                    ";

                    /* Query */
                    $prepare = "SELECT
                        product_id,
                        product_name,
                        trans_item_sell_price,
                        FORMAT(SUM(trans_item_out_qty),2) AS total_item_qty,
                        trans_item_unit,
                        trans_date_created,
                        (SELECT fn_time_ago(trans_date)) AS time_ago
                    FROM products
                    LEFT JOIN trans_items ON trans_item_product_id = product_id
                    LEFT JOIN trans ON trans_item_trans_id = trans_id
                    WHERE trans_item_branch_id=$session_branch_id AND trans_item_flag=1 AND trans_type =$type $where_and
                    GROUP BY `product_id`
                    ORDER BY total_item_qty DESC
                    LIMIT 3";
                    $query       = $this->db->query($prepare);              
                    $row         = $query->result_array();
                    // Fetch Row
                    $set_text .= '*3 Produk Terlaris Terjual Periode* '."\r\n".date('d-M-Y', strtotime($start))." sd ".date('d-M-Y', strtotime($end))."\r\n\r\n";
                    foreach($row as $v){
                        //Text Writing
                        $set_text .= "*Qty:* ".number_format($v['total_item_qty'],2,'.',',')." ".$v['trans_item_unit']."\r\n";
                        $set_text .= "*Harga Rata2:* ".number_format($v['trans_item_sell_price'],0,'.',',') . "\r\n";
                        $set_text .= "*Produk:* ".ltrim($v['product_name']) . "\r\n\r\n";             
                    }          
                    //Generate URL     
                    $set_url = $set_url.'r/product-top-sell?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                    $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";
                    $set_text .= $set_footer;  
                    break;                        
                case "finance-top-cost-out":
                    $session_branch_id = 1;
                    $type = 4;
                    $start = date('Y-m-01').' 00:00:00';
                    $end = date('Y-m-d').' 23:59:59';                    
                    $where_and = "
                        AND journal_item_date > '$start'
                        AND journal_item_date < '$end'
                    ";

                    /* Query */
                    $prepare = "SELECT journal_item_account_id, accounts.account_name AS name,
                    SUM(journal_item_debit) AS total
                    FROM journals_items
                    LEFT JOIN accounts ON journals_items.journal_item_account_id=accounts.account_id
                    WHERE journal_item_branch_id=$session_branch_id AND journal_item_type IN ($type) AND journal_item_debit > 0
                    AND journal_item_account_id IN (
                        SELECT account_id FROM accounts WHERE account_branch_id=$session_branch_id AND account_group_sub=16
                    )
                    $where_and
                    GROUP BY journal_item_account_id
                    ORDER BY total DESC LIMIT 6";
                    $query   = $this->db->query($prepare);
                    $row     = $query->result_array();
      
                    // Fetch Row
                    $set_text .= '*6 Biaya Terbesar Periode* '."\r\n".date('d-M-Y', strtotime($start))." sd ".date('d-M-Y', strtotime($end))."\r\n\r\n";
                    foreach($row as $v){
                        //Text Writing
                        $set_text .= number_format($v['total'],0,'.',',') . "\r\n";
                        $set_text .= ltrim($v['name']) . "\r\n\r\n";             
                    }               
                    $set_url = $set_url.'r/finance-top-cost-out?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                    $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                    
                    $set_text .= $set_footer;  
                    break;
                case "finance-top-contact":
                    $session_branch_id = 1;
                    $type = 2;
                    $start = date('Y-m-01').' 00:00:00';
                    $end = date('Y-m-d').' 23:59:59';                    
                    $where_and = "
                        AND journal_date > '$start'
                        AND journal_date < '$end'
                    ";

                    /* Query */
                    $prepare = "SELECT contact_id, fn_capitalize(contacts.contact_name) AS `name`,
                    SUM(journal_total) AS total,
                    fn_time_ago(MAX(journal_date) OVER(PARTITION BY journal_date)) AS last_insert                 
                    FROM journals
                    LEFT JOIN contacts ON journals.journal_contact_id=contacts.contact_id
                    WHERE journal_branch_id=$session_branch_id AND journal_type IN ($type)
                    $where_and
                    GROUP BY journal_contact_id
                    ORDER BY total DESC LIMIT 10";
                    $query   = $this->db->query($prepare);
                    $row     = $query->result_array();
                    // var_dump($row);die;
                    // Fetch Row
                    $set_text .= '*10 Customer Pembayaran Rajin Periode* '."\r\n".date('d-M-Y', strtotime($start))." sd ".date('d-M-Y', strtotime($end))."\r\n\r\n";
                    foreach($row as $v){
                        //Text Writing
                        $set_text .= "*".ltrim(rtrim($v['name']))."*"."\r\n";
                        $set_text .= number_format($v['total'],0,'.',',') . "\r\n";
                        $set_text .= ltrim($v['last_insert']) . "\r\n\r\n";             
                    }               
                    $set_url = $set_url.'r/finance-top-contact?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                    $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                      
                    $set_text .= $set_footer;  
                    break;
                case "finance-business-flow":
                    $session_branch_id = 1;
                    /* Call  */
                    $prepare = "CALL sp_chart_buy_sell($session_branch_id)";
                    $query   = $this->db->query($prepare); mysqli_next_result($this->db->conn_id);
                    $row     = $query->result_array();

                    // var_dump($row);die;
                    // Fetch Row
                    $set_text .= "*3 Bulan Pergerakan Bisnis Anda* "."\r\n\r\n";
                    $num = 1;
                    foreach($row as $v){
                        //Text Writing
                        if($num > 3){
                            $set_text .= "📌 *".$v['temp_name']."*"."\r\n";
                            $set_text .= "📉 *Pembelian:* ".number_format($v['temp_total_buy'],0,'.',',')."\r\n";
                            $set_text .= "📈 *Penjualan:* ".number_format($v['temp_total_sell'],0,'.',',')."\r\n";
                            $set_text .= "📥 *Pemasukan:* ".number_format($v['temp_total_income'],0,'.',',')."\r\n";
                            $set_text .= "📤 *Biaya:* ".number_format($v['temp_total_expense'],0,'.',',')."\r\n\r\n";                                                                                  
                        }
                        $num++;
                    }
                    $set_url = $set_url.'r/finance-business-flow'."\r\n";
                    $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                                     
                    $set_text .= $set_footer;  
                    break;                    
                case "trans-buy-date-due":
                    $session_branch_id = 1;
                    $contact_id = "''";
                    $start = date('Y-m-01').' 00:00:00';
                    $end = date('Y-m-d').' 23:59:59';

                    /* Call  */
                    $prepare = "CALL sp_report_finance(4,'$start','$end',$session_branch_id,$contact_id,'')";
                    $query   = $this->db->query($prepare); mysqli_next_result($this->db->conn_id);
                    $row     = $query->result_array();

                    // var_dump($row);die;
                    // Fetch Row
                    $set_text .= "*Pembelian Jatuh Tempo* "."\r\n\r\n";
                    $num = 1;
                    foreach($row as $v){
                        //Text Writing
                        if($v['trans_type'] == 1){
                            if($num > 0 and $num < 10){
                                $set_text .= "*Invoice:* ".$v['trans_number']."\r\n";
                                $set_text .= "*Customer:* ".$v['contact_name']."\r\n";                                
                                $set_text .= "*Total:* ".number_format($v['trans_total'],0,'.',',')."\r\n";
                                $set_text .= "*Kekurangan:* ".number_format($v['balance'],0,'.',',')."\r\n";                                
                                $set_text .= "*Jth Tempo:* ".$v['trans_date_due_over']."\r\n\r\n";                                
                                // $set_text .= "📥 *Pemasukan:* ".number_format($v['temp_total_income'],0,'.',',')."\r\n";
                                // $set_text .= "📤 *Biaya:* ".number_format($v['temp_total_expense'],0,'.',',')."\r\n\r\n";                                                                                  
                            }
                            $num++;
                        }
                    }    
                    $set_url = $set_url.'r/buy-date-due?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                    $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                                 
                    $set_text .= $set_footer;  
                    break;                    
                case "trans-sell-date-due":
                    $session_branch_id = 1;
                    $contact_id = "''";
                    $start = date('Y-m-01').' 00:00:00';
                    $end = date('Y-m-d').' 23:59:59';

                    /* Call  */
                    $prepare = "CALL sp_report_finance(5,'$start','$end',$session_branch_id,$contact_id,'')";
                    $query   = $this->db->query($prepare); mysqli_next_result($this->db->conn_id);
                    $row     = $query->result_array();

                    // var_dump($row);die;
                    // Fetch Row
                    $set_text .= "*Invoice Jatuh Tempo* "."\r\n\r\n";
                    $num = 1;
                    foreach($row as $v){
                        //Text Writing
                        if($v['trans_type'] == 2){
                            if($num > 0 and $num < 10){
                                $set_text .= "*Invoice:* ".$v['trans_number']."\r\n";
                                $set_text .= "*Customer:* ".$v['contact_name']."\r\n";                                
                                $set_text .= "*Total:* ".number_format($v['trans_total'],0,'.',',')."\r\n";
                                $set_text .= "*Kekurangan:* ".number_format($v['balance'],0,'.',',')."\r\n";                                
                                $set_text .= "*Jth Tempo:* ".$v['trans_date_due_over']."\r\n\r\n";                                
                                // $set_text .= "📥 *Pemasukan:* ".number_format($v['temp_total_income'],0,'.',',')."\r\n";
                                // $set_text .= "📤 *Biaya:* ".number_format($v['temp_total_expense'],0,'.',',')."\r\n\r\n";                                                                                  
                            }
                            $num++;
                        }
                    }             
                    $set_url = $set_url.'r/sell-date-due?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                    $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                        
                    $set_text .= $set_footer;  
                    break;  
                case "journal-not-balance":
                    $session_branch_id = 1;
                    $contact_id = "''";
                    $start = date('Y-m-01').' 00:00:00';
                    $end = date('Y-m-d').' 23:59:59';

                    /* Call  */
                    $prepare = "SELECT journal_item_trans_id, journal_item_journal_id, journal_item_type_name, journal_item_group_session, journal_number, trans_number,
                    journal_date, trans_date,
                    SUM(journal_item_debit), SUM(journal_item_credit), ABS(SUM(journal_item_debit)-SUM(journal_item_credit)) AS selisih
                    FROM journals_items
                    LEFT JOIN journals ON journal_item_journal_id=journal_id
                    LEFT JOIN trans ON journal_item_trans_id=trans_id
                    GROUP BY journal_item_group_session
                    HAVING ABS(SUM(journal_item_debit)-SUM(journal_item_credit)) > 99
                    ORDER BY selisih DESC LIMIT 5";
                    $query   = $this->db->query($prepare);
                    $row     = $query->result_array();
                    // var_dump($row);die;
                    // Fetch Row
                    $set_text .= "*Journal Not Balance* "."\r\n\r\n";
                    $num = 1;
                    foreach($row as $v){
                        //Text Writing
                        if($v['journal_item_type_name'] === '-'){
                            $set_text .= "⚠️ "."*Type Undefined*"."\r\n";
                        }else{
                            $set_text .= "✅ "."*".$v['journal_item_type_name']."* "."\r\n";
                        }

                        $set_text .= "*Session:* ```".$v['journal_item_group_session']."```"."\r\n";      
                        if(!empty($v['journal_item_journal_id'])){
                            $set_text .= "*Journal:* ```".$v['journal_number']."```, *[".$v['journal_item_journal_id']."]*"."\r\n";
                            $set_text .= "*Date:* ".date("d-M-y, H:i", strtotime($v['journal_date']))."\r\n";
                        }
                        if(!empty($v['journal_item_trans_id'])){
                            $set_text .= "*Trans:* ```".$v['trans_number']."```, *[".$v['journal_item_trans_id']."]*"."\r\n";
                            $set_text .= "*Date:* ".date("d-M-y, H:i", strtotime($v['trans_date']))."\r\n";                            
                        }
                        // $set_text .= $v['journal_date'].", ".$v['trans_date']."\r\n";                                 
                        $set_text .= "*Selisih:* ".number_format($v['selisih'],0,'.',',')."\r\n\r\n";
                        $num++;
                    }             
                    // $set_url = $set_url.'r/sell-date-due?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                    // $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                        
                    $set_text = $set_header.$set_text.$set_footer;  
                    break;  
                case "trans-sell-not-perfect":
                    $session_branch_id = 1;
                    $contact_id = "''";
                    $start = date('Y-m-01').' 00:00:00';
                    $end = date('Y-m-d').' 23:59:59';

                    $params_ar = array(
                        'account_map_branch_id' => 1,
                        'account_map_note' => 'PiutangUsaha'
                    );
                    $get_ar = $this->Account_map_model->get_account_map_custom($params_ar);
                    $account_id = $get_ar['account_map_account_id'];
                    // var_dump($account_id);die;

                    /* Call  */
                    $prepare = "SELECT trans_id, trans_number, trans_date, trans_total_dpp, trans_total_ppn, trans_total, trans_return, trans_discount, trans_total_paid,
                    journal_item_debit, trans_total_paid - journal_item_debit AS selisih 
                    FROM trans 
                    LEFT JOIN (
                        SELECT journal_item_trans_id, SUM(journal_item_debit) AS journal_item_debit FROM journals_items WHERE journal_item_account_id=$account_id AND journal_item_type=11 GROUP BY journal_item_trans_id
                    ) AS journals ON trans.trans_id=journals.journal_item_trans_id
                    WHERE trans_type = 2 
                    HAVING selisih > 0
                    ORDER BY trans_date DESC LIMIT 5";
                    $query   = $this->db->query($prepare);
                    $row     = $query->result_array();
                    // var_dump($row);die;
                    // Fetch Row
                    $set_text .= "*Trans Sell Not Perfect* "."\r\n\r\n";
                    $num = 1;
                    foreach($row as $v){

                        //Text Writing
                        // if($v['journal_item_type_name'] === '-'){
                            // $set_text .= "⚠️ "."*Type Undefined*"."\r\n";
                        // }else{
                            // $set_text .= "✅ "."*".$v['journal_item_type_name']."* "."\r\n";
                        // }

                        // $set_text .= "*Session:* ```".$v['journal_item_group_session']."```"."\r\n";      
                        if($v['selisih'] > 0){
                            $set_text .= "*Trans:* ```".$v['trans_number']."```, *[".$v['trans_id']."]*"."\r\n";
                            $set_text .= "*Date:* ".date("d-M-y, H:i", strtotime($v['trans_date']))."\r\n";  
                            $set_text .= "*Total:* ".number_format($v['trans_total'],0,'.',',')."\r\n";
                            $set_text .= "*Piutang:* ".number_format($v['journal_item_debit'],0,'.',',')."\r\n";                                                                                    
                            $set_text .= "*Selisih:* ".number_format($v['selisih'],0,'.',',')."\r\n";
                            $set_text .= "```UPDATE journals_items SET journal_item_debit='".$v['trans_total']."' WHERE journal_item_trans_id=".$v['trans_id']." AND journal_item_account_id=659 AND journal_item_type=11;```"."\r\n\r\n";
                            $set_text .= "```UPDATE journals_items SET journal_item_credit='".$v['trans_total']."' WHERE journal_item_trans_id=".$v['trans_id']." AND journal_item_account_id=720 AND journal_item_type=11;```"."\r\n\r\n";
                        }
                        // $set_text .= $v['journal_date'].", ".$v['trans_date']."\r\n";                                 

                        $num++;
                    }             
                    // $set_url = $set_url.'r/sell-date-due?s='.date("Y-m-d", strtotime($start)).'&e='.date("Y-m-d", strtotime($end))."\r\n";
                    // $set_text .= "Selengkapnya dapat dilihat disini: "."\r\n".$set_url."\r\n";                        
                    $set_text = $set_header.$set_text.$set_footer;  
                    break;                                                                                            
                default:
                    $action = $action;
            }
            // End of Switch

            //Detect Num has increase is available
            if(intval($num) > 1){
                // Fetch User / Receipient
                $user_params = array(
                    'user_id' => 1
                );
                $get_user     = $this->User_model->get_all_users($user_params,null,null,null,null,null);
                foreach($get_user as $u){

                    // Add to Message
                    $params = array(
                        'message_type' => 1, 'message_session' => $this->random_code(20), 'message_group_session' => $set_group_session,
                        'message_date_created' => date("YmdHis"), 'message_flag' => 0,
                        'message_text' => $set_text,'message_url' => $set_file,
                        'message_contact_name' => !empty($u['user_username']) ? $u['user_username'] : null,
                        'message_contact_number' => !empty($u['user_phone_1']) ? intval($u['user_phone_1']) : null,
                    );
                    $this->Message_model->add_message($params);                                 
                }          

                // Send Message by Group Session
                // $opr = $this->whatsapp_send_group($set_group_session);
                $return->status=1;
                $return->message = 'Cronjob successfully';
            }else{
                $return->status=0;
                $return->message = 'Cronjob not sent';
            }        
        }else{
            $set_text = "🖥 ".$this->app_name." Cron Job "."\r\n"."❓ Empty GET Request \r\n" . "📅 " . date('d-M-Y'). " ⏰ " . date('H:i');

            // Add to Message
            $params = array(
                'message_type' => 1, 'message_session' => $this->random_code(20), 'message_group_session' => $set_group_session,
                'message_date_created' => date("YmdHis"), 'message_flag' => 0,
                'message_text' => $set_text,'message_url' => $set_file,
                'message_contact_name' => $recipient_name,
                'message_contact_number' => $recipient_number,
            );

            // Send Message by Group Session
            $this->Message_model->add_message($params);   
            // $this->whatsapp_send_group($set_group_session);

            $return->status  = 0;
            $return->message = 'Nothing to do';
        }
        echo json_encode($return);
    }
    function random_code($length){ # JEH3F2
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[rand(0, $txtlen)];}
        return $result;
    }
    /*
    This Function Not Available, Cronjob not for send message
    function whatsapp_send_id($message_id = 0){ // Works : Update Send From Multiple Device
        $return             = new \stdClass();
        $return->status     = 0;
        $return->message    = 'Failed';
        $return->result     = '';

        $message_id         = intval($message_id);
        $message_session    = $this->input->get('session');

        $whatsapp_server    = $this->config->item('whatsapp_server');
        $whatsapp_action    = $this->config->item('whatsapp_action');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');
        // $whatsapp_vendor    = $this->config->item('whatsapp_vendor');      
        // $whatsapp_token     = $this->config->item('whatsapp_token');
        // $whatsapp_key       = $this->config->item('whatsapp_key');
        // $whatsapp_auth      = $this->config->item('whatsapp_auth');  

        if(!empty($whatsapp_sender)){
            $next=true;
            $url = '';

            //Get Message 
            if( (!empty($message_session) && strlen($message_session) > 1) or (!empty($message_id) && strlen($message_id) > 0)){
                
                if(intval($message_id) > 0){
                    $params = array('message_id' => $message_id);
                    $url = '&id='.$message_id;
                }
                if(strlen($message_session) > 1){
                    $params = array('message_session' => $message_session);
                    $url = '&session='.$message_session;
                }

                $get_message = $this->Message_model->get_message_custom($params);
                $recipient_number = $this->contact_number($get_message['message_contact_number']);
                // $recipient_name = $get_message['message_contact_name'];

                //Send Message Session & ID
                $content = $get_message['message_text'];
                $url .= '&recipient='.$recipient_number;
                $url .= '&content='.rawurlencode($content);

                // Message has a caption
                if(strlen($get_message['message_url']) > 0){
                    $url .= '&file='.$get_message['message_url'];
                }

                //Detect if Sender different from default
                if($get_message['message_device_id'] > 0){
                    $whatsapp_action['send-message'] = 'devices?action=send-message&auth='.$get_message['device_token'].'&sender='.$get_message['device_number'];
                }else{
                    $whatsapp_action['send-message'] = $whatsapp_action['send-message'].'&sender='.$whatsapp_sender;
                }

            }else{
                //Send Message Directly
                // $url .= '&sender='.$whatsapp_sender.'&recipient='.$recipient_number;
                // $url .= '&content='.rawurlencode($content);
                $next = false;
            }
            //CURL If Completed URL Prepare
            if($next){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $whatsapp_server.$whatsapp_action['send-message'].$url,
                    CURLOPT_HEADER => 0,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_POST =>  1,
                    CURLOPT_POSTFIELDS => array(
                    )
                ));
                $response = curl_exec($curl);

                // Result CURL / API
                $get_response = json_decode($response,true);
                if($get_response['result'] !== false){

                    $where = array(
                        'message_id' => $get_message['message_id']
                    );
                    $params = array(
                        'message_date_sent' => date('YmdHis'),
                        'message_flag' => 1
                    );
                    $this->Message_model->update_message_custom($where,$params);
                    $return->result  = $get_response['result'];
                    $return->status  = $get_response['status']; // 1 
                    $return->message = $get_response['message']; // Berhasil
                }                
                // $result = $get_response['result'];
                // $result['message'] = $get_response['message'];

                // $return->status = $get_response['status'];
                // $return->message = $get_response['message'];
                // $return->result = $result;
            }

        }else{
            $return->message='Access Denied';
        }

        return json_encode($return);
    }    
    function whatsapp_send_group($message_group_session){ //Works, Clone to Login.php , Update Send From Multiple Device
        $return             = new \stdClass();
        $return->status     = 0;
        $return->message    = '';
        $return->result     = '';
        

        // WhatsApp Config

        $whatsapp_server    = $this->config->item('whatsapp_server');        
        $whatsapp_action    = $this->config->item('whatsapp_action');         
        $whatsapp_sender    = $this->config->item('whatsapp_sender');         
        // $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        // $whatsapp_token  = $this->config->item('whatsapp_token');
        // $whatsapp_auth  = $this->config->item('whatsapp_auth');

        if(strlen($message_group_session) > 0){
        
            $datas = array();
            $where = array(
                'message_group_session' => $message_group_session
            );
            $get_data=$this->Message_model->get_message_custom_result($where);
            if(count($get_data) > 0){
                foreach($get_data as $v){

                    // Fetch Data
                    $datas[] = array(
                        'message_group_session' => $v['message_group_session'],
                        'message_id' => $v['message_id'],
                        'message_session' => $v['message_session'],
                        'message_text' => $v['message_text'],
                        'message_contact_number' => $v['message_contact_number'],
                        'message_url' => $v['message_url'],
                        'message_device_id' => $v['message_device_id']
                    );

                    //Detect if Sender different from default
                    if($v['message_device_id'] > 0){
                        $curl_link = $whatsapp_server.'devices?action=send-message&auth='.$v['device_token'].'&sender='.$v['device_number'];
                    }else{
                        $curl_link = $whatsapp_server.$whatsapp_action['send-message'].'&sender='.$whatsapp_sender;
                    }
                    $curl_link .= '&recipient='.$v['message_contact_number'];
                    $curl_link .= '&content='.rawurlencode($v['message_text']); 

                    // Message has a caption
                    if(strlen($v['message_url']) > 0){
                        $curl_link .= '&file='.$v['message_url'];
                    }

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $curl_link,
                        CURLOPT_HEADER => 0,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST =>  1,
                        CURLOPT_POSTFIELDS => 1,
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                
                    // Result Response
                    $get_response = json_decode($response,true);
                    if($get_response['result'] !== false){

                        $where = array(
                            'message_id' => $v['message_id']
                        );
                        $params = array(
                            'message_date_sent' => date('YmdHis'),
                            'message_flag' => 1
                        );
                        $this->Message_model->update_message_custom($where,$params);
                    }
                    $return->result  = $get_response['result']; // Result
                    $return->status  = $get_response['status']; // 1 / 0
                    $return->message = $get_response['message']; // Berhasil / Gagal                
                }
            }else{ 
                $return->message='Session not found';                
            }
        }else{
            $return->message='Session not found';
        }
        return json_encode($return);
    }    
    function whatsapp_send_message($params){ //Works
        // Example
        //     $params = array(
        //         'header' => 'Judul',	
        //         'file' => 'https://www.planetware.com/wpimages/2020/02/france-in-pictures-beautiful-places-to-photograph-eiffel-tower.jpg',
        //         'content' => 'Isi Pesan',	
        //         'recipient' => array(
        //             array('number' => '6281225518118', 'name' => 'Joe'),                                                                                                                                                             
        //         ),
        //         'footer' => '🖥️ Pesan ini dikirim oleh System'
        //     );
             
        $return = new \stdClass();
        $return->status = 0;
        $return->message = 'Failed';
        $return->result = '';

        $whatsapp_server    = $this->config->item('whatsapp_server').'devices?action=send-message';
        $whatsapp_auth      = $this->config->item('whatsapp_auth');
        $whatsapp_sender    = $this->config->item('whatsapp_sender');

        if(count($params) > 0){
            $content    = $params['content'];
            $recipient  = $params['recipient'];
            $file       = $params['file'];
        
            if(count($recipient) > 0){
                $header = '*'.$params['header']."*"."\r\n\r\n";
                $footer = "\r\n".$params['footer']."\r\n";
                $set_content = rawurlencode($header.$content.$footer);
                for($i=0; $i<count($recipient); $i++){
                    
                    //Detect Message have a Caption
                    if(!empty($file)){
                        $caption = "Attachment";
                        $url_file = '&auth='.$whatsapp_auth.'&recipient='.$recipient[$i]['number'].'&sender='.$whatsapp_sender.'&content='.$set_content.'&file='.$file;
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $whatsapp_server.$url_file,
                            CURLOPT_HEADER => 0,
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_SSL_VERIFYHOST => 2,
                            CURLOPT_SSL_VERIFYPEER => 0,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_POST =>  1,
                            CURLOPT_POSTFIELDS => array(
                            )
                        ));              
                        $response = curl_exec($curl);                                        
                    }else{ //Dont have a caption
                        $url = '&auth='.$whatsapp_auth.'&recipient='.$recipient[$i]['number'].'&sender='.$whatsapp_sender.'&content='.$set_content;

                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $whatsapp_server.$url,
                            CURLOPT_HEADER => 0,
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_SSL_VERIFYHOST => 2,
                            CURLOPT_SSL_VERIFYPEER => 0,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_POST =>  1,
                            CURLOPT_POSTFIELDS => array(
                            )
                        ));
                        $response = curl_exec($curl);
                    }                
                }
                // Result CURL / API
                $get_response = json_decode($response,true);
                $return->result  = $get_response['result']; // Result
                $return->status  = $get_response['status']; // 1 / 0
                $return->message = $get_response['message']; // Berhasil / Gagal                
            }else{
                $return->message='Penerima tidak ada';
            }
        }else{
          $return->message='Params doest exist';
        }
        $return->params = $params;
        // return json_encode($get_response);
        return json_encode($return);
    }
    */
}