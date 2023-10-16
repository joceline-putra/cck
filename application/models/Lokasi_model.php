<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Lokasi_model extends CI_Model
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
        $this->db->join('users AS u', 'locations.location_user_id=u.user_id','left');    
    }

    function get_all_lokasis($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        // var_dump($params);die;
        $this->db->select("locations.*, u.user_username");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('locations.location_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('locations')->result_array();
    }
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new lokasi */
    function add_lokasi($params)
    {
        $this->db->insert('locations',$params);
        return $this->db->insert_id();
    }
    
    /* function to get lokasi by id */
    function get_lokasi($id)
    {
        return $this->db->get_where('locations',array('location_id'=>$id))->row_array();
    }

    /* function to update lokasi */
    function update_lokasi($id,$params)
    {
        $this->db->where('location_id',$id);
        return $this->db->update('locations',$params);
    }
    
    /* function to delete lokasi */
    function delete_lokasi($id)
    {
        return $this->db->delete('locations',array('location_id'=>$id));
    }

    /* function to count lokasi */    
    function get_all_lokasis_count($params,$search){
        $this->db->from('locations');   
        $this->set_params($params);        
        $this->set_search($search);    
        return $this->db->count_all_results();
    }

    /* function to check data exists lokasi */
    function check_data_exist($params)
    {
        $this->db->where($params);
        $query = $this->db->get('locations');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>