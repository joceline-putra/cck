<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Account_map_model extends CI_Model
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
        $this->db->join('accounts','accounts_maps.account_map_account_id=accounts.account_id','left');
    }

    function get_all_account_map($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('account_map_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('accounts_maps')->result_array();
    }
    
    function get_all_account_map_count($params){
        $this->db->from('accounts_maps');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }  
    function get_account_map_where($params){
        $this->set_params($params);   
        $this->db->limit(1);
        return $this->db->get('accounts_maps')->row_array();
    }        
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new account_map */
    function add_account_map($params)
    {
        $this->db->insert('accounts_maps',$params);
        return $this->db->insert_id();
    }
    
    /* function to get account_map by id */
    function get_account_map($id)
    {
        return $this->db->get_where('accounts_maps',array('account_map_id'=>$id))->row_array();
    }
    function get_account_map_custom($where)
    {
        $this->set_join();
        return $this->db->get_where('accounts_maps',$where)->row_array();
    }

    /* function to update account_map */
    function update_account_map($id,$params)
    {
        $this->db->where('account_map_id',$id);
        return $this->db->update('accounts_maps',$params);
    }
    
    /* function to delete account_map */
    function delete_account_map($id){
        return $this->db->delete('accounts_maps',array('account_map_id'=>$id));
    }

    /* function to check data exists account_map */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('accounts_maps');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>