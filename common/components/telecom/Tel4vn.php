<?php 
namespace common\components\telecom;

class Tel4vn 
{
	public $server = "https://api.tel4vn.com/api/v1/autocall";
	public $caller_id_number = '02873001968';
	public $destination = '1002';
	public $domain;

	function call($phone) 
	{
		$param = array(
			'extension' => $phone,
			'caller_id_number' => $this->caller_id_number,
			'destination' => $this->destination,
			'domain' => $_SERVER['SERVER_NAME']
		);

		$ch = curl_init($this->server);
		$payload = json_encode( $param );
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}