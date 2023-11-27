<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller{
    
    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
        }
        $this->load->model('Aktivitas_model');
        $this->load->model('User_model');           
        $this->load->model('Produk_model');                   
        $this->load->model('Satuan_model');
        $this->load->model('Referensi_model');          
        $this->load->model('Transaksi_model');
    }   
    function index(){
    }
    function manage(){

        $session = !empty($this->session->userdata()) ? $this->session->userdata() : null;        
        $session_branch_id = !empty($session['user_data']['branch']['id']) ? $session['user_data']['branch']['id'] : null;
        $session_user_id = !empty($session['user_data']['user_id']) ? $session['user_data']['user_id'] : null;        

        $json       = [];
        $terms      = $this->input->get("search");
        $tipe       = $this->input->get("tipe"); //public
        $category   = $this->input->get("category");
        $group      = $this->input->get('group');
        $group_sub  = $this->input->get('group_sub');        
        $source     = $this->input->get("source"); //nama table
        // $parent     = !empty($this->input->get('parentss')) ? $this->input->get('parentss') : false;
        $parent     = $this->input->get('parent');        
        $not_used   = !empty($this->input->get('not_used')) ? $this->input->get('not_used') : false;
        
        $result = array();  
        $terms  = ltrim(rtrim($terms));
        $next   = false;

        // if(strlen($terms) >= 2){
            $next = true;
        // }

        if($next){        
            if($source=="specialist"){
                if(!empty($terms)){                            
                    $query = $this->db->query("
                        SELECT specialist_id AS id, specialist_name AS nama,
                            (SELECT CONCAT(`specialist_name`)) AS `text`
                        FROM branchs_specialists
                        WHERE specialist_name LIKE '%".$terms."%'
                        ORDER BY specialist_name ASC
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT specialist_id AS id, specialist_name AS nama,
                            (SELECT CONCAT(`specialist_name`)) AS `text`
                        FROM branchs_specialists  
                        ORDER BY specialist_name ASC
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="contacts"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT contact_id AS id, contact_code AS kode, contact_name AS nama, contact_phone_1 AS telepon, contact_address AS alamat, contact_email_1 AS email,
                            contact_termin, IFNULL(contact_parent_id,0) AS contact_parent_id, contact_parent_name, contact_session, 
                            IF(contact_type=1,'Supplier','Customer') AS kontak_tipe,
                            (SELECT CONCAT(IFNULL(contact_code,''), ' - ', IFNULL(`contact_name`,''), ' ', IFNULL(`contact_phone_1`,''))) AS `text`
                        FROM contacts
                        WHERE contact_code LIKE '%".$terms."%' OR contact_name LIKE '%".$terms."%' OR contact_phone_1 LIKE '%".$terms."%' 
                        AND contact_flag=1 AND contact_type LIKE '%".$tipe."%' AND contact_branch_id=".$session_branch_id."
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT contact_id AS id, contact_code AS kode, contact_name AS nama, contact_phone_1 AS telepon, contact_address AS alamat, contact_email_1 AS email, 
                            contact_termin, IFNULL(contact_parent_id,0) AS contact_parent_id, contact_parent_name, contact_session, 
                            IF(contact_type=1,'Supplier','Customer') AS kontak_tipe,
                            (SELECT CONCAT(IFNULL(contact_code,''), ' - ', IFNULL(`contact_name`,''), ' ', IFNULL(`contact_phone_1`,''))) AS `text`
                        FROM contacts
                        WHERE contact_type LIKE '%".$tipe."%' AND contact_flag=1 AND contact_branch_id=".$session_branch_id." 
                        ORDER BY contact_name ASC LIMIT 20
                    ");                    
                }

                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));
                if($tipe != 3){ //Employee not displayed
                    $json = array_push($result,array(
                        'id' => "-",
                        'nama' => 'Buat Kontak Baru',
                        'text' => 'Buat Kontak Baru'
                    ));
                }          
            }   
            else if($source=="contacts-use-type"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT contact_id AS id, contact_code AS kode, contact_name AS nama, contact_phone_1 AS telepon, contact_address AS alamat, contact_email_1 AS email,
                            contact_termin, IFNULL(contact_parent_id,0) AS contact_parent_id, contact_parent_name, contact_session, 
                            IF(contact_type=1,'Supplier','Customer') AS kontak_tipe,
                            (SELECT CONCAT(IFNULL(contact_code,''), ' - ', IFNULL(`contact_name`,''), ' ', IFNULL(`contact_phone_1`,''))) AS `text`
                        FROM contacts
                        WHERE contact_code LIKE '%".$terms."%' OR contact_name LIKE '%".$terms."%' OR contact_phone_1 LIKE '%".$terms."%' 
                        AND contact_flag=1 AND contact_type=".$tipe." AND contact_use_type=0 AND contact_branch_id=".$session_branch_id."
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT contact_id AS id, contact_code AS kode, contact_name AS nama, contact_phone_1 AS telepon, contact_address AS alamat, contact_email_1 AS email, 
                            contact_termin, IFNULL(contact_parent_id,0) AS contact_parent_id, contact_parent_name, contact_session, 
                            IF(contact_type=1,'Supplier','Customer') AS kontak_tipe,
                            (SELECT CONCAT(IFNULL(contact_code,''), ' - ', IFNULL(`contact_name`,''), ' ', IFNULL(`contact_phone_1`,''))) AS `text`
                        FROM contacts
                        WHERE contact_type LIKE '%".$tipe."%' AND contact_flag=1 AND contact_branch_id=".$session_branch_id." AND contact_use_type=0
                        ORDER BY contact_name ASC LIMIT 20
                    ");                    
                }

                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));
                if($tipe != 3){ //Employee not displayed
                    $json = array_push($result,array(
                        'id' => "-",
                        'nama' => 'Buat Kontak Baru',
                        'text' => 'Buat Kontak Baru'
                    ));
                }          
            }
            else if($source=="users"){
                if(!empty($terms)){    
                    if($not_used==true){
                        $query = $this->db->query("
                            SELECT user_id AS id, user_code AS kode, user_username AS nama,
                                (SELECT CONCAT(IFNULL(`user_code`,''), ' - ', IFNULL(`user_username`,''), ' - ', IFNULL(`user_group_name`,''))) AS `text`,
                                CONCAT(user_group_name,' - ',user_username) AS user_by_group
                            FROM users
                            LEFT JOIN users_groups ON (users.user_user_group_id=users_groups.user_group_id)
                            WHERE user_flag=1 AND user_branch_id IS NULL AND (user_code LIKE '%".$terms."%' OR user_username LIKE '%".$terms."%' OR user_group_name LIKE '%".$terms."%')
                        ");
                    }else{                        
                        $query = $this->db->query("
                            SELECT user_id AS id, user_code AS kode, user_username AS nama,
                                (SELECT CONCAT(user_code, ' - ', `user_username`, ' - ', `user_group_name`)) AS `text`,
                                CONCAT(user_group_name,' - ',user_username) AS user_by_group
                            FROM users
                            LEFT JOIN users_groups ON (users.user_user_group_id=users_groups.user_group_id)
                            WHERE user_branch_id=".$session_branch_id." AND user_flag=1 AND (user_code LIKE '%".$terms."%' OR user_username LIKE '%".$terms."%' OR user_group_name LIKE '%".$terms."%')
                        ");
                    }
                }else{
                    if($not_used==true){
                        $query = $this->db->query("
                        SELECT user_id AS id, user_code AS kode, user_username AS nama,
                            (SELECT CONCAT(IFNULL(user_code,'-'), ' - ', IFNULL(`user_username`,''), ' - ', IFNULL(`user_group_name`,'-'))) AS `text`
                            , CONCAT(user_group_name,' - ',user_username) AS user_by_group
                        FROM users
                        LEFT JOIN users_groups ON (users.user_user_group_id=users_groups.user_group_id)
                        WHERE user_flag=1 AND user_branch_id IS NULL ORDER BY user_username ASC LIMIT 20
                        ");
                    }else{
                        $query = $this->db->query("
                        SELECT user_id AS id, user_code AS kode, user_username AS nama,
                            (SELECT CONCAT(IFNULL(user_code,'-'), ' - ', IFNULL(`user_username`,''), ' - ', IFNULL(`user_group_name`,'-'))) AS `text`
                            , CONCAT(user_group_name,' - ',user_username) AS user_by_group
                        FROM users
                        LEFT JOIN users_groups ON (users.user_user_group_id=users_groups.user_group_id)
                        WHERE user_flag=1 AND user_branch_id=".$session_branch_id." ORDER BY user_username ASC LIMIT 20
                        ");
                    }                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="users-switch"){
                if(!empty($terms)){    
                    $query = $this->db->query("
                        SELECT user_id AS id, user_code AS kode, user_username AS nama,
                        (SELECT CONCAT(IFNULL(`branch_name`,''), ' - ', IFNULL(`user_group_name`,''),' - ',IFNULL(`user_username`,'C'))) AS `text`
                        FROM users
                        LEFT JOIN users_groups ON user_user_group_id=user_group_id
                        LEFT JOIN branchs ON user_branch_id=branch_id
                        WHERE `user_username` LIKE '%".$terms."%' OR `branch_name` LIKE '%".$terms."%' OR `user_group_name` LIKE '%".$terms."%'
                        ORDER BY branch_name ASC    
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT user_id AS id, user_code AS kode, user_username AS nama,
                        (SELECT CONCAT(IFNULL(`branch_name`,''), ' - ', IFNULL(`user_group_name`,''),' - ',IFNULL(`user_username`,''))) AS `text`
                        FROM users
                        LEFT JOIN users_groups ON (users.user_user_group_id=users_groups.user_group_id)
                        LEFT JOIN branchs ON user_branch_id=branch_id
                        ORDER BY branch_name ASC LIMIT 20
                    ");            
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="users_groups"){
                if(!empty($terms)){    
                    $query = $this->db->query("
                        SELECT user_group_id AS id, user_group_name AS nama,
                            (SELECT CONCAT(IFNULL(`user_group_name`,''))) AS `text`
                        FROM users_groups
                        WHERE user_group_name LIKE '%".$terms."%' AND user_group_id > 1
                        ORDER BY user_group_name ASC
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT user_group_id AS id, user_group_name AS nama,
                            (SELECT CONCAT(IFNULL(`user_group_name`,''))) AS `text`
                        FROM users_groups
                        WHERE user_group_id > 1 ORDER BY user_group_name ASC
                    ");
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="menus"){

                if($parent == "0"){
                    $where = "WHERE menu_parent_id = 0";
                }else{
                    $where = "WHERE menu_parent_id > 0";
                }
                // var_dump($where);die;
                if(!empty($terms)){                 
                    // $query = $this->db->query("
                    //     SELECT menu_id AS id, menu_name AS nama,
                    //         (SELECT CONCAT(menu_group_name, ' - ', `menu_name`)) AS `text`
                    //     FROM menus
                    //     LEFT JOIN menu_groups ON (menus.menu_menu_group_id=menu_groups.menu_group_id)
                    //     WHERE menu_groups.menu_group_name LIKE '%".$terms."%' OR menus.menu_name LIKE '%".$terms."%' 
                    //     AND menus.menu_flag=1
                    // ");
                    $query = $this->db->query("
                        SELECT menu_id AS id, menu_name AS nama,
                            (SELECT CONCAT(`menu_name`)) AS `text`
                        FROM menus
                        $where AND menu_flag=1 AND (menu_name LIKE '%".$terms."%') ORDER BY menu_name ASC LIMIT 20
                    ");                    
                }else{
                    // $query = $this->db->query("
                    //     SELECT menu_parent.menu_id AS id, menu_parent.menu_name AS nama,
                    //         (SELECT CONCAT(menu_parent.menu_name, ' - ', `menu_parent.menu_name`)) AS `text`
                    //     FROM menus
                    //     LEFT JOIN menus AS menu_parent ON (menus.menu_parent_id=menu_parent.menu_id)
                    //     WHERE menus.menu_flag=1 ORDER BY menus.menu_name ASC LIMIT 20
                    // ");
                    $query = $this->db->query("
                        SELECT menu_id AS id, menu_name AS nama,
                            (SELECT CONCAT(`menu_name`)) AS `text`
                        FROM menus
                        $where AND menu_flag=1 ORDER BY menu_name ASC LIMIT 20
                    ");                    
                }    
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                                
            }
            else if($source=="products"){
                // $where_category = '';
                if(!empty($category)){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }else{
                    $where_category = ' AND product_category_id < 1';
                }

                if($category=='add-on'){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }

                if($tipe == 2){
                    $where_category = '';
                }

                if(!empty($terms)){
                    $prepare="SELECT product_id AS id, product_code AS kode, product_type AS tipe, product_name AS nama, product_unit AS satuan, product_stock,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE (product_code LIKE '%".$terms."%' OR product_name LIKE '%".$terms."%' OR product_manufacture LIKE '%".$terms."%')
                        AND product_flag=1 AND product_branch_id=".$session_branch_id." AND product_type = ".$tipe."".$where_category."
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);
                }else{
                    $prepare="SELECT product_id AS id, product_code AS kode, product_type AS tipe, product_name AS nama, product_unit AS satuan, product_stock,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(product_manufacture,''), '- ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE product_flag=1 AND product_branch_id=".$session_branch_id." AND product_type = ".$tipe."".$where_category."
                        ORDER BY product_name ASC, product_manufacture ASC LIMIT 20
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));      
                $json = array_push($result,array(
                    'id' => "-",
                    'nama' => 'Buat Produk Baru',
                    'text' => 'Buat Produk Baru'
                ));                                    
            }
            else if($source=="products-1"){
                // $where_category = '';
                if(!empty($category)){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }else{
                    $where_category = ' AND product_category_id < 1';
                }

                if($category=='add-on'){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }

                if($tipe == 2){
                    $where_category = '';
                }

                if(!empty($terms)){
                    $prepare="SELECT product_id AS id, product_code AS kode, product_name AS nama, product_unit AS satuan,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE (product_code LIKE '%".$terms."%' OR product_name LIKE '%".$terms."%' OR product_manufacture LIKE '%".$terms."%')
                        AND product_flag=1 AND product_ref_id=1 AND product_branch_id=".$session_branch_id." AND product_type=".$tipe."".$where_category."
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);
                }else{
                    $prepare="SELECT product_id AS id, product_code AS kode, product_name AS nama, product_unit AS satuan,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(product_manufacture,''), '- ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE product_flag=1 AND product_ref_id=1 AND product_branch_id=".$session_branch_id." AND product_type=".$tipe."".$where_category."
                        ORDER BY product_name ASC, product_manufacture ASC LIMIT 20
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));      
                $json = array_push($result,array(
                    'id' => "-",
                    'nama' => 'Buat Produk Baru',
                    'text' => 'Buat Produk Baru'
                ));                                    
            }
            else if($source=="products-2"){
                // $where_category = '';
                if(!empty($category)){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }else{
                    $where_category = ' AND product_category_id < 1';
                }

                if($category=='add-on'){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }

                if($tipe == 2){
                    $where_category = '';
                }

                if(!empty($terms)){
                    $prepare="SELECT product_id AS id, product_code AS kode, product_name AS nama, product_unit AS satuan,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE (product_code LIKE '%".$terms."%' OR product_name LIKE '%".$terms."%' OR product_manufacture LIKE '%".$terms."%')
                        AND product_flag=1 AND product_ref_id=2 AND product_branch_id=".$session_branch_id." AND product_type=".$tipe."".$where_category."
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);
                }else{
                    $prepare="SELECT product_id AS id, product_code AS kode, product_name AS nama, product_unit AS satuan,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(product_manufacture,''), '- ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE product_flag=1 AND product_ref_id=2 AND product_branch_id=".$session_branch_id." AND product_type=".$tipe."".$where_category."
                        ORDER BY product_name ASC, product_manufacture ASC LIMIT 20
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));      
                $json = array_push($result,array(
                    'id' => "-",
                    'nama' => 'Buat Produk Baru',
                    'text' => 'Buat Produk Baru'
                ));                                    
            }
            else if($source=="products-all"){
                // $where_category = '';
                if(!empty($category)){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }else{
                    $where_category = ' AND product_category_id < 1';
                }

                if($category=='add-on'){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }

                if($tipe == 2){
                    $where_category = '';
                }

                if(!empty($terms)){
                    $prepare="SELECT product_id AS id, product_code AS kode, product_type AS tipe, product_name AS nama, product_unit AS satuan,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE (product_code LIKE '%".$terms."%' OR product_name LIKE '%".$terms."%' OR product_manufacture LIKE '%".$terms."%')
                        AND product_flag=1 AND product_type < 3 AND product_branch_id=".$session_branch_id."".$where_category."
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);
                }else{
                    $prepare="SELECT product_id AS id, product_code AS kode, product_type AS tipe, product_name AS nama, product_unit AS satuan,
                            (SELECT CONCAT(IFNULL(product_code,''), ' - ', IFNULL(product_manufacture,''), '- ', IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE product_flag=1 AND product_type < 3 AND product_branch_id=".$session_branch_id."".$where_category."
                        ORDER BY product_name ASC, product_manufacture ASC LIMIT 20
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));      
                $json = array_push($result,array(
                    'id' => "-",
                    'nama' => 'Buat Produk Baru',
                    'text' => 'Buat Produk Baru'
                ));                                    
            }
            else if($source=="product_type"){ //From MY_Controller
                $product_type = $this->product_type(null,null);
                // echo json_encode($product_type);die;
                $result = array();
                foreach($product_type as $k => $v){
                    $result[] = array(
                        'id' => $k,
                        'nama' => $v,
                        'text' => $v
                    );
                }
                $json = array_push($result,array(
                    'id' => "110",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="products_other"){
                $branch  = $this->input->get('branch');  
                // $where_category = '';
                if(!empty($category)){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }else{
                    $where_category = '';
                    // $where_category = ' AND product_category_id < 1';
                }

                if($category=='add-on'){
                    // $where_category = ' AND product_category_id > 0';
                    $where_category = '';
                }

                if($tipe == 2){
                    $where_category = '';
                }

                if(!empty($terms)){
                    $prepare="SELECT product_id AS id, product_code AS kode, product_type AS tipe, product_name AS nama, product_unit AS satuan, product_stock,
                            (SELECT CONCAT(IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE (product_name LIKE '%".$terms."%')
                        AND product_flag=1 AND product_branch_id=".$branch." AND product_type = ".$tipe."".$where_category."
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);
                }else{
                    $prepare="SELECT product_id AS id, product_code AS kode, product_type AS tipe, product_name AS nama, product_unit AS satuan, product_stock,
                            (SELECT CONCAT(IFNULL(`product_name`,''))) AS `text`
                        FROM products
                        WHERE product_flag=1 AND product_branch_id=".$branch." AND product_type = ".$tipe."".$where_category."
                        ORDER BY product_name ASC LIMIT 20
                    ";
                    // var_dump($prepare);
                    $query = $this->db->query($prepare);                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));      
                // $json = array_push($result,array(
                //     'id' => "-",
                //     'nama' => 'Buat Produk Baru',
                //     'text' => 'Buat Produk Baru'
                // ));                                    
            }            
            else if($source=="categories"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT category_id AS id, category_name AS nama,
                            (SELECT CONCAT(IFNULL(`category_name`,''))) AS `text`
                        FROM categories
                        WHERE category_name LIKE '%".$terms."%' 
                        AND category_flag=1 AND category_type=".$tipe."
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT category_id AS id, category_name AS nama,
                            (SELECT CONCAT(IFNULL(`category_name`,''))) AS `text`
                        FROM categories
                        WHERE category_flag=1 AND category_type=".$tipe." ORDER BY category_name ASC LIMIT 10;
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="references"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT ref_id AS id, ref_name AS nama,
                            (SELECT CONCAT(IFNULL(`ref_name`,''))) AS `text`
                        FROM `references`
                        WHERE ref_name LIKE '%".$terms."%' 
                        AND ref_flag=1 AND ref_type=".$tipe."
                    ");
                        // AND ref_flag=1 AND ref_branch_id=".$session_branch_id." AND ref_type=".$tipe."                    
                }else{
                    $query = $this->db->query("
                        SELECT ref_id AS id, ref_name AS nama,
                            (SELECT CONCAT(IFNULL(`ref_name`,''))) AS `text`
                        FROM `references`
                        WHERE ref_flag=1 AND ref_type=".$tipe." ORDER BY ref_name ASC LIMIT 50;
                    ");                    
                        // WHERE ref_flag=1 AND ref_branch_id=".$session_branch_id." AND ref_type=".$tipe." ORDER BY ref_name ASC LIMIT 50;                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="references-contact-termin"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT ref_name AS id, ref_note AS `text`
                        FROM `references`
                        WHERE ref_name LIKE '%".$terms."%' 
                        AND ref_flag=1 AND ref_type=".$tipe." ORDER BY ref_name ASC
                    ");
                        // AND ref_flag=1 AND ref_branch_id=".$session_branch_id." AND ref_type=".$tipe."                    
                }else{
                    $query = $this->db->query("
                        SELECT ref_name AS id, ref_note AS `text`
                        FROM `references`
                        WHERE ref_flag=1 AND ref_type=".$tipe." ORDER BY ref_name ASC LIMIT 50;
                    ");                    
                        // WHERE ref_flag=1 AND ref_branch_id=".$session_branch_id." AND ref_type=".$tipe." ORDER BY ref_name ASC LIMIT 50;                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));
            }
            else if($source=="branchs"){
                if(!empty($terms)){
                    $query = $this->db->query("                                                                                                                                                                    
                        SELECT branch_id AS id, branch_name AS nama, branch_logo,
                            (SELECT CONCAT(IFNULL(`branch_name`,''),' - ',IFNULL(`branch_address`,''),' - ',IFNULL(`branch_phone_1`,''))) AS `text` 
                        FROM branchs
                        WHERE branch_name LIKE '%".$terms."%' 
                        AND branch_flag=1
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT branch_id AS id, branch_name AS nama, branch_logo,
                            (SELECT CONCAT(IFNULL(`branch_name`,''),' - ',IFNULL(`branch_address`,''),' - ',IFNULL(`branch_phone_1`,''))) AS `text` 
                        FROM branchs
                        WHERE branch_flag=1 ORDER BY branch_name ASC LIMIT 50;
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="branchs_exclude"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT branch_id AS id, branch_name AS nama, (SELECT CONCAT(IFNULL(`branch_name`,''),' - ',IFNULL(`branch_address`,''),' - ',IFNULL(`branch_phone_1`,''))) AS `text` 
                        FROM branchs WHERE branch_name LIKE '%".$terms."%' AND branch_id != ".$session_branch_id." AND branch_flag=1
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT branch_id AS id, branch_name AS nama, (SELECT CONCAT(IFNULL(`branch_name`,''),' - ',IFNULL(`branch_address`,''),' - ',IFNULL(`branch_phone_1`,''))) AS `text` 
                        FROM branchs WHERE branch_flag=1 AND branch_id != ".$session_branch_id." ORDER BY branch_name ASC LIMIT 50;
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }        
            else if($source=="accounts"){
                if(!empty($group)){
                    $where_group = ' AND account_group='.$group.'';
                }else{
                    $where_group = '';
                }

                if(!empty($group_sub)){
                    $where_group_sub = ' AND account_group_sub='.$group_sub.'';
                }else{
                    $where_group_sub = '';
                }                

                if(!empty($terms)){
                    $prepare = "
                        SELECT account_id AS id, account_name AS nama, account_branch_id,
                            (SELECT CONCAT(IFNULL(`account_code`,''),' - ',IFNULL(`account_name`,''))) AS `text` 
                        FROM accounts
                        WHERE account_code LIKE '%".$terms."%' OR account_name LIKE '%".$terms."%' 
                        AND account_flag=1 
                        AND account_branch_id=".$session_branch_id." 
                        ".$where_group." ".$where_group_sub."
                    ";
                    $query = $this->db->query($prepare);
                }else{
                    $prepare = "
                        SELECT account_id AS id, account_name AS nama, account_branch_id,
                            (SELECT CONCAT(IFNULL(`account_code`,''),' - ',IFNULL(`account_name`,''))) AS `text` 
                        FROM accounts
                        WHERE account_flag=1 
                        AND account_branch_id=".$session_branch_id." 
                        ".$where_group." ".$where_group_sub."
                        ORDER BY account_name ASC LIMIT 50;
                    ";
                    $query = $this->db->query($prepare);                    
                }
                // var_dump($prepare);
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));     
                $json = array_push($result,array(
                    'id' => "-",
                    'nama' => 'Buat Akun Baru',
                    'text' => 'Buat Akun Baru'
                ));                             
            }
            else if($source=="accounts_banks"){
                if(!empty($group)){
                    $where_group = ' AND account_group='.$group.'';
                }else{
                    $where_group = '';
                }

                if(!empty($group_sub)){
                    $where_group_sub = ' AND account_group_sub='.$group_sub.'';
                }else{
                    $where_group_sub = '';
                }                

                if(!empty($terms)){
                    $prepare = "
                        SELECT account_id AS id, account_code AS code, account_name AS nama, account_branch_id, 
                            (SELECT CONCAT(IFNULL(`account_name`,''))) AS `text` 
                        FROM accounts
                        WHERE account_code LIKE '%".$terms."%' OR account_name LIKE '%".$terms."%' AND (account_name LIKE '%Bank%' OR account_name LIKE '%Rekening%')
                        AND account_flag=1 
                        AND account_branch_id=".$session_branch_id." 
                        ".$where_group." ".$where_group_sub."
                    ";
                    $query = $this->db->query($prepare);
                }else{
                    $prepare = "
                        SELECT account_id AS id, account_code AS code, account_name AS nama, account_branch_id, 
                            (SELECT CONCAT(IFNULL(`account_name`,''))) AS `text` 
                        FROM accounts
                        WHERE account_flag=1 
                        AND account_branch_id=".$session_branch_id." AND (account_name LIKE '%Bank%' OR account_name LIKE '%Rekening%')  
                        ".$where_group." ".$where_group_sub."
                        ORDER BY account_name ASC LIMIT 50;
                    ";
                    $query = $this->db->query($prepare);                    
                }
                // var_dump($prepare);
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                          
            }
            else if($source=="accounts_cashs"){
                if(!empty($group)){
                    $where_group = ' AND account_group='.$group.'';
                }else{
                    $where_group = '';
                }

                if(!empty($group_sub)){
                    $where_group_sub = ' AND account_group_sub='.$group_sub.'';
                }else{
                    $where_group_sub = '';
                }                

                if(!empty($terms)){
                    $prepare = "
                        SELECT account_id AS id, account_name AS nama,
                            (SELECT CONCAT(IFNULL(`account_name`,''))) AS `text` 
                        FROM accounts
                        WHERE account_code LIKE '%".$terms."%' OR account_name LIKE '%".$terms."%' AND account_name LIKE '%Kas%' 
                        AND account_flag=1 
                        AND account_branch_id=".$session_branch_id." 
                        ".$where_group." ".$where_group_sub."
                    ";
                    $query = $this->db->query($prepare);
                }else{
                    $prepare = "
                        SELECT account_id AS id, account_name AS nama,
                            (SELECT CONCAT(IFNULL(`account_name`,''))) AS `text` 
                        FROM accounts
                        WHERE account_flag=1 
                        AND account_branch_id=".$session_branch_id." AND account_name LIKE '%Kas%' 
                        ".$where_group." ".$where_group_sub."
                        ORDER BY account_name ASC LIMIT 50;
                    ";
                    $query = $this->db->query($prepare);                    
                }
                // var_dump($prepare);
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                          
            }
            else if($source=="accounts_maps"){
                $map_trans  = !empty($this->input->get('map_trans')) ? intval($this->input->get('map_trans')) : 0;
                $map_type   = !empty($this->input->get('map_type')) ? intval($this->input->get('map_type')) : 0;
                
                if((intval($map_trans) > 0) and (intval($map_type) > 0)){
                    if(!empty($terms)){
                        $query = $this->db->query("
                            SELECT account_id AS id, account_name AS nama,
                                (SELECT CONCAT(`account_code`,' - ',`account_name`)) AS `text`
                            FROM accounts_maps
                            LEFT JOIN accounts ON account_map_account_id=account_id
                            WHERE  account_name LIKE '%".$terms."%' AND account_map_for_transaction =".$map_trans." AND account_map_type=".$map_type." AND account_branch_id=".$session_branch_id." 
                            ORDER BY account_name ASC LIMIT 50;
                        ");                                
                    }else{
                        $query = $this->db->query("
                            SELECT account_id AS id, account_name AS nama,
                                (SELECT CONCAT(`account_code`,' - ',`account_name`)) AS `text`
                            FROM accounts_maps
                            LEFT JOIN accounts ON account_map_account_id=account_id
                            WHERE account_map_for_transaction =".$map_trans." AND account_map_type=".$map_type." AND account_branch_id=".$session_branch_id." 
                            ORDER BY account_name ASC LIMIT 50;
                        ");                    
                    }
                    $result = $query->result();
                }else{
                    $result = array();
                }
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));
            }
            else if($source=="units"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT unit_name AS id, unit_note AS nama,
                            (SELECT CONCAT(IFNULL(`unit_name`,''))) AS `text`
                        FROM units
                        WHERE unit_name LIKE '%".$terms."%' AND unit_flag=1
                    ");
                    // AND unit_branch_id=".$session_branch_id." 
                }else{
                    $query = $this->db->query("
                        SELECT unit_name AS id, unit_note AS nama,
                            (SELECT CONCAT(IFNULL(`unit_name`,''))) AS `text`
                        FROM units
                        WHERE unit_flag=1 ORDER BY unit_name ASC LIMIT 50;
                    ");      
                    // unit_branch_id=".$session_branch_id." AND               
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }   
            else if($source=="locations"){
                if(!empty($terms)){                            
                    $query = $this->db->query("
                        SELECT location_id AS id, IFNULL(location_code,'-') AS kode, location_name AS nama,
                            (SELECT CONCAT(IFNULL(`location_name`,''))) AS `text`
                        FROM locations
                        WHERE location_flag=1 AND (location_code LIKE '%".$terms."%' OR location_name LIKE '%".$terms."%') 
                        
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT location_id AS id, IFNULL(location_code,'-') AS kode, location_name AS nama,
                            (SELECT CONCAT(IFNULL(`location_name`,''))) AS `text`
                        FROM locations
                        WHERE location_flag=1 AND (location_code LIKE '%".$terms."%' OR location_name LIKE '%".$terms."%') 
                        ORDER BY location_name ASC LIMIT 20
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=='villages'){ //Kelurahan
                if(!empty($terms)){                            
                    $query = $this->db->query("
                        SELECT village_id AS id, village_name AS nama,
                            (SELECT CONCAT(IFNULL(`village_name`,''),', ',IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text`,
                            (SELECT CONCAT('Kel. ',IFNULL(`village_name`,''),', Kec. ',IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text_format`
                        FROM villages
                        LEFT JOIN districts ON villages.village_district_id=districts.district_id
                        LEFT JOIN cities ON districts.district_city_id=cities.city_id
                        LEFT JOIN provinces ON cities.city_province_id=provinces.province_id
                        WHERE village_name LIKE '%".$terms."%' AND village_flag=1
                        ORDER BY village_name ASC
                    ");
                }else{     
                    $query = $this->db->query("
                        SELECT village_id AS id, village_name AS nama,
                            (SELECT CONCAT(IFNULL(`village_name`,''),', ',IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text`,
                            (SELECT CONCAT('Kel. ',IFNULL(`village_name`,''),', Kec. ',IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text_format`
                        FROM villages
                        LEFT JOIN districts ON villages.village_district_id=districts.district_id
                        LEFT JOIN cities ON districts.district_city_id=cities.city_id
                        LEFT JOIN provinces ON cities.city_province_id=provinces.province_id
                        WHERE village_flag=1
                        ORDER BY village_name ASC LIMIT 20
                    ");                             
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                )); 
            }
            else if($source=='districts'){ //Kecamatan
                // $province_id = !empty($this->input->get('province_id')) ? $this->input->get('province_id') : 0;                
                $city_id = !empty($this->input->get('city_id')) ? $this->input->get('city_id') : 0;

                $where_search = '';
                $where_and = '';
                if($city_id > 0){
                    $where_search = 'AND city_id='.$city_id;
                    $where_and = 'AND city_id='.$city_id;
                }

                if(!empty($terms)){ 
                    $query = $this->db->query("
                        SELECT district_id AS id, district_name AS nama,
                            (SELECT CONCAT(IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text`,
                            (SELECT CONCAT('Kec. ',IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text_format`
                        FROM districts
                        LEFT JOIN cities ON districts.district_city_id=cities.city_id
                        LEFT JOIN provinces ON cities.city_province_id=provinces.province_id                        
                        WHERE district_name LIKE '%".$terms."%' AND district_flag=1
                        $where_search
                        ORDER BY district_name ASC
                    ");
                }else{     
                    $query = $this->db->query("
                        SELECT district_id AS id, district_name AS nama,
                            (SELECT CONCAT(IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text`,
                            (SELECT CONCAT('Kec. ',IFNULL(`district_name`,''),', ',IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text_format`
                        FROM districts
                        LEFT JOIN cities ON districts.district_city_id=cities.city_id
                        LEFT JOIN provinces ON cities.city_province_id=provinces.province_id                        
                        WHERE district_flag=1
                        $where_and
                        ORDER BY district_name ASC LIMIT 100
                    ");                             
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                )); 
            }
            else if($source=='cities'){ //Kota & Kab 
                $province_id = !empty($this->input->get('province_id')) ? $this->input->get('province_id') : 0;
                $where = '';
                $where_and = '';
                if($province_id > 0){
                    $where = 'WHERE province_id='.$province_id;
                    $where_and = 'AND province_id='.$province_id;
                }

                if(!empty($terms)){                            
                    $query = $this->db->query("
                        SELECT city_id AS id, city_name AS nama, province_id, province_name,
                            (SELECT CONCAT(IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text`,
                            (SELECT CONCAT(IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text_format`
                        FROM cities
                        LEFT JOIN provinces ON cities.city_province_id=provinces.province_id                          
                        WHERE city_name LIKE '%".$terms."%' OR province_name LIKE '%".$terms."%'
                        $where_and
                        ORDER BY city_name ASC
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT city_id AS id, city_name AS nama, province_id, province_name,
                            (SELECT CONCAT(IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text`,
                            (SELECT CONCAT(IFNULL(`city_name`,''),', ',IFNULL(`province_name`,''))) AS `text_format`
                        FROM cities
                        LEFT JOIN provinces ON cities.city_province_id=provinces.province_id
                        $where                        
                        ORDER BY city_name ASC LIMIT 100
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                )); 
            }
            else if($source=="provinces"){ //Provinsi
                if(!empty($terms)){                            
                    $query = $this->db->query("
                        SELECT province_id AS id, province_name AS nama,
                            (SELECT CONCAT(IFNULL(`province_name`,''))) AS `text`
                        FROM provinces
                        WHERE province_name LIKE '%".$terms."%'
                        ORDER BY province_name ASC
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT province_id AS id, province_name AS nama,
                            (SELECT CONCAT(IFNULL(`province_name`,''))) AS `text`
                        FROM provinces  
                        ORDER BY province_name ASC LIMIT 20
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="news"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT news_id AS id, news_title AS nama,
                            (SELECT CONCAT(IFNULL(`news_title`,''))) AS `text`
                        FROM news
                        WHERE news_title LIKE '%".$terms."%'
                        AND news_flag=1 AND news_branch_id=".$session_branch_id." AND news_type=".$tipe."
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT news_id AS id, news_title AS nama,
                            (SELECT CONCAT(IFNULL(`news_title`,''))) AS `text`
                        FROM news
                        WHERE news_flag=1 AND news_branch_id=".$session_branch_id." AND news_type=".$tipe." ORDER BY news_title ASC LIMIT 10;
                    ");                    
                }
                $result = $query->result();
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }
            else if($source=="devices"){
                $where_and = "";
                if($tipe=='WhatsApp'){
                    $where_and = "device_media='WhatsApp' AND ";
                }else if($tipe=='Email'){
                    $where_and = "device_media='Email' AND ";
                }else{
                    $where_and = "device_media='WhatsApp' AND ";                
                }

                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT device_id AS id, 
                            CASE WHEN device_media = 'WhatsApp' THEN device_number WHEN device_media = 'Email' THEN device_mail_email ELSE 'Unknown' END AS `nama`,
                            CASE WHEN device_media = 'WhatsApp' THEN device_number WHEN device_media = 'Email' THEN device_mail_email ELSE 'Unknown' END AS `text`
                        FROM devices
                        WHERE ".$where_and." device_number LIKE '%".$terms."%'
                        AND device_flag=1 AND device_branch_id=".$session_branch_id."
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT device_id AS id, 
                            CASE WHEN device_media = 'WhatsApp' THEN device_number WHEN device_media = 'Email' THEN device_mail_email ELSE 'Unknown' END AS `nama`,
                            CASE WHEN device_media = 'WhatsApp' THEN device_number WHEN device_media = 'Email' THEN device_mail_email ELSE 'Unknown' END AS `text`                        
                        FROM devices
                        WHERE ".$where_and." device_flag=1 AND device_branch_id=".$session_branch_id." ORDER BY device_number ASC LIMIT 10;
                    ");                    
                }
                $result = $query->result();
                // var_dump($this->db->last_query()); die;
                $json = array_push($result,array(
                    'id' => "0",
                    'nama' => '-- Ketik yg ingin di cari --',
                    'text' => '-- Ketik yg ingin di cari --'
                ));                
            }            
            //For Message Instant Messaging
            else if($source=="contacts_instant_messaging"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT contact_id AS id, contact_code AS kode, contact_name AS nama, IFNULL(contact_phone_1,'-') AS telepon, IFNULL(contact_email_1,'-') AS email, 
                            IF(contact_type=1,'Supplier','Customer') AS kontak_tipe,
                            (SELECT CONCAT(IFNULL(`contact_phone_1`,''),' <',IFNULL(`contact_name`,''),'>')) AS `text`
                        FROM contacts
                        WHERE contact_code LIKE '%".$terms."%' OR contact_name LIKE '%".$terms."%' 
                        AND contact_flag=1 AND contact_type LIKE '%".$tipe."%' AND contact_branch_id=".$session_branch_id."
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT contact_id AS id, contact_name AS nama, IFNULL(contact_phone_1,'-') AS telepon, IFNULL(contact_email_1,'-') AS email,
                            IF(contact_type=1,'Supplier','Customer') AS kontak_tipe,
                            (SELECT CONCAT(IFNULL(`contact_phone_1`,''),' <',IFNULL(`contact_name`,''),'>')) AS `text`
                        FROM contacts
                        WHERE contact_type LIKE '%".$tipe."%' AND contact_flag=1 AND contact_branch_id=".$session_branch_id." 
                        ORDER BY contact_name ASC LIMIT 20
                    ");                    
                }

                $result = $query->result();
                // $json = array_push($result,array(
                //     'id' => "0",
                //     'nama' => '-- Ketik yg ingin di cari --',
                //     'text' => '-- Ketik yg ingin di cari --'
                // ));                  
            } 
            else if($source=="contacts_categories"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT category_id AS id, category_name AS text, category_count_data AS total 
                        FROM categories
                        WHERE category_type=4 AND category_name LIKE '%".$terms."%' AND category_branch_id=".$session_branch_id."
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT category_id AS id, category_name AS text, category_count_data AS total 
                        FROM categories
                        WHERE category_type=4 AND category_branch_id=".$session_branch_id." 
                        ORDER BY category_name ASC LIMIT 20
                    ");                    
                }

                $result = $query->result();
                // $json = array_push($result,array(
                //     'id' => "0",
                //     'nama' => '-- Ketik yg ingin di cari --',
                //     'text' => '-- Ketik yg ingin di cari --'
                // ));                  
            }          
            else if($source=="contacts_types"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT contact_type AS id, contact_type_name AS text, COUNT(*) AS total 
                        FROM contacts
                        WHERE contact_flag=1 AND contact_type_name LIKE '%".$terms."%' AND contact_branch_id=".$session_branch_id."
                        GROUP BY contact_type
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT contact_type AS id, contact_type_name AS text, COUNT(*) AS total 
                        FROM contacts
                        WHERE contact_flag=1 AND contact_branch_id=".$session_branch_id." 
                        GROUP BY contact_type
                        ORDER BY contact_type_name ASC LIMIT 20
                    ");                    
                }

                $result = $query->result();
                // $json = array_push($result,array(
                //     'id' => "0",
                //     'nama' => '-- Ketik yg ingin di cari --',
                //     'text' => '-- Ketik yg ingin di cari --'
                // ));                  
            }
            else if($source=="recipients_groups"){
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT group_id AS id, group_name AS text, group_count AS total 
                        FROM recipients_groups
                        WHERE group_flag=1 AND group_name LIKE '%".$terms."%' AND group_branch_id=".$session_branch_id."
                        GROUP BY contact_type
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT group_id AS id, group_name AS text, group_count AS total 
                        FROM recipients_groups
                        WHERE group_flag=1 AND group_branch_id=".$session_branch_id." 
                        ORDER BY group_name ASC LIMIT 20
                    ");                    
                }

                $result = $query->result();
                // $json = array_push($result,array(
                //     'id' => "0",
                //     'nama' => '-- Ketik yg ingin di cari --',
                //     'text' => '-- Ketik yg ingin di cari --'
                // ));                  
            }
            else if($source=="recipients_birthday"){
                $day = 30;
                if(!empty($terms)){
                    $query = $this->db->query("
                        SELECT recipient_id AS id, recipient_name AS name, recipient_birth AS birthday, 
                        CONCAT(recipient_name,' (',DATE_FORMAT(recipient_birth,'%d %b'),DATE_FORMAT(NOW(),' %Y'),')') AS text
                        FROM recipients
                        WHERE recipient_flag=1 AND recipient_name LIKE '%".$terms."%' AND recipient_branch_id=".$session_branch_id." 
                        AND DATE_FORMAT(recipient_birth,'%m %d') BETWEEN DATE_FORMAT(CURDATE(),'%m %d') AND DATE_FORMAT(INTERVAL ".$day." DAY + CURDATE(),'%m %d')
                        ORDER BY DATE_FORMAT(recipient_birth,'%m %d')
                    ");
                }else{
                    $query = $this->db->query("
                        SELECT recipient_id AS id, recipient_name AS name, recipient_birth AS birthday, 
                        CONCAT(recipient_name,' (',DATE_FORMAT(recipient_birth,'%d %b'),DATE_FORMAT(NOW(),' %Y'),')') AS text
                        FROM recipients
                        WHERE recipient_flag=1 AND recipient_branch_id=".$session_branch_id." 
                        AND DATE_FORMAT(recipient_birth,'%m %d') BETWEEN DATE_FORMAT(CURDATE(),'%m %d') AND DATE_FORMAT(INTERVAL ".$day." DAY + CURDATE(),'%m %d')
                        ORDER BY DATE_FORMAT(recipient_birth,'%m %d')
                    ");                    
                }

                $result = $query->result();
                // $json = array_push($result,array(
                //     'id' => "0",
                //     'nama' => '-- Ketik yg ingin di cari --',
                //     'text' => '-- Ketik yg ingin di cari --'
                // ));                  
            }                       
        }else{
            $result[] = array(
                'id' => '0',
                'text' => 'Ketik minimal 3 karakter'
            );            
        }        
        echo json_encode($result);
    }
}
?>