<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class User_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function set_params($params) {
        if ($params) {
            foreach ($params as $k => $v) {
                $this->db->where($k, $v);
            }
        }
    }

    function set_search($search) {
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
        $this->db->join('users_groups AS ug', 'u.user_user_group_id=ug.user_group_id','left');
        $this->db->join('branchs AS b','u.user_branch_id=b.branch_id','left');
    }

    function get_all_users($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("u.user_id, u.user_type, 
            u.user_code,
            u.user_activation_code, 
            u.user_username, 
            u.user_fullname, 
            u.user_email_1,
            u.user_phone_1,
            u.user_flag, 
            ug.user_group_name");
        $this->db->select("b.*");
        $this->db->select("fn_time_ago(u.user_date_last_login) AS time_ago");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('u.user_username', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('users AS u')->result_array();
    }

    function get_user($id){
        $this->db->join('users_groups AS ug', 'u.user_user_group_id=ug.user_group_id','left');
        $this->db->join('branchs AS b','u.user_branch_id=b.branch_id','left');        
        return $this->db->get_where('users AS u',array('user_id'=>$id))->row_array();
    }
    function get_user_custom($where){
        return $this->db->get_where('users',$where)->row_array();
    }    
    function get_user_by_email($email){
        return $this->db->get_where('users',array('user_email_1'=>$email))->row_array();
    }       

    function get_all_user(){
        $this->db->order_by('user_username', 'asc');
        return $this->db->get('users')->result_array();
    }

    /*
    Get all user_group
    */
    function get_all_user_group(){
        $this->db->order_by('user_group_name', 'asc');
        return $this->db->get('users_groups')->result_array();
    }

    function get_all_users_count($params){
        $this->db->from('users');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }

    /*
    Data Table user
    */
    function get_datatable(){
        $this->db->select('users.*, users.user_id, user_groups.name AS group_name');
        $this->db->from('users');  
        $this->db->join('users_groups','user_groups.id=user.user_user_group_id','left');        
        return $this->db->get();
    }
        
    /*
    function to add new user
    */
    function add_user($params){
        $this->db->insert('users',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update user
     */
    function update_user($id,$params){
        $this->db->where('user_id',$id);
        return $this->db->update('users',$params);
    }
    
    /*
     * function to delete user
     */
    function delete_user($id){
        return $this->db->delete('users',array('user_id'=>$id));
    }


    //** HAK AKSES **//

    /*
     * function get user menu info
     */
    function get_user_menu_info($id_user, $id_menu){      
        $this->db->select('users_menus.*, users.user_username as username, menus.menu_name as nama_menu');
        $this->db->from('users_menus');
        $this->db->join('users','user.user_id = users_menus.user_menu_user_id','left'); 
        $this->db->join('menus','menu.id = users_menus.user_menu_menu_id','left');         
        $this->db->where('users_menus.user_id',$id_user);
        $this->db->where('users_menus.menu_id',$id_menu);
        return $this->db->get()->row_array();           
    }

    /*
     * function check user menu
     */
    function check_user_have_menu($menu, $user){
        $sub_query = 'SELECT user_menu_user_id, user_menu_menu_id FROM users_menu 
        WHERE user_menu_menu_id = "'.$menu.'" AND user_menu_user_id = "'.$user.'"';
        $this->db->select("EXISTS($sub_query) AS menu_status");
        return $this->db->get()->row_array();      
    }
    

    function get_user_menu($id){
        return $this->db->get_where('users_menus',array('menu_id'=>$id))->row_array();
    }
    function add_user_menu($params){
        $this->db->insert('users_menus',$params);
        return $this->db->insert_id();
    }

    function get_user_menu_by_id_usermenu($id_user, $id_menu){
        return $this->db->get_where('users_menus',array('user_menu_user_id'=>$id_user, 'user_menu_menu_id'=>$id_menu ))->row_array();
    }
    
    /*
     * function to delete user_menu
     */
    function delete_user_menu($id){
        return $this->db->delete('users_menu',array('menu_id'=>$id));
    }

    function get_user_menu_akses($id_user){      
        $this->db->select('user_menus.*, users.user_username as username, menus.menu_name as nama_menu, menu_groups.name AS nama_menu_group');
        $this->db->from('users_menus');
        $this->db->join('users','users.user_id = user_menus.user_menu_user_id','left'); 
        $this->db->join('menus','menus.id = user_menus.user_menu_menu_id','left'); 
        $this->db->join('menu_groups','menus.menu_menu_group_id = menu_groups.menu_group_id','left');                 
        $this->db->where('user_menus.user_id',$id_user);
        return $this->db->get()->result_array();           
    }

    function get_all_user_approval(){
        $this->db->where('user_status',1);
        $this->db->order_by('user_first_name', 'asc');
        return $this->db->get('users')->result_array();
    }

    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }  
    function check_data_exist_register($email,$telepon){
        $this->db->where('user_email_1',$email);
        $this->db->or_where('user_phone_1',$telepon);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }  
}
