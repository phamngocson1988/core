<?php 
namespace common\components\telecom;

use Yii;
use yii\helpers\Url;

class Tel4vn 
{
	// public $server = "https://ncapi.tel4vn.com/api/v1/autocall";
	public $server = "https://api.tel4vn.com/api/v1/autocall";
	// public $caller_id_number = '02873001968';
	// public $destination = '1002';
	// public $domain;

	/** @var Dialer **/
	protected $_setting;
	protected $_params = [];

	public function call($phone, $content) 
	{
		if (!$this->_setting) throw new Exception("Bạn chưa nhập bộ số", 1);
		
		// $param = array(
		// 	'extension' => $phone,
		// 	'caller_id_number' => $this->caller_id_number,
		// 	'destination' => $this->destination,
		// 	'domain' => $_SERVER['SERVER_NAME']
		// );
		self::downloadMP3($phone, $content);
		$param = array(
			'extension' => $phone,
			'caller_id_number' => $this->_setting->number,
			'destination' => $this->_setting->extend . Yii::$app->user->id,
			'domain' => $this->_setting->domain,
			// 'audio' => 'http://quanly.tudongdoanhnghiep.com/backend/web/files/wav/$id-user/[tenfile]'
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

	private static function generateAudio($content)
	{
		$params = [];
		$params['t'] = $content;
		$params['tl'] = 'vi';
		$params['pitch'] = '0.5';
		$params['rate'] = '0.5';
		$params['vol'] = '1';
		$params['sv'] = '';
		$params['vn'] = '';	
		return Url::to(array_merge(['contact/get-voice'], $params), true);
		// return sprintf("%s?%s", 'http://localhost:8080/docchu/getvoice.php', http_build_query($params));
	}

	private static function execConvertWav($mp3File, $wavFile)
	{
		$temp = dirname($mp3File) . '/' . basename($mp3File, ".mp3") . ".wav";
		$decode = "lame --decode $mp3File $temp";
		exec($decode);
		$cmd = "sox $temp -r 8000 -c  1 $wavFile";
		exec($cmd);
		$delete = "rm $temp";
		exec($delete);
	} 

	/**
	 * @param $audios array of urls
	 * @param $file mp3 file name
	 */
	private static function downloadMP3($phone, $content)
	{    
		$url = self::generateAudio($content);
		$userId = Yii::$app->user->id;
		$mp3 = Yii::getAlias("@backend/web/files/wav/$userId/$phone.mp3");
		$wav = Yii::getAlias("@backend/web/files/wav/$userId/$phone.wav");
		$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		$curl = curl_init();
	    curl_setopt( $curl, CURLOPT_URL, $url );
	    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 0 );
		curl_setopt( $curl, CURLOPT_USERAGENT, $agent);
	    $output = curl_exec( $curl );    
	    curl_close( $curl );
	    $fp = fopen( $mp3, 'ab' );
	    fwrite( $fp, $output );
	    fclose( $fp );
	    // self::execConvertWav($mp3, $wav);
		// @unlink($mp3);
	}
}