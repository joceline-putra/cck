<?php
 
class Kontak_model extends CI_Model
{
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
                    if($key='contact_type'){
                        // var_dump($val);
                        $this->db->like($key, $val);
                    }
                }else{
                    if ($n == 1) {
                        $this->db->like($key, $val);
                    } else {
                        $this->db->or_like($key, $val);
                    }                    
                }
                $n++;
            }
            $this->db->group_end();
        }
    }

    function set_join() {
        $this->db->join('accounts AS ar', 'contacts.contact_account_receivable_account_id=ar.account_id','left');
        $this->db->join('accounts AS ap', 'contacts.contact_account_payable_account_id=ap.account_id','left');        
        $this->db->join('cities','contacts.contact_city_id=cities.city_id','left');
        $this->db->join('cities AS b','contacts.contact_birth_city_id=b.city_id','left');
        // $this->db->join('contacts AS p','contacts.contact_parent_id=p.contact_id','left');

        $this->db->join('categories AS k','contacts.contact_category_id=k.category_id','left');                
        $this->db->join('branchs AS bb','bb.branch_id=contacts.contact_branch_id','left');                
        // $this->db->join('accounts', 'contacts.contact_account_payable_account_id=accounts.account_id','left');        
    }

    function get_all_kontaks($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('contact_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('contacts')->result_array();
    }
	function get_all_kontaks_nojoin($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null, $whereIn = null){
		$this->db->select("");
		$this->set_params($params);
		$this->set_search($search);

		if ($order) {
			$this->db->order_by($order, $dir);
		} else {
			$this->db->order_by('contacts.contact_id', "asc");
		}

		if ($limit) {
			$this->db->limit($limit, $start);
		}

		return $this->db->get('contacts')->result_array();
	}
    function get_kontak($id){   
        $this->db->select("contacts.*");
        $this->db->select("ar.account_id AS receivable_account_id, ar.account_code AS receivable_account_code, ar.account_name AS receivable_account_name");
        $this->db->select("ap.account_id AS payable_account_id, ap.account_code AS payable_account_code, ap.account_name AS payable_account_name");
        $this->db->select("cities.city_name, cities.city_id");
        $this->db->select("b.city_name AS birth_city_name, b.city_id AS birth_city_id");
        $this->db->select("k.category_id AS category_id, k.category_name AS category_name");
        $this->db->select("bb.branch_id AS branch_id, bb.branch_name AS branch_name");        
        $this->db->select("p.contact_id AS parent_id, p.contact_name AS parent_name, p.contact_code AS parent_code, p.contact_phone_1 AS parent_phone");
        $this->set_join();
        $this->db->join('contacts AS p','contacts.contact_parent_id=p.contact_id','left');
        return $this->db->get_where('contacts',array('contacts.contact_id'=>$id))->row_array();
    }
    function get_kontak_custom($where){   
        return $this->db->get_where('contacts',$where)->row_array();
    }        
    function get_all_kontak(){
        $this->db->order_by('contact_id', 'desc');
        return $this->db->get('contacts')->result_array();
    }

    function get_all_kontak_count($params,$search){   
        $this->db->from('contacts');     
        $this->set_params($params);
        $this->set_search($search);        
        return $this->db->count_all_results();
    }

    function add_kontak($params){
        $this->db->insert('contacts',$params);
        return $this->db->insert_id();
    }

    function update_kontak($id,$params){
        $this->db->where('contact_id',$id);
        return $this->db->update('contacts',$params);
    }
    function update_kontak_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('contacts',$params);
    }

    function delete_kontak($id){
        return $this->db->delete('contacts',array('contact_id'=>$id));
    }
    
    function get_kontak_by_url($params){
        return $this->db->get_where('contacts',$params)->row_array();
    }    

    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('contacts');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

}
