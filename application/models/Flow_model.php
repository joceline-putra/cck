<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Flow_model extends CI_Model
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

    function get_all_flow($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('flow_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('flows')->result_array();
    }
    
    function get_all_flow_count($params){
        $this->db->from('flows');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new flow */
    function add_flow($params)
    {
        $this->db->insert('flows',$params);
        return $this->db->insert_id();
    }
    
    /* function to get flow by id */
    function get_flow($id)
    {
        return $this->db->get_where('flows',array('flow_id'=>$id))->row_array();
    }
    function get_flow_custom($where)
    {
        return $this->db->get_where('flows',$where)->row_array();
    }

    /* function to update flow */
    function update_flow($id,$params)
    {
        $this->db->where('flow_id',$id);
        return $this->db->update('flows',$params);
    }
    function update_flow_custom($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('flows',$params);
    }
    
    /* function to delete flow */
    function delete_flow($id){
        return $this->db->delete('flows',array('flow_id'=>$id));
    }
    function delete_flow_custom($where){
        return $this->db->delete('flows',$where);
    }

    /* function to check data exists flow */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('flows');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}


?>