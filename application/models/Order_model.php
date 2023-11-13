<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Order_model extends CI_Model {
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
        $this->db->join('contacts','orders.order_contact_id=contacts.contact_id','left');
        $this->db->join('references','orders.order_ref_id=references.ref_id','left');
        $this->db->join('users','orders.order_user_id=users.user_id','left');
        $this->db->join('accounts','order_with_dp_account=account_id','left');
        $this->db->join('types','order_type=type_type AND type_for=1','left');        
        $this->db->join('references AS label','label.ref_name=order_label','left');      
        // $this->db->join('contacts AS c2','orders.order_contact_id_2=c2.contact_id','left');          
    }

    /* Orders */
    function get_all_orders($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("orders.*, contacts.*, `references`.*, users.*, accounts.*, types.*");
        $this->db->select("order_id AS order_id, DATE_FORMAT(`order_date`,'%d-%b-%y, %H:%i') AS order_date_format, users.user_username");
        // $this->db->select("(SELECT IFNULL(SUM(order_item_total),0) FROM orders_items WHERE order_item_order_id=order_id) AS order_subtotal");
        $this->db->select("IFNULL(order_total,0) AS order_grand_total");
        $this->db->select('label.ref_id AS label_id, label.ref_name AS label_name, label.ref_color AS label_color, label.ref_background AS label_background, label.ref_note AS label_icon');
        // $this->db->select('c2.contact_code AS employee_code, c2.contact_name AS employee_name');
        $this->db->select('order_sales_id AS employee_id, order_sales_name AS employee_code, order_sales_name AS employee_name');
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
    function get_all_orders_datatable($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("orders.*");
        $this->db->select("contacts.*");
        // $this->db->select("`references`.*");
        // $this->db->select("users.*");
        // $this->db->select("accounts.*");
        // $this->db->select("types.*");          

        $this->db->select("order_id AS order_id, DATE_FORMAT(`order_date`,'%d-%b-%y, %H:%i') AS order_date_format, IFNULL(order_total,0) AS order_grand_total");
        // $this->db->select("users.user_username");
        $this->db->select('label.ref_id AS label_id, label.ref_name AS label_name, label.ref_color AS label_color, label.ref_background AS label_background, label.ref_note AS label_icon');
        $this->db->select('order_sales_id AS employee_id, order_sales_name AS employee_code, order_sales_name AS employee_name');
        $this->set_params($params);
        $this->set_search($search);

        $this->db->join('contacts','orders.order_contact_id=contacts.contact_id','left');
        // $this->db->join('references','orders.order_ref_id=references.ref_id','left');
        // $this->db->join('users','orders.order_user_id=users.user_id','left');
        // $this->db->join('accounts','order_with_dp_account=account_id','left');
        // $this->db->join('types','order_type=type_type AND type_for=1','left');        
        $this->db->join('references AS label','label.ref_name=order_label','left'); 

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
    function get_all_orders_pos($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("orders.*");
        $this->db->select("contacts.*");
        $this->db->select("`references`.*");        
        
        $this->db->select("order_id AS order_id, DATE_FORMAT(`order_date`,'%d-%b-%y, %H:%i') AS order_date_format, IFNULL(order_total,0) AS order_grand_total");
        $this->db->select('label.ref_id AS label_id, label.ref_name AS label_name, label.ref_color AS label_color, label.ref_background AS label_background, label.ref_note AS label_icon');
        $this->db->select('order_sales_id AS employee_id, order_sales_name AS employee_code, order_sales_name AS employee_name');
        $this->set_params($params);
        $this->set_search($search);

        $this->db->join('contacts','orders.order_contact_id=contacts.contact_id','left');
        $this->db->join('references','orders.order_ref_id=references.ref_id','left');
        $this->db->join('references AS label','label.ref_name=order_label','left'); 

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
    function get_all_orders_kitchen($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("orders.*");
        $this->db->select("`references`.*");        
        
        $this->db->select("order_id AS order_id, DATE_FORMAT(`order_date`,'%d-%b-%y, %H:%i') AS order_date_format, IFNULL(order_total,0) AS order_grand_total");
        $this->db->select('order_sales_id AS employee_id, order_sales_name AS employee_code, order_sales_name AS employee_name');
                
        $this->set_params($params);
        $this->set_search($search);

        $this->db->join('contacts','orders.order_contact_id=contacts.contact_id','left');
        $this->db->join('references','orders.order_ref_id=references.ref_id','left');

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
    function get_all_orders_nojoin($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
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
    function get_all_orders_count($params,$search){
        $this->db->from('orders');   
        $this->set_join();
        $this->set_params($params);         
        $this->set_search($search);   
        return $this->db->count_all_results();
    }
    function get_all_orders_nojoin_count($params,$search){
        $this->db->from('orders');
        $this->set_params($params);         
        $this->set_search($search);   
        return $this->db->count_all_results();
    }
    function get_all_orders_pos_count($params,$search){
        $this->db->from('orders');   
        $this->db->join('contacts','orders.order_contact_id=contacts.contact_id','left');
        $this->db->join('references','orders.order_ref_id=references.ref_id','left');
        $this->db->join('references AS label','label.ref_name=order_label','left'); 

        $this->set_params($params);         
        $this->set_search($search);   
        return $this->db->count_all_results();
    }
    function get_all_orders_kitchen_count($params,$search){
        $this->db->from('orders');   
        $this->db->join('references','orders.order_ref_id=references.ref_id','left');
        
        $this->set_params($params);         
        $this->set_search($search);   
        return $this->db->count_all_results();
    }    
    /* Orders Items */
    function get_all_order_items($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();

        $this->db->join('products','orders_items.order_item_product_id=products.product_id','left');
        $this->db->join('orders','orders_items.order_item_order_id=orders.order_id','left');

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
    function get_all_order_items_nojoin($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
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
    function get_all_order_items_count($params){
        $this->db->from('orders_items');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }
    function get_all_order_items_nojoin_count($params,$search){
        $this->db->from('orders_items');
        $this->set_params($params);         
        $this->set_search($search);   
        return $this->db->count_all_results();
    }    
    function get_all_order_items_report($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("orders_items.*, orders.*, contacts.*, products.*, locations.*");
        $this->set_params($params);
        // $this->set_search($search);
        // $this->set_join();
        
        $this->db->join('products','orders_items.order_item_product_id=products.product_id','left');
        $this->db->join('orders','orders_items.order_item_order_id=orders.order_id','left');
        $this->db->join('contacts','orders.order_contact_id=contacts.contact_id','left');
        $this->db->join('locations','order_item_location_id=location_id','left');

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

    /* 
        ================
        CRUD 
        ================
    */        
    /* function to add new order */
    function add_order($params){
        $this->db->insert('orders',$params);
        return $this->db->insert_id();
    }
    /* function to get order by id */
    function get_order($id){
        $this->db->select("orders.*, contacts.*, `references`.*, users.*, accounts.*, types.*");
        $this->db->select("order_id AS order_id, DATE_FORMAT(`order_date`,'%d-%b-%y, %H:%i') AS order_date_format, users.user_username");
        // $this->db->select("(SELECT IFNULL(SUM(order_item_total),0) FROM orders_items WHERE order_item_order_id=order_id) AS order_subtotal");
        $this->db->select("IFNULL(order_total,0) AS order_grand_total");
        $this->db->select('label.ref_id AS label_id, label.ref_name AS label_name, label.ref_color AS label_color, label.ref_background AS label_background, label.ref_note AS label_icon');
        // $this->db->select('c2.contact_code AS employee_code, c2.contact_name AS employee_name');        
        $this->db->select('order_sales_id AS employee_id, order_sales_name AS employee_code, order_sales_name AS employee_name');
        $this->set_join();
        return $this->db->get_where('orders',array('order_id'=>$id))->row_array();
    }
    function get_order_custom($params){
        $this->db->select("orders.*, contacts.*, `references`.*, users.*, accounts.*, types.*");
        $this->db->select("order_id AS order_id, DATE_FORMAT(`order_date`,'%d-%b-%y, %H:%i') AS order_date_format, users.user_username");
        // $this->db->select("(SELECT IFNULL(SUM(order_item_total),0) FROM orders_items WHERE order_item_order_id=order_id) AS order_subtotal");
        $this->db->select("IFNULL(order_total,0) AS order_grand_total");
        $this->db->select('label.ref_id AS label_id, label.ref_name AS label_name, label.ref_color AS label_color, label.ref_background AS label_background, label.ref_note AS label_icon');
        // $this->db->select('c2.contact_code AS employee_code, c2.contact_name AS employee_name');         
        $this->db->select('order_sales_id AS employee_id, order_sales_name AS employee_code, order_sales_name AS employee_name');
        $this->set_join();
        return $this->db->get_where('orders',$params)->row_array();
    }
    function get_order_ref_custom($params){
        // $this->db->select("orders.*, contacts.*, `references`.*, users.*, accounts.*, types.*");
        $this->db->select("orders.*, contacts.*, `references`.*");        
        $this->db->select("order_id AS order_id, DATE_FORMAT(`order_date`,'%d-%b-%y, %H:%i') AS order_date_format");
        $this->db->select("IFNULL(order_total,0) AS order_grand_total");
        // $this->db->select('c2.contact_code AS employee_code, c2.contact_name AS employee_name');         
        // $this->db->select('order_sales_id AS employee_id, order_sales_name AS employee_code, order_sales_name AS employee_name');
        $this->db->join('references','orders.order_ref_id=references.ref_id','left');  
        $this->db->join('contacts','orders.order_contact_id=contacts.contact_id','left');              
        return $this->db->get_where('orders',$params)->row_array();
    }
    function get_order_nojoin($where){
        return $this->db->get_where('orders',$where)->row_array();
    }
    function get_order_nojoin_custom($where){
        return $this->db->get_where('orders',$where)->row_array();
    }    
    /* function to update order */
    function update_order($id,$params){
        $this->db->where('order_id',$id);
        return $this->db->update('orders',$params);
    }
    function update_order_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('orders',$params);
    }
    /* function to delete order */
    function delete_order($id){
        return $this->db->delete('orders',array('order_id'=>$id));
    }
    function delete_order_custom($where){
        return $this->db->delete('orders',$where);
    }    

    /* function to check data exists order */
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

    /* 
        ================
        CRUD ITEM
        ================
    */
    /* function to add new order */
    function add_order_item($params){
        $this->db->insert('orders_items',$params);
        return $this->db->insert_id();
    }
    /* function to get order by id */
    function get_order_item($id){
        return $this->db->get_where('orders_items',array('order_item_id'=>$id))->row_array();
    }
    function get_order_item_params($params){
        return $this->db->where($params)->get('orders_items')->row_array();
    }
    /* function to update order */
    function update_order_item($id,$params){
        $this->db->where('order_item_id',$id);
        return $this->db->update('orders_items',$params);
    }
    function update_order_item_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('orders_items',$params);
    }    
    /* function to update order item */
    function update_order_item_by_order_id($id,$params){
        $this->db->where('order_item_order_id',$id);
        return $this->db->update('orders_items',$params);
    }             
    /* function to delete order */
    function delete_order_item($id){
        return $this->db->delete('orders_items',array('order_item_id'=>$id));
    } 

    function delete_order_item_by_order_id($id){
        return $this->db->delete('orders_items',array('order_item_order_id'=>$id));
    } 
    /* function to check data exists order item*/
    function check_data_exist_item($params){
        $this->db->where($params);
        $query = $this->db->get('orders_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }     

    /* OTHER */
    /* function to get order by id */
    function check_unsaved_order_item($identity,$user_id,$branch_id){   
        $this->db->select("orders_items.*, products.*");
        $this->db->from('orders_items');
        $this->db->join('products','orders_items.order_item_product_id=products.product_id','left');
        $this->db->where('order_item_user_id',$user_id);      
        $this->db->where('order_item_branch_id',$branch_id);          
        $this->db->where('order_item_type',$identity);  
        $this->db->where('order_item_flag',0);
        $this->db->where('order_item_order_id',null);        
        $this->db->order_by('order_item_id','desc');
        // return $this->db->get_where('trans_item',array('trans_item_id_user'=>$user_id))->result_array();
        return $this->db->get()->result_array();
    }
    function check_unsaved_order_item_count($identity,$user_id,$branch_id){
        // $this->db->select("orders_items.*, products.*");        
        $this->db->from('orders_items');   
        $this->db->join('products','orders_items.order_item_product_id=products.product_id','left');
        $this->db->where('order_item_user_id',$user_id);      
        $this->db->where('order_item_branch_id',$branch_id);          
        $this->db->where('order_item_type',$identity);  
        $this->db->where('order_item_flag',0);
        $this->db->where('order_item_order_id',null);        
        $this->db->order_by('order_item_id','desc');        
        return $this->db->count_all_results();
    }  
    /* function to get order by id */
    function reset_order_item($user_id){   
        return $this->db->delete('orders_items',array('order_item_flag'=>0,'order_item_user_id'=>$user_id));
    }
    function get_order_item_price_total($params){
        // $this->db->select_sum('trans_item_in_price');
        $this->db->select_sum('order_item_total');
        $this->db->from('orders_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);        
        return $this->db->get()->row_array();
    }
}


?>