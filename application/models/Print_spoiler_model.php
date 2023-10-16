<?php
/*
     @AUTHOR: Joe Witaya
 */ 
 class Print_spoiler_model extends CI_Model
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
 
     function get_all_print_spoiler($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
         $this->db->select("*");
         $this->set_params($params);
         $this->set_search($search);
         $this->set_join();
 
         if ($order) {
             $this->db->order_by($order, $dir);
         } else {
             $this->db->order_by('spoiler_id', "asc");
         }
 
         if ($limit) {
             $this->db->limit($limit, $start);
         }
         
         return $this->db->get('print_spoilers')->result_array();
     }
     
     function get_all_print_spoiler_count($params){
         $this->db->from('print_spoilers');   
         $this->set_params($params);            
         return $this->db->count_all_results();
     }    
         
     /* 
         ================
         CRUD 
         ================
     */        
     
     /* function to add new print_spoiler */
     function add_print_spoiler($params)
     {
         $this->db->insert('print_spoilers',$params);
         return $this->db->insert_id();
     }
     
     /* function to get print_spoiler by id */
     function get_print_spoiler($id)
     {
         return $this->db->get_where('print_spoilers',array('spoiler_id'=>$id))->row_array();
     }
 
     /* function to update print_spoiler */
     function update_print_spoiler($id,$params)
     {
         $this->db->where('spoiler_id',$id);
         return $this->db->update('print_spoilers',$params);
     }
     
     /* function to delete print_spoiler */
     function delete_print_spoiler($id){
         return $this->db->delete('print_spoilers',array('spoiler_id'=>$id));
     }
 
     /* function to check data exists print_spoiler */
     function check_data_exist($params){
         $this->db->where($params);
         $query = $this->db->get('print_spoilers');
         if ($query->num_rows() > 0){
             return true;
         }
         else{
             return false;
         }
     }            
 }
 
  
?>
