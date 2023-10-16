<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Account_model extends CI_Model
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
        /* $this->db->join('','','left'); */
    }

    function get_all_account($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('account_id', "asc");
        }

        if (intval($limit) > 0) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('accounts')->result_array();
    }
    function get_all_account_group($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('group_id', "asc");
        }

        if (intval($limit) > 0) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('accounts_groups')->result_array();
    }    
    function get_all_account_count($params,$search){
        $this->db->from('accounts');   
        $this->set_params($params);   
        $this->set_search($search);         
        return $this->db->count_all_results();
    }  
    function get_all_account_group_count($params,$search){
        $this->db->from('accounts_groups');   
        $this->set_params($params);   
        $this->set_search($search);         
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new account */
    function add_account($params)
    {
        $this->db->insert('accounts',$params);
        return $this->db->insert_id();
    }
    
    /* function to get account by id */
    function get_account($id)
    {
        return $this->db->get_where('accounts',array('account_id'=>$id))->row_array();
    }
    function get_account_group($id)
    {
        return $this->db->get_where('accounts_groups',array('group_id'=>$id))->row_array();
    }
    /* function to update account */
    function update_account($id,$params)
    {
        $this->db->where('account_id',$id);
        return $this->db->update('accounts',$params);
    }
    
    /* function to delete account */
    function delete_account($id){
        return $this->db->delete('accounts',array('account_id'=>$id));
    }

    /* function to check data exists account */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('accounts');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}



?>