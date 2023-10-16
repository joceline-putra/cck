<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Balance_model extends CI_Model
{
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

    function set_join() {
        $this->db->join('users','balance_user_session=user_session','left');
    }

    function get_all_balance($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*, DATE_FORMAT(`balance_date`,'%d-%b-%y, %H:%i') AS balance_date_format,");
        $this->db->select("fn_time_ago(balance_date) AS balance_date_time_ago"); 
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('balance_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('balances')->result_array();
    }
    
    function get_all_balance_count($params){
        $this->db->from('balances');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new balance */
    function add_balance($params)
    {
        $this->db->insert('balances',$params);
        return $this->db->insert_id();
    }
    
    /* function to get balance by id */
    function get_balance($id)
    {
        $this->db->join('users','balance_user_session=user_session','left');
        return $this->db->get_where('balances',array('balance_id'=>$id))->row_array();
    }
    function get_balance_custom($where)
    {
        $this->db->join('users','balance_user_session=user_session','left');        
        return $this->db->get_where('balances',$where)->row_array();
    }

    /* function to update balance */
    function update_balance($id,$params)
    {
        $this->db->where('balance_id',$id);
        return $this->db->update('balances',$params);
    }
    function update_balance_custom($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('balances',$params);
    }
    
    /* function to delete balance */
    function delete_balance($id){
        return $this->db->delete('balances',array('balance_id'=>$id));
    }
    function delete_balance_custom($where){
        return $this->db->delete('balances',$where);
    }

	/* function to get current balance by id */
    /* function get_current_balance($id)
    {
    	$this->db->select('SUM(balance_debit), SUM(balance_credit)');
        return $this->db->get_where('balances',array('balance_id'=>$id))->row_array();
    } */
    function get_current_balance_custom($where)
    {
    	$this->db->select('user_session, user_id, user_email_1, user_phone_1')
    		->select('IFNULL(SUM(balance_debit),0) AS debit, IFNULL(SUM(balance_credit),0) AS credit') 
    		->select('IFNULL(SUM(balance_debit),0) - IFNULL(SUM(balance_credit),0) AS balance')
    		->join('users','balance_user_session=user_session','left')
    		->where($where);
        return $this->db->get('balances')->row_array();
    }

    /* function to check data exists balance */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('balances');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>