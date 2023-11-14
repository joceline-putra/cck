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
            $this->db->order_by('booking_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('bookings')->result_array();
    }  
    function get_all_booking_count($params,$search){
        $this->db->from('bookings');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }
    function get_all_booking_item($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_item();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_item();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('booking_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('booking_items')->result_array();
    }  
    function get_all_booking_item_count($params,$search){
        $this->db->from('booking_items');
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
        $this->db->insert('bookings',$params);
        return $this->db->insert_id();
    }
    
    /* function to get booking by id */
    function get_booking($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('bookings',array('booking_id'=>$id))->row_array();
    }
    function get_booking_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('bookings',$where)->row_array();
    }
    function get_booking_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('bookings',$where)->result_array();
    }

    /* function to update booking */
    function update_booking($id,$params){
        $this->db->where('booking_id',$id);
        return $this->db->update('bookings',$params);
    }
    function update_booking_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('bookings',$params);
    }

    /* function to delete booking */
    function delete_booking($id){
        return $this->db->delete('bookings',array('booking_id'=>$id));
    }
    function delete_booking_custom($where){
        return $this->db->delete('bookings',$where);
    }

    /* function to check data exists booking */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('bookings');
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
            // $this->db->where('booking_session !=',$session);
            $this->db->where('(`booking_column_1="'.$param_2.'" OR `booking_column_2`="'.$param_2.'")');
        // }else{ //When create data
            $this->db->where($param_1);
        // }

        $query = $this->db->get('bookings');
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
        $query = $this->db->get('bookings');
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
    function add_booking_item($params){
        $this->db->insert('booking_items',$params);
        return $this->db->insert_id();
    }
    
    /* function to get booking items by id */
    function get_booking_item($id){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('booking_items',array('booking_item_id'=>$id))->row_array();
    }
    function get_booking_item_custom($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('booking_items',$where)->row_array();
    }
    function get_booking_item_custom_result($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('booking_items',$where)->result_array();
    }

    /* function to update booking items */
    function update_booking_item($id,$params){
        $this->db->where('booking_item_id',$id);
        return $this->db->update('booking_items',$params);
    }
    function update_booking_item_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('booking_items',$params);
    }    
    
    /* function to delete booking items */
    function delete_booking_item($id){
        return $this->db->delete('booking_items',array('booking_item_id'=>$id));
    }
    function delete_booking_item_custom($where){
        return $this->db->delete('booking_items',$where);
    }

    /* function to check data exists booking_items */
    function check_data_exist_items($params){
        $this->db->where($params);
        $query = $this->db->get('booking_items');
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
            $this->db->where('booking_item_session !=',$session);
            $this->db->where('(`booking_item_column_1="'.$param_2.'" OR `booking_item_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($param_1);
        }

        $query = $this->db->get('booking_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}
?>