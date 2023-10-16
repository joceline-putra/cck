<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Tax_model extends CI_Model{
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

    function get_all_tax($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('tax_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('taxs')->result_array();
    }
    /* function get_all_tax($search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        $prepare = "CALL sp_taxs_data('$search',$limit,$start,'$order','$dir')";
        $query = $this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        $query->free_result();

        // log_message("debug", print_r($prepare, true));
        return $result;
    } */  
    function get_all_tax_count($params){
        $this->db->from('taxs');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new tax */
    function add_tax($params){
        $this->db->insert('taxs',$params);
        return $this->db->insert_id();
    }
    
    /* function to get tax by id */
    function get_tax($id){
        $this->set_join();
        return $this->db->get_where('taxs',array('tax_id'=>$id))->row_array();
    }
    function get_tax_custom($where){
        $this->set_join();
        return $this->db->get_where('taxs',$where)->row_array();
    }

    /* function to update tax */
    function update_tax($id,$params){
        $this->db->where('tax_id',$id);
        return $this->db->update('taxs',$params);
    }
    function update_tax_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('taxs',$params);
    }
    
    /* function to delete tax */
    function delete_tax($id){
        return $this->db->delete('taxs',array('tax_id'=>$id));
    }
    function delete_tax_custom($where){
        return $this->db->delete('taxs',$where);
    }

    /* 
        ================
        CRUD 2
        ================
    */        
    /* 
        1 = Create
        2 = Read 
        3 = Update 
        4 = Delete
    */
    /*
    function sp_tax($action = null, $params = null, $where = null) {
        if(intval($action) == 1){
            $p = $this->Tool_model->set_create($params);
            $w = "";
        }elseif((intval($action) == 2) or (intval($action) == 4)){
            $p = "";
            $w = $this->Tool_model->set_where($where);            
        }elseif(intval($action) == 3){
            $p = $this->Tool_model->set_param($params);
            $w = $this->Tool_model->set_where($where);
        }

        $prepare = "CALL sp_taxs($action,\"{$p}\",\"{$w}\")";
        $query = $this->db->query($prepare);
        $result = $query->row_array();        
        mysqli_next_result($this->db->conn_id);
        $query->free_result();

        log_message("debug", print_r($prepare, true));
        log_message("debug", print_r($result, true));   
        return $result;
    }
    */
    /* function to check data exists tax */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('taxs');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists tax of two condition */
    function check_data_exist_two_condition($param_1,$param_2,$session){
        if(strlen($session) > 2){ //When update data
            $this->db->where('tax_session !=',$session);
            $this->db->where('(`tax_column_1="'.$param_2.'" OR `tax_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($param_1);
        }

        $query = $this->db->get('taxs');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }                
}
?>