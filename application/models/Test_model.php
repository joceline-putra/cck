<?php 
/*
    @AUTHOR: Joe Witaya
*/ 
class Test_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function add_test($params)
    {
        $this->db->insert('test',$params);
        return $this->db->insert_id();
    }
    
}1

?>