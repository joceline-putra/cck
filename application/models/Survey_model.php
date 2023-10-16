<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Survey_model extends CI_Model
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
        /* $this->db->join('','','left'); */
    }

    function get_all_survey($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('survey_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('surveys')->result_array();
    }
    
    function get_all_survey_count($params){
        $this->db->from('surveys');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new survey */
    function add_survey($params)
    {
        $this->db->insert('surveys',$params);
        return $this->db->insert_id();
    }
    
    /* function to get survey by id */
    function get_survey($id)
    {
        return $this->db->get_where('surveys',array('survey_id'=>$id))->row_array();
    }
    function get_survey_custom($where)
    {
        return $this->db->get_where('surveys',$where)->row_array();
    }

    /* function to update survey */
    function update_survey($id,$params)
    {
        $this->db->where('survey_id',$id);
        return $this->db->update('surveys',$params);
    }
    function update_survey_custom($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('surveys',$params);
    }
    
    /* function to delete survey */
    function delete_survey($id){
        return $this->db->delete('surveys',array('survey_id'=>$id));
    }
    function delete_survey_custom($where){
        return $this->db->delete('surveys',$where);
    }

    /* function to check data exists survey */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('surveys');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>