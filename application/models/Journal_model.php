<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Journal_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }    
    function set_params($params){
        if ($params) {
            foreach ($params as $k => $v) {
                $this->db->where($k, $v);
            }
        }
    }
    function set_search($search){
        if ($search) {
            $n = 0;
            $this->db->group_start();
            foreach ($search as $key => $val) {
                if ($n == 0) {
                    $this->db->like($key, $val);
                } else {
                    $this->db->or_like($key, $val);
                }
                $n++;
            }
            $this->db->group_end();
        }
    }
    function set_join(){
        /* $this->db->join('','','left'); */
        // $this->db->join('references','orders.order_ref_id=references.ref_id');
        $this->db->join('accounts','journals.journal_account_id=accounts.account_id','left');
        $this->db->join('contacts','journals.journal_contact_id=contacts.contact_id','left');
        $this->db->join('users','journals.journal_user_id=users.user_id','left');  
        $this->db->join('branchs','journals.journal_branch_id=branchs.branch_id','left');        
    }
    function set_select_item_report(){
        $this->db->select("journal_id, journal_session, journal_number, journal_date, journal_type, journal_contact_id, journal_total, journal_note");
        $this->db->select("contact_id, contact_type, contact_code, contact_name, contact_address, contact_phone_1, contact_email_1");
        $this->db->select("journal_item_journal_id, journal_item_type, journal_item_type_name, journal_item_group_session, journal_item_debit, journal_item_credit, journal_item_account_id, journal_item_date, journal_item_note");
        $this->db->select("account_id, account_code, account_name, account_group, account_group_sub, account_group_sub_name");
        $this->db->select("DATE_FORMAT(`journal_item_date`,'%d-%b-%Y, %H:%i') AS journal_item_date_format");
        $this->db->join('journals','journals_items.journal_item_journal_id=journals.journal_id','left');
        $this->db->join('accounts','journals_items.journal_item_account_id=accounts.account_id','left'); 
        $this->db->join('contacts','journals.journal_contact_id=contacts.contact_id','left'); 
        // $this->db->join('users','journals_items.journal_item_user_id=users.user_id','left');  
    }
    /* Journals */
    function get_all_journal($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("journals.*, contacts.*, accounts.*, user_username, user_id, branch_id, branch_name, journal_id AS journal_id, DATE_FORMAT(`journal_date`,'%d-%b-%y, %H:%i') AS journal_date_format, users.user_username, journal_total");
        // $this->db->select("(SELECT SUM(journal_item_debit) FROM journals_items WHERE journal_item_journal_id=journal_id) AS journal_total");        
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('journal_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('journals')->result_array();
    }
    function get_all_journal_nojoin($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");        
        $this->set_params($params);
        $this->set_search($search);
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('journal_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('journals')->result_array();
    }      
    function get_all_journal_count($params,$search){
        $this->db->from('journals');   
        $this->set_join();
        $this->set_params($params);            
        $this->set_search($search);
        return $this->db->count_all_results();
    }
    function get_all_journal_nojoin_count($params,$search){
        $this->db->from('journals');
        $this->set_params($params);            
        $this->set_search($search);
        return $this->db->count_all_results();
    }    
    /* Journals Items */
    function get_all_journal_item($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*, DATE_FORMAT(`journal_item_date`,'%d-%b-%Y, %H:%i') AS journal_item_date");
        $this->set_params($params);
        $this->set_search($search);
        $this->db->join('journals','journals_items.journal_item_journal_id=journals.journal_id','left');
        $this->db->join('accounts','journals_items.journal_item_account_id=accounts.account_id','left');  
        $this->db->join('users','journals_items.journal_item_user_id=users.user_id','left');  
        $this->db->join('branchs','journals_items.journal_item_branch_id=branchs.branch_id');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('journal_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('journals_items')->result_array();
    }
    function get_all_journal_item_nojoin($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");        
        $this->set_params($params);
        $this->set_search($search);
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('journal_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('journals_items')->result_array();
    }      
    function get_all_journal_item_count($params){
        $this->db->from('journals_items');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }
    function get_all_journal_item_nojoin_count($params,$search){
        $this->db->from('journals_items');
        $this->set_params($params);            
        $this->set_search($search);
        return $this->db->count_all_results();
    }        
    function get_all_journal_item_report($where_type_in, $params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_item_report();
        $this->db->where_in('journal_item_type',$where_type_in);
        $this->set_params($params);
        $this->set_search($search);        
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('journal_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('journals_items')->result_array();
    }  
    function get_all_journal_item_report_count($where_type_in, $params,$search){
        $this->set_select_item_report();
        $this->db->where_in('journal_item_type',$where_type_in);        
        $this->set_params($params);
        $this->set_search($search);  
        $this->db->from('journals_items');
        return $this->db->count_all_results();
    }    
    //Custom Load Journal Item GROUP to Journal
    function get_all_journal_item_custom($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("journals.journal_id, journals.journal_number, journals.journal_date, DATE_FORMAT(`journal_date`,'%d-%b-%y, %H:%i') AS journal_date_format, journals.journal_total, 
            journals.journal_note, journals_items.*, users.user_id, users.user_username, accounts.account_name, accounts.account_code, accounts.account_id");
        $this->set_params($params);
        $this->set_search($search);
        $this->db->join('accounts','journals_items.journal_item_account_id=accounts.account_id','left');  
        $this->db->join('users','journals_items.journal_item_user_id=users.user_id','left');  
        $this->db->join('journals','journals_items.journal_item_journal_id=journals.journal_id','left');          

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('journal_item_id', "asc");
        }

        $this->db->group_by('journal_item_journal_id');

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('journals_items')->result_array();
    }  
    function get_all_journal_item_custom_count($params,$search){
        $this->db->from('journals_items');   
        // $this->set_join();         
        $this->set_params($params);            
        $this->set_search($search);    
        $this->db->join('users','journals_items.journal_item_user_id=users.user_id','left');  
        $this->db->join('journals','journals_items.journal_item_journal_id=journals.journal_id','left');              
        $this->db->group_by('journal_item_journal_id');       
        return $this->db->count_all_results();
    }        
    /* 
        ================
        CRUD TRANS
        ================
    */
    /* function to add new journal */
    function add_journal($params){
        $this->db->insert('journals',$params);
        return $this->db->insert_id();
    }
    /* function to get journal by id */
    function get_journal($id){
        $this->set_join();
        return $this->db->get_where('journals',array('journal_id'=>$id))->row_array();
    }
    function get_journal_custom($where){
        $this->set_join();
        return $this->db->get_where('journals',$where)->row_array();
    }
    function get_journal_nojoin_custom($where){
        return $this->db->get_where('journals',$where)->row_array();
    }    
    /* function to update journal */
    function update_journal($id,$params){
        $this->db->where('journal_id',$id);
        return $this->db->update('journals',$params);
    }
    function update_journal_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('journals',$params);
    }    
    /* function to delete journal */
    function delete_journal($id){
        return $this->db->delete('journals',array('journal_id'=>$id));
    }
    /* function to check data exists journal */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('journals');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* 
        ================
        CRUD TRANS ITEM
        ================
    */
    /* function to add new journal items */
    function add_journal_item($params){
        $this->db->insert('journals_items',$params);
        return $this->db->insert_id();
    }
    /* function to get journal items by id */
    function get_journal_item($id){
        return $this->db->get_where('journals_items',array('journal_item_id'=>$id))->row_array();
    }
    function get_journal_item_custom($where){
        $this->db->join('accounts','journal_item_account_id=account_id','left');        
        $this->db->where($where);
        return $this->db->get_where('journals_items',$where)->result_array();        
    }
    function get_journal_item_custom_row($where){
        $this->db->join('accounts','journal_item_account_id=account_id','left');
        $this->db->where($where);
        return $this->db->get_where('journals_items',$where)->row_array();        
    }
    /* function to update journal items */
    function update_journal_item($id,$params){
        $this->db->where('journal_item_id',$id);
        return $this->db->update('journals_items',$params);
    }
    function update_journal_item_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('journals_items',$params);
    }    
    function update_journal_item_by_journal_id($id,$params){
        $this->db->where('journal_item_journal_id',$id);
        return $this->db->update('journals_items',$params);
    }    
    function update_journal_item_for_position_one($id,$params){
        $this->db->where('journal_item_journal_id',$id);
        $this->db->where('journal_item_position',1);
        return $this->db->update('journals_items',$params);
    }
    function update_journal_item_for_position_two($id,$params){
        $this->db->where('journal_item_journal_id',$id);
        $this->db->where('journal_item_position',2);
        return $this->db->update('journals_items',$params);
    }            
    /* function to delete journal items */
    function delete_journal_item($id){
        return $this->db->delete('journals_items',array('journal_item_id'=>$id));
    }
    function delete_journal_item_by_journal_id($id){
        return $this->db->delete('journals_items',array('journal_item_journal_id'=>$id));
    } 
    /* function to check data exists journal_items */
    function check_data_exist_items($params){
        $this->db->where($params);
        $query = $this->db->get('journals_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }  
    /* function to get journal item by id */
    function check_unsaved_journal_item($identity,$user_id){   
        $this->db->select("journals_items.*, accounts.*");
        $this->db->from('journals_items');
        $this->db->join('accounts','journals_items.journal_item_account_id=accounts.account_id','left');
        $this->db->where('journal_item_user_id',$user_id);
        $this->db->where('journal_item_type',$identity);  
        $this->db->where('journal_item_flag',0);
        $this->db->where('journal_item_journal_id',null);        
        $this->db->order_by('journal_item_id','desc');
        // return $this->db->get_where('trans_item',array('trans_item_id_user'=>$user_id))->result_array();
        return $this->db->get()->result_array();
    }
    // Labarugi
    /* backup old version */
        /*function get_pendapatan($start,$end,$type){ 
            if($type == 1){
                $type_name = "Food";
            }else if($type == 2){
                $type_name = "Drink";
            }else if($type == 3){
                $type_name = "Topping";
            }else if($type == 4){
                $type_name = "Paket";
            }else if($type == 5){
                $type_name = "Snack";
            }
            $this->db->select('product_manufacture AS pendapatan, IFNULL(SUM(order_item_total),0) AS saldo');
            $this->db->from('trans');
            $this->db->join('orders','order_trans_id = trans_id','left');
            $this->db->join('order_items','order_item_order_id = order_id','left');
            $this->db->join('products','order_item_product_id = product_id','left');
            $this->db->where('order_flag',1);
            $this->db->where('product_manufacture',$type_name);
            $this->db->where('trans_date >= CONCAT("'.$start.'"," 00:00:00")');
            $this->db->where('trans_date <= CONCAT("'.$end.'", " 23:59:00")');
            return $this->db->get()->row_array();
        }*/
        /*function get_biaya($start,$end,$tipe){
            if($tipe == 1){ // air pdam
                $where = $this->db->where('journal_item_account_id',21);
            }else if($tipe == 2){ // bongkar muat
                $where = $this->db->where('journal_item_account_id',22);
            }else if($tipe == 3){ // lain lain
                $where = $this->db->where('journal_item_account_id',23);
            }else if($tipe == 4){
                $where = $this->db->where('journal_item_account_id',24);
            }
            $this->db->select('account_name,IFNULL(SUM(journal_item_debit), 0) AS biaya');
            $this->db->from('journals_items');
            $this->db->join('accounts','journal_item_account_id = account_id','left');
            $this->db->join('journals','journal_item_journal_id = journal_id','left');
            $this->db->where('journal_item_flag',1);
            $where;
            $this->db->where('journal_date >= CONCAT("'.$start.'"," 00:00:00")');
            $this->db->where('journal_date <= CONCAT("'.$end.'", " 23:59:00")');
            return $this->db->get()->row_array();
        }*/
    /* end of backup old version */

    function get_pendapatan($start,$end){
        $this->db->select('product_manufacture AS product, IFNULL(SUM(order_item_total),0) AS saldo');
        $this->db->from('trans');
        $this->db->join('orders','order_trans_id = trans_id','left');
        $this->db->join('orders_items','order_item_order_id = order_id','left');
        $this->db->join('products','order_item_product_id = product_id','left');
        $this->db->where('order_flag',1);
        $this->db->where('trans_date >= CONCAT("'.$start.'"," 00:00:00")');
        $this->db->where('trans_date <= CONCAT("'.$end.'", " 23:59:00")');
        $this->db->group_by('`product_manufacture`');
        return $this->db->get()->result_array();
    }
    function get_payment_method($start,$end,$type){ 
        $this->db->select('IFNULL(SUM(trans_total),0) AS total');
        $this->db->from('trans');
        $this->db->where('trans_paid_type',$type);
        $this->db->where('trans_date >= CONCAT("'.$start.'"," 00:00:00")');
        $this->db->where('trans_date <= CONCAT("'.$end.'", " 23:59:00")');
        return $this->db->get()->row_array();
    }
    function get_change($start,$end){ 
        $this->db->select('IFNULL(SUM(trans_change),0) AS total');
        $this->db->from('trans');
        $this->db->where('trans_paid_type',1);
        $this->db->where('trans_date >= CONCAT("'.$start.'"," 00:00:00")');
        $this->db->where('trans_date <= CONCAT("'.$end.'", " 23:59:00")');
        return $this->db->get()->row_array();
    }    
    function get_trans_status($start,$end,$type){
        if($type == 1){
            $select = $this->db->select('IFNULL(SUM(order_item_total),0) AS total');
            $where = $this->db->where('order_ref_id',458)
                ->where('order_date >= CONCAT("'.$start.'"," 00:00:00")')
                ->where('order_date <= CONCAT("'.$end.'", " 23:59:59")');            
        }else if($type == 2){
            $select = $this->db->select('IFNULL(SUM(order_item_total),0) AS total');
            $where = $this->db->where('order_ref_id!=458')
                ->where('order_date >= CONCAT("'.$start.'"," 00:00:00")')
                ->where('order_date <= CONCAT("'.$end.'", " 23:59:59")');
        }
        $select;
        $this->db->from('trans');
        $this->db->join('orders','order_trans_id = trans_id','left');
        $this->db->join('orders_items','order_item_order_id = order_id','left');
        $this->db->where('order_flag',1);
        $where;
        return $this->db->get()->row_array();
    }
    function get_pembelian($start,$end){ 
        $this->db->select('product_manufacture, IFNULL(SUM(order_item_total),0) AS total');
        $this->db->from('orders');
        $this->db->join('orders_items','order_item_order_id = order_id','left');
        $this->db->join('products','order_item_product_id = product_id','left');
        $this->db->where('order_flag',1);
        $this->db->where('order_item_type',1);
        $this->db->where('order_date >= CONCAT("'.$start.'"," 00:00:00")');
        $this->db->where('order_date <= CONCAT("'.$end.'", " 23:59:00")');
        return $this->db->get()->row_array();
    }
    function get_biaya($start,$end){
        $this->db->select('account_name,IFNULL(SUM(journal_item_debit), 0) AS biaya');
        $this->db->from('journals_items');
        $this->db->join('accounts','journal_item_account_id = account_id','left');
        $this->db->join('journals','journal_item_journal_id = journal_id','left');
        $this->db->where('journal_item_flag',1);
        $this->db->where('account_parent_id',19);
        $this->db->where('journal_item_date >= CONCAT("'.$start.'"," 00:00:00")');
        $this->db->where('journal_item_date <= CONCAT("'.$end.'", " 23:59:00")');
        $this->db->group_by('`journal_item_account_id`');
        return $this->db->get()->result_array();
    }
    function get_modal($start,$end){
        $this->db->select('IFNULL(SUM(journal_item_debit), 0) AS modal');
        $this->db->from('journals_items');
        // $this->db->join('accounts','journal_item_account_id = account_id','left');
        // $this->db->join('journals','journal_item_journal_id = journal_id','left');
        // $this->db->where('journal_item_flag',1);
        $this->db->where('journal_item_account_id',3);
        // $this->db->or_where('journal_item_account_id',11);        
        $this->db->where('journal_item_date >= CONCAT("'.$start.'"," 00:00:00")');
        $this->db->where('journal_item_date <= CONCAT("'.$end.'", " 23:59:00")');
        return $this->db->get()->row_array();
    }    

    //By Joe : Show Hutang & Piutang
    function get_all_account_payable($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        $session_branch = $params['trans_branch_id'];

        // $subquery = "SELECT IFNULL(SUM(journal_item_debit),0) 
        //               FROM journals_items 
        //               WHERE journal_item_trans_id=trans_id AND journal_item_type=10 AND journal_item_branch_id=$session_branch
        // ";
        $this->db->select("trans_id, trans_session, trans_number, trans_date, trans_date_due, trans_contact_id, trans_paid, trans_flag, trans_total, DATE_FORMAT(`trans_date`,'%d-%b-%y, %H:%i') AS trans_date_format, 
            DATE_FORMAT(`trans_date_due`,'%d-%b-%y, %H:%i') AS trans_date_due_format,
            trans_note");
        $this->db->select("trans_total_paid");
        $this->db->select("contact_name");
        // $this->db->select("($subquery) AS total_paid");
        $this->set_params($params);
        $this->set_search($search);
        // $this->db->join('accounts','journals_items.journal_item_account_id=accounts.account_id','left');  
        // $this->db->join('users','journals_items.journal_item_user_id=users.user_id','left');  
        $this->db->join('contacts','trans.trans_contact_id=contacts.contact_id','left');  
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_date', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans')->result_array();
    }
    function get_all_account_receivable($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        $session_branch = $params['trans_branch_id'];

        // $subquery = "SELECT IFNULL(SUM(journal_item_credit),0) 
        //               FROM journals_items 
        //               WHERE journal_item_trans_id=trans_id AND journal_item_type=11 AND journal_item_branch_id=$session_branch
        // ";
        $this->db->select("trans_id, trans_session, trans_number, trans_date, trans_date_due, trans_contact_id, trans_paid, trans_flag, trans_total, DATE_FORMAT(`trans_date`,'%d-%b-%y, %H:%i') AS trans_date_format, 
            DATE_FORMAT(`trans_date_due`,'%d-%b-%y, %H:%i') AS trans_date_due_format,
            trans_note");
        $this->db->select("trans_total_paid");
        $this->db->select("contact_name");        
        // $this->db->select("($subquery) AS total_paid");
        $this->set_params($params);
        $this->set_search($search);
        // $this->db->join('accounts','journals_items.journal_item_account_id=accounts.account_id','left');  
        // $this->db->join('users','journals_items.journal_item_user_id=users.user_id','left');  
        $this->db->join('contacts','trans.trans_contact_id=contacts.contact_id','left');  
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_date', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans')->result_array();
    }    

    function get_journal_item_debit_total($journal_id,$params){
        // $this->db->select_sum('trans_item_in_price');
        $this->db->select_sum('journal_item_debit');
        $this->db->from('journals_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);        
        return $this->db->get()->row_array();
    }    
    function get_journal_item_credit_total($journal_id,$params){
        // $this->db->select_sum('trans_item_in_price');
        $this->db->select_sum('journal_item_credit');
        $this->db->from('journals_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);        
        return $this->db->get()->row_array();
    }   
    function get_journal_item_debit_sum($params){
        $this->db->select('IFNULL(SUM(journal_item_debit),0) AS journal_item_debit');                
        $this->db->from('journals_items');
        $this->db->where($params);           
        return $this->db->get()->row_array();
    }
    function get_journal_item_credit_sum($params){
        $this->db->select('IFNULL(SUM(journal_item_credit),0) AS journal_item_credit');                
        $this->db->from('journals_items');
        $this->db->where($params);           
        return $this->db->get()->row_array();
    }    
    function get_journal_item_debit_credit_sum($params){ //Down Payment Supplier
        $this->db->select('IFNULL(SUM(journal_item_debit),0) AS journal_item_debit');
        $this->db->select('IFNULL(SUM(journal_item_credit),0) AS journal_item_credit');
        $this->db->select('IFNULL(SUM(journal_item_debit-journal_item_credit),0) AS journal_item_balance');                
        $this->db->from('journals_items');
        $this->db->where($params);           
        $this->db->group_by('journal_item_ref');        
        return $this->db->get()->row_array();
    }
    function get_journal_item_credit_debit_sum($params){ //Down Payment Customer
        $this->db->select('IFNULL(SUM(journal_item_debit),0) AS journal_item_debit');
        $this->db->select('IFNULL(SUM(journal_item_credit),0) AS journal_item_credit');
        $this->db->select('IFNULL(SUM(journal_item_credit-journal_item_debit),0) AS journal_item_balance');                
        $this->db->from('journals_items');
        $this->db->where($params);           
        $this->db->group_by('journal_item_ref');        
        return $this->db->get()->row_array();
    }   
    function get_all_booking_paid($params){
        $this->db->select('order_paid, IFNULL(SUM(order_total),0) AS order_total');                
        $this->db->from('orders');
        // $this->db->join('orders','orders_paids.paid_order_id=orders.order_id','left');
        $this->db->where($params);           
        // $this->db->group_by('order_paid');        
        return $this->db->get()->result_array();
    }  
    function get_all_resto_paid($params){
        $this->db->select('trans_paid, IFNULL(SUM(trans_total),0) AS trans_total');                
        $this->db->from('trans');
        // $this->db->join('orders','orders_paids.paid_order_id=orders.order_id','left');
        $this->db->where($params);           
        // $this->db->group_by('order_paid');        
        return $this->db->get()->result_array();
    }      
    function get_all_sum_cost($params){
        $this->db->select('IFNULL(SUM(journal_item_debit),0) AS journal_item_debit');                
        $this->db->from('journals_items');
        $this->db->join('journals','journals_items.journal_item_journal_id=journals.journal_id','left');
        $this->db->where($params);           
        return $this->db->get()->result_array();
    }    
    function get_all_sum_paid($params){
        $this->db->select('paid_payment_method, IFNULL(SUM(paid_total),0) AS paid_total');                
        $this->db->from('orders_paids');
        $this->db->join('orders','orders_paids.paid_order_id=orders.order_id','left');
        $this->db->where($params);           
        $this->db->group_by('paid_payment_method');        
        return $this->db->get()->result_array();
    }        
}
?>