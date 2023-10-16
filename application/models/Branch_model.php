<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Branch_model extends CI_Model
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
        $this->db->join('branchs_specialists','branch_specialist_id=specialist_id','left');
        $this->db->join('users','branch_user_id=user_id','left');   
        $this->db->join('users_groups','user_user_group_id=user_group_id','left');                
    }

    function get_all_branch($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("branchs.*, branchs_specialists.*, users.*, users_groups.*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('branch_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('branchs')->result_array();
    }
    
    function get_all_branch_count($params,$search){
        $this->db->from('branchs');   
        $this->set_params($params);
        $this->set_search($search);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new branch */
    function add_branch($params)
    {
        $this->db->insert('branchs',$params);
        return $this->db->insert_id();
    }
    
    /* function to get branch by id */
    function get_branch($id)
    {
        $this->db->join('branchs_specialists','branch_specialist_id=specialist_id','left');
        $this->db->join('users','branch_user_id=user_id','left');           
        return $this->db->get_where('branchs',array('branch_id'=>$id))->row_array();
    }

    /* function to update branch */
    function update_branch($id,$params)
    {
        $this->db->where('branch_id',$id);
        return $this->db->update('branchs',$params);
    }
    
    /* function to delete branch */
    function delete_branch($id){
        return $this->db->delete('branchs',array('branch_id'=>$id));
    }

    /* function to check data exists branch */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('branchs');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}



?>