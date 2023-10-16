<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class App_branch_billing_model extends CI_Model
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

    function get_all_app_branch_billing($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('app_branch_billing_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('app_branch_billings')->result_array();
    }
    
    function get_all_app_branch_billing_count($params){
        $this->db->from('app_branch_billings');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new app_branch_billing */
    function add_app_branch_billing($params)
    {
        $this->db->insert('app_branch_billings',$params);
        return $this->db->insert_id();
    }
    
    /* function to get app_branch_billing by id */
    function get_app_branch_billing($id)
    {
        return $this->db->get_where('app_branch_billings',array('app_branch_billing_id'=>$id))->row_array();
    }

    /* function to update app_branch_billing */
    function update_app_branch_billing($id,$params)
    {
        $this->db->where('app_branch_billing_id',$id);
        return $this->db->update('app_branch_billings',$params);
    }
    
    /* function to delete app_branch_billing */
    function delete_app_branch_billing($id){
        return $this->db->delete('app_branch_billings',array('app_branch_billing_id'=>$id));
    }

    /* function to check data exists app_branch_billing */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('app_branch_billings');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>