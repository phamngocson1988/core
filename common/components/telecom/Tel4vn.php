<?php 
namespace common\components\telecom;

use Yii;

class Tel4vn 
{
	public $server = "https://ncapi.tel4vn.com/api/v1/autocall"; //"https://api.tel4vn.com/api/v1/autocall";
	// public $caller_id_number = '02873001968';
	// public $destination = '1002';
	// public $domain;

	/** @var Dialer **/
	protected $_setting;
	protected $_params = [];

	public function call($phone) 
	{
		if (!$this->_setting) throw new Exception("Bạn chưa nhập bộ số", 1);
		
		// $param = array(
		// 	'extension' => $phone,
		// 	'caller_id_number' => $this->caller_id_number,
		// 	'destination' => $this->destination,
		// 	'domain' => $_SERVER['SERVER_NAME']
		// );
		$param = array(
			'extension' => $phone,
			'caller_id_number' => $this->_setting->number,
			'destination' => $this->_setting->extend,
			'domain' => $this->_setting->domain
		);
		Yii::debug($this->server);
		Yii::debug($param);
		$ch = curl_init($this->server);
		$payload = json_encode( $param );
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec($ch);
		curl_close($ch);
		$this->_params = $param;
		return $result;
	}

	public function setSetting($setting)
	{
		$this->_setting = $setting;
	}

	public function getParams()
	{
		return $this->_params;
	}
}