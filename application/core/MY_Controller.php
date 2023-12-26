<?php

class MY_Controller extends CI_Controller{

	public function __construct(){
		parent::__construct();

        date_default_timezone_set('Asia/Jakarta');
        ini_set("date.timezone", "Asia/Jakarta");

		$this->load->library('session');
        $this->load->helper('url');
        $this->load->config('email');
        $this->load->config('whatsapp');
        $this->load->config('firebase');
        $this->load->library('email');
        $this->load->library('phpmailer_lib');

        // $this->load->model('Kontak_model');
        $this->load->model('Survey_model');
        $this->load->model('Message_model');
        $this->load->model('Device_model');

		if(!$this->session->userdata()){
			redirect(base_url("login"));
		} else {
			// $this->check_session();
		}
	}
	private function check_session($user_name = null){
		$userdata = $this->session->userdata('user_data');
		if($user_name){
			if($user_name != $userdata['user_name']){
				redirect('logout');
			}
		}
        $last_time = $userdata['last_time'];
        $curr_time = time();
        $mins = ($curr_time - $last_time) / 60;
        if ($mins > 20) {
            // redirect('logouts');
        } else {
            $userdata['last_time'] = $curr_time;
            $this->session->set_userdata('userdata', $userdata);
        }
	}
    public function is_logged_in(){
        $user = $this->session->userdata('user_data');
        return isset($user);
    }
    public function get_modul_task($modul,$modul_task){
    	$this->load->model('Coa_model');
        $modul_result = $this->Coa_model->get_coa_jurnal($modul,urldecode($modul_task));
        if(isset($modul_result['id_coa'])){
            $moduls = array(
                'coa_id' => $modul_result['id_coa'],
                'coa_kode' => $modul_result['kode_coa'],
                'coa_nama' => $modul_result['nama_coa'],
                'modul' => $modul_result['modul'],
                'modul_task' => $modul_result['modul_task'],
                'type' => $modul_result['type']
            );
            $result = array('status' => 1,
                'message' => 'Success', 'modul' => $modul, 'modul_task' => urldecode($modul_task),
                'data' => $moduls
            );
        }else{
            $result = array('status' => 0,
                'message' => 'COA Jurnal Not Found', 'modul' => $modul, 'modul_task' => urldecode($modul_task),
                'data' => ''
            );
        }
        // $this->output->set_content_type('application/json');
        // $this->output->set_output(json_encode($result));
        return $result;
    }
    public function get_modul_task_json($modul,$modul_task){
        $this->load->model('Coa_model');
        $modul_result = $this->Coa_model->get_coa_jurnal($modul,urldecode($modul_task));
        if(isset($modul_result['id_coa'])){
            $moduls = array(
                'coa_id' => $modul_result['id_coa'],
                'coa_kode' => $modul_result['kode_coa'],
                'coa_nama' => $modul_result['nama_coa'],
                'modul' => $modul_result['modul'],
                'modul_task' => $modul_result['modul_task'],
                'type' => $modul_result['type']
            );
            $result = array('status' => 1,
                'message' => 'Success', 'modul' => $modul, 'modul_task' => urldecode($modul_task),
                'data' => $moduls
            );
        }else{
            $result = array('status' => 0,
                'message' => 'COA Jurnal Not Found', 'modul' => $modul, 'modul_task' => urldecode($modul_task),
                'data' => ''
            );
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
        // return $result;
    }
    public function time_ago($timestamp){
        $selisih = time() - strtotime($timestamp) ;
        $detik = $selisih ;
        $menit = round($selisih / 60 );
        $jam = round($selisih / 3600 );
        $hari = round($selisih / 86400 );
        $minggu = round($selisih / 604800 );
        $bulan = round($selisih / 2419200 );
        $tahun = round($selisih / 29030400 );

        if ($detik <= 60) {
            $waktu = $detik.' detik yang lalu';
        } else if ($menit <= 60) {
            $waktu = $menit.' menit yang lalu';
        } else if ($jam <= 24) {
            $waktu = $jam.' jam yang lalu';
        } else if ($hari <= 7) {
            $waktu = $hari.' hari yang lalu';
        } else if ($minggu <= 4) {
            $waktu = $minggu.' minggu yang lalu';
        } else if ($bulan <= 12) {
            $waktu = $bulan.' bulan yang lalu';
        } else {
            $waktu = $tahun.' tahun yang lalu';
        }
        return $waktu;
    }
    public function say_number($x){ #1200 -> seribu dua ratus
        $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($x < 12){
            return " " . $abil[$x];
        }
        elseif ($x < 20){
            return $this->say_number($x - 10) . " belas";
        }
        elseif ($x < 100){
            return $this->say_number($x / 10) . " puluh" . $this->say_number($x % 10);
        }
        elseif ($x < 200){
            return " seratus" . $this->say_number($x - 100);
        }
        elseif ($x < 1000){
            return $this->say_number($x / 100) . " ratus" . $this->say_number($x % 100);
        }
        elseif ($x < 2000){
            return " seribu" . $this->say_number($x - 1000);
        }
        elseif ($x < 1000000){
            return $this->say_number($x / 1000) . " ribu" . $this->say_number($x % 1000);
        }
        elseif ($x < 1000000000){
            return $this->say_number($x / 1000000) . " juta" . $this->say_number($x % 1000000);
        }
        elseif ($x < 1000000000000){
            return $this->say_number($x / 1000000000) . " milyar" . $this->say_number($x % 1000000000);
        }
        elseif ($x < 1000000000000000){
            return $this->say_number($x / 1000000000000) . " milyar" . $this->say_number($x % 1000000000000);
        }
    }
    public function save_activity($params){
        $this->load->model('Aktivitas_model');
        $add_activity=$this->Aktivitas_model->add_aktivitas($params);
    }
    public function request_number_for_transaction($tipe){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];

        $tgl = date('d-m-Y');
        $tahun = substr($tgl, 6, 4);
        $bulan = substr($tgl, 3, 2);
        $hari = substr($tgl, 0, 2);
        $tahun2 = substr($tgl, 8, 2);

        $query = $this->db->query("SELECT MAX(RIGHT(trans_number,5)) AS last_number
            FROM trans
            WHERE YEAR(trans_date_created)=$tahun
            AND MONTH(trans_date_created)=$bulan
            AND trans_branch_id=$session_branch_id
            AND trans_type=$tipe");
        $nomor = "";
        if ($query->num_rows() > 0){
            foreach ($query->result() as $v){
                $temp = ((int) $v->last_number) + 1;
                $nomor = sprintf("%05s", $temp);
            }
        }else{
            $nomor = "00001";
        }
        $get_init = $this->db->query("SELECT type_doc FROM `types` WHERE type_for=2 AND type_type=$tipe");
        $init = $get_init->row_array();
        $auto_number = $init['type_doc'] . '-' . $tahun2 . $bulan . '-' . $nomor;
        return $auto_number;
    }
    public function request_number_for_order($tipe){
        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];

        $tgl = date('d-m-Y');
        $tahun = substr($tgl, 6, 4);
        $bulan = substr($tgl, 3, 2);
        $hari = substr($tgl, 0, 2);
        $tahun2 = substr($tgl, 8, 2);

        $query = $this->db->query("SELECT MAX(RIGHT(order_number,5)) AS last_number
            FROM orders
            WHERE YEAR(order_date_created)=$tahun
            AND MONTH(order_date_created)=$bulan
            AND order_branch_id=$session_branch_id
            AND order_type=$tipe");
        $nomor = "";
        if ($query->num_rows() > 0){
            foreach ($query->result() as $v){
                $temp = ((int) $v->last_number) + 1;
                $nomor = sprintf("%05s", $temp);
            }
        }else{
            $nomor = "00001";
        }

        $get_init = $this->db->query("SELECT type_doc FROM `types` WHERE type_for=1 AND type_type=$tipe");
        $init = $get_init->row_array();        
        $auto_number = $init['type_doc'] . '-' . $tahun2 . $bulan . '-' . $nomor;
        return $auto_number;
    }
    public function request_number_for_journal($tipe){

        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];

        $tgl = date('d-m-Y');
        $tahun = substr($tgl, 6, 4);
        $bulan = substr($tgl, 3, 2);
        $hari = substr($tgl, 0, 2);
        $tahun2 = substr($tgl, 8, 2);

        $query = $this->db->query("SELECT MAX(RIGHT(journal_number,5)) AS last_number
            FROM journals
            WHERE YEAR(journal_date_created)=$tahun
            AND MONTH(journal_date_created)=$bulan
            AND journal_branch_id=$session_branch_id
            AND journal_type=$tipe");
        $nomor = "";
        if ($query->num_rows() > 0){
            foreach ($query->result() as $v){
                $temp = ((int) $v->last_number) + 1;
                $nomor = sprintf("%05s", $temp);
            }
        }else{
            $nomor = "00001";
        }
        $get_init = $this->db->query("SELECT type_doc FROM `types` WHERE type_for=3 AND type_type=$tipe");
        $init = $get_init->row_array();             
        $auto_number = $init['type_doc'] . '-' . $tahun2 . $bulan . '-' . $nomor;
        return $auto_number;
    }
    public function random_code($length){ # JEH3F2
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[rand(0, $txtlen)];}
        return $result;
    }
    public function random_number($length){ # JEH3F2
        $text = '1234567890';
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[rand(0, $txtlen)];}
        return $result;
    }
    public function random_session($length){
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ'.time();
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[mt_rand(0, $txtlen)];}
        return $result;
    }
    public function get_account_map_for_transaction($branch,$map_for,$map_type){
        /*
            1=Pembelian
            2=Penjualan
            3=Persediaan
            4=Hutang Piutang
            10=Lain
        */
        $result = array();
        $this->db->select('account_id, account_code, account_name');
        $this->db->from('accounts_maps');
        $this->db->join('accounts','account_map_account_id=account_id','left');
        $this->db->where('account_map_branch_id',$branch);
        $this->db->where('account_map_for_transaction',$map_for);
        $this->db->where('account_map_type',$map_type);
        $result = $this->db->get()->row_array();
        return $result;
    }
    /*
    public function journal_for_transaction($operation,$trans_id){

        $session = $this->session->userdata();
        $session_branch_id = $session['user_data']['branch']['id'];
        $session_user_id = $session['user_data']['user_id'];

        $this->load->model('Transaksi_model');
        $this->load->model('Account_model');
        $this->load->model('Account_map_model');
        $this->load->model('Kontak_model');
        $this->load->model('Journal_model');

        // For 1=Pembelian, 2=Penjualan
        // Operation create,update,delete

        $get_trans = $this->Transaksi_model->get_transaksi($trans_id);
        $get_contact = $this->Kontak_model->get_kontak($get_trans['contact_id']);

        $trans_contact_id = $get_contact['contact_id'];
        $trans_type = intval($get_trans['trans_type']);

        $account_payable_id = $get_contact['contact_account_payable_account_id'];
        $account_receivable_id = $get_contact['contact_account_receivable_account_id'];

        // var_dump($trans_type);die;
        if($trans_type==1){
            //Persediaan        D
            //PPN               D
            //Hutang Usaha          K

            $params_inventory = array(
                'trans_item_trans_id' => $trans_id
            );
            $params_no_ppn = array(
                'trans_item_trans_id' => $trans_id,
                'trans_item_ppn' => 0
            );
            $params_ppn = array(
                'trans_item_trans_id' => $trans_id,
                'trans_item_ppn' => 1
            );

            //Persediaan
            $get_inventory = $this->Transaksi_model->get_all_transaksi_items($params_inventory,null,null,null,'trans_item_id','asc');
            foreach ($get_inventory as $v) {
                $params_set_inventory = array(
                    'journal_item_type' => 1,
                    'journal_item_trans_id' => $trans_id,
                    'journal_item_date' => $get_trans['trans_date'],
                    'journal_item_account_id' => 42,
                    'journal_item_debit' => $v['trans_item_total'],
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_user_id' => $session_user_id,
                    'journal_item_date_created' => date("YmdHis"),
                    'journal_item_date_updated' => date("YmdHis"),
                    'journal_item_flag' => 1
                );
                // $total = $v['trans_item_in_price'];
                $set_data = $this->Journal_model->add_journal_item($params_set_inventory);
            }

            $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
            $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

            $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price'];
            $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

            //Set Value Ppn & Hutang
            $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
            $set_total_ppn = $total_ppn*0.1;

            //PPn Masukan
                $params_check_ppn = array(
                    'trans_item_trans_id'=> $trans_id,
                    'trans_item_ppn' => 1
                );
                $check_transaction_is_ppn = $this->Transaksi_model->get_all_transaksi_items_count($params_check_ppn);
                if($check_transaction_is_ppn > 0){
                    $params_ppn_income = array(
                        'journal_item_type' => 1,
                        'journal_item_trans_id' => $trans_id,
                        'journal_item_date' => $get_trans['trans_date'],
                        'journal_item_account_id' => 31,
                        'journal_item_debit' => $set_total_ppn,
                        'journal_item_branch_id' => $session_branch_id,
                        'journal_item_user_id' => $session_user_id,
                        'journal_item_date_created' => date("YmdHis"),
                        'journal_item_date_updated' => date("YmdHis"),
                        'journal_item_flag' => 1
                    );
                    $set_data = $this->Journal_model->add_journal_item($params_ppn_income);
                }

            //Hutang
                $params_account_payable = array(
                    'journal_item_type' => 1,
                    'journal_item_trans_id' => $trans_id,
                    'journal_item_date' => $get_trans['trans_date'],
                    'journal_item_account_id' => $account_payable_id,
                    'journal_item_credit' => $set_total,
                    'journal_item_branch_id' => $session_branch_id,
                    'journal_item_user_id' => $session_user_id,
                    'journal_item_date_created' => date("YmdHis"),
                    'journal_item_date_updated' => date("YmdHis"),
                    'journal_item_flag' => 1
                );
                $set_data = $this->Journal_model->add_journal_item($params_account_payable);
        }

        $next = false;
        if($next==true){
            if($operation == 'create'){
                if(intval($trans_type) == 1){ //Pembelian

                    $params_no_ppn = array(
                        'trans_item_trans_id' => $trans_id,
                        'trans_item_ppn' => 0
                    );
                    $params_ppn = array(
                        'trans_item_trans_id' => $trans_id,
                        'trans_item_ppn' => 1
                    );
                    $get_total_trans_item_no_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_no_ppn);
                    $get_total_trans_item_ppn = $this->Transaksi_model->get_transaksi_item_in_price_total($trans_id,$params_ppn);

                    $total_no_ppn = $get_total_trans_item_no_ppn['trans_item_in_price'];
                    $total_ppn = $get_total_trans_item_ppn['trans_item_in_price'];

                    $set_total = $total_no_ppn + ($total_ppn + ($total_ppn*0.1));
                    $params_total = array(
                        'trans_total' => $set_total
                    );
                }
                if(intval($trans_type) == 2){ //Penjualan

                }
            }
            else if($operation == 'update'){}
            else if($operation == 'delete'){}
            else{ }
        }
    }
    */
    public function journal_for_transaction($operation,$trans_id){
        $prepare = "CALL sp_journal_from_transaction('$operation',$trans_id)";
        $query=$this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        // $query->next_result();
        // $query->free_result();
        // log_message('debug',$prepare);
        return $result;
    }
    public function journal_for_asset($operation,$asset_id){
        $prepare = "CALL sp_journal_from_asset('$operation',$asset_id)";
        $query=$this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        // $query->next_result();
        // $query->free_result();
        // log_message('debug',$prepare);
        return $result;
    }        
    public function journal_for_order($operation,$order_id){
        $prepare = "CALL sp_journal_from_order('$operation',$order_id)";
        $query=$this->db->query($prepare);
        $result = $query->result_array();
        mysqli_next_result($this->db->conn_id);
        // $query->next_result();
        // $query->free_result();
        // log_message('debug',$prepare);
        return $result;
    }
    public function product_stock($action,$product_id,$location_id){
        $prepare ="CALL sp_product_stock($action,$product_id,$location_id)";
        // var_dump($prepare);die;        
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        $result = $query->result_array();
        return $result;
    }
    public function set_account_from_setup_accounts_items($branch_id,$specialist_id){
        $prepare ="CALL sp_setup_account_from_branch($branch_id,$specialist_id)";
        // var_dump($prepare);die;
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        $result = $query->result_array();
        return $result;
    }
    public function set_user_menu_from_setup_branch($user_id){
        $prepare ="CALL sp_setup_user_menu_from_branch($user_id)";
        // var_dump($prepare);die;
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        $result = $query->result_array();
        return $result;
    }
    public function trans_item_out($type,$date,$trans_id,$branch_id,$product_id,$location_id,
        $product_unit,$out_qty,$out_price_sell,$discount,$ppn,$ppn_value,$note,$user_id,$flag,$qty_pack){
        $prepare="CALL sp_trans_item_out($type,'$date',$trans_id,$branch_id,$product_id,$location_id,'$product_unit',$out_qty,$out_price_sell,$discount,$ppn,'$ppn_value','$note',$user_id,$flag,$qty_pack)";
        $query=$this->db->query($prepare);
        $result = $query->row_array();
        // log_message('debug',$prepare);
        // log_message('debug',$result);
        mysqli_next_result($this->db->conn_id);
        $query->free_result();
        return true;
    }
    public function trans_item_out_and_in($type,$date,$trans_id,$branch_id,$product_id,$location_id,$location_to,
        $product_unit,$out_qty,$out_price_sell,$discount,$ppn,$ppn_value,$note,$user_id,$flag,$session){
        $prepare="CALL sp_trans_item_out_and_in($type,'$date',$trans_id,$branch_id,$product_id,$location_id,$location_to,'$product_unit',$out_qty,$out_price_sell,$discount,$ppn,$ppn_value,'$note',$user_id,$flag,'$session')";
        $query=$this->db->query($prepare);
        $result = $query->row_array();
        // log_message('debug',$prepare);
        // log_message('debug',$result);
        mysqli_next_result($this->db->conn_id);
        $query->free_result();
        return true;
    }
    public function report_product_stock($action,$start,$end,$branch_id,$location_id,$product_id,$order,$dir,$search = null,$category = 0){
        $prepare="CALL sp_report_stock($action,'$start','$end',$branch_id,$location_id,$product_id,'$order','$dir','$search',$category)";
        // log_message('debug',$prepare);
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        $result = $query->result_array();
        return $result;
    }
    public function report_finance($action,$start,$end,$branch_id,$account_id,$search){
        $prepare="CALL sp_report_finance($action,'$start','$end',$branch_id,$account_id,'')";
        // log_message('debug',$prepare);
        // var_dump($prepare);die;
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        // $query->free_result();
        $result = $query->result_array();
        $query->free_result();        
        return $result;
    }
    public function report_cashflow($action,$start,$end,$branch_id,$account_id,$search,$limit_start,$limit_end){
        $prepare="CALL sp_report_cashflow($action,'$start','$end',$branch_id,$account_id,$search,$limit_start,$limit_end)";
        // log_message('debug',$prepare);
        // var_dump($prepare);die;
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        // $query->free_result();
        $result = $query->result_array();
        return $result;
    }    
    public function trans_history($trans_id){
        $prepare="CALL sp_trans_history($trans_id)";
        // log_message('debug',$prepare);
        // var_dump($prepare);die;
        $query=$this->db->query($prepare);
        mysqli_next_result($this->db->conn_id);
        // $query->free_result();
        $result = $query->result_array();
        return $result;
    }    
    public function lowercase($var){ //testing
        $final=strtolower($var);
        return $final;
    }
    public function uppercase($var){ //TESTING
        $final=strtoupper($var);
        return $final;
    }
    public function sentencecase($var){ //Testing
        $final=ucfirst($var);
        return $final;
    }
    public function capitalize($var){ // Testing Bro
        $var=strtolower($var);
        $final=ucwords($var);
        return $final;
    }
    public function safe($var){
        $v = trim($var);
        $v = strip_tags($v);
        $v = htmlentities($v);
        $v = strtolower($v);
        $v = ucwords($v);
        return $v;
    }
    public function safe_url($v){
        $v = trim($v);
        $v = strip_tags($v);
        $v = htmlentities($v);
        // $v = strtolower($v);
        $v = str_replace(' ','',$v);
        if (strpos($v, "http") === 0) {
            $r=$v;
        } else {
            $r='https://'.$v;
        }

        return $r;        
    }
    public function generate_seo_link($var){
        $v = trim($var);
        $v = strtolower($v);
        $v = preg_replace('/[^a-zA-Z0-9\s\-]/','',$v);
        $v = str_replace(' ','-',$v);
        return $v;
    }
    public function product_type($index,$value){
        // var_dump($index);
        $var = array(
            '0' => 'Semua Properti',
            '1' => 'Tanah',
            '2' => 'Rumah',
            '3' => 'Apartemen',
            '4' => 'Ruko',
            '5' => 'Perkantoran',
            '6' => 'Pabrik',
            '7' => 'Vila',
            '8' => 'Gudang'
            // '9' => 'Kondominium'
        );
        if(!empty($index) or !empty($value)){
            if($index == null){
                return array_search($value,$var);
            }
            if($value == null){
                return $var[$index];
            }
        }else{
            return $var;
        }
    }
    public function get_email_config(){
        $return = array(
            'protocol' => $this->config->item('protocol'),
            'smtp_auth' => $this->config->item('smtp_auth'),
            'smtp_host' => $this->config->item('smtp_host'),
            'smtp_port' => $this->config->item('smtp_port'),
            'smtp_user' => $this->config->item('smtp_user'),
            'smtp_pass' => $this->config->item('smtp_pass'),
            'mail_set_from' => $this->config->item('mail_set_from'),
            'mail_set_reply_to' => $this->config->item('mail_set_reply_to'),
            'mail_set_from_alias' => $this->config->item('mail_set_from_alias'),
            'smtp_crypto' => $this->config->item('smtp_crypto'),
            'mailtype' => $this->config->item('mailtype'),
            'smtp_timeout' => $this->config->item('smtp_timeout'),
            'charset' => $this->config->item('charset'),
            'wordwrap' => $this->config->item('wordwrap')
        );
        return $return;
    }
    public function get_firebase_config(){
        $return = array(
            'apiKey' => $this->config->item('apiKey'),
            'authDomain' => $this->config->item('authDomain'),
            'databaseURL' => $this->config->item('databaseURL'),
            'projectId' => $this->config->item('projectId'),
            'storageBucket' => $this->config->item('storeageBucket'),
            'messagingSenderId' => $this->config->item('messageSenderId'),
            'appId' => $this->config->item('appId'),
            'measurementId' => $this->config->item('measurementId'),
        );
        return $return;
    }
    public function encrypt_decrypt($action, $string){
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27'; // user define private key
        $secret_iv = 'm45t3r'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
        if ($action == 'e') { //encrypt
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }else if($action == 'd') { //decrypt
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    public function age($tanggal){
        list($thn,$bln,$tgl) = explode("-",$tanggal);
        $fthn = date("Y");
        $fbln = date("m");
        $ftgl = date("d");
        $pas=gregoriantojd($bln,$tgl,$thn);
        $now=gregoriantojd($fbln,$ftgl,$fthn);
        $umur=$now-$pas;
        $t=floor($umur/365);
        return $t." years old"; 
    }
    public function generate_username($string){
        $final = strtolower($string);
        $final = str_replace(' ','.',$final);
        return $final;        
    }
    public function file_upload_image($dir = "", $img_data = ""){
        $r          = new \stdClass();
        $r->status  = 0;
        $r->message = 'Failed';
        $r->result  = array();

        $dir = (substr($dir, -1) != "/" ? $dir . "/" : $dir); // konfig directory data
    
        // validate
        if (empty($dir)) {
            return "";
        }
        if (empty($img_data)) {
            return "";
        }
        if ($img_data == "undefined") {
            return "";
        }
    
        $img_arr_a = explode(";", $img_data);
        $img_arr_b = explode(",", $img_arr_a[1]);
    
        $file_data = base64_decode($img_arr_b[1]);
        $file_name = $this->random_number(20);
        $file_ext = ".png";

        //File Success Move
        if(file_put_contents($dir . $file_name . $file_ext, $file_data)){
            
            //Compress Image
            /*
                $compress['width']              = 64;
                $compress['height']             = 64;
                $compress['image_library']      = 'gd2';
                $compress['source_image']       = $dir . $file_name . $file_ext;
                $compress['create_thumb']       = false;
                $compress['maintain_ratio']     = true;
                $compress['new_image']          = FCPATH . $dir . $file_name . $file_ext;
                $this->load->library('image_lib', $compress);
                $this->image_lib->resize();
                $this->image_lib->clear();
            */

            $bytes = strlen(base64_decode($img_data));
            $roughsize = (((int)$bytes) / 1024.0);
            $file_size = round($roughsize,2);

            $r->status=1;
            $r->result = array(
                'file_directory' => $dir, /* upload/product/ */
                'file_name' => $file_name, /* 1231421*/
                'file_ext' => $file_ext, /* .png */
                'file_location' => $dir . $file_name . $file_ext,
                'file_size' => $file_size
            );
        }
        return $r;
    }
    public function file_upload($path = null, $file, $params = null) { //Binary Upload File
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';

        if(!empty($file) and ($file !== 'undefined')){
            $image_height   = !empty($params['height']) ? $params['height'] : 480;
            $image_width    = !empty($params['width']) ? $params['width'] : 480;

            $file_size = $file['size'] / 1024; // 94018 / 1024 = 91 byte
            $file_name = $file['name']; // apsaja.jpg
            $file_type = $file['type']; // image/jpeg
                     
            $file_config = array(
                'upload_path' => FCPATH . $path,
                'allowed_types' => 'gif|jpg|png|jpeg|pdf|doc|xml|xls|xlsx|docx'
            ); 
            $this->load->library('upload', $file_config);
            $this->upload->initialize($file_config);
            
            //Make Directory if Not Exists
            $folder = FCPATH . $path;
            if(!file_exists($folder)){
                mkdir($folder, 0775, true);
            }

            //Process Upload
            if ($this->upload->do_upload('files')) {
                $upload = $this->upload->data();
                // $rename_file = date("YmdHis") . $upload['file_ext']; //1231232.png
                $rename_file = $this->random_session(20) . $upload['file_ext'];
                // $old_name = $upload['full_path']; // abc/uoload/ABC.png
                // $new_name = $path . $raw_photo; // abc/upload/1231232.png

                if (rename($upload['full_path'], $path . $rename_file)) {
                    if($upload['is_image'] == 1){ //If Data IMAGE
                        $file_compress = [
                            'image_library' => 'gd2',
                            'source_image' => $path . $rename_file,
                            'create_thumb' => FALSE,
                            'maintain_ratio' => TRUE,
                            'width' => $image_width,
                            'height' => $image_height,
                            'new_image' => $path . $rename_file
                        ];                                    
                        $this->load->library('image_lib', $file_compress);
                        $this->image_lib->resize();
                        $file_size = ($upload['is_image'] == 1) ? filesize($path . $rename_file) : $upload['file_size'];
                        $file_size = $file_size / 1024;
                    }else{
                        $file_size = $upload['file_size'];
                    }
                }

                $return->status   = 0;
                $return->message  = 'Upload success'; 
                $return->result   = $upload;      
                $return->file     = array(
                    'name' => $rename_file, //123.jpg
                    'path' => $path, // upload/test
                    'directory' => $path . $rename_file, // upload/test/123.jpg
                    'size' => $file_size, // 91.81 in KB
                    'type' => str_replace('.','',$upload['file_ext']), // jpeg
                );     
            }else{
                $return['status'] = 0;
                $return['message'] = $this->upload->display_errors();
            }
        }else{
            $return->message = 'File not ready';
        }
        return $return;
    }      
}

?>