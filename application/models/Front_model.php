<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Front_model extends CI_Model{
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

    function set_join_item(){
        /* $this->db->join('','','left'); */
    }

    function set_select(){
        $this->db->select("*");
    }

    function set_select_item(){
        $this->db->select("*");
    }

    function get_all_booking($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('order_did', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('orders')->result_array();
    }  
    function get_all_order_dcount($params,$search){
        $this->db->from('orders');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }
    function get_all_order_ditem($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_item();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_item();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('order_ditem_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('order_ditems')->result_array();
    }  
    function get_all_order_ditem_count($params,$search){
        $this->db->from('order_ditems');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }    

    /* 
        ================
        CRUD Front
        ================
    */        
    
    /* function to add new booking */
    function add_booking($params){
        $this->db->insert('orders',$params);
        return $this->db->insert_id();
    }
    
    /* function to get booking by id */
    function get_booking($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('orders',array('order_did'=>$id))->row_array();
    }
    function get_order_dcustom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('orders',$where)->row_array();
    }
    function get_order_dcustom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('orders',$where)->result_array();
    }

    /* function to update booking */
    function update_booking($id,$params){
        $this->db->where('order_did',$id);
        return $this->db->update('orders',$params);
    }
    function update_order_dcustom($where,$params){
        $this->db->where($where);
        return $this->db->update('orders',$params);
    }

    /* function to delete booking */
    function delete_booking($id){
        return $this->db->delete('orders',array('order_did'=>$id));
    }
    function delete_order_dcustom($where){
        return $this->db->delete('orders',$where);
    }

    /* function to check data exists booking */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('orders');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists booking of two condition */
    function check_data_exist_two_condition2($param_1,$param_2){
        // if(strlen($session) > 2){ //When update data
            // $this->db->where('order_dsession !=',$session);
            $this->db->where('(`order_dcolumn_1="'.$param_2.'" OR `order_dcolumn_2`="'.$param_2.'")');
        // }else{ //When create data
            $this->db->where($param_1);
        // }

        $query = $this->db->get('orders');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    function check_data_exist_two_condition($where_not_in,$where_exist){
        if ($where_not_in) {
            foreach ($where_not_in as $k => $v) {
                $this->db->where($k.' !=', $v);
            }
        }
        if ($where_exist) {
            $n = 0;
            $this->db->group_start();
            foreach ($where_exist as $key => $val) {
                if ($n == 0) {
                    $this->db->where($key, $val);
                } else {
                    $this->db->where($key, $val);
                }
                $n++;
            }
            $this->db->group_end();
        }        
        $this->db->limit(1,0);
        $query = $this->db->get('orders');
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    /* 
        ================
        CRUD Front ITEM
        ================
    */
    
    /* function to add new booking items */
    function add_order_ditem($params){
        $this->db->insert('order_ditems',$params);
        return $this->db->insert_id();
    }
    
    /* function to get booking items by id */
    function get_order_ditem($id){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('order_ditems',array('order_ditem_id'=>$id))->row_array();
    }
    function get_order_ditem_custom($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('order_ditems',$where)->row_array();
    }
    function get_order_ditem_custom_result($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('order_ditems',$where)->result_array();
    }

    /* function to update booking items */
    function update_order_ditem($id,$params){
        $this->db->where('order_ditem_id',$id);
        return $this->db->update('order_ditems',$params);
    }
    function update_order_ditem_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('order_ditems',$params);
    }    
    
    /* function to delete booking items */
    function delete_order_ditem($id){
        return $this->db->delete('order_ditems',array('order_ditem_id'=>$id));
    }
    function delete_order_ditem_custom($where){
        return $this->db->delete('order_ditems',$where);
    }

    /* function to check data exists order_ditems */
    function check_data_exist_items($params){
        $this->db->where($params);
        $query = $this->db->get('order_ditems');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists booking of two condition */
    function check_data_exist_items_two_condition($param_1,$param_2,$session){
        if(strlen($session) > 2){ //When update data
            $this->db->where('order_ditem_session !=',$session);
            $this->db->where('(`order_ditem_column_1="'.$param_2.'" OR `order_ditem_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($param_1);
        }

        $query = $this->db->get('order_ditems');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}
?>