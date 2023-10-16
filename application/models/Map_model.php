<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Map_model extends CI_Model
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
    function set_join(){
        /* $this->db->join('','','left'); */
    }

    /* Province = Provinsi */
    function get_all_province($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        $this->db->join('cities','province_id=city_province_id','left');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('province_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('provinces')->result_array();
    }  
    function get_all_province_count($params){
        $this->db->from('provinces');   
        $this->db->join('cities','province_id=city_province_id','left');        
        $this->set_params($params);            
        return $this->db->count_all_results();
    } 

    /* Cities = Kota & Kabupaten */
    function get_all_city($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        $this->db->join('provinces','city_province_id=province_id','left');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('city_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('cities')->result_array();
    }  
    function get_all_city_count($params){
        $this->db->from('cities'); 
        $this->db->join('provinces','city_province_id=province_id','left');          
        $this->set_params($params);            
        return $this->db->count_all_results();
    }     

    /* District = Kecamatan */
    function get_all_district($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        $this->db->join('cities','district_city_id=city_id','left');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('district_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('districts')->result_array();
    }
    function get_all_district_count($params){
        $this->db->from('districts'); 
        $this->db->join('cities','district_city_id=city_id','left');        
        $this->set_params($params);            
        return $this->db->count_all_results();
    }

    /* Village = Kelurahan & Desa */
    function get_all_village($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        $this->db->join('districts','village_district_id=district_id','left');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('village_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('villages')->result_array();
    }
    function get_all_village_count($params){
        $this->db->from('villages'); 
        $this->db->join('districts','village_district_id=village_id','left');        
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    

    /* Fetch Row (Province, City, District, Village) */
    function get_province($params){
        $this->db->join('cities','province_id=city_province_id','left');    	
        return $this->db->get_where('provinces',$params)->row_array();
    } 
    function get_city($params){
        $this->db->join('provinces','city_province_id=province_id','left');     
        return $this->db->get_where('cities',$params)->row_array();
    }
    function get_district($params){
        $this->db->join('cities','district_city_id=city_id','left');      
        return $this->db->get_where('cities',$params)->row_array();
    }
    function get_village($params){
        $this->db->join('districts','village_district_id=village_id','left');
        return $this->db->get_where('provinces',$params)->row_array();
    }           
}


?>