<?php
 
class Konfigurasi_model extends CI_Model
{
    function __construct()
    {
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
        //sql select * from users
        //left join user_roles on user_roles.role_id=users.user_role;
        
        // $this->db->join('user AS u', 't.id_user=u.id','left');
    }

    function get_all_datas($tables, $params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $tables .= $tables.' AS t';
        if($tables = 'units'){
            $this->db->select("t.*");
            $this->db->select("u.user_username");
            // $this->db->from($tables);       
            $this->db->join('users AS u', 't.unit_user_id=u.user_id','left');           

            $this->set_params($params);
            $this->set_search($search);


            // echo 9;die;
            if ($order) {
                $this->db->order_by($order, $dir);
            } else {
                $this->db->order_by('t.unit_name', "asc");
            }

            if ($limit) {
                $this->db->limit($limit, $start);
            }
            
            return $this->db->get($tables)->result_array();
        }        
        else if($tables == "locations"){
            $this->db->select("t.*");
            $this->db->select("us.user_username");
            $this->db->from($table);               
            $this->db->join('user AS u', 't.location_user_id=u.user_id','left');
        }
        else if($tables == "menus"){
            $this->db->select("t.*, ma.nama AS group_nama");
            $this->db->select("u.user_username");
            $this->db->from($table);               
            $this->db->join('menu_groups AS m', 't.menu_menu_group_id=m.menu_group_id','s');
            $this->db->join('users AS u', 't.menu_user_id=u.user_id','left');        
        }            
        // $this->set_join();
    }

    function get_data($table,$id)
    {
        $table_to_prefix = substr($table, 0, -1); 
        $table_column = $table.'.'.$table_to_prefix.'_user_id';

        if($table=='branchs'){
            $this->db->select($table.".*, users.user_id, users.user_username, branchs_specialists.*, users_groups.*");
            $this->db->join("users",$table_column."=users.user_id","left");
            $this->db->join("branchs_specialists","branchs.branch_specialist_id=branchs_specialists.specialist_id","left");
            $this->db->join("users_groups","users.user_user_group_id=users_groups.user_group_id","left");
        }else{
            $this->db->select($table.".*, users.user_id, users.user_username");
            $this->db->join("users",$table_column."=users.user_id","left");
        }
        return $this->db->get_where($table,array($table_to_prefix.'_id'=>$id))->row_array();
    }

    function get_all_data($table)
    {
        $wheres='';
        if($table == 'menu_groups'){
            $wheres = 'menus.menu_parent_id = 0';
            $table = 'menus';
        }
        $table_to_prefix = substr($table, 0, -1);
        
        $this->db->order_by($table_to_prefix.'_id', 'desc');
        $this->db->where($wheres);
        return $this->db->get($table)->result_array();
    }

    function get_all_data_group($table)
    {
        $table_to_prefix = substr($table, 0, -1);
        $this->db->order_by($table_to_prefix.'_name', 'asc');
        return $this->db->get($table)->result_array();
    }

    function get_all_datas_count($table,$params)
    {
        $this->db->from($table.' AS t');   
        // if($table=="satuan"){
            $this->set_params($params);
        // }
        // if($table=="lokasi"){
        //     $this->set_params('t.'.$params);
        // }   
        // if($table=="menu"){
        //     $this->set_params('t.'.$params);
        // }                            
        return $this->db->count_all_results();
    }
   
    function add_data($table,$params)
    {
        $this->db->insert($table,$params);
        return $this->db->insert_id();
    }
    
    function update_data($table,$id,$params)
    {
        $table_to_prefix = substr($table, 0, -1);                 
        $this->db->where($table_to_prefix.'_id',$id);
        return $this->db->update($table,$params);
    }

    function delete_data($table,$id)
    {
        $table_to_prefix = substr($table, 0, -1);            
        return $this->db->delete($table,array($table_to_prefix.'_id'=>$id));
    }

    function check_data_exist($table,$params)
    {
        $this->db->where($params);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }    
}
