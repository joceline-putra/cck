<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Type_model extends CI_Model{
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

    function get_all_type($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('type_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('types')->result_array();
    }
    /* function get_all_type($search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        $prepare = "CALL sp_types_data('$search',$limit,$start,'$order','$dir')";
        $query = $this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        $query->free_result();

        // log_message("debug", print_r($prepare, true));
        return $result;
    } */  
    function get_all_type_count($params,$search){
        $this->db->from('types');   
        $this->set_params($params);           
        $this->set_search($search);         
        return $this->db->count_all_results();
    }    
    function get_all_type_paid($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('paid_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('types_paids')->result_array();
    }     
    function get_all_type_paid_count($params,$search){
        $this->db->from('types_paids');   
        $this->set_params($params);           
        $this->set_search($search);         
        return $this->db->count_all_results();
    }           
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new type */
    function add_type($params){
        $this->db->insert('types',$params);
        return $this->db->insert_id();
    }
    
    /* function to get type by id */
    function get_type($id){
        $this->set_join();
        return $this->db->get_where('types',array('type_id'=>$id))->row_array();
    }
    function get_type_custom($where){
        $this->set_join();
        return $this->db->get_where('types',$where)->row_array();
    }

    function get_type_paid($id){
        $this->set_join();
        return $this->db->get_where('types_paids',array('paid_id'=>$id))->row_array();
    }
    function get_type_paid_custom($where){
        $this->set_join();
        return $this->db->get_where('types_paids',$where)->row_array();
    }

    /* function to update type */
    function update_type($id,$params){
        $this->db->where('type_id',$id);
        return $this->db->update('types',$params);
    }
    function update_type_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('types',$params);
    }
    
    /* function to delete type */
    function delete_type($id){
        return $this->db->delete('types',array('type_id'=>$id));
    }
    function delete_type_custom($where){
        return $this->db->delete('types',$where);
    }         
}
?>