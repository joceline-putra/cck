<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Message_model extends CI_Model
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
        $this->db->join('contacts','message_contact_id=contact_id','left');
        $this->db->join('devices','message_device_id=device_id','left');        
    }

    function get_all_message($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("messages.*, devices.*, contact_name, contact_phone_1");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('message_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('messages')->result_array();
    }
    
    function get_all_message_count($params,$search){
        $this->db->from('messages');   
        $this->set_params($params);     
        $this->set_search($search);               
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new message */
    function add_message($params)
    {
        $this->db->insert('messages',$params);
        return $this->db->insert_id();
    }
    
    /* function to get message by id */
    function get_message($id)
    {
        $this->set_join();
        return $this->db->get_where('messages',array('message_id'=>$id))->row_array();
    }
    function get_message_custom($where)
    {
        $this->set_join();
        return $this->db->get_where('messages',$where)->row_array();
    }
    function get_message_custom_result($where)
    {
        $this->set_join();
        return $this->db->get_where('messages',$where)->result_array();
    }

    /* function to update message */
    function update_message($id,$params)
    {
        $this->db->where('message_id',$id);
        return $this->db->update('messages',$params);
    }
    function update_message_custom($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('messages',$params);
    }
    
    /* function to delete message */
    function delete_message($id){
        return $this->db->delete('messages',array('message_id'=>$id));
    }
    function delete_message_custom($where){
        return $this->db->delete('messages',$where);
    }

    /* function to check data exists message */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('messages');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}



?>