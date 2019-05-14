<?php 
namespace common\components\telecom;

class TeleApi 
{
	protected $provider;

	private $_errors;

	public function __construct($provider)
	{
		$this->provider = $provider;
	}

	public function setProvider($provider)
	{
		$this->provider = $provider;
	}

	public function sms($phone, $content)
	{
		return $this->provider->sms($phone, $content);
	}

	public function call($action = 'call')
	{
		if (!$this->validate()) return false;
		$params = [];
		$params['text'] = $this->content;
		$params['phone'] = $this->phone;
		$params['extension'] = '1002';
		$params['caller_number'] = '02873001968';
		$params['domain'] = $_SERVER['SERVER_NAME'];
		$params['action'] = $action;//call,sms
		$params['audio'][] = $this->generateAudio();

		$ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $this->getCallingServiceUrl()); 
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_POST, TRUE);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params)); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        $returnValue = curl_exec($ch);
        curl_close($ch); 
        return $returnValue;
	}

	private function validate()
	{
		if ($this->validateEmpty($this->phone)) {
			$this->addError('phone', 'Số điện thoại không được rỗng.');
			return false;
		}
		if ($this->validateEmpty($this->content)) {
			$this->addError('phone', 'Nội dung không được rỗng.');
			return false;
		}
		if (!$this->validateRegex("/^\d+$/", $this->phone)) {
			$this->addError('phone', 'Số điện thoại phải bao gồm những chữ số.');
			return false;
		}
		return true;
	}

	private function validateEmpty($value)
	{
		$value = trim($value);
		return !$value;
	}

	private function validateRegex($regex, $value)
	{
		return preg_match($regex, $value);
	}

	private function addError($key, $message)
	{
		$this->_errors[$key] = $message;
	}

	private function getError()
	{
		return (array)$this->_errors;
	}

	private function generateAudio()
	{
		$params = [];
		$params['t'] = $this->content;
		$params['tl'] = 'vi';
		$params['pitch'] = '0.5';
		$params['rate'] = '0.5';
		$params['vol'] = '1';
		$params['sv'] = '';
		$params['vn'] = '';	
		return sprintf("%s/%s?%s", $this->_server, $this->_audio_service, http_build_query($params));
	}

	private function getCallingServiceUrl()
	{
		return sprintf("%s/%s", $this->_server, $this->_call_service);
	}
}