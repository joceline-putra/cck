<?php
 
class Aktivitas extends MY_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Aktivitas_model');
    } 

    /*
     * Listing of aktivitas
     */
    function index()
    {
        
    }

    function manage(){
        $return = new \stdClass();
        $return->status = 0;
        $return->message = '';
        $return->result = '';
        if($this->input->post('action')){
            $action = $this->input->post('action');
            if($action=='dashboard'){

                $start = $this->input->post('start');
                $end = $this->input->post('end');
                $user = $this->input->post('user');  

                $limit_start = !empty($this->input->post('limit_start')) ? $this->input->post('limit_start') : 1;
                $limit_end = !empty($this->input->post('limit_end')) ? $this->input->post('limit_end') : 25;

                $limit = ($limit_start * $limit_end) - $limit_end;
                // $limit_start = $this->input->post('limit_start');
                // $limit_end = $this->input->post('limit_end');         

                  // $limit_start = 5; // jumlah item yg akan ditampilkan
                  // $limit_end = ($this->input->post('limit_start') * $limit_end) - $limit_end; // pagination
                // var_dump($limit_start,$limit_end);die;

                $get_data=$this->Aktivitas_model->get_aktivitas(date("Y-m-d",strtotime($start)),date("Y-m-d",strtotime($end)),$user,$limit,$limit_end);
                // $get_data=$this->Aktivitas_model->get_aktivitas(date("Y-m-d",strtotime($start)),date("Y-m-d",strtotime($end)),$user,$limit_start,$limit_end);
                
                // var_dump($get_data);die;
                foreach($get_data AS $v){
                    if($v['activity_text_1'] == 'NULL'){
                        $text1 = '';
                    }else{
                        $text1 = $v['activity_text_1'];
                    }
                    if($v['activity_text_2'] == 'NULL'){
                        $text2 = '';
                    }else{
                        $text2 = $v['activity_text_2'];
                    }
                    $text = '';
                    $action = '';                    
                    if($v['activity_action'] == 0){ //
                        $action = 'Unknown action';
                    }elseif($v['activity_action'] == 1){ // Login
                        $action = 'Online';
                    }elseif($v['activity_action'] == 2){ // Create
                        $action = 'membuat';
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;                            
                    }elseif($v['activity_action'] == 3){ // Read
                        $action = 'melihat';
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;                
                    }elseif($v['activity_action'] == 4){ // Update
                        $action = 'perbarui';     
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;                       
                    }elseif($v['activity_action'] == 5){ // Delete
                        $action = 'hapus';       
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;                     
                    }elseif($v['activity_action'] == 6){ // Print
                        $action = 'print';        
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2; 
                    }elseif($v['activity_action'] == 7){ // Active
                        $action = 'aktifkan';        
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;  
                    }elseif($v['activity_action'] == 8){ // NonActive
                        $action = 'nonaktifkan';        
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;                                                                    
                    }elseif($v['activity_action'] == 9){ // Pengajuan untuk Persetujuan
                        $action = '';        
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;                                                                                            
                    }elseif($v['activity_action'] == 10){ // Menandai
                        $action = 'menandai';        
                        $text = $text.' '.$text1;
                        $text = $text.' '.$text2;                                                                    
                    }else{
                        $action = '';
                        $text = '';
                    }

                    $datas[]=array(
                        'id' => $v['activity_id'],
                        'user' => ucwords($v['user_username']),
                        'act_action' => intval($v['activity_action']),
                        'act_action_name' => $action,
                        'text' => $text,
                        'act_text_1' => !empty($v['activity_text_1']) ? $v['activity_text_1'] : 0,
                        'act_text_2' => !empty($v['activity_text_2']) ? $v['activity_text_2'] : 0,
                        'act_text_3' => !empty($v['activity_text_3']) ? $v['activity_text_3'] : 0,
                        'act_text_4' => !empty($v['activity_text_4']) ? $v['activity_text_4'] : 0,                        
                        'act_text_5' => !empty($v['activity_text_5']) ? $v['activity_text_5'] : 0,                        
                        'act_type' => $v['activity_type'],
                        'date_time' => $this->time_ago($v['activity_date_created']),
                        'act_icon' => $v['activity_icon']
                    );
                }
                if(isset($datas)){ //Data exist
                    $data_source=$datas; $total=count($datas);
                    $return->status=1; $return->message='Loaded'; $return->total_records=$total;
                    $return->result=$datas;        
                    $return->get_data=$get_data;
                }else{ 
                    $data_source=0; $total=0; 
                    $return->status=0; $return->message='No data'; $return->total_records=$total;
                    $return->result=0;    
                }
                $return->limit=$limit.','.$limit_end;
            }
        }
        echo json_encode($return);
        
    }
}
