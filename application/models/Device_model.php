<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Device_model extends CI_Model
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

    function get_all_device($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('device_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('devices')->result_array();
    }
    
    function get_all_device_count($params){
        $this->db->from('devices');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new device */
    function add_device($params)
    {
        $this->db->insert('devices',$params);
        return $this->db->insert_id();
    }
    
    /* function to get device by id */
    function get_device($id)
    {
        return $this->db->get_where('devices',array('device_id'=>$id))->row_array();
    }
    function get_device_custom($where)
    {
        return $this->db->get_where('devices',$where)->row_array();
    }

    /* function to update device */
    function update_device($id,$params)
    {
        $this->db->where('device_id',$id);
        return $this->db->update('devices',$params);
    }
    function update_device_custom($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('devices',$params);
    }
    
    /* function to delete device */
    function delete_device($id){
        return $this->db->delete('devices',array('device_id'=>$id));
    }
    function delete_device_custom($where){
        return $this->db->delete('devices',$where);
    }

    /* function to check data exists device */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('devices');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>