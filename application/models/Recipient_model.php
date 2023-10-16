<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Recipient_model extends CI_Model{
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
        $this->db->join('recipients_groups','recipient_group_id=group_id','left');
    }

    function set_join_group(){
        /* $this->db->join('','','left'); */
    }

    function set_select(){
        $this->db->select("*");
    }

    function set_select_group(){
        $this->db->select("*");
    }

    function get_all_recipient($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('recipient_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('recipients')->result_array();
    }  
    function get_all_recipient_count($params,$search){
        $this->db->from('recipients');
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();        
        return $this->db->count_all_results();
    }
    /* 
        ================
        CRUD Recipient
        ================
    */        
    
    /* function to add new recipient */
    function add_recipient($params){
        $this->db->insert('recipients',$params);
        return $this->db->insert_id();
    }
    
    /* function to get recipient by id */
    function get_recipient($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('recipients',array('recipient_id'=>$id))->row_array();
    }
    function get_recipient_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('recipients',$where)->row_array();
    }
    function get_recipient_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('recipients',$where)->result_array();
    }

    /* function to update recipient */
    function update_recipient($id,$params){
        $this->db->where('recipient_id',$id);
        return $this->db->update('recipients',$params);
    }
    function update_recipient_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('recipients',$params);
    }

    /* function to delete recipient */
    function delete_recipient($id){
        return $this->db->delete('recipients',array('recipient_id'=>$id));
    }
    function delete_recipient_custom($where){
        return $this->db->delete('recipients',$where);
    }

    /* function to check data exists recipient */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('recipients');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    /* 
        ================
        CRUD Recipient Groups
        ================
    */
    function get_all_recipient_group($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_group();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_group();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('group_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('recipients_groups')->result_array();
    }  
    function get_all_recipient_group_count($params,$search){
        $this->db->from('recipients_groups');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }      
    function add_recipient_group($params){
        $this->db->insert('recipients_groups',$params);
        return $this->db->insert_id();
    }
    function get_recipient_group($id){
        $this->set_select_group();
        $this->set_join_group();
        return $this->db->get_where('recipients_groups',array('group_id'=>$id))->row_array();
    }
    function get_recipient_group_custom($where){
        $this->set_select_group();
        $this->set_join_group();
        return $this->db->get_where('recipients_groups',$where)->row_array();
    }
    function get_recipient_group_custom_result($where){
        $this->set_select_group();
        $this->set_join_group();
        return $this->db->get_where('recipients_groups',$where)->result_array();
    }
    function update_recipient_group($id,$params){
        $this->db->where('group_id',$id);
        return $this->db->update('recipients_groups',$params);
    }
    function update_recipient_group_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('recipients_groups',$params);
    }
    function delete_recipient_group($id){
        return $this->db->delete('recipients_groups',array('group_id'=>$id));
    }
    function delete_recipient_groups_custom($where){
        return $this->db->delete('recipients_groups',$where);
    }  
    function check_data_exist_recipient_group($params){
        $this->db->where($params);
        $query = $this->db->get('recipients_groups');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }      
}
?>