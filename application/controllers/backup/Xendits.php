<?php
/*
  Contoh Controller
  https://packagist.org/packages/xendit/xendit-php
 */
use Xendit\Xendit;
class Xendits extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta'); 
         
        $this->load->config('email');
        $this->load->config('whatsapp');
        $this->load->model('Branch_model');
        $this->load->model('User_model');
        $this->load->model('Flow_model');
        $this->load->model('Xendit_model');  
        $this->load->model('Transaksi_model');                
        $this->load->model('Message_model');   
        $this->load->model('Type_model');  

        $this->xendit_api_key                   = 'xnd_development_KmsDSfHmq8AzbFsEy2gfjrwzrTEcS7LkqPJXwdzJWYihjyaMBf91Pyoa9mjnka'; //Live / Production
        // $this->xendit_api_key                   = 'xnd_development_WAqy2tumK2bweBTif0wT34x0DKO7P6sAGYu7YrTxXE0bVe0wwfgNB1gyMmT'; //Demo / Development        
        $this->xendit_success_payment_url       = site_url('x/status/1/'); //'https://app.mitraklinikita.com/preview/r/success/1/';
        $this->xendit_failure_payment_url       = site_url('x/status/0/');        
        $this->xendit_url_system                = site_url('x/');

        //Get Branch
        $get_branch = $this->Branch_model->get_branch(1);

        $this->app_name             = $get_branch['branch_name'];
        $this->app_url              = site_url();
        $this->app_logo             = site_url() . $get_branch['branch_logo'];
    }
    function index($id){
        die;
    }
    public function invoice_read($id){ //ID From Xendit
        $params = array(
            'log_type' => 1,
            'log_xendit_id' => $id
        );
        $data['xendit'] = $this->Xendit_model->get_xendit_log_custom($params);
        // var_dump($data['xendit']);die;
        $this->load->view('layouts/admin/menu/prints/xendit',$data);
    }
    public function payment_status($status,$trans_session){ //Success HTML Page
        $data['branch_logo'] = $this->app_logo;
        $data['status'] = $status;
        $data['trans'] = $this->Transaksi_model->get_transaksi_custom(array('trans_session' => $trans_session));
        $data['branch'] = $this->Branch_model->get_branch($data['trans']['trans_branch_id']);
        $this->load->view('layouts/admin/menu/prints/xendit_status',$data);
    }
    public function callback(){
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';
    
        $post = file_get_contents('php://input');
        $datas = json_decode($post);

        $params = array(
            'log_type' => 2,
            'log_date_created' => date("YmdHis"),
            'log_message' => json_encode($datas)  
        );
        //Detect Payment Method
        $xendit_event = !empty($datas->event) ? $datas->event : null;
        if($xendit_event){
            
            $xendit_external_id = !empty($datas->data->id) ? $datas->data->id : null; //external_id
            $reference_id = !empty($datas->data->reference_id) ? $datas->data->reference_id : null; //reference_id / trans_session            
            $get_trans = $this->Transaksi_model->get_transaksi_custom(array('trans_session'=>$reference_id));
            
            if($xendit_event == 'qr.payment'){

                $params['log_xendit_payment_method']    = 'QR_PAYMENT';
                $params['log_xendit_id']                = !empty($datas->data->id) ? $datas->data->id : null;            
                // $params['log_xendit_id']             = !empty($datas->data->reference_id) ? $datas->data->reference_id : null;            
                $params['log_xendit_event']             = !empty($datas->event) ? $datas->event : null;
                $params['log_xendit_amount']            = !empty($datas->data->amount) ? $datas->data->amount : null;
                $params['log_xendit_paid_amount']       = !empty($datas->data->amount) ? $datas->data->amount : null;             
                $params['log_xendit_status']            = !empty($datas->data->status) ? $datas->data->status : "";  

                $params['log_trans_session']            = !empty($datas->data->reference_id) ? $datas->data->reference_id : null; 
                $params['log_trans_id']                 = !empty($get_trans['trans_id']) ? $get_trans['trans_id'] : null;
                $params['log_trans_number']             = !empty($get_trans['trans_number']) ? $get_trans['trans_number'] : null;   

            }else if($xendit_event == 'ewallet.capture'){ //Shopee Tested Works

                $params['log_xendit_payment_method']    = 'ONE_TIME_PAYMENT'; //ONE_TIME_PAYMENT
                $params['log_xendit_id']                = !empty($datas->data->id) ? $datas->data->id : null;                  
                $params['log_xendit_event']             = !empty($datas->event) ? $datas->event : null;
                $params['log_xendit_amount']            = !empty($datas->data->capture_amount) ? $datas->data->capture_amount : null;
                $params['log_xendit_paid_amount']       = !empty($datas->data->charge_amount) ? $datas->data->charge_amount : null;             
                $params['log_xendit_status']            = 'PAID';  
                $params['log_xendit_bank_name']         = !empty($datas->data->channel_code) ? $datas->data->channel_code : "";  
                $params['log_xendit_paid_date']         = !empty($datas->data->updated) ? $datas->data->updated : null;

                $params['log_trans_session']            = !empty($datas->data->reference_id) ? $datas->data->reference_id : null; 
                $params['log_trans_id']                 = !empty($get_trans['trans_id']) ? $get_trans['trans_id'] : null;
                $params['log_trans_number']             = !empty($get_trans['trans_number']) ? $get_trans['trans_number'] : null;     

            }else if($xendit_event == 'paylater.payment'){

                $params['log_xendit_payment_method']    = 'PAYLATER';   
                $params['log_xendit_id']                = !empty($datas->data->id) ? $datas->data->id : null;     
                $params['log_xendit_amount']            = !empty($datas->data->amount) ? $datas->data->amount : null;
                $params['log_xendit_paid_amount']       = !empty($datas->data->amount) ? $datas->data->amount : null;                                         
                $params['log_xendit_event']             = !empty($datas->event) ? $datas->event : null;
                // $params['log_xendit_paid_amount']    = !empty($datas->data['channel_code']) ? $datas->data['channel_code'] : null; //Kredivo, Sakuku           
                $params['log_trans_session']            = !empty($datas->data->reference_id) ? $datas->data->reference_id : null; 
                $params['log_trans_id']                 = !empty($get_trans['trans_id']) ? $get_trans['trans_id'] : null;
                $params['log_trans_number']             = !empty($get_trans['trans_number']) ? $get_trans['trans_number'] : null;  

            }
        }else{ //Bank Transfer, OTC (Alfa, Indomart), VA
            $xendit_payment_method = !empty($datas->payment_method) ? $datas->payment_method : null;
            $xendit_retail_outlet_name = !empty($datas->retail_outlet_name) ? $datas->retail_outlet_name : null;    
            $xendit_callback_virtual_account_id = !empty($datas->callback_virtual_account_id) ? $datas->callback_virtual_account_id : null; 
            $xendit_external_id = !empty($datas->external_id) ? $datas->external_id : null;
            //Get Trans Info
            $get_trans = $this->Transaksi_model->get_transaksi_custom(array('trans_session'=>$xendit_external_id));

            if(!empty($xendit_payment_method)){ //Bank Transfer
                // $params['log_xendit_event'] = !empty($datas->event) ? $datas->event : null;
                $params['log_xendit_id'] = !empty($datas->id) ? $datas->id : null;
                $params['log_xendit_payment_method'] = !empty($datas->payment_method) ? $datas->payment_method : null; 
                $params['log_xendit_bank_name   '] = !empty($datas->payment_channel) ? $datas->payment_channel : null; 
                $params['log_xendit_bank_number'] = !empty($datas->payment_destination) ? $datas->payment_destination : null;                                 
                $params['log_xendit_amount'] = !empty($datas->amount) ? $datas->amount : null;
                $params['log_xendit_paid_amount'] = !empty($datas->paid_amount) ? $datas->paid_amount : null;             
                $params['log_xendit_paid_date'] = !empty($datas->paid_at) ? $datas->paid_at : null;
                $params['log_xendit_status'] = !empty($datas->status) ? $datas->status : null;                
                $params['log_trans_id'] = !empty($get_trans['trans_id']) ? $get_trans['trans_id'] : null;
                $params['log_trans_number'] = !empty($get_trans['trans_number']) ? $get_trans['trans_number'] : null;
                $params['log_trans_session'] = !empty($get_trans['trans_session']) ? $get_trans['trans_session'] : null;                
            }
            
            if(!empty($xendit_retail_outlet_name)){ //OTC Alfa, Indo
                // $params['log_xendit_event'] = !empty($datas->event) ? $datas->event : null;
                $params['log_xendit_id'] = !empty($datas->id) ? $datas->id : null;
                $params['log_xendit_payment_method'] = !empty($datas->retail_outlet_name) ? $datas->retail_outlet_name : null; 
                $params['log_xendit_amount'] = !empty($datas->amount) ? $datas->amount : null;
                $params['log_xendit_paid_amount'] = !empty($datas->amount) ? $datas->amount : null;             
                $params['log_xendit_status'] = !empty($datas->status) ? $datas->status : null;
                // $params['log_trans_id'] = !empty($get_trans['trans_id']) ? $get_trans['trans_id'] : null;
                // $params['log_trans_number'] = !empty($get_trans['trans_number']) ? $get_trans['trans_number'] : null;   
                // $params['log_trans_session'] = !empty($get_trans['trans_session']) ? $get_trans['trans_session'] : null;                             
            }     
            
            if(!empty($xendit_callback_virtual_account_id)){ //VA
                // $params['log_xendit_event'] = !empty($datas->event) ? $datas->event : null;
                $params['log_xendit_id'] = !empty($datas->callback_virtual_account_id) ? $datas->callback_virtual_account_id : null;
                $params['log_xendit_payment_method'] = !empty($datas->bank_code) ? 'VA_'.$datas->bank_code.'_'.$datas->account_number : null; 
                $params['log_xendit_amount'] = !empty($datas->amount) ? $datas->amount : null;
                $params['log_xendit_paid_amount'] = !empty($datas->amount) ? $datas->amount : null;             
                // $params['log_xendit_status'] = !empty($datas->status) ? $datas->status : null;
                $params['log_xendit_status'] = 'PAID';                
                // $params['log_trans_id'] = !empty($get_trans['trans_id']) ? $get_trans['trans_id'] : null;
                // $params['log_trans_number'] = !empty($get_trans['trans_number']) ? $get_trans['trans_number'] : null;
                // $params['log_trans_session'] = !empty($get_trans['trans_session']) ? $get_trans['trans_session'] : null;                                
            }                 
            // $params['log_xendit_event'] = !empty($datas->event) ? $datas->event : null;
            // $params['log_xendit_id'] = !empty($datas->id) ? $datas->id : null;
            // $params['log_xendit_payment_method'] = !empty($datas->payment_method) ? $datas->payment_method : null; 
            // $params['log_xendit_amount'] = !empty($datas->amount) ? $datas->amount : null;
            // $params['log_xendit_paid_amount'] = !empty($datas->paid_amount) ? $datas->paid_amount : null;             
            // $params['log_xendit_status'] = !empty($datas->status) ? $datas->status : null;
        }
        // var_dump($params);die;
        $insert = $this->Xendit_model->add_xendit_log($params);
        if($insert){
            $return->status=1;
            $return->message='Log Inserted';
        }else{
            $return->message='Error Inserted';
        }
        echo json_encode($return);
    }
    public function action(){
        require 'vendor/autoload.php';
        // die;
        $return          = new \stdClass();
        $return->status  = 0;
        $return->message = '';
        $return->result  = '';

        Xendit::setApiKey($this->xendit_api_key);
        $next      = true;
        $action    = !empty($this->input->post('action')) ? $this->input->post('action') : null;
        $trans_id  =  !empty($this->input->post('trans_id')) ? $this->input->post('trans_id') : null;
        $trans_session  =  !empty($this->input->post('trans_session')) ? $this->input->post('trans_session') : null;
        $wallet_id =  !empty($this->input->post('wallet_id')) ? $this->input->post('wallet_id') : null;

        $name =  !empty($this->input->post('contact_name')) ? $this->input->post('contact_name') : null;        
        $phone =  !empty($this->input->post('contact_phone')) ? $this->input->post('contact_phone') : null;
        
        $contact_phone = str_replace('+','',str_replace('-','',$phone)); //Remove + and -
        $contact_phone = ltrim(rtrim(trim($contact_phone))); //Remove space
        $contact_phone = str_replace(' ','',$contact_phone);
        $contact_phone_check = substr($contact_phone,0,1); // First char is 0
        if($contact_phone_check == 0){
            $contact_phone = '62'.substr($contact_phone,1,15); //To 62 81213123
        }else{
            $contact_phone = $contact_phone; //
        }

        switch($action){
            case "create-link-payment":
                if(strlen($trans_session) > 5){
                    $params = array(
                        'log_trans_session' => $trans_session,
                        'log_type' => 2,
                        'log_xendit_status' => 'PAID'
                    );
                    $check_is_paid = $this->Xendit_model->get_xendit_log_custom($params);
                    if($check_is_paid['log_xendit_status'] == 'PAID'){
                        $return->message ='Gagal, Invoice ini sudah terbayar';     
                    }else{
                        //Get Trans
                        $get_trans = $this->Transaksi_model->get_transaksi($trans_id);
                        $get_trans_items = $this->Transaksi_model->get_all_transaksi_items(array('trans_item_trans_id'=>$trans_id),null,null,null,'product_name','asc');
                        $get_branch = $this->Branch_model->get_branch($get_trans['trans_branch_id']);
                        $items = array();

                        //Check if Trans is Paid
                        if($get_trans['trans_paid'] == 0){
                            foreach($get_trans_items as $v):
                                $items[] = array(
                                    'name' => $v['product_name'],
                                    'quantity' => $v['trans_item_out_qty'],
                                    'price' => $v['trans_item_sell_price'],
                                    'category' => $v['product_type_name']
                                    // 'url' => 'https=>//yourcompany.com/example_item'
                                );
                            endforeach;
                            /*
                                $address = [
                                    [
                                        'city' => 'Jakarta Selatan',
                                        'country' => 'Indonesia',
                                        'postal_code' => '12345',
                                        'state' => 'Daerah Khusus Ibukota Jakarta',
                                        'street_line1' => 'Jalan Makan',
                                        'street_line2' => 'Kecamatan Kebayoran Baru'
                                    ]
                                ];
                            */

                            $fees   = array();
                            if($get_trans['trans_discount'] > 0){
                                $fees[] = array(
                                    'type' => 'Diskon',
                                    'value' => '-'.intval($get_trans['trans_discount'])
                                );
                            }
                            
                            if($get_trans['trans_voucher'] > 0){
                                $fees[] = array(
                                    'type' => 'Voucher',
                                    'value' => '-'.intval($get_trans['trans_voucher'])
                                );
                            }

                            $address = array();
                            $params_invoice = [ 
                                'external_id' => $get_trans['trans_session'],
                                'amount' => floatVal($get_trans['trans_total']),
                                'description' => 'Invoice #'.$get_trans['trans_number'].', '.$get_branch['branch_name'],
                                'invoice_duration' => 86400,
                                'customer' => [
                                    'given_names' => ltrim(rtrim(trim($name))),
                                    // 'surname' => '',
                                    // 'email' => '',
                                    'mobile_number' => '+'.$contact_phone,
                                    'addresses' => $address
                                ],
                                'customer_notification_preference' => [
                                    'invoice_created' => [
                                        // 'whatsapp',
                                        // 'sms',
                                        // 'email',
                                        // 'viber'
                                    ],
                                    'invoice_reminder' => [
                                        // 'whatsapp',
                                        // 'sms',
                                        // 'email',
                                        // 'viber'
                                    ],
                                    'invoice_paid' => [
                                        'whatsapp', 
                                        // 'sms',
                                        // 'email',
                                        // 'viber'
                                    ],
                                    'invoice_expired' => [
                                        // 'whatsapp',
                                        // 'sms',
                                        // 'email',
                                        // 'viber'
                                    ]
                                ],
                                'success_redirect_url' => $this->xendit_success_payment_url.$get_trans['trans_session'],
                                'failure_redirect_url' => $this->xendit_failure_payment_url.$get_trans['trans_session'],
                                'currency' => 'IDR',
                                'items' => $items,
                                'fees' => $fees
                            ];
                            // var_dump($params_invoice);die;

                            try {
                                $create_invoice = \Xendit\Invoice::create($params_invoice);
                                if(strlen($create_invoice['id']) > 0){
                                    $param_xendit_trans = array(
                                        'log_type' => 1,
                                        'log_trans_id' => $trans_id,
                                        'log_trans_number' => $get_trans['trans_number'],
                                        'log_trans_session' => $get_trans['trans_session'],                                                        
                                        'log_xendit_id' => $create_invoice['id'],
                                        'log_trans_url' => $create_invoice['invoice_url'],
                                        'log_trans_flag' => 0,
                                        'log_message' => json_encode($create_invoice),
                                        'log_date_created' => date("YmdHis"),
                                        'log_xendit_amount' => $get_trans['trans_total'],
                                        'log_xendit_paid_amount' => 0,
                                        'log_xendit_status' => 'UNPAID'
                                    );
                                    $inser_xendit_trans = $this->Xendit_model->add_xendit_log($param_xendit_trans);

                                    $update_trans = $this->Transaksi_model->update_transaksi($trans_id,array('trans_paid_type'=>6));

                                    $return->status=1;
                                    $return->message = 'Berhasil Membuat Link Pembayaran Virtual';
                                    $return->result = array(
                                        'xendit_result' => $create_invoice,
                                        'xendit_id' => $create_invoice['id'],
                                        'xendit_url' => $create_invoice['invoice_url'],
                                        'xendit_url_convert' => $this->xendit_url_system.$create_invoice['id'],
                                        'trans_id' => $trans_id,
                                        'trans_number' => $get_trans['trans_number'],
                                        'trans_session' => $get_trans['trans_session'],
                                        'trans_total' => number_format($get_trans['trans_total'],0,'.',','),
                                        'trans_date' => date("d-M-Y, H:i", strtotime($get_trans['trans_date'])),                    
                                        'contact_id' => $get_trans['trans_contact_id'],
                                        // 'contact_name' => $get_trans['contact_name'], 
                                        // 'contact_phone' => $get_trans['contact_phone_1'],
                                        'contact_name' => $name, 
                                        'contact_phone' => $contact_phone,                                        
                                        'contact_email' => $get_trans['contact_email_1'],                    
                                    );

                                    //WhatsApp
                                    $params = array(
                                        'trans_id' => $get_trans['trans_id'],
                                        'contact_name' => $name,
                                        'contact_phone' => $contact_phone,
                                        'payment_url' => $this->xendit_url_system.$create_invoice['id']
                                    );
                                    $this->whatsapp_template('create-trans-link-payment',1,$params);                                    
                                }else{
                                    $return->message = 'Gagal menghubungkan Payment Gateway:LinkPayment';
                                }
                            } catch (Exception $e) {
                                $return->message = 'ERROR: '.$e->getMessage();
                            }
                            $return->params = $params_invoice;
                        }else if($get_trans['trans_paid'] == 1){
                            $return->message='Invoice sudah dibayar, gagal membuat Link Pembayaran';
                        }else {
                            $return->message='Error Data';
                        }
                    }
                }else{
                    $return->message = 'Invoice belum dibuka';
                }
                break;
            case "create-ewallet":
                if(strlen($trans_session) > 5){
                    $params = array(
                        'log_trans_session' => $trans_session,
                        'log_type' => 2,
                        'log_xendit_status' => 'PAID'
                    );
                    $check_is_paid = $this->Xendit_model->get_xendit_log_custom($params);
                    if($check_is_paid['log_xendit_status'] == 'PAID'){
                        $return->message ='Gagal, Invoice ini sudah terbayar';
                    }else{

                        //Get Trans
                        $get_trans = $this->Transaksi_model->get_transaksi($trans_id);
                        $get_trans_items = $this->Transaksi_model->get_all_transaksi_items(array('trans_item_trans_id'=>$trans_id),null,null,null,'product_name','asc');
                        $get_branch = $this->Branch_model->get_branch($get_trans['trans_branch_id']);
                        $items = array();
                        
                        if(strlen($wallet_id) < 2){
                            $next=false;
                            $return->message ="Pilih Wallet belum dipilih";
                        }
                        
                        //Check if Trans is Paid
                        if($get_trans['trans_paid'] == 0){
                            $next = true;
                        }else{
                            $next=false;
                            $return->message = 'Gagal, Invoice sudah terbayarkan';
                        }
                        
                        //Prepare Connect to Xendit
                        if($next){
                            foreach($get_trans_items as $v):
                                $items[] = array(
                                    'name' => $v['product_name'],
                                    'quantity' => $v['trans_item_out_qty'],
                                    'price' => $v['trans_item_sell_price'],
                                    'category' => $v['product_type_name']
                                    // 'url' => 'https=>//yourcompany.com/example_item'
                                );
                            endforeach;

                            $params_ewallet = [
                                'reference_id' => $get_trans['trans_session'],
                                'currency' => 'IDR',
                                'amount' => floatVal($get_trans['trans_total']),
                                'checkout_method' => 'ONE_TIME_PAYMENT',
                                'channel_code' => $wallet_id, //ID_OVO, ID_DANA, ID_LINKAJA, ID_SHOPEEPAY
                                'channel_properties' => [
                                    'success_redirect_url' => $this->xendit_success_payment_url.$get_trans['trans_session'],
                                    'failure_redirect_url' => $this->xendit_failure_payment_url.$get_trans['trans_session'],
                                ],
                                'metadata' => [
                                    'branch_code' => 'tree_branch'
                                ]
                            ];

                            //Tambahan Parameter sesuai syarat vendor 
                            if($wallet_id == 'ID_SHOPEEPAY'){
                                $params_ewallet['channel_properties']['redeem_points'] = 'REDEEM_NONE'; 
                            }else if($wallet_id == 'ID_OVO'){
                                $params_ewallet['channel_properties']['redeem_points'] = 'REDEEM_NONE';                        
                                $params_ewallet['channel_properties']['mobile_number'] = '+'.$contact_phone;                          
                            }
                            // var_dump($params_ewallet);die;
                            
                            try {
                                $create_ewallet = \Xendit\EWallets::createEWalletCharge($params_ewallet);
                                if(strlen($create_ewallet['reference_id']) > 0){
                                    $param_xendit_trans = array(
                                        'log_type' => 1,
                                        'log_trans_id' => $trans_id,
                                        'log_trans_number' => $get_trans['trans_number'],
                                        'log_trans_session' => $get_trans['trans_session'],                            
                                        'log_xendit_id' => $create_ewallet['id'],
                                        'log_trans_url' => $create_ewallet['actions']['desktop_web_checkout_url'],
                                        'log_trans_flag' => 0,
                                        'log_message' => json_encode($create_ewallet),
                                        'log_date_created' => date("YmdHis"),
                                        'log_xendit_amount' => floatVal($get_trans['trans_total']),
                                        'log_xendit_paid_amount' => 0,
                                        'log_xendit_status' => 'UNPAID'
                                    );
                                    $inser_xendit_trans = $this->Xendit_model->add_xendit_log($param_xendit_trans);
                                    $update_trans = $this->Transaksi_model->update_transaksi($trans_id,array('trans_paid_type'=>7));
                                        
                                    $return->status=1;
                                    $return->message = 'Berhasil Membuat eWallet';                    
                                    $return->result = array(
                                        'xendit_result' => $create_ewallet,
                                        'xendit_id' => $create_ewallet['id'],
                                        'xendit_url' => $create_ewallet['actions']['desktop_web_checkout_url'],
                                        'xendit_url_convert' => $create_ewallet['actions']['desktop_web_checkout_url'],
                                        'trans_id' => $trans_id,
                                        'trans_number' => $get_trans['trans_number'],
                                        'trans_session' => $get_trans['trans_session'],
                                        'trans_total' => number_format($get_trans['trans_total'],0,'.',','),
                                        'trans_date' => date("d-M-Y, H:i", strtotime($get_trans['trans_date'])),                    
                                        'contact_id' => $get_trans['trans_contact_id'],
                                        // 'contact_name' => $get_trans['contact_name'], 
                                        'contact_name' => $name,                                 
                                        'contact_phone' => $contact_phone,
                                        'contact_email' => $get_trans['contact_email_1'],                    
                                    );

                                    //WhatsApp
                                    $params = array(
                                        'trans_id' => $get_trans['trans_id'],
                                        'contact_name' => $name,
                                        'contact_phone' => $contact_phone,
                                    );
                                    $this->whatsapp_template('create-trans-ewallet',1,$params);
                                }else{
                                    $return->message = 'Gagal menghubungkan Payment Gateway:eWallet';
                                }                   
                            } catch (Exception $e) {
                                $return->message = 'ERROR: '.$e->getMessage();
                            }
                            $return->params_ewallet_charge = $params_ewallet;
                        }
                    }
                }else{
                    $return->message = 'Invoice belum dibuka';
                }                    
                break; 
            case "create-ewallet-test":
                $params = array(
                    'log_trans_session' => $trans_session,
                    'log_type' => 2,
                    'log_xendit_status' => 'PAID'
                );
                $check_is_paid = $this->Xendit_model->get_xendit_log_custom($params);
                if($check_is_paid['log_xendit_status'] == 'PAID'){
                    $return->result = $check_is_paid;
                    $return->message ='Gagal, Invoice ini sudah terbayar';
                }else{ 
                    $get_trans = $this->Transaksi_model->get_transaksi($trans_id);
                    $return->status=1;
                    $return->message='Berhasil membuat ewallet';
                    $return->result = array(
                        'trans_number' => !empty($get_trans['trans_number']) ? $get_trans['trans_number'] : 'Nomor Invoice',
                        'trans_date' => !empty($get_trans['trans_date']) ? date("d M Y", strtotime($get_trans['trans_date'])) : '01 01 2023',
                        'trans_total' => !empty($get_trans['trans_total']) ? number_format($get_trans['trans_total']) : '20.000',
                        'trans_session' => !empty($get_trans['trans_session']) ? $get_trans['trans_session'] : 'TRANS_SESSION',
                        'contact_name' => !empty($get_trans['contact_name']) ? $get_trans['contact_name'] : 'Nama Pasien',
                        'contact_phone' => !empty($get_trans['contact_phone_1']) ? $get_trans['contact_phone_1'] : 'WA Pasien',
                        'xendit_result' => array(
                            'actions' => array(
                                'mobile_deeplink_checkout_url' => 'https://wsa.wallet.airpay.co.id/universal-link/wallet/pay?deep_and_deferred=1&token=Um80ZWF4Yk9xZmROApVBRep5QI4nC4BXFBCpgtuPchK6n6loM4g8TzhqIRAL_5bR',
                                'qr_checkout_string' => '00020101021226610016ID.CO.SHOPEE.WWW01189360091800210495420208210495420303UKE51440014ID.CO.QRIS.WWW0215ID20222242495670303UKE520472985303360540829000.005802ID5919Klinikita Indonesia6013KOTA SEMARANG61055018362240520cfhp3u293458cn1994106304FDB8'
                            )
                        )
                    );
                    $params = array(
                        'trans_id' => $get_trans['trans_id'],
                        'contact_name' => $get_trans['contact_name'],
                        'contact_phone' => $get_trans['contact_phone_1'],
                        'payment_url' => 'https://google.com' //Dibutuhkan jika create-trans-link-payment
                    );
                    $this->whatsapp_template('create-trans-ewallet',1,$params);
                    $this->whatsapp_template('create-trans-link-payment',1,$params);                    
                }
                break;
            case "check-payment-history-on-xendit":
                if(strlen($trans_session) > 5){
                    $params = array(
                        'log_trans_session' => $trans_session,
                        // 'log_type' => 2,
                        // 'log_xendit_status' => 'PAID'
                    );
                    $datas = array();              
                    $get_count = $this->Xendit_model->get_all_xendit_log_count($params,null);
                    if($get_count > 0){
                        $check = $this->Xendit_model->get_all_xendit_log($params,null,0,1,'log_id','desc');
                        foreach($check as $v){
                            $datas[] = array(
                                'log_date' => date("d-M-Y, H:i", strtotime($v['log_date_created'])),
                                'log_trans_id' => $v['log_trans_id'],
                                'log_trans_number' => $v['log_trans_number'],
                                'log_trans_session' => $v['log_trans_session'],                            
                                'log_xendit_amount' => floatval($v['log_xendit_amount']),
                                'log_xendit_paid_amount' => floatval($v['log_xendit_paid_amount']),
                                'log_xendit_paid_date' => !empty($v['log_xendit_paid_date']) ? date("d-M-Y, H:i", strtotime($v['log_xendit_paid_date'])) : '',
                                'log_xendit_payment_method' => !empty($v['log_xendit_payment_method']) ? $v['log_xendit_payment_method'].' - '.$v['log_xendit_bank_name'] : '-',
                                'log_xendit_status' => ($v['log_xendit_status'] == 'PAID') ? 'Paid' : $v['log_xendit_status'],                            
                            );
                        }
                        $return->message = 'Data permintaan / pembayaran ditemukan';
                        $return->status = 1;         
                    } else{
                        $return->message = 'Data pembayaran tidak ditemukan';
                    }
                    $return->result = $datas;
                }else{
                    $return->message = 'Invoice belum dibuka';
                }
                break;
            default:
                $return->message = 'No Action';
                break;
        }
        // $return->message = $message;
        // $return->result  = $params_invoice;
        echo json_encode($return);        
    }
    function random_code($length){ # JEH3F2
        $text = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';
        $txtlen = strlen($text)-1;
        $result = '';
        for($i=1; $i<=$length; $i++){
        $result .= $text[rand(0, $txtlen)];}
        return $result;
    }    
    function whatsapp_send_group($message_group_session){ //Works 
        $return             = new \stdClass();
        $return->status     = 0;
        $return->message    = '';
        $return->result     = '';
        

        // WhatsApp Config

        $whatsapp_server    = $this->config->item('whatsapp_server');        
        $whatsapp_action    = $this->config->item('whatsapp_action');         
        $whatsapp_sender    = $this->config->item('whatsapp_sender');         
        // $whatsapp_vendor = $this->config->item('whatsapp_vendor');
        // $whatsapp_token  = $this->config->item('whatsapp_token');
        // $whatsapp_auth  = $this->config->item('whatsapp_auth');

        if(strlen($message_group_session) > 0){
        
            $datas = array();
            $where = array(
                'message_group_session' => $message_group_session
            );
            $get_data=$this->Message_model->get_message_custom_result($where);
            if(count($get_data) > 0){
                foreach($get_data as $v){

                    // Fetch Data
                    $datas[] = array(
                        'message_group_session' => $v['message_group_session'],
                        'message_id' => $v['message_id'],
                        'message_session' => $v['message_session'],
                        'message_text' => $v['message_text'],
                        'message_contact_number' => $v['message_contact_number'],
                        'message_url' => $v['message_url'],
                        'message_device_id' => $v['message_device_id']
                    );

                    //Detect if Sender different from default
                    if($v['message_device_id'] > 0){
                        $curl_link = $whatsapp_server.'devices?action=send-message&auth='.$v['device_token'].'&sender='.$v['device_number'];
                    }else{
                        $curl_link = $whatsapp_server.$whatsapp_action['send-message'].'&sender='.$whatsapp_sender;
                    }
                    $curl_link .= '&recipient='.$v['message_contact_number'];
                    $curl_link .= '&content='.rawurlencode($v['message_text']); 

                    // Message has a caption
                    if(strlen($v['message_url']) > 0){
                        $curl_link .= '&file='.$v['message_url'];
                    }

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $curl_link,
                        CURLOPT_HEADER => 0,
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_POST =>  1,
                        CURLOPT_POSTFIELDS => 1,
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                
                    // Result Response
                    $get_response = json_decode($response,true);
                    if($get_response['result'] !== false){

                        $where = array(
                            'message_id' => $v['message_id']
                        );
                        $params = array(
                            'message_date_sent' => date('YmdHis'),
                            'message_flag' => 1
                        );
                        $this->Message_model->update_message_custom($where,$params);
                    }
                    $return->result  = $get_response['result']; // Result
                    $return->status  = $get_response['status']; // 1 / 0
                    $return->message = $get_response['message']; // Berhasil / Gagal                     
                }
            }else{ 
                $return->message='Session not found';                
            }
        }else{
            $return->message='Session not found';
        }
        return json_encode($return);
    }    
    function whatsapp_template($action, $send_now_or_later, $params){
        /*
            $send_now_or_later ? 1 = Langsung dikirim , 0 = Server yg Mengirim
        */
        $next               = true;
        $set_user_id        = null;
        $set_user_username  = null;
        $set_user_phone     = null;        
        
        switch($action){
            case "create-trans-ewallet": //Done
                $a = '';
                $b = '';
                $c = '';

                //Get Trans
                $trans = $this->Transaksi_model->get_transaksi($params['trans_id']);
                if($trans['trans_id'] > 0){
                    $branch = $this->Branch_model->get_branch($trans['trans_branch_id']);
                    $where = array(
                        'trans_item_trans_id' => $trans['trans_id'],
                    );
                    $trans_item = $this->Transaksi_model->get_transaksi_item_custom($where);
                    foreach($trans_item as $v):
                        $b .=' '.$v['product_name'].' @'.number_format($v['trans_item_sell_price'],0)."\r\n";
                    endforeach;
                    
                    $a .= 'Halo Bpk/Ibu *'.$trans['trans_contact_name'].'*, terimakasih atas permintaan pembayaran layanan konsultasi online'."\r\n\r\n";
                    $a .= '*- Deskripsi:*'."\r\n";
                    $a .= $trans['trans_number']."\r\n";
                    $a .= date("d-F-Y, H:i", strtotime($trans['trans_date']))."\r\n";
                    $c .= $a;
                    if(count($trans_item) > 0){
                        $c .= $b."\r\n";
                    }else{
                        $c .= '-'."\r\n";
                    }

                    $c .= "*- Total*"."\r\n";
                    $c .= "Rp. ".number_format($trans['trans_total_dpp'],0)."\r\n";
                    $c .= "\r\n";        

                    //Pembayaran
                    $c .= '*- Status Pembayaran:*'."\r\n";
                    if($trans['trans_paid'] == 1){
                        $c .= 'LUNAS'."\r\n";
                        // if($trans['trans_paid_type']==1){ //Tunai/Cash
                        //     $c .= 'Cash'."\r\n";
                        // }else if($trans['trans_paid_type']==2){ //Transfer
                        //     $c .= 'Transfer'."\r\n";
                        // }else if($trans['trans_paid_type']==3){ //EDC
                        //     $c .= 'EDC Card'."\r\n";
                        // }else if($trans['trans_paid_type']==4){ //Gratis
                        //     $c .= 'GRATIS'."\r\n";
                        // }else if($trans['trans_paid_type']==5){ //QRIS
                        //     $c .= 'QRIS'."\r\n";
                        // }
                        if($trans['trans_paid_type'] > 0){
                            $get_type_paid = $this->Type_model->get_type_paid($trans['trans_paid_type']);
                            $c .= $get_type_paid['paid_name']."\r\n";
                        }
                        // else{
                            // $c .= 'Belum Terbayar'."\r\n"; // Piutang
                        // }                        
                    }else{
                        $c .= 'Belum Terbayar'."\r\n";
                    }

                    $c .= "\r\n".'_Terimakasih atas kepercayaan anda_'."\r\n";
                    $text = $c;

                    $set_user_id = !empty($trans['trans_contact_id']) ? $trans['trans_contact_id'] : '';
                    $set_user_name = !empty($trans['trans_contact_name']) ? $trans['trans_contact_name'] : '';
                    $set_user_phone = !empty($trans['trans_contact_phone']) ? $trans['trans_contact_phone'] : '';                    
                }else{
                    $next = false;
                }            
                break;
            case "create-trans-link-payment": //Done
                $a = '';
                $b = '';
                $c = '';

                //Get Trans
                $trans = $this->Transaksi_model->get_transaksi($params['trans_id']);
                if($trans['trans_id'] > 0){
                    $branch = $this->Branch_model->get_branch($trans['trans_branch_id']);
                    $where = array(
                        'trans_item_trans_id' => $trans['trans_id'],
                    );
                    $trans_item = $this->Transaksi_model->get_transaksi_item_custom($where);
                    foreach($trans_item as $v):
                        $b .= number_format($v['trans_item_out_qty'],0).' '.$v['trans_item_unit'];
                        $b .=' '.$v['product_name'].' @'.number_format($v['trans_item_sell_price'],0)."\r\n";
                    endforeach;
                    
                    $a .= '📄 *Invoice '.$branch['branch_name']."*"."\r\n\r\n";
                    $a .= 'Halo Bpk/Ibu *'.$trans['trans_contact_name'].'*, berikut Invoice yang kami kirimkan'."\r\n\r\n";
                    $a .= '*- Invoice:*'."\r\n";
                    $a .= $trans['trans_number']."\r\n";
                    $a .= date("d-F-Y, H:i", strtotime($trans['trans_date']))."\r\n"."\r\n";

                    $c .= $a;

                    //Deskripsi
                    $c .= '*- Deskripsi:*'."\r\n";
                    if(count($trans_item) > 0){
                        $c .= $b."\r\n";
                    }else{
                        $c .= '-'."\r\n";
                    }

                    $c .= "*- Total*"."\r\n";
                    // $c .= "Subtotal : Rp. ".number_format($trans['trans_total_dpp'],0)."\r\n";
                    // $c .= "Diskon : Rp. ".number_format($trans['trans_discount'],0)."\r\n";
                    // $c .= "Grand Total : *Rp. ".number_format($trans['trans_total'],0)."*"."\r\n"."\r\n";
                    
                    $c .= "Subtotal : Rp. ".number_format($trans['trans_total_dpp'],0)."\r\n";
                    if(!empty($trans['trans_voucher']) && $trans['trans_voucher'] > 0){
                        $c .= "Voucher : - Rp. ".number_format($trans['trans_voucher'],0)."\r\n";
                    }
                    if(!empty($trans['trans_discount']) && $trans['trans_discount'] > 0){
                        $c .= "Diskon : - Rp. ".number_format($trans['trans_discount'],0)."\r\n";                        
                    }
                    if((!empty($trans['trans_voucher'])) or (!empty($trans['trans_discount']))){
                        $c .= "Grand Total : Rp. ".number_format($trans['trans_total'],0)."\r\n";   
                    }   
                    // if(!empty($trans['trans_received']) && $trans['trans_received'] > 0){
                    //     $c .= "Dibayar : Rp. ".number_format($trans['trans_received'],0)."\r\n";                        
                    // }      
                    // if(!empty($trans['trans_change']) && $trans['trans_change'] > 0){
                    //     $c .= "Kembali : Rp. ".number_format($trans['trans_change'],0)."\r\n";
                    // }          
                    $c .= "\r\n";        

                    //Pembayaran
                    $c .= '*- Status Pembayaran:*'."\r\n";
                    if($trans['trans_paid'] == 1){
                        $c .= 'LUNAS'."\r\n";
                        // if($trans['trans_paid_type']==1){ //Tunai/Cash
                        //     $c .= 'Cash'."\r\n";
                        // }else if($trans['trans_paid_type']==2){ //Transfer
                        //     $c .= 'Transfer'."\r\n";
                        // }else if($trans['trans_paid_type']==3){ //EDC
                        //     $c .= 'EDC Card'."\r\n";
                        // }else if($trans['trans_paid_type']==4){ //Gratis
                        //     $c .= 'GRATIS'."\r\n";
                        // }else if($trans['trans_paid_type']==5){ //QRIS
                        //     $c .= 'QRIS'."\r\n";
                        // }
                        if($trans['trans_paid_type'] > 0){
                            $get_type_paid = $this->Type_model->get_type_paid($trans['trans_paid_type']);
                            $c .= $get_type_paid['paid_name']."\r\n";
                        }
                        // else{
                            // $c .= 'Belum Terbayar'."\r\n"; // Piutang
                        // }                        
                    }else{
                        $c .= 'Belum Terbayar'."\r\n";
                    }
                    
                    //Keterangan
                    $c .= "\r\n"."*- Keterangan:*"."\r\n";
                    if(!empty($trans['trans_note'])){
                        $c .= $trans['trans_note'].""."\r\n"."\r\n";   
                    }else{
                        $c .= '-'."\r\n\r\n";
                    }
                    
                    if(!empty($params['payment_url'])){
                        $c .= '*Link Pembayaran*'."\r\n";
                        $c .= $params['payment_url']."\r\n";
                        $c .= 'Anda dapat melakukan pembayaran melalui link diatas'."\r\n";
                    }
                    // $set_message .= 'Anda juga dapat mendownload invoice ini melalui link dibawah ini'."\r\n";
                    // $set_message .= 'https://minio.id/AuSDil'."\r\n\r\n";
                    $c .= "\r\n".'_Terimakasih atas kepercayaan anda_'."\r\n";
                    $text = $c;

                    $set_user_id = !empty($trans['trans_contact_id']) ? $trans['trans_contact_id'] : '';
                    $set_user_name = !empty($params['contact_name']) ? $params['contact_name'] : '';
                    $set_user_phone = !empty($params['contact_phone']) ? $params['contact_phone'] : '';                    
                }else{
                    $next = false;
                }
                break;      
            default:
                break;
        }
        if($next){
            // Prepare Message insert
            $session_group      = $this->random_code(20);
            $session_message    = $this->random_code(20);

            $params = array(
                'message_contact_id' => $set_user_id,
                'message_contact_name' => $set_user_name,
                'message_contact_number' => $set_user_phone,
                'message_text' => $text,
                'message_session' => $session_message,
                'message_group_session' => $session_group,
                // 'message_news_id' => $message_news,
                // 'message_url' => $get_news_url,
                'message_flag' => 0, 
                'message_date_created' => date("YmdHis"),
                'message_device_id' => !empty($params['device_id']) ? $params['device_id'] : null 
            );  
            $set_data=$this->Message_model->add_message($params);
            $message_id = $set_data;

            if(intval($message_id) > 0){
                $datas = $this->Message_model->get_message($message_id);
                if($datas==true){
                    if($send_now_or_later == 1){
                        return $this->whatsapp_send_group($session_group);
                    }else{
                        $r = array(
                            'status' => 1,
                            'message' => 'Sukses, Pesan akan di kirimkan melalui server sesuai jadwal',
                            'result' => []
                        );
                        return json_encode($r);
                    }
                }
            }
        }else{
            $r = array(
                'status' => 1,
                'message' => 'Pembatalan Pengiriman Pesan',
                'result' => []
            );
            return json_encode($r);            
        }
    }     
}