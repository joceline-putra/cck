<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Usre_balance_model extends CI_Model
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

    function get_all_user_balance($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('user_balance_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('users_balances')->result_array();
    }
    
    function get_all_user_balance_count($params){
        $this->db->from('users_balances');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new user_balance */
    function add_user_balance($params)
    {
        $this->db->insert('users_balances',$params);
        return $this->db->insert_id();
    }
    
    /* function to get user_balance by id */
    function get_user_balance($id)
    {
        return $this->db->get_where('users_balances',array('user_balance_id'=>$id))->row_array();
    }

    /* function to update user_balance */
    function update_user_balance($id,$params)
    {
        $this->db->where('user_balance_id',$id);
        return $this->db->update('users_balances',$params);
    }
    
    /* function to delete user_balance */
    function delete_user_balance($id){
        return $this->db->delete('users_balances',array('user_balance_id'=>$id));
    }

    /* function to check data exists user_balance */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('users_balances');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}



?>