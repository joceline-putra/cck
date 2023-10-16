<?php
 
class Kategori_model extends CI_Model
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
        // $this->db->join('references', 'product_ref_id=ref_id','left');
    }

    function get_all_categoriess($params = null, $search = null, $limit = null, $start = null, $order = null, $dir = null) {
        $subquery = 'IFNULL((SELECT a.category_name FROM categories AS a WHERE a.category_id = b.category_parent_id),0) AS category_names';
        // $subquery_products = '(SELECT IFNULL(COUNT(product_category_id),0) FROM products WHERE product_category_id=category_id) AS category_count';
        $subquery_news = '(SELECT IFNULL(COUNT(news_category_id),0) FROM news WHERE news_category_id=category_id) AS category_count';        

        $this->db->select("b.category_id AS category_id, IFNULL(b.category_parent_id,0) AS category_parent_id, b.category_code, 
        b.category_name, b.category_url, b.category_icon, b.category_type, b.category_date_created, b.category_date_updated, 
        b.category_user_id, b.category_flag, b.category_count_data, b.category_count_data AS category_count");
        $this->db->select($subquery);
        
        if($params['category_type']==1){
            // $this->db->select($subquery_products);
        }else{
            $this->db->select($subquery_news);
        }
        $this->db->from("categories AS b");
        // $this->set_join();
        $this->set_params($params);
        $this->set_search($search);

        if ($order) {
            $this->db->order_by($order, $dir);
        } else {
            $this->db->order_by('b.category_name', "asc");
        }

        if ($limit) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get()->result_array();
    }

    function get_all_categoriess_count($params,$search)
    {
        $this->db->from('categories');   
        $this->set_params($params);      
        $this->set_search($search);      
        return $this->db->count_all_results();
    }

    function get_all_categories()
    {
        $this->db->order_by('category_name', 'asc');
        return $this->db->get('categories')->result_array();
    }

    function get_categories($id)
    {
        return $this->db->get_where('categories',array('category_id'=>$id))->row_array();
    }

    function check_data_exist($params)
    {
        $this->db->where($params);
        $query = $this->db->get('categories');
        if ($query->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }

    /*
     * function to add new produk kategori
     */
    function add_categories($params)
    {
        $this->db->insert('categories',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update kategori
     */
    function update_categories($id,$params)
    {
        $this->db->where('category_id',$id);
        return $this->db->update('categories',$params);
    }
    
    /*
     * function to delete kategori
     */
    function delete_categories($id)
    {
        return $this->db->delete('categories',array('category_id'=>$id));
    }

    function get_categories_by_params($params)
    {
        return $this->db->get_where('categories',$params)->row_array();
    }        
}
