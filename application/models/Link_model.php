<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Link_model extends CI_Model{
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
        $this->db->join('branchs','link_branch_session=branch_session','left');
    }
    function get_all_link($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('link_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('links')->result_array();
    }
    function get_all_link_count($params){
        $this->db->from('links');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
    function add_link($params){
        $this->db->insert('links',$params);
        return $this->db->insert_id();
    }
    function get_link($id){
        $this->set_join();
        return $this->db->get_where('links',array('link_id'=>$id))->row_array();
    }
    function get_link_custom($where){
        $this->set_join();
        return $this->db->get_where('links',$where)->row_array();
    }
    function update_link($id,$params){
        $this->db->where('link_id',$id);
        return $this->db->update('links',$params);
    }
    function update_link_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('links',$params);
    }
    function delete_link($id){
        return $this->db->delete('links',array('link_id'=>$id));
    }
    function delete_link_custom($where){
        return $this->db->delete('links',$where);
    }
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('links');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    /* Link Hits */
    function get_all_links_hits($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('hit_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('links_hits')->result_array();
    }
    function get_all_links_hits_count($params){
        $this->db->from('links_hits');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
    function add_links_hits($params){
        $this->db->insert('links_hits',$params);
        return $this->db->insert_id();
    }
    function get_links_hits($id){
        return $this->db->get_where('links_hits',array('hit_id'=>$id))->row_array();
    }
    function get_links_hits_custom($where){
        return $this->db->get_where('links_hits',$where)->row_array();
    }
    function update_links_hit($id,$params){
        $this->db->where('hit_id',$id);
        return $this->db->update('links_hits',$params);
    }
    function update_links_hit_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('links_hits',$params);
    }
    function delete_links_hits($id){
        return $this->db->delete('links_hits',array('hit_id'=>$id));
    }
    function delete_links_hits_custom($where){
        return $this->db->delete('links_hits',$where);
    }
}
?>