<?php

class Login_model extends CI_Model
{
    
    function cek_login($table,$where)
    {
        return $this->db->get_where($table,$where);
    }

    function login_get_id($table,$where)
    {
        $operator = $this->db->get_where($table,$where)->row();        
        return json_decode(json_encode($operator), True);
    }    
        
    function get_login_info($user_name){
        $this->db->select('users.*, branchs.*')
                ->join('branchs','users.user_branch_id=branchs.branch_id','left');
        return $this->db->get_where('users',array('users.user_username'=>$user_name))->row_array();
    }
    function get_login_info_switch_user($user_id){
        $this->db->select('users.*, branchs.*')
                ->join('branchs','users.user_branch_id=branchs.branch_id','left');
        return $this->db->get_where('users',array('users.user_id'=>$user_id))->row_array();
    }    
    function get_group_info($id){
        return $this->db->get_where('users_groups',array('user_group_id'=>$id))->row_array();
    }
    /*
    function get_menu_group_by_session($id){
        // $wheres = 'user_menus.user_menu_user_id='.$id.' AND menus.menu_parent_id IS NULL';
        // $wheres = 'menus.menu_flag="1" AND menus.menu_parent_id="0"';         
        $wheres = 'user_menus.user_menu_user_id="'.$id.'" AND menus.menu_flag="1"';                 
        $this->db->select('`menus`.`menu_id` AS menu_group_id
                    , `menus`.`menu_name` AS `menu_group_name`
                    , `menus`.`menu_icon` AS `menu_group_icon`
                    , `menus`.`menu_link` AS `menu_group_link`       
                    , `menus`.`menu_sorting` AS `menu_group_sorting`
                    , `menus`.`menu_parent_id`')                                        
                ->from('menus')
                // ->join('menus','user_menus.user_menu_menu_id=menus.menu_id','left')
                // ->join('users','user_menus.user_menu_user_id=users.user_id','left')
                ->join('user_menus','menus.menu_id=user_menus.user_menu_menu_id','left')                
                // ->join('menu_groups','menus.menu_menu_group_id=menu_groups.menu_group_id','left')
                // ->where('user_menus.user_menu_user_id',$id)
                // ->where('menus.menu_parent_id IS NULL','')
                ->where($wheres)
                ->group_by('menus.menu_parent_id')
                ->order_by('menus.menu_sorting','ASC');

        return $this->db->get()->result_array();                    
    } 
    */
    function get_menu_group_by_session($id){
        // $wheres = 'user_menus.user_menu_user_id='.$id.' AND menus.menu_parent_id IS NULL';
        // $wheres = 'menus.menu_flag="1" AND menus.menu_parent_id="0"';         
        $wheres = 'users_menus.user_menu_user_id="'.$id.'" AND menus.menu_flag="1"';                 
        $this->db->select('`users_menus`.`user_menu_menu_parent_id` AS menu_group_id
                    , `menus`.`menu_name` AS `menu_group_name`
                    , `menus`.`menu_icon` AS `menu_group_icon`
                    , `menus`.`menu_link` AS `menu_group_link`       
                    , `menus`.`menu_sorting` AS `menu_group_sorting`
                    , `menus`.`menu_parent_id`')                                        
                ->from('users_menus')
                // ->join('menus','user_menus.user_menu_menu_id=menus.menu_id','left')
                // ->join('users','user_menus.user_menu_user_id=users.user_id','left')
                ->join('menus','users_menus.user_menu_menu_parent_id=menus.menu_id','left')                
                // ->join('menu_groups','menus.menu_menu_group_id=menu_groups.menu_group_id','left')
                // ->where('user_menus.user_menu_user_id',$id)
                // ->where('menus.menu_parent_id IS NULL','')
                ->where($wheres)
                ->group_by('users_menus.user_menu_menu_parent_id')
                ->order_by('menus.menu_sorting','ASC');

        return $this->db->get()->result_array();                    
    }        
    function get_menu_by_session($menu_group_id,$id){
        
        $where = array(
            'users_menus.user_menu_menu_parent_id'=> $menu_group_id,
            'menus.menu_flag'=> 1,
            'users_menus.user_menu_flag'=> 1, 
            // 'user_menu.id_menu'=> $sub_menu,
            'users_menus.user_menu_action'=> 1
        );

        $this->db->select('`menus`.`menu_name`
                    , `menus`.`menu_link`
                    , `menus`.`menu_icon`                    
                    , `menus`.`menu_flag` AS `menu_flag`
                    , `menus`.`menu_id` AS menu_group_id
                    , `menus`.`menu_name` AS menu_group_name
                    , `menus`.`menu_icon` AS menu_group_icon
                    , `users_menus`.`user_menu_menu_id` AS menu_id
                    , `users_menus`.`user_menu_action`
                    , `users_menus`.`user_menu_flag` AS `user_menu_flag`
                    , `users`.`user_id`
                    , `users`.`user_username`')
                ->from('users_menus')
                ->join('menus','users_menus.user_menu_menu_id=menus.menu_id','left')
                ->join('users','users_menus.user_menu_user_id=users.user_id','left')
                // ->join('menu_groups','menus.menu_menu_group_id=menu_groups.menu_group_id','left')
                ->where('users.user_id',$id)
                // ->where('user_menu.action','view')
                ->where($where)
                ->order_by('menus.menu_sorting','asc');
                // ->order BY menu_group.id ASC, menu.nama ASC;
                 // ->from('menu');
        return $this->db->get()->result_array();
    }

    /* Move to Below 30 Oktober 2023
    function get_menu_group_by_user_menu($user){
        $query= $this->db->query("
            SELECT mm.menu_parent_id, mr.menu_name, mr.menu_link, mr.menu_icon, mr.menu_flag, mr.menu_sorting FROM 
            (
                SELECT * FROM users_menus AS um
                LEFT JOIN menus AS m ON um.user_menu_menu_id=m.menu_id
                WHERE um.user_menu_user_id=$user AND um.user_menu_action=1 
                GROUP BY m.menu_id
            ) AS mm
            LEFT JOIN 
            (
                SELECT * FROM menus GROUP BY menu_id
            ) AS mr
            ON mm.menu_parent_id=mr.menu_id
            WHERE mr.menu_flag=1
            GROUP BY mm.menu_parent_id
            ORDER BY mr.menu_sorting ASC
        ");

        return $query->result_array();                   
    }
    */
    function get_menu_group_by_user_menu($user){
        $prepare = "SELECT m.menu_id, m.menu_name, m.menu_link, m.menu_icon, m.menu_flag, m.menu_sorting,
        um.user_menu_flag, IFNULL(um.menu_active_count,0) AS menu_active_count
        FROM menus AS m
        LEFT JOIN (
            SELECT m.menu_parent_id, m.menu_id, m.menu_name, um.`user_menu_flag`, COUNT(*) AS menu_active_count
            FROM users_menus AS um
            LEFT JOIN menus AS m ON um.user_menu_menu_id = m.menu_id
            WHERE um.user_menu_user_id = $user
            AND um.user_menu_action = 1 AND um.user_menu_flag > 0
            GROUP BY menu_parent_id
            ORDER BY menu_parent_id
        ) AS um ON m.`menu_id`=um.menu_parent_id
        WHERE m.menu_parent_id=0 AND menu_flag=1
        ORDER BY m.`menu_sorting` ASC";
        // log_message('debug',$prepare);
        $query= $this->db->query($prepare);

        return $query->result_array();                   
    }    
    function get_menu_child_by_user_menu($parent,$user){
        $query= $this->db->query("
            SELECT menu_parent_id, menu_id, menu_name, menu_link, menu_flag, menu_sorting, menu_icon, users_menus.user_menu_flag
            FROM menus 
            LEFT JOIN users_menus ON menus.menu_id=users_menus.user_menu_menu_id
            WHERE users_menus.user_menu_user_id=$user
            AND menus.menu_parent_id=$parent
            AND menus.menu_flag=1
            GROUP BY menus.menu_id
            ORDER BY menus.menu_sorting ASC
        ");

        return $query->result_array();                   
    }        
}
