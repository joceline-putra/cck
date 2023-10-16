<?php 

/*
    @AUTHOR: Joe Witaya
*/ 
class Supplier_model extends CI_Model
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

    function get_all_supplier($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('supplier_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('suppliers')->result_array();
    }
    /* function get_all_supplier($search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        $prepare = "CALL sp_suppliers_data('$search',$limit,$start,'$order','$dir')";
        $query = $this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        $query->free_result();

        // log_message("debug", print_r($prepare, true));
        return $result;
    } */  
    function get_all_supplier_count($params){
        $this->db->from('suppliers');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new supplier */
    function add_supplier($params){
        $this->db->insert('suppliers',$params);
        return $this->db->insert_id();
    }
    
    /* function to get supplier by id */
    function get_supplier($id){
        $this->set_join();
        return $this->db->get_where('suppliers',array('supplier_id'=>$id))->row_array();
    }
    function get_supplier_custom($where){
        $this->set_join();
        return $this->db->get_where('suppliers',$where)->row_array();
    }

    /* function to update supplier */
    function update_supplier($id,$params){
        $this->db->where('supplier_id',$id);
        return $this->db->update('suppliers',$params);
    }
    function update_supplier_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('suppliers',$params);
    }
    
    /* function to delete supplier */
    function delete_supplier($id){
        return $this->db->delete('suppliers',array('supplier_id'=>$id));
    }
    function delete_supplier_custom($where){
        return $this->db->delete('suppliers',$where);
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
    function sp_supplier($action = null, $params = null, $where = null) {
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

        $prepare = "CALL sp_suppliers($action,\"{$p}\",\"{$w}\")";
        $query = $this->db->query($prepare);
        $result = $query->row_array();        
        mysqli_next_result($this->db->conn_id);
        $query->free_result();

        log_message("debug", print_r($prepare, true));
        log_message("debug", print_r($result, true));   
        return $result;
    }
    */
    /* function to check data exists supplier */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('suppliers');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists supplier of two condition */
    function check_data_exist_two_condition($param_1,$param_2,$session){
        if(strlen($session) > 2){ //When update data
            $this->db->where('supplier_session !=',$session);
            $this->db->where('(`supplier_column_1="'.$param_2.'" OR `supplier_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($params);
        }

        $query = $this->db->get('suppliers');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }                
}


?>