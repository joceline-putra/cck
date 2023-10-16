<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class App_voucher_model extends CI_Model
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

    function get_all_app_voucher($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('app_voucher_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('app_vouchers')->result_array();
    }
    
    function get_all_app_voucher_count($params){
        $this->db->from('app_vouchers');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
        
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new app_voucher */
    function add_app_voucher($params)
    {
        $this->db->insert('app_vouchers',$params);
        return $this->db->insert_id();
    }
    
    /* function to get app_voucher by id */
    function get_app_voucher($id)
    {
        return $this->db->get_where('app_vouchers',array('app_voucher_id'=>$id))->row_array();
    }

    /* function to update app_voucher */
    function update_app_voucher($id,$params)
    {
        $this->db->where('app_voucher_id',$id);
        return $this->db->update('app_vouchers',$params);
    }
    
    /* function to delete app_voucher */
    function delete_app_voucher($id){
        return $this->db->delete('app_vouchers',array('app_voucher_id'=>$id));
    }

    /* function to check data exists app_voucher */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('app_vouchers');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}


?>