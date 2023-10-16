<?php 
/* 
    @Author: Yoceline Witaya 
*/ 
class Asset_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    function set_select(){
        $this->db->select("
            `product_id`,`product_flag`,`product_date_created`,`product_date_updated`, 
            `product_type`, IF(`product_type`=3,'Inventaris','Error') AS product_type_name,
            `product_image`,
            `product_asset_name`,
            `product_asset_code`,
            `product_asset_note`,
            `product_asset_acquisition_date`,
            `product_asset_acquisition_value`,
            `product_asset_dep_flag`,
            `product_asset_dep_method`,
            `product_asset_dep_period`,
            `product_asset_dep_percentage`,
                `product_asset_fixed_account_id`,
                `product_asset_cost_account_id`,
                `product_asset_depreciation_account_id`,
                `product_asset_accumulated_depreciation_account_id`,
            `product_asset_accumulated_depreciation_value`,
            `product_asset_accumulated_depreciation_date`
        ");  
        $this->db->select("
            f.account_id as fixed_account_id, f.account_code as fixed_account_code, f.account_name as fixed_account_name,
            c.account_id as cost_account_id, c.account_code as cost_account_code, c.account_name as cost_account_name,
            d.account_id as depreciation_account_id, d.account_code as depreciation_account_code, d.account_name as depreciation_account_name,
            a.account_id as accumulated_account_id, a.account_code as accumulated_account_code, a.account_name as accumulated_account_name            
        ");
    }
    function set_select_depreciation(){
        $this->db->select("*");
    }
    function set_params($params){
        $this->db->where('product_type',3);
        if ($params) {
            foreach ($params as $k => $v) {
                $this->db->where($k, $v);
            }
        }
    }
    function set_params_depreciation($params){
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
        $this->db->join('accounts as f','product_asset_fixed_account_id=f.account_id','left');
        $this->db->join('accounts as c','product_asset_cost_account_id=c.account_id','left');
        $this->db->join('accounts as d','product_asset_depreciation_account_id=d.account_id','left');
        $this->db->join('accounts as a','product_asset_accumulated_depreciation_account_id=a.account_id','left');                        
    }
    function set_join_depreciation() {
        $this->db->join('journals_items','journal_item_journal_id=journal_id AND journal_item_debit > 0','right');          
        $this->db->join('accounts','journal_item_account_id=account_id','left');           
    }

    function get_all_asset($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        // $this->db->select("*");
        $this->set_select();
        // $this->db->from("products");
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('product_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('products')->result_array();
    }
    function get_all_depreciation($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->set_select_depreciation();
        $this->set_params_depreciation($params);
        $this->set_search($search);
        $this->set_join_depreciation();
        
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('journal_date_created', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('journals')->result_array();
    }    
    /* function get_all_asset($search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        $prepare = "CALL sp_assets_data('$search',$limit,$start,'$order','$dir')";
        $query = $this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        $query->free_result();

        // log_message("debug", print_r($prepare, true));
        return $result;
    } */  
    function get_all_asset_count($params){
        $this->db->from('products');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }    
    function get_all_depreciation_count($params){
        $this->db->from('journals');   
        $this->set_params_depreciation($params);            
        return $this->db->count_all_results();
    }   
    /* 
        ================
        CRUD 
        ================
    */        
    
    /* function to add new asset */
    function add_asset($params){
        $this->db->insert('products',$params);
        return $this->db->insert_id();
    }
    
    /* function to get asset by id */
    function get_asset($id){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('products',array('product_id'=>$id))->row_array();
    }
    function get_asset_custom($where){
        $this->set_select();
        $this->set_join();
        return $this->db->get_where('products',$where)->row_array();
    }

    /* function to update asset */
    function update_asset($id,$params){
        $this->db->where('product_id',$id);
        return $this->db->update('products',$params);
    }
    function update_asset_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('products',$params);
    }
    
    /* function to delete asset */
    function delete_asset($id){
        return $this->db->delete('products',array('asset_id'=>$id));
    }
    function delete_asset_custom($where){
        return $this->db->delete('products',$where);
    }

    /* function to check data exists asset */
    function check_data_exist($params){
        $this->db->where('product_type',3);
        $this->db->where($params);
        $query = $this->db->get('products');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* function to check data exists asset of two condition */
    function check_data_exist_two_condition($param_1,$param_2,$session){
        if(strlen($session) > 2){ //When update data
            $this->db->where('asset_session !=',$session);
            $this->db->where('(`asset_column_1="'.$param_2.'" OR `asset_column_2`="'.$param_2.'")');
        }else{ //When create data
            $this->db->where($param_1);
        }

        $query = $this->db->get('products');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }                
}
?>