<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Type_paid_model extends CI_Model{
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
        /* $this->db->join('','','left'); */
    }

    function set_select(){
        $this->db->select("*");
    }

    function set_select_item(){
        $this->db->select("*");
    }

    function get_all_type_paid($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('paid_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('types_paids')->result_array();
    }  
    function get_all_type_paid_count($params,$search){
        $this->db->from('types_paids');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }   

    /* 
        ================
        CRUD Type_paid
        ================
    */        
    
    /* function to add new type_paid */
    function add_type_paid($params){
        $this->db->insert('types_paids',$params);
        return $this->db->insert_id();
    }
    
    /* function to get type_paid by id */
    function get_type_paid($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('types_paids',array('paid_id'=>$id))->row_array();
    }
    function get_type_paid_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('types_paids',$where)->row_array();
    }
    function get_type_paid_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('types_paids',$where)->result_array();
    }

    /* function to update type_paid */
    function update_type_paid($id,$params){
        $this->db->where('paid_id',$id);
        return $this->db->update('types_paids',$params);
    }
    function update_type_paid_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('types_paids',$params);
    }

    /* function to delete type_paid */
    function delete_type_paid($id){
        return $this->db->delete('types_paids',array('paid_id'=>$id));
    }
    function delete_type_paid_custom($where){
        return $this->db->delete('types_paids',$where);
    }

    /* function to check data exists type_paid */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('types_paids');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}
?>