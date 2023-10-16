<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class User_menu_model extends CI_Model
{
    function __construct(){
        parent::__construct();
    }
    
     /* function to update user_menu */
    function update_user_menu($where,$params)
    {
        $this->db->where($where);
        return $this->db->update('users_menus',$params);
    }

    /* function to check data exists user_menu */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('users_menus');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }            
}



?>