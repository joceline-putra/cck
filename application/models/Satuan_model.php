<?php
 
class Satuan_model extends CI_Model
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
        
        // $this->db->join('users_groups AS ug', 'u.id_user_group=ug.id','left');
    }

    function get_all_satuans($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        $this->db->select("unit_id, unit_name, unit_note, unit_qty, unit_user_id, unit_date_created, unit_date_updated, unit_flag");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('unit_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit,$start);
        }
        
        return $this->db->get('units')->result_array();
    }

    /*
     * Get satuan by id
     */
    function get_satuan($id)
    {
        return $this->db->get_where('units',array('unit_id'=>$id))->row_array();
    }
        
    /*
     * Get all satuan
     */
    function get_all_satuan()
    {
        $this->db->order_by('unit_name', 'asc');
        return $this->db->get('units')->result_array();
    }

    function get_all_satuans_count($params,$search)
    {
        $this->db->from('units');   
        $this->set_params($params);        
        $this->set_search($search);    
        return $this->db->count_all_results();
    }

    /*
     * function to add new satuan
     */
    function add_satuan($params)
    {
        $this->db->insert('units',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update satuan
     */
    function update_satuan($id,$params)
    {
        $this->db->where('unit_id',$id);
        return $this->db->update('units',$params);
    }
    
    /*
     * function to delete satuan
     */
    function delete_satuan($id)
    {
        return $this->db->delete('units',array('unit_id'=>$id));
    }

    function check_data_exist($params)
    {
        $this->db->where($params);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }    
}
