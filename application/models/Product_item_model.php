<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Product_item_model extends CI_Model
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
        $this->db->join('products','products_items.product_item_product_id=products.product_id','left');
    }

    function get_all_product_item($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('product_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('products_items')->result_array();
    }
    
    function get_all_product_item_count($params){
        $this->db->from('products_items');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new product_item */
    function add_product_item($params)
    {
        $this->db->insert('products_items',$params);
        return $this->db->insert_id();
    }
    
    /* function to get product_item by id */
    function get_product_item($id)
    {
        return $this->db->get_where('products_items',array('product_item_id'=>$id))->row_array();
    }

    /* function to update product_item */
    function update_product_item($id,$params)
    {
        $this->db->where('product_item_id',$id);
        return $this->db->update('products_items',$params);
    }
    
    /* function to delete product_item */
    function delete_product_item($id){
        return $this->db->delete('products_items',array('product_item_id'=>$id));
    }

    /* function to check data exists product_item */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('products_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>