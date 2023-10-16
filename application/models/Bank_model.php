<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Bank_model extends CI_Model
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
        $this->db->join('users','bank_user_session=user_session','left');
        $this->db->join('categories','bank_category_id=category_id','left');        
    }

    function get_all_bank($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('bank_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('banks')->result_array();
    }
    
    function get_all_bank_count($params){
        $this->db->from('banks');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new bank */
    function add_bank($params)
    {
        $this->db->insert('banks',$params);
        return $this->db->insert_id();
    }
    
    /* function to get bank by id */
    function get_bank($id)
    {
        $this->db->join('users','bank_user_session=user_session','left');
        return $this->db->get_where('banks',array('bank_id'=>$id))->row_array();
    }
    function get_bank_custom($where)
    {
        $this->db->join('categories','bank_category_id=category_id','left');
        $this->db->join('users','bank_user_session=user_session','left');
        return $this->db->get_where('banks',$where)->row_array();
    }

    /* function to update bank */
    function update_bank($id,$params)
    {
        $this->db->where('bank_id',$id);
        return $this->db->update('banks',$params);
    }
    function update_bank_custom($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('banks',$params);
    }
    
    /* function to delete bank */
    function delete_bank($id){
        return $this->db->delete('banks',array('bank_id'=>$id));
    }
    function delete_bank_custom($where){
        return $this->db->delete('banks',$where);
    }

    /* function to check data exists bank */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('banks');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>