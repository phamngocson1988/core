<?php 
namespace common\components\telecom;

use Yii;
use yii\helpers\Url;
use yii\helpers\FileHelper;

class Tel4vn 
{
	 public $server = "http://ncapi.tel4vn.com/api/v1/autocall";


	//public $server = "https://api.tel4vn.com/api/v1/autocall";
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

	private static function execConvertWav($mp3File, $wavFile)
	{
		$temp = dirname($mp3File) . '/' . basename($mp3File, ".mp3") . "-temp.wav";
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
		$output = self::getAudioData($content);
		$userId = Yii::$app->user->id;
		$mp3 = Yii::getAlias("@backend/web/files/wav/$userId/$phone.mp3");
		$wav = Yii::getAlias("@backend/web/files/wav/$userId/$phone.wav");
		@unlink($mp3);
		@unlink($wav);
		if (!file_exists(dirname($mp3))) FileHelper::createDirectory(dirname($mp3)); 
	    $fp = fopen( $mp3, 'ab' );
	    fwrite( $fp, $output );
	    fclose( $fp );
	    self::execConvertWav($mp3, $wav);
		@unlink($mp3);
	}

	private static function getAudioData($message)
	{
		$params = [];
		$params['t'] = $message;
		$params['tl'] = 'vi';
		$params['pitch'] = '0.5';
		$params['rate'] = '0.5';
		$params['vol'] = '1';
		$params['sv'] = '';
		$params['vn'] = '';	
		$enable_jsonp    = false;
		$enable_native   = true;
		$valid_url_regex = '/.*/';
		// ############################################################################
		//$url = $_GET['url'];
		$qt = urlencode($params['t']);
		$ql = urlencode($params['tl']);
		$qv = urlencode($params['sv']);
		$qn = urlencode($params['vn']);
		$pitch = urlencode($params['pitch']);
		$rate = urlencode($params['rate']);
		$vol = urlencode($params['vol']);
		//die($qt);
		if ( empty($qv) ) {
		//$url = ('https://translate.google.com/translate_tts?ie=UTF-8&q=' . ($qt) . '&tl=' . $ql);
		$url = ('https://www.google.com/speech-api/v1/synthesize?ie=UTF-8&text=' . ($qt) . '&lang=' . $ql . '&pitch=' . $pitch . '&speed=' . $rate . '&vol=' . $vol);
		//die($url);
		} elseif ($qv == "g1") {
		$url = ('https://www.google.com/speech-api/v1/synthesize?ie=UTF-8&text=' . ($qt) . '&lang=' . $ql . '&name=' . $qn . '&pitch=' . $pitch . '&speed=' . $rate . '&vol=' . $vol);
		} elseif ($qv == "tts-api") {
		$url = ('http://tts-api.com/tts.mp3?q=' . ($qt) );
		}
		if ( !$url ) {
		
		// Passed url not specified.
		$contents = 'ERROR: url not specified';
		$status = array( 'http_code' => 'ERROR' );
		
		} else if ( !preg_match( $valid_url_regex, $url ) ) {
		
		// Passed url doesn't match $valid_url_regex.
		$contents = 'ERROR: invalid url';
		$status = array( 'http_code' => 'ERROR' );
		
		} else {
		$ch = curl_init( $url );
		
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
		curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
		
		list( $header, $contents ) = preg_split( '/([\r\n][\r\n])\\1/', curl_exec( $ch ), 2 );
		
		$status = curl_getinfo( $ch );
		
		curl_close( $ch );
		}
		// Split header text into an array.
		$header_text = preg_split( '/[\r\n]+/', $header );
		if ( !$enable_native ) {
			$contents = 'ERROR: invalid mode';
			$status = array( 'http_code' => 'ERROR' );
		}
		
		// Propagate headers to response.
		foreach ( $header_text as $header ) {
			if ( preg_match( '/^(?:Content-Type|Content-Language|Set-Cookie):/i', $header ) ) {
			header( $header );
			}
		}
		return $contents;
	}
}