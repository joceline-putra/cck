<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Printer_model extends CI_Model{
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
        /* $this->db->join('','','left'); */
    }

    function set_join_item(){
        $this->db->join('printers p','i.printer_parent_id=p.printer_id','left');
    }

    function set_select(){
        $this->db->select("*");        
    }

    function set_select_item(){
        $this->db->select("p.printer_name, p.printer_ip, p.printer_type, p.printer_flag");
        $this->db->select("CASE WHEN p.printer_type=1 THEN 'Deskjet' WHEN p.printer_type=2 THEN 'Dot Matrik' WHEN p.printer_type=3 THEN 'Label Works' WHEN p.printer_type=4 THEN 'Receipt Thermal' ELSE 'Undefined' END AS printer_type_name");
        $this->db->select("i.printer_paper_design, i.printer_paper_width, i.printer_paper_height");
    }

    function get_all_printer($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('printer_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('printers')->result_array();
    }  
    function get_all_printer_count($params){
        $this->db->from('printers');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }
    function get_all_printer_item($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_item();
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join_item();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('i.printer_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('printer_items i')->result_array();
    }  
    function get_all_printer_item_count($params){
        $this->db->from('printer_items');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    

    /* 
        ================
        CRUD Printer
        ================
    */        
    
    /* function to add new printer */
    function add_printer($params){
        $this->db->insert('printers',$params);
        return $this->db->insert_id();
    }
    
    /* function to get printer by id */
    function get_printer($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('printers',array('printer_id'=>$id))->row_array();
    }
    function get_printer_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('printers',$where)->row_array();
    }
    function get_printer_custom_result($where){
        $this->set_join();
        $this->set_join();
        return $this->db->get_where('printers',$where)->result_array();
    }

    /* function to update printer */
    function update_printer($id,$params){
        $this->db->where('printer_id',$id);
        return $this->db->update('printers',$params);
    }
    function update_printer_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('printers',$params);
    }

    /* function to delete printer */
    function delete_printer($id){
        return $this->db->delete('printers',array('printer_id'=>$id));
    }
    function delete_printer_custom($where){
        return $this->db->delete('printers',$where);
    }

    /* function to check data exists printer */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('printers');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists printer of two condition */
    function check_data_exist_two_condition($param_1,$param_2,$session){
        if(strlen($session) > 2){ //When update data
            $this->db->where('printer_session !=',$session);
            $this->db->where('(`printer_column_1="'.$param_2.'" OR `printer_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($param_1);
        }

        $query = $this->db->get('printers');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    
    /* 
        ================
        CRUD Printer ITEM
        ================
    */
    
    /* function to add new printer items */
    function add_printer_item($params){
        $this->db->insert('printer_items',$params);
        return $this->db->insert_id();
    }
    
    /* function to get printer items by id */
    function get_printer_item($id){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('printers AS i',array('i.printer_id'=>$id))->row_array();
    }
    function get_printer_item_custom($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('printer_items',$where)->row_array();
    }
    function get_printer_item_custom_result($where){
        $this->set_select_item();
        $this->set_join_item();
        return $this->db->get_where('printer_items',$where)->result_array();
    }

    /* function to update printer items */
    function update_printer_item($id,$params){
        $this->db->where('printer_item_id',$id);
        return $this->db->update('printer_items',$params);
    }
    function update_printer_item_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('printer_items',$params);
    }    
    
    /* function to delete printer items */
    function delete_printer_item($id){
        return $this->db->delete('printer_items',array('printer_item_id'=>$id));
    }
    function delete_printer_item_custom($where){
        return $this->db->delete('printer_items',$where);
    }

    /* function to check data exists printer_items */
    function check_data_exist_items($params){
        $this->db->where($params);
        $query = $this->db->get('printer_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists printer of two condition */
    function check_data_exist_items_two_condition($param_1,$param_2,$session){
        if(strlen($session) > 2){ //When update data
            $this->db->where('printer_item_session !=',$session);
            $this->db->where('(`printer_item_column_1="'.$param_2.'" OR `printer_item_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($param_1);
        }

        $query = $this->db->get('printer_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }               
}
?>