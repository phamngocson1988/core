<?php
namespace common\components\telecom;

use yii\base\Model;

class SpeedSms extends Model
{
    protected $_server = 'https://api.speedsms.vn/index.php';
    protected $_create_pin_api = 'pin/create';
    protected $_verify_pin_api = 'pin/verify';
    public $app_id = 'av7epBIG2PBvqV8lVKmKmHO013AboHMl';
    public $access_token = 'SS68YQPfsPhxKcWTmne6j7Urofzy0rsc';

	public function sms($phone)
	{
		$smsData = [
			"to" => $phone,
			"content" => "Your verification code is: {pin_code}",
			"app_id" => $this->app_id,
        ];
        
        $url = $this->_server . "/" . $this->_create_pin_api;
        $headers = ["Content-Type: application/json"];
		$ch = curl_init(); 
	    curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $smsData); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->access_token);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $returnValue = curl_exec($ch);
        // { "status": "success", "code": "00", "data": { "pin_code": "generated pin code", "tranId": "transaction id", "totalPrice": total price number } }
        // { "status": "error", "code": "error code", "message": "error description" }
	    curl_close($ch); 
        $response = json_decode($returnValue, true);
        if ($response['status'] == 'error') {
            $this->addError($phone, $response['message']);
            return false;
        } 
        return true;
    }
    
    protected function verify($phone, $pin)
    {
        $smsData = [
			"phone" => $phone,
			"pin_code" => $pin,
			"app_id" => $this->app_id,
        ];
        
        $url = $this->_server . "/" . $this->_verify_pin_api;
        $headers = ["Content-Type: application/json"];
		$ch = curl_init(); 
	    curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $smsData); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->access_token);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $returnValue = curl_exec($ch);
        // { "status": "success", "data": { "pin": "Mã pin mà người dùng đã nhập", "phone": "số điện thoại của người dùng", "verified": true/fale, "remainingAttempts": số lần được phép nhập lại mã pin nếu nhập sai trước đó } }
        // { "status": "error", "code": "error code", "message": "error description" }
	    curl_close($ch); 
        $response = json_decode($returnValue, true);
        if ($response['status'] == 'error') {
            $this->addError($phone, $response['message']);
            return false;
        }
        return $response['verified'];
    }

	protected function getErrorList()
	{
		return [
	    	"007" => 'Thành công',
	    	"008" => 'Sai tên user hoặc mật khẩu',
	    	"009" => 'IP không được phép',
	    	"101" => 'Template chưa đăng ký',
	    	"105" => 'Sai số điện thoại',
	    	"300" => 'Lôi hệ thống',
	    	"500" => 'Lôi hệ thống',
	    ];
	}
}