<?php
 
class Aktivitas_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function add_aktivitas($params){
        $this->db->insert('activities',$params);
        return $this->db->insert_id();
    }
    // function get_aktivitas($start,$end,$user,$limit_start,$limit_end){
    function get_aktivitas($start,$end,$user,$limit_start,$limit_end){    
        $date1 = $start.' 00:00:00';
        $date2 = $end.' 23:59:00';

        if($user==0){
            $where = "WHERE a.activity_date_created > '".$date1."' AND a.activity_date_created < '".$date2."' AND a.activity_flag=1";            
        }else{
            $where = "WHERE a.activity_date_created > '".$date1."' AND a.activity_date_created < '".$date2."' AND a.activity_flag=1 AND a.activity_user_id=".$user."";
        }
        $query = $this->db->query("
            SELECT a.*, u.user_username
            FROM activities AS a
            LEFT JOIN users AS u ON (a.activity_user_id=u.user_id)
            $where
            ORDER BY a.activity_date_created DESC 
            LIMIT $limit_start, $limit_end
        ");        
        return $query->result_array();
    }
}
