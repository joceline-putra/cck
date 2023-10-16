<?php

class Core_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function request_number($table,$year,$month){
        $q = $this->db->query("SELECT MAX(RIGHT(nomor,4)) AS last_number FROM $table WHERE YEAR(tgl)=$year AND MONTH(tgl)=$month");
        $kd = "";
        if($q->num_rows()>0){
            foreach($q->result() as $k){
                $tmp = ((int)$k->last_number)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        return $kd;
    }

}
