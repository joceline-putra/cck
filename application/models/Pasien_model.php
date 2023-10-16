<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
/*
    @AUTHOR: Joe Witaya
*/ 
class Pasien_model extends CI_Model
{
    function __construct()
    {
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

    function get_all_patientss($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('patient_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('patients')->result_array();
    }
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new patients */
    function add_patients($params)
    {
        $this->db->insert('patients',$params);
        return $this->db->insert_id();
    }
    
    /* function to get patients by id */
    function get_patients($id)
    {
        return $this->db->get_where('patients',array('patient_id'=>$id))->row_array();
    }

    /* function to update patients */
    function update_patients($id,$params)
    {
        $this->db->where('patient_id',$id);
        return $this->db->update('patients',$params);
    }
    
    /* function to delete patients */
    function delete_patients($id)
    {
        return $this->db->delete('patients',array('patient_id'=>$id));
    }

    /* function to count patients */    
    function get_all_patientss_count($params){
        $this->db->from('patients');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }

    /* function to check data exists patients */
    function check_data_exist($params)
    {
        $this->db->where($params);
        $query = $this->db->get('patients');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>