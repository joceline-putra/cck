<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Mutation_model extends CI_Model
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
        $this->db->join('banks','mutation_bank_session=bank_session','left');
    }

    function get_all_mutation($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->db->select("fn_time_ago(mutation_date) AS mutation_date_time_ago");        
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('mutation_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('mutations')->result_array();
    }
    
    function get_all_mutation_count($params){
        $this->db->from('mutations');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new mutation */
    function add_mutation($params)
    {
        $this->db->insert('mutations',$params);
        return $this->db->insert_id();
    }
    
    /* function to get mutation by id */
    function get_mutation($id)
    {
    	$this->db->join('banks','mutation_bank_session=bank_session','left');
        return $this->db->get_where('mutations',array('mutation_id'=>$id))->row_array();
    }
    function get_mutation_custom($where)
    {
    	$this->db->join('banks','mutation_bank_session=bank_session','left');
        return $this->db->get_where('mutations',$where)->row_array();
    }

    /* function to update mutation */
    function update_mutation($id,$params)
    {
        $this->db->where('mutation_id',$id);
        return $this->db->update('mutations',$params);
    }
    function update_mutation_custom($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('mutations',$params);
    }
    
    /* function to delete mutation */
    function delete_mutation($id){
        return $this->db->delete('mutations',array('mutation_id'=>$id));
    }
    function delete_mutation_custom($where){
        return $this->db->delete('mutations',$where);
    }

    /* function to check data exists mutation */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('mutations');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>