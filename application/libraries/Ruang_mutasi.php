<?php

class Ruang_mutasi{
	private $api_url = 'https://www.ruangmutasi.com/api';
	private $api_token = 'FrPOZy2o7HQt5n3hLabe6wYE01MIAsXp98WuBkCc';
	private $serverIpAddress = '20.198.224.194';

	public function __construct(){

	}

	/*
		Get user information
	*/

	public function user_info(){
		return $this->post('/user_info');
	}

	/*
		Get list account
	*/

	public function accounts_list(){
		return $this->post('/accounts_list');
	}

	/*
		Get account information

		| Param | Type | Required | Description |
		| --- | --- | --- | --- |
		| account_id | integer | yes | id akun anda |
	*/

	public function account($account_id){
		$data = [
			'account_id'=>$account_id
		];
		return $this->post('/account',$data);
	}

	/*
		Get list transaction
		
		| Param | Type | Required | Description |
		| --- | --- | --- | --- |
		| account_id | integer | no | id akun anda |
		| from | date | yes | tanggal awal (Y-m-d) |
		| to | date | yes | tanggal awal (Y-m-d) |
	*/

	public function transactions($account_id, $from, $to){
		$data = [
			'account_id'=>$account_id,
			'from'=>$from,
			'to'=>$to,
		];
		return $this->post('/transactions',$data);
	}

	/*
		Search transaction by amount
		| Param | Type | Required | Description |
		| --- | --- | --- | --- |
		| account_id | integer | no | id akun anda |
		| from | date | yes | tanggal awal (Y-m-d) |
		| to | date | yes | tanggal awal (Y-m-d) |
		| nominal | double | yes | Nominal yang anda cari |
		| type | enum(C\|D) | no | Tipe transaksi Credit (C), atau Debet (D) |
	*/

	public function search_amount($account_id, $from, $to, $nominal, $type){
		$data = [
			'account_id'=>$account_id,
			'from'=>$from,
			'to'=>$to,
			'nominal'=>$nominal,
			'type'=>$type,
		];
		return $this->post('/search_amount',$data);
	}

	/*
		Retrieve Callback data
		Response is different from each callback type.
		Please use our sandbox menu to make your development more easier 
	*/

	public function callback(){
		$response['status']=false;
		$data = json_decode(file_get_contents('php://input'));

		if($this->get_client_ip()!=$this->serverIpAddress){
			$response['msg']='ip not valid';
		}elseif(!empty($data)){
			$response['status']=true;
			$response['data']=$data;
		}elseif(isset($_POST) && !empty($_POST)){
			$response['status']=true;
			$response['data']=$_POST;
		}else{
			$response['msg']='unknown request';
		}
		return $response;
	}

	private function post($uri, $data=[]){
		$post = http_build_query($data);
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->api_url.$uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_HTTPHEADER => array(
			    'Authorization: Bearer '.$this->api_token
			),
			CURLOPT_POSTFIELDS => $post,
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return json_decode($response);
	}

	private function get_client_ip() {
	    $ipaddress = '';
	    if (isset($_SERVER['HTTP_CLIENT_IP']))
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_X_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	    else if(isset($_SERVER['HTTP_FORWARDED']))
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	    else if(isset($_SERVER['REMOTE_ADDR']))
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}

}