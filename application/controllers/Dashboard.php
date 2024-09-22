<?php
/*
    @AUTHOR: Joe Witaya
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller{
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            redirect(base_url("login"));
        }
    }
    function manage(){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';

        $request = $this->input->post("action"); //nama request

    	if($request=="top-product"){ // top 10 product
            $type = $this->input->post('type');
            if(intval($type) == 1){
                $query = $this->db->query("
                    SELECT
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
                    WHERE trans_item_branch_id=$session_branch_id AND trans_item_flag=1 AND trans_type =$type
                    GROUP BY `product_name`
                    ORDER BY total_item_qty DESC
                    LIMIT 10
                ");
            }else{
                $query = $this->db->query("
                    SELECT
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
                    WHERE trans_item_branch_id=$session_branch_id AND trans_item_flag=1 AND trans_type =$type
                    GROUP BY `product_name`
                    ORDER BY total_item_qty DESC
                    LIMIT 10
                ");
            }
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->result_array();
    	}else if($request=="top-custom"){ // top 10 product
            $type = $this->input->post('type');
            if(intval($type) == 1){
                $query = $this->db->query("
                    SELECT type_name,
                        IFNULL(SUM(trans_total),0) AS total,
                        CONCAT(0) AS average
                    FROM trans
                    LEFT JOIN types ON trans_type=type_type AND type_for=2
                    WHERE trans_type=1 OR trans_type=2
                    GROUP BY trans_type
                ");
            }elseif(intval($type) == 2){
                $query = $this->db->query("
                    SELECT
                        account_id,
                        account_name,
                        IFNULL(SUM(journal_item_debit) - SUM(journal_item_credit),0) AS balance
                    FROM journals_items
                    LEFT JOIN accounts ON journal_item_account_id = account_id
                    WHERE account_group=1 AND account_group_sub=3
                    GROUP BY `account_name`
                    ORDER BY account_name ASC
                ");
            }elseif(intval($type) == 3){
                $query = $this->db->query("
                    SELECT
                        product_name,
                        product_unit,
                        location_name,
                        IFNULL(SUM(trans_item_in_qty) - SUM(trans_item_out_qty),0) AS balance
                    FROM trans_items
                    LEFT JOIN locations ON trans_item_location_id=location_id
                    LEFT JOIN products ON trans_item_product_id=product_id
                    GROUP BY trans_item_location_id, product_id
                ");
            }else if(intval($type) == 4){
                $query = $this->db->query("
                    SELECT (
                            SELECT IFNULL(SUM(trans_item_pack),0) FROM trans_items WHERE trans_item_type=1
                        ) AS coli_jadi,
                        (
                            SELECT IFNULL(SUM(trans_item_pack),0) FROM trans_items WHERE trans_item_type=2
                        ) AS coli_kirim,
                        (
                            SELECT coli_jadi - coli_kirim
                        ) AS coli_gudang,
                        (
                            SELECT IFNULL(SUM(trans_item_pack),0) FROM trans_items WHERE trans_item_type=2
                        ) AS coli_terjual,
                        (
                            SELECT coli_kirim - coli_terjual
                        ) AS coli_didjarum
                ");
            }else{
                $query = $this->db->query("
                    SELECT
                        product_name,
                        trans_item_sell_price,
                        SUM(trans_item_out_qty) AS total_item_qty,
                        trans_item_unit,
                        trans_date_created,
                        (SELECT fn_time_ago(trans_date)) AS time_ago
                    FROM products
                    LEFT JOIN trans_items ON trans_item_product_id = product_id
                    LEFT JOIN trans ON trans_item_trans_id = trans_id
                    WHERE trans_item_branch_id=$session_branch_id AND trans_item_flag = '1' AND trans_type =$type
                    GROUP BY `product_name`
                    ORDER BY total_item_qty DESC
                    LIMIT 10
                ");
            }
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->result_array();
        }else if($request=="total-transaction-month"){ // total selling this month
            $type = $this->input->post('type');
            /*$query = $this->db->query("
                SELECT YEAR(order_item_date) AS year_now,MONTH(order_item_date) AS month_now, SUM(order_item_total) AS total_selling_month
                FROM order_items
                WHERE order_item_type=2
                AND order_item_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00')
                AND order_item_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
            ");*/
            $query = $this->db->query("
                SELECT YEAR(trans_date) AS year_now,MONTH(trans_date) AS month_now, SUM(trans_total) AS total_selling_month
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_type=$type
                AND trans_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00')
                AND trans_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
            ");
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->row_array();
    	}else if($request=="total-transaction-day"){ // total selling this day
            $type = $this->input->post('type');
            /*$query = $this->db->query("
                SELECT DATE_FORMAT(NOW() ,'%Y-%m-%d') AS date_now, SUM(order_item_total) AS total_selling_day
                FROM order_items
                WHERE order_item_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 00:00:00')
                AND order_item_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
                AND order_item_flag = 1
            ");*/
            $query = $this->db->query("
                SELECT DATE_FORMAT(NOW() ,'%Y-%m-%d') AS date_now, IFNULL(SUM(trans_total),0) AS total_transaction_day
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 00:00:00')
                AND trans_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
                AND trans_type=$type
            ");
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->row_array();
        }else if($request=="chart-trans-last-NOT-USED"){
            /*$query = $this->db->query("
                SELECT
                DATE_FORMAT(order_item_date,'%d %b') AS order_date,
                DATE_FORMAT(order_item_date,'%d %M %Y') AS full_date,
                SUM(order_item_total) AS total_sold,
                FORMAT(SUM(order_item_total), 0, 'de_DE') AS total_sold_label
                FROM order_items
                WHERE order_item_flag = '1'AND YEAR(order_item_date)=YEAR(CURDATE()) AND MONTH(order_item_date)=MONTH(CURDATE())
                GROUP BY DAY(`order_item_date`)
                ORDER BY order_item_date ASC
                LIMIT 6
            ");*/
            $query1 = $this->db->query("
                SELECT
                    DATE_FORMAT(trans_date,'%d %b') AS order_date,
                    DATE_FORMAT(trans_date,'%d %M %Y') AS full_date,
                    SUM(trans_total) AS total_sold,
                    FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_type=1 AND trans_flag = '1' AND YEAR(trans_date)=YEAR(CURDATE()) AND MONTH(trans_date)=MONTH(CURDATE())
                GROUP BY DAY(`trans_date`)
                ORDER BY trans_date ASC
                LIMIT 6
            ");
            $query2 = $this->db->query("
                SELECT
                    DATE_FORMAT(trans_date,'%d %b') AS order_date,
                    DATE_FORMAT(trans_date,'%d %M %Y') AS full_date,
                    SUM(trans_total) AS total_sold,
                    FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_type=2 AND trans_flag = '1' AND YEAR(trans_date)=YEAR(CURDATE()) AND MONTH(trans_date)=MONTH(CURDATE())
                GROUP BY DAY(`trans_date`)
                ORDER BY trans_date ASC
                LIMIT 6
            ");
            $return->status=1;
            $return->message='Loaded';
            // $return->result_buy= $query1->result_array();
            // $return->result_sell= $query2->result_array();
            $return->results2= array(
                array('chart_name'=>'Jan','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Feb','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Mar','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Apr','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Mei','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Jun','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6))
            );
            $return->results= array(
                array('chart_name'=>'Jan','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Feb','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Mar','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Apr','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Mei','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
                array('chart_name'=>'Jun','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6))
            );
        }else if($request=="chart-trans-last"){
            /*
            $query1 = $this->db->query("
                SELECT
                    DATE_FORMAT(trans_date,'%d %b') AS order_date,
                    DATE_FORMAT(trans_date,'%d %M %Y') AS full_date,
                    SUM(trans_total) AS total_sold,
                    FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_type=1 AND trans_flag = '1' AND YEAR(trans_date)=YEAR(CURDATE()) AND MONTH(trans_date)=MONTH(CURDATE())
                GROUP BY DAY(`trans_date`)
                ORDER BY trans_date ASC
                LIMIT 6
            ");
            $query2 = $this->db->query("
                SELECT
                    DATE_FORMAT(trans_date,'%d %b') AS order_date,
                    DATE_FORMAT(trans_date,'%d %M %Y') AS full_date,
                    SUM(trans_total) AS total_sold,
                    FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_type=2 AND trans_flag = '1' AND YEAR(trans_date)=YEAR(CURDATE()) AND MONTH(trans_date)=MONTH(CURDATE())
                GROUP BY DAY(`trans_date`)
                ORDER BY trans_date ASC
                LIMIT 6
            ");
            */
            $branch = $this->input->post('branch');
            $query = $this->db->query("CALL sp_chart_buy_sell($branch)");
            $result = $query->result_array();
            mysqli_next_result($this->db->conn_id); 
            $query->free_result();
            foreach($result as $v):
                if($v['temp_total_data'] > 0){
                    $results[] = array(
                        'chart_name' => $v['temp_name'],
                        'chart_buy' => $v['temp_total_buy'],
                        'chart_sell' => $v['temp_total_sell'],
                        'chart_income' => $v['temp_total_income'],
                        'chart_expense' => $v['temp_total_expense']
                    );
                    $return->status=1;
                    $return->message='Data Load';
                }
            endforeach;

            // $return->result_buy= $query1->result_array();
            // $return->result_sell= $query2->result_array();

            // $return->results= array(
            //     array('chart_name'=>'Jan','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Feb','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Mar','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Apr','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Mei','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Jun','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6))
            // );
            $return->results = $results;
        }else if($request=="chart-trans-buy-sell"){
            /*
            $query1 = $this->db->query("
                SELECT
                    DATE_FORMAT(trans_date,'%d %b') AS order_date,
                    DATE_FORMAT(trans_date,'%d %M %Y') AS full_date,
                    SUM(trans_total) AS total_sold,
                    FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_type=1 AND trans_flag = '1' AND YEAR(trans_date)=YEAR(CURDATE()) AND MONTH(trans_date)=MONTH(CURDATE())
                GROUP BY DAY(`trans_date`)
                ORDER BY trans_date ASC
                LIMIT 6
            ");
            $query2 = $this->db->query("
                SELECT
                    DATE_FORMAT(trans_date,'%d %b') AS order_date,
                    DATE_FORMAT(trans_date,'%d %M %Y') AS full_date,
                    SUM(trans_total) AS total_sold,
                    FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_type=2 AND trans_flag = '1' AND YEAR(trans_date)=YEAR(CURDATE()) AND MONTH(trans_date)=MONTH(CURDATE())
                GROUP BY DAY(`trans_date`)
                ORDER BY trans_date ASC
                LIMIT 6
            ");
            */
            $query = $this->db->query("CALL sp_chart_buy_sell($session_branch_id)");
            $result = $query->result_array();
            mysqli_next_result($this->db->conn_id); 
            $query->free_result();
            foreach($result as $v):
                if($v['temp_total_data'] > 0){
                    $results[] = array(
                        'chart_name' => $v['temp_name'],
                        'chart_buy' => $v['temp_total_buy'],
                        'chart_sell' => $v['temp_total_sell'],
                    );
                    $return->status=1;
                    $return->message='Data Load';
                }
            endforeach;

            // $return->result_buy= $query1->result_array();
            // $return->result_sell= $query2->result_array();

            // $return->results= array(
            //     array('chart_name'=>'Jan','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Feb','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Mar','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Apr','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Mei','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6)),
            //     array('chart_name'=>'Jun','chart_buy'=> $this->random_number(6), 'chart_sell' => $this->random_number(6))
            // );
            $return->results = $results;
        }else if($request=="chart-account"){
            $query = $this->db->query("
                SELECT
                account_id, account_code, account_name, account_group, account_group_sub,
                  DATE_FORMAT(journal_item_date, '%d %b') AS journal_item_date_short,
                  DATE_FORMAT(journal_item_date, '%d %M %Y') AS journal_item_date_format,
                  fn_time_ago(MAX(journal_item_date) OVER(PARTITION BY journal_item_date)) AS last_insert, 
                  CASE WHEN account_group = 1 THEN SUM(journal_item_debit)-SUM(journal_item_credit)
                  WHEN account_group = 5 THEN SUM(journal_item_debit)-SUM(journal_item_credit)
                  ELSE SUM(journal_item_credit)-SUM(journal_item_debit) END AS balance
                  -- FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM
                  journals_items
                  LEFT JOIN accounts
                    ON journal_item_account_id = account_id
                WHERE journal_item_branch_id = $session_branch_id AND account_locked=1 AND account_group BETWEEN 1 AND 2
                  -- AND YEAR (journal_item_date) = YEAR(CURDATE())
                  -- AND MONTH (journal_item_date) = MONTH(CURDATE())
                GROUP BY `journal_item_account_id`
                ORDER BY balance DESC
                LIMIT 6
            ");
            $return->status=1;
            $return->message='Loaded';
            $return->results= $query->result_array();
        }else if($request=="chart-expense"){
            $query = $this->db->query("
                SELECT
                account_id, account_code, account_name, account_group, account_group_sub,
                  DATE_FORMAT(journal_item_date, '%d %b') AS journal_item_date_short,
                  DATE_FORMAT(journal_item_date, '%d %M %Y') AS journal_item_date_format,
                  fn_time_ago(MAX(journal_item_date) OVER(PARTITION BY journal_item_date)) AS last_insert, 
                  CASE WHEN account_group = 1 THEN SUM(journal_item_debit)-SUM(journal_item_credit)
                  WHEN account_group = 5 THEN SUM(journal_item_debit)-SUM(journal_item_credit)
                  ELSE SUM(journal_item_credit)-SUM(journal_item_debit) END AS balance
                  -- FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
                FROM
                  journals_items
                  LEFT JOIN accounts
                    ON journal_item_account_id = account_id
                WHERE journal_item_branch_id = $session_branch_id
                AND account_group=5 AND account_group_sub=16 AND account_locked=1
                  -- AND YEAR (journal_item_date) = YEAR(CURDATE())
                  -- AND MONTH (journal_item_date) = MONTH(CURDATE())
                GROUP BY `journal_item_account_id`
                ORDER BY balance DESC
                -- LIMIT 6
            ");
            $return->status=1;
            $return->message='Loaded';
            $return->results= $query->result_array();
        }else if($request=="total-cash-in-month"){ // total selling this month
            $type = $this->input->post('type');
            // $type = implode(',', $type);
            $start = !empty($this->input->post('start')) ? date("Y-m-d", strtotime($this->input->post('start')))." 00:00:00" : false;
            $end = !empty($this->input->post('end')) ? date("Y-m-d", strtotime($this->input->post('end')))." 23:59:59" : false;

            $where_and = "
                AND journal_item_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00')
                AND journal_item_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
            ";
            if(($start) and ($end)){
                $where_and = "
                    AND journal_item_date > '$start'
                    AND journal_item_date < '$end'
                ";
            }
            $prepare="
                SELECT YEAR(journal_item_date) AS year_now,MONTH(journal_item_date) AS month_now, SUM(journal_item_debit) AS total_cash_in
                FROM journals_items
                WHERE journal_item_branch_id=$session_branch_id AND journal_item_type IN ($type)
                $where_and
            ";
            $query = $this->db->query($prepare);
            $return->query = $prepare;
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->row_array();
        }else if($request=="total-cash-out-month"){ // total selling this month
            $type = $this->input->post('type');
            // $type = implode(',', $this->input->post('type'));
            $start = !empty($this->input->post('start')) ? date("Y-m-d", strtotime($this->input->post('start')))." 00:00:00" : false;
            $end = !empty($this->input->post('end')) ? date("Y-m-d", strtotime($this->input->post('end')))." 23:59:59" : false;

            $where_and = "
                AND journal_item_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00')
                AND journal_item_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
            ";
            if(($start) and ($end)){
                $where_and = "
                    AND journal_item_date > '$start'
                    AND journal_item_date < '$end'
                ";
            }
            $prepare = "SELECT YEAR(journal_item_date) AS year_now,MONTH(journal_item_date) AS month_now, SUM(journal_item_debit) AS total_cash_out
                FROM journals_items
                WHERE journal_item_branch_id=$session_branch_id
                AND journal_item_account_id IN (
                    SELECT account_id FROM accounts WHERE account_branch_id=$session_branch_id AND account_group_sub=16
                )
                AND journal_item_type IN ($type)
                $where_and";

            // var_dump($prepare);die;
            $query = $this->db->query($prepare);
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->row_array();
        }else if($request=="total-cash-balance"){ // total selling this month
            $type = $this->input->post('type');
            $start = !empty($this->input->post('start')) ? date("Y-m-d", strtotime($this->input->post('start')))." 00:00:00" : false;
            $end = !empty($this->input->post('end')) ? date("Y-m-d", strtotime($this->input->post('end')))." 23:59:59" : false;

            $where_and = "
                AND journal_item_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00')
                AND journal_item_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
            ";
            if(($start) and ($end)){
                $where_and = "
                    AND journal_item_date > '$start'
                    AND journal_item_date < '$end'
                ";
            }
            $prepare ="
                SELECT YEAR(journal_item_date) AS year_now,
                    MONTH(journal_item_date) AS month_now,
                    SUM(journal_item_debit) AS total_cash_debit,
                    SUM(journal_item_credit) AS total_cash_credit,
                    (SELECT SUM(journal_item_debit) - SUM(journal_item_credit)) AS total_cash_balance
                FROM journals_items
                WHERE journal_item_branch_id=$session_branch_id AND journal_item_account_id
                IN (
                    SELECT account_id FROM accounts WHERE account_branch_id=$session_branch_id AND account_group_sub=3
                )
                $where_and
            ";
            $query = $this->db->query($prepare);
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->row_array();
        }else if($request=="get-payment-method"){ // get payment method
            $payment_method = $this->input->post("payment_method");
            if($payment_method == 1){ // cash
                $condition = "AND trans_paid_type=1";
            }else if($payment_method == 2){ // card
                $condition = "AND trans_paid_type=3 OR trans_paid_type=4";
            }else if($payment_method == 3){ // dana
                $condition = "AND trans_paid_type=5 AND trans_digital_provider='DANA'";
            }else if($payment_method == 4){ // gopay
                $condition = "AND trans_paid_type=5 AND trans_digital_provider='GoPAY'";
            }else if($payment_method == 5){ // ovo
                $condition = "AND trans_paid_type=5 AND trans_digital_provider='OVO'";
            }else if($payment_method == 6){ // shopeepay
                $condition = "AND trans_paid_type=5 AND trans_digital_provider='ShopeePAY'";
            }
            $query = $this->db->query("
                SELECT
                COUNT(trans_paid_type) as total_paid_type
                FROM trans
                WHERE trans_branch_id=$session_branch_id AND trans_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 00:00:00')
                AND trans_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59') ".$condition."
            ");
            $return->status=1;
            $return->message='Loaded';
            $return->method= $payment_method;
            $return->result= $query->row_array();
        }else if($request=="finance-list-top-cost-out"){ // total selling this month
            $type = $this->input->post('type');
            $type = implode(',', $this->input->post('type'));
            $limit = !empty($this->input->post('limit')) ? "LIMIT ".$this->input->post('limit') : '';
            $start = !empty($this->input->post('start')) ? date("Y-m-d", strtotime($this->input->post('start')))." 00:00:00" : false;
            $end = !empty($this->input->post('end')) ? date("Y-m-d", strtotime($this->input->post('end')))." 23:59:59" : false;

            $where_and = "
                AND journal_item_date > '$start'
                AND journal_item_date < '$end'
            ";

            $prepare ="SELECT journal_item_account_id, accounts.account_name AS name,
			SUM(journal_item_debit) AS total
			FROM journals_items
			LEFT JOIN accounts ON journals_items.journal_item_account_id=accounts.account_id
            WHERE journal_item_branch_id=$session_branch_id AND journal_item_type IN ($type) AND journal_item_debit > 0
            AND journal_item_account_id IN (
                SELECT account_id FROM accounts WHERE account_branch_id=$session_branch_id AND account_group_sub=16
            )
            $where_and
			GROUP BY journal_item_account_id
			ORDER BY total DESC $limit";

            $query = $this->db->query($prepare);

            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->result_array();
        }else if($request=="finance-list-top-contact"){ // total selling this month
            $type = $this->input->post('type');
            $type = implode(',', $this->input->post('type'));
            $limit = !empty($this->input->post('limit')) ? "LIMIT ".$this->input->post('limit') : '';
            $start = !empty($this->input->post('start')) ? date("Y-m-d", strtotime($this->input->post('start')))." 00:00:00" : date("YmdHis");
            $end = !empty($this->input->post('end')) ? date("Y-m-d", strtotime($this->input->post('end')))." 23:59:59" : date("YmdHis");

            $where_and = "
                -- AND journal_date > '$start'
                AND journal_date < '$end'
            ";

            $prepare = "
				SELECT contact_id, fn_capitalize(contacts.contact_name) AS `name`,
				SUM(journal_total) AS total,
                fn_time_ago(MAX(journal_date) OVER(PARTITION BY journal_date)) AS last_insert                 
				FROM journals
				LEFT JOIN contacts ON journals.journal_contact_id=contacts.contact_id
                WHERE journal_branch_id=$session_branch_id AND journal_type IN ($type)
                $where_and
				GROUP BY journal_contact_id
				ORDER BY total DESC $limit
            ";
            // var_dump($prepare);die;
            $query = $this->db->query($prepare);

            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->result_array();
            $return->prepare=$prepare;
        }else if($request=="finance-list-top-cash-bank"){ // total selling this month
            $type = $this->input->post('type');
            $limit = !empty($this->input->post('limit')) ? "LIMIT ".$this->input->post('limit') : '';
            $start = !empty($this->input->post('start')) ? date("Y-m-d", strtotime($this->input->post('start')))." 00:00:00" : false;
            $end = !empty($this->input->post('end')) ? date("Y-m-d", strtotime($this->input->post('end')))." 23:59:59" : false;

            // $where_and = "
            //     AND journal_item_date > '$start'
            //     AND journal_item_date < '$end'
            // ";
            $where_and = "";
            if(($start) and ($end)){
                $where_and = "
                    AND journal_item_date > '$start'
                    AND journal_item_date < '$end'
                ";
            }
            $query = $this->db->query("
                SELECT account_name AS `name`,
                SUM(journal_item_debit) AS debit_total,
                SUM(journal_item_credit) AS credit_total,
                (SELECT SUM(journal_item_debit) - SUM(journal_item_credit)) AS total
                FROM journals_items
				LEFT JOIN accounts ON journals_items.journal_item_account_id=accounts.account_id
                WHERE journal_item_branch_id=$session_branch_id $where_and
                AND journal_item_account_id IN (
                	SELECT account_id FROM accounts WHERE account_branch_id=$session_branch_id AND account_group_sub=3
                )
                GROUP BY journal_item_account_id
            ");

            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->result_array();
            // $return->result = array(
            //     array('index'=>'Kas Gereja','value'=> 1000000),
            //     array('index'=>'Bank BCA','value'=> 2500000),
            //     array('index'=>'Bank BNI','value'=> 540000),
            // );
        }else if($request=="fo-cece-date-due"){
            // $query = $this->db->query("
            //     SELECT
            //     account_id, account_code, account_name, account_group, account_group_sub,
            //       DATE_FORMAT(journal_item_date, '%d %b') AS journal_item_date_short,
            //       DATE_FORMAT(journal_item_date, '%d %M %Y') AS journal_item_date_format,
            //       fn_time_ago(MAX(journal_item_date) OVER(PARTITION BY journal_item_date)) AS last_insert, 
            //       CASE WHEN account_group = 1 THEN SUM(journal_item_debit)-SUM(journal_item_credit)
            //       WHEN account_group = 5 THEN SUM(journal_item_debit)-SUM(journal_item_credit)
            //       ELSE SUM(journal_item_credit)-SUM(journal_item_debit) END AS balance
            //       -- FORMAT(SUM(trans_total), 0, 'de_DE') AS total_sold_label
            //     FROM
            //       journals_items
            //       LEFT JOIN accounts
            //         ON journal_item_account_id = account_id
            //     WHERE journal_item_branch_id = $session_branch_id AND account_locked=1 AND account_group BETWEEN 1 AND 2
            //       -- AND YEAR (journal_item_date) = YEAR(CURDATE())
            //       -- AND MONTH (journal_item_date) = MONTH(CURDATE())
            //     GROUP BY `journal_item_account_id`
            //     ORDER BY balance DESC
            //     LIMIT 6
            // ");
            $query = $this->db->query("
                SELECT order_id, order_number, order_date, order_total, order_total_paid, order_contact_name, order_contact_phone, branch_name, ref_name, product_name,
                orders_items.order_item_id, orders_items.order_item_branch_id, orders_items.order_item_type, orders_items.order_item_type_name, orders_items.order_item_order_id, orders_items.order_item_qty, orders_items.order_item_price, orders_items.order_item_total, orders_items.order_item_date_created, orders_items.order_item_flag, orders_items.order_item_order_session, orders_items.order_item_note, 
                orders_items.order_item_type_2, orders_items.order_item_ref_id, orders_items.order_item_ref_price_sort, orders_items.order_item_ref_price_id, orders_items.order_item_start_date, orders_items.order_item_end_date, orders_items.order_item_flag_checkin, orders_items.order_item_product_id,
                FORMAT(order_item_price, 0, 'de_DE') AS total_sold_label,
                order_item_expired_day, DATEDIFF(order_item_end_date,NOW()) AS order_item_expired_day_2
                FROM orders_items
                LEFT JOIN orders ON order_item_order_id = order_id
                LEFT JOIN branchs ON order_item_branch_id = branch_id
                LEFT JOIN `references` ON order_item_ref_id = ref_id
                LEFT JOIN products ON order_item_product_id = product_id
                WHERE order_item_flag_checkin = 1 AND order_item_type_2 = 'Bulanan'
                AND DATEDIFF(order_item_end_date,NOW()) > -14
                ORDER BY order_item_expired_day ASC LIMIT 10
            ");            
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->result_array();
        }else if($request=="load-room"){
            $branch = !empty($this->input->post('branch')) ? $this->input->post('branch') : null;
            $ref = !empty($this->input->post('ref')) ? $this->input->post('ref') : 0; 
            $where_ref = '';           
            if($ref > 0){
                $where_ref = "AND ref_id=$ref";
            }
            $pre = "
                SELECT product_id, product_branch_id, product_category_id, product_ref_id, product_name, ref_id, ref_name, branch_id, branch_name,
                c.order_item_id, c.order_item_order_id, c.order_item_product_id, c.order_item_start_date, c.order_item_end_date, 
                c.order_item_flag_checkin, c.order_item_checkin_date, c.order_item_checkout_date,
                c.order_id, c.order_session, c.order_contact_name, c.order_contact_phone 
                FROM products
                LEFT JOIN `references` ON product_ref_id=ref_id
                LEFT JOIN branchs ON product_branch_id=branch_id
                LEFT OUTER JOIN (
                    SELECT order_item_id, order_item_order_id, order_item_product_id, order_item_start_date, order_item_end_date, 
                    order_item_flag_checkin, order_item_checkin_date, order_item_checkout_date, order_id, order_session, order_contact_name, order_contact_phone 
                    FROM orders_items 
                    LEFT JOIN orders ON order_item_order_id=order_id
                    WHERE order_item_flag_checkin = 1
                    GROUP BY order_item_product_id ORDER BY order_item_id DESC
                ) AS c ON product_id=c.order_item_product_id
                WHERE product_type=2 AND product_branch_id=$branch $where_ref";
            // var_dump($pre);die;
            $query = $this->db->query($pre);            
            $return->status=1;
            $return->message='Loaded';
            $return->result= $query->result_array();
        }
        $return->action = $request;
        echo json_encode($return);
    }
    function get_payment_method($id){
        // $query = $this->db->query("
        //         SELECT
        //         COUNT(trans_paid_type)
        //         FROM trans
        //         WHERE trans_branch_id=$session_branch_id AND trans_paid_type=".$id."
        //         AND trans_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 00:00:00')
        //         AND trans_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
        //    ");
        //     $return->status=1;
        //     $return->message='Loaded';
        //     $return->result= $query->result_array();
    }
    function test($request,$type,$limit,$start,$end){
        // $session = $this->session->userdata();
        // $session_branch_id = $session['user_data']['branch']['id'];
        // $session_user_id = $session['user_data']['user_id'];

        // $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        // $return->result = '';

        // if($request=="finance-list-top-contact"){ // total selling this month
        //     // $type = $this->input->post('type');
        //     // $limit = $this->input->post('limit');
        //     $start = !empty($this->input->post('start')) ? date("Y-m-d", strtotime($this->input->post('start')))." 00:00:00" : false;
        //     $end = !empty($this->input->post('end')) ? date("Y-m-d", strtotime($this->input->post('end')))." 23:59:59" : false;

        //     // $where_and = "
        //     //     AND journal_item_date > CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-01'),' 00:00:00')
        //     //     AND journal_item_date < CONCAT(DATE_FORMAT(NOW() ,'%Y-%m-%d'),' 23:59:59')
        //     // ";
        //     // if(($start) and ($end)){
        //         $where_and = "
        //             AND journal_date > '$start'
        //             AND journal_date < '$end'
        //         ";
        //     // }

        //     // var_dump($prepare);die;
        //     $query = $this->db->query($prepare);

        //     $return->status=1;
        //     $return->message='Loaded';
        //     $return->result= $query->result_array();
        //     // $return->result = array(
        //     //     array('index'=>'Hedi Winarto','value'=> 10000),
        //     //     array('index'=>'Sinta Kusumadewi','value'=> 20000),
        //     //     array('index'=>'Joe Witaya','value'=> 30000)
        //     // );
        // }

        // $return->action = $request;
        // echo json_encode($return);
    }
}
?>