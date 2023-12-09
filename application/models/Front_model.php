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
        $this->db->join('orders','order_id=order_item_order_id','left');
        $this->db->join('references','ref_id=order_item_ref_id','left');
        $this->db->join('references_prices','price_id=order_item_ref_price_id','left');
        $this->db->join('products','product_id=order_item_product_id','left');  
        $this->db->join("branchs","order_branch_id=branch_id","left");                
    }
    
    function set_join_paid(){
        $this->db->join('orders','paid_order_id=order_id','left');
        $this->db->join('users','paid_user_id=user_id','left');  
        $this->db->join('branchs','order_branch_id=branch_id','left');                
    }

    function set_select(){
        $this->db->select("orders.order_id, orders.order_branch_id, orders.order_type, orders.order_number, orders.order_date, orders.order_total_dpp, orders.order_with_dp, orders.order_total, orders.order_user_id, orders.order_ref_id, orders.order_date_created, orders.order_flag, orders.order_session, orders.order_contact_code, orders.order_contact_name, orders.order_contact_phone, orders.order_files_count, orders.order_label");
    }

    function set_select_item(){
        $this->db->select("orders.order_id, orders.order_number, orders.order_date, orders.order_session, orders.order_contact_code, orders.order_contact_name, orders.order_contact_phone, orders.order_files_count, orders.order_order_paid_count, orders.order_total, orders.order_paid, orders.order_total_paid, orders.order_flag");        
        $this->db->select("orders_items.order_item_id, orders_items.order_item_branch_id, orders_items.order_item_type, orders_items.order_item_type_name, orders_items.order_item_order_id, orders_items.order_item_qty, orders_items.order_item_price, orders_items.order_item_total, orders_items.order_item_date_created, orders_items.order_item_flag, orders_items.order_item_order_session");
        $this->db->select("orders_items.order_item_type_2, orders_items.order_item_ref_id, orders_items.order_item_ref_price_id, orders_items.order_item_start_date, orders_items.order_item_end_date, orders_items.order_item_flag_checkin, orders_items.order_item_product_id");
        $this->db->select("references.ref_id, references.ref_name");
        $this->db->select("references_prices.price_id, references_prices.price_name");
        $this->db->select("products.product_id, products.product_name, product_flag");  
        $this->db->select("branch_id, branch_name");              
    }
    
    function set_select_paid(){
        $this->db->select("orders_paids.*, fn_time_ago(orders_paids.paid_date) AS time_ago");
        $this->db->select("orders.order_id, orders.order_number, orders.order_total, orders.order_session");      
        $this->db->select("users.user_id, users.user_username, users.user_fullname"); 
        $this->db->select("branchs.branch_id, branchs.branch_name");         
    }

    function get_all_booking($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('order_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('orders')->result_array();
    }  
    function get_all_booking_count($params,$search){
        $this->db->from('orders');
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
            $this->db->order_by('order_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('orders_items')->result_array();
    }  
    function get_all_booking_item_count($params,$search){
        $this->db->from('orders_items');
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_item();        
        return $this->db->count_all_results();
    }    
    function get_all_paid($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_paid();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_paid();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('paid_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('orders_paids')->result_array();
    }  
    function get_all_paid_count($params,$search){
        $this->db->from('orders_paids');
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
        return $this->db->get_where('orders',array('order_id'=>$id))->row_array();
    }
    function get_booking_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('orders',$where)->row_array();
    }
    function get_booking_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('orders',$where)->result_array();
    }

    /* function to update booking */
    function update_booking($id,$params){
        $this->db->where('order_id',$id);
        return $this->db->update('orders',$params);
    }
    function update_booking_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('orders',$params);
    }

    /* function to delete booking */
    function delete_booking($id){
        return $this->db->delete('orders',array('order_id'=>$id));
    }
    function delete_booking_custom($where){
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
    function add_booking_item($params){
        $this->db->insert('orders_items',$params);
        return $this->db->insert_id();
    }
    
    /* function to get booking items by id */
    function get_booking_item($id){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('orders_items',array('order_item_id'=>$id))->row_array();
    }
    function get_booking_item_custom($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('orders_items',$where)->row_array();
    }
    function get_booking_item_custom_result($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('orders_items',$where)->result_array();
    }

    /* function to update booking items */
    function update_booking_item($id,$params){
        $this->db->where('order_item_id',$id);
        return $this->db->update('orders_items',$params);
    }
    function update_booking_item_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('orders_items',$params);
    }    
    
    /* function to delete booking items */
    function delete_booking_item($id){
        return $this->db->delete('orders_items',array('order_item_id'=>$id));
    }
    function delete_booking_item_custom($where){
        return $this->db->delete('orders_items',$where);
    }

    /* function to check data exists order_items */
    function check_data_exist_items($params){
        $this->db->where($params);
        $query = $this->db->get('orders_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists booking of two condition */
    function check_data_exist_item_two_condition($where_not_in,$where_exist){
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
        $query = $this->db->get('orders_items');
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }


    /* function to add new paids */
    function add_paid($params){
        $this->db->insert('orders_paids',$params);
        return $this->db->insert_id();
    }
    
    /* function to get paids by id */
    function get_paid($id){
        $this->set_select_paid();
        $this->set_join_paid();
        return $this->db->get_where('orders_paids',array('paid_id'=>$id))->row_array();
    }
    function get_paid_custom($where){
        $this->set_select_paid();
        $this->set_join_paid();
        return $this->db->get_where('orders_paids',$where)->row_array();
    }
    function get_paid_custom_result($where){
        $this->set_select_paid();
        $this->set_join_paid();
        return $this->db->get_where('orders_paids',$where)->result_array();
    }

    /* function to update paids */
    function update_paid($id,$params){
        $this->db->where('paid_id',$id);
        return $this->db->update('orders_paids',$params);
    }
    function update_paid_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('orders_paids',$params);
    }    
    
    /* function to delete paids */
    function delete_paid($id){
        return $this->db->delete('orders_paids',array('paid_id'=>$id));
    }
    function delete_paid_custom($where){
        return $this->db->delete('orders_paids',$where);
    }    
}
?>