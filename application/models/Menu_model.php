<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Menu_model extends CI_Model{
    
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
        // $this->db->join('menu_groups AS mg', 'menus.menu_menu_group_id=mg.menu_group_id','left');  
    }

    function get_all_menus($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("menus.*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('menus.menu_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('menus')->result_array();
    }
    function get_all_menus_custom($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        // $sub_query = 'SELECT menu_id AS parent_id, menu_icon AS parent_icon, menu_name AS parent_name FROM menus WHERE menu_parent_id=0 AS parent';
        
        $this->db->select("parent.menu_id AS parent_id, parent.menu_name AS parent_name, parent.menu_icon AS parent_icon");
        $this->db->select("child.menu_parent_id AS child_parent_id, child.menu_id AS child_id, child.menu_name AS child_name, child.menu_link AS child_link, child.menu_flag AS child_flag,  child.menu_icon AS child_icon");

        $this->set_params($params);
        $this->set_search($search);
        $this->db->from("menus AS child");
        $this->db->join("(SELECT menu_id, menu_icon, menu_name FROM menus WHERE menu_parent_id=0) AS parent","parent.menu_id=child.menu_parent_id","RIGHT");
        // $this->db->from("(SELECT menu_id AS parent_id, menu_icon AS parent_icon, menu_name AS parent_name FROM menus WHERE menu_parent_id=0) AS parent");
        // $this->db->join("(SELECT menu_parent_id AS child_parent_id, menu_id AS child_id, menu_name AS child_name, menu_link AS 
        // child_link, menu_flag AS child_flag FROM menus) AS child","parent.parent_id=child.child_parent_id","LEFT OUTER");

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('parent.menu_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get()->result_array();
    }        
    /* 
        ================
        CRUD 
        ================
    */        
    
    function add_menu($params){
        $this->db->insert('menus',$params);
        return $this->db->insert_id();
    }
    function get_menu($id){
        return $this->db->get_where('menus',array('menu_id'=>$id))->row_array();
    }
    function update_menu($id,$params){
        $this->db->where('menu_id',$id);
        return $this->db->update('menus',$params);
    }
    function delete_menu($id){
        return $this->db->delete('menus',array('menu_id'=>$id));
    }    
    function get_all_menus_count($params){
        $this->db->from('menus');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }
    function get_all_menus_custom_count($params,$search){
        // $this->db->from('menus');   
        $this->db->select("parent.menu_id AS parent_id, parent.menu_name AS parent_name, parent.menu_icon AS parent_icon");
        $this->db->select("child.menu_parent_id AS child_parent_id, child.menu_id AS child_id, child.menu_id AS child_name, child.menu_link AS child_link, child.menu_flag AS child_flag");

        $this->set_params($params);
        $this->db->from("menus AS child");
        $this->db->join("(SELECT menu_id, menu_icon, menu_name FROM menus WHERE menu_parent_id=0) AS parent","parent.menu_id=child.menu_parent_id","LEFT OUTER");

        // $this->db->select("parent_id, parent_name, child_id, child_name, child_link, child_flag");
        // $this->set_params($params);
        // $this->db->from("(SELECT menu_id AS parent_id, menu_name AS parent_name FROM menus WHERE menu_parent_id=0) AS parent");
        // $this->db->join("(SELECT menu_parent_id AS child_parent_id, menu_id AS child_id, menu_name AS child_name, menu_link AS child_link, menu_flag AS child_flag FROM menus) AS child","parent.parent_id=child.child_parent_id","LEFT OUTER");
        $this->set_search($search);
        return $this->db->count_all_results();
    }
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('menus');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    function check_data_exist_user_menu($params){
        $this->db->where($params);
        $query = $this->db->get('users_menus');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    function update_data_user_menu($id,$params){            
        $this->db->where('user_menu_menu_id',$id);
        return $this->db->update('users_menus',$params);
    }                    
}