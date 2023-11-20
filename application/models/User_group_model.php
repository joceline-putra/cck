<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class User_group_model extends CI_Model{
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


    function set_select(){
        $this->db->select("*");
    }


    function get_all_user_group($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('user_group_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('users_groups')->result_array();
    }  
    function get_all_user_group_count($params,$search){
        $this->db->from('users_groups');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }

    /* 
        ================
        CRUD User_group
        ================
    */        
    
    /* function to add new user_group */
    function add_user_group($params){
        $this->db->insert('users_groups',$params);
        return $this->db->insert_id();
    }
    
    /* function to get user_group by id */
    function get_user_group($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('users_groups',array('user_group_id'=>$id))->row_array();
    }
    function get_user_group_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('users_groups',$where)->row_array();
    }
    function get_user_group_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('users_groups',$where)->result_array();
    }

    /* function to update user_group */
    function update_user_group($id,$params){
        $this->db->where('user_group_id',$id);
        return $this->db->update('users_groups',$params);
    }
    function update_user_group_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('users_groups',$params);
    }

    /* function to delete user_group */
    function delete_user_group($id){
        return $this->db->delete('users_groups',array('user_group_id'=>$id));
    }
    function delete_user_group_custom($where){
        return $this->db->delete('users_groups',$where);
    }

    /* function to check data exists user_group */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('users_groups');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists user_group of two condition */
    function check_data_exist_two_condition($where_not_in,$where_exist){
        if ($where_not_in) {
            foreach ($where_not_in as $k => $v) {
                $this->db->where($k.' !=', $v);
            }
        }
        if ($where_exist) {
            $n = 0;
            $this->db->group_start();
            foreach($where_exist as $key => $val) {
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
        $query = $this->db->get('users_groups');
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    
}?>