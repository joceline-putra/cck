<?php 
/*
    @AUTHOR: Joe Witaya
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MY_Controller{

    function __construct(){
        parent::__construct();
        if(!$this->is_logged_in()){
            // redirect(base_url("login"));
            $this->session->set_userdata('url_before',base_url(uri_string()));
            redirect(base_url("login/return_url"));              
        }            
        $this->load->model('User_model');        
        $this->group_access = array(1); //Super Admin               
    }
    function index(){
        $q = $this->db->query("SELECT * FROM `contacts` ORDER BY contact_id DESC LIMIT 1");        
        $d = $q->row_array(); 

        $print = '';
        // print_r($d);
        echo '<b>SAMPLE RESULT:</b><br>';
        foreach($d as $k => $v):
            echo $k.' => '.$v.',<br>';
        endforeach;   

        $print .= '<br><b>PHP : FORM VALIDATION</b><br>';
        // $print .= "\$params = array(<br>";
        foreach($d as $k => $v):
            // $set = is_numeric($v) ? "intval(\$post['".$k."'])" : "\$post['$k']"; 
            // $print .= "&nbsp;&nbsp;'".$k."' => !empty(\$post['".$k."']) ? ".$set." : null,<br>";
            $print .= "\$this->form_validation->set_rules('".$k."', '".strtoupper($k)."', 'required')<br>";            
        endforeach;
        // $print .= ');';
        $print .= "\$this->form_validation->set_message('required', '{field} wajib diisi');<br>";
        $print .= "if (\$this->form_validation->run() == FALSE){<br>";
        $print .= "&nbsp;&nbsp;&nbsp;&nbsp;\$return->message = validation_errors();<br>";
        $print .= "}else{";
        $print .= "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;// Do Save/Create Row<br>";

        $print .= '';
        $print .= '&nbsp;&nbsp;&nbsp;&nbsp;/*<b> PHP : CREATE / UPDATE</b>*/<br>';
        $print .= "&nbsp;&nbsp;&nbsp;&nbsp;\$params = array(<br>";
        foreach($d as $kk => $vv):
            $set = is_numeric($vv) ? "intval(\$post['".$kk."'])" : "\$post['$k']"; 
            $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'".$kk."' => !empty(\$post['".$kk."']) ? ".$set." : null,<br>";            
        endforeach;
        $print .= '&nbsp;&nbsp;&nbsp;&nbsp;);<br>';
        $print .= "}";
        echo $print."<br><br>";


        $print = '';
        $print .= '<b>PHP : READ</b><br>';
        $print .= "\$params = array(<br>";
        foreach($d as $k => $v):
            $set = is_numeric($v) ? "intval(\$v['".$k."'])" : "\$v['$k']";
            $print .= "&nbsp;&nbsp;'".$k."' => ".$set.",<br>";
        endforeach;
        $print .= ');';
        echo $print."<br><br>";

        $print = '';
        $print .= '<b>PHP : DATAS / DATATABLES</b><br>';
        $print .= "\$datas[] = array(<br>";
        foreach($d as $k => $v):
            $set = is_numeric($v) ? "intval(\$v['".$k."'])" : "\$v['$k']";
            $print .= "&nbsp;&nbsp;'".$k."' => ".$set.",<br>";
        endforeach;
        $print .= ');';
        echo $print."<br><br>";
        
        $print = '';
        $print_2 = '';
        $print .= '<b>JAVASCRIPT: FORM VALIDATION</b><br>';
        $print .= "let next = true<br>";
        $count = 1;
        foreach($d as $k => $v):
            $total = count($d);
            if(intval($count)==1){
                $print .= 'if($("#'.$k.'").val().length == 0){<br>';
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;notif(0,'".strtoupper($k)." wajib diisi');<br>";
                $print .= '&nbsp;&nbsp;&nbsp;&nbsp;$("#'.$k.'").focus();<br>';
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;next=false;<br>";
                $print .= "}";
            }else if(intval($count) < $total){
                $print .= 'else if($("#'.$k.'").val().length == 0){<br>';
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;notif(0,'".strtoupper($k)." wajib diisi');<br>";
                $print .= '&nbsp;&nbsp;&nbsp;&nbsp;$("#'.$k.'").focus();<br>';
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;next=false;<br>";
                $print .= "}";
            }else{
                $print .= "else{<br><br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;// Do Ajax Form POST<br>";
                $print .= '';
                $print .= '&nbsp;&nbsp;&nbsp;&nbsp;<b>// JAVASCRIPT: CREATE / UPDATE</b><br>';
                $print .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>//Plan 1</b><br>';
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;let form = new FormData();<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;form.append('action', 'create');<br>";

                $print_2 = '<br>&nbsp;&nbsp;&nbsp;&nbsp;<b>//Plan 2</b><br>';
                $print_2 .= '&nbsp;&nbsp;&nbsp;&nbsp;var form = {</br>';
                $print_2 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;action: 'create/update',</br>";
                foreach($d as $kk => $vv):
                    $set = is_numeric($vv) ? "intval($".$kk.")" : "\$v['$k']";
                    $print .= "&nbsp;&nbsp;&nbsp;&nbsp;form.append('".$kk."', $('#".$kk."').val());<br>";
                    $print_2 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$kk.": $('#".$kk."').val(),</br>";
                endforeach;
                $print_2 .= '&nbsp;&nbsp;&nbsp;&nbsp;};</br></br>';
                $print .= $print_2;
                // $print .= ');';
                // echo $print."<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;$.ajax({<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;type: 'post',<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;url: url,<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;data: form,<br>"; 
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dataType: 'json', cache: 'false', <br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;contentType: false, processData: false,   <br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;beforeSend:function(){},<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;success:function(d){<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;let s = d.status;<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;let m = d.message;<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;let r = d.result;<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if(parseInt(s) == 1){<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notif(s,m);<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;index.ajax.reload();<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}else{<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notif(s,m);<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;error:function(xhr,status,err){<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;notif(0,err);<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br>";
                $print .= "&nbsp;&nbsp;&nbsp;&nbsp;});<br>";
                $print .= "}";
            }
            $count ++;
        endforeach;
        echo $print."<br><br>";

        $print = '';
        $print .= '<b>HTML PHP Table</b><br>';
        $print .= "&#60;tr&#62;<br>";
        foreach($d as $k => $v):
            $set = is_numeric($v) ? "intval($".$k.")" : "\$v['$k']";
            // $print .= '$("#'.$k.'").val(d.result.'.$k.');<br>';
            $as = '&#60;?php echo $v["'.$k.'"]; ?>';
            $print .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#60;td&#62;'.$as.'&#60;/td&#62;'.'<br>';            
        endforeach;
        $print .= "&#60;/tr&#62;";
        echo $print."<br><br>";

        $print = '';
        $print .= '<b>HTML JAVASCRIPT Table</b><br>';
        $print .= "dsp += '&#60;tr&#62;';<br>";
        foreach($d as $k => $v):
            $set = is_numeric($v) ? "intval($".$k.")" : "\$v['$k']";
            // $print .= '$("#'.$k.'").val(d.result.'.$k.');<br>';
            $as = "'+d.result[a]['".$k."']+'";
            $print .= "dsp += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#60;td&#62;".$as."&#60;/td&#62;';"."<br>";            
        endforeach;
        $print .= "dsp += '&#60;/tr&#62;';";
        echo $print."<br><br>";   

        $print = '';
        $print .= '<b>JAVASCRIPT: READ</b><br>';
        // $print .= "\$datas[] = array(<br>";
        foreach($d as $k => $v):
            $set = is_numeric($v) ? "intval($".$k.")" : "\$v['$k']";
            $print .= '$("#'.$k.'").val(d.result.'.$k.');<br>';
        endforeach;
        // $print .= ');';
        echo $print."<br>";           
    }
    function phpserver(){
        $indicesServer = array('PHP_SELF',
        'argv',
        'argc',
        'GATEWAY_INTERFACE',
        'SERVER_ADDR',
        'SERVER_NAME',
        'SERVER_SOFTWARE',
        'SERVER_PROTOCOL',
        'REQUEST_METHOD',
        'REQUEST_TIME',
        'REQUEST_TIME_FLOAT',
        'QUERY_STRING',
        'DOCUMENT_ROOT',
        'HTTP_ACCEPT',
        'HTTP_ACCEPT_CHARSET',
        'HTTP_ACCEPT_ENCODING',
        'HTTP_ACCEPT_LANGUAGE',
        'HTTP_CONNECTION',
        'HTTP_HOST',
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'HTTPS',
        'REMOTE_ADDR',
        'REMOTE_HOST',
        'REMOTE_PORT',
        'REMOTE_USER',
        'REDIRECT_REMOTE_USER',
        'SCRIPT_FILENAME',
        'SERVER_ADMIN',
        'SERVER_PORT',
        'SERVER_SIGNATURE',
        'PATH_TRANSLATED',
        'SCRIPT_NAME',
        'REQUEST_URI',
        'PHP_AUTH_DIGEST',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'AUTH_TYPE',
        'PATH_INFO',
        'ORIG_PATH_INFO') ;

        echo '<table cellpadding="10">' ;
        foreach ($indicesServer as $arg) {
            if (isset($_SERVER[$arg])) {
                echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
            }
            else {
                echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
            }
        }
        echo '</table>' ;        
    }
    function excel(){
        if($this->input->post()){
            if(isset($_FILES["file"]["name"])){
                  // upload
                $file_tmp   = $_FILES['file']['tmp_name'];
                $file_name  = $_FILES['file']['name'];
                $file_size  = $_FILES['file']['size'];
                $file_type  = $_FILES['file']['type'];
                // move_uploaded_file($file_tmp,"uploads/".$file_name); // simpan filenya di folder uploads
                
                $object = PHPExcel_IOFactory::load($file_tmp);
                $number = 1;
                foreach($object->getWorksheetIterator() as $worksheet){
        
                    $highestRow    = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
            
                    for($row=2; $row <= $highestRow; $row++){
                    // if($number > 1){
                        // $data[] = array(
                        //     'test_name' => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                        //     'test_value' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                        //     'test_date' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                        // );
                        $data[] = array(
                            'tgl' => $worksheet->getCellByColumnAndRow(0, $row)->getValue(),
                            'berita' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                            'nilai' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                            'operator' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),                            
                        );                        
                    // }
                    // $number++;
                    }
        
                }
        
                $this->db->insert_batch('_mutasi', $data);
        
                $message = array(
                    'message'=>'<div class="alert alert-success">Import file excel berhasil disimpan di database</div>',
                );
                
                $this->session->set_flashdata($message);
                // redirect('test/excel');
            }else{
                 $message = array(
                    'message'=>'<div class="alert alert-danger">Import file gagal, coba lagi</div>',
                );
                
                $this->session->set_flashdata($message);
                // redirect('test/excel');
            }
            // echo json_encode($data);
        }else{
            $this->load->view('layouts/excel');
        }
    }
    function manage(){
        //Group Access View
        $data['session'] = $this->session->userdata();     
        $data['user_group'] = $data['session']['user_data']['user_group_id'];
        $group_access = $this->group_access;
        $set_view=false;
        if(in_array($data['user_group'],$group_access)){
            $set_view = true;
        }        
        $data['theme'] = $this->User_model->get_user($data['session']['user_data']['user_id']);

            // Default First Date & End Date of Current Month
            $firstdate = new DateTime('first day of this month');
            $firstdateofmonth = $firstdate->format('d-m-Y');
            
            $data['first_date'] = $firstdateofmonth;
            $data['end_date'] = date("d-m-Y");

            /*
            // Reference Model
            $this->load->model('Reference_model');
            $data['reference'] = $this->Reference_model->get_all_reference();
            */
            $data['hour'] = date("H:i");
            $data['title'] = 'Test';
            $data['_view'] = 'layouts/admin/menu/flow/test';
            $this->load->view('layouts/admin/index',$data);
            $this->load->view('layouts/admin/menu/flow/test_js.php',$data);        
    }
}

?>