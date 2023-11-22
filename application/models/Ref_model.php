<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Ref_model extends CI_Model{
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
        $this->db->join('references AS parent','references.ref_parent_id=parent.ref_id','left');
        $this->db->join('branchs','branchs.branch_id=references.ref_branch_id','left');        
    }

    function set_join_price(){
        $this->db->join('references','price_ref_id=ref_id','left');
    }

    function set_select(){
        $this->db->select("references.*");
        $this->db->select("parent.ref_id AS parent_id, parent.ref_name AS parent_name");
        $this->db->select("branch_id, branch_name");
    }

    function set_select_price(){
        $this->db->select("references_prices.*");
        $this->db->select("ref_id, ref_branch_id, ref_code, ref_name");        
    }

    function get_all_ref($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('ref_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('references')->result_array();
    }  
    function get_all_ref_count($params,$search){
        $this->db->from('references');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }
    function get_all_ref_price($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_price();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_price();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('ref_price_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('references_prices')->result_array();
    }  
    function get_all_ref_price_count($params,$search){
        $this->db->from('references_prices');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    }    

    /* 
        ================
        CRUD Ref
        ================
    */        
    
    /* function to add new ref */
    function add_ref($params){
        $this->db->insert('references',$params);
        return $this->db->insert_id();
    }
    
    /* function to get ref by id */
    function get_ref($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('references',array('ref_id'=>$id))->row_array();
    }
    function get_ref_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('references',$where)->row_array();
    }
    function get_ref_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('references',$where)->result_array();
    }

    /* function to update ref */
    function update_ref($id,$params){
        $this->db->where('ref_id',$id);
        return $this->db->update('references',$params);
    }
    function update_ref_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('references',$params);
    }

    /* function to delete ref */
    function delete_ref($id){
        return $this->db->delete('references',array('ref_id'=>$id));
    }
    function delete_ref_custom($where){
        return $this->db->delete('references',$where);
    }

    /* function to check data exists ref */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('references');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists ref of two condition */
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
        $query = $this->db->get('references');
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    /* 
        ================
        CRUD Ref ITEM
        ================
    */
    
    /* function to add new ref items */
    function add_ref_price($params){
        $this->db->insert('references_prices',$params);
        return $this->db->insert_id();
    }
    
    /* function to get ref items by id */
    function get_ref_price($id){
        $this->set_select_price();
        $this->set_join_price();
        return $this->db->get_where('references_prices',array('price_id'=>$id))->row_array();
    }
    function get_ref_price_custom($where){
        $this->set_select_price();
        $this->set_join_price();
        return $this->db->get_where('references_prices',$where)->row_array();
    }
    function get_ref_price_custom_result($where){
        $this->set_select_price();
        $this->set_join_price();
        return $this->db->get_where('references_prices',$where)->result_array();
    }

    /* function to update ref items */
    function update_ref_price($id,$params){
        $this->db->where('price_id',$id);
        return $this->db->update('references_prices',$params);
    }
    function update_ref_price_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('references_prices',$params);
    }    
    
    /* function to delete ref items */
    function delete_ref_price($id){
        return $this->db->delete('references_prices',array('price_id'=>$id));
    }
    function delete_ref_price_custom($where){
        return $this->db->delete('references_prices',$where);
    }

    /* function to check data exists references_prices */
    function check_data_exist_prices($params){
        $this->db->where($params);
        $query = $this->db->get('references_prices');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists ref of two condition */
    function check_data_exist_prices_two_condition($where_not_in,$where_exist){
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
        $query = $this->db->get('references_prices');
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}
?>