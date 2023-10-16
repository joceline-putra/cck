<?php
 
class Produk_model extends CI_Model{
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
        //sql select * from users
        //left join user_roles on user_roles.role_id=users.user_role;
        $this->db->join('users', 'product_user_id=user_id','left');
        $this->db->join('contacts', 'product_contact_id=contact_id','left');        
        $this->db->join('references', 'product_ref_id=ref_id','left');
        $this->db->join('categories', 'product_category_id=category_id','left');
        $this->db->join('cities', 'product_city_id=city_id','left');            
        $this->db->join('provinces', 'product_province_id=province_id','left');                        
    }

    function get_all_produks($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("product_id, product_branch_id, product_ref_id, product_type, product_barcode, product_code ,product_name, product_unit, product_note, IFNULL(product_price_buy,0) AS product_price_buy, IFNULL(product_price_sell,0) AS product_price_sell, FORMAT(product_price_sell,0) product_price_sell_format, product_min_stock_limit, product_price_promo, FORMAT(product_price_promo,0) product_price_promo_format, product_max_stock_limit, product_fee_1,product_fee_2,product_manufacture, product_user_id, product_date_created, product_date_updated, product_flag, product_image, product_with_stock, IFNULL(product_stock,0) AS product_stock"); 
        $this->db->select('product_url, product_bedroom, product_bathroom, product_garage, product_square_size, product_building_size, product_city_id, product_province_id, product_contact_id, product_reminder, product_reminder_date, DATE_FORMAT(`product_reminder_date`,"%d-%b-%y, %H:%i") AS product_reminder_date_format, DATEDIFF(NOW(),product_reminder_date) AS product_reminder_date_next, product_category_id, product_barcode');
        $this->db->select('cities.*, provinces.*, contact_name');
        $this->db->select('categories.*');
        $this->db->select('references.*');
        $this->db->select('users.user_username');
        
        // if(($params['product_type']==4) or ($params['product_type']==5)){ //Tindakan relasi ke Referensi
        //     $this->db->select("ref_id AS referensi_id, ref_code AS referensi_kode, ref_name AS referensi_nama");
        // }

        // if($params['product_type']==2){ //Inventaris relasi ke Referensi
        //     $this->db->select("ref_id AS referensi_id, ref_code AS referensi_kode, ref_name AS referensi_nama");
        // }

        // if($params['product_type']==3){ //Jasa relasi ke Referensi
        //     $this->db->select("ref_id AS referensi_id, ref_code AS referensi_kode, ref_name AS referensi_nama");
        // }  

        // if($params['product_type']==4){ //tindakan relasi ke Referensi
        //     $this->db->select("ref_id AS referensi_id, ref_code AS referensi_kode, ref_name AS referensi_nama");
        // }  

        // if($params['product_type']==5){ //Labotorium relasi ke Referensi
        //     $this->db->select("ref_id AS referensi_id, ref_code AS referensi_kode, ref_name AS referensi_nama");
        // }        

        //Kategori Barang Relasi ke Referensi
        /*$product_reference = !empty($params['product_ref_id']) ? $params['product_ref_id'] : 0;
        if($product_reference > 0){
            if(($params['product_type']==1) and ($params['product_ref_id'] > 0)){
                $this->db->select("ref_id AS referensi_id, ref_code AS referensi_kode, ref_name AS referensi_nama");
            }        
        }*/

        $this->db->from("products");

        // if($params['ref_type']==8){

        // }else{
            //$this->set_join();
        // }

        $this->set_join();
        $this->set_params($params);
        $this->set_search($search);

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('product_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        // log_message('debug',$limit,$start);
        return $this->db->get()->result_array();
    }
    function get_all_produks_datatable($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->from("products");
        $this->set_params($params);
        $this->set_search($search);

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('product_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result_array();
    }
    function get_all_produks_count($params,$search){
        $this->db->from('products');   
        $this->set_join();
        $this->set_params($params);      
        $this->set_search($search);                
        return $this->db->count_all_results();
    }
    function get_produk($id){
        $this->db->select('products.*, categories.*, IFNULL(product_image,0) AS product_images')
                ->select('buy.account_id AS buy_account_id, buy.account_code AS buy_account_code')
                ->select('buy.account_name AS buy_account_name')
                ->select('sell.account_id AS sell_account_id, sell.account_code AS sell_account_code')
                ->select('sell.account_name AS sell_account_name')
                ->select('inventory.account_id AS inventory_account_id, inventory.account_code AS inventory_account_code')
                ->select('inventory.account_name AS inventory_account_name')
                ->select('references.*');
        $this->db->join('categories','products.product_category_id=categories.category_id','left')
                ->join('accounts AS buy','products.product_buy_account_id=buy.account_id','left')
                ->join('accounts AS sell','products.product_sell_account_id=sell.account_id','left')
                ->join('accounts AS inventory','products.product_inventory_account_id=inventory.account_id','left');                 
        $this->db->join('references','products.product_ref_id=references.ref_id','left');    
        return $this->db->get_where('products',array('product_id'=>$id))->row_array();
    }
    function get_produk_quick($id){
        $this->db->select('product_id, product_branch_id, product_code, product_name, product_unit, product_flag'); 
        return $this->db->get_where('products',array('product_id'=>$id))->row_array();
    }
    function get_all_produk(){
        $this->db->order_by('product_id', 'desc');
        return $this->db->get('products')->result_array();
    }
    function get_all_produk_group(){
        $this->db->order_by('user_group_name', 'asc');
        return $this->db->get('users_groups')->result_array();
    }

    
    function get_all_produks_min($column){
        $this->db->select_min($column);   
        // $this->set_params($params);            
        return $this->db->get('products')->row_array();
    }
    function get_all_produks_max($column){
        $this->db->select_max($column);   
        // $this->set_params($params);            
        return $this->db->get('products')->row_array();
    }       

    function add_produk($params){
        $this->db->insert('products',$params);
        return $this->db->insert_id();
    }

    function update_produk($id,$params){
        $this->db->where('product_id',$id);
        return $this->db->update('products',$params);
    }
    
    function delete_produk($id){
        return $this->db->delete('products',array('product_id'=>$id));
    }

    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('products');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }  

    /*
        MULAI TIDAK DIPAKAI
        product categories
    */
    function get_all_produk_kategoris($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $subquery = 'IFNULL((SELECT a.product_category_name FROM product_categories AS a WHERE a.product_category_id = b.product_category_parent_id),0) AS parent_category_name';
        $this->db->select("b.product_category_id, IFNULL(b.product_category_parent_id,0) AS product_category_parent_id, b.product_category_code, b.product_category_name, b.product_category_url, b.product_category_icon, b.product_category_date_created, b.product_category_date_updated, b.product_category_user_id, b.product_category_flag");
        $this->db->select($subquery);
        $this->db->from("product_categories AS b");
        // $this->set_join();
        $this->set_params($params);
        $this->set_search($search);

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('b.product_category_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get()->result_array();
    }

    function get_all_produk_kategoris_count($params){
        $this->db->from('product_categories');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }

    function get_all_produk_kategori(){
        $this->db->order_by('product_category_id', 'desc');
        return $this->db->get('product_categories')->result_array();
    }

    function get_produk_kategori($id){
        return $this->db->get_where('product_categories',array('product_category_id'=>$id))->row_array();
    }

    function check_data_category_exist($params){
        $this->db->where($params);
        $query = $this->db->get('product_categories');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    function add_produk_kategori($params){
        $this->db->insert('product_categories',$params);
        return $this->db->insert_id();
    }
    function update_produk_kategori($id,$params){
        $this->db->where('product_category_id',$id);
        return $this->db->update('product_categories',$params);
    }
    function delete_produk_kategori($id){
        return $this->db->delete('product_categories',array('product_category_id'=>$id));
    }
    // Stock Card
    function get_product_stock($start,$end,$id){
        $where = "order_item_product_id='$id' AND order_item_date > '$start' AND order_item_date < '$end'";
        $query = $this->db->query("
            SELECT
             ( SELECT product_stock_start FROM products WHERE product_id=$id) AS product_stock_start,
             ( SELECT IFNULL(SUM(order_item_qty),0) FROM order_items WHERE $where AND order_item_type='1') AS product_stock_in,
             ( SELECT IFNULL(SUM(order_item_qty),0) FROM order_items WHERE $where AND order_item_type='2') AS product_stock_out_penjualan,
             ( SELECT IFNULL(SUM(order_item_qty),0) FROM order_items WHERE $where AND order_item_type='6') AS product_stock_out_opname,
             ( SELECT product_stock_out_penjualan + product_stock_out_opname) AS product_stock_out,             
             ( product_stock_start+product_stock_in-(product_stock_out)) AS product_stock_end
            FROM products
            WHERE product_id=".$id."
        ");        
        return $query->row_array();
    }
}
