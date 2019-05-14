<?php
namespace common\components\telecom;

class OneSms 
{
	public $username = 'NCTELECOM';
	public $password = '123456';
	public $secret_key = '4E4354454C45434F4D313233343534366';
	// public $server = 'http://210.211.108.27';
	public $server = 'http://210.211.108.20:9999/onsmsapi/sendsms.jsp';
	public $caller = '0901800026';

	public function sms($phone, $text)
	{
		$smsData = [
			"username" => $this->username,
			"pass" => $this->password,
			"key" => $this->secret_key,
			"phonesend" => $phone,
			"smsid" => '22221',
			"param" => $phone . "__" . $text,
			"sender" => $this->caller,
		];

		$url = $this->server . "?" . http_build_query($smsData);
		$ch = curl_init(); 
	    curl_setopt($ch, CURLOPT_URL, $url); 
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	    $returnValue = curl_exec($ch);
	    curl_close($ch); 

	    $errorList = $this->getErrorList();

	    $returnValue = trim($returnValue . "");
	    return [
	    	'status' => $returnValue == "1",
	    	'message' => array_key_exists($returnValue, $errorList) ? $errorList[$returnValue] : 'Lỗi khác'
	    ];
	}

	protected function getErrorList()
	{
		return [
	    	"1" => 'Thành công',
	    	"-1" => 'Sai tên user hoặc mật khẩu',
	    	"-2" => 'IP không được phép',
	    	"-3" => 'Template chưa đăng ký',
	    	"-4" => 'Sai số điện thoại',
	    	"-5" => 'Lôi hệ thống',
	    	"-6" => 'Lôi hệ thống',
	    	"-7" => 'Số MT gửi cho 1 số điện thoại vượt giới hạn trong ngày',
	    	"-8" => 'Tài khoản không đủ tiền',
	    	"-9" => 'Lôi hệ thống',
	    	"7"  => 'Hết số lượng tin nhắn gửi test',
	    	"10" => 'Sai tài khoản',
	    	"11" => 'Sai mật khẩu',
	    	"12" => 'Sai số điện thoại',
	    	"13" => 'Nội dung gửi bị lỗi',
	    	"14" => 'Sai IP',
	    	"15" => 'Sai mã Code',
	    	"16" => 'Invalid message name',
	    	"17" => 'Lôi hệ thống',
	    	"18" => 'SMS vượt 1000 ký tự',
	    	"0" => 'Lỗi khác',
	    ];
	}
}