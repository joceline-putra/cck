<?php
/* 
    @Author: Yoceline Witaya 
*/ 
class Vendor_model extends CI_Model{
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
    }

    function set_join_log(){
        /* $this->db->join('','','left'); */
    }

    function set_select(){
        $this->db->select("*");
    }

    function set_select_log(){
        $this->db->select("*");
    }

    function get_all_vendor($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('vendor_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('vendors')->result_array();
    }  
    function get_all_vendor_count($params,$search){
        $this->db->from('vendors');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }
    function get_all_vendor_log($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_log();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_log();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('log_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('vendors_logs')->result_array();
    }  
    function get_all_vendor_log_count($params,$search){
        $this->db->from('vendors_logs');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }    

    /* 
        ================
        CRUD Vendor
        ================
    */        
    
    /* function to add new vendor */
    function add_vendor($params){
        $this->db->insert('vendors',$params);
        return $this->db->insert_id();
    }
    
    /* function to get vendor by id */
    function get_vendor($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('vendors',array('vendor_id'=>$id))->row_array();
    }
    function get_vendor_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('vendors',$where)->row_array();
    }
    function get_vendor_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('vendors',$where)->result_array();
    }

    /* function to update vendor */
    function update_vendor($id,$params){
        $this->db->where('vendor_id',$id);
        return $this->db->update('vendors',$params);
    }
    function update_vendor_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('vendors',$params);
    }

    /* function to delete vendor */
    function delete_vendor($id){
        return $this->db->delete('vendors',array('vendor_id'=>$id));
    }
    function delete_vendor_custom($where){
        return $this->db->delete('vendors',$where);
    }
    
    /* 
        ================
        CRUD Vendor ITEM
        ================
    */
    
    /* function to add new vendor items */
    function add_vendor_log($params){
        $this->db->insert('vendors_logs',$params);
        return $this->db->insert_id();
    }
    
    /* function to get vendor items by id */
    function get_vendor_log($id){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('vendors_logs',array('log_id'=>$id))->row_array();
    }
    function get_vendor_log_custom($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('vendors_logs',$where)->row_array();
    }
    function get_vendor_log_custom_result($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('vendors_logs',$where)->result_array();
    }

    /* function to update vendor items */
    function update_vendor_log($id,$params){
        $this->db->where('log_id',$id);
        return $this->db->update('vendors_logs',$params);
    }
    function update_vendor_log_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('vendors_logs',$params);
    }    
    
    /* function to delete vendor items */
    function delete_vendor_log($id){
        return $this->db->delete('vendors_logs',array('log_id'=>$id));
    }
    function delete_vendor_log_custom($where){
        return $this->db->delete('vendors_logs',$where);
    }
}
?>