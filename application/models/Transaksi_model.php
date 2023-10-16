<?php
/*
    @AUTHOR: Joe Witaya
*/ 
class Transaksi_model extends CI_Model{
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
    function set_join() {
        $this->db->join('contacts','trans.trans_contact_id=contacts.contact_id','left');
        $this->db->join('users','trans.trans_user_id=users.user_id','left');
        $this->db->join('types','trans_type=type_type AND type_for=2','left');
        $this->db->join('references AS label','label.ref_name=trans_label','left');        
        $this->db->join('categories','categories.category_id=contacts.contact_category_id','left');
    }
    function get_all_transaksis($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*, trans_id AS trans_id, DATE_FORMAT(`trans_date`,'%d-%b-%y, %H:%i') AS trans_date_format, users.user_username, IFNULL(trans_total,0) AS trans_total");
        $this->db->select("(SELECT IFNULL(SUM(trans_item_total),0) FROM trans_items WHERE trans_item_trans_id=trans_id) AS trans_subtotal");
        $this->db->select("IF(DATEDIFF(trans_date_due,NOW()) < 0, ABS(DATEDIFF(trans_date_due,NOW())), 0) AS date_due_over");
        $this->db->select("(SELECT trans_total - trans_total_paid) AS trans_total_sisa_tagihan");
        $this->db->select('ref_name AS label_name, ref_color AS label_color, ref_background AS label_background, ref_note AS label_icon');
        // $this->db->select('category_id, category_name, category_flag');
        $this->set_params($params);
        $this->set_search($search);
        $this->set_join();

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans')->result_array();
    }
    function get_all_transaksis_goods_out($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("trans.*, trans_id AS trans_id, DATE_FORMAT(`trans_date`,'%d-%b-%y, %H:%i') AS trans_date_format, users.user_username, IFNULL(trans_total,0) AS trans_total");
        // $this->db->select("(SELECT IFNULL(SUM(trans_item_total),0) FROM trans_items WHERE trans_item_trans_id=trans_id) AS trans_subtotal");
        // $this->db->select("IF(DATEDIFF(trans_date_due,NOW()) < 0, ABS(DATEDIFF(trans_date_due,NOW())), 0) AS date_due_over");
        // $this->db->select("(SELECT trans_total - trans_total_paid) AS trans_total_sisa_tagihan");
        $this->db->select('ref_name AS label_name, ref_color AS label_color, ref_background AS label_background, ref_note AS label_icon');
        $this->db->select("branch_2.branch_id AS branch_2_id, branch_2.branch_name AS branch_2_name");          
        // $this->db->select('category_id, category_name, category_flag');
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        // $this->db->join('contacts','trans.trans_contact_id=contacts.contact_id','left');
        $this->db->join('users','trans.trans_user_id=users.user_id','left');
        $this->db->join('types','trans_type=type_type AND type_for=2','left');
        $this->db->join('references AS label','label.ref_name=trans_label','left');        
        // $this->db->join('categories','categories.category_id=contacts.contact_category_id','left');        
        $this->db->join('branchs AS branch_2','trans.trans_branch_id_2=branch_2.branch_id','left');
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans')->result_array();
    }    
    function get_all_transaksi_nojoin($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('trans')->result_array();
    }     
    function get_all_transaksis_count($params,$search){
        $this->db->from('trans');   
        $this->set_join();
        $this->set_params($params);
        $this->set_search($search);            
        return $this->db->count_all_results();
    }
    function get_all_transaksis_goods_out_count($params,$search){
        $this->db->from('trans');   
        // $this->db->join('contacts','trans.trans_contact_id=contacts.contact_id','left');
        $this->db->join('users','trans.trans_user_id=users.user_id','left');
        $this->db->join('types','trans_type=type_type AND type_for=2','left');
        $this->db->join('references AS label','label.ref_name=trans_label','left');        
        // $this->db->join('categories','categories.category_id=contacts.contact_category_id','left');        
        $this->db->join('branchs AS branch_2','trans.trans_branch_id_2=branch_2.branch_id','left');
        $this->set_params($params);
        $this->set_search($search);            
        return $this->db->count_all_results();
    }    
    function get_all_transaksi_nojoin_count($params,$search){
        $this->db->from('trans');
        $this->set_params($params);
        $this->set_search($search);            
        return $this->db->count_all_results();
    }    
    function get_all_transaksi_group_by_paid_type($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("trans_paid_type, paid_id, paid_name, SUM(trans_total_paid) AS trans_total_paid");
        $this->set_params($params);
        $this->set_search($search);
        $this->db->join('types_paids','trans_paid_type=paid_id','left');
        $this->db->group_by('trans_paid_type');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('trans')->result_array();
    }
    function get_all_transaksi_items($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        
        $this->db->join('products','trans_items.trans_item_product_id=products.product_id','left');
        $this->db->join('locations','trans_items.trans_item_location_id=locations.location_id','left');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans_items')->result_array();
    }
    function get_all_transaksi_items_goods_out($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        
        $this->db->join('products','trans_items.trans_item_product_id=products.product_id','left');
        $this->db->join('locations','trans_items.trans_item_location_id=locations.location_id','left');
        $this->db->join('categories','products.product_category_id=categories.category_id','left');
        
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans_items')->result_array();
    }    
    function get_all_transaksi_items_nojoin($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get('trans_items')->result_array();
    }     
    function get_all_transaksi_items_group_by_product($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->db->select("SUM(trans_item_in_qty) AS trans_item_in_qty, SUM(trans_item_out_qty) AS trans_item_out_qty");
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        
        $this->db->join('products','trans_items.trans_item_product_id=products.product_id','left');
        $this->db->join('locations','trans_items.trans_item_location_id=locations.location_id','left');

        $this->db->group_by('product_id');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans_items')->result_array();
    }     
    function get_all_transaksi_items_count($params){
        $this->db->from('trans_items');   
        $this->set_params($params);            
        return $this->db->count_all_results();
    }
    function get_all_transaksi_items_nojoin_count($params,$search){
        $this->db->from('trans_items');
        $this->set_params($params);
        $this->set_search($search);            
        return $this->db->count_all_results();
    }        
    function get_all_transaksi_items_report($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("trans_items.*, trans.*, contacts.*, products.*, locations.*");
        $this->set_params($params);
        // $this->set_search($search);
        // $this->set_join();
        
        $this->db->join('products','trans_items.trans_item_product_id=products.product_id','left');
        $this->db->join('trans','trans_items.trans_item_trans_id=trans.trans_id','left');
        $this->db->join('contacts','trans.trans_contact_id=contacts.contact_id','left');
        $this->db->join('locations','trans_item_location_id=location_id','left');

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans_items')->result_array();
    }
    function get_all_transaksi_items_return($from_trans_buy_or_sell,$params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        
        if($from_trans_buy_or_sell == 1){ //From Pembelian
            $trans_type = 3;
            $this->db->select("product_name")
            ->select("trans_item_id, trans_item_product_id, trans_item_product_type, trans_item_trans_id, trans_item_in_qty, trans_item_out_qty, trans_item_unit")
            ->select("trans_item_in_price, trans_item_out_price, trans_item_sell_price, trans_item_sell_total, trans_item_total")
            ->select("trans_item_ppn, trans_item_ppn_value")
            ->select("location_id, location_name, location_code")
            ->select("trans_item_product_id AS last_product_id, trans_item_ref AS last_ref")
            ->select("(SELECT IFNULL(SUM(trans_item_out_qty),0) FROM trans_items 
                WHERE trans_item_type=$trans_type
                AND trans_item_product_id=last_product_id
                AND trans_item_ref=last_ref
                )
                AS has_return")
            ->select("(SELECT trans_item_in_qty - has_return) AS qty_ready_for_return");
        }else if($from_trans_buy_or_sell == 2){ //From Penjualan
            $trans_type = 4;
            $this->db->select("product_name")
            ->select("trans_item_id, trans_item_product_id, trans_item_product_type, trans_item_trans_id, trans_item_in_qty, trans_item_out_qty, trans_item_unit")
            ->select("trans_item_in_price, trans_item_out_price, trans_item_sell_price, trans_item_sell_total, trans_item_total")
            ->select("trans_item_ppn, trans_item_ppn_value")            
            ->select("location_id, location_name, location_code")
            ->select("trans_item_product_id AS last_product_id, trans_item_ref AS last_ref")
            ->select("(SELECT IFNULL(SUM(trans_item_in_qty),0) FROM trans_items 
                WHERE trans_item_type=$trans_type
                AND trans_item_product_id=last_product_id
                AND trans_item_ref=last_ref
                )
                AS has_return")
            ->select("(SELECT trans_item_out_qty - has_return) AS qty_ready_for_return");            
        }
        
        $this->set_params($params);
        $this->set_search($search);
        // $this->set_join();
        
        $this->db->join('products','trans_items.trans_item_product_id=products.product_id','left');
        $this->db->join('locations','trans_items.trans_item_location_id=locations.location_id','left');

        if($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans_items')->result_array();
    }
    function get_all_transaksi_items_with_category($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $this->db->select("*");
        $this->set_params($params);
        $this->set_search($search);
        $this->db->join('products','trans_items.trans_item_product_id=products.product_id','left');
        $this->db->join('locations','trans_items.trans_item_location_id=locations.location_id','left');
        $this->db->join('categories','products.product_category_id=categories.category_id','left');        
        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('trans_item_id', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get('trans_items')->result_array();
    }    
    /* 
        CRUD 
        ================
    */
    /* function to add new transaksi */
    function add_transaksi($params){
        $this->db->insert('trans',$params);
        return $this->db->insert_id();
    }
    /* function to get transaksi by id */
    function get_transaksi($id){
        $this->db->select("trans.*, contacts.*, users.*, types.*, label.*, locations.*");
        $this->db->select("branch_2.branch_id AS branch_2_id, branch_2.branch_name AS branch_2_name");        
        // $this->db->select("sales.user_id AS sales_id, sales.user_username AS sales_username, sales.user_fullname AS sales_fullname, sales.user_phone_1 AS sales_phone");
        $this->db->select("sales.contact_id AS sales_id, sales.contact_name AS contact_username, sales.contact_name AS sales_fullname, sales.contact_phone_1 AS sales_phone");
        $this->set_join();
        $this->db->join('locations','trans.trans_location_id=locations.location_id','left');
        // $this->db->join('users AS sales','trans.trans_sales_id=sales.user_id','left');
        $this->db->join('contacts AS sales','trans.trans_sales_id=sales.contact_id','left');
        $this->db->join('branchs AS branch_2','trans.trans_branch_id_2=branch_2.branch_id','left');
        return $this->db->get_where('trans',array('trans_id'=>$id))->row_array();
    }
    function get_transaksi_custom($where){
        $this->db->select("trans.*, contacts.*, users.*, types.*, label.*, locations.*");
        // $this->db->select("sales.user_id AS sales_id, sales.user_username AS sales_username, sales.user_fullname AS sales_fullname, sales.user_phone_1 AS sales_phone");
        $this->db->select("sales.contact_id AS sales_id, sales.contact_name AS contact_username, sales.contact_name AS sales_fullname, sales.contact_phone_1 AS sales_phone");
        $this->set_join();
        $this->db->join('locations','trans.trans_location_id=locations.location_id','left');
        // $this->db->join('users AS sales','trans.trans_sales_id=sales.user_id','left');
        $this->db->join('contacts AS sales','trans.trans_sales_id=sales.contact_id','left');
        // $this->db->join('branchs','trans_branch_id=branch_id','left');        
        return $this->db->get_where('trans',$where)->row_array();
    }
    function get_transaksi_nojoin_custom($where){
        $this->db->select("*");
        return $this->db->get_where('trans',$where)->row_array();
    }    
    function get_transaksi_sum_by_column($params,$column){  
        $this->db->select('IFNULL(SUM('.$column.'),0) AS '.$column.'');     
        $this->db->from('trans');
        $this->db->where($params);           
        return $this->db->get()->row_array();        
    }
    /* function to update transaksi */
    function update_transaksi($id,$params){
        $this->db->where('trans_id',$id);
        return $this->db->update('trans',$params);
    }
    /* function to delete transaksi */
    function delete_transaksi($id){
        return $this->db->delete('trans',array('trans_id'=>$id));
    }
    /* function to check data exists transaksi */
    function check_data_exist($params){
        $this->db->where($params);
        $query = $this->db->get('trans');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* 
        ================
        CRUD ITEM
        ================
    */
    /* function to add new transaksi */
    function add_transaksi_item($params){
        $this->db->insert('trans_items',$params);
        return $this->db->insert_id();
    }
    /* function to get transaksi by id */
    function get_transaksi_item($id){
        $this->db->join('products','trans_item_product_id=product_id','left');        
        return $this->db->get_where('trans_items',array('trans_item_id'=>$id))->row_array();
    }
    function get_transaksi_item_custom($where){
        $this->db->select("*, DATE_FORMAT(`trans_date`,'%d-%b-%y, %H:%i') AS trans_date_format");
        $this->db->join('products','trans_item_product_id=product_id','left');
        $this->db->join('trans','trans_item_trans_id=trans_id','left');
        $this->db->join('locations','trans_item_location_id=location_id','left');      
        $this->db->order_by('trans_item_id', "asc");          
        return $this->db->get_where('trans_items',$where)->result_array();
    }
    /* function to update transaksi */
    function update_transaksi_item($id,$params){
        $this->db->where('trans_item_id',$id);
        return $this->db->update('trans_items',$params);
    }
    function update_transaksi_item_custom($where,$params){
        $this->db->where($where);
        return $this->db->update('trans_items',$params);
    }    
    /* function to delete transaksi */
    function delete_transaksi_item($id){
        return $this->db->delete('trans_items',array('trans_item_id'=>$id));
    }       
    function delete_transaksi_item_custom($where){
        return $this->db->delete('trans_items',$where);
    }
    /* function to check data exists transaksi item*/
    function check_data_exist_item($params){
        $this->db->where($params);
        $query = $this->db->get('trans_items');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }
    /* OTHER */
    /* function to get transaksi by id */
    function check_unsaved_transaksi_item($identity,$user_id,$branch_id,$product_type,$position){
        $this->db->select("trans_items.*, products.*, locations.*");
        $this->db->from('trans_items');
        $this->db->join('products','trans_items.trans_item_product_id=products.product_id','left');
        $this->db->join('locations','trans_items.trans_item_location_id=locations.location_id','left');        
        $this->db->where('trans_item_user_id',$user_id); 
        $this->db->where('trans_item_branch_id',$branch_id);          
        $this->db->where('trans_item_type',$identity);                 
        // $this->db->where('trans_item_flag',0);
        $this->db->where('trans_item_trans_id',null);   
        if($product_type > 0){
            $this->db->where('trans_item_product_type',$product_type);
        }else{
            $this->db->where('trans_item_product_type > ',$product_type);
        }   
        $this->db->where('trans_item_position',$position);
        $this->db->order_by('trans_item_id','desc');
        // return $this->db->get_where('trans_item',array('trans_item_id_user'=>$user_id))->result_array();
        return $this->db->get()->result_array();
    }
    /* function to get transaksi by id */
    function reset_transaksi_item($user_id){   
        return $this->db->delete('trans_items',array('trans_item_flag'=>0,'trans_item_user_id'=>$user_id));
    }
    /* function to update trans item */
    function update_transaksi_item_by_trans_id($id,$params){
        $this->db->where('trans_item_trans_id',$id);
        return $this->db->update('trans_items',$params);
    }
    function delete_transaksi_item_by_trans_id($params){
        // $this->db->where('trans_item_trans_id',$id);
        // return $this->db->update('trans_items',$params);
        return $this->db->delete('trans_items',$params);        
    }
    function get_transaksi_item_in_price_total($trans_id,$params){
        // $this->db->select_sum('trans_item_in_price');
        // $this->db->select_sum('trans_item_total','trans_item_in_price');
        $this->db->select('IFNULL(SUM(trans_item_total),0) AS trans_item_in_price');
        $this->db->from('trans_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);        
        return $this->db->get()->row_array();
    }
    function get_transaksi_item_out_price_total($trans_id,$params){
        // $this->db->select_sum('trans_item_out_price');
        // $this->db->select_sum('trans_item_total','trans_item_out_price');
        $this->db->select('IFNULL(SUM(trans_item_total),0) AS trans_item_out_price');                
        $this->db->from('trans_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);           
        return $this->db->get()->row_array();
    }  
    function get_transaksi_item_in_qty_total($trans_id,$params){
        // $this->db->select_sum('IFNULL(trans_item_in_qty,0)','trans_item_in_qty_total');   
        $this->db->select('IFNULL(SUM(trans_item_in_qty),0) AS trans_item_in_qty_total');     
        $this->db->from('trans_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);           
        return $this->db->get()->row_array();        
    }  
    function get_transaksi_item_out_sell_price_total($trans_id,$params){
        // $this->db->select_sum('trans_item_out_price');
        // $this->db->select_sum('trans_item_total','trans_item_out_price');
        $this->db->select('IFNULL(SUM(trans_item_sell_total),0) AS trans_item_sell_price');                
        $this->db->from('trans_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);           
        return $this->db->get()->row_array();
    }      
    function get_transaksi_item_out_qty_total($params){
        // $this->db->select_sum('trans_item_out_price');
        // $this->db->select_sum('trans_item_total','trans_item_out_price');
        $this->db->select('IFNULL(SUM(trans_item_out_qty),0) AS trans_item_out_qty');                
        $this->db->from('trans_items');
        // $this->db->where('trans_item_trans_id',$trans_id);
        $this->db->where($params);           
        return $this->db->get()->row_array();
    }
    //Check Stock is available to Delete
    function check_stock_is_available_to_delete($params){
        $this->db->from('trans_items')
            ->where($params);
        return $this->db->count_all_results();
    }
}
?>