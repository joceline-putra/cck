<?php

class Joe extends CI_Controller {
	function __construct(){
		parent::__construct();

        $this->load->config('email');
        $this->load->config('whatsapp');

        $this->whatsapp_key              = $this->config->item('whatsapp_key');
        $this->whatsapp_token            = $this->config->item('whatsapp_token');
        $this->whatsapp_auth             = $this->config->item('whatsapp_auth');

        $this->whatsapp_vendor           = $this->config->item('whatsapp_vendor');
        $this->whatsapp_server           = $this->config->item('whatsapp_server');
        $this->whatsapp_sender           = $this->config->item('whatsapp_sender');

	}
	function index(){
        // $return = new \stdClass();
        // $return->status = 0;
        // $return->message = '';
        // $return->result = '';


        $whatsapp_vendor    = $this->whatsapp_vendor;
        $whatsapp_server    = $this->whatsapp_server;
        $whatsapp_sender    = $this->whatsapp_sender;
        $whatsapp_auth      = $this->whatsapp_auth;
        $whatsapp_token     = $this->whatsapp_token;
        $whatsapp_key       = $this->whatsapp_key;
        $whatsapp_recipient = '6281225518118';


            /* Message */
            $set_content = '';
			$set_content .= "💻 AVS Cron Job"."\r\n";
			$set_content .= "✔️ Order Update"."\r\n";
			$set_content .= "✔️ Trans Update"."\r\n";
			$set_content .= "✔️ Journal Update"."\r\n";			
			$set_content .= "⏰ ".date("d-M-Y, H:i");

            $set_url = $whatsapp_server."devices?action=send-message&auth=".$whatsapp_auth."&sender=".$whatsapp_sender."&recipient=".$whatsapp_recipient;
            $set_contents = "&content=".rawurlencode($set_content);
            // $set_content = '&content=AS';
            $scurl = $set_url.$set_contents;

            // var_dump($scurl);die;

            /* Action */
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $scurl,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_POST =>  1
            ));

            $response = curl_exec($curl);

            // curl_close($curl);

            /* Result */
            $get_response = json_decode($response,true);
            // var_dump($get_response);die;
            // log_message('debug',$get_response);
            // $return->result = $get_response;
            if($get_response['status'] == 1){
                // $return->status = 1;
                echo '<h1>Alarm berhasil di perintah ke Device Joe,<br> Tunggu ya Mariska 💞</h1>';
            }else{
            	echo '<h1>Gagal mengirim alarm ke Joe :(</h1>';
            }
        // $return->number = $number;
        // $return->vendor = $whatsapp_vendor;
        // return json_encode($return);
        // die;		
	}
}
?>