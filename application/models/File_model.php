<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class File_model extends CI_Model{
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
        $this->db->join('users','file_user_id=user_id','left');
    }

    function set_select(){
        $this->db->select("files.*, fn_time_ago(`file_date_created`) AS time_ago");
        $this->db->select("user_id, user_username");        
    }

    function get_all_file($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('file_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('files')->result_array();
    }  
    function get_all_file_count($params,$search){
        $this->db->from('files');
        $this->set_params($params);
        $this->set_search($search);
        return $this->db->count_all_results();
    } 

    /* 
        ================
        CRUD File
        ================
    */        
    
    function add_file($params){
        $this->db->insert('files',$params);
        return $this->db->insert_id();
    }
    
    function get_file($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('files',array('file_id'=>$id))->row_array();
    }
    function get_file_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('files',$where)->row_array();
    }
    function get_file_custom_result($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('files',$where)->result_array();
    }

    function update_file($id,$params){
        $this->db->where('file_id',$id);
        return $this->db->update('files',$params);
    }
    function update_file_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('files',$params);
    }

    function delete_file($id){
        return $this->db->delete('files',array('file_id'=>$id));
    }
    function delete_file_custom($where){
        return $this->db->delete('files',$where);
    }

    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('files');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists files of two condition */
    function check_data_exist_two_condition($param_1,$param_2,$session){ die;
        if(strlen($session) > 2){ //When update data
            $this->db->where('file_session !=',$session);
            $this->db->where('(`file_column_1="'.$param_2.'" OR `file_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($param_1);
        }

        $query = $this->db->get('files');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}
?>