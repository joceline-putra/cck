<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class App_package_model extends CI_Model
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

    function get_all_app_package($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('app_package_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('app_packages')->result_array();
    }
    function get_all_app_package_item($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('item_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('app_packages_items')->result_array();
    }    
    function get_all_app_package_count($params){
        $this->db->from('app_packages');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
    function get_all_app_package_item_count($params){
        $this->db->from('app_packages_items');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new app_package */
    function add_app_package($params){
        $this->db->insert('app_packages',$params);
        return $this->db->insert_id();
    }
    
    /* function to get app_package by id */
    function get_app_package($id){
        return $this->db->get_where('app_packages',array('app_package_id'=>$id))->row_array();
    }
    function get_app_package_custom($where){
        return $this->db->get_where('app_packagess',$where)->row_array();
    }    
    function get_app_package_item($id){
        return $this->db->get_where('app_packages_items',array('item_app_package_id'=>$id))->row_array();
    }
    function get_app_package_item_custom($where){
        return $this->db->get_where('app_packages_items',$where)->row_array();
    }

    /* function to update app_package */
    function update_app_package($id,$params){
        $this->db->where('app_package_id',$id);
        return $this->db->update('app_packages',$params);
    }
    
    /* function to delete app_package */
    function delete_app_package($id){
        return $this->db->delete('app_packages',array('app_package_id'=>$id));
    }

    /* function to check data exists app_package */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('app_packages');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>