<?php 
    /* 
        @Author: Yoceline Witaya 
    */ 
    class Voucher_model extends CI_Model{
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
            $this->db->join('users','voucher_user_id=user_id','left');
        }

        function set_select(){
            $this->db->select("vouchers.*, users.user_id, users.user_user_group_id, users.user_username, users.user_fullname");
            $this->db->select("DATE_FORMAT(voucher_date_created,'%d-%b-%Y, %H:%i') AS voucher_date_created_format");
            $this->db->select("fn_time_ago(voucher_date_created) AS voucher_date_created_time_ago");
            $this->db->select("CASE WHEN voucher_type = 1 THEN 'Voucher' WHEN voucher_type = 2 THEN 'Promo' WHEN voucher_type = 3 THEN 'Promo Produk' ELSE '' END AS voucher_type_name");
            $this->db->select("CASE WHEN voucher_flag = 1 THEN 'Aktif' WHEN voucher_flag = 0 THEN 'Nonaktif' WHEN voucher_flag = 4 THEN 'Deleted' ELSE '' END AS voucher_flag_name");
            $this->db->select("CONCAT(DATE_FORMAT(voucher_date_start,'%d-%b-%Y'),' sd ',DATE_FORMAT(voucher_date_end,'%d-%b-%Y')) AS voucher_period");
            $this->db->select("DATE_FORMAT(voucher_date_end,'%d-%b-%Y') AS voucher_period_end_format");            
            $this->db->select("DATEDIFF(voucher_date_end,NOW()) AS voucher_expired_day");
            $this->db->select("CASE WHEN DATEDIFF(voucher_date_end,NOW()) > -1 THEN CASE WHEN voucher_flag = 1 THEN 'Available' ELSE 'Expired' END WHEN DATEDIFF(voucher_date_end,NOW()) < 0 THEN CASE WHEN voucher_flag = 1 THEN 'Expired' ELSE 'Expired' END END AS voucher_status");
        }

        function get_all_voucher($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
            $this->set_select();
            $this->set_params($params);
            $this->set_search($search);
            $this->set_join();

            if ($order) {
                $this->db->order_by($order, $dir);
            } else {
                $this->db->order_by('voucher_id', "asc");
            }

            if ($limit) {
                $this->db->limit($limit, $start);
            }
            
            return $this->db->get('vouchers')->result_array();
        }  
        function get_all_voucher_count($params,$search){
            $this->db->from('vouchers');
            $this->set_params($params);
            $this->set_search($search);
            return $this->db->count_all_results();
        }

        /* 
            ================
            CRUD Voucher
            ================
        */        
        
        /* function to add new voucher */
        function add_voucher($params){
            $this->db->insert('vouchers',$params);
            return $this->db->insert_id();
        }
        
        /* function to get voucher by id */
        function get_voucher($id){
            $this->set_select();
            $this->set_join();
            return $this->db->get_where('vouchers',array('voucher_id'=>$id))->row_array();
        }
        function get_voucher_custom($where){
            $this->set_select();
            $this->set_join();
            return $this->db->get_where('vouchers',$where)->row_array();
        }
        function get_voucher_custom_result($where){
            $this->set_select();
            $this->set_join();
            return $this->db->get_where('vouchers',$where)->result_array();
        }

        /* function to update voucher */
        function update_voucher($id,$params){
            $this->db->where('voucher_id',$id);
            return $this->db->update('vouchers',$params);
        }
        function update_voucher_custom($where,$params){
            $this->db->where($where);
            return $this->db->update('vouchers',$params);
        }

        /* function to delete voucher */
        function delete_voucher($id){
            return $this->db->delete('vouchers',array('voucher_id'=>$id));
        }
        function delete_voucher_custom($where){
            return $this->db->delete('vouchers',$where);
        }

        /* function to check data exists voucher */
        function check_data_exist($params){
            $this->db->where($params);
            $query = $this->db->get('vouchers');
            if ($query->num_rows() > 0){
                return true;
            }
            else{
                return false;
            }
        }
        /* function to check data exists voucher of two condition */
        function check_data_exist_two_condition($param_1,$param_2,$session){
            if(strlen($session) > 2){ //When update data
                $this->db->where('voucher_session !=',$session);
                $this->db->where('(`voucher_column_1="'.$param_2.'" OR `voucher_column_2`="'.$param_2.'")');
            }else{ //When create data
                $this->db->where($param_1);
            }

            $query = $this->db->get('vouchers');
            if ($query->num_rows() > 0){
                return true;
            }
            else{
                return false;
            }
        }
    }
    ?>