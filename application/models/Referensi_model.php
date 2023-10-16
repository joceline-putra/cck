<?php
 
class Referensi_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function set_params($params) {
        if ($params) {
            foreach ($params as $k => $v) {
                $this->db->where($k, $v);
            }
        }
    }
    function set_search($search) {
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
        
    }

    function get_all_referensis($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('ref_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('references')->result_array();
    }
    function get_all_referensis_join_ref($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("references.*");
        $this->db->select("parent.ref_id AS parent_id, parent.ref_name AS parent_name");
        $this->set_params($params);
        $this->set_search($search);
        $this->db->join('references AS parent','references.ref_parent_id=parent.ref_id','left');
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
    function get_all_referensis_count($params,$search){
        $this->db->from('references');   
        $this->set_params($params);            
        $this->set_search($search);        
        return $this->db->count_all_results();
    }

    function get_referensi($id){
        return $this->db->get_where('references',array('ref_id'=>$id))->row_array();
    }
    function get_referensi_custom($where){
        return $this->db->get_where('references',$where)->row_array();
    }
    function get_all_referensi($params){
        $this->db->order_by('ref_name', 'asc');
        $this->set_params($params);           
        return $this->db->get('references')->result_array();
    }
    function get_all_referensi_custom($params){
        $this->db->order_by('ref_name', 'asc');
        $this->set_params($params);           
        return $this->db->get('references')->row_array();
    }
   
    /*
     * function to add new referensi
     */
    function add_referensi($params){
        $this->db->insert('references',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update referensi
     */
    function update_referensi($id,$params){
        $this->db->where('ref_id',$id);
        return $this->db->update('references',$params);
    }
    function update_referensi_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('references',$params);
    }

    /*
     * function to delete referensi
     */
    function delete_referensi($id){
        return $this->db->delete('references',array('ref_id'=>$id));
    }
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
}
