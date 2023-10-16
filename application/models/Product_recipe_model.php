<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Product_recipe_model extends CI_Model
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
        $this->db->join('products', 'recipe_product_id_child=product_id','left');        
    }

    function get_all_recipe($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('recipe_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('products_recipes')->result_array();
    }
    
    function get_all_recipe_count($params){
        $this->db->from('products_recipes');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new recipe */
    function add_recipe($params)
    {
        $this->db->insert('products_recipes',$params);
        return $this->db->insert_id();
    }
    
    /* function to get recipe by id */
    function get_recipe($id)
    {
        return $this->db->get_where('products_recipes',array('recipe_id'=>$id))->row_array();
    }

    /* function to update recipe */
    function update_recipe($id,$params)
    {
        $this->db->where('recipe_id',$id);
        return $this->db->update('products_recipes',$params);
    }
    
    /* function to delete recipe */
    function delete_recipe($id){
        return $this->db->delete('products_recipes',array('recipe_id'=>$id));
    }

    /* function to check data exists recipe */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('products_recipes');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}



?>