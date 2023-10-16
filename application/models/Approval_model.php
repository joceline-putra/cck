<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Approval_model extends CI_Model
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
        $this->db->join('users AS user_from','approval_user_from=user_from.user_id','left');
        $this->db->join('users AS user_to','approval_user_id=user_to.user_id','left');        
    }

    function get_all_approval($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("approvals.*, fn_time_ago(`approval_date_created`) AS time_ago");
        $this->db->select("user_from.user_id AS from_user_id, user_from.user_username AS from_user_username");
        $this->db->select("user_to.user_id AS to_user_id, user_to.user_username AS to_user_username");        
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('approval_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('approvals')->result_array();
    }
    
    function get_all_approval_count($params){
        $this->db->from('approvals');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new approval */
    function add_approval($params){
        $this->db->insert('approvals',$params);
        return $this->db->insert_id();
    }
    
    /* function to get approval by id */
    function get_approval($id){
        return $this->db->get_where('approvals',array('approval_id'=>$id))->row_array();
    }
    function get_approval_custom($where){
        return $this->db->get_where('approvals',$where)->row_array();
    }    
    function get_approval_min($column,$where){
        $this->db->select_min($column);
        $this->set_params($where);            
        return $this->db->get('approvals')->row_array();
    }
    function get_approval_max($column,$where){
        $this->db->select_max($column);
        $this->set_params($where);            
        return $this->db->get('approvals')->row_array();
    }    

    /* function to update approval */
    function update_approval($id,$params){
        $this->db->where('approval_id',$id);
        return $this->db->update('approvals',$params);
    }
    function update_approval_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('approvals',$params);
    }    
    
    /* function to delete approval */
    function delete_approval($id){
        return $this->db->delete('approvals',array('approval_id'=>$id));
    }
	function delete_approval_custom($where){
        return $this->db->delete('approvals',$where);
    }    

    /* function to check data exists approval */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('approvals');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}



?>